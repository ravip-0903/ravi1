<?php 

if ( !defined('AREA') ) { die('Access denied'); }

function fn_my_changes_get_product_data_more()
{
    $root_categories = fn_get_subcategories(0);
	foreach ($root_categories as $k => $v) {
		$root_categories[$k]['subcategories'] = fn_get_categories_tree($v['category_id']);
	}
	return $root_categories;
}


function fn_list_tabs($nav)
{	
	$GLOBALS['topLinks'][] = $nav;
	return $GLOBALS['topLinks'];
}

function fn_get_company_contactperson($company_id)
{
	$merchant_name = db_get_row("SELECT firstname, lastname FROM cscart_users WHERE company_id=$company_id");
	return ($merchant_name);
}
?>