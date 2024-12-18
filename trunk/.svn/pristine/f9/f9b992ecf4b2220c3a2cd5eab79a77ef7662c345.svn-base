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


//
// $Id: companies.php 9088 2010-03-15 10:40:51Z 2tl $
//
if ( !defined('AREA') )	{ die('Access denied');	}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	if ($mode == 'apply_for_vendor') {

		if (Registry::get('settings.Suppliers.apply_for_vendor') != 'Y') {
			return array(CONTROLLER_STATUS_NO_PAGE);
		}
		
		if (Registry::get('settings.Image_verification.use_for_apply_for_vendor_account') == 'Y' && fn_image_verification('apply_for_vendor_account', empty($_REQUEST['verification_answer']) ? '' : $_REQUEST['verification_answer']) == false) {
			fn_save_post_data();
			return array(CONTROLLER_STATUS_REDIRECT, "companies.apply_for_vendor");
		}

		$data = $_REQUEST['company_data'];

		// Added by Sudhir dt 16 Aug 2013 to track merchant agent bigin here 
                if(!empty($_REQUEST['aid'])){
			$_SESSION['referer'] = $_REQUEST['aid'];
		}

                if(!empty($_SESSION['referer'])){
			$data['referer'] = $_SESSION['referer'];
		} else {
			$track_ref = db_get_row("SELECT user_id FROM clues_merchant_call WHERE email='".$data['email']."' AND call_status='E'");
			if($track_ref){
				$data['referer'] = $track_ref['user_id'];
			} else {
				$data['referer'] = 0;
			}
		} // Added by Sudhir dt 16 Aug 2013 to track merchant agent end here 


		// Added by Sudhir dt 28 June 2012
		if(!preg_match("/^[0-9]{10}/", $data['phone'])) {
			fn_save_post_data();
			fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('error_invalid_phone'));
                        $num = rand(10,1000);
			return array(CONTROLLER_STATUS_REDIRECT, "companies.apply_for_vendor&error=phone&authcheck=".$num);
		}
		$email_if_exists = db_get_field("SELECT user_id FROM ?:users WHERE email=?s", $data['email']);
		if ($email_if_exists) {
			fn_save_post_data();
			fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('error_email_exists'));
                        $num = rand(10,1000);
			return array(CONTROLLER_STATUS_REDIRECT, "companies.apply_for_vendor&error=email&authcheck=".$num);
		}
		$company_if_exists = db_get_field("SELECT company_id FROM ?:companies WHERE company=?s", $data['company']);
		if ($company_if_exists) {
			fn_save_post_data();
			fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('error_company_exists'));
                        $num = rand(10,1000);
			return array(CONTROLLER_STATUS_REDIRECT, "companies.apply_for_vendor&error=name&authcheck=".$num);
		}
		if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $data['email'])){
				fn_save_post_data();
				fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('error_invalid_email'));
                                $num = rand(10,1000);
				return array(CONTROLLER_STATUS_REDIRECT, "companies.apply_for_vendor&error=invalidemail&authcheck=".$num);
		} // Added by Sudhir end here dt 28 June 2012

		$data['timestamp'] = TIME;
		//$data['status'] = 'N';
		$data['status'] = 'B';	// Edited by Sudhir dt 29th June 2012
		$data['request_user_id'] = !empty($auth['user_id']) ? $auth['user_id'] : 0;

		$account_data = array();
		$account_data['fields'] = isset($_REQUEST['user_data']['fields']) ? $_REQUEST['user_data']['fields'] : '';
		$data['request_account_name'] = isset($_REQUEST['company_data']['admin_firstname']) ? $_REQUEST['company_data']['admin_firstname'] : '';
		$data['request_account_data'] = serialize($account_data);
                
		$result = fn_update_company($data);

		// Added by Sudhir dt 29 June 2012
		if($result){
			$password = substr(md5(rand().rand()), 0, 6);
			
			$user_data['firstname'] = $data['admin_firstname'];
			$user_data['email'] = $data['email'];
			$user_data['password1'] = $password;
			//$user_data['password2'] = '123456';
			$user_data['status'] = 'A';
			$user_data['user_type'] = 'A';
			$user_data['user_login'] = 'A';
			$user_data['tax_exempt'] = 'N';
			$user_data['lang_code'] = 'EN';
			$user_data['company_id'] = $result;
			$user_data['phone'] = $data['phone'];
			$user_data['timestamp'] = TIME;
			$user_data['password'] = md5($password);
			//$user_data['password'] = 'e10adc3949ba59abbe56e057f20f883e';
			$user_data['is_root'] = 'N';
			$user_data['password_change_timestamp'] = 1;

			$user_id = db_query("INSERT INTO ?:users ?e" , $user_data);

			$usergroup_data = array(
				'user_id' => $user_id,
				'usergroup_id' => 5,
				'status' => 'A'
			);  
			$result = db_query("REPLACE INTO ?:usergroup_links SET ?u", $usergroup_data);
			db_query("UPDATE clues_merchant_call SET call_status='S', update_time='".time()."' WHERE user_id=".$data['referer']." AND email='".$user_data['email']."'");
		}// Added by Sudhir end here dt 29 June 2012

		if (!$result) {
			fn_save_post_data();
			fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('text_error_adding_request'));
			return array(CONTROLLER_STATUS_REDIRECT, "companies.apply_for_vendor");
		}

		fn_set_notification('N', fn_get_lang_var('information'), fn_get_lang_var('text_successful_request'));

		// Notify user department on the new vendor application
		Registry::get('view_mail')->assign('company_id', $result);
		Registry::get('view_mail')->assign('company', $data);

		// Changed by Sudhir dt 29 June 2012
		Registry::get('view_mail')->assign('user_data', $user_data);
		//fn_send_mail(Registry::get('settings.Company.company_users_department'), Registry::get('settings.Company.company_users_department'), 'companies/apply_for_vendor_notification_subj.tpl', 'companies/apply_for_vendor_notification.tpl', '', Registry::get('settings.Appearance.admin_default_language'));
		
		fn_instant_mail($data['email'], Registry::get('settings.Company.company_users_department'), 'companies/apply_for_vendor_user_notification_subj.tpl', 'companies/apply_for_vendor_user_notification.tpl');
		// Changed by Sudhir end here dt 29 June 2012

//		$return_url = !empty($_SESSION['apply_for_vendor']['return_url']) ? $_SESSION['apply_for_vendor']['return_url'] : INDEX_SCRIPT;
               
                
        $return_url= 'vendor.php?dispatch=auth.login_form&return_url=vendor.php&preloginform=true&k='.base64_encode($user_data['email']).'&v='.base64_encode($user_data['password1']);
                
		unset($_SESSION['apply_for_vendor']['return_url']);
                    
		return array(CONTROLLER_STATUS_REDIRECT, $return_url);
	}
        
        
        elseif ($mode == 'contact_form'){
                $mydate = time();
                if(!empty($_REQUEST['subject'])){
                    
                  $subj1 = db_get_row("select name,to_emails from clues_issues where issue_id=".$_REQUEST['subject']."");
                }
                
                if(!empty($_REQUEST['subissue'])){
                    
                    $subj2 = db_get_row("select name from clues_issues where issue_id=".$_REQUEST['subissue']."");
                }
                
                if(!empty($_REQUEST['sub_subissue'])){
                    
                    $subj3 = db_get_row("select name from clues_issues where issue_id=".$_REQUEST['sub_subissue']."");
                }
                
                if(!empty($_REQUEST['subsubissue'])){
                    
                    $subj4 = db_get_row("select name from clues_issues where issue_id=".$_REQUEST['subsubissue']."");
                }
                
                if(!empty($_REQUEST['other_text'])){
                    
                    $subj5 = $_REQUEST['other_text'];
                }else{
                    $subj5='';
                }
                
                if(!empty($_REQUEST['other_text_issue'])){
                    
                    $subj6 = $_REQUEST['other_text_issue'];
                }else{
                    $subj6='';
                }
                
                $subj = $subj1['name'];
                
                if(!empty($subj2['name'])){
                  $subj.=' - '.$subj2['name'];
                }
                if(!empty($subj3['name'])){
                   $subj.=' - '.$subj3['name'];
                }
                if(!empty($subj4)) {
                    $subj.=' - '.$subj4['name'];
                }  
                if(!empty($subj5)) {
                    $subj.=' - '.$subj5;
                }  
                 if(!empty($subj6)) {
                    $subj.=' - '.$subj6;
                }  
                //Create ticket in Zendesk 
                require(DIR_ROOT.'/contact_us_api_calls_files/ticket_create_merchant.php');
                
                $merchant_info = db_query("Insert into clues_merchant_queries (mid,email,ticket_id,assignee_name,issue_id,comment,date)
                                 values('" . $_REQUEST['mid'] . "', '" . $_REQUEST['email'] . "','". $ticket_no ."','" . addslashes($assignee_name) . "',
                                 '" . $issue_id . "','" . addslashes($comment) . "','". $created_time ."')");

                Registry::get('view_mail')->assign('email', $_REQUEST['email']);
                Registry::get('view_mail')->assign('mid',$_REQUEST['mid']);
                Registry::get('view_mail')->assign('message', $_REQUEST['message']);
                Registry::get('view_mail')->assign('subj', $subj);
                Registry::get('view_mail')->assign('parent_issue',$subj1['name']);
                Registry::get('view_mail')->assign('sub_issue1',$subj2['name']);
                Registry::get('view_mail')->assign('sub_issue2',$subj3['name']);
                Registry::get('view_mail')->assign('sub_issue3',$subj4['name']);
                Registry::get('view_mail')->assign('sub_issue4',$subj5);
                Registry::get('view_mail')->assign('sub_issue5',$subj6);


                
                if(empty($subj1['to_emails'])){
                    
                     $to_emails = Registry::get('config.merchant_support_email');
                    
                }else{
                    
                    $to_emails = $subj1['to_emails'];
                    
                }
                
                fn_instant_mail($to_emails,  $_REQUEST['email'],'storesetup/merchant_contact_subj.tpl','storesetup/merchant_contact.tpl','',fn_get_lang_var('merchant_support_lang_head'));

                fn_set_notification('N', '', "Thank you for writing to us. An email has been sent to our support team with a copy to you on <b>".$_REQUEST['email']." </b> .
                                     We try our best to respond to you within 48 hours. 
                                     your request number is <b>".$ticket_no."</b>.");
                
                return array(CONTROLLER_STATUS_REDIRECT, 'companies.contact_form&x='.$mydate);         
        }
}

if ($mode == 'view') {
       
	$company_data = !empty($_REQUEST['company_id']) ? fn_get_company_data($_REQUEST['company_id']) : array();
        
        //Merchant last sold product_name
        $product_name = !empty($_REQUEST['company_id']) ? merchant_last_product_sold($_REQUEST['company_id']) : array();
        
        $merchant_info = !empty($_REQUEST['company_id']) ? merchant_duration_location($_REQUEST['company_id']) : array();
        
        $total_product = !empty($_REQUEST['company_id']) ? fn_product_count($_REQUEST['company_id']) : array();
        
        $total_product_sold = !empty($_REQUEST['company_id']) ? total_product_sold($_REQUEST['company_id']) : array();
       
	
       if (empty($company_data) || empty($company_data['status']) || !empty($company_data['status']) && $company_data['status'] != 'A') {
		//return array(CONTROLLER_STATUS_NO_PAGE);
		/*Modified by clues dev to redirect if product no found*/
		//fn_set_notification('N', fn_get_lang_var('notice'), fn_get_lang_var('no_merchant_found'));
		return array(CONTROLLER_STATUS_REDIRECT, $index_script);
		/*Modified by clues dev to redirect if product no found*/
	}
	
	$company_data['manifest'] = fn_get_manifest('customer', CART_LANGUAGE, $_REQUEST['company_id']);
	$company_data['terms'] = fn_sdeep_get_terms($_REQUEST['company_id']);
	
	$view->assign('company_data', $company_data);
	
        $view->assign('last_product_sold', $product_name);
        $view->assign('merchant_since',date('M Y',$merchant_info[0]['timestamp']));
        $view->assign('merchant_location',$merchant_info[0]['city']);
        $view->assign('total_product', $total_product);
        $view->assign('total_product_sold',$total_product_sold);
	// [Breadcrumbs]
	//fn_add_breadcrumb(fn_get_lang_var('all_vendors'), 'companies.catalog');
	fn_add_breadcrumb($company_data['company']);
	// [/Breadcrumbs]
        
} elseif ($mode == 'catalog') {

	$params = $_REQUEST;
	$params['status'] = 'A';
	$params['get_description'] = 'Y';

	$vendors_per_page = Registry::get('settings.Appearance.vendors_per_page');
	list($companies, $search) = fn_get_companies($params, $auth, $vendors_per_page);
	
	// get company logos and manifest
	$company_ids = array();
	$base_manifest = parse_ini_file(DIR_SKINS . Registry::get('settings.skin_name_customer') . '/' . SKIN_MANIFEST, true);
	foreach ($companies as &$company) {
		$company_ids[] = $company['company_id'];
		$company['logos'] = !empty($company['logos']) ? unserialize($company['logos']) : array();
		$company['manifest'] = array_merge($base_manifest, $company['logos']);
	}
	
	$alts = db_get_hash_single_array("SELECT object_id, object_holder, description FROM ?:common_descriptions WHERE object_id IN (?a) AND object_holder = ?s AND lang_code = ?s", array('object_id', 'description'), $company_ids, 'Customer_logo', CART_LANGUAGE);
	
	foreach ($companies as &$company) {
		$company['manifest']['Customer_logo']['alt'] = !empty($alts[$company['company_id']]) ? $alts[$company['company_id']] : $company['company'];
	}
	
	$view->assign('companies', $companies);
	$view->assign('search', $search);

	fn_add_breadcrumb(fn_get_lang_var('all_vendors'));

} elseif ($mode == 'apply_for_vendor') {
       
	if (Registry::get('settings.Suppliers.apply_for_vendor') != 'Y') {
		return array(CONTROLLER_STATUS_NO_PAGE);
	}

	// Added By Sudhir to populate merchant service agent data dt 25th July 2013 bigin here
	if(isset($_GET['name'])){
		    $view->assign('firstname',$_GET['name']);
	}
	if(isset($_GET['ph'])){
		    $view->assign('phone',$_GET['ph']);
	}
	if(isset($_GET['em'])){
		    $view->assign('email',$_GET['em']);
	} // Added By Sudhir to populate merchant service agent data dt 25th July 2013 end here

         if(isset($_SESSION['saved_post_data']['company_data'])){
            $view->assign('firstname',$_SESSION['saved_post_data']['company_data']['admin_firstname']);
            $view->assign('company',$_SESSION['saved_post_data']['company_data']['company']);
            $view->assign('phone',$_SESSION['saved_post_data']['company_data']['phone']);
            $view->assign('email',$_SESSION['saved_post_data']['company_data']['email']);
            $view->assign('city',$_SESSION['saved_post_data']['company_data']['city']);
            $view->assign('category_id',$_SESSION['saved_post_data']['company_data']['business_type']);
            $view->assign('sell_online',$_SESSION['saved_post_data']['company_data']['sell_online']);
            $view->assign('online_cat',$_SESSION['saved_post_data']['company_data']['online_cat']);
            
            }
        if (!empty($_SESSION['saved_post_data']['company_data'])) {
		foreach ((array)$_SESSION['saved_post_data'] as $k => $v) {
			$view->assign($k, $v);
		}

		unset($_SESSION['saved_post_data']['company_data']);
	}

	$profile_fields = fn_get_profile_fields('A', array(), CART_LANGUAGE, true, true);
	
	$root_categories = fn_get_subcategories();
	
	$view->assign('root_categories', $root_categories);
	$view->assign('profile_fields', $profile_fields);

	$view->assign('countries', fn_get_countries(CART_LANGUAGE, true));
	$view->assign('states', fn_get_all_states());

	fn_add_breadcrumb(fn_get_lang_var('apply_for_vendor_account'));

	$_SESSION['apply_for_vendor']['return_url'] = !empty($_REQUEST['return_previous_url']) ? $_REQUEST['return_previous_url'] : INDEX_SCRIPT;

        
}elseif ($mode== 'contact_form'){
       $issues = select_issues();
    
    if(isset($_REQUEST['email'])){
        $email = $_REQUEST['email'];
    }else{
        $email = '';
    }
    
    if(isset($_REQUEST['name']) || !empty($_REQUEST['name'])){
        $name = $_REQUEST['name'];
       
    }else{
        $name = '';
    }
    
    if(isset($_REQUEST['company_id'])){
        $mid = $_REQUEST['company_id'];
    }else{
        $mid = '';
    }
    
    if(isset($_REQUEST['phone'])){
        $phone = $_REQUEST['phone'];
    }else{
        $phone = '';
    }
    
    $view->assign('parent_issues',$issues);
    $view->assign('name',$name);
    $view->assign('email',$email);
    $view->assign('phone',$phone);
    $view->assign('company_id',$mid);
    
    fn_add_breadcrumb(fn_get_lang_var("contact_form_breadcrumb"),'storesetup.contact_form');  
    
    if(isset($_REQUEST['parent_id']) && !empty($_REQUEST['parent_id']) ){
     
      if(isset($_REQUEST['text']) && trim($_REQUEST['text'])!='other') {  
       $subissues = fn_get_subissues($_REQUEST['parent_id']);
   
       echo '<label for="subissue" class="cont_nl_address cm-required">'.
               fn_get_lang_var('subissue_type').
           ': <span class="red_astrik">*</span></label>';
    
        echo '<select  name="subissue" class="round_five profile_detail_field cont_nl_slt_width " id="subissue" style="height:30px;">';
        echo '<option value="">Select</option>';   
        foreach($subissues as $subissue){
                echo '<option name="'.$subissue['allow_free_text'].'" value="'.$subissue['issue_id'].'">'.$subissue['name'].'</option>'; 
            }
        echo '</select>';
        
     }else {

       echo '<label for="other_child_issue" class="cm-required cont_nl_address">'.
            fn_get_lang_var('other_child_text').
            ': <span class="red_astrik">*</span></label>';
       
           echo '<input type="text" name="other_text" id="other_child_issue" size="55"  class="input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100"  />'; 
       
       }
    // $view->assign('subissues',$subissues);
    die;
        
    }elseif(isset($_REQUEST['child_id']) && !empty($_REQUEST['child_id'])){
        
        //$subissues = db_get_row("select allow_free_text from clues_issues where issue_id=".$_REQUEST['child_id']." and type= 'M'");
        
        $sub_subissues = db_get_array("select name,issue_id,allow_free_text from clues_issues where parent_issue_id=".$_REQUEST['child_id']."  and type = 'M'");
       
       if(isset($_REQUEST['text']) && $_REQUEST['text']!='other'){
           if(!empty($sub_subissues)){
           echo '<label for="subissue" class="cont_nl_address cm-required">'.
               fn_get_lang_var('sub_subissue_type').
           ': <span class="red_astrik">*</span></label>';
    
         echo '<select  name="sub_subissue" class="round_five profile_detail_field cont_nl_slt_width " id="sub_subissue" style="height:30px;">';
         echo '<option value="">Select</option>';   
         foreach($sub_subissues as $subissue){
                echo '<option name="'.$subissue['allow_free_text'].'" value="'.$subissue['issue_id'].'">'.$subissue['name'].'</option>'; 
            }
        echo '</select>';
           }
       }else{ 
       echo '<label for="other_issue" class="cm-required cont_nl_address">'.
            fn_get_lang_var('other_text').
            ': <span class="red_astrik">*</span></label>';
       
       echo '<input type="text" name="other_text_issue" id="other_issue" size="55"  class="input-text round_five profile_detail_field cont_nl_inpt_width input-text" maxlength="100"  />'; 
       
       }
      die; 
    }elseif(isset($_REQUEST['sub_subissueid']) && !empty($_REQUEST['sub_subissueid'])){
           
           $sub_subissueid = db_get_array("select name,issue_id,allow_free_text from clues_issues where parent_issue_id=".$_REQUEST['sub_subissueid']."  and type = 'M'");
           
          if(isset($_REQUEST['text']) && $_REQUEST['text']!='other') { 
            if(!empty($sub_subissueid)){
            echo '<label for="subsubissue" class="cont_nl_address cm-required">'.
               fn_get_lang_var('subsubissue_type').
           ': <span class="red_astrik">*</span></label>';
    
         echo '<select  name="subsubissue" class="round_five profile_detail_field cont_nl_slt_width " id="subsubissue" style="height:30px;">';
         echo '<option value="">Select</option>';   
         foreach($sub_subissueid as $subissue){
                echo '<option name="'.$subissue['allow_free_text'].'" value="'.$subissue['issue_id'].'">'.$subissue['name'].'</option>'; 
            }
        echo '</select>';
            }
            }else{
           
       echo '<label for="other_other_issue" class="cm-required cont_nl_address">'.
            fn_get_lang_var('other_other_text').
            ': <span class="red_astrik">*</span></label>';
       
       echo '<input type="text" name="other_text_issue" id="other_other_issue" size="55"  class="input-text round_five profile_detail_field cont_nl_inpt_width input-text" maxlength="100"  />'; 
       
       }
        
      die;
    }elseif((isset($_REQUEST['parent_id']) && empty($_REQUEST['parent_id'])) || (isset($_REQUEST['child_id']) && empty($_REQUEST['child_id'])) || (isset($_REQUEST['sub_subissueid']) && empty($_REQUEST['sub_subissueid']))){  // if parent_id is empty then die
       
        die;
    }
    
}elseif($mode=='useful_count')  //code by ankur for new review design
{
	$id=$_REQUEST['id'];
	$call_type=$_REQUEST['call_type'];
	$obj_type=$_REQUEST['obj_type'];
	$pro_id=$_REQUEST['obj_id'];
	if($obj_type=='M')
	{
		if($call_type=='yes')
		{
			$sql="update clues_user_product_rating set merchant_useful_yes_count=merchant_useful_yes_count+1 where id='".$id."' and company_id='".$pro_id."'";
			db_query($sql);
			$sql="update cscart_discussion_messages set useful_yes_count=useful_yes_count+1 where post_id='".$id."'";
			db_query($sql);
		}
		else if($call_type=='no')
		{
			$sql="update clues_user_product_rating set merchant_useful_no_count=merchant_useful_no_count+1 where id='".$id."' and company_id='".$pro_id."'";
			db_query($sql);
			$sql="update cscart_discussion_messages set useful_no_count=useful_no_count+1 where post_id='".$id."'";
			db_query($sql);
		}
	}	
	echo 1;
	exit;
	
} 
elseif ($mode == 'view_all') {
   $root_categories = fn_get_subcategories(0);
   $view->assign('root_categories', $root_categories);
   
   foreach($root_categories as $category){
   	   $top_rated_sql = "select co.company_id, ccom.company, count(*) as orders from cscart_order_details cod
						left join cscart_products_categories cpc on cpc.product_id = cod.product_id
						left join cscart_categories cc on cc.category_id=cpc.category_id
						left join cscart_category_descriptions ccd on ccd.category_id = cc.category_id
						left join cscart_orders co on co.order_id = cod.order_id
						left join cscart_companies ccom on ccom.company_id = co.company_id
						where cc.id_path like '%".$category['category_id']."%' group by co.company_id order by count(*) desc";

	    if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
		{
			$memcache = $GLOBALS['memcache'];
			$companies_all_sql = "select
									cd1.category meta_category, c.company, c.company_id, c.is_trm, seo.name
									from cscart_companies c
									inner join cscart_products p on p.company_id = c.company_id
									inner join cscart_products_categories cpc on cpc.product_id=p.product_id and link_type = 'M'
									inner join cscart_categories c1 on c1.category_id = cpc.category_id
									inner join cscart_seo_names seo on seo.object_id = c.company_id and seo.type='m'
									LEFT JOIN cscart_category_descriptions cd1 on cd1.category_id=SUBSTRING_INDEX(SUBSTRING_INDEX(id_path, '/',2), '/',1)
									inner join cscart_categories c2 on c2.category_id=cd1.category_id and c2.status='A'
									where c.status = 'A' and p.status = 'A'
									group by cd1.category, c.company
									order by c2.position, c.company";
			$key = md5($companies_all_sql);
			if($mem_value = $memcache->get($key)){
				$companies_all = $mem_value;
			}else{
				$companies_all = db_get_hash_multi_array($companies_all_sql,array('meta_category'));
				$status = $memcache->set($key, $companies_all, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time'));
				if(!$status){
					$memcache->delete($key);
				}
			}   
		
	   }else{
	   $companies_all_sql =  "select
									cd1.category meta_category, c.company, c.company_id, c.is_trm, seo.name
									from cscart_companies c
									inner join cscart_products p on p.company_id = c.company_id
									inner join cscart_products_categories cpc on cpc.product_id=p.product_id and link_type = 'M'
									inner join cscart_categories c1 on c1.category_id = cpc.category_id
									inner join cscart_seo_names seo on seo.object_id = c.company_id and seo.type='m'
									LEFT JOIN cscart_category_descriptions cd1 on cd1.category_id=SUBSTRING_INDEX(SUBSTRING_INDEX(id_path, '/',2), '/',1)
									inner join cscart_categories c2 on c2.category_id=cd1.category_id and c2.status='A'
									where c.status = 'A' and p.status = 'A'
									group by cd1.category, c.company
									order by c2.position, c.company";
	   $companies_all = db_get_hash_multi_array($companies_all_sql,array('meta_category'));
	}
	$view->assign('result', $companies_all);
}}
?>