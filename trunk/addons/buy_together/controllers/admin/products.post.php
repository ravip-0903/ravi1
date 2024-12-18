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
// $Id: products.post.php 8413 2009-12-16 12:45:19Z imac $
//

if ( !defined('AREA') ) { die('Access denied'); }

if ($mode == 'update') {
	$is_restricted = false;
	$show_notice = false;
	
	fn_set_hook('buy_together_restricted_product', $_REQUEST['product_id'], $auth, $is_restricted, $show_notice);
	
	if (!$is_restricted) {
		Registry::set('navigation.tabs.buy_together', array (
			'title' => fn_get_lang_var('buy_together'),
			'js' => true
		));
		
		$params = array(
			'product_id' => $_REQUEST['product_id'],
		);
		
		$chains = fn_buy_together_get_chains($params, array(), DESCR_SL);
	
		$view->assign('chains', $chains);
	}
}

?>
