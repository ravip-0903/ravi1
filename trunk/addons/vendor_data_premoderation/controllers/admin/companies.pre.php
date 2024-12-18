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


//
// $Id: companies.pre.php 12865 2011-07-05 06:57:22Z 2tl $
//

if ( !defined('AREA') )	{ die('Access denied');	}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (($mode == 'add' || $mode == 'update') && !empty($_REQUEST['company_data'])) {
		if (defined('COMPANY_ID')) {
			unset($_REQUEST['company_data']['pre_moderation'], $_POST['company_data']['pre_moderation']);
			unset($_REQUEST['company_data']['pre_moderation_edit'], $_POST['company_data']['pre_moderation_edit']);
			unset($_REQUEST['company_data']['pre_moderation_edit_vendors'], $_POST['company_data']['pre_moderation_edit_vendors']);
		}
	}
}

if ($mode == 'update') {
	if (defined('COMPANY_ID')) {
		$company_data = Registry::get('s_companies.' . COMPANY_ID);
		$vendor_profile_updates_approval = Registry::get('addons.vendor_data_premoderation.vendor_profile_updates_approval');
		if ($company_data['status'] == 'A' && ($vendor_profile_updates_approval == 'all' || ($vendor_profile_updates_approval == 'custom' && !empty($company_data['pre_moderation_edit_vendors']) && $company_data['pre_moderation_edit_vendors'] == 'Y'))) {
			$view->assign('form_class', 'cm-vendor-changes-confirm');
		}
	}
}

?>