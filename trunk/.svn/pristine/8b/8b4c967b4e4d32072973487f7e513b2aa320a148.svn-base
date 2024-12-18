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
// $Id: structure.php 12865 2011-07-05 06:57:22Z 2tl $
//

if ( !defined('AREA') ) { die('Access denied'); }

$schema = array (
	'products' => array (
		'fillings' => array (
			'manually',
			'newest' => array (
				'params' => array (
					'sort_by' => 'timestamp',
					'sort_order' => 'desc',
					'request' => array (
						'cid' => '%CATEGORY_ID%'
					)
				)
			),
			'recent_products' => array (
				'params' => array (
					'apply_limit' => true,
					'session' => array (
						'pid' => '%RECENTLY_VIEWED_PRODUCTS%'
					),
					'request' => array (
						'exclude_pid' => '%PRODUCT_ID%'
					),
					'force_get_by_ids' => true,
				),
				'disable_cache' => true,
			),
			'popularity' => array (
				'params' => array (
					'popularity_from' => 1,
					'sort_by' => 'popularity',
					'sort_order' => 'desc',
					'request' => array (
						'cid' => '%CATEGORY_ID'
					)
				),
				'update_handlers' => array ('product_popularity'),
			),
		),
		'appearances' => array (
			'blocks/products_text_links.tpl' => array (),
			'blocks/products_links_thumb.tpl' => array (
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
			),
			'blocks/products_multicolumns.tpl' => array (
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
			),
			'blocks/products_multicolumns2.tpl' => array (
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
			),
			'blocks/products_multicolumns_small.tpl' => array (
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
			),
			'blocks/products.tpl' => array (
				'bulk_modifier' => array (
					'fn_gather_additional_products_data' => array (
						'products' => '#this',
						'params' => array (
							'get_icon' => true,
							'get_detailed' => true,
							'get_options' => true,
						),
					),
				),
				'params' => array (
					'extend' => array('description'),
				),
				'update_handlers' => array ('product_options', 'product_options_descriptions', 'product_global_option_links', 'product_options_exceptions', 'product_options_inventory', 'product_option_variants', 'product_option_variants_descriptions'),
			),
			'blocks/products2.tpl' => array (
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
					'extend' => array('description'),
				),
			),
			'blocks/products_sidebox_1_item.tpl' => array (
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
			),
			'blocks/products_small_items.tpl' => array (
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
			),
			'blocks/products_without_image.tpl' => array (
			),
			'blocks/products_scroller.tpl' => array (
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
			),
			'blocks/products_scroller2.tpl' => array (
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
			),
			'blocks/products_scroller3.tpl' => array (
			),
			'blocks/short_list.tpl' => array (
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
			),
			'blocks/grid_list.tpl' => array (
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
			),
			'blocks/home_top_banner_grid_list.tpl' => array (
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
			),
			'blocks/clues_cateogry_4product_list.tpl' => array (
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
			),
		),
		'dispatch' => 'products.update',
		'object_id' => 'product_id',
		'object_name' => 'product',
		'picker_props' => array (
			'picker' => 'pickers/products_picker.tpl',
			'params' => array (
				'type' => 'links',
			),
		),
	),
	'categories' => array (
		'fillings' => array (
			'manually' => array (
				'params' => array (
					'simple' => false,
					'group_by_level' => false
				)
			),
			'newest' => array (
				'params' => array (
					'sort_by' => 'timestamp',
					'plain' => true,
					'visible' => true
				)
			),
			'emenu',
			'plain' => array (
				'params' => array (
					'plain' => true
				),
				'update_params' => array (
					'request' => array ('%CATEGORY_ID'),
				),
			),
			'dynamic' => array (
				'params' => array (
					'visible' => true,
					'plain' => true,
					'request' => array (
						'current_category_id' => '%CATEGORY_ID%',
					),
					'session' => array(
						'product_category_id' => '%CURRENT_CATEGORY_ID%'
					)
				),
			)
		),
		'appearances' => array (
			'blocks/categories_text_links.tpl' => array (
				'conditions' => array (
					'fillings' => array('manually', 'newest')
				)
			),
			'blocks/categories_emenu.tpl' => array (
				'conditions' => array (
					'fillings' => array('emenu')
				)
			),
			'blocks/categories_dynamic.tpl' => array (
				'conditions' => array (
					'fillings' => array('dynamic')
				)
			),
			'blocks/categories_plain.tpl' => array (
				'conditions' => array (
					'fillings' => array('plain')
				)
			),
			'blocks/categories_multicolumns.tpl' => array (
				'params' => array (
					'get_images' => true
				)
			),
			'blocks/clues_categories.tpl' => array (
				'conditions' => array (
					'fillings' => array('dynamic')
				)
			),
		),
		'dispatch' => 'categories.update',
		'object_id' => 'category_id',
		'object_name' => 'category',
		'picker_props' => array (
			'picker' => 'pickers/categories_picker.tpl',
			'params' => array (
				'multiple' => true,
				'use_keys' => 'N',
				'view_mode' => 'blocks',
			),
		),
	),
	'pages' => array (
		'fillings' => array (
			'manually' => array(
				'params' => array (
					'status' => 'A',
				)
			),
			'newest' => array (
				'params' => array (
					'sort_by' => 'timestamp',
					'visible' => true,
					'status' => 'A',

				)
			),
			'dynamic' => array (
				'params' => array (
					'visible' => true,
					'get_tree' => 'plain',
					'status' => 'A',
					'request' => array (
						'current_page_id' => '%PAGE_ID%'
					),
				),
			),
			'child_pages' => array (
				'params' => array (
					'status' => 'A',
					'request' => array (
						'parent_id' => '%PAGE_ID%',
					)
				),
			),
			'vendor_pages' => array (
				'params' => array (
					'status' => 'A',
					'vendor_pages' => true,
					'request' => array (
						'company_id' => '%COMPANY_ID%',
					)
				),
			)
		),
		'appearances' => array (
			'blocks/pages_text_links.tpl' => array (
				'conditions' => array (
					'fillings' => array ('manually', 'newest')
				),
				'params' => array ('plain' => true)
			),
			'blocks/pages_dynamic.tpl' => array (
				'conditions' => array (
					'fillings' => array (
						'dynamic',
									'vendor_pages',
								)
				)
			),
			'blocks/pages_child.tpl' => array (
				'conditions' => array (
					'fillings' => array ('child_pages')
				)
			)
		),
		'dispatch' => 'pages.update',
		'object_id' => 'page_id',
		'object_name' => 'page',
		'picker_props' => array (
			'picker' => 'pickers/pages_picker.tpl',
			'params' => array (
				'multiple' => true,
			),
		),
	),
	'product_filters' => array (
		'fillings' => array (
			'dynamic' => array (
				'params' => array (
					'check_location' => true,
					'request' => array (
						'dispatch' => '%DISPATCH%',
						'category_id' => '%CATEGORY_ID%',
						'features_hash' => '%FEATURES_HASH%',
						'variant_id' => '%VARIANT_ID%',
						'advanced_filter' => '%advanced_filter%',
					),
					'skip_if_advanced' => true,
				)
			),
			'filters' => array (
				'params' => array (
					'get_all' => true,
					'request' => array(
						'variant_id' => '%VARIANT_ID%',
					),
					'get_custom' => true,
					'skip_other_variants' => true
				),
			)
		),
		'appearances' => array (
			'blocks/product_filters.tpl' => array (
				'conditions' => array (
					'fillings' => array ('dynamic')
				),
			),
			'blocks/product_filters_extended.tpl' => array (
				'conditions' => array (
					'fillings' => array ('filters')
				)
			),
		),
		'dispatch' => 'product_filters.manage', // what for?
		'object_id' => 'filter_id',
		'object_name' => 'product_filter',
		'data_function' => 'fn_get_filters_products_count',
	),
	'vendors' => array (
		'fillings' => array (
			'all', 'manually'
		),
		'appearances' => array (
			'blocks/companies_list.tpl' => array (
				'conditions' => array (
					'fillings' => array ('all', 'manually'),
				),
				'params' => array (
					'status' => 'A',
				),
			),
		),
		'data_function' => 'fn_get_short_companies',

		'object_description' => 'vendors',
		'object_id' => 'company_id',
		'object_name' => 'companies',
		'picker_props' => array (
			'picker' => 'pickers/companies_picker.tpl',
			'params' => array (
				'multiple' => true,
			),
		),
	),
	'payment_methods' => array (
		'fillings' => array (
		),
		'appearances' => array (
			'blocks/payments.tpl'
		),
		'data_function' => 'fn_get_payment_methods_images',
	),

	'shipping_methods' => array (
		'fillings' => array (
		),
		'appearances' => array (
			'blocks/shippings.tpl'
		),
		'data_function' => 'fn_get_shipping_images',
	),
);

?>
