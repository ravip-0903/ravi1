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
// $Id: products.post.php 10704 2010-09-24 13:09:48Z alexions $
//

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

	if ($mode == 'search') {
		if (isset($_REQUEST['q']) && strlen($_REQUEST['q']) >= Registry::get('addons.quick_search.min_length')) {
			$attr = strip_tags($_REQUEST['q']);
			$md5 = md5('n' . DESCR_SL . $attr);
			
			$_data = array(
				'id' => $md5,
				'item_id' => 0,
				'type' => 'u',
				'lang_code' => DESCR_SL,
				'text' => $attr,
			);
			
			db_query('REPLACE INTO ?:quick_search ?e', $_data);
		}
	}
}