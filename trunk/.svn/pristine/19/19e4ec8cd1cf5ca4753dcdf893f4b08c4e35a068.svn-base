{* $Id: products_search_form.tpl 12544 2011-05-27 10:34:19Z bimib $ *}

{capture name="section"}
{if $page_part}
    {assign var="_page_part" value="#`$page_part`"}
{/if}
<form action="{""|fn_url}" name="promotion_form" method="get" class="cm-disable-empty {$form_meta}">
<input type="hidden" name="type" value="{$search_type|default:"simple"}" />
{if $smarty.request.redirect_url}
<input type="hidden" name="redirect_url" value="{$smarty.request.redirect_url}" />
{/if}
{if $selected_section != ""}
<input type="hidden" id="selected_section" name="selected_section" value="{$selected_section}" />
{/if}

{if $put_request_vars}
{foreach from=$smarty.request key="k" item="v"}
{if $v}
<input type="hidden" name="{$k}" value="{$v}" />
{/if}
{/foreach}
{/if}

{$extra}

<table cellspacing="0" border="0" class="search-header">
<tr>
	<td class="nowrap search-field">
		<label>{$lang.find_results_with}:</label>
		<div class="break">
			<input type="text" name="q" size="20" value="{$search.q}" class="search-input-text" />
			{include file="buttons/search_go.tpl" search="Y" but_name="$dispatch"}&nbsp;
		</div>
	</td>

	<td class="buttons-container">
		{include file="buttons/search.tpl" but_name="dispatch[$dispatch]" but_role="submit"}
	</td>
</tr>
</table>

</form>

{/capture}
{include file="common_templates/section.tpl" section_content=$smarty.capture.section}
