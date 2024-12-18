{* $Id: mainbox_general.tpl 12073 2011-03-18 12:12:26Z 2tl $ *}
{if $anchor}
<a name="{$anchor}"></a>
{/if}
<div class="block-packs-general{if $details_page} details-page{/if}">
	{if $title}
	<h1 class="block-packs-title"><span>{$title}</span></h1>
	{/if}
	<div class="block-packs-body">{$content}</div>
</div>
