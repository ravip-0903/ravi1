<div class="box_header" style="width:810px;"><h1 class="box_heading">{$lang.upload_addressbook}</h1></div>
<br />
 
<form method="post" action="{""|fn_url}" name="addressbook" id="addressbook" enctype="multipart/form-data">
<div class="form-field upload_excel_box" style="float: left; clear: both; margin:10px 0 0; width: 810px;" id="resPrg">

<div style="float:left;" >
<div style="font:18px/22px 'Trebuchet MS', Arial, Helvetica, sans-serif; color:#007AC0; float:left; font-weight:bold; padding:0 0 3px">{$lang.step1_download_excel_file}</div>
<div style="clear:both"></div>
<a href="images/excel_addressbook/addressbook_sample.xls" style="margin:2px 0 0 0px; color:#000;">{$lang.download_excel_sample}</a></div>

<div style="clear:both"></div>
<div style="font:18px/22px 'Trebuchet MS', Arial, Helvetica, sans-serif; color:#007AC0; float:left; margin:30px 0 10px ; font-weight:bold">{$lang.step2_upload_excel_file}</div><br />
<div style="clear:both;"></div>
<input type="file" name="csvfile" class="input_box_add_pj2" id="csvfile" />
<label for="csvfile" class="label_box_add_pj2">Upload Address Book Data (xls File Only):</label>
</div>
	<div class="hidden" id="responePrg">
		<p>{$lang.address_import_inprogress}</p>
		<img src="images/progress-bar.gif">
	</div>

<input type="hidden" name="mode_action" value="import" /><br clear="all" /><br />
<span class="submit-button cm-button-main cm-process-items">
<input type="submit" value="Continue" name="dispatch[profiles.upload_excel]" onclick="return responseProcess();" class="cm-process-items pj2_add_book_btn" /></span>
</form>
{literal}
<script type="text/javascript">
function responseProcess(){
	$('#resPrg').hide();
	$('#responePrg').show();
}
</script>
{/literal}
