<?php
	define('AREA', 'A');
	define('AREA_NAME', 'admin');
	define('PAYMENT_ID','12');
		
	require  dirname(__FILE__) . '/prepare.php';
	require  dirname(__FILE__) . '/init.php';
	
	$response = explode('|', $_REQUEST['responseparams']);
	list($direcpayreferenceid, $flag, $country, $currency, $otherdetails, $merchantorderno, $amount) = explode('|', $_REQUEST['responseparams']);
	if($direcpayreferenceid != 'DpMid!@'){
		die("HEY! Don't Play With ME..............................");
	}
	$order_info = fn_get_order_info($_REQUEST['order_id'], true);
	//echo '<pre>';print_r($response);print_r($order_info);die;
	if (!empty($order_info) ){ 
	
	if (fn_check_payment_script('direcpay_script.php', $_REQUEST['order_id'])) {
		
		
		if($response['1'] == 'SUCCESS') {
			if ($order_info['status'] == 'N' || $order_info['status'] == 'T') {
				//fn_change_order_status($_REQUEST['order_id'], 'P', '', false);
				if($amount == $order_info['total']) {
				   fn_change_order_status($_REQUEST['order_id'], 'P', '', true);
				}else{
				   fn_change_order_status($_REQUEST['order_id'], 'K', '', true);
				   $details = '******PAYMENT AMOUNT Rs. '.$amount.' NOT SAME AS ORDER TOTAL.******'.$order_info['details'];
				   db_query("update cscart_orders set details='".$details."' where order_id=".$Order_Id);
				}
			}
		} elseif($response['1'] == 'FAIL') {
			if ($order_info['status'] == 'N' || $order_info['status'] == 'T') {
				fn_change_order_status($_REQUEST['order_id'], 'F', '', false);
			}
		}			
	}
	
	fn_order_placement_routines($_REQUEST['order_id'], false);
	
	
	}
	else
	{
		## ORDER ID IS NOT PRESENT IN DB 
		fn_set_notification('N','Order','Your order is not placed. Please try again','I');
		fn_redirect('index.php?dispatch=checkout.cart');
	}

	die("DONE");
?>
