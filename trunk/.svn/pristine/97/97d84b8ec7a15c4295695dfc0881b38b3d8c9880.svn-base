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

        require_once   DIR_ADDONS . 'cluessearch/func.php';

	class categories extends REST {

		public $data = "";

		public function __construct(){
			parent::__construct();				// Init parent contructor
		}
		


		/*
		 *  category API
		 *  Login must be POST method
		 */

                function traverse_category($category_id){
                                    
                    $cat_ids = $category_id;
                    $data = db_get_array("SELECT cd.category_id FROM cscart_categories cc
                            LEFT JOIN cscart_category_descriptions cd ON cc.category_id = cd.category_id
                            WHERE cc.status='A'
                            AND cc.parent_id=".$category_id);
                    if(count($data) == 0){
                        return $cat_ids;
                    }    
                    foreach($data as $k=>$v){
                        $cat_ids .= ','.$this->traverse_category($v['category_id']);
                    }
                    return $cat_ids;          
                }
                
                 function format_filter($key){                     
                     $key = ucwords(str_replace('_', ' ', $key));                     
                     return $key;
            }
                
            
		function category($hot_deals=''){

			// Cross validation if the request method is GET else it will return "Not Acceptable" status
			if($this->get_request_method() != "GET"){
                            $this->response('',406);
			}
			if(!empty($this->_request['cat_id'])){
                            if(is_numeric($this->_request['cat_id']) ){
                                $cat_id = $this->_request['cat_id'];
                            } 
                            else{
                                $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_category_id'));
                                $this->response($this->json($error), 400);
                            }
			} 
                        else{
                            $cat_id = 0;
			}
                        
                        if($hot_deals){                            
                            $fields = "cd.category_id, cd.category";
                            $condition = "third_price>0";
                            if(Registry::get('config.api_only_cod_products')) {
                                $condition .= " and products.is_cod='Y'";
                            }
                        }
                        else{
                            $path = Registry::get('config.ext_images_host');
                            $max_size = MAX_FILES_IN_DIR;
                            $fields = "cd.category_id,cc.seo_path as seo_name, cd.category,cc.position,cc.app_position,CONCAT('$path/images/detailed/',floor(cil.detailed_id/$max_size),'/',ci.image_path) 
                                        as image_path";
                             $condition = "1";
                        }
                            
                        $result1 = db_get_array("SELECT ". $fields." FROM cscart_categories cc
                        LEFT JOIN cscart_category_descriptions cd ON cc.category_id = cd.category_id
                        LEFT JOIN cscart_images_links cil on cc.category_id=cil.object_id and cil.object_type='category'
                        LEFT JOIN cscart_images ci on cil.detailed_id=ci.image_id
                        WHERE cc.status='A'
                        AND cc.parent_id=".$cat_id);			
                                                
                        if( (count($result1) == 0 || $hot_deals) && $cat_id !==0  )
                        {                            
                            if($hot_deals){
                                $cat_id = $this->traverse_category($this->_request['cat_id']);
                                unset($result1);
                            }
                            
                            $sort_by = strtolower($this->_request['sort_by']);
                            $sort_order = strtolower($this->_request['sort_order']);
                            $sortings = array (
                                'code' => 'products.product_code',
                                'status' => 'products.status',
                                'product' => 'descr1.product',
                                'position' => 'products_categories.position',
                                'price' => 'prices.price',
                                'list_price' => 'products.list_price',
                                'weight' => 'products.weight',
                                'amount' => 'products.amount',
                                'timestamp' => 'products.timestamp',
                                'boost_index' => 'products.boost_index',
                                'popularity' => 'popularity.total',
                                'hotdeals' => 'products.deals_index',
                                'newarrivals' => 'products.product_id',
                                'featured' => 'products.feature_index',
                                'company' => 'company_name',
                                'last_update' => 'products.last_update',
                                'feature_index' => 'products.feature_index',
                                'deals_index' => 'products.deals_index',
                                'product_sales_amount' => 'cscart_product_sales.amount',
                                'clues_popularity' => 'pp.total',
                                'pro_popularity' =>'?:product_popularity.total',
                                'null' => 'NULL'
                                );

                             if($sort_by == 'popularity' || $sort_by == 'hotdeals' || $sort_by == 'newarrivals' || $sort_by == 'featured'){
                                $sort_order = 'desc';
                             }
                             elseif($sort_by!='price' && $sort_by!='product'){
                                $sort_by = 'popularity';
                                $sort_order = 'desc';

                             }
                             if($sort_order!='asc' && $sort_order!='desc'){
                                $sort_order = 'desc';
                             }
                             /* Sorting Filters Ends Here */
                            $page = 1;
                            if(!empty($this->_request['page'])){
                                if(!(is_numeric($this->_request['page']))){
                                    $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_page'));
                                    $this->response($this->json($error), 400);

                                }
                                $page = $this->_request['page'];
                            }

                            //$per_page = Registry::get('settings.Appearance.products_per_page');
                            $per_page = Registry::get('config.api_listing_per_page');; 
                            $offset = ($page-1)*$per_page;
        $multi_cats = false;
        if(strstr($cat_id,',')) {
            $multi_cats = true;
        }                            
        if(Registry::get('config.category_solr') && $multi_cats != true) {
            $params = $this->_request;  
            $params['sort_by'] = $sort_by;
            $params['sort_order'] = $sort_order;
            $params['page'] = $page;
            $params['cid'] = $cat_id;
            $params['category_id'] = $cat_id;

            if(Registry::get('config.api_only_cod_products')) {
                $params['is_cod'] = 'Y';
            }
        
            try{
                    //create an array for response                  
                    //$result = fn_assign_filtertoitem($params);
//print_r($params);
                    // for category page, show products                            
                    $arr = fn_get_solr_categories($params, $per_page);
//print_r($arr);
                    if(!empty($arr)){
                        
                        $priceArr = $arr['price'];
                        if(!empty($priceArr)) {
                            unset($arr['price']);
                            $ctr = 0;
                            foreach($priceArr as $key=>$val) {
                                $pkey = fn_assign_pricekey($val['key'],'val');
                                $priceArr2[$ctr]['id'] = $pkey;
                                $priceArr2[$ctr]['name'] = fn_showprice($pkey,'key');
                                $priceArr2[$ctr]['count'] = $val['val'];
                                $ctr++;
                            }
                            $arr['price'] = $priceArr2;
                        }                    

                        $discountArr = $arr['discount_percentage'];
                        if(!empty($discountArr)) { 
                            unset($arr['discount_percentage']);
                            $ctr = 0;
                            foreach($discountArr as $key=>$val) {
                                $dkey = fn_assign_discountkey($val['key'],'val');
                                $discountArr2[$ctr]['id'] = $dkey;
                                $discountArr2[$ctr]['name'] = fn_showdiscount($dkey,'key');
                                $discountArr2[$ctr]['count'] = $val['val'];
                                $ctr++;
                            }
                            $arr['discount_percentage'] = $discountArr2;                    
                        }
                        
                        $dpArr = $arr['product_amount_available'];
                          if(!empty($dpArr)) {           
                              unset($arr['product_amount_available']);
                              $ctr = 0;
                              $out_of_stock = fn_get_lang_var('api_out_of_stock');
                              $in_stock = fn_get_lang_var('api_in_stock');
                              foreach($dpArr as $key=>$val) {
                                  $dpArr2[$ctr]['id'] = $val['key'];
                                  $dpArr2[$ctr]['name'] = ($val['key']==0) ? $out_of_stock : $in_stock;
                                  $dpArr2[$ctr]['count'] = $val['val'];
                                  $ctr++;
                              }
                              $arr['product_amount_available'] = $dpArr2;                    
                          }
                          
                        $prodArr = $arr['products'];
                        if(!empty($prodArr)) {           
                            unset($arr['products']);
                            $ctr = 0;
                            foreach($prodArr as $key=>$val) {
                                $pid = $val['product_id'];
                                $prodArr2[$ctr] = $val;
                                    
                                $average_rating=fn_get_average_rating($pid,'P');
                                $is_new = check_product_for_new($val);
                                $prodArr2[$ctr]['average_rating'] =$average_rating;
                                $prodArr2[$ctr]['is_new'] =$is_new;
                                    
                                $image_url = Registry::get('config.internal_images_host').'/'.$val['image_url'];
                                //$image_url = str_replace('320','160', $image_url);
                                //$image_url = str_replace('_','', $image_url);
								$image_url = fn_remove_specialchars_from_image_path($image_url, 160);
                                $prodArr2[$ctr]['image_url'] = $image_url; 
								
								//calculate 3rd price if third price from solr is disabled
								if( $prodArr2[$ctr]['promotion_id'] != 0 &&  Registry::get('config.app_calculate_thirdPrice')){
									$third_price =  fn_get_3rd_price( $prodArr2[$ctr]);
									if($third_price == false){
										$prodArr2[$ctr]['third_price'] = 0;
									}
									else{
										$prodArr2[$ctr]['third_price'] = $third_price;
									}
								}
                                $prodArr2[$ctr]['deals_index'] = ''; //$val['deal_index'];
                                $prodArr2[$ctr]['full_description'] = '';//$val['short_description'];
                                $prodArr2[$ctr]['one_day_sale_end_datetime'] = '';
                                $prodArr2[$ctr]['one_day_sale_short_text'] = '';
                                $prodArr2[$ctr]['one_day_sale_start_datetime'] = '';
                                $prodArr2[$ctr]['transfer_price'] = '0';
                                $prodArr2[$ctr]['view_count'] = '1';
                                $ctr++;
                            }
                            $arr['products'] = $prodArr2;                    
                        }                         
                        
                        //category page filters
                        $notfilter = array('products','category','products_count','items_per_page');
                        foreach($arr as $key => $val) {                                        
                            if(in_array($key, $notfilter)) {
                                $result[$key] =  $val;
                                //$result[$key][] =  $val;                                
                            } 
                            else{
                                 foreach($val as $k => $v){                                     
                                    if($v['count'] > 0){
                                       $display_filter = $this->format_filter($key);
                                        $result['filters'][$key][] = $v;
                                        //$result['filters'][$key][][$k] = $v;
                                    }
                                }
                                //$result['filters'][$key] =  $val;                         
                            }                   
                        }
                        //echo '<pre>'; print_r($result); echo '</pre>'; die; 
                        
                            unset($result['category']); // category not needed
                            $result['category_data'] = fn_get_category_data($cat_id, CART_LANGUAGE, '*');                                          
                            if($result){                                
                                    if($hot_deals){
                                        return $result['products'];                                    
                                    }
									//print_r($result['products']);die;
									$required = array(  'product_id',
														'list_price',
														'price_see_inside',
														'deal_inside_badge',
														'special_offer_badge',
														'freebee_inside',
														'product',
														'price',
														'third_price',
														'image_url',
														'average_rating',
														'is_new'
													);
									foreach($result['products'] as $k=>$v){
										$result['products'][$k] = $this->filter_response($v, $required);
									}
									unset($result['category_data']);
                                    $this->response($this->json($result), 200);
                            }
                            $this->response('',204);	// If no records "No Content" status

                    }else{
                        $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_no_content'));
                        $this->response($this->json($error), 204);
                    }                    

            } catch(Exception $e) {
                $error = array('status' => "Failed", "msg" => "Exception on category.php line 220 ".$e->getMessage());
                $this->response($this->json($error), 204);
            }                 

	} else {
                            
                            $flds = "SELECT SQL_CALC_FOUND_ROWS products.boost_index,products.product_id, products.list_price, products.timestamp, products.free_shipping, products.amount, products.view_count, products.deals_index, products.feature_index, products.transfer_price, products.special_offer_text, products.special_offer, products.one_day_sale_start_datetime, products.one_day_sale_end_datetime,products.one_day_sale_short_text, products.promotion_id, products.price_see_inside, products.deal_inside_badge, products.special_offer_badge,products.freebee_inside, products_categories.position, descr1.product as product, MIN(prices.price) as price,products.third_price, descr1.short_description, IF(descr1.short_description = '', descr1.full_description, '') as full_description, GROUP_CONCAT(IF(products_categories.link_type = 'M', CONCAT(products_categories.category_id, 'M'), products_categories.category_id)) as category_ids, CONCAT('images/thumbnails/',floor(if(i.image_id!=0, i.image_id,il.image_id)/1000),'/160/160/',REPLACE(REPLACE(image_path, ' ', ''), ' ', '')) as image_url ";
$joins = " INNER JOIN cscart_product_descriptions as descr1 ON descr1.product_id = products.product_id
    LEFT JOIN cscart_product_prices as prices ON prices.product_id = products.product_id
    LEFT JOIN cscart_companies AS companies ON companies.company_id = products.company_id
    INNER JOIN cscart_products_categories as products_categories ON products_categories.product_id = products.product_id
    INNER JOIN cscart_categories ON cscart_categories.category_id = products_categories.category_id AND cscart_categories.status IN ('A', 'H') LEFT JOIN cscart_product_popularity as popularity ON popularity.product_id = products.product_id
    left join cscart_product_options_inventory as inv on inv.product_id=products.product_id
    LEFT JOIN cscart_images_links il ON il.object_id = products.product_id and il.object_type = 'product' and il.type='M'
    LEFT JOIN cscart_images i ON il.detailed_id = i.image_id";




 $products = db_get_array("$flds
    FROM cscart_products as products
    $joins
    WHERE ".$condition." AND products.status='A' AND cscart_categories.category_id IN (".$cat_id.") AND (companies.status = 'A' OR products.company_id = 0)
    GROUP BY products.product_id ORDER BY if(products.amount=0,IF(sum(inv.amount)=0 OR sum(inv.amount) IS NULL,0,1),1) desc,products.boost_index asc,". $sortings[$sort_by]." ". $sort_order ." LIMIT ".$offset.",".$per_page);

 $total = count(db_get_array("select count(distinct products.product_id)
    FROM cscart_products as products
    $joins
    WHERE ".$condition." AND products.status='A' AND cscart_categories.category_id IN (".$cat_id.") AND (companies.status = 'A' OR products.company_id = 0)
    GROUP BY products.product_id ORDER BY if(products.amount=0,IF(sum(inv.amount)=0 OR sum(inv.amount) IS NULL,0,1),1) desc,products.boost_index asc"));
    
            $total_records = $total;
            
            foreach($products as $k => $v){
                $average_rating=fn_get_average_rating($v['product_id'],'P');
                $is_new = check_product_for_new($v);
                $products[$k]['average_rating'] =$average_rating;
                $products[$k]['is_new'] =$is_new;
                $products[$k]['image_url'] = Registry::get('config.internal_images_host').'/'.$products[$k]['image_url'];
                $products[$k]['image_url'] = fn_remove_specialchars_from_image_path($products[$k]['image_url'] , 160);
            }                         
            $result['products_count'] = $total_records;

            $result['items_per_page'] = $per_page;
            $result['products'] = $products;
        } // end of solr                   
                            $result['category_data'] = fn_get_category_data($cat_id, CART_LANGUAGE, '*');                  
                            if($result){
                                    if($hot_deals){
                                        return $result;                                    
                                    }
                                    $this->response($this->json($result), 200);
                            }
                            $this->response('',204);	// If no records "No Content" status
                    }
                    else{
                        if($result1){
                            // If success everythig is good send header as "OK" and best selling products
                            $this->response($this->json($result1), 200);
                        }
                        $this->response('',204);	// If no records "No Content" status
                    }
            }


            /*
             *  BestSellingProduct API
             *  Login must be POST method
             */

            public function bestsellers(){

                    // Cross validation if the request method is GET else it will return "Not Acceptable" status
                    if($this->get_request_method() != "GET"){

                            $this->response('',406);
                    }

                    $categories = db_get_array("select * from clues_merchandise_data where type='BESTSELLING' order by position asc");

                    if(!empty($categories) && count($categories)>0) {
                            require_once  DIR_ADDONS . 'bestsellingproduct/func.php';
                            if (Registry::get('settings.General.show_products_from_subcategories') == 'Y') {
                                    $params['subcats'] = 'Y';
                            }
                            foreach($categories as $category) {
                                    $category_array = explode(',',$category['value']);
                                    $params['cid'] = $category_array;
                                    $params['limit'] = Registry::get('config.api_bestseller_per_page');;
                                    $products["Best Selling ".$category['deal_title']] =fn_bestsellerproduct_get_products($params);

                            }
							/* Loop for Adding CDN image path and 3rd Price */
							foreach($products as $key1 => $value1)
							{
								foreach ($value1 as $key => $value)
								{
									$value['main_pair']= fn_get_image_pairs($value['product_id'], 'product', 'M', true, true, $lang_code = CART_LANGUAGE);
									$images_main = fn_get_img_path($value['main_pair'],160,160);
									$value['image_path'] = $images_main;
									$products[$key1][$key]['image_path'] = $value['image_path'];
									$third_price= fn_get_3rd_price($value);
									if($third_price == false){
										$products[$key1][$key]['third_price'] = 0;
									}
									else{
										$products[$key1][$key]['third_price'] = $third_price;
									}									
								}
							}
							/* Ends Here */
                            if($products){
                                    // If success everythig is good send header as "OK" and best selling products
                                    $this->response($this->json($products), 200);
                            }
                            $this->response('',204);	// If no records "No Content" status
                    }
            }
            /*
             * popular - gives list of popular categories id
             * popular?id=1 - gives categories attached to popular category
             * popular?cat_id=85 - gives same result as categories?cat_id=85
             */
            public function popular() {
                // simple routing
                if (!empty($this->_request['id'])) {
                    // route to get mapped categories of popular categories
                    $this->get_categories();
                }
                if (!empty($this->_request['cat_id'])) {
                    // route to get products - default behavior of categories?cat_id=85
                    $this->get_products();
                }
                $sort_by_allowed = array(
                  'clues_popular_category' => array('id', 'name', 'position')                  
                );
                $sort_by = (empty($this->_request['sort_by']) ? 'position' : $this->_request['sort_by']);
                if (!in_array($sort_by, $sort_by_allowed['clues_popular_category'])) {
                    $sort_by = ' position ';
                }
                if ($sort_by == 'id') {
                      $sort_by = ' popular_category_id ';
                }
                $sort_order_allowed = array('asc', 'desc');
                $sort_order = (empty($this->_request['sort_order']) ? 'desc' : $this->_request['sort_order']);
                if (!in_array($sort_order, $sort_order_allowed)) {
                    $sort_order = ' asc ';
                }
                $max_per_page = Registry::get('config.api_popular_category_max_limit');
                $per_page = (empty($this->_request['per_page']) ? $max_per_page : $this->_request['per_page']);
                $per_page = (int) $per_page;
                if (!is_integer($per_page)) {
                    $per_page = $max_per_page;
                }
                if ($per_page > $max_per_page) {
                    $per_page = $max_per_page;
                }
                $page = (empty($this->_request['page'])? 1: $this->_request['page']);
                $offset = ($page-1) * $per_page;
                $sql = "select * from clues_popular_category order by $sort_by $sort_order limit $offset , $per_page";
                $categories = db_get_array($sql);
                if ($categories) {
                    $this->response($this->json($categories), 200);
                }
                $this->response('',204);
            }
            
            public function get_categories() {
                $id = (int) $this->_request['id'];                
                $sql = "select map.category_id as cat_id, cd.category from clues_popular_category_mapping map
                        inner join cscart_categories c on c.category_id = map.category_id and map.status = 'A'
                        inner join cscart_category_descriptions cd on cd.category_id = c.category_id and c.status = 'A'
                        where map.popular_category_id = $id order by c.weight asc";
                $categories = db_get_array($sql);
                if ($categories) {
                    $this->response($this->json($categories), 200);
                }
                $this->response('',204);
            }
            
            public function get_products() {
                //@todo - optimize
                $this->category();
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
