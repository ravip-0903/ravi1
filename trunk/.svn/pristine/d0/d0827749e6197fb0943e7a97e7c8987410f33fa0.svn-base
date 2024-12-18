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
// $Id: promotions.post.php 8413 2009-12-16 12:45:19Z imac $
//

if (!defined('AREA') ) { die('Access denied'); }

if ($mode == 'list') {
	$params['status'] = 'A';
	$params['date'] = true;
	$params['full_info'] = true;
	$params['promotions'] = true;
	
	$chains = fn_buy_together_get_chains($params, $auth);
	
	if (!empty($chains)) {
		$promotions = $view->get_var('promotions');
		$promotions['chains'] = $chains;
		
		$view->assign('promotions', $promotions);
	}

}

?>