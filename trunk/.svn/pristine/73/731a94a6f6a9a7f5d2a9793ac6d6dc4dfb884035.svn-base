  {* $Id: details.tpl 12544 2011-05-27 10:34:19Z bimib $ *}  
{include file="views/orders/taging_ord.tpl"}
<div style="text-align:center"><h1 class="mainbox-title">{$lang.full_detail_page}</h1></div>
{capture name="mainbox"}
  
{assign var="gift_item_message" value=$order_info.order_id|fn_get_order_gift_message}
{capture name="extra_tools"}

	{if $status_settings.appearance_type == "C" && $order_info.doc_ids[$status_settings.appearance_type]}

		{assign var="print_order" value=$lang.print_credit_memo}

		{assign var="print_pdf_order" value=$lang.print_pdf_credit_memo}
 
	{elseif $status_settings.appearance_type == "O"} 

		{assign var="print_order" value=$lang.print_order_details}

		{assign var="print_pdf_order" value=$lang.print_pdf_order_details}

	{else}

		{assign var="print_order" value=$lang.print_invoice}

		{assign var="print_pdf_order" value=$lang.print_pdf_invoice}
 
	{/if}

	<span id="order_extra_tools">
        {hook name="orders:details_tools"}
    
        {*{include file="buttons/button_popup.tpl" but_text=$print_order but_href="orders.print_invoice?order_id=`$order_info.order_id`" width="900" height="600" but_role="tool"}*}
        {if !"COMPANY_ID"|defined}
          {include file="buttons/button.tpl" but_text=$print_pdf_order but_href="orders.print_invoice?order_id=`$order_info.order_id`&format=pdf" but_role="tool"}
        {else}
          <span class="action-add" >
           <a style="background-position-x: -25px !important;padding-left: 10px!important; padding-top:2px" href="?dispatch=orders.print_invoice&order_id={$order_info.order_id}&format=pdf" >{$print_pdf_order}</a>
          </span>
        {/if}
        {if !"COMPANY_ID"|defined}
        {include file="buttons/button_popup.tpl" but_text=$lang.print_packing_slip but_href="orders.print_packing_slip?order_id=`$order_info.order_id`" width="900" height="600" but_role="tool"}
        {/if}
        {include file="buttons/button.tpl" but_text=$lang.edit_order but_href="order_management.edit?order_id=`$order_info.order_id`" but_role="tool"}
        {if !"COMPANY_ID"|defined}
           {if $create_order_link!=''}
       			<a onclick="check_confirm('{$order_info.order_id}');" style="margin-right:10px;">Clone Order</a>
           {/if}
        {/if}
        
        {if $rtoflag==1}
            {include file="buttons/button_popup.tpl" but_text='Address Label Thermal (4"x6")' but_href="orders.pdf_shipping_label?order_id=`$order_info.order_id`&format=pdf&rtoflag=1" width="900" height="600" but_role="tool"}
        {/if}
		
		{if "COMPANY_ID"|defined}
		<span class="action-add" style="margin-left:7px;">
		<a href="#" onclick="showPopUpdefault('default_popup');" style="background-position-x: -25px !important;padding-left: 10px!important; padding-top:2px">Choose shipping label</a>
        </span>
		{/if}
		
		
		
		
		{/hook}
    
        
	<!--order_extra_tools--></span>

{/capture}



{capture name="tabsbox"}



{if $settings.General.use_shipments == "Y"}

	{capture name="add_new_picker"}
    	{include file="views/shipments/components/new_shipment.tpl"}
        
	{/capture}

	{include file="common_templates/popupbox.tpl" id="add_shipment" content=$smarty.capture.add_new_picker text=$lang.new_shipment act="hidden"}
    {capture name="add_new_picker1"}

		{include file="views/shipments/components/new_shipment_re.tpl"}

	{/capture}
	
	{include file="common_templates/popupbox.tpl" id="add_shipment1" content=$smarty.capture.add_new_picker1 text=$lang.new_shipment act="hidden"}
    
{/if}





<form action="{""|fn_url}" method="post" name="order_info_form" class="cm-form-highlight">

<input type="hidden" name="order_id" value="{$smarty.request.order_id}" />

<input type="hidden" name="order_status" value="{$order_info.status}" />

<input type="hidden" name="result_ids" value="content_general" />

<input type="hidden" name="selected_section" value="{$smarty.request.selected_section}" />



<div id="content_general">



	<div class="item-summary clear center" id="order_summary">
    

		<div class="float-right">
        

		{if $order_info.status == $smarty.const.STATUS_INCOMPLETED_ORDER}

			{assign var="get_additional_statuses" value=true}

		{else}

			{assign var="get_additional_statuses" value=false}

		{/if}

		{assign var="order_status_descr" value=$smarty.const.STATUSES_ORDER|fn_get_statuses:true:$get_additional_statuses:true}

		{assign var="extra_status" value=$config.current_url|escape:"url"}

		{if $order_info.have_suppliers == "Y"}

			{assign var="notify_supplier" value=true}

		{else}

			{assign var="notify_supplier" value=false}

		{/if}

		{include file="common_templates/select_popup.tpl" suffix="o" id=$order_info.order_id status=$order_info.status items_status=$order_status_descr update_controller="orders" notify=true notify_department=true notify_supplier=$notify_supplier status_rev="order_summary,order_extra_tools" extra="&return_url=`$extra_status`"}

		</div>



		<div class="float-left">

		{$lang.order}&nbsp;&nbsp;<span>#{$order_info.order_id}</span>&nbsp;{if $order_info.company_id}({$lang.vendor}: {$order_info.company_id|fn_get_company_name}){/if}

		{if $status_settings.appearance_type == "I" && $order_info.doc_ids[$status_settings.appearance_type]}

		({$lang.invoice}&nbsp;&nbsp;<span>#{$order_info.doc_ids[$status_settings.appearance_type]}</span>)&nbsp;

		{elseif $status_settings.appearance_type == "C" && $order_info.doc_ids[$status_settings.appearance_type]}

		({$lang.credit_memo}&nbsp;<span>#{$order_info.doc_ids[$status_settings.appearance_type]}</span>)&nbsp;

		{/if}

		{$lang.by}&nbsp;&nbsp;<span>{if $order_info.user_id}<a href="{"profiles.update?user_id=`$order_info.user_id`"|fn_url}">{/if}{$order_info.firstname}&nbsp;{$order_info.lastname}{if $order_info.user_id}</a>{/if}</span>&nbsp;

		{assign var="timestamp" value=$order_info.timestamp|date_format:"`$settings.Appearance.date_format`"|escape:url}

		{$lang.on}&nbsp;<a href="{"orders.manage?period=C&amp;time_from=`$timestamp`&amp;time_to=`$timestamp`"|fn_url}">{$order_info.timestamp|date_format:"`$settings.Appearance.date_format`"}</a>,&nbsp;&nbsp;{$order_info.timestamp|date_format:"`$settings.Appearance.time_format`"}
                 
                {if !"COMPANY_ID"|defined}
                    
                 {if $success_percentage >=80 }
                  <span style="color:green">{$lang.customer_order_success_rate} 
                 {"("}{$success_order_count}{" / "}{$total_order_count}{")"} {"="}{$success_percentage|number_format:2}{" %"}</span>
                 {elseif $success_percentage >=50 && $success_percentage < 80.0}
                   <span style="color:#606060">{$lang.customer_order_success_rate} 
                 {"("}{$success_order_count}{" / "}{$total_order_count}{")"}{"="}{$success_percentage|number_format:2}{" %"}</span>
                 {else}
                     <span style="color:red">{$lang.customer_order_success_rate} 
                 {"("}{$success_order_count}{" / "}{$total_order_count}{")"}{"="}{$success_percentage|number_format:2}{" %"}</span>
                 {/if}
                 
                {/if}
		</div>
  
		{hook name="orders:customer_shot_info"}

		{/hook}

	<!--order_summary--></div>

   <span style="color:red; font-size:20px">
	{if $order_type=='split'}
    
         {if $order_info.is_parent_order=="Y"}
            {$lang.parent_order}
         {else}
            {$lang.split_order}
         {/if}
    {/if}
    {if !"COMPANY_ID"|defined}
        <!--HPRAHI FOR CLONE ORDERS-->
        {assign var="clone" value=$order_info.order_id|fn_get_clone_relationship}
        {if $clone}
            {foreach from=$clone item=item}
                {if $item.main_order_id == $order_info.order_id}
                <div style="color:red; font-size:20px">Clone Order: <a href="?dispatch=orders.details&order_id={$item.clone_order_id}">{$item.clone_order_id}</a></div>
                {else}
                <div style="color:red; font-size:20px">Clone's Parent Order: <a href="?dispatch=orders.details&order_id={$item.main_order_id}">{$item.main_order_id}</a></div>
                {/if}
            {/foreach}
        {/if}
    {/if}
   </span>
	<table cellpadding="0" cellspacing="0" border="0" width="100%">

	<tr valign="top">

		<td width="68%">
        	{*if !"COMPANY_ID"|defined*}
            {if $order_info.nss_done == 'y'}
			<div style="float:right"><!--hprahi-->
            	<span style="border:1px solid #DFDFDF;background:#F9F9F9;padding:4px">
                Servicable by {assign var="strnss" value = $order_info.order_id|order_nss_detail_html}
                {$strnss}
                </span>
            </div>
            {/if} 
            {*/if*}
			{* Customer info *}

			{include file="views/profiles/components/profiles_info.tpl" user_data=$order_info location="I"}

		</td>

		<td width="32%" class="details-block-container">

			{hook name="orders:payment_info"}

			{* Payment info *}

			{if $order_info.payment_id}

				{include file="common_templates/subheader.tpl" title=$lang.payment_information}

				<div class="form-field">

					<label>{$lang.method}:</label>

					{$order_info.payment_method.payment}&nbsp;{if $order_info.payment_method.description}({$order_info.payment_method.description}){/if}
                                       <br/>
                                       <label>{$lang.paid_using}:</label>
                                        
                                        {if !empty($order_info.paid.name)} {$order_info.paid.name}&nbsp; {/if}
					{if !empty($order_info.paid.type_name)}{$order_info.paid.type_name}{/if}
                                        

				</div>



				{if $order_info.payment_info}

					{foreach from=$order_info.payment_info item=item key=key}

					{if $item && ($key != "expiry_year" && $key != "start_year")}

						<div class="form-field">

							<label>{if $key == "card"}{assign var="cc_exists" value=true}{$lang.credit_card}{elseif $key == "expiry_month"}{$lang.expiry_date}{elseif $key == "start_month"}{$lang.start_date}{else}{$lang.$key}{/if}:</label>

							{if $key == "order_status"}

								{include file="common_templates/status.tpl" status=$item display="view" status_type=""}

							{elseif $key == "reason_text"}

								{$item|nl2br}

							{elseif $key == "expiry_month"}

								{$item}/{$order_info.payment_info.expiry_year}

							{elseif $key == "start_month"}

								{$item}/{$order_info.payment_info.start_year}

							{else}

								{$item}

							{/if}

						</div>

					{/if}

					{/foreach}



					{if $cc_exists}

					<p class="right">

						<input type="hidden" name="order_ids[]" value="{$order_info.order_id}" />

						{include file="buttons/button.tpl" but_text=$lang.remove_cc_info but_meta="cm-ajax cm-comet" but_name="dispatch[orders.remove_cc_info]"}

					</p>

					{/if}

				{/if}

			{/if}

			{/hook}



			{* Shipping info *}

			{if $order_info.shipping}

				{include file="common_templates/subheader.tpl" title=$lang.shipping_information}

				

				{foreach from=$order_info.shipping item="shipping" key="shipping_id" name="f_shipp"}

				<div class="form-field">

					<label>{$lang.method}:</label>

					{$shipping.shipping}

                    

				</div>

				

				{if $settings.General.use_shipments != "Y"}

					<div class="form-field">

						<label for="tracking_number">{$lang.tracking_number}:</label>

						<input id="tracking_number" type="text" class="input-text-medium" name="update_shipping[{$shipping_id}][tracking_number]" size="45" value="{$shipping.tracking_number}" />

					</div>

					<div class="form-field">

						<label for="carrier_key">{$lang.carrier}:</label>

						{include file="common_templates/carriers.tpl" id="carrier_key" name="update_shipping[`$shipping_id`][carrier]" carrier=$shipping.carrier}

					</div>

				{/if}

				{foreachelse}

					{if $settings.General.use_shipments != "Y"}

						<div class="form-field">

							<label for="shipping_method">{$lang.method}:</label>

							{if $shippings}

								<select id="shipping_method" name="add_shipping[shipping_id]">

								{foreach from=$shippings item="shipping"}

									<option value="{$shipping.shipping_id}">{$shipping.shipping}</option>

								{/foreach}

								</select>

							{/if}

						</div>

					

						<div class="form-field">

							<label for="tracking_number">{$lang.tracking_number}:</label>

							<input id="tracking_number" type="text" class="input-text-medium" name="add_shipping[tracking_number]" size="45" />

						</div>

						<div class="form-field">

							<label for="carrier_key">{$lang.carrier}:</label>

							{include file="common_templates/carriers.tpl" id="carrier_key" name="add_shipping[carrier]"}

						</div>

					{/if}

				{/foreach}

                

			{/if}

            

             <!-- Start Change by paresh -->
                {assign var="manifest_details" value=$order_info.order_id|order_manifest_details}


                {if $manifest_details}
                
                <div class="form-field">

                	<label>{$lang.manifest_id}:</label>

                   <!-- #{$manifest_details.manifest_id} -->
					{foreach from=$manifest_details item="manifests"}
                    	<a class="underlined" href="{"manifest_create.manifest_list_detail?manifest_id=`$manifests.manifest_id`"|fn_url}">#{$manifests.manifest_id}</a> {$manifests.dispatch_date} {$manifests.description}<br />
                    {/foreach}
                    <!--<label>{$lang.manifest_date}:</label>
					{$manifests.date_created|date_format:"`$settings.Appearance.date_format`"}<br />


                    <label>{$lang.dispatch_date}:</label>
					{$manifests.dispatch_date}<br />


                    <label>{$lang.carrier}:</label>
                    {$manifests.carrier_name|replace:'_':' '}<br />-->


                </div>
                
                {/if}

                <!-- End Change by paresh -->

            

            

            <!-- Start Change by paresh -->

                {assign var="shipment_details" value=$order_info.shipment_ids|get_shipment_data}

                <div class="form-field">

                {foreach from=$shipment_details item="shipments"}

                	<label>{$lang.shipment_id}:</label>

					<a class="underlined" href="{"shipments.details?shipment_id=`$shipments.shipment_id`"|fn_url}">{$shipments.shipment_id}</a><br />

                    <label>{$lang.tracking_number}:</label>

					{$shipments.tracking_number}<br />

                    <label>{$lang.carrier}:</label>

					{$shipments.carrier|replace:'_':' '}<br />

                    <label>{$lang.shipping_date}:</label>

                    {$shipments.timestamp|date_format:"`$settings.Appearance.date_format`"}<br />

                {/foreach}

                </div>

                <!-- End Change by paresh -->

			

			{if $settings.General.use_shipments == "Y"}

				<div class="form-field">

                {*$order_info|print_r*}

					{if $order_info.need_shipping}<!--Change By Paresh,hprahi -->

						<div id="create_new_shipment_tab" class="small-picker-container">{include file="common_templates/popupbox.tpl" id="add_shipment" content="" but_text=$lang.new_shipment act="create"}</div>
                        {if !"COMPANY_ID"|defined}
                        <div class="small-picker-container">{include file="common_templates/popupbox.tpl" id="add_shipment1" content="" but_text=$lang.new_shipment_re act="create"}</div>
                        {/if}

					{/if}

					<a href="{"shipments.manage?order_id=`$order_info.order_id`"|fn_url}">{$lang.view_shipments}&nbsp;({$order_info.shipment_ids|count})</a>

				</div>

			{/if}

		</td>

	</tr> 

	</table>

	{* Products info *}

	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table">

	<tr>

		<th>{$lang.product}</th>

		<th width="5%">{$lang.price}</th>

		<th width="5%">{$lang.quantity}</th>

		{if $order_info.use_discount}

		<th width="5%">{$lang.discount}</th>

		{/if}

		{if $order_info.taxes && $settings.General.tax_calculation != "subtotal"}

		<th width="5%">&nbsp;{$lang.tax}</th>

		{/if}

		<th width="7%" class="right">&nbsp;{$lang.subtotal}</th>

	</tr>

	{foreach from=$order_info.items item="oi" key="key"}

	{hook name="orders:items_list_row"}

	{if !$oi.extra.parent}

	<tr {cycle values="class=\"table-row\", " name="class_cycle"}>

		<td>

        {if !$oi.deleted_product}<a href="{"products.update?product_id=`$oi.product_id`"|fn_url}">{/if}{$oi.product|unescape}{if !$oi.deleted_product}</a>{/if}<br/>{$oi.ship_time|unescape}

			{hook name="orders:product_info"}

			{if $oi.product_code}<p>{$lang.sku}:&nbsp;{$oi.product_code}</p>{/if}

			{/hook}

			{if $oi.product_options}<div class="options-info">{include file="common_templates/options_info.tpl" product_options=$oi.product_options}</div>{/if}

		</td>

		<td class="nowrap">

			{if $oi.extra.exclude_from_calculate}{$lang.free}{else}{include file="common_templates/price.tpl" value=$oi.original_price}{/if}</td>

		<td class="center">

			&nbsp;{$oi.amount}<br />

			{if $settings.General.use_shipments == "Y" && $oi.shipped_amount > 0}

				&nbsp;<span class="small-note">(<span>{$oi.shipped_amount}</span>&nbsp;{$lang.shipped})</span>

			{/if}

		</td>

		{if $order_info.use_discount}

		<td class="nowrap">

			{if $oi.extra.discount|floatval}{include file="common_templates/price.tpl" value=$oi.extra.discount}{else}-{/if}</td>

		{/if}

		{if $order_info.taxes && $settings.General.tax_calculation != "subtotal"}

		<td class="nowrap">

			{if $oi.tax_value|floatval}{include file="common_templates/price.tpl" value=$oi.tax_value}{else}-{/if}</td>

		{/if}

		<td class="right">&nbsp;<span>{if $oi.extra.exclude_from_calculate}{$lang.free}{else}{include file="common_templates/price.tpl" value=$oi.display_subtotal}{/if}</span></td>

	</tr>

	{/if}

	{/hook}

	{/foreach}

	{hook name="orders:extra_list"}

	{/hook}

	</table>



	{* text_no_items_found*}



	<!--{***** Customer note, Staff note & Statistics *****}-->

	{hook name="orders:totals"}

	<div class="clear order-notes">

	<div class="float-left">

		<h3><label for="notes">{$lang.customer_notes}:</label></h3>

		<textarea class="input-textarea" name="update_order[notes]" id="notes" cols="40" rows="5">{$order_info.notes}</textarea>

	</div>

	

	<div class="float-left">
                    
                    <h3><label for="details">{$lang.staff_only_notes}:</label></h3>
                 
		<textarea class="input-textarea" name="update_order[details]" id="details" cols="40" rows="5">{$order_info.details}</textarea>
                  
                </div>
    {if !"COMPANY_ID"|defined} 
    <!-- by arpit -->
    
    <div class="float-left cust_q_popup_div" style="margin-top:40px;">
    <input class="cust_q_popup" type="button" value="Add/View Notes" style="float:left;background:url('images/buttons_bg.png') no-repeat scroll 100% 0 #09A3D6;border:1px solid #007FA9;border-radius:0px;color:#FFFFFF;display:inline-block;height:25px;line-height:11px;margin:0;padding:2px 15px 3px;text-shadow:0 1px 0 #055C7A;" onclick="document.getElementById('customerquerypopup').style.display='block'" />
    <p>&nbsp;</p>
    <input class="pop_up_zendesk_button" id="pop_up_zendesk_button" type="button" value="Create Zendesk Ticket" style="float:left;background:url('images/buttons_bg.png') no-repeat scroll 100% 0 #09A3D6;border:1px solid #007FA9;border-radius:0px;color:#FFFFFF;display:inline-block;height:25px;line-height:11px;margin:0;padding:2px 15px 3px;text-shadow:0 1px 0 #055C7A;" />
    
    <div style="clear:both;height:0px;overflow:hidden;"></div>
    </div>
    <div style="clear:both;height:0px;overflow:hidden;"></div>
    {/if}
    
    <!--code by ankur to show gift wrap message-->
    {if $order_info.gift_it=='Y'}
     	
         {if !empty($gift_item_message)}
            <div style="float:left;background-color: #F9F9F9; border:1px solid #999; padding:6px; color: #444444;margin-left: 425px;margin-top: 12px;width:425px">
              <h4>{$lang.gift_message}</h4>
               <div style="width:100%; margin:5px 0px">
                  <div style="float:left; width:50%">
                     <span style="float:left;font-weight:bold; font-size:12px">{$lang.gift_from}:&nbsp;</span>
                     <span style="float:left;">{$gift_item_message.gift_from}</span>
                     <div style="clear:both"></div>
                  </div>
                  <div style="float:left; width:50%">
                     <span style="float:left;font-weight:bold; font-size:12px">{$lang.gift_to}:&nbsp;</span>
                     <span style="float:left">{$gift_item_message.gift_to}</span>
                     <div style="clear:both"></div>
                  </div>
                  <div style="clear:both"></div>
               </div> 
               <div style="width:100%;margin:5px 0px">
                 <span style="float:left; font-weight:bold; font-size:12px">{$lang.message}:&nbsp;</span>
                 <span style="float:left;font-size:12px">{$gift_item_message.message}</span>
               </div>
               <br />
<br />

               <a href="javascript: void(0)" id="show_gift_wrap_frm" title="Change">Change</a> 
               &nbsp;&nbsp;&nbsp;&nbsp;<a href="UniTechCity.php?dispatch=orders.remove_message&order_id={$order_info.order_id}" title="Remove Message">Remove Message</a>
               &nbsp;&nbsp;&nbsp;&nbsp;<a href="UniTechCity.php?dispatch=orders.remove_gift_wrapping&order_id={$order_info.order_id}" title="Remove Gift Wrapping" style="float:right;">Remove Gift Wrapping</a>
               
               
               
               
               
               
               
                 
            </div>
         {/if}
         
    {/if}
    <!--code end-->

	<div class="float-right statistic-container">

		<ul class="statistic-list">

			<li>

				<em>{$lang.subtotal}:</em>

				<span>{include file="common_templates/price.tpl" value=$order_info.display_subtotal}</span>

			</li>



			{if $order_info.display_shipping_cost|floatval}

				<li>

					<em>{$lang.shipping_cost}:</em>

					<span>{include file="common_templates/price.tpl" value=$order_info.display_shipping_cost}</span>

				</li>

			{/if}

			{if $order_info.emi_fee != '0' && $order_info.emi_fee != ''  }
				<li>
					<em>{$lang.emi_fee}:</em>
					<span>{include file="common_templates/price.tpl" value=$order_info.emi_fee}</span>
				</li>
			{/if}
			
			


			{if $order_info.discount|floatval}

				<li>

					<em>{$lang.including_discount}:</em>

					<span>{include file="common_templates/price.tpl" value=$order_info.discount}</span>

				</li>

			{/if}



			{if $order_info.subtotal_discount|floatval}

			<li>

				<em>{$lang.order_discount}:</em>

				<span>{include file="common_templates/price.tpl" value=$order_info.subtotal_discount}</span>

			</li>

			{/if}



			{if $order_info.coupons}

			{foreach from=$order_info.coupons key="coupon" item="_c"}

				<li>

					<em>{$lang.discount_coupon}:</em>

					<span>{$coupon}</span>

				</li>

			{/foreach}

			{/if}



			{if $order_info.taxes}

				<li>

					<em>{$lang.taxes}:</em>

					<span>&nbsp;</span>

				</li>



				{foreach from=$order_info.taxes item="tax_data"}

				<li>

					<em>&nbsp;<span>&middot;</span>&nbsp;{$tax_data.description}&nbsp;{include file="common_templates/modifier.tpl" mod_value=$tax_data.rate_value mod_type=$tax_data.rate_type}{if $tax_data.price_includes_tax == "Y" && ($settings.Appearance.cart_prices_w_taxes != "Y" || $settings.General.tax_calculation == "subtotal")}&nbsp;{$lang.included}{/if}{if $tax_data.regnumber}&nbsp;({$tax_data.regnumber}){/if}</em>

					<span>{include file="common_templates/price.tpl" value=$tax_data.tax_subtotal}</span>

				</li>

				{/foreach}

			{/if}



			{if $order_info.tax_exempt == "Y"}

				<li>

					<em>{$lang.tax_exempt}</em>

					<span>&nbsp;</span>

				</li>

			{/if}



			{if $order_info.payment_surcharge|floatval && !$take_surcharge_from_vendor}

				<li>

					<em>{$lang.payment_surcharge}:</em>

					<span>{include file="common_templates/price.tpl" value=$order_info.payment_surcharge}</span>

				</li>

			{/if}

			
			{hook name="orders:totals_content"}

			{/hook}
            {if $order_info.gift_it=='Y' && $order_info.gifting_charge!=0}
            <li>
              <em>{$lang.gift_fee}:</em>
              <span>{include file="common_templates/price.tpl" value=$order_info.gifting_charge}</span>
            </li>
            {/if}
			<li class="total">

				<em>{$lang.total}:</em>

				<span>{include file="common_templates/price.tpl" value=$order_info.total}</span>

			</li>

		</ul>

	</div>

	</div>
     
	{/hook}

	<!--{***** /Customer note, Staff note & Statistics *****}-->

	

	{hook name="orders:staff_only_note"}

	{/hook}



<!--content_general--></div>



<div id="content_addons">



	{hook name="orders:customer_info"}

	{/hook}



<!--content_addons--></div>



{if $downloads_exist}

<div id="content_downloads">

	<input type="hidden" name="order_id" value="{$smarty.request.order_id}" />

	<input type="hidden" name="order_status" value="{$order_info.status}" />

	{foreach from=$order_info.items item="oi"}

	{if $oi.extra.is_edp == "Y"}

	<p><a href="{"products.update?product_id=`$oi.product_id`"|fn_url}">{$oi.product}</a></p>

		{if $oi.files}

		<input type="hidden" name="files_exists[]" value="{$oi.product_id}" />

		<table cellpadding="5" cellspacing="0" border="0" class="table">

		<tr>

			<th>{$lang.filename}</th>

			<th>{$lang.activation_mode}</th>

			<th>{$lang.downloads_max_left}</th>

			<th>{$lang.download_key_expiry}</th>

			<th>{$lang.active}</th>

		</tr>

		{foreach from=$oi.files item="file"}

		<tr>

			<td>{$file.file_name}</td>

			<td>

				{if $file.activation_type == "M"}{$lang.manually}</label>{elseif $file.activation_type == "I"}{$lang.immediately}{else}{$lang.after_full_payment}{/if}

			</td>

			<td>{if $file.max_downloads}{$file.max_downloads} / <input type="text" class="input-text-short" name="edp_downloads[{$file.ekey}][{$file.file_id}]" value="{math equation="a-b" a=$file.max_downloads b=$file.downloads|default:0}" size="3" />{else}{$lang.none}{/if}</td>

			<td>

				{if $oi.extra.unlimited_download == 'Y'}

					{$lang.time_unlimited_download}

				{elseif $file.ekey}

				<p><label>{$lang.download_key_expiry}: </label><span>{$file.ttl|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"|default:"n/a"}</span></p>

				

				<p><label>{$lang.prolongate_download_key}: </label>{include file="common_templates/calendar.tpl" date_id="prolongate_date_`$file.file_id`" date_name="prolongate_data[`$file.ekey`]" date_val=$file.ttl|default:$smarty.const.TIME start_year=$settings.Company.company_start_year}</p>

				{else}{$lang.file_doesnt_have_key}{/if}

			</td>

			<td>

				<select name="activate_files[{$oi.product_id}][{$file.file_id}]">

					<option value="Y" {if $file.active == "Y"}selected="selected"{/if}>{$lang.active}</option>

					<option value="N" {if $file.active != "Y"}selected="selected"{/if}>{$lang.not_active}</option>

				</select>

			</td>

		</tr>

		{/foreach}

		</table>

		{/if}

	{/if}

	{/foreach}

<!--content_downloads--></div>

{/if}



{if $order_info.promotions}

<div id="content_promotions">

	{include file="views/orders/components/promotions.tpl" promotions=$order_info.promotions}

<!--content_promotions--></div>

{/if}



{hook name="orders:tabs_content"}

{/hook}

<div style="width:100%">

{if $parent_id!=0}
<h2>{$lang.split_order_detail}</h2>
{if $parent_id!=$order_info.order_id}
Parent Order:<a href="UniTechCity.php?dispatch=orders.details&order_id={$parent_id}">{$parent_id}</a>
<br />
<br/>
{/if}

{assign var=i value=0}
Child Orders:
{foreach from=$child_order_id item="order_id"}
{assign var=i  value=$i+1}
{if $i!=1},{/if}<a href="UniTechCity.php?dispatch=orders.details&order_id={$order_id}">{$order_id}</a>
{/foreach}
<hr/>
<br/>
<br/>
{/if}

</div>


{if !"COMPANY_ID"|defined}
<div style="width:45%;float:left">
    <div>
        
        {assign var="clone" value=$order_info.order_id|fn_get_clone_relationship}
        {if $clone}
            {foreach from=$clone item=item}
                {if $item.main_order_id != $order_info.order_id}
                <div style="color:red; font-size:20px">It's a Clone Order</div>
                {/if}
            {/foreach}
        {/if}
    {* Added By Sudhir *}    
    Order History
    
    {foreach from=$order_history item="history"}
    
        <br />
    
        {assign var="from_status" value=$history.from_status|fn_get_status_data}
    
        {assign var="to_status" value=$history.to_status|fn_get_status_data}
    
        {$history.transition_date|date_format:'%d/%m/%Y %I:%M:%S %p'}, {$from_status.description} to {$to_status.description}, {if $history.user_id|fn_get_user_name|trim != ''} {$history.user_id|fn_get_user_name} {else} {$history.user_id|fn_get_user_email} {/if}
    
    {/foreach}
    </div>
    <div>

    <br />
    RMA History :
    <br />
    {foreach from=$rma_history item="history"}
        <br />
        {assign var="from_status" value=$history.status_from|fn_get_status_data:R:$history.return_id}
        {assign var="to_status" value=$history.status_to|fn_get_status_data:R:$history.return_id}
    
        {$history.datetime|date_format:'%d/%m/%Y %I:%M:%S %p'}, {$to_status.description}, {$history.user_id|fn_get_user_name}, <span style="color:#dd0000;">{$history.comment}</span>
    {/foreach}
    {* Added By Sudhir end here*}
   
   
    {*Added By Lokesh*}
	{assign var="qadone_detail" value=$order_info.order_id|fn_hp_qadone_details}
    <br />
    <br />
    QA Done History :
    <br />
    {foreach from=$qadone_detail item="qadone_detail"}
        <br />
        {assign var="product" value="|"|explode:$qadone_detail.productqty}
        {$qadone_detail.creationdate|date_format:'%d/%m/%Y %I:%M:%S %p'}, 
        {foreach from=$product item="product"}
        	{assign var="product_id" value=":"|explode:$product}
        		Product:{$product_id.0|fn_hp_get_product_name},
                QTY:{$product_id.1},
        {/foreach}
        
        User:{$qadone_detail.auth|fn_get_user_name}
    {/foreach}
    {*Added By Lokesh end*}
    </div>

</div>
<div style="width:54%;float:left">
	
    <div>
      <!--code by ankur to show cancel request content entered by customer-->
      {assign var="cancel_info" value=$order_info.user_id|fn_get_cancel_info:$order_info.order_id}
      {if !empty($cancel_info)}
      <div style="background:#e5e5e5; padding-bottom:10px">
        <p><strong>{$lang.cancel_info}</strong></p>
        <div style="margin-top: 10px; padding-left: 10px;">
        	<label style="float:left; width:70px"><strong>{$lang.reason}:</strong></label>
            <label style="float:left;">{$cancel_info.reason}</label>
            <div class="clear"></div>
        </div>
        <div style="margin-top: 10px; padding-left: 10px;">
          <label style="float:left; width:70px"><strong>{$lang.comment}:</strong></label>
          <label style="float:left; width:200px">{$cancel_info.comment}</label>
          <div class="clear"></div>
        </div>
      </div>
      {/if}
      <!--code end-->
    	<!--code by arpit gaur shipment history -->
    	<div>
        	<p><strong>Shipment History</strong></p>
            <table style="border-radius:3px;border:1px solid #565656;" cellpadding="2px" cellspacing="0px">
            	
                <tr style="background:#e5e5e5;">
                	<td  style="border-bottom:1px solid #000000;width:25%;" align="center"><strong>Status Update Date</strong></td>
                    <td  style="border-bottom:1px solid #000000;width:25%;" align="center"><strong>Shopclues/Carrier Status</strong></td>
                    <td  style="border-bottom:1px solid #000000;width:25%;" align="center"><strong>Reciever's Name</strong></td>
                    <td  style="border-bottom:1px solid #000000;width:25%;" align="center"><strong>Memo</strong></td>
                </tr>
                {assign var=shipment_counter value=1}
                {assign var="shipment_history" value=$shipments.tracking_number|get_shipment_history:$request_order_id}
                {foreach from=$shipment_history item="history"}
                {if $shipment_counter lte 3}
                <tr>
                	<td align="center">{$history.status_update_date}</td>
                    <td align="center">{$history.sc_status}{if $history.sc_status eq ''}{$history.carrier_status}{/if}</td>
                    <td align="center">{$history.receiver_name}</td>
                    <td align="center">{$history.memo}</td>
                    {assign var=shipment_counter value=$shipment_counter+1}
                    
                </tr>
                {else}
                
                <tr class="expandable_hidable_rows" style="display:none;">
                	<td align="center">{$history.status_update_date}</td>
                    <td align="center">{$history.sc_status}{if $history.sc_status eq ''}{$history.carrier_status}{/if}</td>
                    <td align="center">{$history.receiver_name}</td>
                    <td align="center">{$history.memo}</td>
                    {assign var=shipment_counter value=$shipment_counter+1}
                </tr>
                {/if}
                
                {/foreach}
                
            </table>
            {if $shipment_counter gt 3}
            <div>
                <span id="expand_shipment_history"><a href="javascript:void(0)">Expand All</a></span>
                <span id="hide_shipment_history" style="display:none;"><a href="javascript:void(0)">Hide</a></span>
            </div>
            {/if}
            
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
        </div>
        <!-- by arpit gaur shipment history ends here -->
    
        <div style="margin:8px">
			<div style="float:left; width:115px;height:25px;padding-top:12px;cursor:pointer;font-weight:bold; text-align:center; border:1px solid #eee; background-color:#0A9CCC" class="ord_ex_order_view" onclick="$('#email_detail').hide();$('#causesdetail').show();$(this).css('background-color','#0A9CCC');$('.snd_email_order_view').css('background-color','#fafafa')">Order Exception</div>
			<div style="float:left;width:100px;;height:25px;padding-top:12px;cursor:pointer;font-weight:bold; text-align:center; border:1px solid #eee; background-color:#fafafa; margin-left:5px" class="snd_email_order_view" onclick="$('#email_detail').show();$('#causesdetail').hide();$(this).css('background-color','#0A9CCC');$('.ord_ex_order_view').css('background-color','#fafafa')">Sent Emails</div>
			<div style="clear:both"></div>
		</div> 
        <div id="exccausehistory{$order_info.order_id}">{$order_info.order_id|order_exc_cause_html_ord}</div>
        <a href="javascript:void(0)" onclick="addexceptioncause({$order_info.order_id},'{$order_info.status}')">Add</a>
    </div>
</div>  
<div style="clear:both"></div>

{/if}


<!-- by arpit --><div style="float:left;width:40%;">

<div class="cm-toggle-button">

	<div  class="select-field notify-customer">

		<input type="checkbox" name="notify_user" id="notify_user" value="Y" class="checkbox" />

		<label for="notify_user">{$lang.notify_customer}</label>

	</div>



	<div  class="select-field notify-department">

		<input type="checkbox" name="notify_department" id="notify_department" value="Y" class="checkbox" />

		<label for="notify_department">{$lang.notify_orders_department}</label>

	</div>
    

 


{if $order_info.have_suppliers == "Y"}

	<div class="select-field notify-department">

		<input type="checkbox" name="notify_supplier" id="notify_supplier" value="Y" class="checkbox" />

		<label for="notify_supplier">{if $smarty.const.PRODUCT_TYPE == "MULTIVENDOR"}{$lang.notify_vendor}{else}{$lang.notify_supplier}{/if}</label>

	</div>

{/if}

    <!-- by arpit gaur -->
    
    
    </div>
    
	<div class="buttons-container buttons-bg">

		{include file="buttons/save_cancel.tpl" but_meta="cm-no-ajax" but_name="dispatch[orders.update_details]"}
 
	</div>
    
</div>
{if !"COMPANY_ID"|defined}
 <div style="float:right;width:57%;margin-top:20px;margin-right:2%;display:none;">
    <div><h4>Customer Queries :</h4></div>
    <table >
    {assign var="query_list" value=$smarty.request.order_id|get_customer_queries_for_order_id:$smarty.session.auth.user_id} 
    <tr style="background:#e5e5e5;">
        <td style="border-bottom:1px solid #000000;" align="center"><strong>#</strong></td>
        <td style="border-bottom:1px solid #000000;width:16%;" align="center"><strong>Call Type</strong></td>
        <td style="border-bottom:1px solid #000000;width:20%;" align="center"><strong>Remark</strong></td>
        <td style="border-bottom:1px solid #000000;" align="center"><strong>Status</strong></td>
        <td style="border-bottom:1px solid #000000;" align="center"><strong>Date/Time</strong></td>
        <td style="border-bottom:1px solid #000000;" align="center"><strong>Executive Name</strong></td>
        <td style="border-bottom:1px solid #000000;" align="center"><strong>Executive Name</strong></td>
    </tr>
    {foreach key=key from=$query_list item="query"}
    <tr>
        <td align="center">{$key+1}</td>
        <td align="center">{$query.service_type}</td>
        <td align="center">{$query.remarks}</td>
        <td align="center">{$query.status}</td>
        <td align="center">{$query.date}</td>
        <td align="center">{$query.user_id|fn_get_user_name}</td>
        <td align="center">{$query.follow_up}</td>
    </tr>
    {/foreach}
    </table>
    </div>
 <div style="clear:both;"></div><!-- arpit -->
{/if}      
    </form>
                  <pre>{*$order_info|print_r*}</pre>   
    </br></br>
	
	<!-- Default print label -->
	
	<div id="default_popup" style=" background-color: #FFFFFF; border-color: #E0E0E0 #D4D4D4 #BBBBBB; border-image: none;border-radius: 6px 6px 6px 6px;border-style: solid; border-width: 1px;box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);margin: 0 30px 50px; padding: 7px 21px 3px; position:fixed;
 left:300px;top:100px; display:none;width:400px;height:400px;">
     <h4 align="center">Default print Label</h4> 
     <hr />
	 <p align="right">
     <a href="#" onclick="closePopUpdefault('default_popup');" style="background:url('http://cdn.shopclues.com/images/skin/sprite_png_icon.png') no-repeat scroll -74px -1px transparent; width:30px; height:30px; float:right; position: absolute;right: -10px; top: -10px; width: 30px;z-index: 3333; ">
     </a></p>
	 
	 
	 
	 
	 
	 <form action="{""|fn_url}" method="post" target="_self" name="orders_list_form">
	 {if "COMPANY_ID"|defined}
			
			<div style="padding:5px 0 0 15px;">	
				
				{assign var='top_priority' value=''|fn_get_carrier_selection}
				
				{assign var='default_carr' value=72|fn_get_default_carrier_count}
				
                	<div><input class="cust_carrier_assign" type="radio" name="carrier_assign" value="72" checked="checked" /> Default</div>	
				
				
				{assign var='blk' value=0|fn_get_carrier_priority}
				{if $blk != ""}
                	<div><input  class="cust_carrier_assign" type="radio" name="carrier_assign" value="0" /> Blank</div>	
				{/if}	
				
				{assign var='fdx' value=1|fn_get_carrier_priority}
				{if $fdx != ""}	
                    <div><input  class="cust_carrier_assign" type="radio" name="carrier_assign" value="1"  /> FedEx</div>
				{/if}

				{assign var='ff' value=2|fn_get_carrier_priority}
				{if $ff != ""}	
                    <div><input  class="cust_carrier_assign" type="radio" name="carrier_assign" value="2"  /> First Flight</div>
				{/if}	
				
				{assign var='bd' value=5|fn_get_carrier_priority}
				{if $bd != ""}	
                    <div><input class="cust_carrier_assign" type="radio" name="carrier_assign" value="5"  /> Bluedart</div>
				{/if}	
				
				{assign var='quan' value=9|fn_get_carrier_priority}
				{if $quan != ""}	
                    <div><input  class="cust_carrier_assign" type="radio" name="carrier_assign" value="9"  /> Quantium</div>
				{/if}	
				
				{assign var='aramex' value=10|fn_get_carrier_priority}
				{if $aramex != ""}	
                    <div><input  class="cust_carrier_assign" type="radio" name="carrier_assign" value="10" /> Aramex</div>
				{/if}	
				
				{assign var='dtdc' value=6|fn_get_carrier_priority}
				{if $dtdc != ""}	
                    <div><input  class="cust_carrier_assign" type="radio" name="carrier_assign" value="6" /> DTDC</div>
				{/if}	
				
				{assign var='dehlivery' value=14|fn_get_carrier_priority}
				{if $dehlivery != ""}	
                    <div><input  class="cust_carrier_assign" type="radio" name="carrier_assign" value="14" /> Delhivery</div>
				{/if}	
				
				{assign var='iot' value=18|fn_get_carrier_priority}
				{if $iot != ""}	
                    <div><input  class="cust_carrier_assign" type="radio" name="carrier_assign" value="18" /> IOT</div>
				{/if}	
				
				{assign var='vd' value=19|fn_get_carrier_priority} 
				{if $vd != ""}	
                    <div><input  class="cust_carrier_assign" type="radio" name="carrier_assign" value="19" /> Vdeliver</div>
				{/if}	
					
                </div>
				
				{/if}
				
            	<a id="cust_id" href="?dispatch=orders.print_shipping_label&order_id={$order_info.order_id}&format=pdf" >Bulk Print Shipping Label</a> 
			</form> 
	 
	 
 
	</div> 

     {literal}
     <script>
			  function showPopUpdefault() {
				  var rfp = document.getElementById("default_popup")
				  rfp.style.display = "block"
	  
			  }
			  
			  function closePopUpdefault() {
				  var rfp = document.getElementById("default_popup")
				  rfp.style.display = "none"
			  }
     </script>
    {/literal}
<!--default print label end-->
    
    
    </form>
    <div class="" style="position:fixed;top:200px;width:98%;display:none;margin-left:1%;cursor:url('images/icons/openhand.cur'), move;" id="customerquerypopup">
    <div ><a style="font-size:14px;margin-left:88%;" href="javascript:void(0);" onclick="document.getElementById('customerquerypopup').style.display='none';"><strong>Hide</strong></a></div>
    <iframe id="cust_q_iframe" src="?cust_q_search_keyword=&cust_q_search_order_id={$smarty.request.order_id}&cust_q_search_call_type=All&cust_q_search_status=All&cust_q_search_date=&cust_q_action=search&dispatch=customer_queries.ui&order_call_status=hide&cust_q_search_type=strict" style="postion:absolute;height:300px;width:91%;overflow-x:hidden;"></iframe>
    <div style="clear:both;height:0px;overflow:hidden;"></div>
    </div>
    
    {literal}
    <script>
        $('#customerquerypopup').draggable();
        $(document).keyup(function(e) {
            if (e.keyCode == 27) { $('#customerquerypopup').hide(); }   // esc
});


    </script>
    {/literal}
    <!-- arpit code ends here -->

{if $google_info}

<div class="cm-hide-save-button" id="content_google">

	{include file="views/orders/components/google_actions.tpl"}

<!--content_google--></div>



{/if}

<div class="gift_wrap_frm" id="gift_wrap_frm"  style="display:none; border:1px solid red; background:#F9F9F9; width: 450px; position:fixed; top:25%; left: 40%; height:260px; padding:6px;">
               		<div style="margin:auto; position:relative;">
            			<div class="add_nl_chng_add" style="display:block;">            
                			<form name="gift_wrap" class="gift_popup_block" method="post" action="{""|fn_url}">
                    			<div class="add_nl_chng_add_new" style="text-align:center; font-size:19px;">{$lang.edit_gift_message}</div>
                                <label for="gift_to" class="title_name">{$lang.gift_to}:</label>
                                <input type="text" name="gift_to" id="gift_to" size="45" maxlength="50" value="{if isset($gift_item_message.gift_to)}{$gift_item_message.gift_to}{/if}" class="input-text round_five profile_detail_field cont_nl_inpt_width title_box" maxlength="100" /><br />
                    
                                <label for="gift_from" class="title_name">{$lang.gift_from}:</label>
                                <input type="text" name="gift_from" id="gift_from" size="45" maxlength="50"  value="{if isset($gift_item_message.gift_from)}{$gift_item_message.gift_from}{/if}" class="title_box input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" /><br />

                                <label for="gift_message" class="title_name">{$lang.gift_message}:</label><br />
                               	<textarea name="gift_message" id="gift_message" rows="3" cols="35" class="round_five profile_detail_field" style="max-width:387px; margin-top:5px;  height:103px; max-height:250px; width:387px;" onKeyDown="limitText(this.form.gift_message,this.form.countdown,250);" 
    onKeyUp="limitText(this.form.gift_message,this.form.countdown,250);">{if isset($gift_item_message.message)}{$gift_item_message.message}{/if}</textarea>
                           <span style="float:left; width:100%">You have <input readonly type="text" id="countdown" name="countdown" value="250" style="border:0; float:none; max-width:23px; min-width:10px; background:none; padding:0; margin:0;"> characters left.</font></span>
                        <br />   
                        <div style="float:right;">{include file="buttons/save.tpl" but_name="dispatch[orders.edit_gift_message]" but_text="Submit" but_role="button_main" but_class="box_functions_button nl_btn_blue gift_btn_new"}</div>
                        <a href="javascript:void(0)" class="" style="float:left; margin: 0px 10px;" id="close_gift_form">{$lang.close}</a>
                    </form>
                </div>
                 </div>
                  </div>



{hook name="orders:tabs_extra"}

{/hook}



{/capture}

{include file="common_templates/tabsbox.tpl" content=$smarty.capture.tabsbox active_tab=$smarty.request.selected_section track=true}



{/capture}

{capture name="mainbox_title"}

	{$lang.viewing_order} #{$order_info.order_id} <span class="total">({$lang.total}: <span>{include file="common_templates/price.tpl" value=$order_info.total}</span>{if $order_info.company_id}, {$lang.vendor|lower}:<a href="UniTechCity.php?dispatch=companies.update&company_id={$order_info.company_id}">{$order_info.company_id|fn_get_company_name} </a>, {$order_info.merchant.warehouse_city}, {$order_info.merchant.warehouse_state} : {$order_info.fulfillment.description}{/if})</span>

{/capture}



{include file="common_templates/view_tools.tpl" url="orders.details?order_id="}



{include file="common_templates/mainbox.tpl" title=$smarty.capture.mainbox_title content=$smarty.capture.mainbox tools=$smarty.capture.view_tools extra_tools=$smarty.capture.extra_tools}

{literal}
<script type="text/javascript">
$('#show_gift_wrap_frm').click(function(){
	$('#gift_wrap_frm').toggle();
	document.getElementById('countdown').value = 250 - document.getElementById('gift_message').value.length;
});

$('#close_gift_form').click(function(){
	$('#gift_wrap_frm').toggle();
});
function check_confirm(order_id)
{
	var conf = confirm("Are you sure you want to create a clone?")
	if (conf == true)
	{
		window.location = "UniTechCity.php?dispatch=orders.clone&order_id="+order_id;
	}
}

function limitText(limitField, limitCount, limitNum) {
	if (limitField.value.length > limitNum) {
		limitField.value = limitField.value.substring(0, limitNum);
	} else {
		limitCount.value = limitNum - limitField.value.length;
	}
}
</script>
{/literal}


<!-- adding popup for zendesk api integration by arpit gaur -->
{literal}
<style>
	#pop_up_main_container
	{
		z-index:9999;
		position:fixed;
		top:20%;
		left:25%;
		width:50%;
		border:1px solid black;
		border-radius:5px;
		padding:10px;
		background:#FFFFFF;
	}
	
	#pop_up_main_container h3
	{
		color:#007FA9;
	}
	.lbl_wid_txt{width:100px; display:inline-block; height:40px;}
	.lbl_txtbox_nl{border-radius:4px; -moz-border-radius:4px; border:1px solid #ccc; }
	.pop_zen_disk_new{display:block; margin:5px 0;}
</style>
{/literal}
<div style="display:none;" id="pop_up_main_container">
	<div id="pop_up_title"><h3 style="padding:0; margin:0 0 10px 0 ;">Create a Zendesk Ticket</h3></div>
    <div id="pop_up_message"><span style="display:none; background:#99FF99; border:1px solid #090; border-radius:3px;" id="pop_up_success_message">Zendesk Ticket Created Successfully !!</span><span id="pop_up_failure_message" style="display:none; border:1px solid #F30; border-radius:3px; background:#FF9966;">Zendesk Ticket Creation Failed!! Try Again !!</span></div>
    <div id="pop_up_form_container">
    	<form >
        	<div class="pop_zen_disk_new">
        	<label class="lbl_wid_txt" for="email">Email</label>
            <input id="pop_up_email" class="lbl_txtbox_nl" type="text" value="{$zen_email}" name="email" />
            </div>
            
            <div>
            <label class="lbl_wid_txt" for="orderid">Order ID</label>
            <input type="text" id="pop_up_orderid" name="orderid" value="{$order_info.order_id}" class="lbl_txtbox_nl"/>
            </div>
            
            <div>
            <label class="lbl_wid_txt" for="name">Customer Name</label>
            <input type="text" id="pop_up_name" name="name"  class="lbl_txtbox_nl" value="{$order_info.b_first_name}&nbsp;{$order_info.b_lastname}" />
            </div>
            
            <div>
            <label class="lbl_wid_txt" for="phone">Customer Phone</label>
            <input type="text" id="pop_up_phone" name="phone" class="lbl_txtbox_nl" value="{$order_info.b_phone}" />
            </div>
            
            <div>
            <label class="lbl_wid_txt" for="subject">Issue Type</label>
            <select name="subject" id="subject" class="lbl_txtbox_nl" >
                <option value="">Select</option>
                <option value="Order Status">Order Is Not Shipped</option>
                <option value="Order Is Shipped">Order Is Shipped</option>
                <option value="Order Delivered">Order Delivered</option>
                <option value="Payment Issues">Payment Issues</option>
                <option value="Refund">Refund</option>
                <option value="Returns">Returns</option>
                <option value="Product Query">Product Query</option>
                <option value="Others">Other</option>
             </select>
             </div>
             
             <div>
             <label class="lbl_wid_txt" for="sub_issue">Sub Issue</label>
             <input type="text" id="pop_up_sub_issue" name="sub_issue" class="lbl_txtbox_nl" />
             </div>
             
             <div>
             <label class="lbl_wid_txt" for="message">Message</label>
             <textarea name="message" id="pop_up_message"  class="lbl_txtbox_nl"></textarea>
             </div>
             
             <span style="margin-top:10px;" class="submit-button cm-button-main cm-no-ajax"><input type="submit" id="pop_up_submit_button" value="Create Ticket" /></span>
             
             <span style="margin-top:10px;" class="submit-button cm-button-main cm-no-ajax"><input type="button" id="pop_up_cancel_button" value="Cancel / Hide" /></span>
        </form>
    </div>
</div>

{literal}
	<script>
		$('#pop_up_cancel_button').click(function(){
			$('#pop_up_message').hide();
			$('#pop_up_main_container').hide();
			});
	</script>
    
    <script>
		$('#pop_up_zendesk_button').click(function(){
			$('#pop_up_main_container').show();
			});
	</script>
    
    <script>
		$('#pop_up_submit_button').click(function(){
			
			var email1=$('#pop_up_email').val();
			var orderid1=$('#pop_up_orderid').val();
			var customer_name1=$('#pop_up_name').val();
			var phone1=$('#pop_up_phone').val();
			var subject1=$('#subject').val();
			var sub_issue1=$('#pop_up_sub_issue').val();
			var message1=$('#pop_up_message').val();
			
			$('#pop_up_submit_button').attr("disabled", true);
			
			var ajax=$.ajax({
            type: 'POST',
            url: '/fulfillment/contact_us_api_calls_files/ticket_create.php',
            data: {email:email1,orderid:orderid1,name:customer_name1,phone:phone1,subject:subject1,sub_issue:sub_issue1,message:message1},
            success: function(data){
				
				alert('Ticket Created Successfully!!');
				$('#pop_up_submit_button').removeAttr("disabled");
				$('#pop_up_main_container').hide();
     }
    })
		return false;	
			});
	</script>
{/literal}

{literal}
<script>
				//code by arpit gaur for changing the order status check
				function get_new_shipment_pop_up(mode)
				{
					//alerting on manage screen
					if(mode=='manage')
					alert('You cannot mark an order as Shipped without providing Shipment Details. Please go to order details and add a "New Shipment" to mark this order as shipped.');
					else if(mode=='details')
					//pop up on details page
					{
						$('#create_new_shipment_tab a').click();
					}
					
					return false;
				}
				//code by arpit gaur ends here
</script>
{/literal}

<!-- pop up code ends here -->

{literal}
					<script>
							
						   $(".cust_carrier_assign").change(function(){
						   var data = $(this).val();
						   var new_link=$("#cust_id").attr('href')+'&carrier_assign='+data;
						  $("#cust_id").attr('href',new_link);
						  		 });
					</script>
                {/literal}