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
// $Id: hotdeals.php 6788 2009-01-16 13:29:11Z zeke $
//

if ( !defined('AREA') )	{ die('Access denied');	}

/*if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	return;
}*/

if($mode == 'list')
{
	$categories = db_get_array("select * from clues_merchandise_data where type='DEALS' order by position asc");
	//echo '<pre>';print_r($categories);die;
	//$categories = Registry::get('config.deals_category_ids');
	if(!empty($categories) && count($categories)>0) {
		if (Registry::get('settings.General.show_products_from_subcategories') == 'Y') {
			$params['subcats'] = 'Y';
		}
		foreach($categories as $category) {
			$category_array = explode(',',$category['value']);
			$params['cid'] = $category_array;
			$params['limit'] = 20;
			$products[$category['id']] = fn_hotdeals_get_products($params);
			//echo '<pre>';print_r($params);
			
		}
		fn_add_breadcrumb(fn_get_lang_var('hotdeals'));
		//echo '<pre>';print_r($products);echo '</pre>';die;
		$view->assign('products',$products);
		//$view->assign('products',$products );
	} else {
		fn_set_notification('N', fn_get_lang_var('notice'), fn_get_lang_var('no_deals available'));
		return array(CONTROLLER_STATUS_REDIRECT, $index_script);
	}
}

?>