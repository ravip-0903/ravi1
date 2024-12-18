{* $Id: checkout_login.tpl 12452 2011-05-13 11:33:14Z alexions $ *}

<script type="text/javascript">
//<![CDATA[

function fn_switch_checkout_type(status)
{$ldelim}
	{if $checkout_type == 'classic'}
		{literal}
		$('#profiles_auth').switchAvailability(true);
		$('#profiles_box').switchAvailability(false);
		$('#account_box').switchAvailability(!status);
		$('#sa').switchAvailability(!$('elm_ship_to_another').attr('checked'));
		{/literal}
	{else}
		{literal}
		if (status == true) {
			$('#step_one_register').show();
		} else {
			$('#step_one_anonymous_checkout').show();
		}
		$('#step_one_login').hide();
		{/literal}
	{/if}
{$rdelim}
//]]>
</script>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr class="mob_stp_one" valign="top">
	<td style="width:400px;" id="chkout_login_section">
    {hook name="checkout:login_form"}
	
		{*{include file="common_templates/subheader.tpl" title=$lang.returning_customer}*}
		{include file="views/auth/login_form.tpl" form_name="step_one_login_form" result_ids="sign_io,checkout_steps,cart_status" id="checkout"}
	
	{/hook}
	
	{hook name="checkout:register_customer"}
	
		{*{if $settings.General.approve_user_profiles != "Y" || $settings.General.disable_anonymous_checkout != "Y"}
			{include file="common_templates/subheader.tpl" title=$lang.new_customer}
			{assign var="curl" value=$config.current_url|fn_query_remove:"login_type"}
		{/if}
		
		{if $settings.General.approve_user_profiles != "Y"}
			{$lang.text_dont_have_an_account_full}
			<div class="buttons-container right">{include file="buttons/button.tpl" but_href="$curl&amp;login_type=register" but_onclick="jQuery.processNotifications(); fn_switch_checkout_type(true);" but_text=$lang.register}</div>
			<div class="delim">&nbsp;</div>
		{/if}
		*}
		{if $settings.General.disable_anonymous_checkout != "Y"}
			{*{$lang.text_dont_want_to_register_an_account}*}
			<div id="anonymous_checkout" style="display: none;">
				<form name="step_one_anonymous_checkout_form" class="{$ajax_form}" action="{""|fn_url}" method="post">
					<input type="hidden" name="result_ids" value="checkout_steps" />
					
					{*{include file="views/profiles/components/profile_fields.tpl" section="C" nothing_extra="Y" id_prefix="soacf_" show_email=true}*}

<div class="panel_login">
<div class="panel_login_row">
{include file="views/profiles/components/profile_fields.tpl" class="panel_login_textbox" section="C" nothing_extra="Y" id_prefix="soacf_" show_email=true}
</div>



<div class="panel_login_row">

<div class="panel_login_field">
<input name="checkout_login_radio" onClick="checkout_radio('Y');" type="radio" value="1"  class="panel_login_radiobox" id="a_checkout_login_radio1"  /><label class="panel_login_fieldnametwo">
 Continue without password
<br />
<span class="panel_login_fieldaboutone">(You do not need a password)</span>
</label>
</div>

</div>

<div class="panel_login_row">
<div class="panel_login_field">
<input name="checkout_login_radio" onClick="checkout_radio('N');" type="radio"  value="2" checked="checked" class="panel_login_radiobox" id="a_checkout_login_radio2"  /> <label class="panel_login_fieldnametwo">
 I have a ShopClues account and password
<br />
<span class="panel_login_fieldaboutone">Sign in to your account and checkout faster</span>
</label>
</div>
</div>

</div>


					<div class="box_functions">
						{*{include file="buttons/button.tpl" but_name="dispatch[checkout.customer_info]" but_text=$lang.checkout_as_guest}*}
						{include file="buttons/button.tpl" but_name="dispatch[checkout.customer_info]" but_text=$lang.continue but_class="box_functions_button"}

					</div>
				</form>
			</div>
		{/if}
        {/hook}
	</td>
    <td class="stp_one_or_mob" style="font:25px trebuchet ms; color:#333;" align="center" valign="middle">Or</td>
    <td style="width:400px;" id="chkout_social_login">
    <!--Social Login -->
	<div class="ml_sociallogin" style="border-top:0px; margin-top:0px; padding-top:0px;">
<div class="ml_Pageheader">
<h1 class="ml_Pageheader_heading">{$lang.social_login}</h1>
<div class="ml_Pageheader_subheading">{$lang.social_login_text}</div>
</div>
<div class="clearboth"></div>

{assign var="step_two_redirect" value="index.php?dispatch=checkout.checkout&edit_step=step_two"}

<div class="ml_sociallogin_container margin_top_ten"  style="background-position:45px top;">
<div class="ml_sociallogin_content"><a href="tools/fb_apps/fbaccess.php?auth=fb&page={$step_two_redirect|urlencode}"><img src="images/monalisa/img_loginFacebook.gif" width="150" height="22" /></a></div>
</div>

<div class="ml_sociallogin_container" style="background-position:45px top;">
<div class="ml_sociallogin_content"><a href="tools/fb_apps/google_login/index.php?auth=google&page={$step_two_redirect|urlencode}"><img src="images/monalisa/img_loginGoogle.gif" width="150" height="22" /></a></div>
</div>



</div>
	<!--End Social Login -->
    </td>  
	
</tr>
</table>