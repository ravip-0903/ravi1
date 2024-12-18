{literal}
<style>
.product-notification-container{margin-left:-285px;width: 500px!important; top:30%!important}
.popupbox-closer{right:-43px;}
.product-notification{width:500px;}
.e-shadow{display:none;}
</style>
{/literal}
{assign var="cancel_order_info" value=$smarty.request.order_id|fn_get_order_cancel_info}
{assign var="reasons" value="O"|fn_get_cancel_reason:$cancel_order_info.age}

{if $cancel_order_info}
<div class="order_dtl_nl_pp">
   <label class="title_pp">{$lang.order_id}:</label>
   <label class="title_pp_dtl">{$smarty.request.order_id}</label><div class="clearboth"></div>
   <label class="title_pp">{$lang.status}:</label>
   <label class="title_pp_dtl">{$cancel_order_info.status}</label><div class="clearboth"></div>
   <label class="title_pp">{$lang.order_date}:</label>
   <label class="title_pp_dtl">{$cancel_order_info.order_date|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}</label>
</div>
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="table pp_nl_order_dtl">
 <tr>
   <th>{$lang.product}</th>
   <th>{$lang.amount}</th>
 </tr>
 {foreach from=$cancel_order_info.products item="cancel_products"}
   <tr>
       <td style="border-bottom:1px solid #ccc">{$cancel_products.product}</td>
       <td style="border-bottom:1px solid #ccc">{$cancel_products.amount}</td>
   </tr>
 {/foreach}
</table>

<form action="{""|fn_url}" name="cancel_order" method="POST" onsubmit="return cancel_order_function()">
<input type="hidden" name="token" value="{$smarty.session.form_token_value}"/>
<input type="hidden" id="cancel_order_id" name="orderid" value="{$smarty.request.order_id}">
<input type="hidden" id="current_status" name="cur_status" value="{$cancel_order_info.status_code}">
<div class="pp_reson_cancel_bx">
  <label for="reasons" class="pp_reason_title" >{$lang.reaons}:</label>
  <select id="reasons" name="reasons" class="pp_reason_type profile_detail_field round_five" style="width: 228px;" >
    <option value="">Select</option>
    {foreach from=$reasons item="reason"}
      <option value="{$reason.reason_id}">{$reason.reason}</option>
    {foreachelse}
		<option value="">{$lang.no_reason_found}</option>
    {/foreach}
  </select>
  
</div>
<div  class="pp_reson_cancel_bx">
  <label class="pp_reason_title">{$lang.comment}</label>
  <label><textarea name="comment" id="comment" style="width:216px;"  class="pp_reason_type profile_detail_field round_five"></textarea></label>
</div>
<div class="box_functions" >
  <input type="submit" name="dispatch[orders.cancel_order]" class="box_functions_button pointer_nl" value="{$lang.cancel_order}" >
</div>
</form>
{literal}
 <script>
  function cancel_order_function()
  {
	  var order_id=$('#cancel_order_id').val();
	  var region=$('#reasons').val();
	  var comment=$('#comment').val();
	  var cur_status=$('#current_status').val();
	  $('#reasons').css('border-color','none');
	  if(region=='')
	  {
		  $('#reasons').css('border-color','red');
		  alert("Please Select A Reason");
		  return false;
	  }
	 return true;
  }
 </script>
 
{/literal}
{/if}
