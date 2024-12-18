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

	if($mode=='fb_login')
	{ 
                if(isset($_SESSION['express']))
                {
                    unset($_SESSION['express']);
                }

		if(($_REQUEST['data'] =='valid_ses') && !isset($_REQUEST['signed_request']))

		{

			if($_REQUEST['retur_url'] !='')
			{
				$url = $_REQUEST['retur_url'];
				if((strpos($url, 'review.review') || strpos($url, 'seller_connect')) !== false)
				{
					$_SESSION['fb_login_redirect_popup_new'] = $url;
				}
			}
			
			$_SESSION['fb_return_url'] = $_REQUEST['retur_url'];

			if(isset($_SESSION['auth']['user_id']) && !empty($_SESSION['auth']['user_id']))
			{
				echo 1;
				exit;
			}
			else
			{
				echo 0;
				exit;
			}
			exit;
		}

		if(isset($_REQUEST['signed_request'])) {
			$encoded_sig = null;
			$payload = null;
			list($encoded_sig, $payload) = explode('.', $_REQUEST['signed_request'], 2);
			$sig = base64_decode(strtr($encoded_sig, '-_', '+/'));
			$data = json_decode(base64_decode(strtr($payload, '-_', '+/'), true));
			$name = $data->registration->name;
			$email= $data->registration->email;
			$location_user = addslashes($data->registration->location->name);
			$birthday = $data->registration->birthday;
			$birthday = strtotime($birthday);
			$gender    =  strtoupper(substr($data->registration->gender,0,1));

			if(Registry::get('config.captcha_email_status')=='TRUE')
			{
				$send_pwd = $data->registration->password;
				$pwd = md5($send_pwd);

			}
			else
			{
				$send_pwd =substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,6);
				$pwd = md5($send_pwd);

			}
			$return = $data->registration_metadata->fields;
			preg_match("/default':'(.*?)'/i", $return, $matches);
			if(count($matches[1])==0)
			{
				preg_match("/default':'(.*?)'/i", $return, $matches);
			} 
			$location = $matches[1];
			if(count($matches == 0))
			{
				if(!empty($_SESSION['fb_return_url']))
				{
					$location =    $_SESSION['fb_return_url'];
					unset($_SESSION['fb_return_url']);
				}
				else
				{
					$location = $_SESSION['fb_login_redirect_popup'];
					unset($_SESSION['fb_login_redirect_popup']);
				}
			}

			if(empty($location))
			{
				$location = Registry::get('config.http_location');
			}
			
			$duplicate=db_get_field("select user_id from cscart_users where email='".$email."'");
			$domain = $_SERVER['HTTP_HOST'] == 'localhost' ? '' : '.shopclues.com';
			if($duplicate=='')
			{
				if(Registry::get('config.anniversary_status_show') == 1)
				$_SESSION['anniversary_token_id'] = TRUE;
				db_query("insert into cscart_users (firstname,email,password,gender,location,birthday) values('".$name."','".$email."','".$pwd."','".$gender."','".$location_user."','".$birthday."')");
				$user_data = array('email'=>$email,'password'=>$send_pwd);
				Registry::get('view_mail')->assign('user_data', $user_data);
				fn_instant_mail($email, Registry::get('settings.Company.company_users_department'), 'profiles/create_profile_subj.tpl', 'profiles/create_profile.tpl');
				$userids=db_get_field("select user_id from cscart_users where email='".$email."'");
				fn_login_user($userids);
				fn_set_cookie_for_akamai();
				fn_set_scun_cookie('Y');
                                return array(CONTROLLER_STATUS_REDIRECT,"$location");
			}
			else
			{
				
				$duplicate_upd = db_get_field("select user_id from cscart_users where user_id=$duplicate and gender in ('M','F')");
				if($duplicate_upd == '')
				{
					db_query("update cscart_users set gender='".$gender."',location='".$location_user."',birthday='".$birthday."' where user_id=".$duplicate);
				}
				fn_login_user($duplicate);
				fn_set_cookie_for_akamai();
				fn_set_scun_cookie('Y');
				return array(CONTROLLER_STATUS_REDIRECT,"$location");
			}

		}

	}
	//
	// Login mode
	//
	if ($mode == 'login') {

		$redirect_url = '';
                $express_var = $_SESSION['express'];
		fn_set_hook('before_login', $_REQUEST, $redirect_url);
		$return_url=$_REQUEST['return_url'];
		if($return_url=='index.php')
		{
			$return_url='index.php?dispatch=profiles.myaccount'; //this is for redirecting the customer to their account page after login
		}
	
		if (!empty($redirect_url)) {
			return array(CONTROLLER_STATUS_OK, !empty($redirect_url) ? $redirect_url : $index_script);
		}
                
                if($express_var == 'Y')
                {
                    $return_url = "index.php?dispatch=checkout.express_checkout";
                }
                
                
		/*Modified by clues dev to stop user at step 2*/
		if($_REQUEST['form_name'] == 'step_one_login_form') {
                    if($express_var == 'Y'){
                        $redirect_url = "index.php?dispatch=checkout.express_checkout";
                        //unset($_SESSION['express']);
                        unset($express_var);
                    }
					if($return_url == 'dispatch=checkout.retry_payment_express'){
						$redirect_url = 'index.php?dispatch=checkout.retry_payment_express&order_id='.$_SESSION['cart']['reorder_order_id'];
					}
					else{
                            $redirect_url='index.php?dispatch=checkout.checkout&edit_step=step_two';
                    }
		}else{
			
			if($_REQUEST['type_stat']==1 && $_SESSION['fb_login_redirect_popup_new'] !='')
					{
						
						$redirect_url = $_SESSION['fb_login_redirect_popup_new'];
						unset($_REQUEST['type_stat']);
						unset($_SESSION['fb_login_redirect_popup_new']);
					}
					else
					{
						$redirect_url = $return_url;
					}
		}

		if (AREA != 'A') {
			if (Registry::get('settings.Image_verification.use_for_login') == 'Y' && fn_image_verification('login_' . $_REQUEST['form_name'], empty($_REQUEST['verification_answer']) ? '' : $_REQUEST['verification_answer']) == false) {
				$suffix = (strpos($_SERVER['HTTP_REFERER'], '?') !== false ? '&' : '?') . 'login_type=login' . (!empty($return_url) ? '&return_url=' . urlencode($return_url) : '');
				return array(CONTROLLER_STATUS_REDIRECT, "$_SERVER[HTTP_REFERER]$suffix");
			}
		}

		list($status, $user_data, $user_login, $password) = fn_auth_routines($_REQUEST, $auth);

		if ($status === false) {
                        if($_SESSION['express'] == 'Y')
                        {
                            fn_save_post_data();
                            $suffix = (!empty($return_url) ? '&return_url=' . urlencode($return_url) : '');
			    return array(CONTROLLER_STATUS_REDIRECT, "$_SERVER[HTTP_REFERER]$suffix");
                        }    
			fn_save_post_data();
			$suffix = (strpos($_SERVER['HTTP_REFERER'], '?') !== false ? '&' : '?') . 'login_type=login' . (!empty($return_url) ? '&return_url=' . urlencode($return_url) : '');
			return array(CONTROLLER_STATUS_REDIRECT, "$_SERVER[HTTP_REFERER]$suffix");
		}

	// added by Sudhir to change status dt 11th octo 2012

//		if(fn_get_company_status($user_data['company_id']) == 'B' && ACCOUNT_TYPE == 'vendor' && $user_data['last_login'] == 0){ 
                // && $user_data['last_login'] == 0 code is removed from below line so that merchant automatically gets in Pending status if it is New Status irrespective whether it's first login or 2nd.
		if(((fn_get_company_status($user_data['company_id']) == 'B') OR (fn_get_company_status($user_data['company_id']) == 'M')) && ACCOUNT_TYPE == 'vendor' && AREA == 'A' ){
			db_query("UPDATE cscart_companies SET status='P' WHERE company_id='".$user_data['company_id']."'");
		} // added by Sudhir to change status dt 11th octo 2012 end here

		// edit by lalit to redirect merchant to setup page if status pending
                // Comment this code by RAJ KUMAR on 12-11-2012 As there is no need of this code
               /* if(fn_get_company_status($user_data['company_id']) == 'P' && ACCOUNT_TYPE == 'vendor' && AREA == 'A' && $return_url=='vendor.php'){
                    $redirect_url='vendor.php';
                } */     
		//
		// Success login
		//
                if (!empty($user_data) && !empty($password) && md5($password) == $user_data['password']) {
			//
			// If customer placed orders before login, assign these orders to this account
			//
			/*Removed by chandan to
                         * remove due to order get connected to others account if user n place order as guest and login with different user id
                         */
                         /*if (!empty($auth['order_ids'])) {
				foreach ($auth['order_ids'] as $k => $v) {
					db_query("UPDATE ?:orders SET ?u WHERE order_id = ?i", array('user_id' => $user_data['user_id']), $v);
				}
			}
                        
                       Removed by chandan 
                         */

                       fn_login_user($user_data['user_id']);

			//code by ankur to set users fav store cookie when user login
                       if(!empty($auth['user_id']) && AREA=='C')
                       {
                       	$sql="select group_concat(company_id) from clues_my_favourite_store where user_id='".$auth['user_id']."' and store_like=1";

                       	$comp_id=db_get_field($sql);
                       	$domain = $_SERVER['HTTP_HOST'] == 'localhost' ? '' : '.shopclues.com';
                       	//Code added by Rahul to handle stay login starts here	
                       	if(isset($_REQUEST['stay_sign_in']) && $_REQUEST['stay_sign_in'] == 'Y' && Registry::get('config.stay_signin'))
                       	{
                       		$user_profile = fn_fetch_user_data($auth['user_id']);
                       		if($user_profile['user_verification_code'] == '')
                       		{
                       			$unique_key = fn_generate_unique_logged_in_key($user_profile);
                       			db_query("update cscart_users set user_verification_code='".$unique_key."' where user_id=".$user_data['user_id']);
                       	    	setcookie('sidk',$unique_key,time()+60*60*24*365,'',$domain);
                       		}
                       		else
                       		{
                       			$unique_key = $user_profile['user_verification_code'];
                       			setcookie('sidk',$unique_key,time()+60*60*24*365,'',$domain);
                       		}
                       	}
                       	//Code added by Rahul to handle stay login ends here
                       	setcookie("scfavstore",$comp_id,time()+3600*48,"", $domain);
			fn_set_scun_cookie();
                       	setcookie('scumd',base64_encode($auth['user_id']),time()+60*60*24*365,'',$domain);
				//setcookie("scfavstore",$comp_id,time()+3600*48);
                       }
            //code end
                       $uc_settings = fn_get_settings('Upgrade_center');

                       $data = '';
                       if (!empty($uc_settings['license_number'])) {
				// We need to linking store with helpdesk
                       	$token = fn_crc32(microtime());
                       	fn_set_setting_value('hd_request_code', $token);

                       	$request = array(
                       		'Request@action=check_license' => array(
                       			'token' => $token,
                       			'license_number' => $uc_settings['license_number'],
                       			'ver' => PRODUCT_VERSION,
                       			'store_uri' => fn_url('', 'C', 'http'),
                       			'secure_store_uri' => fn_url('', 'C', 'https'),
                       			'https_enabled' => (Registry::get('settings.General.secure_checkout') == 'Y' || Registry::get('settings.General.secure_admin') == 'Y' || Registry::get('settings.General.secure_auth') == 'Y') ? 'Y' : 'N',
                       			),
                       		);

                       	$request = '<?xml version="1.0" encoding="UTF-8"?>' . fn_array_to_xml($request);
				// Changed by Sudhir dt 20th May 2013 to stop request bigin here
                       	if(Registry::get('config.check_license')) {
                       		list($header, $data) = fn_https_request('GET', $uc_settings['updates_server'] . '/index.php?dispatch=product_updates.check_available&request=' . urlencode($request));

                       		if (empty($header)) {
                       			$data = fn_get_contents($uc_settings['updates_server'] . '/index.php?dispatch=product_updates.check_available&request=' . urlencode($request));
                       		}
                       	} else {			
						$time = time()-19800; // As there is a for 5:30 Hr in header time and current server time
						$header = array("RESPONSE" => "HTTP/1.1 200 OK", "SERVER"=> "nginx", "DATE"=>date("D, d M Y H:i:s \G\M\T", $time), "CONTENT-TYPE"=>"text/xml", "CONNECTION"=>"keep-alive","EXPIRES"=> "-1", "LAST-MODIFIED"=>date("D, d M Y H:i:s \G\M\T", $time), "CACHE-CONTROL"=>"no-store, no-cache, must-revalidate, post-check=0, pre-check=0", "PRAGMA"=>"no-cache", "CONTENT-LENGTH"=> 133);
						$data = '<?xml version="1.0" encoding="UTF-8"?><Response><License>ACTIVE</License><Updates>AVAILABLE</Updates><Messages></Messages></Response>';
				} // Changed by Sudhir dt 20th May 2013 to stop request end here
			}
			
			$updates = '';
			$messages = '';
			
			if (!empty($data)) {
				// Check if we can parse server response
				if (strpos($data, '<?xml') !== false) {
					$data = simplexml_load_string($data);
					$updates = (string) $data->Updates;
					$messages = $data->Messages;
					$data = (string) $data->License;
				}
			}

			// Set system notifications
			if (Registry::get('config.demo_mode') != true && AREA == 'A' && !defined('DEVELOPMENT')) {

				// If username equals to the password
				if ($password == $user_data['user_login']) {
					$msg = fn_get_lang_var('warning_insecure_password');
					$msg = str_replace('[link]', fn_url('profiles.update'), $msg);
					fn_set_notification('E', fn_get_lang_var('warning'), $msg, 'S', 'insecure_password');
				}
				if (empty($auth['company_id']) && !empty($auth['user_id'])) {
					// Insecure admin script
					if (Registry::get('config.admin_index') == 'admin.php') {
						fn_set_notification('E', fn_get_lang_var('warning'), fn_get_lang_var('warning_insecure_admin_script'), 'S');
					}
					
					fn_helpdesk_process_messages($messages);
					
					if (Registry::get('settings.General.auto_check_updates') == 'Y' && fn_check_user_access($auth['user_id'], 'upgrade_store')) {
						// If upgrades available
						if ($updates == 'AVAILABLE') {
							$msg = fn_get_lang_var('text_upgrade_available');
							$msg = str_replace('[link]', fn_url('upgrade_center.manage'), $msg);
							fn_set_notification('W', fn_get_lang_var('notice'), $msg, 'S', 'upgrade_center');
						}
					}

					fn_set_hook('set_admin_notification', $auth);
				}
			}

			if (!empty($_REQUEST['remember_me'])) {
				fn_set_session_data(AREA_NAME . '_user_id', $user_data['user_id'], COOKIE_ALIVE_TIME);
				fn_set_session_data(AREA_NAME . '_password', $user_data['password'], COOKIE_ALIVE_TIME);
			}

			// Set last login time
			db_query("UPDATE ?:users SET ?u WHERE user_id = ?i", array('last_login' => TIME), $user_data['user_id']);
			fn_set_cookie_for_akamai();
			$_SESSION['auth']['this_login'] = TIME;
			$_SESSION['auth']['ip'] = $_SERVER['REMOTE_ADDR'];

			// Log user successful login
			fn_log_event('users', 'session', array(
				'user_id' => $user_data['user_id']
				));
			/*modified by chandan to log cart, request, session*/
			update_cart_cookie();
			if($_REQUEST['form_name'] == 'step_one_login_form' && Registry::get('config.cart_logging')) {
				$cart_data = serialize($_SESSION['cart']);
				$session_data = serialize($_SESSION);
				$request_data = serialize($_REQUEST);
				$checkout_step = 'user login at checkout';
				$user_id = $_SESSION['auth']['user_id'];
				
				$sql = "insert into clues_carts_history (user_id, cart_data, session_data, request_data, checkout_step, session_id) values
				('".$user_id."','".addslashes($cart_data)."','".addslashes($session_data)."','".addslashes($request_data)."','".$checkout_step."','".session_id()."')";
				db_query($sql);
			}
			/*modified by chandan to log cart, request, session*/
			if (defined('AJAX_REQUEST') && Registry::get('settings.General.checkout_style') != 'multi_page') {
				$redirect_url = "checkout.checkout";
			} elseif (!empty($return_url)) {
				//$redirect_url = $return_url;//'index.php?dispatch=profiles.myaccount';//
			}
		} else {
		//
		// Login incorrect
		//
			// Log user failed login
			fn_log_event('users', 'failed_login', array (
				'user' => $user_login
				));

			$auth = array();
			fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('error_incorrect_login'));
			$suffix = (strpos($_SERVER['HTTP_REFERER'], '?') !== false ? '&' : '?') . 'login_type=login' . (!empty($return_url) ? '&return_url=' . urlencode($return_url) : '');
			return array(CONTROLLER_STATUS_REDIRECT, "$_SERVER[HTTP_REFERER]$suffix");
		}
		//unset($_SESSION['edit_step']);
		
		if (isset($data)) {
			$_SESSION['last_status'] = base64_encode($auth['user_id'] . ':' . $data);
		}

		if (!empty($_REQUEST['checkout_login']) && $_REQUEST['checkout_login'] == 'Y') {
			$profiles_num = db_get_field("SELECT COUNT(*) FROM ?:user_profiles WHERE user_id = ?i", $auth['user_id']);
			if ($profiles_num > 1 && Registry::get('settings.General.user_multiple_profiles') == 'Y') {
				$redirect_url = "checkout.customer_info";
			} else {
				$redirect_url = "checkout.checkout";
			}
		}

	}

	//
	// Recover password mode
	//
	if ($mode == 'recover_password') {

		if (!empty($_REQUEST['user_email'])) {

			$u_data = db_get_row("SELECT ?:users.user_id, ?:users.company_id, ?:users.email, ?:users.lang_code, ?:users.user_type FROM ?:users WHERE email = ?s", $_REQUEST['user_email']);

			if (!empty($u_data['email'])) {
				$_data = array (
					'object_id' => $u_data['user_id'],
					'object_type' => 'U',
					'ekey' => md5(uniqid(rand())),
					'ttl' => strtotime("+1 day")
					);

				db_query("REPLACE INTO ?:ekeys ?e", $_data);

				if ($u_data['user_type'] == 'A') {
					$zone = fn_get_account_type($u_data);
				} else {
					$zone = 'C';
				}

				$view_mail->assign('index_script', $u_data['user_type'] == 'A' ? Registry::get('config.admin_index') : Registry::get('config.customer_index'));
				$view_mail->assign('ekey', $_data['ekey']);
				$view_mail->assign('zone', $zone);

				//fn_send_mail($u_data['email'], Registry::get('settings.Company.company_users_department'), 'profiles/recover_password_subj.tpl','profiles/recover_password.tpl', '', $u_data['lang_code']);
				fn_instant_mail($u_data['email'], Registry::get('settings.Company.company_users_department'), 'profiles/recover_password_subj.tpl', 'profiles/recover_password.tpl');
				fn_set_notification('N', fn_get_lang_var('information'), fn_get_lang_var('text_password_recovery_instructions_sent'));
				if(AREA =='C'){
					$redirect_url="auth.after_recover_password";
				}
			} else {
				//fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('error_login_not_exists'));
                fn_set_notification('N', fn_get_lang_var('information'), fn_get_lang_var('text_password_recovery_instructions_sent'));
				//$redirect_url = "auth.recover_password";
                $redirect_url="auth.after_recover_password";
			}
		} else {
			//fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('error_login_not_exists'));
            fn_set_notification('N', fn_get_lang_var('information'), fn_get_lang_var('text_password_recovery_instructions_sent'));
			//$redirect_url = "auth.recover_password";
            $redirect_url="auth.after_recover_password";
		}

	}

	return array(CONTROLLER_STATUS_OK, !empty($redirect_url)? $redirect_url : $index_script);


}
//
// Perform user log out
//
if ($mode == 'logout') {
	unset($_SESSION['cod_verification_code']);

	fn_save_cart_content($_SESSION['cart'], $auth['user_id']);

	$auth = $_SESSION['auth'];

	if (!empty($auth['user_id'])) {
		// Log user logout
		fn_log_event('users', 'session', array(
			'user_id' => $auth['user_id'],
			'time' => TIME - $auth['this_login'],
			'timeout' => false
			));
	}

	unset($_SESSION['auth']);
	fn_clear_cart($_SESSION['cart'], false, true);

	fn_delete_session_data(AREA_NAME . '_user_id', AREA_NAME . '_password');

	unset($_SESSION['product_notifications']);
	
	//code by ankur to unset the fav store cookie
	if(AREA=='C')
	{
		$domain = $_SERVER['HTTP_HOST'] == 'localhost' ? '' : '.shopclues.com';

		setcookie("sclikes","",time() - 3600, '', $domain);
		setcookie("scfavstore","",time() - 3600, '', $domain);
		setcookie("sess_id1","",time() - 3600, '', $domain);
		setcookie("scics","",time() - 3600, '', $domain);
		fn_unset_scun_cookie();
		setcookie("sidk","",time() - 3600, '', $domain);
	}
	//code end

	return array(CONTROLLER_STATUS_OK, $index_script);
}

//
// Recover password mode
//
if ($mode == 'recover_password') {
	// Cleanup expired keys
	db_query("DELETE FROM ?:ekeys WHERE ttl > 0 AND ttl < ?i", TIME); // FIXME: should be moved to another place
	if (!empty($_REQUEST['ekey'])) {
		$u_id = db_get_field("SELECT object_id FROM ?:ekeys WHERE ekey = ?s AND object_type = 'U' AND ttl > ?i", $_REQUEST['ekey'], TIME);
		if (!empty($u_id)) {
			
			// Delete this key
			db_query("DELETE FROM ?:ekeys WHERE ekey = ?s", $_REQUEST['ekey']);

			$user_status = fn_login_user($u_id);

			if ($user_status == LOGIN_STATUS_OK) {
				fn_set_notification('N', fn_get_lang_var('notice'), fn_get_lang_var('text_change_password'));

				if(!empty($_SESSION['auth']['company_id'])){
					return array(CONTROLLER_STATUS_OK, "storesetup.change_password?redirect=mail");    
				}elseif (strpos($_SERVER['REQUEST_URI'], 'niTechCity.php')){
					return array(CONTROLLER_STATUS_OK, "profiles.update");
				}else{
					return array(CONTROLLER_STATUS_OK, "profiles.updatepassword?redirect=mail");
				}
			} else {
				fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('error_account_disabled'));
				return array(CONTROLLER_STATUS_OK, $index_script);
			}
		} else {
			fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('text_ekey_not_valid'));
			return array(CONTROLLER_STATUS_OK, "auth.recover_password");
		}
	}

	fn_add_breadcrumb(fn_get_lang_var('recover_password'));
}

//
// Display login form in the mainbox
//
if ($mode == 'login_form') {

	$state = db_get_array("SELECT csd.state,cs.state_id FROM `cscart_states` cs inner join cscart_state_descriptions csd on cs.state_id = csd.state_id where cs.country_code = 'IN'");

	$view->assign('states', $state);

	if (defined('AJAX_REQUEST') && empty($auth)) {
		exit;
	}

	if (!empty($auth['user_id'])) {
		return array(CONTROLLER_STATUS_REDIRECT, $index_script);
	}

	fn_add_breadcrumb(fn_get_lang_var('my_account'));

} elseif ($mode == 'password_change' && AREA == 'A') {
	if (defined('AJAX_REQUEST') && empty($auth)) {
		exit;
	}

	if (empty($auth['user_id'])) {
		return array(CONTROLLER_STATUS_REDIRECT, $index_script);
	}

	fn_add_breadcrumb(fn_get_lang_var('my_account'));

	$profile_id = 0;
	$user_data = fn_get_user_info($auth['user_id'], true, $profile_id);
	
	$view->assign('user_data', $user_data);
	$view->assign('view_mode', 'simple');
	
} elseif ($mode == 'change_login') {
	$auth = $_SESSION['auth'];

	if (!empty($auth['user_id'])) {
		// Log user logout
		fn_log_event('users', 'session', array(
			'user_id' => $auth['user_id'],
			'time' => TIME - $auth['this_login'],
			'timeout' => false
			));
	}

	unset($_SESSION['auth'], $_SESSION['cart']['user_data'],$_SESSION['cod_verification_code']);

		$domain = $_SERVER['HTTP_HOST'] == 'localhost' ? '' : '.shopclues.com';
		setcookie("sidk","",time() - 3600, '', $domain);
		setcookie("scun","",time() - 3600, '', $domain);
	
	fn_delete_session_data(AREA_NAME . '_user_id', AREA_NAME . '_password');

	return array(CONTROLLER_STATUS_OK, 'checkout.checkout');
}
elseif($mode=='after_recover_password')
{
	fn_add_breadcrumb(fn_get_lang_var('after_recover_password'));
}
function fn_auth_routines($request, $auth)
{
	$status = true;

	$user_login = (!empty($request['user_login'])) ? $request['user_login'] : '';
	$password = (!empty($_POST['password'])) ? $_POST['password']: '';
	$field = (Registry::get('settings.General.use_email_as_login') == 'Y') ? 'email' : 'user_login';

	$user_data = db_get_row("SELECT * FROM ?:users WHERE $field = ?s", $user_login);

	if (!empty($user_data)) {
		$user_data['usergroups'] = fn_get_user_usergroups($user_data['user_id']); 
	}
	
	if (!empty($user_data['user_type']) && $user_data['user_type'] != 'A' && AREA == 'A') {
		fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('error_area_access_denied'));
		$status = false;
	}

	if ((!empty($user_data['company_id']) && ACCOUNT_TYPE == 'admin') || (empty($user_data['company_id']) && ACCOUNT_TYPE == 'vendor')) {
		fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('error_area_access_denied'));
		$status = false;
	}

	if (!empty($user_data['status']) && $user_data['status'] == 'D') {
		fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('error_account_disabled'));
		$status = false;
	}

	return array($status, $user_data, $user_login, $password);
}
?>
