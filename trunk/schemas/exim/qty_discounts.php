<?php
/***************************************************************************
*                                                                          *
*   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
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
// $Id: qty_discounts.php 12865 2011-07-05 06:57:22Z 2tl $
//

if ( !defined('AREA') ) { die('Access denied'); }

//
// Additional product images schema
//
$schema = array (
	'section' => 'products',
	'name' => fn_get_lang_var('qty_discounts'),
	'pattern_id' => 'qty_discounts',
	'key' => array('product_id'),
	'table' => 'products',
	'references' => array (
		'product_prices' => array (
			'reference_fields' => array('product_id' => '#key'),
			'join_type' => 'INNER',
			'alt_key' => array('lower_limit', 'usergroup_id', '#key')
		),
	),
	'range_options' => array (
		'selector_url' => 'products.manage',
		'object_name' => fn_get_lang_var('products'),
	),
	'options' => array (
		'lang_code' => array (
			'title' => 'language',
			'type' => 'languages'
		),
		'price_dec_sign_delimiter' => array (
			'title' => 'price_dec_sign_delimiter',
			'description' => 'text_price_dec_sign_delimiter',
			'type' => 'input',
			'default_value' => '.'
		),
	),
	'export_fields' => array (
		'Product code' => array (
			'required' => true,
			'alt_key' => true,
			'db_field' => 'product_code'
		),
		'Price' => array (
			'table' => 'product_prices',
			'db_field' => 'price',
			'required' => true,
			'convert_put' => array ('fn_exim_import_price', '@price_dec_sign_delimiter'),
			'process_get' => array ('fn_exim_export_price', '#this', '@price_dec_sign_delimiter'),
		),
		'Lower limit' => array (
			'table' => 'product_prices',
			'db_field' => 'lower_limit',
			'key_component' => true,
			'required' => true,
		),
		'User group' => array (
			'db_field' => 'usergroup_id',
			'table' => 'product_prices',
			'key_component' => true,
			'process_get' => array ('fn_exim_get_usergroup', '#this', '@lang_code'),
			'process_put' => array('fn_exim_put_usergroup', '#this', '@lang_code'),
			'return_result' => true
		),
	),
);
function fn_exim_get_usergroup($usergroup_id, $lang_code = '')
{
	if ($usergroup_id < ALLOW_USERGROUP_ID_FROM) {
		$default_usergroups = fn_get_default_usergroups($lang_code);
		$usergroup = !empty($default_usergroups[$usergroup_id]['usergroup'])? $default_usergroups[$usergroup_id]['usergroup'] : '';
	} else {
		$usergroup = db_get_field("SELECT usergroup FROM ?:usergroup_descriptions WHERE usergroup_id = ?i AND lang_code = ?s", $usergroup_id, $lang_code);
	}

	return $usergroup;
}

function fn_exim_put_usergroup($data, $lang_code = '')
{
	if (empty($data)) {
		return 0;
	}

	$default_usergroups = fn_get_default_usergroups($lang_code);
	foreach ($default_usergroups as $usergroup_id => $ug) {
		if ($ug['usergroup'] == $data) {
			return $usergroup_id;
		}
	}

	$usergroup_id = db_get_field("SELECT usergroup_id FROM ?:usergroup_descriptions WHERE usergroup = ?s AND lang_code = ?s LIMIT 1", $data, $lang_code);

	// Create new usergroup
	if (empty($usergroup_id)) {
		$_data = array (
			'type' => 'C', //customer
			'status' => 'A'
		);

		$usergroup_id = db_query("INSERT INTO ?:usergroups ?e", $_data);

		$_data = array (
			'usergroup_id' => $usergroup_id,
			'usergroup' => $data
		);
		foreach ((array)Registry::get('languages') as $_data['lang_code'] => $v) {
			db_query("INSERT INTO ?:usergroup_descriptions ?e", $_data);
		}
	}

	return $usergroup_id;
}
?>