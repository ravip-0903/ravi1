{* $Id: order_notification.tpl 9153 2010-03-25 10:02:32Z lexa $ *}

{include file="letter_header.tpl"}
  <span style="min-height:400px;margin-top:50px;">	
	Order Number: {$order_info.order_id}<br/><br/>
	Return Number: {$return_info.return_id}<br/><br/>
	Comment: {$return_info.comment}<br/><br/>
	
	{foreach from=$return_info.items[$smarty.const.RETURN_PRODUCT_ACCEPTED] item="ri" key="key"}
	Product: {$ri.product|unescape}  		<br/><br/>
			{assign var="reason_id" value=$ri.reason}
	Reason:	{$reasons.$reason_id.property}<br/><br/>
			{assign var="action_id" value=$return_info.action}
	Action: {$actions.$action_id.property}<br/><br/>
	{/foreach}	
  </span>	
        <br/><br/><br/><br/>
{include file="letter_footer.tpl"}
