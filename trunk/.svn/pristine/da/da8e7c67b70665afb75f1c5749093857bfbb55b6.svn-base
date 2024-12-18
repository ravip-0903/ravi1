<?php
 
if(!defined('AREA') ) { die('Access denied'); }

//generating the menu for issues and the subissues by arpit gaur
$issues = select_parent_issues(); 

$view->assign('parent_issues',$issues);
//code by arpit gaur ends here

//code by arpit gaur to prepopulate the fields
$view->assign('prepopulate_email',$_REQUEST['email']);
$view->assign('prepopulate_orderid',$_REQUEST['orderid']);
$view->assign('prepopulate_name',$_REQUEST['name']);
$view->assign('prepopulate_phone',$_REQUEST['phone']);
//code by arpit gaur ends here

if ($_SERVER['REQUEST_METHOD'] == 'POST' && (isset($_SESSION['form_token_value']) && $_REQUEST['token'] != $_SESSION['form_token_value']) && !isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('form_token_not_matched'));
    return array(CONTROLLER_STATUS_OK, $_SERVER['HTTP_REFERER']);
}else{
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
        $token = md5(Registry::get('config.http_host').Registry::get('config.session_salt').time());
        $_SESSION['form_token_value'] = $token;
    }
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if($mode == 'write' || $mode == 'add') {
		   //echo"<pre>";print_r($_REQUEST);die;
		   $cust_q_order_id = $_REQUEST['orderid'];
		   $mydate = time();
		   $qrydate = date("Y-m-d H:i:s", $mydate);
		   $issue_id = $_REQUEST['subject'];
		   
			if (isset($_REQUEST['subject']) && $_REQUEST['subject'] != '' ) {
			$subject = db_get_field("select name from clues_issues where parent_issue_id='" . $_REQUEST['subject'] ."'");
			}
			
			if (isset($_REQUEST['subissues']) && $_REQUEST['subissues'] != '' ) {
			$subissues = db_get_field("select name from clues_issues where issue_id='" . $_REQUEST['subissues'] ."'");
			}
			
			if (isset($_REQUEST['sub_subissues']) && $_REQUEST['sub_subissues'] !='' ) {
				$sub_subissues = db_get_field("select name from clues_issues where issue_id='" . $_REQUEST['sub_subissues'] ."'");
				$subissues = $sub_subissues;
			}
			
			if($subissues == ''){
				fn_set_notification('E', '', 'OOPS!! There was some error collecting your request !! Please try again !!');
				
				if($_REQUEST['query_from'] =="add"){
					return array(CONTROLLER_STATUS_OK, "write_to_us.add&x=".$mydate);
					}else{
					return array(CONTROLLER_STATUS_OK, "write_to_us.write");
			   }
			}
			
			$cust_q_service_type = $subject . '-' . $subissues;

			$cust_q_customer_name = addslashes($_REQUEST['name']);
			if ($cust_q_customer_name == '' || $cust_q_customer_name == null)
				$cust_q_customer_name = ' ';
			$cust_q_customer_contact = addslashes($_REQUEST['phone']);
			$cust_q_customer_email = addslashes($_REQUEST['email']);
			if ($cust_q_customer_contact == '' || $cust_q_customer_contact == null)
				$cust_q_customer_contact = ' ';

			if ($cust_q_remarks == '' || $cust_q_remarks == null)
				$cust_q_remarks = ' ';
			$cust_q_message = addslashes($_REQUEST['message']);
			$cust_q_executive_id = $_SESSION['auth']['user_id'];
			$cust_q_status = db_get_field("select status from cscart_orders where order_id='". $cust_q_order_id ."' ");
			if ($cust_q_status == '' || $cust_q_status == null)
				$cust_q_status = ' ';
			//validating captcha value
			if( $_REQUEST['query_from'] =="add" && $_REQUEST['user_captcha'] != $_REQUEST['real_captcha'])//captcha verification failed
			{
				fn_set_notification('E', '','Please enter the correct numbers in the text box !!');
			}
			else//captcha verification was successful
			{
			
				////$NewIssueField="";$ZDAssignGroup = "",$ZDAssignee=""; 
				//editing the request parameters
			
				if(isset($_REQUEST['sub_subissues']) && $_REQUEST['sub_subissues']!='' )
				{
					$NewIssueField=db_get_zendesk_code($_REQUEST['sub_subissues']);
					$ZDAssignee=db_get_assignee($_REQUEST['sub_subissues']);
					$ZDAssignGroup=db_get_group($_REQUEST['sub_subissues']);
				}
				elseif(isset($_REQUEST['subissues']) && $_REQUEST['subissues'])
				{
					$NewIssueField=db_get_zendesk_code($_REQUEST['subissues']);
					$ZDAssignee=db_get_assignee($_REQUEST['subissues']);
					$ZDAssignGroup=db_get_group($_REQUEST['subissues']);
				}
				else
				{
					$NewIssueField=db_get_zendesk_code($_REQUEST['subject']);
					$ZDAssignee=db_get_assignee($_REQUEST['subject']);
					$ZDAssignGroup=db_get_group($_REQUEST['subject']);
				}
				
				//echo $NewIssueField."\n".$ZDAssignee."\n".$ZDAssignGroup;
				 //Item info
				  $items=db_get_array("select product_id, price,amount,extra from cscart_order_details where order_id='".$cust_q_order_id."'");
				  foreach($items as $k=>$item){
					  $items[$k]['extra'] = unserialize($item['extra']);	
				  }
				  $products['item'] = $items;
				  $prod_id = $products['item'][0]['product_id'];
				  $product = $products['item'][0]['extra']['product'];
				  $cpny_id = $products['item'][0]['extra']['company_id'];
				  $cpny_name = db_get_field("select company from cscart_companies where company_id='".$cpny_id."'");
				  $prod_meta_cat = db_get_field(" SELECT ccd.category
												  FROM cscart_products_categories cpc
												  INNER JOIN cscart_category_descriptions ccd ON cpc.category_id = ccd.category_id
												  WHERE cpc.product_id ='".$prod_id."'");
		        //End Item info
				//shipment info
                $shipment=db_get_row("SELECT cst.order_id, cst.shipment_id, cs.tracking_number, cs.carrier,
                                 Date_format(Date(from_unixtime(cs.timestamp)),'%d-%M-%Y') as date
                                 FROM `cscart_shipment_items` cst
                                 INNER JOIN cscart_shipments cs ON cs.shipment_id = cst.shipment_id
                                 WHERE cst.order_id ='".$cust_q_order_id."'");
                                 
                $courier = $shipment['carrier'];  
				$tracking_number = $shipment['tracking_number']; 
				$shipment_date = $shipment['date'];                
                                 
              // End shipment info  
              
				//creating the zendesk ticket using the zendesk api
				require(DIR_ROOT.'/contact_us_api_calls_files/ticket_create.php');
				//this is whre we add the notification for the user after the form is submitted
				
                fn_set_notification('N', '', "Thank you for writing to us. An email has been sent to our support team with a copy to you on <b>".$_REQUEST['email']." </b> .
                                     We try our best to respond to you within 48 hours. 
                                     your request number is <b>".$_GET['GIT_GENERATED_TICKET_ID']."</b>.");
                                     
               $cust_q_query = "INSERT INTO clues_customer_queries (order_id, user_id, customer_name, customer_contact, customer_email, date, service_type, customer_comments, ticket_number, status, issue_id, ticket_channel, `group`, assignee) VALUES ('" . $cust_q_order_id . "', '" . $cust_q_executive_id . "', '" . $cust_q_customer_name . "', '" . $cust_q_customer_contact . "', '" . $cust_q_customer_email . "', '" . $qrydate . "', '" . $cust_q_service_type . "', '" . $cust_q_message . "', '" . $reqt_no . "', '" . $cust_q_status . "', '" . $issue_id . "', '" . $ticket_channel . "', '" . $group . "', '" . $assignee . "')";
               db_query($cust_q_query);                     
              }                  
			
			  if($_REQUEST['query_from'] =="add"){
					return array(CONTROLLER_STATUS_OK, "write_to_us.add&x=".$mydate);
					}else{
					return array(CONTROLLER_STATUS_OK, "write_to_us.write");
			   }
	}	
}

//Added service_center details by Shashi Kant
elseif($mode == 'service_center')
{
    $brand=db_get_array("select  vd.variant as variation,fd.description as feature,vd.variant_id as variant_id,c.brand_url as url,c.brand_id as brand_id
                           from cscart_product_feature_variants fv 
                           inner join cscart_product_features_descriptions fd on fv.feature_id = fd.feature_id
                           inner join cscart_product_feature_variant_descriptions vd on
                           vd.variant_id=fv.variant_id
                           inner join clues_city_address as c on c.brand_id= vd.variant_id
                           where fd.feature_id in(53) and c.status='A'
                           group by 1
                           ORDER BY variation asc");
    $view->assign('brands', $brand);
    
}
elseif($mode == 'get_city'){
    if(isset($_GET['brand_id']) && $_GET['brand_id'] != '')
    {     
         $city_name = db_get_array("select distinct city_name,brand_id,brand_url from clues_city_address where brand_id =".$_GET['brand_id']." group by 1");

         if(isset($city_name)){
             
             $city='<option value="">--Select--</option>';
             for($i=0;$i<count($city_name);$i++){
                 if($city_name[$i]['city_name']){
                     $city.='<option title="'.$city_name['brand_id'][$i].'" value="'.$city_name[$i]['city_name'].'">'.$city_name[$i]['city_name'].'</option>';
                 }else{
                     
                     $url.="<div style='float:left; width:100%; padding-bottom:0px; font:bold 15px arial; color:#636566;';>".fn_get_lang_var('service_center_url')."</div><a href='".$city_name[$i]['brand_url']."' target='_blank'>".$city_name[$i]['brand_url']."</a>";
                 }   
                 
             }
             
             $res=array();
             $res['city_val']=$city;
             $res['url']=$url;
}
$str=implode('~',$res);
echo $str;exit;
}
}
elseif($mode == 'city_add'){
 if(isset($_GET['city_name']) && $_GET['city_name'] != ''&&isset($_GET['brand2']))
{
 $address = db_get_array("select brand_id,brand_url as url,city_name,state,pin,status,address1,address2 from clues_city_address
                           where city_name='".$_GET['city_name']."' and brand_id=".$_GET['brand2']." ORDER BY city_name asc");
//print_r($address); die;

 if($address)
 {      
     $html .="<div style='float:left; width:100%; font:bold 15px arial; padding-top:10px; color:#636566;';>".fn_get_lang_var('service_center_address')."</div>";
     for($i=0; $i<count($address); $i++){
         $html .='<div style="float:left; padding:10px; border:2px solid #f5f5f5; margin-top:5px; margin-right:20px; width:200px;">'.$address[$i][address1];
         if(!empty($address[$i][address2])){
         $html.='<br>'.$address[$i][address2];}
         if(!empty($address[$i][city_name])){
         $html.='<br>'.$address[$i][city_name];}
         if(!empty($address[$i][state])){
         $html.='<br>'.$address[$i][state];}
         if(!empty($address[$i][pin])){
         $html.='-'.$address[$i][pin];}

	if($address[$i]['url'] != ''){
		$html .="<br><a style='word-wrap:break-word;' href='".$address[$i]['url']."' target='_blank'>".$address[$i]['url']."</a>";
	}
		$html .='</div>';
     }
      
     }
 echo $html;
 exit;
} 
}
//End Added service_center details by Shashi Kant

elseif($mode == "add") 
{
	//assigning the real captcha code
	$real_captcha=rand(100000,9999999);
	$view->assign('real_captcha',$real_captcha); 
	
	//adding the base url
	$api_root=fn_url("/contact_us_api_calls_files/");
	$view->assign('api_root',$api_root);
}

// Search orders
//
elseif ($mode == 'write') {
   fn_add_breadcrumb(fn_get_lang_var('customer_support'));
	$params = $_REQUEST;
	if (!empty($auth['user_id'])) {
		$params['user_id'] = $auth['user_id'];
		$user_sess_email = db_get_field("select email from cscart_users where user_id='".$auth['user_id']."'");
	    $view->assign('user_sess_email',$user_sess_email);

	} elseif (!empty($auth['order_ids'])) {
		if (empty($params['order_id'])) {
			$params['order_id'] = $auth['order_ids'];
		} else {
			$ord_ids = is_array($params['order_id']) ? $params['order_id'] : explode(',', $params['order_id']);
			$params['order_id'] = array_intersect($ord_ids, $auth['order_ids']);
		}

	} else {
		 return array(CONTROLLER_STATUS_REDIRECT, "write_to_us.login?return_url=" . urlencode(Registry::get('config.current_url')));
		//return array(CONTROLLER_STATUS_REDIRECT, "auth.login_form?return_url=" . urlencode(Registry::get('config.current_url')));
	}

	list($orders, $search) = fn_get_orders($params, Registry::get('settings.Appearance.orders_per_page'));

	foreach($orders as $key=>$order_detail)
	{
		$order_info[]=fn_get_order_info($order_detail['order_id']);
        //code by ajay for show priority icon
		$ff_priority = db_get_row(" SELECT `occasion_id`, `priority_level_id` FROM `cscart_order_details` WHERE `order_id` = '".$order_detail['order_id']."'");
    
                  if ( !empty($ff_priority['occasion_id']) && !empty($ff_priority['priority_level_id']) ){
				  $orders[$key]['ff_priority']= 'Y';
			      }else{
				  $orders[$key]['ff_priority']= 'N';	  
				  }
				  
	   $priority_level_name = db_get_row(" SELECT `priority_level_id`, `priority_level_name`, `icon_url` , `color_code` FROM `clues_priority_levels_for_fulfillment`
                                          WHERE `priority_level_id` = '".$ff_priority['priority_level_id']."' ");	
       $orders[$key]['priority_level_name'] = $priority_level_name;	
	  		  	  
	   //end	

		
		//code by ajay for prevent cancellataion for RMA orders
			$main_order_id = db_get_field("select main_order_id from clues_order_clone_rel where clone_order_id='".$order_detail['order_id']."'");
			if (!empty($main_order_id))
			  {
			    $return_order = db_get_field("select return_id from cscart_rma_returns where order_id='".$main_order_id."'");
			    
				  if (!empty($return_order)){
				  $orders[$key]['allow_cancelation']= 'N';
			      }
			  }
		//End code by ajay
		//show Estimated Delivery Date By Ajay
		$orders[$key]['pdd_edd'] = fn_get_pdd_edd($order_detail['order_id']);
	}


	$orders_item=array();
	if(!empty($order_info))
	{   
		foreach($order_info as $order_detail)
		{
			$orders_item[]=$order_detail['items'];
			
			//code by ankur to check for return request
			if($order_detail['allow_return']=='Y')
			{
				$return_order[$order_detail['order_id']]='Y';
			}
			if(isset($order_detail['order_return_status']) && $order_detail['order_return_status']=='N')
			{
				$return_order[$order_detail['order_id']]='N';
			}
			elseif(isset($order_detail['order_return_status']) && $order_detail['order_return_status']=='E')
			{
				$return_order[$order_detail['order_id']]='E';
			}
			//code end
	
		}
	}

	//echo"<pre>";print_r($orders);die;
	$view->assign('orders_write', $orders);
	$view->assign('search_write', $search);
	$view->assign('orders_item_write',$orders_item);
	$view->assign('return_orders_write',$return_order);

}

if ($mode == 'login') {
	if (defined('AJAX_REQUEST') && empty($auth)) {
		exit;
	}

	if (!empty($auth['user_id'])) {
		return array(CONTROLLER_STATUS_REDIRECT, $index_script);
	}

	//fn_add_breadcrumb(fn_get_lang_var('my_account'));

} 

elseif($mode=='ajax_issues')//handling the ajax requests
{
	if(isset($_REQUEST['parent_id']) && !empty($_REQUEST['parent_id']) ){
     
    	if(isset($_REQUEST['text']) && $_REQUEST['text']!='other') {  
        $subissues = get_sub_issues($_REQUEST['parent_id']);
   
       if(count($subissues)>0)
	   {
		    echo '<label for="subissues" class="cont_nl_address cm-required">'.fn_get_lang_var('sub_issue').': <span class="red_astrik">*</span></label>';
    
				echo '<select  name="subissues" class="round_five profile_detail_field cont_nl_slt_width " id="subissues" style="height:30px;">';
				
				echo '<option name="" value="">Select</option>';
				
				foreach($subissues as $subissue){
					echo '<option value="'.$subissue['issue_id'].'">'.$subissue['name'].'</option>'; 
				}
				echo '</select>';
	   }
		else
		echo "";
        
     }
    //echo $view->assign('subissues',$subissues);
    die;
        
    }elseif(isset($_REQUEST['child_id']) && !empty($_REQUEST['child_id'])){
        
        //$subissues = db_get_row("select allow_free_text from clues_issues where issue_id=".$_REQUEST['child_id']."");
    	$subissues = get_sub_issues($_REQUEST['child_id']);
		
		if(count($subissues)>0)
		{
			
			echo '<label for="sub_subissues" class="cont_nl_address cm-required">'.fn_get_lang_var('sub_subissues').': <span class="red_astrik">*</span></label>';
		
			echo '<select  name="sub_subissues" class="round_five profile_detail_field cont_nl_slt_width " id="sub_subissues" style="height:30px;">';
			
			echo '<option name="" value="">Select</option>';
			
			foreach($subissues as $subissue){
				echo '<option value="'.$subissue['issue_id'].'">'.$subissue['name'].'</option>'; 
			}
			echo '</select>';
		}
		else
		echo "";
         
      die;
    }
	elseif(isset($_REQUEST['child_child_id']) && !empty($_REQUEST['child_child_id']))
	{
		//$subissues = db_get_row("select allow_free_text from clues_issues where issue_id='".$_REQUEST['child_child_id']."'");
  		$subissues = get_sub_issues($_REQUEST['child_child_id']);
		if(count($subissues)>0)
		{
			
			echo '<label for="sub_sub_subissues" class="cont_nl_address cm-required">'.fn_get_lang_var('sub_sub_subissues').': <span class="red_astrik">*</span></label>';
		
			echo '<select  name="sub_sub_subissues" class="round_five profile_detail_field cont_nl_slt_width " id="sub_sub_subissues" style="height:30px;">';
			
			echo '<option name="" value="">Select</option>';
			
			foreach($subissues as $subissue){
				echo '<option value="'.$subissue['issue_id'].'">'.$subissue['name'].'</option>'; 
			}
			echo '</select>';
		}
		else
		echo "";
		die;
	}
	elseif(isset($_REQUEST['parent_id']) && empty($_REQUEST['parent_id'])){  // if parent_id is empty then die
       
        die;
    }
}
elseif($mode=="error_notify")
{   $mydate = time();
	fn_set_notification('E', '','OOPS!! There was some error collecting your request !! Please try again !!');
	return array(CONTROLLER_STATUS_OK, "write_to_us.write");
}
elseif($mode=="get_error")
{ 
	fn_set_notification('E', '','OOPS!! There was some error collecting your request !! Please try again !!');
	return array(CONTROLLER_STATUS_OK, "write_to_us.add&x=".$mydate);
	
}elseif($mode == 'issue_type'){
	
	//$result = "<p>";
	if($_REQUEST['st'] == 'A'){
		
    $result .= "<p><input type='checkbox' id='dilivary_confirm' name='dilivary_confirm'   onclick='confirm_delivery($_REQUEST[order_id] , \"$_REQUEST[st]\");'>&nbsp;&nbsp;".fn_get_lang_var('checked_to_confirm')."</p><br /><br /> " ;
			
	} elseif ($_REQUEST['st'] == 'C' || $_REQUEST['st'] == 'H'){
		$result .="<p>". str_replace('[order_id]', $_REQUEST['order_id'] , fn_get_lang_var('post_dilivery_msg'))."</p>";
	}
	//$result .= "</p>";
	echo $result;
	exit;
}elseif($mode == 'confirm_delivery'){
	if($_REQUEST['st'] == 'A'){
		$status_result = fn_change_order_status($_REQUEST['order_id'], 'H', $_REQUEST['st'], fn_get_notification_rules(array(), false)); 
		if($status_result){
			fn_set_notification('N','',fn_get_lang_var('confirm_status_changed'));
		} else {
			fn_set_notification('N','',fn_get_lang_var('confirm_status_not_changed'));
		}
	}
 exit;
}

//added by ajay to show status message through IVR API
elseif($mode == 'show_status_msg'){
	
	$order_id = $_REQUEST['order_id'];
	$api_path = Registry::get('config.ivr_api_url').$order_id."&source=csform";
	$resp = get_ivr_response($api_path);
//	echo"<pre>";print_r($resp);	
	if(empty($resp['status'])){
		$ivr_status = $resp[0]['status'];
		$ivr_api_responce = $resp[0];
	}else{
		$ivr_status = $resp['status'];
		$ivr_api_responce = $resp;
	}
		
	$status_msg = db_get_row("select message_paid, message_cod from clues_cs_statuses where status='".$ivr_status."' ");
    
	if (!empty($status_msg)){
		    		
			$searchvalue = array("[order_id]","[shipment_date]", "[delivery_date]", "[order_confirm_date]", "[order_date]", "[payment_type]", "[days_to_refund]", "[refund_date]", "[refund_type]", "[rma_action]","[edd_start_date]","[edd_end_date]");
			$replacevalue = array($order_id, $ivr_api_responce['shipment_date'],$ivr_api_responce['delivery_date'],$ivr_api_responce['order_confirm_date'],$ivr_api_responce['order_date'],$ivr_api_responce['payment_type'],$ivr_api_responce['days_to_refund'],$ivr_api_responce['refund_date'],$ivr_api_responce['refund_type'],$ivr_api_responce['rma_action'],$ivr_api_responce['edd']['edd_start_date'],$ivr_api_responce['edd']['edd_end_date']);
			
			if (array_key_exists("payment_type", $ivr_api_responce)){
			    
					 if($ivr_api_responce['payment_type'] == 'COD'){
						 $status_message = $status_msg['message_cod'];
					 }elseif($ivr_api_responce['payment_type'] == 'PAID'){
						 $status_message = $status_msg['message_paid'];
					 }else{
						 $status_message = $status_msg['message_paid'];
					 }
				 
			}else{
				 $status_message = $status_msg['message_paid'];
			}
			 
			foreach ($replacevalue as $k=> $input) 
			 { 
			   if(preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/',$input)){
				   
				   $datee = strtotime($input) ;
	               $replacevalue[$k] = date('d M Y h:i A', $datee);	

				}
			 } 
			   
			$result = str_replace($searchvalue, $replacevalue , $status_message);
			echo $result;
			exit;
  }
  
}
//Added by shashikant to remove duplicate tickets
elseif($mode == 'duplicate_tickets'){
   
    $record=db_get_array("select id,service_type,issue_id,order_id,ticket_number,last_modified from clues_customer_queries where 
                          order_id='".$_REQUEST['order_id']."' and issue_id=".$_REQUEST['issue']);
                
    $i=0;          
    $result="";      
    foreach($record as $key=>$row){
    $id=$row[ticket_number];
    $tickets = get_zendesk_response("/tickets/" . $id . ".json");
    $details = json_decode($tickets);  
    $first_comment = $details->ticket->description;
    if(!empty($row['order_id']) && ($row['issue_id'] == $_REQUEST['issue'])){
        if(($details->ticket->status)!= 'closed'){
        if($i==0)
        {
            $result.= fn_get_lang_var("are_working_on_a_similar_issue")."<br /><br />";
            $result.="<table cellpadding='0' id='duplicate_records' cellspacing='0' border='0' width='100%' class='table sortable'>
               <thead>
               <th>Issue</th>
               <th>Ticket Number</th>
               <th>Ticket Created On</th>
               </thead><tbody>";
        }   
    $i++;            
                   $time =  strtotime($row['last_modified']);
                   $date = date('d M Y H:i:s', $time);
               $result.="<tr><td><a class='hide' id='".$row['ticket_number']."' onclick=\"popup('".$row['ticket_number']."');\" param=\"".$first_comment."\">".$row['service_type']."</a></td><td><a class='hide' id='".$row['ticket_number']."' onclick=\"popup('".$row['ticket_number']."');\" param=\"".$first_comment."\">".$row['ticket_number']."</a></td><td>".$date."</td></tr>";
           
          
             }
    }
    
     }
     if($result!=""){
      $result.="</tbody></table><br>";
           $result.=fn_get_lang_var("can_search_for_the_ticket_id_on_your_email");
           
     echo $result;exit;}
}
//End Added by shashikant to remove duplicate ticket


//Added by shashikant to add the facility of comment update in zendesk  through customer support page 
elseif($mode == 'customer_comment_update')
{  
   foreach ($_FILES['uploadFile']['name'] as $key => $file_name) {
        $rand1 = rand(0, 1000);
        $rand1 = rand(0, $rand1 * 2 + $rand1);
        $file_name = str_replace(' ', '-', $file_name);
        $file_name = preg_replace('/[^a-zA-Z0-9.\s]/', '-', $file_name);

        $file_name = $rand1 . $file_name;
        $file_size = $_FILES['uploadFile']['size'][$key];
        $upload_name = $_FILES['uploadFile']['tmp_name'][$key];
        $id = $_REQUEST['ticket_id'];
        $com = urldecode($_REQUEST['mail_body']);

        if (($_FILES['uploadFile']['size'][$key] < 250000 || $_FILES['uploadFile']['size'][$key] == '') && ($_FILES['uploadFile']['type'][$key] == 'image/jpeg' || $_FILES['uploadFile']['type'][$key] == 'image/png' || $_FILES['uploadFile']['type'][$key] == 'image/gif' || $_FILES['uploadFile']['type'][$key] == 'image/jpg' || $_FILES['uploadFile']['type'][$key] == '')) {
            move_uploaded_file($upload_name, "images/zendesk_uploads/" . $file_name);

            define("ZDAPIKEY", "glUcsLADOmVT2OPWbkHPYykzggAHSQxO3iuszolr");
            define("ZDUSER", "kaushik.chakraborty@shopclues.com");
            define("ZDURL", "https://Shopcluescom.zendesk.com/api/v2");

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
            curl_setopt($ch, CURLOPT_URL, "https://Shopcluescom.zendesk.com/api/v2/uploads.json?filename=$file_name");
            curl_setopt($ch, CURLOPT_USERPWD, ZDUSER . "/token:" . ZDAPIKEY);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/binary'));
            curl_setopt($ch, CURLOPT_POST, true);
            $local = trim(Registry::get('config.loc_img')."zendesk_uploads/$file_name");
            $remote =  Registry::get('config.remote_img');
            $parameter = Registry::get('config.rsync_parameter');
            $rsyn = exec("rsync $parameter $local $remote &");
            $img =  Registry::get('config.internal_images_host').'/'.trim(Registry::get('config.loc_img'))."zendesk_uploads/$file_name";
            $file = fopen($local, 'r');
            $size = filesize($local);
            $fildata = fread($file, $size);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fildata);
            curl_setopt($ch, CURLOPT_INFILE, $file);
            curl_setopt($ch, CURLOPT_INFILESIZE, $size);
            curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_VERBOSE, true);

            $output = curl_exec($ch);
            curl_close($ch);
            $decoded = json_decode($output);
            //echo "<pre>"; print_r($decoded); "</pre>"; die;
            $tokens = $decoded->upload->token;

            $token_string .=$tokens . ',';
            
        }
    }
    $tokens = rtrim($token_string, ',');
    $tokens = explode(',', $tokens);
    $comment = array("body" => $com, "public" => true, "uploads" => $tokens);
    //echo"<pre>"; print_r($tokens); die;
    $status = "open";
    $ticket_update = json_encode(array("ticket" => array("comment" => $comment, "status" => $status)));
    $data = get_zendesk_response("/tickets/" . $id . ".json", $ticket_update, "PUT");
    fn_set_notification('N', fn_get_lang_var('comment_successfully_updated'));
    return array(CONTROLLER_STATUS_REDIRECT, "write_to_us.write");
}
//End Added by shashikant to add the facility of comment update in zendesk  through customer support page


 function get_ivr_response($url)
 {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
	curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	$output = curl_exec($ch) or die(curl_error($ch));
	curl_close($ch);
	$decoded = json_decode($output,true);
	return $decoded;
}
//end by ajay

function select_parent_issues(){
     $issues = db_get_array("select name , issue_id,allow_free_text from clues_issues where parent_issue_id=0 and type='C'");
     return $issues;
}
 
function get_sub_issues($parent_id){
     
     $subissues = db_get_array("select name,issue_id,allow_free_text from clues_issues where parent_issue_id=".$parent_id." and type = 'C'");
     
     return $subissues; 
}
 
 function db_get_zendesk_code($id)
 {
	 $sql="select clues_issues.`desc` from clues_issues where issue_id='$id'";
	 $result=db_get_row($sql);
	 $result1=explode('|',$result['desc']);
	 return $result1[0];//returning the descriptions
 } 
 
 function db_get_assignee($id)
 {
	 $sql="select clues_issues.`desc` from clues_issues where issue_id='$id'";
	 $result=db_get_row($sql);
	 $result1=explode('|',$result['desc']);
	 return $result1[2];//returning the assignee
 }
 
 function db_get_group($id)
 {
	 $sql="select clues_issues.`desc` from clues_issues where issue_id='$id'";
	 $result=db_get_row($sql);
	 $result1=explode('|',$result['desc']);
	 return $result1[1];//returning the group
 }
 
 
function fn_get_purchased_gift_certificates($order_id)
{
	$res=db_get_row("select * from cscart_order_data where order_id='".$order_id."' and type='B'");
	return unserialize($res['data']);
}


/*function fn_get_feedback_posting_status($order_id)
{
	$sql="select cod.order_id from cscart_order_details cod
	left join clues_user_product_rating cupr on cupr.order_id=cod.order_id and cupr.product_id=cod.product_id
	where cod.order_id=$order_id and cupr.product_id is null";
	$res=db_get_field($sql);
	if(!empty($res))
	{
		return false;
	}
	else
	{
		return true;
	}
}*/
function fn_get_order_cancel_info($order_id)
{   
	$sql="select o.order_id,o.timestamp,pd.product_id,pd.product,od.amount,csd.status,csd.customer_facing_name as description
	      from cscart_orders o
		  inner join cscart_order_details od on o.order_id=od.order_id
		  inner join cscart_product_descriptions pd on od.product_id=pd.product_id
		  inner join cscart_status_descriptions csd on csd.status=o.status and csd.type='O'
		  where o.order_id='".$order_id."'
	";
	$res=db_get_array($sql);
	foreach($res as $result)
	{   
		$order_detail['order_id']=$result['order_id'];
		$order_detail['status_code']=$result['status'];
		$order_detail['status']=$result['description'];
		$order_detail['order_date']=$result['timestamp'];
		$order_detail['products'][$result['product_id']]['product']=$result['product'];
		$order_detail['products'][$result['product_id']]['amount']=$result['amount'];
		
		$now = time(); // or your date as well
		$your_date = $result['timestamp'];
		$datediff = $now - $your_date;
		$order_detail['age']= floor($datediff/(60*60*24));
		
	}
	return $order_detail;
	
	
}
function fn_get_cancel_reason($type, $age)
{
	$sql="select reason_id,reason from clues_order_cancellation_reason where type='".$type."' and order_days <='".$age."' order by position";
	return db_get_array($sql);
}

function fn_get_return_status($order_id)
{
	$return_status=db_get_row("SELECT status FROM `cscart_orders` o
where o.status IN(select status from clues_status_types where status_group='R' and object_type='O')and order_id='".$order_id."'");
	 return $return_status;
	
}

function fn_get_return_id($order_id)
{
	$return_id=db_get_field("select extra from cscart_order_details where order_id='".$order_id."'");
	$sql=unserialize($return_id);
	return $sql;
	
	}
//Function added by shashi kant to re move duplicate tickets and comment update in zendesk       
function get_zendesk_response($url, $json, $action)
{      
        define("ZDAPIKEY", "glUcsLADOmVT2OPWbkHPYykzggAHSQxO3iuszolr");
        define("ZDUSER", "kaushik.chakraborty@shopclues.com");
        define("ZDURL", "https://Shopcluescom.zendesk.com/api/v2");
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
	curl_setopt($ch, CURLOPT_URL, ZDURL.$url);
	curl_setopt($ch, CURLOPT_USERPWD, ZDUSER."/token:".ZDAPIKEY);
	switch($action){
		case "POST":
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
			break;
		case "GET":
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
			break;
		case "PUT":
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
			break;
		case "DELETE":
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
			break;
		default:
			break;
	}

	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
	curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

	$output = curl_exec($ch);
        //echo "<pre>"; print_r($output); die;
	curl_close($ch);

	return $output;        
}
//End Function added by shashi kant to re move duplicate tickets and comment update in zendesk
 
 
?>
