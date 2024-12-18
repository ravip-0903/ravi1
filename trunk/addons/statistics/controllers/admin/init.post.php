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
// $Id: init.post.php 7502 2009-05-19 14:54:59Z zeke $
//

if ( !defined('AREA') ) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	return;
}

$view->assign('online_time', SESSION_ONLINE);
$view->assign('users_online', db_get_field("SELECT COUNT(distinct ?:stat_requests.sess_id) FROM ?:stat_requests LEFT JOIN ?:stat_sessions ON ?:stat_requests.sess_id = ?:stat_sessions.sess_id WHERE ?:stat_requests.timestamp >= ?i AND ?:stat_requests.timestamp <= ?i AND ?:stat_sessions.client_type = 'U'", (TIME - SESSION_ONLINE), TIME)); // Count active connections in last 5 minutes 

?>
