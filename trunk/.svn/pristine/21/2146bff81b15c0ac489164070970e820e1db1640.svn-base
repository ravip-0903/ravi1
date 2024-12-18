{* $Id: profiles_account.tpl 12815 2011-06-29 10:55:13Z alexions $ *}

{if !$nothing_extra}
	{include file="common_templates/subheader.tpl" title=$lang.user_account_info}
{/if}

{hook name="profiles:account_info"}
{if $settings.General.use_email_as_login == "Y"}
{if $location != "checkout" || $settings.General.disable_anonymous_checkout == "Y"}
<div class="form-field">
        <label for="firstname" class="cm-required cm-trim">{$lang.Firstname}:</label>
	<input type="text" id="firstname" name="user_data[firstname]" size="32" maxlength="128" value="{$user_data.firstname}" class="input-text round_five profile_detail_field" />
</div>

<div class="form-field">
        <label for="lastname" class="cm-required cm-trim">{$lang.Lastname}:</label>
	<input type="text" id="lastname" name="user_data[lastname]" size="32" maxlength="128" value="{$user_data.lastname}" class="input-text round_five profile_detail_field" />
</div>

<div class="form-field">
        <label for="phone" class="cm-required cm-trim cm-phone">{$lang.Phone}:</label>
	<input type="tel" id="phone" name="user_data[phone]" size="10" maxlength="10" value="{$user_data.phone}" class="input-text round_five profile_detail_field" />
</div>
        
<div class="form-field">
	<label for="email" class="cm-email cm-trim ie_des_fx_nl ie_bux_span_bug">{$lang.email}:</label>
        <span class="input-text round_five profile_detail_field">{$user_data.email}</span>
</div>
{/if}
{else}
<div class="form-field">
	<label for="user_login_profile" class="cm-required cm-trim">{$lang.username}:</label>
	<input id="user_login_profile" type="text" name="user_data[user_login]" size="32" maxlength="32" value="{$user_data.user_login}" class="input-text round_five profile_detail_field" />
</div>
{/if}
<a href="index.php?dispatch=profiles.updatepassword">Change your password</a>
{/hook}
