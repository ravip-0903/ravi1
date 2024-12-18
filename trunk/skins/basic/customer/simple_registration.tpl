{if $smarty.session.auth.user_id==0}
{literal}
<style>
.pj2_signup_btn{float:right; background:
    url(images/signup_banner_btn.png) 0 0 no-repeat; padding:0 0 0 5px; line-height:32px; margin:6px 10px 0 20px;}
.pj2_signup_span{background:
    url(images/signup_banner_btn.png) right -40px no-repeat; float:left; padding:0 40px 0 20px; line-height:32px; text-decoration:none; color:#fff;}
	.pj2_signup_btn:hover{background:
    url(images/signup_banner_btn.png) -28px 0 no-repeat; padding:0 0 0 5px; line-height:32px; margin:6px 10px 0 20px;}
	.pj2_signup_btn:hover .pj2_signup_span{background:
    url(images/signup_banner_btn.png) right -80px no-repeat;}
	.email_simple_registration{display:none}
</style>
{/literal}
<form name="simple_registration_form" action="{""|fn_url}" method="post">
<div  class="form-field" style="padding:0px !important;border:1px solid #ddd; width:998px; margin:auto; height:45px; border-radius:5px; -moz-border-radius:5px; background:#eee ">
	<img style="margin:0 40px 0 10px; float:left;" src="images/banner_gift.png" />
    
    <span class="pj2_signup_btn">
    	<a href="javascript:document.getElementById('email_simple_reg_submit').click()"  class="pj2_signup_span">Sign up Now</a>
        <div style="display:none"><input type="submit" id="email_simple_reg_submit" value="register" />
        <input type="hidden" name="dispatch" value="simple_registration.register" /></div>
    </span>
    <label for="email_simple_registration" class="cm-required cm-email" style="display:none">&nbsp;</label>
    <input style="float:right; color:#ccc; border-radius:5px; -moz-border-radius:5px; border:1px solid #ccc; padding:6px; margin:7px 0 0;width:162px " type="email" value="Your email address" name="email" id="email_simple_registration" />
    
	<p style="font-size:18px; line-height:45px; margin:0px 40px 0 0px; font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; color:#454545; margin:0; padding:0;">
    {$lang.simple_registration}
    </p>
    
</div>
</form>
        
    
    {literal}
    <script>
    
	/*jQuery("#email_simple_reg_submit").bind('click',function(){
		
			if(document.getElementById('email_simple_registration').value == '')
			{
				alert('Please enter an email.');
				document.getElementById('email_simple_registration').focus();
				return false;
			}
			else
			{
				var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
				var emailaddressVal = jQuery("#email_simple_registration").val();
				if(!emailReg.test(emailaddressVal)) {
					alert('Please enter a valid email address.');
					document.getElementById('email_simple_registration').focus();
					return false;
				}
			}
			
		});*/
	jQuery("#email_simple_registration").bind('blur',function(){
			var val = document.getElementById('email_simple_registration').value;
			val = jQuery.trim(val);
			document.getElementById('email_simple_registration').value = val;
			if(val== '' || val=='Your email address'){
				document.getElementById('email_simple_registration').style.color = "#ccc";
				document.getElementById('email_simple_registration').value = 'Your email address';
			}
		});
	jQuery("#email_simple_registration").bind('focus',function(){
			var val = document.getElementById('email_simple_registration').value;
			
			if(val=='Your email address'){
				document.getElementById('email_simple_registration').style.color = "#111";
				document.getElementById('email_simple_registration').value = '';
			}
		
		});
    </script>
    {/literal}
{/if}