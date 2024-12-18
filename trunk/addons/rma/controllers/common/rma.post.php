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
// $Id: rma.post.php 12865 2011-07-05 06:57:22Z 2tl $
//

if ( !defined('AREA') )	{ die('Access denied');	}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && (isset($_SESSION['form_token_value']) && $_REQUEST['token'] != $_SESSION['form_token_value']) && !isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('form_token_not_matched'));
    return array(CONTROLLER_STATUS_OK, $_SERVER['HTTP_REFERER']);
}else{
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && !isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
        $token = md5(Registry::get('config.http_host').Registry::get('config.session_salt').time());
        $_SESSION['form_token_value'] = $token;
    }
}

/* POST data processing */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
    if ($mode == 'add_return') {

		if (!empty($_REQUEST['returns'])) {
			//echo"<pre>";print_r($_REQUEST);die;
			$order_id = intval($_REQUEST['order_id']);
			$oder_lang_code = db_get_field("SELECT lang_code FROM ?:orders WHERE order_id = ?i", $order_id);
			$returns = (array) $_REQUEST['returns'];
			$user_id = intval($_REQUEST['user_id']);
			$action = $_REQUEST['action'];
            $comment = addslashes($_REQUEST['comment']);
			$total_amount = 0;
			foreach ($returns as $k => $v) {
				$reason = $v['reason'];
				if (isset($v['chosen']) && $v['chosen'] == 'Y') {
					$total_amount += $v['amount'];
				}
			}
			
			//By ajay
			$apv_req = $_REQUEST['apv_req'];
			$sc_apv_req = $_REQUEST['sc_apv_req'];
			$mc_apv_req = $_REQUEST['mc_apv_req'];
			$cust_msg = $_REQUEST['cust_msg'];
			$picture_req = $_REQUEST['picture_req'];
			$account_holder_name= $_REQUEST['username'];
			$account_no = $_REQUEST['account_no'];
                        $ifsc_code = $_REQUEST['ifsc_code'];
                        $bank_branch = $_REQUEST['bank_branch'];
                        $bank_type = $_REQUEST['bank_type'];
                        $bank_name = $_REQUEST['bank_name'];
                        if($_REQUEST['bank_name'] == 'others')
                        {
                           $bank_name = $_REQUEST['other_bank']; 
                        }
			
			//$picture_req = $_REQUEST['picture_req'];
		if(isset($_FILES["product_pic"])){
			if($_FILES["product_pic"]['name'] != '') {
			    if (isset($_FILES["product_pic"]) && ($_FILES["product_pic"]["size"] < 262144)) {
				    $product_pic_url = "images/refund_product_images/".$_FILES["product_pic"]["name"];
				    $filename = $_FILES["product_pic"]["name"];
                                    $filename = str_replace(' ', '-', $filename);
                                    $filename = preg_replace('/[^a-zA-Z0-9.\s]/', '-', $filename);
				$fn = explode(".",$_FILES["product_pic"]["name"]);
				if(empty($fn['1']) || $fn['1'] =='GIF' || $fn['1'] =='gif' || $fn['1'] =='jpeg' || $fn['1'] =='JPEG' || $fn['1'] =='jpg' || $fn['1'] =='JPG' || $fn['1'] =='png' || $fn['1'] =='PNG'){

				    move_uploaded_file($_FILES["product_pic"]["tmp_name"], "images/refund_product_images/" . $filename);

					$local = Registry::get('config.loc_img'). "refund_product_images/". $filename;
					$remote =  Registry::get('config.remote_img');
					$parameter = Registry::get('config.rsync_parameter');
					$rsyn = exec("rsync $parameter $local $remote &");
				}else{
					fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('invalid_image_type'));
					return array(CONTROLLER_STATUS_REDIRECT, "rma.create_return&order_id=" . $order_id);
				}
			    } else {
				fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('file_size_too_large_should_be_less_than_256KB'));
				return array(CONTROLLER_STATUS_REDIRECT, "rma.create_return&order_id=" . $order_id);
				exit();
			}
		}
}
			 if($_REQUEST['refund_mode']!=''){
			$refund_mode = $_REQUEST['refund_mode'];
			if($refund_mode == ''){
				$refund_in_cb = 'N';
				$refund_in_ac = 'N';
			}
                       if($refund_mode == 'refund_in_cb'){
				$refund_in_cb = 'Y';
				$refund_in_ac = 'N';
			}
			if($refund_mode == 'refund_in_ac'){
				$refund_in_ac = 'Y';
				$refund_in_cb = 'N';
			}
}else{
                      $refund_mode= $_REQUEST['refund_mode_cod'];
                      if($refund_mode == 'refund_in_cb'){
				$refund_in_cb = 'Y';
				$refund_in_ac = 'N';
			}
			if($refund_mode == 'refund_in_ac'){
				$refund_in_ac = 'Y';
				$refund_in_cb = 'N';
			}
                        if($refund_mode == ''){
				$refund_in_cb = 'N';
				$refund_in_ac = 'N';
			}
}
            $shipping_mode = $_REQUEST['shipping_mode'];
             if($shipping_mode == ''){
				$cust_will_send_product = 'N';
				$pick_up_frm_my_shipping_add = 'N';
			}
			
            if($shipping_mode == 'cust_will_send_product'){
				$cust_will_send_product = 'Y';
				$pick_up_frm_my_shipping_add = 'N';
			}
			if($shipping_mode == 'pick_up_frm_my_shipping_add'){
				$pick_up_frm_my_shipping_add = 'Y';
				$cust_will_send_product = 'N';
			}
		
        //end by ajay
			
			    if($apv_req=='Y' || $sc_apv_req=='Y' || $mc_apv_req=='Y')
        {
                                //if($_REQUEST['payment_id'] == 6 && $_REQUEST['refund_mode_cod'] == 'refund_in_ac')
                                
			$_data = array(
				'order_id' => $order_id,
				'user_id' => $user_id,
				'action' => $action,
				'timestamp' => TIME,
				'status' => RMA_DEFAULT_STATUS,
				'total_amount' => $total_amount,
				'comment' => $comment,
				'refund_in_ac' => $refund_in_ac,
				'refund_in_cb' => $refund_in_cb,
				'cust_will_send_product' => $cust_will_send_product,
				'pick_up_frm_my_shipping_add' => $pick_up_frm_my_shipping_add,
				'product_pic_url' => $product_pic_url
			);
		}else{
			$status='A';
			$_data = array(
				'order_id' => $order_id,
				'user_id' => $user_id,
				'action' => $action,
				'timestamp' => TIME,
				'status' => $status,
				'total_amount' => $total_amount,
				'comment' => $comment,
				'refund_in_ac' => $refund_in_ac,
				'refund_in_cb' => $refund_in_cb,
				'cust_will_send_product' => $cust_will_send_product,
				'pick_up_frm_my_shipping_add' => $pick_up_frm_my_shipping_add,
				'product_pic_url' => $product_pic_url
			);
		}
		    //echo"<pre>";print_r($_data);die;
              
			$return_id = db_query('INSERT INTO ?:rma_returns ?e', $_data);
                
			$order_items = db_get_hash_array("SELECT item_id, order_id, extra, price, amount FROM ?:order_details WHERE order_id = ?i", 'item_id', $order_id);
		
			foreach ($returns as $item_id => $v) {
				if (isset($v['chosen']) && $v['chosen'] == 'Y') {
					if (true == fn_rma_declined_product_correction($order_id, $k, $v['available_amount'], $v['amount'])) {
						$_item = $order_items[$item_id];
						$extra = @unserialize($_item['extra']);
						$_data = array (
							'return_id' => $return_id,
							'item_id' => $item_id,
							'product_id' => $v['product_id'],
							'reason' => $v['reason'],
							'amount' => $v['amount'],
							'product_options' => !empty($extra['product_options_value']) ? serialize($extra['product_options_value']) : '',
							'price' => fn_format_price((((!isset($extra['exclude_from_calculate'])) ? $_item['price'] : 0) * $_item['amount']) / $_item['amount']),
							'product' => !empty($extra['product']) ? $extra['product'] : fn_get_product_name($v['product_id'], $oder_lang_code)
						); 

						db_query('INSERT INTO ?:rma_return_products ?e', $_data);

						if (!isset($extra['returns'])) {
							$extra['returns'] = array();
						}
						$extra['returns'][$return_id] = array(
							'amount' => $v['amount'],
							'status' => RMA_DEFAULT_STATUS
						);
						db_query('UPDATE ?:order_details SET ?u WHERE item_id = ?i AND order_id = ?i', array('extra' => serialize($extra)), $item_id, $order_id);
					}
				}
			}
                
			$return_info = fn_get_return_info($return_id);
			$order_info = fn_get_order_info($order_id);
			$view->assign('order_info',$order_info);
			
		    //echo"<pre>";print_r($return_info);die;
		  //by ajay to create ticket at zendesk on customer return request
		  $items=db_get_array("select product_id, price,amount,extra from cscart_order_details where order_id='".$order_id."'");
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
												  
				//shipment info
                $shipment=db_get_row("SELECT cst.order_id, cst.shipment_id, cs.tracking_number, cs.carrier,
                                 Date_format(Date(from_unixtime(cs.timestamp)),'%d-%M-%Y') as date
                                 FROM `cscart_shipment_items` cst
                                 INNER JOIN cscart_shipments cs ON cs.shipment_id = cst.shipment_id
                                 WHERE cst.order_id ='".$order_id."'");
                                 
                $courier = $shipment['carrier'];  
				$tracking_number = $shipment['tracking_number']; 
				$shipment_date = $shipment['date'];                
                $cust_details = db_get_row("SELECT CONCAT(s_firstname, ' ', s_lastname) as name,email,phone,status FROM cscart_orders where order_id='".$order_id."' ");                 
                
                $mydate = time();
		        $qrydate = date("Y-m-d H:i:s", $mydate);
		        $cust_q_service_type = "Post Delivery Issues";
		        
		        $action_name = db_get_field("select property from cscart_rma_property_descriptions where property_id='". $_REQUEST['action'] ."' "); 
		        $reason_name = db_get_field("select property from cscart_rma_property_descriptions where property_id='". $reason ."' "); 
                // End shipment info  
                
				//creating the zendesk ticket using the zendesk api
				require(DIR_ROOT.'/contact_us_api_calls_files/ticket_create.php');

                fn_set_notification('N', '', "Thank you for writing to us. An email has been sent to our support team with a copy to you on <b>".$cust_details['email']." </b> .
                                     We try our best to respond to you within 48 hours. 
                                     your request number is <b>".$reqt_no."</b>.");
                                     
               $cust_q_query = "INSERT INTO clues_customer_queries (order_id, user_id, customer_name, customer_contact, customer_email, date, service_type, customer_comments, ticket_number, status, issue_id, ticket_channel, `group`, assignee) VALUES ('" . $order_id . "', '" . $user_id . "', '" . $cust_details['name'] . "', '" . $cust_details['phone'] . "', '" . $cust_details['email'] . "', '" . $qrydate . "', '" . $cust_q_service_type . "', '" . $comment . "', '" . $reqt_no . "', '" . $cust_details['status'] . "', '78', 'email', 'Delivered', 'Returns-Lead')";
               db_query($cust_q_query);
                if($_REQUEST['payment_id'] == 6 && $_REQUEST['refund_mode_cod'] == 'refund_in_ac')
                {   
                        $return_id_created = "INSERT INTO clues_rma_neft_details (`return_id`,`order_id`, `account_holder_name`, `bank_id`, `account_no`, `ifsc_code`, `bank_branch`, `bank_account_type`) "
                                . "   VALUES ('". $return_id ."','" . $order_id . "', '".$account_holder_name."','".$bank_name."','".$account_no."','".$ifsc_code."','".$bank_branch."','". $bank_type ."')";
                
                        //echo $return_id; die; 
                        db_query($return_id_created);
                }
          //end by ajay
			
			
			//send email to support@shopclues.com
				
				Registry::get('view_mail')->assign('order_info', $order_info);
				Registry::get('view_mail')->assign('return_info', $return_info);
				Registry::get('view_mail')->assign('reasons', fn_get_rma_properties(RMA_REASON));
				Registry::get('view_mail')->assign('actions', fn_get_rma_properties(RMA_ACTION));
				
				/* Hide send mail functionality by ajay
				 
						$email_subj = "Return requested for ShopClues order ".$order_info['order_id'];
						
						Registry::get('view_mail')->assign('email_subj', $email_subj);
						
						//fn_send_mail("support@shopclues.com",$order_info['email'], 'return_status/return_notification_subj.tpl', 'return_status/return_notification_support.tpl','','EN');
											
										fn_instant_mail("support@shopclues.com",$order_info['email'],'return_status/return_notification_subj.tpl', 'return_status/return_notification_support.tpl');
								//fn_send_instant_mail();
								//send email to support@shopclues.com end
					
					//fn_send_return_mail($return_info, $order_info, array('C' => true, 'A' => true, 'S' => true));
					fn_send_return_mail($return_info, $order_info, array('C' => false, 'A' => true, 'S' => true));
			
			*/
			
			if($cust_msg!= ""){
				fn_set_notification('N', '', $cust_msg);
			}
		}
		return array(CONTROLLER_STATUS_OK, "rma.details?return_id=$return_id");
	}
}

if (empty($auth['user_id']) && !isset($auth['order_ids']) && AREA == 'C') {
	return array(CONTROLLER_STATUS_REDIRECT, "auth.login_form?return_url=" . urlencode(Registry::get('config.current_url')));
}

if ($mode == 'details' && !empty($_REQUEST['return_id'])) 
{       $account_info = db_get_row("select return_id from clues_rma_neft_details where return_id=".$_REQUEST['return_id']);
	$shippings = db_get_array("SELECT  b.shipping FROM ?:shippings as a LEFT JOIN ?:shipping_descriptions as b ON a.shipping_id = b.shipping_id AND b.lang_code = ?s WHERE a.status = ?s ORDER BY a.position", DESCR_SL, 'A');
	$view->assign('shippings', $shippings);
	$view->assign('account_info',$account_info);
	
	$return_id = intval($_REQUEST['return_id']);
	/// Added By Sudhir
	$query = db_get_array("SELECT user_id, order_id, status_from, status_to, return_id, comment, datetime FROM clues_rma_history WHERE return_id = '".$return_id."'");
	$view->assign('rma_history', $query);
	/// Added by Sudhir end here

	// [Breadcrumbs]
	if (AREA != 'A') {
		fn_add_breadcrumb(fn_get_lang_var('return_requests'), "rma.returns");
		fn_add_breadcrumb(fn_get_lang_var('return_info'));
	} else {
		fn_add_breadcrumb(fn_get_lang_var('return_requests'), "rma.returns.reset_view");
		fn_add_breadcrumb(fn_get_lang_var('search_results'), "rma.returns.last_view");
	}
	// [/Breadcrumbs]


	Registry::set('navigation.tabs', array (
		'return_products' => array (
			'title' => fn_get_lang_var('return_products_information'),
			'js' => true
		),
		'declined_products' => array (
			'title' => fn_get_lang_var('declined_products_information'),
			'js' => true
		),
	));

	$return_info = fn_get_return_info($return_id);
	//echo"<pre>";print_r($order_id);die;
	
	//added by ajay for remove slashes from comments
	$comment = stripslashes($return_info['comment']);
	$view->assign('comment', $comment);
	//end by ajay
    
	if ((AREA == 'C') && (empty($return_info) || $return_info['user_id'] != $auth['user_id'])) {
		return array(CONTROLLER_STATUS_DENIED);
	}

	if (AREA == 'A') {
		Registry::set('navigation.tabs.comments', array (
			'title' => fn_get_lang_var('comments'),
			'js' => true
		));
		Registry::set('navigation.tabs.actions', array (
			'title' => fn_get_lang_var('actions'),
			'js' => true
		));/// added by anoop to create shipment tab
		Registry::set('navigation.tabs.shipment', array (
			'title' => fn_get_lang_var('shipment'),
			'js' => true
		));/////added by anoop end

		$view->assign('is_refund', fn_is_refund_action($return_info['action']));
		$view->assign('order_info', fn_get_order_info($return_info['order_id']));
	}
	$return_info['extra'] = unserialize($return_info['extra']);
	
	if (!is_array($return_info['extra'])) {
		$return_info['extra'] = array();
	}
    $order_id = $return_info['order_id'];
	$order_info = fn_get_order_info($order_id);
	$view->assign('order_info',$order_info);
	$view->assign('reasons', fn_get_rma_properties( RMA_REASON ));
	$view->assign('actions', fn_get_rma_properties( RMA_ACTION ));
	
	$sql="SELECT return_id FROM cscart_shipment_items where return_id=".$return_info['return_id'];
	$ret=db_get_array($sql);
	if(count($ret) > 0)
	{
		$view->assign('record_exits',1);
	}
	else
	{
		$view->assign('record_exits',0);
	}
	$view->assign('return_info', $return_info);

}
 elseif ($mode == 'print_slip' && !empty($_REQUEST['return_id'])) {

	$return_id = intval($_REQUEST['return_id']);
	$return_info = fn_get_return_info($return_id);
        
        ///////// pdf download start //////////
        
        $jrxml = "rma_return.jrxml";
	
	$url = Registry::get('config.javapdfurl').'screports1/Create';
	
	$outputFileName = 'ShopClues_RMA_Details_'.$return_id.'.pdf';
	
	$str = "filename=".$outputFileName."&"."params=order_ids:".$return_info['order_id']."&jrxmlfile=".$jrxml;
	$j=1;
	while($j<4) 
	{	
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_POST,3);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$str);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,60);
		$result = curl_exec($ch);
		curl_close($ch);
		//echo $result;
		if($result)
		{
			if($save=='save'){
				javabridge_save(Registry::get('config.javapdfurl')."ir/pdf/".$outputFileName,$outputFileName);
				return $outputFileName;
			}
			else
			{
				javabridge_download(Registry::get('config.javapdfurl')."ir/pdf/".$outputFileName,$outputFileName);
				exit;
			}
		}
		//echo $j;
		$j++;
	}
	echo "Please Retry";
	exit;
       
        /////// pdf download end //////////////
        

	if ((AREA == 'C') && (empty($return_info) || $return_info['user_id'] != $auth['user_id'])) {
		return array(CONTROLLER_STATUS_DENIED);
	}
	
	$order_info = fn_get_order_info($return_info['order_id']);

	if (empty($return_info) || empty($order_info)) {
		return array(CONTROLLER_STATUS_NO_PAGE);
	}

	$view_mail->assign('reasons', fn_get_rma_properties( RMA_REASON ));
	$view_mail->assign('actions', fn_get_rma_properties( RMA_ACTION ));
	$view_mail->assign('return_info', $return_info);
	$view_mail->assign('order_info', $order_info);

	$view_mail->display('addons/rma/print_slip.tpl');
	exit;

} elseif ($mode == 'returns') {

	// [Breadcrumbs]
	if (AREA != 'A') {
		fn_add_breadcrumb(fn_get_lang_var('return_requests'));
	}
	// [/Breadcrumbs]

	$params = $_REQUEST;
	//
	if (AREA == 'C') {
		$params['user_id'] = $auth['user_id'];
		if (!empty($auth['order_ids'])) {
			$params['order_ids'] = $auth['order_ids'];
		}
	}

	list($return_requests, $search) = fn_get_rma_returns($params);
	//echo "<pre>"; print_r($return_requests);die;
	//$ship_data = db_get_row("SELECT carrier, shipment_id FROM cscart_");
	//for getting the product name and quantity code by ankur
	//echo "<pre>"; print_r($return_requests);die;
	foreach($return_requests as $k=>$order_detail)
	{
		$order_info[]=fn_get_order_info($order_detail['order_id']);
	}

	$orders_item=array();
	if(!empty($order_info))
	{
		foreach($order_info as $order_detail)
		{
			$orders_item[]=$order_detail['items'];
		}
	}
	$view->assign('product_info',$orders_item);
	//end
	
	$view->assign('return_requests', $return_requests);
	$view->assign('search', $search);

	fn_rma_generate_sections('requests');

	$view->assign('actions', fn_get_rma_properties(RMA_ACTION));

} elseif ($mode == 'create_return' && !empty($_REQUEST['order_id'])) {
	$order_id = intval($_REQUEST['order_id']);
        $bank_name = "SELECT payment_option_id,name,status FROM `clues_payment_options`  where payment_type_id=1
        ORDER BY `clues_payment_options`.`status`,`clues_payment_options`.`name`  ASC";
        $bank_data = db_get_array($bank_name);
        //echo "<pre>";print_r($bank_data); die;
        $view->assign('bank_names', $bank_data);
	// [Breadcrumbs]
	fn_add_breadcrumb(fn_get_lang_var('order').' '.$order_id, "orders.details?order_id=$order_id");
	if (AREA != 'A') {
		fn_add_breadcrumb(fn_get_lang_var('return_registration'));
	}
	// [/Breadcrumbs]
        
	$order_info = fn_get_order_info($order_id);
        
        if (AREA != 'A') {
		$order_returnable_products = fn_get_order_returnable_products($order_info['items'], $order_info['products_delivery_date']);
	}
	else{ 
		if($order_info['status'] == 'C' || $order_info['status'] == 'H'){
	         $order_returnable_products = fn_get_order_returnable_products($order_info['items'], '1');   
                }
		else{
	         $order_returnable_products = fn_get_order_returnable_products($order_info['items'], $order_info['products_delivery_date']);
		}	
	}	
        
        $order_info['items'] = $order_returnable_products['items'];

        if (AREA != 'A') {
            if (!isset($order_info['allow_return'])) {
		return array(CONTROLLER_STATUS_DENIED);
            }  
        }
        else{
            if (!isset($order_info['allow_return']) && $order_info['status'] != 'C' && $order_info['status'] != 'H') {
                	return array(CONTROLLER_STATUS_DENIED);
            }
        }
        
	$view->assign('order_info', $order_info);
	$view->assign('reasons', fn_get_rma_properties( RMA_REASON ));
	$view->assign('actions', fn_get_rma_properties( RMA_ACTION ));
}

function fn_get_rma_returns($params, $items_per_page = 0)
{
	// Init filter
	$params = fn_init_view('rma', $params);

	// Set default values to input params
	$params['page'] = empty($params['page']) ? 1 : $params['page'];

	// Define fields that should be retrieved
	$fields = array (
		'DISTINCT ?:rma_returns.return_id',
		'?:rma_returns.order_id',
		'?:rma_returns.timestamp',
		'?:rma_returns.status',
		'?:rma_returns.total_amount',
		'?:rma_property_descriptions.property AS action',
		'?:users.firstname',
		'?:users.lastname',
		//'?:rma_shipments.shipment_id',
		//'?:rma_shipments.carrier'
	);

	// Define sort fields
	$sortings = array (
		'return_id' => "?:rma_returns.return_id",
		'timestamp' => "?:rma_returns.timestamp",
		'order_id' => "?:rma_returns.order_id",
		'status' => "?:rma_returns.status",
		'amount' => "?:rma_returns.total_amount",
		'action' => "?:rma_returns.action",
		'customer' => "?:users.lastname",
		//'shipment_id' => "?:rma_shipments.shipment_id",
		//'carrier' => "?:rma_shipments.carrier"
	);

	$directions = array (
		'asc' => 'asc',
		'desc' => 'desc'
	);
	//echo "<pre>";print_r($params);die;
	if (empty($params['sort_order']) || empty($directions[$params['sort_order']])) {
		$params['sort_order'] = 'desc';
	}

	if (empty($params['sort_by']) || empty($sortings[$params['sort_by']])) {
		$params['sort_by'] = 'timestamp';
	}

	$sort = $sortings[$params['sort_by']] . " " . $directions[$params['sort_order']];

	// Reverse sorting (for usage in view)
	$params['sort_order'] = $params['sort_order'] == 'asc' ? 'desc' : 'asc';

	$join = $condition = $group = '';

	if (isset($params['cname']) && fn_string_no_empty($params['cname'])) {
		$arr = fn_explode(' ', $params['cname']);
		foreach ($arr as $k => $v) {
			if (!fn_string_no_empty($v)) {
				unset($arr[$k]);
			}
		}
		if (sizeof($arr) == 2) {
			$condition .= db_quote(" AND ?:users.firstname LIKE ?l AND ?:users.lastname LIKE ?l", "%".array_shift($arr)."%", "%".array_shift($arr)."%");
		} else {
			$condition .= db_quote(" AND (?:users.firstname LIKE ?l OR ?:users.lastname LIKE ?l)", "%".trim($params['cname'])."%", "%".trim($params['cname'])."%");
		}
	}

	if (isset($params['email']) && fn_string_no_empty($params['email'])) {
		$condition .= db_quote(" AND ?:users.email LIKE ?l", "%".trim($params['email'])."%");
	}

	if (isset($params['rma_amount_from']) && fn_is_numeric($params['rma_amount_from'])) {
		$condition .= db_quote("AND ?:rma_returns.total_amount >= ?d", $params['rma_amount_from']);
	}

	if (isset($params['rma_amount_to']) && fn_is_numeric($params['rma_amount_to'])) {
		$condition .= db_quote("AND ?:rma_returns.total_amount <= ?d", $params['rma_amount_to']);
	}

	if (!empty($params['action'])) {
		$condition .= db_quote(" AND ?:rma_returns.action = ?s", $params['action']);
	}

	if (!empty($params['return_id'])) {
		$condition .= db_quote(" AND ?:rma_returns.return_id = ?i", $params['return_id']);
	}

	if (!empty($params['request_status'])) {
		$condition .= db_quote(" AND ?:rma_returns.status IN (?a)", $params['request_status']);
	}

	if (!empty($params['period']) && $params['period'] != 'A') {
		list($params['time_from'], $params['time_to']) = fn_create_periods($params);
		$condition .= db_quote(" AND (?:rma_returns.timestamp >= ?i AND ?:rma_returns.timestamp <= ?i)", $params['time_from'], $params['time_to']);
	}

	if (!empty($params['order_id'])) {
		$condition .= db_quote(" AND ?:rma_returns.order_id = ?i", $params['order_id']);
	}

	if (isset($params['user_id'])) {
		$condition .= db_quote(" AND ?:rma_returns.user_id = ?i", $params['user_id']);
	}

	if (!empty($params['order_status'])) {
		$condition .= db_quote(" AND ?:orders.status IN (?a)", $params['order_status']);
	}

	if (!empty($params['p_ids']) || !empty($params['product_view_id'])) {
		$arr = (strpos($params['p_ids'], ',') !== false || !is_array($params['p_ids'])) ? explode(',', $params['p_ids']) : $params['p_ids'];
		if (empty($params['product_view_id'])) {
			$condition .= db_quote(" AND ?:order_details.product_id IN (?n)", $arr);
		} else {
			$condition .= db_quote(" AND ?:order_details.product_id IN (?n)", db_get_fields(fn_get_products(array('view_id' => $params['product_view_id'], 'get_query' => true))));
		}

		$join .= " LEFT JOIN ?:order_details ON ?:order_details.order_id = ?:orders.order_id";
		$group .=  db_quote(" GROUP BY ?:rma_returns.return_id HAVING COUNT(?:orders.order_id) >= ?i", count($arr));
	}

	if (empty($items_per_page)) {
		$items_per_page = Registry::get('settings.Appearance.' . ((AREA == 'A') ? 'admin_elements_per_page' : 'elements_per_page'));
	}

	$total = db_get_field("SELECT COUNT(DISTINCT ?:rma_returns.return_id) FROM ?:rma_returns LEFT JOIN ?:rma_return_products ON ?:rma_return_products.return_id = ?:rma_returns.return_id LEFT JOIN ?:rma_property_descriptions ON ?:rma_property_descriptions.property_id = ?:rma_returns.action LEFT JOIN ?:users ON ?:rma_returns.user_id = ?:users.user_id LEFT JOIN ?:orders ON ?:rma_returns.order_id = ?:orders.order_id $join WHERE 1 $condition $group");

	$limit = fn_paginate($params['page'], $total, $items_per_page); // FIXME
	
	$return_requests = db_get_array("SELECT " . implode(', ', $fields) . " FROM ?:rma_returns LEFT JOIN ?:rma_return_products ON ?:rma_return_products.return_id = ?:rma_returns.return_id LEFT JOIN ?:rma_property_descriptions ON (?:rma_property_descriptions.property_id = ?:rma_returns.action AND ?:rma_property_descriptions.lang_code = ?s) LEFT JOIN ?:users ON ?:rma_returns.user_id = ?:users.user_id LEFT JOIN ?:orders ON ?:rma_returns.order_id = ?:orders.order_id $join WHERE 1 $condition $group ORDER BY $sort $limit", (AREA == 'C') ? CART_LANGUAGE : DESCR_SL);

	/*$return_requests = db_get_array("SELECT DISTINCT cscart_rma_returns.return_id, cscart_rma_returns.order_id, cscart_rma_returns.timestamp, cscart_rma_returns.status, cscart_rma_returns.total_amount, cscart_rma_property_descriptions.property AS
//ACTION,cscart_users.firstname, cscart_users.lastname, cscart_shipments.shipment_id, cscart_shipments.carrier
FROM cscart_rma_returns
LEFT JOIN cscart_rma_return_products ON cscart_rma_return_products.return_id = cscart_rma_returns.return_id
LEFT JOIN cscart_rma_property_descriptions ON ( cscart_rma_property_descriptions.property_id = cscart_rma_returns.action
AND cscart_rma_property_descriptions.lang_code = 'E' )
LEFT JOIN cscart_users ON cscart_rma_returns.user_id = cscart_users.user_id
LEFT JOIN cscart_orders ON cscart_rma_returns.order_id = cscart_orders.order_id
join cscart_shipment_items on (cscart_rma_returns.return_id = cscart_shipment_items.return_id)
join cscart_shipments on (cscart_shipment_items.shipment_id = cscart_shipments.shipment_id)
WHERE 1
ORDER BY cscart_rma_returns.timestamp DESC
LIMIT 0 , 10");*/
//echo "<pre>";print_r($return_requests);die;
	fn_view_process_results('rma_returns', $return_requests, $params, $items_per_page);

	return array($return_requests, $params);
}


?>
