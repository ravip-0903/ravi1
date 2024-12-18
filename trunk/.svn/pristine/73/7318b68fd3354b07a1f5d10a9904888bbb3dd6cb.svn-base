<?php
if (!defined('AREA')) {die('Access denied');}

include_once (DIR_ROOT . '/payments/ccavenue_files/libfuncs.php3');

	if(!empty($order_info)){
		
		$current_location = Registry::get('config.current_location');
		$current_location = $current_location.'/'.$index_script;
		
		$Merchant_Id 	= $order_info['payment_method']['params']['merchantid'];
		$Amount 		= $order_info['total'];
		$Order_Id 		= $order_info['order_id'];
		$Redirect_Url 	= $current_location."?dispatch=payment_notification.return&payment=ccavenue_script&order_id=".$order_info['order_id'] ;//your redirect URL where your customer will be redirected after authorisation from CCAvenue	
		$WorkingKey 	= $order_info['payment_method']['params']['workingkey'];
		$Checksum 		= getCheckSum($Merchant_Id,$Amount,$Order_Id ,$Redirect_Url,$WorkingKey);		
		
		if($order_info['is_parent_order'] == 'Y')
		{
			$child_ids = db_get_fields("SELECT order_id FROM ?:orders WHERE parent_order_id = ?i", $Order_Id);
			$child_ids = implode(',',$child_ids);
		}
		
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
		
		$billing_cust_name		= $order_info['b_firstname'].' '.$order_info['b_lastname'];;
		$billing_cust_address	= $order_info['b_address'].' '.$order_info['b_address_2'];
		$billing_cust_state		= $order_info['b_state'];
		$billing_cust_country	= $b_country;
		$billing_cust_tel		= $b_phone;
		$billing_cust_email		= $order_info['email'];
		$delivery_cust_name		= $order_info['s_firstname'].' '.$order_info['s_lastname'];
		$delivery_cust_address	= $order_info['s_address'].' '.$order_info['s_address_2'];
		$delivery_cust_state 	= $order_info['s_state'];
		$delivery_cust_country 	= $s_country;
		$delivery_cust_tel		= $s_phone;
		$delivery_cust_notes	= $order_info['notes'];
		if($child_ids !='') {
			$Merchant_Param		= $child_ids;
		}else {
			$Merchant_Param		= "";
		}
		$billing_city 			= $order_info['b_city'];
		$billing_zip 			= $order_info['b_zipcode'];
		$delivery_city 			= $order_info['s_city'];
		$delivery_zip 			= $order_info['s_zipcode'];		
?>
	<form name="frmccev" method="post" action="https://www.ccavenue.com/shopzone/cc_details.jsp">
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
	else if($_REQUEST['payment'] == 'ccavenue_script' && $mode == 'return') {
		//echo '<pre>';print_r($_REQUEST);die;
		$Order_Id= $_REQUEST['Order_Id'];
		$order_info = fn_get_order_info($Order_Id, true);
		
		$WorkingKey = $order_info['payment_method']['params']['workingkey'] ; 
		$Merchant_Id= $_REQUEST['Merchant_Id'];
		$Amount= $_REQUEST['Amount'];		
		$Merchant_Param= $_REQUEST['Merchant_Param'];
		$Checksum= $_REQUEST['Checksum'];
		$AuthDesc=$_REQUEST['AuthDesc'];
		$transaction_id = $_REQUEST['nb_order_no'];
			
		$Checksum = verifyChecksum($Merchant_Id, $Order_Id , $Amount,$AuthDesc,$Checksum,$WorkingKey);	
		
		db_query("INSERT INTO clues_prepayment_details (direcpayreferenceid, order_id, flag, other_details, amount, payment_gateway) values('".$transaction_id."','".$Order_Id."','".$AuthDesc."','".addslashes($Merchant_Param)."','".$Amount."','CCAVENUE')");	
		
		
		
		if (!empty($order_info) )
		{ 
			if (fn_check_payment_script('direcpay_script.php', $Order_Id)) 
			{
				if($Checksum=="true" && $AuthDesc=="Y")
				{
					//echo "<br>Thank you for shopping with us. Your credit card has been charged and your transaction is successful. We will be shipping your order to you soon.";
					fn_change_order_status($Order_Id, 'P', '', true);
				}
				else if($Checksum=="true" && $AuthDesc=="B")
				{
					//echo "<br>Thank you for shopping with us.We will keep you posted regarding the status of your order through e-mail";
					fn_change_order_status($Order_Id, 'O', '', true);
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