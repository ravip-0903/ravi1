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
// $Id: addons.pre.php 12865 2011-07-05 06:57:22Z 2tl $
//

if ( !defined('AREA') )	{ die('Access denied');	}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if ($mode == 'tw_connect') {
		if (Registry::get('addons.twigmo.access_id')) {
			fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('access_denied'));
			return array(CONTROLLER_STATUS_REDIRECT, 'addons.manage');
		}

		$tw_register = $_REQUEST['tw_register'];

		$addon_options = fn_get_twigmo_options();

		$connect_options = $tw_register;
		unset($connect_options['password1']);
		unset($connect_options['password2']);

		$addon_options['connect'] = serialize($connect_options);

		fn_update_twigmo_options($addon_options);

		$twigmo =& fn_init_twigmo();

		$user_data = db_get_row("SELECT firstname, lastname, password FROM ?:users WHERE user_id = ?i", $auth['user_id']);

		/*
		* Check fields
		*/
		if (empty($tw_register['use_password']) && $tw_register['password1'] != $tw_register['password2']) {
			fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('error_passwords_dont_match'));
			return array(CONTROLLER_STATUS_REDIRECT, 'addons.manage');
		}

		$prefix = !empty($tw_register['disable_https']) ? 'http' : 'https';
		$params = array (
			'dispatch' => 'connect_store.connect',
			'email' => $tw_register['email'],
			'store_name' => $tw_register['store_name'],
			'admin_url' => fn_url('twigmo.post', 'A', $prefix),
			'customer_url' => fn_url('twigmo.post', 'C', $prefix),
			'firstname' => $user_data['firstname'],
			'lastname' => $user_data['lastname'],
			'password' => !empty($tw_register['use_password']) ? $user_data['password'] : md5($tw_register['password1']),
			'addon_version' => Registry::get('addons.twigmo.version'),
		);

		if (!empty($tw_register['checked_email']) && $tw_register['checked_email'] == $tw_register['email'] && !empty($tw_register['store_id'])) {
			// the request with the selected email has been processed 
			// send the selected store_id in a param
			$params['store_id'] = $tw_register['store_id'];
		}

		if ($twigmo->sendRequest($params, 'GET') && !empty($twigmo->response_data['access_id'])) {

			// store connected update connection data
			$addon_options['access_id'] = $twigmo->response_data['access_id'];
			$addon_options['secret_access_key'] = $twigmo->response_data['secret_access_key'];

			fn_update_twigmo_options($addon_options);

			Registry::set('addons.twigmo.access_id', $twigmo->response_data['access_id']);
			Registry::set('addons.twigmo.secret_access_key', $twigmo->response_data['secret_access_key']);
			$view->assign('addons', Registry::get('addons'));

			fn_set_notification('N', fn_get_lang_var('notice'), fn_get_lang_var('text_twg_store_connected'));
			//return array(CONTROLLER_STATUS_REDIRECT, 'addons.manage');

		} else {

			if (!empty($twigmo->errors)) {
				fn_set_notification('E', fn_get_lang_var('error'), implode('<br />', $twigmo->errors)); 
			} else {
				fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('error_twg_cannot_connect_store'));
			}

		}
		
		if (!empty($twigmo->response_data['store'])) {
			// a few stores has been found show a choice
			// if stores are found show choice
			$data = fn_parse_api_list($twigmo->response_data, 'stores');
			$stores = ApiData::getObjects($data);
				
			foreach ($stores as $k => $v) {
				$parts = parse_url($v['admin_url']);
				$url = $parts['scheme'] . '://' . $parts['host'] . $parts['path'];
				$stores[$k]['title'] = $stores[$k]['domain'] . ' (' . $url . ')';

				if (!empty($tw_register['store_id'])) {
					if ($tw_register['store_id'] == $v['store_id']) {
						$stores[$k]['selected'] = true;
					}

				} elseif ($v['domain'] == $tw_register['store_name']) {
					$stores[$k]['selected'] = true;

				}
			}
			
			if (!empty($twigmo->meta['is_allowed_add'])) {
				$stores[] = array(
					'store_id' => 'new',
					'title' => fn_get_lang_var('new_connection')
				);

			}

			// save current email as checked
			$tw_register['checked_email'] = $tw_register['email'];
			$view->assign('stores', $stores);
		}

		if (defined('AJAX_REQUEST')) {
			$tw_register['version'] = Registry::get('addons.twigmo.version');
			$view->assign('tw_register', $tw_register);
			$view->display('addons/twigmo/settings/connect.tpl');
			exit;

		} else {
			return array(CONTROLLER_STATUS_REDIRECT, 'addons.manage');

		}
	}

	if ($mode == 'update' && $_REQUEST['addon'] == 'twigmo') {

		$twigmo =& fn_init_twigmo();

		$tw_register = $_REQUEST['tw_register'];

		if (empty($tw_register['use_password']) && $tw_register['password1'] != $tw_register['password2']) {
			fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('error_passwords_dont_match'));
			return array(CONTROLLER_STATUS_REDIRECT, 'addons.manage');
		}

		$prefix = !empty($tw_register['disable_https']) ? 'http' : 'https';

		if (!empty($tw_register['use_password'])) {
			$password = db_get_field("SELECT password FROM ?:users WHERE user_id = ?i", $auth['user_id']);
		} else {
			$password = !empty($tw_register['password1']) ? md5($tw_register['password1']) : '';
		}

		$params = array (
			'dispatch' => 'connect_store.update',
			'email' => $tw_register['email'],
			'store_name' => $tw_register['store_name'],
			'admin_url' => fn_url('twigmo.post', 'A', $prefix),
			'customer_url' => fn_url('twigmo.post', 'C', $prefix),
			'password' => $password,
			'addon_version' => Registry::get('addons.twigmo.version'),
		);

		if (!$twigmo->sendRequest($params, 'GET')) {
			if (!empty($twigmo->errors)) {
				fn_set_notification('E', fn_get_lang_var('error'), implode('<br />', $twigmo->errors));
			} else {
				fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('error_twg_cannot_update_store'));
			}
		}

		// Update options
		$addon_options = fn_get_twigmo_options();
		$_REQUEST['addon_data']['options'] = $addon_options;
		if (!empty($_REQUEST['tw_register'])) {
			if (!empty($_REQUEST['tw_register']['password1'])) {
				unset($_REQUEST['tw_register']['password1']);
				unset($_REQUEST['tw_register']['password2']);
			}
			$tw_register = serialize($_REQUEST['tw_register']);
		} else {
			$tw_register = !empty($addon_options['connect']) ? $addon_options['connect'] : '';
		}

		$_REQUEST['addon_data']['options']['connect'] = $tw_register;
	}

} elseif ($mode == 'update') {

	if ($_REQUEST['addon'] == 'twigmo') {
		$addon_options = fn_get_twigmo_options();
		// define re set 'Use my password option'
		$tw_register = !empty($addon_options['connect']) ? unserialize($addon_options['connect']) : array('use_my_password' => 'Y');
		$tw_register['version'] = Registry::get('addons.twigmo.version');
		$view->assign('tw_register', $tw_register);
	}

}

function fn_get_twigmo_options()
{
	$addon_options = db_get_field("SELECT options FROM ?:addons WHERE addon = ?s", 'twigmo');
	return !empty($addon_options) ? fn_parse_addon_options($addon_options) : array();
}

function fn_update_twigmo_options($options)
{
	db_query("UPDATE ?:addons SET options = ?s WHERE addon = ?s", serialize($options), 'twigmo');
	return true;
}

?>