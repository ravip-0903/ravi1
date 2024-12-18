{* $Id: sorting.tpl 12279 2011-04-17 14:36:39Z 2tl $ *}

{if $settings.DHTML.customer_ajax_based_pagination == "Y"}
	{assign var="ajax_class" value="cm-ajax cm-ajax-force"}
{/if}

{assign var="curl" value=$config.current_url|fn_query_remove:"sort_by":"sort_order":"result_ids"}
{assign var="sorting" value=""|fn_get_companies_sorting:"false"}
{assign var="pagination_id" value=$id|default:"pagination_contents"}

{if $search.sort_order == "asc"}
	{capture name="sorting_text"}
		<a class="sort-asc">{$sorting[$search.sort_by].description}</a>
	{/capture}
{else}
	{capture name="sorting_text"}
		<a class="sort-desc">{$sorting[$search.sort_by].description}</a>
	{/capture}
{/if}

<div class="right">
<strong>{$lang.sort_by}:</strong>&nbsp;
{capture name="tools_list"}
	<ul>
		{foreach from=$sorting key="option" item="value"}
			{if $search.sort_by == $option}
				{assign var="sort_order" value=$search.sort_order}
			{else}
				{if $value.default_order}
					{assign var="sort_order" value=$value.default_order}
				{else}
					{assign var="sort_order" value="asc"}
				{/if}
			{/if}
			<li><a class="{$ajax_class} {if $search.sort_by == $option}active{/if}" rev="{$pagination_id}" href="{"`$curl`&amp;sort_by=`$option`&amp;sort_order=`$sort_order`"|fn_url}" rel="nofollow" name="sorting_callback">{$value.description}{if $search.sort_by == $option}&nbsp;{if $search.sort_order == "asc"}<img src="{$images_dir}/icons/sort_asc.gif" width="7" height="6" border="0" alt="" />{else}<img src="{$images_dir}/icons/sort_desc.gif" width="7" height="6" border="0" alt="" />{/if}{/if}</a>
			</li>
		{/foreach}
	</ul>
{/capture}
{include file="common_templates/tools.tpl" tools_list=$smarty.capture.tools_list suffix="sort_by" link_text=$smarty.capture.sorting_text no_link=true}
</div>

<hr />
