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

/*This code is added by Chandan to shut down the store for a time. To open the store again just 
comment this line and close the store uncomment this line.*/

//if(isset($_REQUEST['store_access_key']))
//{header('Location:Sitedown.html');die;};
//-----{header('Sitedown.html');die;};;

/*Site shut down code ends here.*/
if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();

//
// $Id: index.php 11839 2011-02-14 15:27:46Z 2tl $
//

define('AREA', 'C');
define('AREA_NAME', 'customer');
define('ACCOUNT_TYPE', 'customer');
require dirname(__FILE__) . '/prepare.php';
require dirname(__FILE__) . '/init.php';

define('INDEX_SCRIPT', Registry::get('config.customer_index'));

fn_dispatch();

?>
