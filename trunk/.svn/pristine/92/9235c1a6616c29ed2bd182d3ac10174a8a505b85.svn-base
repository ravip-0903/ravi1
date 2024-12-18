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

$manage_page_access = fn_check_priviledges("manage_manifest");
if($manage_page_access == '')
{
	fn_set_notification('E','Order','You cannot access this page','I');
	return array(CONTROLLER_STATUS_REDIRECT, "");
}

$params = $_REQUEST;

if($mode == 'manifest_generate')
{
	$user_name = fn_get_user_name($_SESSION['auth']['user_id']);
	if($_SESSION['auth']['company_id'] == '0')
	{
		$center_details = get_all_center_details();
		$view->assign('by_location',$center_details);
		$view->assign('manf_drop_id',1);
	}else{
		$center_details = fn_get_warehouse_data($_SESSION['auth']['company_id']);
		$vendor_location = $center_details['company_name'].','.$center_details['warehouse_city'].','.$center_details['warehouse_state'];
		$view->assign('vendor_location',$vendor_location);
		$view->assign('manf_drop_id',2);
	}
	//exit;
	$manifest_type = get_manifest_type();
	$view->assign('manifest_type',$manifest_type);
	$view->assign('company_id',$_SESSION['auth']['company_id']);
	$view->assign('user_name',$user_name);
	$view->assign('page_title','Manifest Creation :: Step 1');
	$found = '';
	$not_found = ''; //print_r($_REQUEST);die();
if ($_REQUEST['mode_action'] == 'import') 
	{	
		if(($_REQUEST['text_area'] != '' && $_FILES["file"]["type"] != '') || ($_REQUEST['text_area'] == '' && $_FILES["file"]["type"] == ''))
		{
			fn_set_notification('E','Order','Either upload file or enter order numbers','I');
			unset($_REQUEST['redirect_url']);
			return array(CONTROLLER_STATUS_REDIRECT, "manifest_create.manifest_generate");
		}
		if($_REQUEST['text_area'] != '')
		{
			$string = array("\n",",",";"," ");
			$order_ids = explode(" ", trim(str_replace($string," ",$_REQUEST['text_area'])));
			
				$_SESSION['manifest_order_list']['orders']['data'] = array('order_id' => $order_ids,'carrier_name' => $_REQUEST['shipment_data']['carrier'], 'dispatch_date' => $_REQUEST['dispatch_date'], 'dispatch_report' => $_REQUEST['dispatch_report'],'boy_name' => $_REQUEST['boy_name'], 'vehicle_no' => $_REQUEST['vehicle_no'], 'by_location' => $_REQUEST['by_location'], 'report_by' => $_REQUEST['report_by'], 'manifest_type_id' => $_REQUEST['manifest_type'], 'notes' => $_REQUEST['notes']);

				
				unset($_REQUEST['redirect_url']);
				return array(CONTROLLER_STATUS_REDIRECT, "manifest_create.manifest_list_view");
		}else{
			if (($_FILES["file"]["type"] == "application/vnd.ms-excel" || $_FILES["file"]["type"] == "text/csv") && ($_FILES["file"]["size"] < 2000000))
			{
				$filename = date('m-d-Y-H-i-s').''.$_FILES["file"]["name"];
				if ($_FILES["file"]["error"] > 0)
				{
					echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
				}
				else
				{				
					if (file_exists("images/excel_upload/" . $filename ))
					{
						echo $filename . " already exists. ";
					}
					else
					{
						move_uploaded_file($_FILES["file"]["tmp_name"], "images/excel_upload/" . $filename);
					}
				}
				
				$arrResult = array();
				$handle = fopen("images/excel_upload/" . $filename, "r");
				if( $handle ) 
				{
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
				{
				$arrResult[] = $data;
				}
				fclose($handle);
				}
				
				
				foreach($arrResult as $k => $v)
				{
					$arr = implode(",",$arrResult[$k]);
					if(!empty($arr))
					{
						foreach($v as $k1 => $v1)
						{
								$name = trim($arrResult[0][$k1]);
								if($k != 0)
								{
									$newArray[$name][] = $v1;
								}
						}
					}
				}
				
				if(isset($newArray['Order No']))
				{
					$order_ids = $newArray['Order No'];
				}else if(isset($newArray['Order No.'])){
					$order_ids = $newArray['Order No.'];
				}else if(isset($newArray['Order Number'])){
					$order_ids = $newArray['Order Number'];
				}else{
					fn_set_notification('E','Order','Please check column name or file format','I');
					unset($_REQUEST['redirect_url']);
					return array(CONTROLLER_STATUS_REDIRECT, "manifest_create.manifest_generate");
				}
				
				$_SESSION['manifest_order_list']['orders']['data'] = array('order_id' => $order_ids, 'carrier_name' => $_REQUEST['shipment_data']['carrier'], 'dispatch_date' => $_REQUEST['dispatch_date'], 'dispatch_report' => $_REQUEST['dispatch_report'], 'boy_name' => $_REQUEST['boy_name'], 'vehicle_no' => $_REQUEST['vehicle_no'], 'by_location' => $_REQUEST['by_location'], 'report_by' => $_REQUEST['report_by'], 'manifest_type_id' => $_REQUEST['manifest_type'], 'notes' => $_REQUEST['notes']);
				
				unset($_REQUEST['redirect_url']);
				return array(CONTROLLER_STATUS_REDIRECT, "manifest_create.manifest_list_view");
	
				
				
			}
			else
			{
				fn_set_notification('E','Order','Invalid File','I');
				unset($_REQUEST['redirect_url']);
				return array(CONTROLLER_STATUS_REDIRECT, "manifest_create.manifest_generate");
			}
		}
		//unset($_REQUEST['redirect_url']);
			//return array(CONTROLLER_STATUS_REDIRECT, "manifest_create.manifest_list_view");
	}
}

if ($mode == 'manifest_list_view') {
	
	$courier_name = $_SESSION['manifest_order_list']['orders']['data']['carrier_name'];
	$dispatch_date = $_SESSION['manifest_order_list']['orders']['data']['dispatch_date'];
	$order_ids1 = $_SESSION['manifest_order_list']['orders']['data']['order_id'];
	$dispatch_report = $_SESSION['manifest_order_list']['orders']['data']['dispatch_report'];
	$boy_name = $_SESSION['manifest_order_list']['orders']['data']['boy_name'];
	$vehicle_no = $_SESSION['manifest_order_list']['orders']['data']['vehicle_no'];
	$by_location = $_SESSION['manifest_order_list']['orders']['data']['by_location'];
	$report_by = $_SESSION['manifest_order_list']['orders']['data']['report_by'];
	$manifest_type_id = $_SESSION['manifest_order_list']['orders']['data']['manifest_type_id'];
	$notes = $_SESSION['manifest_order_list']['orders']['data']['notes'];

	if($manifest_type_id == 1)
	{
		$order_statues = implode("','",Registry::get('config.status_for_manifest_dispatch'));
	}elseif($manifest_type_id == 3)
	{
		$order_statues = implode("','",Registry::get('config.status_for_manifest_milkrun'));
	}elseif($manifest_type_id == 4)
	{
		$order_statues = implode("','",Registry::get('config.status_for_manifest_return_to_merchant'));
	}else
	{
		$order_statues = implode("','",Registry::get('config.status_for_manifest_courier'));
	}

	foreach($order_ids1 as $k=>$v)
			{
				$query = "SELECT * FROM cscart_orders where order_id='".$v."' and status IN ('".$order_statues."')";
				$result = db_query($query);
				$num_rows = mysql_num_rows($result);
				if($num_rows > 0)
				{
					$found[] = $v; 
				}else{
					$not_found[] = $v;
				}
			}
			
	if(!empty($not_found)){
	$not_found_imp = implode("",$not_found);
	}
	
	if($not_found_imp != '' && $manifest_type_id == 2)
	{
		$view->assign('not_found',$not_found);
	}else{
		if( $manifest_type_id != 2)
		{
			$view->assign('dispatch_not_found',$not_found);
		}
		$order_ids = $found;
		if($order_ids != '')
		{
		foreach ($order_ids as $k => $v) {
			$order_info[$k] = fn_get_order_info($v);
			$order_id = $order_info[$k]['order_id'];
			
			$merchant_detail[$order_id] = fn_get_company_name($order_info[$k]['company_id']);
			$status_name[$order_id] = fn_get_status_data($order_info[$k]['status']);
			
			foreach($order_info[$k]['items'] as $pk=>$pv)
			{
				$prod_merchant_sku = get_prod_merchant_sku($pv['product_id']);
				$merchant_no[$order_id][] = $prod_merchant_sku['merchant_reference_number'];
				//$prod_detail[$order_id][] = $pv['product'].' (Qty: '.$pv['amount'].' Unit Price: Rs. '.$pv['price'].') ';
				$prod_detail[$order_id][] = $pv['product'];
			}
			
			foreach($order_info[$k]['shipment_ids'] as $key=>$val)
			{
				$shipment_details = get_shipment_data($val);

				$ship_id[$order_id][] = $shipment_details[0]['shipment_id'];
				$track_no[$order_id][] = $shipment_details[0]['tracking_number']; 
				$carrier[$order_id][] = $shipment_details[0]['carrier'];
				$weight[$order_id][] = $shipment_details[0]['weight'];
			}
			if($ship_id[$order_id] != '')
			{
			$order_info[$k][$order_id]['ship_id'] = implode(',',$ship_id[$order_id]);
			}else{
				$order_info[$k][$order_id]['ship_id'] = '';
			}
			if($track_no[$order_id] != '')
			{
			$order_info[$k][$order_id]['track_no'] = implode(',',$track_no[$order_id]);
			}else{
				$order_info[$k][$order_id]['track_no'] = '';
			}
			if($carrier[$order_id] != '')
			{
			$order_info[$k][$order_id]['carrier'] = implode(',',$carrier[$order_id]);
			}else{
				$order_info[$k][$order_id]['carrier'] = '';
			}
			if($weight[$order_id] != '')
			{
			$order_info[$k][$order_id]['weight'] = implode(',',$weight[$order_id]);
			}else{
				$order_info[$k][$order_id]['weight'] = '';
			}
			if($merchant_no[$order_id] != '')
			{
			$prod_merchant_no[$k][$order_id]['merchant_no'] = implode(',',$merchant_no[$order_id]);
			}else{
				$prod_merchant_no[$k][$order_id]['merchant_no'] = '';
			}
			if($prod_detail[$order_id] != '')
			{
			$prod_details[$k][$order_id]['prod_detail'] = implode(',',$prod_detail[$order_id]);
			}else{
				$prod_details[$k][$order_id]['prod_detail'] = '';
			}
			if($order_detail['payment_method']['payment'] == '')
			{
				$order_detail['payment_method']['payment'] = '-';
			}
		}
		}
		
		$_SESSION['manifest_order_list']['orders']['data'] = array('order_id' => $found, 'carrier_name' => $courier_name, 'dispatch_date' => $dispatch_date, 'dispatch_report' => $dispatch_report, 'boy_name' => $boy_name, 'vehicle_no' => $vehicle_no, 'by_location' => $by_location, 'report_by' => $report_by, 'manifest_type_id' => $manifest_type_id, 'notes' => $notes);
		
		$manifest_type_name = get_manifest_type($manifest_type_id);
		
		$view->assign('list_order_id',$list_order_id);
		$view->assign('order_info',$order_info);
		$view->assign('merchant_name',$merchant_detail);
		$view->assign('prod_merchant_no',$prod_merchant_no);
		$view->assign('prod_details',$prod_details);
		$view->assign('status_name',$status_name);
		$view->assign('courier_name',$courier_name);
		$view->assign('dispatch_date',$dispatch_date);
		$view->assign('boy_name',$boy_name);
		$view->assign('vehicle_no',$vehicle_no);
		$view->assign('by_location',$by_location);
		$view->assign('report_by',$report_by);
		$view->assign('manifest_type_name',$manifest_type_name['description']);
		$view->assign('notes',$notes);
		$view->assign('page_title','Manifest Creation :: Step 2');
	}
}

if ($mode == 'manifest_save_list') {
	$user_name = fn_get_user_name($_SESSION['auth']['user_id']);
	$courier_name = $_SESSION['manifest_order_list']['orders']['data']['carrier_name'];
	$dispatch_date = $_SESSION['manifest_order_list']['orders']['data']['dispatch_date'];
	$order_id_arr = $_SESSION['manifest_order_list']['orders']['data']['order_id'];
	$order_ids = implode(",",$_SESSION['manifest_order_list']['orders']['data']['order_id']);
	$dispatch_report = $_SESSION['manifest_order_list']['orders']['data']['dispatch_report'];
	$boy_name = $_SESSION['manifest_order_list']['orders']['data']['boy_name'];
	$vehicle_no = $_SESSION['manifest_order_list']['orders']['data']['vehicle_no'];
	$by_location = $_SESSION['manifest_order_list']['orders']['data']['by_location'];
	$report_by = $_SESSION['manifest_order_list']['orders']['data']['report_by'];
	$manifest_type_id = $_SESSION['manifest_order_list']['orders']['data']['manifest_type_id'];
	$notes = $_SESSION['manifest_order_list']['orders']['data']['notes'];
	
	$insert_query1 = "INSERT INTO clues_order_manifest (carrier_name,dispatch_date,pickup_by,pickup_vehicle_no,generated_by_id,generated_by_name,pickup_location,manifest_type_id,notes,date_created) VALUES ('".$courier_name."','".$dispatch_date."','".$boy_name."','".$vehicle_no."','".$_SESSION['auth']['user_id']."','".$report_by."','".$by_location."','".$manifest_type_id."','".$notes."',now())";
	
	$result = db_query($insert_query1);
	$manifest_id = mysql_insert_id();
	
	$_SESSION['manifest_order_list']['orders']['data'] = array('order_id' => $order_id_arr, 'carrier_name' => $courier_name, 'dispatch_date' => $dispatch_date, 'manifest_id' => $manifest_id, 'dispatch_report' => $dispatch_report, 'boy_name' => $boy_name, 'vehicle_no' => $vehicle_no, 'by_location' => $by_location, 'report_by' => $report_by, 'manifest_type_id' => $manifest_type_id, 'notes' => $notes);
	
	foreach($order_id_arr as $k => $v)
	{
		$insert_query2 = "INSERT INTO clues_order_manifest_details (manifest_id,order_id,date_created) VALUES ('".$manifest_id."','".$v."',now())";
	
		$result = db_query($insert_query2) or die("Duplicate entries: " . mysql_error());
		if($result)
		{
			if($manifest_type_id == 2)
			{
				fn_change_order_status($v, 'L', '', $notify);
			}elseif($manifest_type_id == 4){
				fn_change_order_status($v, 'S', '', $notify);
			}
		}
	}
	
	unset($_REQUEST['redirect_url']);
	return array(CONTROLLER_STATUS_REDIRECT, "manifest_create.manifest_list_detail");
}

if ($mode == 'manifest_list_detail') {
	$view->assign('page_title','Manifest Creation :: Step 3');
	if(isset($_REQUEST['manifest_id']))
	{
		$manifest_details = get_manifest_details($_REQUEST['manifest_id']);
		$order_ids = $manifest_details['order_ids'];
		$courier_name = $manifest_details['courier_name'];
		$dispatch_date = $manifest_details['dispatch_date'];
		$manifest_id = $manifest_details['manifest_id'];
		$boy_name = $manifest_details['pickup_by'];
		$vehicle_no = $manifest_details['pickup_vehicle_no'];
		$by_location = $manifest_details['pickup_location'];
		$report_by = $manifest_details['generated_by_name'];
		$manifest_type_id = $manifest_details['manifest_type_id'];
		$notes = $manifest_details['notes'];
		$view->assign('manifest_id',$_REQUEST['manifest_id']);
	}else{
		$courier_name = $_SESSION['manifest_order_list']['orders']['data']['carrier_name'];
		$dispatch_date = $_SESSION['manifest_order_list']['orders']['data']['dispatch_date'];
		$order_ids = $_SESSION['manifest_order_list']['orders']['data']['order_id'];
		$manifest_id = $_SESSION['manifest_order_list']['orders']['data']['manifest_id'];
		$boy_name = $_SESSION['manifest_order_list']['orders']['data']['boy_name'];
		$vehicle_no = $_SESSION['manifest_order_list']['orders']['data']['vehicle_no'];
		$by_location = $_SESSION['manifest_order_list']['orders']['data']['by_location'];
		$report_by = $_SESSION['manifest_order_list']['orders']['data']['report_by'];
		$manifest_type_id = $_SESSION['manifest_order_list']['orders']['data']['manifest_type_id'];
		$notes = $_SESSION['manifest_order_list']['orders']['data']['notes'];
	}
		foreach ($order_ids as $k => $v) {
			$order_info[$k] = fn_get_order_info($v);
			$order_id = $order_info[$k]['order_id'];
			
			$merchant_detail[$order_id] = fn_get_company_name($order_info[$k]['company_id']);
			$status_name[$order_id] = fn_get_status_data($order_info[$k]['status']);
			
			foreach($order_info[$k]['items'] as $pk=>$pv)
			{
				$prod_merchant_sku = get_prod_merchant_sku($pv['product_id']);
				$merchant_no[$order_id][] = $prod_merchant_sku['merchant_reference_number'];
				//$prod_detail[$order_id][] = $pv['product'].' (Qty: '.$pv['amount'].' Unit Price: Rs. '.$pv['price'].') ';
				$prod_detail[$order_id][] = $pv['product'];
			}
			
			foreach($order_info[$k]['shipment_ids'] as $key=>$val)
			{
				$shipment_details = get_shipment_data($val);

				$ship_id[$order_id][] = $shipment_details[0]['shipment_id'];
				$track_no[$order_id][] = $shipment_details[0]['tracking_number']; 
				$carrier[$order_id][] = $shipment_details[0]['carrier'];
				$weight[$order_id][] = $shipment_details[0]['weight'];
			}
			if($ship_id[$order_id] != '')
			{
			$order_info[$k][$order_id]['ship_id'] = implode(',',$ship_id[$order_id]);
			}else{
				$order_info[$k][$order_id]['ship_id'] = '';
			}
			if($track_no[$order_id] != '')
			{
			$order_info[$k][$order_id]['track_no'] = implode(',',$track_no[$order_id]);
			}else{
				$order_info[$k][$order_id]['track_no'] = '';
			}
			if($carrier[$order_id] != '')
			{
			$order_info[$k][$order_id]['carrier'] = implode(',',$carrier[$order_id]);
			}else{
				$order_info[$k][$order_id]['carrier'] = '';
			}
			if($weight[$order_id] != '')
			{
			$order_info[$k][$order_id]['weight'] = implode(',',$weight[$order_id]);
			}else{
				$order_info[$k][$order_id]['weight'] = '';
			}
			if($merchant_no[$order_id] != '')
			{
			$prod_merchant_no[$k][$order_id]['merchant_no'] = implode(',',$merchant_no[$order_id]);
			}else{
				$prod_merchant_no[$k][$order_id]['merchant_no'] = '';
			}
			if($prod_detail[$order_id] != '')
			{
			$prod_details[$k][$order_id]['prod_detail'] = implode(',',$prod_detail[$order_id]);
			}else{
				$prod_details[$k][$order_id]['prod_detail'] = '';
			}
			if($order_detail['payment_method']['payment'] == '')
			{
				$order_detail['payment_method']['payment'] = '-';
			}
			
			
		}
		
		$manifest_type_name = get_manifest_type($manifest_type_id);
		
		if($_GET['mode_action'] == 'export_csv')
		{
			//print_r($order_info);
			//exit;
			//$filename = $file."_".date("Y-m-d_H-i",time());
			$filename = "Export_Manifest_Order_Details_".date("Y-m-d");
			
			//Generate the CSV file header
			header("Content-type: application/vnd.ms-excel");
			//header("Content-type: text/x-csv");
			//header("Content-type:text/octect-stream");
			header("Content-disposition: csv" . date("Y-m-d") . ".csv");
			header("Content-disposition: filename=".$filename.".csv");
			
			//Print the contents of out to the generated file.
			$out .= 'Order No,Merchant Name,Payment Type,Buyer Name,Shipping Address1,Shipping Address2,Shipping City,Shipping State,Shipping Pincode,Buyer Phone No.,Order SubTotal,Collectible Amount,Shipment Weight,Product Details,Courier Name,Date Of Dispatch,Pick Up Boy,Pick Up Vehicle No,Pick Up By Location,Report Generated By,Manifest Type,Notes';
			$out .= "\n";

				foreach($order_info as $k=>$order_detail)
				{
					$oid = $order_detail['order_id'];
					$order_date = date("d M Y",$order_detail['timestamp']);
					$order_time = date("H:i",$order_detail['timestamp']);
					if($order_detail['payment_method']['payment_id'] == '6')
					{ 
						$collectible_amt = $order_detail['total'];
					}else
					{ 
						$collectible_amt = '0.00'; 
					}
					if($order_detail['payment_method']['payment'] == '' && !isset($order_detail['use_gift_certificates']))
					{
						$payment_type = 'CluesBucks';
					}elseif(isset($order_detail['use_gift_certificates']))
					{
						$payment_type = 'Gift Certificate';
					}else{
						$payment_type = $order_detail['payment_method']['payment'];
					}
					$full_address = str_replace(',',' ', $order_detail['s_address']).' '.str_replace(',',' ',$order_detail['s_address_2']).' '.$order_detail['s_city'].' '.$order_detail['s_state_descr'].' Pincode: '.$order_detail['s_zipcode'];
					
					$out .= $order_detail['order_id'].','.$merchant_detail[$oid].','.str_replace(',',' ',$payment_type).','.$order_detail['b_firstname'].' '.$order_detail['b_lastname'].','.str_replace(',',' ', $order_detail['s_address']).','.str_replace(',',' ', $order_detail['s_address_2']).','.$order_detail['s_city'].','.$order_detail['s_state_descr'].','.$order_detail['s_zipcode'].','.$order_detail['b_phone'].','.$order_detail['subtotal'].','.$collectible_amt.','.$order_detail[$oid]['weight'].','.str_replace(',','/', $prod_details[$k][$oid]['prod_detail']).','.str_replace('_',' ',$courier_name).','.$dispatch_date.','.$boy_name.','.$vehicle_no.','.str_replace(',',' ',$by_location).','.str_replace(',',' ',$report_by).','.str_replace(',',' ',$manifest_type_name['description']).','.str_replace(',',' ',$notes);
					$out .= "\n";
					
				}
			print $out;
			
			//Exit the script
			exit;
		}
		
		if ($_GET['mode_action'] == 'export_pdf') {
			$view_mail->assign('order_status_descr', fn_get_statuses(STATUSES_ORDER, true, true, true));

			$html = array();
			$view_mail->assign('list_order_id',$list_order_id);
			$view_mail->assign('order_info',$order_info);
			$view_mail->assign('merchant_name',$merchant_detail);
			$view_mail->assign('prod_merchant_no',$prod_merchant_no);
			$view_mail->assign('prod_details',$prod_details);
			$view_mail->assign('status_name',$status_name);
			$view_mail->assign('courier_name',$courier_name);
			$view_mail->assign('dispatch_date',$dispatch_date);
			$view_mail->assign('manifest_id',$manifest_id);
			$view_mail->assign('boy_name',$boy_name);
			$view_mail->assign('vehicle_no',$vehicle_no);
			$view_mail->assign('by_location',$by_location);
			$view_mail->assign('report_by',$report_by);
			$view_mail->assign('manifest_type_name',$manifest_type_name['description']);
			$view_mail->assign('notes',$notes);
			$html[] = $view_mail->display('manifest/manifest_pdf_detail.tpl', false);
			if(count($order_ids) < 100){
				fn_html_to_pdf($html, 'Export_manifest_report_pdf_'.date("Y-m-d"),true,true);
			}
			else
			{
				fn_html_to_pdf($html, 'Export_manifest_report_pdf_'.date("Y-m-d"),true,false);
			}
	
			exit;
		}
		
		
		$view->assign('list_order_id',$list_order_id);
		$view->assign('order_info',$order_info);
		$view->assign('merchant_name',$merchant_detail);
		$view->assign('prod_merchant_no',$prod_merchant_no);
		$view->assign('prod_details',$prod_details);
		$view->assign('status_name',$status_name);
		$view->assign('courier_name',$courier_name);
		$view->assign('dispatch_date',$dispatch_date);
		$view->assign('manifest_id',$manifest_id);
		$view->assign('boy_name',$boy_name);
		$view->assign('vehicle_no',$vehicle_no);
		$view->assign('by_location',$by_location);
		$view->assign('report_by',$report_by);
		$view->assign('manifest_type_name',$manifest_type_name['description']);
		$view->assign('notes',$notes);
}

?>
