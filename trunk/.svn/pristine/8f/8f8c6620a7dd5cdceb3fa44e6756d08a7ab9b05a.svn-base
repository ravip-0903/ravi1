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


if ( !defined('AREA') ) { die('Access denied'); }

//if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//	//
//	// Processing updating of product element
//	//
//	if ($mode == 'update') {
//		
//		if( isset($_REQUEST['product_data']['allowed_payment_method']) && !empty($_REQUEST['product_data']['allowed_payment_method'])) {
//			$allowed_payment_method = implode(',',$_REQUEST['product_data']['allowed_payment_method']);
//			$is_exist = db_get_row("select * from product_payment_method where product_id='".$_REQUEST['product_id']."'");
//			if(!empty($is_exist))
//			{
//				db_query("update product_payment_method set payment_method = '".$allowed_payment_method."' where product_id='".$is_exist['product_id']."'");
//			}
//			else
//			{
//				db_query("insert into product_payment_method (product_id, payment_method) values ('".$_REQUEST['product_id']."','".$allowed_payment_method."')");	
//			}
//		} else {
//			$is_exist = db_get_row("select * from product_payment_method where product_id='".$_REQUEST['product_id']."'");
//			if(!empty($is_exist))
//			{
//				db_query("delete from product_payment_method where product_id='".$_REQUEST['product_id']."'");
//			}
//		}
//		
//		$suffix = ".update?product_id=$_REQUEST[product_id]" . (!empty($_REQUEST['product_data']['block_id']) ? "&selected_block_id=" . $_REQUEST['product_data']['block_id'] : "");
//		return array(CONTROLLER_STATUS_OK, "products$suffix");
//	}
//return;
//	
//}



//if ($mode == 'update') {
//	
//	$selected_section = (empty($_REQUEST['selected_section']) ? 'detailed' : $_REQUEST['selected_section']);
//
//	// Get current product data
//	$product_data = fn_get_product_data($_REQUEST['product_id'], $auth, DESCR_SL, '', true, true, true, true);
//
//	if (!empty($_REQUEST['deleted_subscription_id'])) {
//		db_query("DELETE FROM ?:product_subscriptions WHERE subscription_id = ?i", $_REQUEST['deleted_subscription_id']);
//	}
//
//	if (empty($product_data)) {
//		return array(CONTROLLER_STATUS_NO_PAGE);
//	}
//
//	fn_add_breadcrumb(fn_get_lang_var('products'), "products.manage.reset_view");
//
//	fn_add_breadcrumb(fn_get_lang_var('search_results'), "products.manage.last_view");
//
//	fn_add_breadcrumb(fn_get_lang_var('category') . ': ' . fn_get_category_name($product_data['main_category']), "products.manage.reset_view?cid=$product_data[main_category]");
//
//	$taxes = fn_get_taxes();
//
//	arsort($product_data['category_ids']);
//	$allowed_payment_method = db_get_row("select * from product_payment_method where product_id='".$_REQUEST['product_id']."'");
//	$product_data['allowed_payment_method']=explode(',',$allowed_payment_method['payment_method']);
//	
//	$view->assign('product_data', $product_data);
//	$view->assign('taxes', $taxes);
//	$view->assign('companies', fn_get_short_companies());
//	
//} 


?>