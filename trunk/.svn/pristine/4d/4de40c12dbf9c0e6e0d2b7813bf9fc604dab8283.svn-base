{$lang.advertisment_top_content}

<form class="labs_form_fill_nl" name="advertisement" method="post" id="advertisement">
	<div class="form-field">
        <label for="fname" class="cm-required">{$lang.advertisement_name}:</label>
        <input type="text" name="fname" id="fname" size="55" value="" class="input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" />
    </div>
    
    <div class="form-field">
        <label for="email" class="cm-required cm-email">{$lang.advertisement_email}:</label>
        <input type="text" name="email" id="email" size="55" value="" class="input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" />
    </div>
    
    <div class="form-field">
        <label for="mobile_no" class="cm-required cm-phone">{$lang.advertisement_phone}:</label>
        <input type="text" name="mobile_no" id="mobile_no" size="55" maxlength="10" value="" class="input-text round_five profile_detail_field cont_nl_inpt_width" />
    </div>
    
    <div class="form-field">
        <label for="brand" class="cm-required">{$lang.advertisement_brand_to_advertise}:</label>
        <input type="text" name="brand" id="brand" size="55" value="" class="input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" />
    </div>

    <div class="form-field">
        <label for="category" class="cm-required">{$lang.advertisement_location}:</label>
        <select name="category" id="category" class="round_five profile_detail_field cont_nl_slt_width">
            <option value="">Select</option>
            {foreach from=$advertisement_category_ads item="category"}
            <option value="{$category}">{$category}</option>
            {/foreach}      
        </select>
    </div>
    <div class="form-field">
        <label for="budget" class="cm-required">{$lang.advertisement_budget}:</label>
        <select name="budget" id="budget" class="round_five profile_detail_field cont_nl_slt_width">
            <option value="">Select</option>
            {foreach from=$advertisement_ads_budget item="budget"}
            <option value="{$budget}">{$budget}</option>
            {/foreach}      
        </select>
    </div>
    <div class="form-field">
        <label for="frequency" class="cm-required">{$lang.advertisement_frequency}:</label>
        <select name="frequency" id="frequency" class="round_five profile_detail_field cont_nl_slt_width">
            <option value="">Select</option>
            {foreach from=$advertisement_ads_spend_frequency item="frequency_spend"}
            <option value="{$frequency_spend}">{$frequency_spend}</option>
            {/foreach}      
        </select>
    </div>
    
    <div class="form-field">
        <label for="comments" class="cm-required">{$lang.advertisement_comments}:</label>
       <textarea id="comments" class="round_five profile_detail_field cont_nl_slt_width" name="comments" cols="67" rows="10"></textarea>
    </div>


    {include file="buttons/save.tpl" but_name="dispatch[advertisement.contact]" but_text="Submit" but_role="button_main" but_class="box_functions_button nl_btn_blue"}  
    <div style="clear:both"></div>

</form>
<div class="clearboth"></div>


{literal}
<script type="text/javascript">

    $(".cont_nl_slt_width").change(function () {
//	alert("in");
if($(this).val() == "") $(this).addClass("place_holder_nl");
else $(this).removeClass("place_holder_nl");
});
    $(".cont_nl_slt_width").change();
</script>	

{/literal}