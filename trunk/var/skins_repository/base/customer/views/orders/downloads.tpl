{* $Id: downloads.tpl 11786 2011-02-08 09:44:48Z 2tl $ *}

{if $products}

	{include file="common_templates/pagination.tpl"}

	{foreach from=$products item=dp}
	<a name="{$dp.order_id}_{$dp.product_id}"></a>
	{include file="views/products/download.tpl" product=$dp no_capture=true}
	{/foreach}

	{include file="common_templates/pagination.tpl"}

{else}
	<p class="no-items">{$lang.text_downloads_empty}</p>
{/if}

{capture name="mainbox_title"}{$lang.downloads}{/capture}
