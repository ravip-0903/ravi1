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

$manage_page_access = fn_check_priviledges("manage_bulk_shipments");
if($manage_page_access == '')
{
	fn_set_notification('E','Order','You cannot access this page','I');
	return array(CONTROLLER_STATUS_REDIRECT, "");
}

$params = $_REQUEST;

if ($mode == 'import_orders_list_details') {
			$view->assign('page_title','Bulk Update Order Details :: Step 2');
			$status_info = fn_get_statuses();
			$view->assign('status_info', $status_info);

			$ship_no = $_SESSION['import_orders_list']['orders']['data']['ship_id'];
			$status = $_SESSION['import_orders_list']['orders']['data']['status'];
			$default_status = $_SESSION['import_orders_list']['orders']['data']['default_status'];
			$notes = $_SESSION['import_orders_list']['orders']['data']['notes'];
			
			
			//print_r($default_status);
			if(isset($default_status))
			{
				$d_status = implode("','",$default_status);
			}
			
			if($d_status != '')
			{
				//$where1 = " AND co.status LIKE '[".$d_status."]%'";
				$where1 = " AND co.status IN ('".$d_status."')";
			}
			$not_found = array();
			$total_count = array();
			foreach($ship_no as $k2 => $v2)
			{
				if($ship_no[$k2] != '')
				{
					$query = "SELECT csi.shipment_id , css.tracking_number, css.carrier, co.*
								FROM `cscart_shipments` as css 
								INNER JOIN cscart_shipment_items as csi
								ON css.shipment_id = csi.shipment_id 
								INNER JOIN cscart_orders as co
								ON co.order_id = csi.order_id
								WHERE css.tracking_number= '".$ship_no[$k2]."'$where1
								GROUP BY csi.order_id";
					$result = db_get_array($query) or mysql_error();
					$num_rows = count($result);
					if($num_rows > 0)
					{
						foreach($result as $row)
						{
							$row1[$ship_no[$k2]][$row['shipment_id']] = $row;
							$row1[$ship_no[$k2]][$row['shipment_id']]['status_csv'] = $status[$k2];
							$row1[$ship_no[$k2]][$row['shipment_id']]['notes'] = $row['details'].' '.$notes[$k2];
							//$row[$newArray['C/ment No.'][$k2]][$k2]
						}
					}else{
						$not_found[] = $ship_no[$k2];
					}
					$total_count[] = $ship_no[$k2];
				}
			}

			$view->assign('list_order',$row1);
			$view->assign('not_found',$not_found);
			$view->assign('total_count',$total_count);
			
			/*foreach($newArray['C/ment No.'] as $k2 => $v2)
			{
				if($newArray['C/ment No.'][$k2] != '')
				{
					$query = "SELECT csi.shipment_id , css.tracking_number, co.*
								FROM `cscart_shipments` as css 
								INNER JOIN cscart_shipment_items as csi
								ON css.shipment_id = csi.shipment_id 
								INNER JOIN cscart_orders as co
								ON co.order_id = csi.order_id
								WHERE css.tracking_number= '".$newArray['C/ment No.'][$k2]."'
								GROUP BY csi.order_id";
					$result = mysql_query($query) or mysql_error();
					while($row = mysql_fetch_array($result))
					{
						$row1[$newArray['C/ment No.'][$k2]][$row['shipment_id']] = $row;
						//$row[$newArray['C/ment No.'][$k2]][$k2]
					}
				}
			}

			$view->assign('list_order',$row1);*/
	
	
}
?>
