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


	class user_wishlist extends REST {
	
		public $data = "";

		public function __construct(){
			parent::__construct();				// Init parent contructor
		}
                
                
                
                
                /* 
		 *
		 .
		 * 	___________________________________________________________________________________
		 *			Quering user's wishlist info
		 * 	____________________________________________________________________________________	
		 *	
		 *	Request 	: Strictly GET
		 * 	Parameters   	
		 *	@user_id     	: user id of the user
		 *
		 * 	query format 	: api/wishlist?user_id=12345
		 *
		 *
		 * 	
		 *	______________________________________________________________________________________
		 * 			Adding new product to wishlist
		 *	______________________________________________________________________________________
		 * 	
		 *	REQUEST   	: Strictly POST
		 *	Parameters	:
		 *	@user_id 	: id of the user 
		 * 	@product_id     : Product which is to be added to the the wishlist
		 * 	
		 *
		 *	query format 	: /api/wishlist
		 *
		 *	________________________________________________________________________________________
		 *			Clearing wishlist (Removes all the items in wishlist)
		 *	________________________________________________________________________________________
		 *	
		 *	REQUEST 	: Strictly DELETE
		 *	Parameters 	: 
                 *      @user_id        :
                 *      @cart_id        : always 0(zero)
                 *      @option_id
                 *      @varient_id
 		 *
		 *	Query format 	: /api/wishlist?user_id={userid}&cart_id=0
		 *	
		 *	________________________________________________________________________________________
		 *
		 *			Deleting an Item from Wishlist
		 *	________________________________________________________________________________________
		 *
		 *	REQUEST 	: Strictly DELETE
		 *	Parameters	: 
		 *	@user_id	: User id 
		 *	@cart_id	: profile id of address book to be deleted.
		 *
		 *	Query format	: api/wishlist?user_id=12345&cart_id=12345
		 *
		 */
                
                public function wishlist(){
                    
                        $user_id = $this->_request['user_id'];
                        if(empty($user_id)){
                            $msg = array('status' => "Error", "msg" => "User id cannot be empty");
                            $this->response($this->json($msg), 400);  
                        }

                        if(!(is_numeric($user_id))){
                            $error = array('status' => "Failed", "msg" => "Please enter a valid user id");
                            $this->response($this->json($error), 400);
                        }


                        if($this->get_request_method() == "GET"){
                            $response = $this->get_wishlistItems($user_id);
                            if(!empty($response['products_info']))
                                    $this->response($this->json($response), 200);
                            else{
                                    $error = array('status' => "Success", "msg" => "Wishlist is Empty");
                                    $this->response($this->json($error), 200);
                            }

                        }
                        elseif ($this->get_request_method() == "POST"){
                            
                            $product_id = $this->_request['product_id'];
                            if(empty($product_id)){
                                $msg = array('status' => "Error", "msg" => "Product id cannot be empty");
                                $this->response($this->json($msg), 400);  
                            }

                            if(!(is_numeric($product_id))){
                                $error = array('status' => "Failed", "msg" => "Please enter a valid Product id");
                                $this->response($this->json($error), 400);
                            }
                            
                            $response = $this->add_WishlistItem($user_id,$product_id);
                            if(!empty($response)){
                                $msg = array('status' => "Success", "msg" => "Product ". $product_id." added to wishlist");
                                $this->response($this->json($msg), 200);                      
                            }
                            else{
                                $msg = array('status' => "Success", "msg" => "Product already on the wishlist");
                                $this->response($this->json($msg), 200);                      
                                
                            }
                                                 
                        }
                        
                        elseif ($this->get_request_method() == "DELETE"){
                            
                            $this->deleteWishlist($user_id);
                                                 
                        }
                        else{

                            $this->response('',406);                   
                        }
                }
                
                
               public function getShort_wishlist(&$wishlist,$user_id){                  
                    $item_types = fn_get_cart_content_item_types('X');
                    $type = 'W';
                    $user_type = 'R';
                    $_prods = db_get_hash_array("SELECT * FROM ?:user_session_products WHERE user_id = ?l AND type = ?s AND user_type = ?s AND item_type IN (?a)", 'item_id', $user_id, $type, $user_type, $item_types);
                    if (!empty($_prods) && is_array($_prods)) {                        
                        $wishlist['products'] = empty($wishlist['products']) ? array() : $wishlist['products'];
                        foreach ($_prods as $_item_id => $_prod) {
                            $_prod_extra = unserialize($_prod['extra']);
                            unset($_prod['extra']);
                            $wishlist['products'][$_item_id] = empty($wishlist['products'][$_item_id]) ? fn_array_merge($_prod, $_prod_extra, true) : $wishlist['products'][$_item_id];
                        }
                    }                    
                }
                
                public function add_wishlistItem($user_id,$product_id){

                    $wishlist = array();
                    $auth = array();                    
                    $product_data = array();
                    $options = $this->_request['option_id'];
                    $varient = $this->_request['varient_id'];
                    $product_data[$product_id]['product_id'] = $product_id;
                    $res = db_get_row("SELECT count(*) FROM cscart_products WHERE product_id =".$product_id." and status = 'A'");
                    if($res['count(*)'] !=1){
                        $error = array('status' => "Failed", "msg" => "Not a valid product.");
                        $this->response($this->json($error), 400);                        
                    }
                    $this->getShort_wishlist($wishlist,$user_id);
                    if (empty($wishlist)) {
			$wishlist = array(
				'products' => array()
			);
                    }
                    
                    if(isset($options)){        
                        
                        if(is_numeric($options) && ( !empty($varient) && is_numeric($varient)) ){
                            $product_options = fn_get_product_options($product_id, CART_LANGUAGE, true);
                            if(array_key_exists($options,$product_options) && $product_options[$options] = $varient){
                                $product_data[$product_id]['product_options'][$options] = $varient;                      
                            }else{
                                
                                $error = array('status' => "Failed", "msg" => "No such option/varient available for product id ".$product_id);
                                $this->response($this->json($error), 400); 
                            }                                                  
                       
                        }
                        else{                        
                            $error = array('status' => "Failed", "msg" => "Invalid parameter option_id/varient id pair");
                            $this->response($this->json($error), 400);                        
                        }
                    }                    
                    
                    $prev_wishlist = $wishlist['products'];
                    $product_ids = fn_add_product_to_wishlist($product_data, $wishlist, $auth);
                    fn_save_cart_content($wishlist, $user_id, 'W');
                    $added_products = array_diff_assoc($wishlist['products'], $prev_wishlist);
                    return $added_products;
                }

                public function get_wishlistItems($user_id){
                    //echo "<pre>";
                    $wishlist = array();
                    $response = array();
                    $this->getShort_wishlist($wishlist,$user_id);
                    if(empty($wishlist)){                             
                        $error = array('status' => "Success", "msg" => "Wishlist is Empty");
                        $this->response($this->json($error), 200);
                        
                    } else {
                        
                        $auth = array();
                        //$auth = $_SESSION['auth'];
                        $auth['user_id'] = $user_id;
                        
                        $auth['usergroup_ids'] = array();
                        $auth['usergroup_ids'][0] = 0;
                        $auth['usergroup_ids'][1] = 2;
                        
                        $products = !empty($wishlist['products']) ? $wishlist['products'] : array();
                        if (!empty($products)) {
                            foreach($products as $k => $v) {
                                    unset($products[$k]['session_id'], $wishlist['products'][$k]['session_id']);
                                    $_options = array();
                                    $extra = $v['extra'];
                                    if (!empty($v['product_options'])) {
                                            $_options = $v['product_options'];
                                    }
                                    $products[$k] = fn_get_product_data($v['product_id'], $auth);
                                    if (empty($products[$k])) {
                                            unset($products[$k], $wishlist['products'][$k]);
                                            continue;
                                    }

                                    $products[$k]['extra'] = empty($products[$k]['extra']) ? array() : $products[$k]['extra'];
                                    $products[$k]['extra'] = array_merge($products[$k]['extra'], $extra);

                                    if (isset($products[$k]['extra']['product_options']) || $_options) {
                                            $products[$k]['selected_options'] = empty($products[$k]['extra']['product_options']) ? $_options : $products[$k]['extra']['product_options'];
                                    }

                                    if (!empty($products[$k]['selected_options'])) {
                                        $options = fn_get_selected_product_options($v['product_id'], $v['product_options'], CART_LANGUAGE);
                                        foreach ($products[$k]['selected_options'] as $option_id => $variant_id) {
                                            foreach ($options as $option) {
                                                if ($option['option_id'] == $option_id && !in_array($option['option_type'], array('I', 'T', 'F')) && empty($variant_id)) {
                                                        $products[$k]['changed_option'] = $option_id;
                                                        break 2;
                                                }
                                            }
                                        }
                                    }
                                    $products[$k]['display_subtotal'] = $products[$k]['price'] * $v['amount'];
                                    $products[$k]['display_amount'] = $v['amount'];
                            }
                        }
                        fn_gather_additional_products_data($products, array('get_icon' => true, 'get_detailed' => true, 'get_options' => true, 'get_discounts' => true));
                        $response['wishlist'] = $wishlist;
                        $response['products_info'] = $products;
                            
                    }               
                    
                    return $response;
                                       
                }
                
                public function deleteWishlist($user_id){
                    
                    $cart_id = $this->_request['cart_id'];
                    if(!isset($cart_id)){
                            $msg = array('status' => "Error", "msg" => "Cart id cannot be empty");
                            $this->response($this->json($msg), 400);  
                    }

                    if(!(is_numeric($cart_id))){
                        $error = array('status' => "Failed", "msg" => "Please enter a valid cart id");
                        $this->response($this->json($error), 400);
                    }
                    $cart_id = intval($cart_id);
                    //clear all wishlist content
                    if(isset($cart_id) && $cart_id == 0 ){
                        $wishlist = array();
                        fn_save_cart_content($wishlist, $user_id, 'W');
                        $msg = array('status' => "Success", "msg" => "Wishlist of user "."  $user_id"." Cleared");
                        $this->response($this->json($msg), 200);
                    }         
                    
                    $valid = db_get_row("SELECT * FROM ?:user_session_products WHERE user_id = ?i AND item_id = ?i AND TYPE = 'W' ", $user_id,$cart_id);
                    if(empty($valid)){
                        
                        $msg = array('status' => "Failed", "msg" => "User id and cart id dosen't match" );
                        $this->response($this->json($msg), 400);
                        
                    }
                    
                    //for deleting individual item in wishlist.
                    $wishlist = array();
                    $this->getShort_wishlist($wishlist, $user_id);
                    fn_delete_wishlist_product($wishlist, $cart_id);
                    fn_save_cart_content($wishlist, $user_id, 'W');
                    
                    $error = array('status' => "Success", "msg" => "Wishlist of user id "."  $user_id"." with id ".$cart_id." deleted");
                    $this->response($this->json($error), 200);                                
                    
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
