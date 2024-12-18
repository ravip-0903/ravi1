{* $Id: bottom.tpl 9983 2010-07-06 13:55:32Z alexions $ *}

<div class="bottom-search center">
	<span class="float-left">&nbsp;</span>
	<span class="float-right">&nbsp;</span>
	{include file="common_templates/search.tpl" hide_advanced_search=true}
</div>
{hook name="index:bottom_links"}
<p class="quick-links">
	{foreach from=$quick_links item="link"}
		<a href="{$link.param|fn_url}">{$link.descr}</a>
	{/foreach}
</p>
{/hook}
{hook name="index:bottom"}
<p class="bottom-copyright class">&copy; {if $smarty.const.TIME|date_format:"%Y" != $settings.Company.company_start_year}{$settings.Company.company_start_year}-{/if}{$smarty.const.TIME|date_format:"%Y"} {$settings.Company.company_name}. &nbsp;{$lang.powered_by} <a href="http://www.cs-cart.com" target="_blank">{$lang.copyright_shopping_cart}</a>
</p>
{/hook}

{if $manifest.copyright}
<p class="bottom-copyright mini">{$lang.skin_by}&nbsp;<a href="{$manifest.copyright_url}">{$manifest.copyright}</a></p>
{/if}

{if "DEBUG_MODE"|defined}
<div class="bug-report">
	<input type="button" onclick="window.open('bug_report.php','popupwindow','width=700,height=450,toolbar=yes,status=no,scrollbars=yes,resizable=no,menubar=yes,location=no,direction=no');" value="Report a bug" />
</div>
{/if}

{if $smarty.request.meta_redirect_url|fn_check_meta_redirect}
	<meta http-equiv="refresh" content="1;url={$smarty.request.meta_redirect_url|fn_check_meta_redirect|fn_url}" />
{/if}