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
// $Id: config.php 8137 2009-10-28 12:38:53Z zeke $
//

if ( !defined('AREA') ) { die('Access denied'); }

define('AF_ORDER_TOTAL_FACTOR', 2);
define('AF_COMPLETED_ORDERS_FACTOR', 2);
define('AF_FAILED_ORDERS_FACTOR', 1.5);
define('AF_ERROR_FACTOR', 5);

?>
