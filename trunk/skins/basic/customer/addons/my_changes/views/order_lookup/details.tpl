{if isset($smarty.request.order_id) && isset($smarty.request.email_id)}
    {literal}
<style>
.box_Orderinfo_nl{color: #2C2B2B;font-size:12px; padding:10px;}
    .central-column{ width:100%!important; }
    .central-column{ margin-left:0%!important; }
	.order_detail_nl_aj .box_paymentcalculations_fieldname{width:69%!important;}
	.order_detail_nl_aj .box_paymentcalculations_field{width:29%!important;}
.price {font-size: 18px; color: #444; font-weight: bold; }
.nl_store_analytics_summary{display: inline !important; float: left;  width:20%; border-right:1px dotted #CCC; margin:5px 0 10px 0; padding:0px 15px 0px 15px; vertical-align: top;}
.ord_lookup_nw{}
.ord_lookup_nw .price{border-bottom:1px solid #bbb; height:36px; display:block;}
.ord_lookup_nw .block{color:#555;}
.ord_lookup_nw .ord_no{display: table-cell;font-size:33px;}
.ord_lookup_nw .ord_date{font-size:18px;}
.ord_lookup_nw .ord_date .sts{font-size:12px; font-weight:normal; color:#000000; display:block;}
.ord_lookup_nw .ord_status{font-size:18px; width: auto; overflow: hidden;}
.ord_lookup_nw .ord_status .sts{font-size:12px; font-weight:normal; color:#666; display:block; margin-top: 2px;}
.edd_pdd{clear: both; display: block; float: left; margin: 6px 0 20px 0; width: 100%;}
.purchase_details{float: left; padding: 10px 0; width: 100%;}
.payment_info_order{font-weight:normal;color:#333;}
.heading_order_info{font:20px trebuchet ms; color:#007AC0; float:left; width:100%; border-bottom: 1px solid #CCCCCC;}
.order_status_div{float:left;clear:both; width:100%; margin:30px 0 10px 0; }
.order_status_div a:hover{text-decoration:underline;}
</style>{/literal}
<div id="order_lookup_responsive" style="float:left; width:170px;">

<div class="sidebox-wrapper ">
<h3 class="sidebox-title"><span>Help Topics</span></h3>
    <!--<span class="stars_icon"></span>-->
    <div class="clearboth"></div>
	<div class="sidebox-body">
            
           <ul>
		<li><a href="/shipping-and-delivery.html" id="sandd">Shipping &amp; Delivery</a></li>
		<li><a href="/cancel-items-or-orders.html" id="como">Cancel Or Modify Order</a></li>
		<li><a href="/return-or-replacement.html" id="ror">Return or Replacement</a></li>
		<li><a href="/payments.html" id="pay">Payments</a></li>
<li><a href="/ordering.html" id="ord">Ordering</a></li>
<li><a href="/product-query.html" id="pq">Product Query</a></li>
		<li><a href="/promotions-and-coupon.html" id="pac">Promotions &amp; Coupon</a></li>
		<li><a href="/clues-bucks.html" id="cb">Clues Bucks</a></li>
		<li><a href="/gift-certificate.html" id="gc">Gift Certificate</a></li>
		
		<li><a target="_blank" href="/buyer-protection.html">Buyer Protection</a></li>
<li><a target="_blank" href="/bandoftrust.html">Band of Trust</a></li>
<li><a target="_blank" href="http://www.shopclues.com/sell">Selling at ShopClues</a></li>
<li><a href="managing-your-account.html" id="mya">Managing Your Account</a></li>
	</ul>
</div>
	<div class="sidebox-bottom"><span>&nbsp;</span></div>
</div>


</div>

    
<div id ="order_no_track_details">
<div class="box_OrderHeader">
{$lang.order_status_heading}
</div>
<ul>
			 {assign var="status_name" value=$order_info.status|fn_get_status_customer_facing_name} 
             {assign var="status_lang"  value=$status_name.customer_facing_name|default:$status_name.description}
             {assign var=order_status_count value=$status_lang|count_characters}
                 {assign var="order_no_cnt" value=$order_info.order_id|count_characters}
            <li class="nl_store_analytics_summary ord_lookup_nw" style="width:25%; border:0;"> 
				<span class="price ord_no" style="{if $order_no_cnt>8}font-size:26px;{elseif $order_status_count>15}height:45px;{/if}">{$order_info.order_id}</span>
				<span class="block" style="word-wrap:break-word;white-space:normal;">Order Number  </span> 
             </li>
              <li class="nl_store_analytics_summary ord_lookup_nw" style="width:18%;  border:0;"> 
					<span class="price ord_date" {if $order_status_count>15}style=height:45px;{/if}>
					{assign var="order_date" value=$order_info.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"} 
					{assign var=order_date_split value=", "|explode:$order_date}
					{$order_date_split[0]}
				   <label class="sts no_mobile"><b>{$order_date_split[1]}</b><label>
					</span>
                 <span class="block" style="word-wrap:break-word;white-space:normal;">Order Date </span> 
              </li>
     {if $order_info.is_parent_order =='N'}
             <li class="nl_store_analytics_summary ord_lookup_nw" style="width:41%; border:0;">
             <span class="price ord_status" {if $order_status_count >15}style=font-size:13px;height:45px;{/if}>
                 {include file="common_templates/status.tpl" status=$order_info.status display="view" name="update_order[status]"}
			  <label class="sts no_mobile">
				{$lang.status_change_date_message}
				{assign var="status_change_date" value=$order_info.order_id|fn_get_status_change_date:$order_info.status}
				{if !empty($status_change_date)}{$status_change_date|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}
				{else}
				{$order_info.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}{/if}
			 </label>
			</span>
			<span class="block" style="word-wrap:break-word;white-space:normal;">Current Status </span>
			</li>
    {/if}

    
</ul>
             
<!--<div class="box_Orderdetails" style="text-align: left; width:49% !important;">
<div class="box_Orderdetails_content" style="text-align:left; font-size:15px;">Order Number : <span class="box_Orderdetails_title" style="float:none"></span></div> 
<div class="box_Orderdetails_content" style="text-align:left; font-size:15px; ">Order Date : <span class="box_Orderdetails_title" style="float:none"></span></div> 
</div>
<div class="box_Orderdetails"  style="margin-left:0px; text-align: left;">
<div class="box_Orderdetails_title" style="text-align:left; width:298px;">

<div class="box_Orderdetails_content" style="text-align:left; margin-left: 0px; font-size:15px;"></div>  
</div>
</div><-->
<div class="clearboth"></div>

{assign var="lang_var" value='order_message_'|cat:$order_info.status}
{*<fieldset style="border:1px solid #D9D9D9;">*}
  {*<legend style="margin-left:10px;">*}
{if $order_info.is_parent_order =='N'}  
	<div class="box_Orderinfo_nl_q">{$lang.what_this_status_means|replace:"[STATUS]":$status_lang}</div>{*</legend>*}
	{if $order_info.status=='N' || $order_info.status=='F'}
		{assign var="days_in_status" value=$order_info.order_id|fn_get_status_days:$order_info.status:$order_info.timestamp}
		{if $days_in_status < 48}
		 {assign var=lang_var_less_48 value=$lang_var|cat:'_less48'}
		   {if $lang.$lang_var_less_48!=''}
			<div class="box_Orderinfo_nl" style="color:#959595;">
			  {$lang.$lang_var_less_48}
			</div>
		   {/if}
		{else}
		  {assign var=lang_var_above_48 value=$lang_var|cat:'_above48'}
			{if $lang.$lang_var_above_48!=''}
			<div class="box_Orderinfo_nl" style="color:#959595;">
			  {$lang.$lang_var_above_48}
			</div>
		   {/if}
		{/if}
		
	{else}
		{if $lang.$lang_var!=''}
		<div class="box_Orderinfo_nl" style="color:#959595;">
		  {$lang.$lang_var}
		</div>
		{/if}
	{/if}
{/if}
{*</fieldset>*}                    
{if $order_info.is_parent_order =='Y'}
<div class="fot_note_nl" style="clear: both; display: block; background: #C1F3FA; padding: 8px; margin-left:17px; width:607px;">
        {$lang.parent_order_info_lookup}
 </div>

{/if}



{if !empty($shipments)}
	<div style="font:20px trebuchet ms; margin-top: 20px; color:#007AC0; float:left; width:100%; border-bottom: 1px solid #CCCCCC;">{$lang.shipping_information}</div>
	<ul style="margin-left:0">
	{foreach from=$shipments key="id" item="shipment"}
			   {if $shipment.carrier && $shipment.tracking_number}
									   
						{include file="common_templates/carriers.tpl" carrier=$shipment.carrier tracking_number=$shipment.tracking_number shipment_id=$shipment.shipment_id}
					  
						{$smarty.capture.carrier_value}
					   {*done by sapna to show the tracking number or url on customer end here through language variable *} 
					   
						{assign var="is_url_tracking" value=$shipment.carrier|fn_get_tracking_url}
						{if !empty($is_url_tracking) && !empty($is_url_tracking.tracking_url)}
						  {if $is_url_tracking.is_url_trackable == 1}
							 {$lang.is_trackable|replace:"[CARRIER_NAME]":$is_url_tracking.carrier_name|replace:"[TRACKING_URL]":"<a target='_blank' href=`$is_url_tracking.tracking_url``$shipment.tracking_number`>`$shipment.tracking_number`</a>"}
						  {elseif $is_url_tracking.is_url_trackable == 0} 
							 {$lang.is_not_trackable|replace:"[CARRIER_NAME]":$is_url_tracking.carrier_name|replace:"[TRACKING_NUMBER]":$shipment.tracking_number|replace:"[TRACKING_URL]":"<a target='_blank' href=`$is_url_tracking.tracking_url`>`$is_url_tracking.tracking_url`</a>"}            	                
						  {/if}
						 {else}   
							{$lang.not_tracking|replace:"[CARRIER_NAME]":$shipment.carrier|replace:"[LABEL]":$lang.tracking_num|replace:"[TRACKING_NUMBER]":$shipment.tracking_number}
					   {/if}
				  {/if}

	{/foreach}
	<div class="float_left margin_top_five" style="font:11px verdana; color:#666;">
	{$lang.track_no_msg}
	</div>
	<div class="clearboth"></div>
{/if}

{if $tracking}
	{foreach from=$tracking item=tracking_id key="track_awd"}
		<table cellpadding="0" cellspacing="0" border="0" class="table product-list" width="100%">			
			<br><br><p><b><strong style="font:16px trebuchet ms; float:left; width:100%;">{$lang.tracking_details_for}: {$track_awd}</strong></b></p>
			          <br><p style="color:#959595;">{$lang.tracking_detail_footer_msg}</p>
						<tr>
							<th>{$lang.update}</th>
							<th>{$lang.status}</th>
							<th>{$lang.origin}</th>
							<th>{$lang.destination}</th>	
							<th>{$lang.update_date}</th>
						</tr>

		{foreach from=$tracking_id item=track name="last_track" key="key"}
   					{if $key lte 2}
						<tr id="tracking_{$key}" >
							   <td>{$track.carrier_status}{if $track.receiver_name != ''} (received by {$track.receiver_name},{$track.receiver_contact}){/if}</td>
								<td>{$track.sc_status}</td>
								<td>{$track.from_location}</td>
								<td>{$track.to_location}</td>
								<td>{$track.status_update_date|date_format:'%d/%m/%Y %I:%M %p'}</td>
						</tr>
					{else}
						<tr id="tracking_{$key}" class="expandable_hidable_rows" style="display:none;">
							   <td>{$track.carrier_status}{if $track.receiver_name != ''} (received by {$track.receiver_name},{$track.receiver_contact}){/if}</td>
								<td>{$track.sc_status}</td>
								<td>{$track.from_location}</td>
								<td>{$track.to_location}</td>
								<td>{$track.status_update_date|date_format:'%d/%m/%Y %I:%M %p'}</td>
						</tr>
					{/if}
					
			{if $smarty.foreach.last_track.last}
			  {if $tracking_id|count gt 3}
				<tr>
					<td id="expand_shipment_history" colspan="5"><a href="javascript:void(0);" onclick="showtracking();">{$lang.show_full_tracking}</a></td>
					<td id="hide_shipment_history" colspan="5" style="display:none;"><a href="javascript:void(0);" onclick="hidetracking();">{$lang.show_less_tracking}</a></td>
			   </tr>
			 {/if}
			{/if}
		{/foreach}
	     
		</table>
{/foreach}
	
{/if}
{if empty($shipment) && empty($tracking)}
	{if in_array($order_info.status,$edd_pdd_status) && !empty($order_info.pdd_edd)}
	<div class="margin_top_thirty" style="font:20px trebuchet ms; color:#007AC0; float:left; width:100%; border-bottom: 1px solid #CCCCCC;">
	{$lang.edd_pdd_detail}
	</div>
		<div class="edd_pdd info">
		{assign var="edd_pdd_status" value=$config.edd_pdd_status}
		
			{if $order_info.pdd_edd}
			   {$lang.edd_pdd_order_lkup|replace:"[EDD1]":$order_info.pdd_edd.edd1|replace:"[EDD2]":$order_info.pdd_edd.edd2}
			   {*{$order_info.pdd_edd.edd1} {$lang.pdd_mid} {$order_info.pdd_edd.edd2}*}
			 {/if}          
		
		</div>
	{/if}
{/if}	
<p class="">Need help? <a href="http://www.shopclues.com/help">click here</a> {$lang.help_msg} </p>



<div class="margin_top_thirty" style="font:20px trebuchet ms; color:#007AC0; float:left; width:100%; border-bottom: 1px solid #CCCCCC;">
{$lang.order_detail_heading}
</div>
<div class="purchase_details" style="float:left; padding:10px 0 ;">
	{if $order_info.is_parent_order =='N'}
		{assign var="b_state" value=$order_info.b_state|fn_get_state_name_lookup}
		{$lang.delivery_information|replace:"[CITY]":$order_info.b_city|replace:"[STATE]":$b_state}
	{/if}
<div class="payment_details" style="float:right; width:50%;">
  {assign var="payment_info" value=$smarty.request.order_id|fn_get_new_payment_info}
  
  <div class="float_left " style="font-size:13px;font-weight:bold; font-family:trebuchet ms; color:#333;">
           <b>{$lang.paid_using}</b> <br/>
        <span class="payment_info_order">
         {if $order_info.payment_id == 0}
				{$lang.clues_bucks_payment} 
		 {elseif $order_info.payment_id}
				{if $payment_info.type_name}
					{$payment_info.name}
			   {/if}
        </span>
        {/if} 
  </div>  
</div>
</div>
<table cellpadding="0" cellspacing="0" border="0" class="table product-list" width="100%">
<tr class="no_mobile">
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
	{*{if $product.product_code}
	<br />
	<span style="color:#333; font:11px verdana;">{$lang.code}:&nbsp;{$product.product_code}</span>
	{/if}*}
	{if $order_info.is_parent_order =='Y'}
		<span id="sub_order" style="float:right;">
			<b>
			{$lang.sub_order_number}:
                {foreach from=$child_ids item="child_id"}
                     {if $child_id.item_id ==$key}
                     <a href="{"order_lookup.details&order_id=`$child_id.order_id`&email_id=`$smarty.request.email_id`"|fn_url}">{$child_id.order_id}</a>
                     {/if}
                {/foreach}
		  </b>
	 </span>
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
    {*<div style="font:11px verdana; color: #333; margin-top:10px;">
          {assign var="shipping_details" value=$estimation_id|fn_my_changes_get_shipping_estimation}
          {$shipping_details.name}
    </div>*}
               
	<div style="font:11px verdana; color: #333; margin-top:10px;">
       {assign var="return_period" value=$product.product_id|fn_get_return_period}
       {if !empty($return_period)}
       		{$lang.return_period_pre_message}&nbsp;{$return_period}&nbsp;{$lang.days}(<a href="{"pages.view&page_id=13"|fn_url}" title="Return Policy">{$lang.read_more}</a>) 
       {else}
           {$lang.return_period_not_available}
       {/if}     
    </div>
    </td>
    <td class="no_mobile" align="right" valign="top">&nbsp;{$product.amount}</td>
    <td class="no_mobile"  align="right" valign="top">
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
	{*{if $order_info.payment_id}
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
    
	{/if}*}
	
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
		{if !empty($order_info.notes)}{$lang.customer_notes}:&nbsp;
		{$order_info.notes|replace:"\n":"<br />"|default:"-"}{/if}
</div>
	<div class="clearboth"></div>
    {assign var="cancel_info" value=$order_info.user_id|fn_get_cancel_info:$order_info.order_id}
         {if !empty($cancel_info)}
         <div class="clearboth height_ten"></div>	
             <strong>{$lang.cancel_info}</strong>
                <div style="margin-top: 10px; padding-left: 10px;">
                    <label style="float:left; width:70px"><strong>{$lang.reason}:</strong></label>
                    <label style="float:left;">{$cancel_info.reason}</label>
                    <div class="clear"></div>
                </div>
                <div style="margin-top: 10px; padding-left: 10px;">
                  <label style="float:left; width:70px"><strong>{$lang.comment}:</strong></label>
                  <label style="float:left; width:120px">{$cancel_info.comment}</label>
                  <div class="clear"></div>
                </div>
         {/if}
</div>
<div class="float_left width_fiftypercent order_detail_nl_aj">
{include file="views/orders/components/order_total_custom.tpl"}
</div>
</div>

{/hook}
<div class="clearboth"></div>

{if $smarty.request.x}
<div class="thank_msg">
{*$lang.thank_msg*}
<a target="_blank" href='{$config.http_location}/index.php?dispatch=write_to_us.add&order_id={$smarty.request.order_id}&email_id={$smarty.request.email_id}'>{$lang.cs_help_msg_link} </a>
</div>
{/if}

{elseif isset($smarty.request.error)}
{if $lang.not_matched_error!=''}
<div class="" style="float:left; width:100%; text-align:center; margin-top:50px;">
  {*$lang.not_matched_error*}
</div>
<div class="clearboth" style="height:100px;"></div>
{/if}
{/if}


{if $order_info.is_parent_order =='N'}
  <div class="order_status_div margin_top_twenty">
	{if $order_info.allow_cancelation=='Y'}
		  <a href="index.php?dispatch=orders.get_cancel_content&order_id={$order_info.order_id}" class="cm-ajax" onClick="return false" >{$lang.cancelation_request}</a>
	{/if}
	{if $order_info.status|in_array:$config.show_feedback_link_status}
		{assign var="feedback_status" value=$order_info.order_id|fn_get_feedback_posting_status}
		{if $feedback_status}
		  {$lang.feedback_posted|unescape}
		 {else}
		<a class="cm-ajax" href="index.php?dispatch=orders.show_feedback_form&order_id={$order_info.order_id}" onClick="return false" style="float:left;margin-left:5px;" >{$lang.post_feedback}</a>
		 {/if}
	{/if} 

{assign var="order_id" value=$order_info.order_id}
      {if $return_order.$order_id=='Y'}
    		<a href="{"rma.create_return?order_id=`$order_info.order_id`"|fn_url}">{$lang.return_registration}
            </a>
      {elseif $return_order.$order_id=='E'}
        {if $lang.return_expired!=''}
          <div class="not_eligible" style="cursor:pointer">{$lang.return_expired}
            <div class="expand_box">{$lang.return_expired_order_text}</div>
          </div>
         {/if}
      {/if}
</div>
{/if}

</div>
</div>
<div id="self_tools_display_none" style="float:right; width:170px;">

<div class="sidebox-wrapper ">
<h3 class="sidebox-title"><span>Self Help Tools</span></h3>
    <!--<span class="stars_icon"></span>-->
    <div class="clearboth"></div>
	<div class="sidebox-body">
            
            <ul>
		<!--<li><a href="/track-orders.html">Track Order</a></li>-->
		<!--<li><a href="/cancel-items-or-orders.html">Confirm COD Order</a></li>-->
		<li><a target="_blank" href="https://secure.shopclues.com/index.php?dispatch=orders.search">Cancel Order</a></li>
		<!--<li><a href="/managing-your-account.html">Resend Shipping Details</a></li>
		<li><a href="/payments.html">Confirm Order Delivery</a></li>
		<li><a href="/promotions-and-coupon.html">Edit Order Address</a></li>-->
		<li><a target="_blank" href="https://secure.shopclues.com/index.php?dispatch=orders.search">Request Return Of Order</a></li>
		<li><a target="_blank" href="https://secure.shopclues.com/index.php?dispatch=profiles.manage_addressbook">Update Address Book</a></li>
		<li><a target="_blank" href="https://secure.shopclues.com/update-profile">Change Password</a></li>
	</ul>
</div>
	<div class="sidebox-bottom"><span>&nbsp;</span></div>
</div>
</div>
{literal}
            <script>
			$('#expand_shipment_history').click(function(){
				$('#expand_shipment_history').hide();
				$('.expandable_hidable_rows').show();
				$('#hide_shipment_history').show();
				});
				
			$('#hide_shipment_history').click(function(){
				$('.expandable_hidable_rows').hide();
				$('#expand_shipment_history').show();
				$('#hide_shipment_history').hide();
				});
			</script>
 {/literal}
