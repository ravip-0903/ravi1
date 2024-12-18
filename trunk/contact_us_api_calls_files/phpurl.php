<?php 
	include_once('scOrderDetails.php');
	$Ageing = 0;
	$ODS = "";
	$url="http://10.20.117.19/tools/order_status_api.php?mode=getOrderData&order_id=".$_REQUEST['orderid'];
	$ch = curl_init($url); 
	curl_setopt($ch, CURLOPT_URL,$url); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); 
	curl_setopt($ch, CURLOPT_TIMEOUT, 10); 
	$result = curl_exec($ch);
	curl_close($ch);
	$jsonObj = json_decode($result);
	//var_dump($jsonObj);
	$OD =new OrderDetails($jsonObj);
	if($OD->getOrderId() == ""){
		//echo "</br></br><h2>Invalid Order Number</h2>";
		//die();		return ;
	}else{
		$ODS =$OD->getLastOrderStatus();
		date_default_timezone_set('Asia/Kolkata');
		//date('l dS \o\f F Y h:i:s A', $curenttime);
		//echo $OD->getTimestamp();
		//echo $curenttime = time();
		//echo date('l dS \o\f F Y h:i:s A');
		//mktime(0, 0, 0, date('m'), date('d'), date('Y'))
	//echo
		$diff = abs(mktime(0, 0, 0, date('m'), date('d'), date('Y')) - $ODS->getTransitionDate());
	//echo
		$Ageing = floor($diff / (60*60*24));
	}
	
//	echo date('l dS \o\f F Y h:i:s A', $OD->getTimestamp());	
//	echo "<br> days:".$days = floor(($diff / (60*60*24)));
//	echo $OD->getStatusDescriptionByStatusCode($OD->getStatus());
//	echo "\t\n<br> Company ID : ".$OD->getCompanyId();
//	echo "\t\n<br> Latest Status ID : ".$OD->getLastStatus() ." == ".$OD->getStatusDescription();
//	echo "\t\n<br> Latest Status Date : ".date('l d F Y h:i:s A T', $ODS->getTransitionDate());
//	echo "\t\n<br> Total Paid : ".$OD->getTotal();
?>