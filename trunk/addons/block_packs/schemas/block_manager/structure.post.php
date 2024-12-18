<?php

if ( !defined('AREA') ) { die('Access denied'); }

/**
 * Schema extension to add the block_packs.tpl smarty template as an option to the Appearance type block options
 */
$schema['products']['appearances']['addons/block_packs/blocks/block_packs.tpl'] = array (
	'bulk_modifier' => array (
		'fn_gather_additional_products_data' => array (
			'products' => '#this',
			'params' => array (
				'get_icon' => true,
				'get_detailed' => true,
				'get_options' => false,
			),
		),
	),
	'params' => array (
		'block_packs' => true,
		'extend' => array('categories'),
	),
);

//TODO:need to work out how to perform the relevent DB queries for each filling

/**
 * Schema extension to add additional block filling options
 */
$schema['products']['fillings']['what_other_customers_are_looking_at'] =  array (
			'params' => array 
            (
            	'data_function' => 'fn_block_packs_what_other_customers_are_looking_at_data',
			),
			'update_handlers' => array ('what_other_customers_are_looking_at'),
			'disable_cache' => true,
			);	
$schema['products']['fillings']['best_seller_in_a_category'] =  array (
			'params' => array (
				'data_function' => 'fn_block_packs_best_seller_in_a_category_data',
				'request' => array (
					'cid' => '%CATEGORY_ID%'
				)
			),
			'update_handlers' => array ('best_seller_in_a_category'),
			'disable_cache' => true
			);
$schema['products']['fillings']['your_recent_history'] =  array (
			'params' => array 
            (
				'apply_limit' => true,
				'session' => array (
					'pid' => '%RECENTLY_VIEWED_PRODUCTS%'
				),
				'request' => array (
					'exclude_pid' => '%PRODUCT_ID%'
				),
				'force_get_by_ids' => true,
			),
			'disable_cache' => true
			);
$schema['products']['fillings']['most_viewed'] =  array (
			'params' => array 
            (
            	'sort_by' => 'view_count',
				'sort_order' => 'desc',
			),
			'update_handlers' => array ('most_viewed'),
			'disable_cache' => true
			);
$schema['products']['fillings']['frequently_bought_with'] =  array (
			'params' => array 
            (
            	'data_function' => 'fn_block_packs_frequently_bought_with_data',
            	'request' => array (
					'pid' => '%PRODUCT_ID%'
				)
            ),
			'update_handlers' => array ('frequently_bought_with'),
			'disable_cache' => true
			);
$schema['products']['fillings']['customers_who_bought_this_also_bought'] =  array (
			'params' => array 
            (
            	'data_function' => 'fn_block_packs_customers_who_bought_this_also_bought_data',
            	'request' => array (
					'pid' => '%PRODUCT_ID%'
				)
            ),
			'update_handlers' => array ('customers_who_bought_this_also_bought'),
			'disable_cache' => true
			);
$schema['products']['fillings']['inspired_by_your_browsing_history'] =  array (
			'params' => array 
            (
            	'data_function' => 'fn_block_packs_inspired_by_your_browsing_history_data',
            	'apply_limit' => true,
				'session' => array (
					'pid' => '%RECENTLY_VIEWED_PRODUCTS%'
				),
				'request' => array (
					'exclude_pid' => '%PRODUCT_ID%'
				),
				'force_get_by_ids' => true,
            ),
			'update_handlers' => array ('inspired_by_your_browsing_history'),
			'disable_cache' => true
			);
$schema['products']['fillings']['customers_bought_after_viewing'] =  array (
			'params' => array 
            (
            	'data_function' => 'fn_block_packs_customers_bought_after_viewing_data',
            	'request' => array (
					'pid' => '%PRODUCT_ID%'
				)
            ),
			'update_handlers' => array ('customers_bought_after_viewing'),
			'disable_cache' => true
			);
$schema['products']['fillings']['top_featured_merchants'] =  array (
			'params' => array 
            (
            	'data_function' => 'fn_block_packs_top_featured_merchants_data',
				'request' => array (
					'cid' => '%CATEGORY_ID%'
				)
            ),
			'update_handlers' => array ('top_featured_merchants'),
			'disable_cache' => true
			);
$schema['products']['fillings']['best_seller_in_list_of_categories'] =  array (
			'params' => array 
            (
            	'data_function' => 'fn_block_packs_best_seller_in_list_of_categories_data',
				'request' => array (
					'cid' => '%CATEGORY_ID%'
				)
            ),
			'update_handlers' => array ('best_seller_in_list_of_categories'),
			'disable_cache' => true
			);	

?>