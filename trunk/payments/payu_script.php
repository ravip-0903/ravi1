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
		$Redirect_Url 	= $current_location."?dispatch=payment_notification.return&payment=payu_script&order_id=".$order_info['order_id'] ;//your redirect URL where your customer will be redirected after authorisation from CCAvenue	
                $cod_Url 	= $current_location."?dispatch=payment_notification.return&payment=payu_script&action=6&order_id=".$order_info['order_id'];
                $send_product_name_to_payu = Registry::get('config.send_product_name_to_payu');
                $product_info = '';
                if($send_product_name_to_payu == '0'){
                    $product_info = Registry::get('config.send_product_name_to_payu_fixed_value');
                }elseif($send_product_name_to_payu == '1'){
                    if(count($order_info['items']) > 1){
                        $product_info = 'Multiple Product';
                    }else{
                        $product_info = 'Single Product';
                    }
                }elseif($send_product_name_to_payu == '2'){
                    $product_info = array();
                    foreach($order_info['items'] as $item){
                        $product_info[] = $item['product'];
                    }
                    $product_info = implode(',',$product_info);
                }
		//fn_print_die($product_info);
		//$Checksum 		= getCheckSum($Merchant_Id,$Amount,$Order_Id ,$Redirect_Url,$WorkingKey);		
		
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
		$billing_cust_address           = preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['b_address'].' '.$order_info['b_address_2'])));
		$billing_cust_state		= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['b_state'])));
		$billing_cust_country           = preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $b_country)));
		$billing_cust_tel		= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $b_phone)));
		$billing_cust_email		= $order_info['email'];
		$delivery_cust_name		= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['s_firstname'].' '.$order_info['s_lastname'])));
		$delivery_cust_address          = preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['s_address'].' '.$order_info['s_address_2'])));
		$delivery_cust_state            = preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['s_state'])));
		$delivery_cust_country          = preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $s_country)));
		$delivery_cust_tel		= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $s_phone)));
		$delivery_cust_notes            = '';
		if(isset($child_ids) && $child_ids !='') {
			$Merchant_Param		= $child_ids;
		}else {
			$Merchant_Param		= "";
		}
		$billing_city 			= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['b_city'])));
		$billing_zip 			= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['b_zipcode'])));
		$delivery_city 			= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['s_city'])));
		$delivery_zip 			= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['s_zipcode'])));	
		$firstname                      = preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['b_firstname'])));
		$product_info			= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $product_info)));                
                $hash_string    = $Merchant_Id.'|'.$Order_Id.'|'.$Amount.'|'.$product_info.'|'.$firstname.'|'.$billing_cust_email.'|||||||||||'.$salt;
                $hash = strtolower(hash('sha512', $hash_string));
                //die("Hi");
                $user_credentials = $Merchant_Id.':'.md5(trim($order_info['user_id']).trim($order_info['email']));

                $sql = "select cp.payment_option_id, cp.payment_gateway_id, cp.payment_option_pgw_id, cp.bank_code, cp.priority, cpo.payment_type_id
                        from clues_payment_option_pgw cp
                        join clues_payment_options cpo on cpo.payment_option_id = cp.payment_option_id
                        where cp.priority='1' and cp.status='A' and cp.payment_option_id='".$order_info['payment_option_id']."'";
                $payment_details = db_get_row($sql);
                
                if(empty($payment_details)){
                    $sql = "select cpe.payment_option_id, cpe.payment_gateway_id, cpo.payment_type_id
                            from clues_payment_options_emi_pgw cpe
                            join clues_payment_options cpo on cpo.payment_option_id = cpe.payment_option_id
                            where cpe.status='A' and cpe.payment_option_id='".$order_info['payment_option_id']."'";
                    $payment_details = db_get_row($sql);
                }
                $pg = '';
                $bank_code = '';
                if($payment_details['payment_type_id'] == '1'){
                    $pg = 'NB';
                    $bank_code = $payment_details['bank_code'];
                }elseif($payment_details['payment_type_id'] == '2'){
                    $pg = 'CC';
                }elseif($payment_details['payment_type_id'] == '3'){
                    $pg = 'DC';
                }elseif($payment_details['payment_type_id'] == '5'){
                    $pg = 'CASH';
                }elseif($payment_details['payment_type_id'] == '6'){
                    $pg = 'EMI';
                }
                //fn_print_die($payment_details);
                
                if($order_info['promotion_ids'] != '')
                {
                    $sql = "select pgw_promo_code from clues_pgw_promotion where payment_id='".$order_info['payment_id']."' and promotion_id in (".$order_info['promotion_ids'].") limit 0,1";
                    $pgw_promo_code = db_get_field($sql);
                }
               
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
                if ($order_info['cod_eligible'] == 1)
                {
                    $order_data['codurl'] = $cod_Url;
                }
		$order_data['otherNotes'] = $delivery_cust_notes; // customer notes not to send to payment gateway
		$order_data['editAllowed'] = 'not a parameter in ccavenue';
		$order_data['requestparameter'] = $Redirect_Url.'|'.$Order_Id.'|'.$Amount.'|'.$Merchant_Id;
		$order_data['payment_gateway'] = 'PAYU';
		$order_data['amount'] = $order_info['total'];
		$order_data['emi_id'] = $order_info['emi_id'];
		$order_data['emi_fee'] = $order_info['emi_fee'];
                $order_data['pgw_promo_code'] = $pgw_promo_code;
                $order_data['user_credentials'] = $user_credentials;
		$clues_order_pgw_sql = "insert into clues_order_pgw (order_id, amount, payment_gateway, order_data) values ('".$order_info['order_id']."','".$order_info['total']."','".$order_data['payment_gateway']."','".addslashes(serialize($order_data))."')";
		db_query($clues_order_pgw_sql);	
?>
	<form name="frm_payu" method="post" action="<?php echo $target_url; ?> ">
        <input type="hidden" name="key" value="<?php echo $Merchant_Id; ?>">        
        <input type="hidden" name="amount" value="<?php echo $Amount; ?>">
        <input type="hidden" name="txnid" value="<?php echo $Order_Id; ?>">
        <input type="hidden" name="firstname" value="<?php echo $firstname; ?>"> 
        <input type="hidden" name="productinfo" value="<?php echo $product_info; ?>"> 
        <input type="hidden" name="email" value="<?php echo $billing_cust_email; ?>">
        <input type="hidden" name="phone" value="<?php echo $billing_cust_tel; ?>">        
        <input type="hidden" name="surl" value="<?php echo $Redirect_Url; ?>">        
        <input type="hidden" name="furl" value="<?php echo $Redirect_Url; ?>">
        <?php
                if ($order_info['cod_eligible'] == 1)
                {
        ?>
        <input type="hidden" name="codurl" value="<?php echo $cod_Url; ?>">
        <?php
                }
        ?>
        <input type="hidden" name="hash" value="<?php echo $hash; ?>">
        <input type="hidden" name="pg" value="<?php echo $pg; ?>">
        <input type="hidden" name="user_credentials" value="<?php echo $user_credentials; ?>">
        <?php if(isset($bank_code) && $bank_code != '') { ?>
        <input type="hidden" name="bankcode" value="<?php echo $bank_code; ?>">
        <?php } ?>
        <?php if(isset($pgw_promo_code) && $pgw_promo_code != '') { ?>
        <input type="hidden" name="offer_key" value="<?php echo $pgw_promo_code; ?>">
        <?php } ?>
        <!--<INPUT TYPE="submit" value="submit">-->
        <script type="text/javascript">
			document.frm_payu.submit();
		</script>
    </form>
<?php			
	} 
        else if($_REQUEST['payment'] == 'payu_script' && $mode == 'return' && $_REQUEST['action'] == 6) {
            $cod_data = check_for_cod_eligible($_REQUEST['order_id']);
            if(isset($cod_data) && !empty($cod_data))
             {
                if ($cod_data['cod_eligible'] == 1)
                {
 ?> 
<form method="POST" action="<? echo fn_url(''); ?>" name="frm_payu_cod">
    <input type="hidden" name="order_id" value="<? echo $cod_data['order_id']; ?>">
    <input type="hidden" name="user_id" value="<? echo $cod_data['user_id']; ?>">
    <input type="hidden" name="key" value="<? echo $cod_data['key']; ?>">
    <input type="hidden" name="dispatch" value="checkout.place_cod_order">
    <script type="text/javascript">
            document.frm_payu_cod.submit();
    </script>
</form>
 <?php                   
                }
             }
                
        }
	else if($_REQUEST['payment'] == 'payu_script' && $mode == 'return') {
		//echo '<pre>';print_r($_REQUEST);//die;
                $txnRs = array();
                foreach($_POST as $key => $value) {
                    $txnRs[$key] = htmlentities($value, ENT_QUOTES);
                }
		$Order_Id       = $_REQUEST['order_id'];
		$order_info     = fn_get_order_info($Order_Id, true);
                //echo '<pre>';print_r($txnRs);print_r($order_info);die;
		$Merchant_Id 	= $order_info['payment_method']['params']['merchantid'];
		$salt    	= $order_info['payment_method']['params']['salt'];
		$Amount         = $txnRs['amount'];		
		//$Merchant_Param = $txnRs['Merchant_Param'];
		//$Checksum       = $_REQUEST['Checksum'];
		$status         = $txnRs['status'];
		$transaction_id = $txnRs['mihpayid'];
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
		$firstname      = preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['b_firstname'])));
                $send_product_name_to_payu = Registry::get('config.send_product_name_to_payu');
                $product_info = '';
                if($send_product_name_to_payu == '0'){
                    $product_info = Registry::get('config.send_product_name_to_payu_fixed_value');
                }elseif($send_product_name_to_payu == '1'){
                    if(count($order_info['items']) > 1){
                        $product_info = 'Multiple Product';
                    }else{
                        $product_info = 'Single Product';
                    }
                }elseif($send_product_name_to_payu == '2'){
                    $product_info = array();
                    foreach($order_info['items'] as $item){
                        $product_info[] = $item['product'];
                    }
                    $product_info = implode(',',$product_info);
                }
               $product_info	= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $product_info)));
                
                $hash_string    = $salt.'|'.$status.'|||||||||||'.$order_info['email'].'|'.$firstname.'|'.$product_info.'|'.$Amount.'|'.$Order_Id.'|'.$Merchant_Id;
               	$hash = strtolower(hash('sha512', $hash_string));
                //echo '<br>'.$txnRs['hash'];print_r($order_info);	//	die;
                
		db_query("INSERT INTO clues_prepayment_details (direcpayreferenceid, order_id, flag, other_details, amount, payment_gateway) values('".$transaction_id."','".$Order_Id."','".$status."','".addslashes(serialize($txnRs))."','".$Amount."','PAYU')");	
		
		
		
		if (!empty($order_info) )
		{ 
			if (fn_check_payment_script('payu_script.php', $Order_Id)) 
			{
				if($hash==$txnRs['hash'] && strtolower($status)=="success")
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
				else if($hash==$txnRs['hash'] && strtolower($status)=="pending")
				{
					//echo "<br>Thank you for shopping with us.We will keep you posted regarding the status of your order through e-mail";
					//fn_change_order_status($Order_Id, 'O', '', true);
                                        if($Amount == $order_info['total']) {
                                            fn_change_order_status($Order_Id, 'K', '', true);
                                            $details = '******PAYMENT MAY BE SUCCESS ON Payu.******'.$order_info['details'];
                                            db_query("update cscart_orders set details='".$details."' where order_id=".$Order_Id);
                                         }else{
                                            fn_change_order_status($Order_Id, 'K', '', true);
                                            $details = '******PAYMENT AMOUNT Rs. '.$Amount.' NOT SAME AS ORDER TOTAL AND PAYMENT MAY BE SUCCESS ON payu.******'.$order_info['details'];
                                           db_query("update cscart_orders set details='".$details."' where order_id=".$Order_Id);
					}
				}
				else if($hash==$txnRs['hash'] && strtolower($status)=="failure")
				{
					//echo "<br>Thank you for shopping with us.However,the transaction has been declined.";
					fn_change_order_status($Order_Id, 'F', '', true);
				}else if($hash!=$txnRs['hash'])
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