<?php

/* * *************************************************************************
 *                                                                          *
 *   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
 *                                                                          *
 * This  is  commercial  software,  only  users  who have purchased a valid *
 * license  and  accept  to the terms of the  License Agreement can install *
 * and use this program.                                                    *
 *                                                                          *
 * ***************************************************************************
 * PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
 * "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
 * ************************************************************************** */


if (!defined('AREA')) {
    die('Access denied');
}

fn_define('GOOGLE_ORDER_DATA', 'O');
//generating the menu for issues and the subissues by ajay
$issues = select_parent_issues();

$view->assign('parent_issues', $issues);
//code by ajay ends here

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $suffix = '';
    if ($mode == 'bulk_shipping_label_printed') {
        $orders = $_REQUEST['order_ids'];
        $str_note = "";
        foreach ($orders as $id) {
            $sql = "update ?:orders set label_printed='y' where order_id='" . $id . "'";
            fn_change_order_status($id, 'G', '');
            db_query($sql);
            if ($str_note == '') {
                $str_note = "Orders: " . $id;
            } else {
                $str_note = $str_note . ", " . $id;
            }
        }
        fn_set_notification('N', fn_get_lang_var('notice'), $str_note . " have been marked as shipping label printed.");
    }
    if ($mode == 'delete_orders' && !empty($_REQUEST['order_ids'])) {
        foreach ($_REQUEST['order_ids'] as $v) {
            fn_delete_order($v);
        }
    }
    /* Modified by chandan to edit gift message */
    if ($mode == 'edit_gift_message') {
        $gift_to = $_REQUEST['gift_to'];
        $gift_from = $_REQUEST['gift_from'];
        $gift_message = $_REQUEST['gift_message'];
        $order_id = $_REQUEST['order_id'];
        $sql = "update clues_gift_message set gift_to = '" . addslashes($gift_to) . "', gift_from = '" . addslashes($gift_from) . "', message = '" . addslashes($gift_message) . "' where order_id = '" . $order_id . "'";
        db_query($sql);
        if ($_REQUEST['modd'] != 'orders_details') {
            $suffix = ".details?order_id=$_REQUEST[order_id]";
        } else {
            $suffix = ".orders_details?order_id=$_REQUEST[order_id]";
        }
    }
    /* Modified by chandan to edit gift message */

    /* Added by ajay to refund and Customer queries */

    if ($mode == 'create_queries') {
        $cust_q_order_id = $_REQUEST['orderid'];
        //echo '<pre>';print_r($_REQUEST);die;
        $mydate = time();
        $qrydate = date("Y-m-d H:i:s", $mydate);
        $issue_id = $_REQUEST['subject'];
        $subject = db_get_field("select name from clues_issues where parent_issue_id=" . $_REQUEST['subject']);
        $subissues = db_get_field("select name from clues_issues where issue_id=" . $_REQUEST['subissues']);
        if ($_REQUEST['sub_subissues']) {
            $sub_subissues = db_get_field("select name from clues_issues where issue_id=" . $_REQUEST['sub_subissues']);
            $subissues = $sub_subissues;
        }
        $cust_q_service_type = $subject . '-' . $subissues;

        $cust_q_customer_name = $_REQUEST['name'];
        if ($cust_q_customer_name == '' || $cust_q_customer_name == null)
            $cust_q_customer_name = ' ';
        $cust_q_customer_contact = $_REQUEST['phone'];
        $cust_q_customer_email = $_REQUEST['email'];
        if ($cust_q_customer_contact == '' || $cust_q_customer_contact == null)
            $cust_q_customer_contact = ' ';

        if ($cust_q_remarks == '' || $cust_q_remarks == null)
            $cust_q_remarks = ' ';
        $cust_q_remarks = $_REQUEST['customer_comment'];
        $cust_q_executive_id = $_SESSION['auth']['user_id'];
        $cust_q_status = db_get_field("select status from cscart_orders where order_id=" . $cust_q_order_id);
        //$cust_q_follow_up=$_REQUEST['cust_follow_up']; 
        if (isset($_REQUEST['sub_subissues']) && $_REQUEST['sub_subissues'] != '') {
            $NewIssueField = db_get_zendesk_code($_REQUEST['sub_subissues']);
            $ZDAssignee = db_get_assignee($_REQUEST['sub_subissues']);
            $ZDAssignGroup = db_get_group($_REQUEST['sub_subissues']);
        } elseif (isset($_REQUEST['subissues']) && $_REQUEST['subissues']) {
            $NewIssueField = db_get_zendesk_code($_REQUEST['subissues']);
            $ZDAssignee = db_get_assignee($_REQUEST['subissues']);
            $ZDAssignGroup = db_get_group($_REQUEST['subissues']);
        } else {
            $NewIssueField = db_get_zendesk_code($_REQUEST['subject']);
            $ZDAssignee = db_get_assignee($_REQUEST['subject']);
            $ZDAssignGroup = db_get_group($_REQUEST['subject']);
        }

        //echo $NewIssueField."\n".$ZDAssignGroup."\n".$ZDAssignee;die;
        
        //Item info
		  $items=db_get_array("select product_id, price,amount,extra from cscart_order_details where order_id='".$cust_q_order_id."'");
		  foreach($items as $k=>$item){
			  $items[$k]['extra'] = unserialize($item['extra']);	
		  }
		  $products['item'] = $items;
		  $prod_id = $products['item'][0]['product_id'];
		  $product = $products['item'][0]['extra']['product'];
		  $cpny_id = $products['item'][0]['extra']['company_id'];
		  $cpny_name = db_get_field("select company from cscart_companies where company_id='".$cpny_id."'");
		  $prod_meta_cat = db_get_field(" SELECT ccd.category
										  FROM cscart_products_categories cpc
										  INNER JOIN cscart_category_descriptions ccd ON cpc.category_id = ccd.category_id
										  WHERE cpc.product_id ='".$prod_id."'");
		  //echo '<pre>';print_r($prod_meta_cat);die;
		 //End Item info
		 //shipmet info
          $shipment=db_get_array("SELECT cst.order_id, cst.shipment_id, cs.tracking_number, cs.carrier,
                                 Date_format(Date(from_unixtime(cs.timestamp)),'%d-%M-%Y') as date
                                 FROM `cscart_shipment_items` cst
                                 INNER JOIN cscart_shipments cs ON cs.shipment_id = cst.shipment_id
                                 WHERE cst.order_id ='".$cust_q_order_id."'");
                                 
          $courier = $shipment[0]['carrier'];  
          $tracking_number = $shipment[0]['tracking_number']; 
          $shipment_date = $shipment[0]['date'];                 
                                 
         // End shipment info  
        

        if (isset($_REQUEST['ticket'])) {

            //creating the zendesk ticket using the zendesk api
            //echo '<pre>';print_r($_REQUEST); print_r($reqt_no);die;
            require(DIR_ROOT . '/contact_us_api_calls_files/ticket_create.php');

            $cust_q_query = "INSERT INTO clues_customer_queries (order_id, user_id, customer_name, customer_contact, customer_email, date, service_type, remarks, customer_comments, ticket_number, status, issue_id, ticket_channel, `group`, assignee) VALUES ('" . $cust_q_order_id . "', '" . $cust_q_executive_id . "', '" . $cust_q_customer_name . "', '" . $cust_q_customer_contact . "', '" . $cust_q_customer_email . "', '" . $qrydate . "', '" . $cust_q_service_type . "', '" . $cust_q_remarks . "','" . $_REQUEST['message'] . "', '" . $reqt_no . "', '" . $cust_q_status . "', '" . $issue_id . "', '" . $ticket_channel . "', '" . $group . "', '" . $assignee . "')";
            db_query($cust_q_query);
            //this is whre we add the notification for the user after the form is submitted
            fn_set_notification('N', '', "Thank you, your request number is <a href='https://shopcluescom.zendesk.com/tickets/$reqt_no'><b>" . $reqt_no . "</b></a> Please use this number for future references.");
        } else {

            $cust_q_query = "INSERT INTO clues_customer_queries (order_id, user_id, customer_name, customer_contact, customer_email, date,service_type, remarks, status, issue_id) VALUES ('" . $cust_q_order_id . "', '" . $cust_q_executive_id . "', '" . $cust_q_customer_name . "', '" . $cust_q_customer_contact . "', '" . $cust_q_customer_email . "', '" . $qrydate . "', '" . $cust_q_service_type . "', '" . $cust_q_remarks . "', '" . $cust_q_status . "', '" . $issue_id . "')";
            //print_r($cust_q_query);die;
            db_query($cust_q_query);
        }

        if ($_REQUEST['modd'] != 'orders_details') {
            $suffix = ".details?order_id=$_REQUEST[orderid]";
        } else {
            $suffix = ".orders_details?order_id=$_REQUEST[orderid]";
        }
    }
    /* End Added by ajay to refund and customer queries */


    if ($mode == 'update_details') {
        fn_trusted_vars('update_order');

        // Update customer's email if its changed in customer's account
        if (!empty($_REQUEST['update_customer_details']) && $_REQUEST['update_customer_details'] == 'Y') {
            $u_id = db_get_field("SELECT user_id FROM ?:orders WHERE order_id = ?i", $_REQUEST['order_id']);
            $current_email = db_get_field("SELECT email FROM ?:users WHERE user_id = ?i", $u_id);
            db_query("UPDATE ?:orders SET email = ?s WHERE order_id = ?i", $current_email, $_REQUEST['order_id']);
        }

        // Log order update
        fn_log_event('orders', 'update', array(
            'order_id' => $_REQUEST['order_id']
        ));

        db_query('UPDATE ?:orders SET ?u WHERE order_id = ?i', $_REQUEST['update_order'], $_REQUEST['order_id']);

        //Update shipping info
        if (!empty($_REQUEST['update_shipping'])) {
            $additional_data = db_get_hash_single_array("SELECT type, data FROM ?:order_data WHERE order_id = ?i", array('type', 'data'), $_REQUEST['order_id']);
            // Get shipping information
            if (!empty($additional_data['L'])) {
                $shippings = unserialize($additional_data['L']);
                if (!empty($shippings)) {
                    foreach ((array) $shippings as $shipping_id => $shipping) {
                        $shippings[$shipping_id] = fn_array_merge($shipping, $_REQUEST['update_shipping'][$shipping_id]);
                    }
                    db_query("UPDATE ?:order_data SET ?u WHERE order_id = ?i AND type = 'L'", array('data' => serialize($shippings)), $_REQUEST['order_id']);
                }
            }
        }

        // Add new shipping info
        if (!empty($_REQUEST['add_shipping'])) {
            $shipping = db_get_field('SELECT shipping FROM ?:shipping_descriptions WHERE shipping_id = ?i', $_REQUEST['add_shipping']['shipping_id']);
            $shippings[$_REQUEST['add_shipping']['shipping_id']] = array(
                'shipping' => $shipping,
                'tracking_number' => $_REQUEST['add_shipping']['tracking_number'],
                'carrier' => $_REQUEST['add_shipping']['carrier'],
            );

            $_data = array(
                'data' => serialize($shippings),
                'order_id' => $_REQUEST['order_id'],
                'type' => 'L',
            );

            db_query('REPLACE INTO ?:order_data ?e', $_data);
        }

        $order_info = fn_get_order_info($_REQUEST['order_id']);
        fn_order_notification($order_info, array(), fn_get_notification_rules($_REQUEST));

        if (!empty($_REQUEST['prolongate_data']) && is_array($_REQUEST['prolongate_data'])) {
            foreach ($_REQUEST['prolongate_data'] as $ekey => $v) {
                $newttl = fn_parse_date($v, true);
                db_query('UPDATE ?:product_file_ekeys SET ?u WHERE ekey = ?s', array('ttl' => $newttl), $ekey);
            }
        }

        if (!empty($_REQUEST['activate_files'])) {
            $edp_data = fn_generate_ekeys_for_edp(array(), $order_info, $_REQUEST['activate_files']);
        }

        if (!empty($edp_data)) {
            $view_mail->assign('order_info', $order_info);
            $view_mail->assign('edp_data', $edp_data);
            $company = fn_get_company_placement_info($order_info['company_id'], $order_info['lang_code']);
            Registry::get('view_mail')->assign('company_placement_info', $company);

            fn_send_mail($order_info['email'], array('email' => $company['company_orders_department'], 'name' => $company['company_name']), 'orders/edp_access_subj.tpl', 'orders/edp_access.tpl', '', $order_info['lang_code']);
        }

        // Update file downloads section
        if (!empty($_REQUEST['edp_downloads'])) {
            foreach ($_REQUEST['edp_downloads'] as $ekey => $v) {
                foreach ($v as $file_id => $downloads) {
                    $max_downloads = db_get_field("SELECT max_downloads FROM ?:product_files WHERE file_id = ?i", $file_id);
                    if (!empty($max_downloads)) {
                        db_query('UPDATE ?:product_file_ekeys SET ?u WHERE ekey = ?s', array('downloads' => $max_downloads - $downloads), $ekey);
                    }
                }
            }
        }

        $suffix = ".details?order_id=$_REQUEST[order_id]";
    }

    if ($mode == 'bulk_print' && !empty($_REQUEST['order_ids'])) {
        if (DISPATCH_EXTRA == 'java') {
            javabridge_bulkorderdetails($_REQUEST['order_ids']);
        }
        $view_mail->assign('order_status_descr', fn_get_statuses(STATUSES_ORDER, true, true, true));

        $html = array();
        foreach ($_REQUEST['order_ids'] as $k => $v) {
            $order_info = fn_get_order_info($v);
            $view_mail->assign('order_info', $order_info);
            $view_mail->assign('payment_method', fn_get_payment_data((!empty($order_info['payment_method']['payment_id']) ? $order_info['payment_method']['payment_id'] : 0), $order_info['order_id'], CART_LANGUAGE));
            $view_mail->assign('order_status', fn_get_status_data($order_info['status'], STATUSES_ORDER, $order_info['order_id'], CART_LANGUAGE));
            $view_mail->assign('status_settings', fn_get_status_params($order_info['status']));
            $view_mail->assign('profile_fields', fn_get_profile_fields('I'));

            if (DISPATCH_EXTRA == 'pdf' || DISPATCH_EXTRA == 'html') {
                if (DISPATCH_EXTRA == 'html') {
                    $html[] = $view_mail->display('orders/print_invoice_html.tpl', false);
                } else {
                    $html[] = $view_mail->display('orders/print_invoice.tpl', false);
                }
            } else {
                $view_mail->display('orders/print_invoice.tpl');
                if ($v != end($_REQUEST['order_ids'])) {
                    echo "<div style='page-break-before: always;'>&nbsp;</div>";
                }
            }
        }

        if (DISPATCH_EXTRA == 'pdf') {
            //fn_html_to_pdf($html, fn_get_lang_var('invoices') . '-' . implode('-', $_REQUEST['order_ids']));
            fn_html_to_pdf($html, 'order_details_' . date('Y-m-d_H-i-s'));
        }
        if (DISPATCH_EXTRA == 'html') {
            //fn_html_to_pdf($html, 'order_details_'.date('Y-m-d_H-i-s'));
            fn_html_to_print($html, 'order_details_' . date('Y-m-d_H-i-s') . '.html');
        }
        exit;
    }

    if ($mode == 'packing_slip' && !empty($_REQUEST['order_ids'])) {
        foreach ($_REQUEST['order_ids'] as $k => $v) {
            $view_mail->assign('order_info', fn_get_order_info($v));
            $view_mail->display('orders/print_packing_slip.tpl');
            if ($v != end($_REQUEST['order_ids'])) {
                echo "<div style='page-break-before: always;'>&nbsp;</div>";
            }
        }

        exit;
    }

    if ($mode == 'remove_cc_info' && !empty($_REQUEST['order_ids'])) {

        fn_set_progress('total', sizeof($_REQUEST['order_ids']));

        foreach ($_REQUEST['order_ids'] as $v) {
            $payment_info = db_get_field("SELECT data FROM ?:order_data WHERE order_id = ?i AND type = 'P'", $v);
            fn_cleanup_payment_info($v, $payment_info);
        }

        fn_set_notification('N', fn_get_lang_var('notice'), fn_get_lang_var('done'));

        if (count($_REQUEST['order_ids']) == 1) {
            $o_id = array_pop($_REQUEST['order_ids']);
            $suffix = ".details?order_id=$o_id";
        } else {
            exit;
        }
    }

    if ($mode == 'export_range') {
        if (!empty($_REQUEST['order_ids'])) {
            if (empty($_SESSION['export_ranges'])) {
                $_SESSION['export_ranges'] = array();
            }

            if (empty($_SESSION['export_ranges']['orders'])) {
                $_SESSION['export_ranges']['orders'] = array('pattern_id' => 'orders');
            }

            $_SESSION['export_ranges']['orders']['data'] = array('order_id' => $_REQUEST['order_ids']);

            unset($_REQUEST['redirect_url']);
            return array(CONTROLLER_STATUS_REDIRECT, "exim.export?section=orders&pattern_id=" . $_SESSION['export_ranges']['orders']['pattern_id']);
        }
    }

    if ($mode == 'google') {
        $google_request_sent = false;
        $order_info = fn_get_order_info($_REQUEST['order_id']);
        $processor_data = fn_get_payment_method_data($order_info['payment_id']);
        $base_url = "https://" . (($processor_data['params']['test'] == 'N') ? 'checkout.google.com' : 'sandbox.google.com/checkout') . '/cws/v2/Merchant/' . $processor_data['params']['merchant_id'];
        $request_url = $base_url . '/request';
        $schema_url = 'http://checkout.google.com/schema/2';
        $google_data = !empty($_REQUEST['google_data']) ? $_REQUEST['google_data'] : array();

        $post = array();
        // XML request to mark order delivered
        if ($action == 'deliver') {
            $ship_info = reset($order_info['shipping']);

            $post = array();
            $post[] = "<deliver-order xmlns='" . $schema_url . "' google-order-number='" . $order_info['payment_info']['transaction_id'] . "'>";
            $post[] = '<tracking-data>';
            $post[] = '<carrier>' . (!empty($ship_info['carrier']) ? $ship_info['carrier'] : 'Other') . '</carrier>';
            $post[] = '<tracking-number>' . (!empty($ship_info['tracking_number']) ? $ship_info['tracking_number'] : '') . '</tracking-number>';
            $post[] = '</tracking-data>';
            $post[] = '<send-email>false</send-email>';
            $post[] = '</deliver-order>';

            // XML request to cancel the order
        } elseif ($action == 'add_tracking_data') {
            //$ship_info = reset($order_info['shipping']);

            foreach ($order_info['shipping'] as $ship_info) {
                if (!empty($ship_info['carrier']) && !empty($ship_info['tracking_number'])) {
                    $post = array();
                    $post[] = "<add-tracking-data xmlns='" . $schema_url . "' google-order-number='" . $order_info['payment_info']['transaction_id'] . "'>";
                    $post[] = '<tracking-data>';
                    $post[] = '<carrier>' . $ship_info['carrier'] . '</carrier>';
                    $post[] = '<tracking-number>' . $ship_info['tracking_number'] . '</tracking-number>';
                    $post[] = '</tracking-data>';
                    $post[] = '</add-tracking-data>';
                    fn_google_send_order_command($post, $processor_data, $request_url, $action, $_REQUEST['order_id']);
                }
            }
            $google_request_sent = true;

            // XML request to send a message to the customer
        } elseif ($action == 'send_message') {
            $post[] = "<send-buyer-message xmlns='" . $schema_url . "' google-order-number='" . $order_info['payment_info']['transaction_id'] . "'>";
            $post[] = '<message>' . $google_data['message'] . '</message>';
            $post[] = '<send-email>true</send-email>';
            $post[] = '</send-buyer-message>';

            // XML request to refund the order
        } elseif ($action == 'charge') {
            $post[] = "<charge-order xmlns='" . $schema_url . "' google-order-number='" . $order_info['payment_info']['transaction_id'] . "'>";
            $post[] = '<amount currency="' . $processor_data['params']['currency'] . '">' . $google_data['charge_amount'] . '</amount>';
            $post[] = '</charge-order>';

            // XML request to refund the order
        } elseif ($action == 'refund') {
            $post[] = "<refund-order xmlns='" . $schema_url . "' google-order-number='" . $order_info['payment_info']['transaction_id'] . "'>";
            $post[] = '<amount currency="' . $processor_data['params']['currency'] . '">' . $google_data['refund_amount'] . '</amount>';
            $post[] = '<reason>' . $google_data['refund_reason'] . '</reason>';
            $post[] = '<comment>' . $google_data['refund_comment'] . '</comment>';
            $post[] = '</refund-order>';

            // XML request to cancel the order
        } elseif ($action == 'cancel') {
            $post[] = "<cancel-order xmlns='" . $schema_url . "' google-order-number='" . $order_info['payment_info']['transaction_id'] . "'>";
            $post[] = '<reason>' . $google_data['cancel_reason'] . '</reason>';
            $post[] = '<comment>' . $google_data['cancel_comment'] . '</comment>';
            $post[] = '</cancel-order>';

            // XML request to archive the order
        } elseif ($action == 'archive') {
            $post[] = "<archive-order xmlns='" . $schema_url . "' google-order-number='" . $order_info['payment_info']['transaction_id'] . "' />";
        }

        if (!$google_request_sent) {
            fn_google_send_order_command($post, $processor_data, $request_url, $action, $_REQUEST['order_id']);
        }

        $suffix = '.details?order_id=' . $_REQUEST['order_id'];
    }


    if ($mode == 'send_exception_mail') {
        $sub = $_REQUEST['subject'];
        $body = urldecode($_REQUEST['em_body']);
        $order_ids = explode(',', $_REQUEST['order_ids']);

        $temp_id = $_REQUEST['temp_id'];

        foreach ($order_ids as $order_id) {
            $result = db_get_row("select o.order_id,o.firstname,o.lastname,o.total,o.b_phone,o.s_address,o.s_address_2,o.s_city,o.s_state,o.s_country,o.s_zipcode,o.email,cs.tracking_number,cs.carrier,date_format(from_unixtime(cs.timestamp),'%d-%M') as ship_date from cscart_orders o left join cscart_shipment_items csi on csi.order_id=o.order_id left join cscart_shipments cs on cs.shipment_id=csi.shipment_id where o.order_id ='" . $order_id . "' group by csi.order_id");

            $to = $result['email'];

            $values['total'] = $result['total'];
            $values['fname'] = $result['firstname'];
            $values['lname'] = $result['lastname'];
            $values['email'] = $result['email'];
            $values['phone'] = $result['b_phone'];
            $values['order_id'] = $result['order_id'];
            $values['track_no'] = $result['tracking_number'];
            $values['carrier'] = $result['carrier'];
            $values['ship_date'] = $result['ship_date'];
            $values['ship_address'] = $result['s_address'] . "<br/>" . $result['s_address_2'] . "<br/>" . $result['s_city'] . "," . $result['s_state'] . "-" . $result['s_zipcode'];

            $sql = "select cor.id,cor.cause_id,cor.datetime,cor.type,ecl.cause from clues_exception_causes_order_rel cor inner join clues_exception_causes_list ecl on ecl.id=cor.cause_id where cor.order_id='" . $order_id . "' and cor.latest=1 and cor.type='Cause' order by id";
            $query_res = db_get_array($sql);
            foreach ($query_res as $result) {
                $id = $result['id'] + 1;
                $sql = "select ecl.cause from clues_exception_causes_list ecl inner join clues_exception_causes_order_rel ocr on ocr.cause_id=ecl.id and ecl.type='Action' where ocr.id='" . $id . "' and ocr.order_id='" . $order_id . "'";
                $values[$result['cause']] = db_get_field($sql);
            }

            if (!empty($_REQUEST['email_from'])) {
                $from = $_REQUEST['email_from'];
            } else {
                $from = 'support@shopclues.com';
            }
            $arr = fn_hp_send_email_template_email_open($sub, $body, $to, $from, $values);
            $email_subject = $arr[0];
            $email_body = $arr[1];

            fn_hp_store_sent_mail($order_id, $email_subject, $email_body, $temp_id, $auth['user_id'], $from, $to);
        }


        if (isset($_REQUEST['ordr_page'])) {
            $str = order_exc_cause_html_ord($_REQUEST['order_ids']);
            $strhtml = $_REQUEST['order_ids'] . ",hpsepsc," . $str;
        } else {
            $ord_id = explode(',', $_REQUEST['order_ids']);
            for ($i = 0; $i < count($ord_id); $i++) {
                $str = order_exc_cause_html($ord_id[$i]);
                if ($strhtml == '') {
                    $strhtml = $ord_id[$i] . ",hpsepsc," . $str;
                } else {
                    $strhtml = $strhtml . ",hpsepsc," . $ord_id[$i] . ",hpsepsc," . $str;
                }
            }
        }

        echo $strhtml;
        exit;
    }
    if ($mode == 'export_orders_gift_message') {
        javabridge_order_gift_message($_REQUEST['order_ids']);
    }
    return array(CONTROLLER_STATUS_OK, "orders" . $suffix);
}

$params = $_REQUEST;


if ($mode == 'delete') {
    fn_delete_order($_REQUEST['order_id']);

    return array(CONTROLLER_STATUS_REDIRECT);
} elseif ($mode == 'check_shipment') {
    //code by arpit gaur to handle ajax request to check shipment existence
    $result = db_get_array("select * from cscart_shipment_items where order_id='" . $params['order_id'] . "'");
    if (count($result) > 0) {
        //$view->assign('shipment_created','yes');
        echo 'yes';
        die;
    } else {
        //$view->assign('shipment_created','no');
        echo 'no';
        die;
    }
    //code by arpit gaur to handle the ajax request to check shipment existence
} elseif ($mode == 'remove_message') {
    $order_id = $_REQUEST['order_id'];
    $sql = "update clues_gift_message set no_message = 'Y' where order_id = '" . $order_id . "'";
    db_query($sql);
    return array(CONTROLLER_STATUS_REDIRECT, "orders.details?order_id=$order_id");
} elseif ($mode == 'remove_gift_wrapping') {
    $order_id = $_REQUEST['order_id'];
    $order_info = fn_get_order_info($order_id);
    //echo '<pre>';print_r($order_info);die("chandan");
    $total = $order_info['total'];
    $gifting_charges = $order_info['gifting_charge'];
    $total = $total - $gifting_charges;
    $gifting_charges = '0';
    $sql = "update cscart_orders set total = '" . $total . "', gifting_charge='0', gift_it='N' where order_id = '" . $order_id . "'";
    db_query($sql);

    $sql = "update clues_gift_message set no_message = 'Y' where order_id = '" . $order_id . "'";
    db_query($sql);
    return array(CONTROLLER_STATUS_REDIRECT, "orders.details?order_id=$order_id");
} elseif ($mode == 'print_invoice') {
    if (!empty($_REQUEST['order_id'])) {
        $order_info = fn_get_order_info($_REQUEST['order_id']);
        if (empty($order_info)) {
            return array(CONTROLLER_STATUS_NO_PAGE);
        }
        javabridge_bulkorderdetails(array($_REQUEST['order_id']));
        fn_translate_products($order_info['items'], '', CART_LANGUAGE, true);

        $view_mail->assign('order_info', $order_info);
        $view_mail->assign('order_status', fn_get_status_data($order_info['status'], STATUSES_ORDER, $order_info['order_id'], CART_LANGUAGE));
        $view_mail->assign('payment_method', fn_get_payment_data((!empty($order_info['payment_method']['payment_id']) ? $order_info['payment_method']['payment_id'] : 0), $order_info['order_id'], CART_LANGUAGE));
        $view_mail->assign('status_settings', fn_get_status_params($order_info['status']));
        $view_mail->assign('profile_fields', fn_get_profile_fields('I'));

        if (PRODUCT_TYPE == 'MULTIVENDOR') {
            $view_mail->assign('take_surcharge_from_vendor', fn_take_payment_surcharge_from_vendor($order_info['items']));
        }

        if (!empty($_REQUEST['format']) && $_REQUEST['format'] == 'pdf') {
            fn_html_to_pdf($view_mail->display('orders/print_invoice.tpl', false), fn_get_lang_var('invoice') . '-' . $_REQUEST['order_id']);
        } else {
            $view_mail->display('orders/print_invoice.tpl');
        }

        exit;
    }
} elseif ($mode == 'print_gift_message') {

    //$order_info = fn_get_order_info($_REQUEST['order_id']);
    javabridge_order_gift_message($_REQUEST['order_id']);
} elseif ($mode == 'print_packing_slip') {
    if (!empty($_REQUEST['order_id'])) {
        $order_info = fn_get_order_info($_REQUEST['order_id']);
        if (empty($order_info)) {
            return array(CONTROLLER_STATUS_NO_PAGE);
        }

        $view_mail->assign('order_info', $order_info);

        $view_mail->display('orders/print_packing_slip.tpl');

        exit;
    }
} elseif ($mode == 'print_gift_message') {
    header("Content-type: image/png");
    $gift_message = fn_get_order_gift_message($_REQUEST['order_id']);



    $image = imagecreatefrompng('pdf_image.png');
    var_dump($image);
    die;
    //$black = imagecolorallocate($image, 0, 0, 0); 

    $to_static = 'To : ';
    $to_variable = $gift_message . gift_to;
    $message = $gift_message . message;
    $from_static = 'From : ';
    $from_variable = $gift_message . gift_from;
    $custom_order_id = '';

    $message1 = explode(" ", $message);
    $old_str = '';
    $new_str = '';
    for ($i = 0; $i < count($message1); $i++) {
        //$old_str=$new_str='';
        $new_str.=$message1[$i];
        if (strlen($new_str) > 75) {
            $old_str.="\n" . $message1[$i];
            $new_str = '';
        } else {
            $old_str.=" " . $message1[$i];
            $new_str.=" " . $message1[$i];
        }
    }
    // imagefttext($image, 16, 0, 50, 250, $black, 'Mywriting.ttf', $to_static);
    //imagefttext($image, 16, 0, 80, 250, $black, 'Mywriting.ttf', $to_variable);
    //imagefttext($image, 16, 0, 60, 320, $black, 'Mywriting.ttf', $old_str);
    //imagefttext($image, 16, 0, 345, 600, $black, 'Mywriting.ttf', $from_static);
    //imagefttext($image, 16, 0, 405, 600, $black, 'Mywriting.ttf', $from_variable);
    //imagefttext($image, 10, 0, 490, 830, $black, 'final.ttf', '#');
    //imagefttext($image, 10, 0, 500, 830, $black, 'final.ttf', '32424245');

    imagejpeg($image);
    imagedestroy($image);
} elseif ($mode == 'details') {
    global $count;
    $count = 0;
      //change by ajay to redirect merchant to new detail page
	 if(defined("COMPANY_ID"))
	 {      //echo Registry::get('config.merchant_enable_new_order_screen');die;
                if(Registry::get('config.merchant_enable_new_order_screen') == 'Y' && $_REQUEST['ff'] !=1){
		 return array(CONTROLLER_STATUS_REDIRECT,"orders.merchant_detail&order_id=".$_REQUEST['order_id']);
                }
                
	 }
//code end
    
    //changes by arpit gaur for shipment created check in alert service
    $result = db_get_array("select * from cscart_shipment_items where order_id='" . $params['order_id'] . "'");
    if (count($result) > 0) {
        $view->assign('shipment_created', 'yes');
    } else {
        $view->assign('shipment_created', 'no');
    }
//changes by arpit gaur ends here

    $_REQUEST['order_id'] = empty($_REQUEST['order_id']) ? 0 : $_REQUEST['order_id'];

    $order_info = fn_get_order_info($_REQUEST['order_id'], false, true, true);
    //echo "<pre>";print_r($order_info['shipment_ids']);die;
    if (empty($order_info)) {
        return array(CONTROLLER_STATUS_NO_PAGE);
    }

    if (isset($order_info['need_shipping']) && $order_info['need_shipping']) {
        $shippings = db_get_array("SELECT a.shipping_id, a.min_weight, a.max_weight, a.position, a.status, b.shipping, b.delivery_time, a.usergroup_ids FROM ?:shippings as a LEFT JOIN ?:shipping_descriptions as b ON a.shipping_id = b.shipping_id AND b.lang_code = ?s WHERE a.status = ?s ORDER BY a.position", CART_LANGUAGE, 'A');
        $view->assign('shippings', $shippings);
    }

    Registry::set('navigation.tabs', array(
        'general' => array(
            'title' => fn_get_lang_var('general'),
            'js' => true
        ),
        'addons' => array(
            'title' => fn_get_lang_var('addons'),
            'js' => true
        ),
    ));

    $google_info = db_get_field("SELECT data FROM ?:order_data WHERE order_id = ?i AND type = ?s", $_REQUEST['order_id'], GOOGLE_ORDER_DATA);

    if (PRODUCT_TYPE == 'MULTIVENDOR') {
        $view->assign('take_surcharge_from_vendor', fn_take_payment_surcharge_from_vendor($order_info['items']));
    }

    if (!empty($google_info)) {
        Registry::set('navigation.tabs.google', array(
            'title' => fn_get_lang_var('google_info'),
            'js' => true
        ));

        $_SESSION['google_info'] = unserialize($google_info);
        $view->assign('google_info', $_SESSION['google_info']);
    }

    foreach ($order_info['items'] as $v) {
        if (!empty($v['extra']['is_edp']) && $v['extra']['is_edp'] == 'Y') {
            Registry::set('navigation.tabs.downloads', array(
                'title' => fn_get_lang_var('downloads'),
                'js' => true
            ));
            $view->assign('downloads_exist', true);
            break;
        }
    }

    if (!empty($order_info['promotions'])) {
        Registry::set('navigation.tabs.promotions', array(
            'title' => fn_get_lang_var('promotions'),
            'js' => true
        ));
    }
    // Check for the shipment access
    if (Registry::get('settings.General.use_shipments') == 'Y') {
        if (!fn_check_user_access($auth['user_id'], 'edit_order')) {
            $order_info['need_shipment'] = false;
        }
    }

    require_once(DIR_ADDONS . 'barcode/lib/barcodegenerator/barcode.php');
    require_once(DIR_ADDONS . 'barcode/lib/barcodegenerator/c128bobject.php');
    if (!file_exists($_REQUEST['order_id'] . '.png')) {
        generate_image($_REQUEST['order_id']);
    }

    //Paid through               
    $paid = db_get_row("SELECT cpo.name,cpt.name as type_name FROM cscart_orders co , clues_payment_options cpo, 
		              clues_payment_types cpt where co.payment_option_id = cpo.payment_option_id AND 
			      cpo.payment_type_id=cpt.payment_type_id AND co.order_id='" . $_REQUEST['order_id'] . "'");

    $order_info['paid'] = $paid;

    //Merchant city and state by ajay
    $merchant = db_get_row("select warehouse_city,warehouse_state from clues_warehouse_contact where 
	                           company_id='" . $order_info['company_id'] . "'");

    $order_info['merchant'] = $merchant;
    // fulfillment type By Ajay
    $fulfillment = db_get_row("select cfl.description from clues_fulfillment_lookup cfl
					inner join cscart_companies  cc on cfl.fulfillment_id=cc.fulfillment_id
					where cc.company_id='" . $order_info['company_id'] . "'");
    $order_info['fulfillment'] = $fulfillment;
    //End  fulfillment type By Ajay
    //clues bucks used by ajay
    $order_data = db_get_hash_single_array("select type, data from cscart_order_data where order_id='" . $order_info['order_id'] . "' 
	                                        and type in('I','U','W')", array('type', 'data'));
    foreach ($order_data as $k => $o_data) {
        $data = @unserialize($o_data);
        if (is_array($data)) {
            $order_data[$k] = @unserialize($o_data);
        } else {
            $order_data[$k] = $o_data;
        }
    }
    $order_info['order_data'] = $order_data;
    $view->assign('order_info', $order_info);

    //cust_remarks details by ajay
    $cust_remarks = db_get_array("select concat(cu.firstname,' ',cu.lastname) as user_name,ccq.issue_id, ci.name, ccq.date, 
                               ccq.remarks, csd.status,csd.type,csd.description, 
                               ccq.follow_up, ccq.customer_comments, ccq.ticket_number from clues_customer_queries ccq 
                               left join cscart_users cu on (ccq.user_id = cu.user_id) 
                               left join clues_issues ci on ci.issue_id = ccq.issue_id 
                               left join cscart_status_descriptions csd on csd.status=ccq.status where  
                               ccq.order_id='" . $order_info['order_id'] . "' and csd.type='O' order by ccq.id desc");
    $view->assign('cust_remarks', $cust_remarks);
    //////////////////////////////////////////////////////////
    // END cust_remarks details by ajay

    //echo "<pre>";print_r($order_info);die;
    $view->assign('status_settings', fn_get_status_params($order_info['status']));

    // Delete order_id from new_orders table
    db_query("DELETE FROM ?:new_orders WHERE order_id = ?i AND user_id = ?i", $_REQUEST['order_id'], $auth['user_id']);

    fn_add_breadcrumb(fn_get_lang_var('orders'), "orders.manage.reset_view");
    fn_add_breadcrumb(fn_get_lang_var('search_results'), "orders.manage.last_view");

    // Check if customer's email is changed
    if (!empty($order_info['user_id'])) {
        $current_email = db_get_field("SELECT email FROM ?:users WHERE user_id = ?i", $order_info['user_id']);
        if (!empty($current_email) && $current_email != $order_info['email']) {
            $view->assign('email_changed', true);
        }
    }
    /* Edited by Sudhir to show the order history */
    $order_history = db_get_array("SELECT user_id, order_id, from_status, to_status, transition_date, transition_id, memo FROM clues_order_history WHERE order_id= ?i", $_REQUEST['order_id']);

    if (!empty($order_history)) {
        $view->assign('order_history', $order_history);
    }
    $RTOFLAG = 0;
    foreach ($order_history as $statuscheck) {
        if ($statuscheck['to_status'] == 'S') {
            $RTOFLAG = 1;
        }
    }
    $view->assign('rtoflag', $RTOFLAG);
    $sql = "select id,cause,parents from clues_exception_causes_list where type='Cause'";
    $ret = db_get_array($sql);

    $not_del = array();
    $not_ship = array();
    $not_del = array();
    $other = array();
    foreach ($ret as $result) {
        if ($result['parents'] == "Not Delivered") {
            $not_del[$result['id']] = $result['cause'];
        } else if ($result['parents'] == "Not Shipped") {
            $not_ship[$result['id']] = $result['cause'];
        } else if ($result['parents'] == "Not Complete") {
            $not_comp[$result['id']] = $result['cause'];
        } else {
            $other[$result['id']] = $result['cause'];
        }
    }

    $ret_ship = db_get_array("SELECT csi.shipment_id FROM cscart_shipments cs, cscart_shipment_items csi WHERE csi.order_id ='" . $order_info['order_id'] . "' AND cs.shipment_id = csi.shipment_id AND return_id != ''");
    foreach ($ret_ship as $inner) {
        $result[key($inner)] = current($inner);
        $return_ship[] = $result['shipment_id'];
    }
    //echo "<pre>";print_r($return_ship);die;
    $view->assign("return_ship", $return_ship);
    //echo "<pre>";print_r($ret_ship);die;


    $view->assign("not_del", $not_del);
    $view->assign("not_ship", $not_ship);
    $view->assign("not_comp", $not_comp);
    $view->assign("other", $other);

    $view->assign('cause_list', $ret);
    $sql = "select id,cause,parents from clues_exception_causes_list where type='Action'";
    $ret = db_get_array($sql);
    $view->assign('action_list', $ret);
    $sql = "select id,cause from clues_exception_causes_list where type='Tag'";
    $ret = db_get_array($sql);
    $view->assign('tag_list', $ret);

    $ret = db_get_array("select id,title from clues_email_templates");
    $view->assign("em_temp", $ret);

    /// Added By Sudhir
    $query = db_get_array("SELECT user_id, order_id, status_from, status_to, return_id, comment, datetime FROM clues_rma_history WHERE order_id = '" . $_REQUEST['order_id'] . "'");
    $view->assign('rma_history', $query);
    /// Added by Sudhir end here
    //code by anoop to show return id in rma history section
 $return_id = db_get_field("SELECT return_id from cscart_rma_returns where order_id='".$_REQUEST['order_id']."'");
 $view->assign('return_id',$return_id);
 //code by anoop ends here
    ///// end of show history
    //code to display a small box for parent and child order of this particular order by ankur

    $parent_id = 0;

    if ($order_info['is_parent_order'] == 'Y' and $order_info['parent_order_id'] == 0) {
        $parent_id = $order_info['order_id'];
    } else if ($order_info['is_parent_order'] == 'N' and $order_info['parent_order_id'] != 0) {
        $parent_id = $order_info['parent_order_id'];
    }

    if ($order_info['is_parent_order'] == 'N' and $order_info['parent_order_id'] == 0) { //this is to display a small message whether it is asplit order or not
        $order_type = 'single';
    } else {
        $order_type = 'split';
    }
    $child_id_array = array();
    if ($parent_id != 0) {
        $child_order_ids = db_get_array("SELECT order_id FROM cscart_orders WHERE parent_order_id= ?i", $parent_id);


        foreach ($child_order_ids as $child_id) {
            if ($child_id['order_id'] != $order_info['order_id'])
                $child_id_array[] = $child_id['order_id'];
        }
    }

    $view->assign('parent_id', $parent_id);
    $view->assign('order_type', $order_type);
    $view->assign('child_order_id', $child_id_array);
    //end of code

    /* Calculate customer successful order score  */

    $email_id = db_get_row("SELECT email FROM cscart_orders WHERE order_id= ?i", $_REQUEST['order_id']);
    //by arpit gaur for email id
    $view->assign('zen_email', $email_id['email']);

    /* Using email-id to find customer total order count and unsuccessful order count */

    $total_order_count = db_get_row("select count(order_id) from cscart_orders where email='" . $email_id['email'] . "'");

    $unsuccessful_order_count = db_get_row("select count(order_id) from cscart_orders where status in ('i','m','j','r','s','w') AND email= '" . $email_id['email'] . "'");

    $successful_order_count = $total_order_count['count(order_id)'] - $unsuccessful_order_count['count(order_id)'];

    $success_calculate = ($successful_order_count / $total_order_count['count(order_id)']) * 100;

    $view->assign('success_percentage', $success_calculate);
    $view->assign('success_order_count', $successful_order_count);
    $view->assign('total_order_count', $total_order_count['count(order_id)']);

    $view->assign('request_order_id', $_REQUEST['order_id']); //by arpit
    //var_dump($order_info);
    //echo'<pre>';print_r($order_info);die;

    /* Customer success score count End */
}

elseif ($mode == 'merchant_detail')
{
	
	//changes by arpit gaur for shipment created check in alert service
$result=db_get_array("select * from cscart_shipment_items where order_id='".$params['order_id']."'");
	if(count($result)>0)
	{
		$view->assign('shipment_created','yes');
	}
	else
	{
		$view->assign('shipment_created','no');
	}
//changes by arpit gaur ends here

	
	$_REQUEST['order_id'] = empty($_REQUEST['order_id']) ? 0 : $_REQUEST['order_id'];

	$order_info = fn_get_order_info($_REQUEST['order_id'], false, true, true);
	
	if (empty($order_info)) {
		return array(CONTROLLER_STATUS_NO_PAGE);
	}

	if (isset($order_info['need_shipping']) && $order_info['need_shipping']) {
		$shippings = db_get_array("SELECT a.shipping_id, a.min_weight, a.max_weight, a.position, a.status, b.shipping, b.delivery_time, a.usergroup_ids FROM ?:shippings as a LEFT JOIN ?:shipping_descriptions as b ON a.shipping_id = b.shipping_id AND b.lang_code = ?s WHERE a.status = ?s ORDER BY a.position", CART_LANGUAGE, 'A');
		$view->assign('shippings', $shippings);
	}


    //added by ankur to fetch the promotion name
	if(!empty($order_info['promotions']))
	{
		foreach($order_info['promotions'] as $prom_id=>$value)
		{
			$promid[]=$prom_id;
		}
		$sql="select cp.promotion_id,cp.company_id,cpd.name from cscart_promotions cp inner join cscart_promotion_descriptions cpd on cp.promotion_id=cpd.promotion_id where cp.promotion_id in(".implode(',',$promid).")";
		$prom_data=db_get_array($sql);
		$view->assign('prom_data', $prom_data);
	}
	//code end

	/*Registry::set('navigation.tabs', array (
		'general' => array (
			'title' => fn_get_lang_var('general'),
			'js' => true
		),
		'addons' => array (
			'title' => fn_get_lang_var('addons'),
			'js' => true
		),
	));
*/
	$google_info = db_get_field("SELECT data FROM ?:order_data WHERE order_id = ?i AND type = ?s", $_REQUEST['order_id'], GOOGLE_ORDER_DATA);

	if (PRODUCT_TYPE == 'MULTIVENDOR') {
		$view->assign('take_surcharge_from_vendor', fn_take_payment_surcharge_from_vendor($order_info['items']));
	}

	if (!empty($google_info)) {
		Registry::set('navigation.tabs.google', array (
			'title' => fn_get_lang_var('google_info'),
			'js' => true
		));

		$_SESSION['google_info'] = unserialize($google_info);
		$view->assign('google_info', $_SESSION['google_info']);
	}

	foreach ($order_info['items'] as $v) {
		if (!empty($v['extra']['is_edp']) && $v['extra']['is_edp'] == 'Y') {
			Registry::set('navigation.tabs.downloads', array (
				'title' => fn_get_lang_var('downloads'),
				'js' => true
			));
			$view->assign('downloads_exist', true);
			break;
		}
	}

	/*if (!empty($order_info['promotions'])) {
		Registry::set('navigation.tabs.promotions', array (
			'title' => fn_get_lang_var('promotions'),
			'js' => true
		));
	}*/
	// Check for the shipment access
	if (Registry::get('settings.General.use_shipments') == 'Y') {
		if (!fn_check_user_access($auth['user_id'], 'edit_order')) {
			$order_info['need_shipment'] = false;
		}
	}
	
	/*require_once(DIR_ADDONS . 'barcode/lib/barcodegenerator/barcode.php');
	require_once(DIR_ADDONS . 'barcode/lib/barcodegenerator/c128bobject.php');
	if(!file_exists($_REQUEST['order_id'].'.png')){
			generate_image($_REQUEST['order_id']);
		}*/
         
         //Paid through
                
        $paid = db_get_row("SELECT cpo.name,cpt.name as type_name FROM cscart_orders co , clues_payment_options cpo, clues_payment_types cpt where co.payment_option_id = cpo.payment_option_id AND cpo.payment_type_id=cpt.payment_type_id
 AND co.order_id='".$_REQUEST['order_id']."'");

	$order_info['paid']=$paid;
        
        // Merchant city and state

	$merchant = db_get_row("select warehouse_city,warehouse_state from clues_warehouse_contact where company_id='".$order_info['company_id']."'");
         
         $order_info['merchant'] = $merchant;
		 // fulfillment type By Ajay
	$fulfillment = 	db_get_row("select cfl.description from clues_fulfillment_lookup cfl
								inner join cscart_companies  cc on cfl.fulfillment_id=cc.fulfillment_id
								where cc.company_id='".$order_info['company_id']."'"); 
		$order_info['fulfillment'] = $fulfillment;	
		//End  fulfillment type By Ajay
							  
	$view->assign('order_info', $order_info);
	
	$view->assign('status_settings', fn_get_status_params($order_info['status']));
	
	// Delete order_id from new_orders table
	db_query("DELETE FROM ?:new_orders WHERE order_id = ?i AND user_id = ?i", $_REQUEST['order_id'], $auth['user_id']);
   
	  fn_add_breadcrumb(fn_get_lang_var('orders'), "orders.merchants&period=HM");
	  fn_add_breadcrumb(fn_get_lang_var('search_results'), "orders.merchants.last_view");
	  
	//get the orders next action
	 $sql="select ona.action from clues_orders_next_action ona inner join cscart_companies c on ona.fulfillment_id=c.fulfillment_id where ona.status='".$order_info['status']."' and ona.type='O' and c.company_id='".$order_info['company_id']."'";
	 $next_action=db_get_field($sql);
	 $view->assign('next_action', $next_action);
	
	//code end  
	   


	// Check if customer's email is changed
	if (!empty($order_info['user_id'])) {
		$current_email = db_get_field("SELECT email FROM ?:users WHERE user_id = ?i", $order_info['user_id']);
		if (!empty($current_email) && $current_email != $order_info['email']) {
			$view->assign('email_changed', true);
		}
	}
	/* Edited by Sudhir to show the order history */
	$order_history = db_get_array("SELECT user_id, order_id, from_status, to_status, transition_date, transition_id, memo FROM clues_order_history WHERE order_id= ?i", $_REQUEST['order_id']);
        
	if (!empty($order_history)) {
		$view->assign('order_history', $order_history);
	}
	$RTOFLAG = 0;
	foreach($order_history as $statuscheck)
	{
		if($statuscheck['to_status']=='S')
		{
			$RTOFLAG = 1;
		}
	}
	$view->assign('rtoflag', $RTOFLAG);
	
	/// Added By Sudhir
	$query = db_get_array("SELECT user_id, order_id, status_from, status_to, return_id, comment, datetime FROM clues_rma_history WHERE order_id = '".$_REQUEST['order_id']."'");
	$view->assign('rma_history', $query);
	/// Added by Sudhir end here
	
	///// end of show history
	
	//code to display a small box for parent and child order of this particular order by ankur
	
	$parent_id=0;
	
	if($order_info['is_parent_order']=='Y' and $order_info['parent_order_id']==0)
	{
		$parent_id=$order_info['order_id'];
	}
	else if($order_info['is_parent_order']=='N' and $order_info['parent_order_id']!=0)
	{
		$parent_id=$order_info['parent_order_id'];
	}
	
	if($order_info['is_parent_order']=='N' and $order_info['parent_order_id']==0) //this is to display a small message whether it is asplit order or not
	{
		$order_type='single';
	}
	else
	{
		$order_type='split';
	}
	$child_id_array=array();
	if($parent_id!=0)
	{
		$child_order_ids = db_get_array("SELECT order_id FROM cscart_orders WHERE parent_order_id= ?i", $parent_id);
		
		
		foreach($child_order_ids as $child_id)
		{
			if($child_id['order_id']!=$order_info['order_id'])
			$child_id_array[]=$child_id['order_id'];
		}
	}
	
	$view->assign('parent_id',$parent_id);
	$view->assign('order_type',$order_type);
	$view->assign('child_order_id',$child_id_array);
	//end of code
        
        /* Calculate customer successful order score  */
       
        $email_id = db_get_row("SELECT email FROM cscart_orders WHERE order_id= ?i", $_REQUEST['order_id']);
		//by arpit gaur for email id
		$view->assign('zen_email',$email_id['email']);
        
        /* Using email-id to find customer total order count and unsuccessful order count*/
       
        $total_order_count = db_get_row("select count(order_id) from cscart_orders where email='".$email_id['email']."'");
      
        $unsuccessful_order_count = db_get_row("select count(order_id) from cscart_orders where status in ('i','m','j','r','s','w') AND email= '".$email_id['email']."'");
       
        $successful_order_count = $total_order_count['count(order_id)'] - $unsuccessful_order_count['count(order_id)'];
         
        $success_calculate =  ($successful_order_count/$total_order_count['count(order_id)'])*100;
        
        $view->assign('success_percentage',$success_calculate);
        $view->assign('success_order_count',$successful_order_count);
        $view->assign('total_order_count',$total_order_count['count(order_id)']);
		
		$view->assign('request_order_id',$_REQUEST['order_id']); //by arpit
		
		//var_dump($order_info);
        
        /* Customer success score count End */
       
}


elseif ($mode == 'picker') {
    $_REQUEST['skip_view'] = 'Y';

    list($orders, $search) = fn_get_orders($_REQUEST, Registry::get('settings.Appearance.admin_orders_per_page'));
    $view->assign('orders', $orders);
    $view->assign('search', $search);

    $view->display('pickers/orders_picker_contents.tpl');
    exit;
} elseif ($mode == 'update_status') {

    $order_info = fn_get_order_short_info($_REQUEST['id']);
    $old_status = $order_info['status'];
    if (fn_change_order_status($_REQUEST['id'], $_REQUEST['status'], '', fn_get_notification_rules($_REQUEST))) {
        $order_info = fn_get_order_short_info($_REQUEST['id']);
        $new_status = $order_info['status'];
        if ($_REQUEST['status'] != $new_status) {
            $ajax->assign('return_status', $new_status);
            fn_set_notification('W', fn_get_lang_var('warning'), fn_get_lang_var('status_changed'));
        } else {
            fn_set_notification('N', fn_get_lang_var('notice'), fn_get_lang_var('status_changed'));
        }
    } else {
        fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('error_status_not_changed'));
        $ajax->assign('return_status', $old_status);
    }

    if (empty($_REQUEST['return_url'])) {
        exit;
    } else {
        return array(CONTROLLER_STATUS_REDIRECT, $_REQUEST['return_url']);
    }
} elseif ($mode == 'clone') {

    $sql = "select * from cscart_orders where order_id='" . $params['order_id'] . "'";
    $row = db_get_row($sql);
    $status_new = 'P';
    if ($row['payment_id'] == 6) {
        $status_new = 'Q';
    }
    $sql = "insert into cscart_orders set ";
    $i = 0;
    foreach ($row as $key => $value) {
        $value = str_replace("'", "''", $value);
        if ($key == 'order_id') {
            continue;
        }
        if ($key == 'status') {
            $value = $status_new;
        }
        if ($key == 'timestamp') {
            $value = time();
        }
        if ($key == 'billing_advance_done') {
            $value = 'N';
        }
        if ($key == 'billing_done') {
            $value = 'N';
        }
        if ($i != 0) {
            $sql = $sql . ",";
        }
        $i++;
        $sql = $sql . "`" . $key . "`='" . $value . "'";
    }
    db_query($sql);
    $order_id = mysql_insert_id();
    $sql = "select * from cscart_order_details where order_id='" . $params['order_id'] . "'";
    $arr = db_get_array($sql);
    foreach ($arr as $row) {
        $sql = "insert into cscart_order_details set ";
        $i = 0;
        foreach ($row as $key => $value) {
            $value = str_replace("'", "''", $value);
            if ($key == 'item_id') {
                //continue;
            }
            if ($key == 'order_id') {
                $value = $order_id;
            }
            if ($i != 0) {
                $sql = $sql . ",";
            }
            $i++;
            $sql = $sql . "`" . $key . "`='" . $value . "'";
        }
        db_query($sql);
        //echo $sql."<br>";
    }
    $sql = "select * from cscart_order_data where order_id='" . $params['order_id'] . "'";
    $arr = db_get_array($sql);
    foreach ($arr as $row) {
        $sql = "insert into cscart_order_data set ";
        $i = 0;
        foreach ($row as $key => $value) {
            $value = str_replace("'", "''", $value);
            if ($key == 'order_id') {
                $value = $order_id;
            }
            if ($i != 0) {
                $sql = $sql . ",";
            }
            $i++;
            $sql = $sql . "`" . $key . "`='" . $value . "'";
        }
        db_query($sql);
        //echo $sql."<br>";
    }
    $sql = "select * from clues_nss_order_carrier where order_id='" . $params['order_id'] . "'";
    $arr = db_get_array($sql);
    foreach ($arr as $row) {
        $sql = "insert into clues_nss_order_carrier set ";
        $i = 0;
        foreach ($row as $key => $value) {
            $value = str_replace("'", "''", $value);
            if ($key == 'order_id') {
                $value = $order_id;
            }
            if ($i != 0) {
                $sql = $sql . ",";
            }
            $i++;
            $sql = $sql . "`" . $key . "`='" . $value . "'";
        }
        db_query($sql);
        //echo $sql."<br>";
    }
    $sql = "insert into clues_order_clone_rel set main_order_id='" . $params['order_id'] . "', clone_order_id='" . $order_id . "', auth='" . $auth['user_id'] . "'";
    db_query($sql);
    $sql = "insert into clues_order_history set user_id='" . $auth['user_id'] . "',order_id='" . $order_id . "',from_status='N',to_status='" . $status_new . "',transition_date='" . time() . "',transition_id=0,memo='clone creation'";
    db_query($sql);
    fn_set_notification('N', fn_get_lang_var('notice'), "Clone Created");
    return array(CONTROLLER_STATUS_OK, "orders.details&order_id=" . $order_id);
    exit;
} elseif ($mode == 'deletetag') {
    $sql = "delete from clues_exception_causes_order_rel where id='" . $_REQUEST['id'] . "'";
    db_query($sql);
    exit;
} elseif ($mode == 'addnewtag') {
    $auth = $_SESSION['auth'];
    $tag = $_REQUEST['tag'];
    $tag = str_replace("'", "''", $tag);
    $sql = "insert into clues_exception_causes_list set cause='" . $tag . "', type='Tag',auth='" . $auth['user_id'] . "'";
    db_query($sql);
    $sql = "select id,cause from clues_exception_causes_list where type='Tag'";
    $ret = db_get_array($sql);
    echo "<span style=\"font-size:14px;color:#09C\">Select Tags</span><br />";
    $cnt = 0;
    foreach ($ret as $row) {
        $cnt++;
        echo "<input type=\"checkbox\" name=\"tag_list[]\" id=\"taglist" . $cnt . "\" value=\"" . $row['id'] . "\" />" . $row['cause'] . "<br />";
    }
    exit;
} elseif ($mode == 'causedone') {
    $sql = "update clues_exception_causes_order_rel set latest='0' where id='" . $params['id'] . "'";
    db_query($sql);
    if (isset($_REQUEST['orderpage'])) {
        $str = order_exc_cause_html_ord($params['order_id']);
    } else {
        $str = order_exc_cause_html($params['order_id']);
    }
    echo $str;
    exit;
} elseif ($mode == 'applyexccause') {
    //http://localhost/shopclues_dev/UniTechCity.php?dispatch=orders.applyexccause&exception_cause=&exception_action=7&exception_tag=16%2C17&order_id_cause=1005278%2C987742%2C987747%2C987749%2C987750%2C987751%2C987752%2C987753%2C987755%2C987757%2C987758%2C987760%2C987761%2C987762%2C987763&order_status=I&other_cause=
    $cause = $_REQUEST['exception_cause'];
    $auth = $_SESSION['auth'];
    $action = $_REQUEST['exception_action'];
    $tag = $_REQUEST['exception_tag'];
    $orders = explode(",", $_REQUEST['order_id_cause']);
    $strhtml = '';
    $params['other_cause'] = str_replace("'", "''", $params['other_cause']);
    foreach ($orders as $orderid) {
        $sql = "select status from ?:orders where order_id='" . $orderid . "'";
        $status = db_get_field($sql);
        if ($cause) {
            //$sql = "update clues_exception_causes_order_rel set latest='0' where order_id='". $orderid ."' and type='Cause'";
            //db_query($sql);
            $sql = "insert into clues_exception_causes_order_rel set cause_id='" . $cause . "',order_id='" . $orderid . "',order_status='" . $status . "',type='Cause', latest='1',auth='" . $auth['user_id'] . "'";
            db_query($sql);
        }
        if ($action) {
            //$sql = "update clues_exception_causes_order_rel set latest='0' where order_id='". $orderid ."' and type='Action'";
            //db_query($sql);
            $sql = "insert into clues_exception_causes_order_rel set cause_id='" . $action . "',order_id='" . $orderid . "',order_status='" . $status . "',type='Action', latest='1',auth='" . $auth['user_id'] . "',exc_notes='" . $params['other_cause'] . "'";
            db_query($sql);
        }
        if ($tag) {
            $tags = explode(',', $tag);
            foreach ($tags as $tagid) {
                $sql = "insert into clues_exception_causes_order_rel set cause_id='" . $tagid . "',order_id='" . $orderid . "',order_status='" . $status . "',type='Tag', latest='1',auth='" . $auth['user_id'] . "'";
                db_query($sql);
            }
        }

        /*
          if($_REQUEST['exception_cause']=='other')
          {
          $other = $_REQUEST['other_cause'];
          $other = str_replace("'","''",$other);
          $sql = $sql . ",other='". $other ."'";
          }
         */
        //echo $sql;exit;
        //db_query($sql);
        if (isset($_REQUEST['orderpage'])) {
            $str = order_exc_cause_html_ord($orderid);
        } else {
            $str = order_exc_cause_html($orderid);
        }
        if ($strhtml == '') {
            $strhtml = $orderid . ",hpsepsc," . $str;
        } else {
            $strhtml = $strhtml . ",hpsepsc," . $orderid . ",hpsepsc," . $str;
        }
    }
    echo $strhtml;
    exit;
} elseif ($mode == 'manage') {
    if(defined("COMPANY_ID")){
        if(Registry::get('config.merchant_enable_new_order_screen') == 'Y' && $_REQUEST['ff'] !=1){
            return array(CONTROLLER_STATUS_REDIRECT,"orders.merchants");
        }
    }
    if (isset($params['ff'])) {//by hprahi ff means this is the fulfillment view of orders

        NSS_decision_order_can_be_shipped_with();

        $sql = "select carrier_id, carrier_name from clues_carrier_lookup where status='A' order by carrier_name";
        $ret = db_get_array($sql);
        $view->assign('carriers', $ret);
        $view->assign('region_list', fn_get_all_region());
        $sql = "select fulfillment_id, description from clues_fulfillment_lookup";
        $ret = db_get_array($sql);
        $view->assign('fulfillment_list', $ret);

        $sql = "select id,cause,parents from clues_exception_causes_list where type='Cause'";
        $ret = db_get_array($sql);
        foreach ($ret as $result) {
            if ($result['parents'] == "Not Delivered") {
                $not_del[$result['id']] = $result['cause'];
            } else if ($result['parents'] == "Not Shipped") {
                $not_ship[$result['id']] = $result['cause'];
            } else if ($result['parents'] == "Not Complete") {
                $not_comp[$result['id']] = $result['cause'];
            } else {
                $other[$result['id']] = $result['cause'];
            }
        }
        if (!isset($other)) {
            $other = '';
        }
        $view->assign("not_del", $not_del);
        $view->assign("not_ship", $not_ship);
        $view->assign("not_comp", $not_comp);
        $view->assign("other", $other);

        $view->assign('cause_list', $ret);
        $sql = "select id,cause,parents from clues_exception_causes_list where type='Action'";
        $ret = db_get_array($sql);
        $view->assign('action_list', $ret);
        $sql = "select id,cause from clues_exception_causes_list where type='Tag'";
        $ret = db_get_array($sql);
        $view->assign('tag_list', $ret);

        $ret = db_get_array("select id,title from clues_email_templates");
        $view->assign("em_temp", $ret);

        $sql = "select user_id,user_login,concat(firstname,' ',lastname) name,email from ?:users where user_type='A' and company_id='0' order by name";
        //echo "<pre>";
        $users = db_get_array($sql);
        $view->assign("adminusers", $users);

        $sql = "select p.promotion_id, d.internal_name, d.name, to_date, from_date 
				from cscart_promotions p 
				left join cscart_promotion_descriptions d on p.promotion_id=d.promotion_id order by p.promotion_id desc";
        $promotions = db_get_array($sql);
        $view->assign("promotions", $promotions);
    }
    if (!empty($params['status']) && $params['status'] == STATUS_INCOMPLETED_ORDER) {
        $params['include_incompleted'] = true;
    }

    $params['check_for_suppliers'] = true;
    $params['company_name'] = true;
    /* Add code by paresh on 06-02-2012 */
    if (ACCOUNT_TYPE == 'vendor' && $params['status'] == '') {
        //$status = array('A','B','C','E','G','H','J','K','L','M','O','P');
        //$params['status'] = $status;
        $vendor_status = Registry::get('config.order_statuses_for_merchant');
        $params['status'] = $vendor_status;
    }
    /* End code by paresh on 06-02-2012 */


    list($orders, $search, $totals) = fn_get_orders($params, Registry::get('settings.Appearance.admin_orders_per_page'), true);

    if (!empty($params['include_incompleted']) || !empty($search['include_incompleted'])) {
        $view->assign('incompleted_view', true);
    }

    /* Add Condition by Paresh on 09-02-2012 */
    if (ACCOUNT_TYPE == 'vendor') {
        $view->assign('hide_incompleted', 'hide');
    }
    /* End Condition by Paresh on 09-02-2012 */

    /* Add Condition by Paresh */

    if (!empty($_REQUEST['save_view_results'])) {
        //by ankur to stop sending to detail page from fullfillment's view order


        if (!empty($_REQUEST['redirect_if_one']) && count($orders) == 1 && !isset($_REQUEST['ff'])) {
            return array(CONTROLLER_STATUS_REDIRECT, "orders.details?order_id={$orders[0]['order_id']}");
        }
    } elseif (count($orders) == 1 and !isset($_REQUEST['ff'])) {
        return array(CONTROLLER_STATUS_REDIRECT, "orders.manage");
    }/* END Condition by Paresh */

    /* customer_order_success_rate percentage */
    foreach ($orders as $key => $order) {
        //changes by arpit gaur for shipment created check in alert service
        $cust_result = db_get_array("select * from cscart_shipment_items where order_id='" . $key . "'");
        if (count($cust_result) > 0) {
            //$view->assign('shipment_created','yes');
            $orders[$key]['shipment_created'] = 'yes';
        } else {
            //$view->assign('shipment_created','no');
            $orders[$key]['shipment_created'] = 'no';
        }
        //echo "<pre>";var_dump($order);die;
//changes by arpit gaur ends here


        $total_order_count = db_get_row("select count(order_id) from cscart_orders where email='" . $order['email'] . "'");

        $unsuccessful_order_count = db_get_row("select count(order_id) from cscart_orders where status in ('i','m','j','r','s','w') AND email= '" . $order['email'] . "'");

        $successful_order_count = $total_order_count['count(order_id)'] - $unsuccessful_order_count['count(order_id)'];

        $success_calculate = ($successful_order_count / $total_order_count['count(order_id)']) * 100;

        $orders[$key]['success_calculate'] = $success_calculate;
    }

    $view->assign('orders', $orders);
    $view->assign('search', $search);

    $view->assign('totals', $totals);
    $view->assign('display_totals', fn_display_order_totals($orders));
    $view->assign('shippings', fn_get_shippings(true, CART_LANGUAGE));
} elseif ($mode == 'google') {
    // In this action we loop the script until google data is changed
    if ($action == 'wait_response') {
        $current_time = TIME;
        echo "Waiting for a Google response. Please be patient.";
        fn_flush();
        do {
            echo ' .';
            $google_info_new = db_get_field("SELECT data FROM ?:order_data WHERE order_id = ?i AND type = ?s", $_REQUEST['order_id'], GOOGLE_ORDER_DATA);
            if ($google_info_new != $_SESSION['google_info']) {
                unset($_SESSION['google_info']);
                return array(CONTROLLER_STATUS_REDIRECT, "orders.details?order_id=$_REQUEST[order_id]");
            }
            sleep(1);
        } while (time() - TIME < 59);
        return array(CONTROLLER_STATUS_REDIRECT, "orders.google.wait_response?order_id=$_REQUEST[order_id]");
    }
} elseif ($mode == 'get_custom_file') {
    if (!empty($_REQUEST['file']) && !empty($_REQUEST['order_id'])) {
        //$file_path = DIR_CUSTOM_FILES . 'order_data/' . $_REQUEST['order_id'] . '/' . $_REQUEST['file'];
        $filename = !empty($_REQUEST['filename']) ? $_REQUEST['filename'] : '';
        //$file_path = Registry::get('config.ext_images_host') .'/images/custom_files/sess_data/'.$_REQUEST['file'];
        $file_path = Registry::get('config.ftp_host_image') . '/images/custom_files/order_data/' . $_REQUEST['order_id'] . '/' . basename($_REQUEST['file']);
        fn_get_file($file_path, $filename);

        /* if (file_exists($file_path)) {
          $filename = !empty($_REQUEST['filename']) ? $_REQUEST['filename'] : '';

          fn_get_file($file_path, $filename);
          } */
    }
} elseif ($mode == 'gettemplate') {
    $result = db_get_row("select subject,body,email_from from clues_email_templates where id='" . $_REQUEST['id'] . "'");
    echo $result['subject'] . ',hpsec,' . $result['body'] . ',hpsec,' . $result['email_from'];
    exit;
} elseif ($mode == 'merchants') {
    //this is to redirect to first save search if any
    $views = fn_get_views('orders');
    if (!empty($_REQUEST['first_load'])) {
        if (!empty($views)) {
            $keys = array_keys($views);
            return array(CONTROLLER_STATUS_REDIRECT, "orders.merchants&view_id=" . $keys[0]);
        }
    }
    //code end
    //validate that the particular view id is valid or not
    if (!empty($_REQUEST['view_id'])) {
        if (empty($views)) {
            return array(CONTROLLER_STATUS_DENIED);
        } else {
            $keys = array_keys($views);
            if (!in_array($_REQUEST['view_id'], $keys)) {
                return array(CONTROLLER_STATUS_DENIED);
            }
        }
    }
    //code end
    if (!empty($params['status']) && $params['status'] == STATUS_INCOMPLETED_ORDER) {
        $params['include_incompleted'] = true;
    }


    list($orders, $search, $totals) = fn_get_orders($params, Registry::get('settings.Appearance.admin_orders_per_page'), true);


    if (!empty($params['include_incompleted']) || !empty($search['include_incompleted'])) {
        $view->assign('incompleted_view', true);
    }

    /* Add Condition by Paresh on 09-02-2012 */
    if (ACCOUNT_TYPE == 'vendor') {
        $view->assign('hide_incompleted', 'hide');
    }
    /* End Condition by Paresh on 09-02-2012 */

    /* Add Condition by Paresh */

    if (!empty($_REQUEST['save_view_results'])) {
        //by ankur to stop sending to detail page from fullfillment's view order


        if (!empty($_REQUEST['redirect_if_one']) && count($orders) == 1 && !isset($_REQUEST['ff'])) {
            return array(CONTROLLER_STATUS_REDIRECT, "orders.details?order_id={$orders[0]['order_id']}");
        }
    } elseif (count($orders) == 1 and !isset($_REQUEST['ff'])) {
        return array(CONTROLLER_STATUS_REDIRECT, "orders.details&order_id={$orders[0]['order_id']}");
    }/* END Condition by Paresh */

    /* customer_order_success_rate percentage */
    foreach ($orders as $key => $order) {

        $total_order_count = db_get_row("select count(order_id) from cscart_orders where email='" . $order['email'] . "'");

        $unsuccessful_order_count = db_get_row("select count(order_id) from cscart_orders where status in ('i','m','j','r','s','w') AND email= '" . $order['email'] . "'");

        $successful_order_count = $total_order_count['count(order_id)'] - $unsuccessful_order_count['count(order_id)'];

        $success_calculate = ($successful_order_count / $total_order_count['count(order_id)']) * 100;

        $orders[$key]['success_calculate'] = $success_calculate;
    }
    $view->assign('orders', $orders);
    $view->assign('search', $search);

    $view->assign('totals', $totals);
    $view->assign('display_totals', fn_display_order_totals($orders));
    $view->assign('shippings', fn_get_shippings(true, CART_LANGUAGE));
}
// Add by ajay customer query & new_order_details
elseif ($mode == 'ajax_issues') {//handling the ajax requests
    if (isset($_REQUEST['parent_id']) && !empty($_REQUEST['parent_id'])) {

        if (isset($_REQUEST['text']) && $_REQUEST['text'] != 'other') {
            $subissues = get_sub_issues($_REQUEST['parent_id']);

            if (count($subissues) > 0) {
                //echo '<label for="subissues" class="cont_nl_address cm-required">'.fn_get_lang_var('subissue_type').': <span class="red_astrik">*</span></label>';
                echo '<label for="subissues" class="cm-required"></label>';
                echo '<select  name="subissues" class="round_five profile_detail_field cont_nl_slt_width " id="subissues" style="height:30px; width: 230px; margin:5px 0 0 109px;">';

                echo '<option name="" value="" >Select</option>';

                foreach ($subissues as $subissue) {
                    echo '<option value="' . $subissue['issue_id'] . '">' . $subissue['name'] . '</option>';
                }
                echo '</select>';
            }
            else
                echo "";
        }
        //echo $view->assign('subissues',$subissues);
        die;
    }elseif (isset($_REQUEST['child_id']) && !empty($_REQUEST['child_id'])) {

        //$subissues = db_get_row("select allow_free_text from clues_issues where issue_id=".$_REQUEST['child_id']."");
        $subissues = get_sub_issues($_REQUEST['child_id']);

        if (count($subissues) > 0) {

            //echo '<label for="sub_subissues" class="cont_nl_address cm-required">'.fn_get_lang_var('subissue_type').': <span class="red_astrik">*</span></label>';
            echo '<label for="sub_subissues" class="cm-required"></label>';
            echo '<select  name="sub_subissues"  style="height:30px; width: 230px; margin:5px 0 0 109px;"  class="round_five profile_detail_field cont_nl_slt_width " id="sub_subissues" >';

            echo '<option name="" value="">Select</option>';

            foreach ($subissues as $subissue) {
                echo '<option value="' . $subissue['issue_id'] . '">' . $subissue['name'] . '</option>';
            }
            echo '</select>';
        }
        else
            echo "";

        die;
    }
    elseif (isset($_REQUEST['child_child_id']) && !empty($_REQUEST['child_child_id'])) {
        //$subissues = db_get_row("select allow_free_text from clues_issues where issue_id='".$_REQUEST['child_child_id']."'");
        $subissues = get_sub_issues($_REQUEST['child_child_id']);
        if (count($subissues) > 0) {

            //echo '<label for="sub_sub_subissues" class="cont_nl_address cm-required">'.fn_get_lang_var('subissue_type').': <span class="red_astrik">*</span></label>';
            echo '<label for="sub_sub_subissues" class="cm-required"></label>';
            echo '<select  name="sub_sub_subissues" style="height:30px; width: 230px; margin:5px 0 0 109px;"  class="round_five profile_detail_field cont_nl_slt_width " id="sub_sub_subissues" >';

            echo '<option name="" value="">Select</option>';

            foreach ($subissues as $subissue) {
                echo '<option value="' . $subissue['issue_id'] . '">' . $subissue['name'] . '</option>';
            }
            echo '</select>';
        }
        else
            echo "";
        die;
    }
    elseif (isset($_REQUEST['parent_id']) && empty($_REQUEST['parent_id'])) {  // if parent_id is empty then die
        die;
    }
} elseif ($mode == "error_notify") {
    fn_set_notification('E', '', 'OOPS!! There was some error collecting your request !! Please try again !!');
    return array(CONTROLLER_STATUS_OK, "orders.details");
} elseif ($mode == "export_csv") {
    //exporting csv file
    $filename = 'Export_customer_query.csv';
    $csv_terminated = "\n";
    $csv_separator = ",";
    $csv_enclosed = '"';
    $csv_escaped = "\\";
    $sql_query = "SELECT * FROM clues_customer_queries ORDER BY id DESC LIMIT 0,500";

    $result = db_get_array($sql_query); //var_dump($result); die;
    $result = array_slice($result, 0, 500);
    //echo'<pre>'; print_r($result); die;
    $out = '';
    $out.='S.No., Query ID, Order ID,Product Name,Service Type, Remarks, Follow Up, Status, Date, Customer Name, Customer Contact, Executive Name, Customer Comment, Ticket Number';
    $out.="\n";

    $counter = 0;
    // Format the data
    for ($i = 0; $i < count($result); $i++) {
        $result[$i]['remarks'] = str_replace("\n", " ", $result[$i]['remarks']);
        $result[$i]['remarks'] = str_replace(",", " ", $result[$i]['remarks']);
        $result[$i]['service_type'] = str_replace(",", " ", $result[$i]['service_type']);
        $result[$i]['follow_up'] = str_replace(",", " ", $result[$i]['follow_up']);
        $result[$i]['customer_name'] = str_replace(",", " ", $result[$i]['customer_name']);
        $result[$i]['customer_contact'] = str_replace(",", " ", $result[$i]['customer_contact']);
        $result[$i]['customer_comments'] = str_replace(",", " ", $result[$i]['customer_comments']);
        $result[$i]['ticket_number'] = $result[$i]['ticket_number'];
        $user_name = fn_hp_get_user_name($result[$i]['user_id']);
        $result_date = $result[$i]['date'];

        //$courier_names = fn_hp_get_service_by($result[$i]['order_id']);

        $products = db_get_array("SELECT product_id FROM cscart_order_details where order_id='" . $result[$i]['order_id'] . "'");

    
            //getting courier name
            //if (isset($courier_names[$j]['carrier_name']))
                //$courier = $courier_names[$j]['carrier_name'];

            //getting product name
            $product_name = fn_hp_get_product_name($products[0]['product_id']); //echo print_r($product_name);die;
            if ($product_name == '' || $product_name == null)
                $product_name = ' ';

            //generating text
            $out.=($counter + 1) . ',' . $result[$i]['id'] . ',' . $result[$i]['order_id'] . ',' . $product_name . ',' . $result[$i]['service_type'] . ',' . $result[$i]['remarks'] . ',' . $result[$i]['follow_up'] . ',' . $result[$i]['status'] . ',' . $result_date . ',' . $result[$i]['customer_name'] . ',' . $result[$i]['customer_contact'] . ',' . $user_name . ',' . $result[$i]['customer_comments'] . ',' . $result[$i]['ticket_number'];
            $out.="\n";
            $counter+=1;
        
    }
    //echo'<pre>'; print_r($out); die;


    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Length: " . strlen($out));
    // Output to browser with appropriate mime type, you choose <img src="http://thetechnofreak.com/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
    header("Content-type: text/x-csv");
    //header("Content-type: text/csv");
    //header("Content-type: application/csv");
    header("Content-Disposition: attachment; filename=$filename");
    echo $out;
    exit;
}


function select_parent_issues() {

    $issues = db_get_array("select name , issue_id,allow_free_text from clues_issues where parent_issue_id=0 and type='C'");

    return $issues;
}

function get_sub_issues($parent_id) {

    $subissues = db_get_array("select name,issue_id,allow_free_text from clues_issues where parent_issue_id=" . $parent_id . " and type = 'C'");

    return $subissues;
}

function db_get_zendesk_code($id) {
    $sql = "select clues_issues.`desc` from clues_issues where issue_id='$id'";
    $result = db_get_row($sql);
    $result1 = explode('|', $result['desc']);
    return $result1[0]; //returning the description
}

function db_get_group($id) {
    $sql = "select clues_issues.`desc` from clues_issues where issue_id='$id'";
    $result = db_get_row($sql);
    $result1 = explode('|', $result['desc']);
    return $result1[1]; //returning the group
}

function db_get_assignee($id) {
    $sql = "select clues_issues.`desc` from clues_issues where issue_id='$id'";
    $result = db_get_row($sql);
    $result1 = explode('|', $result['desc']);
    return $result1[2]; //returning the assignee
}

// End by ajay customer query and new_order_details
// Calculate gross total and totally paid values for the current set of orders
//
function fn_display_order_totals($orders) {
    $result = array();
    $result['gross_total'] = 0;
    $result['totally_paid'] = 0;

    if (is_array($orders)) {
        foreach ($orders as $k => $v) {
            $result['gross_total'] += $v['total'];
            if ($v['status'] == 'C' || $v['status'] == 'P') {
                $result['totally_paid'] += $v['total'];
            }
        }
    }

    return $result;
}

function fn_google_send_order_command($post, $processor_data, $request_url, $action, $order_id) {
    $_id = base64_encode($processor_data['params']['merchant_id'] . ":" . $processor_data['params']['merchant_key']);
    $headers[] = "Authorization: Basic $_id";
    $headers[] = "Accept: application/xml ";

    list($a, $return) = fn_https_request('POST', $request_url, $post, '', '', 'application/xml', '', '', '', $headers);

    preg_match("/<error-message>(.*)<\/error-message>/", $return, $error);

    if (!empty($error[1])) {
        fn_set_notification('E', fn_get_lang_var('notice'), $error[1]);
    } else {
        if (in_array($action, array('refund', 'cancel', 'deliver'))) {
            $_SESSION['google_info'] = db_get_field("SELECT data FROM ?:order_data WHERE order_id = ?i AND type = ?s", $order_id, GOOGLE_ORDER_DATA);
            echo "Request is successfully sent.<br />";
            echo "Waiting for a Google response. Please be patient.";
            return array(CONTROLLER_STATUS_OK, "orders.google.wait_response?order_id=$order_id");
        }
        fn_set_notification('N', fn_get_lang_var('notice'), str_replace('[action]', fn_get_lang_var($action), fn_get_lang_var('google_request_sent')));
    }

    return true;
}

//function by ankur to create jasper report for order gift message
function javabridge_order_gift_message($order_ids) {
    if (is_array($order_ids))
        $order_id = implode(",", $order_ids);
    else
        $order_id = $order_ids;

    $jrxml = "order_gift_message.jrxml";
    $url = Registry::get('config.javapdfurl') . 'screports1/Create';
    $outputFileName = 'order-gift-message' . date('Y-m-d_H-i-s') . '.pdf';
    $str = "filename=" . $outputFileName . "&" . "params=order_ids:" . $order_id . "&jrxmlfile=" . $jrxml;
    //echo $str;exit;
    $j = 1;
    while ($j < 4) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 3);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $str);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
        $result = curl_exec($ch);
        curl_close($ch);
        //var_dump( $outputFileName);exit;
        if ($result) {
            javabridge_download(Registry::get('config.javapdfurl') . "ir/pdf/" . $outputFileName, $outputFileName);
            exit;
        }
        //echo $j;
        $j++;
    }
    echo "Please Retry";
    exit;
}

//code by arpit gaur for shipment history *
function get_shipment_history($tracking_no, $order_id) {
    $query = "SELECT status_update_date, sc_status, carrier_status, receiver_name, memo FROM clues_shipment_tracking_center WHERE order_id='" . $order_id . "' AND awbno='" . $tracking_no . "' order by id desc";
    return $result = db_get_array($query);
}

//code by arpit gaur ends here



function fn_get_carrier_priority($carrid) {
    if ($carrid != 0) {
        $query = "SELECT preference FROM clues_advance_awb_preference WHERE company_id=" . $_SESSION['auth']['company_id'] . " AND carrier_id=" . $carrid . "";
        return db_get_field($query);
    } else {
        $query = "SELECT preference FROM clues_advance_awb_preference WHERE company_id=" . $_SESSION['auth']['company_id'] . "";
        return db_get_field($query);
    }
}

function fn_get_default_carrier_count($carrid) {
    if ($carrid != 0) {
        $query = "SELECT DISTINCT count(carrier_id) FROM clues_advance_awb_preference WHERE company_id=" . $_SESSION['auth']['company_id'];
        return db_get_field($query);
    }
}

function fn_get_carrier_selection() {
    $query = "SELECT carrier_id FROM clues_advance_awb_preference WHERE company_id=" . $_SESSION['auth']['company_id'] . " AND payment_type='prepaid' AND preference=1";
    $prefer_pp = db_get_field($query);
    if ($prefer_pp != "") {
        return $prefer_pp;
    } else {
        $query = "SELECT carrier_id FROM clues_advance_awb_preference WHERE company_id=" . $_SESSION['auth']['company_id'] . " AND payment_type='cod' AND preference=1";
        $prefer_cod = db_get_field($query);
        return $prefer_cod;
    }
}

function get_order_product_details_for_merchant($order_id) {
    $sql = "SELECT cod.product_id,cod.extra,cpd.product from cscart_order_details as cod
			  INNER JOIN cscart_product_descriptions as cpd
			  ON cod.product_id = cpd.product_id
			  WHERE cod.order_id ='" . $order_id . "' ";
    $result = db_get_array($sql);
    foreach ($result as $order_item) {
        $items[$order_item['product_id']]['product'] = $order_item['product'];
        $extra = unserialize($order_item['extra']);
        //for order's product option
        $items[$order_item['product_id']]['product_options'] = !empty($extra['product_options_value']) ? $extra['product_options_value'] : fn_get_selected_product_options_info($extra['product_options_value'], 'EN');
    }
    return $items;
}

?>
