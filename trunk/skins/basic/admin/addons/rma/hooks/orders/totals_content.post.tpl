{* $Id: totals_content.post.tpl 12143 2011-03-31 12:02:30Z subkey $ *}

{if $order_info.return}
	<li>
		<em>{$lang.rma_return}:&nbsp;</em>
		<span>{include file="common_templates/price.tpl" value=$order_info.return}</span>
	</li>
{/if}