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
// $Id: trusted_controllers.post.php 0 2009-12-28 00:00:00Z 2tl $
//

// if allow == true all modes in controller are allowed, no changes needed.
if (!(isset($schema['exim']['allow']) && $schema['exim']['allow'] === true)) {
	$schema['exim']['allow']['cron_export'] = true;
}

?>