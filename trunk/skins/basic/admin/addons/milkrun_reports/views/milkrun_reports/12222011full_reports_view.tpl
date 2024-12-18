{* $Id: reports.tpl 12178 2011-04-06 12:14:37Z bimib $ *}
{script src="lib/amcharts/swfobject.js"}

<!--{include file="buttons/button.tpl" but_text="Export Report" but_href="superadmin.php?time_from=`$time_from`&time_to=`$time_to`&dispatch[milkrun_reports.reports]=search&order_by=`$order_by`&report_type=`$report_type`&format=pdf" but_role="tool"}-->

<div id="content_{$report.report_id}">
<table border="1" cellpadding="7" width="100%" style="border-collapse:collapse; border:1px solid #999;">
	<tr>
    	<th>Sl.</th>
        <th>Merchant Name</th>
        <th>Merchant Address</th>
        <th>Product Title</th>
        <th>Qty</th>
        <th>Merchant SKU</th>
        <th width="30%">Bar Code</th>
        <th>Merchant Region</th>
    </tr>
    {if $report_rows|count > 0}
    	{assign var="sno" value="1}
        {foreach from=$report_rows item=report_row}
        	<tr>
            	<td>{$sno}</td>
                <td>{$report_row.company}</td>
                <td>
                	{if $report_row.address}{$report_row.address}{/if}
					{if $report_row.city},<br />{$report_row.city}{/if}
                    {if $report_row.state},{$report_row.state}{/if}
					{if $report_row.country},<br />{$report_row.country}{/if}
                    {if $report_row.zipcode}-{$report_row.zipcode}{/if}
                </td>
            	<td>{$report_row.product}</td>
                <td>{$report_row.amount}</td>
                <td>{$report_row.merchant_reference_number}</td>
                <td>SCIN<div class="center margin-top"><img src="{"image.barcode?id=`$report_row.product_code`
&type=`$addons.barcode.type`&width=`$addons.barcode.width`&height=`$addons.barcode.height`&xres=`$addons.barcode.resolution`&font=`$addons.barcode.text_font`"|fn_url}" alt="BarCode" width="{$addons.barcode.width}" height="{$addons.barcode.height}" /></div>

				SC-ORDER<div class="center margin-top"><img src="{"image.barcode?id=`$report_row.order_id`
&type=`$addons.barcode.type`&width=`$addons.barcode.width`&height=`$addons.barcode.height`&xres=`$addons.barcode.resolution`&font=`$addons.barcode.text_font`"|fn_url}" alt="BarCode" width="{$addons.barcode.width}" height="{$addons.barcode.height}" /></div>
                </td>
                <td>{$report_row.state}</td>
            </tr>
            {assign var="sno" value=$sno+1}
        {/foreach}
    {/if}
</table>
<!--content_{$report.report_id}--></div>
