<?php
if (!defined('AREA')) {die('Access denied');}

include_once (DIR_ROOT . '/payments/ccavenue_files_seam/libfuncs.php');

	if(!empty($order_info)){
	
            $sql = "select cpop.payment_option_pgw_id, cpop.payment_option_id, cpop.payment_gateway_id, cpop.priority, cpop.bank_code, cpop.status, cpt.name as payment_type, cpo.name as payment_option from clues_payment_option_pgw cpop join clues_payment_options cpo on (cpop.payment_option_id=cpo.payment_option_id) join clues_payment_types cpt on (cpo.payment_type_id=cpt.payment_type_id) where cpop.payment_option_id='".$order_info['payment_option_id']."' and cpop.status = 'A' order by priority asc";				
                $pgw_details = db_get_row($sql);
            //echo '<pre>';	print_r($pgw_details);die("chandan");
		$current_location = Registry::get('config.current_location');
		$current_location = $current_location.'/'.$index_script;
		
		$Merchant_Id 	= $order_info['payment_method']['params']['merchantid'];
		$Amount 		= $order_info['total'];
		$Order_Id 		= $order_info['order_id'];
		$Redirect_Url 	= $current_location."?dispatch=payment_notification.return&payment=ccavenue_script_seam&order_id=".$order_info['order_id'] ;//your redirect URL where your customer will be redirected after authorisation from CCAvenue	
		$WorkingKey 	= $order_info['payment_method']['params']['workingkey'];
		$Checksum 		= getCheckSum($Merchant_Id,$Amount,$Order_Id ,$Redirect_Url,$WorkingKey);		
		
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
                
                $card_option = ($pgw_details['payment_type'] == 'Net Banking') ?'netBanking':'NonMoto';
                $payment_select_name = ($pgw_details['payment_type'] == 'Net Banking') ?'netBankingCards':'NonMotoCardType';
                $payment_select_value = $pgw_details['bank_code'];
		
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
                $order_data['cardOption'] = $card_option;
                $order_data['payment_select_name'] = $payment_select_name;
                $order_data['payment_select_value'] = $payment_select_value;
                
       $clues_order_pgw_sql = "insert into clues_order_pgw (order_id, amount, payment_gateway, order_data) values ('".$order_info['order_id']."','".$order_info['total']."','CCAVENUE','".addslashes(serialize($order_data))."')";
		if(Registry::get('config.write_pgw_log')){
			log_to_file('pgw',$clues_order_pgw_sql);	
		}
		$res = db_query($clues_order_pgw_sql);	
		
?>
	<form name="frmccev" method="post" action="https://www.ccavenue.com/servlet/new_txn.PaymentIntegration">
        <input type="hidden" name="Merchant_Id" value="<?php echo $Merchant_Id; ?>">
        <input type="hidden" name="Amount" value="<?php echo $Amount; ?>">
        <input type="hidden" name="Order_Id" value="<?php echo $Order_Id; ?>">
        <input type="hidden" name="Redirect_Url" value="<?php echo $Redirect_Url; ?>">
        <input type="hidden" name="Checksum" value="<?php echo $Checksum; ?>">
        <input type="hidden" name="billing_cust_name" value="<?php echo $billing_cust_name; ?>"> 
        <input type="hidden" name="billing_cust_address" value="<?php echo $billing_cust_address; ?>"> 
        <input type="hidden" name="billing_cust_country" value="<?php echo $billing_cust_country; ?>"> 
        <input type="hidden" name="billing_cust_state" value="<?php echo $billing_cust_state; ?>"> 
        <input type="hidden" name="billing_zip" value="<?php echo $billing_zip; ?>">
        <input type="hidden" name="billing_cust_tel" value="<?php echo $billing_cust_tel; ?>"> 
        <input type="hidden" name="billing_cust_email" value="<?php echo $billing_cust_email; ?>"> 
        <input type="hidden" name="delivery_cust_name" value="<?php echo $delivery_cust_name; ?>"> 
        <input type="hidden" name="delivery_cust_address" value="<?php echo $delivery_cust_address; ?>"> 
        <input type="hidden" name="delivery_cust_country" value="<?php echo $delivery_cust_country; ?>"> 
        <input type="hidden" name="delivery_cust_state" value="<?php echo $delivery_cust_state; ?>">
        <input type="hidden" name="delivery_cust_tel" value="<?php echo $delivery_cust_tel; ?>"> 
        <input type="hidden" name="delivery_cust_notes" value="<?php echo $delivery_cust_notes; ?>"> 
        <input type="hidden" name="Merchant_Param" value="<?php echo $Merchant_Param; ?>"> 
        <input type="hidden" name="cardOption" value="<?php echo $card_option; ?>"> 
        <input type="hidden" name="<?php echo $payment_select_name; ?>" value="<?php echo $payment_select_value; ?>">
        
        <input type="hidden" name="billing_cust_city" value="<?php echo $billing_city; ?>"> 
        <input type="hidden" name="billing_zip_code" value="<?php echo $billing_zip; ?>"> 
        <input type="hidden" name="delivery_cust_city" value="<?php echo $delivery_city; ?>"> 
        <input type="hidden" name="delivery_zip_code" value="<?php echo $delivery_zip; ?>"> 
        <input type="hidden" name="billingDeliveryOption" value="no">
        <!--<INPUT TYPE="submit" value="submit">-->
        <script type="text/javascript">
			document.frmccev.submit();
		</script>
    </form>
<?			
	} 
	else if($_REQUEST['payment'] == 'ccavenue_script_seam' && $mode == 'return') {
		//echo '<pre>';print_r($_REQUEST);die;
		$Order_Id= $_REQUEST['Order_Id'];
		$order_info = fn_get_order_info($Order_Id, true);
		//echo '<pre>';print_r($order_info);die("chandan");
		$WorkingKey = $order_info['payment_method']['params']['workingkey'] ; 
		$Merchant_Id= $_REQUEST['Merchant_Id'];
		$Amount= $_REQUEST['Amount'];		
		$Merchant_Param= $_REQUEST['Merchant_Param'];
		$Checksum= $_REQUEST['Checksum'];
		$AuthDesc=$_REQUEST['AuthDesc'];
		$transaction_id = $_REQUEST['nb_order_no'];
			
		$Checksum = verifyChecksum($Merchant_Id, $Order_Id , $Amount,$AuthDesc,$Checksum,$WorkingKey);	
		
		db_query("INSERT INTO clues_prepayment_details (direcpayreferenceid, order_id, flag, other_details, amount, payment_gateway) values('".$transaction_id."','".$Order_Id."','".$AuthDesc."','".addslashes(serialize($_REQUEST))."','".$Amount."','CCAVENUE')");	
		
		
		
		if (!empty($order_info) )
		{ 
			if (fn_check_payment_script('ccavenue_script_seam.php', $Order_Id)) 
			{
				if($Checksum=="true" && $AuthDesc=="Y")
				{
					//echo "<br>Thank you for shopping with us. Your credit card has been charged and your transaction is successful. We will be shipping your order to you soon.";
					if($Amount == $order_info['total']) {
					   fn_change_order_status($Order_Id, 'P', '', true);
					}else{
					   fn_change_order_status($Order_Id, 'K', '', true);
                       $details = '******PAYMENT AMOUNT Rs. '.$Amount.' NOT SAME AS ORDER TOTAL.******'.$order_info['details'];
                       db_query("update cscart_orders set details='".$details."' where order_id=".$Order_Id);
					}
                    
				}
				else if($Checksum=="true" && $AuthDesc=="B")
				{
					//echo "<br>Thank you for shopping with us.We will keep you posted regarding the status of your order through e-mail";
					//fn_change_order_status($Order_Id, 'O', '', true);
                    if($Amount == $order_info['total']) {
					   fn_change_order_status($Order_Id, 'K', '', true);
                       $details = '******PAYMENT MAY BE SUCCESS ON CCAVENUE.******'.$order_info['details'];
                       db_query("update cscart_orders set details='".$details."' where order_id=".$Order_Id);
					}else{
					   fn_change_order_status($Order_Id, 'K', '', true);
                       $details = '******PAYMENT AMOUNT Rs. '.$Amount.' NOT SAME AS ORDER TOTAL AND PAYMENT MAY BE SUCCESS ON CCAVENUE.******'.$order_info['details'];
                       db_query("update cscart_orders set details='".$details."' where order_id=".$Order_Id);
					}
				}
				else if($Checksum=="true" && $AuthDesc=="N")
				{
					//echo "<br>Thank you for shopping with us.However,the transaction has been declined.";
					fn_change_order_status($Order_Id, 'F', '', true);
				}
				else
				{
					//echo "<br>Security Error. Illegal access detected";
					fn_set_notification('E','Order','There is some error with the order. Please try again','I');
					fn_redirect('index.php?dispatch=checkout.cart');
				}
			}
			fn_order_placement_routines($Order_Id, true);
		}
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