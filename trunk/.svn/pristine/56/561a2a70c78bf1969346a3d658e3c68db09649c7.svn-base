<?php


// Initialisation
// ==============

// 
define('AREA', 'C');
define('AREA_NAME', 'customer');
		
require  dirname(__FILE__) . '/../../prepare.php';
require  dirname(__FILE__) . '/../../init.php';

include('VPCPaymentConnection.php');
$conn = new VPCPaymentConnection();

$order_info = fn_get_order_info($_REQUEST['vpc_MerchTxnRef'], true); 

// This is secret for encoding the MD5 hash
// This secret will vary from merchant to merchant

//$secureSecret = "FB4B65A18D73C9963248BDAAA3DA6767";
$secureSecret = $order_info['payment_method']['params']['secrate_hash'];
// Set the Secure Hash Secret used by the VPC connection object
$conn->setSecureSecret($secureSecret);


// *******************************************
// START OF MAIN PROGRAM
// *******************************************
// Sort the POST data - it's important to get the ordering right
ksort ($_POST);

// add the start of the vpcURL querystring parameters
$vpcURL = $_POST["virtualPaymentClientURL"];

// This is the title for display
$title  = $_POST["Title"];


// Remove the Virtual Payment Client URL from the parameter hash as we 
// do not want to send these fields to the Virtual Payment Client.
unset($_POST["virtualPaymentClientURL"]); 
unset($_POST["SubButL"]);


// Add VPC post data to the Digital Order
foreach($_POST as $key => $value) {
	if (strlen($value) > 0) {
		$conn->addDigitalOrderField($key, $value);
	}
}

// Add original order HTML so that another transaction can be attempted.
$conn->addDigitalOrderField("AgainLink", $againLink);

// Obtain a one-way hash of the Digital Order data and add this to the Digital Order
$secureHash = $conn->hashAllFields();
$conn->addDigitalOrderField("vpc_SecureHash", $secureHash);

// Obtain the redirection URL and redirect the web browser
$vpcURL = $conn->getDigitalOrder($vpcURL);

header("Location: ".$vpcURL);
//echo "<a href=$vpcURL>$vpcURL</a>";

?>