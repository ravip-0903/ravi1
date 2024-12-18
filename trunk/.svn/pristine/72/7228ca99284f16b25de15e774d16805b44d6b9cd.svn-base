
{* auction.tpl code by arpit gaur *}
{assign var="obj_id" value=$product.product_id}
{include file="common_templates/product_data.tpl" product=$product show_product_options="true" show_add_to_cart="true" show_qty=true show_product_amount="true" details_page="true"}
<script type="text/javascript" src="{$config.ext_js_path}/js/exceptions.js" ></script>

<div id="notification_container" class="cm-notification-container">
	
</div>


<div class="maincon clearfix">

	{if $auction_details}
	<div class="auction_main_blk">
		<div style="" class="prd_pic_blk">
        {if !$no_images}
			{if $config.isResponsive}
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
		<div class="jcarousel-prev jcarousel-prev-horizontal jcarousel-prev-disabled jcarousel-prev-disabled-horizontal" disabled="true" style="display: block; top: 170px;"></div>
		<div class="jcarousel-next jcarousel-next-horizontal jcarousel-next-disabled jcarousel-next-disabled-horizontal" disabled="true" style="display: block; top: 170px;"></div>
				<!--product_images_{$product.product_id}_update--></div>
				{/if}

				<div class="prd-add-thm no_mobile image-border prew_mng float-left center cm-reload-{$product.product_id} img_cntr_new_div" id="product_images_{$product.product_id}_update">
							
				{include file="views/products/components/product_images.tpl" product=$product show_detailed_link="Y"}
				<!--product_images_{$product.product_id}_update--></div>
			{/if}
        
		</div>
		<div class="prd_dtl">
			<h1 class="heading">{$product.product}</h1>
			<div class="prc_blk">
				<div class="blk">
					<div class="lft">Price :</div>
					<div class="rgt">{$product.list_price|format_price:$currencies.$secondary_currency:""}</div>
				</div>
				<div class="blk">
					<div class="lft">Start Bid :</div>
					<div id="minimum_price" class="rgt">{$auction_details.minimum_price|format_price:$currencies.$secondary_currency:""}</div>
				</div>
				<div class="blk">
					<div class="lft">Current Bid :</div>
					<div id="current_maximum_amount" class="rgt"></div>                        
				</div>
			</div>
			
			<div class="bid_blk">
				<div class="lft">
					<div>
						<span>Enter your bid</span>
						<input id="bid_amount" type="text" placeholder="">
					</div>
				</div>
				<div class="rgt">
					<div id="increment_bid" class="plus"></div>
					<div id="decrement_bid" class="minus"></div>
				</div>
				<label class="sml_lnk">Enter  Rs. <span id="bid_amount_desc"></span> or more
					<a href="{"auction_terms_and_conditions"|fn_get_lang_var}">T and c</a>
				</label>
			</div>
			
			<div class="bid_btns">
				<input type="button" id="place_bid_button" class="bid_button">
				<input id="buy_now" type="button" class="buy_now">
			</div>
		</div>
		
		<div class="prd_right">
	        
			<div class="counter odpdigit" id="auction_timer" >
				<span class="hour">00</span>
				<span class="minute">00</span>
				<span class="second">00</span>
			</div>
			<div class="lrg_blk">
				<span id="total_bid_count">0</span>
				<label>Total Bids Till Now</label>
			</div>
			<div style="display:none;" class="lrg_blk">
				<span>255</span>
				<label>Viewer on this page</label>
			</div>
			
			{if $smarty.config.show_auction_winner}
			<div class="winner_blk">
				<div class="winner_tag">Winer</div>
				<div class="winner_name">Sumit Vijay</div>
				<div class="fnl_bd">12,000</div>
				<div class="last_auc_prd"><img src="images/last_prd_name.jpg" alt=""  title=""></div>
				<div class="last_auc_name"><a href="">Samsung NP355E5X-A02IN Laptop (APU Dual Core/ 2GB/ 500GB/ DOS)</a></div>
			</div>
			{/if}
			
		</div>
	</div>
	{/if}
		
	
	
	{assign var="auction_language_1" value="auction_language_1"|fn_get_lang_var}
	<div id="auction_language_1">
		{$auction_language_1}
	</div>
	
	{if $smarty.config.show_auction_banner_1}
	<div id="auction_banner1">
		<img src="http://cdn.shopclues.com/images/auction_images/banner1.jpg" />
	</div>
	{/if}
	
	{if $smarty.config.show_auction_banner_2}
	<div id="auction_banner2">
		<img src="http://cdn.shopclues.com/images/auction_images/banner2.jpg" />
	</div>
	{/if}
	
	{assign var="auction_language_2" value="auction_language_2"|fn_get_lang_var}
	<div id="auction_language_2">
		{$auction_language_2}
	</div>
	
	
	{if $auction_details}
	<div class="odp_bottom" style="padding:0; width:750px; clear:none; margin-top:25px;">
		<div class="review_text_ods_pj2" style="width:100%;">
			
		<div style="float:left; width:742px">
			<h1 class="ml_pageheaderCateogry_heading_oneday" style="margin-top:0;">Description</h1>
			<div class="wysiwyg-content">{$product.full_description|unescape}</div>
			
			<h1 class="ml_pageheaderCateogry_heading_oneday">Features</h1>
			<div class="wysiwyg-content">
			{foreach from=$product.product_features item="feature"}
				{if isset($feature.variants)}
				<div class="row_productfeatures">
					<div class="form-field">
						<label style="margin-left:-160px;">{$feature.description} : </label>
						{foreach from=$feature.variants item="variants"}
							{$variants.variant}
						{/foreach}<br />
					</div>
				</div>
				{/if}   
			{/foreach}
			<div class="clearboth height_twenty"></div>
			</div>
			
			{foreach from=$product.product_features item="feature"}
				{if isset($feature.subfeatures)}
					<h2 class="subheader">{$feature.description}</h2>
					<div class="row_productfeatures">
						<div class="form-field">
						{foreach from=$feature.subfeatures item="subfeatures"}
							<label style="margin-left:-160px;">{$subfeatures.description} : </label>
							{foreach from=$subfeatures.variants item="subvariants"}
								{$subvariants.variant}
							{/foreach}
							
							<br />
						{/foreach}</div>
			
					</div>
					<div class="clearboth height_twenty"></div>
				{/if}
			{/foreach}
		</div>
	</div>
	{/if}

</div>

<div id="auction_login_box" class="anniversary_lightbox_container" style="display:none;width: 581px; 
z-index: 10001; 
position: fixed; 
left: 50%; 
margin-left: -325px; 
top: 100px; 
background: #FFF; 
box-shadow: 0px 0px 20px #000;"> 
	{include file="views/auth/fb_login.tpl"}
</div>



{if $upcoming_auctions}
	<div class="block-packs-general">
	<h1 class="block-packs-title">
		<span style="float:left;">Upcoming Auctions</span>
	 </h1>
	 
	<div class="block-packs-body">
		<div class="box_manualdeals" style="width:100%;">
		<!--Product slider -->

			<div class="slider_manualdeals jcarousel-skin-tango">
				<div class="jcarousel-container jcarousel-container-horizontal" style="position: relative; display: block;">
					<div class="jcarousel-clip jcarousel-clip-horizontal" style="position: relative;">
						<ul id="scroller_169" class="jcarousel-list jcarousel-list-horizontal" style="overflow: hidden; position: relative; top: 0px; margin: 0px; padding: 0px; left: 0px; width: 2662px;">
            
							{foreach from=$upcoming_auctions item="upcoming_auction"}
							<li style="float: left; list-style: none;" class="jcarousel-item jcarousel-item-horizontal jcarousel-item-1 jcarousel-item-1-horizontal" jcarouselindex="1">
								<div class="box_GridProduct" style="margin-left:0px; margin-top:15px;">
									<div class="clearboth"></div>
					
									<a href="javascript:void(0)" class="box_GridProduct_product ">
										<img class="" src="http://cdn.shopclues.com/{$upcoming_auction.detailed_image_url}" width="160" height="160" border="0" alt="{$upcoming_auction.product}" title="{$upcoming_auction.product}">
									</a>
									<a href="javascript:void(0)" class="box_GridProduct_link" alt="{$upcoming_auction.product}" title="{$upcoming_auction.product}" >{$upcoming_auction.product}</a>

									<div class="box_GridProduct_pricing">
										<span class="">Starting on {$upcoming_auction.start_date}</span>                             
									</div>
								</div>
							</li>
							{/foreach}
		    
						</ul>
					</div>
				</div>

				<div class="clearboth height_ten"></div>
			</div>
		</div>
	</div>
</div>
{/if}





{if $auction_details}
{literal}
<script type="text/javascript">
	$('#auction_login_box').hide();
	
	var auction_id={/literal}{$auction_details.id}{literal};
	var current_maximum_amount=0;
	var current_time=0;
	var end_date=0;
	var error=0;
	var maximum_increment=0;
	var minimum_increment=0;
	var minimum_price=0;
	var start_date=0;
	var total_bid_count=0;
	var endtime= '';
	var new_minimum_bid=0;
	
	$(document).ready(function(){refresh_auction_data();setInterval(function(){refresh_auction_data()},{/literal}{$config.auction_data_refresh_frequency}{literal});});
	
	var refresh_counter=0;
	function refresh_auction_data()
	{
		$.post( "getauction.php", { "action":"1","auction_id":auction_id },function( data ) {
		  //alert( "Data Loaded: " + data );
		  try
		  {
				auction_obj = JSON && JSON.parse(data) || $.parseJSON(data);
				
				if(!auction_obj.error)//no error | refresh the data
				{
					auction_id=parseInt(auction_obj.auction_id);
					minimum_price=parseInt(auction_obj.minimum_price);
					current_maximum_amount=parseInt(auction_obj.current_max_amount);
					maximum_increment=parseInt(auction_obj.max_increment);
					minimum_increment=parseInt(auction_obj.min_increment);
					total_bid_count=parseInt(auction_obj.total_bid_count);
					end_time=auction_obj.end_date;
					
					//refreshing minimum bid price
					$('#minimum_price').html('Rs. '+minimum_price);
					
					//refreshing current maximum amount
					$('#current_maximum_amount').html('Rs. '+current_maximum_amount);
					
					//refreshing bid amount
					new_minimum_bid=current_maximum_amount+minimum_increment;
					if($('#bid_amount').val()!='')
					{
						
					}
					else
					{
						$('#bid_amount').val('');
					}
					$('#bid_amount').attr('placeholder',new_minimum_bid);
					$('#bid_amount_desc').html(new_minimum_bid);
					
					//refreshing total bid count
					$('#total_bid_count').html(total_bid_count);
					
					//refreshing timer
					endtime=auction_obj.timer_end_time;
					//endtime='2014-02-30 11:25:00';
					endtime = endtime.replace(/\-/g,'/');
					var EndDate = new Date(endtime);
					$.countdown('#auction_timer', EndDate);
				}
				else //handle the error | alert and reload the page
				{
					set_notification('e','There was some error while refreshing the auction details. Try reloading the page.');
				}
		   }
		   catch(e) //handle the exception | reload the page
		   {
				set_notification('e','There was some error while refreshing the auction details. Try reloading the page.');
		   }
		});
	}
	
	//this function is responsible for javascript sanity checks and then placement of the bid
	$('#place_bid_button').click(function(){
	
		$.post( "index.php", { "dispatch":"products.place_bid","action": "place_bid","bid_amount":$('#bid_amount').val(),"auction_id":auction_id },function( data ) {
		try
		  {
				result_obj = JSON && JSON.parse(data) || $.parseJSON(data);
				
				if(!result_obj.error)
				{
					refresh_auction_data();
					$('#bid_amount').val('');
					$('#bid_amount').attr('placeholder',new_minimum_bid);
					set_notification('n','Your bid was successfully placed.');
				}
				else
				{
					if(result_obj.error_code=='INVALID_LOGIN')
					{
						$('#auction_login_box').show();
					}
					set_notification('e',result_obj.error_message);
				}
		   }
		   catch(e) //handle the exception | reload the page
		   {
				set_notification('e','There was some error while placing the bid. Try reloading the page and bidding again.');
		   }	
		
		});
	});
	
	
	//controls for the increment / decrement buttons
	$('#increment_bid').click(function(){
		if($('#bid_amount').val()!='')
		{
			temp_bid=parseInt($('#bid_amount').val())+minimum_increment;
		}
		else
		{
			temp_bid=minimum_increment+current_maximum_amount;
		}
		$('#bid_amount').val(''+temp_bid);
	});
	
	$('#decrement_bid').click(function(){
		temp_bid_amount=$('#bid_amount').val();
		if(temp_bid_amount=='')
		{
			temp_bid_amount=current_maximum_amount;
		}
		temp_bid=parseInt(temp_bid_amount)-minimum_increment;
		if(temp_bid<(current_maximum_amount+minimum_increment))
		{
			set_notification('e','You cannot place bid that is less than the current maximum bid.');
		}
		else
		{
			$('#bid_amount').val(''+temp_bid);
		}
	});
	
	$('#fb_login_close_signin').click(function(){$('#auction_login_box').hide()});
	
	//creating the notifications
	function set_notification(type,message)
	{
		var header='';
		if(type=='e')
		{
			header='Error';
		}
		else if(type=='n')
		{
			header='Success';
		}
		var notification_string="<div class='notification-content cm-auto-hide' id=''>"+
												"<div class='notification-"+type+"'>"+
													"<img class='cm-notification-close hand close_notification' src='/skins/basic/customer/images/icons/icon_close.gif' width='13' height='13' border='0' alt='Close'>"+
													"<div class='notification-header-"+type+"'>"+header+"</div>"+
													"<div class='notification-body'>"+message+"</div>"+
												"</div>"+
											"</div>";
		$('#notification_container').html('');
		$('#notification_container').append(notification_string);
		$('.close_notification').click(function(){
			$('#notification_container').html('');
		});
	}
	
</script>
{/literal}
{/if}

<div id="auction_banner">

</div>

<div id="upcoming_auction">

</div>



{literal}
<script type="text/javascript">
$(function(){ 
	 $('.thumbpro').hover(function(){
			var img_src = $(this).children().attr("src");
			$('.mid_bigimg img').attr("src",img_src);
			return false;
	});
});

</script>
{/literal}

