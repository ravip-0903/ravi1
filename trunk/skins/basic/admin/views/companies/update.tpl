{* $Id: update.tpl 12766 2011-06-24 07:31:07Z subkey $ *}

{if $smarty.const.PRODUCT_TYPE == "MULTIVENDOR" || $smarty.const.PRODUCT_TYPE == "MULTISHOP"}
{assign var="lang_available_for_vendor_supplier" value=$lang.available_for_vendor}
{assign var="lang_new_vendor_supplier" value=$lang.new_vendor}
{assign var="lang_editing_vendor_supplier" value=$lang.editing_vendor}
{else}
{assign var="lang_available_for_vendor_supplier" value=$lang.available_for_supplier}
{assign var="lang_new_vendor_supplier" value=$lang.new_supplier}
{assign var="lang_editing_vendor_supplier" value=$lang.editing_supplier}
{/if}


{include file="views/profiles/components/profiles_scripts.tpl"}

{capture name="mainbox"}

{capture name="tabsbox"}
{** /Item menu section **}

<form action="{""|fn_url}" method="post" class="{$form_class}" id="company_update_form" enctype="multipart/form-data"> {* company update form *}
{* class="cm-form-highlight"*}
<input type="hidden" name="fake" value="1" />
<input type="hidden" name="selected_section" id="selected_section" value="{$smarty.request.selected_section}" />
<input type="hidden" name="company_id" value="{$company_data.company_id}" />

{** General info section **}
<div id="content_detailed" > {* content detailed *}
<fieldset style="float:left; display:inline; width:49%;">

{include file="common_templates/subheader.tpl" title=$lang.information}

{hook name="companies:general_information"}
<div class="form-field">
	<label for="company_description_company" class="cm-required">{$lang.company}:</label>
	<input type="text" name="company_data[company]" id="company_description_company" size="32" value="{$company_data.company}" class="input-text" />
</div>

{if !"COMPANY_ID"|defined}
	{include file="common_templates/select_status.tpl" input_name="company_data[status]" id="company_data" obj=$company_data}
{else}
<div class="form-field">
	<label>{$lang.status}:</label>
	<div class="select-field">
		<input type="radio" checked="checked" class="radio" /><label>{if $company_data.status == "A"}{$lang.active}{elseif $company_data.status == "P"}{$lang.pending}{elseif $company_data.status == "D"}{$lang.disabled}{/if}</label>
	</div>
</div>
{/if}

<!--Modified by clues dev to add mark as new -->
 	<div class="form-field">
		<label for="show_as_new">Mark as New:</label>
		<input type="radio" {if $company_data.show_as_new == "Y"}checked="checked"{/if} id="show_as_new" value="Y" name="company_data[show_as_new]" />Yes
        <input type="radio" {if $company_data.show_as_new == "N"}checked="checked"{/if} id="show_as_new" value="N" name="company_data[show_as_new]" />No
	</div>
    <div class="form-field">
		<label for="is_ngo">NGO:</label>
		<input type="radio" {if $company_data.is_ngo == "Y"}checked="checked"{/if} id="is_ngo" value="Y" name="company_data[is_ngo]" />Yes
        <input type="radio" {if $company_data.is_ngo == "N"}checked="checked"{/if} id="is_ngo" value="N" name="company_data[is_ngo]" />No
	</div>
  <!--Modified by clues dev  to add mark as new -->

<div class="form-field">
	<label for="company_fulfillment">{$lang.fulfillment_type}:</label>
	<select name="company_data[fulfillment_id]" id="company_fulfillment">
		<option value="">-- Select --</option>
		{foreach from=$fulfillment item="fulfillment" key="lang_code"}
			<option value="{$fulfillment.fulfillment_id}" {if $fulfillment.fulfillment_id == $company_data.fulfillment_id}selected="selected"{/if}>{$fulfillment.description}</option>
		{/foreach}
	</select>
</div>

<div class="form-field">
	<label for="company_language">{$lang.language}</label>
	<select name="company_data[lang_code]" id="company_language">
		{foreach from=$languages item="language" key="lang_code"}
			<option value="{$lang_code}" {if $lang_code == $company_data.lang_code}selected="selected"{/if}>{$language.name}</option>
		{/foreach}
	</select>
</div>

{if $smarty.const.PRODUCT_TYPE == "MULTIVENDOR" || $smarty.const.PRODUCT_TYPE == "MULTISHOP"}
	{if $smarty.const.MODE == "add"}
		{literal}
		<script type="text/javascript">
		//<![CDATA[
		function fn_toggle_required_fields() {

			if ($('#company_description_vendor_admin').attr('checked')) {
				$('#company_description_username').removeAttr('disabled');
				$('#company_description_first_name').removeAttr('disabled');
				$('#company_description_last_name').removeAttr('disabled');

				$('.cm-profile-field').each(function(index){
					$('#' + $(this).attr('for')).removeAttr('disabled');
				});

			} else {
				$('#company_description_username').attr('disabled', true);
				$('#company_description_first_name').attr('disabled', true);
				$('#company_description_last_name').attr('disabled', true);

				$('.cm-profile-field').each(function(index){
					$('#' + $(this).attr('for')).attr('disabled', true);
				});
			}
		}
		//]]>
		</script>
		{/literal}

		<div class="form-field">
			<label for="company_description_vendor_admin">{$lang.create_administrator_account}:</label>
			<input type="checkbox" name="company_data[is_create_vendor_admin]" id="company_description_vendor_admin" checked="checked" value="Y" onchange="fn_toggle_required_fields();" class="checkbox">
		</div>
		{if $settings.General.use_email_as_login != 'Y'}
		<div class="form-field" id="company_description_admin">
			<label for="company_description_username" class="cm-required">{$lang.account_name}:</label>
			<input type="text" name="company_data[admin_username]" id="company_description_username" size="32" value="{$company_data.admin_username}" class="input-text" />
		</div>
		<div class="form-field">
			<label for="company_description_first_name" class="cm-required">{$lang.first_name}:</label>
			<input type="text" name="company_data[admin_firstname]" id="company_description_first_name" size="32" value="{$company_data.admin_first_name}" class="input-text" />
		</div>
		<div class="form-field">
			<label for="company_description_last_name" class="cm-required">{$lang.last_name}:</label>
			<input type="text" name="company_data[admin_lastname]" id="company_description_last_name" size="32" value="{$company_data.admin_last_name}" class="input-text" />
		</div>
		{/if}
	{/if}
    
    <!--Modified by chandan-->
    <!--{if $smarty.const.MODE == "update"}
    	{assign var="merchant" value=$company_data.company_id|fn_get_company_contactperson}
        <div class="form-field">
			<label>Merchant Contact Person Name:</label>
			<span {if $merchant.firstname == '' || $merchant.lastname == ''} style="color:red;"{/if}>{if $merchant.firstname != '' || $merchant.lastname != ''}{$merchant.firstname}&nbsp;{$merchant.lastname} {else}{$lang.comple_admin_profile}{/if}</span>
		</div>
    {/if}-->
    
     {if $smarty.const.MODE == "update"}
    	{assign var="merchant" value=$company_data.company_id|fn_get_company_contactperson}
        <div class="form-field">
			<label>Merchant Contact Person Name:</label>
			<span>{$company_data.request_account_name}</span>
		</div>
    {/if}
    <!--Modified by chandan-->
	{if !"COMPANY_ID"|defined}
	<div class="form-field">
		<label for="company_vendor_commission">{$lang.vendor_commission}:</label>
		<input type="text" name="company_data[commission]" id="company_vendor_commission" value="{$company_data.commission}" class="input-text-medium" />
		<select name="company_data[commission_type]">
			<option value="A" {if $company_data.commission_type == "A"}selected="selected"{/if}>{$currencies.$primary_currency.symbol}</option>
			<option value="P" {if $company_data.commission_type == "P"}selected="selected"{/if}>%</option>
		</select>
	</div>
	{/if}
{/if}

{if $smarty.const.MODE == "add" && ($smarty.const.PRODUCT_TYPE == "MULTIVENDOR" || $smarty.const.PRODUCT_TYPE == "MULTISHOP")}
	{include file="views/profiles/components/profile_fields.tpl" section="C" title=$lang.contact_information}
{else}
	{include file="common_templates/subheader.tpl" title=$lang.contact_information}
{/if}
<div class="form-field">
	<label for="company_description_email" class="cm-required cm-email">{$lang.email}:</label>
	<input type="text" name="company_data[email]" id="company_description_email" size="32" value="{$company_data.email}" class="input-text" />
</div>

<div class="form-field">
	<label for="company_description_phone" class="cm-required">{$lang.phone}:</label>
	<input type="text" name="company_data[phone]" id="company_description_phone" size="32" value="{$company_data.phone}" class="input-text" />
</div>

<div class="form-field">
	<label for="company_description_url">{$lang.url}:</label>
	<input type="text" name="company_data[url]" id="company_description_url" size="32" value="{$company_data.url}" class="input-text" />
</div>

<div class="form-field">
	<label for="company_description_fax">{$lang.fax}:</label>
	<input type="text" name="company_data[fax]" id="company_description_fax" size="32" value="{$company_data.fax}" class="input-text" />
</div>

{if $smarty.const.MODE == "add" && ($smarty.const.PRODUCT_TYPE == "MULTIVENDOR" || $smarty.const.PRODUCT_TYPE == "MULTISHOP")}
	{include file="views/profiles/components/profile_fields.tpl" section="B" title=$lang.shipping_address shipping_flag=false}
{else}
	{include file="common_templates/subheader.tpl" title=$lang.shipping_address}
{/if}

<div class="form-field">
	<label for="company_address_address" class="cm-required">{$lang.address}:</label>
	<input type="text" name="company_data[address]" id="company_address_address" size="32" value="{$company_data.address}" class="input-text" />
</div>

<div class="form-field">
	<label for="company_address_city" class="cm-required">{$lang.city}:</label>
	<input type="text" name="company_data[city]" id="company_address_city" size="32" value="{$company_data.city}" class="input-text" />
</div>

<div class="form-field">
	<label for="company_address_country" class="cm-required cm-country cm-location-shipping">{$lang.country}:</label>
	{assign var="_country" value=$company_data.country|default:$settings.General.default_country}
	<select id="company_address_country" name="company_data[country]">
		<option value="">- {$lang.select_country} -</option>
		{foreach from=$countries item=country}
		<option {if $_country == $country.code}selected="selected"{/if} value="{$country.code}">{$country.country}</option>
		{/foreach}
	</select>
</div>

<div class="form-field">
	{assign var="country_code" value=$company_data.country|default:$settings.General.default_country}
	{assign var="state_code" value=$company_data.state|default:$settings.General.default_state}
	<label for="company_address_state" class="cm-required cm-state cm-location-shipping">{$lang.state}:</label>
	<select id="company_address_state" name="company_data[state]" {if !$states.$country_code}class="hidden"{/if}>
		<option value="">- {$lang.select_state} -</option>
		{* Initializing default states *}
		{if $states.$country_code}
			{foreach from=$states.$country_code item=state}
				<option {if $state_code == $state.code}selected="selected"{/if} value="{$state.code}">{$state.state}</option>
			{/foreach}
		{/if}
	</select>
	<input type="text" id="company_address_state_d" name="company_data[state]" size="32" maxlength="64" value="{$company_data.state}" {if $states.$country_code}disabled="disabled"{/if} class="input-text {if $states.$country_code}hidden{/if} cm-skip-avail-switch" />
	<input type="hidden" id="company_address_state_default" value="{$state_code}" />
</div>

<div class="form-field">
	<label for="company_address_zipcode" class="cm-required cm-zipcode cm-location-shipping">{$lang.zip_postal_code}:</label>
	<input type="text" name="company_data[zipcode]" id="company_address_zipcode" size="32" value="{$company_data.zipcode}" class="input-text" />
</div>
{/hook}

</fieldset>

<fieldset style="float:left; display:inline; width:49%;">

    <div style="float:left; display:inline; margin-top:200px; border:1px solid #999;">
    <div class="form-field">
		<label>Type of Business/ Category:</label>
		<select class="business_type" name="company_data[business_type]" id="company_description_business_type">
        	<option value="">--Select--</option>
        	{foreach from=$root_categories item=root_categories}
    			<option value="{$root_categories.category_id}" {if $company_data.business_type == $root_categories.category_id} selected="selected" {/if}>{$root_categories.category}</option>
			{/foreach}
        </select>
	</div>
    
    <div class="form-field">
		<label>Other Category:</label>
		<input type="text" name="company_data[business_type_other]" id="company_description_business_type_other" size="32" class="input-text" value="{$company_data.business_type_other}" />
	</div>
    
    <div class="form-field">
		<label>Do you already sell online?</label>
		<select name="company_data[sell_online]" id="company_description_sell_online">
        	<option value="">--Select--</option>
        	<option value="yes" {if $company_data.sell_online == 'yes'} selected="selected" {/if}>Yes</option>
           	<option value="no" {if $company_data.sell_online == 'no'} selected="selected" {/if}>No</option>
        </select>
	</div>
    
    <div class="form-field">
		<label>Do you have a ready online catalog?</label>
		<select name="company_data[online_cat]" id="company_description_online_cat">
        	<option value="">--Select--</option>
            <option value="yes" {if $company_data.online_cat == 'yes'} selected="selected" {/if}>Yes</option>
           	<option value="no" {if $company_data.online_cat == 'no'} selected="selected" {/if}>No</option>
        </select>
	</div>
</div>
</fieldset>
</div> 


{* /content detailed *}
{** /General info section **}



{** Company description section **}
<div id="content_description" class="hidden"> {* content description *}
<fieldset>
<div class="form-field">
	<label for="company_description">{$lang.description}:</label>
	<textarea id="company_description" name="company_data[company_description]" cols="55" rows="8" class="cm-wysiwyg input-textarea-long">{$company_data.company_description}</textarea>
	
</div>
</fieldset>
</div> {* /content description *}
{** /Company description section **}



{** Company logos section **}
<div id="content_logos" class="hidden"> {* content logos *}
&nbsp;
{foreach from=$manifest_definition key="a" item="m" name="fel"}
{assign var="sa" value="skin_name_`$m.skin`"}
<p>{$lang[$m.text]}</p>
<div class="clear">
	<div class="float-left">
		{include file="common_templates/fileuploader.tpl" var_name="logotypes[`$a`]"}
	</div>
	<div class="float-left attach-images-alt logo-image">
		{if $manifests[$m.skin][$m.name].vendor}
		<img class="solid-border" src="{$config.images_path}{$manifests[$m.skin][$m.name].filename}" width="{$manifests[$m.skin][$m.name].width}" height="{$manifests[$m.skin][$m.name].height}" />
		{else}
		<img class="logo-empty" src="{$config.no_image_path}" />
		{/if}
		<label for="alt_text_{$a}">{$lang.alt_text}:</label>
		<input type="text" class="input-text cm-image-field" id="alt_text_{$a}" name="logo_alt[{$a}]" value="{$manifests[$m.skin][$m.name].alt}" />
	</div>
</div>
{if !$smarty.foreach.fel.last}
<hr />
{/if}
{/foreach}
</div> {* /content logos *}
{** /Company logos section **}



{** Company categories section **}
<div id="content_categories" class="hidden"> {* content categories *}
	{include file="pickers/categories_picker.tpl" multiple=true input_name="company_data[categories]" item_ids=$company_data.categories data_id="category_ids" no_item_text=$lang.text_all_items_included|replace:"[items]":$lang.categories use_keys="N"}
</div> {* /content categories *}
{** /Company categories section **}


{if !"COMPANY_ID"|defined}
{** Shipping methods section **}
<div id="content_shipping_methods" class="hidden"> {* shipping_methods *}
		<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table">
		<tr>
			<th width="50%">{$lang.shipping_methods}</th>
			<th class="center">{$lang_available_for_vendor_supplier}</th>
		</tr>
		{if $company_data.shippings}
			{assign var="shippings_ids" value=","|explode:$company_data.shippings}
		{/if}
		{foreach from=$shippings item="shipping" key="shipping_id"}
		<tr {cycle values="class=\"table-row\", "}>
			<td><a href="{"shippings.update?shipping_id=`$shipping_id`"|fn_url}">{$shipping}</a></td>
			<td class="center">
				<input type="checkbox" class="checkbox"{if !$company_data.company_id || $shipping_id|in_array:$shippings_ids} checked="checked"{/if} name="company_data[shippings][]" value="{$shipping_id}">
			</td>
		</tr>
		{foreachelse}
		<tr class="no-items">
			<td colspan="2"><p>{$lang.no_items}</p></td>
		</tr>
		{/foreach}
		</table>
</div> {* /content shipping_methods *}
{** /Shipping methods section **}
{/if}

<div id="content_addons">
	{hook name="companies:detailed_content"}{/hook}
</div>

{** Company warehouse section **}
<div id="content_warehouse_info" class="hidden"> {* content warehouse *}
<fieldset>
<div class="form-field">
	<label for="warehouse_region">{$lang.warehouse_region}:</label>
	<select name="warehouse_data[region]" id="warehouse_region">
		<option value="">-- Select --</option>
		{foreach from=$region_list item="region" key="lang_code"}
			<option value="{$region.region_id}" {if $region.region_id == $warehouse_data.region_code}selected="selected"{/if}>{$region.region_name}</option>
		{/foreach}
	</select>
</div>
<div class="form-field">
	<label for="warehouse_address1" class="cm-address1">{$lang.warehouse_addr1}:</label>
    <!--<textarea id="warehouse_address1" name="warehouse_data[address1]" cols="55" rows="8" class="cm-wysiwyg input-textarea-long">{$warehouse_data.warehouse_address1}</textarea> -->
    <input type="text" id="warehouse_address1" name="warehouse_data[address1]" value="{$warehouse_data.warehouse_address1}" class="input-text" size="60" />
</div>
<div class="form-field">
	<label for="warehouse_address2" class="cm-address2">{$lang.warehouse_addr2}:</label>
	<!--<textarea id="warehouse_address2" name="warehouse_data[address2]" cols="55" rows="8" class="cm-wysiwyg input-textarea-long">{$warehouse_data.warehouse_address2}</textarea> -->
    <input type="text" id="warehouse_address2" name="warehouse_data[address2]" value="{$warehouse_data.warehouse_address2}" class="input-text" size="60" />
</div>
<div class="form-field">
	<label for="warehouse_city" class="cm-city">{$lang.warehouse_city}:</label>
	<input type="text" name="warehouse_data[city]" id="warehouse_city" size="32" value="{$warehouse_data.warehouse_city}" class="input-text" />
</div>
<!--<div class="form-field">
	<label for="warehouse_state" class="cm-state">{$lang.warehouse_state}:</label>
	<input type="text" name="warehouse_data[state]" id="warehouse_state" size="32" value="{$warehouse_data.warehouse_state}" class="input-text" /> 
    
</div>-->
<div class="form-field">
	{assign var="country_code" value=$company_data.country|default:$settings.General.default_country}
	{assign var="warehouse_state_code" value=$warehouse_data.warehouse_state|default:$settings.General.default_state}
	<label for="warehouse_state" class="">{$lang.state}:</label>
	<select id="warehouse_state" name="warehouse_data[state]" {if !$states.$country_code}class="hidden"{/if}>
		<option value="">- {$lang.select_state} -</option>
		{* Initializing default states *}
		{if $states.$country_code}
			{foreach from=$states.$country_code item=warestate}
				<option {if $warehouse_state_code == $warestate.code}selected="selected"{/if} value="{$warestate.code}">{$warestate.state}</option>
			{/foreach}
		{/if}
	</select>
	<!--<input type="text" id="warehouse_state" name="warehouse_data[state]" size="32" maxlength="64" value="{$company_data.state}" {if $states.$country_code}disabled="disabled"{/if} class="input-text {if $states.$country_code}hidden{/if} cm-skip-avail-switch" />
	<input type="hidden" id="company_address_state_default" value="{$warehouse_data.warehouse_state}" /> -->
</div>
<div class="form-field">
	<label for="warehouse_pin" class="cm-pin">{$lang.warehouse_pin}:</label>
	<input type="text" name="warehouse_data[pin]" id="warehouse_pin" size="32" value="{$warehouse_data.warehouse_pin}" class="input-text" />
</div>
<div class="form-field">
	<label for="warehouse_pcontact_name" class="cm-pcontact_name">{$lang.warehouse_pcontact_name}:</label>
	<input type="text" name="warehouse_data[pcontact_name]" id="warehouse_pcontact_name" size="32" value="{$warehouse_data.warehouse_pcontact_name}" class="input-text" />
</div>
<div class="form-field">
	<label for="warehouse_pcontact_phone" class="cm-pcontact_phone">{$lang.warehouse_pcontact_phone}:</label>
	<input type="text" name="warehouse_data[pcontact_phone]" id="warehouse_pcontact_phone" size="32" value="{$warehouse_data.warehouse_pcontact_phone}" class="input-text" />
</div>
<div class="form-field">
	<label for="warehouse_pcontact_email" class="cm-pcontact_email">{$lang.warehouse_pcontact_email}:</label>
	<input type="text" name="warehouse_data[pcontact_email]" id="warehouse_pcontact_email" size="32" value="{$warehouse_data.warehouse_pcontact_email}" class="input-text" />
</div>
<div class="form-field">
	<label for="warehouse_scontact_name" class="cm-scontact_name">{$lang.warehouse_scontact_name}:</label>
	<input type="text" name="warehouse_data[scontact_name]" id="warehouse_scontact_name" size="32" value="{$warehouse_data.warehouse_scontact_name}" class="input-text" />
</div>
<div class="form-field">
	<label for="warehouse_scontact_phone" class="cm-scontact_phone">{$lang.warehouse_scontact_phone}:</label>
	<input type="text" name="warehouse_data[scontact_phone]" id="warehouse_scontact_phone" size="32" value="{$warehouse_data.warehouse_scontact_phone}" class="input-text" />
</div>
<div class="form-field">
	<label for="warehouse_scontact_email" class="cm-scontact_email">{$lang.warehouse_scontact_email}:</label>
	<input type="text" name="warehouse_data[scontact_email]" id="warehouse_scontact_email" size="32" value="{$warehouse_data.warehouse_scontact_email}" class="input-text" />
</div>

</fieldset>
</div> {* /content warehouse *}
{** /Company warehouse section **}

{hook name="companies:tabs_content"}{/hook}

{** Form submit section **}

<div class="buttons-container cm-toggle-button buttons-bg">
	{if $mode == "add"}
		{include file="buttons/save_cancel.tpl" but_name="dispatch[companies.add]"}
	{else}
		{include file="buttons/save_cancel.tpl" but_name="dispatch[companies.update]"}
	{/if}
</div>
{** /Form submit section **}

</form> {* /product update form *}

{hook name="companies:tabs_extra"}{/hook}

{/capture}
{include file="common_templates/tabsbox.tpl" content=$smarty.capture.tabsbox group_name=$controller active_tab=$smarty.request.selected_section track=true}

{/capture}

{if $mode == "add"}
	{include file="common_templates/mainbox.tpl" title=$lang_new_vendor_supplier content=$smarty.capture.mainbox}
{else}
	{include file="common_templates/mainbox.tpl" title="`$lang_editing_vendor_supplier`:&nbsp;`$company_data.company`" content=$smarty.capture.mainbox select_languages=true}
{/if}
