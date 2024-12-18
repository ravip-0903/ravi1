{capture name="section"}

<form name="companies_search_form" action="{""|fn_url}" method="get" class="{$form_meta}"> 

{if $smarty.request.redirect_url}
<input type="hidden" name="redirect_url" value="{$smarty.request.redirect_url}" />
{/if}

{if $selected_section != ""}
<input type="hidden" id="selected_section" name="selected_section" value="{$selected_section}" />
{/if}

{if $search.user_type}
<input type="hidden" name="user_type" value="{$search.user_type}" />
{/if}

{if $company_id}
<input type="hidden" name="company_id" value="{$company_id}" />
{/if}

{if $smarty.request.dispatch == 'billing_detail_master.billing_lookup'}
<input type="hidden" name="search_for_billing" value="for billing" />
{/if}

{if $smarty.request.dispatch == 'companies_related_master.companies_commision'}
<input type="hidden" name="search_for_company_commision" value="company commision" />
{/if}

{if $put_request_vars} 
	{foreach from=$smarty.request key="k" item="v"} 
		{if $v} 
			<input type="hidden" name="{$k}" value="{$v}" /> 
		{/if} 
	{/foreach} 
{/if}

{$extra}

<table cellpadding="0" cellspacing="0" border="0" class="search-header">
<tr>
	<td class="search-field nowrap">
		<label for="elm_name">{$lang.name}:</label>
		<div class="break">
			<input class="search-input-text" type="text" name="company" id="elm_name" value="{$search.company}" />
			{include file="buttons/search_go.tpl" search="Y" but_name=$dispatch}
		</div>
	</td>
	<td class="search-field">
		<label for="elm_email">{$lang.email}:</label>
		<div class="break">
			<input class="input-text" type="text" name="email" id="elm_email" value="{$search.email}" />
		</div>
	</td>
    
    {if $smarty.request.dispatch eq 'companies_related_master.companies_commision'}
    <td class="search-field">
		<label for="elm_set_com">Commision:</label>
		<div class="break">
			<input type="checkbox" id="elm_set_com" name="set_com_y" value="{if isset($search.set_com_y)}{$search.set_com_y} {else} yes {/if}" class="input-text" />Saved </div>
            <div class="break">
            <input type="checkbox" id="elm_set_com2" name="set_com_n" value="{if isset($search.set_com_y)}{$search.set_com_n} {else} no {/if}" class="input-text" />Unsaved 
		</div>
	</td>
    {/if}
	<td class="buttons-container">
		{include file="buttons/search.tpl" but_name="dispatch[$dispatch]" but_role="submit" method="GET"}
	</td>
</tr>
</table>

{capture name="advanced_search"}

<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr valign="top">
	<td>

		<div class="search-field">
			<label for="elm_address">{$lang.address}:</label>
			<input class="input-text" type="text" name="address" id="elm_address" value="{$search.address}" />
		</div>
		<div class="search-field">
			<label for="elm_city">{$lang.city}:</label>
			<input class="input-text" type="text" name="city" id="elm_city" value="{$search.city}" />
		</div>
        {if $smarty.request.dispatch neq 'billing_detail_master.billing_lookup'}
		<div class="search-field">
			<label for="srch_country" class="cm-country cm-location-search">{$lang.country}:</label>
			<select id="srch_country" name="country" class="cm-location-search">
				<option value="">- {$lang.select_country} -</option>
				{foreach from=$countries item=country}
				<option value="{$country.code}" {if $search.country == $country.code}selected="selected"{/if}>{$country.country}</option>
				{/foreach}
			</select>
		</div>

		<div class="search-field">
			<label for="srch_state" class="cm-state cm-location-search">{$lang.state}:</label>
			<input type="text" id="srch_state_d" name="state" maxlength="64" value="{$search.state}" disabled="disabled" class="input-text" />
			<select id="srch_state" class="hidden" name="state">
				<option value="">- {$lang.select_state} -</option>
			</select>
			<input type="hidden" id="srch_state_default" value="{$smarty.request.state}" />
		</div>
		{/if}
        
		<div class="search-field">
			<label for="status">{$lang.status}:</label>
			<select name="status" id="status">
				<option value="">--</option>
				<option value="A" {if $search.status == "A"}selected="selected"{/if}>{$lang.active}</option>
				<option value="B" {if $search.status == "B"}selected="selected"{/if}>{$lang.newnologin}</option>
				<option value="P" {if $search.status == "P"}selected="selected"{/if}>{$lang.pending}</option>
				<option value="N" {if $search.status == "N"}selected="selected"{/if}>{$lang.newmerchant}</option>
				<option value="D" {if $search.status == "D"}selected="selected"{/if}>{$lang.disabled}</option>
				<option value="S" {if $search.status == "S"}selected="selected"{/if}>{$lang.suspend}</option>
				<option value="R" {if $search.status == "R"}selected="selected"{/if}>{$lang.requestapproval}</option>
			</select>
		</div>

	</td>
	<td>

		<div class="search-field">
			<label for="elm_zipcode">{$lang.zip_postal_code}:</label>
			<input class="input-text" type="text" name="zipcode" id="elm_zipcode" value="{$search.zipcode}" />
		</div>

		<div class="search-field">
			<label for="elm_phone">{$lang.phone}:</label>
			<input class="input-text" type="text" name="phone" id="elm_phone" value="{$search.phone}" />
		</div>

		<div class="search-field">
			<label for="elm_url">{$lang.url}:</label>
			<input class="input-text" type="text" name="url" id="elm_url" value="{$search.url}" />
		</div>

		<div class="search-field">
			<label for="elm_fax">{$lang.fax}:</label>
			<input class="input-text" type="text" name="fax" id="elm_fax" value="{$search.fax}" />
		</div>
        
        {if $smarty.request.dispatch eq 'billing_detail_master.billing_lookup'}
        <div class="search-field">
			<label for="elm_fax">Date:</label>From
			{include file="common_templates/calendar.tpl" date_id="elm_date_from" date_name="date_from" date_val=$date_from|default:"" start_year=$settings.Company.company_start_year}
            To
			{include file="common_templates/calendar.tpl" date_id="elm_date_to" date_name="date_to" date_val=$date_to|default:"" start_year=$settings.Company.company_start_year}
		</div>
		{/if}
	</td>
</tr>
</table>

{hook name="companies:search_form"}
{/hook}

{/capture}

{include file="common_templates/advanced_search.tpl" content=$smarty.capture.advanced_search dispatch=$dispatch view_type="companies"}

</form>

{/capture}
{include file="common_templates/section.tpl" section_content=$smarty.capture.section}
