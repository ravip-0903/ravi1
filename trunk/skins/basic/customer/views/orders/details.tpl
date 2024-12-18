{* $Id: details.tpl 12544 2011-05-27 10:34:19Z bimib $ *}
 {literal}

<style>

.price {font-size: 18px; color: #444; font-weight: bold; }
.nl_store_analytics_summary{display: inline !important; float: left;  width:20%; border-right:1px dotted #CCC; margin:5px 0 10px 0; padding:0px 15px 0px 15px; vertical-align: top;}

</style>{/literal}

{if $view_only != "Y"}
<div align="right" class="clear no_mobile">
<ul class="action-bullets">
<li style="padding-right:40px;"> 
    {assign var="rma_status" value=$order_info.order_id|fn_get_return_status}

      {if !empty($rma_status)}
     <a href="http://cdn.shopclues.com/images/banners/Return_Instructions.pdf" target="_blank">{$lang.return_guidelines_for_rma}</a>
    {/if}
</li>
{hook name="orders:details_bullets"}
{/hook}
</ul>
</div>
{/if}

{if $order_info}
	{if $view_only != "Y"}
    <div style="width:100%; height:10px" class="no_mobile">
		<div class="float_right" style="width:550px;">
			{hook name="orders:details_tools"}
			{assign var="print_order" value=$lang.print_invoice}
			{assign var="print_pdf_order" value=$lang.print_pdf_invoice}
           
             {assign var="rma_id" value=$order_info.order_id|fn_get_return_id}
           
			{if $status_settings.appearance_type == "C" && $order_info.doc_ids[$status_settings.appearance_type]}
				{assign var="print_order" value=$lang.print_credit_memo}
				{assign var="print_pdf_order" value=$lang.print_pdf_credit_memo}
			{elseif $status_settings.appearance_type == "O"}
           
				{assign var="print_order" value=$lang.print_order_details}
				{assign var="print_pdf_order" value=$lang.print_pdf_order_details}
             
			{/if}
			{include file="buttons/button.tpl" but_text=$lang.re_order but_href="orders.reorder?order_id=`$order_info.order_id`"}
         
         {if !empty($order_info.gift_certificates) && ($order_info.status !='N' && $order_info.status !='F') }
     
    {include file="buttons/button_popup.tpl" but_text=$print_order but_href="orders.print_invoice?order_id=`$order_info.order_id`" width="900" height="600"}
        
			{elseif !empty($order_info.items)}
             {include file="buttons/button_popup.tpl" but_text=$print_order but_href="orders.print_invoice?order_id=`$order_info.order_id`" width="900" height="600"}
        {/if}
			{/hook}
                {foreach from=$rma_id.returns item=rm_id key=id}
                {assign var="rm_id" value=$id}
                {/foreach}
       
    {if ! empty($rma_status)}
    <span class="button"> <a href="{"rma.print_slip?return_id=`$rm_id`"|fn_url}">{$lang.print_shipping_for_return}</a></span>
       {/if}
            <div class="clearboth"></div>
		</div>
        
      </div>  
	{/if}
  
	{if $settings.General.use_shipments == "Y"}
		{capture name="tabsbox"}
		<div id="content_general" class="hidden">
	{/if}
	<div class="clear order-info">
	{*hook name="orders:info"*}
	<table cellpadding="2" cellspacing="0" border="0" width="100%" class="float_left">
	{if $status_settings.appearance_type == "I" && $order_info.doc_ids[$status_settings.appearance_type]}
	<tr>
		<td><strong>{$lang.invoice}</strong>:&nbsp;</td><td>{$order_info.doc_ids[$status_settings.appearance_type]}</td>
	</tr>
	{elseif $status_settings.appearance_type == "C" && $order_info.doc_ids[$status_settings.appearance_type]}
	<tr>
		<td><strong>{$lang.credit_memo}</strong>:&nbsp;</td><td>{$order_info.doc_ids[$status_settings.appearance_type]}</td>
	</tr>
	{/if}
	<tr>
		<td>
             <div class="order_info">
		<ul>
     
        <li class="nl_store_analytics_summary" style="width:150px; border:0;"> <span class="price">{$order_info.order_id}</span>
         <span class="block" style="word-wrap:break-word;white-space:normal;">{$lang.order} </span> </li>
                        
         <li class="nl_store_analytics_summary" style="width:270px; border:0;">
          <span class="price">{$order_info.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}</span>
          <span class="block" style="word-wrap:break-word;white-space:normal;">{$lang.date}</span> 
          {*{if $order_info.pdd_edd}
          <span class="price" style="color: #333;">{$order_info.pdd_edd.edd1} {$lang.pdd_mid} {$order_info.pdd_edd.edd2}</span>
          <span class="block" style="word-wrap:break-word;white-space:normal;color: #333;">{$lang.edd_detail}</span> 
          {/if}*}
        </li>
               {*sapna added here parent id condition to hide status*}   
                {if $order_info.is_parent_order =='N'}
		 <li class="nl_store_analytics_summary" style="width:150px; border:0;"> <span class="price">{include file="common_templates/status.tpl" status=$order_info.status display="view" name="update_order[status]"}</span>
          <span class="block" style="word-wrap:break-word;white-space:normal;">{$lang.status}</span> </li>
          {/if}
        </ul>
        </div>
{*sapna*}
{if $order_info.is_parent_order =='Y'}
<div class="fot_note_nl" style="clear: both; display: block; background: #C1F3FA; padding: 8px; margin-left: 17px; width: 718px;">
    {if $order_info.multiaddress_order_status =='Y'}
        {$lang.multiaddress_parent_order_info}
    {else}
        {$lang.parent_order_info}
    {/if}
</div>
{/if}
	{if $order_info.parent_order_id !='0'}
	<div class="ordr_details_combined_ordr" style="clear: both; display: block; background: #C1F3FA; padding: 8px; margin-left: 17px;  width: 718px;"><span style="font-size:16px;">{$lang.part_of_original_order} <b><a href="{"orders.details?order_id=`$order_info.parent_order_id`"|fn_url}">{$order_info.parent_order_id}</a></b></span>
<div class="fot_note_nl" style="color:#666;">{$lang.parent_id_footer_text}</div>
</div>

	{/if}
        </td>
	</tr>
	</table>
	{*/hook*}
	</div>
        <div class="no_mobile">
        {if $order_info.is_parent_order!='Y'}       
 {include file="common_templates/bazooka.tpl"} 
{/if}
        </div>

{capture name="group"}

{include file="common_templates/subheader.tpl" title=$lang.products_information}

<table cellpadding="0" cellspacing="0" border="0" class="table product-list" width="100%">
{hook name="orders:items_list_header"}
<tr class="no_mobile">
	<th>{$lang.product}</th>
	<th>{$lang.price}</th>
	<th>{$lang.quantity}</th>
	{if $order_info.use_discount}
		<th>{$lang.discount}</th>
	{/if}
	{if $order_info.taxes && $settings.General.tax_calculation != "subtotal"}
		<th>{$lang.tax}</th>
	{/if}

	<th>{$lang.subtotal}</th>
</tr>
{/hook}

{foreach from=$order_info.items item="product" key="key"}
{hook name="orders:items_list_row"}
{if !$product.extra.parent}
{cycle values=",class=\"table-row\"" name="class_cycle" assign="_class"}
<tr {$_class} valign="top">
	<td class="ordr_details_product">{if !$product.deleted_product}<a href="{"products.view?product_id=`$product.product_id`"|fn_url}" class="product-title">{/if}{$product.product|unescape}{if !$product.deleted_product}</a>{/if}
		{if $product.extra.is_edp == "Y"}
		<div class="right"><a href="{"orders.order_downloads?order_id=`$order_info.order_id`"|fn_url}"><strong>[{$lang.download}]</strong></a></div>
		{/if}
		{if $product.product_code}
		<p>{$lang.code}:&nbsp;{$product.product_code}
                {*sapna*}
                {if $order_info.is_parent_order =='Y'}
                    <span id="sub_order" style="float:right;"><b>{$lang.sub_order_number}:
                    {foreach from=$child_ids item="child_id"}

                     {if $child_id.item_id ==$key}
                     <a href="{"orders.details?order_id=`$child_id.order_id`"|fn_url}">{$child_id.order_id}</a>
                     {/if}

                    {/foreach}
                    </b></span>
                {/if}
		</p>
		{/if}
		
                 
		{hook name="orders:product_info"}
		{if $product.product_options}{include file="common_templates/options_info.tpl" product_options=$product.product_options}{/if}
		{/hook}
		{if $product.merchant_reference_number}
		<p>{$lang.merchant_ref}:&nbsp;{$product.merchant_reference_number}</p>
		{/if}
              {if $order_info.is_parent_order =='Y'}
                  {assign var="merchant_name" value=$product.extra.company_id|fn_get_merchant_name}
              <b>{$lang.merchant_name_sub_orders}:</b>&nbsp;{$merchant_name}
                {/if}
	</td>
	<td class="right nowrap ordr_details_price">
		{if $product.extra.exclude_from_calculate}{$lang.free}{else}{include file="common_templates/price.tpl" value=$product.original_price}{/if}</td>
	<td class="center ordr_details_amnt">&nbsp;{$product.amount}</td>
	{if $order_info.use_discount}
		<td class="right nowrap">
			{if $product.extra.discount|floatval}{include file="common_templates/price.tpl" value=$product.extra.discount}{else}-{/if}
		</td>
	{/if}
	{if $order_info.taxes && $settings.General.tax_calculation != "subtotal"}
		<td class="center nowrap">
			{if $product.tax_value|floatval}{include file="common_templates/price.tpl" value=$product.tax_value}{else}-{/if}
		</td>
	{/if}
	<td class="right ordr_details_total">
         &nbsp;<strong>{if $product.extra.exclude_from_calculate}{$lang.free}{else}{include file="common_templates/price.tpl" value=$product.display_subtotal}{/if}</strong></td>
</tr>
{/if}
{/hook}
{/foreach}
{hook name="orders:extra_list"}
<tr class=" no_mobile table-footer">
	{assign var="colsp" value=5}
	{if $order_info.use_discount}{assign var="colsp" value=$colsp+1}{/if}
	{if $order_info.taxes && $settings.General.tax_calculation != "subtotal"}{assign var="colsp" value=$colsp+1}{/if}
	<td colspan="{$colsp}">&nbsp;</td>
</tr>
{/hook}
</table>

{*include file="common_templates/subheader.tpl" title=$lang.summary*}

<table width="100%" class="fixed-layout float_left ordr_details_pymnt_optns">
	
	<tr>
		<td class="ordr_details_pymnt_optns_lst" style="font:11px verdana; width:200px; float:left; padding-top:25px">
        {if $order_info.payment_id}
			<b>{$lang.payment_method}:</b>
			{$order_info.payment_method.payment}&nbsp;
			{if $order_info.payment_method.description}
				({$order_info.payment_method.description})
			{/if}
			<div class="clearboth height_ten"></div>		
	    {/if}
	    		
		{if $order_info.coupon_codes}
				<strong>{$lang.coupon_codes} :</strong>
				{if isset($order_info.coupons)}
                {foreach from=$order_info.coupons item="coupons" key="k" name="cpn"}
                    {if $smarty.foreach.cpn.iteration == "1"}
                    	{$k}
                    {else}
                    	{","|cat:$k}
                    {/if}
                {/foreach}
                {else}
                	{$order_info.coupon_codes}							
				{/if}	
             <div class="clearboth height_ten"></div>			
		{/if}
	    
	    {include file="views/orders/components/order_gift_message.tpl" order_info=$order_info}
        <div style="clear:both"></div>
			
		<strong>{$lang.customer_notes}:&nbsp;</strong>
		{$order_info.notes|replace:"\n":"<br />"|default:"-"}
        
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
				
		</td>
	<td class="ordr_details_pymnt_optns_costs"style="width:535px">
			{include file="views/orders/components/order_total_custom.tpl" order_info=$order_info}
	</td>
	</tr>	
	

</table>
			
{if $without_customer != "Y"}
{* Customer info *}
{include file="views/profiles/components/profiles_info.tpl" user_data=$order_info location="I"}
{* /Customer info *}
{/if}

{if $order_info.promotions && ($order_info.status!='F' && $order_info.status!='I' && $order_info.status!='N' && $order_info.status!='D' && $order_info.status!='M' ) }
	{include file="views/orders/components/promotions.tpl" promotions=$order_info.promotions}
{ /if }

{/capture}
{include file="common_templates/group.tpl"  content=$smarty.capture.group}
{if $settings.General.use_shipments == "Y"}
	</div>
  
	<div id="content_shipment_info">
     <div class="order_info">
		<ul>
     
        <li class="nl_store_analytics_summary" style="width:150px; border:0;"> <span class="price">{$order_info.order_id}</span>
         <span class="block" style="word-wrap:break-word;white-space:normal;">{$lang.order} </span> </li>
                        
         <li class="nl_store_analytics_summary" style="width:270px; border:0;"> 
          <span class="price">{$order_info.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}</span>
          <span class="block" style="word-wrap:break-word;white-space:normal;">{$lang.date}</span>
          </li>
                        
		 <li class="nl_store_analytics_summary" style="width:150px; border:0;"> <span class="price">{include file="common_templates/status.tpl" status=$order_info.status display="view" name="update_order[status]"}</span>
          <span class="block" style="word-wrap:break-word;white-space:normal;">{$lang.status}</span> </li>
        </ul>
        </div>
{foreach from=$shipments key="id" item="shipment"}
        	<div class="clearboth"></div>
			{math equation="id + 1" id=$id assign="shipment_display_id"}
            {include file="common_templates/subheader.tpl" title="`$lang.shipment`&nbsp;`$shipment_display_id`"}
			<table cellpadding="0" cellspacing="0" border="0" class="table product-list" width="100%">
			<tr>
				<th>{$lang.product}</th>
				<th>{$lang.quantity}</th>
			</tr>
			{foreach from=$shipment.items item="shipped_product" key="key"}
			{assign var="product_hash" value=$shipped_product.item_id}
			{if $order_info.items.$product_hash}
				{assign var="product" value=$order_info.items.$product_hash}
				{cycle values=",class=\"table-row\"" name="class_cycle" assign="_class"}
				<tr {$_class} valign="top">
					<td>{if !$product.deleted_product}<a href="{"products.view?product_id=`$product.product_id`"|fn_url}" class="product-title">{/if}{$product.product|unescape}{if !$product.deleted_product}</a>{/if}
						{if $product.extra.is_edp == "Y"}
						<div class="right"><a href="{"orders.order_downloads?order_id=`$order_info.order_id`"|fn_url}"><strong>[{$lang.download}]</strong></a></div>
						{/if}
						{if $product.product_code}
						<p>{$lang.code}:&nbsp;{$product.product_code}</p>
						{/if}
						{if $product.product_options}{include file="common_templates/options_info.tpl" product_options=$product.product_options}{/if}
						{if $product.merchant_reference_number}
						<p>{$lang.merchant_ref}:&nbsp;{$product.merchant_reference_number}</p>
						{/if}
					</td>
					<td class="center">&nbsp;{$shipped_product.amount}</td>
				</tr>
			{/if}
			{/foreach}
			</table>
			
			
            <h2 class="subheader" style="clear:both;">
	
{$lang.shipping_information}
</h2>
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
               {else}                                        {$lang.not_tracking|replace:"[CARRIER_NAME]":$shipment.carrier|replace:"[LABEL]":$lang.tracking_num|replace:"[TRACKING_NUMBER]":$shipment.tracking_number}
               {/if}
                
                
		{*by <a {if $smarty.capture.carrier_url|strpos:"://"}target="_blank"{/if} href="{$smarty.capture.carrier_url}">{$smarty.capture.carrier_name}</a>*}
			{else}
				{$shipment.shipping}
			{/if}
	{* added by Sudhir dt 27 aug to show tracking details at customer *}			
			{if $shipment.comments}
				
                <h2 class="subheader" style="clear:both;">
	
{$lang.comments}
	</h2>
    			
				{$shipment.comments}
				</p>

			{/if}
	{* added by Sudhir dt 27 aug to show tracking details at customer end here *}

		{foreachelse}
			<p class="no-items" style="clear:both;">{$lang.text_no_shipments_found}</p>
			
	{/foreach}
	
    {if $tracking}
	{foreach from=$tracking item=tracking_id key="track_awd"}
			<table cellpadding="0" cellspacing="0" border="0" class="table product-list" width="100%">			
			<br><br><p><strong>{$lang.tracking_details_for}: {$track_awd}</strong></p>
						<tr>
							<th>{$lang.update}</th>
							<th>{$lang.status}</th>
							<th>{$lang.origin}</th>
							<th>{$lang.destination}</th>	
							<th>{$lang.update_date}</th>
						</tr>

		{foreach from=$tracking_id item=track}


						<tr>

							<td>{$track.carrier_status}{if $track.receiver_name != ''} (received by {$track.receiver_name},{$track.receiver_contact}){/if}</td>
							<td>{$track.sc_status}</td>
							<td>{$track.from_location}</td>
							<td>{$track.to_location}</td>
							<td>{$track.status_update_date|date_format:'%d/%m/%Y %I:%M %p'}</td>
						</tr>
		{/foreach}
			</table>
	{/foreach}
{/if}
 </div>   
	{/capture}
	{include file="common_templates/tabsbox.tpl" content=$smarty.capture.tabsbox active_tab=$smarty.request.selected_section}
{/if}
{/if}

{hook name="orders:details"}
{/hook}

{if $view_only != "Y"}
	{hook name="orders:repay"}
	{if $settings.General.repay == "Y" && $payment_methods}
		{include file="views/orders/components/order_repay.tpl"}
	{/if}
	{/hook}
{/if}

{capture name="mainbox_title"}{$lang.order_info}{/capture}
{assign var=url value=$smarty.request.shipment}
 {if $url ==Y}

{literal}
<script type="text/javascript">
    $(document).ready(function(){
	
$("#shipment_info").addClass("cm-active");
$("#general").removeClass("cm-active");
$('#content_shipment_info').css('display','block');
$('#content_general').css('display','none');
    });
</script>
{/literal}
{/if}
