{* $Id: grid_list.tpl 10220 2010-07-27 09:09:00Z alexions $ *}
{if $products}
    {assign var="columns" value=$category_data.product_columns}
    {if $products|sizeof < $columns}
        {assign var="columns" value=$products|@sizeof}
    {/if}
    {split data=$products size=$columns|default:"2" assign="splitted_products"}
    {math equation="100 / x" x=$columns|default:"2" assign="cell_width"}
    {assign var="new_width_val" value=$cell_width|string_format:"%.0f"}
    {if $item_number == "Y"}
        {assign var="cur_number" value=1}
    {/if}    
{/if}    



<div id="page_{$page_new}" class="ajaxified-pages {if $new_width_val=="33"}grid_3{elseif $new_width_val=="50"}grid_2{/if}">
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
    
    {foreach from=$splitted_products item="sproducts" name="sprod"}
        {foreach from=$sproducts item="product" name="sproducts"}
            {if $product.product_id}
                {assign var="obj_id" value=$product.product_id}
                {assign var="obj_id_prefix" value="`$obj_prefix``$product.product_id`"}
                {include file="common_templates/product_data.tpl" product=$product}

            <div class="box_metacategory box_GridProduct" style="margin-top:10px; margin-left:10px; padding-left:10px; {if ($category_data.show_feature=='Y' && $new_width_val=="33") || ($category_data.show_feature=='Y' && $new_width_val=="50")}height:520px;{elseif ($category_data.show_feature=='N' && $new_width_val=="33") || ($category_data.show_feature=='N' && $new_width_val=="50")}height:465px;{elseif $controller=="products" && $mode=="search" && $config.key_feature_on_search && $new_width_val=="50" }height:465px;{elseif $new_width_val=="25" || $new_width_val=="20"}height:360px;{/if}">
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

    {if $smarty.request.category_id != $lang.48hrsale_category_id}
        <div id="line_prc_discount_value_{$obj_prefix}{$product.product_id}" class="box_metacategory_discount {if $after_apply_promotion !=0} third_price_discount {/if} {if !$config.fb_user_engagement &&  $product.product_amount_available == 0}sold_out_category{/if}">
		<span id="prc_discount_value_label_{$obj_prefix}{$product.product_id}"  class="box_metacategory_discount_number">
        {$disc_perc}%
        </span>
		<span class="box_metacategory_discount_off">Off</span>
		</div>
    {/if}
{/if}
{*$pro_image|print_r*}

{if $config.fb_user_engagement}
{* code for New style for sold out, wishlist and google and facebook share *}
<div class="box_GridProduct_wishList" style="left:10px; top:296px; {if $new_width_val=="50"}width:394px;{/if} {if $new_width_val=="33"}width:250px;{/if} ">
    {if $product.product_amount_available =='0'}
    <div class="top">{$lang.sold_out_feature}</div>
    {/if}
    <div class="middle">
        {* code for facebook share, google share and wishlist *}

        <a id="ajaxified_wishlist" class="ajaxified_wishlist" onclick="ajaxifiedWishlist({$product.product_id})">Add to Wishlist</a>
        <a class="googleShare" onclick="googleShare({$product.product_id})"></a>
        <a class="facebookShare" onclick="fbShare({$product.product_id})"></a>

        {* code for facebook share, google share and wishlist ends here*}
    </div>
    <div class="bottom"></div>
</div>
<div class="clearboth"></div>
{* code for New style for sold out, wishlist and google and facebook share ends here *}
{else}
{if $product.product_amount_available == 0}<div class="sold_out_text_pic product_grid_sold_out">{$lang.sold_out_feature}</div>{/if}
{/if}

<a href="{"products.view?product_id=`$product.product_id`"|fn_url}" id ="{$product.product_id}" class="box_metacategory_image {if !$config.fb_user_engagement && $product.product_amount_available == 0}sold_out_category{/if}" >
{if $controller=='categories' and $mode=='view'}
                {assign var="pro_image" value=$product.product_id|fn_get_image_pairs:'product':'M'}
                {include file="common_templates/image.tpl" obj_id=$obj_id_prefix images=$pro_image object_type="product" show_thumbnail="Y" image_width="320" image_height="320"  no_height=true}
           <!-- <img class="src2srconscroll" src="{$config.http_path}/blank.gif" src2="http://cdn.shopclues.com/{$pro_image.detailed.http_image_path}" alt="{$product.product}" title="{$product.product}" /> -->
{else}
 {include file="common_templates/image.tpl" obj_id=$obj_id_prefix images=$product.main_pair object_type="product" show_thumbnail="Y"  image_width="320" image_height="320" }
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
   <a href="{"products.view?product_id=`$product.product_id`"|fn_url}" class="box_metacategory_name {if !$config.fb_user_engagement && $product.product_amount_available == 0}sold_out_category{/if}" alt="{$product.product}" title="{$product.product}">
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
            {/if}
        {/foreach}
    {/foreach}

{if $smarty.request.category_id !=''}
    {assign var="dfp_ads" value="category_googleads_"|cat:$smarty.request.category_id|cat:"_"|cat:$page_new-1}
    {assign var="dfp_lang" value="_"|cat:$dfp_ads}
    {if $lang.$dfp_ads != $dfp_lang && $config.isResponsive == 0}
        {$lang.$dfp_ads}
        <div class="clearboth margin_top_twenty" style="float: left;width: 100%;"></div>
    {/if}
{/if}

<div class="topBar"><span class="pageNum">Page {$page_new}</span><span class="loader"><img src="{$config.ext_images_host}/images/skin/ajax-loader.gif"><span>Loading more results.....</span></span></div>
<a class="clickMore">Click here to show more results.....</a>
{/if}
</div>
<div class="pagination-end"></div>




{capture name="mainbox_title"}{$title}{/capture}
<div id="prod{$product.product_id}" rev="index.php?dispatch=product_quick_view.view&product_id={$product.product_id}" onclick="quick_view_click({$product.product_id});" class="ql_icon_blk"></div>

{literal}
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
        $(".product-notification-container").remove();
        prod=product_id;
        var update_url = "index.php?dispatch=product_quick_view.view&product_id="+product_id;
        jQuery.ajaxRequest(update_url, {method: 'GET', cache: false, result_ids:'cart_status',
            callback:function(data)
            {
                fn_update_quick_look(product_id);
                $("#button_express_"+prod).die("click",unexp);
                $("#button_express_"+prod).live("click",unexp);
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
         function unexp()
     {
         $("#cart_form").die("submit",docmajax);
         return true;
     }
     $("#button_express_"+prod).live("click",unexp);
 </script>

{/literal}
