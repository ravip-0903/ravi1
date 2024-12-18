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


        <div class="panel_login_row" >

            <div class="panel_login_fieldname">
                <label></label>
                <span class="panel_login_fieldabout"></span>
            </div>

            <div class="panel_login_field">
                <input name="checkout_login_radio" onClick="checkout_radio(this.value);" checked="checked" type="radio" value="N" class="panel_login_radiobox" id="checkout_login_radio2" />
                <label class="panel_login_fieldnametwo">
                    I have a ShopClues account and password 
                    <br />
                    <span class="panel_login_fieldaboutone">Sign in to your account and checkout faster</span>
                </label>

                <div class="panel_login_row" id="checkout_passwd">

                    <div class="panel_login_fieldname">
                        <label for="psw_{$id}" class="cm-required">{$lang.password}:</label>
                    </div>

                    <div class="panel_login_field">
                        <input type="password" id="psw_{$id}" name="password" size="30" value="{$config.demo_password}" class="panel_login_textbox" />
                        <span class="panel_login_fieldaboutone">
                            <a href="{"auth.recover_password"|fn_url}">{$lang.forgot_password_question}</a>
                        </span>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <div class="box_functions">

        {hook name="index:login_buttons"}
        Â {include file="buttons/login.tpl" but_name="dispatch[auth.login]" but_role="action"}
        Â {* <input name="" type="button" class="box_functions_button" value="Continue" /> *}
        {/hook}
    </div>

</div>
</form>
{else}


{if $smarty.request.category_id !=''}
    {assign var="social_login_redirect_url" value="categories.view&category_id=`$smarty.request.category_id`"|fn_url}
    {assign var="social_login_redirect_url" value=$config.http_host|cat:$social_login_redirect_url|urlencode}
    {assign var="social_type" value=2}
{else}
{assign var="social_login_redirect_url" value=$url|urlencode}
{assign var="social_type" value=1}
{/if}
<div class="frm_blk_left_side">
    <img src="http://cdn.shopclues.com/skins/basic/customer/images/icons/close_popupbox.png" id="fb_login_close_signin" style="cursor:pointer; float:right; margin: -35px -35px 0 0;" />
   
    <div class="signin_block_nl">
        <div class="signin_clk_nl active" id="signup">Signup</div>
        <div class="signin_clk_nl" id="signin">Login</div>
    </div>
    <form name="login_form" action="{""|fn_url}" method="post" id="login_form_blk" style="display:none;">
        <input type="hidden" name="form_name" value="{$form_name}" />
        <input type="hidden" name="return_url" value="{$smarty.request.return_url|default:$config.current_url}" />
        <input type="hidden" name="token" value="{$smarty.session.form_token_value}"/>
        <!--Login Page -->
        <div class="aside_logn_frm" id="main_login_form">
            <!--Login Panel -->


            <div class="input_nl_popup_txt">
                <input type="email" id="email" name="user_login" size="30" value="{if $config.demo_username != ''}{$config.demo_username}{else}Enter Your Email{/if}" class="txt_box_popup" /></div>


                <div class="input_nl_popup_txt">
                    <div class=""><input type="password" id="password" name="password" size="30" value="{if $config.demo_password != ''}{else}********{/if}" class="txt_box_popup" />
                    </div>
                </div>
                {if $config.stay_signin}
                 <div class="input_nl_popup_txt">
                    <input class="" type="checkbox" name="stay_sign_in" checked="checked" id="stay_sign_in" value="Y" />
                    <label class="rmbr_popup_nl">{$lang.stay_sign_in}</label>
                </div>
                {/if}
                <div class="" id="login_error" style="display: none;">
                    <label class="error">Invalid Login</label>
                </div>

                <!--<div class="input_nl_popup_txt">
                    <input class="" type="checkbox" name="remember_me" id="remember_me_{$id}" value="Y" />
                    <label class="rmbr_popup_nl">{$lang.remember_me}</label>
                </div>
            -->


            <div class="input_nl_popup_txt">
                <span class="button-submit" style="float:left;"><input name="dispatch[auth.login]" type="button" class="popup_btn" value="Login" onclick="login_velid(); event.returnValue=false; return false;"/></span>
                <a href="{"auth.recover_password"|fn_url}" class="frgt_pswd_nl">{$lang.forgot_password_question}</a>
                <input name="dispatch[auth.login]" type="submit" id="login_submit" style="visibility:hidden;"/>
                <input name="type_stat" value='1' style="visibility:hidden;"/>
            </div>



        </div>
        <!--End Login Panel -->

        <!--End Aside Left -->
        <!--Social Login -->
        <div class="ml_sociallogin" style="width:200px; margin:0px 0 0 40px; border:0;": id="social_login">
            <div class="clearboth"></div>
            <div class="ml_sociallogin_container margin_top_ten popup_blk_nl_login fb_login_new_pop">
                <div class="ml_sociallogin_content">
                    <a href="tools/fb_apps/fbaccess.php?auth=fb&type={$social_type}&page={$social_login_redirect_url}" class="scl_lnk_pop fb_bg_prd_pg">
                        <span class="socl_icon"></span>
                        <label class="socl_label">Sign in with Facebook</label>
                    </a>
                </div>
            </div>

            <div class="ml_sociallogin_container popup_blk_nl_login fb_login_new_pop">
                <div class="ml_sociallogin_content">
                    <a href="tools/fb_apps/google_login/index.php?auth=google&type={$social_type}&page={$social_login_redirect_url}" class="scl_lnk_pop  goo_bg_prd_pg">
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
</form>


<!--Registration Page -->
<div id="registration_form">
    {*{$lang.registration_form_text}*}

    <!--Panel Register -->
    <form name="profile_form" action="{""|fn_url}" method="post">
        <input id="selected_section" type="hidden" value="general" name="selected_section"/>
        <input id="default_card_id" type="hidden" value="" name="default_cc"/>
        <input type="hidden" name="profile_id" value="{$user_data.profile_id}" />
        <div class="ml_panelregistration frm_reg_left">

            <div class="input_nl_popup_txt">
                <input type="text" id="b_firstname" name="user_data[b_firstname]" size="32" class="txt_box_popup" tabindex="1" autocomplete="off" placeholder="Enter Your Name"/>
                <span class="ml_panelregistration_message error_fb_frm"  style="height:auto;" id="name_error"></span>
            </div>

            <div class="input_nl_popup_txt">
                <input type="email" id="reg_email" name="user_data[email]" size="32" maxlength="128" class="ml_panelregistration_field_textbox txt_box_popup" tabindex="2" autocomplete="off" placeholder="Enter Your Email"/>
                <span class="ml_panelregistration_message error_fb_frm"  style="height:auto;" id="email_error"></span>
            </div>

            <div class="input_nl_popup_txt">
                <input type="password" id="password1" name="user_data[password1]" size="32" maxlength="32" class="ml_panelregistration_field_textbox txt_box_popup" tabindex="3" autocomplete="off" placeholder="Enter Password"/>
                <span class="ml_panelregistration_message error_fb_frm"  style="height:auto;" id="password1_error"></span>
            </div>


            <div class="input_nl_popup_txt">
                <input type="password" id="password2" name="user_data[password2]" size="32" maxlength="32" class="ml_panelregistration_field_textbox txt_box_popup" tabindex="4" autocomplete="off" placeholder="Re-enter Your Password"/>
                <span class="ml_panelregistration_message error_fb_frm" style="height:auto;" id="password2_error"></span>
            </div>


             <div class="input_nl_popup_txt">
                <input type="text" id="datepicker" placeholder="Date of Birth" name="user_data[birthday]" size="32" class="ml_panelregistration_field_textbox txt_box_popup" autocomplete="off" tabindex="5">

            </div>

            <div class="ml_panelregistration_row">

                <div class="ml_panelregistration_field">
                    <div><input class="ml_panelregistration_field_optionbox" type="Radio" name="user_data[gender]" value="M" tabindex="6"/><label>Male</label></div>
                    <div><input class="ml_panelregistration_field_optionbox" type="Radio" name="user_data[gender]" value="F" tabindex="7"/><label>Female</label></div>
                </div>

            </div>

            <div class="input_nl_popup_txt">
            <input type="tel" name="user_data[phone]" size="32" maxlength="10" class="ml_panelregistration_field_textbox txt_box_popup" tabindex="8" placeholder="Mobile" autocomplete="off"/>
            </div>


            <div class="ml_panelregistration_authentication" style="width:220px;">
                By clicking this button you agree and accept our <a href="user-agreement.html" tabindex="9">User Agreement</a> and <a href="privacy-policy.html" tabindex="10">Privacy Policy</a>. </div>

                <input type="hidden" name="ref" value="pop" />
                            <!--<div class="ml_function ml_function_arrowtoptoleft padding_top_twenty height_fifty">
                                <input name="dispatch[profiles.add.$_action]" type="button" class="ml_function_button_createaccountRegister" value=""onclick="return regis_velid();" tabindex="7"/>
                                <input name="dispatch[profiles.add.$_action]" type="submit"  id="regis_submit" style="visibility:hidden;" />
                            </div>-->


                            <div class="input_nl_popup_txt">
                                <span class="button-submit" style="float:left;">
                                    <input name="dispatch[profiles.add.$_action]" type="button" value="Create Account" class="popup_btn" value=""onclick="return regis_velid();" tabindex="11"/>
                                </span>
                                <input name="dispatch[profiles.add.$_action]" type="submit" id="regis_submit" style="visibility:hidden;" />
                            </div>
                            <input name="type_stat" value='1' style="visibility:hidden;"/>

                        </div>
                    </form>
                    <!--End Panel Register -->

                    <!--End Registration Page -->

                    <!--facebook prefill login -->
                    <div id="section" class="fb_signup_popup_nl" style="margin-top:0;">
                        <div id="section_fb" class="fb_signup_popup_nl" style="display:none;">
                            {assign var="retu_url" value=$config.http_location|cat:"/"}
                            {assign var="product_id_fb" value=$smarty.request.product_id|base64_encode}
                            {assign var="url" value='index.php?dispatch=products.seller_connect&product_id='|cat:$product_id_fb}
                            {assign var="retu_url" value=$retu_url|cat:"index.php?dispatch=auth.fb_login"}
                            {literal}
                            <iframe src="https://www.facebook.com/plugins/registration?
                            client_id={/literal}{$config.shopclues_app_id}{literal}&
                            redirect_uri={/literal}{$retu_url}{literal}&
                            fields=[ {'name':'name'},
                            {'name':'email'},
                            {'name':'thisUserID','description':'thisUserID','type':'hidden','default':'{/literal}{$url|urlencode}{literal}'},
                            {'name':'location'},
                            {'name':'gender'},
                            {'name':'birthday'},
                            {/literal}{if $config.captcha_email_status=='TRUE'}{literal}
                            {'name':'password'}, {'name':'captcha'}
                            {/literal}{/if}{literal}
                            ]   "
                            scrolling="auto"       
                            frameborder="no"
                            style="border:none"
                            allowTransparency="true"
                            width="100%"
                            height="390">
                        </iframe>
                        {/literal}
                    </div>
                    <div class="ml_sociallogin" style="width:200px; margin:0px 0 0 40px; border:0;": id="social_login_fb">
            <div class="clearboth"></div>
            <div class="ml_sociallogin_container margin_top_ten popup_blk_nl_login fb_login_new_pop">
                <div class="ml_sociallogin_content">
                    <a href="tools/fb_apps/fbaccess.php?auth=fb&type={$social_type}&page={$social_login_redirect_url}" class="scl_lnk_pop fb_bg_prd_pg">
                        <span class="socl_icon"></span>
                        <label class="socl_label">Sign in with Facebook</label>
                    </a>
                </div>
            </div>

            <div class="ml_sociallogin_container popup_blk_nl_login fb_login_new_pop">
                <div class="ml_sociallogin_content">
                    <a href="tools/fb_apps/google_login/index.php?auth=google&type={$social_type}&page={$social_login_redirect_url}" class="scl_lnk_pop  goo_bg_prd_pg">
                        <span class="socl_icon"></span>
                        <label class="socl_label">Sign in with Google</label>
                    </a>
                </div>
            </div>
            {$lang.social_filled_image}
<!--<div class="ml_sociallogin_container">
<div class="ml_sociallogin_content"><img src="images/monalisa/img_loginLinkedin.gif" width="150" height="22" /></div>
</div>-->

</div>

                </div>
                <!--facebook prefill login ends here -->




            </div>
            <!--End Registration Page -->
</div>
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
<div id="fb-root"></div>
{literal}
<script type="text/javascript">



    jQuery("#signin").click(function(){
        jQuery('#registration_form').hide();
        jQuery('#signin').addClass("active");
        jQuery('#signup').removeClass("active");
        jQuery('#login_form_blk').show();
    });
    jQuery("#signup").click(function(){
        jQuery('#signup').addClass("active");
        jQuery('#signin').removeClass("active");
        jQuery('#registration_form').show();
        jQuery('#login_form_blk').hide();
    });
    jQuery("#fb_login_close_signin").click(function(){
        jQuery('#fb_login_popup').hide();        
    });
    

    // jQuery(document).ready(function() {
        var app_ids = '{/literal}{$config.shopclues_app_id}{literal}';
        window.fbAsyncInit = function() {
            FB.init({appId: app_ids, status: true, cookie: true,
               xfbml: true});
            FB.getLoginStatus(function(o) { 
             if (!o && o.status) return;
             if(o.status == "connected" ){ 
                jQuery('.subscribe_popup_anniversary').show();
             jQuery('.section_fb_new').show();
        jQuery('.social_login_fb_new').hide();
        jQuery('.right_pro_blk').show();       
                jQuery('#section_fb').show();
                jQuery('#social_login_fb').hide();

          // USER IS LOGGED IN AND HAS AUTHORIZED APP
      } else if (o.status == 'not_authorized') {
        jQuery('.subscribe_popup_anniversary').show();
         jQuery('.section_fb_new').show();
          jQuery('.social_login_fb_new').hide();
          jQuery('.right_pro_blk').show();
    		  jQuery('#section_fb').show();
             jQuery('#social_login_fb').hide();

          // USER IS LOGGED IN TO FACEBOOK (BUT HASN'T AUTHORIZED YOUR APP YET)
      } else {     
        jQuery('.social_login_anniversary_div').show();
        jQuery('#social_login_fb').show();
         jQuery('.social_login_fb_new').show();
          // USER NOT CURRENTLY LOGGED IN TO FACEBOOK
      }
  });
        };
        (function() {
            var e = document.createElement('script'); e.async = true;
            e.src = document.location.protocol +
            '//connect.facebook.net/en_US/all.js';
            document.getElementById('fb-root').appendChild(e);
        }());


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
<div id="fb-root"></div>
