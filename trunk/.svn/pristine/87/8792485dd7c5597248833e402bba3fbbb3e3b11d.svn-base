<?php

if ( !defined('AREA') )	{ die('Access denied');	}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	if($mode=='cod')
	{
		$order_id=trim($_REQUEST['order_id']);
		$phone=trim($_REQUEST['phone']);
		
		$sql="select user_id,email,status from cscart_orders where order_id='".$order_id."' and (b_phone='".$phone."' or b_phone='91".$phone."') ";
		$info=db_get_row($sql);
		
		if(!empty($info))
		{
			if($info['status']=='O')
			{
				if(empty($_SESSION['auth']['user_id']))
				{
					$_SESSION['auth']['user_id']=$info['user_id'];
				
					fn_change_order_status($order_id,'Q', 'O',$notify = array("C"=>true,"A"=>true,"S"=>true));
				
					unset($_SESSION['auth']);
				}
				else
				{
					fn_change_order_status($order_id,'Q', 'O',$notify = array("C"=>true,"A"=>true,"S"=>true));
				}
				$_SESSION['notify']['reason']='cod_confirm';
				$_SESSION['notify']['order_id']=$order_id;
				$_SESSION['notify']['order_email']=$info['email'];
				return array(CONTROLLER_STATUS_OK, "cod_confirmation.cod&status=confirm&order_id=".$order_id."&email=".$info['email']);
			}
			else
			{
				$_SESSION['notify']['reason']='not_open';
				$_SESSION['notify']['order_status']=$info['status'];
				$_SESSION['notify']['order_id']=$order_id;
				$_SESSION['notify']['order_email']=$info['email'];
				return array(CONTROLLER_STATUS_OK, "cod_confirmation.cod&status=not_open&order_id=".$order_id."&email=".$info['email']);
			}
		}
		else
		{
			$_SESSION['notify']['reason']='not_found';
			return array(CONTROLLER_STATUS_OK, "cod_confirmation.cod&status=not_found");
		}
		
		
	}
	
}


if($mode=='cod')
{
	if(isset($_REQUEST['status']) && !empty($_REQUEST['status']))
	{
		if($_REQUEST['status']=='not_found' && isset($_SESSION['notify']))
		{
			$view->assign('error','not_found');
			unset($_SESSION['notify']);
		}
		else if($_REQUEST['status']=='not_found' && !isset($_SESSION['notify']))
		{
			$view->assign('error','');
		}
		else if($_REQUEST['status']=='not_open')
		{
			
			$order_info = fn_get_order_info($_SESSION['notify']['order_id']);
			
			$shipments = db_get_array('SELECT ?:shipments.shipment_id, ?:shipments.comments, ?:shipments.tracking_number, ?:shipping_descriptions.shipping AS shipping, ?:shipments.carrier FROM ?:shipments LEFT JOIN ?:shipment_items ON (?:shipments.shipment_id = ?:shipment_items.shipment_id) LEFT JOIN ?:shipping_descriptions ON (?:shipments.shipping_id = ?:shipping_descriptions.shipping_id) WHERE ?:shipment_items.order_id = ?i AND ?:shipping_descriptions.lang_code = ?s GROUP BY ?:shipments.shipment_id', $order_info['order_id'], DESCR_SL);
			
			if (!empty($shipments)) {
				foreach ($shipments as $id => $shipment) {
					$shipments[$id]['items'] = db_get_array('SELECT item_id, amount FROM ?:shipment_items WHERE shipment_id = ?i', $shipment['shipment_id']);
				}
			}
			
			
			$view->assign('shipments', $shipments);
			$view->assign('order_info',$order_info);
			
			
			if(!in_array($_SESSION['notify']['order_status'],Registry::get('config.dead_order_status')))
			{
				$view->assign('error','already_confirm');
			}
			else
			{
				$view->assign('error','dead_status');
			}
		}
		else if($_REQUEST['status']='confirm')
		{
			
			$order_info = fn_get_order_info($_SESSION['notify']['order_id']);
			
			$shipments = db_get_array('SELECT ?:shipments.shipment_id, ?:shipments.comments, ?:shipments.tracking_number, ?:shipping_descriptions.shipping AS shipping, ?:shipments.carrier FROM ?:shipments LEFT JOIN ?:shipment_items ON (?:shipments.shipment_id = ?:shipment_items.shipment_id) LEFT JOIN ?:shipping_descriptions ON (?:shipments.shipping_id = ?:shipping_descriptions.shipping_id) WHERE ?:shipment_items.order_id = ?i AND ?:shipping_descriptions.lang_code = ?s GROUP BY ?:shipments.shipment_id', $order_info['order_id'], DESCR_SL);
			
			if (!empty($shipments)) {
				foreach ($shipments as $id => $shipment) {
					$shipments[$id]['items'] = db_get_array('SELECT item_id, amount FROM ?:shipment_items WHERE shipment_id = ?i', $shipment['shipment_id']);
				}
			}
			
			
			$view->assign('shipments', $shipments);
			$view->assign('order_info',$order_info);
			
			
			$view->assign('error','cod_confirm');
		}
		
	}
	else
	{
		$view->assign('error','');
		$view->assign('order_id','');
		$view->assign('email','');
		unset($_SESSION['notify']);
	}
}
?>