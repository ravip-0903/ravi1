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

$schema['fillings']['bestsellers'] = array (
	'limit' => array (
		'type' => 'input',
		'default_value' => 3,
	)
);

/*Added by clues dev to add new block*/
$schema['fillings']['clues_bestsellers'] = array (
	'limit' => array (
		'type' => 'input',
		'default_value' => 3,
	),
	'category' => array (
		'type' => 'input',
		'default_value' => 92,
	)
);
/*Added by clues dev to add new block*/
?>