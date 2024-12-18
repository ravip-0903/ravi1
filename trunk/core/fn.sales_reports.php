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


//
// $Id: fn.sales_reports.php 12865 2011-07-05 06:57:22Z 2tl $
//
if ( !defined('AREA') ) { die('Access denied'); }

//
// This function get the reports data to display, it's also generate time intevals if any specified
//
function fn_get_order_reports($view = false, $report_id = 0)
{
	$status = (empty($view)) ? "" : "AND status = 'A'";

	$data = db_get_hash_array("SELECT a.*, b.description FROM ?:sales_reports as a LEFT JOIN ?:sales_reports_descriptions as b ON a.report_id = b.report_id AND lang_code = ?s WHERE type = 'O' $status ORDER BY position", 'report_id', CART_LANGUAGE);

	if (empty($data)) {
		return array();
	}

	// If we manage reports we need only it's name
	if (empty($view)) {
		return $data;
	}

	$k = $report_id;
	list($data[$k]['time_from'], $data[$k]['time_to']) = fn_create_periods($data[$k]);

	if($_REQUEST['first']){
		$data[$k]['time_from'] = strtotime(date('Y-m-d'));
		$data[$k]['time_to'] = time();
		$data[$k]['period'] = 'D';
	}
	$order = 'desc';
// Orders by Merchnat Report
	if($report_id == '7'){

	     if(isset($_REQUEST['order'])&& $_REQUEST['order'] != ''){
		$order = ($_REQUEST['order'] == 'desc')?'asc':'desc';
	     }
	     if(isset($_REQUEST['field'])&& $_REQUEST['field'] == 'cmp'){
		$orderby = 'c.company';
	     }elseif(isset($_REQUEST['field'])&& $_REQUEST['field'] == 'gsp'){
		$orderby = 'sum(od.price * od.amount)';
	     }elseif(isset($_REQUEST['field'])&& $_REQUEST['field'] == 'qty'){
		$orderby = 'sum(od.amount)';
	     }elseif(isset($_REQUEST['field'])&& $_REQUEST['field'] == 'asp'){
		$orderby = 'sum(od.price * od.amount)/sum(od.amount)';
	     } else {
		$orderby = 'sum(od.amount)';
	     }
	if(ACCOUNT_TYPE == 'vendor'){
		$data[$k]['tables'] = db_get_array("select c.company, sum(od.price * od.amount) as tot_amt, sum(od.amount) as tot_order, sum(od.price * od.amount)/ sum(od.amount) as asp, o.timestamp from cscart_order_details od, cscart_orders o, cscart_companies c where od.order_id = o.order_id and o.company_id = c.company_id and o.status not in ('F','I','N','D') and o.is_parent_order = 'N' and c.company_id= '".$_SESSION['auth']['company_id']."' and o.timestamp BETWEEN ".($data[$k]['time_from'] + Registry::get('config.timestamp'))." AND ".($data[$k]['time_to'] + Registry::get('config.timestamp'))." group by c.company order by ".$orderby." ".$order."");
	}else{
		$data[$k]['tables'] = db_get_array("select c.company, sum(od.price * od.amount) as tot_amt, sum(od.amount) as tot_order, sum(od.price * od.amount)/ sum(od.amount) as asp, o.timestamp from cscart_order_details od, cscart_orders o, cscart_companies c where od.order_id = o.order_id and o.company_id = c.company_id and o.status not in ('F','I','N','D') and o.is_parent_order = 'N' and o.timestamp BETWEEN ".($data[$k]['time_from'] + Registry::get('config.timestamp'))." AND ".($data[$k]['time_to'] + Registry::get('config.timestamp'))." group by c.company order by ".$orderby." ".$order."");
	}
	} elseif($report_id == '8'){
	     if(isset($_REQUEST['order']) && $_REQUEST['order'] != ''){
		$order = ($_REQUEST['order'] == 'desc')?'asc':'desc';
	     }
	     if(isset($_REQUEST['field']) && $_REQUEST['field'] == 'cmp'){
		$orderby = '1,2';
	     }elseif(isset($_REQUEST['field']) && $_REQUEST['field'] == 'gsp'){
		$orderby = '3';
	     }elseif(isset($_REQUEST['field']) && $_REQUEST['field'] == 'qty'){
		$orderby = '4';
	     }elseif(isset($_REQUEST['field']) && $_REQUEST['field'] == 'asp'){
		$orderby = '5';
	     } else {
		$orderby = '4';
	     }
		if(ACCOUNT_TYPE == 'admin'){

	//$data[$k]['tables'] = db_get_array("select cd1.category as cd1c, cd2.category as cd2c, sum(od.price * od.amount) as tot_amt, count(distinct o.order_id) as tot_order, sum(od.price * od.amount)/count(distinct o.order_id) as asp from cscart_categories c, cscart_products_categories pc, cscart_order_details od, cscart_orders o, cscart_category_descriptions cd1, cscart_category_descriptions cd2 where c.category_id = pc.category_id and o.timestamp BETWEEN ".($data[$k]['time_from'] + Registry::get('config.timestamp'))." AND ".($data[$k]['time_to'] + Registry::get('config.timestamp'))." and pc.product_id = od.product_id and od.order_id = o.order_id and o.status not in ('F','I','N','D') and o.is_parent_order = 'N' and cd1.category_id = SUBSTRING_INDEX( c.id_path,  '/', 1 ) and cd2.category_id = right (SUBSTRING_INDEX( c.id_path,  '/', 2 ), INSTR( SUBSTRING_INDEX( c.id_path,  '/', 2 ),  '/')-1) group by cd1.category, cd2.category union select cd1.category, null, sum(od.price * od.amount), count(distinct o.order_id), sum(od.price * od.amount)/count(distinct o.order_id) as asp from cscart_categories c, cscart_products_categories pc, cscart_order_details od, cscart_orders o, cscart_category_descriptions cd1 where c.category_id = pc.category_id and o.timestamp BETWEEN ".($data[$k]['time_from'] + Registry::get('config.timestamp'))." AND ".($data[$k]['time_to'] + Registry::get('config.timestamp'))." and pc.product_id = od.product_id and od.order_id = o.order_id and o.status not in ('F','I','N','D') and o.is_parent_order = 'N' and cd1.category_id = SUBSTRING_INDEX( c.id_path,  '/', 1 ) and INSTR( SUBSTRING_INDEX( c.id_path,  '/', 2 ),  '/') = 0 group by cd1.category order by ".$orderby." ".$order."");
	//$data[$k]['tables'] = db_get_array("select cd1.category as cd1c, cd2.category as cd2c, sum(od.price * od.amount) as tot_amt, count(distinct o.order_id) as tot_order, sum(od.price * od.amount)/count(distinct o.order_id) as asp from cscart_categories c, cscart_products_categories pc, cscart_order_details od, cscart_orders o, cscart_category_descriptions cd1, cscart_category_descriptions cd2 where c.category_id = pc.category_id and o.timestamp BETWEEN 0 AND 1339825966 and pc.product_id = od.product_id and od.order_id = o.order_id and o.status not in ('F','I','N','D') and o.is_parent_order = 'N' and cd1.category_id = substring_index(substring_index(c.id_path,'/',-4),'/',1) and cd1.category_id = substring_index(substring_index(c.id_path,'/',-3),'/',1) and cd1.category_id = substring_index(substring_index(c.id_path,'/',-2),'/',1) and cd1.category_id = substring_index(substring_index(c.id_path,'/',-1),'/',1)  and cd2.category_id = right (SUBSTRING_INDEX( c.id_path, '/', 2 ), INSTR( SUBSTRING_INDEX( c.id_path, '/', 2 ), '/')-1) group by cd1.category, cd2.category";		

	$data[$k]['tables'] = db_get_array("select cd1.category as cd1c, o.order_id, sum(od.price * od.amount) as tot_amt, sum(od.amount) as tot_order, sum(od.price * od.amount)/ sum(od.amount) as asp from cscart_products_categories pc, cscart_order_details od, cscart_orders o, cscart_category_descriptions cd1 where pc.product_id = od.product_id and od.order_id = o.order_id and o.status not in ('F','I','N','D') and o.is_parent_order = 'N' and cd1.category_id = pc.category_id and pc.link_type='M' and o.timestamp BETWEEN ".($data[$k]['time_from'] + Registry::get('config.timestamp'))." AND ".($data[$k]['time_to'] + Registry::get('config.timestamp'))." group by cd1.category_id");
                }
	} elseif($report_id == '9'){ // Orders By State
	     if(isset($_REQUEST['order']) && $_REQUEST['order'] != ''){
		$order = ($_REQUEST['order'] == 'desc')?'asc':'desc';
	     }
	     if(isset($_REQUEST['field']) && $_REQUEST['field'] == 'cmp'){
		$orderby = 's_state';
	     }elseif(isset($_REQUEST['field']) && $_REQUEST['field'] == 'gsp'){
		$orderby = 'sum(od.price * od.amount)';
	     }elseif(isset($_REQUEST['field']) && $_REQUEST['field'] == 'qty'){
		$orderby = 'sum(od.amount)';
	     }elseif(isset($_REQUEST['field']) && $_REQUEST['field'] == 'asp'){
		$orderby = 'sum(od.price * od.amount)/sum(od.amount)';
	     }else{
		$orderby = 'sum(od.amount)';
	     }
		if(ACCOUNT_TYPE == 'vendor'){
			$data[$k]['tables'] = db_get_array("select s_state, sum(od.price * od.amount) as tot_amt, sum(od.amount) as tot_order, sum(od.price * od.amount)/ sum(od.amount) as asp from cscart_order_details od, cscart_orders o where od.order_id = o.order_id and o.status not in ('F','I','N','D') and o.is_parent_order = 'N' and o.company_id != 0 and o.company_id='".$_SESSION['auth']['company_id']."' and o.timestamp BETWEEN ".($data[$k]['time_from'] + Registry::get('config.timestamp'))." AND ".($data[$k]['time_to'] + Registry::get('config.timestamp'))." group by s_state order by ".$orderby." ".$order);
		} else {
			$data[$k]['tables'] = db_get_array("select s_state, sum(od.price * od.amount) as tot_amt, sum(od.amount) as tot_order, sum(od.price * od.amount)/ sum(od.amount) as asp from cscart_order_details od, cscart_orders o where od.order_id = o.order_id and o.status not in ('F','I','N','D') and o.is_parent_order = 'N' and o.company_id != 0 and o.timestamp BETWEEN ".($data[$k]['time_from'] + Registry::get('config.timestamp'))." AND ".($data[$k]['time_to'] + Registry::get('config.timestamp'))." group by s_state order by ".$orderby." ".$order);
		}
	} elseif($report_id == '10'){ // Orders By City
	     if(isset($_REQUEST['order']) && $_REQUEST['order'] != ''){
		$order = ($_REQUEST['order'] == 'desc')?'asc':'desc';
	     }
	     if(isset($_REQUEST['field']) && $_REQUEST['field'] == 'city'){
		$orderby = 's_city';
	     }elseif(isset($_REQUEST['field']) && $_REQUEST['field'] == 'state'){
		$orderby = 's_state';
	     }elseif(isset($_REQUEST['field']) && $_REQUEST['field'] == 'gsp'){
		$orderby = 'sum(od.price * od.amount)';
	     }elseif(isset($_REQUEST['field']) && $_REQUEST['field'] == 'qty'){
		$orderby = 'sum(od.amount)';
	     }elseif(isset($_REQUEST['field']) && $_REQUEST['field'] == 'asp'){
		$orderby = 'sum(od.price * od.amount)/sum(od.amount)';
	     }else{
		$orderby = 'sum(od.amount)';
	     }
		if(ACCOUNT_TYPE == 'vendor'){
			$data[$k]['tables'] = db_get_array("select s_city, s_state, sum(od.price * od.amount) as tot_amt, sum(od.amount) as tot_order, sum(od.price * od.amount)/ sum(od.amount) as asp from cscart_order_details od, cscart_orders o where od.order_id = o.order_id and o.status not in ('F','I','N','D') and o.is_parent_order = 'N' and o.company_id != 0 and o.company_id='".$_SESSION['auth']['company_id']."' and o.timestamp BETWEEN ".($data[$k]['time_from'] + Registry::get('config.timestamp'))." AND ".($data[$k]['time_to'] + Registry::get('config.timestamp'))." group by s_city, s_state order by ".$orderby." ".$order);
		}else{
			$data[$k]['tables'] = db_get_array("select s_city, s_state, sum(od.price * od.amount) as tot_amt, sum(od.amount) as tot_order, sum(od.price * od.amount)/sum(od.amount) as asp from cscart_order_details od, cscart_orders o where od.order_id = o.order_id and o.status not in ('F','I','N','D') and o.is_parent_order = 'N' and o.company_id != 0 and o.timestamp BETWEEN ".($data[$k]['time_from'] + Registry::get('config.timestamp'))." AND ".($data[$k]['time_to'] + Registry::get('config.timestamp'))." group by s_city, s_state order by ".$orderby." ".$order);
		}
	} elseif($report_id == '11'){ // Orders By Payment
	     if(isset($_REQUEST['order']) && $_REQUEST['order'] != ''){
		$order = ($_REQUEST['order'] == 'desc')?'asc':'desc';
	     }
	     if(isset($_REQUEST['field']) && $_REQUEST['field'] == 'cmp'){
		$orderby = "1";
	     }elseif(isset($_REQUEST['field']) && $_REQUEST['field'] == 'gsp'){
		$orderby = '2';
	     }elseif(isset($_REQUEST['field']) && $_REQUEST['field'] == 'qty'){
		$orderby = '3';
	     }elseif(isset($_REQUEST['field']) && $_REQUEST['field'] == 'asp'){
		$orderby = '4';
	     }else{
		$orderby = '3';
	     }
		if(ACCOUNT_TYPE == 'vendor'){
			$data[$k]['tables'] = db_get_array("select IF(pd.payment_id in (12,14), 'Prepaid',pd.payment) as payment, sum(od.price * od.amount) as tot_amt, sum(od.amount) as tot_order, sum(od.price * od.amount)/sum(od.amount) as asp from cscart_order_details od, cscart_orders o, cscart_payment_descriptions pd where od.order_id = o.order_id and o.status not in ('F','I','N','D') and o.company_id != 0 and pd.payment_id = o.payment_id and o.company_id='".$_SESSION['auth']['company_id']."' and o.timestamp BETWEEN ".($data[$k]['time_from'] + Registry::get('config.timestamp'))." AND ".($data[$k]['time_to'] + Registry::get('config.timestamp'))." and o.is_parent_order = 'N' group by IF(pd.payment_id in (12,14), 'Prepaid',pd.payment) UNION select 'Clues Bucks' as payment, sum(od.price * od.amount) as tot_amt, sum(od.amount) as tot_order, sum(od.price * od.amount)/sum(od.amount) as asp from cscart_order_details od, cscart_orders o where od.order_id = o.order_id and o.status not in ('F','I','N','D') and o.payment_id = 0 and o.company_id != 0 and o.company_id='".$_SESSION['auth']['company_id']."' and o.timestamp BETWEEN ".($data[$k]['time_from'] + Registry::get('config.timestamp'))." AND ".($data[$k]['time_to'] + Registry::get('config.timestamp'))." and o.is_parent_order = 'N' order by ".$orderby." ".$order."");
		} else {
			$data[$k]['tables'] = db_get_array("select IF(pd.payment_id in (12,14), 'Prepaid',pd.payment) as payment, sum(od.price * od.amount) as tot_amt, sum(od.amount) as tot_order, sum(od.price * od.amount)/sum(od.amount) as asp from cscart_order_details od, cscart_orders o, cscart_payment_descriptions pd where od.order_id = o.order_id and o.status not in ('F','I','N','D') and o.company_id != 0 and pd.payment_id = o.payment_id and o.timestamp BETWEEN ".($data[$k]['time_from'] + Registry::get('config.timestamp'))." AND ".($data[$k]['time_to'] + Registry::get('config.timestamp'))." and o.is_parent_order = 'N' group by IF(pd.payment_id in (12,14), 'Prepaid',pd.payment) UNION select 'Clues Bucks' as payment, sum(od.price * od.amount) as tot_amt, sum(od.amount) as tot_order, sum(od.price * od.amount)/sum(od.amount) as asp from cscart_order_details od, cscart_orders o where od.order_id = o.order_id and o.status not in ('F','I','N','D') and o.payment_id = 0 and o.company_id != 0 and o.is_parent_order = 'N' and o.timestamp BETWEEN ".($data[$k]['time_from'] + Registry::get('config.timestamp'))." AND ".($data[$k]['time_to'] + Registry::get('config.timestamp'))." order by ".$orderby." ".$order."");
		}
	}elseif($report_id == '12'){ // Orders By Product
	     if(isset($_REQUEST['order']) && $_REQUEST['order'] != ''){
		$order = ($_REQUEST['order'] == 'desc')?'asc':'desc';
	     }
	     if(isset($_REQUEST['field']) && $_REQUEST['field'] == 'prd'){
		$orderby = "product";
	     }elseif(isset($_REQUEST['field']) && $_REQUEST['field'] == 'gsp'){
		$orderby = 'sum(amount * price)';
	     }elseif(isset($_REQUEST['field']) && $_REQUEST['field'] == 'qty'){
		$orderby = ' sum(amount) ';
	     }elseif(isset($_REQUEST['field']) && $_REQUEST['field'] == 'asp'){
		$orderby = 'sum(amount * price)/sum(amount)';
	     }else{
		$orderby = 'sum(amount * price)';
	     }

		if(ACCOUNT_TYPE == 'vendor'){
			$data[$k]['tables'] = db_get_array("select product, sum(amount) as tot_order, sum(amount * price) as tot_amt, sum(amount * price)/sum(amount) as asp from cscart_order_details od, cscart_orders o, cscart_product_descriptions pd where od.order_id = o.order_id and o.is_parent_order = 'N' and o.status not in ('F','I','N','D') and od.product_id = pd.product_id and o.company_id != 0 and o.company_id='".$_SESSION['auth']['company_id']."' and o.timestamp BETWEEN ".($data[$k]['time_from'] + Registry::get('config.timestamp'))." AND ".($data[$k]['time_to'] + Registry::get('config.timestamp'))." group by product order by ".$orderby." ".$order);
		} else {
			//$data[$k]['tables'] = db_get_array("select product, sum(od.amount) as tot_order, sum(amount * price) as tot_amt, sum(amount * price)/sum(amount) as asp from cscart_order_details od, cscart_orders o, cscart_product_descriptions pd where od.order_id = o.order_id and o.is_parent_order = 'N' and o.status not in ('F','I','N','D') and od.product_id = pd.product_id and o.company_id != 0 and o.timestamp BETWEEN ".($data[$k]['time_from'] + Registry::get('config.timestamp'))." AND ".($data[$k]['time_to'] + Registry::get('config.timestamp'))." group by product order by ".$orderby." ".$order);

	$data[$k]['tables'] = db_get_array("select product, sum(od.amount) as tot_order, sum(amount * price) as tot_amt, sum(amount * price)/sum(amount) as asp from  (cscart_order_details od LEFT JOIN cscart_orders o on od.order_id = o.order_id) LEFT OUTER JOIN cscart_product_descriptions pd ON od.product_id = pd.product_id where o.is_parent_order = 'N' and o.status not in ('F','I','N','D') and o.company_id != 0  and o.timestamp BETWEEN ".($data[$k]['time_from'] + Registry::get('config.timestamp'))." AND ".($data[$k]['time_to'] + Registry::get('config.timestamp'))." group by product order by ".$orderby." ".$order);
		}
	}elseif($report_id == '13'){ // Orders By Date
	     if(isset($_REQUEST['order']) && $_REQUEST['order'] != ''){
		$order = ($_REQUEST['order'] == 'desc')?'asc':'desc';
	     }
	     if(isset($_REQUEST['field']) && $_REQUEST['field'] == 'date'){
		$orderby = "ss.date";
	     }elseif( isset($_REQUEST['field']) && $_REQUEST['field'] == 'visitors'){
		$orderby = 'visitors';
	     }elseif(isset($_REQUEST['field']) && $_REQUEST['field'] == 'qty'){
		$orderby = 'product_count';
	     }elseif(isset($_REQUEST['field']) && $_REQUEST['field'] == 'gmv'){
		$orderby = 'GMV';
	     }elseif(isset($_REQUEST['field']) && $_REQUEST['field'] == 'asp'){
		$orderby = 'gmv/product_count';
	     }elseif(isset($_REQUEST['field']) && $_REQUEST['field'] == 'conversion'){
		$orderby = '(product_count/visitors) * 100';
	     }else{
		$orderby = 'product_count';
	     }
		if(ACCOUNT_TYPE == 'vendor'){
			$data[$k]['tables']=db_get_array("SELECT ss.date, visitors, product_count, GMV, gmv/product_count as ASP, (product_count/visitors) * 100 as conversion FROM (select DATE_FORMAT(FROM_UNIXTIME(timestamp+ " .Registry::get('config.timestamp') ."), '%Y-%m-%d') as date, COUNT(*) as visitors from cscart_stat_sessions WHERE timestamp BETWEEN ".($data[$k]['time_from'] + Registry::get('config.timestamp'))." AND ".($data[$k]['time_to'] + Registry::get('config.timestamp'))." AND client_type = 'U' group by date) as ss, (select DATE_FORMAT(FROM_UNIXTIME(timestamp + ".Registry::get('config.timestamp')."), '%Y-%m-%d') as date, sum(od.amount) as product_count, sum(amount * price) as gmv from cscart_orders o, cscart_order_details od where timestamp BETWEEN ".($data[$k]['time_from'] + Registry::get('config.timestamp'))." AND ".($data[$k]['time_to'] + Registry::get('config.timestamp'))." and status not in ('F','I','N','D') and is_parent_order = 'N' and o.company_id != 0 and od.order_id = o.order_id and o.company_id='".$_SESSION['auth']['company_id']."' group by date) as o where o.date = ss.date order by ".$orderby." ".$order);
		} else {
			$data[$k]['tables']=db_get_array("SELECT ss.date, visitors, product_count, GMV, gmv/product_count as ASP, (product_count/visitors) * 100 as conversion FROM (select DATE_FORMAT(FROM_UNIXTIME(timestamp+" .Registry::get('config.timestamp') ."), '%Y-%m-%d') as date, COUNT(*) as visitors from cscart_stat_sessions WHERE timestamp BETWEEN ".($data[$k]['time_from'] + Registry::get('config.timestamp'))." AND ".($data[$k]['time_to'] + Registry::get('config.timestamp'))." AND client_type = 'U' group by date) as ss, (select DATE_FORMAT(FROM_UNIXTIME(timestamp + ".Registry::get('config.timestamp')."), '%Y-%m-%d') as date, sum(od.amount) as product_count, sum(amount * price) as gmv from cscart_orders o, cscart_order_details od where timestamp BETWEEN ".($data[$k]['time_from'] + Registry::get('config.timestamp'))." AND ".($data[$k]['time_to'] + Registry::get('config.timestamp'))." and status not in ('F','I','N','D') and is_parent_order = 'N' and o.company_id != 0 and od.order_id = o.order_id group by date) as o where o.date = ss.date order by ".$orderby." ".$order);
		}
	}elseif($report_id == '14'){
		if(ACCOUNT_TYPE == 'admin'){
//			$data[$k]['tables']=db_get_array("SELECT  o.order_id, sd.description, t.flag, t.previousflag, 'CCAVENUE' FROM clues_prepayment_details_track t, clues_prepayment_details p, cscart_orders o, cscart_status_descriptions sd where t.prepayment_id = p.id and order_id_not_tracked is null and p.order_id = o.order_id and o.status = sd.status and sd.type = 'O' and t.flag = 'Y' and o.status in ('F','N','D') union SELECT  o.order_id, sd.description, t.flag, t.previousflag, 'CCAVENUE' FROM clues_prepayment_details_track t,  cscart_orders o, cscart_status_descriptions sd where t.prepayment_id = 0 and t.order_id_not_tracked = o.order_id and o.status = sd.status and sd.type = 'O' and t.flag = 'Y' and o.status in ('F','N','D') union SELECT  o.order_id, sd.description,  p.flag, '', 'CCAVENUE' FROM clues_prepayment_details p, cscart_orders o, cscart_status_descriptions sd where p.order_id = o.order_id and o.status = sd.status and sd.type = 'O' and o.status in ('F','N','D') and p.flag = 'Y' union SELECT  o.order_id, concat(sd.description,' (PARENT: ',o.parent_order_id,')'),  p.flag, '', 'CCAVENUE' FROM clues_prepayment_details p, cscart_orders o, cscart_status_descriptions sd where p.order_id = o.parent_order_id and o.status = sd.status and sd.type = 'O' and o.status in ('F','N','D') and p.flag = 'Y' UNION SELECT  o.order_id, concat(sd.description,' (PARENT: ',o.parent_order_id,')'),  t.flag, '', 'CCAVENUE' FROM clues_prepayment_details_track t, cscart_orders o, cscart_status_descriptions sd where t.order_id_not_tracked = o.parent_order_id and o.status = sd.status and sd.type = 'O' and o.status in ('F','N','D') and t.flag = 'Y'");

	$table1=db_get_array("SELECT o.order_id, sd.description, t.flag, '', 'CCAVENUE' FROM clues_prepayment_details_track t, clues_prepayment_details p, cscart_orders o, cscart_status_descriptions sd where t.prepayment_id = p.id and order_id_not_tracked is null and p.order_id = o.order_id and o.status = sd.status and sd.type = 'O' and t.flag = 'Y' and o.status in ('F','N','D') ");

	$table2=db_get_array("SELECT o.order_id, sd.description, t.flag, '', 'CCAVENUE' FROM clues_prepayment_details_track t, cscart_orders o, cscart_status_descriptions sd where t.prepayment_id = 0 and t.order_id_not_tracked = o.order_id and o.status = sd.status and sd.type = 'O' and t.flag = 'Y' and o.status in ('F','N','D') ");

	$table3=db_get_array(" SELECT o.order_id, sd.description, p.flag, '', 'CCAVENUE' FROM clues_prepayment_details p, cscart_orders o, cscart_status_descriptions sd where p.order_id = o.order_id and o.status = sd.status and sd.type = 'O' and o.status in ('F','N','D') and p.flag = 'Y'");

	$table4=db_get_array("SELECT o.order_id, concat(sd.description,' (PARENT: ',o.parent_order_id,')') as description, p.flag, '', 'CCAVENUE' FROM clues_prepayment_details p, cscart_orders o USE INDEX(par_stat_idx), cscart_status_descriptions sd where p.order_id = o.parent_order_id and o.status = sd.status and sd.type = 'O' and o.status in ('F','N','D') and p.flag = 'Y' ");

	$table5=db_get_array("SELECT o.order_id, concat(sd.description,' (PARENT: ',o.parent_order_id,')') as description, t.flag, '', 'CCAVENUE' FROM clues_prepayment_details_track t, cscart_orders o, cscart_status_descriptions sd where t.order_id_not_tracked = o.parent_order_id and o.status = sd.status and sd.type = 'O' and o.status in ('F','N','D') and t.flag = 'Y'");

			$pending = array_merge($table1, $table2, $table3, $table4, $table5);

			$result = array();
			foreach ($pending as $value) {
				$result[$value['order_id']] = $value;
			}
			$res = array();
			foreach ($result as $val) {
				$res[] = $val;
			}
			$data[$k]['tables'] = $res;
		}
	} elseif ($report_id == '15'){ // Promotions Report
	     if(isset($_REQUEST['order']) && $_REQUEST['order'] != ''){
		$order = ($_REQUEST['order'] == 'desc')?'asc':'desc';
	     }
	     if(isset($_REQUEST['field']) && $_REQUEST['field'] == 'name'){
		$orderby = 'name';
	     }elseif(isset($_REQUEST['field']) && $_REQUEST['field'] == 'qty'){
		$orderby = 'SUM( od.amount )';
	     }elseif(isset($_REQUEST['field']) && $_REQUEST['field'] == 'gmv'){
		$orderby = ' SUM( od.price * od.amount )';
	     }elseif(isset($_REQUEST['field']) && $_REQUEST['field'] == 'asp'){
		$orderby = 'SUM( od.price * od.amount )/ SUM( od.amount )';
	     }else{
		$orderby = 'SUM( od.amount )';
	     }
		if(ACCOUNT_TYPE == 'admin'){
			$data[$k]['tables']=db_get_array("SELECT name, internal_name, SUM( od.amount ) AS tot_orders, SUM( od.price * od.amount ) AS tot_amount, SUM( od.price * od.amount )/ SUM( od.amount ) as ASP FROM cscart_orders o, cscart_order_details od, cscart_promotions p, cscart_promotion_descriptions pd WHERE o.order_id = od.order_id AND o.promotion_ids = p.promotion_id AND pd.promotion_id = p.promotion_id AND o.status NOT IN ('F',  'I',  'N',  'D') AND o.is_parent_order =  'N' and o.timestamp BETWEEN ".($data[$k]['time_from'] + Registry::get('config.timestamp'))." AND ".($data[$k]['time_to'] + Registry::get('config.timestamp'))." GROUP BY name ORDER BY ".$orderby." ".$order);
		}
	} elseif ($report_id == '17'){
             if(isset($_REQUEST['order']) && $_REQUEST['order'] != ''){
		$order = ($_REQUEST['order'] == 'desc')?'asc':'desc';
	     }
            if(isset($_REQUEST['field']) && $_REQUEST['field']=='amount'){
                $orderby = "cb.amount";
            }elseif(isset($_REQUEST['field']) && $_REQUEST['field']=='subtotal'){
                $orderby = "co.subtotal";
            }elseif(isset($_REQUEST['field']) && $_REQUEST['field']=='unitsold'){
                $orderby= "cd.amount";
            }elseif(isset($_REQUEST['field']) && $_REQUEST['field']=='orderid'){
                $orderby="co.order_id";
            }else{
                $orderby="co.order_id";
            } 
            
             	if(ACCOUNT_TYPE == 'admin'){
                	$data[$k]['tables']=db_get_array("SELECT co.order_id,co.firstname,co.lastname,co.subtotal,sum(cb.amount) as amount,cd.amount as quantity 
                                                            FROM clues_bucks_order_relation cb, cscart_orders co, cscart_order_details cd where cb.order_id = co.order_id 
                                                              AND cb.order_id = cd.order_id AND co.status not in ('F','I','N','D') and co.is_parent_order = 'N' AND co.timestamp BETWEEN ".($data[$k]['time_from'] + Registry::get('config.timestamp')).
                                                               " AND ".($data[$k]['time_to'] + Registry::get('config.timestamp'))." "."GROUP BY co.order_id,co.firstname,co.lastname,co.subtotal ORDER BY"." ".$orderby." ".$order."");
	     
                        $data[$k]['tab']=db_get_array("SELECT  co.subtotal,cd.amount as quantity
                                                            FROM  cscart_orders co, cscart_order_details cd  where co.order_id = cd.order_id 
                                                              AND co.status not in ('F','I','N','D') and co.is_parent_order = 'N' AND co.timestamp BETWEEN ".($data[$k]['time_from'] + Registry::get('config.timestamp')).
                                                               " AND ".($data[$k]['time_to'] + Registry::get('config.timestamp'))."");
             }
         } elseif ($report_id == '16'){  // Promotions Report
	     if($_REQUEST['order'] != ''){
		$order = ($_REQUEST['order'] == 'desc')?'asc':'desc';
	     }
	     if($_REQUEST['field'] == 'name'){
			$orderby = " ORDER BY  1 ";
	     }elseif($_REQUEST['field'] == 'count'){
			$orderby = ' ORDER BY 2 ';
	     }else{
			$orderby = "  ";
			$order = "  ";
	     }
		if(ACCOUNT_TYPE == 'admin'){

		$data[$k]['tables']=db_get_array("SELECT type as merchant_details, DATE_FORMAT(date_created,'%Y-%m-%d') as created,value as total_count FROM clues_merchant_stats WHERE UNIX_TIMESTAMP(DATE_FORMAT(date_created,'%Y-%m-%d')) BETWEEN ".($data[$k]['time_from'] + Registry::get('config.timestamp'))." AND ".($data[$k]['time_to'] + Registry::get('config.timestamp')));

//			$data[$k]['tables']=db_get_array("SELECT concat('Merchant with status: ',status, ' (CURRENT)') as merchant_details, count(*) as total_count FROM cscart_companies group by status union Select concat('SKU STATUS: ',status, ' (CURRENT)'), count(*) from cscart_products group by status union select 'SKUs OUT OF STOCK (CURRENT)', count(*) from cscart_products where amount <=0 union select 'Number of Merchant Signups (BY TIMEFRAME)', count(*) from cscart_companies where cscart_companies.timestamp BETWEEN ".($data[$k]['time_from'] + Registry::get('config.timestamp'))." AND '".($data[$k]['time_to'] + Registry::get('config.timestamp'))."' union Select 'Merchant Live (BY TIMEFRAME)', count(distinct c.company_id) from cscart_companies c,  cscart_products p where c.company_id = p.company_id  AND p.timestamp BETWEEN ".($data[$k]['time_from'] + Registry::get('config.timestamp'))." AND ".($data[$k]['time_to'] + Registry::get('config.timestamp'))." and c.status = 'A' and p.status = 'A' ".$orderby." ".$order);

		}
	 } else {
		$data[$k]['tables']=db_get_hash_array("SELECT a.*, b.description FROM ?:sales_reports_tables as a LEFT JOIN ?:sales_reports_table_descriptions as b ON a.table_id = b.table_id AND lang_code = ?s WHERE a.report_id = ?i ORDER BY position", 'table_id', CART_LANGUAGE, $report_id);
	     }
         
	/*foreach ($data[$k]['tables'] as $key => $value) {

		$data[$k]['tables'][$key]['time_from'] = $data[$k]['time_from'];
		$data[$k]['tables'][$key]['time_to'] = $data[$k]['time_to'];

		$elements = db_get_array("SELECT a.*, c.code FROM ?:sales_reports_table_elements as a LEFT JOIN ?:sales_reports_elements as c ON a.element_id = c.element_id WHERE a.table_id = ?i ORDER BY a.position", $value['table_id']);

		$data[$k]['tables'][$key]['interval_id'] = $value['interval_id'];
		$data[$k]['tables'][$key]['elements'] = fn_check_elements($elements, $data[$k]['tables'][$key]['time_from'], $data[$k]['tables'][$key]['time_to'], $value);
		$data[$k]['tables'][$key]['intervals'] = fn_check_intervals($data[$k]['tables'][$key]['interval_id'], $data[$k]['tables'][$key]['time_from'], $data[$k]['tables'][$key]['time_to']);
	}*/

	return $data;
}

//
// This function generates time intervals for a period
//
function fn_check_intervals($interval, $time_from, $time_to, $limit = 0)
{
	$intervals['0'] = db_get_row("SELECT a.* FROM ?:sales_reports_intervals as a WHERE a.interval_id = ?i", $interval);

	if ($intervals['0']['value'] != 0) {
		$num = 0;
		$time_end = $time_from;
		while ($time_to > $time_end) {
			$temp = array();
			$temp = $intervals[0];
			$temp['interval_id'] = $temp['interval_id'] . $num;
			$temp['time_from'] = $time_end;
			$time_end += ($intervals['0']['interval_id'] == '7') ? (mktime(0, 0, 0, date("m", $time_end) + 1, 1, date("Y", $time_end)) - $time_end) : $temp['value'];
			$temp['time_to'] =  $time_end;
			$num++;
			$temp['description'] = fn_date_format($temp['time_from'], Registry::get('settings.Reports.' . $temp['interval_code']));
			$intervals[] = $temp;
		}
		unset($intervals[0]);
		ksort($intervals);
	} else {
		$intervals['0']['time_from'] = $time_from;
		$intervals['0']['time_to'] = $time_to;
	}

	//$intervals = array_reverse($intervals);
	if (!empty($limit)) {
		$i = 1;
		$j = 0;
		$temp = array();
		foreach ($intervals as $k => $v) {
			$temp[$i][$k] = $v;
			$j ++;
			if ($j == $limit) {
				$j = 0;
				$i ++;
			}
		}
		unset($intervals);
		$intervals = $temp;
	}
	return $intervals;
}

//
// This function SETS AUTO GENERATED PARAMETERS
//
function fn_check_elements($elements, $time_from, $time_to, $table)
{
	$order_status_descr = fn_get_statuses(STATUSES_ORDER, true, true, true);

	foreach ($elements as $k => $v) {
		if ($table['auto'] == "Y") {
			$i = 0;
			$limit = $v['limit_auto'];
			$new_element = $v;
			unset($elements[$k]);
			$table_condition = fn_get_table_condition($table['table_id'], true);
			$order_ids = fn_proceed_table_conditions($table_condition, "a");

			$l_l = (($table['type'] == 'T') ? 32 : (($limit > 9) ? 18 : 19)); // Legend length
			// ************************* GET AUTO ORDERS ***************************** //
			if ($v['code'] == 'order') {
				if ($v['dependence'] == 'max_n') {
					// Get orders with max products bought
					$orders = db_get_array("SELECT b.order_id, SUM(b.amount) as total FROM ?:order_details as b LEFT JOIN ?:orders as a ON b.order_id = a.order_id WHERE a.timestamp BETWEEN ?i AND ?i ?p GROUP BY b.order_id ORDER BY total DESC LIMIT $limit", $time_from, $time_to, $order_ids);

				} elseif ($v['dependence'] == 'max_p') {
					// Get orders with max amount
					$orders = db_get_array("SELECT a.order_id, a.total FROM ?:orders as a WHERE a.timestamp BETWEEN ?i AND ?i ?p ORDER BY total DESC LIMIT $limit", $time_from, $time_to, $order_ids);
				}
			}
			// ************************* GET AUTO STATUSES ***************************** //
			elseif ($v['code'] == 'status') {
				if ($v['dependence'] == 'max_n') {
					// Get satuses with max status appears
					$satuses = db_get_array("SELECT a.status, COUNT(a.total) as status_total FROM ?:orders as a WHERE a.timestamp BETWEEN ?i AND ?i AND a.status != '' ?p GROUP BY status ORDER BY status_total DESC LIMIT $limit", $time_from, $time_to, $order_ids);

				} elseif ($v['dependence'] == 'max_p') {
					// Get satuses with max amount paid
					$satuses = db_get_array("SELECT a.status, SUM(a.total) as status_total FROM ?:orders as a WHERE a.timestamp BETWEEN ?i AND ?i AND a.status != '' ?p GROUP BY status ORDER BY status_total DESC LIMIT $limit", $time_from, $time_to, $order_ids);
				}
			}
			// ************************* GET AUTO PAYMENTS ***************************** //
			elseif ($v['code'] == 'payment') {
				if ($v['dependence'] == 'max_n') {
					// Get payments with max number used
					$payments = db_get_array("SELECT a.payment_id, COUNT(a.total) as payment_total, b.payment FROM ?:orders as a LEFT JOIN ?:payment_descriptions AS b ON a.payment_id = b.payment_id AND b.lang_code = ?s WHERE a.timestamp BETWEEN ?i AND ?i ?p GROUP BY a.payment_id ORDER BY payment_total DESC LIMIT $limit", CART_LANGUAGE, $time_from, $time_to, $order_ids);

				} elseif ($new_element['dependence'] == 'max_p') {
					// Get payments with max amount paid
					$payments = db_get_array("SELECT a.payment_id, SUM(a.total) as payment_total, b.payment FROM ?:orders as a LEFT JOIN ?:payment_descriptions AS b ON a.payment_id = b.payment_id AND b.lang_code = ?s WHERE a.timestamp BETWEEN ?i AND ?i ?p GROUP BY a.payment_id ORDER BY payment_total DESC LIMIT $limit", CART_LANGUAGE, $time_from, $time_to, $order_ids);
				}
			}
			// ************************* GET AUTO LOCATIONS **************************** //
			elseif ($v['code'] == 'location') {
				if ($v['dependence'] == 'max_n') {
					// Get locations with max orders placed
					$countries = db_get_array("SELECT a.s_country, a.s_state, SUM(a.total) as country_total, b.country FROM ?:orders as a LEFT JOIN ?:country_descriptions AS b ON a.s_country = b.code AND b.lang_code = ?s WHERE a.timestamp BETWEEN ?i AND ?i ?p GROUP BY a.s_country, a.s_state ORDER BY country_total DESC LIMIT $limit", CART_LANGUAGE, $time_from, $time_to, $order_ids);

				} elseif ($v['dependence'] == 'max_p') {
					// Get locations with max amount paid
					$countries = db_get_array("SELECT a.s_country, a.s_state, SUM(a.total) as country_total, b.country FROM ?:orders as a LEFT JOIN ?:country_descriptions AS b ON a.s_country = b.code AND b.lang_code = ?s WHERE a.timestamp BETWEEN ?i AND ?i ?p GROUP BY a.s_country, a.s_state ORDER BY country_total DESC LIMIT $limit", CART_LANGUAGE, $time_from, $time_to, $order_ids);
				}
			}
			// *************************** GET AUTO USERS ****************************** //
			elseif ($v['code'] == 'user') {
				if ($v['dependence'] == 'max_n') {
					// Get users with max orders placed
					$users = db_get_array("SELECT a.user_id, COUNT(a.total) as user_total, b.firstname, b.lastname FROM ?:orders as a LEFT JOIN ?:users AS b ON a.user_id = b.user_id WHERE a.timestamp BETWEEN ?i AND ?i ?p GROUP BY a.user_id ORDER BY user_total DESC LIMIT $limit", $time_from, $time_to, $order_ids);

				} elseif ($v['dependence'] == 'max_p') {
					// Get users with max amount paid
					$users = db_get_array("SELECT a.user_id, SUM(a.total) as user_total, b.firstname, b.lastname FROM ?:orders as a LEFT JOIN ?:users AS b ON a.user_id = b.user_id WHERE a.timestamp BETWEEN ?i AND ?i ?p GROUP BY a.user_id ORDER BY user_total DESC LIMIT $limit", $time_from, $time_to, $order_ids);
				}
			}
			// ************************* GET AUTO CATEGORIES ***************************** //
			elseif ($v['code'] == 'category') {
				if ($v['dependence'] == 'max_n') {
					// Get categories with max number of products bought from it
					$categories = db_get_array("SELECT c.category_id, SUM(b.amount) as category_amount, d.category FROM ?:order_details as b LEFT JOIN ?:orders as a ON b.order_id = a.order_id LEFT JOIN ?:products_categories as c ON b.product_id = c.product_id AND c.link_type = 'M' LEFT JOIN ?:category_descriptions as d ON c.category_id = d.category_id AND d.lang_code = ?s WHERE a.timestamp BETWEEN ?i AND ?i ?p GROUP BY c.category_id ORDER BY category_amount DESC LIMIT $limit", CART_LANGUAGE, $time_from, $time_to, $order_ids);

				} elseif ($v['dependence'] == 'max_p') {
					// Get categories with max amount paid for products from it
					$categories = db_get_array("SELECT c.category_id, SUM(b.price * b.amount) as category_amount, d.category FROM ?:order_details as b LEFT JOIN ?:orders as a ON b.order_id = a.order_id LEFT JOIN ?:products_categories as c ON b.product_id = c.product_id AND c.link_type = 'M' LEFT JOIN ?:category_descriptions as d ON c.category_id = d.category_id AND d.lang_code = ?s WHERE a.timestamp BETWEEN ?i AND ?i ?p GROUP BY c.category_id ORDER BY category_amount DESC LIMIT $limit", CART_LANGUAGE, $time_from, $time_to, $order_ids);
				}
			}
			// ************************* GET AUTO PRODUCTS ***************************** //
			elseif ($v['code'] == 'product') {
				$products_rule_ids = '';
				if (!empty($table_condition['product'])) {
					$products_rule_ids .= db_quote(' AND b.product_id IN (?n)', $table_condition['product']);
				}
				if (!empty($table_condition['category'])) {
					$_p_ids = db_get_fields('SELECT product_id FROM ?:products_categories WHERE category_id IN (?n)', $table_condition['category']);
					if (!empty($_p_ids)) {
						$products_rule_ids .= db_quote(' AND b.product_id IN (?n)', $_p_ids);
					}
				} 
				if ($v['dependence'] == 'max_n') {
					// Get products with max number bought
					$products = db_get_array("SELECT b.product_id, SUM(b.amount) as product_amount, c.product FROM ?:order_details as b LEFT JOIN ?:orders as a ON b.order_id = a.order_id LEFT JOIN ?:product_descriptions as c ON b.product_id = c.product_id AND c.lang_code = ?s WHERE a.timestamp BETWEEN ?i AND ?i ?p ?p GROUP BY b.product_id ORDER BY product_amount DESC LIMIT $limit", CART_LANGUAGE, $time_from, $time_to, $order_ids, $products_rule_ids);

				} elseif ($v['dependence'] == 'max_p') {
					// Get products with max amount paid
					$products = db_get_array("SELECT b.product_id, SUM(b.price * b.amount) as product_amount, c.product FROM ?:order_details as b LEFT JOIN ?:orders as a ON b.order_id = a.order_id LEFT JOIN ?:product_descriptions as c ON b.product_id = c.product_id AND c.lang_code = ?s WHERE a.timestamp BETWEEN ?i AND ?i ?p ?p GROUP BY b.product_id ORDER BY product_amount DESC LIMIT $limit", CART_LANGUAGE, $time_from, $time_to, $order_ids, $products_rule_ids);
				}
			}
			while ($i < $limit) {
				$i ++;
				$_desc_id = ($table['type'] == 'P' || $table['type'] == 'C') ? "" : "$i. ";
				$new_element['description'] = " $i." . fn_get_lang_var("reports_parameter_" . $v['element_id']);
				$new_element['element_hash'] = $v['element_hash'] . "_$i";
				$new_element['position'] = $i;
				$new_element['auto_generated'] = 'Y';
				$new_element['request'] = '1';
				// ************************* GET AUTO ORDERS ***************************** //
				if ($new_element['code'] == 'order') {
					if (empty($orders[$i - 1])) {
						return $elements;
					}
					$o_id = $orders[$i - 1]['order_id'];
					$new_element['description'] = ($table['type'] != 'T') ? ($_desc_id . fn_get_lang_var('order') . '#' . $o_id) : ('<a href="' . fn_url("orders.details?order_id=$o_id") . '">'.$i.'. ' . fn_get_lang_var('order') . ' #' . $o_id . "</a>");
					$new_element['request'] = "order_id IN ('$o_id')";
				}
				// ************************* GET AUTO STATUSES ***************************** //
				elseif ($new_element['code'] == 'status') {
					if (empty($satuses[$i - 1])) {
						return $elements;
					}
					$status = $satuses[$i - 1]['status'];
					$new_element['description'] = $_desc_id . $order_status_descr[$status];
					if ($table['type'] == 'T') {
						$time_link = '&from_Year=' . date('Y', $time_from) . '&from_Month=' . date('m', $time_from) . '&from_Day=' . date('j', $time_from) . '&to_Year=' . date('Y', $time_to) . '&to_Month=' . date('m', $time_to) . '&to_Day=' . date('j', $time_to);
					}
					$new_element['description'] = ($table['type'] != 'T') ? ("$i. $order_status_descr[$status]") : ('<a href="' . fn_url("orders.manage?search_orders=Y&status=$status&period=C&$time_link") . "\">$i. $order_status_descr[$status]</a>");
					$new_element['request'] = "order_id IN ('" . implode("', '", db_get_fields("SELECT order_id FROM ?:orders WHERE status = ?s", $status)) . "')";
				}
				// ************************* GET AUTO PAYMENTS ***************************** //
				elseif ($new_element['code'] == 'payment') {
					if (empty($payments[$i - 1])) {
						return $elements;
					}
					$pay_id = $payments[$i - 1]['payment_id'];
					$pay_name = $payments[$i - 1]['payment'];
					$_descr = fn_sales_repors_format_description($pay_name, $l_l, $_desc_id);
					$new_element['description'] = ($table['type'] != 'T') ? $_descr : ('<a href="' . fn_url("payments.manage#group$pay_id") . '">' . "$_descr</a>");
					if (!db_get_field("SELECT payment_id FROM ?:payments WHERE payment_id = ?i", $pay_id)) {
						$new_element['description'] = "$i. " . fn_get_lang_var('deleted');
					}
					$new_element['request'] = "order_id IN ('" . implode("', '", db_get_fields("SELECT order_id FROM ?:orders WHERE payment_id = ?i", $pay_id)) . "')";
				}
				// ************************* GET AUTO LOCATIONS **************************** //
				elseif ($new_element['code'] == 'location') {
					if (empty($countries[$i - 1])) {
						return $elements;
					}
					$c_id = $countries[$i - 1]['s_country'];
					$st_id = $countries[$i - 1]['s_state'];
					$sate = empty($st_id) ? '' : db_get_field("SELECT state FROM ?:state_descriptions as a LEFT JOIN ?:states as b ON b.state_id = a.state_id AND b.country_code = ?s WHERE b.code = ?s AND lang_code = ?s", !empty($c_id) ? $c_id : Registry::get('settings.General.default_country'), $st_id, CART_LANGUAGE);
					$c_name = $countries[$i - 1]['country'] . (empty($sate) ? '' : ' [' . $sate . ']');
					$_descr = fn_sales_repors_format_description($c_name, $l_l, $_desc_id);
					$new_element['description'] =  $_descr;
					$new_element['request'] = "order_id IN ('" . implode("', '", db_get_fields("SELECT order_id FROM ?:orders WHERE s_country = ?s AND s_state = ?s", $c_id, $st_id)) . "')";
				}
				// *************************** GET AUTO USERS ****************************** //
				elseif ($new_element['code'] == 'user') {
					if (empty($users[$i - 1])) {
						return $elements;
					}
					$u_id = $users[$i - 1]['user_id'];
					$u_name = $users[$i - 1]['firstname'] . ' ' . $users[$i - 1]['lastname'];
					$_descr = fn_sales_repors_format_description($u_name, $l_l, $_desc_id);
					$new_element['description'] = ($table['type'] != 'T') ? $_descr : ('<a href="' . fn_url("profiles.update?user_id=$u_id") . '">' . "$_descr</a>");
					if (!db_get_field("SELECT user_id FROM ?:users WHERE user_id = ?i", $u_id)) {
						$new_element['description'] = "$i. " . fn_get_lang_var('anonymous');
					}
					$new_element['request'] = "order_id IN ('" . implode("', '", db_get_fields("SELECT order_id FROM ?:orders WHERE user_id = ?i", $u_id)) . "')";
				}
				// ************************* GET AUTO CATEGORIES ***************************** //
				elseif ($new_element['code'] == 'category') {
					if (empty($categories[$i - 1])) {
						return $elements;
					}
					$c_name = $categories[$i - 1]['category'];
					$c_id = $categories[$i - 1]['category_id'];
					$new_element['product_ids'] = db_get_fields("SELECT product_id FROM ?:products_categories WHERE category_id = ?i", $c_id);
					$_descr = fn_sales_repors_format_description($c_name, $l_l, $_desc_id);
					$new_element['description'] = ($table['type'] != 'T') ? $_descr : ('<a href="' . fn_url("categories.update?category_id=$c_id") . '">' . "$_descr</a>");
					if (empty($c_id)) {
						$new_element['description'] = "$i. " . fn_get_lang_var('deleted');
					}
					$new_element['request'] = "order_id IN ('" . implode("', '", db_get_fields("SELECT a.order_id FROM ?:order_details as a LEFT JOIN ?:products_categories as b ON a.product_id = b.product_id WHERE b.category_id = ?i", $c_id)) . "')";
				}
				// ************************* GET AUTO PRODUCTS ***************************** //
				elseif ($new_element['code'] == 'product') {
					if (empty($products[$i - 1])) {
						return $elements;
					}
					$p_name = $products[$i - 1]['product'];
					$p_id = $products[$i - 1]['product_id'];
					$new_element['product_ids'] = array($p_id);
					$_descr = fn_sales_repors_format_description($p_name, $l_l, $_desc_id);
					$new_element['description'] = ($table['type'] != 'T') ? $_descr : ('<a href="' . fn_url("products.update?product_id=$p_id") . '">' . "$_descr</a>");
					if (!db_get_field("SELECT product_id FROM ?:products WHERE product_id = ?i", $p_id)) {
						$new_element['description'] = "$i. " . fn_get_lang_var('deleted');
					}
					$new_element['request'] = "order_id IN ('" . implode("', '", db_get_fields("SELECT order_id FROM ?:order_details WHERE product_id = ?i", $p_id)) . "')";
				}
				$elements[] = $new_element;
			}
		}
	}
	return $elements;
}

//
// This function gets the parameters and time intervals
//
function fn_get_parameters($report_id)
{
	$report_type = db_get_field("SELECT type FROM ?:sales_reports WHERE report_id = ?i", $report_id);
	$data['parameters'] = db_get_array("SELECT a.* FROM ?:sales_reports_elements as a WHERE a.type = ?s AND a.depend_on_it = 'Y'", $report_type);
	$data['values'] = db_get_array("SELECT a.* FROM ?:sales_reports_elements as a WHERE a.type = ?s AND a.depend_on_it = 'N'", $report_type);
	$data['intervals'] = db_get_array("SELECT a.* FROM ?:sales_reports_intervals as a ORDER BY a.interval_id");

	return $data;

}

function fn_get_report_data($id, $table_id = 0)
{
	// Get Data of Specific Table
	if (!empty($table_id)) {
		$data = db_get_row("SELECT a.*, b.description FROM ?:sales_reports_tables as a LEFT JOIN ?:sales_reports_table_descriptions as b ON a.table_id = b.table_id AND lang_code = ?s WHERE a.report_id = ?i AND a.table_id = ?i", CART_LANGUAGE, $id, $table_id);
		$data['elements'] = db_get_array("SELECT a.* FROM ?:sales_reports_table_elements as a WHERE a.report_id = ?i AND a.table_id = ?i ORDER BY a.position", $id, $table_id);
		$data['intervals'] = db_get_array("SELECT a.interval_id FROM ?:sales_reports_tables as a WHERE a.report_id = ?i AND a.table_id = ?i", $id, $table_id);
		return $data;

	// Get Data of the whole report
	} else {
		$data = db_get_row("SELECT a.*, b.description FROM ?:sales_reports as a LEFT JOIN ?:sales_reports_descriptions as b ON a.report_id = b.report_id AND lang_code = ?s WHERE a.report_id = ?i", CART_LANGUAGE, $id);
		$data['tables'] = db_get_array("SELECT a.*, b.description FROM ?:sales_reports_tables as a LEFT JOIN ?:sales_reports_table_descriptions as b ON a.table_id = b.table_id AND lang_code = ?s WHERE report_id = ?i ORDER BY position", CART_LANGUAGE, $id);
		foreach ($data['tables'] as $k => $v) {
			$data['tables'][$k]['elements'] = db_get_array("SELECT a.* FROM ?:sales_reports_table_elements as a WHERE a.report_id = ?i AND a.table_id = ?i ORDER BY a.position", $id, $v['table_id']);
			$data['tables'][$k]['intervals'] = db_get_array("SELECT a.interval_id FROM ?:sales_reports_tables as a WHERE a.report_id = ?i AND a.table_id = ?i", $id, $v['table_id']);
		}
		return $data;
	}
}

function fn_get_depended()
{
	return db_get_array("SELECT a.element_id, a.code FROM ?:sales_reports_elements as a WHERE a.depend_on_it = 'Y'");

}

//
//   Prepare SQL query for the table condition   //////////////////////////
//
function fn_proceed_table_conditions($table_condition, $alias = false)
{
	$order_ids ='';

	$ord_field = (empty($alias)) ? "order_id" : $alias . ".order_id";

	if (!empty($table_condition['status'])) {
		$st_field = (empty($alias)) ? "status" : $alias . ".status";
		$order_ids .= db_quote(" AND $st_field IN (?a)", $table_condition['status']);
	}

	if (PRODUCT_TYPE == 'MULTIVENDOR' || PRODUCT_TYPE == 'MULTISHOP') {
		$st_field = (empty($alias)) ? "status" : $alias . ".status";
		$order_ids .= db_quote(" AND $st_field != ?s", 'T');
		$st_field = (empty($alias)) ? "is_parent_order" : $alias . ".is_parent_order";
		$order_ids .= db_quote(" AND $st_field != ?s", 'Y');
	}
	
	if (defined('COMPANY_ID')) {
		$st_field = (empty($alias)) ? "company_id" : $alias . ".company_id";
		$order_ids .= db_quote(" AND $st_field = ?i", COMPANY_ID);
	}

	if (!empty($table_condition['order'])) {
		$order_ids .= db_quote(" AND $ord_field IN (?n)", $table_condition['order']);
	}

	if (!empty($table_condition['user'])) {
		$usr_field = (empty($alias)) ? "user_id" : $alias . ".user_id";
		$order_ids .= db_quote(" AND $usr_field IN (?n)", $table_condition['user']);
	}

	if (!empty($table_condition['payment'])) {
		$pm_field = (empty($alias)) ? "payment_id" : $alias . ".payment_id";
		$order_ids .= db_quote(" AND $pm_field IN (?n)", $table_condition['payment']);
	}

	if (!empty($table_condition['product'])) {
		$order_products = db_get_fields("SELECT order_id FROM ?:order_details WHERE product_id IN (?n) ORDER BY order_id", $table_condition['product']);
		if (!empty($order_products)) {
			$order_ids .= db_quote(" AND $ord_field IN (?n)", $order_products);
		}
	}

	if (!empty($table_condition['category'])) {
		$order_products = db_get_fields("SELECT a.order_id FROM ?:order_details as a LEFT JOIN ?:products_categories as b ON a.product_id = b.product_id WHERE b.category_id IN (?n) ORDER BY a.order_id", $table_condition['category']);
		if (!empty($order_products)) {
			$order_ids .= db_quote(" AND $ord_field IN (?n)", $order_products);
		} else {
			$order_ids .= " AND $ord_field IN ('')";
		}
	}

	if (!empty($table_condition['location'])) {
		$states = db_get_fields("SELECT a.code FROM ?:states AS a LEFT JOIN ?:destination_elements AS b ON a.state_id = b.element WHERE b.destination_id IN (?n) AND b.element_type = 'S'", $table_condition['location']);
		$countries = db_get_fields("SELECT element FROM ?:destination_elements WHERE destination_id IN (?n) AND element_type = 'C'", $table_condition['location']);

		if (!empty($states)) {
			$s_field = (empty($alias)) ? "s_state" : $alias . ".s_state";
			$order_ids .= db_quote(" AND $s_field IN (?a)", $states);
		}

		if (!empty($countries)) {
			$cn_field = (empty($alias)) ? "s_country" : $alias . ".s_country";
			$order_ids .= db_quote(" AND $cn_field IN (?a)", $countries);
		}
	}

	return $order_ids;
}

//
//   This function calculates the statistics data for the current table   //////////////////////////
//
function fn_get_report_statistics(&$table)
{
	$table_condition = fn_get_table_condition($table['table_id'], true);
	$order_ids = fn_proceed_table_conditions($table_condition);

	foreach ($table['elements'] as $key => $element) {
		foreach ($table['intervals'] as $interval) {
			$a = $element['element_hash'];
			$b = $interval['interval_id'];
			if (empty($element['auto_generated'])) {
				$element['request'] = fn_get_parameter_request($table['table_id'], $element['element_hash']);
			}
			$interval['request'] = db_quote(" timestamp BETWEEN ?i AND ?i", $interval['time_from'], $interval['time_to']);

			if ($table['display'] == 'order_amount') {
				$data[$a][$b] = db_get_field("SELECT SUM(total) FROM ?:orders WHERE $element[request] AND $interval[request] $order_ids");
			} elseif ($table['display'] == 'order_number') {
				$data[$a][$b] = db_get_field("SELECT COUNT(total) FROM ?:orders WHERE $element[request] AND $interval[request] $order_ids");
			} elseif ($table['display'] == 'shipping') {
				$data[$a][$b] = db_get_field("SELECT SUM(shipping_cost) FROM ?:orders WHERE $element[request] AND $interval[request] $order_ids");
			} elseif ($table['display'] == 'discount') {
				 $data[$a][$b] = db_get_field("SELECT SUM(subtotal_discount) FROM ?:orders WHERE $element[request] AND $interval[request] $order_ids");
				 $_orders = db_get_fields("SELECT order_id FROM ?:orders WHERE $element[request] AND $interval[request] $order_ids");
				 $discounts = db_get_fields("SELECT b.extra FROM ?:orders as a LEFT JOIN ?:order_details as b ON a.order_id = b.order_id WHERE a.order_id IN (?n)", $_orders);
				 foreach ($discounts as $key => $value) {
					$extra = @unserialize($value);
					if (!empty($extra['discount'])) {
						$data[$a][$b] += $extra['discount'];
					}
				 }
				 $data[$a][$b] = fn_format_price($data[$a][$b]);
			} elseif ($table['display'] == 'tax') {
				 $data[$a][$b] = 0;
				 $_orders = db_get_fields("SELECT order_id FROM ?:orders WHERE $element[request] AND $interval[request] $order_ids");
				 $all_taxes = db_get_fields("SELECT data FROM ?:order_data WHERE order_id IN (?n) AND type = 'T'", $_orders);
				 foreach ($all_taxes as $key => $value) {
					$taxes = @unserialize($value);
					if (is_array($taxes)) {
						foreach ($taxes as $v) {
							if (!empty($v['tax_subtotal'])) {
								$data[$a][$b] += $v['tax_subtotal'];
							}
						}
					}
					$data[$a][$b] = fn_format_price($data[$a][$b]);
				}
			} elseif ($table['display'] == 'product_cost') {
				$product_cost = (empty($element['product_ids'])) ? '' : db_quote(" AND product_id IN (?n)", $element['product_ids']);
				$_orders = db_get_fields("SELECT order_id FROM ?:orders WHERE $element[request] AND $interval[request] $order_ids");
				$data[$a][$b] = db_get_field("SELECT SUM(amount * price) FROM ?:order_details WHERE order_id IN (?n) ?p", $_orders, $product_cost);
			} elseif ($table['display'] == 'product_number') {
				$product_count = (empty($element['product_ids'])) ? '' : " AND product_id IN ('" . implode("', '", $element['product_ids']) . "')";
				$_orders = db_get_fields("SELECT order_id FROM ?:orders WHERE $element[request] AND $interval[request] $order_ids ");
				$data[$a][$b] = db_get_field("SELECT SUM(amount) FROM ?:order_details WHERE order_id IN (?n) ?p", $_orders, $product_count);
			}
			$data[$a][$b] = (empty($data[$a][$b])) ? 0 : $data[$a][$b];
			$data[$a][$b] = (@$data[$a][$b] == '0.00') ? 0 : $data[$a][$b];


		}
	}
	return @$data;
}

//
// Gets the table condition from the table
//
function fn_get_table_condition($table_id, $for_calculate = false)
{
	$auth = & $_SESSION['auth'];

	$data = db_get_array("SELECT * FROM ?:sales_reports_table_conditions WHERE table_id = ?i", $table_id);
	foreach ($data as $key => $value) {
		$conditions[$value['code']][$value['sub_element_id']] = $value['sub_element_id'];

		if (empty($conditions[$value['code']][$value['sub_element_id']])) {
			unset($conditions[$value['code']][$value['sub_element_id']]);
		}
	}
	return !empty($conditions) ? $conditions : false;
}


//
// This function gets the conditions of the specified parameter (e.g. 'processed' for status etc.)
//
function fn_get_element_condition($table_id, $element_hash, $for_calculate = false)
{
	$auth = & $_SESSION['auth'];

	$element_id = db_get_field("SELECT element_id FROM ?:sales_reports_table_elements WHERE element_hash = ?s", $element_hash);
	$data = db_get_row("SELECT * FROM ?:sales_reports_elements WHERE element_id = ?i", $element_id);
	$cond = db_get_fields("SELECT ids FROM ?:sales_reports_table_element_conditions WHERE table_id = ?i AND element_hash = ?s", $table_id, $element_hash);
	foreach ($cond as $k => $v) {
		$data['conditions'][$v] = $v;

		if (!$for_calculate) {
			if ($data['code'] == 'product') {
				$data['conditions'][$v] = fn_get_product_data($v, $auth, CART_LANGUAGE, true, false, false);
			}
			if ($data['code'] == 'user') {
				$data['conditions'][$v] = fn_get_user_info($v, false);
			}
			if ($data['code'] == 'order') {
				$data['conditions'][$v] = db_get_row("SELECT * FROM ?:orders WHERE order_id = ?i", $v);
			}
		}
	}
	return $data = (empty($data)) ? false : $data;

}

//
// Generates the SQL request considering the parameter conditions
//
function fn_get_parameter_request($table_id, $element_hash)
{
	$element_code = db_get_field("SELECT b.code FROM ?:sales_reports_table_elements as a LEFT JOIN ?:sales_reports_elements as b ON a.element_id = b.element_id WHERE a.table_id = ?i AND element_hash = ?s", $table_id, $element_hash);
	$element_condition = db_get_fields("SELECT ids FROM ?:sales_reports_table_element_conditions WHERE table_id = ?i AND element_hash = ?s", $table_id, $element_hash);

	if ($element_code == 'status' && !empty($element_condition)) {
		return db_quote("status IN (?a)", $element_condition);

	} elseif ($element_code == 'order' && !empty($element_condition)) {
		return db_quote("order_id IN (?n)", $element_condition);

	} elseif ($element_code == 'user' && !empty($element_condition)) {
		return db_quote("user_id IN (?n)", $element_condition);

	} elseif ($element_code == 'payment' && !empty($element_condition)) {
		return db_quote("payment_id IN (?n)", $element_condition);

	} elseif ($element_code == 'product' && !empty($element_condition)) {
		$order_products = db_get_fields("SELECT order_id FROM ?:order_details WHERE product_id IN (?n) ORDER BY order_id", $element_condition);
		return db_quote("order_id IN (?n)", $order_products);

	} elseif ($element_code == 'category' && !empty($element_condition)) {
		$order_products = db_get_fields("SELECT a.order_id FROM ?:order_details as a LEFT JOIN ?:products_categories as b ON a.product_id = b.product_id WHERE b.category_id IN (?n) ORDER BY a.order_id", $element_condition);
		return db_quote("order_id IN (?n)", $order_products);

	} elseif ($element_code == 'location' && !empty($element_condition)) {
		$states = db_get_fields("SELECT a.code FROM ?:states AS a LEFT JOIN ?:destination_elements AS b ON a.state_id = b.element WHERE b.destination_id IN (?n)", $element_condition);
		$countries = db_get_fields("SELECT element FROM ?:destination_elements WHERE destination_id IN (?n)", $element_condition);
		$result = '';
		if (!empty($states)) {
			$result = db_quote("s_state IN (?a)", $states);
		}
		if (!empty($countries)) {
			$result .= (!empty($result)) ? "AND" : "";
			$result .= db_quote(" s_country IN (?a)", $countries);
		}
		return $result;
	}
	return '1';
}

//
// This function deletes report or one of its objects table etc.
//
function fn_delete_report_data($object = 'report', $id)
{

	if (empty($id)) {
		return false;
	}
	if ($object == 'report') {
		$table_ids = db_get_fields("SELECT table_id FROM ?:sales_reports_tables WHERE report_id = ?i", $id);
		db_query("DELETE FROM ?:sales_reports WHERE report_id = ?i", $id);
		db_query("DELETE FROM ?:sales_reports_descriptions WHERE report_id = ?i", $id);
		foreach ($table_ids as $k => $v) {
			db_query("DELETE FROM ?:sales_reports_tables WHERE table_id = ?i", $v);
			db_query("DELETE FROM ?:sales_reports_table_descriptions WHERE table_id = ?i", $v);
			db_query("DELETE FROM ?:sales_reports_table_elements WHERE table_id = ?i", $v);
			db_query("DELETE FROM ?:sales_reports_table_element_conditions WHERE table_id = ?i", $v);
		}

	} elseif ($object == 'table') {
			db_query("DELETE FROM ?:sales_reports_tables WHERE table_id = ?i", $id);
			db_query("DELETE FROM ?:sales_reports_table_descriptions WHERE table_id = ?i", $id);
			db_query("DELETE FROM ?:sales_reports_table_elements WHERE table_id = ?i", $id);
			db_query("DELETE FROM ?:sales_reports_table_element_conditions WHERE table_id = ?i", $id);
	}
}

//
// Clone existing table
//
function fn_report_table_clone($report_id, $table_id)
{
	//tables for report
	$table_data = db_get_row("SELECT a.*, b.description FROM ?:sales_reports_tables as a LEFT JOIN ?:sales_reports_table_descriptions as b ON a.table_id = b.table_id AND lang_code = ?s WHERE a.table_id = ?i", CART_LANGUAGE, $table_id);
	$data['report_id'] = $table_data['report_id'];
	$data['type'] = $table_data['type'];
	$table_id_new = db_query("INSERT INTO ?:sales_reports_tables ?e", $data);
	fn_create_description('sales_reports_table_descriptions', "table_id", $table_id_new, array("description" =>  $table_data["description"].'[CLONE]'));

	//Orders element for table
	$_elements = db_get_array("SELECT a.* FROM ?:sales_reports_table_elements as a WHERE a.report_id = ?i AND a.table_id = ?i AND a.time_interval = 'N' ORDER BY a.position", $report_id, $table_id);
	foreach ($_elements as $k => $element) {
		$data = $element;
		$data['table_id'] = $table_id_new;
		$data['condition'] = db_get_fields("SELECT ids FROM ?:sales_reports_table_element_conditions WHERE table_id = ?i AND element_hash = ?s", $table_id, $element['element_hash']);
		$data['element_hash'] = fn_generate_element_hash($table_id_new, $data['element_id'], $data['condition']);
		$_data = fn_check_table_fields($data, 'sales_reports_table_elements');
		db_query("INSERT INTO ?:sales_reports_table_elements ?e", $_data);

		$_cond['table_id'] = $table_id_new;
		$_cond['element_hash'] = $data['element_hash'];
		foreach ($data['condition'] as $kk => $value) {
			$_cond['ids'] = $value;
			db_query("INSERT INTO ?:sales_reports_table_element_conditions ?e", $_cond);
		}
	}

	//Intervals for table
	$_intervals = db_get_array("SELECT a.*, b.description FROM ?:sales_reports_table_elements as a WHERE a.report_id = ?i AND a.table_id = ?i AND a.time_interval = 'Y'", $report_id, $table_id);
	foreach ($_intervals as $k => $interval) {
		$data = $interval;
		$data['table_id'] = $table_id_new;
		$_data = fn_check_table_fields($data, 'sales_reports_table_elements');
		db_query("INSERT INTO ?:sales_reports_table_elements ?e", $_data);
	}

	return $table_id;
}

//
// Generates unique indentifier for the element using it's table_id, element_id and condition ids
//
function fn_generate_element_hash($table_id, $element_id, $ids = '')
{
	if (!empty($ids)) {
		natsort($ids);
	} else {
		$ids = array();
	}
	array_unshift($ids, $table_id, $element_id);
	return fn_crc32(implode('_', $ids));
}

//
// This function construct a text notice about table conditions
//
function fn_reports_get_conditions($conditions)
{
	$result = array();
	foreach ($conditions as $key => $value) {
		$result[$key]['objects'] = array();
		if ($key == "order") {
			foreach ($value as $v) {
				$result[$key]['objects'][] = array(
					'href' => 'orders.details?order_id=' . $v,
					'name' => '#' . $v
				);
			}
			$result[$key]['name'] = fn_get_lang_var('orders');

		} elseif ($key == "status") {
			$order_status_descr = fn_get_statuses(STATUSES_ORDER, true, true, true);
			foreach ($value as $k => $v) {
				$result[$key]['objects'][]['name'] = $order_status_descr[$v];
			}
		 	$result[$key]['name'] = fn_get_lang_var('status');

		} elseif ($key == "payment") {
			foreach ($value as $k => $v) {
				$result[$key]['objects'][]['name'] = db_get_field("SELECT payment FROM ?:payment_descriptions WHERE payment_id = ?i AND lang_code = ?s", $v, CART_LANGUAGE);
			}
			$result[$key]['name'] = fn_get_lang_var('payment_methods');

		} elseif ($key == "location") {
			foreach ($value as $k => $v) {
				$result[$key]['objects'][]['name'] = db_get_field("SELECT destination FROM ?:destination_descriptions WHERE destination_id = ?i AND lang_code = ?s", $v, CART_LANGUAGE);
			}
			$result[$key]['name'] = fn_get_lang_var('locations');
		}
		elseif ($key == "user") {
			foreach ($value as $v) {
				$result[$key]['objects'][] = array(
					'href' => 'profiles.update?user_id=' . $v,
					'name' => $v,
				);
			}
			$result[$key]['name'] = fn_get_lang_var('users');

		} elseif ($key == "category") {
			foreach ($value as $k => $v) {
				$result[$key]['objects'][] = array(
					'href' => 'categories.update?category_id=' . $v,
					'name' => db_get_field("SELECT category FROM ?:category_descriptions WHERE category_id = ?i AND lang_code = ?s", $v, CART_LANGUAGE),
				);
			}
			$result[$key]['name'] = fn_get_lang_var('categories');

		} elseif ($key == "product") {
			foreach ($value as $v) {
				$result[$key]['objects'][] = array(
					'href' => 'products.update&product_id=' . $v,
					'name' => $v,
				);
			}
			$result[$key]['name'] = fn_get_lang_var('products');
		}
	}

	return $result;
}


//
// Generate XML data for amcharts
//
function fn_amcharts_data($type, $data, $rows = array())
{
	if (empty($type) || empty($data)) {
		return false;
	}
	$fields = array('url', 'description');
	if ($type == 'bar') {
		$type = 'column';
	}
	// Prepare XML data
	switch ($type) {
		case 'pie':
			$xml_data = '<pie>';
			foreach ($data as $v) {
				$xml_data .= '<slice title="'. $v['title'] .'"';
				foreach ($fields as $fld) {
					if (!empty($v[$fld])) {
						$xml_data .= ' ' . $fld . '="'. $v[$fld] .'"';
					}
				}
				$xml_data .= '>'. $v['value'] .'</slice>';
			}
			$xml_data .= '<angle>30</angle></pie>';
			break;
		case 'column':
			$xid = 0;
			$gid = 1;
			// One columns
			if (empty($rows)) {
				$xml_data = '<chart><series><value xid="'. $xid .'">-</value></series><graphs>';
				foreach (array_reverse($data) as $v) {
						$xml_data .= '<graph gid="'. $gid .'" title="'. $v['title'] .'">';
						$xml_data .= '<value xid="'. $xid .'"';
						foreach ($fields as $fld) {
								if (!empty($v[$fld])) {
										$xml_data .= ' ' . $fld . '="'. $v[$fld] .'"';
								}
						}
						$xml_data .= '>'. $v['value'] .'</value></graph>';
						$gid++;
				}
				$xml_data .= '</graphs></chart>';

			// Many column
			} else {
				$xml_data = '<chart><series>';
				foreach ($rows as $k => $vvv) {
					$xml_data .= '<value xid="'. $vvv['interval_id'] .'">'.@$vvv['description'].'</value>';
				}
				$xml_data .= '</series><graphs>';
				foreach ($data as $key => $value) {
					$_title = $value[$vvv['interval_id']]['title'];
					$xml_data .= '<graph gid="'. $key .'" title="'. $_title .'">';
					foreach ($value as $k => $v) {
						$xml_data .= '<value xid="'. $k .'"';
						foreach ($fields as $fld) {
							if (!empty($v[$fld])) {
								$xml_data .= ' ' . $fld . '="'. $v[$fld] .'"';
							}
						}
						$xml_data .= '>'. $v['value'] .'</value>';
						$gid++;
					}
					$xml_data .= '</graph>';
				}
				$xml_data .= '</graphs></chart>';
			}
			break;
		case 'line':
			$_xaxis = array();
			$graphs = '<graphs>';
			foreach ($data as $gid => $graph) {
				$graphs .= '<graph gid="'. $gid .'" title="'. $graph['title'] .'">';
				foreach ($graph['values'] as $xid => $v) {
					if (!isset($_xaxis[$xid])) {
						$_xaxis[$xid] = $v['title'];
					}
					$graphs .= '<value xid="'. $xid .'"';
					foreach ($fields as $fld) {
						if (isset($v[$fld])) {
							$graphs .= ' ' . $fld . '="'. $v[$fld] .'"';
						}
					}
					$graphs .= '>'. $v['value'] .'</value>';
				}
				$graphs .= '</graph>';
			}
			$xaxis = '<xaxis>';
			foreach ($_xaxis as $xid => $x) {
				$xaxis .= '<value xid="'. $xid .'">'. $x .'</value>';
			}
			$xml_data = "<chart>$xaxis</xaxis>$graphs</graphs></chart>";
			break;
		default:
			$xml_data = '';
			break;
	}
	return $xml_data;
}

//
// Calculate flash object height
//
function fn_calc_height_ampie($data, $inc = 0)
{
	$height = 400;
	$row_height = 28;
	if (!empty($data)) {
		$max_length = 0;
		foreach ($data as $v) {
			if ($max_length < strlen($v['title'])) {
				$max_length = strlen($v['title']);
			}
		}
		if ($max_length < 12) {
			$cols = 5;
		} elseif ($max_length < 17) {
			$cols = 4;
		} elseif ($max_length < 25) {
			$cols = 3;
		} elseif ($max_length < 41) {
			$cols = 2;
		} else {
			$cols = 1;
		}
		$height += ceil(count($data) / $cols) * $row_height;
	}
	return $height + $inc;
}

//
// Calculate flash object height
//
function fn_calc_height_amcolumn($data)
{
	$height = 80;
	$row_height = 45;
	if (!empty($data)) {
		$height += count($data) * $row_height;
	}
	return $height;
}

// [/amCharts functions]

function fn_sales_repors_format_description($value, $limit, $id)
{
	if (strlen($value) > fn_strlen($value)) {
		$limit /= 2;	
	}
	return (fn_strlen($value) > $limit) ? $id . fn_substr($value, 0, $limit) . "..." : $id . $value;
}

?>
