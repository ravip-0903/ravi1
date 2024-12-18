{* $Id: order_notification.tpl 9153 2010-03-25 10:02:32Z lexa $ *}

{*by ajay get edd value*}
{assign var="pdd_edd" value=$order_info.order_id|fn_get_pdd_edd}
{*end by ajay get edd value*}

{*by ajay get short tiny url from google api*}

{if $config.enable_short_url}
{assign var="url_to_short" value="`$config.domain_url`/index.php?dispatch=order_lookup.details&order_id=`$order_info.order_id`&email_id=`$order_info.email`"}
	{assign var="short_url" value=$url_to_short|generate_short_url}
	{if empty($short_url) }
        {assign var="short_url" value="`$config.domain_url`/index.php?dispatch=order_lookup.details&order_id=`$order_info.order_id`&email_id=`$order_info.email`"}
        {/if}

{else}
    {assign var="short_url" value="`$config.domain_url`/index.php?dispatch=order_lookup.details&order_id=`$order_info.order_id`&email_id=`$order_info.email`"}
{/if}

{*end by ajay get short tiny url from google api*}


{include file="letter_header.tpl"}
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="77%" align="left" valign="top">
        <table width="72%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="left" valign="top" style="padding-bottom:10px">
            <table  border="0" cellpadding="0" cellspacing="0" width="475" align="left">
              <tbody><tr>
           	{*<td style="text-align: left; font-family: Arial,Helvetica,sans-serif; font-size: 20px;  color:#333333;" valign="top" width="527">
            Thank you for your order</td>*}
        </tr>
        <tr>
           	<td style=" padding-top: 5px; padding-bottom: 5px;" valign="top" width="527"><img src="http://www.shopclues.com/images/order_email/1279658143_divider_short.gif" height="1" width="403" align="left"></td>
        </tr>
       
        <tr>
           	<td style="padding-top: 5px;" valign="top" width="527">
            {*<p style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#545454;" align="left">*}
            	{$lang.dear} {$order_info.firstname},
            
           
			{*<p style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#545454;" align="left">*}
<br /><br />
	{assign var="shipment_details" value=$order_info.shipment_ids|get_shipment_data}
                
                {if $order_status.status == "P" && $order_info.payment_id == $config.suvidha_payment_id}
			{$lang.cbd_pending_lang_var_for_paid_mail|replace:'[order_id]':$order_info.order_id}
			<br />
            	{elseif $order_status.status == "H"}
			       {$lang.pre_order_delivered|replace:'[order_id]':$order_info.order_id|replace:'[short_url]':$short_url}
			<br /><br />
			{if $shipment_details}

				{$lang.shipment_information}:
				{foreach from=$shipment_details item="shipments"}

				{assign var="shipment_track" value=$shipments.carrier|regex_replace:'/[\_\r\t\n\`\~\!\@\#\$\^\%\&\*\(\)]/':''|replace:' ':''|strtolower|get_carriers}

				<br />
					{if $shipments.tracking_number}
						<a href="{$shipment_track}{$shipments.tracking_number}">{$shipments.shipment_id}</a>
					{/if}

				{/foreach}
				Via
				{foreach from=$shipment_details item="shipments"}
					{if $shipments.carrier}
						{$shipments.carrier|replace:'_':' '},
					{/if}
				{/foreach}
		<br />
			{/if}
		<br />
               	 	{$lang.email_customer_order_pre}
                <br /><br />
		{$lang.email_customer_order_delivered_pre}                
                <br /><br />
                {elseif $order_status.status == "P" && $order_info.payment_id != "6" && $order_info.company_id != "34707" && $order_info.company_id != "67431" }
			{$lang.pre_order_paid|replace:'[order_id]':$order_info.order_id|replace:'[short_url]':$short_url}
		        <br /><br />

		{elseif $order_status.status == "O"}
			{$lang.header_order_cod_pre}
			<br />
                {elseif $order_status.status == $config.cbd_pending_status}
			{$lang.cbd_pending_lang_var_mail|replace:'[order_id]':$order_info.order_id}
			<br />
		{elseif $order_status.status == "Q"}
			{$lang.pre_order_cod_confirm|replace:'[order_id]':$order_info.order_id|replace:'[short_url]':$short_url}
                <br /><br />

		{elseif $order_status.status == "I"}
			{$lang.pre_order_canceled|replace:'[order_id]':$order_info.order_id|replace:'[short_url]':$short_url}
                <br /><br />
		{elseif $order_status.status == "M"}
			{$lang.pre_order_refunded|replace:'[order_id]':$order_info.order_id|replace:'[short_url]':$short_url}
                <br /><br />
			{$lang.any_query_write_us}
		{elseif $order_status.status == "A" && $order_info.payment_id != "6"}
                  We are very happy to inform you that your {$lang.order}&nbsp;<a href="{$short_url}">#{$order_info.order_id}</a> has been shipped.

			{if $shipment_details.0.carrier && $shipment_details.0.tracking_number}
				{assign var="is_url_tracking" value=$shipment_details.0.carrier|fn_get_tracking_url}
				{if !empty($is_url_tracking) && !empty($is_url_tracking.tracking_url)}
					{if $is_url_tracking.is_url_trackable == 1}
						{$lang.is_trackable_mail|replace:"[CARRIER_NAME]":$is_url_tracking.carrier_name|replace:"[TRACKING_URL]":"<a target='_blank' href=`$is_url_tracking.tracking_url``$shipment.tracking_number`>`$shipment_details.0.tracking_number`</a>"}
					{elseif $is_url_tracking.is_url_trackable == 0}

						{$lang.is_not_trackable_mail|replace:"[CARRIER_NAME]":$is_url_tracking.carrier_name|replace:"[TRACKING_NUMBER]":$shipment_details.0.tracking_number|replace:"[TRACKING_URL]":"<a target='_blank' href=`$is_url_tracking.tracking_url`>`$shipment_details.0.tracking_number`</a>"}
					{/if}

				{else}
					{$lang.not_tracking_mail|replace:"[CARRIER_NAME]":$shipment_details.0.carrier|replace:"[LABEL]":$lang.tracking_num|replace:"[TRACKING_NUMBER]":$shipment_details.0.tracking_number}
				{/if}
				<br/><br/>
				You can log into your account on <a href='https://shopclues.com/myaccount'>ShopClues.com</a> to track your orders status.
			{/if}
				<br /><br />
				{$lang.email_customer_order_pre}
				<br /><br />
			
		{elseif $order_status.status == "A" && $order_info.payment_id == "6"}

			Your {$lang.order}&nbsp;<a href="{$short_url}">#{$order_info.order_id}</a> has been shipped through

				{foreach from=$shipment_details item="shipments"}
						{$shipments.carrier|replace:'_':' '}
				{/foreach} (Tracking Id -
				{assign var="is_url_tracking" value=$shipment_details.0.carrier|fn_get_tracking_url}
				<a target='_blank' href={$is_url_tracking.tracking_url}>{$shipment_details.0.tracking_number}</a>
				).
                <br /><br />
                {$lang.pre_order_cod_shipped}
                <br /><br />

                {$lang.email_customer_order_pre}
                <br /><br />
                
                {* by ajay for new status COD confirmed-PGW failiure *}
				{elseif $order_status.status == "93"}
				<br/><br/>
				Thank you for shopping at <a href="http://www.shopclues.com" target="_blank">ShopClues.com</a>.
				<br/><br/>
				We apologize for the inconvenience because the payment for your order <a href="{$short_url}">{$order_info.order_id}</a> could not be processed. Your order has been confirmed for “Cash on Delivery”. You can now pay for it when the product is delivered to you. 
				<br/><br/>
				You will receive an SMS and email from us with tracking details as soon as it is dispatched from our center.
				<br/><br/>
				{* end by ajay *}
				
				{* by ajay to set different mail to sunday_fle_market merchant on PAID STATUS *}	       
		        {elseif $order_status.status == "P" && $order_info.company_id == "34707"}
			    Greetings from ShopClues!
			    <br/><br/>
			    Thankyou for submitting your deal for the ShopClues Sunday Flea Market.
                We have received your payment for this order <a href="{$short_url}">{$order_info.order_id} </a>. Please note that this payment is the registration fee for running your deal in the Sunday Flea Market.
				<br /><br />
				We will evaluate your deal and if it is accepted, you will receive a confirmation on the same.
				<br/><br/>

                       {* By Ajay to set different mail for Clues Club Membership on PAID STATUS *}
                       {elseif $order_status.status == "P" && $order_info.company_id == "67431"}
                       {$lang.club_membership_mail}
                       {* End Clues Club Membership mail content *}

                       {* By Ajay for COD Auto Confirm *}

                       {elseif $order_status.status == "92"}
			<br/><br/>
			Thank you for shopping at <a href="http://www.shopclues.com" target="_blank">ShopClues.com</a>.
			<br/><br/>
			Your order <a href="{$short_url}"> {$order_info.order_id}</a> has been confirmed for “Cash on Delivery”. You will receive an SMS and email from us with tracking details as soon as it is dispatched from our center.
			<br/><br/> 

		      {* end by Ajay *}
				
                {elseif $order_status.status == "F"}
                
                Thank you for placing an order with ShopClues.com.  Unfortunately your Order <a href="{$short_url}">#{$order_info.order_id}</a> has failed to process. <br/> <br />
                An order typically fails due to payment issues-  you might have entered incorrect information, or your bank might have declined transaction due to an error on that part. <br/><br/>
                
                We encourage you to retry confirming your order by going to your #{$order_info.order_id} by going to <a href="http://shopclues.com/index.php?dispatch=orders.details&order_id={$order_info.order_id}"> Order detail</a> and clicking on "Pay Now" button at the bottom of this screen. <br/><br/>
                
                Please note, if you are having trouble paying by Credit card or NetBanking, you can always opt for "Cash on Delivery".<br/><br/>
                
                {else}
                    This email is to confirm that status of your {$lang.order}&nbsp;<a href="{$short_url}">#{$order_info.order_id}</a> has been changed to {$order_status.description}.
                
                <br /><br />
                
                {$lang.email_customer_order_pre}
                <br /><br />
                
                {/if}
                
                {*{$order_status.email_header|unescape}<br /><br />*}
                
                {assign var="order_header" value=$lang.invoice}
                {if $status_settings.appearance_type == "C" && $order_info.doc_ids[$status_settings.appearance_type]}
                    {assign var="order_header" value=$lang.credit_memo}
                {elseif $status_settings.appearance_type == "O"}
                    {assign var="order_header" value=$lang.order_details}
                {/if}
            {*</p>*}
            </td>
        </tr>
       
		
		</tbody>
           </table>
           </td>
          </tr>
          {if $order_status.status == "P" || $order_status.status == "O" || $order_status.status == "C"}
          <tr>
            <td>
            {include file="orders/order_promotion.tpl"}
            </td>
          </tr>
          {/if}
          <tr>
            <td align="left" valign="top" style="padding:10px 5px; background-color:#FFF">

            	{include file="orders/notification_invoice.tpl"}
            </td>
          </tr>
          <tr>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td align="left" valign="top"><table  border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
        <tbody>
        <tr>
          <td style="padding-top: 5px; padding-bottom: 5px;" valign="top" width="524"><img src="http://www.shopclues.com/images/order_email/1279658143_divider_short.gif" height="1" width="403"></td>
        </tr>

        <tr>
           	<td valign="top" width="524">
		{if $order_status.status == "P" || $order_status.status == "O" || $order_status.status == "92"}
      {$lang.will_receive_email_on_shippied}
        {if $config.you_may_like_tm_email}
          {include file="you_may_like_tm.tpl"}
        {/if}
    {/if}
<br />

{if !empty($status_settings.buyer_email_text) }
{$status_settings.buyer_email_text|unescape|replace:'[TRACK_ORDER_URL]':$short_url}
{/if}
{$lang.footer_mail_links}



            <p style="font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#333333;" align="left"> We look forward to seeing you again at <a href="http://www.shopclues.com" target="_blank" style="color: rgb(75, 132, 172);">ShopClues</a>.</p>

	</td>
        </tr>
        <tr>
           	<td style="padding:10px 0px;" valign="top" width="524"><a href="https://shopclues.com/login?return_url=index.php%3Fdispatch%3Dprofiles.update" target="_blank"><img src="http://www.shopclues.com/images/order_email/1279658099_btn_help.gif" alt="Visit Help Online" height="22" border="0" width="151" align="left"></a></td>
        </tr>
        </tbody></table></td>
          </tr>
         
        </table></td>
        <td width="2%" align="left" valign="top" style="border-left: 1px solid rgb(207, 207, 207); border-collapse: collapse;">&nbsp;</td>
        <td width="21%" align="left" valign="top">
        	{include file="orders/order_right_panel.tpl"}
        </td>
      </tr>
    </table>
  
  
{include file="letter_footer.tpl"}
