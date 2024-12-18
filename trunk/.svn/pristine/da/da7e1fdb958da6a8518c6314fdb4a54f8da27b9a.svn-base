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
// $Id: products.post.php 7745 2009-07-21 07:15:15Z alexions $
//

if ( !defined('AREA') ) { die('Access denied'); }

if ($mode == 'register') {
	$user_email = $_REQUEST['email'];
	if(filter_var($user_email, FILTER_VALIDATE_EMAIL)) 
	{
        $is_exist = db_get_field("SELECT user_id from ?:users WHERE email = ?s",$user_email);
		
        if($is_exist == ''){
            $_auth = &$auth;
            $pass = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,6);
            $userarray = array('email'=>$_POST['email'],'password1'=>$pass,'password2'=>$pass);
            if ($res = fn_update_user(0, $userarray, $_auth,!empty($_REQUEST['ship_to_another']),true,true)) {
                    $suffix = 'update';
                    if(isset($_REQUEST['referer']))
                    {
                            $referer = $_REQUEST['referer'];
                    }
                    else{
                            $referer = 'express';
                    }
                    $sql = "update ?:users set referer='".$referer."' where email='". $_POST['email'] ."'";
                    db_query($sql);
                    fn_set_cookie('force_signin','skip','3600');
                    fn_set_cookie_for_akamai();
                    fn_set_scun_cookie();
					//fn_set_cookie('sccache','true','3600','.shopclues.com');
					//setcookie('sccache',true,time()+3600*24,'/','.shopclues.com');
					//setcookie("sccache",true,time()+3600*24, "/", ".shopclues.com");
					//setcookie(
            }
            else
            {
                    $suffix = 'add';
            }
        }else{
            fn_set_cookie('force_signin','skip','3600');
        }
     }
	//return array(CONTROLLER_STATUS_OK, "profiles." . $suffix);
	return array(CONTROLLER_STATUS_REDIRECT,$_SERVER[HTTP_REFERER]);
}

?>
