{* $Id: totals.post.tpl 6483 2008-12-03 14:57:53Z zeke $ *}

	{if $order_info.points_info.in_use}
		<div class="box_paymentcalculations_row">
			<div class="box_paymentcalculations_fieldname">
				<strong>{$lang.points_in_use}</strong>&nbsp;({$order_info.points_info.in_use.points}&nbsp;{$lang.points_lower})&nbsp;<strong>:</strong>
			</div>
			
			<div class="box_paymentcalculations_field">
				{include file="common_templates/price.tpl" value=$order_info.points_info.in_use.cost}
			</div>
		</div>

	{/if}
	
	{*{if $order_info.payment_method.payment_id != "6"}
		{if $order_info.points_info.reward}
			<tr>
				<td><strong>{$lang.points}:&nbsp;</strong></td>
				<td>{$order_info.points_info.reward}&nbsp;
		        <a href="{$addons.sdeep.cod_explanation_url}">
		        {$lang.points_lower}
		        </a>
		        </td>
			</tr>
		{/if}
	{/if}*}
