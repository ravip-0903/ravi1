<?php
/******************************************************************************
*                                                                             *
*           Copyright (c) 2004-2007 CS-Cart.com. All rights reserved.         *
*                                                                             *
*******************************************************************************
*                                                                             *
* CS-Cart  is  commercial  software,  only  users  who have purchased a valid *
* license through  http://www.cs-cart.com/  and  accept  to the terms of this *
* License Agreement can install this product.                                 *
*                                                                             *
*******************************************************************************
* THIS  CS-CART  SHOP END-USER LICENSE AGREEMENT IS A LEGAL AGREEMENT BETWEEN *
* YOU  AND  YOUR  COMPANY  (COLLECTIVELY, "YOU") AND CS-CART.COM (HEREINAFTER *
* REFERRED  TO  AS  "THE AUTHOR")  FOR THE SOFTWARE PRODUCT IDENTIFIED ABOVE, *
* WHICH INCLUDES COMPUTER  SOFTWARE AND MAY INCLUDE ASSOCIATED MEDIA, PRINTED *
* MATERIALS,  AND  "ONLINE"  OR  ELECTRONIC  DOCUMENTATION (COLLECTIVELY, THE *
* "SOFTWARE").  BY  USING  THE  SOFTWARE,  YOU  SIGNIFY YOUR AGREEMENT TO ALL *
* TERMS, CONDITIONS, AND NOTICES CONTAINED  OR  REFERENCED HEREIN. IF YOU ARE *
* NOT  WILLING  TO  BE  BOUND  BY  THIS  AGREEMENT, DO NOT INSTALL OR USE THE *
* SOFTWARE.                                                                   *
*                                                                             *
* PLEASE READ THE FULL  TEXT OF SOFTWARE LICENSE AGREEMENT IN THE "COPYRIGHT" *
* FILE PROVIDED WITH THIS  DISTRIBUTION. THE AGREEMENT TEXT IS ALSO AVAILABLE *
* AT THE FOLLOWING URL: http://www.cs-cart.com/license.html                   *
******************************************************************************/

//
// $Id: paytrace.php 11559 2011-01-11 14:33:45Z klerik $
//

if ( !defined('AREA') ) { die('Access denied'); }

$post = array();
$post['UN']        = $processor_data['params']['username'];
$post['PSWD']      = $processor_data['params']['password'];
if ($processor_data['params']['test'] == 'Y') {
	$post['TEST']  = 'Y';
}
$post['TERMS']     = 'Y';
$post['METHOD']    = 'ProcessTranx';
$post['TRANXTYPE'] = 'Sale';
$post['CC']        = $order_info['payment_info']['card_number'];
$post['EXPMNTH']   = $order_info['payment_info']['expiry_month'];
$post['EXPYR']     = $order_info['payment_info']['expiry_year'];
$post['AMOUNT']    = $order_info['total'];
$post['CSC']       = $order_info['payment_info']['cvv2'];
$post['BADDRESS']  = $order_info['b_address'];
$post['BZIP']      = $order_info['b_zipcode'];
if (!empty($order_info['b_address_2'])) {
	$post['BADDRESS2'] = $order_info['b_address_2'];	
}
$post['BNAME'] = $order_info['payment_info']['cardholder_name'];
$post['BCITY'] = $order_info['b_city'];
$post['EMAIL'] = $order_info['email'];
$post['PHONE'] = $order_info['phone'];
$post['INVOICE'] = $order_id;

$ar[0] = 'parmlist=';
$parts = array();
foreach ($post as $k => $v) {
	$parts[] = $k . '~' . $v;
}
$ar[0] .= implode('|', $parts) . '|';

Registry::set('log_cut_data', array('CC', 'EXPMNTH', 'EXPYR', 'CSC', 'CVV2', 'StartMonth', 'StartYear'));
list($a, $response) = fn_https_request("POST", "https://paytrace.com/api/default.pay", $ar);

$response = explode('|', $response);
$vars = array();
foreach ($response as $pair) {
	$tmp = explode('~', $pair);
	if (!empty($tmp[1])) {
		$vars[$tmp[0]] = $tmp[1];
	}
}

$approved = false;
$error_message = '';

foreach ($vars as $key => $value) {
	if ($key == 'APPCODE') {
		if (!empty($value)) {
			$approved = true;
		}
	}
	else if ($key == 'ERROR') {
		$error_message .= $value;
	}
}

$pp_response = array();
if (!empty($error_message)) {
	$pp_response['order_status'] = 'F';
	$pp_response['reason_text']  = 'Declined: ' . $error_message;
} else {
	if ($approved == true) {
		$pp_response['order_status']   = 'P';
		$pp_response['transaction_id'] = $vars['TRANSACTIONID'];
		$pp_response['reason_text']  = $vars['APPMSG'];
	}
	else {
		$pp_response['order_status'] = 'F';
		$pp_response['reason_text']  = $vars['APPMSG'];
	}
}

?>