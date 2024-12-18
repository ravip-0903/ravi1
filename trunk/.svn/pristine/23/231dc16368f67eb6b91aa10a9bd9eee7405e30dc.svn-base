
{*On form submit call check_captcha() function*}

<div id="captcha_value">
 
    <div id="captcha_error"></div>
</div>


{literal}
  
    <script>
        
  function show_numbers(){
       
	var captcha_number = Math.floor((Math.random()*1000000)+1);
	var captcha_text = {/literal}'<p>{$lang.enter_the_text_for_captcha} </p>'{literal};
	var captcha_text = captcha_text + '<label for="verification_code" class="cm-required captcha_lbl" style="float:left; background:#CCC; cursor:pointer; height:26px; text-align:center; color:#000; display:block; padding:0 5px; width:63px; margin-right:10px; font:bold 13px/26px trebuchet ms; border:1px solid #666; margin-left:0px;">' + captcha_number + '</label>';
	//alert(captcha_text);
        var captcha_text = captcha_text + '<input type="hidden" id="captcha_number" value="'+captcha_number+'" />';
	var captcha_text = captcha_text + '<input type="text" class="form_onecolumnsmall_field_textbox round_five profile_detail_field" style="width:70px;" id="verification_code" value="" />';
	
         $('#captcha_value').html(captcha_text);
  }
  
  show_numbers()
           
 function check_captcha(){
	var captcha_number = $('#captcha_number').val();
	var verification_code = $('#verification_code').val();	
	if(captcha_number != verification_code){
		show_numbers('post_data');
		$('#captcha_error').html('<span style="color:red;">{/literal}{$lang.verification_code_not_same}{literal}</span>');
		return false;
	}else{
		return true;
	}
}

</script>
    
{/literal}