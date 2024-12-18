<?php

define('INDEX_SCRIPT', Registry::get('config.customer_index'));
$order_id = $_GET['order_id'];
$retry = $_GET['retry'];
$status = $_GET['status'];

if(isset($_GET['order_id']) && !isset($_GET['retry'])){
	complete($order_id,$status);
}
elseif(isset($_GET['retry']) ) {
	retry($retry,$order_id);		
}
else {
	place_order($_GET);
}
	
function complete($order_id,$status) {

	$log['order_id'] = $order_id;
	//echo "<pre>";
	//print_r($order_details);die;
	if($status == 'P'){
		$msg = str_replace('[order_id]' ,$order_id, fn_get_lang_var('api_text_order_placed_successfully') );
		$class = 'success';
		$log['order_status'] = 'success';
	}
	elseif($status == 'K'){
		$msg = str_replace('[order_id]',$order_id, fn_get_lang_var('api_text_order_pending'));
		$class = 'pending';
		$log['order_status'] = 'pending';
	}
	elseif($status == 'F' || $status == 'D'){
		if($status == 'F'){
			$msg = fn_get_lang_var('api_text_order_placed_error');
			$log['order_status'] = 'failed';
		}
		else{
			$msg = fn_get_lang_var('api_text_order_canceled_error');
			$log['order_status'] = 'Cancelled by user';
		}
		$class = 'fail'; 		
		$order_info = db_get_row('SELECT * FROM cscart_order_data Where Type = \'Z\' AND order_id='.$order_id);
		$order_info = unserialize($order_info['data']);
		unset($order_info['cart']['coupons']);
		unset($order_info['cart']['pending_certificates']);
		unset($order_info['cart']['use_gift_certificates']);
		unset($order_info['cart']['points_info']['in_use']);
		fn_save_cart_content($order_info['cart'],$order_info['cart']['user_data']['user_id']);
	}
	else{
		$msg = fn_get_lang_var('api_text_order_placed_error');
		$class = 'fail';	
		$log['order_status'] = 'failed';
	}

	LogMetric::dump_log(array_keys($log), array_values($log));
	include dirname(__FILE__) .'../../templates/order_placed.php';
	exit();		
}
	
function retry($retry,$order_id) {
	if($retry != 1)
		complete (null, 'F');
	else{
		//echo 'SELECT * FROM cscart_order_data Where order_id='.$order_id;
		$order_info = db_get_row('SELECT * FROM cscart_order_data Where Type = \'Z\' AND order_id='.$order_id);
		$order_info = unserialize($order_info['data']);
		$data['user_id'] = $order_info['cart']['user_data']['user_id'];
		$data['payment_option_id'] = $order_info['cart']['payment_option_id'];
		$data['profile_id'] = $order_info['profile_id'];
		
		//get coupon codes
		$i = 0;
		foreach($order_info['cart']['coupons'] as $k=>$v) {
			$data['coupon_code'][$i] = $k;
			$i++;
		}
		$data['gift_certi'] = array_keys($order_info['cart']['use_gift_certificates']);
		$data['gift_certi'] = $data['gift_certi'][0];
		$data['points_to_use'] = $order_info['cart']['points_info']['in_use']['points'];
		//print_r($data);die;
		place_order($data);
		exit();
	}		
}
	
	
	
function place_order($req) {
	echo fn_get_lang_var('api_placing_order');
	$place_order = new checkout();
	$place_order->_request =$req;
	$order_details = $place_order->place_order();
}
	
      
    
 
?>    
     
