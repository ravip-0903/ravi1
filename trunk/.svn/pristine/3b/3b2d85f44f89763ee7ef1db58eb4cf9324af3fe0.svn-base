{* $Id: recover_password.tpl 12126 2011-03-29 14:12:02Z subkey $ *}

<div class="login-wrap">
<h1 class="clear">
	<a href="{$index_script|fn_url}" class="float-left">{$settings.Company.company_name}</a>
	<span>{$lang.recover_password}</span>
</h1>
<form action="{""|fn_url}" method="post" name="recover_form" class="cm-form-highlight cm-skip-check-items">
<input type="hidden" name="token" value="{$smarty.session.form_token_value}"/>
<div class="login-content">
	<p>{$lang.text_recover_password_notice}</p>
	<p><label for="user_login">{$lang.email}:&nbsp;</label></p>
	<input type="text" name="user_email" id="user_login" size="20" value="" class="input-text cm-focus" />

	<div class="buttons-container center">
		{include file="buttons/button.tpl" but_text=$lang.reset_password but_name="dispatch[auth.recover_password]" but_role="button_main"}
	</div>
</div>
</form>
</div>
