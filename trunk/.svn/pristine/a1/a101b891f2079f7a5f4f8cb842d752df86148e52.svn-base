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

$manage_page_access = fn_check_priviledges("manage_milkruns");
if($manage_page_access == '')
{
	fn_set_notification('E','Order','You cannot access this page','I');
	return array(CONTROLLER_STATUS_REDIRECT, "");
}

$params = $_REQUEST;

if($mode == 'milkrun_initiate_list')
{
	$view->assign('page_title','Milkrun Completed :: Step 1');
	$found = '';
	$not_found = '';
if ($_REQUEST['mode_action'] == 'import') 
	{	//print_r($_REQUEST);die();
		if(($_REQUEST['text_area'] != '' && $_FILES["file"]["type"] != '') || ($_REQUEST['text_area'] == '' && $_FILES["file"]["type"] == ''))
		{
			fn_set_notification('E','Order','Either upload file or enter order numbers','I');
			unset($_REQUEST['redirect_url']);
			return array(CONTROLLER_STATUS_REDIRECT, "milkrun_completed.milkrun_initiate_list");
		}
		if($_REQUEST['text_area'] != '')
		{
			$string = array("\n",",",";"," ");
			$order_ids = explode(" ", trim(str_replace($string," ",$_REQUEST['text_area'])));
			
				$_SESSION['milkrun_order_list']['orders']['data'] = array('order_id' => $order_ids);

				
				unset($_REQUEST['redirect_url']);
				return array(CONTROLLER_STATUS_REDIRECT, "milkrun_completed.milkrun_order_list");
		}else{
			if (($_FILES["file"]["type"] == "application/vnd.ms-excel" || $_FILES["file"]["type"] == "text/csv") && ($_FILES["file"]["size"] < 20000000))
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
				}else if(isset($newArray['Order ID'])){
					$order_ids = $newArray['Order ID'];
				}else{
					fn_set_notification('E','Order','Please check column name or file format','I');
					unset($_REQUEST['redirect_url']);
					return array(CONTROLLER_STATUS_REDIRECT, "milkrun_completed.milkrun_initiate_list");
				}
				
				$_SESSION['milkrun_order_list']['orders']['data'] = array('order_id' => $order_ids);
				unset($_REQUEST['redirect_url']);
				return array(CONTROLLER_STATUS_REDIRECT, "milkrun_completed.milkrun_order_list");
			}
			else
			{
				fn_set_notification('E','Order','Invalid File','I');
				unset($_REQUEST['redirect_url']);
				return array(CONTROLLER_STATUS_REDIRECT, "milkrun_completed.milkrun_initiate_list");
			}
		}
		//unset($_REQUEST['redirect_url']);
			//return array(CONTROLLER_STATUS_REDIRECT, "manifest_create.manifest_list_view");
	}
}

if ($mode == 'milkrun_order_list') {
	
	$order_ids1 = $_SESSION['milkrun_order_list']['orders']['data']['order_id'];
	foreach($order_ids1 as $k=>$v)
			{
				$query = "SELECT * FROM cscart_orders where order_id='".$v."' and status = 'E'";
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
	
	if($not_found_imp != '')
	{
		$view->assign('not_found',$not_found);
	}else{
		$order_ids = $found;
		
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
		
		$view->assign('list_order_id',$list_order_id);
		$view->assign('order_info',$order_info);
		$view->assign('merchant_name',$merchant_detail);
		$view->assign('prod_merchant_no',$prod_merchant_no);
		$view->assign('prod_details',$prod_details);
		$view->assign('status_name',$status_name);
		$view->assign('courier_name',$courier_name);
		$view->assign('dispatch_date',$dispatch_date);
		$view->assign('page_title','Milkrun Completed :: Step 2');
	}
}

if ($mode == 'milkrun_completed_status') {
	$order_ids = $_SESSION['milkrun_order_list']['orders']['data']['order_id'];
	$cnt = count($order_ids);
	foreach($order_ids as $k => $v)
	{
		fn_change_order_status($v, 'G', '', $notify);
	}
	fn_set_notification('N','Order',$cnt.' orders has change status to Milkrun completed successfully.','I');
	unset($_REQUEST['redirect_url']);
	return array(CONTROLLER_STATUS_REDIRECT, "milkrun_completed.milkrun_initiate_list");
}

?>