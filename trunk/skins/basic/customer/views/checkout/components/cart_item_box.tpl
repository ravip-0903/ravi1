{literal}<style>
.prd_box_nl_fs a {color:#636566; font:11px Verdana, Geneva, sans-serif!important; }
.prd_box_nl_fs_crt_val{margin:0 0 0 ;float:right; text-align:right; width:70px;}
</style>
{/literal}
<div class="mobile-stp-three-hdng active" style="display: inline; " onclick="mobExpandCollapse(this)"><h2 class="subheader" style="font-size:14px;">{$lang.fs_cart_header}</h2><span class="mob_cat_icn_rgt"></span>
{if $cart.nss_on_cod && $cart['nss_on_cod'] == 'N' && $config.show_nss_alert}
<div class="nss_pincode_error">
  {$lang.nss_pincode_errors} 
  <a href="index.php?dispatch=checkout.checkout&edit_step=step_two">Change</a>
  </div>
  <div class="clearboth height_ten"></div>
{/if}</div>

<div class="mobile-stp-three-cart mob_inn_mnu_blk" style="font:11px verdana; color:#636566">
<!--For cart products -->

{foreach from=$cart_products item="product" key="item_id"}
  <div style="padding:0 0 10px; margin:0 0 10px;width:100%; border-bottom:1px solid #eee; float:left;" class="prd_box_nl_fs">
  	
     
     
     <div style="float:left; width:59%; margin:1px 0px 3px 5px; font-size:11px">
         <a href="{"index.php?dispatch=products.view&product_id=`$product.product_id`"|fn_url}" style="line-height:12px; font-size:12px; font-family:Verdana, Geneva, sans-serif">{if $product.product|strlen>40}{$product.product|substr:0:37}...{else}{$product.product}{/if}</a>
    {if $cart.user_data.s_zipcode|strlen == '6'}
    {assign var="is_serviceable" value=$product.product_id|get_servicability_type:$cart.user_data.s_zipcode}
        {if $is_serviceable == '4'}
            <div class="foot_note_nl" style="color:#666">
                {$lang.not_cod_only_prepaid}
            </div>
        {elseif $is_serviceable == '0'}
            <div class="foot_note_nl" style="color:#ff0000">
                {$lang.nss_product}
            </div>
        {/if}
     {/if}
     
    </div>
    <div class="list_lightboxcartitem" style="float:left; margin:-3px 0 0; border:0; padding:0px; width:32px;">
    {if $cart.multiple_shipping_addresses != 1}

	{if !eregi("Chrome",$smarty.server.HTTP_USER_AGENT)}
        	<a name="update_cart_item_quantity" style="margin:0 12px 0 0;" href="index.php?dispatch=checkout.delete.from_status&cart_id={$item_id}&location=step_three" class="cm-ajax list_lightboxcartitem_close" rev="payment_summary,shipping_rates_list,checkout_cart,checkout_totals,checkout_steps,cart_status,step_three">X</a>
    	{else}
		<a name="update_cart_item_quantity" style="margin:0 12px 0 0;" href="index.php?dispatch=checkout.delete.from_status&cart_id={$item_id}&location=step_three" class="cm-ajax list_lightboxcartitem_close" rev="payment_summary,shipping_rates_list,checkout_cart,checkout_totals,checkout_steps,cart_status,step_three" onclick="return false;">X</a>
	{/if}
     {/if}</div>
    {assign var="update_url" value="index.php?dispatch=checkout.update_quantity&product_id="|cat:$product.product_id|cat:"&cart_id="|cat:$item_id}
    {if isset($product_option)}
        {foreach from=$product_option item="p_option"}
            {assign var="update_url" value=$update_url|cat:"&product_options["|cat:$p_option.option_id|cat:"]="|cat:$p_option.value}
        {/foreach}    
    {/if}
    <div class="prd_box_nl_fs_crt_val">
     {assign var="p_price" value=$product.price+$product.discount}
     {assign var="p_price" value=$p_price*$product.amount}
     {include file="common_templates/price.tpl" value=$p_price}
    </div>
    
    <div class="prd_box_nl_fs_crt_val" style="margin-left:10px; float:right">
    {if $cart.multiple_shipping_addresses != 1}
      {if $product.qty_step != 0 and $product.qty_step != ''}
        {assign var="dec_update_url" value=$update_url|cat:"&qty="|cat:$product.amount-$product.qty_step}
      {else}
        {assign var="dec_update_url" value=$update_url|cat:"&qty="|cat:$product.amount-1}
      {/if}
  	    
      {if !eregi("Chrome",$smarty.server.HTTP_USER_AGENT)}
        <a href="{if $dec_update_url == "0"}javascript:void(0);{else}{$dec_update_url|cat:"&location=step_three"}{/if}" style="{if $dec_update_url == "0"}background:url(images/skin/cart_prd_qty_inc_dec.jpg) -17px -16px no-repeat;{else}background:url(images/skin/cart_prd_qty_inc_dec.jpg) -17px 0 no-repeat;{/if} float:left; width:17px; height:17px;" {if $dec_update_url != "0"}class="cm-ajax"{/if} rev="payment_summary,shipping_rates_list,checkout_cart,checkout_totals,checkout_steps,cart_status,step_three" ></a>
      {else}
        <a href="{if $dec_update_url == "0"}javascript:void(0);{else}{$dec_update_url|cat:"&location=step_three"}{/if}" style="{if $dec_update_url == "0"}background:url(images/skin/cart_prd_qty_inc_dec.jpg) -17px -16px no-repeat;{else}background:url(images/skin/cart_prd_qty_inc_dec.jpg) -17px 0 no-repeat;{/if} float:left; width:17px; height:17px;" {if $dec_update_url != "0"}class="cm-ajax"{/if} rev="payment_summary,shipping_rates_list,checkout_cart,checkout_totals,checkout_steps,cart_status,step_three" onclick="return false;"></a>
      {/if}
    
  {/if}
    <span style="float:left; margin:0 5px; padding:0 5px; width:13px; text-align:center; border:1px #ccc dotted">{$cart.products.$item_id.amount}</span>
    {if $product.qty_step != 0 and $product.qty_step != ''}
      {assign var="inc_update_url" value=$update_url|cat:"&qty="|cat:$product.amount+$product.qty_step}
    {else}
      {assign var="inc_update_url" value=$update_url|cat:"&qty="|cat:$product.amount+1}
    {/if}
    {if $cart.multiple_shipping_addresses != 1}
     {if !eregi("Chrome",$smarty.server.HTTP_USER_AGENT)}
       <a href="{$inc_update_url|cat:"&location=step_three"}" style="background:url(images/skin/cart_prd_qty_inc_dec.jpg) 0 0 no-repeat; float:left; width:17px; height:17px;" class="cm-ajax" rev="payment_summary,shipping_rates_list,checkout_cart,checkout_totals,checkout_steps,cart_status,step_three"></a>
     {else}
	    <a href="{$inc_update_url|cat:"&location=step_three"}" style="background:url(images/skin/cart_prd_qty_inc_dec.jpg) 0 0 no-repeat; float:left; width:17px; height:17px;" class="cm-ajax" rev="payment_summary,shipping_rates_list,checkout_cart,checkout_totals,checkout_steps,cart_status,step_three" onclick="return false;"></a>
     {/if}
    {/if}
    </div>
    
  </div>
 {/foreach}
 <!--For cart products -->
{if $cart.gift_certificates|count > 0} 
 {foreach from=$cart.gift_certificates item="gc" key="key"}
 	<div style="padding:0 0 10px; margin:0 0 10px;width:100%; border-bottom:1px solid #eee; float:left;" class="prd_box_nl_fs">
    	 
        
        <div style="float:left; width:59%; margin:1px 0px 3px 5px; font-size:11px">
        	{$lang.ts_gift_certificates}
        </div>
        <div class="list_lightboxcartitem" style="float:left; margin:-3px 0 0; border:0; padding:0px; width:32px;">
        	<a name="update_cart_item_quantity" style="margin:0 12px 0 0;" href="index.php?dispatch=gift_certificates.delete&gift_cert_id={$key}&location=step_three" class="cm-ajax list_lightboxcartitem_close" rev="payment_summary,shipping_rates_list,checkout_cart,checkout_totals,checkout_steps,cart_status,step_three" onclick="return false;">X</a>
   		</div>
        
        <div class="prd_box_nl_fs_crt_val">
         	{include file="common_templates/price.tpl" value=$gc.amount}
        </div>
        
        <div class="prd_box_nl_fs_crt_val" style="float:right">
			<span style="float:left; margin:0 0px 0 24px; padding:0 5px; width: 13px; text-align: center; border:1px #ccc dotted">1</span>
		</div>
        <div style="float:left; clear:both; margin-left:5px;">{$lang.ts_to}: {$gc.recipient}</div>        
    </div>
 {/foreach}
 <p>{$lang.explanation_for_gc}</p>
{/if}
<p>{$lang.cart_description_on_step_three}</p>
</div>


