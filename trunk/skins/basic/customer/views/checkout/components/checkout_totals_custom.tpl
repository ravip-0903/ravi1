{* $Id: checkout_totals_custom.tpl 12565 2011-05-31 08:31:28Z alexions $ *}
<!--Payment Total Calculation -->
<div class="box_paymentcalculations" style="width:34%; clear:both ;{if $controller=='checkout' && $mode=='checkout'}margin-top:0;{/if} {if $smarty.request.edit_step == 'step_four'} margin-top:10px; border:0;{else}width:auto;{/if} {if $controller =='checkout' && $mode == 'cart'}width:54%; margin-top:10px; margin-bottom:10px;{/if} {if $config.xbuy_now_popup}border:none;{/if}">
{if $smarty.request.edit_step !='step_four'}
{if $smarty.request.edit_step == 'step_three' || $controller=='checkout' && $mode=='checkout'}
{if $config.show_coupon_code_on_third_step}
<div id="show_coupan_code" >

{assign var="coupon_data" value=$cart.products|fn_process_coupon}
{if !empty($coupon_data)}
<span style="margin-right:5px; float:left;">{$lang.cc_on_third_step|replace:'[COUPON_CODE]':$coupon_data}</span>
{/if}
</div> 
{/if}
{/if}
{/if}
<div class="box_paymentcalculations_row">
<div class="box_paymentcalculations_fieldname bold" {if $config.xbuy_now_popup  && $location=="cart_content"}style="font:normal 20px trebuchet ms; width: 65% !important;"{/if}>
{if $location=="cart_content"} {$lang.cart_total}{else}{$lang.subtotal} :
{/if}
</div>
{assign var="total_discount" value="0"}
{assign var="total_discount" value=$total_discount+$cart.subtotal_discount}
{assign var="total_discount" value=$total_discount+$cart.discount}

<div class="box_paymentcalculations_field bold" {if $config.xbuy_now_popup && $location=="cart_content"}style="font: bold 20px trebuchet ms; color: #99010D; width: 30%;"{/if}>
{if $location=="cart_content"}
    {if $config.xbuy_now_popup}
        {assign var="product_price" value=0} 
        {foreach from=$cart.products item="product"}
           {assign var="cart_total" value="0"} 
           {if isset($product.third_price) && $product.third_price!=0}
             {assign var="product_price" value=$product.amount*$product.third_price+$product_price}
           {else}
             {assign var="product_price" value=$product.amount*$product.price+$product_price}  
           {/if}
           {if $product.free_shipping=='N'}
               {assign var="product_price" value=$product_price+$product.shipping_freight*$product.amount}
           {/if}
        {/foreach}
        {foreach from=$cart.gift_certificates item="gc" key="key"}
           {assign var="product_price" value=$gc.amount+$product_price}
        {/foreach}
            {include file="common_templates/price.tpl" value=$product_price}
        {else}
	{include file="common_templates/price.tpl" value=$cart.subtotal}
        {/if}
{else}
	{if ($cart.discount|floatval)}
    {include file="common_templates/price.tpl" value=$cart.subtotal+$cart.discount}
    {else}
		{include file="common_templates/price.tpl" value=$cart.subtotal}
   	{/if}
{/if}
</div>

</div>
{if $location != "cart_content"}

    {*{if ($cart.subtotal_discount|floatval)}
        <div class="box_paymentcalculations_row">
        <div class="box_paymentcalculations_fieldname">
        {$lang.order_discount} :
        </div>
        
        <div class="box_paymentcalculations_field">
        {include file="common_templates/price.tpl" value=$cart.subtotal_discount}
        </div>
        
        </div>
    {/if}
    
    {if ($cart.discount|floatval)}
        <div class="box_paymentcalculations_row">
        <div class="box_paymentcalculations_fieldname">
        {$lang.order_discount} :
        </div>
        
        <div class="box_paymentcalculations_field">
        {include file="common_templates/price.tpl" value=$cart.discount}
        </div>
        </div>
    {/if}*}
    
    {if ($total_discount|floatval)}
        <div class="box_paymentcalculations_row">
        <div class="box_paymentcalculations_fieldname">
        {$lang.order_discount} :
        </div>
        
        <div class="box_paymentcalculations_field">
        {include file="common_templates/price.tpl" value=$total_discount}
        </div>
        </div>
    {/if}

    {*{if ($cart.subtotal_discount|floatval) || ($cart.discount|floatval)}
        {if ($cart.discount|floatval)}
            {assign var="after_discount" value=$cart.subtotal}
        {else}
            {assign var="after_discount" value=$cart.subtotal-$cart.subtotal_discount}
        {/if}
        <div class="box_paymentcalculations_row">
        <div class="box_paymentcalculations_fieldname">
        {$lang.price_after_discount} :
        </div>
        
        <div class="box_paymentcalculations_field">
        {include file="common_templates/price.tpl" value=$after_discount}	
        </div>
        
        </div>
    {/if}*}
    
    {if ($total_discount|floatval)}
        {assign var="after_discount" value=$cart.subtotal-$cart.subtotal_discount}
        <div class="box_paymentcalculations_row">
        <div class="box_paymentcalculations_fieldname">
        {$lang.price_after_discount} :
        </div>
        
        <div class="box_paymentcalculations_field">
        {include file="common_templates/price.tpl" value=$after_discount}	
        </div>
        
        </div>
    {/if}

<div class="box_paymentcalculations_row">
<div class="box_paymentcalculations_fieldname">
{$lang.shipping_cost} :

</div>

<div class="box_paymentcalculations_field">
{include file="common_templates/price.tpl" value=$cart.display_shipping_cost}
</div>

</div>
{if isset($cart.gifting) && $cart.gifting.gift_it == 'Y' &&  $cart.giftable == 'Y'}
<div class="box_paymentcalculations_row">
<div class="box_paymentcalculations_fieldname">
{$lang.gifting_cost} :

</div>

<div class="box_paymentcalculations_field">
{include file="common_templates/price.tpl" value=$cart.gifting.gifting_charge}
</div>

</div>

{/if}

<div class="box_paymentcalculations_row">

{hook name="checkout:checkout_totals"}
{/hook}
</div>



{if $completed_steps.step_three == true}

{if $cart.emi_fee != '' && $cart.emi_fee != 0}
<div class="box_paymentcalculations_row">
	<div class="box_paymentcalculations_fieldname">
		{$lang.emi_process_fee} :
	</div>
	
	<div class="box_paymentcalculations_field">
		{include file="common_templates/price.tpl" value=$cart.emi_fee}
	</div>
</div>
{/if}

{if $cart.cod_fee > 0}
<div class="box_paymentcalculations_row">
	<div class="box_paymentcalculations_fieldname">
		{$lang.cod_fee} :
	</div>
	
	<div class="box_paymentcalculations_field">
		{include file="common_templates/price.tpl" value=$cart.cod_fee}
	</div>
</div>
{/if}

<div class="box_paymentcalculations_row">
<div class="box_paymentcalculations_fieldname">
<span class="box_paymentcalculations_fieldname_total">
{$lang.you_will_pay} :
</span>
</div>

<div class="box_paymentcalculations_field">
<span  class="box_paymentcalculations_field_totalamount">
{include file="common_templates/price.tpl" value=$cart.total}
</span>
</div>
<div class="clearboth"></div>
<div class="box_paymentcalculations_fieldabout">{include file="addons/reward_points/common_templates/reward_points.tpl"}</div>

</div>
{if $location !="four_step"}

{if $cart.discount >0 || $cart.subtotal_discount >0 }
<div class="box_paymentcalculations_promotionmessage">
{include file="views/checkout/components/applied_promotions.tpl" location=$location show_active="false" show_link="false"}
</div>
{/if}
	    {/if}        
{/if}

{/if}
</div>

<!--End Payment Total Calculation -->
