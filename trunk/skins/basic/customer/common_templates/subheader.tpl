{* $Id: subheader.tpl 11959 2011-03-01 16:15:44Z zeke $ *}
{if $anchor}
<a name="{$anchor}"></a>
{/if}
<h2 class="{$class|default:"subheader"}">
	{$extra}
	{$title}

	{if $tooltip|trim}
		{include file="common_templates/tooltip.tpl" tooltip=$tooltip}
	{/if}
</h2>