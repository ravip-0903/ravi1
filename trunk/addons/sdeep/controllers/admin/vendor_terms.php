<?php
if(!defined('AREA') ) { die('Access denied'); }
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if($mode == 'update') {
		if(defined('COMPANY_ID') && COMPANY_ID && is_array($_REQUEST['terms'])) {
			db_query("UPDATE ?:companies SET terms=?s WHERE company_id=?i", @serialize($_REQUEST['terms']), COMPANY_ID);
		}
	}
	return array(CONTROLLER_STATUS_OK, "vendor_terms.manage");
} elseif($mode == 'manage') {
	$view->assign('terms', fn_sdeep_get_terms(COMPANY_ID));
}
?>
