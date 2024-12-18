<?php
//Always place this code at the top of the Page
	define('AREA', 'C');
	define('AREA_NAME', 'customer');
	define('ACCOUNT_TYPE','customer');
		
	require dirname(__FILE__) . '/../../../prepare.php';
	require dirname(__FILE__) . '/../../../init.php';

if (isset($_SESSION['id'])) {
    // Redirection to login page twitter or facebook
	$current_location = Registry::get('config.current_location');
    header('location: '.$current_location.'/tools/fb_apps/google_login/home.php?page='.$_GET['page']);
}

if (array_key_exists("login", $_GET)) {
    $oauth_provider = $_GET['oauth_provider'];
    if ($oauth_provider == 'google') {
        header('Location: login-google.php?page='.$_GET['page']);
    } 
}
 
if($_GET['auth'] == 'google')
{

	$_SESSION['val'] = $_REQUEST['page'];
	if($_REQUEST['type'] ==1)
		$_SESSION['type'] = 1;
	if($_REQUEST['type'] ==2)
		$_SESSION['type'] = 2;

	header('location: ?login&oauth_provider=google&page='.$_GET['page']);
}

?>