{* $Id: order_notification_subj.tpl 10702 2010-09-24 11:59:12Z klerik $ *}

{*{$company_placement_info.company_name|unescape}: {$lang.order} #{$order_info.order_id} {$order_status.email_subj}*}
{$settings.Company.company_name}: {*{$lang.order} #{$order_info.order_id} {$order_status.email_subj}*}

{if $order_info.payment_id == '6' && $order_status.status == 'O'}
	{$lang.order_received_cod_subj|replace:'[order_id]':$order_info.order_id}
{elseif $order_info.payment_id != '6' && $order_status.status == 'P'}
	{$lang.order_received_paid_subj|replace:'[order_id]':$order_info.order_id}
{elseif $order_info.payment_id == '6' && $order_status.status == 'Q'}
	{$lang.order_cod_confirmerd_subj|replace:'[order_id]':$order_info.order_id}
{elseif $order_info.payment_id != '6' && $order_status.status == 'A'}
	{$lang.order_paid_shipped_subj|replace:'[order_id]':$order_info.order_id}
{elseif $order_info.payment_id == '6' && $order_status.status == 'A'}
	{$lang.order_cod_shipped_subj|replace:'[order_id]':$order_info.order_id}
{elseif $order_status.status == 'I'}
	Your {$lang.order} #{$order_info.order_id} {$order_status.email_subj}
{elseif $order_status.status == 'M'}
	{$lang.order_refunded_subj|replace:'[order_id]':$order_info.order_id}
{elseif $order_status.status == 'H'}
	{$lang.order_delivered_subj|replace:'[order_id]':$order_info.order_id}
{else}
	{$lang.order} #{$order_info.order_id} {$order_status.email_subj}
{/if}
