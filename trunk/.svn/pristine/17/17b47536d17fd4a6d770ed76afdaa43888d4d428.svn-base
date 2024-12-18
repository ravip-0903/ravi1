{assign var="result_ids" value="cart_items,checkout_totals,checkout_steps,cart_status,checkout_cart"}
<div class="list_lightboxcartitemheader" {if $location !='checkout'}style="margin-left:6px;"{/if}>
<div class="list_lightboxcartitemheader_image">Item</div>
<div class="list_lightboxcartitemheader_name">Name</div>

</div>

<div class="{if $location !='checkout'}list_lightboxcartitems{else}list_lightboxcartitems_withoutscroll{/if}" {if $location !='checkout'}style="margin-left:6px; width:98%; border-right:1px solid #eee; height:300px;"{/if}>
{if $cart.products|count > 0}

{assign var="cart_total" value="0"}
{foreach from=$cart.products item="product" key="key"}
<!--Cart Item -->
<div class="list_lightboxcartitem">
<div class="{if $location !='checkout'}list_lightboxcartitem_image{else}list_lightboxcartitem_image_fourty{/if}">
{assign var="pro_images" value=$product.product_id|fn_get_image_pairs:'product':'M'}
{if $location!="checkout"}
	{include file="common_templates/image.tpl" image_width="50" image_height="50" obj_id=$obj_id_prefix images=$pro_images object_type="product" show_thumbnail="Y"}
{else}
	{include file="common_templates/image.tpl" image_width="50" image_height="50" obj_id=$obj_id_prefix images=$pro_images object_type="product" show_thumbnail="Y"}
{/if}
</div>
<div class="list_lightboxcartitem_name" style="font-size:22px; width:480px;">
{$product.product_id|fn_get_product_name|unescape}
            	{assign var ="product_option" value=$product.product_options|fn_get_selected_product_options_info}
                {if $product_option}
                    {include file="common_templates/options_info.tpl" product_options=$product_option}
                {/if}
                
<div class="clearboth"></div>

<div class="box_mobileCheckout">
<div class="float_left">Price: <strong>{$product.price}</strong></div>
<div class="float_left margin_left_fifty">Qty: <strong>{$product.amount}</strong></div>
</div>



{if $location!="checkout"}
<input type="hidden" name="{$key}_amount" id="{$key}_amount" value="{$product.amount}" size="5" onchange="update_cart_item_quantity({$key})"/>               

{assign var="update_url" value="index.php?dispatch=checkout.update_quantity&result_ids=cart_status&product_id="|cat:$product.product_id|cat:"&cart_id="|cat:$key}
	{if isset($product_option)}
		{foreach from=$product_option item="p_option"}
	 		{assign var="update_url" value=$update_url|cat:"&product_options["|cat:$p_option.option_id|cat:"]="|cat:$p_option.value}
	 	{/foreach}    
	{/if}
{/if}

</div>

<div style="display:none;">{assign var="product_subtotal" value=$product.amount*$product.price}{$product_subtotal}
{assign var="cart_total" value=$cart_total+$product_subtotal}</div>

{if $location!="checkout"}
<a rev="cart_status" class="cm-ajax list_lightboxcartitem_close" style="margin-left:25px; float:right;" href="index.php?dispatch=checkout.delete.from_status&cart_id={$key}" name="update_cart_item_quantity">X</a>
{/if}
</div>
<!--End Cart Item -->
{/foreach}
{/if}
{if $cart.gift_certificates|count >0}
 {foreach from=$cart.gift_certificates item="gc" key="key"}
   <div class="list_lightboxcartitem">
     <div class="{if $location !='checkout'}list_lightboxcartitem_image{else}list_lightboxcartitem_image_fourty{/if}">
      <img src="skins/basic/customer/images/icons/gift_certificates_cart_icon.gif" height="40px" />
     </div>
     <div class="list_lightboxcartitem_name">
       Gift Certificates
     </div>
     <div class="list_lightboxcartitem_pricing">
       <div class="list_lightboxcartitem_pricing_priceoffer">{$gc.amount}</div>
     </div> 
     <div class="list_lightboxcartitem_quantity">
      <label>1</label>
     </div>
     <div class="list_lightboxcartitem_subtotal">
       {$gc.amount}
     </div>
     {if $location!="checkout"}
        <a rev="cart_status" class="cm-ajax list_lightboxcartitem_close" style="margin-left:25px; float:left;" href="index.php?dispatch=gift_certificates.delete&gift_cert_id={$key}" name="update_cart_item_quantity">X</a>
     {/if} 
     {assign var="cart_total" value=$cart_total+$gc.amount}
   </div>  
 {/foreach}
{/if} 
{if $cart.products|count == 0  && $cart.gift_certificates|count ==0}

<div style="float:left; display:inline; width:80%; background:#fafafa; border:1px solid #eee; text-align:center; padding:10px 0px; margin-left:66px; margin-top:20px; font:15px trebuchet ms;">
Your Cart is Empty
</div>



{/if}
</div>

<div class="clearboth"></div>
{if $location!="checkout"}
<div style="float:left; display:inline; float: left; font: 12px trebuchet ms;  margin-left:10px; color:#636566; width:320px; margin-top:5px;">
{$lang.cart_instruction_by_admin}
</div>
{*include file="views/checkout/components/checkout_totals_custom.tpl" location="cart_content"*}
<div class="box_paymentcalculations" style="clear:both; width:98%; margin-left:5px; margin-right:5px; float:left;">
    <div class="box_paymentcalculations_row">
        <div class="box_paymentcalculations_fieldname bold" style="width:310px; !important">
        {$lang.cart_total} :
        </div>
        <div class="box_paymentcalculations_field bold" style="width:90px !important;">
        {include file="common_templates/price.tpl" value=$cart_total}
        </div>
    </div>
</div>

<div class="box_functions">
<a href="javascript:return void();" class="cm-notification-close box_functions_button_left box_functions_button_color_gray margin_left_five" title="Continue Shopping">
Continue Shopping</a>
<!--<a href="index.php?dispatch=checkout.checkout&edit_step=step_two" class="box_functions_button margin_right_fifteen" title="Checkout">Checkout</a>-->
<a href="{$config.https_location}/index.php?dispatch=checkout.checkout&edit_step=step_two" class="box_functions_button margin_right_five" title="Checkout">Checkout</a>
</div>
{/if}

{*{if $settings.DHTML.ajax_add_to_cart != "Y" && $settings.General.redirect_to_cart == 'Y'}
    {include file="buttons/continue_shopping.tpl" but_href=$continue_url|default:$index_script but_role="action"}
{else}
    {include file="buttons/continue_shopping.tpl" but_meta="cm-notification-close"}
{/if}
{include file="buttons/checkout.tpl" but_href="checkout.checkout"}*}



{literal}
<script>
function update_cart_item_quantity(id)
{
	var str = document.getElementById('update_cart_item_quantity' + id).href;
	var cnt = document.getElementById(id + '_amount').value;
	var ind = str.indexOf('&qty=');
	var str_pre = str.substr(0,ind);
	str = str_pre + '&qty=' + cnt;
	document.getElementById('update_cart_item_quantity' + id).href = str;
}
</script>
{/literal}
