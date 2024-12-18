{* $Id: sales_reports_search_form.tpl 9517 2010-05-19 14:02:43Z klerik $ *}

{capture name="section"}

<form action="{""|fn_url}" method="GET" name="milkrun_report_full">

{include file="common_templates/period_selector.tpl" period=$period form_name="orders_search_form" display="form" but_name="dispatch[milkrun_reports.reports]"}

{if $report_type == 'full'}
<p><label>Order By</label>
<select name="order_by">
	<option value="state">State</option>
</select>
{elseif $report_type == 'merchant'}
<label>Merchant Name </label>
<select name="merchant">
	{foreach from=$companies item=company}
    	<option value="{$company.company_id}">{$company.company}</option>
    {/foreach}
</select>
{/if}
<input type="hidden" name="report_type" value="{$report_type}" />
</p>
</form>

{/capture}
{include file="common_templates/section.tpl" section_content=$smarty.capture.section}
