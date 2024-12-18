<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if($mode=='seller_connect'){
               
         $parent_id = $_REQUEST['thread_id'];
         $customer_id = $_REQUEST['customer_id'];
         $merchant_id = $_SESSION['auth']['company_id'];
         $subject = addslashes($_REQUEST['topic']) ;
         $message = addslashes($_REQUEST['merchant_reply']) ;
         $topic = $_REQUEST['topic_id'];
         $timestamp = date(time());
         $open_timestamp = 0; //Update when user opens the message
         $direction = 'M2C';
         $product_id = $_REQUEST['product_id'];
         
         $topic_name = db_get_row("select name from clues_issues where issue_id=".$topic."");
     
         if(preg_match("/\d(?:[-\s]?\d){7,12}/",$message)){
            
             fn_set_notification('E', fn_get_lang_var('Error'), fn_get_lang_var('number_is_not_allowed'),'I');
             
             return array(CONTROLLER_STATUS_REDIRECT, "merchant_messages.seller_connect_reply&thread_id=".$_REQUEST['thread_id']);
      
             return false;
             
         }else if(preg_match("/([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})/",$message)){
            
             fn_set_notification('E', fn_get_lang_var('Error'), fn_get_lang_var('email_is_not_allowed'),'I');
             
             return array(CONTROLLER_STATUS_REDIRECT, "merchant_messages.seller_connect_reply&thread_id=".$_REQUEST['thread_id']);
      
             return false;
             
         }else if(preg_match("/(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?/",$message)){
             
             fn_set_notification('E', fn_get_lang_var('Error'), fn_get_lang_var('url_is_not_allowed'),'I');
             
             return array(CONTROLLER_STATUS_REDIRECT, "merchant_messages.seller_connect_reply&thread_id=".$_REQUEST['thread_id']);
      
             return false;
             
             
         }
         
         $thread_id = fn_seller_connect($parent_id,$customer_id,$merchant_id,$product_id,$subject,$message,$topic,$timestamp,$open_timestamp,$direction);
      
         fn_set_notification('N', fn_get_lang_var('notice'), fn_get_lang_var('mail_sent_successfully'),'I');
      
          
           $url = Registry::get('config.current_location')."/"."index.php?dispatch=profiles.user_query_response&thread_id=".$parent_id;
          
           Registry::get('view_mail')->assign('subject', $subject);
           Registry::get('view_mail')->assign('message',$message);
           Registry::get('view_mail')->assign('url',$url);
           Registry::get('view_mail')->assign('merchant_name',$_REQUEST['merchant_name']);
           Registry::get('view_mail')->assign('date_time',date("m-d-Y H:i a")); 
           Registry::get('view_mail')->assign('topic_name',$topic_name['name']);
           //Registry::get('view_mail')->assign('product_name',$_REQUEST['product_name']);
           
            
           // For customer email $_REQUEST['customer_email']
            
           if($_REQUEST['mode']=='debug'){
              
              $to_email = 'raj.singh@shopclues.com';
          } else {
              
              $to_email = $_REQUEST['customer_email'];
          }
          
              fn_instant_mail($to_email, Registry::get('settings.Company.company_support_department'),'product/seller_connect_to_user_subj.tpl','product/seller_connect_to_user.tpl');
          
               $url = "merchant_messages.seller_connect_reply&thread_id=".$parent_id;
     
               return array(CONTROLLER_STATUS_REDIRECT, $url);
     
         // To send mail to user your query resolved
    }
    
}


if($mode=='messages'){
    
    if(isset($_REQUEST['order']) && $_REQUEST['order']!=''){
              $order = ($_REQUEST['order']=='desc')?'asc':'desc';
    } else{
         $order= 'desc';
    }
  
    if(isset($_REQUEST['field']) && $_REQUEST['field'] == 'date'){
              $order_by = 1;
             
    }elseif (isset($_REQUEST['field']) && $_REQUEST['field'] == 'subject'){
              $order_by= 2;
              
    }elseif (isset($_REQUEST['field']) && $_REQUEST['field'] == 'message'){
              $order_by= 3;
              
    }else{
        $order_by='';
    }
    
    if(isset($_REQUEST['m_id']) && !empty($_REQUEST['m_id'])){
            $m_id = $_REQUEST['m_id'];        
    }else{  
            $m_id = '';
    }
  
    $message_data = merchant_message_alert($_SESSION['auth']['user_id'],$m_id,$_SESSION['auth']['company_id'],'',$order,$order_by);
    $view->assign('message_data',$message_data);
    
    $count_values = fn_mail_category_count($_SESSION['auth']['user_id'],$_SESSION['auth']['company_id']);
    $view->assign('mail_per_category_count',$count_values);
    
    $view->assign('order',$order);
    
    
    
}elseif ($mode=='messages_detail'){
    
    
    // Update mail_opened by merchant
    
    $status = fn_update_mail_opened('Y',$_SESSION['auth']['user_id'],$_REQUEST['message_id']);
    $mail_data = fn_mail_message_functionality($_REQUEST['message_id']);
    
    
    $view->assign('mail_data',$mail_data);
    $view->assign('attachment',explode(',',ltrim($mail_data['attachment'],',')));
    
}elseif ($mode=='seller_connect'){
   
    if(isset($_REQUEST['order']) && $_REQUEST['order']!=''){
              $order = ($_REQUEST['order']=='desc')?'asc':'desc';
    } else {
         $order= 'desc';
    }
             
    if(isset($_REQUEST['field']) && $_REQUEST['field'] == 'date'){
              $order_by = 'sc.timestamp';
             
    }elseif (isset($_REQUEST['field']) && $_REQUEST['field'] == 'subject'){
              $order_by= 'sc.subject';
              
    }elseif (isset($_REQUEST['field']) && $_REQUEST['field'] == 'message'){
              $order_by= 'sc.message';
              
    }elseif (isset($_REQUEST['field']) && $_REQUEST['field'] == 'from'){
              $order_by= 'u.firstname';
        
    }else{
            $order_by='sc.timestamp';
    }
 
    
    $messages_data = "select sc.thread_id,sc.subject,ci.name,u.firstname,u.lastname ,sc.timestamp,sc.open_timestamp from clues_seller_connect sc,clues_issues ci,cscart_users u where 
                                    sc.merchant_id=".$_SESSION['auth']['company_id']." and u.user_id = sc.customer_id and sc.parent_id=0 and ci.issue_id=sc.topic ";
   
    if(isset($_REQUEST['cat_id'])){
        $topic_id = $_REQUEST['cat_id'];
        
        $messages_data .= "and sc.topic=".$topic_id;
    }  
    
    $messages_data .= " order by ".$order_by." ".$order;
    
    $messages_data = db_get_array($messages_data);
    
    
    $message_count = db_get_array("SELECT ci.name as topic_name,sc.topic,count(sc.topic) as total_message  FROM clues_seller_connect sc, clues_issues ci 
                                                                where sc.merchant_id = ".$_SESSION['auth']['company_id']."  and sc.parent_id=0 and sc.topic=ci.issue_id group by 2");
    
    $view->assign('message_data',$messages_data);
    
    $view->assign('message_count',$message_count);
    
    $view->assign('order',$order);
    
}elseif ($mode=='seller_connect_reply'){
    
    //controller for message reply
    
    //Parent thread
    
    $message_thread= db_get_row("select sc.subject,sc.product_id,sc.message,sc.open_timestamp,sc.timestamp,sc.topic,ci.name,sc.customer_id,u.firstname,u.lastname,u.email from clues_seller_connect sc, cscart_users u,clues_issues ci where sc.thread_id=".$_REQUEST['thread_id']." and u.user_id = sc.customer_id and sc.topic=ci.issue_id and sc.merchant_id=".$_SESSION['auth']['company_id']."");
   
    
    if($message_thread['product_id']!=0){
        
        $product_name = db_get_row("select product from cscart_product_descriptions where product_id=".$message_thread['product_id']);
        
        $view->assign('product_complete_name',$product_name['product']);
        }
    // All child threads
    
    $messages_reply = db_get_array("select sc.message,sc.parent_id,sc.open_timestamp,sc.timestamp,sc.direction,u.firstname,u.lastname,c.company from clues_seller_connect sc,cscart_users u,cscart_companies c where
                                    sc.parent_id=".$_REQUEST['thread_id']." and sc.merchant_id=".$_SESSION['auth']['company_id']." and u.company_id=sc.merchant_id and c.company_id=sc.merchant_id");
   
    
    $view->assign('message_thread',$message_thread);
    $view->assign('message',$messages_reply);
    $view->assign('topic',$message_thread['name']);
    $view->assign('topic_id',$message_thread['topic']);
    
    // update open timestamp if it is not updated
    
    if($message_thread['open_timestamp']==0){
        
        fn_update_thread_timestamp($_REQUEST['thread_id'],date(time()));
        
    }
    
    $view->assign('current_timestamp',date(time()));
       
}

?>