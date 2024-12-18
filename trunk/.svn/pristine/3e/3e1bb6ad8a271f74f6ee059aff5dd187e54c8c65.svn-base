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
// $Id: quickbooks_export.pre.php 7502 2009-05-19 14:54:59Z zeke $
//

if ( !defined('AREA') ) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	if ($mode == 'export_to_iif') {

		foreach ($_REQUEST['order_ids'] as $k => $v) {
			$orders[$k] = fn_get_order_info($v);
		}

		$order_certificates = array();

		foreach ($orders as $k => $v) {
			if (!empty($v['gift_certificates'])) {
				foreach ($v['gift_certificates'] as $key => $value) {
					$order_certificates[$value['gift_cert_code']] = $value;
				}
			}
		}

		$view->assign('order_certificates', $order_certificates);
	}
}
?>