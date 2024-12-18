<?php
define('AREA', 'A');
define('AREA_NAME', 'admin');

require dirname(__FILE__) . '/../prepare.php';
require dirname(__FILE__) . '/../init.php';
$zeroProduct = db_get_array("SELECT cscart_orders.order_id, FROM_UNIXTIME(cscart_orders.timestamp) as order_date, cscart_shipments.shipment_id,cscart_shipments.tracking_number, cscart_shipments.carrier,cscart_product_descriptions.product, cscart_orders.firstname, cscart_orders.lastname  FROM cscart_orders INNER JOIN cscart_shipment_items ON cscart_orders.order_id = cscart_shipment_items.order_id INNER JOIN cscart_shipments ON cscart_shipments.shipment_id = cscart_shipment_items.shipment_id INNER JOIN cscart_product_descriptions ON cscart_product_descriptions.product_id = cscart_shipment_items.product_id WHERE (cscart_orders.status='C' OR cscart_orders.status='A' OR cscart_orders.status='H')");
?>

<table cellpadding="0" cellspacing="0" border="1" width="900">
<h1 style="padding-left:100px">All Orders in - Delivered, Shipped or Complete</h1>
<tr><td colspan="8"><hr></hr></td></tr>

<tr>
	<td valign="top" align="left" >Sl No</td>
	<td valign="top" align="left" >Order ID</td>
	<td valign="top" align="center" style="padding-right:10px">Order DateTime</td>
	<td valign="top">Shipment ID</td>
	<td valign="top">Tracking Number</td>
	<td valign="top">Carrier Name</td>
	<td valign="top">Product Name</td>
	<td valign="top">First Name</td>
	<td valign="top">Last Name</td>
</tr>
<tr><td colspan="8"><hr></hr></td></tr>
<?php for($i=0; $i<sizeof($zeroProduct); ++$i){	?>		
<tr>
	<td valign="top"><?php echo $i + 1;?></td>
	<td valign="top" align="left" style="padding-right:10px"><?php echo $zeroProduct[$i]['order_id'];?></td>
	<td valign="top"><?php echo $zeroProduct[$i]['order_date'];?></td>
	<td valign="top"><?php echo $zeroProduct[$i]['shipment_id'];?></td>
	<td valign="top"><?php echo $zeroProduct[$i]['tracking_number'];?></td>
	<td valign="top"><?php echo $zeroProduct[$i]['carrier'];?></td>
	<td valign="top"><?php echo $zeroProduct[$i]['product'];?></td>
	<td valign="top"><?php echo $zeroProduct[$i]['firstname'];?></td>
	<td valign="top"><?php echo $zeroProduct[$i]['lastname'];?></td>
</tr>
<?php } ?>
</table>
