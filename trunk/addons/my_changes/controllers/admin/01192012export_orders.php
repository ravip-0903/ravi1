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

$params = $_REQUEST;

if ($mode == 'export_orders_list') {
	//echo '<pre>';
	//print_r($_SESSION['export_orders_list']);die;
	
	$order_ids = $_SESSION['export_orders_list']['orders']['data']['order_id'];

	//$list_order_id = implode(',',$order_ids);
	
		foreach ($order_ids as $k => $v) {
			$order_info[$k] = fn_get_order_info($v);
			$order_id = $order_info[$k]['order_id'];
			
			$merchant_detail[$order_id] = fn_get_company_name($order_info[$k]['company_id']);
			$status_name[$order_id] = fn_get_status_data($order_info[$k]['status']);
			
			foreach($order_info[$k]['items'] as $pk=>$pv)
			{
				$prod_merchant_sku = get_prod_merchant_sku($pv['product_id']);
				$merchant_no[$order_id][] = $prod_merchant_sku['merchant_reference_number'];
				$prod_detail[$order_id][] = $pv['product'].' (Qty: '.$pv['amount'].' Unit Price: Rs. '.$pv['price'].') ';
				//$price[$order_id][] = $shipment_details[0]['tracking_number'];  	
			}
			
			foreach($order_info[$k]['shipment_ids'] as $key=>$val)
			{
				$shipment_details = get_shipment_data($val);

				$ship_id[$order_id][] = $shipment_details[0]['shipment_id'];
				$track_no[$order_id][] = $shipment_details[0]['tracking_number']; 
				$carrier[$order_id][] = $shipment_details[0]['carrier']; 	
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
		/*echo "<pre>";
		print_r($order_info)
;exit;*/
		
		

		
		if($_GET['mode_action'] == 'export')
		{
			
			//$filename = $file."_".date("Y-m-d_H-i",time());
			$filename = "Export_Order_Details_".date("Y-m-d");
			
			//Generate the CSV file header
			header("Content-type: application/vnd.ms-excel");
			//header("Content-type: text/x-csv");
			//header("Content-type:text/octect-stream");
			header("Content-disposition: csv" . date("Y-m-d") . ".csv");
			header("Content-disposition: filename=".$filename.".csv");
			
			//Print the contents of out to the generated file.
			$out .= 'Order No.,Order Status,Product Details,Buyer Name,Shipping Address,Shipping City,Shipping State,Shipping Pincode,Buyer Phone No.,Item Count,Payment Type,Order SubTotal,Collectible Amount,Merchant SKU,Shipment ID,Tracking No,Carrier Name,Merchant Name';
			$out .= "\n";
			$out .= "\n";

				foreach($order_info as $k=>$order_detail)
				{
					$oid = $order_detail['order_id'];
					
					if($order_detail['payment_method']['payment_id'] == '6')
					{ 
						$collectible_amt = $order_detail['total'];
					}else
					{ 
						$collectible_amt = '0.00'; 
					}
					
					$out .= $order_detail['order_id'].','.$status_name[$oid]['description'].','.str_replace(',','/', $prod_details[$k][$oid]['prod_detail']).','.$order_detail['b_firstname'].' '.$order_detail['b_lastname'].','.str_replace(',',' ', $order_detail['s_address']).' '.str_replace(',',' ',$order_detail['s_address2']).','.$order_detail['s_city'].','.$order_detail['s_state_descr'].','.$order_detail['s_zipcode'].','.$order_detail['b_phone'].','.count($order_detail['items']).','.$order_detail['payment_method']['payment'].','.$order_detail['subtotal'].','.$collectible_amt.','.str_replace(',','/', $prod_merchant_no[$oid]['merchant_no']).','.str_replace(',','/', $order_detail[$oid]['ship_id']).','.str_replace(',','/', $order_detail[$oid]['track_no']).','.str_replace(',','/', $order_detail[$oid]['carrier']).','.$merchant_detail[$oid];
					$out .= "\n";
					
				}
			print $out;
			
			//Exit the script
			exit;
		}
		
		$view->assign('list_order_id',$list_order_id);
		$view->assign('order_info',$order_info);
		$view->assign('merchant_name',$merchant_detail);
		$view->assign('prod_merchant_no',$prod_merchant_no);
		$view->assign('prod_details',$prod_details);
		$view->assign('status_name',$status_name);
}
?>
