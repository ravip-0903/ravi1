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

//$today_events = db_get_array("SELECT event_id, title FROM ?:giftreg_events WHERE (start_date <= ?i AND end_date > ?i) AND type IN ('P','U') ORDER BY start_date LIMIT " . (Registry::get('addons.gift_registry.events_in_sidebox') + 1), TIME, TIME);

if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
{
	$memcache = $GLOBALS['memcache'];
	$key = md5($_SERVER['QUERY_STRING'].'-today_events');
	if($mem_value = $memcache->get($key)){
		$today_events = $mem_value; 
	}else{
		$today_events = db_get_array("SELECT event_id, title FROM ?:giftreg_events WHERE (start_date <= ?i AND end_date > ?i) AND type IN ('P','U') ORDER BY start_date LIMIT " . (Registry::get('addons.gift_registry.events_in_sidebox') + 1), TIME, TIME);
		$status = $memcache->set($key, $today_events, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time'));
                if(!$status){
                    $memcache->delete($key);
                }
	}
}else{	
	$today_events = db_get_array("SELECT event_id, title FROM ?:giftreg_events WHERE (start_date <= ?i AND end_date > ?i) AND type IN ('P','U') ORDER BY start_date LIMIT " . (Registry::get('addons.gift_registry.events_in_sidebox') + 1), TIME, TIME);
}
if (count($today_events) > Registry::get('addons.gift_registry.events_in_sidebox')) {
	array_pop($today_events);
	$view->assign('more_link', true);
}

$view->assign('today_events', $today_events);

fn_event_update_status();

?>
