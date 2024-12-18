{* $Id: view.tpl 10541 2010-08-30 12:27:53Z alexions $ *}
{literal}<script type="text/javascript">
$(document).ready(function(){

if ($('.product-notification-container').length){
		if($(".our-logo").css("display")== "none"){
			window.location = "index.php?dispatch=checkout.cart";
		}
	}
	else{
		$('body').bind('DOMNodeInserted', 'central-column', function(e) {
		    // detecting mobile
		    if($(".our-logo").css("display")== "none"){
		        // mobile true!
		        if ($(e.target).hasClass('product-notification-container')) {
		            window.location = "index.php?dispatch=checkout.cart";
		        }

		    }
        	});
	}

});
</script>
{/literal}
<div class="box_headerTwo">
<h1 class="box_headingTwo">{$lang.my_wishlist}</h1>
</div>
<div class="clearboth height_twenty"></div>
{if !$wishlist|fn_cart_is_empty}

	{script src="js/exceptions.js"}

	{assign var="show_hr" value=false}
	{assign var="location" value="cart"}

	{if $products}

		{foreach from=$products item=product key=key name="products"}
		{hook name="wishlist:items_list"}
		{if !$wishlist.products.$key.extra.parent}
		
		<div class="product-container clear wishlist_product_container" style="float:left; width:200px; margin-left:50px;">
			<div class="product-image" style="border:1px solid #eee; width:200px;">
				<span class="cm-reload-{$key}" id="product_image_update_{$key}">
					<input type="hidden" name="appearance[wishlist]" value="1" />
					<a href="{"products.view?product_id=`$product.product_id`"|fn_url}">{include file="common_templates/image.tpl" image_width=$settings.Thumbnails.product_lists_thumbnail_width image_height=$settings.Thumbnails.product_lists_thumbnail_height obj_id=$key images=$product.main_pair object_type="product" show_thumbnail="Y"}</a>
				<!--product_image_update_{$key}--></span>
			</div>
			<div class="product-description wishlist-product-description" style="text-align: center; width:100%;">
				<div style="width:100%; height:40px;">
				<a href="{"products.view?product_id=`$product.product_id`"|fn_url}" class="product-title">{$product.product|truncate:"50":"...."}</a>
				</div>
                                {assign var="after_apply_promotion" value=$product|fn_get_3rd_price}
                                {include file="common_templates/product_data.tpl" product=$product show_add_to_cart=true but_role=action hide_form=false stay_in_cart=false show_product_options=true show_price_values=true show_old_price=true show_price=true}  
                                {assign var="form_open" value="form_open_`$product.product_id`"}
                                {$smarty.capture.$form_open}
                                {assign var="old_price" value="old_price_`$product.product_id`"}
                                {assign var="price" value="price_`$product.product_id`"}
                                {if $smarty.capture.$old_price|trim}{$smarty.capture.$old_price}&nbsp;{/if}
                                {$smarty.capture.$price}
                                {if $after_apply_promotion !=0}
                                <span class="price">{$after_apply_promotion|format_price:$currencies.$secondary_currency:""}</span>
                                {/if}
                                {*if $product.amount > 0 }
                                    {assign var="product_options" value="product_options_`$product.product_id`"}
                                    {$smarty.capture.$product_options}
                                {/if*}
                                    {*{assign var="product_amount" value="product_amount_`$product.product_id`"}
                                    {$smarty.capture.$product_amount}*}
                                    {*assign var="advanced_options" value="advanced_options_`$product.product_id`"}
                                    {$smarty.capture.$advanced_options}
                                    {if $capture_options_vs_qty}{/capture}{/if*}
                                   <div class="myaccnt_wishlst_mobile" style=" clear: both; display: block; float: left; margin: 0 0 0 41px; text-align: center; width: 165px;">
                                                {assign var="add_to_cart" value="add_to_cart_`$product.product_id`"}
                                    {$smarty.capture.$add_to_cart}
                                    </div>
                                {assign var="form_close" value="form_close_`$product.product_id`"}
                                {$smarty.capture.$form_close}
                                
				<a class="mob-del-prod" href="{"wishlist.delete?cart_id=`$key`"|fn_url}"><img src="{$images_dir}/icons/delete_product.gif" width="12" height="12" border="0" alt="{$lang.remove}" title="{$lang.remove}" align="bottom" /> Remove</a>
				
				{include file="blocks/list_templates/simple_list.tpl" obj_id=$key product=$product show_sku=true show_old_price=true show_price=true show_list_discount=true show_discount_label=true show_product_amount=true show_product_options=true show_qty=true show_min_qty=true show_edp=true show_add_to_cart=true but_role="action"}
				
				<div class="clearboth height_thirty"></div>
			</div>
		</div>
		{/if}
		{/hook}
		{/foreach}
	{/if}
	{hook name="wishlist:view"}
	{/hook}
	<div class="clearboth"></div>
	<div class="buttons-container">
		{include file="buttons/button.tpl" but_text=$lang.clear_wishlist but_href="wishlist.clear"}&nbsp;&nbsp;
		{include file="buttons/continue_shopping.tpl" but_href=$continue_url|default:$index_script but_role="action"}
	</div>
{else}

	<p class="no-items">{$lang.text_wishlist_empty}</p>

	<div class="buttons-container center">
		{include file="buttons/continue_shopping.tpl" but_href=$continue_url|default:$index_script but_role="action"}
	</div>

{/if}

{capture name="mainbox_title"}{$lang.wishlist_content}{/capture}
