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
	<td valign="top" align="center" style="width: 100%; height: 100%; padding: 24px 0;">
	<div style="background-color: #ffffff; border: 1px solid #999; margin: 0px auto; padding: 0px 12px 0px 12px; width:420px; text-align: left;">
		{assign var="profile_fields" value='I'|fn_get_profile_fields}

		{if $profile_fields.S}
		<table cellpadding="0" cellspacing="0" border="0" width="100%" style="padding-top: 4px;">
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
                    <td><h2 style="font: bold 14px Tahoma; margin: 0px 0px 5px 0px; text-align:center;">AWB Number</h2></td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td align="center" colspan="2"><div style=" color:#999;border:1px solid #999; height:20px; padding:10px 10px 10px 10px;">Carier Barcode Goes here</div></td>
                  </tr>
                </table>
            
            
				
			</td>
		</tr>
		</table>
		{* Customer info *}
		{if $profile_fields}
		<table cellpadding="0" cellspacing="0" border="0" width="100%" style="padding: 5px 0px 5px 0px;">
		<tr valign="top">
			{if $profile_fields.B}
			<td width="54%">
				<h3 style="font: bold 17px Tahoma; padding: 0px 0px 3px 1px; margin: 0px;">Delivery Address:</h3>
				{if $order_info.s_firstname || $order_info.s_lastname}
				<p style="margin: 2px 0px 3px 0px;font-size:14px;">
					{$order_info.s_firstname} {$order_info.s_lastname}
				</p>
				{/if}
				{if $order_info.s_address || $order_info.s_address_2}
				<p style="margin: 2px 0px 3px 0px;font-size:14px;">
					{$order_info.s_address} {$order_info.s_address_2}
				</p>
				{/if}
				{if $order_info.s_city || $order_info.s_state_descr || $order_info.s_zipcode}
				<p style="margin: 2px 0px 3px 0px;font-size:14px;">
					{$order_info.s_city}{if $order_info.s_city && ($order_info.s_state_descr || $order_info.s_zipcode)},{/if} {$order_info.s_state_descr} {$order_info.s_zipcode}
				</p>
				{/if}
				{if $order_info.s_country_descr}
				<p style="margin: 2px 0px 3px 0px;font-size:14px;">
					{$order_info.s_country_descr}
				</p>
				{/if}
                {if $order_info.s_phone || $order_info.b_phone}
				<p style="margin: 2px 0px 3px 0px;font-size:14px;">
					<b>Phone : {if $order_info.s_phone} {$order_info.s_phone} {elseif $order_info.b_phone} {$order_info.b_phone}{/if} </b>
				</p>
				{/if}
                
				{include file="profiles/profiles_extra_fields.tpl" fields=$profile_fields.S}
			</td>
			{/if}
			{if $profile_fields.S}
			<td width="54%">
				{if $order_info.payment_method.payment == 'Cash on Delivery'}
                <h3 style="border: 1px solid #000000;font: bold 18px Tahoma;height: 70px;margin: 0;padding: 27px 0 3px 1px;text-align: center;">Cash On Delivery <br />Collectable <br />{include file="common_templates/price.tpl" value=$order_info.total}</h3>
                {/if}
			</td>
			{/if}
		</tr>
		</table>
		{/if}
		{* Customer info *}
		
			
		{* Ordered products *} 
            <h3 style="font: bold 12px Tahoma; padding: 0px 0px 3px 1px; margin: 0px;">Product Details:</h3>
		<table width="100%" border="1" cellpadding="0" cellspacing="0" style="background-color: #dddddd; border-collapse:collapse; border:1px solid #999;">
			<tr>
				<th style="background-color: #eeeeee; padding: 4px 7px; white-space: nowrap; font-size: 11px; font-family: Arial;">{$lang.product} Code</th>
				<th style="background-color: #eeeeee; padding: 4px 7px; white-space: nowrap; font-size: 11px; font-family: Arial;">{$lang.product} Title</th>
				<th style="background-color: #eeeeee; padding: 4px 7px; white-space: nowrap; font-size: 11px; font-family: Arial;"><!--{$lang.quantity}-->Qty</th>
				<th style="background-color: #eeeeee; padding: 4px 7px; white-space: nowrap; font-size: 11px; font-family: Arial;">{$lang.unit_price}</th>
				{if $order_info.use_discount}
					<th style="background-color: #eeeeee; padding: 4px 7px; white-space: nowrap; font-size: 11px; font-family: Arial;">{$lang.discount}</th>
				{/if}
				{if $order_info.taxes && $settings.General.tax_calculation != "subtotal"}
					<th style="background-color: #eeeeee; padding: 4px 7px; white-space: nowrap; font-size: 11px; font-family: Arial;">{$lang.tax}</th>
				{/if}
				<th style="background-color: #eeeeee; padding: 4px 7px; white-space: nowrap; font-size: 11px; font-family: Arial;">{$lang.subtotal}</th>
			</tr>
			{foreach from=$order_info.items item="oi"}
			{hook name="orders:items_list_row"}
				{if !$oi.extra.parent}
				<tr>
					<td style="padding: 5px 10px; background-color: #ffffff; font-size: 10px; font-family: Arial;">
						{if $oi.product_code}{$oi.product_code}{/if}
					</td>
					<td style="padding: 5px 10px; background-color: #ffffff; text-align: center; font-size: 10px; font-family: Arial;">{$oi.product|unescape|default:$lang.deleted_product}</td>
					<td style="padding: 5px 10px; background-color: #ffffff; text-align: center; font-size: 10px; font-family: Arial;">{$oi.amount}</td>
					<td style="padding: 5px 10px; background-color: #ffffff; text-align: right; font-size: 10px; font-family: Arial;">{if $oi.extra.exclude_from_calculate}{$lang.free}{else}{include file="common_templates/price.tpl" value=$oi.original_price}{/if}</td>
					{if $order_info.use_discount}
					<td style="padding: 5px 10px; background-color: #ffffff; text-align: right; font-size: 10px; font-family: Arial;">{if $oi.extra.discount|floatval}{include file="common_templates/price.tpl" value=$oi.extra.discount}{else}&nbsp;-&nbsp;{/if}</td>
					{/if}
					{if $order_info.taxes && $settings.General.tax_calculation != "subtotal"}
						<td style="padding: 5px 10px; background-color: #ffffff; text-align: right; font-size: 10px; font-family: Arial;">{if $oi.tax_value}{include file="common_templates/price.tpl" value=$oi.tax_value}{else}&nbsp;-&nbsp;{/if}</td>
					{/if}
		
					<td style="padding: 5px 10px; background-color: #ffffff; text-align: right; white-space: nowrap; font-size: 10px; font-family: Arial;">{if $oi.extra.exclude_from_calculate}{$lang.free}{else}{include file="common_templates/price.tpl" value=$oi.display_subtotal}{/if}&nbsp;</td>
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
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<td align="right">
				<table border="0" style="padding: 3px 0px 12px 0px;">
				<tr>
					<td style="text-align: right; white-space: nowrap; font-size: 12px; font-family: Arial;">{$lang.subtotal}:&nbsp;</td>
					<td style="text-align: right; white-space: nowrap; font-size: 12px; font-family: Arial;">{include file="common_templates/price.tpl" value=$order_info.display_subtotal}</td>
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

				{if $order_info.coupons}
				{foreach from=$order_info.coupons item="coupon" key="key"}
				<tr>
					<td style="text-align: right; white-space: nowrap; font-size: 12px; font-family: Arial;"><b>{$lang.coupon}:</b>&nbsp;</td>
					<td style="text-align: right; white-space: nowrap; font-size: 12px; font-family: Arial;">{$key}</td>
				</tr>
				{/foreach}
				{/if}
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
					<td style="text-align: right; white-space: nowrap; font-size: 12px; font-family: Arial;">{include file="common_templates/price.tpl" value=$order_info.display_shipping_cost}</td>
				</tr>
				{/if}
				
				
				<tr>
					<td colspan="2"><hr style="border: 0px solid #999; border-top-width: 1px; margin:0;" /></td>
				</tr>
				<tr>
					<td style="text-align: right; white-space: nowrap; font: 12px Tahoma; text-align: right;">{$lang.total_cost}:&nbsp;</td>
					<td style="text-align: right; white-space: nowrap; font: 12px Tahoma; text-align: right;">{include file="common_templates/price.tpl" value=$order_info.total}</td>
				</tr>
				</table>
				</td>
			</tr>
			</table>


		
			{* /Order totals *}
		
        {*Shipper Address*}
        
        
				
                
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td valign="top">
                    	<h3 style="font: bold 12px Tahoma; padding: 0px 0px 3px 1px; margin: 0px;">Return Address:</h3>
                        <h2 style="font: 12px Arial; margin: 0px 0px 3px 0px;">Clues&nbsp;Network&nbsp;(P)&nbsp;Limited </h2>
                        40A/5, Chander Nager<br />
                        Sec-15 Part-2, Gurgaon <br />
                        India - 122 001 <br />
                    </td>                    
                    <td valign="top">                    	
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td align="center">
                            <h2 style="font: bold 14px Tahoma; margin: 0px; text-align:center;">Shopclues.com Order No.</h2>
                            </td>
                          </tr>
                          <tr>
                            <td align="center" class="barcode_mng">{hook name="orders:invoice"}{/hook}</td>
                          </tr>
                        </table>
                    </td>                    
                  </tr>
                  <tr>
                  <td colspan="2">                
                    {$lang.phone1_label}: 0124 388 4500 <br />
                    {$lang.web_site}: http://www.shopclues.com <br />
                    {$lang.email}: <a href="mailto:support@shopclues.com" title="shopclues.com">support@shopclues.com</a> <br /> <br />
                  </td>
                  </tr>
                </table>

                
                
                
				<!--<table cellpadding="0" cellspacing="0" border="0">
				<tr valign="top">
					<td style="font: 12px verdana, helvetica, arial, sans-serif; text-transform: uppercase; color: #000000; padding-right: 10px;	white-space: nowrap;"></td>
					<td width="100%"></td>
				</tr>
				<tr valign="top">
					<td style="font: 12px verdana, helvetica, arial, sans-serif; text-transform: uppercase; color: #000000; padding-right: 10px; white-space: nowrap;"></td>
					<td width="100%"></td>
				</tr>
				<tr valign="top">
					<td style="font: 12px verdana, helvetica, arial, sans-serif; text-transform: uppercase; color: #000000; padding-right: 10px; white-space: nowrap;"></td>
					<td width="100%"></td>
				</tr>
				</table>-->
        {*Shipper Address*}
        <h3 style="text-align:center; margin:-7px 0px 7px 0px;">IF UNDELIVERED, RETURN TO SHIPPER</h3>
		
	</div>
	</td>
</tr>
</table>
{/if}

</body>
</html>
