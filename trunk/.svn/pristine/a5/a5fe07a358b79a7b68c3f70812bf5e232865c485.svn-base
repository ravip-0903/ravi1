{* $Id: emails_search_form.tpl 12235 2011-04-11 13:11:24Z alexions $ *}
{literal}
<style type="text/css">
	table.search-header td {
		padding: 0 10px 12px 11px;
		width: 252px;
	}
</style>
{/literal}
{capture name="section"}

<form action="{""|fn_url}" name="manifest_search_form" method="get">

{if $smarty.request.redirect_url}
<input type="hidden" name="redirect_url" value="{$smarty.request.redirect_url}" />
{/if}
{if $selected_section != ""}
<input type="hidden" id="selected_section" name="selected_section" value="{$selected_section}" />
{/if}

{$extra}

<table cellpadding="10" cellspacing="0" border="0" class="search-header order_search_mng_bymsc">
<tr>
	<td class="search-field">
		<label for="manifest_id">{$lang.manifest_id}:</label>
		<div class="break">
			<input type="text" name="manifest_id" id="manifest_id" value="{$search.manifest_id}" size="8" class="input-text" />
		</div>
	</td>
    <!--<td class="nowrap search-field">
		<label for="pickup_by">{$lang.pickup_by}:</label>
		<div class="break">
			<input type="text" name="pickup_by" id="pickup_by" value="{$search.pickup_by}" size="20" class="input-text" />
		</div>
	</td>
    <td class="nowrap search-field">
		<label for="generated_by">{$lang.report_generated_by}:</label>
		<div class="break">
			<input type="text" name="generated_by" id="generated_by" value="{$search.generated_by}" size="20" class="input-text" />
		</div>
	</td>
    <td class="nowrap search-field">
		<label for="pickup_location">{$lang.pickup_location}:</label>
		<div class="break">
			<input type="text" name="pickup_location" id="pickup_location" value="{$search.pickup_location}" size="20" class="input-text" />
		</div>
	</td>-->
    <td class="search-field">
		<label for="order_id">{$lang.order_id}:</label>
		<div class="break">
			<input type="text" name="order_id" id="order_id" value="{$search.order_id}" size="8" class="input-text" />
		</div>
	</td>
    <td class="search-field">
    	{if $smarty.const.PRODUCT_TYPE == "MULTIVENDOR"}
            {include file="common_templates/select_supplier_vendor.tpl"}
        {/if}
    </td>
    </tr>
    <tr>
    <td class="nowrap search-field">
		<label for="pickup_by">{$lang.pickup_by}:</label>
		<div class="break">
			<input type="text" name="pickup_by" id="pickup_by" value="{$search.pickup_by}" size="20" class="input-text" />
		</div>
	</td>
    <td class="nowrap search-field">
		<label for="generated_by">{$lang.report_generated_by}:</label>
		<div class="break">
			<input type="text" name="generated_by" id="generated_by" value="{$search.generated_by}" size="20" class="input-text" />
		</div>
	</td>
    <td class="nowrap search-field">
		<label for="pickup_location">{$lang.pickup_location}:</label>
		<div class="break">
			<input type="text" name="pickup_location" id="pickup_location" value="{$search.pickup_location}" size="20" class="input-text" />
		</div>
	</td>
	<!--<td class="buttons-container">
		{*include file="buttons/button.tpl" but_text=$lang.search but_name="dispatch[`$dispatch`]" but_role="submit"*}
	</td>-->
</tr>
</table>
{*capture name="advanced_search"*}
<div class="search-field">
<label for="carrier_name" style="text-align: right; width: 100px;">{$lang.carrier}:</label>
			{assign var="manifest_type" value=""|get_all_carrier_list}
			{html_checkboxes name="carrier_name" options=$manifest_type.carrier_list selected=$search.carrier_name columns=$columns|default:4}
</div>
<div class="search-field">
	<label style="text-align: right; width: 100px;">{$lang.period}:</label>
	{include file="common_templates/period_selector.tpl" period=$search.period form_name="manifest_search_form"}
</div>
<div class="search-field">
<label for="manifest_type" style="text-align: right; width: 100px;">{$lang.manifest_type}:</label>
			{assign var="manifest_type" value=""|get_manifest_type}
			{html_checkboxes name="manifest_type" options=$manifest_type.manifest_lookup selected=$search.manifest_type columns=$columns|default:4}
</div>
<div class="search-field">
	{include file="buttons/button.tpl" but_text=$lang.search but_name="dispatch[`$dispatch`]" but_role="submit"}
</div>
{*/capture*}
{*include file="common_templates/advanced_search.tpl" content=$smarty.capture.advanced_search dispatch=$dispatch view_type=""*}

</form>

{/capture}
{include file="common_templates/section.tpl" section_content=$smarty.capture.section}
