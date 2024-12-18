{* $Id: my_account.tpl 12660 2011-06-09 13:19:38Z bimib $ *}
{** block-description:my_account **}

{if $auth.user_id}
<strong>{$user_info.firstname} {$user_info.lastname}</strong>
{/if}

{assign var="return_current_url" value=$config.current_url|escape:url}
<ul class="arrows-list">
{hook name="profiles:my_account_menu"}
	{if $auth.user_id}
		<li><a href="{"profiles.update"|fn_url}" rel="nofollow" class="underlined">{$lang.profile_details}</a></li>
		<li><a href="{"orders.downloads"|fn_url}" rel="nofollow" class="underlined">{$lang.downloads}</a></li>
	{else}
		<li><a href="{if $controller == "auth" && $mode == "login_form"}{$config.current_url|fn_url}{else}{"auth.login_form?return_url=`$return_current_url`"|fn_url}{/if}" rel="nofollow" class="underlined">{$lang.sign_in}</a> / <a href="{"profiles.add"|fn_url}" rel="nofollow" class="underlined">{$lang.register}</a></li>
	{/if}
	<li><a href="{"orders.search"|fn_url}" rel="nofollow" class="underlined">{$lang.orders}</a></li>
{/hook}

{if $auth.user_id}
		<li class="delim"></li>
		<li><a href="{"auth.logout?redirect_url=`$return_current_url`"|fn_url}" rel="nofollow" class="underlined">{$lang.sign_out}</a></li>
{/if}

{if $settings.Suppliers.apply_for_vendor == "Y" && $controller != 'companies' && $mode != 'apply_for_vendor' && !$user_info.company_id}
	<li><a href="{"companies.apply_for_vendor?return_previous_url=`$return_current_url`"|fn_url}" rel="nofollow" class="underlined">{$lang.apply_for_vendor_account}</a></li>{/if}
</ul>

<div class="updates-wrapper" id="track_orders">

<form action="{""|fn_url}" method="get" class="cm-ajax" name="track_order_quick">
<input type="hidden" name="result_ids" value="track_orders" />

<p>{$lang.track_my_order}:</p>

<div class="form-field">
<label for="track_order_item{$block.block_id}" class="cm-required hidden">{$lang.track_my_order}:</label>
	<input type="text" size="20" class="input-text cm-hint" id="track_order_item{$block.block_id}" name="track_data" value="{$lang.order_id|escape:html}{if !$auth.user_id}/{$lang.email|escape:html}{/if}" />
	{include file="buttons/go.tpl" but_name="orders.track_request" alt=$lang.go}
	{if $settings.Image_verification.use_for_track_orders == "Y"}
		{include file="common_templates/image_verification.tpl" id="track_orders" align="left" sidebox=true}
	{/if}
</div>

</form>

<!--track_orders--></div>
