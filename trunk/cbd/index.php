<?php
define('AREA', 'C');
define('AREA_NAME', 'customer');
define('ACCOUNT_TYPE', 'customer');
require dirname(__FILE__) . '../../prepare.php';
require dirname(__FILE__) . '../../init.php';

$params = $_REQUEST;
$response	=	validate_request($params);
if(!$response){
    fn_prepare_response(array('status'=>'fail','data'=>'INVALID_TOKEN'));
}

if($params['action'] == 'GET_ORDER'){
    // get order data
    //$params['Order_id'] = $params['Order_id'].'a';
    if(is_numeric($params['order_id'])){
		$order_data = fn_get_order_data($params['order_id']);
        if(!empty($order_data)) {
            $result = array('status'=>'success','data'=> $order_data);
        } else {
            $result = array('status'=>'fail','data'=>'ORDER_NOT_FOUND');
        }
    }else{
        $result=array('status'=>'fail','data'=>'ORDER_NOT_FOUND');
    }

    
}elseif($params['action'] == 'PROCESS_ORDER'){
	// fn_process_order
    if(is_numeric($params['order_id']) && isset($params['amount']) && $params['amount'] > 0){
        fn_login_user('7465');
		$process_order = fn_process_cbd_order($params);
        if(isset($process_order['error'])){
            $result=array('status'=>'fail','data'=>$process_order['error']);
        } else {
            $result=array('status'=>'success','data'=>$process_order['STATUS']);
        }
        
    }else{
        $result=array('status'=>'fail','data'=>'ORDER_NOT_FOUND');
    }
    
    
}elseif($params['action'] == 'CHECK_TXN'){
    // check order status
    if(ctype_alnum($params['suvidhaa_unique_transaction_id'])){
        $check_txn=fn_get_order_status($params['suvidhaa_unique_transaction_id']);
        if(isset($check_txn['error'])){
            $result=array('status'=>'fail','data'=>$check_txn['error']);
        } else {
            $result=array('status'=>'success','data'=>$check_txn['status']);
        }
    }else{
        $result=array('status'=>'fail','data'=>'ORDER_NOT_FOUND');
    }
    
    
}

fn_prepare_response($result);


function validate_request($params){
	if($params['action'] == 'GET_ORDER'){
		$req_parameter = $params['order_id'].'|'.$params['action'];
		$checksum = hash_hmac('sha256', $req_parameter, Registry::get('config.Suvidhaa_Salt'));
		if($checksum == $params['token']){
			return TRUE;
		}
	}elseif($params['action'] == 'PROCESS_ORDER' && $_SERVER['REQUEST_METHOD'] == 'POST'){
		$req_parameter = $params['order_id'].'|'.$params['suvidhaa_unique_transaction_id'].'|'.$params['amount'].'|'.$params['action'];
		$checksum = hash_hmac('sha256', $req_parameter, Registry::get('config.Suvidhaa_Salt'));
		if($checksum == $params['token']){
			return TRUE;
		}
	}elseif($params['action'] == 'CHECK_TXN'){
		$req_parameter = $params['suvidhaa_unique_transaction_id'].'|'.$params['action'];
		$checksum = hash_hmac('sha256', $req_parameter, Registry::get('config.Suvidhaa_Salt'));
		if($checksum == $params['token']){
			return TRUE;
		}
	}
	return false;
}

function fn_prepare_response($res, $format='JSON'){
    if($format == 'JSON'){
        $res = json_encode($res);
        echo $res;
        exit;
    }
}



?>
