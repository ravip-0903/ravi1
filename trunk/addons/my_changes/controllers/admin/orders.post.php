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

$params = $_REQUEST;

if ($mode == 'export_orders_list' && !empty($_REQUEST['order_ids'])) {
		if (!empty($_REQUEST['order_ids'])) {
			//$_SESSION['export_orders_list'] = '';
			if (empty($_SESSION['export_orders_list'])) {
				$_SESSION['export_orders_list'] = array();
			}

			if (empty($_SESSION['export_orders_list']['orders'])) {
				$_SESSION['export_orders_list']['orders'] = array('pattern_id' => 'orders');
			}
			
			$_SESSION['export_orders_list']['orders']['data'] = array('order_id' => $_REQUEST['order_ids']);

			unset($_REQUEST['redirect_url']);
			return array(CONTROLLER_STATUS_REDIRECT, "export_orders.export_orders_list?pattern_id=" . $_SESSION['export_orders_list']['orders']['pattern_id']);
		}
}
elseif ($mode == 'print_shipping_label') { 
	if (!empty($_REQUEST['order_id'])) {
		$order_info = fn_get_order_info($_REQUEST['order_id']);
		if (empty($order_info)) {
			return array(CONTROLLER_STATUS_NO_PAGE);
		}

		fn_translate_products($order_info['items'], '', CART_LANGUAGE, true);			
		
		$view_mail->assign('order_info', $order_info);
		$view_mail->assign('order_status', fn_get_status_data($order_info['status'], STATUSES_ORDER, $order_info['order_id'], CART_LANGUAGE));
		$view_mail->assign('payment_method', fn_get_payment_data((!empty($order_info['payment_method']['payment_id']) ? $order_info['payment_method']['payment_id'] : 0), $order_info['order_id'], CART_LANGUAGE));		
	  	$view_mail->assign('status_settings', fn_get_status_params($order_info['status']));
		$view_mail->assign('profile_fields', fn_get_profile_fields('I'));
	  	 
		if (PRODUCT_TYPE == 'MULTIVENDOR') {
			$view_mail->assign('take_surcharge_from_vendor', fn_take_payment_surcharge_from_vendor($order_info['items']));
		}

		/*modified by clues dev to save barcode to file*/
		
		require_once(DIR_ADDONS . 'barcode/lib/barcodegenerator/barcode.php');
		require_once(DIR_ADDONS . 'barcode/lib/barcodegenerator/c128bobject.php');
		if(!file_exists($_REQUEST['order_id'].'.png')){
			generate_image($_REQUEST['order_id']);
		}
		
		
		
		if (!empty($_REQUEST['format']) && $_REQUEST['format'] == 'pdf') {
			fn_html_to_pdf($view_mail->display('orders/print_shipping_label.tpl', false), fn_get_lang_var('Shipping Label') . '-' . $_REQUEST['order_id']);
		} else {
			$view_mail->display('orders/print_shipping_label.tpl');
		}

		exit;
	}

}
elseif ($mode == 'pdf_shipping_label') { 
	if (!empty($_REQUEST['order_id'])) {
		$order_info = fn_get_order_info($_REQUEST['order_id']);
		if (empty($order_info)) {
			return array(CONTROLLER_STATUS_NO_PAGE);
		}
		$RTOFLAG = 0;
		if(isset($_GET['rtoflag']))
		{
			$RTOFLAG = 1;
		}
		$view_mail->assign('rtoflag', $RTOFLAG);
		// Added by Sudhir to show carrier code at print order pdf
		$query = "SELECT noc.carrier_id, c.carrier_code 
			  FROM clues_nss_order_carrier noc 
			  INNER JOIN clues_carriers c
			  ON noc.carrier_id = c.carrier_id
			  WHERE noc.order_id = '".$order_info['order_id']."'";
			  
		$carrier = db_get_array($query);
		$view_mail->assign('carrier', $carrier);


		// Added by Sudhir end here
		
		fn_translate_products($order_info['items'], '', CART_LANGUAGE, true);			
		
		$view_mail->assign('order_info', $order_info);
		$view_mail->assign('order_status', fn_get_status_data($order_info['status'], STATUSES_ORDER, $order_info['order_id'], CART_LANGUAGE));
		$view_mail->assign('payment_method', fn_get_payment_data((!empty($order_info['payment_method']['payment_id']) ? $order_info['payment_method']['payment_id'] : 0), $order_info['order_id'], CART_LANGUAGE));
	  	$view_mail->assign('status_settings', fn_get_status_params($order_info['status']));
		$view_mail->assign('profile_fields', fn_get_profile_fields('I'));
	  	 
		if (PRODUCT_TYPE == 'MULTIVENDOR') {
			$view_mail->assign('take_surcharge_from_vendor', fn_take_payment_surcharge_from_vendor($order_info['items']));
		}
		require_once(DIR_ADDONS . 'barcode/lib/barcodegenerator/barcode.php');
		require_once(DIR_ADDONS . 'barcode/lib/barcodegenerator/c128bobject.php');
		if(!file_exists($_REQUEST['order_id'].'.png')){
			generate_image($_REQUEST['order_id']);
		}
		if (!empty($_REQUEST['format']) && $_REQUEST['format'] == 'pdf') {
			fn_html_to_pdf($view_mail->display('orders/pdf_shipping_label.tpl', false), fn_get_lang_var('Shipping Label') . '-' . $_REQUEST['order_id']);
		} else {
			$view_mail->display('orders/pdf_shipping_label.tpl');
		}

		exit;
	}

}
elseif ($mode == 'bulk_shipping_label' && !empty($_REQUEST['order_ids'])) {
		$view_mail->assign('order_status_descr', fn_get_statuses(STATUSES_ORDER, true, true, true));
		require_once(DIR_ADDONS . 'barcode/lib/barcodegenerator/barcode.php');
		require_once(DIR_ADDONS . 'barcode/lib/barcodegenerator/c128bobject.php');
		$html = array();
		foreach ($_REQUEST['order_ids'] as $k => $v) {
			$order_info = fn_get_order_info($v);
			$view_mail->assign('order_info', $order_info);
			$view_mail->assign('payment_method', fn_get_payment_data((!empty($order_info['payment_method']['payment_id']) ? $order_info['payment_method']['payment_id'] : 0), $order_info['order_id'], CART_LANGUAGE));
			$view_mail->assign('order_status', fn_get_status_data($order_info['status'], STATUSES_ORDER, $order_info['order_id'], CART_LANGUAGE));
			$view_mail->assign('status_settings', fn_get_status_params($order_info['status']));
			$view_mail->assign('profile_fields', fn_get_profile_fields('I'));
			if(!file_exists($v.'.png')){
				generate_image($v);
			}
			generate_image($v);
			//if (DISPATCH_EXTRA == 'pdf') {
				$html[] = $view_mail->display('orders/print_shipping_label.tpl', false);
			//} else {
			//	$view_mail->display('orders/print_shipping_label.tpl');
			//	if ($v != end($_REQUEST['order_ids'])) {
			//		echo "<div style='page-break-before: always;'>&nbsp;</div>";
			//	}
			//}
		}

		//if (DISPATCH_EXTRA == 'pdf') {
			//fn_html_to_pdf($html, fn_get_lang_var('shipping_label') . '-' . implode('-', $_REQUEST['order_ids']));
			fn_html_to_pdf($html, fn_get_lang_var('shipping_label'));
		//}

		exit;
}
elseif ($mode == 'bulk_shipping_label_thermal' && !empty($_REQUEST['order_ids'])) {
		$view_mail->assign('order_status_descr', fn_get_statuses(STATUSES_ORDER, true, true, true));
		require_once(DIR_ADDONS . 'barcode/lib/barcodegenerator/barcode.php');
		require_once(DIR_ADDONS . 'barcode/lib/barcodegenerator/c128bobject.php');
		$html = array();
		foreach ($_REQUEST['order_ids'] as $k => $v) {
			$order_info = fn_get_order_info($v);
			
		// Added by Sudhir to show carrier code at print order pdf
		$query = "SELECT noc.carrier_id, c.carrier_code 
			  FROM clues_nss_order_carrier noc 
			  INNER JOIN clues_carriers c
			  ON noc.carrier_id = c.carrier_id
			  WHERE noc.order_id = '".$order_info['order_id']."'";
			  
		$carrier = db_get_array($query);
		$view_mail->assign('carrier', $carrier);

		// Added by Sudhir end here
			
			$view_mail->assign('order_info', $order_info);
			$view_mail->assign('payment_method', fn_get_payment_data((!empty($order_info['payment_method']['payment_id']) ? $order_info['payment_method']['payment_id'] : 0), $order_info['order_id'], CART_LANGUAGE));
			$view_mail->assign('order_status', fn_get_status_data($order_info['status'], STATUSES_ORDER, $order_info['order_id'], CART_LANGUAGE));
			$view_mail->assign('status_settings', fn_get_status_params($order_info['status']));
			$view_mail->assign('profile_fields', fn_get_profile_fields('I'));
			if(!file_exists($v.'.png')){
				generate_image($v);
			}
			//if (DISPATCH_EXTRA == 'pdf') {
				$html[] = $view_mail->display('orders/pdf_shipping_label.tpl', false);
			//} else {
			//	$view_mail->display('orders/pdf_shipping_label.tpl');
			//	if ($v != end($_REQUEST['order_ids'])) {
			//		echo "<div style='page-break-before: always;'>&nbsp;</div>";
			//	}
			//}
		}

		//if (DISPATCH_EXTRA == 'pdf') {
			//fn_html_to_pdf($html, fn_get_lang_var('shipping_label_thermal') . '-' . implode('-', $_REQUEST['order_ids']));
			fn_html_to_pdf($html, fn_get_lang_var('shipping_label_thermal'));
		//}

		exit;
}
elseif($mode == 'export_order_details' && !empty($_REQUEST['order_ids'])) {
		if (!empty($_REQUEST['order_ids'])) {
			if (empty($_SESSION['export_ranges'])) {
				$_SESSION['export_ranges'] = array();
			}

			if (empty($_SESSION['export_ranges']['orders'])) {
				$_SESSION['export_ranges']['orders'] = array('pattern_id' => 'orders');
			}

			$_SESSION['export_ranges']['orders']['data'] = array('order_id' => $_REQUEST['order_ids']);

			unset($_REQUEST['redirect_url']);
			return array(CONTROLLER_STATUS_REDIRECT, "export_order_details.export_order_details&pattern_id=" . $_SESSION['export_ranges']['orders']['pattern_id']);
		}
	}

?>
