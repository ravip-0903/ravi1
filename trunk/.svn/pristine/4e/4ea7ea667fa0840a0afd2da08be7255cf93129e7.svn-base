{* $Id: login_form.tpl 12337 2011-04-27 13:38:58Z alexions $ *}

<div class="login-wrap">
<h1 class="clear">
	{assign var="name" value=$settings.Company.company_name|substr:0:40}
	{if $settings.Company.company_name|fn_strlen > 40}
		{assign var="name" value="`$name`..."}
	{/if}
	<a href="{$index_script|fn_url}" class="float-left">{$name}</a>
	<span>{$lang.administration_panel}</span>
</h1>
<form action="{$config.current_location}/{$index_script}" method="post" name="main_login_form" class="cm-form-highlight cm-skip-check-items">
<input type="hidden" name="token" value="{$smarty.session.form_token_value}"/>
<input type="hidden" name="return_url" value="{$smarty.request.return_url|default:$index_script}" />



<div class="login-content">
	<p><label for="username" class="cm-required">{if $settings.General.use_email_as_login == "Y"}{$lang.email}{else}{$lang.username}{/if}:</label></p>
	<input id="username" type="text" name="user_login" size="20" value="{$config.demo_username}" class="input-text cm-focus" tabindex="1" />
	<p><label for="password">{$lang.password}:</label></p>
	<input type="password" id="password" name="password" size="20" value="{$config.demo_password}" class="input-text" tabindex="2" />
	<div class="buttons-container nowrap">
		<div class="float-left">
			{include file="buttons/sign_in.tpl" but_name="dispatch[auth.login]" but_role="button_main" tabindex="3"}
		</div>

		<div class="float-right">
			<a href="{"auth.recover_password"|fn_url}" class="underlined">{$lang.forgot_password_question}</a>
		</div>
	</div>
</div>
</form>
</div>
