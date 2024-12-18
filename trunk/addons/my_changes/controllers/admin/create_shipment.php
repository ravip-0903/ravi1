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

$page_access_premission = fn_check_priviledges("manage_shipment");
if($page_access_premission == '')
{
	fn_set_notification('E','Order','You cannot access this page','I');
	return array(CONTROLLER_STATUS_REDIRECT, "");
}


if($mode == 'new')

{

	unset($_SESSION['shipment_order_list']);

	unset($_SESSION['shipment_data_filename']);

	

	if($_REQUEST['mode_action'] == 'import')

	{

		$order_ids = array();

		if(isset($_REQUEST['ordernumberlist']) && $_REQUEST['ordernumberlist'] != '' 

		&& isset($_FILES["csvfile"]) && $_FILES["csvfile"]['name'] != '')

		{

			fn_set_notification('E','Order','Either upload file or enter order numbers','I');

			unset($_REQUEST['redirect_url']);

			return array(CONTROLLER_STATUS_REDIRECT, "create_shipment.new");

		}

		else if((isset($_REQUEST['ordernumberlist']) && $_REQUEST['ordernumberlist'] != '') || isset($_FILES["csvfile"]))

		{

			if(isset($_FILES["csvfile"]) && $_FILES["csvfile"]['name'] != '')

			{

				if(strpos($_FILES["csvfile"]['name'],'.csv') !== FALSE)

				{

					if($_FILES["csvfile"]["size"] < 2000000)

					{

						if($_FILES["csvfile"]["error"] == 0)

						{

							$filename = 'Order-Ids-'.date('m-d-Y-H-i-s').'-'.$_FILES["csvfile"]["name"];

							move_uploaded_file($_FILES["csvfile"]["tmp_name"], "images/excel_upload/" . $filename);

						

							$records = array();

							$handle = fopen("images/excel_upload/" . $filename, "r");

							if($handle) 

							{

								while(($data = fgetcsv($handle, 1000, ",")) !== FALSE) 

								{

									$records[] = $data;

								}

								fclose($handle);

							}

							

							foreach($records as $rkey => $rvalue)

							{

								if(is_array($rvalue))

								{

									foreach($rvalue as $key => $value)

									{

										if($rkey == 0)

										{

											if($value == 'Order No.' || $value == 'Order No' || $value == 'Order Number')

											{

												$required_key = $key;

											}

											else

											{

												continue;

											}

										}

										else

										{

											if($key == $required_key)

											{

												$order_ids[] = $value;

											}

											else

											{

												continue;

											}

										}

									}

								}

							}

							

							$order_ids = array_unique($order_ids);

							$order_ids = implode('-',$order_ids);

							$_SESSION['shipment_order_list'] = base64_encode(gzdeflate($order_ids));

							

							unset($_REQUEST['redirect_url']);

							return array(CONTROLLER_STATUS_REDIRECT, "create_shipment.list");

						}

						else

						{

							fn_set_notification('E','Order','File Error-'.$_FILES["csvfile"]["error"],'I');

							unset($_REQUEST['redirect_url']);

							return array(CONTROLLER_STATUS_REDIRECT, "create_shipment.new");

						}

					}

					else

					{

						fn_set_notification('E','Order','File size too big','I');

						unset($_REQUEST['redirect_url']);

						return array(CONTROLLER_STATUS_REDIRECT, "create_shipment.new");

					}

				}

				else

				{

					fn_set_notification('E','Order','Invalid file format','I');

					unset($_REQUEST['redirect_url']);

					return array(CONTROLLER_STATUS_REDIRECT, "create_shipment.new");

				}

			}

			else if(isset($_REQUEST['ordernumberlist']) && $_REQUEST['ordernumberlist'] != '')

			{

				$order_ids = preg_split('/[,;\s]/', $_REQUEST['ordernumberlist'], -1, PREG_SPLIT_NO_EMPTY);

				$order_ids = array_filter($order_ids);

				$order_ids = array_unique($order_ids);

				

				$order_ids = implode('-',$order_ids);

				$_SESSION['shipment_order_list'] = base64_encode(gzdeflate($order_ids));

				

				unset($_REQUEST['redirect_url']);

				return array(CONTROLLER_STATUS_REDIRECT, "create_shipment.list");

			}

		}

		else

		{

			fn_set_notification('E','Order','Either upload file or enter order numbers','I');

			unset($_REQUEST['redirect_url']);

			return array(CONTROLLER_STATUS_REDIRECT, "create_shipment.new");

		}
	}
	$view->assign('page_title','Shipment Creation System');
}



if ($mode == 'list') 

{

	if(isset($_SESSION['shipment_order_list']) && $_SESSION['shipment_order_list'] !== NULL)

	{

		$order_ids = explode('-', gzinflate(base64_decode($_SESSION['shipment_order_list'])));

		

		$unsuccessful_order_ids = array();

		$successful_order_ids = array();

		

		sort($order_ids);

		$order_ids = array_unique(array_filter($order_ids));

		

		$order_ids = array_map('trim', $order_ids);



		$query = "SELECT order_id FROM `cscart_orders` WHERE order_id IN ('" . implode("','", $order_ids) . "') ";

		$res = mysql_query($query);

		if(mysql_num_rows($res) > 0)

		{

			while($row = mysql_fetch_assoc($res))

			{

				$successful_order_ids[] = $row['order_id'];

			}

		}

		

		

		$unsuccessful_order_ids = array_diff($order_ids, $successful_order_ids);

	

		foreach($successful_order_ids as $ikey => $ivalue)

		{

			$order_info[$ikey] = fn_get_order_info($ivalue);

		

			$order_info[$ikey]['payment_method']['payment'] = 

			str_replace(',', ' ', $order_info[$ikey]['payment_method']['payment']);

			

			$order_info[$ikey]['merchant_detail'] = fn_get_company_name($order_info[$ikey]['company_id']);

			$order_info[$ikey]['merchant_detail'] = str_replace(',', ' ', $order_info[$ikey]['merchant_detail']);

			

			$order_info[$ikey]['status_name'] = fn_get_status_data($order_info[$ikey]['status']);

			$order_info[$ikey]['status_name'] = str_replace(',', ' ', $order_info[$ikey]['status_name']);

			

			$manifest_details = fn_get_manifest_details($order_info[$ikey]['order_id']);

			$order_info[$ikey]['manifest_details'] = $manifest_details[0];

			$order_info[$ikey]['manifest_details']['manifest_id'] = 

			str_replace(',', ' ', $order_info[$ikey]['manifest_details']['manifest_id']);

			$order_info[$ikey]['manifest_details']['dispatch_date'] = 

			str_replace(',', ' ', $order_info[$ikey]['manifest_details']['dispatch_date']);

			

			

			if(isset($order_info[$ikey]['items']) && !empty($order_info[$ikey]['items']))

			{

				foreach($order_info[$ikey]['items'] as $tkey => $tvalue)

				{

					$order_info[$ikey]['product_details'] .= $tvalue['product'] . ' ';

				}

			}

				

			if(isset($order_info[$ikey]['shipment_ids']) && !empty($order_info[$ikey]['shipment_ids']))

			{

				foreach($order_info[$ikey]['shipment_ids'] as $skey => $svalue)

				{

					$shipping_details = get_shipment_data($svalue);

					$order_info[$ikey]['shipment_id'] .= '1 ';

					$order_info[$ikey]['tracking_number'] .= $shipping_details[0]['tracking_number'] . ' ';

					$order_info[$ikey]['carrier'] .= $shipping_details[0]['carrier'] . ' ';

					$order_info[$ikey]['weight'] .= $shipping_details[0]['weight'] . ' ';

				}

			}

		}

		$unsuccessful_order_ids = array_filter(array_unique($unsuccessful_order_ids));

		

		if(!empty($unsuccessful_order_ids))

		{

			$summary_message = '';

			foreach($unsuccessful_order_ids as $ukey => $uvalue)

			{

				$summary_message .= $uvalue . ", ";

			}

			fn_set_notification('E','Invalid Order Ids: ', substr($summary_message, 0, -2),'I');

		}

		$view->assign('type','downloadorderdata');

	}

	else if(isset($_SESSION['shipment_data_filename']) && $_SESSION['shipment_data_filename'] != '')

	{

		$records = array();

		$order_ids = array();

		$unsuccessful_order_ids = array();

		$successful_order_ids = array();

		$unsuccessful_order_ids_from_validation = array();

		

		$weight_index = NULL;

		$manifest_id_index = NULL;

		$manifest_date_index = NULL;

		$filename = gzinflate(base64_decode($_SESSION['shipment_data_filename']));

		

		$handle = fopen("images/excel_upload/" . $filename, "r");

		if($handle) 

		{

			while(($data = fgetcsv($handle, 1000, ",")) !== FALSE) 

			{

				$records[] = $data;

			}

			fclose($handle);

		}

		

		foreach($records as $rkey => $rvalue)

		{	

			if($rkey == 0)

			{

				foreach($rvalue as $key => $value)

				{	

					$value = trim($value);

					if($value == 'Order No.' || $value == 'Order No' || $value == 'Order Number')

					{

						$order_index = $key;

					}

					if($value == 'AWB No.' || $value == 'AWB No' || $value == 'AWB Number'

					|| $value == 'Tracking No.' || $value == 'Tracking No' || $value == 'Tracking Number')

					{

						$tracking_index = $key;

					}

					if($value == 'Carrier Name' || $value == 'Courier Name')

					{

						$carrier_index = $key;

					}

					if($value == 'Shipment Weight' || $value == 'Weight')

					{

						$weight_index = $key;

					}

					if($value == 'Manifest Id')

					{

						$manifest_id_index = $key;

					}

					if($value == 'Manifest Dispatch Date')

					{

						$manifest_date_index = $key;

					}

				}

				unset($records[0]);

			}

			else

			{

				$absent_fields = array();

				$rvalue[$order_index] = trim($rvalue[$order_index]);

				$rvalue[$tracking_index] = trim($rvalue[$tracking_index]);

				$rvalue[$carrier_index] = trim($rvalue[$carrier_index]);

				

				if($rvalue[$order_index] == '' || $rvalue[$order_index] === NULL)

				{

					$absent_fields[] = $rkey;

					$unsuccessful_order_ids_from_validation[$rvalue[$order_index]][] 

					= 'Invalid upload file format: "Order Number" not present.';

					unset($records[$rkey]);

				}

				

				if($rvalue[$tracking_index] == '' || $rvalue[$tracking_index] === NULL)

				{

					$absent_fields[] = $rkey;

					$unsuccessful_order_ids_from_validation[$rvalue[$order_index]][] 

					= 'Invalid upload file format: "Tracking Number" not present.';

					unset($records[$rkey]);

				}

				

				if($rvalue[$carrier_index] == '' || $rvalue[$carrier_index] === NULL)

				{

					$absent_fields[] = $rkey;

					$unsuccessful_order_ids_from_validation[$rvalue[$order_index]][] 

					= 'Invalid upload file format: "Carrier Name" not present.';

					unset($records[$rkey]);

				}

				

				if(empty($absent_fields))

				{

					$order_ids[$rvalue[$order_index]] = $rvalue[$order_index];

				}

			}

		}

		

		$query = "SELECT order_id FROM `cscart_orders` WHERE order_id IN ('" . implode("','", $order_ids) . "') ";

		$res = mysql_query($query);

		if(mysql_num_rows($res) > 0)

		{

			while($row = mysql_fetch_assoc($res))

			{

				$successful_order_ids[$row['order_id']] = $row['order_id'];

			}

		}

		

		$unsuccessful_order_ids = array_diff_assoc($order_ids, $successful_order_ids);

		

		foreach($unsuccessful_order_ids as $ukey => $uvalue)

		{

			$unsuccessful_order_ids[$ukey] = array();

			$unsuccessful_order_ids[$ukey][] = 'Invalid Order Id';

		}

		

		$unsuccessful_order_ids = $unsuccessful_order_ids + $unsuccessful_order_ids_from_validation;

		

		foreach($records as $rkey => $rvalue)

		{

			if(array_key_exists($rvalue[$order_index], $unsuccessful_order_ids))

			{

				unset($records[$rkey]);

			}

		}

		

		$indx = 0;

		foreach($records as $rkey => $rvalue)

		{

			$order_info[$indx] = fn_get_order_info($rvalue[$order_index]);

			

			$order_info[$indx]['order_id'] = $rvalue[$order_index];

			$order_info[$indx]['tracking_number'] = $rvalue[$tracking_index];

			$order_info[$indx]['carrier'] = $rvalue[$carrier_index];

			

			if(isset($weight_index) && $weight_index !== NULL)

			{	

				$order_info[$indx]['weight'] = $rvalue[$weight_index];

			}

			else

			{

				foreach($order_info[$indx]['shipment_ids'] as $skey => $svalue)

				{

					$shipping_details = get_shipment_data($svalue);

					$order_info[$indx]['weight'] .= $shipping_details[0]['weight'] . ' ';

				}

			}

			

			if(isset($order_info[$indx]['items']) && !empty($order_info[$indx]['items']))

			{

				foreach($order_info[$indx]['items'] as $tkey => $tvalue)

				{

					$order_info[$indx]['product_details'] .= $tvalue['product'] . ' ';

				}

			}

			

			if(isset($manifest_id_index) && $manifest_id_index !== NULL 

			&& isset($manifest_date_index) && $manifest_date_index !== NULL)

			{

				$order_info[$indx]['manifest_details']['manifest_id'] = $rvalue[$manifest_id_index];

				$order_info[$indx]['manifest_details']['dispatch_date'] = $rvalue[$manifest_date_index];

			}

			else

			{

				$manifest_details = fn_get_manifest_details($order_info[$indx]['order_id']);

				$order_info[$indx]['manifest_details'] = $manifest_details[0];

			}

			

			$order_info[$indx]['merchant_detail'] = fn_get_company_name($order_info[$indx]['company_id']);

			$order_info[$indx]['status_name'] = fn_get_status_data($order_info[$indx]['status']);

			

			++$indx;

		}

		

		if(!empty($unsuccessful_order_ids))

		{

			$summary_message = "<br/>";

			foreach($unsuccessful_order_ids as $ukey => $uvalue)

			{

				$summary_message .= $ukey . ': ' . implode(', ',  $uvalue) . "<br/>";

			}

			fn_set_notification('E','Message',$summary_message,'I');

		}

		$view->assign('type','createshipment');

	}

	
	$view->assign('page_title','Shipment Creation Review Page');
	$view->assign('order_info',$order_info);

	

	if(isset($_GET['mode_action']) && $_GET['mode_action'] == 'downloadorderdata')

	{

		$filename = "Shipment_Order_Details_".date("Y_m_d");

		header('Content-type: text/csv');

		header('Content-disposition: attachment; filename="' . $filename . '.csv"');

		

		$output_str = "Serial No.,Order No.,AWB No.,Carrier Name,Weight,Payment Mode,Merchant Name,Buyer Name,Shipping Address 1,"

					. "Shipping Address 2,Shipping City,Shipping State,Shipping Pincode,Buyer Phone No.,Total Order Amount,"

					. "Collectible Amount,Product Details,Manifest Id,Manifest Dispatch Date\n";

		if(isset($order_info) && !empty($order_info))

		{

			foreach($order_info as $okey => $ovalue)

			{

				if($ovalue['payment_method']['payment'] == '' && !isset($ovalue['use_gift_certificates']))

				{

					$ovalue['payment_method'] = 'CluesBucks';

				}

				else if(isset($ovalue['use_gift_certificates']))

				{

					$ovalue['payment_method'] = 'Gift Certificate';

				}

				else

				{

					$ovalue['payment_method'] = $ovalue['payment_method']['payment'];

				}

				

				if($ovalue['payment_method']['payment_id'] == 6)

				{

					$ovalue['total'] = $ovalue['total'];

				}

				else

				{

					$ovalue['total'] = 0.00;

				}

				

				$output_str .= ($okey+1) . ","

							  . $ovalue['order_id'] . ","

							  . str_replace(',', ' ', $ovalue['tracking_number']) . ","

							  . str_replace(',', ' ', $ovalue['carrier']) . ","

							  . str_replace(',', ' ', $ovalue['weight']) . ","

							  . str_replace(',', ' ', $ovalue['payment_method']) . ","

							  . str_replace(',', ' ', $ovalue['merchant_detail']) . ','

							  . str_replace(',', ' ', $ovalue['b_firstname'] . ' ' . $ovalue['b_lastname']) . ","

							  . str_replace(',', ' ', $ovalue['s_address']) . ","

							  . str_replace(',', ' ', $ovalue['s_address_2']) . ","

							  . str_replace(',', ' ', $ovalue['s_city']) . ","

							  . str_replace(',', ' ', $ovalue['s_state']) . ","

							  . str_replace(',', ' ', $ovalue['s_zipcode']) . ","

							  . str_replace(',', ' ', $ovalue['b_phone']) . ","

							  . number_format(str_replace(',', ' ', $ovalue['subtotal']),2,".","") . ","

							  . number_format(str_replace(',', ' ', $ovalue['total']),2,".","") . ","

							  . str_replace(',', ' ', $ovalue['product_details']) . ","

							  . str_replace(',', ' ', $ovalue['manifest_details']['manifest_id']) . ","

							  . str_replace(',', ' ', $ovalue['manifest_details']['dispatch_date']) . "\n";

			}

			echo $output_str;

			exit;

		}

	}

	

	if(isset($_GET['mode_action']) && $_GET['mode_action'] == 'saveshipmentdata')

	{
    $order_statues = implode("','",Registry::get('config.order_statuses_for_create_manifest'));
		//$result = mysql_query("SELECT DISTINCT status FROM `cscart_status_descriptions` ". " WHERE description IN ('Completed', 'Failed', 'Canceled', 'Declined')");
		$result = mysql_query("SELECT DISTINCT status FROM `cscart_status_descriptions` ". " WHERE description IN ('".$order_statues."')");
		if(mysql_num_rows($result) > 0)
		{
			while($row = mysql_fetch_assoc($result))
			{
				$defective_status_values[] = $row['status'];
			}
		}

		$successful_shipment = array();

		$unsuccessful_shipment = array();

		$defective_order_ids = array();

		

		function getOrderIds($a)

		{

			return $a['order_id'];

		}

		

		$all_order_ids = array_map("getOrderIds", $order_info);
		
		/*$all_order_ids = array_map(create_function($a) {

			return $a['order_id'];

		}, $order_info);*/
		

		$query = "SELECT DISTINCT csi.order_id, cs.shipment_id FROM `cscart_shipment_items` csi "

				. " LEFT JOIN `cscart_shipments` cs ON csi.shipment_id = cs.shipment_id  WHERE csi.order_id IN ('" 

				. implode("','", $all_order_ids) . "') ";

		$res = mysql_query($query);

		if(mysql_num_rows($res) > 0)

		{

			while($row = mysql_fetch_assoc($res))

			{
				//commented below line to remove a validation. by HPRAHI 
				$sql = "select count(id) from clues_order_history where order_id='".$row['order_id']."' and to_status in ('J','R')";
				if(db_get_field($sql) == 0)
				{
					$defective_order_ids[] = $row['order_id'];
				}

			}

		}
		

		foreach($order_info as $okey => $ovalue)

		{	

			if($ovalue['order_id'] != '' && $ovalue['order_id'] !== NULL)

			{

				$details = fn_get_order_info($ovalue['order_id'], false, true, true);

				$status = fn_get_status_data($details['status']);

				

				if(!empty($details['items']))

				{

					if(in_array($details['status'], $defective_status_values))

					{	

						$unsuccessful_shipment[$details['order_id']] = 'Invalid Order status: ' . $status['description'];

					}

					else if(in_array($ovalue['order_id'], $defective_order_ids))

					{	

						$unsuccessful_shipment[$details['order_id']] = 'Shipment already exists';

					}

					else if($ovalue['order_id'] == '' || $ovalue['order_id'] === NULL)

					{	

						$unsuccessful_shipment[$details['order_id']] = 'Order Id not present.';

					}

					else if($ovalue['tracking_number'] == '' || $ovalue['tracking_number'] === NULL)

					{	

						$unsuccessful_shipment[$details['order_id']] = 'AWB Number not present.';

					}

					else if($ovalue['carrier'] == '' || $ovalue['carrier'] === NULL)

					{	

						$unsuccessful_shipment[$details['order_id']] = 'Carrier Name not present.';

					}

					else

					{	

						$shipment_timestamp = time();

						if($ovalue['weight'] != '' && $ovalue['weight'] !== NULL)

						{

							$weight = preg_replace("/[^0-9]+/", "", $ovalue['weight']);

							if($weight > 0)

							{

							$query = "INSERT INTO `cscart_shipments` (shipping_id, tracking_number, carrier, timestamp, weight) "

								. " VALUES('1', '" . $ovalue['tracking_number'] 

								. "', '" . $ovalue['carrier'] . "', '" . $shipment_timestamp . "', '" . $ovalue['weight'] . "') ";

							}

							else

							{

							$query = "INSERT INTO `cscart_shipments` (shipping_id, tracking_number, carrier, timestamp) "

								. " VALUES('1', '" . $ovalue['tracking_number'] 

								. "', '" . $ovalue['carrier'] . "', '" . $shipment_timestamp . "') ";

							}

						}

						else

						{

							$query = "INSERT INTO `cscart_shipments` (shipping_id, tracking_number, carrier, timestamp) "

								. " VALUES('1', '" . $ovalue['tracking_number'] 

								. "', '" . $ovalue['carrier'] . "', '" . $shipment_timestamp . "') ";

						}

						//echo '<br/>query' . $query;		

						$res = mysql_query($query);

						$shipment_id = mysql_insert_id();

						

						foreach($details['items'] as $ikey => $ivalue)

						{

							$query = "INSERT INTO `cscart_shipment_items` (item_id, shipment_id, order_id, product_id, amount) "

									. " VALUES('" . $ikey . "', '" . $shipment_id . "', '" . $details['order_id'] 

									. "', '" . $ivalue['product_id'] . "', '" . $ivalue['amount'] . "') ";

							$res = mysql_query($query);

						}

						$successful_shipment[$details['order_id']] = 'Shipment created successfully';

						$force_notification = fn_get_notification_rules(true);

						

						if (!empty($force_notification['C'])) 

						{

							$shipment_data = array(

								'shipment_id' => $shipment_id,

								'timestamp' => $shipment_timestamp,

								'shipping' => db_get_field('SELECT shipping FROM ?:shipping_descriptions WHERE shipping_id = ?i AND lang_code = ?s', $details['shipping_ids'], $details['lang_code']),

								'tracking_number' => $ovalue['tracking_number'],

								'carrier' => $ovalue['carrier'],

								'items' => $ovalue['product_details'],

							);

							

							$view_mail->assign('shipment', $shipment_data);

							$view_mail->assign('order_info', $details);

			

							$company_details = fn_get_company_placement_info($details['company_id'], $details['lang_code']);

							$view_mail->assign('company_placement_info', $company_details);

					
							/*modified by clues dev*/
							fn_send_mail($ovalue['email'], Registry::get('settings.Company.company_orders_department'),

							'shipments/shipment_products_subj.tpl', 'shipments/shipment_products.tpl', '', $details['lang_code']);

							

							fn_change_order_status($details['order_id'], 'A');

						}

					}

				}

				else

				{

					$unsuccessful_shipment[$details['order_id']] = 'No products selected for shipment';

				}

			}

			else

			{

				$unsuccessful_shipment[$details['order_id']] = 'Invalid Order Id';

			}

		}

		

		

		$summary_message = "<br/>Shipment created successfully for order ids: <br/>";

							foreach($successful_shipment as $skey => $svalue)

							{

								$summary_message .= $skey . ': ' . $svalue . "<br/>";

							}



		$summary_message .= "<br/>Shipment unsuccessful for order ids: <br/>";

							foreach($unsuccessful_shipment as $ukey => $uvalue)

							{

								$summary_message .= $ukey . ': ' . $uvalue . "<br/>";

							}

		if(empty($successful_shipment))

		{

			fn_set_notification('E','Summary: ',$summary_message,'I');

		}

		else

		{

			fn_set_notification('N','Summary: ',$summary_message,'I');

		}

		unset($_REQUEST['redirect_url']);

		return array(CONTROLLER_STATUS_REDIRECT, "create_shipment.list");

	}



}



if ($mode == 'upload') 

{

	unset($_SESSION['shipment_order_list']);

	unset($_SESSION['shipment_order_list']);

	

	if($_REQUEST['mode_action'] == 'import')

	{

		if(isset($_FILES["csvfile"]) && $_FILES["csvfile"]['name'] != '')

		{

			if(isset($_FILES["csvfile"]) && $_FILES["csvfile"]['name'] != '')

			{

				if(strpos($_FILES["csvfile"]['name'],'.csv') !== FALSE)

				{

					if($_FILES["csvfile"]["size"] < 2000000)

					{

						if($_FILES["csvfile"]["error"] == 0)

						{

							$absent_fields = array();

							$filename = 'Shipment-Data-'.date('m-d-Y-H-i-s').'-'.$_FILES["csvfile"]["name"];

							move_uploaded_file($_FILES["csvfile"]["tmp_name"], "images/excel_upload/" . $filename);

						

							$records = array();

							$handle = fopen("images/excel_upload/" . $filename, "r");

							if($handle) 

							{

								while(($data = fgetcsv($handle, 1000, ",")) !== FALSE) 

								{

									$records[] = $data;

								}

								fclose($handle);

							}

							

							if((array_search('Order No.', $records[0]) === FALSE) 

							&& (array_search('Order No', $records[0]) === FALSE) 

							&& (array_search('Order Number', $records[0]) === FALSE))

							{

								$absent_fields[] = 'Invalid upload file format: "Order Number" not present.';

							}

							

							if((array_search('AWB No.', $records[0]) === FALSE)

							&& (array_search('AWB No', $records[0]) === FALSE) 

							&& (array_search('AWB Number', $records[0]) === FALSE)

							&& (array_search('Tracking No.', $records[0]) === FALSE) 

							&& (array_search('Tracking No', $records[0]) === FALSE) 

							&& (array_search('Tracking Number', $records[0]) === FALSE))

							{

								$absent_fields[] = 'Invalid upload file format: "Tracking Number" not present.';

							}

							

							if((array_search('Carrier Name', $records[0]) === FALSE)

							&& (array_search('Courier Name', $records[0]) === FALSE))

							{

								$absent_fields[] = 'Invalid upload file format: "Carrier Name" not present.';

							}

							

							if(!empty($absent_fields))

							{

								$message = implode("\n", $absent_fields);

								fn_set_notification('E','Shipment Data',$message,'I');

								unset($_REQUEST['redirect_url']);

								return array(CONTROLLER_STATUS_REDIRECT, "create_shipment.upload");

							}

							else

							{

								$_SESSION['shipment_data_filename'] = base64_encode(gzdeflate($filename));

								unset($_SESSION['shipment_order_list']);

								unset($_REQUEST['redirect_url']);

								return array(CONTROLLER_STATUS_REDIRECT, "create_shipment.list");

							}

						}

						else

						{

							fn_set_notification('E','Order','File Error-'.$_FILES["csvfile"]["error"],'I');

							unset($_REQUEST['redirect_url']);

							return array(CONTROLLER_STATUS_REDIRECT, "create_shipment.upload");

						}

					}

					else

					{

						fn_set_notification('E','Order','File size too big','I');

						unset($_REQUEST['redirect_url']);

						return array(CONTROLLER_STATUS_REDIRECT, "create_shipment.list");

					}

				}

				else

				{

					fn_set_notification('E','Order','Invalid file format','I');

					unset($_REQUEST['redirect_url']);

					return array(CONTROLLER_STATUS_REDIRECT, "create_shipment.upload");

				}

			}

		}

		else

		{

			fn_set_notification('E','Order','upload file','I');

			unset($_REQUEST['redirect_url']);

			return array(CONTROLLER_STATUS_REDIRECT, "create_shipment.upload");

		}

	}
	$view->assign('page_title','Shipment Creation System');
}



function fn_get_manifest_details($order_id)

{

	$manifest_details = array();

	//echo '<br/>-->'.

	$query = "SELECT com.manifest_id, com.dispatch_date "
			. " FROM `clues_order_manifest_details` comd "
			. " JOIN `clues_order_manifest` com ON comd.manifest_id = com.manifest_id "
			. " WHERE comd.order_id = '" . $order_id . "' ORDER BY comd.manifest_id DESC LIMIT 0,1 ";

	$res = mysql_query($query);

	if(mysql_num_rows($res) > 0)

	{

		$indx = 0;

		while($row = mysql_fetch_assoc($res))

		{

			$manifest_details[$indx]['manifest_id'] = $row['manifest_id'];

			$manifest_details[$indx]['dispatch_date'] = $row['dispatch_date'];

			++$indx;

		}

	}

	

	return $manifest_details;

}

?>
