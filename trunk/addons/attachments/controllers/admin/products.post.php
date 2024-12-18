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
// $Id: products.post.php 10668 2010-09-21 06:09:52Z klerik $
//

if (!defined('AREA')) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	return;
}

if ($mode == 'update') {
	// Assign attachments files for products
	$attachments = fn_get_attachments('product', $_REQUEST['product_id'], 'M', DESCR_SL, empty($_REQUEST['rev']['product']) ? null : $_REQUEST['rev']['product'], empty($_REQUEST['rev_id']['product']) ? null : $_REQUEST['rev_id']['product']);

	Registry::set('navigation.tabs.attachments', array (
		'title' => fn_get_lang_var('attachments'),
		'js' => true
	));

	$view->assign('attachments', $attachments);
}

?>
