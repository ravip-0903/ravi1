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

if ($mode == 'import_orders_list') {
	
	$view->assign('page_title','Bulk Update Order Details :: Step 1');
	$status_info = fn_get_statuses();
	$view->assign('status_info', $status_info);

	if ($_REQUEST['mode_action'] == 'save') {
		//print_r($_REQUEST); exit;

		$user_name = fn_get_user_name($_SESSION['auth']['user_id']);

		if($_REQUEST['notify'] == 'yes')
		{
			$notify = true;
		}else{
			$notify = false;
		}
		
		$cnt = count($_REQUEST['order_ids']);
		$delivered_cnt = 0;
		$rto_cnt = 0;
		$no_cnt = 0;

		foreach($_REQUEST['order_ids'] as $k => $v)
		{
			$current_status = strtolower(trim($_REQUEST['current_status'][$v]));
			$new_status = strtolower(trim($_REQUEST['new_status'][$v]));
			if($current_status == 'shipped' || $current_status == 'paid' || $current_status == 'cod - confirmed')
			{
				if($new_status == 'delivered')
				{
					fn_change_order_status($v, 'H', '', $notify);
					$str = 'Updated On: '.date('m-d-Y H:i:s').', Order ID:'.$v.', Current order status: '.$_REQUEST['current_status'][$v].', New Order status: Delivered, Username: '.$user_name;
					$stringData = $str."\r\n";
					$myFile = DIR_ROOT . "/tools/change_order_status_log.txt";
					$fh = fopen($myFile, 'a') or die("can't open file");
					fwrite($fh, $stringData); 
					fclose($fh);
					$delivered_cnt++;
				}elseif($new_status == '*delivered'){
					fn_change_order_status($v, 'J', '', $notify);
					$str = 'Updated On: '.date('m-d-Y H:i:s').', Order ID:'.$v.', Current order status: '.$_REQUEST['current_status'][$v].', New Order status: Return To Origin, Username: '.$user_name;
					$stringData = $str."\r\n";
					$myFile = DIR_ROOT . "/tools/change_order_status_log.txt";
					$fh = fopen($myFile, 'a') or die("can't open file");
					fwrite($fh, $stringData); 
					fclose($fh);
					$rto_cnt++;
				}elseif($new_status == 'rto'){ ///  Added By Sudhir
					fn_change_order_status($v, 'J', '', $notify);
					$str = 'Updated On: '.date('m-d-Y H:i:s').', Order ID:'.$v.', Current order status: '.$_REQUEST['current_status'][$v].', New Order status: Return To Origin, Username: '.$user_name;
					$stringData = $str."\r\n";
					$myFile = DIR_ROOT . "/tools/change_order_status_log.txt";
					$fh = fopen($myFile, 'a') or die("can't open file");
					fwrite($fh, $stringData); 
					fclose($fh);
					$rto_cnt++; /// Added by Sudhir end here 
				} else {
					$no_cnt++;		
				}
			}else{
					$no_cnt++;	
				}
			$query = "UPDATE cscart_orders SET details ='".mysql_real_escape_string($_REQUEST['order_notes'][$k])."' WHERE order_id=".$v;
			$result = db_query($query);
		}
		
		fn_set_notification('N','Order','<br />'.$delivered_cnt.' orders has change status to "delivered" successfully.<br />'.$rto_cnt.' orders has change status to "return to origin" successfully.<br />'.$no_cnt.' orders has not change status.','I');
		return array(CONTROLLER_STATUS_REDIRECT, $_REQUEST['dispatch']);
	}
	
	if ($_REQUEST['mode_action'] == 'import') 
	{	
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

			$track_name = '';
			$track_head =  array('C/ment No.','Tracking No.','Tracking No','Tracking Number','AWB No.','AWB No','AWB Number');
			foreach($track_head as $k=>$v)
			{
				if (array_key_exists($v, $newArray)) {
					$track_name = $v;
					break;
				}	
			}

			if($track_name != '' && isset($newArray['Status']))
			{
			$_SESSION['import_orders_list']['orders']['data'] = array('ship_id' => $newArray[$track_name], 'status' => $newArray['Status'], 'default_status' => $_REQUEST['order_type'], 'notes' => $newArray['Receiver Name & Contact Number']);
			
			unset($_REQUEST['redirect_url']);
			return array(CONTROLLER_STATUS_REDIRECT, "import_orders_details.import_orders_list_details");
			}else{
				fn_set_notification('E','Order','Invalid File Content','I');
				unset($_REQUEST['redirect_url']);
				return array(CONTROLLER_STATUS_REDIRECT, "import_orders.import_orders_list");
			}

			
			
		}
		else
		{
			fn_set_notification('E','Order','Invalid File','I');
			unset($_REQUEST['redirect_url']);
			return array(CONTROLLER_STATUS_REDIRECT, "import_orders.import_orders_list");
		}
		
	}
	
	
}
?>
