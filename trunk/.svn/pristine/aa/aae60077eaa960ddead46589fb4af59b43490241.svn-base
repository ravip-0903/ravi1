{* $Id: print_shipping_label.tpl 12792 2011-06-27 13:33:36Z bimib $ *}

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head></head>

<body>

{if $order_info}
{literal}
<style type="text/css" media="screen,print">
body,p,div {
	color: #000000;
	font: 12px Arial;
}
body {
	padding: 0;
	margin: 0;
}
a, a:link, a:visited, a:hover, a:active {
	color: #000000;
	text-decoration: underline;
}
a:hover {
	text-decoration: none;
}
.barcode_mng div{ padding:0px!important;}
</style>
<style media="print">
body {
	background-color: #ffffff;
}
.scissors {
	display: none;
}
.barcode_mng div{ padding:0px;}

</style>
{/literal}
{if !$company_placement_info}
{assign var="company_placement_info" value=$order_info.company_id|fn_get_company_placement_info}
{/if}
<table class="order_search_mng_bymsc" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #f4f6f8; height: 100%;">
<tr>
	<td valign="top" align="center" style="width: 100%; height: 100%; padding: 2px 0;">
	<div style="background-color: #ffffff; border: 1px solid #999; margin: 0px auto; padding: 0px 6px 0px 6px; width:589px; text-align: left;">
		{assign var="profile_fields" value='I'|fn_get_profile_fields}

		{if $profile_fields.S}
		<table cellpadding="0" cellspacing="0" border="0" width="100%" style="padding-top: 2px;">
		<tr valign="top">
			<td width="100%" align="center">
				<!--<h3 style="font: bold 17px Tahoma; padding: 0px 0px 3px 1px; margin: 0px;">
                {if $order_info.payment_method.payment == 'C.O.D'}
                	{$lang.shipping_label_with_cod}
                {else}
                	{$lang.shipping_label_without_cod}
                {/if}
                </h3>-->                
				
			</td>
		</tr>
		</table>
		
		{/if}
		
		
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td valign="top" style="width: 65%; padding: 5px 0px 0px 2px;">
                
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><h2 style="font: bold 28px Tahoma; margin: 0px 0px 10px 0px; text-align:center;">AWB Number</h2></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="center" colspan="2"><div style=" color:#999;border:1px solid #999; height:40px; padding:10px 10px 10px 10px;">

<br />Carier Barcode Goes here</div></td>
                  </tr>
                </table>
			</td>
		</tr>
		</table>
		{* Customer info *}
		{if $profile_fields}
		<table cellpadding="0" cellspacing="0" border="0" width="100%" style="padding: 25px 0px 25px 0px;">
		<tr valign="top">
			{if $profile_fields.B}
			<td width="75%">
				<h3 style="font: bold 30px Tahoma; padding: 0px 0px 3px 1px; margin: 0px;">Delivery Address:</h3>
				{if $order_info.s_firstname || $order_info.s_lastname}
				<p style="margin: 2px 0px 3px 0px;font-size:21px;">
					<b>{$order_info.s_firstname} {$order_info.s_lastname}</b>
				</p>
				{/if}
				{if $order_info.s_address || $order_info.s_address_2}
				<p style="margin: 2px 0px 3px 0px;font-size:21px;">
					<b>{$order_info.s_address} {$order_info.s_address_2}</b>
				</p>
				{/if}
				{if $order_info.s_city || $order_info.s_state_descr || $order_info.s_zipcode}
				<p style="margin: 2px 0px 3px 0px;font-size:21px;">
					<b>{$order_info.s_city}{if $order_info.s_city && ($order_info.s_state_descr || $order_info.s_zipcode)},{/if} {$order_info.s_state_descr} {$order_info.s_zipcode}</b>
				</p>
				{/if}
				{if $order_info.s_country_descr}
				<p style="margin: 2px 0px 3px 0px;font-size:21px;">
					<b>{$order_info.s_country_descr}</b>
				</p>
				{/if}

                {if $order_info.s_phone || $order_info.b_phone}
				<p style="margin: 2px 0px 3px 0px;font-size:21px;">
					<b>Phone : {if $order_info.s_phone} {$order_info.s_phone} {elseif $order_info.b_phone} {$order_info.b_phone}{/if} </b>
				</p>
				{/if}

				{include file="profiles/profiles_extra_fields.tpl" fields=$profile_fields.S}
			</td>
			{/if}
			{if $profile_fields.S}
			<td width="25%">
            	{if $rtoflag != 1}
				{if $order_info.payment_method.payment == 'Cash on Delivery'}
                <h3 style="border: 1px solid #000000;font: bold 22px Tahoma;height: 150px;margin: 0;padding: 7px 0 3px 1px;text-align: center;">Cash On Delivery<br /><br />Please Collect:<br />{include file="common_templates/price.tpl" value=$order_info.total} Only</h3>
				{else}
                <h3 style="border: 1px solid #000000;font: bold 22px Tahoma;height: 100px;margin: 0;padding: 7px 0 3px 1px;text-align: center;"><br />Pre Paid </h3>
	                	{/if}
                {/if}        
			</td>
			{/if}
		</tr>
		</table>
		{/if}
		{* Customer info *}
		
			
		{* Ordered products *}
            <h3 style="font: bold 12px Tahoma; padding: 0px 0px 12px 1px; margin: 0px;">Product Details:</h3>
		<table width="100%" border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse; border:1px solid #999; padding: 25px 0px 25px 0px;">
			<tr>
				<th style="padding: 4px 7px; white-space: nowrap; font-size: 15px; font-family: Arial;">{$lang.product} Code</th>
				<th style="padding: 4px 7px; white-space: nowrap; font-size: 15px; font-family: Arial;">{$lang.product} Title</th>
				<th style="padding: 4px 7px; white-space: nowrap; font-size: 15px; font-family: Arial;"><!--{$lang.quantity}-->Qty</th>
{if $order_info.payment_method.payment_id == '6'}
				<th style="padding: 4px 7px; white-space: nowrap; font-size: 15px; font-family: Arial;">{$lang.unit_price}</th>
				{if $order_info.use_discount}
					<th style="padding: 4px 7px; white-space: nowrap; font-size: 15px; font-family: Arial;">{$lang.discount}</th>
				{/if}
				{if $order_info.taxes && $settings.General.tax_calculation != "subtotal"}
					<th style="padding: 4px 7px; white-space: nowrap; font-size: 15px; font-family: Arial;">{$lang.tax}</th>
				{/if}
				<th style="padding: 4px 7px; white-space: nowrap; font-size: 15px; font-family: Arial;">{$lang.subtotal}</th>
{/if}
			</tr>
			{foreach from=$order_info.items item="oi"}
			{hook name="orders:items_list_row"}
				{if !$oi.extra.parent}
				<tr>
					<td style="padding: 5px 10px; background-color: #ffffff; font-size: 13px; font-family: Arial;">
						<h3>{if $oi.product_code}{$oi.product_code}{/if}</h3>
					</td>
					<td style="padding: 5px 10px; background-color: #ffffff; text-align: center; font-size: 13px; font-family: Arial;"><h3>{$oi.product|unescape|default:$lang.deleted_product}
		<br />{include file="common_templates/options_info.tpl" product_options=$oi.product_options}
</h3></td>
					<td style="padding: 5px 10px; background-color: #ffffff; text-align: center; font-size: 13px; font-family: Arial;"><h3>{$oi.amount}</h3></td>
{if $order_info.payment_method.payment_id==6}
					<td style="padding: 5px 10px; background-color: #ffffff; text-align: right; font-size: 13px; font-family: Arial;"><h3>{if $oi.extra.exclude_from_calculate}{$lang.free}{else}{include file="common_templates/price.tpl" value=$oi.original_price}{/if}</h3></td>
					{if $order_info.use_discount}
					<td style="padding: 5px 10px; background-color: #ffffff; text-align: right; font-size: 13px; font-family: Arial;"><h3>{if $oi.extra.discount|floatval}{include file="common_templates/price.tpl" value=$oi.extra.discount}{else}&nbsp;-&nbsp;{/if}</h3></td>
					{/if}
					{if $order_info.taxes && $settings.General.tax_calculation != "subtotal"}
						<td style="padding: 5px 10px; background-color: #ffffff; text-align: right; font-size: 13px; font-family: Arial;"><h3>{if $oi.tax_value}{include file="common_templates/price.tpl" value=$oi.tax_value}{else}&nbsp;-&nbsp;{/if}</h3></td>
					{/if}
		
					<td style="padding: 5px 10px; background-color: #ffffff; text-align: right; white-space: nowrap; font-size: 13px; font-family: Arial;"><h3>{if $oi.extra.exclude_from_calculate}{$lang.free}{else}{include file="common_templates/price.tpl" value=$oi.display_subtotal}{/if}</h3>&nbsp;</td>
{/if}
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

			<table cellpadding="0" cellspacing="0" border="0" width="100%" height="150px">
			<tr>
				<td align="right">
{if $order_info.payment_method.payment_id==6}
				<table border="0" style="padding: 3px 0px 12px 0px;">
				<tr>
					<td style="text-align: right; white-space: nowrap; font-size: 12px; font-family: Arial;"><b>{$lang.subtotal}:</b>&nbsp;</td>
					<td style="text-align: right; white-space: nowrap; font-size: 12px; font-family: Arial;"><b>{include file="common_templates/price.tpl" value=$order_info.display_subtotal}</b></td>
				</tr>
				{if $order_info.discount|floatval}
				<tr>
					<td style="text-align: right; white-space: nowrap; font-size: 12px; font-family: Arial;"><b>{$lang.including_discount}:</b>&nbsp;</td>
					<td style="text-align: right; white-space: nowrap; font-size: 12px; font-family: Arial;">
						{include file="common_templates/price.tpl" value=$order_info.discount}</td>
				</tr>
				{/if}

			
				{if $order_info.subtotal_discount|floatval}
				<tr>
					<td style="text-align: right; white-space: nowrap; font-size: 12px; font-family: Arial;">{$lang.order_discount}:</td>
					<td style="text-align: right; white-space: nowrap; font-size: 12px; font-family: Arial;">
						{include file="common_templates/price.tpl" value=$order_info.subtotal_discount}</td>
				</tr>
				{/if}

				{*{if $order_info.coupons}
				{foreach from=$order_info.coupons item="coupon" key="key"}
			<!--	<tr>
					<td style="text-align: right; white-space: nowrap; font-size: 15px; font-family: Arial;"><b>{$lang.coupon}:</b>&nbsp;</td>
					<td style="text-align: right; white-space: nowrap; font-size: 15px; font-family: Arial;">{$key}</td>
				</tr> -->
				{/foreach}
				{/if}*}
				{if $order_info.taxes}
				<tr>
					<td style="text-align: right; white-space: nowrap; font-size: 12px; font-family: Arial;"><b>{$lang.taxes}:</b>&nbsp;</td>
					<td style="text-align: right; white-space: nowrap; font-size: 12px; font-family: Arial;">&nbsp;</td>
				</tr>
				{foreach from=$order_info.taxes item=tax_data}
				<tr>
					<td style="text-align: right; white-space: nowrap; font-size: 12px; font-family: Arial;">{$tax_data.description}&nbsp;{include file="common_templates/modifier.tpl" mod_value=$tax_data.rate_value mod_type=$tax_data.rate_type}{if $tax_data.price_includes_tax == "Y" && ($settings.Appearance.cart_prices_w_taxes != "Y" || $settings.General.tax_calculation == "subtotal")}&nbsp;{$lang.included}{/if}{if $tax_data.regnumber}&nbsp;({$tax_data.regnumber}){/if}:&nbsp;</td>
					<td style="text-align: right; white-space: nowrap; font-size: 12px; font-family: Arial;">{include file="common_templates/price.tpl" value=$tax_data.tax_subtotal}</td>
				</tr>
				{/foreach}
				{/if}
				{if $order_info.tax_exempt == 'Y'}
				<tr>
					<td style="text-align: right; white-space: nowrap; font-size: 12px; font-family: Arial;"><b>{$lang.tax_exempt}</b></td>
					<td style="text-align: right; white-space: nowrap; font-size: 12px; font-family: Arial;">&nbsp;</td>
				</tr>
				{/if}
			
				{if $order_info.payment_surcharge|floatval && !$take_surcharge_from_vendor}
				<tr>
					<td style="text-align: right; white-space: nowrap; font-size: 12px; font-family: Arial;">{$lang.payment_surcharge}:&nbsp;</td>
					<td style="text-align: right; white-space: nowrap; font-size: 12px; font-family: Arial;"><b>{include file="common_templates/price.tpl" value=$order_info.payment_surcharge}</b></td>
				</tr>
				{/if}
			
			
				{if $order_info.shipping}
				<tr>
					<td style="text-align: right; white-space: nowrap; font-size: 12px; font-family: Arial;"><b>{$lang.shipping_cost}:</b>&nbsp;</td>
					<td style="text-align: right; white-space: nowrap; font-size: 12px; font-family: Arial;"><h3>{include file="common_templates/price.tpl" value=$order_info.display_shipping_cost}</h3></td>
				</tr>
				{/if}
				
{if $order_info.points_info.in_use}
				<tr>
					<td style="text-align: right; white-space: nowrap; font: 12px Tahoma; text-align: right;"><h3>{$lang.print_shipping_cluesbucks}:</h3>&nbsp;</td>
					<td style="text-align: right; white-space: nowrap; font: 12px Tahoma; text-align: right;"><h3>-{include file="common_templates/price.tpl" value=$order_info.points_info.in_use.cost}</h3></td>
				</tr>
{/if}
				
				<tr>
					<td colspan="2"><hr style="border: 0px solid #999; border-top-width: 1px; margin:0;" /></td>
				</tr>
				<tr>
					<td style="text-align: right; white-space: nowrap; font: 12px Tahoma; text-align: right;"><h3>{$lang.total_cost}:</h3>&nbsp;</td>
					<td style="text-align: right; white-space: nowrap; font: 12px Tahoma; text-align: right;"><h3>{include file="common_templates/price.tpl" value=$order_info.total}</h3></td>
				</tr>
				</table>
{/if}
				</td>
			</tr>
			</table>
	
			{* /Order totals *}
		
        {*Shipper Address*}
        
        
				
                
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td valign="top">
                    	<h3 style="font: bold 20px Tahoma; padding: 0px 0px 3px 1px; margin: 0px;">Return Address:</h3>
                        <h2 style="font: 20px Arial; margin: 0px 0px 3px 0px;">Clues&nbsp;Network&nbsp;(P)&nbsp;Limited </h2>
                        <h3>40A/5, Chander Nager<br />
                        Sec-15 Part-2, Gurgaon <br />
                        India - 122 001 </h3>
                    </td>                    
                    <td valign="top">                    	
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="center">
                            <h2 style="font: bold 22px Tahoma; margin: 0px; text-align:center;">Shopclues.com Order No.</h2>
                            </td>
                          </tr>
                          <tr>
                            <td align="center" class="barcode_mng">{hook name="orders:invoice"}{/hook}</td>
                          </tr>
                        </table>
                    </td>                    
                  </tr>
                  <tr>
                  <td colspan="2" style="font: 15px;">
                    <h3>{$lang.phone1_label}: 0124 388 4500 <br />
                    {$lang.web_site}: http://www.shopclues.com <br />
                    {$lang.email}: <a href="mailto:support@shopclues.com" title="shopclues.com">support@shopclues.com</a></h3> <br />
                  </td>
                  </tr>
                </table>

        {*Shipper Address*}
<b>
Code:
{if $carrier}
{foreach from=$carrier item="carrier_code" name="cc"}
	{$carrier_code.carrier_code}{if !$smarty.foreach.cc.last},{/if}
{/foreach}
{else}
NSS
{/if}
</b>
        <h3 style="text-align:center; margin:-7px 0px 7px 0px;">IF UNDELIVERED, RETURN TO SHIPPER</h3>
		
	</div>
	</td>
</tr>
</table>
{/if}
</body>
</html>
