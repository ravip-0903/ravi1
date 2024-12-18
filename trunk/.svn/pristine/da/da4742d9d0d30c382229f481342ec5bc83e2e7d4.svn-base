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
// $Id: init.php 11501 2010-12-29 09:23:57Z klerik $
//

if ( !defined('AREA') ) { die('Access denied'); }

fn_register_hooks(
	'place_order',
	'get_order_info', 
	'change_order_status', 
	'clone_product', 
	array('calculate_cart', 300), 
	'get_cart_product_data', 
	'get_additional_product_data', 
	'get_user_info', 
	'get_product_options', 
	'get_selected_product_options', 
	'apply_option_modifiers', 
	'get_external_discounts', 
	'form_cart', 
	'allow_place_order', 
	'rma_recalculate_order',
	'user_init',
	'get_users',
	'get_orders',
	'get_product_data',
	'update_product',
	'update_category',
	'global_update',
	'delete_order'
);

?>