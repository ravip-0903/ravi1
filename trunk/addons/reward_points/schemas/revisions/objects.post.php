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
// $Id: objects.post.php 8091 2009-10-19 08:29:55Z lexa $
//

if ( !defined('AREA') ) { die('Access denied'); }

//
// Add new tables
//
$schema['product']['database']['product_point_prices'] = array (
	'keys' => array ('point_price_id'),
	'parent' => array (),
	'children' => array (),
	'is_auto' => true,
	'auto_key' => 'point_price_id'
);
$schema['product']['database']['reward_points'] = array (
	'keys' => array ('reward_point_id'),
	'main_key' => array('object_id' => '#key', 'usergroup_id' => '#key', 'object_type' => 'P'),
	'parent' => array (),
	'children' => array (),
	'is_auto' => true,
	'auto_key' => 'reward_point_id'
);
$schema['category']['database']['reward_points'] = array (
	'keys' => array ('reward_point_id'),
	'main_key' => array('object_id' => '#key', 'usergroup_id' => '#key', 'object_type' => 'C'),
	'parent' => array (),
	'children' => array (),
	'is_auto' => true,
	'auto_key' => 'reward_point_id'
);

?>