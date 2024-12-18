<?php
define('AREA', 'C');
define('AREA_NAME', 'customer');

require  dirname(__FILE__) . '/../../prepare.php';
require  dirname(__FILE__) . '/../../init.php';

$strMessage =  isset($_GET['message']) ? $_GET['message'] : '';
$strMTRCKID =  isset($_GET['ME_TX_ID']) ? $_GET['ME_TX_ID'] : '';
if($strMTRCKID == ''){
	fn_set_notification('E', '', 'Your order has been declined by the payment processor. Please review your information and contact store administration.');
	$url = Registry::get('config.domain_url').'/index.php?dispatch=checkout.checkout&edit_step=step_four';
}else{

$url = Registry::get('config.domain_url').'/index.php?dispatch=payment_notification.return&from=F&payment=hdfc_script&order_id='.$strMTRCKID.'&message='.$strMessage;
//$url = 'http://ssmoke.shopclues.com/index.php?dispatch=payment_notification.return&from=F&payment=hdfc_script&order_id='.$strMTRCKID.'&message='.$strMessage;
//fn_redirect($url);
}
header("Location: $url");

?>
