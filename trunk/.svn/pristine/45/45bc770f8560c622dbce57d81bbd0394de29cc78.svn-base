{* $Id: reports.tpl 12178 2011-04-06 12:14:37Z bimib $ *}
{script src="lib/amcharts/swfobject.js"}

<div id="content_{$report.report_id}">

<h1 class="mainbox-title">MilkRun {$report_type} Reports</h1>

{include file="addons/milkrun_reports/views/milkrun_reports/component/milkrun_reports_search_form.tpl" period=$report.period search=$report}
{if $report_rows && $report_type == 'full'}
{include file="addons/milkrun_reports/views/milkrun_reports/full_reports_view.tpl" report_rows=$report_rows}
{elseif $report_rows && $report_type == 'merchant'}
{include file="addons/milkrun_reports/views/milkrun_reports/merchant_reports_view.tpl" report_rows=$report_rows}
{/if}
<!--content_{$report.report_id}--></div>
