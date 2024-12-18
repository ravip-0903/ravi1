{script src="lib/amcharts/swfobject.js"}
<h1 class="mainbox-title">BULK ORDER STATUS CHANGE SYSTEM</h1>
<h2>
	{$smarty.request.from|fn_hp_status_description:"O"}
 => {$smarty.request.to|fn_hp_status_description:"O"}</h2>
 {assign var="fromarr" value=","|explode:$smarty.request.from}
From <select name="from[]" id="status_from" multiple="multiple">
    {foreach from=$statusarray item="status"}
    <option value="{$status.status}" {if $status.status|in_array:$fromarr}selected="selected"{/if}>{$status.description}</option>
    {/foreach}
</select>
To <select name="to" id="status_to">
    {foreach from=$statusarray item="status"}
    <option value="{$status.status}" {if $smarty.request.to == $status.status}selected="selected"{/if}>{$status.description}</option>
    {/foreach}
</select>
<input type="button" value="get" onclick="redirectshipmentintiallist()" />
{literal}
<script type="text/javascript">
function redirectshipmentintiallist()
{
	var from = '';
	ele = document.getElementById('status_from');
	for(i=0;i<ele.options.length;i++)
	{
	 if (ele.options[i].selected)
		{
			if(from == '')
			{
				from = ele.options[i].value;
			}
			else
			{
				from = from + ',' + ele.options[i].value;
			}
		}
	}
	window.location = "UniTechCity.php?dispatch=shipment_completed.shipment_initiate_list&from="+from+"&to=" + document.getElementById('status_to').value;
}
</script>
{/literal}
{if $smarty.request.from && $smarty.request.to}
<p><strong>Step1: Scan Or Upload Order Numbers</strong></p>

<form method="post" action="{""|fn_url}?from={$smarty.request.from}&to={$smarty.request.to}" name="milkrun_completed_form" id="milkrun_completed_form" enctype="multipart/form-data">
<div class="form-field">
<label for="upload_csv_file">Upload File with Order Numbers<br /> (CSV File Only):</label><input type="file" name="file" id="upload_csv_file"  />
</div>
<div class="form-field">
OR
</div>
<div class="form-field">
<label></label>
Enter order numbers in the text area below. <br />Please enter one order number per line.<br /><br />
<textarea name="text_area" id="upload_csv_file" rows="10"></textarea>
</div>
<input type="hidden" name="mode_action" value="import" /><br clear="all" /><br />
<span class="submit-button cm-button-main cm-process-items">
<input type="submit" value="Continue" name="dispatch[shipment_completed.shipment_initiate_list]" class="cm-process-items" /></span>
</form>

<!--<span class="submit-button cm-button-main cm-process-items">
<input type="button" value="Cancel" onclick="javascript:history.go(-1);" class="cm-process-items" /></span> -->
{/if}