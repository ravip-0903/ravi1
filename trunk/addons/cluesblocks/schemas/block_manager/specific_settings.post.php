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
// $Id: specific_settings.post.php 7502 2009-05-19 14:54:59Z zeke $
//

if ( !defined('AREA') ) { die('Access denied'); }

$schema['fillings']['categoryfeaturedproducts'] = array (
	'limit' => array (
		'type' => 'input',
		'default_value' => 4,
	),
	'category' => array (
		'type' => 'input',
		'default_value' => '',
	)
);

$schema['fillings']['categorydealindexproducts'] = array (
	'limit' => array (
		'type' => 'input',
		'default_value' => 4,
	),
	'category' => array (
		'type' => 'input',
		'default_value' => '',
	)
);

$schema['fillings']['category_best_seller'] = array (
	'limit' => array (
		'type' => 'input',
		'default_value' => 4,
	),
	'category' => array (
		'type' => 'input',
		'default_value' => '',
	)
);

$schema['fillings']['clues_popularity'] = array (
	'limit' => array (
		'type' => 'input',
		'default_value' => 4,
	),
	'category' => array (
		'type' => 'input',
		'default_value' => '',
	)
);

$schema['fillings']['category_new_arrival'] = array (
	'limit' => array (
		'type' => 'input',
		'default_value' => 4,
	),
	'category' => array (
		'type' => 'input',
		'default_value' => '',
	)
);

?>
