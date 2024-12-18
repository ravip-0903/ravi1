{* $Id: user_info.override.tpl 12338 2011-04-27 14:03:05Z bimib $ *}

{hook name="index:user_info"}
    {assign var="escaped_current_url" value=$config.current_url|escape:url}
    {if !$auth.user_id}
    <a href="{"auth.login_form?return_url=`$escaped_current_url`"|fn_url}" rel="nofollow" class="margin_right_twenty">
    <!--{$lang.register} -->
    {$lang.register}
    </a>
    
    <a id="sw_login" {if $settings.General.secure_auth == "Y"} rel="nofollow" href="{if $controller == "auth" && $mode == "login_form"}{$config.current_url|fn_url}{else}{"auth.login_form?return_url=`$escaped_current_url`"|fn_url}{/if}"{else}class="cm-combination"{/if}>
    <!--{$lang.sign_in} -->
    {$lang.sign_in}
    </a>
    
    {else}
    <div class="ml_box_accountlinks_userinfo">
    Welcome: {if $user_info.firstname && $user_info.lastname}{$user_info.firstname} {$user_info.lastname}{else}{$user_info.email}{/if}
    </div>
    
    <a href="{"profiles.myaccount"|fn_url}" class="margin_right_twenty">
    My Account
    </a>
    {include file="buttons/button.tpl" but_role="text" but_href="auth.logout?redirect_url=`$escaped_current_url`" but_text=$lang.sign_out}
    
    {/if}
{/hook}