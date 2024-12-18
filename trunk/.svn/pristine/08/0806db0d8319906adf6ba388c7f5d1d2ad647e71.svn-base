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
<input type="submit" value="Export To CSV" name="dispatch[export_orders.export_rma_list]" class="cm-process-items cm-new-window" /></span>
</form>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="table sortable">
<tr>
	<!--<th width="1%" class="center">
		<input type="checkbox" name="check_all" value="Y" title="{$lang.check_uncheck_all}" class="checkbox cm-check-items" /></th> -->
	<th width="5%">Return ID</th>
	<th width="10%">RMA Date</th>
    <th width="10%">RMA Status</th>
    <th width="10%">Order ID</th>
    <th width="10%">Order Date</th>
	<th width="20%">Order Status</th>
    <th width="20%">Action</th>
    <th width="20%">Company</th>
    <th width="10%">Buyer</th>
    <th width="10%">Shipping Address</th>
    <th width="10%">Zipcode</th>
    <th width="10%">Contact</th>
    <th width="10%">Products</th>
    <th width="10%">Total Qty</th>
    <th width="10%">Comment</th>
</tr>
{foreach from=$order_info item="order_details" key="k"}
    {assign var="oid" value=$order_details.order_id}

{hook name="orders:order_row"}
<tr {cycle values="class=\"table-row\", "}>
	<!--<td class="center" width="5%">
		<input type="checkbox" name="order_ids[]" value="{$o.id}" class="checkbox cm-item" /></td> -->
	<td>
		{$order_details.return_id}
	</td>
    <td>
		{$order_details.rma_date|date_format:"%d %b %Y"}
	</td>
    <td>
		{$order_details.rma_status}
	</td>
	<td>
		{$order_details.order_id}
	</td>
    <td>
		{$order_details.order_date|date_format:"%d %b %Y"}
	</td>
    <td>
		{$order_details.order_status}
	</td>
    <td>
		{$order_details.action}
	</td>
    <td>
    	{$order_details.company}
    </td>
    <td>
		{$order_details.buyer}
	</td>
    <td>
		{$order_details.shipping_address}
	</td>
    <td>
		{$order_details.s_zipcode}
	</td>
    <td>
		{$order_details.s_phone}
	</td>
    <td>
		{$order_details.products}
	</td>
    <td>
		{$order_details.total_qty}
	</td>
    <td>
    	{$order_details.comment}
    </td>
</tr>
{/hook}


{/foreach}
</table>
