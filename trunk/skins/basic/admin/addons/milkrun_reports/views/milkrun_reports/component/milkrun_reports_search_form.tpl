{* $Id: sales_reports_search_form.tpl 9517 2010-05-19 14:02:43Z klerik $ *}

{capture name="section"}
{literal}
<style type="text/css">
.sortable th{ padding: 0px 5px;}
.sortable td, .sortable tr:hover td{ border-right: 1px solid #e4e4e4;}
.form-field label { margin-left:0px;clear:none;float:none;}
label { color: #555; font-size: 11px;}
.region_section{ float: left; margin:0 0 0 10px;}
.warehouse_region{ display:none; float:left; margin: 0 0 0 10px;}
</style>
<script type="text/javascript" language="javascript">
$(document).ready(function(){
	$('#warehouse_region').change(function(){
			//alert(this.value);
			var xhr = $.ajax({
				type: "POST",
				url: "?dispatch=milkrun_reports.reports",
				data: "&region="+this.value+"&rand="+Math.random(),
				success: function(msg){
					if(msg == 0)
					{
						$('#warehouse_data').html('No companies found');
						$('#warehouse_data').show();
						$('#warehouse_details').hide();
					}
					else
					{
						$('#warehouse_data').html(msg);
						$('#warehouse_data').show();
						$('#warehouse_details').show();
				   }
    			}
			});
	});
	
});
</script>
{/literal}
<!--<form method="post" action="?dispatch=milkrun_create.milkrun_generate" name="milkrun_generate_form" 
id="milkrun_generate_form">

</form> -->


<form action="{""|fn_url}" method="GET" name="milkrun_report_full">

{include file="common_templates/period_selector.tpl" period=$period form_name="orders_search_form" display="form" but_name="dispatch[milkrun_reports.reports]"}


{if $report_type == 'full'}
<p><label>Order By</label>
<select name="order_by">
	<option value="state">State</option>
</select>
{elseif $report_type == 'merchant'}
<div class="region_section">
<label for="upload_csv_file">Select Region:</label>
<select name="warehouse_region" id="warehouse_region">
    <option value="">-- Select --</option>
    {foreach from=$region_list item="region" key="lang_code"}
    <option value="{$region.region_id}" 
        {if $region.region_id == $warehouse_data[0].region_code}selected="selected"{/if}>
        {$region.region_name}
    </option>
    {/foreach}
</select>
</div>
<div id="warehouse_data" class="warehouse_region">
    
</div>
<br clear="all" /><br />
{if $smarty.const.PRODUCT_TYPE == "MULTIVENDOR"}
            {*include file="common_templates/select_supplier_vendor.tpl"*}
        {/if}
{/if}
<input type="hidden" name="report_type" value="{$report_type}" />
</p>
</form>

{/capture}
{include file="common_templates/section.tpl" section_content=$smarty.capture.section}
