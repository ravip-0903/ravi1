<?php
// GET PARAMETERS "hour","onlysync","nottosync" all are not compulsory But "mode" is Compulsory.

define('AREA', 'A');
	define('AREA_NAME', 'admin');
require  dirname(__FILE__) . '/../prepare.php';
require  dirname(__FILE__) . '/../init.php';

$mode = $_GET['mode'];
$reg = array('hour_diff' => "72",
			 'status' => "'P','F','N','E','G','A','H','T'"
			);
$action_array = array();
$noaction_array = array();
$update_query_array = array();
//function fn_sdeep_is_cod_payment(){}			
if ($mode == 'pgsi') {
	//Directpay = 12 and CC Avenue = 14
	if(!isset($_GET['onlysync']))
	{
		//fn_sync_status_pg(12);
		tools_fn_sync_status_pg(14,$reg);
	}
	if(!isset($_GET['nottosync']))
	{
		tools_fn_pgss_login();
		echo "<h1>sync_history_for_not_tracked_before_orders</h1>";
		tools_fn_sync_history_for_not_tracked_before_orders();
		echo "<h1>go_on_status_sync</h1>";
		tools_go_on_status_sync();
		tools_fn_sendnotifications();
	}
}
if($mode=='responseDP')
{
	fn_responseDP();
}
exit;	
function tools_fn_pgss_login()
{
	$sql = "select user_id from cscart_users where email='script@shopclues.com'";
	$row = db_get_row($sql);
	fn_login_user($row['user_id']);
}
function tools_fn_sync_history_for_not_tracked_before_orders()
{
	$sql = "select * from clues_prepayment_details_track where order_id_not_tracked != '' and sync_status='0' and prepayment_id='0'";
	$res = db_get_array($sql);
	for($i=0;$i<count($res);$i++)
	{
		if($res[$i]['payment_gateway']=='CCAVENUE'):
		
			$amount = '';
			$tn_no = '';
			$arr = explode('&',$res[$i]['other_details']);
			for($j=0;$j<count($arr);$j++)
			{
				$arr1 = explode('=',$arr[$j]);
				if($arr1[0]=="Amount")
				{
					$amount = $arr1[1];
				}
				if($arr1[0]=="nb_order_no")
				{
					$tn_no = $arr1[1];
				}
			}
			$sql = "select direcpayreferenceid from clues_prepayment_details where direcpayreferenceid='". $tn_no ."'";
			$result = db_get_row($sql);
			if(count($result)==0){
				$sql = "insert into clues_prepayment_details set direcpayreferenceid = '". $tn_no ."',
																 order_id='". $res[$i]['order_id_not_tracked'] ."',
																 flag='". $res[$i]['flag'] ."',
																 other_details='not tracked previously',
																 amount='". $amount ."',
																 payment_gateway='CCAVENUE'";
				db_query($sql);	
			}
			$sql = "update clues_prepayment_details_track set sync_status='1' where id='". $res[$i]['id'] ."'";
			db_query($sql);
			$res_order = fn_get_order_short_info($res[$i]['order_id_not_tracked']);
			tools_fn_change_status_order_accordingly($res[$i]['order_id_not_tracked'],$res_order,array("id"=>"","flag"=>$res[$i]['flag'],"tnx_id"=>$tn_no));											 
		endif;
		
	}
}
function tools_go_on_status_sync()
{
	$sql = "select cpdt.*,cpd.order_id,cpd.flag as cpdflag, cpd.payment_gateway,cpd.direcpayreferenceid as tnx_id
					from clues_prepayment_details_track cpdt,clues_prepayment_details cpd 
					where cpdt.prepayment_id=cpd.id and cpdt.sync_status='0' order by cpdt.id and prepayment_id != '0'"; 
	$res = db_get_array($sql);
	for($i=0;$i<count($res);$i++):
		if($res[$i]['payment_gateway']=="CCAVENUE"):
			$res_order = fn_get_order_short_info($res[$i]['order_id']);
			if($res_order['status']=='T')
			{
				$sql = "select order_id from cscart_orders where parent_order_id='" . $res[$i]['order_id'] . "'";
				$res_child_orders = db_get_array($sql);
				for($j=0;$j<count($res_child_orders);$j++)
				{
					$res_order_c = fn_get_order_short_info($res_child_orders[$j]['order_id']);
					tools_fn_change_status_order_accordingly($res_child_orders[$j]['order_id'],$res_order_c,$res[$i]);
				}
			}
			else
			{
				tools_fn_change_status_order_accordingly($res[$i]['order_id'],$res_order,$res[$i]);
			}
		endif;
	endfor;	
}

function tools_fn_change_status_order_accordingly($orderid,$order_short_info,$history)
{
	global $action_array, $noaction_array,$update_query_array;
	echo "<div style='padding:5px;font-size:10px;margin:3px;background-color:#ccc'>";
	var_dump($order_short_info);
	var_dump($history);
	$status_to = '';
	$status_from = $order_short_info['status'];
	//'Y', 'N' OR 'B'. for cc avenue
	if($history['flag']=="Y" and ($status_from == "N" || $status_from == "F"))
	{
		$status_to = "P";
		$action_array[] = array("order_id"=>$orderid,"tnx_id"=>$history['tnx_id'],"payment_gateway"=>"CCAVENUE","previousflag"=>$history['previousflag'],"flag"=>$history['flag'],"order_status"=>$status_from,"order_status_changed_to"=>$status_to);
	}
	else
	{
		$noaction_array[] = array("order_id"=>$orderid,"tnx_id"=>$history['tnx_id'],"payment_gateway"=>"CCAVENUE","previousflag"=>$history['previousflag'],"flag"=>$history['flag'],"order_status"=>$status_from,"order_status_changed_to"=>$status_to);
	}
	if($history['id'] != ""){
		$sql = "update clues_prepayment_details_track set sync_status='1' where id='". $history['id'] ."'";
		$update_query_array[] = $sql;
	}
	if($status_to != "" && $status_from != $status_to){
		$ret_st = fn_change_order_status($orderid, $status_to, '',$notify = array("C"=>false,"A"=>false,"S"=>false));
		if($ret_st==true){
			
			echo "Status Changed of $orderid to $status_to ";
		}
	}
	echo "</div>";
}
function tools_fn_sendnotifications()
{
	global $action_array, $noaction_array,$update_query_array;
	var_dump($action_array);
	var_dump($noaction_array);
	$dat = date('Y-m-d H:i:s');
	$arr = $action_array + $noaction_array;
	if(count($arr)==0)
	{
		exit;
	}
	$tablechangeflag=0;
	$html = "<div style='font-size:20px'>Payment Status Changed Report - </div>";
	$html = $html ."<div style='padding:10px'><div style='font-size:16px'>Order Status To Be Changed In Payed. Please Review.</div>";
	$html= $html ."<table border='1'><tr><td>Order No</td><td>Transaction No.</td><td>Payment Gateway</td><td>Gateway Previous Status</td><td>Gateway Current Status</td><td>Order Status</td><td>Order Status To be Changed In</td></tr>";
	foreach($arr as $v)
	{
		$sql = "insert into clues_order_statuschangeaction_byscript set order_id='".$v['order_id']."',
																		tnx_id='".$v['tnx_id']."',
																		payment_gateway='".$v['payment_gateway']."',
																		previousflag='".$v['previousflag']."',
																		flag='".$v['flag']."',
																		order_status='".$v['order_status']."',
																		order_status_changed_to='".$v['order_status_changed_to']."',
																		datetime='".$dat."'";
		db_query($sql);	
		if($v['order_status_changed_to']=="" && $tablechangeflag==0)
		{
			$tablechangeflag=1;
			$html = $html . "</table>
							<div style='font-size:16px;padding-top:10px'>Orders To Be Reviewed.</div>";
			$html= $html ."<table border='1'><tr><td>Order No</td><td>Transaction No.</td><td>Payment Gateway</td><td>Gateway Previous Status</td><td>Gateway Current Status</td><td>Order Status</td><td>Order Status To be Changed In</td></tr>";				
		}
		if($v['order_status_changed_to']=='')
		{
			$tobe = "To be reviewed";
		}
		else
		{
			$tobe = $v['order_status_changed_to'];
			$sql = "select description from cscart_status_descriptions where status='" . $tobe . "' and type='o'";
			$res_st = db_get_row($sql);
			$tobe = $res_st['description'];
		}
		$sql = "select description from cscart_status_descriptions where status='" . $v['order_status'] . "' and type='o'";
		$res_st = db_get_row($sql);
		$v['order_status'] = $res_st['description'];
		
		if($v['payment_gateway']=="CCAVENUE")
		{
			if($v['flag']=='Y')
			{
				$v['flag'] = 'Payment Received';
			}
			if($v['flag']=='N')
			{
				$v['flag'] = 'Payment Not Received';
			}
			if($v['flag']=='B')
			{
				$v['flag'] = 'Pending';
			}
			if($v['previousflag']=='Y')
			{
				$v['previousflag'] = 'Payment Received';
			}
			if($v['previousflag']=='N')
			{
				$v['previousflag'] = 'Payment Not Received';
			}
			if($v['previousflag']=='B')
			{
				$v['previousflag'] = 'Pending';
			}
		}
		$html = $html ."<tr><td>".$v['order_id']."</td>
					<td>".$v['tnx_id']."</td>
					<td>".$v['payment_gateway']."</td>
					<td>".$v['previousflag']."</td>
					<td>".$v['flag']."</td>
					<td>".$v['order_status']."</td>
					<td>". $tobe ."</td></tr>";		
	}
	$html = $html . "</table></div>";
	foreach($update_query_array as $sql)
	{
		db_query($sql);
	}
	echo $html;
	$sql = "select user_id from cscart_users where email='script@shopclues.com'";
	$row = db_get_row($sql);
	$html2 = $memo = str_replace("'","''",$html);
	$subject = "Payment Status Changed Report";
	db_query("INSERT INTO  clues_email_queue (user_id, from_email, to_email, subject, message) values('". $row['user_id'] ."','orders@shopclues.com','".$_GET['email']."','".$subject."','".$html2."')");
}
function tools_fn_sync_status_pg($pgid,$reg)
{
	$hour_diff = $reg['hour_diff'];
	if(isset($_GET['hour']) && (int)$_GET['hour']>0)
	{
		$hour_diff = (int)$_GET['hour'];
	}
	
	$status_check = $reg['status'];
	$dat = tools_fn_ct_addhour(date('Y-m-d H:i:s'),-$hour_diff);
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
												FROM_UNIXTIME(co.timestamp) > '". $dat ."' order by co.order_id desc";						
	$res = array();
	$res = db_get_array($sql);
	$sql = "select order_id from clues_prepayment_details where payment_gateway='". $paymet ."'";
	$res_prep_orders = db_get_array($sql);
	$str_prep_orders = "'0'";
	foreach($res_prep_orders as $r)
	{
		if($str_prep_orders == '')
		{
			$str_prep_orders = "'" . $r['order_id'] . "'";
		}
		else
		{
			$str_prep_orders = $str_prep_orders . ",'" . $r['order_id'] . "'";
		}
	}
	$sql = "select order_id,status,payment_id,FROM_UNIXTIME(timestamp) as order_date 
							 from cscart_orders where payment_id='" . $pgid . "' and
							 status in (" . $status_check . ") and
							 FROM_UNIXTIME(timestamp) > '". $dat ."' and
							 order_id not in (".$str_prep_orders.")  order by order_id desc";
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
				$returnurl				= Registry::get('config.http_location') . "/tools/Payment_Gateway_Status_Sync.php?mode=responseDP";
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
function fn_responseDP()
{
	$myFile = "process_response.txt";
	$fh = fopen($myFile, 'a') or die("can't open file");
	fwrite($fh, date("Y-m-d\TH:i:s") . substr((string)microtime(), 1, 8) . " ".$_REQUEST['responseparams'] . "\r\n");
	fclose($fh);
}
function tools_fn_ct_addhour($dat,$hour)//$dat will be in ymd function
{
	$dat2 = strtotime(date("Y-m-d H:i:s", strtotime($dat)) . " +" . $hour . " hour");
	return date('Y-m-d H:i:s', $dat2);
}