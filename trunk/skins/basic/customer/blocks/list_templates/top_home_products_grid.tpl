{foreach from=$products item="product" name="topproductlisting"}
{assign var="is_new" value=$product|check_product_for_new}

<div class="product_homepagedealblock"  style="{if $smarty.foreach.topproductlisting.iteration == $smarty.foreach.topproductlisting.last} border-right:none; {/if}">

       {assign var="image_cat_id" value=$product.category_ids|fn_get_category_image}
		{assign var="image_cat" value="-"|explode:$image_cat_id}
			{if $image_cat.1 !=''}
				{if $image_cat.0 > 0}
				<div class="cate_icon_nl">
				<img src="{$image_cat.1}">
			  	<div class="label_cate_image" style="display:none;">{$lang.cate_image_hover}</div> </div>
				{/if}
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
   
        <div id="line_prc_discount_value_{$obj_prefix}{$product.product_id}" class="product_homepagedealblock_discountlabel {if $after_apply_promotion !=0} third_price  {/if}">
<span id="prc_discount_value_label_{$obj_prefix}{$product.product_id}" class="product_homepagedealblock_discountlabel_discount">{$disc_perc}%</span>
<span class="product_homepagedealblock_discountlabel_off">Off</span>
</div>
    {/if}
{/if} 



{assign var="after_apply_promotion" value=0}
{assign var="price_see_inside" value=0}
{if $product.promotion_id !=0}
	{assign var="after_apply_promotion" value=$product|fn_get_3rd_price}
{/if}

<a href="{"products.view?product_id=`$product.product_id`"|fn_url}" class="product_homepagedealblock_image" onClick="_gaq.push(['_trackEvent', 'Homepage', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.topproductlisting.iteration}']);">

{assign var="pro_images" value=$product.product_id|fn_get_image_pairs:'product':'M'}
{include file="common_templates/image.tpl" image_width="182" image_height="123" obj_id=$obj_id_prefix images=$pro_images object_type="product" show_thumbnail="Y"}
</a>
 
<a href="{"products.view?product_id=`$product.product_id`"|fn_url}" class="product_homepagedealblock_name" onClick="_gaq.push(['_trackEvent', 'Homepage', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.topproductlisting.iteration}']);">
{$product.product|truncate:50:"...."}
</a>

<div class="product_homepagedealblock_starrating">
{assign var="average_rating" value=$product.product_id|fn_get_average_rating:'P'}
{if $average_rating}
{include file="addons/discussion/views/discussion/components/top_banner_stars.tpl" stars=$average_rating|fn_get_discussion_rating}
{/if}
</div>

<div class="product_homepagedealblock_pricing">
{if $product.list_price > $product.price}
<span class="product_homepagedealblock_pricing_listprice">{$product.list_price|format_price:$currencies.$secondary_currency:""}</span>
{/if}

		
			{if $after_apply_promotion !=0}
                <span class="product_homepagedealblock_pricing_listprice">{$product.price|format_price:$currencies.$secondary_currency:""}</span>
                
				{if $product.price_see_inside ==0}
					<span class="product_homepagedealblock_pricing_offerprice">{$after_apply_promotion|format_price:$currencies.$secondary_currency:""}</span>           
				 {else}
					 <span class="top_home_blue_deal_nl"><a style="color:#fff!important;" href="{"products.view?product_id=`$product.product_id`"|fn_url}" onClick="_gaq.push(['_trackEvent', 'Homepage', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.topproductlisting.iteration}']);">{$lang.click_to_see_inside}</a></span>
				{/if}
			{else}
				<span class="product_homepagedealblock_pricing_offerprice">{$product.price|format_price:$currencies.$secondary_currency:""}</span>
                 {if $product.price_see_inside =="1" && (isset($product.special_offer_text) && !empty($product.special_offer_text)) }
    <span class="top_home_blue_deal_nl"><a style="color:#fff!important;" href="{"products.view?product_id=`$product.product_id`"|fn_url}" onClick="_gaq.push(['_trackEvent', 'Homepage', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.topproductlisting.iteration}']);">{$lang.click_to_see_inside}</a></span>
    			{/if}
			{/if}	


   	
                            {if $product.freebee_inside =="1"}
                             <a class="top_home_free_gft_nl" href="{"products.view?product_id=`$product.product_id`"|fn_url}" onClick="_gaq.push(['_trackEvent', 'Homepage', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.topproductlisting.iteration}']);"><img src="{$config.freebee_inside_image_url}" /></a> 
                            {/if}
                           		
                            {if $product.deal_inside_badge =="1"}
                              <a class="top_home_deal_inside_nl" href="{"products.view?product_id=`$product.product_id`"|fn_url}" onClick="_gaq.push(['_trackEvent', 'Homepage', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.topproductlisting.iteration}']);"><img src="{$config.cashback_inside_image_url}" /></a>
                            {/if}
                            
                            {if $product.special_offer_badge =="1" || (isset($product.special_offer_text) && !empty($product.special_offer_text) && $product.price_see_inside !="1") }
                             <span  class="nl_red_icon_spl_offer_tag"><a class="nl_prc_red_icon_spl" href="{"products.view?product_id=`$product.product_id`"|fn_url}" onClick="_gaq.push(['_trackEvent', 'Homepage', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.topproductlisting.iteration}']);">{$lang.see_special_offer_inside}</a></span> 
                             
                             {elseif $product.price_see_inside =="1" && $product.special_offer_badge =="1" }
    <span  class="nl_red_icon_spl_offer_tag"><a class="nl_prc_red_icon_spl" href="{"products.view?product_id=`$product.product_id`"|fn_url}" onClick="_gaq.push(['_trackEvent', 'Homepage', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.topproductlisting.iteration}']);">{$lang.see_special_offer_inside}</a></span>
    
                            {/if}
                            


</div>

</div>

{/foreach}


<script type="text/javascript">
  //<![CDATA[  
        $("#scroller_{$block.block_id}").jcarousel({$ldelim}
                size: {$products|count},
				scroll:1,
				item_count: {$products|sizeof}
        {$rdelim});  
  //]]>
        </script>
