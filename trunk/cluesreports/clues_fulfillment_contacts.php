<?php
define('AREA', 'A');
define('AREA_NAME', 'admin');
require dirname(__FILE__) . '/../prepare.php';
require dirname(__FILE__) . '/../init.php';
$foldr=basename(dirname(__FILE__));
if ($_POST) {

$compnyPostVal=array_filter(explode("-",$_POST['Company']));
$qryInsert="INSERT INTO clues_fullfillment_contacts (company_id, company, address1, address2, city, state, p_contact_name, p_contact_phone, s_contact_name, s_contact_phone, region) VALUES (".$compnyPostVal[0].", '".$compnyPostVal[1]."', '".$_POST['Address1']."', '".$_POST['Address2']."', '".$_POST['City']."', '".$_POST['State']."', '".$_POST['PC_Name']."', '".$_POST['PC_Phone']."', '".$_POST['SC_Name']."', '".$_POST['SC_Phone']."', '".$_POST['Region']."')";

mysql_query($qryInsert);
header("Location: " . $_SERVER['HTTP_REFERER']);

}

$contacts=db_get_array('SELECT * FROM  clues_fullfillment_contacts  WHERE  status = "A"');
$slno=1;
$merchants =db_get_array("SELECT distinct(co.company_id), cc.company FROM cscart_order_details cod LEFT JOIN cscart_orders co ON cod.order_id = co.order_id LEFT JOIN cscart_companies cc ON cc.company_id = co.company_id LEFT JOIN cscart_products cp ON cp.product_id = cod.product_id LEFT JOIN cscart_product_descriptions cpd ON cod.product_id = cpd.product_id WHERE (co.status='O' OR co.status='P' OR co.status='B')");

?>
<html><head>
<script type="text/javascript">
function submitform(){document.getElementById("fulfillmentcontacts").submit();}
</script>
</head><title>Fulfillment Contacts</title>
<body>
<div style="background:lightblue;height:20px;width:100%;">
	<div id="menu" align="left" >
	<a href="<?php $_SERVER['SERVER_NAME']?><?='/'.$foldr;?>/clues_fulfillment_contacts.php"><strong>HOME</strong></a>
	</div>
</div>

<div style="float:left;visibility:visible;width:15%;height:100%;background-color:#FFFFCC;layer-backgroundcolor:#FFFFCC;border-style:groove;"> 
	<div align="left" style="font-size:10pt;font-family:areal;font-weight:bold;font-style:normal;color:#23238e;height:20px;">
		<a href="<?php $SERVER['PHP_SELF']?>?type=add">Add Fulfillment Contacts</a>
	</div>
</div>

<div align="rihgt" style="float:left;width:84%;height:100%;background-color:#FFFFFF;border-style:groove;">
<?php 
if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_REQUEST['type'] !='' && $_REQUEST['type'] =='add'){ ?>
	
	<form id="fulfillmentcontacts" method="post" action="<?php $_SERVER['SERVER_NAME']?><?='/'.$foldr;?>/clues_fulfillment_contacts.php">
	<table berdor="0" width="100%">
		<tr align="center"><td colspan="3">Fulfillment Contacts</td></tr>
			<tr align="center"><td><table berdor="0">

				<tr>
					<td>Company Name</td>
					<td>
					
						<select name="Company">
						<option selected="selected">All Merchants</option>
						<?php foreach($merchants as $merchant=>$companyVal){?>	
				<option value="<?=$merchants[$merchant]['company_id'].'-'.$merchants[$merchant]['company'];?>"><?= $merchants[$merchant]['company'];?></option>
						<?php }?>
						</select>					
					</td>
					<td>&nbsp;</td>
				<tr>
				<tr>
					<td>Address 1</td>
					<td><input type="text" name="Address1" id="Address1" value="" width="200"></td>
					<td>&nbsp;</td>
				<tr>
				<tr>
					<td>Address 2</td>
					<td><input type="text" name="Address2" id="Address2" value="" width="200"></td>
					<td>&nbsp;</td>
				<tr>
				<tr>
					<td>City</td>
					<td><input type="text" name="City" id="City" value="" width="200"></td>
					<td>&nbsp;</td>
				<tr>

				<tr>
					<td>State</td>
					<td><input type="text" name="State" id="State" value="" width="200"></td>
					<td>&nbsp;</td>
				<tr>

				<tr>
					<td>Primary Contact Name</td>
					<td><input type="text" name="PC_Name" id="PC_Name" value="" width="200"></td>
					<td>&nbsp;</td>
				<tr>

				<tr>
					<td>Primary Contacted Phone</td>
					<td><input type="text" name="PC_Phone" id="PC_Phone" value="" width="200"></td>
					<td>&nbsp;</td>
				<tr>

				<tr>
					<td>Secondary Contact Name</td>
					<td><input type="text" name="SC_Name" id="SC_Name" value="" width="200"></td>
					<td>&nbsp;</td>
				<tr>

				<tr>
					<td>Secondary Contacted Phone</td>
					<td><input type="text" name="SC_phone" id="SC_phone" value="" width="200"></td>
					<td>&nbsp;</td>
				<tr>
				<tr>
					<td>Region</td>
					<td><input type="text" name="Region" id="Region" value="" width="200"></td>
					<td>&nbsp;</td>
				<tr>

			</table></td><tr>
			<tr align="center">

				<td colspan="2"><input type="button" value="Add Fulfillment Contacts" onclick="submitform()"></td></tr>
	</table></form>

<?php } ?>

	<table berdor="0" width="100%">

		<tr align="center" style="background:lightblue;height:20px;width:100%;">
			<td>Company Name</td>
			<td>Address 1</td>
			<td>Address 2</td>
			<td>City</td>
			<td>State</td>
			<td>P_Contact</br>Name</td>
			<td>P_Contact</br>Phone</td>
			<td>S_Contact</br>Name</td>
			<td>S_Contact</br>Phone</td>
			<td>Region</td>
		</tr>
<?php for($i=0; $i<sizeof($contacts); ++$i){ ?>
		<tr align="center">
			<td align="left"><?= $contacts[$i]['company'];?></td>
			<td align="left"><?= $contacts[$i]['address1'];?></td>
			<td align="left"><?= $contacts[$i]['address2'];?></td>
			<td align="left"><?= $contacts[$i]['city'];?></td>
			<td align="left"><?= $contacts[$i]['state'];?></td>
			<td align="left"><?= $contacts[$i]['p_contact_name'];?></td>
			<td align="left"><?= $contacts[$i]['p_contact_phone'];?></td>
			<td align="left"><?= $contacts[$i]['s_contact_name'];?></td>
			<td align="left"><?= $contacts[$i]['s_contact_phone'];?></td>
			<td align="left"><?= $contacts[$i]['region'];?></td>
		</tr>
<?php $slno=$slno+1; } ?>
	</table>
</div>
</body>
</html>

