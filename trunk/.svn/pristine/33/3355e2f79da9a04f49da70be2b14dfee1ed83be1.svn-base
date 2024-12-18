<?php
if (!defined('AREA')) {die('Access denied');}

    
	if(!empty($order_info)){
		
            $current_location = Registry::get('config.current_location');
            $current_location = $current_location.'/'.$index_script;

            $Merchant_Id 	= $order_info['payment_method']['params']['merchantid'];
            $access_code 	= $order_info['payment_method']['params']['access_code'];
            $Amount 		= (int)($order_info['total']*100);
            $Order_Id 		= $order_info['order_id'];
            $Redirect_Url 	= 'https://'.Registry::get('config.https_host').Registry::get('config.https_path')."/payments/amex/PHP_VPC_3Party_Auth_Capture_Order_DR.php" ;//your redirect URL where your customer will be redirected after authorisation from AMEX	
            
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

            $billing_cust_name	= preg_replace($patterns, $replacements, trim(preg_replace('/[^a-zA-Z0-9\s]/', " ", $order_info['b_firstname'].' '.$order_info['b_lastname'])));
            $billing_cust_address   = 'NA';
            $billing_cust_state	= 'NA';
            $billing_cust_country   = 'NA';
            $billing_cust_tel	= 'NA';
            $billing_cust_email	= $order_info['email'];
            $delivery_cust_name     = 'NA';
            $delivery_cust_address	= 'NA';
            $delivery_cust_state 	= 'NA';
            $delivery_cust_country 	= 'NA';
            $delivery_cust_tel      = 'NA';
            $delivery_cust_notes	= 'NA';

            $billing_city 		= 'NA';
            $billing_zip 		= 'NA';
            $delivery_city 		= 'NA';
            $delivery_zip 		= 'NA';

            $order_data = array();
            $order_data['cust_name']        = $billing_cust_name;
            $order_data['custAddress']      = $billing_cust_address;
            $order_data['custCity']         = $billing_city;
            $order_data['custState']        = $billing_cust_state;
            $order_data['custPinCode']      = $billing_zip;
            $order_data['custCountry']      = $billing_cust_country;
            $order_data['custMobileNo']     = $billing_cust_tel;
            $order_data['custEmailId']      = $billing_cust_email;
            $order_data['deliveryName']     = $delivery_cust_name;
            $order_data['deliveryAddress']  = $delivery_cust_address;
            $order_data['deliveryCity']     = $delivery_city;
            $order_data['deliveryState']    = $delivery_cust_state;
            $order_data['deliveryPinCode']  = $delivery_zip;
            $order_data['deliveryCountry']  = $delivery_cust_country;
            $order_data['deliveryMobileNo'] = $delivery_cust_tel;
            $order_data['otherNotes']       = $delivery_cust_notes; // customer notes not to send to payment gateway
            $order_data['requestparameter'] = $Redirect_Url.'|'.$Order_Id.'|'.$Amount.'|'.$Merchant_Id;
            $order_data['payment_gateway']  = 'AMEX';
            $order_data['amount']           = $order_info['total'];
            
            $clues_order_pgw_sql = "insert into clues_order_pgw (order_id, amount, payment_gateway, order_data) values ('".$order_info['order_id']."','".$order_info['total']."','AMEX','".addslashes(serialize($order_data))."')";
            if(Registry::get('config.write_pgw_log')){
                    log_to_file('pgw',$clues_order_pgw_sql);	
            }
            $res = db_query($clues_order_pgw_sql);

		
			
?>
	<form name="frmamex" method="post" action="<?php echo "https://".Registry::get('config.https_host').Registry::get('config.https_path')?>/payments/amex/PHP_VPC_3Party_Auth_Capture_Order_DO.php" accept-charset="UTF-8">
        <input type="hidden" name="Title" value = "PHP VPC 3 Party Super Transacion">
        <input type="hidden" name="virtualPaymentClientURL" size="65" value="https://vpos.amxvpos.com/vpcpay" maxlength="250"/>
        <input type="hidden" name="vpc_Version" value="1" size="20" maxlength="8"/>
        <input type="hidden" name="vpc_Command" value="pay" size="20" maxlength="16"/>
        <input type="hidden" name="vpc_AccessCode" value="<?php echo $access_code; ?>" size="20" maxlength="8"/>
        <input type="hidden" name="vpc_MerchTxnRef" value="<?php echo $Order_Id; ?>" size="20" maxlength="40"/>
        <input type="hidden" name="vpc_Merchant" value="<?php echo $Merchant_Id; ?>" size="20" maxlength="16"/>
        <input type="hidden" name="vpc_OrderInfo" value="<?php echo $Order_Id; ?>" size="20" maxlength="34"/>
        <input type="hidden" name="vpc_Amount" value="<?php echo $Amount; ?>" maxlength="10"/>
        <input type="hidden" name="vpc_ReturnURL" size="65" value="<?php echo $Redirect_Url; ?>" maxlength="250"/>
        <input type="hidden" name="vpc_Locale" value="en" size="20" maxlength="5"/>
        
        <input type="hidden" name="vpc_BillTo_Title" value="N/A">
        <input type="hidden" name="vpc_BillTo_Firstname" value="N/A">
        <input type="hidden" name="vpc_BillTo_Middlename" value="N/A">
        <input type="hidden" name="vpc_BillTo_Lastname" value="N/A">
        <input type="hidden" name="vpc_BillTo_Phone" value="N/A">
        <input type="hidden" name="vpc_AVS_Street01" maxlength="20" value="N/A">
        <input type="hidden" name="vpc_AVS_City" value="N/A">
        <input type="hidden" name="vpc_AVS_StateProv" maxlength="5" value="N/A">
        <input type="hidden" name="vpc_AVS_PostCode" maxlength="9" value="N/A">
        <input type="hidden" name="vpc_AVS_Country" value="N/A">
        
        <!--<input type="submit" NAME="SubButL" value="Pay Now!">-->
        <script type="text/javascript">
			document.frmamex.submit();
		</script>
    </form>
<?php			
	} 
	else if($_REQUEST['payment'] == 'amex_script' && $mode == 'return') {
            $order_id = $_REQUEST['order_id'];
            $order_info = fn_get_order_info($order_id, true);            
            $response_data = db_get_row("select direcpayreferenceid, order_id, flag, other_details, amount, payment_gateway,txn_response, 3dstatus from clues_prepayment_details where order_id='".$order_id."'");
            
            //echo '<pre>';print_r($response_data);die("Hello");
            if (!empty($order_info) )
            { 
                if (fn_check_payment_script('amex_script.php', $order_id)) 
                {   
                    $response_data['amount'] = ($response_data['amount']/100);
                    /*if payment capture status = 0 and authrization response is 0  and 3d status is either Y or A then its a successful order*/
                    if($response_data['flag'] == '0' && $response_data['txn_response'] == '0' && ($response_data['3dstatus'] == 'Y' || $response_data['3dstatus'] == 'A')){                       
                        if($response_data['amount'] == $order_info['total']) {
                            fn_change_order_status($order_id, 'P', '', true);
                        }else{
                            fn_change_order_status($order_id, 'K', '', true);
                            $details = '******PAYMENT AMOUNT Rs. '.$response_data['amount'].' NOT SAME AS ORDER TOTAL.******'.$order_info['details'];
                            db_query("update cscart_orders set details='".$details."' where order_id=".$order_id);
                        }
                    }elseif($response_data['flag'] != '0' && $response_data['txn_response'] == '0' && ($response_data['3dstatus'] == 'Y' || $response_data['3dstatus'] == 'A')){
                        /*if payment capture status not 0 and authrization response is 0  and 3d status is either Y or A then its a may be a successful order so put it in payment pending.*/
                        fn_change_order_status($order_id, 'K', '', true);
                        $details = 'Capture failed at payment gateway';
                        db_query("update cscart_orders set details='".$details."' where order_id=".$order_id);
                    }else{
                        /*if authrization response is not 0 means authrizaiton is failed and its a fail order.*/
                        fn_change_order_status($order_id, 'F', '', true);
                    }
                }
            }
            fn_order_placement_routines($order_id, true); 
	}
	elseif ($mode == 'cancel') {	
	//  CANCEL MODE 	
	}	
	exit;	
?>