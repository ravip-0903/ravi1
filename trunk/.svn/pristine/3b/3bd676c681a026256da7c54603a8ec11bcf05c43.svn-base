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
// $Id$
//

if ( !defined('AREA') ) { die('Access denied'); }

$schema = array (
	'disable_cache' => false,
	'disable_central_content_cache' => false,
	'update_handlers' => array ('addons', 'settings', 'blocks', 'block_descriptions', 'block_links', 'block_positions', 'languages', 'language_values'),

	'data_functions' =>array (
		'_STATIC_TEMPLATE_BLOCK_' => array (
			'templates' => array (
				'blocks/my_account.tpl' => array (
					'disable_cache' => true,
				),

				'blocks/feature_comparison.tpl' => array (
					'disable_cache' => true,
				),

				'blocks/shipping_estimation.tpl' => array (
					'disable_cache' => true,
				),

				'blocks/html_block.tpl' => array (
					'disable_cache' => true,
				),

				'blocks/unique_html_block.tpl' => array (
					'disable_cache' => true,
				),

				'blocks/rss_feed.tpl' => array (
					'disable_cache' => true,
				),
				// [andyye]
				'blocks/companies_list.tpl' => array (
					'disable_cache' => true,
				),
				// [/andyye]
				
				'blocks/custom_left_category.tpl' => array (
					'disable_cache' => true,
				),
				
				'blocks/search_category_left_block.tpl' => array (
					'disable_cache' => true,
				),
			),
		),

		'fn_get_categories' => array (
			'update_handlers' => array ('categories', 'category_descriptions'),
			'disable_cache' =>true,
		),

		'fn_get_products' => array (
			'update_handlers' => array ('products', 'product_descriptions', 'product_prices', 'products_categories'),
			'use_currency_cache_level' => true,
		),

		'fn_get_short_companies' => array (
			'update_handlers' => array ('companies', 'company_descriptions'),
		),

		'fn_get_pages' => array (
			'update_handlers' => array ('pages', 'page_descriptions'),
		),
	
		'fn_get_filters_products_count' => array (
			'disable_cache' => true,
		),

		'fn_get_payment_methods_images' => array (
			'update_handlers' => array ('payments', 'payment_descriptions'),
		),

		'fn_get_shipping_images' => array (
			'update_handlers' => array ('shippings', 'shipping_descriptions'),
		),

	),
);

?>
