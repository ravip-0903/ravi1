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
				WHERE (co.status='O' OR co.status='P' OR co.status='B') $query_order_rows_where_segment_period  order by cc.$order_by ASC";
			
			$report_rows = db_get_array($query_milkrun_full);
			
			$view->assign('report_rows', $report_rows);
			$view->assign('report_type', $report_type);
			$view->assign('time_from', $_REQUEST['time_from']);
			$view->assign('time_to', $_REQUEST['time_to']);
			$view->assign('order_by', $_REQUEST['order_by']);
			//echo '<pre>';print_r($view_mail->display('milkrun_reports/milkrun_report_full.tpl',false));die;
			
			if (!empty($_REQUEST['format']) && $_REQUEST['format'] == 'pdf') {
				fn_html_to_pdf($view_mail->display('milkrun_reports/milkrun_report_full.tpl', false),'MilkRun Report');
			}
			
		} else if($report_type == 'merchant')
		{
			$company_id = $_REQUEST['merchant'];
			$company_data = fn_get_company_data($company_id);
			$query_order_rows_where_segment_merchang = " AND co.company_id=$company_id";
			$query_milkrun_full = "SELECT 
				cpd.product, cod.amount, cp.merchant_reference_number, cod.product_code, co.order_id
				FROM cscart_order_details cod 
				LEFT JOIN cscart_orders co ON cod.order_id = co.order_id 
				LEFT JOIN cscart_companies cc ON cc.company_id = co.company_id 
				LEFT JOIN cscart_products cp ON cp.product_id = cod.product_id 
				LEFT JOIN cscart_product_descriptions cpd ON cod.product_id = cpd.product_id 
				WHERE (co.status='O' OR co.status='P' OR co.status='B') $query_order_rows_where_segment $query_order_rows_where_segment_merchang ";
			$report_rows = db_get_array($query_milkrun_full);
			
			$view->assign('report_rows', $report_rows);
			$view->assign('company_data', $company_data);
		}
		$view->assign('report_type', $report_type);
	}
} else {
		
		
}

?>