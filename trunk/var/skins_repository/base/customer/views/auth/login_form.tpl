{* $Id: login_form.tpl 12290 2011-04-19 10:18:07Z bimib $ *}

{assign var="form_name" value=$form_name|default:main_login_form}

{capture name="login"}
<form name="{$form_name}" action="{""|fn_url}" method="post">
<input type="hidden" name="form_name" value="{$form_name}" />
<input type="hidden" name="return_url" value="{$smarty.request.return_url|default:$config.current_url}" />

<div class="form-field">
	<label for="login_{$id}" class="cm-required cm-trim{if $settings.General.use_email_as_login == "Y"} cm-email{/if}">{if $settings.General.use_email_as_login == "Y"}{$lang.email}{else}{$lang.username}{/if}:</label>
	<input type="text" id="login_{$id}" name="user_login" size="30" value="{$config.demo_username}" class="input-text cm-focus" />
</div>

<div class="form-field">
	<label for="psw_{$id}" class="cm-required">{$lang.password}:</label>
	<input type="password" id="psw_{$id}" name="password" size="30" value="{$config.demo_password}" class="input-text password" />
</div>

{if $settings.Image_verification.use_for_login == "Y"}
	{include file="common_templates/image_verification.tpl" id="login_`$form_name`" align="left"}
{/if}

<div class="clear">
	<div class="float-left">
		<input class="valign checkbox" type="checkbox" name="remember_me" id="remember_me_{$id}" value="Y" />
		<label for="remember_me_{$id}" class="valign lowercase">{$lang.remember_me}</label>
	</div>
	{hook name="index:login_buttons"}
	<div class="float-right">
		{include file="buttons/login.tpl" but_name="dispatch[auth.login]" but_role="action"}
	</div>
	{/hook}
</div>
<p class="center"><a href="{"auth.recover_password"|fn_url}">{$lang.forgot_password_question}</a></p>
</form>
{/capture}

{if $style == "popup"}
	{$smarty.capture.login}
{else}
	<div{if $controller != "checkout"} class="login"{/if}>
		{$smarty.capture.login}
	</div>

	{capture name="mainbox_title"}{$lang.sign_in}{/capture}
{/if}
