{* $Id: complete.tpl 12815 2011-06-29 10:55:13Z alexions $ *}
<div class="ty_header">

<div class="ty_header">

<div class="float_left">
<div class="ty_header_heading">{$lang.thank_you_message} <span>{$order_info.firstname} {$order_info.lastname}!</span></div>
{if $order_info.payment_id==$config.suvidha_payment_id}{$lang.cbd_lang_var}{/if}
{if $saved>0}<div class="ty_header_subheading">{$lang.saved_ruppes_message} {$saved}</div>{/if}
</div>
{assign var="product_name" value=""}
{assign var="product_id" value=""}
{foreach from=$order_info.items item="product"}
{if $product_name == ""}
{assign var="product_name" value=$product.product}
{assign var="product_id" value=$product.product_id}
{assign var="pro_images" value=$product.product_id|fn_get_image_pairs:'product':'M'}
{/if}
{/foreach}
{assign var="product_url" value="products.view?product_id=`$product.product_id`"|fn_url}
{assign var="pro_url" value=$config.http_location|cat:$product_url}
{assign var="ret_url" value=$config.https_location|cat:'/'}
{assign var="ret_url" value=$ret_url|cat:$config.current_url|escape:'url'}
{assign var="pro_image" value=$config.ext_images_host|cat:$pro_images.detailed.http_image_path}

<div class="ty_pagelinks">
<a href="{"`$config.current_location`/fb_apps/orders_share.php?pro_url=`$pro_url`&pro_name=`$product_name`&pro_image=`$pro_image`&redirect_url=`$ret_url`"|fn_url }" target="_new"><img src="http://cdn.shopclues.com/images/skin/img_facebook.gif" /></a>
<a href="{if $continue_url}{$continue_url|fn_url} {else}{$index_script|fn_url}{/if}" class="ty_pagelinks_continueshopping">Continue Shopping</a>
<a href="?dispatch=orders.search" class="ty_pagelinks_vieworder">View Orders</a>
{if $order_info}
{if $order_info.child_ids}
<a href="{"`$index_script`?dispatch[orders.search]=Search&period=A&order_id=`$order_info.child_ids`"}" class="ty_pagelinks_myorderdetails">My Order Detail</a>
{else}
<a href="{"`$index_script`?dispatch=orders.details&order_id=`$order_info.order_id`"}" class="ty_pagelinks_myorderdetails">My Order Detail</a>
{/if}
{/if}
</div>

</div>




<div class="ty_orderdetails">


<ul class="ordersummary">
<li class="heading">{$lang.order_summary_message}</li>
<li><label>{$lang.total_items_heading} :</label> {$item_count}{if $order_info.is_parent_order=='Y'} ({$lang.split_into_different_orders}){/if}</li>
<li><label>{$lang.order_date_heading} :</label> {$order_date}</li>
<li><label>{$lang.payment_method_heading}:</label>{if $order_info.payment_id!=0}{$order_info.payment_method.payment}{else}GC/CB {/if}</li>
<li><label>{$lang.total_value_heading} :</label> {$order_info.total|string_format:"%d"}</li>
<li class="last"><label>{$lang.clues_bucks_heading} :</label> {$lang.used_cb} ({$order_info.cb_used|string_format:"%d"})  {* {$lang.earned_cb} ({$total_reward_points})*}</li>
</ul>

<!--Address-->
<div class="orderaddressdetails">
<div class="heading">{$lang.shipping_details}</div>

<div class="username">{$order_info.s_firstname} {$order_info.s_lastname}</div>
<div class="detail">+91-{$order_info.s_phone}</div>
<div class="detail">{$order_info.email}</div>

<div class="subheading">{$lang.delivery_address}</div>
<div class="detail">{$order_info.s_address} {$order_info.s_address_2} {$order_info.s_city} {$order_info.s_state} - {$order_info.s_zipcode}</div>



{*<!--<div class="detail">
This order has multiple shipping addresses,
<br>
<a href="#">Click here to view.</a>
</div>-->*}

</div>
{*
<!--Products In Order
<div class="orderproduct">
<div class="image"><img src="images/skin/img_product.gif" /></div>
<div class="details">
<div class="name">ACER Backpack</div>
<div class="row"><label>Order ID :</label> 023569</div>
<div class="row"><label>You Paid :</label> <span>Rs.399</span></div>
</div>

</div>

</div>-->
*}

</div>


{include file="common_templates/bazooka.tpl"}
{hook name="checkout:order_confirmation"}
{/hook}
<!--Modified by clues dev to allow registered user as guest checkout.-->
{if $order_info && $settings.General.allow_create_account_after_order == "Y" && !$auth.user_id && !$user_exist}
<h2 class="subheader">{$lang.create_account}</h2>
<form name="order_register_form" action="{""|fn_url}" method="post" style="width:380px; margin-left:200px;">
    <input type="hidden" name="order_id" value="{$order_info.order_id}" />

    {if $settings.General.use_email_as_login != "Y"}
    <div class="form-field">
        <label for="user_login_profile" class="cm-required">{$lang.username}:</label>
        <input id="user_login_profile" type="text" name="user_data[user_login]" size="32" maxlength="32" value="" class="input-text" />
    </div>
    {/if}

    <div class="form-field">
        <label for="password1" class="cm-required cm-password">{$lang.password}:</label>
        <input type="password" id="password1" name="user_data[password1]" size="32" maxlength="32" value="" class="input-text cm-autocomplete-off" />
    </div>

    <div class="form-field">
        <label for="password2" class="cm-required cm-password">{$lang.confirm_password}:</label>
        <input type="password" id="password2" name="user_data[password2]" size="32" maxlength="32" value="" class="input-text" autocomplete="off" />
    </div>

    <div class="buttons-container margin-top">
        <p>{include file="buttons/button.tpl" but_name="dispatch[checkout.create_profile]" but_text=$lang.create}</p>
    </div>
</form>
{/if}
       
{*

        {include file="buttons/button.tpl" but_text=$lang.view_orders but_href="orders.search"}
        
        {include file="buttons/continue_shopping.tpl" but_href=$continue_url|default:$index_script}
    </div>
</div>
*}
   <!--     <div class="wysiwyg-content" style="float: left;margin-top: 50px;">
    <h1>Thank you for your purchase!</h1>
    <p style="color: #048CCC;font-size: 15px;">Delivery Estimated Between {$order_info.pdd1} and {$order_info.pdd2} to {$order_info.s_zipcode}</p>
    <p style="font-size: 12px;">You will receive an order confirmation email with details of your order.</p>
</div> -->
{capture name="mainbox_title"}{$lang.order}{/capture}

<!-- Tracking code -->
{assign var="utm_source" value=$smarty.cookies.utm_source|strtolower}
{if !empty($order_info.promotion_ids) }

    {assign var="promotion_internal_name" value=$order_info.promotion_ids|fn_find_promotion_for_affiliates:$utm_source}
    {if $promotion_internal_name}
        {assign var="promotional_status" value="TRUE"}
    {else}
        {assign var="promotion_type_one_day" value=$order_info.promotion_ids|fetch_promotion_type_from_id:$config.promotion_types_one_day}
        {if !$promotion_type_one_day}
            {assign var="promotion_type_special_deal" value=$order_info.promotion_ids|fetch_promotion_type_from_id:$config.promotion_types_special_sale}
        {/if}
        {if !$promotion_type_special_deal}
            {assign var="promotion_type_other_sale" value=$order_info.promotion_ids|fetch_promotion_type_from_id:$config.promotion_type_other_sale}
        {/if}
    {/if}
{/if}

{assign var="utm_data" value=$utm_source|fn_set_cookie_for_utm_source}
{assign var="whole_sale_status" value=$order_info.items|fn_find_categories_for_affiliates:$utm_source}
{if $utm_data && $whole_sale_status}
    
    {assign var="unique_value" value=$order_info.order_id|cat:$order_info.timestamp}
    {assign var="utm_data" value=$utm_data|replace:'[ORDER_ID]':$unique_value}
    {assign var="utm_data" value=$utm_data|replace:'[ORDER_AMOUNT]':$order_info.total}

    {if $utm_data|strpos:"[ACTION]"}
        {if $promotional_status=='TRUE' || $promotion_type_one_day}
            {if $utm_source == 'markgroup'}
                {assign var="utm_data" value=$utm_data|replace:'[ACTION]':$config.markgroup_coupon_for_same_affiliate}
            {elseif $utm_source == 'payoom'}
                {assign var="utm_data" value=$utm_data|replace:'[ACTION]':$config.payoom_coupon_for_same_affiliate}
            {elseif $utm_source == 'vcom' &&  $config.new_vcom_stat==1}
                {assign var="utm_data" value=$utm_data|replace:'[ACTION]':$config.coupon_for_vcom}
            {elseif $utm_source == 'icubes' &&  $config.new_icubes_stat==1}
                {assign var="utm_data" value=$utm_data|replace:'[ACTION]':$config.coupon_for_icubes}
             {elseif $utm_source == 'shoogloo'}
                {assign var="utm_data" value=$utm_data|replace:'[ACTION]':$config.coupon_for_trootrac} 
            {elseif $utm_source == 'komli'}
                {assign var="utm_data" value=$utm_data|replace:'[ACTION]':$config.coupon_for_komli}   
            {else}
                {assign var="utm_data" value=$utm_data|replace:'[ACTION]':$config.coupon_for_same_affiliate}
                {if $utm_source=='tyroo'}
                {assign var="tyroo_utm_source" value=$config.coupon_for_same_affiliate}
                {/if}
            {/if}
        {elseif $promotion_type_special_deal}
            {if $utm_source == 'markgroup'}
                {assign var="utm_data" value=$utm_data|replace:'[ACTION]':$config.markgroup_deal_coupon_aff}
            {elseif $utm_source == 'payoom'}
                {assign var="utm_data" value=$utm_data|replace:'[ACTION]':$config.payoom_deal_coupon_aff}
            {elseif $utm_source == 'vcom' &&  $config.new_vcom_stat==1}
                {assign var="utm_data" value=$utm_data|replace:'[ACTION]':$config.deal_coupon_for_vcom}
            {elseif $utm_source == 'icubes' &&  $config.new_icubes_stat==1}
                {assign var="utm_data" value=$utm_data|replace:'[ACTION]':$config.deal_coupon_for_icubes}
            {elseif $utm_source == 'shoogloo'}
                {assign var="utm_data" value=$utm_data|replace:'[ACTION]':$config.deal_coupon_for_trootrac} 
            {elseif $utm_source == 'komli'}
                {assign var="utm_data" value=$utm_data|replace:'[ACTION]':$config.deal_coupon_for_komli}    
            {else}
                {assign var="utm_data" value=$utm_data|replace:'[ACTION]':$config.deal_coupon_aff}
                {if $utm_source=='tyroo'}
                    {assign var="tyroo_utm_source" value=$config.deal_coupon_aff}
                {/if}
            {/if}  
        {/if}
        {if empty($order_info.promotion_ids) || $promotion_type_other_sale}
            {assign var="category" value=$order_info.items|fn_find_categories:$utm_source}
            {if $utm_source == 'vcom' && $category=='others' &&  $config.new_vcom_stat==1}
                {assign var="utm_data" value=$utm_data|replace:'[ACTION]':$config.others_for_vcom}
            {elseif $utm_source == 'vcom' && $category=='electronics' &&  $config.new_vcom_stat==1}
                {assign var="utm_data" value=$utm_data|replace:'[ACTION]':$config.elec_for_vcom}
            {elseif $utm_source == 'icubes' && $category=='others' &&  $config.new_icubes_stat==1}
                {assign var="utm_data" value=$utm_data|replace:'[ACTION]':$config.others_for_icubes}
            {elseif $utm_source == 'icubes' && $category=='electronics' &&  $config.new_icubes_stat==1}
                {assign var="utm_data" value=$utm_data|replace:'[ACTION]':$config.elec_for_icubes}   
            {else}
                {assign var="utm_data" value=$utm_data|replace:'[ACTION]':$category}
                {if $utm_source=='tyroo'}
                    {assign var="tyroo_utm_source" value=$category}
                {/if}
            {/if}
        {/if}
    {/if}
    {if !$utm_data|strpos:"[ACTION]"}
    {if $utm_source=='tyroo' && $config.tyroo_enable_stat == 1  && $config.tyroo_new_enable_script != 1}
        {$utm_data|unescape}
    {elseif $utm_source=='tyroo' && $config.tyroo_new_enable_script == 1 && $config.tyroo_enable_stat != 1}
        {literal}
        <script type="text/javascript">
            cookieid ='';
            getjson ={TRANSACTIONID_VALUE:{/literal}'{$unique_value}'{literal}, CARTVALUE_VALUE:{/literal}'{$order_info.total}'{literal}, ACTION_KEY:{/literal}'{$tyroo_utm_source}'{literal}, OPTIONALADVER_VALUE:''};
            res =encodeURIComponent( JSON.stringify( getjson ) );
            loc = document.URL;
            var scr = document.createElement("script");
            var host = (("https:" == document.location.protocol) ? "https://" : "http://") + "srv.tyroodr.com/www/delivery";
            scr.setAttribute('async', 'true');
            scr.type = "text/javascript";
            scr.src = host + "/container.php?cid=247&getjson="+res+'&loc='+loc+'&cookieid='+cookieid; ((document.getElementsByTagName('head') || [null])[0] || document.getElementsByTagName('script')[0].parentNode).appendChild(scr);
        </script>
        {/literal}
    {elseif $utm_source=='tyroo' && $config.tyroo_enable_stat == 1 && $config.tyroo_new_enable_script == 1}
        {$utm_data|unescape}
        {literal}
        <script type="text/javascript">
            cookieid ='';
            getjson ={TRANSACTIONID_VALUE:{/literal}'{$unique_value}'{literal}, CARTVALUE_VALUE:{/literal}'{$order_info.total}'{literal}, ACTION_KEY:{/literal}'{$tyroo_utm_source}'{literal}, OPTIONALADVER_VALUE:''};
            res =encodeURIComponent( JSON.stringify( getjson ) );
            loc = document.URL;
            var scr = document.createElement("script");
            var host = (("https:" == document.location.protocol) ? "https://" : "http://") + "srv.tyroodr.com/www/delivery";
            scr.setAttribute('async', 'true');
            scr.type = "text/javascript";
            scr.src = host + "/container.php?cid=247&getjson="+res+'&loc='+loc+'&cookieid='+cookieid; ((document.getElementsByTagName('head') || [null])[0] || document.getElementsByTagName('script')[0].parentNode).appendChild(scr);
        </script>
        {/literal}
    {elseif $utm_source!='tyroo'}
        {$utm_data|unescape}
    {/if}
{/if}
{literal}

<script type="text/javascript">
    {/literal}
    var utm_source = '{$utm_source}';
    {literal}
    SetCookie("utm_source",utm_source,'1','.shopclues.com');
    function SetCookie(cookieName,cookieValue,nDays,domain) {
        var today = new Date();
        var expire = new Date();
        if (nDays==null || nDays==0) nDays=1;
        expire.setTime(today.getTime() + 60000*nDays);
        document.cookie = cookieName+"="+escape(cookieValue) + ";expires="+expire.toGMTString()+";domain="+domain;

    }
</script>
{/literal}
{/if}
{assign var="product_name" value=""}
{assign var="product_id" value=""}
{foreach from=$order_info.items item="product"}

{if $config.pixels_on_thankyou_page.targeting_mantra}
    <img src='http://api.targetingmantra.com/RecordEvent?mid=130915&eid=2&pid={$product.product_id}&cid={$smarty.session.auth.user_id}' width='1' height='1'>
{/if}

{if $product_name == ""}
{assign var="product_name" value=$product.product}
{assign var="product_id" value=$product.product_id}
{assign var="pro_images" value=$product.product_id|fn_get_image_pairs:'product':'M'}
{/if}
    {/foreach}
    {assign var="product_url" value="products.view?product_id=`$product.product_id`"|fn_url}
    {assign var="pro_url" value=$config.http_location|cat:$product_url}
{assign var="ret_url" value=$config.https_location|cat:'/'}
{assign var="ret_url" value=$ret_url|cat:$config.current_url|escape:'url'}
{assign var="pro_image" value=$config.ext_images_host|cat:$pro_images.detailed.http_image_path}
   
{*Commented out wrt  JIRA - 119 Bazooka, Don't need this block in new design*}   
{*<a href="{"`$config.current_location`/fb_apps/orders_share.php?pro_url=`$pro_url`&pro_name=`$product_name`&pro_image=`$pro_image`&redirect_url=`$ret_url`"|fn_url }" target="_new">{$lang.share_on_facebook}</a>*}

<br /><br /><br />
{*<div class="buttons-container float_right clear{if !$order_info || !$settings.General.allow_create_account_after_order == "Y" || $auth.user_id} margin-top{/if}">
<div class="wysiwyg-content" style="float: left;margin-bottom: 25px;width:100%;">
    <h1>Thank you for your purchase!</h1>
    <p style="color: #048CCC;font-size: 15px;">Delivery Estimated Between {$order_info.pdd1} and {$order_info.pdd2} to {$order_info.s_zipcode}</p>
    <p style="font-size: 12px;">You will receive an order confirmation email with details of your order.</p>
</div>
 {if $express_setup!=''}
 {$lang.express_setup_lang}
 {/if}
<div class="buttons-container float_right clear{if !$order_info || !$settings.General.allow_create_account_after_order == "Y" || $auth.user_id} margin-top{/if}">
    
    <div class="ordr_complete_options float-right">
        {if $order_info}
        {if $order_info.child_ids}
        {include file="buttons/button.tpl" but_text=$lang.my_order_details but_href="`$index_script`?dispatch[orders.search]=Search&period=A&order_id=`$order_info.child_ids`"}
        {else}
        {include file="buttons/button.tpl" but_text=$lang.my_order_details but_href="orders.details?order_id=`$order_info.order_id`"}
        {/if}
        {/if}

        {include file="buttons/button.tpl" but_text=$lang.view_orders but_href="orders.search"}
        
        {include file="buttons/continue_shopping.tpl" but_href=$continue_url|default:$index_script}
    </div>
</div>
*}
{*
        <div class="wysiwyg-content" style="float: left;margin-top: 50px;">
    <h1>Thank you for your purchase!</h1>
    <p style="color: #048CCC;font-size: 15px;">Delivery Estimated Between {$order_info.pdd1} and {$order_info.pdd2} to {$order_info.s_zipcode}</p>
    <p style="font-size: 12px;">You will receive an order confirmation email with details of your order.</p>
</div> 
*}
{*Comment end  here wrt JIRA - 119 Bazooka*}

{if !empty($utm_source) && !$utm_data|strpos:"[ACTION]"}
{assign var="utm_log" value=$order_info.order_id|fn_utm_source_log:$utm_source:$utm_data}
{/if}


{if $smarty.cookies.utm_source == '' && $smarty.cookies.utm_campaigns !='' && !$utm_data|strpos:"[ACTION]"}
{assign var="utm_log" value=$order_info.order_id|fn_utm_source_log:'shopclues':''}
{/if}

{*code added by rahul to show hidden params for sokrati --- start here*}
{assign var="payment" value=$order_info.payment_method}
{assign var="payment_mode" value=$payment.payment}
{assign var="product_name" value=""}
{assign var="product_sku" value=""}
{assign var="product_quantity" value="0"}
{assign var="experian_pixel_pattern_val" value=""}
{foreach from=$order_info.items item="product"}


    {if $config.zettata_track && $config.zettata_master_switch}
    {assign var="prodName" value=$product.product|replace:'-':''}

            {literal}
            <script type="text/javascript" src="http://cdn.zettata.com/tracker.js"></script>
            <script type="text/javascript">
           
            var order_id ='{/literal}{$order_info.order_id}{literal}';
            var prodName='{/literal}{$prodName}{literal}';
            var price='{/literal}{$product.price}{literal}';
            var quantity='{/literal}{$product.amount}{literal}';
            var param="Shopclues--"+order_id+"--"+prodName+"--"+price+"--"+quantity;
            
            zettataTracker(param);

             </script>
            {/literal}
    {/if}

    {if $config.piwik_switch}
        {assign var="product_category" value=$product.product_id|fn_get_category_for_piwik}
            {literal}
            <script type="text/javascript">
            var _paq = _paq || [];
            var productSKU ='{/literal}{$product.product_id}{literal}';
            var productName='{/literal}{$product.product_name}{literal}';
            var productCategory="";
            var price='{/literal}{$product.price}{literal}';
            var quantity='{/literal}{$product.amount}{literal}';
            var product_category='{/literal}{$product_category}{literal}';
            _paq.push(['addEcommerceItem',productSKU,productName,product_category,price,quantity]);
            </script>
            {/literal}
    {/if}
{assign var="product_names" value=$product.product|cat:','}
{assign var="product_name" value=$product_name$product_names}
{assign var="product_quantity" value=$product_quantity+$product.amount}
{assign var="sku" value=$product.product_code|cat:','}
{assign var="product_sku" value=$product_sku$sku}


{if $config.pixels_on_thankyou_page.experian_tracking}
{assign var="pipe" value="|"}
{assign var="experian_pixel_pattern" value=$product.product|cat:'@'|cat:$product.amount|cat:'@'|cat:$product.price}
{assign var="experian_pixel_pattern_val" value=$experian_pixel_pattern|cat:$pipe|cat:$experian_pixel_pattern_val}
{/if}
{/foreach}
{if $utm_source == 'googsokrati'}
<input type="hidden" name="order_id" value="{$order_info.order_id}" />
<input type="hidden" value="{$order_info.timestamp}" name="order_date" />
<input type="hidden" value="{$product_name}" name="product_name" />
<input type="hidden" value="{$product_quantity}" name="quantity" />
<input type="hidden" value="{$order_info.total}" name="price" />
<input type="hidden" value="{$payment_mode}" name="payment_mode" />
<input type="hidden" value="{$order_info.coupon_codes}" name="coupon_code" />
<input type="hidden" value="{$product_sku}" name="sku" />
{/if} 
{$lang.referal_script}
{*code added by rahul to show hidden params for sokrati ---- end here*}  

{*code added by rahul to display icube total amount --- start here*}

{if $config.pixels_on_thankyou_page.icubes}
{literal}
<script>
    window.CT_C_OrderTotal={/literal}{$order_info.total}{literal};
</script>

{/literal}

{/if}
{if $config.pixels_on_thankyou_page.viralmint}
{literal}
<script src="https://tracker.viralmint.com/sales/1376179846/{/literal}{$order_info.total}{literal}/{/literal}{$order_info.order_id}{literal}?COUPON_CODE={/literal}{$order_info.coupon_codes}{literal}"></script>

{/literal}
{/if}
{literal}
<script type="text/javascript">
    var piwik_switch="{/literal}{$config.piwik_switch}{literal}";
    var order_id="{/literal}{$order_info.order_id}{literal}";
    var grandtotal="{/literal}{$order_info.total}{literal}";
    var subtotal="{/literal}{$order_info.subtotal}{literal}";
    var shipping_cost="{/literal}{$order_info.shipping_cost}{literal}";
    adroll_conversion_value_in_dollars = {/literal}{$order_info.total}{literal};
    adroll_custom_data = {"ORDER_ID": {/literal}"{$order_info.order_id}"{literal}}
    adroll_adv_id = "FH7NTQ632VGRTITHRCKAZV";
    adroll_pix_id = "7VKIKCDLOVAG7CIK7EJUPB";
    (function () {
        var oldonload = window.onload;
        window.onload = function(){
         __adroll_loaded=true;
         var scr = document.createElement("script");
         var host = (("https:" == document.location.protocol) ? "https://s.adroll.com" : "http://a.adroll.com");
         scr.setAttribute('async', 'true');
         scr.type = "text/javascript";
         scr.src = host + "/j/roundtrip.js";
         ((document.getElementsByTagName('head') || [null])[0] ||
            document.getElementsByTagName('script')[0].parentNode).appendChild(scr);
         if(oldonload){oldonload()}};
     }());
//track order by piwik 
if(piwik_switch){
_paq.push(['trackEcommerceOrder',order_id,grandtotal,subtotal,'',shipping_cost,false ]);
_paq.push(["trackPageView"]);
 }
</script>

{/literal}

{foreach from=$config.thankyou_pixels_dynamic_lang_var key="pixel_key" item="pixel_val"}
{if $pixel_val}
        
        {$lang.$pixel_key}

{/if}
{/foreach}

{*code added by rahul to display icube total amount --- ends here*}

{if $config.pixels_on_thankyou_page.google_conversion}

{literal}
<!-- Google Code for Order Confirmation Conversion Page -->
<script type="text/javascript">
    /* <![CDATA[ */
    var google_conversion_id = 1008677249;
    var google_conversion_language = "en";
    var google_conversion_format = "3";
    var google_conversion_color = "ffffff";
    var google_conversion_label = "E5MNCNen8AIQgeP84AM";
    var google_conversion_value = {/literal}{$order_info.total}{literal};
    var google_remarketing_only = false;
    /* ]]> */
</script>
<script type="text/javascript" src="https://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
    <div style="display:inline;">
        <img height="1" width="1" style="border-style:none;" alt="" src="https://www.googleadservices.com/pagead/conversion/1008677249/?label=E5MNCNen8AIQgeP84AM&amp;guid=ON&amp;script=0"/>
    </div>
</noscript>

{/literal}
{/if}
{if $config.pixels_on_thankyou_page.fb_dynamic_value}
{literal}
<script type="text/javascript">
	var fb_param = {};
	fb_param.pixel_id = '6015094921571';
	fb_param.value = {/literal}{$order_info.total}{literal};
	fb_param.currency = 'INR';
	(function(){
		var fpw = document.createElement('script');
		fpw.async = true;
		fpw.src = '//connect.facebook.net/en_US/fp.js';
		var ref = document.getElementsByTagName('script')[0];
		ref.parentNode.insertBefore(fpw, ref);
	})();
</script>
<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/offsite_event.php?id=6015094921571&amp;value=0&amp;currency=INR" /></noscript>

{/literal}
{/if}
{if $config.pixels_on_thankyou_page.experian_tracking && $utm_source == 'internal-edm' && $smarty.cookies.utm_medium == 'email' && $smarty.cookies.utm_campaign|strpos:"cheetah" !== false}
{assign var="experian_pixel_pattern_val" value=$experian_pixel_pattern_val|html_entity_decode}
<img src= "https://offers.shopcluesmail.com/a/r2094636840/shpclues.gif?id={$order_info.order_id}&amount={$order_info.total}&items={$experian_pixel_pattern_val|rtrim:'|'}">
{/if}
{if $config.pixels_on_thankyou_page.komliremarketing && $utm_source == 'komliremarketing'}
{literal}
<script type="text/javascript" src="//trk.atomex.net/cgi-bin/tracker.fcgi/conv?px=10566&ty=1&tid={/literal}{$order_info.order_id}{literal}&tamt={/literal}{$order_info.total}{literal}"></script>
{/literal}
{/if}
{if $config.pixels_on_thankyou_page.flex_msn_yahoo}
{literal}
<script type="text/javascript"> if (!window.mstag) mstag = {loadTag : function(){},time : (new Date()).getTime()};</script> <script id="mstag_tops" type="text/javascript" src="//flex.msn.com/mstag/site/fa9922cf-753c-4953-be48-1a185e36ee0f/mstag.js"></script> <script type="text/javascript"> mstag.loadTag("analytics", {dedup:"1",domainId:"2725218",type:"1",revenue:{/literal}"{$order_info.total}"{literal},actionid:"191734"})</script> <noscript> <iframe src="//flex.msn.com/mstag/tag/fa9922cf-753c-4953-be48-1a185e36ee0f/analytics.html?dedup=1&domainId=2725218&type=1&revenue=&actionid=191734" frameborder="0" scrolling="no" width="1" height="1" style="visibility:hidden;display:none"> </iframe> </noscript>
{/literal}
{/if}
