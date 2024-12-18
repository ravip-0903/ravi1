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
// $Id: categories.php 12865 2011-07-05 06:57:22Z 2tl $
//

if ( !defined('AREA') ) { die('Access denied'); }

$_REQUEST['category_id'] = empty($_REQUEST['category_id']) ? 0 : $_REQUEST['category_id'];

if ($mode == 'catalog') {
	fn_add_breadcrumb(fn_get_lang_var('catalog'));

	$root_categories = fn_get_subcategories(0);

	foreach ($root_categories as $k => $v) {
		$root_categories[$k]['main_pair'] = fn_get_image_pairs($v['category_id'], 'category', 'M');
	}

	$view->assign('root_categories', $root_categories);

} elseif($mode == 'view'){

	if(isset($_REQUEST['jlt_product_id']))
	{
		if(isset($_SESSION['auth']['user_id']) && !empty($_SESSION['auth']['user_id']))
		{
			$jlt_product_id = $_REQUEST['jlt_product_id'];
			if(is_numeric($jlt_product_id))
			{
				$comment_limit = " limit 0,".Registry::get('config.jlt_comment_limit');
				$response = db_get_array("SELECT cjc.comment,cu.firstname FROM `clues_jlt_comment` cjc 
					inner join cscart_users cu on cjc.user_id = cu.user_id 
					where cjc.product_id=$jlt_product_id order by cjc.creation_time desc $comment_limit");
				if($response)
				{
					foreach ($response as $key=>$val) { 
						if(empty($val['firstname']))
						{
							$response[$key]['firstname'] = fn_get_lang_var('jlt_anonymous_logged_in');
						}
					}
					$jlt_data = array("html"=>$response);
					echo json_encode($jlt_data);
				}
				else
				{
					echo json_encode(array("html"=>""));
				}
				
			}
			exit;
		}
		else
		{
			echo 0;
			exit;
		}
		exit;
	}

	if(isset($_REQUEST['jltproduct_id']) && isset($_REQUEST['comment']))
	{
		if(isset($_SESSION['auth']['user_id']) && !empty($_SESSION['auth']['user_id']))
		{
			$jlt_product_id = $_REQUEST['jltproduct_id'];
			$comments = addslashes($_REQUEST['comment']);
			if(is_numeric($jlt_product_id))
			{
				$time = time();
				$user_logged = $_SESSION['auth']['user_id'];
				db_query("insert into clues_jlt_comment (product_id,user_id,comment,creation_time) values('".$jlt_product_id."','".$user_logged."','".$comments."',$time)");
			}
			$uname = empty($_SESSION['cart']['user_data']['firstname']) ? fn_get_lang_var('jlt_anonymous_logged_in'):$_SESSION['cart']['user_data']['firstname'];
			echo json_encode(array("firstname"=>$uname));
			exit;
		}
		else
		{
			echo 0;
			exit;
		}
		exit;
	}

	$_statuses = array('A', 'H');
	$_condition = ' AND (' . fn_find_array_in_set($auth['usergroup_ids'], 'usergroup_ids', true) . ')';
	$_condition .= fn_get_localizations_condition('localization', true);

	// Added by Sudhir dt 29th Sept 2012 to show company name in breadcrumb at merchant microsite
            if(isset($_REQUEST['company_id']) && $_REQUEST['company_id']){
		$company_data = !empty($_REQUEST['company_id']) ? fn_get_company_data($_REQUEST['company_id']) : array();
		fn_add_breadcrumb($company_data['company'], "companies.view?company_id=".$company_data['company_id']);
            }
	// Added by Sudhir dt 29th Sept 2012 to show company name in breadcrumb at merchant microsite end here

	if ($auth['area'] != 'A' || empty($_REQUEST['action']) || $_REQUEST['action'] != 'preview') {
		$_condition .= db_quote(' AND status IN (?a)', $_statuses);
	}

//	$is_avail = db_get_field("SELECT category_id FROM ?:categories WHERE category_id = ?i ?p", $_REQUEST['category_id'], $_condition);
	if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
	{
		$memcache = $GLOBALS['memcache'];
		$key = md5($_SERVER['QUERY_STRING'].'-is_avail');
		if($mem_value = $memcache->get($key)){
			$is_avail = $mem_value; 
		}else{
			$is_avail = db_get_field("SELECT category_id FROM ?:categories WHERE category_id = ?i ?p", $_REQUEST['category_id'], $_condition);
			$status = $memcache->set($key, $is_avail, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time')); // or die ("Failed to save data at the server");
                        if(!$status){
                            $memcache->delete($key);
                        }
		}
	}else{	
		$is_avail = db_get_field("SELECT category_id FROM ?:categories WHERE category_id = ?i ?p", $_REQUEST['category_id'], $_condition);
	}

	if (!empty($is_avail)) {

		// Save current url to session for 'Continue shopping' button
		$_SESSION['continue_url'] = "categories.view?category_id=$_REQUEST[category_id]";

		// Save current category id to session
		$_SESSION['current_category_id'] = $_SESSION['breadcrumb_category_id'] = $_REQUEST['category_id'];

		
		// Get subcategories list for current category
		//$view->assign('subcategories', fn_get_subcategories($_REQUEST['category_id']));
		
		if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
		{
			$memcache = $GLOBALS['memcache'];
			$key = md5($_REQUEST['category_id'].'-subcat_list');
			if($mem_value = $memcache->get($key)){
					$subcat_list = $mem_value;
					$view->assign('subcategories', $subcat_list);
			}else{
					$subcat_list = fn_get_subcategories($_REQUEST['category_id']);
					$status = $memcache->set($key, $subcat_list, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time')); // or die ("Failed to save data at the server");
					if(!$status){
                                            $memcache->delete($key);
                                        }
                                        $view->assign('subcategories', $subcat_list);
			}
		}else{
			$view->assign('subcategories', fn_get_subcategories($_REQUEST['category_id']));
		}
		
		// Get full data for current category
		 if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
                {
                        $memcache = $GLOBALS['memcache'];
                         $key = md5($_SERVER['QUERY_STRING'].'-category_data');
                        if($mem_value = $memcache->get($key)){
                                $category_data = $mem_value;
                        }else{
								$category_data = fn_get_category_data($_REQUEST['category_id'], CART_LANGUAGE, '*');
                                $status = $memcache->set($key, $category_data, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time')); // or die ("Failed to save data at the server");
                                if(!$status){
                                        $memcache->delete($key);
                                }
                        }
                }else{
			$category_data = fn_get_category_data($_REQUEST['category_id'], CART_LANGUAGE, '*');
		}

        $root = fn_get_root_category($category_data['id_path']);
        $under_nrh = 'N';
        if(Registry::get('config.nrh_root_category_id') == $root) {
            $under_nrh = 'Y';
        }
        $view->assign('under_nrh', $under_nrh);


		if (!empty($category_data['meta_description']) || !empty($category_data['meta_keywords'])) {
			$view->assign('meta_description', $category_data['meta_description']);
			$view->assign('meta_keywords', $category_data['meta_keywords']);
		}
                
		$params = $_REQUEST;
		$params['cid'] = $_REQUEST['category_id'];
		$params['extend'] = array('categories', 'description');
		if (Registry::get('settings.General.show_products_from_subcategories') == 'Y') {
			$params['subcats'] = 'Y';
		}
		//echo '<pre>';print_r($_REQUEST);die;
		if($category_data['is_meta'] != 'Y' || (isset($_REQUEST['features_hash'])) || isset($_REQUEST['company_id'])) {
                    if($category_data['is_nrh'] == 'N'){
		
				if(!empty($params['category_id']) && Registry::get('config.category_solr')) {	    		
					$parentCat = fn_check_parent_id($params['category_id']);
				}
	         	if(Registry::get('config.category_solr') && $parentCat != '0') {
			        		try{
                            	$arr = fn_get_solr_categories($params, 100);
                            	//echo '<pre>'; print_r($arr); echo '</pre>';  //die('categories.php');
	                            $prd = $arr['products'];
	                            $products_count = $arr['products_count'];
	                            $items_per_page = $arr['items_per_page'];
	                            
                            }catch(Exception $e){
                                
                            }
                            $search = $params;
                            $products = array();
                    $i=0;
					if(is_array($prd))                           
                           foreach($prd as $pr=>$pord){
                                $products[] = (array) $pord;
								$products[$i]['timestamp'] = $products[$i]['newarrivals'];                                    
                            $i++;
                           }
                            $view->assign('product_count', $products_count);
                            fn_paginate($params['page'], $products_count, $items_per_page);    
                            fn_view_process_results('products', $products, $params, $items_per_page);
                    if(!isset($params['page'])) {
						$category_name = (isset($params['cid']) && $params['cid']!='' && $params['cid']!=0) ? fn_get_category_name($params['cid']) : 'All';
						$time_exec = microtime(true) - $time_start;
		                $content = $params['q'].':'.$products_count.':'.$category_name.':'. date('d-M-Y h-i-s').':'.$time_exec;
						log_to_file('categorystats',$content);
					}
			} else {
				list($products, $search) = fn_get_products($params, Registry::get('settings.Appearance.products_per_page'));
			}
			$selected_layout = fn_get_products_layout($_REQUEST);
			$view->assign('category_id', $_REQUEST['category_id']);

			// Added by Sudhir for similar search result when no product found for search dt 23rd jan 2013 bigin here
			if(count($products) == 0 && isset($_SESSION['products_similar']) && isset($params['features_hash'])){
				$last_sr = array_diff($_SESSION['products_similar'], $params['features_hash']);
				$last_sr1 = array_diff($params['features_hash'], $_SESSION['products_similar']);
				$last_sr = array_values($last_sr);
				$last_sr1 = array_values($last_sr1);

				if(count($last_sr) > 0){
					$last_sr_opt = $last_sr;
				} else {
					$last_sr_opt = $last_sr1;
				}

				$params['features_hash'] = $_SESSION['products_similar'];
				list($products_similar, $search) = fn_get_products($params, Registry::get('settings.Appearance.products_per_page'));
			}else{
				if(isset($_SESSION['products_similar']) && $_SESSION['products_similar']){
					unset($_SESSION['products_similar']);
				}
                                if(isset($params['features_hash'])){
                                    $_SESSION['products_similar']=$params['features_hash'];
                                }
                             }
			// Added by Sudhir for similar search result when no product found for search dt 23rd jan 2013 end here

				
				//condition by ankur to call this function only in list view
	        		if(isset($_REQUEST['layout']) && $_REQUEST['layout']=='products'){
					if(count($products) != 0){
						fn_gather_additional_products_data($products, array('get_icon' => true, 'get_detailed' => true, 'get_options' => true, 'get_discounts' => true, 'get_features' => true));
					} else {
						fn_gather_additional_products_data($products_similar, array('get_icon' => true, 'get_detailed' => true, 'get_options' => true, 'get_discounts' => true, 'get_features' => true));
					}
				} else {
					if(count($products) != 0){
						foreach($products as &$product)
						{
							fn_set_hook('get_additional_product_data_before_discounts', $product, $auth, $params['get_options'], $params);
			
			
							if (empty($product['discount']) && !empty($product['list_price']) && !empty($product['price']) && floatval($product['price']) && $product['list_price'] > $product['price']) 
							{
								$product['list_discount'] = fn_format_price($product['list_price'] - $product['price']);
								$product['list_discount_prc'] = sprintf('%d', round($product['list_discount'] * 100 / $product['list_price']));
							}
						}
					} else if(isset($products_similar)) {
						foreach($products_similar as &$product)
						{
							fn_set_hook('get_additional_product_data_before_discounts', $product, $auth, $params['get_options'], $params);
			
			
							if (empty($product['discount']) && !empty($product['list_price']) && !empty($product['price']) && floatval($product['price']) && $product['list_price'] > $product['price']) 
							{
								$product['list_discount'] = fn_format_price($product['list_price'] - $product['price']);
								$product['list_discount_prc'] = sprintf('%d', round($product['list_discount'] * 100 / $product['list_price']));
							}
						}
					}
				}
                    }
		}

		$selected_layout = fn_get_products_layout($_REQUEST);

		if(isset($products_similar) && $products_similar){
			$view->assign('products_similar', $products_similar);
			$view->assign('products', $products_similar);
			$view->assign('last_search',$last_sr_opt[0]);
		} else if(isset($products)){
			$view->assign('products', $products);
		}
                if(isset($search)){
		$view->assign('search', $search);
                }
		$view->assign('selected_layout', $selected_layout);

                //code added by rahul to add extra seo url..
		$seo_names = basename($_SERVER['REDIRECT_URL'],".html");
		 $new_seo_data = db_get_field("select description from clues_seo_affiliation where object_id=".$_REQUEST['category_id']." and name='".$seo_names."'"); 
		if(!empty($new_seo_data))
		{
			$category_data['description'] = $new_seo_data;
		}
                //code ends here
		$view->assign('category_data', $category_data);

		// If page title for this category is exist than assign it to template
		if (!empty($category_data['page_title'])) {
			 $view->assign('page_title', $category_data['page_title']);
		}
		fn_define('FILTER_CUSTOM_ADVANCED', true); // this constant means that extended filtering should be stayed on the same page

		if (!empty($_REQUEST['advanced_filter']) && $_REQUEST['advanced_filter'] == 'Y') {
			list($filters) = fn_get_filters_products_count($_REQUEST);
			$view->assign('filter_features', $filters);
		}
		// [Breadcrumbs]
		$parent_ids = explode('/', $category_data['id_path']);
		array_pop($parent_ids);

		if (!empty($parent_ids)) {
			$cats = fn_get_category_name($parent_ids);
			foreach($parent_ids as $c_id) {
				fn_add_breadcrumb($cats[$c_id], "categories.view?category_id=$c_id");
			}
		}

		fn_add_breadcrumb($category_data['category'], (empty($_REQUEST['features_hash']) && empty($_REQUEST['advanced_filter'])) ? '' : "categories.view?category_id=$_REQUEST[category_id]");
		if (!empty($params['features_hash'])) {
			fn_add_filter_ranges_breadcrumbs($params, "categories.view?category_id=$_REQUEST[category_id]");
		} elseif (!empty($_REQUEST['advanced_filter'])) {
			fn_add_breadcrumb(fn_get_lang_var('advanced_filter'));
		}
                if($_REQUEST['isis']==1){
                        $view->assign('page_new', $_REQUEST['page']+1);
                        $view->assign('products', $products);
                        if($selected_layout === 'products_multicolumns3'){
                                $ajax_products = $view->display('blocks/list_templates/grid_list_ajaxified.tpl', false);}
                       elseif($selected_layout === 'just_like_that_products_grid'){
                                $ajax_products = $view->display('blocks/list_templates/jlt_grid_ajaxified.tpl', false);}   
                        else{
                                $ajax_products = $view->display('blocks/list_templates/products_grid_ajaxified.tpl', false);}
                        echo $ajax_products;exit;
                }
		// [/Breadcrumbs]
	} else {
		//return array(CONTROLLER_STATUS_NO_PAGE);
		/*Modified by clues dev to redirect if product no found*/
		//fn_set_notification('N', fn_get_lang_var('notice'), fn_get_lang_var('no_category_found'));
		return array(CONTROLLER_STATUS_REDIRECT, $index_script);
		/*Modified by clues dev to redirect if product no found*/
	}

} elseif ($mode == 'picker') {

	$category_count = db_get_field("SELECT COUNT(*) FROM ?:categories");
	if ($category_count < CATEGORY_THRESHOLD) {
		$params = array (
			'simple' => false
		);
 		list($categories_tree, ) = fn_get_categories($params);
 		$view->assign('show_all', true);
	} else {
		$params = array (
			'category_id' => $_REQUEST['category_id'],
			'current_category_id' => $_REQUEST['category_id'],
			'visible' => true,
			'simple' => false
		);
		list($categories_tree, ) = fn_get_categories($params);
	}

	if (!empty($_REQUEST['root'])) {
		array_unshift($categories_tree, array('category_id' => 0, 'category' => $_REQUEST['root']));
	}
	$view->assign('categories_tree', $categories_tree);
	if ($category_count < CATEGORY_SHOW_ALL) {
		$view->assign('expand_all', true);
	}
	if (defined('AJAX_REQUEST')) {
		$view->assign('category_id', $_REQUEST['category_id']);
	}
	$view->display('pickers/categories_picker_contents.tpl');
	exit;
	
} elseif ($mode == 'view_all') {

	$categories = fn_my_changes_get_product_data_more();
	$view->assign('categories', $categories);
}

?>
