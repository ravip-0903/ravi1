{* $Id: export_order_list.tpl 12544 2011-05-27 10:34:19Z bimib $ *}
<!--<pre>
{$order_info|print_r}-->
{literal}
<style type="text/css">
.sortable th{ padding: 0px 5px;}
.sortable td, .sortable tr:hover td{ border-right: 1px solid #e4e4e4;}
ul.order_type li{ width: 160px; float: left;}
</style>
{/literal}
<h1 class="mainbox-title">Import Order Details</h1>

<form method="post" action="{""|fn_url}" name="order_import_form" id="order_import_form" target="_self" enctype="multipart/form-data">
<label for="upload_csv_file" class="cm-required">Upload File(CSV Only):</label><input type="file" name="file" id="upload_csv_file"  />
<br />
<ul class="order_type">
{foreach from=$status_info item="status_details"}
	<li><input type="checkbox" name="order_type[]" value="{$status_details.status}" checked="checked" /> {$status_details.description}</li>
{/foreach}
</ul>
<input type="hidden" name="mode_action" value="import" /><br clear="all" /><br />
<span class="submit-button cm-button-main cm-process-items cm-new-window">
<input type="submit" value="Import CSV" name="dispatch[import_orders.import_orders_list]" class="cm-process-items" /></span>
</form>
<br />
<span style="width: 955px; display: block;"><strong>{assign var='fail_count' value=$not_found|count}{$fail_count}</strong> of <strong>{$total_count|count}</strong> not found {assign var='fail_data' value=$not_found|@implode:', '}<font color="#FF0000"><strong>({$fail_data})</strong></font></span>
{if $list_order}
<form method="post" action="{""|fn_url}" name="order_import_form" id="order_import_form">

<table border="0" cellpadding="0" cellspacing="0" width="100%" class="table sortable">
<tr>
	<th width="1%" class="center">
		<input type="checkbox" name="check_all" value="Y" title="{$lang.check_uncheck_all}" class="checkbox cm-check-items" /></th>
	<th width="5%">Order No.</th>
    <th width="10%">Order Date</th>
	<th width="10%">Current Order Status</th>
    <th width="10%">New Order Status</th>
    <th width="10%">Tracking No</th>
    <th width="10%">Carrier Name</th>
    <th width="10%">Buyer Name</th>
    <!-- Add By Paresh -->
    <th width="15%">Shipping Address
	</th><!--END Add By Paresh -->

	<th>&nbsp;</th>
</tr>
{foreach from=$list_order item="order_info"}

{foreach from=$order_info item="order_details" key="k"}

<tr {cycle values="class=\"table-row\", "}>
	<td class="center" width="5%">
		<input type="checkbox" name="order_ids[]" value="{$order_details.order_id}" class="checkbox cm-item" />
    	<input type="hidden" name="order_notes[]" value="{$order_details.notes}" />
    </td>
	<td>
		{$order_details.order_id}
	</td>
    <td>
		{$order_details.timestamp|date_format:"%d %b %Y %k:%M"}
	</td>
	<td>
		{assign var="status" value=$order_details.status|fn_get_status_data}
        {$status.description}
        <input type="hidden" name="current_status[{$order_details.order_id}]" value="{$status.description}" />
	</td>
    <td>
		{$order_details.status_csv}
		<input type="hidden" name="new_status[{$order_details.order_id}]" value="{$order_details.status_csv}" />
	</td>
     <td>
		{$order_details.tracking_number}
    </td>
    <td>
		{$order_details.carrier}
    </td>
	<td>
		{$order_details.b_firstname} {$order_details.b_lastname}
	</td><!-- Add By Paresh -->
    <td class="nowrap">
		{$order_details.s_address},{$order_details.s_address2} <br />{$order_details.s_city}, {$order_details.s_state_descr} - Pincode: {$order_details.s_zipcode}
     </td>
</tr>

{/foreach}

{/foreach}
</table> <br />
<input type="checkbox" value="yes" name="notify" /> Notification to Customer <br /> <br />
<input type="hidden" name="mode_action" value="save" />
<div class="buttons-container buttons-bg">
<span class="submit-button cm-button-main cm-process-items cm-new-window">
<input type="submit" value="Complete Order" name="dispatch[import_orders.import_orders_list]" class="cm-process-items" /></span>
</div>
</form>
{/if}