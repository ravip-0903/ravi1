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
// $Id: age_verification.php 7502 2009-05-19 14:54:59Z zeke $
//

if (!defined('AREA')) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if ($mode == 'verify') {
		if (!empty($_REQUEST['age'])) {
			$age = intval($_REQUEST['age']);

			if ($age < 0) {
				$age = 0;
			}

			$_SESSION['auth']['age'] = $age;

			if (!empty($_REQUEST['redirect_url'])) {
				return array (CONTROLLER_STATUS_OK, $_REQUEST['redirect_url']);
			}

			return array (CONTROLLER_STATUS_REDIRECT, '');
		}
	}
}

?>