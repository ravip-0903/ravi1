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


if ( !defined('AREA') )	{ die('Access denied');	}

if ($mode == 'complete') {

	if (!empty($_REQUEST['order_id'])) {
		if (empty($auth['user_id'])) {
			if (empty($auth['order_ids'])) {
				return array(CONTROLLER_STATUS_REDIRECT, "auth.login_form?return_url=" . urlencode(Registry::get('config.current_url')));
			} else {
				$allowed_id = in_array($_REQUEST['order_id'], $auth['order_ids']);
			}
		} else {
			$allowed_id = db_get_field("SELECT user_id FROM ?:orders WHERE user_id = ?i AND order_id = ?i", $auth['user_id'], $_REQUEST['order_id']);
		}

		fn_set_hook('is_order_allowed', $_REQUEST['order_id'], $allowed_id); 

		if (empty($allowed_id)) { // Access denied
			return array(CONTROLLER_STATUS_DENIED);
		}
		
		$order_info = fn_get_order_info($_REQUEST['order_id']);
		
		if (!empty($order_info['is_parent_order']) && $order_info['is_parent_order'] == 'Y') {
			$order_info['child_ids'] = implode(',', db_get_fields("SELECT order_id FROM ?:orders WHERE parent_order_id = ?i", $_REQUEST['order_id']));
		}
		if (!empty($order_info)) {
			$view->assign('order_info', $order_info);
		}
		
		/* To send the order notification to customer change by chandan sharma*/
		Registry::get('view_mail')->assign('order_info', $order_info);
		Registry::get('view_mail')->assign('order_status', fn_get_status_data($order_info['status'], STATUSES_ORDER, $order_info['order_id'], $order_info['lang_code']));
		Registry::get('view_mail')->assign('payment_method', fn_get_payment_data((!empty($order_info['payment_method']['payment_id']) ? $order_info['payment_method']['payment_id'] : 0), $order_info['order_id'], $order_info['lang_code']));
		Registry::get('view_mail')->assign('status_settings', $order_statuses[$order_info['status']]);
		Registry::get('view_mail')->assign('profile_fields', fn_get_profile_fields('I', '', $order_info['lang_code']));
		
		$company_id = $order_info['company_id'];
		Registry::get('view_mail')->assign('manifest', fn_get_manifest('customer', $order_info['lang_code'], $company_id));
		fn_send_mail($order_info['email'], Registry::get('settings.Company.company_orders_department'), 'orders/order_notification_subj.tpl', 'orders/order_notification.tpl', '', $order_info['lang_code']);
		$status_from = '';
		$status_to = $order_info['status'];
		$edp_data = fn_generate_ekeys_for_edp(array('status_from' => $status_from, 'status_to' => $status_to), $order_info);
				
		if (!empty($edp_data)) {
				Registry::get('view_mail')->assign('edp_data', $edp_data);
				fn_send_mail($order_info['email'], Registry::get('settings.Company.company_orders_department'), 'orders/edp_access_subj.tpl', 'orders/edp_access.tpl', '', $order_info['lang_code']);
		}
		/* To send the order notification to customer change by chandan sharma*/
	}
	fn_add_breadcrumb(fn_get_lang_var('landing_header'));
	
} 
?>