<h1 class="block-packs-title" style="clear:both; float:left; margin:20px 0 0 ">
    <span style="float:left;">{$lang.labs_project_title}</span>
</h1>
<form class="labs_form_fill_nl" name="labs" method="post" id="labs" enctype="multipart/form-data" >
	<div class="form-field">
        <label for="fname" class="cm-required">{$lang.labs_name}:</label>
        <input placeholder="Name" type="text" name="fname" id="fname" size="55" value={if $request_data.name}"{$request_data.name}"{else}""{/if} class="input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" />
    </div>
    
    <div class="form-field">
        <label for="email" class="cm-required cm-email">{$lang.labs_email}:</label>
        <input placeholder="Email" type="text" name="email" id="email" size="55" value={if $request_data.email}"{$request_data.email}"{else}""{/if} class="input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" />
    </div>
    
    <div class="form-field">
        <label for="mobile_no" class="cm-required cm-phone">{$lang.labs_phone}:</label>
        <input placeholder="Phone" type="text" name="mobile_no" id="mobile_no" size="55" maxlength="10" value={if $request_data.phone}{$request_data.phone}{else}""{/if} class="input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" />
    </div>
    
    <div class="form-field">
        <label for="institute_name" class="cm-required">{$lang.labs_institute_name}:</label>
        <select name="institute_name" id="institute_name" class="round_five profile_detail_field cont_nl_slt_width">
        <option value="">Select</option>
        {foreach from=$labs_institute_names item="labs_institute_name"}
            <option {if $request_data.institute == $labs_institute_name}selected="selected"{/if} value="{$labs_institute_name}">{$labs_institute_name}</option>
        {/foreach}  
        </select>
    </div>

    <div class="form-field">
        <label for="pro_working" class="cm-required ">{$lang.labs_pro_submitting}:</label>
        <select name="pro_working" id="pro_working" class="round_five profile_detail_field cont_nl_slt_width">
        <option value="">Select</option>
        {foreach from=$labs_project_working item="labs_project_working_on"}
            <option {if $request_data.project == $labs_project_working_on}selected="selected"{/if} value="{$labs_project_working_on}">{$labs_project_working_on}</option>
        {/foreach}      
        </select>
    </div>
    
    <div class="form-field">
        <label for="attach_your_code" class="cm-required">{$lang.attach_your_code}:</label>
        <input class="fb_text_field" type="file" name="attach_your_code" id="attach_your_code" />
    </div>
    
    <div class="form-field">
        <label class="cm_option" for="presentation">{$lang.attach_presentation}:</label>
                <input class="fb_text_field" type="file" name="presentation" id="presentation" />
    </div>
    
    <div class="form-field">
        <label class="cm_option" for="write_up">{$lang.attach_write_up}:</label>
               <input class="fb_text_field" type="file" name="write_up" id="write_up" />
    </div>
     
    {include file="buttons/save.tpl" but_name="dispatch[labs.submit_project]" but_text="Submit" but_role="button_main" but_class="box_functions_button nl_btn_blue"}  
    <div style="clear:both"></div>
   
</form>

{literal}
<script type="text/javascript">

$(".cont_nl_slt_width").change(function () {
//  alert("in");
    if($(this).val() == "") $(this).addClass("place_holder_nl");
    else $(this).removeClass("place_holder_nl");
});
$(".cont_nl_slt_width").change();
</script>   

{/literal}