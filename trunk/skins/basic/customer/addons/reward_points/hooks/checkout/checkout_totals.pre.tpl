{* $Id: checkout_totals.pre.tpl 12479 2011-05-18 08:54:10Z alexions $ *}

{if $cart.points_info.in_use }

		{assign var="_redirect_url" value=$config.current_url|escape:url}
		{if $use_ajax}{assign var="_class" value="cm-ajax"}{/if}		
<div class="box_paymentcalculations_fieldname">
{*include file="buttons/button.tpl" but_href="checkout.delete_points_in_use?redirect_url=`$_redirect_url`" but_meta=$_class but_role="delete" but_rev="checkout_totals,subtotal_price_in_points,checkout_steps`$additional_ids`"*}
        {$lang.points_in_use}&nbsp;({$cart.points_info.in_use.points}) :
        </div>

<div class="box_paymentcalculations_field">
{include file="common_templates/price.tpl" value=$cart.points_info.in_use.cost}
	{*if $settings.General.checkout_style != "multi_page"*}
    {*/if*}
</div>

{/if}