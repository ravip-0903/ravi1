{* $Id: step_two.tpl 12479 2011-05-18 08:54:10Z alexions $ *}
{if $smarty.request.userstatus == 'exist'}
<script type="text/javascript">
//<![CDATA[
{literal}
	document.getElementById('haveaccount').style.display = 'block';
	document.getElementById('anonymous_checkout').style.display = 'none';
	document.getElementById("login_checkout").value = document.getElementById("soacf_elm_email").value;
	document.getElementById('psw_checkout').focus();
	document.getElementById("checkout_login_radio2").checked = true;
{/literal}
//]]>
</script>

{/if}
<div class="step-container{if $edit}-active{/if} add_nl_container" id="step_two">
	<h2 class="step-title{if $edit}-active{/if}">
		<span class="float-left title">2.</span>

		{if $complete && !$edit}
			<img src="{$images_dir}/icons/icon_step_close.gif" width="19" height="17" border="0" alt="" class="float-right" />
		{/if}
		
		{hook name="checkout:edit_link_title"}
		<a style="float:left" class="title{if $complete && !$edit} cm-ajax cm-ajax-force{/if}" {if $complete && !$edit}href="{"checkout.checkout?edit_step=step_two&amp;from_step=`$edit_step`"|fn_url}" rev="checkout_steps"{/if}>
        {$lang.address}
        </a>
		{/hook}
        </h2>  
    
   
    
     <div class="clearboth"></div>
     
	<div id="step_two_body" class="step-body{if $edit}-active{/if}{if !$edit && !$complete} hidden{/if} add_nl_new_box">
		<div id="address_book">
        <h3 class="add_nl_new_heading heading_step_2_nl">{$lang.shipping_address_book}</h3>
        <a href="#shipping_address" onclick="return false" id="new_s_address" class="pj2_read_more_prd" style="font:11px Arial, Helvetica, sans-serif; float:left; margin:5px 0 0 6px;">Ship To New Address</a>
			<div class="clearboth"></div>
            <div id="scrollbar1" style="float:left; width:97%; height:370px; {if $user_profiles|count <= 4 }background: url({$config.ext_images_host}/images/skin/bg_saprater_contactus.gif) 440px 0 no-repeat{/if}">
            <div class="scrollbar"><div class="track"><div class="thumb" style="400px"><div class="end"></div></div></div></div>
            <div class="viewport">
            <div class="overview" style="top:0;">
                
                
                {foreach from=$user_profiles item="user_profile}
                	{assign var="state_name" value=$user_profile.s_state|fn_get_state_name:"IN"}
                    {if $user_profile.s_firstname != "" && $user_profile.s_lastname != "" && $user_profile.s_address != "" && $user_profile.s_city != "" && $user_profile.s_state != "" && $user_profile.s_zipcode != ""}
                    <div id="add_cntr" class="add_nl_box">
                    <div id="{$user_profile.profile_id}" class="add_book add_nl_block" style="height:150px;" >
                        <h2 style="color:#048ccc">{$user_profile.profile_name}</h2>
                        <div style="display:none">
                            <div style="margin:0 0 10px;">                            
                            <span id="b_{$user_profile.profile_id}_b_firstname" class="add_nl_name">{$user_profile.b_firstname}</span>
                            <span id="b_{$user_profile.profile_id}_b_lastname" class="add_nl_surname">{$user_profile.b_lastname}</span>
                            </div>
                            <div style="clear:both;"></div>
                            <span id="b_{$user_profile.profile_id}_b_address">{$user_profile.b_address}</span> 
                            <span id="b_{$user_profile.profile_id}_b_address_2">{if $user_profile.b_address_2 neq ""}{$user_profile.s_address_2}{/if}</span><div style="clear:both;"></div>
                            <span id="b_{$user_profile.profile_id}_b_city">{$user_profile.b_city}</span>,                            
                            <span id="b_{$user_profile.profile_id}_b_state" style="display:none;">{$user_profile.b_state}</span>
                            <span>{$state_name}</span>
                            <div style="clear:both;"></div>
                            <span id="b_{$user_profile.profile_id}_b_country">{$user_profile.b_country|fn_get_country_name}</span><div style="clear:both;"></div>
                            <span id="b_{$user_profile.profile_id}_b_zipcode">{$user_profile.b_zipcode}</span>
                            <span id="b_{$user_profile.profile_id}_b_phone">{$user_profile.b_phone}</span>                        
                        </div>
                        <div style="overflow:hidden; max-height:100px;">
                        <span id="s_{$user_profile.profile_id}_s_profile_name" class="add_nl_name" style="display:none;">{$user_profile.profile_name}</span>
                        <span id="s_{$user_profile.profile_id}_s_firstname" class="add_nl_name">{$user_profile.s_firstname}</span>
                        <span id="s_{$user_profile.profile_id}_s_lastname" class="add_nl_surname">{$user_profile.s_lastname}</span><div style="clear:both;"></div>
                        <span id="s_{$user_profile.profile_id}_s_address">{$user_profile.s_address}</span> 
                        <span id="s_{$user_profile.profile_id}_s_address_2">{if $user_profile.s_address_2 neq ""}{$user_profile.s_address_2}{/if}</span><div style="clear:both;"></div>
                        <span id="s_{$user_profile.profile_id}_s_city">{$user_profile.s_city}</span>,
                        
                        <span id="s_{$user_profile.profile_id}_s_state" style="display:none;">{$user_profile.s_state}</span>
                        <span>{$state_name}</span>
                        <div style="clear:both;"></div>
                        <!--<span id="s_{$user_profile.profile_id}_s_country">{$user_profile.s_country|fn_get_country_name}</span>--><div style="clear:both;"></div>
                        <span id="s_{$user_profile.profile_id}_s_zipcode">{$user_profile.s_zipcode}</span>
                        <span id="s_{$user_profile.profile_id}_s_phone" style="padding-left:10px;">{$user_profile.s_phone}</span>   <div style="clear:both;"></div></div>
                        <a id="{$user_profile.profile_id}_change_s" class="pj2_read_more_prd change_address" style="font:11px Arial, Helvetica, sans-serif;" rel="{$user_profile.profile_id}" href="javascript: void(0);">Edit Address</a>
                        <span class="nl_add_change">Click to Select</span>                     
                    </div>
                    </div>
                    {/if}
                    
                {/foreach}  
                </div>
                </div>  
            </div>
        </div>
		<div>
			<form name="step_two_billing_address" class="{$ajax_form} {$ajax_form_force}" action="{""|fn_url}" method="{if !$edit}get{else}post{/if}">
			<input type="hidden" name="update_step" value="step_two" />
			<input type="hidden" name="next_step" value="{if $smarty.request.from_step && $smarty.request.from_step != "step_two" && $smarty.request.from_step != "step_one"}{$smarty.request.from_step}{else}step_three{/if}" />
			<input type="hidden" name="result_ids" value="checkout_steps,sign_io,checkout_cart" />
			<input type="hidden" name="dispatch" value="checkout.checkout" />

			{*{if $smarty.request.profile == "new"}
				{assign var="hide_profile_name" value=false}
			{else}
				{assign var="hide_profile_name" value=true}
			{/if}
			
			{if !$edit}
			<div class="step-complete-wrapper multiple-profiles">
				{include file="views/profiles/components/multiple_profiles.tpl" hide_profile_name=$hide_profile_name hide_profile_delete=true profile_id=$cart.profile_id create_href="checkout.checkout?edit_step=step_two&amp;from_step=$edit_step&amp;profile=new"}
			</div>
			{else}
				{include file="views/profiles/components/multiple_profiles.tpl" show_text=true hide_profile_name=$hide_profile_name hide_profile_delete=true profile_id=$cart.profile_id create_href="checkout.checkout?edit_step=step_two&amp;from_step=$edit_step&amp;profile=new"}
			{/if}*}
			
			{if $settings.General.address_position == "billing_first"}
				{assign var="first_section" value="B"}
				{assign var="first_section_text" value=$lang.billing_address}
				{assign var="sec_section" value="S"}
				{assign var="sec_section_text" value=$lang.shipping_address}
				{assign var="ship_to_another_text" value=$lang.text_ship_to_billing}
				{assign var="body_id" value="sa"}
			{else}
				{assign var="first_section" value="S"}
				{assign var="first_section_text" value=$lang.shipping_address}
				{assign var="sec_section" value="B"}
				{assign var="sec_section_text" value=$lang.billing_address}
				{assign var="ship_to_another_text" value=$lang.text_billing_same_with_shipping}
				{assign var="body_id" value="ba"}
			{/if}
			
			{if !$edit}
				<table width="92%" cellpadding="0" cellspacing="0" border="0">
				<tr valign="top">
				{if $profile_fields[$first_section]}
					<td width="45%">
						{include file="views/profiles/components/step_profile_fields.tpl" section=$first_section text=$first_section_text}
					</td>
				{/if}
				<td width="10%">&nbsp;</td>
				{if $profile_fields[$sec_section]}
					<td width="45%">
						{if $cart.ship_to_another}
							{include file="views/profiles/components/step_profile_fields.tpl" section=$sec_section text=$sec_section_text}
						{else}
							<div class="step-complete-wrapper clear">
								<strong class="float-left">{$sec_section_text}: &nbsp;</strong>
								<p class="no-padding overflow-hidden">{$ship_to_another_text}</p>
							</div>
						{/if}
					</td>
				{/if}
				</tr>
				</table>
			{else}
				<div id="shipping_add" class="clear">
					{if $profile_fields[$sec_section]}
						<div class="float-right" style="width:100%;">
							{include file="views/profiles/components/profile_fields.tpl" section=$sec_section body_id=$body_id address_flag=$profile_fields|fn_compare_shipping_billing ship_to_another=$cart.ship_to_another title=$sec_section_text}
						</div>
					{/if}
                    
                    {if $profile_fields[$first_section]}
						<div class="float-left" style="float:right; margin-top:20px; width:100%; clear:both;">
							{include file="views/profiles/components/profile_fields.tpl" section=$first_section body_id="" ship_to_another="Y" title=$first_section_text}
						</div>
					{/if}

				</div>
				
				
				<div class="buttons-container box_functions">
					<span style="margin-left:20px;">{include file="buttons/button.tpl" but_name="dispatch[checkout.update_steps]" but_text=$lang.multiaddress_continue}</span>
					{assign var="prds" value=$cart.products|get_products_in_cart}
                	{assign var="is_wholesale" value=$prds|is_wholesale_product_in_cart}

                	{if !$is_wholesale || $config.enable_wholesale_feature != 1}
                                        {if $lang.multiaddress_to_multiple != ''}
                                            {if $auth.user_id}
                                                {$lang.multiaddress_to_multiple}
                                                {$lang.multiaddress_help}
                                            {/if}
                                       {/if}
                    {/if}
                                       <div class="clearboth"></div>
					<p>{$lang.address_note}</p>
				</div>
				
			{/if}
			</form>
		</div>

		{if $complete && !$edit}
			{hook name="checkout:edit_link"}
			<div class="right">
				{include file="buttons/button.tpl" but_meta="cm-ajax cm-ajax-force" but_href="checkout.checkout?edit_step=step_two&amp;from_step=$edit_step" but_rev="checkout_steps" but_text=$lang.change but_role="tool"}
			</div>
			{/hook}
		{/if}
	</div>
    <div id="change_address_popup" style="position: absolute; display:none; z-index: 200; margin-top:-130px; left: 0px; top: 0px; width: 100%; min-height: 100%;  background-position: initial initial; background-repeat: initial initial;"> 
    	<div class="chng_address_wrapper"style="width:1000px; margin:auto; position:relative;">
            <div  class="add_nl_chng_add" style="display:block;">
            <img id="close_notification" class="cm-notification-close hand add_nl_close_btn" src="skins/basic/customer/images/icons/icon_close.gif" width="13" height="13" border="0" alt="Close" title="Close">
            <form name="change_add_popup" id="change_add_popup" method="post" action="{""|fn_url}">
            <input type="hidden" id="pop_profile_id" name="profile_id" value="" />
          	<div class="add_nl_chng_add_new">Change Your Address</div>
		<div class="chng_address_profile">                
		<label class="nl_add_chng_add_title" >Profile Name</label>
		<input type="text" id="profile_name"  style="margin-left:13px" name="profile_name" value="" />
		</div>

		<div class="chng_address_fields">
		<label class="nl_add_chng_add_title">First Name</label> 
		<input type="text" id="firstname"  name="firstname" value="" />
		</div>
                
		<div class="chng_address_fields">
		<label class="nl_add_chng_add_title_2">Last Name</label>
		<input type="text" id="lastname"  name="lastname" value="" />
		</div>

		<div class="chng_address_fields">
		<label class="nl_add_chng_add_title">Address</label>
		<input type="text" id="address"  name="address" value="" />
		</div>
		<div class="chng_address_fields">
		<label class="nl_add_chng_add_title_2">Address, line 2:</label>
		<input type="text" id="address_2"  name="address_2" value="" />
		</div>
		<div class="chng_address_fields">             
		<label class="nl_add_chng_add_title">City</label>
		<input type="text" id="city"  name="city" value="" />
		</div>
<div class="chng_address_fields">	
			<label class="nl_add_chng_add_title_2">State</label>
     
                {assign var="country_code" value=$settings.General.default_country}
                {assign var="state_code" value=$value|default:$settings.General.default_state}
                <select name="state" id="state">
                    <option value="">- {$lang.select_state} -</option>
                    {if $states}
                        {foreach from=$states.$country_code item=state}
                            <option value="{$state.code}">{$state.state}</option>
                        {/foreach}
                    {/if}
                </select>
</div>

                <div class="clearboth"></div>
                <!--<input type="text" id="state"  name="state" value="" /><br />-->
                <input type="hidden" id="country"  name="country" value="" />
	<div class="chng_address_fields">
	<label class="nl_add_chng_add_title">Pincode</label>
	<input type="tel" id="zipcode"  name="zipcode" maxlength="6" value="" />
	</div>
	<div class="chng_address_fields">
	<label class="nl_add_chng_add_title_2">Mobile Number</label>
	<input type="tel" id="phone"  name="phone" value="" />
	</div>
                <a href="javascript: void(0)" class="act_btn_nl_add" id="add_popup_close">Close</a>
                <input type="submit" name="dispatch[checkout.change_address]" class="act_btn_nl_add_chng" style="cursor:pointer;" value="Change" />
            </form>
        </div>
	</div>
</div>    
    
<!--step_two-->
</div>

{literal}
<script type="text/javascript">
	$('.add_book').mouseup(function(){
		$('#b_firstname').val($('#b_'+this.id+'_b_firstname').text());
		$('#b_lastname').val($('#b_'+this.id+'_b_lastname').text());
		$('#b_address').val($('#b_'+this.id+'_b_address').text());
		$('#b_address_2').val($('#b_'+this.id+'_b_address_2').text());
		$('#b_city').val($('#b_'+this.id+'_b_city').text());
		$('#b_state').val($('#b_'+this.id+'_b_state').text());
		$('#b_country').val($('#b_'+this.id+'_b_country').text());
		$('#b_zipcode').val($('#b_'+this.id+'_b_zipcode').text());
		$('#b_phone').val($('#b_'+this.id+'_b_phone').text());
		
		$('#s_firstname').val($('#s_'+this.id+'_s_firstname').text());
		$('#s_lastname').val($('#s_'+this.id+'_s_lastname').text());
		$('#s_address').val($('#s_'+this.id+'_s_address').text());
		$('#s_address_2').val($('#s_'+this.id+'_s_address_2').text());
		$('#s_city').val($('#s_'+this.id+'_s_city').text());
		$('#s_state').val($('#s_'+this.id+'_s_state').text());
		$('#s_country').val($('#s_'+this.id+'_s_country').text());
		$('#s_zipcode').val($('#s_'+this.id+'_s_zipcode').text());
		$('#s_phone').val($('#s_'+this.id+'_s_phone').text());
		
		$('#profile_id').val(this.id);
		$('.add_book').removeClass('add_nl_active');
		$('#'+this.id).addClass('add_nl_active');
		
		/*if( ($('#b_firstname').val() != $('#s_firstname').val()) || ($('#b_lastname').val() != $('#s_lastname').val()) || ($('#b_address').val() != $('#s_address').val()) || ($('#b_address_2').val() != $('#s_address_2').val()) || ($('#b_city').val() != $('#s_city').val()) || ($('#b_state').val() != $('#s_state').val()) || ($('#b_country').val() != $('#s_country').val()) || ($('#b_zipcode').val() != $('#s_zipcode').val()) || ($('#b_phone').val() != $('#s_phone').val()) ){
			$('#elm_ship_to_another').removeAttr('checked');	
		}*/
	});
	$('#s_'+$('#profile_id').val()).addClass('add_nl_active');
	
	$('.billing_add_book').click(function(){
		$('#b_firstname').val($('#'+this.id+'_s_firstname').text());
		$('#b_lastname').val($('#'+this.id+'_s_lastname').text());
		$('#b_address').val($('#'+this.id+'_s_address').text());
		$('#b_address_2').val($('#'+this.id+'_s_address_2').text());
		$('#b_city').val($('#'+this.id+'_s_city').text());
		$('#b_state').val($('#'+this.id+'_s_state').text());
		$('#b_country').val($('#'+this.id+'_s_country').text());
		$('#b_zipcode').val($('#'+this.id+'_s_zipcode').text());
		$('#b_phone').val($('#'+this.id+'_s_phone').text());
		var id = this.id.split('_');
		$('#b_profile_id').val(id[1]);	
		$('.billing_add_book').removeClass('add_nl_active');
		$('#'+this.id).addClass('add_nl_active');
	});
	$('#b_'+$('#b_profile_id').val()).addClass('add_nl_active');
	
	if ($('#elm_ship_to_another').is(':checked')) {
		$('#billing_address :input').attr('disabled', 'disabled');
		//$('#billing_address :select').attr('disabled', 'disabled');
		$('#billing_address > b_state').attr('disabled', 'disabled');
	}	
	
	$('.change_address').click(function(event){
		event.stopPropagation();
		var a_type = this.id.split('_');
		var add_type = a_type[2];
		$('#profile_name').val($('#'+add_type+'_'+this.rel+'_s_profile_name').text());
		$('#firstname').val($('#'+add_type+'_'+this.rel+'_s_firstname').text());
		$('#lastname').val($('#'+add_type+'_'+this.rel+'_s_lastname').text());
		$('#address').val($('#'+add_type+'_'+this.rel+'_s_address').text());
		$('#address_2').val($('#'+add_type+'_'+this.rel+'_s_address_2').text());
		$('#city').val($('#'+add_type+'_'+this.rel+'_s_city').text());
		$('#state').val($('#'+add_type+'_'+this.rel+'_s_state').text());
		$('#country').val($('#'+add_type+'_'+this.rel+'_s_country').text());
		$('#zipcode').val($('#'+add_type+'_'+this.rel+'_s_zipcode').text());
		$('#phone').val($('#'+add_type+'_'+this.rel+'_s_phone').text());
		$('#pop_profile_id').val(this.rel);
		$('#change_address_popup').css('display','block');
		//$('.header_global').css('z-index','-1');
	});
	
	$('#new_s_address').click(function(){
		$('#s_firstname').val('');
		$('#s_lastname').val('');
		$('#s_address').val('');
		$('#s_address_2').val('');
		$('#s_city').val('');
		$('#s_state').val('');
		$('#s_country').val('');
		$('#s_zipcode').val('');
		$('#s_phone').val('');
		$('#profile_id').val('-1');
		if($(window).width()<=800)
			{
			$('#shipping_address').toggle(); $('html, body').animate({
			scrollTop: $("#shipping_address").offset().top-50
			}, 200);
			}

	});
	
	$('#add_popup_close').click(function(){
		$('#change_address_popup').css('display','none');
		$('.header_global').css('z-index','10');
	});
	$('#close_notification').click(function(){
		$('#change_address_popup').css('display','none');
		$('.header_global').css('z-index','10');
	});
	
	
	
</script>
<script type="text/javascript">
		$(document).ready(function(){
			if($(window).width()>=800) {$('#scrollbar1').tinyscrollbar();}

					    });
			if($(window).width()<=800)
				{
				if($('.add_nl_box').length==0)
					{
					$('#address_book').hide();
					$('#shipping_address').show();
					$('.title').hide();
					}					
				}
		$(document).ready(function(){
	if($(window).width()<=800)
	{	
	$(".add_nl_block").click(function(){$(".button-submit input").click()});
	}
		});	
	</script>	
{/literal}


