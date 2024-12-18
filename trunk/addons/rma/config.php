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
// $Id: config.php 8329 2009-11-27 10:00:40Z ivan $
//

if ( !defined('AREA') ) { die('Access denied'); }

define('STATUSES_RETURN', 'R');
define('ORDER_DATA_RETURN', 'H');
define('ORDER_DATA_PRODUCTS_DELIVERY_DATE', 'V');

define('RMA_REASON', 'R');
define('RMA_ACTION', 'A');
define('RMA_DEFAULT_STATUS', 'R');
define('RETURN_PRODUCT_ACCEPTED', 'A');
define('RETURN_PRODUCT_DECLINED', 'D');

?>
