<?php 

if ( !defined('AREA') ) { die('Access denied'); }

function fn_my_changes_get_product_data_more()
{
   if(Registry::get('config.memcache'))
    {
        $memcache = $GLOBALS['memcache'];
        $key = md5('topmenu');
        if($mem_value = $memcache->get($key)){
            $root_categories = $mem_value;
        }else{
            $root_categories = fn_get_subcategories(0);
        	foreach ($root_categories as $k => $v) {
        		$root_categories[$k]['subcategories'] = fn_get_categories_tree($v['category_id']);
        	}
            $memcache->set($key, $root_categories, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time'));
        }   
    }else{
        $root_categories = fn_get_subcategories(0);
    	foreach ($root_categories as $k => $v) {
    		$root_categories[$k]['subcategories'] = fn_get_categories_tree($v['category_id']);
    	}
    }   
	return $root_categories;
}

function fn_my_changes_send_mail_pre($mailer, $to, $from, $subj, $body, $attachments, $lang_code, $reply_to, $is_html)
{
   $user_details = db_get_row("SELECT * FROM cscart_users WHERE email = '$to'");
   if(isset($user_details['user_id'])){
   		$user_id = $user_details['user_id'];
   }
   else
   {
	  $user_id=''; 
   }
   Registry::get('view_mail')->setLanguage($lang_code);
   $msg_subject =  addslashes(Registry::get('view_mail')->display($subj,false));
   $msg_body = addslashes(Registry::get('view_mail')->display($body,false));
    db_query("INSERT INTO  clues_email_queue (user_id, from_email, to_email, subject, message) values('".$user_id."','".$from."','".$to."','".$msg_subject."','".$msg_body."')");
}

function fn_my_changes_get_products_post($products, $params)
{
	/*if(isset($params['product_code']) && $params['product_code'] == 'Y') {
		$auth = $_SESSION['auth'];
		$productarray = db_get_array("SELECT product_id from cscart_products where product_code like '%".mysql_real_escape_string($params['q'])."%'");
		
		foreach($productarray as $pro)
		{
			$products[] = db_get_row("SELECT * from cscart_products left join cscart_product_descriptions on (cscart_products.product_id=cscart_product_descriptions.product_id) where cscart_products.product_id='".$pro['product_id']."'");
			
		}
	}
	return $products; */
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
			//$final_array['msg'] = 'match_found';
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

/* Add By Paresh get_manifest_id */
function get_manifest_id($order_id)
{
	$query = "SELECT *
			  FROM `clues_order_manifest_details` as comd
			  INNER JOIN clues_order_manifest as cmd ON cmd.manifest_id = comd.manifest_id
			  WHERE comd.order_id LIKE '%".$order_id."%' ORDER BY comd.manifest_id DESC LIMIT 0,1";
			  
	$manifest_detail = db_get_row($query);
	return $manifest_detail;
}
/* Add By Paresh order_manifest_details */
function order_manifest_details($order_id)
{
	$query = "SELECT MAX( comd.manifest_id ) as manifest_id , MAX( comd.date_created ) as date_created , com.manifest_type_id, com.dispatch_date, com.carrier_name, cmt.description
				FROM  `clues_order_manifest_details` AS comd
				JOIN clues_order_manifest AS com ON com.manifest_id = comd.manifest_id
				JOIN clues_manifest_type AS cmt ON cmt.manifest_type_id = com.manifest_type_id
				WHERE comd.order_id ='".$order_id."'
				GROUP BY com.manifest_type_id
				ORDER BY comd.manifest_id ASC";
			  
	$manifest_detail = db_get_array($query);
	return $manifest_detail;
}


/* Add By Paresh get_manifest_details */
function get_manifest_details($manifest_id)
{
	$query = "SELECT * 
			  FROM `clues_order_manifest_details` as comd
			  INNER JOIN clues_order_manifest as cmd ON cmd.manifest_id = comd.manifest_id
			  WHERE cmd.manifest_id = '".$manifest_id."'";
	
	$manifest_result =  db_get_array($query);
	
	foreach($manifest_result as $key=>$manifest_detail)
	{
		$manifest_details['order_ids'][] = array('order_id'=>$manifest_detail['order_id'],'weight'=>$manifest_detail['weight'],'awbno'=>$manifest_detail['awbno']);
		$manifest_details['courier_name'] = $manifest_detail['carrier_name'];
		$manifest_details['dispatch_date'] = $manifest_detail['dispatch_date'];
		$manifest_details['manifest_id'] = $manifest_detail['manifest_id'];
		$manifest_details['pickup_by'] = $manifest_detail['pickup_by'];
		$manifest_details['pickup_vehicle_no'] = $manifest_detail['pickup_vehicle_no'];
		$manifest_details['pickup_location'] = $manifest_detail['pickup_location'];
		$manifest_details['generated_by_name'] = $manifest_detail['generated_by_name'];
		$manifest_details['manifest_type_id'] = $manifest_detail['manifest_type_id'];
		$manifest_details['notes'] = $manifest_detail['notes'];
                $manifest_details['date_created'] = $manifest_detail['date_created'];
	}
	
	return $manifest_details;
}

function fn_my_changes_log_order_status($status_to, $status_from, $order_id)
{
	$user_id = 0;
	if(!empty($_SESSION['auth']['user_id'])) 
	{
		$user_id = $_SESSION['auth']['user_id'];
	} 
	
	$query = "INSERT INTO `clues_orderstatus_history` (`orderid`, `fromstatus`, `tostatus`, `changedate`, `userid`) "
			. " VALUES('" . $order_id . "', '" . $status_from . "', '" . $status_to . "', NOW(), '" . $user_id . "') ";
	
	db_query($query);
}

/*Funciton to check the product as new*/

function check_product_for_new($product)
{
  if($product['timestamp'] == '' || $product['timestamp'] == '0')
  {
	  return false;
  }
  $product_date = date('d/m/Y',$product['timestamp']);
  $current_date = date('d/m/Y');
  $diff = abs(strtotime($current_date) - strtotime($product_date));
  $years = floor($diff / (365*60*60*24));
  $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
  $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
  $new_days = (Registry::get('config.new_days_range'))? Registry::get('config.new_days_range'):'7';
  if($days <= $new_days)
  {
    return 'new';
  }
  else
  {
    return false;
  }
}

/* Added by Sudhir to track order history */
function fn_my_changes_change_order_status($status_to, $status_from, $order_info, $force_notification, $order_statuses, $place_order){

$user = $_SESSION['auth'];
    if($order_info['payment_method']['payment_id'] == '6'){
	if($status_from == 'N'){	
		$memo = 'New Order - COD';
	} else {
		$memo = 'Need to work on this';
	}
    } else {
	$transaction_id = db_get_array("SELECT direcpayreferenceid, payment_gateway FROM clues_prepayment_details WHERE order_id = '".$order_info['order_id']."'");
	if(!empty($transaction_id))
	$memo = $transaction_id['0']['payment_gateway'] .'-'. $transaction_id['0']['direcpayreferenceid'];
	else
	$memo='';
    }
	db_query("INSERT INTO clues_order_history (user_id, order_id, from_status, to_status, transition_date, transition_id, memo) VALUES ('".$user['user_id']."', '".$order_info['order_id']."',  '".$status_from."', '".$status_to."', '".time()."','','".$memo."' )");

}

/**
 *  Given a file, i.e. /css/base.css, replaces it with a string containing the
 *  file's mtime, i.e. /css/base.1221534296.css.
 *  
 *  @param $file  The file to be loaded.  Must be an absolute path (i.e.
 *                starting with slash).
 */
function auto_version($file)
{ 
  if(strpos($file, '/') !== 0 || !file_exists($_SERVER['DOCUMENT_ROOT'] . $file))
    return $file;

  $mtime = filemtime($_SERVER['DOCUMENT_ROOT'] . $file);
  return preg_replace('{\\.([^./]+)$}', ".$mtime.\$1", $file);
}

function fn_get_popular_brands($category_id) 
{ 
	/*$subcategories = fn_get_plain_categories_tree($category_id);
	$subcategories_list = $category_id;
	foreach($subcategories as $subcategory){
	$subcategories_list = $subcategories_list.','.$subcategory['category_id'];
	}
	$popular_brands = db_get_array("select distinct(varient_name), varient_id from clues_popular_brands where category_id in ($subcategories_list) order by popularity DESC LIMIT 0,".Registry::get('config.category_popular_brands_limit'));*/

	if(Registry::get('config.memcache'))
	{
		$memcache = $GLOBALS['memcache'];
		$key = md5($category_id.'-subcategories');
		if(($mem_value = $memcache->get($key)) !== false){
				$subcategories = $mem_value;
		}else{
			$subcategories = fn_get_plain_categories_tree($category_id);
			$memcache->set($key, $subcategories, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time'));
		}
	}else{
		$subcategories = fn_get_plain_categories_tree($category_id);
	}
	
	$subcategories_list = $category_id;
	foreach($subcategories as $subcategory){
		$subcategories_list = $subcategories_list.','.$subcategory['category_id'];
	}
	
	if(Registry::get('config.memcache'))
	{
		$memcache = $GLOBALS['memcache'];
		$key = md5($category_id.'-popular_brands');
		if(($mem_value = $memcache->get($key)) !== false){
				$popular_brands = $mem_value;
		}else{
			$popular_brands = db_get_array("select distinct(varient_name), varient_id from clues_popular_brands where category_id in ($subcategories_list) order by popularity DESC LIMIT 0,".Registry::get('config.category_popular_brands_limit'));
			$memcache->set($key, $popular_brands, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time'));
		}
	}else{
		$popular_brands = db_get_array("select distinct(varient_name), varient_id from clues_popular_brands where category_id in ($subcategories_list) order by popularity DESC LIMIT 0,".Registry::get('config.category_popular_brands_limit'));
	}
	return $popular_brands;  
}

function fn_check_merchant_for_ngo($merchant_id)
{
	$result = db_get_row("select is_ngo from cscart_companies where company_id='".$merchant_id."'");
	return $result['is_ngo'];
}

function fn_get_merchant_category($category_id){

	/*$count = count($category_id);
	$id_path='';
	for($i=0; $i<$count; $i++){
		$result = db_get_row("SELECT id_path FROM cscart_categories WHERE category_id='".$category_id[$i]."'");
		$id_path .= $id_path.'/'.$result['id_path'];
	}
	$category = explode('/', $id_path);

	$category=array_unique($category);

	return $category;*/

	$count = count($category_id);

	for($i=0; $i<$count; $i++){
		$category[] = $category_id[$i];		
		$result = db_get_row("SELECT parent_id FROM cscart_categories WHERE category_id='".$category_id[$i]."'");

		if($result['parent_id'] != '0'){
			$category[] = $result['parent_id'];
			$res=db_get_row("SELECT parent_id FROM cscart_categories WHERE category_id='".$result['parent_id']."'");
			if($res['parent_id'] != '0'){
				$category[] = $res['parent_id'];
			}
		}
	}
	return $category;
}
/* Fetch fulfillment data */

function fn_get_all_fulfillment()

{

	$query = "SELECT * FROM clues_fulfillment_lookup";

	$fulfillment = db_get_array($query);

	return $fulfillment;

}

/* Fetch region data */

function fn_get_all_region()

{

	//$query = "SELECT * FROM clues_region_lookup ORDER BY region_name";
	
	$query = "SELECT region_id, if(region_name IN ('Delhi NCR', 'Chennai','Mumbai', 'Hyderabad'), 1, 2) as sort_order, region_name, date_updated FROM clues_region_lookup order by 2,3";

	$region_list = db_get_array($query);	

	return $region_list;

}

/* Fetch warehouse data */
function fn_get_warehouse_data($company_id)
{
	$query = "SELECT * FROM clues_warehouse_contact WHERE company_id ='".$company_id."'";
	$warehouse_data = db_get_row($query);
	return $warehouse_data;
}

/* Fetch warehouse data */
function fn_check_warehouse_data($company_id)
{
	$query = "SELECT count(id) as total FROM clues_warehouse_contact WHERE company_id ='".$company_id."'";
	$warehouse_result = db_get_row($query);
	return $warehouse_result['total'];
}

/* Update warehouse data */
function fn_update_warehouse_data($warehouse_data,$company_id,$company_name)
{
	$warehouse_data['company_name'] =  $company_name;
        $warehouse_data = fn_match_table_fields($warehouse_data,"clues_warehouse_contact");
        $setQuery = fn_get_update_query($warehouse_data); 
        $update_result = db_query("UPDATE clues_warehouse_contact SET $setQuery WHERE company_id = $company_id");
}

/* Insert warehouse data */
function fn_insert_warehouse_data($warehouse_data,$company_id,$company_name)
{   
    $warehouse_data['company_id'] =  $company_id;    
    $warehouse_data['company_name'] =  $company_name;
    $warehouse_data['creation_date'] =  time();
    $warehouse_data = fn_match_table_fields($warehouse_data,"clues_warehouse_contact");
    $setQuery = fn_get_insert_query($warehouse_data);
    //$query = "INSERT INTO clues_warehouse_contact (company_id, company_name, warehouse_address1, warehouse_address2, warehouse_city, warehouse_state, warehouse_pin, warehouse_pcontact_name, warehouse_pcontact_phone, warehouse_pcontact_email, warehouse_scontact_name, warehouse_scontact_phone, warehouse_scontact_email, region_code, creation_date) VALUES ('".$company_id."','".$company_name."','".$warehouse_data['address1']."','".$warehouse_data['address2']."','".$warehouse_data['city']."','".$warehouse_data['state']."','".$warehouse_data['pin']."','".$warehouse_data['pcontact_name']."','".$warehouse_data['pcontact_phone']."','".$warehouse_data['pcontact_email']."','".$warehouse_data['scontact_name']."','".$warehouse_data['scontact_phone']."','".$warehouse_data['scontact_email']."','".$warehouse_data['region']."',now())";
    $insert_result = db_query("INSERT INTO clues_warehouse_contact $setQuery");
}

/* Fetch warehouse data */

function fn_get_region_warehouse_data($region_code)
{

	/*$query = "SELECT * FROM clues_warehouse_contact WHERE region_code ='".$region_code."'";
	$warehouse_data = db_get_array($query);
	return $warehouse_data;*/
	$statuses = implode('\',\'',Registry::get('config.order_statuses_for_milkrun'));
	$query = "SELECT * 
				FROM clues_warehouse_contact as cwc 
				INNER JOIN cscart_orders as co ON cwc.company_id = co.company_id
				WHERE cwc.region_code ='".$region_code."' and co.status IN ('$statuses') GROUP BY co.company_id";
	$warehouse_data = db_get_array($query);
	return $warehouse_data;
}

function fn_html_to_pdf_save($html, $filename)
{
	if (!fn_init_pdf()) {
		
		fn_redirect((!empty($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : INDEX_SCRIPT);
	}
	
	if (!is_array($html)) {
		$html = array($html);
	}
	
	$g_config = array (
		'cssmedia' => 'print',
		'scalepoints'   => true,
		'html2xhtml'    => true,
		'landscape'     => false,
		'method'        => 'fpdf',
		'pagewidth'     => 800,
		'ps2pdf'        => false,
		'renderimages' => true,
		'renderlinks' => false,
		'renderfields' => false,
		'renderforms' => true,
		'mode' => 'html',
		'encoding' => 'utf8',
		'debugbox' => false,
		'pdfversion' => '1.4',
		'draw_page_border' => false,
		'smartpagebreak' => true,
		'output' => 2,
	);
	
	$pipeline = PipelineFactory::create_default_pipeline('', $filename);
	
	$pipeline->fetchers = array (
		new PdfFetcherMemory($html, Registry::get('config.current_location') . '/'),
		new FetcherURL(),
	);
	
	$pipeline->data_filters[] = new DataFilterDoctype();
	$pipeline->data_filters[] = new DataFilterHTML2XHTML();
	
	//$pipeline->output_driver = new OutputDriverFPDF();
	
	$pipeline->destination = new DestinationFile($filename, 'File saved as: <a href="%link%">%name%</a>');	
	
	$media = & Media::predefined('A4');
	$media->set_landscape(false);
	$media->set_margins(array('left'   => 10, 'right'  => 10,
							  'top'    => 10, 'bottom' => 10));
	$media->set_pixels(600);
	
	$pipeline->configure($g_config);
	$pipeline->process_batch(array_keys($html), $media);
	
	return $pipeline->destination->filename;
}

function fn_my_changes_get_servicable_carrier($shipping_address_zip)
{
	$query = "select * from clues_carriers_service_area where pincode='".$shipping_address_zip."'";	
	$result = db_get_array($query);
	return $result;
}
function log_to_file($file_name,$content) {
	       $stringData = date('Y-m-d h:i:s').'=='.$content."\r\n";
	       $myFile = DIR_IMAGES.'/logs/'.$file_name."_log.txt";
	       $fh = fopen($myFile, 'a');
	       if($fh){
	        fwrite($fh, $stringData);	
	        fclose($fh);
	       }
}

function fn_get_product_is_approved($product_id){

	$query = "SELECT is_approved FROM cscart_products WHERE product_id='".$product_id."'";
	$result = db_get_array($query);
	return $result[0]['is_approved'];
}

function fn_get_company_warehouse_data($company_id)
{
	$query = "SELECT cwc.company_id, cwc.company_name AS company, "
			. " CONCAT_WS(' ', warehouse_address1, warehouse_address2) AS address, warehouse_city AS city, "
			. " warehouse_state AS state, warehouse_pin, "
			. " CONCAT_WS(' ', warehouse_pcontact_phone, warehouse_scontact_phone) AS phone "
			. " FROM `clues_warehouse_contact` cwc WHERE company_id = '".$company_id."' ";
	$company_warehouse_data = db_get_row($query);
	return $company_warehouse_data;
}

/* Add By Paresh get_all_center_details */
function get_all_center_details()
{
	$query = "SELECT * 
			  FROM clues_sorting_centers";
	
	$center_result =  db_get_array($query);
	
	return $center_result;
}

/* Add By Paresh get_all_warehouse_details */
function get_manifest_type($manifest_type_id='')
{
	if($manifest_type_id != '')
	{
		$where = "WHERE manifest_type_id='".$manifest_type_id."'";	
	}
	
	if(!isset($where))
	$where='';
	
	$query = "SELECT * 
			  FROM clues_manifest_type ". $where;
	if($manifest_type_id != '')
	{
		$manifest_type =  db_get_row($query);
	}else{
		$manifest_type =  db_get_array($query);
		if(!empty($manifest_type))
		{
			foreach($manifest_type as $k=>$v)
			{
				$manifest_type['manifest_lookup'][$manifest_type[$k]['manifest_type_id']] .= $manifest_type[$k]['description'];
			}
		}
	}
	return $manifest_type;
}

function generate_image($order_id)
 
{
	$style = BCS_ALIGN_CENTER;
	if (Registry::get('addons.barcode.text') == 'Y') {
		$style = $style + BCS_DRAW_TEXT;
	}
	if (Registry::get('addons.barcode.output') == 'png') {
		$style = $style + BCS_IMAGE_PNG;
	}

	if (Registry::get('addons.barcode.output') == 'jpeg') {
		$style = $style + BCS_IMAGE_JPEG;
	}

	$width = '300';
	$height = '90';
	$xres = '2';
	$font = '5';
	$id = $order_id;
	$type = 'C128B';
	
	// Define supported barcode types
	$objects = array (
		'I25' => 'I25Object',
		'C39' => 'C39Object',
		'C128A' => 'C128AObject',
		'C128B' => 'C128BObject',
		'C128C' => 'C128CObject',
	);

	// Define barcode types that should have only numeric values
	$numeric_objects = array (
		'I25' => true,
		'C128C' => true,
	);

	if (!empty($objects[$type])) {
		$prefix = Registry::get('addons.barcode.prefix');
		if (!empty($numeric_objects[$type]) && !is_numeric($prefix)) {
			$prefix = '';
		}
		$code = $prefix . $id;
		if (strlen($code) % 2 != 0) {
			//$code = $prefix . '0' . $id;
			$code = $prefix . $id;
		}

		

		$obj = new $objects[$type]($width, $height, $style, $code);
		if ($obj) {
			$obj->SetFont($font);
			$obj->DrawObject($xres);
			$obj->FlushObject($code);
			$obj->DestroyObject();
			unset($obj);  /* clean */
		}
	} else {
		__DEBUG__("Need bar code type ex. C39");
	}
 
}

/* Add By Paresh get_all_carrier_list */
function get_all_carrier_list()
{
	$query = "SELECT * 
			  FROM clues_carrier_lookup 
			  WHERE status='A'";
	$carrier_result =  db_get_array($query);
	foreach($carrier_result as $k=>$v)
	{
		$carrier_result['carrier_list'][$carrier_result[$k]['carrier_value']] .= $carrier_result[$k]['carrier_name'];
	}
	return $carrier_result;
}

/* Add By Paresh get_all_carrier_list */
function get_all_carrier_name($carrier_id)
{
	$query = "SELECT carrier_name
			  FROM clues_carrier_lookup 
			  WHERE status='A' AND carrier_id='".$carrier_id."'";	
	$carrier_name =  db_get_row($query);
	return $carrier_name['carrier_name'];
}

/* Add By Paresh get_single_carrier_name */
function get_single_carrier_name($carrier_value)
{
	$query = "SELECT carrier_name
			  FROM clues_carrier_lookup 
			  WHERE status='A' AND carrier_value='".$carrier_value."'";
	$carrier_name =  db_get_row($query);
	return $carrier_name['carrier_name'];
}

function get_manifest_order_cnt($manifest_id)
{
	$query = "SELECT count(order_id) as cnt
			  FROM clues_order_manifest_details 
			  WHERE manifest_id='".$manifest_id."'";
	$order_cnt =  db_get_row($query);
	return $order_cnt['cnt'];
}

/* Add By Paresh company_order_details */
function company_order_details($order_id = '', $company_id = '')
{
	$where= '';
	if($order_id != '')
	{
		$where .= " AND comd.order_id ='".$order_id."'";
	}
	
	if($company_id != '')
	{
		$where .= " AND co.company_id ='".$company_id."'";
	}
				
	$query = "SELECT com_od.manifest_id FROM
(SELECT MAX(comd.manifest_id ) as manifest_id
FROM cscart_orders as co
JOIN `clues_order_manifest_details` AS comd ON comd.order_id = co.order_id
JOIN clues_order_manifest AS com ON com.manifest_id = comd.manifest_id
WHERE 1 ".$where."
GROUP BY com.manifest_type_id,comd.order_id) as com_od
GROUP BY com_od.manifest_id
ORDER BY com_od.manifest_id ASC";
			  
	$manifest_detail = db_get_array($query);
	return $manifest_detail;
}

function get_carriers($carrier){
	$carrier_name = db_get_array("SELECT tracking_url FROM clues_carriers WHERE carrier_name LIKE '%".$carrier."%'");

	return $carrier_name[0]['tracking_url'];
}

//changes by ankur.This function takes the order ids as array and return an array with detail which require to display on pend_feedback page

/* Function to assign the coupon code to the customer*/
function assign_auto_generated_coupon($user_id, $no_of_coupon = '0', $promotion_duration = '30', $auto_coupon_promotion_id){
   if($no_of_coupon > 0 ){
      for($i = 1; $i<=$no_of_coupon; $i++) {
	 $code = fn_generate_code('',COUPON_CODE_LENGTH);
	 $promotion_data = fn_get_promotion_data($auto_coupon_promotion_id);
	 fn_promotion_update_condition($promotion_data['conditions']['conditions'], 'add', 'auto_coupons', $code);
	 db_query("UPDATE ?:promotions SET conditions = ?s, conditions_hash = ?s, users_conditions_hash = ?s WHERE promotion_id = ?i", serialize($promotion_data['conditions']), fn_promotion_serialize($promotion_data['conditions']['conditions']), fn_promotion_serialize_users_conditions($promotion_data['conditions']['conditions']), $auto_coupon_promotion_id);
	 
	 $assign_date = strtotime(date('Y-m-d'));
	 $expiration_date = strtotime('+'.$promotion_duration.' days',$assign_date);
	 
	 $sql = "INSERT INTO clues_customer_coupon (user_id, promotion_id, coupon_code, assign_date, expiration_date) VALUES ('".$user_id."','".$auto_coupon_promotion_id."','".$code."','".$assign_date."','".$expiration_date."')";
	 db_query($sql);
      }
   }
   
}

function check_coupon_code_for_user($user_id){
   $sql = "SELECT * from clues_customer_coupon where user_id = $user_id";
   $have_coupon = db_get_array($sql); 
   if(count($have_coupon)>0){
    return "true";
   }else{
    return "false";
   }
}


function get_payment_types()
{
	$sql = "SELECT * FROM clues_payment_types WHERE status = 'A' order by position asc";	
	$result = db_get_array($sql);
	return $result;
}

function get_payment_options($payment_type_id)
{
	$sql = "SELECT cpo.*, cpop.*,group_concat(cpop.payment_gateway_id) as served_by FROM clues_payment_options cpo join clues_payment_option_pgw cpop on (cpo.payment_option_id=cpop.payment_option_id) join cscart_payments cp on (cpop.payment_gateway_id=cp.payment_id) WHERE cpo.status = 'A' and cpo.payment_type_id = '".$payment_type_id."' and cp.`status` = 'A' group by cpo.payment_option_id order by position asc";
	$result = db_get_array($sql);
        if($payment_type_id == '6'){
            $sql = "SELECT cpo.* 
					FROM clues_payment_options cpo
					join clues_payment_options_emi_pgw cpoep on (cpo.payment_option_id=cpoep.payment_option_id) 
					join cscart_payments cp on (cpoep.payment_gateway_id=cp.payment_id)  
					WHERE cpo.status = 'A' and cp.status = 'A' and cpoep.status = 'A'  and cpo.payment_type_id = '".$payment_type_id."' group by cpo.name";
            $result = db_get_array($sql);
        }
	return $result;
}


function get_emi_options($payment_option_id){
   $sql = "SELECT cpo.*, cpoep.*
            FROM clues_payment_options cpo 
            join clues_payment_options_emi_pgw cpoep on (cpo.payment_option_id=cpoep.payment_option_id) 
            join cscart_payments cp on (cpoep.payment_gateway_id=cp.payment_id) 
            WHERE cpo.status = 'A' and cp.`status` = 'A' and cpoep.status = 'A' and cpoep.payment_option_id='".$payment_option_id."'";
    
    $result = db_get_array($sql);
    return $result;
}

function check_for_cod($products){
	$cod_allowed = 'YES';
	if(count($products) == "0"){
			$cod_allowed = 'NO';
			return  $cod_allowed;
	}
	foreach($products as $product){
	if($product['is_cod'] != 'Y'){
		$cod_allowed = 'NO';
	}
	else
	{
		if(Registry::get('config.enforce_nss_on_cod'))
		{
			$is_serviceable_nss = get_servicability_type($product['product_id'],$_SESSION['cart']['user_data']['s_zipcode']);
			if($is_serviceable_nss != 3)
			{
				$cod_allowed = 'NO';
			}
		}
	}
	}
	return $cod_allowed;
}

function fn_cart_total($cart){
	/*$total = $cart['display_subtotal'] + $cart['shipping_cost'];
	$discount = ($cart['discount'] > 0) ? $cart['discount'] : $cart['subtotal_discount'];
	$points_in_use = ($cart['points_info']['in_use']['cost']) ? $cart['points_info']['in_use']['cost'] : '0';
        $emi_fee = ($cart['emi_fee']> 0) ? $cart['emi_fee'] : '0';
	$cart_total = $total -$discount - $points_in_use - $emi_fee;*/
        $cart_total = $cart['total'] - $cart['emi_fee'];
	return $cart_total;
}

/* Fetch company bank data */
function fn_get_company_bank_data($company_id)
{
	$query = "SELECT * FROM clues_company_bank WHERE company_id ='".$company_id."'";
	$company_bank_data = db_get_row($query);
	return $company_bank_data;
}

/* Fetch company bank data */
function fn_check_company_bank_data($company_id)
{
	$query = "SELECT count(id) as total FROM clues_company_bank WHERE company_id ='".$company_id."'";
	$company_bank_result = db_get_row($query);
	return $company_bank_result['total'];
}

/* Update company bank data */
function fn_update_company_bank_data($company_bank_data,$company_id)
{	
    $company_bank_data = fn_match_table_fields($company_bank_data,"clues_company_bank");
    $setQuery = fn_get_update_query($company_bank_data); 
    return db_query("UPDATE clues_company_bank SET $setQuery WHERE company_id = $company_id");
}

/* Insert company_bank data */
function fn_insert_company_bank_data($company_bank_data,$company_id)
{   
    $company_bank_data['company_id'] = $company_id;  
    $company_bank_data['creation_date'] =  time();
    $company_bank_data = fn_match_table_fields($company_bank_data,"clues_company_bank");
    $setQuery = fn_get_insert_query($company_bank_data);
    return db_query("INSERT INTO clues_company_bank $setQuery");
}

function fn_match_table_fields($data, $table_name)
{
	$_fields = db_get_fields("SHOW COLUMNS FROM $table_name");
	if (is_array($_fields)) {
		foreach ($data as $k => $v) {
			if (!in_array($k, $_fields)) {
				unset($data[$k]);
			}
		}
		return $data;
	}
	return false;
}

function fn_get_update_query($data){
   $setQuery = '';
   foreach($data as $field => $value){
       $setQuery .= '`' . db_field($field) . "` = '" . addslashes($value) . "',"; 
   }
   return rtrim($setQuery,',');
}

function fn_get_insert_query($data){
   return '(`' . implode('`, `', array_map('addslashes', array_keys($data))) . "`) VALUES ('" . implode("', '", array_map('addslashes', array_values($data))) . "')";
}

function fn_update_sdeep_data($sdeepData, $company_id){
    $vendors_fv = array();
    if(is_array($sdeepData)) {
        foreach($sdeepData as $k => $v) {
                $vendors_fv[] = $k;
        }
    }
    return db_query("UPDATE ?:companies SET sdeep_features=?s WHERE company_id=?i", @serialize($vendors_fv), $company_id);
}
/* Fetch company_bank data */
function fn_get_user_email($user_id)
{
	if (!empty($user_id)) {
		$user_data = db_get_row("SELECT email FROM ?:users WHERE user_id = ?i", $user_id);
		return $user_data['email'];
	}
	return false;	
}

// Function creted for 3rd price by Sudhir dt 09th Octo 2012

function fn_get_3rd_price($product){

	$auth =array();

	$auth = $_SESSION['auth'];

	$condition = fn_product_promotion_check($product['promotion_id'], $product, $_SESSION['auth']);

	if($condition) {

		$condi = unserialize($condition['conditions']);
		$bonuses = unserialize($condition['bonuses']);

	 	if($bonuses){
		     foreach ($bonuses as $bn=>$value){
				if($value['bonus'] == 'discount_on_products' && $value['value'] == $product['product_id']){
					if($value['discount_bonus'] == 'by_percentage'){
						$discount = round($product['price'] * $value['discount_value'] / 100);
						if(($value['max_discount_value'] > 0) && ($discount > $value['max_discount_value'])){
							$discount = $value['max_discount_value'];
						}
						$third_price = $product['price'] - $discount;
					}elseif($value['discount_bonus'] == 'to_percentage'){
						$discount = round($product['price'] * $value['discount_value'] / 100);
						$discount_price = $product['price'] - $discount;
						if(($value['max_discount_value'] > 0) && ($discount_price >$value['max_discount_value'])){
							$discount_price = $value['max_discount_value'];
						}
						$third_price = $product['price'] - $discount_price;
					}elseif($value['discount_bonus'] == 'to_fixed'){
						if(($value['max_discount_value'] > 0 ) && ($value['discount_value'] > $value['max_discount_value'])){
							$value['discount_value'] = $value['max_discount_value'];
						}
						$third_price = $value['discount_value'];
					}elseif($value['discount_bonus'] == 'by_fixed'){
						if(($value['max_discount_value'] > 0 ) && ($value['discount_value'] > $value['max_discount_value'])){
							$value['discount_value'] = $value['max_discount_value'];
						}
						$third_price = $product['price'] - $value['discount_value'];
					}
				}elseif($value['bonus'] == 'order_discount'){
					if($value['discount_bonus'] == 'by_percentage'){
						$discount = round($product['price'] * $value['discount_value'] / 100);
						if(($value['max_discount_value'] > 0) && ($discount > $value['max_discount_value'])){
							$discount = $value['max_discount_value'];
						}
						$third_price = $product['price'] - $discount;
					}elseif($value['discount_bonus'] == 'to_percentage'){
						$discount = round($product['price'] * $value['discount_value'] / 100);
						$discount_price = $product['price'] - $discount;
						if(($value['max_discount_value'] > 0) && ($discount_price >$value['max_discount_value'])){
							$discount_price = $value['max_discount_value'];
						}
						$third_price = $product['price'] - $discount_price;
					}elseif($value['discount_bonus'] == 'to_fixed'){
						if(($value['max_discount_value'] > 0 ) && ($value['discount_value'] > $value['max_discount_value'])){
							$value['discount_value'] = $value['max_discount_value'];
						}
						$third_price = $value['discount_value'];
					}elseif($value['discount_bonus'] == 'by_fixed'){
						if(($value['max_discount_value'] > 0 ) && ($value['discount_value'] > $value['max_discount_value'])){
							$value['discount_value'] = $value['max_discount_value'];
						}
						$third_price = $product['price'] - $value['discount_value'];
					}
				}elseif($value['bonus'] == 'discount_on_categories'){
					if($value['discount_bonus'] == 'by_percentage'){
						$discount = round($product['price'] * $value['discount_value'] / 100);
						$third_price = $product['price'] - $discount;
					}elseif($value['discount_bonus'] == 'to_percentage'){
						$discount = round($product['price'] * $value['discount_value'] / 100);
						$discount_price = $product['price'] - $discount;
						$third_price = $product['price'] - $discount_price;
					}elseif($value['discount_bonus'] == 'to_fixed'){
						$third_price = $value['discount_value'];
					}elseif($value['discount_bonus'] == 'by_fixed'){
						$third_price = $product['price'] - $value['discount_value'];
					}
				}
			}
			// Updating third_price of a product with config option to Trun On and Off			
			if($product['third_price'] != $third_price && $product['product_id'] != '' && Registry::get('config.update_third_price')) {
				$sql = "UPDATE cscart_products SET third_price = '".$third_price."' WHERE product_id = ".$product['product_id'];
				db_query($sql);
			}
		return $third_price;
	   }
	}
	return false;
}

function fn_product_promotion_check($promotion_id, $product, $auth){

		if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
		{
			$memcache = $GLOBALS['memcache'];
			$key = md5("SELECT conditions, bonuses, to_date, from_date FROM cscart_promotions WHERE promotion_id='".$product['promotion_id']."' AND status='A'");
			if($mem_value = $memcache->get($key)){
				$condition = $mem_value;			
			}else{
				$condition = db_get_row("SELECT conditions, bonuses, to_date, from_date FROM cscart_promotions WHERE promotion_id='".$product['promotion_id']."' AND status='A'");
				$status = $memcache->set($key, $condition, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time'));
                                if(!$status){
                                    $memcache->delete($key);
                                }
			}
		}else{
			$condition = db_get_row("SELECT conditions, bonuses, to_date, from_date FROM cscart_promotions WHERE promotion_id='".$product['promotion_id']."' AND status='A'");
		}

	if($condition){
		$date = time();
		if(($condition['to_date']=='0') && ($condition['from_date']=='0')){
			return $condition;
		}elseif(($condition['from_date'] <= $date) && ($condition['to_date'] >= $date)){
			return $condition;
		}
	}
	return false;
}

function calculate_3rd_price_percentage($product, $third_price){
	if($product['list_price'] > 0){
		$discount_perc = round((($product['list_price']-$third_price)*100)/$product['list_price']);
	}else{
		$discount_perc = round((($product['price']-$third_price)*100)/$product['price']);
	}
	return $discount_perc;
}



function fn_get_category_image($category_ids)
{   
	if (!empty($category_ids)) {
		foreach($category_ids as $category_id=>$v) {

			if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
			{
				$memcache = $GLOBALS['memcache'];
				$key = md5("select show_image_on_catalog, show_seasonal_image_path from cscart_categories where show_image_on_catalog='Y' and category_id =$category_id ");
				if($mem_value = $memcache->get($key)){
					$cat_images_id = $mem_value;			
				}else{
					$cat_images_id = db_get_row("select show_image_on_catalog, show_seasonal_image_path from cscart_categories where show_image_on_catalog='Y' and category_id =$category_id ");
					$status = $memcache->set($key, $cat_images_id, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time'));
		                        if(!$status){
		                            $memcache->delete($key);
		                        }
				}
			}else{
				$cat_images_id = db_get_row("select show_image_on_catalog, show_seasonal_image_path from cscart_categories where show_image_on_catalog='Y' and category_id =$category_id ");
			}

			if($cat_images_id['show_image_on_catalog'] == 'Y') {
				return $category_id.'-'.$cat_images_id['show_seasonal_image_path'];
			}
		}			
	}
	return 0;		
}

function get_left_category_structure($category_id){
	$sql = "select id_path from cscart_categories where category_id=".$category_id;
	$id_path = db_get_field($sql);
	$categorie_ids = explode('/',$id_path);
	$parent_category_id = $categorie_ids['0'];
	$current_category_tree = fn_get_categories_tree($parent_category_id);
	$cat = fn_get_category_data($parent_category_id);
	$parent_category_data_sql = "select cc.category_id, cc.parent_id, cc.id_path, cc.show_as_new, ccd.category from cscart_categories cc
			left join cscart_category_descriptions ccd on cc.category_id=ccd.category_id
			where cc.category_id=".$parent_category_id;
	$parent_category_data = db_get_row($parent_category_data_sql);
	$parent_category_data['subcategories'] = $current_category_tree;
	$rest_category_data = fn_my_changes_get_product_data_more();
	//$category_tree = array_merge($parent_category_data,$rest_category_data);
	return array($parent_category_data, $rest_category_data);
	
}


//function by ankur move from order lookup file
function fn_get_status_change_date($order_id,$status)
{
	$sql="select transition_date from clues_order_history where order_id='".$order_id."' and to_status='".$status."'";
	$res=db_get_field($sql);
	return $res;
}
function fn_get_status_days($order_id,$status,$order_placing_date)
{
	$in_status_date=fn_get_status_change_date($order_id,$status);
	if(!empty($in_status_date))
	{
		$in_status_date=$in_status_date;
	}
	else
	{
		$in_status_date=$order_placing_date;
	}
	$current_date=time();
	$to_hour = ($current_date-$in_status_date)/( 60 * 60);
	return round($to_hour);
}
function fn_get_return_period($prod_id)
{
	return db_get_field("select return_period from cscart_products where product_id='".$prod_id."' and is_returnable='Y'");
	
}
function fn_get_company_name_from_id($comp_id)
{
	return db_get_field("select company from cscart_companies where company_id='".$comp_id."'");
}
function fn_get_product_estimation($product_id)
{
	return db_get_field("select product_shipping_estimation from cscart_products where product_id='".$product_id."'");
}
function fn_get_new_payment_info($order_id)
{
	 $paid = db_get_row("SELECT cpo.name,cpt.name as type_name FROM cscart_orders co , clues_payment_options cpo, clues_payment_types cpt where co.payment_option_id = cpo.payment_option_id AND cpo.payment_type_id=cpt.payment_type_id
 AND co.order_id='".$order_id."'");
	 return $paid;
}
function fn_get_is_fav_store($comp_id,$user_id)
{
	$sql="select id from clues_my_favourite_store where company_id='".$comp_id."' and user_id='".$user_id."' and store_like=1";
	return db_get_row($sql);
}
function group_privileges(){
	$group_privileges=db_get_fields("SELECT privilege FROM ?:usergroup_privileges WHERE usergroup_id IN(?n)", $_SESSION['auth']['usergroup_ids']);
return $group_privileges;
}

function fn_get_product_search_by_category($search_string){
	$pieces = fn_explode(' ', $search_string);
	$_condition = '';
	foreach ($pieces as $piece) {
		if (strlen($piece) == 0) {
			continue;
		}
		$tmp = "(descr1.search_words LIKE '%$piece%')"; // check search words

		if ($_REQUEST['pname'] == 'Y') {
			$tmp .= " OR descr1.product LIKE '%$piece%'";
		}
		if ($_REQUEST['pshort'] == 'Y') {
			$tmp .= " OR descr1.short_description LIKE '%$piece%'";
		}
		if ($_REQUEST['pfull'] == 'Y') {
			$tmp .= " OR descr1.full_description LIKE '%$piece%'";
		}
		if ($_REQUEST['pkeywords'] == 'Y') {
			$tmp .= " OR (descr1.meta_keywords LIKE '%$piece%')";
		}
		$_condition .= ' AND(' . $tmp . ')';
	}
	
	$sql = "SELECT products_categories.category_id,cscart_category_descriptions.category,cd1.category as meta_category, cc1.category_id as meta_cat_id,
			cscart_categories.id_path, count(products.product_id) as no_of_products
			FROM cscart_products as products 
			INNER JOIN cscart_product_descriptions as descr1 ON descr1.product_id = products.product_id AND descr1.lang_code = 'EN' 
			LEFT JOIN cscart_companies AS companies ON companies.company_id = products.company_id 
			INNER JOIN cscart_products_categories as products_categories ON products_categories.product_id = products.product_id 
			INNER JOIN cscart_categories ON cscart_categories.category_id = products_categories.category_id
			INNER JOIN cscart_categories cc1 ON cc1.category_id = SUBSTRING_INDEX(SUBSTRING_INDEX(cscart_categories.id_path, '/',2), '/',1) and cc1.status = 'A'
			
			INNER JOIN cscart_category_descriptions cd1 on cd1.category_id= cc1.category_id
			left join cscart_category_descriptions on cscart_category_descriptions.category_id =  cscart_categories.category_id
			WHERE 1 AND products.status='A'  $_condition AND (companies.status = 'A' OR products.company_id = 0) 
			AND cscart_categories.`status` = 'A' GROUP BY 1";	
	$result = db_get_hash_multi_array($sql,array('category_id'));
	$left_product_arr = array();
	foreach($result as $key=>$res){
		$left_product_arr[$res['0']['meta_category']][$key]['category_id'] = $res['0']['category_id'];
		$left_product_arr[$res['0']['meta_category']][$key]['no_of_products'] = $res['0']['no_of_products'];		
		$left_product_arr[$res['0']['meta_category']][$key]['meta_cat_id'] = $res['0']['meta_cat_id'];
		$left_product_arr[$res['0']['meta_category']][$key]['category'] = $res['0']['category'];
		$left_product_arr[$res['0']['meta_category']][$key]['id_path'] = $res['0']['id_path'];
	}
	$id_path_sql = "select SUBSTRING_INDEX(SUBSTRING_INDEX(id_path, '/',2), '/',1) from cscart_categories where category_id=".$_REQUEST['cid']; 
	$id_path = db_get_field($id_path_sql); 
	$current_cat_name = fn_get_category_name($id_path);
	if(array_key_exists($current_cat_name,$left_product_arr)){
		$temp[$current_cat_name] = $left_product_arr[$current_cat_name];
		unset($left_product_arr[$current_cat_name]);
		$left_product_arr = array_merge($temp,$left_product_arr);
	}
	return $left_product_arr;
}
function fn_get_status_customer_facing_name($status,$type='O')
{
	$result = db_get_row("SELECT description,customer_facing_name 
	FROM cscart_status_descriptions
	where status='".$status."' and type='".$type."'");
	return $result;
}
function shopping_option_name($features_hash){

	preg_match_all('/([A-Z]+)([\d]+)[,]?/', $features_hash, $vals);
	if($vals[1][0] == 'P'){
		$value_name = db_get_field("SELECT pfrd.range_name FROM cscart_product_filter_ranges_descriptions pfrd INNER JOIN cscart_product_filter_ranges pfr ON pfrd.range_id=pfr.range_id WHERE pfrd.range_id = '".$vals[2][0]."'");
	} else {
		$val = explode(".", $features_hash);
		$value_name = db_get_field("SELECT variant FROM cscart_product_feature_variant_descriptions WHERE variant_id = '".$val[1]."'");
	}
   return $value_name;
}


// Added By Sudhir dt 5th March 2013 to log query report into sql table bigin here
function fn_report_query_log($sql, $type, $user_id){
	$query = addslashes(trim($sql));
	$log = db_query("insert into clues_query_log set query='".$query."', user=".$user_id.", datetime=NOW(), type='".$type."'");
}
// Added By Sudhir dt 5th March 2013 to log query report into sql table end here


function fn_get_merchant_agreement(){
	$sql = "select text from clues_agreements where type='MOU' order by id desc limit 0,1";
	$result = db_get_field($sql);
	return $result;
}

// Created by Sudhir dt 27th May 2013, to check mobile browser for checkout popup bigin here
function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}
 // Created by Sudhir dt 27th May 2013, to check mobile browser for checkout popup end here

function newrelic_start(){
    if( extension_loaded('newrelic') ) { 
        $js = newrelic_get_browser_timing_header(); 
        return $js;
    }
}

function newrelic_end(){
     if( extension_loaded('newrelic') ) { 
         $js = newrelic_get_browser_timing_footer(); 
         return $js;
     }
}
//added by ajay 
function fn_get_tracking_url($carrier_value)
{
	$res=db_get_row("select carrier_name,tracking_url ,is_url_trackable from clues_carrier_lookup where carrier_value='".$carrier_value."'");
	return $res;
}
//end by ajay
function fn_buy_together_promotion($product_id)
{
    $sql="select 
                btp.product_id, btp.promotion_id, btp.deal_price, btp.coupon_code, cpd.product,cpp.price, p.list_price, p.status as product_status, cco.status as company_status, c.status as category_status , if(p.tracking='0',IF(inv.amount=0 OR inv.amount IS NULL,0,1),IF(p.amount=0,0,1)) as product_amount_status       
          from clues_buy_together_promo btp
          left join  cscart_product_descriptions cpd on cpd.product_id=btp.product_id
          left join cscart_product_prices  cpp on cpp.product_id=btp.product_id
          left join cscart_products p on p.product_id = btp.product_id 
          left join cscart_companies cco on  cco.company_id=p.company_id 
          left join cscart_products_categories cca on cca.product_id=p.product_id and cca.link_type='M'
          left join cscart_categories c on c.category_id=cca.category_id 
          left join cscart_product_options_inventory inv on inv.product_id=btp.product_id
                where btp.promotion_id in(select btp1.promotion_id 
          from clues_buy_together_promo btp1 where btp1.product_id=$product_id)
                order by btp.promotion_id ASC
";
 
    $result = db_get_hash_multi_array($sql,array('promotion_id'));
    
    foreach($result as $main_key=>$promotions)
    {
        
        if(count($promotions)>3)
        {
            unset($result[$main_key]);
            continue;
        }
        
        foreach($promotions as $p)
        {
             if($p['product_amount_status']==0 || ($p['product_status']!='A'&& $p['product_status']!='H') || $p['company_status']!='A' || ($p['category_status']!='A' && $p['category_status']!='H'))
             {
                 unset($result[$main_key]);
             }
    
        }
        
    
    }
    
// fn_print_die($result);
    return $result;
}


//Function added by shashi kant to show recently_viewed_history
function recently_viewed_products()
{       //echo"<pre>"; print_r($_SESSION); die;
        $limit = Registry::get('config.recently_view_product_limit');
        $prod_id = $_COOKIE['recently_viewed'];
        $prod_id = rtrim($prod_id, ',');
        $prod_id = explode(',', $prod_id);
        $prod_id_unique = array_unique($prod_id);
        $produc_id_latest=array_reverse($prod_id_unique);
        
        for ($i=0;$i<=$limit;$i++)
         {
             if($produc_id_latest[$i]!='')
             $produc_id_latest1[$i]=$produc_id_latest[$i];
         }
        $produc_id_first_six = implode(',' , $produc_id_latest1);
        if(!empty($produc_id_first_six)){
        $product_data = db_get_array("SELECT p.product_id as id, REPLACE(REPLACE(REPLACE(pd.product, '\n', ''), '', ''), '\r','') as product, p.list_price as list_price,
                                        min(pp.price) as price, 
                                        p.third_price,
                                        concat('images/thumbnails/',floor(if(i.image_id!=0, i.image_id,il.image_id)/1000),'/320/320/',REPLACE(REPLACE(image_path, '\n', ''), '\r', '')) as image_url,
                                        avg(pr.product_rating) as rating,
                                        REPLACE(REPLACE(ps.name, '\n', ''), '\r', '') as seo_name,
                                        p.last_update,
                                        p.status
                                        FROM
                                        cscart_products p
                                        LEFT JOIN cscart_product_prices pp ON pp.product_id = p.product_id
                                        LEFT JOIN cscart_seo_names ps ON p.product_id = ps.object_id and ps.type = 'p'
                                        LEFT JOIN cscart_products_categories pc ON p.product_id = pc.product_id and link_type = 'M'
                                        LEFT JOIN cscart_products_categories pc1 ON p.product_id = pc1.product_id
                                        LEFT JOIN cscart_product_options_inventory po on po.product_id = p.product_id
                                        LEFT JOIN cscart_category_descriptions cd2 ON cd2.category_id = pc.category_id
                                        LEFT JOIN cscart_product_descriptions pd ON pd.product_id = p.product_id
                                        LEFT JOIN cscart_images_links il ON il.object_id = p.product_id and il.object_type = 'product' and il.type = 'M'
                                        LEFT JOIN cscart_images i ON il.detailed_id = i.image_id
                                        LEFT JOIN cscart_categories cat ON cat.category_id = pc.category_id
                                        LEFT JOIN cscart_companies c ON c.company_id = p.company_id
                                        LEFT JOIN cscart_category_descriptions cd1 on cd1.category_id=SUBSTRING_INDEX(SUBSTRING_INDEX(id_path, '/',2), '/',1)
                                        LEFT JOIN cscart_categories c1 on c1.category_id = cd1.category_id
                                        LEFT JOIN cscart_categories c2 on c2.category_id = pc.category_id
                                        left join cscart_product_features_values cpfv on cpfv.product_id=p.product_id and cpfv.feature_id=53
                                        left join cscart_product_feature_variant_descriptions cpfvd on cpfvd.variant_id=cpfv.variant_id
                                        left join cscart_product_popularity pp1 on pp1.product_id = p.product_id
                                        LEFT JOIN cscart_seo_names pe ON cpfv.variant_id = pe.object_id and pe.type = 'e'
                                        LEFT JOIN clues_user_product_rating pr ON p.product_id = pr.product_id
                                        where p.product_id in ($produc_id_first_six)
                                        group by p.product_id");

        foreach($product_data as $key=>$row)
        {
            
            $image_url = explode('/', $row['image_url']);
            $image_name = array_pop($image_url);
            $image_name= preg_replace("/[^a-zA-Z0-9.]/i", "", $image_name);
            $image_url=  implode('/', $image_url);
            $product_data[$key]['image_url'] = $image_url. '/' . $image_name;
            
        }
//echo"<pre>"; print_r($product_data);  
return $product_data;
}

}
//End added by shashi kant to show recently_viewed_history

//added by anoop - replica of admin functions to use in frontend

function mpromotion_expected_payout($product_id, $discount_on_selling_price)
{ 
// write your logic here and return "some amount" or false "in case of error"
    $tp = db_get_row("SELECT tp, if(type='deal_tp',1,2) as tporder FROM clues_product_TP 
			WHERE product_id ='".$product_id."' AND latest=1
			AND start_date <= '".time()."' AND tp != 0 AND end_date >= '".time()."' order by tporder ASC");
    
    if(!empty($tp['tp'])){
        $expected_payout = '';
    }
    else
    {
//RBcase
        $res = db_get_row("SELECT shipping_freight as shipping_cost, (pp.price-$discount_on_selling_price) as prod_rsp, p.company_id from cscart_products p
                INNER JOIN cscart_product_prices pp ON pp.product_id = p.product_id
                WHERE p.product_id = '".$product_id."'"); 
	// Here $res['prod_rsp'] rsp is rsp after discount.
	$SF = fn_calculate_selling_fee_product($res, $product_id);
	$weight = db_get_field("select weight from clues_product_shipment_weight WHERE product_id = '".$product_id."'");
	if(empty($weight))
        {		
            $weight = db_get_field("select cc.weight from cscart_categories cc INNER JOIN cscart_products_categories cpc ON cpc.category_id = cc.category_id WHERE cpc.link_type = 'M' AND cpc.product_id = '".$product_id."'");
            if(empty($weight))
            {            
                $weight = 500;
            }
	}
	// We assume that payment type is prepaid as our mainhttp://192.168.1.200:8080/svn/admin/branches/AP-23-expected-payout business nis prepaid.
	$payment_type = 'prepaid';
	$FF = fn_get_fulfillment_charges_product($res['company_id'],$weight,$payment_type,$product_id,$type='fff');
	$expected_payout = $res['prod_rsp'] + $res['shipping_cost'] - $FF - $SF; 
    }
    if(!empty($expected_payout) && $expected_payout > 0)
    {
	return $expected_payout;
    }
    else
    {
	return false;
    }
}

function fn_calculate_selling_fee_product($res, $prod_id)
{
	$selling_service_fee_excluding_tax = 0;
	$sql = "select c.id,c.company_id,c.effective_date,c.billing_category_id,c.selling_fee_rate,bc.category as billing_category from clues_billing_companies_commission c 
			left join clues_billing_categories bc on bc.id=c.billing_category_id
			where c.company_id='".$res['company_id']."'";
	$comm = db_get_array($sql); 
	$prod_comm = 0;
	$effective_date_timestamp = strtotime($comm[0]['effective_date']);
	 if($effective_date_timestamp <= time() && count($comm)==1 && $comm[0]['billing_category_id']==0){
		$prod_comm	= $comm[0]['selling_fee_rate'];		
		$prod_rsp = $res['prod_rsp'];			
		$ship_p = $res['shipping_cost'];
		
		$selling_service_fee_excluding_tax = $selling_service_fee_excluding_tax + (($prod_rsp + $ship_p)*$prod_comm/100);	
	 }
	else if(count($comm)==1 && $comm[0]['billing_category_id']==0)
	{
		$prod_comm	= $comm[0]['selling_fee_rate'];
		
		$prod_rsp = $res['prod_rsp'];			
		$ship_p = $res['shipping_cost'];		
		$selling_service_fee_excluding_tax = $selling_service_fee_excluding_tax + (($prod_rsp + $ship_p)*$prod_comm/100);	
	}
	else if(count($comm)>=0)
	{ 
			$pro_billing_cat_id = 0;
			$sql = "select b.id,c.billing_category,default_commision from cscart_products_categories pc
					inner join cscart_categories c on pc.category_id=c.category_id
					inner join clues_billing_categories b on b.id=c.billing_category
					where pc.product_id='".$prod_id."'";
			$result_bilc = db_get_array($sql);
			if($result_bilc)
			{
				foreach($result_bilc as $res_bil)
				{
					if($res_bil['default_commision']>0)
					{
						$pro_billing_cat_id=$res_bil['id'];
						break;
					}
				}
			}	
			
			
			if($pro_billing_cat_id)
			{
				$sql = "select selling_fee_rate,effective_date from clues_billing_companies_commission where company_id='".$res['company_id']."' and billing_category_id='".$pro_billing_cat_id."'";
				$comm_r = db_get_array($sql);
				
				if($comm_r[0]['selling_fee_rate'])
				{
                    $effective_cc =0;
					foreach($comm_r as $comm_r1){
							
							$effective_date_timestamp = "";
							$effective_date_timestamp = strtotime($comm_r1['effective_date']);
							if($effective_date_timestamp <= time()){
									$prod_comm = $comm_r1['selling_fee_rate'];
									$effective_cc = 1;
							}
							if($effective_cc != 1){
									$prod_comm = $comm_r1['selling_fee_rate'];
                                    $effective_cc = 1;

							}
					}
				}
				else
				{
					$sql = "select selling_fee_rate,effective_date from clues_billing_companies_commission where company_id='".$res['company_id']."' and billing_category_id='0'";
					$comm_r = db_get_array($sql);
					
					if($comm_r[0]['selling_fee_rate'])
					{
                        $effective_cc =0;
						foreach($comm_r as $comm_r1){
								
								$effective_date_timestamp = "";
								$effective_date_timestamp = strtotime($comm_r1['effective_date']);
								if($effective_date_timestamp <= time()){
										$prod_comm = $comm_r1['selling_fee_rate'];
										$effective_cc = 1;
								}
								if($effective_cc != 1){
										$prod_comm = $comm_r1['selling_fee_rate'];
                                        $effective_cc = 1;
								}
						}
					}
					
					else
					{
						//To get the default commision of billing category
						$sql="select default_commision,effective_date from clues_billing_categories where id='".$pro_billing_cat_id."'";
						$comm_r = db_get_array($sql);
						
						if($comm_r[0]['default_commision'])
						{
                            $effective_cc =0;
							foreach($comm_r as $comm_r1){
									
									$effective_date_timestamp = "";
									$effective_date_timestamp = strtotime($comm_r1['effective_date']);
									if($effective_date_timestamp <= time()){
											$prod_comm = $comm_r1['default_commision'];
											$effective_cc = 1;
									}
									if($effective_cc != 1){
											$prod_comm = $comm_r1['default_commision'];
                                            $effective_cc = 1;
									}
							}
						}
						
						else
						{
							$prod_comm = 8;
						}
					}
				}
			}
       
			$prod_rsp = $res['prod_rsp'];			
			$ship_p = $res['shipping_cost'];				
			// Assumes that this function is for RB only so $prod_rsp + $ship_p
			$selling_service_fee_excluding_tax = (($prod_rsp + $ship_p)*$prod_comm/100); 	
	}
	return $selling_service_fee_excluding_tax;
}

function fn_get_fulfillment_charges_product($company_id,$weight,$payment_type,$prod_id,$type='fff')
{
	//$sql = "select * from clues_billing_companies_shipping_rel where company_id='".$company_id."'";
	$sql = "select r.*, cc.fulfillment_id from clues_billing_companies_shipping_rel r
			INNER JOIN cscart_companies cc ON cc.company_id = r.company_id
			where r.company_id='".$company_id."'";
	$cmpship = db_get_row($sql);
	
	
	if(empty($cmpship))
	{
		$cmpship = array();
		$cmpship['table_rate_type']='STD';
	}
	
	if($cmpship['table_rate_type'] != '' && $cmpship['fulfillment_id'] != 3)
	{
		
		if($prod_id != ""){
		//vk changes
            $sql = "select fee_" . $payment_type . ",effective_date from clues_billing_shipping_table_rates where type='".$cmpship['table_rate_type']."' and `from`<=". $weight ." and `to`>=". $weight ."";
			$ff_charge_arr = db_get_array($sql);   
            $ff_flag = 0;

            foreach($ff_charge_arr as $ff_charge_res){
				$effective_date = '';
                $payment_type_new = 'fee_'.$payment_type;
                $effective_date = strtotime($ff_charge_res['effective_date']);
				
                if($effective_date <= time() && $effective_date != ''){
                    $ff_charg = $ff_charge_res[$payment_type_new];
                    $ff_flag = 1;
                }
                if($ff_flag != 1){
                    $ff_charg = $ff_charge_res[$payment_type_new];
					$ff_flag = 1;
                }	
            }
     //vk changes end
        }
		
	}
	else
	{
		$ff_charg = $cmpship['fee_' . $payment_type . '_flatrate'];
	}
  
	return $ff_charg;
}


?>
