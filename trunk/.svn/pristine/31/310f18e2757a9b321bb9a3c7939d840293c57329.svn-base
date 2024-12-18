{* $Id: top.tpl 12544 2011-05-27 10:34:19Z bimib $ *}
<div class="global_container">
    {assign var="url" value=$config.current_url}
    {assign var="page_id" value="page_id="|explode:$url}

    {if $controller!='checkout' && $mode!='checkout'}
        {if $lang.top_news != "_top_news" && $lang.top_news != ""}
            <div id="sc_news" style="margin: auto; width: 978px; padding-left:20px; height: 30px; background:#edf8fe; color:#777777;">
                <div style="float: left; padding-left:1px;">
                    <marquee scrollamount="4" onmouseout="this.start();" onmouseover="this.stop();" style="font-size: 12px; font-weight: bold; float:left; margin: 0px; text-align: left; padding-top: 6px; width:900px;" direction="left">
                        {$lang.top_news}
                    </marquee></div><div style="float: right;padding-top:5px; text-align:center;">
                    <div class="list_lightboxcartitem " style="float:right; padding:0; width:65px; border-bottom:0px;"> <a class="cm-ajax list_lightboxcartitem_close" onclick="$('#sc_news').hide('slow'); abc();" rev="cart_status" style="padding:2px 4px; height:auto; margin:0px 5px 0 0; width:auto; border-radius:5px; -moz-border-radius:5px;">
                            <span style="float: left; margin-right: 4px; margin-top: 1px;">Close </span>
                            <img style="border-radius: 12px 12px 12px 12px; margin-top: 1px;" src="images/skin/close_alert_header.png" />
                        </a></div>
                </div></div>
        {/if}
    {/if}
    {*$lang.diwali_lights_top*}

    {if ($controller != "checkout" && $mode!="checkout")}

        {if $lang.top_news != "_top_news" && $lang.top_news != ""}
        {literal}
            <script type="text/javascript">


                var sa='{/literal}{$lang.top_news|md5}{literal}';
                var cookie_status = ReadCookie('skip_express_news');

                if(cookie_status == sa || sa == ''){
                    $("#sc_news").hide();
                }
                function abc(){
                    SetCookie('skip_express_news',sa,'365','');
                }

            </script>
        {/literal}
        {/if}
    {/if}

    <div class="header_global">
        <div class="mobile">
       <div class="header_new_mob">
	<div class="site_menu_mob"></div>
    <div class="head_sep_mob"></div>
    
    {if $controller=='checkout' && $mode=='checkout'}
            <span> <img src="images/skin/shopclues_logo.gif" class="logo" alt="" border="0"> </span>
        {elseif "HTTPS"|defined}
        <a title="Shopclues online shopping" href="{$config.http_location}"> <div class="site_logo_mob"></div></a>
        {else}
         <a title="Shopclues online shopping" href="{$config.http_location}"><div class="site_logo_mob"></div></a>
        {/if}
    <div class="site_cart_mob">
    	<div class="cart_value"></div>
    </div>
    <div class="head_sep_mob"></div>    
    <div class="site_account_mob"></div>
</div>
        </div>
        {if $controller=='checkout' && $mode=='checkout'}
            <span> <img src="images/skin/shopclues_logo.gif" class="logo" alt="" border="0"> </span>
        {elseif "HTTPS"|defined}
            <div class="inline-block our-logo desktop"><a title="Shopclues online shopping" href="{$config.http_location}"> <span> <img class="logo" src="images/skin/shopclues_logo.gif" alt="" border="0"> </span></a></div>
        {else}
            <div class="inline-block our-logo desktop"><a title="Shopclues online shopping" href="{$config.http_location}"> <img class="logo" src="http://cdn.shopclues.com/images/skin/shopclues_logo.gif" alt="" border="0"> </a></div>
        {/if}
        <div class="inline-block header_middle_bar">
            <div class="user_merchant desktop">
                <div class="sb_wrapper inline-block">

                    <div class="welcomegst">
                        {if $auth.user_id}
                            <label>Welcome
     <span class="bold">
         {if $user_info.firstname || $user_info.lastname}
             {$user_info.firstname}
             {$user_info.lastname}
         {else}
             {$user_info.email}
         {/if}
     </span>
                            </label>
                        {/if}

                    </div>


                </div>

                {if $mode!='checkout'}
                <div class="box_websitelinks inline-block">
                    <a href="/sell" class="box_websitelinks_sellwithus"><label class="no_tablet">{$lang.sell_with_us}</label></a>

                    <a href="{$config.http_location}/index.php?dispatch=write_to_us.write" class="box_websitelinks_customersupport">
                        <label class="no_tablet">{$lang.suprt_mail}</label>
                    </a>

                    <a href="{$config.http_location}/help.html" class="box_websitelinks_help">
                        <label class="no_tablet">{$lang.suprt_help}</label>
                    </a>
                </div>





            </div>

            <div class="box_search">
                {include file="common_templates/search.tpl"}
            </div>
            {/if}
        </div>
        {hook name="index:user_info"}
        {assign var="escaped_current_url" value=$config.current_url|escape:url}
        <div class="inline-block header_right desktop">
            <div class="box_header_userinfo float-right">


                <div id="login_user_data" class="inline-block">
                    <div>{if !$auth.user_id}
                        <a {if $settings.General.secure_auth == "Y"} href="{if $controller == "auth" && $mode == "login_form"}{$config.current_url|fn_url}{else}{"auth.login_form?return_url=`$escaped_current_url`"|fn_url}{/if}" {/if}>Sign In</a>
                        <span class="box_header_userinfo_span">/</span>
                        <a  href="{"auth.login_form?return_url=`$escaped_current_url`"|fn_url}" >Register</a>
                    </div>{/if}

                    {if $auth.user_id}
                        <div>
                            {include file="buttons/button.tpl" but_role="text" but_href="auth.logout?redirect_url=`$escaped_current_url`" but_text=$lang.sign_out}
                            <span class="box_header_userinfo_span">/</span>
                            <a href="{"profiles.myaccount"|fn_url}" >My Account</a>
                        </div>
                    {/if}
                </div>
                <div id="cart_data" class="inline-block">
                    {include file="views/checkout/components/cart_status.tpl"}
                </div>
            </div>
            {if $mode!='checkout'}



            <div class="support1">
                <div class="box_customersupport inline-block">
                </div>
                <div class="inline-block">
                    <div class="support_number">0124-441 4888</div>
                    <div class="support_time">
                        9am - 11pm, Mon-Sat<br>
                        10am - 6pm, Sun
                    </div>



                    {$lang.live_chat_icon}
                    {/if}

                </div>
            </div>
            {/hook}
            <!--{$lang.user_secure_payment_header}-->
            {if $controller=='checkout' && $mode=='checkout'}
                <div class="header_chng_cart_top" style="float:right; margin-top:21px; width:710px;">
                    {include file="views/checkout/components/progressbar.tpl"}
                </div>
            {/if}
        </div>
        <div style="clear:both"></div>

    </div>
        
    <div class="clearboth"></div>



    <div class="content-tools" style="display:none;">

        <div class="content-tools-helper clear">

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