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
// $Id: statuses.php 12865 2011-07-05 06:57:22Z 2tl $
//

if ( !defined('AREA') )	{ die('Access denied');	}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	fn_trusted_vars('status_data');

	if ($mode == 'update') {
		// Added and edited by Sudhir dt 3rd Nov 2012 to validate status bigin here
		if(!preg_match("/^[0-9A-Z]{1,4}+$/", $_REQUEST['status_data']['status'])) {
			fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('error_invalid_status'));
		} else {
			fn_update_status($_REQUEST['status'], $_REQUEST['status_data'], $_REQUEST['type']);
		} // Added and edited by Sudhir dt 3rd Nov 2012 to validate status end here
	}

	return array(CONTROLLER_STATUS_OK, "statuses.manage?type=$_REQUEST[type]");
}

if ($mode == 'update') {

	$status_data = db_get_row("SELECT ?:statuses.*, ?:status_descriptions.* FROM ?:statuses LEFT JOIN ?:status_descriptions ON ?:statuses.status = ?:status_descriptions.status AND ?:statuses.type = ?:status_descriptions.type AND ?:status_descriptions.lang_code = ?s WHERE ?:statuses.status = ?s AND ?:statuses.type = ?s ORDER BY ?:status_descriptions.description", DESCR_SL, $_REQUEST['status'], $_REQUEST['type']);

	$status_data['params'] = db_get_hash_single_array("SELECT param, value FROM ?:status_data USE INDEX(stat_type_idx) WHERE status = ?s AND type = ?s", array('param', 'value'), $_REQUEST['status'], $_REQUEST['type']);

	$view->assign('status_data', $status_data);
	$view->assign('type', $_REQUEST['type']);
	$view->assign('status_params', fn_get_status_params_definition($_REQUEST['type']));

} elseif ($mode == 'delete') {
	if (!empty($_REQUEST['status'])) {
		$can_delete = db_get_field("SELECT status FROM ?:statuses WHERE status = ?s AND type = ?s AND is_default = 'N'", $_REQUEST['status'], $_REQUEST['type']);
		if (!empty($can_delete)) {
			fn_delete_status($_REQUEST['status'], $_REQUEST['type']);
			
			$count = db_get_field("SELECT COUNT(*) FROM ?:statuses");
			if (empty($count)) {
				$view->display('views/statuses/manage.tpl');
			}
		}
	}
	exit;

} elseif ($mode == 'manage') {

	$section_data = array();

	$statuses = db_get_hash_array("SELECT ?:statuses.*, ?:status_descriptions.* FROM ?:statuses LEFT JOIN ?:status_descriptions ON ?:statuses.status = ?:status_descriptions.status AND ?:statuses.type = ?:status_descriptions.type AND ?:status_descriptions.lang_code = ?s AND ?:statuses.type = ?s ORDER BY ?:status_descriptions.description", 'status', DESCR_SL, $_REQUEST['type']);

	$view->assign('statuses', $statuses);

	$type = !empty($_REQUEST['type']) ? $_REQUEST['type'] : STATUSES_ORDER;
	$view->assign('type', $type);
	$view->assign('status_params', fn_get_status_params_definition($type));

	// Orders only
	if ($type == STATUSES_ORDER) {
		$view->assign('title', fn_get_lang_var('order_statuses'));
	}
}

function fn_get_status_params_definition($type)
{
	$status_params = array();

	if ($type == STATUSES_ORDER) {
		$status_params = array (
			'notify' => array (
				'type' => 'checkbox',
				'label' => 'notify_customer'
			),
			'notify_department' => array (
				'type' => 'checkbox',
				'label' => 'notify_orders_department'
			),
			'notify_supplier' => array (
				'type' => 'checkbox',
				'label' => 'notify_supplier'
			),
			'inventory' => array (
				'type' => 'select',
				'label' => 'inventory',
				'variants' => array (
					'I' => 'increase',
					'D' => 'decrease',
				),
			),
			'remove_cc_info' => array (
				'type' => 'checkbox',
				'label' => 'remove_cc_info'
			),
			'repay' => array (
				'type' => 'checkbox',
				'label' => 'pay_order_again'
			),
			'appearance_type' => array (
				'type' => 'select',
				'label' => 'invoice_credit_memo',
				'variants' => array (
					'D' => 'default',
					'I' => 'invoice',
					'C' => 'credit_memo',
					'O' => 'order'
				),
			),
		  'allow_cancelation'=>array(    //added by ankur
			  'type'=>'checkbox',
			  'label' =>'allow_cancel'
		   ),
		);
		if (PRODUCT_TYPE == 'PROFESSIONAL' && Registry::get('settings.Suppliers.enable_suppliers') != 'Y' || PRODUCT_TYPE == 'COMMUNITY') {
			unset($status_params['notify_supplier']);
		} elseif (PRODUCT_TYPE == 'MULTIVENDOR' || PRODUCT_TYPE == 'MULTISHOP') {
			$status_params['notify_supplier']['label'] = 'notify_vendor';
			$status_params['calculate_for_payouts'] = array(
				'type' => 'checkbox',
				'label' => 'charge_to_vendor_account'
			);
		}
	}

	fn_set_hook('get_status_params_definition', $status_params, $type);

	return $status_params;
}

?>
