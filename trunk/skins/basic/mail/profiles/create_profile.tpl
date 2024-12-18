{* $Id: create_profile.tpl 10293 2010-08-02 11:02:07Z klerik $ *}

{include file="letter_header.tpl"}

{$lang.dear} {if $user_data.firstname}{$user_data.firstname}{else}{$user_data.user_type|fn_get_user_type_description|lower|escape}{/if},<br><br>

{$lang.email_user_registration_pre} {*{$lang.create_profile_notification_header} {$settings.Company.company_name}*}

{if $user_data.user_type == 'P'}
	<p>{$lang.affiliate_backend}:	{$config.http_location}/{$config.partner_index}<br />
	{$lang.text_partner_create_profile}</p><br /><br />

{/if}
{if $user_data.referer != "fblogin"}
{if $has_coupon == "true"}
{$lang.coupon_code_message}<br /><br />
{/if}
{/if}
{$lang.save_this_information}
{*{include file="profiles/profiles_info.tpl"}*}
<b>{$lang.signin}</b> {$user_data.email}<br />
<b>{$lang.user_password}</b> {$user_data.password}
<br /><br />
{$lang.email_user_registration_post}
<br /><br />
{include file="letter_footer.tpl"}
