{* $Id: order_promotion.tpl 9153 2010-03-25 10:02:32Z lexa $ *}
{assign var="bonus_coupon" value=""}
{assign var="promotions" value=$order_info.promotions}
{foreach from=$promotions item="promotion" name="pfe" key="promotion_id"}

{foreach from=$order_info.promotions.$promotion_id.bonuses item="bonus"}
{if $bonus.bonus == "give_coupon"}

{assign var="bonus_coupon" value=$bonus_coupon|cat:$bonus.coupon_code}

{/if}
{/foreach}

{/foreach}
{if $bonus_coupon!='' and ($order_status.status=='P' or $order_status.status=='O') }
 
  {$lang.promotion_eligible_text}

{elseif $bonus_coupon!='' and $order_status.status=='C'} 

{$lang.get_order_promotion|replace:'[bonus_coupon]':$bonus_coupon}


{/if}