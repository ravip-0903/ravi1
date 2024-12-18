{* $Id: export_order_list.tpl 12544 2011-05-27 10:34:19Z bimib $ *}
<!--<pre>
{$order_info|print_r}-->
{literal}
<style type="text/css">
.sortable th{ padding: 0px 5px;}
.sortable td, .sortable tr:hover td{ border-right: 1px solid #e4e4e4;}
</style>
{/literal}
<h1>Export Order Details</h1>

<form method="get" action="{""|fn_url}" name="order_export_form" id="order_export_form">
<input type="hidden" name="mode_action" value="export" />
<span class="submit-button cm-button-main cm-process-items cm-new-window">
<input type="submit" value="Export To CSV" name="dispatch[export_orders.export_orders_list]" class="cm-process-items cm-new-window" /></span>
</form>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="table sortable">
<tr>
	<!--<th width="1%" class="center">
		<input type="checkbox" name="check_all" value="Y" title="{$lang.check_uncheck_all}" class="checkbox cm-check-items" /></th> -->
	<th width="5%">Order No.</th>
	<th width="10%">Order Status</th>
	<th width="20%">Product Details</th>
	<th width="10%">Buyer Name</th>
    <!-- Add By Paresh -->
    <th width="15%">Shipping Address
	</th><!--END Add By Paresh -->
	<th width="15%">Shipping City
	</th>
	<th width="15%">Shipping State</th>
    <th width="15%">Shipping Pincode</th>
    <th width="10%">Buyer Phone No.</th>
    <th width="10%">Item Count</th>
    <th width="10%">Payment Type</th>
    <th width="10%">Order SubTotal</th>
    <th width="10%">Collectible Amount</th>
    <th width="10%">Merchant SKU</th>
    <th width="10%">Shipment ID</th>
    <th width="10%">Tracking No</th>
    <th width="10%">Carrier Name</th>
    <th width="10%">Merchant Name</th>
	<th>&nbsp;</th>
</tr>
{foreach from=$order_info item="order_details" key="k"}
    {assign var="oid" value=$order_details.order_id}

{hook name="orders:order_row"}
<tr {cycle values="class=\"table-row\", "}>
	<!--<td class="center" width="5%">
		<input type="checkbox" name="order_ids[]" value="{$o.id}" class="checkbox cm-item" /></td> -->
	<td>
		{$order_details.order_id}
	</td>
	<td>
		{$status_name.$oid.description}
	</td>
    <td>
		<!--{foreach from=$order_details.items item="items"}
        	{$items.product} {$items.price}
        {/foreach}-->
        {$prod_details.$k.$oid.prod_detail}
    </td>
	<td>
		{$order_details.b_firstname} {$order_details.b_lastname}
	</td><!-- Add By Paresh -->
    <td class="nowrap">
		{$order_details.s_address} {$order_details.s_address2}
        </td>
	<td class="nowrap">
		{$order_details.s_city} 
        </td>
	<td >
		{$order_details.s_state_descr} 
    </td>
    <td >
		{$order_details.s_zipcode} 
    </td>
    <td>
		{$order_details.b_phone} 
    </td>
    
    <td>
		{$order_details.items|count}
    </td>
    <td>
		{$order_details.payment_method.payment}
    </td>
    <td>
		{$order_details.subtotal|number_format:2:".":","}
    </td>
    <td>
		{if $order_details.payment_method.payment_id == '6'}
		{$order_details.total}
        {else}
        0.00
        {/if}
    </td>
     <td>
		{$prod_merchant_no.$k.$oid.merchant_no}
    </td>
    <td>
		{$order_info.$k.$oid.ship_id}
    </td>
     <td>
		{$order_info.$k.$oid.track_no}
    </td>
    <td>
		{$order_info.$k.$oid.carrier}
    </td>
    <td>
		{$merchant_name.$oid}
    </td>
</tr>
{/hook}


{/foreach}
</table>
