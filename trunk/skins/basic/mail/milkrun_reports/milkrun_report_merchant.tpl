{* $Id: milkrun_report_merchant.tpl 9728 2010-06-07 10:58:27Z angel $ *}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
{literal}
<style type="text/css" media="print,screen">
body, p, div .content{
    color: #000000;
    font-family: Tahoma,Arial,Verdana,Helvetica sans-serif;
    font-size: 12px;
	background-color:#FFF;
}
table {
	border-left:1px solid #000;
	border-top:1px solid #000;
}
th td {
	border-right:1px solid #000;
	border-bottom:1px solid #000;
}
</style>
{/literal}
</head>
<body onload="javascript:window.print();">
<div id="content_{$report.report_id}" class="content" 
style="font-family: Tahoma,Arial,Verdana,Helvetica sans-serif;font-size:12px;">
{if $company_data}
<br />
<table  width="100%" cellpadding="0" style="border-left:1px solid #000;border-top:1px solid #000;">
	<tr>
    	<td style="border-right:1px solid #000;border-bottom:1px solid #000;font-weight:normal;font-size:9px;font-family: Tahoma,Arial,Verdana,Helvetica sans-serif;">
			<b>Merchant Name: </b>{$company_data.company}<br />
			{assign var="comp_country_name" value=$company_data.country|fn_get_country_name}
            <b>Address: </b>{$company_data.address},{$company_data.city},{$company_data.state},{$comp_country_name}<br />
			<b>Phone: </b>{$company_data.phone}<br />
            {if $time_from}<b>Time From: </b>{$time_from}<br />{/if}
            {if $time_to}<b>Time To: </b>{$time_to}<br />{/if}
            {if $total_order_counts}<b>Order Count: </b>{$total_order_counts.total_order_count}<br />{/if}
            {if $total_product_counts}<b>Product Count: </b>{$total_product_counts.total_product_count}<br />{/if}
       	</td> 
    </tr>
</table>
{/if}                                           
<h3 style="font-family: Tahoma,Arial,Verdana,Helvetica sans-serif;">Summary Product Pickup Table</h3>
<table style="border-left:1px solid #000;border-top:1px solid #000;" width="45%" cellpadding="0">
	<tr>
    	<th style="border-right:1px solid #000;border-bottom:1px solid #000;font-size:9px;font-family: Tahoma,Arial,Verdana,Helvetica sans-serif;">Product CODE</th>
        <th style="border-right:1px solid #000;border-bottom:1px solid #000;font-size:9px;font-family: Tahoma,Arial,Verdana,Helvetica sans-serif;">Merchant SKU</th>
        <th style="border-right:1px solid #000;border-bottom:1px solid #000;font-size:9px;font-family: Tahoma,Arial,Verdana,Helvetica sans-serif;">Name</th>
        <th style="border-right:1px solid #000;border-bottom:1px solid #000;font-size:9px;font-family: Tahoma,Arial,Verdana,Helvetica sans-serif;">QTY</th>
    </tr>
    {foreach from=$product_summary_rows item=product_summary_row}
    	<tr>
		<td style="border-right:1px solid #000;border-bottom:1px solid #000;font-weight:normal;font-size:9px;font-family: Tahoma,Arial,Verdana,Helvetica sans-serif;">
		{$product_summary_row.product_code}</td>
		<td style="border-right:1px solid #000;border-bottom:1px solid #000;font-weight:normal;font-size:9px;font-family: Tahoma,Arial,Verdana,Helvetica sans-serif;">
		{$product_summary_row.merchant_reference_number}</td>
		<td style="border-right:1px solid #000;border-bottom:1px solid #000;font-weight:normal;font-size:9px;font-family: Tahoma,Arial,Verdana,Helvetica sans-serif;">
		{$product_summary_row.product}</td>
		<td style="border-right:1px solid #000;border-bottom:1px solid #000;font-weight:normal;font-size:9px;font-family: Tahoma,Arial,Verdana,Helvetica sans-serif;">
		{$product_summary_row.qty}</td>
        </tr>
    {/foreach}
</table>
<br />
<table style="border-left:1px solid #000;border-top:1px solid #000;font-size:9px;font-family: Tahoma,Arial,Verdana,Helvetica sans-serif;" cellpadding="0" width="100%">
	<tr>
    	<th style="border-right:1px solid #000;border-bottom:1px solid #000;font-size:9px;font-family: Tahoma,Arial,Verdana,Helvetica sans-serif;" width="5%">SI.</th>
        <th style="border-right:1px solid #000;border-bottom:1px solid #000;font-size:9px;font-family: Tahoma,Arial,Verdana,Helvetica sans-serif;" width="25%">Product Title</th>
        <th style="border-right:1px solid #000;border-bottom:1px solid #000;font-size:9px;font-family: Tahoma,Arial,Verdana,Helvetica sans-serif;" width="20%">Buyer Name</th>
        <th style="border-right:1px solid #000;border-bottom:1px solid #000;font-size:9px;font-family: Tahoma,Arial,Verdana,Helvetica sans-serif;" width="20%">Address</th>
        <th style="border-right:1px solid #000;border-bottom:1px solid #000;font-size:9px;font-family: Tahoma,Arial,Verdana,Helvetica sans-serif;" width="5%">Qty</th>
        <th style="border-right:1px solid #000;border-bottom:1px solid #000;font-size:9px;font-family: Tahoma,Arial,Verdana,Helvetica sans-serif;" width="25%">Bar Code</th>
    </tr>
    
    {if $report_rows|count > 0}
    	{assign var="sno" value="1}
        {foreach from=$report_rows item=report_row}
        	<tr>
            	<td style="border-right:1px solid #000;border-bottom:1px solid #000;font-weight:normal;font-size:9px;font-family: Tahoma,Arial,Verdana,Helvetica sans-serif;">{$sno}</td>
            	<td style="border-right:1px solid #000;border-bottom:1px solid #000;font-weight:normal;font-size:9px;font-family: Tahoma,Arial,Verdana,Helvetica sans-serif;">
                    {$report_row.product}
                    {assign var="order_opt" value=$report_row.order_id|fn_get_order_info}
                    {foreach from=$order_opt.items item=pro_opt}
                        {if $report_row.product_code == $pro_opt.product_code}
                            {if $pro_opt.product_options}
                                {foreach from=$pro_opt.product_options item=option}
                                    <br><b>{$option.option_name}: </b>{$option.variant_name}
                                {/foreach}
                            {/if}   
                        {/if}
                    {/foreach}
                    <br><b>Price: </b>{$report_row.subtotal}
                    {if $report_row.notes}<br><b>Customer Notes: </b>{$report_row.notes} {/if}
                    {if $report_row.merchant_reference_number}<br><b>Mer. SKU:</b> {$report_row.merchant_reference_number} {/if}
                </td>
                <td style="border-right:1px solid #000;border-bottom:1px solid #000;font-weight:normal;font-size:9px;font-family: Tahoma,Arial,Verdana,Helvetica sans-serif;">
				{$report_row.s_firstname} &nbsp; {$report_row.s_lastname}
				</td>
                <td style="border-right:1px solid #000;border-bottom:1px solid #000;font-weight:normal;font-size:9px;font-family: Tahoma,Arial,Verdana,Helvetica sans-serif;">
                	{assign var="state_name" value=$report_row.s_state|fn_get_state_name:$report_row.s_country}
                    {assign var="country_name" value=$report_row.s_country|fn_get_country_name}
                    {$report_row.s_address},<br>
					{$report_row.s_address_2},<br>
					{$report_row.s_city},{$state_name},<br>
                    {$country_name}-{$report_row.s_zipcode}
                </td>
                <td style="border-right:1px solid #000;border-bottom:1px solid #000;font-weight:normal;font-size:9px;font-family: Tahoma,Arial,Verdana,Helvetica sans-serif;">{$report_row.amount}</td>
              <td style="border-right:1px solid #000;border-bottom:1px solid #000;font-weight:normal;font-size:9px;font-family: Tahoma,Arial,Verdana,Helvetica sans-serif;">
                    SCIN-No. : {$report_row.product_code}<br />
                    Order ID : {$report_row.order_id}
               	<br/>
                <!--<img src="{"image.barcode?id=`$report_row.order_id`&type=`$addons.barcode.type`&width=200&height=80&xres`$addons.barcode.resolution`&font=`$addons.barcode.text_font`"|fn_url}" alt="BarCode" width="130" height="30" />-->
                <img src="../images/barcodes/{$report_row.order_id|cat:".png"}" alt="BarCode" width="130" height="30" />
                </td>
            </tr>
            {assign var="sno" value=$sno+1}
        {/foreach}
    {/if}
</table>
<!--content_{$report.report_id}--></div>
</body>
</html>