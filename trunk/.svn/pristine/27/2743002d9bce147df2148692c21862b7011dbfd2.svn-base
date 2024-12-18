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
// $Id: actions.post.php 10955 2010-10-19 13:17:19Z klerik $
//
if ( !defined('AREA') ) { die('Access denied'); }

/**
 * Check if mod_rewrite is active and clean up templates cache
 */
function fn_settings_actions_addons_seo(&$new_value, $old_value)
{
	if ($new_value == 'A') {
		$result = fn_http_request('GET', Registry::get('config.http_location') . '/catalog.html?version');
		if (strpos($result[0]['RESPONSE'], '200 OK') === false) {
			$new_value = 'D';
			fn_set_notification('W', fn_get_lang_var('warning'), fn_get_lang_var('warning_seo_urls_disabled'));
		}
	}

	fn_rm(DIR_COMPILED . 'customer');

	return true;
}

?>