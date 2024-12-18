{if ($controller == "profiles" && $mode =="update_addressbook") }
{if $profile_fields.$section}

{if $address_flag}
    <input type="hidden" name="copy_address" value="" />
{/if}

{literal}
	<script type="text/javascript">
           $(document).ready(function() {
                var value = '';
		$('#elm_profile_id').focus(function() {
			value = $(this).val();
			if(value.trim() == '- New -'){
                            $(this).val("");
                        }
		});
		$('#elm_profile_id').blur(function() {
			if($(this).val()=="") {
			  $(this).val(value);
			}
		});
            });
                
                
	</script>
{/literal}

{if !$nothing_extra}
	{include file="common_templates/subheader.tpl" title=$title extra=$smarty.capture.title_extra}
{/if}

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

{if $section == 'S'}
        <input type="hidden" name="copy_address" value="" />
	<div id="shipping_address" class="form_onecolumn" >		
        <div class="form_onecolumn_row">
            <div class="aside_left">
                <div class="form_onecolumn_row_fieldname cm-required">
                	<label for="s_firstname" class="cm-required">First Name</label>
                </div>
                <div class="form_onecolumn_row_field">
                    <input type="text" id="s_firstname" name="user_data[s_firstname]" size="32" value="{$user_data.s_firstname}" class="input-text form_onecolumn_row_field_textbox " {if !$ship_to_another}disabled="disabled"{/if}  />
                </div>
            </div>
            <div class="aside_right">
                <div class="form_onecolumn_row_fieldname cm-required">
                	<label for="s_lastname" class="cm-required">Last Name</label>
                 </div>
                <div class="form_onecolumn_row_field">
                    <input type="text" id="s_lastname" name="user_data[s_lastname]" size="32" value="{$user_data.s_lastname}" class="input-text form_onecolumn_row_field_textbox "  {if !$ship_to_another}disabled="disabled"{/if} />	
                </div>    
            </div>
        </div>
                    
        <div class="form_onecolumn_row">
            <div class="form_onecolumn_row_fieldname">
            	<label for="s_address" class="cm-required">Address</label>
            </div>
            <div class="form_onecolumn_row_field">
                <input type="text" id="s_address" name="user_data[s_address]" size="32" value="{$user_data.s_address}" class="input-text form_onecolumn_row_field_textbox " {if !$ship_to_another}disabled="disabled"{/if}  />
            </div>
        </div>
        
        <div class="form_onecolumn_row">
            <div class="form_onecolumn_row_fieldname">
            	<label for="s_address_2" class="">Address, line 2:</label>
            </div>
            <div class="form_onecolumn_row_field">
                <input type="text" id="s_address_2" name="user_data[s_address_2]" size="32" value="{$user_data.s_address_2}" class="input-text form_onecolumn_row_field_textbox" {if !$ship_to_another}disabled="disabled"{/if}  />
            </div>
        </div>
                
                            
        <div class="form_onecolumn_row">
            <div class="aside_left">
                <div class="form_onecolumn_row_fieldname">
                	<label for="s_city" class="cm-required">City</label>
                </div>
                <div class="form_onecolumn_row_field">
                    <input type="text" id="s_city" name="user_data[s_city]" size="32" value="{$user_data.s_city}" class="input-text form_onecolumn_row_field_textbox" {if !$ship_to_another}disabled="disabled"{/if}  />
                </div>
            </div>
            <div class="aside_right">
                <div class="form_onecolumn_row_fieldname">
                	<label for="s_state" class="cm-required">State</label>
                </div>
                <div class="form_onecolumn_row_field">
                    <select id="s_state" class="form_onecolumn_row_field_listbox" name="user_data[s_state]" {if !$ship_to_another}disabled="disabled"{/if}  >
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
                    <input type="text" id="s_state_d" name="user_data[s_state]" size="32" maxlength="64" value="{$user_data.s_state}" disabled="disabled" class="input-text hidden "/>
                    <input type="hidden" id="s_state_default" value="{$user_data.s_state}" />
                </div>    
            </div>
        </div>
        
                            
        <div class="form_onecolumn_row">
            <div class="aside_left">
                <div class="form_onecolumn_row_fieldname">
                	<label for="s_zipcode" class="cm-required cm-integer">Pincode</label>
                </div>
                <div class="form_onecolumn_row_field">
                    <input type="tel" id="s_zipcode" name="user_data[s_zipcode]" size="32" value="{$user_data.s_zipcode}" maxlength="6" class="input-text form_onecolumn_row_field_textbox"  {if !$ship_to_another}disabled="disabled"{/if}  />    
                </div>
            </div>
            <div class="aside_right">
                <div class="form_onecolumn_row_fieldname">
                	<label for="s_phone" class="cm-required cm-phone">Mobile Number</label>
                </div>
                <div class="form_onecolumn_row_field">
                <div class="float_left" style="margin-top:5px;">+91</div>
                    <input type="tel" id="s_phone" name="user_data[s_phone]" size="32" style="width:80%" value="{$user_data.s_phone}" class="input-text form_onecolumn_row_field_textbox" maxlength="10" {if !$ship_to_another}disabled="disabled"{/if}  />
                </div>
                    
                </div>    
            </div>
        </div>			
					

{/if}


{if $body_id}
</div>
{/if}

{/if}
{/if}

