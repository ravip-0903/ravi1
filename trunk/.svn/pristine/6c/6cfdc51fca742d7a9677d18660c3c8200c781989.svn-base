<?php
/***************************************************************************
*                                                                          *
*   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
*                                                                          *
* This  is  commercial  software,  only  users  who have purchased a valid *
* license  and  accept  to the terms of the  License Agreement can install *
* and use this program.                                                    *
*                                                                          *
****************************************************************************
* PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
* "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
****************************************************************************/


if ( !defined('AREA') )	{ die('Access denied');	}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if ($mode == 'validate_request' && !empty($_REQUEST['token'])) {
		$result = 'invalid';
		if (Registry::get('settings.hd_request_code') == trim($_REQUEST['token'])) {
			$result = 'valid';
		}
		
		echo $result;
		exit(0);
	} elseif ($mode == 'messages') {
		if (!empty($_REQUEST['token'])) {
			$uc_settings = fn_get_settings('Upgrade_center');
			
			$is_valid = fn_get_contents($uc_settings['updates_server'] . '/index.php?dispatch=validators.validate_request&token=' . $_REQUEST['token'] . '&license_key=' . $uc_settings['license_number']);
			if ($is_valid == 'valid') {
				$data = simplexml_load_string(urldecode($_REQUEST['request']));
				
				fn_helpdesk_process_messages($data->Messages);
				
				echo 'OK';
				exit(0);
				
			} else {
				return array(CONTROLLER_STATUS_NO_PAGE);
			}
		}
	}
}

return array(CONTROLLER_STATUS_NO_PAGE);

?>