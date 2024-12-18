<?php
if (!defined('AREA')) {die('Access denied');}

        include("ezeclick/ClientAPI.php");
	if(!empty($order_info)){
		
            $current_location = Registry::get('config.current_location');
            $current_location = $current_location.'/'.$index_script;

            $target_url         = $order_info['payment_method']['params']['url'];
            $Merchant_Id 	= $order_info['payment_method']['params']['merchantid'];
            $enc_key  	        = $order_info['payment_method']['params']['enc_key'];
            $Amount 		= (int)($order_info['total']*100);
            $Order_Id 		= $order_info['order_id'];
            $Return_Url 	= $current_location."?dispatch=payment_notification.return&payment=ezeclick_script&order_id=".$order_info['order_id'] ;
            
            $order_data = array();
            $order_data['merchant_id'] = $Merchant_Id;
            $order_data['order_id']    = $Order_Id;
            $order_data['other']       = $order_info;
            
            
            $ClientAPI = new ClientAPI();
            $paymentReqMsg = $ClientAPI->generateDigitalOrder($Merchant_Id, $Order_Id, $Amount, $Return_Url, $enc_key);
            $order_data['requestparameter'] = $paymentReqMsg;
            
            $clues_order_pgw_sql = "insert into clues_order_pgw (order_id, amount, payment_gateway, order_data) values ('".$order_info['order_id']."','".$order_info['total']."','EZECLICK','".addslashes(serialize($order_data))."')";
            db_query($clues_order_pgw_sql);
            
?>
        <form id="form_ezeclick" action="<?php echo $target_url; ?> " method="POST" > 
            <input type="hidden" name="merchantRequest" value="<?php echo $paymentReqMsg; ?> ">
            <input type="hidden" name="MID" value="<?php echo $Merchant_Id; ?> ">
            <!--<input type="submit" value= "Submit">-->
            <script type="text/javascript">
                                document.forms["form_ezeclick"].submit();
                        </script>
        </form>
<?php

    
        }else if($_REQUEST['payment'] == 'ezeclick_script' && $mode == 'return') {
           // echo "<pre>";print_r($_REQUEST);die;
            $order_id        = $_REQUEST['order_id'];
            $order_info      = fn_get_order_info($order_id, true);
            $enc_key  	     = $order_info['payment_method']['params']['enc_key'];
            $ClientAPI       = new ClientAPI(); 
            $responseDTO     = new ResponseDTO(); 
            $responseDTO     = $ClientAPI->getDigitalReceipt($_REQUEST['merchantResponse'], $enc_key);
            $captured_amount = $responseDTO->getVpc_CapturedAmount();
            $transaction_id  = $responseDTO->getQp_TransRefNo();
            $status          = $responseDTO->getQp_PaymentStatus();
            $response_array  = (array)$responseDTO;
            
            db_query("INSERT INTO clues_prepayment_details (direcpayreferenceid, order_id, flag, other_details, amount, payment_gateway) values('".$transaction_id."','".$order_id."','".$status."','".addslashes(serialize($response_array))."','".$captured_amount."','EZECLICK')");
            if (!empty($order_info)){ 

                if (fn_check_payment_script('ezeclick_script.php', $order_id)){  
                    
                    if ($status == 'S') {
                        if($order_info['total'] == ($captured_amount/100)){
                            fn_change_order_status($order_id, 'P', '', true);
                        }else{
                            fn_change_order_status($order_id, 'K', '', true);
                            $details = '******PAYMENT AMOUNT Rs. '.$response_data['amount'].' NOT SAME AS ORDER TOTAL.******'.$order_info['details'];
                            db_query("update cscart_orders set details='".$details."' where order_id=".$order_id);
                        }
                    }else{
                        /*if authrization response is not S means authrizaiton is failed and its a fail order.*/
                        fn_change_order_status($order_id, 'F', '', true);
                    }

                }
            }
            fn_order_placement_routines($order_id, true);
        }
        elseif ($mode == 'cancel') {	
	fn_change_order_status($order_id, 'F', '', true);	
	}	
	exit;
            
            ?>