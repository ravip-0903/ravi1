<?php
define('AREA', 'A');
define('AREA_NAME', 'admin');
require  dirname(__FILE__) . '/../prepare.php';
require  dirname(__FILE__) . '/../init.php';

$params=$_REQUEST;

if($params['mode']=='code')
{
	$order_id=$_REQUEST['order_id'];
	$sql="select status from cscart_orders where order_id='".$order_id."'";
	echo db_get_field($sql);
	exit;
}
if($params['mode']=='text')
{
	$order_id=$_REQUEST['order_id'];
	$sql="select description from cscart_status_descriptions csd inner join cscart_orders o on o.status=csd.status where o.order_id='".$order_id."' and csd.type='O'";
	echo db_get_field($sql);
	exit;
}


?>