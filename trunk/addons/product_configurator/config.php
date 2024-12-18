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
// $Id: config.php 7502 2009-05-19 14:54:59Z zeke $
//
if ( !defined('AREA') ) { die('Access denied'); }

define('DIGG_DEPTH', 1); // This constant defines the deep of hierarchy analysis of compatibilities. Be careful it may slow down the store.
define('FREE_RECCOMMENDED', 1); // This constant leads to not checking the compatibilites if reccomended products are defined.

?>
