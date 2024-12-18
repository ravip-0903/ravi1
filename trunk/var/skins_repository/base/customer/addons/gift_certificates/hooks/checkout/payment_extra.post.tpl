{* $Id: payment_extra.post.tpl 12493 2011-05-19 10:32:19Z subkey $ *}

{if $settings.General.checkout_style == "multi_page"}
	{assign var="additional_ids" value=",step_three"}
{/if}

<div class="cm-tools-list right">
<form {if $location == "checkout" && $settings.General.checkout_style != "multi_page"}class="cm-ajax"{/if} name="gift_certificate_payment_form{$position}" action="{""|fn_url}" method="post">
<input type="hidden" name="redirect_mode" value="{$location}" />
<input type="hidden" name="result_ids" value="checkout_steps,cart_status,checkout_cart{$additional_ids}" />

<div class="form-field">
	<span class="label">{$lang.gift_cert_code}:</span><label for="gc_field{$position}" class="hidden cm-required">{$lang.gift_cert_code}:</label>
	<input type="text" id="gc_field{$position}" class="input-text" name="gift_cert_code" size="40" value="" />
	<input type="submit" class="hidden" name="dispatch[checkout.apply_certificate]" value="" />
	{include file="buttons/button.tpl" but_role="text" but_name="dispatch[checkout.apply_certificate]" but_rev="gift_certificate_payment_form`$position`" but_text=$lang.apply}
</div>

</form>

{if $cart.use_gift_certificates}
	{foreach from=$cart.use_gift_certificates item="ugc" key="ugc_key"}
		<li>
			<span>{$lang.gift_certificate}:</span>
			<strong>&nbsp;</strong>
		</li>
		<li>
		<span><a href="{"gift_certificates.verify?verify_code=`$ugc_key`"|fn_url}">{$ugc_key}</a>&nbsp;<a {if $use_ajax}class="cm-ajax"{/if} href="{"checkout.delete_use_certificate?gift_cert_code=`$ugc_key`&amp;redirect_mode=`$mode`"|fn_url}" rev="checkout_totals,cart_items,cart_status,checkout_steps,checkout_cart{$additional_ids}"><img src="{$images_dir}/icons/delete_icon.gif" width="10" height="8" border="0" alt="{$lang.delete}" title="{$lang.delete}" /></a>&nbsp;:</span>
		<strong>{include file="common_templates/price.tpl" value=$ugc.cost}</strong>
		</li>
	{/foreach}
{/if}

</div>