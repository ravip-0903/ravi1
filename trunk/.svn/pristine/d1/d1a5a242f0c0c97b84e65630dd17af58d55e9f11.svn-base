<?php
if(!defined('AREA') ) { die('Access denied'); }
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$suffix = '';
	if($mode == 'update') {
		foreach($_REQUEST['vendor_parameters'] as $k => $v) {
			db_query("REPLACE INTO ?:sdeep_rating_params (rating_id,name,icon_url) VALUES (?i,?s,?s)", $k,$v['name'],$v['icon_url']);
		}
		$suffix = '.manage';
	}
	return array(CONTROLLER_STATUS_OK, "sdeep_ratings$suffix");
} elseif($mode == 'manage') {
	$view->assign('vendor_rating_params', fn_sdeep_get_vendor_rating_params());
}
?>
