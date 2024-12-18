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



//

// Get product description to show it in the cart

//

function fn_get_cart_product_data($hash, &$product, $skip_promotion, &$cart, &$auth, $promotion_amount = 0)

{
	if (!empty($product['product_id'])) {



		$_p_statuses = array('A', 'H');

		$_c_statuses = array('A', 'H');


	// Changes by Sudhir dt 29th octo 2012 to optimize query bigin here
		//$avail_cond = (AREA == 'C') ? " AND (" . fn_find_array_in_set($auth['usergroup_ids'], '?:categories.usergroup_ids', true) . ")" : '';

		//$avail_cond .= (AREA == 'C') ? " AND (" . fn_find_array_in_set($auth['usergroup_ids'], '?:products.usergroup_ids', true) . ")" : '';

		//$avail_cond .= (AREA == 'C' && !(isset($auth['area']) && $auth['area'] == 'A')) ? db_quote(' AND ?:categories.status IN (?a) AND ?:products.status IN (?a)', $_c_statuses, $_p_statuses) : '';

		//$avail_cond .= (AREA == 'C') ? fn_get_localizations_condition('?:products.localization') : '';
		$avail_cond = (AREA == 'C') ? fn_get_localizations_condition('?:products.localization') : '';
	// Changes by Sudhir dt 29th octo 2012 to optimize query end here

		$join = " INNER JOIN ?:products_categories ON ?:products_categories.product_id = ?:products.product_id INNER JOIN ?:categories ON ?:categories.category_id = ?:products_categories.category_id $avail_cond";

		$join .= db_quote(" LEFT JOIN ?:companies ON ?:companies.company_id = ?:products.company_id");


	// Changes by Sudhir dt 29th octo 2012 to optimize query bigin here
		//$_pdata = db_get_row("SELECT ?:products.product_id, ?:products.company_id, GROUP_CONCAT(IF(?:products_categories.link_type = 'M', CONCAT(?:products_categories.category_id, 'M'), ?:products_categories.category_id)) as category_ids, ?:products.product_code, ?:products.list_price, ?:products.weight, ?:products.tracking, ?:product_descriptions.product, ?:product_descriptions.short_description, ?:products.is_edp, ?:products.edp_shipping, ?:products.shipping_freight, ?:products.free_shipping, ?:products.zero_price_action, ?:products.tax_ids, ?:products.qty_step, ?:products.list_qty_count, ?:products.max_qty, ?:products.min_qty, ?:products.amount as in_stock, ?:products.shipping_params, ?:products.return_period, ?:products.is_returnable, ?:companies.status as company_status, ?:companies.company as company_name FROM ?:products LEFT JOIN ?:product_descriptions ON ?:product_descriptions.product_id = ?:products.product_id AND ?:product_descriptions.lang_code = ?s ?p WHERE ?:products.product_id = ?i GROUP BY ?:products.product_id", CART_LANGUAGE, $join, $product['product_id']);
		$chk_status = (AREA == 'C' && !(isset($auth['area']) && $auth['area'] == 'A')) ? db_quote(' AND ?:categories.status IN (?a) AND ?:products.status IN (?a)', $_c_statuses, $_p_statuses) : '';

		$_pdata = db_get_row("SELECT ?:products.product_id, ?:products.company_id, GROUP_CONCAT(IF(?:products_categories.link_type = 'M', CONCAT(?:products_categories.category_id, 'M'), ?:products_categories.category_id)) as category_ids, ?:products.product_code, ?:products.list_price, ?:products.weight, ?:products.tracking, ?:product_descriptions.product, ?:product_descriptions.short_description, ?:products.is_edp, ?:products.edp_shipping, ?:products.shipping_freight, ?:products.free_shipping, ?:products.zero_price_action, ?:products.tax_ids, ?:products.qty_step, ?:products.list_qty_count, ?:products.max_qty, ?:products.min_qty, ?:products.amount as in_stock, ?:products.shipping_params, ?:products.return_period, ?:products.is_returnable, ?:companies.status as company_status, ?:companies.company as company_name, ?:products.is_wholesale_product FROM ?:products LEFT JOIN ?:product_descriptions ON ?:product_descriptions.product_id = ?:products.product_id AND ?:product_descriptions.lang_code = ?s ?p WHERE ?:products.product_id = ?i $chk_status ", CART_LANGUAGE, $join, $product['product_id']);

	// Changes by Sudhir dt 29th octo 2012 to optimize query end here

		// delete product from cart if supplier or vendor was disabled.

		if (empty($_pdata) || (!empty($_pdata['company_id']) && ($_pdata['company_status'] != 'A' || ((PRODUCT_TYPE == 'PROFESSIONAL' || PRODUCT_TYPE == 'COMMUNITY') && Registry::get('settings.Suppliers.enable_suppliers') != 'Y') ))) {

			return false;

		}


		$_pdata['category_ids'] = !empty($_pdata['category_ids']) ? fn_convert_categories($_pdata['category_ids']) : array();



		$_pdata['options_count'] = db_get_field("SELECT COUNT(*) FROM ?:product_options WHERE product_id = ?i AND status = 'A'", $product['product_id']);



		$_pdata['price'] = fn_get_product_price($product['product_id'], $product['amount'], $auth);



		$_pdata['base_price'] = (isset($product['stored_price']) && $product['stored_price'] == 'Y') ? $product['price'] : $_pdata['price'];



		fn_set_hook('get_cart_product_data', $product['product_id'], $_pdata, $product);



		$product['stored_price'] = empty($product['stored_price']) ? 'N' : $product['stored_price'];

		$product['stored_discount'] = empty($product['stored_discount']) ? 'N' : $product['stored_discount'];

		$product['product_options'] = empty($product['product_options']) ? array() : $product['product_options'];



		if (empty($_pdata['product_id'])) { // FIXME - for deleted products for OM

			unset($cart['products'][$hash]);

			return array();

		}



		if (!empty($_pdata['options_count']) && empty($product['product_options'])) {

			$cart['products'][$hash]['product_options'] = fn_get_default_product_options($product['product_id']);

		}



		if (Registry::get('settings.General.inventory_tracking') == 'Y' && !empty($_pdata['tracking']) && $_pdata['tracking'] == 'O' && !empty($product['selectable_cart_id'])) {

			$_pdata['in_stock'] = db_get_field("SELECT amount FROM ?:product_options_inventory WHERE combination_hash = ?i", $product['selectable_cart_id']);

		}



		if (fn_check_amount_in_stock($product['product_id'], $product['amount'], $product['product_options'], $hash, $_pdata['is_edp'], !empty($product['original_amount']) ? $product['original_amount'] : 0, $cart) == false) {

			unset($cart['products'][$hash]);

			$out_of_stock = true;

			return false;

		}

		$exceptions = fn_get_product_exceptions($product['product_id'], true);

		if (!isset($product['options_type']) || !isset($product['exceptions_type'])) {

			$product = array_merge($product, db_get_row('SELECT options_type, exceptions_type FROM ?:products WHERE product_id = ?i', $product['product_id']));

		}

		

		if (!fn_is_allowed_options_exceptions($exceptions, $product['product_options'], $product['options_type'], $product['exceptions_type']) && !defined('GET_OPTIONS')) {

			fn_set_notification('E', fn_get_lang_var('notice'), str_replace('[product]', $_pdata['product'], fn_get_lang_var('product_options_forbidden_combination')));

			unset($cart['products'][$hash]);

			

			return false;

		}

		if (isset($product['extra']['custom_files'])) {

			$_pdata['extra']['custom_files'] = $product['extra']['custom_files'];

		}



		$_pdata['calculation'] = array();



		if (isset($product['extra']['exclude_from_calculate'])) {

			$_pdata['exclude_from_calculate'] = $product['extra']['exclude_from_calculate'];

			$_pdata['aoc'] = !empty($product['extra']['aoc']);

			$_pdata['price'] = 0;

		} else {

			if ($product['stored_price'] == 'Y') {

				$_pdata['price'] = $product['price'];

			}

		}



		// If price defined and zero price action allows add zero priced product to cart, get price from the database

		if (isset($_pdata['price']) && $_pdata['zero_price_action'] != 'A') {

			$product['price'] = floatval($_pdata['price']);

			$cart['products'][$hash]['price'] = $product['price'];

		}



		$_pdata['original_price'] = $product['price'];



		if ($product['stored_price'] != 'Y' && !isset($product['extra']['exclude_from_calculate'])) {

			if ($_pdata['zero_price_action'] != 'A' || $_pdata['zero_price_action'] == 'A' && empty($product['modifiers_price'])) {

				$_tmp = $product['price'];

				$product['price'] = fn_apply_options_modifiers($product['product_options'], $product['price'], 'P');

				$product['modifiers_price'] = $_pdata['modifiers_price'] = $product['price'] - $_tmp; // modifiers

			}

		} else {

			$product['modifiers_price'] = $_pdata['modifiers_price'] = 0;

		}



		if (isset($product['modifiers_price']) && $_pdata['zero_price_action'] == 'A') {

			$_pdata['base_price'] = $product['price'] - $product['modifiers_price'];

		}



		$_pdata['weight'] = fn_apply_options_modifiers($product['product_options'], $_pdata['weight'], 'W');

		$_pdata['amount'] = $product['amount'];

		$_pdata['price'] = $_pdata['original_price'] = fn_format_price($product['price']);



		$_pdata['stored_price'] = $product['stored_price'];



		if ($cart['options_style'] == 'F') {

			$_pdata['product_options'] = fn_get_selected_product_options($product['product_id'], $product['product_options'], CART_LANGUAGE);

		} elseif ($cart['options_style'] == 'I') {

			$_pdata['product_options'] = fn_get_selected_product_options_info($product['product_options'], CART_LANGUAGE);

		} else {

			$_pdata['product_options'] = $product['product_options'];

		}



		if (($_pdata['free_shipping'] != 'Y' || AREA == 'A') && ($_pdata['is_edp'] != 'Y' || ($_pdata['is_edp'] == 'Y' && $_pdata['edp_shipping'] == 'Y'))) {

			$cart['shipping_required'] = true;

		}



		$cart['products'][$hash]['is_edp'] = (!empty($_pdata['is_edp']) && $_pdata['is_edp'] == 'Y') ? 'Y' : 'N';

		$cart['products'][$hash]['edp_shipping'] = (!empty($_pdata['edp_shipping']) && $_pdata['edp_shipping'] == 'Y') ? 'Y' : 'N';



		if (empty($cart['products'][$hash]['extra']['parent'])) { // count only products without parent

			if ($skip_promotion == true && !empty($promotion_amount)){

				$cart['amount'] += $promotion_amount;

			}else{

				$cart['amount'] += $product['amount'];

			}

		}



		if ($skip_promotion == false) {

			if (empty($cart['order_id'])) {

				fn_promotion_apply('catalog', $_pdata, $auth);

			} else {

				if (isset($product['discount'])) {

					$_pdata['discount'] = $product['discount'];

					$_pdata['price'] -= $product['discount'];



					if ($_pdata['price'] < 0) {

						$_pdata['discount'] += $_pdata['price'];

						$_pdata['price'] = 0;

					}

				}

			}



			// apply discount to the product

			if (!empty($_pdata['discount'])) {

				$cart['use_discount'] = true;

			}

		}



		if (!empty($product['object_id'])) {

			$_pdata['object_id'] = $product['object_id'];

		}



		$_pdata['shipping_params'] = empty($_pdata['shipping_params']) ? array() : unserialize($_pdata['shipping_params']);



		$_pdata['stored_discount'] = $product['stored_discount'];

		$cart['products'][$hash]['modifiers_price'] = $product['modifiers_price'];



		$_pdata['subtotal'] = $_pdata['price'] * $product['amount'];

		$cart['original_subtotal'] += $_pdata['original_price'] * $product['amount'];

		$cart['subtotal'] += $_pdata['subtotal'];
                
                if($cart['shipping_required'] == true)
                {
                    if(fn_check_if_shipping_price_set_for_product($_pdata['product_id']) && $cart['products'][$hash]['amount'] > 1)
                    {  
                        
                        $product_shipping_charge = fn_caclulate_shipping_for_more_quantity($_pdata['product_id'],$cart['products'][$hash]['amount']);
                        
                        $product_shipping_charge = (empty($product_shipping_charge))? 0 : floatval($product_shipping_charge);

                        $_pdata['shipping_freight'] = $product_shipping_charge;
 
                    }
                }

		return $_pdata;

	}



	return array();

}



function fn_update_cart_data(&$cart, &$cart_products)

{

	foreach ($cart_products as $k => $v) {

		if (isset($cart['products'][$k])) {

			if (!isset($v['base_price'])) {

				$cart['products'][$k]['base_price'] = $v['base_price'] = $cart['products'][$k]['stored_price'] != 'Y' ? $v['price'] : $cart['products'][$k]['price'];

			} else {

				if ($cart['products'][$k]['stored_price'] == 'Y') {

					$cart_products[$k]['base_price'] = $cart['products'][$k]['price'];

				}

			}



			$cart['products'][$k]['base_price'] = $cart['products'][$k]['stored_price'] != 'Y' ? $v['base_price'] : $cart['products'][$k]['price'];

			$cart['products'][$k]['price'] = $cart['products'][$k]['stored_price'] != 'Y' ? $v['price'] : $cart['products'][$k]['price'];

			if (isset($v['discount'])) {

				$cart['products'][$k]['discount'] = $v['discount'];

			}

			if (isset($v['promotions'])) {

				$cart['products'][$k]['promotions'] = $v['promotions'];

			}

		}

	}

}



//

// Get payment methods

//

function fn_get_payment_methods(&$auth, $lang_code = CART_LANGUAGE)

{

	$condition = '';

	if (AREA == 'C') {

		$condition .= " AND (" . fn_find_array_in_set($auth['usergroup_ids'], '?:payments.usergroup_ids', true) . ")";

		$condition .= " AND ?:payments.status = 'A' AND (?:payment_processors.type != 'C' OR ?:payments.processor_id = 0)";

		$condition .= fn_get_localizations_condition('?:payments.localization');

	}
	$payment_methods = db_get_hash_array("SELECT ?:payments.payment_id, ?:payments.a_surcharge, ?:payments.p_surcharge, ?:payment_descriptions.* FROM ?:payments LEFT JOIN ?:payment_descriptions ON ?:payments.payment_id = ?:payment_descriptions.payment_id AND ?:payment_descriptions.lang_code = ?s LEFT JOIN ?:payment_processors ON ?:payment_processors.processor_id = ?:payments.processor_id WHERE 1 $condition ORDER BY ?:payments.position", 'payment_id', $lang_code);
	
	fn_set_hook('get_payment_methods', $payment_methods);
	return $payment_methods;
}



function fn_get_simple_payment_methods($lang_code = CART_LANGUAGE)

{

	return db_get_hash_single_array("SELECT ?:payments.payment_id, ?:payment_descriptions.payment FROM ?:payments LEFT JOIN ?:payment_descriptions ON ?:payments.payment_id = ?:payment_descriptions.payment_id AND ?:payment_descriptions.lang_code = ?s WHERE status = 'A' ORDER BY ?:payments.position, ?:payment_descriptions.payment", array('payment_id', 'payment'), $lang_code);

}



//

// Get payment method data

//

function fn_get_payment_method_data($payment_id, $lang_code = CART_LANGUAGE)

{

	$payment = db_get_row("SELECT ?:payments.*, ?:payment_descriptions.*, ?:payment_processors.processor, ?:payments.params FROM ?:payments LEFT JOIN ?:payment_processors ON ?:payment_processors.processor_id = ?:payments.processor_id LEFT JOIN ?:payment_descriptions ON ?:payments.payment_id = ?:payment_descriptions.payment_id AND ?:payment_descriptions.lang_code = ?s WHERE ?:payments.payment_id = ?i", $lang_code, $payment_id);
	
	$payment['params'] = (!empty($payment['params'])) ? unserialize($payment['params']) : '';



	fn_set_hook('summary_get_payment_method', $payment_id, $payment);



	return $payment;

}



//

// Update product amount

//

// returns true if inventory successfully updated and false if amount

// is negative is allow_negative_amount option set to false



function fn_update_product_amount($product_id, $amount, $product_options, $sign)

{

	if (Registry::get('settings.General.inventory_tracking') != 'Y') {

		return true;

	}



	$tracking = db_get_field("SELECT tracking FROM ?:products WHERE product_id = ?i", $product_id);



	if ($tracking == 'D') {

		return true;

	}



	if ($tracking == 'B') {

		$product = db_get_row("SELECT amount, product_code FROM ?:products WHERE product_id = ?i", $product_id);

		$current_amount = $product['amount'];

		$product_code = $product['product_code'];

	} else {

		$cart_id = fn_generate_cart_id($product_id, array('product_options' => $product_options), true);

		$product = db_get_row("SELECT amount, product_code FROM ?:product_options_inventory WHERE combination_hash = ?i", $cart_id);

		$current_amount = empty($product['amount']) ? 0 : $product['amount'];

		

		if (empty($product['product_code'])) {

			$product_code = db_get_field("SELECT product_code FROM ?:products WHERE product_id = ?i", $product_id);

		} else {

			$product_code = $product['product_code'];

		}

	}



	if ($sign == '-') {

		$new_amount = $current_amount - $amount;



		// Notify administrator about inventory low stock

		if ($new_amount <= Registry::get('settings.General.low_stock_threshold')) {

			// Log product low-stock

			fn_log_event('products', 'low_stock', array (

				'product_id' => $product_id

			));



			$company_id = fn_get_company_id('products', 'product_id', $product_id);

			$company_placement_info = fn_get_company_placement_info($company_id);

			$lang_code = !empty($company_placement_info['lang_code']) ? $company_placement_info['lang_code'] : Registry::get('settings.Appearance.admin_default_language');

			

			Registry::get('view_mail')->assign('new_amount', $new_amount);

			Registry::get('view_mail')->assign('product_id', $product_id);

			Registry::get('view_mail')->assign('product_code', $product_code);

			Registry::get('view_mail')->assign('product', db_get_field("SELECT product FROM ?:product_descriptions WHERE product_id = ?i AND lang_code = ?s", $product_id, $lang_code));

			if ($tracking == 'O') {

				Registry::get('view_mail')->assign('product_options', fn_get_selected_product_options_info($product_options, $lang_code));

			}



			Registry::get('view_mail')->assign('company_placement_info', $company_placement_info);



			//fn_send_mail($company_placement_info['company_orders_department'], Registry::get('settings.Company.company_orders_department'), 'orders/low_stock_subj.tpl', 'orders/low_stock.tpl', '', $lang_code);

		}



		if ($new_amount < 0 && Registry::get('settings.General.allow_negative_amount') != 'Y') {

			return false;

		}

	} else {

		$new_amount = $current_amount + $amount;

	}



	fn_set_hook('update_product_amount', $new_amount, $product_id, $cart_id, $tracking);



	if ($tracking == 'B') {

		db_query("UPDATE ?:products SET amount = ?i WHERE product_id = ?i", $new_amount, $product_id);

	} else {

		db_query("UPDATE ?:product_options_inventory SET amount = ?i WHERE combination_hash = ?i", $new_amount, $cart_id);

	}



	if (($current_amount <= 0) && ($new_amount > 0)) {

		$product['product'] = db_get_field("SELECT product FROM ?:product_descriptions WHERE product_id = ?i", $product_id);

        //fn_send_product_notifications($product_id, $product['product']);
    }
	//Changes By Megha Sudan

	//Changes Promotion status if Product Quantity goes below the minimum quantity discount

	if(Registry::get('config.show_min_qty_discount') == 1)

	{

		fn_check_activate_min_qty_discount($product_id);

	}



	return true;

}



/**

 * Order placing function

 *

 * @param array $cart

 * @param array $auth

 * @param string $action

 * @return int order_id or bool FALSE

 */

function fn_place_order(&$cart, &$auth, $action = '', $parent_order_id = 0)

{

	$allow = true;
	


	fn_set_hook('pre_place_order', $cart, $allow);

 

	if ($allow == true && !fn_cart_is_empty($cart)) {

		$ip = fn_get_ip();

		$__order_status = STATUS_INCOMPLETED_ORDER;

		$order = fn_check_table_fields($cart, 'orders');

		$order = fn_array_merge($order, fn_check_table_fields($cart['user_data'], 'orders'));


                if($cart['multiple_shipping_addresses']){
                    $ks = array_keys($cart['products']);
                    $cid = $ks[0];
                    if(isset($cart['products'][$cid]['mashipping_profile_id']) && isset($cart['user_data']['user_id'])){
                        $user_profiles = fn_get_user_profiles_data($cart['user_data']['user_id']);
                        foreach ($user_profiles as $profile){
                            if ($profile['profile_id'] == $cart['products'][$cid]['mashipping_profile_id']){
                                $shipping_fields = array('s_title', 's_firstname', 's_lastname', 's_address', 's_address_2', 's_city', 's_state', 's_country', 's_zipcode', 's_phone');
                                foreach($shipping_fields as $fd){
                                    $order[$fd]=$profile[$fd];
                                }
                            }
                        }
                    }
                }
		// filter hidden fields, which were hidden to checkout

		fn_filter_hidden_profile_fields($order, 'O');



		// If the contact information fields were disabled, fill the information from the billing/shipping

		Registry::get('settings.General.address_position') == 'billing_first' ? $address_zone = 'b' : $address_zone = 's';

		if (!empty($order['firstname']) || !empty($order[$address_zone . '_firstname'])) {

			$order['firstname'] = empty($order['firstname']) && !empty($order[$address_zone . '_firstname']) ? $order[$address_zone . '_firstname'] : $order['firstname'];

		}

		if (!empty($order['lastname']) || !empty($order[$address_zone . '_lastname'])) {

			$order['lastname'] = empty($order['lastname']) && !empty($order[$address_zone . '_lastname']) ? $order[$address_zone . '_lastname'] : $order['lastname'];

		}

		if (!empty($order['phone']) || !empty($order[$address_zone . '_phone'])) {

			$order['phone'] = empty($order['phone']) && !empty($order[$address_zone . '_phone']) ? $order[$address_zone . '_phone'] : $order['phone'];

		}



		$order['user_id'] = $auth['user_id'];

		$order['timestamp'] = TIME;

		$order['lang_code'] = CART_LANGUAGE;

		$order['tax_exempt'] = $auth['tax_exempt'];

		$order['status'] = STATUS_INCOMPLETED_ORDER; // incomplete by default to increase inventory

		$order['ip_address'] = $ip['host'];



		if (defined('CART_LOCALIZATION')) {

			$order['localization_id'] = CART_LOCALIZATION;

		}



		if (!empty($cart['payment_surcharge'])) {

			$cart['total'] += $cart['payment_surcharge'];

			$order['total'] = $cart['total'];

		}



		//$cart['companies'] = fn_get_products_companies($cart['products']);



		$order['is_parent_order'] = 'N';

		if (PRODUCT_TYPE == 'MULTIVENDOR') {

			$order['parent_order_id'] = $parent_order_id;

			if (count($cart['companies']) > 1 || ($cart['multiple_shipping_addresses'] && $parent_order_id == '0')) {

				$order['is_parent_order'] = 'Y';

				$__order_status = $order['status'] = STATUS_PARENT_ORDER;

			} else {

				$order['company_id'] = key($cart['companies']);

			}

			

			$take_payment_surcharge_from_vendor = fn_take_payment_surcharge_from_vendor($cart['products']);



			if (Registry::get('settings.Suppliers.include_payment_surcharge') == 'Y' && $take_payment_surcharge_from_vendor && !empty($cart['payment_surcharge'])) {

				$cart['companies_count'] = count($cart['companies']);

				$cart['total'] -= $cart['payment_surcharge'];

				$order['total'] = $cart['total'];

			}

		}


		
		$order['promotions'] = serialize(!empty($cart['promotions']) ? $cart['promotions'] : array());

		if (!empty($cart['promotions'])) {

			$order['promotion_ids'] = implode(',', array_keys($cart['promotions']));

		}

		

		$order['shipping_ids'] = !empty($cart['shipping']) ? fn_create_set(array_keys($cart['shipping'])) : '';



		if (!empty($cart['payment_info'])) {

			$ccards = fn_get_static_data_section('C', true);

			if (!empty($cart['payment_info']['card']) && !empty($ccards[$cart['payment_info']['card']])) {

				// Check if cvv2 number required and unset it if not

				if ($ccards[$cart['payment_info']['card']]['param_2'] != 'Y') {

					unset($cart['payment_info']['cvv2']);

				}

				// Check if start date exists and required and convert it to string

				if ($ccards[$cart['payment_info']['card']]['param_3'] != 'Y') {

					

					unset($cart['payment_info']['start_year'], $cart['payment_info']['start_month']);

				}

				// Check if issue number required

				if ($ccards[$cart['payment_info']['card']]['param_4'] != 'Y') {

					unset($cart['payment_info']['issue_number']);

				}

			}

		}



		// We're editing existing order

		if (!empty($order['order_id']) && $order['is_parent_order'] != 'Y') {



			$_tmp = db_get_row("SELECT status, ip_address, details, timestamp, payment_id, lang_code, repaid FROM ?:orders WHERE order_id = ?i", $order['order_id']);

			$order['ip_address'] = $_tmp['ip_address']; // Leave original customers IP address

			$order['details'] = $_tmp['details']; // Leave order details

			$order['timestamp'] = $_tmp['timestamp']; // Leave the original date

			$order['lang_code'] = $_tmp['lang_code']; // Leave the original language

			$order['repaid'] = $_tmp['repaid'];



			if ($action == 'save') {



				$payment_info = db_get_field("SELECT data FROM ?:order_data WHERE order_id = ?i AND type = 'P'", $order['order_id']);

				if (!empty($payment_info) && $cart['payment_id'] == $_tmp['payment_id']) {

					$payment_info = unserialize(fn_decrypt_text($payment_info));

				} else {

					$payment_info = array();

				}



				$cart['payment_info'] = array_merge($payment_info, $cart['payment_info']);



				$__order_status = $_tmp['status']; // Get the original order status

			}



			fn_change_order_status($order['order_id'], STATUS_INCOMPLETED_ORDER, $_tmp['status'], fn_get_notification_rules(array(), false)); // incomplete the order to increase inventory amount.



			db_query("DELETE FROM ?:orders WHERE order_id = ?i", $order['order_id']);

			db_query("DELETE FROM ?:order_details WHERE order_id = ?i", $order['order_id']);

			db_query("DELETE FROM ?:profile_fields_data WHERE object_id = ?i AND object_type = 'O'", $order['order_id']);

			db_query("DELETE FROM ?:order_data WHERE order_id = ?i AND type IN ('T', 'C', 'P')", $order['order_id']);



			fn_set_hook('edit_place_order', $order['order_id']);

		}



		if (!empty($cart['rewrite_order_id'])) {

			$order['order_id'] = array_shift($cart['rewrite_order_id']);

		}

		/*modified by clues dev to record coupon code being used.*/
		if(isset($cart['coupons']) && count($cart['coupons'])>0){
			$coupon_codes = '';
			foreach($cart['coupons'] as $k=>$coupon){
				if($coupon_codes == ''){
					$coupon_codes = $k;
				}else{
					$coupon_codes = $coupon_codes.','.$k;
				}
			}
			$order['coupon_codes'] = $coupon_codes;
		}
		/*modified by clues dev to record coupon code being used.*/
		
		
		/*modified by clues dev to record payment option id being used.*/
		$order['payment_option_id'] = $cart['payment_option_id'];
		/*modified by clues dev to record payment option id being used.*/
		
		/*modified by clues dev to record emi id being used.*/
		$order['emi_id'] = $cart['emi_id'];
		/*modified by clues dev to record emi id being used.*/
		
		/*modified by clues dev to record emi processing fee being used.*/
		//$order['emi_fee'] = $cart['emi_fee'];
		if(!isset($cart['processed_order_id']) || !isset($cart['rewrite_order_id'])){
			$order['emi_fee'] = $cart['emi_fee'];		
		}else{
			if(!empty($cart['products'])){
				$order['emi_fee'] = $cart['company_discount'][$cart['order_company_id']]['total_company_emi_part'];
				if($cart['emi_to_adjust'] > 0){
					$order['emi_fee'] += $cart['emi_to_adjust'];
					$cart['emi_to_adjust'] = 0;	
				}
			}else{
				$order['emi_fee'] = '0';
				$order['subtotal_discount'] = '0';
				$order['discount'] = '0';	
			}
		}
		/*modified by clues dev to record emi processing fee being used.*/
		
		/*modified by clues dev to record emi processing fee being used.*/
		if(!isset($cart['processed_order_id']) || !isset($cart['rewrite_order_id'])){
			$order['cod_fee'] = $cart['cod_fee'];		
		}else{
			if(!empty($cart['products'])){
				$order['cod_fee'] = $cart['company_discount'][$cart['order_company_id']]['total_company_cod_part'];
				if($cart['cod_to_adjust'] > 0){
					$order['cod_fee'] += $cart['cod_to_adjust'];
					$cart['cod_to_adjust'] = 0;	
				}
			}else{
				$order['emi_fee'] = '0';
				$order['subtotal_discount'] = '0';
				$order['discount'] = '0';
				$order['cod_fee'] = '0';	
			}
		}
		/*modified by clues dev to record emi processing fee being used.*/
		
		/*modified by clues dev to record gifting details being used.*/
		if(isset($cart['gifting']) && $cart['gifting']['gift_it'] == 'Y'){
			if(isset($cart['processed_order_id']) || isset($cart['rewrite_order_id'])){
				$cart_items = 0;
				foreach($cart['products'] as $items)
				{
					$cart_items += $items['amount'];
				}
				$order['gifting_charge'] = $cart_items * Registry::get('config.gifting_charge');
				$order['gift_it'] = $cart['gifting']['gift_it'];	
			}else{
				$order['gifting_charge'] = $cart['gifting']['gifting_charge'];
				$order['gift_it'] = $cart['gifting']['gift_it'];
			}
		}		
		/*modified by clues dev to record gifting details being used.*/
		
                /*added by chandan to check the child order calculation*/
                if(isset($cart['processed_order_id']) || isset($cart['rewrite_order_id'])){
                    if($cart['gifting']['gift_it'] == 'Y'){
                            $gifting_fee = $cart['gifting']['gifting_charge'];
                    }else{
                            $gifting_fee = 0;
                    }
                    $gc_total = 0;
                    if(isset($cart['use_gift_certificates'])){
                        foreach($cart['use_gift_certificates'] as $gc){
                                $gc_total += $gc['cost'];	
                        }
                    }
                    $cart_total = 0;
                    foreach($cart['products'] as $cproduct)
                    {
                            $cart_total += $cproduct['amount']*	($cproduct['price']-$cproduct['discount']);
                    }

                    if(isset($cart['gift_certificates']) && empty($cart['products'])){
                            foreach($cart['gift_certificates'] as $gc){
                                    if($gc['subtotal'] != ''){
                                            $cart_total += $gc['subtotal'];
                                    }else{
                                            $cart_total += $gc['amount'];
                                    }
                            }
                    }
                    //$cb_used_total = ($cart['points_info']['in_use']['cost']) ? $cart['points_info']['in_use']['cost'] : 0;
                    $cb_used_total = 0; 
                    $cb_used_total = ($cart['points_info']['in_use']['points']) ? $cart['points_info']['in_use']['points'] : 0;
                    $cb_used_total = fn_format_price($cb_used_total);
                    $c_emi_fee = ($order['emi_fee']) ? $order['emi_fee'] : 0;
                    $c_cod_fee = ($order['cod_fee']) ? $order['cod_fee'] : 0;
                    if(($order['subtotal'] != $cart_total) || (($order['total'] + $cart['subtotal_discount'] + $cb_used_total + $gc_total - $cart['emi_fee'] - $cart['cod_fee'] - $gifting_fee - $cart['shipping_cost']) != $cart_total)){
                        if(Registry::get('config.correct_total')){    
                            $old_cart_total = 0;
                            $old_cart_total = $cart['total'];
                            if(!empty($cart['products'])){
                                $order['total'] = $cart_total + $cart['shipping_cost'] + $c_emi_fee + $c_cod_fee + $gifting_fee - $cart['subtotal_discount'] - $cb_used_total - $gc_total;
                            }else{
                                $order['total'] = $cart_total + $cart['shipping_cost'] + $gifting_fee  - $cart['cb_for_gc'] - $gc_total;
                            }
                        }
                        if(Registry::get('config.log_correct_total')){    
                            $log_data = array();
                            $log_data['cart'] = $cart;
                            $log_data['old_cart_total'] = $old_cart_total;
                            $log_data['new_cart_total'] = $order['total'];
                            $log_data['cart_total']     = $cart_total;
                            $log_data['shipping_cost'] = $cart['shipping_cost'];
                            $log_data['emi_fee'] = $c_emi_fee;
                            $log_data['cod_fee'] = $c_cod_fee;
                            $log_data['gifting_fee'] = $gifting_fee;
                            $log_data['subtotal_discount'] = $cart['subtotal_discount'];
                            $log_data['cb_used_total'] = $cb_used_total;
                            $log_data['gc_total'] = $gc_total;
                            $content = json_encode($log_data);
                            log_to_file('cart_correct', $content);
                        }
                    }
                }
                /*added by chandan to check the child order calculation*/
                
		
		/*Modified by chandan to add user id if registered customer  do guest checkout*/
		$user_id = db_get_field("SELECT user_id FROM ?:users WHERE email = ?s", $cart['user_data']['email']);
		if (!empty($user_id)) {
			$order['user_id'] = $user_id;				
		}

                
		/*modified by chandan to track the order whether its on cod or not*/
                $order['cod_eligible'] = eligible_for_cod($cart);
                /*modified by chandan to track the order whether its on cod or not*/
		/*Modified by chandan to add user id if registered customer  do guest checkout*/
                
		$order_id = db_query("INSERT INTO ?:orders ?e", $order);

                if(Registry::get('config.logging_for_user_id_issue')){
                	if($order['user_id'] == 0 || $order['user_id']== ''){ 
                            $mail =mail('brajendra.nagar@shopclues.com, sudhir.singh@shopclues.com, vinay.gupta@shopclues.com', 'Issue in order_id:'.$order_id, "\r\nsession Array: ".serialize($_SESSION)."\r\nServer Array:".serialize($_SERVER)."\r\nRequest Data:".serialize($_REQUEST)."\r\nOrder:".serialize($order)."\r\nCart Data:".serialize($cart));
                	}
                        
                }
                
                if($order['is_parent_order'] != 'Y' && $cart['multiple_shipping_addresses'])
                {
                    $msg_profile_id = $cart['products'][$cid]['mashipping_profile_id'];
                    if(array_key_exists($msg_profile_id,$_SESSION['multiaddress_message']))
                    {
                    $msg_to = mysql_real_escape_string($_SESSION['multiaddress_message'][$msg_profile_id]['to']);
                    $msg_from = mysql_real_escape_string($_SESSION['multiaddress_message'][$msg_profile_id]['from']);
                    $msg_desc = mysql_real_escape_string($_SESSION['multiaddress_message'][$msg_profile_id]['msg']);
                    $msg_query = "INSERT IGNORE INTO clues_multiaddress_messages (msg_to_name,msg_from_name,msg_description,order_id,timestamp) values ('$msg_to','$msg_from','$msg_desc','$order_id',UNIX_TIMESTAMP())";
                    db_query($msg_query);
                    }
                }
                
                
                
		/*modified by clues dev to record gifting details being used.*/
		if(isset($cart['gifting']) && $cart['gifting']['gift_it'] == 'Y'){
			$order['gifting_charge'] = $cart['gifting']['gifting_charge'];
			$order['gift_it'] = $cart['gifting']['gift_it'];
			
			if($cart['gifting']['to'] != '' && $cart['gifting']['from'] != '' && $cart['gifting']['msg'] != ''){
				$query = "insert into clues_gift_message(order_id, gift_to, gift_from, message) values ('".$order_id."','".addslashes($cart['gifting']['to'])."','".addslashes($cart['gifting']['from'])."','".addslashes($cart['gifting']['msg'])."')";
			}else{
				$query = "insert into clues_gift_message(order_id, no_message) values ('".$order_id."','Y')";
			}
			db_query($query);
		}		
		/*modified by clues dev to record gifting details being used.*/

		// Log order creation

		fn_log_event('orders', 'create', array(

			'order_id' => $order_id

		));
		/*added by chandan to check the order total*/
		if($cart['total_matched'] == 'NO'){
			$cause_sql = "INSERT INTO clues_exception_causes_order_rel (cause_id, order_id, order_status, type, latest, auth, exc_notes) values (43, '".$order_id."','N','Cause', '1', '7465', 'Fraud Alert')";
			$action_sql = "INSERT INTO clues_exception_causes_order_rel (cause_id, order_id, order_status, type, latest, auth, exc_notes) values (47, '".$order_id."','N','Action', '1', '7465', 'Fraud Alert')";	
			db_query($cause_sql);
			db_query($action_sql);
		}
		
		if($cart['payment_id'] == '-1'){
			$cause_sql = "INSERT INTO clues_exception_causes_order_rel (cause_id, order_id, order_status, type, latest, auth, exc_notes) values (43, '".$order_id."','N','Cause', '1', '7465', 'Fraud Alert')";
			$action_sql = "INSERT INTO clues_exception_causes_order_rel (cause_id, order_id, order_status, type, latest, auth, exc_notes) values (47, '".$order_id."','N','Action', '1', '7465', 'Fraud Alert')";	
			db_query($cause_sql);
			db_query($action_sql);	
		}
		/*added by chandan to check the order total*/
		fn_store_profile_fields($cart['user_data'], $order_id, 'O');



		$order['order_id'] = $order_id;

		// If customer is not logged in, store order ids in the session

		if (empty($auth['user_id'])) {

			$auth['order_ids'][] = $order_id;

		}

		// Add order details data
		if (!empty($order_id)) {

			if (!empty($cart['products'])) {

				foreach ((array)$cart['products'] as $k => $v) {
					
					$product_code = '';

					$extra = empty($v['extra']) ? array() : $v['extra'];

					$v['discount'] = empty($v['discount']) ? 0 : $v['discount'];



					$extra['product'] = empty($v['name']) ? fn_get_product_name($v['product_id']) : $v['name'];



					$extra['company_id'] = !empty($v['company_id']) ? $v['company_id'] : 0;



					if (isset($v['is_edp'])) {

						$extra['is_edp'] = $v['is_edp'];

					}

					if (isset($v['edp_shipping'])) {

						$extra['edp_shipping'] = $v['edp_shipping'];

					}

					if (!empty($v['discount'])) {

						$extra['discount'] = $v['discount'];

					}

					if (isset($v['base_price'])) {

						$extra['base_price'] = floatval($v['base_price']);

					}

					if (!empty($v['promotions'])) {

						$extra['promotions'] = $v['promotions'];

					}

					if (!empty($v['stored_price'])) {

						$extra['stored_price'] = $v['stored_price'];

					}



					if (!empty($v['product_options'])) {

						$_options = fn_get_product_options($v['product_id']);

						if (!empty($_options)) {

							foreach ($_options as $option_id => $option) {

								if (!isset($v['product_options'][$option_id])) {

									$v['product_options'][$option_id] = '';

								}

							}

						}

						

						$extra['product_options'] = $v['product_options'];

						$cart_id = fn_generate_cart_id($v['product_id'], array('product_options' => $v['product_options']), true);

						$tracking = db_get_field("SELECT tracking FROM ?:products WHERE product_id = ?i", $v['product_id']);

						

						if ($tracking == 'O') {

							$product_code = db_get_field("SELECT product_code FROM ?:product_options_inventory WHERE combination_hash = ?i", $cart_id);

						}



						$extra['product_options_value'] = fn_get_selected_product_options_info($v['product_options']);
                                                if(!empty($extra['product_options_value']))
                                                {
                                                    $options_for_product = array();
                                                    foreach($extra['product_options_value'] as $option_values)
                                                    {
                                                        $options_for_product[] =  $option_values['option_name'] . ':' . $option_values['variant_name'];
                                                    }
                                                }
					} else {

						$v['product_options'] = array();

					}



					if (empty($product_code)) {

						$product_code = db_get_field("SELECT product_code FROM ?:products WHERE product_id = ?i", $v['product_id']);

					}



					// Check the cart custom files

					if (isset($extra['custom_files'])) {

						$dir_path = DIR_CUSTOM_FILES . 'order_data/' . $order_id;

						$sess_dir_path = DIR_CUSTOM_FILES . 'sess_data';

						

						if (!is_dir($dir_path)) {

							fn_mkdir($dir_path);

						}
//////// Added By Sudhir dt 07 June 2012

// connect to FTP server (port 21)
$conn_id = ftp_connect(Registry::get('config.ftp_host'), 21);

// send access parameters
ftp_login($conn_id, Registry::get('config.ftp_user'), Registry::get('config.ftp_pwd'));

// turn on passive mode transfers (some servers need this)
ftp_pasv ($conn_id, true);

$dir = Registry::get('config.ftp_path_order').$order_id;

ftp_mkdir($conn_id, $dir);

ftp_chmod($conn_id, 0755, $dir);
// close the connection
ftp_close($conn_id);
////////////////////// Code added by Sudhir end here

						foreach ($extra['custom_files'] as $option_id => $files) {

							if (is_array($files)) {

								foreach ($files as $file_id => $file) {

//////// Added By Sudhir dt 07 June 2012

// connect to FTP server (port 21)
$conn_id = ftp_connect(Registry::get('config.ftp_host'), 21);

// send access parameters
ftp_login($conn_id, Registry::get('config.ftp_user'), Registry::get('config.ftp_pwd'));

// turn on passive mode transfers (some servers need this)
ftp_pasv ($conn_id, true);

$dir = Registry::get('config.ftp_path_order').$order_id.'/';
$sess_path = Registry::get('config.ftp_path').basename($file['path']);

$ftp_get = ftp_get($conn_id, basename($file['path']), $sess_path, FTP_BINARY);

$ftp_put = ftp_put($conn_id, $dir.basename($file['path']), basename($file['path']), FTP_BINARY);
ftp_chmod($conn_id, 0755, $dir.basename($file['path']));

ftp_delete($conn_id, basename($file['path']));

// close the connection
ftp_close($conn_id);
////////////////////// Code added by Sudhir end here
									//$file['path'] = $sess_dir_path . '/' . basename($file['path']);

									//fn_copy($file['path'], $dir_path . '/' . $file['file']);

									//fn_rm($file['path']);

									//fn_rm($file['path'] . '_thumb');

									$extra['custom_files'][$option_id][$file_id]['path'] = $dir_path . '/' . $file['file'];

								}

							}

						}

					}
                                        // start Shipping cost changes By Munish
                                        
                                        $shipping_data = db_get_row("SELECT free_shipping,shipping_freight,is_wholesale_product,min_qty FROM ?:products WHERE product_id = ?i", $v['product_id']);
                                        //Changes By Megha Sudan
                                        //$free_shipping = ($shipping_data['free_shipping']=='Y' ? 0 : $shipping_data['shipping_freight'])
                                        //check for wholesale products -- if its wholesale then divide the shipping_cost of the bunch by amount
                                        if($shipping_data['min_qty'] < $v['amount'])
                                        {
                                            if($shipping_data['is_wholesale_product'] == 1)
                                            {
                                                $shipping_bunch = (($v['amount']%$shipping_data['min_qty']) != 0 ? floor($v['amount']/$shipping_data['min_qty']) + 1 : floor($cart_products[$cart_id]['amount']/$cart_products[$cart_id]['min_qty']));
                                                $rate = ($shipping_data['shipping_freight'] * $shipping_bunch);
                                                $free_shipping = ($shipping_data['free_shipping']=='Y' ? 0 : ($shipping_data['is_wholesale_product'] == 1 ? ($rate/$v['amount']) : $shipping_data['shipping_freight']));
                                            }
                                            elseif(fn_check_if_shipping_price_set_for_product($v['product_id']) && $v['amount'] > 1)
                                            {  
                                                $product_shipping_charge = fn_caclulate_shipping_for_more_quantity($v['product_id'],$v['amount']);
                                                
                                                $product_shipping_charge = (empty($product_shipping_charge))? 0 : floatval($product_shipping_charge);

                                                $free_shipping += ($product_shipping_charge*$v['amount']);

                                            }
                                            else
                                            {
                                                $free_shipping = ($shipping_data['free_shipping']=='Y' ? 0 : $shipping_data['shipping_freight']);
                                            }
                                        }
                                        
                                        else
                                        {
                                            $free_shipping = ($shipping_data['free_shipping']=='Y' ? 0 : $shipping_data['shipping_freight']);
                                        }
                                        
                                        // End Shipping cost changes By Munish
					$order_details = array (
						'item_id' => $k,
						'order_id' => $order_id,
						'product_id' => $v['product_id'],
						'product_code' => $product_code,
						//'price' => (!empty($v['stored_price']) && $v['stored_price'] == 'Y') ? $v['price'] - $v['discount'] : $v['price'],
                                                'price' => (!empty($cart['dcp'][$k]['price'])) ? $cart['dcp'][$k]['price'] : $v['price'],
						'amount' => $v['amount'],
						'extra' => serialize($extra),
						'exempt_packingfee' => $v['exempt_packingfee'],
                                                'occasion_id' => $v['occasion_id'],
                                                'product_name'=> $extra['product'],
                                                'product_options' => implode(',',$options_for_product),
                                                'shipping_cost' =>  $free_shipping // added by chandan to track shipping cost
					);
					
					/*Added by chandan to track the mpromotion discount*/
                                        if(isset($cart['mpromotions']) && array_key_exists($v['product_id'],$cart['mpromotions'])){
                                            $order_details['m_discount'] = $cart['mpromotions'][$v['product_id']]['m_discount'];
                                            $order_details['m_promotion_ids'] = implode(',',$cart['mpromotions'][$v['product_id']]['m_promotion_id']);
                                        }
					/*Added by chandan to track the mpromotion discount*/

					/*Modified by clues dev to add product transfer price in the order details table*/			
					//$_data = db_get_row('SELECT transfer_price, start_date, end_date FROM ?:products WHERE product_id = ?i', $v['product_id']);
					
					/* Modified by Lokesh Gupta to add TP price from new TP rate table start */
					
					$_data = db_get_row("select tp, start_date, end_date, type from clues_product_TP where product_id = '".$v['product_id']."' and latest = 1 and type = 'deal_tp'");
					$flagdealtp = 0;
					if($_data)
					{
						if($_data['start_date'] != '' && $_data['start_date'] != '0' && $_data['end_date'] != '' && $_data['end_date'] != '0'){
							if( (date('Y-m-d') >= date('Y-m-d',$_data['start_date'])) && (date('Y-m-d') <= date('Y-m-d',$_data['end_date'])) ){
								$order_details['transfer_price'] = $_data['tp'];
								$flagdealtp = 1;
							}
						}
					}
					
					if($flagdealtp==0)
					{
						$_data = db_get_row("select tp, start_date, end_date, type from clues_product_TP where product_id = '".$v['product_id']."' and latest = 1 and type = 'tp'");
						if($_data)
						{
							if($_data['start_date'] != '' && $_data['start_date'] != '0' && $_data['end_date'] != '' && $_data['end_date'] != '0'){
								if( (date('Y-m-d') >= date('Y-m-d',$_data['start_date'])) && (date('Y-m-d') <= date('Y-m-d',$_data['end_date'])) ){
									$order_details['transfer_price'] = $_data['tp'];
								}
							}
						}
					}
					$order_details['selling_price'] = $v['base_price'];
                                        //added by chandan to track the modifier price of product option in selling price and track list price
                                        if(isset($v['modifiers_price'])){
                                            $order_details['selling_price'] += $v['modifiers_price'];
                                        }
                                        $order_details['list_price'] = $v['list_price'];
                                        /* Modified by Lokesh Gupta to add TP price from new TP rate table end */
					
					/*Modified by clues dev to add product transfer price and date range in the cart*/
					/*Added by chandan to set is_freebie Y if its a free product with promotion*/
                                        if(isset($v['extra']['is_freebie']) && $v['extra']['is_freebie'] == 'Y'){
                                            $order_details['is_freebie'] = $v['extra']['is_freebie'];
                                        }
                                        /*Added by chandan to set is_freebie Y if its a free product with promotion*/
					
					/* anoop code to catch orders with empty product_name */
					/*if(empty($order_details['product_name']))
					{
						$product_name_options = fn_get_product_name_options_valid($order_details['extra']);
						if(!empty($product_name_options))
						{
							$order_details['product_name'] = $product_name_options['product_name'];
							$order_details['product_options'] = $product_name_options['product_options'];
						}
					}*/
                                        
                                         if(!isset($order_details['product_name']) || empty($order_details['product_name']) || $order_details['product_name']=='' || $order_details['product_name'] == NULL || $order_details['product_name'] == ' '){
                                            $order_details['product_name'] = fn_get_product_name($v['product_id']);
                                        }
					//anoop code ends here


					db_query("INSERT INTO ?:order_details ?e", $order_details);

					// Increase product popularity

					$_data = array (

						'product_id' => $v['product_id'],

						'bought' => 1,

						'total' => POPULARITY_BUY

					);

					

					db_query("INSERT INTO ?:product_popularity ?e ON DUPLICATE KEY UPDATE bought = bought + 1, total = total + ?i", $_data, POPULARITY_BUY);

				}

			}



			// Save shipping information

			if (!empty($cart['shipping'])) {

				// Get carriers and tracking number

				$data = db_get_field("SELECT data FROM ?:order_data WHERE order_id = ?i AND type = 'L'", $order_id);

				if (!empty($data)) {

					$data = unserialize($data);

					foreach ($cart['shipping'] as $sh_id => $_d) {

						if (!empty($data[$sh_id]['carrier'])) {

							$cart['shipping'][$sh_id]['carrier'] = $data[$sh_id]['carrier'];

						}



						if (!empty($data[$sh_id]['tracking_number'])) {

							$cart['shipping'][$sh_id]['tracking_number'] = $data[$sh_id]['tracking_number'];

						}

					}

					fn_apply_stored_shipping_rates($cart, $order_id);

				}

				$_data = array (

					'order_id' => $order_id,

					'type' => 'L', //shipping information

					'data' => serialize($cart['shipping'])

				);

				db_query("REPLACE INTO ?:order_data ?e", $_data);

			}
                        
                        // added os name to order data
                        
                        
                        
                                $_data = array (

					'order_id' => $order_id,

					'type' => 'O', //os name

					'data' => serialize(fn_get_os()),

				);

				db_query("REPLACE INTO ?:order_data ?e", $_data);
                                
                        //added user agent to order data
                                $_data = array (

					'order_id' => $order_id,

					'type' => 'E', //user agent

					'data' => serialize($_SERVER['HTTP_USER_AGENT']),

				);

				db_query("REPLACE INTO ?:order_data ?e", $_data);
				
				//logging for express reorder
				
				 if(isset($cart['reorder_express']) && $cart['reorder_express']== true){
					$_data = array (

							 'order_id' => $order_id,

							 'type' => 'Q', //express order

							 'data' => 'express_reorder',

					 );
					db_query("REPLACE INTO ?:order_data ?e", $_data);  
					unset($cart['reorder_express']);
				}
                                
                         //added for express checkout       
                                
                               if(isset($cart['express_logging']) && $cart['express_logging']=='exp')
                               {
                                   $_data = array (

                                            'order_id' => $order_id,

                                            'type' => 'X', //express order

                                            'data' => 'exp',

                                    );

				    db_query("REPLACE INTO ?:order_data ?e", $_data);  
                                    unset($cart['express_logging']);
                               }
                               
                               if(isset($cart['express_logging']) && $cart['express_logging']=='noexp')
                               {
                                   $_data = array (

                                            'order_id' => $order_id,

                                            'type' => 'X', //express order

                                            'data' => 'noexp',

                                    );

				    db_query("REPLACE INTO ?:order_data ?e", $_data);  
                                    unset($cart['express_logging']);
                               }




			// Save taxes

			if (!empty($cart['taxes'])) {

				$_data = array (

					'order_id' => $order_id,

					'type' => 'T', //taxes information

					'data' => serialize($cart['taxes']),

				);

				db_query("REPLACE INTO ?:order_data ?e", $_data);

			}



			// Save payment information

			if (!empty($cart['payment_info'])) {

				$_data = array (

					'order_id' => $order_id,

					'type' => 'P', //payment information

					'data' => fn_encrypt_text(serialize($cart['payment_info'])),

				);

				db_query("REPLACE INTO ?:order_data ?e", $_data);

			}



			// Save coupons information

			if (!empty($cart['coupons'])) {
				if($_SESSION['cart']['custom_coupon']){
					$coupondata = $cart['coupons'];
					foreach($_SESSION['cart']['custom_coupon'] as $key => $val)
					{						
						foreach($coupondata as $ckey => $cval)
						{
							if($ckey==$val)
							{
								unset($coupondata[$ckey]);
								$coupondata[$key] = $cval;
							}
						}
					}				
					$_data = array (
							'order_id' => $order_id,
							'type' => 'C', //coupons
							'data' => serialize($coupondata),
						);
					db_query("REPLACE INTO ?:order_data ?e", $_data);
				}
				else
				{
					$_data = array (
					'order_id' => $order_id,
					'type' => 'C', //coupons
					'data' => serialize($cart['coupons']),
					);
				}
			}
                        
                        $_data = array (

				'order_id' => $order_id,

				'type' => 'H', //secondary currency

				'data' => gethostname(),

			);

			db_query("REPLACE INTO ?:order_data ?e", $_data);

			// Save secondary currency (for order notifications from payments with feedback requests) 

			$_data = array (

				'order_id' => $order_id,

				'type' => 'R', //secondary currency

				'data' => serialize(CART_SECONDARY_CURRENCY),

			);

			db_query("REPLACE INTO ?:order_data ?e", $_data);



			//

			// Place the order_id to new_orders table for all admin profiles

			//

			/*if (!$parent_order_id) {

				$condition = '';

				if (PRODUCT_TYPE == 'MULTIVENDOR') {

					$condition = empty($order['company_id']) ? ' AND company_id = 0 ' : fn_get_company_condition('company_id', true, $order['company_id'], true);

				}

				$admins = db_get_fields("SELECT user_id FROM ?:users WHERE user_type = 'A' $condition");

				foreach ($admins as $k => $v) {

					db_query("REPLACE INTO ?:new_orders (order_id, user_id) VALUES (?i, ?i)", $order_id, $v);

				}

			}*/



			fn_set_hook('place_order', $order_id, $action, $__order_status, $cart);

			// If order total is zero, just save the order without any processing procedures

			if (floatval($cart['total']) == 0) {

				$action = 'save';

				$__order_status = 'P';

			}

			

			list($is_processor_script, ) = fn_check_processor_script($cart['payment_id'], $action, true);

                        if (!$is_processor_script && $__order_status == STATUS_INCOMPLETED_ORDER && $order['payment_id'] == Registry::get('config.suvidha_payment_id')) {

				$__order_status = Registry::get('config.cbd_pending_status');

			}elseif (!$is_processor_script && $__order_status == STATUS_INCOMPLETED_ORDER) {

				$__order_status = 'O';

			}



			// Set new order status
            if(($cart['payment_id']==6 && $cart['user_data']['verified'] == 1 && $__order_status == 'O') || ($cart['payment_id']==6 && Registry::get('config.isResponsive')==1 && Registry::get('config.auto_confirmed_status_for_default_cod') && Registry::get('config.default_cod_on_mobile'))){ 
				if(fn_change_order_status($order_id, $__order_status, '', fn_get_notification_rules(array(), true) , true)){
					fn_change_order_status($order_id, '92', 'O', (($is_processor_script || $__order_status == STATUS_PARENT_ORDER) ? fn_get_notification_rules(array(), true) : fn_get_notification_rules(array())));
				}
			}else{
				fn_change_order_status($order_id, $__order_status, '', (($is_processor_script || $__order_status == STATUS_PARENT_ORDER) ? fn_get_notification_rules(array(), true) : fn_get_notification_rules(array())), true);
			}


			$cart['processed_order_id'] = array();

			$cart['processed_order_id'][] = $order_id;

			
			if (!$parent_order_id && (count($cart['companies']) > 1 || $cart['multiple_shipping_addresses']) && PRODUCT_TYPE == 'MULTIVENDOR') {

				fn_companies_place_suborders($order_id, $cart, $auth, $action);

				$child_orders = db_get_fields("SELECT order_id FROM ?:orders WHERE parent_order_id = ?i", $order_id);

				array_unshift($child_orders, $order_id);

				$cart['processed_order_id'] = $child_orders;
                                
                                if($cart['multiple_shipping_addresses']){
                                    $log['mashipping'] = true;
                                    $log['parent_order_id'] = $order_id;
                                    $log['child_orders'] = $child_orders;
                                    LogMetric::dump_log(array_keys($log), array_values($log), LogConstants::LOG_IDENTIFIED_USER|LogConstants::LOG_SERVER_NAME);

                                    //M in type field stands for multiple_shipping_address
                                    $madata = array (
                                            'order_id' => $order_id,
                                            'type' => 'M',
                                            'data' => implode(",",$child_orders),
                                    );
                                    db_query("REPLACE INTO ?:order_data ?e", $madata);
                                }
			}
			//die("Hello");


			return array($order_id, $action != 'save');

		}

	}



	return array(false, false);

}



/**

 * Order payment processing

 *

 * @param array $payment payment data

 * @param int $order_id order ID

 * @param bool $force_notification force user notification (true - notify, false - do not notify, order status properties will be skipped)

 */

function fn_start_payment($order_id, $force_notification = array())

{


	$order_info = fn_get_order_info($order_id);
	//Changes By Megha Sudan
	$redirect = notify_wholesale_subscription($order_info['items'],$_SESSION['auth']['user_id'],Registry::get('config.ws_membership_type'),$edit_step);
	if($redirect)
	{
		return array(CONTROLLER_STATUS_REDIRECT, "checkout.checkout?edit_step=step_three");
	}

	list($is_processor_script, $processor_data) = fn_check_processor_script($order_info['payment_id'], '');

	if ($is_processor_script) {

		set_time_limit(300);

		$idata = array (

			'order_id' => $order_id,

			'type' => 'S',

			'data' => TIME,

		);

		db_query("REPLACE INTO ?:order_data ?e", $idata);





		$index_script = INDEX_SCRIPT;

		$mode = MODE;



		include(DIR_PAYMENT_FILES . $processor_data['processor_script']);



		return fn_finish_payment($order_id, $pp_response, $force_notification);

	}



	return false;

}



/**

 * Finish order paymnent

 *

 * @param int $order_id order ID

 * @param array $pp_response payment response

 * @param bool $force_notification force user notification (true - notify, false - do not notify, order status properties will be skipped)

 */

function fn_finish_payment($order_id, $pp_response, $force_notification = array())

{

	// Change order status

	$valid_id = db_get_field("SELECT order_id FROM ?:order_data WHERE order_id = ?i AND type = 'S'", $order_id);



	if (!empty($valid_id)) {

		db_query("DELETE FROM ?:order_data WHERE order_id = ?i AND type = 'S'", $order_id);



		fn_update_order_payment_info($order_id, $pp_response);



		if ($pp_response['order_status'] == 'N' && !empty($_SESSION['cart']['placement_action']) && $_SESSION['cart']['placement_action'] == 'repay') {

			$pp_response['order_status'] = 'I';

		}



		fn_set_hook('finish_payment', $order_id, $pp_response, $force_notification);

    	$place_order = true;

		fn_change_order_status($order_id, $pp_response['order_status'], '', $force_notification,$place_order);

	}

}



//

// Store cart content in the customer's profile

//

function fn_save_cart_content(&$cart, $user_id, $type = 'C', $user_type = 'R')

{
	if (empty($user_id)) {

		if (fn_get_session_data('cu_id')) {

			$user_id = fn_get_session_data('cu_id');

		} else {

			$user_id = fn_crc32(uniqid(TIME));

			fn_set_session_data('cu_id', $user_id, COOKIE_ALIVE_TIME);

		}

		$user_type = 'U';

	}



	if (!empty($user_id)) {

		db_query("DELETE FROM ?:user_session_products WHERE user_id = ?i AND type = ?s AND user_type = ?s", $user_id, $type, $user_type);

		if (!empty($cart['products']) && is_array($cart['products'])) {

			$_cart_prods = $cart['products'];

			foreach ($_cart_prods as $_item_id => $_prod) {

				$_cart_prods[$_item_id]['user_id'] = $user_id;

				$_cart_prods[$_item_id]['timestamp'] = TIME;

				$_cart_prods[$_item_id]['type'] = $type;

				$_cart_prods[$_item_id]['user_type'] = $user_type;

				$_cart_prods[$_item_id]['item_id'] = $_item_id;

				$_cart_prods[$_item_id]['item_type'] = 'P';

				$_cart_prods[$_item_id]['extra'] = serialize($_prod);

				$_cart_prods[$_item_id]['amount'] = empty($_cart_prods[$_item_id]['amount']) ? 1 : $_cart_prods[$_item_id]['amount'];

				$_cart_prods[$_item_id]['session_id'] = Session::get_id();



				if (!empty($_cart_prods[$_item_id])) {
                                    
                                    //db_query('REPLACE INTO ?:user_session_products ?e', $_cart_prods[$_item_id]);
                                    $sql = "SELECT cscart_user_session_products.item_id, cscart_user_session_products.item_type, 
                                                        cscart_user_session_products.product_id, cscart_user_session_products.amount,
                                                        cscart_user_session_products.price, cscart_user_session_products.extra
                                                        FROM cscart_user_session_products WHERE cscart_user_session_products.user_id = '".$user_id."' AND 
                                                        cscart_user_session_products.type = 'C' AND cscart_user_session_products.item_id = '".$_item_id."' 
                                                        AND cscart_user_session_products.user_type ='".$user_type."'";
                                    $result = db_get_array($sql);
                                    
                                    if(empty($result)){
                                        db_query("INSERT INTO ?:user_session_products ?e", $_cart_prods[$_item_id]);
                                    }else{
                                        db_query("UPDATE ?:user_session_products SET ?u WHERE user_id = ?i AND type = 'C' AND item_id = ?i AND user_type = ?s", $_cart_prods[$_item_id], $_cart_prods[$_item_id]['user_id'],$_cart_prods[$_item_id]['item_id'],$_cart_prods[$_item_id]['user_type']);
                                    }
				}

			}

		}


		fn_set_hook('save_cart', $cart, $user_id, $type);
	}

	return true;

}



/**

 * Extract cart content from the customer's profile.

 * $type : C - cart, W - wishlist

 *

 * @param array $cart

 * @param integer $user_id

 * @param char $type

 *

 * @return void

 */

function fn_extract_cart_content(&$cart, $user_id, $type = 'C', $user_type = 'R')

{

	$auth = & $_SESSION['auth'];

	$old_session_id = '';



	// Restore cart content

	if (!empty($user_id)) {

		$item_types = fn_get_cart_content_item_types('X');

		$_prods = db_get_hash_array("SELECT * FROM ?:user_session_products WHERE user_id = ?i AND type = ?s AND user_type = ?s AND item_type IN (?a)", 'item_id', $user_id, $type, $user_type, $item_types);

		if (!empty($_prods) && is_array($_prods)) {

			$cart['products'] = empty($cart['products']) ? array() : $cart['products'];

			foreach ($_prods as $_item_id => $_prod) {

				$old_session_id = $_prod['session_id'];

				$_prod_extra = unserialize($_prod['extra']);

				unset($_prod['extra']);

				$cart['products'][$_item_id] = empty($cart['products'][$_item_id]) ? fn_array_merge($_prod, $_prod_extra, true) : $cart['products'][$_item_id];

			}

		}

	}



	fn_set_hook('extract_cart', $cart, $user_id, $type, $user_type);



	if ($type == 'C') {

		//fn_calculate_cart_content($cart, $auth, 'S', false, 'I');

	}

}

/*Modified by clues dev*/

function fn_extract_cart_content_with_session_id(&$cart, $session_id, $type = 'C', $user_type = 'R')

{

	$auth = & $_SESSION['auth'];

	$old_session_id = '';



	// Restore cart content

	if (!empty($session_id)) {

		$item_types = fn_get_cart_content_item_types('X');

		$_prods = db_get_hash_array("SELECT * FROM ?:user_session_products WHERE session_id = ?l AND type = ?s AND user_type = ?s AND item_type IN (?a)", 'item_id', $session_id, $type, $user_type, $item_types);
       
		//$_prods = db_get_hash_array("SELECT * FROM ?:user_session_products WHERE session_id = ?l AND type = ?s AND item_type IN (?a)", 'item_id', $session_id, $type, $item_types);
 
		if (!empty($_prods) && is_array($_prods)) {

			$cart['products'] = empty($cart['products']) ? array() : $cart['products'];

			foreach ($_prods as $_item_id => $_prod) {

				$old_session_id = $_prod['session_id'];

				$_prod_extra = unserialize($_prod['extra']);

				unset($_prod['extra']);

				$cart['products'][$_item_id] = empty($cart['products'][$_item_id]) ? fn_array_merge($_prod, $_prod_extra, true) : $cart['products'][$_item_id];

			}

		}

	}



	fn_set_hook('extract_cart', $cart, $user_id, $type, $user_type);



	if ($type == 'C') {

		fn_calculate_cart_content($cart, $auth, 'S', false, 'I');

	}
	$_SESSION['cart'] = $cart;

}

/*Modified by clues dev*/

function fn_extract_wishlist_content_with_session_id(&$wishlist, $session_id, $type = 'W', $user_type = 'R')

{

	$auth = & $_SESSION['auth'];

	$old_session_id = '';
	// Restore cart content

	if (!empty($session_id)) {

		$item_types = fn_get_cart_content_item_types('X');

		$_prods = db_get_hash_array("SELECT * FROM ?:user_session_products WHERE session_id = ?l AND type = ?s AND user_type = ?s AND item_type IN (?a)", 'item_id', $session_id, $type, $user_type, $item_types);
       
		//$_prods = db_get_hash_array("SELECT * FROM ?:user_session_products WHERE session_id = ?l AND type = ?s AND item_type IN (?a)", 'item_id', $session_id, $type, $item_types);
 
		if (!empty($_prods) && is_array($_prods)) {

			$wishlist['products'] = empty($wishlist['products']) ? array() : $wishlist['products'];

			foreach ($_prods as $_item_id => $_prod) {

				$old_session_id = $_prod['session_id'];

				$_prod_extra = unserialize($_prod['extra']);

				unset($_prod['extra']);

				$wishlist['products'][$_item_id] = empty($wishlist['products'][$_item_id]) ? fn_array_merge($_prod, $_prod_extra, true) : $wishlist['products'][$_item_id];

			}

		}

	}
	$_SESSION['wishlist'] = $wishlist;

}


/**

 * get cart content item types

 *

 * @param char $action

 * V - for View mode

 * X - for eXtract mode

 * @return array

 */

function fn_get_cart_content_item_types($action = 'V')

{

	$item_types = array('P');



	fn_set_hook('get_cart_item_types', $item_types, $action);



	return $item_types;

}



//

// Get order name

//

function fn_get_order_name($order_id)

{

	$total = db_get_field("SELECT total FROM ?:orders WHERE order_id = ?i", $order_id);

	if ($total == '') {

		return false;

	}



	if (Registry::get('settings.General.alternative_currency') == "Y") {

		$result = fn_format_price_by_currency($total, CART_PRIMARY_CURRENCY);

		if (CART_SECONDARY_CURRENCY != CART_PRIMARY_CURRENCY) {

			$result .= ' (' . fn_format_price_by_currency($total) . ')';

		}

	} else {

		$result = fn_format_price_by_currency($total);

	}



	return $order_id . ' - ' . $result;

}



function fn_format_price_by_currency($price, $currency_code = CART_SECONDARY_CURRENCY)

{

	$currencies = Registry::get('currencies');

	$currency = $currencies[$currency_code];

	$result = fn_format_rate_value($price, 'F', $currency['decimals'], $currency['decimals_separator'], $currency['thousands_separator'], $currency['coefficient']);

	if ($currency['after'] == 'Y') {

		$result .= ' ' . $currency['symbol'];

	} else {

		$result = $currency['symbol'] . $result;

	}



	return $result;

}



//

// Get order info

//

function fn_get_order_info($order_id, $native_language = false, $format_info = true, $get_edp_files = false, $skip_static_values = false)

{

	if (!empty($order_id)) {

		$condition = fn_get_company_condition();

		$order = db_get_row("SELECT * FROM ?:orders WHERE ?:orders.order_id = ?i $condition", $order_id);



		if (!empty($order)) {

			$lang_code = ($native_language == true) ? $order['lang_code'] : CART_LANGUAGE;



			$order['payment_method'] = fn_get_payment_method_data($order['payment_id'], $lang_code);

			

			// Get additional profile fields

			$additional_fields = db_get_hash_single_array("SELECT field_id, value FROM ?:profile_fields_data WHERE object_id = ?i AND object_type = 'O'", array('field_id', 'value'), $order_id);

			$order['fields'] = $additional_fields;
			// Changed by Sudhir dt 29th octo 2012 to optimize query start here
			/*$query = "SELECT ?:order_details.*, ?:product_descriptions.product, ?:products.merchant_reference_number , clues_shipping_estimation.name as ship_time "
					. " FROM ?:order_details "
					. " LEFT JOIN ?:products ON ?:order_details.product_id = ?:products.product_id "
					. " LEFT JOIN ?:product_descriptions ON ?:order_details.product_id = ?:product_descriptions.product_id "
                                        . " LEFT JOIN clues_shipping_estimation on ?:products.product_shipping_estimation = clues_shipping_estimation.id"
					. " AND ?:product_descriptions.lang_code = ?s "
					. " WHERE ?:order_details.order_id = ?i ORDER BY ?:product_descriptions.product ";*/
                        // Changed by Munish at 25 sep 2013 to get child orderid on complete page
			$query = "SELECT ?:orders.order_id as child,?:order_details.*, ?:product_descriptions.product, ?:products.merchant_reference_number , clues_shipping_estimation.name as ship_time "
					. " FROM ?:order_details "
					. " LEFT JOIN ?:products ON ?:order_details.product_id = ?:products.product_id "
					. " LEFT JOIN ?:product_descriptions ON ?:order_details.product_id = ?:product_descriptions.product_id "
                                        . " LEFT JOIN clues_shipping_estimation on ?:products.product_shipping_estimation = clues_shipping_estimation.id"
                                        . " LEFT JOIN ?:orders ON ?:orders.parent_order_id = ?:order_details.order_id AND ?:orders.company_id = ?:products.company_id"
                                        . " WHERE ?:order_details.order_id = ?i"
					. " AND ?:product_descriptions.lang_code = ?s ";

			//$order['items'] = db_get_hash_array($query, 'item_id', $lang_code, $order_id);
			$order['items'] = db_get_hash_array($query, 'item_id', $order_id, $lang_code);
			
// Changed by Sudhir dt 29th octo 2012 to optimize query end here

			/*$order['items'] = db_get_hash_array("SELECT ?:order_details.*, ?:product_descriptions.product FROM ?:order_details LEFT JOIN ?:product_descriptions ON ?:order_details.product_id = ?:product_descriptions.product_id AND ?:product_descriptions.lang_code = ?s WHERE ?:order_details.order_id = ?i ORDER BY ?:product_descriptions.product", 'item_id', $lang_code, $order_id);*/

                        
			$order['promotions'] = unserialize($order['promotions']);

			if (!empty($order['promotions'])) { // collect additional data

				$params = array (

					'promotion_id' => array_keys($order['promotions']),

				);

				list($promotions) = fn_get_promotions($params);

				foreach ($promotions as $pr_id => $p) {

					$order['promotions'][$pr_id]['name'] = $p['name'];

					$order['promotions'][$pr_id]['short_description'] = $p['short_description'];

				}

			}



			// Get additional data

			$additional_data = db_get_hash_single_array("SELECT type, data FROM ?:order_data WHERE order_id = ?i", array('type', 'data'), $order_id);



			$order['taxes'] = array();

			$order['tax_subtotal'] = 0;

			$order['display_shipping_cost'] = $order['shipping_cost'];



			// Replace country, state and title values with their descriptions

			$order_company_id = isset($order['company_id']) ? $order['company_id'] : ''; // company_id will be rewritten by user field, so need to save it.

			fn_add_user_data_descriptions($order, $lang_code);

			$order['company_id'] = $order_company_id;

			

			$order['need_shipping'] = false;

			$deps = array();



				// Get shipments common information

			if (Registry::get('settings.General.use_shipments') == 'Y') {

				$order['shipment_ids'] = db_get_fields('SELECT sh.shipment_id FROM ?:shipments AS sh LEFT JOIN ?:shipment_items AS s_items ON (sh.shipment_id = s_items.shipment_id) WHERE s_items.order_id = ?i GROUP BY s_items.shipment_id', $order_id);

				

				$_products = db_get_array("SELECT item_id, SUM(amount) AS amount FROM ?:shipment_items WHERE order_id = ?i GROUP BY item_id", $order_id);

				$shipped_products = array();

				

				if (!empty($_products)) {

					foreach ($_products as $_product) {

						$shipped_products[$_product['item_id']] = $_product['amount'];

					}

				}

				unset($_products);

			}

				foreach ($order['items'] as $k => $v) {

				//Check for product existance

				if (empty($v['product'])) {

					$order['items'][$k]['deleted_product'] = true;

				}

				$order['items'][$k]['discount'] = 0;



				$v['extra'] = @unserialize($v['extra']);

				if ($skip_static_values == false && !empty($v['extra']['product'])) {

					$order['items'][$k]['product'] = $v['extra']['product'];

				}



				$order['items'][$k]['company_id'] = empty($v['extra']['company_id']) ? 0 : $v['extra']['company_id'];



				if (!empty($v['extra']['discount']) && floatval($v['extra']['discount'])) {

					$order['items'][$k]['discount'] = $v['extra']['discount'];

					$order['use_discount'] = true;

				}



				if (!empty($v['extra']['promotions'])) {

					$order['items'][$k]['promotions'] = $v['extra']['promotions'];

				}



				if (isset($v['extra']['base_price'])) {

					$order['items'][$k]['base_price'] = floatval($v['extra']['base_price']);

				} else {

					$order['items'][$k]['base_price'] = $v['price'];

				}

				$order['items'][$k]['original_price'] = $order['items'][$k]['base_price'];



				// Form hash key for this product

				$order['items'][$k]['cart_id'] = $v['item_id'];

				$deps['P_'.$order['items'][$k]['cart_id']] = $k;



				// Unserialize and collect product options information

				if (!empty($v['extra']['product_options'])) {

					if ($format_info == true) {

						$order['items'][$k]['product_options'] = ($skip_static_values == false && !empty($v['extra']['product_options_value'])) ? $v['extra']['product_options_value'] : fn_get_selected_product_options_info($v['extra']['product_options'], $lang_code);

					}



					if (empty($v['extra']['stored_price']) || (!empty($v['extra']['stored_price']) && $v['extra']['stored_price'] != 'Y')) { // apply modifiers if this is not the custom price

						$order['items'][$k]['original_price'] = fn_apply_options_modifiers($v['extra']['product_options'], $order['items'][$k]['base_price'], 'P', ($skip_static_values == false && !empty($v['extra']['product_options_value'])) ? $v['extra']['product_options_value'] : array());

					}

				}



				$order['items'][$k]['extra'] = $v['extra'];

				$order['items'][$k]['tax_value'] = 0;

				$order['items'][$k]['display_subtotal'] = $order['items'][$k]['subtotal'] = ($v['price'] * $v['amount']);



				// Get information about edp

				if ($get_edp_files == true && $order['items'][$k]['extra']['is_edp'] == 'Y') {

					$order['items'][$k]['files'] = db_get_array("SELECT ?:product_files.file_id, ?:product_files.activation_type, ?:product_files.max_downloads, ?:product_file_descriptions.file_name, ?:product_file_ekeys.active, ?:product_file_ekeys.downloads, ?:product_file_ekeys.ekey, ?:product_file_ekeys.ttl FROM ?:product_files LEFT JOIN ?:product_file_descriptions ON ?:product_file_descriptions.file_id = ?:product_files.file_id AND ?:product_file_descriptions.lang_code = ?s LEFT JOIN ?:product_file_ekeys ON ?:product_file_ekeys.file_id = ?:product_files.file_id AND ?:product_file_ekeys.order_id = ?i WHERE ?:product_files.product_id = ?i", $lang_code, $order_id, $v['product_id']);

				}

						// Get shipments information

				if (Registry::get('settings.General.use_shipments') == 'Y') {

					if (isset($shipped_products[$k])) {

						$order['items'][$k]['shipped_amount'] = $shipped_products[$k];

						$order['items'][$k]['shipment_amount'] = $v['amount'] - $shipped_products[$k];



					} else {

						$order['items'][$k]['shipped_amount'] = 0;

						$order['items'][$k]['shipment_amount'] = $v['amount'];

					}

					

					if ($order['items'][$k]['shipped_amount'] < $order['items'][$k]['amount']) {

						$order['need_shipment'] = true;

					}

				}

						// Check if the order needs the shipping method

				if (!($v['extra']['is_edp'] == 'Y' && (!isset($v['extra']['edp_shipping']) || $v['extra']['edp_shipping'] != 'Y'))) {

					$order['need_shipping'] = true;

				}

			}



			if (Registry::get('settings.Suppliers.enable_suppliers') == 'Y') {

				$order['companies'] = fn_get_products_companies($order['items']);

				$order['have_suppliers'] = fn_check_companies_have_suppliers($order['companies']);

			} elseif (PRODUCT_TYPE == 'MULTIVENDOR') {

				$order['have_suppliers'] = empty($order['company_id']) ? 'N' : 'Y';

			}



			// Unserialize and collect taxes information

			if (!empty($additional_data['T'])) {

				$order['taxes'] = unserialize($additional_data['T']);

				if (is_array($order['taxes'])) {

					foreach ($order['taxes'] as  $tax_id => $tax_data) {

						foreach ($tax_data['applies'] as $_id => $value) {

							if (strpos($_id, 'P_') !== false && isset($deps[$_id])) {

								$order['items'][$deps[$_id]]['tax_value'] += $value;

								if ($tax_data['price_includes_tax'] != 'Y') {

									$order['items'][$deps[$_id]]['subtotal'] += $value;

									$order['items'][$deps[$_id]]['display_subtotal'] += (Registry::get('settings.Appearance.cart_prices_w_taxes') == 'Y') ? $value : 0;

									$order['tax_subtotal'] += $value;

								}

							}

							if (strpos($_id, 'S_') !== false && Registry::get('settings.Appearance.cart_prices_w_taxes') == 'Y') {

								if ($tax_data['price_includes_tax'] != 'Y') {

									$order['display_shipping_cost'] += $value;

								}

							}

						}

					}

				} else {

					$order['taxes'] = array();

				}

			}



			if (!empty($additional_data['C'])) {

				$order['coupons'] = unserialize($additional_data['C']);

			}



			if (!empty($additional_data['R'])) {

				$order['secondary_currency'] = unserialize($additional_data['R']);

			}



			// Recalculate subtotal

			$order['subtotal'] = $order['display_subtotal'] = 0;

			foreach ($order['items'] as $v) {

				$order['subtotal'] += $v['subtotal'];

				$order['display_subtotal'] += $v['display_subtotal'];

			}



			// Unserialize and collect payment information

			if (!empty($additional_data['P'])) {

				$order['payment_info'] = unserialize(fn_decrypt_text($additional_data['P']));

			}



			if (empty($order['payment_info']) || !is_array($order['payment_info'])) {

				$order['payment_info'] = array();

			}



			// Get shipping information

			if (!empty($additional_data['L'])) {

				$order['shipping'] = unserialize($additional_data['L']);

			}



			$order['doc_ids'] = db_get_hash_single_array("SELECT type, doc_id FROM ?:order_docs WHERE order_id = ?i", array('type', 'doc_id'), $order_id);

		}
//this is for giving clues bucks if user place order with anonymous checkout and he is registered by Ankur Goyal 
     /* if($order['user_id']!=0)
	  {
		  //$additional_data['W']=$order['total']*0.02;
	  }*/
//this is for giving clues bucks if user place order with anonymous checkout and he is registered by Ankur Goyal 
		fn_set_hook('get_order_info', $order, $additional_data);

   
		return $order;

	}



	return false;

}



//

// Get order short info

//

function fn_get_order_short_info($order_id)

{

	if (!empty($order_id)) {

		$order = db_get_row("SELECT total, status, firstname, lastname, timestamp FROM ?:orders WHERE order_id = ?i", $order_id);



		return $order;

	}



	return false;

}



//

// Change order status

//

function fn_change_order_status($order_id, $status_to, $status_from = '', $force_notification = array(), $place_order = false)

{
	//change by ankur to prevent admin user to change order status who do not have permission
	if(AREA!='A')
	{

        $order_info = fn_get_order_info($order_id, true);

        /* validation added by chandan to restrict staus change for new order 21st apr */
        if ($order_info['is_parent_order'] == 'N' && AREA == 'C' && in_array($status_to, array('P', 'F', 'O', '93')) && $order_info['status'] == 'N') {
            
        } elseif ($order_info['is_parent_order'] == 'N' && AREA == 'C' && in_array($status_to, array('Q', '92')) && $order_info['status'] == 'O') {
            
        } elseif ($order_info['is_parent_order'] == 'N' && AREA == 'C' && $status_to == '93' && in_array($order_info['status'], array('K', 'F'))) {
	
	}elseif($order_info['is_parent_order'] == 'N' && AREA == 'C' && $status_to == 'P' && $order_info['status'] == Registry::get('config.cbd_pending_status')){
            
        } elseif ($order_info['is_parent_order'] == 'N' && AREA == 'C' && in_array($status_to, array('P', 'F', 'O', '93', 'Q', '92'))) {
            if (Registry::get('config.log_status_change_attempt')) {
                //log status change attempt
                $user_id = ($_SESSION['auth']['user_id'] != 0 ? $_SESSION['auth']['user_id'] : $order_info['user_id']);
                db_query("INSERT INTO clues_status_change_attempt (order_id,from_status,to_status,user_id,timestamp,payment_id,area) values('" . $order_id . "','" . $order_info['status'] . "','" . $status_to . "'," . $user_id . "," . TIME . "," . $order_info['payment_id'] . ",'" . AREA . "')");
            }
            return false;
        }
        /* validation added by chandan to restrict staus change for new order */


        if (defined('CART_LOCALIZATION') && $order_info['localization_id'] && CART_LOCALIZATION != $order_info['localization_id']) {

            Registry::get('view')->assign('localization', fn_get_localization_data(CART_LOCALIZATION));
        }



        $order_statuses = fn_get_statuses(STATUSES_ORDER, false, true);



        if (empty($status_from)) {

            $status_from = $order_info['status'];
        }
        /* manipulation by ankur if gc is used in order  */
        if (($status_from == 'N' || $status_from == 'F') && (!in_array($status_to, Registry::get('config.left_for_gc_order'))) && isset($order_info['use_gift_certificates'])) {
            $ret = fn_apply_gift_certificate($order_info, $status_to, $status_from);
            if ($ret == '2') { // this is indicating that problem in order placing from customer side
                $status_to = 'K';   //change order status to payment pending
            }
        }
        /* Code End      */


        if (empty($order_info) || empty($status_to) || $status_from == $status_to) {

            return false;
        }


        if ($order_info['is_parent_order'] == 'Y') {

            if (!empty($order_statuses[$status_to]['remove_cc_info']) && $order_statuses[$status_to]['remove_cc_info'] == 'Y' && !empty($order_info['payment_info'])) {

                fn_cleanup_payment_info($order_id, $order_info['payment_info'], true);
            }



            $child_ids = db_get_fields("SELECT order_id FROM ?:orders WHERE parent_order_id = ?i", $order_id);

            $res = true;

            foreach ($child_ids as $child_order_id) {

                $_res = fn_change_order_status($child_order_id, $status_to, '', $force_notification, $place_order);
            }

            $res = $res && $_res;



            return $res;
        }



        $_updated_ids = array();

        $_error = false;



        foreach ($order_info['items'] as $k => $v) {



            // Generate ekey if EDP is ordered

            if (!empty($v['extra']['is_edp']) && $v['extra']['is_edp'] == 'Y') {

                continue; // don't track inventory
            }



            // Update product amount if inventory tracking is enabled

            if (Registry::get('settings.General.inventory_tracking') == 'Y') {

                if ($order_statuses[$status_to]['inventory'] == 'D' && $order_statuses[$status_from]['inventory'] == 'I') {

                    // decrease amount

                    if (fn_update_product_amount($v['product_id'], $v['amount'], @$v['extra']['product_options'], '-') == false) {

                        $status_to = 'B'; //backorder

                        $_error = true;

                        $msg = str_replace('[product]', fn_get_product_name($v['product_id']) . ' #' . $v['product_id'], fn_get_lang_var('low_stock_subj'));

                        fn_set_notification('W', fn_get_lang_var('warning'), $msg);



                        break;
                    } else {

                        $_updated_ids[] = $k;
                    }
                } elseif ($order_statuses[$status_to]['inventory'] == 'I' && $order_statuses[$status_from]['inventory'] == 'D') {

                    // increase amount

                    fn_update_product_amount($v['product_id'], $v['amount'], @$v['extra']['product_options'], '+');
                }
            }
        }



        if ($_error) {

            if (!empty($_updated_ids)) {

                foreach ($_updated_ids as $id) {

                    // increase amount

                    fn_update_product_amount($order_info['items'][$id]['product_id'], $order_info['items'][$id]['amount'], @$order_info['items'][$id]['extra']['product_options'], '+');
                }

                unset($_updated_ids);
            }



            if ($status_from == $status_to) {

                return false;
            }
        }



        fn_promotion_post_processing($status_to, $status_from, $order_info, $force_notification);



        fn_set_hook('change_order_status', $status_to, $status_from, $order_info, $force_notification, $order_statuses, $place_order = true);



        // Log order status change

        fn_log_event('orders', 'status', array(
            'order_id' => $order_id,
            'status_from' => $status_from,
            'status_to' => $status_to,
        ));



        if (!empty($order_statuses[$status_to]['appearance_type']) && ($order_statuses[$status_to]['appearance_type'] == 'I' || $order_statuses[$status_to]['appearance_type'] == 'C') && !db_get_field("SELECT doc_id FROM ?:order_docs WHERE type = ?s AND order_id = ?i", $order_statuses[$status_to]['appearance_type'], $order_id)) {

            $_data = array(
                'order_id' => $order_id,
                'type' => $order_statuses[$status_to]['appearance_type']
            );

            $order_info['doc_ids'][$order_statuses[$status_to]['appearance_type']] = db_query("INSERT INTO ?:order_docs ?e", $_data);
        }



        // Check if we need to remove CC info

        if (!empty($order_statuses[$status_to]['remove_cc_info']) && $order_statuses[$status_to]['remove_cc_info'] == 'Y' && !empty($order_info['payment_info'])) {

            fn_cleanup_payment_info($order_id, $order_info['payment_info'], true);
        }



        $edp_data = fn_generate_ekeys_for_edp(array('status_from' => $status_from, 'status_to' => $status_to), $order_info);

        $order_info['status'] = $status_to;

        if ($order_statuses[$status_to]['initiate_refund'] == 'Y') {
            fn_start_refund_request($order_info);
        }

        fn_order_notification($order_info, $edp_data, $force_notification);

        if (db_query("UPDATE ?:orders SET status = ?s WHERE order_id = ?i", $status_to, $order_id)) {
            //code to assign wholesale membership if valid
            if ($status_to == "P") {
                $mtype_id = Registry::get('config.ws_membership_type');
                assign_wholesale_membership($order_info['user_id'], $order_id, $mtype_id);
            }
            //code to assign wholesale membership ends here
            //auction operations			
            if ($status_to == 'P') {
                $auction_product_ids = array();
                foreach ($order_info['items'] as $key => $value) {
                    array_push($auction_product_ids, $value['product_id']);
                }
                $auction_user_id = $order_info['user_id'];
                $auction_update_result = allow_winner_user_for_auction($auction_user_id, $auction_product_ids);
            }
            //auction operations ends

            if ($_SESSION['cart']['user_data']['verified'] == 1 && $status_to == 'O') {
                //$resp=fn_send_sms_to_user($order_id,$status_to);
            } else {
                $resp = fn_send_sms_to_user($order_id, $status_to);
            }
        }
        if ($status_to == 'P' || $status_to == 'O' || $status_to == '92') { // '92' added by anuj for cod auto confirm
            $sql = "select coupon_codes from ?:orders where order_id='" . $order_id . "'";
            $internalcoupon = db_get_field($sql);
            if ($internalcoupon) {
                $sql = "select data from ?:order_data where order_id='" . $order_id . "' and type='C'";
                $rescpn = db_get_field($sql);
                $rescpn = unserialize($rescpn); //hprahi
                if (is_array($rescpn)) {
                    foreach ($rescpn as $key => $value) {
                        $sql = "update clues_mass_coupon_code set status='USED', dateofused='" . time() . "' where coupon_code='" . $key . "'";
                        db_query($sql);
                    }
                }
            }
        }

        return true;
    } else {
        $check_access = fn_check_priviledges("change_order_status");
        if ($check_access != '' || $_SESSION['auth']['user_id'] == 1) {
            $order_info = fn_get_order_info($order_id, true);

            if (defined('CART_LOCALIZATION') && $order_info['localization_id'] && CART_LOCALIZATION != $order_info['localization_id']) {

                Registry::get('view')->assign('localization', fn_get_localization_data(CART_LOCALIZATION));
            }



            $order_statuses = fn_get_statuses(STATUSES_ORDER, false, true);



            if (empty($status_from)) {

                $status_from = $order_info['status'];
            }
            /* modified by restrict the order transtion based on user group id */
            $user_group_ids = $_SESSION['auth']['usergroup_ids'];

            if ($_SESSION['auth']['user_id'] != '1' && CONTROLLER != 'order_management') {
                $is_allowed = fn_check_status_permission($user_group_ids, $status_from, $status_to);

                if (empty($is_allowed)) {
                    fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('no_permission_for_this_order_transition'));
                    return false;
                }
            }
            /* modified by restrict the order transtion based on user group id */


            if (empty($order_info) || empty($status_to) || $status_from == $status_to) {

                return false;
            }
            /* manipulation by ankur if gc is used in order  */
            if (in_array($status_from, Registry::get('config.left_for_gc_order')) && (!in_array($status_to, Registry::get('config.left_for_gc_order'))) && isset($order_info['use_gift_certificates'])) {
                $return = fn_apply_gift_certificate($order_info, $status_to, $status_from);
                if (!$return) {
                    return false;
                }
            } else if (!in_array($status_from, Registry::get('config.left_for_gc_order')) && in_array($status_to, Registry::get('config.left_for_gc_order')) && isset($order_info['use_gift_certificates'])) {
                fn_remove_gift_certificate($order_info);
            }

            /* Code End      */


            if ($order_info['is_parent_order'] == 'Y') {

                if (!empty($order_statuses[$status_to]['remove_cc_info']) && $order_statuses[$status_to]['remove_cc_info'] == 'Y' && !empty($order_info['payment_info'])) {

                    fn_cleanup_payment_info($order_id, $order_info['payment_info'], true);
                }



                $child_ids = db_get_fields("SELECT order_id FROM ?:orders WHERE parent_order_id = ?i", $order_id);

                $res = true;

                foreach ($child_ids as $child_order_id) {

                    $_res = fn_change_order_status($child_order_id, $status_to, '', $force_notification, $place_order);
                }

                $res = $res && $_res;



                return $res;
            }



            $_updated_ids = array();

            $_error = false;



            foreach ($order_info['items'] as $k => $v) {



                // Generate ekey if EDP is ordered

                if (!empty($v['extra']['is_edp']) && $v['extra']['is_edp'] == 'Y') {

                    continue; // don't track inventory
                }



                // Update product amount if inventory tracking is enabled

                if (Registry::get('settings.General.inventory_tracking') == 'Y') {

                    if ($order_statuses[$status_to]['inventory'] == 'D' && $order_statuses[$status_from]['inventory'] == 'I') {

                        // decrease amount

                        if (fn_update_product_amount($v['product_id'], $v['amount'], @$v['extra']['product_options'], '-') == false) {

                            $status_to = 'B'; //backorder

                            $_error = true;

                            $msg = str_replace('[product]', fn_get_product_name($v['product_id']) . ' #' . $v['product_id'], fn_get_lang_var('low_stock_subj'));

                            fn_set_notification('W', fn_get_lang_var('warning'), $msg);



                            break;
                        } else {

                            $_updated_ids[] = $k;
                        }
                    } elseif ($order_statuses[$status_to]['inventory'] == 'I' && $order_statuses[$status_from]['inventory'] == 'D') {

                        // increase amount

                        fn_update_product_amount($v['product_id'], $v['amount'], @$v['extra']['product_options'], '+');
                    }
                }
            }



            if ($_error) {

                if (!empty($_updated_ids)) {

                    foreach ($_updated_ids as $id) {

                        // increase amount

                        fn_update_product_amount($order_info['items'][$id]['product_id'], $order_info['items'][$id]['amount'], @$order_info['items'][$id]['extra']['product_options'], '+');
                    }

                    unset($_updated_ids);
                }



                if ($status_from == $status_to) {

                    return false;
                }
            }



            fn_promotion_post_processing($status_to, $status_from, $order_info, $force_notification);



            fn_set_hook('change_order_status', $status_to, $status_from, $order_info, $force_notification, $order_statuses, $place_order = true);



            // Log order status change

            fn_log_event('orders', 'status', array(
                'order_id' => $order_id,
                'status_from' => $status_from,
                'status_to' => $status_to,
            ));



            if (!empty($order_statuses[$status_to]['appearance_type']) && ($order_statuses[$status_to]['appearance_type'] == 'I' || $order_statuses[$status_to]['appearance_type'] == 'C') && !db_get_field("SELECT doc_id FROM ?:order_docs WHERE type = ?s AND order_id = ?i", $order_statuses[$status_to]['appearance_type'], $order_id)) {

                $_data = array(
                    'order_id' => $order_id,
                    'type' => $order_statuses[$status_to]['appearance_type']
                );

                $order_info['doc_ids'][$order_statuses[$status_to]['appearance_type']] = db_query("INSERT INTO ?:order_docs ?e", $_data);
            }



            // Check if we need to remove CC info

            if (!empty($order_statuses[$status_to]['remove_cc_info']) && $order_statuses[$status_to]['remove_cc_info'] == 'Y' && !empty($order_info['payment_info'])) {

                fn_cleanup_payment_info($order_id, $order_info['payment_info'], true);
            }



            $edp_data = fn_generate_ekeys_for_edp(array('status_from' => $status_from, 'status_to' => $status_to), $order_info);

            $order_info['status'] = $status_to;



            fn_order_notification($order_info, $edp_data, $force_notification);
            if ($status_to == 'P' || $status_to == 'O' || $status_to == '92') {// '92' added by anuj for cod auto confirm
                if ($order_statuses[$status_to]['initiate_refund'] == 'Y') {
                    fn_start_refund_request($order_info);
                }
                $sql = "select coupon_codes from ?:orders where order_id='" . $order_id . "'";
                $internalcoupon = db_get_field($sql);
                if ($internalcoupon) {
                    $sql = "select data from ?:order_data where order_id='" . $order_id . "' and type='C'";
                    $rescpn = db_get_field($sql);
                    $rescpn = unserialize($rescpn); //hprahi
                    if (is_array($rescpn)) {
                        foreach ($rescpn as $key => $value) {
                            $sql = "update clues_mass_coupon_code set status='USED', dateofused='" . time() . "' where coupon_code='" . $key . "'";
                            db_query($sql);
                        }
                    }
                }
            }

            if (db_query("UPDATE ?:orders SET status = ?s WHERE order_id = ?i", $status_to, $order_id)) {
                //code to assign wholesale membership if valid
                if ($status_to == "P") {
                    $mtype_id = Registry::get('config.ws_membership_type');
                    assign_wholesale_membership($order_info['user_id'], $order_id, $mtype_id);
                }
                //code to assign wholesale membership ends here
                //auction operations
                if ($status_to == 'P') {
                    $auction_product_ids = array();
                    foreach ($order_info['items'] as $key => $value) {
                        array_push($auction_product_ids, $value['product_id']);
                    }
                    $auction_user_id = $order_info['user_id'];
                    $auction_update_result = allow_winner_user_for_auction($auction_user_id, $auction_product_ids);
                }
                //auction operations ends

                $resp = fn_send_sms_to_user($order_id, $status_to);

                return true;
            }
        } else {
            fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('not_have_permission'));
            if (CONTROLLER == 'orders') {
                return false;
            } else {
                return array(CONTROLLER_STATUS_REDIRECT, $_SERVER['HTTP_REFERER']);
            }
        }
    }
}



/**

 * Function delete order

 *

 * @param int $order_id

 */

function fn_delete_order($order_id)

{

	if (defined('COMPANY_ID')) {

		fn_company_access_denied_notification();

		return false;

	}



	// Log order deletion

	fn_log_event('orders', 'delete', array (

		'order_id' => $order_id,

	));



	fn_change_order_status($order_id, STATUS_INCOMPLETED_ORDER, '', fn_get_notification_rules(array(), false)); // incomplete to increase inventory



	fn_set_hook('delete_order', $order_id);



	db_query("DELETE FROM ?:new_orders WHERE order_id = ?i", $order_id);

	db_query("DELETE FROM ?:order_data WHERE order_id = ?i", $order_id);

	db_query("DELETE FROM ?:order_details WHERE order_id = ?i", $order_id);

	db_query("DELETE FROM ?:orders WHERE order_id = ?i", $order_id);

	db_query("DELETE FROM ?:product_file_ekeys WHERE order_id = ?i", $order_id);

	db_query("DELETE FROM ?:profile_fields_data WHERE object_id = ?i AND object_type='O'", $order_id);

	db_query("DELETE FROM ?:order_docs WHERE order_id = ?i", $order_id);

}



/**

 * Function generate edp ekeys for email notification

 *

 * @param array $statuses order statuses

 * @param array $order_info order information

 * @param array $active_files array with file download statuses

 * @return array $edp_data

 */



function fn_generate_ekeys_for_edp($statuses, $order_info, $active_files = array())

{

	$edp_data = array();

	$order_statuses = fn_get_statuses(STATUSES_ORDER, false, true);



	foreach ($order_info['items'] as $v) {



		// Generate ekey if EDP is ordered

		if (!empty($v['extra']['is_edp']) && $v['extra']['is_edp'] == 'Y') {



			$activations = db_get_hash_single_array("SELECT activation_type, file_id FROM ?:product_files WHERE product_id = ?i", array('file_id', 'activation_type'), $v['product_id']);



			foreach ($activations as $file_id => $activation_type) {



				$send_notification = false;



				// Check if ekey already was generated for this file

				$_ekey = db_get_row("SELECT ekey, active, file_id, product_id, order_id, ekey FROM ?:product_file_ekeys WHERE file_id = ?i AND order_id = ?i", $file_id, $order_info['order_id']);

				if (!empty($_ekey)) {

					// If order status changed to "Processed"

					if (($activation_type == 'P') && !empty($statuses)) {

						if ($order_statuses[$statuses['status_to']]['inventory'] == 'D' && substr_count('O', $statuses['status_to']) == 0 && ($order_statuses[$statuses['status_from']]['inventory'] != 'D' || substr_count('O', $statuses['status_from']) > 0)) {

							$active_files[$v['product_id']][$file_id] = 'Y';

						} elseif (($order_statuses[$statuses['status_to']]['inventory'] != 'D' && substr_count('O', $statuses['status_from']) == 0 || substr_count('O', $statuses['status_to']) > 0) && $order_statuses[$statuses['status_from']]['inventory'] == 'D') {

							$active_files[$v['product_id']][$file_id] = 'N';

						}

					}



					if (!empty($active_files[$v['product_id']][$file_id])) {

						db_query('UPDATE ?:product_file_ekeys SET ?u WHERE file_id = ?i AND product_id = ?i AND order_id = ?i', array('active' => $active_files[$v['product_id']][$file_id]), $_ekey['file_id'], $_ekey['product_id'], $_ekey['order_id']);



						if ($active_files[$v['product_id']][$file_id] == 'Y' && $_ekey['active'] !== 'Y') {

							$edp_data[$v['product_id']]['files'][$file_id] = $_ekey;

						}

					}



				} else {

					$_data = array (

						'file_id' => $file_id,

						'product_id' => $v['product_id'],

						'ekey' => md5(uniqid(rand())),

						'ttl' => (TIME + (Registry::get('settings.General.edp_key_ttl') * 60 * 60)),

						'order_id' => $order_info['order_id'],

						'activation' => $activation_type

					);



					// Activate the file if type is "Immediately" or "After full payment" and order statuses is from "paid" group

					if ($activation_type == 'I' || !empty($active_files[$v['product_id']][$file_id]) && $active_files[$v['product_id']][$file_id] == 'Y' || ($activation_type == 'P' && $order_statuses[$statuses['status_to']]['inventory'] == 'D' && substr_count('O', $statuses['status_to']) == 0 && ($order_statuses[$statuses['status_from']]['inventory'] != 'D' || substr_count('O', $statuses['status_from']) > 0 ))) {

						$_data['active'] = 'Y';

						$edp_data[$v['product_id']]['files'][$file_id] = $_data;

					}



					db_query('REPLACE INTO ?:product_file_ekeys ?e', $_data);

				}



				if (!empty($edp_data[$v['product_id']]['files'][$file_id])) {

					$edp_data[$v['product_id']]['files'][$file_id]['file_size'] = db_get_field("SELECT file_size FROM ?:product_files WHERE file_id = ?i", $file_id);

					$edp_data[$v['product_id']]['files'][$file_id]['file_name'] = db_get_field("SELECT file_name FROM ?:product_file_descriptions WHERE file_id = ?i AND lang_code = ?s", $file_id, CART_LANGUAGE);

				}

			}

		}

	}



	return $edp_data;

}



//

// Update order payment information

//

function fn_update_order_payment_info($order_id, $pp_response)

{

	if (empty($order_id) || empty($pp_response) || !is_array($pp_response)) {

		return false;

	}



	$payment_info = db_get_field("SELECT data FROM ?:order_data WHERE order_id = ?i AND type = 'P'", $order_id);

	if (!empty($payment_info)) {

		$payment_info = unserialize(fn_decrypt_text($payment_info));

	} else {

		$payment_info = array();

	}





	foreach ($pp_response as $k => $v) {

		$payment_info[$k] = $v;

	}



	$data = array (

		'data' => fn_encrypt_text(serialize($payment_info)),

		'order_id' => $order_id,

		'type' => 'P'

	);



	db_query("REPLACE INTO ?:order_data ?e", $data);

	

	$child_orders_ids = db_get_fields("SELECT order_id FROM ?:orders WHERE parent_order_id = ?i", $order_id);

	if (!empty($child_orders_ids)) {

		foreach ($child_orders_ids as $child_id) {

			fn_update_order_payment_info($child_id, $pp_response);

		}

	}



	return true;

}



//

// Get all shippings list

//





function fn_get_shippings($simple, $lang_code = CART_LANGUAGE)

{

	$conditions = '1';



	if (AREA == 'C') {

		$conditions .= " AND (" . fn_find_array_in_set($_SESSION['auth']['usergroup_ids'], 'a.usergroup_ids', true) . ")";

		$conditions .= " AND a.status = 'A'";

		$conditions .= fn_get_localizations_condition('a.localization');

	}



	if ($simple == true) {

		return db_get_hash_single_array("SELECT a.shipping_id, b.shipping FROM ?:shippings as a LEFT JOIN ?:shipping_descriptions as b ON a.shipping_id = b.shipping_id AND b.lang_code = ?s WHERE ?p ORDER BY a.position", array('shipping_id', 'shipping'), $lang_code, $conditions);

	} else {

		return db_get_array("SELECT a.shipping_id, a.min_weight, a.max_weight, a.position, a.status, b.shipping, b.delivery_time, a.usergroup_ids FROM ?:shippings as a LEFT JOIN ?:shipping_descriptions as b ON a.shipping_id = b.shipping_id AND b.lang_code = ?s WHERE ?p ORDER BY a.position", $lang_code, $conditions);

	}

}



//

// Get all rates for specific shipping

//

function fn_get_shipping_rates($shipping_id)

{

	if (!empty($shipping_id)) {

		return db_get_array("SELECT rate_id, ?:shipping_rates.destination_id, destination FROM ?:shipping_rates LEFT JOIN ?:destination_descriptions ON ?:destination_descriptions.destination_id = ?:shipping_rates.destination_id AND ?:destination_descriptions.lang_code = ?s WHERE shipping_id = ?i", CART_LANGUAGE, $shipping_id);

	} else {

		return false;

	}

}



//

// Get shipping name

//

function fn_get_shipping_name($shipping_id, $lang_code = CART_LANGUAGE)

{

	if (!empty($shipping_id)) {

		return db_get_field("SELECT shipping FROM ?:shipping_descriptions WHERE shipping_id = ?i AND lang_code = ?s", $shipping_id, $lang_code);

	}



	return false;

}



//

// Get all taxes list

//

function fn_get_taxes($lang_code = '')

{

	if (empty($lang_code)) {

		$lang_code = CART_LANGUAGE;

	}



	return db_get_hash_array("SELECT a.*, b.tax FROM ?:taxes as a LEFT JOIN ?:tax_descriptions as b ON b.tax_id = a.tax_id AND b.lang_code = ?s ORDER BY a.priority", 'tax_id', $lang_code);

}



//

// Get tax name

//

function fn_get_tax_name($tax_id = 0, $lang_code = CART_LANGUAGE, $as_array = false)

{

	if (!empty($tax_id)) {

		if (!is_array($tax_id) && strpos($tax_id, ',') !== false) {

			$tax_id = explode(',', $tax_id);

		}

		if (is_array($tax_id) || $as_array == true) {

			return db_get_hash_single_array("SELECT tax_id, tax FROM ?:tax_descriptions WHERE tax_id IN (?n) AND lang_code = ?s", array('tax_id', 'tax'), $tax_id, $lang_code);

		} else {

			return db_get_field("SELECT tax FROM ?:tax_descriptions WHERE tax_id = ?i AND lang_code = ?s", $tax_id, $lang_code);

		}

	}



	return false;

}



//

// Get all rates for specific tax

//

function fn_get_tax_rates($tax_id, $destination_id = 0)

{

	if (empty($tax_id)) {

		return false;

	}

	return db_get_array("SELECT * FROM ?:tax_rates WHERE tax_id = ?i AND destination_id = ?i", $tax_id, $destination_id);

}



//

// Get selected taxes

//

function fn_get_set_taxes($taxes_set)

{

	if (empty($taxes_set)) {

		return false;

	}



	return db_get_hash_array("SELECT tax_id, address_type, priority, price_includes_tax, regnumber FROM ?:taxes WHERE tax_id IN (?n) AND status = 'A' ORDER BY priority", 'tax_id', explode(',', $taxes_set));

}



function fn_add_exclude_products(&$cart, &$auth)

{

	$subtotal = 0;

	$original_subtotal = 0;



	if (isset($cart['products']) && is_array($cart['products'])) {

		foreach($cart['products'] as $cart_id => $product) {

			if (empty($product['product_id'])) {

				continue;

			}



			if (isset($product['extra']['exclude_from_calculate'])) {

				if (empty($cart['order_id'])) {

					unset($cart['products'][$cart_id]);
                                        fn_delete_mashipping_cart_product($cart_id, $cart);

				}

			} else {

				if (!isset($product['product_options'])) {

					$product['product_options'] = array();

				}

				

				$product_subtotal = fn_apply_options_modifiers($product['product_options'], $product['price'], 'P') * $product['amount'];

				$original_subtotal += $product_subtotal;

				$subtotal += $product_subtotal - ((isset($product['discount'])) ? $product['discount'] : 0);

			}

		}

	}



	fn_set_hook('exclude_products_from_calculation', $cart, $auth, $original_subtotal, $subtotal);



}



//

// Calculate cart content

//

// options style:

// F - full

// S - skip selection

// I - info

// calculate_shipping:

// A - calculate all available methods

// E - calculate selected methods only (from cart[shipping])

// S - skip calculation



// Products prices definition

// base_price - price without options modifiers

// original_price - price without discounts (with options modifiers)

// price - price includes discount and taxes

// original_subtotal - original_price * product qty

// subtotal - price * product qty

// discount - discount for this product

// display_price - the displayed price (price does not use in the calculaton)

// display_subtotal - the displayed subtotal (price does not use in the calculaton)



// Cart prices definition

// shipping_cost - total shipping cost

// subtotal - sum (price * amount) of all products

// original_subtotal - sum (original_price * amount) of all products

// tax_subtotal - sum of all the tax values

// display_subtotal - the displayed subtotal (does not use in the calculaton)

// subtotal_discount - the order discount

// discount - sum of all products discounts (except subtotal_discount)

// total - order total



function fn_calculate_cart_content(&$cart, $auth, $calculate_shipping = 'A', $calculate_taxes = true, $options_style = 'F', $apply_cart_promotions = true, $compute=false)

{

	$shipping_rates = array();

	$cart_products = array();

	$cart['subtotal'] = $cart['display_subtotal'] = $cart['original_subtotal'] = $cart['amount'] = $cart['total'] = $cart['discount'] = $cart['tax_subtotal'] = 0;

	$cart['use_discount'] = false;

	$cart['shipping_required'] = false;

	$cart['shipping_failed'] = $cart['company_shipping_failed'] = false;

	$cart['stored_taxes'] = empty($cart['stored_taxes']) ? 'N': $cart['stored_taxes'];

	$cart['display_shipping_cost'] = $cart['shipping_cost'] = 0;

	$cart['coupons'] = empty($cart['coupons']) ? array() : $cart['coupons'];

	$cart['recalculate'] = isset($cart['recalculate']) ? $cart['recalculate'] : false;

	$cart['free_shipping'] = array();

	$cart['options_style'] = $options_style;
	
	$cart['giftable'] = 'Y';
        
        if((!isset($cart['processed_order_id']) || !isset($cart['rewrite_order_id']))){
            $cart['cashback_emi_fee_in_cb'] = 'N';
        }

	fn_add_exclude_products($cart, $auth);



	if (isset($cart['products']) && is_array($cart['products'])) {


		if((!isset($cart['processed_order_id']) || !isset($cart['rewrite_order_id']))){
			$cart['company_discount'] = array();
		}
		$cart_items = 0;
		// Collect product data

		foreach ($cart['products'] as $k => $v) {

			
			/*modified by chandan to skip promotion applied on getting data of cart product*/
			//$_cproduct = fn_get_cart_product_data($k, $cart['products'][$k], false, $cart, $auth);
			$_cproduct = fn_get_cart_product_data($k, $cart['products'][$k], true, $cart, $auth);
			/*modified by chandan to skip promotion applied on getting data of cart product*/
			if (empty($_cproduct)) { // FIXME - for deleted products for OM

				unset($cart['products'][$k]);

				continue;

			}
			/*Modified by chandan to calculate the total company part for an order */
			if($compute){
				if((!isset($cart['processed_order_id']) || !isset($cart['rewrite_order_id']))){
					if(isset($cart['company_discount'][$v['company_id']])){
						$cart['company_discount'][$v['company_id']]['total_company_part']	+= ($v['price']*$v['amount'])+($v['shipping_freight']*$v['amount']);	
					}else{
						$cart['company_discount'][$v['company_id']]['total_company_part']	= ($v['price']*$v['amount'])+($v['shipping_freight']*$v['amount']);
					}
				}
			}
			$cart['order_company_id'] = $v['company_id'];
			/*Modified by chandan to calculate the total company part for an order */
			$cart_products[$k] = $_cproduct;
			
			/*added by chandan to check wheather the cart is giftable or not.*/
			if($v['giftable'] == 'N'){
				$cart['giftable'] = 'N';
				if(!in_array($v['product_id'], $cart['no_giftable_products'])){
					$cart['no_giftable_products'][] = $v['product_id'];
				}
			}
			$cart_items += $v['amount'];
			/*added by chandan to check wheather the cart is giftable or not.*/
		}
		$cart['gifting']['gifting_charge'] = $cart_items * Registry::get('config.gifting_charge');
                //echo '<pre>';print_r($cart);echo '</pre>';

		fn_set_hook('calculate_cart_items', $cart, $cart_products, $auth);
 		
 		
		// Apply cart promotions
		/*Modified by chandan to stop applying promotion on child orders and apply pre calculate discount to an order */
		if(isset($cart['processed_order_id']) || isset($cart['rewrite_order_id'])){
			  $cart['subtotal_discount'] = $cart['company_discount'][$cart['order_company_id']]['total_company_discount_part'];	
			  if($cart['discount_to_adjust'] > 0 ) {
					$cart['subtotal_discount'] +=	$cart['discount_to_adjust'];
					$cart['discount_to_adjust'] = 0; 	  
			  }
		}else{
			if ($apply_cart_promotions == true && $cart['subtotal'] > 0 && empty($cart['order_id'])) {
	
				fn_promotion_apply('cart', $cart, $auth, $cart_products);			
	
			}
		}
		/*Modified by chandan to stop applying promotion on child orders and apply pre calculate discount to an order */

		if (Registry::get('settings.Shippings.disable_shipping') == 'Y') {

			$cart['shipping_required'] = false;

		}
		
		/*Modified by chandan to calculate the discount part for the child orders to be applied to an order */
		if($compute){
			if($cart['subtotal_discount'] != 0){
				$cart_ttl = 0;
				$discount_given = 0;
				foreach($cart['company_discount'] as $company_total)
				{
					$cart_ttl += $company_total['total_company_part'];
				}			
				
				foreach($cart['company_discount'] as $cmpny=>$company_total)
				{
					$cart['company_discount'][$cmpny]['total_company_discount_part'] = fn_format_price(($company_total['total_company_part']/$cart_ttl)*$cart['subtotal_discount']);
					$discount_given += $cart['company_discount'][$cmpny]['total_company_discount_part'];
				}
				$cart['discount_to_adjust'] = $cart['subtotal_discount'] - $discount_given;
			}
			
			
			if($cart['emi_fee'] != '0' || $cart['emi_fee'] != ''){
				$cart_ttl = 0;
				$emi_given = 0;
				foreach($cart['company_discount'] as $company_total)
				{
					$cart_ttl += $company_total['total_company_part'];
				}
				
				foreach($cart['company_discount'] as $cmpny=>$company_total)
				{
					$cart['company_discount'][$cmpny]['total_company_emi_part'] = fn_format_price(($company_total['total_company_part']/$cart_ttl)*$cart['emi_fee']);
					$emi_given += $cart['company_discount'][$cmpny]['total_company_emi_part'];
				}			
				$cart['emi_to_adjust'] = $cart['emi_fee'] - $emi_given;
			}
                        
                        if($cart['emi_fee'] != '0' || $cart['emi_fee'] != ''){
				$cart_ttl = 0;
				$emi_given = 0;
				foreach($cart['company_discount'] as $company_total)
				{
					$cart_ttl += $company_total['total_company_part'];
				}
				
				foreach($cart['company_discount'] as $cmpny=>$company_total)
				{
					$cart['company_discount'][$cmpny]['total_company_emi_part'] = fn_format_price(($company_total['total_company_part']/$cart_ttl)*$cart['emi_fee']);
					$emi_given += $cart['company_discount'][$cmpny]['total_company_emi_part'];
				}			
				$cart['emi_to_adjust'] = $cart['emi_fee'] - $emi_given;
			}
			
			if($cart['cod_fee'] != '0' || $cart['cod_fee'] != ''){
				$cart_ttl = 0;
				$cod_fee_given = 0;
				foreach($cart['company_discount'] as $company_total)
				{
					$cart_ttl += $company_total['total_company_part'];
				}
				
				foreach($cart['company_discount'] as $cmpny=>$company_total)
				{
					$cart['company_discount'][$cmpny]['total_company_cod_part'] = fn_format_price(($company_total['total_company_part']/$cart_ttl)*$cart['cod_fee']);
					$cod_fee_given += $cart['company_discount'][$cmpny]['total_company_cod_part'];
				}			
				$cart['cod_to_adjust'] = $cart['cod_fee'] - $cod_fee_given;
			}
		
		}
		/*Modified by chandan to calculate the discount part for the child orders to be applied to an order */
		
		// Apply shipping fee

		if ($calculate_shipping != 'S' && $cart['shipping_required'] == true) {



			if (defined('CACHED_SHIPPING_RATES') && $cart['recalculate'] == false) {

				$shipping_rates = $_SESSION['shipping_rates'];

			} else {

				$shipping_rates = fn_calculate_shipping_rates($cart, $cart_products, $auth, ($calculate_shipping == 'E'));

			}


			fn_apply_cart_shipping_rates($cart, $cart_products, $auth, $shipping_rates);
			fn_apply_stored_shipping_rates($cart);

		} else {

			if (!empty($cart['shipping'])) {

				$cart['chosen_shipping'] = $cart['shipping'];

			}

			$cart['shipping'] = $shipping_rates = array();

			$cart['shipping_cost'] = 0;

		}


		$cart['display_shipping_cost'] = $cart['shipping_cost'];


		// Calculate taxes

		if ($cart['subtotal'] > 0 && $calculate_taxes == true && $auth['tax_exempt'] != 'Y') {

			fn_calculate_taxes($cart, $cart_products, $shipping_rates, $auth);

		} elseif ($cart['stored_taxes'] != 'Y') {

			$cart['taxes'] = $cart['tax_summary'] = array();

		}


		$cart['subtotal'] = $cart['display_subtotal'] = 0;

      

		fn_update_cart_data($cart, $cart_products);


 

		// Calculate totals

		if(isset($cart['processed_order_id']) || isset($cart['rewrite_order_id'])){
        foreach ($cart_products as $k => $v) {
                if(array_key_exists($k, $cart['dcp'])){                                
                    $_tax = (!empty($cart_products[$k]['tax_summary']) ? ($cart_products[$k]['tax_summary']['added'] / $v['amount']) : 0);
                    $cart_products[$k]['display_price'] = $cart['dcp'][$k]['price'] + (Registry::get('settings.Appearance.cart_prices_w_taxes') == 'Y' ? $_tax : 0);
                    $cart_products[$k]['subtotal'] = $cart['dcp'][$k]['price'] * $v['amount'];
                    $cart_products[$k]['display_subtotal'] = $cart['dcp'][$k]['display_price'] * $v['amount'];
                    $cart['subtotal'] += $cart['dcp'][$k]['subtotal'];
                    $cart['display_subtotal'] += $cart['dcp'][$k]['display_subtotal'];
                    $cart['products'][$k]['display_price'] = $cart['dcp'][$k]['display_price'];
                    $cart['tax_subtotal'] += (!empty($cart_products[$k]['tax_summary']) ? ($cart_products[$k]['tax_summary']['added']) : 0);
                    $cart['total'] += ($cart['dcp'][$k]['price'] - 0) * $v['amount'];
                    //if (!empty($v['discount'])) {
                            $cart['discount'] += $cart['dcp'][$k]['discount'] * $v['amount'];
                    //}
                    //echo '<pre>';print_r($cart);die;
                    
                }else{
                    $_tax = (!empty($cart_products[$k]['tax_summary']) ? ($cart_products[$k]['tax_summary']['added'] / $v['amount']) : 0);
                    $cart_products[$k]['display_price'] = $cart_products[$k]['price'] + (Registry::get('settings.Appearance.cart_prices_w_taxes') == 'Y' ? $_tax : 0);
                    $cart_products[$k]['subtotal'] = $cart_products[$k]['price'] * $v['amount'];
                    $cart_products[$k]['display_subtotal'] = $cart_products[$k]['display_price'] * $v['amount'];
                    $cart['subtotal'] += $cart_products[$k]['subtotal'];
                    $cart['display_subtotal'] += $cart_products[$k]['display_subtotal'];
                    $cart['products'][$k]['display_price'] = $cart_products[$k]['display_price'];
                    $cart['tax_subtotal'] += (!empty($cart_products[$k]['tax_summary']) ? ($cart_products[$k]['tax_summary']['added']) : 0);
                    $cart['total'] += ($cart_products[$k]['price'] - 0) * $v['amount'];
                    if (!empty($v['discount'])) {
                            $cart['discount'] += $v['discount'] * $v['amount'];
                    }
                }
        }
    }else{
        foreach ($cart_products as $k => $v) {
                $_tax = (!empty($cart_products[$k]['tax_summary']) ? ($cart_products[$k]['tax_summary']['added'] / $v['amount']) : 0);
                $cart_products[$k]['display_price'] = $cart_products[$k]['price'] + (Registry::get('settings.Appearance.cart_prices_w_taxes') == 'Y' ? $_tax : 0);
                $cart_products[$k]['subtotal'] = $cart_products[$k]['price'] * $v['amount'];
                $cart_products[$k]['display_subtotal'] = $cart_products[$k]['display_price'] * $v['amount'];
                $cart['subtotal'] += $cart_products[$k]['subtotal'];
                $cart['display_subtotal'] += $cart_products[$k]['display_subtotal'];
                $cart['products'][$k]['display_price'] = $cart_products[$k]['display_price'];
                $cart['tax_subtotal'] += (!empty($cart_products[$k]['tax_summary']) ? ($cart_products[$k]['tax_summary']['added']) : 0);
                $cart['total'] += ($cart_products[$k]['price'] - 0) * $v['amount'];
                if (!empty($v['discount'])) {
                        $cart['discount'] += $v['discount'] * $v['amount'];
                }
        }
    }



		if (Registry::get('settings.General.tax_calculation') == 'subtotal') {

			$cart['tax_subtotal'] += (!empty($cart['tax_summary']['added']) ? ($cart['tax_summary']['added']) : 0);

		}



		$cart['subtotal'] = fn_format_price($cart['subtotal']);

		$cart['display_subtotal'] = fn_format_price($cart['display_subtotal']);



		$cart['total'] += $cart['tax_subtotal'];



		$cart['total'] = fn_format_price($cart['total'] + $cart['shipping_cost']);
		/*added by chandan to add the emi fees in the cart total*/
		if(isset($cart['emi_fee']) && $cart['emi_fee'] != ''){
			if(!isset($cart['processed_order_id']) || !isset($cart['rewrite_order_id'])){
				$cart['total'] = fn_format_price($cart['total'] + $cart['emi_fee']);		
			}else{
				if(!empty($cart['products'])){
					$cart['total'] = fn_format_price($cart['total'] + $cart['company_discount'][$cart['order_company_id']]['total_company_emi_part']);
				}
			}
		}
		
		if(isset($cart['cod_fee']) && $cart['cod_fee'] != ''){
			if(!isset($cart['processed_order_id']) || !isset($cart['rewrite_order_id'])){
				$cart['total'] = fn_format_price($cart['total'] + $cart['cod_fee']);		
			}else{
				if(!empty($cart['products'])){
					$cart['total'] = fn_format_price($cart['total'] + $cart['company_discount'][$cart['order_company_id']]['total_company_cod_part']);
                                        
				}
			}
		}
		
		
		/*added by chandan to add the emi fees in the cart*/
		
		/*added by chandan to add the gifting fees in the cart total*/
		/*if(AREA == 'A'){
			if(isset($cart['gifting']) && $cart['gifting']['gifting_charge'] != '0' && $cart['gifting']['gift_it'] == 'Y'){
				$cart['total'] = fn_format_price($cart['total'] + $cart['gifting']['gifting_charge']);
			}
		}else{
			*/if($cart['giftable'] == 'Y'){
				if(isset($cart['gifting']) && isset($cart['gifting']['gift_it']) && $cart['gifting']['gift_it'] == 'Y'){
					$cart['total'] = fn_format_price($cart['total'] + $cart['gifting']['gifting_charge']);
				}
			}
		//}
		/*added by chandan to add the gifting fees in the cart*/


		if (!empty($cart['subtotal_discount'])) {

			$cart['total'] -= ($cart['subtotal_discount'] < $cart['total']) ? $cart['subtotal_discount'] : $cart['total'];

		}
		/*added by chandan to substract CB amount used in GC orders*/
		if(isset($cart['processed_order_id']) || isset($cart['rewrite_order_id'])){
			$current_company_id = '';
			foreach($cart['products'] as $product){
				if($product['company_id'] == '0'){
					$current_company_id = '0';
				}else{
					break;
				}
			}
			
			if((empty($cart['products']) && $cart['companies']['0'] == '0') || ($current_company_id == '0')){
				if ($cart['cb_for_gc'] > 0) {
					$cart['total'] -= $cart['cb_for_gc'];	
				}		
			}
		}
		/*added by chandan to substract CB amount used in GC orders*/		

	}
            
        


	if (fn_check_suppliers_functionality ()) {

		$cart['companies'] = fn_get_products_companies($cart_products);

		$cart['have_suppliers'] = fn_check_companies_have_suppliers($cart['companies']);

		

		if (defined('ESTIMATION') || $calculate_shipping != 'S' && $cart['shipping_required'] == true) {

			fn_companies_apply_cart_shipping_rates($cart, $cart_products, $auth, $shipping_rates, false);

		}

	}


	fn_set_hook('calculate_cart', $cart, $cart_products, $auth, $calculate_shipping, $calculate_taxes, $apply_cart_promotions);
        if(isset($cart['emi_fee']) && $cart['emi_fee'] != ''){
                if(isset($cart['processed_order_id']) || isset($cart['rewrite_order_id'])){
                        if(!empty($cart['products'])){
                                if($cart['cashback_emi_fee_in_cb'] == 'Y'){
                                    $cart['points_info']['reward'] += $cart['company_discount'][$cart['order_company_id']]['total_company_emi_part'];
                                }
                        }
                }
        }
	/*added by chandan to validate the cod, emi and gifing at order placememnt*/
	if($compute){
		if(!isset($cart['processed_order_id']) || !isset($cart['rewrite_order_id']) || CONTROLLER != 'order_management'){
			$cod_allowed = check_for_cod($cart['products']);
			if(($cod_allowed == 'NO' && $cart['payment_id'] == '6')|| ($cod_allowed == 'NO' && $cart['payment_id'] == '6' && count($cart['gift_certificates']) > 0)){
					if(!in_array('COD_ERROR',$cart['error'])){
							$cart['error'][] = 'COD_ERROR';
					}
			}elseif($cod_allowed == "YES" && count($cart['gift_certificates']) > 0 && $cart['payment_id'] == '6'){
					if(!in_array('COD_ERROR',$cart['error'])){
							$cart['error'][] = 'COD_ERROR';
					}
			}
			
			if($cart['emi_fee'] != '' && ($cart['total'] - $cart['emi_fee']) < Registry::get('config.emi_min_amount')){
				if(!in_array('EMI_ERROR',$cart['error'])){
					$cart['error'][] = 'EMI_ERROR';	
				}
			}
			
			if($cart['giftable'] == 'N' && isset($cart['gifting']['gift_it']) && $cart['gifting']['gift_it'] == 'Y'){
				if(!in_array('GIFT_ERROR',$cart['error'])){
					$cart['error'][] = 'GIFT_ERROR';
				}
			}
		}
	}
	/*added by chandan to validate the cod, emi and gifing at order placememnt*/
	$cart['recalculate'] = false;
	/*modified by chandan to limit the max cod amount for order*/
        
	if($compute){
		if(!isset($cart['processed_order_id']) || !isset($cart['rewrite_order_id']) || CONTROLLER != 'order_management'){
			$max_cod_amount = (Registry::get('config.max_cod_amount')) ? Registry::get('config.max_cod_amount') : '0';
			if($max_cod_amount > 0){
				if($cart['total'] > $max_cod_amount  && $cart['payment_id'] == "6"){
					$cart['payment_id']	='-1';
					$cart['payment_option_id'] = '';
					$cart['cod_fee'] = '';
					unset($cart['payment_details']);
					unset($cart['error']);	
					fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('cod_method_deselected'));
				}
			}
			
			/*modified by chandan to limit the max cod amount for order*/
			if(($cart['total'] - $cart['emi_fee']) < Registry::get('config.emi_min_amount') && $cart['emi_fee'] > '0' ){
				
				$cart['payment_id']	='-1';
				$cart['payment_option_id'] = '';
				$cart['total'] -= $cart['emi_fee'];
				$cart['emi_fee'] = '';
				$cart['emi_id'] = '';
				unset($cart['payment_details']);
				unset($cart['error']);			
				fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('emi_method_deselected'));						
			}       
      if($cart['discount'] != 0){
          $dcp = array();
          foreach($cart_products as $k=>$p){
             if($p['discount'] > 0){
                 $dcp[$k]['price'] = $p['price'];
                 $dcp[$k]['discount'] = $p['discount'];
                 $dcp[$k]['display_price'] = $p['display_price'];
                 $dcp[$k]['subtotal'] = $p['subtotal'];
                 $dcp[$k]['display_subtotal'] = $p['display_subtotal'];
             }
          }
          $cart['dcp'] = $dcp;
      }                 
		}
	}
	/*modified by chandan to limit the max cod amount for order*/
	return array (

		$cart_products,

		$shipping_rates

	);

}



function fn_cart_is_empty($cart)

{

	$result = true;



	if (!empty($cart['products'])) {

		foreach ($cart['products'] as $v) {

			if (!isset($v['extra']['exclude_from_calculate']) && empty($v['extra']['parent'])) {

				$result = false;

				break;

			}

		}

	}



	fn_set_hook('is_cart_empty', $cart, $result);



	return $result;

}



/**

 * Calculate total cost of products in cart

 *

 * @param array $cart cart information

 * @param array $cart_products cart products

 * @param char $type S - cost for shipping, A - all, C - all, exception excluded from calculation

 * @return int products cost

 */

function fn_get_products_cost($cart, $cart_products, $type = 'S')

{

	$cost = 0;



	if (is_array($cart_products)) {

		foreach ($cart_products as $k => $v) {

			if ($type == 'S') {

				if (($v['is_edp'] == 'Y' && $v['edp_shipping'] != 'Y') || $v['free_shipping'] == 'Y' || fn_exclude_from_shipping_calculate($cart['products'][$k])) {

					continue;

				}

			} elseif ($type == 'C') {

				if (isset($v['exclude_from_calculate'])) {

					continue;

				}

			}

			if (isset($v['price'])) {

				$cost += $v['subtotal'];

			}

		}

	}



	return $cost;

}



/**

 * Calculate total weight of products in cart

 *

 * @param array $cart cart information

 * @param array $cart_products cart products

 * @param char $type S - weight for shipping, A - all, C - all, exception excluded from calculation

 * @return int products weight

 */

function fn_get_products_weight($cart, $cart_products, $type = 'S')

{

	$weight = 0;



	if (is_array($cart_products)) {

		foreach ($cart_products as $k => $v) {

			if ($type == 'S') {

				if (($v['is_edp'] == 'Y' && $v['edp_shipping'] != 'Y') || $v['free_shipping'] == 'Y' || fn_exclude_from_shipping_calculate($cart['products'][$k])) {

					continue;

				}

			} elseif ($type == 'C') {

				if (isset($v['exclude_from_calculate'])) {

					continue;

				}

			}



			if (isset($v['weight'])) {

				$weight += ($v['weight'] * $v['amount']);

			}

		}

	}



	return !empty($weight) ? sprintf("%.2f", $weight) : '0.01';

}



/**

 * Calculate total quantity of products in cart

 *

 * @param array $cart cart information

 * @param array $cart_products cart products

 * @param char $type S - quantity for shipping, A - all, C - all, exception excluded from calculation

 * @return int products quantity

 */

function fn_get_products_amount($cart, $cart_products, $type = 'S')

{

	$amount = 0;



	foreach ($cart_products as $k => $v) {

		if ($type == 'S') {

			if (($v['is_edp'] == 'Y' && $v['edp_shipping'] != 'Y') || $v['free_shipping'] == 'Y' || fn_exclude_from_shipping_calculate($cart['products'][$k])) {

				continue;

			}

		} elseif ($type == 'C') {

			if (isset($v['exclude_from_calculate'])) {

				continue;

			}

		}



		$amount += $v['amount'];

	}



	return $amount;

}



/**

 * Divide the products into a separate packages

 *

 * @param array $cart cart information

 * @param array $cart_products cart products

 * @return array products packages

 */

function fn_get_products_packages($cart, $cart_products)

{

	// Implode the same products but with the different options to one package

	$package_groups = array(

		'personal' => array(),

		'global' => array(

			'products' => array(),

			'amount' => 0,

		),

	);

	foreach ($cart_products as $cart_id => $product) {

		if (empty($product['shipping_params']) || (empty($product['shipping_params']['min_items_in_box']) && empty($product['shipping_params']['max_items_in_box']))) {

			if (!(($product['is_edp'] == 'Y' && $product['edp_shipping'] != 'Y') || $product['free_shipping'] == 'Y' || fn_exclude_from_shipping_calculate($cart['products'][$cart_id]))) {

				$package_groups['global']['products'][$cart_id] = $product['amount'];

				$package_groups['global']['amount'] += $product['amount'];

			}

			

		} else {

			if (!isset($package_groups['personal'][$product['product_id']])) {

				$package_groups['personal'][$product['product_id']] = array(

					'shipping_params' => $product['shipping_params'],

					'amount' => 0,

					'products' => array(),

				);

			}

			

			if (!(($product['is_edp'] == 'Y' && $product['edp_shipping'] != 'Y') || $product['free_shipping'] == 'Y' || fn_exclude_from_shipping_calculate($cart['products'][$cart_id]))) {

				$package_groups['personal'][$product['product_id']]['amount'] += $product['amount'];

				$package_groups['personal'][$product['product_id']]['products'][$cart_id] = $product['amount'];

			}

		}

	}

	

	// Divide the products into a separate packages

	$packages = array();

	

	if (!empty($package_groups['personal'])) {

		foreach ($package_groups['personal'] as $product_id => $package_products) {

			while ($package_products['amount'] > 0) {

				if (!empty($package_products['shipping_params']['min_items_in_box']) && $package_products['amount'] < $package_products['shipping_params']['min_items_in_box']) {

					list($_data, $package_size) = fn_get_package_by_amount($package_products['amount'], $package_products['products']);

					foreach ($_data as $cart_id => $amount) {

						$package_groups['global']['products'][$cart_id] = isset($package_groups['global']['products'][$cart_id]) ? $package_groups['global']['products'][$cart_id] : 0;

						$package_groups['global']['products'][$cart_id] += $amount;

						$package_groups['global']['amount'] += $amount;

					}

				} elseif (empty($package_products['shipping_params']['max_items_in_box'])) {

					list($_data, $package_size) = fn_get_package_by_amount($package_products['amount'], $package_products['products']);

					

					$packages[] = array(

						'shipping_params' => $package_products['shipping_params'],

						'products' => $_data,

						'amount' => $package_size,

					);

				} else {

					list($_data, $package_size) = fn_get_package_by_amount($package_products['shipping_params']['max_items_in_box'], $package_products['products']);

					$packages[] = array(

						'shipping_params' => $package_products['shipping_params'],

						'products' => $_data,

						'amount' => $package_size,

					);

				}

				

				// Decrease the current product amount in the global package groups

				foreach ($_data as $cart_id => $amount) {

					$package_products['products'][$cart_id] -= $amount;

				}

				$package_products['amount'] -= $package_size;

			}

		}

	}

	

	if (!empty($package_groups['global']['products'])) {

		$packages[] = $package_groups['global'];

	}

	

	// Calculate the package additional info (weight, cost)

	foreach ($packages as $package_id => &$package) {

		$package['weight'] = 0;

		$package['cost'] = 0;

		

		foreach ($package['products'] as $cart_id => $amount) {

			$package['weight'] += $cart_products[$cart_id]['weight'] * $amount;

			$package['cost'] += $cart_products[$cart_id]['price'] * $amount;

		}

		

		if ($package['weight'] == 0) {

			$package['weight'] = 0.1;

		}

	}

	

	return $packages;

}



function fn_get_package_by_amount($amount, $products)

{

	$return = array();

	$size = 0;

	

	foreach ($products as $cart_id => $product_amount) {

		if ($product_amount < $amount) {

			if ($product_amount == 0) {

				continue;

			}

			$return[$cart_id] = $product_amount;

			$amount -= $product_amount;

			$size += $product_amount;

		} else {

			if ($amount == 0) {

				continue;

			}

			$return[$cart_id] = $amount;

			$size += $amount;

			$amount = 0;

		}

		

		if ($amount <= 0) {

			break;

		}

	}

	

	return array($return, $size);

}



// Get Payment processor data

function fn_get_processor_data($payment_id)

{

	$pdata = db_get_row("SELECT processor_id, params FROM ?:payments WHERE payment_id = ?i", $payment_id);

	if (empty($pdata)) {

		return false;

	}



	$processor_data = db_get_row("SELECT * FROM ?:payment_processors WHERE processor_id = ?i", $pdata['processor_id']);

	$processor_data['params'] = unserialize($pdata['params']);



	$processor_data['currencies'] = (!empty($processor_data['currencies'])) ? explode(',', $processor_data['currencies']) : array();



	return $processor_data;

}



//

// Calculate shipping rate

//

function fn_calculate_shipping_rate($package, $rate_value)

{

	$rate_value = unserialize($rate_value);



	$base_cost = $package['C'];

	$shipping_cost = 0;



	foreach ($package as $type => $amount) {

		if (isset($rate_value[$type]) && is_array($rate_value[$type])) {

			$__rval = array_reverse($rate_value[$type], true);

			foreach ($__rval as $__amnt => $__data) {

				if ($__amnt < $amount) {

					/*if (!empty($__data['per_unit']) && $__data['per_unit'] == 'Y') {

					$__data['value'] = (($__data['type'] == 'F') ? $__data['value'] : ($base_cost * $__data['value'])/100) * $package[$type];

					}*/



					$shipping_cost += (($__data['type'] == 'F') ? $__data['value'] : ($base_cost * $__data['value'])/100) * ((!empty($__data['per_unit']) && $__data['per_unit'] == 'Y') ? $package[$type] : 1);

					break;

				}

			}

		}

	}



	fn_set_hook('calculate_shipping_rate', $shipping_cost, $package, $rate_value);

	

	return fn_format_price($shipping_cost);

}



//

// Calculate shipping rates based on cart data and user info

//

function fn_calculate_shipping_rates(&$cart, &$cart_products, $auth, $calculate_selected = false)

{
	$shipping_rates = array();



	$condition = '';

	if ($calculate_selected == true) {

		$shipping_ids = !empty($cart['shipping']) ? array_keys($cart['shipping']) : array();

		if (!empty($shipping_ids)) {

			$condition = db_quote(" AND a.shipping_id IN (?n)", $shipping_ids);

		} else {

			return array();

		}

	}



	$condition .= fn_get_localizations_condition('a.localization');



	$location = fn_get_customer_location($auth, $cart);

	$destination_id = fn_get_available_destination($location);





	$package_infos = fn_prepare_package_info($cart, $cart_products);



	$company_shippings = db_get_hash_single_array("SELECT company_id, shippings FROM ?:companies WHERE company_id IN (?a)", array('company_id', 'shippings'), array_keys($package_infos));

	foreach ($package_infos as $o_id => $package_info) {

		

		$c = fn_get_company_condition('a.company_id', false, $o_id, false, true);

		

		if (!empty($company_shippings[$o_id])) {

			if (trim($c)) {

				$c = "$c OR ";

			}

			$c .= db_quote('a.shipping_id IN (?n)', explode(',', $company_shippings[$o_id]));

			$c = "($c)";

		}

		if (trim($c)) {

			$c = " AND $c";

		}

		
		//TODO select companies shippings

		fn_set_hook('calculate_shipping_rates', $c, $o_id, $package_info);



		if (AREA == 'C') {

			$condition .= " AND (" . fn_find_array_in_set($auth['usergroup_ids'], 'a.usergroup_ids', true) . ")";

		}


		$shipping_methods = db_get_hash_array("SELECT a.shipping_id, a.rate_calculation, a.service_id, a.params, a.position, b.shipping as name, b.delivery_time FROM ?:shippings as a LEFT JOIN ?:shipping_descriptions as b ON a.shipping_id = b.shipping_id AND b.lang_code = ?s WHERE (a.min_weight <= ?d AND (a.max_weight >= ?d OR a.max_weight = 0.00)) AND a.status = 'A' ?p ?p ORDER BY a.position", 'shipping_id', CART_LANGUAGE, $package_info['W'], $package_info['W'], $condition, $c);


		if (empty($shipping_methods)) {

			continue;

		}



		$found_rates = array();



		if (function_exists('curl_multi_init') && fn_check_curl() && false) {

			$allow_multithreading = true;

			$h_curl_multi = curl_multi_init();

			$threads = array();

		} else {

			$allow_multithreading = false;

		}

		

		$base_package = $package_info;

		$base_general_package = array();

		

		// Find the general product package (if exists)

		if (!empty($package_info['packages'])) {

			foreach ($package_info['packages'] as $package_id => $package) {

				if (empty($package['shipping_params'])) {

					$base_general_package = $package;

					unset($base_package['packages'][$package_id]);

					

					break;

				}

			}

		}

		

		foreach ($shipping_methods as $k => $method) {

			if (!empty($method['params'])) {

				$method['params'] = unserialize($method['params']);

			}

			

			// Prepare package for the current shipping method

			$package_info = $base_package;

			$general_package = $base_general_package;


			if (!empty($method['params']['max_weight_of_box']) && !empty($general_package['products'])) {

				$package = array();

				while (count($general_package['products']) > 0) {

					if (empty($package)) {

						$package = array(

							'products' => array(),

							'amount' => 0,

							'weight' => 0,

							'cost' => 0,

						);

					}

					

					foreach ($general_package['products'] as $cart_id => $amount) {

						// Check, if the product have weight more than package weight. Pack it into a personal package

						if ($cart_products[$cart_id]['weight'] >= $method['params']['max_weight_of_box'] && empty($package['products'])) {

							$package = array(

								'products' => array(

									$cart_id => 1,

								),

								'amount' => 1,

								'weight' => $cart_products[$cart_id]['weight'],

								'cost' => $cart_products[$cart_id]['subtotal'],

							);

							

							$general_package['products'][$cart_id]--;

							

							if ($general_package['products'][$cart_id] == 0) {

								unset($general_package['products'][$cart_id]);

							}

							

							break;

						}

						

						if ($cart_products[$cart_id]['weight'] <= $method['params']['max_weight_of_box'] && (($cart_products[$cart_id]['weight'] + $package['weight']) <= $method['params']['max_weight_of_box'])) {

							while ($general_package['products'][$cart_id] > 0) {

								if (($cart_products[$cart_id]['weight'] + $package['weight']) <= $method['params']['max_weight_of_box']) {

									isset($package['products'][$cart_id]) ? $package['products'][$cart_id]++ : $package['products'][$cart_id] = 1;

									$package['weight'] += $cart_products[$cart_id]['weight'];

									$package['amount']++;

									$package['cost'] += $cart_products[$cart_id]['subtotal'];

								} else {

									break;

								}

								$general_package['products'][$cart_id]--;

							}

							

							if ($general_package['products'][$cart_id] == 0) {

								unset($general_package['products'][$cart_id]);

							}

						}

					}

					

					if ($package['weight'] == 0) {

						$package['weight'] = 0.1;

					}

					

					$package_info['packages'][] = $package;

					$package = array();

				}

				

			} else {

				if (!empty($general_package['products'])) {

					$package_info['packages'][] = $general_package;

				}

			}

			

			if (!empty($package_info['has_free_shipping'])) {

				// Paskage does not need the shipping rate

				$found_rates[$method['shipping_id']] = 0;

				

			} elseif ($method['rate_calculation'] == 'M') {

				// Manual rate calculation

				if ($destination_id !== false) {

					$rate_data = db_get_row("SELECT rate_id, rate_value FROM ?:shipping_rates WHERE shipping_id = ?i AND destination_id = ?i", $method['shipping_id'], $destination_id);



					if (!empty($rate_data)) {

						$found_rates[$method['shipping_id']] = fn_calculate_shipping_rate($package_info, $rate_data['rate_value']);

					}

				}

			

			} else {

				// Realtime rate calculation

				

				$charge = db_get_field("SELECT rate_value FROM ?:shipping_rates WHERE shipping_id = ?i AND destination_id = 0", $method['shipping_id']);

				$rate_data = fn_calculate_realtime_shipping_rate($method['service_id'], $location, $package_info, $auth, $method['shipping_id'], $allow_multithreading);



				if ($rate_data !== false) {

					if ($allow_multithreading && false === array_key_exists('cost', $rate_data)) {

						$threads[$k] = $rate_data;

						$threads[$k][3] = $method['shipping_id'];

						$threads[$k][4] = $charge;

						curl_multi_add_handle($h_curl_multi, $threads[$k][0]);

					} else {

						$found_rates[$method['shipping_id']] = $rate_data['cost'];

						$found_rates[$method['shipping_id']] += fn_calculate_shipping_rate($package_info, $charge);

					}

				}

			}

			

			// Save prepeared packages for the current shipping method

			$shipping_packages[$method['shipping_id']] = $package_info;

		}



		if (false === empty($threads)) {



			// Launch the jobs pool

			// FIXME: we must use fn_http(s)_request instead of this code!

			

			do {

				$status = curl_multi_exec($h_curl_multi, $active);

				$info = curl_multi_info_read($h_curl_multi);

			} while ($status === CURLM_CALL_MULTI_PERFORM || $active);



			foreach ($threads as $k => $thread) {

				$res[$k] = curl_multi_getcontent($threads[$k][0]);

				$request_info = curl_getinfo($threads[$k][0]);

				

				curl_close($threads[$k][0]);



				if (!isset($threads[$k][2])) {

					$threads[$k][2] = array();

				} elseif (!is_array($threads[$k][2])) {

					$threads[$k][2] = array($threads[$k][2]);

				}

				array_unshift($threads[$k][2], '200 OK', $res[$k]);



				$rate_data = call_user_func_array($threads[$k][1], $threads[$k][2]);



				if ($rate_data !== false) {

					$found_rates[$threads[$k][3]] = $rate_data['cost'];

					$found_rates[$threads[$k][3]] += fn_calculate_shipping_rate($package_info, $threads[$k][4]);

				}

				

				// Prepare log info. FIXME: use fn_http(s)_request instead of this code!

				(strpos($request_info['url'], '?') != false) ? list($url, $data) = explode('?', $request_info['url']) : $url = $request_info['url'];

				$_data = array();

				if (!empty($data)) {

					$data = explode('&', $data);

					foreach ($data as $part) {

						list($key, $value) = explode('=', $part);

						$_data[$key] = urldecode($value);

					}

				}



				fn_log_event('requests', 'http', array(

					'url' => $url,

					'data' => var_export($_data, true),

					'response' => $res[$k],

				));

			}



			curl_multi_close($h_curl_multi);

		}



		$shipping_freight = 0;
                
		foreach ($cart_products as $v) {

			if (($v['is_edp'] != 'Y' || ($v['is_edp'] == 'Y' && $v['edp_shipping'] == 'Y')) && $v['free_shipping'] != 'Y') {
			//echo "<pre>".print_r($v);

				//Changes By Megha Sudan
				//to calculate lot shipping for wholesale products
				if($v['is_wholesale_product'] == 1)
				{
					if($v['min_qty'] < $v['amount'])
					{
						$shipping_bunch = (($v['amount']%$v['min_qty']) != 0 ? floor($v['amount']/$v['min_qty']) + 1 : floor($v['amount']/$v['min_qty'])); 
						$shipping_freight += ($v['shipping_freight'] * $shipping_bunch);
					}
					else
					{
						$shipping_freight += $v['shipping_freight'];	
						//echo "<br>sc = ".$shipping_freight;
					}
				}
                                elseif(fn_check_if_shipping_price_set_for_product($v['product_id']) &&  $v['amount'] > 1)
                                {    
                                   
                                    $product_shipping_charge = fn_caclulate_shipping_for_more_quantity($v['product_id'],$v['amount']);
                                    
                                    $product_shipping_charge = (empty($product_shipping_charge))? 0 : floatval($product_shipping_charge);
                                    
                                    $shipping_freight += ($product_shipping_charge*$v['amount']);
                                    
                                }
				else
				{
					$shipping_freight += ($v['shipping_freight'] * $v['amount']);	
				}

			}

		}




		foreach ($found_rates as $shipping_id => $rate_value) {

			if (!isset($shipping_rates[$shipping_id])) {

				$shipping_rates[$shipping_id]['name'] = $shipping_methods[$shipping_id]['name'];

				$shipping_rates[$shipping_id]['delivery_time'] = $shipping_methods[$shipping_id]['delivery_time'];

				$shipping_rates[$shipping_id]['position'] = $shipping_methods[$shipping_id]['position'];

			}

			$shipping_rates[$shipping_id]['rates'][$o_id] = $rate_value + $shipping_freight;
			if (!empty($shipping_packages[$shipping_id])) {
				$shipping_rates[$shipping_id]['packages_info'] = $shipping_packages[$shipping_id];

			}

		}

	}



	$shipping_rates = fn_sort_array_by_key($shipping_rates, 'position', SORT_ASC);

	

	// Unset the unnecessary packages info from the manual calculated shipping methods

	if (!empty($shipping_rates)) {

		foreach ($shipping_rates as $shipping_id => $rate) {

			if (isset($shipping_methods[$shipping_id]) && $shipping_methods[$shipping_id]['rate_calculation'] == 'M') {

				unset($shipping_rates[$shipping_id]['packages_info']['packages']);

			}

		}

	}


	return $shipping_rates;

}



//

// Returns customer location or default location

//

function fn_get_customer_location($auth, $cart, $billing = false)

{



	$s_info = array();

	$prefix = 's';

	if ($billing == true) {

		$prefix = 'b';

	}



	$fields = array (

		'country',

		'state',

		'city',

		'zipcode',

		'address',

		'address_2',

	);



	$u_info = (!empty($cart['user_data'])) ? $cart['user_data'] : ((empty($cart['user_data']) && !empty($auth['user_id'])) ? fn_get_user_info($auth['user_id'], true, $cart['profile_id']) : array());



	// Fill basic fields

	foreach ($fields as $field) {

		$s_info[$field] = !empty($u_info[$prefix . '_' . $field]) ? $u_info[$prefix . '_' . $field] : Registry::get("settings.General.default_$field");

	}



	// Add phone

	$s_info['phone'] = !empty($u_info['phone']) ? $u_info['phone'] : Registry::get('settings.General.default_phone');



	// Add residential address flag

	$s_info['address_type'] = (!empty($u_info['s_address_type'])) ? $u_info['s_address_type'] : 'residential';



	// Get First and Last names

	$u_info['firstname'] = !empty($u_info['firstname']) ? $u_info['firstname'] : 'John';

	$u_info['lastname'] = !empty($u_info['lastname']) ? $u_info['lastname'] : 'Doe';



	if ($prefix == 'b') {

		$s_info['firstname'] = (!empty($u_info['b_firstname'])) ? $u_info['b_firstname'] : $u_info['firstname'];

		$s_info['lastname'] = (!empty($u_info['b_lastname'])) ? $u_info['b_lastname'] : $u_info['lastname'];

	} else {

		$s_info['firstname'] = (!empty($u_info['s_firstname'])) ? $u_info['s_firstname'] : (!empty($u_info['b_firstname']) ? $u_info['b_firstname'] : $u_info['firstname']);

		$s_info['lastname'] = (!empty($u_info['s_lastname'])) ? $u_info['s_lastname'] : (!empty($u_info['b_lastname']) ? $u_info['b_lastname'] : $u_info['lastname']);

	}



	// Get country/state descriptions

	$avail_country = db_get_field("SELECT COUNT(*) FROM ?:countries WHERE code = ?s AND status = 'A'", $s_info['country']);

	if (empty($avail_country)) {

		return array();

	}



	$avail_state = db_get_field("SELECT COUNT(*) FROM ?:states WHERE country_code = ?s AND code = ?s AND status = 'A'", $s_info['country'], $s_info['state']);

	if (empty($avail_state)) {

		$s_info['state'] = '';

	}

	

	return $s_info;

}



//

// Calculate taxes for the products

//

function fn_calculate_taxes(&$cart, &$cart_products, &$shipping_rates, $auth)

{

	$calculated_data = array();

	

	if (Registry::get('settings.General.tax_calculation') == 'unit_price') {

		// Tax calculation method based on UNIT PRICE

		

		// Calculate product taxes

		foreach ($cart_products as $k => $product) {

			$taxes = fn_get_product_taxes($k, $cart, $cart_products);

			

			if (empty($taxes)) {

				continue;

			}



			if (isset($product['subtotal'])) {

				if ($product['price'] == $product['subtotal'] && $product['amount'] != 1) {

					$price = fn_format_price($product['price']);

				} else {

					$price = fn_format_price($product['subtotal'] / $product['amount']);

				}

				

				$calculated_data['P_' . $k] = fn_calculate_tax_rates($taxes, $price, $product['amount'], $auth, $cart);



				$cart_products[$k]['tax_summary'] = array('included' => 0, 'added' => 0, 'total' => 0); // tax summary for 1 unit of product



				// Apply taxes to product subtotal

				if (!empty($calculated_data['P_' . $k])) {

					foreach ($calculated_data['P_' . $k] as $_k => $v) {

						$cart_products[$k]['taxes'][$_k] = $v;

						if ($taxes[$_k]['price_includes_tax'] != 'Y') {

							$cart_products[$k]['tax_summary']['added'] += $v['tax_subtotal'];

						} else {

							$cart_products[$k]['tax_summary']['included'] += $v['tax_subtotal'];

						}

					}

					$cart_products[$k]['tax_summary']['total'] = $cart_products[$k]['tax_summary']['added'] + $cart_products[$k]['tax_summary']['included'];

				}

			}

		}

		

		// Calculate shipping taxes

		if (!empty($shipping_rates)) {

			foreach ($shipping_rates as $shipping_id => $shipping) {

				$taxes = fn_get_shipping_taxes($shipping_id, $shipping_rates, $cart);

				

				if (!empty($taxes)) {



					$shipping_rates[$shipping_id]['taxes'] = array();

					

					$calculate_rate = true;

					

					if (!empty($cart['shipping'][$shipping_id])) {

						foreach ($cart['shipping'][$shipping_id]['rates'] as $k => $v) {

							$calculated_data['S_' . $shipping_id . '_' . $k] = fn_calculate_tax_rates($taxes, $v, 1, $auth, $cart);

							

							if (!empty($calculated_data['S_' . $shipping_id . '_' . $k])) {

								foreach ($calculated_data['S_' . $shipping_id . '_' . $k] as $__k => $__v) {

									if ($taxes[$__k]['price_includes_tax'] != 'Y') {

										$cart['display_shipping_cost'] += Registry::get('settings.Appearance.cart_prices_w_taxes') == 'Y' ? $__v['tax_subtotal'] : 0;

										$cart['tax_subtotal'] += $__v['tax_subtotal'];

									}

									



									if ($cart['stored_taxes'] == 'Y') {

										$cart['taxes'][$__k]['applies']['S_' . $shipping_id . '_' . $k] = $__v['tax_subtotal'];

									}

								}

								

								$shipping_rates[$shipping_id]['taxes']['S_' . $shipping_id . '_' . $k] = $calculated_data['S_' . $shipping_id . '_' . $k];

								$calculate_rate = false;

							}

						}

					}



					foreach ($shipping_rates as $shipping_id => $shipping) {

						// Calculate taxes for each shipping rate

						$taxes = fn_get_shipping_taxes($shipping_id, $shipping_rates, $cart);



						$shipping_rates[$shipping_id]['taxed_price'] = 0; 

						unset($shipping_rates[$shipping_id]['inc_tax']);

						

						if (!empty($taxes)) {

							$shipping_rates[$shipping_id]['taxes'] = array();

							

							foreach ($shipping['rates'] as $k => $v) {

								$tax = fn_calculate_tax_rates($taxes, fn_format_price($v), 1, $auth, $cart);

								

								$shipping_rates[$shipping_id]['taxes']['S_' . $shipping_id . '_' . $k] = $tax;

								

								if (!empty($tax) && Registry::get('settings.Appearance.cart_prices_w_taxes') == 'Y') {

									foreach ($tax as $_id => $_tax) {

										if ($_tax['price_includes_tax'] != 'Y') {

											$shipping_rates[$shipping_id]['taxed_price'] += $_tax['tax_subtotal'];

										}

									}

									$shipping_rates[$shipping_id]['inc_tax'] = true;

								}

							}

							

							if (!empty($shipping_rates[$shipping_id]['rates']) && $shipping_rates[$shipping_id]['taxed_price'] > 0) {

								$shipping_rates[$shipping_id]['taxed_price'] += array_sum($shipping_rates[$shipping_id]['rates']);

							}

						}

					}



					if ($calculate_rate) {

						foreach ($shipping['rates'] as $k => $v) {

							if (isset($shipping['rates'][$k])) {

								$cur_shipping_rates = fn_calculate_tax_rates($taxes, $v, 1, $auth, $cart);

								if (!empty($cur_shipping_rates)) {

									$shipping_rates[$shipping_id]['taxes']['S_' . $shipping_id . '_' . $k] = $cur_shipping_rates;

								}

							}

						}

					}

				}

			}

		}

		

	} else {

		// Tax calculation method based on SUBTOTAL

		

		// Calculate discounted subtotal

		if (!isset($cart['subtotal_discount'])) {

			$cart['subtotal_discount'] = 0;

		}

		$discounted_subtotal = $cart['original_subtotal'] - $cart['subtotal_discount'];

		

		// Get discount distribution coefficient (DDC) between taxes

		$ddc = $discounted_subtotal / $cart['original_subtotal'];

		

		//

		// Group subtotal by taxes

		//

		$subtotal = array();

		

		// Get products taxes

		foreach ($cart_products as $k => $product) {

			$taxes = fn_get_product_taxes($k, $cart, $cart_products);

			

			if (!empty($taxes)) {

				foreach ($taxes as $tax_id => $tax) {

					if (empty($subtotal[$tax_id])) {

						$subtotal[$tax_id] = $tax;

						$subtotal[$tax_id]['subtotal'] = $subtotal[$tax_id]['applies']['P'] = $subtotal[$tax_id]['applies']['S'] = 0;

						$subtotal[$tax_id]['applies']['items']['P'] = $subtotal[$tax_id]['applies']['items']['S'] = array();

					}

					

					$_subtotal = ($product['price'] == $product['subtotal'] && $product['amount'] != 1) ? fn_format_price($product['price'] * $product['amount']) : $product['subtotal'];

					

					$subtotal[$tax_id]['subtotal'] += $_subtotal;

					$subtotal[$tax_id]['applies']['P'] += $_subtotal;

					$subtotal[$tax_id]['applies']['items']['P'][$k] = true;

				}

			}

		}

		

		// Get shipping taxes

		if (!empty($shipping_rates)) {

			foreach ($shipping_rates as $shipping_id => $shipping) {

				// Calculate taxes for each shipping rate

				$taxes = fn_get_shipping_taxes($shipping_id, $shipping_rates, $cart);



				$shipping_rates[$shipping_id]['taxed_price'] = 0; 

				unset($shipping_rates[$shipping_id]['inc_tax']);

				

				if (!empty($taxes)) {

					$shipping_rates[$shipping_id]['taxes'] = array();

					

					foreach ($shipping['rates'] as $k => $v) {

						$tax = fn_calculate_tax_rates($taxes, fn_format_price($v), 1, $auth, $cart);

						$shipping_rates[$shipping_id]['taxes']['S_' . $shipping_id . '_' . $k] = $tax;

						

						if (!empty($tax) && Registry::get('settings.Appearance.cart_prices_w_taxes') == 'Y') {

							foreach ($tax as $_id => $_tax) {

								if ($_tax['price_includes_tax'] != 'Y') {

									$shipping_rates[$shipping_id]['taxed_price'] += $_tax['tax_subtotal'];

								}

							}

							$shipping_rates[$shipping_id]['inc_tax'] = true;

						}

					}

					

					if (!empty($shipping_rates[$shipping_id]['rates']) && $shipping_rates[$shipping_id]['taxed_price'] > 0) {

						$shipping_rates[$shipping_id]['taxed_price'] += array_sum($shipping_rates[$shipping_id]['rates']);

					}

				}

				

				if (!isset($cart['shipping'][$shipping_id])) {

					continue;

				}



				if (!empty($taxes)) {

					foreach ($taxes as $tax_id => $tax) {

						if (empty($subtotal[$tax_id])) {

							$subtotal[$tax_id] = $tax;

							$subtotal[$tax_id]['subtotal'] = $subtotal[$tax_id]['applies']['P'] = $subtotal[$tax_id]['applies']['S'] = 0;

							$subtotal[$tax_id]['applies']['items']['P'] = $subtotal[$tax_id]['applies']['items']['S'] = array();

						}

						

						$subtotal[$tax_id]['subtotal'] += array_sum($cart['shipping'][$shipping_id]['rates']);

						$subtotal[$tax_id]['applies']['S'] += array_sum($cart['shipping'][$shipping_id]['rates']);

						$subtotal[$tax_id]['applies']['items']['S'][$shipping_id] = true;

					}

				}

			}

		}

		

		// Apply DDC and calculate tax rates

		$calculated_taxes = array();

		

		foreach ($subtotal as $tax_id => $_st) {

			if (empty($_st['tax_id'])) {

				$_st['tax_id'] = $tax_id;

			}

			$tax = fn_calculate_tax_rates(array($_st), fn_format_price($_st['applies']['P'] * $ddc + $_st['applies']['S']), 1, $auth, $cart);

			

			if (empty($tax)) {

				continue;

			}

			

			$calculated_data[$tax_id] = reset($tax);

			$products_tax = fn_calculate_tax_rates(array($_st), fn_format_price($_st['applies']['P'] * $ddc), 1, $auth, $cart);

			

			$calculated_data[$tax_id]['applies']['P'] = $products_tax[$tax_id]['tax_subtotal'];

			

			$shipping_tax = fn_calculate_tax_rates(array($_st), fn_format_price($_st['applies']['S']), 1, $auth, $cart);

			$calculated_data[$tax_id]['applies']['S'] = $shipping_tax[$tax_id]['tax_subtotal'];

			

			$calculated_data[$tax_id]['tax_subtotal'] = $products_tax[$tax_id]['tax_subtotal'] + $shipping_tax[$tax_id]['tax_subtotal'];

			$calculated_data[$tax_id]['applies']['items'] = $_st['applies']['items'];

		}

	}

	

	fn_apply_calculated_taxes($calculated_data, $cart);



	return false;

}



function fn_get_product_taxes($idx, $cart, $cart_products)

{

	if ($cart['stored_taxes'] == 'Y') {

		$_idx = '';

		if (isset($cart['products'][$idx]['original_product_data']['cart_id'])) {

			$_idx = $cart['products'][$idx]['original_product_data']['cart_id'];

		}

		

		$taxes = array();

		foreach ((array)$cart['taxes'] as $_k => $_v) {

			$tax = array();

			if (!empty($_v['applies']['P_'.$idx]) || isset($_v['applies']['items']['P'][$idx]) || !empty($_v['applies']['P_'.$_idx]) || isset($_v['applies']['items']['P'][$_idx])) {

				$taxes[$_k] = $_v;

			}

		}

	}

	if ($cart['stored_taxes'] != 'Y' || empty($taxes)) {

		$taxes = fn_get_set_taxes($cart_products[$idx]['tax_ids']);

	}

	

	return $taxes;

}



function fn_get_shipping_taxes($shipping_id, $shipping_rates, $cart)

{

	static $_taxes;

	

	$tax_ids = array();

	if (defined('ORDER_MANAGEMENT')) {

		if (empty($_taxes)) {

			$_taxes = db_get_hash_single_array("SELECT tax_ids, shipping_id FROM ?:shippings WHERE shipping_id IN (?n)", array('shipping_id', 'tax_ids'), array_keys($shipping_rates));

		}

		

		if (!empty($_taxes)) {

			foreach ($_taxes as $_ship => $_tax) {

				if (!empty($_tax)) {

					$_tids = explode(',', $_tax);

					foreach ($_tids as $_tid) {

						$tax_ids[$_ship] = $_tid;

					}

				}

			}

		}

	}

	

	if ($cart['stored_taxes'] == 'Y') {

		$taxes = array();



		foreach ((array)$cart['taxes'] as $_k => $_v) {

			isset($_v['applies']['items']['S'][$shipping_id]) ? $exists = true : $exists = false;

			foreach ($_v['applies'] as $aid => $av) {

				if (strpos($aid, 'S_' . $shipping_id . '_') !== false) {

					$exists = true;



				}

			}

			if ($exists == true || (!empty($tax_ids[$shipping_id]) && $tax_ids[$shipping_id] == $_k)) {

				$taxes[$_k] = $_v;

				$taxes[$_k]['applies'] = array();

			}

		}

	} else {

		$taxes = array();

		$tax_ids = db_get_field("SELECT tax_ids FROM ?:shippings WHERE shipping_id = ?i", $shipping_id);

		if (!empty($tax_ids)) {

			$taxes = db_get_hash_array("SELECT tax_id, address_type, priority, price_includes_tax, regnumber FROM ?:taxes WHERE tax_id IN (?n) AND status = 'A' ORDER BY priority", 'tax_id', explode(',', $tax_ids));

		}

	}

	

	return $taxes;

}



function fn_apply_calculated_taxes($calculated_data, &$cart)

{

	if (!empty($calculated_data)) {

		if (Registry::get('settings.General.tax_calculation') == 'unit_price') {

			// Based on the unit price

			$taxes_data = array();

			foreach ($calculated_data as $id => $_taxes) {

				if (empty($_taxes)) {

					continue;

				}

				foreach ($_taxes as $k => $v) {

					if (empty($taxes_data[$k])) {

						$taxes_data[$k] = $v;

						$taxes_data[$k]['tax_subtotal'] = 0;

					}

					$taxes_data[$k]['applies'][$id] = $v['tax_subtotal'];

					$taxes_data[$k]['tax_subtotal'] += $v['tax_subtotal'];

				}

			}

			

			$cart['taxes'] = $taxes_data;

			

		} else {

			// Based on the order subtotal

			$cart['taxes'] = array();

			$cart['tax_subtotal'] = 0;

			$cart['tax_summary'] = array(

				'included' => 0,

				'added' => 0,

				'total' => 0

			);

			

			foreach ($calculated_data as $tax_id => $v) {

				$cart['taxes'][$tax_id] = $v;

				

				if ($v['price_includes_tax'] == 'Y') {

					$cart['tax_summary']['included'] += $v['tax_subtotal'];

				} else {

					$cart['tax_summary']['added'] += $v['tax_subtotal'];

				}

				

				$cart['tax_summary']['total'] += $v['tax_subtotal'];

			}

		}

		

	} else { // FIXME!!! Test on order management

		$cart['taxes'] = array();

		$cart['tax_summary'] = array();

	}

	

	return true;

}



function fn_format_rate_value($rate_value, $rate_type, $decimals='2', $dec_point='.', $thousands_sep=',', $coefficient = '')

{

	if (!empty($coefficient) && @$rate_type != 'P') {

		$rate_value = $rate_value / floatval($coefficient);

	}



	if (empty($rate_type)) {

		$rate_type = 'F';

	}



	$value = number_format(fn_format_price($rate_value, '', $decimals), $decimals, $dec_point, $thousands_sep);

	if ($rate_type == 'F') { // Flat rate

		return $value;

	}

	elseif ($rate_type == 'P') { // Percent rate

		return $value.'%';

	}



	return $rate_value;



}



function fn_check_amount_in_stock($product_id, $amount, $product_options, $cart_id, $is_edp, $original_amount, &$cart, $update_id = 0)

{

	fn_set_hook('check_amount_in_stock', $product_id, $amount, $product_options, $cart_id, $is_edp, $original_amount, $cart);



	// If the product is EDP don't track the inventory

	if ($is_edp == 'Y') {

		return 1;

	}



	$product = db_get_row("SELECT ?:products.tracking, ?:products.amount, ?:products.min_qty, ?:products.max_qty, ?:products.qty_step, ?:products.list_qty_count, ?:product_descriptions.product FROM ?:products LEFT JOIN ?:product_descriptions ON ?:product_descriptions.product_id = ?:products.product_id AND lang_code = ?s WHERE ?:products.product_id = ?i", CART_LANGUAGE, $product_id);



	if (Registry::get('settings.General.inventory_tracking') == 'Y' && $product['tracking'] != 'D') {

		// Track amount for ordinary product

		if ($product['tracking'] == 'B') {

			$current_amount = $product['amount'];



		// Track amount for product with options

		} elseif ($product['tracking'] == 'O') {

			$selectable_cart_id = fn_generate_cart_id($product_id, array('product_options' => $product_options), true);

			$current_amount = db_get_field("SELECT amount FROM ?:product_options_inventory WHERE combination_hash = ?i", $selectable_cart_id);

			$current_amount = intval($current_amount);

		}



		if (!empty($cart['products']) && is_array($cart['products'])) {

			$product_not_in_cart = true;

			foreach ($cart['products'] as $k => $v) {

				if ($k != $cart_id){ // Check if the product with the same selectable options already exists ( for tracking = O)

					if (($product['tracking'] == 'B' && $v['product_id'] == $product_id) || ($product['tracking'] == 'O' && @$v['selectable_cart_id'] == $selectable_cart_id)) {

						$current_amount -= $v['amount'];

					}

				} else {

					$product_not_in_cart = false;

				}

			}



			if ($product['tracking'] == 'B' && !empty($update_id) && $product_not_in_cart && !empty($cart['products'][$update_id])) {

				$current_amount += $cart['products'][$update_id]['amount'];

			}



			if ($product['tracking'] == 'O') {

				// Store cart_id for selectable options in cart variable, so if the same product is added to

				// the cart with the same selectable options, but different text options,

				// the total amount will be tracked anyway as it is the one product

				if (!empty($selectable_cart_id) && isset($cart['products'][$cart_id])) {

					$cart['products'][$cart_id]['selectable_cart_id'] = $selectable_cart_id;

				}

			}

		}

	}



	$min_qty = 1;



	if (!empty($product['min_qty']) && $product['min_qty'] > $min_qty) {

		$min_qty = $product['min_qty'];

	}



	if (!empty($product['qty_step']) && $product['qty_step'] > $min_qty) {

		$min_qty = $product['qty_step'];

	}



	if (empty($product['list_qty_count']) && !empty($product['qty_step'])) {

		$product['list_qty_count'] = intval((isset($current_amount) ? $current_amount : $product['amount']) / $product['qty_step']);

	}



	if (!empty($product['qty_step']) && !empty($product['list_qty_count'])) {

		$per_item = 0;

		$amount_corrected = false;

		if (Registry::get('settings.General.allow_negative_amount') == 'Y' && !empty($product['max_qty'])) {

			$_amount = $product['max_qty'];

		} else {

			$_amount = isset($current_amount) ? $current_amount : $product['amount'];

		}

		$_amount = ($product['tracking'] == 'D') ? (!empty($product['max_qty']) ? $product['max_qty'] : $product['qty_step'] * $product['list_qty_count']) : (($amount < $product['qty_step']) ? $product['qty_step'] : $amount);

		for ($i = 1; $per_item <= ($_amount - $product['qty_step']); $i++) {

			$per_item = $product['qty_step'] * $i;



			if ($i > $product['list_qty_count'] && $amount <= $per_item - $product['qty_step']) {

				break;

			}



			if ((!empty($product['max_qty']) && $per_item > $product['max_qty']) || (!empty($product['min_qty']) && $per_item < $product['min_qty'])) {

				continue;

			}



			if ($amount == $per_item) {

				break;

			}



			if ($amount != $per_item && $amount < $per_item) {

				$amount = $per_item;

				$amount_corrected = true;

				break;

			}

		}

		if ($amount > $per_item) {

			$amount = $per_item;

			$amount_corrected = true;

		}

		if ($amount_corrected) {

			fn_set_notification('W', fn_get_lang_var('important'), str_replace('[product]', $product['product'], fn_get_lang_var('text_cart_amount_changed')));

		}

	}

	

	if (isset($current_amount) && $current_amount >= 0 && $current_amount - $amount < 0 && Registry::get('settings.General.allow_negative_amount') != 'Y') {

		// For order edit: add original amount to existent amount

		$current_amount += $original_amount;


		if ($current_amount > 0 && $current_amount - $amount < 0 && Registry::get('settings.General.allow_negative_amount') != 'Y') {

			if (!defined('ORDER_MANAGEMENT')) {

				fn_set_notification('W', fn_get_lang_var('important'), str_replace('[product]', $product['product'], fn_get_lang_var('text_cart_amount_corrected')));

				$amount = $current_amount;

			} else {

				fn_set_notification('W', fn_get_lang_var('warning'), fn_get_lang_var('text_cart_not_enough_inventory'));

			}

		} elseif ($current_amount - $amount < 0 && Registry::get('settings.General.allow_negative_amount') != 'Y') {
                                                        $_SESSION['zero_inventory_product'] = $product['product'];
			return false;

		} elseif ($current_amount <= 0 && $amount <= 0 && Registry::get('settings.General.allow_negative_amount') != 'Y') {

			fn_set_notification('E', fn_get_lang_var('notice'), str_replace('[product]', $product['product'], fn_get_lang_var('text_cart_zero_inventory_and_removed')));

			return false;

		}

	} elseif ($amount < $min_qty || (isset($current_amount) && $amount > $current_amount && Registry::get('settings.General.allow_negative_amount') != 'Y' && Registry::get('settings.General.inventory_tracking') == 'Y') && isset($product_not_in_cart) && !$product_not_in_cart) {
		if (($current_amount < $min_qty || $current_amount == 0) && Registry::get('settings.General.allow_negative_amount') != 'Y' && Registry::get('settings.General.inventory_tracking') == 'Y') {

			fn_set_notification('W', fn_get_lang_var('warning'), fn_get_lang_var('text_cart_not_enough_inventory'));

			if (!defined('ORDER_MANAGEMENT')) {

				$amount = false;

			}

		} elseif ($amount > $current_amount && Registry::get('settings.General.allow_negative_amount') != 'Y' && Registry::get('settings.General.inventory_tracking') == 'Y') {

			fn_set_notification('W', fn_get_lang_var('warning'), fn_get_lang_var('text_cart_not_enough_inventory'));

			if (!defined('ORDER_MANAGEMENT')) {

				$amount = $current_amount;

			}

		} elseif ($amount < $min_qty) {

			fn_set_notification('W', fn_get_lang_var('notice'), str_replace(array('[product]' , '[quantity]'), array($product['product'] , $min_qty), fn_get_lang_var('text_cart_min_qty')));

			if (!defined('ORDER_MANAGEMENT')) {

				$amount = $min_qty;

			}

		}

	} 
	
	//change by ankur to fix the max qty issue 
	if (!empty($product['max_qty']) && $amount > $product['max_qty']) {
		fn_set_notification('W', fn_get_lang_var('notice'), str_replace(array('[product]' , '[quantity]'), array($product['product'], $product['max_qty']), fn_get_lang_var('text_cart_max_qty')));

		fn_set_notification('W', fn_get_lang_var('notice'), str_replace(array('[product]' , '[quantity]'), array($product['product'], $product['max_qty']), fn_get_lang_var('text_cart_max_qty')));

		if (!defined('ORDER_MANAGEMENT')) {

			$amount = $product['max_qty'];

		}

	}


	return empty($amount) ? false : $amount;

}



//

// Calculate unique product id in the cart

//

function fn_generate_cart_id($product_id, $extra, $only_selectable = false)

{

	$_cid = array();



	if (!empty($extra['product_options']) && is_array($extra['product_options'])) {

		foreach ($extra['product_options'] as $k => $v) {

			if ($only_selectable == true && ((string)intval($v) != $v || db_get_field("SELECT inventory FROM ?:product_options WHERE option_id = ?i", $k) != 'Y')) {

				continue;

			}

			$_cid[] = $v;

		}

	}



	if (isset($extra['exclude_from_calculate'])) {

		$_cid[] = $extra['exclude_from_calculate'];

	}



	fn_set_hook('generate_cart_id', $_cid, $extra, $only_selectable);



	natsort($_cid);

	array_unshift($_cid, $product_id);

	$cart_id = fn_crc32(implode('_', $_cid));



	return $cart_id;

}





//

// Normalize product amount

//

function fn_normalize_amount($amount = '1')

{

	$amount = abs(intval($amount));



	return empty($amount) ? 0 : $amount;

}



function fn_order_placement_routines($order_id, $force_notification = array(), $clear_cart = true, $action = '',$cod_api = '')

{

	$order_info = fn_get_order_info($order_id, true);


	$display_notification = true;

	/*api = 1 only if order is placed from api in COD mode*/
	if($cod_api == 0){            
		$order_type = db_get_array("SELECT order_id from cscart_order_data WHERE type = 'Z' and order_id=".$order_id );
		if($order_type[0]['order_id'] == $order_id){
			$online_payment_api = 1;        // if order placed through api in online payment mode
		}
	}


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



	$_error = false;



	if ($action == 'save') {

		if ($display_notification) {

			fn_set_notification('N', fn_get_lang_var('congratulations'), fn_get_lang_var('text_order_saved_successfully'));

		}

	} else {

		if ($order_info['status'] == STATUS_PARENT_ORDER) {

			$child_orders = db_get_hash_single_array("SELECT order_id, status FROM ?:orders WHERE parent_order_id = ?i", array('order_id', 'status'), $order_id);

			$status = reset($child_orders);

			$child_orders = array_keys($child_orders);

		} else {

			$status = $order_info['status'];

		}


		if (substr_count('OP', $status) > 0 || substr_count('92', $status) > 0 || substr_count('93', $status) > 0) {

			if ($action == 'repay') {

				fn_set_notification('N', fn_get_lang_var('congratulations'), fn_get_lang_var('text_order_repayed_successfully'));

			} else {

				fn_set_notification('N', fn_get_lang_var('order_placed'), fn_get_lang_var('text_order_placed_successfully'));

			}

		} elseif ($status == 'B') {

			fn_set_notification('W', fn_get_lang_var('important'), fn_get_lang_var('text_order_backordered'));

		} else {

			if (AREA == 'A' || $action == 'repay') {

				if ($status != 'I') {

					$_payment_info = db_get_field("SELECT data FROM ?:order_data WHERE order_id = ?i AND type = 'P'", $order_id);

					if (!empty($_payment_info)) {

						$_payment_info = unserialize(fn_decrypt_text($_payment_info));

						$_msg = !empty($_payment_info['reason_text']) ? $_payment_info['reason_text'] : '';

						$_msg .= empty($_msg) ? fn_get_lang_var('text_order_placed_error') : '';

						fn_set_notification('E', '', $_msg);

					}

				}

			} else {

				$_error = true;

				if (!empty($child_orders)) {

					array_unshift($child_orders, $order_id);

				} else {

					$child_orders = array();

					$child_orders[] = $order_id;

				}

				$_SESSION['cart'][($status == 'N' ? 'processed_order_id' : 'failed_order_id')] = $child_orders;

			}

			if ($status == 'N' || ($action == 'repay' && $status == 'I')) {

				fn_set_notification('W', fn_get_lang_var('important'), fn_get_lang_var('text_transaction_cancelled'));

			}

		}

	}



	// Empty cart

	if ($clear_cart == true && $_error == false) {

		$_SESSION['cart'] = array(

			'user_data' => !empty($_SESSION['cart']['user_data']) ? $_SESSION['cart']['user_data'] : array(), 

			'profile_id' => !empty($_SESSION['cart']['profile_id']) ? $_SESSION['cart']['profile_id'] : 0, 

			'user_id' => !empty($_SESSION['cart']['user_id']) ? $_SESSION['cart']['user_id'] : 0,

		);

		$_SESSION['shipping_rates'] = array();

		unset($_SESSION['shipping_hash']);

		

		db_query('DELETE FROM ?:user_session_products WHERE session_id = ?s AND type = ?s', Session::get_id(), 'C');

	}



	fn_set_hook('order_placement_routines', $order_id, $force_notification, $order_info);



	$prefix = ((Registry::get('settings.General.secure_auth') == 'Y') && (AREA == 'C')) ? Registry::get('config.https_location') . '/' : '';
	//$prefix = ((Registry::get('settings.General.secure_auth') == 'Y') && (AREA == 'C')) ? Registry::get('config.domain_url') . '/' : '';



 	if (AREA == 'A' || $action == 'repay') {

		fn_redirect($prefix . INDEX_SCRIPT . "?dispatch=orders.details&order_id=$order_id", true);

	} else {

		$_SESSION['auth']['skip_redirect_validation'] = true;
		if($cod_api == 1 || $online_payment_api == 1){                    
			if($cod_api == 1){
				return true;
			}
			elseif($online_payment_api == 1){
				fn_redirect($prefix . 'api/'.Registry::get('config.current_api_version').'/payment?order_id='.$order_id. '&key='.Registry::get('config.api_static_key') . '&status=' . $status );
			}
		}

		fn_redirect($prefix . INDEX_SCRIPT . "?dispatch=checkout." . ($_error == true ? (Registry::get('settings.General.checkout_style') != 'multi_page' ? "checkout" : "summary") : "complete&order_id=$order_id"), true);

	}

}



//

// Calculate difference

//

function fn_less_zero($first_arg, $second_arg = 0, $zero = false)

{

	if (!empty($second_arg)) {

		if ($first_arg - $second_arg > 0) {

			return $first_arg - $second_arg;

		} else {

			return 0;

		}

	} else {

		if (empty($zero)) {

			return $first_arg;

		} else {

			return 0;

		}

	}

}


function fn_add_mashipping_cart_product($cart_id, $product_data, &$cart){
    if(!isset($cart['new_cart']['cart_to_show']) || !$cart['multiple_shipping_addresses']){
        return;
    }

    foreach($cart['new_cart']['cart_to_show'] as $_cart_item){
        if ($_cart_item['cart_id'] == $cart_id) { 
            return;
        }
    }
    
    $mashipping_cart_fields = array('amount','cart_id','name','price','product_id','product_options','profile_id');
    $_product_data= array();
    
    foreach($mashipping_cart_fields as $fld){
        if(isset($product_data[$fld])){
            $_product_data[$fld] = $product_data[$fld];
        }
    }
    
    if($_product_data){
        $_product_data['cart_id'] = $cart_id;
        $cart['new_cart']['cart_to_show'][] = $_product_data;
    }
}

function fn_delete_mashipping_cart_product($cart_id, &$cart){
    if(!isset($cart['new_cart']['cart_to_show']) || !$cart['multiple_shipping_addresses']){
        return;
    }

    foreach($cart['new_cart']['cart_to_show'] as $id => $_cart_item){
        if ($_cart_item['cart_id'] == $cart_id) { 
            unset($cart['new_cart']['cart_to_show'][$id]);
            return;
        }
    }
    
}

//

// Add product to cart

//

// @param array $product_data array with data for the product to add)(product_id, price, amount, product_options, is_edp)

// @return mixed cart ID for the product if addition is successful and false otherwise

//

function fn_add_product_to_cart($product_data, &$cart, &$auth, $update = false)

{

	$ids = array();

	if (!empty($product_data) && is_array($product_data)) {
           
		if (!defined('GET_OPTIONS')) {

			list($product_data, $cart) = fn_add_product_options_files($product_data, $cart, $auth, $update);

		}



		fn_set_hook('pre_add_to_cart', $product_data, $cart, $auth, $update);


		foreach ($product_data as $key => $data) {

			if (empty($key)) {

				continue;

			}

			if (empty($data['amount'])) {

				continue;

			}


			$data['stored_price'] = (!empty($data['stored_price']) && AREA != 'C') ? $data['stored_price'] : 'N';



			if (empty($data['extra'])) {

				$data['extra'] = array();

			}


			$product_id = (!empty($data['product_id'])) ? $data['product_id'] : $key;

                        //checks for auction products
                        if (!is_product_allowed_for_user($_SESSION['auth']['user_id'], $product_id)) {
                            fn_set_notification('E', 'Error', 'You cannot add this product to your cart');
                            return false;
                        }
                        //check for auction products end here
                        // Check if product options exist

			if (!isset($data['product_options'])) {

				$data['product_options'] = fn_get_default_product_options($product_id);

			}



			// Generate cart id

			$data['extra']['product_options'] = $data['product_options'];



			$_id = fn_generate_cart_id($product_id, $data['extra'], false);



			if (isset($ids[$_id]) && $key == $_id) {

				continue;

			}



			if (isset($data['extra']['exclude_from_calculate'])) {

				if (!empty($cart['products'][$key]) && !empty($cart['products'][$key]['extra']['aoc'])) {

					$cart['saved_product_options'][$cart['products'][$key]['extra']['saved_options_key']] = $data['product_options'];

				}

				if (isset($cart['deleted_exclude_products'][$data['extra']['exclude_from_calculate']][$_id])) {

					continue;

				}

			}

			$amount = fn_normalize_amount(@$data['amount']);

                         

			if (!isset($data['extra']['exclude_from_calculate'])) {

				if ($data['stored_price'] != 'Y') {

					// Check if the product price with options modifiers equals to zero

					$price = fn_get_product_price($product_id, $amount, $auth);

					$price = fn_apply_options_modifiers($data['product_options'], $price, 'P');

					if (!floatval($price)) {

						$data['price'] = isset($data['price']) ? fn_parse_price($data['price']) : 0;

						$zero_price_action = db_get_field("SELECT zero_price_action FROM ?:products WHERE product_id = ?i", $product_id);

						if (($zero_price_action == 'R' || ($zero_price_action == 'A' && floatval($data['price']) < 0)) && AREA == 'C') {

							if ($zero_price_action == 'A') {

								fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('incorrect_price_warning'));

							}

							continue;

						}

						$price = empty($data['price']) ? 0 : $data['price'];

					}

				} else {

					$price = empty($data['price']) ? 0 : $data['price'];

				}

			} else {

				$price = 0;

			}
                        
                        $is_serviceable = '';
                        if(strlen($_COOKIE['pincode'])=='6' && is_numeric($_COOKIE['pincode']))
                        {
                            $is_serviceable = get_servicability_type($product_id,$_COOKIE['pincode']);
                            
                        }


                        $_data = db_get_row("SELECT pd.product, is_edp, edp_shipping,free_shipping, options_type, tracking, promotion_id,unlimited_download, shipping_freight, list_price, is_cod, exempt_packingfee, c.occasion_id
                                            FROM cscart_products as p
                                            LEFT JOIN cscart_product_descriptions as pd ON p.product_id = pd.product_id 
                                            LEFT JOIN cscart_products_categories as pc ON pc.product_id = p.product_id
                                            LEFT JOIN cscart_categories as c ON c.category_id = pc.category_id and c.status in ('A','H') and c.occasion_id!=''
                                            LEFT JOIN clues_occasions_list_for_fulfillment as olf ON olf.occasion_id=c.occasion_id
                                            WHERE p.product_id = ".$product_id."  
                                            order by occasion_date ASC limit 1");
                        $data['name'] = $_data['product'];
                        $data['occasion_id'] = $_data['occasion_id'];
			$data['is_edp'] = $_data['is_edp'];
                        $data['edp_shipping'] = $_data['edp_shipping'];
                        $data['free_shipping'] = $_data['free_shipping'];

			$data['options_type'] = $_data['options_type'];
                        $data['coupon_code'] = fn_get_upsell_product_coupon_code($_data['promotion_id']);

                        $data['tracking'] = $_data['tracking'];

                        $data['list_price'] = $_data['list_price'];

                        $data['third_price']  = $_data['third_price'];

                        $data['is_cod'] = $_data['is_cod'];

                        $data['exempt_packingfee'] = $_data['exempt_packingfee'];

                        $data['shipping_freight'] = $_data['shipping_freight'];
                        
			$data['extra']['unlimited_download'] = $_data['unlimited_download'];
                        
                        $data['is_serviceable'] = $is_serviceable;

			// Check the sequential options

			if (!empty($data['tracking']) && $data['tracking'] == 'O' && $data['options_type'] == 'S') {

				$inventory_options = db_get_fields("SELECT a.option_id FROM ?:product_options as a LEFT JOIN ?:product_global_option_links as c ON c.option_id = a.option_id WHERE (a.product_id = ?i OR c.product_id = ?i) AND a.status = 'A' AND a.inventory = 'Y'", $product_id, $product_id);

				

				$sequential_completed = true;

				if (!empty($inventory_options)) {

					foreach ($inventory_options as $option_id) {

						if (!isset($data['product_options'][$option_id]) || empty($data['product_options'][$option_id])) {

							$sequential_completed = false;

							break;

						}

					}

				}

				

				if (!$sequential_completed) {

					fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('select_all_product_options'));

					// Even if customer tried to add the product from the catalog page, we will redirect he/she to the detailed product page to give an ability to complete a purchase

					$redirect_url = fn_url('products.view?product_id=' . $product_id . '&combination=' . fn_get_options_combination($data['product_options']));

					$_REQUEST['redirect_url'] = $redirect_url; //FIXME: Very very very BAD style to use the global variables in the functions!!!

					

					return false;

				}

			}

			

			if (!isset($cart['products'][$_id])) { // If product doesn't exists in the cart

				$amount = empty($data['original_amount']) ? fn_check_amount_in_stock($product_id, $amount, $data['product_options'], $_id, $data['is_edp'], 0, $cart, $update == true ? $key : 0) : $data['original_amount'];

				

				if ($amount === false) {
                                                                $_SESSION['zero_inventory_cart']=1;
                                                                continue;

				}



                                $cart['products'][$_id]['product_id'] = $product_id;
                                $cart['products'][$_id]['occasion_id'] = $data['occasion_id'];
                                $cart['products'][$_id]['third_price'] = $data['third_price'];

                                $cart['products'][$_id]['is_serviceable'] = $data['is_serviceable'];

				$cart['products'][$_id]['amount'] = $amount;

				$cart['products'][$_id]['name'] = $data['name'];
                                
				$cart['products'][$_id]['product_options'] = $data['product_options'];

				$cart['products'][$_id]['price'] = $price;
				
				$cart['products'][$_id]['list_price'] = $data['list_price'];
				
				$cart['products'][$_id]['is_cod'] = $data['is_cod'];
                                $cart['products'][$_id]['edp_shipping'] = $data['edp_shipping'];
                                
                                
                                $cart['products'][$_id]['free_shipping'] = $data['free_shipping'];
                                
                                
				
				$cart['products'][$_id]['exempt_packingfee'] = $data['exempt_packingfee'];
                                
                                if(fn_check_if_shipping_price_set_for_product($product_id) && $cart['products'][$_id]['amount'] > 1)
                                {
                                    $cart['products'][$_id]['price'] = empty($price) ? 0 : fn_get_product_price($product_id, $cart['products'][$_id]['amount'], $auth);
                                    
                                    $product_shipping_charge = fn_caclulate_shipping_for_more_quantity($product_id,$cart['products'][$_id]['amount']);
                                    
                                    $cart['products'][$_id]['shipping_freight'] = (empty($product_shipping_charge))? 0 : floatval($product_shipping_charge);
                                }
                                else
                                {
                                    $cart['products'][$_id]['shipping_freight'] = $data['shipping_freight'];
                                }
				
				$cart['products'][$_id]['stored_price'] = $data['stored_price'];
                                $cart['products'][$_id]['coupon_code'] = $data['coupon_code'];


				fn_add_mashipping_cart_product($_id, $cart['products'][$_id], $cart);


				fn_define_original_amount($product_id, $_id, $cart['products'][$_id], $data);


				if ($update == true && $key != $_id) {

					unset($cart['products'][$key]);

				}

                             // set cookie for akamai
			    fn_set_cookie_for_akamai();

			} else { // If product is already exist in the cart



				$_initial_amount = empty($cart['products'][$_id]['original_amount']) ? $cart['products'][$_id]['amount'] : $cart['products'][$_id]['original_amount'];



				// If ID changed (options were changed), summ the total amount of old and new products

				if ($update == true && $key != $_id) {

					$amount += $_initial_amount;

					unset($cart['products'][$key]);

				}


				$cart['products'][$_id]['amount'] = fn_check_amount_in_stock($product_id, (($update == true) ? 0 : $_initial_amount) + $amount, $data['product_options'], $_id, (!empty($data['is_edp']) && $data['is_edp'] == 'Y' ? 'Y' : 'N'), 0, $cart, $update == true ? $key : 0);
                                if(fn_check_if_shipping_price_set_for_product($product_id) && $cart['products'][$_id]['amount'] > 1)
                                {

                                    $cart['products'][$_id]['price'] = empty($price) ? 0 : fn_get_product_price($product_id, $cart['products'][$_id]['amount'], $auth);
                                    
                                    $product_shipping_charge = fn_caclulate_shipping_for_more_quantity($product_id,$cart['products'][$_id]['amount']);
                                    
                                    $cart['products'][$_id]['shipping_freight'] = (empty($product_shipping_charge))? 0 : floatval($product_shipping_charge);

                                }

			}



			$cart['products'][$_id]['extra'] = (empty($data['extra'])) ? array() : $data['extra'];

			$cart['products'][$_id]['stored_discount'] = @$data['stored_discount'];

			if (defined('ORDER_MANAGEMENT')) {

				$cart['products'][$_id]['discount'] = @$data['discount'];

			}



			// Increase product popularity

			if (empty($_SESSION['products_popularity']['added'][$product_id])) {

				$_data = array (

					'product_id' => $product_id,

					'added' => 1,

					'total' => POPULARITY_ADD_TO_CART

				);

				

				db_query("INSERT INTO ?:product_popularity ?e ON DUPLICATE KEY UPDATE added = added + 1, total = total + ?i", $_data, POPULARITY_ADD_TO_CART);

				

				$_SESSION['products_popularity']['added'][$product_id] = true;

			}

			

			$company_id = db_get_field("SELECT company_id FROM ?:products WHERE product_id = ?i", $product_id);

			$cart['products'][$_id]['company_id'] = $company_id;
			
			/*added by chandan to add is_giftable variable in cart*/
			$sql = "select cc.fulfillment_id from cscart_companies cc 
					left join clues_warehouse_contact cwc on cwc.company_id = cc.company_id
					where cc.company_id = $company_id and cwc.region_code in ('".Registry::get('config.giftable_region_code')."')";
			//die;		
			//$company_fulfillment_id = db_get_field("select fulfillment_id from ?:companies where company_id=?i", $company_id);			
			$company_fulfillment_id = db_get_field($sql);
			if(in_array($company_fulfillment_id, Registry::get('config.giftable_fulfilment_id'))){
				$cart['products'][$_id]['giftable']	 = 'Y';
			}else{
				$cart['products'][$_id]['giftable']	 = 'N';
			}
			/*added by chandan to add is_giftable variable in cart*/

			fn_set_hook('add_to_cart', $cart, $product_id, $_id);



			$ids[$_id] = $product_id;

		}




		$cart['recalculate'] = true;
                update_cart_cookie();
                if(isset($cart['cod_eligible_order_id'])){
                    unset($cart['cod_eligible_order_id']);
                }

		return $ids;
                


	} else {

		return false;

	}

}



function fn_form_cart($order_id, &$cart, &$auth)

{

	$order_info = fn_get_order_info($order_id, false, false);



	// Fill the cart

	foreach ($order_info['items'] as $_id => $item) {

		$_item = array (

			$item['product_id'] => array (

				'amount' => $item['amount'],

				'product_options' => @$item['extra']['product_options'],

				'price' => $item['original_price'],

				'stored_discount' => 'Y',

				'stored_price' => 'Y',

				'discount' => @$item['extra']['discount'],

				'original_amount' => $item['amount'], // the original amount, that stored in order

				'original_product_data' => array ( // the original cart ID and amount, that stored in order

					'cart_id' => $_id,

					'amount' => $item['amount'],

				),

			),

		);

		if (isset($item['extra'])) {

			$_item[$item['product_id']]['extra'] = $item['extra'];

		}

		fn_add_product_to_cart($_item, $cart, $auth);

	}



	// Restore custom files

	$dir_path = DIR_CUSTOM_FILES . 'order_data/' . $order_id;

	

	if (is_dir($dir_path)) {

		fn_mkdir(DIR_CUSTOM_FILES . 'sess_data');

		fn_copy($dir_path, DIR_CUSTOM_FILES . 'sess_data');

	}



	$cart['payment_id'] = $order_info['payment_id'];
	
	/*added by chandan to preserve the payment option id when order is bing edit from admin side*/
	$cart['o_payment_id'] = $order_info['payment_id'];
	$cart['payment_option_id'] = $order_info['payment_option_id'];
	/*added by chandan to preserve the payment option id when order is bing edit from admin side*/
	$cart['stored_taxes'] = 'Y';

	$cart['stored_discount'] = 'Y';

	$cart['taxes'] = $order_info['taxes'];

	$cart['promotions'] = !empty($order_info['promotions']) ? $order_info['promotions'] : array();



	$cart['shipping'] = (!empty($order_info['shipping'])) ? $order_info['shipping'] : array();

	$cart['stored_shipping'] = array();

	foreach ($cart['shipping'] as $sh_id => $v) {

		if (!empty($v['rates'])) {

			$cart['stored_shipping'][$sh_id] = array_sum($v['rates']);

		}

	}



	$cart['notes'] = $order_info['notes'];
	
	/*Added by chandan to add emi fee when order edit by admin*/
	$cart['emi_fee'] = $order_info['emi_fee'];
	/*Added by chandan to add emi fee when order edit by admin*/
	$cart['cod_fee'] = $order_info['cod_fee'];
	/*Added by chandan to add gifting fee when order edit by admin*/
	$cart['gift_it'] = $order_info['gift_it'];
	$cart['gifting']['gift_it'] = $order_info['gift_it'];
	$cart['gifting_charge'] = $order_info['gifting_charge'];
	
	/*Added by chandan to add gifting fee when order edit by admin*/
	$cart['payment_info'] = @$order_info['payment_info'];



	// Add order discount

	if (floatval($order_info['subtotal_discount'])) {

		$cart['stored_subtotal_discount'] = 'Y';

		$cart['subtotal_discount'] = $cart['original_subtotal_discount'] = fn_format_price($order_info['subtotal_discount']);

	}



	// Fill the cart with the coupons

	if (!empty($order_info['coupons'])) {

		$cart['coupons'] = $order_info['coupons'];

	}



	// Set the customer if exists

	$_data = array();

	if (!empty($order_info['user_id'])) {

		$_data = db_get_row("SELECT user_id, user_login as login FROM ?:users WHERE user_id = ?i", $order_info['user_id']);

	}

	$auth = fn_fill_auth($_data, array(), false, 'C');

	$auth['tax_exempt'] = $order_info['tax_exempt'];



	// Fill customer info

	$cart['user_data'] = fn_check_table_fields($order_info, 'user_profiles');

	$cart['user_data'] = fn_array_merge(fn_check_table_fields($order_info, 'users'), $cart['user_data']);

	if (!empty($order_info['fields'])) {

		$cart['user_data']['fields'] = $order_info['fields'];

	}

	fn_add_user_data_descriptions($cart['user_data']);

	/*modified by chandan to add parent order id */
	$cart['processed_order_id'] = array();

	$cart['processed_order_id'][] = $order_info['parent_order_id'];
	/*modified by chandan to add parent order id */

	fn_set_hook('form_cart', $order_info, $cart);

}



//

// Calculate taxes for products or shippings

//

function fn_calculate_tax_rates($taxes, $price, $amount, $auth, &$cart)

{

	static $destination_id;

	static $tax_description;

	static $user_data;



	$taxed_price = $price;



	if (!empty($cart['user_data'])) {

		$profile_fields = fn_get_profile_fields('O', $auth);

		$billing_population = fn_check_profile_fields_population($cart['user_data'], 'B', $profile_fields);

		$shipping_population = fn_check_profile_fields_population($cart['user_data'], 'S', $profile_fields);

	}



	if (empty($auth['user_id']) && (empty($cart['user_data']) || fn_is_empty($cart['user_data']) || $billing_population != true || $shipping_population != true) && defined('CHECKOUT') && Registry::get('settings.Appearance.taxes_using_default_address') !== 'Y' && !defined('ESTIMATION')) {

		return false;

	}



	if ((empty($destination_id) || $user_data != @$cart['user_data'])) {

		// Get billing location

		$location = fn_get_customer_location($auth, $cart, true);

		$destination_id['B'] = fn_get_available_destination($location);



		// Get shipping location

		$location = fn_get_customer_location($auth, $cart);

		$destination_id['S'] = fn_get_available_destination($location);

	}



	if (!empty($cart['user_data'])) {

		$user_data = $cart['user_data'];

	}

	$_tax = 0;

	$previous_priority = 0;



	foreach ($taxes as $key => $tax) {

		if (empty($tax['tax_id'])) {

			$tax['tax_id'] = $key;

		}



		if (empty($tax['priority'])) {

			$tax['priority'] = 1;

		}



		$_is_zero = floatval($taxed_price);

		if (empty($_is_zero)) {

			continue;

		}



		if (!empty($cart['stored_taxes']) && $cart['stored_taxes'] == 'Y' && !empty($tax['rate_type'])) {

			$rate = array (

				'rate_value' => $tax['rate_value'],

				'rate_type' => $tax['rate_type'],

			);

		} else {

			if (!isset($destination_id[$tax['address_type']])) {

				continue;

			}



			$rate = db_get_row("SELECT destination_id, apply_to, rate_value, rate_type FROM ?:tax_rates WHERE tax_id = ?i AND destination_id = ?i", $tax['tax_id'], $destination_id[$tax['address_type']]);

			if (!@floatval($rate['rate_value'])) {

				continue;

			}

		}





		$base_price = ($tax['priority'] == $previous_priority) ? $previous_price : $taxed_price;



		if ($rate['rate_type'] == 'P') { // Percent dependence

			// If tax is included into the price

			if ($tax['price_includes_tax'] == 'Y') {

				$_tax = fn_format_price($base_price - $base_price / ( 1 + ($rate['rate_value'] / 100)));

				// If tax is NOT included into the price

			} else {

				$_tax = fn_format_price($base_price * ($rate['rate_value'] / 100));

				$taxed_price += $_tax;

			}



		} else {

			$_tax = fn_format_price($rate['rate_value']);

			// If tax is NOT included into the price

			if ($tax['price_includes_tax'] != 'Y') {

				$taxed_price += $_tax;

			}

		}



		$previous_priority = $tax['priority'];

		$previous_price = $base_price;



		if (empty($tax_description[$tax['tax_id']])) {

			$tax_description[$tax['tax_id']] = db_get_field("SELECT tax FROM ?:tax_descriptions WHERE tax_id = ?i AND lang_code = ?s", $tax['tax_id'], CART_LANGUAGE);

		}



		$taxes_data[$tax['tax_id']] = array (

			'rate_type' => $rate['rate_type'],

			'rate_value' => $rate['rate_value'],

			'price_includes_tax' => $tax['price_includes_tax'],

			'regnumber' => @$tax['regnumber'],

			'priority' => @$tax['priority'],

			'tax_subtotal' => fn_format_price($_tax * $amount),

			'description' => $tax_description[$tax['tax_id']],

		);

	}



	return empty($taxes_data) ? false : $taxes_data;

}



//

// Get order status data

//

function fn_get_status_data($status, $type = STATUSES_ORDER, $object_id = 0, $lang_code = CART_LANGUAGE)

{

	$data = db_get_row("SELECT * FROM ?:status_descriptions WHERE status = ?s AND type = ?s AND lang_code = ?s", $status, $type, $lang_code);



	fn_set_hook('get_status_data', $data, $status, $type, $object_id, $lang_code);



	return $data;

}



//

//Get order payment data

//

function fn_get_payment_data($payment_id, $object_id = 0, $lang_code = CART_LANGUAGE)

{	

	$data = db_get_row("SELECT * FROM ?:payment_descriptions WHERE payment_id = ?i AND lang_code = ?s", $payment_id, $lang_code);

	

	fn_set_hook('get_payment_data', $data, $payment_id, $object_id, $lang_code);

	

	return $data;

}



//

// Get all order statuses

//

function fn_get_statuses($type = STATUSES_ORDER, $simple = false, $additional_statuses = false, $exclude_parent = false, $lang_code = CART_LANGUAGE)

{

	if ($simple) {
		
			$statuses = db_get_hash_single_array("SELECT a.status, b.description FROM ?:statuses as a LEFT JOIN ?:status_descriptions as b ON b.status = a.status AND b.type = a.type AND b.lang_code = ?s WHERE a.type = ?s ", array('status', 'description'), $lang_code, $type);
	
			if ($type == STATUSES_ORDER && !empty($additional_statuses)) {
	
				$statuses['N'] = fn_get_lang_var('incompleted', $lang_code);
	
				if (empty($exclude_parent)) {
	
					$statuses[STATUS_PARENT_ORDER] = fn_get_lang_var('parent_order', $lang_code);
	
				}
	
			}
		

	} else {

		$statuses = db_get_hash_array("SELECT a.status, b.description FROM ?:statuses as a LEFT JOIN ?:status_descriptions as b ON b.status = a.status AND b.type = a.type AND b.lang_code = ?s WHERE a.type = ?s", 'status', $lang_code, $type);
		
		foreach ($statuses as $status => $data) {

			$statuses[$status] = fn_array_merge($statuses[$status], fn_get_status_params($status, $type));

		}

		if ($type == STATUSES_ORDER && !empty($additional_statuses)) {

			$statuses[STATUS_INCOMPLETED_ORDER] = array (

				'status' => STATUS_INCOMPLETED_ORDER,

				'description' => fn_get_lang_var('incompleted', $lang_code),

				'inventory' => 'I',

				'type' => STATUSES_ORDER,

			);

			if (empty($exclude_parent)) {

				$statuses[STATUS_PARENT_ORDER] = array (

					'status' => STATUS_PARENT_ORDER,

					'description' => fn_get_lang_var('parent_order', $lang_code),

					'inventory' => 'I',

					'type' => STATUSES_ORDER,

				);

			}

		}

	}



	return $statuses;

}



function fn_get_status_params($status, $type = STATUSES_ORDER)
{
	/*if(Registry::get('config.memcache'))
	{
		$memcache = $GLOBALS['memcache'];
		$key = md5($status.'-statuses');
		if($mem_value = $memcache->get($key)){
				$statuses = $mem_value;
		}else{
			$statuses = db_get_hash_single_array("SELECT param, value FROM ?:status_data WHERE status = ?s AND type = ?s", array('param', 'value'), $status, $type);
			$memcache->set($key, $statuses, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time')) or die ("Failed to save data at the server");
		}
	}else{
		$statuses = db_get_hash_single_array("SELECT param, value FROM ?:status_data WHERE status = ?s AND type = ?s", array('param', 'value'), $status, $type);
		//echo '<pre>';print_r($statuses);echo '</pre>';		
	}*/
    
          if(Registry::get('config.memcache') && $GLOBALS['memcache_status']){

                    $memcache = $GLOBALS['memcache'];
                    $value = "SELECT param, value FROM ?:status_data USE INDEX(stat_type_idx) WHERE status = ?s AND type = ?s".$status. $type;
                    $key = md5($value);
                    if($mem_value = $memcache->get($key)){
                        return $mem_value;
                    }else {
                            $status_value = db_get_hash_single_array("SELECT param, value FROM ?:status_data USE INDEX(stat_type_idx) WHERE status = ?s AND type = ?s", array('param', 'value'), $status, $type);
                            $status = $memcache->set($key, $status_value, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time')) ; 
                            if(!$status){
                                $memcache->delete($key);
                            }
                    }
        }else{ 
               return db_get_hash_single_array("SELECT param, value FROM ?:status_data USE INDEX(stat_type_idx) WHERE status = ?s AND type = ?s", array('param', 'value'), $status, $type);
        }
	//return db_get_hash_single_array("SELECT param, value FROM ?:status_data USE INDEX(stat_type_idx) WHERE status = ?s AND type = ?s", array('param', 'value'), $status, $type);
	//return $statuses;
}

//

// Delete product from the cart

//
function fn_synchronize_mashipping_cart_with_main_cart(&$cart){
    if(!$cart['multiple_shipping_addresses'] || !count($cart['new_cart']['cart_to_show'])){
        return;
    }
    
    $new_cart_datas = &$cart['new_cart']['cart_to_show'];
    $cart_ids = array_keys($cart['products']);
    foreach($new_cart_datas as $k => &$new_cart_product){
        if(!in_array($new_cart_product['cart_id'], $cart_ids)){
            unset($new_cart_datas[$k]);
            continue;
        }
        $new_cart_quantity[$new_cart_product['cart_id']] += $new_cart_product['amount'];
    }
    
    foreach($cart['products'] as $cid => $cp){
        $cart_quantity[$cid] += $cp['amount'];
    }
    
    $extra_quantity = array();
    foreach($new_cart_quantity as $k => $qty){
        if($qty > $cart_quantity[$k]){
            $extra_quantity[$k] = $qty - $cart_quantity[$k];
        }
    }
    $new_cart_datas = array_reverse($new_cart_datas);
    foreach($new_cart_datas as $k => &$new_cart_product){
        $ctid = $new_cart_product['cart_id'];
        if($extra_quantity[$ctid] > 0){
            if($extra_quantity[$ctid] > $new_cart_product['amount']){
               $extra_quantity[$ctid] -= $new_cart_product['amount'];
               unset($new_cart_datas[$k]);
            }
            else if($extra_quantity[$ctid] < $new_cart_product['amount']){
               $new_cart_product['amount'] -= $extra_quantity[$ctid];
               unset($extra_quantity[$ctid]);
            }
            else if($extra_quantity[$ctid] == $new_cart_product['amount']){
               unset($new_cart_datas[$k]);
               unset($extra_quantity[$ctid]);
            }
        }
    }
    $new_cart_datas = array_reverse($new_cart_datas);
}
                
function fn_delete_cart_product(&$cart, $cart_id, $full_erase = true)

{

	fn_set_hook('delete_cart_product', $cart, $cart_id, $full_erase);



	if (!empty($cart_id) && !empty($cart['products'][$cart_id])) {

		// Decrease product popularity

		$product_id = $cart['products'][$cart_id]['product_id'];

		

		$_data = array (

			'product_id' => $product_id,

			'deleted' => 1,

			'total' => 0

		);

		

		db_query("INSERT INTO ?:product_popularity ?e ON DUPLICATE KEY UPDATE deleted = deleted + 1, total = total - ?i", $_data, POPULARITY_DELETE_FROM_CART);

		

		unset($_SESSION['products_popularity']['added'][$product_id]);

		

		// Delete saved product files

		if (isset($cart['products'][$cart_id]['extra']['custom_files'])) {

			foreach ($cart['products'][$cart_id]['extra']['custom_files'] as $option_id => $images) {

				if (!empty($images)) {

					foreach ($images as $image) {

						@unlink($image['path']);

						@unlink($image['path'] . '_thumb');

					}

				}

			}

		}

		

		unset($cart['products'][$cart_id]);

		$cart['recalculate'] = true;

	}


        update_cart_cookie();
	return true;

}



//

// Checks whether this order used the current payment and calls the payment_cc_complete.php file

//

function fn_check_payment_script($script_name, $order_id, &$processor_data = null)

{

	$payment_id = db_get_field("SELECT payment_id FROM ?:orders WHERE order_id = ?i", $order_id);

	$processor_data = fn_get_processor_data($payment_id);

	if ($processor_data['processor_script'] == $script_name) {

		return true;

	}

	return false;

}



//

// This function calculates product prices without taxes and with taxes

//

function fn_get_taxed_and_clean_prices(&$product, &$auth)

{

	$tax_value = 0;

	$included_tax = false;



	if (empty($product) || empty($product['product_id']) || empty($product['tax_ids'])) {

		return false;

	}

	if (isset($product['subtotal'])) {

		$tx_price =  $product['subtotal'];

	} elseif (empty($product['price'])) {

		$tx_price = 0;

	} elseif (isset($product['discounted_price'])) {

		$tx_price = $product['discounted_price'];

	} else {

		$tx_price = $product['price'];

	}



	$product_taxes = fn_get_set_taxes($product['tax_ids']);



	$calculated_data = fn_calculate_tax_rates($product_taxes, $tx_price, 1, $auth, $_SESSION['cart']);

	// Apply taxes to product subtotal

	if (!empty($calculated_data)) {

		foreach ($calculated_data as $_k => $v) {

			$tax_value += $v['tax_subtotal'];

			if ($v['price_includes_tax'] != 'Y') {

				$included_tax = true;

				$tx_price += $v['tax_subtotal'];

			}

		}

	}



	$product['clean_price'] = $tx_price - $tax_value;

	$product['taxed_price'] = $tx_price;

	$product['taxes'] = $calculated_data;

	$product['included_tax'] = $included_tax;



	return true;

}



function fn_clear_cart(&$cart, $complete = false, $clear_all = false)

{

	fn_set_hook('clear_cart', $cart, $complete, $clear_all);

	

	// Decrease products popularity
	
	//code by ankur to unset gc session
	 if(isset($cart['use_gift_certificates']))
	 {
		 foreach($cart['use_gift_certificates'] as $k=>$v)
		 {
			 if(isset($_SESSION[$k]))
			 unset($_SESSION[$k]);
		 }
	 }
	//code end

	if (!empty($cart['products'])) {

		$pids = array();

		

		foreach ($cart['products'] as $product) {

			$pids[] = $product['product_id'];

			unset($_SESSION['products_popularity']['added'][$product['product_id']]);

		}

		

		db_query("UPDATE ?:product_popularity SET deleted = deleted + 1, total = total - ?i WHERE product_id IN (?n)", POPULARITY_DELETE_FROM_CART, $pids);

	}

	

	if ($clear_all) {

		$cart = array();

	} else {

		$cart = array (

			'products' => array(),

			'recalculate' => false,

			'user_data' => !empty($cart['user_data']) && $complete == false ? $cart['user_data'] : array(),

		);

	}
	// set cookie for akamai
	fn_set_cookie_for_akamai();
	return true;

}



function fn_apply_cart_shipping_rates(&$cart, &$cart_products, &$auth, &$shipping_rates)

{

	$cart['shipping_failed'] = true;



	if (!fn_is_empty($shipping_rates)) {



		// Delete all free shippings

		foreach ($shipping_rates as $k => $v) {

			if (!empty($v['free_shipping']) && !in_array($k, $cart['free_shipping'])) {

				if (!empty($v['original_rates'])) {

					$shipping_rates[$k]['rates'] = $v['original_rates'];

					unset($shipping_rates[$k]['free_shipping']);

				} else {

					unset($shipping_rates[$k]);

				}

			}

		}



		// Set free shipping rates

		if (!empty($cart['free_shipping'])) {

			foreach ($cart['free_shipping'] as $sh_id) {

				if (isset($shipping_rates[$sh_id])) {

					if (empty($shipping_rates[$sh_id]['added_manually'])) {

						if (empty($shipping_rates[$sh_id]['original_rates'])) { // save original rates

							$shipping_rates[$sh_id]['original_rates'] = $shipping_rates[$sh_id]['rates'];

						}

						foreach ($shipping_rates[$sh_id]['rates'] as $_k => $_v) { // null rates

							$shipping_rates[$sh_id]['rates'][$_k] = 0;

						}

					}

				} else {

					$name = db_get_row("SELECT b.shipping as name, b.delivery_time FROM ?:shippings as a LEFT JOIN ?:shipping_descriptions as b ON a.shipping_id = b.shipping_id AND b.lang_code = ?s WHERE a.shipping_id = ?i AND a.status = 'A'", CART_LANGUAGE, $sh_id);

					if (!empty($name)) {

						$shipping_rates[$sh_id] = $name;

						$shipping_rates[$sh_id]['rates'] = array(0);

						$shipping_rates[$sh_id]['added_manually'] = true;

					}

				}



				if (isset($shipping_rates[$sh_id])) {

					$shipping_rates[$sh_id]['free_shipping'] = true;

				}

			}

			

			$positions = db_get_hash_array('SELECT position, shipping_id FROM ?:shippings WHERE shipping_id IN (?a)', 'shipping_id', array_keys($shipping_rates));

			foreach ($positions as $shipping_id => $position) {

				$shipping_rates[$shipping_id]['position'] = $position['position'];

			}

			$shipping_rates = fn_sort_array_by_key($shipping_rates, 'position', SORT_ASC);
		

		}



		// Delete not existent rates

		if (!empty($cart['shipping'])) {

			foreach ($cart['shipping'] as $sh_id => $v) {

				foreach ($v['rates'] as $o_id => $r) {

					if (!isset($shipping_rates[$sh_id]['rates'][$o_id]) && empty($shipping_rates[$sh_id]['added_manually'])) {

						unset($cart['shipping'][$sh_id]);

					}

				}

			}

		}

		if (fn_check_suppliers_functionality()) {

			fn_companies_apply_cart_shipping_rates($cart, $cart_products, $auth, $shipping_rates);


		} else {

			if (isset($cart['shipping']) && false != reset($cart['shipping']) && isset($shipping_rates[key($cart['shipping'])])) {

				$k = key($cart['shipping']);

				$first_method = $shipping_rates[$k];



				// enables to select the last chosen shipping method on checkout

			} elseif (isset($cart['chosen_shipping']) && false != reset($cart['chosen_shipping']) && isset($shipping_rates[key($cart['chosen_shipping'])])) {

				$cart['shipping'] = $cart['chosen_shipping'];

				$k = key($cart['chosen_shipping']);

				$first_method = $shipping_rates[$k];

			} else {

				$cart['shipping'] = array();

				$first_method = reset($shipping_rates);

				$k = key($shipping_rates);

			}

			

			$cart['shipping_cost'] = reset($first_method['rates']);

			$cart['shipping'] = fn_array_merge(isset($cart['shipping']) ? $cart['shipping'] : array(), array(

				$k => array(

					'shipping' => $first_method['name'],

					'rates' => $first_method['rates'],

					'packages_info' => isset($first_method['packages_info']) ? $first_method['packages_info'] : array(),

				)

			));

		}



		if (!empty($cart['shipping'])) {

			$cart['shipping_failed'] = false;

		}

		

		fn_set_hook('apply_cart_shipping_rates', $cart, $cart_products, $auth, $shipping_rates);

	}

}



function fn_external_discounts($product)

{

	$discounts = 0;



	fn_set_hook('get_external_discounts', $product, $discounts);



	return $discounts;

}



// FIX-EVENT - must be revbuilt to check edp, free, etc

function fn_exclude_from_shipping_calculate($product)

{

	$exclude = false;



	fn_set_hook('exclude_from_shipping_calculation', $product, $exclude);



	return $exclude;

}

//

// This function is used to find out the total shipping cost. Used in payments, quickbooks

//



function fn_order_shipping_cost($order_info)

{

	$cost = (floatval($order_info['shipping_cost'])) ? $order_info['shipping_cost'] : 0;



	if (floatval($order_info['shipping_cost'])) {

		foreach($order_info['taxes'] as $tax) {

			if ($tax['price_includes_tax'] == 'N') {

				foreach ($tax['applies'] as $_id => $value) {

					if (strpos($_id, 'S_') !== false) {

						$cost += $value;

					}

				}

			}

		}

	}



	return $cost ? fn_format_price($cost) : 0;

}



//

// Cleanup payment information

//

function fn_cleanup_payment_info($order_id, $payment_info, $silent = false)

{



	if ($silent == false) {

		fn_set_progress('echo', fn_get_lang_var('processing_order') . '&nbsp;<b>#'.$order_id.'</b>...');

	}



	if (!is_array($payment_info)) {

		$info = @unserialize(fn_decrypt_text($payment_info));

	} else {

		$info = $payment_info;

	}



	if (!empty($info['cvv2'])) {

		$info['cvv2'] = 'XXX';

	}

	if (!empty($info['card_number'])) {

		$info['card_number'] = substr_replace($info['card_number'], str_repeat('X', strlen($info['card_number']) - 4), 0, strlen($info['card_number']) - 4);

	}



	foreach (array('start_month', 'start_year', 'expiry_month', 'expiry_year') as $v) {

		if (!empty($info[$v])) {

			$info[$v] = 'XX';

		}

	}



	$_data = fn_encrypt_text(serialize($info));

	db_query("UPDATE ?:order_data SET data = ?s WHERE order_id = ?i AND type = 'P'", $_data, $order_id);

}



//

// Checks if order can be placed

//

function fn_allow_place_order(&$cart)

{

	$total = Registry::get('settings.General.min_order_amount_type') == "S" ? $cart['total'] : $cart['subtotal'];



	fn_set_hook('allow_place_order', $total, $cart);



	$cart['amount_failed'] = (Registry::get('settings.General.min_order_amount') > $total && floatval($total));



	if (!empty($cart['amount_failed']) || !empty($cart['shipping_failed']) || !empty($cart['company_shipping_failed'])) {

		return false;

	}



	return true;

}







/**

 * Calculate shipping rates using real-time shipping processors

 *

 * @param int $service_id shipping service ID

 * @param array $location customer location

 * @param array $package_info package information (weight, subtotal, qty)

 * @param array $auth customer session information

 * @param array $substitution_settings settings what can replace default shipping origination

 * @return mixed array with rates if calculated, false otherwise

 */

function fn_calculate_realtime_shipping_rate($service_id, $location, $package_info, &$auth, $shipping_id, $allow_multithreading = false, $new_settings = array())

{

	static $shipping_settings = array();



	$code = fn_get_shipping_service_data($service_id);



	if (empty($code)) {

		return false;

	}

	

	if (empty($shipping_settings)) {

		$shipping_settings = fn_get_settings('Shippings');

	}

	if (!empty($new_settings)) {

		$shipping_settings[$code['module']] = $new_settings;

	} else {

		$shipping_settings[$code['module']] = fn_get_shipping_params($shipping_id);

	}



	include_once(DIR_LIB . 'xmldocument/xmldocument.php');

	include_once(DIR_SHIPPING_FILES . $code['module'] . '.php');



	$func = 'fn_get_' . $code['module'] . '_rates';

	$weight = fn_expand_weight($package_info['W']);



	return $func($code['code'], $weight, $location, $auth, $shipping_settings, $package_info, $package_info['origination'], $service_id, $allow_multithreading);

}



function fn_get_shipping_params($shipping_id)

{

	$params = array();

	if ($shipping_id) {

		$params = db_get_field("SELECT params FROM ?:shippings WHERE shipping_id = ?i", $shipping_id);

		$params = unserialize($params);

	}

	return $params;

}



function fn_get_shipping_service_data($service_id)

{

	static $services = array();



	if (!isset($services[$service_id])) {



		$service = db_get_row("SELECT intershipper_code, code, module FROM ?:shipping_services WHERE service_id = ?i AND status = 'A'", $service_id);



		if (empty($service)) {

			$services[$service_id] = false;

			return false;

		}



		if (!empty($service['intershipper_code']) && Registry::get('settings.Shippings.intershipper_enabled') == 'Y') {

			$service['module'] = 'intershipper';

			$service['code'] = $service['intershipper_code'];

		}



		$services[$service_id] = $service;

	}


	return $services[$service_id];

}



/**

 * Convert weight to pounds/ounces

 *

 * @param float $weight weight

 * @return array converted data

 */

function fn_expand_weight($weight)

{

	$full_ounces = ceil(round($weight * Registry::get('settings.General.weight_symbol_grams') / 28.35, 3));

	$full_pounds = sprintf("%.1f", $full_ounces/16);

	$pounds = floor($full_ounces/16);

	$ounces = $full_ounces - $pounds * 16;



	return array (

		'full_ounces' => $full_ounces,

		'full_pounds' => $full_pounds,

		'pounds' => $pounds,

		'ounces' => $ounces,

		'plain' => $weight,

	);

}



/**

 * Generate unique ID to cache rates calculation results

 *

 * @param mixed parameters to generate unique ID from

 * @return mixed array with rates if calculated, false otherwise

 */

function fn_generate_cached_rate_id()

{

	return md5(serialize(func_get_args()));

}



/**

 * Send order notification

 *

 * @param array $order_info order information

 * @param array $edp_data information about downloadable products

 * @param mixed $force_notification user notification flag (true/false), if not set, will be retrieved from status parameters

 * @return array structured data

 */



function fn_order_notification(&$order_info, $edp_data = array(), $force_notification = array())

{
// add by ajay for show payment_type_name in mail
    $paid = db_get_row("SELECT cpo.name,cpt.name as type_name FROM cscart_orders co , clues_payment_options cpo, 
		              clues_payment_types cpt where co.payment_option_id = cpo.payment_option_id AND 
			      cpo.payment_type_id=cpt.payment_type_id AND co.order_id='" . $order_info['order_id'] . "'");

    $order_info['paid'] = $paid;
    // end by ajay
    
	static $notified = array();



	$send_order_notification = true;



	if ((!empty($notified[$order_info['order_id']][$order_info['status']]) && $notified[$order_info['order_id']][$order_info['status']]) || $order_info['status'] == STATUS_INCOMPLETED_ORDER || $order_info['status'] == STATUS_PARENT_ORDER) {

		$send_order_notification = false;

	}

	

	fn_set_hook('send_order_notification', $order_info, $edp_data, $force_notification, $notified, $send_order_notification);



	if (!$send_order_notification) {

		return true;

	}

	

	if (!is_array($force_notification)) {

		$force_notification = fn_get_notification_rules($force_notification, !$force_notification);	

	}



	$order_statuses = fn_get_statuses(STATUSES_ORDER, false, true);

	$status_params = $order_statuses[$order_info['status']];



	$notify_user = isset($force_notification['C']) ? $force_notification['C'] : (!empty($status_params['notify']) && $status_params['notify'] == 'Y' ? true : false);

	$notify_department = isset($force_notification['A']) ? $force_notification['A'] : (!empty($status_params['notify_department']) && $status_params['notify_department'] == 'Y' ? true : false);

	$notify_supplier = isset($force_notification['S']) ? $force_notification['S'] : (!empty($status_params['notify_supplier']) && $status_params['notify_supplier'] == 'Y' ? true : false);



	$company_id = $order_info['company_id'];

	$company = fn_get_company_placement_info($company_id, $order_info['lang_code']);

	Registry::get('view_mail')->assign('company_placement_info', $company);

	Registry::get('view_mail')->assign('manifest', fn_get_manifest('customer', $order_info['lang_code'], $company_id));



	if ($notify_user == true || $notify_department == true || $notify_supplier == true) {



		$notified[$order_info['order_id']][$order_info['status']] = true;

       
		Registry::get('view_mail')->assign('order_info', $order_info);

		Registry::get('view_mail')->assign('order_status', fn_get_status_data($order_info['status'], STATUSES_ORDER, $order_info['order_id'], $order_info['lang_code']));

		Registry::get('view_mail')->assign('payment_method', fn_get_payment_data((!empty($order_info['payment_method']['payment_id']) ? $order_info['payment_method']['payment_id'] : 0), $order_info['order_id'], $order_info['lang_code']));

		Registry::get('view_mail')->assign('status_settings', $order_statuses[$order_info['status']]);

		Registry::get('view_mail')->assign('profile_fields', fn_get_profile_fields('I', '', $order_info['lang_code']));



		// restore secondary currency

		if (!empty($order_info['secondary_currency']) && Registry::get("currencies.{$order_info['secondary_currency']}")) {

			Registry::get('view_mail')->assign('secondary_currency', $order_info['secondary_currency']);

		}



		// Notify customer

		if ($notify_user == true) {

			Registry::get('view_mail')->assign('manifest', fn_get_manifest('customer', $order_info['lang_code'], $company_id));

			/*Modified by chandan*/

			

			/*db_query("INSERT INTO  clues_email_queue (order_id, from_email, to_email, subject, message) values('".$order_info['order_id']."','".Registry::get('settings.Company.company_orders_department')."','".$order_info['email']."','".addslashes(Registry::get('view_mail')->display('orders/order_notification_subj.tpl', false))."','".addslashes(Registry::get('view_mail')->display('orders/order_notification.tpl', false))."')");*/			

	//		fn_send_mail($order_info['email'], Registry::get('settings.Company.company_orders_department'), 'orders/order_notification_subj.tpl', 'orders/order_notification.tpl', '', $order_info['lang_code']);
            fn_instant_mail($order_info['email'], Registry::get('settings.Company.company_orders_department'), 'orders/order_notification_subj.tpl', 'orders/order_notification.tpl');


			if (!empty($edp_data)) {

				Registry::get('view_mail')->assign('edp_data', $edp_data);

				/*db_query("INSERT INTO  clues_email_queue (order_id, from_email, to_email, subject, message) values('".$order_info['order_id']."','".Registry::get('settings.Company.company_orders_department')."','".$order_info['email']."','".addslashes(Registry::get('view_mail')->display('orders/edp_access_subj.tpl', false))."','".addslashes(Registry::get('view_mail')->display('orders/edp_access.tpl', false))."')");*/				

				fn_send_mail($order_info['email'], Registry::get('settings.Company.company_orders_department'), 'orders/edp_access_subj.tpl', 'orders/edp_access.tpl', '', $order_info['lang_code']);

			}

		}

		/*Modified by chandan*/

		// Notify supplier or vendor

		if ($notify_supplier == true) {

			if (PRODUCT_TYPE == 'PROFESSIONAL') {



				fn_companies_suppliers_order_notification($order_info, $order_statuses, $force_notification);



			} elseif (PRODUCT_TYPE == 'MULTIVENDOR' && !empty($company_id)) {



				Registry::get('view_mail')->assign('manifest', fn_get_manifest('customer', $company['lang_code'], $company_id));

				Registry::get('view_mail')->assign('company_placement_info', fn_get_company_placement_info($company_id, $company['lang_code']));

                //change by ankur to identified that mail is going to merchant
				Registry::get('view_mail')->assign('mail_for_merchant',1);
				//code end

				// Translate descriptions to admin language

				fn_translate_products($order_info['items'], '', fn_get_company_language($company_id), true);

				Registry::get('view_mail')->assign('payment_method', fn_get_payment_data($order_info['payment_method']['payment_id'], $order_info['order_id'], fn_get_company_language($company_id)));

				Registry::get('view_mail')->assign('order_info', $order_info);

				Registry::get('view_mail')->assign('order_status', fn_get_status_data($order_info['status'], STATUSES_ORDER, $order_info['order_id'], $company['lang_code']));

				Registry::get('view_mail')->assign('profile_fields', fn_get_profile_fields('I', '', $company['lang_code']));

				/*Modified by chandan*/

				/*db_query("INSERT INTO  clues_email_queue (order_id, from_email, to_email, subject, message) values('".$order_info['order_id']."','".Registry::get('settings.Company.company_orders_department')."','".$company['company_orders_department']."','".addslashes(Registry::get('view_mail')->display('orders/order_notification_subj.tpl', false))."','".addslashes(Registry::get('view_mail')->display('orders/order_notification.tpl', false))."')");	*/			

				fn_send_mail($company['company_orders_department'], Registry::get('settings.Company.company_orders_department'), 'orders/order_notification_subj.tpl', 'orders/order_notification.tpl', '', $company['lang_code'], $order_info['email']);

				/*Modified by chandan*/

			}

		}



		// Notify order department

		if ($notify_department == true) {

			Registry::get('view_mail')->assign('manifest', fn_get_manifest('customer', Registry::get('settings.Appearance.admin_default_language'), $company_id));

			Registry::get('view_mail')->assign('company_placement_info', fn_get_company_placement_info($company_id, Registry::get('settings.Appearance.admin_default_language')));

			// Translate descriptions to admin language

			fn_translate_products($order_info['items'], '', Registry::get('settings.Appearance.admin_default_language'), true);

			Registry::get('view_mail')->assign('payment_method', fn_get_payment_data($order_info['payment_method']['payment_id'], $order_info['order_id'], Registry::get('settings.Appearance.admin_default_language')));

			Registry::get('view_mail')->assign('order_status', fn_get_status_data($order_info['status'], STATUSES_ORDER, $order_info['order_id'], Registry::get('settings.Appearance.admin_default_language')));

			Registry::get('view_mail')->assign('order_info', $order_info);

			Registry::get('view_mail')->assign('profile_fields', fn_get_profile_fields('I', '', Registry::get('settings.Appearance.admin_default_language')));

			/*Modified by chandan*/

			/*db_query("INSERT INTO  clues_email_queue (order_id, from_email, to_email, subject, message) values('".$order_info['order_id']."','".Registry::get('settings.Company.company_orders_department')."','".Registry::get('settings.Company.company_orders_department')."','".addslashes(Registry::get('view_mail')->display('orders/order_notification_subj.tpl', false))."','".addslashes(Registry::get('view_mail')->display('orders/order_notification.tpl', false))."')");	*/			

			fn_send_mail(Registry::get('settings.Company.company_orders_department'), Registry::get('settings.Company.company_orders_department'), 'orders/order_notification_subj.tpl', 'orders/order_notification.tpl', '', Registry::get('settings.Appearance.admin_default_language'), $order_info['email']);

			/*Modified by chandan*/

		}



	}



	if (!empty($edp_data) && !$notify_user) {

		// Send out download links for EDP with "Immediately" Activation mode



		// TRUE if the EDP download links e-mail has already been sent. Used to avoid sending duplicate e-mails.

		$download_email_sent = false;

		foreach ($edp_data as $edp_item) {

			foreach ($edp_item['files'] as $file) {

				if (!empty($file['activation']) && $file['activation'] == 'I' && !$download_email_sent) {

					Registry::get('view_mail')->assign('edp_data', $edp_data);

					Registry::get('view_mail')->assign('order_info', $order_info);

					fn_send_mail($order_info['email'], array('email' => $company['company_orders_department'], 'name' => $company['company_name']), 'orders/edp_access_subj.tpl', 'orders/edp_access.tpl', '', $order_info['lang_code']);

					$download_email_sent = true;

					break;

				}

			}

		}

	}



	fn_set_hook('order_notification', $order_info, $order_statuses, $force_notification);

}



function fn_prepare_package_info(&$cart, &$cart_products)

{

	$package_infos = array();

	

	$groupped_products = array();



	foreach ($cart['products'] as $k => $v) {

		if (!isset($v['company_id'])) {

			// for old saved carts

			$cart['products'][$k]['company_id'] = $v['company_id'] = $cart_products[$k]['company_id'] = 0;

		}

		

		$groupped_products[$v['company_id']][$k] = $cart_products[$k];

		

		if ($cart_products[$k]['free_shipping'] == 'Y' || ($cart_products[$k]['is_edp'] == 'Y' && $cart_products[$k]['edp_shipping'] != 'Y')) {

			$package_infos[$v['company_id']]['has_free_shipping'] = true;

		} else {

			$package_infos[$v['company_id']]['need_shipping'] = true;

		}

	}

	

	foreach ($groupped_products as $_cid => $products) {

		// Leave this code to back compability

		$package_infos[$_cid]['C'] = fn_get_products_cost($cart, $products);

		$package_infos[$_cid]['W'] = fn_get_products_weight($cart, $products);

		$package_infos[$_cid]['I'] = fn_get_products_amount($cart, $products);

		

		$package_infos[$_cid]['packages'] = fn_get_products_packages($cart, $products);

		

		if (empty($package_infos[$_cid]['origination'])) {

			if (!$_cid) {

				$package_infos[$_cid]['origination'] = array(

					'name' => Registry::get('settings.Company.company_name'),

					'address' => Registry::get('settings.Company.company_address'),

					'city' => Registry::get('settings.Company.company_city'),

					'country' => Registry::get('settings.Company.company_country'),

					'state' => Registry::get('settings.Company.company_state'),

					'zipcode' => Registry::get('settings.Company.company_zipcode'),

					'phone' => Registry::get('settings.Company.company_phone'),

					'fax' => Registry::get('settings.Company.company_fax'),

				);

			} else {

				$supplier_data = fn_get_company_data($_cid);

				$package_infos[$_cid]['origination'] = array(

					'name' => !empty($supplier_data['company']) ? $supplier_data['company'] : '',

					'phone' => !empty($supplier_data['phone']) ? $supplier_data['phone'] : '',

					'fax' => !empty($supplier_data['fax']) ? $supplier_data['fax'] : '',

					'country' => !empty($supplier_data['country']) ? $supplier_data['country'] : '',

					'state' => !empty($supplier_data['state']) ? $supplier_data['state'] : '',

					'zipcode' => !empty($supplier_data['zipcode']) ? $supplier_data['zipcode'] : '',

					'city' => !empty($supplier_data['city']) ? $supplier_data['city'] : '',

					'address' => !empty($supplier_data['address']) ? $supplier_data['address'] : '',

				);

			}

		}

		

		if (!empty($package_infos[$_cid]['need_shipping'])) {

			unset($package_infos[$_cid]['has_free_shipping'], $package_infos[$_cid]['need_shipping']);

		}

	}	

	

	fn_set_hook('prepare_package_info', $cart, $cart_products, $package_infos);

	

	return $package_infos;

}



/**

 *

 * @param int $payment_id payment ID

 * @param string $action action 

  * @return array (boolean, string) 

 */

function fn_check_processor_script($payment_id, $action, $additional_params = false)

{

	

	if ($additional_params) {

		if ($action == 'save' || (!empty($_REQUEST['skip_payment']) && AREA == 'C')){

			return array(false, '');

		}

	}	

	

	$payment = fn_get_payment_method_data((int)$payment_id);



	if (!empty($payment['processor_id'])) {

		$processor_data = fn_get_processor_data($payment['payment_id']);

		if (!empty($processor_data['processor_script']) && file_exists(DIR_PAYMENT_FILES . $processor_data['processor_script'])) {

			return array(true, $processor_data);

		}

	}



	return array(false, '');

}



function fn_add_product_options_files($product_data, &$cart, &$auth, $update = false, $location = 'cart')

{

	// Check if products have cusom images

	if (!$update) {

		$uploaded_data = fn_filter_uploaded_data('product_data');

	} else {

		$uploaded_data = fn_filter_uploaded_data('cart_products');

	}

	

	$dir_path = DIR_CUSTOM_FILES . 'sess_data';

	

	// Check for the already uploaded files

	if (!empty($product_data['custom_files']['uploaded'])) {

		foreach ($product_data['custom_files']['uploaded'] as $file_id => $file_data) {

			if (file_exists($dir_path . '/' . basename($file_data['path']))) {

				$id = $file_data['product_id'] . $file_data['option_id'] . $file_id;

				$uploaded_data[$id] = array(

					'name' => $file_data['name'],

					'path' => $dir_path . '/' . basename($file_data['path']),

				);

				

				$product_data['custom_files'][$id] = $file_data['product_id'] . '_' . $file_data['option_id'];

			}

		}

	}

	

	if (!empty($uploaded_data) && !empty($product_data['custom_files'])) {

		$files_data = array();

		

		foreach ($uploaded_data as $key => $file) {

			$file_info = pathinfo($file['name']);

			$file['extension'] = empty($file_info['extension']) ? '' : $file_info['extension'];

			

			$file_info = getimagesize($file['path']);

			$file['type'] = $file_info['mime'];

			$file['is_image'] = fn_get_image_extension($file_info['mime']);

			

			$_data = explode('_', $product_data['custom_files'][$key]);

			$product_id = empty($_data[0]) ? 0 : $_data[0];

			$option_id = empty($_data[1]) ? 0 : $_data[1];

			$file_id = str_replace($option_id . $product_id, '', $key);

			

			if (empty($file_id)) {

				$files_data[$product_id][$option_id][] = $file;

			} else {

				$files_data[$product_id][$option_id][$file_id] = $file;

			}

		}

		

		if (!is_dir($dir_path)) {

			if (!fn_mkdir($dir_path)) {

				// Unable to create a directory

				fn_set_notification('E', fn_get_lang_var('error'), str_replace('[directory]', DIR_CUSTOM_FILES, fn_get_lang_var('text_cannot_write_directory')));

			}

		}

	}



	unset($product_data['custom_files']);



	foreach ($product_data as $key => $data) {

		$product_id = (!empty($data['product_id'])) ? $data['product_id'] : $key;

		

		// Check if product has cusom images

		if ($update || isset($files_data[$key])) {

			$hash = $key;

		} else {

			$hash = $product_id;

		}

		

		if (!empty($files_data[$hash]) && is_array($files_data[$hash])) {

			$_options = fn_get_product_options($product_id);

			

			foreach ($files_data[$hash] as $option_id => $files) {

				foreach ($files as $file_id => $file) {

					// Check for the allowed extensions

					if (!empty($_options[$option_id]['allowed_extensions'])) {

						if ((empty($file['extension']) && !empty($_options[$option_id]['allowed_extensions'])) || !preg_match("/\b" . $file['extension'] . "\b/i", $_options[$option_id]['allowed_extensions'])) {

							$message = fn_get_lang_var('text_forbidden_uploaded_file_extension');

							$message = str_replace('[ext]', $file['extension'], $message);

							$message = str_replace('[exts]', $_options[$option_id]['allowed_extensions'], $message);

							

							fn_set_notification('E', fn_get_lang_var('error'), $file['name'] . ': ' . $message);

							unset($files_data[$hash][$option_id][$file_id]);

							continue;

						}

					}

					

					// Check for the max file size

					

					if (!empty($_options[$option_id]['max_file_size'])) {

						if (empty($file['size'])) {

							$file['size'] = filesize($file['path']);

						}

						

						if ($file['size'] > $_options[$option_id]['max_file_size'] * 1024) {

							fn_set_notification('E', fn_get_lang_var('error'), str_replace('[size]', $_options[$option_id]['max_file_size'] . ' kb', $file['name'] . ': ' . fn_get_lang_var('text_forbidden_uploaded_file_size')));

							unset($files_data[$hash][$option_id][$file_id]);

							continue;

						}

					}

					

					$_file_path = tempnam($dir_path, 'file_');

					if (!fn_copy($file['path'], $_file_path)) {

						fn_set_notification('E', fn_get_lang_var('error'), str_replace('[file]', $file['name'], fn_get_lang_var('text_cannot_create_file')));

						

						unset($files_data[$hash][$option_id][$file_id]);

						continue;

					}

					

					$file['path'] = $_file_path;

					$file['file'] = basename($file['path']);

					

					if ($file['is_image']) {

						$file['thumbnail'] = 'image.custom_image&image=' . $file['file'] . '&type=T';

						$file['detailed'] = 'image.custom_image&image=' . $file['file'] . '&type=D';

					}

					

					$file['location'] = $location;

//////// Added By Sudhir dt 06 June 2012

// connect to FTP server (port 21)
$conn_id = ftp_connect(Registry::get('config.ftp_host'), 21);

// send access parameters
ftp_login($conn_id, Registry::get('config.ftp_user'), Registry::get('config.ftp_pwd'));

// turn on passive mode transfers (some servers need this)
ftp_pasv ($conn_id, true);

// perform file upload

// file to upload:
$local_file = $_file_path;
$ftp_path = Registry::get('config.ftp_path').$file['file'];

$upload = ftp_put($conn_id, $ftp_path, $local_file, FTP_BINARY);

ftp_chmod($conn_id, 0666, $ftp_path);
// close the connection
ftp_close($conn_id);
////////////////////// Code added by Sudhir end here
					

					if ($update) {

						$cart['products'][$key]['extra']['custom_files'][$option_id][] = $file;

					} else {

						$data['extra']['custom_files'][$option_id][] = $file;

						

					}

				}

				

				if ($update) {

					if (!empty($cart['products'][$key]['product_options'][$option_id])) {

						$cart['products'][$key]['product_options'][$option_id] = md5(serialize($cart['products'][$key]['extra']['custom_files'][$option_id]));

					}

				} else {

					if (!empty($data['extra']['custom_files'][$option_id])) {

						$data['product_options'][$option_id] = md5(serialize($data['extra']['custom_files'][$option_id]));

					}

				}

			}

			

			// Check the required options

			if (empty($data['extra']['parent'])) {

				foreach ($_options as $option) {

					if ($option['option_type'] == 'F' && $option['required'] == 'Y' && !$update) {

						if (empty($data['product_options'][$option['option_id']])) {

							fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('product_cannot_be_added'));

							

							unset($product_data[$key]);

							return array($product_data, $cart);

						}

					}

				}

			}

			

		} else {

			if (empty($data['extra']['parent'])) {

				$_options = fn_get_product_options($product_id);

				

				foreach ($_options as $option) {

					if ($option['option_type'] == 'F' && $option['required'] == 'Y' && empty($cart['products'][$hash]['extra']['custom_files'][$option['option_id']]) && empty($data['extra']['custom_files'][$option['option_id']])) {

						fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('product_cannot_be_added'));

						

						unset($product_data[$key]);

						return array($product_data, $cart);

					}

				}

			}

		}

		

		if (isset($cart['products'][$key]['extra']['custom_files'])) {

			foreach ($cart['products'][$key]['extra']['custom_files'] as $option_id => $files) {

				foreach ($files as $file) {

					$data['extra']['custom_files'][$option_id][] = $file;

				}

				

				$data['product_options'][$option_id] = md5(serialize($files));

			}

		}

		

		$product_data[$key] = $data;

	}

	

	return array($product_data, $cart);

}



/**

 *   save stored taxes for products

 * @param array $cart cart

 * @param int $update_id   key of $cart['products'] to be updated

 * @param int $new_id  new key

 * @param bool $consider_existing  whether consider or not existing key

 */

function fn_update_stored_cart_taxes(&$cart, $update_id, $new_id, $consider_existing = false)

{

	if (!empty($cart['taxes']) && is_array($cart['taxes'])) {

		foreach ($cart['taxes'] as $t_id => $s_tax) {

			if (!empty($s_tax['applies']) && is_array($s_tax['applies'])) {

				$compare_key = 'P_' . $update_id;

				$new_key = 'P_' . $new_id;

				if (array_key_exists($compare_key, $s_tax['applies'])) {

					$cart['taxes'][$t_id]['applies'][$new_key] = (isset($s_tax['applies'][$new_key]) && $consider_existing ? $s_tax['applies'][$new_key] : 0) + $s_tax['applies'][$compare_key];

					unset($cart['taxes'][$t_id]['applies'][$compare_key]);

				}

			}

		}

	}

}



function fn_define_original_amount($product_id, $cart_id, &$product, $prev_product)

{

	if (!empty($prev_product['original_product_data']) && !empty($prev_product['original_product_data']['amount'])) {

		$tracking = db_get_field("SELECT tracking FROM ?:products WHERE product_id = ?i", $product_id);

		if ($tracking != 'O' || $tracking == 'O' && $prev_product['original_product_data']['cart_id'] == $cart_id) {

			$product['original_amount'] = $prev_product['original_product_data']['amount'];

		}

		$product['original_product_data'] = $prev_product['original_product_data'];

	} elseif (!empty($prev_product['original_amount'])) {

		$product['original_amount'] = $prev_product['original_amount'];

	}

}



function fn_get_shipments_info($params, $items_per_page = SHIPMENTS_PER_PAGE)

{

	// Init view params

	$params = fn_init_view('shipments', $params);

	

	// Set default values to input params

	$params['page'] = empty($params['page']) ? 1 : $params['page']; // default page is 1

	

	$fields_list = array(

		'?:shipments.shipment_id',

		'?:shipments.timestamp AS shipment_timestamp',

		'?:shipments.comments',

		'?:shipment_items.order_id',

		'?:orders.timestamp AS order_timestamp',

		'?:orders.s_firstname',

		'?:orders.s_lastname',

	);

	

	$joins = array(

		'LEFT JOIN ?:shipment_items ON (?:shipments.shipment_id = ?:shipment_items.shipment_id)',

		'LEFT JOIN ?:orders ON (?:shipment_items.order_id = ?:orders.order_id)',

	);



	$condition = '';

	if (PRODUCT_TYPE == 'MULTIVENDOR' && defined('COMPANY_ID') && COMPANY_ID) {

		$joins[] = 'LEFT JOIN ?:companies ON (?:companies.company_id = ?:orders.company_id)';

		$condition = db_quote(' AND ?:companies.company_id = ?i', COMPANY_ID);

	}

	

	$group = array(

		'?:shipments.shipment_id',

	);

	

	// Define sort fields

	$sortings = array (

		'id' => "?:shipments.shipment_id",

		'order_id' => "?:orders.order_id",

		'shipment_date' => "?:shipments.timestamp",

		'order_date' => "?:orders.timestamp",

		'customer' => array("?:orders.s_lastname", "?:orders.s_firstname"),

	);



	$directions = array (

		'asc' => 'asc',

		'desc' => 'desc'

	);



	if (empty($params['sort_order']) || empty($directions[$params['sort_order']])) {

		$params['sort_order'] = 'desc';

	}



	if (empty($params['sort_by']) || empty($sortings[$params['sort_by']])) {

		$params['sort_by'] = 'id';

	}



	$sorting = (is_array($sortings[$params['sort_by']]) ? implode(' ' . $directions[$params['sort_order']] . ', ', $sortings[$params['sort_by']]) : $sortings[$params['sort_by']]) . " " . $directions[$params['sort_order']];



	// Reverse sorting (for usage in view)

	$params['sort_order'] = $params['sort_order'] == 'asc' ? 'desc' : 'asc';

	

	if (isset($params['advanced_info']) && $params['advanced_info']) {

		$fields_list[] = '?:shipping_descriptions.shipping AS shipping';

		$fields_list[] = '?:shipments.tracking_number';

		$fields_list[] = '?:shipments.carrier';

		

		$joins[] = ' LEFT JOIN ?:shippings ON (?:shipments.shipping_id = ?:shippings.shipping_id)';

		$joins[] = ' LEFT JOIN ?:shipping_descriptions ON (?:shippings.shipping_id = ?:shipping_descriptions.shipping_id)';

		

		$condition .= db_quote(' AND ?:shipping_descriptions.lang_code = ?s', DESCR_SL);

	}

	

	if (!empty($params['order_id'])) {

		$condition .= db_quote(' AND ?:shipment_items.order_id = ?i', $params['order_id']);

	}

	

	if (!empty($params['shipment_id'])) {

		$condition .= db_quote(' AND ?:shipments.shipment_id = ?i', $params['shipment_id']);

	}

	

	if (isset($params['cname']) && fn_string_no_empty($params['cname'])) {

		$arr = fn_explode(' ', $params['cname']);

		foreach ($arr as $k => $v) {

			if (!fn_string_no_empty($v)) {

				unset($arr[$k]);

			}

		}

		if (sizeof($arr) == 2) {

			$condition .= db_quote(" AND ?:orders.firstname LIKE ?l AND ?:orders.lastname LIKE ?l", "%".array_shift($arr)."%", "%".array_shift($arr)."%");

		} else {

			$condition .= db_quote(" AND (?:orders.firstname LIKE ?l OR ?:orders.lastname LIKE ?l)", "%".trim($params['cname'])."%", "%".trim($params['cname'])."%");

		}

	}



	if (!empty($params['p_ids']) || !empty($params['product_view_id'])) {

		$arr = (strpos($params['p_ids'], ',') !== false || !is_array($params['p_ids'])) ? explode(',', $params['p_ids']) : $params['p_ids'];



		if (empty($params['product_view_id'])) {

			$condition .= db_quote(" AND ?:shipment_items.product_id IN (?n)", $arr);

		} else {

			$condition .= db_quote(" AND ?:shipment_items.product_id IN (?n)", db_get_fields(fn_get_products(array('view_id' => $params['product_view_id'], 'get_query' => true)), ','));

		}



		$joins[] = "LEFT JOIN ?:order_details ON ?:order_details.order_id = ?:orders.order_id";

	}

	

	if (!empty($params['shipment_period']) && $params['shipment_period'] != 'A') {

		$params['time_from'] = $params['shipment_time_from'];

		$params['time_to'] = $params['shipment_time_to'];

		$params['period'] = $params['shipment_period'];

		

		list($params['shipment_time_from'], $params['shipment_time_to']) = fn_create_periods($params);



		$condition .= db_quote(" AND (?:shipments.timestamp >= ?i AND ?:shipments.timestamp <= ?i)", $params['shipment_time_from'], $params['shipment_time_to']);

	}

	

	if (!empty($params['order_period']) && $params['order_period'] != 'A') {

		$params['time_from'] = $params['order_time_from'];

		$params['time_to'] = $params['order_time_to'];

		$params['period'] = $params['order_period'];

		

		list($params['order_time_from'], $params['order_time_to']) = fn_create_periods($params);



		$condition .= db_quote(" AND (?:orders.timestamp >= ?i AND ?:orders.timestamp <= ?i)", $params['order_time_from'], $params['order_time_to']);

	}

	

	fn_set_hook('get_shipments', $params, $fields_list, $joins, $condition, $group);

	

	$fields_list = implode(', ', $fields_list);

	$joins = implode(' ', $joins);

	$group = implode(', ', $group);

	

	if (!empty($group)) {

		$group = ' GROUP BY ' . $group;

	}

	

	$limit = '';

	if (!empty($items_per_page)) {

		$total = db_get_field("SELECT COUNT(DISTINCT(?:shipments.shipment_id)) FROM ?:shipments $joins WHERE 1 $condition");

		$limit = fn_paginate($params['page'], $total, $items_per_page);

	}

	

	$shipments = db_get_array("SELECT $fields_list FROM ?:shipments $joins WHERE 1 $condition $group ORDER BY $sorting $limit");

	

	if (isset($params['advanced_info']) && $params['advanced_info'] && !empty($shipments)) {

		foreach ($shipments as $id => $shipment) {

			$items = db_get_array('SELECT item_id, amount FROM ?:shipment_items WHERE shipment_id = ?i', $shipment['shipment_id']);

			if (!empty($items)) {

				foreach ($items as $item) {

					$shipments[$id]['items'][$item['item_id']] = $item['amount'];

				}

			}

		}

	}



	fn_view_process_results('shipments_info', $shipments, $params, $items_per_page);



	return array($shipments, $params, $total);

}



function fn_delete_shipping($shipping_id)

{

	db_query("DELETE FROM ?:shipping_rates WHERE shipping_id = ?i", $shipping_id);

	db_query("DELETE FROM ?:shipping_descriptions WHERE shipping_id = ?i", $shipping_id);

	db_query("DELETE FROM ?:shippings WHERE shipping_id = ?i", $shipping_id);

	

	fn_set_hook('delete_shipping', $shipping_id);

}



function fn_purge_undeliverable_products(&$cart)

{

	foreach ((array)$cart['products'] as $k => $v) {

		if (isset($v['shipping_failed']) && $v['shipping_failed']) {

			unset($cart['products'][$k]);

		}

	}

}



function fn_apply_stored_shipping_rates(&$cart, $order_id = 0)

{

	if (!empty($cart['stored_shipping'])) {

		$total_cost = 0;

		foreach ($cart['shipping'] as $sh_id => $method) {

			if (isset($cart['stored_shipping'][$sh_id])) {

				$piece = fn_format_price($cart['stored_shipping'][$sh_id] / count($method['rates']));

				foreach ($method['rates'] as $k => $v) {

					$cart['shipping'][$sh_id]['rates'][$k] = $piece;

					$total_cost += $piece;

				}

				if (($sum = array_sum($cart['shipping'][$sh_id]['rates'])) != $cart['stored_shipping'][$sh_id]) {

					$deviation = $cart['stored_shipping'][$sh_id] - $sum;

					$value = reset($cart['shipping'][$sh_id]['rates']);

					$key = key($cart['shipping'][$sh_id]['rates']);

					$cart['shipping'][$sh_id]['rates'][$key] = $value + $deviation;

					$total_cost += $deviation;

				}

			} else {

				if (!empty($method['rates'])) {

					$total_cost += array_sum($method['rates']);

				}

			}

		}

		if (!empty($order_id)) {

			db_query("UPDATE ?:orders SET shipping_cost = ?i WHERE order_id = ?i", $total_cost, $order_id);

		}

		$cart['shipping_cost'] = $total_cost;

	}

}



function fn_checkout_update_shipping(&$cart, $shipping_ids)

{

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

			$cart['shipping'][$shipping_id] = array();

			if (!empty($_SESSION['shipping_rates'][$shipping_id]['name'])) {

				$cart['shipping'][$shipping_id]['shipping'] = $_SESSION['shipping_rates'][$shipping_id]['name'];

			}

		}



		$cart['shipping'][$shipping_id]['rates'][$k] = $_SESSION['shipping_rates'][$shipping_id]['rates'][$k];

	}

	

	$cart['chosen_shipping'] = $cart['shipping'];



	return true;

}


function calculate_discount_perc($product){
	$discount_perc = round((($product['list_price']-$product['price'])*100)/$product['list_price']);
	return $discount_perc;
}

//code by ankur for sending sms to user when status is change to open,paid,shipped
function fn_send_sms_to_user($order_id,$status_to)
{
	if($status_to=='O' or $status_to=='P' or $status_to=='A' or $status_to=='Q' or $status_to=='71' or  $status_to=='92' or $status_to==Registry::get('config.cbd_pending_status'))
	{
		if($status_to=='A')
		{
			$result=db_get_row("select cscart_shipments.tracking_number,cscart_shipments.carrier,cscart_orders.total,cscart_orders.firstname,cscart_orders.b_phone as phone,cscart_orders.payment_id
from cscart_shipments left join cscart_shipment_items on cscart_shipment_items.shipment_id=cscart_shipments.shipment_id left join cscart_orders on
cscart_orders.order_id=cscart_shipment_items.order_id  where cscart_shipment_items.order_id=?i",$order_id);
			
		
			if(!empty($result))
			{
				if($result['phone']!='' and $result['carrier']!='' and $result['tracking_number']!='' )
				{
					$mobile=trim($result['phone']);
					
					if($result['firstname']!='')					
					$cust_name=trim($result['firstname']);
					else
					$cust_name='Customer';
					
					$ord_id=$order_id;
					$carr=str_replace(' ','%20',trim($result['carrier']));
					
					if(strlen(trim($result['tracking_number']))>16)
					$trac_no=substr(trim($result['tracking_number']),0,16);
					else
					$trac_no=trim($result['tracking_number']);
					
					$price=trim($result['total']);
					
					if($result['payment_id']==6)
					{
						$vars=$ord_id.":".$carr.":".$trac_no.":".$price;
                                                $template_id = Registry::get('config.sms_template_id_for_shipped_cod');
						db_query("insert into clues_sms_queue set template_id='".$template_id."',variable='".$vars."',mobile='".$mobile."'");
					}
					else
					{
						$vars=$ord_id.":".$carr.":".$trac_no;
                                                $template_id = Registry::get('config.sms_template_id_for_shipped_not_cod'); 
						db_query("insert into clues_sms_queue set template_id='".$template_id."',variable='".$vars."',mobile='".$mobile."'");
					}
											
				}
				else
				{
					$content="Order Id-".$order_id.":error_type-In changing order status to Shipped:error_msg-Either Phone No. or Tracking No. or Carrier Name is not available:Date-".date('Y-m-d H:i:s');
						log_to_file('sms_error',$content);
						
				}
				
			}
			else
			{
				$content="Order Id-".$order_id.":error_type-In changing order status to Shipped:error_msg-No Shipment Information Available :Date-".date('Y-m-d H:i:s');
						log_to_file('sms_error',$content);
			}
				
  			
		}
		else
		{
			$result=db_get_row("select firstname,payment_id,total,b_phone from cscart_orders where order_id=?i",$order_id);
			if(!empty($result))
			{
				if($result['b_phone']!='')
				{
					$mobile=trim($result['b_phone']);
					
					if($status_to=='O' and $result['payment_id']==6)
					{
                                                $template_id = Registry::get('config.sms_template_id_for_open_cod'); 
						$sql="insert into clues_sms_queue set template_id='".$template_id."',variable='".$order_id."',mobile='".$mobile."'";
                                                
					}elseif($status_to=='P' && $result['payment_id'] == Registry::get('config.suvidha_payment_id')){
                                            
                                                $template_id = Registry::get('config.sms_template_id_for_cbd_paid_status');
                                                $url = Registry::get('config.suvidha_url');
                                                $variable = $order_id.':'.$result['total'].':'.$url;
						$sql="insert into clues_sms_queue set template_id='".$template_id."',variable='".$variable."',mobile='".$mobile."'";
                                        }
					else if($status_to=='P')
					{
                                                $template_id = Registry::get('config.sms_template_id_for_paid');
						$sql="insert into clues_sms_queue set template_id='".$template_id."',variable='".$order_id."',mobile='".$mobile."'";
					}
					else if($status_to=='Q' || $status_to=='92')
					{
                                                $template_id = Registry::get('config.sms_template_id_for_cod_confirmed');
						$sql="insert into clues_sms_queue set template_id='".$template_id."',variable='".$order_id."',mobile='".$mobile."'";
					}
					else if($status_to=='71')
					{
                                                $template_id = Registry::get('config.sms_template_id_for_71');
						$sql="insert into clues_sms_queue set template_id='".$template_id."',variable='".$order_id."',mobile='".$mobile."'";
                                                
					}elseif($status_to==Registry::get('config.cbd_pending_status')){
                                            
                                                $template_id = Registry::get('config.sms_template_id_for_cbd_pending_status');
						$sql="insert into clues_sms_queue set template_id='".$template_id."',variable='".$order_id."',mobile='".$mobile."'";
                                                
                                        }
					if($sql!='')
					{
						db_query($sql);
					}
					
					
				}
				else
				{
					$content="Order Id-".$order_id.":error_type-In changing order status to ".$status_to.":error_msg-Phone No.is not available:Date-".date('Y-m-d H:i:s');
						log_to_file('sms_error',$content);
				}
			}
			else
			{
				$content="Order Id-".$order_id.":error_type-In changing order status to ".$status_to.":error_msg-No Information Available :Date-".date('Y-m-d H:i:s');
						log_to_file('sms_error',$content);
			}
		
		}
	}
		
}
//by ankur to get merchant's order status
function fn_get_merchant_status_array($id)
{
	$mer_status=Registry::get('config.merchant_status');
	$arr=explode(',',$mer_status);
	return $arr;

}

function fn_check_status_permission($user_group_ids, $status_from, $status_to){
	$user_groups = "'".implode("','",$user_group_ids)."'";
	if(!empty($user_groups)){
		$sql = "Select from_status, to_status, type, user_type from clues_status_transition where type = 'O' and from_status = '".$status_from."' and to_status = '".$status_to."' and user_group_id in (".$user_groups.")";
		$result = db_get_row($sql);
	}else{
		$result = array();	
	}
	return $result;
}

function fn_get_allowed_destination_status($user_group_ids){
	$user_groups = "'".implode("','",$user_group_ids)."'";
	if(!empty($user_groups)){
		$sql = "SELECT a.status, b.description 
			FROM cscart_statuses as a 
			LEFT JOIN cscart_status_descriptions as b ON b.status = a.status AND b.type = a.type 
			INNER JOIN clues_status_transition as c ON c.to_status = a.`status` and c.`type`='O' and c.user_group_id in (".$user_groups.")
			WHERE a.type = 'O'";
		$result = db_get_hash_single_array($sql,array('status','description'));
		//echo '<pre>';print_r($result);die;
	}else{
		$result = array();	
	}
	return $result;	
}
function fn_start_refund_request($order_info){
    
	$payment_id = $order_info['payment_id'];
        $order_id = $order_info['order_id'];
	$check_for_refund_request_sql = "select id from clues_refunds where status in ('A','R','H','P') and order_id=$order_id";
	$is_exist_refund_request = db_get_array($check_for_refund_request_sql);
	if(empty($is_exist_refund_request)){
		if($payment_id == "6"){
		    $other_refund = '0';
		    $pgw_refund = 0;
		}else{
		    $pgw_refund = ($order_info['total']-$order_info['emi_fee']);
		    $other_refund    = 0;
		}
		$gc_refund = $order_info['gc_used'];
		$cb_refund = $order_info['cb_used'];
		$req_order_status = $order_info['status'];
		$status = 'R';
		$total_refund = $other_refund + $pgw_refund + $gc_refund + $cb_refund;
		$pgw_order_id = ($order_info['parent_order_id'] != '0') ? $order_info['parent_order_id'] : $order_info['order_id'];
		
		
		$sql = "Insert into clues_refunds (order_id, payment_id, pgw_refund, other_refund, gc_refund, cb_refund, total_refund, user_id, timestamp, status, req_order_status,pgw_order_id) values ('".$order_id."','".$payment_id."','".$pgw_refund."','".$other_refund."','".$gc_refund."','".$cb_refund."','".$total_refund."','".$_SESSION['auth']['user_id']."','".TIME."','".$status."','".$req_order_status."','".$pgw_order_id."')";
		$refund_id = db_query($sql);
		fn_refund_logging($order_info);		
		if(isset($order_info['use_gift_certificates'])){
		    foreach($order_info['use_gift_certificates'] as $gc_code=>$gc){
		        $query = "insert into clues_refunds_gc(refund_id, gift_cert_id, gift_cert_code, amount) values ('".$refund_id."','".$gc['gift_cert_id']."','".$gc_code."','".$gc['cost']."')";
		        db_query($query);
		    }
		}
	}else{
		fn_set_notification('W','','your_refund_already_requested');		
	}
}

function eligible_for_cod($cart){
    $cod_allowed = check_for_cod($cart['products']);
    
    if(isset($cart['gift_certificates']) && $cart['gift_certificates'] > 0){
        $cod_allowed = 'NO';
    }
    if($cod_allowed == 'YES'){
        foreach($cart['products'] as $cart_id=>$product){
            $result = get_servicability_type($product['product_id'], $cart['user_data']['s_zipcode']);
            if($result != 3){
                $cod_allowed = 'NO';
                break;
            }
        }
    }
    if(isset($cart['new_cart']) && isset($cart['multiple_shipping_addresses']) && $cart['multiple_shipping_addresses'] == true){
            $cod_allowed = 'NO';
    }

    if($cod_allowed == 'YES'){
        $fail_percent = db_get_field("select failed_percentage from clues_pgw_health order by timestamp desc limit 0,1");
        
        if($fail_percent > Registry::get('config.fail_percent_threshold')){           
            if($cart['total'] < Registry::get('config.fail_percent_max_amount_threshold') && $cart['total'] > Registry::get('config.fail_percent_min_amount_threshold')){
                return 1;
            }else{
                if($cart['total']==0 || $cart['total']=='' || !isset($cart['total'])){
                    return 1;
                }
                return 0;
            }
        }else{
            return 0;
        }
    }else{
        return 0;
    }
}
function check_for_cod_eligible($order_id){
    $sql = "select order_id, user_id, cod_eligible, email, timestamp from cscart_orders where order_id = '".$order_id."'";
    $res = db_get_row($sql);
    $res['key'] = md5($res['order_id'].$res['email'].$res['timestamp'].$res['user_id']);
    //echo '<pre>';print_r($res);
    return $res;
}

function place_order_on_cod($order_id,$user_id,$key){
    $sql = "select order_id, user_id, cod_eligible, email, timestamp, is_parent_order from cscart_orders where order_id = '".$order_id."' and user_id = '".$user_id."'";
    $result = db_get_row($sql);
    if(!empty($result)){
        $secure_key = md5($result['order_id'].$result['email'].$result['timestamp'].$result['user_id']);
        if($key == $secure_key){
            //print_r($result);
            $time_gap = round((TIME - $result['timestamp'])/60, 1);
            if($time_gap <= Registry::get('config.fail_on_cod_threshold_minutes')){
                $sql = "update cscart_orders set payment_id = '6', payment_option_id = '61' where order_id='".$order_id."'";
                db_query($sql);
				//not useful. type=w for order_data is always inserted. grant of cb on cod is controlled from admin	
                //$sql = "delete from cscart_order_data where type='W' and order_id='".$order_id."'";
                //db_query($sql);
                if ($result['is_parent_order'] == 'Y') {	
                    $child_ids = db_get_fields("SELECT order_id FROM ?:orders WHERE parent_order_id = ?i", $order_id);	
                    $res = true;	
                    foreach ($child_ids as $child_order_id) {	
                        $sql = "update cscart_orders set payment_id = '6', payment_option_id = '61' where order_id='".$child_order_id."'";
                        db_query($sql);    
                        
						//not useful. type=w for order_data is always inserted. grant of cb on cod is controlled from admin	
						//$sql = "delete from cscart_order_data where type='W' and order_id='".$child_order_id."'";
                        //db_query($sql);
                        $_res = fn_change_order_status($child_order_id, '93', '', true);
                    }	
                    $res = $res && $_res;	
                    return $res;	
		}elseif(fn_change_order_status($order_id, '93', '', true)){
                    return true;
                }else{
                    fn_set_notification('E', '', fn_get_lang_var('technical_issue_with_the_order'));
                    return false;
                }
            }else{
                fn_set_notification('E', '', fn_get_lang_var('exceed_grace_time'));
                return false;
            }
        }else{
            fn_set_notification('E', '', fn_get_lang_var('secure_key_match_fail'));
            return false;
        }
    }else{
        fn_set_notification('E', '', fn_get_lang_var('no_order_found_with_this_orderid'));
        return false;
    }
}
// START - Code changed by munish on 25 sep 2013
function fn_is_eproduct($product_ids)
{
    $query = "SELECT product_id,is_edp FROM cscart_products WHERE product_id IN (".implode(',', $product_ids).")";
    $edp = db_get_array($query);
    foreach($edp as $val)
    {
        if($val['is_edp'] == 'Y')
        {
            $x = array_search($val['product_id'],$product_ids);
            unset($product_ids[$x]);
        }
    }
    return $product_ids;
}
function fn_get_shipping_time($userpincode ,$product_ids,$amount , $courier )
{ 
  $product_ids = fn_is_eproduct($product_ids);
  if(!empty($product_ids))
  { 
    $config = Registry::get('config.pdd_edd_configs');
    $seller_data = fn_get_product_seller_and_handling_time($product_ids);
    $seller_weight_data = fn_get_product_weight_normal_or_volumetric($product_ids,$amount);
    if($seller_weight_data >= Registry::get('config.pdd_weigth'))
      $delivery = 'surface';
    else
      $delivery = 'air';
    $tier1 = fn_get_tier_info($userpincode);
    $sla_info_mat = array();
    $sla_info_mat['seller_id'] = $seller_data['seller_id']; 
    $sla_info_mat = array_merge($sla_info_mat,fn_get_seller_pincode($seller_data['seller_id']));
    $sla_info_mat['location'] = fn_get_tier_info($sla_info_mat['pincode']);
    $tmp_trasition_info = array();
    $tmp_trasition_info = fn_get_transit_time($tier1,$sla_info_mat['location'],$delivery);
    if($tmp_trasition_info[0] > 0)
      $sla_info_mat['shipping_time']['transit_time'] = $tmp_trasition_info[0];
    else
      $sla_info_mat['shipping_time']['transit_time'] = 432000;
    $sla_info_mat['shipping_time']['transit_id'] = $tmp_trasition_info[1];
    $product_deal_info = fn_get_stock_inventory_on_product($product_ids);
    if($product_deal_info)
    {
      $sla_info_mat['shipping_time']['pickup_time_min'] = 0;
      $sla_info_mat['shipping_time']['cutoff_time'] = 0;
    }
    else
    {
      $sla_info_mat['shipping_time']['pickup_time_min'] = $seller_data['pickup_time_min'];
      $sla_info_mat['shipping_time']['cutoff_time'] = fn_implement_cut_off_time($config['cutoff'][$sla_info_mat['seller_type']],$seller_data['handling_time'],$sla_info_mat['seller_type']);
    }
    $sla_info_mat['shipping_time']['pickup_time_max'] = $seller_data['pickup_time_max'];
    $sla_info_mat['shipping_time']['grace'] = $config['grace'][$sla_info_mat['seller_type']];

    // get deal running on product. get to know function which returns deal type.
    // get if deal is exempted from pickuptime.
    if($sla_info_mat['seller_type'] == 1)
      $sla_info_mat['shipping_time']['handling_time'] = $seller_data['handling_time']/2;
    else
      $sla_info_mat['shipping_time']['handling_time'] = $seller_data['handling_time'];
    $sla_info_mat['shipping_time']['holidays_min'] = fn_get_holidays($sla_info_mat['shipping_time']['transit_time'] + $sla_info_mat['shipping_time']['pickup_time_min'] + $sla_info_mat['shipping_time']['handling_time'] + $sla_info_mat['shipping_time']['grace'] + $sla_info_mat['shipping_time']['cutoff_time']);
    $sla_info_mat['shipping_time']['holidays_max'] = fn_get_holidays($sla_info_mat['shipping_time']['transit_time'] + $sla_info_mat['shipping_time']['pickup_time_max'] + $sla_info_mat['shipping_time']['handling_time'] + $sla_info_mat['shipping_time']['grace'] + $sla_info_mat['shipping_time']['cutoff_time']);
   // $sla_info_mat['shipping_time']['holidays'] = fn_get_holidays($sla_info_mat['shipping_time'],$sla_info_mat['seller_type']);
    $sla_info_mat['shipping_time']['total_shipping_time'] = ($sla_info_mat['shipping_time']['pickup_time_min']+$sla_info_mat['shipping_time']['grace']+$sla_info_mat['shipping_time']['transit_time']+$sla_info_mat['shipping_time']['cutoff_time']+ $sla_info_mat['shipping_time']['handling_time']+$sla_info_mat['shipping_time']['holidays_min']);
    $sla_info_mat['shipping_time']['total_shipping_time_max'] = ($sla_info_mat['shipping_time']['pickup_time_max']+$sla_info_mat['shipping_time']['grace']+$sla_info_mat['shipping_time']['transit_time']+$sla_info_mat['shipping_time']['cutoff_time']+ $sla_info_mat['shipping_time']['handling_time']+$sla_info_mat['shipping_time']['holidays_max']);
  }
  else
    {
        $sla_info_mat = array();
        $sla_info_mat['seller_id'] = 0;
        $sla_info_mat['location']= 0;
        $sla_info_mat['shipping_time']['transit_time'] = Registry::get('config.edp_transit_time');
        $sla_info_mat['shipping_time']['transit_id'] =0;
        $sla_info_mat['shipping_time']['pickup_time_min'] = 0;
        $sla_info_mat['shipping_time']['pickup_time_max'] = 0;
        $sla_info_mat['shipping_time']['cutoff_time'] = 0;
        $sla_info_mat['shipping_time']['grace'] =0;
        $sla_info_mat['shipping_time']['handling_time']= 0;
        $sla_info_mat['shipping_time']['holidays_min'] =0;
        $sla_info_mat['shipping_time']['total_shipping_time'] = Registry::get('config.edp_transit_time');
        $sla_info_mat['shipping_time']['total_shipping_time_max'] = Registry::get('config.edp_transit_time');
    }
  return json_encode($sla_info_mat);
  //get shipping days based on tier info and delivery style info and courier id
  //get list of holidays from holiday table.
  //get seller type premium,basic,3rd type.
  //select respective formula
  //put respective value in formula based on business logic.
  //pickupdays(from config for premium only)+handling time + grace + transition days+ holidays + 2 (5-7 days)
  //return sellerid with zero shipping charges and shipping fees. 
}
function fn_implement_cut_off_time($cut_off_array,$handling_time,$seller_type)
{
  $time = strtotime('now');
  $today_holiday_check = strtotime(date('Y-m-d',$time).' 00:00:00');
  $holiday = db_get_array("SELECT day from clues_courier_holiday_list where day = ".$today_holiday_check." and is_active = 1");
  if(count($holiday) == 1)
    $new_time = strtotime(date('Y-m-d',mktime(0,0,0,date("m",$time),date("d",$time)+1,date("Y",$time))).' 00:00:00');
  else
    $new_time = $time;
  if($seller_type == 1)
  {
    foreach($cut_off_array as $key=>$val)
    {
      if(strtotime(date('Y-m-d',$new_time).' '.str_replace('.',':',$val).':00') > $new_time)
      {
        return (strtotime(date('Y-m-d',$new_time).' '.str_replace('.',':',$val).':00') - (strtotime('now')));
      }
    }
    return (strtotime(date('Y-m-d',mktime(0,0,0,date("m",$new_time),date("d",$new_time)+1,date("Y",$new_time))).' '.str_replace('.',':',$cut_off_array[0]).':00') - (strtotime('now')));
  }
  else
  {
    foreach($cut_off_array as $key=>$val)
    {
      if(strtotime(date('Y-m-d',$new_time).' '.str_replace('.',':',$val).':00') > ($new_time+$handling_time))
      {
        return (strtotime(date('Y-m-d',$new_time).' '.str_replace('.',':',$val).':00') - (strtotime('now')+$handling_time));
      }
    }
    return (strtotime(date('Y-m-d',mktime(0,0,0,date("m",$new_time),date("d",$new_time)+1,date("Y",$new_time))).' '.str_replace('.',':',$cut_off_array[0]).':00') - (strtotime('now')+$handling_time));
  }
}
function fn_get_holidays($ship_time,$time=0)
{
  $count = 0;  
  $holidays=0;
  $flag=0;
  if($time==0)
  {
      $time=strtotime('now');
  }
  $ship_query_time=$ship_time;
  while ($holidays != $holidays+$count || $flag==0)   
    {
        $holiday = db_get_array("SELECT day from clues_courier_holiday_list where day < (".$time."+".$ship_query_time.") 
         and day > ".$time." and is_active = 1");     
        $ship_query_time = $ship_time+ count($holiday)*86400;  
        $count = (count($holiday)*86400)-$holidays;   
        $flag=1;                                                      
        $holidays=$holidays+$count;                            
    }  
    return $holidays;
}
function fn_get_product_seller_and_handling_time($pids)
{
  $product_weight_seller_data = array();
  $product_data = db_get_array("SELECT cp.company_id,cc.handling_time,cse.minimum_pickup_time,cse.maximum_pickup_time 
      from cscart_products as cp 
      inner join cscart_products_categories as cpc on cpc.product_id = cp.product_id 
      inner join cscart_categories cc on cc.category_id = cpc.category_id 
      inner join clues_shipping_estimation as cse on cp.product_shipping_estimation = cse.id
      where cp.product_id IN (". implode(',', $pids).") and cpc.link_type = 'M'");
  $product_seller_data['seller_id'] = $product_data[0]['company_id'];
  $product_seller_data['handling_time'] = 0;
  $product_seller_data['pickup_time_min'] = 0;
  $product_seller_data['pickup_time_max'] = 0;
  foreach($product_data as $value)
  {
      $product_seller_data['handling_time'] = max($product_seller_data['handling_time'],$value['handling_time']);
      $product_seller_data['pickup_time_min'] = max($product_seller_data['pickup_time_min'],$value['minimum_pickup_time']);
      $product_seller_data['pickup_time_max'] = max($product_seller_data['pickup_time_max'],$value['maximum_pickup_time']);
  }
  $product_seller_data['handling_time'] = $product_data[0]['handling_time'];
  return $product_seller_data;
}
/*  if seller is basic then sellers pincode otherwise in case 
    of premium consider warehouse pincode .*/
function fn_get_seller_pincode($seller_id)
{
  $seller_pincode = db_get_array("select cc.fulfillment_id,cwc.center_id,cwc.warehouse_pin,csc.center_pin from cscart_companies as 
  cc inner join clues_warehouse_contact as cwc on cwc.company_id = cc.company_id left join clues_sorting_centers as csc on 
  csc.center_id = cwc.center_id where cc.company_id = $seller_id");
  if($seller_pincode[0]['fulfillment_id'] == 1)
    return array('pincode'=>$seller_pincode[0]['center_pin'],'seller_type'=>$seller_pincode[0]['fulfillment_id']);
  else
    return array('pincode'=>$seller_pincode[0]['warehouse_pin'],'seller_type'=>$seller_pincode[0]['fulfillment_id']);

}
function fn_get_tier_info($pincode)
{
  $product_weight_data = db_get_array("SELECT * from clues_master_pincode_tier_mapping where pincode = ".$pincode);
  return $product_weight_data[0];
}
function fn_get_transit_time($tier2,$tier1,$shipment_type)
{
  // Get courier id from preference.
  // mapping of zones
  //$shipment_type = 'air';
  $product_trasition_sla = array();
  if(substr($tier1['pincode'],0,3) == substr($tier2['pincode'],0,3) || ($tier1['group_id'] > 0 && $tier2['group_id'] > 0 && $tier1['group_id'] == $tier2['group_id']))
  {
    $column_name = 'sla_same_city';
    $shipment_type = 'surface'; // with in city always surface.
    $transition_code = 'WITH_IN_CITY_'.strtoupper($shipment_type);
  } 
  elseif($tier1['tier'] == 'X' && $tier2['tier'] == 'X')
  {
    $column_name = 'sla_metro';
    $transition_code = 'METRO_TO_METRO_'.strtoupper($shipment_type);
  } 
  else
  {
    $column_name = 'sla';
    if($tier1['zone'] == $tier2['zone'])
      $transition_code = 'WITH_IN_ZONE_'.strtoupper($shipment_type);
    else
      $transition_code = 'REST_OF_INDIA_'.strtoupper($shipment_type);
  }   
  $product_sla_time = db_get_array("SELECT ".$column_name." as sla from clues_courier_transit_sla_matrix where from_zone = '".$tier1['zone']."'
    AND to_zone = '".$tier2['zone']."' AND shipment_type = '".$shipment_type."' AND is_active = 1");
  $product_sla_transition_id = db_get_array("SELECT transition_id from clues_shipment_transition_type where transition_code = '".$transition_code."'
    AND status = 1");
  return array($product_sla_time[0]['sla'],$product_sla_transition_id[0]['transition_id']);

}  
function fn_get_stock_inventory_on_product($pids)
{
  $product_deal_data = db_get_array("select cpt.type,c.in_inventory from cscart_products as c left join 
  cscart_promotions cp on cp.promotion_id = c.promotion_id inner join clues_promotion_type as cpt on 
  cpt.promotion_type_id = cp.promotion_type_id where c.product_id IN (". implode(',', $pids).") and from_date < UNIX_TIMESTAMP() and to_date > UNIX_TIMESTAMP()");
  $config = Registry::get('config.pdd_edd_configs');
  foreach($product_deal_data as $value)
  {
      if((array_key_exists($value['type'],$config['pickup_time_deal']) && $config['pickup_time_deal'][$value['type']] == 1) || $value['in_inventory'] == 'Y')
      {
          $x = 1;
      }
      else
      {
          $x=0;
          break;
      }        
  }
  if($x)
      return true;
  else
      return false;
}
function fn_get_pdd_edd($order_id)
{
    $pdd_query = "SELECT cse.minimum_pickup_time,cse.maximum_pickup_time,cog.promised_delivery_date,cog.cutoff_time,cog.create_date,cog.grace_period,cog.picking_time,cog.handling_time,cog.holidays,cog.transit_time 
        FROM clues_orders_grace as cog 
        inner join cscart_order_details as cod on cog.order_id = cod.order_id
        inner join cscart_products as cp on cp.product_id = cod.product_id
        inner join clues_shipping_estimation as cse on cp.product_shipping_estimation = cse.id
        WHERE cog.order_id=".$order_id." group by cod.order_id having max(cse.minimum_pickup_time)";
    $pdd_data = db_get_row($pdd_query);
    if($pdd_data)
    {
      $orders['f_pdd'] = date('D j M',$pdd_data['promised_delivery_date']);
      $orders['s_pdd'] = date('D j M',$pdd_data['promised_delivery_date']+Registry::get('config.pdd_buffer_time'));
      $estdate = $pdd_data['create_date']+$pdd_data['picking_time']+$pdd_data['handling_time']+$pdd_data['cutoff_time']+$pdd_data['grace_period']+$pdd_data['holidays']+$pdd_data['transit_time'];
      $orders['edd1'] = date('D j M',$estdate);
      $orders['edd2'] = date('D j M',$estdate+$pdd_data['maximum_pickup_time']-$pdd_data['minimum_pickup_time']+fn_get_holidays($pdd_data['maximum_pickup_time']-$pdd_data['minimum_pickup_time'],$estdate));
      return $orders;
      
    }
}
// END - Code changed by munish on 25 sep 2013

// Start - Code changed by munish on 19 Oct 2013
function fn_insert_edd_pdd_of_order_into_db($order_id,$user_id){
    $sql = "SELECT is_parent_order FROM cscart_orders WHERE order_id=".$order_id;
    $is_parent = db_get_field($sql);
    if($is_parent == 'Y')
    {
        $query = "SELECT o.order_id,od.amount , o.s_zipcode , od.product_id FROM cscart_orders as o
                INNER JOIN cscart_order_details as od ON od.order_id = o.order_id
                WHERE o.parent_order_id=".$order_id;
    }
    else
    {
        $query = "SELECT o.order_id,od.amount , o.s_zipcode , od.product_id FROM cscart_orders as o
                INNER JOIN cscart_order_details as od ON od.order_id = o.order_id
                WHERE o.order_id=".$order_id;
    }
    $pdd = array();
    $result = db_get_array($query);
    $orders = array();
    foreach($result as $value)
    {
        if(array_key_exists($value['order_id'], $orders))
        {
            array_push($orders[$value['order_id']]['product_id'],$value['product_id']);
            $orders[$value['order_id']]['amount'][$value['product_id']] = $value['amount'];
        }
        else
        {
            $orders[$value['order_id']]['order_id'] = $value['order_id'];
            $orders[$value['order_id']]['s_zipcode'] = $value['s_zipcode'];
            $orders[$value['order_id']]['product_id'] = array();
            array_push($orders[$value['order_id']]['product_id'],$value['product_id']);
            $orders[$value['order_id']]['amount'][$value['product_id']] = $value['amount'];
        }
    }
    foreach($orders as $order_info)
    {
        $result = json_decode(fn_get_shipping_time($order_info['s_zipcode'],$order_info['product_id'],$order_info['amount']), true);
        $pdd[$order_info['order_id']]['grace'] =  $result['shipping_time']['grace'];
        $pdd[$order_info['order_id']]['edd'] =  $result['shipping_time']['total_shipping_time'];
        $pdd[$order_info['order_id']]['edd1'] =  $result['shipping_time']['total_shipping_time_max'];
        $pdd[$order_info['order_id']]['pickup_time_min'] =  $result['shipping_time']['pickup_time_min'];
        $pdd[$order_info['order_id']]['handling_time'] =  $result['shipping_time']['handling_time'];
        $pdd[$order_info['order_id']]['holidays'] = $result['shipping_time']['holidays_min'];
        $pdd[$order_info['order_id']]['transit_time'] =  $result['shipping_time']['transit_time'];
        $pdd[$order_info['order_id']]['cutoff_time'] =  $result['shipping_time']['cutoff_time'];
        $pdd[$order_info['order_id']]['transit_id'] =  $result['shipping_time']['transit_id'];
    }
    
$query = "INSERT IGNORE INTO clues_orders_grace (order_id,user_id,grace_period,promised_delivery_date,picking_time,handling_time,holidays,transit_time,cutoff_time,transition_id,create_date,last_update) values";
$curdate = strtotime('now');
$complete_pdd = array('min_pdd'=>0,'max_pdd'=>0);
foreach($pdd as $key=>$value){
    $fpdd = $curdate+$value["edd"];
    $fpdd1 = $curdate+$value["edd1"];
    $complete_pdd['min_pdd'] = max($fpdd,$complete_pdd['min_pdd']);
    $complete_pdd['max_pdd'] = max($fpdd1,$complete_pdd['max_pdd']);
    $pick = $value["pickup_time_min"];
    $grace = $value["grace"];
    $handling = $value["handling_time"];
    $holiday = $value["holidays"];
    $transit = $value["transit_time"];
    $cutoff = $value["cutoff_time"];
    $trans_id = $value["transit_id"];
    $query .= "('$key','$user_id','$grace','$fpdd','$pick','$handling','$holiday','$transit','$cutoff','$trans_id','$curdate','$curdate'),"; 
}
$query = rtrim($query, ",");
db_query($query);
return $complete_pdd;
}
// End - Code changed by munish on 19 Oct 2013
//for express checkout
function fn_express_apply_cb(&$cart)
{
    $sql = "select data from cscart_user_data where user_id='".$_SESSION['auth']['user_id']."' and type='W'";
    $user_cb = unserialize(db_get_field($sql));
    
    if($user_cb == '' || $user_cb=='0')
    {unset($cart['points_info']['in_use']);}
    else
    {$cart['points_info']['in_use']['points'] = $user_cb;}

}
function fn_express_apply_promotion(&$cart, &$cart_products, &$no_apply_promotion)
{
    foreach($cart['products'] as $key => $products)
    {
        $coupon_codes[$key] = $products['coupon_code'];
    }
      
    foreach ($coupon_codes as $key => $coupon_code) {
        
        $time = time();
        if($coupon_code!=''){
            $no_apply = FALSE;
            $sql = "select bonuses,conditions,cod_on_mob from cscart_promotions where conditions_hash like '%".$coupon_code."%' and status='A' and to_date>='".$time."' and from_date<='".$time."'";
            $bonuses_condi = db_get_row($sql);
            
            $order_discount = strpos($bonuses_condi['bonuses'],'order_discount');
            $conditions  = unserialize($bonuses_condi['conditions']);

                foreach ($conditions['conditions'] as $condi_key => $value) {
                    if($value['condition'] == 'payment' && $value['operator'] == 'neq' && $value['value'] == '6'){
                        $cart['promo_cod'] = 'N';
                    }elseif($value['condition'] == 'payment' && $value['operator'] == 'eq' && $value['value'] != '6'){
                        $cart['promo_cod'] = 'N';
                    }
                }

            if ($order_discount !== false) {
               $no_apply = TRUE;
            }
            
            if(!$no_apply && (!$no_for_cod && $cart['payment_id']=='6')){
                $cart['pending_coupon'] = $coupon_code;
                $cart['recalculate'] = TRUE;
                $cart['bulk_coupon'] = 'Y';
                fn_promotion_apply('cart', $cart, $auth, $cart_products);
            }else{
               
                    if($cart['products'][$key]['product_id'] == $_SESSION['exp_lst_prd_id']){
                        $no_apply_promotion = $coupon_code;
                    }
                
            }
        }
    }
    unset($cart['bulk_coupon']);
}
function express_check_cod_conditions(&$cart){
            $max_cod_amount = (Registry::get('config.max_cod_amount')) ? Registry::get('config.max_cod_amount') : '0';
            if($max_cod_amount > 0){
               if($cart['total'] > $max_cod_amount )
                   return false;
            }

            if(express_check_cod($cart['products']) == 'NO')
                return false;
            else
                return true;
                 
}
function express_check_cod($products){

            $cod_allowed = 'YES';
            if(count($products) == "0"){
                $cod_allowed = 'NO';
                return  $cod_allowed;
            }
            foreach($products as $product){
                if($product['is_cod'] != 'Y'){
                        $cod_allowed = 'NO';
                }
            }
            return $cod_allowed;
}
//*******************
// Start - code changed by munish on 11-dec-2013

function fn_get_product_weight_normal_or_volumetric($arr_product_ids,$amountarray)
{
    $product_ids = array();
    $pro_arr = array();
    
    $default_weight_for_product = Registry::get('config.default_weight_for_product');
    $default_weight_type_for_product = Registry::get('config.default_weight_type_for_product');
    $default_lwh_for_product = Registry::get('config.default_lwh_for_product');
    
    $sql="select product_id,weight,length,width,height,is_validated,weight_type,UNIX_TIMESTAMP(last_modeified) as last_updated from clues_product_shipment_weight where product_id in (".  implode(',', $arr_product_ids).") order by last_updated DESC";
    $result = db_get_array($sql);
    
    
    for($i=0;$i<count($result);$i++)
        {   
           if($pro_arr[$result[$i]['product_id']])
            {
                if($pro_arr[$result[$i]['product_id']]['is_validated']=='Y'||$pro_arr[$result[$i]['product_id']]['last_updated']>$result[$i]['last_updated'])
                {
                    continue;
                }
                else
                {   
                    $pro_arr[$result[$i]['product_id']]=$result[$i];
                    $pro_arr[$result[$i]['product_id']]['amount'] = $amountarray[$result[$i]['product_id']];
                }
            }
            else
            {
                $pro_arr[$result[$i]['product_id']]=$result[$i];
                $pro_arr[$result[$i]['product_id']]['amount'] = $amountarray[$result[$i]['product_id']];
                array_push($product_ids,$result[$i]['product_id']);
            }
        } // end for loop
   
      $category_product_ids =  array_diff($arr_product_ids, $product_ids);
      
      unset($result);
      //unset($product_ids);
      $product_ids=array();
      if(!empty($category_product_ids))
      {
          $sql="select pc.product_id,c1.weight,c1.weight_type from cscart_categories c1
                inner join cscart_products_categories pc on pc.category_id = c1.category_id
                where c1.category_id not in (select parent_id from cscart_categories) and pc.link_type = 'M' and pc.product_id in (".  implode(',', $category_product_ids).")";
          
          $result=db_get_array($sql);
          
          for($i=0;$i<count($result);$i++)
          {
              $pro_arr[$result[$i]['product_id']]=$result[$i];
             
              if($result[$i]['weight']==0&&$result[$i]['weight_type']!=2)
              {
                  $pro_arr[$result[$i]['product_id']]['weight']=$default_weight_for_product;
              }
              $pro_arr[$result[$i]['product_id']]['length'] = $pro_arr[$result[$i]['product_id']]['width'] = $pro_arr[$result[$i]['product_id']]['height'] = $default_lwh_for_product;
              $pro_arr[$result[$i]['product_id']]['is_validated']='N';
              $pro_arr[$result[$i]['product_id']]['amount'] = $amountarray[$result[$i]['product_id']];
              array_push($product_ids,$result[$i]['product_id']);
          }
          
          $pro_left = array_diff($category_product_ids, $product_ids);
          
          if(!empty($pro_left))     // product_ids which not present product_shipment
          {
              foreach($pro_left as $row)
              {
                  $pro_arr[$row]['product_id']=$row;
                  $pro_arr[$row]['weight'] =  $default_weight_for_product;
                  $pro_arr[$row]['length'] = $pro_arr[$row]['width'] = $pro_arr[$row]['height'] = $default_lwh_for_product;
                  $pro_arr[$row]['is_validated']='N';
                  $pro_arr[$row]['weight_type']=$default_weight_type_for_product;
                  $pro_arr[$row]['amount'] = $amountarray[$row];
              }              
          }
        }
    foreach($pro_arr as $product_id=>$row)
    {
        if($row['weight_type']==3)
        {
            $x = ($row['length']*$row['width']*$row['height'])/5;
            if($row['weight'] > $x)
                {
                    $pro_arr[$product_id]['weight_type'] = 1;
                }
            else
                {
                    $pro_arr[$product_id]['weight_type'] = 2;
                }
        }
    }
    $weight = fn_weight_summation($pro_arr);
      return $weight['weight']; 
}
function fn_weight_summation($weight) {
    $arr = array();
    $shipment_weight=array();
    
    foreach ($weight as $product=>$key)
        array_push($arr, $key['weight_type']);
    sort($arr);
    
    $weight_temp = 0;
    if ($arr[0] != $arr[count($arr)-1]) {

        foreach ($weight as $product=>$row)
        {
            if ($row['weight_type'] == 2)
            {
                $weight_temp+=$row['amount'] * (($row['width'] * $row['height'] * $row['length']) / 5);
            }
        }
        $shipment_weight['weight']=$weight_temp;
        $shipment_weight['weight_type']=2;
    }
    else if ($arr[0] == 1) {

        $weight_temp = 0;
        foreach ($weight as $product=>$row)
        {    
            $weight_temp+=$row['amount'] * $row['weight'];
        }    
        $shipment_weight['weight']=$weight_temp;
        $shipment_weight['weight_type']=1;
    } else if ($arr[0] == 2) {
        $weight_temp = 0;
        foreach ($weight as $product_id=>$row)
        {    
            $weight_temp+=$key['amount'] * (($key['width'] * $key['height'] * $key['length']) / 5);
        }
        $shipment_weight['weight']=$weight_temp;
        $shipment_weight['weight_type']=2;
    }
    
    return $shipment_weight;
}

// End - code changed by munish on 11-dec-2013

function fn_create_retry_cart($order_id,$processing = false){	
	
	unset($_SESSION['cart']);
	unset($_SESSION['shipping_rates']);
	unset($_SESSION['edit_step']);
	unset($_SESSION['product_notifications']);
	if(isset($_SESSION['shipping_hash'])){
		unset($_SESSION['shipping_hash']);	
	}
		
	$products = db_get_array('SELECT product_id, amount,extra FROM cscart_order_details WHERE order_id = '.$order_id);
	
	foreach($products as $k => $v){
		$product_id = $v['product_id'];
		$amount = $v['amount'];
		$options = unserialize($v['extra']);
		$product_data = array();
		$product_data[$product_id]['product_id'] = $product_id;
		$product_data[$product_id]['amount'] = $amount;
		$product_data[$product_id]['product_options'] = $options['product_options'];
		fn_add_product_to_cart($product_data, $cart, $auth = array(), false);
	}
	if($processing == false){
		return $cart;
	}
	
	$payment = db_get_row('SELECT payment_option_id,emi_id from cscart_orders where order_id = '.$order_id);	
	$payment_option_id = $payment['payment_option_id'];
	$emi_id = $payment['emi_id'];
	
	if($emi_id != 0){
		$sql = "select cpoep.id, cpoep.payment_option_id, cpoep.payment_gateway_id, cpoep.period, cpoep.fee, cpoep.promo_fee, cpoep.promo_end_date, cpoep.name, cpoep.status, cpt.name as payment_type,
						cpo.name as payment_option 
						from clues_payment_options_emi_pgw cpoep 
						join clues_payment_options cpo on (cpoep.payment_option_id=cpo.payment_option_id)
						join clues_payment_types cpt on (cpo.payment_type_id=cpt.payment_type_id)						
						join cscart_payments cp on (cpoep.payment_gateway_id=cp.payment_id) 
						where cpoep.payment_option_id='".$payment_option_id."' and cpoep.status = 'A' and cp.status = 'A' and cpoep.id=".$emi_id."";
	}	
	else{
		$sql = "select cpop.payment_option_pgw_id, cpop.payment_option_id, cpop.payment_gateway_id, cpop.priority, cpop.status, cpt.name as payment_type, cpo.name as payment_option from clues_payment_option_pgw cpop join clues_payment_options cpo on (cpop.payment_option_id=cpo.payment_option_id)
						join clues_payment_types cpt on (cpo.payment_type_id=cpt.payment_type_id)
						join cscart_payments cp on (cpop.payment_gateway_id=cp.payment_id)
						where cpop.payment_option_id='".$payment_option_id."' and cpop.status = 'A' and cp.status = 'A' order by priority asc";
	}
	$pgw_avail = db_get_row($sql);
	$payment_id = !empty($pgw_avail['payment_gateway_id']) ? (int) $pgw_avail['payment_gateway_id'] : '-1';
	if($emi_id != 0){
		fn_calculate_cart_content($cart, $auth, 'S', true, 'F', false,true);
		$cart['emi_id'] = $emi_id;	
		if(date('Y-m-d h:i:s') <= $pgw_avail['promo_end_date']) {
			$cart['emi_fee'] = $pgw_avail['promo_fee'];	
		}
		else {
			$cart['emi_fee'] = fn_format_price((($cart['total']-$cart['emi_fee']) * $pgw_avail['fee'])/100);
		}
	}	
	$cart['payment_id'] = $payment_id;
	$cart['payment_option_id'] = $payment_option_id;
	$cart['payment_details'] = $pgw_avail;		
	return $cart;
}

function retry_get_user_data($order_id){
			
		$fields = 'b_title,b_firstname,b_lastname,b_address,b_address_2,b_city,b_state,b_zipcode,b_phone,
							s_title,s_firstname,s_lastname,s_address,s_state,s_address_2,s_city,s_country,s_zipcode,s_phone,user_id ';
		$user_data = db_get_row('SELECT '.$fields.' FROM cscart_orders where order_id = '.$order_id);	
		$profiles = db_get_array('SELECT * from cscart_user_profiles where user_id = '.$user_data['user_id']);
		foreach($profiles as $k => $v){
			unset($profiles[$k]['profile_type']);
			unset($profiles[$k]['b_county']);
			unset($profiles[$k]['b_country']);
			unset($profiles[$k]['s_county']);
			unset($profiles[$k]['s_address_type']);
			unset($profiles[$k]['profile_name']);
			unset($profiles[$k]['credit_cards']);
			unset($profiles[$k]['verified']);
			$profile_id = $profiles[$k]['profile_id'];
			unset($profiles[$k]['profile_id']);
			$diff = array_diff($profiles[$k],$user_data);
			if(empty($diff)){
				$user_data['profile_id'] = $profile_id;
				break;
			}
		}

		$user_data = fn_get_user_info($user_data['user_id'],'true',$user_data['profile_id']);
		fn_fill_address($user_data , fn_get_profile_fields('O'));
		return $user_data;	
}

function fn_get_product_name_options_valid($extra)
{
	$return_data_array = array();
	$options_for_product = array();
	if(!empty($extra))
	{
		$unserialized_data = unserialize($extra);
		$return_data_array['product_name'] = $unserialized_data['product'];
		if(!empty($unserialized_data['product_options_value']))
        {
            foreach($unserialized_data['product_options_value'] as $option_values)
		    {
				$options_for_product[] =  $option_values['option_name'] . ':' . $option_values['variant_name'];
	        }
        }
        $return_data_array['product_options'] = (!empty($options_for_product)) ? implode(",",$options_for_product) : '';
	}
	return $return_data_array;
}

function fn_get_order_data($order_id) {
    $sql = "Select 
                o.order_id,
                concat(o.s_firstname, ' ', o.s_lastname) as customer_name,
                o.email as customer_email,
                o.total as amount,
                o.s_phone as customer_mobile,
                group_concat(REPLACE(REPLACE(REPLACE(REPLACE(cod.product_name,'\'',''),',',''),'\"',''),';','')) as product
            from
                cscart_orders o
                    left join
                cscart_order_details cod ON cod.order_id = o.order_id
            where o.order_id=$order_id
            group by 1";
    $result = db_get_row($sql);
    return $result;
}

function fn_get_order_status($txn_id) {
    $sql = "select 
                o.order_id,
                o.status,
                cpd.direcpayreferenceid as suvidhaa_unique_transaction_id,
                cst.status_group
            from
                clues_prepayment_details cpd
                    join
                cscart_orders o ON o.order_id = cpd.order_id
                    join
                clues_status_types cst ON cst.status = o.status and cst.object_type = 'O'
            where
                cpd.direcpayreferenceid = '$txn_id' and o.payment_id='".Registry::get('config.suvidha_payment_id')."'"; // and o.payment_id='".Registry::get('config.suvidha_payment_id')."'
    $result = db_get_row($sql);
    if (!empty($result)) {
        if (($result['status_group'] == 'O' && $result['status'] == Registry::get('config.cbd_pending_status')) || ($result['status_group'] == 'W')) {
            $res = array('status' => 'PENDING');
        } elseif (($result['status_group'] == 'I' && $result['status'] == 'H') || ($result['status'] == 'C')) {
            $res = array('status' => 'COMPLETE');
        } elseif (in_array($result['status_group'], array('E', 'F', 'N', 'R', 'X'))) {
            $res = array('status' => 'CANCELLED');
        } elseif (($result['status_group'] == 'O' && $result['status'] == 'P') || ($result['status_group'] == 'I')) {
            $res = array('status' => 'PROCESSING');
        } else {
            $res = array('error' => 'ORDER_NOT_FOUND');
        }
    } else {
        $res = array('error' => 'ORDER_NOT_FOUND');
    }
    //fn_print_die($result);
    return $res;
}

function fn_process_cbd_order($params) {
    $sql = "Select 
                o.order_id,
                o.total as amount,
                o.payment_id,
                o.status
            from
                cscart_orders o
            where o.order_id=" . $params['order_id'] . " and o.payment_id='".Registry::get('config.suvidha_payment_id')."'"; // and o.payment_id='".Registry::get('config.suvidha_payment_id')."'
    $result = db_get_row($sql);
    if (!empty($result)) {
        if ($result['amount'] != $params['amount']) {
            $res = array('error' => 'FAIL:AMOUNT_MISMATCH');
        } elseif ($result['status'] != Registry::get('config.cbd_pending_status')) {
            $res = array('error' => 'FAIL:ORDER_ALLREADY_PROCESSED_OR_CANCELLED');
        } else {
			$Amount=$params['amount'];
            db_query("INSERT INTO clues_prepayment_details (direcpayreferenceid, order_id, amount, payment_gateway) values('" . $params['suvidhaa_unique_transaction_id'] . "','" . $params['order_id'] . "','" . $Amount . "','Suvidhaa')");
            if(fn_change_order_status($params['order_id'], 'P')){
                $res = array('STATUS'=>'SUCCESS');
            }else{
                $res = array('STATUS'=>'FAIL');
            }
        }
    } else {
        $res = array('error' => 'ORDER_NOT_FOUND');
    }
    return $res;
}

function fn_check_if_shipping_price_set_for_product($product_id)
{
    if(Registry::get('config.quantity_discount_flag') != 1)
    {
        return false;
    }
    $tp = db_get_row("SELECT tp, if(type='deal_tp',1,2) as tporder FROM clues_product_TP 
            WHERE product_id ='".$product_id."' AND latest=1
            AND start_date <= '".time()."' AND tp != 0 AND end_date >= '".time()."' order by tporder ASC");
    
    if(isset($tp['tp']) && !empty($tp['tp']))
    {
        return false;
    }
  
    $multiple_prices_set = db_get_row("SELECT count(?:product_prices.lower_limit) as cnt,?:product_prices.product_id,MAX(?:product_prices.lower_limit) as lower_limit FROM ?:product_prices WHERE ?:product_prices.product_id = $product_id ORDER BY lower_limit DESC LIMIT 0,1", $product_id);
    
    if(empty($multiple_prices_set) || $multiple_prices_set['lower_limit']<=1 || $multiple_prices_set['cnt'] <=1 )
    {
            return false;
    }
    return true;
}

function fn_caclulate_shipping_for_more_quantity($product_id,$amount)
{
    $product_shipping_charge = 0;
    
    $max_lower_limit = db_get_field("SELECT MAX(lower_limit) FROM ?:product_prices WHERE product_id='".$product_id."'");

    if($max_lower_limit > $amount)
    {
        $product_shipping_charge = db_get_field("SELECT MIN(?:product_prices.shipping_charge) as shipping_charge FROM ?:product_prices WHERE lower_limit <= '".$amount."' AND ?:product_prices.product_id = '".$product_id."' ORDER BY lower_limit DESC LIMIT 1");
    }
    elseif($max_lower_limit <= $amount)
    {
        $product_shipping_charge = db_get_field("SELECT MIN(?:product_prices.shipping_charge) as shipping_charge FROM ?:product_prices WHERE lower_limit = '".$max_lower_limit."' AND ?:product_prices.product_id = '".$product_id."' ORDER BY lower_limit DESC LIMIT 1");
    }
    return $product_shipping_charge;
}
?>