{* $Id: reports.tpl 12178 2011-04-06 12:14:37Z bimib $ *}
{script src="lib/amcharts/swfobject.js"}
<pre>{$report_rows|print_r}</pre>
<div id="content_{$report.report_id}">
<table border="1">
	<tr>
    	<th>Sl.</th>
        <th>Product Title</th>
        <th>Qty</th>
        <th>Merchant SKU</th>
        <th>Shopclues SCIN</th>
        <th>Product Bar Code</th>
        <th>Order Number</th>
        <th>Order Bar Code</th>
        <th>3PL Provider</th>
    </tr>
    {if $report_rows|count > 0}
    	{assign var="sno" value="1}
        {foreach from=$report_rows item=report_row}
        	<tr>
            	<td>{$sno}</td>
            	<td>{$report_row.product}</td>
                <td>{$report_row.amount}</td>
                <td>{$report_row.product_code}</td>
            	<td></td>
                <td><div class="center margin-top"><img src="{"image.barcode?id=`$report_row.product_id`
&type=`$addons.barcode.type`&width=`$addons.barcode.width`&height=`$addons.barcode.height`&xres=`$addons.barcode.resolution`&font=`$addons.barcode.text_font`"|fn_url}" alt="BarCode" width="{$addons.barcode.width}" height="{$addons.barcode.height}" /></div></td>
                <td>{$report_row.order_id}</td>
            	<td><div class="center margin-top"><img src="{"image.barcode?id=`$report_row.order_id`
&type=`$addons.barcode.type`&width=`$addons.barcode.width`&height=`$addons.barcode.height`&xres=`$addons.barcode.resolution`&font=`$addons.barcode.text_font`"|fn_url}" alt="BarCode" width="{$addons.barcode.width}" height="{$addons.barcode.height}" /></div></td>
                <td></td>
            </tr>
            {assign var="sno" value=$sno+1}
        {/foreach}
    {/if}
</table>
<!--content_{$report.report_id}--></div>
