<?php
if ( !defined('AREA') )	{ die('Access denied');	}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if ($mode == 'update') {
		$company_id = $_REQUEST['company_id'];
		if(isset($_REQUEST['file_trm_icon'])) {
			$icon = fn_filter_uploaded_data('trm_icon');
			$icon = $icon[0];
			$short_name = "company_icons/{$company_id}/{$icon['name']}";
			$filename = DIR_IMAGES . $short_name;
			fn_mkdir(dirname($filename));
			if(fn_get_image_size($icon['path'])) {
				if(fn_copy($icon['path'], $filename)) {
					$http_image_path = Registry::get('config.http_images_path') . $short_name;
					db_query("UPDATE ?:companies SET icon_url=?s WHERE company_id=?i", $http_image_path, $company_id);
				}
			}
		}
		$vendors_fv = array();
		if(is_array($_REQUEST['sdeep_features'])) {
			foreach($_REQUEST['sdeep_features'] as $k => $v) {
				$vendors_fv[] = $k;
			}
		}
		db_query("UPDATE ?:companies SET sdeep_features=?s WHERE company_id=?i", @serialize($vendors_fv), $company_id);
	}
}
?>
