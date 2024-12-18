{* $Id: top.tpl 12544 2011-05-27 10:34:19Z bimib $ *}

<div class="header-helper-container">
	<div class="logo-image">    
		<a class="beta" href="{$lang.beta_logo_means}"></a>
		<a href="{""|fn_url}"><img src="{$images_dir}/{$manifest.Customer_logo.filename}" width="{$manifest.Customer_logo.width}" height="{$manifest.Customer_logo.height}" border="0" alt="{$manifest.Customer_logo.alt}" /></a>
	</div>
	
	<div class="shopclues-top-content supp_mng">
	{$lang.sc_customer_support}
	</div>
	<div class="shopclues-top-content">
	Follow us: 
    <a href="{$lang.facebook_url}" target="_blank">
    	<img src="{$images_dir}/Facebook-Logo.png" alt="" width="15"/>
   	</a> 										    
    <a href="{$lang.twitter_url}" target="_blank">
    	<img src="{$images_dir}/twitter-logo.jpg" alt="" width="15"/>
    </a> 
    <!--<a href="{$lang.google_plus_url}" target="_blank">
    	<img src="{$images_dir}/google_plus_logo.png" alt="" width="16"/>
    </a> -->
    <a href="{$lang.linkedin_url}" target="_blank">
    	<img src="{$images_dir}/LinkedIn-Logo.jpg" alt="" width="15"/>
    </a> 
	</div>
    <div class="float-right" id="sign_io">
			{hook name="index:user_info"}
			{assign var="escaped_current_url" value=$config.current_url|escape:url}
			{if !$auth.user_id}
				<a id="sw_login" {if $settings.General.secure_auth == "Y"} rel="nofollow" href="{if $controller == "auth" && $mode == "login_form"}{$config.current_url|fn_url}{else}{"auth.login_form?return_url=`$escaped_current_url`"|fn_url}{/if}"{else}class="cm-combination"{/if}>{$lang.sign_in}</a>
				{$lang.or}
				<a href="{"profiles.add"|fn_url}" rel="nofollow">{$lang.register}</a>
			{else}
				<a href="{"profiles.update"|fn_url}" class="strong">{if $user_info.firstname && $user_info.lastname}{$user_info.firstname}&nbsp;{$user_info.lastname}{else}{$user_info.email}{/if}</a>
				({include file="buttons/button.tpl" but_role="text" but_href="auth.logout?redirect_url=`$escaped_current_url`" but_text=$lang.sign_out})
			{/if}
			
			{if $settings.General.secure_auth != "Y"}
			<div id="login" class="cm-popup-box hidden">
				<div class="login-popup">
					<div class="header">{$lang.sign_in}</div>
					{include file="views/auth/login_form.tpl" style="popup" form_name="login_popup_form" id="popup"}
				</div>
			</div>
			{/if}
			{/hook}
		<!--sign_io--></div>
	<div class="clear c_h_mng">&nbsp;</div>
	
	
	{include file="top_quick_links.tpl"}
	
    
    
    
	{*include file="top_menu.tpl"*}
</div>

<div class="top-tools-container">
	<span class="float-left">&nbsp;</span>
	<span class="float-right">&nbsp;</span>
	<div class="top-tools-helper">
		
		<div class="top-search">
			{include file="common_templates/search.tpl"}
		</div>
        {include file="top_menu.tpl"}
	</div>
</div>

{if ($controller == 'products' && $mode == 'view') || ($controller == 'pages' && $mode == 'view') || ($controller == 'profiles' && $mode == 'update') || ($controller == "orders" && $mode == "search") || ($controller == "rma" && $mode == "returns") || ($controller == "reward_points" && $mode == "userlog") || ($controller == "wishlist" && $mode == "view") || ($controller == "subscriptions" && $mode == "search") || ($controller == "orders" && $mode == "downloads") || ($controller == "rate_vendor" && $mode == "manage")}

<div class="top-tools-container top-dropdown-horizontal">
	{assign var="root_cat" value='0'|fn_my_changes_get_product_data_more}
	<ul id="hmenu" class="dropdown dropdown-horizontal">
    	<li><a href="index.php" title="Home"><img alt="Home" src="images/home_icon.png"></a></li>
<li class="h-sep">&nbsp;</li>
		{include file="views/categories/components/new_menu_items.tpl" items=$root_cat separated=true submenu=false}
	</ul>
</div>
{/if}


<div class="content-tools">
	<span class="float-left">&nbsp;</span>
	<span class="float-right" style="position:relative;">
		<div class="select-wrap band-of-trust" style="position:absolute; right:0px;">
		<div class="band-of-trust-vertalign" id="band-of-trust-text"><b>BAND OF TRUST</b>; Cash on Delivery, No Anxiety Shipping, Safe and Secure Shopping</div>
		<div class="band-of-trust-vertalign"><img src="/skins/basic/customer/images/tick.png" style="width:20px;"/></div>

		</div>
	</span>
	<div class="content-tools-helper clear">
		{include file="views/checkout/components/cart_status.tpl"}
		<div class="float-right">
			{if $localizations|sizeof > 1}
				<div class="select-wrap localization">{include file="common_templates/select_object.tpl" style="graphic" suffix="localization" link_tpl=$config.current_url|fn_link_attach:"lc=" items=$localizations selected_id=$smarty.const.CART_LOCALIZATION display_icons=false key_name="localization" text=$lang.localization}</div>
			{/if}

			{if $languages|sizeof > 1}
				<div class="select-wrap">{include file="common_templates/select_object.tpl" style="graphic" suffix="language" link_tpl=$config.current_url|fn_link_attach:"sl=" items=$languages selected_id=$smarty.const.CART_LANGUAGE display_icons=true key_name="name" language_var_name="sl"}</div>
			{/if}

			{if $currencies|sizeof > 1}
				<div class="select-wrap">{include file="common_templates/select_object.tpl" style="graphic" suffix="currency" link_tpl=$config.current_url|fn_link_attach:"currency=" items=$currencies selected_id=$secondary_currency display_icons=false key_name="description"}</div>
			{/if}
		</div>
	</div>
</div>
