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
// $Id: languages.pre.php 11342 2010-12-02 13:47:56Z alexions $
//

if ( !defined('AREA') )	{ die('Access denied');	}

if ($mode == 'delete_language') {
	
	fn_quick_search_delete_language($_REQUEST['lang_code']);
}

function fn_quick_search_delete_language($lang_code)
{
	db_query('DELETE FROM ?:quick_search WHERE lang_code = ?s', $lang_code);
}

?>