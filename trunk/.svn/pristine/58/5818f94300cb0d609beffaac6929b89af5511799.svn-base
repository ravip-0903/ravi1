{* $Id: order_totals_custom.tpl 12565 2011-05-31 08:31:28Z alexions $ *}


<!--Payment Total Calculation -->
<div class="box_paymentcalculations" style="width:100%; border:none;">

<div class="box_paymentcalculations_row">
<div class="box_paymentcalculations_fieldname bold">
{$lang.subtotal} :
</div>

<div class="box_paymentcalculations_field bold">
{include file="common_templates/price.tpl" value=$order_info.display_subtotal}
</div>
</div>
{if $order_info.parent_order_id =='0'}
	{if ($order_info.subtotal_discount|floatval)}
	<div class="box_paymentcalculations_row">
	<div class="box_paymentcalculations_fieldname">
	{$lang.order_discount} :
	</div>
	<div class="box_paymentcalculations_field">
	{include file="common_templates/price.tpl" value=$order_info.subtotal_discount}
	</div>
	</div>
	{/if}
{/if}
{if $order_info.parent_order_id =='0'}
	{if ($order_info.subtotal_discount|floatval)}
	{assign var="after_discount" value=$order_info.subtotal-$order_info.subtotal_discount}
	<div class="box_paymentcalculations_row">
	<div class="box_paymentcalculations_fieldname">
	{$lang.price_after_discount} :
	</div>

	<div class="box_paymentcalculations_field">
	{include file="common_templates/price.tpl" value=$after_discount}	
	</div>

	</div>
	{/if}
{/if}


{if $order_info.parent_order_id =='0'}
<div class="box_paymentcalculations_row">
	<div class="box_paymentcalculations_fieldname"> {$lang.shipping_cost} : </div>	
	<div class="box_paymentcalculations_field">
		{include file="common_templates/price.tpl" value=$order_info.display_shipping_cost}
	</div>
</div>

{hook name="orders:totals"}
{/hook}
{/if}
{if $order_info.parent_order_id =='0'}
<div class="box_paymentcalculations_row">

{hook name="checkout:checkout_totals"}
{/hook}
</div>
{/if}



{if $order_info.parent_order_id =='0'}
{if $order_info.emi_fee != '0' && $order_info.emi_fee != ''  }
<div class="box_paymentcalculations_row">
	<div class="box_paymentcalculations_fieldname">
		{$lang.emi_process_fee} :
	</div>
	
	<div class="box_paymentcalculations_field">
		{include file="common_templates/price.tpl" value=$order_info.emi_fee}
	</div>
</div>
{/if}
{/if}
{if $order_info.parent_order_id =='0'}
{if $order_info.cod_fee != '0' && $order_info.cod_fee != ''  }
<div class="box_paymentcalculations_row">
	<div class="box_paymentcalculations_fieldname">
		{$lang.cod_process_fee} :
	</div>
	
	<div class="box_paymentcalculations_field">
		{include file="common_templates/price.tpl" value=$order_info.cod_fee}
	</div>
</div>
{/if}
{/if}

{if $order_info.parent_order_id =='0'}
{if $order_info.gift_it=='Y'}
<div class="box_paymentcalculations_row">
	<div class="box_paymentcalculations_fieldname">
		{$lang.gift_fee} :
	</div>
	
	<div class="box_paymentcalculations_field">
		{include file="common_templates/price.tpl" value=$order_info.gifting_charge}
	</div>
</div>
{/if}

{/if}
{if $order_info.parent_order_id =='0'}
<div class="box_paymentcalculations_row">
<div class="box_paymentcalculations_fieldname">
<span class="box_paymentcalculations_fieldname_total">
{$lang.you_will_pay} :
</span>
</div>


<div class="box_paymentcalculations_field">
<span  class="box_paymentcalculations_field_totalamount">
{include file="common_templates/price.tpl" value=$order_info.total}
</span>
</div>
</div>
{/if}
{*parent id condition by sapna *}
<div class="clearboth"></div>
{if $order_info.parent_order_id =='0'}
{if $order_info.payment_id != "6" && $order_info.payment_id!=0}
<div class="box_paymentcalculations_fieldabout">

    {if $order_info.points_info.reward}
            <span>{$lang.you_earn}:</span>
            <strong>{$order_info.points_info.reward}</strong>
    {/if}

</div>
{/if}
{/if}

{if $order_info.parent_order_id =='0'}
{if $cart.discount >0 || $cart.subtotal_discount >0 }
<div class="box_paymentcalculations_promotionmessage">
{include file="views/checkout/components/applied_promotions.tpl" location=$location show_active="false" show_link="false"}
</div>
{/if}
{/if}
<!--End Payment Total Calculation -->
