{* $Id: update.tpl 12452 2011-05-13 11:33:14Z alexions $ *}

<script type="text/javascript">
//<![CDATA[
lang.no_products_defined = '{$lang.text_no_products_defined|escape:"javascript"}';
{literal}
function fn_check_amount()
{
	var max = parseInt((parseFloat(max_amount) / parseFloat(currencies.secondary.coefficient))*100)/100;
	var min = parseInt((parseFloat(min_amount) / parseFloat(currencies.secondary.coefficient))*100)/100;

	is_check = ($('input:checked[name="gift_cert_data[amount_type]"]').val() == 'I') ? true : false;
	if(is_check && $('#gift_cert_amount')){
		var amount = parseFloat($('#gift_cert_amount').val());
		if(amount < min || isNaN(amount) || amount > max){
			$('#gift_cert_amount').removeClass('input-text');
			$('#gift_cert_amount').addClass('failed-field');
			alert(amount_alert);
		}else{
			$('#gift_cert_amount').removeClass('failed-field');
			$('#gift_cert_amount').addClass('input-text');
		}
		return ((amount <= max) && (amount >= min) && !isNaN(amount)) ? true : false;
	}
	return true;
}
function fn_giftcert_form_elements_disable(dsbl, enbl)
{
	if(!$('form[name="gift_certificates_form"]').get(0)){
		return false;
	}
	$(':input', '#'+dsbl).attr('disabled', 'disabled');
	$(':input', '#'+dsbl).addClass('disabled');
	$(':input', '#'+enbl).removeAttr('disabled');
	$(':input', '#'+enbl).removeClass('disabled');
}
{/literal}
//]]>
</script>
{assign var="min_amount" value=$addons.gift_certificates.min_amount|escape:javascript|fn_format_rate_value:"":$currencies.$secondary_currency.decimals:$currencies.$secondary_currency.decimals_separator:$currencies.$secondary_currency.thousands_separator:$currencies.$secondary_currency.coefficient}
{assign var="max_amount" value=$addons.gift_certificates.max_amount|escape:javascript|fn_format_rate_value:"":$currencies.$secondary_currency.decimals:$currencies.$secondary_currency.decimals_separator:$currencies.$secondary_currency.thousands_separator:$currencies.$secondary_currency.coefficient}

{include file="views/profiles/components/profiles_scripts.tpl"}

<script type="text/javascript">
//<![CDATA[
var text_no_products = '{$lang.text_no_products_defined}';
var max_amount = '{$addons.gift_certificates.max_amount|escape:javascript}';
var min_amount = '{$addons.gift_certificates.min_amount|escape:javascript}';
var amount_alert = '{$lang.text_gift_cert_amount_higher|escape:javascript} {$max_amount|escape:javascript} {$lang.text_gift_cert_amount_less|escape:javascript} {$min_amount|escape:javascript}';
//]]>
</script>
{script src="js/profiles_scripts.js"}

{** Gift certificates section **}

<div class="box_giftcertificate">
<div class="box_giftcertificate_banner">
<img src="images/skin/banner_giftcertificate.gif" />
</div>
<!--Redeem Gift Certificate -->
<div class="box_giftcertificate_redeemgiftcertificate">
<h3 class="box_giftcertificate_redeemgiftcertificate_heading">{$lang.RedeemYourGiftCertificate}</h3>
{include file="addons/gift_certificates/views/gift_certificates/components/gift_certificates_verify.tpl"}
</div>
<!--End Redeem Gift Certificate -->
<div class="clearboth"></div>

<div class="box_aboutgiftcertificate">
<div class="box_aboutgiftcertificate_heading">{$lang.HowDoesItWork}</div>
<div class="box_aboutgiftcertificate_content">{$lang.AboutGiftCertifcate}</div>
</div>


<!--Buy Gift Certificate -->
<div class="box_buygiftcertificate">
<div class="box_buygiftcertificate_heading">{$lang.BuyGiftCertificate}</div>
<div class="box_buygiftcertificate_subheading">
{$lang.text_gift_cert_amount_higher}
{include file="common_templates/price.tpl" value=$addons.gift_certificates.max_amount}
 {$lang.text_gift_cert_amount_less}
{include file="common_templates/price.tpl" value=$addons.gift_certificates.min_amount}
</div>
<!--Form -->
<div class="form_buygiftcertificate">
<form {if $settings.DHTML.ajax_add_to_cart == "Y" && !$no_ajax && $mode != "update"}class="cm-ajax" {/if}action="{""|fn_url}" method="post" target="_self" name="gift_certificates_form">
{if $mode == "update"}
<input type="hidden" name="gift_cert_id" value="{$gift_cert_id}" />
<input type="hidden" name="type" value="{$type}" />
{/if}
<div class="form_buygiftcertificate_row">
<div class="form_buygiftcertificate_fieldname"><label for="gift_cert_recipient" class="cm-required">{$lang.gift_cert_to}</label></div>
<div class="form_buygiftcertificate_field">
<input type="text" id="gift_cert_recipient" name="gift_cert_data[recipient]" class="form_buygiftcertificate_field_textbox" value="{$gift_cert_data.recipient}" />
</div>
</div>
<div class="form_buygiftcertificate_row">
<div class="form_buygiftcertificate_fieldname"><label for="gift_cert_sender" class="cm-required">{$lang.gift_cert_from}</label></div>
<div class="form_buygiftcertificate_field">
<input type="text" id="gift_cert_sender" name="gift_cert_data[sender]" class="form_buygiftcertificate_field_textbox" size="50" maxlength="255" value="{$gift_cert_data.sender}" />
</div>
</div>
<div class="form_buygiftcertificate_row">
<div class="form_buygiftcertificate_fieldname"><label for="radio_at" class="cm-required">{$lang.amount}</label></div>
<div class="form_buygiftcertificate_field" style="margin-left:10px;">
<input type="radio" name="gift_cert_data[amount_type]" value="I" id="radio_at" onclick="fn_giftcert_form_elements_disable('select_block', 'input_block');" {if $mode == "add" || $gift_cert_data.amount_type == "I"}checked="checked"{/if} class="radio{if !$amount_variants} hidden{/if}" />
<span id="input_block">
{if $currencies.$secondary_currency.after != "Y"}
<span class="valign">
{$currencies.$secondary_currency.symbol|unescape}
</span>
{/if}

<input type="text" id="gift_cert_amount" name="gift_cert_data[amount]" class="valign input-text-short inp-el" size="5" value="{if $gift_cert_data && $gift_cert_data.amount_type == "I"}{$gift_cert_data.amount|fn_format_rate_value:"":$currencies.$secondary_currency.decimals:".":"":$currencies.$secondary_currency.coefficient}{else}{$addons.gift_certificates.min_amount|fn_format_rate_value:"":$currencies.$secondary_currency.decimals:".":"":$currencies.$secondary_currency.coefficient}{/if}" />
{if $currencies.$secondary_currency.after == "Y"}
<span class="valign">
{$currencies.$secondary_currency.symbol|unescape}
</span>
{/if}
</span>

{if $amount_variants}
<input type="radio" name="gift_cert_data[amount_type]" value="S" id="radio_at2" onclick="fn_giftcert_form_elements_disable('input_block', 'select_block');" {if $gift_cert_data.amount_type == "S"}checked="checked"{/if} class="radio margin_left_ten" />
<span id="select_block">
<select	id="gift_cert_amount2" name="gift_cert_data[amount]" class="valign sel-el" >
{foreach from=$amount_variants item="av"}
{if $av == $gift_cert_data.amount}{assign var="av_isset" value="Y"}
{/if}
{if !$av_isset && $mode == "update" && $av > $gift_cert_data.amount}
{assign var="av_isset" value="Y"}
<option value="{$gift_cert_data.amount|fn_format_price}" {if $gift_cert_data.amount_type == "S"}selected="selected"{/if}>
{include file="common_templates/price.tpl" value=$gift_cert_data.amount}
</option>
{/if}
<option value="{$av|fn_format_price}" {if ($av == $gift_cert_data.amount && $gift_cert_data.amount_type == "S" && $gift_cert_data) || (!$gift_cert_data && $addons.gift_certificates.min_amount == $av)}selected="selected"{/if}>
{include file="common_templates/price.tpl" value=$av}
</option>
{/foreach}
</select>
</span>
{/if}
</div>
</div>

<div class="form_buygiftcertificate_row">
<div class="form_buygiftcertificate_fieldname"><label for="gift_cert_message">{$lang.message}</label></div>
<div class="form_buygiftcertificate_field">
<textarea id="gift_cert_message" name="gift_cert_data[message]" class="form_buygiftcertificate_field_textarea" {if $is_text == "Y"}readonly="readonly"{/if}>{$gift_cert_data.message}</textarea>
</div>
</div>



<div class="box_sendvia">
<div class="box_sendvia_heading">
<input type="radio" name="gift_cert_data[send_via]" value="E" onclick="fn_giftcert_form_elements_disable('post_block', 'email_block');" {if $mode == "add" || $gift_cert_data.send_via == "E"}checked="checked"{/if} class="radio" id="send_via_email" />
<label for="send_via_email" class="valign">{$lang.send_via_email}</label>
</div>

<div class="form_buygiftcertificate_row" id="email_block">
<div class="form_buygiftcertificate_fieldname">
<label for="gift_cert_email" class="cm-required cm-email">{$lang.email}</label>
</div>
<div class="form_buygiftcertificate_field">
<input type="email" id="gift_cert_email" name="gift_cert_data[email]" class="form_buygiftcertificate_field_textbox" value="{$gift_cert_data.email}" />
</div>
<div class="pj2_gift_certificate_text">{$lang.email_text_gc}</div>
</div>

{if $templates|sizeof > 1}
			<label for="gift_cert_template">{$lang.template}:</label>
			<select id="gift_cert_template" name="gift_cert_data[template]">
			{foreach from=$templates item="name" key="file"}
				<option value="{$file}" {if $file == $gift_cert_data.template}selected{/if}>{$name}</option>
			{/foreach}
			</select>
		{else}
			{foreach from=$templates item="name" key="file"}
				<input id="gift_cert_template" type="hidden" name="gift_cert_data[template]" value="{$file}" />
			{/foreach}
{/if}

</div>

<div class="float_left" style="font:20px trebuchet ms; color:#35b2e1; border:1px solid #35b2e1; border-radius:30px; height:30px; width:25px; padding-left:5px; line-height:30px; margin-left:180px; margin-top:10px;">{$lang.Or_And}</div>

<div class="box_sendvia">
<div class="box_sendvia_heading">
<input type="radio" name="gift_cert_data[send_via]" value="P" onclick="fn_giftcert_form_elements_disable('email_block', 'post_block');" {if $gift_cert_data.send_via == "P"}checked="checked"{/if} class="valign radio" id="send_via_post" />
<label for="send_via_post" class="radio">{$lang.send_via_postal_mail}</label>
</div>

<div id="post_block">

<div class="form_buygiftcertificate_row">
<div class="form_buygiftcertificate_fieldname">
<label for="gift_cert_address" class="cm-required">{$lang.address}:</label>
</div>
<div class="form_buygiftcertificate_field">
<input type="text" id="gift_cert_address" name="gift_cert_data[address]" class="form_buygiftcertificate_field_textbox" value="{$gift_cert_data.address}" />
</div>
</div>
<div class="form_buygiftcertificate_row">
<div class="form_buygiftcertificate_fieldname">
<label for="gift_cert_address_2">{$lang.address_2}</label>
</div>
<div class="form_buygiftcertificate_field">
<input type="text" id="gift_cert_address_2" name="gift_cert_data[address_2]" class="form_buygiftcertificate_field_textbox" size="50" value="{$gift_cert_data.address_2}" />
</div>
</div>
<div class="form_buygiftcertificate_row">
<div class="form_buygiftcertificate_fieldname">
<label for="gift_cert_city" class="cm-required">{$lang.city}</label>
</div>
<div class="form_buygiftcertificate_field">
<input type="text" id="gift_cert_city" name="gift_cert_data[city]" class="form_buygiftcertificate_field_textbox" size="50" value="{$gift_cert_data.city}" />
</div>
</div>
<div class="form_buygiftcertificate_row">
<div class="form_buygiftcertificate_fieldname">
<label for="gift_cert_country" class="cm-required cm-country cm-location-billing">{$lang.country}</label>
</div>
<div class="form_buygiftcertificate_field">
{assign var="_country" value=$gift_cert_data.country|default:$settings.General.default_country}
<select id="gift_cert_country" name="gift_cert_data[country]" class="form_buygiftcertificate_field_listbox" >
<option value="">- {$lang.select_country} -</option>
{foreach from=$countries item=country}
<option {if $_country == $country.code}selected="selected"{/if} value="{$country.code}">{$country.country}</option>
{/foreach}
</select>
</div>
</div>
<div class="form_buygiftcertificate_row">
<div class="form_buygiftcertificate_fieldname">
<label for="gift_cert_state" class="cm-required cm-state cm-location-billing">{$lang.state}:</label>
</div>
<div class="form_buygiftcertificate_field">
<input type="text" id="gift_cert_state_d" name="gift_cert_data[state]" class="input-text hidden" size="50" maxlength="64" value="{$value}" disabled="disabled"  />
<select id="gift_cert_state" name="gift_cert_data[state]" class="form_buygiftcertificate_field_listbox">
			<option value="">- {$lang.select_state} -</option>
			{if $states}
				{foreach from=$states.$_country item=state}
					<option value="{$state.code}">{$state.state}</option>
				{/foreach}
			{/if}
		</select>
<input type="hidden" id="gift_cert_state_default" value="{$gift_cert_data.state|default:$settings.General.default_state}" />
</div>
</div>
<div class="form_buygiftcertificate_row">
<div class="form_buygiftcertificate_fieldname">
<label for="gift_cert_zipcode" class="cm-required">{$lang.zip_postal_code}</label>
</div>
<div class="form_buygiftcertificate_field">
<input type="tel" id="gift_cert_zipcode" name="gift_cert_data[zipcode]" class="form_buygiftcertificate_field_textbox" size="50" value="{$gift_cert_data.zipcode}" />
</div>
</div>
<div class="form_buygiftcertificate_row">
<div class="form_buygiftcertificate_fieldname">
<label for="gift_cert_phone">{$lang.phone}</label>
</div>
<div class="form_buygiftcertificate_field">
<input type="tel" id="gift_cert_phone" name="gift_cert_data[phone]" class="form_buygiftcertificate_field_textbox" size="50" value="{$gift_cert_data.phone}" />
</div>
</div>



</div>
</div>

{if $addons.gift_certificates.free_products_allow == "Y"}
<div class="box_sendvia" id="post_block">
<div class="box_sendvia_heading">{$lang.free_products}</div>
{include file="pickers/products_picker.tpl" data_id="free_products" item_ids=$gift_cert_data.products input_name="gift_cert_data[products]" type="table" no_item_text=$lang.text_no_products_defined holder_name="gift_certificates"}
</div>
{/if}

<div class="box_functions">

{if $mode == "add"}
<input type="hidden" name="result_ids" value="cart_status" />
	
		<div class="float_right margin_left_five" style="display:inline-block; width:78px;">
                {include file="buttons/add_to_cart.tpl" but_text=$lang.buy_now but_name="dispatch[gift_certificates.add]" but_onclick="return fn_check_amount();" but_role="action"}
        </div>
	
{else}
	<div class="float_right margin_left_five"  style="display:inline-block; width:190px;">
    {include file="buttons/save.tpl" but_name="dispatch[gift_certificates.update]" but_onclick="return fn_check_amount();"  but_role=""}
    </div>
{/if}

<div class="float_right margin_left_five"  style="display:inline-block; width:190px;">
{if $templates}
	{include file="buttons/button.tpl" but_name="dispatch[gift_certificates.preview]" but_text=$lang.preview  but_meta="cm-new-window" but_role=""}
{/if}
<div class="float_right" style="margin-top:8px;">
{hook name="gift_certificates:buttons"}
{/hook}
</div>

</div>

</div>

</form>
</div>
<!--End Form -->


</div>
<!--Buy Gift Certificate -->
</div>

<script type="text/javascript">
//<![CDATA[
	fn_giftcert_form_elements_disable({if $mode == "add" || $gift_cert_data.amount_type == "I"}'select_block', 'input_block'{else}'input_block', 'select_block'{/if});
	fn_giftcert_form_elements_disable({if $mode == "add" || $gift_cert_data.send_via == "E"}'post_block', 'email_block'{else}'email_block', 'post_block'{/if});
//]]>
</script>
{** / Gift certificates section **}

{capture name="mainbox_title"}{if $mode == "add"}{$lang.purchase_gift_certificate}{else}{$lang.gift_certificate}{/if}{/capture}
