{* $Id: step_four.tpl 12479 2011-05-18 08:54:10Z alexions $ *}
{literal}
  <script type="text/javascript">
  $(document).ready(function(){
{/literal}
        {if $cart.multiple_shipping_addresses == 1}
        {assign var="update_flag" value="1"}
        {/if}
{literal}
});
</script>
{/literal}

<div class="step-container{if $edit}-active{/if}" id="step_four">
	<h2 class="step-title{if $edit}-active{/if}">
		<span class="float-left">{if $profile_fields.B || $profile_fields.S}4{else}3{/if}.</span>
		
		<span class="title">{$lang.review_and_place_order}</span>
		
		{if $edit && $cart|fn_allow_place_order && !$iframe_mode}
			<span class="float-right">
				{include file="buttons/place_order.tpl" but_onclick="$('#place_order').click();" but_role="big"}
			</span>
		{/if}
	</h2>

	<div id="step_four_body" class="step-body{if $edit}-active{/if} {if !$edit && !$complete}hidden{/if}">
		<div class="clear">
			{if $edit}
				{if $settings.General.checkout_style == "multi_page"}
					{* Display summary customer info (payment, shipping, etc...) *}
					{* Shipping/billing information *}
					{*{if $settings.General.address_position == "billing_first"}
						{assign var="first_section" value="S"}
						{assign var="first_section_text" value=$lang.shipping_address}
						{assign var="sec_section" value="B"}
						{assign var="sec_section_text" value=$lang.billing_address}
						{assign var="ship_to_another_text" value=$lang.text_billing_same_with_shipping}
						{assign var="body_id" value="sa"}
					{else}
						{assign var="first_section" value="S"}
						{assign var="first_section_text" value=$lang.billing_address}
						{assign var="sec_section" value="B"}
						{assign var="sec_section_text" value=$lang.shipping_address}
						{assign var="ship_to_another_text" value=$lang.text_ship_to_billing}
						{assign var="body_id" value="ba"}
					{/if}*}
                    
                    {if $settings.General.address_position == "billing_first"}
						{assign var="first_section" value="B"}
						{assign var="first_section_text" value=$lang.billing_address}
						{assign var="sec_section" value="S"}
						{assign var="sec_section_text" value=$lang.shipping_address}
						{assign var="ship_to_another_text" value=$lang.text_billing_same_with_shipping}
						{assign var="body_id" value="ba"}                        
					{else}						
                        {assign var="first_section" value="B"}
						{assign var="first_section_text" value=$lang.shipping_address}
						{assign var="sec_section" value="S"}
						{assign var="sec_section_text" value=$lang.billing_address}
						{assign var="ship_to_another_text" value=$lang.text_ship_to_billing}
						{assign var="body_id" value="sa"}
					{/if}
					
					<table width="100%" cellpadding="0" cellspacing="0" border="0" class="margin-top">
					<tr valign="top">
					{if $profile_fields[$sec_section]}
						<td width="45%">
                                                    {if $cart.multiple_shipping_addresses==1}
                                                       <strong class="float-left" style="color:#000;">{$sec_section_text}: &nbsp;</strong>{$lang.ship_to_mul_add}    
                                                    {else}
                                                   {include file="views/profiles/components/step_profile_fields.tpl" section=$sec_section text=$sec_section_text}
                                                   {/if}
                                                </td>
					{/if}
					<td width="10%">&nbsp;</td>
						<td width="45%">
						{if $profile_fields[$first_section]}
								{if $cart.multiple_shipping_addresses==1 || $cart.ship_to_another}
								{include file="views/profiles/components/step_profile_fields.tpl" section=$first_section text=$first_section_text}                                    
								{else}
									<div class="step-complete-wrapper clear">
										<strong class="float-left" style="color:#000;">{$first_section_text}: &nbsp;</strong>
										<p class="no-padding overflow-hidden" style="display:inline;">{$ship_to_another_text}</p>
                                        <span><a href="index.php?dispatch=checkout.checkout&edit_step=step_two"
									      title="Change Billing Address">Change</a></span>
									</div>
                                    
								{/if}
						{/if}
						</td>
					</tr>
					</table>
					
					<table width="100%" cellpadding="0" cellspacing="0" border="0" class="cc-infobox">
					<tr valign="top">
						<td width="45%">
							{* Payment information *}
							<div class="step-complete-wrapper">
								{if $cart.payment_id}
									<strong style="color:#000;">{$lang.payment_method}:</strong> &nbsp;{*$payment_info.payment*}
                                    {if $cart.payment_details.payment_gateway_id != '6' && $cart.payment_details.payment_gateway_id != $config.suvidha_payment_id}{$cart.payment_details.payment_type} : {/if}{$cart.payment_details.payment_option} 
                                    {if $cart.emi_id != ''} {$cart.payment_details.payment_type} {$cart.payment_details.name}{/if}
									{if $cart.extra_payment_info.card_number}
										{foreach from=$credit_cards item="card"}
											{if $card.param == $cart.extra_payment_info.card}
												{$card.descr}:&nbsp;{$cart.extra_payment_info.secure_card_number}&nbsp;{$lang.exp}:&nbsp;{$cart.extra_payment_info.expiry_month}/{$cart.extra_payment_info.expiry_year}
											{/if}
										{/foreach}
									{/if}
									<span><a href="index.php?dispatch=checkout.checkout&edit_step=step_three" title="Change Payment Method">Change</a></span>
                                                                        {if $cart.payment_details.payment_gateway_id == $config.suvidha_payment_id}{$lang.cbd_lang_var_sfour}{/if}
								{else}
									{$lang.text_no_payments_needed}
								{/if}
							</div>
						</td>
						<td width="10%">&nbsp;</td>
						<td width="45%">
							{* Shipping information *}
							<div class="step-complete-wrapper">
								<!--<strong>{$lang.shipping_method}: &nbsp;</strong>-->
								{*{if $cart.shipping_required == true}
									{include file="views/checkout/components/shipping_rates.tpl" no_form=true display="show"}
								{else}
									{$lang.free_shipping}
								{/if}*}
							</div>
						</td>
					</tr>
					</table>
				{/if}
			
				<table class="table review margin-top" width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<th colspan="2" class="left">{$lang.products_in_your_order}{$lang.forth_to_three_lang}<a href="{"checkout.checkout&edit_step=step_three"|fn_url}" style="color:#007AC0; font-weight: 600;">{$lang.change}</a></th>
                                            {if $cart.multiple_shipping_addresses != 1}	
                                            {if isset($cart.gifting) && $cart.gifting.gift_it == 'Y' &&  $cart.giftable == 'Y'}
                                                <th class="no_mobile no_tablet" align="left">{$lang.gifting_heading}</th>
                                            {/if}
                                            {/if}
					</tr>
					{hook name="checkout:summary_products_row"}
					{*{foreach from=$cart_products key="key" item="product"}
						{if !$cart.products.$key.extra.parent}
						<tr {cycle values=",class=\"table-row\""}>
							<td width="77%">
								{hook name="checkout:summary_products"}
									<a href="{"products.view?product_id=`$product.product_id`"|fn_url}" class="product-title">{$product.product|unescape}</a>
									{if $product.product_code}<p class="sku">{$lang.code}: {$product.product_code}</p>{/if}
									{include file="common_templates/options_info.tpl" product_options=$product.product_options no_block=true}
								{/hook}
							</td>
							<td width="10%" class="center"><strong>{$product.amount}&nbsp;x&nbsp;{include file="common_templates/price.tpl" value=$product.display_price}</strong>&nbsp;=&nbsp;{include file="common_templates/price.tpl" value=$product.display_subtotal class="price"}</td>
						</tr>
						{/if}
					{/foreach}*}
					<tr {cycle values=",class=\"table-row\""}>
						<td valign="top" width="77%">
							{include file="views/checkout/components/cart_content_popup.tpl" location="checkout"}	
						</td>
                            {if $cart.multiple_shipping_addresses != 1}
                            {if isset($cart.gifting) && $cart.gifting.gift_it == 'Y'}
                        	<td class="no_mobile no_tablet" valign="top" colspan="2">
                            	{if  $cart.giftable == 'Y' && $cart.gifting.gift_it == 'Y' && $cart.gifting.to != "" && $cart.gifting.from != "" && $cart.gifting.msg != ""}
                                    <div class="gift_area" style=" margin: 16px 0 0; width:300px; ">
                                        <p class="gift_area_to">{$lang.gift_to} : {$cart.gifting.to}</p>
                                        <p class="gift_area_msg">{$lang.gift_msg} : {$cart.gifting.msg}</p>
                                        <p class="gift_area_frm">{$lang.gift_from} : {$cart.gifting.from}</p>
                                        <div class="clearboth"></div>
                                        <a href="index.php?dispatch=checkout.checkout&edit_step=step_three" title="Change">Change</a>
                                    </div>
                                {elseif $cart.gifting.gift_it == 'Y' &&  $cart.giftable == 'Y'}
                                    <div class="gift_area" style="background:none; margin: 16px 0 0; width:184px;">
                                        <span>{$lang.you_opted_for_only_wrapping}</span>
                                        <div class="clearboth"></div>
                                        <a href="index.php?dispatch=checkout.checkout&edit_step=step_three" title="Change">Change</a>
                                    </div>
                                {/if}
                            </td>
                        {/if}
                        {/if}
					</tr>
					{/hook}
				</table>                
				
				{if $cart|fn_allow_place_order}
					<form action="{""|fn_url}" method="post" name="summary_form" id="summary_form">
					<div class="clear" style="margin-top:10px">
						{*include file="views/checkout/components/checkout_totals_info.tpl"*}
						{if !$iframe_mode}
							{include file="views/checkout/components/customer_notes.tpl"}
						{/if}

                                                
						
                        {if $cart.payment_id == '6' && $cart.user_data.verified==0 }
                        {if ($config.display_otp_captcha && $config.isResponsive=='1') || $config.isResponsive=='0'}<div id="cod_confirmation">
							{if $smarty.session.cod_verification_code >0}
                            
							<div>
								   							   
								   <div id="otp" style=" padding:10px; background-color:#e5f7ff; height:59px;">
								   <div style="width:100%; font:bold 13px trebuchet ms; color:#333;">
                                   <div>
                                   {$lang.cod_pwd_input}
                                   </div>
                                   
                                   <div class="hoverbox_code">                                   
                                   ?                                                             
                                   <div class="hoverbox_code_text">{$lang.cod_hovertext}</div>                                            
                                   </div>
                                   
                                   </div>
								   <input name="one_time_pwd" type="text" size="6" maxlength="4" style="float:left; width:60%; padding:7px 5px; outline:none; border-radius:5px; border:1px solid #bfebff; margin-top:5px;">
								   </div>                                   
								   
							                         							   
							<div class="captcha" style="float:right; display:none; background-color:#e5f7ff; padding:10px; margin-top:1px;" id="captcha">
							   {include file="common_templates/image_verification.tpl" id="step_four" destination="step_four"}
							   </div>
                               
                               <div style=" background-color:#d9f3ff; padding:9px 2px; margin-top:1px;">
								   <input type="checkbox" id="chkotp" value="otp" onclick="change_input(this);" >
								   <label style=" font:11px verdana; color:#205670; margin-left:5px;">{$lang.cod_chk}</label>
								   </div>
                                   
                                   </div>
                               
                           {else}
							<div class="captcha" style="float:right;" id="captcha">
							   {include file="common_templates/image_verification.tpl" id="step_four" destination="step_four"}
							   </div>
						   {/if}
                           </div>{/if}
                       {/if}  
                       
{include file="views/checkout/components/checkout_totals_custom.tpl" location="four_step"}
                       
					</div>
					
					{if $cart_agreements || $settings.General.agree_terms_conditions == "Y"}
						<script type="text/javascript">
						//<![CDATA[
						lang.checkout_terms_n_conditions_alert = '{$lang.checkout_terms_n_conditions_alert|escape:javascript}';
						{literal}
						function fn_check_agreement(id)
						{
							if (!$('#' + id).attr('checked')) {
								return lang.checkout_terms_n_conditions_alert;
							}

							return true;
						}
						{/literal}
						
						{if $iframe_mode}
							{literal}
							function fn_check_agreements()
							{
								if ($('#summary_form input:checkbox:checked').length > 0 && $('#summary_form input:checkbox:checked').length == $('#summary_form input:checkbox').length) {
									$('#payment_method_iframe').addClass('hidden');
								} else {
									$('#payment_method_iframe').removeClass('hidden');
								}
							}
							{/literal}
						{/if}
						//]]>
						</script>

						<table width="100%" cellpadding="3" cellspacing="0" border="0">
						<tr valign="top">
							<td>
							{if $settings.General.agree_terms_conditions == "Y"}
							<div class="form-field margin-top">
								{hook name="checkout:terms_and_conditions"}
								
								<label for="id_accept_terms" class="valign cm-custom (check_agreement)"><input type="checkbox" id="id_accept_terms" name="accept_terms" value="Y" class="checkbox valign" {if $iframe_mode}onclick="fn_check_agreements();"{/if} />{$lang.checkout_terms_n_conditions}</label>
								{/hook}
							</div>
							{/if}
							{if $cart_agreements}
							<div class="form-field">
								{hook name="checkout:terms_and_conditions_downloadable"}
								
								<label for="product_agreements" class="valign cm-custom (check_agreement)"><input type="checkbox" id="product_agreements" name="agreements[]" value="Y" class="valign checkbox"  {if $iframe_mode}onclick="fn_check_agreements();"{/if}/>{$lang.checkout_edp_terms_n_conditions}</label>{include file="buttons/button.tpl" but_text=$lang.license_agreement but_role="text" but_id="sw_elm_agreements" but_meta="cm-combination"}
								{/hook}
								<div class="hidden" id="elm_agreements">
								{foreach from=$cart_agreements item="product_agreements"}
									{foreach from=$product_agreements item="agreement"}
									<p>{$agreement.license|unescape}</p>
									{/foreach}
								{/foreach}
								</div>
							</div>
							{/if}
							</td>
							<td valign="bottom">
						{/if}
						
						{if !$iframe_mode}
							<div class="buttons-container right clear-both box_functions">
								{include file="buttons/place_order.tpl" but_name="dispatch[checkout.place_order]" but_role="big" but_id="place_order"}
							</div>
						{/if}
						
						{if $cart_agreements || $settings.General.agree_terms_conditions == "Y"}
							</td>
						</tr>
						</table>
					{/if}
					
					{if $auth.act_as_user}
						<div class="select-field">
							<input type="checkbox" id="skip_payment" name="skip_payment" value="Y" class="checkbox" />
							<label for="skip_payment">{$lang.skip_payment}</label>
						</div>
					{/if}
                                        
                                        
					</form>
					
					{if $iframe_mode}
						<div class="payment_method_iframe_box">
							<iframe width="100%" height="820" id="order_iframe_{$smarty.const.TIME}" src="{"checkout.process_payment"|fn_url:$smarty.const.AREA:'checkout'}" style="border: 0px" frameBorder="0" ></iframe>
							{if $cart_agreements || $settings.General.agree_terms_conditions == "Y"}
							<div id="payment_method_iframe" class="payment_method_iframe">
								<div class="payment_method_iframe_label">
									<div class="payment_method_iframe_text">{$lang.checkout_terms_n_conditions_alert}</div>
								</div>
							</div>
							{/if}
						</div>
					{/if}
				{else}
					{if $cart.shipping_failed}
					<p class="error-text center">{$lang.text_no_shipping_methods}</p>
					{/if}
					{if $cart.amount_failed}
					<p class="error-text center">{$lang.text_min_order_amount_required}&nbsp;<strong>{include file="common_templates/price.tpl" value=$settings.General.min_order_amount}</strong></p>
					{/if}

					{if $settings.General.checkout_style == "multi_page"}
					<div class="buttons-container center">
						{include file="buttons/continue_shopping.tpl" but_href=$continue_url|default:$index_script but_role="action"}
					</div>
					{/if}
				{/if}
			{/if}
		</div>
        {$lang.fourth_step_footer_text}
	</div>
<!--step_four--></div>

{literal}
  <script>
  function change_input(chk){
	 
	  if(chk.checked){
	   $('#otp').hide();
	   $('#captcha').show();
	  }else{	 
	   $('#otp').show();
	   $('#captcha').hide();
	  }	
  }
    
  </script>
                          <style type="text/css">
                            .hoverbox_code
							{
								float:right;
								display:inline;
								background-color:#007ac0;
								border-radius:20px;
								color:#fff !important;
								width:20px;
								height:20px;
								text-align:center;
								vertical-align:middle;
								}
							.hoverbox_code .hoverbox_code_text
							{
								display:none;
								position:absolute;
								z-index:100;
								background-color:#fff;
								border:1px solid #444;
								padding:5px;
								border-radius:5px;								
								text-align:left;
								vertical-align:top;
								margin-left:-13px;										
								}
							.hoverbox_code:hover .hoverbox_code_text
							{
								display:inline;								
								
								}
                            </style>
  {/literal}

