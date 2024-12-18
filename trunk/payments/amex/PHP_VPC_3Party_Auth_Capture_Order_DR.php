<?php
define('AREA', 'C');
define('AREA_NAME', 'customer');
	
require  dirname(__FILE__) . '/../../prepare.php';
require  dirname(__FILE__) . '/../../init.php';

// Initialisation
// ==============

// 
include('VPCPaymentConnection.php');
$conn = new VPCPaymentConnection();
$capt = new VPCPaymentConnection();

$order_info = fn_get_order_info($_REQUEST['vpc_MerchTxnRef'], true);

// This is secret for encoding the MD5 hash
// This secret will vary from merchant to merchant

//$secureSecret = "FB4B65A18D73C9963248BDAAA3DA6767";
$secureSecret = $order_info['payment_method']['params']['secrate_hash'];

// Set the Secure Hash Secret used by the VPC connection object
$conn->setSecureSecret($secureSecret);


// Set the error flag to false
$errorsExist = false;



// *******************************************
// START OF MAIN PROGRAM
// *******************************************


// This is the title for display
$title  = $_GET["Title"];


// Add VPC GET data to the Digital Order
foreach($_GET as $key => $value) {
	if ((strlen($value) > 0) && ($key!="vpc_SecureHash")) {
		$conn->addDigitalOrderField($key, $value);
	}
}


// Obtain a one-way hash of the Digital Order data and
// check this against what was received.
$secureHash = $conn->hashAllFields();

if ($secureHash==$_GET["vpc_SecureHash"]) {
	$hashValidated = "<font color='#00AA00'><strong>CORRECT</strong></font>";
} else {
	$hashValidated = "<font color='#FF0066'><strong>INVALID HASH</strong></font>";
	$errorsExist = true;
}



    
/*  If there has been a merchant secret set then sort and loop through all the
    data in the Virtual Payment Client response. while we have the data, we can
    append all the fields that contain values (except the secure hash) so that
    we can create a hash and validate it against the secure hash in the Virtual
    Payment Client response.

    NOTE: If the vpc_TxnResponseCode in not a single character then
    there was a Virtual Payment Client error and we cannot accurately validate
    the incoming data from the secure hash. 

    // remove the vpc_TxnResponseCode code from the response fields as we do not 
    // want to include this field in the hash calculation
    
    if (secureSecret != null && secureSecret.length() > 0 && 
        (fields.GET("vpc_TxnResponseCode") != null || fields.GET("vpc_TxnResponseCode") != "No Value Returned")) {
        
        // create secure hash and append it to the hash map if it was created
        // remember if secureSecret = "" it wil not be created
        String secureHash = vpc3conn.hashAllFields(fields);
    
        // Validate the Secure Hash (remember MD5 hashes are not case sensitive)
        if (vpc_Txn_Secure_Hash.equalsIgnoreCase(secureHash)) {
            // Secure Hash validation succeeded, add a data field to be 
            // displayed later.
            hashValidated = "<font color='#00AA00'><strong>CORRECT</strong></font>";
        } else {
            // Secure Hash validation failed, add a data field to be
            // displayed later.
            errorExists = true;
            hashValidated = "<font color='#FF0066'><strong>INVALID HASH</strong></font>";
        }
    } else {
        // Secure Hash was not validated, 
        hashValidated = "<font color='orange'><strong>Not Calculated - No 'SECURE_SECRET' present.</strong></font>";
    }
*/

    // Extract the available receipt fields from the VPC Response
    // If not present then let the value be equal to 'Unknown'
    // Standard Receipt Data
$title           = $_GET["Title"];
$againLink       = $_GET["AgainLink"];
$amount          = $_GET["vpc_Amount"];
$locale          = $_GET["vpc_Locale"];
$batchNo         = $_GET["vpc_BatchNo"];
$command         = $_GET["vpc_Command"];
$message         = $_GET["vpc_Message"];
$version         = $_GET["vpc_Version"];
$cardType        = $_GET["vpc_Card"];
$orderInfo       = $_GET["vpc_OrderInfo"];
$receiptNo       = $_GET["vpc_ReceiptNo"];
$merchantID      = $_GET["vpc_Merchant"];
$merchTxnRef     = $_GET["vpc_MerchTxnRef"];
$authorizeID     = $_GET["vpc_AuthorizeId"];
$transactionNo   = $_GET["vpc_TransactionNo"];
$acqResponseCode = $_GET["vpc_AcqResponseCode"];
$txnResponseCode = $_GET["vpc_TxnResponseCode"];
$vpc_3DSstatus = $_REQUEST["vpc_3DSstatus"];
$vpc_ReturnAuthResponseData = $_GET["vpc_ReturnAuthResponseData"];

    // CSC Receipt Data
$cscResultCode  = $_GET["vpc_CSCResultCode"];
$ACQCSCRespCode = $_GET["vpc_AcqCSCRespCode"];
    
    // AVS Receipt Data
$avsResultCode  = $_GET["vpc_AVSResultCode"];
$ACQAVSRespCode = $_GET["vpc_AcqAVSRespCode"];

// GET the descriptions behind the QSI, CSC and AVS Response Codes
    // Only GET the descriptions if the string returned is not equal to "No Value Returned".
    
$txnResponseCodeDesc = "";
$cscResultCodeDesc = "";
$avsResultCodeDesc = "";
    
	
    if ($txnResponseCode != "No Value Returned") {
        $txnResponseCodeDesc = GETResultDescription($txnResponseCode);
    }
    
    if ($cscResultCode != "No Value Returned") {
        $cscResultCodeDesc = GETCSCResultDescription($cscResultCode);
    }
    
    if ($avsResultCode != "No Value Returned") {
        $avsResultCodeDesc = GETAVSResultDescription($avsResultCode);
    }
    
		$error = "";
    // Show this page as an error page if error condition
    if ($txnResponseCode=="7" || $txnResponseCode=="No Value Returned" || $errorExists) {
        $error = "Error ";
    }

    $captAttempted = false;
$captMerchTxnRef = $merchTxnRef;
$captMerchTxnRef = $captMerchTxnRef."-C";
// Now that we have a successful authorisation, we can process the capture request.
if ($txnResponseCode == "0" && ($vpc_3DSstatus == "Y" || $vpc_3DSstatus == "A" )) {
	$captAttempted = true;
	$username = $order_info['payment_method']['params']['amauser_name'];
	$password = $order_info['payment_method']['params']['password'];
	$accessCode = $order_info['payment_method']['params']['access_code'];
	$captVpcURL = "https://vpos.amxvpos.com/vpcdps";
	
	
	$capt->addDigitalOrderField("vpc_Version","1");
	$capt->addDigitalOrderField("vpc_Command","capture");
	$capt->addDigitalOrderField("vpc_AccessCode", $accessCode);
	$capt->addDigitalOrderField("vpc_MerchTxnRef",$captMerchTxnRef);
	$capt->addDigitalOrderField("vpc_Merchant", $merchantID);
	$capt->addDigitalOrderField("vpc_TransNo", $transactionNo);
	$capt->addDigitalOrderField("vpc_Amount",$amount);
	$capt->addDigitalOrderField("vpc_User", $username);
	$capt->addDigitalOrderField("vpc_Password",$password);
	$capt->addDigitalOrderField("vpc_ReturnAuthResponseData",$vpc_ReturnAuthResponseData);
	
	
	// Send the capture request to the Payment Server
	$capt->sendMOTODigitalOrder($captVpcURL, $proxy);
	// don't overwrite message if any error messages detected
	if (strlen($capt->GETErrorMessage()) == 0) {
    $captMessage            = $capt->GETResultField("vpc_Message");
	}

	// Standard Receipt Data
	$captMerchTxnRef     = $capt->GETResultField("vpc_MerchTxnRef");

	$captAmount          = $capt->GETResultField("vpc_Amount");
	$captBatchNo         = $capt->GETResultField("vpc_BatchNo");
	$captCommand         = $capt->GETResultField("vpc_Command");
	$captVersion         = $capt->GETResultField("vpc_Version");
	$captOrderInfo       = $capt->GETResultField("vpc_OrderInfo");
	$captReceiptNo       = $capt->GETResultField("vpc_ReceiptNo");
	$captAuthorizeID     = $capt->GETResultField("vpc_AuthorizeId");
	$captTransactionNr   = $capt->GETResultField("vpc_TransactionNo");
	$captAcqResponseCode = $capt->GETResultField("vpc_AcqResponseCode");
	$captTxnResponseCode = $capt->GETResultField("vpc_TxnResponseCode");
	$captReturnAuthResponseData = $capt->GETResultField("vpc_ReturnAuthResponseData");

	// AMA Transaction Data
	$captShopTransNo     = $capt->GETResultField("vpc_ShopTransactionNo");
	$captAuthorisedAmount= $capt->GETResultField("vpc_AuthorisedAmount");
	$captCapturedAmount  = $capt->GETResultField("vpc_CapturedAmount");
	$captRefundedAmount  = $capt->GETResultField("vpc_RefundedAmount");
	$captTicketNumber    = $capt->GETResultField("vpc_TicketNo");
	
        $other_details =    'captMerchTxnRef'.'=>'.$captMerchTxnRef.','.
                            'captAmount'.'=>'.$captAmount.','.
                            'captBatchNo'.'=>'.$captBatchNo.','.
                            'captCommand'.'=>'.$captCommand.','.
                            'captVersion'.'=>'.$captVersion.','.
                            'captOrderInfo'.'=>'.$captOrderInfo.','.
                            'captReceiptNo'.'=>'.$captReceiptNo.','.
                            'captAuthorizeID'.'=>'.$captAuthorizeID.','.
                            'captTransactionNr'.'=>'.$captTransactionNr.','.
                            'captAcqResponseCode'.'=>'.$captAcqResponseCode.','.
                            'captTxnResponseCode'.'=>'.$captTxnResponseCode.','.
                            'captReturnAuthResponseData'.'=>'.$captReturnAuthResponseData.','.
                            'captShopTransNo'.'=>'.$captShopTransNo.','.
                            'captAuthorisedAmount'.'=>'.$captAuthorisedAmount.','.
                            'captCapturedAmount'.'=>'.$captCapturedAmount.','.
                            'captRefundedAmount'.'=>'.$captRefundedAmount.','.
                            'captTicketNumber'.'=>'.$captTicketNumber.'';
	db_query("INSERT INTO clues_prepayment_details (direcpayreferenceid, order_id, flag, other_details, amount, payment_gateway,txn_response,3dstatus) values('".$captTransactionNr."','".$orderInfo."','".$captTxnResponseCode."','". addslashes($other_details)."','".$captCapturedAmount."','AMEX','$txnResponseCode','$vpc_3DSstatus')");					
        
        $url = Registry::get('config.domain_url').'/index.php?dispatch=payment_notification.return&payment=amex_script&order_id='.$orderInfo;
        /*if ($captTxnResponseCode != "No Value Returned") {
  	  $captTxnResponseCodeDesc = GETResultDescription($captTxnResponseCode);
	}
	
	if (($captTxnResponseCode == "7") || ($captTxnResponseCode == "No Value Returned")) {
			$captError = "Error ";
	}*/
        header("Location: $url");
} else {
    db_query("INSERT INTO clues_prepayment_details (direcpayreferenceid, order_id, flag, other_details, amount, payment_gateway,txn_response,3dstatus) values('".$transactionNo."','".$orderInfo."','','','".$amount."','AMEX','$txnResponseCode','$vpc_3DSstatus')");
    //$captMessage = "Capture not attempted due to Authorisation Failure - see above";
    $url = Registry::get('config.domain_url').'/index.php?dispatch=payment_notification.return&payment=amex_script&order_id='.$orderInfo;
    header("Location: $url");
}
        
    // FINISH TRANSACTION - Process the VPC Response Data
    // =====================================================
    // For the purposes of demonstration, we simply display the Result fields on a
    // web page.
?> 