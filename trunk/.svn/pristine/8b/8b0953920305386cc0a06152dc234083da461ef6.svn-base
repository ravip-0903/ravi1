<?php
if ( !defined('AREA') ) { die('Access denied'); }
function fn_sdeep_get_vendor_rating_params() {
	return db_get_array("SELECT * FROM ?:sdeep_rating_params ORDER BY rating_id");
}
function fn_sdeep_get_lang_icons() {
	return db_get_array("SELECT * FROM ?:sdeep_lang_icons");
}
function fn_sdeep_is_cod_payment($order_info) {
	if(Registry::get('addons.sdeep.is_alternate_cod_behaviour') == 'Y') {
		$cod_id = Registry::get('addons.sdeep.cod_payment_id');
		if($order_info['payment_id'] == $cod_id) {
			return true;
		}
	}
	return false;
}
function fn_sdeep_show_cod_warning($payment_id) {
	if(Registry::get('addons.sdeep.is_alternate_cod_behaviour') == 'Y') {
		$cod_id = Registry::get('addons.sdeep.cod_payment_id');
		if($payment_id == $cod_id) {
			$msg = Registry::get('addons.sdeep.warning_cod_selected');
			fn_set_notification('W', '', $msg, 'I');
		}
	}
}
function fn_sdeep_get_unreviewed_orders($auth) {
	if($auth['user_id']) {
		$db_orders = db_get_array("SELECT company_id, order_id FROM ?:orders WHERE user_id=?i AND rating_info=''", $auth['user_id']);
		$orders = array();
		foreach($db_orders as $order) {
			if($order['company_id']) {
				$order['company_name'] = fn_get_company_name($order['company_id']);
				$orders[$order['company_id']] = $order;
			}
		}
		return $orders;
	}
	return false;
}
function fn_sdeep_get_rating($company_id, $update = false) {
	if($company_id) {
		// TODO: adjust to dynamic rating params if required
		$orders = db_get_array("SELECT rating_info FROM ?:orders WHERE company_id=?i", $company_id);
		if(is_array($orders) && !empty($orders)) {
			foreach($orders as $order) {
				$review = $order['rating_info'];
				if(is_string($review)) {
					$review = @unserialize($review);
					if(is_array($review)) {
						$num_of_reviews++;
						foreach($review as $k => $v) {
							if($k !== 'timestamp') {
								$total_mark += $v;
							}
						}
					}
				}
			}
			if($num_of_reviews) {
				$total_mark = round($total_mark / $num_of_reviews / 4, 2);
				if($update) {
					db_query("UPDATE ?:companies SET sdeep_rating=?d WHERE company_id=?i", $total_mark, $company_id);
				}
				return $total_mark;
			}
		}
	}
}
function fn_sdeep_get_terms($company_id) {
	return @unserialize(db_get_field("SELECT terms FROM ?:companies WHERE company_id=?i", $company_id));
}
function fn_sdeep_placement_routines($order_id, $order_info, $force_notification, $clear_cart, $action, $display_notification) {
	if($order_info['status'] == 'O') {
		Registry::get('view_mail')->assign('items', $order_info['items']);
		//fn_send_mail($order_info['email'], array('email' => $company['company_orders_department'], 'name' => $company['company_name']), 'addons/sdeep/rate_product_subj.tpl', 'addons/sdeep/rate_product_body.tpl', '', $order_info['lang_code']);
	}
}
function fn_sdeep_get_vendor_info($company_id) {
	return db_get_row("SELECT company_id, icon_url, company, is_trm FROM ?:companies WHERE company_id=?i", $company_id);
}
function fn_sdeep_is_trm($company_id) {
	$is_trm = db_get_field("SELECT is_trm FROM ?:companies WHERE company_id=?i", $company_id);
	if($is_trm === 'Y') {
		return true;
	}
	return false;
/*
	if(fn_sdeep_get_rating($company_id)) {
		$all_vendors = db_get_array("SELECT company_id FROM ?:companies ORDER BY sdeep_rating DESC");
		$percentage = Registry::get('addons.sdeep.trm_percentage');
		foreach($all_vendors as $k => $vendor) {
			if($vendor['company_id'] == $company_id) {
				$position = $k+1;
			}
		}
		return ($percentage >= 100/count($all_vendors) * $position);
	}
*/
}
function fn_sdeep_get_vendor_detailed_rating($company_id, $timestamp = 0) {
	if($company_id) {
		// TODO: adjust to dynamic rating params if required
		$orders = db_get_array("SELECT rating_info FROM ?:orders WHERE company_id=?i", $company_id);
		if(is_array($orders) && !empty($orders)) {
			$feedback['count'] = 0;
			foreach($orders as $order) {
				$review = $order['rating_info'];
				if(is_string($review)) {
					$review = @unserialize($review);
					if(is_array($review)) {
						if($review['timestamp'] > $timestamp) {
							$feedback['count']++;
							foreach($review as $k => $v) {
								if($k !== 'timestamp') {
									$total_mark += $v;
								}
							}
							if($total_mark <= 8) {
								$feedback['negative']++;
							}
							if($total_mark < 16 && $total_mark > 8) {
								$feedback['neutral']++;
							}
							if ($total_mark >= 16) {
								$feedback['positive']++;
							}
							$total_mark = 0;
						}
					}
				}
			}
			if(!$feedback['count']) {
				$feedback['negative'] = 0;
				$feedback['neutral'] = 0;
				$feedback['positive'] = 0;
			} else {
				$feedback['negative'] = round($feedback['negative'] / $feedback['count'] * 100);
				$feedback['neutral'] = round($feedback['neutral'] / $feedback['count'] * 100);
				$feedback['positive'] = round($feedback['positive'] / $feedback['count'] * 100);
			}
			return $feedback;
		}
	}
}
function fn_sdeep_get_vendor_detailed_rating_30days($company_id) {
	return fn_sdeep_get_vendor_detailed_rating($company_id, strtotime('-30 days'));
}
function fn_sdeep_get_vendor_detailed_rating_90days($company_id) {
	return fn_sdeep_get_vendor_detailed_rating($company_id, strtotime('-90 days'));
}
function fn_sdeep_get_vendor_detailed_rating_365days($company_id) {
	return fn_sdeep_get_vendor_detailed_rating($company_id, strtotime('-365 days'));
}
function fn_sdeep_get_product_features_variants() {
	$return = (db_get_array("SELECT * FROM ?:product_feature_variant_descriptions AS d LEFT JOIN ?:product_feature_variants AS v ON v.variant_id = d.variant_id WHERE v.feature_id=?i AND lang_code=?s", Registry::get('addons.sdeep.features_brands_id'), DESCR_SL));
	return $return;
}
function fn_sdeep_get_vendors_features_variants($company_id, $system_fv = false) {
	if(!$system_fv) {
		$system_fv = fn_sdeep_get_product_features_variants();
	}
	$vendors_fv = db_get_field("SELECT sdeep_features FROM ?:companies WHERE company_id=?i", $company_id);
	$vendors_fv = @unserialize($vendors_fv);
	if(is_array($vendors_fv)) {
		foreach($system_fv as &$sfv) {
			if(in_array($sfv['variant_id'], $vendors_fv)) {
				$sfv['exists'] = true;
			}
		}
	}
	return $system_fv;
}
function fn_sdeep_get_stars($rating_value)
{
	static $cache = array();
	if (!isset($cache[$rating_value])) {
		$cache[$rating_value] = array();
		$cache[$rating_value]['full'] = floor($rating_value);
		$cache[$rating_value]['part'] = $rating_value - $cache[$rating_value]['full'];
		$cache[$rating_value]['empty'] = 5 - $cache[$rating_value]['full'] - (($cache[$rating_value]['part'] == 0) ? 0 : 1);
		if (!empty($cache[$rating_value]['part'])) {
			if ($cache[$rating_value]['part'] <= 0.25) {
				$cache[$rating_value]['part'] = 1;
			} elseif ($cache[$rating_value]['part'] <= 0.5) {
				$cache[$rating_value]['part'] = 2;
			} elseif ($cache[$rating_value]['part'] <= 0.75) {
				$cache[$rating_value]['part'] = 3;
			} elseif ($cache[$rating_value]['part'] <= 0.99) {
				$cache[$rating_value]['part'] = 4;
			}
		}
	}
	return $cache[$rating_value];
}
function fn_sdeep_get_auth_dealer_info($vendor_id) {
	$vendors_fv = db_get_field("SELECT sdeep_features FROM ?:companies WHERE company_id=?i", $vendor_id);
	$vendors_fv = @unserialize($vendors_fv);
	if($vendors_fv) {
		$auth_dealer_info = db_get_array("SELECT l.object_id, l.image_id, image_path FROM cscart_images_links as l LEFT JOIN cscart_images as i ON i.image_id = l.image_id WHERE l.object_type='feature_variant' AND l.object_id IN(".implode(',', $vendors_fv).")");
		foreach ($auth_dealer_info as &$v) {
			if($v['image_id'] && $v['image_path']) {
				$image_data['images_image_id'] = $v['image_id'];
				$image_data['image_path'] = $v['image_path'];
				$image_data = fn_attach_absolute_image_paths($image_data, 'feature_variant');
				$v['thumb_path'] = fn_generate_thumbnail($image_data['image_path'], Registry::get('addons.sdeep.brand_thumb_width'));
			}
		}
		return $auth_dealer_info;
	}
}
?>
