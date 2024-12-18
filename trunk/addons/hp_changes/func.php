<?php 
if ( !defined('AREA') ) { die('Access denied'); }
function hp_fill_product_options($order_ids)
{
	foreach($order_ids as $ord) 
	{
		$sql = "select item_id,extra from cscart_order_details where order_id='".$ord."'";
		$arr_opt = db_get_array($sql);
		foreach($arr_opt as $rvalue)
		{
			$rvalue['extra'] = unserialize($rvalue['extra']);
			if(isset($rvalue['extra']['product_options']) && !empty($rvalue['extra']['product_options']))
			{
				$selected_options = '';
				foreach($rvalue['extra']['product_options_value'] as $key => $value)
				{
					$selected_options .= $value['option_name'] . ': ' . $value['variant_name'] . ' ';
				}
				$selected_options = str_replace("'","''",$selected_options);
				$itmid = db_get_field("select item_id from clues_manifest_item_options where item_id='".$rvalue['item_id']."'");
				if(!$itmid){
					db_query("insert into clues_manifest_item_options set item_id='".$rvalue['item_id']."', options='".$selected_options."'");
				}
			}
		}
	}
}
function javabridge_merchantinvoice($billing_id)
{
	$jrxml = "merchant_invoice.jrxml";
	$url = Registry::get('config.javapdfurl').'screports1/Create';
	$outputFileName = 'merchant_invoice'.date('Y-m-d_H-i-s').'.pdf';
	$str = "filename=".$outputFileName."&"."params=billing_id:".$billing_id."&jrxmlfile=".$jrxml;
	$j=1;
	while($j<4)
	{	
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_POST,3);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$str);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,60);
		$result = curl_exec($ch);
		curl_close($ch);
		//echo $result;
		if($result)
		{
			javabridge_download(Registry::get('config.javapdfurl')."ir/pdf/".$outputFileName,$outputFileName);
			exit;
		}
		//echo $j;
		$j++;
	}
	echo "Please Retry";
	exit;

}
function javabridge_bulkorderdetails($order_ids)
{
	hp_fill_product_options($order_ids);
	$jrxml = "bulk_order_details.jrxml";
	$url = Registry::get('config.javapdfurl').'screports1/Create';
	$outputFileName = 'order-details'.date('Y-m-d_H-i-s').'.pdf';
	$str = "filename=".$outputFileName."&"."params=order_ids:".implode(",",$order_ids)."&jrxmlfile=".$jrxml;
	//echo $str;exit;
	$j=1;
	while($j<4)
	{
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_POST,3);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$str);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,500);
		$result = curl_exec($ch);
		curl_close($ch);
		//var_dump( $outputFileName);exit;
		if($result)
		{
			javabridge_download(Registry::get('config.javapdfurl')."ir/pdf/".$outputFileName,$outputFileName);
			exit;
		}
		//echo $j;
		$j++;
	}
	echo "Please Retry";
	exit;
}

function javabridge_bulkthermallabel($order_ids,$pagesize='')
{
	hp_fill_product_options($order_ids);
	if($pagesize==''){
		$jrxml = "bulk-order-thermal-report.jrxml";
	}
	else if($pagesize=='A4'){
		$jrxml = "bulk-order-thermal-report-A4.jrxml";
	}
	$url = Registry::get('config.javapdfurl').'screports1/Create';
	$outputFileName = 'shipping_label_thermal'.date('Y-m-d_H-i-s').'.pdf';
	$str = "filename=".$outputFileName."&"."params=order_ids:".implode(",",$order_ids)."&jrxmlfile=".$jrxml;
	$j=1;
	while($j<4)
	{
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_POST,3);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$str);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,500);
		$result = curl_exec($ch);
		curl_close($ch);
		if($result)
		{
			javabridge_download(Registry::get('config.javapdfurl') ."ir/pdf/".$outputFileName,$outputFileName);
			exit;
		}
		$j++;
	}
	echo "Please Retry";
	exit;
}

function javabridge_download($file,$name)
{
	header("Content-type: application/x-msdownload");
	header("Content-Disposition: attachment; filename=".$name);
	header("Pragma: no-cache");
	header("Expires: 0");
	echo file_get_contents($file);
	exit;
}
function javabridge_save($file,$name)
{
	$content = file_get_contents($file);
	///$myFile1 = "hprahi-".$pass.".html";
	$myFile = "images/excel_upload/" . $name;
	//$str = $html[0];
	$fh = fopen($myFile, 'w') or die("can't open file");
	fwrite($fh,$content);
	fclose($fh);
}
function fn_hp_merchant_milkrun_pdf($company_id,$manifest_id,$filename)
{
	$jrxml = "milkrun_report_to_merchant.jrxml";
	$url = Registry::get('config.javapdfurl').'screports1/Create';
	$outputFileName = $filename;
	$str = "filename=".$outputFileName."&"."params=company_id:".$company_id."|manifest_id:".$manifest_id."&jrxmlfile=".$jrxml;
	//echo $str;exit;
	$j=1;
	$sucflag = 0;
	while($j<4)
	{
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_POST,3);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$str);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,500);
		$result = curl_exec($ch);
		curl_close($ch);
		//var_dump( $outputFileName);exit;
		if($result)
		{
			javabridge_save(Registry::get('config.javapdfurl')."ir/pdf/".$outputFileName,$outputFileName);
			$sucflag = 1;
			break;
		}
		//echo $j;
		$j++;
	}
	if($sucflag==0){
		echo "Please Retry";
		exit;
	}
}

function fn_hp_merchant_milkrun_csv($company_data, $filename,$manifest_id)
{
	$i = 1;
	$output_str = "";
	if($company_data['manifest_id'])
	{
		$output_str = $output_str . "Manifest ID:," . $company_data['manifest_id'] . ",Date:," . $company_data['dispatch_date'] . ",Pickup Boy:," . $company_data['pickupboy'] . "\n";
	}
	
	
	$m_counts = fn_hp_get_order_and_product_count_for_manifest_for_company($manifest_id, $company_data['company_id']);
	
	$output_str = $output_str . "Merchant Name:, " . $company_data['company'] . "\n"
				. "Address:, " . $company_data['address'] . "," . $company_data['city'] 
				. $company_data['state'] . "\n"
				. "Phone:, " . $company_data['phone'] . "\n"
				. "Order Count:, " . $m_counts['order_count'] . "\n"
				. "Product Count:, " . $m_counts['product_count'] . "\n\n\n"
				. "Sl.,Order No,Order Date,Product Title,Selected Options,Qty,Unit Price,Line Subtotal,Order Subtotal,Shipping,Buyer Name,Address,City,State,Pincode,Mer. SKU,SCIN Number\n";
	$sql = "SELECT co.order_id, cod.amount,cod.selling_price, cod.product_id, cpd.product,
					co.s_firstname, co.s_lastname, co.timestamp, cod.product_code,
					co.s_address, co.s_address_2, co.s_city, co.s_state, (co.subtotal+co.discount) as subtotal,co.shipping_cost,
					co.s_country, co.s_zipcode, co.s_phone,co.s_zipcode, cp.merchant_reference_number,cmio.options
					FROM ?:order_details cod
					inner join clues_order_manifest_details md on md.manifest_id='".$manifest_id."' and md.order_id=cod.order_id
					inner JOIN ?:orders co ON cod.order_id = co.order_id
					inner JOIN ?:products cp ON cp.product_id = cod.product_id
					LEFT JOIN ?:product_descriptions cpd ON cpd.product_id = cp.product_id
					left join clues_manifest_item_options cmio on cmio.item_id=cod.item_id
					WHERE co.company_id = '" . $company_data['company_id'] . "'
					group by co.order_id,cod.item_id";
	$details = db_get_array($sql);
	foreach($details as $dkey => $dvalue)
	{
		$selected_options='';
		if(isset($dvalue['options'])){
			$selected_options = $dvalue['options'];
		}
		
		$output_str .= ($dkey+1) . ","
					. $dvalue['order_id'] . ","
					. date('Y-m-d', $dvalue['timestamp']) . ","
					. '"' . str_replace('"', ' ', $dvalue['product']) .' ",'
					. '"' . str_replace('"', ' ', $selected_options) . ' ",'
					. $dvalue['amount'] . ","
					. $dvalue['selling_price'] . ","
					. ($dvalue['amount']*$dvalue['selling_price']) . ","
					. $dvalue['subtotal'] . ","
					. $dvalue['shipping_cost'] . ","
					. '"' . str_replace('"', ' ', $dvalue['s_firstname'] . " " . $dvalue['s_lastname']) . ' ",'
					. '"' . str_replace('"', ' ', $dvalue['s_address'] . " " . $dvalue['s_address_2']) . ' ",'
					.'"' .  str_replace('"', ' ', $dvalue['s_city']) . ' ",'
					. '"' . str_replace('"', ' ', $dvalue['s_state']) . ' ",'
					. '"' . str_replace('"', ' ', $dvalue['s_zipcode']) . ' ",'
					. str_replace(',', ' ', $dvalue['merchant_reference_number']) . ","
					. str_replace(',', ' ', $dvalue['product_code']) . "\n";
	}
	
	file_put_contents("images/excel_upload/" . $filename, $output_str);
	
	return $filename;
}

function fn_hp_addday($dat,$days)//$dat will be in ymd function
{
	$dat2 = strtotime(date("Y-m-d", strtotime($dat)) . " +" . $days . " day");
	return date('Y-m-d', $dat2);
}
function fn_hp_status_description($status,$type)
{
	$arr = explode(",",$status);
	$str='';
	foreach($arr as $st)
	{
		$sql = "select description from ?:status_descriptions where status='".$st."' and `type`='".$type."'";
		if($str ==''){
			$str = db_get_field($sql);
		}
		else
		{
			$str = $str . ", " . db_get_field($sql);
		}
		
	}
	return $str;
}
function fn_hp_company_name($id)
{
	return db_get_field("select company from cscart_companies where company_id='".$id."'");
}
function fn_hp_order_status_history($order_id)
{
	$sql = "select h.*,sf.description as status_from_desc,st.description as status_to_desc, concat(u.firstname,' ',u.lastname) as username
			from clues_order_history h
			left join ?:status_descriptions st on st.status=h.to_status and st.type='O'
			left join ?:status_descriptions sf on sf.status=h.from_status and sf.type='O'
			left join ?:users u on u.user_id = h.user_id
			where order_id='".$order_id."'";
	$ret = db_get_array($sql);	
	return $ret;	
}
function fn_hp_timestamp_to_printdate($timestamp)
{
	return date("dM'y",$timestamp);
}
function fn_hp_get_order_shipments($order_id)
{
	$sql = "select s.shipment_id,s.tracking_number,s.timestamp,s.weight from ?:shipment_items si,?:shipments s where si.order_id='".$order_id."' and si.shipment_id=s.shipment_id group by si.shipment_id";
	//echo $sql;exit;
	$ret = db_get_array($sql);
	for($i=0;$i<count($ret);$i++)
	{
		$sql = "select * from ?:shipment_items where shipment_id='".$ret[$i]['shipment_id']."'";
		$ret[$i]['items'] = db_get_array($sql);
	}
	return $ret;
}
function fn_hp_get_carrier_lookup()
{
	return db_get_array("select * from clues_carrier_lookup where status='A'");
}
function fn_hp_get_email_templates()
{
	return db_get_array("select * from clues_email_templates");
}
function fn_hp_get_email_template($id)
{
	return db_get_row("select * from clues_email_templates where id='".$id."'");
}
function fn_hp_send_email_template_email_open($subject,$body,$to,$from,$values)
{
	//$from = array('email' => $company['company_orders_department'], 'name' => $company['company_name'])
	foreach($values as $key => $value)
	{
		$str = "{" . $key . "}"; 
		$subject = str_replace($str,$value,$subject);
		$body = str_replace($str,$value,$body);
	}
	fn_hp_send_mail($to,$from,$subject,$body);
	$arr[0]=$subject;
	$arr[1]=$body;
	return $arr;
}
function fn_hp_send_email_template_email($id,$to,$from,$values)
{
	$temp = fn_hp_get_email_template($id);
	fn_hp_send_email_template_email_open($temp['subject'],$temp['body'],$to,$from,$values);
}
function fn_get_clone_relationship($order_id)
{
	return db_get_array("select * from clues_order_clone_rel where main_order_id='".$order_id."' or clone_order_id='".$order_id."'");
}
function fn_hp_send_mail($to,$from,$subject,$body)
{
	db_query("insert into clues_email_queue set from_email='".$from."',to_email='".$to."',subject='".$subject."',message='".$body."',status='UNSENT'");
}
function fn_hp_store_sent_mail($order_id,$sub,$body,$temp_id,$auth,$from,$to)
{
	db_query("insert into clues_exception_email set order_id='".$order_id."',em_sub='".$sub."',em_body='".$body."',template_id='".$temp_id."',user_id='".$auth."',from_email='".$from."',to_email='".$to."'");
}
function fn_hp_is_company_mri_done($manifest_id,$company_id)
{
	$sql = "select * from clues_mri_receive where manifest_id='".$manifest_id."' and company_id='".$company_id."'";
	$ret = db_get_row($sql);
	if(!empty($ret))
	{
		return true;
	}
	else
	{
		return false;
	}
}
function fn_hp_is_mri_done($manifest_id)
{
	$sql = "select done from clues_order_manifest where manifest_id='".$manifest_id."'";
	$ret = db_get_field($sql);
	if($ret=='Y')
	{
		return true;
	}
	else
	{
		return false;
	}
}

//Changes by lokesh
function fn_hp_company_details($company_id)
{	
	$comp_detail = db_get_row("SELECT company,city FROM ?:companies WHERE company_id = '".$company_id."'");
	return $comp_detail;
}

function fn_hp_qadone_details($order_id)
{	
	$qadone_detail = db_get_array("SELECT * FROM clues_order_QA WHERE order_id = '".$order_id."'");
	return $qadone_detail;
}

function fn_hp_get_product_name($product_id)
{
	$product_name = db_get_field("select product from ?:product_descriptions where product_id='".$product_id."'");
	return $product_name;
}

function fn_hp_get_service_by($order_id)
{
	$service_by = db_get_array("select car.carrier_name from clues_carrier_lookup as car inner join clues_nss_order_carrier as nss on nss.carrier_id = car.carrier_id where nss.order_id = '".$order_id."'");
	return $service_by;
}

function fn_hp_get_last_exp($order_id)
{
	$action = db_get_array("select cause_id,type,datetime,auth,exc_notes from clues_exception_causes_order_rel where (type='Cause' or type='Action' or type='Tag') and order_id='".$order_id."' order by type");
	return $action;
}

function fn_hp_get_cause_detail($order_id)
{
	$cause = db_get_array("select cause_id,type,datetime,auth,exc_notes from clues_exception_causes_order_rel where type='Cause' and order_id='".$order_id."' order by datetime");
	return $cause;
}

function fn_hp_get_action_detail($order_id)
{
	$action = db_get_array("select cause_id,type,datetime,auth,exc_notes from clues_exception_causes_order_rel where type='Action' and order_id='".$order_id."' order by datetime");
	return $action;
}

function fn_hp_get_last_email($order_id)
{
	$email = db_get_row("select temp.title,exp.user_id,exp.creation_date from clues_exception_email as exp inner join clues_email_templates as temp on temp.id = exp.template_id where order_id='".$order_id."' limit 1");
	return $email;
}

function fn_hp_get_email_detail($order_id)
{
	$email = db_get_array("select temp.title,exp.user_id,exp.creation_date from clues_exception_email as exp inner join clues_email_templates as temp on temp.id = exp.template_id where order_id='".$order_id."'");
	return $email;
}

function fn_hp_get_exp_name($exp_id)
{
	$exp = db_get_field("select cause from clues_exception_causes_list where id = '".$exp_id."'");
	return $exp;
}

function fn_hp_get_user_name($user_id)
{
	$exp = db_get_field("select concat(firstname,' ',lastname) as name from cscart_users where user_id = '".$user_id."'");
	return $exp;
}

function fn_hp_get_pickup_boy($pickup_boy_id)
{
	$name = db_get_field("select pickupboy from clues_pickupboy where pickupboy_id = '".$pickup_boy_id."'");
	return $name;
}

function fn_hp_get_billing_cat()
{
	$billing_category = db_get_array("select * from clues_billing_categories");
	return $billing_category;
}

function fn_hp_get_action_value($id)
{
	$action = db_get_field("select cause from clues_exception_causes_list where id = '".$id."'");
	return $action;
}

function fn_hp_get_exp_qty($manifest_id)
{
		$exp_qty = db_get_field("select sum(amount) as exp_qty from (select comd.order_id, amount from clues_order_manifest_details as comd inner join cscart_order_details od on od.order_id=comd.order_id where comd.manifest_id = '".$manifest_id."' group by order_id,item_id) as tbl");
		return $exp_qty;
}

//Changes by lokesh end
function fn_hp_assign_awb_to_orders_for_merchants($order_ids)
{
	for($i=0;$i<count($order_ids);$i++)
	{
		$sql = "select company_id,payment_id from cscart_orders where order_id='".$order_ids[$i]."'";
		$data = db_get_row($sql);
		$sql = "select * from clues_nss_order_carrier where order_id='".$order_ids[$i]."'";
		$nssarr = db_get_array($sql);
		$nss = array();
		foreach($nssarr as $r)
		{
			$nss[$r['carrier_id']]=1;
		}
		$payment_type = 'prepaid';
		if($data['payment_id']==6)
		{
			$payment_type='cod';
		}
		$sql = "select * from clues_advance_awb_preference where company_id='".$data['company_id']."' and payment_type='".$payment_type."' order by preference";
		$res = db_get_array($sql);
		foreach($res as $row)
		{
			if(isset($nss[$row['carrier_id']]))
			{
				fn_hp_assign_awb_to_orders(array($order_ids[$i]),$row['carrier_id']);
				break;
			}
		}
	}
}
function fn_hp_assign_awb_to_orders($order_ids,$carrier_id)
{
	if($carrier_id!=0)
	{	
		$orderstr = implode(",",$order_ids);
		$countstatus = db_get_field("select count(order_id) from cscart_orders where order_id in (".$orderstr.") and status in ('P','G','E','Q')");
		//echo $countstatus;exit;
		if($countstatus < count($order_ids))
		{
			return "statuserror";
		}
		$countnss = db_get_array("select * from clues_nss_order_carrier where order_id in (".$orderstr.") and carrier_id='".$carrier_id."' group by order_id");
		if(count($countnss) < count($order_ids))
		{
			return "NSS";
		}
		
		$str = "select o.order_id,o.payment_id,awbno
				from cscart_orders o
				left join clues_advance_awb_order_id_assignment a on a.order_id=o.order_id and carrier_id='".$carrier_id."'
				where o.order_id in (".$orderstr.")";
		$ord_arr = db_get_array($str);
		$order_pp = array();
		$order_pp['assigned'] = array();
		$order_pp['require'] = array();
		$order_cod = array();
		$order_cod['assigned'] = array();
		$order_cod['require'] = array();
		foreach($ord_arr as $orderdata)
		{
			if($orderdata['payment_id']==6){
				if($orderdata['awbno'])
				{
					$order_cod['assigned'][] = $orderdata;
				}
				else
				{
					$order_cod['require'][] = $orderdata;
				}
			}
			else
			{
				if($orderdata['awbno'])
				{
					$order_pp['assigned'][] = $orderdata;
				}
				else
				{
					$order_pp['require'][] = $orderdata;
				}
			}
		}
		$pp_errorflag = 0;
		$cod_errorflag = 0;
		if(count($order_pp['require']) > 0){
			$prepaid_available = db_get_array("select id,awbno from clues_advance_awb where used='0' and carrier_id='".$carrier_id."' and payment_type='prepaid' limit 0," . count($order_pp['require']));
			if(count($prepaid_available) < count($order_pp['require']))
			{
				$pp_errorflag = 1;
			}
		}
		if(count($order_cod['require']) > 0){
			$cod_available = db_get_array("select id,awbno from clues_advance_awb where used='0' and carrier_id='".$carrier_id."' and payment_type='cod' limit 0," . count($order_cod['require']));
			if(count($cod_available) < count($order_cod['require']))
			{
				$cod_errorflag = 1;
			}
		}
		
		if($cod_errorflag && $pp_errorflag)
		{
			return "both insufficiant";
		}
		else if($cod_errorflag)
		{
			return "cod insufficiant";
		}
		else if($pp_errorflag)
		{
			return "prepaid insufficiant";
		}
		$auth = $_SESSION['auth'];
		if(count($order_pp['require']) > 0){
			$cnt = 0;
			foreach($order_pp['require'] as $order)
			{
				$sql = "select used from clues_advance_awb where id='".$prepaid_available[$cnt]['id']."'";
				$check = db_get_field($sql);
				if($check==1)
				{
					return "conflict.try again";
				}
				$sql = "insert into clues_advance_awb_order_id_assignment set order_id='".$order['order_id']."',
																			  carrier_id='".$carrier_id."',
																			  awbno='".$prepaid_available[$cnt]['awbno']."',
																			  auth='".$auth['user_id']."'";
				db_query($sql);														  
				$sql = "update clues_advance_awb set used='1' where id='".$prepaid_available[$cnt]['id']."'";
				db_query($sql);	
				$cnt++;
			}
		}
		if(count($order_cod['require']) > 0){
			$cnt = 0;
			foreach($order_cod['require'] as $order)
			{
				$sql = "select used from clues_advance_awb where id='".$cod_available[$cnt]['id']."'";
				$check = db_get_field($sql);
				if($check==1)
				{
					return "conflict.try again";
				}
				$sql = "insert into clues_advance_awb_order_id_assignment set order_id='".$order['order_id']."',
																			  carrier_id='".$carrier_id."',
																			  awbno='".$cod_available[$cnt]['awbno']."',
																			  auth='".$auth['user_id']."'";
				db_query($sql);														  
				$sql = "update clues_advance_awb set used='1' where id='".$cod_available[$cnt]['id']."'";
				db_query($sql);	
				$cnt++;
			}
		}
	}
	foreach($order_ids as $order_id)
	{
		$str = "select * from clues_advance_awb_order_id_assignment where order_id='".$order_id."' and carrier_id!='".$carrier_id."'";
		$ret = db_get_array($str);
		foreach($ret as $res)
		{
			$str = "delete from clues_advance_awb_order_id_assignment where id='".$res['id']."'";
			db_query($str);
			$str = "update clues_advance_awb set used='0' where awbno='".$res['awbno']."'";
			db_query($str);
		}
	}
	
	$sql = "insert into clues_advance_awb_assignment_manifest set carrier_id='".$carrier_id."',
																  count='".count($order_ids)."',
																  auth='".$_SESSION['auth']['user_id']."'";
	db_query($sql);
	$manifest_id = mysql_insert_id();
	$sql = "insert into clues_advance_awb_assignment_manifest_details(manifest_id,order_id) values";
	$comma = '';
	foreach($order_ids as $order_id)
	{
		$sql = $sql . $comma . "(".$manifest_id.",".$order_id.")";
		$comma = ",";
	}
	db_query($sql);
	
	return 'done';
	
}
function fn_hp_get_booked_awb_no($order_id,$carrier_id)
{
	return db_get_field("select awbno from clues_advance_awb_order_id_assignment where order_id='".$order_id."' and carrier_id='".$carrier_id."'");
}
function fn_hp_get_order_weight_awb_from_manifest($order_id)
{
	$sql = "select ms.weight,ms.awbno,cl.carrier_name
	from clues_order_manifest_details ms
	inner join clues_order_manifest m on m.manifest_id=ms.manifest_id
	inner join clues_carrier_lookup cl on cl.carrier_value=m.carrier_name
	where order_id='".$order_id."' and (weight!='' or awbno!='') order by ms.date_created desc limit 0,1";
	$row = db_get_row($sql);
	return $row;
}

function fn_html_to_print($html,$name='thermal-shipping-label.html')
{
	if (!is_array($html)) {
		$html = array($html);
	}
	$str = "
	<style>
	@media print {
.header, .hide { visibility: hidden }
}
	</style>
	";
	foreach($html as $ht)
	{
		$str = $str . $ht;
	}
	
	header("Content-type: application/x-msdownload");
	header("Content-Disposition: attachment; filename=".$name);
	header("Pragma: no-cache");
	header("Expires: 0");
	
	print $str;
	exit;
	
}

function fn_hp_mri_don_info($pickup_boy_id,$manifest_id,$comp_id,$product_id)
{
	$query ='';
	if(isset($product_id) && $product_id != '')
	{
		$query = " and cod.product_id='".$product_id."'";
	}
		$product_data = db_get_array("select cod.product_id, sum(md.qty) as qty,cmio.options  ,sum(md.expected) as expected from clues_mri_receive_details as md inner join clues_mri_receive as m on m.id = md.mri_receive_id inner join cscart_order_details as cod on cod.order_id = md.order_id left join clues_manifest_item_options as cmio on cmio.item_id=cod.item_id where m.manifest_id = '".$manifest_id."' and m.company_id = '".$comp_id."'".$query." group by cod.product_id");

		return $product_data;
}

function fn_hp_mri_don_info_order($pickup_boy_id,$manifest_id,$comp_id,$product_id)
{
	$query ='';
	if(isset($product_id) && $product_id != '')
	{
		$query = " and cod.product_id='".$product_id."'";
	}
		$order_data = db_get_array("select md.order_id, sum(md.qty) as qty, sum(md.expected) as expected, cod.product_id, md.action, md.notes,md.completed,cmio.options  from clues_mri_receive_details as md inner join clues_mri_receive as m on m.id = md.mri_receive_id inner join cscart_order_details as cod on cod.order_id = md.order_id and  md.item_id=cod.item_id left join clues_manifest_item_options as cmio on cmio.item_id=cod.item_id where m.manifest_id = ".$manifest_id." and m.company_id = '".$comp_id."'".$query." group by md.order_id,md.item_id");
		foreach($order_data as $order)
		{
			$new_array[$order['order_id']][]=$order;
			
		}

		return $new_array;
}

function fn_hp_get_mri_total_qty($comp_id,$manifest_id)
{
	$product_qty = db_get_row("select sum(md.qty) as total_qty, sum(md.expected) as total_exp from clues_mri_receive_details as md inner join clues_mri_receive as m on m.id = md.mri_receive_id inner join cscart_order_details as cod on cod.order_id = md.order_id where m.manifest_id = '".$manifest_id."' and m.company_id = '".$comp_id."'");
	
	return $product_qty;
}
function fn_hp_get_product_option($order_id)
{
 $sql = db_get_array("select item_id,extra from cscart_order_details where order_id='".$order_id."'");
 foreach($sql as $rvalue)
 {
  $rvalue['extra'] = unserialize($rvalue['extra']);
  if(isset($rvalue['extra']['product_options']) && !empty($rvalue['extra']['product_options']))
  {
   $selected_options = '';
   foreach($rvalue['extra']['product_options_value'] as $key => $value)
   {
    $selected_options .= $value['option_name'] . ': ' . $value['variant_name'] . ' ';
   }
   $selected_options = str_replace("'","''",$selected_options);
  }
 }
 
 if(!isset($selected_options))
 $selected_options='';
 
 return $selected_options;
}
function fn_get_manifest_orders_shipment_created_count($manifest_id)
{
	$sql = "select md.order_id
			from clues_order_manifest_details md
			inner join cscart_shipment_items si on si.order_id=md.order_id
			where md.manifest_id='".$manifest_id."'
			group by md.order_id";
	return count(db_get_array($sql));
}
function fn_get_order_dispatch_date($order_id)
{
	$sql = "select m.dispatch_creation_date from clues_order_manifest m
			inner join clues_order_manifest_details md on md.order_id='".$order_id."' and md.manifest_id=m.manifest_id
			where m.manifest_type_id=2 and dispatch_creation_date is not NULL";
	$dt = db_get_row($sql);
	if($dt){
		return $dt['dispatch_creation_date'];
	}
	else
	{
		return "";
	}
}
function fn_hp_get_manifest_csv_content($manifest_id,$keep_header=true,$extra='')
{
	$sql = "select * from clues_order_manifest where manifest_id='".$manifest_id."'";
	$manifest = db_get_row($sql);
	if($manifest)
	{
		$sql = "select o.*,md.weight,md.awbno,c.company
		from clues_order_manifest_details md
		inner join cscart_orders o on o.order_id=md.order_id
		inner join cscart_companies c on c.company_id=o.company_id
		where md.manifest_id='".$manifest_id."'";
		$order_info = db_get_array($sql);
		
		
		
		$courier_name = db_get_field("select carrier_name from clues_carrier_lookup where carrier_value='".$manifest['carrier_name']."'");
		if($keep_header==true)
		{
			$out = "AWB No.,Type,Order No.,Merchant Name,Ship To,Address1,Address2,Address3,Pincode,Tel. Number,Mobile number,Prod/SKU code,Product name,Weight(K.G.),Declared Value,Collectable Value,Vendor Code,Shipper Name,Return Address1,Return Address2,Return Address3,Return Pin,Length ( Cms ),Bredth ( Cms ),Height ( Cms ),Pieces,Carrier Name,Weight";
		}
		else
		{
			$out='';
		}
		
		if($extra==1)
		{
			$out.=",Manifest Id,Manifest Type,Dispatch Date,Notes,Pickup Location,Pickup By,Report Generated By\n";
			
		}
		else if($extra=='')
		{
			$out.="\n";
		}
		
		if($extra!='')
		{
			$extra_detail=fn_get_extra_manifest_detail($manifest_id);
		}
		foreach($order_info as $k=>$order_detail)
		{
			$oid = $order_detail['order_id'];
			if($order_detail['payment_id'] == '6')
			{ 
				$collectible_amt = $order_detail['total'];
			}else
			{ 
				$collectible_amt = '0.00'; 
			}
			if($order_detail['payment_id'] == '6')
			{
				$payment_type = 'COD';
			}else{
				$payment_type = 'NONCOD';
			}
			$full_address = str_replace(',',' ', $order_detail['s_address']).' '.str_replace(',',' ',$order_detail['s_address_2']).' '.$order_detail['s_city'].' '.$order_detail['s_state'].' Pincode: '.$order_detail['s_zipcode'];
			$address3 = '"'.$order_detail['s_city'].','.$order_detail['s_state'].'"';
			
			$prod_val = db_get_array("select p.product_code,product from cscart_order_details od,cscart_products p,cscart_product_descriptions pd where od.order_id='".$order_detail['order_id']."' and p.product_id=od.product_id and pd.product_id=od.product_id");
			$contents = '';
			$sku = '';
			foreach($prod_val as $prodline)
			{
				if($contents != '')
				{
					$contents = $contents . "|" . $prodline['product'];
				}
				else
				{
					$contents = $prodline['product'];
				}
				if($sku != '')
				{
					$sku = $sku . "|" . $prodline['product_code'];
				}
				else
				{
					$sku = $prodline['product_code'];
				}
			}
			
			
			
			
			
			$ffid = db_get_field("select fulfillment_id from cscart_companies where company_id='".$order_detail['company_id']."';");
			
			if($ffid==1){
				$r_address1 = "Clues Netword (P) Limited,Plot Number- 648,Bijwasan";
				$r_address2 = "";
				$r_address3 = "New Delhi";
				$r_pincode = "110061";
			}
			else
			{
				$wh_row = db_get_row("select * from clues_warehouse_contact where company_id='".$order_detail['company_id']."'");
				if($wh_row){
					$r_address1 = $wh_row['warehouse_address1'];
					$r_address1 = str_replace('"',' ',$r_address1);
					$r_address2 = $wh_row['warehouse_address2'];
					$r_address2 = str_replace('"',' ',$r_address2);
					$r_address3 = $wh_row['warehouse_city']. ", " . $wh_row['warehouse_state'];
					$r_address3 = str_replace('"',' ',$r_address3);
					$r_pincode = $wh_row['warehouse_pin'];
				}
				else
				{
					$cmp_row = db_get_row("select * from cscart_companies where company_id='".$order_detail['company_id']."'");
					$r_address1 = $cmp_row['address'];
					$r_address1 = str_replace('"',' ',$r_address1);
					$r_address2 = "";
					$r_address3 = $cmp_row['city']. ", " . $cmp_row['state'];
					$r_address3 = str_replace('"',' ',$r_address3);
					$r_pincode = $cmp_row['zipcode'];
				}
			}
			$wgt = (int)$order_detail['weight'];
			if($wgt)
			{
				$wgt = $wgt/1000;
			}
			//$address3 = str_replace(',',' ',$address3);
			$order_detail['s_address'] = str_replace(',',' ', $order_detail['s_address']);
			$order_detail['s_address'] = str_replace('"',' ', $order_detail['s_address']);
			$order_detail['s_address'] = str_replace('\n',' ', $order_detail['s_address']);
			$order_detail['s_address'] = str_replace('\r',' ', $order_detail['s_address']);
			
			$order_detail['s_address_2'] = str_replace(',',' ', $order_detail['s_address_2']);
			$order_detail['s_address_2'] = str_replace('"',' ', $order_detail['s_address_2']);
			$order_detail['s_address_2'] = str_replace('\n',' ', $order_detail['s_address_2']);
			$order_detail['s_address_2'] = str_replace('\r',' ', $order_detail['s_address_2']);
			$contents = str_replace(","," ",$contents);
			$out = $out . $order_detail['awbno'] . ",";
			$out = $out . $payment_type . ",";
			$out = $out . $oid . ",";
			$out = $out . $order_detail['company'] . ",";
			$out = $out . str_replace(',',' ',$order_detail['s_firstname'].' '.$order_detail['s_lastname']) . ",";
			$out = $out . '"' . $order_detail['s_address'] . '"' . ",";
			$out = $out . '"' . $order_detail['s_address_2'] . '"' .  ",";
			$out = $out . $address3 . ",";
			$out = $out . $order_detail['s_zipcode'] . ",";
			$out = $out . $order_detail['s_phone'] . ",";
			$out = $out . $order_detail['s_phone'] . ",";
			$out = $out . $sku . ",";
			$out = $out . $contents . ",";
			$out = $out . $wgt . ",";
			$out = $out . $order_detail['subtotal'] . ",";
			$out = $out . $collectible_amt . ",";
			$out = $out . $order_detail['company_id'] . ",";
			$out = $out . "Shopclues (" . $order_detail['company'] . "),";
			$out = $out . '"'.$r_address1.'"' . ",";
			$out = $out . '"'.$r_address2.'"' . ",";
			$out = $out . '"'.$r_address3.'"' . ",";
			$out = $out . $r_pincode . ",";
			$out = $out . ",";
			$out = $out . ",";
			$out = $out . ",";
			$out = $out . "1,";
			$out = $out . $courier_name . ",";
			if($extra=='')
			{
			$out = $out . $order_detail['weight']."\n";
			}
			else
			{
				$notes=$extra_detail['notes'];
				$notes=str_replace(',',' ',$notes);
				$notes=str_replace('"',' ',$notes);
				$notes=str_replace('\n',' ',$notes);
				
				$pl=str_replace(',',' ',$extra_detail['pickup_location']);
				$pl=str_replace('"',' ',$pl);
				$pl=str_replace('\n',' ',$pl);
				
				$pby=str_replace(',',' ',$extra_detail['pickup_by']);
				$pby=str_replace("\n",' ',$pby);
				$pby=str_replace("'"," ",$pby);
				
				$rgby=$extra_detail['generated_by_name'];
				$rgby=str_replace(',',' ',$rgby);
                $rgby=str_replace("\n",' ',$rgby);
				$rgby=str_replace("'"," ",$rgby);
				
				$out = $out .'"'. $order_detail['weight'].'"'.",";
				$out.='"'.$manifest_id.'"'.",";
				$out.='"'.$extra_detail['manifest_type'].'"'.",";
				$out.='"'.$extra_detail['dispatch_date'].'"'.",";
				$out.='"'.$notes.'"'.",";
				$out.='"'.$pl.'"'.",";
				$out.='"'.$pby.'"'.",";
				$out.='"'.$rgby.'"'."\n";
			}
			
		}
		return $out;
	}
	else
	{
		return "";
	}
}
function fn_hp_get_product_default_selling_fee($product_id)
{
	$pro_perc = 0;
	$sql = "select c.billing_category,default_commision from cscart_products_categories pc
			inner join cscart_categories c on pc.category_id=c.category_id
			inner join clues_billing_categories b on b.id=c.billing_category
			where pc.product_id='".$product_id."'";
	$result = db_get_array($sql);
	if($result)
	{
		foreach($result as $res_b)
		{
			if($res_b['default_commision']>0)
			{
				$pro_perc=$res_b['default_commision'];
				break;
			}
		}
	}
	if($pro_perc==0)
	{
		$pro_perc=8;
	}
	return $pro_perc;
}
function fn_get_extra_manifest_detail($manifest_id)
{
	return db_get_row("select cmt.description as manifest_type,com.dispatch_date,com.notes,com.pickup_location,com.pickup_by,com.generated_by_name from clues_order_manifest com inner join clues_manifest_type cmt on cmt.manifest_type_id=com.manifest_type_id where com.manifest_id='".$manifest_id."'");
}
function fn_hp_get_product_seo_name($pro_id)
{
	return db_get_field("select name from cscart_seo_names where object_id='".$pro_id."' and type='P'");
}
function fn_hp_get_user_group($user_id)
{
	return db_get_row("select usergroup_id from cscart_usergroup_links where user_id='".$user_id."' and status='A'");
}
function fn_hp_get_users_carrier_name($user_id)
{
	return db_get_field("select carrier_value from clues_carrier_lookup ccl inner join						cscart_users cu on ccl.carrier_id=cu.carrier_id  where user_id='".$user_id."'");
}
function fn_hp_check_company_mri_manifest_for_today($company_id)
{
	$sql = "select m.manifest_id,m.pickupboy_id
			from clues_order_manifest m
			inner join clues_order_manifest_details md on md.manifest_id=m.manifest_id
			inner join cscart_orders o on o.order_id=md.order_id and company_id='".$company_id."'
			where m.dispatch_date='".date('d/m/Y')."' and m.manifest_type_id='3'
			group by m.manifest_id order by manifest_id desc";
	$res = db_get_array($sql);
	//var_dump($res);
	if($res)
	{
		return $res;
	}
	else
	{
		return "";
	}
}
function fn_hp_get_order_and_product_count_for_manifest_for_company($manifest_id,$company_id)
{
	$order_count_query = "SELECT COUNT(DISTINCT(o.order_id)) as total_order_count
							from clues_order_manifest_details md
							inner join cscart_orders o on o.order_id=md.order_id and company_id='".$company_id."'
							where md.manifest_id='".$manifest_id."'";
	$total_order_counts = db_get_field($order_count_query);
	
	$product_count_query = "select sum(amount) as total_product_count from (SELECT od.amount
							from clues_order_manifest_details md
							inner join cscart_orders o on o.order_id=md.order_id and company_id='".$company_id."'
							inner join cscart_order_details od on od.order_id=md.order_id
							where md.manifest_id='".$manifest_id."'
							group by md.order_id,od.item_id) as tbl";
	$total_product_counts = db_get_field($product_count_query);
	return array('order_count'=>$total_order_counts,'product_count'=>$total_product_counts);
}
function fn_hp_get_promotion_types()
{
	$sql = "select * from clues_promotion_type";
	return db_get_array($sql);
}

function fn_hp_divide_status_in_group($status_descr)
{
	$not_shipped_group=Registry::get('config.not_shipped_group');
	$in_process_group=Registry::get('config.in_process_group');
	$forward_state_group=Registry::get('config.forward_logistics_state_group');
	$reverse_state_group=Registry::get('config.reverse_logistics_state_group');
	$returns_group=Registry::get('config.returns_group');
	$exception_group=Registry::get('config.exception_group');
	$ret=array();
	foreach($status_descr as $key=>$value)
	{
		if(in_array($key,$not_shipped_group))
		{
			$ret['not_shipped_group'][$key]=$value;
		}
		else if(in_array($key,$in_process_group))
		{
			$ret['in_process_group'][$key]=$value;
		}
		else if(in_array($key,$forward_state_group))
		{
			$ret['forward_logistics_state_group'][$key]=$value;
		}
		else if(in_array($key,$returns_group))
		{
			$ret['returns_group'][$key]=$value;
		}
		else if(in_array($key,$exception_group))
		{
			$ret['exception_group'][$key]=$value;
		}
		else if(in_array($key,$reverse_state_group))
		{
			$ret['reverse_logistics_state_group'][$key]=$value;
		}
	}
	$return=array();
	$return['not_shipped_group']=$ret['not_shipped_group'];
	$return['in_process_group']=$ret['in_process_group'];
	$return['forward_logistics_state_group']=$ret['forward_logistics_state_group'];
	$return['reverse_logistics_state_group']=$ret['reverse_logistics_state_group'];
	$return['returns_group']=$ret['returns_group'];
	$return['exception_group']=$ret['exception_group'];
	return $return;
}
function get_customer_queries_for_order_id($order_id,$user_id)
{
    $cust_q_query="SELECT * FROM clues_customer_queries WHERE user_id=".$user_id." AND order_id=".$order_id;
    $cust_q_list=db_get_array($cust_q_query);
    //print_r($cust_q_list);
    //print_r($cust_q_query); 
    return $cust_q_list;
}
function fn_hp_company_sum_net_payout($company_id)
{
	$sql = "select sum(Sum_Net_Payout) from clues_billing_payout_summary where company_id='".$company_id."'";
	$ret = db_get_field($sql);
	if(!$ret){
		$ret = 0;
	}
	return $ret;
}
function fn_hp_company_sum_paid($company_id)
{
	$sql = "select sum(amount) from clues_billing_company_payments where company_id='".$company_id."'";
	$ret = db_get_field($sql);
	if(!$ret){
		$ret = 0;
	}
	return $ret;
}
function fn_hp_check_user_group($user_id)
{
	$sql = "select p.privilege from cscart_usergroup_links l
			inner join cscart_usergroup_privileges p on l.usergroup_id=l.usergroup_id
			where user_id='".$user_id."' and status='A'";
	$ret = db_get_array($sql);
	$pre = array();
	foreach($ret as $res)
	{
		$pre[$res['privilege']] = 1;
	}
	return $pre;
}
function fn_hp_get_payment_request_status_list($user_type)
{
	$sql = "select status,description from cscart_status_descriptions where type='B' and lang_code='EN'";
	$res = db_get_array($sql);
	$sql = "select * from clues_billing_payment_request_status_access";
	$per = db_get_array($sql);
	for($i=0;$i<count($res);$i++)
	{
		$res[$i]['locked']='1';
		$res[$i]['notsettable']='1';
		for($j=0;$j<count($per);$j++)
		{
			if($res[$i]['status']==$per[$j]['status'] && $per[$j]['user_type']==$user_type)
			{
				$res[$i]['locked']=$per[$j]['locked'];
				$res[$i]['notsettable']=$per[$j]['notsettable'];
			}
		}
	}
	return $res;
}
function fn_hp_get_payment_request_status_description($status)
{
	$sql = "select description from cscart_status_descriptions where type='B' and status='".$status."' and lang_code='EN'";
	$res = db_get_field($sql);
	return $res;
}
function fn_hp_payment_request_change_status($request_id,$statusto,$statusfrom,$comments,$user_type,$dont_validate = 0)
{
	$statuslist = fn_hp_get_payment_request_status_list($user_type);
	$errorflag = 1;
	if($dont_validate==0){
		$fromstatus = db_get_field("select status from clues_billing_payment_request where id='".$request_id."'");
		if($statusfrom != $fromstatus)
		{
			return "fromstatusnotmatched";
		}
		for($i=0;$i<count($statuslist);$i++)
		{
			if($statuslist[$i]['status']==$fromstatus)
			{
				if($statuslist[$i]['locked']==1)
				{
					//return "error";
				}
			}
			if($statuslist[$i]['status']==$statusto)
			{
				if($statuslist[$i]['notsettable']==1)
				{
					//return "error";
				}
				else
				{
					$errorflag = 0;
				}
			}
		}
	}
	else
	{
		$errorflag = 0;
	}
	if($errorflag)
	{
		return "error";
	}
	else
	{
		$sql = "update clues_billing_payment_request set status='".$statusto."' where id='".$request_id."'";
		db_query($sql);
		$sql = "insert into clues_billing_payment_request_history set request_id='".$request_id."',
																	  from_status='".$fromstatus."',
																	  to_status='".$statusto."',
																	  comments='".$comments."',
																	  auth='".$_SESSION['auth']['user_id']."'";
		db_query($sql);															  
		return fn_hp_get_payment_request_status_description($statusto);
	}
}
function fn_hp_already_received_history($order_id,$item_id='')
{
	if($item_id)
	{
		$sql = "select d.*,r.manifest_id,r.pickupboy_id,datetime,auth from clues_mri_receive_details d
				inner join clues_mri_receive r on r.id=d.mri_receive_id
				where d.order_id='".$order_id."' and item_id='".$item_id."' and d.qty>0";
	}
	else{
		$sql = "select d.*,r.manifest_id,r.pickupboy_id,datetime,auth from clues_mri_receive_details d
				inner join clues_mri_receive r on r.id=d.mri_receive_id
				where d.order_id='".$order_id."' where d.qty>0";
	}
	return db_get_array($sql);		
	
}
function fn_hp_get_billing_payment_history($id)
{
	$sql = "select * from clues_billing_payment_request_history where request_id='".$id."' order by id desc";
	return db_get_array($sql);
}
function fn_hp_get_company_current_payment_status($company_id)
{
	$net_payout = fn_hp_company_sum_net_payout($company_id);
	$settled = fn_hp_get_company_settled_amount($company_id);
	$paid = fn_hp_company_sum_paid($company_id);
	$approved = fn_hp_get_company_approved_amount($company_id);
	return array('net_payout'=>$net_payout,'settled'=>$settled,'paid'=>$paid,'approved'=>$approved);
}
function fn_hp_get_company_approved_amount($company_id)
{
	$sql = "select sum(disbursement_amount) as sm from clues_billing_payment_request where status in ('L','A')";
	$res = db_get_field($sql);
	return $res;
}
function fn_hp_get_company_settled_amount($company_id)
{
	$sql = "select sum(net_payout) from clues_billing_order_data where settled='Y' and company_id='".$company_id."'";
	$res1 = db_get_field($sql);
	$sql = "select sum(Sum_Net_Payout) from clues_billing_payout_summary where settled='Y' and company_id='".$company_id."'";
	$res2 = db_get_field($sql);
	return $res1+$res2;
}
function fn_hp_settle_billing_cycle($billing_cycles,$amount,$company_id,$request_id)
{
	$balance = $amount;
	foreach($billing_cycles as $bc)
	{
		$sql = "select id,net_payout from clues_billing_order_data where billing_cycle='".$bc."' and company_id='".$company_id."' and settled='N' order by net_payout";
		$res = db_get_array($sql);
		if($res)
		{
			foreach($res as $row)
			{
				if($balance>$row['net_payout'])
				{
					$balance = $balance - $row['net_payout'];
					$sql = "update clues_billing_order_data set settled='Y', settled_through_request='".$request_id."' where id='".$row['id']."'";
					db_query($sql);
				}
				else
				{
					continue;
				}
			}
			
		}
		else//means that this is just an adjustment
		{
			$sql = "select id,Sum_Net_Payout as net_payout from clues_billing_payout_summary where billing_cycle='".$bc."' and company_id='".$company_id."' and settled='N'";
			$row = db_get_row($sql);
			if($balance>$row['net_payout'])
			{
				$balance = $balance - $row['net_payout'];
				$sql = "update clues_billing_payout_summary set settled='Y', settled_through_request='".$request_id."' where id='".$row['id']."'";
				db_query($sql);
			}
		}
	}
	return $amount-$balance;
}
function fn_hp_unsettle_against_request_id($request_id)
{
	$sql = "update clues_billing_order_data set settled='N',settled_through_request='0' where settled_through_request='".$request_id."'";
	db_query($sql);
	$sql = "update clues_billing_payout_summary set settled='N',settled_through_request='0' where settled_through_request='".$request_id."'";
	db_query($sql);
}
function fn_hp_get_bank_details($request_id,$manifest_id)
{
	$sql = "select * from clues_billing_payment_request where id='".$request_id."'";
	$row = db_get_row($sql);
	if($row['entity_type']=='Merchant')
	{
		$company_id = $row['entity_id'];
		$sql = "select credit_bank from clues_billing_payment_request_manifest where manifest_id='".$manifest_id."' and request_id='".$request_id."'";
		$bankdetails = db_get_field($sql);
		if(!$bankdetails)
		{
			$sql = "select * from clues_company_bank where company_id='".$row['entity_id']."'";
			$cb = db_get_row($sql);
			if($cb)
			{
				$arr = array("account_name"=>$cb['account_name'],"bank_name"=>$cb['bank_name'],"account_number"=>$cb['account_number'],"IFSC"=>$cb['ifsc_code']);
				db_query("update clues_billing_payment_request_manifest set credit_bank='".serialize($arr)."' where manifest_id='".$manifest_id."' and request_id='".$request_id."'");
				return $arr;
			}
		}
		else
		{
			$arr = unserialize($bankdetails);
			return $arr;
		}
	}
	else
	{
		return;
	}
}
function fn_hp_get_shopclues_bank_details($id='')
{
	if($id)
	{
		$sql = "select * from clues_billing_payment_shopclues_accounts where id='".$id."'";
		return db_get_row($sql);
	}
	else
	{
		$sql = "select * from clues_billing_payment_shopclues_accounts";
		return db_get_array($sql);
	}
}
function fn_check_is_order_gift_item($order_id)
{
	$sql="select gift_it from cscart_orders where order_id='".$order_id."'";
	$res=db_get_field($sql);
	if($res=='Y')
	return true;
	else
	return false;
}
//function by ankur to get orders gift message info
function fn_get_order_gift_message($order_id)
{
	$sql="select gift_to,gift_from,message from clues_gift_message where order_id='".$order_id."' and no_message='N'";
	return db_get_row($sql);
}
//function end
?>