{* $Id: products_grid.tpl 11222 2010-11-16 11:53:42Z klerik $ *}
<h2 style="background:none; padding:0; line-height:30px;font-size: 22px;
color: #EE811D; border-bottom: 1px solid #DDD; font-weight: normal; margin: 0 0 15px; font-weight: bold;" class="main_heading">{$promo_type.type}
</h2>
<img src="{$config.ext_images_host}/images/mpromotion/banners/{$promo_type.file_bannerpath}" style="padding-bottom:10px;"/>
{if $promo_data}

{script src="js/exceptions.js"}


{if !$no_pagination}
	{include file="common_templates/pagination.tpl"}
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
{foreach from=$promo_data item="products" name="promo" key=keys}
<div class="clear"></div>
<div class="block_metacategory " style="clear:both;"><h1 class="block_metacategory_heading">{$keys|fn_get_category_name}</h1></div>

{foreach from=$products item="product" name="sproducts" key="k"}

{assign var="obj_id" value=$product.product_id}
{assign var="obj_id_prefix" value="`$obj_prefix``$product.product_id`"}
{include file="common_templates/product_data.tpl" product=$product}

<div class="box_metacategory box_GridProduct" style="margin-top:10px; margin-left:5px; padding-left:5px; {if $category_data.show_feature=='Y'}height:360px;{elseif $controller=="products" && $mode=="search" && $config.key_feature_on_search }height:360px;{/if}">
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
    
 

     {*{if $smarty.request.category_id != $lang.48hrsale_category_id}*}
      
        <div id="line_prc_discount_value_{$obj_prefix}{$product.product_id}" class="box_metacategory_discount {if $after_apply_promotion !=0} third_price_discount {/if}">
		<span id="prc_discount_value_label_{$obj_prefix}{$product.product_id}"  class="box_metacategory_discount_number">
        {$disc_perc}%

        </span>
		<span class="box_metacategory_discount_off">Off</span>
		</div> 
  {*{/if}*}
{/if} 
<a href="{"products.view?product_id=`$product.product_id`"|fn_url}" class="box_metacategory_image ">

     {assign var="pro_image" value=$product.product_id|fn_get_image_pairs:'product':'M'}
     {include file="common_templates/image.tpl" obj_id=$obj_id_prefix images=$pro_image object_type="product" show_thumbnail="Y" image_width="160" image_height="160"}

</a>

   {if $item_number == "Y"}
   {$cur_number}.&nbsp;{math equation="num + 1" num=$cur_number assign="cur_number"}
   {/if}
   <a href="{"products.view?product_id=`$product.product_id`"|fn_url}" class="box_metacategory_name" alt="{$product.product}" title="{$product.product}" >
    {$product.product|truncate:40:"...."} 
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

		<span style="color: #900; position: absolute; left:0; bottom:18px; font:bold 16px trebuchet ms; margin-top: -3px; float: left;">{$after_apply_promotion|format_price:$currencies.$secondary_currency:""}</span>
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
{/foreach}
<div class="clearboth"></div>




{if !$no_pagination}
	{include file="common_templates/pagination.tpl"}
{/if}
{else}
<div style="padding:10px 0px; text-align:center;">{$lang.no_product_for_this_promotion}</div>
{/if}

{capture name="mainbox_title"}{$title}{/capture}


