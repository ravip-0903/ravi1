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

$page_access_premission = fn_check_priviledges("manage_milkruns");
if($page_access_premission == '')
{
	fn_set_notification('E','Order','You cannot access this page','I');
	return array(CONTROLLER_STATUS_REDIRECT, "");
}

$params = $_REQUEST;

if($mode == 'milkrun_generate')
{	
	unset($_SESSION['company_id']);
	unset($_SESSION['region_id']);
	if(isset($_REQUEST['region']) && $_REQUEST['region'] != '')
	{
		$outputStr = '<input type="hidden" name="region_id" value="' . $_REQUEST['region'] . '" />';
		$warehouse_data = fn_get_region_warehouse_data($_REQUEST['region']);
		
		if(isset($warehouse_data) && !empty($warehouse_data))
		{
			foreach($warehouse_data as $wkey => $wvalue)
			{
				$outputStr .= '<input type="checkbox" name="company_id[]" id="company_id_' . $wkey . '" '
							. ' value="' . $wvalue['company_id'] . '" />'
							. '<label for="company_id_' . $wkey . '">' . $wvalue['company_name'] . '</label>';
			}
		}
		else
		{
			$outputStr = 0;
		}
		echo $outputStr;
		exit;
	}
	
	if(isset($_REQUEST['process_type']) && $_REQUEST['process_type'] == 'get_orders')
	{
		$_SESSION['company_id'] = base64_encode(gzdeflate(implode(',', $_REQUEST['company_id'])));
		$_SESSION['region_id'] = $_REQUEST['region_id'];
		unset($_REQUEST['redirect_url']);
		return array(CONTROLLER_STATUS_REDIRECT, "milkrun_create.milkrun_list");
	}
	
	$view->assign('page_title','Milkrun Initiation System');
	$view->assign('region_list', fn_get_all_region());
}

if ($mode == 'milkrun_list') 
{
	if(isset($_SESSION['company_id']) && $_SESSION['company_id'] != '')
	{	
		$status_values = array('COD - Confirmed', 'Paid', 'Backordered');
		$company_id = array();
		$order_ids = array();
		$order_details = array();
		
		$company_id = explode(',', gzinflate(base64_decode($_SESSION['company_id'])));
		
		$query = "SELECT cso.order_id FROM ?:orders cso "
				. " JOIN ?:companies csc ON cso.company_id = csc.company_id "
				. " JOIN ?:status_descriptions csd ON cso.status = csd.status "
				. " WHERE csc.company_id IN ('" . implode("', '", $company_id) 
				. "') AND csd.description IN ('" . implode("', '", $status_values) . "') ";
		$order_ids = db_get_array($query);
		
		if(!empty($order_ids))
		{
			foreach($order_ids as $ikey => $ivalue)
			{
				$order_details[$ikey] = fn_get_order_info($ivalue['order_id']);
				$order_details[$ikey]['merchant_detail'] = fn_get_company_name($order_details[$ikey]['company_id']);
				
				if(isset($order_details[$ikey]['items']) && !empty($order_details[$ikey]['items']))
				{
					foreach($order_details[$ikey]['items'] as $tkey => $tvalue)
					{
						$order_details[$ikey]['product_details'] .= $tvalue['product'] . ' ';
					}
				}
					
				if(isset($order_details[$ikey]['shipment_ids']) && !empty($order_details[$ikey]['shipment_ids']))
				{
					foreach($order_details[$ikey]['shipment_ids'] as $skey => $svalue)
					{
						$shipping_details = get_shipment_data($svalue);
						$order_details[$ikey]['shipment_id'] .= '1 ';
						$order_details[$ikey]['tracking_number'] .= $shipping_details[0]['tracking_number'] . ' ';
						$order_details[$ikey]['carrier'] .= $shipping_details[0]['carrier'] . ' ';
						$order_details[$ikey]['weight'] .= $shipping_details[0]['weight'] . ' ';
					}
				}
			}
		}
		$view->assign('page_title','MilkRun Initiation Review Page');
		$view->assign('order_details', $order_details);
		
		if(isset($_REQUEST['process_type']) && $_REQUEST['process_type'] == 'intiate_milkrun')
		{	
			$successful_order_ids = array();
			
			if(isset($_REQUEST['milkrun_initiate_orderid']) && !empty($_REQUEST['milkrun_initiate_orderid']))
			{
				$query = "SELECT status FROM ?:status_descriptions WHERE type = 'O' AND "
						. " lang_code = 'EN' AND description = 'MilkRun Initiated' ";
				$milkrun_intiate_status = db_get_row($query);
				foreach($_REQUEST['milkrun_initiate_orderid'] as $okey => $ovalue)
				{	
					if($ovalue == 'on')
					{
						$res = fn_change_order_status($okey, $milkrun_intiate_status['status']);
						if($res)
						{
							$successful_order_ids[$okey] = 'Status successfully updated';
						}
					}
				}
			}
			
			if(!empty($successful_order_ids))
			{
				$summary_message = "<br/><br/>";
				foreach($successful_order_ids as $okey => $ovalue)
				{
					$summary_message .= $okey . ': ' . $ovalue . "<br/>";
				}
				fn_set_notification('N','Summary: ',$summary_message,'I');
			}
			unset($_REQUEST['redirect_url']);
			return array(CONTROLLER_STATUS_REDIRECT, "milkrun_create.milkrun_distribution");
		}
	}
}

if ($mode == 'milkrun_distribution')
{	
	$email_content = "Hi, \n\nPlease find Product Pickup report for " . date('Y-m-d'). ". \n"
					. "Our 3PL partner will pick up the orders today around 12:00. \n\n"
					. "Kindly keep these products ready for dispatch. \n\n"
					. "ShopClues Warehouse Support \n"
					. "Gurgaon, India | phone: +91-124-3884510";
	
	if(isset($_REQUEST['process_type']) && $_REQUEST['process_type'] == 'generate_report')
	{	
		$from = Registry::get('settings.Company.company_orders_department');
		
		$files_names_for_download = array();
		
		foreach($_REQUEST['company'] as $ckey => $cvalue)
		{	
			$email_content = '<html><body>' . $cvalue['content'] . '</body></html>';
			$email_content = nl2br($email_content);
			
			$files_names_for_email = array();
			$CSV_ = array();
			$PDF_ = array();
			
			$allowed_statuses = implode('\',\'', Registry::get('config.order_statuses_for_milkrun_initiated'));
			
			$query = "SELECT co.order_id, cod.amount, cod.product_id, cpd.product, cod.extra, "
					. " co.s_firstname, co.s_lastname, co.timestamp, cod.product_code, "
					. " co.s_address, co.s_address_2, co.s_city, co.s_state, co.total, co.subtotal,"
					. " co.s_country, co.s_zipcode, co.s_phone, co.total, cp.merchant_reference_number "
					. " FROM ?:order_details cod "
					. " LEFT JOIN ?:orders co ON cod.order_id = co.order_id " 
					. " LEFT JOIN ?:products cp ON cp.product_id = cod.product_id "
					. " LEFT JOIN ?:product_descriptions cpd ON cpd.product_id = cp.product_id "
					. " WHERE co.status IN ('" . $allowed_statuses . "') AND co.company_id = '" . $ckey . "'  ";
			
			$report_rows = db_get_array($query);
			
			foreach($report_rows as $rkey => & $rvalue)
			{
				$rvalue['extra'] = unserialize($rvalue['extra']);
				if(isset($rvalue['extra']['product_options']) && !empty($rvalue['extra']['product_options']))
				{
					$selected_options = '';
					foreach($rvalue['extra']['product_options_value'] as $key => $value)
					{
						$selected_options .= $value['option_name'] . ': ' . $value['variant_name'] . ' ';
					}
					$rvalue['selected_options'] = $selected_options;
				}
			}
			
			$order_count_query = "SELECT COUNT(DISTINCT(co.order_id)) as total_order_count "
								 . " FROM cscart_order_details cod "
								 . " LEFT JOIN cscart_orders co ON cod.order_id = co.order_id "
								 . " LEFT JOIN cscart_companies cc ON cc.company_id = co.company_id "
								 . " WHERE co.status IN ('" . $allowed_statuses . "') AND co.company_id = '" . $ckey . "'  ";
			$total_order_counts = db_get_row($order_count_query);
			
			$product_count_query = "SELECT COUNT(cod.amount) total_product_count "
									. " FROM cscart_order_details cod "
									. " LEFT JOIN cscart_orders co ON cod.order_id = co.order_id " 
									. " LEFT JOIN cscart_companies cc ON cc.company_id = co.company_id "
									. " WHERE co.status IN ('" . $allowed_statuses . "') AND co.company_id = '" . $ckey . "'  ";
			$total_product_counts = db_get_row($product_count_query);
			
			$product_query = "SELECT DISTINCT(cod.product_id), cpd.product, cod.product_code, "
							. " SUM(cod.amount) as qty, cp.merchant_reference_number "
							. " FROM ?:order_details cod "
							. " LEFT JOIN ?:orders co ON cod.order_id = co.order_id " 
							. " LEFT JOIN ?:companies cc ON cc.company_id = co.company_id "
							. " LEFT JOIN ?:products cp ON cp.product_id = cod.product_id "
							. " LEFT JOIN ?:product_descriptions cpd ON cod.product_id = cpd.product_id " 
							. " WHERE co.status IN ('" . $allowed_statuses . "') AND co.company_id = '" . $ckey 
							. "' GROUP BY cod.product_id ";
			$product_summary_rows = db_get_array($product_query);
			
			$company_data = fn_get_company_warehouse_data($ckey);
			
			$company_name = str_replace(' ', '_', $company_data['company']);
			$company_name = preg_replace('/[^A-Za-z0-9_]/', '', $company_name);  
			
			require_once(DIR_ADDONS . 'barcode/lib/barcodegenerator/barcode.php');
			require_once(DIR_ADDONS . 'barcode/lib/barcodegenerator/c128bobject.php');
		
			foreach($report_rows as $report_row)
			{
				if(!file_exists($report_row['order_id'].'.png')){
					generate_image($report_row['order_id']);
				}	
			}
			if(isset($cvalue['pdf']) && $cvalue['pdf'] == "on")
			{
				$filename = $company_name . '_MilkRunReport_' . date('Ymd_H_i')/* . '.pdf'*/;
				
				$view_mail->assign('report_rows', $report_rows);
				$view_mail->assign('company_data', $company_data);
				$view_mail->assign('total_order_counts', $total_order_counts);
				$view_mail->assign('total_product_counts', $total_product_counts);
				$view_mail->assign('product_summary_rows', $product_summary_rows);
				
				$html = $view_mail->display('milkrun_reports/milkrun_report_merchant.tpl', false);
				
				fn_html_to_pdf($html, $filename,false,false,"images/excel_upload/".$filename);
				$PDF_[$ckey] = $filename . '.pdf';
				//$PDF_[$ckey] = fn_html_to_pdf_save($html, $filename);
				//$PDF_[$ckey] = $PDF_[$ckey] . '.pdf';
				$files_names_for_download[] = $PDF_[$ckey];
			}
			
			if(isset($cvalue['csv']) && $cvalue['csv'] == "on")
			{	
				$filename = $company_name . '_MilkRunReport_' . date('Ymd_H_i') . '.csv';
				$CSV_[$ckey] = form_csv_file($report_rows, $company_data, $total_product_counts, $total_order_counts, $filename);
				$files_names_for_download[] = $CSV_[$ckey];
			}
			
			if((isset($_REQUEST['report_type']) && $_REQUEST['report_type'] == 'Email Reports')
			&& ($cvalue['csv'] == "on" || $cvalue['pdf'] == "on"))
			{
				$subject =  $company_name . ' MilkRun Report For ' . date('Y-m-d');
				$attachments = array();
				$email = preg_split('/[,;\s]/', $cvalue['email'], -1, PREG_SPLIT_NO_EMPTY);
				$email = array_unique(array_filter($email));
				$email[] = 'satyender.singh@shopclues.com';
				$email[] = $_REQUEST['cc_email'];
				
				$to = implode(';', $email);
				
				if($cvalue['csv'] == "on")
				{
					$csv_data = file_get_contents('images/excel_upload/'.$CSV_[$ckey]);
					$attachments[] = array(
					   'data' => $csv_data,
					   'name' => "$CSV_[$ckey]",
					   'type' => 'application/vnd.ms-excel'
					);
					$csvId = uploadAttachment('', $CSV_[$ckey], $csv_data);
				}
				
				if($cvalue['pdf'] == "on")
				{
					$pdf_data = file_get_contents('images/excel_upload/'.$PDF_[$ckey]);
					$attachments[] = array(
					   'data' => $pdf_data,
					   'name' => "$PDF_[$ckey]",
					   'type' => 'application/pdf'
					);
					$pdfId = uploadAttachment('', $PDF_[$ckey], $pdf_data);
				}
				/**
				* Generate a boundary string
				**/
				$semi_rand = md5(time());
				$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

				/**
				* Add the headers for a file attachment
				**/
				$headers = "MIME-Version: 1.0\n" 
						. "From: {$from}\n" 
						. "Content-Type: multipart/mixed;\n" 
						. " boundary=\"{$mime_boundary}\"";

				/** 
				* Add a multipart boundary above the plain message
				**/
				$message = "This is a multi-part message in MIME format.\n\n" 
						. "--{$mime_boundary}\n" 
						. "Content-Type: text/html; charset=\"UTF-8\"\n" 
						. "Content-Transfer-Encoding: 7bit\n\n" 
						.  $email_content . "\n\n";

				/**
				* Add attachments
				**/
				foreach($attachments as $attachment)
				{
				   $data = chunk_split(base64_encode($attachment['data']));
				   $name = $attachment['name'];
				   $type = $attachment['type'];
			   
			   	 $message .= "--{$mime_boundary}\n" 
							. "Content-Type: {$type};\n" 
							. " name=\"{$name}\"\n" 
							. "Content-Transfer-Encoding: base64\n\n" 
							. $data . "\n\n" ; 
				}
				$message .= "--{$mime_boundary}--\n";


				$mail = sendElasticEmail($to, $subject, null, $email_content, 'orders@shopclues.com', 'ShopClues.com', $csvId.';'.$pdfId);

				if($mail){
					$summary = 'Reports successfully emailed';
				} else {
					$summary = 'Error';
				}


				/*if(mail($to, $subject, $message, $headers))
				{
					$summary = 'Reports successfully emailed';
				}
				else
				{
					$summary = 'Error';
				}*/
			}
		}
		if(isset($_REQUEST['report_type']) && $_REQUEST['report_type'] == 'Download')
		{
			$files_names_for_download = array_unique(array_filter($files_names_for_download));
			
			$zip = new ZipArchive;
			$filename = 'Merchant_Reports_' . date('YmdHis') . '.zip';
			
			if($zip->open($filename, ZIPARCHIVE::CREATE)!==TRUE) 
			{
				exit("cannot open <$filename>\n");
			}
			foreach($files_names_for_download as $file)
			{
				$zip->addFile('images/excel_upload/' . $file, $file);
			}
			$zip->close();
			header("Content-type: application/zip"); 
			header("Content-Disposition: attachment; filename=" . $filename); 
			header('Content-Length: ' . filesize($filename));
			readfile($filename);
			exit;
		}
		
		fn_set_notification('N','Summary: ',$summary,'I');
		unset($_REQUEST['redirect_url']);
		return array(CONTROLLER_STATUS_REDIRECT, "milkrun_create.milkrun_distribution");
	}
	
	if(isset($_GET['region_id']) && $_GET['region_id'] != '')
	{	
		$_SESSION['region_id'] = $_GET['region_id'];
	}
	
	if(isset($_SESSION['region_id']) && $_SESSION['region_id'] != '')
	{
		$region_id = ($_SESSION['region_id']) ? $_SESSION['region_id'] : $_REQUEST['region_id'];
		$merchant_details = array();
		
		$query = "SELECT cwc.company_id, cwc.company_name, "
				. " warehouse_pcontact_email, warehouse_scontact_email, warehouse_address1, warehouse_address2, warehouse_city, warehouse_state, warehouse_pin, warehouse_pcontact_phone,"
				. " COUNT(DISTINCT(csod.order_id)) AS ordercount"
				. " FROM `clues_warehouse_contact` cwc "
				. " JOIN ?:orders cso ON cwc.company_id = cso.company_id "
				. " RIGHT JOIN ?:order_details csod ON cso.order_id = csod.order_id  "
				. " JOIN ?:status_descriptions csd ON cso.status = csd.status "
				. " WHERE region_code = '" . $region_id . "' AND csd.type = 'O' AND "
				. " csd.lang_code = 'EN' AND csd.description = 'MilkRun Initiated' GROUP BY cso.company_id ";
		$merchant_details = db_get_array($query);
		
		$view->assign('merchant_details', $merchant_details);
	}
	
	$view->assign('email_content', $email_content);
	$view->assign('page_title','Milkrun Distribution System ');
	$view->assign('region_list', fn_get_all_region());
}

function getOrderIds($a)
{
	return $a['order_id'];
}

function form_csv_file($details, $company_data, $total_product_counts, $total_order_counts, $filename)
{
	$i = 1;
	
	$output_str = "Merchant Name:, " . $company_data['company'] . "\n"
				. "Address:, " . $company_data['address'] . "," . $company_data['city'] 
				. $company_data['state'] . "\n"
				. "Phone:, " . $company_data['phone'] . "\n"
				. "Order Count:, " . $total_order_counts['total_order_count'] . "\n"
				. "Product Count:, " . $total_product_counts['total_product_count'] . "\n\n\n"
				. "Sl.,Order No,Order Date,Product Title,Selected Options,Order Total,Buyer Name,Address,Qty,"
				. "Mer. SKU,SCIN Number\n";
	
	foreach($details as $dkey => $dvalue)
	{
		$output_str .= ($dkey+1) . ","
					. str_replace(',', ' ', $dvalue['order_id']) . ","
					. str_replace(',', ' ', date('Y-m-d', $dvalue['timestamp'])) . ","
					. str_replace(',', ' ', $dvalue['product']) . " (Rs. " . str_replace(',', ' ', $dvalue['total']) . "),"
					. str_replace(',', ' ', $dvalue['selected_options']) . ","
					. str_replace(',', ' ', $dvalue['subtotal']) . ","
					. str_replace(',', ' ', $dvalue['fullname']) . ","
					. str_replace(',', ' ', $dvalue['fulladdress']) . ","
					. str_replace(',', ' ', $dvalue['amount']) . ","
					. str_replace(',', ' ', $dvalue['merchant_reference_number']) . ","
					. str_replace(',', ' ', $dvalue['product_code']) . "\n";
	}
	
	file_put_contents("images/excel_upload/" . $filename, $output_str);
	
	return $filename;
}

function get_ids($a)
{
	return $a['product_id'];
}
?>
