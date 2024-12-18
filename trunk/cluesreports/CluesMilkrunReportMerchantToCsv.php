<?php
define('AREA', 'A');
define('AREA_NAME', 'admin');
require dirname(__FILE__) . '/../prepare.php';
require dirname(__FILE__) . '/../init.php';
$out="";

if (isset($_POST['dataarry'])) {
	$dataarry=$_POST['dataarry'];
}
if (isset($_POST['csv_hdr'])) {
	$out .= $_POST['csv_hdr'];
	$out .= "\n";
}

if($_POST && $_POST['merchantName'] !='All'){
	$merchantName=$_POST['merchantName'];
} else {$merchantName="All";}


$milkRunByMerchantQry ="SELECT cpd.product, cod.amount, cp.merchant_reference_number, cod.product_code, co.order_id, co.s_firstname, co.s_lastname, co.s_address, co.s_address_2, co. s_city, co.s_state, co.s_country, co.s_zipcode, co.s_phone, co.subtotal FROM cscart_order_details cod LEFT JOIN cscart_orders co ON cod.order_id = co.order_id LEFT JOIN cscart_companies cc ON cc.company_id = co.company_id LEFT JOIN cscart_products cp ON cp.product_id = cod.product_id LEFT JOIN cscart_product_descriptions cpd ON cod.product_id = cpd.product_id WHERE (co.status='O' OR co.status='P' OR co.status='B')";

if($_POST['merchantNameById'] == 'All') {
	$milkRunByMerchantQry ="SELECT cpd.product, cod.amount, cp.merchant_reference_number, cod.product_code, co.order_id, co.s_firstname, co.s_lastname, co.s_address, co.s_address_2, co. s_city, co.s_state, co.s_country, co.s_zipcode, co.s_phone, co.subtotal, co.company_id, cc.company FROM cscart_order_details cod LEFT JOIN cscart_orders co ON cod.order_id = co.order_id LEFT JOIN cscart_companies cc ON cc.company_id = co.company_id LEFT JOIN cscart_products cp ON cp.product_id = cod.product_id LEFT JOIN cscart_product_descriptions cpd ON cod.product_id = cpd.product_id WHERE (co.status='O' OR co.status='P' OR co.status='B')";
} 
if($_POST && $_POST['merchantNameById'] !='All') {
	$milkRunByMerchantQry .= db_quote( " AND co.company_id=".$_POST['merchantNameById']);
}

$data = db_get_array($milkRunByMerchantQry);

$merchantName=str_replace(" ","-",$merchantName); 
//$filename = $file."_".date("Y-m-d_H-i",time());
$filename = $file."Merchant_Milkrun_Report_".$merchantName."_".date("Y-m-d").".xls";
 
?>
<?PHP 
function cleanData(&$str) { 
	$str = preg_replace("/\t/", "\\t", $str);
	$str = preg_replace("/\r?\n/", "\\n", $str); 
	if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
}  

header("Content-Disposition: attachment; filename=\"$filename\""); 
header("Content-Type: application/vnd.ms-excel"); 
$flag = false; 
foreach($data as $row) { 
	if(!$flag) { # display field/column names as first row 
		echo implode("\t", array_keys($row)) . "\r\n"; 
		$flag = true; 
	} 
	array_walk($row, 'cleanData'); 
	echo implode("\t", array_values($row)) . "\r\n"; 
} 
exit; 
?>

