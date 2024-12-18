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
// $Id: reward_points.post.php 10229 2010-07-27 14:21:39Z 2tl $
//

if ( !defined('AREA') ) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	return;
}

if ($mode == 'userlog') {

	$user_id = (AREA == 'A') ? $_REQUEST['user_id'] : $auth['user_id']; 
	$user_isset = db_get_field("SELECT user_id FROM ?:users WHERE user_id = ?i", $user_id);
	
	if (AREA == 'A') {
		fn_add_breadcrumb(fn_get_lang_var('users'), "profiles.manage");
		fn_add_breadcrumb(fn_get_lang_var('user_details_page'), "profiles.update?user_id=" . $user_id);
	} else {
		fn_add_breadcrumb(fn_get_lang_var('reward_points_log'));
	}

	if (!empty($user_isset)) {
		if (AREA == 'A') { // FIXME: What do we need it for? Replace with get_user_info possibly

			$params = array (
				'user_id' => $user_id,
				'exclude_user_types' => array ('A', 'S'),
			);

			list($users) = fn_get_users($params, $auth, Registry::get('settings.Appearance.admin_elements_per_page'));
			$view->assign('users', $users);		
		}

		$sortings = array (
			'timestamp' => 'timestamp',
			'amount' => 'amount',
			'expire_on' => 'expire_on'
		);

		$directions = array (
			'asc' => 'asc',
			'desc' => 'desc'
		);

		$sort_order = empty($_REQUEST['sort_order']) ? '' : $_REQUEST['sort_order'];
		$sort_by = empty($_REQUEST['sort_by']) ? '' : $_REQUEST['sort_by'];
		
		if (empty($sort_order) || !isset($directions[$sort_order])) {
			$sort_order = 'desc';
		}

		if (empty($sort_by) || !isset($sortings[$sort_by])) {
			$sort_by = 'timestamp';
		}
        //code by ankur to sort the info on the basis of change id if timestamp is same
		  if($sort_by=='timestamp')
		  {
			  $sec_sort_by=',change_id';
			  $sec_sort_order=$sort_order;
		  }
		  else
		  {
			  $sec_sort_by='';
			  $sec_sort_order='';
		  }
		//code end
		$log_count = db_get_field("SELECT COUNT(change_id) FROM ?:reward_point_changes WHERE user_id = ?i", $user_id);
		/*modified by clues dev to show all cluebucks log*/
		//$limit = fn_paginate(@$_REQUEST['page'], $log_count, Registry::get('addons.reward_points.log_per_page')); // FIXME
				
		//$userlog = db_get_array("SELECT change_id, action, timestamp, amount, reason FROM ?:reward_point_changes WHERE user_id = ?i ORDER BY $sort_by $sort_order $limit", $user_id);
		//$userlog = db_get_array("SELECT change_id, action, timestamp, amount, reason,balance,expire_on,order_payment_history,ref_change_id FROM ?:reward_point_changes WHERE user_id = ?i ORDER BY $sort_by $sort_order", $user_id);
		 if (AREA == 'A'){
			$userlog = db_get_array("SELECT rpc.change_id, rpc.action, rpc.timestamp, rpc.amount, rpc.reason, rpc.balance, rpc.expire_on, rpc.order_payment_history,rpc.ref_change_id, cbt.name FROM ?:reward_point_changes rpc LEFT JOIN clues_bucks_type cbt ON rpc.type_id=cbt.id WHERE rpc.user_id = ?i ORDER BY $sort_by $sort_order $sec_sort_by $sec_sort_order", $user_id);
		 }
		 elseif(AREA == 'C'){
			$userlog =db_get_array("SELECT rpc.change_id, rpc.action, rpc.timestamp, rpc.amount, rpc.reason, rpc.balance, if(date(rpc.expire_on) = '3000-01-01', null, rpc.expire_on) as expire_on, rpc.order_payment_history,rpc.ref_change_id, cbt.name FROM ?:reward_point_changes rpc LEFT JOIN clues_bucks_type cbt ON rpc.type_id=cbt.id WHERE rpc.user_id = ?i ORDER BY $sort_by $sort_order $sec_sort_by $sec_sort_order", $user_id);
				 }
				 
		// print_r($userlog);die;
		$view->assign('sort_order', ($sort_order == 'asc') ? 'desc' : 'asc');
		$view->assign('sort_by', $sort_by);		
		$view->assign('userlog', $userlog);
			$view->assign('auth', $auth);
		
		//  Added by Sudhir dt 10th Sept 2012
		$clues_bucks_type = db_get_array("SELECT id, name, code,expiry_days FROM clues_bucks_type WHERE status = ?s and id != ?i", 'A', 1);
		$view->assign('bucks_types', $clues_bucks_type);
		//$clues_expiry=db_get_row("select expiry_days from clues_bucks_type where id='".$_REQUEST['id']."' ");
       // echo "select expiry_days from clues_bucks_type where id='".$_REQUEST['id']."' ";
		//die;
		
		
		//  Added by Sudhir dt 10th Sept 2012 end here

	} else {
		if (empty($auth['user_id'])) {
			return array(CONTROLLER_STATUS_REDIRECT, "auth.login_form?return_url=" . urlencode(Registry::get('config.current_url')));
		} else {
			return array(CONTROLLER_STATUS_NO_PAGE);
		}
		
	}
	
}
//added by sapna to show the expiration of clues bucks .
function fn_get_clues_bucks_total($user_id)
{
	$sql= db_get_field("select data from cscart_user_data where user_id='". $user_id."' and type='W'");
	$sql=unserialize($sql);
	return $sql;
}

function fn_get_clues_bucks_thirty($user_id)
{
	
	$date = date('Y-m-d',time());
	   $time=(30*3600*24);
	  $ctime =date('Y-m-d',TIME+$time);
	  
	$sql= db_get_array("select sum(balance) as amount from cscart_reward_point_changes where user_id='".$user_id."' and expire_on>='".$date."' && expire_on<='".$ctime."'");
	//echo "select sum(balance) as amount  from cscart_reward_point_changes where user_id='".$user_id."' and expire_on>='".$date."' && expire_on<='".$ctime."'and balance>0 OR expire_on=NULL";
	//echo "<pre>";
	//print_r($sql);
//print_r($sql);
	//$view->assign('clues_bucks',$sql);
	//$view->assign('adtime',$date);
//print_r($sql);
return $sql;
	}

function fn_get_clues_bucks_sixty($user_id)
{
	//$date = date('Y-m-d',time());
	 //$timestamp=(30*3600*24);
	 $date = date('Y-m-d',time());
	   $time=(60*3600*24);
	 //$ttime =date('Y-m-d',TIME+$timestamp);
	  $stime =date('Y-m-d',TIME+$time);
	  
	$sql= db_get_array("select sum(balance) as amount  from cscart_reward_point_changes where user_id='". $user_id."' and expire_on>='".$date."' && expire_on<='".$stime."'");
	$notexpiry= db_get_field("select sum(amount) as cb from cscart_reward_point_changes where user_id='".$user_id."' and expire_on=NULL");
	//echo "select sum(amount) as cb from cscart_reward_point_changes where user_id='".$user_id."' and expire_on=NULL";
	//print_r($notexpiry);
	//$view->assign('clues_bucks',$sql);
	//$view->assign('adtime',$date);

return $sql;
	}
?>
