{* $Id: milkrun_report_full.tpl 9728 2010-06-07 10:58:27Z angel $ *}

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
{literal}
<style type="text/css" media="print">
.main-table {background-color: #ffffff !important;}
</style>
<style type="text/css" media="screen,print">
body,p,div,td {	color: #000000;	font: 10px Arial;}
body {padding: 0;margin: 0;}
a, a:link, a:visited, a:hover, a:active {color: #000000;text-decoration: underline;}
a:hover {text-decoration: none;}
border{1px;}
td{border:1pt;}
</style>
{/literal}
</head>

<body>
<table border="0" cellpadding="8" width="100%" style="border-collapse:collapse; border:0px solid #999;">

	<tr>
    	<th>Sl.</th>
        <th>Merchant Name</th>
        <th>Merchant Address</th>
        <th>Product Title</th>
        <th>Qty</th>
        <th>Mer. SKU</th>
        <th>Bar Code</th>
        <th>Mer. Region</th>
    </tr>
    {if $report_rows|count > 0}
    	{assign var="sno" value="1}
        {foreach from=$report_rows item=report_row}
        	<tr>
            	<td>{$sno}</td>
                <td style="border:normal;">{$report_row.company}</td>
                <td>
                	{if $report_row.address}{$report_row.address}{/if}
					{if $report_row.city},<br />{$report_row.city}{/if}
                    {if $report_row.state},{$report_row.state}{/if}
					{if $report_row.country},<br />{$report_row.country}{/if}
                    {if $report_row.zipcode}-{$report_row.zipcode}{/if}
                </td>
            	<td>
                {$report_row.product}
                {assign var="order_opt" value=$report_row.order_id|fn_get_order_info}
                {foreach from=$order_opt.items item=pro_opt}
                    {if $report_row.product_code == $pro_opt.product_code}
                     	{if $pro_opt.product_options}
                        	{foreach from=$pro_opt.product_options item=option}
                            	<br><b>{$option.option_name} :</b>  {$option.variant_name}
                            {/foreach}
                        {/if}   
                    {/if}
                {/foreach}
                </td>
                <td>{$report_row.amount}</td>
                <td>{$report_row.merchant_reference_number}</td>
                <td>SCIN<div class="center margin-top"><img src="{"image.barcode?id=`$report_row.product_code`&type=`$addons.barcode.type`&width=`$addons.barcode.width`&height=`$addons.barcode.height`&xres=`$addons.barcode.resolution`&font=`$addons.barcode.text_font`"|fn_url}" alt="BarCode" width="{$addons.barcode.width}" height="{$addons.barcode.height}" /></div>

				SC-ORDER<div class="center margin-top"><img src="{"image.barcode?id=`$report_row.order_id`&type=`$addons.barcode.type`&width=`$addons.barcode.width`&height=`$addons.barcode.height`&xres=`$addons.barcode.resolution`&font=`$addons.barcode.text_font`"|fn_url}" alt="BarCode" width="{$addons.barcode.width}" height="{$addons.barcode.height}" /></div>
                </td>
                <td>{$report_row.state}</td>
            </tr>
            {assign var="sno" value=$sno+1}
			<tr><td colspan="8"><hr></hr></td></tr>
        {/foreach}
    {/if}
</table>
</body>
</html>
