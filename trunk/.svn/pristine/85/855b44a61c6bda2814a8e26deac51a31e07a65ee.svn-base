<?php
define('AREA', 'A');
define('AREA_NAME', 'admin');

require dirname(__FILE__) . '/../prepare.php';
require dirname(__FILE__) . '/../init.php';
$zeroProduct = db_get_array("SELECT   c.company, p.product_code, pd.product FROM `cscart_products` p join cscart_companies c on (p.company_id = c.company_id) 
join cscart_product_descriptions pd on (p.product_id = pd.product_id) order by 1,2");
?>

<table cellpadding="0" cellspacing="0" border="1" width="900">
<h1 style="padding-left:100px">Product List</h1>
<tr><td colspan="4"><hr></hr></td></tr>

<tr>
	<td valign="top" align="left" >Sl. No.</td>
	<td valign="top" align="left" >Company Name</td>
	<td valign="top" align="left" >Product Code</td>
	<td valign="top" align="center" style="padding-right:10px">Product Name</td>
</tr>
<tr><td colspan="4"><hr></hr></td></tr>
<?php for($i=0; $i<sizeof($zeroProduct); ++$i){	?>		
<tr>
	<td valign="top"><?php echo $i + 1;?></td>
	<td valign="top" align="left" style="padding-right:10px"><?php echo $zeroProduct[$i]['company'];?></td>
	<td valign="top"><?php echo $zeroProduct[$i]['product_code'];?></td>
	<td valign="top"><?php echo $zeroProduct[$i]['product'];?></td>
</tr>
<?php } ?>
</table>
