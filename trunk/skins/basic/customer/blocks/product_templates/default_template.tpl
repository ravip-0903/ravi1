{literal}
<style type="text/css">
    .owl-theme .owl-controls .owl-page{
        display: inline-block;
        zoom: 1;
        *display: inline;/*IE7 life-saver */
    }
    .owl-carousel .clickable{
        bottom:0px;
    }
    .owl-theme .owl-controls .owl-page span{
        display: block;
        width: 12px;
        height: 12px;
        margin: 5px 7px;
        filter: Alpha(Opacity=50);/*IE7 fix*/
        opacity: 0.5;
        -webkit-border-radius: 20px;
        -moz-border-radius: 20px;
        border-radius: 20px;
        background: #869791;
    }

    .owl-theme .owl-controls .owl-page.active span,
    .owl-theme .owl-controls.clickable .owl-page:hover span{
        filter: Alpha(Opacity=100);/*IE7 fix*/
        opacity: 1;
    }

        /* If PaginationNumbers is true */

    .owl-theme .owl-controls .owl-page span.owl-numbers{
        height: auto;
        width: auto;
        color: #FFF;
        padding: 2px 10px;
        font-size: 12px;
        -webkit-border-radius: 30px;
        -moz-border-radius: 30px;
        border-radius: 30px;
    }

        /* preloading images */
    .owl-item.loading{
        min-height: 150px;
        background: url(AjaxLoader.gif) no-repeat center center
    }
.upsell_section{font:10px Verdana, Geneva, sans-serif; margin-top:10px;}
.upsell_section fieldset{border:1px dashed #aaa; background:#fafafa;}
.upsell_product_section{float: left; width:25%; padding: 7px 0 3px 0; margin-left:5px; position:relative;}
.upsell_product_section .prd_plus{position:absolute; right:10px; top:10px; font:bold 30px Verdana, Geneva, sans-serif;}
.upsell_product_section label.lang_var_prd_up{float:left; margin:0px 3px 0 3px ; clear:both;}
.upsell_product_section .upsell_section_image{float:left; }
.upsell_product_section .upsell_section_image img{width:80px; height:80px;}
.upsell_product_section .upsell_section_info{float:left; width:45%; height:46px; position:relative; font:11px/12px "Trebuchet MS", Arial, Helvetica, sans-serif; color:#636566; margin:28px 0 10px 5px;}
/*.strk_price{position:absolute; top:25px;}
.strk_price_prc{position:absolute; top:36px;}*/
.upsell_product_section .upsell_section_info strike{padding:0 3px 0 0;}
.upsell_product_section .upsell_section_info label.name{color:#048ccc; height:26px; display:block; word-wrap:break-word;}
.upsell_product_section .price{font-size:11px;}
.upsell_show{display:block;}
.upsell_hide{display:none;}
.pro_det_add_to_cart_butto span input{margin-left:0!important}
/*********************************************************
/ When clicking on thumbs jqzoom will add the class
/ "zoomThumbActive" on the anchor selected
/*********************************************************/
</style>

<script type="text/javascript">
$(document).ready(function() {

//Added by lijo for TM Single Api call Product Page starts here
function get_tm_data(){

           var product_id = {/literal}{$smarty.request.product_id}{literal};
           var limit = {/literal}{$config.TM_limit_single_api}{literal};
           var prod_tm_blocks = {/literal}"{$config.prod_tm_blocks}"{literal};
           var tm_url= 'http://api.targetingmantra.com/TMWidgets?w='+prod_tm_blocks+'&mid=130915&limit='+limit+'&pid='+product_id+'&json=true&callback=?';


   $.getJSON(tm_url,function(data){

                    window.tm_array = JSON.stringify(data);
                    var data = JSON.parse(tm_array);
                    
                    try
                    {
                        recent_recommend();
                        
                    }
                    catch(err)
                    {
                        

                    }
                    try
                    {
                        vsims();
                        
                    }
                    catch(err)
                    {
                        

                    }
					         try
                    {
                        csims();
                        
                    }
                    catch(err)
                    {
                        

                    }
                        
                    
             })

                .fail(function() {
                      return true;
                    });


            
        }

var single_api_tm = {/literal}{$config.single_api_tm}{literal}; 

    if(single_api_tm) {

        get_tm_data(); 

    }           
//Added by lijo for TM Single Api call Product Page ends here

 $('.jqzoom').live("hover",function(){
  $(this).jqzoom({
//          zoomType: 'standard',
            lens:false,
            preloadImages: true,
            alwaysOn:false,
            preloadText: 'Loading zoom',
            zoomType:'reverse',
            zoomWidth:440,
            zoomHeight:330,
            lens:true,
            title:false
        });
 });
});
</script>
{/literal}
{* $Id: default_template.tpl 12442 2011-05-12 12:45:40Z 2tl $ *}

{script src="js/exceptions.js"}
{if $product.tracking == "O"}
    {assign var="actual_amount" value=$product.inventory_amount}
{else}
    {assign var="actual_amount" value=$product.amount}
{/if}
<div class="product-main-info">
{hook name="products:view_main_info"}
	{if $product}

		{assign var="after_apply_promotion" value=''}
		{if $product.promotion_id !=0}
			{assign var="after_apply_promotion" value=$product|fn_get_3rd_price}
		{/if}
        
	{assign var="obj_id" value=$product.product_id}
	{include file="common_templates/product_data.tpl" product=$product}
        
	{assign var="form_open" value="form_open_`$obj_id`"}
	{$smarty.capture.$form_open}
	<div class="clear margin_top_five">
		
        <!--Modified by clues dev to add new mark on product-->
          {assign var="is_new" value=$product|check_product_for_new}
          {if $is_new == 'new'}
          	<div class="label_new_prodctdetails"></div> 
          {/if}
          {assign var="is_ngo" value="$product.company_id|fn_check_merchant_for_ngo}
          {if $is_ngo == 'Y'}
          	<div class="label_ngo_detail">{$lang.ngo_popup_hover}</div> 
          {/if}
      <!--Modified by clues dev to add new mark on product-->
      {assign var="discount_label" value="discount_label_`$obj_id`"}
                        
            {if $show_discount_label && $smarty.capture.$discount_label|trim}
			<div class="float-left">
			{$smarty.capture.$discount_label}
			</div>
			{/if}

            <!--Added by Sudhir to show category image dt 17 octo 2012-->
		{assign var="image_cat_id" value=$product.category_ids|fn_get_category_image}
		{assign var="image_cat" value="-"|explode:$image_cat_id}
			{if $image_cat.1 !=''}
				{if $image_cat.0 > 0}
				<div class="cate_icon_nl">
				<img src="{$image_cat.1}">
			  	<div class="label_cate_image" style="display:none;">{$lang.cate_image_hover}</div> </div>
				{/if}
			{/if}
            <!--Added by Sudhir to show category image dt 17 octo 2012 end here-->

         <!--code by ankur to show contest icon-->
         {assign var="contest_event" value=$product.product_id|fn_get_contest_data}
         {if !empty($contest_event.contest_icon_url)}
          <div class="contest_icon" style="cursor:pointer; position:absolute; top: 290px; left: 10px;"><img src="{$contest_event.contest_icon_url}" /></div>
          <div class="contest_popup" style="position: fixed; z-index: 200; background-image: url(http://cdn.shopclues.com/images/skin/background_for_banklist.png); left: 0px; top: 0px; width: 100%; min-height: 100%; background-position: initial initial; display:none; background-repeat: initial initial; ">{$contest_event.contest_message}</div>
         {/if}
         
         
         <!--code end-->

	        {if !$no_images}
		{if $config.isResponsive && !$smarty.request.one_day_sale}
		<div class="mobile mobile-slider-cntnr image-border prew_mng float-left center cm-reload-{$product.product_id} img_cntr_new_div" id="product_images_{$product.product_id}_update">
            <div class="mobile-title" itemprop="name">{$product.product|unescape}</div>
	        <div class="mobile-slider">
				{assign var="th_size" value="30"}

                {if $product.main_pair.icon || $product.main_pair.detailed}
                    {assign var="image_pair_var" value=$product.main_pair}
                {elseif $product.option_image_pairs}
                    {assign var="image_pair_var" value=$product.option_image_pairs|reset}
                {/if}

                {if $image_pair_var.image_id == 0}
                    {assign var="image_id" value=$image_pair_var.detailed_id}
                {else}
                    {assign var="image_id" value=$image_pair_var.image_id}
                {/if}

                {include file="common_templates/image.tpl" isMobileSlider=true obj_id="`$product.product_id`_`$image_id`" images=$image_pair_var show_detailed_link=true object_type="detailed_product" show_thumbnail="Y" image_width=$settings.Thumbnails.product_details_thumbnail_width image_height=$settings.Thumbnails.product_details_thumbnail_height rel="preview[product_images]" wrap_image=true}
                {if $product.in_inventory == 'Y'}
                    <img src="http://cdn.shopclues.com/images/banners/icons/shiping_two_four_delivery_icon.png" style="position: absolute;top: 290px;z-index: 4444;left: 10px;" title="{$lang.ship24}" />
                {/if}
                {foreach from=$product.image_pairs item="image_pair"}
                    {if $image_pair}
                        {if $image_pair.image_id == 0}
                            {assign var="image_id" value=$image_pair.detailed_id}
                        {else}
                            {assign var="image_id" value=$image_pair.image_id}
                        {/if}
                        {include file="common_templates/image.tpl" isMobileSlider=true images=$image_pair show_detailed_link=true object_type="detailed_product" show_thumbnail="Y" detailed_link_class="hidden" obj_id="`$product.product_id`_`$image_id`" image_width=$settings.Thumbnails.product_details_thumbnail_width image_height=$settings.Thumbnails.product_details_thumbnail_height rel="preview[product_images]" wrap_image=true}
                    {/if}
                {/foreach}
				</div>
	        <!--product_images_{$product.product_id}_update--></div>
	        {/if}

			<div class="prd-add-thm no_mobile image-border prew_mng float-left center cm-reload-{$product.product_id} img_cntr_new_div" id="product_images_{$product.product_id}_update">
						
			{include file="views/products/components/product_images.tpl" product=$product show_detailed_link="Y"}
			<!--product_images_{$product.product_id}_update--></div>
		{/if}
        
        <div class="img_blw no_mobile" style="clear:both; float:left;">
            <div class="gifting_notification">{if $product.is_giftable == 'Y'}
                {$lang.product_is_giftable}
            {/if}
            </div>
        <div class="social_buttons" style="margin-left:127px">
            <div class="fb_like_button float_left">
               <iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fshopclues.com/{$product.seo_name}.html&amp;send=false&amp;layout=button_count&amp;width=90&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21&amp;appId=174463259310647" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:90px; height:21px;" allowTransparency="true"></iframe>
            </div>
            <!--//commented by ankur not to show pinit-->
            <!--<div class="float_left">
			<a href="http://pinterest.com/pin/create/button/?url=http%3A%2F%2Fshopclues.com/{$product.product}.html&media=http%3A%2F%2Fshopclues.com{$product.main_pair.detailed.image_path}" class="pin-it-button" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>
			{literal}<script type="text/javascript" src="http://assets.pinterest.com/js/pinit.js"></script>{/literal}
        </div>-->
        </div>
		{assign var="disc_count" value=$product.product_id|fn_get_discussion_count:"P"}
		{if $disc_count==0}
        {assign var="new_review_enabled" value=""|is_review_enabled}

        {if $new_review_enabled}
            {assign var="review_url" value="index.php?dispatch=review.review&product_id=`$product.product_id`"}
		   <a href="{$review_url}" style="float:left; clear:both;display: block;
        text-align: center;margin-top: 4px; width: 320px;" class="ahover_nl">{$lang.write_first_review}
			</a>
        {else}
        <a href="{"products.view&product_id=`$product.product_id`"|fn_url}#write_new_review" style="float:left; clear:both;display: block;text-align: center;margin-top: 4px; width: 320px;" class="ahover_nl">{$lang.write_first_review}
			</a>
        {/if}
            {else}
            {if $config.new_review_enable}
                {assign var="review_url" value="index.php?dispatch=review.review&product_id=`$smarty.request.product_id`"}
            <a class="fb-popup-login_new write_new_review wrte_rev_blw_prd" rev="{$review_url}" href='index.php?dispatch=auth.fb_login'>{$lang.write_a_reivew_prd}</a>
        {else}
            <a class="write_new_review wrte_rev_blw_prd" href="{"products.view&product_id=`$product.product_id`"|fn_url}#write_new_review" title="Write a Review">{$lang.write_a_reivew_prd}</a>
            {/if}

		{/if}
		</div>

        
		<div class="mobile-prdct-cntnr" itemscope itemtype="http://schema.org/Product">
		<div class="product-info">
			<h1 class="mainbox-title" itemprop="name">{$product.product|unescape}</h1>
			 
			
			<div class="clearboth"></div>
			{*{assign var="sku" value="sku_$obj_id"}{$smarty.capture.$sku}*}
			
            <div class="float_left">{assign var="rating" value="rating_`$obj_id`"}</div>
            <div class="float_left" ></div>
            <div class="float_left str_prd_rat_icon" style="font:12px/14px 'Trebuchet MS', Arial, Helvetica, sans-serif; color:#636566">{$smarty.capture.$rating}</div>            

            
            <!--<div class="float_left">
			<a href="http://pinterest.com/pin/create/button/?url=http%3A%2F%2Fshopclues.com/{$product.product}.html&media=http%3A%2F%2Fshopclues.com{$product.main_pair.detailed.image_path}" class="pin-it-button" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>
			{literal}<script type="text/javascript" src="http://assets.pinterest.com/js/pinit.js"></script>{/literal}
            </div>-->
            
            <div class="clearboth"></div>
			
            {assign var="old_price" value="old_price_`$obj_id`"}
            {assign var="retail_price" value="retail_price_`$obj_id`"}

			{assign var="price" value="price_`$obj_id`"}
            {assign var="clean_price" value="clean_price_`$obj_id`"}
			{assign var="list_discount" value="list_discount_`$obj_id`"}
			
            
            <div class="short_text">{$product.short_text|unescape}</div>                 
            <div id="price_container_box_{$obj_id}" class="{if $smarty.capture.$old_price|trim || $smarty.capture.$clean_price|trim || $smarty.capture.$list_discount|trim}prices-container {/if}clear">
            
            <table class="mobile-prc-table" border="0" cellspacing="0" width="397" cellpadding="0">
			<tr><td>
            
			{if $smarty.capture.$old_price|trim || $smarty.capture.$clean_price|trim || $smarty.capture.$list_discount|trim}
			
            <div class="float-left product-prices">
                <!--Added by clues dev to show original price when catlog promotion is applied.-->
                {if $product.promotions}
                {if $product.list_price != $product.base_price}
                <span class="list-price float-left margin_right_five">{$lang.list_price}:
                <strike>{include file="common_templates/price.tpl" value=$product.list_price span_id="list_price_`$obj_prefix``$obj_id`" class="list_price"}</strike>{/if}
                {/if}
            </span>

			<div class="float_left" style="font:11px verdana #636566; margin-top:4px;">
{if $smarty.capture.$old_price|trim}{$smarty.capture.$old_price}&nbsp;{/if}</div>
                        {if $product.is_wholesale_product == 1 && !empty($product.retail_price) && $product.retail_price > $product.price && $product.retail_price < $product.list_price}
                            <div class="float_left no_mobile" style="font:11px verdana #636566; margin-top:4px;">{if $smarty.capture.$retail_price|trim}{$smarty.capture.$retail_price}&nbsp;{/if}</div>
                        {/if}
			
            {/if}
			<div class="clearboth"></div>

			{if !$smarty.capture.$old_price|trim || $details_page}<p>{/if}
					{$smarty.capture.$price}

				{if $after_apply_promotion != 0}
					<br><span class="price"><span class="lst_price_tit_nl {if $after_apply_promotion != 0}prc_third_app{/if}">{$lang.3rd_Price}:</span>{$after_apply_promotion|format_price:$currencies.$secondary_currency:""}</span>
				{/if}

			{if !$smarty.capture.$old_price|trim || $details_page}</p>{/if}
		
			{if $smarty.capture.$old_price|trim || $smarty.capture.$clean_price|trim || $smarty.capture.$list_discount|trim}
					{$smarty.capture.$clean_price}
                    
					{*{$smarty.capture.$list_discount}*}
                    
				</div>
			{/if}
           </td>
			<td valign="bottom">
			<div class="float-left mobile-dscnt-prc" style="clear:both; float:right; width:215px; text-align:center; display:inline; font:12px/14px 'Trebuchet MS', Arial, Helvetica, sans-serif; color:#636566; margin-bottom:3px; color:#048ccc;" id="product_save">
           
			{if $product.promotions}
					{assign var="disc_label" value=$product|calculate_discount_perc}
							{else}
					{if $product.discount}
							{assign var="disc_label" value=$product.discount_prc}
					{else}
							{assign var="disc_label" value=$product.list_discount_prc}
					{/if}
			{/if}
		<!-- Added By Sudhir dt 09 octo 2012 to show third price percentage-->
		{if $product.promotion_id !=0}
			{if $after_apply_promotion !=0}			
				{assign var="disc_label" value=$product|calculate_3rd_price_percentage:$after_apply_promotion}
			{/if}
		{/if}
       
       
		{if $disc_label>=0 }
			{if $after_apply_promotion >0}
               {if $product.list_price>0}
		             {assign var ="product_save" value=$product.list_price-$after_apply_promotion}
              {else}
              {assign var ="product_save" value=$product.price-$after_apply_promotion}
            {/if}
		   {else}
		   		{if $product.price < $product.list_price}
           			{assign var ="product_save" value=$product.list_price-$product.price}
		  		{else}
                	{assign var ="product_save" value="0"}
                {/if}
		   {/if}
           {if $product_save>0 && $disc_label>0}
		   		{$lang.product_you_save|replace:"[DISCOUNT_AMOUNT]":$product_save|replace:"[DISCOUNT_PERCENT]":$disc_label}
           {elseif $product_save>0}          	
           		{$lang.product_saving|replace:"[DISCOUNT_AMOUNT]":$product_save}
           {/if}
           
		{/if}
		   
		   </div>
           </td>
		   </tr></table>
            <div class="clearboth"></div>
            <div style="float:left; display:inline; font:12px/14px 'Trebuchet MS', Arial, Helvetica, sans-serif; color:#636566; margin-left:0px;">{$lang.price_includes_all_taxes}</div>
			<div class="clearboth"></div>

                
                 {assign var="red_hot_start_time" value=$product.redhot_start_datetime|strtotime}
                 {assign var="red_hot_end_time" value=$product.redhot_end_datetime|strtotime}
                 {assign var="end_time" value=$red_hot_end_time+$config.extend_time}
                 
                   <div class="odpdigit prd_page_timer" id="red_hot_deal_timer" style="margin:10px 10px 10px 0;">
                   </div>
                 
                 {if $smarty.now >= $red_hot_start_time && $product.redhot == 'Y' && $smarty.now <= $end_time}
                 <script type="text/javascript">
                                    
                                    var endtime= '{$product.redhot_end_datetime}';
                                    endtime = endtime.replace(/\-/g,'/');
                                    var EndDate = new Date(endtime);
                                    {literal}$.countdown('#red_hot_deal_timer', EndDate);{/literal}
                                    
                 </script>
                  {/if}
                
               
            <div class="special_offer_link">     
            {if $smarty.now > $red_hot_end_time && $smarty.now <= $end_time && $product.redhot == 'Y'}
            <span>{$lang.you_missed}</span>
            {else}
            <div class="spcl_offer_main_blk">
            <img class="spcl_offer_icon" src="http://cdn.shopclues.com/images/banners/icons/bg_offer_prd_page.png" />
            <div class="spcl_offer_bg">
	
{if $product.coupan != ''}
		<div class="spcl_offer_deal_blk"><b>{$product.offer_name}</b> {$lang.spl_offer} {$after_apply_promotion|format_price:$currencies.$secondary_currency:""} {$lang.spl_rs_val}<br />
		<b>{$lang.spl_offer_coupan}</b> <span class="spcl_clr_cpn">{$product.coupan}</span></div>
		
{/if}
{if $product.freebee}
	<div class="spcl_offer_deal_blk">
            <b>{$lang.spl_offer_freebee} </b> 
            <span class="spcl_clr_cpn">
                {assign var="freebee_image" value=$product.freebee.product_id|fn_get_image_pairs:'product':'M'}
                {include file="common_templates/image.tpl" image_width="160" image_height="160" obj_id=$product.freebee.product_id images=$freebee_image object_type="product" show_thumbnail="Y" class="img_free_prd"}
                <a href="{"products.view?product_id=`$product.freebee.product_id`"|fn_url}" target="_blank" >{$product.freebee.name} </a>
            </span>{$lang.spl_offer_worth} {$product.freebee.price}
        </div>	
{/if}	
{if $product.points_info.reward.pure_amount}
        {if $after_apply_promotion != 0}
        {math assign="cb_amount" equation="x*y/100" x=$after_apply_promotion y=$product.points_info.reward.pure_amount}
        {else}
        {math assign="cb_amount" equation="x*y/100" x=$product.price y=$product.points_info.reward.pure_amount}
        {/if}
        <div class="spcl_offer_deal_blk" style="border:none;padding-bottom: 0;">{$lang.spl_offer_cb} {$product.points_info.reward.pure_amount}{$lang.spl_offer_cb1} {$cb_amount|ceil}{$lang.spl_offer_cb2}<span class="spcl_clr_cpn"> {if $config.is_responsive}<a href="/ui/cluesbucks.html" target="_blank">{else}<a href="/cluesbucks.html" target="_blank">{/if}{$lang.spl_cb}</a></span></div>
{/if}

</div>            </div>
{/if}
         </div>  
            <div class="clearboth height_ten"></div>
			
            
			
            
            </div>
		
			{if $capture_options_vs_qty}{capture name="product_options"}{/if}
			
			<div class="mobile-stck-dlvry">
            {assign var="product_amount" value="product_amount_`$obj_id`"}
            {$smarty.capture.$product_amount}
            </div>
			
            {if $product.amount > 0 }
			{assign var="product_options" value="product_options_`$obj_id`"}
			{$smarty.capture.$product_options}
			{/if}
			{assign var="qty" value="qty_`$obj_id`"}
			{$smarty.capture.$qty}

			{assign var="advanced_options" value="advanced_options_`$obj_id`"}
			{$smarty.capture.$advanced_options}
			{if $capture_options_vs_qty}{/capture}{/if}
		
			{assign var="min_qty" value="min_qty_`$obj_id`"}
			{$smarty.capture.$min_qty}
			
			{assign var="product_edp" value="product_edp_`$obj_id`"}
			{$smarty.capture.$product_edp}

      
            {assign var="gc_data" value=$product.product_id|fn_get_gc_on_product_page}
        {if $gc_data|count > 0}
        <div class="add_gc_prd_page_nl">
            {$lang.add_gc_prd_page_pop_up}
            <span class="add_gc_text_prd_page">Add Gift Certificate </span>
        {foreach from=$gc_data item="gc_product_data"}

        <input type="checkbox" class="gc_data" name="gc_data[]" value={$gc_product_data.gc_amt} />
        <span class="gc_rs_new_container" style="margin-right:20px;">Rs.{$gc_product_data.gc_amt}
        </span>
        {/foreach}
      <div align="left" style="font:9px Verdana, Geneva, sans-serif; margin-top:-4px;">{$lang.gc_discount_text}</div>
        </div>
        {/if}

      {*
            {assign var="gc_pid" value=$config.gc_on_products|count}
  
            {if $gc_pid > 0 && $product.product_id|in_array:$config.gc_on_products}
            <div class="add_gc_prd_page_nl">
            {$lang.add_gc_prd_page_pop_up}
            <span class="add_gc_text_prd_page">Add Gift Certificate </span>
            <input type="checkbox" class="gc_data" name="gc_data[]" value="250" /><span class="gc_rs_new_container" style="margin-right:20px;">Rs.250</span>
            <input type="checkbox" class="gc_data" name="gc_data[]" value="500" /><span class="gc_rs_new_container">Rs.500</span>
            <div align="left" style="font:9px Verdana, Geneva, sans-serif; margin-top:-4px;">Get 15% Discount on Rs. 500 & 10% Discount on Rs. 250 </div>
            </div>
			{elseif $gc_pid == 0}
            	<div class="add_gc_prd_page_nl">
                {$lang.add_gc_prd_page_pop_up}
                <span class="add_gc_text_prd_page">Add Gift Certificate </span>
            <input type="checkbox" class="gc_data" name="gc_data[]" value="250" /><span class="gc_rs_new_container" style="margin-right:20px;">Rs.250</span>
            <input type="checkbox" class="gc_data" name="gc_data[]" value="500" /><span class="gc_rs_new_container">Rs.500</span>
            <div align="left" style="font:9px Verdana, Geneva, sans-serif; margin-top:-4px;">Get 15% Discount on Rs. 500 & 10% Discount on Rs. 250 </div>
            </div>
            {/if}  
            
          *}

			{if $capture_buttons}{capture name="buttons"}{/if}
				<div class="buttons-container nowrap" style="{if $actual_amount == 0}{if ($product.out_of_stock_actions == "S") && ($product.tracking != "O")}margin-top:0px; {else}margin-top:25px;{/if}{else}margin-top:25px;{/if}">
					{assign var="add_to_cart" value="add_to_cart_`$obj_id`"}
					{$smarty.capture.$add_to_cart}
					
					{assign var="list_buttons" value="list_buttons_`$obj_id`"}
					{$smarty.capture.$list_buttons}
				</div>
        {$smarty.capture.availability}

			{if $capture_buttons}{/capture}{/if}
            
		</div>
        <div class="clearboth height_ten"></div>
			{if $all_promotions_array==NULL}<hr class="dashed clear-both" />{/if}
        
	</div>
        </div>                            
   <!--/*change by ankur to show upsell products*/-->
   {if $config.show_upsell_products}
    {assign var="upsell_products" value=$product.main_category|fn_get_upsell_products:$product.product_id}
    {if !empty($upsell_products)}
    <div class="box_productsSmall">
      <div class="upsell_header">{$lang.upsell_header}</div>
      {foreach from=$upsell_products item="upsell_product"}
      <div class="prd_new_up_sell_box">
        <div class="box_productsSmall_product">
        <div class="box_productsSmall_product_image">
        <a href="{"products.view?product_id=`$upsell_product.product_id`"|fn_url}" class="box_GridProduct_product">
        {assign var="pro_images" value=$upsell_product.product_id|fn_get_image_pairs:'product':'M'}
        {include file="common_templates/image.tpl" image_width="160" image_height="160" obj_id=$upsell_product.product_id images=$pro_images object_type="product" show_thumbnail="Y"}</a>
        </div>
        <div class="box_productsSmall_product_name"><a href="{"products.view?product_id=`$upsell_product.product_id`"|fn_url}">{$upsell_product.product|unescape}</a></div>
        </div>
		<div style="margin:5px 0 5px 10px; float:left;">        
            <input type="checkbox" class="new_prd_up_sell_input" name="upsell_product[]" value="{$upsell_product.product_id}" onclick="show_order_total(this.checked,{$upsell_product.price},{$product.price})" /> 
            <div class="lang_pick_me_too">{$lang.pick_me_too}</div>
		</div>            
        <div style="clear:both"></div>
        <div class="pro_rating">
          {assign var="average_rating" value=$upsell_product.product_id|fn_get_average_rating:'P'}
                {if $average_rating}
                	{include file="addons/discussion/views/discussion/components/top_banner_stars.tpl" stars=$average_rating|fn_get_discussion_rating}
              	{/if}
        </div>
        <div class="pro_price">
         {if $upsell_product.list_price > $upsell_product.price}
			<span class="box_metacategory_price">MRP: <span style="text-decoration:line-through">{$upsell_product.list_price|format_price:$currencies.$secondary_currency:""}</span></span>
		 {/if}
         <span class="box_metacategory_price_new"> {$upsell_product.price|format_price:$currencies.$secondary_currency:""}</span>
        </div>
      </div>
      {/foreach}
      <div class="upsell_total" style="display:none">
      	<span style="font:11px verdana; color: #636566;">Rs.&nbsp;<span style="font: bold 18px trebuchet ms; color: #252525;" id="your_total_value"></span></span>
        <div style="clear:both"></div>
        <span style="font:11px verdana; color: #636566;">{$lang.order_total_will_be}</span>
      </div>
      
      <input type="hidden" name="your_total" id="your_total" value="{$product.price}" />
      {literal}
          <script>

    function show_order_total(val,upsell_product_price,product_price)
		  {
			   var current_total=parseInt($('#your_total').val());
			   product_price=parseInt(product_price);
			   if(val==true)
			   {
				   current_total+=parseInt(upsell_product_price);
			   }
			   else
			   {
				   current_total-=parseInt(upsell_product_price);
			   }
			   $('#your_total').val(current_total);
			   if(current_total!=product_price)
			   {
			  	 $('#your_total_value').html(current_total);
				 $('.upsell_total').show();
			   }
			   else
			   {
			  	 $('#your_total_value').html('');
				 $('.upsell_total').hide('');
			   }
		  }
		      function formatNumber(number) {
				number=number+'';
				return number.replace(/[^\d\.\-]/g,'').replace(/(\.\d{2})[\W\w]+/g,				'$1').split('').reverse().join('').replace(/(\d{3})/g, '$1,').split('').reverse().join('').replace(/^([\-]{0,1}),/, '$1').replace(/(\.\d)$/, '$1'+'0').replace(/\.$/, '.00');
			}
		</script>
      {/literal}
    </div>
    {/if}
   {/if} 
    <!--code end-->
	{assign var="form_close" value="form_close_`$obj_id`"}
	{$smarty.capture.$form_close}
	{/if}
	
{/hook}
<div class="clearboth">

                <!--code by chandan to get the upsell products new concept -->
{assign var="upsell_products" value=$product.product_id|fn_get_upsell_product_info}
{if !empty($upsell_products)}
    <form name="n_upsell_product" class="cm-ajax gift_popup_block" method="post" action="{""|fn_url}">
    <div class="upsell_section">
    <fieldset>
        <legend style="margin:0 0 0 8px;">{$lang.upsell_header_section}</legend>
        <div class="clearboth"></div>
        {assign var="combine_total" value="0"}
        <div class="upsell_product_section c_tog_{$product.product_id}">                
            <div class="upsell_section_image">
                {assign var="pro_images" value=$product.product_id|fn_get_image_pairs:'product':'M'}
                {assign var="up_sec_id" value='obj_id'|cat:$product.product_id}
                {include file="common_templates/image.tpl" image_width="160" image_height="160" obj_id="up_image_`$up_sec_id`" images=$pro_images object_type="product" show_thumbnail="Y" alt_text=$up_product.product}
            </div>
           {* <div class="prd_plus" id="prd_pl">+</div>*}
            <div class="upsell_section_info">
                {*<label class="name">
                    <a href="{"products.view&product_id=`$up_product.product_id`"|fn_url}" target="_blank" title="{$product.product}">
                    {if $product.product|strlen>36}{$product.product|substr:0:33}...{else}{$product.product}{/if}
                    </a>
                </label>*}
                <div class="clearboth"></div>
                <div class="strk_price">
                    {if !empty($product.list_price)}
                        {if !empty($product.price)}<strike>{/if} 
                        <label>
                        {include file="common_templates/price.tpl" value=$product.list_price span_id="list_price_`$up_product.product_id`" class="list_price"}
                        </label>
                        {if !empty($product.price)}</strike>{/if}
                    {/if}
                    {assign var="price_class" value="price"}  
                    {if !empty($after_apply_promotion)} 
                        {assign var="price_class" value="list_price"}
                        <strike>
                    <div class="clearboth"></div>  
                    <label>
                        {include file="common_templates/price.tpl" value=$product.price span_id="selling_price_`$up_product.product_id`"  class=$price_class}
                    </label>{/if}
                    {if !empty($after_apply_promotion)}</strike>{/if}
                </div>
                <div class="strk_price_prc">
                    {if $after_apply_promotion != 0}
                            <br><span class="price">{$after_apply_promotion|format_price:$currencies.$secondary_currency:""}</span>
                    {else}
                        <label>
                            {include file="common_templates/price.tpl" value=$product.price span_id="selling_price_`$up_product.product_id`"  class=$price_class}
                        </label>
                    {/if}
                </div>
                {if !empty($after_apply_promotion)}
                    {assign var="combine_total" value=$combine_total+$after_apply_promotion}
                {elseif !empty($product.price)}
                     {assign var="combine_total" value=$combine_total+$product.price} 
                {else}
                    {assign var="combine_total" value=$combine_total+$product.list_price}
                {/if}
            </div>
            <label class="lang_var_prd_up"></label>
                
        </div> 
        {foreach from=$upsell_products item="up_product" name="upsale_pro"}
            <div class="upsell_product_section c_tog_{$up_product.product_id}" {if $smarty.foreach.upsale_pro.first} id="first"{/if} >
                {assign var='up_third_price' value=null}
                {if $up_product.promotion_id!=0}                    
                    {assign var='up_third_price' value=$up_product|fn_get_3rd_price}
                {/if}
                <div class="upsell_section_image">
                    {assign var="pro_images" value=$up_product.product_id|fn_get_image_pairs:'product':'M'}
                    {assign var="up_sec_id" value='obj_id'|cat:$up_product.product_id}
                    {include file="common_templates/image.tpl" image_width="160" image_height="160" obj_id="up_image_`$up_sec_id`" images=$pro_images object_type="product" show_thumbnail="Y" alt_text=$up_product.product}
                </div>
                {*{if !$smarty.foreach.upsale_pro.last}
                    <div class="prd_plus" id="prd_pl">+</div>
                {/if}*}
                <div class="upsell_section_info">
                    {*<label class="name">
                        <a href="{"products.view&product_id=`$up_product.product_id`"|fn_url}" target="_blank" title="{$up_product.product}">
                        {if $up_product.product|strlen>36}{$up_product.product|substr:0:33}...{else}{$up_product.product}{/if}
                        </a>
                    </label>*}
                    <div class="clearboth"></div>
                    <div class="strk_price">
                        {if !empty($up_product.list_price)}
                            {if !empty($up_product.price)}<strike>{/if} 
                            <label>
                            {include file="common_templates/price.tpl" value=$up_product.list_price span_id="list_price_`$up_product.product_id`" class="list_price"}
                            </label>
                            {if !empty($up_product.price)}</strike>{/if}
                        {/if}
                        {assign var="price_class" value="price"}  
                        {if !empty($up_third_price)} 
                            {assign var="price_class" value="list_price"}
                            <strike>
                        <div class="clearboth"></div>
                        
                        <label>
                            {include file="common_templates/price.tpl" value=$up_product.price span_id="selling_price_`$up_product.product_id`"  class=$price_class}
                        </label>
                        {/if}
                        {if !empty($up_third_price)}</strike>{/if}
                    </div>
                    <div class="strk_price_prc">
                        {if !empty($up_third_price)}
                            <label>{include file="common_templates/price.tpl" value=$up_third_price span_id="3rd_price_`$up_product.product_id`" class="price"}</label> 
                        {else}
                            <label>
                                {include file="common_templates/price.tpl" value=$up_product.price span_id="selling_price_`$up_product.product_id`"  class=$price_class}
                            </label>
                        {/if}
                    </div>
                </div>
                  
                    
                <label class="lang_var_prd_up"></label>                
                {if !empty($up_third_price)}
                    {assign var="combine_total" value=$combine_total+$up_third_price}
                {elseif !empty($up_product.price)}
                     {assign var="combine_total" value=$combine_total+$up_product.price} 
                {else}
                    {assign var="combine_total" value=$combine_total+$up_product.list_price}
                {/if}
            </div> 
        {/foreach}
        <div id="c_tog_tb" style="float:right; margin: 30px 15px 0 0;">
                    <input type="hidden" id="combine_price" name="combine_price" value="{$combine_total}" />
                    <div style="text-align:center; font-size:15px; color:#990000;">
                        <span>Rs.</span>
                        <span id="c_tog_pr">
                            {$combine_total}
                        </span>
                    </div>
                    <div class="clear:both;"></div>
                    <input type="hidden" name="redirect" value="{$config.current_url}" />
                    <input type="hidden" name="result_ids" value="cart_status,wish_list" />
                    <div id="c_tog_butt">
                    <div class="pro_det_add_to_cart_butto">                         
						
 		<span id="wrap_button_cart_{$product.product_id}" class="button-submit-action new_btn_buy_together_btn">

                {include file="buttons/save.tpl" but_name="dispatch[checkout.add]" but_text=$lang.buy_all_together but_role="button_main" but_class="box_functions_button nl_btn_blue c_buy_together"}

	</span>
	
	 </div>
                        
        </div>
               </div>   
                    
  
        <div class="clear:both;"></div>
        <div id="c_tog_p" style="float:left; clear:both; margin-left:10px;">
            <input type="checkbox" class="c_tog_pid" name="new_pro_upsell[]" value="{$product.product_id}" checked="checked" onclick="calculate_c_together({$product.product_id},this.checked,{if !empty($after_apply_promotion)}{$after_apply_promotion}{else}{$product.price}{/if})">
            {$product.product}
            {if $product.promotion_id != 0}
                {assign var="coupon_code" value=$product.promotion_id|fn_get_upsell_product_coupon_code}
                {$lang.cc_on_product_page|replace:'[COUPON_CODE]':$coupon_code}
            {/if}    
            
            {foreach from=$upsell_products item="up_product"}
                <div class="clear:both;"></div>
                {assign var='up_third_price' value=null}
                {if $up_product.promotion_id!=0}                    
                    {assign var='up_third_price' value=$up_product|fn_get_3rd_price}
                {/if}
                <input type="checkbox" class="c_tog_pid" name="new_pro_upsell[]" value="{$up_product.product_id}" checked="checked" onclick="calculate_c_together({$up_product.product_id},this.checked,{if !empty($up_third_price)}{$up_third_price}{else}{$up_product.price}{/if})">
                {$up_product.product}
                {if $up_product.promotion_id != 0}
                    {assign var="coupon_code" value=$up_product.promotion_id|fn_get_upsell_product_coupon_code}
                    {$lang.cc_on_product_page|replace:'[COUPON_CODE]':$coupon_code}
                {/if}
            {/foreach}        
        </div>
            
<label style="padding: 2px 5px 5px 5px; display: block; clear: both;">{$lang.upsell_bottom_section}</label>
</fieldset>
</div>
</form>
{literal}
<script type="text/javascript">
    function calculate_c_together(p_id,val,product_price)
    {
            var current_total=parseInt($('#combine_price').val());
            //product_price=parseInt(product_price);
            
            if(val==true)
            {
                    current_total+=parseInt(product_price);
                    $('.c_tog_'+p_id).css('display','block');
            }
            else
            {
                    current_total-=parseInt(product_price);
                    $('.c_tog_'+p_id).css('display','none');
                    
            }
            $('#combine_price').val(current_total);
            if(current_total == "0")
            {
                $('#c_tog_butt').css('display','none');
                $('#c_tog_tb').css('display','none');
            }else{
                $('#c_tog_butt').css('display','block');
                $('#c_tog_tb').css('display','inline');
                $('#c_tog_pr').html(current_total);
            }
            if($(".c_tog_pid:checkbox:checked").length == "1"){
                $(".c_buy_together:submit").attr('value','{/literal}{$lang.add_to_cart}{literal}');
            }else if($(".c_tog_pid:checkbox:checked").length > "1"){
                $(".c_buy_together:submit").attr('value','{/literal}{$lang.buy_all_together}{literal}');
            }
			/*if(val==true){
				$('.c_tog_'+p_id).prev(".upsell_product_section").children(".prd_plus").css('display','block');
			}else{
				if($('.c_tog_'+p_id).attr('id') != "first"){
					$('.c_tog_'+p_id).prev(".upsell_product_section").children(".prd_plus").css('display','none');
				}
			}*/

    }        
</script>
{/literal}
{/if}
            <!-- code end -->
    
    
<!--<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fshopclues.wiantech.net/{$product.seo_name}.html&amp;send=false&amp;layout=button_count&amp;width=200&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21&amp;appId=174463259310647" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:200px; height:21px;" allowTransparency="true"></iframe>-->

</div>
	
{if $smarty.capture.hide_form_changed == "Y"}
	{assign var="hide_form" value=$smarty.capture.orig_val_hide_form}
{/if}
{capture name="tabsbox"}
	{assign var="but_role" value=""}
	{assign var="tabs_block_orientation" value=$blocks.$tabs_block_id.properties.block_order}
    
{assign var="count" value=0}

	{foreach from=$blocks item="block" key="block_id" name="nav_box"}
   
		{if $block.group_id == $tabs_block_id}
			{assign var="tabs_capture_name" value="tab_`$block_id`"}
			{capture name=$tabs_capture_name}
				{block id=$block_id no_box=true}
			{/capture}
			{assign var="nav_block_id" value="block_`$block_id`"}
            
			{if $smarty.capture.$tabs_capture_name|trim}
            {assign var="tab_lists" value=$navigation.tabs.$nav_block_id.title|fn_list_tabs}            
				{if $tabs_block_orientation == "V"}
                <!--Page Heading -->
                             
<div class="ml_pageheaderCateogry ml_pageheaderCateogry_{$block_id}">
<h1 class="ml_pageheaderCateogry_heading">

<a name="{$navigation.tabs.$nav_block_id.title}">{$navigation.tabs.$nav_block_id.title}</a>
</h1>
		<span class="mob_cat_icn_rgt"></span>
</div>
<!--End Page Heading -->					
				{/if}
			{/if}

			<div id="content_block_{$block_id}" class="mobile_cntnt_blks wysiwyg-content{if $hide_tab && $tabs_block_orientation == "H"} hidden{/if}">
				{$smarty.capture.$tabs_capture_name}
                
                {if $navigation.tabs.$nav_block_id.title == "Features"}
                	{$lang.shopclue_extra_text|unescape}
                   
                {/if}
                {assign var="count" value=$count+1}
               	{if $count == 2}      
                {include file="views/products/report_issue_redirect.tpl"} 
                   {/if}
			</div>
              

           
			{if $smarty.capture.$tabs_capture_name|trim}
				{assign var="hide_tab" value=true}
			{/if}
            
		{/if}
      
       
	{/foreach}
    
{/capture}
{if $all_promotions_array==NULL}<br />{/if}
{if $all_promotions_array!==NULL}
        <div class="buy_tog_blk_prd_page">
        <div id="tabs">
        <ul>
        {assign var="promotion_counter" value=0}
        {foreach from=$all_promotions_array item="d"}
                {assign var="promotion_counter" value=$promotion_counter+1}
                {assign var="deal_name" value="offer_"|cat:$promotion_counter}
                <li><a href="#fragment-{$promotion_counter}">{$lang.$deal_name}</a></li>
        {/foreach}
        </ul>

        {assign var="promotion_count" value=1}

        {foreach from=$all_promotions_array item="d"}
                {assign var="total_price_for_products" value=0}
                {assign var="number_of_products_in_deal" value=$d|count}
                <div id="fragment-{$promotion_count++}">
                        {foreach from=$d item="e" name="e_s"}
                            {assign var="total_price_for_products" value=$total_price_for_products+$e.price}
                                {if $number_of_products_in_deal== "2"}
                                       {assign var="prd_grid" value="one_prd_grid"}
                                {else}
                                        {assign var="prd_grid" value="two_prd_grid"}
                                {/if}
                        <a href="{"products.view?product_id=`$e.product_id`"|fn_url}" target="_blank">
                        <div class="chain-products {$prd_grid}" style="display:inline-block; vertical-align:middle;">
                        <div class="chain-product">{assign var="pro_images" value=$e.product_id|fn_get_image_pairs:'product':'M'}
                        {include file="common_templates/image.tpl" image_width="160" image_height="160" obj_id=$obj_id_prefix images=$pro_images object_type="product" show_thumbnail="Y"}
                        </div>
                        <div class="chain-note buy_tog_prd_name">{$e.product}</div>
                        <div class="chain-note buy_tog_act"><strike>Rs.{$e.list_price}</strike></div>
                        <div class="chain-note buy_tog_act_price">Rs.{$e.price}</div>

                        </div>
                        </a>
                        {if !$smarty.foreach.e_s.last}
                            <div class="chain-plus {$prd_grid}_plus">+</div>
                        {/if}

                        {/foreach}
                <div class="chain-price">
                <div class="chain-old-price"><span class="chain-old">{$lang.coupon_code_buy_together}</span> <span class="coupon_code_buy_tog clear_both_buy_tog">{$e.coupon_code}</span></div>
                <div class="chain-old-price" style="margin-top:10px;"><span class="chain-old">{$lang.total_price_buy_together}</span> <span class="chain-old-line clear_both_buy_tog">Rs.{$total_price_for_products}</span></div>
                <div class="chain-new-price"><span class="chain-new new_price_buy_tog">{$lang.deal_price_buy_together}</span> <span class="chain-new-line clear_both_buy_tog">Rs.{$e.deal_price}</span></div>
                <form name="n_upsell_product" class="cm-ajax gift_popup_block" method="post" action="{""|fn_url}">
                {foreach from=$d item="ee"}    
                    <input type="hidden" class="c_tog_pid" name="combo_offer[]" value="{$ee.product_id}">
                {/foreach}    
                <input type="hidden" value="checkout.add" name="dispatch">
                <span id="wrap_button_cart_{$product.product_id}" class="button-submit-action new_btn_buy_together_btn">
                {include file="buttons/save.tpl" but_name="dispatch[checkout.add]" but_text=$lang.buy_all_together_offer but_role="button_main" but_class="box_functions_button nl_btn_blue c_buy_together"}
                </span>
        </form>                   

        </div>

        </div> 
        {/foreach}
        </div>
        </div>
        {literal}
             <script>
        $(function() {
        $( "#tabs" ).tabs();
        });
        </script>
            {/literal}

{/if}
{if $config.other_sellers_same_product}
    {include file="views/products/solr_other_sellers.tpl"}
{/if}
{capture name="tabsbox_content"}
{if $tabs_block_orientation == "V"}
	<div class="pj2_tabs_bg">
    {foreach from=$tab_lists item="tab_list" key="block_id" name="tabs_list"}
    	{if  $smarty.foreach.tabs_list.last }
			<a href="{"products.view&product_id=`$product.product_id`"|fn_url}#{$tab_list}" title="{$tab_list}">{$tab_list}</a>
		{else} 
			<a href="{"products.view&product_id=`$product.product_id`"|fn_url}#{$tab_list}" title="{$tab_list}">{$tab_list}</a> 
		{/if}
    {/foreach}
    
    </div>
    <div class="clearboth"></div>
    <div class="mobile-dlvry-note">{$lang.delivery_note}</div>
{if $product.coupan != ''}
{if $product.special_offer != ""}
        <div class="mobile-spcl-offr" style="float:left; width:97%;  background-color:#f2f2f2; margin-top:10px; padding:5px 10px; ">
        <h1 style="margin-top:0px;" class="tab-list-title ancor_mng"><a name="special_offer">Special Offer</a></h1>
        {$product.special_offer|unescape}
        </div>
        <div class="clearboth"></div>
        {/if}
    {/if}
     {if $product.additional_offer != ""}
        <div class="mobile-spcl-offr" style="float:left; width:97%;  background-color:#f2f2f2; margin-top:10px; padding:5px 10px; ">
        {$product.additional_offer|unescape}
        </div>
        <div class="clearboth"></div>
    {/if}
    {$smarty.capture.tabsbox}
{else}
	{include file="common_templates/tabsbox.tpl" content=$smarty.capture.tabsbox active_tab="block_`$smarty.request.selected_section`"}
{/if}
{/capture}

{if $blocks.$tabs_block_id.properties.wrapper}
{$blocks.$tabs_block_id.properties.wrapper}
	{include file=$blocks.$tabs_block_id.properties.wrapper content=$smarty.capture.tabsbox_content title=$blocks.$tabs_block_id.description}
{else}
	{$smarty.capture.tabsbox_content}
{/if}
</div>

<div class="product-details">
</div>

{capture name="mainbox_title"}{assign var="details_page" value=true}{/capture}
         {literal}
          <script>
		   $('.contest_icon').click(function(){
			    $('.contest_popup').show();
			   });
		   $('.img_close').click(function(){
			     $('.contest_popup').hide();
			   });	   
		  </script>
<script type="text/javascript">
		$('#product_thumbnails > li > a').click(function(){
			var img_id = this.id.slice(0,-5);
			$('#'+img_id).css('display','table-cell');    
		});
var optionsOwl_product = {
            navigation : false,
            slideSpeed : 300,
            paginationSpeed : 200,
            singleItem:true
        };

if($(window).width()<630)
{

jQuery_1_10_2(".mobile-slider").owlCarousel(optionsOwl_product);
// Custom Navigation Events
        jQuery_1_10_2(".mobile-slider-cntnr .jcarousel-next").click(function(){
            var owl = jQuery_1_10_2(this).parent().find(".mobile-slider");
            owl.trigger('owl.next');
        });
        jQuery_1_10_2(".mobile-slider-cntnr .jcarousel-prev").click(function(){
            var owl = jQuery_1_10_2(this).parent().find(".mobile-slider");
            owl.trigger('owl.prev');
        });

$(".product-main-info .ml_pageheaderCateogry").click(function(){

            if($(this).hasClass("active"))
            {
                $(".mobile_cntnt_blks").slideUp("fast");
                $(".product-main-info .ml_pageheaderCateogry").removeClass("active");
            }
            else
            {
                $(".mobile_cntnt_blks").slideUp("fast");
                $(".product-main-info .ml_pageheaderCateogry").removeClass("active");
                $(this).addClass("active");
                $(this).next(".mobile_cntnt_blks").slideDown("fast");
            }


        });
	/*if ($('.product-notification-container').length){
		if($(".our-logo").css("display")== "none"){
			window.location = "index.php?dispatch=checkout.cart";
		}
	}
	else{
		$('body').bind('DOMNodeInserted', 'central-column', function(e) {
		    // detecting mobile
		    if($(".our-logo").css("display")== "none"){
		        // mobile true!
		        if ($(e.target).hasClass('product-notification-container')) {
		            window.location = "index.php?dispatch=checkout.cart";
		        }

		    }
        	});
	}*/
	$(".ml_pageheaderCateogry_discussion").click();
}
	</script>
         {/literal}
               {literal}
                <script type="text/javascript">
                
                     var h = $('.hour').html();
                     var m = $('.minute').html();
                     var s = $('.second').html();
                    
                    if(h=='00' && m=='00' && s=='00')
                    {
                      $(".special_offer_link").html();
                    }
                
                 </script>
                 
                {/literal}
{if $config.express_checkout}
{literal}
            <script type="text/javascript">
                var product_id = {/literal}{$product.product_id}{literal};
                var function_name = 'fn_form_post_product_form_'+product_id;
                function setFunc (name) {
                window[name] = function (result) { 
                                    $('.notification-x').css('display','none');
                                    var ex = JSON.stringify(result);
            
                                    var z = $.parseJSON(ex);
            
                                    var y = z.notifications;
        
                                    var u;
                                    $.each(y,function(index){u = y[index];});

                                    var return_url;
                                    var ext_msg = u.ext_msg;
                                    var url = ext_msg;
                                    var type = u.type;

                                    if(url != '' && type=='X' && url!=null)
                                    {
                                       
                                        return_url = 'index.php?dispatch='+url;
                                        $(location).attr('href',return_url);
                                    }
                                
                           }
                }
            setFunc(function_name);
     
            </script>
        {/literal}
        {/if}
{literal}
 <script>
function SetCookieProd(){
         var set_cookie_value = {/literal}"{$config.is_set_recent_cookie}"{literal};
         if(set_cookie_value > 0){
         var cookie_product_ids = ReadCookie('rvph');
         var product_id= {/literal}"{$product.product_id}"{literal};
         var product_ids = product_id+','+cookie_product_ids;
         var cookieName = 'rvph';
         var temp = new Array();
         var temp = product_ids.split(",");
         var arr1=new Array();
         var hash={};
         for(var i= 0;i<temp.length;i++)
         {
         if(!(temp[i] in hash) )
          {
         arr1.push(temp[i]);
         hash[temp[i]]=1;
       
          }
    
         }
         var arr2 = new Array();
         var len = 0;
         if(arr1.length < set_cookie_value){
         len=arr1.length;
         }
         else{
         len = set_cookie_value;
         }
         for(var i= 0;i<len;i++)
         {
         arr2.push(arr1[i]);
         }
         var cookieValue1 = arr2.join(",");
         var cookieValue2 = cookieValue1.replace(/,+$/g,"");
         var domain = ".shopclues.com";
         var today = new Date();
         var expire = new Date();
         var nDays = 720;
         expire.setTime(today.getTime() + 2000000000*nDays);
         document.cookie = cookieName+"="+escape(cookieValue2)
                 + ";expires="+expire.toGMTString()+";domain="+domain+";path=/";

       }
         }
     SetCookieProd();



function SetCookieTrackUser(track_source){
 var cookie_data= ReadCookie('scts');
 var cookieValue= cookie_data+track_source;
 var cookieName='scts';
 var domain = {/literal}"{$config.cookie_domain}"{literal};
 var expire={/literal}{$config.track_user_cookie}{literal};
 var now = new Date();
 var time = now.getTime();
 var expireTime = time + (expire * 1000);
 now.setTime(expireTime);
    if(track_source){
        document.cookie = cookieName+"="+ cookieValue + ";expires="+now.toGMTString()+";domain="+domain+";path=/";
       
    } 
var cookie_scts= ReadCookie('scts');
$.ajax({
type: "post", 
url: "index.php?dispatch=products.track_product_view", 
data:{product_id:id,cookie:cookie_scts},   
success: function(msg) {
}
});

}    
  var trackuser={/literal}"{$config.track_user}"{literal};
  
var referral=document.referrer;
function call_track_user(id,referral){ 
 $.ajax({
type: "post", 
url: "index.php?dispatch=products.track_source_cookie", 
data:{product_id:id,referral:referral},   
  success: function(msg) {
    SetCookieTrackUser(msg);
  }
  
  });
}
var id={/literal}"{$smarty.request.product_id}"{literal};

  if(trackuser){
  call_track_user(id,referral);
  }
  </script>
  {/literal}

{literal}
        <script type="text/javascript">
            $("body").append("<div class='mob_fullimg_overlay'></div>");
            $("body").append("<a class='mob_close_ovrlay'><span> Ã— Close </span></a>");
            $(".mobile-slider-cntnr .mobile-slider .owl-item a img").click(function(){
                if($(".mobile-slider-cntnr").hasClass("mob_overlay")){
                    var src = $(this).parent().attr('href');
                    $(".mob_fullimg_overlay").append("<img src='"+ src +"'/>");
                    $(".mob_fullimg_overlay").show();
                    $(".helper-container ").hide();
                }else{
                    $(".mobile-slider-cntnr").addClass("mob_overlay");
                    $(".mob_close_ovrlay").show();
                }
                return false;
            })
            $(".mob_close_ovrlay").click(function(){
                $(".mobile-slider-cntnr").removeClass("mob_overlay");
                $(".mob_fullimg_overlay img").remove();
                $(".mob_fullimg_overlay").hide();
                $(".mob_close_ovrlay").hide();
                $(".helper-container ").show();
            });

            $(".mob_fullimg_overlay").click(function(){
                $(".mob_fullimg_overlay img").remove();
                $(".mob_fullimg_overlay").hide();
                $(".helper-container ").show();
            });
        </script>
{/literal}
