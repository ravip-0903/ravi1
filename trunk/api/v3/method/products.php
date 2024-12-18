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


    require_once DIR_ADDONS . "my_changes/func.php";
	class products extends REST {

		public $data = "";

		public function __construct(){
			parent::__construct();				// Init parent contructor
		}
		


                /*
		 *	Simple Api for Displaying Product Page info
		 *
		 *	Created for returining the JSON object of the infos available on product page through api
		 .
		 * 	___________________________________________________________________________________
		 *			Quering Product Page info
		 * 	____________________________________________________________________________________
		 *
		 *	Request 	: Strictly GET
		 * 	Parameters
		 *	@product_id     : product id of the product page whose info is required.
		 *
		 * 	query format 	: api/products?product_id=12345
                 *
                 *
                 */

                public function products(){

                        $para = array();
                        require DIR_ADDONS . "sdeep/func.php";
                        
                        
                        
                        
                        $merchant_info=array();
                        if($this->get_request_method() != "GET"){
                                $this->response('',406);
                        }
                        $product_id =$this->_request['product_id'];
                        if(!(is_numeric($product_id))){
                            $error = array('status' => "ERROR", "msg" => fn_get_lang_var('api_param_invalid_params'));
                            $this->response($this->json($error), 400);
                        }

                        if(empty($product_id)){
                                $msg = array('status' => "Error", "msg" => fn_get_lang_var('api_param_invalid_params'));
                                $this->response($this->json($msg), 400);
                        }
                        
                        
                        
//                      $product = fn_get_product_data($product_id, $_SESSION['auth'], $lang_code = CART_LANGUAGE, $field_list = '', $get_add_pairs = true, $get_main_pair = true, $get_taxes = true, $get_qty_discounts = false, $preview = false, $features = true);

						$product = fn_get_product_data($product_id, $_SESSION['auth'], CART_LANGUAGE,'', true, true, true, true, false);
                        fn_gather_additional_product_data($product, true, true);
                        //print_r($product['main_pair']);die;
                        //$images_main = fn_get_img_path($product['main_pair'],160,160);
                        $images_main = fn_get_img_path($product['main_pair'],320,320);
                        $product['image_path'] = $images_main;
						
						//calculate 3rd price if third price from solr is disabled
						if($product['promotion_id'] != 0 &&  Registry::get('config.app_calculate_thirdPrice')){
							$third_price = fn_get_3rd_price($product);
							if($third_price == false){
								$product['third_price'] = 0;
							}
							else{
								$product['third_price'] = $third_price;
							}
						}
                        if(!empty($product['product_options'])){

                            foreach ($product['product_options'] as $key=>$val){

                                $pick_first = explode('-',$val['option_name']);
                                $product['product_options'][$key]['option_name'] = $pick_first[0];

                                }
                        }
                        $merchant_data = db_get_row("SELECT u.email,u.company_id,cpd.product as product_name,c.company as company_name from cscart_products p,cscart_companies c,cscart_users u,cscart_product_descriptions cpd where p.product_id=".$product_id." and cpd.product_id=".$product_id." and p.company_id=u.company_id and u.company_id=c.company_id");
                        $merchant_info['vendor_info'] = fn_sdeep_get_vendor_info($merchant_data['company_id']);
                        $merchant_info['rating'] = fn_sdeep_get_rating($merchant_data['company_id']);
                        $merchant_info['detail_rating'] = merchant_detail_rating($merchant_data['company_id']);
                        $merchant_info['auth_dealer_info'] = fn_sdeep_get_auth_dealer_info($merchant_data['company_id']);
                        $merchant_info['state'] = fn_get_vendor_state($merchant_data['company_id']);

                        $estimation_id = $product['product_shipping_estimation'];
                        $default_ship =  Registry::get('config.default_shipping_estimation');
                        if(!empty($estimation_id)){

                            $product['delivery_time'] = fn_my_changes_get_shipping_estimation($estimation_id);
                        }
                        else{

                            $product['delivery_time'] = fn_my_changes_get_shipping_estimation($default_ship);
                        }

						//response for coupon code
						if($product['promotion_id']!=0)
							$product['coupon_code'] = $this->fn_get_coupon_code_api($product['promotion_id']);
						if(!isset($product['coupon_code']))
							$product['coupon_code']="NOT ACTIVE";

                        $product['total_ratings'] =fn_get_average_rating_cnt($product_id,"P");
						$product['average_rating']=fn_get_average_rating($product_id,'P');
                        //$product['detail_rating'] = fn_get_detail_rating($product_id,"P");

                        $product['total_reviews'] = fn_get_discussion_count($product_id, 'P');
						$page = floor($product['total_reviews']/30);
			
                        //$product['recent_reviews'] = fn_get_recent_reviews($product_id,"P");
                        //$product['useful_reviews'] = fn_get_useful_reviews($product_id,"P");
                        $dis = fn_get_discussion($product_id, 'P');
                        $thread_id = $dis['thread_id'];
                        $product['all_reviews'] = fn_get_discussion_posts($thread_id,$page,30);
						//@Fix - remove hprconcat 
						foreach ($product['all_reviews'] as $k => $v) {
							$product['all_reviews'][$k]['message'] = str_replace('hprconcatsc','',$v['message']);
						}
						$product['website_link'] = 'http://' . Registry::get('config.http_host') .'/'.  $product['seo_name'].'.html'; 
						$product['social_share_msg'] = fn_get_lang_var('app_product_social_share_msg');
						$product['twitter_hash_tag'] = fn_get_lang_var('app_product_twitter_hash_tag');
						
						$required = array('feature_id','feature_type','description','variant_id','subfeatures','variant','selected',
										  'option_id','option_name','option_type','inventory','description','option_text','variants','variant_name'
							);
						foreach($product['product_options'] as $k => $v){	
							$product['product_options'][$k] = $this->filter_response($v, $required, TRUE);												
						}
						//filter the data in options and feature
						foreach($product['product_features'] as $k => $v){
							$product['product_features'][$k] = $this->filter_response($v, $required, TRUE);
							if(isset($v['subfeatures']) && is_array($v['subfeatures'])){
								foreach($v['subfeatures'] as $key => $value){
									if(isset($value['variants']) && is_array($value['variants'])){
										foreach($value['variants'] as $kk => $vv){
											$product['product_features'][$k]['subfeatures'][$key]['variants'][$kk] = $this->filter_response($vv, $required);									
										}
									}
								}
							}
						}			
						
                        $response = array();
                        $response['merchant_info'] = $merchant_info;
                        $response['product_info'] = $product;
						
                        if(!empty($product)){
                                // If success everythig is good send header as "OK" and best selling products
							$required = array(  'product_id', 'list_price', 'price_see_inside', 'deal_inside_badge', 'special_offer_badge',
												'freebee_inside', 'product', 'price', 'third_price', 'image_url', 'average_rating', 'is_new',
												'amount' , 'shipping_freight', 'free_shipping', 'is_cod', 'timestamp', 'feature_index', 'promotion_id',
												'deal_inside_badge', 'price', 'image_path' , 'full_description', 'coupon_code', 'average_rating',
												'total_ratings', 'all_reviews','status', 'product_features',  'website_link','product_options'	,'social_share_msg',
												'twitter_hash_tag'
										);
							
							$response['product_info'] = $this->filter_response($response['product_info'], $required, FALSE);									
							$this->response($this->json($response), 200);
                        } else {
                                $this->response('',204);	// If no records "No Content" status
                        }
                }

				public function fn_get_coupon_code_api($promotion_id){
					require_once DIR_ROOT . "/controllers/customer/mpromotion.php";
					$coupon_data=  db_get_field("select conditions_hash from cscart_promotions where promotion_id='" . $promotion_id. "' and status='A'");
					$coupon_code = fn_get_coupon_code($coupon_data);
					if(!isset($coupon_code))$coupon_code="NOT ACTIVE";
					return $coupon_code;
				   }
               
				public function check_inventory() {
					 $product_id = $this->_request['product_id'];
					 $amount = $this->_request['amount'];
					 $product_options = $this->_request['options'];
					 $is_avail = fn_check_amount_in_stock($product_id, $amount, $product_options);                   
					 //@todo : return the amount so that it can update the cart
					 if ($is_avail) {
						  if ($amount == $is_avail) {
							  $msg = 'Y';
						  }			
						  else {
							  $msg = fn_get_lang_var('api_option_out_of_stock');
						  }			
					 } 
					 else {
					   $msg = fn_get_lang_var('api_option_out_of_stock');
					 }   
					  $data['status'] = 'Success';
					  $data['msg'] = $msg;
					  $this->response($this->json($data), 200);
				}

                /*
		 *	Encode array into JSON
		 */

		function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}

	}

?>
