<?php
    define('AREA', 'C');
    define('AREA_NAME', 'customer');
    require  dirname(__FILE__) . '/../prepare.php';
    require  dirname(__FILE__) . '/../init.php';

        $txnresponse = array();
        $txnresponse = $_REQUEST;
        
    
        $order_id       = $_REQUEST['TxId'];
        $amount         = $_REQUEST['amount'];
        $status         = $_REQUEST['TxStatus'];
        $transaction_id = $_REQUEST['pgTxnNo'];
        
        //echo "<pre>";print_r($txnresponse);die;
   
        db_query("INSERT INTO clues_prepayment_details (direcpayreferenceid, order_id, flag, other_details, amount, payment_gateway) values('".$transaction_id."','".$order_id."','".$status."','".addslashes(serialize($txnresponse))."','".$amount."','CITRUSPAY')");
        
        $current_location = Registry::get('config.current_location');
        $current_location = $current_location.'/'.Registry::get('config.customer_index');

        if($_REQUEST['paymentMode'] == 'NET_BANKING')
        $Redirect_Url 	= $current_location."?dispatch=payment_notification.return&payment=citruspay_script&order_id=".$order_id;
        else
        $Redirect_Url 	= $current_location."?dispatch=payment_notification.return&payment=citruspay_ccdc_script&order_id=".$order_id;

        fn_redirect($Redirect_Url);
        
?>