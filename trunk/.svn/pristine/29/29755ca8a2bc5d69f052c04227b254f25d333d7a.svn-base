<?php 

if ( !defined('AREA') ) { die('Access denied'); }

function fn_my_changes_get_product_data_more()
{
   $root_categories = fn_get_subcategories(0);
	foreach ($root_categories as $k => $v) {
		$root_categories[$k]['subcategories'] = fn_get_categories_tree($v['category_id']);
	}
	return $root_categories;
}

function fn_my_changes_send_mail_pre($mailer, $to, $from, $subj, $body, $attachments, $lang_code, $reply_to, $is_html)
{
   $user_details = db_get_row("SELECT * FROM cscart_users WHERE email = '$to'");
   $user_id = $user_details['user_id'];
   Registry::get('view_mail')->setLanguage($lang_code);
   $msg_subject =  addslashes(Registry::get('view_mail')->display($subj,false));
   $msg_body = addslashes(Registry::get('view_mail')->display($body,false));
   db_query("INSERT INTO  clues_email_queue (user_id, from_email, to_email, subject, message) values('".$user_id."','".$from."','".$to."','".$msg_subject."','".$msg_body."')");
}

function fn_my_changes_get_products_post($products, $params)
{
	if(isset($params['product_code']) && $params['product_code'] == 'Y') {
		$auth = $_SESSION['auth'];
		$productarray = db_get_array("SELECT product_id from cscart_products where product_code like '%".$params['q']."%'");
		
		foreach($productarray as $pro)
		{
			$products[] = db_get_row("SELECT * from cscart_products left join cscart_product_descriptions on (cscart_products.product_id=cscart_product_descriptions.product_id) where cscart_products.product_id='".$pro['product_id']."'");
			
		}
	}
	return $products;
}
function fn_list_tabs($nav)
{	
	$GLOBALS['topLinks'][] = $nav;
	return $GLOBALS['topLinks'];
}

function fn_get_company_contactperson($company_id)
{
	$merchant_name = db_get_row("SELECT firstname, lastname FROM cscart_users WHERE company_id=$company_id");
	return ($merchant_name);
}

/*To get payment methods*/
function fn_my_changes_payment_methods()
{
	return db_get_array("SELECT cscart_payments.payment_id, cscart_payment_descriptions.payment FROM cscart_payments 
LEFT JOIN cscart_payment_descriptions ON (cscart_payments.payment_id = cscart_payment_descriptions.payment_id)
WHERE cscart_payments.status='A'");
}

/*To get payment methods for a product.*/	
function get_payment_methods($products)
{
	$key = '';
	$common_payment_gateway = TRUE;
	foreach($products as $product)
	{
		$allowed_payment_method = db_get_row("select payment_method from product_payment_method where product_id='".$product['product_id']."'");
		if($allowed_payment_method['payment_method']!='') {
			$payment_methods[$product['product_id']] = explode(',',$allowed_payment_method['payment_method']);
			if($key =='')
			{
				$key = $product['product_id'];
			}
		}
		
	}
	
	$first_array = $payment_methods[$key];
	
	if(count($payment_methods)>0) {
		foreach($payment_methods as $payment_method)
		{	
			$final_array = array_intersect($first_array,$payment_method);
			//echo '<pre>count : '.count($final_array);print_r($final_array);
			if(count($final_array) == '0')
			{
				$common_payment_gateway = FALSE;
				break;
			}			
		}
		if($common_payment_gateway == FALSE) {
			$final_array = array();
			$final_array['msg'] = 'no_match_found';
			return $final_array;
		} elseif($common_payment_gateway == TRUE && count($final_array) > 0) {
			$final_array['msg'] = 'match_found';
			return($final_array);
	}
	} else {
		$final_array = array();
		$final_array['msg'] = 'no_method_alloted';
		return $final_array;
	}
}

/*To get payment methods*/
function fn_my_changes_get_shipping_estimation($id='')
{
	if($id == '') {
		return db_get_array("SELECT * FROM clues_shipping_estimation");
	}elseif($id != '') {
		return db_get_row("SELECT * FROM clues_shipping_estimation where id=".$id);	
	}
}

/* Add By Paresh get_shipment_data */
function get_shipment_data($shipment_ids, $fetch_type = 'array')
{
	if(is_array($shipment_ids))
	{
		$shipment_ids = implode("','",$shipment_ids);//Change By paresh
	}
	$query = "SELECT * 
			  FROM cscart_shipments as cship 
			  LEFT JOIN clues_package_types as cpt
			  ON cship.package_type = cpt.id
			  WHERE cship.shipment_id IN ('".$shipment_ids."')";
	if($fetch_type == 'array')
	{
		$shipment_data = db_get_array($query);
	}elseif($fetch_type == 'row')
	{
		
		$shipment_data = db_get_row($query);
		
	}
	return $shipment_data;
}

/* Add By Paresh get_package_types */
function get_package_types()
{
	$packet_types = db_get_array("SELECT * FROM clues_package_types WHERE status = 0");
	return $packet_types;
}

/* Add By Paresh get_order_product_details */
function get_order_product_details($order_id)
{
	$query = "SELECT * 
			  FROM cscart_orders as csord
			  INNER JOIN cscart_order_details as cod
			  ON csord.order_id = cod.order_id
			  INNER JOIN cscart_product_descriptions as cpd
			  ON cod.product_id = cpd.product_id
			  WHERE csord.order_id = '".$order_id."'";
			  
	$order_product_details = db_get_array($query);
	return $order_product_details;
}

/* Add By Paresh get_order_product_details */
function get_order_full_details($order_id)
{
	$query = "SELECT * 
			  FROM cscart_orders as csord
			  INNER JOIN cscart_order_details as cod
			  ON csord.order_id = cod.order_id
			  INNER JOIN cscart_product_descriptions as cpd
			  ON cod.product_id = cpd.product_id
			  WHERE csord.order_id = '".$order_id."'";
			  
	$order_product_details = db_get_array($query);
	return $order_product_details;
}

/* Add By Paresh get_merchant_sku */
function get_prod_merchant_sku($prod_id)
{
	$query = "SELECT merchant_reference_number 
			  FROM cscart_products
			  WHERE product_id = '".$prod_id."'";
			  
	$prod_merchant_sku = db_get_row($query);
	return $prod_merchant_sku;
}

?>