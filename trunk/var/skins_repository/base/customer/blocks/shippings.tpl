{* $Id: shippings.tpl 10972 2010-10-21 13:58:18Z klerik $ *}

{if $items}
<p class="center image-border">
	{foreach from=$items item=image}
		<img src="{$image.image_path}" width="{$image.image_x}" height="{$image.image_y}" alt="{$image.alt}" />
	{/foreach}
</p>
{/if}