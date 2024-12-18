{*assign var="common_payment_method" value=$cart.products|get_payment_methods*}

{assign var="cod_allowed" value=$cart.products|check_for_cod}
{assign var="has_gift_certificate" value=$cart.gift_certificates|count}
{assign var="payment_types" value=""|get_payment_types}
{assign var="min_emi_amount" value=$config.emi_min_amount|default:"4000"}
{if $settings.General.checkout_style == "multi_page"}
    {assign var="result_ids" value="payment_summary,shipping_rates_list,checkout_cart,checkout_totals,checkout_steps,cart_status,step_three"}
{else}
    {assign var="result_ids" value="checkout_steps,checkout_cart"}
{/if}



<div class="clearboth"></div>


<input type="hidden" id="selectedtab" value="{if isset($cart.payment_details.position)} {$cart.payment_details.position} {else}{$smarty.cookies.pt}{/if}" />
{if isset($cart.payment_details.position)}
        <input type="hidden" name="dpt" id="dpt" value="{$cart.payment_details.position}" />
{/if}

<div class="paymentOptionsBox">
    <div id="vTabs" class="paymentTabs">
        <ul class="mainTabDiv">
        	{assign var="cnt" value="0"}
            {foreach from=$payment_types item="payment_type"}
            	{assign var="cnt" value=$cnt+1}
                
            	<li id="payment_tab_li_{$cnt}" class="ptab{if isset($cart.payment_details.position) && $cart.payment_details.position == $cnt} selected {else}{if $smarty.cookies.pt==$cnt && !isset($cart.payment_details.position)} selected {/if}{/if}">
                    <a href="javascript:changepaymenttab({$cnt})">
                    	{$payment_type.name}
                    </a>
            	</li>
           
            {/foreach}  
        </ul>
	</div>

    <div class="paymentTabDetail" style="min-height:auto; width:296px;">
    	{assign var="cnt" value="0"}
    	{foreach from=$payment_types item="payment_type"} 
        	{assign var="cnt" value=$cnt+1}
            {assign var="payment_options" value=$payment_type.payment_type_id|get_payment_options}
            <div style="{if isset($cart.payment_details.position) && $cart.payment_details.position == $cnt} display:block; {else}{if $smarty.cookies.pt==$cnt && !isset($cart.payment_details.position)}display:block;{else}display:none;{/if}{/if} padding-bottom:55px; {if $payment_type.payment_type_id == '6' && $cart.payment_failed_status == '1'}padding-bottom:125px;{elseif $payment_type.payment_type_id == '6'}padding-bottom:90px;{/if} min-height:220px;" id="paymentbody-{$cnt}" class="paymentTabDetail_body">
            	<h3>{$pay_by} {$payment_type.name}</h3> 
                {if ($payment_type.payment_type_id == '4') && $cod_allowed == 'YES' && $has_gift_certificate==0}
	                {if $payment_type.short_text_top != ''}
	                    {if !$smarty.session.cart.multiple_shipping_addresses}
                            <div class="paymentTabDetail_message" style="margin-top:0;">{$payment_type.short_text_top}</div>
                            {else}
                                {$lang.multiaddress_no_cod}
                             {/if}
	                {/if}
                {/if}
                {if $payment_type.payment_type_id != '4' && $payment_options|count > 0}
                	{if $payment_type.short_text_top != ''}
	                    <div class="paymentTabDetail_message" style="margin-top:0;">{$payment_type.short_text_top}</div>
	                {/if}
                {/if}
                {if $payment_options|count > 0}
                		{if ($payment_type.payment_type_id != '4') && ($payment_type.payment_type_id != '6') && ($payment_type.payment_type_id != '8')}
							{if $has_gift_certificate > 0 && $payment_type.payment_type_id|in_array:$config.hide_payment_type_id_on_gc}
								 <select name="payment_options" class="paymentTabDetail_selectbox" style="display:none" >
							{else}
								<select name="payment_options" class="paymentTabDetail_selectbox" onchange="select_payment(this.value,'')" onblur="select_payment(this.value,'')">

							{/if}
                                <option value="">Select</option>
                                {foreach from=$payment_options item="payment_option"}
									{if $has_gift_certificate >0 && $payment_option.payment_option_id|in_array:$config.hide_payment_option_id_on_gc }
									{else}
                                    <option value="{$payment_option.payment_option_id}" id="payment_method_{$payment_option.payment_option_id}" {if $cart.payment_option_id == $payment_option.payment_option_id}selected="selected"{/if}>{$payment_option.name}</option>
									{/if}
								{/foreach}
                            </select>
                        {/if}
                        
                        {if $payment_type.payment_type_id == '6'}
                            {if $cart.total >= $min_emi_amount}                        
                            {if $has_gift_certificate > 0 && $payment_type.payment_type_id|in_array:$config.hide_payment_type_id_on_gc}
								 <select name="payment_options" class="paymentTabDetail_selectbox" style="display:none" >
							{else}
								<select name="payment_options" class="paymentTabDetail_selectbox" onchange="show_emi_option(this.value)">

							{/if}
                                <option value="">Select</option>
                                {foreach from=$payment_options item="payment_option"} {$cart.payment_option_id}        
                                    {if $has_gift_certificate > 0 && $payment_option.payment_option_id|in_array:$config.hide_payment_option_id_on_gc }
									{else}
										<option value="{$payment_option.payment_option_id}" id="payment_method_{$payment_option.payment_option_id}" {if $cart.payment_option_id == $payment_option.payment_option_id}selected="selected"{/if}>{$payment_option.name}</option>
									{/if}
								{/foreach}
                            </select>
                            <div class="clearboth height_five"></div>
                            {foreach from=$payment_options item="payment_option"}         
                                {assign var="payment_emi_options" value=$payment_option.payment_option_id|get_emi_options}
                                <div id="emi_{$payment_option.payment_option_id}" class="emi_block" style="{if $smarty.cookies.opt==$payment_option.payment_option_id && $cart.payment_option_id == $payment_option.payment_option_id}display:block;{else}display:none;{/if} font:11px verdana; color:#333; height:auto;">
                				
                               <div style="float:left; width:98%; background-color:#eee; padding:5px 0px;">
                                   <div style="float:left; width:88px; margin-left:22px; color: #333; font-weight:bold;">{$lang.emi_plan}</div>
                                   <div style="float:left; text-align:right; color: #333; font-weight:bold;">{$lang.installment}<span style="color:red;font-size: 9px;">*</span></div>
                                   <div style="float:right; text-align:right; margin-right:10px; width:65px; font-weight:bold; color:#333;">{$lang.interest}<span style="color:red;font-size: 9px;">*</span></div>                                  
                               </div>
                                	
                                    {foreach from=$payment_emi_options item="payment_emi"}
									<div style="float:left; width:100%; color:#333; font:11px verdana; margin-top:5px;">                                    
                                    
                                    <input type="radio" id="payment_method_emi_{$payment_emi.id}" class="radio" onclick="select_payment({$payment_option.payment_option_id},{$payment_emi.id})" name="payment_emi_option" value="{$payment_emi.id}" {if $cart.emi_id == $payment_emi.id} checked="checked"{/if} style="float:left; width:15px; margin-top:2px;"/>
                                    <div style="float:left; width:88px; color: #333;">
                                    {$payment_emi.name}
                                    </div>
                                    {if $smarty.now|date_format:'%Y-%m-%d' <= $payment_emi.promo_end_date}
                                        {assign var="emi_fees" value=$payment_emi.promo_fee}
                                    {else}
                                        {assign var="emi_processing_fee" value=$cart.total-$cart.emi_fee}
                                        {assign var="emi_processing_fee" value=$emi_processing_fee*$payment_emi.fee}
                                        {assign var="emi_processing_fee" value=$emi_processing_fee/100}
                                        {assign var="emi_fees" value=$emi_processing_fee}
                                    {/if}

                                    <div style="float:left; width:50px; text-align:right; color: #333;">
                                	
                                    {assign var="cart_total" value=$cart|fn_cart_total}                                    
                                    {assign var="total_amount" value=$cart_total+$emi_fees}
                                    {assign var="installment" value=$total_amount|fn_calculate_emi:$payment_emi.interest_rate:$payment_emi.period}
                                    {$installment|ceil|number_format}
                                    </div>
                                
                                	<div style="float:right; text-align:right; margin-right:17px; color: #333; width:70px;">
                                    {assign var="interest" value =$installment*$payment_emi.period-$total_amount}
                                    {assign var="interest" value=$interest|ceil|number_format}
                                    {$interest}
                                    </div>
                                    <div style="clear:both;"></div>
                                    <div style="float:left; width:94%; border-bottom:1px solid #ececbb; background-color:#ffffcc; color:#000; padding:4px 5px; margin-top:5px; font:9px verdana;">
                                    
                                    {assign var="emi_fees" value=$emi_fees|number_format}
                                    {if $emi_fees > 0}
                                    {$lang.we_charge_emi_fee|replace:'[emi_fee]':$emi_fees}
                                    {/if}
                                    {if $payment_emi.interest_rate != '0' && $emi_fees > 0}
                                    <br>
                                    {/if}
                                    {if $payment_emi.interest_rate != '0'}
                                    {$lang.bank_interest_and_percent|replace:'[percent]':$payment_emi.interest_rate|replace:'[interest]':$interest}{/if}</div>
									</div>									
                                    {/foreach}
                                
                                </div>
                            {/foreach}
                            {else}
                            	{$lang.min_emi_amount_not_matched}
                            {/if}
                        {/if}

						 {if ($payment_type.payment_type_id|in_array:$config.hide_payment_type_id_on_gc) && $has_gift_certificate==1}
								<div class="paymentTabDetail_message" style="margin-top:20;">{$lang.gc_payment_not_available}</div>
						 {/if}
                    
                        {if ($payment_type.payment_type_id == '4') && $cod_allowed == 'YES' && $has_gift_certificate==0}
                            {if isset($cart.gifting) && $cart.gifting.gift_it == 'Y'}
                            	<span>{$lang.gifting_not_on_cod}</span>
                                <div class="clearboth"></div>
                            {/if}
                            <!--modified by chandan to limit the max cod amount-->
                            {if isset($config.max_cod_amount) && $config.max_cod_amount > 0}
                            	{assign var="cod_max_limit" value=$config.max_cod_amount}
                            {else}
                            	{assign var="cod_max_limit" value="0"}
                            {/if}
                            {if ($cart.total <= $cod_max_limit && $cod_max_limit > "0") || ($cod_max_limit == "0") }
                            {foreach from=$payment_options item="payment_option"}         
                                {if !$smarty.session.cart.multiple_shipping_addresses}<input type="radio" id="payment_method_{$payment_option.payment_option_id}" {if $pm.disabled}disabled="disabled"{/if} class="radio" onclick="select_payment({$payment_option.payment_option_id},'')" name="payment_id" value="{$payment_option.payment_option_id}" {if $cart.payment_option_id == $payment_option.payment_option_id}{assign var="selected_payment_id" value=$payment_option.payment_option_id}checked="checked"{/if} /> {$lang.agree_for_cod} {/if}
                            {/foreach}
                            {else}
                            	{$lang.max_limit_cod_error|replace:'[MAX_COD_AMT]':$config.max_cod_amount}
                            {/if}
                        	<!--modified by chandan to limit the max cod amount-->
                        
                            <div class="clearboth" style="height:20px;"></div>
                            {if $payment_type.payment_type_id == '4'}
                                <div id="cod_warning" class="paymentTabDetail_note">
                                        {if $shipping_error == 'yes'}
                                            {if $cart.shipping_error == 'yes'}
                                                {$lang.cod_shipping_address_error}
                                            {/if}
                                        {/if}                              
                                </div>
                                <div class="clearboth"></div>
                            {/if}
                        
                        {elseif ($payment_type.payment_type_id == '4') && ($cod_allowed == 'NO' or $has_gift_certificate>0)}
                            <div class="paymentTabDetail_message" style="margin-top:0;">{$lang.not_served_by_cod}</div>
                        {/if}
                        {if ($payment_type.payment_type_id == '8')}
                            {if !($has_gift_certificate > 0 && $payment_type.payment_type_id|in_array:$config.hide_payment_type_id_on_gc)}
                            {foreach from=$payment_options item="payment_option"}         
                                <input type="radio" id="payment_method_{$payment_option.payment_option_id}" {if $pm.disabled}disabled="disabled"{/if} class="radio" onclick="select_payment({$payment_option.payment_option_id},'')" name="payment_id" value="{$payment_option.payment_option_id}" {if $cart.payment_option_id == $payment_option.payment_option_id}{assign var="selected_payment_id" value=$payment_option.payment_option_id}checked="checked"{/if} /> {$lang.agree_for_cbd}
                            {/foreach}                        
                            <div class="clearboth" style="height:20px;"></div>
                            {/if}
                        {/if}
                {else}
                	<p class="paymentTabDetail_message" style="margin-top:0;">{$lang.payment_method_not_available}</p>
                {/if}
                <div class="clearboth"></div>
                   <div class="paymentTabDetail_note" style="padding:10px 0 0; position:absolute; bottom:5px;"> 
                {if $payment_type.short_text_bottom != ''}
                    {$payment_type.short_text_bottom}
                {/if}
                {if $cart.payment_failed_status == '1' && $payment_type.name == $cart.payment_details.payment_type}
                    {$lang.failed_status}
                {/if}
                   </div>
                </div>
                
        {/foreach}
        
   	</div>
</div>
{literal}
<style>
.mainTabDiv .selected{background:#fff}
</style>
{/literal}
{literal}
<script type="text/javascript">
function select_payment(opt,period)
{
	if(period == '' && opt != ''){
	{/literal}
    	jQuery.ajaxRequest("index.php?dispatch=checkout.order_info&payment_option_id="+opt, {literal}{method: 'POST', cache: false, result_ids: {/literal}'{$result_ids}'{literal}}{/literal});
	document.cookie = 'opt'+"="+opt + ";expires=";
	{literal}
	}else if(period != '' && opt != ''){
	{/literal}
		jQuery.ajaxRequest("index.php?dispatch=checkout.order_info&payment_option_id="+opt+"&eprd="+period, {literal}{method: 'POST', cache: false, result_ids: {/literal}'{$result_ids}'{literal}}{/literal});
		document.cookie = 'opt'+"="+opt + ";expires=";
	}
} 
{literal}

function show_emi_option(opt)
{
    $('.emi_block').css('display','none');
    document.getElementById('emi_' + opt).style.display='block'; 
} 

function changepaymenttab(cnt)
{
	var i=1;
	while(document.getElementById('paymentbody-'+i))
	{
		if(i==cnt)
		{
			document.getElementById('paymentbody-' + i).style.display='block';
			document.getElementById('payment_tab_li_' + i).className = "ptab selected";
			document.cookie = 'pt'+"="+cnt + ";expires=";
		}
		else
		{
			document.getElementById('paymentbody-'+i).style.display='none';
			document.getElementById('payment_tab_li_' + i).className = "ptab";
		}
		i++;
	}
}
if(document.getElementById('selectedtab').value=='')
{
	changepaymenttab(1);
}
if(document.getElementById('dpt')){
	document.cookie = 'pt'+"="+document.getElementById('dpt').value + ";expires=";
}

if(document.getElementById('paymentTabDetail_selectbox').value != ''){
	show_emi_option(document.getElementById('paymentTabDetail_selectbox').value);		
}
</script>
{/literal}
