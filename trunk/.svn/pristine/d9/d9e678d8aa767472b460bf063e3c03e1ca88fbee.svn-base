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
// $Id: profiles.post.php 12865 2011-07-05 06:57:22Z 2tl $
//
if ( !defined('AREA') )	{ die('Access denied');	}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && (isset($_SESSION['form_token_value']) && $_REQUEST['token'] != $_SESSION['form_token_value']) && !isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('form_token_not_matched'));
    return array(CONTROLLER_STATUS_OK, $_SERVER['HTTP_REFERER']);
}else{
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
        $token = md5(Registry::get('config.http_host').Registry::get('config.session_salt').time());
        $_SESSION['form_token_value'] = $token;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
	if (AREA == 'A') {
		$_auth = NULL;
	} else {
		$_auth = &$auth;
	}
        
       if($mode == 'user_query_response') {
         //print_r($_REQUEST); die;
         $parent_id = $_REQUEST['thread_id'];
         $customer_id = $_SESSION['auth']['user_id'];
         $merchant_id = $_REQUEST['merchant_id'];
         $product_id = $_REQUEST['product_id'];
         $subject = addslashes($_REQUEST['subject']) ;
         $message = addslashes($_REQUEST['user_reply']) ;
         $topic = $_REQUEST['topic_id'];
         $timestamp = date(time());
         $open_timestamp = 0; //Update when user opens the message
         $direction = 'C2M';
   
         
         if(preg_match("/[0-9]/",$message)){
            
             fn_set_notification('E', fn_get_lang_var('Error'), fn_get_lang_var('number_is_not_allowed'),'I');
             
             return array(CONTROLLER_STATUS_REDIRECT, "profiles.user_query_response&thread_id=".$_REQUEST['thread_id']);
      
             return false;
             
         }else if(preg_match("/([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})/",$message)){
            
             fn_set_notification('E', fn_get_lang_var('Error'), fn_get_lang_var('email_is_not_allowed'),'I');
             
             return array(CONTROLLER_STATUS_REDIRECT, "profiles.user_query_response&thread_id=".$_REQUEST['thread_id']);
      
             return false;
             
         }else if(preg_match("/(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?/",$message)){
             
             fn_set_notification('E', fn_get_lang_var('Error'), fn_get_lang_var('url_is_not_allowed'),'I');
             
             return array(CONTROLLER_STATUS_REDIRECT, "profiles.user_query_response&thread_id=".$_REQUEST['thread_id']);
      
             return false;
             
             
         }
         
         $thread_id = fn_seller_connect($parent_id,$customer_id,$merchant_id,$product_id,$subject,$message,$topic,$timestamp,$open_timestamp,$direction);
      
         fn_set_notification('N', fn_get_lang_var('notice'), fn_get_lang_var('mail_sent_successfully'),'I');
      
          
          $url = Registry::get('config.current_location')."/"."vendor.php?dispatch=merchant_messages.seller_connect_reply&thread_id=".$parent_id;
           
           Registry::get('view_mail')->assign('subject', $subject);
           Registry::get('view_mail')->assign('message',$message);
           Registry::get('view_mail')->assign('url',$url);
           Registry::get('view_mail')->assign('username',$_REQUEST['user_name']);
           Registry::get('view_mail')->assign('date_time',date("m-d-Y H:i a")); 
           Registry::get('view_mail')->assign('topic_name',$topic_name['name']);
           Registry::get('view_mail')->assign('product_name',$_REQUEST['product_name']);
           
            
           // For merchant email $_REQUEST['merchant_email']
          
          $to_email = $_REQUEST['merchant_email'];
          
          fn_instant_mail($to_email, Registry::get('settings.Company.company_support_department'),'product/seller_connect_subj.tpl','product/seller_connect.tpl');
      
         // To send mail to user your query resolved
          
        $url_user_query_response = "profiles.user_query_response&thread_id=".$_REQUEST['thread_id'];
      
        return array(CONTROLLER_STATUS_REDIRECT, $url_user_query_response);
      
    }
    

	//
	// Create new user
	//
	if ($mode == 'add') {

		if (fn_is_restricted_admin($_REQUEST) == true) {
			return array(CONTROLLER_STATUS_DENIED);
		}

		if (AREA != 'A') {
    			if (Registry::get('settings.Image_verification.use_for_register') == 'Y' && fn_image_verification('register', empty($_REQUEST['verification_answer']) ? '' : $_REQUEST['verification_answer']) == false) {
				fn_save_post_data();
				$suffix = (strpos($_SERVER['HTTP_REFERER'], '?') !== false ? '&' : '?') . 'login_type=register';

				return array(CONTROLLER_STATUS_REDIRECT, $_SERVER['HTTP_REFERER'] . $suffix);
			}
		}
		
		if (isset($_REQUEST['copy_address']) && empty($_REQUEST['copy_address'])) {
			$_REQUEST['ship_to_another'] = 'Y';
		}
		if ($res = fn_update_user(0, $_REQUEST['user_data'], $_auth, !empty($_REQUEST['ship_to_another']), (AREA == 'A' ? !empty($_REQUEST['notify_customer']) : true))) {
			
			list($user_id, $profile_id) = $res;

			// Cleanup user info stored in cart
			if (!empty($_SESSION['cart']) && !empty($_SESSION['cart']['user_data']) && AREA != 'A') {
				unset($_SESSION['cart']['user_data']);
			}

			if (Registry::get('settings.General.user_multiple_profiles') == 'Y') {
				$suffix .= "?profile_id=$profile_id";
			}

			if (AREA == 'A') {
				$suffix .= "?user_id=$user_id";
			}

			// Delete anonymous authentication
			if (AREA != 'A') {
				if ($cu_id = fn_get_session_data('cu_id') && !empty($auth['user_id'])) {
					fn_delete_session_data('cu_id');
				}
			}
		} else {
			return array(CONTROLLER_STATUS_OK, "auth.login_form");
		}
		
		
		if(AREA == 'A')
		{
			return array(CONTROLLER_STATUS_OK, "profiles.update" . $suffix);
		}
		else
		{
			if(isset($_REQUEST['return_url']) && $_REQUEST['return_url'] != ''  && $_REQUEST['return_url'] != 'index.php' ){
				return array(CONTROLLER_STATUS_OK, $_REQUEST['return_url']);
			}else{
				
				$current_location = Registry::get('config.current_location')."/".$_SESSION['fb_login_redirect_popup_new'];

				if($_REQUEST['type_stat']==1 && $_SESSION['fb_login_redirect_popup_new'] !='')
				{
					unset($_REQUEST['type_stat']);
					unset($_SESSION['fb_login_redirect_popup_new']);
					header("Location: ".$current_location);
				}
				else
				{
					return array(CONTROLLER_STATUS_OK, "profiles.myaccount" . $suffix);
				}
			}
			
		}
	}

	//
	// Update user
	//
	if ($mode == 'update' || $mode == 'update_addressbook') {
               
		if (fn_is_restricted_admin($_REQUEST) == true) {
			return array(CONTROLLER_STATUS_DENIED);
		}

		$user_id = (AREA == 'A' && !empty($_REQUEST['user_id'])) ? $_REQUEST['user_id'] : $auth['user_id'];
		$suffix = '';

		if (!empty($_REQUEST['default_cc'])) {
			$cards_data = db_get_field("SELECT credit_cards FROM ?:user_profiles WHERE profile_id = ?i", $_REQUEST['profile_id']);
			if (!empty($cards_data)) {
				$cards = unserialize(fn_decrypt_text($cards_data));
				foreach ($cards as $cc_id => $val) {
					$cards[$cc_id]['default'] = $_REQUEST['default_cc'] == $cc_id ? true : false;
				}
				$cards_data = array (
					'credit_cards' => fn_encrypt_text(serialize($cards))
				);
				db_query('UPDATE ?:user_profiles SET ?u WHERE profile_id = ?i', $cards_data, $_REQUEST['profile_id']);
			}
		}
		
		if (isset($_REQUEST['copy_address']) && empty($_REQUEST['copy_address'])) {
			$_REQUEST['ship_to_another'] = 'Y';
		}
                 
                    //changed by munish on 31 oct 2013
                if($_REQUEST['mode'] == 'manage')
                {
                    for($i=0;$i<count($_REQUEST['profile_id']);$i++){ 
                        if(!is_numeric($_REQUEST['zipcode'][$i]) || !is_numeric($_REQUEST['phone'][$i])){
                            fn_set_notification('W', '', fn_get_lang_var('pincode_or_phone_not_numeric'));
                            return array(CONTROLLER_STATUS_OK, "profiles.manage_addressbook");
                        }
                    }
                    for($i=0;$i<count($_REQUEST['profile_id']);$i++)
                    { 
                        $chkquery = "SELECT b_firstname,b_lastname,b_address,b_city FROM cscart_user_profiles WHERE profile_type='P' AND profile_id=".$_REQUEST['profile_id'][$i];
                        $chkresult = db_get_row($chkquery);
                        if(!empty($chkresult))
                        {
                            if($chkresult['b_firstname'] =='' && $chkresult['b_lastname']=='' && $chkresult['b_address']=='' && $chkresult['b_city']== '')
                            {
                                $updquery = "UPDATE cscart_user_profiles SET b_firstname='".$_REQUEST['first_name'][$i]."',b_lastname='".$_REQUEST['last_name'][$i]."',b_address='".$_REQUEST['address'][$i]."',
                                            b_address_2='".$_REQUEST['address_2'][$i]."',b_city='".$_REQUEST['city'][$i]."',b_state='".$_REQUEST['state'][$i]."',
                                            b_zipcode='".$_REQUEST['zipcode'][$i]."',b_phone='".$_REQUEST['phone'][$i]."',profile_name='".$_REQUEST['profile_name'][$i]."' WHERE profile_id=".$_REQUEST['profile_id'][$i];
                                db_query($updquery);
                            }
                        }
                        $array['user_data'] = fn_make_user_data_array($_REQUEST,$i);
                        if((!preg_match('/^[A-Za-z\s]+$/',$array['user_data']['s_firstname'])) && (!preg_match('/^[A-Za-z\s]+$/',$array['user_data']['s_lastname'])) && (!preg_match('/^\d{6}$/',$array['user_data']['s_zipcode'])) && (!preg_match('/^\d{10}$/',$array['user_data']['s_phone'])))
                        {
                            fn_set_notification('E','Error','There is an error','I');
                            return array(CONTROLLER_STATUS_REDIRECT, "profiles.manage_addressbook");
                        }
                        if ($res = fn_update_user($user_id, $array['user_data'], $_auth, !empty($_REQUEST['ship_to_another']), (AREA == 'A' ? !empty($_REQUEST['notify_customer']) : true),false,$mode)) {

                                list($user_id, $profile_id) = $res;

                                // Cleanup user info stored in cart
                                if (!empty($_SESSION['cart']) && !empty($_SESSION['cart']['user_data']) && AREA != 'A') {
                                        unset($_SESSION['cart']['user_data']);
                                }

                                unset($_SESSION['saved_post_data']['user_data']);

                                if (!empty($_REQUEST['return_url'])) {
                                        return array(CONTROLLER_STATUS_OK, $_REQUEST['return_url']);			
                                }

                                if (Registry::get('settings.General.user_multiple_profiles') == 'Y') {
                                        $suffix = "?profile_id=$profile_id";
                                }
                        }
                    }
                }
                else
                {
                     if(isset($_REQUEST['user_data']['email'])){
                        $user_exist = "SELECT user_id, email from cscart_users where user_id=$user_id and email = '".$_REQUEST['user_data']['email']."'";
                        $res = db_get_row($user_exist);
                        if(empty($res)){
                            fn_set_notification('W', '', fn_get_lang_var('user_data_does_not_exist'));
                            return array(CONTROLLER_STATUS_OK, "profiles.update".$suffix);
                        }
                    }else{
                        if(isset($_REQUEST['user_data']['email'])){
                            unset($_REQUEST['user_data']['email']);
                        }
                    }
                    if ($res = fn_update_user($user_id, $_REQUEST['user_data'], $_auth, !empty($_REQUEST['ship_to_another']), (AREA == 'A' ? !empty($_REQUEST['notify_customer']) : true),false,$mode))
                    {
                        if($mode == 'update' && Registry::get('config.stay_signin'))
                        {
                            fn_update_user_verification_code($user_id);
                        }
                                list($user_id, $profile_id) = $res;

                                // Cleanup user info stored in cart
                                if (!empty($_SESSION['cart']) && !empty($_SESSION['cart']['user_data']) && AREA != 'A') {
                                        unset($_SESSION['cart']['user_data']);
                                }

                                unset($_SESSION['saved_post_data']['user_data']);

                                if (!empty($_REQUEST['return_url'])) {
                                        return array(CONTROLLER_STATUS_OK, $_REQUEST['return_url']);			
                                }

                                if (Registry::get('settings.General.user_multiple_profiles') == 'Y') {
                                        $suffix = "?profile_id=$profile_id";
                                }
                    }
                } 
		if (AREA == 'A' && !empty($_REQUEST['user_id'])) {
			$user_type = !empty($_REQUEST['user_type']) ? ('&user_type=' . $_REQUEST['user_type']) : '';
			$suffix .= "?user_id=$_REQUEST[user_id]" . $user_type;
		}

		if (!empty($_REQUEST['return_url'])) {
			$suffix .= '?return_url=' . urlencode($_REQUEST['return_url']);
		}
		if(AREA =='A')
		{
			return array(CONTROLLER_STATUS_OK, "profiles.update".$suffix);
		}
                elseif($mode == 'update_addressbook')
		{
			return array(CONTROLLER_STATUS_OK, "profiles.manage_addressbook");
		}
		else
		{       
			return array(CONTROLLER_STATUS_OK, "profiles.myaccount");
		}
		
	}
          
        if($mode == 'updatepassword'){
           
            if (fn_is_restricted_admin($_REQUEST) == true) {
                return array(CONTROLLER_STATUS_DENIED);
            }
            $user_id= $_SESSION['auth']['user_id'];
            if(!empty($user_id) && !empty($_REQUEST['passwordc'])){

                $password_check = db_get_field("SELECT password FROM cscart_users WHERE user_id = ". $user_id);
                  //echo $password_check;//die;
                if($password_check == md5($_REQUEST['passwordc']) && ($_REQUEST['password1'] == $_REQUEST['password2']) )
                {
                    db_query("UPDATE cscart_users SET password= '".md5($_REQUEST['password1'])."' WHERE user_id = ".$user_id);
                    if(Registry::get('config.stay_signin'))
                    {
                        fn_update_user_verification_code($user_id);
                    }
                    fn_set_notification('N','Password','updated successfully','I');
                    fn_redirect('index.php?dispatch=profiles.myaccount');

                }else{
                    fn_set_notification('E','Wrong','Password. Please try again','I');
                    fn_redirect('index.php?dispatch=profiles.updatepassword');
                }
             }elseif(!empty($_REQUEST['mail']) && !empty($_REQUEST['password3']) && !empty($_REQUEST['password4']) && !empty($user_id) && ($_REQUEST['password3'] == $_REQUEST['password4'])){

		if(Registry::get('config.forget_password_issue') == 'TRUE'){
            $upd_pass=db_query("UPDATE cscart_users SET password='".md5($_REQUEST['password3'])."' WHERE user_id=".$user_id);
			if(Registry::get('config.stay_signin'))
            {
                fn_update_user_verification_code($user_id);
            }
            if($upd_pass){
				fn_set_notification('N','Password','updated successfully','I');
				fn_redirect('index.php?dispatch=profiles.myaccount');
			} else {
				$query_log=db_process("UPDATE cscart_users SET password='".md5($_REQUEST['password3'])."' WHERE user_id=".$user_id);
				mail('sudhir.singh@shopclues.com, vinay.gupta@shopclues.com, mrinal@shopclues.com', 'Issue in update forget password:'.$user_id, "Password: ".md5($_REQUEST['password3'])."\r\nQuery Array:".serialize($_REQUEST)."\r\nData:".serialize($_SERVER)."\r\nQuery:".$query_log."\r\nError:".mysql_error());
				fn_redirect('index.php?dispatch=profiles.myaccount');
			}
		} else {
              db_query("UPDATE cscart_users SET password= '".md5($_REQUEST['password3'])."' WHERE user_id = ".$user_id);
              if(Registry::get('config.stay_signin'))
              {
                    fn_update_user_verification_code($user_id);
               }
              fn_set_notification('N','Password','updated successfully','I');
              fn_redirect('index.php?dispatch=profiles.myaccount');
		}

             }else{
                 fn_set_notification('E','Wrong','Password. Please try again','I');
                 fn_redirect('index.php?dispatch=profiles.updatepassword&redirect=mail');
             }
             
            
            return array(CONTROLLER_STATUS_OK, "profiles.updatepassword");
        }
        
	if ($mode == 'update_cards') {
		if (fn_is_restricted_admin($_REQUEST) == true) {
			return array(CONTROLLER_STATUS_DENIED);
		}
		$suffix = '';
		if (!empty($_REQUEST['profile_id']) && !empty($_REQUEST['payment_info'])) {
			$cards_data = db_get_field("SELECT credit_cards FROM ?:user_profiles WHERE profile_id = ?i", $_REQUEST['profile_id']);
			$cards = empty($cards_data) ? array() : unserialize(fn_decrypt_text($cards_data));

			$id = empty($_REQUEST['card_id']) ? 'cc_' . TIME : $_REQUEST['card_id'];
			$cards[$id] = $_REQUEST['payment_info'];
			$cards[$id]['default'] = empty($cards_data) ? true : (empty($_REQUEST['default_cc']) ? false : true);

			$cards_data = array (
				'credit_cards' => fn_encrypt_text(serialize($cards))
			);
			db_query('UPDATE ?:user_profiles SET ?u WHERE profile_id = ?i', $cards_data, $_REQUEST['profile_id']);

			if (Registry::get('settings.General.user_multiple_profiles') == 'Y') {
				$suffix = "?profile_id=$_REQUEST[profile_id]";
			}
			if (AREA == 'A' && !empty($_REQUEST['user_id'])) {
				$suffix .= "?user_id=$_REQUEST[user_id]";
			}
		}
		return array(CONTROLLER_STATUS_OK, "profiles.update" . $suffix);
	}

	//Code added by rahul gupta for express settings checkout starts here

	if ($mode == 'checkout_express_settings') 
	{

		$profile_id = $_SESSION['auth']['user_id'];

		if($_REQUEST['user_data']['billing_status'])
		{
			$_REQUEST['user_data']['b_state'] = $_REQUEST['user_data']['s_state'];
		}

		if(trim($_REQUEST['user_data']['s_address']) =='' ||  trim($_REQUEST['user_data']['s_firstname']) ==''  ||  trim($_REQUEST['user_data']['s_lastname']) =='' ||  trim($_REQUEST['user_data']['s_city']) ==''  || !is_numeric($_REQUEST['user_data']['s_zipcode']) || strlen($_REQUEST['user_data']['s_zipcode']) != 6 || !is_numeric($_REQUEST['user_data']['s_phone']) || strlen($_REQUEST['user_data']['s_phone']) != 10 || trim($_REQUEST['user_data']['b_address']) =='' ||  trim($_REQUEST['user_data']['b_firstname']) ==''  ||  trim($_REQUEST['user_data']['b_lastname']) =='' ||  trim($_REQUEST['user_data']['b_city']) ==''  || !is_numeric($_REQUEST['user_data']['b_zipcode']) || strlen($_REQUEST['user_data']['b_zipcode']) != 6 || !is_numeric($_REQUEST['user_data']['b_phone']) || strlen($_REQUEST['user_data']['b_phone']) != 10 || !is_numeric($_REQUEST['user_data']['payment_option_id']))
		{

			return array(CONTROLLER_STATUS_REDIRECT,$_SERVER['HTTP_REFERER']);

		}
		else
		{
			//echo "<pre>";print_r($_REQUEST['user_data']);

			if($_REQUEST['user_data']['apply_cb'])
				$apply_cb = 'Y';
			else
				$apply_cb = 'N';

			if($_REQUEST['user_data']['apply_promotions'])
				$apply_promotions = 'Y';
			else
				$apply_promotions = 'N';
			if($_REQUEST['user_data']['billing_status'])
				$billing_status = 'Y';
			else
				$billing_status = 'N';

			$entry = db_get_field("select user_id from clues_express_checkout_setup where user_id=".$profile_id);

			if(!$entry)
			{

				db_query("insert into clues_express_checkout_setup (user_id,b_firstname,b_lastname,b_address,b_address_2,b_country,b_state,b_city,b_zipcode,b_phone,s_firstname,s_lastname,s_address,s_address_2,s_country,s_state,s_city,s_zipcode,s_phone,apply_cb,apply_promotion,payment_option_id,timestamp,billing_status)
					values('".$profile_id."','".addslashes($_REQUEST['user_data']['b_firstname'])."','".addslashes($_REQUEST['user_data']['b_lastname'])."','".addslashes($_REQUEST['user_data']['b_address'])."','".addslashes($_REQUEST['user_data']['b_address_2'])."','".addslashes($_REQUEST['user_data']['b_country'])."','".addslashes($_REQUEST['user_data']['b_state'])."','".addslashes($_REQUEST['user_data']['b_city'])."','".addslashes($_REQUEST['user_data']['b_zipcode'])."','".addslashes($_REQUEST['user_data']['b_phone'])."','".addslashes($_REQUEST['user_data']['s_firstname'])."','".addslashes($_REQUEST['user_data']['s_lastname'])."','".addslashes($_REQUEST['user_data']['s_address'])."','".addslashes($_REQUEST['user_data']['s_address_2'])."' ,'".addslashes($_REQUEST['user_data']['s_country'])."','".addslashes($_REQUEST['user_data']['s_state'])."','".addslashes($_REQUEST['user_data']['s_city'])."','".addslashes($_REQUEST['user_data']['s_zipcode'])."','".addslashes($_REQUEST['user_data']['s_phone'])."','".addslashes($apply_cb)."','".addslashes($apply_promotions)."','".$_REQUEST['user_data']['payment_option_id']."','".time()."','".$billing_status."')");
			    fn_set_notification('N',fn_get_lang_var('express_added_success'));	
				return array(CONTROLLER_STATUS_REDIRECT,$_SERVER['HTTP_REFERER']);
			}
			else
			{
				db_query("update clues_express_checkout_setup set  billing_status='".$billing_status."',last_updated_by='".$profile_id."',last_update_timestamp='".time()."',b_firstname='".addslashes($_REQUEST['user_data']['b_firstname'])."',b_lastname='".addslashes($_REQUEST['user_data']['b_lastname'])."',b_address='".addslashes($_REQUEST['user_data']['b_address'])."',b_address_2='".addslashes($_REQUEST['user_data']['b_address_2'])."',b_state='".addslashes($_REQUEST['user_data']['b_state'])."',b_city='".addslashes($_REQUEST['user_data']['b_city'])."',b_zipcode='".addslashes($_REQUEST['user_data']['b_zipcode'])."',b_phone='".addslashes($_REQUEST['user_data']['b_phone'])."',s_firstname='".addslashes($_REQUEST['user_data']['s_firstname'])."',s_lastname='".addslashes($_REQUEST['user_data']['s_lastname'])."',s_address='".addslashes($_REQUEST['user_data']['s_address'])."',s_address_2='".addslashes($_REQUEST['user_data']['s_address_2'])."',b_country='".addslashes($_REQUEST['user_data']['b_country'])."',s_country='".addslashes($_REQUEST['user_data']['s_country'])."',s_state='".addslashes($_REQUEST['user_data']['s_state'])."',s_city='".addslashes($_REQUEST['user_data']['s_city'])."',s_zipcode='".addslashes($_REQUEST['user_data']['s_zipcode'])."',s_phone='".addslashes($_REQUEST['user_data']['s_phone'])."',apply_cb='".addslashes($apply_cb)."',apply_promotion='".addslashes($apply_promotions)."',payment_option_id='".$_REQUEST['user_data']['payment_option_id']."' where user_id=".$entry);
			    fn_set_notification('N',fn_get_lang_var('express_update_success'));
				return array(CONTROLLER_STATUS_REDIRECT,$_SERVER['HTTP_REFERER']);
			}

		}

	}

	//Code added by rahul gupta for express settings checkout ends here 
}

//Code added by rahul gupta for express settings checkout starts here

if ($mode == 'checkout_express_settings') 
{	
	if (!empty($auth['user_id'])) {
		$view->assign('countries', fn_get_countries(CART_LANGUAGE, true));
		if(isset($_REQUEST['id']) && is_numeric($_REQUEST['id']))
		{

			$payment_option=db_get_array("select * from clues_payment_options po where po.payment_type_id='".$_REQUEST['id']."' and po.status='A'");
			$x = '<option  value="">Select one option</option>';
			foreach($payment_option as $key=>$ids)
			{
				//echo "<pre>";print_r($ids);
				$x .= "<option  value='".$ids['payment_option_id']."'>".$ids['name']."</option>";
			}
			echo $x;
			exit;
		}
		if(isset($_REQUEST['id']) && $_REQUEST['id'] =='')
		{
			echo "";
			exit;
		}

		$view->assign('states', fn_get_all_states());
		$user_session = db_get_row("select * from clues_express_checkout_setup where user_id=".$_SESSION['auth']['user_id']);
		if($user_session)
		{
			$payment_option=db_get_row("select * from clues_payment_options po where po.payment_option_id='".$user_session['payment_option_id']."'");
			$paymnt_data = db_get_array("select * from clues_payment_options po where po.payment_type_id='".$payment_option['payment_type_id']."'");
			$status_show = 'update';
			$view->assign('express_fields', $user_session);
			$view->assign('pymnt_options', $payment_option);
			$view->assign('paymnt_data',$paymnt_data);
			$view->assign('status_show', $status_show);
		}
		else
		{
			$status_show = 'add';
			$view->assign('status_show', $status_show);
		}
	}

	else {
		return array(CONTROLLER_STATUS_REDIRECT, "auth.login_form?return_url=" . urlencode(Registry::get('config.current_url')));
	}

}

	//Code added by rahul gupta for express settings checkout ends here 

if ($mode == 'add' || $mode == 'update' || $mode == 'update_addressbook' || $mode == 'updatepassword') {

	if (AREA == 'A' && fn_is_restricted_admin($_REQUEST) == true) {
		return array(CONTROLLER_STATUS_DENIED);
	}

	$uid = 0;
	$user_data = array();
	$profile_id = empty($_REQUEST['profile_id']) ? 0 : $_REQUEST['profile_id'];
	if (AREA == 'A') {
		$uid = empty($_REQUEST['user_id']) ? (($mode == 'add') ? '' : $auth['user_id']) : $_REQUEST['user_id'];
	} elseif ($mode == 'update') {
		fn_add_breadcrumb(fn_get_lang_var(($mode == 'add') ? 'new_profile' : 'editing_profile'));
		$uid = $auth['user_id'];
	}elseif($mode == 'update_addressbook'){
            fn_add_breadcrumb(fn_get_lang_var('address_book'),"profiles.manage_addressbook");
            fn_add_breadcrumb(fn_get_lang_var((isset($_REQUEST['profile']) && $_REQUEST['profile'] == 'new') ? 'new_address' : 'editing_address'));
            $uid = $auth['user_id'];
    }elseif($mode == 'updatepassword'){

          if($auth['user_id']!=0)  {

            fn_add_breadcrumb(fn_get_lang_var('updatepassword'),"profiles.updatepassword");

          }

          else{
            fn_redirect($index_script);

        }

    }
       
	if (!empty($_SESSION['saved_post_data']['user_data'])) {
		foreach ((array)$_SESSION['saved_post_data'] as $k => $v) {
			$view->assign($k, $v);
		}

		$user_data = $_SESSION['saved_post_data']['user_data'];
		unset($_SESSION['saved_post_data']['user_data']);

	} else {
		if ($mode == 'update' || $mode == 'update_addressbook') {
          
			if (!empty($profile_id)) {
				$is_allowed = db_get_field("SELECT user_id FROM ?:user_profiles WHERE user_id = ?i AND profile_id = ?i", $uid, $profile_id);
				if (empty($is_allowed)) {

					return array(CONTROLLER_STATUS_REDIRECT, "profiles.update" . (!empty($_REQUEST['user_id']) ? "?user_id=$_REQUEST[user_id]" : ''));
				}
			}

 
			if (!empty($profile_id)) {
				$user_data = fn_get_user_info($uid, true, $profile_id);
			} elseif (!empty($_REQUEST['profile']) && $_REQUEST['profile'] == 'new') {
				$user_data = fn_get_user_info($uid, false, $profile_id);                 
			} else {
				$user_data = fn_get_user_info($uid, true, $profile_id);
			}

			if (empty($user_data)) {
				return array(CONTROLLER_STATUS_NO_PAGE);
			}
		}

		if ($mode == 'add' && !empty($_SESSION['cart']) && !empty($_SESSION['cart']['user_data']) && AREA != 'A') {
			$user_data = $_SESSION['cart']['user_data'];
		}
	}

	$user_type = (!empty($_REQUEST['user_type'])) ? ($_REQUEST['user_type']) : (!empty($user_data['user_type']) ? $user_data['user_type'] : 'C');
	if (AREA == 'A') {
		fn_add_breadcrumb(fn_get_lang_var('users'), "profiles.manage.reset_view");
		fn_add_breadcrumb(fn_get_lang_var('search_results'), "profiles.manage.last_view");
		fn_add_breadcrumb(fn_get_user_type_description($user_type, true), "profiles.manage?user_type=" . $user_type);
	} else {
		Registry::set('navigation.tabs.general', array (
			'title' => fn_get_lang_var('general'),
			'js' => true
		));
		if (($mode == 'update' || $mode == 'update_addressbook') && Registry::get('settings.General.user_store_cc') == 'Y') {
			Registry::set('navigation.tabs.credit_cards', array (
				'title' => fn_get_lang_var('credit_cards'),
				'js' => true
			));
			$credit_cards = fn_get_static_data_section('C', true, 'credit_card');
			$view->assign('credit_cards', $credit_cards);
			$card_names = array();
			foreach ($credit_cards as $val) {
				$card_names[$val['param']] = $val['descr'];
			}
			$view->assign('card_names', $card_names);
			$view->assign('profile_cards', empty($user_data['credit_cards']) ? array() : unserialize(fn_decrypt_text($user_data['credit_cards'])));
		}
	}
	$usergroups = fn_get_usergroups((!empty($user_data['user_type']) && $user_data['user_type'] == 'A' ? 'F' : 'C'), CART_LANGUAGE);
	if (AREA != 'A' && Registry::get('settings.General.allow_usergroup_signup') != 'Y') {
		$hide_tab = true;
		if (!empty($user_data['usergroups'])) {
			foreach ($user_data['usergroups'] as $_user_group) {
				if ($_user_group['status'] == 'A') {
					$hide_tab = false;
					break;
				}
			}
		}
		if ($hide_tab) {
			$usergroups = array();
		}
	}
	$user_data['user_type'] = empty($user_data['user_type']) ? 'C' : $user_data['user_type'];
	$user_data['user_id'] = empty($user_data['user_id']) ? '0' : $user_data['user_id'];
	// FIXME
	$auth['is_root'] = isset($auth['is_root']) ? $auth['is_root'] : '';

	if (($mode == 'update' || $mode == 'update_addressbook') && 
		(
			AREA == 'A' 
			&& 
			(
				($user_data['user_type'] != 'A' && !defined('COMPANY_ID'))
				|| 
				($user_data['user_type'] == 'A' && !defined('COMPANY_ID') && $auth['is_root'] == 'Y' && (!empty($user_data['company_id']) || (empty($user_data['company_id']) && $user_data['is_root'] != 'Y')))
				||
				($user_data['user_type'] == 'A' && defined('COMPANY_ID') && $auth['is_root'] == 'Y' && $user_data['user_id'] != $auth['user_id'] && $user_data['company_id'] == COMPANY_ID)
			) 
			|| 
			AREA != 'A' && $user_data['user_type'] != 'A' && !empty($usergroups)
		)
	) {
		Registry::set('navigation.tabs.usergroups', array (
			'title' => fn_get_lang_var('usergroups'),
			'js' => true
		));
	}
	$view->assign('usergroups', $usergroups);
	$profile_fields = fn_get_profile_fields($user_type);
	$view->assign('user_type', $user_type);
	$view->assign('profile_fields', $profile_fields);
	$view->assign('user_data', $user_data);
	//print_r($user_data);die;
	$view->assign('ship_to_another', fn_check_shipping_billing($user_data, $profile_fields));
	$view->assign('titles', fn_get_static_data_section('T'));
	$view->assign('countries', fn_get_countries(CART_LANGUAGE, true));
	$view->assign('states', fn_get_all_states());
	$view->assign('uid', $uid);
	if (Registry::get('settings.General.user_multiple_profiles') == 'Y' && !empty($uid)) {
		$view->assign('user_profiles', fn_get_user_profiles($uid));
	}

// Delete profile
} elseif ($mode == 'delete_profile' || $mode == 'delete_addressbook') {

	if (AREA == 'A' && (fn_is_restricted_admin($_REQUEST) == true || defined('COMPANY_ID'))) {
		return array(CONTROLLER_STATUS_DENIED);
	}

	if (AREA == 'A') {
		$uid = empty($_REQUEST['user_id']) ? $auth['user_id'] : $_REQUEST['user_id'];
	} else {
		$uid = $auth['user_id'];
	}

	$can_delete = db_get_field("SELECT profile_id FROM ?:user_profiles WHERE user_id = ?i AND profile_id = ?i AND profile_type = 'S'", $uid, $_REQUEST['profile_id']);
	if (!empty($can_delete)) {
		db_query("DELETE FROM ?:user_profiles WHERE profile_id = ?i", $_REQUEST['profile_id']);
	}
        // add return url
        $return_url =  "profiles.update?user_id=" . $uid;
        if($mode == 'delete_addressbook'){
            fn_set_notification('N','Address','deleted successfully','I');
            $return_url =  "profiles.manage_addressbook?user_id=" . $uid;
        }
        
	return array(CONTROLLER_STATUS_OK, $return_url);

} elseif ($mode == 'delete_card') {

	if (AREA == 'A' && fn_is_restricted_admin($_REQUEST) == true) {
		return array(CONTROLLER_STATUS_DENIED);
	}

	if (!empty($_REQUEST['card_id']) && !empty($_REQUEST['profile_id'])) {
		$cards_data = db_get_field("SELECT credit_cards FROM ?:user_profiles WHERE profile_id = ?i", $_REQUEST['profile_id']);
		if (!empty($cards_data)) {
			$cards = unserialize(fn_decrypt_text($cards_data));

			$is_default = $cards[$_REQUEST['card_id']]['default'];
			unset($cards[$_REQUEST['card_id']]);
			if ($is_default && !empty($cards)) {
				reset($cards);
				$cards[key($cards)]['default'] = true;
			}

			$cards_data = array (
				'credit_cards' => empty($cards) ? '' : fn_encrypt_text(serialize($cards))
			);
			db_query('UPDATE ?:user_profiles SET ?u WHERE profile_id = ?i', $cards_data, $_REQUEST['profile_id']);

			if (AREA == 'A') {
				$uid = empty($_REQUEST['user_id']) ? $auth['user_id'] : $_REQUEST['user_id'];
			} else {
				$uid = $auth['user_id'];
			}
			return array(CONTROLLER_STATUS_OK, "profiles.update?user_id=$uid&profile_id=$_REQUEST[profile_id]");
		}
	}
	exit;
} elseif ($mode == 'request_usergroup') {

	if (AREA == 'A' && fn_is_restricted_admin($_REQUEST) == true || empty($_REQUEST['status']) || empty($_REQUEST['usergroup_id'])) {
		return array(CONTROLLER_STATUS_DENIED);
	}

	$uid = $auth['user_id'];
	if (!empty($uid)) {
		$_data = array(
			'user_id' => $uid,
			'usergroup_id' => $_REQUEST['usergroup_id'],
		);

		if ($_REQUEST['status'] == 'A' || $_REQUEST['status'] == 'P') {
			$_data['status'] = 'F';

		} elseif ($_REQUEST['status'] == 'F' || $_REQUEST['status'] == 'D') {
			$_data['status'] = 'P';
			$usergroup_request = true;
		} else {
			return array(CONTROLLER_STATUS_DENIED);
		}

		db_query("REPLACE INTO ?:usergroup_links SET ?u", $_data);

		if (!empty($usergroup_request)) {
			$user_data = fn_get_user_info($uid);

			Registry::get('view_mail')->assign('user_data', $user_data);
			Registry::get('view_mail')->assign('usergroups', fn_get_usergroups('F', Registry::get('settings.Appearance.admin_default_language')));
			Registry::get('view_mail')->assign('usergroup_id', $_REQUEST['usergroup_id']);

			fn_send_mail(Registry::get('settings.Company.company_users_department'), Registry::get('settings.Company.company_users_department'), 'profiles/usergroup_request_subj.tpl', 'profiles/usergroup_request.tpl', '', Registry::get('settings.Appearance.admin_default_language'), $user_data['email']);
		}
	}

	return array(CONTROLLER_STATUS_OK, "profiles.update");
}
else if($mode=='myaccount'){
	
if (AREA == 'A' && fn_is_restricted_admin($_REQUEST) == true) {
		return array(CONTROLLER_STATUS_DENIED);
	}
	
	
	if (!empty($auth['user_id'])) {
		
		//for getting user detail
		$uid = $auth['user_id'];
		$user_data=fn_get_user_info($uid);
		
   		 $view->assign('user_profiles',$user_data);
		 //for getting clues buck use user_profiles array
		
		
		//for getting pending feedbacks
		$pend_feedback_orders=fn_sdeep_get_unreviewed_products($auth);		
		$view->assign('pend_feedback_count',count($pend_feedback_orders));
		
		//for getting coupons
		$coupons_detail=db_get_array("select coupon_code,assign_date,expiration_date from clues_customer_coupon where user_id='".$auth['user_id']."'");
		$view->assign('coupons_detail',$coupons_detail);
	
		
		$unused_coupon=array();
		$dead_orders=implode("','",Registry::get('config.dead_orders'));
		foreach($coupons_detail as $coupon)
		{
		//for getting order list
		
		$orders_list=db_get_array("select cscart_order_details.order_id,cscart_order_details.product_id,cscart_order_details.price from cscart_order_details,cscart_orders where cscart_orders.order_id=cscart_order_details.order_id and cscart_orders.user_id=?i and cscart_orders.status!='N' and cscart_orders.is_parent_order='N'  order by cscart_orders.timestamp desc",$auth['user_id']);
		
		$view->assign('item_info', $orders_list);
			//$res=db_get_row("select * from cscart_orders where user_id='".$auth['user_id']."' and find_in_set('".$coupon['coupon_code']."',coupon_codes) and status NOT IN ('".$dead_orders."')");
			$res=db_get_row("select * from cscart_orders where user_id='".$auth['user_id']."' and coupon_codes LIKE '%".$coupon['coupon_code']."%' and status NOT IN ('".$dead_orders."')");
	
                        if(empty($res))
			{
				$unused_coupon[]=$coupon['coupon_code'];
			}
			
		}
	
		$view->assign('unused_coupon',$unused_coupon);
		
		$sql="select mfs.company_id,mfs.timestamp,c.company,c.status
		from clues_my_favourite_store mfs
		inner join cscart_companies c on c.company_id=mfs.company_id
		where mfs.user_id='".$auth['user_id']."' and mfs.store_like=1 order by timestamp desc $limit
		";
		$res=db_get_array($sql);
		$view ->assign('my_stores',$res);
	
		//for getting order list
		
		$orders_list=db_get_array("select cscart_order_details.order_id,cscart_order_details.product_id,cscart_order_details.price,cscart_orders.status,cscart_orders.timestamp from cscart_order_details,cscart_orders where cscart_orders.order_id=cscart_order_details.order_id and cscart_orders.user_id=?i and cscart_orders.status!='N' and cscart_orders.is_parent_order='N'  order by cscart_orders.timestamp desc",$auth['user_id']);
		
		//code by ajay for show priority icon
		foreach($orders_list as $key=> $orders){
		$ff_priority = db_get_row(" SELECT `occasion_id`, `priority_level_id` FROM `cscart_order_details` WHERE `order_id` = '".$orders['order_id']."'");
    
                  if ( !empty($ff_priority['occasion_id']) && !empty($ff_priority['priority_level_id']) ){
				  $orders_list[$key]['ff_priority']= 'Y';
			      }else{
				  $orders_list[$key]['ff_priority']= 'N';	  
				  }
				  
		$priority_level_name = db_get_row(" SELECT `priority_level_id`, `priority_level_name`, `icon_url` , `color_code` FROM `clues_priority_levels_for_fulfillment`
                                          WHERE `priority_level_id` = '".$ff_priority['priority_level_id']."' ");	
        $orders_list[$key]['priority_level_name'] = $priority_level_name;			  
		
	   }
	   //end by ajay
	   
	  $view->assign('item_info', $orders_list);
	//for getting cart items
	$cart = & $_SESSION['cart'];
	$product_ids=array();
	$total_product=0;
  		if(!empty($cart['products']))
		{
			$count=0;
			foreach($cart['products'] as $product_detail)
			{
				$count++;
				
				if($count==7) break;
				$product_ids[]=$product_detail['product_id'];
			}
			$total_product=count($cart['products']);
			$view->assign('total_product',$total_product);
			$view->assign('product_id',$product_ids);
		}
	
	//for getting wishlist
	$wishlist = & $_SESSION['wishlist'];
	$wish_list=array();
	$total_wish=0;
		if(!empty($wishlist))
		{
			$count=0;
			foreach($wishlist['products'] as $list_detail)
			{
				$count++;
				if($count==7) break;
				$wish_list[]=$list_detail;
			}
			$total_wish=count($wishlist['products']);
		}
		$view->assign('total_wish',$total_wish);
		$view->assign('wishlist',$wish_list);
		

	}
	
	else {
		return array(CONTROLLER_STATUS_REDIRECT, "auth.login_form?return_url=" . urlencode(Registry::get('config.current_url')));
	}
	
	
	
}
else if($mode=='pending_feedback'){
	
if (AREA == 'A' && fn_is_restricted_admin($_REQUEST) == true) {
		return array(CONTROLLER_STATUS_DENIED);
	}
	$order_info=array();
	
	if (!empty($auth['user_id'])) {
		
		fn_add_breadcrumb(fn_get_lang_var('pending_feedback'));
		
		$pend_feedback_orders_with_product=fn_sdeep_get_unreviewed_products($auth);//this function return the only required detail which is to be displayed on the pending_feedback page
		
	
		$view->assign('pend_feedback_count',count($pend_feedback_orders_with_product));
		$view->assign('pend_feedback_order',$pend_feedback_orders_with_product);
		
		
	}
	else
	{
		return array(CONTROLLER_STATUS_REDIRECT, "auth.login_form?return_url=" . urlencode(Registry::get('config.current_url')));
	}
	
	
}

//Added by shashi kant to show submitted feedback on my account page
else if($mode=='submitted_feedback'){
	
if (AREA == 'A' && fn_is_restricted_admin($_REQUEST) == true) {
		return array(CONTROLLER_STATUS_DENIED);
	}
	$order_info=array();
	
	if (!empty($auth['user_id'])) {
		
		fn_add_breadcrumb(fn_get_lang_var('post_feedback'));
		
		
		$post_feedback_orders_with_product=fn_get_reviewed_products($auth);
		
		$view->assign('post_feedback_count',count($post_feedback_orders_with_product));
		$view->assign('post_feedback_order',$post_feedback_orders_with_product);	
	}
	else
	{
		return array(CONTROLLER_STATUS_REDIRECT, "auth.login_form?return_url=" . urlencode(Registry::get('config.current_url')));
	}
	
	
}
else if($mode=='my_feedbacks'){
	
if (AREA == 'A' && fn_is_restricted_admin($_REQUEST) == true) {
		return array(CONTROLLER_STATUS_DENIED);
	}
	$order_info=array();
	
	if (!empty($auth['user_id'])) {
		
		fn_add_breadcrumb(fn_get_lang_var('feedbacks'));
		
		$pend_feedback_orders_with_product=fn_sdeep_get_unreviewed_products($auth);
		$post_feedback_orders_with_product=fn_get_reviewed_products($auth);

		$view->assign('post_feedback_count',count($post_feedback_orders_with_product));
	        $view->assign('pend_feedback_count',count($pend_feedback_orders_with_product));

	}
	else
	{
		return array(CONTROLLER_STATUS_REDIRECT, "auth.login_form?return_url=" . urlencode(Registry::get('config.current_url')));
	}
	
	
}
//End added by shashi kant to show submitted feedback on my account page

// to manage address book
elseif ($mode == 'manage_addressbook') {
    if (AREA == 'A' && fn_is_restricted_admin($_REQUEST) == true) {
		return array(CONTROLLER_STATUS_DENIED);
	}
    fn_add_breadcrumb(fn_get_lang_var('address_book'));
	if (!empty($auth['user_id'])) {
		
		//for getting user detail
		$uid = $auth['user_id'];
		$user_data = fn_get_user_profiles_for_address_book($uid);
		
   		 $view->assign('user_profiles',$user_data);
        } 
        $view->assign('states', fn_get_all_states());
        
        /*$view->assign('user_types', fn_get_user_types());
	$view->assign('countries', fn_get_countries(CART_LANGUAGE, true));
	$view->assign('states', fn_get_all_states());
	$view->assign('usergroups', fn_get_usergroups('F', DESCR_SL));*/

}
else if($mode=='store')
{
	if (AREA == 'A' && fn_is_restricted_admin($_REQUEST) == true) {
		return array(CONTROLLER_STATUS_DENIED);
	}
	if (!empty($auth['user_id'])) {
		
		$sql="select mfs.company_id,mfs.timestamp,c.company,c.status
		from clues_my_favourite_store mfs
		inner join cscart_companies c on c.company_id=mfs.company_id
		where mfs.user_id='".$auth['user_id']."' and mfs.store_like=1
		
		";
		$res=db_get_array($sql);
		
		$params['page'] = empty($params['page']) ? 1 : $params['page'];
		
		$total=count($res);
		
		$arrlim=false;
		$items_per_page=Registry::get('settings.Appearance.orders_per_page');
		$limit = fn_paginate($_REQUEST['page'], $total, $items_per_page,false,$arrlim);
		
		$sql="select mfs.company_id,mfs.timestamp,c.company,c.status
		from clues_my_favourite_store mfs
		inner join cscart_companies c on c.company_id=mfs.company_id
		where mfs.user_id='".$auth['user_id']."' and mfs.store_like=1 order by timestamp desc $limit
		";
		$res=db_get_array($sql);
		
		fn_add_breadcrumb(fn_get_lang_var('my_fav_store'));
		
		
		$view->assign('my_stores',$res);
	} else {
		return array(CONTROLLER_STATUS_REDIRECT, "auth.login_form?return_url=" . urlencode(Registry::get('config.current_url')));
	}
}
else if($mode=='like')
{
	$cur_url=Registry::get('config.current_url');
	$ref_url=$_REQUEST['ret_url'];
	if (!empty($auth['user_id'])) {
		
		$sql="select id from clues_my_favourite_store where user_id='".$auth['user_id']."' and company_id='".$_REQUEST['c_id']."'";
		$id=db_get_field($sql);
		if(!empty($id))
		{
			$sql="update clues_my_favourite_store set user_id='".$auth['user_id']."',company_id='".$_REQUEST['c_id']."',product_id='".$_REQUEST['p_id']."',store_like=1 where id='".$id."'";
		}
		else
		{
			$sql="insert into clues_my_favourite_store set user_id='".$auth['user_id']."',company_id='".$_REQUEST['c_id']."',product_id='".$_REQUEST['p_id']."',store_like=1";
		}
		db_query($sql);
		
		$all_fav_store=db_get_field("select group_concat(company_id) from clues_my_favourite_store where user_id='".$auth['user_id']."' and store_like=1");
		setcookie("scfavstore",$all_fav_store,time()+3600*48,"/",".shopclues.com");
		
		return array(CONTROLLER_STATUS_REDIRECT,$ref_url );
	}
	else if(!empty($_COOKIE['sclikes']))
	{
		$user_id=substr($_COOKIE['sclikes'],6,strlen($_COOKIE['sclikes'])-12);
		
		$sql="select id from clues_my_favourite_store where user_id='".$user_id."' and company_id='".$_REQUEST['c_id']."'";
		$id=db_get_field($sql);
		if(!empty($id))
		{
			$sql="update clues_my_favourite_store set user_id='".$user_id."',company_id='".$_REQUEST['c_id']."',product_id='".$_REQUEST['p_id']."',store_like=1 where id='".$id."'";
		}
		else
		{
			$sql="insert into clues_my_favourite_store set user_id='".$user_id."',company_id='".$_REQUEST['c_id']."',product_id='".$_REQUEST['p_id']."',store_like=1";
		}
		db_query($sql);
		
		$all_fav_store=db_get_field("select group_concat(company_id) from clues_my_favourite_store where user_id='".$user_id."' and store_like=1");
		setcookie("scfavstore",$all_fav_store,time()+3600*48,"/",".shopclues.com");
		
		return array(CONTROLLER_STATUS_REDIRECT,$ref_url );
	}
	else
	{
		$erro_msg=fn_get_lang_var('need_to_login');
		fn_set_notification('E',fn_get_lang_var('notice'),$erro_msg);
		return array(CONTROLLER_STATUS_REDIRECT, "auth.login_form?return_url=" . urlencode($cur_url));
	}
	
}
else if($mode=='unlike')
{
	$cur_url=Registry::get('config.current_url');
	$ref_url=$_REQUEST['ret_url'];
	if (!empty($auth['user_id'])) {
		
		$sql="update clues_my_favourite_store set store_like=0 where user_id='".$auth['user_id']."' and company_id='".$_REQUEST['c_id']."'";
		
		db_query($sql);
		
		$all_fav_store=db_get_field("select group_concat(company_id) from clues_my_favourite_store where user_id='".$auth['user_id']."' and store_like=1");
		setcookie("scfavstore",$all_fav_store,time()+3600*48,"/",".shopclues.com");
		return array(CONTROLLER_STATUS_REDIRECT,$ref_url );
	}
	else if(!empty($_COOKIE['sclikes']))
	{
		$user_id=substr($_COOKIE['sclikes'],6,strlen($_COOKIE['sclikes'])-12);
		$sql="update clues_my_favourite_store set store_like=0 where user_id='".$user_id."' and company_id='".$_REQUEST['c_id']."'";
		
		db_query($sql);
		
		$all_fav_store=db_get_field("select group_concat(company_id) from clues_my_favourite_store where user_id='".$user_id."' and store_like=1");
		setcookie("scfavstore",$all_fav_store,time()+3600*48,"/",".shopclues.com");
		
		return array(CONTROLLER_STATUS_REDIRECT,$ref_url );
	}
	else
	{
		$erro_msg=fn_get_lang_var('need_to_login');
		fn_set_notification('E',fn_get_lang_var('notice'),$erro_msg);
		return array(CONTROLLER_STATUS_REDIRECT, "auth.login_form?return_url=" . urlencode($cur_url));
	}
}
else if($mode=='unlike_merchant')
{
	$ref_url=$_REQUEST['ret_url'];
	if (!empty($auth['user_id'])) {
		
		$sql="update clues_my_favourite_store set store_like=0 where user_id='".$auth['user_id']."' and company_id='".$_REQUEST['c_id']."'";
		
		db_query($sql);
		$all_fav_store=db_get_field("select group_concat(company_id) from clues_my_favourite_store where user_id='".$auth['user_id']."' and store_like=1");
		setcookie("scfavstore",$all_fav_store,time()+3600*48,"/",".shopclues.com");
		return array(CONTROLLER_STATUS_REDIRECT,$ref_url );
	}
}
else if($mode=='user_query'){
    
    if(empty($_SESSION['auth']['user_id'])){
        return array(CONTROLLER_STATUS_OK, $index_script);
    } else {
     //breadcrumb
        
     fn_add_breadcrumb(fn_get_lang_var("my_messages_view"),$mode == 'user_query' ? '' : "profiles.user_query");
   
     if(isset($_REQUEST['order']) && $_REQUEST['order']!=''){
              $order = ($_REQUEST['order']=='desc')?'asc':'desc';
    } else {
         $order= 'desc';
    }
        
    if(isset($_REQUEST['field']) && $_REQUEST['field'] == 'date'){
              $order_by = 'sc.timestamp';
             
    }elseif (isset($_REQUEST['field']) && $_REQUEST['field'] == 'subject'){
              $order_by= 'sc.subject';
              
    }elseif (isset($_REQUEST['field']) && $_REQUEST['field'] == 'to'){
              $order_by= 'c.company';
              
    }else{
            $order_by='sc.timestamp';
    }
 
    
    $messages_data = "select sc.thread_id,sc.subject,ci.name,c.company,sc.timestamp,sc.open_timestamp from clues_seller_connect sc,clues_issues ci,cscart_users u,cscart_companies c where 
                                    sc.customer_id=".$_SESSION['auth']['user_id']." and u.company_id = sc.merchant_id and c.company_id = sc.merchant_id and sc.parent_id=0 and ci.issue_id=sc.topic ";
   
    
    $messages_data .= " order by ".$order_by." ".$order;
    
    //echo $messages_data; die;
    $user_name = $_SESSION['cart']['user_data']['firstname'].' '.$_SESSION['cart']['user_data']['lastname'];
    
    $view->assign('user_name',$user_name);
    
    $messages_data = db_get_array($messages_data);
    
    $view->assign('user_data',$messages_data);
    
    $view->assign('order',$order);
    
    
    }
    
}else if ($mode == 'user_query_response'){
    
    if(empty($_SESSION['auth']['user_id'])){
        return array(CONTROLLER_STATUS_OK, $index_script);
    }else{
        
    fn_add_breadcrumb(fn_get_lang_var("user_query_reply"),$mode == 'user_query_response' ? '' : "profiles.user_query_response");
   
    $message_thread= db_get_row("select sc.subject,sc.message,sc.product_id,sc.open_timestamp,sc.merchant_id,sc.timestamp,sc.topic,ci.name,c.company,c.email from clues_seller_connect sc, cscart_companies c,clues_issues ci 
                                       where  sc.thread_id=".$_REQUEST['thread_id']."  and sc.topic=ci.issue_id and sc.customer_id=".$_SESSION['auth']['user_id']." and c.company_id = sc.merchant_id");
      
      if($message_thread['product_id']!=0){
        
        $product_name = db_get_row("select product from cscart_product_descriptions where product_id=".$message_thread['product_id']);
        
        $view->assign('product_complete_name',$product_name['product']);
        }
  
    // All child threads
    //echo "select parent_id,open_timestamp from clues_seller_connect where parent_id=".$_REQUEST['thread_id']." and merchant_id=".$_SESSION['auth']['company_id'].""; 
    $messages_reply = db_get_array("select sc.message,sc.merchant_id,sc.parent_id,sc.open_timestamp,sc.timestamp,sc.direction,u.firstname,u.lastname,c.company from clues_seller_connect sc,cscart_users u,cscart_companies c where
                                    sc.parent_id=".$_REQUEST['thread_id']." and sc.customer_id=".$_SESSION['auth']['user_id']." and u.company_id=sc.merchant_id and c.company_id=sc.merchant_id");
  
    
    $view->assign('message_thread',$message_thread);
    $view->assign('message',$messages_reply);
    $view->assign('topic',$message_thread['name']);
    $view->assign('topic_id',$message_thread['topic']);
    
    // update open timestamp if it is not updated
    
    if($message_thread['open_timestamp']==0){
        
        fn_update_thread_timestamp($_REQUEST['thread_id'],date(time()));
        
    }
    
    $view->assign('current_timestamp',date(time()));
    
     $user_name = $_SESSION['cart']['user_data']['firstname'].' '.$_SESSION['cart']['user_data']['lastname'][0];
     $view->assign('user_name',$user_name);
     
    }
}
function fn_get_user_saving($user_id)
{ 
	    $user_id = intval($user_id);
 if(!empty($user_id) && $user_id !== 0 && $user_id !== '0')
 {
			$dead_orders = Registry::get('config.dead_orders');
			$status_cond = "'".implode("','",$dead_orders)."'";
			$condition = " AND status NOT IN (".$status_cond.")";

			$saving= db_get_row("select sum(subtotal_discount)as sd,sum(discount)as dis FROM  `cscart_orders` where user_id='".$user_id."' $condition");
		
			return $saving;
			}
else 
return null;
}
function fn_make_user_data_array($array,$key)
{
    $x['profile_id'] = $array['profile_id'][$key];
    $x['profile_name'] = $array['profile_name'][$key];
    $x['s_firstname'] = $array['first_name'][$key];
    $x['s_lastname'] = $array['last_name'][$key];
    $x['s_address'] = $array['address'][$key];
    $x['s_address_2'] = $array['address_2'][$key];
    $x['s_city'] = $array['city'][$key];
    $x['s_state'] = $array['state'][$key];
    $x['s_zipcode'] = $array['zipcode'][$key];
    $x['s_phone'] = $array['phone'][$key];
    return $x;
}
?>
