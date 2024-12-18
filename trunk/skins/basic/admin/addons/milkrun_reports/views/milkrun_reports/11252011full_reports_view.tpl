{* $Id: reports.tpl 12178 2011-04-06 12:14:37Z bimib $ *}
{script src="lib/amcharts/swfobject.js"}

<div id="content_{$report.report_id}">
<table border="1">
	<tr>
    	<th>Sl.</th>
        <th>Merchant Name</th>
        <th>Merchant Address</th>
        <th>Product Title</th>
        <th>Qty</th>
        <th>Merchant SKU</th>
        <th>Shopclues SCIN</th>
        <th>Bar Code</th>
        <th>Order Number</th>
       <!-- <th>Order Bar Code</th>-->
        <th>Merchant Region</th>
        <th>3PL Provider</th>
    </tr>
    {if $report_rows|count > 0}
    	{assign var="sno" value="1}
        {foreach from=$report_rows item=report_row}
        	<tr>
            	<td>{$sno}</td>
                <td>{$report_row.company}</td>
                <td>{$report_row.address}</td>
            	<td>{$report_row.product}</td>
                <td>{$report_row.amount}</td>
                <td>{$report_row.merchant_reference_number}</td>
            	<td>{$report_row.product_code}</td>
                <td>SCIN<div class="center margin-top"><img src="{"image.barcode?id=`$report_row.product_code`
&type=`$addons.barcode.type`&width=`$addons.barcode.width`&height=`$addons.barcode.height`&xres=`$addons.barcode.resolution`&font=`$addons.barcode.text_font`&bar_type=pcode"|fn_url}" alt="BarCode" width="{$addons.barcode.width}" height="{$addons.barcode.height}" /></div>

				SC-ORDER<div class="center margin-top"><img src="{"image.barcode?id=`$report_row.order_id`
&type=`$addons.barcode.type`&width=`$addons.barcode.width`&height=`$addons.barcode.height`&xres=`$addons.barcode.resolution`&font=`$addons.barcode.text_font`"|fn_url}" alt="BarCode" width="{$addons.barcode.width}" height="{$addons.barcode.height}" /></div>
                </td>
                <td>{$report_row.order_id}</td>
            	<!--<td>SC-ORDER<div class="center margin-top"><img src="{"image.barcode?id=`$report_row.order_id`
&type=`$addons.barcode.type`&width=`$addons.barcode.width`&height=`$addons.barcode.height`&xres=`$addons.barcode.resolution`&font=`$addons.barcode.text_font`"|fn_url}" alt="BarCode" width="{$addons.barcode.width}" height="{$addons.barcode.height}" /></div></td>-->
                <td>{$report_row.state}</td>
                <td>{$report_row.carrier}</td>
            </tr>
            {assign var="sno" value=$sno+1}
        {/foreach}
    {/if}
</table>
<!--content_{$report.report_id}--></div>
