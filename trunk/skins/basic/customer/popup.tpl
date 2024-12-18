    <div id="fb-root"></div>
    <div id="popup_exp_reg" style="display: none;">
    <div class="lightbox_overlay"></div>

    <div class="lightbox_container">

    <div class="lightbox_container_close" id="popup_close"></div>

    <div class="clearboth"></div>


    <div class="lightbox_container_bgg">
    <!--<div class="lightbox_container_heading">Shop with us & get hottest deals online !</div> -->
    <!--<div class="lightbox_container_logo"></div> -->
    <div class="clue_buyer_pro_nl" style="display:none;">
    <div class="clues_logo_icon_nl"></div>
    </div>
    <form name="popup_registration_form" action="{""|fn_url}" method="post" onsubmit="return validate();">
    <div class="clues_middle_pro">
    <div class="clearboth"></div>
    <label for="email_popup_registration" class="lightbox_container_label"></label>
     <input type="hidden" name="dispatch" value="simple_registration.register" />
     <input type="hidden" name="referer" value="popup" />
    <input value="Your Email Address" name="email" id="email_popup_registration" type="email" class="lightbox_container_textbox" />
    <input name="" id="email_popup_submit" type="submit" class="lightbox_container_button" value="" style="cursor: pointer;" />
    <div class="subscribe_popup_anniversary" style="display:none;background:#fff;clear: both;height: 203px;width: 95%;margin-left: 4%;">{$lang.subscribe_popup_anniversary_content}</div>
    </div>
    </form>
    <div class="clues_right_pro_blk" >

{assign var="retu_url" value=$config.http_location|cat:"/"}

    {if $smarty.request.category_id !=''}
    {assign var="url" value="categories.view&category_id=`$smarty.request.category_id`"|fn_url}
    {assign var="social_login_url" value="categories.view&category_id=`$smarty.request.category_id`"|fn_url}
    {elseif $smarty.request.product_id !=''}
    {assign var="url" value="categories.view&category_id=`$smarty.request.category_id`"|fn_url}
    {assign var="social_login_url" value="products.view&product_id=`$smarty.request.product_id`"|fn_url}
    {else}
    {assign var="url" value=$retu_url}
    {assign var="social_login_url" value=""}
    {/if}

    {assign var="retu_url" value=$retu_url|cat:"index.php?dispatch=auth.fb_login"}

    {assign var="func" value=$url|fb_login_redirect}
    
        <div class="pop_right_sign_up_easy"></div>
        <div class="pop_right_sign_in_easy right_pro_blk" id="right_pro_blk" style="display:none;">
            <a href="tools/fb_apps/fbaccess.php?auth=fb&page={$social_login_url|urlencode}" class="sign_in_fb"></a>
            <a href="tools/fb_apps/google_login/index.php?auth=google&page={$social_login_url|urlencode}" class="sign_in_gg"></a>
        </div>    

    

    {literal}
        <div class="pop_right_registration_easy section_fb_new" id="section_fb_new" style="display:none;">
            <iframe style="margin-top:10px;" src="http://www.facebook.com/plugins/registration.php?
                 client_id={/literal}{$config.shopclues_app_id}{literal}&
                 redirect_uri={/literal}{$retu_url}{literal}&
                 fields=name,email,location,gender,birthday"
            scrolling="auto"
            frameborder="no"
            style="border:none"
            allowTransparency="true"
            width="335px"
            height="400px">
            </iframe>
        </div>
        <div id="social_login_fb_new" class="social_login_fb_new" style="display:none;">
             <!--Social Login -->
            <div class="ml_sociallogin" style="width:200px; margin:95px 0 0 65px; border:0;": id="social_login">
                <div class="clearboth"></div>
                <div class="ml_sociallogin_container margin_top_ten popup_blk_nl_login fb_login_new_pop">
                    <div class="ml_sociallogin_content">
                        <a href="tools/fb_apps/fbaccess.php?auth=fb&page={$social_login_url|urlencode}" class="scl_lnk_pop fb_bg_prd_pg">
                            <span class="socl_icon"></span>
                            <label class="socl_label">Sign in with Facebook</label>
                        </a>
                    </div>
                </div>

                <div class="ml_sociallogin_container popup_blk_nl_login fb_login_new_pop">
                    <div class="ml_sociallogin_content">
                        <a href="tools/fb_apps/google_login/index.php?auth=google&page={$social_login_url|urlencode}" class="scl_lnk_pop  goo_bg_prd_pg">
                            <span class="socl_icon"></span>
                            <label class="socl_label">Sign in with Google</label>
                        </a>
                    </div>
                </div>

                <div class="social_login_anniversary_div" style="display:none;">{$lang.social_login_anniversary_content}</div>

    <!--<div class="ml_sociallogin_container">
    <div class="ml_sociallogin_content"><img src="images/monalisa/img_loginLinkedin.gif" width="150" height="22" /></div>
    </div>-->

    </div>
    <!--End Social Login -->
        </div>
    </div>
    </div>


    </div>
    </div> 
