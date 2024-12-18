{* $Id: totals.post.tpl 9353 2010-05-04 06:10:09Z klerik $ *}

{if $order_info.use_gift_certificates}
{foreach from=$order_info.use_gift_certificates item="certificate" key="code" name="certs"}
<div class="box_paymentcalculations_row">
	<div class="box_paymentcalculations_fieldname">
	{$lang.gift_certificate}(<a href="{"gift_certificates.verify?verify_code=`$code`"|fn_url}">{$code}</a>):
    </div>
    <div class="box_paymentcalculations_field">
    {include file="common_templates/price.tpl" value=$certificate.cost}
	</div>
</div>
{/foreach}
{/if}
