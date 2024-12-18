{* $Id: just_like_that_products_grid.tpl 11222 2010-11-16 11:53:42Z klerik $ *}
<div id="page_{$page_new}" class="ajaxified-pages">
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


	{if $products|sizeof < $columns}
	{assign var="columns" value=$products|@sizeof}
	{/if}
	{split data=$products size=$columns|default:"3" assign="splitted_products"}
	{math equation="100 / x" x=$columns|default:"3" assign="cell_width"}
	{if $item_number == "Y"}
	{assign var="cur_number" value=1}
	{/if}

	{assign var="total_count" value=$products|count}
	{assign var="key" value=0}
	{assign var="adword" value=""}

	{foreach from=$products item="product" name="sproducts" key="k"}
	{assign var="obj_id" value=$product.product_id}
	{assign var="obj_id_prefix" value="`$obj_prefix``$product.product_id`"}
	{include file="common_templates/product_data.tpl" product=$product}
	<!--added by sapna -->
	{if isset($smarty.request.page)}
	{math assign="key" equation="(x*z)+(y)" x=$smarty.request.page-1 y=$key+1 z=$product_per_page}

	{else}
	{assign var="key" value=$key+1}

	{/if}
	{assign var="elevate" value=""}
	{assign var="elevate" value=$product|fn_check_elevated:$smarty.request.q:$key:$smarty.request.page}

	{if $elevate ==1} {assign var="adword" value=$adword|cat:$product.product_id|cat:","} {/if}
	{if $smarty.foreach.sproducts.last && empty($smarty.request.page) && empty($smarty.request.sort_order) && !empty($adword)} <input type="hidden" id="ad_products" value="{$adword}" onclick="fn_show_product('{$adword}','{$smarty.request.q}')"/> {/if}
	<div class="box_metacategory scProduct">

		<div class="inner">
			<!-- Added by Ajay dt 1 nov 2012 to show special_offer_badge and deal_inside_badge -->
			{assign var="after_apply_promotion" value=0}
			{if $product.promotion_id !=0}
			{assign var="after_apply_promotion" value=$product|fn_get_3rd_price}
			{/if}

			<!--End Added By Ajay-->

			<!--Added by Sudhir to show category image dt 17 octo 2012-->
			{assign var="image_cat_id" value=$product.category_ids|fn_get_category_image}
			{assign var="image_cat" value="-"|explode:$image_cat_id}
			{if $image_cat.1}
			{if $image_cat.0 > 0}                    
			<img src="{$image_cat.1}">
			{$lang.cate_image_hover}
			{/if}
			{/if}
			<!--Added by Sudhir to show category image dt 17 octo 2012 end here-->

			{assign var="is_new" value=$product|check_product_for_new}
			{if $is_new == 'new'}
			{*<div>New</div> *}
			{/if}

			{assign var="is_ngo" value="$product.company_id|fn_check_merchant_for_ngo"}
			{if $is_ngo == 'Y'}
			<div>{$lang.ngo_popup_hover}</div> 
			{/if}
			<!-- Added By Sudhir dt 09 octo 2012 to show third price percentage-->
			{assign var="after_apply_promotion" value=0}
			{if $product.promotion_id !=0}
			{assign var="after_apply_promotion" value=$product|fn_get_3rd_price}
			{/if}<!-- Added By Sudhir end here-->

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

			<!-- Added By Sudhir dt 09 octo 2012 to show third price percentage-->

			{if $product.promotion_id !=0}
			{if $after_apply_promotion !=0}
			{assign var="disc_perc" value=$product|calculate_3rd_price_percentage:$after_apply_promotion}
			{/if}
			{/if}
			<!-- Added By Sudhir end here-->

			{if $disc_perc>=50}
			{assign var="styles" value="label_discount_grid_first"}
			{elseif $disc_perc>=0 and $disc_perc<=49}
			{assign var="styles" value="label_discount_grid_second"}
			{/if}

			{if $smarty.request.category_id != $lang.48hrsale_category_id}
			<div class="off" id="line_prc_discount_value_{$obj_prefix}{$obj_id}" {if $after_apply_promotion !=0}{/if}>
				<label id="prc_discount_value_label_{$obj_prefix}{$obj_id}">
					{$disc_perc}%
				</label>
				<span>Off</span>
			</div> 
			{/if}
			{/if} 

			<a class="image" href="{"products.view?product_id=`$product.product_id`"|fn_url}" id={$product.product_id}>
				{if $controller=='categories' and $mode=='view'}
				{assign var="pro_image" value=$product.product_id|fn_get_image_pairs:'product':'M'}
				{include file="common_templates/image.tpl" obj_id=$obj_id_prefix images=$pro_image object_type="product" show_thumbnail="Y" image_width="160" image_height="160"}
				{else}
				{include file="common_templates/image.tpl" obj_id=$obj_id_prefix images=$product.main_pair object_type="product" show_thumbnail="Y" image_width="160" image_height="160"}
				{/if}
			</a>

			<!--<div class="box_metacategory_name">-->
			{if $item_number == "Y"}
			{$cur_number}.&nbsp;{math equation="num + 1" num=$cur_number assign="cur_number"}
			{/if}
			<a href="{"products.view?product_id=`$product.product_id`"|fn_url}" class="name">
				{$product.product|truncate:40:"...."}
			</a>
			<!--</div>-->

			<div class="rating">
				{assign var="average_rating" value=$product.product_id|fn_get_average_rating:'P'}
				{if $average_rating}
				{include file="addons/discussion/views/discussion/components/top_banner_stars.tpl" stars=$average_rating|fn_get_discussion_rating}
				{/if}
			</div>

			<div class="details">
				<div class="social">
					{assign var="social_count" value=$product.product_id|fn_get_social_count}
					<span class="like">
						<label style=" vertical-align: top; ">{if $social_count.like_count==''}0{else}{$social_count.like_count}{/if}</label>
						{assign var="fb_like_url" value="products.view?product_id=`$product.product_id`"|fn_url}
						{assign var="fb_like_url" value="http://"|cat:$config.http_host|cat:$fb_like_url}
						<iframe src="//www.facebook.com/plugins/like.php?href={$fb_like_url}&amp;width&amp;layout=button&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=35&amp;appId={$config.shopclues_app_id}" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:20px;width:48px;" allowTransparency="true"></iframe>
					</span>
					<span class="facebookShare share" onclick="fbShare({$product.product_id})">
						<label>{if $social_count.share_count==''}0{else}{$social_count.share_count}{/if}</label>
						<a href="javascript:void();">Share</a>
					</span>
					<span class="comment">
						<label>{$social_count.comment_count}</label>
						<a onclick="show_jlt_comments({$product.product_id},{if $smarty.session.auth.user_id ==0|| $smarty.session.auth.user_id ==''}0{else}1{/if})">
							Comment
						</a>

					</span>
				</div>
				{assign var="final_price" value=''}
				<div class="price" id="jlt_price_{$product.product_id}">
					{if $product.list_price > $product.price}
					{assign var="final_price" value=$product.list_price|format_price:$currencies.$secondary_currency:""}
					{/if}  

					{if $after_apply_promotion !=0}
					{assign var="final_price" value=$after_apply_promotion|format_price:$currencies.$secondary_currency:""}
					{else}
					{assign var="final_price" value=$product.price|format_price:$currencies.$secondary_currency:""}
					{/if} 
					{$final_price}
				</div>
			</div>

			{if $category_data.show_feature == 'Y'}
			{assign var="key_features" value=$product.product_id|get_products_feature}
			{if $key_features|count > "0"}
			<ul>
				{foreach from=$key_features item="key_feature"}
				<li>{$key_feature.variant|truncate:20:"...."}</li>				
				{/foreach}
			</ul>
			{/if}
			{/if}



			{if $product.why_buy_reason_1 !='' && $product.why_buy_reason_2 !=''}
			<div class="whyBuy">
				<div class="icon"></div>
				<br />
				
				<div class="comments">
					<h4>{$lang.why_buy_resaon_1_text}</h4>
					<span>{$product.why_buy_reason_1}</span>
					<br />
					<h4>{$lang.why_buy_resaon_2_text}</h4>
					<span>{$product.why_buy_reason_2}</span>
				</div>
			</div>
			{/if}
		</div>
	</div>
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
<div class="clearboth"></div>

{capture name="mainbox_title"}{$title}{/capture}