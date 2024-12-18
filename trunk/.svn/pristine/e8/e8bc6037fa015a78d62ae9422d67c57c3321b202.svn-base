<?php
// HPRAHI CREATED THIS 2 FUNCs. will return cod/prepaid
function fn_get_order_tracking_no($order_id,$str=false)
{
	$sql = "select s.shipment_id,s.tracking_number,s.carrier from ?:shipments s,?:shipment_items i where i.order_id='".$order_id."' and i.shipment_id=s.shipment_id group by s.shipment_id";
	$ret = db_get_array($sql);
	if($str)
	{
		$str_s='';
		foreach($ret as $t)
		{
			if($str_s=='')
			{
				$str_s = $t['tracking_number'] . "/" . $t['carrier'];
			}
			else
			{
				$str_s = $str_s . ", " . $t['tracking_number'] . "/" . $t['carrier'];
			}
		}
		return $str_s;
	}
	return $ret;
}
function fn_get_order_payment_method($order_id=false,$status=false)
{
	$st = $status;
	if($order_id){
		$sql = "select payment_id from ?:orders where order_id='".$order_id."'";
		$ret = db_get_field($sql);
		if($ret)
		{
			$st = $ret;
		}
	}
	if(!$st)
	{
		return false;
	}
	else if($st == 6)
	{
		return "COD";
	}
	else
	{
		return "Prepaid";
	}
}
//Created by HPRAHI
function NSS_decision_order_can_be_shipped_with()
{
	// open paid cod confirmed milkrun ini comp
	$sql = "select order_id,s_zipcode,payment_id from ?:orders where status in('O','P','Q','E','G') and nss_done!='y'";
	$res = db_get_array($sql);
	foreach($res as $v)
	{
		$v['s_zipcode'] = str_replace(" ","",$v['s_zipcode']);
		if($v['payment_id']==6)
		{
			$sql = "select * from clues_carriers_service_area where pincode='". $v['s_zipcode'] ."' and is_cod='Y'";
		}
		else
		{
			$sql = "select * from clues_carriers_service_area where pincode='". $v['s_zipcode'] ."'";
		}
		$ret = db_get_array($sql);
		foreach($ret as $k)
		{
			$sql = "delete from clues_nss_order_carrier where order_id='". $v['order_id'] ."' and carrier_id='". $k['carrier_id'] ."'";
			db_query($sql);
			$sql = "insert into clues_nss_order_carrier set order_id='". $v['order_id'] ."',carrier_id='". $k['carrier_id'] ."'";
			db_query($sql);
			$sql = "update ?:orders set nss_done='y' where order_id='". $v['order_id'] ."'";
			db_query($sql);
		}
	}
	//clues_nss_order_carrier
}
//Created by HPRAHI
function order_nss_detail_html($order_id)
{
	$sql = "select cc.carrier_name from clues_nss_order_carrier c left join clues_carrier_lookup cc on cc.carrier_id=c.carrier_id where order_id='".$order_id."'";
	$res = db_get_array($sql);
	$str = '';
	foreach($res as $row)
	{
		if($str == '')
		{
			$str = $row['carrier_name'];
		}
		else
		{
			$str = $str . ", " . $row['carrier_name'];
		}
	}
	if($str=='')
	{
		$str = "none";
	}
	return $str;
}
//Created by HPRAHI
function order_exc_cause_html_ord($order_id)
{
	$sql = "select cr.*,cl.cause,cd.description, concat(u.firstname, ' ',u.lastname) as user from clues_exception_causes_order_rel cr 
	left join clues_exception_causes_list cl on cr.cause_id = cl.id
	left join cscart_status_descriptions cd on cr.order_status = cd.status and cd.type='O'
	left join cscart_users u on u.user_id=cr.auth
	 where cr.order_id='". $order_id ."' order by id desc";
	$res = db_get_array($sql);
	
	$sql1="select exm.em_sub,exm.em_body,exm.to_email,concat(u.firstname,' ',u.lastname) as user,exm.creation_date from clues_exception_email exm
	left join cscart_users u on u.user_id=exm.user_id
	where exm.order_id='".$order_id."' order by exm.creation_date desc";
	$res1=db_get_array($sql1);
	
	$html = "<div id='causesdetail'> 
	<table border='1'><tr><th><strong>Type</strong></th><th><strong>Cause</strong></th><th><strong>Status was</strong></th><th><strong>Date</strong></th><th><strong>Notes</strong></th><th><strong>User</strong></th></tr>";
	foreach($res as $row)
	{
		$done = '';
		$style = ' style="text-decoration: line-through;" ';
		if($row['latest'])
		{
			$style='';
			$done = "<a href='javascript:causedone(".$row['id'].",".$order_id.")'>Done</a>";
		}
		$html = $html . "
			<tr ".$style."><td>".$row['type']."</td><td>".$row['cause']."<td>".$row['description']."</td><td>". date('d M H:i', strtotime($row['datetime']))."</td><td>".$row['exc_notes']."</td>
			<td>".$row['user']."</td><td>".$done."</td></tr>				
		";
	}
	$html = $html . '</table></div>';
	
	$html = $html . '<div id="email_detail" style="display:none">
			<table border="1"><tr><th style="text-align:center;width:80px"><strong>Email Subject</strong></th><th style="text-align:center;width:280px"><strong>Email Body</strong></th><th style="text-align:center"><strong>User</strong></th><th style="text-align:center"><strong>Sent To</strong></th><th style="text-align:center"><strong>Date</strong></th></tr>';
		foreach($res1 as $row)
		{
			$html = $html ."<tr>
			 
			   <td>".$row['em_sub']."</td>
			   <td><div style='height:100px;width:280px;overflow:auto'>".$row['em_body']."</div></td>
			   <td>".$row['user']."</td>
			   <td>".$row['to_email']."</td>
			   <td>".date('d M H:i', strtotime($row['creation_date']))."</td>
			</tr>";
			
		}
		
		
		$html = $html .'</table>
		</div>';
	return $html;
}
//Created by HPRAHI
function order_exc_cause_html($order_id)
{
	$sql = "select cr.*,cl.cause,cd.description, concat(u.firstname, ' ',u.lastname) as user from clues_exception_causes_order_rel cr 
	inner join clues_exception_causes_list cl on cr.cause_id = cl.id
	left join cscart_status_descriptions cd on cr.order_status = cd.status and cd.type='O'
	left join cscart_users u on u.user_id=cr.auth
    where cr.order_id='". $order_id ."' order by id desc";
	$res = db_get_array($sql);
	
	$sql1="select emt.title,exm.em_sub,exm.em_body,exm.to_email,concat(u.firstname,' ',u.lastname) as user,exm.creation_date from clues_exception_email exm
	inner join cscart_users u on u.user_id=exm.user_id
	inner join clues_email_templates emt on emt.id=exm.template_id
	where exm.order_id='".$order_id."' order by exm.creation_date desc";
	$res1=db_get_array($sql1);
	
	$arr = array();
	$html = '';
	if(count($res) || count($res1)){
		$html = $html . "<div style='width:200px'>";
	}
	$col = '#ccc';
	if(count($res)){
		foreach($res as $row)
		{
			$arr[$row['type']][] = $row;
		}
		
		$i=0;
		
		if($arr['Cause']){
		foreach($arr['Cause'] as $row)
		{
			$i++;
			if($i==1)
			{
				$html = $html . "<span style='font-size:11px'>Reason</span>";
			}
			if($col=='#f0f5f7')
			{
				$col='#e4e9eb';
			}
			else
			{
				$col='#f0f5f7';
			}
			if($row['latest'])
			{
				$html = $html . "<div style='background:".$col.";font:11px verdana;color:#575859'><div style='float:left'>".$row['cause']."</div><div style='float:right'><a href='javascript:causedone(".$row['id'].",".$order_id.")'>Done</a></div><div style='clear:both'></div></div>";
			}
			else
			{
				$html = $html . "<div style='background:".$col.";font:11px verdana;color:#575859;text-decoration:line-through'>".$row['cause']."</div>";
			}
			
		}}
		$i=0;
		if($arr['Action']){
		foreach($arr['Action'] as $row)
		{
			$i++;
			if($i==1)
			{
				$html = $html . "<span style='font-size:11px'>Action</span>";
			}
			if($col=='#f0f5f7')
			{
				$col='#e4e9eb';
			}
			else
			{
				$col='#f0f5f7';
			}
			if($row['latest'])
			{
				$html = $html . "<div style='background:".$col.";font:11px verdana;color:#575859'><div style='float:left'>".$row['cause']."</div><div style='float:right'><a href='javascript:causedone(".$row['id'].",".$order_id.")'>Done</a></div><div style='clear:both'></div></div>";
			}
			else
			{
				$html = $html . "<div style='background:".$col.";font:11px verdana;color:#575859;text-decoration:line-through'>".$row['cause']."</div>";
			}
			
		}
		}
		$i=0;
		if($arr['Tag']){
		$html = $html . "<div style='padding:3px'>";	
		foreach($arr['Tag'] as $row)
		{
			$i++;
			if($i==1)
			{
				$html = $html . "<div style='float:left;margin-right:3px'><span style='font-size:11px'>Tag</span></div>";
			}
			if($col=='#f0f5f7')
			{
				$col='#e4e9eb';
			}
			else
			{
				$col='#f0f5f7';
			}
			$html = $html . "<div id='causediv".$row['id']."' style='background:#e4e9eb;font:11px verdana;color:#575859;font-style:italic;float:left;margin-right:3px;padding:2px;margin-bottom:3px'>".$row['cause']." <a href='javascript:void(0)' style='color:#fff;' onclick=\"deletetag('".$row['id']."')\">X</a></div>";
			
		}
		$html = $html . "<div style='clear:both'></div></div>";
		}
	}
		if(!empty($res1))
		{
			$i=0;
			$html = $html . "<div style='padding:3px'>";
			foreach($res1 as $row)
			{
				$i++;
				if($i==1)
				{
					$html = $html . "<span style='font-size:11px'>Sent Emails</span>";
				}
				if($col=='#f0f5f7')
				{
					$col='#e4e9eb';
				}
				else
				{
					$col='#f0f5f7';
				}
				$subject=$row['title'];
				$html = $html . "<div style='background:".$col.";font:11px verdana;color:#575859;'>".$subject."</div>";
				
			}
		}
		if(count($res) or count($res1)){
		$html = $html . "<a href=\"javascript:viewdetailedcasues('".$order_id."')\">View Details</a></div>";
		$html = $html . '<div style="padding:10px;display:none;width:600px;position:fixed;top:50px;left:300px;height:400px;overflow:auto;background:#fff;border:2px solid black" id="viewdetailedcasues'.$order_id.'">
		<div style="float:left">For Order:' . $order_id . "</div>
		<div style='float:right'>
			<a href=\"javascript:hidedetailedcasues('".$order_id."'); \">X</a>
		</div>
		<div style='clear:both'></div>
		<div style=\"margin:8px\">
			<div style=\"float:left; width:115px;height:25px;padding-top:12px;cursor:pointer;font-weight:bold; text-align:center; border:1px solid #eee; background-color:#0A9CCC\" class=\"ord_ex_listing\" onclick=\"$('#email_detail_".$order_id."').hide();$('#causesdetail_".$order_id."').show();$(this).parent().children().css('background-color','#fafafa');$(this).css('background-color','#0A9CCC');\">Order Exception</div>
			<div style=\"float:left;width:100px;height:25px;margin-left:5px;font-weight:bold;cursor:pointer;padding-top:12px; text-align:center; border:1px solid #eee; background-color:#fafafa\" class=\"snd_email_listing\" onclick=\"$('#email_detail_".$order_id."').show();$('#causesdetail_".$order_id."').hide();$(this).parent().children().css('background-color','#fafafa');$(this).css('background-color','#0A9CCC');\">Sent Emails</div>
			<div style=\"clear:both\"></div>
		</div>
	  <div id='causesdetail_".$order_id."'>
		<table class='table'><tr><th style=\"text-align:center\"><strong>Type</strong></th><th style=\"text-align:center\"><strong>Cause</strong></th><th style=\"text-align:center\"><strong>Status was</strong></th><th style=\"text-align:center\"><strong>Date</strong></th><th style=\"text-align:center\"><strong>Notes</strong></th><th style=\"text-align:center\"><strong>User</strong></th><th style=\"text-align:center\">Done</th></tr>";
		foreach($res as $row)
		{
			$done = '';
			$style = ' style="text-decoration: line-through;" ';
			if($row['latest'])
			{
				$style='';
				$done = "<a href='javascript:causedone(".$row['id'].",".$order_id.")'>Done</a>";
			}
			$html = $html . "
				<tr ".$style."><td>".$row['type']."</td><td>".$row['cause']."</td><td>".$row['description']."</td><td>". date('d M H:i', strtotime($row['datetime']))."</td><td>".$row['exc_notes']."</td>
				<td>".$row['user']."</td><td>".$done."</td></tr>				
			";
		}
		$html = $html . '</table></div>
		
		<div id="email_detail_'.$order_id.'" style="display:none">
			<table class="table"><tr><th style="text-align:center"><strong>Email Subject</strong></th><th style="text-align:center"><strong>Email Body</strong></th><th style="text-align:center"><strong>User</strong></th><th style="text-align:center"><strong>Sent To</strong></th><th style="text-align:center"><strong>Date</strong></th></tr>';
		foreach($res1 as $row)
		{
			
			$html = $html ."<tr>
			 
			   <td>".$row['em_sub']."</td>
			   <td><div style='height:100px;width:200px;overflow:auto'>".$row['em_body']."</div></td>
			   <td>".$row['user']."</td>
			   <td>".$row['to_email']."</td>
			   <td>".date('d M H:i', strtotime($row['creation_date']))."</td>
			</tr>";
			
		}
		
		
		$html = $html .'</table>
		</div>';
		$html=$html."</div>";
	}
	if(count($res) || count($res1)){
      $html=$html."</div>";
	}
	return $html;
}

/**

 * Returns orders

 *

 * @param array $params array with search params

 * @param int $items_per_page

 * @param bool $get_totals

 * @param string $lang_code

 * @return array

 */


function fn_get_orders($params, $items_per_page = 0, $get_totals = false, $lang_code = CART_LANGUAGE)

{

	// Init filter

	$params = fn_init_view('orders', $params);



	// Set default values to input params

	$params['page'] = empty($params['page']) ? 1 : $params['page']; // default page is 1



	if (AREA != 'C') {

		$params['include_incompleted'] = empty($params['include_incompleted']) ? false : $params['include_incompleted']; // default incomplited orders should not be displayed

		if (!empty($params['status']) && (is_array($params['status']) && in_array(STATUS_INCOMPLETED_ORDER, $params['status']) || !is_array($params['status']) && $params['status'] == STATUS_INCOMPLETED_ORDER)) {

			$params['include_incompleted'] = true;

		}

	} else {

		$params['include_incompleted'] = true; //Modified by clues dev to show incomplete orders to customer.

	}



	// Define fields that should be retrieved

	$fields = array (


		"distinct ?:orders.order_id", // "distinct ?:orders.order_id", changed by sudhir dt 29th octo 2012 to optimize query

		"?:orders.order_id", // "distinct ?:orders.order_id", changed by sudhir dt 29th octo 2012 to optimize query

		"?:orders.user_id",

		"?:orders.is_parent_order",

		"?:orders.parent_order_id",

		"?:orders.company_id",

		"?:orders.timestamp",

		"?:orders.firstname",

		"?:orders.lastname",

		"?:orders.email",

		"?:orders.status",

		"?:orders.total",

		"?:orders.subtotal",//modified by chandan

		"?:orders.details", // modified by Sudhir
		
		"?:orders.payment_id",
		"?:orders.s_city",
		"?:orders.s_state",
		"?:orders.s_zipcode",
		"?:orders.label_printed",
		"?:orders.gift_it",
		"?:orders.phone"

	);
	if(isset($params['ff'])){
		$fields[] = "(select sum(amount) from cscart_order_details csord where csord.order_id=?:orders.order_id) as qty";
	}

	// Define sort fields

	$sortings = array (

		'order_id' => "?:orders.order_id",

		'status' => "?:orders.status",

		'customer' => array("?:orders.lastname", "?:orders.firstname"),

		'email' => "?:orders.email",

		'date' => array("?:orders.timestamp", "?:orders.order_id"),

		'total' => "?:orders.total",
		'qty' => "qty",
		'payment_mode'=>'?:orders.payment_id',
		'product' => 'prods.product'
	);



	$directions = array (

		'asc' => 'asc',

		'desc' => 'desc'

	);



	fn_set_hook('pre_get_orders', $params, $fields, $sortings, $items_per_page, $get_totals, $lang_code);



	if (empty($params['sort_order']) || empty($directions[$params['sort_order']])) {

		$params['sort_order'] = 'desc';

	}



	if (empty($params['sort_by']) || empty($sortings[$params['sort_by']])) {

		//$params['sort_by'] = 'date';
		$params['sort_by'] = 'order_id'; // changed by Sudhir dt 29th octo 2012 to optimize query

	}
	/*hprahi starts*/
	if(isset($params['ff'])){
		if(!isset($_GET['sort_order']))
		{
			$params['sort_order'] = 'asc';
		}
	}
	/*hprahi ends*/
	$sorting = (is_array($sortings[$params['sort_by']]) ? implode(' ' . $directions[$params['sort_order']] . ', ', $sortings[$params['sort_by']]): $sortings[$params['sort_by']]) . ' ' . $directions[$params['sort_order']];
	/*hprahi starts*/
	//if($params['ff']){
	//	if($params['sort_by'] != 'date'){
	//		$sorting = $sorting . ", ?:orders.timestamp , ?:orders.order_id ";
	//	}
	//}
	/*hprahi ends*/

	// Reverse sorting (for usage in view)

	$params['sort_order'] = $params['sort_order'] == 'asc' ? 'desc' : 'asc';



	if (isset($params['compact']) && $params['compact'] == 'Y') {

		$union_condition = ' OR ';

	} else {

		$union_condition = ' AND ';

	}



	$condition = $_condition = $join = $group = '';

	

	$condition .= " AND ?:orders.is_parent_order != 'Y' ";

	$condition .= fn_get_company_condition('?:orders.company_id');


 
	if (isset($params['cname']) && fn_string_no_empty($params['cname'])) {

		$arr = fn_explode(' ', $params['cname']);

		foreach ($arr as $k => $v) {

			if (!fn_string_no_empty($v)) {

				unset($arr[$k]);

			}

		}

		if (sizeof($arr) == 2) {

			$_condition .= db_quote(" $union_condition ?:orders.firstname LIKE ?l AND ?:orders.lastname LIKE ?l", "%" . array_shift($arr) . "%", "%" . array_shift($arr) . "%");

		} else {

			$_condition .= db_quote(" $union_condition (?:orders.firstname LIKE ?l OR ?:orders.lastname LIKE ?l)", "%" . trim($params['cname']) . "%", "%" . trim($params['cname']) . "%");

		}

	}



	if (isset($params['company_id']) && $params['company_id'] != '') {

		$condition .= db_quote(' AND ?:orders.company_id = ?i ', $params['company_id']);

	}

	/* Add condition for payment method */
	if (!empty($params['payment_method'])) {

		$condition .= db_quote(" AND ?:orders.payment_id = ?i", $params['payment_method']);

	}
	/* COD or Prepaid or Both - HPRAHI */
	if (!empty($params['payment_method_ff'])) {
		if($params['payment_method_ff']=="Both"){
			
		}
		else if($params['payment_method_ff']=="COD"){
			$condition .= db_quote(" AND ?:orders.payment_id = 6");
		}
		else if($params['payment_method_ff']=="Prepaid"){
			$condition .= db_quote(" AND ?:orders.payment_id != 6");
		}

	}
	/* NSS - HPRAHI */
	if (!empty($params['nss'])) {
		$join .= " INNER JOIN clues_nss_order_carrier nss ON ?:orders.order_id = nss.order_id and carrier_id='". $params['nss'] ."'";
	}
	/* exception_cause - HPRAHI */
	if (!empty($params['exception_cause'])) {
		$strexccause = '';
		foreach($params['exception_cause'] as $exccause)
		{
			if($strexccause=='')
			{
				$strexccause = $exccause;
			}
			else
			{
				$strexccause = $strexccause . "," . $exccause;
			}
		}
		/*$join .= " INNER JOIN clues_exception_causes_order_rel cecor ON ?:orders.order_id = cecor.order_id and cecor.cause_id in (". $strexccause .") and cecor.latest='1' and (cecor.`type` = 'Tag' or cecor.order_status = ?:orders.status)";*/
		
		$join .= " INNER JOIN clues_exception_causes_order_rel cecor ON ?:orders.order_id = cecor.order_id and cecor.cause_id in (". $strexccause .") and cecor.latest='1'";
	}
	/* Promotion_ids - HPRAHI */
	
	if (!empty($params['promotion_ids'])) {
		$str_promo = "";
		foreach($params['promotion_ids'] as $spid)
		{
			if($str_promo != '')
			{
				$str_promo = $str_promo . " OR ";
			}
			//$str_promo = $str_promo . "find_in_set('".$spid."',`promotion_ids`)";
                         $str_promo = $str_promo . "`promotion_ids` LIKE '%".$spid."%'";
		}
		$condition .= db_quote(" AND (".$str_promo.")");
	}
	/*searchnotes HPRAHI*/

	if (!empty($params['searchnotes'])) {
		$params['searchnotes'] = str_replace("'","''",$params['searchnotes']);
		
		$condition .= db_quote(" AND (?:orders.details like '%". $params['searchnotes'] ."%' OR ?:orders.notes like '%". $params['searchnotes'] ."%')");

	}
	/*searchaddress HPRAHI*/

	if (!empty($params['searchaddress'])) {
		$params['searchaddress'] = str_replace("'","''",$params['searchaddress']);
		
		$condition .= db_quote(" AND (?:orders.s_address like '%". $params['searchaddress'] ."%' OR ?:orders.s_address like '%". $params['searchaddress'] ."%' OR ?:orders.s_city like '%". $params['searchaddress'] ."%' OR ?:orders.s_zipcode like '%". $params['searchaddress'] ."%')");

	}
	/*search phone ankur */
	if (!empty($params['searchphone'])) {
		
		$condition .= db_quote(" AND (?:orders.b_phone like '%". $params['searchphone'] ."%' OR ?:orders.s_phone like '%". $params['searchphone'] ."%')");

	}
	/* search by tracking number by ankur */
	if(!empty($params['search_trackno']))
	{
		$join .= " inner join cscart_shipment_items csi on csi.order_id=?:orders.order_id inner join cscart_shipments cs on cs.shipment_id=csi.shipment_id";
		$track=explode(',',$params['search_trackno']);
		$track_no='';
		for($i=0;$i<count($track);$i++)
		{
			if($i==0)
			$track_no.=trim($track[$i]);
			else
			$track_no.="','".trim($track[$i]);
		}
		$condition .= db_quote(" AND cs.tracking_number in ('".$track_no."')");
	}
	/* search by city by ankur */
	if(!empty($params['search_city']))
	{
		$city=explode(',',$params['search_city']);
		$city_cond='';
		for($i=0;$i<count($city);$i++)
		{
			if($params['search_city_type']=='S')
			{
				if($i==0)
				$city_cond.="?:orders.s_city like '%".trim($city[$i])."%'";
				else
				$city_cond.=" or ?:orders.s_city like '%".trim($city[$i])."%'";
			}
			else
			{
				if($i==0)
				$city_cond.="?:orders.b_city like '%".trim($city[$i])."%'";
				else
				$city_cond.=" or ?:orders.b_city like '%".trim($city[$i])."%'";
			}
			
		}
		$condition .= db_quote(" and (".$city_cond.")"); 
	}
	
	/*search gift certificates ankur */
	/*if (!empty($params['gift_cert_code']) && !empty($params['gift_cert_in'])) {
		
		$condition .= db_quote(" AND ?:cscart_gift_certificates.gift_cert_code='". $params['gift_cert_code']."'");
		if($params['gift_cert_in']=='B')
		{
			
		}

	}*/
	/* search gift wrap only by ankur */
	if(!empty($params['gift_wrap']))
	{
		$condition .= db_quote(" and ?:orders.gift_it='Y'");
	}
	/* code end */
	/*search has gift message only by ankur */
	if(!empty($params['has_gift_mesg']))
	{
		$join .="inner join clues_gift_message cgm on ?:orders.order_id=cgm.order_id and cgm.no_message='N'";
	}
	/* code end */
	/* qtymorethanlessthan - HPRAHI */
	if (!empty($params['qtymorethanlessthan'])) {
		$qty_ml = (int)$params['qtymorethanlessthan'];
		$gtelte = '';
		if($params['qtymorethanlessthanselect'] == "gte")
		{
			$gtelte = '>=';
		}
		else
		{
			$gtelte = '<=';
		}
		$join .= " INNER JOIN ?:order_details ordd ON ?:orders.order_id = ordd.order_id";
		
		$group = " group by ?:orders.order_id having sum(ordd.amount) ".$gtelte." " . $qty_ml;
	}
	/*fulfillment type HPRAHI*/

	if (!empty($params['fulfillment_id'])) {
		
		$condition .= db_quote(" AND ?:companies.fulfillment_id like '". $params['fulfillment_id'] ."'");

	}
	/*label_printed type HPRAHI*/

	if (!empty($params['label_printed'])) {
		$condition .= db_quote(" AND ?:orders.label_printed ='". $params['label_printed'] ."'");

	}
	
	/*label_printed type HPRAHI*/

	//if (!empty($params['billing_done'])) {
	if ($params['billing_done'] == 'Y') {
		$condition .= db_quote(" AND (?:orders.billing_done='Y' or ?:orders.reverse_billing_done='Y')");
	}
	if ($params['billing_done'] == 'N') {
		$condition .= db_quote(" AND (?:orders.billing_done!='Y' and ?:orders.reverse_billing_done!='Y')");
	}
	
	if (!empty($params['tax_exempt'])) {

		$condition .= db_quote(" AND ?:orders.tax_exempt = ?s", $params['tax_exempt']); 

	}
	
	
	if (isset($params['email']) && fn_string_no_empty($params['email'])) {

		$_condition .= db_quote(" $union_condition ?:orders.email LIKE ?l", "%" . trim($params['email']) . "%");

	}



	if (!empty($params['user_id'])){

		$condition .= db_quote(' AND ?:orders.user_id IN (?n)', $params['user_id']);

	}



	if (isset($params['total_from']) && fn_is_numeric($params['total_from'])) {

		$condition .= db_quote(" AND ?:orders.total >= ?d", fn_convert_price($params['total_from']));

	}



	if (!empty($params['total_to']) && fn_is_numeric($params['total_to'])) {

		$condition .= db_quote(" AND ?:orders.total <= ?d", fn_convert_price($params['total_to']));

	}



	if (!empty($params['status'])) {

		//$condition .= db_quote(' AND ?:orders.status IN (?a)', $params['status']); change by paresh on 06-02-2012

		$condition .= db_quote(' AND ?:orders.status IN (?a)', !is_array($params['status']) ? explode("','", $params['status']) : $params['status']);

	}



	if (empty($params['include_incompleted'])) {

		$condition .= db_quote(' AND ?:orders.status != ?s', STATUS_INCOMPLETED_ORDER);

	}

   //change by ankur to be able search if order ids are passed wih separated by space
	if (!empty($params['order_id'])) {
        if(strpos($params['order_id'], ',')!== false)
		{						  
			$_condition .= db_quote($union_condition . ' ?:orders.order_id IN (?n)', (!is_array($params['order_id']) && (strpos($params['order_id'], ',') !== false) ? explode(',', $params['order_id']) : $params['order_id']));
		}
		else
		{
			$order_arr=explode(' ', $params['order_id']);
			if(count($order_arr)>1)
			{
				$search_order_id='';
				for($i=0;$i<count($order_arr);$i++)
				{
					if(!empty($order_arr[$i]))
					$search_order_id.=trim($order_arr[$i]).',';
				}
				if(!empty($search_order_id))
				{
					$search_order_id=substr($search_order_id,0,-1);
				}
			}
			else
			{
				$search_order_id=$params['order_id'];
			}
			$_condition .= db_quote($union_condition . " ?:orders.order_id IN ($search_order_id)");
		}
	}
   //code end


	if (!empty($params['p_ids']) || !empty($params['product_view_id'])) {

		$arr = (strpos($params['p_ids'], ',') !== false || !is_array($params['p_ids'])) ? explode(',', $params['p_ids']) : $params['p_ids'];



		if (empty($params['product_view_id'])) {

			$condition .= db_quote(" AND ?:order_details.product_id IN (?n)", $arr);

		} else {

			$condition .= db_quote(" AND ?:order_details.product_id IN (?n)", db_get_fields(fn_get_products(array('view_id' => $params['product_view_id'], 'get_query' => true))));

		}
		if(!empty($params['itemids']))
		{
			
			$condition .= db_quote(" AND ?:order_details.item_id IN (".$params['itemids'].")");
		}



		$join .= " LEFT JOIN ?:order_details ON ?:order_details.order_id = ?:orders.order_id";
		if($group == ''){
		$group .=  " GROUP BY ?:orders.order_id ";
		}

	}



	/*if (!empty($params['admin_user_id'])) {

		$condition .= db_quote(" AND ?:new_orders.user_id = ?i", $params['admin_user_id']);

		$join .= " LEFT JOIN ?:new_orders ON ?:new_orders.order_id = ?:orders.order_id";

	}*/


	/*
	$docs_conditions = array();

	if (!empty($params['invoice_id']) || !empty($params['has_invoice'])) {

		if (!empty($params['has_invoice'])) {

			$docs_conditions[] = "invoice_docs.doc_id IS NOT NULL";

		} elseif (!empty($params['invoice_id'])) {

			$docs_conditions[] = db_quote("invoice_docs.doc_id = ?i", $params['invoice_id']);

		}

	}

	$join .= " LEFT JOIN ?:order_docs as invoice_docs ON invoice_docs.order_id = ?:orders.order_id AND invoice_docs.type = 'I'";



	if (!empty($params['credit_memo_id']) || !empty($params['has_credit_memo'])) {

		if (!empty($params['has_credit_memo'])) {

			$docs_conditions[] = "memo_docs.doc_id IS NOT NULL";

		} elseif (!empty($params['credit_memo_id'])) {

			$docs_conditions[] = db_quote("memo_docs.doc_id = ?i", $params['credit_memo_id']);

		}

	}

	$join .= " LEFT JOIN ?:order_docs as memo_docs ON memo_docs.order_id = ?:orders.order_id AND memo_docs.type = 'C'";



	if (!empty($docs_conditions)) {

		$condition .= ' AND (' . implode(' OR ', $docs_conditions) . ')';

	}
	*/


	if (!empty($params['shippings'])) {

		$set_conditions = array();

		foreach ($params['shippings'] as $v) {

			//$set_conditions[] = db_quote("FIND_IN_SET(?s, ?:orders.shipping_ids)", $v);
                        $set_conditions[] = db_quote("?:orders.shipping_ids LIKE '%$v%'");

		}

		$condition .= ' AND (' . implode(' OR ', $set_conditions) . ')';

	}



	if (!empty($params['period']) && $params['period'] != 'A') {

		list($params['time_from'], $params['time_to']) = fn_create_periods($params);



		$condition .= db_quote(" AND (?:orders.timestamp >= ?i AND ?:orders.timestamp <= ?i)", $params['time_from'], $params['time_to']);

	}

	

	if (!empty($params['custom_files']) && $params['custom_files'] == 'Y') {

		$condition .= db_quote(" AND ?:order_details.extra LIKE ?l", '%custom_files%');

		

		if (empty($params['p_ids']) && empty($params['product_view_id'])) {

			$join .= " LEFT JOIN ?:order_details ON ?:order_details.order_id = ?:orders.order_id";

		}

	}

	

	if (!empty($params['company_name']) || !empty($params['fulfillment_id']) || !empty($params['region_id']) || !empty($params['adminuser_id']) ) {

		$fields[] = '?:companies.company as company_name';

		$join .= " LEFT JOIN ?:companies ON ?:companies.company_id = ?:orders.company_id";

	}
	/* MRI Received - HPRAHI */
	
	if (!empty($params['mrireceivedpartialy'])) {
		$join .= " INNER JOIN clues_mri_receive_details cmrecd ON ?:orders.order_id = cmrecd.order_id and cmrecd.completed='N' and cmrecd.qty!='0'";
		if($group == ''){
		$group .=  " GROUP BY ?:orders.order_id ";
		}
	}
	else if (!empty($params['mrireceived'])) {
		$join .= " INNER JOIN clues_mri_receive_details cmrecd ON ?:orders.order_id = cmrecd.order_id and cmrecd.completed='Y'";
		if($group == ''){
		$group .=  " GROUP BY ?:orders.order_id ";
		}
	}
	/* Region_Id - HPRAHI */
	if (!empty($params['region_id'])) {
		$join .= " INNER JOIN clues_warehouse_contact cwc ON ?:companies.company_id = cwc.company_id and cwc.region_code='". $params['region_id'] ."'";
	}
	
	/* orderage - HPRAHI */
	if (!empty($params['orderage'])) {
		$dt_oa = date('Y-m-d');
		$dt_oa = strtotime($dt_oa)-(24*60*60*($params['orderage']-1));
		$join .= " INNER JOIN clues_order_history coh ON ?:orders.order_id = coh.order_id and ?:orders.status=coh.to_status and coh.transition_date<'".$dt_oa."'";
	}
	/* bd company rel filter - HPRAHI */
	if (!empty($params['adminuser_id'])) {
		$join .= " INNER JOIN clues_bd_merchant_rel cbmr ON ?:companies.company_id = cbmr.company_id and cbmr.user_id='".$params['adminuser_id']."'";
	}
	/* to order by product - HPRAHI*/
	if($params['sort_by']=='product'){
	$join .= " Left Join ?:order_details ordet on ordet.order_id=?:orders.order_id Left Join ?:product_descriptions prods on prods.product_id=ordet.product_id";
	}

	if (!empty($_condition)) {

		$condition .= ' AND (' . ($union_condition == ' OR ' ? '0 ' : '1 ') . $_condition . ')';

	}

	 //code by ankur to get allow cancellation field of order status
	 if(AREA=='C')
	 {
		 $fields[]="csd.value as allow_cancelation";
		 $join.="LEFT JOIN cscart_status_data csd on csd.status=?:orders.status and csd.type='O' and csd.param='allow_cancelation'";
	 }
	 //code end

	//fn_set_hook('get_orders', $params, $fields, $sortings, $condition, $join, $group);



	// Used for Extended search

	if (!empty($params['get_conditions'])) {

		return array($fields, $join, $condition);

	}



	$limit = '';

	if (!empty($items_per_page)) {
		if($group)
		{
			$total = db_get_field("select count(*) from (SELECT COUNT(DISTINCT (?:orders.order_id)) FROM ?:orders $join WHERE 1 $condition $group) a");
		}
		else
		{
			$total = db_get_field("SELECT COUNT(DISTINCT (?:orders.order_id)) FROM ?:orders $join WHERE 1 $condition");
		}
		//hprahi did it
		if(isset($params['ff']) && $params['ff']==1)
		{
			$arrlim = array(10,20,50,100,200,300,400,500,1000);
		}
		else
		{
			$arrlim = false;
		}
		//hprahi did it[closed]
		$limit = fn_paginate($params['page'], $total, $items_per_page,false,$arrlim);

	}
	if(isset($params['limitqty']) and $params['limitqty']!=''){
		$limit = "LIMIT 0," . $params['limitqty'];
	}
	//echo db_process('SELECT ' . implode(', ', $fields) . " FROM ?:orders $join WHERE 1 $condition $group ORDER BY $sorting $limit");
	//exit;
	if(isset($_REQUEST['first'])){
		$orders = array();
	} else {
		$orders = db_get_array('SELECT ' . implode(', ', $fields) . " FROM ?:orders $join WHERE 1 $condition $group ORDER BY $sorting $limit");
	}


	if (!empty($params['check_for_suppliers'])) {

		if (Registry::get('settings.Suppliers.enable_suppliers') == 'Y') {

			foreach ($orders as &$order) {

				$order['items'] = db_get_hash_array("SELECT ?:order_details.* FROM ?:order_details WHERE ?:order_details.order_id = ?i", 'item_id', $order['order_id']);

				foreach ($order['items'] as $k => &$v) {

					$v = @unserialize($v['extra']);

					$v['company_id'] = empty($v['company_id']) ? 0 : $v['company_id'];

				}

				$order['companies'] = fn_get_products_companies($order['items']);

				$order['have_suppliers'] = fn_check_companies_have_suppliers($order['companies']);

			}

		} elseif (PRODUCT_TYPE == 'MULTIVENDOR') {

			foreach ($orders as &$order) {

				$order['have_suppliers'] = empty($order['company_id'])? 'N' : 'Y';

			}

		}

	}



	if ($get_totals == true) {

		$paid_statuses = array('P', 'C');

		fn_set_hook('get_orders_totals', $paid_statuses, $join, $condition, $group);
       
		$totals = array (

			'gross_total' => db_get_field("SELECT sum(t.total) FROM ( SELECT total FROM ?:orders $join WHERE 1 $condition $group) as t"),

			'totally_paid' => db_get_field("SELECT sum(t.total) FROM ( SELECT total FROM ?:orders $join WHERE ?:orders.status IN (?a) $condition $group) as t", $paid_statuses),
			
			'gmv_total' =>db_get_field("select sum(?:orders.subtotal+?:orders.discount+?:orders.shipping_cost+?:orders.emi_fee+?:orders.gifting_charge) FROM ?:orders $join WHERE 1 $condition $group"),

		);

	}



	fn_view_process_results('orders', $orders, $params, $items_per_page);



	return array($orders, $params, ($get_totals == true ? $totals : array()));

}
function fn_get_cancel_info($user_id,$order_id)
{
	$sql="select ocr.reason,cc.comment from clues_customer_cancellation cc
	inner join clues_order_cancellation_reason ocr on ocr.reason_id=cc.reason_id
	where cc.user_id='".$user_id."' and cc.order_id='".$order_id."'
	";
	return db_get_row($sql);
}
?>
