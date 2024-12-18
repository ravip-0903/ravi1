<h1 class="cat_title" style="margin-top:5px !important;">{$lang.bestdellingproduct_heading_nl}</h1>
<!--Hot Deals -->
<div class="box_manualdeals" style="width:100%;">
<!--Product slider -->
<div class="slider_manualdeals jcarousel-skin-tango">
{foreach from=$products item="sproducts" key=k}
    {*assign var="category_details" value=$k|fn_get_category_name*}
    {assign var="deal_title" value=$k|get_deal_title:'BESTSELLING'}
    {if $sproducts|count}
    <!-- Heading -->

          <h1 class="block-packs-title">
          <span class="float_left">
          {if $deal_title != ''}{$lang.bestseller_cat_heading} {$deal_title}{else}{$lang.featured_deal_title_not_found}{/if}
          </span>
          </h1>

<!-- End Heading -->
 <div class="clearboth"></div>
    <ul id="hotdeals_{$k}">
    {foreach from=$sproducts item="product"}
      <li>
            <div class="box_GridProduct" style="margin-left:0px; margin-top:15px;">
            {assign var="is_new" value=$product|check_product_for_new}
            {if $config.quick_view_show}
            <div id="prod{$product.product_id}" rev="index.php?dispatch=product_quick_view.view&product_id={$product.product_id}" onclick="quick_view_click({$product.product_id});" class="ql_icon_blk"></div>
            {/if}
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

                {if $product.discount || $product.list_discount_prc || ($after_apply_promotion!=0)}

                        {if $product.promotions}
                            {assign var="disc_perc" value=$product|calculate_discount_perc}
                        {else}
                            {if $product.discount}
                              {assign var="disc_perc" value=$product.discount}
                            {else}
                              {assign var="disc_perc" value=$product.list_discount_prc}
                            {/if}
                        {/if}


            <div class="box_GridProduct_discountlabel {if $after_apply_promotion !=0} third_price_discount {/if}">

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


                        <div id="line_prc_discount_value_{$obj_prefix}{$product.product_id}" class="box_metacategory_discount ">
                        <span id="prc_discount_value_label_{$obj_prefix}{$product.product_id}" style="float:left; margin-top:5px; margin-left:3px; width:90%; text-align:center;" class="box_metacategory_discount_number">
                        {$disc_perc}%
                        </span>
                        <span style="float:left; font:bold 11px arial; margin-top:-4px; margin-left:10px;" class="box_metacategory_discount_off">Off</span>
                        </div>
                {/if}
            </div>

               {if $config.fb_user_engagement}
                {*Wishlist*}
                <div class="box_GridProduct_wishList" style="top:126px;">
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
                {*End Wishlist*}
                {/if}

             <a href="{"products.view?product_id=`$product.product_id`"|fn_url}" id="{$product.product_id}" class="box_GridProduct_product">
             {assign var="pro_images" value=$product.product_id|fn_get_image_pairs:'product':'M'}
    {include file="common_templates/image.tpl" image_width="160" image_height="160" obj_id=$obj_id_prefix images=$pro_images object_type="product" show_thumbnail="Y"}
            </a>
            <a href="{"products.view?product_id=`$product.product_id`"|fn_url}" id="{$product.product_id}"  class="box_GridProduct_link" alt="{$product.product}" title="{$product.product}"  >{$product.product|truncate:50:"...."}</a>
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
                        <span class="box_GridProduct_priceoffer" style="clear:both;">{$after_apply_promotion|format_price:$currencies.$secondary_currency:""}</span>

                    {else}
                        <span class="deals_blue_deal_nl"><a style="color:#fff!important;" href="{"products.view?product_id=`$product.product_id`"|fn_url}">{$lang.click_to_see_inside}</a></span>
                    {/if}
                {else}
                    <span class="box_GridProduct_priceoffer">{$product.price|format_price:$currencies.$secondary_currency:""}</span>
                     {if $product.price_see_inside =="1" && (isset($product.special_offer_text) && !empty($product.special_offer_text)) }
        <span class="deals_blue_deal_nl"><a style="color:#fff!important;" href="{"products.view?product_id=`$product.product_id`"|fn_url}">{$lang.click_to_see_inside}</a></span>
                    {/if}

                {/if}

            {if $product.freebee_inside =="1"}
            <a class="deals_free_gft_grn_nl" href="{"products.view?product_id=`$product.product_id`"|fn_url}" onClick="_gaq.push(['_trackEvent', 'Homepage', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.topproductlisting.iteration}']);"><img src="{$config.freebee_inside_image_url}" /></a>
            {/if}

            {if $product.deal_inside_badge =="1"}
              <a class="deals_deal_inside_red_nl" href="{"products.view?product_id=`$product.product_id`"|fn_url}" onClick="_gaq.push(['_trackEvent', 'Homepage', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.topproductlisting.iteration}']);"><img src="{$config.cashback_inside_image_url}" /></a>
            {/if}


            {if $product.special_offer_badge =="1" || (isset($product.special_offer_text) && !empty($product.special_offer_text) && $product.price_see_inside !="1") }
            <span  class="nl_red_icon_spl_offer_tag deals_page_nl_mar_lef" ><a class="nl_prc_red_icon_spl" href="{"products.view?product_id=`$product.product_id`"|fn_url}" onClick="_gaq.push(['_trackEvent', 'Homepage', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.topproductlisting.iteration}']);">{$lang.see_special_offer_inside}</a></span>

             {elseif $product.price_see_inside =="1" && $product.special_offer_badge =="1" }
<span  class="nl_red_icon_spl_offer_tag deals_page_nl_mar_lef"><a class="nl_prc_red_icon_spl" href="{"products.view?product_id=`$product.product_id`"|fn_url}" onClick="_gaq.push(['_trackEvent', 'Homepage', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.topproductlisting.iteration}']);">{$lang.see_special_offer_inside}</a></span>

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
        </li>
    {/foreach}
    </ul>
	{if $config.isResponsive}
    <div class="mobile-arrow jcarousel-prev jcarousel-prev-horizontal jcarousel-prev-disabled jcarousel-prev-disabled-horizontal" disabled="true" style="display: block; top: 170px;"></div>
    <div class="mobile-arrow jcarousel-next jcarousel-next-horizontal jcarousel-next-disabled jcarousel-next-disabled-horizontal" disabled="true" style="display: block; top: 170px;"></div>
{/if}
     <script type="text/javascript">

        </script>
    <div class="clearboth height_ten"></div>
    {/if}
{literal}

                <script type="text/javascript">
		    if({/literal}{$config.isResponsive}{literal} && $(window).width()<801){

			    jQuery_1_10_2("#hotdeals_{/literal}{$k}{literal}").owlCarousel(optionsOwl);

		            jQuery_1_10_2("#hotdeals_{/literal}{$k}{literal}").parent().find(".jcarousel-next").click(function(){
		                var owl = jQuery_1_10_2(this).parent().find("#hotdeals_{/literal}{$k}{literal}");
		                owl.trigger('owl.next');
		            });
		            jQuery_1_10_2("#hotdeals_{/literal}{$k}{literal}").parent().find(".jcarousel-prev").click(function(){
		                var owl = jQuery_1_10_2(this).parent().find("#hotdeals_{/literal}{$k}{literal}");
		                owl.trigger('owl.prev');
		            });

                    }
                    else{
                        $("#hotdeals_{/literal}{$k}{literal}").jcarousel({
                            size: {/literal}{$sproducts|count}{literal},
                            scroll:1,
                            item_count: {/literal}{$sproducts|sizeof}{literal}
                        });
                    }

                </script>
{/literal}
{/foreach}
</div>


</div>
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