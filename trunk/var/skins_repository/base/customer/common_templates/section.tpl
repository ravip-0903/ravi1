{* $Id: section.tpl 11890 2011-02-22 10:25:13Z zeke $ *}

{assign var="id" value=$section_title|md5|string_format:"s_%s"}
{math equation="rand()" assign="rnd"}
{if $smarty.cookies.$id || $collapse}
	{assign var="collapse" value=true}
{else}
	{assign var="collapse" value=false}
{/if}

<div class="section-border{if $class} {$class}{/if}" id="ds_{$rnd}">
	<h3 class="section-title">
		<a class="cm-combo-{if !$collapse}off{else}on{/if} cm-combination cm-save-state cm-ss-reverse" id="sw_{$id}">{$section_title}</a>
	</h3>
	<div id="{$id}" class="{$section_body_class|default:"section-body"} {if $collapse}hidden{/if}">{$section_content}</div>
</div>
