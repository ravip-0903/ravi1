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


if ( !defined('AREA') ) { die('Access denied'); }

function fn_twigmo_place_order($order_id, $action = '', $__order_status = '', $cart = null)
{
	if (!$access_id = Registry::get('addons.twigmo.access_id')) {
		return;
	}
	
	if ($action == 'save') {
		return;
	}
	
	$fields = array(
		'order_id',
		'total',
		'products'
	);

	$order_info = fn_get_order_info($order_id);

	if (!empty($order_info['items'])) {
		$order_info['products'] = array();

		foreach ($order_info['items'] as $product) {
			$order_info['products'][] = $product;
		}
		unset($order_info['items']);
	}

	$api_data = fn_get_as_api_list('orders', array($order_info));

	return fn_post_request($api_data, 'orders', 'add');
}

function fn_twigmo_get_shipments($params, $fields_list, $joins, &$condition, $group)
{
	if (!empty($params['shipping_id'])) {
		$condition .= db_quote(' AND ?:shipments.shipping_id = ?i', $params['shipping_id']);
	}

	if (!empty($params['carrier'])) {
		$condition .= db_quote(' AND ?:shipments.carrier = ?s', $params['carrier']);
	}

	if (!empty($params['email'])) {
		$condition .= db_quote(' AND ?:orders.email LIKE ?l', '%'.trim($params['email']).'%');
	}

	return true;
}

function fn_twigmo_get_users($params, $fields, $sortings, $condition, $join)
{
	// Search string condition for SQL query
	if (isset($params['q']) && fn_string_no_empty($params['q'])) {

		$params['q'] = trim($params['q']);
		if (empty($params['match'])) {
			$params['match'] = '';
		}

		if ($params['match'] == 'any') {
			$pieces = fn_explode(' ', $params['q']);
			$search_type = ' OR ';
		} elseif ($params['match'] == 'all') {
			$pieces = fn_explode(' ', $params['q']);
			$search_type = ' AND ';
		} else {
			$pieces = array($params['q']);
			$search_type = '';
		}

		$_condition = array();
		foreach ($pieces as $piece) {
			if (strlen($piece) == 0) {
				continue;
			}

			$tmp = db_quote("?:users.email LIKE ?l", "%$piece%");
			$tmp .= db_quote(" OR ?:users.user_login LIKE ?l", "%$piece%");
			$tmp .= db_quote(" OR (?:users.firstname LIKE ?l OR ?:users.lastname LIKE ?l)", "%$piece%", "%$piece%");

			$_condition[] = '(' . $tmp . ')';
		}

		$_cond = implode($search_type, $_condition);

		if (!empty($_condition)) {
			$condition .= ' AND (' . $_cond . ') ';
		}
	}
}

function fn_twigmo_additional_fields_in_search($params, $fields, $sortings, $condition, &$join, $sorting, $group_by, &$tmp, $piece)
{
	if (!empty($params['ppcode']) && $params['ppcode'] == 'Y') {
		$tmp .= db_quote(" OR (twg_pcinventory.product_code LIKE ?l OR products.product_code LIKE ?l)", "%$piece%", "%$piece%");
	}
}

function fn_twigmo_get_products($params, $fields, $sortings, $condition, &$join, $sorting, $group_by, $lang_code)
{
	if (isset($params['q']) && fn_string_no_empty($params['q']) && !empty($params['ppcode']) && $params['ppcode'] == 'Y') {
		$join .= " LEFT JOIN ?:product_options_inventory as twg_pcinventory ON twg_pcinventory.product_id = products.product_id";
	}
}

function fn_post_request($api_data, $object_type, $action)
{
	$twigmo = fn_init_twigmo();
	
	if ($object_type != 'orders') {
		return true;
	}
	
	$result = $twigmo->postData($api_data, $object_type, $action, array('dispatch' => 'api.post'));

	if ($result) {
		return true;
	}
	
	if (empty($twigmo->response_data['error'])) {
		if (AREA == 'A') {
			fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('twg_post_request_fail'), true);
		}
		return false;
	}
		
	$errors = array();
	foreach ($twigmo->getObjects($twigmo->response_data['error']) as $error) {
		$errors[] = $error['message'];
	}

	if (AREA == 'A') {
		fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('twg_post_request_fail') . ' "' . implode('", "', $errors) . '"', true);
	}

	return false;
}

function fn_get_user_search_params()
{
	$twigmo_params = array (
		'page',
		'company',
		'user_name',
		'user_login',
		'tax_exempt',
		'status',
		'email',
		'address',
		'zipcode',
		'country',
		'state',
		'city',
		'user_type',
		'user_id',
		'exclude_user_types',
		'sort_order',
		'sort_by'
	);

	$result = array();
	foreach ($twigmo_params as $param) {
		if (!empty($_REQUEST[$param])) {
			$result[$param] = $_REQUEST[$param];
		}
	}

	return $result;
}

function fn_init_twigmo()
{
	$twigmo = new Twigmo(Registry::get('addons.twigmo.service_url'), Registry::get('addons.twigmo.access_id'), Registry::get('addons.twigmo.secret_access_key'), Registry::get('addons.twigmo.service_username'), Registry::get('addons.twigmo.service_password'));

	return $twigmo;
}

function fn_validate_auth()
{
	return Twigmo::validateAuth(Registry::get('addons.twigmo.secret_access_key'));
}

// section functions
function fn_get_order_conditions($params)
{
	$condition = $join = $group = '';

	if (!empty($params['cname'])) {
		$arr = explode(' ', $params['cname']);
		if (sizeof($arr) == 2) {
			$condition .= db_quote(" AND ?:orders.firstname LIKE ?l AND ?:orders.lastname LIKE ?l", "%$arr[0]%", "%$arr[1]%");
		} else {
			$condition .= db_quote(" AND (?:orders.firstname LIKE ?l OR ?:orders.lastname LIKE ?l)", "%$params[cname]%", "%$params[cname]%");
		}
	}

	if (!empty($params['tax_exempt'])) {
		$condition .= db_quote(" AND ?:orders.tax_exempt = ?s", $params['tax_exempt']);
	}

	if (!empty($params['email'])) {
		$condition .= db_quote(" AND ?:orders.email LIKE ?l", "%$params[email]%");
	}

	if (!empty($params['user_id'])){
		$condition .= db_quote(' AND ?:orders.user_id IN (?n)', $params['user_id']);
	}

	if (!empty($params['total_from'])) {
		$condition .= db_quote(" AND ?:orders.total >= ?d", fn_convert_price($params['total_from']));
	}

	if (!empty($params['total_to'])) {
		$condition .= db_quote(" AND ?:orders.total <= ?d", fn_convert_price($params['total_to']));
	}

	if (!empty($params['status'])) {
		$condition .= db_quote(' AND ?:orders.status IN (?a)', $params['status']);
	}

	if (!empty($params['order_id'])) {
		$condition .= db_quote(' AND ?:orders.order_id IN (?n)', (!is_array($params['order_id']) && (strpos($params['order_id'], ',') !== false) ? explode(',', $params['order_id']) : $params['order_id']));
	}

	if (!empty($params['p_ids']) || !empty($params['product_view_id'])) {
		$arr = (strpos($params['p_ids'], ',') !== false || !is_array($params['p_ids'])) ? explode(',', $params['p_ids']) : $params['p_ids'];

		if (empty($params['product_view_id'])) {
			$condition .= db_quote(" AND ?:order_details.product_id IN (?n)", $arr);
		} else {
			$condition .= db_quote(" AND ?:order_details.product_id IN (?n)", db_get_fields(fn_get_products(array('view_id' => $params['product_view_id'], 'get_query' => true))));
		}

		$join .= " LEFT JOIN ?:order_details ON ?:order_details.order_id = ?:orders.order_id";
	}

	if (!empty($params['admin_user_id'])) {
		$condition .= db_quote(" AND ?:new_orders.user_id = ?i", $params['admin_user_id']);
		$join .= " LEFT JOIN ?:new_orders ON ?:new_orders.order_id = ?:orders.order_id";
	}

	if (!empty($params['shippings'])) {
		$set_conditions = array();
		foreach ($params['shippings'] as $v) {
			$set_conditions[] = db_quote("FIND_IN_SET(?s, ?:orders.shipping_ids)", $v);
		}
		$condition .= " AND (" . implode(' OR ', $set_conditions) . ")";
	}

	if (!empty($params['period']) && $params['period'] != 'A') {
		list($params['time_from'], $params['time_to']) = fn_create_periods($params);

		$condition .= db_quote(" AND (?:orders.timestamp >= ?i AND ?:orders.timestamp <= ?i)", $params['time_from'], $params['time_to']);
	}
	
	if (!empty($params['custom_files']) && $params['custom_files'] == 'Y') {
		$condition .= db_quote(" AND ?:order_details.extra LIKE ?l", "%custom_files%");
		
		if (empty($params['p_ids']) && empty($params['product_view_id'])) {
			$join .= " LEFT JOIN ?:order_details ON ?:order_details.order_id = ?:orders.order_id";
		}
	}

	fn_set_hook('get_orders', $params, $fields, $sortings, $condition, $join);
	
	return array($condition, $join);
}

function fn_get_order_sections($orders, $params)
{
	// the order periods
	// name points to start period time
	
	$params['get_conditions'] = 'Y';

	list($condition, $join) = fn_get_order_conditions($params);


	$today = getdate(TIME);
	$wday = empty($today['wday']) ? "6" : (($today['wday'] == 1) ? "0" : $today['wday'] - 1);
	$wstart = getdate(strtotime("-$wday day"));
	
	$date_periods = array(
		'today' => mktime(0, 0, 0, $today['mon'], $today['mday'], $today['year']),
		'week' => mktime(0, 0, 0, $wstart['mon'], $wstart['mday'], $wstart['year']),
		'month' => mktime(0, 0, 0, $today['mon'], 1, $today['year']),
		'year' => mktime(0, 0, 0, 1, 1, $today['year'])
	);

	$total_periods = array(10000, 1000, 100, 10);	
	$order_totals = array();

	$sort_order = $params['sort_order'] == 'asc' ? 'asc' : 'desc';
	$sort_by = $params['sort_by'];
	list($order_sections, $section_names, $order_totals, $show_empty_sections) = 
		fn_get_order_sections_info($date_periods, $total_periods, $orders, $sort_by, $sort_order);
		
	// remove empty sections from the begin and the end of the page

	$pagination = Registry::get('view')->get_template_vars('pagination');

	$first_section = false;
	$last_section = false;
	$recalculate_sections = array();

	if ($pagination['current_page'] == 1) {
		$first_section = true;
	}

	$total_items = 0;
	$first_calculated = false;
	$last_calculated = false;

	foreach($section_names as $section_id => $section) {
		if (!$show_empty_sections) {
			if (!isset($order_sections[$section_id]) || empty($order_sections[$section_id])) {
				unset($section_names[$section_id]);
			}
			continue;
		}
		if ($pagination['total_pages'] == 1) {
			continue;
		}
		if (isset($order_sections[$section_id]) && !empty($order_sections[$section_id])) {
			$total_items += count($order_sections[$section_id]);
			if (($total_items == $pagination['items_per_page']) &&
				($pagination['current_page'] != $pagination['total_pages'])) {

				$last_section = true;
				$section_condition = fn_get_order_section_condition($section_id, $params['sort_by'], $date_periods, $total_periods);

				$new_totals = db_get_field("SELECT sum(total) FROM ?:orders $join WHERE 1 $condition $section_condition");

				if ($new_totals != $order_totals[$section_id]) {
					$order_totals[$section_id] = $new_totals;
					$last_calculated = true;

				}

			}
			if (!$first_calculated) {
				$first_calculated = true;
				if ($pagination['current_page'] > 1) {
					$section_condition = fn_get_order_section_condition($section_id, $params['sort_by'], $date_periods, $total_periods);
					
					$order_totals[$section_id] = db_get_field("SELECT sum(total) FROM ?:orders $join WHERE 1 $condition $section_condition");
				}
			}
			$first_section = true;
		} else if ($last_section || !$first_section) {
			if (!$first_section) {
				unset($section_names[$section_id]);
			}
			if ($last_section) {
				if ($last_calculated) {
					unset($section_names[$section_id]);
				} else {
					$section_condition = fn_get_order_section_condition($section_id, $params['sort_by'], $date_periods, $total_periods);
					
					$section_total = db_get_field("SELECT sum(total) FROM ?:orders $join WHERE 1 $condition $section_condition");

					if ($section_total > 0) {
						unset($section_names[$section_id]);
						$last_calculated = true;
					}
				}
			}
		}
	}

	$sections = array();

	foreach ($section_names as $section_id => $section_name) {
		$sections[] = array (
			'name' => $section_name,
			'total' => !empty($order_totals[$section_id]) ? $order_totals[$section_id] : 0,
			'orders' => !empty($order_sections[$section_id]) ? $order_sections[$section_id] : array()
		);
		
	}

	return $sections;
}

function fn_get_order_sections_info($date_periods, $total_periods, $orders, $sort_by, $sort_order)
{
	$order_sections = array();
	$section_names = array();
	$order_totals = array();

	$show_empty_sections = false;
	if ($sort_by == 'date') {
		$last_date = min($date_periods);

		foreach(array_keys($date_periods) as $period_id) {
			$section_names[$period_id] = fn_get_order_period_name($period_id);
		}

		foreach ($orders as $order) {
			$selected_period_id = '';
			if ($order['timestamp'] > TIME) {
				$selected_period_id = 'future';
			} elseif ($order['timestamp'] < $last_date) {
				$selected_period_id = 'past';

			} else {
				foreach	($date_periods as $period_id => $start_date) {
					if ($order['timestamp'] > $start_date) {
						$selected_period_id = $period_id;
						break;
					}
				}
			}
			if ($selected_period_id != '') {
				$order_sections[$selected_period_id][] = $order;
				$order_totals[$selected_period_id] = isset($order_totals[$selected_period_id])? 
					$order_totals[$selected_period_id] + $order['total'] : $order['total'];
			}
		}
			
		if (isset($order_sections['future'])) {
			$section_names = array(
				'future' => fn_get_order_period_name('future')
			) + $section_names;
		}
		if (isset($order_sections['past'])) {
			$section_names['past'] = fn_get_order_period_name('more_than_year');
		}

		if ($sort_order == 'asc') {
			$section_names = array_reverse($section_names, true);
		}

		$show_empty_sections = true;
			
	} elseif ($sort_by == 'status') {
		$section_names = fn_get_statuses(STATUSES_ORDER, true);

		ksort($section_names);
		if ($sort_order == 'desc') {
			$section_names = array_reverse($section_names);
		}
			
		foreach ($orders as $order) {
			$selected_period_id = $order['status'];
			$order_sections[$selected_period_id][] = $order;
			$order_totals[$selected_period_id] = isset($order_totals[$selected_period_id])? 
				$order_totals[$selected_period_id] + $order['total'] : $order['total'];
		}
			
		$show_empty_sections = true;

	} elseif ($sort_by == 'total') {
		$min_total = min($total_periods);

		$section_names = array();
		foreach($total_periods as $subtotal) {
			$section_names['more_' . $subtotal] = fn_get_lang_var('more_than') . ' ' . fn_format_price($subtotal);
		}
		$section_names['less'] = fn_get_lang_var('less_than') . ' ' . fn_format_price($min_total);

		reset($total_periods);

		foreach ($orders as $order) {
			if ($order['total'] < $min_total) {
				$selected_period_id = 'less';
			} else {
				foreach	($total_periods as $subtotal) {
					if ($order['total'] > $subtotal) {
						$selected_period_id = 'more_' . $subtotal;
						break;
					}
				}
			}
			if ($selected_period_id) {
				$order_sections[$selected_period_id][] = $order;
				$order_totals[$selected_period_id] = isset($order_totals[$selected_period_id])? 
					$order_totals[$selected_period_id] + $order['total'] : $order['total'];
			}				
		}

		if ($sort_order == 'asc') {
			$section_names = array_reverse($section_names);
		}
	}

	return array($order_sections, $section_names, $order_totals, $show_empty_sections);
}

function fn_get_order_period_name($period_id)
{
	return fn_get_lang_var($period_id);
}

function fn_get_order_section_condition($section_id, $sort_by, $date_periods, $total_periods)
{
	$section_condition = ' AND ';

	if ($sort_by == 'date') {

		if ($section_id == 'future') {
			$max_date = max($date_periods);
			$section_condition .= db_quote("?:orders.timestamp > ?i", TIME);

		} elseif ($section_id == 'past') {
			$min_date = min($date_periods);
			$section_condition .= db_quote("?:orders.timestamp <= ?i", $min_date);

		} else {
			$end_date = TIME;
			foreach	($date_periods as $period_id => $start_date) {
				if ($section_id == $period_id) {
					$section_condition .= db_quote("?:orders.timestamp > ?i AND ?:orders.timestamp <= ?i", $start_date, $end_date);
					break;
				}
				$end_date = $start_date;
			}				
		}

	} elseif ($sort_by == 'status') {
		$section_condition .= db_quote("?:orders.status = ?s", $section_id);
		
	} elseif ($sort_by == 'total') {

		if ($section_id == 'less') {
			$min_total = min($total_periods);
			$section_condition .= db_quote("?:orders.total <= ?i", $min_total);

		} else {

			$prev_total = 0;
			foreach($total_periods as $subtotal) {
				if ($section_id == 'more_' . $subtotal) {
					$section_condition .= db_quote("?:orders.total >= ?i", $subtotal);
					if ($prev_total) {
						$section_condition .= db_quote(" AND ?:orders.total < ?i", $prev_tort);
					}
					break;
				}
				$prev_total = $subtotal;
			}
		}
	}

	return $section_condition;
}

function fn_twigmo_api_update_product($product_data, $product_id = 0, $lang_code = CART_LANGUAGE)
{
	$_data = $product_data;

	if (!empty($product_data['timestamp'])) {
		$_data['timestamp'] = fn_parse_date($product_data['timestamp']); // Minimal data for product record
	}

	if (!empty($product_data['avail_since'])) {
		$_data['avail_since'] = fn_parse_date($product_data['avail_since']);
	}

	if (Registry::get('settings.General.allow_negative_amount') == 'N' && isset($_data['amount'])) {
		$_data['amount'] = abs($_data['amount']);
	}

	// add new product
	if (empty($product_id)) {
		$create = true;
		// product title can't be empty
		if(empty($product_data['product'])) {
			return false;
		}

		$product_id = db_query("INSERT INTO ?:products ?e", $_data);

		if (empty($product_id)) {
			return false;
		}

		//
		// Adding same product descriptions for all cart languages
		//
		$_data = $product_data;
		$_data['product_id'] =	$product_id;
		$_data['product'] = trim($_data['product'], " -");

		foreach ((array)Registry::get('languages') as $_data['lang_code'] => $_v) {
			db_query("INSERT INTO ?:product_descriptions ?e", $_data);
		}

	// update product
	} else {
		if (isset($product_data['product']) && empty($product_data['product'])) {
			unset($product_data['product']);
		}

		db_query("UPDATE ?:products SET ?u WHERE product_id = ?i", $_data, $product_id);

		$_data = $product_data;
		if (!empty($_data['product'])){
			$_data['product'] = trim($_data['product'], " -");
		}
		db_query("UPDATE ?:product_descriptions SET ?u WHERE product_id = ?i AND lang_code = ?s", $_data, $product_id, $lang_code);
	}

	// Log product add/update
	fn_log_event('products', !empty($create) ? 'create' : 'update', array(
		'product_id' => $product_id
	));

	// Update product prices
	if (isset($product_data['price'])) {
		if (!isset($product_data['prices'])) {
			$product_data['prices'] = array();
			$skip_price_delete = true;
		}
		$_price = array (
			'price' => abs($product_data['price']),
			'lower_limit' => 1,
		);

		array_unshift($product_data['prices'], $_price);
	}

	if (!empty($product_data['prices'])) {
		if (empty($skip_price_delete)) {
			db_query("DELETE FROM ?:product_prices WHERE product_id = ?i", $product_id);
		}

		foreach ($product_data['prices'] as $v) {
			if (!empty($v['lower_limit'])) {
				$v['product_id'] = $product_id;
				db_query("REPLACE INTO ?:product_prices ?e", $v);
			}
		}
	}
	
	// Update main icon
	if (!empty($product_data['icon'])) {
		fn_update_icons_by_api_data($product_data['icon'], $product_id);
	}

	// Update additional images
	if (!empty($product_data['images'])) {
		fn_update_images_by_api_data($product_data['images'], $product_id);
	}

	
	return $product_id;
}

/*
 * Extract image from api data
 */
function fn_get_image_by_api_data($api_image)
{
	if (empty($api_image['data']) || (empty($api_image['file_name']) && empty($api_image['type']))) {
		return false;
	}
		

	if (empty($api_image['file_name'])) {
		$api_image['file_name'] = 'image_' . strtolower(fn_generate_code('', 4)) . '.' . $api_image['type'];
	}

	$_data = base64_decode($api_image['data']);

	$image = array (
		'name' => $api_image['file_name'],
		'path' => fn_create_temp_file(),
		'size' => strlen($_data)
	);

	$fd = fopen($image['path'], 'wb');

	if (!$fd) {
		return false;
	}

	fwrite($fd, $_data, $image['size']);
	fclose($fd);
	@chmod($image['path'], DEFAULT_FILE_PERMISSIONS);

	return $image;
}

/*
 * Update additional images
 */
function fn_update_images_by_api_data($images, $object_id = 0, $object_type = 'product', $lang_code = CART_LANGUAGE)
{
	$icons = array();
	$detailed = array();
	$pair_data = array();

	foreach ($images as $image) {
		$p_data = array (
			'pair_id' => 0,
			'type' => 'A',
			'image_alt' => '',
			'detailed_alt' => !empty($image['alt']) ? $image['alt'] : '',
		);


		if (!empty($image['image_id'])) {
			$image_info = db_get_row("SELECT type, pair_id FROM ?:images_links WHERE object_id = ?i AND object_type=?s AND detailed_id = ?i", $object_id, $object_type, $image['image_id']);

			if (empty($image_info) || $image_info['type'] == 'M') {
				// ignore errors in image_id 
				// deny update/delete main detailed image
				continue;
			}

			if (!empty($image['deleted']) && $image['deleted'] == 'Y') {
				fn_delete_image($image['image_id'], $image_info['pair_id'], 'detailed');
				continue;
			}

			$p_data['pair_id'] = $image_info['pair_id'];
			$p_data['image_alt'] = db_get_field("SELECT a.description FROM ?:common_descriptions as a, ?:images_links as b WHERE a.object_holder = ?s AND a.lang_code = ?s AND a.object_id = b.image_id AND b.pair_id = ?i", 'images', $lang_code, $image_info['pair_id']);
		}

		$detailed_image = fn_get_image_by_api_data($image);
		if (empty($image['image_id']) && empty($detailed_image)) {
			continue;
		}
		$detailed[] = $detailed_image;
		$pair_data[] = $p_data;
	}

	return fn_update_image_pairs($icons, $detailed, $pair_data, $object_id, $object_type, array(), '', 0, true, $lang_code);
}

function fn_update_icons_by_api_data($image, $object_id = 0, $object_type = 'product', $lang_code = CART_LANGUAGE)
{
	if (!empty($image['deleted']) && $image['deleted'] == 'Y') {
		// delete image
		$image_info = db_get_row("SELECT image_id, pair_id FROM ?:images_links WHERE object_id = ?i AND object_type=?s AND type = 'M'", $object_id, $object_type);

		if (!empty($image_info)) {
			fn_delete_image($image_info['image_id'], $image_info['pair_id'], $object_type);
		}

		return true;
	}

	$icon_list = array();

	if ($icon = fn_get_image_by_api_data($image)) {
		$icon_list[] = $icon;
	}

	$detailed_alt = db_get_field("SELECT a.description FROM ?:common_descriptions as a, ?:images_links as b WHERE a.object_holder = ?s AND a.lang_code = ?s AND a.object_id = b.detailed_id AND b.object_id = ?i AND b.object_type = ?s AND b.type = ?s", 'images', $lang_code, $object_id, $object_type, 'M');

	$icon_data = array (
		'type' => 'M',
		'image_alt' => !empty($image['alt']) ? $image['alt'] : '',
		'detailed_alt' => $detailed_alt
	);

	return fn_update_image_pairs($icon_list, array(), array($icon_data), $object_id, $object_type, array(), '', 0, true, $lang_code);
}

function fn_get_carriers()
{
	return array (
		'USP'=> fn_get_lang_var('usps'),
		'UPS'=> fn_get_lang_var('ups'),
		'FDX'=> fn_get_lang_var('fedex'),
		'AUP'=> fn_get_lang_var('australia_post'),
		'DHL'=> fn_get_lang_var('dhl'),
		'CHP'=> fn_get_lang_var('chp')
	);
}

function fn_get_orders_as_api_list($orders, $lang_code)
{
	$order_ids = array();
	foreach ($orders as $order) {
		$order_ids[] = $order['order_id'];
	}

	$payment_names = db_get_hash_array("SELECT order_id, payment FROM ?:orders, ?:payment_descriptions WHERE ?:payment_descriptions.payment_id = ?:orders.payment_id AND ?:payment_descriptions.lang_code = ?s AND ?:orders.order_id IN (?a)", 'order_id', $lang_code, $order_ids);

	$shippings  = db_get_hash_array("SELECT order_id, data FROM ?:order_data WHERE type = ?s AND order_id IN (?a)", 'order_id', 'L', $order_ids);

	foreach ($orders as $k => $v) {
		$orders[$k]['payment'] = !empty($payment_names[$v['order_id']]) ? $payment_names[$v['order_id']]['payment'] : '';
		$orders[$k]['shippings'] = array();
		if (!empty($shippings[$v['order_id']])) {
			$shippings = @unserialize($shippings[$v['order_id']]['data']);

			if (empty($shippings)) {
				continue;
			}

			foreach ($shippings as $shipping) {
				$orders[$k]['shippings'][] = array (
					'carrier' => !empty($shipping['carrier']) ? $shipping['carrier'] : '',
					'shipping' => !empty($shipping['shipping']) ? $shipping['shipping'] : '',
				);
			}
		}
	}

	$fields = array (
		'order_id',
		'user_id',
		'total',
		'timestamp',
		'status',
		'firstname',
		'lastname',
		'email',
		'payment_name',
		'shippings'
	);

	return fn_get_as_api_list('orders', $orders, $fields);
}

function fn_get_api_image_bin_data($icon, $params, $type = 'product')
{
	if (!empty($icon['absolute_path'])) {
		$image_file = $icon['absolute_path'];
	} else {
		$_image_file = db_get_field("SELECT image_path FROM ?:images WHERE image_id = ?i", $image_id);
		$image_file = DIR_IMAGES. $type . '/' . $_image_file;
	}

	if (extension_loaded('gd') && (!empty($params['image_x']) || !empty($params['image_y']))) {
		$new_image_x = !empty($params['image_x']) ? $params['image_x'] : $params['image_y'] / $icon['image_y'] * $icon['image_x'];
		$new_image_y = !empty($params['image_y']) ? $params['image_y'] : $params['image_x'] / $icon['image_x'] * $icon['image_y'];

		//$image_gd = imagecreatefromstring($image_data);
		$new_image_gd = imagecreatetruecolor($new_image_x, $new_image_y);
		list($width, $height, $mime_type) = fn_get_image_size($image_file);
		$ext = fn_get_image_extension($mime_type);

		if ($ext == 'gif' && function_exists('imagegif')) {
			$image_gd = imagecreatefromgif($image_file);
		} elseif ($ext == 'jpg' && function_exists('imagejpeg')) {
			$image_gd = imagecreatefromjpeg($image_file);
		} elseif ($ext == 'png' && function_exists('imagepng')) {
			$image_gd = imagecreatefrompng($image_file);
		} else {
			return false;
		}
			
		imagecopyresized($new_image_gd, $image_gd, 0, 0, 0, 0, $new_image_x, $new_image_y, $icon['image_x'], $icon['image_y']);

		$tmp_file = fn_create_temp_file();
		if ($ext == 'gif') {
			imagegif($new_image_gd, $tmp_file);
		} elseif ($ext == 'jpg') {
			imagejpeg($new_image_gd, $tmp_file, 50);
		} elseif ($ext == 'png') {
			imagepng($new_image_gd, $tmp_file, 0);
		}
			
		if (!($image_data = fn_get_contents($tmp_file))) {
			return false;
		}
			
		$icon['data'] = base64_encode($image_data);
		$icon['image_x'] = $new_image_x;
		$icon['image_y'] = $new_image_y;
			
	} elseif ($image_data = fn_get_contents($image_file)) {
		$icon['data'] = base64_encode($image_data);
	}

	return $icon;
}

function fn_get_api_image_data($image_pair, $type='product', $image_type = 'icon', $params = array())
{
	
	if (empty($image_pair[$image_type])) {
		return false;
	}
	
	$icon = $image_pair[$image_type];
	if ($image_type == 'icon') {
		$icon['image_id'] = $image_pair['image_id'];
	} else {
		$icon['image_id'] = $image_pair['detailed_id'];
	}

	$icon['url'] = 'http://' . Registry::get('config.http_host') . $image_pair[$image_type]['http_image_path'];
	
	if (!empty($params['use_bin_data'])) {
		$icon = fn_get_api_image_bin_data($icon, $params, $type);
	}
	
	return $icon;
}

function fn_tw_get_domain_name($host)
{
	$parts = explode('.', $host);
	array_pop($parts); // remove 1st-level domain
	$domain = array_pop($parts); // get 2nd-level domain

	return $domain;
}

function fn_api_get_products($params, $items_per_page, $lang_code = CART_LANGUAGE)
{

	if (empty($params['extend'])) {
		$params['extend'] = array (
			'description'
		);
	}
	
	if (!empty($params['pid']) && !is_array($params['pid'])) {
		$params['pid'] = explode(',', $params['pid']);
	}

	if (!empty($params['q'])) {
		// search by product code
		$params['ppcode'] = 'Y';
	}
	
	list($products, $search, $totals) = fn_get_products($params, $items_per_page, $lang_code);

	if (empty($products)) {
		return false;
	}

	$product_ids = array();
	foreach ($products  as $k => $v) {

		//unset($products[$k]['short_description']);
		//$products[$k]['full_description'] = substr($products[$k]['full_description'], 0, TWG_MAX_DESCRIPTION_LEN);
		if (!empty($products[$k]['short_description']) || !empty($products[$k]['full_description'])) {
			$products[$k]['short_description'] = !empty($products[$k]['short_description']) ? strip_tags($products[$k]['short_description']) : fn_substr(strip_tags($products[$k]['full_description']), 0, TWG_MAX_DESCRIPTION_LEN);
			unset($products[$k]['full_description']);
		} else {
			$products[$k]['short_description'] = '';
		}

		$product_ids[] = $v['product_id'];

		// Get product image data
		$main_pair = fn_get_image_pairs($v['product_id'], 'product', 'M', true, false, $lang_code);
		if (!empty($main_pair)) {
			$products[$k]['icon'] = fn_get_api_image_data($main_pair, 'product', 'icon', $params);
		}

	}

	$category_descriptions = db_get_hash_array("SELECT p.product_id, p.category_id, c.category FROM ?:products_categories AS p, ?:category_descriptions AS c WHERE c.category_id = p.category_id AND c.lang_code = ?s AND p.product_id IN (?a) AND p.link_type = 'M'", 'product_id', $lang_code, $product_ids);
	
	foreach ($products as $k => $v) {
		if (!empty($v['product_id']) && !empty($category_descriptions[$v['product_id']])) {
			$products[$k]['category'] = $category_descriptions[$v['product_id']]['category'];
			$products[$k]['category_id'] = $category_descriptions[$v['product_id']]['category_id'];
		}
	}

	$result = fn_get_as_api_list('products', $products);

	return $result;
}

function fn_api_get_categories($params, $lang_code = CART_LANGUAGE)
{
	$category_id = !empty($params['id']) ? $params['id'] : 0;
	$type = !empty($params['type']) ? $params['type'] : '';

	if ($type == 'one_level') {
		$type_params = array (
			'category_id' => $category_id,
			'current_category_id' => $category_id,
			'simple' => false,
			'visible' => true
		);

	} elseif ($type == 'plain_tree') {
		$type_params = array (
			'category_id' => $category_id,
			'current_category_id' => $category_id,
			'simple' => false,
			'visible' => false,
			'plain' => true
		);
		
	} else {
		$type_params = array (
			'simple' => false,
			'category_id' => $category_id,
			'current_category_id' => $category_id
		);
	}
	$params =  array_merge($type_params, $params);

	list($categories, ) = fn_get_categories($params, $lang_code);

	foreach ($categories as $k => $v) {
		if (!empty($v['has_children'])) {
			$categories[$k]['subcategory_count'] = db_get_field("SELECT COUNT(*) FROM ?:categories WHERE parent_id = ?i", $v['category_id']);
		}
		if (!empty($params['get_images']) && !empty($v['main_pair'])) {
			$categories[$k]['icon'] = fn_get_api_image_data($v['main_pair'], 'category', $params);
		}
	}

	$result = fn_get_as_api_list('categories', $categories);

	return $result;
}

function fn_twigmo_get_categories(&$params, &$join, &$condition, &$fields, &$group_by, &$sortings)
{
	if (!empty($params['depth'])) {
		
		if (!empty($params['category_id'])) {
			$from_id_path = db_get_field("SELECT id_path FROM ?:categories IGNORE INDEX(p_category_id) WHERE category_id = ?i", $params['category_id']) . '/';
			
		} else {
			$from_id_path = '';
		}

		$from_id_path .= str_repeat('%/', $params['depth']) . '%';
		$condition .= db_quote(" AND NOT ?:categories.id_path LIKE ?l", "$from_id_path");
	}

	if (!empty($params['cid'])) {
		$cids = is_array($params['cid']) ? $params['cid'] : array($params['cid']);
		$condition .= db_quote(" AND ?:categories.category_id IN (?n)", $cids);
	}
}

function fn_get_api_product_data($product_id, $lang_code = CART_LANGUAGE)
{
	$auth = & $_SESSION['auth'];

	$product = fn_get_product_data($product_id, $auth, $lang_code);

	if (empty($product)) {
		return array();
	}

	fn_gather_additional_product_data($product, true, true);

	if (Registry::get('addons.discussion.status') == 'A') {
		$discussion = fn_get_discussion($product_id, 'P');
		
		if (!empty($discussion['thread_id'])) {
			$discussion_page = !empty($_REQUEST['discussion_page']) ? $_REQUEST['discussion_page'] : 0;
			$product['product_reviews'] = fn_get_discussion_posts($discussion['thread_id'], $discussion_page);
		}
	}

	$product['category_id'] = $product['main_category'];
	$product['images'] = array();

	if (!empty($product['main_pair'])) {
		$product['icon'] = fn_get_api_image_data($product['main_pair'], 'product', 'icon', $_REQUEST);
		$product['images'][] = fn_get_api_image_data($product['main_pair'], 'product', 'detailed', $_REQUEST);
	}

	foreach ($product['image_pairs'] as $k => $v) {
		$product['images'][] = fn_get_api_image_data($v, 'product', 'detailed', $_REQUEST);
	}

	$product['category'] = db_get_field("SELECT category FROM ?:category_descriptions WHERE category_id = ?i AND lang_code = ?s", $product['main_category'], $lang_code);
	
	$product['options_exceptions'] = fn_get_api_product_options_exceptions($product_id);
	$product['options_inventory'] = fn_get_api_product_options_inventory($product_id, $lang_code);

	return fn_get_as_api_object('products', $product);
}

function fn_get_api_category_data($category_id, $lang_code)
{
	$category = fn_get_category_data($category_id, $lang_code);

	if (!empty($category['parent_id'])) {
		$category['parent_category'] = db_get_field("SELECT category FROM ?:category_descriptions WHERE ?:category_descriptions.category_id = ?i AND ?:category_descriptions.lang_code = ?s", $category['parent_id'], $lang_code);
	}
	if (!empty($category['main_pair'])) {
		$category['icon'] = fn_get_api_image_data($category['main_pair'], 'category', 'detailed', $_REQUEST);
	}

	return fn_get_as_api_object('categories', $category);
}

function fn_get_api_product_options_exceptions($product_id)
{
	$exceptions = db_get_array("SELECT * FROM ?:product_options_exceptions WHERE product_id = ?i ORDER BY exception_id", $product_id);

	if (empty($exceptions)) {
		return array();
	}
	
	foreach ($exceptions as $k => $v) {
		$_comb = unserialize($v['combination']);
		$exceptions[$k]['combination'] = array();
		
		foreach ($_comb as $option_id => $variant_id) {
			$exceptions[$k]['combination'][] = array (
				'option_id' => $option_id,
				'variant_id' => $variant_id
			);
		}
	}

	return fn_get_as_api_list('product_options_exceptions', $exceptions);
}

function fn_get_api_product_options_inventory($product_id, $lang_code = CART_LANGUAGE)
{
	$inventory = db_get_array("SELECT * FROM ?:product_options_inventory WHERE product_id = ?i ORDER BY position", $product_id);

	if (empty($inventory)) {
		return array();
	}

	$inventory_ids = array();
	foreach ($inventory as $k => $v) {
		$inventory_ids[] = $v['combination_hash'];
	}

	$image_pairs = fn_get_image_pairs($inventory_ids, 'product_option', 'M', false, true, $lang_code);

	foreach ($inventory as $k => $v) {
		$inventory[$k]['combination'] = array();
		$_comb = fn_get_product_options_by_combination($v['combination']);

		if (!empty($image_pairs[$v['combination_hash']])) {
			$inventory[$k]['image'] = fn_get_api_image_data(current($image_pairs[$v['combination_hash']]), 'product_option', 'detailed', $_REQUEST);
		}

		foreach ($_comb as $option_id => $variant_id) {
			$inventory[$k]['combination'][] = array (
				'option_id' => $option_id,
				'variant_id' => $variant_id
			);
		}
	}

	return fn_get_as_api_list('product_options_inventory', $inventory);
}

function fn_api_update_order($order, $response)
{
	if (!defined('ORDER_MANAGEMENT')) {
		define('ORDER_MANAGEMENT', true);
	}

	if (!empty($order['status'])) {

		$statuses = fn_get_statuses(STATUSES_ORDER, false, true);

		if (!isset($statuses[$order['status']])) {
			$msg = str_replace('[object_id]', $order['order_id'], fn_get_lang_var('wrong_api_object_data'));
			$response->addError('ERROR_OBJECT_UPDATE', str_replace('[object]', 'orders', fn_get_lang_var('wrong_api_object_data')));
		} else {
			fn_change_order_status($order['order_id'], $order['status']);
		}
	}

	$cart = array();
	fn_clear_cart($cart, true);
	$customer_auth = fn_fill_auth(array(), array(), false, 'C');

	fn_form_cart($order['order_id'], $cart, $customer_auth);
	$cart['order_id'] = $order['order_id'];

	// update only profile data
	$profile_data = fn_check_table_fields($order, 'user_profiles');

	$cart['user_data'] = fn_array_merge($cart['user_data'], $profile_data);
	fn_calculate_cart_content($cart, $customer_auth, 'A', true, 'I');

	if (!empty($order['details'])) {
		db_query('UPDATE ?:orders SET details = ?s WHERE order_id = ?i', $order['details'], $order['order_id']);
	}

	if (!empty($order['notes'])) {
		$cart['notes'] = $order['notes'];
	}

	list($order_id, $process_payment) = fn_place_order($cart, $customer_auth, 'save');

	// place order routines with the disabled notifications
	//fn_order_placement_routines($order_id, fn_get_notification_rules(array(), true), true, 'save');

	return true;
}

function fn_api_customer_login($user_login, $password)
{
	list($status, $user_data, $user_login, $password) = fn_api_auth_routines($user_login, $password);

	if ($status === false) {
		return false;
	}

	if (empty($user_data) || ($password != $user_data['password']) || empty($password)) {

		fn_log_event('users', 'failed_login', array (
			'user' => $user_login
		));

		return false;
	}

	$_SESSION['auth'] = fn_fill_auth($user_data);

	// Set last login time
	db_query("UPDATE ?:users SET ?u WHERE user_id = ?i", array('last_login' => TIME), $user_data['user_id']);

	$_SESSION['auth']['this_login'] = TIME;
	$_SESSION['auth']['ip'] = $_SERVER['REMOTE_ADDR'];

	// Log user successful login
	fn_log_event('users', 'session', array(
		'user_id' => $user_data['user_id']
	));

	if ($cu_id = fn_get_session_data('cu_id')) {
		$cart = array();
		fn_clear_cart($cart);
		fn_save_cart_content($cart, $cu_id, 'C', 'U');
		fn_delete_session_data('cu_id');
	}

	fn_init_user_session_data($_SESSION, $user_data['user_id']);

	return $user_data;
}

function fn_api_customer_logout()
{
	// copied from common/auth.php - logout mode
	$auth = $_SESSION['auth'];

	fn_save_cart_content($_SESSION['cart'], $auth['user_id']);

	if (!empty($auth['user_id'])) {
		// Log user logout
		fn_log_event('users', 'session', array(
			'user_id' => $auth['user_id'],
			'time' => TIME - $auth['this_login'],
			'timeout' => false
		));
	}

	unset($_SESSION['auth']);
	fn_clear_cart($_SESSION['cart'], false, true);

	fn_delete_session_data(AREA_NAME . '_user_id', AREA_NAME . '_password');

	return true;
}

/*
 * Copy of fn_auth_routines
 * from auth.php
 */
function fn_api_auth_routines($user_login, $password)
{
	$status = true;

	$field = (Registry::get('settings.General.use_email_as_login') == 'Y') ? 'email' : 'user_login';
	$user_data = db_get_row("SELECT * FROM ?:users WHERE $field = ?s", $user_login);

	if (!empty($user_data)) {
		$user_data['usergroups'] = fn_get_user_usergroups($user_data['user_id']); 
	}

	fn_set_hook('auth_routines', $status, $user_data);

	if (!empty($user_data['user_type']) && $user_data['user_type'] != 'A' && AREA == 'A') {
		fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('error_area_access_denied'));
		$status = false;
	}

	if ((!empty($user_data['company_id']) && ACCOUNT_TYPE == 'admin') || (empty($user_data['company_id']) && ACCOUNT_TYPE == 'vendor')) {
		fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('error_area_access_denied'));
		$status = false;
	}

	if (!empty($user_data['status']) && $user_data['status'] == 'D') {
		fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('error_account_disabled'));
		$status = false;
	}

	return array($status, $user_data, $user_login, $password);
}

function fn_init_api_session_data()
{
	Session::set_params();
	Session::set_handlers();
	Session::start();
	register_shutdown_function('session_write_close');
	
	// init session data
	fn_init_user();
}

function fn_api_get_session_cart($lang_code = CART_LANGUAGE)
{
	if (empty($_SESSION['cart'])) {
		return false;
	}

	// fetch cart data
	$cart_data = $_SESSION['cart'];

	return fn_get_as_api_object('cart', $cart_data);
}

function fn_api_get_cart_products($cart_items, $lang_code = CART_LANGUAGE)
{
	if (empty($cart_items)) {
		return array();
	}

	$api_products = array();

	foreach ($cart_items as $item) {
		$product = array (
			'product_id' => $item['product_id'],
			'price' => $item['price'],
			'amount' => $item['amount'],
		);

		if (!empty($item['product_options'])) {

			$product['product_options'] = array();
			foreach ($item['product_options'] as $k => $v) {
				$product['product_options'][] = array (
					'option_id' => $k,
					'value' => $v
				);
			}
		}

		$api_products[] = $product;
	}

	return $api_products;
}

function fn_api_add_product_to_cart($products, &$cart)
{
	$products_data = array();

	foreach ($products as $k => $v) {

		if (!empty($v['product_options'])) {
			$product_options = array();

			foreach ($v['product_options'] as $option) {
				$product_options[$option['option_id']] = $option['value'];
			}

			$v['product_options'] = $product_options;
		}

		$cid = fn_generate_cart_id($v['product_id'], $v);

		if (!empty($products_data[$cid])) {
			$products_data[$cid]['amount'] += $v['amount'];
		}

		$products_data[$cid] = $v;
	}

	$auth = & $_SESSION['auth'];

	// actions copied from the checkout.php 'add' action
	fn_add_product_to_cart($products_data, $cart, $auth);

	fn_save_cart_content($cart, $auth['user_id']);
	fn_calculate_cart_content($cart, $auth, 'S', true, 'F', true);
}

function fn_get_random_ids($qty, $field, $table, $condition = '')
{
	// max quantity of rows in tables to use the mysql rand() 
	// to prevent server load for large tables
	$max_rand_items = 1000; 
	
	if (!empty($condition)) {
		$condition = 'WHERE ' . $condition;
	}

	$total = db_get_field("SELECT COUNT(*) as total FROM $table $condition");
	
	if ($total <= $qty) {
		return db_get_fields("SELECT $field FROM $table $condition");
	}
	
	if ($total < $max_rand_items) {
		return db_get_fields("SELECT $field FROM $table $condition ORDER BY RAND() LIMIT $qty");
	}
	
	$ids = array();
	$rands = array();
	$min_rand = 0;
	$max_rand = (int) $total - 1;
	
	for ($i = 0; $i < $qty; $i++) {
		$rand_num = rand($min_rand, $max_rand);

		while (in_array($rand_num, $rands)) {
			$rand_num++;
			if ($rand_num > $max_rand) {
				$rand_num = $min_rand;
			}
			echo $rand_num . ' <br/> ';
		}

		$rands[] = $rand_num;
		$ids[] = db_get_field("SELECT $field FROM $table $condition LIMIT $rand_num, 1");
	}
	
	return $ids;
}

/*
 * API functions adding data to response
 */
function fn_set_response_pagination(&$response, $set_empty = false)
{
	$pagination = Registry::get('view')->get_template_vars('pagination');
	if (!empty($pagination)) {
		$response->setMeta($pagination['total_pages'], 'total_pages');
		$response->setMeta($pagination['total_items'], 'total_items');
		$response->setMeta($pagination['items_per_page'], 'items_per_page');
		$response->setMeta($pagination['current_page'], 'current_page');
	} elseif ($set_empty) {
		$response->setMeta(0, 'total_pages');
		$response->setMeta(0, 'total_items');
		$response->setMeta(0, 'items_per_page');
		$response->setMeta(0, 'current_page');
	}
}

function fn_set_response_products(&$response, $parmas, $items_per_page = 0, $lang_code = CART_LANGUAGE)
{
	$products = fn_api_get_products($_REQUEST, $items_per_page, $lang_code);
	if (!empty($products)) {
		$response->setResponseList($products);
		if (!empty($_REQUEST['cid'])) {
			$response->setMeta($_REQUEST['cid'], 'category_id');
			$category = !empty($category_descriptions[$_REQUEST['cid']]) ? $category_descriptions[$_REQUEST['cid']] : '';
			$response->setMeta($category, 'category');
		}
	}

	fn_set_response_pagination($response);
}

function fn_set_response_categories(&$response, $params, $items_per_page = 0, $lang_code = CART_LANGUAGE)
{
	if (empty($items_per_page)) {
		$result = fn_api_get_categories($params, $lang_code);
		$response->setMeta(db_get_field("SELECT COUNT(*) FROM ?:categories"), 'total_items');
		$response->setResponseList($result);

	} else {
		$default_params = array (
			'depth' => 0,
			'page' => 1
		);

		$params = array_merge($default_params, $params);
		$params['type'] = 'plain_tree';

		$categories = fn_api_get_categories($params, $lang_code);
			
		if (!empty($categories)) {
			$total = count($categories['category']);
			$params['page'] = !empty($params['page']) ? $params['page'] : 1;
			fn_paginate($params['page'], $total, $items_per_page);
			
			$pagination = Registry::get('view')->get_template_vars('pagination');
			
			$start = $pagination['prev_page'] * $pagination['items_per_page'];
			$end = $start + $items_per_page;
			$result = array();

			for ($i = $start; $i < $end; $i++) {
				if (!isset($categories['category'][$i])) {
					break;
				}

				$result[] = $categories['category'][$i];
			}

			$response->setResponseList(array('category' => $result));
			fn_set_response_pagination($response);
		}

	} 

	$category_id =  !empty($params['id']) ? $params['id'] : 0;

	if (!empty($category_id)) {
		$parent_data = db_get_row("SELECT a.parent_id, b.category FROM ?:categories AS a LEFT JOIN ?:category_descriptions AS b ON a.parent_id = b.category_id WHERE a.category_id = ?i AND b.lang_code = ?s", $category_id, $lang_code);

		if (!empty($parent_data)) {
			$response->setMeta($parent_data['parent_id'], 'grand_id');
			$response->setMeta($parent_data['category'], 'grand_category');
		}

		$response->setMeta($category_id, 'category_id');
		$category = db_get_field("SELECT category FROM ?:category_descriptions WHERE category_id = ?i AND lang_code = ?s", $category_id, $lang_code);
		$response->setMeta($category, 'category_name');
	}	
	
}

function fn_set_response_catalog(&$response, $params, $items_per_page = 0, $lang_code = CART_LANGUAGE)
{
	// supported params:
	// id - category id
	// sort_by - products sort
	// sort_order - products sort order
	// page - products page number
	// items_per_page
	$params['category_id'] = !empty($params['category_id']) ? $params['category_id'] : 0;

	$response->setData($params['category_id'], 'category_id');

	if (empty($params['page']) || $params['page'] == 1) {
		$category_params = array (
			'id' => !empty($params['category_id']) ? $params['category_id'] : 0,
			'type' => 'one_level'
		);

		$categories = fn_api_get_categories($category_params, $lang_code);

		if (!empty($categories['category'])) {
			foreach ($categories['category'] as $k => $v) {

				// Set category icon
				$main_pair = fn_get_image_pairs($v['category_id'], 'category', 'M', true, false, $lang_code);
				if (!empty($main_pair)) {
					$icon = fn_get_api_image_data($main_pair, 'category', 'icon', $params);
					if (!empty($icon)) {
						$categories['category'][$k]['icon'] = fn_get_as_api_object('images', $icon);
					}
				}
			}
			$response->setData($categories['category'], 'subcategories');
		}
	}

	if (!empty($params['category_id'])) {
		// set products
		$params['cid'] = $params['category_id'];
		$products = fn_api_get_products($params, $items_per_page, $lang_code);

		if (!empty($products['product'])) {
			$response->setData($products['product'], 'products');
		}

		fn_set_response_pagination($response, true);
	}

}

function fn_api_get_base_statuses($add_hidden = true, $lang_code = CART_LANGUAGE)
{
	$base_statuses = array (
		array('A', fn_get_lang_var('active', $lang_code), '#97CF4D'),
		array('D', fn_get_lang_var('disabled', $lang_code), '#D2D2D2'),
	);

	if ($add_hidden) {
		$base_statuses[] = array('H', fn_get_lang_var('hidden', $lang_code), '#8D8D8D');
	}

	$api_statuses = array();
	foreach ($base_statuses as $k => $v) {
		$api_statuses[] = array (
			'code' => $v[0],
			'description' => $v[1],
			'color' => $v[2]
		);
	}

	return $api_statuses;
}

function fn_api_get_object($response, $object_type, $params)
{
	$pattern = fn_get_schema('api', $object_type, 'php', false);
	$condition = array();

	if (!empty($pattern['key'])) {
		$api_key_id = current($pattern['key']);
		if ($key_id = $pattern['fields'][$api_key_id]['db_field']) {
			$condition = array($key_id => $params['id']);
		}
	}

	if (empty($condition)) {
		$response->addError('ERROR_WRONG_OBJECT_DATA', str_replace('[object]', $object_type, fn_get_lang_var('wrong_api_object_data')));
		$response->returnResponse();
	}

	$objects = fn_get_api_schema_data($object_type, $condition);

	if (empty($objects)) {
		$response->addError('ERROR_OBJECT_WAS_NOT_FOUND', str_replace('[object]', $object_type, fn_get_lang_var('object_was_not_found')));
		$response->returnResponse();
	}

	$api_data = current($objects[$pattern['object_name']]);
	$response->setData($api_data);
	$response->returnResponse($pattern['object_name']);
}

function fn_get_payment_options($payment_id)
{
	$template =  db_get_field("SELECT template FROM ?:payments WHERE payment_id = ?i", $payment_id);

	if ($template && preg_match('/(.+)\.tpl/', $template, $matches)) {
		return fn_get_schema('api/payments', $matches[1], 'php', false);
	}

	return false;
}

function fn_api_get_credit_cards()
{
	$values = fn_get_static_data_section('C', true, 'credit_card');
	$variants = array();

	foreach ($values as $k => $v) {
		$variants[] = array (
			'variant_id' => $v['param_id'],
			'variant_name' => $v['param'],
			'description' => $v['descr'],
			'position' => $v['position'],
			'display_cvv2' => $v['param_2'],
			'display_start_date' => $v['param_3'],
			'display_issue_number' => $v['param_4'],
		);
	}

	return $variants;
}

function fn_api_update_user($user, &$auth, $notify_user = false)
{
	if (empty($user['user_id']) && empty($user['is_complete_data'])) {
		return false;
	}

	if (!empty($user['profiles'])) {
		$user = array_merge($user, current($user['profiles']));
		unset($user['profiles']);
	}

	if (!empty($user['user_id'])) {
		$user_data = db_get_row("SELECT * FROM ?:users WHERE user_id = ?i", $user['user_id']);
		$user_data = array_merge($user_data, $user);
	} else {
		$user['user_id'] = 0;
		$user_data = $user;
	}

	$user_data['password1'] = !empty($user_data['password1']) ? $user_data['password1'] : '';
	$result = fn_update_user($user['user_id'], $user_data, $auth, true, $notify_user);

	return $result;
}

function fn_set_internal_errors(&$response, $error_code)
{
	$notifications = fn_get_notifications();

	if (empty($notifications)) {
		return false;
	}

	$i = 1;
	foreach ($notifications as $n) {
		if ($n['type'] != 'N') {
			$response->addError($error_code . $i, $n['message']);
			$i++;
		}
	}

	if ($i > 1) {
		return true;
	}

	return false;
}

function fn_api_place_order($data, $response, $skip_payment = false)
{
	$cart = & $_SESSION['cart'];
	$auth = & $_SESSION['auth'];

	if (empty($cart)) {
		$response->addError('ERROR_ACCESS_DENIED', fn_get_lang_var('access_denied', $lang_code));
		$response->returnResponse();
	}

	if (!empty($data['payment_info'])) {
		$cart['payment_id'] = (int) $data['payment_info']['payment_id'];
		unset($data['payment_info']['payment_id']);

		if (!empty($data['payment_info'])) {
			$cart['payment_info'] = $data['payment_info'];
		}

		unset($cart['payment_updated']);
		fn_twg_update_payment_surcharge($cart);

		fn_save_cart_content($cart, $auth['user_id']);
	}

	if (!empty($data['shippings'])) {
		$shipping_ids = array();
		foreach($data['shippings'] as $k => $v) {
			$shipping_ids[] = $v['shipping_id'];
		}

		list ($cart_products, $_SESSION['shipping_rates']) = fn_calculate_cart_content($cart, $auth, 'A', true, 'F', true);
		fn_twg_checkout_update_shipping($cart, $shipping_ids);
	}

	if (empty($cart['shipping']) || empty($cart['payment_info'])) {
		return false;
	}

	if (!empty($data['notes'])) {
		$cart['notes'] = $data['notes'];
	}

	list($order_id, $process_payment) = fn_place_order($cart, $auth);

	if (empty($order_id)) {
		return false;
	}

	if (empty($skip_payment) && $process_payment == true || (!empty($skip_payment) && empty($auth['act_as_user']))) {
	// administrator, logged in as customer can skip payment
		fn_start_payment($order_id);
	}

	fn_twg_order_placement_routines($order_id);

	return $order_id;
}

function fn_api_get_order_details($order_id)
{
	$order_info = fn_get_order_info($order_id);
	if (empty($order_info) || empty($order_info['order_id'])) {
		return false;
	}

	if (!empty($order_info['items'])) {
		$order_info['products'] = array();

		foreach ($order_info['items'] as $product) {
			$order_info['products'][] = $product;
		}
		unset($order_info['items']);
	}

	return fn_get_as_api_object('orders', $order_info);
}

/**
 * Func copies
 */

function fn_twg_order_placement_routines($order_id, $force_notification = array(), $clear_cart = true, $action = '')
{
	// don't show notifications
	// only clear cart
	$order_info = fn_get_order_info($order_id, true);
	$display_notification = true;

	fn_set_hook('placement_routines', $order_id, $order_info, $force_notification, $clear_cart, $action, $display_notification);

	if (!empty($_SESSION['cart']['placement_action'])) {
		if (empty($action)) {
			$action = $_SESSION['cart']['placement_action'];
		}
		unset($_SESSION['cart']['placement_action']);
	}

	if (AREA == 'C' && !empty($order_info['user_id'])) {
		$__fake = '';
		fn_save_cart_content($__fake, $order_info['user_id']);
	}

	$edp_data = fn_generate_ekeys_for_edp(array(), $order_info);
	fn_order_notification($order_info, $edp_data, $force_notification);

	// Empty cart
	if ($clear_cart == true && (substr_count('OP', $order_info['status']) > 0)) {
		$_SESSION['cart'] = array(
			'user_data' => !empty($_SESSION['cart']['user_data']) ? $_SESSION['cart']['user_data'] : array(), 
			'profile_id' => !empty($_SESSION['cart']['profile_id']) ? $_SESSION['cart']['profile_id'] : 0, 
			'user_id' => !empty($_SESSION['cart']['user_id']) ? $_SESSION['cart']['user_id'] : 0,
		);
		
		db_query('DELETE FROM ?:user_session_products WHERE session_id = ?s AND type = ?s', Session::get_id(), 'C');
	}

	fn_set_hook('order_placement_routines', $order_id, $force_notification, $order_info);

}

function fn_twg_checkout_update_shipping(&$cart, $shipping_ids)
{
	// copy of the 'fn_checkout_update_shipping' from the 'customer/checkout.php'
	$cart['shipping'] = array();
	$parsed_data = array();
	foreach ($shipping_ids as $k => $shipping_id) {
		if (strpos($k, ',') !== false) {
			$parsed_data = fn_array_merge($parsed_data, fn_array_combine(fn_explode(',', $k), $shipping_id));
		} else {
			$parsed_data[$k] = $shipping_id;
		}
	}

	foreach ($parsed_data as $k => $shipping_id) {
		if (empty($cart['shipping'][$shipping_id])) {
			$cart['shipping'][$shipping_id] = array(
				'shipping' => $_SESSION['shipping_rates'][$shipping_id]['name'],
			);
		}

		$cart['shipping'][$shipping_id]['rates'][$k] = $_SESSION['shipping_rates'][$shipping_id]['rates'][$k];
	}

	return true;
}

function fn_twg_update_payment_surcharge(&$cart)
{
	// copy of the 'fn_update_payment_surcharge' from the 'customer/checkout.php'
	$cart['payment_surcharge'] = 0;
	if (!empty($cart['payment_id'])) {
		$_data = db_get_row("SELECT a_surcharge, p_surcharge FROM ?:payments WHERE payment_id = ?i", $cart['payment_id']);
		if (floatval($_data['a_surcharge'])) {
			$cart['payment_surcharge'] += $_data['a_surcharge'];
		}
		if (floatval($_data['p_surcharge'])) {
			$cart['payment_surcharge'] += fn_format_price($cart['total'] * $_data['p_surcharge'] / 100);
		}
	}
}

?>