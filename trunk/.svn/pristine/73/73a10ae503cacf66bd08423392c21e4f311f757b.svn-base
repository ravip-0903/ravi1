{strip}

{if $capture_image}
	{capture name="image"}
{/if}

{if !$obj_id}
{math equation="rand()" assign="obj_id"}
{/if}

{assign var="flash" value=false}

{*<pre>{$images|print_r}</pre>*}

{if $show_thumbnail != "Y"}
	{if !$image_width}
		{if $images.icon.image_x}
			{assign var="image_width" value=$images.icon.image_x}
		{/if}
		{if $images.icon.image_y}
			{assign var="image_height" value=$images.icon.image_y}
		{/if}
		{if !$image_width || !$image_height}
			{if $images.detailed.image_x}
				{assign var="image_width" value=$images.detailed.image_x}
			{/if}
			{if $images.detailed.image_y}
				{assign var="image_height" value=$images.detailed.image_y}
			{/if}
		{/if}
	{else}  
		{if $images.icon.image_x && $images.icon.image_y}
			{math equation="new_x * y / x" new_x=$image_width x=$images.icon.image_x y=$images.icon.image_y format="%d" assign="image_height"}
		{/if}
		{if !$image_height && $images.detailed.image_x && $images.detailed.image_y}
			{math equation="new_x * y / x" new_x=$image_width x=$images.detailed.image_x y=$images.detailed.image_y format="%d" assign="image_height"}
		{/if}
	{/if}
{/if}

{if $max_width && !$image_width}
	{if $images.icon.image_x}
		{assign var="image_width" value=$images.icon.image_x}
	{elseif $images.detailed.image_x}
		{assign var="image_width" value=$images.detailed.image_x}
	{/if}
{/if}

{if $max_height && !$image_height}
	{if $images.icon.image_y}
		{assign var="image_height" value=$images.icon.image_y}
	{elseif $images.detailed.image_y}
		{assign var="image_height" value=$images.detailed.image_y}
	{/if}
{/if}

{if $max_width && $image_width && $image_width > $max_width}
	{assign var="image_width" value=$max_width}
	{math equation="new_x * y / x" new_x=$image_width x=$images.icon.image_x|default:$images.detailed.image_x y=$images.icon.image_y|default:$images.detailed.image_y format="%d" assign="image_height"}
{/if}

{if $max_height && $image_height && $image_height > $max_height}
	{assign var="image_height" value=$max_height}
	{math equation="new_y * x / y" new_y=$image_height y=$images.icon.image_y|default:$images.detailed.image_y x=$images.icon.image_x|default:$images.detailed.image_x format="%d" assign="image_width"}
{/if}

{if $images.icon}
	{assign var="image_id" value=$images.image_id}
{elseif $images.detailed}
	{assign var="image_id" value=$images.detailed_id}
{/if}

{if !$images.icon.is_flash && !$images.detailed.is_flash}
	{if $show_thumbnail == "Y" && ($image_width || $image_height) && $image_id}
		{if $image_width && $image_height}
			{assign var="make_box" value=true}
			{assign var="proportional" value=true}
		{/if}
		{assign var="object_type" value=$object_type|default:"product"}
		{if $images.icon.image_path}
			{assign var="image_path" value=$images.icon.image_path}
		{else}
			{assign var="image_path" value=$images.detailed.image_path}
		{/if}

		{assign var="icon_image_path" value=$images|fn_get_img_path:$image_width:$image_height}

		{if $absolute_image_path}
			{assign var="icon_image_path" value=$icon_image_path|fn_convert_relative_to_absolute_image_url}
		{/if}

		{if $make_box && !$proportional}
			{assign var="image_height" value=$image_width}
		{/if}
	{else}
		{assign var="icon_image_path" value=$images.icon.image_path}
		{if !$icon_image_path}
			{if $object_type == "detailed_product" && $images.detailed.image_x}
				{if $settings.Thumbnails.product_details_thumbnail_width}
					{assign var="image_width" value=$settings.Thumbnails.product_details_thumbnail_width}                    
					{if $make_box && !$proportional}
						{assign var="image_height" value=$image_width}
					{else}
						{math equation="new_x * y / x" new_x=$image_width x=$images.detailed.image_x y=$images.detailed.image_y format="%d" assign="image_height"}
					{/if}
				{/if}
			{/if}
			{assign var="icon_image_path" value=$images|fn_get_img_path:$image_width:$image_height}
		{/if}
	{/if}

	{if $show_detailed_link && $images.detailed_id}
		{if $object_type == "detailed_product" && ($settings.Thumbnails.product_detailed_image_width || $settings.Thumbnails.product_detailed_image_height)}
			{assign var="detailed_image_path" value=$images|fn_get_img_path:$settings.Thumbnails.product_detailed_image_width:$settings.Thumbnails.product_detailed_image_height:}
		{elseif $object_type == "detailed_category" && ($settings.Thumbnails.category_detailed_image_width || $settings.Thumbnails.category_detailed_image_height)}
			{assign var="detailed_image_path" value=$images|fn_get_img_path:$settings.Thumbnails.category_detailed_image_width:$settings.Thumbnails.category_detailed_image_height}
		{else}
			{assign var="detailed_image_path" value=$images.detailed.image_path}
		{/if}
	{/if}

	{if $icon_image_path || !$hide_if_no_image}

        {if !$isMobileSlider}
            <input type='hidden' id={$product.product_id} value="{$images.detailed.image_path}"/>
        {/if}
	{if $detailed_image_path || $wrap_image}
	<a id="det_img_link_{$obj_id}" alt="{$product.product}" title="{$product.product}" {if $detailed_image_path && $rel}rel="{$rel}"{/if} {if $rel}rev="{$rel}"{/if} class="{if $detailed_image_path && !$isMobileSlider}jqzoom{/if} {$link_class} {if !$detailed_image_path}default-cursor{/if} {if $detailed_image_path && !$isMobileSlider}cm-previewer{/if}" {if $detailed_image_path}href="{$config.ext_images_host}{$config.full_host_name}{$detailed_image_path}" title="{$images.detailed.alt}"{/if}>
	
	{/if}
	<img class="{$valign} {$class} src2srconscroll" {if $obj_id && !$no_ids}id="det_img_{$obj_id}"{/if} src="{$config.http_path}/blank.gif" src2="{$icon_image_path}" {if $image_width}width="{$image_width}"{/if} {if $image_height && !$no_height}height="{$image_height}"{/if} {if $image_onclick}onclick="{$image_onclick}"{/if} border="0"  {if $alt_text!="" } alt="{$alt_text}" title="{$alt_text}" {else} alt="{$product.product}" title="{$product.product}"{/if}/>
	
	{if $detailed_image_path || $wrap_image}
	</a>
	{/if}

	{/if}

{else}
	{assign var="flash" value=true}
	{if $images.icon.is_flash}
		{assign var="flash_path" value=$images.icon.image_path}
	{else}
		{assign var="flash_path" value=$images.detailed.image_path}
	{/if}

	{assign var="icon_image_path" value=$flash_path|default:$config.no_image_path}
	{assign var="detailed_image_path" value=$flash_path|default:$config.no_image_path}
	
	<div id="{$obj_id}" {if $image_onclick}onmousedown="{$image_onclick}"{/if} class="{$class|default:"object-image"} option-changer" style="{if $image_width}width: {$image_width}px;{/if} {if $image_height}height: {$image_height}px;{/if}">
	<object {if $valign}class="valign"{/if} classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" {if $image_width}width="{$image_width}"{/if} {if $image_height}height="{$image_height}"{/if}>
	<param name="movie" value="{$config.full_host_name}{$flash_path|default:$config.no_image_path}" />
	<param name="quality" value="high" />
	<param name="wmode" value="transparent" />
	<param name="allowScriptAccess" value="sameDomain" />
	{if $flash_vars}
	<param name="FlashVars" value="{$flash_vars}">
	{/if}
	<embed src="{$config.full_host_name}{$flash_path|default:$config.no_image_path}" quality="high" wmode="transparent" {if $image_width}width="{$image_width}"{/if} {if $image_height}height="{$image_height}"{/if} allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" {if $flash_vars}FlashVars="{$flash_vars}"{/if} />
	</object>
	</div>
{/if}

{if $show_detailed_link && $images.detailed_id && !$isMobileSlider}
<p class="{if !$images.detailed_id || $flash}hidden{/if} {$detailed_link_class} center" id="box_det_img_link_{$obj_id}">
	<a class="cm-external-click view-larger-image" rev="det_img_link_{$obj_id}">{$lang.view_larger_image}</a>
</p>
{/if}

{if $capture_image}
	{/capture}
	{capture name="icon_image_path"}
		{$icon_image_path|default:$config.no_image_path}
	{/capture}
	{capture name="detailed_image_path"}
		{$detailed_image_path|default:$config.no_image_path}
	{/capture}
{/if}
{/strip}
