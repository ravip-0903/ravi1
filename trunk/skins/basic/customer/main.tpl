{* $Id: main.tpl 10618 2010-09-13 11:50:51Z alexions $ *}

{block group="top" assign="top"}

{if ($controller == "profiles" && $mode == "update") || ($controller == "orders" && $mode == "search") || ($controller == "rma" && $mode == "returns") || ($controller == "reward_points" && $mode == "userlog") || ($controller == "wishlist" && $mode == "view") || ($controller == "subscriptions" && $mode == "search") || ($controller == "orders" && $mode == "downloads") || ($controller == "rate_vendor" && $mode == "manage") || ($controller == "orders" && $mode == "details")|| ($controller == "profiles" && $mode == "myaccount") || ($controller == "auth" && $mode == "login_form") || ($controller == "profiles" && $mode == "add")|| ($controller == "rate_product" && $mode == "manage")|| ($controller == "checkout" && $mode == "complete") || ($controller == "mpromotion" && $mode == "promo") || ($controller == "write_to_us" && $mode == "write") ||($controller == "write_to_us" && $mode == "service_center") || ($controller == "profiles" && $mode == "checkout_express_settings")}

    {block group="right" assign="left"}

{else}

    {block group="left" assign="left"}

    {block group="right" assign="right"}

{/if}

{block group="bottom" assign="bottom"}
<div id="container" class="container{if !$left|trim && !$right|trim}-long{elseif !$left|trim}-left{elseif !$right|trim}-right{/if}">
{hook name="index:main_content"}
    <div id="header"> {include file="top.tpl"} </div>
{/hook}


<!--Box Main -->
<div class="ml_containermain">
<div class="ml_box_containermain {if $controller == "index"}ql_homepage{/if}">



{assign var="landing_page_ids" value=","|explode:$lang.landing_pages}
{assign var="landing_page_search_string" value=","|explode:$lang.landing_page_search_string}




<div id="content" style="float:left;">
<div class="content-helper"> {if $top|trim}
    <div class="header"> {$top} </div>
    <div class="clearboth"></div>
{/if}
<div class="clearboth"></div>



{* Written by Pankaj Jasoria | 12th March 2014 | App Download Banner*}
{if $config.isResponsive}
{if ($controller != "checkout" && $mode!="checkout")}
    <div id="mobapp_downl_banner">            
        {$lang.DownloadAppBanner}
    </div>
{/if}
    <div class="clearboth"></div>
{/if}
{* End  Written by Pankaj Jasoria | 12th March 2014 | App Download Banner*}


{if $category_data.category_id == $lang.48hrsale_category_id}
    <script type="text/javascript">
        //<![CDATA[
        //$(document).ready(function() {$ldelim}
        // create a new date and insert it
        var EndDate = new Date({$lang.48hrsale_end_datetime});
        $.countdown('#cat_timer', EndDate);
        //{$rdelim});
        //]]>
    </script>
    <div class="maincon clearfix">
        <div class="lft_con48hrs float_left">
            <div class="banner48hrs">
                {$lang.48hrsale_image}
            </div>
        </div>
        <div class="rght_contimer float_right">
            <div class="timerarea48hrs">
                <div class="odptimerbg48hrs" id="cat_timer">
                </div>
            </div>
        </div>
    </div>
{/if}

{if $category_data.category_id == $lang.24hrsale_category_id}
    <script type="text/javascript">
        //<![CDATA[
        //$(document).ready(function() {$ldelim}
        // create a new date and insert it
        var EndDate = new Date({$lang.24hrsale_end_datetime});
        $.countdown('#24_cat_timer', EndDate);
        //{$rdelim});
        //]]>
    </script>
    <div class="maincon clearfix">
        <div class="lft_con48hrs float_left">
            <div class="banner48hrs">
                {$lang.24hrsale_image}
            </div>
        </div>
        <div class="rght_contimer float_right">
            <div class="timerarea48hrs" style="background: url('http://cdn.shopclues.com/images/skin/timeleft_bg_dynamite.gif') no-repeat scroll 0 0 rgba(0, 0, 0, 0)">
                <div class="odptimerbg48hrs" id="24_cat_timer">
                </div>
            </div>
        </div>
    </div>      
{/if}
{hook name="index:columns"}
    <!--{if $controller neq "companies" && $mode neq "view"}
          {assign var="companies_css" value="merchant_full"}
          {/if}-->
    <!--added by wiantech-->
{if $controller == "companies" && $mode == "view"}
    {assign var="companies_css" value="merchant_full"}
{else}
    {assign var="companies_css" value=""}
{/if}
{if $controller == "profiles" && $mode == "my_feedbacks"}
    {assign var="feedbacks_css" value="merchant_full"}
{else}
    {assign var="feedbacks_css" value=""}
{/if}

{if $controller == "profiles" && $mode == "submitted_feedback"}
    {assign var="submitted_css" value="merchant_full"}
{else}
    {assign var="submitted_css" value=""}
{/if}

{if $controller == "auth" && $mode == "login_form"}
    {assign var="login_css" value="login_full"}
{else}
    {assign var="login_css" value=""}
{/if}

{if $controller == "hotdeals" && $mode == "list"}
    {assign var="hotdeals_css" value="hotdeals_full"}
{else}
    {assign var="hotdeals_css" value=""}
{/if}

{if $controller == "bestsellingproduct" && $mode == "list"}
    {assign var="bestseller_css" value="bestseller_full"}
{else}
    {assign var="bestseller_css" value=""}
{/if}

{if $controller == "profiles" && $mode == "manage_addressbook"}
    {assign var="adrs_css" value="products_search_full"}
{else}
    {assign var="adrs_css" value=""}
{/if}

{if $controller == "categories" && $mode == "view_all"}
    {assign var="cat_view_all" value="products_search_full"}
{else}
    {assign var="cat_view_all" value=""}
{/if}



{if $controller == "mostviewedproduct" && $mode == "list"}
    {assign var="mostviewed_css" value="mostviewed_full"}
{else}
    {assign var="mostviewed_css" value=""}
{/if}

{if $controller == "featuredproduct" && $mode == "list"}
    {assign var="featured_css" value="featured_full"}
{else}
    {assign var="featured_css" value=""}
{/if}

{if $controller == "newarrivalproduct" && $mode == "list"}
    {assign var="newarrival_css" value="featured_full"}
{else}
    {assign var="newarrival_css" value=""}
{/if}

{if $controller == "companies" && $mode == "apply_for_vendor"}
    {assign var="companies_reg_css" value="companies_reg_full"}
{else}
    {assign var="companies_reg_css" value=""}
{/if}

{if $controller == "write_to_us" && $mode == "add"}
    {assign var="write_to_us_css" value="write_to_us_full"}
{else}
    {assign var="write_to_us_css" value=""}
{/if}

{if $controller == "labs" && $mode == "add"}
    {assign var="labs_css" value="write_to_us_full"}
{else}
    {assign var="labs_css" value=""}
{/if}

{if $controller == "advertisement" && $mode == "contact"}
    {assign var="advertisement_css" value="write_to_us_full"}
{else}
    {assign var="advertisement_css" value=""}
{/if}



{if $controller == "categories" && $mode == "view"}
    {assign var="categories_css" value="categories_full"}
{else}
    {assign var="categories_css" value=""}
{/if}

{if $controller == "product_features" && $mode == "view"}
    {assign var="product_features_css" value="product_features_full"}
{else}
    {assign var="product_features_css" value=""}
{/if}

{if $controller == "products" && $mode == "onedaysale"}
    {assign var="onedaysale_page" value="onedaysale_page"}
{else}
    {assign var="onedaysale_page" value=""}
{/if}

{if $controller == "products" && $mode == "auction"}
    {assign var="auction_page" value="auction_page"}
{else}
    {assign var="auction_page" value=""}
{/if}

{if $controller == "scrapbook" && $mode == "view"}
    {assign var="scrapbook_view" value="scrapbook_page"}
{else}
    {assign var="scrapbook_view" value=""}
{/if}

{if $controller == "scrapbook" && $mode == "show"}
    {assign var="scrapbook_show" value="scrapbook_show_page"}
{else}
    {assign var="scrapbook_show" value=""}
{/if}

{if $controller == "products" && $mode == "search"}
    {assign var="products_search_css" value="products_search_full"}
{else}
    {assign var="products_search_css" value=""}
{/if}


{if $controller == "rate_product" && $mode == "manage"}
    {assign var="rate_product_css" value="rate_product_full"}
{else}
    {assign var="rate_product_css" value=""}
{/if}

{if $controller == "products" && $mode == "view"}
    {assign var="products_css" value="products_full"}
{else}
    {assign var="products_css" value=""}
{/if}

{if $controller == "gift_certificates" && $mode == "add"}
    {assign var="giftcertificate" value="login_full"}
{else}
    {assign var="giftcertificate" value=""}
{/if}

{if $controller == "gift_certificates" && $mode == "update"}
    {assign var="giftcertificateupdate" value="login_full"}
{else}
    {assign var="giftcertificateupdate" value=""}
{/if}

{if $controller == "categories" && $mode == "view" && isset($smarty.request.category_id) && $smarty.request.category_id|in_array:$config.special_sale_category_id}
    {assign var="special_cat_css" value="login_full"}
{else}
    {assign var="special_cat_css" value=""}
{/if}

{if $controller == "rma" && $mode == "create_return"}
    {assign var="rma_css" value="categories_full"}
{else}
    {assign var="rma_css" value=""}
{/if}

{if $controller == "order_lookup" && $mode == "details"}
    {assign var="order_lookup_detail" value="login_full"}
{else}
    {assign var="order_lookup_detail" value=""}
{/if}

{if $controller == "order_lookup" && $mode == "form_order"}
    {assign var="order_lookup_css" value="login_full"}
{else}
    {assign var="order_lookup_detail" value=""}
{/if}

{if $controller == "write_to_us" && $mode == "login"}
    {assign var="write_to_us_login" value="login_full"}
{else}
    {assign var="write_to_us_login" value=""}
{/if}

{if $controller == "write_to_us" && $mode == "service_center"}
    {assign var="write_to_us_center" value="login_full"}
{else}
    {assign var="write_to_us_center" value=""}
{/if}

{if $controller == "product_features" && $mode == "view_all"}

    {assign var="product_feature_listing" value="categories_full"}
{else}
    {assign var="product_feature_listing" value=""}
{/if}

{if $controller == "product_features" && $mode == "view_all"}
    {assign var="product_feature_listing" value="categories_full"}
{else}
    {assign var="product_feature_listing" value=""}
{/if}

    <!--added by ajay-->
{if $controller == "companies" && $mode == "view_all"}
    {assign var="comp_view_all" value="company_search_full"}
{else}
    {assign var="comp_view_all" value=""}
{/if}

    <!--added by wiantech-->

    <!--added by ankur -->
{if $controller == "cod_confirmation" && $mode == "cod"}
    {assign var="cod_confirm" value="login_full"}
{else}
    {assign var="cod_confirm" value=""}
{/if}

    <!--added by ankur -->

    <!--added by ankur -->
{if $controller == "profiles" && $mode == "store"}
    {assign var="fav_store" value="categories_full"}
{else}
    {assign var="fav_store" value=""}
{/if}

    <!--added by ankur -->
    <!--added by chandan -->
{if $controller == "brands" && $mode == "list"}
    {assign var="brands_css" value="mostviewed_full"}
{else}
    {assign var="brands_css" value=""}
{/if}
    <!--added by chadnan -->

    <!--added by rahul gupta for deal submission-->
{if $controller == "deal_submission" && $mode == "add"}
    {assign var="deal_submission_css" value="mostviewed_full"}
{else}
    {assign var="deal_submission_css" value=""}
{/if}

{if $controller == "products" && $mode == "report_issue"}
    {assign var="deal_submission_css" value="mostviewed_full"}
{else}
    {assign var="deal_submission_css" value=""}
{/if}

{if $controller == "mpromotion" && $mode == "promo"}
    {assign var="mpromo_css" value="mostviewed_full"}
{else}
    {assign var="mpromo_css" value=""}
{/if}

{if $controller == "nrh" && $mode == "view"}
    {assign var="nrh_css" value="mostviewed_full"}
{else}
    {assign var="nrh_css" value=""}
{/if}

{if $config.mobile_perf_optimization && ($controller == "index" && $mode == "index")}

{else}
{if $left|trim}
    <!--Side Left -->
    <div class="arrow-up-mobile-menu"></div>
    <div id="left-pnl" class="left-pnl {if $controller == "categories" && $mode == "view"} shoppingOption{/if} {if $controller == "products" && $mode == "search"} shoppingOption{/if}">
        <!--            <div class="left-column"> -->

        {if ($controller == "profiles" && $mode == "user_query_response") || ($controller == "profiles" && $mode == "user_query") || ($controller == "profiles" && $mode == "update") || ($controller == "orders" && $mode == "search") || ($controller == "rma" && $mode == "returns") || ($controller == "reward_points" && $mode == "userlog") || ($controller == "wishlist" && $mode == "view") || ($controller == "subscriptions" && $mode == "search") || ($controller == "orders" && $mode == "downloads") || ($controller == "rate_vendor" && $mode == "manage") || ($controller == "orders" && $mode == "details") || ($controller == "profiles" && $mode == "myaccount") || ($controller == "profiles" && $mode == "add") || ($controller == "profiles" && $mode == "update_addressbook") || ($controller == "profiles" && $mode == "manage_addressbook") || ($controller == "profiles" && $mode == "updatepassword") || ($controller == "rate_product" && $mode == "manage")||  ($controller == "profiles" && $mode == "pending_feedback")|| ($controller == "profiles" && $mode == "my_feedbacks") || ($controller == "profiles" && $mode == "submitted_feedback") || ($controller == "rma" && $mode == "details") || ($controller == "rma" && $mode == "create_return") || ($controller == "profiles" && $mode == "upload_excel") || ($controller == "profiles" && $mode == "store") || ($controller == "write_to_us" && $mode == "write") || ($controller == "profiles" && $mode == "checkout_express_settings")}



            <div class="sidebox-wrapper nl_rgt_side_prd" style="margin-top:0">
                <h3 class="sidebox-title"> <span>{$lang.account_details}</span> </h3>
                <div class="clearboth"></div>
                <div class="sidebox-body"> {include file="blocks/my_account.tpl"} </div>
                <div class="sidebox-bottom"> <span> &nbsp; </span> </div>
            </div>
        {else}
            {if ($controller == "order_lookup" && $mode == "form_order") || ($controller == "products" && $mode == "seller_connect") || ($controller == "hotdeals" && $mode == "list") || ($controller == "companies" && $mode == "apply_for_vendor")  || ($controller == "auth" && $mode == "login_form")||($controller == "featuredproduct" && $mode == "list")||($controller == "bestsellingproduct" && $mode == "list")||($controller == "mostviewedproduct" && $mode == "list")||($controller == "newarrivalproduct" && $mode == "list") || ($controller == "gift_certificates" && $mode == "add") || ($controller == "gift_certificates" && $mode == "update") || ($controller == "order_lookup" && $mode == "details") || ($controller == "products" && $mode == "onedaysale")|| ($controller == "products" && $mode == "auction") || ($controller =="write_to_us" && $mode =="add") || ($controller =="labs" && $mode =="add") || ($controller == "cod_confirmation" && $mode == "cod")|| ($controller == "companies" && $mode == "contact_form") || ($controller == "profiles" && $mode == "store") || ($controller == "brands" && $mode == "list") || ($controller == "deal_submission" && $mode == "add")||($controller == "products" && $mode == "report_issue")||($controller == "seller_summit" && $mode == "email") || ($controller == "nrh" && $mode == "view") || ($controller == "write_to_us" && $mode == "login") || ($controller == "review" && $mode == "review") || ($controller == "advertisement" && $mode == "contact") || ($controller =="labs" && $mode =="submit_project") ||($controller == "scrapbook" && $mode == "view")||($controller == "scrapbook" && $mode == "show")}
            {else}

                {if !$config.solr}
                    {if ($controller == "profiles" && $mode == "user_query") || ($controller == "products" && $mode == "search" && $smarty.request.q != "") && !isset($smarty.request.company_id)}

                        <div class="sidebox-wrapper  nl_rgt_side_prd">
                            <h3 class="sidebox-title"> <span>{$lang.product_search_pro_cnt}</span> </h3>
                            <div class="clearboth"></div>
                            <div class="sidebox-body"> {include file="blocks/search_category_left_block.tpl"} </div>

                            <div class="sidebox-bottom"> <span> &nbsp; </span> </div>
                        </div>
                    {/if}
                {/if}

                {if $config.solr && $controller == "products"}
                    <div class="sidebox-wrapper nl_rgt_side_prd">
                        <h3 id="mob_filters_cat_heading" class="sidebox-title"> <span>{$lang.product_search_pro_cnt}</span> </h3>
                        <div class="clearboth"></div>
                        <div class="sidebox-body"> {include file="blocks/solr_filters.tpl"} </div>
                        <div class="sidebox-bottom"> <span> &nbsp; </span> </div>
                    </div>
                {/if}
                {if $controller == "categories" && $mode == "view" && isset($smarty.request.category_id) && $smarty.request.category_id|in_array:$config.special_sale_category_id}
                {else}
                    {$left}
                {/if}
            {/if}
        {/if}

    </div>
    <!--End Side Left -->
{/if}
{/if}

{if ($controller == "products" && $mode == "search") || ($controller == "index" && $mode == "index" && $config.mobile_perf_optimization) || ($controller == "products" && $mode == "view") || ($controller == "profiles" && $mode == "myaccount") || ($controller == "profiles" && $mode == "manage_addressbook") || ($controller == "orders" && $mode == "search") || ($controller == "profiles" && $mode == "pending_feedback")|| ($controller == "profiles" && $mode == "submitted_feedback") || ($controller == "profiles" && $mode == "my_feedbacks") || ($controller == "reward_points" && $mode == "userlog") || ($controller == "profiles" && $mode == "store") || ($controller == "profiles" && $mode == "user_query") || ($controller == "wishlist" && $mode == "view") || ($controller == "rma" && $mode == "returns") || ($controller == "orders" && $mode == "downloads") || ($controller == "profiles" && $mode == "update") || ($controller == "pages" && $mode == "view") || ($controller == "companies" && $mode == "view") || ($controller == "auth" && $mode == "login_form") || ($controller == "profiles" && $mode == "updatepassword") || ($controller == "orders" && $mode == "details") || ($controller == "profiles" && $mode == "checkout_express_settings") || ($controller == "categories" && $mode == "view")}
    <div class="arrow-up-mobile-menu"></div>
    <div id="left-pnl-prdct" class="left-pnl-prdct mobile">

        {assign var="items" value=""|fn_get_categories_resp_mobile}
        <ul class="nav_mainmenu">
            {foreach from=$items item="category" name=$foreach_name}
                <li class="cate_nl_hover_cond">
                    <a href="{"categories.view?category_id=`$category.category_id`"|fn_url}">
                        <div class="nav_mainmenu_label">{$category.category}</div>
                    </a>
                </li>
            {/foreach}
        </ul>
    </div>
{/if}

{if !$config.isResponsive && $config.enable_all_category && ( $mode != "checkout")}
    <div class="category_menu">
        {assign var="items" value=""|fn_get_categories_resp_mobile}
        <ul id="vmenu_8" class="nav_mainmenu" style="z-index:1000;">
        {include file="views/categories/components/new_top_menu_items.tpl"}
        </ul>
    </div>
{/if}


{if $exception_status == "404"}
    {$lang.page_not_found_error}
{else}
    <div class="central-column {$companies_css} {$products_css} {$newarrival_css} {$order_lookup_detail} {$hotdeals_css} {$mostviewed_css} {$bestseller_css} {$featured_css} {$categories_css} {$products_search_css} {$companies_reg_css} {$rate_product_css} {$product_features_css} {$feedbacks_css} {$submitted_css} {$login_css} {$adrs_css} {$giftcertificate} {$giftcertificateupdate} {$onedaysale_page} {$auction_page} {$special_cat_css} {$write_to_us_css} {$product_feature_listing}{$advertisement_css} {$labs_css} {$cat_view_all} {$cod_confirm} {$fav_store} {$brands_css} {$deal_submission_css} {$order_lookup_css} {$write_to_us_login} {$nrh_css} {$write_to_us_center} {$mpromo_css}{$rma_css} {$scrapbook_view}{$scrapbook_show}">
        <div class="central-content"> {include file="common_templates/breadcrumbs.tpl"}
            {include file="common_templates/notification.tpl"}

            {if $controller == "categories" && $mode == "view"}
                <br />
                {hook name="categories:sub_cat"}
                {/hook}
            {/if}

            {block group="central"}
        </div>
        {if $smarty.request.dispatch=='index.index'}
            {if $config.showMobOffers}
                <div class="mob_top_offers_new mobile">{$lang.mob_top_offers_new}</div>
            {/if}
            <div class="mobile-categories mobile">
                {assign var="items" value=""|fn_get_categories_resp_mobile}
                <ul class="mob_mnu_cat_blk">
                    <li class="cat-headng mob_cat_out_fl_blk"><span>{$lang.mob_shopclues_categories}</span></li>
                    {include file="views/categories/components/mobile_responsive_menu.tpl" items=$items separated=true submenu=false}
                </ul>
            </div>
        {/if}
        {if ($controller == "profiles" && $mode == "myaccount") || ($controller == "profiles" && $mode == "manage_addressbook") || ($controller == "orders" && $mode == "search") || ($controller == "profiles" && $mode == "pending_feedback") || ($controller == "profiles" && $mode == "submitted_feedback") || ($controller == "profiles" && $mode == "my_feedbacks") || ($controller == "reward_points" && $mode == "userlog") || ($controller == "profiles" && $mode == "store") || ($controller == "profiles" && $mode == "user_query") || ($controller == "wishlist" && $mode == "view") || ($controller == "rma" && $mode == "returns") || ($controller == "orders" && $mode == "downloads") || ($controller == "profiles" && $mode == "update") || ($controller == "profiles" && $mode == "updatepassword") || ($controller == "profiles" && $mode == "checkout_express_settings") || ($controller == "orders" && $mode == "details") ||($controller == "order_lookup" && $mode == "details")}
            {assign var="return_current_url" value=$config.current_url|escape:url}
            {assign var="page" value=$config.current_url}
            {assign var="page_id" value="dispatch="|explode:$page}
            <div class="mobile myaccnt-menu-below"><ul class="arrows-list">
                    <li><a href="{"profiles.myaccount"|fn_url}" rel="nofollow" class="underlined {if $page_id[1] == 'profiles.myaccount'} ul_active {/if}">{$lang.my_account}</a></li>
                    <li><a href="{"profiles.manage_addressbook"|fn_url}" rel="nofollow" class="underlined {if $page_id[1] == 'profiles.manage_addressbook'} ul_active {/if}">{$lang.address_book}</a></li>
                    <li><a href="{"orders.search"|fn_url}" rel="nofollow" class="underlined {if $page_id[1] == 'orders.search'} ul_active {/if}">{$lang.orders_history}</a></li>
                    <li><a href="{"profiles.my_feedbacks"|fn_url}" rel="nofollow" class="underlined {if $page_id[1] == 'profiles.my_feedbacks'} ul_active {/if}">{$lang.feedback}</a></li>
                    {if $auth.user_id}
                        <li><a href="{"reward_points.userlog"|fn_url}" rel="nofollow" class="{if $page_id[1] == 'reward_points.userlog'} ul_active {/if}">{$lang.my_points}:&nbsp;<strong>{$user_info.points|default:"0"}</strong></a></li>{else}<li><a href="{"reward_points.userlog"|fn_url}" rel="nofollow" class="{if $page_id[1] == 'reward_points.userlog'} ul_active {/if}">{$lang.my_points}</a></li>
                    {/if}
                    {if $auth.user_id}
                        <li>
                            <a href="{"profiles.store"|fn_url}" rel="nofollow" class="underlined {if $page_id[1] == 'profiles.store'} ul_active {/if}">{$lang.my_fav_store}</a>
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
                    {/if}

                    <!--{if $settings.Suppliers.apply_for_vendor == "Y" && $controller != 'companies' && $mode != 'apply_for_vendor' && !$user_info.company_id}
	<li><a href="{"companies.apply_for_vendor?return_previous_url=`$return_current_url`"|fn_url}" rel="nofollow" class="underlined">{$lang.apply_for_vendor_account}</a></li>{/if}-->
                </ul></div>
        {/if}

    </div>
{/if}
{if ($controller == "order_lookup" && $mode == "form_order") || ($controller == "profiles" && $mode == "user_query_response") || ($controller == "profiles" && $mode == "user_query") || ($controller == "products" && $mode == "seller_connect") || ($controller == "companies" && $mode == "view") || ($controller == "hotdeals" && $mode == "list")|| ($controller == "companies" && $mode == "apply_for_vendor") || ($controller == "categories" && $mode == "view")|| ($controller == "products" && $mode == "search")|| ($controller == "profiles" && $mode == "myaccount") || ($controller == "profiles" && $mode == "manage_addressbook") || ($controller == "rate_product" && $mode == "manage") || ($controller == "product_features" && $mode == "view") || ($controller == "profiles" && $mode == "pending_feedback") ||($controller == "profiles" && $mode == "my_feedbacks") || ($controller == "profiles" && $mode == "submitted_feedback") || ($controller == "rma" && $mode == "details") || ($controller == "rma" && $mode == "create_return") || ($controller == "auth" && $mode == "login_form") || ($controller == "featuredproduct" && $mode == "list")||($controller == "bestsellingproduct" && $mode == "list")||($controller == "mostviewedproduct" && $mode == "list")||($controller == "newarrivalproduct" && $mode == "list") || ($controller == "gift_certificates" && $mode == "add") || ($controller == "gift_certificates" && $mode == "update")|| ($controller == "order_lookup" && $mode == "details") || ($controller == "products" && $mode == "onedaysale") || ($controller == "products" && $mode == "auction") || ($controller == "gift_certificates" && $mode == "update")|| ($controller == "order_lookup" && $mode == "details") || ($controller =="write_to_us" && $mode =="add") || ($controller =="labs" && $mode =="add")|| ($controller =="categories" && $mode =="view_all") || ($controller =="companies" && $mode =="view_all") || ($controller == "cod_confirmation" && $mode == "cod") || ($controller == "profiles" && $mode == "store") || ($controller == "brands" && $mode == "list") || ($controller == "deal_submission" && $mode == "add")||($controller == "products" && $mode == "report_issue") || ($controller == "nrh" && $mode == "view") || ($controller == "write_to_us" && $mode == "login") || ($controller == "review" && $mode == "review") || ($controller == "profiles" && $mode == "checkout_express_settings") || ($controller == "advertisement" && $mode == "contact") || ($controller =="labs" && $mode =="submit_project")||($controller == "scrapbook" && $mode == "view")||($controller == "scrapbook" && $mode == "show")}
{else}

    {if $right|trim}

        {if $controller == "products" && $mode == "view"}

            {assign var="products_css" value="products_right"}

        {else}

            {assign var="products_css" value=""}

        {/if}
        <div class="right-column {$products_css}"> {$right} </div>
    {/if}

{/if}

{/hook}

    {if $bottom|trim}

        <div class="bottom clear-both">
            {$bottom}
        </div>
    {/if}

</div>

{if $config.isResponsive}
    {if ($controller != "checkout" && $mode!="checkout")}
        <div class="order_history_track no_desktop" ><a href="{$config.http_location}/index.php?dispatch=order_lookup.form_order">{$lang.track_your_order}</a></div>
    <br />
    {/if}
    <div class="mob_top_three_links" >{$lang.top_three_links}</div>
    <div class="mob_app_popup">{$lang.mob_app_popup}</div>
{/if}

{if !$config.isResponsive && ($controller != "checkout" && $mode!="checkout") && $config.enable_scrapbook}
 <div>
<a href="{"scrapbook.view"|fn_url}"  style="position: fixed;left:0px; top:220px; z-index: 1000000000;">
 <img src="{$config.scrapbook_icon_url}" alt="scrapbook" /></a>
</div>
{*http://cdn.shopclues.com/images/banners/img_orderlookup_vrtcle_new.png*}
{/if}


    
<div class="foot-bluebot-mobile">
    {if $config.isResponsive}
 
    <div class="android_footer_download" id="android"> 
    <div class="inner_blk">
        <a class="android_app_lnk" href="https://play.google.com/store/apps/details?id=com.shopclues&referrer=utm_source%3Dm_shopclues%26utm_medium%3Dmobile%26utm_campaign%3Dandroid_app" target="_blank">{$lang.download_apps} - </a>
    </div>
</div>
	<a class="site-url" href="http://{$config.desktopSiteURL}">{$lang.go_to_our_desktop_site}</a>
        
{/if}
    <a href="privacy-policy.html">Privacy Policy</a> | <a href="user-agreement.html">User Agreement</a>     <span>Copyright © 2011-2013 Clues Network PVT. LTD.</span>
</div>
</div>
<!--<a class="ml_footer_linktop">TOP</a> -->
<div class="clearboth"></div>
</div>
</div>
<!--End Box Main -->
<div class="clearboth"></div>
</div>

<!--Footer -->
<!--<div class="ml_footer">
  <div class="ml_box_footer">

    <div class="ml_box_footer_weboptions">

    {$lang.footer_testimonial}

    {include file="simple_registration.tpl"}

    </div>
    <div class="clearboth"></div>
  </div>
</div> -->
<!--End Footer -->
<!--<div class="clearboth"></div> -->
<!--Copyright -->


<div class="clearboth"></div>
<!--Footer Area Start Here-->
<div class="no_mobile {if $controller=='checkout' && $mode=='checkout'}footer_wid_chckout {else} footer_execpt_chckout{/if}">
    <div class="sc_footer_con">
        <!--Changes by ankur for hiding the footer on checkout pages-->

        {if $controller != "index"}
            {$lang.home_page_merchant_info_small}
        {else}
            {$lang.home_page_merchant_info}
        {/if}

        {if $mode!='checkout'}

        <div class="sc_footer clearfix">
            {$lang.footer_links}


            <div class="foot_seccol float_left">
                <div class="emailsubcribe">
                    {$lang.footer_emailsubscribetext}

                    <div style="height:40px;">
                        <form name="simple_registration_form" action="{""|fn_url}" method="post">
                            <input type="hidden" name="dispatch" value="simple_registration.register" />
                            <div class="foot_textfield float_left"><input id="email_simple_registration"  name="email" value="Email Address" type="email" class="txtfield" /></div>
                            <div class="foot_but float_left"><input id="email_simple_reg_submit" name="" type="image" src="images/skin/subscribe_but.jpg" onclick="return val_simple_reg();" /></div>
                            <input type="submit" id="footer_regis_submit" style="visibility:hidden; position:absolute;"/>

                        </form>
                        {literal}
                            <script>

                                function val_simple_reg(){
                                    var val = document.getElementById('email_simple_registration').value;
                                    val = jQuery.trim(val);
                                    document.getElementById('email_simple_registration').value = val;
                                    if(val== '' || val=='Email Address'){
                                        document.getElementById('email_simple_registration').style.color = "#000";
                                        document.getElementById('email_simple_registration').style.background = "#ff9a9a";
                                        document.getElementById('email_simple_registration').value = 'Email Address';
                                        //document.getElementById('simple_reg_error').style.display = 'block';
                                        return false;
                                    }else{
                                        var filter = /^\w[a-zA-Z0-9-_.]+@[a-zA-Z_]+.[a-zA-Z]+.[a-zA-Z]{2,3}$/;
                                        if(String(document.getElementById('email_simple_registration').value).search (filter) == -1)
                                        {
                                            document.getElementById('email_simple_registration').style.color = "#000";
                                            document.getElementById('email_simple_registration').style.background = "#ff9a9a";
                                            document.getElementById('email_simple_registration').value = 'Email Address';
                                            //document.getElementById('simple_reg_error').style.display = 'block';
                                            return false;
                                        }
                                        else
                                        {
                                            document.getElementById('email_simple_registration').style.color = "#333";
                                            document.getElementById('email_simple_registration').style.background = "#FFF";
                                            //document.getElementById('simple_reg_error').style.display = 'none';
                                            document.getElementById('footer_regis_submit').click();
                                        }
                                    }
                                }

                                jQuery("#email_simple_registration").bind('blur',function(){
                                    var val = document.getElementById('email_simple_registration').value;
                                    val = jQuery.trim(val);
                                    document.getElementById('email_simple_registration').value = val;
                                    if(val== '' || val=='Email Address'){
                                        document.getElementById('email_simple_registration').style.color = "#000";
                                        document.getElementById('email_simple_registration').value = 'Email Address';
                                    }
                                });
                                jQuery("#email_simple_registration").bind('focus',function(){
                                    var val = document.getElementById('email_simple_registration').value;

                                    if(val=='Email Address'){
                                        document.getElementById('email_simple_registration').style.color = "#000";
                                        document.getElementById('email_simple_registration').value = '';
                                    }

                                });
                            </script>
                        {/literal}

                    </div>

                    {$lang.footer_social_links}
                </div>
            </div>
        </div>
    </div>

    <div class="clearboth"></div>
    {$lang.footer_copyright}

    {foreach from=$config.footer_pixels_dynamic_lang_var key="fpixel_key" item="fpixel_val"}
    {if $fpixel_val}

        {$lang.$fpixel_key}

    {/if}
    {/foreach}
    {if $smarty.request.dispatch!='checkout.complete' && $config.pixels_across_site.adroll_pixel_script}

        {$lang.adroll_pixel_across}

    {/if}

</div>
{/if}


</div>


{if $smarty.request.dispatch=='checkout.complete' && $config.pixels_with_dynamic_lang_var.fb_event_across_page}

    {$lang.fb_pixel_checkout}

{/if}

{if $smarty.request.dispatch=='auth.login_form' && $config.pixels_with_dynamic_lang_var.fb_event_across_page}

    {$lang.fb_pixel_registration_complete}

{/if}



<!--Footer Area End Here-->


{literal}
<script type="text/javascript">
    {/literal}
    {if ($controller == "products" && $mode == "view")}
    {literal}
    $('.product_rating').ratingbar();
    {/literal}
    {/if}
    {literal}
    $(document).ready(function(){
        $(".ml_footer_linktop").click(function(){
            $("html, body").animate({ scrollTop: 0 }, "slow");
            return false;
        });
    });
</script>
<!-- Piwik -->
<script type="text/javascript">
var piwik_switch="{/literal}{$config.piwik_switch}{literal}";
var dispatch = "{/literal}{$smarty.request.dispatch}{literal}";
var piwik_url="{/literal}{$config.piwik_url}{literal}";
var user_id="{/literal}{$smarty.session.auth.user_id}{literal}";
    if(piwik_switch){
	   var _paq = _paq || [];
	  _paq.push(["setCookieDomain", "*.shopclues.com"]);
	  if(dispatch !="products.search"){
	   	  _paq.push(["trackPageView"]);
	   }
	  _paq.push(["enableLinkTracking"]);
	   if(user_id ==0){
	   var user_id="logged out";
	   }
	   _paq.push(['setCustomVariable',1,"user id",user_id,scope="page"]); 
	  (function() {
		var u=(("https:" == document.location.protocol) ? "https" : "http") + "://" + piwik_url;
		_paq.push(["setTrackerUrl", u+"piwik.php"]);
		_paq.push(["setSiteId", "1"]);
		var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0]; g.type="text/javascript";
		g.defer=true; g.async=true; g.src=u+"piwik.js"; s.parentNode.insertBefore(g,s);
	  })();
 }  
</script>
<!-- End Piwik Code -->
{/literal}

{if !$config.isResponsive}
{if ($controller == "categories" && $mode == "view") || ($controller == "products" && $mode == "search")}
{literal}
          <script type="text/javascript">
	       	$(document).ready(function(){
				$(".shoppingOption").pj_autoScroll({
					headerHeight:235,
					footerHeight:603,
                                        minPageHeight:4000
				});	
			});
          </script>          
          {/literal}
{/if}
{/if}
{literal}
<script type="text/javascript">
    var hrsForDsktpSiteView = {/literal}{$config.dsktpSiteViewHours}{literal};
    $(".foot-bluebot-mobile .site-url").click(function(){
        //check cookie if not set then set
        var dsktp_site_view = ReadCookie("dsktp_site_view");
        if(dsktp_site_view == ""){
            SetCookie("dsktp_site_view", true, hrsForDsktpSiteView, ".shopclues.com");
        }
    });

    // click event for mobile filters category starts
    if($(window).width() <630){
        $("#mob_filters_cat_heading span").css("float", "left");
        $("#mob_filters_cat_heading").append('<span class="mob_plus_minus box_collapse_mob"></span>');
        $(".nav_mainmenu.new_menu_link_nl").hide();

        $("#mob_filters_cat_heading").click(function(){
            $(".nav_mainmenu.new_menu_link_nl").slideToggle();
            if($(this).find(".mob_plus_minus").hasClass('box_expand_mob')){
                $(this).find(".mob_plus_minus").removeClass('box_expand_mob');
                $(this).find(".mob_plus_minus").addClass('box_collapse_mob');
            }else{
                $(this).find(".mob_plus_minus").removeClass('box_collapse_mob');
                $(this).find(".mob_plus_minus").addClass('box_expand_mob');
            }
        });
    }
    // click event for mobile filters category ends

</script>
{/literal}
