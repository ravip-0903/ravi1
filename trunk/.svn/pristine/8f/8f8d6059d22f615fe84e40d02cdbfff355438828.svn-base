{* $Id: export_order_list.tpl 12544 2011-05-27 10:34:19Z bimib $ *}
{script src="lib/amcharts/swfobject.js"}
{literal}
<style type="text/css">
.sortable th{ padding: 0px 5px;}
.sortable td, .sortable tr:hover td{ border-right: 1px solid #e4e4e4;}
.form-field label { margin-left:0px;clear:none;float:none;}
</style>
<script type="text/javascript" language="javascript">
$(document).ready(function(){
	$('#warehouse_region').change(function(){
			//alert(this.value);
			var xhr = $.ajax({
				type: "POST",
				url: "?dispatch=milkrun_create.milkrun_generate",
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
	
	$('#get_orders').click(function(){
		var selector_checked = $("input[@name=company_id]:checked").length;							
		if (selector_checked == 0) 
		{ 
			alert('No items selected! At least one check box must be selected to perform this action.');
			return false; 
		}
	});
	
	$('#select_all').click(function(){
		$('input[@name=company_id]:checkbox').attr('checked', true);
	});
	
	$('#unselect_all').click(function(){
		$('input[@name=company_id]:checkbox').attr('checked', false);
	});
	
});
</script>
{/literal}

{assign var="milkrun_initiation_system" value="Milkrun Initiation System"}
{assign var="get_orders" value="Get Orders"}

{capture name="mainbox"}
<form method="post" action="?dispatch=milkrun_create.milkrun_generate" name="milkrun_generate_form" 
id="milkrun_generate_form">
	<div class="form-field">
		<label for="upload_csv_file">Select Region:</label>
		<select name="warehouse_region" id="warehouse_region">
			<option value="">--Select--</option>
			{foreach from=$region_list item="region" key="lang_code"}
			<option value="{$region.region_id}" 
				{if $region.region_id == $warehouse_data[0].region_code}selected="selected"{/if}>
				{$region.region_name}
			</option>
			{foreachelse}
			<option value="">><p>{$lang.no_data}</p></option>
			{/foreach}
		</select>
	</div>
	<div class="form-field" id="warehouse_data" style="display:none"></div>
	<br clear="all" /><br />
	<div style="margin-left:190px;display:none" id="warehouse_details">
		<input type="hidden" name="process_type" value="get_orders" />
		<a class="cm-check-items cm-on underlined" id="select_all">Select All</a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a class="cm-check-items cm-on underlined" id="unselect_all">Unselect All</a>
		<br clear="all" /><br />
		<div class="float-left">
			{include file="buttons/button.tpl" but_text=$get_orders 
			but_id="get_orders" but_name="get_orders" but_role="button_main"}
		</div>
		<!--<span class="submit-button cm-button-main cm-process-items">
		<input type="submit" value="Get Orders" class="cm-process-items" id="get_orders" />
		</span>-->
	</div>
</form>
{/capture}
{include file="common_templates/mainbox.tpl" title=$milkrun_initiation_system content=$smarty.capture.mainbox title_extra=$smarty.capture.title_extra tools=$smarty.capture.tools}