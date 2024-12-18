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
// $Id: objects.post.php 8329 2009-11-27 10:00:40Z ivan $
//

if ( !defined('AREA') ) { die('Access denied'); }

//
// Add new tables
//
$schema['product']['database']['product_required_products'] = array (
	'keys' => array ('product_id', 'required_id'),
	'parent' => array (),
	'children' => array (),
	'is_auto' => false,
);

?>