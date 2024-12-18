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
// $Id: design_mode.php 12865 2011-07-05 06:57:22Z 2tl $
//

if ( !defined('AREA') ) { die('Access denied'); }

if ( !defined('TRANSLATION_MODE') && !defined('CUSTOMIZATION_MODE')) { 
	die('Access denied'); 
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if ($mode == 'update_design_mode') {
		if (!empty($_REQUEST['design_mode'])) {
			$_restore = $_REQUEST['design_mode'] == 'translation_mode' ? 'customization_mode' : 'translation_mode';
			fn_set_setting_value($_REQUEST['design_mode'], 'Y');
			fn_set_setting_value($_restore, 'N');
			fn_rm(DIR_COMPILED . 'customer');
			fn_rm(DIR_COMPILED . 'admin');
		}

		return array(CONTROLLER_STATUS_OK, $_REQUEST['current_url']);
	}
}

if ($mode == 'update_langvar') {
	fn_trusted_vars('langvar_value');
	$name = empty($_REQUEST['langvar_name']) ? '' : strtolower($_REQUEST['langvar_name']);
	$table = 'language_values';
	$update_fields = array();
	$condition = array();
	
	if (strpos($name, '-') !== false) {
		$params = explode('-', $name);
		for ($i = 2; $i < count($params); $i += 2) {
			$condition[$params[$i]] = $params[$i+1];
		}
		$condition['lang_code'] = $_REQUEST['lang_code'];
		$update_fields[] = db_quote($params[1] . ' = ?s', $_REQUEST['langvar_value']);
	} else {
		$update_fields[] = db_quote('value = ?s', $_REQUEST['langvar_value']);
		$condition['name'] = $_REQUEST['langvar_name'];
		$condition['lang_code'] = $_REQUEST['lang_code'];
	}
	
	fn_set_hook('translation_mode_update_langvar', $table, $update_fields, $condition);
	
	db_query('UPDATE ?:' . $table . ' SET ' . implode(', ', $update_fields) . ' WHERE ?w', $condition);
	
	exit;

} elseif ($mode == 'get_langvar') {
	$name = empty($_REQUEST['langvar_name']) ? '' : strtolower($_REQUEST['langvar_name']);
	if (strpos($name, '-') !== false) {
		$params = explode('-', $name);
		$where = array();
		for ($i = 2; $i < count($params); $i += 2) {
			$where[$params[$i]] = $params[$i+1];
		}
		$where['lang_code'] = $_REQUEST['lang_code'];
		$ajax->assign('langvar_value', db_get_field("SELECT $params[1] FROM ?:$params[0] WHERE ?w", $where));
	} else {
		$ajax->assign('langvar_value', fn_get_lang_var($name, $_REQUEST['lang_code']));
	}
	exit;

} elseif ($mode == 'get_content') {
	$ext = strtolower(fn_get_file_ext($_REQUEST['file']));

	if ($ext == 'tpl') {
		
		$skin_path = DIR_SKINS . Registry::get('config.skin_name');
		$area = 'customer';
		
		fn_set_hook('get_skin_path', $area, $skin_path);
		
		$ajax->assign('content', fn_get_contents($_REQUEST['file'], $skin_path . '/' . AREA_NAME . '/'));
	}
	exit;

} elseif ($mode == 'save_template') {
	fn_trusted_vars('content');
	if (defined('DEVELOPMENT')) {
		exit;
	}
	$ext = strtolower(fn_get_file_ext($_REQUEST['file']));
	if ($ext == 'tpl') {
		$skin_path = DIR_SKINS . Registry::get('config.skin_name');
		$area = 'customer';
		
		fn_set_hook('get_skin_path', $area, $skin_path);
		
		fn_put_contents($_REQUEST['file'], $_REQUEST['content'], $skin_path . '/' . AREA_NAME . '/');

		$msg = fn_get_lang_var('text_file_saved');
		fn_set_notification('N', fn_get_lang_var('notice'), str_replace("[file]", basename($_REQUEST['file']), $msg));
	}
	return array(CONTROLLER_STATUS_OK, $_REQUEST['current_url']);

} elseif ($mode == 'restore_template') {
	$copied = false;
	
	$skin_path = DIR_SKINS . Registry::get('config.skin_name');
	$area = 'customer';
	
	fn_set_hook('get_skin_path', $area, $skin_path);
	
	$full_path = $skin_path . '/' . AREA_NAME . '/' . $_REQUEST['file'];
	if (fn_check_path($full_path)) {
		$c_name = fn_normalize_path($full_path);

		$r_name = str_replace('/skins/', '/var/skins_repository/', $c_name);
		if (is_file($r_name)) {
			$copied = fn_copy($r_name, $c_name);
		}
		
		if ($copied) {
			$msg = fn_get_lang_var("text_file_restored");
			$type = 'N';
			$title = fn_get_lang_var('notice');
		} else {
			$msg = n_get_lang_var("text_cannot_restore_file");
			$type = 'E';
			$title = fn_get_lang_var('error');
		}

		fn_set_notification($type, $title, str_replace("[file]", basename($_REQUEST['file']), $msg));
		if ($copied) {
			return array(CONTROLLER_STATUS_OK, $_REQUEST['current_url']);
		}
	}
	exit;

}
?>