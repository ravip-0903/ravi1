{* $Id: company_field.tpl 12223 2011-04-10 11:34:37Z gvs $ *}

{if $smarty.const.PRODUCT_TYPE == "MULTIVENDOR" || $smarty.const.PRODUCT_TYPE == "MULTISHOP" || ($settings.Suppliers.enable_suppliers == "Y" && ($smarty.const.CONTROLLER == "products" || $smarty.const.CONTROLLER == "shippings"))}

{if $smarty.const.PRODUCT_TYPE == "MULTIVENDOR" || $smarty.const.PRODUCT_TYPE == "MULTISHOP"}
{assign var="lang_vendor_supplier" value=$lang.vendor}
{else}
{assign var="lang_vendor_supplier" value=$lang.supplier}
{/if}

<div class="form-field">
	<label for="{$id|default:"company_id"}">{$lang_vendor_supplier}:</label>
	{if "COMPANY_ID"|defined}
		{$companies[$smarty.const.COMPANY_ID]}
		<input type="hidden" name="{$name}" id="{$id|default:"company_id"}" value="{$smarty.const.COMPANY_ID}">
	{else}
		<input type="hidden" name="{$name}" id="{$id|default:"company_id"}" value="{$selected|default:0}" />
		{include file="common_templates/ajax_select_object.tpl" data_url="companies.get_companies_list" text=$selected|fn_get_company_name:0 result_elm=$id|default:"company_id" id="`$id`_selector"}
	{/if}
</div>

{/if}