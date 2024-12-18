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

if($_GET['auth'] == 'fb')
{
	$loginUrl = $facebook->getLoginUrl(array('scope' => 'email,read_stream', 'page' => $_GET['page']));
	header('location: ' .$loginUrl);
}
?>