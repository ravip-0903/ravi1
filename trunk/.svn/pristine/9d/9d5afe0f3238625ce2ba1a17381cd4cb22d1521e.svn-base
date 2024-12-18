<?php

define('AREA', 'C');
define('AREA_NAME', 'customer');
		
require  dirname(__FILE__) . '/../../prepare.php';
require  dirname(__FILE__) . '/../../init.php';
require DIR_ROOT. '/payments/icici/Sfa/EncryptionUtil.php';

		 $strMerchantId=$_POST['MID'];
		 $astrFileName=DIR_ROOT. "/payments/icici/".$_POST['MID'].".key";
		 $astrClearData;
		 $ResponseCode = "";
		 $Message = "";
		 $TxnID = "";
		 $ePGTxnID = "";
	     	 $AuthIdCode = "";
		 $RRN = "";
		 $CVRespCode = "";

		 $Reserve1 = "";
		 $Reserve2 = "";
		 $Reserve3 = "";
		 $Reserve4 = "";
		 $Reserve5 = "";

		 $Reserve6 = "";
		 $Reserve7 = "";
		 $Reserve8 = "";
		 $Reserve9 = "";

		 $Reserve10 = "";



if(!empty($_POST))
{

			if($_POST['DATA']==null){
				fn_set_notification('E',fn_get_lang_var('notice'),fn_get_lang_var('error_in_transaction'));
				$url = Registry::get('config.domain_url').'/index.php?dispatch=checkout.checkout';
			}
			 $astrResponseData=$_POST['DATA'];
			 $astrDigest = $_POST['EncryptedData'];
			 $oEncryptionUtilenc = 	new 	EncryptionUtil();
			 $astrsfaDigest  = $oEncryptionUtilenc->getHMAC($astrResponseData,$astrFileName,$strMerchantId);

			if (strcasecmp($astrDigest, $astrsfaDigest) == 0) {
			 parse_str($astrResponseData, $output);
			 if( array_key_exists('RespCode', $output) == 1) {
			 	$ResponseCode = $output['RespCode'];
			 }
			 if( array_key_exists('Message', $output) == 1) {
			 	$Message = $output['Message'];
			 }
			 if( array_key_exists('TxnID', $output) == 1) {
			 	$TxnID=$output['TxnID'];
			 }
			 if( array_key_exists('ePGTxnID', $output) == 1) {
			 	$ePGTxnID=$output['ePGTxnID'];
			 }
			 if( array_key_exists('AuthIdCode', $output) == 1) {
			 	$AuthIdCode=$output['AuthIdCode'];

			 }
			 if( array_key_exists('RRN', $output) == 1) {
			 	$RRN = $output['RRN'];
			 }

			 if( array_key_exists('CVRespCode', $output) == 1) {
			 	$CVRespCode=$output['CVRespCode'];
			 }

				if(isset($_POST['MID']) && isset($ResponseCode) && isset($Message) && isset($TxnID) && isset($ePGTxnID) && isset($AuthIdCode)){

					$merchant_id=$_POST['MID'];
					$response_code=$ResponseCode;
					$response_Message=$Message;
					$order_id=$TxnID;
					$transaction_id=$ePGTxnID;
	
					$other_deatils='RespCode=>'.$response_code.','.
								   'Response_message=>'.$response_Message.','.
								   'PGTrans_id=>'.$transaction_id.','.
								   'Ref_id=>'.$order_id.','.
								   'Merchant_id=>'.$merchant_id.','.
								   'Auth_id=>'.$AuthIdCode.','.
								   'RRN=>'.$RRN.','.
								   'CVRes=>'.$CVRespCode;
	
					$sql="insert into clues_prepayment_details set direcpayreferenceid='".$transaction_id."',order_id='".$order_id."',flag='".$response_code."',payment_gateway='ICICI',other_details='".addslashes($other_deatils)."'";
					db_query($sql);
					$url = Registry::get('config.domain_url').'/index.php?dispatch=payment_notification.return&from=P&payment=icici_script&order_id='.$order_id.'&resp_code='.$response_code;
					header("Location: $url");
				} else {
					fn_set_notification('E',fn_get_lang_var('notice'),fn_get_lang_var('error_in_transaction'));
					$url = Registry::get('config.domain_url').'/index.php?dispatch=checkout.checkout';
				}
			}

	/*$merchant_id=$_POST['MID'];
	$response_code=$_POST['RespCode'];
	$response_Message=$_POST['Message'];
	$order_id=$_POST['TxnID'];
	$transaction_id=$_POST['ePGTxnID'];
	$AuthIdCode=$_POST['AuthIdCode'];
	$RRN=$_POST['RRN'];
	$CVRespCode=$_POST['CVRespCode'];
	
	$other_deatils='RespCode=>'.$response_code.','.
				   'Response_message=>'.$response_Message.','.
				   'PGTrans_id=>'.$transaction_id.','.
				   'Ref_id=>'.$order_id.','.
	               'Merchant_id=>'.$merchant_id.','.
				   'Auth_id=>'.$AuthIdCode.','.
				   'RRN=>'.$RRN.','.
				   'CVRes=>'.$CVRespCode;
	
	$sql="insert into clues_prepayment_details set direcpayreferenceid='".$transaction_id."',order_id='".$order_id."',flag='".$response_code."',payment_gateway='ICICI',other_details='".addslashes($other_deatils)."'";
	db_query($sql);
	$url = Registry::get('config.domain_url').'/index.php?dispatch=payment_notification.return&from=P&payment=icici_script&order_id='.$order_id.'&resp_code='.$response_code;
	header("Location: $url");*/
	
	
}
else
{
	fn_set_notification('E',fn_get_lang_var('notice'),fn_get_lang_var('error_in_transaction'));
	$url = Registry::get('config.domain_url').'/index.php?dispatch=checkout.checkout';
}

		/* $strMerchantId=$_POST['MID'];
		 $astrFileName=DIR_ROOT. "/payments/icici/".$_POST['MID'].".key";
		 $astrClearData;
		 $ResponseCode = "";
		 $Message = "";
		 $TxnID = "";
		 $ePGTxnID = "";
	     $AuthIdCode = "";
		 $RRN = "";
		 $CVRespCode = "";
		 $Reserve1 = "";
		 $Reserve2 = "";
		 $Reserve3 = "";
		 $Reserve4 = "";
		 $Reserve5 = "";
		 $Reserve6 = "";
		 $Reserve7 = "";
		 $Reserve8 = "";
		 $Reserve9 = "";
		 $Reserve10 = "";


		 if($_POST){

			if($_POST['DATA']==null){
				print "null is the value";
			}
			 $astrResponseData=$_POST['DATA'];
			 $astrDigest = $_POST['EncryptedData'];
			 $oEncryptionUtilenc = 	new 	EncryptionUtil();
			 $astrsfaDigest  = $oEncryptionUtilenc->getHMAC($astrResponseData,$astrFileName,$strMerchantId);

			if (strcasecmp($astrDigest, $astrsfaDigest) == 0) {
			 parse_str($astrResponseData, $output);
			 if( array_key_exists('RespCode', $output) == 1) {
			 	$ResponseCode = $output['RespCode'];
			 }
			 if( array_key_exists('Message', $output) == 1) {
			 	$Message = $output['Message'];
			 }
			 if( array_key_exists('TxnID', $output) == 1) {
			 	$TxnID=$output['TxnID'];
			 }
			 if( array_key_exists('ePGTxnID', $output) == 1) {
			 	$ePGTxnID=$output['ePGTxnID'];
			 }
			 if( array_key_exists('AuthIdCode', $output) == 1) {
			 	$AuthIdCode=$output['AuthIdCode'];
			 }
			 if( array_key_exists('RRN', $output) == 1) {
			 	$RRN = $output['RRN'];
			 }
			 if( array_key_exists('CVRespCode', $output) == 1) {
			 	$CVRespCode=$output['CVRespCode'];
			 }
			}
		 }
	print "<h6>Response Code:: $ResponseCode <br>";
	print "<h6>Response Message:: $Message <br>";
	print "<h6>Auth ID Code:: $AuthIdCode <br>";
	print "<h6>RRN:: $RRN<br>";
	print "<h6>Transaction id:: $TxnID<br>";
	print "<h6>Epg Transaction ID:: $ePGTxnID<br>";
	print "<h6>CV Response Code:: $CVRespCode<br>";*/

?>
