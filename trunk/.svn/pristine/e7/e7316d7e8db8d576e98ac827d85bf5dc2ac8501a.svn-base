{* $Id: returns.tpl 9517 2010-05-19 14:02:43Z klerik $ *}
{*
{capture name="section"}
	{include file="addons/rma/views/rma/components/rma_search_form.tpl"}
{/capture}
{include file="common_templates/section.tpl" section_title=$lang.search section_content=$smarty.capture.section}*}

<form action="{""|fn_url}" method="post" name="rma_list_form">

{include file="common_templates/pagination.tpl"}

{assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}
{if $search.sort_order == "asc"}
{assign var="sort_sign" value="&nbsp;&nbsp;&#8595;"}
{else}
{assign var="sort_sign" value="&nbsp;&nbsp;&#8593;"}
{/if}
{if $settings.DHTML.admin_ajax_based_pagination == "Y"}
	{assign var="ajax_class" value="cm-ajax"}

{/if}
<div class="box_headerTwo">
<h1 class="box_headingTwo">{$lang.my_return_request}</h1>
</div>
<div class="clearboth height_ten"></div>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table">
<tr>
	<th width="10%"><a class="{$ajax_class}" href="{"`$c_url`&amp;sort_by=return_id&amp;sort_order=`$search.sort_order`"|fn_url}" rev="pagination_contents">{$lang.id}</a>{if $search.sort_by == "return_id"}{$sort_sign}{/if}</th>
	<th width="15%"><a class="{$ajax_class}" href="{"`$c_url`&amp;sort_by=status&amp;sort_order=`$search.sort_order`"|fn_url}" rev="pagination_contents">{$lang.status}</a>{if $search.sort_by == "status"}{$sort_sign}{/if}</th>
	<th width="35%"><a class="{$ajax_class}"><span>{$lang.product} </span><span class="mobile_inline"> / {$lang.order}&nbsp;{$lang.id} </span><span class="mobile_inline"> / {$lang.date}</span></a></th>
	<th class="no_mobile" width="18%"><a class="{$ajax_class}" href="{"`$c_url`&amp;sort_by=timestamp&amp;sort_order=`$search.sort_order`"|fn_url}" rev="pagination_contents">{$lang.date}</a>{if $search.sort_by == "timestamp"}{$sort_sign}{/if}</th>
	<th class="no_mobile" width="10%"><a class="{$ajax_class}" href="{"`$c_url`&amp;sort_by=order_id&amp;sort_order=`$search.sort_order`"|fn_url}" rev="pagination_contents">{$lang.order}&nbsp;{$lang.id}</a>{if $search.sort_by == "order_id"}{$sort_sign}{/if}</th>
	<th width="8%" class="no_mobile"><a class="{$ajax_class}" href="{"`$c_url`&amp;sort_by=amount&amp;sort_order=`$search.sort_order`"|fn_url}" rev="pagination_contents">{$lang.quantity}</a>{if $search.sort_by == "amount"}{$sort_sign}{/if}</th>
</tr>
{assign var="i" value=0}
{foreach from=$return_requests item="request"}
<tr {cycle values=",class=\"table-row\""}>
	<td class="center"><a href="{"rma.details?return_id=`$request.return_id`"|fn_url}">&nbsp;<strong>{$request.return_id}</strong>&nbsp;</a></td>
	<td>
		<input type="hidden" name="origin_statuses[{$request.return_id}]" value="{$request.status}">
		{include file="common_templates/status.tpl" status=$request.status display="view" name="return_statuses[`$request.return_id`]" status_type=$smarty.const.STATUSES_RETURN}
	</td>
	<td>
    <ul class="no-markers">
    	{foreach from=$product_info[$i] item='order_detail' }
			<li>{$order_detail.product}</li>
<li class="mobile"><a href="{"orders.details?order_id=`$request.order_id`"|fn_url}">&nbsp;<strong>{$request.order_id}</strong>&nbsp;</a></li>
<li class="mobile"><a href="{"rma.details?return_id=`$request.return_id`"|fn_url}">{$request.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}</a></li>
	    {/foreach}
    </ul>    
     {assign var="i" value=$i+1}</td>
	<td class="no_mobile"><a href="{"rma.details?return_id=`$request.return_id`"|fn_url}">{$request.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}</a></td>
	<td class="center no_mobile"><a href="{"orders.details?order_id=`$request.order_id`"|fn_url}">&nbsp;<strong>{$request.order_id}</strong>&nbsp;</a></td>
	<td class="center no_mobile">{$request.total_amount}</td>
<tr>
{foreachelse}
<tr>
	<td colspan="6"><p class="no-items">{$lang.no_return_requests_found}</p></td>
</tr>
{/foreach}
<tr class="table-footer">
	<td colspan="6">&nbsp;</td>
</tr>
</table>

{include file="common_templates/pagination.tpl"}

</form>

{capture name="mainbox_title"}{$lang.return_requests}{/capture}
