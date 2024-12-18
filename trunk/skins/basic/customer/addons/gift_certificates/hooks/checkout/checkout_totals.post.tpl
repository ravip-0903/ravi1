{* $Id: checkout_totals.post.tpl 12479 2011-05-18 08:54:10Z alexions $ *}

{if $cart.use_gift_certificates}
{foreach from=$cart.use_gift_certificates item="ugc" key="ugc_key"}
<div class="box_paymentcalculations_row">

<div class="box_paymentcalculations_fieldname">{$lang.gift_certificate} :</div>

<div class="box_paymentcalculations_field">{include file="common_templates/price.tpl" value=$ugc.cost}</div>

</div>






{if $settings.General.checkout_style != "multi_page"}

<a {if $use_ajax}class="cm-ajax"{/if} href="{"checkout.delete_use_certificate?gift_cert_code=`$ugc_key`&amp;redirect_mode=`$mode`"|fn_url}" rev="checkout_totals,cart_items,cart_status,checkout_steps{$additional_ids}">
<img src="{$images_dir}/icons/delete_icon.gif" width="10" height="8" border="0" alt="{$lang.delete}" title="{$lang.delete}" />
</a>

{/if}
	
	
	
{/foreach}
{/if}
