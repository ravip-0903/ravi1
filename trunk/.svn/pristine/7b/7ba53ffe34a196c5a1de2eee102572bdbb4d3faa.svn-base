<?php
/******************************************************************************
*                                                                             *
*     Copyright (c) 2009 Simbirsk Technologies LTD. All rights reserved.      *
*                                                                             *
*******************************************************************************
*                                                                             *
* CS-Cart  is  commercial  software,  only  users  who have purchased a valid *
* license through  http://www.cs-cart.com/  and  accept  to the terms of this *
* License Agreement can install this product.                                 *
*                                                                             *
*******************************************************************************
* THIS  CS-CART  SHOP END-USER LICENSE AGREEMENT IS A LEGAL AGREEMENT BETWEEN *
* YOU  AND  YOUR  COMPANY (COLLECTIVELY, "YOU") AND SIMBIRSK TECHNOLOGIES LTD *
* (HEREINAFTER  REFERRED  TO   AS  "THE AUTHOR")  FOR  THE  SOFTWARE  PRODUCT *
* IDENTIFIED  ABOVE,  WHICH  INCLUDES  COMPUTER   SOFTWARE  AND  MAY  INCLUDE *
* ASSOCIATED   MEDIA,   PRINTED  M ATERIALS,   AND   "ONLINE"  OR  ELECTRONIC *
* DOCUMENTATION  (COLLECTIVELY,  THE  "SOFTWARE").  BY  USING  THE  SOFTWARE, *
* YOU  SIGNIFY YOUR AGREEMENT TO ALL TERMS, CONDITIONS, AND NOTICES CONTAINED *
* OR  REFERENCED  HEREIN.  IF  YOU  ARE NOT  WILLING  TO  BE  BOUND  BY  THIS *
* AGREEMENT, DO NOT INSTALL OR USE THE SOFTWARE.                              *
*                                                                             *
* PLEASE   READ  THE   FULL  TEXT  OF  SOFTWARE  LICENSE   AGREEMENT  IN  THE *
* "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION. THE AGREEMENT TEXT IS *
* ALSO AVAILABLE AT THE FOLLOWING URL: http://www.cs-cart.com/license.html    *
******************************************************************************/

//
// $Id: config.php 6788 2009-01-16 13:29:11Z zeke $
//

if ( !defined('AREA') ) { die('Access denied'); }

Registry::set('recurring_billing_data', array(
	'periods' => array(
		'A' => 'rb_annually',
		'Q' => 'rb_quarterly',
		'M' => 'rb_monthly',
		'W' => 'rb_weekly',
		'P' => 'rb_by_period'
	),
	'price' => array('original', 'to_percentage', 'by_percentage', 'to_fixed', 'by_fixed'),
	'events' => array(
		'A' => 'rb_attempt_charging',
		'C' => 'rb_charge_subscription',
		'F' => 'rb_notification_future_paying',
		'M' => 'rb_notification_manual_paying'
	),
	'events_per_pass' => 10
));

?>
