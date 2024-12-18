<?php
	define('AREA', 'A');
	define('AREA_NAME', 'admin');
	define('PAYMENT_ID','12');
		
	require  dirname(__FILE__) . '/../prepare.php';
	require  dirname(__FILE__) . '/../init.php';
	
	$myFile = "process_response.txt";
	$fh = fopen($myFile, 'a') or die("can't open file");
	fwrite($fh, date("Y-m-d\TH:i:s") . substr((string)microtime(), 1, 8) . " ".$_REQUEST['responseparams'] . "\r\n");
	fclose($fh);
	exit;
	
	$response_data = explode('|', $_REQUEST['responseparams']);
	
	$order_id = $response_data['5'];  
  
	$payment_current_status = db_get_row("SELECT order_id, status, 			date_format(FROM_UNIXTIME(timestamp),'%d-%m-%Y') as order_date
										from cscart_orders 
										where order_id=".$order_id); 
    //print_r($payment_current_status);die;
	if($payment_current_status['status'] == 'N' and $response_data['1'] == 'SUCCESS') 
	{ 
		logthis('INCOMPLETE',$payment_current_status['order_date'],$response_data);
	}
	elseif($payment_current_status['status'] == 'T')
	{
		$child_ids = db_get_array("select order_id from cscart_orders where parent_order_id=".$payment_current_status['order_id']);
		foreach($child_ids as $child_id)
		{
			$order_status = db_get_row("SELECT order_id, status
						from cscart_orders 
						where order_id=".$order_id);
			if($order_status['status'] == 'N' and $response_data['1'] == 'SUCCESS')
			{
				logthis('INCOMPLETE',$payment_current_status['order_date'],$response_data);
			}
			elseif(($payment_current_status['status'] == 'P' or $payment_current_status['status'] == 'A' or $payment_current_status['status'] == 'E' or $payment_current_status['status'] == 'G') and $response_data['1'] == 'FAIL')
			{
				$order_status_readable = get_readable_status($payment_current_status['status']);
				logthis($order_status_readable,$payment_current_status['order_date'],$response_data);
			}
		}		
	}
	elseif($payment_current_status['status'] == 'K' and $response_data['1'] == 'SUCCESS')
	{
		$order_status_readable = get_readable_status($payment_current_status['status']);
		logthis(order_status_readable,$payment_current_status['order_date'],$response_data);
	}
	elseif($payment_current_status['status'] == 'K' and $response_data['1'] == 'FAIL')
	{
		$order_status_readable = get_readable_status($payment_current_status['status']);
		logthis($order_status_readable,$payment_current_status['order_date'],$response_data);
	}
	elseif(($payment_current_status['status'] == 'P' or $payment_current_status['status'] == 'A' or $payment_current_status['status'] == 'E' or $payment_current_status['status'] == 'G') and $response_data['1'] == 'FAIL')
	{
		$order_status_readable = get_readable_status($payment_current_status['status']);
		logthis($order_status_readable,$payment_current_status['order_date'],$response_data);
	}
	elseif(($payment_current_status['status'] == 'P' or $payment_current_status['status'] == 'A' or $payment_current_status['status'] == 'E' or $payment_current_status['status'] == 'G') and $response_data['1'] == 'SUCCESS')
	{
		$order_status_readable = get_readable_status($payment_current_status['status']);
		logthis($order_status_readable,$payment_current_status['order_date'],$response_data);
	}
	
	function logthis($current_order_status,$payment_order_date, $response_data) {
		$to      = 'wianbrain@gmail.com';
    	$subject = 'Status of '.$response_data['5'].' is changed from '.$current_order_status.'=>'.$response_data['1'];
    	$message = 'current status for the payment on direcpay is: '.$_REQUEST['responseparams'];
    	$headers = 'From: admin@shopclues.com' . "\r\n" .
    		'Reply-To: admin@shopclues.com' . "\r\n" .
    		'X-Mailer: PHP/' . phpversion();
		
		$order_info = db_get_row("SELECT status from cscart_orders where order_id=".$response_data['5']);
		
		$str = $payment_order_date.' Order ID:'.$response_data['5'].' TXN ID:'.$response_data['0'].' order status : '.$current_order_status.' New state: '.$response_data['1'];
    	if(mail($to, $subject, $message, $headers))
    		echo 'mail sent';
    	$stringData = $str."\r\n";
    	$myFile = "payment_log.txt";
    	$fh = fopen($myFile, 'a') or die("can't open file");
    	fwrite($fh, $stringData);	
    	fclose($fh);	
	}
	
	function get_readable_status($status_code,$type='O')
	{
		$order_status_readable = db_get_row("SELECT description FROM cscart_status_descriptions WHERE STATUS='".$status_code."' AND TYPE='".$type."'");
		return $order_status_readable['description'];
	}
?>
