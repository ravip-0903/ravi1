{* $Id: product_images.tpl 12695 2011-06-16 08:23:29Z alexions $ *}

<!--change by ankur to change the option image on one day sale page-->

{if $smarty.session.onedaysale == "YES"}
    <ul style="margin-bottom:20px;">
                <li  class="mid_bigimg">
                <img src="{$config.ext_images_host}{$product.main_pair.detailed.image_path}" alt="" /></li>
    </ul>
{else}
{assign var="th_size" value="30"}

{if $product.main_pair.icon || $product.main_pair.detailed}
	{assign var="image_pair_var" value=$product.main_pair}
{elseif $product.option_image_pairs}
	{assign var="image_pair_var" value=$product.option_image_pairs|reset}
{/if}

{if $image_pair_var.image_id == 0}
	{assign var="image_id" value=$image_pair_var.detailed_id}
{else}
	{assign var="image_id" value=$image_pair_var.image_id}
{/if}

{include file="common_templates/image.tpl" obj_id="`$product.product_id`_`$image_id`" images=$image_pair_var object_type="detailed_product" show_thumbnail="Y" image_width=$settings.Thumbnails.product_details_thumbnail_width image_height=$settings.Thumbnails.product_details_thumbnail_height rel="preview[product_images]" wrap_image=true}
{if $product.in_inventory == 'Y'}
    <img src="http://cdn.shopclues.com/images/banners/icons/shiping_two_four_delivery_icon.png" style="position: absolute;top: 290px;z-index: 4444;left: 10px;" title="{$lang.ship24}" />
{/if}
{foreach from=$product.image_pairs item="image_pair"}
	{if $image_pair}
		{if $image_pair.image_id == 0}
			{assign var="image_id" value=$image_pair.detailed_id}
		{else}
			{assign var="image_id" value=$image_pair.image_id}
		{/if}
		{include file="common_templates/image.tpl" images=$image_pair object_type="detailed_product" link_class="hidden" show_thumbnail="Y" detailed_link_class="hidden" obj_id="`$product.product_id`_`$image_id`" image_width=$settings.Thumbnails.product_details_thumbnail_width image_height=$settings.Thumbnails.product_details_thumbnail_height rel="preview[product_images]" wrap_image=true}
	{/if}
{/foreach}

{if $image_pair_var && $product.image_pairs}
	{if $settings.Appearance.thumbnails_gallery == "Y"}
	<input type="hidden" name="no_cache" value="1" />
	{strip}
		<ul id="product_thumbnails" class="center jcarousel-skin">
			<li>
				{if $image_pair_var.image_id == 0}
					{assign var="img_id" value=$image_pair_var.detailed_id}
				{else}
					{assign var="img_id" value=$image_pair_var.image_id}
				{/if}
				{include file="common_templates/image.tpl" images=$image_pair_var object_type="detailed_product" link_class="cm-thumbnails-mini cm-cur-item" image_width=$th_size image_height=$th_size show_thumbnail="Y" show_detailed_link=false make_box=true obj_id="`$product.product_id`_`$img_id`_mini" wrap_image=true}
			</li>
			{foreach from=$product.image_pairs item="image_pair"}
				{if $image_pair}
					<li>
						{if $image_pair.image_id == 0}
							{assign var="img_id" value=$image_pair.detailed_id}
						{else}
							{assign var="img_id" value=$image_pair.image_id}
						{/if}
						{include file="common_templates/image.tpl" images=$image_pair object_type="detailed_product" link_class="cm-thumbnails-mini" image_width=$th_size image_height=$th_size show_thumbnail="Y" show_detailed_link=false make_box=true obj_id="`$product.product_id`_`$img_id`_mini" wrap_image=true}
					</li>
				{/if}
			{/foreach}
		</ul>
		{/strip}

		{script src=$config.ext_js_path|cat:"/js/jquery.jcarousel.js"}

	{else}
		<div class="center" id="product_thumbnails" style="width: {$settings.Thumbnails.product_details_thumbnail_width}px;">
		{strip}
			{if $image_pair_var.image_id == 0}
				{assign var="img_id" value=$image_pair_var.detailed_id}
			{else}
				{assign var="img_id" value=$image_pair_var.image_id}
			{/if}
			{include file="common_templates/image.tpl" images=$image_pair_var object_type="detailed_product" link_class="cm-thumbnails-mini cm-cur-item" image_width=$th_size image_height=$th_size show_thumbnail="Y" show_detailed_link=false obj_id="`$product.product_id`_`$img_id`_mini" make_box=true wrap_image=true}
			{foreach from=$product.image_pairs item="image_pair"}
				{if $image_pair}
						{if $image_pair.image_id == 0}
							{assign var="img_id" value=$image_pair.detailed_id}
						{else}
							{assign var="img_id" value=$image_pair.image_id}
						{/if}
						{include file="common_templates/image.tpl" images=$image_pair object_type="detailed_product" link_class="cm-thumbnails-mini" image_width=$th_size image_height=$th_size show_thumbnail="Y" show_detailed_link=false obj_id="`$product.product_id`_`$img_id`_mini" make_box=true wrap_image=true}
				{/if}
			{/foreach}
		{/strip}
	    </div>
	{/if}
{/if}


{include file="common_templates/previewer.tpl" rel="preview[product_images]"}
{script src="js/product_image_gallery.js"}

<script type="text/javascript">
//<![CDATA[
jQuery.ceProductImageGallery();
//]]>
</script>
{/if}
