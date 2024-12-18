{* $Id: products_qty_discounts.tpl 6962 2009-03-02 14:40:38Z angel $ *}
{if $product_on_tp == 0 && $config.quantity_discount_flag == 1}
<p>{$lang.text_qty_discounts}:</p>

<table cellpadding="0" cellspacing="1" border="0" class="table qty-discounts" width="100%">
<tr>
	<th class="left" valign="middle">{$lang.quantity}</th>
        <th class="left" valign="middle">{$lang.price_per_unit}</th>
        {if $config.quantity_discount_flag}
            <th class="left" valign="middle">{$lang.shipping_per_unit}</th>
        {/if}
	
</tr>

{assign var="arr_count" value=$product_prices|count}
{foreach from=$product_prices key="key" item="price"}
    {assign var="next_key" value=$key+1}
    <tr>
        <td class="left">&nbsp;{if $product_prices.$next_key.lower_limit - $price.lower_limit > 1}{$price.lower_limit} Units &nbsp;- {$product_prices.$next_key.lower_limit-1} Units{else}{$price.lower_limit} Units{if $key == $arr_count-1}&nbsp; And More{/if}{/if}&nbsp;</td>
        <td class="left">&nbsp;{include file="common_templates/price.tpl" value=$price.price}&nbsp;</td>
        {if $config.quantity_discount_flag}
            {if $price.lower_limit == 1}
                <td class="left">&nbsp;{if $product.free_shipping == 'Y'}Free Home Delivery{else}{include file="common_templates/price.tpl" value=$product.shipping_freight}{/if}&nbsp;</td>
            {else}
                <td class="left">&nbsp;{if $product.free_shipping == 'Y'}Free Home Delivery{else}{include file="common_templates/price.tpl" value=$price.shipping_charge}{/if}&nbsp;</td>
            {/if}
            
        {/if}
    </tr>
{/foreach}
</table>
{/if}
