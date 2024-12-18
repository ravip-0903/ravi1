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


function fn_get_short_companies($params = array())
{
	$condition = $limit = $join = $companies = '';

	if (!empty($params['status'])) {
		$condition .= db_quote(" AND ?:companies.status = ?s ", $params['status']);
	}

	if (!empty($params['item_ids'])) {
		$params['item_ids'] = fn_explode(",", $params['item_ids']);
		$condition .= db_quote(" AND ?:companies.company_id IN (?n) ", $params['item_ids']);
	}

	if (!empty($params['displayed_vendors'])) {
		$limit = 'LIMIT ' . $params['displayed_vendors'];
	}

	$condition .= defined('COMPANY_ID') ? fn_get_company_condition('company_id', true, COMPANY_ID) : '';

	fn_set_hook('get_short_companies', $params, $condition, $join, $limit);

	$count = db_get_field("SELECT COUNT(*) FROM ?:companies $join WHERE 1 $condition");

	$_companies = db_get_hash_single_array("SELECT ?:companies.company_id, ?:companies.company FROM ?:companies $join WHERE 1 $condition ORDER BY ?:companies.company $limit", array('company_id', 'company'));

	$companies[0] = Registry::get('settings.Company.company_name');
	$companies = $companies + $_companies;

	$return = array(
		'companies' => $companies,
		'count' => $count,
	);

	if (!empty($params)) {
		unset($return['companies'][0]);
		return array($return);
	}
	return $companies;
}

function fn_get_company_name($company_id, $default_company_id = 'all')
{
	static $cache_names = array();
	
	if ($company_id == '') {
		$company_id = $default_company_id;
	}
	
	if (isset($cache_names[$company_id])) {
		return $cache_names[$company_id];
	}
	
	$_company_name = Registry::get("s_companies.$company_id.company");
	$name = $_company_name ? $_company_name : db_get_field("SELECT company FROM ?:companies WHERE company_id = ?i", $company_id);
	$cache_names[$company_id] = $name;
	
	return $name;
}

function fn_get_companies($params, &$auth, $items_per_page = 0, $lang_code = CART_LANGUAGE)
{       
	// Init filter
	$_view = 'companies';
	$params = fn_init_view($_view, $params);

	// Set default values to input params
	$params['page'] = empty($params['page']) ? 1 : $params['page'];

	// Define fields that should be retrieved
	$fields = array (
		"?:companies.company_id",
		"?:companies.lang_code",
		"?:companies.email",
		"?:companies.company",
		"?:companies.timestamp",
		"?:companies.status",
		"?:companies.logos",
                "?:companies.request_approval_date",
                "?:companies.approved_timestamp",
	);

	// Define sort fields
	$sortings = array (
		'id' => "?:companies.company_id",
		'company' => "?:companies.company",
		'email' => "?:companies.email",
		'date' => "?:companies.timestamp",
		'status' => "?:companies.status",
	);

	$directions = array (
		'asc' => 'asc',
		'desc' => 'desc'
	);

	$condition = $join = $group = '';

	$condition .= fn_get_company_condition('?:companies.company_id');

	$group .= " GROUP BY ?:companies.company_id";

	if (isset($params['company']) && fn_string_no_empty($params['company'])) {
		$condition .= db_quote(" AND ?:companies.company LIKE ?l", "%".trim($params['company'])."%");
	}

	if (!empty($params['status'])) {
		if (is_array($params['status'])) {
			$condition .= db_quote(" AND ?:companies.status IN (?a)", $params['status']);
		} else {
			$condition .= db_quote(" AND ?:companies.status = ?s", $params['status']);
		}
	}

	if (isset($params['email']) && fn_string_no_empty($params['email'])) {
		$condition .= db_quote(" AND ?:companies.email LIKE ?l", "%".trim($params['email'])."%");
	}
        
        if (isset($params['company_id']) && fn_string_no_empty($params['company_id'])) {
		$condition .= db_quote(" AND ?:companies.company_id LIKE ?l", "%".$params['company_id']."%");
	}

	if (isset($params['address']) && fn_string_no_empty($params['address'])) {
		$condition .= db_quote(" AND ?:companies.address LIKE ?l", "%".trim($params['address'])."%");
	}

	if (isset($params['zipcode']) && fn_string_no_empty($params['zipcode'])) {
		$condition .= db_quote(" AND ?:companies.zipcode LIKE ?l", "%".trim($params['zipcode'])."%");
	}

	if (!empty($params['country'])) {
		$condition .= db_quote(" AND ?:companies.country = ?s", $params['country']);
	}

	if (isset($params['state']) && fn_string_no_empty($params['state'])) {
		$condition .= db_quote(" AND ?:companies.state LIKE ?l", "%".trim($params['state'])."%");
	}

	if (isset($params['city']) && fn_string_no_empty($params['city'])) {
		$condition .= db_quote(" AND ?:companies.city LIKE ?l", "%".trim($params['city'])."%");
	}

	if (isset($params['phone']) && fn_string_no_empty($params['phone'])) {
		$condition .= db_quote(" AND ?:companies.phone LIKE ?l", "%".trim($params['phone'])."%");
	}

	if (isset($params['url']) && fn_string_no_empty($params['url'])) {
		$condition .= db_quote(" AND ?:companies.url LIKE ?l", "%".trim($params['url'])."%");
	}

	if (isset($params['fax']) && fn_string_no_empty($params['fax'])) {
		$condition .= db_quote(" AND ?:companies.fax LIKE ?l", "%".trim($params['fax'])."%");
	}
	
	if (isset($params['date_from']) && fn_string_no_empty($params['date_from']) && isset($params['date_to']) && fn_string_no_empty($params['date_to'])) {
		$from_date = fn_parse_date($params['date_from']);
		$to_date = fn_parse_date($params['date_to']);
	
		$condition .= db_quote(" AND ?:companies.timestamp > '".$from_date."' and ?:companies.timestamp < '".$to_date."'");
	}

	if (!empty($params['company_id'])) {
		$condition .= db_quote(' AND ?:companies.company_id IN (?n)', $params['company_id']);
	}

	if (!empty($params['exclude_company_id'])) {
		$condition .= db_quote(' AND ?:companies.company_id != ?n', $params['exclude_company_id']);
	}

	fn_set_hook('get_companies', $params, $fields, $sortings, $condition, $join, $auth, $lang_code, $group);

	if (empty($params['sort_order']) || empty($directions[$params['sort_order']])) {
		$params['sort_order'] = 'asc';
	}

	if (empty($params['sort_by']) || empty($sortings[$params['sort_by']])) {
		$params['sort_by'] = 'company';
	}

	$sorting = (is_array($sortings[$params['sort_by']]) ? implode(' ' . $directions[$params['sort_order']]. ', ', $sortings[$params['sort_by']]) : $sortings[$params['sort_by']]). " " .$directions[$params['sort_order']];

	// Reverse sorting (for usage in view)
	$params['sort_order'] = $params['sort_order'] == 'asc' ? 'desc' : 'asc';
        
        // total companies count
        
        $total_comp_count = db_get_field("SELECT COUNT(DISTINCT(?:companies.company_id)) FROM ?:companies $join WHERE 1");
        
	// Paginate search results
	$limit = '';
	if (!empty($items_per_page)) {
		$total = db_get_field("SELECT COUNT(DISTINCT(?:companies.company_id)) FROM ?:companies $join WHERE 1 $condition");
		$limit = fn_paginate($params['page'], $total, $items_per_page);
	}

	$companies = db_get_array("SELECT " . implode(', ', $fields) . " FROM ?:companies $join WHERE 1 $condition $group ORDER BY $sorting $limit");
        
	return array($companies, $params, $total_comp_count);
}

function fn_get_company_condition($db_field = 'company_id', $and = true, $company = '', $show_admin = false, $area_c = false)
{
	if (PRODUCT_TYPE == 'MULTISHOP' && defined('COMPANY_ID') && (empty($company) || $company == 0)) {
		$company = COMPANY_ID;
	}
	
	$company = ($company === '') ? (defined('COMPANY_ID') ? COMPANY_ID : '') : $company;

	return ($company === '' || $company === 'all' || (AREA == 'C' && !$area_c && PRODUCT_TYPE != 'MULTISHOP')) ? '' : ((($and == true) ? ' AND' : '') . (($show_admin && $company) ? " $db_field IN (0, $company)" : " $db_field = $company"));
}

function fn_get_company_data($company_id, $lang_code = DESCR_SL, $get_description = true)
{       
	if (!empty($company_id)) {

		if ($get_description && (PRODUCT_TYPE == 'MULTIVENDOR' || PRODUCT_TYPE == 'MULTISHOP')) {
			$descriptions_list = "?:company_descriptions.*";
			$field_list = "$descriptions_list, ?:companies.*";
		} else {
			$field_list = "?:companies.*";
		}
		
		$join = '';

		$condition = fn_get_company_condition('?:companies.company_id');
		
		fn_set_hook('get_company_data', $company_id, $field_list, $join, $condition, $lang_code);
		
		if ($get_description && (PRODUCT_TYPE == 'MULTIVENDOR' || PRODUCT_TYPE == 'MULTISHOP')) {
			$company_data = db_get_row("SELECT $field_list FROM ?:companies LEFT JOIN ?:company_descriptions ON ?:company_descriptions.company_id = ?:companies.company_id AND ?:company_descriptions.lang_code = ?s ?p WHERE ?:companies.company_id = ?i $condition", $lang_code, $join, $company_id);
		} else {
			$company_data = db_get_row("SELECT $field_list FROM ?:companies ?p WHERE ?:companies.company_id = ?i $condition", $join, $company_id);
		}

		if (empty($company_data)) {
			return false;
		}

		$company_data['category_ids'] = explode(',', $company_data['categories']);
                // Added by Sudhir
		$company_data['custom_category_ids'] = explode(',', $company_data['custom_categories']);
		// Added by Sudhir end here
               $company_data['shippings_ids'] = explode(',', $company_data['shippings']);
		
		$company_data['logos_data'] = unserialize($company_data['logos']);
		
		$company_data['company_id'] = $company_id;
		
		fn_set_hook('get_company_data_post', $company_data);
	}
       
	return (!empty($company_data) ? $company_data : false);
}

function fn_companies_apply_cart_shipping_rates(&$cart, $cart_products, $auth, &$shipping_rates, $calculate = true)
{

	$cart['use_suppliers'] = false;
	$cart['shipping_failed'] = $cart['company_shipping_failed'] = false;

	// Get suppliers products
	$supplier_products = array();
	$total_freight = 0;
	foreach ($cart_products as $k => $v) {
		$s_id = !empty($v['company_id']) ? $v['company_id'] : 0;
		$supplier_products[$s_id][] = $k;
	}
	if (!empty($supplier_products) && !defined('CACHED_SHIPPING_RATES') && $calculate) {
		$supplier_rates = array();
		foreach ($supplier_products as $rate_id => $products) {
			foreach ($products as $cart_id) {
				if ($cart_products[$cart_id]['free_shipping'] == 'Y' || ($cart_products[$cart_id]['is_edp'] == 'Y' && $cart_products[$cart_id]['edp_shipping'] != 'Y')) { 
					$rate = 0;
				} else {
					//Changes By Megha Sudan
					//to calculate lot shipping for wholesale products
					if($cart_products[$cart_id]['is_wholesale_product'] == 1)
					{
						if($cart_products[$cart_id]['min_qty'] < $cart_products[$cart_id]['amount'])
						{
							$shipping_bunch = (($cart_products[$cart_id]['amount']%$cart_products[$cart_id]['min_qty']) != 0 ? floor($cart_products[$cart_id]['amount']/$cart_products[$cart_id]['min_qty']) + 1 : floor($cart_products[$cart_id]['amount']/$cart_products[$cart_id]['min_qty']));
							$rate = ($cart_products[$cart_id]['shipping_freight'] * $shipping_bunch);
						}
						else
						{
							$rate = $cart_products[$cart_id]['shipping_freight'];	
						}
					}
                                        elseif(fn_check_if_shipping_price_set_for_product($cart_products[$cart_id]['product_id'],$cart_products[$cart_id]['amount'],$auth))
                                        {
                                            
                                            $product_shipping_charge = fn_caclulate_shipping_for_more_quantity($cart_products[$cart_id]['product_id'],$cart_products[$cart_id]['amount']);
                                            
                                            $product_shipping_charge = (empty($product_shipping_charge))? 0 : floatval($product_shipping_charge);

                                            $rate = $product_shipping_charge * $cart_products[$cart_id]['amount'];

                                        }
					else
					{
						$rate = $cart_products[$cart_id]['shipping_freight'] * $cart_products[$cart_id]['amount'];
					}
				}
				empty($supplier_rates[$rate_id]) ? $supplier_rates[$rate_id] = $rate : $supplier_rates[$rate_id] += $rate;
				$total_freight += $rate;
			}
		}
		if (!empty($supplier_rates)) {
			foreach ($shipping_rates as $shipping_id => $shipping) {
				if (!empty($shipping['rates'])) {
					foreach ($shipping['rates'] as $rate_id => $rate) {
						if (isset($supplier_rates[$rate_id])) {
							$shipping_rates[$shipping_id]['rates'][$rate_id] = $rate - $total_freight + $supplier_rates[$rate_id];

						} else {
							unset($shipping_rates[$shipping_id]['rates'][$rate_id]);
						}
					}
				}
			}
		}
	}

	// Add zero rates to free shipping
	foreach ($shipping_rates as $sh_id => $v) {
		if (!empty($v['added_manually'])) {
			$shipping_rates[$sh_id]['rates'] = fn_array_combine(array_keys($supplier_products), 0);
		}
	}

	// If all suppliers should be displayed in one box, filter them

	if (PRODUCT_TYPE != 'MULTIVENDOR' && Registry::get('settings.Suppliers.display_shipping_methods_separately') !== 'Y') {
		$s_ids = array_keys($supplier_products);

		foreach ($shipping_rates as $sh_id => $v) {
			if (sizeof(array_intersect($s_ids, array_keys($v['rates']))) != sizeof($s_ids)) {
				unset($shipping_rates[$sh_id]);
			}
		}
	}
	
	// Get suppliers and determine what shipping methods applicable to them
	$suppliers = array();
	foreach ($supplier_products as $s_id => $p_ids) {
		if (!empty($s_id)) {
			$s_data = fn_get_company_data($s_id);
			$cart['use_suppliers'] = true;
		} else {
			$s_data = array(
				'company' => Registry::get('settings.Company.company_name')
			);
		}

		$suppliers[$s_id] = array (
			'company' => $s_data['company'],
			'products' => $p_ids,
			'rates' => array(),
			'packages_info' => array(),
		);

		// Get shipping methods
		foreach ($shipping_rates as $sh_id => $shipping) {
			if (isset($shipping['rates'][$s_id])) {
				$shipping['rate'] = $shipping['rates'][$s_id];
				unset($shipping['rates']);
				$suppliers[$s_id]['rates'][$sh_id] = $shipping;
			}
		}
	}

	// Select shipping for each supplier
	$cart_shipping = !empty($cart['shipping']) ? $cart['shipping'] : (!empty($cart['chosen_shipping']) ? $cart['chosen_shipping'] : array());
	$cart['shipping'] = array();
	foreach ($suppliers as $s_id => $supplier) {
		
		if (!empty($supplier['products']) && is_array($supplier['products'])) {
			$all_edp_no_shipping = true;
			$all_edp_free_shipping = true;
			$all_free_shipping = true;
			foreach ($supplier['products'] as $pcart_id) {
				$all_edp_no_shipping = $all_edp_no_shipping && ($cart_products[$pcart_id]['is_edp'] == "Y" && $cart_products[$pcart_id]['edp_shipping'] == "N");
				$all_edp_free_shipping = $all_edp_free_shipping && ($cart_products[$pcart_id]['is_edp'] == "Y" && $cart_products[$pcart_id]['edp_shipping'] == "Y" && $cart_products[$pcart_id]['free_shipping'] == "Y");
				$all_free_shipping = $all_free_shipping && ($cart_products[$pcart_id]['is_edp'] == "N" && $cart_products[$pcart_id]['free_shipping'] == "Y");
			}
			$suppliers[$s_id]['all_edp_free_shipping'] = $all_edp_free_shipping;
			$suppliers[$s_id]['all_edp_no_shipping'] = $all_edp_no_shipping;
			$suppliers[$s_id]['all_free_shipping'] = $all_free_shipping;
		}

		if (empty($supplier['rates'])) {
			if (!empty($supplier['products']) && is_array($supplier['products'])) {
				foreach ((array)$supplier['products'] as $pcart_id) {
					if ($cart_products[$pcart_id]['free_shipping'] != "Y" && ($cart_products[$pcart_id]['is_edp'] != "Y" || ($cart_products[$pcart_id]['is_edp'] == "Y" && $cart_products[$pcart_id]['edp_shipping'] == "Y" ))) {
						$cart['shipping_failed'] = $cart['company_shipping_failed'] = true;
						$cart['products'][$pcart_id]['shipping_failed'] = true;
						$suppliers[$s_id]['shipping_failed'] = true;
					} elseif (isset($cart['products'][$pcart_id]['shipping_failed'])) {
						unset($cart['products'][$pcart_id]['shipping_failed']);
					}
				}
			} else {
				$cart['shipping_failed'] = $cart['company_shipping_failed'] = true;
				$suppliers[$s_id]['shipping_failed'] = true;
			}
			continue;
		}

		$sh_ids = array_keys($supplier['rates']);
		$shipping_selected = false;

		// Check if shipping method from this supplier is selected
		foreach ($sh_ids as $sh_id) {
			if (isset($cart_shipping[$sh_id]) && isset($cart_shipping[$sh_id]['rates'][$s_id])) {
				if ($shipping_selected == false) {
					if (!isset($cart['shipping'][$sh_id])) {
						$cart['shipping'][$sh_id] = $cart_shipping[$sh_id];
						$cart['shipping'][$sh_id]['rates'] = array();
					}
					$cart['shipping'][$sh_id]['rates'][$s_id] = $supplier['rates'][$sh_id]['rate']; // set new rate
					$cart['shipping'][$sh_id]['packages_info'] = $shipping_rates[$sh_id]['packages_info'];
					$shipping_selected = true;
				} else {
					//unset($cart['shipping'][$sh_id]['rates'][$s_id]);
				}
			}
		}

		if ($shipping_selected == false) {
			$sh_id = reset($sh_ids);
			if (empty($cart['shipping'][$sh_id])) {
				if (empty($cart_shipping[$sh_id])) {
					$cart['shipping'][$sh_id] = array(
						'shipping' => $supplier['rates'][$sh_id]['name'],
					);
				} else {
					$cart['shipping'][$sh_id] = $cart_shipping[$sh_id];
				}
			}

			$cart['shipping'][$sh_id]['rates'][$s_id] = $supplier['rates'][$sh_id]['rate'];
			$cart['shipping'][$sh_id]['packages_info'] = $shipping_rates[$sh_id]['packages_info'];
		}
	}

	// Calculate total shipping cost
	$cart['shipping_cost'] = 0;
	foreach ($cart['shipping'] as $sh_id => $shipping) {
		$cart['shipping_cost'] += array_sum($shipping['rates']);
	}

	ksort($suppliers);
	Registry::get('view')->assign('suppliers', $suppliers); // FIXME: That's bad...
	Registry::get('view')->assign('supplier_ids', array_keys($suppliers)); // FIXME: That's bad...

	return true;
}


function fn_get_company_id($table, $key, $key_id, $company_id = '')
{
	$condition = ($company_id !== '') ? db_quote(' AND company_id = ?i ', $company_id) : '';
	
	$id = db_get_field("SELECT company_id FROM ?:$table WHERE $key = ?i $condition", $key_id);
	
	return ($id !== NULL) ? $id : false;
}

function fn_check_company_id($table, $key, $key_id, $company_id = '')
{
if (!defined('COMPANY_ID')) {
		return true;
	}

	if ($company_id === '') {
		$company_id = COMPANY_ID;
	}

	$id = db_get_field("SELECT $key FROM ?:$table WHERE $key = ?i AND company_id = ?i", $key_id, $company_id);

	return (!empty($id)) ? true : false;
}

/**
 * Set company_id to actual company_id
 *
 * @param mixed $data Array with data
 */
function fn_set_company_id(&$data, $key_name = 'company_id')
{
	if (defined('COMPANY_ID')) {
		$data[$key_name] = COMPANY_ID;
	} else {
		if (!isset($data[$key_name])) {
			$data[$key_name] = 0;
		}
	}
}

function fn_get_products_companies($products)
{
	$companies = array();

	foreach ($products as $v) {
		$_company_id = !empty($v['company_id']) ? $v['company_id'] : 0;
		$companies[$_company_id] = $_company_id;
	}

	return $companies;
}

function fn_core_delete_shipping($shipping_id)
{
	db_query("UPDATE ?:companies SET shippings = ?p", fn_remove_from_set('shippings', $shipping_id));
}

function fn_companies_suppliers_order_notification($order_info, $order_statuses, $force_notification)
{

	$suppliers = array();

	foreach ($order_info['items'] as $k => $v) {
		if (isset($v['company_id'])) {
			$suppliers[$v['company_id']] = 0;
		}
	}

	if (!empty($suppliers)) {
		if (!empty($order_info['shipping'])) {
			foreach ($order_info['shipping'] as $shipping_id => $shipping) {
				foreach ((array)$shipping['rates'] as $supplier_id => $rate) {
					if (isset($suppliers[$supplier_id])) {
						$suppliers[$supplier_id] += $rate;
					}
				}
			}
		}

		Registry::get('view_mail')->assign('order_info', $order_info);
		Registry::get('view_mail')->assign('status_inventory', $order_statuses[$order_info['status']]['inventory']);
		foreach ($suppliers as $supplier_id => $shipping_cost) {
			if ($supplier_id != 0) {
				$supplier = fn_get_company_data($supplier_id);
				
				Registry::get('view_mail')->assign('shipping_cost', $shipping_cost);
				Registry::get('view_mail')->assign('supplier_id', $supplier_id);
				Registry::get('view_mail')->assign('order_status', fn_get_status_data($order_info['status'], STATUSES_ORDER, $order_info['order_id'], $supplier['lang_code']));
				Registry::get('view_mail')->assign('profile_fields', fn_get_profile_fields('I', '', $supplier['lang_code']));

				fn_send_mail($supplier['email'], Registry::get('settings.Company.company_orders_department'), 'orders/supplier_notification_subj.tpl', 'orders/supplier_notification.tpl', '', $supplier['lang_code'], Registry::get('settings.Company.company_orders_department'));
			}
		}

		return true;
	}

	return false;
}

function fn_companies_suppliers_rma_notification($order_info, $return_info)
{

	$suppliers = array();

	foreach ($order_info['items'] as $k => $v) {
		if (isset($v['company_id'])) {
			$suppliers[$v['company_id']] = 0;
		}
	}

	if (!empty($suppliers)) {
		foreach ($suppliers as $supplier_id => $shipping_cost) {
			if ($supplier_id != 0) {
				$supplier = fn_get_company_data($supplier_id);
				// Translate descriptions to admin language
				Registry::get('view_mail')->assign('return_status', fn_get_status_data($return_info['status'], STATUSES_RETURN, $return_info['return_id'], $supplier['lang_code']));
				fn_send_mail($supplier['email'], Registry::get('settings.Company.company_orders_department'), 'addons/rma/slip_notification_subj.tpl', 'addons/rma/slip_notification.tpl', '', $supplier['lang_code'], array($order_info['email'], Registry::get('settings.Company.company_orders_department')));
			}
		}
		return true;
	}

	return false;
}

function fn_check_suppliers_functionality()
{
	if (PRODUCT_TYPE == 'MULTIVENDOR' || Registry::get('settings.Suppliers.enable_suppliers') == 'Y') {
		return true;
	} else {
		return false;
	}
}

function fn_get_companies_shipping_ids($company_id)
{
	$shippings = array();

	$companies_shippings = explode(',', db_get_field("SELECT shippings FROM ?:companies WHERE company_id = ?i", $company_id));
	$default_shippings = db_get_fields("SELECT shipping_id FROM ?:shippings WHERE company_id = ?i", $company_id);
	$shippings = array_merge($companies_shippings, $default_shippings);

	return $shippings;
}

function fn_check_companies_have_suppliers($companies)
{
	unset($companies[0]);
	return !empty($companies) ? 'Y' : 'N';
}

function fn_update_company($company_data, $company_id = 0, $lang_code = CART_LANGUAGE)
{       
	fn_set_hook('update_company_pre', $company_data, $company_id, $lang_code);
	
	if (PRODUCT_TYPE == 'MULTIVENDOR' && defined('COMPANY_ID')) {
            unset($company_data['comission'], $company_data['comission_type'], $company_data['categories'], $company_data['shippings']);
	}
	
	$_data = $company_data;

	// Check if company with same email already exists
	$is_exist = db_get_field("SELECT email FROM ?:companies WHERE company_id != ?i AND email = ?s", $company_id, $_data['email']);
	if (!empty($is_exist)) {
		fn_save_post_data();
		$_text = (PRODUCT_TYPE == 'MULTIVENDOR' || PRODUCT_TYPE == 'MULTISHOP') ? 'error_vendor_exists' : 'error_supplier_exists';
		fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var($_text));
		return false;
	}

	$_data['shippings'] = empty($company_data['shippings']) ? '' : fn_create_set($company_data['shippings']);
	$_data['shippings'] = '1';
	// add new company
	if (empty($company_id)) {
		// company title can't be empty
		if(empty($company_data['company'])) {
			return false;
		}

		$_data['timestamp'] = TIME;

		$company_id = db_query("INSERT INTO ?:companies ?e", $_data);

		if (empty($company_id)) {
			return false;
		}

		$old_logos = array();
		
		// Adding same company descriptions for all cart languages
		$_data = array(
			'company_id' => $company_id,
			'company_description' => !empty($company_data['company_description']) ? $company_data['company_description'] : '',
		);

		if (PRODUCT_TYPE == 'MULTIVENDOR' || PRODUCT_TYPE == 'MULTISHOP') {
			foreach ((array)Registry::get('languages') as $_data['lang_code'] => $_v) {
				db_query("INSERT INTO ?:company_descriptions ?e", $_data);
			}
		}

	// update company information
	} else {
		if (isset($company_data['company']) && empty($company_data['company'])) {
			unset($company_data['company']);
		}

		if (!empty($_data['status'])) {
			$status_from = db_get_field("SELECT status FROM ?:companies WHERE company_id = ?i", $company_id);
		}
		db_query("UPDATE ?:companies SET ?u WHERE company_id = ?i", $_data, $company_id);

		if (isset($status_from) && $status_from != $_data['status']) {
			fn_companies_change_status($company_id, $_data['status'], '', $status_from, true);
		}

		$old_logos = db_get_field("SELECT logos FROM ?:companies WHERE company_id = ?i", $company_id);
		$old_logos = !empty($old_logos) ? unserialize($old_logos) : array();

		if (PRODUCT_TYPE == 'MULTIVENDOR' || PRODUCT_TYPE == 'MULTISHOP') {
			// Updating company description
			$descr = !empty($company_data['company_description']) ? $company_data['company_description'] : '';
			db_query("UPDATE ?:company_descriptions SET company_description = ?s WHERE company_id = ?i AND lang_code = ?s", $descr, $company_id, DESCR_SL);
		}
	}
	// Do not upload logo if a dummy company is being added.
	if (!empty($_data['email'])) {
		fn_companies_update_logos($company_id, $old_logos);
	}
	
	fn_set_hook('update_company', $company_data, $company_id, $lang_code);
	
	return $company_id;
}

function fn_companies_filter_company_product_categories(&$request, &$product_data)
{
	if (PRODUCT_TYPE == 'MULTIVENDOR' && defined('COMPANY_ID')) {
		$company_data = Registry::get('s_companies.' . COMPANY_ID);
		$company_categories = !empty($company_data['categories']) ? explode(',', $company_data['categories']) : array();
		if (empty($company_categories)) {
			// all categories are allowed
			return true;
		}

		if (!empty($request['category_id']) && !in_array($request['category_id'], $company_categories)) {
			unset($request['category_id']);
			$changed = true;
		}
		if (!empty($product_data['main_category']) && !in_array($product_data['main_category'], $company_categories)) {
			unset($product_data['main_category']);
			$changed = true;
		}
		if (!empty($product_data['add_categories'])) {
			$add_categories = explode(',', $product_data['add_categories']);
			foreach ($add_categories as $k => $v) {
				if (!in_array($v, $company_categories)) {
					unset($add_categories[$k]);
					$changed = true;
				}
			}
			$product_data['add_categories'] = implode(',', $add_categories);
		}
	}
	
	return empty($changed);
}

function fn_companies_get_manifest_definition()
{
	$manifest_definition = fn_get_manifest_definition();

	$available_areas = array('C', 'M', 'A');
	
	foreach ($manifest_definition as $area => $v) {
		if (!in_array($area, $available_areas)) {
			unset($manifest_definition[$area]);
		}
	}

	return $manifest_definition;
}

function fn_companies_update_logos($company_id, $old_logos)
{
	$logotypes = fn_filter_uploaded_data('logotypes');

	$areas = fn_companies_get_manifest_definition();

	// Update company logotypes
	if (!empty($logotypes)) {
		$logos = $old_logos;
		foreach ($logotypes as $type => $logo) {
			$area = $areas[$type];

			$short_name = "company/{$company_id}/{$type}_{$logo['name']}";
			$filename = DIR_IMAGES . $short_name;
			fn_mkdir(dirname($filename));

			if (fn_get_image_size($logo['path'])) {
				if (fn_copy($logo['path'], $filename)) {
					list($w, $h, ) = fn_get_image_size($filename);

					$logos[$area['name']] = array(
						'vendor' => 1,
						'filename' => $short_name,
						'width' => $w,
						'height' => $h,
					);

					//remove old logo
					if (!empty($old_logos[$area['name']]['filename']) && $filename != DIR_IMAGES . $old_logos[$area['name']]['filename']) {
						@unlink(DIR_IMAGES . $old_logos[$area['name']]['filename']);
					}
				} else {
					$text = fn_get_lang_var('text_cannot_create_file');
					$text = str_replace('[file]', $filename, $text);
					fn_set_notification('E', fn_get_lang_var('error'), $text);
				}
			} else {
				$text = fn_get_lang_var('error_file_not_image');
				$text = str_replace('[file]', $filename, $text);
				fn_set_notification('E', fn_get_lang_var('error'), $text);
			}
			@unlink($logo['path']);
		}
		$logos = serialize($logos);
		db_query("UPDATE ?:companies SET logos = ?s WHERE company_id = ?i", $logos, $company_id);
	}

	fn_save_logo_alt($areas, $company_id);
}

function fn_delete_company($company_id)
{
	if (empty($company_id)) {
		return false;
	}

	if (PRODUCT_TYPE == 'MULTIVENDOR') {
		// Do not delete vendor if there're any orders associated with this company
		if (db_get_field("SELECT COUNT(*) FROM ?:orders WHERE company_id = ?i", $company_id)) {
			fn_set_notification('W', fn_get_lang_var('warning'), fn_get_lang_var('unable_delete_vendor_orders_exists'));

			return false;
		}
	}
	
	db_query("DELETE FROM ?:companies WHERE company_id = ?i", $company_id);

	// deleting products
	$product_ids = db_get_fields("SELECT product_id FROM ?:products WHERE company_id = ?i", $company_id);
	foreach ($product_ids as $product_id) {
		fn_delete_product($product_id);
	}

	// deleting shipping
	$shipping_ids = db_get_fields("SELECT shipping_id FROM ?:shippings WHERE company_id = ?i", $company_id);
	foreach ($shipping_ids as $shipping_id) {
		fn_delete_shipping($shipping_id);
	}
	
	if (PRODUCT_TYPE == 'MULTIVENDOR' || PRODUCT_TYPE == 'MULTISHOP') {
		db_query("DELETE FROM ?:company_descriptions WHERE company_id = ?i", $company_id);

		// deleting product_options
		$option_ids = db_get_fields("SELECT option_id FROM ?:product_options WHERE company_id = ?i", $company_id);
		foreach ($option_ids as $option_id) {
			fn_delete_product_option($option_id);
		}

		// deleting company admins
		$user_ids = db_get_fields("SELECT user_id FROM ?:users WHERE company_id = ?i AND user_type = 'A'", $company_id);
		foreach ($user_ids as $user_id) {
			fn_delete_user($user_id);
		}

		// deleting pages
		$page_ids = db_get_fields("SELECT page_id FROM ?:pages WHERE company_id = ?i", $company_id);
		foreach ($page_ids as $page_id) {
			fn_delete_page($page_id);
		}

		// deleting promotions
		$promotion_ids = db_get_fields("SELECT promotion_id FROM ?:promotions WHERE company_id = ?i", $company_id);
		fn_delete_promotions($promotion_ids);
		
		//FIXME: multishop add settings deleting
	}

	fn_set_hook('delete_company', $company_id);

	return true;
}

function fn_chown_company($from, $to)
{
	// Only allow the superadmin to merge vendors

	if (empty($from) || empty($to) || !isset($_SESSION['auth']['is_root']) || $_SESSION['auth']['is_root'] != 'Y' || defined('COMPANY_ID')) {
		return false;
	}

	// Chown & disable vendor's admin accounts
	db_query("UPDATE ?:users SET status = 'D', company_id = ?i WHERE company_id = ?i AND user_type = 'A'", $to, $from);

	$config = Registry::get('config');
	$tables = db_get_fields("SELECT INFORMATION_SCHEMA.COLUMNS.TABLE_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE INFORMATION_SCHEMA.COLUMNS.COLUMN_NAME = 'company_id' AND TABLE_SCHEMA = ?s;", $config['db_name']);

	foreach ($tables as $table) {
		$table = str_replace(TABLE_PREFIX, '', $table);
		if ($table != 'companies' && $table != 'company_descriptions') {
			db_query("UPDATE ?:$table SET company_id = ?i WHERE company_id = ?i", $to, $from);
		}
	}

	return true;
}

/**
 * Function returns address of company and emails of company' departments.
 *
 * @param integer $company_id ID of company
 * @param string $lang_code Language of retrieving data. If null, lang_code of company will be used.
 * @return array Company address, emails and lang_code.
 */
function fn_get_company_placement_info($company_id, $lang_code = null)
{
	if (empty($company_id)) {
		return Registry::get('settings.Company');
	} else {

		$company = fn_get_company_data($company_id, !empty($lang_code) ? $lang_code : CART_LANGUAGE, false);

		$company_placement_info = array(
			'company_state' => $company['state'],
			'company_city' => $company['city'],
			'company_address' => $company['address'],
			'company_phone' => $company['phone'],
			'company_fax' => $company['fax'],
			'company_name' => $company['company'],
			'company_website' => $company['url'],
			'company_zipcode' => $company['zipcode'],
			'company_country' => $company['country'],
			'company_users_department' => $company['email'],
			'company_site_administrator' => $company['email'],
			'company_orders_department' => $company['email'],
			'company_support_department' => $company['email'],
			'company_newsletter_email' => $company['email'],
			'lang_code' => $company['lang_code'],
		);
		
		if (empty($lang_code)) {
			$lang_code = $company['lang_code'];
		}
		
		$company_placement_info['company_country_descr'] = fn_get_country_name($company['country'], $lang_code);
		$company_placement_info['company_state_descr'] = fn_get_state_name($company['state'], $company['country'], $lang_code);

		return $company_placement_info;
	}
}

function fn_get_company_language($company_id)
{
	if (empty($company_id)) {
		return Registry::get('settings.Appearance.admin_default_language');
	} else {
		$company = fn_get_company_data($company_id, DESCR_SL, false);
		return $company['lang_code'];
	}
}

/**
 * Fucntion changes company status. Allowed statuses are A(ctive) and D(isabled)
 *
 * @param int $company_id
 * @param string $status_to A or D
 * @param string $reason The reason of the change
 * @param string $status_from Previous status
 * @param boolean $skip_query By default false. Update query might be skipped if status is already changed.
 * @return boolean True on success or false on failure
 */
function fn_companies_change_status($company_id, $status_to, $reason, &$status_from = '', $skip_query = false, $notify = true)
{         
	if (empty($status_from)) {
		$status_from = db_get_field("SELECT status FROM ?:companies WHERE company_id = ?i", $company_id);
	}
        
	if (!in_array($status_to, array('A', 'P', 'D', 'S', 'R','B','M','E','W','X','Y','Z','H','C','T','F')) || $status_from == $status_to) {
		return false;
	}
        
        if(check_merchant_status_valid_transisiton($status_to,$status_from)){
          
          if($status_to=='R' && $status_from== 'P'){
              if(strrchr(fn_request_high_priority_check($company_id),'A')){
                 $status_to = 'H';
                 $result = $skip_query ? true : db_query("UPDATE ?:companies SET status = ?s WHERE company_id = ?i", $status_to,$company_id);
              //fn_set_notification('N', fn_get_lang_var('notice'), fn_get_lang_var('merchant_move_to_high_prioritystate'), 'K');
                 }else{
                    $request_approval_timestamp= date(time());
	            $result = $skip_query ? true : db_query("UPDATE ?:companies SET status = ?s,request_approval_date = ?s WHERE company_id = ?i", $status_to,$request_approval_timestamp,$company_id);
     
                 }
          }elseif($status_to=='A' && $status_from=='R'){
                
               if(check_tab_approval($company_id)){
                  $request_approval_timestamp=date(time());
                  $result = $skip_query ? true : db_query("UPDATE ?:companies SET status = ?s,approved_timestamp = ?s where company_id = ?i",$status_to,$request_approval_timestamp,$company_id);
             }else{
                   echo fn_set_notification('E', fn_get_lang_var('notice'), fn_get_lang_var('Approve_tab_first'), 'K');
                   return false; 
                }
                
        }elseif(($status_to=='R'||$status_to=='H') && $status_from=='S'){
                    
                     $status_to = 'T';
                     $result = $skip_query ? true : db_query("UPDATE ?:companies SET status = ?s WHERE company_id = ?i", $status_to, $company_id);    
            
              }else{   
                 $result = $skip_query ? true : db_query("UPDATE ?:companies SET status = ?s WHERE company_id = ?i", $status_to, $company_id);    
         } 
          
        }
       
	if (!$result) {
		return false;
	}

	$company_data = fn_get_company_data($company_id);
         
	$account = $username = '';
	if ($status_from == 'N' && ($status_to == 'A' || $status_to == 'P' || $status_to == 'S' || $status_to == 'R')) {
		if (Registry::get('settings.Suppliers.create_vendor_administrator_account') == 'Y') {
			if (!empty($company_data['request_user_id'])) {
				$password_change_timestamp = db_get_field("SELECT password_change_timestamp FROM ?:users WHERE user_id = ?i", $company_data['request_user_id']);
				$_set = '';
				if (empty($password_change_timestamp)) {
					$_set = ", password_change_timestamp = 1 ";
				}
				db_query("UPDATE ?:users SET company_id = ?i, user_type = 'A'$_set WHERE user_id = ?i", $company_id, $company_data['request_user_id']);

				$username = fn_get_user_name($company_data['request_user_id']);
				$account = 'updated';

				$msg = fn_get_lang_var('new_administrator_account_created') . "<a href=?dispatch=profiles.update&user_id=" . $company_data['request_user_id'] . ">" . fn_get_lang_var('you_can_edit_account_details') . '</a>';
				fn_set_notification('N', fn_get_lang_var('notice'), $msg, 'K');

			} else {
				$user_data = array();

				if (!empty($company_data['request_account_name'])) {
					$user_data['user_login'] = $company_data['request_account_name'];
				} else {
					$user_data['user_login'] = $company_data['email'];
				}

				$request_account_data = unserialize($company_data['request_account_data']);
				$user_data['fields'] = $request_account_data['fields'];
				$user_data['firstname'] = $user_data['b_firstname'] = $user_data['s_firstname'] = $request_account_data['admin_firstname'];
				$user_data['lastname'] = $user_data['b_lastname'] = $user_data['s_lastname'] = $request_account_data['admin_lastname'];

				$user_data['user_type'] = 'A';
				$user_data['password1'] = fn_generate_password();
				$user_data['password2'] = $user_data['password1'];
				$user_data['status'] = 'A';
				$user_data['company_id'] = $company_id;
				$user_data['email'] = $company_data['email'];
				$user_data['company'] = $company_data['company'];
				$user_data['last_login'] = 0;
				$user_data['lang_code'] = $company_data['lang_code'];
				$user_data['password_change_timestamp'] = 0;

				// Copy vendor admin billing and shipping addresses from the company's credentials
				$user_data['b_address'] = $user_data['s_address'] = $company_data['address'];
				$user_data['b_city'] = $user_data['s_city'] = $company_data['city'];
				$user_data['b_country'] = $user_data['s_country'] = $company_data['country'];
				$user_data['b_state'] = $user_data['s_state'] = $company_data['state'];
				$user_data['b_zipcode'] = $user_data['s_zipcode'] = $company_data['zipcode'];

				list($added_user_id, $null) = fn_update_user(0, $user_data, $null, false,  false);
                                
				if ($added_user_id) {
					$msg = fn_get_lang_var('new_administrator_account_created') . "<a href=?dispatch=profiles.update&user_id=$added_user_id>" . fn_get_lang_var('you_can_edit_account_details') . '</a>';
					fn_set_notification('N', fn_get_lang_var('notice'), $msg, 'K');

					$username = $user_data['user_login'];
					$account = 'new';
				}
			}
		}       
	}
	
	if (empty($user_data)) {
		$user_id = db_get_field("SELECT user_id FROM ?:users WHERE company_id = ?i AND is_root = 'Y' AND user_type = 'A'", $company_id);
		$user_data = fn_get_user_info($user_id);
	}
	
	if ($notify && !empty($company_data['email'])) {
		$view_mail = & Registry::get('view_mail');
		$view_mail->assign('company_data', $company_data);
		$view_mail->assign('user_data', $user_data);
		$view_mail->assign('reason', $reason);
		$view_mail->assign('status', fn_get_lang_var($status_to == 'A' ? 'active' : 'disabled'));
                
                if (!empty($company_data['seo_name'])){
                  if (fn_product_count($company_data['company_id']) < 25){
                   $seo_url=Registry::get('config.current_location')."/"."!"."/".$company_data['seo_name'];
                  }else{
                   $seo_url=Registry::get('config.current_location')."/".$company_data['seo_name'];
                  }
                } else {
                  $seo_url='';
                }
               
                $view_mail->assign('seo_url', $seo_url);
                
		if ($status_from == 'N' && ($status_to == 'A' || $status_to == 'P' || $status_to == 'S' || $status_to == 'R')) {
			$view_mail->assign('username', $username);
			$view_mail->assign('account', $account);
			if ($account == 'new') {
				$view_mail->assign('password', $user_data['password1']);
			}
		}

		$mail_template = strtolower($status_from . '_' . $status_to);

		//fn_send_mail($company_data['email'], Registry::get('settings.Company.company_support_department'), 'companies/status_' . $mail_template . '_notification_subj.tpl', 'companies/status_' . $mail_template . '_notification.tpl', '', CART_LANGUAGE);
		fn_instant_mail($company_data['email'], Registry::get('settings.Company.company_support_department'), 'companies/status_' . $mail_template . '_notification_subj.tpl', 'companies/status_' . $mail_template . '_notification.tpl');
	}

	// Added by Sudhir dt 8th nov 2012 to log merchant change status history bigin here
		$user = $_SESSION['auth'];
		db_query("INSERT INTO clues_merchant_status_history (company_id, from_status, to_status, changed_by, reason) VALUES ('".$company_id."', '".$status_from."',  '".$status_to."', '".$user['user_id']."', '".$reason."' )");
	// Added by Sudhir dt 8th nov 2012 to log merchant change status history end here
             
	return $result;
}

function fn_get_company_by_product_id($product_id)
{
	return db_get_row("SELECT * FROM ?:companies AS com LEFT JOIN ?:products AS prod ON com.company_id = prod.company_id WHERE prod.product_id = ?i", $product_id);
}

function fn_core_get_products(&$params, &$fields, &$sortings, &$condition, &$join, &$sorting, &$group_by, $lang_code)
{
	// code for products filter by company (supplier or vendor)
	if (fn_check_suppliers_functionality()) {
		if (isset($params['company_id']) && $params['company_id'] != '') {
			$params['company_id'] = intval($params['company_id']);
			$condition .= db_quote(' AND products.company_id = ?i ', $params['company_id']);
		}
	}
}

function fn_get_companies_sorting($simple_mode = true)
{
	$sorting = array(
		'company' => array('description' => fn_get_lang_var('name'), 'default_order' => 'asc'),
	);
	
	fn_set_hook('companies_sorting', $sorting);
	if ($simple_mode) {
		foreach ($sorting as &$sort_item) {
			$sort_item = $sort_item['description'];
		}
	}
	
	return $sorting;
}

function fn_helpdesk_process_messages($messages)
{
	if (!empty($messages)) {
		$messages_queue = fn_get_storage_data('hd_messages');
		if (empty($messages_queue)) {
			$messages_queue = array();
		} else {
			$messages_queue = unserialize($messages_queue);
		}
		
		foreach ($messages->Message as $message) {
			$message_id = empty($message->Id) ? intval(fn_crc32(microtime()) / 2) : (string) $message->Id;
			$message = array(
				'type' => empty($message->Type) ? 'W' : (string) $message->Type,
				'title' => (empty($message->Title)) ? fn_get_lang_var('notice') : (string) $message->Title,
				'text' => (string) $message->Text,
			);
			
			$messages_queue[$message_id] = $message;
		}
		
		fn_set_storage_data('hd_messages', serialize($messages_queue));
	}
}

/**
 * 
 * Gets company manifest from ini file by company id
 * @param int $company_id Id of company
 * @return array of manifest data
 */
function fn_get_company_manifest($company_id){
	// If name of settings skin_name_admin	or skin_name_customer will be changed. Please fix code below.
	if (AREA == 'A') {
		$area = 'admin';
	} else {  
		$area = 'customer';
	}
	return fn_get_manifest($area, CART_LANGUAGE, $company_id);
}

    function fn_get_company_status($company_id)
{        
	$company_status = db_get_row("SELECT status FROM ?:companies WHERE company_id = ?i",$company_id);
        $company_status = db_get_row("select status_group as status  from clues_status_types where status='".$company_status['status']."' and object_type='M' ");
        return $company_status['status'];
}

function fn_update_billing_companies_comission($comission_data,$company_id ){
    $replaceValues = '';
    $daleted = db_query("DELETE FROM clues_billing_companies_commission where company_id = $company_id");
    if(is_array($comission_data)) {
        foreach($comission_data as $k => $v) {
             $replaceValues .= "('$company_id','$k','$v'),";
        }
        $replaceValues = rtrim($replaceValues,',');
        return db_query("INSERT INTO clues_billing_companies_commission (company_id,billing_category_id,selling_fee_rate) values $replaceValues");
    }
    return $daleted;
}
function fn_get_billing_companies_commission_count($company_id){
    return db_get_row("SELECT count(company_id) as total FROM clues_billing_companies_commission WHERE company_id = $company_id");
}

/* To check whether merchant has changed password */
function fn_password_change_status($company_id){
    $password_change = false;
    
    if($company_id){
                  $password_data = db_get_field("select password_change_timestamp from cscart_users where company_id='".$company_id."'");
                  
                  if($password_data > 1){
                      $password_change= true;
                  }
    }
    
    return $password_change;
}

/*Count how many products merchant has uploaded*/

function fn_product_count($company_id){
      
    if($company_id){
        $product_count = db_get_field("select count(product_id) from cscart_products where company_id='".$company_id."'");
    }
    
        
    return $product_count;
  
}

// To check the successful entries of store steps
function fn_store_status($company_id){
    
   $firststepFinished = false;
   $secondstepFinished = false;
   $thirdstepFinished = false;
   $fourthstepFinished = false;
   
   $company_data = !empty($company_id) ? fn_get_company_data($company_id) : array();
   $warehouse_data = !empty($company_id) ? fn_get_warehouse_data($company_id) : array();
   $company_bank_data = !empty($company_id) ? fn_get_company_bank_data($company_id) : array();
   $billing_companies_commission_count = !empty($company_id) ? fn_get_billing_companies_commission_count($company_id) : array();
    if($company_data['company']!='' && $company_data['phone']!='' && $company_data['email']!='' && $company_data['address']!='' && $company_data['city']!='' && $company_data['state']!='' && $company_data['zipcode']!='' && $warehouse_data['warehouse_address1']!='' && $warehouse_data['warehouse_city']!='' && $warehouse_data['warehouse_state']!='' && $warehouse_data['warehouse_pin']!='' && $warehouse_data['warehouse_pcontact_name']!='' && $warehouse_data['warehouse_pcontact_phone']!='' && $warehouse_data['warehouse_pcontact_email']!=''){
        $firststepFinished = true;
    }
    if($company_bank_data['account_name']!='' && $company_bank_data['bank_name']!='' && $company_bank_data['branch_address']!='' && $company_bank_data['city']!='' && $company_bank_data['state']!='' && $company_bank_data['zipcode']!='' && $company_bank_data['account_number']!='' && $company_bank_data['account_type']!='' && $company_bank_data['ifsc_code']!='' && ($company_bank_data['vat_cst_number']!='' || $company_bank_data['annual_turnover']=='0_5L')){
        $secondstepFinished = true;
    }
    if($company_data['company']!='' && $company_data['company_description']!='' || $company_data['logos']!=''){
        $thirdstepFinished = true;
    }
    if($billing_companies_commission_count['total'] > 0){
        $fourthstepFinished = true;
    }
    
    $store_status['firststepFinished']=$firststepFinished;
    $store_status['secondstepFinished']=$secondstepFinished;
    $store_status['thirdstepFinished']=$thirdstepFinished;
    $store_status['fourthstepFinished']=$fourthstepFinished;
    
    
    return $store_status;
}

function fn_merchant_approval($company_id){
         
         $company_data = !empty($company_id) ? fn_get_company_data($company_id) : array();

         $emailStatus ='';
         
         if(strrchr(fn_request_high_priority_check($company_id),'A')){
             $status = 'H';
            }else{
             $status = 'R';
          }
           
         if (fn_companies_change_status($company_id, $status,'')) {
                $searchData = array('{company}','{updated_on}','{url}');
                $replaceData = array($company_data['company'],date("j M, Y",$company_data['timestamp']),Registry::get('config.domain_url')."/UniTechCity.php?dispatch=companies.update&company_id=".$company_data['company_id']);
                $body = str_replace($searchData,$replaceData,fn_get_lang_var("approval_request_body"));
                $emailStatus = sendElasticEmail(Registry::get('settings.Company.company_approval_department'),fn_get_lang_var("approval_request_subject"),$body,$body,$company_data['email'],$company_data['company'],false);
               }
                if ($emailStatus) {
                           return fn_set_notification('N', fn_get_lang_var('notice'), fn_get_lang_var('approval_request_sent'));
                    } else {
                           return fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('error_sending_approval_request'), 'I');
                    }

}

function fn_get_merchant_company_name($company_id){
    
    if($company_id){
        
        $merchant_name = db_get_field("select company from cscart_companies where company_id='".$company_id."'");
    }
      
    return $merchant_name;
    
}

function fn_update_agreement($agree_check,$company_id,$action_date,$name){
   
    if($company_id){
        
         $update_agreement = db_query("UPDATE cscart_companies set user_accepted='".$agree_check."',user_accepted_timestamp='".$action_date."', user_accepted_name='".$name."' where company_id=".$company_id.""); 
    }
    
}

function check_last_completed_steps($company_id){
    
   $store_status = fn_store_status($company_id);
   $password_status = fn_password_change_status($company_id);
   
   if(!$password_status){
       $data['lang']=fn_get_lang_var('click_here_now');
       $data['url']='vendor.php?dispatch=storesetup.change_password';
   }elseif(!$store_status['firststepFinished']){
       $data['lang']=fn_get_lang_var('finish_first');
       $data['url']='vendor.php?dispatch=storesetup.first_step';
   }elseif(!$store_status['secondstepFinished']){
       $data['lang']=fn_get_lang_var('finish_second');
       $data['url']='vendor.php?dispatch=storesetup.second_step';
   }elseif(!$store_status['thirdstepFinished']){
       $data['lang']=fn_get_lang_var('finish_third');
       $data['url']='vendor.php?dispatch=storesetup.third_step';
   }elseif(!$store_status['thirdstepFinished']){
       $data['lang']=fn_get_lang_var('finish_fourth');
       $data['url']='vendor.php?dispatch=storesetup.fourth_step';
   }else{
       $data['lang']=fn_get_lang_var('upload_product');
       $data['url']='vendor.php?dispatch=products.manage';
   }
   
   return $data;
}

function status_back_to_pending($company_id,$status){
    
    if(!empty($company_id)){
        
        $change_status = db_query("UPDATE cscart_companies set status='".$status."' where company_id=".$company_id."");
   }

}

// Last product sold by merchant

function merchant_last_product_sold($company_id){
    
    if(!empty($company_id)){
        $product_name = db_get_field("SELECT cpd.product FROM cscart_orders o , cscart_order_details co,cscart_product_descriptions cpd where o.order_id=co.order_id and co.product_id=cpd.product_id and o.company_id=".$company_id." order by o.timestamp desc limit 0,1");
    }
    
    return $product_name;
}

// Merchant at shopclues since and their location

function merchant_duration_location($company_id){
    
    if(!empty($company_id)){
        
        $merchant_info = db_get_array("SELECT u.timestamp,c.city FROM cscart_users u, cscart_companies c where u.company_id=c.company_id and u.company_id=".$company_id."");
    
    }
    
    return $merchant_info;
}

// Total product sold

function total_product_sold($company_id){
    
    if(!empty($company_id)){
        
        $total_product_sold = db_get_field("select count(od.product_id) from cscart_orders o, cscart_order_details od where o.order_id=od.order_id and o.company_id=".$company_id." AND o.status NOT IN ('F','I','N','D','T','Y','O')");
    }
    
    return $total_product_sold;
 }

function fn_new_url($url){
     
     $url=explode('/',$url);
     $newurl = '/'.'!'.'/'.$url[1];
    
     return $newurl;
}

function fn_change_password_merchant($company_id,$new_password,$password_confirm){
    
        if(!empty($company_id)){
                if(!empty($new_password) && !empty($password_confirm)){
                    if($new_password== $password_confirm){
                       
                       $timestamp=date(time());
                       $pass_status = db_query("UPDATE cscart_users SET password = '".md5($new_password)."',password_change_timestamp ='".$timestamp."' WHERE company_id=".$company_id."");
                        if ($pass_status){
                            fn_set_notification('N', fn_get_lang_var('notice'), fn_get_lang_var('password_updated'),'I');
                                } else {
                            fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('password_updation_failed'), 'I');
                            }
                      } 
                    }
                }
}

function fn_billing_commission($company_id){
    
    if(!empty($company_id)){
     
    $billing_category = db_get_array("SELECT cb.category,cb.default_commision from clues_billing_companies_commission cbc,clues_billing_categories cb where cbc.billing_category_id=cb.id and company_id=".$company_id."");
    
    }
    
    return $billing_category;
}

function send_forgot_password_mail($user_id){
    
    if(!empty($user_id)){
        $email = db_get_field("select email from cscart_users where user_id=".$user_id."");
        $pass=random_string();
        
        $update_pass= db_query("update cscart_users set password='".md5($pass)."' where user_id=".$user_id."");
         Registry::get('view_mail')->assign('password', $pass);
         Registry::get('view_mail')->assign('email', $email);
        if($update_pass){
            fn_instant_mail($email,  Registry::get('settings.Company.company_support_department'),'companies/forgot_password_subj.tpl','companies/forgot_password.tpl');
        }
    }
    
    return true;
}

function random_string()
{
    $character_set_array = array();
    $character_set_array[] = array('count' => 7, 'characters' => 'abcdefghijklmnopqrstuvwxyz');
    $character_set_array[] = array('count' => 1, 'characters' => '0123456789');
    $temp_array = array();
    foreach ($character_set_array as $character_set) {
        for ($i = 0; $i < $character_set['count']; $i++) {
            $temp_array[] = $character_set['characters'][rand(0, strlen($character_set['characters']) - 1)];
        }
    }
    shuffle($temp_array);
    return implode('', $temp_array);
}

function fn_catalog_data_for_merchant($company_id){
    
    if(!empty($company_id)){
     
    $catalog_data = db_get_array("SELECT cp.product_id,cp.product_code,cp.status,cp.list_price,cp.amount,cp.last_update,cd.product,cpp.price FROM cscart_products cp , cscart_product_descriptions cd, cscart_product_prices cpp where cp.product_id=cd.product_id and cp.product_id=cpp.product_id and cp.company_id=".$company_id."");
    }
    
    return $catalog_data;
}

function fn_insert_shipping_rate($company_id,$fee_prepaid_flatrate=0,$fee_cod_flatrate=0,$table_rate_type="STD"){
    
    $count = db_get_row("select count(company_id) as count from clues_billing_companies_shipping_rel where company_id=".$company_id."");
    
    if($count['count'] > 0){
        
         db_query("UPDATE clues_billing_companies_shipping_rel SET fee_prepaid_flatrate=".$fee_prepaid_flatrate.",fee_cod_flatrate=".$fee_cod_flatrate.", table_rate_type='".$table_rate_type."'where company_id=".$company_id.""); 
    }else{
        
        db_query("insert into clues_billing_companies_shipping_rel(company_id,fee_prepaid_flatrate,fee_cod_flatrate,table_rate_type) values(".$company_id.",".$fee_prepaid_flatrate.",".$fee_cod_flatrate.",'".$table_rate_type."')");
    }
    
}

function fn_change_password_users($user_id,$new_password,$password_confirm){
    
        if(!empty($user_id)){
                if(!empty($new_password) && !empty($password_confirm)){
                    if($new_password== $password_confirm){
                       
                       $timestamp=date(time());
                       $pass_status = db_query("UPDATE cscart_users SET password = '".md5($new_password)."',password_change_timestamp ='".$timestamp."' WHERE user_id=".$user_id."");
                        if ($pass_status){
                            fn_set_notification('N', fn_get_lang_var('notice'), fn_get_lang_var('password_updated'),'I');
                                } else {
                            fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('password_updation_failed'), 'I');
                            }
                      } 
                    }
                }
}

/* Storing UniTechCity tab and section approval and disapproval data */

function merchant_section_approval($company_id,$tab,$section,$status,$user_id,$timestamp){
   
    $count = db_get_row("select count(company_id) as count from clues_merchant_section_approval where sections='".$section."' and company_id =".$company_id."");
   
   if($count['count'] > 0){
       
       db_query("UPDATE clues_merchant_section_approval SET company_id = ".$company_id.",tab='".$tab."',sections='".$section."',approval_status=".$status.",user_id=".$user_id.",timestamp=".$timestamp." WHERE company_id=".$company_id." and sections='".$section."'");
       
   }else{
      
       db_query("insert into clues_merchant_section_approval(company_id,tab,sections,approval_status,user_id,timestamp) values(".$company_id.",'".$tab."','".$section."',".$status.",".$user_id.",".$timestamp.")"); 
   }
   
   return true;
}

function merchant_tab_approval($company_id,$tab,$value,$user_id,$timestamp,$notes){

    $count = db_get_row("select count(company_id) as count from clues_merchant_tab_approval where tab='".$tab."' and company_id =".$company_id."");
    
    if($count['count'] > 0){
         
        db_query("UPDATE clues_merchant_tab_approval SET company_id = ".$company_id.",tab='".$tab."',approval_status=".$value.",user_id=".$user_id.",timestamp=".$timestamp.",notes='".$notes."' WHERE company_id=".$company_id." and tab='".$tab."'");
     
    }else{
     
        db_query("insert into clues_merchant_tab_approval(company_id,tab,approval_status,user_id,timestamp,notes) values(".$company_id.",'".$tab."',".$value.",".$user_id.",".$timestamp.",'".$notes."')"); 
   }
   
   return true;
}

function get_merchant_section_approval_data($company_id,$sections){
 
    $approval_data = db_get_row("select approval_status from clues_merchant_section_approval where sections='".$sections."'and company_id =".$company_id."");
    
    return $approval_data;
}

function get_merchant_tab_approval_data($company_id,$tab){
    
    $tab_approval_data = db_get_row("select approval_status from clues_merchant_tab_approval where company_id =".$company_id." and tab='".$tab."'");

    return $tab_approval_data;
}

function merchant_tab_approved_by($company_id,$tab){
    
    $tab_approved_by = db_get_row("select notes,approval_status,user_id,timestamp from clues_merchant_tab_approval where company_id=".$company_id." and tab='".$tab."'");
   
    if(!empty($tab_approved_by['user_id'])){
        $user_name = db_get_row("select firstname,lastname from cscart_users where user_id=".$tab_approved_by['user_id']."");
    }
    $user_name['timestamp']=date("Y-m-d H:i:s",$tab_approved_by['timestamp']);
    $user_name['notes']=$tab_approved_by['notes'];
    $user_name['name']=$user_name['firstname'].' '.$user_name['lastname'];
    
    return $user_name;
}

function merchant_section_approved_by($company_id,$section){
    
    $section_approved_by = db_get_row("select approval_status,user_id,timestamp from clues_merchant_section_approval where company_id=".$company_id." and sections='".$section."'");
   
    if(!empty($section_approved_by['user_id'])){
        $user_name = db_get_row("select firstname,lastname from cscart_users where user_id=".$section_approved_by['user_id']."");
    }
    
    $section_data['timestamp']=date("Y-m-d H:i:s",$section_approved_by['timestamp']);
    $section_data['username']=$user_name['firstname'].' '.$user_name['lastname'];
   // $user_name['notes']=$tab_approved_by['notes'];
   
    return $section_data;
}

function merchant_fulfillment_type($company_id){
    
    return db_get_row("select fulfillment_id  from cscart_companies where company_id=".$company_id."");
}

function check_tab_approval($company_id){
    
   $val['payment_tab']= get_merchant_tab_approval_data($company_id,"PaymentTab");
   $val['WarehouseTab']= get_merchant_tab_approval_data($company_id,"WarehouseTab");
   $val['FeesAndAgreeementTab']= get_merchant_tab_approval_data($company_id,"FeesAndAgreeementTab");
   $val['StoreSetup']= get_merchant_tab_approval_data($company_id,"StoreSetup");
   
   if($val['payment_tab']['approval_status']==1 && $val['WarehouseTab']['approval_status']==1 && $val['FeesAndAgreeementTab']['approval_status']==1 && $val['StoreSetup']['approval_status']==1){
       
       return true;
   } else {
       
       return false;
   }
   
 }

 /* Function created by Raj Kumar on 15-11-2012 to show merchant product statistics */
 
 function merchant_store_statistics($company_id){
     
        $store_statistics = db_get_row("SELECT
                                                        sum(pp.viewed) clicked,
                                                        sum(pp.added) added_to_cart, 
                                                        sum(pp.deleted) deleted, 
                                                        sum(o1.units_sold) sold,
                                                        (sum(o1.units_sold)/sum(pp.viewed)) * 100.00 c2c,
                                                        (sum(pp.added)/sum(pp.viewed)) * 100.00 c2k,
                                                        (sum(o1.units_sold)/sum(pp.added)) * 100 k2s
                                                        FROM 
                                                        cscart_product_popularity pp 
                                                        LEFT JOIN (select product_id, sum(amount) as units_sold
                                                        from cscart_orders o, cscart_order_details od
                                                        where od.order_id = o.order_id
                                                        and o.company_id = ".$company_id."
                                                        and o.status not in ('F','I','N','D','Z','Y', 'T', 'M')
                                                        group by product_id
                                                        ) o1 ON o1.product_id = pp.product_id
                                                        INNER JOIN cscart_products p ON p.product_id = pp.product_id
                                                        INNER JOIN cscart_companies c ON c.company_id = p.company_id and c.company_id = ".$company_id."
                                                        INNER JOIN cscart_products_categories pc on pc.product_id = pp.product_id AND link_type = 'M'
                                                        INNER JOIN cscart_categories cat ON cat.category_id = pc.category_id
                                          " );
        
    return $store_statistics;
 }
 
 /* Show Merchant Global Statistics on UniTechCity Pages*/
 
 function store_statistics_global($company_id){
     
    $global_store_statistics = db_get_row("SELECT  sum(pp.viewed) clicked, 
                                            sum(pp.added) added_to_cart, 
                                            sum(pp.deleted) deleted, 
                                            sum(o1.units_sold) sold,
                                            (sum(o1.units_sold)/sum(pp.viewed)) * 100.00 c2c,
                                            (sum(pp.added)/sum(pp.viewed)) * 100.00 c2k,
                                            (sum(o1.units_sold)/sum(pp.added)) * 100 k2s
                                            FROM 
                                            (select product_id, sum(amount) as units_sold
                                            from cscart_orders o, cscart_order_details od
                                            where od.order_id = o.order_id
                                            and o.status not in ('F','I','N','D','Z','Y', 'T', 'M')
                                            group by product_id
                                            ) o1
                                            LEFT JOIN cscart_product_popularity pp ON pp.product_id = o1.product_id
                                            INNER JOIN cscart_products p ON p.product_id = o1.product_id
                                            LEFT JOIN cscart_companies c ON c.company_id = p.company_id
                                            LEFT JOIN  cscart_products_categories pc on pc.product_id = o1.product_id AND link_type = 'M'
                                            LEFT JOIN cscart_categories cat ON cat.category_id = pc.category_id
                                            ");
   
    return $global_store_statistics;

 }
 
 
 function status_total(){
     
     $all_status = db_get_row("SELECT sum(if(c.status='A',1,0)) as active,sum(if(c.status='D',1,0)) as disabled,
                                 sum(if(c.status='R',1,0)) as request_approval,sum(if(c.status='P',1,0)) as pending,
                                 sum(if(c.status='B',1,0)) as new,sum(if(c.status='M',1,0)) as new1,
                                 sum(if(c.status='S',1,0)) as suspended,sum(if(c.status='D',1,0)) as disabled,
                                 sum(if(c.status='H',1,0)) as highpriority,sum(if(c.status='E',1,0)) as backtopending,
                                 sum(if(c.status='C',1,0)) as merchantactionrequired,sum(if(c.status='W',1,0)) as policyviolation,
                                 sum(if(c.status='X',1,0)) as categorynotserved,sum(if(c.status='Y',1,0)) as nss,
                                 sum(if(c.status='Z',1,0)) as standardnotmet,sum(if(c.status='N',1,0)) as newlegacy,
                                 sum(if(c.status='F',1,0)) as storeoffline, sum(if(c.status='T',1,0)) as rasuspended
                                 FROM `cscart_companies` c");
     
     return $all_status;
 }
 
 /* Add Merchant Notes created by Raj Kumar*/
 
 function fn_merchant_notes($objecttype,$notetype,$company_id,$notes,$date_created,$user_id){
    
     if(!empty($company_id) && !empty($user_id)){
        
         db_query("insert into clues_notes(object_type,note_type,object_id,notes,date_created,created_by) values('".$objecttype."','".$notetype."',".$company_id.",'".$notes."',".$date_created.",".$user_id.")");
        
     }
     
     return true;
 }
 
 function fetch_merchant_notes($company_id,$type,$notetype){
     
     if(!empty($company_id)){
         
         $notes_data= db_get_array("select clues_notes.*,concat(cscart_users.firstname,' ',cscart_users.lastname) as created_by_username from clues_notes join cscart_users on (clues_notes.created_by = cscart_users.user_id) where clues_notes.object_id=".$company_id." and clues_notes.object_type='".$type."' and clues_notes.note_type='".$notetype."' order by clues_notes.date_created desc limit 0,5");
        
         
     }
     
     return $notes_data;
 }
 
 function merchant_microsite_url($company_id){
    
     if(!empty($company_id)){
         
         $data=fn_get_company_data($company_id);
         $count=fn_product_count($company_id);
         
         
         if($count < 25){
             $seo_url=Registry::get('config.current_location')."/"."!"."/".$data['seo_name'];
         }else{
             $seo_url=Registry::get('config.current_location')."/".$data['seo_name'];
         }
         
     }
     
     return $seo_url;
     
 }
 
 
 function fn_get_company_user_data($user_id){
     
     if(!empty($user_id)){
      $data = db_get_row("select email,phone,company_id, concat(firstname,' ',lastname) as name from cscart_users where user_id=".$user_id.""); 
     }
     
     return $data;
 }
 
 function select_issues(){
     
     $issues = db_get_array("select name , issue_id,allow_free_text from clues_issues where parent_issue_id=0 and type='M'");
     
     return $issues;
 }
 
 function fn_get_subissues($parent_id){
     
     $subissues = db_get_array("select name,issue_id,allow_free_text from clues_issues where parent_issue_id=".$parent_id." and type = 'M'");
     
     return $subissues;
 }
 
 function fn_request_high_priority_check($company_id){
     
     $status = db_get_row("select group_concat(distinct to_status) as status from clues_merchant_status_history where company_id=".$company_id."");
     
     return $status['status'];
 }
 
 function check_merchant_status_valid_transisiton($status_to,$status_from){
     
     $status = db_get_row("select * from clues_status_transition where to_status='".$status_to."' and from_status='".$status_from."' and type='M'");

     if(!empty($status)){
         return true;
     }else{
         return false;
     }
     
     }
     
   // Based on merchant status return merchant status descriptions
     
     function merchant_status_descriptions($company_id){
         
        if(!empty($company_id)){
            $company_status = db_get_row("SELECT status FROM ?:companies WHERE company_id = ?i",$company_id);
            
            $status_descriptions = db_get_row("select description from cscart_status_descriptions where status='".$company_status['status']."' and type='M'");
            $status_descriptions["company_id"]=$company_status['status'];
        }
        
        return $status_descriptions;
     }
     
     function all_merchant_status_descriptions(){
         
         $all_status_descriptions = db_get_array("select description,status from cscart_status_descriptions where type='M'");
         
         return $all_status_descriptions;
     }
     
     function merchant_message_alert($user_id,$m_id,$company_id,$limit,$order,$order_by){
          
           if(!empty($company_id)){
               
              $sql= "SELECT m.timestamp,m.subject,m.id,mt.Name, 'Y' as mail_opened , m.attachment 
                                                            FROM clues_merchant_messages m,clues_message_types mt 
                                                            where mt.id=m.message_type_id and m.message_type_id!=10
                                                            and m.id not in 
                                                            (select message_id from clues_message_recipients)
                                                            UNION
                                                            SELECT m.timestamp,m.subject,m.id,mt.Name,r.mail_opened,m.attachment 
                                                            FROM clues_message_recipients r,clues_merchant_messages m,clues_message_types mt 
                                                            where mt.id=m.message_type_id  and m.message_type_id!=10 and m.id=r.message_id and r.user_id =".$user_id."";
                         
              if(!empty($m_id)){
                  
                    $sql.=" and m.message_type_id =".$m_id.""; 
                    
              }                                                                                                         
            
              if(empty($order_by)){
                  
                  $sql.="  order by 1 desc " ;
                  
              }else{
                  $sql.=" order by ".$order_by." ".$order."  ";
              }
              
                  if(!empty($limit)){
                      
                      $sql.=" limit $limit";
                  }
                   
           } else {
                
                $sql = "SELECT m.timestamp,m.subject,m.id,m.attachment,mt.Name FROM clues_merchant_messages m,clues_message_types mt where mt.id=m.message_type_id and m.message_type_id!=10 ";
                
                  if(!empty($m_id)){
                      
                    $sql.=" and m.message_type_id =".$m_id.""; 
              }
              
                if(empty($order_by)){

                    $sql.="  order by m.timestamp desc" ;

                }else{
                    
                    $sql.=" order by ".$order_by." ".$order."  ";
                    
                }
                 
                  
           }   
           
           $message_data = db_get_array($sql);
                
            return $message_data;
         
     }
     
     function fn_update_mail_opened($status,$user_id,$message_id){
         
         $sql = "update clues_message_recipients set mail_opened='".$status."' where user_id=".$user_id." and message_id=".$message_id."";
        
         db_query($sql);
         
         return true;
     }
     
     function fn_mail_message_functionality($message_id){
         
         $mail_data =  db_get_row("select mm.subject , mm.body , u.email , mm.attachment from clues_merchant_messages mm , cscart_users u  where mm.id=". $message_id." and mm.created_by=u.user_id");
    
         return $mail_data;       
         
         }
         
    function fn_mail_category_count($user_id,$company_id){
      
       if(empty($company_id)){
           
            $sql = "SELECT mt.Name as name,m.message_type_id as m_id,count(m.message_type_id) as message_type_count  FROM clues_message_recipients r, clues_merchant_messages m,clues_message_types mt 
                                                                where mt.id = m.message_type_id  and m.message_type_id!=10 ";
          
                 }else{
            
            $sql=" SELECT mt.Name as name,m.message_type_id as m_id,count(m.message_type_id) as message_type_count  FROM clues_merchant_messages m,clues_message_types mt 
                                                                where mt.id = m.message_type_id and m.message_type_id!=10 and m.id not in 
                                                            (select message_id from clues_message_recipients) group by m.message_type_id
                                                                union
                                                                 select mt.Name as name , m.message_type_id as m_id , count(m.message_type_id) as message_type_count
                                                                 from clues_message_recipients r, clues_merchant_messages m, clues_message_types mt
                                                                 where mt.id = m.message_type_id and m.message_type_id!=10 and  m.id=r.message_id and r.user_id=".$user_id." ";
          
      }
            
            $sql.="group by m.message_type_id";
            
            $count_values = db_get_array($sql);
            
            return $count_values;
              
    }
    
    
    function fn_seller_connect($parent_id,$customer_id,$merchant_id,$product_id,$subject,$message,$topic,$timestamp,$open_timestamp,$direction){
        
        $sql = "insert into clues_seller_connect(parent_id,customer_id,merchant_id,product_id,subject,message,topic,timestamp,open_timestamp,direction) 
                                                              values (".$parent_id.",".$customer_id.",".$merchant_id.",".$product_id.",'".$subject."','".$message."','".$topic."',".$timestamp.",".$open_timestamp.",'".$direction."')";
        
        $last_insert = db_query($sql);
        
        //return mysql_insert_id();
        
        return $last_insert;

    }
    
    function fn_update_thread_timestamp($thread_id,$open_timestamp){
        
        $sql = "update clues_seller_connect set open_timestamp=".$open_timestamp." where thread_id=".$thread_id."";
        
        db_query($sql);
        
    }
    
    function fn_update_child_thread_timestamp($thread_id,$open_timestamp){
       
        $sql = "update clues_seller_connect set open_timestamp=".$open_timestamp." where parent_id=".$thread_id."";
        
        db_query($sql);
        
        
    }
    
    function get_merchant_name_date_acceptance($company_id) {
            
        $merchant_agree = db_get_row("select user_accepted_name,user_accepted_timestamp from cscart_companies
                                      where company_id=".$company_id);
    
        
        return $merchant_agree;
        
    }
    
    function get_asked_query_all_status(){
        
      $asked_query_data = db_get_row("select sum(if(parent_id=0,1,0)) as total_asked, 
                                      sum(if(open_timestamp=0 and parent_id =0,1,0)) as not_replied,
                                      sum(if(parent_id!=0,1,0)) as replied
                                      from clues_seller_connect");
      
      return $asked_query_data;
        
    }
    
    ?>
