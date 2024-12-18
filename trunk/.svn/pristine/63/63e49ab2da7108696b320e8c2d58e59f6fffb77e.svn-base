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
if ($mode == 'export_rma_list') {
	//echo '<pre>';
	//print_r($_SESSION['export_orders_list']);die;
	
	$rma_ids = $_SESSION['export_orders_list']['orders']['data']['order_id'];

	//$list_order_id = implode(',',$order_ids);
		$str_rma_ids = '';
		foreach ($rma_ids as $rmaid) {
			
			if($str_rma_ids=='')
			{
				$str_rma_ids = $rmaid;
			}
			else
			{
				$str_rma_ids = $str_rma_ids . "," . $rmaid;
			}
			
		}
		$sql = "
		select r.return_id, r.order_id, from_unixtime(o.timestamp) as order_date, from_unixtime(r.timestamp) as rma_date,
		os.description as order_status, rs.description as rma_status,
		concat(o.firstname,' ',o.lastname) as buyer, concat(o.s_address,' ',o.s_address_2,' ',s_city,' ',s_state) as shipping_address, s_zipcode,s_phone,
			(
			select GROUP_CONCAT(concat('[',pd.product,'(QTY:',p.amount,')]   ')) 
			from ?:rma_return_products p 
			left join ?:product_descriptions pd on pd.product_id = p.product_id
			where p.return_id=r.return_id
			) as products,
		r.total_amount as total_qty,r.comment,c.company,rpd.property as action
		from ?:rma_returns r
		left join ?:orders o on o.order_id = r.order_id
		left join ?:status_descriptions os on os.status=o.status and os.`type`='O'
		left join ?:status_descriptions rs on rs.status=r.status and rs.`type`='R'
		left join ?:companies c on c.company_id=o.company_id
		left join ?:rma_property_descriptions rpd on rpd.property_id = r.action
		where r.return_id in (".$str_rma_ids.")";
		//echo $sql;
		$order_info = db_get_array($sql);

		if($_GET['mode_action'] == 'export')
		{
			//$filename = $file."_".date("Y-m-d_H-i",time());
			$filename = "Export_RMA_Details_".date("Y-m-d");
			
			//Generate the CSV file header
			header("Content-type: application/vnd.ms-excel");
			//header("Content-type: text/x-csv");
			//header("Content-type:text/octect-stream");
			header("Content-disposition: csv" . date("Y-m-d") . ".csv");
			header("Content-disposition: filename=".$filename.".csv");
			
			//Print the contents of out to the generated file.
			$out .= 'Return ID,RMA Date,RMA Status,Order ID,Order Date,Order Status,Action,Company,Buyer,Shipping Address,Zipcode,Contact,Products,Total Qty,Comment';
			$out .= "\n";
			//$out .= "\n";

				foreach($order_info as $order_detail)
				{	
					$rmadate = date("d M Y",strtotime($order_detail['rma_date']));
					$orderdate = date("d M Y",strtotime($order_detail['order_date']));
					$comment = str_replace(',','/',$order_detail['comment']);
					$comment = str_replace('\n',' ',$comment);
					$comment = str_replace('\r',' ',$comment);
					
					
					$comment =  preg_replace("/[\n\r]/","",$comment);
										
					$out .= $order_detail['return_id'].','.$rmadate.','.$order_detail['rma_status'].','.$order_detail['order_id'].','.$orderdate.','.$order_detail['order_status'].','.$order_detail['action'].','.$order_detail['company'].','.$order_detail['buyer'].','.str_replace(',','/',$order_detail['shipping_address']).','.$order_detail['s_zipcode'].','.$order_detail['s_phone'].','.str_replace(',','/',$order_detail['products']).','.$order_detail['total_qty'].','.$comment;
					$out .= "\n";
					
				}
			print $out;
			exit;
		}
		
		$view->assign('order_info',$order_info);
}
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
			$out .= 'Order No.,Order Date,Order Time,Order Status,Product Details,Buyer Name,Shipping Address,Shipping City,Shipping State,Shipping Pincode,Buyer Phone No.,Item Count,Payment Type,Order SubTotal,Collectible Amount,Merchant SKU,Shipment ID,Tracking No,Carrier Name,Merchant Name';
			$out .= "\n";
			//$out .= "\n";

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
					}elseif($order_detail['payment_method']['payment_id'] == '6'){
						$payment_type = 'COD';
					}elseif($order_detail['payment_method']['payment_id'] == '12' || $order_detail['payment_method']['payment_id'] == '14'){
						$payment_type = 'PrePaid';
					}
					$full_address = str_replace(',',' ', $order_detail['s_address']).' '.str_replace(',',' ',$order_detail['s_address2']).' '.$order_detail['s_city'].' '.$order_detail['s_state_descr'].' Pincode: '.$order_detail['s_zipcode'];
					
					$out .= $order_detail['order_id'].','.$order_date.','.$order_time.','.$status_name[$oid]['description'].','.str_replace(',','/', $prod_details[$k][$oid]['prod_detail']).','.$order_detail['b_firstname'].' '.$order_detail['b_lastname'].','.$full_address.','.$order_detail['s_city'].','.$order_detail['s_state_descr'].','.$order_detail['s_zipcode'].','.$order_detail['b_phone'].','.count($order_detail['items']).','.$payment_type.','.$order_detail['subtotal'].','.$collectible_amt.','.str_replace(',','/', $prod_merchant_no[$oid]['merchant_no']).','.str_replace(',','/', $order_detail[$oid]['ship_id']).','.str_replace(',','/', $order_detail[$oid]['track_no']).','.str_replace(',','/', $order_detail[$oid]['carrier']).','.$merchant_detail[$oid];
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
