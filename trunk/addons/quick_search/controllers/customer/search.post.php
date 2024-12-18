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
// $Id: search.post.php 12865 2011-07-05 06:57:22Z 2tl $
//

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

	if ($mode == 'quick_search') {
		if (!empty($_REQUEST['q']) && strlen($_REQUEST['q']) >= Registry::get('addons.quick_search.min_search') && !empty($_REQUEST['result_ids'])) {
			$pattern = trim($_REQUEST['q']);
			$cid = empty($_REQUEST['cid']) ? 0 : intval($_REQUEST['cid']);
			
			$values = $result = $text = $news = $pages = array();
			
			$cache_id = 'quick_search' .  md5($pattern . $cid);
			Registry::register_cache('quick_search', array('quick_search', 'addons'), CACHE_LEVEL_LOCALE_AUTH . $cache_id);
			
			if (Registry::get('addons.quick_search.use_cache') == 'Y' && Registry::is_exist('quick_search')) {
				$result = Registry::get('quick_search');
				
			} else {
				$condition = '';
				
				if (Registry::get('settings.General.search_objects.pages') == 'Y') {
					$condition .= db_quote(' OR type = ?s', 'i');
				}
				if (Registry::get('settings.General.search_objects.news') == 'Y') {
					$condition .= db_quote(' OR type = ?s', 'n');
				}
				
				if (Registry::get('addons.quick_search.search_by_sku') == 'Y') {
					$values = array_merge($values, db_get_array('SELECT item_id, type FROM ?:quick_search WHERE type = ?s AND text like ?l LIMIT ?i', 's', "%$pattern%", Registry::get('addons.quick_search.product_search')));
				}
				
				if (Registry::get('addons.quick_search.search_in_titles') != 'Y') {
					$values = array_merge($values, db_get_array('SELECT item_id, type, MATCH(text) AGAINST(?s) as score FROM ?:quick_search WHERE (type = ?s OR type = ?s ' . $condition . ') AND MATCH(text) AGAINST(?s) AND lang_code = ?s GROUP BY item_id ORDER BY score DESC LIMIT ?i', $pattern, 'p', 't', $pattern, DESCR_SL, Registry::get('addons.quick_search.product_search')));
				}
				
				// Use LIKE operator, if no results found or count of found products less than the max search limit
				if (empty($values) || count($values) < Registry::get('addons.quick_search.product_search') || Registry::get('addons.quick_search.search_in_titles') == 'Y') {
					if (Registry::get('addons.quick_search.match_type') == 'any') {
						$pieces = explode(' ', $pattern);
						$search_type = ' OR ';
					} elseif (Registry::get('addons.quick_search.match_type') == 'all') {
						$pieces = explode(' ', $pattern);
						$search_type = ' AND ';
					} else {
						$pieces = array($pattern);
						$search_type = '';
					}
					
					$_condition = array();
					foreach ($pieces as $piece) {
						$tmp = db_quote("text LIKE ?l", "%$piece%"); // check search words
						$_condition[] = '(' . $tmp . ')';
					}
					
					if (!empty($_condition)) {
						$_cond = ' AND (' . implode($search_type, $_condition) . ')';
					} else {
						$_cond = '';
					}
					
					$values = array_merge($values, db_get_array('SELECT item_id, type FROM ?:quick_search WHERE (type = ?s ' . $condition . ') ' . $_cond . ' AND lang_code = ?s GROUP BY item_id', 't', DESCR_SL));
				}
				
				$products = fn_filter_results($values, 'item_id', array('p', 't', 's'));
				
				if (Registry::get('addons.quick_search.user_search') > 0) {
					$_values = db_get_array('SELECT text, type FROM ?:quick_search WHERE type = ?s AND text LIKE ?s AND lang_code = ?s LIMIT ?i', 'u', $pattern . '%', DESCR_SL, Registry::get('addons.quick_search.user_search'));
					
					$text = fn_filter_results($_values, 'text', array('u'), 0, 'custom');
					
				}
				
				if (Registry::get('settings.General.search_objects.pages') == 'Y') {
					$pages = fn_filter_results($values, 'item_id', array('i'));
				}
				
				if (Registry::get('settings.General.search_objects.news') == 'Y') {
					$news = fn_filter_results($values, 'item_id', array('n'));
				}
				
				if (!empty($products) || !empty($text) || !empty($pages) || !empty($news)) {
					if (!empty($pages) && Registry::get('addons.quick_search.pages_search') > 0) {
						$params = array();
						$params['item_ids'] = implode(',', $pages);
						$params['limit'] = Registry::get('addons.quick_search.pages_search');
						
						list($pages) = fn_get_pages($params);
						
						$result = array_merge(fn_filter_results($pages, 'page', array(), 'page_id', 'page'), $result);
					}
					
					if (!empty($news) && Registry::get('addons.quick_search.news_search') > 0) {
						$params = array();
						$params['item_ids'] = implode(',', $news);
						$params['limit'] = Registry::get('addons.quick_search.news_search');
						
						list($news) = fn_get_news($params);
						
						$result = array_merge(fn_filter_results($news, 'news', array(), 'news_id', 'news'), $result);
					}
					
					if (!empty($products)) {
						$params = array();
						$params['pid'] = $products;
						$params['cid'] = $cid;
						$params['subcats'] = 'Y';
						$params['limit'] = Registry::get('addons.quick_search.product_search');
						
						list($found_products) = fn_get_products($params);
						
						$search_objects = Registry::get('settings.General.search_objects');
						if (!empty($search_objects)) {
							foreach ($search_objects as $object => $value) {
								if ($value != 'Y') {
									unset($search_objects[$object]);
								}
							}
						}
						
						if (empty($search_objects) && Registry::get('addons.quick_search.show_product_images') == 'Y') {
							foreach ($found_products as $key => $product) {
								$found_products[$key]['main_pair'] = fn_get_image_pairs($product['product_id'], 'product', 'M', true, true);
							}
						}
						
						$found_products = fn_sort_results($found_products, 'product_id', $products);
						$result = array_merge(fn_filter_results($found_products, 'product', array(), 'product_id', 'product', true), $result);
					}
					
					$result = array_merge($text, $result);
					
					Registry::set('quick_search', $result);
				}
			}
			
			$result = fn_quick_search_unescape($result);
			
			fn_set_hook('quick_search', $result, $pattern, $cid);
			
			$result = fn_quick_search_truncate($result);
			$result = fn_quick_search_highlight($result, $pattern);
			$result = fn_quick_search_urls($result);
			
			$view->assign('patterns', $result);
			$view->assign('id', str_replace('_result', '', $_REQUEST['result_ids']));
		}
		
		$view->display('addons/quick_search/views/quick_search/components/quick_search.tpl');
		
		exit;
	}
}