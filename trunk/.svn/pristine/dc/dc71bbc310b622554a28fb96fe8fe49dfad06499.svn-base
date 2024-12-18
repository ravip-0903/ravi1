{* $Id: payment_options.post.tpl 12479 2011-05-18 08:54:10Z alexions $ *}

{if $settings.General.checkout_style == "multi_page"}
	{assign var="additional_ids" value=",step_three"}
{/if}

{if $cart_products && $cart.points_info.total_price && $user_info.points > 0}
<!--<div class="buttons-container clear-both">
		{*include file="buttons/button.tpl" but_role="text" but_text=$lang.point_payment but_id="sw_point_payment" but_meta="cm-combination"*}
</div>-->


<form class="cm-ajax" name="point_payment_form" action="{""|fn_url}" method="post">
<input type="hidden" name="redirect_mode" value="{$location}" />
<input type="hidden" name="result_ids" value="checkout_totals,checkout_steps{$additional_ids}" />
        
<div class="form_twocolumnwithbutton_row margin_top_fifty">
<div class="form_twocolumnwithbutton_fieldname">
{$lang.points_to_use} :
<span class="clues_bck_nl">{$user_info.points|fn_format_price}</span>
<span class="foot_note_nl" style="text-align: center; display: block; word-wrap: break-word; width: 115px;">{$lang.cb_in_your_account}</span>
</div>
<div class="form_twocolumnwithbutton_field" style="width:245px!important;">
<input type="tel" class="form_twocolumnwithbutton_field_textbox" name="points_to_use" size="40"  value="" style="width:245px!important;"/>
{if !$cart.points_info.in_use.points}
<span class="foot_note_nl">{$lang.apply_cb}</span>
{/if}
</div>
<div class="form_twocolumnwithbutton_functions">
{include file="buttons/button.tpl" but_role="text" but_name="dispatch[checkout.point_payment]" but_text=$lang.apply but_class="form_twocolumnwithbutton_functions_button"}
<input type="submit" class="hidden" name="dispatch[checkout.point_payment]" value="" />
</div>

<div class="clearboth"></div>

<div class="form_twocolumnwithbutton_fieldabout">
{*{$lang.text_point_in_account} {$user_info.points|fn_format_price}*}
<div class="clearboth"></div>
{if $cart.points_info.in_use.points}
			{assign var="_redirect_url" value=$config.current_url|escape:url}
			{if $use_ajax}{assign var="_class" value="cm-ajax"}{/if}
			{$lang.points_in_use}&nbsp;({$cart.points_info.in_use.points}&nbsp;{$lang.points}):
			{include file="common_templates/price.tpl" value=$cart.points_info.in_use.cost}&nbsp;{include file="buttons/button.tpl" but_href="checkout.delete_points_in_use?redirect_url=`$_redirect_url`" but_meta=$_class but_role="text" but_text=$lang.remove but_rev="checkout_totals,subtotal_price_in_points,checkout_steps`$additional_ids`"}
		{/if}

</div>

</div>



		
</form>
<!--point_payment-->

{/if}
