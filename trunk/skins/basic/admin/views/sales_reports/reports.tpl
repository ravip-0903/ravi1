{* $Id: reports.tpl 12178 2011-04-06 12:14:37Z bimib $ *}
{script src="lib/amcharts/swfobject.js"}

{if $smarty.request.order == 'asc'}
   {assign var="order" value="desc"}
{else}
   {assign var="order" value="asc"}
{/if}

<div id="content_{$report.report_id}">

{capture name="mainbox"}

{capture name="extra_tools"}
	{include file="buttons/button.tpl" but_text=$lang.edit_report but_href="sales_reports.table.edit?report_id=$report_id&table_id=`$table.table_id`" but_role="tool"}
{/capture}

{include file="views/sales_reports/components/sales_reports_search_form.tpl" period=$report.period search=$report}

{if $report_id < 7}
{if $report}

{capture name="tabsbox"}
{if $report.tables}
{assign var="table_id" value=$table.table_id}
{assign var="table_prefix" value="table_$table_id"}
<div id="content_table_{$table_id}">

{if !$table.elements || $table.empty_values == "Y"}

<p class="no-items">{$lang.no_data}</p>

{elseif $table.type == "T"}

{if $table_conditions.$table_id}
<p>
	<a id="sw_box_table_conditions_{$table_id}" class="text-link text-button cm-combination">{$lang.table_conditions}</a>
</p>
<div id="box_table_conditions_{$table_id}" class="hidden">
	{foreach from=$table_conditions.$table_id item="i"}
	<div class="form-field">
	<label>{$i.name}:</label>
	{foreach from=$i.objects item="o" name="feco"}
	{if $o.href}<a href="{$o.href|fn_url}">{/if}{$o.name}{if $o.href}</a>{/if}{if !$smarty.foreach.feco.last}, {/if}
	{/foreach}
	</div>
	{/foreach}
</div>
{/if}

{if $table.interval_id != 1}

<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table-fixed">
<tr valign="top">
	{cycle values="" assign=""}
	<td width="300">
		<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table">
		<tr>
			<th width="100%">{$table.parameter}</th>
		</tr>
		{foreach from=$table.elements item=element}
		<tr>
			<td>{$element.description|unescape}&nbsp;</td>
		</tr>
		{/foreach}
		<tr>
			<td class="right">{$lang.total}:</td>
		</tr>
		</table>
	</td>
	<td>
	{cycle values="" assign=""}
	<div id="div_scroll_{$table_id}" class="scroll-x">
		<table cellpadding="0" cellspacing="0" border="0" class="table no-left-border">
		<tr>
				{foreach from=$table.intervals item=row}
				<th>&nbsp;{$row.description}&nbsp;</th>
				{/foreach}
		</tr>
		{foreach from=$table.elements item=element}
		<tr>
		{assign var="element_hash" value=$element.element_hash}
				{foreach from=$table.intervals item=row}
				{assign var="interval_id" value=$row.interval_id}
				<td class="center">
				{if $table.values.$element_hash.$interval_id}
				{if $table.display != "product_number" && $table.display != "order_number"}{include file="common_templates/price.tpl" value=$table.values.$element_hash.$interval_id}{else}{$table.values.$element_hash.$interval_id}{/if}
				{else}-{/if}</td>
				{/foreach}
		</tr>
		{/foreach}
		<tr>
			{foreach from=$table.totals item=row}
			<td class="center">
				{if $row}
				<span>{if $table.display != "product_number" && $table.display != "order_number"}{include file="common_templates/price.tpl" value=$row}{else}{$row}{/if}</span>
				{else}-{/if}
			</td>
			{/foreach}
		</tr>
		</table>
	</div>
	</td>
</tr>
</table>

{else}

<table cellpadding="0" cellspacing="0" border="0" width="500" class="table-fixed">
<tr>
	{cycle values="" assign=""}
	<td width="403" valign="top">
		<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table no-bottom-border">
		<tr>
			<th>{$table.parameter}</th>
		</tr>
		</table>
	</td>
	<td width="100">
		<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table no-left-border no-bottom-border">
		<tr>
			{foreach from=$table.intervals item=row}
			{assign var="interval_id" value=$row.interval_id}
			{assign var="interval_name" value="reports_interval_$interval_id"}
			<th class="center">&nbsp;{$lang.$interval_name}&nbsp;</th>
			{/foreach}
		</tr>
		</table>
	</td>
</tr>
</table>

{assign var="elements_count" value=$table.elements|sizeof}

{if $elements_count>14}
<div id="div_scroll_{$table_id}" class="reports-table-scroll">
{/if}

<table cellpadding="0" cellspacing="0" border="0" class="table-fixed" width="500">
<tr valign="top">
	<td width="403" class="max-height no-padding">
		<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table no-top-border">
		{foreach from=$table.elements item=element}
		{assign var="element_hash" value=$element.element_hash}
		<tr>
			{foreach from=$table.intervals item=row}
			{assign var="interval_id" value=$row.interval_id}
			{math equation="round(value_/max_value*100)" value_=$table.values.$element_hash.$interval_id|default:"0" max_value=$table.max_value assign="percent_value"}
			{*if $percent_value<1}{assign var="percent_value" value=1}{/if*}
			{/foreach}
			<td class="no-padding">
			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table-fixed">
			<tr>
				<td class="nowrap overflow-hidden" width="233">{$element.description|unescape}&nbsp;</td>
				<td align="right" width="120">{include file="views/sales_reports/components/graph_bar.tpl" bar_width="100px" value_width=$percent_value}</td>
			</tr>
			</table>
			</td>
		</tr>
		{/foreach}
		<tr>
			<td class="right">{$lang.total}:</td>
		</tr>
		</table>
	</td>
	<td width="100">
		{cycle values="" assign=""}
		<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table no-top-border no-left-border">
		{foreach from=$table.elements item=element}
		<tr>
		{assign var="element_hash" value=$element.element_hash}
				{foreach from=$table.intervals item=row}
				{assign var="interval_id" value=$row.interval_id}
				<td  class="center">
				{if $table.values.$element_hash.$interval_id}
				{if $table.display != "product_number" && $table.display != "order_number"}{include file="common_templates/price.tpl" value=$table.values.$element_hash.$interval_id}{else}{$table.values.$element_hash.$interval_id}{/if}
				{else}-{/if}</td>
				{/foreach}
		</tr>
		{/foreach}
		<tr>
			{foreach from=$table.totals item="row"}
			<td class="center">
				{if $row}
				<span>{if $table.display != "product_number" && $table.display != "order_number"}{include file="common_templates/price.tpl" value=$row}{else}{$row}{/if}</span>
				{else}-{/if}
			</td>
			{/foreach}
		</tr>
		</table>
	</td>
</tr>
</table>

{if $elements_count>14}
</div>
{/if}

{/if}

{elseif $table.type == "P"}
	<div id="{$table_prefix}pie">{include file="views/sales_reports/components/amchart.tpl" type="pie" chart_data=$new_array.pie_data chart_id=$table_prefix chart_title=$table.description chart_height=$new_array.pie_height}<!--{$table_prefix}pie--></div>

{elseif $table.type == "C"}
	<div id="{$table_prefix}pie">{include file="views/sales_reports/components/amchart.tpl" type="pie" set_type="piefl" chart_data=$new_array.pie_data chart_id=$table_prefix chart_title=$table.description chart_height=$new_array.pie_height}<!--{$table_prefix}pie--></div>

{elseif $table.type == "B"}
	<div id="div_scroll_{$table_id}" class="reports-graph-scroll">
		<div id="{$table_prefix}bar">{include file="views/sales_reports/components/amchart.tpl" type="column" chart_data=$new_array.column_data chart_id=$table_prefix chart_title=$table.description chart_height=$new_array.column_height chart_width=$new_array.column_width}<!--{$table_prefix}bar--></div>
	</div>
{/if}

<!--content_table_{$table_id}--></div>

{else}
	<p class="no-items">{$lang.no_data}</p>
{/if}

{/capture}
{include file="common_templates/tabsbox.tpl" content=$smarty.capture.tabsbox active_tab="table_`$table_id`" track=true}

{else}
	<p class="no-items">{$lang.no_data}</p>
{/if}
{/capture}
{include file="common_templates/mainbox.tpl" title=$lang.reports content=$smarty.capture.mainbox extra_tools=$smarty.capture.extra_tools}

<!--content_{$report.report_id}--></div>
{elseif $report_id == '7'}
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table">
	<tr><th width="10%">{$lang.sl_no}</th><th width="30%"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=cmp">{$lang.merchant_name}  </a></th><th width="20%" style="text-align:right;"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=gsp">{$lang.gsp}</a></th><th width="20%" style="text-align:right;"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=qty">{$lang.total_order}</a></th> <th width="20%" style="text-align:right;"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=asp">{$lang.asp}</a></th></tr>

		    <tr><th style="font-weight:bold">{$lang.total}</th><th></th><th id='tot_ave_amt_pre' style="text-align:right;font-weight:bold" ></th><th id='tot_ave_order_pre' style="text-align:right;font-weight:bold"></th><th id='tot_ave_asp_pre' style="text-align:right;font-weight:bold"></th></tr>
		    <tr><th style="font-weight:bold">{$lang.average}</th><th></th><th id='amt_gsp_pre' style="text-align:right;font-weight:bold"> </th><th id='amt_orders_pre' style="text-align:right;font-weight:bold"> </th> <th id='asp_pre' style="text-align:right;font-weight:bold"> </th></tr>

		{assign var="count" value=$report.tables|count}
		{assign var="sl_no" value=1}
		{foreach from=$report.tables item="item_report"}

		   {assign var="tot_amt" value=$item_report.tot_amt+$tot_amt}
		   {assign var="total_orders" value=$item_report.tot_order+$total_orders}
		   {assign var="total_asp_amt" value=$item_report.asp+$total_asp_amt}
			<tr><td>{$sl_no}</td><td>{$item_report.company}</td><td align="right">{$item_report.tot_amt|number_format}</td><td align="right">{$item_report.tot_order|number_format}</td><td align="right">
			{$item_report.asp|number_format:2}</td></tr>
			{assign var="sl_no" value=$sl_no+1}
		   {assign var="tot_qty" value=$item_report.tot_order+$tot_qty}
		{/foreach}
		{assign var="ave_amt" value=$tot_amt/$count}
		{assign var="ave_orders" value=$tot_qty/$count}

		<input type="hidden" name="tot_ave_amt" id="tot_ave_amt" value={$tot_amt|number_format}>
		<input type="hidden" name="tot_ave_order" id="tot_ave_order" value={$tot_qty}>
		<input type="hidden" name="tot_asp_amt" id="tot_asp_amt" value={$tot_amt/$total_orders|number_format}>
		<input type="hidden" name="amt_gsp" id="amt_gsp" value={$ave_amt|number_format}>
		<input type="hidden" name="amt_orders" id="amt_orders" value={$ave_orders|number_format}>
		<input type="hidden" name="asp" id="asp" value={$total_asp_amt/$count|number_format}>
	</table>
	<script type="text/javascript">
	//<![CDATA[
	{literal}
		document.getElementById('tot_ave_amt_pre').innerHTML = document.getElementById('tot_ave_amt').value;
		document.getElementById('tot_ave_order_pre').innerHTML = document.getElementById('tot_ave_order').value;
		document.getElementById('tot_ave_asp_pre').innerHTML = document.getElementById('tot_asp_amt').value;
		document.getElementById('amt_gsp_pre').innerHTML = document.getElementById('amt_gsp').value;
		document.getElementById('amt_orders_pre').innerHTML = document.getElementById('amt_orders').value;
		//document.getElementById('asp_pre').innerHTML = document.getElementById('asp').value;
	{/literal}
	//]]>
	</script>

{elseif $report_id == '8'}
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table">

	    <tr><th width="10%">{$lang.sl_no}</th><th width="30%"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=cmp">{$lang.category_name}</a></th><th width="20%" style="text-align:right;"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=gsp">{$lang.gsp}</a></th><th width="20%" style="text-align:right;"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=qty">{$lang.total_order}</a></th><th width="20%" style="text-align:right;"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=asp">{$lang.asp}</a></th></tr> 

		    <tr><th style="font-weight:bold">{$lang.total}</th><th></th><th id='tot_ave_amt_pre' style="text-align:right;font-weight:bold"></th><th id='tot_ave_order_pre' style="text-align:right;font-weight:bold"></th><th id='tot_ave_asp_pre' style="text-align:right;font-weight:bold"></th></tr>
		    <tr><th style="font-weight:bold">{$lang.average}</th><th></th><th id='amt_gsp_pre' style="text-align:right;font-weight:bold;"> </th><th id='amt_orders_pre' style="text-align:right;font-weight:bold;"> </th> <th id='asp_pre' style="text-align:right;font-weight:bold;"> </th></tr>

		{assign var="count" value=$report.tables|count}
		{assign var="sl_no" value=1}
		{foreach from=$report.tables item="item_report"}
		   {assign var="tot_amt" value=$item_report.tot_amt+$tot_amt}
		   {assign var="total_orders" value=$item_report.tot_order+$total_orders}
		   {assign var="asp_amt" value=$item_report.asp+$asp_amt}
		   <tr><td>{$sl_no}</td><td>{$item_report.cd1c} </td><td align="right">{$item_report.tot_amt|number_format}</td><td align="right">{$item_report.tot_order|number_format}</td><td align="right">{$item_report.asp|number_format}</td></tr>
		   {assign var="sl_no" value=$sl_no+1}
		{/foreach}
		{assign var="ave_amt" value=$tot_amt/$count}
		{assign var="ave_orders" value=$total_orders/$count}
		{assign var="ave_asp" value=$asp_amt/$count}

		<input type="hidden" name="tot_ave_amt" id="tot_ave_amt" value={$tot_amt|number_format}>
		<input type="hidden" name="tot_ave_order" id="tot_ave_order" value={$total_orders}>
		<input type="hidden" name="tot_asp_amt" id="tot_asp_amt" value={$tot_amt/$total_orders|number_format}>
		<input type="hidden" name="amt_gsp" id="amt_gsp" value={$ave_amt|number_format}>
		<input type="hidden" name="amt_orders" id="amt_orders" value={$ave_orders|number_format}>
		<input type="hidden" name="asp" id="asp" value={$asp_amt/$count|number_format}>

	</table>
	<script type="text/javascript">
	//<![CDATA[
	{literal}
		document.getElementById('tot_ave_amt_pre').innerHTML = document.getElementById('tot_ave_amt').value;
		document.getElementById('tot_ave_order_pre').innerHTML = document.getElementById('tot_ave_order').value;
		document.getElementById('tot_ave_asp_pre').innerHTML = document.getElementById('tot_asp_amt').value;
		document.getElementById('amt_gsp_pre').innerHTML = document.getElementById('amt_gsp').value;
		document.getElementById('amt_orders_pre').innerHTML = document.getElementById('amt_orders').value;   
		//document.getElementById('asp_pre').innerHTML = document.getElementById('asp').value;
	{/literal}
	//]]>
	</script>

{elseif $report_id == '9'}
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table">
	    <tr>
<th width="10%">{$lang.sl_no}</th>
<th width="25%" align="left"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=cmp">{$lang.states}</a></th>
<th width="15%" style="text-align:right;"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=gsp">{$lang.gsp}</a></th>
<th width="25%" style="text-align:right;"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=qty">{$lang.total_order}</a></th>
<th width="25%" style="text-align:right;"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=asp">{$lang.asp}</a></th>
</tr>
		    <tr>
<th style="font-weight:bold">{$lang.total}</th>
<th></th><th id="tot_ave_amt_pre" style="text-align:right;font-weight:bold"></th><th style="text-align:right;font-weight:bold" id="tot_ave_order_pre"></th><th style="text-align:right;font-weight:bold" id='tot_ave_asp_pre'></th></tr>
		    <tr>
<th style="font-weight:bold">{$lang.average}</th><th></th>
<th align="right" id="amt_gsp_pre" style="text-align:right;font-weight:bold"> </th><th id="amt_orders_pre" style="text-align:right;font-weight:bold"> </th> <th id="asp_pre" style="text-align:right;font-weight:bold"> </th></tr>

		{assign var="count" value=$report.tables|count}
		{assign var="sl_no" value=1}
		{foreach from=$report.tables item="item_report"}
		   {assign var="tot_amt" value=$item_report.tot_amt+$tot_amt}
		   {assign var="total_orders" value=$item_report.tot_order+$total_orders}
		   {assign var="total_asp_amt" value=$item_report.asp+$total_asp_amt}
			<tr><td>{$sl_no}</td><td>{$item_report.s_state}</td><td align="right">{$item_report.tot_amt|number_format}</td><td align="right">{$item_report.tot_order}</td><td align="right">{$item_report.asp|number_format}</td></tr>
		   {assign var="sl_no" value=$sl_no+1}
		{/foreach}

		{assign var="ave_amt" value=$tot_amt/$count}
		{assign var="ave_orders" value=$total_orders/$count}

		<input type="hidden" name="tot_ave_amt" id="tot_ave_amt" value={$tot_amt|number_format}>
		<input type="hidden" name="tot_ave_order" id="tot_ave_order" value={$total_orders}>
		<input type="hidden" name="tot_asp_amt" id="tot_asp_amt" value={$tot_amt/$total_orders|number_format}>
		<input type="hidden" name="amt_gsp" id="amt_gsp" value={$ave_amt|number_format}>
		<input type="hidden" name="amt_orders" id="amt_orders" value={$ave_orders|number_format}>
		<input type="hidden" name="asp" id="asp" value={$total_asp_amt/$count|number_format}>
	</table>
	<script type="text/javascript">
	//<![CDATA[
	{literal}
		document.getElementById('tot_ave_amt_pre').innerHTML = document.getElementById('tot_ave_amt').value;
		document.getElementById('tot_ave_order_pre').innerHTML = document.getElementById('tot_ave_order').value;
		document.getElementById('tot_ave_asp_pre').innerHTML = document.getElementById('tot_asp_amt').value;
		document.getElementById('amt_gsp_pre').innerHTML = document.getElementById('amt_gsp').value;
		document.getElementById('amt_orders_pre').innerHTML = document.getElementById('amt_orders').value;
		//document.getElementById('asp_pre').innerHTML = document.getElementById('asp').value;
	{/literal}
	//]]>
	</script>
{elseif $report_id == '10'}
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table">
	    <tr><th width="10%">{$lang.sl_no}</th><th width="20%"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=city">{$lang.city}</th></a><th width="20%"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=state">{$lang.states}</a></th><th width="10%" style="text-align:right;"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=gsp">{$lang.gsp}</a></th><th width="20%" style="text-align:right;"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=qty">{$lang.total_order}</a></th><th width="20%" style="text-align:right;"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=asp">{$lang.asp}</a></th></tr>
		<tr><th style="font-weight:bold">{$lang.total}</th><th> </th><th id='tot_ave_city_amt_pre'></th><th id='tot_ave_amt_pre' style="text-align:right;font-weight:bold"></th><th id='tot_ave_order_pre' style="text-align:right;font-weight:bold"></th><th id='tot_ave_asp_pre' style="text-align:right;font-weight:bold"></th></tr>
		<tr><th style="font-weight:bold">{$lang.average}</th><th> </th><th id='tot_ave_city_amt_pre'></th><th id='amt_gsp_pre' style="text-align:right;font-weight:bold"> </th><th id='amt_orders_pre' style="text-align:right;font-weight:bold"> </th> <th id='asp_pre' style="text-align:right;font-weight:bold"> </th></tr>
		{assign var="count" value=$report.tables|count}
		{assign var="sl_no" value=1}
		{foreach from=$report.tables item="item_report"}
		   {assign var="tot_amt" value=$item_report.tot_amt+$tot_amt}
		   {assign var="total_orders" value=$item_report.tot_order+$total_orders}
			<tr><td>{$sl_no}</td><td>{$item_report.s_city}</td><td>{$item_report.s_state}</td><td align="right">{$item_report.tot_amt}</td><td align="right">{$item_report.tot_order}</td><td align="right">{$item_report.asp|number_format}</td></tr>
			{assign var="total_asp_amt" value=$item_report.asp+$total_asp_amt}
			{assign var="sl_no" value=$sl_no+1}
		{/foreach}

		{assign var="ave_amt" value=$tot_amt/$count}
		{assign var="ave_orders" value=$total_orders/$count}

		<input type="hidden" name="tot_ave_amt" id="tot_ave_amt" value={$tot_amt|number_format}>
		<input type="hidden" name="tot_ave_order" id="tot_ave_order" value={$total_orders}>
		<input type="hidden" name="tot_asp_amt" id="tot_asp_amt" value={$tot_amt/$total_orders|number_format}>
		<input type="hidden" name="amt_gsp" id="amt_gsp" value={$ave_amt|number_format}>
		<input type="hidden" name="amt_orders" id="amt_orders" value={$ave_orders|number_format}>
		<input type="hidden" name="asp" id="asp" value={$total_asp_amt/$count|number_format}>
	</table>
	<script type="text/javascript">
	//<![CDATA[
	{literal}
		document.getElementById('tot_ave_amt_pre').innerHTML = document.getElementById('tot_ave_amt').value;
		document.getElementById('tot_ave_order_pre').innerHTML = document.getElementById('tot_ave_order').value;
		document.getElementById('tot_ave_asp_pre').innerHTML = document.getElementById('tot_asp_amt').value;
		document.getElementById('amt_gsp_pre').innerHTML = document.getElementById('amt_gsp').value;
		document.getElementById('amt_orders_pre').innerHTML = document.getElementById('amt_orders').value;
		//document.getElementById('asp_pre').innerHTML = document.getElementById('asp').value;
	{/literal}
	//]]>
	</script>
{elseif $report_id == '11'}
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table">
	    <tr><th width="10%">{$lang.sl_no}</th><th width="30%"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=cmp">{$lang.payment_method}</a></th><th width="20%" style="text-align:right;"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=gsp">{$lang.gsp}</a></th><th width="20%" style="text-align:right;"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=qty">{$lang.total_order}</a></th><th width="20%" style="text-align:right;"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=asp">{$lang.asp}</a></th></tr>
		<tr><th style="font-weight:bold">{$lang.total}</th><th></th><th id='tot_ave_amt_pre' style="text-align:right;font-weight:bold"></th><th id='tot_ave_order_pre' style="text-align:right;font-weight:bold"></th><th id='tot_ave_asp_pre' style="text-align:right;font-weight:bold"></th></tr>
		<tr><th style="font-weight:bold">{$lang.average}</th><th></th><th id='amt_gsp_pre' style="text-align:right;font-weight:bold"> </th><th id='amt_orders_pre' style="text-align:right;font-weight:bold"> </th> <th id='asp_pre' style="text-align:right;font-weight:bold"> </th></tr>
		{assign var="count" value=$report.tables|count}
		{assign var="sl_no" value=1}

		{foreach from=$report.tables item="item_report"}
		   {assign var="tot_amt" value=$item_report.tot_amt+$tot_amt}
		   {assign var="total_orders" value=$item_report.tot_order+$total_orders}
		   {assign var="asp_amt" value=$item_report.asp+$asp_amt}
			<tr><td>{$sl_no}</td><td>{$item_report.payment}</td><td align="right">{$item_report.tot_amt|number_format}</td><td align="right">{$item_report.tot_order}</td><td align="right">{$item_report.asp|number_format}</td></tr>
		   {assign var="sl_no" value=$sl_no+1}
		{/foreach}
		{assign var="ave_amt" value=$asp_amt/$count}
		{assign var="ave_orders" value=$total_orders/$count}
		{assign var="ave_asp" value=$total_asp_amt/$count}
		   {assign var="asp_amt" value=$tot_amt/$total_orders}

		<input type="hidden" name="tot_ave_amt" id="tot_ave_amt" value={$tot_amt|number_format}>
		<input type="hidden" name="tot_ave_order" id="tot_ave_order" value={$total_orders}>
		<input type="hidden" name="tot_asp_amt" id="tot_asp_amt" value={$asp_amt|number_format}>
		<input type="hidden" name="amt_gsp" id="amt_gsp" value={$tot_amt/$count|number_format}>
		<input type="hidden" name="amt_orders" id="amt_orders" value={$ave_orders|number_format}>
		<input type="hidden" name="asp" id="asp" value={$ave_amt|number_format}>
	</table>
	<script type="text/javascript">
	//<![CDATA[
	{literal}
		document.getElementById('tot_ave_amt_pre').innerHTML = document.getElementById('tot_ave_amt').value;
		document.getElementById('tot_ave_order_pre').innerHTML = document.getElementById('tot_ave_order').value;
		document.getElementById('tot_ave_asp_pre').innerHTML = document.getElementById('tot_asp_amt').value;
		document.getElementById('amt_gsp_pre').innerHTML = document.getElementById('amt_gsp').value;
		document.getElementById('amt_orders_pre').innerHTML = document.getElementById('amt_orders').value;
		//document.getElementById('asp_pre').innerHTML = document.getElementById('asp').value;
	{/literal}
	//]]>
	</script>
{elseif $report_id == '12'}
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table">
	    <tr><th width="10%">{$lang.sl_no}</th><th width="30%"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=prd">{$lang.product_name}</a></th><th width="20%" style="text-align:right;"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=gsp">{$lang.gsp}</a></th><th width="20%" style="text-align:right;"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=qty">{$lang.total_order}</a></th><th width="20%" style="text-align:right;"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=asp">{$lang.asp}</a></th></tr>
		<tr><th style="font-weight:bold">{$lang.total}</th><th></th><th id='tot_ave_amt_pre' style="text-align:right;font-weight:bold">   </th><th id='tot_ave_order_pre' style="text-align:right;font-weight:bold"></th><th id='tot_ave_asp_pre' style="text-align:right;font-weight:bold"></th></tr>
		<tr><th style="font-weight:bold">{$lang.average}</th><th></th><th id='amt_gsp_pre' style="text-align:right; font-weight:bold"> </th><th id='amt_orders_pre' style="text-align:right;font-weight:bold"> </th> <th id='asp_pre' style="text-align:right;font-weight:bold"> </th></tr>
		{assign var="count" value=$report.tables|count}
		{assign var="sl_no" value=1}

		{foreach from=$report.tables item="item_report"}
		   {assign var="tot_amt" value=$item_report.tot_amt+$tot_amt}
		   {assign var="total_orders" value=$item_report.tot_order+$total_orders}
			<tr><td>{$sl_no}</td><td>{$item_report.product}</td><td align="right">{$item_report.tot_amt}</td><td align="right">{$item_report.tot_order}</td><td align="right">{$item_report.asp|number_format}</td></tr>
		   {assign var="sl_no" value=$sl_no+1}
		{/foreach}
		{assign var="ave_amt" value=$tot_amt/$count}
		{assign var="ave_orders" value=$total_orders/$count}
		{assign var="ave_asp" value=$tot_amt/$count}
		   {assign var="asp_amt" value=$tot_amt/$total_orders}

		<input type="hidden" name="tot_ave_amt" id="tot_ave_amt" value={$tot_amt|number_format}>
		<input type="hidden" name="tot_ave_order" id="tot_ave_order" value={$total_orders}>
		<input type="hidden" name="tot_asp_amt" id="tot_asp_amt" value={$asp_amt|number_format}>
		<input type="hidden" name="amt_gsp" id="amt_gsp" value={$tot_amt/$count|number_format}>
		<input type="hidden" name="amt_orders" id="amt_orders" value={$ave_orders|number_format}>
		<input type="hidden" name="asp" id="asp" value={$ave_asp|number_format}>
	</table>
	<script type="text/javascript">
	//<![CDATA[
	{literal}
		document.getElementById('tot_ave_amt_pre').innerHTML = document.getElementById('tot_ave_amt').value;
		document.getElementById('tot_ave_order_pre').innerHTML = document.getElementById('tot_ave_order').value;
		document.getElementById('tot_ave_asp_pre').innerHTML = document.getElementById('tot_asp_amt').value;
		document.getElementById('amt_gsp_pre').innerHTML = document.getElementById('amt_gsp').value;
		document.getElementById('amt_orders_pre').innerHTML = document.getElementById('amt_orders').value;
		//document.getElementById('asp_pre').innerHTML = document.getElementById('asp').value;
	{/literal}
	//]]>
	</script>
{elseif $report_id == '13'}
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table">
	    <tr><th width="10%">{$lang.sl_no}</th><th width="10%"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=date">{$lang.date}</a></th><th width="20%" style="text-align:right;"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=visitors">{$lang.visitots}</a></th><th width="10%" style="text-align:right;"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=qty">{$lang.total_order}</a></th><th width="20%" style="text-align:right;"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=gmv">{$lang.gsp}</a></th><th width="20%" style="text-align:right;"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=asp">{$lang.asp}</a></th><th width="10%" style="text-align:right;"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=conversion">{$lang.conversion}</a></th></tr>
		<tr>
<th style="font-weight:bold">{$lang.total}</th>
<th></th>
<th id='tot_visitors_pre' style="text-align:right;font-weight:bold"></th>
<th id='tot_order_pre' style="text-align:right;font-weight:bold"></th>
<th id='tot_gmv_pre' style="text-align:right;font-weight:bold"></th>
<th id='tot_asp_pre' style="text-align:right;font-weight:bold"></th>
<th id='tot_conversion_pre' style="text-align:right;font-weight:bold"></th>
		</tr>
		<tr>
<th style="font-weight:bold">{$lang.average}</th>
<th></th>
<th id='ave_visitors_pre' style="text-align:right; font-weight:bold"> </th>
<th id='ave_orders_pre' style="text-align:right;font-weight:bold"> </th>
<th id='ave_gmv_pre' style="text-align:right; font-weight:bold"> </th>
<th id='ave_asp_pre' style="text-align:right;font-weight:bold"> </th>
<th id='ave_conversion_pre' style="text-align:right; font-weight:bold"> </th>
		</tr>
		{assign var="count" value=$report.tables|count}
		{assign var="sl_no" value=1}

		{foreach from=$report.tables item="item_report"}
			{assign var="tot_visitors" value=$item_report.visitors+$tot_visitors}
			{assign var="tot_orders" value=$item_report.product_count+$tot_orders}
			{assign var="tot_gmv" value=$item_report.GMV+$tot_gmv}
			{assign var="tot_asp" value=$item_report.ASP+$tot_asp}
			{assign var="tot_conversion" value=$item_report.conversion+$tot_conversion}

			<tr><td>{$sl_no}</td><td>{$item_report.date}</td><td align="right">{$item_report.visitors}</td><td align="right">{$item_report.product_count}</td><td align="right">{$item_report.GMV|number_format}</td><td align="right">{$item_report.ASP|number_format}</td><td align="right">{$item_report.conversion|number_format:2}</td></tr>
		{/foreach}

		<input type="hidden" name="tot_visitors" id="tot_visitors" value={$tot_visitors|number_format}>
		<input type="hidden" name="tot_orders" id="tot_orders" value={$tot_orders|number_format}>
		<input type="hidden" name="tot_gmv" id="tot_gmv" value={$tot_gmv|number_format}>
		<input type="hidden" name="tot_asp" id="tot_asp" value={$tot_asp|number_format}>
		<input type="hidden" name="tot_conversion" id="tot_conversion" value={$tot_conversion|number_format:2}>

		<input type="hidden" name="ave_visitors" id="ave_visitors" value={$tot_visitors/$count|number_format}>
		<input type="hidden" name="ave_orders" id="ave_orders" value={$tot_orders/$count|number_format}>
		<input type="hidden" name="ave_gmv" id="ave_gmv" value={$tot_gmv/$count|number_format}>
		<input type="hidden" name="ave_asp" id="ave_asp" value={$tot_asp/$count|number_format}>
		<input type="hidden" name="ave_conversion" id="ave_conversion" value={$tot_conversion/$count|number_format:2}>

	</table>
	<script type="text/javascript">
	//<![CDATA[
	{literal}
		document.getElementById('tot_visitors_pre').innerHTML = document.getElementById('tot_visitors').value;
		document.getElementById('tot_order_pre').innerHTML = document.getElementById('tot_orders').value;
		document.getElementById('tot_gmv_pre').innerHTML = document.getElementById('tot_gmv').value;
		document.getElementById('tot_asp_pre').innerHTML = document.getElementById('tot_asp').value;
		document.getElementById('tot_conversion_pre').innerHTML = document.getElementById('tot_conversion').value;

		document.getElementById('ave_visitors_pre').innerHTML = document.getElementById('ave_visitors').value;
		document.getElementById('ave_orders_pre').innerHTML = document.getElementById('ave_orders').value;
		document.getElementById('ave_gmv_pre').innerHTML = document.getElementById('ave_gmv').value;
		document.getElementById('ave_asp_pre').innerHTML = document.getElementById('ave_asp').value;
		document.getElementById('ave_conversion_pre').innerHTML = document.getElementById('ave_conversion').value;
	{/literal}
	//]]>
	</script>
{elseif $report_id == '14'}
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table">
	    <tr><th width="10%">{$lang.sl_no}</th><th width="20%">{$lang.order_id}</th><th width="20%">{$lang.description}</th><th width="10%">{$lang.flag}</th><th width="20%">{$lang.previousflag}</th><th width="20%">{$lang.CCAVENUE}</th></tr>
		<tr>

		{assign var="count" value=$report.tables|count}
		{assign var="sl_no" value=1}

		{foreach from=$report.tables item="item_report"}
			<tr><td>{$sl_no}</td><td>{$item_report.order_id}</td><td>{$item_report.description}</td><td>{$item_report.flag}</td><td>{$item_report.previousflag}</td><td>{$item_report.CCAVENUE}</td></tr>
		{assign var="sl_no" value=$sl_no+1}
		{/foreach}
	</table>


{elseif $report_id == '15'}

	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table">
	    <tr><th width="10%">{$lang.sl_no}</th><th width="30%"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=name">{$lang.promotion_name}</a></th><th width="20%" style="text-align:right;"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=qty">{$lang.total_order}</a></th><th width="20%" style="text-align:right;"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=gmv">{$lang.gsp}</a></th><th width="20%" style="text-align:right;"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=asp">{$lang.asp}</a></th></tr>
		<tr>
<th style="font-weight:bold">{$lang.total}</th>
<th></th>
<th id='tot_order_pre' style="text-align:right;font-weight:bold"></th>
<th id='tot_gmv_pre' style="text-align:right;font-weight:bold"></th>
<th id='tot_asp_pre' style="text-align:right;font-weight:bold"></th>
		</tr>
		<tr>
<th style="font-weight:bold">{$lang.average}</th>
<th></th>
<th id='ave_orders_pre' style="text-align:right; font-weight:bold"> </th>
<th id='ave_gmv_pre' style="text-align:right;font-weight:bold"> </th>
<th id='ave_asp_pre' style="text-align:right; font-weight:bold"> </th>
		</tr>
		{assign var="count" value=$report.tables|count}
		{assign var="sl_no" value=1}

		{foreach from=$report.tables item="item_report"}
			{assign var="tot_orders" value=$item_report.tot_orders+$tot_orders}
			{assign var="tot_gmv" value=$item_report.tot_amount+$tot_gmv}
			{assign var="tot_asp" value=$item_report.ASP+$tot_asp}

			<tr><td>{$sl_no}</td><td>{$item_report.name}{if $item_report.internal_name}({$item_report.internal_name}){/if}</td><td align="right">{$item_report.tot_orders}</td><td align="right">{$item_report.tot_amount}</td><td align="right">{$item_report.ASP|number_format:2}</td></tr>
		{assign var="sl_no" value=$sl_no+1}
		{/foreach}

		<input type="hidden" name="tot_orders" id="tot_orders" value={$tot_orders|number_format}>
		<input type="hidden" name="tot_gmv" id="tot_gmv" value={$tot_gmv|number_format}>
		<input type="hidden" name="tot_asp" id="tot_asp" value={$tot_asp|number_format:2}>

		<input type="hidden" name="ave_orders" id="ave_orders" value={$tot_orders/$count|number_format}>
		<input type="hidden" name="ave_gmv" id="ave_gmv" value={$tot_gmv/$count|number_format}>


	</table>
	<script type="text/javascript">
	//<![CDATA[
	{literal}
		document.getElementById('tot_order_pre').innerHTML = document.getElementById('tot_orders').value;
		document.getElementById('tot_gmv_pre').innerHTML = document.getElementById('tot_gmv').value;
		document.getElementById('tot_asp_pre').innerHTML = document.getElementById('tot_asp').value;

		document.getElementById('ave_orders_pre').innerHTML = document.getElementById('ave_orders').value;
		document.getElementById('ave_gmv_pre').innerHTML = document.getElementById('ave_gmv').value;

	{/literal}
	//]]>
	</script>
        {elseif $report_id=='17'}
        
    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="table">
    <tr><th width="20%">{$lang.sl_no}</th>
        <th width="20%"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=orderid">{$lang.order_id}</a></th>
        <th width="20%"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=unitsold">{$lang.unitsold}</a></th>
        <th width="20%">{$lang.name}</th>
        <th width="20%"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=subtotal">{$lang.subtotal}</a></th>
        <th width="20%"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=amount">{$lang.amount}</a></th>
        {*<th width="20%">{$lang.total}</th>*}
        <th width="20%">{$lang.ASP}</th>
        
        <tr><th style="font-weight:bold">{$lang.total}</th><th></th>
            <th id="tot_unit_sold" style="text-align:left;font-weight:bold"></th><th></th>{*total unit sold included clues_buck*}
            <th id='total_subtotal_value_without_clues' style="text-align:left;font-weight:bold" ></th>
            <th id='' style="text-align:left;font-weight:bold"></th>
            <th id='total_average_selling_price' style="text-align:right;font-weight:bold"></th>
            <th id="" style="text-align:right;font-weight:bold"></th>
        </tr>
        <tr><th style="font-weight:bold">{$lang.clues_bucks_used}</th><th></th>
            <th id="total_unit_sold_clues" style="text-align:left;font-weight:bold"></th><th></th>
            <th id='subtotal_amt_pre' style="text-align:left;font-weight:bold" ></th>
            <th id='clues_bucks_value' style="text-align:left;font-weight:bold"></th>
           {* <th id='' style="text-align:right;font-weight:bold"></th> *}
            <th id='asp_value' style="text-align:right;font-weight:bold"></th>
        </tr>
        
		{assign var="count" value=$report.tables|count}
		{assign var="sl_no" value=1}
              
         {foreach from=$report.tab item="item_total"}
             {assign var="tot_quant_sold" value=$item_total.quantity+$tot_quant_sold}
             {assign var="total_subtotal_value" value=$item_total.subtotal+$total_subtotal_value}
                 
         {/foreach}
           
         {foreach from=$report.tables item="item_report"} 
                {assign var="act_tot_val" value=$item_report.subtotal+$act_tot_val}
                {assign var="clues_buck" value=$item_report.amount+$clues_buck}
		{assign var="tot_count" value=$item_report.total_count+$tot_count}
                {assign var="total" value=$item_report.subtotal+$item_report.amount}
                {assign var="asp_value" value=$item_report.subtotal/$item_report.quantity} 
                {assign var="quantity" value=$item_report.quantity+$quantity} {*counts total unit sold*}
                
                {*{assign var="tot_al_quantity" value=$aspvalue + $tot_al_quantity} *}
                 
                {*{assign var="unit_sold" value=$item_report.quantity + $unit_sold }*}
               
		<tr><td>{$sl_no}</td><td><a href="UniTechCity.php?dispatch=orders.details&order_id={$item_report.order_id}">{$item_report.order_id}</a></td>
                    <td>{$item_report.quantity}</td>
                    <td>{$item_report.firstname} {$item_report.lastname}</td>
                    <td align="left">{$item_report.subtotal}</td>
                    <td align="left">{$item_report.amount}</td>
                   {* <td align="left">{$total}</td> *}
                    <td align="left">{$asp_value|number_format:2}</td></tr>
		 {assign var="sl_no" value=$sl_no+1}
	{/foreach}
               {assign var="actual_asp" value=$act_tot_val/$quantity}
               {assign var="actual_total_asp_val" value=$total_subtotal_value/$tot_quant_sold}{*asp value calculated for overall sale*}
               {* {assign var="actual_asp_val" value= $act_tot_val/$tot_al_quantity} *}
                {assign var="tot_aft_clues" value=$act_tot_val+$clues_buck}
                
    
    </table>
                <input type="hidden" name="tot_orders" id="tot_orders" value={$act_tot_val|number_format}>
                <input type="hidden" name="clues_bucks" id="clues_bucks" value={$clues_buck|number_format}>
                <input type="hidden" name="tot_aft_clues" id="tot_aft_clues" value={$tot_aft_clues|number_format}>
                <input type="hidden" name="total_unitsold" id="total_unitsold" value={$quantity}>
                <input type="hidden" name="asp" id="asp1" value={$actual_asp|number_format:2}>
                <input type="hidden" name="total_sold_value" id="total_sold_value" value={$total_subtotal_value|number_format}> 
                <input type="hidden" name="total_unit_sold_without_clues" id="total_unit_sold_without_clues" value={$tot_quant_sold}>
                <input type="hidden" name="total_asp_price" id="total_asp_price" value={$actual_total_asp_val|number_format:2}>
                
    <script type="text/javascript">
	//<![CDATA[
	{literal} 
		document.getElementById('subtotal_amt_pre').innerHTML = document.getElementById('tot_orders').value;
                document.getElementById('clues_bucks_value').innerHTML = document.getElementById('clues_bucks').value;
                document.getElementById('asp_value').innerHTML = document.getElementById('asp1').value;
		document.getElementById('total_unit_sold_clues').innerHTML = document.getElementById('total_unitsold').value;
                document.getElementById('total_subtotal_value_without_clues').innerHTML = document.getElementById('total_sold_value').value;
                document.getElementById('tot_unit_sold').innerHTML = document.getElementById('total_unit_sold_without_clues').value;
                document.getElementById('total_average_selling_price').innerHTML = document.getElementById('total_asp_price').value;
	{/literal}
	//]]>
	</script>
{elseif $report_id == '16'}

	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table">
	    <tr><th width="20%">{$lang.sl_no}</th><th width="20%">{$lang.date}</th><th width="30%"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=name">{$lang.merchant_details}</a></th><th width="30%" style="text-align:right;"><a href="UniTechCity.php?dispatch=sales_reports.reports&report_id={$report_id}&order={$order}&field=count">{$lang.total_count}</a></th></tr>

		{assign var="count" value=$report.tables|count}
		{assign var="sl_no" value=1}

		{foreach from=$report.tables item="item_report"}
			{assign var="tot_count" value=$item_report.total_count+$tot_count}
			<tr><td>{$sl_no}</td><td>{$item_report.created}</td><td>{$item_report.merchant_details}</td><td align="right">{$item_report.total_count}</td></tr>
		{assign var="sl_no" value=$sl_no+1}
		{/foreach}

	</table>
{/if}
