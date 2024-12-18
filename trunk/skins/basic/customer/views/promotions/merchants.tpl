{literal}<script>
$(document).ready(function(){
	$('.central-column').addClass('categories_full');
});
</script>{/literal}

{assign var="type_type" value="page_title_type_"|cat:$smarty.request.type_id}
{assign var="desc_type" value="page_desc_type_"|cat:$smarty.request.type_id}

{$lang.$type_type}
<br>
{$lang.$desc_type}
<br>

{if $promo_product}

{foreach from=$promo_product item="products" name="promo" key=keys}
<div class="clear"></div>
<div class="block_metacategory " style="clear:both;"><h1 class="block_metacategory_heading">{$keys|fn_get_category_name}</h1></div>
<ul class="jcarousel-skin-ie7">
    {foreach from=$products item="product" name="productlisting"}

     <li style="float: left; list-style: none; padding:10px">   
			<div id="box_metacategory" class="box_metacategory" >
			{assign var="is_new" value=$product|check_product_for_new}
			{if $is_new == 'new'}
				<div class="box_metacategory_labelnew"></div> 
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
    
    {if $smarty.request.category_id != $lang.48hrsale_category_id && $smarty.request.category_id != $lang.24hrsale_category_id}
        <div id="line_prc_discount_value_{$obj_prefix}{$obj_id}" class="box_metacategory_discount {if $after_apply_promotion !=0} third_price_discount {/if}">
		<span id="prc_discount_value_label_{$obj_prefix}{$obj_id}"  class="box_metacategory_discount_number">
        {$disc_perc}%
        </span>
		<span class="box_metacategory_discount_off">Off</span>
		</div> 
        
    {/if}
{/if} 
            
			<a href="{"products.view?product_id=`$product.product_id`"|fn_url}" class="box_metacategory_image" onClick="_gaq.push(['_trackEvent', 'Category', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.productlisting.iteration}']);">
			{assign var="pro_images" value=$product.product_id|fn_get_image_pairs:'product':'M'}
			{include file="common_templates/image.tpl" image_width="160" image_height="160" obj_id=$obj_id_prefix images=$pro_images object_type="product" show_thumbnail="Y"}
			</a>
            
            <a href="{"products.view?product_id=`$product.product_id`"|fn_url}" class="box_metacategory_name" onClick="_gaq.push(['_trackEvent', 'Category', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.productlisting.iteration}']);" alt="{$product.product}" title="{$product.product}">
			{$product.product|truncate:40:"...."}
			</a>
			
			<div class="box_metacategory_rating">

               {assign var="average_rating" value=$product.product_id|fn_get_average_rating:'P'}
                {if $average_rating}
                	{include file="addons/discussion/views/discussion/components/top_banner_stars.tpl" stars=$average_rating|fn_get_discussion_rating}
              	{/if}
			</div>
			{if $block.properties.show_key_feature == 'Y'}
				{assign var="key_features" value=$product.product_id|get_products_feature}
				{if $key_features|count > "0"}
					<ul class="box_metacategory_features">
						{foreach from=$key_features item="key_feature"}
						<li>{$key_feature.variant|truncate:20:"...."}</li>				
						{/foreach}
					</ul>
				{else}
                	<div style=" float: left; height: 55px; margin-left: 30px; margin-top: 5px; overflow: hidden; width: 83%;"></div>
                {/if}
			{/if}
			<div class="clearboth"></div>
			
			<div class="box_metacategory_pricing">
			{if $product.list_price > $product.price}
			<span class="box_metacategory_price">MRP: {$product.list_price|format_price:$currencies.$secondary_currency:""}</span>
			{/if}
			{assign var="after_apply_promotion" value=0}
			{assign var="price_see_inside" value=0}
			{assign var="deal_inside_badge" value=0}
			{assign var="special_offer_badge" value=0}
			{if $product.promotion_id !=0}
				{assign var="after_apply_promotion" value=$product|fn_get_3rd_price}
			{/if}
			{if $after_apply_promotion !=0}						
				{if $product.deal_inside_badge ==1}
				<a href="{"products.view?product_id=`$product.product_id`"|fn_url}"><img src="{$config.deal_inside_badge_url}" /></a>
				{/if}
			{/if}		
								
			{if $after_apply_promotion !=0}						
				{if $product.special_offer_badge ==1}
					<a href="{"products.view?product_id=`$product.product_id`"|fn_url}"><img src="{$config.special_offer_badge_url}" /></a>
				{/if}	
			{/if}
			{assign var="after_apply_promotion" value=0}
			{assign var="price_see_inside" value=0}
			{if $product.promotion_id !=0}
				{assign var="after_apply_promotion" value=$product|fn_get_3rd_price}
			{/if}
			{if $after_apply_promotion !=0}
				<span class="box_metacategory_priceoffer" style="color: #444444; display: block; font: 12px trebuchet ms,Geneva,sans-serif; text-decoration:line-through; top:21px;">{$product.price|format_price:$currencies.$secondary_currency:""}</span>
                
				{if $product.price_see_inside ==0}
					<span class="box_metacategory_priceoffer" style="clear:both;">{$after_apply_promotion|format_price:$currencies.$secondary_currency:""}</span>
                    
				{else}
					<span class="meta_blue_deal_nl"><a style="color:#fff!important;" href="{"products.view?product_id=`$product.product_id`"|fn_url}">{$lang.click_to_see_inside}</a></span>
				{/if}
			{else}
				<span class="box_metacategory_priceoffer">{$product.price|format_price:$currencies.$secondary_currency:""}</span>
                 {if $product.price_see_inside =="1" && (isset($product.special_offer_text) && !empty($product.special_offer_text)) }
    <span class="meta_blue_deal_nl"><a style="color:#fff!important;" href="{"products.view?product_id=`$product.product_id`"|fn_url}">{$lang.click_to_see_inside}</a></span>
    			{/if}
                
			{/if}
  	
                            {if $product.freebee_inside =="1"}
	                            <a class="cate_free_gft_nl" href="{"products.view?product_id=`$product.product_id`"|fn_url}" onClick="_gaq.push(['_trackEvent', 'Homepage', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.topproductlisting.iteration}']);"><img src="{$config.freebee_inside_image_url}" /></a> 
                            {/if}
                           		
                            {if $product.deal_inside_badge =="1"}
                              <a class="cate_deal_inside_nl" href="{"products.view?product_id=`$product.product_id`"|fn_url}" onClick="_gaq.push(['_trackEvent', 'Homepage', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.topproductlisting.iteration}']);"><img src="{$config.cashback_inside_image_url}" /></a>
                            {/if}
                            
                           
                            {if $product.special_offer_badge =="1" || (isset($product.special_offer_text) && !empty($product.special_offer_text) && $product.price_see_inside !="1") }
                             <span  class="nl_red_icon_spl_offer_tag" style="clear:both; margin-left:0;bottom:-2px ;text-align: center;
width: 72px; position:absolute;  left:0;"><a class="nl_prc_red_icon_spl" href="{"products.view?product_id=`$product.product_id`"|fn_url}" onClick="_gaq.push(['_trackEvent', 'Homepage', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.topproductlisting.iteration}']);">{$lang.see_special_offer_inside}</a></span>  
                             
                             {elseif $product.price_see_inside =="1" && $product.special_offer_badge =="1" }
    <span  class="nl_red_icon_spl_offer_tag" style="clear:both; margin-left:0; position:absolute; text-align: center;
width:72px; bottom:-2px ; left:0;"><a class="nl_prc_red_icon_spl" href="{"products.view?product_id=`$product.product_id`"|fn_url}" onClick="_gaq.push(['_trackEvent', 'Homepage', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.topproductlisting.iteration}']);">{$lang.see_special_offer_inside}</a></span> 
    
                            {/if}
			</div>
		{assign var="image_cat_id" value=$product.category_ids|fn_get_category_image}
		{assign var="image_cat" value="-"|explode:$image_cat_id}
			{if $image_cat.1 !=''}
				{if $image_cat.0 > 0}
				<div class="cate_icon_nl">
				<img src="{$image_cat.1}">
			  	<div class="label_cate_image" style="display:none;">{$lang.cate_image_hover}</div> </div>
				{/if}
			{/if}
			</div>            
      </li>   
    {/foreach}
    </ul>
{/foreach}
{else}
	{assign var="not_found" value="page_notfound_type_"|cat:$smarty.request.type_id}
	{$lang.$not_found}
{/if}
