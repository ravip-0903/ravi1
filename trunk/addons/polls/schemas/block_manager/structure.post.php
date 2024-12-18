<?php
/***************************************************************************
*                                                                          *
*    Copyright (c) 2004 Simbirsk Technologies Ltd. All rights reserved.    *
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
// $Id: structure.post.php 10953 2010-10-19 12:06:06Z klerik $
//

if ( !defined('AREA') ) { die('Access denied'); }

$schema['polls'] = array (
	'fillings' => array (
		'manually',
	),
	'appearances' => array (
		'addons/polls/blocks/sidebox.tpl' => array (
			'params' => array ()
		),
		'addons/polls/blocks/central.tpl' => array ()
	),
	'dispatch' => 'pages.update',
	'object_id' => 'page_id',
	'object_name' => 'polls',
	'picker_props' => array (
		'picker' => 'addons/polls/pickers/polls_picker.tpl',
		'params' => array (
			'multiple' => true,
		),
	),

	'cache_properties' => array (
		'update_handlers' => array ('polls', 'polls_answers', 'polls_votes', 'poll_descriptions', 'poll_items'),
	),

);

?>