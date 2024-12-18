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
// $Id: fn.mse_functions.php 12865 2011-07-05 06:57:22Z 2tl $
//

function fn_mse_get_skin_path($zone, $skin_path)
{
	if ($zone == 'admin') {
		return true;
	}
	
	if (defined('COMPANY_ID')) {
		$skin_name = (!in_array(basename($skin_path), array('customer', 'admin', 'skins'))) ? basename($skin_path) : (basename($skin_path) == 'skins' ? '' : Registry::get('settings.skin_name_customer'));
		$last_char = empty($skin_name) ? '' : (substr($skin_path, -1) == '/' ? '/' : '');
		
		if ($skin_path != basename($skin_path)) {
			$skin_path = DIR_ROOT . '/vendors/' . COMPANY_ID . '/skins' . (empty($skin_name) ? '' : '/' . $skin_name) . $last_char;
		} else {
			$skin_path = 'vendors/' . COMPANY_ID . '/skins' . (empty($skin_name) ? '' : '/' . $skin_name) . $last_char;
		}
	}
	
	return $skin_path;
}

function fn_mse_pre_get_blocks($object_id, $params, $fields, $condition, $lang_code)
{
	$condition .= ' AND ?:blocks.company_id ' . (defined('COMPANY_ID') ? db_quote(' = ?i', COMPANY_ID) : ' IS NULL');
}

function fn_mse_get_available_group($fields, $condition)
{
	$condition .= ' AND ?:blocks.company_id ' . (defined('COMPANY_ID') ? db_quote(' = ?i', COMPANY_ID) : ' IS NULL');
}

/**
 * Hook updates the company settings table (corresponding to selected COMPANY_ID)
 *
 * @param string $option_name Name of setting
 * @param string $value New value of setting
 * @param string $section_id Name of settings' section
 * @param string $subsection_id Name of settings' subsection
 * @param boolean $global_update Update or not ?:settings table (key for MSE)
 * @param string $condition Condition of query
 */
function fn_mse_set_setting_value($option_name, $value, $section_id, $subsection_id, $global_update, $condition)
{
	if (defined('COMPANY_ID')) {
		$option_id = db_get_field('SELECT option_id FROM ?:settings' . $condition);
		if (!empty($option_id)) {
			db_query('UPDATE ?:company_settings SET ' . (is_array($value) ? '?u' : 'value = ?s') . ' WHERE option_id = ?i AND company_id = ?i', $value, $option_id, COMPANY_ID);
		}
		
		// no need to update ?:settings table
		$global_update = false;
	}
}

function fn_mse_get_static_data($params, $fields, $condition, $sorting, $lang_code)
{
	$condition .= ' AND ?:static_data.company_id ' . (defined('COMPANY_ID') ? db_quote(' = ?i', COMPANY_ID) : ' IS NULL');
}

function fn_mse_add_block($block, $_data, $selected_location, $current_location)
{
	if (defined('COMPANY_ID')) {
		$_data['company_id'] = COMPANY_ID;
	}
}

function fn_mse_init_selected_company($params, $var_path)
{
	if (!defined('DEVELOPMENT') && defined('SELECTED_COMPANY_ID') && SELECTED_COMPANY_ID != 'all') {
		$var_path = '/vendors/' . SELECTED_COMPANY_ID;
	}
}

function fn_mse_update_static_data($data, $param_id, $condition, $section, $lang_code)
{
	if (defined('COMPANY_ID')) {
		$data['company_id'] = COMPANY_ID;
		$condition .= db_quote(' AND company_id = ?i', COMPANY_ID);
	}
}

function fn_mse_get_lang_var($fields, $tables, $left_join, $condition, $params = array())
{
	if (defined('SELECTED_COMPANY_ID') && SELECTED_COMPANY_ID != 'all') {
		$left_join[] = db_quote("?:mse_language_values ON ?:mse_language_values.name = lang.name AND company_id = ?i AND ?:mse_language_values.lang_code = lang.lang_code", SELECTED_COMPANY_ID);
		
		unset($fields['lang.value']);
		$fields['IF(?:mse_language_values.value IS NULL, lang.value, ?:mse_language_values.value) as value'] = true;
	}
}

function fn_mse_translation_mode_update_langvar($table, $update_fields, $condition)
{
	if (defined('COMPANY_ID')) {
		$table = 'mse_language_values';
		
		$is_exists = db_get_field('SELECT COUNT(*) FROM ?:mse_language_values WHERE ?w', $condition);
		if (!$is_exists) {
			$_data = $condition;
			
			foreach ($update_fields as $field) {
				list($_field, $_value) = explode('=', $field);
				$_data[trim($_field)] = substr($_value, 2, fn_strlen($_value) - 3);
			}
			
			$_data['company_id'] = COMPANY_ID;
			
			db_query('INSERT INTO ?:mse_language_values ?e', $_data);
		}
	}
}

function fn_mse_update_lang_values($lang_data, $lang_code, $error_flag, $params = array())
{
	if (defined('COMPANY_ID') && COMPANY_ID != 'all') {
		foreach ($lang_data as $k => $v) {
			if (!empty($v['name'])) {
				preg_match("/(^[a-zA-z0-9][a-zA-Z0-9_]*)/", $v['name'], $matches);
				if (fn_strlen($matches[0]) == fn_strlen($v['name'])) {
					$v['lang_code'] = $lang_code;
					$v['company_id'] = COMPANY_ID;
					db_query("REPLACE INTO ?:mse_language_values ?e", $v);
				} elseif (!$error_flag) {
					fn_set_notification('E', fn_get_lang_var('warning'), fn_get_lang_var('warning_lanvar_incorrect_name'));
					$error_flag = true;
				}
			}
			
			if (!isset($params['clear']) || $params['clear']) {
				unset($lang_data[$k]);
			}
		}
	} else {
		$overwrite = array();
		
		foreach ($lang_data as $k => $v) {
			if (!empty($v['name']) && !empty($v['overwrite']) && $v['overwrite'] == 'Y') {
				$overwrite[] = $v['name'];
			}
		}
		
		db_query('DELETE FROM ?:mse_language_values WHERE name IN (?a) AND lang_code = ?s', $overwrite, $lang_code);
	}
}

function fn_mse_delete_language_variable($name)
{
	if (!empty($name)) {
		if (defined('COMPANY_ID')) {
			db_query('DELETE FROM ?:mse_language_values WHERE name = ?s AND company_id = ?i AND lang_code = ?s', $name, COMPANY_ID, DESCR_SL);
		} else {
			db_query("DELETE FROM ?:language_values WHERE name = ?s", $name);
			db_query("DELETE FROM ?:mse_language_values WHERE name = ?s", $name);
		}
	}
	
	$name = '';
}

function fn_mse_delete_language_variables($names)
{
	if (!empty($names)) {
		if (defined('COMPANY_ID')) {
			db_query("DELETE FROM ?:mse_language_values WHERE name IN (?a) AND company_id = ?i AND lang_code = ?s", $names, COMPANY_ID, DESCR_SL);
		} else {
			db_query("DELETE FROM ?:language_values WHERE name IN (?a)", $names);
			db_query("DELETE FROM ?:mse_language_values WHERE name IN (?a)", $names);
		}
	}
	
	$names = '';
}

function fn_mse_sitemap_get_sections($section_fields, $section_tables, $section_left_join, $section_condition)
{
	$section_condition[] = 's.company_id ' . (defined('COMPANY_ID') ? db_quote(' = ?i', COMPANY_ID) : ' IS NULL'); 
}

function fn_mse_sitemap_get_links($links_fields, $links_tables, $links_left_join, $links_condition)
{
	$links_condition[] = 'company_id ' . (defined('COMPANY_ID') ? db_quote(' = ?i', COMPANY_ID) : ' IS NULL'); 
}

function fn_mse_sitemap_update_object($object, $object_id, $mode)
{
	if (defined('COMPANY_ID')) {
		$object['company_id'] = COMPANY_ID;
	}
}

function fn_mse_sitemap_delete_links($link_ids)
{
	if (defined('COMPANY_ID')) {
		// Check permissions to delete link objects
		$_ids = db_get_fields('SELECT link_id FROM ?:sitemap_links WHERE link_id IN (?n) AND company_id = ?i', $link_ids, COMPANY_ID);
		
		db_query("DELETE FROM ?:sitemap_links WHERE link_id IN (?n)", $_ids);
		db_query("DELETE FROM ?:common_descriptions WHERE object_holder = 'sitemap_links' AND object_id IN (?n)", $_ids);
		
		$link_ids = array();
	}
}

function fn_mse_sitemap_delete_sections($section_ids)
{
	if (defined('COMPANY_ID')) {
		// Check permissions to delete link objects
		$_ids = db_get_fields('SELECT section_id FROM ?:sitemap_sections WHERE section_id IN (?n) AND company_id = ?i', $section_ids, COMPANY_ID);
		
		db_query("DELETE FROM ?:sitemap_sections WHERE section_id IN (?n)", $_ids);
		db_query("DELETE FROM ?:common_descriptions WHERE object_holder = 'sitemap_sections' AND object_id IN (?n)", $_ids);

		$links = db_get_fields("SELECT link_id FROM ?:sitemap_links WHERE section_id IN (?n)", $_ids);
		if (!empty($links)) {
			db_query("DELETE FROM ?:sitemap_links WHERE section_id IN (?n)", $_ids);
			db_query("DELETE FROM ?:common_descriptions WHERE object_holder = 'sitemap_links' AND object_id IN (?n)", $links);
		}
		
		$section_ids = array();
	}
}

?>