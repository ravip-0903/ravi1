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
// $Id: config.php 7502 2009-05-19 14:54:59Z zeke $
//

if (!defined('AREA')) { die('Access denied'); }

// form page type
define('PAGE_TYPE_POLL', 'P');

// ------------------------------------- Types and codes -------------------------------------
//
// Poll types
// A - public (for all)
// P - private
// H - hidden
// D - disabled
//
// Description types
// P - poll
// I - item
// H - header
// F - footer
// R - results
//
// Item types
// Q - question
// M - multi answers question
// T - question with text answer
// A - simple answer
// O - answer with text field
//
// ------------------------------------- Types and codes -------------------------------------
?>