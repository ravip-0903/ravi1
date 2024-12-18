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


//
// $Id: reward_points.php 10264 2010-07-29 08:09:07Z klerik $
//

if ( !defined('AREA') ) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	//
	//Change points
	//
	if ($mode == 'do_change_points') {
		$amount = intval($_REQUEST['reason']['amount']);
		if (!empty($amount)) {
			fn_change_user_points(($_REQUEST['reason']['action'] == 'A') ? abs($amount) : -abs($amount), $_REQUEST['user_id'], $_REQUEST['reason']['reason'], $_REQUEST['reason']['action'],'',$_REQUEST['expires_on'], $_REQUEST['reason']['clues_bucks_type']);
			$force_notification = fn_get_notification_rules($_REQUEST);
			if (!empty($force_notification['C'])) {
				$user_data = db_get_row("SELECT firstname, email, lang_code FROM ?:users WHERE user_id = ?i", $_REQUEST['user_id']);
				Registry::get('view_mail')->assign('user_data', $user_data);
				Registry::get('view_mail')->assign('reason', $_REQUEST['reason']);
				fn_send_mail($user_data['email'], Registry::get('settings.Company.company_users_department'), 'addons/reward_points/notification_subj.tpl', 'addons/reward_points/notification.tpl', '', $user_data['lang_code']);
			}
		}
	} 

	if ($mode == 'do_userlog_delete') {
		foreach ($_REQUEST['change_ids'] as $change_id) {
			/*code to log the clues bucks event*/
			$log_amount = db_get_field("select amount from ?:reward_point_changes WHERE change_id = ?i", $change_id);
			$log_reason = 'Request to delete the clues bucks entry whose change_id is '.$change_id ;
			$updated_by = $_SESSION['auth']['user_id'];
			$update_time = date('Y-m-d h:i:s');
			$to_user = $_REQUEST['user_id'];
	
			$sql = "insert into clues_bucks_history (amount, reason, update_by, update_time, to_user) value ('".$log_amount."','".$log_reason."','".$updated_by."','".$update_time."','".$to_user."')";
			db_query($sql);
			/*code to log the clues bucks event*/
			
			db_query("DELETE FROM ?:reward_point_changes WHERE change_id = ?i", $change_id);
		}
	}

	if ($mode == 'do_cleanup_logs') {
		/*code to log the clues bucks event*/
		$log_amount = '';
		$log_reason = 'Request to cleanup the clues bucks entry whose user_id is '.$_REQUEST['user_id'] ;
		$updated_by = $_SESSION['auth']['user_id'];
		$update_time = date('Y-m-d h:i:s');
		$to_user = $_REQUEST['user_id'];
	
		$sql = "insert into clues_bucks_history (amount, reason, update_by, update_time, to_user) value ('".$log_amount."','".$log_reason."','".$updated_by."','".$update_time."','".$to_user."')";
		db_query($sql);
		/*code to log the clues bucks event*/
		
		db_query("DELETE FROM ?:reward_point_changes WHERE user_id = ?i", $_REQUEST['user_id']);
	}

	// Add/Update wholesale prices info
	if ($mode == 'add' || $mode == 'update') {
		if (isset($_REQUEST['reward_points'])) {
			foreach ($_REQUEST['reward_points'] as $k => $v) {
				fn_add_reward_points($v, 0, GLOBAL_REWARD_POINTS);
			}
		}
	}

	return array(CONTROLLER_STATUS_OK, "reward_points.manage");
}



if ($mode == 'manage') {

	// Add new tab to page sections
	Registry::set('navigation.tabs.reward_points', array (
		'title' => fn_get_lang_var('reward_points'),
		'js' => true
	));

	$view->assign('reward_points', fn_get_reward_points(0, GLOBAL_REWARD_POINTS));
	$view->assign('object_type', GLOBAL_REWARD_POINTS);

} elseif ($mode == 'add') {

	// Add new tab to page sections

	Registry::set('navigation.tabs.reward_points', array (
		'title' => fn_get_lang_var('reward_points'),
		'js' => true
	));

	$view->assign('object_type', GLOBAL_REWARD_POINTS);

} /*elseif ($mode == 'do_userlog_delete') {
	if (!empty($_REQUEST['change_id'])) {
		//code to log the clues bucks event
		$log_amount = db_get_field("select amount from ?:reward_point_changes WHERE change_id = ?i", $_REQUEST['change_id']);
		$log_reason = 'Request to delete the clues bucks entry whose change_id is '.$_REQUEST['change_id'] ;
		$updated_by = $_SESSION['auth']['user_id'];
		$update_time = date('Y-m-d h:i:s');		
		$to_user = $_REQUEST['user_id'];
	
		$sql = "insert into clues_bucks_history (amount, reason, update_by, update_time, to_user) value ('".$log_amount."','".$log_reason."','".$updated_by."','".$update_time."','".$to_user."')";
		db_query($sql);
		//code to log the clues bucks event
			
		db_query("DELETE FROM ?:reward_point_changes WHERE change_id = ?i", $_REQUEST['change_id']);
	}

	return array(CONTROLLER_STATUS_REDIRECT, "reward_points.userlog?user_id=$_REQUEST[user_id]");
}*/

$view->assign('reward_usergroups', fn_array_merge(fn_get_default_usergroups(), fn_get_usergroups('C')));



/** /Body **/
?>
