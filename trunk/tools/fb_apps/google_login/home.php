<?php

	define('AREA', 'C');
	define('AREA_NAME', 'customer');
	define('ACCOUNT_TYPE','customer');
		
	require dirname(__FILE__) . '/../../../prepare.php';
	require dirname(__FILE__) . '/../../../init.php';


if($_GET != '')
{
        $express_var = $_SESSION['express'];
	$_auth = &$auth;
	$firstname = $_GET['openid_ext1_value_firstname'];
	$lastname = $_GET['openid_ext1_value_lastname'];
	$email = $_GET['openid_ext1_value_email'];
	$current_location = Registry::get('config.current_location');
        $current_location1 = Registry::get('config.current_location');
        if($_SESSION['type'] ==1)
        {
        	$current_location = Registry::get('config.current_location')."/".$_SESSION['fb_login_redirect_popup_new'];
        	unset($_SESSION['type']);
        	unset($_SESSION['fb_login_redirect_popup_new']);
        }
        else if($_SESSION['type'] ==2)
        {
        	$current_location = "http://".$_SESSION['val'];
        	unset($_SESSION['val']);
        }
        else
        {
        	$current_location = Registry::get('config.current_location')."/".$_SESSION['val'];
        	unset($_SESSION['val']);
        }

        if($express_var == 'Y')
        {
            $current_location = Registry::get('config.current_location');
            $current_location = $current_location."/index.php?dispatch=checkout.express_checkout";
        }
        unset($_SESSION['express']);
        unset($express_var);

        if($express_var == 'Y')
        {
            $current_location = Registry::get('config.current_location');
            $current_location = $current_location."/index.php?dispatch=checkout.express_checkout";
        }
        unset($_SESSION['express']);
        unset($express_var);
	
	$pass = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,6);

	$duplicate=db_get_field("select user_id from cscart_users where email='".$email."'");
	$domain = $_SERVER['HTTP_HOST'] == 'localhost' ? '' : '.shopclues.com';
	if ($duplicate == '') 
	{
		if(Registry::get('config.anniversary_status_show') == 1)
		$_SESSION['anniversary_token_id'] = TRUE;
		$insert = db_query("insert into cscart_users set firstname='".$firstname."',lastname='".$lastname."',password='".md5($pass)."',email='".$email."',referer='google'");
		
		$sql = "select user_id from cscart_users where email='".$email."' and referer='google'";
		$row = db_get_row($sql);
		fn_login_user($row['user_id']);
		fn_set_cookie_for_akamai();
                fn_set_scun_cookie('Y');
		if($_SESSION['auth']['user_id'] >0)
		{
			if(!isset($_COOKIE['sccache']) && Registry::get('config.show_cookie_akamai'))
			{
				$domain = $_SERVER['HTTP_HOST'] == 'localhost' ? '' : '.shopclues.com';
				setcookie("sccache",true,time()+1440,"/",$domain);
			}
		}
		header("Location: ".$current_location);
	}
	else
	{
             if($_REQUEST['openid_mode'] == 'cancel')
             {
                 $current_location1 = $current_location1."/login?return_url";

                 header('location:'.$current_location1);
             }
             else
             {
                 
                 fn_login_user($duplicate);
				 fn_set_cookie_for_akamai();
				 fn_set_scun_cookie('Y');
				 if($_SESSION['auth']['user_id'] >0)
				 {
				 	if(!isset($_COOKIE['sccache']) && Registry::get('config.show_cookie_akamai'))
				 	{
				 		$domain = $_SERVER['HTTP_HOST'] == 'localhost' ? '' : '.shopclues.com';
				 		setcookie("sccache",true,time()+1440,"/",$domain);
				 	}
				 }
				 header("Location: ".$current_location);
             }
		
	}
}
?>