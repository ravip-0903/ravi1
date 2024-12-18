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
		
		
		$custName = $order_info['b_firstname'].' '.$order_info['b_lastname'];
		$custAddress = $order_info['b_address'].' '.$order_info['b_address_2'];
		$custCity = $order_info['b_city'];
		$custState = $order_info['b_state'];
		$custPinCode = $order_info['b_zipcode'];
		$custCountry = $order_info['b_country'];
		$custPhoneNo1 = '';
		$custPhoneNo2 = '';
		$custPhoneNo3 = '';
		$custMobileNo = $b_phone;
		$custEmailId = $order_info['email'];
		$deliveryName = $order_info['s_firstname'].' '.$order_info['s_lastname'];
		$deliveryAddress = $order_info['s_address'].' '.$order_info['s_address_2'];
		$deliveryCity = $order_info['s_city'];
		$deliveryState = $order_info['s_state'];
		$deliveryPinCode = $order_info['s_zipcode'];
		$deliveryCountry = $order_info['s_country'];
		$deliveryPhNo1 = '';
		$deliveryPhNo2 = '';
		$deliveryPhNo3 = '';
		$deliveryMobileNo = $s_phone;
		$otherNotes = $order_info['notes'];
		$editAllowed = 'N';
		$requestparameter = $config['direcpay_merchantid'].'|DOM|IND|INR|'.$order_info['total'].'|'.$order_info['order_id'].'|others|'.$config['direcpay_responseurl'].'|'.$config['direcpay_responseurl'].'|'.$config['collaborator_id'];
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
		
		$order_info = fn_get_order_info($_REQUEST['order_id'], true);
		
		if (!empty($order_info) ){ 
		
		if (fn_check_payment_script('direcpay_script.php', $_REQUEST['order_id'])) {
			
			
			if($response['1'] == 'SUCCESS') {
				if ($order_info['status'] == 'N') {
					fn_change_order_status($_REQUEST['order_id'], 'P', '', true);
				}
			} elseif($response['1'] == 'FAIL') {
				if ($order_info['status'] == 'N') {
					fn_change_order_status($_REQUEST['order_id'], 'F', '', true);
				}
			}			
		}
		
		fn_order_placement_routines($_REQUEST['order_id'], true);
		
		
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
