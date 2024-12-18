
{* $Id: order_totals_custom.tpl 12565 2011-05-31 08:31:28Z alexions $ *}
<!--Payment Total Calculation -->
<div  style="width:100%; border:none; float:right;	display:inline;	margin-top:20px;">
<div  style="float:left;	display:inline;	width:100%;	margin-top:7px;">
<div style="color: #7C7E80;
    display: inline;
    float: left;
    font: 13px trebuchet ms;
    text-align: right;
    width: 69%;">
{$lang.subtotal} :
</div>

<div style="float:right; display:inline; width:29%; text-align:right; font:13px trebuchet ms; color:#636566; font-weight:bold;">
{include file="common_templates/price.tpl" value=$order_info.display_subtotal}
</div>
</div>
{if $order_info.parent_order_id =='0'}
	{if ($order_info.subtotal_discount|floatval)}
	<div style="float:left;	display:inline;	width:100%;	margin-top:7px;">
	<div style="float:left; display:inline; width:69%; text-align:right; font:13px trebuchet ms; color:#7c7e80;">
	{$lang.order_discount} :
	</div>
	<div style="float:right; display:inline; width:29%; text-align:right; font:13px trebuchet ms; color:#636566;">
	{include file="common_templates/price.tpl" value=$order_info.subtotal_discount}
	</div>
	</div>
	{/if}
{/if}
{if $order_info.parent_order_id =='0'}
	{if ($order_info.subtotal_discount|floatval)}
	{assign var="after_discount" value=$order_info.subtotal-$order_info.subtotal_discount}
	<div style="float:left;	display:inline;	width:100%;	margin-top:7px;">
	<div style="float:left; display:inline; width:69%; text-align:right; font:13px trebuchet ms; color:#7c7e80;">
	{$lang.price_after_discount} :
	</div>

	<div style="float:right; display:inline; width:29%; text-align:right; font:13px trebuchet ms; color:#636566;">
	{include file="common_templates/price.tpl" value=$after_discount}	
	</div>

	</div>
	{/if}
{/if}

{if $order_info.parent_order_id =='0'}
	<div style="float:left;	display:inline;	width:100%;	margin-top:7px;">
		<div style="float:left; display:inline; width:69%; text-align:right; font:13px trebuchet ms; color:#7c7e80;"> {$lang.shipping_cost} : </div>	
		<div style="float:right; display:inline; width:29%; text-align:right; font:13px trebuchet ms; color:#636566;">
			{include file="common_templates/price.tpl" value=$order_info.display_shipping_cost}
		</div>
	</div>
{/if}

{hook name="orders:totals"}
{/hook}


{if $order_info.parent_order_id =='0'}
<div style="float:left;	display:inline;	width:100%;	margin-top:7px;">

{hook name="checkout:checkout_totals"}
{/hook}
</div>
{/if}

{if $order_info.parent_order_id =='0'}
	{if $order_info.emi_fee != '0' && $order_info.emi_fee != ''  }
	<div style="float:left;	display:inline;	width:100%;	margin-top:7px;">
		<div style="float:left; display:inline; width:69%; text-align:right; font:13px trebuchet ms; color:#7c7e80;">
			{$lang.emi_process_fee} :
		</div>
	
		<div style="float:right; display:inline; width:29%; text-align:right; font:13px trebuchet ms; color:#636566;">
			{include file="common_templates/price.tpl" value=$order_info.emi_fee}
		</div>
	</div>
	{/if}
{/if}
{if $order_info.parent_order_id =='0'}
	{if $order_info.cod_fee != '0' && $order_info.cod_fee != ''  }
	<div style="float:left;	display:inline;	width:100%;	margin-top:7px;">
		<div style="float:left; display:inline; width:69%; text-align:right; font:13px trebuchet ms; color:#7c7e80;">
			{$lang.cod_process_fee} :
		</div>
	
		<div style="float:right; display:inline; width:29%; text-align:right; font:13px trebuchet ms; color:#636566;">
			{include file="common_templates/price.tpl" value=$order_info.cod_fee}
		</div>
	</div>
	{/if}
{/if}
{if $order_info.parent_order_id =='0'}
	{if $order_info.gift_it=='Y'}
	<div style="float:left;	display:inline;	width:100%;	margin-top:7px;">
		<div style="float:left; display:inline; width:69%; text-align:right; font:13px trebuchet ms; color:#7c7e80;">
			{$lang.gift_fee} :
		</div>
	
		<div style="float:right; display:inline; width:29%; text-align:right; font:13px trebuchet ms; color:#636566;">
			{include file="common_templates/price.tpl" value=$order_info.gifting_charge}
		</div>
	</div>
	{/if}
{/if}
{if $order_info.parent_order_id =='0'}
<div style="float:left;	display:inline;	width:100%;	margin-top:7px;">
	<div style="float:left; display:inline; width:69%; text-align:right; font:13px trebuchet ms; color:#7c7e80;">
	<span style="float:left; display:inline; width:100%; text-align:right; font:bold 20px trebuchet ms; color:#99002A;">
	{$lang.you_will_pay} :
	</span>
	</div>

	<div style="float:right; display:inline; width:29%; text-align:right; font:13px trebuchet ms; color:#636566;">
	<span  style="float:left; display:inline; width:100%; text-align:right; font:bold 20px trebuchet ms; color:#99002A;">
	{include file="common_templates/price.tpl" value=$order_info.total}
	</span>
	</div>
{/if}
<div style="clear:both;"></div>
<div style="	float:left; display:inline; width:100%; text-align:right; font:11px verdana; color:#7c7e80;">
{if $order_info.parent_order_id =='0'}
	{if $order_info.payment_id != "6"}
	    {if $order_info.points_info.reward}
		    <span>{$lang.you_earn}:</span>
		    <strong>{$order_info.points_info.reward}</strong>
	    {/if}
	{/if}
{/if}
</div>




</div>

{if $order_info.parent_order_id =='0'}
	{if $cart.discount >0 || $cart.subtotal_discount >0 }
	<div  style="	float:right; display:inline; width:96%; font:11px verdana; color:#333; border:1px dashed #0d860f; border-radius:5px; margin-top:10px; padding:5px; background-color:#b3dcb4;">
	{include file="views/checkout/components/applied_promotions.tpl" location=$location show_active="false" show_link="false"}
	</div>
	{/if}
	</div>
{/if}

<!--End Payment Total Calculation -->


