<?php  include("ResponseDTO.php");
include("EzeClickSecurity.php");
include("HTTPPost.php");
/**
 *
 * @Author: Sushant Shinde
 * @Creation-Date: 22/11/2012
 * @Copyright: Mindgate Solution Pvt. Ltd.
 * @Description: This class is used for Merchant integration
 * @Program-Specs-Referred: Functional Specification and TAD documents
 * @Revision:
 * ----------------------------------------------------------------------------------------------------------------
 * @Version    | @Last Revision Date | @Name                  | @Function/Module affected    | @Modifications Done
 * ----------------------------------------------------------------------------------------------------------------	
 * 0.1			22/11/2012			  Sushant Shinde		   Initial Class and functionalities created
 *
 *
 */
class ClientAPI
{


/**
 * This method is used to get transaction status
 *
 * Step 1 : Load propery File
 * Step 2 : Get URL from property file to get the transactioin status
 * Step 3 : Validate the input fields
 * Step 4 : Called excutePost() method to post the data
 * Step 5 : Validate the transactioin status response
 * Step 6 : Called decryptValue() method to decrypt the transaction status response
 * Step 7 : Set decrypted data to Response DTO
 *
 * @param String : Merchant ID
 * @param String : Oorder Id
 * @param String : Transaction Reference No
 * @param String : Key
 * @return ResponseDTO
 */
function getTransactionStatus($mid, $orderid, $txRefNo,$keyVal)
 {
	// Declaration and Initialization
	$enc_data=null;
	$dec_data = null;
	$responseDTO = new ResponseDTO();
	$httpPost = new HTTPPost();
	$ezeClickSecurity = new EzeClickSecurity();

	// Step 1 : Load propery File
	$ini_array = parse_ini_file("ClientAPI.ini");

	// Step 2 : Get URL from property file to get the transactioin status
	$qpURL=$ini_array['QP_GET_TRNSTATUS'];
	
	// Step 3 : Validate the input fields
	if(empty($mid)||is_null($mid)||empty($orderid)||is_null($orderid)||empty($keyVal)||is_null($keyVal))
	{
		$responseDTO->setQp_PaymentStatus("F");
		$responseDTO->setQp_PaymentStatusDesc("Invalid request was received.");
		return $responseDTO;
	}

	// Preapare the request parameter to post the data
	$merchantReqStr= $mid ."|". $orderid  ."|". $txRefNo;
	$urlParameters="merchantReqStrT=".urlencode ($merchantReqStr);
	
	// Step 4 : Called excutePost() method to post the data
	$enc_data=$httpPost ->excutePost($qpURL,$urlParameters);


	// Step 5 : Validate the transactioin status response
	if(empty($enc_data)||is_null($enc_data))
	{
		$responseDTO->setQp_PaymentStatus("F");
		$responseDTO->setQp_PaymentStatusDesc("Invalid request was received.");
		return $responseDTO;
	}
	
	// Step 6 : Called decryptValue() method to decrypt the transaction status response
	$dec_data=$ezeClickSecurity -> decryptValue($enc_data,$keyVal);

	// Validate the decrypted message
	if(empty($dec_data)||is_null($dec_data))
	{
		$responseDTO->setQp_PaymentStatus("F");
		$responseDTO->setQp_PaymentStatusDesc("Invalid request was received.");
		return $responseDTO;
	}
	
	// Split the message
	$strings=explode("|",$dec_data);
	
	// Step 7 : Set decrypted data to Response DTO 
	if(!empty($strings)&& sizeof($strings)==38)
	{
		$responseDTO->setQp_TransRefNo($strings[0]);
		$responseDTO->setQp_OrderID($strings[1]);
		$responseDTO->setQp_PaymentStatus($strings[2]);
		$responseDTO->setVpc_Amount($strings[3]);
		$responseDTO->setVpc_Locale($strings[4]);
		$responseDTO->setVpc_BatchNo($strings[5]);
		$responseDTO->setVpc_Command($strings[6]);
		$responseDTO->setVpc_Message($strings[7]);
		$responseDTO->setVpc_Version($strings[8]);
		$responseDTO->setVpc_Card($strings[9]);
		$responseDTO->setVpc_OrderInfo($strings[10]);
		$responseDTO->setVpc_ReceiptNo($strings[11]);
		$responseDTO->setVpc_Merchant($strings[12]);
		$responseDTO->setVpc_MerchTxnRef($strings[13]);
		$responseDTO->setVpc_AuthorizeId($strings[14]);
		$responseDTO->setVpc_TransactionNo($strings[15]);
		$responseDTO->setVpc_AcqResponseCode($strings[16]);
		$responseDTO->setVpc_TxnResponseCode($strings[17]);
		$responseDTO->setVpc_CSCResultCode($strings[18]);
		$responseDTO->setVpc_AcqCSCRespCode($strings[19]);
		$responseDTO->setVpc_AVSResultCode($strings[20]);
		$responseDTO->setVpc_AcqAVSRespCode($strings[21]);
		$responseDTO->setVpc_3DSECI($strings[22]);
		$responseDTO->setVpc_3DSXID($strings[23]);
		$responseDTO->setVpc_3DSenrolled($strings[24]);
		$responseDTO->setVpc_3DSstatus($strings[25]);
		$responseDTO->setVpc_VerToken($strings[26]);
		$responseDTO->setVpc_VerType($strings[27]);
		$responseDTO->setVpc_VerStatus($strings[28]);
		$responseDTO->setVpc_VerSecurityLevel($strings[29]);
		$responseDTO->setVpc_ShopTransactionNo($strings[30]);
		$responseDTO->setVpc_AuthorisedAmount($strings[31]);
		$responseDTO->setVpc_CapturedAmount($strings[32]);
		$responseDTO->setVpc_txnResponseCodeDesc($strings[33]);
		$responseDTO->setAuthZ_vpc_ReceiptNo($strings[34]);
		$responseDTO->setAuthZ_vpc_TransactionNo($strings[35]);
		$responseDTO->setAuthZ_vpc_BatchNo($strings[36]);
		$responseDTO->setQp_PaymentStatusDesc($strings[37]);
	}
	else
	{
		$responseDTO->setQp_PaymentStatus("F");
		$responseDTO->setQp_PaymentStatusDesc("Invalid response was received.");
	}
		return $responseDTO;
	}


	
/**
 * This method is used to get card details
 *
 * Step 1 : Load propery File
 * Step 2 : Get URL from property file to get the card details
 * Step 3 : Validate the input fields
 * Step 4 : Called excutePost() method to post the data
 * Step 5 : Validate the transactioin status response
 * Step 6 : Called decryptValue() method to decrypt the transaction status response
 * Step 7 : Set decrypted data to Response DTO
 *
 * @param String : Merchant ID
 * @param String : Oorder Id
 * @param String : Transaction Reference No
 * @param String : Key
 * @return ResponseDTO
 */	
function  getCardDetails($mid,$orderid,$txRefNo,$keyVal)
{
	// Declaration and Initialization
	$enc_data=null;
	$dec_data = null;
	$responseDTO = new ResponseDTO();
	$ezeClickSecurity = new EzeClickSecurity();
	$httpPost = new HTTPPost();

	// Step 1 : Load propery File
	$ini_array = parse_ini_file("ClientAPI.ini");

	// Step 2 : Get URL from property file to get the card details
	$qpURL=$ini_array['QP_GET_CARDINFO'];

	// Step 3 : Validate the input fields
	if(empty($mid)||is_null($mid)||empty($orderid)||is_null($orderid)||empty($keyVal)||is_null($keyVal)||$txRefNo==0)
	{
		$responseDTO->setQp_PaymentStatus("F");
		$responseDTO->setQp_PaymentStatusDesc("Invalid request was received.");
		return $responseDTO;
	}

    // Preapare the request parameter to post the data
	$merchantReqStr= $mid ."|". $orderid  ."|". $txRefNo;
	$urlParameters="merchantReqStrC=".urlencode ($merchantReqStr);
	
	// Step 4 : Called excutePost() method to post the data
	$enc_data=$httpPost ->excutePost($qpURL,$urlParameters);
	
	// Step 5 : Validate the card detail response
	if(empty($enc_data)||is_null($enc_data))
	{
		$responseDTO->setQp_PaymentStatus("F");
		$responseDTO->setQp_PaymentStatusDesc("Invalid request was received.");
		return $responseDTO;
	}
	
	//  Step 6 : Called decryptValue() method to decrypt the card detail response
	$dec_data=$ezeClickSecurity -> decryptValue($enc_data,$keyVal);


	// Validate the decrypted message
	if(empty($dec_data)||is_null($dec_data))
	{
		$responseDTO->setQp_PaymentStatus("F");
		$responseDTO->setQp_PaymentStatusDesc("Invalid request was received.");
		return $responseDTO;
	}

	// Split the message
    $strings=explode("|",$dec_data);

	// Step 7 : Set decrypted data to Response DTO
	if(!empty($strings)&& sizeof($strings)==9)
	{
		$responseDTO->setQp_TransRefNo($strings[0]);
		$responseDTO->setQp_OrderID($strings[1]);
		$responseDTO->setMid($strings[2]);
		$responseDTO->setFirstName($strings[3]);
		$responseDTO->setMiddleName($strings[4]);
		$responseDTO->setLastName($strings[5]);
		$responseDTO->setCardNumber($strings[6]);
		$responseDTO->setCardExpYear($strings[7]);
		$responseDTO->setCardExpMonth($strings[8]);

		$responseDTO->setQp_PaymentStatus("S");
		$responseDTO->setQp_PaymentStatusDesc("Valid request was received.");

	}
	else
	{
		$responseDTO->setQp_PaymentStatus("F");
		$responseDTO->setQp_PaymentStatusDesc("Invalid response was received.");
	}
	return $responseDTO;

}



/**
 * This method is used to get digital order
 *
 * Step 1 : Validate the input fields
 * Step 2 : Prepare input for encryption
 * Step 3 : Called encryptValue() method to encrypt the data
 * Step 4 : Validate the encrypted value
 *
 * @param String : Merchant ID
 * @param String : Oorder Id
 * @param String : Transaction Amount
 * @param String : Return URL
 * @param String : Key
 * @return ResponseDTO
*/
function  generateDigitalOrder($mid, $orderid, $tranAmount, $returnURL, $keyVal)
{
	// Declaration and Initialization
	$enc_data="";
	$plan_text="";
	
	$ezeClickSecurity = new EzeClickSecurity();
	
    // Step 1 : Validate the input fields
	if(is_null($mid)||is_null($orderid) || is_null($tranAmount) || is_null($keyVal)|| empty($mid)|| empty($orderid)||empty($keyVal)||empty($tranAmount))
	{
		echo "Invalid input";
		return $enc_data;
	}

	// Step 2 : Prepare input for encryption 
	$plan_text = $mid . "|" . $orderid  . "|" . $tranAmount . "|" . $returnURL;
	
	// Step 3 : Called encryptValue() method to encrypt the data
	$enc_data=$ezeClickSecurity -> encryptValue($plan_text,$keyVal);

	// Step 4 : Validate the encrypted value
	if(is_null($enc_data) || empty($enc_data))
	{
		echo "Error in encryption ";
	}
	return $enc_data;
}


/**
 * This method is used to decrypt digital response and return response status
 *
 * Step 1 : Validate the input fields
 * Step 2 : Called decryptValue() method to decrypt the data
 * Step 3 : Validate the decrypted value
 * Step 4 : Set decrypted data to Response DTO
 *
 * @param String : Enrtyped data
 * @param String : Key
 * @return ResponseDTO
 */
function getDigitalReceipt($enc_data, $enc_key)
{
	// Declaration and Initialization
	$dec_data="";
	$plan_text="";
	$responseDTO = new ResponseDTO();
	$ezeClickSecurity = new EzeClickSecurity();
	
    // Step 1 : Validate the input fields
	if(is_null($enc_data)||is_null($enc_data) || empty($enc_data)|| empty($enc_key))
	{
		$responseDTO->setQp_PaymentStatus("F");
		$responseDTO->setQp_PaymentStatusDesc("Invalid request was received.");
		return $responseDTO;
	}
	
	// Step 2 : Called decryptValue() method to decrypt the data
	$dec_data=$ezeClickSecurity -> decryptValue($enc_data, $enc_key);
	
	// Step 3 : Validate the decrypted value
	if(is_null($dec_data) || empty($dec_data))
	{
		$responseDTO->setQp_PaymentStatus("F");
		$responseDTO->setQp_PaymentStatusDesc("Invalid response was received.");
		return $responseDTO;
	}
	
	// Split the message
	$strings=explode("|",$dec_data);
	
	// Step 4 : Set decrypted data to Response DTO
	if(!is_null($strings) && !empty($strings)&& sizeof($strings)==35)
	{
		$responseDTO->setQp_TransRefNo($strings[0]);
		$responseDTO->setQp_OrderID($strings[1]);
		$responseDTO->setQp_PaymentStatus($strings[2]);
		$responseDTO->setVpc_Amount($strings[3]);
		$responseDTO->setVpc_Locale($strings[4]);
		$responseDTO->setVpc_BatchNo($strings[5]);
		$responseDTO->setVpc_Command($strings[6]);
		$responseDTO->setVpc_Message($strings[7]);
		$responseDTO->setVpc_Version($strings[8]);
		$responseDTO->setVpc_Card($strings[9]);
		$responseDTO->setVpc_OrderInfo($strings[10]);
		$responseDTO->setVpc_ReceiptNo($strings[11]);
		$responseDTO->setVpc_Merchant($strings[12]);
		$responseDTO->setVpc_MerchTxnRef($strings[13]);
		$responseDTO->setVpc_AuthorizeId($strings[14]);
		$responseDTO->setVpc_TransactionNo($strings[15]);
		$responseDTO->setVpc_AcqResponseCode($strings[16]);
		$responseDTO->setVpc_TxnResponseCode($strings[17]);
		$responseDTO->setVpc_CSCResultCode($strings[18]);
		$responseDTO->setVpc_AcqCSCRespCode($strings[19]);
		$responseDTO->setVpc_AVSResultCode($strings[20]);
		$responseDTO->setVpc_AcqAVSRespCode($strings[21]);
		$responseDTO->setVpc_3DSECI($strings[22]);
		$responseDTO->setVpc_3DSXID($strings[23]);
		$responseDTO->setVpc_3DSenrolled($strings[24]);
		$responseDTO->setVpc_3DSstatus($strings[25]);
		$responseDTO->setVpc_VerToken($strings[26]);
		$responseDTO->setVpc_VerType($strings[27]);
		$responseDTO->setVpc_VerStatus($strings[28]);
		$responseDTO->setVpc_VerSecurityLevel($strings[29]);
		$responseDTO->setVpc_ShopTransactionNo($strings[30]);
		$responseDTO->setVpc_AuthorisedAmount($strings[31]);
		$responseDTO->setVpc_CapturedAmount($strings[32]);
		$responseDTO->setVpc_txnResponseCodeDesc($strings[33]);
		$responseDTO->setQp_PaymentStatusDesc($strings[34]);
	}
	else
	{
		$responseDTO->setQp_PaymentStatus("F");
		$responseDTO->setQp_PaymentStatusDesc("Invalid response was received.");
	}
	return $responseDTO;
	
}


}

//ClientAPI::main("test");

?>