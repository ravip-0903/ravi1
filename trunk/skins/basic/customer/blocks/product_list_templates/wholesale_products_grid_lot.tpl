{* $Id: grid_list.tpl 10220 2010-07-27 09:09:00Z alexions $ *}
{if $products}
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

<div id="page_2" class="whole_sale_lot {if $new_width_val=="33"}grid_3{elseif $new_width_val=="50"}grid_2 {else}grid_3{/if}">
    <input type="hidden" value="{$products|count}" id="products_count">
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



{foreach from=$splitted_products item="sproducts" name="sprod"}
{foreach from=$sproducts item="product" name="sproducts"}
{if $product.product_id}
{assign var="obj_id" value=$product.product_id}
{assign var="obj_id_prefix" value="`$obj_prefix``$product.product_id`"}
{include file="common_templates/product_data.tpl" product=$product}

<div class="box_metacategory whl_sl_blk box_GridProduct" style="margin-top:10px; margin-left:10px; padding-left:10px; {if ($category_data.show_feature=='Y' && $new_width_val=="33") || ($category_data.show_feature=='Y' && $new_width_val=="50")}height:520px;{elseif ($category_data.show_feature=='N' && $new_width_val=="33") || ($category_data.show_feature=='N' && $new_width_val=="50")}height:465px;{elseif $controller=="products" && $mode=="search" && $config.key_feature_on_search && $new_width_val=="50" }height:465px;{elseif $new_width_val=="25" || $new_width_val=="20"}height:360px;{else}height:465px;{/if}">
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

        

        {assign var="is_ngo" value="$product.company_id|fn_check_merchant_for_ngo}
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

        {include file="common_templates/product_data.tpl" obj_prefix='' hide_enctype=true}
        {assign var="form_open" value="form_open_`$product.product_id`"}
        {$smarty.capture.$form_open}
        <span class="nl_add_wish_list" id="cart_buttons_block_{$product.product_id}">
            {include file="addons/wishlist/views/wishlist/components/add_to_wishlist.tpl" but_id="button_wishlist_`$product.product_id`" but_name="dispatch[wishlist.add..`$product.product_id`]" but_role="text"}
        </span>
        {assign var="form_close" value="form_close_`$product.product_id`"}
        {$smarty.capture.$form_close}
        <a class="googleShare" onclick="googleShare({$product.product_id})"></a>
        <a class="facebookShare" onclick="fbShare({$product.product_id})"></a>

        {* code for facebook share, google share and wishlist ends here*}
    </div>
    <div class="bottom"></div>
</div>
<div class="clearboth"></div>
{* code for New style for sold out, wishlist and google and facebook share ends here *}
{else}
{if $product.product_amount_available == 0}<div class="sold_out_text_pic">{$lang.sold_out_feature}</div>{/if}
{/if}

<a href="{"products.view?product_id=`$product.product_id`"|fn_url}" id ="{$product.product_id}" class="box_metacategory_image {if !$config.fb_user_engagement && $product.product_amount_available == 0}sold_out_category{/if}" >
{if $controller=='categories' and $mode=='view'}
     {assign var="pro_image" value=$product.product_id|fn_get_image_pairs:'product':'M'}
     {include file="common_templates/image.tpl" obj_id=$obj_id_prefix images=$pro_image object_type="product" show_thumbnail="Y" image_width="320" image_height="320" no_height=true }
     <!--<img class="src2srconscroll" src="{$config.http_path}/blank.gif" src2="http://cdn.shopclues.com/{$pro_image.detailed.http_image_path}" alt="{$product.product}" title="{$product.product}" />-->
{else}
 {include file="common_templates/image.tpl" obj_id=$obj_id_prefix images=$product.main_pair object_type="product" show_thumbnail="Y"  image_width="320" image_height="320" no_height=true }
{/if}

</a>

    {if $product.is_wholesale_product && $config.wholesale_icon}
        <div class="clearboth"></div>
        <div class="icon_wholesale"></div>
    {/if}

   {if $item_number == "Y"}
   {$cur_number}.&nbsp;{math equation="num + 1" num=$cur_number assign="cur_number"}
   {/if}
   {if $product.is_wholesale_product == 0}<a href="{"products.view?product_id=`$product.product_id`"|fn_url}" class="box_metacategory_name" alt="{$product.product}" title="{$product.product}">{/if}
    <div class="whole_sale_lot_name">{$product.product}</div>
    {if $product.is_wholesale_product == 0}</a>{/if}



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
<div class="metacategory_pricing">


{if $product.is_wholesale_product == 1}
<span class="whole_sale_min_qty">
    <ul>
        <li>{$lang.lot_size}</li>
        <li>{$product.min_qty}</li>
    </ul>
</span>
{/if}

{if $after_apply_promotion !=0}
    {math assign="lot_price" equation="p*q" p=$product.price q=$product.min_qty}
    
    <span class="whole_sale_min_qty">{$lang.total_lot_price}:{$lot_price}</span>
    <span class="box_metacategory_price" style="margin:0;">{$product.price|format_price:$currencies.$secondary_currency:""}</span>
    {if $product.price_see_inside ==0}
        <span class="box_metacategory_priceoffer_mobile" style="color: #900; position: absolute; left:0; bottom:18px; font:bo ld 16px trebuchet ms; margin-top: -3px; float: left;">{if $product.is_wholesale_product == 1}{$lang.price_per_unit}{/if}{$after_apply_promotion|format_price:$currencies.$secondary_currency:""}</span>

    {else}
        <span class="grid_blue_deal_nl"><a style="color:#fff!important;" href="{"products.view?product_id=`$product.product_id`"|fn_url}">{$lang.click_to_see_inside}</a></span>
    {/if}
{else}
    {math assign="lot_price" equation="p*q" p=$product.price q=$product.min_qty}
    
    <label class="whole_sale_lot_prc"><span class="whole_sale_lot_size">{$lang.total_lot_price}{$lot_price}</span>
    <span class="prc_offer">{if $product.is_wholesale_product == 1}{$lang.price_per_unit}{/if}{$product.price|format_price:$currencies.$secondary_currency:""}</span>
     </label>
    
    
{/if}
</div>
<a class="whole_sale_btn" href="{"products.view?product_id=`$product.product_id`"|fn_url}"></a>
</div>
{/if}
{/foreach}
{/foreach}
<div class="topBar"><span class="pageNum">Page 2</span><span class="loader"><img src="{$config.ext_images_host}/images/skin/ajax-loader.gif"><span>Loading more results.....</span></span></div>
{/if}
</div>

<div class="pagination-end"></div>

{if !$no_pagination}
    {include file="common_templates/pagination.tpl"}
{/if}

{capture name="mainbox_title"}{$title}{/capture}
