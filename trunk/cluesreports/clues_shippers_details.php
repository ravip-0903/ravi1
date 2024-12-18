<?php
define('AREA', 'A');
define('AREA_NAME', 'admin');
require dirname(__FILE__) . '/../prepare.php';
require dirname(__FILE__) . '/../init.php';
require_once 'Excel/reader.php';
$foldr=basename(dirname(__FILE__));
$filenm="office_buyer.xls";
/*** 
if you want you can change 'iconv' to mb_convert_encoding:
$data->setUTFEncoder('mb');
**/

/*** By default rows & cols indeces start with 1
	For change initial index use:
	$data->setRowColOffset(0);
**/

$orderid=clues_fn_get_orderid($filenm);

function clues_fn_get_orderid($filenm){
	$data = new Spreadsheet_Excel_Reader();
	// Set output Encoding.
	$data->setOutputEncoding('CP1251');
	$data->read($filenm);

	error_reporting(E_ALL ^ E_NOTICE);
	$startloging=false;
	$k=0;
	
	echo "<TABLE width='80%' border='1'>
	<h1>Shopclues.com  Order Details</h1>
	<tr>
		<td align='center'>ORDERNO</td>
		<td align='center'>OrderAmount</td>
		<td align='center'>Shippers Name</td>
		<td align='center' colspan='2'>Shippers Address</td>
		<td align='center'>City</td>
		<td align='center' colspan='2'>State</td>
		<td align='center'>Payment Method</td>
		<td align='center'>Merchant Name</td>
	</tr>
	<tr> ";
	$orderidDetailId="";
	for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
		for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
			if($data->sheets[0]['cells'][$i][$j]=='ORDERNO'){
	        $startloging=true;
	            break;
	        }
	           	
			//if($startloging){
				//echo $data->sheets[0]['cells'][$i][1].','; //read the first colmns
				//echo $data->sheets[0]['cells'][$i][2]; //read the second colmns
			//}
	            
			if($startloging){
				$ordercolm=$k%2;
					
				if($ordercolm==0){
				$buyerQry="select co.order_id, co.total, co.s_firstname, co.s_lastname, co.s_address, co.s_address_2, co.s_city, co.s_state, co.s_phone, cpd.payment, cc.company from cscart_orders co join cscart_payments cp ON (co.payment_id = cp.payment_id) join cscart_payment_descriptions cpd ON (co.payment_id = cpd.payment_id) JOIN cscart_companies cc ON ( co.company_id = cc.company_id ) WHERE co.order_id =".$data->sheets[0]['cells'][$i][1]." and (co.status='O' OR co.status='P') ORDER BY  cc.company";
				$orderidDetail=db_get_row($buyerQry);
				echo"<td>" .$orderidDetail['order_id']."</td><td>".$orderidDetail['total']."</td><td>".$orderidDetail['s_firstname']." ".$orderidDetail['s_lastname']."</td><td>".$orderidDetail['s_address']."</td><td>".
				$orderidDetail['s_address2']."</td><td>".$orderidDetail['s_city']."</td><td>".$orderidDetail['s_state']."</td><td>".$orderidDetail['s_phone']."</td><td>".$orderidDetail['payment']."</td><td>".
				$orderidDetail['company'].'</td>';
				$orderidDetailId.=$orderidDetail['order_id'].',';
				}
					
				$k=$k+1;
			}
		}echo"</tr>";
	}
	echo"
	<tr><td align='center' colspan='12'>
		<form name='buyeraddressfrm' action='/".$foldr."/clues_shippers_report_csv.php' method='post'>
		<input type='hidden' value='".$orderidDetailId."' name='orderidDetailId' >
		<input type='submit' value='Buyer Details Export To Csv'>
		</form>
	</td></tr>
	</table>";
}

?>
