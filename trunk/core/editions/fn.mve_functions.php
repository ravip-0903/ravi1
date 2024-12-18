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
// $Id: fn.mve_functions.php 12865 2011-07-05 06:57:22Z 2tl $
//

/* HOOKS */

function fn_mve_get_product_filter_fields(&$fields)
{
	$fields['S'] = array (
		'db_field' => 'company_id',
		'table' => 'products',
		'description' => 'vendor',
		'condition_type' => 'F',
		'range_name' => 'company',
		'foreign_table' => 'companies',
		'foreign_index' => 'company_id'
	);
}

function fn_mve_place_order($order_id, $action, $__order_status, $cart)
{
	$order_info = fn_get_order_info($order_id);
	if ($order_info['is_parent_order'] != 'Y') {
		// Check if the order already placed
		$payout_id = db_get_field('SELECT payout_id FROM ?:vendor_payouts WHERE order_id = ?i', $order_id);
		
		$company_data = fn_get_company_data($order_info['company_id'], DESCR_SL, false);
		$commission_amount = 0;
		
		if ($company_data['commission_type'] == 'P') {
			//Calculate commission amount and check if we need to include shipping cost
			$commission_amount = (($order_info['total'] - (Registry::get('settings.Suppliers.include_shipping') == 'N' ?  $order_info['shipping_cost'] : 0)) * $company_data['commission'])/100;
		} else {
			$commission_amount = $company_data['commission'];
		}

		//Check if we need to take payment surcharge from vendor
		if (Registry::get('settings.Suppliers.include_payment_surcharge') == 'Y') {
			$commission_amount += $order_info['payment_surcharge'];
		}

		$_data = array(
			'company_id' => $order_info['company_id'],
			'order_id' => $order_id,
			'payout_date' => TIME,
			'start_date' => TIME,
			'end_date' => TIME,
			'commission' => $company_data['commission'],
			'commission_type' => $company_data['commission_type'],
			'order_amount' => $order_info['total'],
			'commission_amount' => $commission_amount
		);

		fn_set_hook('mve_place_order', $order_info, $company_data, $action, $__order_status, $cart, $_data);

		if ($commission_amount > $order_info['total']) {
			$commission_amount = $order_info['total'];
		}

		if (empty($payout_id)) {		
			db_query('INSERT INTO ?:vendor_payouts ?e', $_data);
		} else {
			db_query('UPDATE ?:vendor_payouts SET ?u WHERE payout_id = ?i', $_data, $payout_id);
		}
	}
}

function fn_mve_delete_category($category_id)
{
	db_query("UPDATE ?:companies SET categories = ?p", fn_remove_from_set('categories', $category_id));
}

function fn_mve_export_process($pattern, $export_fields, $options, $conditions, $joins, $table_fields, $processes)
{
	if (defined('COMPANY_ID')) {
		if ($pattern['section'] == 'products') {
			// Limit scope to the current vendor's products only (if in vendor mode)
			$company_condition = fn_get_company_condition('products.company_id', false);
			if (!empty($company_condition)) {
				$conditions[] = $company_condition;
			}
		}
		
		if ($pattern['section'] == 'products' && $pattern['pattern_id'] == 'product_combinations') {
			$joins[] = 'INNER JOIN ?:products AS products ON (products.product_id = product_options_inventory.product_id)';	
		}
		
		if ($pattern['section'] == 'orders') {
			$company_condition = fn_get_company_condition('orders.company_id', false);
			
			if (!empty($company_condition)) {
				$conditions[] = $company_condition;
				
				if ($pattern['pattern_id'] == 'order_items') {
					$joins[] = 'INNER JOIN ?:orders AS orders ON (order_details.order_id = orders.order_id)';
				}
			}
		}
		
		if ($pattern['section'] == 'users') {
			$company_condition = fn_get_company_condition('orders.company_id', false);
			
			if (!empty($company_condition)) {
				$u_ids = db_get_fields('SELECT users.user_id FROM ?:users AS users LEFT JOIN ?:orders AS orders ON (users.user_id = orders.user_id) WHERE ' . $company_condition . ' GROUP BY users.user_id');
			}
			
			$conditions[] = db_quote('users.user_id IN (?a)', $u_ids);
			
		}
	}
}

function fn_mve_import_process_data($primary_object_id, $v, $pattern, $options, $processed_data, $processing_groups, $skip_record)
{
	static $company_categories     = null;
	static $company_categories_ids = null;
	
	if (defined('COMPANY_ID')) {
		unset($v['company']);
		if ($pattern['section'] == 'products' && in_array($pattern['pattern_id'], array('products', 'product_images', 'qty_discounts'))) {
			// Check the product data
			if ($pattern['pattern_id'] == 'products') {
				$v['company_id'] = COMPANY_ID;
				// Check the category name
				if (!empty($v['Category'])) {
					if (strpos($v['Category'], $options['category_delimiter']) !== false) {
						$paths = explode($options['category_delimiter'], $v['Category']);
						array_walk($paths, 'fn_trim_helper');
					} else {
						$paths[] = $v['Category'];
					}
					
					if (!empty($paths)) {
						$parent_id = 0;
						foreach ($paths as $category) {
							$category_id = db_get_field("SELECT ?:categories.category_id FROM ?:category_descriptions INNER JOIN ?:categories ON ?:categories.category_id = ?:category_descriptions.category_id WHERE ?:category_descriptions.category = ?s AND lang_code = ?s AND parent_id = ?i", $category, $options['lang_code'], $parent_id);
							if (empty($category_id)) {
								$skip_record = true;
								return false;
							}
							$parent_id = $category_id;
						}
						if ($company_categories === null) {
							$company_categories = Registry::get('s_companies.' . COMPANY_ID . '.categories');
							$company_categories_ids = explode(',', $company_categories);
						}
						$allow = empty($company_categories) || in_array($parent_id, $company_categories_ids);
						
						if (!$allow) {
							$skip_record = true;
							return false;
						}
					}
				}
			}
			
			if (!empty($primary_object_id)) {
				list($field, $value) = each($primary_object_id);
				$company_id = db_get_field('SELECT company_id FROM ?:products WHERE ' . $field . ' = ?s', $value);
				
				if ($company_id != COMPANY_ID) {
					$processed_data['S']++;
					$skip_record = true;
				}
			}
		} elseif ($pattern['section'] == 'products' && $pattern['pattern_id'] == 'product_combinations') {
			if (empty($primary_object_id) && empty($v['product_id'])) {
				$processed_data['S']++;
				$skip_record = true;
				
				return false;
			}
			
			If (!empty($primary_object_id)) {
				list($field, $value) = each($primary_object_id);
				$company_id = db_get_field('SELECT company_id FROM ?:products WHERE ' . $field . ' = ?s', $value);
			} else {
				$company_id = db_get_field('SELECT company_id FROM ?:products WHERE product_id = ?i', $v['product_id']);
			}
			
			if ($company_id != COMPANY_ID) {
				$processed_data['S']++;
				$skip_record = true;
			}
		}
	}
}

function fn_mve_set_admin_notification($auth)
{
	if ($auth['company_id'] == 0 && fn_check_permissions('companies', 'manage_vendors', 'admin')) {

		$count = db_get_field("SELECT COUNT(*) FROM ?:companies WHERE status IN ('N', 'P')");

		if ($count > 0) {
			$msg = fn_get_lang_var('text_not_approved_vendors');
			$msg = str_replace(']', '</a>', $msg);
			$msg = str_replace('[', '<a href="' . fn_url('companies.manage?status[]=N&status[]=P') . '">', $msg);
			fn_set_notification('W', fn_get_lang_var('notice'), $msg, 'K');
		}
	}
}

function fn_mve_get_companies(&$params, &$fields, &$sortings, &$condition, &$join, &$auth, &$lang_code)
{
	if (!empty($params['get_description'])) {
		$fields[] = '?:company_descriptions.company_description';
		$join .= db_quote(' LEFT JOIN ?:company_descriptions ON ?:company_descriptions.company_id = ?:companies.company_id AND ?:company_descriptions.lang_code = ?s ', CART_LANGUAGE);
	}
}

function fn_mve_delete_order($order_id)
{
	$parent_id = db_get_field("SELECT parent_order_id FROM ?:orders WHERE order_id = ?i", $order_id);
	if ($parent_id) {
		$count = db_get_field("SELECT COUNT(*) FROM ?:orders WHERE parent_order_id = ?i", $parent_id);
		if ($count == 1) { //this is the last child order, so we can delete the parent order.
			fn_delete_order($parent_id);
		}
	}
}

/* FUNCTIONS */

function fn_get_cart_key($cartid, $profileid, $merged_cart){
    foreach($merged_cart as $i => $v){
        if(($v['profile_id'] == $profileid) && ($v['cart_id'] == $cartid)){
            return $i;
        }
    }
    return false;
}

function fn_mashipping_merge_products(&$cart){
    if(!$cart['multiple_shipping_addresses']){
        return;
    }
    
    $i = 0;
    $merged_cart = array();
    foreach($cart['new_cart']['cart_to_show'] as $value)
    {
        if(($key = fn_get_cart_key($value['cart_id'], $value['profile_id'], $merged_cart)) !== false)
        {
            $merged_cart[$key]['amount'] += $value['amount'];
        }
        else
        {
            $merged_cart[$i]=$value;
        }
        $i++;
    }
    
    $cart['new_cart']['cart_to_show'] = $merged_cart;
}

function fn_companies_place_suborders($order_id, $cart, &$auth, $action)
{
    fn_mashipping_merge_products($cart);
    
	foreach ($cart['companies'] as $company_id) {
		$_cart = $cart;
		foreach ($_cart['products'] as $product_id => $product) {
			if ($product['company_id'] != $company_id) {
				unset($_cart['products'][$product_id]);
			}
                        else{
                                $company_calculations[$product['company_id']]['product_count'] += $product['amount'];
                                $company_calculations[$product['company_id']]['price'] += ($product['price'] + $product['shipping_freight']) * $product['amount'];
                        }
		}

                if($_cart['multiple_shipping_addresses']){
                    $ks = array_keys($_cart['products']);
                    foreach($ks as $current_product_cart_id){
                        foreach($_cart['new_cart']['cart_to_show'] as $saved_cproduct){
                            if($current_product_cart_id == $saved_cproduct['cart_id']){
                                $_ma_cart = $_cart;
                                foreach ($_ma_cart['products'] as $_ma_cart_id => $_ma_product) {
                                        if ($_ma_cart_id != $current_product_cart_id) {
                                                unset($_ma_cart['products'][$_ma_cart_id]);
                                        }
                                }
$log['cart_id'] = $current_product_cart_id;
                                $_product_count = $_ma_cart['products'][$current_product_cart_id]['amount'];
$log['_product_count'] = $_product_count;
                                $_ma_cart['products'][$current_product_cart_id]['amount'] = $saved_cproduct['amount'];
                                $_ma_cart['products'][$current_product_cart_id]['mashipping_profile_id'] = $saved_cproduct['profile_id'];
                                
                                $_ma_product = $_ma_cart['products'][$current_product_cart_id];
                                $ma_extra = &$_ma_cart['products'][$current_product_cart_id]['extra'];
$log['points_info'] = $ma_extra['points_info'];
                                
                                $product_cost = ($_ma_product['price'] + $_ma_product['shipping_freight']) * $_ma_product['amount'];
$log['product_cost'] = $product_cost;
                                $_total_company_part = ($_ma_product['price'] + $_ma_product['shipping_freight']) * $_product_count;
                                $_total_company_part = $company_calculations[$company_id]['price'];
                                if(is_array($_ma_cart['company_discount']) && count($_ma_cart['company_discount']) >= 1){
                                    $_company_discount = &$_ma_cart['company_discount'][$_ma_cart['products'][$current_product_cart_id]['company_id']];
$log['_company_discount_before_adjust']= $_company_discount;
                                    $div = $product_cost/$_total_company_part;
$log['div'] = $div;

                                    //Product Amount
                                    $_company_discount['total_company_part'] = $product_cost;
                                    
                                    //Coupon Code
                                    fn_multiply_and_adjust_decimal('total_company_discount_part', $_company_discount['total_company_discount_part'], $div, $company_id);
                                    
                                    //Earned Clues Bucks
                                    if(isset($ma_extra['points_info']['reward']) && $ma_extra['points_info']['reward'] > 0){
                                        fn_multiply_and_adjust_decimal('reward', $ma_extra['points_info']['reward'], $div, $company_id);
                                    }
                                    
                                    //Applied Clues Bucks
                                    if(isset($ma_extra['points_info']['discount']) && $ma_extra['points_info']['discount'] > 0){
                                        fn_multiply_and_adjust_decimal('discount', $ma_extra['points_info']['discount'], $_ma_product['amount']/$_product_count, $company_id);
                                    }

                                    $ks = array_keys($_ma_cart['shipping']);
                                    $_ma_cart['shipping'][$ks[0]]['rates'][$company_id] = $_ma_product['shipping_freight'];

                                    //EMI
                                    fn_multiply_and_adjust_decimal('total_company_emi_part', $_ma_cart['company_discount'][$company_id]['total_company_emi_part'], $div, $company_id);
                                    
                                    //Gift Certificate
                                    foreach($_ma_cart['use_gift_certificates'] as $gk => &$gv){
$log['_gift_certificate_before_adjust']= $gv;
                                        $_SESSION[$gk]['parent_gc_amount'] = $gv['amount'];
                                        //$_SESSION[$gk]['company_part_for_gc'] = $div;
                                        fn_multiply_and_adjust_decimal('gc_amount', $gv['amount'], $div, $company_id);
                                        fn_multiply_and_adjust_decimal('gc_cost', $gv['cost'], $div, $company_id);
$log['_gift_certificate_after_adjust']= $gv;
                                    }
                                }
$log['_company_discount_after_adjust']= $_company_discount;
LogMetric::dump_log(array_keys($log), array_values($log));
                                fn_place_suborder($order_id, $_ma_cart, $auth, $action, $company_id);
                            }
                        }
                    }
                }
                else{
                    fn_place_suborder($order_id, $_cart, $auth, $action, $company_id);
                }

       }
}

function fn_multiply_and_adjust_decimal($adjust_name, &$adjust, $divisor, $company_id){
    static $adjustments = array();
    
    $mul = $adjust * $divisor;
    $whole = floor($mul);
    $decimal = $mul - $whole;
    
    $adjust = $whole;
    $adjust_store = '__' . $company_id . '_' . $adjust_name . "_to_adjust";
    if($adjustments[$adjust_store]){
        $adjust = $adjust  + $adjustments[$adjust_store] + $decimal;
        $adjustments[$adjust_store] = 0;
    }
    else{
        $adjustments[$adjust_store] = $decimal;
    }
}

function fn_place_suborder($order_id, $_cart, &$auth, $action, $company_id){
        $rewrite_order_id = empty($_cart['rewrite_order_id']) ? array() : $_cart['rewrite_order_id'];
        $_auth = & $auth;
        $total_products_price = 0;
        $total_shipping_cost = 0;
        $total_company_part = 0;

        //changed by ankur to calculate the company cart with product amount
        $ks = array_keys($_cart['products']);
        $cid = $ks[0];
        $total_products_price += $_cart['products'][$cid]['price']*$_cart['products'][$cid]['amount'];

        foreach ($_cart['shipping'] as $s_id => $shipping) {
                $total_shipping_cost += !empty($shipping['rates'][$company_id]) ? $shipping['rates'][$company_id] : 0;
        }

        $total_company_part = (($total_products_price + $total_shipping_cost)*100) / ($_cart['subtotal'] + $_cart['shipping_cost']);

        //code by ankur for calculating company part for only GC order
        if($total_company_part==0 && empty($_cart['products']) && !empty($_cart['gift_certificates']))
        {
                $purchas_all_gc_total=0;
                foreach($_cart['gift_certificates'] as $id=>$value)
                {
                        $purchas_all_gc_total+=$value['amount'];
                }
                $total_company_part=($purchas_all_gc_total*100)/($_cart['subtotal'] + $_cart['shipping_cost']);
        }
        /* code by ankur for Gift Certificates */
         if(isset($_cart['use_gift_certificates']))
         {
                 foreach($_cart['use_gift_certificates'] as $code=>$value)
                 {
                        $_SESSION[$code]['company_part_for_gc']=$total_company_part; 
                 }
         }
        /* code end */
        $_cart['payment_surcharge'] = $total_company_part * $_cart['payment_surcharge'] / 100;
        $_cart['recalculate'] = true;
        $_cart['rewrite_order_id'] = array();
        if ($next_id = array_shift($rewrite_order_id)) {
                $_cart['rewrite_order_id'][] = $next_id;
        }


        fn_calculate_cart_content($_cart, $_auth);

        fn_place_order($_cart, $_auth, $action, $order_id);
}

function fn_check_addon_permission($addon)
{
	$schema = fn_get_schema('permissions', 'vendor');
	$schema = $schema['addons'];

	if (isset($schema[$addon]['permission'])) {
		$permission = $schema[$addon]['permission'];
	}

	return isset($permission) ? $permission : true;
}

function fn_company_access_denied_notification($save_post_data = true)
{
	if ($save_post_data) {
		fn_save_post_data();
	}
	fn_set_notification('W', fn_get_lang_var('warning'), fn_get_lang_var('access_denied'));
}

function fn_companies_get_payouts($params = array())
{
	$params = fn_init_view('balance', $params);
	
	$default_params = array(
		'sort_by' => 'vendor',
		'sort_order' => 'asc',
	);

	$params = array_merge($default_params, $params);

	$fields = array();
	$join = ' ';
	
	// Define sort fields
	$sortings = array(
		'sort_vendor' => 'companies.company',
		'sort_period' => 'payouts.start_date',
		'sort_amount' => 'payout_amount',
		'sort_date' => 'payouts.payout_date',
	);

	$directions = array(
		'asc' => 'asc',
		'desc' => 'desc'
	);
	
	$condition = $date_condition = ' 1 ';
	
	// Set default values to input params
	$params['page'] = empty($params['page']) ? 1 : $params['page']; // default page is 1
	$params['items_per_page'] = empty($params['items_per_page']) ? Registry::get('settings.Appearance.admin_elements_per_page') : $params['items_per_page'];
	
	$join .= ' LEFT JOIN ?:orders AS orders ON (payouts.order_id = orders.order_id)';
	$join .= ' LEFT JOIN ?:companies AS companies ON (payouts.company_id = companies.company_id)';
	
	if (!isset($sortings[$params['sort_by']])) {
		$params['sort_by'] = 'sort_vendor';
	}
	
	// If the sales period not defined, specify it as 'All'
	if (empty($params['time_from']) && empty($params['time_to'])) {
		$params['period'] = 'A';
	}
	
	if (empty($params['time_from']) && empty($params['period'])) {
		$params['time_from'] = mktime(0, 0, 0, date('n', TIME), 1, date('Y', mktime()));
	} elseif (!empty($params['time_from'])) {
		$params['time_from'] = fn_parse_date($params['time_from']);
	} else {
		$time_from = true;
	}
	
	if (empty($params['time_to']) && empty($params['period'])) {
		$params['time_to'] = mktime();
	} elseif (!empty($params['time_to'])) {
		$params['time_to'] = fn_parse_date($params['time_to']) + 24 * 60 * 60 - 1; //Get the day ending time
	} else {
		$time_to = true;
	}
	
	if (isset($time_from) || isset($time_to)) {
		$dates = db_get_row('SELECT MIN(start_date) AS time_from, MAX(end_date) AS time_to FROM ?:vendor_payouts');
		if (isset($time_from)) {
			$params['time_from'] = $dates['time_from'];
		}
		if (isset($time_to)) {
			$params['time_to'] = $dates['time_to'];
		}
	}
	
	// Order statuses condition
	$statuses = db_get_fields('SELECT status FROM ?:status_data WHERE `type` = ?s AND param = ?s AND `value` = ?s', 'O', 'calculate_for_payouts', 'Y');
	if (!empty($statuses)) {
		$condition .= db_quote(' AND (orders.status IN (?a) OR payouts.order_id = 0)', $statuses);
	}
	
	$date_condition .= db_quote(' AND ((payouts.start_date >= ?i AND payouts.end_date <= ?i AND payouts.order_id != ?i) OR (payouts.order_id = ?i AND (payouts.start_date BETWEEN ?i AND ?i OR payouts.end_date BETWEEN ?i AND ?i)))', $params['time_from'], $params['time_to'], 0, 0, $params['time_from'], $params['time_to'], $params['time_from'], $params['time_to']);
	
	// Filter by the transaction type
	if (!empty($params['transaction_type']) && ($params['transaction_type'] == 'income' || $params['transaction_type'] == 'expenditure')) {
		if ($params['transaction_type'] == 'income') {
			$condition .= ' AND (payouts.order_id != 0 OR payouts.payout_amount > 0)';
		} else {
			$condition .= ' AND payouts.payout_amount < 0';
		}
	}
	
	// Filter by vendor
	if (defined('COMPANY_ID')) {
		$params['vendor'] = COMPANY_ID;
	}
	if (isset($params['vendor']) && $params['vendor'] != 'all') {
		$condition .= db_quote(' AND payouts.company_id = ?i', $params['vendor']);
	}
	
	if (!empty($params['payment'])) {
		$condition .= db_quote(' AND payouts.payment_method like ?l', '%' . $params['payment'] . '%');
	}
	
	$params['sort_order'] = $params['sort_order'] == 'asc' ? 'desc' : 'asc';
	
	$sorting = $sortings[$params['sort_by']] . ' ' . $directions[$params['sort_order']];
	
	$limit = '';
	if (!empty($params['items_per_page'])) {
		$limit = fn_paginate($params['page'], 0, $params['items_per_page'], true);
	}

	$items = db_get_array("SELECT SQL_CALC_FOUND_ROWS * FROM ?:vendor_payouts AS payouts $join WHERE $condition AND $date_condition GROUP BY payouts.payout_id ORDER BY $sorting $limit");

	if (!empty($params['items_per_page'])) {
		$_total = db_get_found_rows();
		fn_paginate($params['page'], $_total, $params['items_per_page']);
	}
	
	
	// Calculate balance for the selected period
	$total = array(
		'BCF' => 0, //Ballance carried forward
		'NO' => 0, // New orders
		'TPP' => 0, // Total period payouts
		'LPM' => 0, // Less Profit Margin
		'TOB' => 0, // Total outstanding balance
	);

	$bcf_query = db_quote("SELECT SUM(payouts.order_amount) - SUM(payouts.payout_amount) * (-1) - SUM(payouts.commission_amount) AS BCF FROM ?:vendor_payouts AS payouts $join WHERE $condition AND payouts.start_date < ?i", $params['time_from']);
	$current_payouts_query = db_quote("SELECT SUM(payouts.order_amount) AS NO, SUM(payouts.payout_amount) * (-1) AS TTP, SUM(payouts.order_amount) - SUM(payouts.commission_amount) + SUM(payouts.payout_amount) AS LPM FROM ?:vendor_payouts AS payouts LEFT JOIN ?:orders AS orders ON (payouts.order_id = orders.order_id) WHERE $condition AND $date_condition");
	$payouts_query = db_quote("SELECT payouts.*, companies.company, IF(payouts.order_id <> 0,orders.total,payouts.payout_amount) AS payout_amount, IF(payouts.order_id <> 0, payouts.end_date, '') AS date FROM ?:vendor_payouts AS payouts $join WHERE $condition AND $date_condition GROUP BY payouts.payout_id ORDER BY $sorting $limit");

	fn_set_hook('mve_companies_get_payouts', $bcf_query, $current_payouts_query, $payouts_query, $join, $total, $condition, $date_condition);

	$payouts = db_get_array($payouts_query);
	$total['BCF'] += db_get_field($bcf_query);

	$current_payouts = db_get_row($current_payouts_query);

	$total['NO'] = $current_payouts['NO'];
	$total['TPP'] = $current_payouts['TTP'];
	$total['LPM'] = $current_payouts['LPM'];
	$total['TOB'] += fn_format_price($total['BCF'] + $total['LPM']);
	$total['LPM'] = $total['LPM'] < 0 ? 0 : $total['LPM'];
	
	$total['new_period_date'] = db_get_field('SELECT MAX(end_date) FROM ?:vendor_payouts');
	
	return array($payouts, $params, $total);
}

function fn_companies_delete_payout($ids)
{
	if (is_array($ids)) {
		db_query('DELETE FROM ?:vendor_payouts WHERE payout_id IN (?a)', $ids);
	} else {
		db_query('DELETE FROM ?:vendor_payouts WHERE payout_id = ?i', $ids);
	}
}

function fn_companies_add_payout($payment)
{
	$_data = array(
		'company_id' => $payment['vendor'],
		'payout_date' => TIME, // Current timestamp
		'start_date' => fn_parse_date($payment['start_date']),
		'end_date' => fn_parse_date($payment['end_date']),
		'payout_amount' => $payment['amount'] * (-1),
		'payment_method' => $payment['payment_method'],
		'comments' => $payment['comments'],
	);
	
	if ($_data['start_date'] > $_data['end_date']) {
		$_data['start_date'] = $_data['end_date'];
	}
	
	db_query('INSERT INTO ?:vendor_payouts ?e', $_data);
	
	if (isset($payment['notify_user']) && $payment['notify_user'] == 'Y') {
		$company_data = fn_get_company_data($payment['vendor'], DESCR_SL, false);
		if (!empty($company_data['email'])) {
			$view_mail = Registry::get('view_mail');
			$view_mail->assign('company_data', $company_data);
			$view_mail->assign('payment', $payment);
			fn_send_mail($company_data['email'], Registry::get('settings.Company.company_support_department'), 'companies/payment_notification_subj.tpl', 'companies/payment_notification.tpl', '', $company_data['lang_code']);
		}
	}
}

function fn_company_products_check($product_ids, $notify = false)
{
	if (!empty($product_ids)) {
		$c = db_get_field("SELECT count(*) FROM ?:products WHERE product_id IN (?n) ?p", $product_ids, fn_get_company_condition('?:products.company_id'));
		if (count((array)$product_ids) == $c) {
			return true;
		} else {
			if ($notify) {
				fn_company_access_denied_notification(false);
			}
			return false;
		}
	}
	
	return true;
}

function fn_get_company_customers_ids($company_id)
{
	return db_get_fields("SELECT user_id FROM ?:orders WHERE company_id = ?i", $company_id);
}

function fn_take_payment_surcharge_from_vendor($products)
{
	$take_surcharge_from_vendor = false;
	if (Registry::get('settings.Suppliers.include_payment_surcharge') == 'Y') {
		$take_surcharge_from_vendor = true;
	}

	return $take_surcharge_from_vendor;
}

?>