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


if (!defined('AREA')) {
    die('Access denied');
}

if ($mode == 'view') {
     $market_id = $_REQUEST['market_id'];
    //$market_id = $_REQUEST['object_id'];
    $categories = fn_get_nrh_categories($market_id);
    foreach ($categories as $k => $v) {
        $categories[$k]['main_pair'] = fn_get_image_pairs($v['pcategory_id'], 'category', 'M');
    }
    $view->assign('categories', $categories);
    $view->assign('market', fn_get_nrh_markets($market_id));
}

function p($s, $die=true) {echo '<pre>';print_r($s);if($die)die;}
?>