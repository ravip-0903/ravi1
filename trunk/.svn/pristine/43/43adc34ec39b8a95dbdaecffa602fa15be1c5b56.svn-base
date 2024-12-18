<?php
if (!defined('AREA')) {die('Access denied');}

include(DIR_ROOT ."/payments/icici/Sfa/BillToAddress.php");
include(DIR_ROOT ."/payments/icici/Sfa/CardInfo.php");
include(DIR_ROOT ."/payments/icici/Sfa/Merchant.php");
include(DIR_ROOT ."/payments/icici/Sfa/MPIData.php");
include(DIR_ROOT ."/payments/icici/Sfa/ShipToAddress.php");
include(DIR_ROOT ."/payments/icici/Sfa/PGResponse.php");
include(DIR_ROOT ."/payments/icici/Sfa/PostLibPHP.php");
include(DIR_ROOT ."/payments/icici/Sfa/PGReserveData.php");

		if(!empty($order_info)){
			$current_location = Registry::get('config.current_location');
			$Merchant_Id 	= $order_info['payment_method']['params']['merchantid'];
			
			$Amount 		= $order_info['total'];
			$Order_Id 		= $order_info['order_id'];
			$Redirect_Url 	= $current_location."/payments/icici/SFAResponse.php";
			
			if($order_info['is_parent_order'] == 'Y')
			{
				$child_ids = db_get_fields("SELECT order_id FROM ?:orders WHERE parent_order_id = ?i", $Order_Id);
				$child_ids = implode(',',$child_ids);
			}
			
			$patterns = array();
			$patterns[0] = '/^and\s/i';
			$patterns[1] = '/\sand\s/i';
			$patterns[2] = '/\sand$/i';
			$patterns[3] = '/^or\s/i';
			$patterns[4] = '/\sor\s/i';
			$patterns[5] = '/\sor$/i';
			$patterns[6] = '/^between\s/i';
			$patterns[7] = '/\sbetween\s/i';
			$patterns[8] = '/\sbetween$/i';
			$replacements = array();
			$replacements[0] = '';
			$replacements[1] = '';
			$replacements[2] = '';
			$replacements[3] = '';
			$replacements[4] = '';
			$replacements[5] = '';
			$replacements[6] = '';
			$replacements[7] = '';
			$replacements[8] = '';
			
			$s_phone = str_replace(' ','',$order_info['s_phone']);
			$s_phone = str_replace('-','',$s_phone);
			$s_phone = str_replace('+','',$s_phone);
			$s_phone = str_replace('(','',$s_phone);
			$s_phone = str_replace(')','',$s_phone);
			
			$b_phone = str_replace(' ','',$order_info['b_phone']);
			$b_phone = str_replace('-','',$b_phone);
			$b_phone = str_replace('+','',$b_phone);
			$b_phone = str_replace('(','',$b_phone);
			$b_phone = str_replace(')','',$b_phone);
			$b_country = fn_get_country_name($order_info['b_country']);
			$s_country = fn_get_country_name($order_info['s_country']);
			
			$billing_cust_name		= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['b_firstname'].' '.$order_info['b_lastname'])));
			$billing_cust_address	= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['b_address'].' '.$order_info['b_address_2'])));
			$billing_cust_state		= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['b_state'])));
			$billing_cust_country	= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $b_country)));
			$billing_cust_tel		= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $b_phone)));
			$billing_cust_email		= $order_info['email'];
			$delivery_cust_name		= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['s_firstname'].' '.$order_info['s_lastname'])));
			$delivery_cust_address	= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['s_address'].' '.$order_info['s_address_2'])));
			$delivery_cust_state 	= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['s_state'])));
			$delivery_cust_country 	= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $s_country)));
			$delivery_cust_tel		= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $s_phone)));
			$delivery_cust_notes	= '';
			if(isset($child_ids) && $child_ids !='') {
				$Merchant_Param		= $child_ids;
			}else {
				$Merchant_Param		= "";
			}
			$billing_city 			= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['b_city'])));
			$billing_zip 			= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['b_zipcode'])));
			$delivery_city 			= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['s_city'])));
			$delivery_zip 			= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['s_zipcode'])));	
			
			$order_data = array();
			$order_data['cust_name'] = $billing_cust_name;
			$order_data['custAddress'] = $billing_cust_address;
			$order_data['custCity'] = $billing_city;
			$order_data['custState'] = $billing_cust_state;
			$order_data['custPinCode'] = $billing_zip;
			$order_data['custCountry'] = $billing_cust_country;
			$order_data['custMobileNo'] = $billing_cust_tel;
			$order_data['custEmailId'] = $billing_cust_email;
			$order_data['deliveryName'] = $delivery_cust_name;
			$order_data['deliveryAddress'] = $delivery_cust_address;
			$order_data['deliveryCity'] = $delivery_city;
			$order_data['deliveryState'] = $delivery_cust_state;
			$order_data['deliveryPinCode'] = $delivery_zip;
			$order_data['deliveryCountry'] = $delivery_cust_country;
			$order_data['deliveryMobileNo'] = $delivery_cust_tel;
			$order_data['otherNotes'] = $delivery_cust_notes; // customer notes not to send to payment gateway
			$order_data['editAllowed'] = 'not a parameter in ccavenue';
			$order_data['requestparameter'] = $Redirect_Url.'|'.$Order_Id.'|'.$Amount.'|'.$Merchant_Id;
			$order_data['payment_gateway'] = 'CCAVENUE';
			$order_data['amount'] = $order_info['total'];
			$order_data['emi_id'] = $order_info['emi_id'];
			$order_data['emi_fee'] = $order_info['emi_fee'];
			$clues_order_pgw_sql = "insert into clues_order_pgw (order_id, amount, payment_gateway, order_data) values ('".$order_info['order_id']."','".$order_info['total']."','CCAVENUE','".addslashes(serialize($order_data))."')";
			db_query($clues_order_pgw_sql);	
			
			//$thousand_number=number_format($order_info['total'], 2, '.', ',');
// to get thousand formatted number
			
			$oMPI 			= 	new MPIData();
			$oCI			=	new	CardInfo();
			$oPostLibphp	=	new	PostLibPHP();
			$oMerchant		=	new	Merchant();
			$oBTA			=	new	BillToAddress();
			$oSTA			=	new	ShipToAddress();
			$oPGResp		=	new	PGResponse();
			$oPGReserveData =	new PGReserveData();
			
			$oMerchant->setMerchantDetails($Merchant_Id ,$Merchant_Id ,$Merchant_Id ,"",$order_info['order_id']."",$order_info['order_id'],$Redirect_Url,"POST","INR","","req.Sale",$order_info['total'],"","",'true',"","","");
			
			$oBTA->setAddressDetails ("",$order_data['cust_name'],$order_data['custAddress'],"","",$order_data['custCity'],$order_data['custState'],$order_data['custPinCode'],"IND",$order_data['custEmailId']);
			$oSTA->setAddressDetails ($order_data['deliveryAddress'],"","",$order_data['deliveryCity'],$order_data['deliveryState'],$order_data['deliveryPinCode'],"IND",$order_data['custEmailId']);
			
			//$oMPI->setMPIRequestDetails($order_info['total'],"INR".$thousand_number,"356","0","2 shirts","3","20141212","3","0","","image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, application/vnd.ms-powerpoint, application/vnd.ms-excel, application/msword, application/x-shockwave-flash, */*","Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0)");
		
			$oPGResp=$oPostLibphp->postSSL($oBTA,$oSTA,$oMerchant,$oMPI,$oPGReserveData);
			
			
			if($oPGResp->getRespCode() == '000'){
				$url	=$oPGResp->getRedirectionUrl();
				
				redirect($url);
			}else{
				print "Error Occured.<br>";
				print "Error Code:".$oPGResp->getRespCode()."<br>";
				print "Error Message:".$oPGResp->getRespMessage()."<br>";die;
				//fn_change_order_status($Order_Id, 'F', '', true);
			}
			
			
		}
		else if($mode == 'return') {
			
		  $order_id = $_REQUEST['order_id'];
          $order_info = fn_get_order_info($order_id, true);
          $resp_code = $_REQUEST['resp_code'];
		  
		  $response_data = db_get_row("select direcpayreferenceid, order_id, flag, other_details, payment_gateway from clues_prepayment_details where order_id='".$order_id."'");
		  
		  $other_details=explode(',',$response_data['other_details']);
		  $response=explode('=>',$other_details[1]);
		  
		  if (!empty($order_info) )
          { 
                if (fn_check_payment_script('icici_script.php', $order_id)) 
                {
					if($resp_code==0)  //0 means successful transaction
					{
						fn_change_order_status($order_id, 'P', '', true);
					}
					else  //any other response code means transaction declined
					{
						fn_change_order_status($order_id, 'F', '', true);
						$details = 'Transaction Declined By Payment Gateway.Reason:'.$response[1];
                        db_query("update cscart_orders set details='".$details."' where order_id=".$order_id);
					}
                }
           }
            fn_order_placement_routines($order_id, true);
			
			
		}

function redirect($url) {
	if(headers_sent()){
	?>
		<html><head>
			<script language="javascript" type="text/javascript">
				window.self.location='<?php print($url);?>';
			</script>
		</head></html>
	<?php
		exit;
	} else {
		header("Location: ".$url);
		exit;
	}
}

?>
