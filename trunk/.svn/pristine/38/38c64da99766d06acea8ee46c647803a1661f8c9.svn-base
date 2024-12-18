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
// $Id: specific_settings.php 12865 2011-07-05 06:57:22Z 2tl $
//

if ( !defined('AREA') ) { die('Access denied'); }

$schema = array (
	'properties' => array (
		'location' => array (
			'page_title' => array (
				'type' => 'input_long',
				'multilingual' => true
			),
			'meta_description' => array (
				'type' => 'simple_text',
				'multilingual' => true
			),
			'meta_keywords' => array (
				'type' => 'simple_text',
				'multilingual' => true
			)
		)
	),
	'list_object' => array (
		'products' => array (
			'hide_add_to_cart_button' => array (
				'type' => 'checkbox',
				'default_value' => 'Y'
			),
			'show_key_feature' => array (
				'type' => 'checkbox',
				'default_value' => 'N'
			)
		),
		'blocks/html_block.tpl' => array (
			'settings' => array (
				'items_function' => 'fn_get_html_content',
				'hide_label' => true,
				'section' => 'special',
				'force_open' => true,
			),
			'block_text' => array (
				'type' => 'text',
				'multilingual' => true,
			),
		),
		'blocks/unique_html_block.tpl' => array (
			'settings' => array (
				'items_function' => 'fn_get_html_content',
				'object_id' => '%OBJECT_ID%',
				'hide_label' => true,
				'section' => 'special',
				'per_object' => true,
				'locations' => array('products', 'categories', 'pages'),
			)
		),
		'blocks/rss_feed.tpl' => array (
			'feed_url' => array (
				'type' => 'input',
				'default_value' => ''
			),
			'max_item' => array (
				'type' => 'input',
				'default_value' => 3
			),
			'settings' => array (
				'items_function' => 'fn_get_rss_feed',
				'hide_label' => true,
				'section' => 'special'
			)
		),
		'vendors' => array (
			'displayed_vendors' => array (
				'type' => 'input',
				'default_value' => '10'
			)
		),
	),
	'fillings' => array (
		'newest' => array (
			'period' => array (
				'type' => 'selectbox',
				'values' => array (
					'A' => 'any_date',
					'D' => 'today',
					'HC' => 'last_days',
				),
				'default_value' => 'any_date'
			),
			'last_days' => array (
				'type' => 'input',
				'default_value' => 1
			),
			'limit' => array (
				'type' => 'input',
				'default_value' => 3
			)
		),
		'filters' => array(
			'filter_id' => array (
				'type' => 'selectbox',
				'option_name' => 'show',
				'no_lang' => true,
				'data_function' => array('fn_get_simple_product_filters'),
			),
		),
		'recent_products' => array (
			'limit' => array (
				'type' => 'input',
				'default_value' => 3
			)
		),
		'popularity' => array (
			'limit' => array (
				'type' => 'input',
				'default_value' => 3
			)
		),
	),
	'appearances' => array (
		'blocks/text_links.tpl' => array (
			'item_number' => array (
				'type' => 'checkbox',
				'default_value' => 'N'
			)
		),
		'blocks/products_text_links.tpl' => array (
			'item_number' => array (
				'type' => 'checkbox',
				'default_value' => 'N'
			)
		),
		'blocks/products_links_thumb.tpl' => array (
			'item_number' => array (
				'type' => 'checkbox',
				'default_value' => 'N'
			)
		),
		'blocks/products_multicolumns.tpl' => array (
			'item_number' => array (
				'type' => 'checkbox',
				'default_value' => 'N'
			),
			'number_of_columns' => array (
				'type' => 'input',
				'default_value' => 2
			)
		),
		'blocks/products_multicolumns_small.tpl' => array (
			'item_number' => array (
				'type' => 'checkbox',
				'default_value' => 'N'
			),
			'number_of_columns' =>  array (
				'type' => 'input',
				'default_value' => 3
			)
		),
		'blocks/products.tpl' => array (
			'item_number' => array (
				'type' => 'checkbox',
				'default_value' => 'N'
			),
			'hide_options' => array (
				'type' => 'checkbox',
				'default_value' => 'N'
			)
		),
		'blocks/products2.tpl' => array (
			'item_number' => array (
				'type' => 'checkbox',
				'default_value' => 'N'
			),
			'number_of_columns' =>  array (
				'type' => 'input',
				'default_value' => 2
			)
		),
		'blocks/products_sidebox_1_item.tpl' => array (
			'item_number' => array (
				'type' => 'checkbox',
				'default_value' => 'N'
			)
		),
		'blocks/products_small_items.tpl' => array (
			'item_number' => array (
				'type' => 'checkbox',
				'default_value' => 'N'
			)
		),
		'blocks/clues_products_small_items.tpl' => array (
			'item_number' => array (
				'type' => 'checkbox',
				'default_value' => 'N'
			)
		),
		'blocks/products_without_image.tpl' => array (
			'item_number' => array (
				'type' => 'checkbox',
				'default_value' => 'N'
			)
		),
		'blocks/products_scroller.tpl' => array (
			'item_number' => array (
				'type' => 'checkbox',
				'default_value' => 'N'
			),
			'scroller_direction' => array (
				'type' => 'selectbox',
				'values' => array (
					'up' => 'up',
					'down' => 'down',
					'left' => 'left',
					'right' => 'right'
				),
				'default_value' => 'up'
			),
			'speed' => array (
				'type' => 'selectbox',
				'values' => array (
					'slow' => 'slow',
					'normal' => 'normal',
					'fast' => 'fast'
				),
				'default_value' => 'normal'
			),
			'easing' => array (
				'type' => 'selectbox',
				'values' => array (
					'linear' => 'linear',
					'swing' => 'swing'
				),
				'default_value' => 'swing'
			),
			'pause_delay' =>  array (
				'type' => 'input',
				'default_value' => 3000
			),
			'item_quantity' =>  array (
				'type' => 'input',
				'default_value' => 1
			),
			'thumbnail_width' =>  array (
				'type' => 'input',
				'default_value' => 80
			)
		),
		'blocks/products_scroller2.tpl' => array (
			'hide_image' => array (
				'type' => 'checkbox',
				'default_value' => 'N'
			),
			'item_number' => array (
				'type' => 'checkbox',
				'default_value' => 'N'
			),
			'scroller_direction' => array (
				'type' => 'selectbox',
				'values' => array (
					'up' => 'up',
					'down' => 'down',
					'left' => 'left',
					'right' => 'right'
				),
				'default_value' => 'up'
			),
			'speed' => array (
				'type' => 'selectbox',
				'values' => array (
					'slow' => 'slow',
					'normal' => 'normal',
					'fast' => 'fast'
				),
				'default_value' => 'normal'
			),
			'easing' => array (
				'type' => 'selectbox',
				'values' => array (
					'linear' => 'linear',
					'swing' => 'swing'
				),
				'default_value' => 'swing'
			),
			'pause_delay' =>  array (
				'type' => 'input',
				'default_value' => 3000
			),
			'item_quantity' =>  array (
				'type' => 'input',
				'default_value' => 1
			),
			'thumbnail_width' =>  array (
				'type' => 'input',
				'default_value' => 40
			)
		),
		'blocks/products_scroller3.tpl' => array (
			'item_number' => array (
				'type' => 'checkbox',
				'default_value' => 'N'
			),
			'scroller_direction' => array (
				'type' => 'selectbox',
				'values' => array (
					'up' => 'up',
					'down' => 'down',
					'left' => 'left',
					'right' => 'right'
				),
				'default_value' => 'up'
			),
			'speed' => array (
				'type' => 'selectbox',
				'values' => array (
					'slow' => 'slow',
					'normal' => 'normal',
					'fast' => 'fast'
				),
				'default_value' => 'normal'
			),
			'easing' => array (
				'type' => 'selectbox',
				'values' => array (
					'linear' => 'linear',
					'swing' => 'swing'
				),
				'default_value' => 'swing'
			),
			'pause_delay' =>  array (
				'type' => 'input',
				'default_value' => 10000
			),
			'item_quantity' =>  array (
				'type' => 'input',
				'default_value' => 1
			)
		),
		'blocks/categories_multicolumns.tpl' => array (
			'number_of_columns' =>  array (
				'type' => 'input',
				'default_value' => 2
			)
		),
		'blocks/grid_list.tpl' => array (
			'item_number' => array (
				'type' => 'checkbox',
				'default_value' => 'N'
			),
			'number_of_columns' => array (
				'type' => 'input',
				'default_value' => 2
			),
			'number_of_products_to_show' => array (
				'type' => 'input',
				'default_value' => 4
			),
			'view_all_page_link' => array (
				'type' => 'input',
			)
			,
			'link1_text' => array (
				'type' => 'input',
			)
			,
			'link1_url' => array (
				'type' => 'input',
			)
			,
			'link2_text' => array (
				'type' => 'input',
			)
			,
			'link2_url' => array (
				'type' => 'input',
			)
			,
			'link3_text' => array (
				'type' => 'input',
			)
			,
			'link3_url' => array (
				'type' => 'input',
			)
			,
			'link4_text' => array (
				'type' => 'input',
			)
			,
			'link4_url' => array (
				'type' => 'input',
			)
		),
		'blocks/list_templates/clues_home_page_4x1.tpl' => array (
			'view_all_page_link' => array (
				'type' => 'input',
			)
			,
			'link1_text' => array (
				'type' => 'input',
			)
			,
			'link1_url' => array (
				'type' => 'input',
			)
			,
			'link2_text' => array (
				'type' => 'input',
			)
			,
			'link2_url' => array (
				'type' => 'input',
			)
			,
			'link3_text' => array (
				'type' => 'input',
			)
			,
			'link3_url' => array (
				'type' => 'input',
			)
			,
			'link4_text' => array (
				'type' => 'input',
			)
			,
			'link4_url' => array (
				'type' => 'input',
			)
		),
		'blocks/list_templates/clues_home_page_4x2.tpl' => array (
			'view_all_page_link' => array (
				'type' => 'input',
			)
			,
			'link1_text' => array (
				'type' => 'input',
			)
			,
			'link1_url' => array (
				'type' => 'input',
			)
			,
			'link2_text' => array (
				'type' => 'input',
			)
			,
			'link2_url' => array (
				'type' => 'input',
			)
			,
			'link3_text' => array (
				'type' => 'input',
			)
			,
			'link3_url' => array (
				'type' => 'input',
			)
			,
			'link4_text' => array (
				'type' => 'input',
			)
			,
			'link4_url' => array (
				'type' => 'input',
			)
		),
		'blocks/list_templates/clues_home_page_3x1.tpl' => array (
			'view_all_page_link' => array (
				'type' => 'input',
			)
			,
			'link1_text' => array (
				'type' => 'input',
			)
			,
			'link1_url' => array (
				'type' => 'input',
			)
			,
			'link2_text' => array (
				'type' => 'input',
			)
			,
			'link2_url' => array (
				'type' => 'input',
			)
			,
			'link3_text' => array (
				'type' => 'input',
			)
			,
			'link3_url' => array (
				'type' => 'input',
			)
			,
			'link4_text' => array (
				'type' => 'input',
			)
			,
			'link4_url' => array (
				'type' => 'input',
			)
		),
		'blocks/list_templates/clues_home_page_3x2.tpl' => array (
			'view_all_page_link' => array (
				'type' => 'input',
			)
			,
			'link1_text' => array (
				'type' => 'input',
			)
			,
			'link1_url' => array (
				'type' => 'input',
			)
			,
			'link2_text' => array (
				'type' => 'input',
			)
			,
			'link2_url' => array (
				'type' => 'input',
			)
			,
			'link3_text' => array (
				'type' => 'input',
			)
			,
			'link3_url' => array (
				'type' => 'input',
			)
			,
			'link4_text' => array (
				'type' => 'input',
			)
			,
			'link4_url' => array (
				'type' => 'input',
			)
		),
		'blocks/clues_cateogry_4product_list.tpl' => array (
			'view_all_page_link' => array (
				'type' => 'input',
			)
			,
			'link1_text' => array (
				'type' => 'input',
			)
			,
			'link1_url' => array (
				'type' => 'input',
			)
			,
			'link2_text' => array (
				'type' => 'input',
			)
			,
			'link2_url' => array (
				'type' => 'input',
			)
			,
			'link3_text' => array (
				'type' => 'input',
			)
			,
			'link3_url' => array (
				'type' => 'input',
			)
			,
			'link4_text' => array (
				'type' => 'input',
			)
			,
			'link4_url' => array (
				'type' => 'input',
			)
		),
		'blocks/list_templates/clues_category_3x1.tpl' => array (
			'view_all_page_link' => array (
				'type' => 'input',
			)
			,
			'link1_text' => array (
				'type' => 'input',
			)
			,
			'link1_url' => array (
				'type' => 'input',
			)
			,
			'link2_text' => array (
				'type' => 'input',
			)
			,
			'link2_url' => array (
				'type' => 'input',
			)
			,
			'link3_text' => array (
				'type' => 'input',
			)
			,
			'link3_url' => array (
				'type' => 'input',
			)
			,
			'link4_text' => array (
				'type' => 'input',
			)
			,
			'link4_url' => array (
				'type' => 'input',
			)
		),
		'blocks/list_templates/clues_category_3x2.tpl' => array (
			'view_all_page_link' => array (
				'type' => 'input',
			)
			,
			'link1_text' => array (
				'type' => 'input',
			)
			,
			'link1_url' => array (
				'type' => 'input',
			)
			,
			'link2_text' => array (
				'type' => 'input',
			)
			,
			'link2_url' => array (
				'type' => 'input',
			)
			,
			'link3_text' => array (
				'type' => 'input',
			)
			,
			'link3_url' => array (
				'type' => 'input',
			)
			,
			'link4_text' => array (
				'type' => 'input',
			)
			,
			'link4_url' => array (
				'type' => 'input',
			)
		),
		'blocks/list_templates/clues_category_4x2.tpl' => array (
			'view_all_page_link' => array (
				'type' => 'input',
			)
			,
			'link1_text' => array (
				'type' => 'input',
			)
			,
			'link1_url' => array (
				'type' => 'input',
			)
			,
			'link2_text' => array (
				'type' => 'input',
			)
			,
			'link2_url' => array (
				'type' => 'input',
			)
			,
			'link3_text' => array (
				'type' => 'input',
			)
			,
			'link3_url' => array (
				'type' => 'input',
			)
			,
			'link4_text' => array (
				'type' => 'input',
			)
			,
			'link4_url' => array (
				'type' => 'input',
			)
		),
		'blocks/clues_cateogry_3product_list.tpl' => array (
			'item_number' => array (
				'type' => 'checkbox',
				'default_value' => 'N'
			),
			'number_of_columns' => array (
				'type' => 'input',
				'default_value' => 2
			),
			'number_of_products_to_show' => array (
				'type' => 'input',
				'default_value' => 4
			),
			'view_all_page_link' => array (
				'type' => 'input',
			)
		),
		'blocks/home_top_banner_grid_list.tpl' => array (
			'item_number' => array (
				'type' => 'checkbox',
				'default_value' => 'N'
			),
			'number_of_columns' => array (
				'type' => 'input',
				'default_value' => 2
			),
			'number_of_products_to_show' => array (
				'type' => 'input',
				'default_value' => 4
			),
			'show_discount_label'=>array(
				'type'=>'checkbox',
				'default_value'=>'Y'
			  				   
			),
			'view_all_url'=>array(
				'type'=>'input',			  				   
			),
			'show_title'=>array(
				'type'=>'checkbox',	
				'default_value'=>'N',
			),
			'icon_image_url'=>array(
				'type'=>'input',			  				   
			),
			'punch_line_text'=>array(
				'type'=>'input',			  				   
			),
		),
	),
);

?>
