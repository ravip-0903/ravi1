<?php
if(!defined('AREA') ) { die('Access denied'); }
if($_SERVER['REQUEST_METHOD'] == 'POST') {
//	$suffix = '';
	if($mode == 'update') {
		$rating_info = $_REQUEST['rating_info'];
		if(is_array($rating_info) && is_numeric($_REQUEST['order_id'])) {
			$rating_info['timestamp'] = time();
			db_query("UPDATE ?:orders SET rating_info=?s WHERE order_id=?i", serialize($rating_info), $_REQUEST['order_id']);
			$company_id = db_get_field("SELECT company_id FROM ?:orders WHERE order_id=?i", $_REQUEST['order_id']);
			fn_sdeep_get_rating($company_id, true);
		}
		$suffix = '.manage';
	}
	return array(CONTROLLER_STATUS_REDIRECT, $index_script);
} elseif($mode == 'manage') {
	$view->assign('vendor_rating_params', fn_sdeep_get_vendor_rating_params());
}
?>
