<?php
if (!defined('AREA')) {die('Access denied');}

if(!empty($order_info)){
		
		$current_location = Registry::get('config.current_location');
		$current_location = $current_location.'/'.$index_script;
		
		$TranAmount		= $order_info['total'];
		$TranTrackid	= $order_info['order_id'];		
?>
	<form name="frmhdfc" method="post" action="<?php echo "https://".Registry::get('config.https_host').Registry::get('config.https_path')?>/payments/hdfc/SendPerformREQ.php">
        <input type="hidden" name="MTrackid" value="<?php echo $TranTrackid; ?>">
        <input type="hidden" name="MAmount" value="<?php echo $TranAmount; ?>">
        <!--<INPUT TYPE="submit" value="submit">-->
        <script type="text/javascript">
			document.frmhdfc.submit();
		</script>
        </form>
<?php			
	}elseif($_REQUEST['payment'] == 'hdfc_script' && $mode == 'return') {
            $order_id = $_REQUEST['order_id'];
            $order_info = fn_get_order_info($order_id, true);
            $message = $_REQUEST['message'];
            $response_data = db_get_row("select direcpayreferenceid, order_id, flag, other_details, amount, payment_gateway from clues_prepayment_details where order_id='".$order_id."'");
            
            
            if (!empty($order_info) )
            { 
                if (fn_check_payment_script('hdfc_script.php', $order_id)) 
                {
                    if($response_data['flag'] == 'CAPTURED' || $response_data['flag'] == 'APPROVED'){
                        if($response_data['amount'] == $order_info['total']) {
                            fn_change_order_status($order_id, 'P', '', true);
                        }else{
                            fn_change_order_status($order_id, 'K', '', true);
                            $details = '******PAYMENT AMOUNT Rs. '.$response_data['amount'].' NOT SAME AS ORDER TOTAL.******'.$order_info['details'];
                            db_query("update cscart_orders set details='".$details."' where order_id=".$order_id);
                        }
                    }else{
                        fn_change_order_status($order_id, 'F', '', true);
                    }
                }
            }
            fn_order_placement_routines($order_id, true);            
        }
        exit;



?>
