<?php
/***************************************************************************
*                                                                          *
*    Copyright (c) 2004 Simbirsk Technologies Ltd. All rights reserved.    *
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
// $Id: milkrun_reports.php 10028 2010-07-09 11:17:28Z 2tl $
//

if ( !defined('AREA') )	{ die('Access denied');	}

$report_type = $_REQUEST['type'];
$view->assign('report_type', $report_type);

if($mode == 'reports')
{
	$params['status'] = 'A';	
	list($companies, $search) = fn_get_companies($params, $auth);
	$view->assign('companies', $companies);

	/* Add region merchant display code by paresh */
	if(isset($_REQUEST['region']) && $_REQUEST['region'] != '')
		{
			$outputStr = '';
		$warehouse_data = fn_get_region_warehouse_data($_REQUEST['region']);
		
		if(isset($warehouse_data) && !empty($warehouse_data))
		{
			$outputStr .= '<label for="upload_csv_file">Select Merchant:</label><select name="company_id" class="cm-select-list">
			<option value="">-- Select --</option>';
			foreach($warehouse_data as $wkey => $wvalue)
			{
				$outputStr .= '	<option value="'.$wvalue['company_id'].'">' . $wvalue['company_name'] . '</option>';

			}
			$outputStr .= '</select>';
		}
		else
		{
			$outputStr = 0;
		}
		echo $outputStr;
		exit;
	}
	$view->assign('region_list', fn_get_all_region());
	/* End region merchant display code by paresh */
}


/*Generate the report based on the search options*/
if ($_SERVER['REQUEST_METHOD'] == 'GET' && $_REQUEST['report_type'] !='') {
	if($mode == 'reports')
	{
		$report_type = $_REQUEST['report_type'];
		
		if($_REQUEST['time_from'] != '' && $_REQUEST['time_to'] != '') {
			$time_from = date('m/d/Y',strtotime($_REQUEST['time_from'])).' 00:00:00';
			$time_to = date('m/d/Y',strtotime($_REQUEST['time_to'])).' 23:59:59';
		} else {
			$time_from = '';
			$time_to = '';	
		}
		if($time_from != '' && $time_to != '') {
			$query_order_rows_where_segment_period="AND co.timestamp between '".strtotime($time_from)."' and '".strtotime($time_to)."'";
		} else {
			$query_order_rows_where_segment_period = '';	
		}
		
		if($report_type == 'full') {
			$order_by = $_REQUEST['order_by'];			
			$query_milkrun_full = "SELECT cc.company, cc.address, cc.city, cc.state, cc.country, cc.zipcode, 
				cpd.product, cod.amount, cp.merchant_reference_number, cod.product_code, co.order_id, cc.state, 
				cs.carrier 
				FROM cscart_order_details cod 
				LEFT JOIN cscart_orders co ON cod.order_id = co.order_id 
				LEFT JOIN cscart_companies cc ON co.company_id = cc.company_id 
				LEFT JOIN cscart_products cp ON cp.product_id = cod.product_id
				LEFT JOIN cscart_product_descriptions cpd ON cod.product_id = cpd.product_id 
				LEFT JOIN cscart_shipment_items csi ON (cod.order_id=csi.order_id AND cod.product_id=csi.product_id) 
				LEFT JOIN cscart_shipments cs ON csi.shipment_id = cs.shipment_id 
				WHERE (co.status='M' OR co.status='P' OR co.status='B') $query_order_rows_where_segment_period  order by cc.$order_by ASC";
			
			$report_rows = db_get_array($query_milkrun_full);
			
			$view->assign('report_rows', $report_rows);
			$view->assign('report_type', $report_type);
			$view->assign('time_from', $_REQUEST['time_from']);
			$view->assign('time_to', $_REQUEST['time_to']);
			$view->assign('order_by', $_REQUEST['order_by']);
			//echo '<pre>';print_r($view_mail->display('milkrun_reports/milkrun_report_full.tpl',false));die;
			$view_mail->assign('report_rows', $report_rows);
			if (!empty($_REQUEST['format']) && $_REQUEST['format'] == 'pdf') {
				fn_html_to_pdf($view_mail->display('milkrun_reports/milkrun_report_full.tpl', false),'MilkRun Report');
			}
			
		} else if($report_type == 'merchant')
		{
			if($_REQUEST['company_id'] !='') {
				$company_id = $_REQUEST['company_id'];
				$company_data = fn_get_company_data($company_id);
				$query_order_rows_where_segment_merchant = " AND co.company_id=$company_id";
			}

			$allowed_statuses = implode('\',\'', Registry::get('config.order_statuses_for_milkrun'));
			$query_milkrun_merchant = "SELECT 
				cpd.product, cod.amount, cp.merchant_reference_number,cpp.price, cod.product_code, cod.product_id, co.order_id, 
        co.s_firstname, co.s_lastname, co.s_address, co.s_address_2, co.s_city, co.s_state, 
        co.s_country, co.s_zipcode, co.s_phone, co.total,co.notes,co.subtotal
				FROM cscart_order_details cod 
				LEFT JOIN cscart_orders co ON cod.order_id = co.order_id 
				LEFT JOIN cscart_companies cc ON cc.company_id = co.company_id 
				LEFT JOIN cscart_products cp ON cp.product_id = cod.product_id 
				LEFT JOIN cscart_product_descriptions cpd ON cod.product_id = cpd.product_id 
				LEFT JOIN cscart_product_prices cpp ON cod.product_id = cpp.product_id 				
				WHERE co.status IN ('".$allowed_statuses."') $query_order_rows_where_segment_period $query_order_rows_where_segment_merchant order by co.order_id DESC"; 

			$report_rows = db_get_array($query_milkrun_merchant);
      
      $total_order_count = "SELECT 
			 count(distinct(co.order_id)) as total_order_count
			 FROM cscart_order_details cod
			 LEFT JOIN cscart_orders co ON cod.order_id = co.order_id
			 LEFT JOIN cscart_companies cc ON cc.company_id = co.company_id
			 WHERE co.status IN ('".$allowed_statuses."') 
			 $query_order_rows_where_segment_period $query_order_rows_where_segment_merchant";
			$total_order_counts = db_get_row($total_order_count);
			
			$total_product_count = "SELECT 
				count(cod.amount) total_product_count
				FROM cscart_order_details cod 
				LEFT JOIN cscart_orders co ON cod.order_id = co.order_id 
				LEFT JOIN cscart_companies cc ON cc.company_id = co.company_id
				WHERE co.status IN ('".$allowed_statuses."') $query_order_rows_where_segment_period $query_order_rows_where_segment_merchant";
			$total_product_counts = db_get_row($total_product_count);
			
			$product_summary = "SELECT 
				distinct(cod.product_id), cpd.product, cod.product_code, sum(cod.amount) as qty, cp.merchant_reference_number 
				FROM cscart_order_details cod 
				LEFT JOIN cscart_orders co ON cod.order_id = co.order_id 
				LEFT JOIN cscart_companies cc ON cc.company_id = co.company_id
				LEFT JOIN cscart_products cp ON cp.product_id = cod.product_id 
				LEFT JOIN cscart_product_descriptions cpd ON cod.product_id = cpd.product_id 
				WHERE co.status IN ('".$allowed_statuses."') $query_order_rows_where_segment_period $query_order_rows_where_segment_merchant group by cod.product_id";
			$product_summary_rows = db_get_array($product_summary);
						
			//echo '<pre>';print_r($product_summary_rows);die;
			
			$view->assign('report_rows', $report_rows);
			$view->assign('total_order_counts', $total_order_counts);
			$view->assign('total_product_counts', $total_product_counts);
			$view->assign('product_summary_rows', $product_summary_rows);
			
      if($_REQUEST['company_id'] !='') {
				$view->assign('company_data', $company_data);
			}
			$view->assign('company_id', $company_id);
			$view->assign('time_from', $time_from);
			$view->assign('time_to', $time_to);
			
			
			if (!empty($_REQUEST['format']) && $_REQUEST['format'] == 'pdf') {
				if($_REQUEST['company_id'] !='') {
					$view_mail->assign('company_data', $company_data);
					$view_mail->assign('total_order_counts', $total_order_counts);
					$view_mail->assign('total_product_counts', $total_product_counts);
					$view_mail->assign('product_summary_rows', $product_summary_rows);
				}
				$view_mail->assign('report_rows', $report_rows);					
				$html = $view_mail->display('milkrun_reports/milkrun_report_merchant.tpl', false);
				echo $html;
				die;
				//fn_html_to_pdf($view_mail->display('milkrun_reports/milkrun_report_merchant.tpl', false),'MilkRun Report Merchant');
			}
		}
		$view->assign('report_type', $report_type);
	}
}

$params = $_REQUEST;
if ($mode == 'manifest') {		
	$params['status'] = array('O','P');
	//echo '<pre>';print_r($params);echo '</pre>';
	//list($orders, $search, $totals) = fn_get_orders($params, Registry::get('settings.Appearance.admin_orders_per_page'), true);
	if($_REQUEST['time_from'] != '' && $_REQUEST['time_to'] != '') {
			$time_from = date('m/d/Y',strtotime($_REQUEST['time_from'])).' 00:00:00';
			$time_to = date('m/d/Y',strtotime($_REQUEST['time_to'])).' 23:59:59';
	} else {
		$time_from = '';
		$time_to = '';	
	}
	if($time_from != '' && $time_to != '') {
		$query_order_rows_where_segment_period="AND co.timestamp between '".strtotime($time_from)."' and '".strtotime($time_to)."'";
	} else {
		$query_order_rows_where_segment_period = '';	
	}
	$query_orders = "SELECT cc.company, cc.address, cc.city, cc.state, cc.country, cc.zipcode, 
				cpd.product, cod.amount, cp.merchant_reference_number, cod.product_code, co.order_id, cc.state, 
				cs.carrier 
				FROM cscart_order_details cod 
				LEFT JOIN cscart_orders co ON cod.order_id = co.order_id 
				LEFT JOIN cscart_companies cc ON co.company_id = cc.company_id 
				LEFT JOIN cscart_products cp ON cp.product_id = cod.product_id
				LEFT JOIN cscart_product_descriptions cpd ON cod.product_id = cpd.product_id 
				LEFT JOIN cscart_shipment_items csi ON (cod.order_id=csi.order_id AND cod.product_id=csi.product_id) 
				LEFT JOIN cscart_shipments cs ON csi.shipment_id = cs.shipment_id 
				WHERE (co.status='O' OR co.status='P') $query_order_rows_where_segment_period";
			
	$orders = db_get_array($query_milkrun_full);
	//echo '<pre>';print_r($orders);echo '</pre>';
	$view->assign('orders', $orders);
	$view->assign('search', $search);
	$view->assign('totals', $totals);
} elseif($mode == 'manifest_preview') {
	echo '<pre>';print_r($_REQUEST);die("hello");	
}


?>
