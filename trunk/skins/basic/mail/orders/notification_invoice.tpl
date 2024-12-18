{if $order_info}

{assign var="order_header" value=$lang.invoice}

{if $status_settings.appearance_type == "I" && $order_info.doc_ids[$status_settings.appearance_type]}
	{assign var="doc_id_text" value="`$lang.invoice` #`$order_info.doc_ids[$status_settings.appearance_type]`"}
{elseif $status_settings.appearance_type == "C" && $order_info.doc_ids[$status_settings.appearance_type]}
	{assign var="doc_id_text" value="`$lang.credit_memo` #`$order_info.doc_ids[$status_settings.appearance_type]`"}
	{assign var="order_header" value=$lang.credit_memo}
{elseif $status_settings.appearance_type == "O"}
	{assign var="order_header" value=$lang.order_details}
{/if}
{if !$company_placement_info}
	{assign var="company_placement_info" value=$order_info.company_id|fn_get_company_placement_info}
{/if}
{if $order_info.company_id}
	{assign var="manifest" value=$order_info.company_id|fn_get_company_manifest}	
{/if}
{assign var="company_name" value=$order_info.company_id|fn_get_company_name}

<table cellpadding="0" cellspacing="0" border="0" width="100%" class="main-table" style="height: 100%; background-color: #f4f6f8; font-size: 12px; font-family: Arial;">
<tr>
	<td align="center" style="width: 100%; height: 100%;">
	<table cellpadding="0" cellspacing="0" border="0" style=" width: 602px; table-layout: fixed; margin: 24px 0 24px 0;">
	<tr>
		<td style="background-color: #ffffff; border: 1px solid #e6e6e6; margin: 0px auto 0px auto; padding: 0px 44px 0px 46px; text-align: left;">
			<table cellpadding="0" cellspacing="0" border="0" width="100%" style="padding: 27px 0px 0px 0px; border-bottom: 1px solid #868686; margin-bottom: 8px;">
			<tr>
				<td align="left" style="padding-bottom: 3px; font: bold 24px Arial;" valign="middle">
				{$lang.order} #{$order_info.order_id}
		                </td>

					<tr valign="top">
						<td style="font-size: 12px; font-family: verdana, helvetica, arial, sans-serif; text-transform: uppercase; color: #000000; padding-right: 10px; white-space: nowrap;">{$lang.date}:</td>
						<td style="font-size: 12px; font-family: Arial;">{$order_info.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}</td>
					</tr>
					
		{*by ajay to show paid using *}
					
					{if $order_info.payment_id != "6" }
					<tr valign="top">
						<td style="font-size: 12px; font-family: verdana, helvetica, arial, sans-serif; text-transform: uppercase; color: #000000; padding-right: 10px; white-space: nowrap;">{$lang.payment_method}:</td>
						<td style="font-size: 12px; font-family: Arial;">{$payment_method.payment|default:" - "}   </td>
					</tr>
					{/if}
					
					<tr valign="top">
						<td style="font-size: 12px; font-family: verdana, helvetica, arial, sans-serif; text-transform: uppercase; color: #000000; padding-right: 10px; white-space: nowrap;">{$lang.paid_using}:</td>
					      {if !empty($order_info.paid.type_name)}{$order_info.paid.type_name}{/if}
					    </td>
					</tr>
       {*end by ajay to show paid using *}


	{assign var="shipment_details" value=$order_info.shipment_ids|get_shipment_data}
			{if $shipment_details}
					<br /><tr valign="top">
						<td style="font-size: 12px; font-family: verdana, helvetica, arial, sans-serif; text-transform: uppercase; color: #000000; padding-right: 10px; white-space: nowrap;">{$lang.shipment_information}: Tracking Number &nbsp;
			        {assign var="is_url_tracking" value=$shipment_details.0.carrier|fn_get_tracking_url}
				<a target='_blank' href={$is_url_tracking.tracking_url}>{$shipment_details.0.tracking_number}</a>
				Via
				{foreach from=$shipment_details item="shipments"}
					{$shipments.carrier|replace:'_':' '}
				{/foreach}
					</td></tr>
			{/if}

			</tr>
			</table>

			{* Ordered products *}
			
			<table width="100%" cellpadding="0" cellspacing="1" style="background-color: #dddddd;">
			<tr>
				<th width="70%" style="background-color: #eeeeee; padding: 6px 10px; white-space: nowrap; font-size: 12px; font-family: Arial;">{$lang.product}</th>
				<th style="background-color: #eeeeee; padding: 6px 10px; white-space: nowrap; font-size: 12px; font-family: Arial;">{$lang.quantity}</th>
				<th style="background-color: #eeeeee; padding: 6px 10px; white-space: nowrap; font-size: 12px; font-family: Arial;">{$lang.unit_price}</th>
				{if $order_info.use_discount}
					<th style="background-color: #eeeeee; padding: 6px 10px; white-space: nowrap; font-size: 12px; font-family: Arial;">{$lang.discount}</th>
				{/if}
				{if $order_info.taxes && $settings.General.tax_calculation != "subtotal"}
					<th style="background-color: #eeeeee; padding: 6px 10px; white-space: nowrap; font-size: 12px; font-family: Arial;">{$lang.tax}</th>
				{/if}
				<th style="background-color: #eeeeee; padding: 6px 10px; white-space: nowrap; font-size: 12px; font-family: Arial;">{$lang.subtotal}</th>
			</tr>
			{foreach from=$order_info.items item="oi"}
			{hook name="orders:items_list_row"}
				{if !$oi.extra.parent}
				<tr>
					<td style="padding: 5px 10px; background-color: #ffffff; font-size: 12px; font-family: Arial;">
						{$oi.product|unescape|default:$lang.deleted_product}
						{*hook name="orders:product_info"*}
						{if $oi.product_code}<p style="margin: 2px 0px 3px 0px;">{$lang.code}: {$oi.product_code}</p>{/if}
						{*/hook*}
						{if $oi.product_options}<br/>{include file="common_templates/options_info.tpl" product_options=$oi.product_options}{/if}
						{if $oi.merchant_reference_number}
							<p style="margin: 2px 0px 3px 0px;">{$lang.merchant_ref}: {$oi.merchant_reference_number}</p>
						{/if}
						{if $settings.Suppliers.enable_suppliers == "Y" && $oi.company_id && $settings.Suppliers.display_supplier == "Y"}
							<p style="margin: 2px 0px 3px 0px;">{$lang.supplier}: {$oi.company_id|fn_get_company_name}</p>
						{/if}
					</td>
					<td style="padding: 5px 10px; background-color: #ffffff; text-align: center; font-size: 12px; font-family: Arial;">{$oi.amount}</td>
					<td style="padding: 5px 10px; background-color: #ffffff; text-align: right; font-size: 12px; font-family: Arial;">{if $oi.extra.exclude_from_calculate}{$lang.free}{else}{include file="common_templates/price.tpl" value=$oi.original_price}{/if}</td>
					{if $order_info.use_discount}
					<td style="padding: 5px 10px; background-color: #ffffff; text-align: right; font-size: 12px; font-family: Arial;">{if $oi.extra.discount|floatval}{include file="common_templates/price.tpl" value=$oi.extra.discount}{else}&nbsp;-&nbsp;{/if}</td>
					{/if}
					{if $order_info.taxes && $settings.General.tax_calculation != "subtotal"}
						<td style="padding: 5px 10px; background-color: #ffffff; text-align: right; font-size: 12px; font-family: Arial;">{if $oi.tax_value}{include file="common_templates/price.tpl" value=$oi.tax_value}{else}&nbsp;-&nbsp;{/if}</td>
					{/if}
		
					<td style="padding: 5px 10px; background-color: #ffffff; text-align: right; white-space: nowrap; font-size: 12px; font-family: Arial;"><b>{if $oi.extra.exclude_from_calculate}{$lang.free}{else}{include file="common_templates/price.tpl" value=$oi.display_subtotal}{/if}</b>&nbsp;</td>
				</tr>
				{/if}
			{/hook}
			{/foreach}
			{hook name="orders:extra_list"}
			{/hook}
			</table>

			{hook name="orders:ordered_products"}
			{/hook}
			{* /Ordered products *}
		
			{* Order totals *}
            
			{include file="orders/order_total_custom.tpl" order_info=$order_info}
		
			{* /Order totals *}
             <div style="float:left">
            
			{include file="orders/order_gift_message.tpl" order_info=$order_info}
            </div>
            <div style="clear:both"></div>
			{hook name="orders:invoice_customer_info"}
			{if !$profile_fields}
			{assign var="profile_fields" value='I'|fn_get_profile_fields}
			{/if}
			{if $profile_fields}
			<table cellpadding="0" cellspacing="0" border="0" width="100%" style="padding: 32px 0px 24px 0px;">
			<tr valign="top">
				{if $profile_fields.C}
					{assign var="profields_c" value=$profile_fields.C|fn_fields_from_multi_level:"field_name":"field_id"}
				{/if}
				{if $profile_fields.B}
				{assign var="profields_b" value=$profile_fields.B|fn_fields_from_multi_level:"field_name":"field_id"}
				<td width="34%" style="font-size: 12px; font-family: Arial; {if $profile_fields.S}padding-right: 10px;{/if} {if $profile_fields.C}padding-left: 10px;{/if}">
					<h3 style="font: bold 17px Tahoma; padding: 0px 0px 3px 1px; margin: 0px;">{$lang.billing_address}:</h3>
					{if $order_info.b_firstname && $profields_b.b_firstname || $order_info.b_lastname && $profields_b.b_lastname}
					<p style="margin: 2px 0px 3px 0px;">
						{if $profields_b.b_firstname}{$order_info.b_firstname} {/if}{if $profields_b.b_lastname}{$order_info.b_lastname}{/if}
					</p>
					{/if}
					{if $order_info.b_address && $profields_b.b_address || $order_info.b_address_2 && $profields_b.b_address_2}
					<p style="margin: 2px 0px 3px 0px;">
						{if $profields_b.b_address}{$order_info.b_address} {/if}{if $profields_b.b_address_2}<br />{$order_info.b_address_2}{/if}
					</p>
					{/if}
					{if $order_info.b_city && $profields_b.b_city || $order_info.b_state_descr && $profields_b.b_state || $order_info.b_zipcode && $profields_b.b_zipcode}
					<p style="margin: 2px 0px 3px 0px;">
						{if $profields_b.b_city}{$order_info.b_city}{if $profields_b.b_state},{/if} {/if}{if $profields_b.b_state}{$order_info.b_state_descr} {/if}{if $profields_b.b_zipcode}{$order_info.b_zipcode}{/if}
					</p>
					{/if}
					{if $order_info.b_country_descr && $profields_b.b_country}
					<p style="margin: 2px 0px 3px 0px;">
						{$order_info.b_country_descr}
					</p>
					{/if}
					{*{if $order_info.b_phone && $profields_b.b_phone}
					<p style="margin: 2px 0px 3px 0px;">
						{if $profields_b.b_phone}{$order_info.b_phone} {/if}
					</p>
					{/if}*}
					{include file="profiles/profiles_extra_fields.tpl" fields=$profile_fields.B}
				</td>
				{/if}
				{if $profile_fields.S}
				{assign var="profields_s" value=$profile_fields.S|fn_fields_from_multi_level:"field_name":"field_id"}
				<td width="33%" style="font-size: 12px; font-family: Arial;">
					<h3 style="font: bold 17px Tahoma; padding: 0px 0px 3px 1px; margin: 0px;">{$lang.shipping_address}:</h3>
					{if $order_info.s_firstname && $profields_s.s_firstname || $order_info.s_lastname && $profields_s.s_lastname}
					<p style="margin: 2px 0px 3px 0px;">
						{if $profields_s.s_firstname}{$order_info.s_firstname} {/if}{if $profields_s.s_lastname}{$order_info.s_lastname}{/if}
					</p>
					{/if}
					{if $order_info.s_address && $profields_s.s_address || $order_info.s_address_2 && $profields_s.s_address_2}
					<p style="margin: 2px 0px 3px 0px;">
						{if $profields_s.s_address}{$order_info.s_address} {/if}{if $profields_s.s_address_2}<br />{$order_info.s_address_2}{/if}
					</p>
					{/if}
					{if $order_info.s_city && $profields_s.s_city || $order_info.s_state_descr && $profields_s.s_state || $order_info.s_zipcode && $profields_s.s_zipcode}
					<p style="margin: 2px 0px 3px 0px;">
						{if $profields_s.s_city}{$order_info.s_city}{if $profields_s.s_state},{/if} {/if}{if $profields_s.s_state}{$order_info.s_state_descr} {/if}{if $profields_s.s_zipcode}{$order_info.s_zipcode}{/if}
					</p>
					{/if}
					{if $order_info.s_country_descr && $profields_s.s_country}
					<p style="margin: 2px 0px 3px 0px;">
						{$order_info.s_country_descr}
					</p>
					{/if}
					{*{if $order_info.s_phone && $profields_s.s_phone}
					<p style="margin: 2px 0px 3px 0px;">
						{if $profields_s.s_phone}{$order_info.s_phone} {/if}
					</p>
					{/if}*}
					{include file="profiles/profiles_extra_fields.tpl" fields=$profile_fields.S}
				</td>
				{/if}
			</tr>
			</table>
			{/if}
			{/hook}
			{* Customer info *}

			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr valign="top">
				{hook name="orders:invoice_company_info"}
				<td style="width: 50%; padding: 14px 0px 0px 2px; font-size: 12px; font-family: Arial;">
					<h2 style="font: bold 17px Arial; margin: 0px 0px 3px 0px;">{$lang.merchant}: &nbsp; {$company_placement_info.company_name}</h2>
					{$company_placement_info.company_address}<br />
					{$company_placement_info.company_city}{if $company_placement_info.company_city && ($company_placement_info.company_state_descr || $company_placement_info.company_zipcode)},{/if} {$company_placement_info.company_state_descr} {$company_placement_info.company_zipcode}<br />
					{$company_placement_info.company_country_descr}
					<table cellpadding="0" cellspacing="0" border="0">


					</table>
				</td>
				{/hook}

			</tr>
			</table>
		
	
		
			{if $order_info.notes}
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr valign="top">
				<td style="font-size: 12px; font-family: Arial;"><strong>{$lang.notes}:</strong></td>
				<td width="100%"><div style="padding-left: 7px; padding-bottom: 15px; overflow-x: auto; clear: both; width: 464px; height: 100%; padding-bottom: 20px; overflow-y: hidden; font-size: 12px; font-family: Arial;">{$order_info.notes|wordwrap:85:"\n":false|nl2br}</div></td>
			</tr>
			</table>
			{/if}
		
			{if $content == "invoice"}
			<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
			<tr>
				<td>
					{include file="buttons/continue_shopping.tpl" but_href=$continue_url|default:$index_script but_arrow="on" skin_area="customer"}</td>
				<td align="right">
					{include file="buttons/button_popup.tpl" but_text=$lang.print_invoice but_href="orders.print_invoice?order_id=`$order_info.order_id`" width="800" height="600"  skin_area="customer"}</td>
			</tr>
			</table>	
			{/if}
		{/if}
		
		{*hook name="orders:invoice"*}
		{*/hook*}
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>
