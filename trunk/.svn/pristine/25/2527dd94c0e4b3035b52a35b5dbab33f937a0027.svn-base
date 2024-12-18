<?php 

if ( !defined('AREA') ) { die('Access denied'); }

function fn_user_email_verification_update_profile($action, $user_data, $current_user_data)
{
   if($action == 'add') 
   {
	   $email_verification_code = md5($user_data['email'].$user_data['user_login']);
	   $activation_url = Registry::get('config.http_location').'/index.php?dispatch=user_email_verification.verify&code='.$email_verification_code;
	   
	   $res = db_query("UPDATE ?:users SET status = ?s, email_verification_code = ?s WHERE user_id = ?i", 'N', $email_verification_code, $user_data['user_id']);
	   
	   $user_data['email_verification_code'] = $email_verification_code;
	   $user_data['activation_url'] = $activation_url;
	   if($res)
	   {
			Registry::get('view_mail')->assign('user_data', $user_data);			
			fn_send_mail($user_data['email'], Registry::get('settings.Company.company_users_department'), 'profiles/email_verification_subj.tpl', 'profiles/email_verification.tpl', '', $user_data['lang_code']);
			unset($_SESSION['auth']);
			
			fn_set_notification('N', fn_get_lang_var('notice'), fn_get_lang_var('activate_your_account'));
		   	
			if (AREA == 'A') {
				if ($cu_id = fn_get_session_data('cu_id') && !empty($auth['user_id'])) {
					fn_delete_session_data('cu_id');
				}
			}else{
				unset($_SESSION['auth']);
				fn_redirect(Registry::get('config.http_location'));	
			}
	   }
   }
   
}
?>