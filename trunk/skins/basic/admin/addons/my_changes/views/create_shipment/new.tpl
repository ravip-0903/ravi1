{* $Id: export_order_list.tpl 12544 2011-05-27 10:34:19Z bimib $ *}
{script src="lib/amcharts/swfobject.js"}

<h1>Shipment Creation System</h1>
<p><strong>1. Download Order Data</strong> | <a href="?dispatch=create_shipment.upload">2. Upload AWB Details</a></p><br />
 
<form method="post" action="{""|fn_url}" name="create_shipment" id="create_shipment" enctype="multipart/form-data">
<div class="form-field">
<label for="csvfile">Upload File with Order Numbers<br /> (CSV File Only):</label>
<input type="file" name="csvfile" id="csvfile"  />
</div>
<div class="form-field">
OR
</div>
<div class="form-field">
<label></label>
Enter order numbers in the text area below. <br />Please enter one order number per line.<br /><br />
<textarea name="ordernumberlist" id="ordernumberlist" rows="10"></textarea>
</div>

<input type="hidden" name="mode_action" value="import" /><br clear="all" /><br />
<span class="submit-button cm-button-main cm-process-items">
<input type="submit" value="Continue" name="dispatch[create_shipment.new]" class="cm-process-items" /></span>
</form>
