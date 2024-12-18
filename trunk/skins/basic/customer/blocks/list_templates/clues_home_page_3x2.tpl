{* $Id: grid_list.tpl 11191 2010-11-11 11:56:01Z klerik $ *}
{** block-description:clues_home_page_3x2 **}
<div class="box_manualdeals" style="width:100%;">
<!--Product slider -->

{assign var="flag" value=0}
<div class="slider_manualdeals">
    <ul class="jcarousel-skin-tango">

		{foreach from=$items key="key" item="product" name="productlisting"}
                    {if (isset($key))}
                      {assign var="flag" value=$flag+1}
                      {if $flag < '7'}
                      
                        
                   
			<li class="slider_block {if $flag % 3=='0'}last_prd_img{/if}" style="{if $block.properties.number_of_products_to_show == "3"} margin-right:47px; {/if} 
            {if $smarty.foreach.productlisting.iteration == $smarty.foreach.productlisting.last} border-right:none; {/if}">
            	<div class="box_GridProduct" style="margin-left:0px; margin-top:15px;">
				{assign var="is_new" value=$product|check_product_for_new}
			   {if $is_new == 'new'}	
				<div class="box_GridProduct_newlabel">{$lang.ngo_popup_hover}</div> 
			   {/if}
				{assign var="is_ngo" value="$product.company_id|fn_check_merchant_for_ngo}
			   {if $is_ngo == 'Y'}
				<div class="box_GridProduct_ngolabel">{$lang.ngo_popup_hover}</div> 
			   {/if}
               
            {assign var="after_apply_promotion" value=0}
            {if $product.promotion_id !=0}
                 {assign var="after_apply_promotion" value=$product|fn_get_3rd_price}
            {/if}     
             <!--Modified by clues dev-->
            {if $product.discount || $product.list_discount_prc || ($after_apply_promotion!=0)}
                {if $product.discount}
                  {assign var="disc_perc" value=$product.discount_prc}
                {else}
                  {assign var="disc_perc" value=$product.list_discount_prc}
                {/if}

                
                {if $product.promotion_id !=0}
                   
                    {if $after_apply_promotion !=0}			
                        {assign var="disc_perc" value=$product|calculate_3rd_price_percentage:$after_apply_promotion}
                    {/if}
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

    

		
                {if $disc_perc>=50}
                  {assign var="styles" value="label_discount_grid_first"}
                {elseif $disc_perc>=0 and $disc_perc<=49}
                  {assign var="styles" value="label_discount_grid_second"}
                {/if}
                
               
            
                <div id="line_prc_discount_value_{$obj_prefix}{$product.product_id}" class="box_GridProduct_discountlabel {if $after_apply_promotion !=0} third_price_discount {/if} {if $product.product_amount_available == 0}sold_out_category{/if}">
                <span id="prc_discount_value_label_{$obj_prefix}{$product.product_id}" style="float:left; margin-top:5px; margin-left:3px; width:90%; text-align:center;">{$disc_perc}%</span>
                <span style="float:left; font:bold 11px arial; margin-top:-4px; margin-left:10px;">Off</span>
                </div> 
            
               {/if} 


        

		{assign var="image_cat_id" value=$product.category_ids|fn_get_category_image}
		{assign var="image_cat" value="-"|explode:$image_cat_id}
			{if $image_cat.1}
				{if $image_cat.0 > 0}
				<div class="cate_icon" id="cate_icon">
				<img src="{$image_cat.1}">
			  	<div class="label_cate_image" style="display:none;">{$lang.cate_image_hover}</div> </div>
				{/if}
			{/if}
       {if $product.product_amount_available =='0'}<div class="sold_out_text_pic home_page_sold_out">{$lang.sold_out_feature}</div>{/if}
       		<a href="{"products.view?product_id=`$product.product_id`"|fn_url}" class="box_GridProduct_product {if $product.product_amount_available == 0}sold_out_category{/if}"  onClick="_gaq.push(['_trackEvent', 'Homepage', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.productlisting.iteration}']);">
				 {assign var="pro_images" value=$product.product_id|fn_get_image_pairs:'product':'M'}
		<img src="http://cdn.shopclues.com/images/banners/blank.gif" src2="http://cdn.shopclues.com/{$pro_images.detailed.http_image_path}" alt="{$product.product}" title="{$product.product}" class="src2srconscroll" />
				</a>
				<a href="{"products.view?product_id=`$product.product_id`"|fn_url}" class="box_GridProduct_link" alt="{$product.product}" title="{$product.product}"   onClick="_gaq.push(['_trackEvent', 'Homepage', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.productlisting.iteration}']);">{$product.product|truncate:50:"...."}</a>
                
                
               
                <div class="box_GridProduct_starrating">
                {assign var="average_rating" value=$product.product_id|fn_get_average_rating:'P'}

                    {if $average_rating}
                        {include file="addons/discussion/views/discussion/components/top_banner_stars.tpl" stars=$average_rating|fn_get_discussion_rating}
                    {/if}
                </div>
                          
				<div class="box_GridProduct_pricing">
				{if $product.list_price > $product.price}
				<span class="box_GridProduct_price">{$product.list_price|format_price:$currencies.$secondary_currency:""}</span>
				{/if}
                
		{assign var="after_apply_promotion" value=0}
		{assign var="price_see_inside" value=0}
		{if $product.promotion_id !=0}
			{assign var="after_apply_promotion" value=$product|fn_get_3rd_price}
		{/if}

			{if $after_apply_promotion !=0}
				
                <span class="box_GridProduct_price">{$product.price|format_price:$currencies.$secondary_currency:""}</span>
                
				{if $product.price_see_inside ==0}
                	<span class="box_GridProduct_priceoffer">{$after_apply_promotion|format_price:$currencies.$secondary_currency:""}</span>
              
				{else}
                 <div class="clearboth"></div>
					<span class="home_blue_deal_nl"><a style="color:#fff!important;" href="{"products.view?product_id=`$product.product_id`"|fn_url}"  onClick="_gaq.push(['_trackEvent', 'Homepage', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.productlisting.iteration}']);">{$lang.click_to_see_inside}</a></span>
				{/if}
					
			{else}
           
				<span class="box_GridProduct_priceoffer">{$product.price|format_price:$currencies.$secondary_currency:""}</span>
                 {if $product.price_see_inside =="1" && (isset($product.special_offer_text) && !empty($product.special_offer_text)) }
                  <div class="clearboth"></div>
    <span class="home_blue_deal_nl"><a style="color:#fff!important;" href="{"products.view?product_id=`$product.product_id`"|fn_url}"  onClick="_gaq.push(['_trackEvent', 'Homepage', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.productlisting.iteration}']);">{$lang.click_to_see_inside}</a></span>
    			{/if}
			{/if}	
   	
                            {if $product.freebee_inside =="1"}
                             <a class="home_free_gft_nl" href="{"products.view?product_id=`$product.product_id`"|fn_url}" onClick="_gaq.push(['_trackEvent', 'Homepage', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.topproductlisting.iteration}']);"><img src="{$config.freebee_inside_image_url}" /></a> 
                            {/if}
                           		
                            {if $product.deal_inside_badge =="1"}
                              <a class="home_deal_inside_nl" href="{"products.view?product_id=`$product.product_id`"|fn_url}" onClick="_gaq.push(['_trackEvent', 'Homepage', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.topproductlisting.iteration}']);"><img src="{$config.cashback_inside_image_url}" /></a>
                            {/if}
                          
                            {if $product.special_offer_badge =="1" || (isset($product.special_offer_text) && !empty($product.special_offer_text) && $product.price_see_inside !="1") }
                             <div class="clearboth"></div>
                             <span  class="nl_red_icon_spl_offer_tag home_deals_org_txt"><a class="nl_prc_red_icon_spl" href="{"products.view?product_id=`$product.product_id`"|fn_url}" onClick="_gaq.push(['_trackEvent', 'Homepage', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.topproductlisting.iteration}']);">{$lang.see_special_offer_inside}</a></span> 
                             
                             {elseif $product.price_see_inside =="1" && $product.special_offer_badge =="1" }
                              <div class="clearboth"></div>
    <span  class="nl_red_icon_spl_offer_tag home_deals_org_txt"><a class="nl_prc_red_icon_spl" href="{"products.view?product_id=`$product.product_id`"|fn_url}" onClick="_gaq.push(['_trackEvent', 'Homepage', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.topproductlisting.iteration}']);">{$lang.see_special_offer_inside}</a></span>
    
                            {/if}
                          
				</div>
			    </div>
			</li> 
                        {/if}
                        {/if}
		{/foreach}
    </ul>
	<div class="clearboth height_ten"></div>
</div>

</div>
