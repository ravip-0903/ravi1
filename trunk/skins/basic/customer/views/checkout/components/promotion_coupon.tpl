{* $Id: promotion_coupon.tpl 12493 2011-05-19 10:32:19Z subkey $ *}

{if $settings.General.checkout_style == "multi_page"}
	{assign var="additional_ids" value=",step_three"}
{/if}

<!--<form {if $use_ajax}class="cm-ajax"{/if} name="coupon_code_form{$position}" action="{""|fn_url}" method="post">-->
<form  name="coupon_code_form{$position}" action="{""|fn_url}" method="post">
<input type="hidden" name="redirect_mode" value="{$location}" />
<input type="hidden" name="result_ids" value="checkout_totals,cart_items,checkout_steps,cart_status,checkout_cart{$additional_ids}" />
<div class="form_twocolumnwithbutton_row">
<div class="form_twocolumnwithbutton_fieldname">
{$lang.discount_coupon_code}:
<label for="coupon_field{$position}" class="hidden cm-required cm-custom (coupon_code_for_spe_char)">{$lang.discount_coupon_code}</label>
</div>
<div class="form_twocolumnwithbutton_field" style="width:245px;">
<input type="text" class="form_twocolumnwithbutton_field_textbox" id="coupon_field{$position}" name="coupon_code" size="40" value="" style="width:245px;"  />
{if !$cart.coupons|floatval}
<span class="foot_note_nl">{$lang.apply_coupon_code}</span>
{/if}
	<input type="submit" class="hidden" name="dispatch[checkout.apply_coupon]"value="" />
</div>
<div class="form_twocolumnwithbutton_functions">
{include file="buttons/button.tpl" but_role="text" but_name="dispatch[checkout.apply_coupon]" but_text=$lang.apply but_rev="coupon_code_form`$position`" but_class="form_twocolumnwithbutton_functions_button"}
</div>

<div class="clearboth"></div>

<div class="form_twocolumnwithbutton_fieldabout" style="width:68%;">
{if $cart.coupons|floatval}
	{foreach from=$cart.coupons item="coupon" key="coupon_code" name="app_c"}

		{if !$smarty.foreach.app_c.first}
        	<br />
        {/if}
        {$lang.applied_coupon} 
        {if $smarty.session.cart.custom_coupon}
        	{assign var="fl" value="0"}
            {foreach from=$smarty.session.cart.custom_coupon key="customcoupon" item="systemcoupon"}
            	{if $systemcoupon==$coupon_code}
                	"{$customcoupon}"
                    {assign var="fl" value="1"}
                {/if}
            {/foreach}
            {if $fl=="0"}
            "{$coupon_code}"
            {/if}
        {else}
        	"{$coupon_code}"
        {/if} {$lang.for_this_order}
		{assign var="_redirect_url" value=$config.current_url|escape:url}
		{if $use_ajax}{assign var="_class" value="cm-ajax"}{/if}
		{include file="buttons/button.tpl" but_href="checkout.delete_coupon?coupon_code=`$coupon_code`&redirect_url=`$_redirect_url`" but_role="text" but_text=$lang.remove but_meta=$_class but_rev="checkout_totals,cart_items,cart_status,checkout_steps,checkout_cart`$additional_ids`"}
		

	{/foreach}
{/if}
{if $smarty.session.cart.sor == "Y" && !empty($smarty.session.cart.coupons)}
	{$lang.cannot_combine_with_others}
{/if}
</div>
</div>
</form>

{literal}
<script>
 function fn_coupon_code_for_spe_char()
 {
     var position = {/literal}'{$position}';{literal}
     var coupon = $('#coupon_field' + position).val();
     lang.coupon_code_for_special_char = '{$lang.coupon_code_for_special_char}';
        if(/^[a-zA-Z0-9]*$/.test(coupon))
        {
            return true;
        }
        else
            {
                return lang.coupon_code_for_special_char;
            }
 }
</script>
{/literal}
