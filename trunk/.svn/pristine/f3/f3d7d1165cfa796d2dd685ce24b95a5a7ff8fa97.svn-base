<?php 

if ( !defined('AREA') ) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	return;
}

if(isset($_GET['code']) && $_GET['code'] != '')
{
	$security_code = $_GET['code'];
	$user_data = db_get_row("select user_id, email_verification_code from ?:users where email_verification_code = ?s AND status = ?s",$security_code, 'N');
	if($user_data)
	{
		db_query("UPDATE ?:users SET status = ?s WHERE user_id = ?i", 'A', $user_data['user_id']);
		$user_info = fn_get_user_info($user_data['user_id']);
		
		$suffix = 'update';
		list($user_id, $profile_id) = $user_info;
		
		if (Registry::get('settings.General.user_multiple_profiles') == 'Y') {
			$suffix .= "?profile_id=$profile_id";
		}
		$res = fn_login_user($user_data['user_id']);
		fn_set_notification('N', fn_get_lang_var('notice'), fn_get_lang_var('account_activated_successfully'));
		return array(CONTROLLER_STATUS_OK, "profiles." . $suffix);
	}
	else
	{
		fn_set_notification('E', fn_get_lang_var('Error'), fn_get_lang_var('account_activation_error'));
		fn_redirect(Registry::get('config.http_location'));
	}
}
else
{
	fn_set_notification('E', fn_get_lang_var('Error'), fn_get_lang_var('account_activation_error'));
	fn_redirect(Registry::get('config.http_location'));
}
?>