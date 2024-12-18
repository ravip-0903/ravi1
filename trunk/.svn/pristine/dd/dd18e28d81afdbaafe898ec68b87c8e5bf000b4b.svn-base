{* $Id: milkrun_initiate_list.tpl 12544 2011-05-27 10:34:19Z bimib $ *}
{script src="lib/amcharts/swfobject.js"}

<h1 class="mainbox-title">Milkrun Completion System</h1>
<p><strong>Step1: Scan Or Upload Order Numbers</strong></p>
 
<form method="post" action="{""|fn_url}" name="milkrun_completed_form" id="milkrun_completed_form" enctype="multipart/form-data">
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
<input type="submit" value="Continue" name="dispatch[milkrun_completed.milkrun_initiate_list]" class="cm-process-items" /></span>
</form>

<!--<span class="submit-button cm-button-main cm-process-items">
<input type="button" value="Cancel" onclick="javascript:history.go(-1);" class="cm-process-items" /></span> -->
