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
// $Id: orders.php 12865 2011-07-05 06:57:22Z 2tl $
//
if ( !defined('AREA') )	{ die('Access denied');	}
if (!empty($_REQUEST['order_id']) && $mode != 'search') {
	// If user is not logged in and trying to see the order, redirect him to login form
	if (empty($auth['user_id']) && empty($auth['order_ids'])) {
		return array(CONTROLLER_STATUS_REDIRECT, "auth.login_form?return_url=" . urlencode(Registry::get('config.current_url')));
	}

	if (!empty($auth['user_id'])) {
		$allowed_id = db_get_field("SELECT user_id FROM ?:orders WHERE user_id = ?i AND order_id = ?i", $auth['user_id'], $_REQUEST['order_id']);

	} elseif (!empty($auth['order_ids'])) {
		$allowed_id = in_array($_REQUEST['order_id'], $auth['order_ids']);
	}

	// Check order status (incompleted order)
	if (!empty($allowed_id)) {
		$status = db_get_field('SELECT status FROM ?:orders WHERE order_id = ?i', $_REQUEST['order_id']);
		if ($status == STATUS_INCOMPLETED_ORDER) {
			//$allowed_id = 0;
			$allowed_id = 1;//modified by clues dev to show incomplete orders details to customer
		}
	}

	fn_set_hook('is_order_allowed', $_REQUEST['order_id'], $allowed_id);

	if (empty($allowed_id)) {// Access denied
            fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('this_order_not_exist'));
            return array(CONTROLLER_STATUS_OK, "profiles.myaccount");
            
	}
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && (isset($_SESSION['form_token_value']) && $_REQUEST['token'] != $_SESSION['form_token_value']) && !isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('form_token_not_matched'));
    return array(CONTROLLER_STATUS_OK, $_SERVER['HTTP_REFERER']);
}else{
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
        $token = md5(Registry::get('config.http_host').Registry::get('config.session_salt').time());
        $_SESSION['form_token_value'] = $token;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if ($mode == 'repay') {
		$view->assign('order_action', fn_get_lang_var('processing'));
		$view->display('views/orders/components/placing_order.tpl');
		fn_flush();

		$order_info = fn_get_order_info($_REQUEST['order_id']);

		$payment_info = empty($_REQUEST['payment_info']) ? array() : $_REQUEST['payment_info'];

		// Save payment information
		if (!empty($payment_info)) {
			$ccards = fn_get_static_data_section('C', true);
			if (!empty($payment_info['card']) && !empty($ccards[$payment_info['card']])) {
				// Check if cvv2 number required and unset it if not
				if ($ccards[$payment_info['card']]['param_2'] != 'Y') {
					unset($payment_info['cvv2']);
				}
				// Check if start date exists and required and convert it to string
				if ($ccards[$payment_info['card']]['param_3'] != 'Y') {
					unset($payment_info['start_month'], $payment_info['start_year']);
				}
				// Check if issue number required
				if ($ccards[$payment_info['card']]['param_4'] != 'Y') {
					unset($payment_info['issue_number']);
				}
			}

			$_data = array (
				'order_id' => $_REQUEST['order_id'],
				'type' => 'P', //payment information
				'data' => fn_encrypt_text(serialize($payment_info)),
			);

			db_query("REPLACE INTO ?:order_data ?e", $_data);
		} else {
			db_query("DELETE FROM ?:order_data WHERE type = 'P' AND order_id = ?i", $_REQUEST['order_id']);
		}

		// Change payment method
		$update_order['payment_id'] = $_REQUEST['payment_id'];
		$update_order['repaid'] = ++ $order_info['repaid'];

		// Add new customer notes
		if (!empty($_REQUEST['customer_notes'])) {
			$update_order['notes'] = (!empty($order_info['notes']) ? $order_info['notes'] . "\n" : '') . $_REQUEST['customer_notes'];
		}

		// Update total and surcharge amount
		$payment = fn_get_payment_method_data($_REQUEST['payment_id']);
		if (!empty($payment['p_surcharge']) || !empty($payment['a_surcharge'])) {
			$surcharge_value = 0;
			if (floatval($payment['a_surcharge'])) {
				$surcharge_value += $payment['a_surcharge'];
			}
			if (floatval($payment['p_surcharge'])) {
				$surcharge_value += fn_format_price(($order_info['total'] - $order_info['payment_surcharge']) * $payment['p_surcharge'] / 100);
			}
			$update_order['payment_surcharge'] = $surcharge_value;
			if (PRODUCT_TYPE == 'MULTIVENDOR' && fn_take_payment_surcharge_from_vendor($order_info['items'])) {
				$update_order['total'] = fn_format_price($order_info['total']);
			} else {
				$update_order['total'] = fn_format_price($order_info['total'] - $order_info['payment_surcharge'] + $surcharge_value);
			}
		} else {
			if (PRODUCT_TYPE == 'MULTIVENDOR' && fn_take_payment_surcharge_from_vendor($order_info['items'])) {
				$update_order['total'] = fn_format_price($order_info['total']);
			} else {
				$update_order['total'] = fn_format_price($order_info['total'] - $order_info['payment_surcharge']);
			}
			$update_order['payment_surcharge'] = 0;
		}

		db_query('UPDATE ?:orders SET ?u WHERE order_id = ?i', $update_order, $_REQUEST['order_id']);

		// Change order status back to Open and restore amount.
		fn_change_order_status($order_info['order_id'], 'O', $order_info['status'], fn_get_notification_rules(array(), false));

		$_SESSION['cart']['placement_action'] = 'repay';

		// Process order (payment)
		fn_start_payment($order_info['order_id']);

		fn_order_placement_routines($order_info['order_id'], array(), true, 'repay');
	}
	else if($mode=='new_feedback_post')
	{		
			$ip_add=$_SERVER['REMOTE_ADDR'];
			$mer_status = 'A';
			$pro_status = 'A';
			$ip_bl_pro_status='A';
			$sql="select id from clues_restricted_phrase_ip where restrict_ip='".$ip_add."' and type='R'";
			
			$rest_check=db_get_field($sql);
			if(!empty($rest_check))
			{
				//fn_set_notification('N', '', fn_get_lang_var('ip_blocked'));
				$to=Registry::get('config.error_to_email_ids');
				$from="support@shopclues.com";
				$sub=fn_get_lang_var('review_post_error');
				$msg="<h1>Review Post Error Due To IP BLOCKED</h1>";
				$msg.="<br/><br/>Posted IP-". $ip_add;
				sendElasticEmail($to, $sub, " ", $msg, $from, fn_get_lang_var('review_mail_header'), '');
				$mer_status = 'D';
				$ip_bl_pro_status = 'D';
			}
			$mer_review=$_REQUEST['review_merchant'];
			$sql="select restrict_phrase from clues_restricted_phrase_ip where type='R'";
			$result=db_get_array($sql);
			
			$rest_ph_match=array();
			$rest_pro_review=array();
			$pro_rest_ph_match=array();
			foreach($result as $phrase)
			{
				if(stripos($mer_review,$phrase['restrict_phrase'])!==false)
				{
					$rest_ph_match[]=$phrase['restrict_phrase'];
				}
			}
			if(!empty($rest_ph_match))
			{
				$mer_status = 'D';
			}
			$cnt = 0;
			$sum = 0;
			if($_REQUEST['shipping_time']>0)
			{
				$cnt++;
				$sum = $sum + $_REQUEST['shipping_time'];
			}
			if($_REQUEST['shipping_cost']>0)
			{
				$cnt++;
				$sum = $sum + $_REQUEST['shipping_cost'];
			}
			if($_REQUEST['product_quality']>0)
			{
				$cnt++;
				$sum = $sum + $_REQUEST['product_quality'];
			}
			if($_REQUEST['value_for_money']>0)
			{
				$cnt++;
				$sum = $sum + $_REQUEST['value_for_money'];
			}
			$avg_rate = 0;
			if($cnt>0)
			{
				$avg_rate = $sum/$cnt;
				
				$rating_info = db_get_row("SELECT count(id) as cnt,sum(avg_rate) as sm FROM clues_user_product_rating WHERE company_id='".  $_REQUEST['company_id'] ."' and avg_rate>0");
				$rate_overall = ($rating_info['cnt'] + $avg_rate)/($rating_info['cnt']+1);
				$sql = "update ?:companies set sdeep_rating='".$rate_overall."' where company_id='".$_REQUEST['company_id']."'";
				db_query($sql);
				$rating_info1['1'] = $_REQUEST['shipping_time'];
				$rating_info1['2'] = $_REQUEST['shipping_cost'];
				$rating_info1['3'] = $_REQUEST['product_quality'];
				$rating_info1['4'] = $_REQUEST['value_for_money'];
				$rating_info1['timestamp'] = time();
				db_query("UPDATE ?:orders SET rating_info=?s WHERE order_id=?i", serialize($rating_info1), $_REQUEST['order_id']);
			}
			foreach($_REQUEST['product_rating'] as $pro_id=>$value)
			{
				$pro_review=$value['review'];
				$rest_product=0;
				if($ip_bl_pro_status!='D')
				{
					foreach($result as $phrase)
					{
						if(stripos($pro_review,$phrase['restrict_phrase'])!==false)
						{
							$pro_rest_ph_match[]=$phrase['restrict_phrase'];
							$rest_product=1;
						}
					}
					if($rest_product==1)
					{
						$pro_status='D';
						$rest_pro_review[]=$pro_review;
					}
					else if($rest_product==0)
					{
						$pro_status='A';
					}
				}
				else
				{
					$pro_status='D';
				}
				
				$sql = "insert into clues_user_product_rating set product_id='".$pro_id."',
			      company_id='".$_REQUEST['company_id']."',
			      order_id='".$_REQUEST['order_id']."',
			      product_rating='".$value['rating_count']."',
			      score='".$_REQUEST['score']."',
			      review_title='',
				  video_url='',
			      review='".addslashes($value['review'])."',
			      shipping_time='".$_REQUEST['shipping_time']."',
			      shipping_cost='".$_REQUEST['shipping_cost']."',
			      product_quality='".$_REQUEST['product_quality']."',
			      value_for_money='".$_REQUEST['value_for_money']."',
				  review_merchant='".addslashes($_REQUEST['review_merchant'])."',
			      avg_rate='".$avg_rate."',for_merchant_status='".$mer_status."',for_product_status='".$pro_status."'";
				  db_query($sql);
				  
				 if(is_numeric($value['rating_count']) && is_numeric($pro_id) && $value['rating_count']>0) {
						// Check whether the product exists
						$product_id = db_get_field("SELECT product_id FROM ?:products WHERE product_id=?i", $pro_id);
						if($product_id) {
							$rating_info = db_get_field("SELECT sdeep_rating_info FROM ?:products WHERE product_id=?i", $product_id);
							$rating_info = @unserialize($rating_info);
							//echo '<pre>';print_r($_REQUEST);print_r($rating_info);die;
							if(!isset($rating_info['total_score'])) $rating_info['total_score'] = 0;
							if(!isset($rating_info['num_rates'])) $rating_info['num_rates'] = 0;
							$rating_info['total_score'] = ($value['rating_count'] + $rating_info['total_score']*$rating_info['num_rates'])/($rating_info['num_rates'] + 1);
							$rating = (int)($rating_info['total_score']*100);
							$rating_info['total_score'] = $rating / 100;
							$rating_info['num_rates'] = $rating_info['num_rates'] + 1;
							db_query("UPDATE ?:products SET sdeep_rating_info = ?s WHERE product_id = ?i", @serialize($rating_info), $pro_id);
				 }
			}
			}
			if(!empty($rest_ph_match) || !empty($pro_rest_ph_match))
			{
				$to=Registry::get('config.error_to_email_ids');
				$from="support@shopclues.com";
				$sub=fn_get_lang_var('feedback_post_error');
				$msg="<h1>".fn_get_lang_var('phrase_restricted')."</h1>";
				$msg.="Name-".$_REQUEST['user_name'];
			}
			if(!empty($rest_ph_match))
			{
				$msg.="<br/><br/>Posted Merchant Review-". $_REQUEST['review_merchant'];
				$msg.="<br/><br/>Restrcited Phrase Found-".implode(",",$rest_ph_match)." ".implode(",",$pro_rest_ph_match);
			}
			if(!empty($pro_rest_ph_match))
			{
				$msg.="<br/><br/>Posted Product Review-". implode(",",$rest_pro_review);
				$msg.="<br/><br/>Restrcited Phrase Found-".implode(",",$pro_rest_ph_match);
			}
			if(!empty($rest_ph_match) || !empty($pro_rest_ph_match))
			{
				sendElasticEmail($to, $sub, " ", $msg, $from, fn_get_lang_var('review_mail_header'), '');
			}
			
			fn_set_notification('N', '', 'Thanks for your feedback. ');
	  		return array(CONTROLLER_STATUS_OK, $_SERVER['HTTP_REFERER']);
	}
	else if($mode=='cancel_order') //write by ankur for the order cancelation from front end
	{ 
		
		if(!empty($auth['user_id']) && !empty($_REQUEST['orderid']))
		{
			$order_id=$_REQUEST['orderid'];
			$user_id=$auth['user_id'];
			$region=$_REQUEST['reasons'];
			$comment=$_REQUEST['comment'];
			$current_status=$_REQUEST['cur_status'];
			
			$order_data = db_get_row("select status, order_id from cscart_orders where order_id='".$order_id."' and user_id= '".$user_id."' ");
			$allow_cancell = db_get_field("select value from cscart_status_data where status='".$order_data['status']."' and param= 'allow_cancelation' ");
			
                        $sql = "select cancell_status from clues_order_cancellation_reason where reason_id=".$region;
                        $reason_status = db_get_field($sql);
                        //changes by ajay
                        if(!empty($order_data)){
							if($allow_cancell == 'Y'){
							db_query("insert into clues_customer_cancellation set order_id='".$order_id."',user_id='".$user_id."',reason_id='".$region."',comment='".addslashes($comment)."'");
											 
							fn_change_order_status($order_id,$reason_status,$current_status,$notify = array("C"=>false,"A"=>false,"S"=>false));
							}else{
								fn_set_notification('E','',fn_get_lang_var('cant_cancel_order'));
								return array(CONTROLLER_STATUS_OK, "orders.search");
						         }
                            }else{
								fn_set_notification('E','',fn_get_lang_var('user_id_not_match'));
								return array(CONTROLLER_STATUS_OK, "orders.search");
							}
                        //end changes by ajay
                        
			fn_set_notification('N',fn_get_lang_var('notice'),fn_get_lang_var('order_cancel'));
			return array(CONTROLLER_STATUS_OK,$_SERVER['HTTP_REFERER']);
			exit;
		}
	}


	return array(CONTROLLER_STATUS_OK, "orders.details?order_id=$_REQUEST[order_id]");
}

fn_add_breadcrumb(fn_get_lang_var('orders'), $mode == 'search' ? '' : "orders.search");

//
// Show invoice
//
if ($mode == 'invoice') {
	fn_add_breadcrumb(fn_get_lang_var('order') . ' #' . $_REQUEST['order_id'], "orders.details?order_id=$_REQUEST[order_id]");
	fn_add_breadcrumb(fn_get_lang_var('invoice'));

	$view->assign('order_info', fn_get_order_info($_REQUEST['order_id']));

//
// Show invoice on separate page
//
} elseif ($mode == 'print_invoice') {

	$order_info = fn_get_order_info($_REQUEST['order_id']);
	$view_mail->assign('order_info', $order_info);
	$view_mail->assign('order_status', fn_get_status_data($order_info['status'], STATUSES_ORDER, $order_info['order_id'], CART_LANGUAGE));
	$view_mail->assign('payment_method', fn_get_payment_data((!empty($order_info['payment_method']['payment_id']) ? $order_info['payment_method']['payment_id'] : 0), $order_info['order_id'], CART_LANGUAGE));
	$view_mail->assign('profile_fields', fn_get_profile_fields('I'));

	if (PRODUCT_TYPE == 'MULTIVENDOR') {
		$view_mail->assign('take_surcharge_from_vendor', fn_take_payment_surcharge_from_vendor($order_info['items']));
	}

	if (!empty($_REQUEST['format']) && $_REQUEST['format'] == 'pdf') {
		$view_mail->assign('index_script', Registry::get('config.current_location') . '/' . $index_script);
		fn_html_to_pdf($view_mail->display('orders/print_invoice.tpl', false), fn_get_lang_var('invoice') . '-' . $_REQUEST['order_id']);
	} else {
		$view_mail->display('orders/print_invoice.tpl');
	}
	exit;

//
// Track orders by ekey
//
} elseif ($mode == 'track') {
	if (!empty($_REQUEST['ekey'])) {
		$email = db_get_field("SELECT object_string FROM ?:ekeys WHERE object_type = 'T' AND ekey = ?s AND ttl > ?i", $_REQUEST['ekey'], TIME);

		// Cleanup keys
		db_query("DELETE FROM ?:ekeys WHERE object_type = 'T' AND ttl < ?i", TIME);

		if (empty($email)) {
			return array(CONTROLLER_STATUS_DENIED);
		}

		$auth['order_ids'] = db_get_fields("SELECT order_id FROM ?:orders WHERE email = ?s", $email);

		if (!empty($_REQUEST['o_id']) && in_array($_REQUEST['o_id'], $auth['order_ids'])) {
			return array(CONTROLLER_STATUS_REDIRECT, "orders.details?order_id=$_REQUEST[o_id]");
		} else {
			return array(CONTROLLER_STATUS_REDIRECT, "orders.search");
		}
	} else {
		return array(CONTROLLER_STATUS_DENIED);
	}

	exit;

//
// Request for order tracking
//
} elseif ($mode == 'track_request') {

	if (Registry::get('settings.Image_verification.use_for_track_orders') == 'Y' && fn_image_verification('track_orders', empty($_REQUEST['verification_answer']) ? '' : $_REQUEST['verification_answer']) == false) {
		$view->display('blocks/my_account.tpl');
		exit;
	}

	if (!empty($auth['user_id'])) {
		$allowed_id = db_get_field("SELECT user_id FROM ?:orders WHERE user_id = ?i AND order_id = ?i AND is_parent_order != 'Y'", $auth['user_id'], $_REQUEST['track_data']);

		if (!empty($allowed_id)) {
			$ajax->assign('force_redirection', 'orders.details?order_id=' . $_REQUEST['track_data']);
			exit;
		} else {
			fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('warning_track_orders_not_allowed'));
		}
	} else {
		$email = '';
		
		if (!empty($_REQUEST['track_data'])) {
			$o_id = 0;
			// If track by email
			if (strpos($_REQUEST['track_data'], '@') !== false) {
				$order_info = db_get_row("SELECT order_id, email, company_id, lang_code FROM ?:orders WHERE email = ?s ORDER BY timestamp DESC LIMIT 1", $_REQUEST['track_data']);
			// Assume that this is order number
			} else {
				$order_info = db_get_row("SELECT order_id, email, company_id, lang_code FROM ?:orders WHERE order_id = ?i", $_REQUEST['track_data']);
			}
		}

		if (!empty($order_info['email'])) {
			// Create access key
			$ekey_data = array (
				'object_string' => $order_info['email'],
				'object_type' => 'T',
				'ekey' => md5(uniqid(rand())),
				'ttl' => strtotime("+1 hour"), // FIXME!!! hardcoded
			);

			db_query("REPLACE INTO ?:ekeys ?e", $ekey_data);

			$view_mail->assign('access_key', $ekey_data['ekey']);
			$view_mail->assign('o_id', $order_info['order_id']);

   			$company_id = fn_get_company_id('orders', 'order_id', $order_info['order_id']);
			$company = fn_get_company_placement_info($company_id);
			Registry::get('view_mail')->assign('company_placement_info', $company);

			$result = fn_send_mail($order_info['email'], array('email' => $company['company_orders_department'], 'name' => $company['company_name']), 'orders/track_subj.tpl', 'orders/track.tpl', '', $order_info['lang_code']);
			if ($result) {
				fn_set_notification('N', fn_get_lang_var('notice'), fn_get_lang_var('text_track_instructions_sent'));
			}
		} else {
			fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('warning_track_orders_not_found'));
		}
	}
	$view->display('blocks/my_account.tpl');
	exit();

//
// Show order details
//
} elseif ($mode == 'details') {

	fn_add_breadcrumb(fn_get_lang_var('order_info'));

	$order_info = fn_get_order_info($_REQUEST['order_id']);
		
	if ($order_info['is_parent_order'] == 'Y') {
//changed query for parent id condtion on frontend  by sapna 
	$child_ids = db_get_array("SELECT od.item_id, o.order_id,od.product_id,company_id,status,od.product_code FROM ?:orders o left join cscart_order_details od on      o.order_id=od.order_id   WHERE o.parent_order_id = ?i", $_REQUEST['order_id']);
		//fn_redirect(INDEX_SCRIPT . "?dispatch[orders.search]=Search&period=A&order_id=" . implode(',', $child_ids));
        $view->assign('child_ids',$child_ids);
        
	}
        
	if (empty($order_info)) {
		return array(CONTROLLER_STATUS_NO_PAGE);
	}

	if (PRODUCT_TYPE == 'MULTIVENDOR') {
		$view->assign('take_surcharge_from_vendor', fn_take_payment_surcharge_from_vendor($order_info['items']));
	}
	// Repay functionality
	$statuses = fn_get_statuses(STATUSES_ORDER, false, true);

	if (Registry::get('settings.General.repay') == 'Y' && (!empty($statuses[$order_info['status']]['repay']) && $statuses[$order_info['status']]['repay'] == 'Y')) {
		fn_prepare_repay_data(empty($_REQUEST['payment_id']) ? 0 : $_REQUEST['payment_id'], $order_info, $auth, $view);
	}
	if (Registry::get('settings.General.use_shipments') == 'Y') {
	
		Registry::set('navigation.tabs', array (
			'general' => array(
				'title' => fn_get_lang_var('general'),
				'js' => true
			),
			'shipment_info' => array(
				'title' => fn_get_lang_var('shipment_info'),
				'js' => true
			)
		));
		
		$shipments = db_get_array('SELECT ?:shipments.shipment_id, ?:shipments.comments, ?:shipments.tracking_number, ?:shipping_descriptions.shipping AS shipping, ?:shipments.carrier FROM ?:shipments LEFT JOIN ?:shipment_items ON (?:shipments.shipment_id = ?:shipment_items.shipment_id) LEFT JOIN ?:shipping_descriptions ON (?:shipments.shipping_id = ?:shipping_descriptions.shipping_id) WHERE ?:shipment_items.order_id = ?i AND ?:shipping_descriptions.lang_code = ?s GROUP BY ?:shipments.shipment_id', $order_info['order_id'], DESCR_SL);

		if (!empty($shipments)) {
			foreach ($shipments as $id => $shipment) {
				$shipments[$id]['items']=db_get_array('SELECT item_id, amount FROM ?:shipment_items WHERE shipment_id=?i', $shipment['shipment_id']);
			}
		}
		/* added by Sudhir dt 27 aug 2012 to show tracking details at customer */
			$trackin = db_get_array("SELECT carrier_status, sc_status, from_location, to_location, status_update_date, memo, awbno, receiver_name, receiver_contact FROM clues_shipment_tracking_center WHERE order_id=".$order_info['order_id']." ORDER BY latest desc, date_of_creation desc");

		foreach($trackin as $tr=>$track){
			$tracking[$track['awbno']][]=$track;
		}
		$view->assign('tracking', $tracking);
		/* added by Sudhir dt 27 aug 2012 to show tracking details at customer end here */
		$view->assign('shipments', $shipments);
	}
        $order_info['pdd_edd'] = fn_get_pdd_edd($order_info['order_id']);
        $order_info['multiaddress_order_status'] = fn_get_multiaddress_order_status($order_info['order_id']);
        $view->assign('order_info', $order_info);
	$view->assign('status_settings', $statuses[$order_info['status']]);
        
        if(isset($order_info['is_parent_order'])&&$order_info['is_parent_order']=='N')
        {    
            fn_get_bazooka(array($order_info['order_id']),0);
        }    
//      
// Show Process
//
} elseif ($mode == 'process') {
        $text = str_replace(' ','+',urldecode($_REQUEST['x']));
        $key = fn_decrypt_text($text);
        $dataArray = explode('|',$key);
        $order_id = $dataArray[0];
        $action = $dataArray[1];
        $action_time = $dataArray[2];
        $reason_id = $dataArray[3];
        $seccode = $_REQUEST['a'];
        $existOrderId = db_get_row("SELECT user_id, status, email, CONCAT(b_firstname,' ',b_lastname) as user_name FROM cscart_orders WHERE order_id =".$order_id);
        if($seccode == md5(Registry::get('config.secret_key')."|".$existOrderId['email']."|".$order_id))
            {
                $user_id = $existOrderId['user_id'];
                $response = array();
                $response['username'] = $existOrderId['user_name']; 
                $response['order_id'] = $order_id; 
                switch($action)
                    {
                        case "grace":
                            if($action_time == 0)
                            {
                                return array(CONTROLLER_STATUS_REDIRECT, "auth.login_form?return_url=index.php?dispatch=orders.search");
                            }
                            else 
                            {
                                $checkorder = db_get_field("SELECT 1 FROM clues_customer_cancellation WHERE order_id =".$order_id);
                                if(empty($checkorder))
                                {
                                    $grace = db_get_field("SELECT grace_period FROM clues_orders_grace WHERE order_id =".$order_id);
                                    if($grace == 0)
                                    {
                                        $graceperiod = $action_time * 86400;
                                        $update_date = strtotime('now');
                                        $query = "UPDATE clues_orders_grace SET grace_period ='$graceperiod' ,promised_delivery_date= promised_delivery_date+$graceperiod, last_update= '$update_date' WHERE order_id =".$order_id;
                                        $sql_query = db_query($query);
                                        $response['action'] = 'grace';
                                        $response['action_time'] = $action_time;
                                    }
                                    else
                                    {
                                        $response['action'] = 'grace_error';
                                    }
                                }
                                else
                                {
                                    $response['action'] = 'grace_error';
                                }
                            }
                            break;
                        default:
                            break;

                    }                 
            
            }
            else
            {
                $response['action'] = 'error';
            }
            
            $view->assign('response', $response);

//
// Show Process
//
} elseif ($mode == 'process') {
        $text = str_replace(' ','+',urldecode($_REQUEST['x']));
        $key = fn_decrypt_text($text);
        $dataArray = explode('|',$key);
        $order_id = $dataArray[0];
        $action = $dataArray[1];
        $action_time = $dataArray[2];
        $reason_id = $dataArray[3];
        $seccode = $_REQUEST['a'];
        $existOrderId = db_get_row("SELECT user_id, status, email, CONCAT(b_firstname,' ',b_lastname) as user_name FROM cscart_orders WHERE order_id =".$order_id);
        if($seccode == md5(Registry::get('config.secret_key')."|".$existOrderId['email']."|".$order_id))
            {
                $user_id = $existOrderId['user_id'];
                $response = array();
                $response['username'] = $existOrderId['user_name']; 
                $response['order_id'] = $order_id; 
                switch($action)
                    {
                        case "grace":
                            if($action_time == 0)
                            {
                                return array(CONTROLLER_STATUS_REDIRECT, "auth.login_form?return_url=index.php?dispatch=orders.search");
                            }
                            else 
                            {
                                $checkorder = db_get_field("SELECT 1 FROM clues_customer_cancellation WHERE order_id =".$order_id);
                                if(empty($checkorder))
                                {
                                    $grace = db_get_field("SELECT grace_period FROM clues_orders_grace WHERE order_id =".$order_id);
                                    if($grace == 0)
                                    {
                                        $graceperiod = $action_time * 86400;
                                        $update_date = strtotime('now');
                                        $query = "UPDATE clues_orders_grace SET grace_period ='$graceperiod' ,promised_delivery_date= promised_delivery_date+$graceperiod, last_update= '$update_date' WHERE order_id =".$order_id;
                                        $sql_query = db_query($query);
                                        $response['action'] = 'grace';
                                        $response['action_time'] = $action_time;
                                    }
                                    else
                                    {
                                        $response['action'] = 'grace_error';
                                    }
                                }
                                else
                                {
                                    $response['action'] = 'grace_error';
                                }
                            }
                            break;
                        default:
                            break;

                    }                 
            
            }
            else
            {
                $response['action'] = 'error';
            }
            
            $view->assign('response', $response);

//
// Ajax Grace process
//
} elseif($mode == 'ajaxprocess') { 
  
    if($_GET['orderid'] && $_GET['userid'] && $_GET['grace'])
    {
        $order_id = $_GET['orderid'];
        $user_id = $_GET['userid'];
        $action_time = $_GET['grace'];
        $checkordergrace = db_get_field("SELECT grace_period FROM clues_orders_grace WHERE order_id =".$order_id);
        if($checkordergrace == 0)
        {
            $graceperiod = $action_time * 86400;
            $update_date = strtotime('now');
            $query = "UPDATE clues_orders_grace SET grace_period ='$graceperiod' ,promised_delivery_date= promised_delivery_date+$graceperiod, last_update= '$update_date' WHERE order_id =".$order_id;
            $sql_query = db_query($query);
            echo "success";
        }
        else {
            echo "error";
         }
    }
    else
    {
        echo "error";
    }
    exit;
//
// Search orders
//
} elseif ($mode == 'search') {

	$params = $_REQUEST;
	if (!empty($auth['user_id'])) {
		$params['user_id'] = $auth['user_id'];

	} elseif (!empty($auth['order_ids'])) {
		if (empty($params['order_id'])) {
			$params['order_id'] = $auth['order_ids'];
		} else {
			$ord_ids = is_array($params['order_id']) ? $params['order_id'] : explode(',', $params['order_id']);
			$params['order_id'] = array_intersect($ord_ids, $auth['order_ids']);
		}

	} else {
		return array(CONTROLLER_STATUS_REDIRECT, "auth.login_form?return_url=" . urlencode(Registry::get('config.current_url')));
	}

	list($orders, $search) = fn_get_orders($params, Registry::get('settings.Appearance.orders_per_page'));

	foreach($orders as $key=>$order_detail)
	{
		$order_info[]=fn_get_order_info($order_detail['order_id']);

		//code by ajay for show priority icon
		$ff_priority = db_get_row(" SELECT `occasion_id`, `priority_level_id` FROM `cscart_order_details` WHERE `order_id` = '".$order_detail['order_id']."'");
    
                  if ( !empty($ff_priority['occasion_id']) && !empty($ff_priority['priority_level_id']) ){
				  $orders[$key]['ff_priority']= 'Y';
			      }else{
				  $orders[$key]['ff_priority']= 'N';	  
				  }
				  
	   $priority_level_name = db_get_row(" SELECT `priority_level_id`, `priority_level_name`, `icon_url` , `color_code` FROM `clues_priority_levels_for_fulfillment`
                                          WHERE `priority_level_id` = '".$ff_priority['priority_level_id']."' ");	
       $orders[$key]['priority_level_name'] = $priority_level_name;		  		  	  
	   //end	
	    		  
		//code by ajay for prevent cancellataion for RMA orders
			$main_order_id = db_get_field("select main_order_id from clues_order_clone_rel where clone_order_id='".$order_detail['order_id']."'");
			if (!empty($main_order_id))
			  {
			    $return_order = db_get_field("select return_id from cscart_rma_returns where order_id='".$main_order_id."'");
			    
				  if (!empty($return_order)){
				  $orders[$key]['allow_cancelation']= 'N';
			      }
			  }
		//End code by ajay
              $orders[$key]['pdd_edd'] = fn_get_pdd_edd($order_detail['order_id']);
                          
        }

	$orders_item=array();
	if(!empty($order_info))
	{   
		foreach($order_info as $order_detail)
		{
			$orders_item[]=$order_detail['items'];
			
			//code by ankur to check for return request
			if($order_detail['allow_return']=='Y')
			{
				$return_order[$order_detail['order_id']]='Y';
			}
			if(isset($order_detail['order_return_status']) && $order_detail['order_return_status']=='N')
			{
				$return_order[$order_detail['order_id']]='N';
			}
			elseif(isset($order_detail['order_return_status']) && $order_detail['order_return_status']=='E')
			{
				$return_order[$order_detail['order_id']]='E';
			}
			//code end
	
		}
	}
        // code by munish start on 5 nov 2013
       $grace = array(); 
       for($i=0;$i<count($orders);$i++)
       {
          
           $checkordergrace = db_get_field("SELECT grace_period FROM clues_orders_grace WHERE order_id =".$orders[$i]['order_id']);
           if($checkordergrace == 0)
            {
                $grace[$orders[$i]['order_id']] = 'Y';
            }
            else {
                $grace[$orders[$i]['order_id']] = 'N';
            }
       }
       // code by munish end on 5 nov 2013
	//echo"<pre>";print_r($orders_item);die;
	$view->assign('orders', $orders);
	$view->assign('grace', $grace);
        $view->assign('search', $search);
	$view->assign('orders_item',$orders_item);
	$view->assign('return_orders',$return_order);

//
// Reorder order
//
} elseif ($mode == 'reorder') {

	fn_reorder($_REQUEST['order_id'], $_SESSION['cart'], $auth);

	return array(CONTROLLER_STATUS_REDIRECT, "checkout.cart");

} elseif ($mode == 'downloads') {

	if (empty($auth['user_id']) && empty($auth['order_ids'])) {
		return array(CONTROLLER_STATUS_REDIRECT, $index_script);
	}

	fn_add_breadcrumb(fn_get_lang_var('downloads'));

	$view->assign('products', fn_get_user_edp($auth['user_id'], empty($auth['user_id']) ? $auth['order_ids'] : 0, empty($_REQUEST['page']) ? 1 : $_REQUEST['page']));

} elseif ($mode == 'order_downloads') {

	if (empty($auth['user_id']) && empty($auth['order_ids'])) {
		return array(CONTROLLER_STATUS_REDIRECT, $index_script);
	}

	if (!empty($_REQUEST['order_id'])) {
		if (empty($auth['user_id']) && !in_array($_REQUEST['order_id'], $auth['order_ids'])) {
			return array(CONTROLLER_STATUS_DENIED);
		}
		$order = db_get_row("SELECT user_id, order_id FROM ?:orders WHERE ?:orders.order_id = ?i AND is_parent_order != 'Y'", $_REQUEST['order_id']);

		if (empty($order) && fn_is_empty($order)) {
			return array(CONTROLLER_STATUS_NO_PAGE);
		}

		fn_add_breadcrumb(fn_get_lang_var('order') . ' #' . $_REQUEST['order_id'], "orders.details?order_id=" . $_REQUEST['order_id']);
		fn_add_breadcrumb(fn_get_lang_var('downloads'));

		$view->assign('products', fn_get_user_edp($order['user_id'], $_REQUEST['order_id']));
	} else {
		return array(CONTROLLER_STATUS_NO_PAGE);
	}

} elseif ($mode == 'get_file') {

	$field = empty($_REQUEST['preview']) ? 'file_path' : 'preview_path';
	
	if (($field == 'file_path' && !empty($_REQUEST['ekey']) || $field == 'preview_path')) {

		if (!empty($_REQUEST['ekey'])) {

			$ekey_info = fn_get_product_edp_info($_REQUEST['product_id'], $_REQUEST['ekey']);

			if (empty($ekey_info) || $ekey_info['file_id'] != @$_REQUEST['file_id']) {
				return array(CONTROLLER_STATUS_DENIED);
			}

			// Increase downloads for this file
			$max_downloads = db_get_field("SELECT max_downloads FROM ?:product_files WHERE file_id = ?i", $_REQUEST['file_id']);
			$file_downloads = db_get_field("SELECT downloads FROM ?:product_file_ekeys WHERE ekey = ?s AND file_id = ?i", $_REQUEST['ekey'], $_REQUEST['file_id']);

			if (!empty($max_downloads)) {
				if ($file_downloads >= $max_downloads) {
					return array(CONTROLLER_STATUS_DENIED);
				}
			}
			db_query('UPDATE ?:product_file_ekeys SET ?u WHERE file_id = ?i AND product_id = ?i AND order_id = ?i', array('downloads' => $file_downloads + 1), $_REQUEST['file_id'], $ekey_info['product_id'], $ekey_info['order_id']);
		}

		$file = db_get_row("SELECT $field, file_name, product_id FROM ?:product_files LEFT JOIN ?:product_file_descriptions ON ?:product_file_descriptions.file_id = ?:product_files.file_id AND ?:product_file_descriptions.lang_code = ?s WHERE ?:product_files.file_id = ?i", CART_LANGUAGE, $_REQUEST['file_id']);
		if (!empty($file)) {
			fn_get_file(DIR_DOWNLOADS . $file['product_id'] . '/' . $file[$field]);
		}
	}

	return array(CONTROLLER_STATUS_DENIED);

//
// Display list of files for downloadable product
//
} elseif ($mode == 'download') {
	if (!empty($_REQUEST['ekey'])) {

		$ekey_info = fn_get_product_edp_info($_REQUEST['product_id'], $_REQUEST['ekey']);

		if (empty($ekey_info)) {
			return array(CONTROLLER_STATUS_DENIED);
		}

		$product = array(
			'ekey' => $_REQUEST['ekey'],
			'product_id' => $ekey_info['product_id'],
		);

		if (!empty($product['product_id'])) {
			$product['product'] = db_get_field("SELECT product FROM ?:product_descriptions WHERE product_id = ?i AND lang_code = ?s", $product['product_id'], CART_LANGUAGE);
			$product['files'] = fn_get_product_files($product['product_id'], false, $ekey_info['order_id']);
		}
	}

	if (!empty($auth['user_id'])) {
		fn_add_breadcrumb(fn_get_lang_var('downloads'), "profiles.downloads");
	}

	fn_add_breadcrumb($product['product'], "products.view?product_id=$product[product_id]");
	fn_add_breadcrumb(fn_get_lang_var('download'));

	if (!empty($product['files'])) {
		$view->assign('product', $product);
	} else {
		return array(CONTROLLER_STATUS_DENIED);
	}
	
} elseif ($mode == 'get_custom_file') { ////// Edited By SUdhir dt 08 June 2012
	$filename = !empty($_REQUEST['filename']) ? $_REQUEST['filename'] : '';
	
	if (!empty($_REQUEST['file']) && !empty($_REQUEST['order_id'])) {
		$file_path = Registry::get('config.ftp_host_image') .'/images/custom_files/order_data/'.$_REQUEST['order_id'].'/'.basename($_REQUEST['file']);
		//fn_get_file($file_path, $filename);
		$get_file = true;
	} elseif (!empty($_REQUEST['file'])) {
		$file_path = Registry::get('config.ftp_host_image') .'/images/custom_files/sess_data/'.basename($_REQUEST['file']);
		//fn_get_file($file_path, $filename);
		$get_file = true;
	}

	if ($get_file) {
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		readfile($file_path);
		exit;
	}
} ////////////edit by sudhir end here
else if($mode=='show_feedback_form')
{
	if($_REQUEST['order_id'])
	{
		$msg =$view->display('views/orders/components/feedback_form_popup.tpl', false);
		fn_set_notification('P', fn_get_lang_var('post_feedback_heading'), $msg, 'I');
		exit;
	}
	
	//$view->assign('order_info', $order_info);
	
}
else if($mode=='get_cancel_content')
{
	if(!empty($_REQUEST['order_id']))
	{
		//$msg="ANKUR";
		$msg =$view->display('views/orders/components/order_cancel_popup.tpl', false);
		fn_set_notification('P', fn_get_lang_var('order_cancel_heading'), $msg, 'I');
		exit;
	}
}


function fn_reorder($order_id, &$cart, &$auth)
{
	$order_info = fn_get_order_info($order_id, false, false, false, true);
	fn_set_hook('reorder', $order_info, $cart, $auth);
    unset($order_info['returns_info']);
    
	foreach ($order_info['items'] as $k => $item) {
		// refresh company id
		$company_id = db_get_field("SELECT company_id FROM ?:products WHERE product_id = ?i", $item['product_id']);
		$order_info['items'][$k]['company_id'] = $company_id;
                
        $is_cod = db_get_field("SELECT is_cod FROM ?:products WHERE product_id = ?i", $item['product_id']);
		$order_info['items'][$k]['is_cod'] = $is_cod;
		
		unset($order_info['items'][$k]['extra']['ekey_info']);
		unset($order_info['items'][$k]['extra']['returns']);
		
		$order_info['items'][$k]['product_options'] = empty($order_info['items'][$k]['extra']['product_options']) ? array() : $order_info['items'][$k]['extra']['product_options'];
	}
	
	if (!empty($cart) && !empty($cart['products'])) {
		$cart['products'] = fn_array_merge($cart['products'], $order_info['items']);
	} else {
		$cart['products'] = $order_info['items'];
	}

	foreach ($cart['products'] as $k => $v) {
		$_is_edp = db_get_field("SELECT is_edp FROM ?:products WHERE product_id = ?i", $v['product_id']);
		if ($amount = fn_check_amount_in_stock($v['product_id'], $v['amount'], $v['product_options'], $k, $_is_edp, 0, $cart)) {
			$cart['products'][$k]['amount'] = $amount;
			
			// Change the path of custom files
			if (!empty($v['extra']['custom_files'])) {
				$sess_dir_path = DIR_CUSTOM_FILES . 'sess_data';
				
				foreach ($v['extra']['custom_files'] as $option_id => $_data) {
					if (!empty($_data)) {
						foreach ($_data as $file_id => $file) {
							$cart['products'][$k]['extra']['custom_files'][$option_id][$file_id]['path'] = $sess_dir_path . '/' . basename($file['path']);
						}
					}
				}
			}
		} else {
			unset($cart['products'][$k]);
		}
	}
	
	// Restore custom files for editing
	$dir_path = DIR_CUSTOM_FILES . 'order_data/' . $order_id;
	
	if (is_dir($dir_path)) {
		fn_mkdir(DIR_CUSTOM_FILES . 'sess_data');
		fn_copy($dir_path, DIR_CUSTOM_FILES . 'sess_data');
	}

	// Redirect customer to step three after reordering
	$cart['payment_updated'] = true;

	fn_save_cart_content($cart, $auth['user_id']);
}

function fn_prepare_repay_data($payment_id, $order_info, $auth, &$templater)
{
	//Get payment methods
	$payment_methods = fn_get_payment_methods($auth);
	if (!empty($payment_methods)) {
		// Get payment method info
		if (!empty($payment_id)) {
			$order_payment_id = $payment_id;
		} else {
			$first = reset($payment_methods);
			$order_payment_id = $first['payment_id'];
		}

		$payment_data = fn_get_payment_method_data($order_payment_id);
		$payment_data['surcharge_value'] = 0;

		if (floatval($payment_data['a_surcharge'])) {
			$payment_data['surcharge_value'] += $payment_data['a_surcharge'];
		}

		if (floatval($payment_data['p_surcharge'])) {
			if (PRODUCT_TYPE == 'MULTIVENDOR' && fn_take_payment_surcharge_from_vendor($order_info['items'])) {
				$payment_data['surcharge_value'] += fn_format_price($order_info['total']);
			} else {
				$payment_data['surcharge_value'] += fn_format_price(($order_info['total'] - $order_info['payment_surcharge']) * $payment_data['p_surcharge'] / 100);
			}
		}

		$templater->assign('payment_methods', $payment_methods);
		$templater->assign('credit_cards', fn_get_static_data_section('C', true));
		$templater->assign('order_payment_id', $order_payment_id);
		$templater->assign('payment_method', $payment_data);
	}
}
function fn_get_purchased_gift_certificates($order_id)
{
	$res=db_get_row("select * from cscart_order_data where order_id='".$order_id."' and type='B'");
	return unserialize($res['data']);
}
function fn_get_order_feedback_info($order_id)
{
	$sql="select p.product_id,p.company_id,pd.product
	from cscart_order_details cod
	inner join cscart_products p on p.product_id=cod.product_id
	inner join cscart_product_descriptions pd on p.product_id=pd.product_id
	left join clues_user_product_rating cupr on cupr.order_id=cod.order_id and cupr.product_id=cod.product_id
	where cod.order_id=$order_id and cupr.product_id is null
	";
	return db_get_array($sql);
}


function fn_get_order_cancel_info($order_id)
{   
	$sql="select o.order_id,o.timestamp,pd.product_id,pd.product,od.amount,csd.status,csd.customer_facing_name as description
	      from cscart_orders o
		  inner join cscart_order_details od on o.order_id=od.order_id
		  inner join cscart_product_descriptions pd on od.product_id=pd.product_id
		  inner join cscart_status_descriptions csd on csd.status=o.status and csd.type='O'
		  where o.order_id='".$order_id."'
	";
	$res=db_get_array($sql);
	foreach($res as $result)
	{   
		$order_detail['order_id']=$result['order_id'];
		$order_detail['status_code']=$result['status'];
		$order_detail['status']=$result['description'];
		$order_detail['order_date']=$result['timestamp'];
		$order_detail['products'][$result['product_id']]['product']=$result['product'];
		$order_detail['products'][$result['product_id']]['amount']=$result['amount'];
		
		$now = time(); // or your date as well
		$your_date = $result['timestamp'];
		$datediff = $now - $your_date;
		$order_detail['age']= floor($datediff/(60*60*24));
		
	}
	return $order_detail;
	
	
}
function fn_get_cancel_reason($type, $age)
{
	$sql="select reason_id,reason from clues_order_cancellation_reason where type='".$type."' and order_days <='".$age."' order by position";
	return db_get_array($sql);
}
function fn_get_user_saving($user_id)
{ 
 $user_id = intval($user_id);
 if(!empty($user_id) && $user_id !== 0 && $user_id !== '0')
 {
    $dead_orders = Registry::get('config.dead_orders');
    $status_cond = "'".implode("','",$dead_orders)."'";
    $condition = " AND status NOT IN (".$status_cond.")";
    $saving= db_get_row("select (sum(subtotal_discount)+ sum(discount)) as 'user_saved' FROM  `cscart_orders` where user_id='".$user_id."'  $condition");
    return $saving;
}
 else 
  return null; 
}
function fn_get_return_status($order_id)
{
	$return_status=db_get_row("SELECT status FROM `cscart_orders` o
where o.status IN(select status from clues_status_types where status_group='R' and object_type='O')and order_id='".$order_id."'");
	 return $return_status;
	
}

function fn_get_return_id($order_id)
{
	$return_id=db_get_field("select extra from cscart_order_details where order_id='".$order_id."'");
	$sql=unserialize($return_id);
	return $sql;
	
	}
function fn_get_merchant_name($m_id)
{
$merchant_name=db_get_field("select company from cscart_companies where company_id='".$m_id."'");
return $merchant_name;
}
function fn_get_multiaddress_order_status($order_id)
{
    $check = db_get_field("SELECT 1 FROM cscart_order_data WHERE order_id =".$order_id." AND type='M'");
    if(empty($check))
        return 'N';
    else
        return 'Y';
}
?>
