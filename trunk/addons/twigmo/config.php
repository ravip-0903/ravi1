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
// $Id: config.php 12865 2011-07-05 06:57:22Z 2tl $
//

if (!defined('AREA')) { die('Access denied'); }

// addon version
Registry::set('addons.twigmo.version', '1.0.0');

Registry::set('addons.twigmo.service_url', 'http://twigmo.com/svc/index.php?dispatch=api.post');
Registry::set('addons.twigmo.service_username', '');
Registry::set('addons.twigmo.service_password', '');

if (file_exists(DIR_ADDONS . 'twigmo/local_conf.php')) {
	include(DIR_ADDONS . 'twigmo/local_conf.php');
}

?>