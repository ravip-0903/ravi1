<?php

	/* 	This is an example class script proceeding secured API
		To use this class you should keep same as query string and function name
		Ex: If the query string value rquest=delete_user Access modifiers doesn't matter but function should be
		     function delete_user(){
				 Your code goes here
			 }
		Class will execute the function dynamically;

		usage :

		    $object->response(output_data, status_code);
			$object->_request	- to get santinized input

			output_data : JSON (I am using)
			status_code : Send status message for headers 	*/


	class user_cart extends REST {

		public $data = "";

		public function __construct(){
			parent::__construct();				// Init parent contructor
		}

                public function cart(){
                    $user_id = $this->_request['user_id'];
                    if(empty($user_id)){
                        $msg = array('status' => "Error", "msg" => fn_get_lang_var('api_param_empty_user_id'));
                        $this->response($this->json($msg), 200);
                    }

                    if(!(is_numeric($user_id))){
                        $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_user_id'));
                        $this->response($this->json($error), 200);
                    }


                    if($this->get_request_method() == "GET"){
                        $response = $this->get_cartItems($user_id);
                        if(!empty($response['cart']['products']))
                                $this->response($this->json($response), 200);
                        else{
                                $error = array('status' => "Success", "msg" => fn_get_lang_var('api_empty_cart'));
                                $this->response($this->json($error), 200);
                        }

                    }
                    elseif($this->get_request_method() == "POST"){
                        /* Check if content type is Json or not */
                        if($_SERVER['CONTENT_TYPE']!='application/json')
                        {
                            $msg = array('status' => "Error", "msg" => fn_get_lang_var('api_param_invalid_content_type'));
                            $this->response($this->json($msg), 200);
                        }
                        $response = $this->add_cartItems($user_id);
                        $this->response($this->json($response), 200);

                    }
                    elseif($this->get_request_method() == "PUT"){

                        $product_id = $this->_request['product_id'];
                        $cart_id = $this->_request['cart_id'];
                        $amount = $this->_request['qty'];
                        if(empty($product_id)  || empty($cart_id) || empty($amount)){
                            $msg = array('status' => "Error", "msg" => fn_get_lang_var('api_param_invalid_params'));
                            $this->response($this->json($msg), 200);
                        }

                        if(!(is_numeric($product_id))  || !(is_numeric($cart_id)) || !(is_numeric($amount)) || $amount < 1 ){
                            $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_params'));
                            $this->response($this->json($error), 200);
                        }

                        $response = $this->update_cartItems($user_id,$cart_id,$product_id,$amount);
                        $this->response($this->json($response), 200);

                    }

                    elseif($this->get_request_method() == "DELETE"){
                        $this->deleteCart($user_id);

                    }
                    else{

                        $this->response('',406);

                    }

                }


                /*
		 *
		 .
		 * 	___________________________________________________________________________________
		 *			Quering user's cart info
		 * 	____________________________________________________________________________________
		 *
		 *	Request 	: Strictly GET
		 * 	Parameters
		 *	@user_id     	: user id of the user
		 *
		 * 	query format 	: api/cart?user_id=12345
		 *
		 *
		 *
		 *	______________________________________________________________________________________
		 * 			Adding new product to cart
		 *	______________________________________________________________________________________
		 *
		 *	REQUEST   	: Strictly POST
		 *	Parameters	:
		 *	@user_id 	: id of the user
		 * 	@product_id     : Product which is to be added to the the wishlist
		 * 	@amount         : quantity of the product to be added
		 *      @option_id      : id of the option selected
                 *      @varient_id     : varient of the option that has been selected
		 *	query format 	: /api/cart
		 *
		 *	________________________________________________________________________________________
		 *			Clearing cart (Removes all the items in cart)
		 *	________________________________________________________________________________________
		 *
		 *	REQUEST 	: Strictly DELETE
		 *	Parameters 	:
                 *      @user_id        :
                 *      @cart_id        : always 0(zero)
                 *
 		 *
		 *	Query format 	: /api/cart?user_id={userid}&cart_id=0
		 *
		 *	________________________________________________________________________________________
		 *
		 *			Deleting an Item from cart
		 *	________________________________________________________________________________________
		 *
		 *	REQUEST 	: Strictly DELETE
		 *	Parameters	:
		 *	@user_id	: User id
		 *	@cart_id	: profile id of address book to be deleted.
		 *
		 *	Query format	: api/cart?user_id=12345&cart_id=12345
		 *
                 *
                 *
                 * ________________________________________________________________________________________
		 *
		 *			Updating Quantity of a cart Item
		 *	________________________________________________________________________________________
		 *
		 *	REQUEST 	: Strictly Put
		 *	Parameters	:
		 *	@user_id	: User id
		 *	@cart_id	:
		 *      @product_id     : product id of the product in cart whose quantity is to be updated
		 *	@qty            : quantity of the product to be added.
                 *
                 *      Query format	: api/cart
                 *
                 *
                 *
		 */




                public function add_cartItems($user_id, $step = ''){

                    $cart = array();
                    $response = array();
                    $auth = array();
                    fn_extract_cart_content($cart, $user_id, $type = 'C', $user_type = 'R');
                    $product_id = $this->_request['product_id'];
                    $user_id = $this->_request['user_id'];
                    $amount = $this->_request['amount'];
                    $auth['user_id'] = $user_id;

                    //print_r($this->_request);die;
                     if(empty($product_id)){
                        $msg = array('status' => "Error", "msg" => fn_get_lang_var('api_param_empty_product_id'));
                        $this->response($this->json($msg), 200);
                    }

                    if(!(is_numeric($product_id))){
                        $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_product_id'));
                        $this->response($this->json($error), 200);
                    }

                    if(isset($amount) && ( !(is_numeric($amount)) || $amount< 1) ){
                        $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_amount'));
                        $this->response($this->json($error), 200);
                    }
                    $res = db_get_row("SELECT count(*) FROM cscart_products WHERE product_id =".$product_id." and status = 'A'");
                    if($res['count(*)'] !=1){
                        $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_product_not_available'));
                        $this->response($this->json($error), 200);
                    }
                    $product_data = array();
                    $product_data[$product_id]['product_id'] = $product_id;

                    if(isset($this->_request['options']) && is_array($this->_request['options']))
                    {
                        foreach ($this->_request['options'] as $option_id => $varient_id)
                        {
                            if(!empty($varient_id) && is_numeric($varient_id) && !empty($option_id) && is_numeric($option_id))
                            {
                                $product_options = fn_get_product_options($product_id, CART_LANGUAGE, true);

                                if(array_key_exists($option_id,$product_options) && array_key_exists($varient_id,$product_options[$option_id]['variants']))
                                {
                                    $product_data[$product_id]['product_options'][$option_id] = $varient_id;
                                }
                                else
                                {
                                    $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_option_not_available') . $product_id);
                                    $this->response($this->json($error), 200);
                                }
                            }
                        else
                        {
                            $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_option_variant_pair'));
                            $this->response($this->json($error), 200);
                        }
                    }
                }


                    if (!isset($amount)) {
                        $product_data[$product_id]['amount'] = 1;
                    }
                    else
                        $product_data[$product_id]['amount'] = $amount;

                    foreach ($product_data as $key => $data) {
                        if ($key != $product_id && $key != 'custom_files') {
                                unset($_REQUEST['product_data'][$key]);
                        }
                    }


                    //print_r($product_data);die;

                    $prev_cart_products = empty($cart['products']) ? array() : $cart['products'];

                    if(!empty($upsell_product)){
                        foreach($upsell_product as $upsell_product){
                                $product_data[$upsell_product]['product_id']=$upsell_product;
                                $product_data[$upsell_product]['amount']=1;
                        }
                    }

                    if(!empty($new_pro_upsell)){
                       foreach($new_pro_upsell as $upsell_id){
                          $product_data[$upsell_id]['product_id']=$upsell_id;
                          $product_data[$upsell_id]['amount']=1;
                       }
                    }

                    if(!empty($fs_product)){
                        foreach($fs_product as $fs_product){
                                $product_data[$fs_product]['product_id']=$fs_product;
                                $product_data[$fs_product]['amount']=1;
                        }
                    }


                    if(isset($gc_data)){

                        foreach($gc_data as $gc){
                            $gift_cert_data = array
                            (
                                'recipient' => ($user_id > 0)? $cart['user_data']['email']:Registry::get('config.default_gc_email'),
                                'sender' => ($user_id > 0)? $cart['user_data']['email']:Registry::get('config.default_gc_email'),
                                'amount_type' => 'I',
                                'amount' => $gc,
                                'message' => '',
                                'send_via' => 'E',
                                'email' => ($user_id > 0)? $cart['user_data']['email']:Registry::get('config.default_gc_email'),
                                'template' => 'default.tpl'
                            );

                            list($gift_cert_id, $gift_cert) = fn_add_gift_certificate_to_cart($gift_cert_data, $auth);

                            if (!empty($gift_cert_id)) {
                                    $cart['gift_certificates'][$gift_cert_id] = $gift_cert;
                            }
                        }
                    }
                    fn_add_product_to_cart($product_data, $cart, $auth, false);
                    fn_save_cart_content($cart, $user_id);
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

                    if (!empty($added_products)) {
                        $add = true ;
                        if(Registry::get('config.cart_logging')) {
                            $cart_data = serialize($cart);
                            $session_data = serialize($_SESSION);
                            $request_data = serialize($_REQUEST).'server data : '.serialize($_SERVER);
                            $checkout_step = 'product added to cart';
                            $user_id = $user_id;

                            $sql = "insert into clues_carts_history (user_id, cart_data, session_data, request_data, checkout_step, session_id) values
                                            ('".$user_id."','".addslashes($cart_data)."','".addslashes($session_data)."','".addslashes($request_data)."','".$checkout_step."','".session_id()."')";
                            db_query($sql);
                        }

                        $cart['recalculate'] = false;
                    }

                    if($add == true){
                        if($step == "step_four"){
                            return true;
                        }
                        $response['msg'] = "Product added Successfully";
                        $response['Cart'] = $this->get_CartItems($user_id);
                    }
                    else{
                        $response['status'] = "Failed";
                        $response['msg'] = fn_get_lang_var('api_product_inventory_not_available');

                    }
                    $log['product_id'] = $this->_request['product_id'];
                    $log['msg'] = $response['msg'];
                    //$log['response'] = json_encode($response);
                    $this->RecordLogs($log);
                    return $response;
                }


                public function update_cartItems($user_id,$cart_id,$product_id,$amount){

                        $cart = array();
                        $response = array();
                        $shipping = array();
                        $auth = array();

                        $valid = db_get_row("SELECT * FROM ?:user_session_products WHERE user_id = ?i AND item_id = ?i AND TYPE = 'C' AND product_id = ?i", $user_id,$cart_id,$product_id);
                        if(empty($valid)){

                            $msg = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_cart_user_not_match'));
                            $this->response($this->json($msg), 200);

                        }

                        fn_extract_cart_content($cart, $user_id, $type = 'C', $user_type = 'R');
                        //print_r($cart);die;
                        $product_options = $cart['products'][$cart_id]['product_options'];
                        $cart_products[$cart_id] = array('product_id' => $product_id,'amount' => $amount);
                        $cart_products[$cart_id]['product_options']= $product_options;
                        if (empty($cart_products['amount']) && !isset($cart['products'][$cart_id]['extra']['parent'])) {
                                fn_delete_cart_product($cart, $cart_id);
                        }
                        fn_add_product_to_cart($cart_products, $cart, $auth, true);
                        fn_save_cart_content($cart, $user_id);
                        if($amount != $cart['products'][$cart_id]['amount']){
                            $response = array('status' => "Failed", "msg" => fn_get_lang_var('api_product_inventory_not_available') );
                        }else{
                            $response['status'] = 'Success';
                            $response['msg'] = 'Cart '.$cart_id. fn_get_lang_var('api_updated_successfully');
                        }
                        $log['product_id'] = $this->_request['product_id'];
                        $log['msg'] = $response['msg'];
                        $this->RecordLogs($log);
                        return $response;
                }



                public function get_cartItems($user_id,$step = ''){

                    if( $step=="step_three" || $step == "step_four"){
                        //print_r(fn_get_user_info($this->_request['user_id'],$this->_request['profile_id']));
                        $cart['user_data'] = fn_get_user_info($this->_request['user_id'],'true',$this->_request['profile_id']);
                    }

                    fn_extract_cart_content($cart, $user_id, $type = 'C', $user_type = 'R');
                    list ($cart_products, $shipping['shipping_rates']) = fn_calculate_cart_content($cart, $_SESSION['auth'], 'A', true, 'F', false);
                    fn_gather_additional_products_data($cart_products, array('get_icon' => true, 'get_detailed' => true, 'get_options' => true, 'get_discounts' => false));

                    /* Loop for Adding CDN image path */
                    foreach ($cart_products as $key => &$value) {
                        // $cluesbucks = $value['points_info']['reward']['amount'] *  $value['amount'];
                        // $cart_products[$key] =array_merge($cart_products[$key],array("cluesbucks" => $cluesbucks));
                        $value['main_pair']= fn_get_image_pairs($value['product_id'], 'product', 'M', true, true, $lang_code = CART_LANGUAGE);
                        $images_main = fn_get_img_path($value['main_pair'],160,160);
                        $value['image_path'] =  $images_main;
                    
                     }
                     /* Ends Here */

                    $cart_products = array_reverse($cart_products, true);
                    //unset($cart['products']);
                    //$response['cart_info'] = $cart;
                    $response['cart'] = $cart;
                    $response['cart_products'] = $cart_products;
                    return $response;
                }

                public function deleteCart($user_id){

                    $cart = array();
                    fn_extract_cart_content($cart, $user_id, $type = 'C', $user_type = 'R');

                    $cart_id = $this->_request['cart_id'];
                    if(!isset($cart_id)){
                            $msg = array('status' => "Error", "msg" => fn_get_lang_var('api_param_empty_cart_id'));
                            $this->response($this->json($msg), 200);
                    }

                    if(!(is_numeric($cart_id))){
                        $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_cart_id'));
                        $this->response($this->json($error), 200);
                    }
//                    $cart_id = intval($cart_id);

                    //clear all the products from the cart

                    if(isset($cart_id) && $cart_id == 0 ){

                        fn_clear_cart($cart);
                        fn_save_cart_content($cart, $user_id);
                        $msg = array('status' => "Success", "msg" => fn_get_lang_var('api_cart_deleted_of_user'));
                        $this->response($this->json($msg), 200);
                    }

                    $valid = db_get_row("SELECT * FROM ?:user_session_products WHERE user_id =".$user_id." AND item_id =".$cart_id." AND TYPE = 'C' ");
                    if(empty($valid)){

                        $msg = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_cart_user') );
                        $this->response($this->json($msg), 200);

                    }

                    fn_delete_cart_product($cart, $cart_id);

                    if (fn_cart_is_empty($cart) == true) {
                            fn_clear_cart($cart);
                    }

                    fn_save_cart_content($cart, $user_id);
                    $msg = array('status' => "Success", "msg" => fn_get_lang_var('api_cart_deleted_of_user'));
                    $this->response($this->json($msg), 200);

                }
				
				function cart_count(){
					
					if($this->get_request_method() != "GET"){
						$this->response('',406);
					}					
					$user_id = $this->_request['user_id'];	
					 if(empty($user_id) || !(is_numeric($user_id)) ){
                        $msg = array('status' => "failed", "msg" => fn_get_lang_var('api_param_invalid_user_id'));
                        $this->response($this->json($msg), 200);
                    }
					$sql = 'SELECT COUNT( * ) as count FROM  `cscart_user_session_products` WHERE type = \'C\' and user_id = '.$user_id;
					$count = db_get_field($sql);
					$msg = array('status' => "Success", "count" => $count );
                    $this->response($this->json($msg), 200);
				}
				


		/*
		 *	Encode array into JSON
		*/

		public function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}

	}

?>
