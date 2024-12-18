{capture name="mainbox"}

{capture name="tabsbox"}

{** /Item menu section **}

<form action="{""|fn_url}" method="post" name="product_update_form" class="cm-form-highlight cm-disable-empty-files{if ""|fn_check_form_permissions} cm-hide-inputs{/if}" enctype="multipart/form-data"> {* product update form *}
<input type="hidden" name="fake" value="1" />
<input type="hidden" name="selected_section" id="selected_section" value="{$smarty.request.selected_section}" />
<input type="hidden" name="product_id" value="{$product_data.product_id}" />

{** Product description section **}

<div id="content_detailed"> {* content detailed *}

{** General info section **}
<fieldset>

{include file="common_templates/subheader.tpl" title=$lang.information}

<div class="form-field">
	<label for="product_description_product" class="cm-required">{$lang.name}:</label>
	<span class="input-helper"><input type="text" name="product_data[product]" id="product_description_product" size="55" value="{$product_data.product}" class="input-text-large main-input" /></span>
</div>

<div class="form-field">
	{math equation="rand()" assign="rnd"}
	{assign var="request_category_id" value=","|explode:$smarty.request.category_id|array_flip}
	<label for="ccategories_{$rnd}_ids" class="cm-required">{$lang.categories}:</label>
	<div class="categories">{include file="pickers/categories_picker.tpl" rnd=$rnd data_id="categories" input_name="product_data[add_categories]" radio_input_name="product_data[main_category]" item_ids=$product_data.category_ids|default:$request_category_id hide_link=true hide_delete_button=true display_input_id="category_ids" disable_no_item_text=true view_mode="list"}</div>
</div>

<div class="form-field">
	<label for="price_price" class="cm-required">{$lang.price} ({$currencies.$primary_currency.symbol}):</label>
	<input type="text" name="product_data[price]" id="price_price" size="10" value="{$product_data.price|default:"0.00"}" class="input-text-medium" />
</div>

<div class="form-field">
	<label for="product_full_descr">{$lang.full_description}:</label>
	<textarea id="product_full_descr" name="product_data[full_description]" cols="55" rows="8" class="cm-wysiwyg input-textarea-long">{$product_data.full_description}</textarea>
	
</div>
{** /General info section **}

{include file="common_templates/select_status.tpl" input_name="product_data[status]" id="product_data" obj=$product_data hidden=true}

{hook name="companies:product_details_fields"}
	{include file="views/companies/components/company_field.tpl" title=$lang.vendor name="product_data[company_id]" id="product_data_company_id" selected=$product_data.company_id}
{/hook}

<div class="form-field">
	<label>{$lang.images}:</label>
	{include file="common_templates/attach_images.tpl" image_name="product_main" image_object_type="product" image_pair=$product_data.main_pair icon_text=$lang.text_product_thumbnail detailed_text=$lang.text_product_detailed_image no_thumbnail=true}
</div>
</fieldset>

<fieldset>

{include file="common_templates/subheader.tpl" title=$lang.options_settings}

<div class="form-field">
	<label for="product_options_type">{$lang.options_type}:</label>
	<select name="product_data[options_type]" id="options_type">
		<option value="P" {if $product_data.options_type == "P"}selected="selected"{/if}>{$lang.simultaneous}</option>
		<option value="S" {if $product_data.options_type == "S"}selected="selected"{/if}>{$lang.sequential}</option>
	</select>
</div>
<div class="form-field">
	<label for="product_exceptions_type">{$lang.exceptions_type}:</label>
	<select name="product_data[exceptions_type]" id="exceptions_type">
		<option value="F" {if $product_data.exceptions_type == "F"}selected="selected"{/if}>{$lang.forbidden}</option>
		<option value="A" {if $product_data.exceptions_type == "A"}selected="selected"{/if}>{$lang.allowed}</option>
	</select>
</div>
</fieldset>

<fieldset>

{include file="common_templates/subheader.tpl" title=$lang.pricing_inventory}

<div class="form-field">
	<label for="product_product_code">{$lang.product_code}:</label>
	<input type="text" name="product_data[product_code]" id="product_product_code" size="20" maxlength="32"  value="{$product_data.product_code}" class="input-text-medium" />
</div>

<div class="form-field">
	<label for="product_description_product">{$lang.merchant_reference_number} :</label>
	<span class="input-helper"><input type="text" name="product_data[merchant_reference_number]" id="merchant_reference_number" size="55" value="{$product_data.merchant_reference_number}" class="input-text-medium" /></span> &nbsp; 
</div>

<div class="form-field mfg_ref">
	<span><label for="product_description_product" >{$lang.manufacturer_reference_number} :</label></span>
	<span class="input-helper">
    <input type="text" name="product_data[manufacturer_reference_number]" id="manufacturer_reference_number" size="30" value="{$product_data.manufacturer_reference_number}" class="input-text-medium" />
    </span>
    
    <span class="mfg_ref_type"><label class="mfg_ref_la_mng" for="product_description_product">{$lang.manufacturer_reference_type} :</label></span>
	<span class="input-helper">
   		 <!--<input type="text" name="product_data[manufacturer_reference_type]" id="manufacturer_reference_type" size="30" value="{$product_data.manufacturer_reference_type}" class="input-text-medium" />-->         
         <select name="product_data[manufacturer_reference_type]" id="manufacturer_reference_type">
		<option value="UPC" {if $product_data.manufacturer_reference_type == "UPC"}selected="selected"{/if}>UPC</option>
		<option value="ISBN" {if $product_data.manufacturer_reference_type == "ISBN"}selected="selected"{/if}>ISBN</option>
	</select>
    </span>
</div>

<!--<div class="form-field">
	<label for="product_description_product">MFG ref Type:</label>
	<span class="input-helper"><input type="text" name="product_data[manufacturer_reference_type]" id="manufacturer_reference_type" size="55" value="{$product_data.manufacturer_reference_type}" class="input-text-medium" /></span>
</div>-->

<div class="form-field">
	<label for="product_list_price">{$lang.list_price} ({$currencies.$primary_currency.symbol}) :</label>
	<input type="text" name="product_data[list_price]" id="product_data_list_price" size="10" value="{$product_data.list_price|default:"0.00"}" class="input-text-medium" />
</div>

<div class="form-field">
	<label for="product_amount">{$lang.in_stock}:</label>
	{if $product_data.tracking == "O"}
		{include file="buttons/button.tpl" but_text=$lang.edit but_href="product_options.inventory?product_id=`$product_data.product_id`" but_role="edit"}
	{else}
		<input type="text" name="product_data[amount]" id="product_amount" size="10" value="{$product_data.amount|default:"1"}" class="input-text-short" />
	{/if}
</div>

<div class="form-field">
	<label for="zero_price_action">{$lang.zero_price_action}:</label>
	<select name="product_data[zero_price_action]" id="zero_price_action">
		<option value="R" {if $product_data.zero_price_action == "R"}selected="selected"{/if}>{$lang.zpa_refuse}</option>
		<option value="P" {if $product_data.zero_price_action == "P"}selected="selected"{/if}>{$lang.zpa_permit}</option>
		<option value="A" {if $product_data.zero_price_action == "A"}selected="selected"{/if}>{$lang.zpa_ask_price}</option>
	</select>
</div>

<div class="form-field">
	<label for="product_tracking">{$lang.inventory}:</label>
	<select name="product_data[tracking]" id="product_tracking">
		{if $product_options}
			<option value="O" {if $product_data.tracking == "O"}selected="selected"{/if}>{$lang.track_with_options}</option>
		{/if}
		<option value="B" {if $product_data.tracking == "B"}selected="selected"{/if}>{$lang.track_without_options}</option>
		<option value="D" {if $product_data.tracking == "D"}selected="selected"{/if}>{$lang.dont_track}</option>
	</select>
</div>

<div class="form-field">
	<label for="min_qty">{$lang.min_order_qty}:</label>
	<input type="text" name="product_data[min_qty]" size="10" id="min_qty" value="{$product_data.min_qty|default:"0"}" class="input-text-short" />
</div>

<div class="form-field">
	<label for="max_qty">{$lang.max_order_qty}:</label>
	<input type="text" name="product_data[max_qty]" id="max_qty" size="10" value="{$product_data.max_qty|default:"0"}" class="input-text-short" />
</div>

<div class="form-field">
	<label for="qty_step">{$lang.quantity_step}:</label>
	<input type="text" name="product_data[qty_step]" id="qty_step" size="10" value="{$product_data.qty_step|default:"0"}" class="input-text-short" />
</div>

<div class="form-field">
	<label for="list_qty_count">{$lang.list_quantity_count}:</label>
	<input type="text" name="product_data[list_qty_count]" id="list_qty_count" size="10" value="{$product_data.list_qty_count|default:"0"}" class="input-text-short" />
</div>

<div class="form-field">
	<label for="products_tax_id">{$lang.taxes}:</label>
	<div class="select-field">
		<input type="hidden" name="product_data[tax_ids]" value="" />
		{foreach from=$taxes item="tax"}
			<input type="checkbox" name="product_data[tax_ids][{$tax.tax_id}]" id="product_data_{$tax.tax_id}" {if $tax.tax_id|in_array:$product_data.taxes || $product_data.taxes[$tax.tax_id]}checked="checked"{/if} class="checkbox" value="{$tax.tax_id}" />
			<label for="product_data_{$tax.tax_id}">{$tax.tax}</label>
		{foreachelse}
			&ndash;
		{/foreach}
	</div>
</div>
</fieldset>

<fieldset>

{include file="common_templates/subheader.tpl" title=$lang.seo_meta_data}

<div class="form-field">
	<label for="product_page_title">{$lang.page_title}:</label>
	<input type="text" name="product_data[page_title]" id="product_page_title" size="55" value="{$product_data.page_title}" class="input-text-large" />
</div>

<div class="form-field">
	<label for="product_meta_descr">{$lang.meta_description}:</label>
	<textarea name="product_data[meta_description]" id="product_meta_descr" cols="55" rows="2" class="input-textarea-long">{$product_data.meta_description}</textarea>
</div>

<div class="form-field">
	<label for="product_meta_keywords">{$lang.meta_keywords}:</label>
	<textarea name="product_data[meta_keywords]" id="product_meta_keywords" cols="55" rows="2" class="input-textarea-long">{$product_data.meta_keywords}</textarea>
</div>

<div class="form-field">
	<label for="product_search_words">{$lang.search_words}:</label>
	<textarea name="product_data[search_words]" id="product_search_words" cols="55" rows="2" class="input-textarea-long">{$product_data.search_words}</textarea>
</div>
</fieldset>

<fieldset>

{include file="common_templates/subheader.tpl" title=$lang.availability}
<div class="form-field">
	<label>{$lang.usergroups}:</label>
		<div class="select-field">
			{include file="common_templates/select_usergroups.tpl" id="ug_id" name="product_data[usergroup_ids]" usergroups="C"|fn_get_usergroups:$smarty.const.DESCR_SL usergroup_ids=$product_data.usergroup_ids input_extra="" list_mode=false}
		</div>
</div>
<div class="form-field">
	<label>{$lang.creation_date}:</label>
	{include file="common_templates/calendar.tpl" date_id="date_holder" date_name="product_data[timestamp]" date_val=$product_data.timestamp|default:$smarty.const.TIME start_year=$settings.Company.company_start_year}
</div>

<div class="form-field">
	<label for="date_avail_holder">{$lang.available_since}:</label>
	{include file="common_templates/calendar.tpl" date_id="date_avail_holder" date_name="product_data[avail_since]" date_val=$product_data.avail_since|default:"" start_year=$settings.Company.company_start_year}
</div>

<div class="form-field">
	<label for="out_of_stock_actions">{$lang.out_of_stock_actions}:</label>
	<select name="product_data[out_of_stock_actions]" id="product_out_of_stock_actions">
		<option value="N" {if $product_data.out_of_stock_actions == "N"}selected="selected"{/if}>{$lang.none}</option>
		<option value="B" {if $product_data.out_of_stock_actions == "B"}selected="selected"{/if}>{$lang.buy_in_advance}</option>
		<option value="S" {if $product_data.out_of_stock_actions == "S"}selected="selected"{/if}>{$lang.sign_up_for_notification}</option>
	</select>
</div>
</fieldset>

<fieldset>

{include file="common_templates/subheader.tpl" title=$lang.extra}

<div class="form-field">
	<label for="details_layout">{$lang.product_details_layout}:</label>
	<select id="details_layout" name="product_data[details_layout]">
		{foreach from=$product_data.product_id|fn_get_product_details_views key="layout" item="item"}
			<option {if $product_data.details_layout == $layout}selected="selected"{/if} value="{$layout}">{$item}</option>
		{/foreach}
	</select>
</div>

<div class="form-field">
	<label for="product_feature_comparison">{$lang.feature_comparison}:</label>
	<input type="hidden" name="product_data[feature_comparison]" value="N" />
	<input type="checkbox" name="product_data[feature_comparison]" id="product_feature_comparison" value="Y" {if $product_data.feature_comparison == "Y"}checked="checked"{/if} class="checkbox" />
</div>

<div class="form-field">
	<label for="product_is_edp">{$lang.downloadable}:</label>
	<input type="hidden" name="product_data[is_edp]" value="N" />
	<input type="checkbox" name="product_data[is_edp]" id="product_is_edp" value="Y" {if $product_data.is_edp == "Y"}checked="checked"{/if} onclick="$('#edp_shipping').toggleBy(); $('#edp_unlimited').toggleBy();" class="checkbox" />
</div>

<div class="form-field {if $product_data.is_edp != "Y"}hidden{/if}" id="edp_shipping">
	<label for="product_edp_shipping">{$lang.edp_enable_shipping}:</label>
	<input type="hidden" name="product_data[edp_shipping]" value="N" />
	<input type="checkbox" name="product_data[edp_shipping]" id="product_edp_shipping" value="Y" {if $product_data.edp_shipping == "Y"}checked="checked"{/if} class="checkbox" />
</div>

<div class="form-field {if $product_data.is_edp != "Y"}hidden{/if}" id="edp_unlimited">
	<label for="product_edp_unlimited">{$lang.time_unlimited_download}:</label>
	<input type="hidden" name="product_data[unlimited_download]" value="N" />
	<input type="checkbox" name="product_data[unlimited_download]" id="product_edp_unlimited" value="Y" {if $product_data.unlimited_download == "Y"}checked="checked"{/if} class="checkbox" />
</div>

{include file="views/localizations/components/select.tpl" data_from=$product_data.localization data_name="product_data[localization]"}

<div class="form-field">
	<label for="product_short_descr">{$lang.short_description}:</label>
	<textarea id="product_short_descr" name="product_data[short_description]" cols="55" rows="2" class="cm-wysiwyg input-textarea-long">{$product_data.short_description}</textarea>
	
</div>

<div class="form-field">
	<label for="product_popularity">{$lang.popularity}:</label>
	<input type="text" name="product_data[popularity]" id="product_popularity" size="55" value="{$product_data.popularity|default:0}" class="input-text-medium" />
</div>

</fieldset>
</div> {* /content detailed *}

{** /Product description section **}

{** Product images section **}
<div id="content_images" class="hidden"> {* content images *}
<fieldset>
	{include file="common_templates/subheader.tpl" title=$lang.additional_images}
	{foreach from=$product_data.image_pairs item=pair name="detailed_images"}
		{include file="common_templates/attach_images.tpl" image_name="product_additional" image_object_type="product" image_key=$pair.pair_id image_type="A" image_pair=$pair icon_title=$lang.additional_thumbnail detailed_title=$lang.additional_popup_larger_image icon_text=$lang.text_additional_thumbnail detailed_text=$lang.text_additional_detailed_image delete_pair=true no_thumbnail=true}
		<hr />
	{/foreach}
</fieldset>

<div id="box_new_image" class="margin-top">
	<div class="clear cm-row-item">
		<div class="float-left">{include file="common_templates/attach_images.tpl" image_name="product_add_additional" image_object_type="product" image_type="A" icon_title=$lang.additional_thumbnail detailed_title=$lang.additional_popup_larger_image icon_text=$lang.text_additional_thumbnail detailed_text=$lang.text_additional_detailed_image no_thumbnail=true}</div>
		<div class="buttons-container">{include file="buttons/multiple_buttons.tpl" item_id="new_image"}</div>
	</div>
	<hr />
</div>

</div> {* /content images *}
{** /Product images section **}

{** Shipping settings section **}
<div id="content_shippings" class="hidden"> {* content shippings *}
	{include file="views/products/components/products_shipping_settings.tpl"}
</div> {* /content shippings *}
{** /Shipping settings section **}

{** Quantity discounts section **}
{hook name="products:update_qty_discounts"}
	{include file="views/products/components/products_update_qty_discounts.tpl"}
{/hook}
{** /Quantity discounts section **}
{** Product features section **}
{include file="views/products/components/products_update_features.tpl"}
{** /Product features section **}


<div id="content_addons">
{hook name="products:detailed_content"}
{/hook}
</div>


{hook name="products:tabs_content"}
{/hook}

{** Form submit section **}

<div class="buttons-container cm-toggle-button buttons-bg">
	{if $mode == "add"}
		{include file="buttons/save_cancel.tpl" but_name="dispatch[products.add]"}
	{else}
		{include file="buttons/save_cancel.tpl" but_name="dispatch[products.update]"}
	{/if}
</div>
{** /Form submit section **}

</form> {* /product update form *}
{if !"COMPANY_ID"|defined}
{if $mode != "add"}
<div id="content_blocks" class="cm-hide-save-button">
	{include file="views/products/components/product_tabs.tpl" location="products" object_id=$product_data.product_id}
</div>
{/if}
{/if}
{hook name="products:tabs_extra"}{/hook}

{if $mode == "update"}
{** Product options section **}
<div class="cm-hide-save-button hidden" id="content_options">
	{include file="views/products/components/products_update_options.tpl"}
</div>
{** /Product options section **}

{** Products files section **}
<div id="content_files" class="cm-hide-save-button hidden">
	{hook name="products:content_files"}
		{include file="views/products/components/products_update_files.tpl"}
	{/hook}
</div>
{** /Products files section **}
{/if}

{** Subscribers section **}
<div id="content_subscribers" class="cm-hide-save-button hidden">
	{include file="views/products/components/product_subscribers.tpl" product_id=$product_data.product_id}
</div>
{** /Subscribers section **}

{/capture}
{include file="common_templates/tabsbox.tpl" content=$smarty.capture.tabsbox group_name=$controller active_tab=$smarty.request.selected_section track=true}

{/capture}
{if $mode == "add"}
	{include file="common_templates/mainbox.tpl" title=$lang.new_product content=$smarty.capture.mainbox}
{else}
	{include file="common_templates/view_tools.tpl" url="products.update?product_id="}
	
	{capture name="preview"}
		{assign var="view_uri" value="products.view?product_id=`$product_data.product_id`"}
		{assign var="view_uri_escaped" value="`$view_uri`&amp;action=preview"|fn_url:'C':'http':'&':$smarty.const.DESCR_SL|escape:"url"}
		
		<a target="_blank" class="tool-link" title="{$view_uri|fn_url:'C':'http':'&':$smarty.const.DESCR_SL}" href="{$view_uri|fn_url:'C':'http':'&':$smarty.const.DESCR_SL}">{$lang.preview}</a>
		<a target="_blank" class="tool-link" title="{$view_uri|fn_url:'C':'http':'&':$smarty.const.DESCR_SL}" href="{"profiles.act_as_user?user_id=`$auth.user_id`&amp;area=C&amp;redirect_url=`$view_uri_escaped`"|fn_url}">{$lang.preview_as_admin}</a>
	{/capture}
	{include file="common_templates/mainbox.tpl" title="`$lang.editing_product`:&nbsp;`$product_data.product`"|unescape|strip_tags content=$smarty.capture.mainbox select_languages=true tools=$smarty.capture.view_tools}
{/if}