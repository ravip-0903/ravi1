<?php
define('AREA', 'C');
define('AREA_NAME', 'customer');
	
require  dirname(__FILE__) . '/../../prepare.php';
require  dirname(__FILE__) . '/../../init.php';

$txmessage = isset($_GET['message']) ? $_GET['message'] : '';
$txmeid= isset($_GET['ME_TX_ID']) ? $_GET['ME_TX_ID'] : '';
$url = Registry::get('config.domain_url').'/index.php?dispatch=payment_notification.return&from=P&payment=hdfc_script&order_id='.$txmeid.'&message='.$txmessage;
//$url = 'http://ssmoke.shopclues.com/index.php?dispatch=payment_notification.return&from=P&payment=hdfc_script&order_id='.$txmeid.'&message='.$txmessage;
//fn_redirect($url);
header("Location: $url");
?>
