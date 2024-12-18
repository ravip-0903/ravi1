{* $Id: view_all.tpl 11988 2011-03-05 09:44:33Z 2tl $ *}

{if $view_all_filter}
<div style="float:left; width:100%">{$lang.brand_top_banner|unescape}</div>
<div style="float:left; width:100%">
{if $config.solr}
{split data=$view_all_filter size="4" assign="splitted_filter" preverse_keys=true}
<table cellpadding="5" cellspacing="0" border="0" width="100%" class="view-all">
{foreach from=$splitted_filter item="group"}
<tr valign="top">
	{foreach from=$group item="ranges" key="index"}
	<td class="center" width="25%">
		<div>
			{if $ranges}
				{include file="common_templates/subheader.tpl" title=$index}
				<ul class="arrows-list">
				{foreach from=$ranges item="range"}
					<li><a href="{$range.url}.html">{$range.range_name}</a></li>
				{/foreach}
			</ul>
			{else}&nbsp;{/if}
		</div>
	</td>
	{/foreach}
</tr>
{/foreach}
</table>
{else}
{assign var="view_all_filter" value=$view_all_filter|fn_get_sorted_brands}
{assign var="filter_qstring" value=$smarty.request.q|fn_query_remove:"result_ids":"filter_id":"features_hash"}
{split data=$view_all_filter size="4" assign="splitted_filter" preverse_keys=true}
<table cellpadding="5" cellspacing="0" border="0" width="100%" class="view-all">
{foreach from=$splitted_filter item="group"}

<tr valign="top">
	{foreach from=$group item="ranges" key="index"}
    {assign var="type" value=$index|cat:'A'}   <!--this is for making integer value alphanumeric because the use function work with only string-->
    {if $type|ctype_alnum}
	<td class="center" width="25%">
		<div>
			{if $ranges}
				{include file="common_templates/subheader.tpl" title=$index}
				<ul class="arrows-list">
				{foreach from=$ranges item="range"}
					{assign var="_features_hash" value=$params.features_hash|fn_add_range_to_url_hash:$range}
					<li><a href="{if $range.feature_type == "E" || $range.feature_type == "S"}{"product_features.view?variant_id=`$range.range_id``$cur_features_hash`"|fn_url}{else}{"`$filter_qstring`&features_hash=`$_features_hash`"|fn_url}{/if}">{$range.range_name|fn_text_placeholders}</a></li>
				{/foreach}
			</ul>
			{else}&nbsp;{/if}
		</div>
	</td>
    {/if}
	{/foreach}
</tr>
{/foreach}
</table>
{/if}
</div>

<div class="clearboth"></div>
{/if}
