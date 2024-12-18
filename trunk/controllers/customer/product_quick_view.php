<?php

/*
 * controller for quick view 
 */
if ($mode == "view") {
    $product_id = $_GET['product_id'];
    $auth = $_SESSION['auth'];
    $count = 1;
    $_REQUEST['product_id'] = empty($_REQUEST['product_id']) ? 0 : $_REQUEST['product_id'];
    $product = fn_get_product_data($_REQUEST['product_id'], $auth, CART_LANGUAGE, '', true, true, true, true, ($auth['area'] == 'A' && !empty($_REQUEST['action']) && $_REQUEST['action'] == 'preview'));
    fn_gather_additional_product_data($product, true, true);
    if (!empty($product)) {
        $product['product'] = str_replace('[CLONE]', '', $product['product']);
    }

    if (!empty($product['product_options'])) {

        foreach ($product['product_options'] as $key => $val) {

            $pick_first = explode('-', $val['option_name']);
            $product['product_options'][$key]['option_name'] = $pick_first[0];
        }
    }
    $view->assign('product', $product);
    $vendor_rating=array();
    $vendor_rating['vendor_info'] = fn_sdeep_get_vendor_info($product['company_id']);
    $vendor_rating['rating'] = fn_sdeep_get_rating($product['company_id']);
    $vendor_rating['state'] = fn_get_vendor_state($product['company_id']);
    $view->assign('vendor_rating',$vendor_rating);
    $msg = $view->display('views/product_quick_view/view.tpl', false);
   fn_set_notification('P', $msg, '', 'I');
    exit();
}
?>
