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
// $Id: clues_bucks.php 11458 2010-12-23 13:18:51Z alexions $
//

if ( !defined('AREA') ) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	if ($mode == 'do_verify') {
		return array(CONTROLLER_STATUS_REDIRECT, "clues_bucks.verify?email_id=$_POST[email_id]");
	}

}

if ($mode == 'verify') {

	fn_add_breadcrumb(fn_get_lang_var('clues_bucks_verification'));
    //$user_id = db_get_field("SELECT user_id FROM `cscart_users` WHERE email = '".$_REQUEST['email_id']."' ");
    
	$cb = db_get_row(" SELECT cu.email, cu.user_id, cud.type, cud.data FROM cscart_users cu LEFT JOIN cscart_user_data cud ON cud.user_id = cu.user_id 
	                   AND cud.type = 'W' WHERE cu.email = '".$_REQUEST['email_id']."' "); 
	                  
    $cb_remain = @unserialize($cb['data']);	
    //echo '<pre>';print_r($cb);echo'</pre>'; die;
 
	$view->assign('cb_remain', $cb_remain);
	$view->assign('cb', $cb);
}  
?>
