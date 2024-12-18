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
// $Id: variants.post.php 7502 2009-05-19 14:54:59Z zeke $
//
if ( !defined('AREA') ) { die('Access denied'); }

/**
 * Get shipping methods
 */
function fn_settings_variants_addons_sms_notifications_sms_send_shipping()
{
	return db_get_hash_single_array("SELECT a.shipping_id, b.shipping FROM ?:shippings as a LEFT JOIN ?:shipping_descriptions as b ON a.shipping_id=b.shipping_id AND b.lang_code = '" . CART_LANGUAGE . "' ORDER BY a.position", array('shipping_id', 'shipping'));
}




?>