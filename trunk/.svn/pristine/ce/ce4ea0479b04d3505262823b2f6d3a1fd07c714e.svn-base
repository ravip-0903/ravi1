<?php
define('AREA', 'A');
define('AREA_NAME', 'admin');
require dirname(__FILE__) . '/../prepare.php';
require dirname(__FILE__) . '/../init.php';
$zeroProduct = db_get_array("SELECT c.company, p.product_code, pd.product, pc.price, p.list_price FROM cscart_products p join cscart_product_prices pc on (p.product_id = pc.product_id) JOIN cscart_companies c ON ( p.company_id = c.company_id ) JOIN cscart_product_descriptions pd ON ( p.product_id = pd.product_id ) where p.status = 'A' and pc.price = 0 and c.status = 'A' ORDER BY 1 , 2, 4");
?>

<table cellpadding="0" cellspacing="0" border="0" width="900">
<h1 style="padding-left:100px">Products with ZERO Listing Price</h1>
<p>This report shows only products that are LIVE on ShopClues.com from Merchants with ACTIVE status</p>
<tr><td colspan="6"><hr></hr></td></tr>

<tr>
	<td valign="top" align="left" >SL_No</td>
	<td valign="top" align="center" style="padding-right:10px">company</td>
	<td valign="top">Product Code</td>
	<td valign="top">Product</td>
	<td valign="top">Selling Price</td>
	<td valign="top">List Price</td>
</tr>
<tr><td colspan="6"><hr></hr></td></tr>
<?php for($i=0; $i<sizeof($zeroProduct); ++$i){	?>		
<tr>
	<td valign="top"><?php echo $i;?></td>
	<td valign="top" align="left" style="padding-right:10px"><?php echo $zeroProduct[$i]['company'];?></td>
	<td valign="top"><?php echo $zeroProduct[$i]['product_code'];?></td>
	<td valign="top"><?php echo $zeroProduct[$i]['product'];?></td>
	<td valign="top"><?php echo $zeroProduct[$i]['price'];?></td>
	<td valign="top"><?php echo $zeroProduct[$i]['list_price'];?></td>
</tr>
<?php } ?>
</table>
