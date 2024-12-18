<?php
/***************************************************************************
*                                                                          *
*   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
*                                                                          *
* This  is  commercial  software,  only  users  who have purchased a valid *
* license  and  accept  to the terms of the  License Agreement can install *
* and use this program.                                                    *
*                                                                          *
****************************************************************************
* PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
* "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
****************************************************************************/


//
// $Id: companies.post.php 12865 2011-07-05 06:57:22Z 2tl $
//

if ( !defined('AREA') ) { die('Access denied'); }

// Ajax content
if ($mode == 'get_companies_list') {
	
	$condition = '';
	$pattern = !empty($_REQUEST['pattern']) ? $_REQUEST['pattern'] : '';
	$start = !empty($_REQUEST['start']) ? $_REQUEST['start'] : 0;
	$limit = (!empty($_REQUEST['limit']) ? $_REQUEST['limit'] : 10) + 1;
	
	if (AREA == 'C') {
		$condition = " AND status = 'A' ";
	}
	
	fn_set_hook('get_companies_list', $condition, $pattern, $start, $limit);
	
	$objects = db_get_hash_array("SELECT company_id as value, company AS name, CONCAT('s_company=', company_id) as append FROM ?:companies WHERE 1 $condition AND company LIKE ?l ORDER BY company LIMIT ?i, ?i", 'value', $pattern . '%', $start, $limit);

	if (defined('AJAX_REQUEST') && sizeof($objects) < $limit) {
		$ajax->assign('completed', true);
	} else {
		array_pop($objects);
	}

	if (empty($_REQUEST['start']) && empty($_REQUEST['pattern'])) {
		$all_vendors = array();
		
		$all_vendors['0'] = array(
			'name' => Registry::get('settings.Company.company_name') . ' (' . fn_get_lang_var('default') . ')',
			'value' => '0',
			'extra_class' => 'default-company'
		);
		
		if (!empty($_REQUEST['show_all']) && $_REQUEST['show_all'] == 'Y') {
			$all_vendors['all'] = array(
				'name' => fn_get_lang_var('all_vendors'),
				'value' => (!empty($_REQUEST['search']) && $_REQUEST['search'] == 'Y') ? '' : 'all',
			);
		}
		
		$objects = $all_vendors + $objects;
	}
	
	if (defined('AJAX_REQUEST') && !empty($_REQUEST['action'])) {
		$ajax->assign('action', $_REQUEST['action']);
	}	
	
	$view->assign('objects', $objects);
	$view->assign('id', $_REQUEST['result_ids']);
	$view->display('common_templates/ajax_select_object.tpl');
	exit;
}

?>