{if $show_email}

<div class="panel_login_fieldname">
<label for="{$id_prefix}elm_email" class="cm-required cm-email">{$lang.email}:</label>
<span class="panel_login_fieldabout">(Required)</span>
</div>

<div class="panel_login_field">
<input type="email" id="{$id_prefix}elm_email" name="user_data[email]" size="32" value="{$user_data.email}" class="panel_login_textbox {$_class}" {$disabled_param} />
<script type="text/javascript">
//<![CDATA[
{literal}
	document.getElementById('login_checkout').focus();
{/literal}
//]]>
</script>

<span class="panel_login_fieldaboutone">Your order details will be sent to this email address</span>
</div>

{else}

{if ($controller == "profiles" && $mode =="update") || ($controller == "profiles" && $mode =="update_addressbook") || ($controller == "checkout" && $mode =="checkout")}
{if $profile_fields.$section}


{if $body_id}
	<div id="{$body_id}">
    {/if}

{if ($address_flag && !$ship_to_another && ($section == "S" || $section == "B")) || $disabled_by_default}
	{assign var="disabled_param" value="disabled=\"disabled\""}
	{assign var="_class" value="disabled"}
{else}
	{assign var="disabled_param" value=""}
	{assign var="_class" value=""}
{/if}

{if $section == 'B'}
		<h3 class="heading_step_2_nl">{$lang.billing_address}</h3>
        {$smarty.capture.title_extra}
    <div id="billing_address" class="form_onecolumn" {if $cart.ship_to_another == '1' }style="display:block;" {else}style="display:none;"{/if}>		
    	
        <div class="form_onecolumn_row">
            <div class="aside_left">
                <div class="form_onecolumn_row_fieldname cm-required">
                	<label for="b_firstname" class="cm-required">First Name</label>
                </div>
                <div class="form_onecolumn_row_field">
                    <input type="text" id="b_firstname" name="user_data[b_firstname]" size="32" value="{$user_data.b_firstname}" class="input-text form_onecolumn_row_field_textbox "  {if !$ship_to_another}disabled="disabled"{/if}    />
                    <input type="hidden" id="b_profile_id" name="user_data[b_profile_id]" value="{$user_data.profile_id}" />
                </div>
            </div>
            <div class="aside_right">
                <div class="form_onecolumn_row_fieldname cm-required">
                	<label for="b_lastname" class="cm-required">Last Name</label>
                </div>
                <div class="form_onecolumn_row_field">
                    <input type="text" id="b_lastname" name="user_data[b_lastname]" size="32" value="{$user_data.b_lastname}" class="input-text form_onecolumn_row_field_textbox "  {if !$ship_to_another}disabled="disabled"{/if}   />	
                </div>    
            </div>
        </div>
                    
        <div class="form_onecolumn_row">
            <div class="form_onecolumn_row_fieldname">
            	<label for="b_address" class="cm-required">Address</label>
            </div>
            <div class="form_onecolumn_row_field">
                <input type="text" id="b_address" name="user_data[b_address]" size="32" value="{$user_data.b_address}" class="input-text form_onecolumn_row_field_textbox "  {if !$ship_to_another}disabled="disabled"{/if}   />
            </div>
        </div>
        
        <div class="form_onecolumn_row">
            <div class="form_onecolumn_row_fieldname">
            	<label for="b_address_2" class="">Address, line 2:</label>
            </div>
            <div class="form_onecolumn_row_field">
                <input type="text" id="b_address_2" name="user_data[b_address_2]" size="32" value="{$user_data.b_address_2}" class="input-text form_onecolumn_row_field_textbox"  {if !$ship_to_another}disabled="disabled"{/if}   />
            </div>
        </div>
                
         <div class="form_onecolumn_row">
            <div class="form_onecolumn_row_fieldname">
            	<label for="b_country" class="">Country:</label>
            </div>
             <div class="form_onecolumn_row_field">
            {assign var="_country" value=$value|default:$settings.General.default_country}
            <select id="b_country" class="cm-location-billing form_onecolumn_row_field_listbox" name="user_data[b_country]" onchange="select_country(this.value)">
                <option value="">- {$lang.select_country} -</option>
                {foreach from=$countries item=country}
                <option {if $user_data.b_country == $country.code}selected="selected"{/if} value="{$country.code}">{$country.country}</option>
                {/foreach}
            </select>
            </div>
            
        </div>                   
        <div class="form_onecolumn_row">
            <div class="aside_left">
                <div class="form_onecolumn_row_fieldname">
                	<label for="b_city" class="cm-required">City</label>
                </div>
                <div class="form_onecolumn_row_field">
                    <input type="text" id="b_city" name="user_data[b_city]" size="32" value="{$user_data.b_city}" class="input-text form_onecolumn_row_field_textbox" {if !$ship_to_another}disabled="disabled"{/if}    />
                </div>
            </div>
           
            <div class="aside_right">
                <div class="form_onecolumn_row_fieldname">
                	<label for="b_state" class="cm-required">State</label>
                </div>
                <div class="form_onecolumn_row_field">
                    <select id="b_state" class="form_onecolumn_row_field_listbox" name="user_data[b_state]" {if !$ship_to_another}disabled="disabled"{/if} {if $user_data.b_country == 'IN'}style="display:block;"{else} style="display:none;" disabled="disabled"{/if}     >
                        <option value="">- {$lang.select_state} -</option>
                        {* Initializing default states *}
                        {assign var="country_code" value=$settings.General.default_country}
                        {assign var="state_code" value=$value|default:$settings.General.default_state}
                        {if $states}
                            {foreach from=$states.$country_code item=state}
                                <option {if $state.code == $user_data.b_state}selected="selected"{/if} value="{$state.code}">{$state.state}</option>
                            {/foreach}
                        {/if}
                    </select>
                    <input type="text" id="b_state_d" name="user_data[b_state]" size="32" maxlength="64" value="{$user_data.b_state}" {if $user_data.b_country == 'IN'} disabled="disabled" {/if} class="input-text cm-skip-avail-switch form_onecolumn_row_field_textbox hidden" {if $user_data.b_country == 'IN'}style="display:none;"{else} style="display:block;"{/if} />
                    <input type="hidden" id="elm_24_default" value="{$user_data.b_state}"  {if !$ship_to_another}disabled="disabled"{/if}  />
                </div>    
            </div>
        </div>
        
                            
        <div class="form_onecolumn_row">
            <div class="aside_left">
                <div class="form_onecolumn_row_fieldname">
                	<label for="b_zipcode" class="cm-required cm-integer">Pincode</label>
                </div>
                <div class="form_onecolumn_row_field">
                    <input type="tel" id="b_zipcode" name="user_data[b_zipcode]" size="32" value="{$user_data.b_zipcode}" class="input-text form_onecolumn_row_field_textbox" maxlength="6"  {if !$ship_to_another}disabled="disabled"{/if}   />    
                </div>
            </div>
            <div class="aside_right">
                <div class="form_onecolumn_row_fieldname">
                	
                	<label for="b_phone" class="cm-required cm-phone">Mobile Number</label>
                </div>
                <div class="form_onecolumn_row_field">
                    <div class="float_left" style="margin-top:5px;">+91</div>
                    <input type="tel" id="b_phone" name="user_data[b_phone]" size="32" value="{$user_data.b_phone}" class="input-text form_onecolumn_row_field_textbox" maxlength="10" style="width:80%"  {if !$ship_to_another}disabled="disabled"{/if}  />
                </div>    
            </div>
        </div>			
					
	</div>
{elseif $section == 'S'}
	<div id="shipping_address" class="form_onecolumn">
    	<h3 class="heading_step_2_nl">{$lang.shipping_address}</h3>
        {if $cart.nss_on_cod && $cart['nss_on_cod'] == 'N' && $config.show_nss_alert}
        <div class="nss_pincode_error">
            {$lang.nss_pincode_errors}
        </div>
        {/if}
    	<div class="form_onecolumn_row">
            <div class="aside_left">
                <div class="form_onecolumn_row_fieldname cm-required">
                	<label for="s_firstname" class="cm-required">First Name</label>
                </div>
                <div class="form_onecolumn_row_field">
                	<input type="hidden" id="profile_id" name="user_data[profile_id]" value="{$user_data.profile_id}" />
                    <input type="text" id="s_firstname" name="user_data[s_firstname]" size="32" value="{$user_data.s_firstname}" class="input-text form_onecolumn_row_field_textbox "   />
                </div>
            </div>
            <div class="aside_right">
                <div class="form_onecolumn_row_fieldname cm-required">
                	<label for="s_lastname" class="cm-required">Last Name</label>
                 </div>
                <div class="form_onecolumn_row_field">
                    <input type="text" id="s_lastname" name="user_data[s_lastname]" size="32" value="{$user_data.s_lastname}" class="input-text form_onecolumn_row_field_textbox "  />	
                </div>    
            </div>
        </div>
                    
        <div class="form_onecolumn_row">
            <div class="form_onecolumn_row_fieldname">
            	<label for="s_address" class="cm-required">Address</label>
            </div>
            <div class="form_onecolumn_row_field">
                <input type="text" id="s_address" name="user_data[s_address]" size="32" value="{$user_data.s_address}" class="input-text form_onecolumn_row_field_textbox "   />
            </div>
        </div>
        
        <div class="form_onecolumn_row">
            <div class="form_onecolumn_row_fieldname">
            	<label for="s_address_2" class="">Address, line 2:</label>
            </div>
            <div class="form_onecolumn_row_field">
                <input type="text" id="s_address_2" name="user_data[s_address_2]" size="32" value="{$user_data.s_address_2}" class="input-text form_onecolumn_row_field_textbox"  />
            </div>
        </div>
                
                            
        <div class="form_onecolumn_row">
            <div class="aside_left">
                <div class="form_onecolumn_row_fieldname">
                	<label for="s_city" class="cm-required">City</label>
                </div>
                <div class="form_onecolumn_row_field">
                    <input type="text" id="s_city" name="user_data[s_city]" size="32" value="{$user_data.s_city}" class="input-text form_onecolumn_row_field_textbox"  />
                </div>
            </div>
            <div class="aside_right">
                <div class="form_onecolumn_row_fieldname">
                	<label for="s_state" class="cm-required">State</label>
                </div>
                <div class="form_onecolumn_row_field">
                    <select id="s_state" class="form_onecolumn_row_field_listbox" name="user_data[s_state]"  >
                        <option value="">- {$lang.select_state} -</option>
                        {* Initializing default states *}
                        {assign var="country_code" value=$settings.General.default_country}
                        {assign var="state_code" value=$value|default:$settings.General.default_state}
                        {if $states}
                            {foreach from=$states.$country_code item=state}
                                <option {if $state.code == $user_data.s_state}selected="selected"{/if} value="{$state.code}">{$state.state}</option>
                            {/foreach}
                        {/if}
                    </select>
                    <!-- <input type="text" id="s_state_d" name="user_data[s_state]" size="32" maxlength="64" value="{$user_data.s_state}" disabled="disabled" class="input-text hidden "/> -->
                    <input type="hidden" id="s_state_default" value="{$user_data.s_state}" />
                </div>    
            </div>
        </div>
        
                            
        <div class="form_onecolumn_row">
            <div class="aside_left">
                <div class="form_onecolumn_row_fieldname">
                	<label for="s_zipcode" class="cm-required cm-integer cm-custom (pincode_length)">Pincode</label>
                </div>
                <div class="form_onecolumn_row_field">
                    <input type="tel" id="s_zipcode" name="user_data[s_zipcode]" size="32" value="{$user_data.s_zipcode}" class="input-text form_onecolumn_row_field_textbox" maxlength="6"/>    
                </div>
            </div>
            <div class="aside_right" style="margin-top:10px;">
                <div class="form_onecolumn_row_fieldname">
                	<label for="s_phone" class="cm-required cm-phone">Mobile Number</label>
                </div>
                <div class="form_onecolumn_row_field">
                <div class="float_left" style="margin-top:5px;">+91</div>
                    <input type="tel" id="s_phone" name="user_data[s_phone]" size="32" style="width:80%" value="{$user_data.s_phone}" class="input-text form_onecolumn_row_field_textbox" maxlength="10" />
                </div>
                    
                </div>    
            </div>
        </div>			
		
        
{if $address_flag}
	{capture name="title_extra"}
		<span class="float_left" style="margin-top:10px;">
			<input type="hidden" name="copy_address" value="" />

			<div class="float_left">
           
            <input style="margin-top:5px;" class="checkbox float_left" id="elm_ship_to_another" type="checkbox" name="copy_address" value="Y" onclick="toggleStatus();" {if $cart.ship_to_another != '1' }checked="checked"{/if} />
            <!--<input style="margin-top:5px;" class="checkbox float_left" id="elm_ship_to_another" type="checkbox" name="copy_address" value="Y" onclick="toggleStatus();" {if !$ship_to_another}checked="checked"{/if} />-->
            <!--<input class="checkbox" id="elm_ship_to_another" type="checkbox" name="copy_address" value="Y" onclick="$('#{$body_id}').switchAvailability(this.checked, false);" {if !$ship_to_another}checked="checked"{/if} />-->
			<a onclick="$('#elm_ship_to_another').click();" style="float:left; text-decoration:none; display:block;">
            {*{if $section == "S"}{$lang.use_billing}{else}{$lang.use_shipping}{/if}*}
            {if $section == "B"}{$lang.use_billing}{else}{$lang.use_shipping}{/if}
            
            <div class="clearboth"></div>
            <span style="float:left; display:inline; font:11px verdana; color:#636566; text-align:right; line-height:10px;">{$lang.change_shipping_address}</span>
            </a>           
            
            </div>
            
		
        </span>
   	{/capture}
{/if}

{literal}
	<script type="text/javascript">
            function fn_pincode_length()
                {
                   var pincode = $('#s_zipcode').val();
                   var pin = pincode.toString();
                   lang.pincode_length_should_be_six = '{$lang.pincode_length_should_be_six}';
                   if(pin.length != '6' || !(/^[0-9]*$/.test(pincode)))
                   {
                        return lang.pincode_length_should_be_six;  
                   }
                }
		function toggleStatus() 
		{
			$('#billing_address').toggle();
			$('#billing_address_book_area').toggle();
			if ($('#elm_ship_to_another').is(':checked')) {
				$('#billing_address :input').attr('disabled', 'disabled');
				$('#billing_address :select').attr('disabled', 'disabled');
			} else {
				$('#billing_address :input').removeAttr('disabled');
			}   
		}
		
		function select_country(country_code)
		{
			if(country_code != 'IN')
			{
				$('#b_state').attr('disabled','disabled');
				$('#b_state').css('display','none');	
				$('#b_state_d').removeAttr('disabled');
				$('#b_state_d').css('display','block');
				$('#b_state_d').removeClass('hidden');
			}else{
				$('#b_state').removeAttr('disabled','disabled');
				$('#b_state').css('display','block');	
				$('#b_state_d').attr('disabled');
				$('#b_state_d').css('display','none');
			}
		}
	</script>
{/literal}

{*$smarty.capture.title_extra*}			

{/if}
{if $body_id}
</div>
{/if}

{/if}
{/if}
{/if}
