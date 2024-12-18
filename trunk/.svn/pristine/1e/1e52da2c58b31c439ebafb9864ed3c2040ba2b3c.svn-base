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
// $Id: checkout.post.php 12865 2011-07-05 06:57:22Z 2tl $
//

if ( !defined('AREA') )	{ die('Access denied');	}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if ($mode == 'customer_info' || ($mode == 'update_steps' && isset($_REQUEST['update_step']) && $_REQUEST['update_step'] == 'step_one') || $mode == 'add_profile') {
		$subscriber = db_get_row("SELECT * FROM ?:subscribers WHERE email = ?s", $_REQUEST['user_data']['email']);
		if (!empty($_REQUEST['mailing_lists']) && !fn_is_empty($_REQUEST['mailing_lists'])) {
			if (empty($subscriber)) {
				$_data = array(
					'email' => $_REQUEST['user_data']['email'],
					'timestamp' => TIME,
				);
				
				$subscriber_id = db_query("INSERT INTO ?:subscribers ?e", $_data);
			} else {
				$subscriber_id = $subscriber['subscriber_id'];
			}
			
			fn_update_subscriptions($subscriber_id, $_REQUEST['mailing_lists'], $_REQUEST['newsletter_format'], NEWSLETTER_SAVE_UNCHECKED, NULL, fn_get_notification_rules(true));
		} elseif (isset($_REQUEST['mailing_lists'])) {
			if (!empty($subscriber)) {
				fn_delete_subscribers($subscriber['subscriber_id']);
			}
		}
	}
	
	return true;
}

?>