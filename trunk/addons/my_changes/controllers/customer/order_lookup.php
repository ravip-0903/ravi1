<?php

if ( !defined('AREA') )	{ die('Access denied');	}

if($mode=='details')
{   
	if(!empty($_REQUEST['order_id']) and !empty($_REQUEST['email_id']))
	{
		
		$res=fn_check_email_and_order_id($_REQUEST['order_id'],$_REQUEST['email_id']);
		if($res=='true')
		{
			$order_info = fn_get_order_info($_REQUEST['order_id']);
			//echo '<pre>';print_r($order_info);die;
			if ($order_info['is_parent_order'] == 'Y') {

		$child_ids = db_get_array("SELECT od.item_id, o.order_id,od.product_id,company_id,status,od.product_code FROM ?:orders o left join cscart_order_details od on o.order_id=od.order_id   WHERE o.parent_order_id = ?i", $_REQUEST['order_id']);
		//fn_redirect(INDEX_SCRIPT . "?dispatch[orders.search]=Search&period=A&order_id=" . implode(',', $child_ids));
        $view->assign('child_ids',$child_ids);
        
	}
			$shipments = db_get_array('SELECT ?:shipments.shipment_id, ?:shipments.comments, ?:shipments.tracking_number, ?:shipping_descriptions.shipping AS shipping, ?:shipments.carrier FROM ?:shipments LEFT JOIN ?:shipment_items ON (?:shipments.shipment_id = ?:shipment_items.shipment_id) LEFT JOIN ?:shipping_descriptions ON (?:shipments.shipping_id = ?:shipping_descriptions.shipping_id) WHERE ?:shipment_items.order_id = ?i AND ?:shipping_descriptions.lang_code = ?s GROUP BY ?:shipments.shipment_id', $order_info['order_id'], DESCR_SL);
			
			if (!empty($shipments)) {
				foreach ($shipments as $id => $shipment) {
					$shipments[$id]['items'] = db_get_array('SELECT item_id, amount FROM ?:shipment_items WHERE shipment_id = ?i', $shipment['shipment_id']);
				}
			}
			/* added by Sudhir dt 27 aug 2012 to show tracking details at customer */
			$trackin = db_get_array("SELECT carrier_status, sc_status, from_location, to_location, status_update_date, memo, awbno, receiver_name, receiver_contact FROM clues_shipment_tracking_center WHERE order_id=".$order_info['order_id']." ORDER BY latest desc, date_of_creation desc");

		foreach($trackin as $tr=>$track){
			$tracking[$track['awbno']][]=$track;
		}
		$view->assign('tracking', $tracking);
		$order_detail=fn_get_status_params($order_info['status'],$type='O');
			   $order_return_info=db_get_row("SELECT * FROM cscart_rma_returns  where order_id='".$order_info['order_id']."'");
			 if($order_detail['allow_return']=='Y' && empty($order_return_info))
			{
				$return_orders[$order_info['order_id']]='Y';
			}
			if(isset($order_info['order_return_status']) && $order_info['order_return_status']=='N')
			{
				$return_orders[$order_info['order_id']]='N';
			}
			elseif(isset($order_info['order_return_status']) && $order_info['order_return_status']=='E')
			{
				$return_orders[$order_info['order_id']]='E';
			}
		/* added by Sudhir dt 27 aug 2012 to show tracking details at customer end here */
            $order_info['pdd_edd'] = fn_get_pdd_edd($order_info['order_id']);
            $order_cancelation=fn_get_status_params($order_info['status'],$type='O');
            $main_order_id = db_get_field("select main_order_id from clues_order_clone_rel where clone_order_id='".$order_info['order_id']."'");
			if (!empty($main_order_id))
			  {
			    $return_order = db_get_field("select return_id from cscart_rma_returns where order_id='".$main_order_id."'");
			    
				  if (!empty($return_order)){
				  $order_cancelation['allow_cancelation']= 'N';
			      }
			  }
			  
            $order_info['allow_cancelation']=$order_cancelation['allow_cancelation'];
			$view->assign('shipments', $shipments);
			$view->assign('order_info',$order_info);
			$view->assign('return_order',$return_orders);
		}
		else
		{
			 fn_set_notification('E','',fn_get_lang_var('orderid_email_not_matched'));
			 return array(CONTROLLER_STATUS_OK, "/index.php?dispatch=order_lookup.form_order&order_id=".$_REQUEST['order_id']."&email_id=".$_REQUEST['email_id']);
            //return array(CONTROLLER_STATUS_OK, "/index.php?dispatch=order_lookup.details&error=1");
		} 
	}
	
	
	  else if(empty($_REQUEST['order_id']) && empty($_REQUEST['email_id']) )
	{
	    fn_set_notification('E','',fn_get_lang_var('Please_enter_order_no_and_email_id'));
	    return array(CONTROLLER_STATUS_OK, "/index.php?dispatch=order_lookup.form_order&order_id=".$_REQUEST['order_id']."&email_id=".$_REQUEST['email_id']);
	}
	
	 else if(empty($_REQUEST['order_id']) && $_REQUEST['x'] == 'y' )
	{
	    //fn_set_notification('N','',fn_get_lang_var('order_id_blank'));
	    return array(CONTROLLER_STATUS_OK, "/index.php?dispatch=write_to_us.add&order_id=".$_REQUEST['order_id']."&email_id=".$_REQUEST['email_id']);
	}
	
	   else if(empty($_REQUEST['order_id']))
	{
	    fn_set_notification('E','',fn_get_lang_var('Please_enter_order_no'));
	    return array(CONTROLLER_STATUS_OK, "/index.php?dispatch=order_lookup.form_order&order_id=".$_REQUEST['order_id']."&email_id=".$_REQUEST['email_id']);
	}

        else if(empty($_REQUEST['email_id']))
	{
	    fn_set_notification('E','',fn_get_lang_var('Please_enter_email_id'));
	    return array(CONTROLLER_STATUS_OK, "/index.php?dispatch=order_lookup.form_order&order_id=".$_REQUEST['order_id']."&email_id=".$_REQUEST['email_id']);
	}
        

} 

function fn_check_email_and_order_id($order_id,$email_id)
{
	$res=db_get_row("select order_id from cscart_orders where order_id='".$order_id."' and email='".$email_id."'");
	
	if(!empty($res))
	return true;
	else
	return false;
}

?>
