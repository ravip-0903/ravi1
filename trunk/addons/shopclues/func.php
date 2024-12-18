<?php


if ( !defined('AREA') ) { die('Access denied'); }


function fn_shopclues_init_templater($view, $view_mail)
{    
    $view->assign('storeURL', dirname($_SERVER['SCRIPT_NAME']));
}

?>
