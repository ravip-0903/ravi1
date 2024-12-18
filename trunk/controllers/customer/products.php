<?php
/***************************************************************************
*                                                                          *
*   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
*                                                                          *
* This  is  commercial  software,  only  users  who have purchased a valid *
* license  and  accept  to the terms of the  License Agreement can install *
* and use this program.                                                    *
*                                                                          *
****************************************************************************
* PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
* "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
****************************************************************************/


if ( !defined('AREA') ) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST' && (isset($_SESSION['form_token_value']) && $_REQUEST['token'] != $_SESSION['form_token_value']) && !isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('form_token_not_matched'));
    return array(CONTROLLER_STATUS_OK, $_SERVER['HTTP_REFERER']);
}else{
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
        $token = md5(Registry::get('config.http_host').Registry::get('config.session_salt').time());
        $_SESSION['form_token_value'] = $token;
    }
}
//
// Search products
//



//$recent_viewed_data = recently_viewed_products();
//$view->assign('recent_products',$recent_viewed_data);
//print_r($recent_viewed_data); die;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 
 if($mode == 'seller_connect'){
     
 
       $customer_id = $_REQUEST['customer_id'];
       $merchant_id = $_REQUEST['merchant_id']; 
       $parent_id = 0;     // for initial message
       $message = addslashes($_REQUEST['user_message']);
       $topic = $_REQUEST['message_id'];
       $subject = addslashes($_REQUEST['subject']);
       $timestamp = date(time());
       $open_timestamp = 0; //default value
       $direction = 'C2M';
       $product_id = empty($_REQUEST['product_id'])? 0:$_REQUEST['product_id'];
       
       $topic_name = db_get_row("select name from clues_issues where issue_id=".$topic."");
       
       if($product_id > 0){
           
           $product_name = db_get_row ("select product from cscart_product_descriptions where product_id=".$product_id);
       }
       
       $subject = str_replace('[topic_name]',$topic_name['name'],$subject);
       
       $subject = addslashes($subject);
       
       if(preg_match("/\d(?:[-\s]?\d){7,12}/",$message)){
            
             fn_set_notification('E', fn_get_lang_var('Error'), fn_get_lang_var('number_is_not_allowed'),'I');
             
             //validate if merchant comes through company microsite or product page
             
             if(empty($_REQUEST['product_id'])){
                     $url_set = "index.php?dispatch=products.seller_connect&company_id=".base64_encode($_REQUEST['company_id']);               
                }else{
                     $url_set = "index.php?dispatch=products.seller_connect&product_id=".base64_encode($product_id);        
                 }
                 
             return array(CONTROLLER_STATUS_REDIRECT,$url_set);
      
             return false;
             
         }else if(preg_match("/([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})/",$message)){
            
             fn_set_notification('E', fn_get_lang_var('Error'), fn_get_lang_var('email_is_not_allowed'),'I');
             
 
             if(empty($_REQUEST['product_id'])){
                     
                 $url_set = "index.php?dispatch=products.seller_connect&company_id=".base64_encode($_REQUEST['company_id']);               
                
                     
                }else{
                     
                    $url_set = "index.php?dispatch=products.seller_connect&product_id=".base64_encode($product_id);        
                 }
             
             return array(CONTROLLER_STATUS_REDIRECT, $url_set);
      
             return false;
             
         }else if(preg_match("/(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?/",$message)){
             
             fn_set_notification('E', fn_get_lang_var('Error'), fn_get_lang_var('url_is_not_allowed'),'I');
             
             if(empty($_REQUEST['product_id'])){
                     
                 $url_set = "index.php?dispatch=products.seller_connect&company_id=".base64_encode($_REQUEST['company_id']);               
                
                     
                }else{
                     
                    $url_set = "index.php?dispatch=products.seller_connect&product_id=".base64_encode($product_id);        
                 }
             
             return array(CONTROLLER_STATUS_REDIRECT, $url_set);
      
             return false;
             
             
         }
         
       if(isset($merchant_id) && !empty($merchant_id)){ 
       
           $thread_id = fn_seller_connect($parent_id,$customer_id,$merchant_id,$product_id,$subject,$message,$topic,$timestamp,$open_timestamp,$direction);
     
           $url = Registry::get('config.current_location')."/"."vendor.php?dispatch=merchant_messages.seller_connect_reply&thread_id=".$thread_id;
           
           Registry::get('view_mail')->assign('subject', $subject);
           Registry::get('view_mail')->assign('message',$message);
           Registry::get('view_mail')->assign('url',$url);
          
           //In case first name is empty then use value of language 
          if(!empty($_REQUEST['user_name'])){
              
            Registry::get('view_mail')->assign('username',$_REQUEST['user_name']);
         
          } else {
              
           Registry::get('view_mail')->assign('username',fn_get_lang_var('substitute_first_name'));         
          }
          
           Registry::get('view_mail')->assign('date_time',date("m-d-Y H:i a")); 
           Registry::get('view_mail')->assign('topic_name',$topic_name['name']);
           Registry::get('view_mail')->assign('product_name',$product_name['product']);
           
           if($_REQUEST['mode_debug']=='debug'){
              
              $to_email = 'raj.singh@shopclues.com';
          } else {
              
              $to_email = $_REQUEST['merchant_email'];
          }
           
           fn_instant_mail($to_email, Registry::get('settings.Company.company_support_department'),'product/seller_connect_subj.tpl','product/seller_connect.tpl');
            
           fn_set_notification('N', fn_get_lang_var('notice'), fn_get_lang_var('mail_sent_successfully'),'I');
      
      }
      
      if(isset($_REQUEST['company_id']) && !empty($_REQUEST['company_id']) && $product_id==0){
          
          $url_set = "products.seller_connect&company_id=".base64_encode($_REQUEST['company_id'])."&status=success&thread_id=".$thread_id;
               
          } elseif (isset($_REQUEST['product_id'])){
          
          $url_set = "products.seller_connect&product_id=".base64_encode($_REQUEST['product_id'])."&status=success&thread_id=".$thread_id;
      }
      return array(CONTROLLER_STATUS_REDIRECT, $url_set);
      

    }
	else if($mode =='report_issue')
	{
		
		 $customer_id = $_REQUEST['customer_id'];
		 
         $merchant_id = $_REQUEST['merchant_id']; 
		 
		 $time_of_report = date(time());
		
		 $product_id1 = $_REQUEST['product_id1'];
		  //echo  $product_id1;die;
		 $type= $_REQUEST['type'];
		
		 $message= addslashes($_REQUEST['message']);
		//echo  $message;
		//echo "<br>";
		$update_issues ="insert into clues_product_issue set user_id='".$_REQUEST['customer_id']."',timestamp=". $time_of_report.",type='".$_REQUEST['type']."',message='".$message."',product_id='".$product_id1."',merchant_id='".$_REQUEST['merchant_id']."'";
		$id=db_query($update_issues);
		//print_r($data);
		return array(CONTROLLER_STATUS_OK, "products.report_issue&product_id=".base64_encode($product_id1)."&status=success&id=".$id);
		//echo $product_id1;die;
	
		
		}
      
      else if ($mode == 'search_feedback_form')
            {
              $session_id= Session::get_id();  //for session id
              
              $query="insert into clues_search_feedback_log set mobile_no='".$_REQUEST['feedback_mobile_no']."', search_word='".$_REQUEST['search_value']."', type='".$_REQUEST['feedback_category']."', comments='".$_REQUEST['feedback_comments']."', user_id='".$_REQUEST['user_id']."', session_id='".$session_id."', email='".$_REQUEST['email']."', url='".$_REQUEST['url']."', found='".$_REQUEST['found']."'";
              db_query($query);
           
              exit;
             
            }          
     else if($mode =='adword_view'){
         if($_REQUEST['elevate']==1){
             if($_REQUEST['type'] == "I") {
                 if(strpos($_REQUEST['product_id'], ',')) {
                     $arr = explode(",",$_REQUEST['product_id']);
                     foreach($arr as $key=>$val) {
                         fn_adword_view($val,'I',$_REQUEST['keyword'],$key+1);
                     }
                 } else {
                     fn_adword_view($_REQUEST['product_id'],$_REQUEST['type'],$_REQUEST['keyword'],$_REQUEST['position']);
                 }
             } else {
                 fn_adword_view($_REQUEST['product_id'],$_REQUEST['type'],$_REQUEST['keyword'],$_REQUEST['position']);
             }
             exit;         
         }
     }           
             
}


if ($mode == 'search') {
    
        $time_start = microtime(true);
	$params = $_REQUEST;
        $log_data['query'] = $params['q'];
        if($params['cid']){
            $log_data['filter_category'] = $params['cid'];
        }
        if($params['br']){
            $log_data['filter_brand'] = $params['br'];
        }
         if(!empty($params['q']) || $params['z'] !=''){
             $_SESSION['search_source'] = ($params['z']==1) ? 'Z' : 'S';            
             
         }
	if (!empty($params['search_performed']) || !empty($params['fq']) || !empty($params['features_hash']) || !empty($params['br'])) {

	  if(empty($_REQUEST['company_id']))
	  {
		//fn_add_breadcrumb(fn_get_lang_var('advanced_search'), "products.search" . (!empty($_REQUEST['advanced_filter']) ? '?advanced_filter=Y' : ''));
		fn_add_breadcrumb(fn_get_lang_var('search_results'));
	  }
		$params = $_REQUEST;
		$params['extend'] = array('description');
		//list($products, $search, $products_count) = fn_get_products($params, Registry::get('settings.Appearance.products_per_page'));

		//fn_gather_additional_products_data($products, array('get_icon' => true, 'get_detailed' => true, 'get_options'=> true));

		/*if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
		{
			$memcache = $GLOBALS['memcache'];
			$key = md5($_SERVER['QUERY_STRING'].'-product');
			if($mem_value = $memcache->get($key)){
					$products = $mem_value;
			}else{
				list($products, $search, $products_count) = fn_get_products($params, Registry::get('settings.Appearance.products_per_page'));
				fn_gather_additional_products_data($products, array('get_icon' => true, 'get_detailed' => true, 'get_options'=> true));
				$status = $memcache->set($key, $products, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time'));
                                if(!$status){
                                    $memcache->delete($key);
                                }
			}
		}else{*/

                
                        if(Registry::get('config.solr')) {
                            try{
                            	$arr = fn_assign_filtertoitem($params);
                                $prd = $arr['products'];
                                $products_count = $arr['products_count'];
                                $items_per_page = $arr['items_per_page'];
	                            
                                if(!empty($arr['suggestion'])) $view->assign('suggestion', $arr['suggestion']);	

                            }catch(Exception $e){
                                
                            }
                            $search = $params;
                            $products = array();
                            $i=0;
                            foreach($prd as $pr=>$pord){
                                $products[] = (array) $pord;
                                  $products[$i]['timestamp'] = $products[$i]['newarrivals'];
                            $i++;
                            }

                            fn_paginate($params['page'], $products_count, $items_per_page);    
                            fn_view_process_results('products', $products, $params, $items_per_page);
                    if(!isset($params['page'])) {
						$category_name = (isset($params['cid']) && $params['cid']!='' && $params['cid']!=0) ? fn_get_category_name($params['cid']) : 'All';
						$time_exec = microtime(true) - $time_start;
						$log_data['query_time'] = round($time_exec,3);
                                                
		                $content = $params['q'].':'.$products_count.':'.$category_name.':'. date('d-M-Y h-i-s').':'.$time_exec;
						log_to_file('searchstats',$content);
					}
			} else {
				list($products, $search, $products_count) = fn_get_products($params, Registry::get('settings.Appearance.products_per_page'));
			
                                
                        }
			fn_gather_additional_products_data($products, array('get_icon' => FALSE, 'get_detailed' => TRUE, 'get_options'=> FALSE));
			// Added by Sudhir for similar search result when no product found for search dt 23rd jan 2013 bigin here
			/*if(count($products) == 0){
				$last_sr = array_diff($_SESSION['products_similar'], $params['features_hash']);
				$last_sr1 = array_diff($params['features_hash'], $_SESSION['products_similar']);
				$last_sr = array_values($last_sr);
				$last_sr1 = array_values($last_sr1);

				if(count($last_sr) > 0){
					$last_sr_opt = $last_sr;
				} else {
					$last_sr_opt = $last_sr1;
				}
				
				$params['features_hash'] = $_SESSION['products_similar'];

				list($products_similar, $search, $products_count) = fn_get_products($params, Registry::get('settings.Appearance.products_per_page'));
				fn_gather_additional_products_data($products_similar, array('get_icon' => true, 'get_detailed' => true, 'get_options'=> true));
			} else {
				fn_gather_additional_products_data($products, array('get_icon' => true, 'get_detailed' => true, 'get_options'=> true));
				if($_SESSION['products_similar']){
					unset($_SESSION['products_similar']);
				}
				$_SESSION['products_similar']=$params['features_hash'];
			}*/
			// Added by Sudhir for similar search result when no product found for search dt 23rd jan 2013 end here
		//}
                 
		if (!empty($products)) {
                           
			$_SESSION['continue_url'] = Registry::get('config.current_url');
		}

		$selected_layout = fn_get_products_layout($params);
		
		/* MODIFIED BY SOUMYA FOR THE TIME : NEED TO BE UPDATED LATER */
		
		  if(!empty($_REQUEST['company_id']))
		  {
			$company_data = !empty($_REQUEST['company_id']) ? fn_get_company_data($_REQUEST['company_id']) : array();
			
			//fn_add_breadcrumb(fn_get_lang_var());
			
		    fn_add_breadcrumb($company_data['company'], "companies.view&company_id=" . $_REQUEST['company_id'] . (!empty($_REQUEST['advanced_filter']) ? '?advanced_filter=Y' : ''));
					
			if (empty($company_data) || empty($company_data['status']) || !empty($company_data['status']) && $company_data['status'] != 'A') {
			return array(CONTROLLER_STATUS_NO_PAGE);
			}
			
			$company_data['manifest'] = fn_get_manifest('customer', CART_LANGUAGE, $_REQUEST['company_id']);
			$view->assign('company_data', $company_data);		  	
		  }
		/* MODIFIED BY SOUMYA FOR THE TIME : NEED TO BE UPDATED LATER */

		// Added by Sudhir for similar search result when no product found for search dt 23rd jan 2013 bigin here
		/*if($products_similar){
			$view->assign('products_similar',$products_similar);
			$view->assign('products', $products_similar);
			$view->assign('last_search',$last_sr_opt[0]);
		} else {*/
                  //echo "<pre>";print_r($products);

                    $elevated_product1=array();
                    $elevated_product=array();
                    $products1=array();
                    $products2=array();
                    $i=0; 
                    //echo '<pre>'; print_r($products);die;
                    foreach($products as $product){                       
                      if($product['[elevated]']=='1'){
                         $elevated_product[$product['product_id']]=$product['product_id'];
                      } else {
                          $products2[$product['product_id']]=$product;
                      }
                      $products1[$product['product_id']]=$product;                
                    
                    $i++;
                    }  
                        //echo '<pre>'; print_r($elevated_product); 

                        if(!empty($elevated_product)) {
                            $products = array();
                            $sponsored_count = Registry::get('config.sponsored_count');
                            if($sponsored_count <= 0) {
                                $products = $products2;
                            } else {
                                $elevated_count = count($elevated_product);
                                if($elevated_count < $sponsored_count) $sponsored_count = $elevated_count;
                                $elevated_product1 = array_rand($elevated_product,$sponsored_count);

                                //To handle "array_rand" function - if you request only one item, it returns the index, if you request more than that, it returns an array of indices.
                                if($sponsored_count == 1) $elevated_product1 = array($elevated_product1);
                                
                                $ele_diff = array_diff($elevated_product,$elevated_product1);
                                $products = array_diff_key($products1,$ele_diff);
                            }                            
                            $products_count = count($products);
                        }                      
                         
                        $view->assign('elevate_products', $elevated_product1);                        
                        $view->assign('products', $products);
                        
                        $view->assign('product_per_page',Registry::get('settings.Appearance.products_per_page'));
		//}
                                
		$view->assign('search', $search);
		$view->assign('product_count', $products_count);
		$view->assign('selected_layout', $selected_layout);
                //sapna added some more parameters for logging
                
                //$selling_price=$products['price'];
                
                $log_data['referral_url']=Registry::get('config.current_url');
                $log_data['search_word']=$_REQUEST['q'];
                $log_data['hits'] = $products_count;
                LogMetric::dump_log(array_keys($log_data), array_values($log_data));
	} else {
		//fn_add_breadcrumb(fn_get_lang_var('advanced_search'));
		return array(CONTROLLER_STATUS_REDIRECT, $index_script);
	}
	if (!empty($params['advanced_filter'])) {

		/*$params['get_all'] = 'Y';

		list($filters, $view_all_filter) = fn_get_filters_products_count($params);
		$view->assign('filter_features', $filters);
		$view->assign('view_all_filter', $view_all_filter);*/
		return array(CONTROLLER_STATUS_REDIRECT, $index_script);
	}
        if($_REQUEST['isis']==1){
            $view->assign('page_new', $_REQUEST['page']+1);
            $view->assign('products', $products);
            $ajax_products = $view->display('blocks/list_templates/products_grid_ajaxified.tpl', false);
            echo $ajax_products;exit;
        }
//
// 
// View product details
//
}
elseif($mode =='track_source_cookie'){
       $referral = $_REQUEST['referral'];
    if (Registry::get('config.track_user')) {
           $source = fn_get_source_for_cookie($referral);
            //$value = "";
        if (!empty($source)) {
            $value.="#@" .$_REQUEST['product_id'] . ":" . $source . ",";
            echo $value;
           exit;
        }
    }
     
}
elseif($mode =='track_product_view'){
    if(Registry::get('config.track_user')){
        fn_track_product_view($_REQUEST['cookie'],$_REQUEST['product_id'],'productviewed');
         exit;
    }
}
 elseif ($mode == 'view'){
      //sapna
        $count=1;
            //echo "<pre>";print_r($_SESSION['continue_url']);die;
           //$search_word= strstr($_SESSION['continue_url'],"q=");
            //$data=explode("&dispatch=",$search_word);
            //$keyword=trim($data[0],"q=");
            //$keyword=str_replace("+"," ",$keyword);
       

        
       if(isset($_REQUEST['track']) && !empty($_REQUEST['track'])){  
             
            $track= base64_decode($_REQUEST['track']);
            $track_ar=explode("@@", $track);
             //echo "<pre>";print_r($track_ar);die;
            if(isset($track_ar[3]) && !empty($track_ar[3]) && $track_ar[3]==1 && $count==1){
                $count=0;
                fn_adword_view($_REQUEST['product_id'],'C',addslashes($track_ar[2]),$track_ar[1]);   
            }
         
         }//sapna 
        if(!empty($_REQUEST['filter']) && isset($_REQUEST['filter'])){
            $view->assign('filter_val_pro',$_REQUEST['filter']);
	}else{
            $view->assign('filter_val_pro',0);
        }
	$_REQUEST['product_id'] = empty($_REQUEST['product_id']) ? 0 : $_REQUEST['product_id'];
	$_SESSION['onedaysale'] = "NO";
        
     //dipankar   
        $all_promotions_array=fn_buy_together_promotion($_REQUEST['product_id']);
        if(!empty($all_promotions_array)){
            $view->assign('all_promotions_array',$all_promotions_array);
        }
        if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
    {
        $memcache = $GLOBALS['memcache'];
        $key = md5($_SERVER['QUERY_STRING'].'-product');
        if($mem_value = $memcache->get($key)){
            $product = $mem_value;
        }else{
            $product = fn_get_product_data($_REQUEST['product_id'], $auth, CART_LANGUAGE, '', true, true, true, true, ($auth['area'] == 'A' && !empty($_REQUEST['action']) && $_REQUEST['action'] == 'preview'));
            
            fn_gather_additional_product_data($product, true, true);
	    $status = $memcache->set($key, $product, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time'));
            if(!$status){
                $memcache->delete($key);
            }
        }   
    }else{
        $product = fn_get_product_data($_REQUEST['product_id'], $auth, CART_LANGUAGE, '', true, true, true, true, ($auth['area'] == 'A' && !empty($_REQUEST['action']) && $_REQUEST['action'] == 'preview'));
        fn_gather_additional_product_data($product, true, true);
    }

       // Code by Raj to Remove clone from product name
        if (!empty($product)) {
            $product['product'] = str_replace('[CLONE]','',$product['product']);
        }
       /* code by Raj to display only first value of option name */
       
       if(!empty($product['product_options'])){
           
           foreach ($product['product_options'] as $key=>$val){
                     
                    $pick_first = explode('-',$val['option_name']);
                    $product['product_options'][$key]['option_name'] = $pick_first[0];
                   
           }
       }
       
	if (empty($product)) {
		//return array(CONTROLLER_STATUS_NO_PAGE);
		/*Modified by clues dev to redirect if product no found*/
		//fn_set_notification('N', fn_get_lang_var('notice'), fn_get_lang_var('no_product_found'));
		return array(CONTROLLER_STATUS_REDIRECT, $index_script);
		/*Modified by clues dev to redirect if product no found*/
	}

	if ((empty($_SESSION['current_category_id']) || empty($product['category_ids'][$_SESSION['current_category_id']])) && !empty($product['main_category'])) {
		if (!empty($_SESSION['breadcrumb_category_id']) && !empty($product['category_ids'][$_SESSION['breadcrumb_category_id']])) {
			$_SESSION['current_category_id'] = $_SESSION['breadcrumb_category_id'];
		} else {
			$_SESSION['current_category_id'] = $product['main_category'];
		}
	}

	if (!empty($product['meta_description']) || !empty($product['meta_keywords'])) {
		$view->assign('meta_description', $product['meta_description']);
		$view->assign('meta_keywords', $product['meta_keywords']);

	} else {
		if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
        {
            $memcache = $GLOBALS['memcache'];
            $key = md5($_SERVER['QUERY_STRING'].'-meta_tags');
            if($mem_value = $memcache->get($key)){
                $meta_tags = $mem_value;       
            }else{
                $meta_tags = db_get_row("SELECT meta_description, meta_keywords FROM ?:category_descriptions WHERE category_id = ?i AND lang_code = ?s", $_SESSION['current_category_id'], CART_LANGUAGE);
                $status = $memcache->set($key, $meta_tags, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time'));// or die ("Failed to save data at the server");             
                if(!$status){
                    $memcache->delete($key);
                }
            }   
        }else{
            $meta_tags = db_get_row("SELECT meta_description, meta_keywords FROM ?:category_descriptions WHERE category_id = ?i AND lang_code = ?s", $_SESSION['current_category_id'], CART_LANGUAGE);
        }       
		if (!empty($meta_tags)) {
			$view->assign('meta_description', $meta_tags['meta_description']);
			$view->assign('meta_keywords', $meta_tags['meta_keywords']);
		}
	}
	if (!empty($_SESSION['current_category_id'])){
		$_SESSION['continue_url'] = "categories.view?category_id=$_SESSION[current_category_id]";

		if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
        {
            $memcache = $GLOBALS['memcache'];
            $key = md5($_SERVER['QUERY_STRING'].'-parent_ids');
            if($mem_value = $memcache->get($key)){
                $parent_ids = $mem_value;       
            }else{
                $parent_ids = fn_explode('/', db_get_field("SELECT id_path FROM ?:categories IGNORE INDEX(p_category_id) WHERE category_id = ?i", $_SESSION['current_category_id']));
                $status = $memcache->set($key, $parent_ids, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time'));// or die ("Failed to save data at the server");
                if(!$status){
                    $memcache->delete($key);
                }
            }   
        }else{
            $parent_ids = fn_explode('/', db_get_field("SELECT id_path FROM ?:categories IGNORE INDEX(p_category_id) WHERE category_id = ?i", $_SESSION['current_category_id']));
        }

		if (!empty($parent_ids)) {
			if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
            {
                $memcache = $GLOBALS['memcache'];
                $key = md5($_SERVER['QUERY_STRING'].'-cats');
                if($mem_value = $memcache->get($key)){
                    $cats = $mem_value;       
                }else{
                    $cats = fn_get_category_name($parent_ids);
                    $status = $memcache->set($key, $cats, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time')); // or die ("Failed to save data at the server");
                    if(!$status){
                        $memcache->delete($key);
                    }
                }   
            }else{
                $cats = fn_get_category_name($parent_ids);
            }
			foreach($parent_ids as $c_id) {
				fn_add_breadcrumb($cats[$c_id], "categories.view?category_id=$c_id");
			}
		}
	}
	fn_add_breadcrumb($product['product']);

	if (!empty($_REQUEST['combination'])) {
		$product['combination'] = $_REQUEST['combination'];
	}
	
	if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
    {   
        $memcache = $GLOBALS['memcache'];
        $key = md5($_SERVER['QUERY_STRING'].'-allowed_payment_method');
        if($mem_value = $memcache->get($key)){
            $allowed_payment_method = $mem_value;       
        }else{
            $allowed_payment_method = db_get_field("select is_cod from cscart_products where product_id = '".$_REQUEST['product_id']."'");
            $status = $memcache->set($key, $allowed_payment_method, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time')); // or die ("Failed to save data at the server");
            if(!$status){
                $memcache->delete($key);
            }
        }   
    }else{
        $allowed_payment_method = db_get_field("select is_cod from cscart_products where product_id = '".$_REQUEST['product_id']."'");
    }
	if(!empty($allowed_payment_method)) {
	//$allowed_payment_method = explode(',',$allowed_payment_method['payment_method']);
	if($allowed_payment_method == "Y") {
	  $product['allow_cod'] = 'yes';
	 }else {
		$product['allow_cod'] = 'no'; 
	 }
	}else{
		$product['allow_cod'] = 'no';	
	}
	$sql = "select cc.fulfillment_id from cscart_companies cc 
					left join clues_warehouse_contact cwc on cwc.company_id = cc.company_id
					where cc.company_id = ".$product['company_id']." and cwc.region_code in ('".Registry::get('config.giftable_region_code')."')";
	$company_fulfillment_id = db_get_field($sql);
	if(in_array($company_fulfillment_id, Registry::get('config.giftable_fulfilment_id')))
	{
		$product['is_giftable']	 = 'Y';
	}
        if($product['price'] >= Registry::get('config.emi_min_amount'))
        {
            $emisql = "SELECT min(fee) FROM clues_payment_options_emi_pgw WHERE period = 3";
            $product['emi_fee'] = db_get_field($emisql);
            $banksql = "select c1.name,c1.payment_option_id from clues_payment_options as c1 join clues_payment_options_emi_pgw as c2 on c1.payment_option_id = c2.payment_option_id AND c2.status = 'A' group by c1.name";
            $product['bank'] = db_get_array($banksql);  
        }
        
        if($product['promotion_id'])
        {
            $offer_sql = "select conditions from cscart_promotions where promotion_id=".$product['promotion_id']." and to_date>=".strtotime('now')." and from_date<=".strtotime('now')." and status='A'";
            $offerdcond = db_get_field($offer_sql);
            if(!empty($offerdcond))
            {
                $con_array = unserialize($offerdcond);
                foreach ($con_array as $value) {
                 foreach ($value as $val) {
                     
                   if($val['condition'] == 'coupon_code') {
                       $product['coupan'] =$val['value'];
                   }
                }   
                }
                if($product['coupan'])
                {
                    $offer_query = "SELECT cpt.customer_facing_name FROM clues_promotion_type as cpt
                            INNER JOIN cscart_promotions as cp ON cp.promotion_type_id = cpt.promotion_type_id
                            WHERE cp.promotion_id=".$product['promotion_id'];
                    $product['offer_name'] = db_get_field($offer_query);
                }
            }
        }
        if($product['freebie_promotion_id'])
        {
            $offer_sql1 = "select bonuses from cscart_promotions where promotion_id=".$product['freebie_promotion_id']." and to_date>=".strtotime('now')." and from_date<=".strtotime('now')." and status='A'";
            $offerdfreebie = db_get_field($offer_sql1);
            if(!empty($offerdfreebie))
            {
                $bonus_array = unserialize($offerdfreebie);
                foreach ($bonus_array as $value) {
                    foreach ($value['value'] as $v) {
                        if($v['product_id'])
                        {
                                $check_query = "select count(*) as count from cscart_products as c1
                                INNER JOIN cscart_companies as com ON com.company_id = c1.company_id
                                INNER JOIN cscart_products_categories as cpc ON cpc.product_id = c1.product_id
                                INNER JOIN cscart_categories as cc ON cpc.category_id = cc.category_id
                                where c1.product_id=".$v['product_id']." AND c1.status IN ('A','H') AND com.status='A' AND cc.status IN ('A','H')";
                            $check_count = db_get_field($check_query);
                            if($check_count)
                            {
                                $product['freebee']['product_id'] = $v['product_id'];
                                $product['freebee']['name'] = fn_get_product_name($v['product_id']);
                                $product['freebee']['price'] = db_get_field("SELECT price FROM cscart_product_prices WHERE product_id=".$v['product_id']);
                            }
                        }
                   }
                }
            }
         }
         
        if($product['price'] >= Registry::get('config.emi_min_amount'))
        {
            $emisql = "SELECT min(fee) FROM clues_payment_options_emi_pgw WHERE period = 3";
            $product['emi_fee'] = db_get_field($emisql);
            $banksql = "select c1.name,c1.payment_option_id from clues_payment_options as c1 join clues_payment_options_emi_pgw as c2 on c1.payment_option_id = c2.payment_option_id AND c2.status = 'A' group by c1.name";
            $product['bank'] = db_get_array($banksql);  
        }
        //start code by munish to take dispatch time of product
        $product['dispatch_days'] = db_get_field("SELECT FLOOR( cse.maximum_pickup_time /86400 )
                            FROM clues_shipping_estimation AS cse
                            INNER JOIN cscart_products AS cp ON cp.product_shipping_estimation = cse.id
                            WHERE cp.product_id =?i", $product['product_id']);
        $product['retail_price'] = db_get_field("SELECT retail_price FROM ?:product_prices WHERE product_id =?i", $product['product_id']);
        //End code by munish to take dispatch time of product
    //fn_gather_additional_product_data($product, true, true);
        
        //anoop code to catch ajax from product page when buyer changes quantity
        $product['check_for_product_diff_price'] = 0;
        if(fn_check_if_shipping_price_set_for_product($_REQUEST['product_id']) && $product['is_wholesale_product'] != 1)
        {
            $product['check_for_product_diff_price'] = 1;
            $product_price_arr = array();
            if(!empty($product['prices']))
            {
                foreach($product['prices'] as $value)
                {
                    $value['price'] = number_format($value['price'], 0, '.', '');
                    $value['shipping_charge'] = number_format($value['shipping_charge'], 0,'.','');
                    $product_price_arr[$value['lower_limit']] = $value;
                }
            }
            if(!empty($product_price_arr))
            {
                $product_price_arr = array_reverse($product_price_arr,true);
                $product_price_arr = json_encode($product_price_arr);
                $view->assign("product_price_arr",$product_price_arr);
            } 
       }

        //ends here

	//Anoop code to check value added services on product
        $result_value_added = fn_check_if_value_added_service_going_on_product($product['product_id']);
        $product['value_added_services'] = $result_value_added[$product['product_id']];
        //ends here

        if(Registry::get('config.enable_PBE'))
        {
            $master_id_sql = "select master_id from cscart_products where product_id=$_REQUEST[product_id]";
            $master_id = db_get_field($master_id_sql);
            if($master_id!=0)
            {
                $master_data = fn_get_product_data($master_id, $auth, CART_LANGUAGE, '', true, true, true, true, ($auth['area'] == 'A' && !empty($_REQUEST['action']) && $_REQUEST['action'] == 'preview'));
                $product['full_description']=$master_data['full_description'];
            }   
        }
	$view->assign('product', $product);

	
	// If page title for this product is exist than assign it to template
	if (!empty($product['page_title'])) {
		$view->assign('page_title', $product['page_title']);
	}

    
/* Code by munish on 28- feb- 2014 for question answer block on product page - Start */
    if(Registry::get('config.ques_ans_block_enable'))
    {
        $ques_query = "SELECT csc1.message as question,csc.message as answer,csc.timestamp 
                                    FROM clues_seller_connect as csc
                                    INNER JOIN  clues_seller_connect  as csc1 on csc.parent_id=csc1.thread_id and csc1.direction = 'C2M'
                                    WHERE csc.product_id =".$product['product_id']." and csc.direction = 'M2C' and csc.publish= 'Y' ";
        $data_count = db_get_array($ques_query);
        $total_count =  count($data_count);
        $limit = fn_paginate($_REQUEST['page'],$total_count);
        $ques_query .= $limit;
        $data = db_get_array($ques_query);
        $view->assign('question_data',$data);
    }
/* Code by munish on 28- feb- 2014 for question answer block on product page - End*/
    
	if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
    {
        $memcache = $GLOBALS['memcache'];
        $key = md5($_SERVER['QUERY_STRING'].'-files');
        if($mem_value = $memcache->get($key)){
            $files = $mem_value;       
        }else{
            $files = fn_get_product_files($_REQUEST['product_id'], true);
            $status = $memcache->set($key, $files, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time')); // or die ("Failed to save data at the server");
            if(!$status){
                $memcache->delete($key);
            }
        }   
    }else{
        $files = fn_get_product_files($_REQUEST['product_id'], true);
    }
	if (!empty($files)) {
		$view->assign('files', $files);
	}

	/* [Block manager tabs] */
	$_blocks = $view->get_var('blocks');
	foreach ($_blocks as $block) {
		if (!empty($block['text_id']) && $block['text_id'] == 'product_details') {
			$tabs_group_id = $block['block_id'];
			break;
		}
	}
	if (!empty($tabs_group_id)) {
		$view->assign('tabs_block_id', $tabs_group_id);
		foreach ($_blocks as $block) {
			if (!empty($block['group_id']) && $block['group_id'] == $tabs_group_id) {
				Registry::set('navigation.tabs.block_' . $block['block_id'], array (
					'title' => $block['description'],
					'js' => true
				));
			}
		}
	}
	/* [/Block manager tabs] */
//Added by shashi kant to show recently_viewed_history
	// Set recently viewed products history
	if (!empty($_SESSION['recently_viewed_products'])) {
		$recently_viewed_product_id = array_search($_REQUEST['product_id'], $_SESSION['recently_viewed_products']);
		// Existing product will be moved on the top of the list
		if ($recently_viewed_product_id !== FALSE) {
			// Remove the existing product to put it on the top later
			unset($_SESSION['recently_viewed_products'][$recently_viewed_product_id]);
			// Re-sort the array
			$_SESSION['recently_viewed_products'] = array_values($_SESSION['recently_viewed_products']);
		}
		array_unshift($_SESSION['recently_viewed_products'], $_REQUEST['product_id']);
	} elseif (empty($_SESSION['recently_viewed_products'])) {
		$_SESSION['recently_viewed_products'] = array($_REQUEST['product_id']);
	}

	if (count($_SESSION['recently_viewed_products']) > MAX_RECENTLY_VIEWED) {
		array_pop($_SESSION['recently_viewed_products']);
	}
//End added by shashi kant to show recently_viewed_history
	// Increase product popularity
	if (empty($_SESSION['products_popularity']['viewed'][$_REQUEST['product_id']])) {
		$_data = array (
			'product_id' => $_REQUEST['product_id'],
			'viewed' => 1,
			'total' => POPULARITY_VIEW
		);
		
		db_query("INSERT INTO ?:product_popularity ?e ON DUPLICATE KEY UPDATE viewed = viewed + 1, total = total + ?i", $_data, POPULARITY_VIEW);
		
		$_SESSION['products_popularity']['viewed'][$_REQUEST['product_id']] = true;
	}

/*	$product_notification_enabled = (isset($_SESSION['product_notifications']) ? (isset($_SESSION['product_notifications']['product_ids']) && in_array($_REQUEST['product_id'], $_SESSION['product_notifications']['product_ids']) ? 'Y' : 'N') : 'N');
	if ($product_notification_enabled) {
		if (($_SESSION['auth']['user_id'] == 0) && !empty($_SESSION['product_notifications']['email'])) {
			if (!db_get_field("SELECT subscription_id FROM ?:product_subscriptions WHERE product_id = ?i AND email = ?s", $_REQUEST['product_id'], $_SESSION['product_notifications']['email'])) {
				$product_notification_enabled = 'N';
			}
		} elseif (!db_get_field("SELECT subscription_id FROM ?:product_subscriptions WHERE product_id = ?i AND user_id = ?i", $_REQUEST['product_id'], $_SESSION['auth']['user_id'])) {
			$product_notification_enabled = 'N';
		}
	}
*/
        if(Registry::get('config.quantity_discount_flag') == 1)
        {
            $is_product_on_tp = 0;
            $tp = db_get_row("SELECT tp, if(type='deal_tp',1,2) as tporder FROM clues_product_TP 
            WHERE product_id ='".$_REQUEST['product_id']."' AND latest=1
            AND start_date <= '".time()."' AND tp != 0 AND end_date >= '".time()."' order by tporder ASC");
            
            if(!empty($tp['tp']))
            {
                $is_product_on_tp = 1;
            }
           
            $view->assign("product_on_tp",$is_product_on_tp);
        }
        $product_prices = db_get_array("SELECT * FROM cscart_product_prices WHERE product_id=$_REQUEST[product_id]");
        $view->assign("product_prices",$product_prices);
	if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
    {
        $memcache = $GLOBALS['memcache'];
        $key = md5($_SERVER['QUERY_STRING'].'-product_notification_enabled');
        if($mem_value = $memcache->get($key)){
            $product_notification_enabled = $mem_value;       
        }else{
            $product_notification_enabled = (isset($_SESSION['product_notifications']) ? (isset($_SESSION['product_notifications']['product_ids']) && in_array($_REQUEST['product_id'], $_SESSION['product_notifications']['product_ids']) ? 'Y' : 'N') : 'N');
			if ($product_notification_enabled) {
				if (($_SESSION['auth']['user_id'] == 0) && !empty($_SESSION['product_notifications']['email'])) {
					if (!db_get_field("SELECT subscription_id FROM ?:product_subscriptions WHERE product_id = ?i AND email = ?s", $_REQUEST['product_id'], $_SESSION['product_notifications']['email'])) {
						$product_notification_enabled = 'N';
					}
				} elseif (!db_get_field("SELECT subscription_id FROM ?:product_subscriptions WHERE product_id = ?i AND user_id = ?i", $_REQUEST['product_id'], $_SESSION['auth']['user_id'])) {
					$product_notification_enabled = 'N';
				}
			}
			$status = $memcache->set($key, $product_notification_enabled, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time')); // or die ("Failed to save data at the server");
                        if(!$status){
                            $memcache->delete($key);
                        }
        }   
    }else{        
		$product_notification_enabled = (isset($_SESSION['product_notifications']) ? (isset($_SESSION['product_notifications']['product_ids']) && in_array($_REQUEST['product_id'], $_SESSION['product_notifications']['product_ids']) ? 'Y' : 'N') : 'N');
		if ($product_notification_enabled) {
			if (($_SESSION['auth']['user_id'] == 0) && !empty($_SESSION['product_notifications']['email'])) {
				if (!db_get_field("SELECT subscription_id FROM ?:product_subscriptions WHERE product_id = ?i AND email = ?s", $_REQUEST['product_id'], $_SESSION['product_notifications']['email'])) {
					$product_notification_enabled = 'N';
				}
			} elseif (!db_get_field("SELECT subscription_id FROM ?:product_subscriptions WHERE product_id = ?i AND user_id = ?i", $_REQUEST['product_id'], $_SESSION['auth']['user_id'])) {
				$product_notification_enabled = 'N';
			}
		}
	}
	$view->assign('product_notification_enabled', $product_notification_enabled);
	$view->assign('product_notification_email', (isset($_SESSION['product_notifications']) ? $_SESSION['product_notifications']['email'] : ''));
         
        
           $value=$_COOKIE['searchlog'];
           $split_data=split (",", $value);
          
            $log_data['product_id']=$split_data['0'];
            $log_data['position']=$split_data['1'];
            $log_data['last_price']=$split_data['2']; 
           
         LogMetric::dump_log(array_keys($log_data), array_values($log_data));
         
//Added by shashi kant  to sizing options at product page
         $pid = $_REQUEST['product_id'];
         $cid = db_get_field("select category_id from cscart_products_categories where product_id ='".$pid."' and link_type='M'");
         //$show_size_chart = db_get_field("select show_size_chart from cscart_products where product_id ='".$pid."' ");

         if($product[show_size_chart]!= 'N'){
if(Registry::get('config.enable_size_chart')==true && in_array($cid,Registry::get('config.size_chart_categories_id'))){
        $cid = db_get_field("select category_id from cscart_products_categories where product_id ='".$pid."' and link_type='M'");
       
        $sizes=db_get_array('select * from clues_sizes where category_id='.$cid.' order by position asc');
        $size_parts=db_get_array('select * from clues_size_parts where category_id='.$cid.' order by position asc');
        $image_query = db_get_field("Select sizing_image from cscart_categories where category_id=".$cid);
        $query = "select vals.*,parts.size_part,sizes.size
            from
                clues_size_values vals
            inner join
                clues_size_parts parts on vals.size_part_id=parts.size_part_id
            inner join
                clues_sizes sizes on vals.size_id=sizes.size_id
            where
                vals.object_type='p' and vals.object_id=$pid
            order by
                parts.position asc, sizes.position asc
        ";
        $size_values=db_get_array($query);
        if(empty($size_values)){

            $query = "select vals.*,parts.size_part,sizes.size
            from
                clues_size_values vals
            inner join
                clues_size_parts parts on vals.size_part_id=parts.size_part_id
            inner join
                clues_sizes sizes on vals.size_id=sizes.size_id
            where
                vals.object_type='c' and vals.object_id=$cid
            order by
                parts.position asc, sizes.position asc
        ";
            $size_values=db_get_array($query);
        }
        
        $view->assign('sizechart_sizes',$sizes);
        $view->assign('sizechart_size_parts',$size_parts);
        $view->assign('sizechart_size_values',$size_values);
        $view->assign('image_size',$image_query);
        }
         }
//End added by shashi kant to sizing options at product page	
         
} elseif ($mode == 'product_notifications') {

	$data = array (
		'product_id' => $_REQUEST['product_id'],
		'user_id' => $_SESSION['auth']['user_id'],
		'email' => (!empty($_SESSION['cart']['user_data']['email']) ? $_SESSION['cart']['user_data']['email'] : (!empty($_REQUEST['email']) ? $_REQUEST['email'] : ''))
	);

	if (!empty($data['email'])) {
		$_SESSION['product_notifications']['email'] = $data['email'];
		if ($_REQUEST['enable'] == 'Y') {
		
			$product_subscription = db_get_array("SELECT user_id, email, product_id FROM ?:product_subscriptions WHERE product_id =".$data['product_id']." and email='".$data['email']."'");
			if(count($product_subscription) == 0)
			{
				fn_set_notification('N', '', fn_get_lang_var('notification_for_subscription'));
				db_query("INSERT INTO ?:product_subscriptions (product_id,user_id,email) VALUES('".$data['product_id']."','".$data['user_id']."','".$data['email']."')");
				if (!isset($_SESSION['product_notifications']['product_ids']) || (is_array($_SESSION['product_notifications']['product_ids']) && !in_array($data['product_id'], $_SESSION['product_notifications']['product_ids']))) {
					$_SESSION['product_notifications']['product_ids'][] = $data['product_id'];
				}
			}
			else
			{
				if($product_subscription['user_id'] != 0)
				{
					fn_set_notification('N', '', fn_get_lang_var('already_subscribed_for_notification'));
					//die("Helloooooooooo");
				}
				elseif($data['user_id'] != 0 && $product_subscription['user_id'] == 0)
				{
					fn_set_notification('N', '', fn_get_lang_var('notification_for_subscription'));
					db_query("UPDATE ?:product_subscriptions SET user_id = ?i WHERE product_id = ?i AND email = ?s", $data['user_id'],$data['product_id'],$data['email']);
					if (!isset($_SESSION['product_notifications']['product_ids']) || (is_array($_SESSION['product_notifications']['product_ids']) && !in_array($data['product_id'], $_SESSION['product_notifications']['product_ids']))) {
						$_SESSION['product_notifications']['product_ids'][] = $data['product_id'];
					}
				}
				elseif($data['user_id'] == 0 && $product_subscription['user_id'] == 0)
				{
					fn_set_notification('N', '', fn_get_lang_var('already_subscribed_for_notification'));
				}
			}
			
		} else {
			db_query("DELETE FROM ?:product_subscriptions WHERE product_id = ?i AND user_id = ?i AND email = ?s", $data['product_id'], $data['user_id'], $data['email']);
			if (isset($_SESSION['product_notifications']) && isset($_SESSION['product_notifications']['product_ids']) && in_array($data['product_id'], $_SESSION['product_notifications']['product_ids'])) {
				$_SESSION['product_notifications']['product_ids'] = array_diff($_SESSION['product_notifications']['product_ids'], array($data['product_id']));
			}
		}
	}
	
	if (defined('AJAX_REQUEST')) {
		die(empty($data['product_id']) ? 'Access denied' : 'OK');
	} else {
		return array(CONTROLLER_STATUS_REDIRECT, 'products.view&product_id=' . $data['product_id']);
	}
} elseif ($mode == 'qty') {
	$product_id = $_REQUEST['product_id'];
	$qty = db_get_field("select amount from ?:products where product_id = ?i", $product_id);
	echo $qty;exit;
}
 elseif ($mode == 'onedaysale') {	

	//$_REQUEST['product_id'] = empty($_REQUEST['product_id']) ? 0 : $_REQUEST['product_id'];
	
	$sql = "select product_id, one_day_sale_start_datetime, one_day_sale_end_datetime from cscart_products where one_day_sale_start_datetime <= '".date('Y-m-d H:i:s')."' and one_day_sale_end_datetime >='".date('Y-m-d H:i:s')."' and (status = 'A' or status = 'H') order by one_day_sale_start_datetime LIMIT 0,1 ";
	$result = db_get_row($sql);
	if(empty($result)){
		$sql = "select product_id, one_day_sale_start_datetime, one_day_sale_end_datetime from cscart_products where one_day_sale_end_datetime <='".date('Y-m-d h:i:s')."' order by one_day_sale_end_datetime desc LIMIT 0,1";	
		$result = db_get_row($sql);
		$view->assign('current_avail_sale', 'NO');
	}
	//echo '<pre>';print_r($result);die("CHANDAN");
	$product_id = $result['product_id'];
	$_SESSION['onedaysale'] = 'YES';
    if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
    {
        $memcache = $GLOBALS['memcache'];
        $key = md5($product_id.'-onedaysaleproduct');
        if($mem_value = $memcache->get($key)){
            $product = $mem_value;
        }else{
            $product = fn_get_product_data($product_id, $auth, CART_LANGUAGE, '', true, true, true, true, ($auth['area'] == 'A' && !empty($_REQUEST['action']) && $_REQUEST['action'] == 'preview'));
            fn_gather_additional_product_data($product, true, true);
	    	$status = $memcache->set($key, $product, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time'));
            if(!$status){
                $memcache->delete($key);
            }
        }   
    }else{
        $product = fn_get_product_data($product_id, $auth, CART_LANGUAGE, '', true, true, true, true, ($auth['area'] == 'A' && !empty($_REQUEST['action']) && $_REQUEST['action'] == 'preview'));
		
		fn_gather_additional_product_data($product, true, true);
    }
  	  
	if (empty($product)) {
		//return array(CONTROLLER_STATUS_NO_PAGE);
		/*Modified by clues dev to redirect if product no found*/
		//fn_set_notification('N', fn_get_lang_var('notice'), fn_get_lang_var('no_product_found'));
		return array(CONTROLLER_STATUS_REDIRECT, $index_script);
		/*Modified by clues dev to redirect if product no found*/
	}

	if ((empty($_SESSION['current_category_id']) || empty($product['category_ids'][$_SESSION['current_category_id']])) && !empty($product['main_category'])) {
		if (!empty($_SESSION['breadcrumb_category_id']) && !empty($product['category_ids'][$_SESSION['breadcrumb_category_id']])) {
			$_SESSION['current_category_id'] = $_SESSION['breadcrumb_category_id'];
		} else {
			$_SESSION['current_category_id'] = $product['main_category'];
		}
	}

	if (!empty($product['meta_description']) || !empty($product['meta_keywords'])) {
		$view->assign('meta_description', $product['meta_description']);
		$view->assign('meta_keywords', $product['meta_keywords']);

	} else {
		if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
        {
            $memcache = $GLOBALS['memcache'];
            $key = md5($product_id.'-onedaysalemeta_tags');
            if($mem_value = $memcache->get($key)){
                $meta_tags = $mem_value;       
            }else{
                $meta_tags = db_get_row("SELECT meta_description, meta_keywords FROM ?:category_descriptions WHERE category_id = ?i AND lang_code = ?s", $_SESSION['current_category_id'], CART_LANGUAGE);
                $status = $memcache->set($key, $meta_tags, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time'));// or die ("Failed to save data at the server");             
                if(!$status){
                    $memcache->delete($key);
                }
            }   
        }else{
            $meta_tags = db_get_row("SELECT meta_description, meta_keywords FROM ?:category_descriptions WHERE category_id = ?i AND lang_code = ?s", $_SESSION['current_category_id'], CART_LANGUAGE);
        }       
		if (!empty($meta_tags)) {
			$view->assign('meta_description', $meta_tags['meta_description']);
			$view->assign('meta_keywords', $meta_tags['meta_keywords']);
		}
	}
	if (!empty($_SESSION['current_category_id'])){
		$_SESSION['continue_url'] = "categories.view?category_id=$_SESSION[current_category_id]";

		if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
        {
            $memcache = $GLOBALS['memcache'];
            $key = md5($product_id.'-onedaysaleparent_ids');
            if($mem_value = $memcache->get($key)){
                $parent_ids = $mem_value;       
            }else{
                $parent_ids = fn_explode('/', db_get_field("SELECT id_path FROM ?:categories IGNORE INDEX(p_category_id) WHERE category_id = ?i", $_SESSION['current_category_id']));
                $status = $memcache->set($key, $parent_ids, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time'));// or die ("Failed to save data at the server");
                if(!$status){
                    $memcache->delete($key);
                }
            }   
        }else{
            $parent_ids = fn_explode('/', db_get_field("SELECT id_path FROM ?:categories IGNORE INDEX(p_category_id) WHERE category_id = ?i", $_SESSION['current_category_id']));
        }

		if (!empty($parent_ids)) {
			if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
            {
                $memcache = $GLOBALS['memcache'];
                $key = md5($product_id.'-onedaysalecats');
                if($mem_value = $memcache->get($key)){
                    $cats = $mem_value;       
                }else{
                    $cats = fn_get_category_name($parent_ids);
                    $status = $memcache->set($key, $cats, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time')); // or die ("Failed to save data at the server");
                    if(!$status){
                        $memcache->delete($key);
                    }
                }   
            }else{
                $cats = fn_get_category_name($parent_ids);
            }
			foreach($parent_ids as $c_id) {
				fn_add_breadcrumb($cats[$c_id], "categories.view?category_id=$c_id");
			}
		}
	}
	fn_add_breadcrumb($product['product']);

	if (!empty($_REQUEST['combination'])) {
		$product['combination'] = $_REQUEST['combination'];
	}
	
	if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
    {
        $memcache = $GLOBALS['memcache'];
        $key = md5($product_id.'-onedaysaleallowed_payment_method');
        if($mem_value = $memcache->get($key)){
            $allowed_payment_method = $mem_value;       
        }else{
            $allowed_payment_method = db_get_field("select is_cod from cscart_products where product_id = '".$_REQUEST['product_id']."'");
            $status = $memcache->set($key, $allowed_payment_method, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time')); // or die ("Failed to save data at the server");
            if(!$status){
                $memcache->delete($key);
            }
        }   
    }else{
        $allowed_payment_method = db_get_field("select is_cod from cscart_products where product_id = '".$_REQUEST['product_id']."'");
    }
	if(!empty($allowed_payment_method)) {
	//$allowed_payment_method = explode(',',$allowed_payment_method['payment_method']);
	if($allowed_payment_method == "Y") {
	  $product['allow_cod'] = 'yes';
	 }else {
		$product['allow_cod'] = 'no'; 
	 }
	}else{
		$product['allow_cod'] = 'no';	
	}

    //fn_gather_additional_product_data($product, true, true);
	$view->assign('product', $product);
	
	
	// If page title for this product is exist than assign it to template
	if (!empty($product['page_title'])) {
		$view->assign('page_title', $product['page_title']);
	}

	if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
    {
        $memcache = $GLOBALS['memcache'];
        $key = md5($product_id.'-onedaysalefiles');
        if($mem_value = $memcache->get($key)){
            $files = $mem_value;       
        }else{
            $files = fn_get_product_files($_REQUEST['product_id'], true);
            $status = $memcache->set($key, $files, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time')); // or die ("Failed to save data at the server");
            if(!$status){
                $memcache->delete($key);
            }
        }   
    }else{
        $files = fn_get_product_files($_REQUEST['product_id'], true);
    }
	if (!empty($files)) {
		$view->assign('files', $files);
	}

	/* [Block manager tabs] */
	$_blocks = $view->get_var('blocks');
	foreach ($_blocks as $block) {
		if (!empty($block['text_id']) && $block['text_id'] == 'product_details') {
			$tabs_group_id = $block['block_id'];
			break;
		}
	}
	if (!empty($tabs_group_id)) {
		$view->assign('tabs_block_id', $tabs_group_id);
		foreach ($_blocks as $block) {
			if (!empty($block['group_id']) && $block['group_id'] == $tabs_group_id) {
				Registry::set('navigation.tabs.block_' . $block['block_id'], array (
					'title' => $block['description'],
					'js' => true
				));
			}
		}
	}
	/* [/Block manager tabs] */

	// Set recently viewed products history
	if (!empty($_SESSION['recently_viewed_products'])) {
		$recently_viewed_product_id = array_search($_REQUEST['product_id'], $_SESSION['recently_viewed_products']);
		// Existing product will be moved on the top of the list
		if ($recently_viewed_product_id !== FALSE) {
			// Remove the existing product to put it on the top later
			unset($_SESSION['recently_viewed_products'][$recently_viewed_product_id]);
			// Re-sort the array
			$_SESSION['recently_viewed_products'] = array_values($_SESSION['recently_viewed_products']);
		}
		array_unshift($_SESSION['recently_viewed_products'], $_REQUEST['product_id']);
	} elseif (empty($_SESSION['recently_viewed_products'])) {
		$_SESSION['recently_viewed_products'] = array($_REQUEST['product_id']);
	}

	if (count($_SESSION['recently_viewed_products']) > MAX_RECENTLY_VIEWED) {
		array_pop($_SESSION['recently_viewed_products']);
	}

	// Increase product popularity
	if (empty($_SESSION['products_popularity']['viewed'][$_REQUEST['product_id']])) {
		$_data = array (
			'product_id' => $_REQUEST['product_id'],
			'viewed' => 1,
			'total' => POPULARITY_VIEW
		);
		
		db_query("INSERT INTO ?:product_popularity ?e ON DUPLICATE KEY UPDATE viewed = viewed + 1, total = total + ?i", $_data, POPULARITY_VIEW);
		
		$_SESSION['products_popularity']['viewed'][$_REQUEST['product_id']] = true;
	}

	if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
    {
        $memcache = $GLOBALS['memcache'];
        $key = md5($product_id.'-onedaysaleproduct_notification_enabled');
        if($mem_value = $memcache->get($key)){
            $product_notification_enabled = $mem_value;       
        }else{
            $product_notification_enabled = (isset($_SESSION['product_notifications']) ? (isset($_SESSION['product_notifications']['product_ids']) && in_array($_REQUEST['product_id'], $_SESSION['product_notifications']['product_ids']) ? 'Y' : 'N') : 'N');
			if ($product_notification_enabled) {
				if (($_SESSION['auth']['user_id'] == 0) && !empty($_SESSION['product_notifications']['email'])) {
					if (!db_get_field("SELECT subscription_id FROM ?:product_subscriptions WHERE product_id = ?i AND email = ?s", $_REQUEST['product_id'], $_SESSION['product_notifications']['email'])) {
						$product_notification_enabled = 'N';
					}
				} elseif (!db_get_field("SELECT subscription_id FROM ?:product_subscriptions WHERE product_id = ?i AND user_id = ?i", $_REQUEST['product_id'], $_SESSION['auth']['user_id'])) {
					$product_notification_enabled = 'N';
				}
			}
			$status = $memcache->set($key, $product_notification_enabled, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time')); // or die ("Failed to save data at the server");
			if(!$status){
				$memcache->delete($key);
			}
        }   
    }else{        
		$product_notification_enabled = (isset($_SESSION['product_notifications']) ? (isset($_SESSION['product_notifications']['product_ids']) && in_array($_REQUEST['product_id'], $_SESSION['product_notifications']['product_ids']) ? 'Y' : 'N') : 'N');
		if ($product_notification_enabled) {
			if (($_SESSION['auth']['user_id'] == 0) && !empty($_SESSION['product_notifications']['email'])) {
				if (!db_get_field("SELECT subscription_id FROM ?:product_subscriptions WHERE product_id = ?i AND email = ?s", $_REQUEST['product_id'], $_SESSION['product_notifications']['email'])) {
					$product_notification_enabled = 'N';
				}
			} elseif (!db_get_field("SELECT subscription_id FROM ?:product_subscriptions WHERE product_id = ?i AND user_id = ?i", $_REQUEST['product_id'], $_SESSION['auth']['user_id'])) {
				$product_notification_enabled = 'N';
			}
		}
	}
	$view->assign('product_notification_enabled', $product_notification_enabled);
	$view->assign('product_notification_email', (isset($_SESSION['product_notifications']) ? $_SESSION['product_notifications']['email'] : ''));
	
	$upcoming_sale_sql = "select cscart_products.product_id, cscart_product_descriptions.product, cscart_products.one_day_sale_short_text, cscart_product_descriptions.short_description   
						from cscart_products
						left join cscart_product_descriptions on (cscart_products.product_id = cscart_product_descriptions.product_id) 
						where one_day_sale_start_datetime > '".$result['one_day_sale_end_datetime']."' order by one_day_sale_start_datetime desc limit 0,1";
	$upcoming_sale = db_get_row($upcoming_sale_sql);
	if(!empty($upcoming_sale)){
		$upcoming_sale['images'] = fn_get_image_pairs($upcoming_sale['product_id'],'product','M');
	}
	$view->assign('upcoming_sale', $upcoming_sale);
	
}
elseif($mode=='useful_count')  //code by ankur for new review design
{
	$id=$_REQUEST['id'];
	$call_type=$_REQUEST['call_type'];
	$obj_type=$_REQUEST['obj_type'];
	$pro_id=$_REQUEST['obj_id'];
	if($obj_type=='P')
	{
		if($call_type=='yes')
		{
			$sql="update clues_user_product_rating set product_useful_yes_count=product_useful_yes_count+1 where id='".$id."' and product_id='".$pro_id."'";
			db_query($sql);
			$sql="update cscart_discussion_messages set useful_yes_count=useful_yes_count+1 where post_id='".$id."'";
			db_query($sql);
		}
		else if($call_type=='no')
		{
			$sql="update clues_user_product_rating set product_useful_no_count=product_useful_no_count+1 where id='".$id."' and product_id='".$pro_id."'";
			db_query($sql);
			$sql="update cscart_discussion_messages set useful_no_count=useful_no_count+1 where post_id='".$id."'";
			db_query($sql);
		}
	}	
	echo 1;
	exit;
	
}  
else if($mode=='check_fav_store')
{
	if(isset($_SESSION['auth']['user_id']) && !empty($_SESSION['auth']['user_id']))
	{
		$comp_id=$_GET['c_id'];
		$user_id=$_SESSION['auth']['user_id'];
		$fav_store=fn_get_is_fav_store($comp_id,$user_id);
		
		$all_fav_store=db_get_field("select group_concat(company_id) from clues_my_favourite_store where user_id='".$user_id."' and store_like=1");
		setcookie("scfavstore",$all_fav_store,time()+3600*48,"/",".shopclues.com");
		
		if(!isset($_COOKIE['sclikes']))
		{       
			$rand1=substr(rand(),0,6);
			$rand2=substr(rand(),0,6);
			$cookie_val=$rand1.$_SESSION['auth']['user_id'].$rand2; 
			setcookie("sclikes",$cookie_val,time()+3600*24*60,"/",".shopclues.com"); 
		}
		
		if(!empty($fav_store))
			echo 1;
		else
			echo 0;
		
		exit;
		
	}
	elseif(isset($_COOKIE['sclikes']) && !empty($_COOKIE['sclikes']))
	{
		
		$comp_id=$_GET['c_id'];
		$user_id=substr($_COOKIE['sclikes'],5,strlen($_COOKIE['sclikes'])-10);
		$fav_store=fn_get_is_fav_store($comp_id,$user_id);
		
		$all_fav_store=db_get_field("select group_concat(company_id) from clues_my_favourite_store where user_id='".$user_id."' and store_like=1");
		setcookie("scfavstore",$all_fav_store,time()+3600*48,"/",".shopclues.com");
		
		if(!empty($fav_store))
			echo 1;
		else
			echo 0;
		
		exit;
	}
	else
	{
		echo 0;
		exit;
	}
}                            //code end
else if ($mode=='seller_connect'){
       
      $product_id = base64_decode($_REQUEST['product_id']); 
      $company_id = base64_decode($_REQUEST['company_id']);
      
     if(isset($_SESSION['auth']['user_id']) && !empty($_SESSION['auth']['user_id'])){
            
        
      //fn_add_breadcrumb(fn_get_lang_var("seller_connect_breadcrumb"),'products.seller_connect');
   
       if(isset($product_id) && !empty($product_id)){
       
           $merchant_data = db_get_row("SELECT u.email,u.company_id,cpd.product as product_name,c.company as company_name from cscart_products p,cscart_companies c,cscart_users u,cscart_product_descriptions cpd where p.product_id=".$product_id." and cpd.product_id=".$product_id." and p.company_id=u.company_id and u.company_id=c.company_id");
      
           }
       
       if(isset($company_id) && !empty($company_id)){
           
           $merchant_data = db_get_row("select u.email,u.company_id,c.company as company_name from cscart_companies c,cscart_users u where c.company_id = u.company_id and u.company_id =".$company_id."");
       }
       // Getting all the issues
       
       if(isset($_REQUEST['status']) && $_REQUEST['status']=="success"){
           
           if(!empty($_REQUEST['thread_id'])){
               
              $message_success = db_get_row("select sc.message,ci.name from clues_seller_connect sc,clues_issues ci where sc.thread_id=".$_REQUEST['thread_id']." and ci.issue_id=sc.topic"); 
           
              $view->assign('message_success',$message_success['message']);
              $view->assign('message_success_option',$message_success['name']);
           }
           
           
       }
       $option_id =  db_get_array("select issue_id,parent_issue_id,name from clues_issues where type='S'");
       
       $view->assign('merchant_id',$merchant_data['company_id']);
       $view->assign('merchant_email',$merchant_data['email']);
       
       if(isset($company_id) && !empty($company_id)){
          
          $view->assign('product_id','');
          $view->assign('product_name','');
          $view->assign('company_id',$company_id);      
          
       } else {
          
           $view->assign('product_id',$product_id);
           $view->assign('product_name',$merchant_data['product_name']);
       
       }      
       
       if(isset($_REQUEST['mode_debug']) && !empty($_REQUEST['mode_debug'])){
           $view->assign('mode_debug',$_REQUEST['mode_debug']);
       } else {
           $view->assign('mode_debug','');
       }
       
       $view->assign('customer_id',$_SESSION['auth']['user_id']);
       $view->assign('option_data',$option_id);
       $view->assign('company_name',$merchant_data['company_name']);
       
       if(!empty($_SESSION['auth']['user_id'])){
           
       $user_complete_data = db_get_row("select firstname,lastname,email from cscart_users where user_id=".$_SESSION['auth']['user_id']);
       $view->assign('user_complete_data',$user_complete_data);
       }
       $user_name = $_SESSION['cart']['user_data']['firstname'].' '.$_SESSION['cart']['user_data']['lastname'][0];

       
       $view->assign('user_name',$user_name);
       
     } else {
         
         if(isset($product_id) && !empty($product_id)){
             
           $return_url = urlencode("index.php?dispatch=products.seller_connect&product_id=".$_REQUEST['product_id']);
         }else{
           $return_url = urlencode("index.php?dispatch=products.seller_connect&company_id=".$_REQUEST['company_id']);  
         }
          return array(CONTROLLER_STATUS_REDIRECT,"index.php?dispatch=auth.login_form&return_url=".$return_url);
            
      }
     
}
else if($mode =='report_issue')
{ 
//print_r($_REQUEST);die;
     if(isset($_SESSION['auth']['user_id']) && !empty($_SESSION['auth']['user_id'])){
 $report_issue_type= db_get_array("select  name,type from clues_issues where type='A'");
 $view->assign('report_issue_type',$report_issue_type);
 
	if(isset($_REQUEST['product_id']) && !empty($_REQUEST['product_id'])){
       
	   
           $product_merchant_data = db_get_row("SELECT u.email,u.company_id as merchant_id,cpd.product as product_name,cpd.product_id as product_id,c.company as company_name,c.company_id as company_id from cscart_products p,cscart_companies c,cscart_users u,cscart_product_descriptions cpd where p.product_id=".base64_decode($_REQUEST['product_id'])." and cpd.product_id=".base64_decode($_REQUEST['product_id'])." and p.company_id=u.company_id and u.company_id=c.company_id");
		              // echo "SELECT u.email,u.company_id as merchant_id,cpd.product as product_name,cpd.product_id as product_id,c.company as company_name,c.company_id as company_id from cscart_products p,cscart_companies c,cscart_users u,cscart_product_descriptions cpd where p.product_id=".base64_decode($_REQUEST['product_id'])." and cpd.product_id=".base64_decode($_REQUEST['product_id'])." and p.company_id=u.company_id and u.company_id=c.company_id";die;          
		   $view->assign('product_merchant_data',$product_merchant_data);
		     //$product_id=$product_merchant_data['product_id']; die;
			// print_r($product_merchant_data);die;
		    $view->assign('customer_id',$_SESSION['auth']['user_id']);
   
       }
	 }
	 else{
		  $return_url = urlencode("index.php?dispatch=products.report_issue&product_id=".$_REQUEST['product_id']);
		   return array(CONTROLLER_STATUS_REDIRECT,"index.php?dispatch=auth.login_form&return_url=".$return_url);
            
		 
	 }
	    if(isset($_REQUEST['status']) && $_REQUEST['status']=="success"){
			//print_r($_REQUEST);die;
		$report_issue_data= db_get_row("SELECT type ,message from clues_product_issue where id='".$_REQUEST['id']."'");
		//print_r($report_issue_data);die;
		$view->assign('report_issue_data',$report_issue_data);
		$view->assign('customer_id',$_SESSION['auth']['user_id']);
		}
	
}elseif($mode == "get_coupon"){
    $cc = Registry::get('config.3times_use_cc');
    $sql = "select number_of_use from clues_limited_promotion_use where coupon_code='".$cc."'";
    $times_used = db_get_field($sql);
    if($times_used == '' || $times_used < 3){
        $times_used += '1';
        db_query("update clues_limited_promotion_use set number_of_use='".$times_used."' where coupon_code='".$cc."'");
        $msg = str_replace('[COUPON_CODE]', $cc, fn_get_lang_var('three_time_use_coupon'));
    }else{
        $cc = Registry::get('config.m3times_use_cc');
        $msg = str_replace('[COUPON_CODE]', $cc, fn_get_lang_var('more_than_three_time_use_coupon'));
    }
    //die;
    fn_set_notification('P', '', $msg, 'I');
    exit;
}else if($mode == 'validate_pin')
      {
          
          if(isset($_REQUEST['pincode']) && isset($_REQUEST['product_ids']))
          {
            if(strlen($_REQUEST['pincode'])!=6 || !is_numeric($_REQUEST['pincode']))
            {
                echo "-1";
                exit;
            }
          }
          
          if(isset($_REQUEST['pincode']) && isset($_REQUEST['product_ids']))
          {
              $response = array();
              setcookie("pincode", $_REQUEST['pincode'],time()+3600*24*365,'/','.shopclues.com');
              $is_servicable = get_servicability_type($_REQUEST['product_ids'], $_REQUEST['pincode']);
              if(isset($is_servicable))
              {
              $response['pin_result'] = $is_servicable;
              if($is_servicable == 3 || $is_servicable == 4)
              {
                  $result = json_decode(fn_get_shipping_time($_REQUEST['pincode'],$_REQUEST['product_ids']), true);
                  $curdate = strtotime('now');
                  $estdate = $curdate + $result['shipping_time']['total_shipping_time'];
                  $response['fdate'] = date('l j M',$estdate);
                  $response['sdate'] = date('l j M',$estdate+Registry::get('config.pdd_buffer_time'));
                  echo json_encode($response);
                  //print_r($result);
              }
              }
              exit;
          }

      }

elseif ($mode == 'period') {
    $price = $_REQUEST['price'];
    $sql = "SELECT period,fee FROM clues_payment_options_emi_pgw WHERE payment_option_id = ".$_REQUEST['payment_id'];
    $result = db_get_array($sql);
    $x = '<table border="0" width="100%"><tr>';
    for($i=0;$i<count($result);$i++)
    {
       $x .= '<td align="center"><div><span style="font:bold 18px/22px tahoma,trebuchet ms, ubuntu;">'.fn_get_lang_var('rs').number_format(ceil(($price + $price *$result[$i]['fee']/100)/$result[$i]['period'])).'</span><br/><span style="font-size:12px; font-family:tahoma, ubuntu; color:#666;">'.$result[$i]['period'].fn_get_lang_var('emi_plan').'</span></div></td>';
    }
    $x .='</tr></table>';
    //echo json_encode($x);
    echo $x;
    exit;
}

//Added by shashi kant to show recently_viewed_history homepage
elseif ($mode == 'recently_viewed_data'){ 
    
        $limit = Registry::get('config.recently_view_product_limit');      
        $produc_id_first_six = $_REQUEST['product_id'];
        $produc_id_first_six = ltrim($produc_id_first_six , ',');
        $produc_id_first_six = rtrim($produc_id_first_six , ',');
        $produc_id_first_six = explode(',', $produc_id_first_six);
        
        $produc_id_latest= array_values(array_unique($produc_id_first_six));

        $k=0;
        for ($i=0;$i<=$limit;$i++)
         {
             if($produc_id_latest[$i]!='')
             $produc_id_latest1[$k++]=$produc_id_latest[$i];
         }
         
        $product_id_first_six_data = implode(',' , $produc_id_latest1);
        
        foreach($produc_id_latest1 as $row)
        {
            $product_final_arr[$row]=$row;
        }    
        
        if(!empty($product_id_first_six_data)){
        $product_data = db_get_hash_array("SELECT p.product_id as id, REPLACE(REPLACE(REPLACE(pd.product, '\n', ''), '', ''), '\r','') as product, p.list_price as list_price,
                                        min(pp.price) as price, 
                                        p.third_price,
                                        concat('images/thumbnails/',floor(if(i.image_id!=0, i.image_id,il.image_id)/1000),'/160/160/',REPLACE(REPLACE(image_path, '\n', ''), '\r', '')) as image_url,
                                        avg(pr.product_rating) as rating,
                                        REPLACE(REPLACE(ps.name, '\n', ''), '\r', '') as seo_name,
                                        p.last_update,
                                        p.status
                                        FROM
                                        cscart_products p
                                        LEFT JOIN cscart_product_prices pp ON pp.product_id = p.product_id
                                        LEFT JOIN cscart_seo_names ps ON p.product_id = ps.object_id and ps.type = 'p'
                                        LEFT JOIN cscart_products_categories pc ON p.product_id = pc.product_id and link_type = 'M'
                                        LEFT JOIN cscart_products_categories pc1 ON p.product_id = pc1.product_id
                                        LEFT JOIN cscart_product_options_inventory po on po.product_id = p.product_id
                                        LEFT JOIN cscart_category_descriptions cd2 ON cd2.category_id = pc.category_id
                                        LEFT JOIN cscart_product_descriptions pd ON pd.product_id = p.product_id
                                        LEFT JOIN cscart_images_links il ON il.object_id = p.product_id and il.object_type = 'product' and il.type = 'M'
                                        LEFT JOIN cscart_images i ON il.detailed_id = i.image_id
                                        LEFT JOIN cscart_categories cat ON cat.category_id = pc.category_id
                                        LEFT JOIN cscart_companies c ON c.company_id = p.company_id
                                        LEFT JOIN cscart_category_descriptions cd1 on cd1.category_id=SUBSTRING_INDEX(SUBSTRING_INDEX(id_path, '/',2), '/',1)
                                        LEFT JOIN cscart_categories c1 on c1.category_id = cd1.category_id
                                        LEFT JOIN cscart_categories c2 on c2.category_id = pc.category_id
                                        left join cscart_product_features_values cpfv on cpfv.product_id=p.product_id and cpfv.feature_id=53
                                        left join cscart_product_feature_variant_descriptions cpfvd on cpfvd.variant_id=cpfv.variant_id
                                        left join cscart_product_popularity pp1 on pp1.product_id = p.product_id
                                        LEFT JOIN cscart_seo_names pe ON cpfv.variant_id = pe.object_id and pe.type = 'e'
                                        LEFT JOIN clues_user_product_rating pr ON p.product_id = pr.product_id
                                        where p.product_id in ($product_id_first_six_data)
                                        group by p.product_id",'id');
        
        $final_arr =array();
        foreach($product_data as $key=>$row)
        {
            
            $image_url = explode('/', $row['image_url']);
            $image_name = array_pop($image_url);
            $image_name= preg_replace("/[^a-zA-Z0-9.]/i", "", $image_name);
            $image_url=  implode('/', $image_url);
            $product_data[$key]['image_url'] = $image_url. '/' . $image_name;
            $product_final_arr[$product_data[$key]['id']]=$product_data[$key];
        }
        
        foreach($product_final_arr as $row)
        {
            array_push($final_arr, $row);
        }
        unset($product_final_arr);
        $product_recent_data = json_encode($final_arr);
        echo($product_recent_data); exit;

}    
else 
{
    echo 'null'; exit;
}
}
//End added by shashi kant to show recently_viewed_history


/*else if($mode == 'validate_pin')
        {

          if(isset($_REQUEST['pincode']) && isset($_REQUEST['product_ids']))
          {
            if(strlen($_REQUEST['pincode'])!=6 || !is_numeric($_REQUEST['pincode']))
                echo "-1";
                exit;
            }
          }
          
          if(isset($_REQUEST['pincode']) && isset($_REQUEST['product_ids']))
          {
              $response = array();
              setcookie("pincode", $_REQUEST['pincode'],time()+3600*24*365,'/','.shopclues.com');
              $is_servicable = get_servicability_type($_REQUEST['product_ids'], $_REQUEST['pincode']);
              if(isset($is_servicable))
              {
              $response['pin_result'] = $is_servicable;
              if($is_servicable == 3 || $is_servicable == 4)
              {
                  $result = json_decode(fn_get_shipping_time($_REQUEST['pincode'],$_REQUEST['product_ids']), true);
                  $curdate = strtotime('now');
                  $estdate = $curdate + $result['shipping_time']['total_shipping_time'];
                  $response['fdate'] = date('l j M',$estdate);
                  $response['sdate'] = date('l j M',$estdate+Registry::get('config.pdd_buffer_time'));
                  echo json_encode($response);
                  //print_r($result);
              }
              }
              exit;
          }

      }

                setcookie("pincode", $_REQUEST['pincode'],time()+3600*24*365,'/','.shopclues.com');
                $is_servicable = get_servicability_type($_REQUEST['product_ids'], $_REQUEST['pincode']);
                if(isset($is_servicable))
                {
                echo $is_servicable;
                }
                exit;
            }

        }*/
elseif ($mode == 'period') {
    $price = $_REQUEST['price'];
    $sql = "SELECT period,fee FROM clues_payment_options_emi_pgw WHERE payment_option_id = ".$_REQUEST['payment_id'];
    $result = db_get_array($sql);
    $x = '<table border="0" width="100%"><tr>';
    for($i=0;$i<count($result);$i++)
    {
       $x .= '<td align="center"><div><span style="font:bold 18px/22px tahoma,trebuchet ms, ubuntu;">'.fn_get_lang_var('rs').number_format(ceil(($price + $price *$result[$i]['fee']/100)/$result[$i]['period'])).'</span><br/><span style="font-size:12px; font-family:tahoma, ubuntu; color:#666;">'.$result[$i]['period'].fn_get_lang_var('emi_plan').'</span></div></td>';
    }
    $x .='</tr></table>';
    //echo json_encode($x);
    echo $x;
    exit;
}

//ap-auction mode starts here
elseif($mode=="place_bid")
{
	$error_details=array(
	2=>'There was some error while placing your bid. Kindly refresh your page and try again.',
	3=>'It seems like the auction has expired or has been disabled. Kindly refresh the page and try again.',
	4=>'It seems like the auction has expired or has been disabled. Kindly refresh the page and try again.',
	5=>'It seems like the auction has expired or has been disabled. Kindly refresh the page and try again.',
	6=>'No more bids can be placed by this user. The maximum allowed bid count for this auction has been exceeded.',
	7=>'You cannot place bid for amount less than the specified minimum amount.',
	8=>'The bid value specified is invalid.',
	9=>'The bid value is too high. Kindly specify a bid value lower than that.',
	10=>'There was some unknown error. Try reloading the page.',
	11=>'There was some error while placing your bid. Try again.'
	);
	
	$error=array('error'=>1);
	$auction_data=array();
	$proceed_flag=true;
		
	if(!empty($_POST['action']) && $_POST['action']=='place_bid')
	{
		
		if(empty($_SESSION['auth']['user_id']))
		{
			$error['error_code']='INVALID_LOGIN';
			$error['error_message']='You must be logged in to participate into auction';
			$proceed_flag=false;
		}
		
		if( $proceed_flag && !empty($_POST['auction_id']) && !empty($_POST['bid_amount']))
		{
			$bid_result=process_bid($_SESSION['auth']['user_id'],$_POST['auction_id'],$_POST['bid_amount']);
			
			if($bid_result==1)
			{
				$error['error']=0;
				$auction_data=get_auction_data_memcache($_POST['auction_id']);
			}
			else
			{
				$error['error_code']='ERROR';
				$error['error_message']=$error_details[$bid_result];
			}
			
		}
		else
		{
			$error['error']=1;
			
			if(empty($error['error_code']))
			{
				$error['error_code']='INVALID_INPUT';
				$error['error_message']='There was some error placing your bid . Please try again.';
			}
		}
	}
	
	echo json_encode(array_merge($error,$auction_data));
	exit;
}

elseif ($mode=="auction")
{
	if(!Registry::get('config.enable_auction'))
	{
		return array(CONTROLLER_STATUS_REDIRECT, $index_script);
	}
	
	//assign upcoming auctions
	$upcoming_auctions=get_upcoming_auctions();
	if(!empty($upcoming_auctions))
	{
		$view->assign('upcoming_auctions',$upcoming_auctions);
	}
		
	$sql="SELECT * FROM clues_product_auctions WHERE status=1 AND  start_date <= UNIX_TIMESTAMP() AND end_date > UNIX_TIMESTAMP() order by end_date asc LIMIT 1";
	$result=db_get_row($sql);

	if(!empty($result['id']) && !empty($result['product_id']))
	{	
		$auction_id=$result['id'];
		$product_id=$result['product_id'];
		
		$view->assign('auction_details',$result);
		
		//getting the product information from the memcache
		if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
		{
			$memcache = $GLOBALS['memcache'];
			$key = md5($product_id.'-auctionproduct');
			if($mem_value = $memcache->get($key)){
				$product = $mem_value;
			}else{
				$product = fn_get_product_data($product_id, $auth, CART_LANGUAGE, '', true, true, true, true, ($auth['area'] == 'A'));
				fn_gather_additional_product_data($product, true, true);
				$status = $memcache->set($key, $product, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time'));
				if(!$status){
					$memcache->delete($key);
				}
			}
		}
		else
		{
			$product = fn_get_product_data($product_id, $auth, CART_LANGUAGE, '', true, true, true, true, ($auth['area'] == 'A'));
			fn_gather_additional_product_data($product, true, true);
		}
		//redirecting to the index page if no product information is found
		if(empty($product))
		{
			return array(CONTROLLER_STATUS_REDIRECT, $index_script);
		}
		
		
		//getting and setting the categories of the product into the session for generation of the meta information tags
		if ((empty($_SESSION['current_category_id']) || empty($product['category_ids'][$_SESSION['current_category_id']])) && !empty($product['main_category'])) {
			if (!empty($_SESSION['breadcrumb_category_id']) && !empty($product['category_ids'][$_SESSION['breadcrumb_category_id']])) {
				$_SESSION['current_category_id'] = $_SESSION['breadcrumb_category_id'];
			} else {
				$_SESSION['current_category_id'] = $product['main_category'];
			}
		}
		
		
		//setting the meta tags information from the memcache
		if (!empty($product['meta_description']) || !empty($product['meta_keywords'])) 
		{
			$view->assign('meta_description', $product['meta_description']);
			$view->assign('meta_keywords', $product['meta_keywords']);

		} 
		else 
		{
			if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
			{
				$memcache = $GLOBALS['memcache'];
				$key = md5($product_id.'-auctionproductmeta_tags');
				if($mem_value = $memcache->get($key))
				{
					$meta_tags = $mem_value;       
				}
				else
				{
					$meta_tags = db_get_row("SELECT meta_description, meta_keywords FROM ?:category_descriptions WHERE category_id = ?i AND lang_code = ?s", $_SESSION['current_category_id'], CART_LANGUAGE);
					$status = $memcache->set($key, $meta_tags, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time'));// or die ("Failed to save data at the server");             
					if(!$status)
					{
						$memcache->delete($key);
					}
				}   
			}
			else
			{
				$meta_tags = db_get_row("SELECT meta_description, meta_keywords FROM ?:category_descriptions WHERE category_id = ?i AND lang_code = ?s", $_SESSION['current_category_id'], CART_LANGUAGE);
			}       
			if (!empty($meta_tags)) 
			{
				$view->assign('meta_description', $meta_tags['meta_description']);
				$view->assign('meta_keywords', $meta_tags['meta_keywords']);
			}
		}//end of meta tag generation
		
		
		
		//adding categories and the breadcrumbs
		if (!empty($_SESSION['current_category_id']))
		{
			$_SESSION['continue_url'] = "categories.view?category_id=$_SESSION[current_category_id]";

			if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
			{
				$memcache = $GLOBALS['memcache'];
				$key = md5($product_id.'-auctionparent_ids');
				if($mem_value = $memcache->get($key)){
					$parent_ids = $mem_value;       
				}
				else
				{
					$parent_ids = fn_explode('/', db_get_field("SELECT id_path FROM ?:categories IGNORE INDEX(p_category_id) WHERE category_id = ?i", $_SESSION['current_category_id']));
					$status = $memcache->set($key, $parent_ids, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time'));// or die ("Failed to save data at the server");
					if(!$status){
						$memcache->delete($key);
					}
				}   
			}
			else
			{
				$parent_ids = fn_explode('/', db_get_field("SELECT id_path FROM ?:categories IGNORE INDEX(p_category_id) WHERE category_id = ?i", $_SESSION['current_category_id']));
			}

				if (!empty($parent_ids)) 
				{
					if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
					{
						$memcache = $GLOBALS['memcache'];
						$key = md5($product_id.'-onedaysalecats');
						if($mem_value = $memcache->get($key))
						{
							$cats = $mem_value;       
						}
						else
						{
							$cats = fn_get_category_name($parent_ids);
							$status = $memcache->set($key, $cats, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time')); // or die ("Failed to save data at the server");
							if(!$status)
							{
								$memcache->delete($key);
							}
						}   
					}
					else
					{
						$cats = fn_get_category_name($parent_ids);
					}
					foreach($parent_ids as $c_id) 
					{
						fn_add_breadcrumb($cats[$c_id], "categories.view?category_id=$c_id");
					}
				}
		}
		fn_add_breadcrumb($product['product']);
		//categories and breadcrumbs end here
		
		
		//adding allowed payment methods
		if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
		{
			$memcache = $GLOBALS['memcache'];
			$key = md5($product_id.'-auctionproductallowed_payment_method');
			if($mem_value = $memcache->get($key)){
				$allowed_payment_method = $mem_value;       
			}
			else
			{
				$allowed_payment_method = db_get_field("select is_cod from cscart_products where product_id = '".$product_id."'");
				$status = $memcache->set($key, $allowed_payment_method, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time')); // or die ("Failed to save data at the server");
				if(!$status){
					$memcache->delete($key);
				}
			}   
		}
		else
		{
			$allowed_payment_method = db_get_field("select is_cod from cscart_products where product_id = '".$product_id."'");
		}
		
		if(!empty($allowed_payment_method)) 
		{
			if($allowed_payment_method == "Y") 
			{
				$product['allow_cod'] = 'yes';
			}else 
			{
				$product['allow_cod'] = 'no'; 
			}
		}
		else
		{
			$product['allow_cod'] = 'no';	
		}	

		$product_seo_name=db_get_field("select name from cscart_seo_names where type='P' and object_id=$product_id");
		$http_host=Registry::get("config.http_host");
		$product['product_page_link']=$http_host."/".$product_seo_name.".html";

		$view->assign('product', $product);
		//adding allowed payment methods ends here
		
		
		
		// If page title for this product is exist than assign it to template
		if (!empty($product['page_title'])) 
		{
			$view->assign('page_title', $product['page_title']);
		}
		
		
		//getting the files for the product if any
		if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
		{
			$memcache = $GLOBALS['memcache'];
			$key = md5($product_id.'-auctionproductfiles');
			if($mem_value = $memcache->get($key)){
				$files = $mem_value;       
			}else{
				$files = fn_get_product_files($product_id, true);
				$status = $memcache->set($key, $files, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time')); // or die ("Failed to save data at the server");
				if(!$status){
					$memcache->delete($key);
				}
			}   
		}else{
			$files = fn_get_product_files($product_id, true);
		}
		if (!empty($files)) {
			$view->assign('files', $files);
		}
		//product files ends here
		
		
		
		
		//adding the block manager
		/* [Block manager tabs] */
		$_blocks = $view->get_var('blocks');
		foreach ($_blocks as $block) {
			if (!empty($block['text_id']) && $block['text_id'] == 'product_details') {
				$tabs_group_id = $block['block_id'];
				break;
			}
		}
		if (!empty($tabs_group_id)) {
			$view->assign('tabs_block_id', $tabs_group_id);
			foreach ($_blocks as $block) {
				if (!empty($block['group_id']) && $block['group_id'] == $tabs_group_id) {
					Registry::set('navigation.tabs.block_' . $block['block_id'], array (
						'title' => $block['description'],
						'js' => true
					));
				}
			}
		}
		/* [/Block manager tabs] */
		//block manager ends here
		
		
		
		//setting recently viewed product history
		if (!empty($_SESSION['recently_viewed_products'])) {
			$recently_viewed_product_id = array_search($product_id, $_SESSION['recently_viewed_products']);
			// Existing product will be moved on the top of the list
			if ($recently_viewed_product_id !== FALSE) {
				// Remove the existing product to put it on the top later
				unset($_SESSION['recently_viewed_products'][$recently_viewed_product_id]);
				// Re-sort the array
				$_SESSION['recently_viewed_products'] = array_values($_SESSION['recently_viewed_products']);
			}
			array_unshift($_SESSION['recently_viewed_products'], $product_id);
		} elseif (empty($_SESSION['recently_viewed_products'])) {
			$_SESSION['recently_viewed_products'] = array($product_id);
		}

		if (count($_SESSION['recently_viewed_products']) > MAX_RECENTLY_VIEWED) {
			array_pop($_SESSION['recently_viewed_products']);
		}
		//recently viewed product history ends here
		
		
		
		// Increase product popularity
		if (empty($_SESSION['products_popularity']['viewed'][$product_id])) {
			$_data = array (
				'product_id' => $product_id,
				'viewed' => 1,
				'total' => POPULARITY_VIEW
			);
			
			db_query("INSERT INTO ?:product_popularity ?e ON DUPLICATE KEY UPDATE viewed = viewed + 1, total = total + ?i", $_data, POPULARITY_VIEW);
			
			$_SESSION['products_popularity']['viewed'][$product_id] = true;
		}
		//product popularity increase ends here
		
		
		
		//product notification
		if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
		{
			$memcache = $GLOBALS['memcache'];
			$key = md5($product_id.'-onedaysaleproduct_notification_enabled');
			if($mem_value = $memcache->get($key)){
				$product_notification_enabled = $mem_value;       
			}else{
				$product_notification_enabled = (isset($_SESSION['product_notifications']) ? (isset($_SESSION['product_notifications']['product_ids']) && in_array($_REQUEST['product_id'], $_SESSION['product_notifications']['product_ids']) ? 'Y' : 'N') : 'N');
				if ($product_notification_enabled) {
					if (($_SESSION['auth']['user_id'] == 0) && !empty($_SESSION['product_notifications']['email'])) {
						if (!db_get_field("SELECT subscription_id FROM ?:product_subscriptions WHERE product_id = ?i AND email = ?s", $product_id, $_SESSION['product_notifications']['email'])) {
							$product_notification_enabled = 'N';
						}
					} elseif (!db_get_field("SELECT subscription_id FROM ?:product_subscriptions WHERE product_id = ?i AND user_id = ?i", $product_id, $_SESSION['auth']['user_id'])) {
						$product_notification_enabled = 'N';
					}
				}
				$status = $memcache->set($key, $product_notification_enabled, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time')); // or die ("Failed to save data at the server");
				if(!$status){
					$memcache->delete($key);
				}
			}   
		}else{        
			$product_notification_enabled = (isset($_SESSION['product_notifications']) ? (isset($_SESSION['product_notifications']['product_ids']) && in_array($product_id, $_SESSION['product_notifications']['product_ids']) ? 'Y' : 'N') : 'N');
			if ($product_notification_enabled) {
				if (($_SESSION['auth']['user_id'] == 0) && !empty($_SESSION['product_notifications']['email'])) {
					if (!db_get_field("SELECT subscription_id FROM ?:product_subscriptions WHERE product_id = ?i AND email = ?s", $product_id, $_SESSION['product_notifications']['email'])) {
						$product_notification_enabled = 'N';
					}
				} elseif (!db_get_field("SELECT subscription_id FROM ?:product_subscriptions WHERE product_id = ?i AND user_id = ?i", $product_id, $_SESSION['auth']['user_id'])) {
					$product_notification_enabled = 'N';
				}
			}
		}
		$view->assign('product_notification_enabled', $product_notification_enabled);
		$view->assign('product_notification_email', (isset($_SESSION['product_notifications']) ? $_SESSION['product_notifications']['email'] : ''));
		//product notification ends here
		
	}
}	
//auction mode ends here

 function fn_create_img_path($id) {
        $x = fn_get_image_pairs($id,'product','M');
        $detailArr = explode('/',$x['detailed']['image_path']);
	$count_result = count($detailArr);
	$imagename = $detailArr[$count_result-1];
	$imageid = $detailArr[$count_result-2];
	if($imageid && $imagename)
        {
        $newurl = 'http://cdn.shopclues.com/images/thumbnails/'.$imageid.'/160/160/'.$imagename;
        }
        else {
        $product['freebee']['image'] = "http://cdn.shopclues.com/images/no_image.gif";
        }
        return $newurl;	

}

?>
