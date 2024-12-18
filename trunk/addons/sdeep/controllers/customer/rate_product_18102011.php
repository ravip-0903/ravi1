<?php
if(!defined('AREA') ) { die('Access denied'); }
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if($mode == 'update') {
		if(is_numeric($_REQUEST['rating']) && is_numeric($_REQUEST['product_id'])) {
			// Check whether the product exists
			$product_id = db_get_field("SELECT product_id FROM ?products WHERE product_id=?i", $_REQUEST['product_id']);
			if($product_id) {
				$rating_info = db_get_field("SELECT sdeep_rating_info FROM ?products WHERE product_id=?i", $product_id);
				$rating_info = @unserialize($rating_info);
				if(!isset($rating_info['total_score'])) $rating_info['total_score'] = 0;
				if(!isset($rating_info['num_rates'])) $rating_info['num_rates'] = 0;
				$rating_info['total_score'] = $_REQUEST['rate'] + $rating_info['total_score'];
				$rating_info['num_rates'] = $rating_info['num_rates'] + 1;
				db_query("UPDATE ?:products SET sdeep_rating_info=?s WHERE product_id=?i", @serialize($rating_info), $_REQUEST['product_id']);
			} else return array(CONTROLLER_STATUS_NO_PAGE);
		} else return array(CONTROLLER_STATUS_NO_PAGE);
	}
	return array(CONTROLLER_STATUS_REDIRECT, $index_script);
} elseif($mode == 'manage') {
	//$view->assign('vendor_rating_params', fn_sdeep_get_vendor_rating_params());
}
?>
