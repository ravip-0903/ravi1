{* $Id: breadcrumbs.tpl 12625 2011-06-03 13:57:09Z alexions $ *}

{if $breadcrumbs && $breadcrumbs|@sizeof > 1}
	<div class="breadcrumbs">
		{strip}
			{foreach from=$breadcrumbs item="bc" name="bcn" key="key"}
				{if $key != "0"}
					<img src="{$images_dir}/icons/breadcrumbs_arrow.gif" class="bc-arrow" border="0" alt="&gt;" />
				{/if}
				{if $bc.link}
					<a href="{$bc.link|fn_url}"{if $additional_class} class="{$additional_class}"{/if}>{$bc.title|unescape|strip_tags|escape:"html"}</a>
				{else}
					{$bc.title|unescape|strip_tags|escape:"html"}
				{/if}
			{/foreach}
		{/strip}
	</div>
{/if}