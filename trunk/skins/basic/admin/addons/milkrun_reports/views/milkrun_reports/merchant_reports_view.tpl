{* $Id: reports.tpl 12178 2011-04-06 12:14:37Z bimib $ *}

{script src="lib/amcharts/swfobject.js"}

<a href="UniTechCity.php?time_from={$time_from}&time_to={$time_to}&company_id={$company_id}&dispatch[milkrun_reports.reports]=search&order_by={$order_by}&report_type={$report_type}&format=pdf" target="_blank">Export as pdf</a>


<div id="content_{$report.report_id}">

{if $company_data}

<table width="100%" cellpadding="0">
	<tr>
    	<td><b>Merchant Name:</b> {$company_data.company}<br />
			{assign var="comp_country_name" value=$company_data.country|fn_get_country_name}
            <b>Address:</b> {$company_data.address},{$company_data.city},{$company_data.state},{$comp_country_name}<br />
			 <strong>phone:</strong>{$company_data.phone}<br />
            {if $time_from}<b>Time From:</b> {$time_from}<br />{/if}
            {if $time_to}<b>Time To:</b> {$time_to}<br />{/if}
            {if $total_order_counts}<b>Order Count:</b> {$total_order_counts.total_order_count}<br />{/if}
            {if $total_product_counts}<b>Product Count:</b> {$total_product_counts.total_product_count}<br />{/if}
       	</td>            
    </tr>
</table>
{/if}

<h3>Summary Product Pickup Table</h3>
<table border="1">
	<tr>
    	<th>Product CODE</th>
        <th>Merchant SKU</th>
        <th>Name</th>
        <th>QTY</th>
    </tr>
    {foreach from=$product_summary_rows item=product_summary_row}
    	<tr>
        	<td>{$product_summary_row.product_code}</td>
            <td>{$product_summary_row.merchant_reference_number}</td>
            <td>{$product_summary_row.product}</td>
            <td>{$product_summary_row.qty}</td>
        </tr>
    {/foreach}
</table><br />
<table border="1" cellpadding="7" width="100%" style="border-collapse:collapse; border:1px solid #999;">

	<tr>

    	<th>Sl.</th>

        <th>Product Title</th>

        <th>Buyer Name</th>

        <th>Address</th>

        <th>Qty</th>

        <th>Merchant SKU</th>

        <th width="30%">Bar Code</th>

    </tr>

    

    {if $report_rows|count > 0}

    	{assign var="sno" value="1}

        {foreach from=$report_rows item=report_row}

        	<tr>

            	<td>{$sno}</td>

            	<td>

                {$report_row.product}

                {assign var="order_opt" value=$report_row.order_id|fn_get_order_info}

                {foreach from=$order_opt.items item=pro_opt}
                	
					{if $report_row.product_id == $pro_opt.product_id}

                     	{if $pro_opt.product_options}

                        	{foreach from=$pro_opt.product_options item=option}

                            	<br><b>{$option.option_name} :</b>  {if $option.variant_name}{$option.variant_name}{else}Check the file{/if}

                            {/foreach}

                        {/if}   

                    {/if}

                {/foreach}

                <br><b>Price : </b> {$report_row.price}
                {if $report_row.notes}<br><b>Customer Notes : </b> {$report_row.notes} {/if}

                </td>

                <td>{$report_row.s_firstname} &nbsp; {$report_row.s_lastname}</td>

                <td>

                	{assign var="state_name" value=$report_row.s_state|fn_get_state_name:$report_row.s_country}

                    {assign var="country_name" value=$report_row.s_country|fn_get_country_name}

                    {$report_row.s_address},<br>

					{$report_row.s_address_2},<br>

					{$report_row.s_city},{$state_name},<br>

                    {$country_name}-{$report_row.s_zipcode}

                </td>

                <td>{$report_row.amount}</td>

                <td>{$report_row.merchant_reference_number}</td>

                <td>
                	SCIN-No. : {$report_row.product_code|default:"Not Available"}<br />
                    Order ID : {$report_row.order_id}
                 	<div class="center margin-top">
                <img src="{"image.barcode?id=`$report_row.order_id`
&type=`$addons.barcode.type`&width=`$addons.barcode.width`&height=`$addons.barcode.height`&xres=`$addons.barcode.resolution`&font=`$addons.barcode.text_font`"|fn_url}" alt="BarCode" width="160" height="60" /></div>
                </td>

            </tr>

            {assign var="sno" value=$sno+1}

        {/foreach}

    {/if}

</table>

<!--content_{$report.report_id}--></div>

