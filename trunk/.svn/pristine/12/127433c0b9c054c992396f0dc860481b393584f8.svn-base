{* $Id: first_step.tpl 12766 2011-06-24 07:31:07Z subkey $ *}

{*assign var="lang_available_for_vendor_supplier" value=$lang.available_for_vendor}
{assign var="lang_new_vendor_supplier" value=$lang.new_vendor*}
{assign var="lang_fourth_step" value=$lang.fourth_step}
{assign var="store_status" value=$smarty.session.auth.company_id|fn_store_status}
{assign var="password_status" value=$smarty.session.auth.company_id|fn_password_change_status}
{assign var="company_status" value=$smarty.session.auth.company_id|fn_get_company_status}

{include file="views/profiles/components/profiles_scripts.tpl"}

{capture name="mainbox"}
{** /Item menu section **}
<div>{$lang.category_section_description}</div><br/>
<form action="{"storesetup.fourth_step"|fn_url}" method="post" class="{$form_class}" id="company_update_form" > {* company update form *}
{* class="cm-form-highlight"*}
<input type="hidden" name="company_id" value="{$company_data.company_id}" />
<input type="hidden" name="next_step" value="fourth_step" />

{hook name="storesetup:categories"}
{** third section **}
<div id="content_detailed" > {* content detailed *}
<fieldset >

<div style="width:48%;float:left;">{include file="common_templates/subheader.tpl" title=$lang.category_section}</div>
<div style="float: left; width: 48%; margin: 0pt 0pt 0pt 4%;">{include file="common_templates/subheader.tpl" title=$lang.fulfillment_section}</div>
{** Company categories section **}
<div id="content_categories"> {* content categories *}
{assign var="billing_categories" value=$company_data.company_id|fn_get_billing_categories}
  
    <div style="text-align:left;float:left; width:48%;">{$lang.categories_and_selling_fees}</div>
    <div style="float: left; margin: 0pt 0pt 0pt 4%; width:48%;">{$lang.fulfillment_fees} </div>
    <div style="clear:both"></div>
   
    <table width="100%"> <tr>
        <td  valign="top" style="width:44%;" width="48%">
          <table border="0" cellpadding="0" width="100%" cellspacing="0">
            <tr> <td>
              <table style="border-bottom:0px;" border="0" cellpadding="0" cellspacing="0" width="100%" class="table sortable float-left"  ><tr>
                  <th width="2%" class="center"><input type="checkbox" name="check_all" value="Y" title="Check / uncheck all" class="checkbox cm-check-items" /></th>
                   <th width="48%">{$lang.name}</th>
                   <th width="30%">{$lang.comission}</th>
              </tr></table>
           </td></tr>
       
        <tr> <td>
                
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background: none; border-top:0px; margin-top:-6px; padding-top:6px;" class="table sortable float-left">
             <tbody style="height: 920px; width: 100%; overflow-y:scroll; display:inline-block;">
                {foreach from=$billing_categories item="bcat"}
                  {if $bcat.category != 'NA'}
                    <tr>
                       <th width="2%" class="center"><input type="checkbox" class="checkbox cm-item cm-required"  id="bcat_{$bcat.id}" name="comission_data[{$bcat.id}]" value="{$bcat.default_commision}" {if $bcat.exists} checked=checked{/if}/></th>
                       <th width="48%">{$bcat.category}</th>
                        <th width="30%">{$bcat.default_commision}%</th>
                        </tr> {/if}
                    {/foreach}</tbody></table>
                        </td>
                 </tr>
        </table>
        </td>
    <td style="padding-left:4%; width:48%; vertical-align: top;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" class="table sortable float-right">

     <tr>
        <th width="10%" class="center">{$lang.weight_in_kg}</th>
        <th width="16%" style="text-align:center">{$lang.fee_prepaid_in_rs}</th>
        <th width="16%" style="text-align:center">{$lang.fee_cod_in_rs}</th>
    </tr> 
    {foreach from=$shipping_charge item="ship_charge"}
     <tr>  
 
        <th class="center">{$ship_charge.from/1000|number_format:1} kg - {$ship_charge.to/1000|number_format:1} kg</th>
        <th style="text-align:center">Rs. {$ship_charge.fee_prepaid}</th>
        <th style="text-align:center">Rs. {$ship_charge.fee_cod}</th>  
        
    </tr>
    {/foreach}
</table>
    </td>
    </tr></table>

</div> {* /content categories *}
{** /Company categories section **}


 {/hook}

</fieldset>
<br/>
{$lang.agreement_header_text}
<br/><br/>
    <span style="height:200px; overflow-y: scroll; display: block; border:1px solid #E4E4E4"> {$lang.agreement_text} </span>
<br/>  
    {$lang.agreement_accept_text}
</div>  
<br/>

    <input type="checkbox" name="i_agree" id="agree_check" value="yes" class="checkbox cm-check-items" {if $company_data.user_accepted_timestamp > 0 && $company_status!='P'} checked=checked  {/if}/>{$lang.agree_text}

    <br/><br/>
    
        {if $company_data.user_accepted_timestamp>0 }
    
        {assign var="date" value=$company_data.user_accepted_timestamp|date_format:"%Y-%b-%d %H:%M %p"}
        {$lang.text_after_acceptance|replace:'[Name]':$company_data.user_accepted_name|replace:'[Date]':$date}

   {/if}
   
	<label for="name" class="cm-required">{$lang.Name}:</label>
	<input type="name" id="name" name="name" size="32" maxlength="32" class="input-text cm-autocomplete-off round_five profile_detail_field" />
        <br/><span style="margin:38px;">{$lang.input_text}</span>
{hook name="storesetup:tabs_content"}{/hook}

{** Form submit section **}
<br/><br/>
<div>{$lang.do_you_know_5} </div>
 
<div id= "fourth_step_button_display" class="buttons-container cm-toggle-button buttons-bg">
{include file="buttons/save_cancel.tpl" but_name="dispatch[storesetup.fourth_step]" hide_first_button=$hide_first_button hide_second_button=$hide_second_button }	
</div>

<!-- This button is dispay when user doesn't check agree checkbox -->

<div id= "grey_button_disable" class="buttons-container buttons-bg">
   <span class="cm-button-main">
    <input type="submit"  name="save" value="Save" disabled=true style="background:#E4E4E4 !important; color:#bbb; -moz-text-shadow:none; text-shadow:none; border:0;">&nbsp;&nbsp;
    <input type="submit"  name="save" value="{$lang.save_and_next}" disabled=true style="background:#E4E4E4 !important; color:#bbb; -moz-text-shadow:none; text-shadow:none; border:0;">&nbsp;&nbsp;or
   &nbsp;&nbsp; <a href="vendor.php?dispatch=storesetup.third_step" class="underlined tool-link">Back</a>
        </span>
   </div>

{** /Form submit section **}

</form>

{hook name="storesetup:tabs_extra"}{/hook}

{*/capture*}
{*include file="common_templates/tabsbox.tpl" content=$smarty.capture.tabsbox group_name=$controller active_tab=$smarty.request.selected_section track=true*}

{/capture}

{include file="common_templates/mainbox.tpl" title="`$lang_fourth_step`" content=$smarty.capture.mainbox select_languages=true}

<!-- Show_popup -->

{if $smarty.request.show=='popup'}
  {include file="views/storesetup/components/congratulation_popup.tpl"}
{/if}
      <!-- Popup End -->
      
{literal}
    <script type="text/javascript">
        
        $(document).ready(function (){
            
        $('#grey_button_disable').show();
        $('#fourth_step_button_display').hide();
        $('#agree_check').click(function(){
        
        var checkbox = $('#agree_check').attr('checked');
            
            if(checkbox){
                $('#fourth_step_button_display').show();
                $('#grey_button_disable').hide();
                 } else{
                $('#fourth_step_button_display').hide();
                $('#grey_button_disable').show();
                  }
            });
        });
            
        </script>
{/literal}

