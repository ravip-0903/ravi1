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


if ( !defined('AREA') )	{ die('Access denied');	}

include(DIR_LIB . 'js/elfinder/connectors/php/elFinder.class.php');


$opts = array(
	'rootAlias' => fn_get_lang_var('home'),
	'tmbDir' => '',
	'dirSize' => false,
	'fileMode' => DEFAULT_FILE_PERMISSIONS,
	'dirMode' => DEFAULT_DIR_PERMISSIONS,
	'uploadDeny' => Registry::get('config.forbidden_mime_types'),
	'disabled' => array('mkfile', 'rename', 'paste', 'read', 'edit', 'archive', 'extract'),
);

if ($mode == 'files') {

	$opts['root'] = DIR_ROOT;
	$opts['URL'] = Registry::get('config.current_location') . '/';

	$fm = new elFinder($opts); 

	$fm->run();

} elseif ($mode == 'images') {


	$opts['root'] = DIR_IMAGES;
	$opts['URL'] = Registry::get('config.current_location') . '/images/';
	
	$fm = new elFinder($opts); 
	$fm->run();
}

exit;

?>