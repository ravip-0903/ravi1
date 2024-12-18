{* $Id: milkrun_order_list.tpl 12544 2011-05-27 10:34:19Z bimib $ *}
<!--<pre>
{$order_info|print_r}</pre> -->
{literal}
<style type="text/css">
.sortable th{ padding: 0px 5px;}
.sortable td, .sortable tr:hover td{ border-right: 1px solid #e4e4e4;}
.sortable td span{ float:left;}
.form_box span{ float: left; margin: 0px 8px 20px 0;}
</style>
{/literal}
<h1 class="mainbox-title">BULK ORDER STATUS CHANGE SYSTEM</h1>
<h2>{$smarty.request.from|fn_hp_status_description:"O"}
 => {$smarty.request.to|fn_hp_status_description:"O"}</h2>
<p><a href="{"milkrun_completed.milkrun_initiate_list"|fn_url}">Step1: Scan Or Upload Order Numbers</a></p>

{if $not_found}
	<font color="#FF0000">Some of your order numbers were not found or not in correct state.<br />Please go back and remove (or fix) incorrect order numbers.<br /> ({$not_found|implode:","})</font><br /><br />
    <span class="submit-button cm-button-main cm-process-items">
<input type="button" value="Go Back" onclick="javascript:history.go(-1);" class="cm-process-items" /></span>
{/if}
{if $order_info}<br />
    <span><strong>No. Of Orders:</strong> {$order_info|count}</span><br /><br />

<table border="0" cellpadding="0" cellspacing="0" width="100%" class="table sortable">
<tr>
	<!--<th width="1%" class="center">
		<input type="checkbox" name="check_all" value="Y" title="{$lang.check_uncheck_all}" class="checkbox cm-check-items" /></th> -->
	<th width="5%">Order No.</th>
    <th width="10%">Merchant Name</th>
    <th width="10%">Payment Mode</th>
    <th width="10%">Buyer Name</th>
    <th width="15%">Shipping Address1</th>
    <th width="15%">Shipping Address2</th>
    <th width="15%">Shipping City</th>
    <th width="15%">Shipping State</th>
    <th width="15%">Shipping Pincode</th>
    <th width="10%">Buyer Phone No.</th>
    <th width="10%">Order SubTotal</th>
    <th width="10%">Collectible Amount</th>
    <th width="10%">Shipment Weight</th>
    <th width="20%">Product Details</th>
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
		{$merchant_name.$oid}
    </td>
    <td>
		{if $order_details.payment_method.payment == '' && !isset($order_details.use_gift_certificates)}
        	CluesBucks
        {elseif isset($order_details.use_gift_certificates)}
        	Gift Certificate
        {else}
        	{$order_details.payment_method.payment}
        {/if}
    </td>
    <td>
		{$order_details.b_firstname} {$order_details.b_lastname}
	</td>
    <td>
		{$order_details.s_address}
    </td>
    <td>
		{$order_details.s_address_2}
    </td>
    <td>
		{$order_details.s_city} 
    </td>
    <td>
		{$order_details.s_state_descr} 
    </td>
    <td >
		{$order_details.s_zipcode} 
    </td>
    <td>
		{$order_details.b_phone} 
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
        {$order_info.$k.$oid.weight}
    </td>
    <td>
        {$prod_details.$k.$oid.prod_detail}
    </td>
</tr>
{/hook}


{/foreach}

</table>
<form method="get" action="{""|fn_url}" name="milkrun_form" id="milkrun_form">
<input type="checkbox" value="1" name="C" checked="checked" /> Notify Customer | <input type="checkbox" value="1" name="A" checked="checked" /> Notify Order Department | <input type="checkbox" value="1" name="S" checked="checked" /> Notify Merchant
<input type="hidden" name="mode_action" value="save" />

<input type="hidden" name="from" value="{$smarty.request.from}" />
<input type="hidden" name="to" value="{$smarty.request.to}" />
<div class="buttons-container buttons-bg">
<span class="submit-button cm-button-main cm-process-items">
<input type="submit" value="Change Status to {$smarty.request.to}" name="dispatch[shipment_completed.shipment_completed_status]" class="cm-process-items" /></span>
</form>
<span class="submit-button cm-button-main cm-process-items">
<input type="button" value="Cancel" onclick="javascript:history.go(-1);" class="cm-process-items" /></span> 
</div>
{/if}
