{* $Id: view.tpl 10541 2010-08-30 12:27:53Z alexions $ *}

{if !$wishlist|fn_cart_is_empty}

	{script src="js/exceptions.js"}

	{assign var="show_hr" value=false}
	{assign var="location" value="cart"}

	{if $products}

		{foreach from=$products item=product key=key name="products"}
		{hook name="wishlist:items_list"}
		{if !$wishlist.products.$key.extra.parent}

		{if $show_hr}
		<hr />
		{else}
			{assign var="show_hr" value=true}
		{/if}

		<div class="product-container clear">
			<div class="product-image">
				<span class="cm-reload-{$key}" id="product_image_update_{$key}">
					<input type="hidden" name="appearance[wishlist]" value="1" />
					<a href="{"products.view?product_id=`$product.product_id`"|fn_url}">{include file="common_templates/image.tpl" image_width=$settings.Thumbnails.product_lists_thumbnail_width image_height=$settings.Thumbnails.product_lists_thumbnail_height obj_id=$key images=$product.main_pair object_type="product" show_thumbnail="Y"}</a>
				<!--product_image_update_{$key}--></span>
			</div>
			<div class="product-description">
				<a href="{"products.view?product_id=`$product.product_id`"|fn_url}" class="product-title">{$product.product|unescape}</a>&nbsp;<a href="{"wishlist.delete?cart_id=`$key`"|fn_url}"><img src="{$images_dir}/icons/delete_product.gif" width="12" height="12" border="0" alt="{$lang.remove}" title="{$lang.remove}" align="bottom" /></a>
				
				{include file="blocks/list_templates/simple_list.tpl" obj_id=$key product=$product show_sku=true show_old_price=true show_price=true show_list_discount=true show_discount_label=true show_product_amount=true show_product_options=true show_qty=true show_min_qty=true show_edp=true show_add_to_cart=true but_role="action"}
			</div>
		</div>
		{/if}
		{/hook}
		{/foreach}
	{/if}
	{hook name="wishlist:view"}
	{/hook}

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
