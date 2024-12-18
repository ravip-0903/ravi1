{* $Id: totals.post.tpl 10348 2010-08-04 12:38:43Z angel $ *}

{if $order_info.parent_order_id =='0'}
	{if $order_info.points_info.in_use}
			<div  style="float:left; display:inline; width:100%; margin-top:7px;">
				<div  style="float:left; display:inline; width:69%; text-align:right; font:13px trebuchet ms; color:#7c7e80;">
					<strong>{$lang.points_in_use}</strong>&nbsp;({$order_info.points_info.in_use.points}&nbsp;{$lang.points_lower})&nbsp;<strong>:</strong>
				</div>
			
				<div  style="float:right; display:inline; width:29%; text-align:right; font:13px trebuchet ms; color:#636566;">
					{include file="common_templates/price.tpl" value=$order_info.points_info.in_use.cost}
				</div>
			</div>

		{/if}
{/if}
