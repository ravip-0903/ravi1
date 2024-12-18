{* $Id: login_form.tpl 12290 2011-04-19 10:18:07Z bimib $ *}

{assign var="form_name" value=$form_name|default:main_login_form}

{capture name="login"}

<!--Login Panel -->
{if $form_name == "step_one_login_form"}
<form name="{$form_name}" action="{""|fn_url}" method="post">
<input type="hidden" name="form_name" value="{$form_name}" />
<input type="hidden" name="return_url" value="{$smarty.request.return_url|default:$config.current_url}" />
<input type="hidden" name="token" value="{$smarty.session.form_token_value}"/>
<div id="haveaccount" style="display:block;">

<div class="panel_login">
<div class="panel_login_row">

<div class="panel_login_fieldname">
<label for="login_{$id}" class="cm-required cm-trim{if $settings.General.use_email_as_login == "Y"} cm-email{/if}">{if $settings.General.use_email_as_login == "Y"}{$lang.email}{else}{$lang.username}{/if}:</label>
<span class="panel_login_fieldabout">(Required)</span>
</div>

<div class="panel_login_field">
<input type="email" id="login_{$id}" name="user_login" size="30" value="{$config.demo_username}" class="panel_login_textbox" />
<span class="panel_login_fieldaboutone">Your order details will be sent to this email address</span>
</div>

</div>
{if $smarty.session.express != 'Y'}
<div class="panel_login_row">

<div class="panel_login_fieldname">
<label></label>
<span class="panel_login_fieldabout"></span>
</div>

<div class="panel_login_field">
<input name="checkout_login_radio" onClick="checkout_radio(this.value);" type="radio" value="Y" class="panel_login_radiobox" id="checkout_login_radio1" /><label class="panel_login_fieldnametwo">
Continue without password
<br />
<span class="panel_login_fieldaboutone">(You do not need a password)</span>
</label>
</div>
</div>
{/if}

<div class="panel_login_row" >

<div class="panel_login_fieldname" {if $smarty.session.express == 'Y'}style="display:none;"{/if}>
<label></label>
<span class="panel_login_fieldabout"></span>
</div>

<div class="panel_login_field" {if $smarty.session.express == 'Y'}style="float:left; width:100%;"{/if}>
    {if $smarty.session.express != 'Y'}
<input name="checkout_login_radio" onClick="checkout_radio(this.value);" checked="checked" type="radio" value="N" class="panel_login_radiobox" id="checkout_login_radio2" />
 <label class="panel_login_fieldnametwo">
I have a ShopClues account and password 
<br />
<span class="panel_login_fieldaboutone">Sign in to your account and checkout faster</span>
</label>
{/if}
<div class="panel_login_row" id="checkout_passwd">

<div class="panel_login_fieldname">
<label for="psw_{$id}" class="cm-required">{$lang.password}:</label>
</div>

<div class="panel_login_field">
<input type="password" id="psw_{$id}" name="password" size="30" value="{$config.demo_password}" class="panel_login_textbox" />
<span class="panel_login_fieldaboutone">
<a href="{"auth.recover_password"|fn_url}">{$lang.forgot_password_question}</a>
</span>
{if $config.stay_signin}
<br />
<br />
<div class="ml_panellogin_authenticate">
<input style=" margin-top: 4px; float: left; margin-right: 5px; " class="ml_panellogin_authenticate_checkbox" type="checkbox" checked="checked" name="stay_sign_in" id="stay_sign_in" value="Y" />
<label class="ml_panellogin_authenticate_label">{$lang.stay_sign_in}</label>
</div>
{/if}
</div>

</div>

</div>

</div>
</div>

<div class="box_functions" {if $smarty.session.express == 'Y'}style="width:80%;"{/if}>

{hook name="index:login_buttons"}
  {include file="buttons/login.tpl" but_name="dispatch[auth.login]" but_role="action"}
  {* <input name="" type="button" class="box_functions_button" value="Continue" /> *}
 {/hook}
</div>

</div>
</form>
{else}

{if $smarty.request.return_url!=''}
{assign var="social_login_redirect_url" value=$smarty.request.return_url|urlencode}
{else}
{assign var="social_login_redirect_url" value="index.php?dispatch=profiles.myaccount"|urlencode}
{/if}

<form name="login_form" action="{""|fn_url}" method="post">
<input type="hidden" name="form_name" value="{$form_name}" />
<input type="hidden" name="return_url" value="{$smarty.request.return_url|default:$config.current_url}" />
<input type="hidden" name="token" value="{$smarty.session.form_token_value}"/>
<!--Login Page -->
<div class="aside_right" style="border-left:1px dashed #d2d7d9; padding-left:19px;">

<!--Social Login -->
<div class="ml_sociallogin">
<div class="ml_Pageheader">
<h1 class="ml_Pageheader_heading">Social login</h1>
<div class="ml_Pageheader_subheading">one-click sign in to ShopClues via your social account</div>
</div>
<div class="clearboth"></div>
<div class="ml_sociallogin_container margin_top_ten">
<div class="ml_sociallogin_contentt">	
		<a href="tools/fb_apps/fbaccess.php?auth=fb&page={$social_login_redirect_url}" class="scl_lnk_pop fb_bg_prd_pg">
			<span class="socl_icon"></span>
			<label class="socl_label">Sign in with Facebook</label>
		</a>
</div>

<div class="ml_sociallogin_contentt">
	<a href="tools/fb_apps/google_login/index.php?auth=google&page={$social_login_redirect_url}" class="scl_lnk_pop  goo_bg_prd_pg">
		<span class="socl_icon"></span>
		<label class="socl_label">Sign in with Google</label>
	</a>
</div>

</div>





<!--<div class="ml_sociallogin_container">
<div class="ml_sociallogin_content"><img src="images/monalisa/img_loginLinkedin.gif" width="150" height="22" /></div>
</div>-->

</div>
<!--End Social Login -->




<div class="ml_Pageheader">
<h1 class="ml_Pageheader_heading">Login to your account</h1>
<div class="ml_Pageheader_subheading">We are happy to see you return! Please log in to continue.</div>
</div>

<!--Login Panel -->
<div class="ml_panellogin margin_top_twentyfive">

<div class="ml_panellogin_row">
<div class="ml_panellogin_fieldname"><label>Login ID</label></div>
<div class="ml_panellogin_field">
<input type="email" id="email" name="user_login" size="30" value="{if $config.demo_username != ''}{$config.demo_username}{else}Enter Your Email{/if}" class="ml_panellogin_field_textbox" /></div>
</div>

<div class="ml_panellogin_row">
<div class="ml_panellogin_fieldname"><label>Password</label></div>
<div class="ml_panellogin_field"><input type="password" id="password" name="password" size="30" value="{if $config.demo_password != ''}{else}********{/if}" class="ml_panellogin_field_textbox" />
<a href="{"auth.recover_password"|fn_url}" class="ml_panellogin_field_link">{$lang.forgot_password_question}</a>
</div>

</div>

{if $config.stay_signin}
<div class="ml_panellogin_authenticate">
<input class="ml_panellogin_authenticate_checkbox" type="checkbox" checked="checked" name="stay_sign_in" id="stay_sign_in" value="Y" />
<label class="ml_panellogin_authenticate_label">{$lang.stay_sign_in}</label>
</div>
{/if}

<div class="ml_function ml_function_arrowleft margin_top_fifteen height_fifty">
<input name="dispatch[auth.login]" type="button" class="ml_function_button" value="Login" onclick="login_velid(); event.returnValue=false; return false;"/>
<input name="dispatch[auth.login]" type="submit" id="login_submit" style="visibility:hidden;"/>
</div>

<div class="ml_panellogin_message" id="login_error" style="display:none;">
<label class="ml_panellogin_message_error">Invalid Login</label>
</div>

</div>
<!--End Login Panel -->



</div>
<!--End Aside Left -->
</form>
<!--Registration Page -->
<div class="aside_left">

{$lang.registration_form_text}

<!--Panel Register -->
<form name="profile_form" action="{""|fn_url}" method="post">
<input id="selected_section" type="hidden" value="general" name="selected_section"/>
<input id="default_card_id" type="hidden" value="" name="default_cc"/>
<input type="hidden" name="profile_id" value="{$user_data.profile_id}" />
<input type="hidden" name="return_url" value="{$smarty.request.return_url}" />
<input type="hidden" name="token" value="{$smarty.session.form_token_value}"/>
<div class="ml_panelregistration">


<div class="aside_left">

<div class="ml_panelregistration_row">
<div class="ml_panelregistration_fieldname"><label>Your Name <b style="color:red;">*</b></label></div>
<div class="ml_panelregistration_field"><input style="height:15px;" type="text" id="b_firstname" name="user_data[b_firstname]" size="32" class="ml_panelregistration_field_textbox" tabindex="1" autocomplete="off"/></div>
<div class="ml_panelregistration_message" id="name_error"></div>
</div>
<div class="ml_panelregistration_row">
<div class="ml_panelregistration_fieldname"><label>Password <b style="color:red;">*</b></label></div>
<div class="ml_panelregistration_field"><input style="height:15px;" type="password" id="password1" name="user_data[password1]" size="32" maxlength="32" class="ml_panelregistration_field_textbox" tabindex="3" autocomplete="off"/></div>
<div class="ml_panelregistration_message" id="password1_error"></div>
</div>



<div class="ml_panelregistration_row">
<div class="ml_panelregistration_fieldname"><label>Date of Birth</label></div>
<div class="ml_panelregistration_field"><input style="height:15px;" type="text" id="datepicker" placeholder="Click to Select" name="user_data[birthday]" size="32" class="ml_panelregistration_field_textbox" tabindex="5">
</div>
<div class="ml_panelregistration_message" id="password1_error"></div>
</div>


<div class="ml_panelregistration_row">
<div class="ml_panelregistration_fieldname"><label>City</label></div>
<div class="ml_panelregistration_field"><input style="height:15px;" type="text" name="user_data[city]" size="32" class="ml_panelregistration_field_textbox" tabindex="8"/></div>
</div>


<div class="ml_panelregistration_row">
<div class="ml_panelregistration_fieldname"><label>Mobile</label></div>
<div class="ml_panelregistration_field"><input style="height:15px;" type="tel" name="user_data[phone]" size="32" maxlength="10" class="ml_panelregistration_field_textbox" tabindex="10"/></div>
</div>

</div>

<div class="aside_right">

<div class="ml_panelregistration_row">
<div class="ml_panelregistration_fieldname"><label>e-Mail ID</label></div>
<div class="ml_panelregistration_field"><input type="email" id="reg_email" name="user_data[email]" size="32" maxlength="128" class="ml_panelregistration_field_textbox" tabindex="2" autocomplete="off"/></div>
<div class="ml_panelregistration_message" id="email_error"></div>
</div>
<div class="ml_panelregistration_row">
<div class="ml_panelregistration_fieldname"><label>Confirm Password <b style="color:red;">*</b></label></div>
<div class="ml_panelregistration_field"><input style="height:15pxte;" type="password" id="password2" name="user_data[password2]" size="32" maxlength="32" class="ml_panelregistration_field_textbox" tabindex="4" autocomplete="off"/></div>
<div class="ml_panelregistration_message" id="password2_error"></div>
</div>

<div class="ml_panelregistration_row">
	<div class="ml_panelregistration_fieldname"><label>Gender</label></div>

	<div class="ml_panelregistration_field">
		<div><input class="ml_panelregistration_field_optionbox" type="Radio" name="user_data[gender]" value="M" tabindex="6"/><label>Male</label></div>
		<div><input class="ml_panelregistration_field_optionbox" type="Radio" name="user_data[gender]" value="F" tabindex="7"/><label>Female</label></div>
	</div>

</div>



<div class="ml_panelregistration_row" style="margin-top:23px;">
	<div class="ml_panelregistration_fieldname"><label>State</label></div>
	<div class="ml_panelregistration_field">
		
		<select style="height:28px;" class="ml_panelregistration_field_textbox" name="user_data[state]" id="state" tabindex="9">
			<option value="">- {$lang.select_state} -</option>
			{if $states}
			{foreach from=$states item=state}
			<option value="{$state.state}">{$state.state}</option>
			{/foreach}
			{/if}
		</select>

	</div>
	<div class="ml_panelregistration_message" id="password2_error"></div>
</div>



</div>

<div class="ml_panelregistration_authentication">
By clicking this button you agree and accept our <a href="user-agreement.html" tabindex="11">User Agreement</a> and <a href="privacy-policy.html" tabindex="12">Privacy Policy</a>. </div>


<div class="ml_function ml_function_arrowtoptoleft padding_top_twenty height_fifty">
<input name="dispatch[profiles.add.$_action]" type="button" class="ml_function_button_createaccountRegister" value=""onclick="return regis_velid();" tabindex="13"/>
<input name="dispatch[profiles.add.$_action]" type="submit" id="regis_submit" style="visibility:hidden;" />
</div>

</div>
</form>
<!--End Panel Register -->


<!--Registration Features -->
{$lang.registration_feature}
<!--End Registration Features -->




</div>
<!--End Registration Page -->

{/if}


<!--End Login Panel -->

{if $settings.Image_verification.use_for_login == "Y"}
	{include file="common_templates/image_verification.tpl" id="login_`$form_name`" align="left"}
{/if}


<!--<div class="float-left">
		<input class="valign checkbox" type="checkbox" name="remember_me" id="remember_me_{$id}" value="Y" />
		<label for="remember_me_{$id}" class="valign lowercase">{$lang.remember_me}</label>
	</div> -->
	

{/capture}

{if $style == "popup"}
	{$smarty.capture.login}
{else}
	<div{if $controller != "checkout"} class=""{/if}>
		{$smarty.capture.login}
	</div>

	{capture name="mainbox_title"}{$lang.sign_in}{/capture}
{/if}

{literal}
<script type="text/javascript">
function login_velid()
{
	var filter = /^\w[a-zA-Z0-9-_.]+@[a-zA-Z_]+.[a-zA-Z]+.[a-zA-Z]{2,3}$/;
	var val = document.getElementById('email').value;
	val = jQuery.trim(val);
	document.getElementById('email').value = val;
	var pass = document.getElementById('password').value;	
	
	if(val== '' || val == 'Enter Your Email' || pass == '')
	{
			document.getElementById('login_error').style.display = "block";
			return false;
	}
	else if(String(document.getElementById('email').value).search (filter) == -1)
	{
		document.getElementById('login_error').style.display = "block";
		return false;
	}
	else
	{
		document.getElementById('login_error').style.display = "none";
		document.getElementById('login_submit').click();

	}
}

function regis_velid()
{
	var flag = 0;
	
    var name = document.getElementById('b_firstname');
	var name_error = document.getElementById('name_error');

	var email = document.getElementById('reg_email');
	var email_error = document.getElementById('email_error');
	
	var password1 = document.getElementById('password1');
	var password1_error = document.getElementById('password1_error');
	
	var password2 = document.getElementById('password2');
	var password2_error = document.getElementById('password2_error');
	

    if(name.value == '' || name.value.length < 2)                         
	{
		name_error.innerHTML = '<label class="error">Please Enter Your Name</label>'; 
		flag++;
	}
    else                                    
	{
		name_error.innerHTML = ''; 
	}
	
	//var filter = /^\w[a-zA-Z0-9-_.]+@[a-zA-Z_]+.[a-zA-Z]+.[a-zA-Z]{2,3}$/;
	var filter = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
	if(email.value == '' || String(email.value).search (filter) == -1)                         
	{
		email_error.innerHTML = '<label class="error">Please Enter Correct Email ID</label>'; 
		flag++;
	}
    else                                    
	{
		email_error.innerHTML = '';
	}
	
	if(password1.value == '')                         
	{
		password1_error.innerHTML = '<label class="error">Please Enter Your Password</label>'; 
		flag++;
	}
	else if(password1.value.length < 2)                         
	{
		password1_error.innerHTML = '<label class="error">Password length should be atleast 2 characters</label>'; 
		flag++;
	}
    else                                    
	{
		password1_error.innerHTML = ''; 
	}
	
	if(password2.value == '' || password2.value != password1.value)                         
	{
		password2_error.innerHTML = '<label class="error">Password & Confirm Password does not match</label>'; 
		flag++;
	}
    else                                    
	{
		password2_error.innerHTML = ''; 
	}
	
    if(flag == 0)
    {
        document.getElementById('regis_submit').click();
    } 
    else
    {
        return false;
    }
}

jQuery("#email").bind('blur',function(){
			var val = document.getElementById('email').value;
			val = jQuery.trim(val);
			document.getElementById('email').value = val;
			if(val== '' || val=='Enter Your Email'){
				document.getElementById('email').value = 'Enter Your Email';
			}
});

jQuery("#email").bind('focus',function(){
		var val = document.getElementById('email').value;
		
		if(val=='Enter Your Email'){
			document.getElementById('email').value = '';
		}
	
});

jQuery("#password").bind('blur',function(){
			var val = document.getElementById('password').value;
			val = jQuery.trim(val);
			document.getElementById('password').value = val;
			if(val== '' || val=='********'){
				document.getElementById('password').value = '********';
			}
});

jQuery("#password").bind('focus',function(){
		var val = document.getElementById('password').value;
		
		if(val=='********'){
			document.getElementById('password').value = '';
		}
	
});

$("#datepicker").removeClass('hasDatepicker').datepicker({changeMonth: true, yearRange: '1950:2050',
    changeYear: true,onSelect: function() { $(".ui-datepicker a").removeAttr("href"); } });

</script>
{/literal}
