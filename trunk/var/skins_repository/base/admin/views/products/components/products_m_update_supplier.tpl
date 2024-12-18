{* $Id: products_m_update_supplier.tpl 12134 2011-03-31 08:06:59Z 2tl $ *}

	{if $override_box}
		{assign var="elm_name" value="override_products_data[`$field`]"}
	{else}
		{assign var="elm_name" value="products_data[`$product.product_id`][`$field`]"}
	{/if}
	<input type="hidden" name="{$elm_name}" id="field_{$field}_{$product.product_id}_" value="{$product.$field}" {if $override_box}disabled="disabled"{/if} />
	{include file="common_templates/ajax_select_object.tpl" data_url="companies.get_companies_list" text=$product.$field|fn_get_company_name result_elm="field_`$field`_`$product.product_id`_" id="prod_`$product.product_id`"}
