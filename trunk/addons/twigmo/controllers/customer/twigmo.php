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

$format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : TWG_DEFAULT_DATA_FORMAT;
if (!empty($_REQUEST['callback'])) {
	$format = 'jsonp';
}

$api_version = !empty($_REQUEST['api_version']) ? $_REQUEST['api_version'] : TWG_DEFAULT_API_VERSION;
$response = new ApiData($api_version, $format);
// set response callback
if (!empty($_REQUEST['callback'])) {
	$response->setCallback($_REQUEST['callback']);
}

$lang_code = CART_LANGUAGE;
$items_per_page = !empty($_REQUEST['items_per_page']) ? $_REQUEST['items_per_page'] : TWG_RESPONSE_ITEMS_LIMIT;

if (!empty($_REQUEST['language'])) {
	if (in_array($_REQUEST['language'], array_keys(Registry::get('languages')))) {
		$lang_code = $_REQUEST['language'];
	}
}

if (!fn_validate_auth()) {
	$response->addError('ERROR_ACCESS_DENIED', fn_get_lang_var('access_denied', $lang_code));
	$response->returnResponse();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && $mode == 'post') {

	$meta = fn_init_api_meta($response);

	if ($meta['action'] == 'login') {

		$login = !empty($_REQUEST['login']) ? $_REQUEST['login'] : '';
		$password = !empty($_REQUEST['password']) ? $_REQUEST['password'] : '';

		// Support login by email even if it is disabled
		// replace email in login name with the login corresponding to entered email
		// REMOVE AFTER adding login settings to the application
		if ((Registry::get('settings.General.use_email_as_login') != 'Y') && fn_validate_email($login)) {
			$login = db_get_field('SELECT user_login FROM ?:users WHERE email = ?s', $login);
		}

		if (!$user_data = fn_api_customer_login($login, $password)) {
			$response->addError('ERROR_CUSTOMER_LOGIN_FAIL', fn_get_lang_var('customer_login_faild'));
		}

		// compose result data: user data and cart
		$result = array (
			'firstname' => $user_data['firstname'],
			'lastname' => $user_data['lastname'],
			'email' => $user_data['email'],
			'cart' => fn_api_get_session_cart($lang_code),
		);

		$response->setData($result);
		
	} elseif ($meta['action'] == 'logout') {
		fn_api_customer_logout();

	} elseif ($meta['action'] == 'place_order') {

		$data = fn_get_api_data($response, $format);
		$order_id = fn_api_place_order($data, $response);

		if (empty($order_id)) {
			if (!fn_set_internal_errors($response, 'ERROR_FAIL_POST_ORDER')) {
				$response->addError('ERROR_FAIL_POST_ORDER', fn_get_lang_var('fail_post_order', $lang_code));
			}
			$response->returnResponse();
		}

		$order = fn_api_get_order_details($order_id);

		$response->setData($order);

	} elseif ($meta['action'] == 'update') {

		if ($meta['object'] == 'cart') {
			// update cart
			$data = fn_get_api_data($response, $format);

			$cart = & $_SESSION['cart'];
			fn_clear_cart($cart);

			if (!empty($data['products'])) {
				fn_api_add_product_to_cart($data['products'], $cart);
			}

			$result = fn_api_get_session_cart($lang_code);
			$response->setData($result);

		} elseif ($meta['object'] == 'users') {

			$user = fn_get_api_data($response, $format);
			$user = fn_parse_api_object($user, 'users');

			$_auth = & $_SESSION['auth'];

			if (!empty($user['user_id']) && $user['user_id'] != $_auth['user_id']) {
				$response->addError('ERROR_ACCESS_DENIED', fn_get_lang_var('access_denied', $lang_code));
				$response->returnResponse();
			}

			if (empty($user['user_id']) && !empty($user['password_hash'])) {
				$user['password1'] = 'tmp';
				$user['password2'] = 'tmp';
			}

			$result = fn_api_update_user($user, $_auth);

			if (!$result) {
				if (!fn_set_internal_errors($response, 'ERROR_FAIL_CREATE_USER')) {
					$response->addError('ERROR_FAIL_CREATE_USER', fn_get_lang_var('fail_create_user', $lang_code));
				}
				$response->returnResponse();
			}

			if (!empty($user['password_hash'])) {
				db_query("UPDATE ?:users SET password = ?s WHERE user_id = ?i", $user['password_hash'], $_auth['user_id']);
			}

		} else {
			$response->addError('ERROR_UNKNOWN_REQUEST', fn_get_lang_var('unknown_request', $lang_code));
			$response->returnResponse();
		}

	} elseif ($meta['action'] == 'get') {

		if ($meta['object'] == 'cart') {
			$result = fn_api_get_session_cart($lang_code);
			$response->setData($result);

		} elseif ($meta['object'] == 'products') {
			fn_set_response_products($response, $_REQUEST, $items_per_page, $lang_code);

		} elseif ($meta['object'] == 'categories') {
			fn_set_response_categories($response, $_REQUEST, $items_per_page, $lang_code);

		} elseif ($meta['object'] == 'catalog') {
			fn_set_response_catalog($response, $_REQUEST, $items_per_page, $lang_code);

		} elseif ($meta['object'] == 'orders') {
			$_auth = & $_SESSION['auth'];
			if (empty($_auth['user_id'])) {
				$response->addError('ERROR_ACCESS_DENIED', fn_get_lang_var('access_denied'));
				$response->returnResponse();
			}

			$params = $_REQUEST;
			$params['user_id'] = $_auth['user_id'];

			list($orders, $search, $totals) = fn_get_orders($params, $items_per_page, true);
		
			$response->setMeta(!empty($totals['gross_total']) ? $totals['gross_total'] : 0, 'gross_total');
			$response->setMeta(!empty($totals['totally_paid']) ? $totals['totally_paid'] : 0, 'totally_paid');

			$response->setResponseList(fn_get_orders_as_api_list($orders, $lang_code));
			fn_set_response_pagination($response);

		} elseif ($meta['object'] == 'homepage') {
			$request_params = array (
				'location' => fn_get_blocks_location('index'),
			);
			list($blocks) = fn_get_blocks($request_params);

			//print_r($blocks);
			foreach ($blocks as $k => $v) {
				if ($v['location'] != 'index' || $v['properties']['list_object'] != 'products') {
					continue;
				}

				$block = array (
					'title' => $v['description']
				);

				$product_ids = explode(',', $v['item_ids']);
				if (!empty($product_ids)) {

					$search_params = array (
						'pid' => $product_ids
					);
					$block['products'] = fn_api_get_products($search_params, count($product_ids), $lang_code);
					$block['total_items'] = count($block['products']);
				}

				$response->setData($block, 'block_' . $v['block_id']);
			}

		} elseif ($meta['object'] == 'payment_methods') {

			$payment_methods = fn_twg_prepare_checkout_payment_methods($_SESSION['cart'], $_SESSION['auth']);

			$payment_methods = fn_get_as_api_list('payments', $payment_methods);

			if (!empty($payment_methods['payment'])) {
				foreach ($payment_methods['payment'] as $k => $v) {
					if ($options = fn_get_payment_options($v['payment_id'])) {
						$payment_methods['payment'][$k]['options'] = $options;
					}
				}

				$response->setData($payment_methods['payment']);
			}

		} elseif ($meta['object'] == 'shipping_methods') {

			if (empty($_SESSION['shipping_rates'])) {
				list ($cart_products, $_SESSION['shipping_rates']) = fn_calculate_cart_content($_SESSION['cart'], $_SESSION['auth'], 'A', true);
			}
			$shippings = array();

			foreach ($_SESSION['shipping_rates'] as $shipping_id => $data) {
				$data['shipping_id'] = $shipping_id;
				$shippings[] = $data;
			}

			$shipping_methods = fn_get_as_api_list('shipping_methods', $shippings);

			if (!empty($shipping_methods['shipping_method'])) {
				$response->setData($shipping_methods['shipping_method']);
			}

		} else {
			$response->addError('ERROR_UNKNOWN_REQUEST', fn_get_lang_var('unknown_request'));
			$response->returnResponse();

		}

	} elseif ($meta['action'] == 'details') {

		if ($meta['object'] == 'products') {
			$object = fn_get_api_product_data($_REQUEST['id'], $lang_code);
			$title = 'product';

		} elseif ($meta['object'] == 'categories') {
			$object = fn_get_api_category_data($_REQUEST['id'], $lang_code);
			$title = 'category';

		} elseif ($meta['object'] == 'orders') {
			$_auth = & $_SESSION['auth'];
			$order_id = !empty($_REQUEST['id']) ? $_REQUEST['id'] : 0;
			
			if (!empty($_auth['user_id'])) {
				$allowed_id = db_get_field("SELECT user_id FROM ?:orders WHERE user_id = ?i AND order_id = ?i", $_auth['user_id'], $order_id);

			}

			// Check order status (incompleted order)
			if (!empty($allowed_id)) {
				$status = db_get_field('SELECT status FROM ?:orders WHERE order_id = ?i', $order_id);
				if ($status == STATUS_INCOMPLETED_ORDER) {
					$allowed_id = 0;
				}
			}

			if (empty($allowed_id)) {
				$response->addError('ERROR_ACCESS_DENIED', fn_get_lang_var('access_denied'));
				$response->returnResponse();
			}

			$object = fn_api_get_order_details($order_id);
			$title = 'order';

		} elseif ($meta['object'] == 'users') {

			$_auth = & $_SESSION['auth'];
			if (!empty($_auth['user_id'])) {
				$params = array(
					'id' => $_auth['user_id']
				);
				fn_api_get_object($response, $meta['object'], $params);

			} else {
				$response->addError('ERROR_ACCESS_DENIED', fn_get_lang_var('access_denied'));

			}

		} else {
			$response->addError('ERROR_UNKNOWN_REQUEST', fn_get_lang_var('unknown_request'));
			$response->returnResponse();
		}

		if (!empty($object)) {
			$response->setData($object);
		} elseif(!empty($title)) {
			$response->addError('ERROR_OBJECT_WAS_NOT_FOUND', str_replace('[object]', $title, fn_get_lang_var('object_was_not_found')));
		}

	} elseif ($meta['action'] == 'featured') {

		$items_qty = !empty($_REQUEST['items']) ? $_REQUEST['items'] : TWG_RESPONSE_ITEMS_LIMIT;
		$params = $_REQUEST;

		if ($meta['object'] == 'products') {
			$conditions = array();

			$table = '?:products';

			if (!empty($params['product_id'])) {
				$conditions[] = db_quote('product_id != ?i', $params['product_id']);
			}

			if (!empty($params['category_id'])) {
				$table = '?:products_categories';
				$category_ids = db_get_fields("SELECT a.category_id FROM ?:categories as a LEFT JOIN ?:categories as b ON b.category_id = ?i WHERE a.id_path LIKE CONCAT(b.id_path, '/%')", $params['category_id']);
				$conditions[] = db_quote('category_id IN (?n)', $category_ids);
			}
	
			$condition = implode(' AND ', $conditions);
			$product_ids = fn_get_random_ids($items_qty, 'product_id', $table, $condition);

			if (!empty($product_ids)) {

				$search_params = array (
					'pid' => $product_ids
				);
				$search_params = array_merge($_REQUEST, $search_params);
				$result = fn_api_get_products($search_params, $items_qty, $lang_code);
			}

		} elseif ($meta['object'] == 'categories') {

			$condition = '';

			if (!empty($params['category_id'])) {
				$category_path = db_get_field("SELECT id_path FROM ?:categories IGNORE INDEX(p_category_id) WHERE category_id = ?i", $params['category_id']);

				if (!empty($category_path)) {
					$condition = "id_path LIKE '$category_path/%'";
				}
			}
			
			$category_ids = fn_get_random_ids($items_qty, 'category_id', '?:categories', $condition);
	
			if (!empty($category_ids)) {
				$search_params = array (
					'cid' => $category_ids,
					'group_by_level' => false
				);

				$search_params = array_merge($_REQUEST, $search_params);
				$result = fn_api_get_categories($search_params, $lang_code);
			}

		} else {
			$response->addError('ERROR_UNKNOWN_REQUEST', fn_get_lang_var('unknown_request'));
			$response->returnResponse();
		}
		
		if (!empty($result)) {
			$response->setResponseList($result);
		}
		
	} else {
		$response->addError('ERROR_UNKNOWN_REQUEST', fn_get_lang_var('unknown_request'));
	}

	$response->returnResponse();
}

function fn_init_api_meta($response)
{
	// init request params
	$meta = array (
		'object' => !empty($_REQUEST['object']) ? $_REQUEST['object'] : '',
		'action' => !empty($_REQUEST['action']) ? $_REQUEST['action'] : '',
		'session_id' => !empty($_REQUEST['session_id']) ? $_REQUEST['session_id'] : '',
	);

	// set request params for the response
	$response->setMeta($meta['action'], 'action');

	if (!empty($meta['object'])) {
		$response->setMeta($meta['object'], 'object');
	}

	// init session
	if (!empty($meta['session_id'])) {
		// replace qurrent session with the restored by session id
		Session::set_id($meta['session_id']);
	}

	// start session
	fn_init_api_session_data();

	$response->setMeta(Session::get_id(), 'session_id');

	return $meta;
}

function fn_get_api_data($response, $format, $required = true)
{
	if (!empty($_REQUEST['data'])) {
		$data = ApiData::parseDocument(base64_decode(rawurldecode($_REQUEST['data'])), $format);
	} elseif ($required) {
		$response->addError('ERROR_WRONG_DATA', fn_get_lang_var('wrong_api_data'));
		$response->returnResponse();
	}

	return $data;
}

/**
 * Copy of the fn_prepare_checkout_payment_methods from the
 * 'customer/checkout.php'
 */
function fn_twg_prepare_checkout_payment_methods(&$cart, &$auth)
{
	static $payment_methods;

	//Get payment methods
	if (empty($payment_methods)) {
		$payment_methods = fn_get_payment_methods($auth);
	}

	// Check if payment method has surcharge rates
	foreach ($payment_methods as $k => $v) {
		$payment_methods[$k]['surcharge_value'] = 0;
		if (floatval($v['a_surcharge'])) {
			$payment_methods[$k]['surcharge_value'] += $v['a_surcharge'];
		}
		if (floatval($v['p_surcharge']) && !empty($cart['total'])) {
			$payment_methods[$k]['surcharge_value'] += fn_format_price($cart['total'] * $v['p_surcharge'] / 100);
		}
	}

	fn_set_hook('prepare_checkout_payment_methods', $cart, $auth, $payment_methods);

	return $payment_methods;
}
?>