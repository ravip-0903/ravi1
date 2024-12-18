<?php
 define('AREA', 'A');
 define('AREA_NAME', 'admin');
 define('PAYMENT_ID','12');
  
 require  dirname(__FILE__) . '/../prepare.php';
 require  dirname(__FILE__) . '/../init.php';
 define('RETURN_URL','http://'.Registry::get('config.http_host').'/tools/process_response.php');
 
 /*$incomplete_order_arr = db_get_array("SELECT cpd . * , co . *
              FROM clues_prepayment_details cpd
             LEFT JOIN cscart_orders co ON ( cpd.order_id = co.order_id )
             WHERE co.payment_id = '12'
             AND (co.status = 'I' || co.status = 'F')
             ");*/
 $incomplete_order_arr = db_get_array("SELECT cpd.direcpayreferenceid, cpd.order_id, cpd.flag, co.status, 
         date_format(FROM_UNIXTIME(co.timestamp),'%d-%m-%Y') as order_date
         FROM clues_prepayment_details cpd
         LEFT JOIN cscart_orders co ON ( cpd.order_id = co.order_id )
         WHERE co.payment_id = '12'
         AND (cpd.flag = 'SUCCESS')
         AND (co.status='P' or co.status='G' or co.status='E' or co.status='T' or co.status='A')
         AND cpd.payment_gateway='DirecPay' AND FROM_UNIXTIME(co.timestamp) > '".$_GET['date']."' order by order_date DESC LIMIT 0,50");
         
 //echo '<pre>';print_r($incomplete_order_arr);die;
 $payment_method_data = fn_get_payment_method_data(PAYMENT_ID);
 //echo '<pre>';print_r($payment_method_data); die;
 $merchant_id = $payment_method_data['params']['merchantid'];
 if(isset($payment_method_data['params']['testmode']) && $payment_method_data['params']['testmode']=='on') {
  $mode = 'test';
 }else{
  $mode = 'live';
 }
 
 if($mode == 'test') {
  $action = 'https://test.timesofmoney.com/direcpay/secure/dpPullMerchAtrnDtls.jsp';
 }elseif($mode == 'live'){
  $action = 'https://www.timesofmoney.com/direcpay/secure/dpPullMerchAtrnDtls.jsp';
 }
 
 if(count($incomplete_order_arr)>0)  { 
  foreach($incomplete_order_arr as $incomplete_order)
  {
   $direcpayreferenceid = $incomplete_order['direcpayreferenceid'];
   $mid     = $merchant_id;
   $returnurl    = RETURN_URL;
   $requestparams   = $direcpayreferenceid.'|'.$mid.'|'.$returnurl;
   
   $url = $action.'?requestparams='.$requestparams;
   echo $url.'<br/>';//die;
   $ch=curl_init();
   
   curl_setopt($ch, CURLOPT_URL,$url);
   curl_setopt($ch, CURLOPT_FAILONERROR,1);
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
   curl_setopt($ch, CURLOPT_TIMEOUT, 200);
   curl_exec($ch);
   curl_close($ch);
  }
 }
 
?>