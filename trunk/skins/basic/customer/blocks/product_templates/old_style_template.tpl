{* $Id: old_style_template.tpl 12442 2011-05-12 12:45:40Z 2tl $ *}

{script src="js/exceptions.js"}

{if $product}
{assign var="obj_id" value=$product.product_id}
{include file="common_templates/product_data.tpl" product=$product quantity_text=$lang.qty}

<div class="product-main-info old-style">

<div class="product-header-extra">
	{assign var="rating" value="rating_`$obj_id`"}{$smarty.capture.$rating}{assign var="sku" value="sku_$obj_id"}{$smarty.capture.$sku}
</div>
{hook name="products:view_main_info"}
	{assign var="form_open" value="form_open_`$obj_id`"}
	{$smarty.capture.$form_open}
	<div class="clear">
		{if !$no_images}
			<div class="image-border float-left center cm-reload-{$product.product_id}" id="product_images_{$product.product_id}_update">
				{include file="views/products/components/product_images.tpl" product=$product show_detailed_link="Y"}
			<!--product_images_{$product.product_id}_update--></div>
		{/if}
		
		<div class="product-info">
			<div class="product-options-container clear">
				<div class="float-left prices">
					{assign var="old_price" value="old_price_`$obj_id`"}
					{assign var="price" value="price_`$obj_id`"}
					{assign var="clean_price" value="clean_price_`$obj_id`"}
					{assign var="list_discount" value="list_discount_`$obj_id`"}
					{assign var="discount_label" value="discount_label_`$obj_id`"}
					<div class="{if $smarty.capture.$old_price|trim || $smarty.capture.$clean_price|trim || $smarty.capture.$list_discount|trim}prices-container {/if}clear">
					{if $smarty.capture.$old_price|trim || $smarty.capture.$clean_price|trim || $smarty.capture.$list_discount|trim}
						<div class="float-left product-prices">
							{if $smarty.capture.$old_price|trim}{$smarty.capture.$old_price}&nbsp;{/if}
					{/if}
					
					{if !$smarty.capture.$old_price|trim || $details_page}<p>{/if}
							{$smarty.capture.$price}
					{if !$smarty.capture.$old_price|trim || $details_page}</p>{/if}
				
					{if $smarty.capture.$old_price|trim || $smarty.capture.$clean_price|trim || $smarty.capture.$list_discount|trim}
							{$smarty.capture.$clean_price}
							{$smarty.capture.$list_discount}
						</div>
					{/if}
					</div>
					
					{assign var="product_amount" value="product_amount_`$obj_id`"}
					{$smarty.capture.$product_amount}
				</div>
			
				<div class="float-left">
					{if $capture_options_vs_qty}{capture name="product_options"}{/if}
					
					{assign var="product_options" value="product_options_`$obj_id`"}
					{$smarty.capture.$product_options}
					
					
					{assign var="advanced_options" value="advanced_options_`$obj_id`"}
					{$smarty.capture.$advanced_options}
					{if $capture_options_vs_qty}{/capture}{/if}
				
					{assign var="min_qty" value="min_qty_`$obj_id`"}
					{$smarty.capture.$min_qty}
					
					{assign var="product_edp" value="product_edp_`$obj_id`"}
					{$smarty.capture.$product_edp}
				</div>
			</div>

			{if $capture_buttons}{capture name="buttons"}{/if}
				<div class="buttons-container buttons-wrapper">
					{assign var="qty" value="qty_`$obj_id`"}
					{$smarty.capture.$qty}
					{assign var="add_to_cart" value="add_to_cart_`$obj_id`"}
					{$smarty.capture.$add_to_cart}
					
					{assign var="list_buttons" value="list_buttons_`$obj_id`"}
					{$smarty.capture.$list_buttons}
				</div>
			{if $capture_buttons}{/capture}{/if}
		</div>
	</div>
	{assign var="form_close" value="form_close_`$obj_id`"}
	{$smarty.capture.$form_close}
	
{/hook}
	
{if $smarty.capture.hide_form_changed == "Y"}
	{assign var="hide_form" value=$smarty.capture.orig_val_hide_form}
{/if}

{capture name="tabsbox"}
	{assign var="but_role" value=""}
	{assign var="tabs_block_orientation" value=$blocks.$tabs_block_id.properties.block_order}
	{foreach from=$blocks item="block" key="block_id"}
		{if $block.group_id == $tabs_block_id}
			{assign var="tabs_capture_name" value="tab_`$block_id`"}
			{capture name=$tabs_capture_name}
				{block id=$block_id no_box=true}
			{/capture}
			{assign var="nav_block_id" value="block_`$block_id`"}
			{if $smarty.capture.$tabs_capture_name|trim}
				{if $tabs_block_orientation == "V"}
					<h1 class="tab-list-title">{$navigation.tabs.$nav_block_id.title}</h1>
				{/if}
			{/if}

			<div id="content_block_{$block_id}" class="wysiwyg-content{if $hide_tab && $tabs_block_orientation == "H"} hidden{/if}">
				{$smarty.capture.$tabs_capture_name}
			</div>
			{if $smarty.capture.$tabs_capture_name|trim}
				{assign var="hide_tab" value=true}
			{/if}
		{/if}
	{/foreach}
{/capture}

{capture name="tabsbox_content"}
{if $tabs_block_orientation == "V"}
	{$smarty.capture.tabsbox}
{else}
	{include file="common_templates/tabsbox.tpl" content=$smarty.capture.tabsbox active_tab="block_`$smarty.request.selected_section`"}
{/if}
{/capture}

{if $blocks.$tabs_block_id.properties.wrapper}
	{include file=$blocks.$tabs_block_id.properties.wrapper content=$smarty.capture.tabsbox_content title=$blocks.$tabs_block_id.description}
{else}
	{$smarty.capture.tabsbox_content}
{/if}
</div>

<div class="product-details">
</div>

{capture name="mainbox_title"}{$product.product|unescape}{/capture}
{/if}