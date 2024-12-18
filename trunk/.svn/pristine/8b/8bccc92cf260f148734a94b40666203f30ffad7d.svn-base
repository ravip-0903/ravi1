{* $Id: applied_promotions.tpl 8509 2010-01-05 08:22:11Z 2tl $ *}

{if $show_active == "true"}
<div class="buttons-container clear-both">
	{include file="buttons/button.tpl" but_role="text" but_text=$lang.active_promotions but_id="sw_applied_promotions" but_meta="cm-combination"}
</div>
{/if}

<div class="box_paymentcalculations_promotionmessage_congo">{$lang.text_applied_promotions}</div>

	<ul style="list-style:disc; margin-left:20px; float:left;">
	{foreach from=$applied_promotions item="promotion"}

			{if $promotion.short_description}
				{if $show_link == "false"}
                	<div class="box_paymentcalculations_promotionmessage_promo">{$promotion.name}</div>
                {else}
                    <a id="sw_promo_description_{$promotion.promotion_id}">{$promotion.name}</a>
                    
                    <div id="promo_description_{$promotion.promotion_id}" class="hidden">{$promotion.short_description|unescape}</div>
                {/if}
			{else}
				<li><strong>{$promotion.name}</strong></li>			
			{/if}

	{/foreach}	
    </ul>
<!--applied_promotions-->
