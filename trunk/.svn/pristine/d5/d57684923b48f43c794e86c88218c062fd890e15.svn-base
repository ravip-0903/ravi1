<div class="customerSupport">
<div class="asideLeft no_mobile" style="float:left; width:170px;">

<div class="sidebox-wrapper ">
<h3 class="sidebox-title"><span>Help Topics</span></h3>
    <!--<span class="stars_icon"></span>-->
    <div class="clearboth"></div>
	<div class="sidebox-body">
            
       <ul>
		<li><a href="/shipping-and-delivery.html" id="sandd">Shipping &amp; Delivery</a></li>
		<li><a href="/cancel-items-or-orders.html" id="como">Cancel Or Modify Order</a></li>
		<li><a href="/return-or-replacement.html" id="ror">Return or Replacement</a></li>
		<li><a href="/payments.html" id="pay">Payments</a></li>
<li><a href="/ordering.html" id="ord">Ordering</a></li>
<li><a href="/product-query.html" id="pq">Product Query</a></li>
		<li><a href="/promotions-and-coupon.html" id="pac">Promotions &amp; Coupon</a></li>
		<li><a href="/clues-bucks.html" id="cb">Clues Bucks</a></li>
		<li><a href="/gift-certificate.html" id="gc">Gift Certificate</a></li>
		
		<li><a target="_blank" href="/buyer-protection.html">Buyer Protection</a></li>
<li><a target="_blank" href="/bandoftrust.html">Band of Trust</a></li>
<li><a target="_blank" href="http://www.shopclues.com/sell">Selling at ShopClues</a></li>
<li><a href="managing-your-account.html" id="mya">Managing Your Account</a></li>
	</ul>
</div>
	<div class="sidebox-bottom"><span>&nbsp;</span></div>
</div>
</div>

    <div class="asideCenter">
<div style="width:100%;">
<h1 class="main_heading" style="color: #EE811D; font-size: 22px;">{$lang.enter_email_and_password}</h1>

<form name="login_form" action="{""|fn_url}" method="post">
<input type="hidden" name="form_name" value="{$form_name}" />
<input type="hidden" name="return_url" value="{$smarty.request.return_url|default:$config.current_url}" />
<input type="hidden" name="token" value="{$smarty.session.form_token_value}"/>
<div style="margin-top:20px;">
<div><label style="color: #636566; font: 11px/20px Verdana,Geneva,sans-serif;">Login ID<span style="color: red;">*</span></label> </div>
<div><input type="email" id="email" name="user_login" style="border-radius:5px; padding:5px; border:1px inset;" value="{if $config.demo_username != ''}{$config.demo_username}{else}Enter Your Email{/if}" /></div>
</div>

<div style="margin-top:15px;">
<div><label style="color: #636566; font: 11px/20px Verdana,Geneva,sans-serif;">Password<span style="color: red;">*</span></label> </div>
<div><input type="password" id="password" name="password" style="border-radius:5px; padding:5px; border:1px inset;" value="{if $config.demo_password != ''}{else}********{/if}" />
<a href="{"auth.recover_password"|fn_url}" class="ml_panellogin_field_link">{$lang.forgot_password_question}</a>
</div></div>

{if $config.stay_signin}
<div class="ml_panellogin_authenticate" style="margin-top:5px;">
<input class="ml_panellogin_authenticate_checkbox" style="float:left; margin-right:5px;" type="checkbox" name="stay_sign_in" id="stay_sign_in" value="Y" checked="checked"/>
<label class="ml_panellogin_authenticate_label">{$lang.stay_sign_in}</label>
</div>
{/if}

<div class="ml_function margin_top_fifteen height_fifty" style="width:100px !important; margin-right:250px;">
<input name="dispatch[auth.login]" type="button" class="ml_function_button" value="Login" onclick="login_velid(); event.returnValue=false; return false;"/>
<!-- <a style="margin-left:30px;" href='{$config.http_location}/index.php?dispatch=write_to_us.add'>{$lang.skip_login}</a> -->
<input name="dispatch[auth.login]" type="submit" id="login_submit" style="visibility:hidden;"/>
</div>

<div class="ml_panellogin_authenticate" style="margin-top:5px; float:left; width:100%; font:13px trebuchet ms; color:#007AC0;">
{$lang.write_to_us_login_text}
<p>
    <a style="float:left" href="{"index.php?dispatch=auth.login_form&return_url=index.php?dispatch=write_to_us.write"}" style="margin-right:15px;">
        {$lang.not_yet_user}
    </a>
</p>
<br/>
<p>
    {$lang.customerSupport}   
</p>
</div>

<div class="ml_panellogin_message" id="login_error" style="display:none;">
<label class="ml_panellogin_message_error" style="color:red;">Invalid Login</label>
</div>

</form>


</div></div>

<div class="asideRight no_mobile" style="float:right; width:170px;">

<div class="sidebox-wrapper ">
<h3 class="sidebox-title"><span>Self Help Tools</span></h3>
    <!--<span class="stars_icon"></span>-->
    <div class="clearboth"></div>
	<div class="sidebox-body">
            
     <ul>
		<!--<li><a href="/track-orders.html">Track Order</a></li>-->
		<!--<li><a href="/cancel-items-or-orders.html">Confirm COD Order</a></li>-->
		<li><a target="_blank" href="https://secure.shopclues.com/index.php?dispatch=orders.search">Cancel Order</a></li>
		<!--<li><a href="/managing-your-account.html">Resend Shipping Details</a></li>
		<li><a href="/payments.html">Confirm Order Delivery</a></li>
		<li><a href="/promotions-and-coupon.html">Edit Order Address</a></li>-->
		<li><a target="_blank" href="https://secure.shopclues.com/index.php?dispatch=orders.search">Request Return Of Order</a></li>
		<li><a href="/clues-bucks.html" target="_blank">Clues Bucks Tracker</a></li>
        <li><a href="/giftcertificate" target="_blank">Gift Certificate Tracker</a></li>
		<li><a target="_blank" href="https://secure.shopclues.com/index.php?dispatch=profiles.manage_addressbook">Update Address Book</a></li>
		<li><a target="_blank" href="https://secure.shopclues.com/update-profile">Change Password</a></li>
                <li><a target="_blank" href="{$config.current_location}/index.php?dispatch=write_to_us.service_center">Search Service Center</a></li>
	</ul>
</div>
	<div class="sidebox-bottom"><span>&nbsp;</span></div>
</div>

</div>
        
</div>

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

               
</script>
{/literal}
