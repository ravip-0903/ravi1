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
// $Id: structure.post.php 11291 2010-11-24 13:36:08Z klerik $
//

if ( !defined('AREA') ) { die('Access denied'); }

$schema['products']['fillings']['also_bought'] = array (
	'params' => array (
		'sort_by' => 'amnt',
		'request' => array(
			'also_bought_for_product_id' => '%PRODUCT_ID%'
		),
	),
	'locations' => array( // applicable to these locations only
		'products'
	),

	'update_handlers' => array ('also_bought_products'),
);

?>