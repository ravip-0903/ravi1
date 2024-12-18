<?php
	
	
	
	
	if(!empty($order_info)){
		
		$current_location = Registry::get('config.current_location');
		$current_location = $current_location.'/'.$index_script;
		
		$config['direcpay_merchantid'] = $order_info['payment_method']['params']['merchantid'];
		$config['direcpay_responseurl'] = $current_location."?dispatch=payment_notification.return&payment=direcpay_script&order_id=".$order_info['order_id'];
		
		if($order_info['payment_method']['params']['testmode'] == 'on') {
			$config['direcpay_payment_url'] = 'https://test.timesofmoney.com/direcpay/secure/dpMerchantTransaction.jsp';
			$config['collaborator_id'] = 'TOML';
		} else {
			$config['direcpay_payment_url'] = 'https://www.timesofmoney.com/direcpay/secure/dpMerchantTransaction.jsp';	
			$config['collaborator_id'] = 'DirecPay';
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
		
		
		$custName 			= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s,]/', " ", $order_info['b_firstname'].' '.$order_info['b_lastname'])));
		$custAddress 		= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s,]/', " ", $order_info['b_address'].' '.$order_info['b_address_2'])));
		$custCity 			= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s,]/', " ", $order_info['b_city'])));
		$custState 			= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s,]/', " ", $order_info['b_state'])));
		$custPinCode 		= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s,]/', " ", $order_info['b_zipcode'])));
		$custCountry 		= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s,]/', " ", $order_info['b_country'])));
		$custPhoneNo1 		= '';
		$custPhoneNo2 		= '';
		$custPhoneNo3 		= '';
		$custMobileNo 		= $b_phone;
		$custEmailId 		= $order_info['email'];
		$deliveryName 		= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s,]/', " ", $order_info['s_firstname'].' '.$order_info['s_lastname'])));
		$deliveryAddress 	= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s,]/', " ", $order_info['s_address'].' '.$order_info['s_address_2'])));
		$deliveryCity 		= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s,]/', " ", $order_info['s_city'])));
		$deliveryState 		= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s,]/', " ", $order_info['s_state'])));
		$deliveryPinCode 	= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s,]/', " ", $order_info['s_zipcode'])));
		$deliveryCountry 	= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s,]/', " ", $order_info['s_country'])));
		$deliveryPhNo1 		= '';
		$deliveryPhNo2 		= '';
		$deliveryPhNo3 		= '';
		$deliveryMobileNo 	= $s_phone;
		$otherNotes 		= ''; // customer notes not to send to payment gateway
		$editAllowed 		= 'N';
		$requestparameter = $config['direcpay_merchantid'].'|DOM|IND|INR|'.$order_info['total'].'|'.$order_info['order_id'].'|others|'.$config['direcpay_responseurl'].'|'.$config['direcpay_responseurl'].'|'.$config['collaborator_id'];
		

		$order_data = array();
		$order_data['cust_name'] = $custName;
		$order_data['custAddress'] = $custAddress;
		$order_data['custCity'] = $custCity;
		$order_data['custState'] = $custState;
		$order_data['custPinCode'] = $custPinCode;
		$order_data['custCountry'] = $custCountry;
		$order_data['custMobileNo'] = $custMobileNo;
		$order_data['custEmailId'] = $custEmailId;
		$order_data['deliveryName'] = $deliveryName;
		$order_data['deliveryAddress'] = $deliveryAddress;
		$order_data['deliveryCity'] = $deliveryCity;
		$order_data['deliveryState'] = $deliveryState;
		$order_data['deliveryPinCode'] = $deliveryPinCode;
		$order_data['deliveryCountry'] = $deliveryCountry;
		$order_data['deliveryMobileNo'] = $deliveryMobileNo;
		$order_data['otherNotes'] = $otherNotes;
		$order_data['editAllowed'] = $editAllowed;
		$order_data['requestparameter'] = $requestparameter;
		$order_data['payment_gateway'] = 'DirecPay';
		$order_data['amount'] = $order_info['total'];
		$clues_order_pgw_sql = "insert into clues_order_pgw (order_id, amount, payment_gateway, order_data) values ('".$order_info['order_id']."','".$order_info['total']."','Direcpay','".addslashes(serialize($order_data))."')";
		db_query($clues_order_pgw_sql);
		
?>
		
		<script src="js/dpEncodeRequest.js"></script>
		<form name="ecom" method="post" action="<?php echo $config['direcpay_payment_url'];?>">
			<input type="hidden" name="custName" value="<?php echo $custName; ?>">
			<input type="hidden" name="custAddress" value="<?php echo $custAddress; ?>">
			<input type="hidden" name="custCity" value="<?php echo $custCity; ?>">
			<input type="hidden" name="custState" value="<?php echo $custState; ?>">
			<input type="hidden" name="custPinCode" value="<?php echo $custPinCode; ?>">
			<input type="hidden" name="custCountry" value="<?php echo $custCountry; ?>">
			<input type="hidden" name="custPhoneNo1" value="<?php echo $custPhoneNo1; ?>">
			<input type="hidden" name="custPhoneNo2" value="<?php echo $custPhoneNo2; ?>">
			<input type="hidden" name="custPhoneNo3" value="<?php echo $custPhoneNo3; ?>">
			<input type="hidden" name="custMobileNo" value="<?php echo $custMobileNo; ?>">
			<input type="hidden" name="custEmailId" value="<?php echo $custEmailId; ?>">
			<input type="hidden" name="deliveryName" value="<?php echo $deliveryName; ?>">
			<input type="hidden" name="deliveryAddress" value="<?php echo $deliveryAddress; ?>">
			<input type="hidden" name="deliveryCity" value="<?php echo $deliveryCity; ?>">
			<input type="hidden" name="deliveryState" value="<?php echo $deliveryState; ?>">
			<input type="hidden" name="deliveryPinCode" value="<?php echo $deliveryPinCode; ?>">
			<input type="hidden" name="deliveryCountry" value="<?php echo $deliveryCountry; ?>">
			<input type="hidden" name="deliveryPhNo1" value="<?php echo $deliveryPhNo1; ?>">
			<input type="hidden" name="deliveryPhNo2" value="<?php echo $deliveryPhNo2; ?>">
			<input type="hidden" name="deliveryPhNo3" value="<?php echo $deliveryPhNo3; ?>">
			<input type="hidden" name="deliveryMobileNo" value="<?php echo $deliveryMobileNo; ?>">
			<input type="hidden" name="otherNotes" value="<?php echo $otherNotes; ?>">
			<input type="hidden" name="editAllowed" value="<?php echo $editAllowed; ?>">
			<input type="hidden" name="requestparameter" value="<?php echo $requestparameter; ?>"> 
			<script type="text/javascript">
				document.ecom.requestparameter.value = encodeValue(document.ecom.requestparameter.value);
				document.ecom.submit();
			</script>
		</form>
<?			
	} 
	else if($_REQUEST['payment'] == 'direcpay_script' && $mode == 'return') {
	
		$response = explode('|', $_REQUEST['responseparams']);
		list($direcpayreferenceid, $flag, $country, $currency, $otherdetails, $merchantorderno, $amount) = explode('|', $_REQUEST['responseparams']);
		
		db_query("INSERT INTO clues_prepayment_details (direcpayreferenceid, order_id, flag, other_details, amount, payment_gateway) values('".$direcpayreferenceid."','".$merchantorderno."','".$flag."','".addslashes($otherdetails)."','".$amount."','DirecPay')");		
		
		$order_info = fn_get_order_info($_REQUEST['order_id'], true);
		
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
                       db_query("update cscart_orders set details='".$details."' where order_id=".$_REQUEST['order_id']);
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
		//exit;
	}
	elseif ($mode == 'cancel') {
	
	//  CANCEL MODE 
	
	}
	else {
		fn_set_notification('N','Order','Your order is not placed. Please try again','I');
		fn_redirect('index.php?dispatch=checkout.cart');
		}
	
	
	exit;
	
?>
