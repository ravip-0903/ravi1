
{foreach from=$order_info.items item="oi"}
{assign var='product_code' value=$oi.product_code}
{/foreach}

{assign var=block value=$product_code|fn_targeting_mantra_block}


<div>
<div style="overflow-x: hidden;">
<div style="width:100%;" >
{assign var="recommendation" value=$block.eppr.recommendedItems}
<table cellspacing="0" cellpadding="0" border="0"  style="background-color:#f2faff; border-bottom:2px solid #66c7ff; margin-right:5px;">
<tr bgcolor="#4cbeff">
	{$lang.recommendation_just_for_you}
	
	<td></td>
	<td></td>
</tr>
<tr>
{foreach from=$recommendation item="product" name="productlisting"}

<td width="150px" >
<div style="float:left; text-align:center; padding:5px; margin-right:5px;">

<a style="color: #048CCC;font: bold 12px trebuchet ms; text-decoration:none;" href="{$product.itemURL}" target="_blank">
	<img src="{$product.itemImage}" width="150" style="border:1px solid #ccc;">
	<span style="height: 34px; display:block; line-height:14px; clear:both; font:normal 11px verdana,'ubuntu';">{$product.itemTitle|truncate:50}</span>
</a>


<div style="left:73px; position:absolute; bottom:0; top: 212px;">
<span style="float: left;width: 100%;font: 9px verdana,'ubuntu'; color: #757575; margin: 1px 0 0;text-decoration: line-through;">MRP: {$product.itemMRP}
</span>
<div style="position:absolute; bottom:0; width: 100%; clear:both; text-align: center; white-space:nowrap;">
<div style="color: #900;font: bold 14px verdana,'ubuntu';margin-top: 10px;">
Rs. {$product.itemPrice}
</div>
</div>
</div>
</div>
</td>

{/foreach}
</tr></table>
</div></div></div>