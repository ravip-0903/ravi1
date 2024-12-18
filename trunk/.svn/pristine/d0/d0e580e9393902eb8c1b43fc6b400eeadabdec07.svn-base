{* $Id: top.tpl 12544 2011-05-27 10:34:19Z bimib $ *}
<div class="global_container">
{assign var="url" value=$config.current_url}
{assign var="page_id" value="page_id="|explode:$url}

{if $controller!='checkout' && $mode!='checkout' && !$config.mobile_perf_optimization}
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

{if ($controller != "checkout" && $mode!="checkout"  && !$config.mobile_perf_optimization)}

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

{literal}
<script>
    $( document ).ready(function() {
	if({/literal}{$config.isResponsive}{literal}){
	if($(window).width()<630){
        $( "#mobile_menu" ).click(function(event) {
        {/literal}
            {if ($controller == "products" && $mode == "search") || ($controller == "categories" && $mode == "view") || ($controller == "index" && $mode == "index" && $config.mobile_perf_optimization) || ($controller == "profiles" && $mode == "myaccount") || ($controller == "profiles" && $mode == "manage_addressbook") || ($controller == "orders" && $mode == "search") || ($controller == "profiles" && $mode == "pending_feedback") ||($controller == "profiles" && $mode == "my_feedbacks") || ($controller == "profiles" && $mode == "submitted_feedback") || ($controller == "reward_points" && $mode == "userlog") || ($controller == "profiles" && $mode == "store") || ($controller == "profiles" && $mode == "user_query") || ($controller == "wishlist" && $mode == "view") || ($controller == "rma" && $mode == "returns") || ($controller == "orders" && $mode == "downloads") || ($controller == "profiles" && $mode == "update") || ($controller == "pages" && $mode == "view") || ($controller == "companies" && $mode == "view") || ($controller == "auth" && $mode == "login_form") || ($controller == "profiles" && $mode == "updatepassword") || ($controller == "orders" && $mode == "details")}
        {literal} 
                $( ".left-pnl-prdct" ).toggle(); 


                    {/literal}
                    {else}
                    {literal}
                    $( ".left-pnl" ).toggle();

                    if($(window).width()<630)
                    {
                        $( ".left-pnl-prdct" ).toggle();
                    }
                    else
                    {
                        $( ".left-pnl-prdct" ).css("display","none");
                    }
                    {/literal}
                    {/if}

                    {literal}

                    $('.site_account_mob_option').hide();
                    $(".arrow-up").hide();
                    $('.arrow-up-mobile-menu').toggle();
                    event.stopPropagation();

                });

                $('html').click(function(event) {
                    $('.left-pnl , .left-pnl-prdct').hide();
                    $('.site_account_mob_option').hide();
                    $(".arrow-up").hide();
                    $('.arrow-up-mobile-menu').hide();
                });
                
                $('.site_account_mob_option').click(function(event){
                    event.stopPropagation();
                });

                $("#mobile_user_options").click(function(event){
                $(".site_account_mob_option").toggle();
                $(".arrow-up").toggle();
                $('.left-pnl , .left-pnl-prdct').hide();
                $('.arrow-up-mobile-menu').hide();
                event.stopPropagation();
                });

$(".left-pnl , .left-pnl-prdct").children().click(function(e){e.stopPropagation();});
$(".mob_filter_icn").click(function(event){
    $(".left-pnl").css("margin-top","87px");
    $(".left-pnl").toggle();
    $( ".left-pnl-prdct" ).hide();
    $('.arrow-up-mobile-menu').hide();
    $(".site_account_mob_option").hide();
    $(".arrow-up").hide();
    event.stopPropagation();
});
}
}
});

</script>
{/literal}

<div class="header_global">
    <div class="mobile">
        <div class="header_new_mob" style="text-align:center;">
            {if $controller != 'checkout'}
            {$lang.goto_desktop_to_top}
            {/if}
            <div id="mobile_menu" class="site_menu_mob"></div>

            <div class="head_sep_mob"></div>

            {if $controller=='checkout' && $mode=='checkout'}
                <span class="site_logo_mob"></span>
            {elseif "HTTPS"|defined}
                <a title="Shopclues online shopping" href="{$config.http_location}"> <div class="site_logo_mob"></div></a>
            {else}
                <a title="Shopclues online shopping" href="{$config.http_location}"><div class="site_logo_mob"></div></a>
            {/if}
            <div class="mobile_header_right">
                <div class="head_sep_mob"></div>
                <div id="mobile_user_options" class="{if $auth.user_id &&  $mode == 'checkout'} site_account_mob_user {else}site_account_mob{/if} inline-block"></div>
                <div class="head_sep_mob"></div>
                
                <a href="{$cofig.http_location}index.php?dispatch=checkout.cart" class="site_cart_mob inline-block">
                    {if $smarty.session.cart.products|count || $smarty.session.cart.gift_certificates|count}
                        {assign var="_cart_products" value=$smarty.session.cart.products|array_reverse:true}
                        {if isset($smarty.session.cart.gift_certificates)}
                            {assign var="_gift_certificate" value=$smarty.session.cart.gift_certificates|count}
                        {else}
                            {assign var="_gift_certificate" value=0}
                        {/if}
                        {assign var="product_count_in_cart" value=$_cart_products|count}
                        {assign var="product_count_in_cart" value=$product_count_in_cart+$_gift_certificate}
                        {if $product_count_in_cart!=0 && $mode == 'checkout'}<div class="cart_value">{$product_count_in_cart}</div>{/if}
                    {/if}


                </a>
                
            </div>
        </div>

    </div>
<div class="container">                        
    {if $controller=='checkout' && $mode=='checkout'}
        <div class="inline-block our-logo desktop">
            <span>                
            </span>
        </div>
    {elseif "HTTPS"|defined}
        <div class="inline-block our-logo desktop">
            <a title="Shopclues online shopping" href="{$config.http_location}">                
            </a>
        </div>
    {else}
        <div class="inline-block our-logo desktop">
            <a title="Shopclues online shopping" href="{$config.http_location}">
                
            </a>
        </div>
    {/if}
    <div class="inline-block header_middle_bar">               
            <div class="box_websitelinks inline-block desktop" id="login_user_data">
            {if $mode!='checkout'}                    
                    <a href="{$config.http_location}/index.php?dispatch=write_to_us.write" class="box_websitelinks_customersupport">
                        <label class="no_tablet">{$lang.suprt_mail}</label>
                    </a>
                    <a href="/sell" class="box_websitelinks_sellwithus">
                        <label class="no_tablet">
                            {$lang.sell_with_us}
                        </label>
                    </a> 
                   {* <a href="{$config.http_location}/help.html" class="box_websitelinks_help">
                        <label class="no_tablet">{$lang.suprt_help}</label>
                    </a>*}
              {/if}                  
              
              {hook name="index:user_info"}
              {assign var="escaped_current_url" value=$config.current_url|escape:url}              
                  {if !$auth.user_id || ($auth.user_id && $mode != 'checkout')}
                      <a class="box_websitelinks_signIn"
                       {if $settings.General.secure_auth == "Y"}
                            href="
                         {if $controller == "auth" && $mode == "login_form"}
                             {$config.current_url|fn_url}
                         {else}
                             {"auth.login_form?return_url=`$escaped_current_url`"|fn_url}
                         {/if}
                         " 
                       {/if}>
                        <label class="no_tablet">Sign In</label>
                    </a>
                  {/if}
                  {if $auth.user_id && $mode == 'checkout'}                      
                      {*include file="buttons/button.tpl" but_role="text" but_href="auth.logout?redirect_url=`$escaped_current_url`" but_text=$lang.sign_out*}                                         {if ($controller != "checkout" && $mode!="checkout")}                      

                          <span class="my_acnt_info_top inline-block" style="margin:0px; padding: 0px;">  
                            <a class="box_websitelinks_myAccount" href="{"profiles.myaccount"|fn_url}">
                                <label class="no_tablet">My Account</label>                                                                                      
                            </a>
                            <ul class="my_act_sub-options my_acnt_info_hover">
                                <li>
                                    <a href="{"profiles.manage_addressbook"|fn_url}" rel="nofollow" class="underlined {if $page_id[1] == 'profiles.manage_addressbook'} ul_active {/if}">
                                        {$lang.address_book}
                                    </a>
                                </li>
                                <li>
                                    <a href="{"orders.search"|fn_url}" rel="nofollow" class="underlined {if $page_id[1] == 'orders.search'} ul_active {/if}">
                                        {$lang.orders_history}
                                    </a>
                                </li>
                                <li>
                                    <a href="{"profiles.my_feedbacks"|fn_url}" rel="nofollow" class="underlined {if $page_id[1] == 'profiles.my_feedbacks'} ul_active {/if}">
                                        {$lang.feedback}
                                    </a>
                                </li>
                                <li>
                                    <a href="{"reward_points.userlog"|fn_url}" rel="nofollow" class="{if $page_id[1] == 'reward_points.userlog'} ul_active {/if}">
                                        {$lang.my_points}:&nbsp;<strong>{$user_info.points|default:"0"}</strong>
                                    </a>
                                </li>
                                <li>
                                    <a href="{"profiles.store"|fn_url}" rel="nofollow" class="underlined {if $page_id[1] == 'profiles.store'} ul_active {/if}">
                                        {$lang.my_fav_store}
                                    </a>
                                </li>
                                <li>
                                    <a href="{"profiles.user_query"|fn_url}" rel="nofollow" class="underlined {if $page_id[1] == 'profiles.user_query'} ul_active {/if}">
                                        {$lang.my_query_messages}
                                    </a>
                                </li>
                                <li>
                                    <a href="{"wishlist.view"|fn_url}" rel="nofollow" class="{if $page_id[1] == 'wishlist.view'} ul_active {/if}">
                                        {$lang.wishlist}
                                    </a>
                                </li>
                                <li>
                                    <a href="{"rma.returns"|fn_url}" rel="nofollow" class="{if $page_id[1] == 'rma.returns'} ul_active {/if}">
                                        {$lang.return_requests}
                                    </a>
                                </li>
                                <li>
                                    <a href="{"profiles.update"|fn_url}" rel="nofollow" class="underlined {if $page_id[1] == 'profiles.update'} ul_active {/if}">
                                        {$lang.profile_details}
                                    </a>
                                </li>
                                <li>
                                    <a href="{"orders.downloads"|fn_url}" rel="nofollow" class="underlined {if $page_id[1] == 'orders.downloads'} ul_active {/if}">
                                        {$lang.downloads}
                                    </a>
                                </li>
                                <li>
                                    <a href="{"auth.logout?redirect_url=`$escaped_current_url`"|fn_url}" rel="nofollow" class="underlined">
                                        {$lang.sign_out}
                                    </a>
                                </li>
                                </ul>
                        </span>

                       
                      {else}
                            <a class="box_websitelinks_myAccount" href="{"profiles.myaccount"|fn_url}">My Account</a>
                      {/if}
                  {/if}                     
                {/hook}
            </div>         
            
             {if !$config.mobile_perf_optimization}
        <div class="user_merchant">
            <div class="inline-block desktop">

                <div class="welcomegst">
                    {if $auth.user_id  && $mode == 'checkout'}
                        <label>Hi

                            <span class="bold">
                                {if $user_info.firstname}
                                    {$user_info.firstname}
                                {elseif $user_info.lastname}    
                                    {$user_info.lastname}
                                {else}
                                    {$user_info.email}
                                {/if}
                            </span>

                        </label>
                    {/if}

                </div>


            </div>
         </div>           
            {/if}
        <div class="clearboth"></div>
        
        {if $mode!='checkout'}
            <div class="box_search">
                {include file="common_templates/search.tpl"}
            </div>
        {/if}
    <div id = "up_cart_data">    
    <div class = "last">    
    <div id="cart_data">
        {if $mode == 'checkout'}
           {include file="views/checkout/components/cart_status.tpl"}
        {else}
           <div class="box_cartstatus">
               <a class="shopping_cart_link cm-combination cm-combo-on valign hand" href="index.php?dispatch=checkout.cart">
                   <span class="bold nl_new_luk_cart_no">0</span>
                   Cart                   
               </a>
           </div>
        {/if}
    </div>
    </div>
    </div>

   
    
    <div class="clearboth height_five"></div>        
</div>
    <!--{$lang.user_secure_payment_header}-->
    {if !($smarty.session.express == 'Y' && $smarty.request.edit_step == 'step_one')}
    {if $controller=='checkout' && $mode=='checkout'}
        <div class="header_chng_cart_top" style="float:right;">
            {include file="views/checkout/components/progressbar.tpl"}
        </div>
    {/if}
    {/if}
{if !$auth.user_id || ($auth.user_id && $mode != 'checkout')}
<div class="arrow-up hidden"></div>    

                    <ul class="site_account_mob_option hidden">
                    <li><a {if $settings.General.secure_auth == "Y"} href="{if $controller == "auth" && $mode == "login_form"}{$config.current_url|fn_url}{else}{"auth.login_form?return_url=`$escaped_current_url`"|fn_url}{/if}" {/if}>Sign In</a></li>
<hr>
                    <li><a  href="{"auth.login_form?return_url=`$escaped_current_url`"|fn_url}" >Register</a></li>
                    </ul>{/if}

                {if $auth.user_id && $mode == 'checkout'}
<div class="arrow-up hidden"></div>
                    <ul class="site_account_mob_option hidden">
<li><a href="{"profiles.myaccount"|fn_url}" >My Account
                        <label class="float_right">Welcome
     <span class="bold">
         {if $user_info.firstname || $user_info.lastname}
             {$user_info.firstname}
         {elseif $user_info.lastname}    
             {$user_info.lastname}
         {else}
             {$user_info.email}
         {/if}
     </span>
                        </label>
                    </a></li>
                       
{*<li><a href="{"auth.logout?redirect_url=`$escaped_current_url`"|fn_url}">Sign Out</a></li>*}
	                    
                    </ul>
                {/if}
    <div class="inline-block header_middle_bar">               
            <div class="box_websitelinks inline-block desktop" id="login_user_data">
            {if $mode!='checkout'}                    
                    <a href="{$config.http_location}/index.php?dispatch=write_to_us.write" class="box_websitelinks_customersupport">
                        <label class="no_tablet">{$lang.suprt_mail}</label>
                    </a>
                    <a href="/sell" class="box_websitelinks_sellwithus">
                        <label class="no_tablet">
                            {$lang.sell_with_us}
                        </label>
                    </a> 
                   {* <a href="{$config.http_location}/help.html" class="box_websitelinks_help">
                        <label class="no_tablet">{$lang.suprt_help}</label>
                    </a>*}
              {/if}                  
              
              {hook name="index:user_info"}
              {assign var="escaped_current_url" value=$config.current_url|escape:url}              
                  {if !$auth.user_id || ($auth.user_id && $mode != 'checkout')}
                      <a class="box_websitelinks_signIn"
                       {if $settings.General.secure_auth == "Y"}
                            href="
                         {if $controller == "auth" && $mode == "login_form"}
                             {$config.current_url|fn_url}
                         {else}
                             {"auth.login_form?return_url=`$escaped_current_url`"|fn_url}
                         {/if}
                         " 
                       {/if}>
                        <label class="no_tablet">Sign In</label>
                    </a>
                  {/if}
                  {if $auth.user_id && $mode == 'checkout'}                      
                      {*include file="buttons/button.tpl" but_role="text" but_href="auth.logout?redirect_url=`$escaped_current_url`" but_text=$lang.sign_out*}                                         {if ($controller != "checkout" && $mode!="checkout")}                      

    
    <div class="clearboth height_five"></div>        
</div>
<div style="clear:both"></div>

                    <ul class="site_account_mob_option hidden">
                    <li><a {if $settings.General.secure_auth == "Y"} href="{if $controller == "auth" && $mode == "login_form"}{$config.current_url|fn_url}{else}{"auth.login_form?return_url=`$escaped_current_url`"|fn_url}{/if}" {/if}>Sign In</a></li>
<hr>
                    <li><a  href="{"auth.login_form?return_url=`$escaped_current_url`"|fn_url}" >Register</a></li>
                    </ul>{/if}

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

<!--js for menu on hover on myaccount homepage -->
{literal}
    <script type="text/javascript">
        $(document).ready(function(){
            $('.my_acnt_info_top').mouseenter(function(){
                $(this).parent('.userAction').css('border-bottom-right-radius','0');
            });
            $('.my_acnt_info_top').mouseleave(function(){
                $(this).parent('.userAction').css('border-bottom-right-radius','5px');
            });
        });
    </script>
{/literal}
<!--end by ajay -->

