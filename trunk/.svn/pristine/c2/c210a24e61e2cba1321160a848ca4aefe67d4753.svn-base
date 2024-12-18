<?php
/***************************************************************************
*                                                                          *
*    Copyright (c) 2004 Simbirsk Technologies Ltd. All rights reserved.    *
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
// $Id: func.php 11577 2011-01-12 12:54:10Z klerik $
//

if ( !defined('AREA') ) { die('Access denied'); }


//
// Update sales stats for the product
//

function fn_cluesblocks_get_products(&$params, &$fields, &$sortings, &$condition, &$join, &$sorting, &$group_by)
{
	if (!empty($params['categoryfeaturedproducts'])) {
	     if(AREA == 'C'){
		if (!empty($params['category'])) {
			$cids = $params['category'];
		} elseif (!empty($params['cid'])) {
			$cids = $params['cid'];
		}
		$cat_id = db_get_fields("SELECT a.category_id FROM ?:categories as a LEFT JOIN ?:categories as b ON b.category_id IN (?n) WHERE a.id_path LIKE CONCAT(b.id_path, '/%')", $cids);
		$cids = fn_array_merge($cids, $cat_id, false);
		$category_ids = implode(',',$cids);
		//$fields[] = '?:products.feature_index';
		$join = "LEFT JOIN cscart_product_descriptions as descr1 ON descr1.product_id = products.product_id AND descr1.lang_code = 'EN' LEFT JOIN cscart_product_prices as prices ON prices.product_id = products.product_id AND prices.lower_limit = 1 
LEFT JOIN cscart_companies AS companies ON companies.company_id = products.company_id INNER JOIN cscart_products_categories as products_categories ON products_categories.product_id = products.product_id INNER JOIN cscart_categories ON cscart_categories.category_id = products_categories.category_id AND cscart_categories.status IN ('A', 'H') LEFT JOIN cscart_seo_names ON cscart_seo_names.object_id = products.product_id AND cscart_seo_names.type = 'p' AND cscart_seo_names.dispatch = '' AND cscart_seo_names.lang_code = 'EN'";

		$condition = db_quote(" AND ?:categories.category_id IN (".$category_ids.") AND (companies.status = 'A' OR products.company_id = 0)");
	    }
	}

	if (!empty($params['categorydealindexproducts'])) {
	     if(AREA == 'C'){
		if (!empty($params['category'])) {
			$cids = $params['category'];
		} elseif (!empty($params['cid'])) {
			$cids = $params['cid'];
		}

		$cat_id = db_get_fields("SELECT a.category_id FROM ?:categories as a LEFT JOIN ?:categories as b ON b.category_id IN (?n) WHERE a.id_path LIKE CONCAT(b.id_path, '/%')", $cids);
		$cids = fn_array_merge($cids, $cat_id, false);
		$category_ids = implode(',',$cids);

		$join = "LEFT JOIN cscart_product_descriptions as descr1 ON descr1.product_id = products.product_id AND descr1.lang_code = 'EN' LEFT JOIN cscart_product_prices as prices ON prices.product_id = products.product_id AND prices.lower_limit = 1 
LEFT JOIN cscart_companies AS companies ON companies.company_id = products.company_id INNER JOIN cscart_products_categories as products_categories ON products_categories.product_id = products.product_id INNER JOIN cscart_categories ON cscart_categories.category_id = products_categories.category_id AND cscart_categories.status IN ('A', 'H') LEFT JOIN cscart_seo_names ON cscart_seo_names.object_id = products.product_id AND cscart_seo_names.type = 'p' AND cscart_seo_names.dispatch = '' AND cscart_seo_names.lang_code = 'EN'";
		$condition = db_quote(" AND ?:categories.category_id IN (".$category_ids.") AND (companies.status = 'A' OR products.company_id = 0)");
	    }
	}
	
	if (!empty($params['category_best_seller'])) {
	     if(AREA == 'C'){
		if (!empty($params['category'])) {
			$cids = $params['category'];
		} elseif (!empty($params['cid'])) {
			$cids = $params['cid'];
		}
		$cat_id = db_get_fields("SELECT a.category_id FROM ?:categories as a LEFT JOIN ?:categories as b ON b.category_id IN (?n) WHERE a.id_path LIKE CONCAT(b.id_path, '/%')", $cids);
		$cids = fn_array_merge($cids, $cat_id, false);
		$category_ids = implode(',',$cids);

		$join = "LEFT JOIN cscart_product_descriptions as descr1 ON descr1.product_id = products.product_id AND descr1.lang_code = 'EN' LEFT JOIN cscart_product_prices as prices ON prices.product_id = products.product_id AND prices.lower_limit = 1 
LEFT JOIN cscart_companies AS companies ON companies.company_id = products.company_id INNER JOIN cscart_products_categories as products_categories ON products_categories.product_id = products.product_id INNER JOIN cscart_categories ON cscart_categories.category_id = products_categories.category_id AND cscart_categories.status IN ('A', 'H') LEFT JOIN cscart_seo_names ON cscart_seo_names.object_id = products.product_id AND cscart_seo_names.type = 'p' AND cscart_seo_names.dispatch = '' AND cscart_seo_names.lang_code = 'EN' LEFT JOIN cscart_product_sales ON cscart_product_sales.product_id = products.product_id AND cscart_product_sales.category_id = products_categories.category_id";

		$condition = db_quote(" AND ?:categories.category_id IN (".$category_ids.") AND (companies.status = 'A' OR products.company_id = 0)");
	    }
	}

	if (!empty($params['clues_popularity'])) {
	     if(AREA == 'C'){
		if (!empty($params['category'])) {
			$cids = $params['category'];
		} elseif (!empty($params['cid'])) {
			$cids = $params['cid'];
		}

		$cat_id = db_get_fields("SELECT a.category_id FROM ?:categories as a LEFT JOIN ?:categories as b ON b.category_id IN (?n) WHERE a.id_path LIKE CONCAT(b.id_path, '/%')", $cids);
		$cids = fn_array_merge($cids, $cat_id, false);
		$category_ids = implode(',',$cids);

		$join = "LEFT JOIN cscart_product_descriptions as descr1 ON descr1.product_id = products.product_id AND descr1.lang_code = 'EN' LEFT JOIN cscart_product_prices as prices ON prices.product_id = products.product_id AND prices.lower_limit = 1 
LEFT JOIN cscart_companies AS companies ON companies.company_id = products.company_id INNER JOIN cscart_products_categories as products_categories ON products_categories.product_id = products.product_id INNER JOIN cscart_categories ON cscart_categories.category_id = products_categories.category_id AND cscart_categories.status IN ('A', 'H') LEFT JOIN cscart_seo_names ON cscart_seo_names.object_id = products.product_id AND cscart_seo_names.type = 'p' AND cscart_seo_names.dispatch = '' AND cscart_seo_names.lang_code = 'EN' inner join cscart_product_popularity pp on pp.product_id = products.product_id ";
		$condition = db_quote(" AND ?:categories.category_id IN (".$category_ids.") AND (companies.status = 'A' OR products.company_id = 0)");
   	    }
	}

	if (!empty($params['category_new_arrival'])) {
	     if(AREA == 'C'){
		if (!empty($params['category'])) {
			$cids = $params['category'];
		} elseif (!empty($params['cid'])) {
			$cids = $params['cid'];
		}
		$cat_id = db_get_fields("SELECT a.category_id FROM ?:categories as a LEFT JOIN ?:categories as b ON b.category_id IN (?n) WHERE a.id_path LIKE CONCAT(b.id_path, '/%')", $cids);
		$cids = fn_array_merge($cids, $cat_id, false);
		$category_ids = implode(',',$cids);

		$join = "LEFT JOIN cscart_product_descriptions as descr1 ON descr1.product_id = products.product_id AND descr1.lang_code = 'EN' LEFT JOIN cscart_product_prices as prices ON prices.product_id = products.product_id AND prices.lower_limit = 1 
LEFT JOIN cscart_companies AS companies ON companies.company_id = products.company_id INNER JOIN cscart_products_categories as products_categories ON products_categories.product_id = products.product_id INNER JOIN cscart_categories ON cscart_categories.category_id = products_categories.category_id AND cscart_categories.status IN ('A', 'H') LEFT JOIN cscart_seo_names ON cscart_seo_names.object_id = products.product_id AND cscart_seo_names.type = 'p' AND cscart_seo_names.dispatch = '' AND cscart_seo_names.lang_code = 'EN'";
		$condition = db_quote(" AND ?:categories.category_id IN (".$category_ids.") AND (companies.status = 'A' OR products.company_id = 0)");
	     }
	}
}

?>
