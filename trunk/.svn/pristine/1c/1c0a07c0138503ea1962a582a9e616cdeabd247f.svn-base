{* $Id: products_list.tpl 12452 2011-05-13 11:33:14Z alexions $ *}
{if $products}

{script src="js/exceptions.js"}

{if !$no_pagination}
	{include file="common_templates/pagination.tpl"}
{/if}
{if !$no_sorting}
	{include file="views/products/components/sorting.tpl"}
{/if}
{assign var="key" value=0}
{assign var="adword" value=""}
{foreach from=$products item=product  name="products" key="k"}
{capture name="capt_options_vs_qty"}{/capture}
{hook name="products:product_block"}
{assign var="obj_id" value=$product.product_id}
{assign var="obj_id_prefix" value="`$obj_prefix``$product.product_id`"}

{assign var="after_apply_promotion" value=0}
		{if $product.promotion_id !=0}
			{assign var="after_apply_promotion" value=$product|fn_get_3rd_price}
		{/if}
                
    <!--added by sapna -->
    {if isset($smarty.request.page)}
        {math assign="key" equation="(x*z)+(y)" x=$smarty.request.page-1 y=$key+1 z=$product_per_page}
    {else}
        {assign var="key" value=$key+1}

    {/if}

    {assign="keyword" value=$smarty.request.q}
    {assign var="elevate" value=""}
    {assign var="elevate" value=$product|fn_check_elevated:$smarty.request.q:$key:$smarty.request.page}
    {if $elevate ==1} {assign var="adword" value=$adword|cat:$product.product_id|cat:","} {/if}
    {if $smarty.foreach.products.last && empty($smarty.request.page) && empty($smarty.request.sort_order) && !empty($adword)} <input type="hidden" id="ad_products" value="{$adword}" onclick="fn_show_product('{$adword}', '{$smarty.request.q}')"/> {/if}
    <!--added by sapna -->
    {*hardcoded{assign var="elevate" value=1}*}
    {assign var="keyword" value=$smarty.request.q}
    {assign var="search_keyword" value=""|fn_check_elevated:$smarty.request.q:"":""}
    {assign var="url" value=""}
    {assign var="product_data_url" value=""}
    {if $elevate ==1}

        {assign var="track" value=""}
        {assign var="track" value=$track|cat:"@@"|cat:$key|cat:"@@"|cat:$search_keyword|cat:"@@"|cat:$elevate}
        {assign var="url" value=$track|base64_encode}
        {assign var="product_data_url" value="?track=$url"}
        {assign var="url" value="?track=$url"}
{/if}
{if $after_apply_promotion !=0}
    {assign var="last_price" value=$after_apply_promotion}

{else}
    {if $product.price !=0}
        {assign var="last_price" value=$product.price}
     
    {else}
      {assing var="last_price" value=$product.list_price}
   
    {/if}
{/if}
{if $controller=='products' and $mode=='search'}
	{*if !empty($smarty.request.name)}
		{*assign var="url" value=$smarty.request.name}
		{assign var="url" value="?utm_source=$url"}
		{assign var="product_data_url" value=$url}
	{/if*} 
{/if} 
{include file="common_templates/product_data.tpl" product=$product min_qty=true last_price=$last_price key=$key url=$product_data_url}
<div class="clearboth"></div>

<div class="product-container clear product_list_page" style="padding: 5px 0 18px 0; border-bottom: 1px solid #EEE; margin-bottom: 10px;{if $elevate ==1 && $config.sponsored_highlight}background-color:#edf8fe;{/if}">
	{assign var="form_open" value="form_open_`$obj_id`"}
	{$smarty.capture.$form_open}
	{if $bulk_addition}
	<div class="float-right">
		<input class="cm-item" type="checkbox" id="bulk_addition_{$obj_prefix}{$product.product_id}" name="product_data[{$product.product_id}][amount]" value="{if $js_product_var}{$product.product_id}{else}1{/if}" {if ($product.zero_price_action == "R" && $product.price == 0)}disabled="disabled"{/if} />
	</div>
	{/if}
	{*sapna*}
    
	<div class="float-left product-item-image center prd_lst_img_blk" style="position:relative;">

        {*anoop code for value added service stamps*}
        {if $product.value_added_services.qty_disc_flag == '1'}
                <div class="icon_EDB_small">  
                </div>
          {/if}
          {if $product.value_added_services.min_qty_disc_flag == '1'}
                <div class="icon_EDR_small">
                </div>
          {/if}
        {*anoop code ends here*}
		<span class="cm-reload-{$obj_prefix}{$obj_id} image-reload" id="list_image_update_{$obj_prefix}{$obj_id}">

                {if $config.fb_user_engagement}
                {*Wishlist*}
                <div class="product_list_page_wishList" style="bottom:146px;">
                    {if $product.product_amount_available =='0'}
                    <div class="top">{$lang.sold_out_feature}</div>
                    {/if}
                    <div class="middle">
                        {* code for facebook share, google share and wishlist *}
                        <a id="ajaxified_wishlist" class="ajaxified_wishlist" onclick="ajaxifiedWishlist({$product.product_id})">Add to Wishlist</a>
                        <a  class="googleShare" onclick="googleShare({$product.product_id})"></a>
                        <a  class="facebookShare" onclick="fbShare({$product.product_id})"></a>

                        {* code for facebook share, google share and wishlist ends here *}
                    </div>
                    <div class="bottom"></div>
                </div>
                <div class="clearboth"></div>
                {*End Wishlist*}
                {else}
                {if $product.product_amount_available == 0}<div class="sold_out_text_pic product_grid_sold_out">{$lang.sold_out_feature}</div>{/if}
                {/if}

                {if !$hide_links}

                    {if $controller=='products' and $mode=='search'}

                        <a href="{"products.view?product_id=`$product.product_id`"|fn_url}{$url}" id="{$product.product_id}" class="{if !$config.fb_user_engagement && $product.product_amount_available == 0}sold_out_category{/if}" style="position:relative; float:left;" onclick="productCookie('{$product.product_id}', '{$key}', '{$last_price}');">

                        {else}
                            <a href="{"products.view?product_id=`$product.product_id`"|fn_url}" class="{if !$config.fb_user_engagement && $product.product_amount_available == 0}sold_out_category{/if}" id="{$product.product_id}" style="position:relative; float:left;">
                            {/if}
                            <input type="hidden" name="image[list_image_update_{$obj_prefix}{$obj_id}][link]" value="{"products.view?product_id=`$product.product_id`"|fn_url}" />
                        {/if}

                        {assign var="after_apply_promotion" value=0}
                        {assign var="price_see_inside" value=0}
                        {if $product.promotion_id !=0}
                            {assign var="after_apply_promotion" value=$product|fn_get_3rd_price}
                        {/if}


                        <input type="hidden" name="image[list_image_update_{$obj_prefix}{$obj_id}][data]" value="{$obj_id_prefix},{$settings.Thumbnails.product_lists_thumbnail_width},{$settings.Thumbnails.product_lists_thumbnail_height},product" />

                        {assign var="is_new" value=$product|check_product_for_new}
                        {if $is_new == 'new'}
                            <div class="label_new_list"></div>
                        {/if}
                        {assign var="is_ngo" value="$product.company_id|fn_check_merchant_for_ngo}
      {if $is_ngo == 'Y'}
        <div class="label_ngo_list"></div>
      {/if}

      <div class="mobile_list_view">
            {include file="common_templates/image.tpl" image_width=$settings.Thumbnails.product_lists_thumbnail_width obj_id=$obj_id_prefix images=$product.main_pair object_type="product" show_thumbnail="Y" image_height=$settings.Thumbnails.product_lists_thumbnail_height}
            </div>

			{if !$hide_links}
				</a>
			{/if}
		{if $product.is_wholesale_product && $config.wholesale_icon}
			<div class="clearboth"></div>
			<div class="icon_wholesale"></div>
		{/if}
	</span>
		<div class="clearboth"></div>


		{assign var="rating" value="rating_$obj_id"}
		{*$smarty.capture.$rating*}
        <div class="box_metacategory_rating grid_str_nl">
	{assign var="average_rating" value=$product.product_id|fn_get_average_rating:'P'}
	{if $average_rating}
	{include file="addons/discussion/views/discussion/components/top_banner_stars.tpl" stars=$average_rating|fn_get_discussion_rating}
	{/if}
</div>


                            {if $product.freebee_inside =="1"}
                              <a class="list_free_gft_nl" href="{"products.view?product_id=`$product.product_id`"|fn_url}" onClick="_gaq.push(['_trackEvent', 'Homepage', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.topproductlisting.iteration}']);"><img src="{$config.freebee_inside_image_url}" /></a>
                            {/if}

                            {if $product.deal_inside_badge =="1"}
                             <a class="list_deal_inside_nl" href="{"products.view?product_id=`$product.product_id`"|fn_url}" onClick="_gaq.push(['_trackEvent', 'Homepage', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.topproductlisting.iteration}']);"><img src="{$config.cashback_inside_image_url}" /></a>
                            {/if}

                            {if $product.special_offer_badge =="1" || (isset($product.special_offer_text) && !empty($product.special_offer_text) && $product.price_see_inside !="1") }
                             <span  class="nl_red_icon_spl_offer_tag prd_list_nl_spc_org_txt"><a class="nl_prc_red_icon_spl" href="{"products.view?product_id=`$product.product_id`"|fn_url}" onClick="_gaq.push(['_trackEvent', 'Homepage', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.topproductlisting.iteration}']);">{$lang.see_special_offer_inside}</a></span>

                             {elseif $product.price_see_inside =="1" && $product.special_offer_badge =="1" }
    <span  class="nl_red_icon_spl_offer_tag prd_list_nl_spc_org_txt"><a class="nl_prc_red_icon_spl" href="{"products.view?product_id=`$product.product_id`"|fn_url}" onClick="_gaq.push(['_trackEvent', 'Homepage', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.topproductlisting.iteration}']);">{$lang.see_special_offer_inside}</a></span>

                            {/if}



	</div>
	<div class="product-info product_list_imag_height">
		{if $js_product_var}
			<input type="hidden" id="product_{$obj_prefix}{$product.product_id}" value="{$product.product}" />
		{/if}
		{if $item_number == "Y"}<strong>{$smarty.foreach.products.iteration}.&nbsp;</strong>{/if}
		{assign var="name" value="name_$obj_id"}{$smarty.capture.$name}



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

        <div class="clearboth"></div>
        <div id="" class="labe_discount_list_position {$styles} {if $after_apply_promotion !=0} third_price_discount {/if}{if !$config.fb_user_engagement && $product.product_amount_available == 0}sold_out_category{/if}">
		<span id="prc_discount_value_label_{$obj_prefix}{$product.product_id}" style="float:left; margin-top:5px; margin-left:3px; width:90%; text-align:center;">{$disc_perc}%</span>
        <span style="float:left; font:bold 11px arial; margin-top:-4px; margin-left:10px;">Off</span>
        </div>
    {*{/if}*}
{/if}



		{assign var="sku" value="sku_$obj_id"}{$smarty.capture.$sku}



		<div class="prod-info">
			<div class="prices-container clear {if $product.is_wholesale_product == 1 && !empty($product.retail_price) && $product.retail_price > $product.price && $product.retail_price < $product.list_price}list_view_prc_blk{/if}">
                <div class="float-right right add-product pro_list_mng" style="text-align:center;">
                {assign var="add_to_cart" value="add_to_cart_`$obj_id`"}
                {$smarty.capture.$add_to_cart}
                </div>
				<div class="float-left product-prices" style="padding-top:2px; padding-right:2px;">
					{assign var="old_price" value="old_price_`$obj_id`"}
					{if $smarty.capture.$old_price|trim}
                                            <span class="lst_prc_blk" style="padding-top:2px;">
                                                {$smarty.capture.$old_price}
                                            </span>&nbsp;
                                        {/if}
                                        {if $product.is_wholesale_product == 1 && !empty($product.retail_price) && $product.retail_price > $product.price && $product.retail_price < $product.list_price}
                                            {assign var="retail_price" value="retail_price_`$obj_id`"}
                                            {if $smarty.capture.$retail_price|trim}
                                                <span class="lst_prc_blk no_mobile" style="padding-top:2px;">
                                                    {$smarty.capture.$retail_price}
                                                </span>&nbsp;
                                            {/if}
                                        {/if}
					{assign var="price" value="price_`$obj_id`"}
					{*$smarty.capture.$price*}

					{assign var="clean_price" value="clean_price_`$obj_id`"}
					{$smarty.capture.$clean_price}

					{assign var="list_discount" value="list_discount_`$obj_id`"}
					{$smarty.capture.$list_discount}
				</div>

			{if $after_apply_promotion !=0}
				<span class="box_GridProduct_price" style="font:12px 'Trebuchet MS', Arial, Helvetica, sans-serif; color:#636566; text-decoration:line-through; padding: 2px 10px 0 0; float:left;">{$product.price|format_price:$currencies.$secondary_currency:""}</span>
				{if $product.price_see_inside ==0}
                                    <span class="box_GridProduct_priceoffer" style="color:#990000;">
                                        {$after_apply_promotion|format_price:$currencies.$secondary_currency:""}
                                    </span>

				{else}
					<span class="list_blue_deal_nl"><a style="color:#fff!important;" href="{"products.view?product_id=`$product.product_id`"|fn_url}" onClick="_gaq.push(['_trackEvent', 'Homepage', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.topproductlisting.iteration}']);">{$lang.click_to_see_inside}</a></span>
				{/if}
			{else}
{if $product.is_wholesale_product == 1 && !empty($product.retail_price) && $product.retail_price > $product.price && $product.retail_price < $product.list_price}

<label class="lst_prc_list_view list-price">{$lang.final_price_list_view}</label>{/if}
				<span class="product_homepagedealblock_pricing_offerprice" style="color:#990000; padding-top:2px; float:left;">
                                    {$product.price|format_price:$currencies.$secondary_currency:""}
                                </span>
                            {if $product.price_see_inside =="1" && (isset($product.special_offer_text) && !empty($product.special_offer_text)) }
                                <span  class="list_blue_deal_nl"><a style="color:#fff!important;" href="{"products.view?product_id=`$product.product_id`"|fn_url}" onClick="_gaq.push(['_trackEvent', 'Homepage', 'Click', '{$block.description|replace:" ":"_"}_{$smarty.foreach.topproductlisting.iteration}']);">{$lang.click_to_see_inside}</a></span>
    			{/if}
			{/if}	

         
                
			</div>
			{if $settings.Appearance.in_stock_field == "N"}
				{assign var="product_amount" value="product_amount_`$obj_id`"}
				{$smarty.capture.$product_amount}
			{/if}
			<div class="product-descr">
				<div class="strong">{assign var="product_features" value="product_features_`$obj_id`"}{$smarty.capture.$product_features}</div>
				{assign var="prod_descr" value="prod_descr_`$obj_id`"}{$smarty.capture.$prod_descr}
			</div>
			{if $settings.Appearance.in_stock_field == "Y"}
				{assign var="product_amount" value="product_amount_`$obj_id`"}
				{$smarty.capture.$product_amount}
			{/if}
			
			{if !$smarty.capture.capt_options_vs_qty}
			{assign var="product_options" value="product_options_`$obj_id`"}
			{$smarty.capture.$product_options}
			
			{assign var="qty" value="qty_`$obj_id`"}
			{$smarty.capture.$qty}
			{/if}
			
			{assign var="advanced_options" value="advanced_options_`$obj_id`"}
			{$smarty.capture.$advanced_options}
			
			{assign var="min_qty" value="min_qty_`$obj_id`"}
			{$smarty.capture.$min_qty}
			
			{assign var="product_edp" value="product_edp_`$obj_id`"}
			{$smarty.capture.$product_edp}
		</div>
		
	</div>
	{if $bulk_addition}
	<script type="text/javascript">
	//<![CDATA[
		$('#opt_' + '{$obj_prefix}{$product.product_id} :input').each(function () {$ldelim}
			$(this).attr("disabled", true);
		{$rdelim});
	//]]>
	</script>
	{/if}
	{assign var="form_close" value="form_close_`$obj_id`"}
	{$smarty.capture.$form_close}
</div>
{if !$smarty.foreach.products.last}

{/if}
{/hook}

{/foreach}

{if $bulk_addition}
{literal}
<script type="text/javascript">
//<![CDATA[
	$('.cm-item').click(function () {
		(this.checked) ? disable = false : disable = true;
		
		$('#opt_' + $(this).attr('id').replace('bulk_addition_', '')).switchAvailability(disable, false);
	});
//]]>
</script>
{/literal}
{/if}

{if !$no_pagination}
	{include file="common_templates/pagination.tpl"}
{/if}

{/if}

{capture name="mainbox_title"}{$title}{/capture}


{if $controller=='products' and $mode=='search'}

{literal}
<script language="javascript">
function productCookie(product_id,position,last_price)
{
	//fn_capture_click_on_product(product_id,type,keyword,position,elevate);

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
