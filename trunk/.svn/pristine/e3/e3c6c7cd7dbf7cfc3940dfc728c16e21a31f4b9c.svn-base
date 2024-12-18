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
// $Id: actions.php 12870 2011-07-05 08:33:31Z alexions $
//
if ( !defined('AREA') ) { die('Access denied'); }

/**
 * Check if secure connection is available
 */
function fn_settings_actions_general_secure_auth(&$new_value, $old_value)
{
	if ($new_value == 'Y') {
		$content = fn_https_request('GET', Registry::get('config.https_location') . '/' . INDEX_SCRIPT . '?check_https=Y');
		if (empty($content[1]) || $content[1] != 'OK') {
			// Disable https
			db_query("UPDATE ?:settings SET value = 'N' WHERE section_id = 'General' AND option_name LIKE 'secure\_%'");
			$new_value = 'N';

			fn_set_notification('W', fn_get_lang_var('warning'), fn_get_lang_var('warning_https_disabled'));
		}
	}
}

/**
 * Check if secure connection is available
 */
function fn_settings_actions_general_secure_checkout(&$new_value, $old_value) 
{
	return fn_settings_actions_general_secure_auth($new_value, $old_value);
}

/**
 * Check if secure connection is available
 */
function fn_settings_actions_general_secure_admin(&$new_value, $old_value)
{
	return fn_settings_actions_general_secure_auth($new_value, $old_value);
}

/**
 * Alter order initial ID
 */
function fn_settings_actions_general_order_start_id(&$new_value, $old_value)
{
	if (intval($new_value)) {
		db_query("ALTER TABLE ?:orders AUTO_INCREMENT = ?i", $new_value);
	}
}

/**
 * Enable/disable revisions objects
 */
function fn_settings_actions_general_active_revisions_objects(&$new_value, $old_value)
{
	$old = Registry::get('settings.General.active_revisions_objects');

	include_once(DIR_CORE . 'fn.revisions.php');
	fn_init_revisions();

	parse_str($new_value, $new);
	$revisions = Registry::get('revisions');

	$skip = array ();
	$show_notification = false;

	if ($revisions) {
		foreach ($old as $key => $rec) {
			if ($rec == 'N' && isset($new[$key])) {
				fn_create_revision_tables();
				fn_revisions_set_object_active($key);
				fn_echo(fn_get_lang_var('creating_revisions') . ' ' . fn_get_lang_var($revisions['objects'][$key]['title']));
				fn_revisions_delete_objects($key);
				fn_revisions_create_objects($key, true);
				fn_echo(' ' .fn_get_lang_var('done') . '<br>');
				$show_notification = true;
			} elseif ($rec == 'Y' && !isset($new[$key])) {
				fn_echo(fn_get_lang_var('deleting_revisions') . ' ' . fn_get_lang_var($revisions['objects'][$key]['title']));
				fn_revisions_delete_objects($key);
				fn_echo(' ' .fn_get_lang_var('done') . '<br>');
			}

			$skip[] = $key;
		}

		if (!empty($new)) {
			foreach ($new as $object => $_v) {
				if (!in_array($object, $skip)) {
					fn_create_revision_tables();
					fn_revisions_set_object_active($object);
					fn_echo(fn_get_lang_var('creating_revisions') . ' ' . fn_get_lang_var($revisions['objects'][$object]['title']));
					fn_revisions_delete_objects($object);
					fn_revisions_create_objects($object, true);
					fn_echo(' ' .fn_get_lang_var('done') . '<br>');
					$show_notification = true;
				}
			}
		}
		if ($show_notification) {
			$msg = fn_get_lang_var('warning_create_workflow');
			$msg = str_replace('[link]', fn_url("revisions_workflow.manage", 'A'), $msg);
			fn_set_notification('E', fn_get_lang_var('warning'), $msg, 'S');
		}
	}
}

/**
 * Enable/disable Canada Post
 */
function fn_settings_actions_shippings_can_enabled(&$new_value, $old_value)
{
	$currencies = Registry::get('currencies');
	if ($new_value == 'Y' && empty($currencies['CAD'])) {
		fn_set_notification('E', fn_get_lang_var('warning'), fn_get_lang_var('canada_post_activation_error'), 'S');
		$new_value = 'N';
	}
}

function fn_settings_actions_upgrade_center_license_number($new_value, $old_value)
{
	if (!empty($new_value)) {
		$settings = fn_get_settings('Upgrade_center');
		
		$token = fn_crc32(microtime());
		fn_set_setting_value('hd_request_code', $token);
		
		$request = array(
			'Request@action=check_license' => array(
				'token' => $token,
				'license_number' => $settings['license_number'],
				'ver' => PRODUCT_VERSION,
				'store_uri' => fn_url('', 'C', 'http'),
				'secure_store_uri' => fn_url('', 'C', 'https'),
			),
		);

		$request = '<?xml version="1.0" encoding="UTF-8"?>' . fn_array_to_xml($request);
		// Changed by Sudhir dt 20th May 2013 to stop request bigin here
		if(Registry::get('config.check_license')) {
			$data = fn_get_contents($settings['updates_server'] . '/index.php?dispatch=product_updates.check_available&request=' . urlencode($request));
		} else {
			$data = '<?xml version="1.0" encoding="UTF-8"?><Response><License>ACTIVE</License><Updates>AVAILABLE</Updates><Messages></Messages></Response>';
		}
		// Changed by Sudhir dt 20th May 2013 to stop request end here
		if (!empty($data)) {
			// Check if we can parse server response
			if (strpos($data, '<?xml') !== false) {
				$data = simplexml_load_string($data);
				$updates = (string) $data->Updates;
				fn_helpdesk_process_messages($data->Messages);
				$data = (string) $data->License;
			}
		}
		
		$_SESSION['last_status'] = base64_encode($_SESSION['auth']['user_id'] . ':' . $data);
	}
}

?>
