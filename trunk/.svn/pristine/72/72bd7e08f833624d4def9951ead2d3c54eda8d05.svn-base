{* $Id: my_account.tpl 12660 2011-06-09 13:19:38Z bimib $ *}
{** block-description:my_account **}

{*{if $auth.user_id}
<a class="sidebox_title" href='index.php?dispatch=profiles.myaccount'><strong>{$user_info.firstname} {$user_info.lastname}</strong>
</a>
{/if}*}

<div class="clearboth"></div>

{assign var="return_current_url" value=$config.current_url|escape:url}
{assign var="page" value=$config.current_url}
{assign var="page_id" value="dispatch="|explode:$page}
<ul class="arrows-list">
<li><a href="{"profiles.myaccount"|fn_url}" rel="nofollow" class="underlined {if $page_id[1] == 'profiles.myaccount'} ul_active {/if}">{$lang.my_account}</a></li>
<li><a href="{"profiles.manage_addressbook"|fn_url}" rel="nofollow" class="underlined {if $page_id[1] == 'profiles.manage_addressbook'} ul_active {/if}">{$lang.address_book}</a></li>
<li><a href="{"orders.search"|fn_url}" rel="nofollow" class="underlined {if $page_id[1] == 'orders.search'} ul_active {/if}">{$lang.orders_history}</a></li>
<li><a href="{"profiles.my_feedbacks"|fn_url}" rel="nofollow" class="underlined {if $page_id[1] == 'profiles.my_feedbacks'} ul_active {/if}">{$lang.my_feedbacks}</a></li>
{if $auth.user_id}
<li><a href="{"reward_points.userlog"|fn_url}" rel="nofollow" class="{if $page_id[1] == 'reward_points.userlog'} ul_active {/if}">{$lang.my_points}:&nbsp;<strong>{$user_info.points|default:"0"}</strong></a></li>{else}<li><a href="{"reward_points.userlog"|fn_url}" rel="nofollow" class="{if $page_id[1] == 'reward_points.userlog'} ul_active {/if}">{$lang.my_points}</a></li>
{/if}
{if $auth.user_id}
<li>
  <a href="{"profiles.store"|fn_url}" rel="nofollow" class="underlined {if $page_id[1] == 'profiles.store'} ul_active {/if}">{$lang.my_fav_store}</a>

</li>
<li>
  <a href="{"profiles.user_query"|fn_url}" rel="nofollow" class="underlined {if $page_id[1] == 'profiles.user_query'} ul_active {/if}">{$lang.my_query_messages}</a>

</li>
{/if}
<li><a href="{"wishlist.view"|fn_url}" rel="nofollow" class="{if $page_id[1] == 'wishlist.view'} ul_active {/if}">{$lang.wishlist}</a></li>
<li><a href="{"rma.returns"|fn_url}" rel="nofollow" class="{if $page_id[1] == 'rma.returns'} ul_active {/if}">{$lang.return_requests}</a></li>
{if $auth.user_id}
		<li><a href="{"profiles.update"|fn_url}" rel="nofollow" class="underlined {if $page_id[1] == 'profiles.update'} ul_active {/if}">{$lang.profile_details}</a></li>
		<li><a href="{"orders.downloads"|fn_url}" rel="nofollow" class="underlined {if $page_id[1] == 'orders.downloads'} ul_active {/if}">{$lang.downloads}</a></li>
	{else}
   		 <li><a href="{"orders.downloads"|fn_url}" rel="nofollow" class="underlined {if $page_id[1] == 'orders.downloads'} ul_active {/if}">{$lang.downloads}</a></li>
		<li><a href="{if $controller == "auth" && $mode == "login_form"}{$config.current_url|fn_url}{else}{"auth.login_form?return_url=`$return_current_url`"|fn_url}{/if}" rel="nofollow" class="underlined">{$lang.sign_in}</a>  <a href="{"profiles.add"|fn_url}" rel="nofollow" class="underlined">{$lang.register}</a></li>
	{/if}
{if $auth.user_id}
		<li><a href="{"auth.logout?redirect_url=`$return_current_url`"|fn_url}" rel="nofollow" class="underlined">{$lang.sign_out}</a></li>
		{if $config.express_checkout == 1}
	    <li><a href="{"profiles.checkout_express_settings"|fn_url}" rel="nofollow" class="underlined">{$lang.expresss_account_settings}</a></li>
	    {/if}
{/if}

<!--{if $settings.Suppliers.apply_for_vendor == "Y" && $controller != 'companies' && $mode != 'apply_for_vendor' && !$user_info.company_id}
	<li><a href="{"companies.apply_for_vendor?return_previous_url=`$return_current_url`"|fn_url}" rel="nofollow" class="underlined">{$lang.apply_for_vendor_account}</a></li>{/if}-->
</ul>

<div class="clearboth"></div>
{if $auth.user_id}
<div class="updates-wrapper" id="track_orders" style="">

<form action="{""|fn_url}" method="get" class="cm-ajax" name="track_order_quick">
<input type="hidden" name="result_ids" id="result_ids" value="track_orders" />

<p>{$lang.track_my_order}:</p>

<div class="form-field">
<label for="track_order_item{$block.block_id}" class="cm-required hidden">{$lang.track_my_order}:</label>
	<input type="tel" size="20" class="float_left input-text cm-hint" id="track_order_item{$block.block_id}" name="track_data" value="{$lang.order_id|escape:html}{if !$auth.user_id}/{$lang.email|escape:html}{/if}" onfocus="document.getElementById('search_for').value='track_orders'" style="height:24px; line-height:0px !important;" />
	{include file="buttons/go.tpl" but_name="orders.track_request"  alt=$lang.go}
    
	{if $settings.Image_verification.use_for_track_orders == "Y"}
		{include file="common_templates/image_verification.tpl" id="track_orders" align="left" sidebox=true}
	{/if}
</div>

</form>

<!--track_orders--></div>
{/if}
