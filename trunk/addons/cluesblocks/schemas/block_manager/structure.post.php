<?php
/***************************************************************************
*                                                                          *
*    Copyright (c) 2009 Simbirsk Technologies Ltd. All rights reserved.    *
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
// $Id: structure.post.php 11501 2010-12-29 09:23:57Z klerik $
//

if ( !defined('AREA') ) { die('Access denied'); }

$schema['products']['fillings']['categoryfeaturedproducts'] = array (
	'params' => array (
		'categoryfeaturedproducts' => true,
		'sort_by' => 'feature_index',
		'sort_order' => 'desc',
		'request' => array (
			'cid' => '%CATEGORY_ID'
		)
	),
);

$schema['products']['fillings']['categorydealindexproducts'] = array (
	'params' => array (
		'categorydealindexproducts' => true,
		'sort_by' => 'deals_index',
		'sort_order' => 'desc',
		'request' => array (
			'cid' => '%CATEGORY_ID'
		)
	),
);

$schema['products']['fillings']['category_best_seller'] = array (
	'params' => array (
		'category_best_seller' => true,
		'sales_amount_from' => 1,
		'sort_by' => 'product_sales_amount',
		'sort_order' => 'desc',
		'request' => array (
			'cid' => '%CATEGORY_ID%'
		)
	),
);

$schema['products']['fillings']['clues_popularity'] = array (
	'params' => array (
		'clues_popularity' => true,
		'sales_amount_from' => 1,
		'sort_by' => 'popularity',
		'sort_order' => 'desc',
		'request' => array (
			'cid' => '%CATEGORY_ID%'
		)
	),
);

$schema['products']['fillings']['category_new_arrival'] = array (
	'params' => array (
		'category_new_arrival' => true,
		'sort_by' => 'timestamp',
		'sort_order' => 'desc',
		'request' => array (
			'cid' => '%CATEGORY_ID%'
		)
	),
);

?>
