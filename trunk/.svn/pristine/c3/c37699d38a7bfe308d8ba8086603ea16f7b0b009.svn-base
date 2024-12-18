<?php
if (!defined('AREA')) { die('Access denied'); }

function fn_sync_status_pg($pgid)
{
	$reg = Registry::get('payment_gateway_status_sync');
	$hour_diff = $reg['hour_diff'];
	$status_check = $reg['status'];
	$dat = fn_ct_addhour(date('Y-m-d H:i:s'),-$hour_diff);
	if($pgid==14)
	{
		$paymet = "CCAVENUE";
	}
	else if($pgid==12)
	{
		$paymet = "DirecPay";
	}
	$sql = "select co.order_id, co.status, co.payment_id,FROM_UNIXTIME(co.timestamp) as order_date, cpd.id, cpd.direcpayreferenceid, cpd.flag
												from cscart_orders co, clues_prepayment_details cpd where 
												co.order_id = cpd.order_id and
												cpd.payment_gateway = '" . $paymet . "' and
												co.status in (" . $status_check . ") and
												FROM_UNIXTIME(co.timestamp) > '". $dat ."'";
							
	$res = array();
	$res = db_get_array($sql);
	$sql = "select order_id,status,payment_id,FROM_UNIXTIME(timestamp) as order_date 
							 from cscart_orders where payment_id='" . $pgid . "' and
							 status in (" . $status_check . ") and
							 FROM_UNIXTIME(timestamp) > '". $dat ."' and
							 order_id not in (select order_id from clues_prepayment_details where payment_gateway='". $paymet ."')";
	$res_withouthistory = db_get_array($sql);
	if($pgid==14)://CCAVENUE
		echo "<h1>CCAVENUE</h1>";
		echo "<br>Count: " . count($res) . "<br><br>";
		$payment_method_data = fn_get_payment_method_data($pgid);
		$merchant_id = $payment_method_data['params']['merchantid'];
		for($i=0;$i<count($res);$i++)
		{
			$status = $res[$i]['flag'];
			$sql = "select ct.* from clues_prepayment_details_track ct USE INDEX(prepayment_id) where ct.prepayment_id='" . $res[$i]['id'] . "' order by id desc";
			$res_track = db_get_array($sql);
			//var_dump($res_track);
			for($j=0;$j<count($res_track);$j++)
			{
				if($j==0)
				{
					$status = $res_track[$j]['flag'];
				}
				echo $res_track[$j]['flag'] . "&nbsp;<br>";
			}
			
			echo "<div style='padding:2px;margin-bottom:3px;background:$ccc;font-size:10px'>";
			$url = "https://mars.ccavenue.com/servlet/new_txn.OrderStatusTracker?Merchant_Id=" . $merchant_id . "&Order_Id=" . $res[$i]['order_id'];
			echo $url;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_FAILONERROR,1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 200);
			$retValue = curl_exec($ch);  
			curl_close($ch);
			$otdetails = str_replace("'","''",$retValue);
			$arr = explode('&AuthDesc=',$retValue);
			if(count($arr)==2)
			{
				$arr = explode('&',$arr[1]);
				echo "&nbsp;&nbsp;".$arr[0]."&nbsp;&nbsp;";
				echo "Prestatus: " . $status . "&nbsp;&nbsp;";
				if($arr[0] != $status)
				{
					//var_dump($res[$i]);
					echo "<a style='background:red'><b>Mismatched</b></a>";
					$sql = "insert into clues_prepayment_details_track set prepayment_id='". $res[$i]['id'] ."',
																		   flag='". $arr[0] ."',
																		   previousflag='". $status ."',
																		   other_details='". $otdetails ."'";
					db_query($sql);													   
				}
			}
			echo "</div>";
			
		}
		echo "<h1>for without track history</h1>";
		for($i=0;$i<count($res_withouthistory);$i++)
		{
			echo "<div style='padding:2px;margin-bottom:3px;background:$ccc;font-size:10px'>";
			$url = "https://mars.ccavenue.com/servlet/new_txn.OrderStatusTracker?Merchant_Id=" . $merchant_id . "&Order_Id=" . $res_withouthistory[$i]['order_id'];
			echo $url;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_FAILONERROR,1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 200);
			$retValue = curl_exec($ch);  
			curl_close($ch);
			$otdetails = str_replace("'","''",$retValue);
			$arr = explode('&AuthDesc=',$retValue);
			if(count($arr)==2)
			{
				$arr = explode('&',$arr[1]);
				echo "&nbsp;&nbsp;".$arr[0]."&nbsp;&nbsp;";
				echo "Prestatus: No Track&nbsp;&nbsp;";
				
				$sql = "insert into clues_prepayment_details_track set order_id_not_tracked='". $res_withouthistory[$i]['order_id'] ."',
																	   flag='". $arr[0] ."',
																	   previousflag='',
																	   other_details='". $otdetails ."',
																	   payment_gateway='CCAVENUE'";
				db_query($sql);													   
				
			}
			echo "</div>";
		}
	endif;
	if($pgid==12)://DIRECTPAY
		$payment_method_data = fn_get_payment_method_data($pgid);
		$merchant_id = $payment_method_data['params']['merchantid'];
		$mode = "";
		if(isset($payment_method_data['params']['testmode']) && $payment_method_data['params']['testmode']=='on') {
			$mode = 'test';
		}else{
			$mode = 'live';
		}
		//$mode = 'test';
		if($mode == 'test') {
			$action = 'https://test.timesofmoney.com/direcpay/secure/dpPullMerchAtrnDtls.jsp';
		}elseif($mode == 'live'){
			$action = 'https://www.timesofmoney.com/direcpay/secure/dpPullMerchAtrnDtls.jsp';
		}
		
		
		for($i=0;$i<count($res);$i++)
		{
			if($res[$i]['status']!='N'){
				$transactionid	= $res[$i]['direcpayreferenceid'];
				$mid					= $merchant_id;
				$returnurl				= Registry::get('config.http_location') . "/index.php?dispatch=payment_gateway_status_sync.responseDP";
				$requestparams			= $transactionid.'|'.$mid.'|'.$returnurl;
				
				$url = $action.'?requestparams='.$requestparams;
				echo $url.'<br/>';//die;
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,$url);
				curl_setopt($ch, CURLOPT_FAILONERROR,1);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
				curl_setopt($ch, CURLOPT_TIMEOUT, 200);
				$retValue = curl_exec($ch);  
				echo "<br>" . $retValue . "<br>";                    
				curl_close($ch);
			}
		}
	endif;	
}
function fn_ct_addhour($dat,$hour)//$dat will be in ymd function
{
	$dat2 = strtotime(date("Y-m-d H:i:s", strtotime($dat)) . " +" . $hour . " hour");
	return date('Y-m-d H:i:s', $dat2);
}
?>