{if $config.cb_reward_on_cod==TRUE}
	{assign var="reward" value="TRUE"}
{else}
	{if $cart.payment_id=="6"}
		{assign var="reward" value="FALSE"}
	{else}
		{assign var ="reward" value="TRUE"}
	{/if}
{/if}


{if $reward=="TRUE"}
    {if isset($cart.gift_certificates)}
    	{assign var="cb_on_gc" value=$cart.gift_certificates|fn_cb_reward_on_gc}
     {else}
     	{assign var="cb_on_gc" value="0"}
     {/if}
    {if $cart.points_info.reward}
            <span>{$lang.you_earn}:</span>
            {if $cb_on_gc > 0}
            	<strong>{$cart.points_info.reward+$cb_on_gc }</strong>
            {else}
            	<strong>{$cart.points_info.reward}</strong>
            {/if}
    {/if}
{/if}
