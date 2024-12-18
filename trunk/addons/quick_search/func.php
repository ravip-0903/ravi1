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
// $Id: func.php 12865 2011-07-05 06:57:22Z 2tl $
//

if ( !defined('AREA') ) { die('Access denied'); }

function fn_filter_results(&$values, $key, $types = array(), $object_id = '', $type = '', $get_images = false)
{
	$return = array();
	
	if (!empty($values)) {
		foreach ($values as $_id => $value) {
			if (empty($types) || (!empty($types) && in_array($value['type'], $types))) {
				if (!empty($object_id) || !empty($type)) {
					$return[] = array(
						'text' => addslashes($value[$key]),
						'id' => isset($value[$object_id]) ? $value[$object_id] : '',
						'type' => $type,
						'image' => $get_images ? empty($value['main_pair']) ? array() : $value['main_pair'] : array(),
					);
				} else {
					$return[] = addslashes($value[$key]);
				}
				unset($values[$_id]);
			}
		}
		
		return $return;
	}
	
	return array();
}

function fn_sort_results($values, $field, $sort_ids)
{
	if (empty($values) || empty($field) || empty($sort_ids)) {
		return $values;
	}
	
	$result = array();
	
	foreach ($values as $key => $value) {
		$result[array_search($value[$field], $sort_ids)] = $value;
	}
	
	ksort($result);
	
	return $result;
}

function fn_quick_search_unescape($values)
{
	if (!empty($values)) {
		foreach ($values as &$value) {
			$value['text'] = stripslashes($value['text']);
		}
	}
	
	return $values;
}

function fn_quick_search_generate_info()
{
	$search = array('[http_location]', '[admin_index]', '[customer_index]');
	$replace = array(Registry::get('config.http_location'), fn_get_index_script(), Registry::get('config.customer_index'));
	
	return str_replace($search, $replace, fn_get_lang_var('quick_search_generate_info'));
}

function fn_quick_search_update_product($product_data, $product_id, $lang_code, $create)
{
	db_query('DELETE FROM ?:quick_search WHERE type IN (?a) AND item_id = ?i AND lang_code = ?s', array('p', 't', 's'), $product_id, $lang_code);
	
	$_update = array(
		'product' => $product_data['product'],
		'product_code' => isset($product_data['product_code']) ? $product_data['product_code'] : '',
		'short_description' => isset($product_data['short_description']) ? $product_data['short_description'] : '',
		'full_description' => isset($product_data['full_description']) ? $product_data['full_description'] : '',
		'meta_keywords' => isset($product_data['meta_keywords']) ? $product_data['meta_keywords'] : '',
		'meta_description' => isset($product_data['meta_description']) ? $product_data['meta_description'] : '',
		'search_words' => isset($product_data['search_words']) ? $product_data['search_words'] : '',
	);
	
	foreach ($_update as $field => $attr) {
		if (!empty($attr)) {
			($field == 'product') ? $type = 't' : ($field == 'product_code' ? $type = 's' : $type = 'p');
			
			if ($create) {
				foreach ((array)Registry::get('languages') as $lang_code => $_v) {
					fn_quick_search_fill_data($product_id, $type, $lang_code, $attr);
				}
			} else {
				fn_quick_search_fill_data($product_id, $type, $lang_code, $attr);
			}
		}
	}
}

function fn_quick_search_update_news($news_data, $news_id, $lang_code, $create)
{
	db_query('DELETE FROM ?:quick_search WHERE type = ?s AND item_id = ?i AND lang_code = ?s', 'n', $news_id, $lang_code);
	
	$_update = array(
		'news' => $news_data['news'],
	);
	
	if (isset($news_data['description'])) {
		$_update['description'] = $news_data['description'];
	}
	
	foreach ($_update as $field => $attr) {
		if (!empty($attr)) {
			if ($create) {
				foreach ((array)Registry::get('languages') as $lang_code => $_v) {
					fn_quick_search_fill_data($news_id, 'n', $lang_code, $attr);
				}
			} else {
				fn_quick_search_fill_data($news_id, 'n', $lang_code, $attr);
			}
		}
	}
}

function fn_quick_search_update_page($page_data, $page_id, $lang_code, $create)
{
	db_query('DELETE FROM ?:quick_search WHERE type = ?s AND item_id = ?i AND lang_code = ?s', 'i', $page_id, $lang_code);

	$_update = array(
		'page' => empty($page_data['page']) ? '' : $page_data['page'],
		'description' => empty($page_data['description']) ? '' : $page_data['description'],
		'page_title' => empty($page_data['page_title']) ?  '' : $page_data['page_title'],
		'meta_description' => empty($page_data['meta_description']) ?  '' : $page_data['meta_description'],
		'meta_keywords' => empty($page_data['meta_keywords']) ?  '' : $page_data['meta_keywords'],
	);
	
	foreach ($_update as $field => $attr) {
		if (!empty($attr)) {
			if ($create) {
				foreach ((array)Registry::get('languages') as $lang_code => $_v) {
					fn_quick_search_fill_data($page_id, 'i', $lang_code, $attr);
				}
			} else {
				fn_quick_search_fill_data($page_id, 'i', $lang_code, $attr);
			}
		}
	}
}

function fn_quick_search_delete_product($product_id)
{
	db_query('DELETE FROM ?:quick_search WHERE type IN (?a) AND item_id = ?i', array('p', 't', 's'), $product_id);
}

function fn_quick_search_delete_news($news_id)
{
	db_query('DELETE FROM ?:quick_search WHERE type = ?s AND item_id = ?i', 'n', $news_id);
}

function fn_quick_search_delete_page($page_id)
{
	db_query('DELETE FROM ?:quick_search WHERE type = ?s AND item_id = ?i', 'i', $page_id);
}

function fn_quick_search_highlight($result, $pattern)
{
	$_pattern = array();
	if (!empty($pattern)) {
		preg_match_all('/(\pL+)/iu', $pattern, $matches);
		
		$matches = array_unique($matches[0]);
		if (!empty($matches)) {
			foreach ($matches as $match) {
				if (fn_strlen($match) <= 1) {
					continue;
				}
				
				$_pattern[] = $match;
			}
		}
	}
	
	if (!empty($result)) {
		foreach ($result as $id => $line) {
			if (!is_array($line)) {
				$text = stripslashes($line);
				$result[$id] = array();
			} else {
				$text = $line['text'];
			}
			$result[$id]['text'] = array(
				'value' => $text,
			);
			
			preg_match_all('/(\pL+)/iu', $text, $matches);
			$matches = array_unique($matches[0]);
			
			if (!empty($matches)) {
				$_replace = array();
				$_pos = 0;
				foreach ($matches as $match) {
					if (fn_strlen($match) <= 1) {
						continue;
					}
					
					foreach ($_pattern as $value) {
						if (stripos($match, $value) !== false) {
							$start = stripos($match, $value);
							$length = fn_strlen($value);
							
							$_replace[$_pos] = '<strong>' . fn_substr($match, $start, $length) . '</strong>';
							$text = preg_replace('/' . $value . '/i', '###' . $_pos . '###', $text, 1);
							
							$_pos++;
						}
					}
				}
				
				if (!empty($_replace)) {
					foreach ($_replace as $key => $value) {
						$text = str_replace('###' . $key . '###', $value, $text);
					}
				}
				
				$result[$id]['text']['highlighted'] = $text;
			}
		}
	}
	
	return $result;
}

function fn_quick_search_fill_data($id, $type, $lang_code, $attr)
{
	$attr = strip_tags($attr);
	$md5 = md5($id . $type . $lang_code . $attr);
							
	$_data = array(
		'id' => $md5,
		'item_id' => $id,
		'type' => $type,
		'lang_code' => $lang_code,
		'text' => $attr,
	);
	
	db_query('REPLACE INTO ?:quick_search ?e', $_data);
	
	return true;
}

function fn_quick_search_truncate($result)
{
	$length = 50;
	if (!empty($result) && is_array($result)) {
		foreach ($result as &$value) {
			$value['full_text'] = $value['text'];
			if (!empty($value['text'])) {
				if (fn_strlen($value['text']) > $length) {
					$value['text'] = substr($value['text'], 0, $length);
					$value['text'] = preg_replace('/\pL+$/', '', $value['text']) . '...';
				}
			}
		}
		unset($value);
	}
	
	return $result;
}

function fn_quick_search_urls($result)
{
	if (!empty($result)) {
		foreach ($result as &$item) {
			$item['url'] = '';
			
			if ($item['type'] == 'product') {
				$item['url'] = fn_url('products.view?product_id=' . $item['id']);
				
			} else if ($item['type'] == 'page') {
				$item['url'] = fn_url('pages.view?page_id=' . $item['id']);
				
			} if ($item['type'] == 'news') {
				$item['url'] = fn_url('news.view?news_id=' . $item['id']);
			}
			
			$item['url'] = urlencode($item['url']);
		}
	}
	
	return $result;
}

function fn_generate_search_catalog()
{
	if (!defined('SEARCH_LIMIT')) {
		define('SEARCH_LIMIT', 100);
	}

	echo '<strong>' . fn_get_lang_var('generating_catalog') . '</strong><br />';

	$pos = 0;
	$count = db_get_field('SELECT COUNT(*) FROM ?:product_descriptions');
	
	db_query('DELETE FROM ?:quick_search');
	
	// Get all products information
	while ($pos <= $count) {
		$products = db_get_array('SELECT descr.product_id, descr.product, descr.shortname, descr.short_description, descr.full_description, descr.meta_keywords, descr.meta_description, descr.search_words, descr.lang_code as lang_code, ?:products.product_code FROM ?:product_descriptions AS descr LEFT JOIN ?:products ON (descr.product_id = ?:products.product_id) LIMIT ?i, ?i', $pos, SEARCH_LIMIT);
		
		$pos += SEARCH_LIMIT;
		
		if (!empty($products)) {
			foreach ($products as $product) {
				$product_id = $product['product_id'];
				$lang_code = $product['lang_code'];
				unset($product['product_id'], $product['lang_code']);
				
				foreach ($product as $field => $attr) {
					if (!empty($attr)) {
						($field == 'product') ? $type = 't' : ($field == 'product_code' ? $type = 's' : $type = 'p');
						
						fn_quick_search_fill_data($product_id, $type, $lang_code, $attr);
						
						echo '.';
					}
				}
			}
			
			echo '<br />';
		}
	}
	
	// Get news information
	$pos = 0;
	$count = db_get_field('SELECT COUNT(*) FROM ?:news_descriptions ');
	
	while ($pos <= $count) {
		$news = db_get_array('SELECT news_id, news, description, lang_code FROM ?:news_descriptions LIMIT ?i, ?i', $pos, SEARCH_LIMIT);
		
		$pos += SEARCH_LIMIT;
		
		if (!empty($news)) {
			foreach ($news as $item) {
				$news_id = $item['news_id'];
				$lang_code = $item['lang_code'];
				unset($item['news_id'], $item['lang_code']);
				
				foreach ($item as $field => $attr) {
					if (!empty($attr)) {
						fn_quick_search_fill_data($news_id, 'n', $lang_code, $attr);
						
						echo '.';
					}
				}
			}
			
			echo '<br />';
		}
	}
	
	// Get pages information
	$pos = 0;
	$count = db_get_field('SELECT COUNT(*) FROM ?:page_descriptions');
	
	while ($pos <= $count) {
		$pages = db_get_array('SELECT page_id, page, description, lang_code FROM ?:page_descriptions LIMIT ?i, ?i', $pos, SEARCH_LIMIT);
		
		$pos += SEARCH_LIMIT;
		
		if (!empty($pages)) {
			foreach ($pages as $page) {
				$page_id = $page['page_id'];
				$lang_code = $page['lang_code'];
				unset($page['page_id'], $page['lang_code']);
				
				foreach ($page as $field => $attr) {
					if (!empty($attr)) {
						fn_quick_search_fill_data($page_id, 'i', $lang_code, $attr);
						
						echo '.';
					}
				}
			}
			
			echo '<br />';
		}
	}
}

?>