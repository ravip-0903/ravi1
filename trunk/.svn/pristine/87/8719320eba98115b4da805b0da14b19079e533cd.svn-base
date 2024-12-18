{* $Id: export_order_list.tpl 12544 2011-05-27 10:34:19Z bimib $ *}

{literal}
<style type="text/css">
.sortable th{ padding: 0px 5px;}
.sortable td, .sortable tr:hover td{ border-right: 1px solid #e4e4e4;}
</style>
<script type="text/javascript" language="javascript">
$(document).ready(function()
{
	$('input[@name=company_id]:checkbox').attr('checked', true);
	$('#check_all').attr('checked', true);
	
	$('#check_all').click(function()
	{
		var selector_checked = $("#check_all").attr('checked');		
							
		if (selector_checked == false) 
		{ 
			$('input[@name=company_id]:checkbox').attr('checked', false);
		}
		else
		{
			$('input[@name=company_id]:checkbox').attr('checked', true);
		}
	});
	
	
	$('#milkrun_initiate').click(function()
	{
		var selector_checked = $("input[@name=milkrun_initiate_orderid]:checked").length;	
								
		if (selector_checked == 0) 
		{ 
			alert('No items selected! At least one check box must be selected to perform this action.');
			return false; 
		}
	});
});
</script>
{/literal}

{assign var="milkrun_initiation_review_page" value="MilkRun Initiation Review Page"}
{assign var="initiate_milkrun" value="Initiate Milkrun"}
{capture name="mainbox"}
<form method="post" action="?dispatch=milkrun_create.milkrun_list" name="milkrun_list_form" id="milkrun_list_form">

<table border="0" cellpadding="0" cellspacing="0" width="100%" class="table sortable">
  <tr>
    <th width="2%">
		<input type="checkbox" id="check_all" name="check_all" title="{$lang.check_uncheck_all}" class="checkbox cm-check-items" />
	</th>
    <th width="5%">Order No.</th>
    <th width="8%">Merchant Name</th>
    <th width="5%">Payment Mode</th>
    <th width="5%">Buyer Name</th>
    <th width="10%">Shipping Address1</th>
    <th width="10%">Shipping Address2</th>
    <th width="5%">Shipping City</th>
    <th width="5%">Shipping State</th>
    <th width="8%">Shipping Pincode</th>
    <th width="10%">Buyer Phone No.</th>
    <th width="5%">Order SubTotal</th>
    <th width="5%">Collectible Amount</th>
    <th width="5%">Shipment Weight</th>
    <th width="8%">Product Details</th>
    <th width="8%">Courier Name</th>
  </tr>
  {foreach from=$order_details item="value" key="key"}
  <tr {cycle values="class=\"table-row\", "}>
    <td class="center"><input type="checkbox" name="milkrun_initiate_orderid[{$value.order_id}]" /></td>
    <td>{$value.order_id}</td>
    <td>{$value.merchant_detail}</td>
    <td> {if $value.payment_method.payment == '' && !isset($value.use_gift_certificates)}
      CluesBucks
      {elseif isset($value.use_gift_certificates)}
      Gift Certificate
      {else}
      {$value.payment_method.payment}
      {/if} </td>
    <td>{$value.b_firstname} {$value.b_lastname}</td>
    <td>{$value.s_address}</td>
    <td>{$value.s_address_2}</td>
    <td>{$value.s_city}</td>
    <td>{$value.s_state}</td>
    <td>{$value.s_zipcode}</td>
    <td>{$value.b_phone}</td>
    <td>{$value.subtotal|number_format:2:".":","}</td>
    <td>{$value.total}</td>
    <td>{$value.weight}</td>
    <td>{$value.product_details}</td>
    <td>{$value.carrier}</td>
  </tr>
  {foreachelse}
  <tr>
    <td colspan="16"><p>{$lang.no_data}</p></td>
  </tr>
  {/foreach}
</table>
	
{if $order_details}
<input type="hidden" name="process_type" value="intiate_milkrun" />
<div class="buttons-container buttons-bg">
	<div class="float-left">
		{include file="buttons/button.tpl" but_text=$initiate_milkrun 
		but_id="milkrun_initiate" but_role="button_main"}
		<span class="submit-button cm-button-main cm-process-items">
			<input type="button" value="Cancel" onclick="javascript:history.go(-1);" />
		</span>
	</div>
</div>
{/if}
</form>
{/capture}
{include file="common_templates/mainbox.tpl" title=$milkrun_initiation_review_page content=$smarty.capture.mainbox title_extra=$smarty.capture.title_extra tools=$smarty.capture.tools}
