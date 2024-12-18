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
// $Id: xmlsitemap.php 12865 2011-07-05 06:57:22Z 2tl $
//

if ( !defined('AREA') ) { die('Access denied'); }

if ($mode == 'view') {
	if (!empty($_REQUEST['page'])) {
		$page = $_REQUEST['page'];
		$filename = DIR_CACHE . 'sitemap' . $_REQUEST['page'] . '.xml';
	} else {
		$page = 0;
		$filename = DIR_CACHE . 'sitemap.xml';
	}
	
	if (file_exists($filename)) {
		header("Content-Type: text/xml;charset=utf-8");
		
		readfile($filename);
		exit();
		
	} else {
		@ignore_user_abort(true);
		set_time_limit(3600);

		fn_google_sitemap_get_content($page);
	}
}

?>