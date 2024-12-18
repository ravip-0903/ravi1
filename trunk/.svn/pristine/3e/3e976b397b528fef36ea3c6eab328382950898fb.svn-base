<?php
if (!defined('AREA')) {die('Access denied');}

	if(!empty($order_info)){
		
                $current_location = Registry::get('config.current_location');
                $current_location = $current_location.'/'.$index_script;
                //fn_print_die($order_info);
                $Merchant_Id 	= $order_info['payment_method']['params']['merchantid'];
                $salt    	= $order_info['payment_method']['params']['salt'];
                $target_url     = $order_info['payment_method']['params']['url'];
                $Amount 	= $order_info['total'];
                $Order_Id 	= $order_info['order_id'];
                $sql            = "SELECT count(order_id) FROM cscart_order_details where order_id='".$Order_Id."'";
                $itemcnt        = db_get_field($sql);
                $ME_TxnID       = $Order_Id;
                $Redirect_Url 	= $current_location."?dispatch=payment_notification.return&payment=emvantage_script&order_id=".$order_info['order_id'] ;//your redirect URL where your customer will be redirected after authorisation from emvantage
                $Version        = $order_info['payment_method']['params']['version'];
                $API_KEY        = $order_info['payment_method']['params']['api_key'];
                $MerchToken     = $order_info['payment_method']['params']['merchtoken'];
        
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
		$billing_cust_address   = preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['b_address'].' '.$order_info['b_address_2'])));
		$billing_cust_state		= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['b_state'])));
		$billing_cust_country   = preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $b_country)));
		$billing_cust_tel		= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $b_phone)));
		$billing_cust_email		= $order_info['email'];
		$delivery_cust_name		= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['s_firstname'].' '.$order_info['s_lastname'])));
		$delivery_cust_address  = preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['s_address'].' '.$order_info['s_address_2'])));
		$delivery_cust_state    = preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['s_state'])));
		$delivery_cust_country  = preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $s_country)));
		$delivery_cust_tel		= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $s_phone)));
		$delivery_cust_notes            = '';
		
		$billing_city 			= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['b_city'])));
		$billing_zip 			= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['b_zipcode'])));
		$delivery_city 			= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['s_city'])));
		$delivery_zip 			= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['s_zipcode'])));	
		$firstname              = preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['b_firstname'])));              
                
               
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
		$order_data['payment_gateway'] = 'EMVANTAGE';
		$order_data['amount'] = $order_info['total'];
		$order_data['emi_id'] = $order_info['emi_id'];
		$order_data['emi_fee'] = $order_info['emi_fee'];
                $order_data['pgw_promo_code'] = $pgw_promo_code;
                $input  = $Merchant_Id.'|'.$Amount.'|'.$ME_TxnID.'|'.$Redirect_Url.$salt;    
                $thumb = hash('sha256',$input);
                
		$clues_order_pgw_sql = "insert into clues_order_pgw (order_id, amount, payment_gateway, order_data) values ('".$order_info['order_id']."','".$order_info['total']."','".$order_data['payment_gateway']."','".addslashes(serialize($order_data))."')";
		db_query($clues_order_pgw_sql);	
?>
	<form name="emvantage_form" method="post" action="<?php echo $target_url;?> ">
        <INPUT TYPE=Hidden NAME="Version" VALUE="<?php echo $Version ?>">
        <INPUT TYPE=Hidden NAME="API_KEY" VALUE="<?php echo $API_KEY ?>">
        <INPUT TYPE=Hidden NAME="MerchToken" VALUE="<?php echo $MerchToken ?>">
        <INPUT TYPE=Hidden NAME="MerchantID" VALUE="<?php echo $Merchant_Id ?>">
        <INPUT TYPE=Hidden NAME="MerchType" VALUE="RFU">
        <INPUT TYPE=Hidden NAME="TxnType" VALUE="SALE">
        <INPUT TYPE=Hidden NAME="TxnAmount" VALUE="<?php echo $Amount ?>">
        <INPUT TYPE=Hidden NAME="ME_OrderID" VALUE="<?php echo $Order_Id ?>">
        <INPUT TYPE=Hidden NAME="ME_TxnID" VALUE="<?php echo $ME_TxnID ?>">
        <INPUT TYPE=Hidden NAME="CurrencyCode" VALUE="356">
        <INPUT TYPE=Hidden NAME="CountryCode" VALUE="356">
        <INPUT TYPE=Hidden NAME="ME_RFU1" VALUE="">
        <INPUT TYPE=Hidden NAME="ME_RFU2" VALUE="">
        <INPUT TYPE=Hidden NAME="ME_RFU3" VALUE="">
        <INPUT TYPE=Hidden NAME="ME_RFU4" VALUE="">
        <INPUT TYPE=Hidden NAME="ME_RFU5" VALUE="">
        <INPUT TYPE=Hidden NAME="ItemsPurchased" VALUE="Ecommerce">
        <INPUT TYPE=Hidden NAME="ItemQuantity" VALUE="<?php echo $itemcnt ?>">
        <INPUT TYPE=Hidden NAME="ShipAddress1" VALUE="">
        <INPUT TYPE=Hidden NAME="ShipAddress2" VALUE="">
        <INPUT TYPE=Hidden NAME="ShipAddress3" VALUE="">
        <INPUT TYPE=Hidden NAME="ShipCity" VALUE="">
        <INPUT TYPE=Hidden NAME="ShipZipCode" VALUE="">
        <INPUT TYPE=Hidden NAME="ShipState" VALUE="">
        <INPUT TYPE=Hidden NAME="ShipCountry" VALUE="<?php echo $delivery_cust_country ?>">
        <INPUT TYPE=Hidden NAME="BillAddress1" VALUE="<?php echo $billing_cust_address ?>">
        <INPUT TYPE=Hidden NAME="BillAddress2" VALUE="">
        <INPUT TYPE=Hidden NAME="BillAddress3" VALUE="">
        <INPUT TYPE=Hidden NAME="BillCity" VALUE="">
        <INPUT TYPE=Hidden NAME="BillZipCode" VALUE="">
        <INPUT TYPE=Hidden NAME="BillState" VALUE="<?php echo $billing_cust_state ?>">
        <INPUT TYPE=Hidden NAME="BillCountry" VALUE="<?php echo $billing_cust_country ?>">
        <input type="Hidden" name="CustomerName" value="<?php echo $billing_cust_name ?>">
        <INPUT TYPE=Hidden NAME="ME_CustomerID" VALUE="customer">
        <INPUT TYPE=Hidden NAME="ME_ReturnURL" VALUE="<?php echo $Redirect_Url ?>">
        <INPUT TYPE=Hidden NAME="CustomerEmail" VALUE="<?php echo $billing_cust_email ?>">
        <INPUT TYPE=Hidden NAME="CustomerMobile" VALUE="<?php echo $billing_cust_tel ?>">
        <INPUT TYPE=Hidden NAME="ThumbPrint" VALUE="<?php echo $thumb ?>">
        
        <script type="text/javascript">
		document.emvantage_form.submit();
        </script>
        </form>

 <?php                   
                
        }
	else if($_REQUEST['payment'] == 'emvantage_script' && $mode == 'return') {
            //echo "<pre>";print_r($_REQUEST);die;
                $Order_Id       = $_REQUEST['order_id'];
		$order_info     = fn_get_order_info($Order_Id, true);
		$Merchant_Id 	= $order_info['payment_method']['params']['merchantid'];;
		$salt    	= $order_info['payment_method']['params']['salt'];
		$Amount         = $_REQUEST['TxnAmount'];		
		$status         = $_REQUEST['TxnStatus'];
		$transaction_id = $_REQUEST['TxnID'];
                $txdate         = $_REQUEST['TxnDate'];
                $txtime         = $_REQUEST['TxnTime'];
                $txrepomsg      = $_REQUEST['TxnRespMessage'];
                $authcode       = $_REQUEST['TxnAuthCode'];
                $rrn            = $_REQUEST['TxnReference'];
                $thumb          = $_REQUEST['RespThumbPrint'];
                                
                $res_data  = $Merchant_Id.'|'.$transaction_id.'|'.$Order_Id.'|'.$Amount.'|'.$txdate.'|'.$txtime.'|'.$txrepomsg.'|'.$status.'|'.$authcode.'|'.$rrn.$salt;    
                $our_thumb = strtoupper(hash('sha256',$res_data));
                
		db_query("INSERT INTO clues_prepayment_details (direcpayreferenceid, order_id, flag, other_details, amount, payment_gateway) values('".$transaction_id."','".$Order_Id."','".$status."','".addslashes(serialize($_REQUEST))."','".$Amount."','ENVANTAGE')");	
		

		if (!empty($order_info) )
		{ 
			if (fn_check_payment_script('emvantage_script.php', $Order_Id)) 
			{
				if(strcmp($thumb, $our_thumb) == 0 && $status=="0" && strtoupper($txrepomsg)=='APPROVED')
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
				else if(strtolower($status)=="FAILED")
				{
					//echo "<br>Thank you for shopping with us.However,the transaction has been declined.";
					fn_change_order_status($Order_Id, 'F', '', true);
				}
				else if(strcmp($thumb, $our_thumb) == 0 && $status!="0")
				{
					//echo "<br>Thank you for shopping with us.However,the transaction has been declined.";
					fn_change_order_status($Order_Id, 'F', '', true);
				}else if(strcmp($thumb, $our_thumb) != 0)
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
               fn_set_notification('E','Order','There is some error with the order. Please try again','I');
               fn_redirect('index.php?dispatch=checkout.cart');
	}
	else {
		fn_set_notification('N','Order','Your order is not placed. Please try again','I');
		fn_redirect('index.php?dispatch=checkout.cart');
	}	
	exit;		
?>
