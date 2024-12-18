<?php
define('AREA', 'A');
	define('AREA_NAME', 'admin');
require  dirname(__FILE__) . '/../prepare.php';
require  dirname(__FILE__) . '/../init.php';
$date = '';
if($_GET['till']){
$date = $_GET['till'];
}
else
{
	$date = tools_fn_ct_addday(date('Y-m-d'),-15);
}
function tools_fn_ct_addday($dat,$days)//$dat will be in ymd function
{
	$dat2 = strtotime(date("Y-m-d", strtotime($dat)) . " +" . $days . " day");
	return date('Y-m-d', $dat2);
}
if($_GET['productandweight']){
	$sql = "select o.order_id,d.product_id,si.shipment_id,s.`tracking_number`,weight
			from cscart_orders o
			inner join cscart_order_details d on o.order_id=d.order_id
			inner join cscart_shipment_items si on o.order_id=si.order_id
			inner join cscart_shipments s on s.shipment_id=si.shipment_id and s.weight is not NULL and s.weight !=''
			group by o.order_id having sum(d.amount)=1
			order by d.product_id";
	exit;
}
if($_GET['recalculate_payout'])
{
	$sql = "delete from clues_billing_payout_summary where billing_cycle='". $_GET['recalculate_payout'] ."'";
	db_query($sql);
	$sql = "select billing_cycle,company_id,company,
	sum(subtotal) as Sum_Selling_Price, 
	sum(order_total) as Sum_Order_Total,
	sum(selling_fee) as Sum_Selling_Fee,
	sum(total_fulfillment_fee) as Sum_Fulfillment_Service_Fee,
	sum(total_service_fee) as Sum_Total_Service_Fee,
	sum(net_payout) as Sum_Net_Payout			
	from clues_billing_order_data
	where billing_cycle='". $_GET['recalculate_payout'] ."'
	group by company_id
	";
	$r_s = db_get_array($sql);
	foreach($r_s as $rs)
	{
		$cmpny = $rs['company'];
		$cmpny = str_replace("'","''",$cmpny);
		$sql = "insert into clues_billing_payout_summary set billing_cycle='".$rs['billing_cycle']."',
																 merchant='".$cmpny."',
																 company_id='".$rs['company_id']."',
																 Sum_Selling_Price='".$rs['Sum_Selling_Price']."',
																 Sum_Order_Total='".$rs['Sum_Order_Total']."',
																 Sum_Selling_Fee='".$rs['Sum_Selling_Fee']."',
																 Sum_Fulfillment_Service_Fee='".$rs['Sum_Fulfillment_Service_Fee']."',
																 Sum_Total_Service_Fee='".$rs['Sum_Total_Service_Fee']."',
																 Sum_Net_Payout='".$rs['Sum_Net_Payout']."'";
		db_query($sql);														 
	}
	echo "Recalculated";exit;
}
if($_GET['weight'])
{
	$sql = "insert into clues_billing_exception_handling set order_id='".$_GET['order_id']."', weight='".$_GET['weight']."'";
	db_query($sql);
	echo "success";
	exit;
}
if($_GET['orderitemdata'])
{
	$comm = $_GET['commission'];
	$orddata = explode('|',$_GET['orderitemdata']);
	//var_dump($orddata);
	foreach($orddata as $d)
	{
		$da = explode(',',$d);
		$sql = "insert into clues_billing_exception_handling set order_id='".$da[0]."', item_id='".$da[1]."', commission='". $comm ."'";
		db_query($sql);
	
	}
	echo "success";
	exit;
}
if($_GET['bulk_weight_orders'])
{
	$orderdata = explode(',',$_GET['bulk_weight_orders']);
	foreach($orderdata as $d)
	{
		$sql = "insert into clues_billing_exception_handling set order_id='".$d."', weight='".$_GET['bulkweight']."'";
		db_query($sql);
	}
	echo "success";
	exit;
}
if($_GET['comm'])
{
	$sql = "insert into clues_billing_exception_handling set order_id='".$_GET['order_id']."', item_id='".$_GET['item_id']."', commission='". $_GET['comm'] ."'";
	db_query($sql);
	echo "success";
	exit;
}

/*$sql = "select order_id from clues_billing_order_data";
$res = db_get_array($sql);
$str = '';
foreach($res as $r)
{
	if($str == '')
	{
		$str = "'" . $r['order_id'] . "'";
	}
	else
	{
		$str = $str . ",'" . $r['order_id'] . "'";
	}
}*/
$companycondition = "";
if($_GET['company'])
{
	$includeexclude = "in";
	if($_GET['includeexclude'])
	{
		$includeexclude = $_GET['includeexclude'];
	}
	$companycondition = " and co.company_id ".$includeexclude." (".$_GET['company'].")";
}
$sql = "select co.order_id,co.subtotal,co.subtotal_discount,co.discount,co.shipping_cost,

if(REPLACE(SUBSTRING(cd.`data`,INSTR(cd.data,'\"cost\";d:') + 9),';}','') is NULL,0,REPLACE(SUBSTRING(cd.`data`,INSTR(cd.data,'\"cost\";d:') + 9),';}','')) as cb,
(co.subtotal+co.shipping_cost-co.subtotal_discount-if(REPLACE(SUBSTRING(cd.`data`,INSTR(cd.data,'\"cost\";d:') + 9),';}','') is NULL,0,REPLACE(SUBSTRING(cd.`data`,INSTR(cd.data,'\"cost\";d:') + 9),';}',''))) as wecollect,

(select sum(amount) from cscart_order_details cod where cod.order_id=co.order_id) as cartcount,
cc.company,
co.company_id,
co.status,
DATE(FROM_UNIXTIME(co.timestamp)) orderDate,
co.payment_id

from cscart_orders co
left join cscart_order_data cd on cd.`order_id`=co.order_id and cd.type='I'
JOIN cscart_companies cc ON (co.company_id = cc.company_id) ".$companycondition."
where co.billing_done='N' and co.status in ('C','H') and FROM_UNIXTIME(co.timestamp) <= '".$date."'
order by order_id desc";
$str = '';
//echo $sql;exit;
$res = db_get_array($sql);
$res_merged = array();
$res_exc = array();
$company_no_comm_avail = array();
$company_no_ship_info = array();
$cnt = 0;
foreach($res as $row)
{
	$cnt++;
	if($cnt>1000)
	{
		//break;
	}
	$sql = "select shipment_id from cscart_shipment_items where order_id='". $row['order_id'] ."' group by shipment_id";
	$sh = db_get_array($sql);
	$row['shipments'] = $sh;
	$shid = '';
	foreach ($sh as $shs)
	{
		if($shid=='')
		{
			$shid = $shs['shipment_id'];
		}
		else
		{
			$shid = $shid . "," . $shs['shipment_id'];
		}
	}
	$wt = 0;
	$wgtnull = 1;
	if($shid != '')
	{
		$sql = "select css.weight as wgt from cscart_shipments css where css.shipment_id in (". $shid .")";
		$wgt = db_get_array($sql);
		foreach($wgt as $w)
		{
			if($w['wgt']!='')
			{
				$wgtnull = 0;
			}
			//echo $w['wgt'] . "<br>";
			if(strpos($w['wgt'],'k'))
			{
				$f = (float)$w['wgt'];
				$wt = $wt + ($f*1000);
			}
			else
			{
				$f = (float)$w['wgt'];
				$wt = $wt + $f;
			}
		}
	}
	
	$row['weight'] = $wt;
	$sql = "select weight from clues_billing_exception_handling where order_id='".$row['order_id']."' and weight is not NULL  order by id desc";
	$res_w = db_get_field($sql);
	if($res_w)
	{
		$row['weight'] = $res_w;
		$wt = $res_w;
		$wgtnull  = 0;
		$row['weight_exception'] = 1;
	}
	
	
	$ff_charg = 'error_no_company_rel';
	$sql = "select * from clues_billing_companies_shipping_rel where company_id='".$row['company_id']."'";
	$cmpship = db_get_row($sql);
	if(!empty($cmpship))
	{
		if($row['payment_id']==6)
		{
			$method = "cod";
		}
		else
		{
			$method = 'prepaid';
		}
		if($cmpship['table_rate_type'] != '')
		{
			if($wgtnull==0){
				$sql = "select fee_" . $method . " from clues_billing_shipping_table_rates where type='".$cmpship['table_rate_type']."' and `from`<=". $wt ." and `to`>=". $wt ."";
				$ff_charg = db_get_field($sql);
			}
			else
			{
				$ff_charg = 'weight null';
			}
			
		}
		else
		{
			$ff_charg = $cmpship['fee_' . $method . '_flatrate'];
		}
	}
	$row['fulfillment_charge'] = $ff_charg;
	$sql = "select d.product_id,d.item_id,d.amount,d.price,d.extra as product,d.transfer_price from cscart_order_details d where d.order_id='".$row['order_id']."'";
	$products = db_get_array($sql);
	$transferpriceset = 1;
	for($i=0;$i<count($products);$i++)
	{
		if((int)$products[$i]['transfer_price']==0)
		{
			$transferpriceset = 0;
		}
		$sql = "select ct.category_id,d.category from cscart_products_categories ct,cscart_category_descriptions d where ct.product_id='".$products[$i]['product_id']."' and ct.category_id=d.category_id";
		$cts = db_get_array($sql);
		$products[$i]['categories'] = $cts;
		$prd = unserialize($products[$i]['product']);
		$products[$i]['product'] = $prd['product'];
	}
	if(count($products)==0)
	{
		$transferpriceset = 0;
	}
	$row['products'] = $products;
	$sql = "select * from clues_billing_companies_commission where company_id='".$row['company_id']."'";
	$comm = db_get_array($sql);
	$commission = 'info unavailable';
	if(count($comm)==1)
	{
		$commission	= $comm[0]['selling_fee_rate'];
	}
	else if(count($comm)>1)
	{
		$commission = 'array';
		$row['commission_array'] = $comm;
	}
	
	
	// check exception table
	
	if($commission=="array")
	{
		$not_complete = 0;
		$prod_comm = array();
		for($i=0;$i<count($products);$i++)
		{
			if((int)$products[$i]['transfer_price']==0)
			{
				$sql = "select commission from clues_billing_exception_handling where order_id='".$row['order_id']."' and item_id='". $products[$i]['item_id'] ."' order by id desc";
				//if($row['order_id']=='999320')
				//{
					//echo $sql;	
				//}
				$comm_r = db_get_field($sql);
				if($comm_r)
				{
					$prod_comm[$products[$i]['item_id']] = $comm_r;
				}
				else
				{
					$not_complete = 1;
				}
			}
		}
		if(!$not_complete)
		{
			$commission = 'selected';
		}
		$row['commission_selected'] = $prod_comm;
	}
	$row['commission'] = $commission;
	
	$row['transferpriceset'] = $transferpriceset;
	
	
	
	//var_dump($row);
	if(($ff_charg=='error_no_company_rel' || $ff_charg=='weight null' || $commission=='info unavailable' || $commission == 'array') and !$transferpriceset)
	{
		$res_exc[] = $row;
	}
	else
	{
		
		$ship_tot = $row['shipping_cost'];
		$subtot = $row['subtotal'];
		
		$subtotpay = 0;
		$comm_arr = '';
		$selling_fee = 0;
		$ff_totfee = 0;
		$ff_fee = 0;
		$ff_tax = 0;
		$tp_shipping_our=0;
		for($i=0;$i<count($products);$i++)
		{
			if((int)$products[$i]['transfer_price'] == 0){
				$subt_p = $products[$i]['amount'] * $products[$i]['price'];
				$ship_p = (int)(($subt_p/$subtot)*$ship_tot);
				if($row['commission'] == "selected"){
					$comm_p = $row['commission_selected'][$products[$i]['item_id']];
				}
				else
				{
					$comm_p = $row['commission'];
				}
				$subt_p = $subt_p + $ship_p;
				$subtotpay = $subtotpay + ($subt_p - ($subt_p*$comm_p/100));
				$selling_fee = $selling_fee + ($subt_p*$comm_p/100);
				if($comm_arr=='')
				{
					$comm_arr =$comm_arr . "Product" . ($i+1) . ":" . $comm_p;
				}
				else
				{
					$comm_arr =$comm_arr . " | Product" . ($i+1) . ":" . $comm_p;
				}
			}
			else
			{
				$subt_p = $products[$i]['amount'] * $products[$i]['price'];
				$ship_p = (int)(($subt_p/$subtot)*$ship_tot);
				
				$tp_shipping_our = $tp_shipping_our + $ship_p;
				
				$subtotpay = $subtotpay + (int)($products[$i]['transfer_price']*$products[$i]['amount']);
				if($comm_arr=='')
				{
					$comm_arr =$comm_arr . "Product" . ($i+1) . ":T.P.";
				}
				else
				{
					$comm_arr =$comm_arr . " | Product" . ($i+1) . ":T.P.";
				}
			}
		}
		if($transferpriceset)
		{
			
		}
		else
		{
			$subtotpay = $subtotpay - ($ff_charg + ($ff_charg*12.36/100));
			$ff_totfee = ($ff_charg + ($ff_charg*12.36/100));
			$ff_fee = $ff_charg;
			$ff_tax = 12.36;
			
		}
		$row['tobepaid'] = (int)$subtotpay;
		$row['comm_arr'] = $comm_arr;
		$row['selling_fee'] = $selling_fee;
		$row['ff_fee'] = $ff_fee;
		$row['ff_totfee'] = $ff_totfee;
		$row['ff_tax'] = $ff_tax;
		$row['tp_shipping_our'] = $tp_shipping_our;
		$res_merged[] = $row;
		
	}
	if($commission == 'info unavailable' && !$transferpriceset)
	{
		$company_no_comm_avail[$row['company_id']] = array($row['company_id'],$row['company']);
	}
	if($ff_charg == 'error_no_company_rel' && !$transferpriceset)
	{
		$company_no_ship_info[$row['company_id']] = array($row['company_id'],$row['company']);
	}
}

if($_POST['billingcycle'])
{

	//echo $_POST['billingcycle'];
	foreach($res_merged as $res)
	{
		$cmpny = $res['company'];
		$cmpny = str_replace("'","''",$cmpny);
		$order_total = $res['subtotal'] + $res['shipping_cost'];
		$total_service_fee = $res['ff_totfee'] + $res['selling_fee'];
		$str_prod = '';
		$str_cat ='';
		
		foreach($res['products'] as $prod)
		{
			$catstr = '';
			foreach($prod['categories'] as $cat)
			{
				if($catstr=='')
				{
					$catstr = $cat['category'];
				}
				else
				{
					$catstr = $catstr . " | " .$cat['category'];
				}
			}
			if($str_prod == '')
			{
				$str_prod = "[" . $prod['product'] . "(" . $prod['amount'] . "*". $prod['price'] .") - " ."(TP:".$prod['transfer_price'].") Cat:". $catstr . "]";
			}
			else
			{
				$str_prod = $str_prod . " [" . $prod['product'] . "(" . $prod['amount'] . "*". $prod['price'] .") - " ."(TP:".$prod['transfer_price'].") Cat:". $catstr . "]";
			}
		}
		$str_prod = str_replace("'","''",$str_prod);
		
		
		$sql = "select sum(transfer_price*amount) from cscart_order_details where order_id='".$res['order_id']."'";
		$tp_sum = db_get_field($sql);
		//echo $tp_sum;
		$dp = $res['subtotal'] - (int)$res['subtotal_discount'];
		//echo "<br>". $dp; 
		$revenue_kind = "";
		$marketing_spend = 0;
		if($dp<$tp_sum)
		{
			$marketing_spend = $tp_sum-$dp;
			$revenue_kind = "DTP";
		}
		else
		{
			$marketing_spend = (int)$res['subtotal_discount']+(int)$res['discount'];
			if($tp_sum>0)
			{
				$revenue_kind = "RTP";
			}
			else
			{
				$revenue_kind = "RB";
			}
		}
		
		
		$sql = "insert into clues_billing_order_data set BillingKey='',
															 order_id='". $res['order_id'] ."',
															 orderDate='". $res['orderDate'] ."',
															 company='". $cmpny ."',
															 company_id='".$res['company_id']."',
															 status='". $res['status'] ."',
															 cartsize='". $res['cartcount'] ."',
															 products='". $str_prod ."',
															 categories='',
															 billing_category='',
															 payment_id='". $res['payment_id'] ."',
															 Weight='". $res['weight'] ."',
															 shipping_Cost='". $res['shipping_cost'] ."',
															 tp_shipping_our='". $res['tp_shipping_our'] ."',
															 discount = '".$res['subtotal_discount']."',
															 total='". $res['wecollect'] ."',
															 subtotal='". $res['subtotal'] ."',
															 order_total='". $order_total ."',
															 selling_fee_rate='". $res['comm_arr'] ."',
															 selling_fee='". $res['selling_fee'] ."',
															 fulfillment_service_fee='". $res['ff_fee'] ."',
															 fulfillment_service_tax='". $res['ff_tax'] ."',
															 total_fulfillment_fee='". $res['ff_totfee'] ."',
															 total_service_fee='". $total_service_fee ."',
															 net_payout='". $res['tobepaid'] ."',
															 billing_cycle='". $_POST['billingcycle'] ."',
															 payment_disbursed='',
															 payment_settled='',
															 notes='',
															 previous_billing_cycle='',
															 adjustments='',
															 clues_bucks='".$res['cb']."',
															 deal_price='".$dp."',
															 TP_sum='".$tp_sum."',
															 revenue_type='".$revenue_kind."',
															 marketing_spend='".$marketing_spend."'
															 ";
		db_query($sql);	
		$sql = "update cscart_orders set billing_done='Y' where order_id='". $res['order_id'] ."'";
		db_query($sql);												 
															 
	}
	$sql = "delete from clues_billing_payout_summary where billing_cycle='". $_POST['billingcycle'] ."'";
	db_query($sql);
	$sql = "select billing_cycle,company_id,company,
	sum(subtotal) as Sum_Selling_Price, 
	sum(order_total) as Sum_Order_Total,
	sum(selling_fee) as Sum_Selling_Fee,
	sum(total_fulfillment_fee) as Sum_Fulfillment_Service_Fee,
	sum(total_service_fee) as Sum_Total_Service_Fee,
	sum(net_payout) as Sum_Net_Payout			
	from clues_billing_order_data
	where billing_cycle='". $_POST['billingcycle'] ."'
	group by company_id
	";
	$r_s = db_get_array($sql);
	foreach($r_s as $rs)
	{
		$cmpny = $rs['company'];
		$cmpny = str_replace("'","''",$cmpny);
		$sql = "insert into clues_billing_payout_summary set billing_cycle='".$rs['billing_cycle']."',
																 merchant='".$cmpny."',
																 company_id='".$rs['company_id']."',
																 Sum_Selling_Price='".$rs['Sum_Selling_Price']."',
																 Sum_Order_Total='".$rs['Sum_Order_Total']."',
																 Sum_Selling_Fee='".$rs['Sum_Selling_Fee']."',
																 Sum_Fulfillment_Service_Fee='".$rs['Sum_Fulfillment_Service_Fee']."',
																 Sum_Total_Service_Fee='".$rs['Sum_Total_Service_Fee']."',
																 Sum_Net_Payout='".$rs['Sum_Net_Payout']."'";
		db_query($sql);														 
	}
	header('Location: billing_export_excel.php');
	exit;	
}
$sql = "select company_id,company from cscart_companies order by company";
$company_list = db_get_array($sql);
$getcompanyarray = array();
$getcompanyarray = explode(',',$_GET['company']);
?>
<title>Billing</title>
Till Date <input type="text" value="<?php echo $date?>" id="tilldate" /> <input type="button" value="change" onclick='changetilldate()' />

<input type="radio" name="includeexclude" value="in" checked="checked" id="radioin" /> In <input type="radio" name="includeexclude" value="not in" id="radionotin" /> Not In

Companies <select multiple="multiple" name="company" id="company">
			<?php foreach($company_list as $company){?>
            	<option value="<?php echo $company['company_id']?>" <?php if(in_array($company['company_id'] ,$getcompanyarray)){?> selected="selected"<?php }?>><?php echo $company['company']?> (<?php echo $company['company_id']?>)</option>
			<?php }?>
		  </select> <input type="button" name="filtercompany" value="filter" onclick="filtercompany()" />

<h2>Companies with no Commission info available</h2>
<?php
	foreach($company_no_comm_avail as $cmp)
	{
		echo $cmp[0] . " " . $cmp[1]."<br>";
	}
?>
<h2>Companies with no Shipping Info available</h2>
<?php
	foreach($company_no_ship_info as $cmp)
	{
		echo $cmp[0] . " " . $cmp[1]."<br>";
	}
?>
<script src="../lib/js/jquery/jquery.min.js"></script>
<script>
function filtercompany()
{
	includeexclude = "";
	if(document.getElementById('radioin').checked)
	{
		includeexclude = "in";
	}
	else
	{
		includeexclude = "not in";
	}
	
	
	var company = '';
	ele = document.getElementById('company');
	for(i=0;i<ele.options.length;i++)
	{
	 if (ele.options[i].selected)
		{
			if(company == '')
			{
				company = ele.options[i].value;
			}
			else
			{
				company = company + ',' + ele.options[i].value;
			}
		}
	}
	
	window.location = 'billing.php?till=' + document.getElementById('tilldate').value + '&company='+ company +'&includeexclude='+includeexclude;
}
function changetilldate()
{
	window.location = 'billing.php?till=' + document.getElementById('tilldate').value + '&company=<?php echo $_GET['company'];?>&includeexclude=<?php echo $_GET['includeexclude']?>';
}
function submitweight(order_id)
{
	if(!parseInt(jQuery('#weight_'+order_id).val()))
	{
		alert('enter a valid non zero numeric value');
	}
	else
	{
		$.ajax({
		  type: "GET",
		  url: "billing.php",
		  data: {order_id:order_id,weight:parseInt(jQuery('#weight_'+order_id).val())}
		}).done(function( msg ) {
		  if(msg=="success")
		  {
			  jQuery('#go_weight_'+order_id).hide();
			  alert('saved');
		  }
		  else
		  {
			  alert("error");
		  }
		});
	}
}
function submitcomm(item_id,order_id)
{
	if(!jQuery('#prod_' + item_id + '_' + order_id).val())
	{
		alert('select an option');
	}
	else
	{
		$.ajax({
		  type: "GET",
		  url: "billing.php",
		  data: {order_id:order_id,item_id:item_id,comm:jQuery('#prod_' + item_id + '_' + order_id).val()}
		}).done(function( msg ) {
		  if(msg=="success")
		  {
			  jQuery('#go_prod_' + item_id + '_' + order_id).hide();
			  alert('saved');
		  }
		  else
		  {
			  alert("error");
		  }
		});
	}
}
function submitcommall(productid)
{
	if(!jQuery('#prod_all_' + productid).val())
	{
		alert('select an option');
	}
	else
	{
		$.ajax({
		  type: "GET",
		  url: "billing.php",
		  data: {orderitemdata:jQuery('#prod_all_orders_' + productid).val(),commission:jQuery('#prod_all_' + productid).val()}
		}).done(function( msg ) {
		  if(msg=="success")
		  {
			  jQuery('#go_prod_all_' + productid).hide();
			  alert('saved');
		  }
		  else
		  {
			  alert("error");
		  }
		});
	}
}
function bulkweightupload(i)
{
	if(!jQuery('#weight-bulk-' + i).val())
	{
		alert('Enter Weight');
	}
	else
	{
		$.ajax({
		  type: "GET",
		  url: "billing.php",
		  data: {bulk_weight_orders:jQuery('#order-bulk-weight-' + i).val(),bulkweight:jQuery('#weight-bulk-' + i).val()}
		}).done(function( msg ) {
		  if(msg=="success")
		  {
			  jQuery('#btnbulkweightupload-' + i).hide();
			  alert('saved');
		  }
		  else
		  {
			  alert("error");
		  }
		});
	}
}
</script>

<h1>Billing</h1>
<h3>Exceptional orders(<?php echo count($res_exc)?>)</h3>
<div style="height:500px;overflow:scroll">
<table border="1">
    <tr style="background:#A3A39E">
    	<th>SR</th>
    	<th>order_id</th>
        <th>subtotal</th>
        <th>discount</th>
        <th>shipping_cost</th>
        <th>cb</th>
        <th style="background-color:green">wecollect</th>
        <th>weight</th>
        <th>fulfillment_charge</th>
        <th>commission</th>
        <th style="background-color:gray">to be paid</th>
        <th>products and categories</th>
        <th>cartcount</th>
        <th>company</th>
        <th>company_id</th>
        <th>status</th>
        <th>orderDate</th>
        <th>payment_id</th>
        
    </tr>
    <?php
	$i = 0;
	$col = '#fff';
	$rowcommselect = array();
	$rowweightnullselect = array();
	foreach($res_exc as $row)
	{
		$i++;
		if($col=='#fff')
		{
			$col = '#F2F1C9';
		}
		else
		{
			$col = '#fff';
		}
	?>
    <tr style='background:<?php echo $col;?>'>
    	<td><?php echo $i;?></td>
        <td><?php echo $row['order_id'];?></td>
        <td><?php echo $row['subtotal'];?></td>
        <td><?php echo $row['subtotal_discount'];?></td>
        <td><?php echo $row['shipping_cost'];?></td>
        <td><?php echo $row['cb'];?></td>
        <td style="background-color:green"><?php echo $row['wecollect'];?></td>
        <td>
		<?php 
		echo $row['weight'];
		?>
        </td>
        <td>
		<?php 
		echo $row['fulfillment_charge'];
		if($row['fulfillment_charge']=='weight null')
		{
			$strprodweight = "";
			foreach($row['products'] as $prod)
			{
				$strprodweight = $strprodweight . "[" . $prod['product'] . "(QTY:".$prod['amount'].")]";
			}
			$rowweightnullselect[$strprodweight]['orders'][] = $row['order_id'];
			echo "<br>";
		?>
        	<input type="text" name="weight" value="" style="width:50px" id="weight_<?php echo $row['order_id'];?>" />
            <input type="button" value="go" onclick="submitweight(<?php echo $row['order_id'];?>)" id="go_weight_<?php echo $row['order_id'];?>" />
		<?php
		}
        ?>
        </td>
        <td <?php if($row['commission']=='selected'){?> style="background-color:green"<?php }?>>
		<?php 
		if($row['commission']=='array' || $row['commission']=='selected')
		{
			$ip = 0;
			foreach($row['products'] as $prod)
			{
				$ip++;
				echo "<div>
					Product ".$ip.":";
					if($row['commission_selected'][$prod['item_id']])
					{
						echo "selected: ". $row['commission_selected'][$prod['item_id']] . "%";
					}
					else if((float)$prod['transfer_price']==0)
					{
						$rowcommselect[$prod['product_id']]['orders'][] = array($row['order_id'],$prod['item_id']);
						$rowcommselect[$prod['product_id']]['commission_array'] = $row['commission_array'];
						$rowcommselect[$prod['product_id']]['product'] = $prod['product'];
						$rowcommselect[$prod['product_id']]['company'] = $row['company'];
				?>
                	<select id="prod_<?php echo $prod['item_id']?>_<?php echo $row['order_id']?>">
                    	<option value="">--</option>
                        <?php foreach($row['commission_array'] as $commrow){?>
                        <option value="<?php echo $commrow['selling_fee_rate'];?>"><?php echo $commrow['billing_category'] . "(". $commrow['selling_fee_rate'] ."%)";?></option>
                        <?php }?>
                    </select>
                    <input type="button" value="go" onclick="submitcomm(<?php echo $prod['item_id']?>,<?php echo $row['order_id']?>)" id="go_prod_<?php echo $prod['item_id']?>_<?php echo $row['order_id']?>" />
                <?php
					}
					else
					{
						echo "<strong>T.P.</strong>";
					}
				echo "</div>";
			}
		}
		else
		{
			echo $row['commission'];
		}
		?>
        </td>
        <td style="background-color:gray">to be paid</td>
        <td>
		<?php
		foreach($row['products'] as $prod)
		{
			$catstr = '';
			foreach($prod['categories'] as $cat)
			{
				if($catstr=='')
				{
					$catstr = $cat['category'];
				}
				else
				{
					$catstr = $catstr . " | " .$cat['category'];
				}
			}
			echo "<div style='padding:3px;margin:3px;width:400px;background:#ccc'>
					<div style='float:left;width:300px;background:#ccc'>" . $prod['product_id'] . ": " . $prod['product'] . "(" . $prod['amount'] . "*". $prod['price'] .") - " ."(TP:".$prod['transfer_price'].")</div>
					<div style='float:left;width:100px;background:#999'>". $catstr ."</div>
					<div style='clear:both'></div>
				</div>";
		}
        ?>
        </td>
        <td><?php echo $row['cartcount'];?></td>
        <td><?php echo $row['company'];?></td>
        <td><?php echo $row['company_id'];?></td>
        <td><?php echo $row['status'];?></td>
        <td><?php echo $row['orderDate'];?></td>
        <td><?php echo $row['payment_id'];?></td>
    </tr>
    <?php	
	}
	?>
</table>
</div>
<div style="padding:10px;font-size:20px">
Please Choose Commissions
</div>
<div style="height:200px;overflow:auto;background-color:#ddd">
<?php
foreach($rowcommselect as $product_id => $row)
{
	$orderstr='';
	foreach($row['orders'] as $r)
	{
		if($orderstr=='')
		{
			$orderstr = $r[0] . ",". $r[1];
		}
		else
		{
			$orderstr = $orderstr . "|" . $r[0] . ",". $r[1];
		}
	}
?>
<div style="padding:10px">
<?php echo $row['product'] . "(Repeated:" . count($row['orders']) . ")";?><br />
<select id="prod_all_<?php echo $product_id?>">
    <option value="">--</option>
    <?php foreach($row['commission_array'] as $commrow){?>
    <option value="<?php echo $commrow['selling_fee_rate'];?>"><?php echo $commrow['billing_category'] . "(". $commrow['selling_fee_rate'] ."%)";?></option>
    <?php }?>
</select>
<input type="hidden" value="<?php echo $orderstr?>" id="prod_all_orders_<?php echo $product_id?>" />
<input type="button" value="go" onclick="submitcommall(<?php echo $product_id?>)" id="go_prod_all_<?php echo $product_id?>" />
</div>
<?php
}
?>
</div>
<div style="padding:10px;font-size:20px">
Please Enter Weights
</div>
<div style="height:200px;overflow:auto;background-color:#ddd;margin-bottom:15px">
<?php $cnt=0;foreach($rowweightnullselect as $product=>$orders){$cnt++;$ordertext = implode(",",$orders['orders']);?>
	<div style="padding:10px">
	<?php echo $product . "(Repeated:" . count($orders['orders']) . ")"?><br />
    <input type="text" value="" id="weight-bulk-<?php echo $cnt?>" /> 
    <input type="button" value="go" onclick="bulkweightupload(<?php echo $cnt?>)" id="btnbulkweightupload-<?php echo $cnt?>" />
    <input type="hidden" id="order-bulk-weight-<?php echo $cnt?>" value="<?php echo $ordertext;?>" />
    </div>
<?php }?>
</div>

<div style="padding:20px">
	<form name="frm" action="billing.php?till=<?php echo $_GET['till'] ?>&company=<?php echo $_GET['company'] ?>&includeexclude=<?php echo $_GET['includeexclude']?>" method="post">
	Billing Cycle <input type="text" value="" name="billingcycle" id="billingcycle" /> <input type="submit" name="submit" value="export" />
    </form>
</div>


<h3>calculated orders(<?php echo count($res_merged)?>)</h3>
<div style="height:500px;overflow:scroll">
<table border="1">
    <tr style="background:#A3A39E">
    	<th>SR</th>
    	<th>order_id</th>
        <th>subtotal</th>
        <th>discount</th>
        <th>shipping_cost</th>
        <th>cb</th>
        <th style="background-color:green">wecollect</th>
        <th>fulfillment_charge</th>
        <th>commission</th>
        <th>weight</th>
        <th style="background-color:gray">to be paid</th>
        <th>products and categories</th>
        <th>cartcount</th>
        <th>company</th>
        <th>company_id</th>
        <th>status</th>
        <th>orderDate</th>
        <th>payment_id</th>
        
    </tr>
    <?php
	$i = 0;
	$col = '#fff';
	foreach($res_merged as $row)
	{
		$i++;
		if($col=='#fff')
		{
			$col = '#F2F1C9';
		}
		else
		{
			$col = '#fff';
		}
	?>
    <tr style='background:<?php echo $col;?>'>
    	<td><?php echo $i;?></td>
        <td><?php echo $row['order_id'];?></td>
        <td><?php echo $row['subtotal'];?></td>
        <td><?php echo $row['subtotal_discount'];?></td>
        <td><?php echo $row['shipping_cost'];?></td>
        <td><?php echo $row['cb'];?></td>
        <td style="background-color:green"><?php echo $row['wecollect'];?></td>
        <td>
		<?php 
		echo $row['fulfillment_charge'] . "(+12.36%)";
		echo "=" . ($row['fulfillment_charge'] + ($row['fulfillment_charge']*12.36/100))
		?> 
		<?php if($row['transferpriceset']){echo "<B>TP</B>";}?>
        </td>
        <td>
		<?php 
		if($row['commission']=='array' || $row['commission']=='selected')
		{
			$ip = 0;
			foreach($row['products'] as $prod)
			{
				$ip++;
				echo "<div>
					Product ".$ip.":";
					if($row['commission_selected'][$prod['item_id']])
					{
						echo "selected: ". $row['commission_selected'][$prod['item_id']] . "%";
					}
					else if((float)$prod['transfer_price']==0)
					{
				?>
                	<select id="prod_<?php echo $prod['item_id']?>_<?php echo $row['order_id']?>">
                    	<option value="">--</option>
                        <?php foreach($row['commission_array'] as $commrow){?>
                        <option value="<?php echo $commrow['selling_fee_rate'];?>"><?php echo $commrow['billing_category'] . "(". $commrow['selling_fee_rate'] ."%)";?></option>
                        <?php }?>
                    </select>
                    <input type="button" value="go" onclick="submitcomm(<?php echo $prod['item_id']?>,<?php echo $row['order_id']?>)" id="go_prod_<?php echo $prod['item_id']?>_<?php echo $row['order_id']?>" />
                <?php
					}
					else
					{
						echo "<strong>T.P.</strong>";
					}
				echo "</div>";
			}
		}
		else
		{
			echo $row['commission'];
		}
		?>
		</td>
        <td><?php echo $row['weight'];?></td>
        <th style="background-color:gray">
        <?php
			echo $row['tobepaid'];
		?>
        </th>
        <td>
		<?php
		foreach($row['products'] as $prod)
		{
			$catstr = '';
			foreach($prod['categories'] as $cat)
			{
				if($catstr=='')
				{
					$catstr = $cat['category'];
				}
				else
				{
					$catstr = $catstr . " | " .$cat['category'];
				}
			}
			echo "<div style='padding:3px;margin:3px;width:400px;background:#ccc'>
					<div style='float:left;width:300px;background:#ccc'>" . $prod['product'] . "(" . $prod['amount'] . "*". $prod['price'] .") - " ."(TP:".$prod['transfer_price'].")</div>
					<div style='float:left;width:100px;background:#99billingcycle9'>". $catstr ."</div>
					<div style='clear:both'></div>
				</div>";
		}
        ?>
        </td>
        <td><?php echo $row['cartcount'];?></td>
        <td><?php echo $row['company'];?></td>
        <td><?php echo $row['company_id'];?></td>
        <td><?php echo $row['status'];?></td>
        <td><?php echo $row['orderDate'];?></td>
        <td><?php echo $row['payment_id'];?></td>
    </tr>
    <?php	
	}
	?>
</table>
</div>