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
// $Id: languages.php 12865 2011-07-05 06:57:22Z 2tl $
//

if ( !defined('AREA') )	{ die('Access denied');	}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	fn_trusted_vars("lang_data", "new_lang_data");

	//
	// Edit language variables
	//
	if ($mode == 'update_variables') {
		if (is_array($_REQUEST['lang_data'])) {
			$lang_data = $_REQUEST['lang_data'];
			$error_flag = false;
			$lang_code = DESCR_SL;
			
			fn_set_hook('update_lang_values', $lang_data, $lang_code, $error_flag);
			
			foreach ($lang_data as $k => $v) {
				if (!empty($v['name'])) {
					preg_match("/(^[a-zA-z0-9][a-zA-Z0-9_]*)/", $v['name'], $matches);
					if (fn_strlen($matches[0]) == fn_strlen($v['name'])) {
						$v['lang_code'] = $lang_code;
						db_query("REPLACE INTO ?:language_values ?e", $v);
					} elseif (!$error_flag) {
						fn_set_notification('E', fn_get_lang_var('warning'), fn_get_lang_var('warning_lanvar_incorrect_name'));
						$error_flag = true;
					}
				}
			}
		}
	}

	//
	// Edit language variables
	//
	if ($mode == 'delete_variables') {
	
		fn_set_hook('delete_language_variables', $_REQUEST['names']);
		
		if (!empty($_REQUEST['names'])) {
			db_query("DELETE FROM ?:language_values WHERE name IN (?a)", $_REQUEST['names']);
		}
	}

	//
	// Add new language variable
	// NOTE: variable will be added for all defined languages
	//
	if ($mode == 'add_variables') {
		$error_flag = false;

		if (!empty($_REQUEST['new_lang_data'])) {
			foreach ((array)Registry::get('languages') as $lc => $_v) {
				$lang_data = $_REQUEST['new_lang_data'];
				$error_flag = false;
				$lang_code = $lc;
				$params = array('clear' => false);
				
				fn_set_hook('update_lang_values', $lang_data, $lang_code, $error_flag, $params);
				
				foreach ($lang_data as $k1 => $v1) {
					if (!empty($v1['name'])) {
						preg_match("/(^[a-zA-z0-9][a-zA-Z0-9_]*)/", $v1['name'], $matches);
						if (strlen($matches[0]) == strlen($v1['name'])) {
							$v1['lang_code'] = $lc;
							db_query("REPLACE INTO ?:language_values ?e", $v1);
						} elseif ($error_flag == false) {
							fn_set_notification('E', fn_get_lang_var('warning'), fn_get_lang_var('warning_lanvar_incorrect_name'));
							$error_flag = true;
						}
					}
				}
			}
		}
	}
	//
	// Update languages
	//
	if ($mode == 'update_languages') {

		if (is_array($_REQUEST['update_language'])) {
			foreach ($_REQUEST['update_language'] as $__lang_code => $__data) {
				db_query("UPDATE ?:languages SET ?u WHERE lang_code = ?s", $__data, $__lang_code);
			}
			fn_check_languages_availability();
		}
	}

	//
	// Delete languages
	//
	if ($mode == 'delete_languages') {

		if (!empty($_REQUEST['lang_codes'])) {
			fn_delete_languages($_REQUEST['lang_codes']);
		}
	}

	//
	// Add languages
	//
	if ($mode == 'add_languages') {
		$new_language = $_REQUEST['new_language'];
		if (!empty($new_language['lang_code']) && !empty($new_language['name'])) {
			$is_exists = db_get_field("SELECT COUNT(*) FROM ?:languages WHERE lang_code = ?s", $new_language['lang_code']);
			if (empty($is_exists)) {
				db_query("INSERT INTO ?:languages ?e", $new_language);
				// Adding new language descriptions for all objects

				$db_descr_tables = db_get_fields("SHOW TABLES LIKE '?:%_descriptions'");
				$db_descr_tables[] = 'language_values';
				$db_descr_tables[] = 'product_features_values';

				foreach ($db_descr_tables as $table) {
					$table = str_replace(TABLE_PREFIX, '', $table);
					$fields_select = fn_get_table_fields($table, array(), true);
					$fields_insert = fn_get_table_fields($table, array(), true);
					$k = array_search('`lang_code`', $fields_select);
					$fields_select[$k] = db_quote("?s as lang_code", $new_language['lang_code']);
					db_query("REPLACE INTO ?:$table (" . implode(', ', $fields_insert) . ") SELECT " . implode(', ', $fields_select) . " FROM ?:$table WHERE lang_code = 'EN'");
				}
			} else {
				fn_set_notification('E', fn_get_lang_var('error'), str_replace('[code]', $new_language['lang_code'], fn_get_lang_var('error_lang_code_exists')));
			}
		}
	}
	$q = (empty($_REQUEST['q'])) ? '' : $_REQUEST['q'];

	return array(CONTROLLER_STATUS_OK, "languages.manage?q=$q");
}

//
// Get language variables values
//
if ($mode == 'manage') {

	$params = $_REQUEST;
	
	$fields = array(
		'lang.value' => true,
		'lang.name' => true,
	);
	
	$tables = array(
		'?:language_values lang',
	);
	
	$left_join = array();
	
	$condition = array();
	
	if (isset($_REQUEST['q']) && fn_string_no_empty($_REQUEST['q'])) {
		$condition[] = db_quote('lang.lang_code = ?s', DESCR_SL);
		$condition[] = db_quote('(lang.name LIKE ?l OR lang.value LIKE ?l)', '%' . trim($_REQUEST['q']) . '%', '%' . trim($_REQUEST['q']) . '%');
	} else {
		$condition[] = db_quote('lang.lang_code = ?s', DESCR_SL);
	}

	fn_set_hook('get_lang_var', $fields, $tables, $left_join, $condition, $params);

	$joins = !empty($left_join) ? ' LEFT JOIN ' . implode(', ', $left_join) : '';

	$page = empty($_REQUEST['page']) ? 1 : $_REQUEST['page'];
	
	$lang_data_count = db_get_field('SELECT COUNT(*) FROM ' . implode(', ', $tables) . $joins . ' WHERE ' . implode(' AND ', $condition));
	$limit = fn_paginate($page, $lang_data_count, Registry::get('settings.Appearance.admin_elements_per_page'));
	$lang_data = db_get_array('SELECT ' . implode(', ', array_keys($fields)) . ' FROM ' . implode(', ', $tables) . $joins . ' WHERE ' . implode(' AND ', $condition) . ' ORDER BY lang.name ' . $limit);
	Registry::set('navigation.tabs', array (
		'translations' => array (
			'title' => fn_get_lang_var('translations'),
			'js' => true
		),
		'languages' => array (
			'title' => fn_get_lang_var('languages'),
			'js' => true
		),
	));
	$view->assign('lang_data', $lang_data);
	$view->assign('langs', Registry::get('languages'));

} elseif ($mode == 'delete_variable') {
	
	fn_set_hook('delete_language_variable', $_REQUEST['name']);
	
	if (!empty($_REQUEST['name'])) {
		db_query("DELETE FROM ?:language_values WHERE name = ?s", $_REQUEST['name']);
	}

	$page = (!empty($_REQUEST['page_id'])) ? '&page=' . $_REQUEST['page_id'] : '';
	$redirect_url = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'languages.manage';
	
	return array(CONTROLLER_STATUS_REDIRECT, $redirect_url . $page);

//
// Delete languages
//
} elseif ($mode == 'delete_language') {

	if (!empty($_REQUEST['lang_code'])) {
		fn_delete_languages($_REQUEST['lang_code']);
	}

	return array(CONTROLLER_STATUS_REDIRECT, "languages.manage?selected_section=languages");
}

function fn_delete_languages($lang_codes)
{
	$db_descr_tables = db_get_fields("SHOW TABLES LIKE '%_descriptions'");
	$db_descr_tables[] = '?:language_values';
	$db_descr_tables[] = '?:product_features_values';

	foreach ((array)$lang_codes as $v) {
		db_query("DELETE FROM ?:languages WHERE lang_code = ?s", $v);
			db_query("DELETE FROM ?:localization_elements WHERE element_type = 'L' AND element = ?s", $v);
			foreach ($db_descr_tables as $table) {
			db_query("DELETE FROM $table WHERE lang_code = ?s", $v);
		}
	}
	fn_check_languages_availability();
}

function fn_check_languages_availability()
{
	$avail = db_get_field("SELECT COUNT(*) FROM ?:languages WHERE status = 'A'");
	if (empty($avail)) {
		db_query("UPDATE ?:languages SET status = 'A' WHERE lang_code = 'EN'");
	}

	$first_avail_code = db_get_field("SELECT lang_code FROM ?:languages WHERE status = 'A' LIMIT 1");

	$is_customer_lang_avail = db_get_field("SELECT COUNT(*) FROM ?:languages WHERE lang_code = ?s AND status = 'A'", Registry::get('settings.Appearance.customer_default_language'));

	// Set default language for customer zone
	if (empty($is_customer_lang_avail)) {
		fn_set_setting_value('customer_default_language', $first_avail_code, 'Appearance');
	}

	$is_admin_lang_avail = db_get_field("SELECT COUNT(*) FROM ?:languages WHERE lang_code = ?s AND status = 'A'", Registry::get('settings.Appearance.admin_default_language'));
	// Set default language for admin zone
	if (empty($is_admin_lang_avail)) {
		fn_set_setting_value('admin_default_language', $first_avail_code, 'Appearance');
	}

	if (empty($is_customer_lang_avail) || empty($is_admin_lang_avail)) {
		$href = fn_url('settings.manage?section_id=Appearance');
		fn_set_notification('W', fn_get_lang_var('warning'), str_replace('[link]', "<a href='$href'>Settings::Appearance</a>", fn_get_lang_var('warning_default_language_disabled')));
	}
}

?>