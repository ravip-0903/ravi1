<?php
if(!defined('AREA') ) { die('Access denied'); }
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$suffix = '';
	if($mode == 'update') {
		foreach($_REQUEST['icons'] as $k => $v) {
			if($v['pattern'] && $v['icon_url']) {
				db_query("REPLACE INTO ?:sdeep_lang_icons (pattern,icon_url) VALUES (?s,?s)",$v['pattern'],$v['icon_url']);
			}
		}
		$suffix = '.manage';
	}
	return array(CONTROLLER_STATUS_OK, "sdeep_iconization$suffix");
} elseif($mode == 'manage') {
	$view->assign('lang_icons', fn_sdeep_get_lang_icons());
}
?>
