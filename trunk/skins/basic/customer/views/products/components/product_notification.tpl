{* $Id: product_notification.tpl 12763 2011-06-23 16:01:56Z alexions $ *}

{* NOTE: This template doesn\'t used for direct display
   It will store in the session and then display into notification box
   ---------------------------------------------------------------
   So, it is STRONGLY recommended to use strip tags in such templates
*}
{assign var="customerId" value=$smarty.session.auth.user_id}
<img src='http://api.targetingmantra.com/RecordEvent?mid=130915&eid=9&pid={$productId}&cid={$customerId}' width='1' height='1'>

{strip}
<div class="notification-body">
	{if $added_products}
	<table width="100%" cellpadding="0" cellspacing="3" border="0" style="font:11px verdana;">
		<tr>
        	<td><b>Item</b></td>
            <td><b>Name</b></td>
            <td><b>Price</b></td>
            <td><b>Quantity</b></td>
            <td style="width:100px;"><b>Sub Total</b></td>
        </tr>
        {foreach from=$added_products item=product key="key"}
			<tr>
            {hook name="products:notification_product"}
				<td>
					{assign var="pro_images" value=$product.product_id|fn_get_image_pairs:'product':'M'}
    {include file="common_templates/image.tpl" image_width="50" image_height="50" obj_id=$obj_id_prefix images=$pro_images object_type="product" show_thumbnail="Y"}
              	</td>
                <td>
                   	{$product.product_id|fn_get_product_name|unescape}<br />
					{if $product.product_option_data}
                    	{include file="common_templates/options_info.tpl" product_options=$product.product_option_data}
                   	{/if}
				</td>
				{if !$hide_amount}
					<td>
						{include file="common_templates/price.tpl" value=$product.display_price span_id="price_`$key`" class="none"}
					</td>
				{/if}
                <td>
                	<span class="none strong">{$product.amount}</span>
                </td>

                <td>
                	{assign var="product_subtotal" value=$product.display_price*$product.amount}
			{include file="common_templates/price.tpl" value=$product_subtotal span_id="subtotal_`$key`" class="none"}
                </td>


			{/hook}
            </tr>
		{/foreach}
	</table>
	{else}
	{$empty_text}
	{/if}
</div>
<div class="clear{if $n_type} center{/if}">
	{if !$n_type}
		<div class="float-left">
			{if $settings.DHTML.ajax_add_to_cart != "Y" && $settings.General.redirect_to_cart == 'Y'}
				{include file="buttons/continue_shopping.tpl" but_href=$continue_url|default:$index_script but_role="action"}
			{else}
				{include file="buttons/continue_shopping.tpl" but_meta="cm-notification-close"}
			{/if}
		</div>
		<div class="float-right">
			{include file="buttons/checkout.tpl" but_href="checkout.checkout"}
		</div>
	{else}
		{hook name="products:notification_control"}
		{if $n_type == "C"}
			{include file="buttons/button.tpl" but_href="product_features.compare" but_text=$lang.view_compare_list}
		{/if}
		{/hook}
	{/if}
</div>
{/strip}
