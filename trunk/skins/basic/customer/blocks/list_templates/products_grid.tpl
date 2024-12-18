{* $Id: products_grid.tpl 11222 2010-11-16 11:53:42Z klerik $ *}
<div id="page_2">
<input type="hidden" value="{$products|count}" id="products_count">
<input type="hidden" value="{$product_count}" id="total_count_products">
{if $smarty.request.pp && ($smarty.request.pp != '' || $smarty.request.pp != 0) && !$config.isResponsive}
        <input type="hidden" value="{$smarty.request.pp}" id="products_per_page">
    {else}
	{if $config.products_limit_per_page && $config.products_limit_per_page > 0}
		<input type="hidden" value="{$config.products_limit_per_page}" id="products_per_page">
	{else}
		<input type="hidden" value="{$settings.Appearance.products_per_page}" id="products_per_page">
	{/if}
    {/if}
{if $products}

{script src="js/exceptions.js"}


{if !$no_pagination}
    {include file="common_templates/pagination.tpl"}
{/if}
{if !$no_sorting}
    {include file="views/products/components/sorting.tpl"}
{/if}

{if $products|sizeof < $columns}
    {assign var="columns" value=$products|@sizeof}
{/if}
{split data=$products size=$columns|default:"3" assign="splitted_products"}
{math equation="100 / x" x=$columns|default:"3" assign="cell_width"}
{if $item_number == "Y"}
    {assign var="cur_number" value=1}
{/if}



{assign var="total_count" value=$products|count}
{assign var="key" value=0}
{assign var="adword" value=""}

{foreach from=$products item="product" name="sproducts" key="k"}
{assign var="obj_id" value=$product.product_id}
{assign var="obj_id_prefix" value="`$obj_prefix``$product.product_id`"}
{include file="common_templates/product_data.tpl" product=$product}
<!--added by sapna -->
{if isset($smarty.request.page)}
    {math assign="key" equation="(x*z)+(y)" x=$smarty.request.page-1 y=$key+1 z=$product_per_page}

{else}
    {assign var="key" value=$key+1}

{/if}
{assign var="elevate" value=""}
{assign var="elevate" value=$product|fn_check_elevated:$smarty.request.q:$key:$smarty.request.page}

{if $elevate ==1} {assign var="adword" value=$adword|cat:$product.product_id|cat:","} {/if}
{if $smarty.foreach.sproducts.last && empty($smarty.request.page) && empty($smarty.request.sort_order) && !empty($adword)} <input type="hidden" id="ad_products" value="{$adword}" onclick="fn_show_product('{$adword}','{$smarty.request.q}')"/> {/if}

<div class="box_metacategory box_GridProduct" style="margin-top:10px; margin-left:10px; padding-left:10px; {if $category_data.show_feature=='Y'}height:360px;{elseif $controller=="products" && $mode=="search" && $config.key_feature_on_search }height:360px;{/if}{if $elevate ==1 && $config.sponsored_highlight} background-color:#edf8fe;{/if}">
{assign var="after_apply_promotion" value=0}
{if $product.promotion_id !=0}
    {assign var="after_apply_promotion" value=$product|fn_get_3rd_price}
{/if}

{assign var="image_cat_id" value=$product.category_ids|fn_get_category_image}
{assign var="image_cat" value="-"|explode:$image_cat_id}
{if $image_cat.1}
    {if $image_cat.0 > 0}
        <div class="cate_icon_nl">
            <img src="{$image_cat.1}">
            <div class="label_cate_image" style="display:none;">{$lang.cate_image_hover}</div> </div>
    {/if}
{/if}

{assign var="is_new" value=$product|check_product_for_new}
{if $is_new == 'new'}
    <div class="box_metacategory_labelnew"></div>
{/if}

{assign var="is_ngo" value="$product.company_id|fn_check_merchant_for_ngo"}
        {if $is_ngo == 'Y'}
        <div class="box_GridProduct_ngolabel">{$lang.ngo_popup_hover}</div>
        {/if}
       {assign var="after_apply_promotion" value=0}
        {if $product.promotion_id !=0}
            {assign var="after_apply_promotion" value=$product|fn_get_3rd_price}
        {/if}

{if $product.discount || $product.list_discount_prc || ($after_apply_promotion!=0)}

        {if $product.promotions}
            {assign var="disc_perc" value=$product|calculate_discount_perc}
        {else}
            {if $product.discount}
              {assign var="disc_perc" value=$product.discount_prc}
            {else}
              {assign var="disc_perc" value=$product.list_discount_prc}
            {/if}
        {/if}


                {if $product.promotion_id !=0}
                        {if $after_apply_promotion !=0}
                                {assign var="disc_perc" value=$product|calculate_3rd_price_percentage:$after_apply_promotion}
                        {/if}
                {/if}

    {if $disc_perc>=50}
      {assign var="styles" value="label_discount_grid_first"}
    {elseif $disc_perc>=0 and $disc_perc<=49}
      {assign var="styles" value="label_discount_grid_second"}
    {/if}


    {if $disc_perc>=50}
      {assign var="styles" value="label_discount_grid_first"}
    {elseif $disc_perc>=0 and $disc_perc<=49}
      {assign var="styles" value="label_discount_grid_second"}
    {/if}



     {*{if $smarty.request.category_id != $lang.48hrsale_category_id}*}

        <div id="line_prc_discount_value_{$obj_prefix}{$product.product_id}" class="box_metacategory_discount {if $after_apply_promotion !=0} third_price_discount {/if} {if !$config.fb_user_engagement && $product.product_amount_available == 0}sold_out_category{/if}">
        <span id="prc_discount_value_label_{$obj_prefix}{$product.product_id}"  class="box_metacategory_discount_number">
        {$disc_perc}%

        </span>
        <span class="box_metacategory_discount_off">Off</span>
        </div>
        {*value added services start - anoop*}
        {if $product.value_added_services.qty_disc_flag == '1'}
                        <div class="icon_EDB_small">     
                        </div>
          {/if}
          {if $product.value_added_services.min_qty_disc_flag == '1'}
                        <div class="icon_EDR_small">
                        </div>
          {/if}
       {*value added services end*}
  {*{/if}*}
{/if}
<input type="hidden" id="quick_view_load{$product.product_id}" value=0>
 <!--added by sapna -->

{if $after_apply_promotion !=0}
    {assign var="last_price" value=$after_apply_promotion}

{else}
    {if $product.price !=0}
        {assign var="last_price" value=$product.price}

    {else}
      {assing var="last_price" value=$product.list_price}

    {/if}
{/if}

{if $config.fb_user_engagement}
{* code for New style for sold out, wishlist and google and facebook share *}
<div class="box_GridProduct_wishList" style="top:141px;">
    {if $product.product_amount_available =='0'}
        <div class="top">{$lang.sold_out_feature}</div>
    {/if}
    <div class="middle">
        {* code for facebook share, google share and wishlist *}

        {include file="common_templates/product_data.tpl" obj_prefix='' hide_enctype=true}
        {assign var="form_open" value="form_open_`$product.product_id`"}
        {$smarty.capture.$form_open}
        <span class="nl_add_wish_list" id="cart_buttons_block_{$product.product_id}">
        {include file="addons/wishlist/views/wishlist/components/add_to_wishlist.tpl" but_id="button_wishlist_`$product.product_id`" but_name="dispatch[wishlist.add..`$product.product_id`]" but_role="text"}
        </span>
        {assign var="form_close" value="form_close_`$product.product_id`"}
        {$smarty.capture.$form_close}
        <a  class="googleShare" onclick="googleShare({$product.product_id})"></a>
        <a  class="facebookShare" onclick="fbShare({$product.product_id})"></a>

        {* code for facebook share, google share and wishlist ends here *}
    </div>
    <div class="bottom"></div>
</div>
<div class="clearboth"></div>
{* code for New style for sold out, wishlist and google and facebook share ends here *}
{else}
    {if $product.product_amount_available == 0}<div class="sold_out_text_pic product_grid_sold_out">{$lang.sold_out_feature}</div>{/if}
{/if}

{*hardcoded{assign var="elevate" value=1}*}
{assign var="keyword" value=$smarty.request.q}
{assign var="search_keyword" value=""|fn_check_elevated:$smarty.request.q:"":""}

{assign var="url" value=""}
{if $elevate ==1}

        {assign var="track" value=""}
        {assign var="track" value=$track|cat:"@@"|cat:$key|cat:"@@"|cat:$search_keyword|cat:"@@"|cat:$elevate}
        {assign var="url" value=$track|base64_encode}
        {assign var="url" value="?track=$url"}
{/if}
{if $controller=='products' and $mode=='search'}
  {*{if !empty($smarty.request.name)}
    {assign var="url" value=$smarty.request.name}
    {assign var="url" value="?utm_source=$url"}
  {/if}*}
{if $config.zettata_master_switch && $config.zettata_track}
    {assign var="zettata_url" value=$product.click_url}
{else}
    {assign var="zettata_url" value=""}       
{/if}
      
	<a href="{"products.view&product_id=`$product.product_id`"|fn_url}{$url}" id ="{$product.product_id}" class="box_metacategory_image {if !$config.fb_user_engagement && $product.product_amount_available == 0}sold_out_category{/if}" onclick="productCookie('{$product.product_id}','{$key}','{$last_price}','{$zettata_url}');">

{else}
 <a href="{"products.view?product_id=`$product.product_id`"|fn_url}" id ="{$product.product_id}" class="box_metacategory_image {if !$config.fb_user_engagement && $product.product_amount_available == 0}sold_out_category{/if}">

{/if}
{if $controller=='categories' and $mode=='view'}
    {assign var="pro_image" value=$product.product_id|fn_get_image_pairs:'product':'M'}
    {include file="common_templates/image.tpl" obj_id=$obj_id_prefix images=$pro_image object_type="product" show_thumbnail="Y" image_width="160" image_height="160"}


{else}
    {include file="common_templates/image.tpl" obj_id=$obj_id_prefix images=$product.main_pair object_type="product" show_thumbnail="Y" image_width="160" image_height="160"}
{/if}
</a>
{if $config.quick_view_show}
<div id="prod{$product.product_id}" rev="index.php?dispatch=product_quick_view.view&product_id={$product.product_id}" onclick="quick_view_click({$product.product_id});" class="ql_icon_blk"></div>
{/if}
	{if $product.is_wholesale_product && $config.wholesale_icon}
		<div class="clearboth"></div>
		<div class="icon_wholesale"></div>
	{/if}

   {if $item_number == "Y"}
   {$cur_number}.&nbsp;{math equation="num + 1" num=$cur_number assign="cur_number"}
   {/if}
{if $controller=='products' and $mode=='search'}

         <a href="{"products.view&product_id=`$product.product_id`"|fn_url}{$url}" id ="{$product.product_id}" class="box_metacategory_image
    {if !$config.fb_user_engagement && $product.product_amount_available == 0}sold_out_category{/if}" onclick="productCookie('{$product.product_id}','{$key}','{$last_price}','{$zettata_url}');">

  {else}
    <a href="{"products.view?product_id=`$product.product_id`"|fn_url}" id ="{$product.product_id}" class="box_metacategory_name" alt="{$product.product}" title="{$product.product}">
{/if}
{if $config.isResponsive}
    {$product.product}
{else}
    {$product.product|truncate:40:"...."}
{/if}
    </a>

<div class="box_metacategory_rating">
    {assign var="average_rating" value=$product.product_id|fn_get_average_rating:'P'}
    {if $average_rating}
    {include file="addons/discussion/views/discussion/components/top_banner_stars.tpl" stars=$average_rating|fn_get_discussion_rating}
    {/if}
</div>
{if $category_data.show_feature=='Y'}
<div class="key_features">

    {assign var="key_features" value=$product.product_id|get_products_feature}
    {if $key_features|count > "0"}
        <ul class="box_metacategory_features">
            {foreach from=$key_features item="key_feature"}
            <li>{$key_feature.variant|truncate:20:"...."}</li>
            {/foreach}
        </ul>
    {/if}
</div>
{/if}


{if $controller=="products" && $mode=="search"  && $config.key_feature_on_search}

    {assign var="key_features" value=$product.product_id|get_products_feature}
<div class="key_features">
    {if $key_features|count > "0"}
    <ul class="box_metacategory_features">
        {foreach from=$key_features item="key_feature"}
        <li>{$key_feature.variant|truncate:20:"...."}</li>
        {/foreach}
    </ul>
    {/if}
</div>
{/if}

<div class="clearboth"></div>
<div class="box_metacategory_pricing pj2_price_bottom">

{if $product.list_price > $product.price}
<span class="box_metacategory_price" style="margin-left:0px;">MRP: {$product.list_price|format_price:$currencies.$secondary_currency:""}</span>

{/if}

{if $product.is_wholesale_product == 1 && !empty($product.retail_price) && $product.retail_price > $product.price && $product.retail_price < $product.list_price}
<span class="box_metacategory_priceoffer ret_gui_prc_offr no_mobile">Retail Price: {$product.retail_price|format_price:$currencies.$secondary_currency:""}</span>

{/if}

{if $after_apply_promotion !=0}
<span class="box_metacategory_price" style="margin:0;">{$product.price|format_price:$currencies.$secondary_currency:""}</span>
    {if $product.price_see_inside ==0}

    <span class="box_metacategory_priceoffer_mobile" style="color: #900; position: absolute; left:0; bottom:18px; font:bold 16px trebuchet ms; margin-top: -3px; float: left;">{$after_apply_promotion|format_price:$currencies.$secondary_currency:""}</span>
    {else}
    <span class="grid_blue_deal_nl"><a style="color:#fff!important;" href="{"products.view?product_id=`$product.product_id`"|fn_url}">{$lang.click_to_see_inside}</a></span>
    {/if}
{else}
    <span class="box_metacategory_priceoffer">{$product.price|format_price:$currencies.$secondary_currency:""}</span>
     {if $product.price_see_inside =="1" && (isset($product.special_offer_text) && !empty($product.special_offer_text)) }
    <span class="grid_blue_deal_nl"><a style="color:#fff!important;" href="{"products.view?product_id=`$product.product_id`"|fn_url}">{$lang.click_to_see_inside}</a></span>
    {/if}
{/if}
{if $product.freebee_inside =="1"}
<a class="grid_free_gft_nl" href="{"products.view?product_id=`$product.product_id`"|fn_url}" onClick="_gaq.push(['_trackEvent', 'Homepage', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.topproductlisting.iteration}']);"><img src="{$config.freebee_inside_image_url}" /></a>
                            {/if}

                            {if $product.deal_inside_badge =="1"}
                             <a class="grid_deal_inside_nl" href="{"products.view?product_id=`$product.product_id`"|fn_url}" onClick="_gaq.push(['_trackEvent', 'Homepage', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.topproductlisting.iteration}']);"><img src="{$config.cashback_inside_image_url}" /></a>
                            {/if}

{if $product.special_offer_badge =="1" || (isset($product.special_offer_text) && !empty($product.special_offer_text) && $product.price_see_inside !="1") }
<span  class="nl_red_icon_spl_offer_tag nl_fr_srch_fix"><a class="nl_prc_red_icon_spl" href="{"products.view?product_id=`$product.product_id`"|fn_url}" onClick="_gaq.push(['_trackEvent', 'Homepage', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.topproductlisting.iteration}']);">{$lang.see_special_offer_inside}</a></span>

{elseif $product.price_see_inside =="1" && $product.special_offer_badge =="1" }
<span  class="nl_red_icon_spl_offer_tag nl_fr_srch_fix"><a class="nl_prc_red_icon_spl" href="{"products.view?product_id=`$product.product_id`"|fn_url}" onClick="_gaq.push(['_trackEvent', 'Homepage', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.topproductlisting.iteration}']);">{$lang.see_special_offer_inside}</a></span>

{/if}

</div>

</div>

{/foreach}

{if $smarty.request.category_id !=''}
    {assign var="dfp_ads" value="category_googleads_"|cat:$smarty.request.category_id|cat:"_"|cat:1}
    {assign var="dfp_lang" value="_"|cat:$dfp_ads}
    {if $lang.$dfp_ads != $dfp_lang && $config.isResponsive == 0}
        {$lang.$dfp_ads}
        <div class="clearboth margin_top_twenty" style="float: left;width: 100%;"></div>
    {/if}
{/if}

<div class="topBar"><span class="pageNum">Page 2</span><span class="loader"><img src="{$config.ext_images_host}/images/skin/ajax-loader.gif"><span>Loading more results.....</span></span></div>
{/if}
</div>
<div class="pagination-end"></div>
<div class="clearboth"></div>




{if !$no_pagination}
    {include file="common_templates/pagination.tpl"}
{/if}



{capture name="mainbox_title"}{$title}{/capture}

{if $controller=='products' and $mode=='search'}

{literal}
<script language="javascript">

function productCookie(product_id,position,last_price,click_url)
{
    //fn_capture_click_on_product(product_id,type,keyword,position,elevate);
    if(click_url!=''){
        //$('#zettata_click').attr('src',click_url);
        $.ajax({
           type: "POST",
           url: click_url,
           success: function(msg){
            
           }
         });
    }

    var name = "searchlog"; var value = product_id+","+position+","+last_price; var expires = "";
    document.cookie = name + "=" + value + expires + "; path=/";
}

function fn_capture_click_on_product(product_id,type,keyword,position,elevate){
    $.ajax({
       type: "POST",
       url: "index.php?dispatch=products.adword_view",
       data: {product_id:product_id,type:type,keyword:keyword,position:position,elevate:elevate},
       success: function(msg){

       }
     });
}

function fn_show_product(ad_products,keyword){
    if(ad_products != '' && keyword != '') {
        var ad_products1 = ad_products.slice(0,-1);
        if(ad_products1 != '') {
            fn_capture_click_on_product(ad_products1,'I',keyword,1,1);
        }
    }
}
$( "#ad_products" ).trigger( "click" );
</script>
{/literal}
{/if}
{literal}

<script type="text/javascript">
  var piwik_switch="{/literal}{$config.piwik_switch}{literal}";
  if(piwik_switch){
	   var _paq = _paq || [];
	   _paq.push(["setCookieDomain", "*.shopclues.com"]);
	   var dispatch = "{/literal}{$smarty.request.dispatch}{literal}";
	   var keyword = "{/literal}{$smarty.request.q}{literal}";
	   var count="{/literal}{$product_count}{literal}";
	   var user_id="{/literal}{$smarty.session.auth.user_id}{literal}";
	   
	   if(user_id ==0){
	   var user_id="logged out";
	   }
	   
	   _paq.push(['setCustomVariable',1,"user id",user_id,scope="page"]); 
	   if(dispatch == "products.search" && keyword != '') 
	   {
	   _paq.push(['setCustomVariable',2,"search",keyword,scope="page"]); 
	    var catname = "{/literal}{$smarty.request.cid}{literal}";
		if(catname == '' || catname == '0') catname = false;
		 _paq.push(['trackSiteSearch',keyword,catname,count]);
		
	   }
  }
 </script> 
 <script>
     var prod="";
     function fn_update_quick_look(product_id)
     {
      var url="{/literal}{$config.current_url}{literal}";      
      $("div").removeClass( "product-notification" );
      document.getElementById('product_quick_redirect').value=url;
      $(".popupbox-closer").hide();
      var pinlength=ReadCookie("pincode").length;
      if(pinlength==6)
        check_pin(product_id,0);
      $('.clk_view_prd_blk').show();
     }
     function quick_view_click(product_id)
     {  
        prod=product_id;
        var update_url = "index.php?dispatch=product_quick_view.view&product_id="+product_id;
        jQuery.ajaxRequest(update_url, {method: 'GET', cache: false, result_ids:'cart_status',
            callback:function(data)
            {
                fn_update_quick_look(product_id);
                $("#cart_form").die("submit",docmajax);
                $("#cart_form").live("submit",docmajax);
            }
        });
     }
     function quick_look_close(product_id)
     {
         $(".product-notification-container").remove();
     }
     function docmajax()
     {
         var update_url="index.php?dispatch=checkout.add.."+prod+"&"+$("#cart_form").serialize();
          jQuery.ajaxRequest(update_url, {method: 'POST', cache: false, result_ids:'cart_status',
            callback:function(data)
            {
            }
        });
        return false;
     }
 </script>

{/literal}
