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


include   DIR_ADDONS . 'my_changes/func.php';
include   DIR_ADDONS . 'cluessearch/func.php';

class search extends REST {
	
		public $data = "";

		public function __construct(){
			parent::__construct();				// Init parent contructor
		}
		
		/* 
		 *	Simple search API
		 * 	Query must be in Get Method
		 *	q:  		<query string>
		 *	cid:		category id (obtained by clicking on the category filter)
		 *	fq(array):      filters the result with given price range id 
		 *	br(array):      filters the result with given brands id              
		 *	sort_by	:	price,bestsellers,hotdeals,featured....
		 *	sort_order:	asc or dsc   
		 *	Pagination:     offset and limit  
		 *
		 */
		 
    public function search(){
            if($this->get_request_method() != "GET"){
                        $this->response('',406);
            }


            if(empty($this->_request['q'])){
                    $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_search_string'));
                    $this->response($this->json($error), 400);
            }
                                
             //create an array for response
             $response = array();                   
            
             $params = $this->_request;
             $params['search_performed'] = 'Y';
            if(Registry::get('config.api_only_cod_products')) {
                $params['is_cod'] = 'Y';
            }
   //echo '<pre>'; print_r($params); echo '</pre>'; die;
            
              //$search_result = $this->get_products($params);
              $filter_result = fn_assign_filtertoitem($params);
              //echo '<pre>'; print_r($filter_result); echo '</pre>'; die;      
            
	
		$image_host =  Registry::get('config.internal_images_host');
              if(!empty($filter_result)) {
                //$prodArr2[$pid]['image_url'] = $image_url; 
 	
                    $brandArr = $filter_result['brand'];
                    if(!empty($brandArr)) {           
                        unset($filter_result['brand']);
                        $ctr = 0;
                        foreach($brandArr as $key=>$val) {
                            $bid = $val['brand_id'];
                            $brandArr2[$ctr]['id'] = $bid;
                            $brandArr2[$ctr]['name'] = $val['brand_name'];
                            $brandArr2[$ctr]['count'] = $val['count'];
                            $ctr++;
                        }
                        $filter_result['brand'] = $brandArr2;                    
                    }  
                    
                    $priceArr = $filter_result['price'];
                    if(!empty($priceArr)) {
                        unset($filter_result['price']);
                        $ctr = 0;
                        foreach($priceArr as $key=>$val) {
                            $pkey = fn_assign_pricekey($val['key'],'val');
                            $priceArr2[$ctr]['id'] = $pkey;
                            $priceArr2[$ctr]['name'] = fn_showprice($pkey,'key');
                            $priceArr2[$ctr]['count'] = $val['val'];
                            $ctr++;
                        }
                        $filter_result['price'] = $priceArr2;
                    }                   

                    $discountArr = $filter_result['discount_percentage'];
                    if(!empty($discountArr)) {   
                        unset($filter_result['discount_percentage']);
                        $ctr = 0;
                        foreach($discountArr as $key=>$val) {
                            $dkey = fn_assign_discountkey($val['key'],'val');
                            $discountArr2[$ctr]['id'] = $dkey;
                            $discountArr2[$ctr]['name'] = fn_showdiscount($dkey,'key');
                            $discountArr2[$ctr]['count'] = $val['val'];
                            $ctr++;
                        }
                        $filter_result['discount_percentage'] = $discountArr2;                    
                    } 

                    $dpArr = $filter_result['product_amount_available'];
                      $out_of_stock = fn_get_lang_var('api_out_of_stock');
                      $in_stock = fn_get_lang_var('api_in_stock');
                    if(!empty($dpArr)) {           
                        unset($filter_result['product_amount_available']);
                        $ctr = 0;
                        foreach($dpArr as $key=>$val) {
                            $dpArr2[$ctr]['id'] = $val['key'];
                            $dpArr2[$ctr]['name'] = ($val['key']==0) ? $out_of_stock : $in_stock;
                            $dpArr2[$ctr]['count'] = $val['val'];
                            $ctr++;
                        }
                        $filter_result['product_amount_available'] = $dpArr2;                    
                    }
                    
                    $prodArr = $filter_result['products'];
                    if(!empty($prodArr)) {           
                        unset($filter_result['products']);
                        $ctr = 0;
                        foreach($prodArr as $key=>$val) {
                            $pid = $val['product_id'];
			                $image_url = $image_host . '/' . $val['image_url'];
			                $image_url = fn_remove_specialchars_from_image_path($image_url, 160);
			                $val['image_url'] = $image_url;
							//calculate 3rd price if third price from solr is disabled
							if( $val['promotion_id'] != 0 &&  Registry::get('config.app_calculate_thirdPrice')){
								$third_price = fn_get_3rd_price( $val);
								if($third_price == false){
									$val['third_price'] = 0;
								}
								else{
									$val['third_price'] = $third_price;
								}
							}
                            $prodArr2[$ctr] = $val;
                            $ctr++;
                        }
                        $filter_result['products'] = $prodArr2;                    
                    }
                   

                    //search page filters
                    $notfilter = array('suggestion','products','category','products_count','items_per_page');
                    foreach($filter_result as $key => $val) {
                        if(in_array($key, $notfilter)) {
                            $response[$key] =  $val;    
                            //$response[$key][] =  $val;                      
                        } 
                        else{
                            foreach($val as $k => $v){                                     
                                if($v['count'] > 0){
                                    $response['filters'][$key][$k] = $v;
                                    //$response['filters'][$key][][$k] = $v;
                                }
                            }
                                //$result['filters'][$key] =  $val;                         
                        }                
                    }
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
										'product_rating',
										'is_new'													
								);

					foreach($response['products'] as $k=>$v){
						$response['products'][$k] = $this->filter_response($v, $required);
					}
                    //$response['products'] = $search_result;
                    $this->response($this->json($response), 200);
                }else{
                    $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_no_content'));
                    $this->response($this->json($error), 204);
                }
	}
                
                private function get_products($params){

                        if(Registry::get('config.solr')) {
                        //$limit = 32; 				// Default value for limit if only offset is passed.
                        $limit = 16; 				// Default value for limit if only offset is passed.
                        if(isset($params['limit']))
                                $limit = $params['limit'];

                        // calling solr function to connect and search keyword.
                        list($client, $query) = fn_solr_connect();
                        $query = fn_solr_search_field($query, $params['q']);

                       /*$client = new SolrClient(Registry::get('config.solr_url'));
                        $query = new SolrQuery();                        
                       if(!empty($params['q'])) {
                            $mm = Registry::get('config.min_match');
                                $query->setQuery("'".addslashes($params['q'])."'");
                            $query->setParam('defType','dismax');
                            $query->setParam('qf','product meta_keywords search_words page_title');
                            $query->setParam('mm', $mm);   
                            $query->setRows(32);      // to override solr default value of 10.
                        }*/
		
        
                        //add filter for category_id
                        if(isset($params['cid']) && ($params['cid'] != '0')){
                            $query->addFilterQuery("metacategory_id:".$params['cid'] ." OR ". "category_id:".$params['cid']);
                        }

                        //add filter for price range
                        if(isset($params['fq'])){
                            $fq='';
                            foreach($params['fq'] as $k=>$v){
                                    if($k > 0){
                                            $fq .= ' OR ';
                                    }
                                    $value = fn_assign_pricekey($v,'key');
                                    //echo $value;die();
                                    if(empty($value)){
                                        $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_filter'));
                                        $this->response($this->json($error), 400);

                                    }
                                    $fq .= $value;
                            }
                            try{
                                $query->addFilterQuery($fq);
                            }
                            catch(Exception $e){
                                //error_log("fn_get_solr_products_brand=".$e->getMessage(), 1, "vinay.gupta@shopclues.com");

                            }
                        }
                       //add filter for brands
                        if(isset($params['br'])){
                            $fq2='';
                            foreach($params['br'] as $kb=>$vb){
                                    if($kb > 0){
                                            $fq2 .= ' OR ';
                                    }
                                    $fq2 .= 'brand_id:'.$vb;
                            }
                            $query->addFilterQuery($fq2);
                        }
                        if(isset($params['sort_by'])){
                            $sort_type = $params['sort_by'];
                            if(strtolower($params['sort_order'])=="asc") {
                                $query->addSortField($sort_type, SolrQuery::ORDER_ASC);
                            } 
                            else
                                $query->addSortField($sort_type);                               
                        }

                        //add calculate the no of( items to display on the page
                        if(isset($params['offset'])){
                            $query->setStart($params['offset']);
                            $query->setRows($limit);                   
                        }  
                        //echo $query; die();
                        $products = array();

                        try{
                            $solrResult = $client->query($query)->getResponse();
                            $prd = $solrResult->response->docs;
                            $products_count = $solrResult->response->numFound;
                        }
                        catch(Exception $e){

                        }
                        $products['total_items'] =  $products_count;                    
                        foreach($prd as $pr=>$pord){
                            $products[] = (array) $pord;
                        }
                        
                        /* Adding CDN path in Images and adding 3rd price in search results */
                        
                        foreach($products as $key => $value)
                        {
                            $third_price = fn_get_3rd_price($value);
                            $image_url = Registry::get('config.internal_images_host').'/'.$value['image_url'];
                            $products[$key]['image_url'] = $image_url;
                            $products[$key]['3rd_price'] = $third_price;                           
                        }
                        
                        /* Ends Here */
                        
                        return $products;
                    }
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
