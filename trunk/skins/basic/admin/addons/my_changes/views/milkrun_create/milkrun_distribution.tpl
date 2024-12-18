{* $Id: export_order_list.tpl 12544 2011-05-27 10:34:19Z bimib $ *}
{script src="lib/amcharts/swfobject.js"}
{literal}
<style type="text/css">
.sortable th{ padding: 0px 5px;}
.sortable td, .sortable tr:hover td{ border-right: 1px solid #e4e4e4;}
</style>
<script type="text/javascript" language="javascript">
$(document).ready(function()
{
	$('#warehouse_region').change(function(){
		var region_id = $(this).val();
		window.location.href = "?dispatch=milkrun_create.milkrun_distribution&region_id="+region_id;
	});
	
	$('#pdf_all').click(function(){
		if($('input#pdf_all').attr('checked') == true)
		{
			$('input.pdf_check').attr('checked', true);
		}else{
			$('input.pdf_check').attr('checked', false);
		}
	});
	
	$('input.pdf_check').click(function(){
		$('#pdf_all').attr('checked', false);
	});
	
	$('#csv_all').click(function(){
		if($('input#csv_all').attr('checked') == true)
		{
			$('input.csv_check').attr('checked', true);
		}else{
			$('input.csv_check').attr('checked', false);
		}
	});
	
	$('input.csv_check').click(function(){
		$('#csv_all').attr('checked', false);
	});
	
});

function validateFields(thisValue)
{	
	var file_selector = $("input[@name=company]:checked").length;
	
	if (parseInt(file_selector) == 0) 
	{ 
		alert('No items selected! At least one check box must be selected to perform this action.');
		return false; 
	}
	else
	{	
		$('#report_type').val(thisValue);//return false;
		$('#milkrun_distribution_form').submit();
		return true; 
	}
}
</script>
{/literal}

{assign var="milkrun_distribution_system" value="Milkrun Distribution System"}
{assign var="download" value="Download"}
{assign var="email_reports" value="Email Reports"}

{capture name="mainbox"}
<form method="post" id="milkrun_distribution_form" action="?dispatch=milkrun_create.milkrun_distribution">
<div class="form-field">
<label for="upload_csv_file">Select MilkRun Region:</label>
<select name="warehouse_region" id="warehouse_region">
    <option value="">--Select--</option>
    {foreach from=$region_list item="region" key="lang_code"}
    <option value="{$region.region_id}" 
        {if $region.region_id == $smarty.session.region_id}selected="selected"{/if}>
        {$region.region_name}
    </option>
    {/foreach}
</select>
</div>
<!--<div id="milkrun_distribution_details">
</div>-->
<div class="form-field">
<label for="cc_address">CC Address:</label> <input type="text" name="cc_email" value="" />
</div>

<table border="0" cellpadding="0" cellspacing="0" width="100%" class="table sortable">
<tr>
	<th width="25%">Merchant Name</th>
	<th width="5%">No. Of Orders</th>
	<th width="5%" align="center">PDF<br /><input type="checkbox" name="pdf_all" id="pdf_all" /></th>
	<th width="5%" align="center">CSV<br /><input type="checkbox" name="csv_all" id="csv_all" /></th>
	<th width="30%">Email Body</th>
	<th width="30%">Email Address</th>
</tr>
{foreach from=$merchant_details item="value" key="key"}
<tr {cycle values="class=\"table-row\", "}>
	<td valign="top">{$value.company_name}<br />{if $value.warehouse_address1 != '' || $value.warehouse_address2 != ''}{$value.warehouse_address1} {$value.warehouse_address2}<br />{$value.warehouse_city},{$value.warehouse_state} - {$value.warehouse_pin}{/if}<br />{if $value.warehouse_pcontact_phone}Contact: {$value.warehouse_pcontact_phone}{/if}</td>
	<td>{$value.ordercount}</td>
	<td>
    	{if $value.ordercount <= 150}
    	<input class="inputBox" type="checkbox" name="company[{$value.company_id}][pdf]" />
    	{else}
        *N/A
        {/if}
    </td>
	<td><input class="csv_check" type="checkbox" name="company[{$value.company_id}][csv]" /></td>
	<td><textarea class="inputBox" cols="30" rows="4"
	name="company[{$value.company_id}][content]">{$email_content}</textarea>
	</td>
	<td>
	<textarea class="inputBox"  cols="30" rows="4"
	name="company[{$value.company_id}][email]">{if $value.warehouse_pcontact_email ne '' && $value.warehouse_scontact_email ne ''}{$value.warehouse_pcontact_email}, {$value.warehouse_scontact_email}{elseif $value.warehouse_pcontact_email ne ''}{$value.warehouse_pcontact_email}{elseif $value.warehouse_scontact_email ne ''}{$value.warehouse_scontact_email}{else}{/if}</textarea>
	</td>
</tr>
{foreachelse}
<tr>
	<td colspan="16"><p>{$lang.no_data}</p></td>
</tr>
{/foreach}
</table>
<div style="color:red;font-size:15px">*N/A- PDF over 150 orders not allowed.</div> 
{if $merchant_details}
<input type="hidden" name="process_type" value="generate_report" />
<input type="hidden" name="report_type" id="report_type" value="" />
<div class="buttons-container cm-toggle-button buttons-bg">
	<div class="float-left">
		<span class="submit-button cm-button-main">
		<input type="button" value="Download" name="downloadreports" 
		id="download" onclick="return  validateFields(this.value);" />
		</span>
		<span class="submit-button cm-button-main">
		<input type="button" value="Email Reports" name="emailreports" 
		id="email_reports" onclick="return  validateFields(this.value);" />
		</span>
		&nbsp;or&nbsp;&nbsp;
		<a class="underlined tool-link" href="?dispatch=milkrun_create.milkrun_distribution">Cancel</a>
	</div>
</div>
<!--<div class="buttons-container cm-toggle-button buttons-bg">
	<div class="float-left"> 
		<span class="submit-button cm-button-main">
			<input type="submit" value="Download" name="downloadreports" id="download" 
			onclick="return  validateFields(this.value);">
		</span>&nbsp;<span class="submit-button cm-button-main">
			<input type="submit" value="Email Reports" name="emailreports" id="email_reports" 
			onclick="return  validateFields(this.value);">
		</span>&nbsp;or&nbsp;&nbsp;
		<a class="underlined tool-link" href="?dispatch=milkrun_create.milkrun_distribution">Cancel</a>
	</div>
</div>-->
{/if}

</form>
{/capture}
{include file="common_templates/mainbox.tpl" title=$milkrun_distribution_system content=$smarty.capture.mainbox title_extra=$smarty.capture.title_extra tools=$smarty.capture.tools}
