{* $Id: export_order_list.tpl 12544 2011-05-27 10:34:19Z bimib $ *}
{script src="lib/amcharts/swfobject.js"}

<h1>Shipment Creation System</h1>
<p><a href="?dispatch=create_shipment.new">1. Download Order Data</a> | <strong>2. Upload AWB Details</strong></p><br />
 
<form method="post" action="{""|fn_url}" name="create_shipment" id="create_shipment" enctype="multipart/form-data">
<div class="form-field">
<label for="csvfile">Upload Shipment Data<br /> (CSV File Only):</label>
<input type="file" name="csvfile" id="csvfile"  />
</div>

<input type="hidden" name="mode_action" value="import" /><br clear="all" /><br />
<span class="submit-button cm-button-main cm-process-items">
<input type="submit" value="Continue" name="dispatch[create_shipment.upload]" class="cm-process-items" /></span>
</form>
