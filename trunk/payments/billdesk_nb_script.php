<?php
if (!defined('AREA')) {die('Access denied');}

	if(!empty($order_info)){
		
		$current_location = Registry::get('config.current_location');
		$current_location = $current_location.'/'.$index_script;
		//fn_print_die($order_info);
		$Merchant_Id 	= $order_info['payment_method']['params']['merchantid'];
		$checksum_key  	= $order_info['payment_method']['params']['checksum_key'];
                $payment_url    = $order_info['payment_method']['params']['payment_url'];
                $security_id	= 'shopclues';
		$Amount 	= $order_info['total'];
		$Order_Id 	= $order_info['order_id'];
		$Redirect_Url 	= $current_location."?dispatch=payment_notification.return&payment=billdesk_nb_script&order_id=".$order_info['order_id'] ;//your redirect URL where your customer will be redirected after authorisation from Billdesk
                
                $sql = "select cp.payment_option_id, cp.payment_gateway_id, cp.payment_option_pgw_id, cp.bank_code, cp.priority, cpo.payment_type_id
                        from clues_payment_option_pgw cp
                        join clues_payment_options cpo on cpo.payment_option_id = cp.payment_option_id
                        where cp.priority='1' and cp.status='A' and cp.payment_option_id='".$order_info['payment_option_id']."'";
                $payment_details = db_get_row($sql);
                
                $bank_code = '';
                $item_code = '';
                if($payment_details['payment_type_id'] == '1'){
                    $bank_code = $payment_details['bank_code'];
                    $item_code = 'DIRECT';
                }else{
                    $bank_code = 'NA';
                    $item_code = 'NA';
                }
                
                $response_url	= $Redirect_Url;
                
                $msg  		= $Merchant_Id.'|'.$Order_Id.'|NA|'.$Amount.'|'.$bank_code.'|NA|NA|INR|'.$item_code.'|R|'.$security_id.'|NA|NA|F|'.$order_info['email'].'|NA|NA|NA|NA|NA|NA|'.$response_url;
                $checksum 	=  strtoupper(hash_hmac('sha256',$msg,$checksum_key, false));
                $request_message= $msg.'|'.$checksum;

		$order_data = array();
		$order_data['request_message'] = $request_message;
		$clues_order_pgw_sql = "insert into clues_order_pgw (order_id, amount, payment_gateway, order_data) values ('".$order_info['order_id']."','".$order_info['total']."','Billdesk','".addslashes(serialize($order_data))."')";
		if(Registry::get('config.write_pgw_log')){
			log_to_file('pgw',$clues_order_pgw_sql);	
		}
                db_query($clues_order_pgw_sql);	
?>
	<form name="frm_billdesk" method="post" action="<?php echo $payment_url; ?> ">
            <input type="hidden" name="msg" value="<?php echo $request_message; ?>"/>
            <input type="hidden" name="hidOperation" value="ME100" />
            <input type="hidden" name="hidRequestId" value="PGIME1000" />
            <!--<INPUT TYPE="submit" value="submit">-->
            <script type="text/javascript">
                document.frm_billdesk.submit();
            </script>
        </form>
<?php			
	} 
	else if($_REQUEST['payment'] == 'billdesk_nb_script' && $mode == 'return') {
		//echo '<pre>';print_r($_REQUEST);//die;
                //$response = 'SHOPCLUES|240682371|MSPD0000305791|113102439671316|2.00|SPD|00008216|NA|INR|DIRECT|NA|NA|NA|24-10-2013 19:07:14|0300|NA|chandan.sharma@shopclues.com|NA|NA|NA|NA|NA|NA|NA|Transaction Successful|43E43EE6ACC69A507ACA75846EFD78206685717AEDE6AAD9AFE0CAB24589547D';
                $response           = $_REQUEST['msg'];
                $return_response    = explode('|',$response);
		$Order_Id           = $_REQUEST['order_id'];
                $return_checksum    = $return_response['25'];
                unset($return_response['25']);
                $res                = implode('|',$return_response);
                $order_info         = fn_get_order_info($Order_Id, true);
                $Merchant_Id 	= $order_info['payment_method']['params']['merchantid'];
		$checksum_key  	= $order_info['payment_method']['params']['checksum_key'];
		
                $checksum           =  strtoupper(hash_hmac('sha256',$res,$checksum_key, false));
		$Amount             = $return_response['4'];
                $transaction_id     = $return_response['2'];
                $status             = $return_response['14'];
		
		db_query("INSERT INTO clues_prepayment_details (direcpayreferenceid, order_id, flag, other_details, amount, payment_gateway) values('".$transaction_id."','".$Order_Id."','".$status."','".addslashes(serialize($response))."','".$Amount."','Billdesk NB')");	
		
		
		
		if (!empty($order_info) )
		{ 
			if (fn_check_payment_script('billdesk_nb_script.php', $Order_Id)) 
			{
				if($return_checksum==$checksum && $status == '0300')
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
				else if($return_checksum==$checksum && $status == '0002')
				{
					//echo "<br>Thank you for shopping with us.We will keep you posted regarding the status of your order through e-mail";
					//fn_change_order_status($Order_Id, 'O', '', true);
                                        if($Amount == $order_info['total']) {
                                            fn_change_order_status($Order_Id, 'K', '', true);
                                            $details = '******PAYMENT MAY BE SUCCESS ON billdesk.******'.$order_info['details'];
                                            db_query("update cscart_orders set details='".$details."' where order_id=".$Order_Id);
                                         }else{
                                            fn_change_order_status($Order_Id, 'K', '', true);
                                            $details = '******PAYMENT AMOUNT Rs. '.$Amount.' NOT SAME AS ORDER TOTAL AND PAYMENT MAY BE SUCCESS ON billdesk.******'.$order_info['details'];
                                           db_query("update cscart_orders set details='".$details."' where order_id=".$Order_Id);
					}
				}
				else if($return_checksum==$checksum && ($status == '0001' || $status == 'NA' || $status == '0399'))
				{
					//echo "<br>Thank you for shopping with us.However,the transaction has been declined.";
					fn_change_order_status($Order_Id, 'F', '', true);
				}else if($return_checksum != $checksum)
				{
					//echo "<br>Thank you for shopping with us.However,the transaction has been declined.";
					fn_change_order_status($Order_Id, 'F', '', true);
				}
				else
				{
					//echo "<br>Security Error. Illegal access detected";
					fn_set_notification('E','Order','There is some error with the order. Please try again','I');
					fn_redirect('index.php?dispatch=checkout.checkout');
				}
			}
			fn_order_placement_routines($Order_Id, true);
		}
	}
	elseif ($mode == 'cancel') {	
	//  CANCEL MODE 
               fn_set_notification('E','Order','There is some error with the order. Please try again','I');
               fn_redirect('index.php?dispatch=checkout.checkout');
	}
	else {
		fn_set_notification('N','Order','Your order is not placed. Please try again','I');
		fn_redirect('index.php?dispatch=checkout.checkout');
	}	
	exit;	
?>
