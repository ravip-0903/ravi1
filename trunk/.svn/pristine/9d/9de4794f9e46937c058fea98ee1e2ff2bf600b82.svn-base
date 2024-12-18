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

fn_define('CHECKOUT', true);
fn_define('ORDERS_TIMEOUT', 60);

// Cart is empty, create it
if (empty($_SESSION['cart'])) {
	fn_clear_cart($_SESSION['cart']);
}


$cart = & $_SESSION['cart'];
//echo '<pre>';print_r($cart);echo '</pre>';//die;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$_suffix = '';

	//
	// Add product to cart
	//
   
	if ($mode == 'add') {
            //echo '<pre>';print_r($_REQUEST);die;
		if (empty($auth['user_id']) && Registry::get('settings.General.allow_anonymous_shopping') != 'Y') {
			return array(CONTROLLER_STATUS_REDIRECT, "auth.login_form?return_url=" . urlencode($_SERVER['HTTP_REFERER']));
		}

    $ws_product_data = $_REQUEST['product_data'];
    foreach($ws_product_data as $product_id => $value)
    {
      $ws_product_id   = $value['product_id'];
    }
    if(!can_add_in_cart($cart['products'],$ws_product_id))
    {
      fn_set_notification('E',fn_get_lang_var('notice'), fn_get_lang_var('choose_uniform_cart'));
      exit;
    }
    
		
		$ws_product_data = $_REQUEST['product_data'];
		foreach($ws_product_data as $product_id => $value)
		{
		  $ws_product_id   = $value['product_id'];
		}
		
			//checks for auction products
			if(!is_product_allowed_for_user($_SESSION['auth']['user_id'],$ws_product_id))
			{
				fn_set_notification('E','Error','You cannot add this product to your cart');
				return false;
			}
			//check for auction products end here
                
                if(isset($_SESSION['express']))
                {
                    unset($_SESSION['express']);
                }
                
                if(isset($cart['express_logging']))
                {
                    unset($cart['express_logging']);
                }
	
		// Add to cart button was pressed for single product on advanced list
                //echo '<pre>';print_r($dispatch_extra);die;
		if (!empty($dispatch_extra)) {
			if (empty($_REQUEST['product_data'][$dispatch_extra]['amount'])) {
				$_REQUEST['product_data'][$dispatch_extra]['amount'] = 1;
			}
			foreach ($_REQUEST['product_data'] as $key => $data) {
				if ($key != $dispatch_extra && $key != 'custom_files') {
					unset($_REQUEST['product_data'][$key]);
				}
			}
		}
             
		$prev_cart_products = empty($cart['products']) ? array() : $cart['products'];
		
		//code by ankur to add upsell products
		if(!empty($_REQUEST['upsell_product']))
		{
			foreach($_REQUEST['upsell_product'] as $upsell_product)
			{
				$_REQUEST['product_data'][$upsell_product]['product_id']=$upsell_product;
				$_REQUEST['product_data'][$upsell_product]['amount']=1;
			}
		}
		//code end
                
                //code by ankur to add new upsell products
                if(!empty($_REQUEST['new_pro_upsell']))
                {
                   foreach($_REQUEST['new_pro_upsell'] as $upsell_id)
                   {
                      $_REQUEST['product_data'][$upsell_id]['product_id']=$upsell_id;
                      $_REQUEST['product_data'][$upsell_id]['amount']=1;
                   }
                }
                //code end
		
		
		//code by ankur to add upsell products
		if(!empty($_REQUEST['fs_product']))
		{
			foreach($_REQUEST['fs_product'] as $fs_product)
			{
				$_REQUEST['product_data'][$fs_product]['product_id']=$fs_product;
				$_REQUEST['product_data'][$fs_product]['amount']=1;
			}
		}
		//code end
                
                if(!empty($_REQUEST['combo_offer']))
                {
                   foreach($_REQUEST['combo_offer'] as $combo_id)
                   {
                      $_REQUEST['product_data'][$combo_id]['product_id']=$combo_id;
                      $_REQUEST['product_data'][$combo_id]['amount']=1;
                   }
                }
                
		fn_add_product_to_cart($_REQUEST['product_data'], $cart, $auth);
                /*added by sapna to track buy now clicks*/
                if(Registry::get('config.track_user')){
                   fn_track_user($_COOKIE['scto'],"",$_REQUEST['product_data'],'buynow');
                }
		/*added by chandan to add GC in cart from product page*/
                 
		if(isset($_REQUEST['gc_data'])){
                   
			foreach($_REQUEST['gc_data'] as $gc){
				$gift_cert_data = array
									(
										'recipient' => ($_SESSION['auth']['user_id'] > 0)? $cart['user_data']['email']:Registry::get('config.default_gc_email'),
										'sender' => ($_SESSION['auth']['user_id'] > 0)? $cart['user_data']['email']:Registry::get('config.default_gc_email'),
										'amount_type' => 'I',
										'amount' => $gc,
										'message' => '',
										'send_via' => 'E',
										'email' => ($_SESSION['auth']['user_id'] > 0)? $cart['user_data']['email']:Registry::get('config.default_gc_email'),
										'template' => 'default.tpl'
									); 
				
				list($gift_cert_id, $gift_cert) = fn_add_gift_certificate_to_cart($gift_cert_data, $auth);
		
				if (!empty($gift_cert_id)) {
					$_SESSION['cart']['gift_certificates'][$gift_cert_id] = $gift_cert;
				}
			}
		}
		/*added by chandan to add GC in cart from product page*/
		fn_save_cart_content($cart, $auth['user_id']);
		$cart['recalculate'] = false;
		$previous_state = md5(serialize($cart['products']));
		/*Modified by chandan to stop cart calculation on add to cart*/
		//fn_calculate_cart_content($cart, $auth, 'S', false, 'F', false);
		
		//if (md5(serialize($cart['products'])) != $previous_state && empty($cart['skip_notification'])) {
		if (empty($cart['skip_notification'])) {
		/*Modified by chandan to stop cart calculation on add to cart*/
			$product_cnt = 0;
			$added_products = array();
			foreach ($cart['products'] as $key => $data) {
				if (empty($prev_cart_products[$key]) || !empty($prev_cart_products[$key]) && $prev_cart_products[$key]['amount'] != $data['amount']) {
					$added_products[$key] = $data;
					$added_products[$key]['product_option_data'] = fn_get_selected_product_options_info($data['product_options']);
					if (!empty($prev_cart_products[$key])) {
						$added_products[$key]['amount'] = $data['amount'] - $prev_cart_products[$key]['amount'];
					}
					$product_cnt += $added_products[$key]['amount'];
				}
			}
			
			if (!empty($added_products) || $_SESSION['zero_inventory_cart']) {
				$view->assign('added_products', $added_products);
                                                                        $view->assign('zero_inventory', $_SESSION['zero_inventory_cart']);
                                                                        $view->assign('zero_inventory_product',$_SESSION['zero_inventory_product']);
				$view->assign('cart', $cart);
				if (Registry::get('settings.DHTML.ajax_add_to_cart') != 'Y' && Registry::get('settings.General.redirect_to_cart') == 'Y') {
					$view->assign('continue_url', (!empty($_REQUEST['redirect_url']) && empty($_REQUEST['appearance']['details_page'])) ? $_REQUEST['redirect_url'] : $_SESSION['continue_url']);
				}

				 /*modified by chandan to log cart, request, session*/
				if(Registry::get('config.cart_logging')) {
					$cart_data = serialize($cart);
					$session_data = serialize($_SESSION);
					$request_data = serialize($_REQUEST).'server data : '.serialize($_SERVER);
					$checkout_step = 'product added to cart';
					$user_id = $_SESSION['auth']['user_id'];
					
					$sql = "insert into clues_carts_history (user_id, cart_data, session_data, request_data, checkout_step, session_id) values
							('".$user_id."','".addslashes($cart_data)."','".addslashes($session_data)."','".addslashes($request_data)."','".$checkout_step."','".session_id()."')";
					db_query($sql);
				}
				/*modified by chandan to log cart, request, session*/

				//$msg = $view->display('views/products/components/product_notification.tpl', false);

				/*// Changes by Sudhir dt 27th May 2013 to detect mobile browser to show cart popup bigin here
				if(isMobile()){
					$msg = $view->display('views/checkout/components/cart_mobile_popup.tpl', false);					
				} else {					
					//$msg = $view->display('views/checkout/components/cart_mobile_popup.tpl', false);
					$msg = $view->display('views/checkout/components/cart_content_popup.tpl', false);
				}
				// Changes by Sudhir dt 27th May 2013 to detect mobile browser to show cart popup end here*/
				
				// Rolled back pankaj changes for mobile tpl dt 03 June 2013
				if(Registry::get('config.isResponsive')){
                    $cart['recalculate'] = false;
                    $_suffix = '.cart';
                    fn_redirect('index.php?dispatch=checkout.cart');
                }else{
                    if(!isset($_REQUEST['appearance']['layout']) && $_REQUEST['appearance']['layout'] != "products"){
                        if(Registry::get('config.xbuy_now_popup')){
                            $msg = $view->display('views/checkout/components/xcart_content_popup.tpl', false);
                            fn_set_notification('P', fn_get_lang_var($product_cnt > 1 ? 'xproducts_added_to_cart' : 'xproduct_added_to_cart'), $msg, 'I');
                        }else{
                            $msg = $view->display('views/checkout/components/cart_content_popup.tpl', false);
                            fn_set_notification('P', fn_get_lang_var($product_cnt > 1 ? 'products_added_to_cart' : 'product_added_to_cart'), $msg, 'I');
                        }

                    //fn_set_notification('P', fn_get_lang_var($product_cnt > 1 ? 'products_added_to_cart' : 'product_added_to_cart'), $msg, 'I');

                                    }
                    $cart['recalculate'] = false;
                }
                                                                        unset($_SESSION['zero_inventory_cart']);
                                                                        unset($_SESSION['zero_inventory_product']);
			} 
                        else {
                                                    fn_set_notification('N', fn_get_lang_var('notice'), fn_get_lang_var('product_in_cart'));
                            	}
		}
	
		unset($cart['skip_notification']);

		if (defined('AJAX_REQUEST')) {
			$view->assign('cart_amount', $cart['amount']);
			$view->assign('cart_subtotal', $cart['display_subtotal']);
			$view->assign('force_items_deletion', true);

			// The redirection is made in order to update the page content to see changes made in the cart when adding a product to it from the 'view cart' or 'checkout' pages. 
			if (strpos($_SERVER['HTTP_REFERER'], 'dispatch=checkout.cart') || strpos($_SERVER['HTTP_REFERER'], 'dispatch=checkout.checkout') || strpos($_SERVER['HTTP_REFERER'], 'dispatch=checkout.summary')) {
				$ajax->assign('force_redirection', $_SERVER['HTTP_REFERER']);
			}

			$view->display('views/checkout/components/cart_status.tpl');
			exit;
		}

		$_suffix = '.cart';
						
		if (Registry::get('settings.DHTML.ajax_add_to_cart') != 'Y' && Registry::get('settings.General.redirect_to_cart') == 'Y') {
			if (!empty($_REQUEST['redirect_url']) && empty($_REQUEST['appearance']['details_page'])) {
				$_SESSION['continue_url'] = $_REQUEST['redirect_url'];
			}
			unset($_REQUEST['redirect_url']);
		}
	}

	//
	// Update products quantity in the cart
	//
	if ($mode == 'update') {
		if (!empty($_REQUEST['cart_products'])) {
			foreach ($_REQUEST['cart_products'] as $_key => $_data) {
				if (empty($_data['amount']) && !isset($cart['products'][$_key]['extra']['parent'])) {
					fn_delete_cart_product($cart, $_key);
				}
			}
			fn_add_product_to_cart($_REQUEST['cart_products'], $cart, $auth, true);
			fn_save_cart_content($cart, $auth['user_id']);
		}
		/*modified by chandan to log cart, request, session*/
		if(Registry::get('config.cart_logging')) {
			$cart_data = serialize($cart);
			$session_data = serialize($_SESSION);
			$request_data = serialize($_REQUEST).'server data ' . serialize($_SERVER);
			$checkout_step = 'product updated from cart page';
			$user_id = $_SESSION['auth']['user_id'];
	
			$sql = "insert into clues_carts_history (user_id, cart_data, session_data, request_data, checkout_step, session_id) values
					('".$user_id."','".addslashes($cart_data)."','".addslashes($session_data)."','".addslashes($request_data)."','".$checkout_step."','".session_id()."')";
			db_query($sql);
		}
                /*modified by chandan to log cart, request, session*/
		fn_set_notification('N', fn_get_lang_var('notice'), fn_get_lang_var('text_products_updated_successfully'));

		// Recalculate cart when updating the products
		$cart['recalculate'] = true;
		
		
		$_suffix = ".$_REQUEST[redirect_mode]";
	}
	
	
	if($mode == 'gift_wrap'){
		if($cart['payment_id'] == '6'){
			$cart['gifting']['gift_it'] = 'N';
			fn_set_notification('w', '', fn_get_lang_var('gifting_not_allowed_on_cod'));
		}else{
			$cart['gifting']['gift_it'] = 'Y';
		}
		$cart['gifting']['to'] = $_REQUEST['gift_to'];
		$cart['gifting']['from'] = $_REQUEST['gift_from'];
		$cart['gifting']['msg'] = $_REQUEST['gift_message'];
		$cart_items = 0;
		foreach($cart['products'] as $items)
		{
			$cart_items += $items['amount'];
		}
		$cart['gifting']['gifting_charge'] = $cart_items * Registry::get('config.gifting_charge');
		
		return array(CONTROLLER_STATUS_OK, "checkout.checkout&edit_step=step_three");
	}
        
        if($mode == 'place_cod_order'){
            $order_id = addslashes($_REQUEST['order_id']);
            $user_id = addslashes($_REQUEST['user_id']);
            $key = addslashes($_REQUEST['key']);
            $response = place_order_on_cod($order_id,$user_id,$key);
            if($response){
                fn_order_placement_routines($order_id);
            }else{
                unset($cart['cod_eligible_order_id']);
                return array(CONTROLLER_STATUS_OK, "checkout.checkout&edit_step=step_three");
            }
	}
	
	//
	// Estimate shipping cost
	//
	if ($mode == 'shipping_estimation') {
		
		fn_define('ESTIMATION', true);

		$customer_location = empty($_REQUEST['customer_location']) ? array() : $_REQUEST['customer_location'];
		foreach ($customer_location as $k => $v) {
			$cart['user_data']['s_' . $k] = $v;
		}
		$_SESSION['customer_loc'] = $customer_location;

		$cart['recalculate'] = true;

		if (!empty($_REQUEST['shipping_ids'])) {
			fn_checkout_update_shipping($cart, $_REQUEST['shipping_ids']);
		}

		list ($cart_products, $_SESSION['shipping_rates']) = fn_calculate_cart_content($cart, $auth, 'A', true, 'F', true);

		$view->assign('shipping_rates', $_SESSION['shipping_rates']);
		$view->assign('cart', $cart);
		$view->assign('cart_products', array_reverse($cart_products, true));
		$view->assign('location', empty($_REQUEST['location']) ? 'cart' : $_REQUEST['location']);
		$view->assign('additional_id', empty($_REQUEST['additional_id']) ? '' : $_REQUEST['additional_id']);

		if (defined('AJAX_REQUEST')) {
			if (fn_is_empty($cart_products) && fn_is_empty($_SESSION['shipping_rates'])) {
				$ajax->assign_html('shipping_estimation_sidebox' . (empty($_REQUEST['additional_id']) ? '' : '_' . $_REQUEST['additional_id']), fn_get_lang_var('no_rates_for_empty_cart'));
			} else {
				$view->display(empty($_REQUEST['location']) ? 'views/checkout/components/checkout_totals.tpl' : 'views/checkout/components/shipping_estimation.tpl');
			}
			exit;
		}

		$_suffix = '.' . (empty($_REQUEST['current_mode']) ? 'cart' : $_REQUEST['current_mode']) . '?show_shippings=Y';
	}

	if ($mode == 'update_shipping') {
		if (!empty($_REQUEST['shipping_ids'])) {
			fn_checkout_update_shipping($cart, $_REQUEST['shipping_ids']);
		}

		$_suffix = ".$_REQUEST[redirect_mode]";
	}

	if ($mode == 'calculate_total_shipping_cost') {
		if (!empty($_REQUEST['shipping_ids'])) {
			fn_checkout_update_shipping($cart, $_REQUEST['shipping_ids']);
			list ($cart_products, $_SESSION['shipping_rates']) = fn_calculate_cart_content($cart, $auth, 'A', true, 'F', true);
			$view->assign('shipping_rates', $_SESSION['shipping_rates']);
			$view->assign('cart', $cart);
			$view->assign('display', 'radio');
			if (defined('AJAX_REQUEST')) {
				$view->display("views/checkout/components/shipping_rates.tpl");
				exit;
			}
		}
	}

	if($mode == 'change_address'){
		
		$user_data['profile_id'] = $_REQUEST['profile_id'];
		$user_data['profile_name'] = (trim($_REQUEST['profile_name']) != '') ? $_REQUEST['profile_name'] : $_REQUEST['firstname']. ' ' . $_REQUEST['lastname']; 
		$user_data['s_firstname'] = $_REQUEST['firstname'];
		$user_data['s_lastname'] = $_REQUEST['lastname'];
		$user_data['s_address'] = $_REQUEST['address'];
		$user_data['s_address_2'] = $_REQUEST['address_2'];
		$user_data['s_city'] = $_REQUEST['city'];
		$user_data['s_state'] = $_REQUEST['state'];
		$user_data['s_country'] = $_REQUEST['country'];
		$user_data['s_zipcode'] = $_REQUEST['zipcode'];
		$user_data['s_phone'] = $_REQUEST['phone'];
		$user_data['verified'] = 0;
		db_query('UPDATE ?:user_profiles SET ?u WHERE profile_id = ?i', $user_data, $user_data['profile_id']);
		return array(CONTROLLER_STATUS_OK, "checkout.checkout&edit_step=step_two");
	}
	
	// Apply Discount Coupon
	if ($mode == 'apply_coupon') {

                $cart['pending_coupon'] = strtoupper($_REQUEST['coupon_code']);
		$cart['recalculate'] = true;
		$cart['earlier_promotions'] = $cart['promotions'];
               /*modified by chandan to log cart, request, session*/
		if(Registry::get('config.cart_logging')) {
			$cart_data = serialize($cart);
			$session_data = serialize($_SESSION);
			$request_data = serialize($_REQUEST);
			$checkout_step = 'ajax coupon';
			$user_id = $_SESSION['auth']['user_id'];
			
			$sql = "insert into clues_carts_history (user_id, cart_data, session_data, request_data, checkout_step, session_id) values
							('".$user_id."','".addslashes($cart_data)."','".addslashes($session_data)."','".addslashes($request_data)."','".$checkout_step."','".session_id()."')";
			db_query($sql);
		}
		/*modified by chandan to log cart, request, session*/
             
		$_suffix = ".$_REQUEST[redirect_mode]";
	}

	if ($mode == 'add_profile') {
	
		if (Registry::get('settings.Image_verification.use_for_register') == 'Y' && fn_image_verification('register', empty($_REQUEST['verification_answer']) ? '' : $_REQUEST['verification_answer']) == false) {
			fn_save_post_data();
			
			return array(CONTROLLER_STATUS_REDIRECT, "checkout.checkout?login_type=register");
		}

		
		if ($res = fn_update_user(0, $_REQUEST['user_data'], $auth, false, true)) {
			$profile_fields = fn_get_profile_fields('O');
			
			$step = 'step_two';
			if (empty($profile_fields['B']) && empty($profile_fields['S'])) {
				$step = 'step_three';
			}
			
			$suffix = '?edit_step=' . $step;
		} else {
			$suffix = '?login_type=register';
		}

		return array(CONTROLLER_STATUS_OK, "checkout.checkout" .  $suffix);
	}

	if ($mode == 'customer_info') {
		if (Registry::get('settings.General.disable_anonymous_checkout') == 'Y' && empty($cart['user_data']['email']) && Registry::get('settings.Image_verification.use_for_checkout') == 'Y' && fn_image_verification('checkout', empty($_REQUEST['verification_answer']) ? '' : $_REQUEST['verification_answer']) == false) {
			fn_save_post_data();

			return array(CONTROLLER_STATUS_REDIRECT, "checkout.checkout?login_type=guest");
		}

		$profile_fields = fn_get_profile_fields('O');
		$user_profile = array();

		if (!empty($_REQUEST['user_data'])) {
			/*Modified by clues dev to allow registered user as guest checkout.*/
			/*if (empty($auth['user_id']) && !empty($_REQUEST['user_data']['email'])) { 
				$email_exists = db_get_field("SELECT email FROM ?:users WHERE email = ?s", $_REQUEST['user_data']['email']); 
				if (!empty($email_exists)) {
					fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('error_user_exists')); 
					fn_save_post_data(); 

					return array(CONTROLLER_STATUS_REDIRECT, "checkout.checkout&userstatus=exist"); 
				}
			}*/
			/*Modified by clues dev to allow registered user as guest checkout.*/
			$user_data = $_REQUEST['user_data'];
			!empty($_REQUEST['copy_address']) ? $_REQUEST['ship_to_another'] = '' : $_REQUEST['ship_to_another'] = 'Y';


           $check_emailid=fn_check_email_id($user_data);
			 if(empty($check_emailid))
			 {
				 $pass = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,6);
				 $userarray = array('email'=>$user_data['email'],'password1'=>$pass,'password2'=>$pass);
				$arr_user_profile_id = fn_update_user(0, $userarray, $_auth,!empty($_REQUEST['ship_to_another']),true,true,'','checkout');				
			 }
			 
			unset($user_data['user_type']);
			if (!empty($cart['user_data'])) {
				$cart['user_data'] = fn_array_merge($cart['user_data'], $user_data);
			} else {
				$cart['user_data'] = $user_data;
			}

			// Fill shipping info with billing if needed
			if (empty($_REQUEST['ship_to_another'])) {
				fn_fill_address($cart['user_data'], $profile_fields);
			}

			// Add descriptions for titles, countries and states
			fn_add_user_data_descriptions($cart['user_data']);

			// Update profile info (if user is logged in)
			$cart['profile_registration_attempt'] = false;
			$cart['ship_to_another'] = !empty($_REQUEST['ship_to_another']);

			if (!empty($auth['user_id'])) {
				// Check email
				$email_exists = db_get_field("SELECT email FROM ?:users WHERE email = ?s AND user_id != ?i", $cart['user_data']['email'], $auth['user_id']);
				if (!empty($email_exists)) {
					fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('error_user_exists'));
					$cart['user_data']['email'] = '';
				} else {
					db_query('UPDATE ?:users SET ?u WHERE user_id = ?i', $cart['user_data'], $auth['user_id']);

					if (!empty($cart['profile_id'])) {
						db_query('UPDATE ?:user_profiles SET ?u WHERE profile_id = ?i', $cart['user_data'], $cart['profile_id']);
					} else {
						$cart['profile_id'] = db_query('INSERT INTO ?:user_profiles ?e', $cart['user_data']);
					}

					fn_store_profile_fields($cart['user_data'], $cart['profile_id'], 'P');

					
				}

			} elseif (Registry::get('settings.General.disable_anonymous_checkout') == 'Y' || !empty($user_data['password1'])) {
				$cart['profile_registration_attempt'] = true;
				$user_profile = fn_update_user(0, $cart['user_data'], $auth, $cart['ship_to_another'], true);
				if ($user_profile === false) {
					unset($cart['user_data']['email'], $cart['user_data']['user_login']);
				} else {
					$cart['profile_id'] = $user_profile[1];
				}
			} else {
				$profile_fields = fn_get_profile_fields('O', $auth);
				if (count($profile_fields['C']) > 1) {
					return array(CONTROLLER_STATUS_REDIRECT, "checkout.checkout&edit_step=step_one"); 
				}else if(!empty($arr_user_profile_id[0])){ 
					fn_login_user($arr_user_profile_id[0]); //auto login
				}
			}
		}

		$cart['recalculate'] = true;

		fn_save_cart_content($cart, $auth['user_id']);
		fn_set_scun_cookie();
		/*modified by chandan to log cart, request, session*/
		if(Registry::get('config.cart_logging')) {
			$cart_data = serialize($cart);
			$session_data = serialize($_SESSION);
			$request_data = serialize($_REQUEST);
			$checkout_step = 'guest checkout';
			$user_id = $_SESSION['auth']['user_id'];
			
			$sql = "insert into clues_carts_history (user_id, cart_data, session_data, request_data, checkout_step, session_id) values
							('".$user_id."','".addslashes($cart_data)."','".addslashes($session_data)."','".addslashes($request_data)."','".$checkout_step."','".session_id()."')";
			db_query($sql);
		}
		/*modified by chandan to log cart, request, session*/	
		
		$step = 'step_two';
		if (empty($profile_fields['B']) && empty($profile_fields['S'])) {
			$step = 'step_three';
		}
		
		$_suffix = '.checkout?edit_step=' . $step;
	}

	if ($mode == 'order_info') {
		if (isset($_REQUEST['payment_id']) || isset($_REQUEST['payment_option_id'])) {
			if(isset($_REQUEST['payment_option_id']) && isset($_REQUEST['eprd'])){
				$sql = "select cpoep.id, cpoep.payment_option_id, cpoep.payment_gateway_id, cpoep.period, cpoep.fee, cpoep.promo_fee, cpoep.promo_end_date, cpoep.name, cpoep.status, cpt.name as payment_type,
						cpo.name as payment_option 
						from clues_payment_options_emi_pgw cpoep 
						join clues_payment_options cpo on (cpoep.payment_option_id=cpo.payment_option_id)
						join clues_payment_types cpt on (cpo.payment_type_id=cpt.payment_type_id)						
						join cscart_payments cp on (cpoep.payment_gateway_id=cp.payment_id) 
						where cpoep.payment_option_id='".$_REQUEST['payment_option_id']."' and cpoep.status = 'A' and cp.status = 'A' and cpoep.id=".$_REQUEST['eprd']."";				
				$pgw_avail = db_get_row($sql);
                                
				$payment_id = !empty($pgw_avail['payment_gateway_id']) ? (int) $pgw_avail['payment_gateway_id'] : '-1';
				$cart['emi_id'] = $_REQUEST['eprd'];
				//print_r($pgw_avail);
				if(date('Y-m-d h:i:s') <= $pgw_avail['promo_end_date']) {
					$cart['emi_fee'] = $pgw_avail['promo_fee'];	
				}else {
                                    $cart['emi_fee'] = fn_format_price((($cart['total']-$cart['emi_fee']) * $pgw_avail['fee'])/100);
				}
			}elseif(isset($_REQUEST['payment_option_id'])){
				//$sql = "select * from clues_payment_option_pgw where payment_option_id='".$_REQUEST['payment_option_id']."' and status = 'A' order by priority asc";
				$sql = "select cpop.payment_option_pgw_id, cpop.payment_option_id, cpop.payment_gateway_id, cpop.priority, cpop.status, cpt.name as payment_type, cpo.name as payment_option from clues_payment_option_pgw cpop join clues_payment_options cpo on (cpop.payment_option_id=cpo.payment_option_id)
						join clues_payment_types cpt on (cpo.payment_type_id=cpt.payment_type_id)						
						join cscart_payments cp on (cpop.payment_gateway_id=cp.payment_id) 
						where cpop.payment_option_id='".$_REQUEST['payment_option_id']."' and cpop.status = 'A' and cp.status = 'A' order by priority asc";				
				$pgw_avail = db_get_row($sql);
				$payment_id = !empty($pgw_avail['payment_gateway_id']) ? (int) $pgw_avail['payment_gateway_id'] : '-1';
				if($payment_id == '6'){
					$cart['cod_fee'] = Registry::get('config.cod_fee_amt');
				}else{
					$cart['cod_fee'] = '';
				}
				$cart['emi_id'] = '';
				$cart['emi_fee'] = '';
			}else{
				$payment_id = !empty($_REQUEST['payment_id']) ? (int) $_REQUEST['payment_id'] : '-1';
			}
			// [andyye]
			fn_sdeep_show_cod_warning($payment_id);
			// [/andyye]
			$cart['payment_id'] = $payment_id;
			$cart['payment_option_id'] = $_REQUEST['payment_option_id'];
			$cart['payment_details'] = $pgw_avail;
			$cart['payment_updated'] = false;
			fn_update_payment_surcharge($cart);
			if($payment_id == '6')
			{
				$cart['gifting']['gift_it'] = 'N';	
				
			}
			if($payment_id == '6'){
				$cart['shipping_error']='yes';
			}else{
				$cart['shipping_error']='no';
			}
                        $metrics_to_log['payment_type'] = $pgw_avail['payment_type'];
                        $metrics_to_log['payment_option'] = $pgw_avail['payment_option'];
                        if($pgw_avail['payment_type'] == 'EMI' && isset($pgw_avail['name'])){
                            $metrics_to_log['emi_type'] = $pgw_avail['name'];
                        }
                        
                        LogMetric::dump_log(array_keys($metrics_to_log), array_values($metrics_to_log));
		}
		fn_save_cart_content($cart, $auth['user_id']);
                fn_calculate_cart_content($cart, $auth, 'S', true, 'F', true);
                /*modified by chandan to log cart, request, session*/
		if(Registry::get('config.cart_logging')) {
			$cart_data = serialize($cart);
			$session_data = serialize($_SESSION);
			$request_data = serialize($_REQUEST);
			$checkout_step = 'ajax payment selection';
			$user_id = $_SESSION['auth']['user_id'];
			
			$sql = "insert into clues_carts_history (user_id, cart_data, session_data, request_data, checkout_step, session_id) values
							('".$user_id."','".addslashes($cart_data)."','".addslashes($session_data)."','".addslashes($request_data)."','".$checkout_step."','".session_id()."')";
			db_query($sql);
		}
		/*modified by chandan to log cart, request, session*/		
		$_suffix = ".checkout";
		
	}

	if ($mode == 'place_order') { 

		//echo "Here";die();
		//Changes By Megha Sudan
		$redirect = notify_wholesale_subscription($cart['products'],$_SESSION['auth']['user_id'],Registry::get('config.ws_membership_type'),$edit_step);
		
		if($redirect)
		{
			return array(CONTROLLER_STATUS_REDIRECT, "checkout.checkout?edit_step=step_three");
		}
		
	//echo $_REQUEST['one_time_pwd']; echo $_SESSION['cod_verification_code'];die;
		if((!empty($_REQUEST['one_time_pwd']) &&  $_REQUEST['one_time_pwd'] ==  $_SESSION['cod_verification_code'])){
			$cart['user_data']['verified'] = 1;
		}		
		if(array_key_exists('verification_answer',$_REQUEST ) && $cart['user_data']['verified'] != 1){
			$code = empty($_REQUEST['verification_answer']) ? '' : $_REQUEST['verification_answer'];
			$verification_status = fn_image_verification('step_four',$code,'step_four');
			if(!$verification_status){
				return array(CONTROLLER_STATUS_REDIRECT, 'checkout.checkout&edit_step=step_four');
			}
		}
		
		//echo '<pre>';print_r($cart);die;
		/*validation for payment id for not set or blank or -1*/
		if(!isset($cart['payment_id'])){
			$cart['payment_id']	='-1';
			$cart['payment_option_id'] = '';
			$cart['emi_id'] = '';
			$cart['emi_fee'] = '';
			$cart['cod_fee'] = '';
			unset($cart['payment_details']);
			fn_set_notification('E', fn_get_lang_var('Error'), fn_get_lang_var('error_with_order_processing'));
			return array(CONTROLLER_STATUS_REDIRECT, 'checkout.checkout&edit_step=step_three');	
		}
		
		if($cart['payment_id'] == '-1'){
			$cart['payment_id']	='-1';
			$cart['payment_option_id'] = '';
			$cart['emi_id'] = '';
			$cart['emi_fee'] = '';
			$cart['cod_fee'] = '';
			unset($cart['payment_details']);
			fn_set_notification('E', fn_get_lang_var('Error'), fn_get_lang_var('error_with_order_processing'));
			return array(CONTROLLER_STATUS_REDIRECT, 'checkout.checkout&edit_step=step_three');	
		}
		
		if($cart['payment_id'] === ''){
			$cart['payment_id']	='-1';
			$cart['payment_option_id'] = '';
			$cart['emi_id'] = '';
			$cart['emi_fee'] = '';
			$cart['cod_fee'] = '';
			unset($cart['payment_details']);
			fn_set_notification('E', fn_get_lang_var('Error'), fn_get_lang_var('error_with_order_processing'));
			return array(CONTROLLER_STATUS_REDIRECT, 'checkout.checkout&edit_step=step_three');	
		}
		/*validation for payment id for not set or blank or -1*/
		/*added by chandan to check the order total*/
		/*added by chandan to recalculate the cart if modified*/
		fn_calculate_cart_content($cart, $auth, 'A', true, 'F', true, true);
		/*added by chandan to recalculate the cart if modified*/
		/*added by chandan to validate the cod, emi and gifing at order placememnt*/
		if(isset($cart['error'])){
			$error_msg = '';
			if(in_array('COD_ERROR',$cart['error'])){
				$cart['payment_id']	='-1';
				$cart['payment_option_id'] = '';
				$cart['cod_fee'] = '';
				unset($cart['payment_details']);
				$error_msg .= fn_get_lang_var('cod_error');
			}
			if(in_array('EMI_ERROR',$cart['error'])){
				$cart['payment_id']	='-1';
				$cart['payment_option_id'] = '';
				$cart['emi_id'] = '';
				$cart['emi_fee'] = '';
				$cart['cod_fee'] = '';
				unset($cart['payment_details']);				
				$error_msg .= fn_get_lang_var('emi_error');
			}
			if(in_array('GIFT_ERROR',$cart['error'])){
				$cart['gifting']['gift_it'] = 'N';
				$error_msg .= fn_get_lang_var('gift_error');
			}	
			fn_set_notification('E', fn_get_lang_var('Error'), $error_msg);
			unset($cart['error']);
        	return array(CONTROLLER_STATUS_REDIRECT, 'checkout.checkout&edit_step=step_three');				
		}
		/*added by chandan to validate the cod, emi and gifing at order placememnt*/
		if($cart['gifting']['gift_it'] == 'Y'){
			$gifting_fee = $cart['gifting']['gifting_charge'];
		}else{
			$gifting_fee = 0;
		}
		$gc_total = 0;
		foreach($cart['use_gift_certificates'] as $gc){
			$gc_total += $gc['cost'];	
		}
		
		$cart_total = 0;
		foreach($cart['products'] as $cproduct)
		{
			$cart_total += $cproduct['amount']*	$cproduct['price'];
		}
		
		if(isset($cart['gift_certificates'])){
			foreach($cart['gift_certificates'] as $gc){
					if($gc['subtotal'] != ''){
						$cart_total += $gc['subtotal'];
					}else{
						$cart_total += $gc['amount'];
					}
			}
		}
		/*To handle if cart payment id set -1 due to any reason and payment option and payment details are set.*/
		if($cart['payment_id'] == '-1' && $cart['payment_option_id'] != '' && !empty($cart['payment_details'])){
			
			$cart['payment_id'] = $cart['payment_details']['payment_gateway_id'];
			
			$to = Registry::get('config.error_to_email_ids');
        	$from = Registry::get('settings.Company.company_orders_department');
        	$subject = '****************Fraud Alert*************';
        	$body_html = '<b>payment id -1 found for following </b>:<br><br> Email : '.$cart['user_data']['email'].'<br><br> User Id : '.$cart['user_data']['user_id'].'<br><br> Session Id : '.session_id().'New value for -1 : '.$cart['payment_details']['payment_gateway_id'];
        	$body_text = '';
        	$fromName = 'Shopclues.com';        	
        	sendElasticEmail($to, $subject, $body_text, $body_html, $from, $fromName, '');
		}elseif($cart['payment_id'] == '-1' && !isset($cart['payment_details'])){
			fn_set_notification('E', fn_get_lang_var('Error'), fn_get_lang_var('payment_method_negative_error'));
        	return array(CONTROLLER_STATUS_REDIRECT, 'checkout.checkout&edit_step=step_three');
		}
		/*To handle if cart payment id set -1 due to any reason and payment option and payment details are set.*/
		
		if(($cart['subtotal'] == $cart_total) && (($cart['total'] + $cart['subtotal_discount'] + $cart['points_info']['in_use']['cost'] + $gc_total - $cart['emi_fee'] - $cart['cod_fee'] - $gifting_fee - $cart['shipping_cost']) == $cart_total)){
			$cart['total_matched']	 = 'YES';
		}else{
			$cart['total_matched']	 = 'NO';
			$to = Registry::get('config.error_to_email_ids');
        	$from = Registry::get('settings.Company.company_orders_department');
        	$subject = '****************Fraud Alert*************';
        	$body_html = '<b>Please Check this cart data </b>:<br><br> '.serialize($cart);
        	$body_text = '';
        	$fromName = 'Shopclues.com';        	
        	sendElasticEmail($to, $subject, $body_text, $body_html, $from, $fromName, '');	
		}
		
		/*added by chandan to check the order total*/
		if($cart['total'] < 0 || $cart['subtotal'] < 0 ||  $cart['shipping_cost'] < 0 ||  $cart['emi_fee'] < 0 || ($cart['payment_id'] == '0' && $cart['total']> 0) ){
        	fn_set_notification('E', fn_get_lang_var('Error'), fn_get_lang_var('error_with_order_processing'));
        	$to = Registry::get('config.error_to_email_ids');
        	$from = Registry::get('settings.Company.company_orders_department');
        	$subject = 'Error in order Processing ----- on click of place order.';
        	$body_html = 'Alert!!!!! <br> There is some error with the order<br>
        				 Either cart total is less than zero. <br>
        				 Or cart subtotal is less than zero. <br>        	
        				 Or shipping cost is less than zero. <br>        	
        				 Or emi fees is less than zero. <br>        	        	
        				 '.serialize($cart);
        	$body_text = '';
        	$fromName = 'Shopclues.com';        	
        	sendElasticEmail($to, $subject, $body_text, $body_html, $from, $fromName, '');
			return array(CONTROLLER_STATUS_REDIRECT, 'checkout.checkout&edit_step=step_four');
    	}
       
		// Prevent unauthorized access
		if (empty($cart['user_data']['email'])) {
			return array(CONTROLLER_STATUS_DENIED);
		}

		// Prevent using disabled payment method by challenging HTTP data
		if (isset($cart['payment_id'])) {
			$payment_method_data = fn_get_payment_method_data($cart['payment_id']);
			if (!empty ($payment_method_data['status']) && $payment_method_data['status'] != 'A') {
				return array(CONTROLLER_STATUS_DENIED);
			}
		}

		// Remove previous failed order
		/*if (!empty($cart['failed_order_id']) || !empty($cart['processed_order_id'])) {
			$_order_ids = !empty($cart['failed_order_id']) ? $cart['failed_order_id'] : $cart['processed_order_id'];

			foreach ($_order_ids as $_order_id) {
				fn_delete_order($_order_id);
			}
			$cart['rewrite_order_id'] = $_order_ids;
			unset($cart['failed_order_id'], $cart['processed_order_id']);
		}*/

		// Clean up saved shipping rates
		unset($_SESSION['shipping_rates']);
		if (!empty($_REQUEST['customer_notes'])) {
			$cart['notes'] = $_REQUEST['customer_notes'];
		}

		if (!empty($_REQUEST['payment_info'])) {
			$cart['payment_info'] = $_REQUEST['payment_info'];
		}
		
		if (empty($_REQUEST['payment_info']) && !empty($cart['extra_payment_info'])) {
			$cart['payment_info'] = empty($cart['payment_info']) ? array() : $cart['payment_info'];
			$cart['payment_info'] = array_merge($cart['extra_payment_info'], $cart['payment_info']);
		}
		
		unset($cart['payment_info']['secure_card_number']);

		if (!empty($cart['products'])) {
			foreach ($cart['products'] as $k => $v) {
				$_is_edp = db_get_field("SELECT is_edp FROM ?:products WHERE product_id = ?i", $v['product_id']);
				if (fn_check_amount_in_stock($v['product_id'], $v['amount'], empty($v['product_options']) ? array() : $v['product_options'], $k, $_is_edp, 0, $cart) == false) {
					unset($cart['products'][$k]);
					return array(CONTROLLER_STATUS_REDIRECT, "checkout.cart");
				}
				$exceptions = fn_get_product_exceptions($v['product_id'], true);
				if (!isset($v['options_type']) || !isset($v['exceptions_type'])) {
					$v = array_merge($v, db_get_row('SELECT options_type, exceptions_type FROM ?:products WHERE product_id = ?i', $v['product_id']));
				}
				
				if (!fn_is_allowed_options_exceptions($exceptions, $v['product_options'], $v['options_type'], $v['exceptions_type'])) {
					fn_set_notification('E', fn_get_lang_var('notice'), str_replace('[product]', $v['product'], fn_get_lang_var('product_options_forbidden_combination')));
					unset($cart['products'][$k]);
					
					return array(CONTROLLER_STATUS_REDIRECT, "checkout.cart");
				}
			}
		}

		$_last_order_time = fn_get_session_data('last_order_time');

		/*if (!empty($_last_order_time) && ($_last_order_time + ORDERS_TIMEOUT > TIME)) {
			fn_set_notification('E', fn_get_lang_var('error'), str_replace('[minutes]', round(ORDERS_TIMEOUT / 60, 2), fn_get_lang_var('duplicate_order_warning')));
			if (!empty($auth['order_ids'])) {
				$_o_ids = $auth['order_ids'];
			}
			$last_order_id = empty($auth['user_id']) ? array_pop($_o_ids) : db_get_field("SELECT order_id FROM ?:orders WHERE user_id = ?i ORDER BY order_id DESC", $auth['user_id']);

			return array(CONTROLLER_STATUS_REDIRECT, "orders.details?order_id=$last_order_id");
		}*/

		// Time of placing ordes is saved to avoid duplicate  orders.
		fn_set_session_data('last_order_time', TIME);
		/*Modified by clues dev to remove the blank shipping address issue*/
		
		if(trim($cart['user_data']['s_firstname']) == '' && trim($cart['user_data']['s_lastname']) == '' && trim($cart['user_data']['s_address']) == '' && trim($cart['user_data']['s_city']) == ''){ 
			$cart['user_data']['s_firstname'] 	= $cart['user_data']['b_firstname'];
			$cart['user_data']['s_lastname'] 	= $cart['user_data']['b_lastname'] ;
			$cart['user_data']['s_address'] 	= $cart['user_data']['b_address'] ;
			$cart['user_data']['s_city'] 		= $cart['user_data']['b_city'] ;	
		}
		
		//code by ankur to unset session of gc if it is already present
		if(isset($cart['use_gift_certificates']))
		{
			foreach($cart['use_gift_certificates'] as $k=>$v)
			{
				if(isset($_SESSION[$k]['company_part_for_gc']))
				unset($_SESSION[$k]['company_part_for_gc']);
			}
		}
		
		//end
		
		/*Modified by clues dev to remove the blank shipping address issue*/
		list($order_id, $process_payment) = fn_place_order($cart, $auth);
		
		/*modified by chandan to log cart, request, session*/
		if(Registry::get('config.cart_logging')) {
			$cart_data = serialize($cart);
			$session_data = serialize($_SESSION);
			$request_data = serialize($_REQUEST);
			$checkout_step = 'final step place order';
			$user_id = $_SESSION['auth']['user_id'];
			
			$sql = "insert into clues_carts_history (user_id, cart_data, session_data, request_data, checkout_step, session_id) values
							('".$user_id."','".addslashes($cart_data)."','".addslashes($session_data)."','".addslashes($request_data)."','".$checkout_step."','".session_id()."')";
			db_query($sql);
		}
		/*modified by chandan to log cart, request, session*/
		
                foreach($cart['products'] as $p){
                    $log_data['product_id'][] = $p['product_id'];
                    $log_data['company_id'][] = $p['company_id'];
                }
                $log_data['user_id'] = $cart['user_data']['user_id'];
                $log_data['cart_total'] = $cart_total;
                
                if(count($payment_method_data) > 0){
                    $log_data['payment_processor'] = $payment_method_data['processor'];
                }

                if(count($cart['payment_details']) > 0){
                    $log_data['payment_type'] = $cart['payment_details']['payment_type'];
                    $log_data['payment_option'] = $cart['payment_details']['payment_option'];
                    if($cart['payment_details']['payment_type'] == 'EMI' && isset($cart['payment_details']['name'])){
                        $log_data['emi_type'] = $cart['payment_details']['name'];
                    }
                }
                
                LogMetric::dump_log(array_keys($log_data), array_values($log_data));
                
		if (!empty($order_id)) {
			$view->assign('order_action', fn_get_lang_var('placing_order'));
			$view->display('views/orders/components/placing_order.tpl');
			fn_flush();
			//code by ankur to remove session
            foreach($cart['use_gift_certificates'] as $k=>$v)
			{
				unset($_SESSION[$k]['parent_gc_amount']);
				unset($_SESSION[$k]['company_part_for_gc']);
			}
			//code end
			if (empty($_REQUEST['skip_payment']) && $process_payment == true || (!empty($_REQUEST['skip_payment']) && empty($auth['act_as_user']))) { // administrator, logged in as customer can skip payment
				fn_start_payment($order_id);
			}
			
			fn_order_placement_routines($order_id);
		} else {
			return array(CONTROLLER_STATUS_REDIRECT, "checkout.cart");
		}
 	}

	if ($mode == 'update_steps') {  
        //pincode validation on step two
            if(isset($_REQUEST['user_data']['s_zipcode']))
            {
                if(!preg_match('/^\d{0,9}$/',$_REQUEST['user_data']['s_zipcode']) || strlen($_REQUEST['user_data']['s_zipcode'])!='6')
                {
                    return array(CONTROLLER_STATUS_REDIRECT, "checkout.checkout?edit_step=step_two");
                }
            }
            
            //phone no. validation on step two
            if(isset($_REQUEST['user_data']['s_phone']))
            {
                $pattern='/^([1-9]{1}|[0]{1}[1-9]{1}|(91)[1-9]{1})[0-9]{9}$/';
                if(!preg_match($pattern,$_REQUEST['user_data']['s_phone']) || strlen($_REQUEST['user_data']['s_phone'])!='10')
                {
                    return array(CONTROLLER_STATUS_REDIRECT, "checkout.checkout?edit_step=step_two");
                }
            }

            if(isset($_REQUEST['ship_to']) &&  strlen($_REQUEST['ship_to']) > 0){
                unset($cart['new_cart']);
                unset($cart['multiple_shipping_addresses']);
                unset($_SESSION['multiaddress_viewed']);
                unset($_SESSION['multiaddress_message']);
                return array(CONTROLLER_STATUS_REDIRECT, "checkout.checkout?edit_step=step_two");
            }
            else if(isset($_REQUEST['new_cart']) && count($_REQUEST['new_cart'])>0){
                $cart['new_cart'] = array();
                $cart_product_quantity = array();
                $new_cart_datas = $_REQUEST['new_cart'];

                $multiaddresses = $prev_profile_id = false;
                 $_cart_products = $cart['products'];
                foreach($new_cart_datas as $k=>$new_cart_data){
                    if(!isset($new_cart_data['amount']) || $new_cart_data['amount'] == 0){
                        fn_delete_cart_product($cart, $new_cart_data['cart_id']);
                        unset($new_cart_datas[$k]);
                        continue;
                    }
                    if($prev_profile_id && $prev_profile_id != $new_cart_data['profile_id']){
                        $multiaddresses = true;
                    }
                    $prev_profile_id = $new_cart_data['profile_id'];
                    
                    if(array_key_exists($new_cart_data['cart_id'], $cart_product_quantity)){
                        $cart_product_quantity[$new_cart_data['cart_id']]['amount'] += $new_cart_data['amount'];
                    }else{
                        $cart_product_quantity[$new_cart_data['cart_id']]['amount'] = $new_cart_data['amount'];
                    }
                    $cart_product_quantity[$new_cart_data['cart_id']]['product_id'] = $new_cart_data['product_id'];
                    $cart_product_quantity[$new_cart_data['cart_id']]['product_options'] = $_cart_products[$new_cart_data['cart_id']]['extra']['product_options'];
                    $new_cart_datas[$k]['price'] = $_cart_products[$new_cart_data['cart_id']]['price'];
                    $new_cart_datas[$k]['product_options'] = $_cart_products[$new_cart_data['cart_id']]['product_options'];
                }
                $cart['new_cart']['cart_to_show'] = $new_cart_datas;
                $cart['new_cart']['cart_quantity'] = $cart_product_quantity;
                $cart['multiple_shipping_addresses'] = $multiaddresses;
                
                fn_add_product_to_cart($cart_product_quantity, $cart, $auth, true);
                fn_synchronize_mashipping_cart_with_main_cart($cart);
                fn_save_cart_content($cart, $auth['user_id']);
                fn_calculate_cart_content($cart, $auth,'S',false,'F',true);
                update_cart_cookie();
                if(!$multiaddresses){
                    return array(CONTROLLER_STATUS_REDIRECT, "checkout.checkout?edit_step=step_three");
                }
            }

		$user_data = !empty($_REQUEST['user_data']) ? $_REQUEST['user_data'] : array();
		$_suffix = ".checkout";
                
		unset($user_data['user_type']);
		if (!empty($auth['user_id'])) {
			if (isset($user_data['profile_id'])) {
				if (empty($user_data['profile_id'])) {
					$user_data['profile_type'] = 'S';
				}
				$profile_id = $user_data['profile_id'];

			} elseif (!empty($cart['profile_id'])) {
				$profile_id = $cart['profile_id'];

			} else {
				$profile_id = db_get_field("SELECT profile_id FROM ?:user_profiles WHERE user_id = ?i AND profile_type = 'P'", $auth['user_id']);
			}

			$user_data['user_id'] = $auth['user_id'];
			if($user_data['profile_id'] != '-1'){
				$current_user_data = fn_get_user_info($auth['user_id'], true, $profile_id);
			}else{
				$current_user_data = array();	
			}
			if ($profile_id != NULL) {
				$cart['profile_id'] = $profile_id;
			}

			// Update contact information
			if ($_REQUEST['update_step'] == 'step_one') {
				// Check email
				/*$email_exists = db_get_field("SELECT email FROM ?:users WHERE email = ?s AND user_id != ?i", $user_data['email'], $auth['user_id']);
				if (!empty($email_exists)) {
					fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('error_user_exists'));
					$_suffix .= '?edit_step=step_one';
				} else {
					$user_data = fn_array_merge($current_user_data, $user_data);
					db_query("UPDATE ?:users SET ?u WHERE user_id = ?i", $user_data, $auth['user_id']);
				}*/
			} elseif ($_REQUEST['update_step'] == 'step_two' && !empty($user_data)) {
				
				$user_data = fn_array_merge($current_user_data, $user_data);
				
				Registry::get('settings.General.address_position') == 'billing_first' ? $address_zone = 'b' : $address_zone = 's';
				if (!empty($user_data['firstname']) || !empty($user_data[$address_zone . '_firstname'])) {
					$user_data['firstname'] = empty($user_data['firstname']) && !empty($user_data[$address_zone . '_firstname']) ? $user_data[$address_zone . '_firstname'] : $user_data['firstname'];
				}
				if (!empty($user_data['lastname']) || !empty($user_data[$address_zone . '_lastname'])) {
					$user_data['lastname'] = empty($user_data['lastname']) && !empty($user_data[$address_zone . '_lastname']) ? $user_data[$address_zone . '_lastname'] : $user_data['lastname'];
				}
				if (!empty($user_data['phone']) || !empty($user_data[$address_zone . '_phone'])) {
					$user_data['phone'] = empty($user_data['phone']) && !empty($user_data[$address_zone . '_phone']) ? $user_data[$address_zone . '_phone'] : $user_data['phone'];
				}
				unset($user_data['user_verification_code']);
				db_query("UPDATE ?:users SET ?u WHERE user_id = ?i", $user_data, $auth['user_id']);
			}
			
			// Update billing/shipping information
			if ($_REQUEST['update_step'] == 'step_two') {
				$user_data = fn_array_merge($current_user_data, $user_data);
				
				//  update verified flag if check current address is changed.
				if(count($current_user_data)>1 && count($_REQUEST)>1){					
					$current_address = $current_user_data['s_firstname'] . $current_user_data['s_lastname'] . $current_user_data['s_address'] . $current_user_data['s_address_2'] .	$current_user_data['s_city'] . $current_user_data['s_state']  . $current_user_data['s_zipcode'] . $current_user_data['s_phone'];
				    $request_new_address = $_REQUEST['user_data']['s_firstname'] . $_REQUEST['user_data']['s_lastname'] .	$_REQUEST['user_data']['s_address'] .	$_REQUEST['user_data']['s_address_2'] . $_REQUEST['user_data']['s_city'] . $_REQUEST['user_data']['s_state']  . $_REQUEST['user_data']['s_zipcode'] . $_REQUEST['user_data']['s_phone'] ;
				    if(!fn_are_both_addresses_same($current_address,$request_new_address)){
						$user_data['verified'] = 0;
					}				
				}
				
				!empty($_REQUEST['copy_address']) ? $_REQUEST['ship_to_another'] = '' : $_REQUEST['ship_to_another'] = 'Y';
				
				if (empty($_REQUEST['ship_to_another'])) {
					$profile_fields = fn_get_profile_fields('O');
					fn_fill_address($user_data, $profile_fields);
				}
				if($user_data['profile_id'] == '-1'){
					$user_data['profile_name'] = $user_data['s_firstname'].' '.$user_data['s_lastname'];
					$user_data['profile_type'] = 'S';
				}
				
				if($user_data['b_profile_id'] == '-1'){
					$user_data['profile_name'] = $user_data['b_firstname'].' '.$user_data['b_lastname'];
					$user_data['profile_type'] = 'S';
					$_cus_user_data = $user_data	;
					$_cus_user_data['profile_id'] 	= '';
					$_cus_user_data['s_firstname']	= $_cus_user_data['b_firstname'];
					$_cus_user_data['s_lastname'] 	= $_cus_user_data['b_lastname'];
					$_cus_user_data['s_address'] 	= $_cus_user_data['b_address'];
					$_cus_user_data['s_address_2'] 	= $_cus_user_data['b_address_2'];
					$_cus_user_data['s_city'] 		= $_cus_user_data['b_city'];
					$_cus_user_data['s_county'] 	= $_cus_user_data['b_county'];
					$_cus_user_data['s_state'] 		= $_cus_user_data['b_state'];
					$_cus_user_data['s_country'] 	= $_cus_user_data['b_country'];
					$_cus_user_data['s_zipcode'] 	= $_cus_user_data['b_zipcode'];
					$_cus_user_data['s_phone'] 		= $_cus_user_data['b_phone'];
					db_query("INSERT INTO ?:user_profiles ?e", $_cus_user_data);
				}
				
						
				
                                //$cart['profile_id'] = $profile_id = db_query("REPLACE INTO ?:user_profiles ?e", $user_data);
                                $profile_details = db_get_row("SELECT profile_id, profile_type, user_id FROM ?:user_profiles WHERE profile_id = ?i AND profile_type = '".$user_data['profile_type']."'", $user_data['profile_id']);
                                //db_query("UPDATE ?:companies SET ?u WHERE company_id = ?i", $_data, $company_id);
                                if(empty($profile_details)){
                                    $cart['profile_id'] = $profile_id = db_query("INSERT INTO ?:user_profiles ?e", $user_data);
                                }else{
                                    db_query("UPDATE ?:user_profiles SET ?u WHERE profile_id = ?i", $user_data, $profile_details['profile_id']);
                                    $cart['profile_id'] = $profile_id = $profile_details['profile_id'];
                                }

				fn_set_hook('checkout_profile_update', $cart, $_REQUEST['update_step']);
			}

			// Add/Update additional fields
			if (!empty($user_data['fields'])) {
				fn_store_profile_fields($user_data, array('U' => $auth['user_id'], 'P' => $profile_id), 'UP'); // FIXME
			}		
			
		} elseif (Registry::get('settings.General.disable_anonymous_checkout') != 'Y') {
			if (empty($auth['user_id']) && !empty($_REQUEST['user_data']['email'])) {
				$email_exists = db_get_field("SELECT email FROM ?:users WHERE email = ?s", $_REQUEST['user_data']['email']);
				if (!empty($email_exists)) {
					fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('error_user_exists'));
					return array(CONTROLLER_STATUS_REDIRECT, "checkout.checkout");
				}
			}
			
			if (isset($user_data['fields'])) {
				$fields = fn_array_merge(isset($cart['user_data']['fields']) ? $cart['user_data']['fields'] : array(), $user_data['fields']);
			}
			
			if ($_REQUEST['update_step'] == 'step_two' && !empty($user_data)) {
				Registry::get('settings.General.address_position') == 'billing_first' ? $address_zone = 'b' : $address_zone = 's';
				if (!empty($user_data['firstname']) || !empty($user_data[$address_zone . '_firstname'])) {
					$user_data['firstname'] = empty($user_data['firstname']) && !empty($user_data[$address_zone . '_firstname']) ? $user_data[$address_zone . '_firstname'] : $user_data['firstname'];
				}
				if (!empty($user_data['lastname']) || !empty($user_data[$address_zone . '_lastname'])) {
					$user_data['lastname'] = empty($user_data['lastname']) && !empty($user_data[$address_zone . '_lastname']) ? $user_data[$address_zone . '_lastname'] : $user_data['lastname'];
				}
				if (!empty($user_data['phone']) || !empty($user_data[$address_zone . '_phone'])) {
					$user_data['phone'] = empty($user_data['phone']) && !empty($user_data[$address_zone . '_phone']) ? $user_data[$address_zone . '_phone'] : $user_data['phone'];
				}
			}
                       
			$cart['user_data'] = fn_array_merge($cart['user_data'], $user_data);
			!empty($_REQUEST['copy_address']) ? $_REQUEST['ship_to_another'] = '' : $_REQUEST['ship_to_another'] = 'Y';
			
			// Fill shipping info with billing if needed
			if (empty($_REQUEST['ship_to_another']) && $_REQUEST['update_step'] == 'step_two') {
				$profile_fields = fn_get_profile_fields('O');
				fn_fill_address($cart['user_data'] , $profile_fields);
			}
		}

		if (!empty($_REQUEST['next_step'])) {
			$_suffix .= '?edit_step=' . $_REQUEST['next_step'];
		}
	
		if (!empty($_REQUEST['shipping_ids'])) {
			fn_checkout_update_shipping($cart, $_REQUEST['shipping_ids']);
		}

		/*Modified by chandan to validate the payment method selection*/
                //if ((!empty($_REQUEST['payment_id']))) { 
               
        if (($cart['payment_id'] >= '0' && !empty($cart['payment_option_id']) && !empty($cart['payment_details'])) || ($cart['payment_id'] == '0' && $cart['total'] == '0')) { 

			//$cart['payment_id'] = (int) $_REQUEST['payment_id'];
                        $cart['payment_id'] = (int) $cart['payment_id'];
						if (!empty($_REQUEST['payment_info'])) {
				$cart['extra_payment_info'] = $_REQUEST['payment_info'];
				if (!empty($cart['extra_payment_info']['card_number'])) {
					$cart['extra_payment_info']['secure_card_number'] = preg_replace('/^(.+?)([0-9]{4})$/i', '***-$2', $cart['extra_payment_info']['card_number']);
				}
			} else {
				unset($cart['extra_payment_info']);
			}
			unset($cart['payment_updated']);
			fn_update_payment_surcharge($cart);

			fn_save_cart_content($cart, $auth['user_id']);
		}
		elseif($cart['payment_id'] < '0' && !empty($cart['payment_option_id']) && !empty($cart['payment_details'])){

			/*Modified by chandan to validate the payment method selection*/
			fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('payment_id_is_negative'));
			return array(CONTROLLER_STATUS_REDIRECT, "checkout.checkout");
		}
		elseif($_REQUEST['update_step'] == 'step_three'){

			/*Modified by chandan to validate the payment method selection*/
			fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('payment_not_selected'));
			return array(CONTROLLER_STATUS_REDIRECT, "checkout.checkout");
		}
		
		if (floatval($cart['total']) == 0) {
			unset($cart['payment_updated']);
		}
		
		if(!(($cart['user_data']['b_firstname'] == $cart['user_data']['s_firstname']) AND ($cart['user_data']['b_lastname'] == $cart['user_data']['s_lastname'] ) AND ($cart['user_data']['b_address'] == $cart['user_data']['s_address']) AND ($cart['user_data']['b_address_2'] == $cart['user_data']['s_address_2']) AND ($cart['user_data']['b_city'] == $cart['user_data']['s_city']) AND ($cart['user_data']['b_country'] == $cart['user_data']['s_country']) AND ($cart['user_data']['b_state'] == $cart['user_data']['s_state']) AND ($cart['user_data']['b_zipcode'] == $cart['user_data']['s_zipcode']))){
				$view->assign('shipping_error', 'yes');
				$cart['differ_address'] = 'yes';
		}else{
			$cart['differ_address'] = 'no';
		}
		/*modified by chandan to log cart, request, session*/
		if(Registry::get('config.cart_logging')) {
			$cart_data = serialize($cart);
			$session_data = serialize($_SESSION);
			$request_data = serialize($_REQUEST);
			$checkout_step = $_REQUEST['update_step'];
			$user_id = $_SESSION['auth']['user_id'];
			
			$sql = "insert into clues_carts_history (user_id, cart_data, session_data, request_data, checkout_step, session_id) values
							('".$user_id."','".addslashes($cart_data)."','".addslashes($session_data)."','".addslashes($request_data)."','".$checkout_step."','".session_id()."')";
			db_query($sql);
		}
		/*modified by chandan to log cart, request, session*/
		// Recalculate the cart
		$cart['recalculate'] = true;		
                if($cart['multiple_shipping_addresses'])
                    {
                        $primary_profile_id = db_get_field("SELECT profile_id FROM cscart_user_profiles WHERE user_id =".$auth['user_id']." AND profile_type = 'P'");
                        $cart['profile_id'] = $primary_profile_id;
                    }

	}

	if ($mode == 'create_profile') {

		if (!empty($_REQUEST['order_id']) && !empty($auth['order_ids']) && in_array($_REQUEST['order_id'], $auth['order_ids'])) {

			$order_info = fn_get_order_info($_REQUEST['order_id']);
			$user_data = $_REQUEST['user_data'];

			fn_fill_user_fields($user_data);

			foreach ($user_data as $k => $v) {
				if (isset($order_info[$k])) {
					$user_data[$k] = $order_info[$k];
				}
			}
			if($user_data['company_id'] != '0') {
				$user_data['company_id'] = '0';
			}
			if ($res = fn_update_user(0, $user_data, $auth, true, true)) {
				return array(CONTROLLER_STATUS_REDIRECT, "profiles.update");
			} else {
				$_suffix = '.complete?order_id=' . $_REQUEST['order_id'];
			}
		} else {
			return array(CONTROLLER_STATUS_DENIED);
		}
	}
        if($mode == 'validate_pin_cart')
        {
                    if(isset($_REQUEST['pincode']) && is_numeric($_REQUEST['pincode']) && strlen($_REQUEST['pincode'])=='6')
                    {
                        
                        setcookie("pincode", $_REQUEST['pincode'],time()+3600*24*365,'/','.shopclues.com');
                        foreach ($cart['products'] as $key => $product) 
                        {
                            $is_serviceable = get_servicability_type($cart['products'][$key]['product_id'], $_REQUEST['pincode']);
                            
                            $cart['products'][$key]['is_serviceable'] = $is_serviceable;
                            $cart_products[$key]['is_serviceable'] = $is_serviceable;
                        }
                        $cart['invalid_pin'] = '0'; 
                       // $cart['pin_to_validate'] = $_REQUEST['pincode'];
                    }
                    else
                    {
                       //$cart['invalid_pin'] = '-1'; 
                       setcookie("invalid_pincode", '1',time()+60,'/','.shopclues.com');
                    }
                   return array(CONTROLLER_STATUS_REDIRECT, "checkout.cart");
  
        }
        if($mode == 'express_checkout')
        {
            
                
                //$login = ($auth['user_id']=='0'?FALSE:TRUE);
                $_SESSION['express'] = 'Y';
                
                if(isset($_SESSION['shipping_hash']))
                {
                    unset($_SESSION['shipping_hash']);
                }
                if(isset($cart['coupons']))
                {
                    unset($cart['coupons']);
                }
            
                fn_add_product_to_cart($_REQUEST['product_data'], $cart, $auth);
                
                /*added by chandan to add GC in cart from product page*/
                
                if(isset($_REQUEST['gc_data'])){
                	
                	foreach($_REQUEST['gc_data'] as $gc){
                		$gift_cert_data = array
                		(
                			'recipient' => ($_SESSION['auth']['user_id'] > 0)? $cart['user_data']['email']:Registry::get('config.default_gc_email'),
                			'sender' => ($_SESSION['auth']['user_id'] > 0)? $cart['user_data']['email']:Registry::get('config.default_gc_email'),
                			'amount_type' => 'I',
                			'amount' => $gc,
                			'message' => '',
                			'send_via' => 'E',
                			'email' => ($_SESSION['auth']['user_id'] > 0)? $cart['user_data']['email']:Registry::get('config.default_gc_email'),
                			'template' => 'default.tpl'
                			); 
                		
                		list($gift_cert_id, $gift_cert) = fn_add_gift_certificate_to_cart($gift_cert_data, $auth);
                		
                		if (!empty($gift_cert_id)) {
                			$_SESSION['cart']['gift_certificates'][$gift_cert_id] = $gift_cert;
                		}
                	}
                }
                /*added by chandan to add GC in cart from product page*/
                
                foreach($_REQUEST['product_data'] as $key => $product_data){
                    $_SESSION['exp_lst_prd_id'] = $product_data['product_id'];
                }
                
                if(isset($cart['points_info']['in_use']))
                {
                    unset($cart['points_info']['in_use']);
                }
                if(isset($cart['pending_coupon']))
                {
                    unset($cart['pending_coupon']);
                }
                
                if($ajax)
                {
                    $ext_msg = 'checkout.express_checkout';
                    fn_set_notification('X', '', '', '','',$ext_msg);
                    exit;
                }
                else
                {
                    fn_redirect('checkout.express_checkout');
                }
                
        }
      
      // Delete mode for Post request added by Rahul..
        if ($mode == 'delete' && isset($_REQUEST['cart_id'])) {

        	fn_delete_cart_product($cart, $_REQUEST['cart_id']);

        	if (fn_cart_is_empty($cart) == true) {
        		fn_clear_cart($cart);
        	}
        	fn_synchronize_mashipping_cart_with_main_cart($cart);

        	fn_save_cart_content($cart, $auth['user_id']);
        	/*modified by chandan to log cart, request, session*/
        	if(Registry::get('config.cart_logging')) {
        		$cart_data = serialize($cart);
        		$session_data = serialize($_SESSION);
        		$request_data = serialize($_REQUEST).'server data ' . serialize($_SERVER);
        		$checkout_step = 'product deleted';
        		$user_id = $_SESSION['auth']['user_id'];

        		$sql = "insert into clues_carts_history (user_id, cart_data, session_data, request_data, checkout_step, session_id) values
        		('".$user_id."','".addslashes($cart_data)."','".addslashes($session_data)."','".addslashes($request_data)."','".$checkout_step."','".session_id()."')";
        		db_query($sql);
        	}
        	/*modified by chandan to log cart, request, session*/

        	/*modified by chandan to stop cart calculation on produciton deletion*/
			//$cart['recalculate'] = true;
        	$cart['recalculate'] = false;
        	/*modified by chandan to stop cart calculation on produciton deletion*/
        	/*modified by chandan to return on ajax request on step three*/
        	if(isset($_REQUEST['location']) && $_REQUEST['location'] == "step_three"){
        		$cart['recalculate'] = true;
        		fn_calculate_cart_content($cart, $auth, 'S', true, 'F', true);
        		return array(CONTROLLER_STATUS_REDIRECT, "checkout.checkout&edit_step=step_three");	
				//$_suffix = ".checkout";			
        		/*modified by chandan to return on ajax request on step three*/
        	}elseif (defined('AJAX_REQUEST')) {
        		if ($action == 'from_status') {
        			/*modified by chandan to stop cart calculation on produciton deletion*/
					//fn_calculate_cart_content($cart, $auth, 'S', true, 'F', true);
        			/*modified by chandan to stop cart calculation on produciton deletion*/
        			$view->assign('force_items_deletion', true);
        			$view->display('views/checkout/components/cart_status.tpl');			
        			$view->assign('cart', $cart);
        			if (Registry::get('settings.DHTML.ajax_add_to_cart') != 'Y' && Registry::get('settings.General.redirect_to_cart') == 'Y') {
        				$view->assign('continue_url', (!empty($_REQUEST['redirect_url']) && empty($_REQUEST['appearance']['details_page'])) ? $_REQUEST['redirect_url'] : $_SESSION['continue_url']);
        			}

        			if(Registry::get('config.xbuy_now_popup')){
                                   $msg = $view->display('views/checkout/components/xcart_content_popup.tpl', false);
                                   fn_set_notification('P', fn_get_lang_var($product_cnt > 1 ? 'xproducts_added_to_cart' : 'xproduct_added_to_cart'), $msg, 'I');
                                }else{
                                   $msg = $view->display('views/checkout/components/cart_content_popup.tpl', false); 
                                   fn_set_notification('P', fn_get_lang_var($product_cnt > 1 ? 'products_added_to_cart' : 'product_added_to_cart'), $msg, 'I');
                                }
        			//fn_set_notification('P', fn_get_lang_var($product_cnt > 1 ? 'products_added_to_cart' : 'product_added_to_cart'), $msg, 'I');
        			/*modified by chandan to stop cart calculation on produciton deletion*/
					//$cart['recalculate'] = true;
        			$cart['recalculate'] = false;
        			/*modified by chandan to stop cart calculation on produciton deletion*/			
        			exit;
        		}
        	}

        	return array(CONTROLLER_STATUS_REDIRECT,(!empty($_REQUEST['redirect_mode'])?("checkout." . $_REQUEST['redirect_mode']):"checkout.cart"));

        } 
            if(isset($_REQUEST['new_cart']) && isset($cart['multiple_shipping_addresses']) && $cart['multiple_shipping_addresses'] == true){
                    return array(CONTROLLER_STATUS_REDIRECT, "checkout.checkout?edit_step=step_three");
            }
            else{
                    return array(CONTROLLER_STATUS_OK, "checkout$_suffix");
            }
	}

	//Changes By Megha Sudan
	if($mode == 'add_wholesale_subscription')
 	{
 		
 		$subscription_product = array();
 		$subscription_product[Registry::get('config.ws_subscription_product')]['product_id'] = Registry::get('config.ws_subscription_product');
 		$subscription_product[Registry::get('config.ws_subscription_product')]['amount'] = 1;

 		fn_add_product_to_cart($subscription_product, $cart, $auth);
 		if(!empty($cart))
 		{
 			foreach($cart['products'] as $item_id=>$product_data)
 			{
 				if($product_data['product_id'] == Registry::get('config.ws_subscription_product'))
 				{
 					$cart['pending_coupon'] = !empty($product_data['coupon_code']) ? $product_data['coupon_code'] : '';
 				}

 			}
 		}
		if(isset($cart['pending_coupon']) && empty($cart['pending_coupon']))
 		{
 			unset($cart['pending_coupon']);
 		}
 		return array(CONTROLLER_STATUS_REDIRECT, "checkout.checkout?edit_step=step_three");
 	}

	// modified by clues dev
	// Update products quantity in the cart
	//
        
       if($mode == 'express_checkout')
        {        
	
                if(isset($_REQUEST['ec']) && strtoupper($_REQUEST['ec'])=='N')
                {
                    if(isset($_SESSION['express']))
                    {
                        unset($_SESSION['express']);
                    }
                    fn_redirect('checkout.checkout?edit_step=step_two');
                }
               
                $login = ($auth['user_id']=='0'?FALSE:TRUE);
                
                if(!$login)
                {
                    fn_redirect('checkout.checkout?edit_step=step_one');
                }
                
                $sql = "select * from clues_express_checkout_setup where user_id='".$_SESSION['auth']['user_id']."'";
                $profile = db_get_row($sql);
                
                $sql = "select * from cscart_user_profiles where user_id='".$_SESSION['auth']['user_id']."' and profile_type='P'";
                $exist_profile = db_get_row($sql);
                
                if($exist_profile['s_firstname']=='' && $exist_profile['s_lastname']=='' && $exist_profile['s_address']=='')
                {$exist_profile_flag = false;}
                else
                {
                    $sql = "select order_id from cscart_orders where user_id = '".$_SESSION['auth']['user_id']."' and status not in ('N','F') and payment_id != '0'";
                    $is_order = db_get_field($sql);
                    $exist_profile_flag = ($is_order)?true:false;
                }

                if(empty($profile) && $exist_profile_flag)
                {
                    
                    fn_save_cart_content($cart, $auth['user_id']);
                    //fn_calculate_cart_content($cart, $auth, 'A', true, 'F', true);
                    
                    if(express_check_cod_conditions($cart) == TRUE)
                    {$false_payment_ids = '(0)';}
                    else
                    {$false_payment_ids = '(0,6)';}   
                    
                     $cod_eligible = eligible_for_cod($cart);

        
                    if(isset($_SESSION['auth']['user_id'])){
                        $sql = "select cpop.payment_option_id
                                from clues_payment_option_pgw cpop       
                                join cscart_payments cp on (cpop.payment_gateway_id=cp.payment_id) and cp.status = 'A'
                                join cscart_orders co on (co.payment_option_id = cpop.payment_option_id) and co.emi_id = 0 and co.user_id = ".$_SESSION['auth']['user_id']." and co.payment_id not in $false_payment_ids and co.status not in ('N','F')
                                join clues_payment_options cpo on cpo.payment_option_id = cpop.payment_option_id and cpo.status='A' 
                                where cpop.status = 'A'  
                                order by co.order_id desc, cpop.priority asc limit 0,1";
                        $payment_option_id = db_get_field($sql);

                        if($payment_option_id == ''){
                           $payment_option_id = '56';
                        }
                        $sql = "select cpop.payment_option_pgw_id, cpop.payment_option_id, cpop.payment_gateway_id, cpop.priority, cpop.status, 
                                cpt.name as payment_type, cpt.payment_type_id, cpo.name as 	payment_option,cpt.position
                                from clues_payment_option_pgw cpop join clues_payment_options cpo on (cpop.payment_option_id=cpo.payment_option_id)
                                join clues_payment_types cpt on (cpo.payment_type_id=cpt.payment_type_id)						
                                join cscart_payments cp on (cpop.payment_gateway_id=cp.payment_id) 
                                where cpo.payment_option_id='".$payment_option_id."' order by cpop.priority asc limit 0,1";
                        $prev_payment_data= db_get_row($sql);
                        if($prev_payment_data){
                                $cart['payment_id'] = $prev_payment_data['payment_gateway_id'];
                                $cart['payment_option_id'] = $prev_payment_data['payment_option_id'];
                                $cart['payment_details'] = $prev_payment_data;
                        }
                        if(isset($cart['payment_details']['payment_option_pgw_id']))
                        {
                           $sql = "select failed_status from clues_payment_option_pgw where payment_option_pgw_id = ".$cart['payment_details']['payment_option_pgw_id']."";
                           $failed_status = db_get_field($sql);
                           $cart['payment_failed_status'] = $failed_status;
                        }

                      }
                      
                      foreach ($cart['products'] as $k => $v) {
			$_cproduct = fn_get_cart_product_data($k, $cart['products'][$k], true, $cart, $auth);
			if (empty($_cproduct)) { 
				unset($cart['products'][$k]);
				continue;
			}
			$cart['order_company_id'] = $v['company_id'];
			$cart_products[$k] = $_cproduct;
                        }
                      
                       $cart['promo_cod'] = 'Y';
                       fn_express_apply_promotion($cart, $cart_products, $no_apply_promotion);
                       
                       if(Registry::get('config.isResponsive')==1 && Registry::get('config.default_cod_on_mobile') && $cod_eligible==1 && $cart['promo_cod'] = 'Y'){

                        $payment_option_id = 61;
                        $sql = "select cpop.payment_option_pgw_id, cpop.payment_option_id, cpop.payment_gateway_id, cpop.priority, cpop.status, 
                                            cpt.name as payment_type, cpt.payment_type_id, cpo.name as 	payment_option,cpt.position
                                            from clues_payment_option_pgw cpop join clues_payment_options cpo on (cpop.payment_option_id=cpo.payment_option_id)
                                            join clues_payment_types cpt on (cpo.payment_type_id=cpt.payment_type_id)						
                                            join cscart_payments cp on (cpop.payment_gateway_id=cp.payment_id) 
                                            where cpo.payment_option_id='".$payment_option_id."' order by cpop.priority asc limit 0,1";
                        $prev_payment_data= db_get_row($sql);
                        if($prev_payment_data){
                                $cart['payment_id'] = $prev_payment_data['payment_gateway_id'];
                                $cart['payment_option_id'] = $prev_payment_data['payment_option_id'];
                                $cart['payment_details'] = $prev_payment_data;
                        }
                        if(isset($cart['payment_details']['payment_option_pgw_id']))
                        {
                           $sql = "select failed_status from clues_payment_option_pgw where payment_option_pgw_id = ".$cart['payment_details']['payment_option_pgw_id']."";
                           $failed_status = db_get_field($sql);
                           $cart['payment_failed_status'] = $failed_status;
                        }
                    }
                       
                       if(isset($no_apply_promotion) && $no_apply_promotion!='')
                       {
                          $cart['pending_coupon'] = $no_apply_promotion;
                          $cart['bulk_coupon'] = 'Y';
                       }
                       
                      fn_express_apply_cb($cart);
                      
                      
                      fn_set_notification('W', '', fn_get_lang_var('you_do_not_have_express_setup'));
                      return array(CONTROLLER_STATUS_REDIRECT, 'checkout.checkout&edit_step=step_four');                   
                }
                
                if(empty($profile) && !$exist_profile_flag)
                {
                    fn_set_notification('W', '', fn_get_lang_var('you_do_not_have_express_setup_or_order'));
                    
                    return array(CONTROLLER_STATUS_REDIRECT, "checkout.checkout&edit_step=step_two");
                }
                
                if(!empty($profile))
                {      
                    $user_data = $profile;
                    unset($user_data['id']);
                    unset($user_data['last_update_timestamp']);
                    unset($user_data['last_updated_by']);
                    unset($user_data['status']);
                    unset($user_data['apply_cb']);
                    unset($user_data['apply_promotion']);
                    unset($user_data['timestamp']);
                    unset($user_data['payment_option_id']);

                    $current_user_data = fn_get_user_info($auth['user_id'], true, $exist_profile['profile_id']);

                    $user_data = fn_array_merge($current_user_data,$user_data);
                    $profile_fields = fn_get_profile_fields('O');
                    fn_fill_address($user_data, $profile_fields);
                    $cart['user_data'] = $user_data;
                    $profile['profile_id'] = $exist_profile['profile_id'];
                    $profile['profile_type'] = $exist_profile['profile_type'];

                    
                    $profile_details = db_get_row("SELECT profile_id, profile_type, user_id FROM ?:user_profiles WHERE profile_id = ?i AND profile_type = '".$user_data['profile_type']."'", $user_data['profile_id']);
                    //db_query("UPDATE ?:companies SET ?u WHERE company_id = ?i", $_data, $company_id);
                    if(empty($profile_details)){
                        $cart['profile_id'] = $profile_id = db_query("INSERT INTO ?:user_profiles ?e", $user_data);
                    }else{
                        db_query("UPDATE ?:user_profiles SET ?u WHERE profile_id = ?i", $user_data, $profile_details['profile_id']);
                        $cart['profile_id'] = $profile_id = $profile_details['profile_id'];
                    }
                    
                    // Add/Update additional fields
                    if (!empty($user_data['fields'])) {
                            fn_store_profile_fields($user_data, array('U' => $auth['user_id'], 'P' => $profile_id), 'UP'); // FIXME
                    }
                                
                    fn_save_cart_content($cart, $auth['user_id']);
                    
                     $cod_eligible = eligible_for_cod($cart);

        
                    if(isset($profile['payment_option_id'])){
                            $sql = "select cpop.payment_option_pgw_id, cpop.payment_option_id, cpop.payment_gateway_id, cpop.priority, cpop.status, 
                                            cpt.name as payment_type, cpt.payment_type_id, cpo.name as 	payment_option,cpt.position
                                            from clues_payment_option_pgw cpop 
                                            join clues_payment_options cpo on (cpop.payment_option_id=cpo.payment_option_id) and cpo.status='A'
                                            join clues_payment_types cpt on (cpo.payment_type_id=cpt.payment_type_id)						
                                            join cscart_payments cp on (cpop.payment_gateway_id=cp.payment_id) 
                                            where cpo.payment_option_id='".$profile['payment_option_id']."' order by cpop.priority asc limit 0,1";
                            $prev_payment_data= db_get_row($sql);
                            if($prev_payment_data){
                                    $cart['payment_id'] = $prev_payment_data['payment_gateway_id'];
                                    $cart['payment_option_id'] = $prev_payment_data['payment_option_id'];
                                    $cart['payment_details'] = $prev_payment_data;
                            }
                            if(isset($cart['payment_details']['payment_option_pgw_id']))
                            {
                               $sql = "select failed_status from clues_payment_option_pgw where payment_option_pgw_id = ".$cart['payment_details']['payment_option_pgw_id']."";
                               $failed_status = db_get_field($sql);
                               $cart['payment_failed_status'] = $failed_status;
                            }
                            //echo '<pre>';print_r($prev_payment_data);echo '</pre>';

                    }
                    
                    
                    foreach ($cart['products'] as $k => $v) {
			$_cproduct = fn_get_cart_product_data($k, $cart['products'][$k], true, $cart, $auth);
			if (empty($_cproduct)) {
				unset($cart['products'][$k]);
				continue;
			}
			$cart['order_company_id'] = $v['company_id'];
			$cart_products[$k] = $_cproduct;
                        }
                        
                        $cart['promo_cod'] = 'Y';
                    
                    if($profile['apply_promotion']=='Y')
                    {
                        fn_express_apply_promotion($cart, $cart_products, $no_apply_promotion);
                    }
                    
                    if(Registry::get('config.isResponsive')==1 && Registry::get('config.default_cod_on_mobile') && $cod_eligible==1 && $cart['promo_cod']=='Y'){

                        $payment_option_id = 61;
                        $sql = "select cpop.payment_option_pgw_id, cpop.payment_option_id, cpop.payment_gateway_id, cpop.priority, cpop.status, 
                                            cpt.name as payment_type, cpt.payment_type_id, cpo.name as 	payment_option,cpt.position
                                            from clues_payment_option_pgw cpop join clues_payment_options cpo on (cpop.payment_option_id=cpo.payment_option_id)
                                            join clues_payment_types cpt on (cpo.payment_type_id=cpt.payment_type_id)						
                                            join cscart_payments cp on (cpop.payment_gateway_id=cp.payment_id) 
                                            where cpo.payment_option_id='".$payment_option_id."' order by cpop.priority asc limit 0,1";
                        $prev_payment_data= db_get_row($sql);
                        if($prev_payment_data){
                                $cart['payment_id'] = $prev_payment_data['payment_gateway_id'];
                                $cart['payment_option_id'] = $prev_payment_data['payment_option_id'];
                                $cart['payment_details'] = $prev_payment_data;
                        }
                        if(isset($cart['payment_details']['payment_option_pgw_id']))
                        {
                           $sql = "select failed_status from clues_payment_option_pgw where payment_option_pgw_id = ".$cart['payment_details']['payment_option_pgw_id']."";
                           $failed_status = db_get_field($sql);
                           $cart['payment_failed_status'] = $failed_status;
                        }
                    }
                    
                    if(isset($no_apply_promotion) && $no_apply_promotion!='')
                    {
                          $cart['pending_coupon'] = $no_apply_promotion;
                          $cart['bulk_coupon'] = 'Y';
                    }
                    if($profile['apply_cb']=='Y')
                    {
                        fn_express_apply_cb($cart);
                    }
                    
                    if(empty($prev_payment_data)){
                        
                        if ($_SESSION['express']=='Y') {
                           $cart['express_logging'] = 'exp';
                           unset($_SESSION['express']);
                        }
                        fn_set_notification('W', '', fn_get_lang_var('your_selected_payment_method_now_disable_select_other'));
                        return array(CONTROLLER_STATUS_REDIRECT, "checkout.checkout&edit_step=step_three");
                    }

                    if(Registry::get('config.express_to_four_step')==true)
                    {
                        return array(CONTROLLER_STATUS_REDIRECT, 'checkout.checkout&edit_step=step_four'); 
                    }
                    else
                    {
                            return array(CONTROLLER_STATUS_REDIRECT, 'checkout.start_payment_process');  
                    }
                }
            
            }
			
			if($mode == 'retry_payment_express'){
				
				$login = ($auth['user_id']=='0'? FALSE:TRUE);
				$parent_order = $_REQUEST['order_id'];
				//validate order id
				if(!is_numeric($parent_order)){
					return array(CONTROLLER_STATUS_REDIRECT, "checkout.cart");					
				}	
				
				if(!$login){
					//redirect to login	 return_url=
					$cart = fn_create_retry_cart($parent_order);
					$_SESSION['cart'] = $cart;
					$return_url = urldecode('return_url=dispatch=checkout.retry_payment_express');
					$_SESSION['cart']['reorder_order_id'] = $parent_order;
					return array(CONTROLLER_STATUS_REDIRECT, "checkout.checkout&edit_step=step_one&".$return_url);
				}
				else{					
					
					unset($_SESSION['cart']['reorder_order_id']);
					$cart = fn_create_retry_cart($parent_order,true);
					$cart['user_data'] = retry_get_user_data($parent_order);
					$cart['profile_id'] = $cart['user_data']['profile_id'];
					$_SESSION['cart'] = $cart;
					if($auth['user_id'] == $cart['user_data']['user_id']){
						$cart_products = $cart['products'];
						$no_apply_promotion = '';
						$cart['bulk_coupon'] = 'Y';
						fn_express_apply_promotion($cart, $cart_products, $no_apply_promotion);
						if(isset($no_apply_promotion) && $no_apply_promotion!=''){
							$cart['bulk_coupon'] = 'Y';
							$cart['pending_coupon'] = $no_apply_promotion;
						}
						$_SESSION['cart'] = $cart;
						if($cart['emi_id'] != 0 && ($cart['total'] - $cart['emi_fee']) < Registry::get('config.emi_min_amount')){
							unset($cart['emi_id']);
							unset($cart['emi_fee']);
							unset($cart['payment_id']);
							$cart['payment_option_id'] = '';
							unset($cart['payment_details']);
							$_SESSION['cart'] = $cart;
							$_SESSION['cart']['reorder_express'] = true;
							return array(CONTROLLER_STATUS_REDIRECT, "checkout.checkout&edit_step=step_three");			
						}
						$_SESSION['cart']['reorder_express'] = true;
						return array(CONTROLLER_STATUS_REDIRECT, "checkout.checkout&edit_step=step_four");
					}
					else{
						unset($cart['user_data']);
						$_SESSION['cart']['user_data']['user_id'] = $auth['user_id'];	
						$_SESSION['cart']['reorder_express'] = true;
						return array(CONTROLLER_STATUS_REDIRECT, "checkout.checkout&edit_step=step_two");
					}
					 
				}				
			}
			
            if($mode == 'start_payment_process')
            { 
                 //use for express checkout
                
            	//Changes By Megha Sudan
                $redirect = notify_wholesale_subscription($cart['products'],$_SESSION['auth']['user_id'],Registry::get('config.ws_membership_type'),$edit_step);
				if($redirect)
				{
					return array(CONTROLLER_STATUS_REDIRECT, "checkout.checkout?edit_step=step_three");
				}

                 $cart['express_logging'] = 'exp';
                 
                 $cart['recalculate'] = TRUE;
                 fn_calculate_cart_content($cart, $auth, 'A', true, 'F', true, true);
                 list($order_id, $process_payment) = fn_place_order($cart, $auth);

                 

                 if($cart['total']!='0')
                 {
                    fn_start_payment($order_id);
                 }

                 fn_order_placement_routines($order_id);
            
            }
	if ($mode == 'update_quantity') {
		if (!empty($_REQUEST)) {
			$product_id = $_REQUEST['product_id'];
			$amount = $_REQUEST['qty'];
			$cart_id = $_REQUEST['cart_id'];		
			$cart_products[$cart_id]= array('product_id' => $product_id,'amount' => $amount);
			$cart_products[$cart_id]['product_options']= $_REQUEST['product_options'];
			if (empty($cart_products['amount']) && !isset($cart['products'][$_key]['extra']['parent'])) {
				fn_delete_cart_product($cart, $_key);
			}
			fn_add_product_to_cart($cart_products, $cart, $auth, true);
			fn_save_cart_content($cart, $auth['user_id']);
		}
		/*modified by chandan to return on ajax request on step three*/
		if(isset($_REQUEST['location']) && $_REQUEST['location'] == "step_three"){
			$cart['recalculate'] = true;
			fn_calculate_cart_content($cart, $auth, 'S', true, 'F', true);
			return array(CONTROLLER_STATUS_REDIRECT, "checkout.checkout&edit_step=step_three");	
			//$_suffix = ".checkout";			
		}else{
			/*modified by chandan to return on ajax request on step three*/
		/*Modified by chandan to stop cart calculation on product quantity update*/
		//$cart['recalculate'] = true;
		$cart['recalculate'] = false;
		/*Modified by chandan to stop cart calculation on product quantity update*/
		if (defined('AJAX_REQUEST')) {
			/*Modified by chandan to stop cart calculation on product quantity update*/
			//fn_calculate_cart_content($cart, $auth, 'S', true, 'F', true);
			/*Modified by chandan to stop cart calculation on product quantity update*/
			$view->display('views/checkout/components/checkout_totals.tpl');
			$view->display('views/checkout/components/cart_status.tpl');
			$view->assign('cart', $cart);
			if (Registry::get('settings.DHTML.ajax_add_to_cart') != 'Y' && Registry::get('settings.General.redirect_to_cart') == 'Y') {
				$view->assign('continue_url', (!empty($_REQUEST['redirect_url']) && empty($_REQUEST['appearance']['details_page'])) ? $_REQUEST['redirect_url'] : $_SESSION['continue_url']);
			}
			//$msg = $view->display('views/products/components/product_notification.tpl', false);
			 /*modified by chandan to log cart, request, session*/
			  if(Registry::get('config.cart_logging')) {
					$cart_data = serialize($cart);
					$session_data = serialize($_SESSION);
					$request_data = serialize($_REQUEST).'server data ' . serialize($_SERVER);
					$checkout_step = 'product updated from popup';
					$user_id = $_SESSION['auth']['user_id'];
	
					$sql = "insert into clues_carts_history (user_id, cart_data, session_data, request_data, checkout_step, session_id) values
							('".$user_id."','".addslashes($cart_data)."','".addslashes($session_data)."','".addslashes($request_data)."','".$checkout_step."','".session_id()."')";
					db_query($sql);
			  }
					/*modified by chandan to log cart, request, session*/
                          if(Registry::get('config.xbuy_now_popup')){
                              $msg = $view->display('views/checkout/components/xcart_content_popup.tpl', false);
                              fn_set_notification('P', fn_get_lang_var($product_cnt > 1 ? 'xproducts_added_to_cart' : 'xproduct_added_to_cart'), $msg, 'I');
                          }else{
                              $msg = $view->display('views/checkout/components/cart_content_popup.tpl', false);
                              fn_set_notification('P', fn_get_lang_var($product_cnt > 1 ? 'products_added_to_cart' : 'product_added_to_cart'), $msg, 'I');
                          }
			//fn_set_notification('P', fn_get_lang_var($product_cnt > 1 ? 'products_added_to_cart' : 'product_added_to_cart'), $msg, 'I');
		
			// Recalculate cart when updating the products
			$cart['recalculate'] = false;
			
		}
                
		exit;
		
		}
	}


        
if($mode == 'mashipping'){
        $_SESSION['multiaddress_viewed'] = 'Y';
        $new_cart_structure = array();
	$k=0;
	$product_count = array();
	if(!isset($cart['new_cart']['cart_to_show'])) {
		foreach($cart['products'] as $cart_id=>$cart_product){		
			for($i=1; $i <= $cart_product['amount']; $i++){
				$new_cart_structure[$k]['cart_id'] = $cart_id;
				$new_cart_structure[$k]['product_id'] = $cart_product['product_id'];
				$new_cart_structure[$k]['name'] = $cart_product['name'];
				$new_cart_structure[$k]['amount'] = '1';
				$new_cart_structure[$k]['price'] = $cart_product['price'];
				$new_cart_structure[$k]['product_options'] = $cart_product['product_options'];
				$k++;
			}
			$product_count[$cart_id] = $cart_product['amount'];
		}		
	}else{
		foreach($cart['products'] as $cart_id=>$cart_product){		
			$product_count[$cart_id] = $cart_product['amount'];
		}
		$saved_cart_product_count = array();
		foreach($cart['new_cart']['cart_to_show'] as $saved_cproduct){
			if(array_key_exists($saved_cproduct['cart_id'], $saved_cart_product_count)){
				$saved_cart_product_count[$saved_cproduct['cart_id']] += $saved_cproduct['amount'];
			}else{
				$saved_cart_product_count[$saved_cproduct['cart_id']] = $saved_cproduct['amount'];
			}		
		}
		$product_to_add = array();
		foreach($product_count as $cart_id=>$p_count ){
			if(array_key_exists($cart_id, $saved_cart_product_count)){
				if($saved_cart_product_count[$cart_id] < $p_count){
					$diff = $p_count - $saved_cart_product_count[$cart_id];
					$product_to_add[$cart_id] = $diff;
				}
			}else{
				$product_to_add[$cart_id] = $p_count;		
			}
		}
		
		$k = count($cart['new_cart']['cart_to_show']);
		$new_cart_structure = $cart['new_cart']['cart_to_show'];
		if(count($product_to_add) > 0){
			foreach($product_to_add as $cart_id=>$p_count){		
				for($i=1; $i <= $p_count; $i++){
					$new_cart_structure[$k]['cart_id'] = $cart_id;
					$new_cart_structure[$k]['product_id'] = $cart['products'][$cart_id]['product_id'];
					$new_cart_structure[$k]['name'] = $cart['products'][$cart_id]['name'];
					$new_cart_structure[$k]['amount'] = '1';
					$new_cart_structure[$k]['price'] = $cart['products'][$cart_id]['price'];
					$new_cart_structure[$k]['product_options'] = $cart['products'][$cart_id]['product_options'];
					$k++;
				}
			}
		}
	}
        $cart['new_cart']['cart_to_show'] = $new_cart_structure;
	$view->assign('new_cart_structure', $new_cart_structure);

        $user_id = (isset($cart['user_data']['user_id']) && $cart['user_data']['user_id'] != '') ? $cart['user_data']['user_id'] : $_SESSION['auth']['user_id'];
	$user_profiles = fn_get_user_profiles_data($user_id);
        $profile_keys_required = array('profile_id','profile_type', 'user_id', 's_title', 's_firstname', 's_lastname','s_address','s_address_2','s_city','s_state','s_country','s_zipcode','s_phone','profile_name','to','from','msg');
        $profile_type_keys_required = array('profile_id','profile_type', 'user_id','b_title', 'b_firstname', 'b_lastname','b_address','b_address_2','b_city','b_state','b_country','b_zipcode','b_phone', 's_title', 's_firstname', 's_lastname','s_address','s_address_2','s_city','s_state','s_country','s_zipcode','s_phone','profile_name','to','from','msg');
        $user_profiles_json = array();
        foreach($user_profiles as $pid => $pval){
            if($pval['profile_name'] == 'Main'){
                $main_profile_id = $pval['profile_id'];
            }
            if($pval['profile_type'] == 'P')
                {
                    foreach($profile_type_keys_required as $k){
                    $user_profiles_json[$pval['profile_id']][$k] = htmlspecialchars_decode($pval[$k]);
                    }
                }
            else
                {
                    foreach($profile_keys_required as $k){
                    $user_profiles_json[$pval['profile_id']][$k] = htmlspecialchars_decode($pval[$k]);
                    }
                }
        }
	foreach($_SESSION['multiaddress_message'] as $k =>$value)
        {   
            $user_profiles_json[$k]['to'] = $value['to'];
            $user_profiles_json[$k]['from'] = $value['from'];
            $user_profiles_json[$k]['msg'] = $value['msg'];
        }
        
	$view->assign('user_profiles', $user_profiles);
        $view->assign('user_profiles_json', $user_profiles_json);
        
        foreach($new_cart_structure as $cval){
            $total_product_quantity += $cval['amount'];
            $total_product_price += $cval['amount'] * $cart['products'][$cval['cart_id']]['price'];
        }
        if(Registry::get('config.quantity_discount_flag') == 1)
        {
            $cart_product_ids = array();
            foreach($cart['products'] as $item_id=>$product_data)
            {
                array_push($cart_product_ids,$product_data['product_id']);
            }
            $cart_product_ids = array_values(array_unique($cart_product_ids));
            $product_prices_arr = array();
            if(!empty($cart_product_ids))
            {
                foreach($cart_product_ids as $selected_product_id)
                {
                    $product_prices_arr[$selected_product_id] = db_get_hash_array("SELECT * from cscart_product_prices WHERE product_id=$selected_product_id ORDER by product_id", "lower_limit");
                }
                
            }
            $view->assign("product_prices_arr",$product_prices_arr);
        }
        $view->assign('total_product_quantity', $total_product_quantity);
        $view->assign('total_product_price', $total_product_price);
        $mode='checkout';
        $_SESSION['edit_step'] = 'step_two';
        
}
/*Save/update address in case of multiaddress */
if($mode == 'saveaddress')
{
    $billing_status = $_REQUEST['billing'];
    $profile_name = $_REQUEST['profile_name'];
    $profile_id = $_REQUEST['profile_id'];
    $user_id = $_REQUEST['user_id'];
    $s_fname = $_REQUEST['s_fname'];
    $s_lname = $_REQUEST['s_lname'];
    $s_add = mysql_real_escape_string($_REQUEST['s_add']);    
    $s_add2 = mysql_real_escape_string($_REQUEST['s_add_2']);
    $s_city = $_REQUEST['s_city'];
    $s_country = $_REQUEST['s_country'];
    $s_state = $_REQUEST['s_state'];
    $s_zip = $_REQUEST['s_zip'];
    $s_phone = $_REQUEST['s_phone'];
    $sql_query = "SELECT b_firstname, b_lastname, b_address, b_zipcode FROM cscart_user_profiles WHERE user_id=".$user_id." AND profile_type='P'"; 
    $row = db_get_row($sql_query);
    if($row['b_firstname'] && $row['b_lastname'] && $row['b_address'] && $row['b_zipcode'])
    {
        if($profile_id)
            {
                if($billing_status == 'Y')
                {
                    $query = "UPDATE cscart_user_profiles SET b_firstname='".$s_fname."',b_lastname='".$s_lname."',b_address='".$s_add."',
                    b_address_2='".$s_add2."',b_city='".$s_city."',b_state='".$s_state."',
                    b_country='".$s_country."',b_zipcode='".$s_zip."',b_phone='".$s_phone."',profile_name='".$profile_name."' WHERE profile_id=".$profile_id;
                    $update_flag = 2;
                }
                else
                {
                    $query = "UPDATE cscart_user_profiles SET s_firstname='".$s_fname."',s_lastname='".$s_lname."',s_address='".$s_add."',
                    s_address_2='".$s_add2."',s_city='".$s_city."',s_state='".$s_state."',
                    s_country='".$s_country."',s_zipcode='".$s_zip."',s_phone='".$s_phone."',profile_name='".$profile_name."' WHERE profile_id=".$profile_id;
                }
            }
         else {
               $query = "INSERT INTO cscart_user_profiles(user_id,profile_type,s_firstname,s_lastname,s_address,s_address_2,s_city,s_state,
                    s_country,s_zipcode,s_phone,profile_name) VALUES ('$user_id','S','$s_fname','$s_lname','$s_add','$s_add2',
                    '$s_city','$s_state','IN','$s_zip','$s_phone','$profile_name')";
            }
    }
 else
     {
        if($profile_id)
            {
                $query = "UPDATE cscart_user_profiles SET b_firstname='".$s_fname."',b_lastname='".$s_lname."',b_address='".$s_add."',
                    b_address_2='".$s_add2."',b_city='".$s_city."',b_state='".$s_state."',
                    b_country='".$s_country."',b_zipcode='".$s_zip."',b_phone='".$s_phone."',s_firstname='".$s_fname."',s_lastname='".$s_lname."',s_address='".$s_add."',
                    s_address_2='".$s_add2."',s_city='".$s_city."',s_state='".$s_state."',
                    s_country='".$s_country."',s_zipcode='".$s_zip."',s_phone='".$s_phone."',profile_name='".$profile_name."' WHERE profile_id=".$profile_id;
            }
         else {
               $query = "UPDATE cscart_user_profiles SET b_firstname='".$s_fname."',b_lastname='".$s_lname."',b_address='".$s_add."',
                    b_address_2='".$s_add2."',b_city='".$s_city."',b_state='".$s_state."',
                    b_country='".$s_country."',b_zipcode='".$s_zip."',b_phone='".$s_phone."',s_firstname='".$s_fname."',s_lastname='".$s_lname."',s_address='".$s_add."',
                    s_address_2='".$s_add2."',s_city='".$s_city."',s_state='".$s_state."',
                    s_country='".$s_country."',s_zipcode='".$s_zip."',s_phone='".$s_phone."',profile_name='".$profile_name."' WHERE user_id=".$user_id;
                $update_flag = 1;
               
         }   
     }   
 $x = db_query($query);
 if($update_flag == 1)
 {
    $x = 'update_new';
 }
 elseif($update_flag == 2)
 {
     $x = 'update_billing';
 }
 if($x==1)
 {
     $x = 'update';
 }
 echo $x;
 exit;
}
if($mode == 'savemessage')
{
        $_SESSION['multiaddress_message'][$_REQUEST['profile']]['to'] = $_REQUEST['msg_to'];
        $_SESSION['multiaddress_message'][$_REQUEST['profile']]['from'] = $_REQUEST['msg_from'];
        $_SESSION['multiaddress_message'][$_REQUEST['profile']]['msg']= $_REQUEST['msg_desc'];
        echo 1;
        exit;
}
if($mode == 'removemessage')
{
        unset($_SESSION['multiaddress_message'][$_REQUEST['profile']]);
        echo 1;
        exit;
}
//end code by munish
if ($mode == 'get_service_status') {
        
        $is_servicable = get_servicability_type($_REQUEST['product_id'], $_REQUEST['pin_code']);
        echo $is_servicable;                
exit;
}
/*Gift Wrap it.*/
if ($mode == 'gift_wrap_it') {
		$msg = $view->display('views/checkout/components/gift_wrap.tpl', false);
		fn_set_notification('P', fn_get_lang_var('gift_it_for_me'), $msg, 'I');
		// Recalculate cart when updating the products
		$cart['recalculate'] = true;
	exit;
}

/*remove Gift Wrap it.*/
if ($mode == 'remove_gift_wrap') {
	
	$cart['gifting']['gift_it'] = 'N';		
	// Recalculate cart when updating the products
	$cart['recalculate'] = true;
	
	//
	fn_save_cart_content($cart, $auth['user_id']);
	fn_calculate_cart_content($cart, $auth, 'S', true, 'F', true);
	return array(CONTROLLER_STATUS_REDIRECT, "checkout.checkout&edit_step=step_three");
	//$_suffix = ".checkout";//die("chandan");
}



//
	// Delete discount coupon
	//
	if ($mode == 'delete_coupon') {
		unset($cart['coupons'][$_REQUEST['coupon_code']], $cart['pending_coupon']);
		unset($_SESSION['cart']['custom_coupon']);
                if($cart['sor'] == 'Y'){
                    $cart['sor'] = 'N';
                }
		$cart['recalculate'] = true;
		return array(CONTROLLER_STATUS_OK);
	}

if (empty($mode) || ($_SERVER['REQUEST_METHOD'] != 'POST' && in_array($mode, array('customer_info', 'summary')) && !defined('AJAX_REQUEST'))) {
	$redirect_mode = empty($_REQUEST['redirect_mode']) ? 'checkout' : $_REQUEST['redirect_mode'];
	return array(CONTROLLER_STATUS_REDIRECT, "checkout." . $redirect_mode);
       
}

$payment_methods = fn_prepare_checkout_payment_methods($cart, $auth);
if (((true == fn_cart_is_empty($cart) && !isset($force_redirection)) || empty($payment_methods)) && !in_array($mode, array('clear', 'delete', 'cart', 'update', 'apply_coupon', 'shipping_estimation', 'update_shipping', 'complete'))) {
	if (empty($payment_methods)) {
		fn_set_notification('W', fn_get_lang_var('notice'),  fn_get_lang_var('cannot_proccess_checkout_without_payment_methods'), 'K', 'no_payment_notification');
	} else {
		/*Modified by clues dev to log empty cart error*/
		$content = date('Y-m-d h:i:s')."\r\n+++++++++++++".'SERVER variable : '.serialize($_SERVER)."\r\n".'user session : '.serialize($_SESSION['auth'])."\r\n++++++++++++";
		log_to_file('cart_empty_error',$content);
		/*Modified by clues dev to log empty cart error*/
		fn_set_notification('W', fn_get_lang_var('cart_is_empty'),  fn_get_lang_var('cannot_proccess_checkout'));
	}
	$force_redirection = "checkout.cart";
	if (defined('AJAX_REQUEST')) {
		Registry::get('ajax')->assign('force_redirection', $force_redirection);
		exit;
	} else {
		return array(CONTROLLER_STATUS_REDIRECT, $force_redirection);
	}
}

if (($mode == 'customer_info' || $mode == 'checkout') && Registry::get('settings.General.min_order_amount_type') == "P" && Registry::get('settings.General.min_order_amount') > $cart['subtotal']) {
	$view->assign('value', Registry::get('settings.General.min_order_amount'));
	$min_amount = $view->display('common_templates/price.tpl', false);
	fn_set_notification('W', fn_get_lang_var('notice'), fn_get_lang_var('text_min_products_amount_required') . ' ' . $min_amount);
	return array(CONTROLLER_STATUS_REDIRECT, "checkout.cart");
}

if ($mode == 'customer_info' || $mode == 'checkout' || $mode == 'summary') {
	if (Registry::get('settings.General.checkout_redirect') == 'Y') {
		fn_check_redirect_to_cart();
	}
}

//Cart Items
if ($mode == 'cart') {
    
        if(isset($_SESSION['express']))
        {
            unset($_SESSION['express']);
        }
    
        if($_COOKIE['invalid_pincode']=='1')
        {
           $view->assign('invalid_pin','-1');
           setcookie('invalid_pincode','1',time()-7200,'/','.shopclues.com');
        }

        if(isset($cart[multiple_shipping_addresses]) && $cart[multiple_shipping_addresses]==1 )
        {
                return array(CONTROLLER_STATUS_REDIRECT, "checkout.mashipping");
        }

	list ($cart_products, $_SESSION['shipping_rates']) = fn_calculate_cart_content($cart, $auth, Registry::get('settings.General.estimate_shipping_cost') == 'Y' ? 'A' : 'S', true, 'F', true);

	fn_gather_additional_products_data($cart_products, array('get_icon' => true, 'get_detailed' => true, 'get_options' => true, 'get_discounts' => false));

	// [Breadcrumbs]
	fn_add_breadcrumb(fn_get_lang_var('cart_contents'));
	// [/Breadcrumbs]

	fn_update_payment_surcharge($cart);

	$cart_products = array_reverse($cart_products, true);
	$view->assign('cart_products', $cart_products);
	$view->assign('shipping_rates', $_SESSION['shipping_rates']);

	// Check if any outside checkout is enbaled
	if (fn_cart_is_empty($cart) != true) {
		$checkout_buttons = fn_get_checkout_payment_buttons($cart, $cart_products, $auth);
		if (!empty($checkout_buttons)) {
			$view->assign('checkout_add_buttons', $checkout_buttons, false);
		} elseif (empty($payment_methods) && !fn_notification_exists('E', 'no_payment_notification')) {
			fn_set_notification('W', fn_get_lang_var('notice'),  fn_get_lang_var('cannot_proccess_checkout_without_payment_methods'));
		}
	}

// Step 1/2: Customer information
} elseif ($mode == 'customer_info') {

	if (Registry::get('settings.General.approve_user_profiles') == 'Y' && Registry::get('settings.General.disable_anonymous_checkout') == 'Y' && empty($auth['user_id'])) {
		fn_set_notification('W', fn_get_lang_var('warning'), fn_get_lang_var('text_anonymous_checkout'));

		return array(CONTROLLER_STATUS_REDIRECT, "checkout.cart");
	}

	$cart['profile_id'] = empty($cart['profile_id']) ? 0 : $cart['profile_id'];
	if (!empty($cart['user_data']['profile_id']) && $cart['profile_id'] != $cart['user_data']['profile_id']) {
		$cart['profile_id'] = $cart['user_data']['profile_id'];
	}
	$profile_fields = fn_get_profile_fields('O');

	//Get user profiles
	if (Registry::get('settings.General.user_multiple_profiles') == 'Y') {
		//$user_profiles = fn_get_user_profiles($auth['user_id']);
		$user_profiles = fn_get_user_profiles_data($auth['user_id']);
		$view->assign('user_profiles', $user_profiles);
	}

	//Get countries and states
	$view->assign('countries', fn_get_countries(CART_LANGUAGE, true));
	$view->assign('states', fn_get_all_states());
	$view->assign('usergroups', fn_get_usergroups('C', CART_LANGUAGE));

	// CHECK ME!!!
	$_SESSION['saved_post_data'] = empty($_SESSION['saved_post_data']) ? array() : $_SESSION['saved_post_data'];
	$saved_post_data = & $_SESSION['saved_post_data'];
	unset($_SESSION['saved_post_data']);

	if (!empty($saved_post_data['user_data'])) {
		$view->assign('saved_user_data', $saved_post_data['user_data']);
		$view->assign('ship_to_another', !empty($saved_post_data['ship_to_another']));
	}

	if (!empty($_REQUEST['login_type'])) {
		$view->assign('login_type', $_REQUEST['login_type']);
	}

	// Change user profile
	if (!empty($auth['user_id']) && (empty($cart['user_data']) || (!empty($_REQUEST['profile_id']) && $cart['profile_id'] != $_REQUEST['profile_id']) || (!empty($_REQUEST['profile']) && $_REQUEST['profile'] == 'new'))) {
		if (!empty($_REQUEST['profile_id'])) {
			$cart['profile_id'] = $_REQUEST['profile_id'];
		}

		if (!empty($_REQUEST['profile']) && $_REQUEST['profile'] == 'new') {
			$cart['profile_id'] = 0;
		}

		$cart['user_data'] = fn_get_user_info($auth['user_id'], empty($_REQUEST['profile']), $cart['profile_id']);
	}

	if (!empty($cart['user_data'])) {
		$cart['ship_to_another'] = fn_check_shipping_billing($cart['user_data'], $profile_fields);
	}

	$titles = fn_get_static_data_section('T', false, true);
	$view->assign('titles', $titles);


} elseif($mode == 'validate_pin')
        {
                    if(isset($_REQUEST['pincode']) && is_numeric($_REQUEST['pincode']) && strlen($_REQUEST['pincode'])=='6')
                    {
                        
                        setcookie("pincode", $_REQUEST['pincode'],time()+3600*24*365,'/','.shopclues.com');
                        $_COOKIE['pincode'] = $_REQUEST['pincode'];
                        foreach ($cart['products'] as $key => $product) 
                        {
                            $is_serviceable = get_servicability_type($cart['products'][$key]['product_id'], $_REQUEST['pincode']);
                            
                            $cart['products'][$key]['is_serviceable'] = $is_serviceable;
                            $cart_products[$key]['is_serviceable'] = $is_serviceable;
                        }
                      //  $cart['pin_to_validate'] = $_REQUEST['pincode'];
                        $view->assign('pincode_var', $_REQUEST['pincode']);
                       // $cart['invalid_pin'] = '0';
                    }
                    else
                    {
                       $view->assign('invalid_pin','-1');
                    }
                    $cart['recalculate'] = false;
                    
                    if (defined('AJAX_REQUEST')) 
                    {
                        
                        $view->display('views/checkout/components/checkout_totals.tpl');
			$view->assign('cart', $cart);
			if (Registry::get('settings.DHTML.ajax_add_to_cart') != 'Y' && Registry::get('settings.General.redirect_to_cart') == 'Y') {
				$view->assign('continue_url', (!empty($_REQUEST['redirect_url']) && empty($_REQUEST['appearance']['details_page'])) ? $_REQUEST['redirect_url'] : $_SESSION['continue_url']);
			}
                        if(Registry::get('config.xbuy_now_popup')){
                            $msg = $view->display('views/checkout/components/xcart_content_popup.tpl', false);
                            fn_set_notification('P', fn_get_lang_var($product_cnt > 1 ? 'xproducts_added_to_cart' : 'xproduct_added_to_cart'), $msg, 'I');
                        }else{
                            $msg = $view->display('views/checkout/components/cart_content_popup.tpl', false);
                            fn_set_notification('P', fn_get_lang_var($product_cnt > 1 ? 'products_added_to_cart' : 'product_added_to_cart'), $msg, 'I');
                        }
			//fn_set_notification('P', fn_get_lang_var($product_cnt > 1 ? 'products_added_to_cart' : 'product_added_to_cart'), $msg, 'I');
		
			$cart['recalculate'] = false;
                    }
                    exit;
        } // Step 3: Shipping and payment methods
elseif ($mode == 'checkout') {
    if( isset($cart['gift_certificates']) && count($cart['gift_certificates']) > 0)
    {
        $is_gift_certificate = true;
    }
    $view->assign('is_gift_certificate',$is_gift_certificate);
	
    $profile_fields = fn_get_profile_fields('O');

	// Array notifying that one or another step is completed.
	$completed_steps = array();
        
        
	
	// Array responsible for what step has editing status
	$edit_step = !empty($_REQUEST['edit_step']) ? $_REQUEST['edit_step'] : (!empty($_SESSION['edit_step']) ? $_SESSION['edit_step'] : '');
        if($_REQUEST['edit_step'] == 'step_four')
        {            
            if ($_SESSION['express']=='Y') {
               $cart['express_logging'] = 'exp';
               unset($_SESSION['express']);
            }elseif(!isset($_SESSION['express']) && $cart['express_logging']=='exp'){
                $cart['express_logging'] = 'noexp';
            }
            
        }



	$cart['user_data'] = !empty($cart['user_data']) ? $cart['user_data'] : array();
	/*added by chandan to replace default gc email id with login user email id*/
	
	if($edit_step == 'step_two') {
            if($_REQUEST['dispatch'] == 'checkout.checkout' && $_REQUEST['edit_step'] == 'step_two'){
                if(isset($cart['multiple_shipping_addresses']) && $cart['multiple_shipping_addresses'] == true){
                    if($_REQUEST['change_billing']=='Y'){
                        return array(CONTROLLER_STATUS_REDIRECT, "checkout.mashipping&change_billing=Y");
                    }
                    else {
                        return array(CONTROLLER_STATUS_REDIRECT, "checkout.mashipping");
                    }
                }
            }
	}
	
	if($edit_step == 'step_three' || $edit_step == 'step_four') { 

			
		//Changes By Megha Sudan
	
		$redirect = notify_wholesale_subscription($cart['products'],$_SESSION['auth']['user_id'],Registry::get('config.ws_membership_type'),$edit_step);
		
		if($redirect)
		{
			return array(CONTROLLER_STATUS_REDIRECT, "checkout.checkout?edit_step=step_three");
		}
	
    	//echo "Here";die();
		if(isset($cart['processed_order_id'])){
			unset($cart['processed_order_id']);	
			return array(CONTROLLER_STATUS_REDIRECT, "checkout.checkout?edit_step=step_three");
		}
	}
	
        if(isset($cart['payment_details']['payment_option_pgw_id']))
        {
           $sql = "select failed_status from clues_payment_option_pgw where payment_option_pgw_id = ".$cart['payment_details']['payment_option_pgw_id']."";
           $failed_status = db_get_field($sql);
           $cart['payment_failed_status'] = $failed_status;
        }
        elseif(isset($cart['payment_details']['id']))
        {
           $sql = "select failed_status from clues_payment_options_emi_pgw where id = ".$cart['payment_details']['id']."";
           $failed_status = db_get_field($sql);
           $cart['payment_failed_status'] = $failed_status;
        }
	
	//-------------Send COD confirmation code via SMS----------------------
	if((isset($cart['payment_id']) && $cart['payment_id'] == '6'  && $_REQUEST['edit_step']=='step_four' && empty($cart['user_data']['verified']) && !is_int($_SESSION['cod_verification_code']) &&  isset($cart['user_data']['s_phone']) && Registry::get('config.isResponsive')==0) || (isset($cart['payment_id']) && $cart['payment_id'] == '6'  && $_REQUEST['edit_step']=='step_four' && empty($cart['user_data']['verified']) && !is_int($_SESSION['cod_verification_code']) &&  isset($cart['user_data']['s_phone']) && Registry::get('config.isResponsive')==1  && Registry::get('config.display_otp_captcha'))){			
	
                        $code= rand(1000,9999);$response_send_sms="";
			$response_send_sms=fn_send_sms($cart['user_data']['s_phone'],15,$code);//params mobile,templete id, variables
			
                        if(!empty($response_send_sms) &&  $response_send_sms=='Sent.'){				 
				$_SESSION['cod_verification_code'] = $code;
			}else{
				unset($_SESSION['cod_verification_code']);
			}
					
	}else if($_REQUEST['edit_step']=='step_four' && $cart['payment_id'] != '6'){
			unset($_SESSION['cod_verification_code']);
	}elseif($_REQUEST['edit_step']=='step_four' && $cart['payment_id'] == '6' && Registry::get('config.isResponsive')==1 && !Registry::get('config.display_otp_captcha')){
            unset($_SESSION['cod_verification_code']);
        }	
	//--------------end --Send COD confirmation code via SMS-------------
	
	if(isset($cart['user_data']) && isset($cart['user_data']['email']) && $cart['user_data']['email'] != '' && isset($cart['gift_certificates'])){
		
		foreach($cart['gift_certificates'] as $k=>$gift_certificate){
			if($cart['gift_certificates'][$k]['recipient'] == Registry::get('config.default_gc_email') || $cart['gift_certificates'][$k]['sender'] == Registry::get('config.default_gc_email') || $cart['gift_certificates'][$k]['email'] == Registry::get('config.default_gc_email')){
				$cart['gift_certificates'][$k]['recipient'] = $cart['user_data']['email'];
				$cart['gift_certificates'][$k]['sender'] = $cart['user_data']['email'];
				$cart['gift_certificates'][$k]['email'] = $cart['user_data']['email'];
			}
		}
	}
	//echo '<pre>';print_r($auth);die;
	/*added by chandan to replace default gc email id with login user email id*/
	if (!empty($auth['user_id'])) {

		//if the error occurred during registration, but despite this, the registration was performed, then the variable should be cleared.
		unset($_SESSION['failed_registration']);

		if (!empty($_REQUEST['profile_id'])) {
			$cart['profile_id'] = $_REQUEST['profile_id'];
		
		} elseif (!empty($_REQUEST['profile']) && $_REQUEST['profile'] == 'new') {
			$cart['profile_id'] = 0;
		
		} elseif (empty($cart['profile_id'])) {
			$cart['profile_id'] = db_get_field("SELECT profile_id FROM ?:user_profiles WHERE user_id = ?i AND profile_type='P'", $auth['user_id']);
		}

		// Here check the previous and the current checksum of user_data - if they are different, recalculate the cart.
		$current_state = fn_crc32(serialize($cart['user_data']));

		$cart['user_data'] = fn_get_user_info($auth['user_id'], empty($_REQUEST['profile']), $cart['profile_id']);

		if ($current_state != fn_crc32(serialize($cart['user_data']))) {
			$cart['recalculate'] = true;
		}

	} else {
		if (!empty($_SESSION['saved_post_data']) && !empty($_SESSION['saved_post_data']['user_data'])) {
			$_SESSION['failed_registration'] = true;
			$_user_data = $_SESSION['saved_post_data']['user_data'];
			unset($_SESSION['saved_post_data']);
		} else {
			unset($_SESSION['failed_registration']);

		}

		$view->assign('login_type', empty($_REQUEST['login_type']) ? 'login' : $_REQUEST['login_type']);

		fn_add_user_data_descriptions($cart['user_data']);

		if (!empty($_REQUEST['action'])) {
			$view->assign('checkout_type', $_REQUEST['action']);
		}
	}
	
	fn_get_default_credit_card($cart, !empty($_user_data) ? $_user_data : $cart['user_data']);

	if (!empty($cart['extra_payment_info'])) {
		$cart['payment_info'] = empty($cart['payment_info']) ? array() : $cart['payment_info'];
		$cart['payment_info'] = array_merge($cart['payment_info'], $cart['extra_payment_info']);
	}
	
	$view->assign('user_data', !empty($_user_data) ? $_user_data : $cart['user_data']);
	$contact_info_population = fn_check_profile_fields_population($cart['user_data'], 'E', $profile_fields);
	$view->assign('contact_info_population', $contact_info_population);

	// Check fields population on first and second steps
	if ($contact_info_population == true && empty($_SESSION['failed_registration'])) {
		if ($edit_step != 'step_one' && !fn_check_profile_fields_population($cart['user_data'], 'C', $profile_fields)) {
			fn_set_notification('W', fn_get_lang_var('notice'), fn_get_lang_var('text_fill_the_mandatory_fields'));
			
			return array(CONTROLLER_STATUS_REDIRECT, "checkout.checkout?edit_step=step_one");
		}
		
		$completed_steps['step_one'] = true;
	
		// All mandatory Billing address data exist.
		$billing_population = fn_check_profile_fields_population($cart['user_data'], 'B', $profile_fields);
		$view->assign('billing_population', $billing_population);

		if ($billing_population == true || empty($profile_fields['B'])) {
			// All mandatory Shipping address data exist.
			$shipping_population = fn_check_profile_fields_population($cart['user_data'], 'S', $profile_fields);
			$view->assign('shipping_population', $shipping_population);

			if ($shipping_population == true || empty($profile_fields['S'])) {
				$completed_steps['step_two'] = true;
			}
		}
		
	}

	// Define the variable only if the profiles have not been changed and settings.General.user_multiple_profiles == Y.
	if (fn_need_shipping_recalculation($cart) == false && (!empty($_SESSION['shipping_rates']) && (Registry::get('settings.General.user_multiple_profiles') != "Y" || (Registry::get('settings.General.user_multiple_profiles') == "Y" && ((isset($user_data['profile_id']) && empty($user_data['profile_id'])) || (!empty($user_data['profile_id']) && $user_data['profile_id'] == $cart['profile_id'])))) || (empty($_SESSION['shipping_rates']) && Registry::get('settings.General.user_multiple_profiles') == "Y" && isset($user_data['profile_id']) && empty($user_data['profile_id'])))) {
		define('CACHED_SHIPPING_RATES', true);
	}
	
	if(trim($cart['user_data']['s_firstname']) == '' || trim($cart['user_data']['s_lastname']) == '' || trim($cart['user_data']['s_address']) == '' || trim($cart['user_data']['s_city']) == '' || trim($cart['user_data']['s_state']) == '' || trim($cart['user_data']['s_country']) == '' || trim($cart['user_data']['s_zipcode']) == ''){ 
		$cart['user_data']['s_firstname'] 	= $cart['user_data']['b_firstname'];
		$cart['user_data']['s_lastname'] 	= $cart['user_data']['b_lastname'] ;
		$cart['user_data']['s_address'] 	= $cart['user_data']['b_address'] ;
		$cart['user_data']['s_address_2'] 	= $cart['user_data']['b_address_2'] ;
		$cart['user_data']['s_city'] 		= $cart['user_data']['b_city'] ;	
		$cart['user_data']['s_state'] 		= $cart['user_data']['b_state'] ;	
		$cart['user_data']['s_country'] 	= $cart['user_data']['b_country'] ;	
		$cart['user_data']['s_zipcode'] 	= $cart['user_data']['b_zipcode'] ;	
		$cart['user_data']['s_phone'] 		= $cart['user_data']['b_phone'] ;
	}

	/*added by Rahul to check nss pincode*/
        if(($edit_step == 'step_two' || $edit_step == 'step_three') && Registry::get('config.show_nss_alert') && $cart['user_data']['s_zipcode']!='') {
        	$cart['nss_on_cod'] = 'Y';
        	foreach($cart['products'] as $product){
        		$is_serviceable_nss = get_servicability_type($product['product_id'],$cart['user_data']['s_zipcode']);
        		if($is_serviceable_nss == 0)
        		{
        			$cart['nss_on_cod'] = 'N';
        		}
        	}
        }

	$cart_not_calculate_steps = array('step_one', 'step_two');
	if(!in_array($_REQUEST['edit_step'], $cart_not_calculate_steps)) {		
		list ($cart_products, $_SESSION['shipping_rates']) = fn_calculate_cart_content($cart, $auth, !empty($completed_steps['step_two']) ? 'A' : 'S', true, 'F');
	}
	/*Modified by clues dev to stop user at address section when landing directly at checkout*/
	//if(!isset($_REQUEST['edit_step'])){
		//$completed_steps['step_two'] = false;
	//}
	/*Modified by clues dev to stop user at address section when landing directly at checkout*/

	// if address step is completed, check if shipping step is completed
	if (!empty($completed_steps['step_two'])) {
		$completed_steps['step_three'] = true;
	}

	// If shipping step is completed, assume that payment step is completed too
	if (!empty($completed_steps['step_three']) && empty($cart['payment_updated'])) {
		$completed_steps['step_four'] = true;
	} elseif (!empty($completed_steps['step_three']) && $edit_step == 'step_four') {
		fn_set_notification('N', fn_get_lang_var('notice'), fn_get_lang_var('payment_method_was_changed'));
	}

	if (!empty($cart['shipping_failed']) || !empty($cart['company_shipping_failed'])) {
		$completed_steps['step_four'] = false;
		fn_set_notification('W', fn_get_lang_var('warning'), fn_get_lang_var('text_no_shipping_methods'));
	}
	/*if(!(($cart['user_data']['b_firstname'] == $cart['user_data']['s_firstname']) AND ($cart['user_data']['b_lastname'] == $cart['user_data']['s_lastname'] ) AND ($cart['user_data']['b_address'] == $cart['user_data']['s_address']) AND ($cart['user_data']['b_address_2'] == $cart['user_data']['s_address_2']) AND ($cart['user_data']['b_city'] == $cart['user_data']['s_city']) AND ($cart['user_data']['b_country'] == $cart['user_data']['s_country']) AND ($cart['user_data']['b_state'] == $cart['user_data']['s_state']) AND ($cart['user_data']['b_zipcode'] == $cart['user_data']['s_zipcode']))){
		$view->assign('shipping_error', 'yes');
		$cart['differ_address'] = 'yes';
	}else{
		$cart['differ_address'] = 'no';
	}*/
//// Added by Sudhir code

	if($completed_steps['step_three'] == 'true'){
			if(isset($cart['payment_id'])){
				if($cart['payment_id'] == '6'){
					if(!(($cart['user_data']['b_firstname'] == $cart['user_data']['s_firstname']) AND ($cart['user_data']['b_lastname'] == $cart['user_data']['s_lastname'] ) AND ($cart['user_data']['b_address'] == $cart['user_data']['s_address']) AND ($cart['user_data']['b_address_2'] == $cart['user_data']['s_address_2']) AND ($cart['user_data']['b_city'] == $cart['user_data']['s_city']) AND ($cart['user_data']['b_country'] == $cart['user_data']['s_country']) AND ($cart['user_data']['b_state'] == $cart['user_data']['s_state']) AND ($cart['user_data']['b_zipcode'] == $cart['user_data']['s_zipcode']))){
						$view->assign('shipping_error', 'yes');
						$cart['shipping_error'] = 'yes';
						$cart['differ_address'] = 'yes';
					}
				}else
				{
					$view->assign('shipping_error', 'no');
					$cart['shipping_error'] = 'no';
				}
			}
	}
//// Added by Sudhir code end here

	// If shipping methods changed and shipping step is completed, display notification
	$shipping_hash = '';
	
	if (!empty($_SESSION['shipping_rates'])) {
		$rates = $_SESSION['shipping_rates'];
		foreach ($rates as $shipping_id => $shipping) {
			unset($rates[$shipping_id]['packages_info']);
			unset($rates[$shipping_id]['taxes']);
			if (!empty($rates[$shipping_id]['taxes'])) {
				ksort($rates[$shipping_id]['taxes']);
			}
			ksort($rates[$shipping_id]['rates']);
		}
		ksort($rates);
		$shipping_hash = md5(serialize($rates));
	}
	
	if (!empty($_SESSION['shipping_hash']) && $_SESSION['shipping_hash'] != $shipping_hash && !empty($completed_steps['step_three'])) {
		//fn_set_notification('W', fn_get_lang_var('important'), fn_get_lang_var('text_shipping_rates_changed'));
		
		if ($edit_step == 'step_four') {
			fn_redirect('checkout.checkout?edit_step=step_three');
		}
	}
	
	$_SESSION['shipping_hash'] = $shipping_hash;

	fn_gather_additional_products_data($cart_products, array('get_icon' => true, 'get_detailed' => true, 'get_options' => true, 'get_discounts' => false));

	/*if (false !=($first_method = reset($payment_methods)) && empty($cart['payment_id']) && floatval($cart['total']) != 0) {
		$cart['payment_id'] = $first_method['payment_id'];
		$completed_steps['step_four'] = false;
	}*/
	
	if (empty($cart['payment_id'])) {
		$cart['payment_id'] = '-1';
	}
	
	if (floatval($cart['total']) == 0) {
		$cart['payment_id'] = 0;
	}

	if (!empty($cart['payment_id'])) {
		$payment_info = fn_get_payment_method_data($cart['payment_id']);
		$view->assign('payment_info', $payment_info);
		
		if (!empty($payment_info['params']['iframe_mode']) && $payment_info['params']['iframe_mode'] == 'Y') {
			$view->assign('iframe_mode', true);
		}
	}

        $cod_eligible = eligible_for_cod($cart);

        
        if($edit_step == 'step_three' && Registry::get('config.isResponsive')==1 && Registry::get('config.default_cod_on_mobile') && $cod_eligible==1){
            if((isset($cart['payment_id']) && $cart['payment_id'] == '-1') || !isset($cart['payment_id'])){
            $payment_option_id = 61;
            $sql = "select cpop.payment_option_pgw_id, cpop.payment_option_id, cpop.payment_gateway_id, cpop.priority, cpop.status, 
                                cpt.name as payment_type, cpt.payment_type_id, cpo.name as 	payment_option,cpt.position
                                from clues_payment_option_pgw cpop join clues_payment_options cpo on (cpop.payment_option_id=cpo.payment_option_id)
                                join clues_payment_types cpt on (cpo.payment_type_id=cpt.payment_type_id)						
                                join cscart_payments cp on (cpop.payment_gateway_id=cp.payment_id) 
                                where cpo.payment_option_id='".$payment_option_id."' order by cpop.priority asc limit 0,1";
            $prev_payment_data= db_get_row($sql);
            if($prev_payment_data){
                    $cart['payment_id'] = $prev_payment_data['payment_gateway_id'];
                    $cart['payment_option_id'] = $prev_payment_data['payment_option_id'];
                    $cart['payment_details'] = $prev_payment_data;
            }
            if(isset($cart['payment_details']['payment_option_pgw_id']))
            {
               $sql = "select failed_status from clues_payment_option_pgw where payment_option_pgw_id = ".$cart['payment_details']['payment_option_pgw_id']."";
               $failed_status = db_get_field($sql);
               $cart['payment_failed_status'] = $failed_status;
            }
            }
        }
        
	if($edit_step == 'step_three' && isset($cart['user_data']['user_id'])){
		if((isset($cart['payment_id']) && $cart['payment_id'] == '-1') || !isset($cart['payment_id'])){
                        $sql = "select co.order_id, cpop.payment_option_pgw_id, cpop.payment_option_id, cpop.payment_gateway_id, cpop.priority, cpop.status, 
                                cpt.name as payment_type, cpt.payment_type_id, cpo.name as 	payment_option,co.email,cpt.position
                                from clues_payment_option_pgw cpop       
                                join cscart_payments cp on (cpop.payment_gateway_id=cp.payment_id) and cp.status = 'A'
                                join clues_payment_options cpo on cpo.payment_option_id = cpop.payment_option_id and cpo.status='A' 
                                join clues_payment_types cpt on (cpo.payment_type_id=cpt.payment_type_id)
                                join cscart_orders co on (co.payment_option_id = cpop.payment_option_id) and co.emi_id = 0 and co.user_id = ".$_SESSION['auth']['user_id']." and co.payment_id not in (0,6) and co.status not in ('N','F')
                                where cpop.status = 'A'  
                                order by co.order_id desc, cpop.priority asc limit 0,1";
			$prev_payment_data= db_get_row($sql);
			if($prev_payment_data){
				$cart['payment_id'] = $prev_payment_data['payment_gateway_id'];
				$cart['payment_option_id'] = $prev_payment_data['payment_option_id'];
				$cart['payment_details'] = $prev_payment_data;
			}
                        if(isset($cart['payment_details']['payment_option_pgw_id']))
                        {
                           $sql = "select failed_status from clues_payment_option_pgw where payment_option_pgw_id = ".$cart['payment_details']['payment_option_pgw_id']."";
                           $failed_status = db_get_field($sql);
                           $cart['payment_failed_status'] = $failed_status;
                        }
			//echo '<pre>';print_r($prev_payment_data);echo '</pre>';die;
		}	
	}
        
        
	
	/*Modified by clues dev to limit the service area*/
	/*$shipping_address_zip = $cart['user_data']['s_zipcode'];
	$carriers = fn_my_changes_get_servicable_carrier($shipping_address_zip);
	
	$is_cod = 'N';
	$is_servicable = 'N';
	if(count($carriers)>0)
	{
		$is_servicable = 'Y';
		foreach($carriers as $carrier)
		{
			if($carrier['is_cod'] == 'Y')
			{
				$is_cod = 'Y';
			}
		}
	}*/
	/*if($is_servicable == 'N' && $is_cod == 'N')
	{
		fn_set_notification('E', 'Error', 'we do not service your location.');
	}elseif($is_cod == 'N')
	{
		fn_set_notification('E', 'Error', 'we do not service your location with cod.');
	}*/
	
	/*$view->assign('is_servicable', $is_servicable);
	$view->assign('is_cod', $is_cod);	*/
	/*Modified by clues dev to limit the service area*/
	
	$view->assign('shipping_rates', $_SESSION['shipping_rates']);
	$view->assign('payment_methods', $payment_methods = fn_prepare_checkout_payment_methods($cart, $auth));
	
	$cart['payment_surcharge'] = 0;
	if (!empty($cart['payment_id']) && !empty($payment_methods[$cart['payment_id']])) {
		$cart['payment_surcharge'] = $payment_methods[$cart['payment_id']]['surcharge_value'];
	}

	if (PRODUCT_TYPE == 'MULTIVENDOR') {
		$view->assign('take_surcharge_from_vendor', fn_take_payment_surcharge_from_vendor($cart['products']));
	}

	$view->assign('titles', fn_get_static_data_section('T'));
	$view->assign('usergroups', fn_get_usergroups('C', CART_LANGUAGE));
	$view->assign('countries', fn_get_countries(CART_LANGUAGE, true));
	$view->assign('states', fn_get_all_states());

	$cart['ship_to_another'] = fn_check_shipping_billing($cart['user_data'], $profile_fields);

	$view->assign('profile_fields', $profile_fields);

	if (Registry::get('settings.General.user_multiple_profiles') == 'Y') {
		//$user_profiles = fn_get_user_profiles($auth['user_id']);
		$user_profiles = fn_get_user_profiles_data($auth['user_id']);
		$view->assign('user_profiles', $user_profiles);
	}

	fn_checkout_summary($cart);
	
	if ($edit_step == 'step_two' && !empty($completed_steps['step_one']) && empty($profile_fields['B']) && empty($profile_fields['S'])){
		$edit_step = 'step_four';
	}

	// If we're on shipping step and shipping is not required, switch to payment step
	//FIXME
	/*if ($edit_step == 'step_three' && $cart['shipping_required'] != true) {
		$edit_step = 'step_four';
	}*/

	if (empty($edit_step) || empty($completed_steps[$edit_step])) {
		// If we don't pass step to edit, open default (from settings)
		if (!empty($completed_steps['step_three'])) {
			$edit_step = 'step_three';
		} else {
			$edit_step = !empty($completed_steps['step_one']) ? 'step_two' : 'step_one';
		}
	}

	if (!empty($_REQUEST['expand_cart'])) {
		$_SESSION['expand_cart'] = ($_REQUEST['expand_cart'] == 'Y') ? true : false;
	}
	$view->assign('expand_cart', !isset($_SESSION['expand_cart']) ? false : $_SESSION['expand_cart']);

	$_SESSION['edit_step'] = $edit_step;
	$view->assign('use_ajax', 'true');
	$view->assign('edit_step', $edit_step);
	$view->assign('completed_steps', $completed_steps);
	$view->assign('location', 'checkout');
        
        if($cart['user_data']['s_zipcode'] != "" && strlen($cart['user_data']['s_zipcode'])=='6' && is_numeric($cart['user_data']['s_zipcode'])) 
        {
            setcookie("pincode",$cart['user_data']['s_zipcode'],time()+3600*24*365,'/','.shopclues.com');
            foreach ($cart['products'] as $key => $product) 
            {
             $is_serviceable = get_servicability_type($cart['products'][$key]['product_id'], $cart['user_data']['s_zipcode']);
             $cart['products'][$key]['is_serviceable'] = $is_serviceable;
             $cart_products[$key]['is_serviceable'] = $is_serviceable;
                            
            }
        }

	if (defined('AJAX_REQUEST')) {



		$view->assign('cart', $cart);
		$view->assign('cart_products', array_reverse($cart_products, true));

		if (in_array('sign_io', Registry::get('ajax')->result_ids)) {
			$view->display('top.tpl');
		}
		if (in_array('cart_status', Registry::get('ajax')->result_ids)) {
			$view->display('views/checkout/components/cart_status.tpl');
		}
		if (in_array('checkout_totals', Registry::get('ajax')->result_ids)) {
			//$view->assign('location', 'checkout');
			$view->display('views/checkout/components/checkout_totals.tpl');
		}
		if (in_array('checkout_steps', Registry::get('ajax')->result_ids) || in_array('checkout_cart', Registry::get('ajax')->result_ids)) {
			$view->display('views/checkout/components/checkout_steps.tpl');
		}
		if (in_array('payments_summary', Registry::get('ajax')->result_ids)) {
			$view->display('views/checkout/components/payment_methods.tpl');
		}
		if (in_array('shipping_rates_list', Registry::get('ajax')->result_ids)) {
			$view->assign('shipping_rates', $_SESSION['shipping_rates']);
			$view->assign('display', 'radio');
			$view->display('views/checkout/components/shipping_rates.tpl');
		}

		exit;
	}
        //changed by munish
$addr = array();
$x =fn_get_user_profiles_data($cart['user_data']['user_id']);
    foreach($x as $z)
    {
        
            $addr[$z['profile_id']]['title']= $z['s_title'];
            $addr[$z['profile_id']]['firstname']= $z['s_firstname'];
            $addr[$z['profile_id']]['lastname']= $z['s_lastname'];
            $addr[$z['profile_id']]['address']= $z['s_address'];
            $addr[$z['profile_id']]['address_2']= $z['s_address_2'];
            $addr[$z['profile_id']]['city']= $z['s_city'];
            $addr[$z['profile_id']]['state']= fn_get_state_name($z['s_state'],$z['s_country']);
            $addr[$z['profile_id']]['zipcode']= $z['s_zipcode'];
            $addr[$z['profile_id']]['country']= fn_get_country_name($z['s_country']);     
    }
    
        $view->assign('mul_address',$addr);
        if($edit_step == 'step_four')
        {
                $update_cart = array();
                $i=0;
                foreach($cart['new_cart']['cart_to_show'] as $value)
                {
                    if(fn_check_new_cart_multiaddress($value['profile_id'],$value['product_id'],$update_cart))
                    {
                        $key = fn_get_new_cart_key($value['profile_id'],$value['product_id'],$update_cart);
                        $update_cart[$key]['amount'] += $value['amount'];
                            $update_cart[$key]['product_options'] = fn_merge_product_option($update_cart[$key]['product_options'],$value['product_options']);
                        
                    }
                    else
                    {
                        $update_cart[$i]=$value;
                    }
                    $i++;
                }
                
                $view->assign('update_cart', $update_cart);
        }
        // end changed by Munish
        $view->assign('cart_products', array_reverse($cart_products, true));

// Step 4: Summary
} elseif ($mode == 'summary') {

	if (!empty($_SESSION['shipping_rates'])) {
		define('CACHED_SHIPPING_RATES', true);
	}

	list($cart_products, $_SESSION['shipping_rates']) = fn_calculate_cart_content($cart, $auth, 'E', true, Registry::get('settings.General.checkout_style') != 'multi_page' ? 'F' : 'I'); // we need this for promotions only actually...

	$profile_fields = fn_get_profile_fields('O');

	if (empty($cart['payment_id']) && floatval($cart['total']) || !fn_allow_place_order($cart)) {
		return array(CONTROLLER_STATUS_REDIRECT, "checkout.checkout");
	}

	fn_checkout_summary($cart);

	fn_get_default_credit_card($cart, empty($cart['user_data']) ? array() : $cart['user_data']);

	$view->assign('shipping_rates', $_SESSION['shipping_rates']);

	if (defined('AJAX_REQUEST')) {

		fn_gather_additional_products_data($cart_products, array('get_icon' => true, 'get_detailed' => false, 'get_options' => true, 'get_discounts' => false));

		$view->assign('cart', $cart);
		$view->assign('cart_products', array_reverse($cart_products, true));
		$view->assign('location', 'checkout');
		$view->assign('profile_fields', $profile_fields);
		$view->assign('use_ajax', true);

		if (Registry::get('settings.General.checkout_style') != 'multi_page') {
			$view->assign('edit_step', 'step_four');
			$view->display('views/checkout/components/checkout_steps.tpl');
			$view->display('views/checkout/components/cart_items.tpl');
		} else {
			$view->display('views/checkout/checkout.tpl');
		}
		$view->display('views/checkout/components/checkout_totals.tpl');

		exit;
	}

// Delete product from the cart
} elseif ($mode == 'delete' && isset($_REQUEST['cart_id'])) {

	fn_delete_cart_product($cart, $_REQUEST['cart_id']);
	
	if (fn_cart_is_empty($cart) == true) {
		fn_clear_cart($cart);
	}
        fn_synchronize_mashipping_cart_with_main_cart($cart);

	fn_save_cart_content($cart, $auth['user_id']);
	 /*modified by chandan to log cart, request, session*/
     if(Registry::get('config.cart_logging')) {
			$cart_data = serialize($cart);
			$session_data = serialize($_SESSION);
			$request_data = serialize($_REQUEST).'server data ' . serialize($_SERVER);
			$checkout_step = 'product deleted';
			$user_id = $_SESSION['auth']['user_id'];
	
			$sql = "insert into clues_carts_history (user_id, cart_data, session_data, request_data, checkout_step, session_id) values
					('".$user_id."','".addslashes($cart_data)."','".addslashes($session_data)."','".addslashes($request_data)."','".$checkout_step."','".session_id()."')";
			db_query($sql);
	 }
                /*modified by chandan to log cart, request, session*/

	/*modified by chandan to stop cart calculation on produciton deletion*/
	//$cart['recalculate'] = true;
	$cart['recalculate'] = false;
	/*modified by chandan to stop cart calculation on produciton deletion*/
	/*modified by chandan to return on ajax request on step three*/
	if(isset($_REQUEST['location']) && $_REQUEST['location'] == "step_three"){
		$cart['recalculate'] = true;
		fn_calculate_cart_content($cart, $auth, 'S', true, 'F', true);
		return array(CONTROLLER_STATUS_REDIRECT, "checkout.checkout&edit_step=step_three");	
		//$_suffix = ".checkout";			
		/*modified by chandan to return on ajax request on step three*/
	}elseif (defined('AJAX_REQUEST')) {
		if ($action == 'from_status') {
			/*modified by chandan to stop cart calculation on produciton deletion*/
			//fn_calculate_cart_content($cart, $auth, 'S', true, 'F', true);
			/*modified by chandan to stop cart calculation on produciton deletion*/
			$view->assign('force_items_deletion', true);
			$view->display('views/checkout/components/cart_status.tpl');			
			$view->assign('cart', $cart);
			if (Registry::get('settings.DHTML.ajax_add_to_cart') != 'Y' && Registry::get('settings.General.redirect_to_cart') == 'Y') {
					$view->assign('continue_url', (!empty($_REQUEST['redirect_url']) && empty($_REQUEST['appearance']['details_page'])) ? $_REQUEST['redirect_url'] : $_SESSION['continue_url']);
				}
                        if(Registry::get('config.xbuy_now_popup')){
			   $msg = $view->display('views/checkout/components/xcart_content_popup.tpl', false);
                           fn_set_notification('P', fn_get_lang_var($product_cnt > 1 ? 'xproducts_added_to_cart' : 'xproduct_added_to_cart'), $msg, 'I');
                        }else{
                           $msg = $view->display('views/checkout/components/cart_content_popup.tpl', false); 
                           fn_set_notification('P', fn_get_lang_var($product_cnt > 1 ? 'products_added_to_cart' : 'product_added_to_cart'), $msg, 'I');
                        }
			//fn_set_notification('P', fn_get_lang_var($product_cnt > 1 ? 'products_added_to_cart' : 'product_added_to_cart'), $msg, 'I');
			/*modified by chandan to stop cart calculation on produciton deletion*/
			//$cart['recalculate'] = true;
			$cart['recalculate'] = false;
			/*modified by chandan to stop cart calculation on produciton deletion*/			
			exit;
		}
	}
        
        return array(CONTROLLER_STATUS_REDIRECT,(!empty($_REQUEST['redirect_mode'])?("checkout." . $_REQUEST['redirect_mode']):"checkout.cart"));
        
} elseif ($mode == 'get_custom_file' && isset($_REQUEST['cart_id']) && isset($_REQUEST['option_id']) && isset($_REQUEST['file'])) {
	if (isset($cart['products'][$_REQUEST['cart_id']]['extra']['custom_files'][$_REQUEST['option_id']][$_REQUEST['file']])) {
		$file = $cart['products'][$_REQUEST['cart_id']]['extra']['custom_files'][$_REQUEST['option_id']][$_REQUEST['file']];
		
		fn_get_file($file['path'], $file['name']);
	}

} elseif ($mode == 'delete_file' && isset($_REQUEST['cart_id'])) {

	if (isset($cart['products'][$_REQUEST['cart_id']]['extra']['custom_files'][$_REQUEST['option_id']][$_REQUEST['file']])) {
		// Delete saved custom file
		$file = $cart['products'][$_REQUEST['cart_id']]['extra']['custom_files'][$_REQUEST['option_id']][$_REQUEST['file']];
		
		@unlink($file['path']);
		@unlink($file['path'] . '_thumb');
		
		unset($cart['products'][$_REQUEST['cart_id']]['extra']['custom_files'][$_REQUEST['option_id']][$_REQUEST['file']]);
	}
	
	fn_save_cart_content($cart, $auth['user_id']);

	$cart['recalculate'] = true;

	if (defined('AJAX_REQUEST')) {
		fn_set_notification('N', fn_get_lang_var('notice'), fn_get_lang_var('text_product_file_has_been_deleted'));
		if ($action == 'from_status') {
			fn_calculate_cart_content($cart, $auth, 'S', true, 'F', true);
			$view->assign('force_items_deletion', true);
			$view->display('views/checkout/components/cart_status.tpl');
			exit;
		}
	}

	return array(CONTROLLER_STATUS_REDIRECT, "checkout." . $_REQUEST['redirect_mode']);

//Clear cart
} elseif ($mode == 'clear') {

	fn_clear_cart($cart);
        update_cart_cookie();
	fn_save_cart_content($cart, $auth['user_id']);
	/*modified by chandan to log cart, request, session*/
	if(Registry::get('config.cart_logging')) {
		$cart_data = serialize($cart);
		$session_data = serialize($_SESSION);
		$request_data = serialize($_REQUEST).'server data ' . serialize($_SERVER);
		$checkout_step = 'cart cleared';
		$user_id = $_SESSION['auth']['user_id'];

		$sql = "insert into clues_carts_history (user_id, cart_data, session_data, request_data, checkout_step, session_id) values
				('".$user_id."','".addslashes($cart_data)."','".addslashes($session_data)."','".addslashes($request_data)."','".$checkout_step."','".session_id()."')";
		db_query($sql);
	}
                /*modified by chandan to log cart, request, session*/

	return array(CONTROLLER_STATUS_REDIRECT, "checkout.cart");

//Purge undeliverable products
} elseif ($mode == 'purge_undeliverable') {

	fn_purge_undeliverable_products($cart);
	fn_set_notification('N', fn_get_lang_var('notice'), fn_get_lang_var('notice_undeliverable_products_removed'));

	return array(CONTROLLER_STATUS_REDIRECT, "checkout.checkout");

} elseif ($mode == 'complete') {
		unset($_SESSION['cod_verification_code']);// unset otp sms flag
        unset($_SESSION['multiaddress_message']); // unset profile messages By Munish 12 nov 2013
         $cart['user_data']['verified']=0;
	if (!empty($_REQUEST['order_id'])) { 
		if (empty($auth['user_id'])) {
			if (empty($auth['order_ids'])) {
				return array(CONTROLLER_STATUS_REDIRECT, "auth.login_form?return_url=" . urlencode(Registry::get('config.current_url')));
			} else {
				$allowed_id = in_array($_REQUEST['order_id'], $auth['order_ids']);
			}
		} else {
			$allowed_id = db_get_field("SELECT user_id FROM ?:orders WHERE user_id = ?i AND order_id = ?i", $auth['user_id'], $_REQUEST['order_id']);
		}

		fn_set_hook('is_order_allowed', $_REQUEST['order_id'], $allowed_id); 

		if (empty($allowed_id)) { // Access denied
			return array(CONTROLLER_STATUS_DENIED);
		}
		
		$order_info = fn_get_order_info($_REQUEST['order_id']);
                
                if(isset($auth['user_id']) && Registry::get('config.express_checkout'))
                {
                    $sql = "select * from clues_express_checkout_setup where user_id='".$auth['user_id']."'";
                    $express_data = db_get_row($sql);
                    
                    if(empty($express_data))
                    {
                        $sql = "insert into clues_express_checkout_setup (user_id,b_firstname,b_lastname,b_address,b_address_2,b_country,b_state,b_city,b_zipcode,b_phone,s_firstname,s_lastname,s_address,s_address_2,s_country,s_state,s_city,s_zipcode,s_phone,timestamp,payment_option_id,billing_status) values ('".$auth['user_id']."','".addslashes($order_info['b_firstname'])."','".addslashes($order_info['b_lastname'])."','".addslashes($order_info['b_address'])."','".addslashes($order_info['b_address_2'])."','".addslashes($order_info['b_country'])."','".addslashes($order_info['b_state'])."','".addslashes($order_info['b_city'])."','".addslashes($order_info['b_zipcode'])."','".addslashes($order_info['b_phone'])."','".addslashes($order_info['s_firstname'])."','".addslashes($order_info['s_lastname'])."','".addslashes($order_info['s_address'])."','".addslashes($order_info['s_address_2'])."','".addslashes($order_info['s_country'])."','".addslashes($order_info['s_state'])."','".addslashes($order_info['s_city'])."','".addslashes($order_info['s_zipcode'])."','".addslashes($order_info['s_phone'])."','".TIME."','".$order_info['payment_option_id']."','Y')";
                        $express_setup = db_query($sql);
                        $view->assign('express_setup',$express_setup);
                    }
                }
                
		//echo "<pre>";print_r($order_info);echo "</pre>";
                //
                //code by Munish to add edd pdd into grace table
                $pdd = fn_insert_edd_pdd_of_order_into_db($_REQUEST['order_id'],$order_info['user_id']);
                
                /* code by ankur to unset all session variable created for gc*/
		 if(isset($order_info['use_gift_certificates']))
		 {
			 foreach($order_info['use_gift_certificates'] as $k=>$value)
			 {
				 unset($_SESSION[$k]);
			 }
		 }
		/* code end */
		/*Modified by clues dev to allow registered user as guest checkout.*/
		$user_id = db_get_field("SELECT user_id FROM ?:users WHERE email = ?s", $order_info['email']);
		if (!empty($user_id)) {
			$view->assign("user_exist","yes"); 
		}
		/*Modified by clues dev to allow registered user as guest checkout.*/
		if (!empty($order_info['is_parent_order']) && $order_info['is_parent_order'] == 'Y') {
			$order_info['child_ids'] = implode(',', db_get_fields("SELECT order_id FROM ?:orders WHERE parent_order_id = ?i", $_REQUEST['order_id']));
		}
		if (!empty($order_info)) {

// Added by Sudhir end here
$conversionCode = "
    <script type=\"text/javascript\">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-29756496-1']);
  _gaq.push(['_trackPageview']);


    _gaq.push(['_addTrans',
                '".$order_info['order_id']."', // order ID
                '',
                '".number_format($order_info['total'], 2, '.', '')."', // order total
                '".number_format($order_info['tax_subtotal'], 2, '.', '')."', // tax
                '".number_format($order_info['shipping_cost'], 2, '.', '')."', // shipping
                '".$order_info['b_city']."', // suburb
                '".$order_info['b_state']."', // state
                '".$order_info['b_country']."' // country
            ]);
";

foreach($order_info['items'] as $items=>$order){
    $productId = $order['product_id'];

    $prodCode = $order['product_code'];

    $conversionCode .= "
        _gaq.push(['_addItem',
            '".$order['order_id']."', // order ID
            '".addslashes($order['product_code'])."', // SKU
            '".addslashes($order['product'])."', // product name
            '',
            '".number_format($order['price'], 2, '.', '')."', // product price
            '".$order['amount']."' // order quantity
        ]);
    ";
}

$conversionCode .= "
        _gaq.push(['_trackTrans']);


  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>";
// Added by Sudhir end here
    
                        $order_info['pdd1'] = date('l j M',$pdd['min_pdd']);
                        $order_info['pdd2'] = date('l j M',$pdd['max_pdd']);
                        $view->assign('order_info', $order_info);
			$domain = $_SERVER['HTTP_HOST'] == 'localhost' ? '' : '.shopclues.com';
			setcookie('sess_id1',"",time()+60*60*24*365,'',$domain);
			$_COOKIE['sess_id1'] = '';
			$_SESSION['cart']['products'] = array();
			$domain = $_SERVER['HTTP_HOST'] == 'localhost' ? '' : '.shopclues.com';
			setcookie('sess_id1',"",time()+60*60*24*365,'',$domain);
			$_COOKIE['sess_id1'] = '';
			$_SESSION['cart']['products'] = array();
		}
                /*added by sapna to track orders*/
            if(Registry::get('config.track_user')) {
               fn_track_user($_COOKIE['scto'], $order_info,"","orderplaced");
            }       
	}
    $arr=array();
        /*Added for Bazooka */
      //echo '<pre>'; print_r($cart); 
      $tot_reward_points=0;
      $arr_child_orders=array();
      if(!empty($order_info['child_ids']))
      {
           $arr_child_orders = explode(',',$order_info['child_ids']);
      }
      else
      {
          array_push($arr_child_orders,$_REQUEST['order_id']);
      }
           
      if(!empty($cart['gift_certificates']))
      {    
            $earn_on_gc=fn_cb_reward_on_gc($cart['gift_certificates']);
            $tot_reward_points=$cart['points_info']['reward']+$earn_on_gc;
      }
      else 
      {
          $tot_reward_points=$cart['points_info']['reward'];
      }    
      $view->assign('total_reward_points', $tot_reward_points);  
      $order_date= date("d/m/Y, g:i A",$order_info['timestamp'] );
      $view->assign('order_date', $order_date);
      $item_count = count($order_info['items']);
      $view->assign('item_count',$item_count);

      $saved=$order_info['discount']+$order_info['subtotal_discount']+$tot_reward_points;
      //$saved=(($order_info['subtotal']-$order_info['subtotal_discount'])+$order_info['cb_used']);

      $view->assign('saved',$saved);
      if(Registry::get('config.bazooka_on_thank_you_page')==1)
      {
	      fn_get_bazooka($arr_child_orders,1);
      }
	
	fn_add_breadcrumb(fn_get_lang_var('landing_header'));
	
} elseif ($mode == 'process_payment') {
	if (fn_allow_place_order($cart) == true) {
		$order_info = $cart;
		$order_info['items'] = $cart['products'];
		$order_info = fn_array_merge($order_info, fn_check_table_fields($cart['user_data'], 'orders'));
		$order_info['order_id'] = $order_id = TIME . "_" . (!empty($auth['user_id']) ? $auth['user_id'] : 0);

		list($is_processor_script, $processor_data) = fn_check_processor_script($order_info['payment_id'], '');
		if ($is_processor_script) {
			set_time_limit(300);
			fn_define('IFRAME_MODE', true);

			include(DIR_PAYMENT_FILES . $processor_data['processor_script']);

			fn_finish_payment($order_id, $pp_response, array());
			fn_order_placement_routines($order_id);
		}
	}
}

if (fn_cart_is_empty($cart) && !isset($force_redirection) && !in_array($mode, array('clear', 'delete', 'cart', 'update', 'apply_coupon', 'shipping_estimation', 'update_shipping', 'complete'))) {
	/*Modified by clues dev to log empty cart error*/
	$content = date('Y-m-d h:i:s')."\r\n+++++++++++++".'SERVER variable : '.serialize($_SERVER)."\r\n".'user session : '.serialize($_SESSION['auth'])."\r\n++++++++++++";
	log_to_file('cart_empty_error',$content);
	/*Modified by clues dev to log empty cart error*/
	fn_set_notification('W', fn_get_lang_var('cart_is_empty'),  fn_get_lang_var('cannot_proccess_checkout'));
	
	return array(CONTROLLER_STATUS_REDIRECT, 'checkout.cart');
}

if ($mode == 'checkout' || $mode == 'summary') {
	if (!empty($cart['failed_order_id']) || !empty($cart['processed_order_id'])) {
		$_ids = !empty($cart['failed_order_id']) ? $cart['failed_order_id'] : $cart['processed_order_id'];
		$_order_id = reset($_ids);
		$_payment_info = db_get_field("SELECT data FROM ?:order_data WHERE order_id = ?i AND type = 'P'", $_order_id);
		if (!empty($_payment_info)) {
			$_payment_info = unserialize(fn_decrypt_text($_payment_info));
		}

		if (!empty($cart['failed_order_id'])) {
			$_msg = !empty($_payment_info['reason_text']) ? $_payment_info['reason_text'] : '';
			$_msg .= empty($_msg) ? fn_get_lang_var('text_order_placed_error') : '';
			fn_set_notification('O', '', $_msg);
			$cart['processed_order_id'] = $cart['failed_order_id'];
                        $cart['cod_eligible_order_id'] = $cart['failed_order_id'];
			unset($cart['failed_order_id']);
		}

		unset($_payment_info['card_number'], $_payment_info['cvv2'], $_payment_info['issue_number']);
		$cart['payment_info'] = $_payment_info;
		if (!empty($cart['extra_payment_info'])) {
			$cart['payment_info'] = array_merge($cart['payment_info'], $cart['extra_payment_info']);
		}
	}
}

if (!empty($profile_fields)) {
	$view->assign('profile_fields', $profile_fields);
}


$view->assign('cart', $cart);
$view->assign('continue_url', empty($_SESSION['continue_url']) ? '' : $_SESSION['continue_url']);
$view->assign('mode', $mode);
$view->assign('payment_methods', $payment_methods);

// Remember mode for the check shipping rates
$_SESSION['checkout_mode'] = $mode;

function fn_prepare_checkout_payment_methods(&$cart, &$auth)
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

function fn_checkout_summary(&$cart)
{
	if (fn_cart_is_empty($cart) == true) {
		return false;
	}

	fn_set_hook('checkout_summary', $cart);

	//Get payment methods
	$payment_data = fn_get_payment_method_data($cart['payment_id']);

	Registry::get('view')->assign('payment_method', $payment_data);
	Registry::get('view')->assign('credit_cards', fn_get_static_data_section('C', true, 'credit_card'));

	// Downlodable files agreements
	$agreements = array();
	foreach ($cart['products'] as $item) {
		if ($item['is_edp'] == 'Y') {
			if ($_agreement = fn_get_edp_agreements($item['product_id'], true)) {
				$agreements[$item['product_id']] = $_agreement;
			}
		}
	}

	if (!empty($agreements)) {
		Registry::get('view')->assign('cart_agreements', $agreements);
	}
}

function fn_need_shipping_recalculation(&$cart)
{
	if ($cart['recalculate'] == true) {
		return true;
	}

	$recalculate_shipping = false;
	if (!empty($_SESSION['customer_loc'])) {
		foreach ($_SESSION['customer_loc'] as $k => $v) {
			if (!empty($v) && empty($cart['user_data'][$k])) {
				$recalculate_shipping = true;
				break;
			}
		}
	}

	if ($recalculate_shipping == false && !empty($_SESSION['checkout_mode']) && ($_SESSION['checkout_mode'] == 'cart' && MODE == 'checkout')) {
		$recalculate_shipping = true;
	}

	unset($_SESSION['customer_loc']);

	return $recalculate_shipping;

}

function fn_get_checkout_payment_buttons(&$cart, &$cart_products, &$auth)
{
	$checkout_buttons = array();

	$ug_condition = 'AND (' . fn_find_array_in_set($auth['usergroup_ids'], 'b.usergroup_ids', true) . ')';
	$checkout_payments = db_get_fields("SELECT b.payment_id FROM ?:payment_processors as a LEFT JOIN ?:payments as b ON a.processor_id = b.processor_id WHERE a.type != 'P' AND b.status = 'A' ?p", $ug_condition);

	if (!empty($checkout_payments)) {
		foreach ($checkout_payments as $_payment_id) {
			$processor_data = fn_get_processor_data($_payment_id);
			if (!empty($processor_data['processor_script']) && file_exists(DIR_PAYMENT_FILES . $processor_data['processor_script'])) {
				include(DIR_PAYMENT_FILES . $processor_data['processor_script']);
			}
		}
	}

	return $checkout_buttons;
}

function fn_get_default_credit_card(&$cart, $user_data) 
{
	if (!empty($user_data['credit_cards'])) {
		$cards = unserialize(fn_decrypt_text($user_data['credit_cards']));
		foreach ((array)$cards as $cc) {
			if ($cc['default']) {
				$cart['payment_info'] = $cc;
				break;
			}
		}
	} elseif (isset($cart['payment_info'])) {
		unset($cart['payment_info']);
	}
}

function fn_update_payment_surcharge(&$cart)
{
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

function fn_check_redirect_to_cart()
{
	if (!isset($_SESSION['auth']['skip_redirect_validation'])) {
		if (!defined('AJAX_REQUEST') && (!empty($_SERVER['HTTP_REFERER']) && ((stripos($_SERVER['HTTP_REFERER'], Registry::get('config.http_location')) !== false || stripos($_SERVER['HTTP_REFERER'], Registry::get('config.https_location')) !== false) && strpos(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY ), 'checkout') === false))) {
			$redirect = true;
			
			fn_set_hook('check_redirect_to_cart', $_SERVER['HTTP_REFERER'], $redirect);
			
			if ($redirect) {
				fn_redirect('checkout.cart', true);
			}
		}
	}
	
	unset($_SESSION['auth']['skip_redirect_validation']);
}
function fn_check_email_id($userdata)
{
	return db_get_row("select user_id from cscart_users where email='".$userdata['email']."'");
}
function fn_check_new_cart_multiaddress($proid,$pid,$update)
{
    foreach($update as $i => $v)
    {
        if(($update[$i]['profile_id'] ==$proid) && ($update[$i]['product_id']==$pid))
        {
            $x = 1;
        }
    }
    if($x)
        return true;
    else
        return false;
}
function fn_get_new_cart_key($proid,$pid,$update)
{
    foreach($update as $i => $v)
    {
        if(($update[$i]['profile_id'] ==$proid) && ($update[$i]['product_id']==$pid))
        {
            return $i;
        }
    }
}
function fn_merge_product_option($x,$y)
{
    foreach($y as $ykey => $yval)
    { 
        foreach($x as $key => $value)
        {
            if($key == $ykey){
                if(!is_array($x[$key])){
                    $x[$key] = array($x[$key]);
                }
                array_push($x[$key], $yval);
            }
        }
    }
   return $x;
}
?>
