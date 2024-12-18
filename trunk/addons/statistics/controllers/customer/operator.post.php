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
// $Id: operator.post.php 7502 2009-05-19 14:54:59Z zeke $
//

if ( !defined('AREA') )	{ die('Access denied');	}

if (!empty($_REQUEST['on_timer']) && !empty($SESSID)) {
	
	// Retrieving information about visitor
	if ((!empty($_REQUEST['get_info']) || !empty($_REQUEST['trace_info'])) && !empty($visitor_ip) && !empty($_results)) {

		$sess_id = db_get_field("SELECT MAX(sess_id) FROM ?:stat_sessions WHERE host_ip='$visitor_ip'");
		$_results['visit_history'] = db_get_hash_array("SELECT req_id AS id, timestamp AS date, title, url AS href, '' AS referer FROM ?:stat_requests WHERE sess_id='$sess_id' AND  req_id > '" . $visit_history_id . "' ORDER BY timestamp", 'id');
		foreach ($_results['visit_history'] as $_id => $_row) {
			foreach ($_results['visit_history'][$_id] as $_field => $v) {
				$_results['visit_history'][$_id][$_field] = htmlentities($v);
			}
		}
		
	}
}

?>