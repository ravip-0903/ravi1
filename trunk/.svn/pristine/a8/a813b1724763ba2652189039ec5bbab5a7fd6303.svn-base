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
// $Id: init.php 12865 2011-07-05 06:57:22Z 2tl $
//

if ( !defined('AREA') ) { die('Access denied'); }

fn_define('SEO_FILENAME_EXTENSION', '.html');
fn_define('SEO_RUNTIME_CACHE_COUNT', 10000);

fn_register_hooks(
	'url',
	'get_route',
	'before_dispatch',
	'check_redirect_to_cart',

	'update_category',
	'get_category_data',
	'get_category_data_post',
	'get_categories',
	'get_categories_post',
	'delete_category',
	
	'update_product',
	'get_products',
	'get_products_post',
	'get_product_data',
	'delete_product',

	'update_company',
	'get_companies',
	'get_company_data',
	'delete_company',

	'update_page',
	'get_pages',
	'get_page_data',
	'delete_page',
	
	'get_product_feature_variants',
	'update_product_feature',
	'delete_product_feature',
	
	'get_news',
	'get_news_post',
	'get_news_data',
	'update_news',
	'delete_news'
);

?>