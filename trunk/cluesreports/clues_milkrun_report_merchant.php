<?php
define('AREA', 'A');
define('AREA_NAME', 'admin');
require dirname(__FILE__) . '/../prepare.php';
require dirname(__FILE__) . '/../init.php';

$milkRunByMerchantQry ="SELECT cpd.product, cod.amount, cp.merchant_reference_number, cod.product_code, co.order_id, co.s_firstname, co.s_lastname, co.s_address, co.s_address_2, co. s_city, co.s_state, co.s_country, co.s_zipcode, co.s_phone, co.subtotal FROM cscart_order_details cod 
LEFT JOIN cscart_orders co ON cod.order_id = co.order_id 
LEFT JOIN cscart_companies cc ON cc.company_id = co.company_id 
LEFT JOIN cscart_products cp ON cp.product_id = cod.product_id 
LEFT JOIN cscart_product_descriptions cpd ON cod.product_id = cpd.product_id 
WHERE (co.status='O' OR co.status='P' OR co.status='B') ";

if($_POST['merchantNameById'] == 'All') {
	$milkRunByMerchantQry ="SELECT cpd.product, cod.amount, cp.merchant_reference_number, cod.product_code, co.order_id, co.s_firstname, co.s_lastname, co.s_address, co.s_address_2, co. s_city, co.s_state, co.s_country, co.s_zipcode, co.s_phone, co.subtotal, co.company_id, cc.company 
	FROM cscart_order_details cod 
	LEFT JOIN cscart_orders co ON cod.order_id = co.order_id 
	LEFT JOIN cscart_companies cc ON cc.company_id = co.company_id 
	LEFT JOIN cscart_products cp ON cp.product_id = cod.product_id 
	LEFT JOIN cscart_product_descriptions cpd ON cod.product_id = cpd.product_id 
	WHERE (co.status='O' OR co.status='P' OR co.status='B') ";
} 
if($_POST && $_POST['merchantNameById'] !='All') {
	$milkRunByMerchantQry .= db_quote( " AND co.company_id=".$_POST['merchantNameById']);
}
$milkRunByMerchant = db_get_array($milkRunByMerchantQry);

$companies=db_get_array("SELECT distinct(cc.company), co.company_id FROM cscart_order_details cod 
	LEFT JOIN cscart_orders co ON cod.order_id = co.order_id 
	LEFT JOIN cscart_companies cc ON cc.company_id = co.company_id 
	LEFT JOIN cscart_products cp ON cp.product_id = cod.product_id 
	LEFT JOIN cscart_product_descriptions cpd ON cod.product_id = cpd.product_id 
	WHERE (co.status='O' OR co.status='P' OR co.status='B') ");

?>

<style type="text/css" media="screen,print">
.dottedline { border-bottom: 1px dotted Black;}
</style>

<div id="MilkrunReportMerchant">
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<h1>Milkrun Report(Merchant Pickup)</h1>
<tr><td colspan="7"><hr></hr></td></tr>
<tr><td width="50%">
<form id="milkrunMerchantFrm" action="http://local.shopclues.com/reports/clues_milkrun_report_merchant.php" method="post" > 
<table border="0"><tr>
<td>
	<select name="merchantNameById">
	<option value="All" selected="selected">All Merchants</option>
	<?php foreach($companies as $company=>$companyVal){?>	
		<option value="<?=$companies[$company]['company_id'];?>"><?= $companies[$company]['company'];?></option>
	<?php }?>
	</select>
</td>

<!--<td class="search-field"><label>Select dates:</label>
	<div class="break nowrap">
	<input id="f_date" class="input-text cm-calendar hasDatepicker" type="text" size="10" onchange="" value="" name="time_from">
	<img class="cm-external-focus calendar-but" alt="Calendar" title="Calendar" rev="f_date" src="/skins/basic/admin/images/icons/calendar.png">
	&nbsp;&nbsp;-&nbsp;&nbsp;
	<input id="t_date" class="input-text cm-calendar hasDatepicker" type="text" size="10" onchange="" value="" name="time_to">
	<img class="cm-external-focus calendar-but" alt="Calendar" title="Calendar" rev="t_date" src="/skins/basic/admin/images/icons/calendar.png">
	</div>
</td>-->

<td class="buttons-container"><span class="submit-button "><input type="submit" value="Search" onclick="submitform()"></span></td>
</tr></table></form></td>
<td >
<form name="export" action="http://local.shopclues.com/reports/clues_xporttocsv_milkrunmerchant.php" method="post">
<input type="submit" value="Export to csv">
<input type="hidden" value="<?echo $csv_hdr; ?>" name="csv_hdr">
<input type="hidden" value="<?echo $milkRunByMerchantQry;?>" name="dataarry" >
</form>

</td></tr>

<tr><td colspan="7"><hr></hr></td></tr>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
<!--<h2 style="padding-left:100px">Milkrun Report(Merchant Pickup)</h2>-->
<tr><td colspan="7">Merchant_Name: <?php ?></td></tr>
<tr><td>Date:</td><td colspan="6"><strong><?= date("Y-m-d");?></strong></td></tr>
<tr><td colspan="7">Total Order Count: <strong><?=sizeof($milkRunByMerchant)?></strong></td></tr>

<tr><td colspan="7" class="dottedline">&nbsp;</td></tr>
<tr>
	<td valign="top" align="left" >SL_No</td>
	<td valign="top" align="center" style="padding-right:10px">Product Title</td>
	<td valign="top">Buyer Name</td>
	<td valign="top">Address</td>
	<td valign="top">QTY</td>
	<td valign="top">Merchant</br>SKU</td>
	<td>Bar Code</td>

</tr>
<tr><td colspan="7"><hr></hr></td></tr>
<?php for($i=0; $i<sizeof($milkRunByMerchant); ++$i){ ?>		
<tr>
	<td valign="top"><?php echo $i;?></td>
	<td valign="top" align="left" style="padding-right:10px"><?php echo $milkRunByMerchant[$i]['product'];?>
	</br>Price: <?php echo $milkRunByMerchant[$i]['subtotal'];?>
	</td>
	
	<td valign="top"><?php echo $milkRunByMerchant[$i]['s_firstname'].'&nbsp;'.$milkRunByMerchant[$i]['s_lastname'];?></td>
	<td valign="top"><?php echo $milkRunByMerchant[$i]['s_address'].'</br>'.$milkRunByMerchant[$i]['s_address_2'].'</br>'.$milkRunByMerchant[$i]['s_city'];?></td>
	<td valign="top"><?php echo $milkRunByMerchant[$i]['amount'];?></td>
	<td valign="top"><?php echo $milkRunByMerchant[$i]['merchant_reference_number'];?></td>
	<td>SCIN
	<div class="center margin-top"><?php echo $milkRunByMerchant[$i]['product_code'];?></div>
	SC-ORDER
	<div class="center margin-top"><?php echo $milkRunByMerchant[$i]['order_id'];?></div>
	</td>

</tr>
<tr><td colspan="7" class="dottedline">&nbsp;</td></tr>
<?php } ?>
</table>
</table>
</div>
<script type="text/javascript">
function submitform(){
	document.getElementById("milkrunMerchantFrm").submit();
}
</script>
