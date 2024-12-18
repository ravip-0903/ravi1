<?php
define('AREA', 'C');
	define('AREA_NAME', 'customer');
	define('ACCOUNT_TYPE','customer');
	
	require 'fb_login/src/facebook.php';
	require dirname(__FILE__) . '/../../prepare.php';
	require dirname(__FILE__) . '/../../init.php';


$appids= Registry::get('config.shopclues_app_id_for_login');
$secret = Registry::get('config.shopclues_app_secret_for_login');

$facebook = new Facebook(array(
  'appId'  => $appids,
  'secret' => $secret,
));

$access_token = $facebook->getAccessToken(); 
$user = $facebook->getUser();

$express_var = $_SESSION['express'];

        if(isset($_REQUEST['error_reason']) && isset($_REQUEST['error']) && isset($_REQUEST['error_description']))
        {
		$current_location = $current_location."/login?return_url";

            header('location:'.$current_location);
        }

       /* if(isset($_REQUEST['code']))
        {
        	$current_location = Registry::get('config.current_location');

        	$current_location = $current_location."/index.php?dispatch=profiles.myaccount";
                
                if($express_var == 'Y')
                {
                    $current_location = Registry::get('config.current_location');
                    $current_location = $current_location."/index.php?dispatch=checkout.express_checkout";
                }

        	header('location:'.$current_location);
        }*/
        
        

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    if($access_token == $facebook->getAccessToken())
      {
            $user_profile = $facebook->api("/$user");
      }
      else
      {
          $facebook->setAccessToken($access_token);
          $user_profile = $facebook->api("/$user");
      }
  } catch (FacebookApiException $e) {
      //error_log($e);
    error_log($e,1,'vinay.gupta@shopclues.com');
    $user = null;
    header('location: '. Registry::get('config.domain_url'));
  }
}
else
{
    header('location: '. Registry::get('config.domain_url'));
}

if(!$user_profile)
{
	if(isset($_REQUEST['code']))
	{

		$user_profile = $facebook->api("/$user");
		login_via_fb($user_profile); 
	}
}

if ($user)
{
	login_via_fb($user_profile); 
}



function login_via_fb($user_data)
{
	$_auth = &$auth;
	$firstname = $user_data['first_name'];
	$lastname = $user_data['last_name'];
	$id = $user_data['id'];
	$email = $user_data['email'];
	$current_location = Registry::get('config.current_location');
	$location_user = addslashes($user_data['location']['name']);
	$birthday =$user_data['birthday'];
	$birthday = strtotime($birthday);
	$gender    =  strtoupper(substr($user_data['gender'],0,1));
        $express_var = $_SESSION['express'];
	
       if($_REQUEST['type'] ==1)
        {
        	$current_location = Registry::get('config.current_location')."/".$_SESSION['fb_login_redirect_popup_new'];
        	//unset($_SESSION['type']);
        	unset($_SESSION['fb_login_redirect_popup_new']);
        }
        else if($_REQUEST['type'] ==2)
        {
        	$current_location = "http://".$_REQUEST['page'];
        }
        else
        {
        	$current_location = Registry::get('config.current_location')."/".$_REQUEST['page'];
        }

        if($express_var == 'Y')
        {
            $current_location = Registry::get('config.current_location');
            $current_location = $current_location."/index.php?dispatch=checkout.express_checkout";
            unset($_SESSION['express']);
            unset($express_var);

        }

	$pass = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,6);

	$duplicate=db_get_field("select user_id from cscart_users where email='".$email."'");
	$domain = $_SERVER['HTTP_HOST'] == 'localhost' ? '' : '.shopclues.com';
	if ($duplicate == '') 
	{	
		if(Registry::get('config.anniversary_status_show') == 1)
		$_SESSION['anniversary_token_id'] = TRUE;
		$insert = db_query("insert into cscart_users set firstname='".$firstname."',gender='".$gender."',location='".$location_user."',birthday='".$birthday."',lastname='".$lastname."',password='".md5($pass)."',email = '".$email."',referer='fblogin_".$id."'");
		
		$sql = "select user_id from cscart_users where email='".$email."' and referer='fblogin_".$id."'";
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

		$duplicate_upd = db_get_field("select user_id from cscart_users where user_id=$duplicate and gender in ('M','F')");
		if($duplicate_upd == '')
		{
			db_query("update cscart_users set gender='".$gender."',location='".$location_user."',birthday='".$birthday."' where user_id=".$duplicate);
		}
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

?>
