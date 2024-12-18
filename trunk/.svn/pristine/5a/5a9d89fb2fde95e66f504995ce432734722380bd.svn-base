{* $Id: totals.post.tpl 10348 2010-08-04 12:38:43Z angel $ *}

{if $order_info.use_gift_certificates}
{foreach from=$order_info.use_gift_certificates item="certificate" key="code" name="certs"}
{if $order_info.parent_order_id =='0'}
<div style="float:left;	display:inline;	width:100%;	margin-top:7px;">
	<div style="float:left; display:inline; width:69%; text-align:right; font:13px trebuchet ms; color:#7c7e80;">
	{$lang.gift_certificate}&nbsp;{if !isset($mail_for_merchant)}({$code}){else if isset($mail_for_merchant) && $mail_for_merchant==1}({'/[^a-z -\s]/'|preg_replace:'X':$code}){/if}:
    </div>
    <div style="float:right; display:inline; width:29%; text-align:right; font:13px trebuchet ms; color:#636566;">
    {include file="common_templates/price.tpl" value=$certificate.cost}
	</div>
</div>
{/if}
{/foreach}
{/if}

