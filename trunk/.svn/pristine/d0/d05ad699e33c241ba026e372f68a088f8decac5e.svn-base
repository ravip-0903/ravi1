<?php
if (!defined('AREA')) {die('Access denied');}

        set_include_path(DIR_PAYMENT_FILES.'/cit-lib'.PATH_SEPARATOR.get_include_path());            
        require_once(DIR_PAYMENT_FILES.'/cit-lib/CitrusPay.php');
        require_once(DIR_PAYMENT_FILES.'/cit-lib/Zend/Crypt/Hmac.php');
        
        
        function generateHmacKey($data, $apiKey=null){
                $hmackey = Zend_Crypt_Hmac::compute($apiKey, "sha1", $data);
                return $hmackey;
        }
        
	if(!empty($order_info)){
            
                $current_location = Registry::get('config.current_location');
		//$current_location = $current_location.'/'.$index_script;              
                $redirectUrl = "";
                
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
                $s_country = fn_get_country_name($order_info['s_country']);
                $b_country = fn_get_country_name($order_info['b_country']);
                
		$merchantAccessKey   =       $order_info['payment_method']['params']['access_key'];
                $transactionID       =       $order_info['order_id'];
                $addressState        =       preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['s_state'])));
                $addressCity         =       preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['s_city'])));
                $addressStreet1      =       preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['s_address'].' '.$order_info['s_address_2'])));
                $addressCountry      =       preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $s_country)));
                $addressZip          =       preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['s_zipcode'])));
                $firstName           =       preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['s_firstname'])));
                $lastName            =       preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['s_lastname'])));
                $phoneNumber         =       $s_phone;
                $email               =       $order_info['email'];
                $returnUrl           =       $current_location."/payments/citruspay_res.php";
                $amount              =       $order_info['total'];		
		$cardHolderName = "";
                $cardNumber = "";
                $expiryMonth = "";
                $cardType = "";
                $cvvNumber = "";
                $expiryYear = "";
                
		
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
		$billing_city 			= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['b_city'])));
		$billing_zip 			= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['b_zipcode'])));
		$delivery_city 			= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['s_city'])));
		$delivery_zip 			= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['s_zipcode'])));	
		$firstname                      = preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['b_firstname'])));
              
                $sql = "select cp.payment_option_id, cp.payment_gateway_id, cp.payment_option_pgw_id, cp.bank_code, cp.priority, cpo.payment_type_id
                        from clues_payment_option_pgw cp
                        join clues_payment_options cpo on cpo.payment_option_id = cp.payment_option_id
                        where cp.priority='1' and cp.status='A' and cp.payment_option_id='".$order_info['payment_option_id']."'";
                $payment_details = db_get_row($sql);
                $paymentMode = '';
                $issuerCode = '';
                if($payment_details['payment_type_id'] == '1'){
                    $paymentMode = 'NET_BANKING';
                    $issuerCode = $payment_details['bank_code'];
                }elseif($payment_details['payment_type_id'] == '2'){
                    $paymentMode = 'CREDIT_CARD';
                }elseif($payment_details['payment_type_id'] == '3'){
                    $paymentMode = 'DEBIT_CARD';
                }
                //fn_print_die($payment_details);

                $tarr = array(
			"merchantAccessKey" => "$merchantAccessKey",
			"transactionId" => "$transactionID",
			"issuerCode" => "$issuerCode",
			"addressState" => "$addressState",
			"addressCity" => "$addressCity",
			"addressStreet1" => "$addressStreet1",
			"addressCountry" => "$addressCountry",
			"addressZip" => "$addressZip",
			"firstName" => "$firstName",
			"lastName" => "$lastName",
			"phoneNumber" => "$phoneNumber",
			"email" => "$email",
			"paymentMode" => "$paymentMode",
			"cardHolderName" => "$cardHolderName",
			"cardNumber" => "$cardNumber",
			"expiryMonth" => "$expiryMonth",
			"cardType" => "$cardType",
			"cvvNumber" => "$cvvNumber",
			"expiryYear" => "$expiryYear",
			"returnUrl" => "$returnUrl",
			"amount" => "$amount"
                );

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
		$order_data['payment_gateway'] = 'CITRUSPAY';
		$order_data['amount']  = $order_info['total'];
		$order_data['emi_id']  = $order_info['emi_id'];
		$order_data['emi_fee'] = $order_info['emi_fee'];
                $order_data['tarr']    = serialize($tarr);

		$clues_order_pgw_sql = "insert into clues_order_pgw (order_id, amount, payment_gateway, order_data) values ('".$order_info['order_id']."','".$order_info['total']."','".$order_data['payment_gateway']."','".addslashes(serialize($order_data))."')";
		db_query($clues_order_pgw_sql);	
                
                if(isset($order_info['payment_method']['params']['test_mode']) && $order_info['payment_method']['params']['test_mode']=='on')
                {
                    CitrusPay::setApiKey($order_info['payment_method']['params']['merchantkey'],'sandbox');
                }
                else
                {
                    CitrusPay::setApiKey($order_info['payment_method']['params']['merchantkey'],'production');
                }
                
                $response = Transaction::create($tarr,CitrusPay::getApiKey());
                $redirectUrl = $response->get_redirect_url();
                $response_code = $response->get_resp_code();
               
                if($redirectUrl != "" && $response_code == 200)
                {
                    
?>
                <script>
                       window.top.location = "<?php echo $redirectUrl; ?>";
                </script>

<?php
    
                }
                else
                {
                    fn_set_notification('E','Order','There is some error with the order. Please try again','I');
                    fn_redirect('index.php?dispatch=checkout.cart');
                }
	
	} 
	else if($_REQUEST['payment'] == 'citruspay_script' && $mode == 'return') {
		//echo '<pre>';print_r($_REQUEST);die;
                
                $order_id = $_REQUEST['order_id'];
                $order_info     = fn_get_order_info($order_id, true);
                
                $sql = "select other_details from clues_prepayment_details where order_id='".$order_id."'";
                $resdata = unserialize(db_get_field($sql));
                
                $merchant_key 	= $order_info['payment_method']['params']['merchantkey'];
                $amount         = $resdata['amount'];
                $status         = $resdata['TxStatus'];
                $transaction_id = $resdata['pgTxnNo'];
                $issuerRefNo    = $resdata['issuerRefNo'];
                $authIdCode     = $resdata['authIdCode'];
                $firstName      = $resdata['firstName'];
                $lastName       = $resdata['lastName'];
                $pgRespCode     = $resdata['pgRespCode'];
                $addressZip     = $resdata['addressZip'];
                $signature      = $resdata['signature'];

                $data = $order_id.$status.$amount.$transaction_id.$issuerRefNo.$authIdCode.$firstName.$lastName.$pgRespCode.$addressZip;
                $respSignature = generateHmacKey($data,$merchant_key);

                $authentication = '0';
                if(strcmp($respSignature, $signature)=='0')
                {            
                        $authentication = '1';		
                }
                
                
		if (!empty($order_info) )
		{ 
			if (fn_check_payment_script('citruspay_script.php', $order_id)) 
			{
				if($authentication && strtolower($status)=="success")
				{
					//echo "<br>Thank you for shopping with us. Your credit card has been charged and your transaction is successful. We will be shipping your order to you soon.";
					if($amount == $order_info['total']) {
					   fn_change_order_status($order_id, 'P', '', true);
					}else{
					   fn_change_order_status($order_id, 'K', '', true);
                                            $details = '******PAYMENT AMOUNT Rs. '.$amount.' NOT SAME AS ORDER TOTAL.******'.$order_info['details'];
                                            db_query("update cscart_orders set details='".$details."' where order_id=".$order_id);
					}
                    
				}
				else if($authentication && strtolower($status)=="fail")
				{
					//echo "<br>Thank you for shopping with us.However,the transaction has been declined.";
					fn_change_order_status($order_id, 'F', '', true);
				}else if($authentication && strtolower($status)=="canceled")
				{
					//echo "<br>Thank you for shopping with us.However,the transaction has been declined.";
					fn_change_order_status($order_id, 'D', '', true);
				}else if(!$authentication)
				{
					//echo "<br>Thank you for shopping with us.However,the transaction has been declined.";
					fn_change_order_status($order_id, 'F', '', true);
				}
				else
				{
					//echo "<br>Security Error. Illegal access detected";
					fn_set_notification('E','Order','There is some error with the order. Please try again','I');
					fn_redirect('index.php?dispatch=checkout.cart');
				}
			}
			fn_order_placement_routines($order_id, true);
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
