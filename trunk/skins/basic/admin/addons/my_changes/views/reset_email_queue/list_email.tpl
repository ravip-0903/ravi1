{* $Id: list_email.tpl 12178 2011-04-06 12:14:37Z bimib $ *}


<div id="content_manage_emails">
{include file="common_templates/pagination.tpl" save_current_page=true save_current_url=true div_id=$smarty.request.content_id}

{assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}

{if $settings.DHTML.admin_ajax_based_pagination == "Y"}
	{assign var="ajax_class" value="cm-ajax cm-history"}
{/if}

{assign var="rev" value=$smarty.request.content_id|default:"pagination_contents"}

<table border="0" cellpadding="0" cellspacing="0" width="100%" class="table sortable">
<tr>
	<th width="1%" class="center">
		<input type="checkbox" name="check_all" value="Y" title="{$lang.check_uncheck_all}" class="checkbox cm-check-items" /></th>
	<th width="5%"><a class="{$ajax_class}{if $search.sort_by == "order_id"} sort-link-{$search.sort_order}{/if}" href="{"`$c_url`&amp;sort_by=order_id&amp;sort_order=`$search.sort_order`"|fn_url}" rev={$rev}>{$lang.id}</a></th>
	<th width="30%"><a class="{$ajax_class}{if $search.sort_by == "status"} sort-link-{$search.sort_order}{/if}" href="{"`$c_url`&amp;sort_by=status&amp;sort_order=`$search.sort_order`"|fn_url}" rev={$rev}>{$lang.email}</a></th>
	<th width="20%"><a class="{$ajax_class}{if $search.sort_by == "customer"} sort-link-{$search.sort_order}{/if}" href="{"`$c_url`&amp;sort_by=customer&amp;sort_order=`$search.sort_order`"|fn_url}" rev={$rev}>{$lang.subject}</a></th>
	<th width="10%"><a class="{$ajax_class}{if $search.sort_by == "email"} sort-link-{$search.sort_order}{/if}" href="{"`$c_url`&amp;sort_by=email&amp;sort_order=`$search.sort_order`"|fn_url}" rev={$rev}>{$lang.status}</a></th>
    <!-- Add By Paresh -->
    <th width="15%">Retry Count
	</th><!--END Add By Paresh -->
	<th width="15%">Error Message
	</th>
	<th width="20%">Date Created</th>
	<th>&nbsp;</th>
</tr>
{foreach from=$list_email_data item="o"}
{hook name="orders:order_row"}
<tr {cycle values="class=\"table-row\", "}>
	<td class="center">
		<input type="checkbox" name="order_ids[]" value="{$o.id}" class="checkbox cm-item" /></td>
	<td>
		<a href="{"orders.details?order_id=`$o.order_id`"|fn_url}" class="underlined">&nbsp;#{$o.id}&nbsp; {include file="views/companies/components/company_name.tpl" company_name=$o.company_name company_id=$o.company_id}</a>
		{if $order_statuses_data[$o.status].appearance_type == "I" && $o.invoice_id}
			<p class="small-note">{$lang.invoice} #{$o.invoice_id}</p>
		{elseif $order_statuses_data[$o.status].appearance_type == "C" && $o.credit_memo_id}
			<p class="small-note">{$lang.credit_memo} #{$o.credit_memo_id}</p>
		{/if}
	</td>
	<td>
		{* [andyye] *}
		{if !"COMPANY_ID"|defined}
			<a href="mailto:{$o.email|escape:url}">{$o.to_email}</a>
		{else}
			**********************
		{/if}
		{* [/andyye] *}
	</td>
	<td>{$o.subject}</td>
	<td>
		{$o.status}
	</td><!-- Add By Paresh -->
    <td class="nowrap">
		{$o.retry_count}</td><!--End Add By Paresh -->
	<td class="nowrap">
		{$o.retry_count}</td>
	<td >
		{$o.error_message}
    </td>
    <td >
		{$o.date_created}
    </td>
</tr>
{/hook}
{foreachelse}
<tr class="no-items">
	<td colspan="9"><p>{$lang.no_data}</p></td>
</tr>
{/foreach}
</table>

{include file="common_templates/pagination.tpl" div_id=$smarty.request.content_id}
</div>