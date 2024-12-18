{* $Id: grid_list.tpl 11191 2010-11-11 11:56:01Z klerik $ *}
{** block-description:clues_wrapper_3x2 **}
<div class="grid_3">
<!-- <a href="#" class="block_metacategory_left"></a> -->


<ul class="jcarousel-skin-ie7">

    {foreach from=$items key="key" item="product" name="productlisting"}
	{if $key < '6'}
     	<li>   
			<div id="box_metacategory" class="box_metacategory" style="{if $block.properties.show_key_feature=='Y'}height:340px;{/if}">

			{if !isset($block.properties.show_key_feature) || $block.properties.show_key_feature == 'N'}
				<input type="hidden" name="cus_block_{$block.block_id}" value="210" id="cus_block_{$block.block_id}"/>
            {else}
            	<input type="hidden" name="cus_block_{$block.block_id}" value="270" id="cus_block_{$block.block_id}"/>
			{/if}
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

    {if $smarty.request.category_id != $lang.48hrsale_category_id &&  $smarty.request.category_id != $lang.24hrsale_category_id}
        <div id="line_prc_discount_value_{$obj_prefix}{$product.product_id}" class="box_metacategory_discount {if $after_apply_promotion !=0} third_price_discount {/if}{if $product.product_amount_available == 0}sold_out_category{/if}">
		<span id="prc_discount_value_label_{$obj_prefix}{$product.product_id}"  class="box_metacategory_discount_number">
        {$disc_perc}%
        </span>
		<span class="box_metacategory_discount_off">Off</span>
		</div> 
        
    {/if}
{/if} 
           
			

            {if $product.product_amount_available == 0}<div class="sold_out_text_pic meta_cate_nl">{$lang.sold_out_feature}</div>{/if}
                        
		<a href="{"products.view?product_id=`$product.product_id`"|fn_url}" class="box_metacategory_image {if $product.product_amount_available== 0} sold_out_category{/if}" onClick="_gaq.push(['_trackEvent', 'Category', 'Click', '{$lang.CP}-{$block.description|replace:" ":"_"}_{$smarty.foreach.productlisting.iteration}']);">
			{assign var="pro_images" value=$product.product_id|fn_get_image_pairs:'product':'M'}
			<img src="http://cdn.shopclues.com/images/banners/blank.gif" src2="http://cdn.shopclues.com/{$pro_images.detailed.http_image_path}" alt="{$product.product}" title="{$product.product}" class="src2srconscroll" />
			</a>
            
            <a href="{"products.view?product_id=`$product.product_id`"|fn_url}" class="box_metacategory_name" onClick="_gaq.push(['_trackEvent', 'Category', 'Click', '{$lang.CP}-{$block.description|replace:" ":"_"}_{$smarty.foreach.productlisting.iteration}']);" alt="{$product.product}" title="{$product.product}">
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
                             <span class="nl_red_icon_spl_offer_tag" style="clear:both; margin-left:0;bottom:-2px; text-align: center;
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
	{/if}
    {/foreach}
    </ul>
</div>
