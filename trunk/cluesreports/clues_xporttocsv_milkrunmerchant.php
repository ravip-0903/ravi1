<?php
define('AREA', 'A');
define('AREA_NAME', 'admin');
require dirname(__FILE__) . '/../prepare.php';
require dirname(__FILE__) . '/../init.php';
$out="";

if (isset($_POST['csv_hdr'])) {
$out .= $_POST['csv_hdr'];
$out .= "\n";
}

if (isset($_POST['dataarry'])) {
	$dataarry=$_POST['dataarry'];
}

$dt = db_get_array($dataarry);

//$filename = $file."_".date("Y-m-d_H-i",time());
$filename = $file."Merchant_Milkrun_Report_".date("Y-m-d");

//Generate the CSV file header
header("Content-type: application/vnd.ms-excel");
//header("Content-type:text/octect-stream");
header("Content-disposition: csv" . date("Y-m-d") . ".csv");
header("Content-disposition: filename=".$filename.".csv");

//Print the contents of out to the generated file.
$out .= "<table>";
$out .= "<tr>";
$out .= "<td valign='top'>SL_NO</td>";
$out .= "<td valign='top'>Product Title</td>";
$out .= "<td valign='top'>Price</td>";
$out .= "<td valign='top'>Buyer_Name</td>";
$out .= "<td valign='top'>Address</td>";
$out .= "<td valign='top'>QTY</td>";
$out .= "<td valign='top'>SKU</td>";
$out .= "<td valign='top'>Product_code</td>";
$out .= "<td valign='top'>Order</td>";
$out .="</tr>";
	for($i=0; $i<sizeof($dt); ++$i){
		$out .= "<tr>";
		$out .= "<td valign='top'>".$i."</td>";
		$out .= "<td valign='top'>".$dt[$i]['product']."</td>";
		$out .= "<td valign='top'>".$dt[$i]['subtotal']."</td>";
		$out .= "<td valign='top'>".$dt[$i]['s_firstname'].' '.$dt[$i]['s_lastname']."</td>";
		$out .= "<td valign='top'>".$dt[$i]['s_address'].'</br>'.$dt[$i]['s_address_2'].'</br>'.$dt[$i]['s_city']."</td>";
		$out .= "<td valign='top'>".$dt[$i]['amount']."</td>";
		$out .= "<td valign='top'>".$dt[$i]['merchant_reference_number']."</td>";
		$out .= "<td valign='top'>".$dt[$i]['product_code']."</td>";
		$out .= "<td valign='top'>".$dt[$i]['order_id']."</td>";
		$out .="</tr>";
	}
$out .="</table>";
print $out;

//Exit the script
exit;

?>
