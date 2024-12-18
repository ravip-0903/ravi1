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
// $Id: schema.post.php 7502 2009-05-19 14:54:59Z zeke $
//

if ( !defined('AREA') ) { die('Access denied'); }

$schema['conditions']['reward_points'] = array (
	'operators' => array ('eq', 'neq', 'lte', 'gte', 'lt', 'gt'),
	'type' => 'input',
	'field' => '@auth.points',
	'zones' => array('catalog', 'cart')
);

$schema['bonuses']['give_points'] = array (
	'type' => 'input',
	'function' => array('fn_reward_points_promotion_give_points', '#this', '@cart', '@auth', '@cart_products'),
	'zones' => array('cart'),
);

$schema['bonuses']['give_points_pct'] = array (
	'type' => 'hidden',
	'function' => array('fn_reward_points_promotion_give_points_pct', '#this', '@cart', '@auth', '@cart_products'),
	'zones' => array('cart'),
);
?>
