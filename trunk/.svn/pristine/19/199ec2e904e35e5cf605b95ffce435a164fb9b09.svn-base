{* $Id: totals_content.post.tpl 12143 2011-03-31 12:02:30Z subkey $ *}

{if $order_info.payment_method.payment_id != "6"}
{if $order_info.points_info.reward}
	<li>
		<em>{$lang.points}:</em>
		<span>{$order_info.points_info.reward}&nbsp;{$lang.points_lower}</span>
	</li>
{/if}
{/if}
{if $order_info.points_info.in_use}
	<li>
	{if !"COMPANY_ID"|defined}
		<em>{$lang.points_in_use}&nbsp;({$order_info.points_info.in_use.points}&nbsp;{$lang.points_lower}):</em>
	{else}
		<em>{$lang.print_shipping_cluesbucks}</em>
	{/if}
		<span>-{include file="common_templates/price.tpl" value=$order_info.points_info.in_use.cost}</span>
	</li>
{/if}
