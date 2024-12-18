<?php

include(dirname(__FILE__) ."/Sfa/BillToAddress.php");
include(dirname(__FILE__) ."/Sfa/CardInfo.php");
include(dirname(__FILE__) ."/Sfa/Merchant.php");
include(dirname(__FILE__) ."/Sfa/MPIData.php");
include(dirname(__FILE__) ."/Sfa/ShipToAddress.php");
include(dirname(__FILE__) ."/Sfa/PGResponse.php");
include(dirname(__FILE__) ."/Sfa/PostLibPHP.php");
include(dirname(__FILE__) ."/Sfa/PGReserveData.php");

$oMPI 			= 	new MPIData();
$oCI			=	new	CardInfo();
$oPostLibphp	=	new	PostLibPHP();
$oMerchant		=	new	Merchant();
$oBTA			=	new	BillToAddress();
$oSTA			=	new	ShipToAddress();
$oPGResp		=	new	PGResponse();
$oPGReserveData =	new PGReserveData();



$oMerchant->setMerchantDetails("00007214","00007214","00007214","10.10.10.238",rand()."","Ord1234",dirname(__FILE__)."/SFAResponse.php","","INR","INV123","req.Sale","100","","Ext1","true","Ext3","Ext4","New PHP");

$oBTA->setAddressDetails ("CID","Tester","Aline1","Aline2","Aline3","Pune","A.P","48927489","IND","tester@soft.com");

$oSTA->setAddressDetails ("Add1","Add2","Add3","City","State","443543","IND","sad@df.com");

#$oMPI->setMPIRequestDetails("1245","12.45","356","2","2 shirts","12","20011212","12","0","","image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, application/vnd.ms-powerpoint, application/vnd.ms-excel, application/msword, application/x-shockwave-flash, */*","Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0)");


$oPGResp=$oPostLibphp->postSSL($oBTA,$oSTA,$oMerchant,$oMPI,$oPGReserveData);


if($oPGResp->getRespCode() == '000'){
	$url	=$oPGResp->getRedirectionUrl();
	#$url =~ s/http/https/;
	#print "Location: ".$url."\n\n";
	#header("Location: ".$url);
	redirect($url);
}else{
	print "Error Occured.<br>";
	print "Error Code:".$oPGResp->getRespCode()."<br>";
	print "Error Message:".$oPGResp->getRespMessage()."<br>";
}

# This will remove all white space
#$oResp =~ s/\s*//g;

# $oPGResp->getResponse($oResp);

#print $oPGResp->getRespCode()."<br>";

#print $oPGResp->getRespMessage()."<br>";

#print $oPGResp->getTxnId()."<br>";

#print $oPGResp->getEpgTxnId()."<br>";

function redirect($url) {
	if(headers_sent()){
	?>
		<html><head>
			<script language="javascript" type="text/javascript">
				window.self.location='<?php print($url);?>';
			</script>
		</head></html>
	<?php
		exit;
	} else {
		header("Location: ".$url);
		exit;
	}
}
?>