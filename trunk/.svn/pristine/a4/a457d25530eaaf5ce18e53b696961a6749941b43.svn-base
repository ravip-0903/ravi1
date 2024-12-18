{if $order_info.gift_it=='Y'}
{assign var="gift_message" value=$order_info.order_id|fn_get_order_gift_message}
{if !empty($gift_message)}
<div style="margin-top:5px; margin-bottom:8px; width:100%; float:left">
   <h3>{$lang.gift_heading}</h3>
   <div style="float:left;width:100%">
  	   <b>{$lang.gift_to}:</b>{$gift_message.gift_to}
   </div>
   <div style="float:left; width:100%; margin:8px 5px; padding-left:75px">
      {$gift_message.message}
   </div>
   <div style="float:right; width:100%; text-align:right">
      <b>{$lang.gift_from}:</b>{$gift_message.gift_from}
   </div>
   <div style="clear:both"></div> 
</div>
{/if}
{/if}