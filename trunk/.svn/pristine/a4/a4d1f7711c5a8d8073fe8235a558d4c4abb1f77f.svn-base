{if $order_info.gift_it=='Y'}
{assign var="gift_message" value=$order_info.order_id|fn_get_order_gift_message}
{if !empty($gift_message)}
<div style="margin-top:5px; margin-bottom:8px; {if $controller=='order_lookup'}width:437px;{else}width:265px;{/if} float:left">
   <h3>{$lang.gift_heading}</h3>
   <div style="float:left; margin-bottom:5px; margin-top:5px; width:100%">
  	<div style="float:left; {if $controller=='order_lookup'}width:68%{else}width:50%{/if}"><b>{$lang.gift_to}:</b>{$gift_message.gift_to}</div>
    
   <div style="{if $controller=='order_lookup'}float:left;width:29%{else}float:right;width:50%{/if}"><b>{$lang.gift_from}:</b>{$gift_message.gift_from}</div>
    <div style="clear:both"></div>
   </div>
   <div style="float:left">
    <b>{$lang.gift_message}:</b>{$gift_message.message}
   </div>
   <div style="clear:both"></div> 
</div>
{/if}
{/if}


