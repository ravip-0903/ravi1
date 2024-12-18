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
// $Id: profiles.pre.php 7502 2009-05-19 14:54:59Z zeke $
//

if ( !defined('AREA') ) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (($mode == 'update' || $mode == 'add') && !empty($_REQUEST['user_data']['birthday'])) {
		$_REQUEST['user_data']['birthday'] = fn_parse_date($_REQUEST['user_data']['birthday']);
	}

	if ($mode == 'add' && !empty($_POST['user_data']['birthday'])) {
		$_POST['user_data']['birthday'] = fn_parse_date($_POST['user_data']['birthday']);
	}

	return;
}

?>