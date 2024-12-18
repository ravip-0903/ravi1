<?php
define('AREA', 'A');
define('AREA_NAME', 'admin');
require dirname(__FILE__) . '/../prepare.php';
require dirname(__FILE__) . '/../init.php';
$duplicateAddress = db_get_array("SELECT u.user_id, u.email, ou1.b_address
FROM cscart_users u
INNER JOIN ( SELECT up.b_address, up.user_id FROM cscart_user_profiles up
	INNER JOIN( SELECT up1.b_address, COUNT(*) AS n 
	FROM cscart_user_profiles up1 INNER JOIN cscart_users u ON(up1.user_id=u.user_id) 
	WHERE up1.b_address<>'' AND u.status='A' GROUP BY up1.b_address HAVING n>2 ORDER BY n DESC ) ou ON(up.b_address=ou.b_address)
) ou1 ON(u.user_id=ou1.user_id)");
$slno=1;
?>

<table cellpadding="0" cellspacing="0" border="0" width="900">
<h1 style="padding-left:50px">Duplicate Address</h1>
<p>This report shows Duplicate address </p>
<tr><td colspan="4"><hr></hr></td></tr>

<tr>
	<td valign="top" align="left" >SL_No</td>
	<td valign="top" align="center" style="padding-right:10px">User Id</td>
	<td valign="top">E-mail Id</td>
	<td valign="top">Address</td>
	
</tr>
<tr><td colspan="4"><hr></hr></td></tr>
<?php for($i=0; $i<sizeof($duplicateAddress); ++$i){	?>		
<tr>
	<td valign="top"><?= $slno;?></td>
	<td valign="top" align="left" style="padding-right:10px"><?= $duplicateAddress[$i]['user_id'];?></td>
	<td valign="top"><?= $duplicateAddress[$i]['email'];?></td>
	<td valign="top"><?= $duplicateAddress[$i]['b_address'];?></td>
</tr>
<?php $slno=$slno+1;} ?>
</table>

