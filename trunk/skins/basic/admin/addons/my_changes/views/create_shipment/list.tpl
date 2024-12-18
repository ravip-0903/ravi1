{* $Id: export_order_list.tpl 12544 2011-05-27 10:34:19Z bimib $ *}
{literal}
<style type="text/css">
.sortable th{ padding: 0px 5px;}
.sortable td, .sortable tr:hover td{ border-right: 1px solid #e4e4e4;}
</style>
{/literal}
<h1>Shipment Creation System</h1>

<p><a href="?dispatch=create_shipment.new">1. Download Order Data</a> | <a href="?dispatch=create_shipment.upload">2. Upload AWB Details</a></p><br />

<form method="get" action="{""|fn_url}" name="shipmentorderform" id="shipmentorderform">
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="table sortable">
<tr>
	<th width="5%">Serial No.</th>
	<th width="5%">Order No.</th>
	<th width="5%">AWB No.</th>
    <th width="10%">Carrier Name</th>
	<th width="10%">Weight</th>
    <th width="10%">Payment Mode</th>
	<th width="10%">Merchant Name</th>
    <th width="10%">Buyer Name</th>
    <th width="15%">Shipping Address 1</th>
    <th width="15%">Shipping Address 2</th>
    <th width="15%">Shipping City</th>
    <th width="15%">Shipping State</th>
    <th width="15%">Shipping Pincode</th>
    <th width="10%">Buyer Phone No.</th>
	<th width="10%">Total Order Amount</th>
    <th width="10%">Collectible Amount</th>
    <th width="20%">Product Details</th>
    <th width="10%">Manifest Id</th>
    <th width="10%">Manifest Dispatch Date</th>
</tr>
{if !empty($order_info)}
	{foreach from=$order_info item="value" key="k"}
		<tr>
			<td>{$k+1}</td>
			<td>{$value.order_id}</td>
			<td>{$value.tracking_number}</td>
			<td>{$value.carrier}</td>
			<td>{$value.weight}</td>
			<td>
				{if $value.payment_method.payment == '' && !isset($value.use_gift_certificates)}
					CluesBucks
				{elseif isset($value.use_gift_certificates)}
					Gift Certificate
				{else}
					{$value.payment_method.payment}
				{/if}
			</td>
			<td>{$value.merchant_detail}</td>
			<td>{$value.b_firstname} {$value.b_lastname}</td>
			<td>{$value.s_address}</td>
			<td>{$value.s_address_2}</td>
			<td>{$value.s_city}</td>
			<td>{$value.s_state}</td>
			<td>{$value.s_zipcode}</td>
			<td>{$value.b_phone}</td>
			<td>{$value.subtotal|number_format:2:".":","}</td>
			<td>{if $value.payment_method.payment_id == '6'}{$value.total}{else}0.00{/if}</td>
			<td>{$value.product_details}</td>
			<td>{$value.manifest_details.manifest_id}</td>
			<td>{$value.manifest_details.dispatch_date}</td>
		</tr>
	{/foreach}
	{if $type eq 'downloadorderdata'}
		<input type="hidden" name="mode_action" value="downloadorderdata" />
		<span class="submit-button cm-button-main cm-process-items">
		<input type="submit" value="Download Order Data" name="dispatch[create_shipment.list]" class="cm-process-items" />
		</span>
	{else}
		<input type="hidden" name="mode_action" value="saveshipmentdata" />
		<span class="submit-button cm-button-main cm-process-items">
		<input type="submit" value="Create Shipment" name="dispatch[create_shipment.list]" class="cm-process-items" />
		</span>
	{/if}
{/if}
</table>
</form>