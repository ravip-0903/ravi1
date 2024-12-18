{$lang.labs_top_content}

<form class="labs_form_fill_nl" name="labs" method="post" id="labs">
    <div class="form-field">
        <label for="fname" class="cm-required">{$lang.labs_name}:</label>
        <input placeholder="Name" type="text" name="fname" id="fname" size="55" value="" class="input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" />
    </div>
    
    <div class="form-field">
        <label for="email" class="cm-required cm-email">{$lang.labs_email}:</label>
        <input placeholder="Email" type="text" name="email" id="email" size="55" value="" class="input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" />
    </div>
    
    <div class="form-field">
        <label for="mobile_no" class="cm-required cm-phone">{$lang.labs_phone}:</label>
        <input placeholder="Phone" type="text" name="mobile_no" id="mobile_no" size="55" maxlength="10" value="" class="input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" />
    </div>
    
    <div class="form-field">
        <label for="addr_1" class="cm-required">{$lang.labs_address}:</label>
        <input placeholder="Address Line 1" type="text" name="addr_1" id="addr_1" size="55" value="" class="input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" />
        <input placeholder="Address Line 2" type="text" name="addr_2" id="addr_2" size="55" value="" class="labs_txt_lrg input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" />
        <input placeholder="City" type="text" name="city" id="city" size="55" value="" class="labs_txt_sml input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" />
        <input placeholder="State" type="text" name="state" id="state" size="55" value="" class="labs_txt_sml_scnd input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" />
        <input placeholder="Country" type="text" name="country" id="country" size="55" value="" class="labs_txt_sml input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" />
        <input placeholder="Pincode" type="text" name="pincode" id="pincode" maxlength="6" size="55" value="" class="labs_txt_sml_scnd input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" />
        
    </div>

    <div class="form-field">
        <label for="institute_name" class="cm-required">{$lang.labs_institute_name}:</label>
        <select name="institute_name" id="institute_name" class="round_five profile_detail_field cont_nl_slt_width">
        <option value="">Select</option>
        {foreach from=$labs_institute_names item="labs_institute_name"}
            <option value="{$labs_institute_name}">{$labs_institute_name}</option>
        {/foreach}  
        </select>
    </div>
    
    <div class="form-field">
        <label for="graduation_complete" class="cm-required">{$lang.labs_expect_to_graduate}:</label>
        <input type="text" placeholder="Month & Year" name="graduation_complete" id="graduation_complete"  autocomplete="off" size="55" value="" class="input-text round_five profile_detail_field cont_nl_inpt_width clndr" maxlength="100" />
    </div>
    
    <div class="form-field">
        <label for="concentration" class="cm-required">{$lang.labs_concentration}:</label>
        <select name="concentration" id="concentration" class="round_five profile_detail_field cont_nl_slt_width">
        <option value="">Select</option>
        {foreach from=$labs_concentration item="labs_concentration_name"}
            <option value="{$labs_concentration_name}">{$labs_concentration_name}</option>
        {/foreach}      
        </select>
    </div>
    
    <div class="form-field">
        <label for="pro_working" class="cm-required ">{$lang.labs_pro_working_on}:</label>
        <select name="pro_working" id="pro_working" class="round_five profile_detail_field cont_nl_slt_width">
        <option value="">Select</option>
        {foreach from=$labs_project_working item="labs_project_working_on"}
            <option value="{$labs_project_working_on}">{$labs_project_working_on}</option>
        {/foreach}      
        </select>
    </div>
    
    <div class="form-field">
        <label for="expect_start_date" class="cm-required">{$lang.labs_expect_strt_date}:</label>
        <input type="text" placeholder="Expected Start Date for the Project"  autocomplete="off" name="expect_start_date" id="expect_start_date" size="55" value="" class="input-text round_five profile_detail_field cont_nl_inpt_width clndr" maxlength="100" />
    </div>
    
    <div class="form-field">
        <label for="expect_end_date" class="cm-required">{$lang.labs_expect_end_date}:</label>
        <input placeholder="Expected End Date for the Project" type="text" name="expect_end_date"  autocomplete="off" id="expect_end_date" size="55" value="" class="input-text round_five profile_detail_field cont_nl_inpt_width clndr" maxlength="100" />
    </div>
    
    <div class="form-field">
        <label for="pro_selection" class="cm-required">{$lang.labs_why_are_you_choosing}:</label>
        <textarea placeholder="Why are you choosing this project" name="pro_selection" id="pro_selection" rows="3" maxlength="500" cols="40" class="round_five profile_detail_field"></textarea>
    </div>
    
    <div class="form-field">
        <label for="pro_vision" class="cm-required">{$lang.your_vision_on_this}:</label>
        <textarea placeholder="Your vision on this project" name="pro_vision" id="pro_vision" maxlength="1500" rows="3" cols="40" class="round_five profile_detail_field"></textarea>
    </div>
     
    {include file="buttons/save.tpl" but_name="dispatch[labs.add]" but_text="Submit" but_role="button_main" but_class="box_functions_button nl_btn_blue"}  
    <div style="clear:both"></div>
    {$lang.labs_tnc}<br/>

</form>
<form name="invitation_labs" method="post" id="invitation_labs" style="float:right;" action=''>
<div class="labs_invitation_form">
<h1>{$lang.ins_invitation_labs}</h1>
<div class="form_field">
<label for="requester_name" class="cm-required"></label>
<input type="text" name="requester_name" id="requester_name" class="profile_detail_field round_five" placeholder="{$lang.labs_invitation_req_name}" value={if $request_data.rname}"{$request_data.rname}"{else}""{/if}/></div>
<div class="form_field">
<label for="requester_email" class="cm-required cm-email"></label>
<input type="text" id="requester_email" name="requester_email" class="profile_detail_field round_five" placeholder="{$lang.labs_invitation_req_email}" value={if $request_data.remail}"{$request_data.remail}"{else}""{/if}/></div>
<div class="form_field">
<label for="requester_institute" class="cm-required"></label>
<input type="text" name="requester_institute" id="requester_institute" class="profile_detail_field round_five" placeholder="{$lang.labs_invitation_institute}" value={if $request_data.institute}"{$request_data.institute}"{else}""{/if}/></div>

<div class="form_field">
<label for="contact_name" class="cm-required"></label>
<input type="text" id="contact_name" name="contact_name" class="profile_detail_field round_five" placeholder="{$lang.labs_invitation_con_name}" value={if $request_data.cname}"{$request_data.cname}"{else}""{/if}/></div>
<div class="form_field">
<label for="designation" class="cm-required"></label>
<input type="text" id="designation" name="designation" class="profile_detail_field round_five" placeholder="{$lang.labs_invitation_con_designation}" value={if $request_data.designation}"{$request_data.designation}"{else}""{/if}/></div>
<div class="form_field">
<label for="contact_email" class="cm-required cm-email"></label>
<input type="text" id="contact_email" name="contact_email" class="profile_detail_field round_five" placeholder="{$lang.labs_invitation_con_email}" value={if $request_data.cemail}"{$request_data.cemail}"{else}""{/if}/></div>
<div class="form_field">

<label for="contact_phone" class="cm-required cm-phone"></label>
<input type="text" maxlength="10" id="contact_phone" name="contact_phone" class="profile_detail_field round_five" placeholder="{$lang.labs_invitation_con_phone}" value={if $request_data.cphone}"{$request_data.cphone}"{else}""{/if}/></div>
<div class="form_field">{$lang.labs_invitation_reason}</div>
<div class="form_field">
<label for="reason" class="cm-required"></label>
<textarea type="text" id="reason" name="reason" class="profile_detail_field round_five" maxlength="300">{if $request_data.reason}{$request_data.reason}{/if}</textarea></div>
<input type="hidden" name="dispatch_new" value="submit_project">
</div>
<div class="form_field" style="float:left; clear:both;">
    <input class="box_functions_button nl_btn_blue" type="submit" name="dispatch[labs.add]" value="Submit" style="margin-left:5px; curser:hand; cursor:pointer; margin-top:10px;">
</div>
</form>
<div class="clearboth"></div>
{$lang.labs_ins_invited}
{$lang.labs_project_submission}

{literal}
<script type="text/javascript">

$("#expect_start_date").removeClass('hasDatepicker').datepicker({changeMonth: true, minDate: 0,
    changeYear: true,onSelect: function() { $(".ui-datepicker a").removeAttr("href"); } });
$("#expect_end_date").removeClass('hasDatepicker').datepicker({changeMonth: true,minDate: 0,
    changeYear: true,onSelect: function() { $(".ui-datepicker a").removeAttr("href"); } });
$("#graduation_complete").removeClass('hasDatepicker').datepicker({changeMonth: true,
    changeYear: true,onSelect: function() { $(".ui-datepicker a").removeAttr("href"); } });



$('#labs').submit(function(){

    var pincode = $.trim($('#pincode').val());
    var state = $.trim($('#state').val());
    var city = $.trim($('#city').val());
    var country = $.trim($('#country').val());


    $('#pincode').css({"border":"","background-color":""});
    $('#state').css({"border":"","background-color":""});
    $('#city').css({"border":"","background-color":""});
    $('#country').css({"border":"","background-color":""});
    var stat = 0;
    if(pincode == '' || isNaN(pincode))
    { 

        $('#pincode').css({"border":"1px dashed #cc0000","background-color":"#ffdddd"});
        stat++;
    }
    if(state == '')
    {
        $('#state').css({"border":"1px dashed #cc0000","background-color":"#ffdddd"});
         stat++;
    }
    if(city == '')
    {
        $('#city').css({"border":"1px dashed #cc0000","background-color":"#ffdddd"});
         stat++;
    }
    if(country == '')
    {
        $('#country').css({"border":"1px dashed #cc0000","background-color":"#ffdddd"});
         stat++;
    }
        if(stat >0)
        {
            return false;
        }

});
</script>   

{/literal}