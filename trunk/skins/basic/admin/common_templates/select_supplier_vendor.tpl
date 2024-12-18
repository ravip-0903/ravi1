{* $Id: select_supplier_vendor.tpl 12134 2011-03-31 08:06:59Z 2tl $ *}

{assign var="id" value=$id|default:"company_id"}
{assign var="name" value=$name|default:"company_id"}

{if $smarty.const.PRODUCT_TYPE == "MULTIVENDOR" || $smarty.const.PRODUCT_TYPE == "MULTISHOP"}
	{assign var="lang_search_by_vendor_supplier" value=$lang.search_by_vendor}
{elseif $smarty.const.PRODUCT_TYPE == "PROFESSIONAL"}
	{assign var="lang_search_by_vendor_supplier" value=$lang.search_by_supplier}
{/if}

{if !"COMPANY_ID"|defined}

<div class="{$class|default:"search-field"}">
	<input type="hidden" name="{$name}" id="{$id}" value="{$search.company_id|default:''}" />
	{include file="common_templates/ajax_select_object.tpl" label=$lang_search_by_vendor_supplier data_url="companies.get_companies_list?show_all=Y&search=Y" text=$search.company_id|fn_get_company_name result_elm=$id id="`$id`_selector"}
</div>

{else}

<div class="{$class|default:"search-field"}">
	<label for="{$id}">{$lang_search_by_vendor_supplier}:</label>
	<span id="{$id}">{$s_companies[$smarty.const.COMPANY_ID].company}</span>
	<input type="hidden" name="{$name}" id="{$id}" value="{$smarty.const.COMPANY_ID}">
</div>

{/if}
