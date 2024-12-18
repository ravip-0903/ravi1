{literal}<style>
.questoin_stts:hover{text-decoration:underline;}
</style>
{/literal}
<div>
{if $error=='not_found' or $error==''}
    <h2 class="subheader" style="font: bold 22px trebuchet ms; color: #EE811D;">	
      {$lang.confirm_your_order}
    </h2>
    
{elseif $error=='already_confirm'}
    <h2 class="subheader" style="font: bold 22px trebuchet ms; color: #EE811D;">	
      {$lang.already_confirm_head|escape}
    </h2>
    <div style="width:100%">
      {$lang.already_confirm_text|escape}
    </div>
{elseif $error=='dead_status'}
   <h2 class="subheader" style="font: bold 22px trebuchet ms; color: #EE811D;">	
      {$lang.dead_status_head|escape}
    </h2>
    <div style="width:100%">
      {$lang.dead_status_text|escape}
    </div>
{elseif $error=='cod_confirm'}
  <h2 class="subheader" style="font: bold 22px trebuchet ms; color: #EE811D;">	
      {$lang.order_confirm_head|escape}
  </h2>
  <div style="width:100%">
      {$lang.order_confirm_text|escape}
    </div>
{/if}
{if $error=='already_confirm' || $error=='dead_status' || $error=='cod_confirm'}
    <div style="float:right; margin-top:-46px">
      {'[click_here]'|str_replace:"<a href='index.php?dispatch=cod_confiramtion.cod'>Click Here</a>":$lang.another_cod}
    </div>
{/if}
{if $error=='not_found' or $error==''}
<div>
{$lang.cod_cofirm_pre_text|unescape}
</div>
{/if}
{if $error=='not_found'}
      <div class="not_found">
        {$lang.not_found|unescape}
      </div>
{/if}
{if $error=='not_found' or $error==''}
<form action="{""|fn_url}" name="cod_confirmation"  method="post">
  <div style="width:500px; float:left; display:block; clear:both; margin:5px 0 0;">
  	<label class="cm-required cm-email cont_nl_address" style="width:175px">{$lang.enter_orderno}:</label>
  	<input type="text" name="order_id" class="input-text round_five profile_detail_field cont_nl_inpt_width" id="email_id">
  </div>
  <div style="margin:10px 0 0; width:500px; float:left; display:block; clear:both; ">
    <label class="cm-required cm-email cont_nl_address" style="width:175px">{$lang.enter_phone}:</label>
  	<input type="text" name="phone" class="input-text round_five profile_detail_field cont_nl_inpt_width" id="phone">
  </div>
  <div class="box_functions" style="width:190px;">
   {include file="buttons/save.tpl" but_name="dispatch[cod_confirmation.cod]" but_text="Confirm" but_role="button_main" but_class="box_functions_button "}
  </div>
  <div style="float:right; clear:both; width:390px; text-align:left; margin-top:5px ">
   {$lang.cod_confirm_post_text|unescape}
  </div>
</form>
{/if}
</div>

{if $error=='already_confirm' || $error=='dead_status' || $error=='cod_confirm'}
<div style="width:100%">

<div class="box_OrderHeader">
{$lang.order_status_heading}
</div>

<div class="box_Orderdetails">
<div class="box_Orderdetails_content">{$order_info.order_id}</div>  
<div class="box_Orderdetails_title">Order Number</div>
</div>
<div class="box_Orderdetails">
<div class="box_Orderdetails_content">{include file="common_templates/status.tpl" status=$order_info.status display="view" name="update_order[status]"}</div>  
<div class="box_Orderdetails_title">
{$lang.status_change_date_message}
<br />
{assign var="status_change_date" value=$order_info.order_id|fn_get_status_change_date:$order_info.status}
<strong>{if !empty($status_change_date)}{$status_change_date|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}{else}{$order_info.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}{/if}</strong>
</div>
</div>
<div class="box_Orderdetails">
<div class="box_Orderdetails_content">{$order_info.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}</div>  
<div class="box_Orderdetails_title">Order Date</div>
</div>
<div class="clearboth"></div>

{if !empty($shipments)}
<div class="box_OrderCourierDetails">
{foreach from=$shipments key="id" item="shipment"}
				{if $shipment.carrier && $shipment.tracking_number}
               
				{include file="common_templates/carriers.tpl" carrier=$shipment.carrier tracking_number=$shipment.tracking_number shipment_id=$shipment.shipment_id}
                   {assign var="ship_msg" value=$lang.ship_msg|replace:'[carrier_name]':"<a href=`$smarty.capture.carrier_url`>`$shipment.carrier`</a>"}
                   {assign var="ship_msg" value=$ship_msg|replace:'[tracking_no]':"<a href=`$smarty.capture.carrier_url`>`$shipment.tracking_number`</a>"}
                   
                   {$ship_msg}
					
				{else}
				    {$shipment.shipping}
				{/if}
			{/foreach}
</div>
<div class="float_left margin_top_five" style="font:11px verdana; color:#666;">
{$lang.track_no_msg}
</div>
<div class="clearboth"></div>
{/if}

{assign var="lang_var" value='order_message_'|cat:$order_info.status}
{if $order_info.status=='N' || $order_info.status=='F'}
    {assign var="days_in_status" value=$order_info.order_id|fn_get_status_days:$order_info.status:$order_info.timestamp}
    {if $days_in_status < 48}
     {assign var=lang_var_less_48 value=$lang_var|cat:'_less48'}
       {if $lang.$lang_var_less_48!=''}
        <div class="box_Orderinfo">
          {$lang.$lang_var_less_48}
        </div>
       {/if}
    {else}
      {assign var=lang_var_above_48 value=$lang_var|cat:'_above48'}
        {if $lang.$lang_var_above_48!=''}
        <div class="box_Orderinfo">
          {$lang.$lang_var_above_48}
        </div>
       {/if}
    {/if}
{else}
    {if $lang.$lang_var!=''}
    <div class="box_Orderinfo">
      {$lang.$lang_var}
    </div>
    {/if}
{/if}





<div class="margin_top_fifty" style="font:20px trebuchet ms; color:#007AC0; float:left; width:100%;">
{$lang.order_detail_heading}
</div>
<table cellpadding="0" cellspacing="0" border="0" class="table product-list" width="100%">
<tr>
	<th align="left" valign="top">{$lang.product}</th>	
	<th style="width:88px;" align="right" valign="top">{$lang.quantity}</th>
    <th style="width:88px;" align="right" valign="top">{$lang.price}</th>
	{if $order_info.use_discount}
	<th style="width:88px;" align="right" valign="top">{$lang.discount}</th>
	{/if}
	{if $order_info.taxes && $settings.General.tax_calculation != "subtotal"}
	<th style="width:88px;" align="right" valign="top">{$lang.tax}</th>
	{/if}
    <th style="width:88px;" align="right" valign="top">{$lang.subtotal}</th>
</tr>

{foreach from=$order_info.items item="product" key="key"}
{cycle values=",class=\"table-row\"" name="class_cycle" assign="_class"}
{if !$product.extra.parent}
<tr {$_class} valign="top">
	<td  align="left" valign="top">
    {if !$product.deleted_product}
    <a href="{"products.view?product_id=`$product.product_id`"|fn_url}" class="product-title" style="font:bold 11px verdana;">
    {/if}
    {$product.product|unescape}
    {if !$product.deleted_product}
    </a>
    {/if}
	{if $product.product_code}
	<br />
	<span style="color:#333; font:11px verdana;">{$lang.code}:&nbsp;{$product.product_code}</span>
	{/if}
    <br />
        <div style="font:11px Verdana, Geneva, sans-serif; margin-top:10px; color:#333;">
        {assign var="company_name" value=$product.company_id|fn_get_company_name_from_id}
        {assign var="vendor_info" value=$product.company_id|fn_sdeep_get_vendor_info}
         <div class="float_left margin_top_five">
          {$lang.sold_by}:<a href="{"companies.view?company_id=`$product.company_id`"|fn_url}">{$company_name}</a> 
         </div>
         <div class="float_left margin_left_five">
         {include file="addons/sdeep/common_templates/vendor_icons.tpl" vendor_info=$vendor_info size=10}
        </div>
        </div>
    <div class="clearboth"></div>
    {assign var="estimation_id" value=$product.product_id|fn_get_product_estimation}
    {if $estimation_id == ''}
       {assign var="estimation_id" value=$config.default_shipping_estimation}
    {/if}
    <div style="font:11px verdana; color: #333; margin-top:10px;">
          {assign var="shipping_details" value=$estimation_id|fn_my_changes_get_shipping_estimation}
          {$shipping_details.name}
    </div>
               
	<div style="font:11px verdana; color: #333; margin-top:10px;">
       {assign var="return_period" value=$product.product_id|fn_get_return_period}
       {if !empty($return_period)}
       		{$lang.return_period_pre_message}&nbsp;{$return_period}&nbsp;{$lang.days}(<a href="{"pages.view&page_id=13"|fn_url}" title="Return Policy">{$lang.read_more}</a>) 
       {else}
           {$lang.return_period_not_available}
       {/if}     
    </div>
    </td>
    <td  align="right" valign="top">&nbsp;{$product.amount}</td>
    <td  align="right" valign="top">
	{if $product.extra.exclude_from_calculate}
    {$lang.free}
    {else}
    {include file="common_templates/price.tpl" value=$product.original_price}
    {/if}
    </td>
	{if $order_info.use_discount}
	<td  align="right" valign="top">
			{if $product.extra.discount|floatval}{include file="common_templates/price.tpl" value=$product.extra.discount}{else}-{/if}
		</td>
	{/if}
	{if $order_info.taxes && $settings.General.tax_calculation != "subtotal"}
	<td  align="right" valign="top">
			{if $product.tax_value|floatval}{include file="common_templates/price.tpl" value=$product.tax_value}{else}-{/if}
		</td>
	{/if}
	<td  align="right" valign="top">
{if $product.extra.exclude_from_calculate}{$lang.free}{else}{include file="common_templates/price.tpl" value=$product.display_subtotal}{/if}
</td>
</tr>
{/if}
{/foreach}
</table>
{hook name="orders:extra_list"}
<div>
<div class="float_left width_fiftypercent"> 
	{if $order_info.payment_id}
	<div class="float_left margin_top_twenty" style="font-size:13px; font-family:trebuchet ms; font-weight:bold; color:#7C7E80; width:100%">
	{$lang.payment_method}:
    {if $order_info.payment_method.payment_id==0}
          {$lang.clues_bucks_payment} 
    </div>      
    {else}
          {assign var="payment_info" value=$smarty.request.order_id|fn_get_new_payment_info}
       {$payment_info.type_name}
     </div>  
    <div class="float_left margin_top_five" style="font-size:13px;font-weight:bold; font-family:trebuchet ms; color:#7C7E80;">
           {$lang.paid_using}: &nbsp;{$payment_info.name}
    </div>   
    {/if}
    
	{/if}
	
	<div class="clearboth"></div>
	{if $order_info.coupons}
	{foreach from=$order_info.coupons item="coupon" key="key"}
    <div class="float_left margin_top_five" style="font-size:13px; font-family:trebuchet ms; color:#7C7E80;">
    {$lang.coupon_apply}:
    {$key}
    </div>
	{/foreach}
	{/if}
    <div class="clearboth"></div>

	{include file="views/orders/components/order_gift_message.tpl" order_info=$order_info}
    <div style="clear:both"></div>
	<div class="float_left margin_top_five" style="font-size:13px; font-family:trebuchet ms; color:##7C7E80;">
		{$lang.customer_notes}:&nbsp;
		{$order_info.notes|replace:"\n":"<br />"|default:"-"}
</div>
	<div class="clearboth"></div>
</div>
<div class="float_left width_fiftypercent">
{include file="views/orders/components/order_total_custom.tpl"}
</div>
{/hook}
<div class="clearboth"></div>
<div class="thank_msg">
{$lang.thank_msg}
</div>

<div class="clearboth height_thirty"></div>

</div>
{/if}
{literal}
<script type="text/javascript">
	$('.cm-notification-close').click(function(){
		$('.notification-e').css('display','none');
		$('.notification-n').css('display','none');
	});
</script>
{/literal}