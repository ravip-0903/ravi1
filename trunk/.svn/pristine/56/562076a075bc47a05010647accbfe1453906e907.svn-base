<?php

//error_reporting(E_ALL);

//ini_set('display_errors', '1');

include("Sfa/BillToAddress.php");
include("Sfa/CardInfo.php");
include("Sfa/Merchant.php");
include("Sfa/MPIData.php");
include("Sfa/ShipToAddress.php");
include("Sfa/PGResponse.php");
include("Sfa/PostLibPHP.php");
include("Sfa/PGReserveData.php");

$oMPI 		= 	new 	MPIData();
$oCI		=	new	CardInfo();
$oPostLibphp	=	new	PostLibPHP();
$oMerchant	=	new	Merchant();
$oBTA		=	new	BillToAddress();
$oSTA		=	new	ShipToAddress();
$oPGResp	=	new	PGResponse();
$oPGReserveData = new PGReserveData();

$oMerchant->setMerchantDetails("00002116","00002116","00002116","10.10.10.238",rand()."","OrderRef1","","","INR","INV1234","req.Sale","100","","Ext1","Ext2","Ext3","Ext4","Ext5");

$oSTA->setAddressDetails ("Add1","Add2","Add3","City","State","443543","IND","sad@df.com");

$oBTA->setAddressDetails ("CID","Tester","Aline1","Aline2","Aline3","Pune","MH","48927489","IND","tester@soft.com");

$oCI->setCardDetails ("MC","5081264401288025","234","2028","12","Tester","CREDI");

$oMPI->setMPIResponseDetails  ("02","NTBlZjRjMThjMjc1NTUxYzk1MTY=","Y","AAAAAAAAAAAAAAAAAAAAAAAAAAA=","84759435","1000","356");

$oPGResp=$oPostLibphp->postMOTO($oBTA,$oSTA,$oMerchant,$oMPI,$oCI,$oPGReserveData);

$oPGResp->getResponse($oResp);

print "Response Code:".$oPGResp->getRespCode()."<br>";

print "Response Message:".$oPGResp->getRespMessage()."<br>";

print "Transaction ID:".$oPGResp->getTxnId()."<br>";

print "Epg Transaction ID:".$oPGResp->getEpgTxnId()."<br>";

print "Auth Id Code :".$oPGResp->getAuthIdCode()."<br>";

print "RRN :".$oPGResp->getRRN()."<br>";

print "CVResp Code :".$oPGResp->getCVRespCode()."<br>";

?>