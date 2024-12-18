{* $Id: send_to_friend.tpl 12724 2011-06-21 12:48:57Z zeke $ *}
{** block-description:send_to_friend **}

<!--Form Small -->
<div class="form_onecolumnsmall" id="content_send_to_friend">

<form name="send_to_friend_form" action="{""|fn_url}" method="post" id="send_to_friend_form">
<input type="hidden" name="selected_section" value="send_to_friend" />
<input type="hidden" name="redirect_url" value="{$config.current_url}" />

<div class="form_onecolumnsmall_row">
<div class="form_onecolumnsmall_fieldname"><label for="send_yourname">{$lang.your_name}:</label></div>
<div class="form_onecolumnsmall_field">
<input class="form_onecolumnsmall_field_textbox" id="send_yourname" type="text" name="send_data[from_name]" value="{if $send_data.from_name}{$send_data.from_name}{/if}" />
</div>
</div>

<div class="form_onecolumnsmall_row">
<div class="form_onecolumnsmall_fieldname"><label for="send_youremail" class="cm-email">{$lang.your_email}:</label></div>
<div class="form_onecolumnsmall_field">
<input class="form_onecolumnsmall_field_textbox" id="send_youremail" type="text" name="send_data[from_email]" value="{if $send_data.from_email}{$send_data.from_email}{/if}" />
</div>
</div>

<div class="form_onecolumnsmall_row">
<div class="form_onecolumnsmall_fieldname"><label for="send_email" class="cm-required">{$lang.email_of_friend}:</label></div>
<div class="form_onecolumnsmall_field">
<textarea class="form_onecolumnsmall_field_textbox" id="send_email" rows="1" name="send_data[to_email]">{if $send_data.toemail}{$send_data.toemail}{/if}</textarea>
<span class="form_onecolumnsmall_fieldabout">{$lang.message_for_email_instruction}</span>
</div>
</div>

<div class="form_onecolumnsmall_row">
<div class="form_onecolumnsmall_fieldname"><label for="send_notes" class="cm-required">{$lang.your_message}:</label></div>
<div class="form_onecolumnsmall_field">
<textarea class="form_onecolumnsmall_field_textarea" id="send_notes"  name="send_data[notes]">
{if $send_data.notes}{$send_data.notes}{else}{$product.product|unescape}{/if}
</textarea>
</div>
</div>      
 {*{include file="common_templates/image_verification.tpl" id="send_to_friend" align="left"}*}
    
{if $settings.Image_verification.use_for_send_to_friend == "Y"}
	{include file="common_templates/image_verification.tpl" id="send_to_friend" align="left"}
{/if}
<div id="captcha_value3" style="float: left; padding: 15px 0 0 280px; margin:0;"></div>
<div id="captcha_error3" style="clear:both; float: left; padding: 0px 0 0 280px; margin:0; width:320px;"></div>
<div class="box_functions">

<div class="buttons-container">
	{include file="buttons/button.tpl" but_text=$lang.send but_name="dispatch[send_to_friend.send]"}
</div>

</div>

</form>


</div>
<div id="send_email_result" style="display:none; border-radius: 5px 5px 5px 5px;float: right; text-align:right; font-weight: bold; margin: 0 0 0 270px; padding: 5px 0; width: 341px;"></div>
<!--End Form Small -->


{literal}
<script type="text/javascript">
	$(function(){

		show_numbers_sf('send_data');
	});

</script>
{/literal}

{literal}
<script type="text/javascript">

function show_numbers_sf(field_arr_sf){
 
	var captcha_number_sf = Math.floor((Math.random()*1000000)+5);
     
	var captcha_text_sf = {/literal}'<p>{$lang.enter_the_text_for_captcha_for_verification} </p>'{literal};

	 captcha_text_sf = captcha_text_sf + '<label for="verification_code_sf" class="cm-required captcha_lbl" style="float:left; background:#CCC; cursor:pointer; height:26px; text-align:center; color:#000; display:block; padding:0 5px; width:63px; margin-right:10px; font:bold 13px/26px trebuchet ms; border:1px solid #666; margin-left:0px;" onclick="show_numbers_sf(\''+field_arr_sf+'\')">' + captcha_number_sf + '</label>'; 
	 captcha_text_sf = captcha_text_sf + '<input type="hidden" id="captcha_number_sf" name="'+field_arr_sf+'[captcha_number_sf]" value="'+captcha_number_sf+'" />';
	 captcha_text_sf = captcha_text_sf + '<input type="text" class="form_onecolumnsmall_field_textbox round_five profile_detail_field" style="width:70px;" id="verification_code_sf" name="'+field_arr_sf+'[verification_code_sf]" value="" />';

	$('#captcha_value3').html(captcha_text_sf);
}


function check_captcha_sf(){
	var captcha_number_sf = $('#captcha_number_sf').val();
	var verification_code_sf = $('#verification_code_sf').val();	
	if(captcha_number_sf != verification_code_sf){
		show_numbers_sf('send_data');
		$('#captcha_error3').html('<span style="color:red;">{/literal}{$lang.verification_code_not_same}{literal}</span>');
		return false;
	}else{
		return true;
	}
}

$('#send_to_friend_form').submit(function(){
	var verification_code_sf = $('#verification_code_sf').val();
	if (verification_code_sf == "") {
	     $('#captcha_error3').html('<span style="color:red;">{/literal}{$lang.verification_code_can_not_blank}{literal}</span>');
	      return false;
	 }
else if(check_captcha_sf())
{
if(check_captcha_sf())
{
return true
}

}
		
});

</script>
{/literal}
