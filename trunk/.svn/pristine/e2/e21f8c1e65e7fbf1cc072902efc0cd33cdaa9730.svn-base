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
// $Id: quick_search.php 11339 2010-12-02 12:47:50Z alexions $
//

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

	if ($mode == 'generate_catalog') {
		fn_generate_search_catalog();
		
		return array(CONTROLLER_STATUS_REDIRECT, $index_script);
	}
}