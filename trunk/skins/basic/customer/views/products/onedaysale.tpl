{literal}<style>
.product-list-field ,margin_top_5{padding:0; width:60%;}
.margin_top_5 label , .product-list-field label{text-align:left; width:70px!important; margin:0;}
.margin_top_5 select , .product-list-field select{margin:0; width:160px;}
.margin_top_5  input , .product-list-field input{margin:0; width:150px;}
.margin_top_5  input[type="radio"] , .product-list-field input[type="radio"]{width:auto; margin:0}
.margin_top_5  input[type="check"] , .product-list-field input[type="check"]{width:auto; margin:0}
.margin_top_5 .pj2_desc_textbox , .product-list-field .pj2_desc_textbox {margin-left:75px;}
.onedaysale_btn a.cm-submit-link {position: absolute; bottom: -7px; right:-29px; line-height:50px!important; font-size:18px!important; padding-left:67px!important;}
.timerarea .odptimerbg .odpdigit span{padding:0 0 0 29px}
.new_name_seller_nl .mconame{float:left;} 
.new_name_seller_nl .trm_clk{float:left; margin-top:-3px;}
.reg_bg { background:#FF9a9a!important}
.mid_bigimg img{ vertical-align:middle;}
.margin_top_5  ul , .product-list-field ul{width:300px!important;}
.margin_top_5  textarea , .product-list-field textarea{width:230px!important;}
.new_blk_nl_desc div{width:90%!important; margin:0;}
.midcon h2{margin:-1px 0 5px 0px!important}
.new_blk_nl_desc{}
</style>
{/literal}
{* $Id: onedaysale.tpl 10654 2010-09-16 10:54:41Z klerik $ *}
{assign var="obj_id" value=$product.product_id}
{include file="common_templates/product_data.tpl" product=$product show_product_options="true" show_add_to_cart="true" show_qty=true show_product_amount="true" details_page="true" one_day_sale=true}
<script type="text/javascript" src="{$config.ext_js_path}/js/exceptions.js" ></script>
<div class="maincon clearfix" style="{if !$config.isResponsive}width:755px;{/if}">
{if $config.isResponsive}
    	
            {if $current_avail_sale == 'NO'}
                <span class="crkr_deal_red crkr_deal_tagline">{$lang.sale_is_ended}</span>
                        {else}
                            <span class="odpdigit crkr_deal_grn crkr_deal_tagline">Time Left - <span id="onedaysale_timer" ></span></span>
        	{/if}
        
        <script type="text/javascript">
                //<![CDATA[	
                //$(document).ready(function() {$ldelim}
                    // create a new date and insert it
                    var endtime= '{$product.one_day_sale_end_datetime}';
                    endtime = endtime.replace(/\-/g,'/');
                    var EndDate = new Date(endtime);
                    $.countdown('#onedaysale_timer', EndDate);
			//{$rdelim});	
			//]]>
		</script>

        {/if}
<div style="float:left; {if $config.isResponsive}width:100%;{else}width:756px;{/if}">
<div class="lft_con">
	<div class="imgonedaysale"><img src="{$config.ext_images_host}/images/skin/deaily_cracker_deal_logo.jpg" alt="" /></div>
       {if $product.image_pairs|count > 0}
           <ul class="prothumbbox">
                <li class="thumbpro">
                    <img src="{$config.ext_images_host}{$product.main_pair.detailed.http_image_path}"  alt="" />
                </li>
                {foreach from=$product.image_pairs item="images" name="img_foreach"}
                	{if $smarty.foreach.img_foreach.iteration <=7}
                    <li class="thumbpro"><img src="{$config.ext_images_host}{$images.detailed.image_path}" alt="" /></li>
                    {/if}
                {/foreach}
            </ul>
        {/if}
        {if !$config.isResponsive}
         {if !empty($upcoming_sale)}
         <div class="next_upcoming_sale">
    <div class="pj2_ods_review_block" style="margin:10px 0 0 0px;">
       
		<p style="padding: 5px 10px; width: 215px; background: #999; color: white; font:13px/16px 'Trebuchet MS', Arial, Helvetica, sans-serif;">{$lang.next_deal_title}</p>
            
            </div>
            <div class="odp_nxprorgt" style="width:223px; height:auto;  border-top:0; border:1px solid #eee;">
                <div class="odp_nxpbox" style="width:180px; margin:auto; margin-top:5px; border:1px solid #cfcfcf; ">
                    <h4 style="font:bold 14px/18px 'Trebuchet MS', Arial, Helvetica, sans-serif">{$upcoming_sale.product|unescape}</h4>
                    <p style="margin-top:5px;">
                        {include file="common_templates/image.tpl" image_height="80" obj_id=$obj_id_prefix images=$upcoming_sale.images object_type="product" show_thumbnail="Y"}
                    </p>
                    <p>{$upcoming_sale.short_description|unescape}</p>
                   
                </div>
                 <ul class="odp_box" style="padding:5px 17px; color:#333;">
                	{$upcoming_sale.one_day_sale_short_text|unescape}
            	</ul>
            </div>
            </div>
       {/if}{/if}
    </div> 
	
<div class="mid_con">
        <h2 style="margin:-1px 0 7px 0px;">{$product.product}</h2>
        <span id="product_images_{$product.product_id}_update" class="cm-reload-{$product.product_id}">
            <ul style="margin-bottom:20px;">
                <li  class="mid_bigimg">
                <img src="{$config.ext_images_host}{$product.main_pair.detailed.image_path}" alt="" /></li>
            </ul>
        </span>
{if !$config.isResponsive}
     {assign var="form_open" value="form_open_`$obj_id`"}
		{$smarty.capture.$form_open}
                {if $product.special_offer != ""}
            <div class="special_offer_link">
            <a href="{"products.onedaysale"|fn_url}#special_offer" title="{$lang.special_offer}">{$product.special_offer_text|unescape}</a>          
            </div>
        {/if}
        
        
        <div style="float:left;width:100%; position:relative; margin-top:20px; " id="hide_mar_top">
        {if $product.amount > 0}
            {assign var="product_options" value="product_options_`$obj_id`"}
			{$smarty.capture.$product_options}
        {/if}
        {assign var="qty" value="qty_`$obj_id`"}
		{$smarty.capture.$qty}
        
        
		<div class="onedaysale_btn" style="position:absolute; bottom:6px; right: 0px; width:auto;">
        {if $current_avail_sale != 'NO'}
            {if $product.amount > 0}
                {assign var="add_to_cart" value="add_to_cart_`$obj_id`"}
                {$smarty.capture.$add_to_cart}
                {assign var="list_buttons" value="list_buttons_`$obj_id`"}
                {$smarty.capture.$list_buttons}
            {else}
            	{$lang.prod_sold_out}
            {/if}
        {else}
        	{$lang.oneday_pro_sold_out}
        {/if}
        </div>
		</div>
        
      {/if}  
        	
            
        <!--<div class="buynow_btn"><input name="buy now" type="image" src="images/skin/buynow.jpg" /></div>-->
    </div>
     {if $config.isResponsive}
    {assign var="form_open" value="form_open_`$obj_id`"}
		{$smarty.capture.$form_open}
<div class="mobile mobile_pricearea">
    <div class="pricearea">
    	
       	<p>List Price : <span class="linethrough">{$product.list_price|format_price:$currencies.$secondary_currency:""}</span>/-</p>
        

       
		{assign var="after_apply_promotion" value=0}
		{if $product.promotion_id !=0}
			{assign var="after_apply_promotion" value=$product|fn_get_3rd_price}
		{/if}
		{if $after_apply_promotion != 0}
	       		<p>{$lang.loot_price} :
		        <strike><span class="linethrough odprice">{$product.price|format_price:$currencies.$secondary_currency:""}/-</span></strike>
		        </p>
                <p class="price" style="padding:6px 0 10px 0; font: bold 15px/13px Trebuchet MS, Arial, Helvetica, sans-serif; color: #900; text-decoration: none;">{$lang.cracker_Price}:&nbsp;Rs. {$after_apply_promotion}/-
                </p>
		{else}
	       		<p>{$lang.loot_price} :
		        <span class="odprice linethrough " >{$product.price|format_price:$currencies.$secondary_currency:""}/-</span> 
		        </p>
		{/if}
                <div class="stock">
                    <div class="float-left in-stock">      
                 {assign var="product_amount" value="product_amount_`$obj_id`"}
                {$smarty.capture.$product_amount}
                </div>
         	<div class="float-right productShippingDetails">
                {if $product.shipping_freight > 0 && $product.free_shipping == 'N'}
                {$lang.shipping_cost} :
		        	<span style="text-decoration:none;" >{$product.shipping_freight|format_price:$currencies.$secondary_currency:""}/-</span> 
                {else}
                		{$lang.free_shipping}
                {/if}
        </div>
                </div>
</div>

                {if $product.special_offer != ""}
            <div class="special_offer_link">
            <a href="{"products.onedaysale"|fn_url}#special_offer" title="{$lang.special_offer}">{$product.special_offer_text|unescape}</a>          
            </div>
        {/if}
        
        
        <div style="float:left;width:100%; position:relative; margin-top:20px; " id="hide_mar_top">
        {if $product.amount > 0}
            {assign var="product_options" value="product_options_`$obj_id`"}
			{$smarty.capture.$product_options}
        {/if}
        {assign var="qty" value="qty_`$obj_id`"}
		{$smarty.capture.$qty}
        
        
		<div class="onedaysale_btn" style="bottom:6px; right: 0px; width:auto;">
        {if $current_avail_sale != 'NO'}
            {if $product.amount > 0}
                {assign var="add_to_cart" value="add_to_cart_`$obj_id`"}
                {$smarty.capture.$add_to_cart}
                {assign var="list_buttons" value="list_buttons_`$obj_id`"}
                {$smarty.capture.$list_buttons}
            {else}
            	{$lang.prod_sold_out}
            {/if}
        {else}
        	{$lang.oneday_pro_sold_out}
        {/if}
        </div>
		</div>
</div>
    {assign var="form_close" value="form_close_`$obj_id`"}
    	{$smarty.capture.$form_close}
            {/if}
</div>	

<div style="width:100%;float:left;">
<div style="float:left; width:100%;" class="new_blk_nl_desc"> 
        {if $product.special_offer != ""}
            <div style="float:left; width:97%;  background-color:#f2f2f2; margin-top:20px; padding:5px 10px; ">
            <h1 style="margin-top:0px;" class="tab-list-title ancor_mng"><a name="special_offer">Special Offer</a></h1>
            {$product.special_offer|unescape}
            </div>
          
            
        {/if}</div>


  
</div>
</div>
{if !$config.isResponsive}
<div class="rght_con" style="padding:5px 0 ;">
	<div class="timerarea">
    	<ul class="odptimerbg" style="padding:0 0 0 0px; margin:0; width:238px;">
        	<li><div class="odpdigit" id="onedaysale_timer" style="margin-left:10px;"></div></li>
            {if $current_avail_sale == 'NO'}
        		<li><span style="display:block; height:35px; overflow:hidden; text-align:center; padding:15px 0 0 0; clear:both; color:#ff0000; ">{$lang.sale_is_ended}</span></li>
        	{/if}
        </ul>
        
        <script type="text/javascript">
			//<![CDATA[	
			//$(document).ready(function() {$ldelim}
				// create a new date and insert it
				var endtime= '{$product.one_day_sale_end_datetime}';
				endtime = endtime.replace(/\-/g,'/');
				var EndDate = new Date(endtime);
				$.countdown('#onedaysale_timer', EndDate);
			//{$rdelim});	
			//]]>
		</script>
        
        
         {assign var="form_close" value="form_close_`$obj_id`"}
    	{$smarty.capture.$form_close}
        
        {assign var="product_amount" value="product_amount_`$obj_id`"}
        {$smarty.capture.$product_amount}
            
        
        
    </div>
    <div class="odp_pricedtls">
    	
       	<p>MRP : <span class="linethrough">{$product.list_price|format_price:$currencies.$secondary_currency:""}</span>/-</p>
        

       
		{assign var="after_apply_promotion" value=0}
		{if $product.promotion_id !=0}
			{assign var="after_apply_promotion" value=$product|fn_get_3rd_price}
		{/if}
		{if $after_apply_promotion != 0}
	       		<p>{$lang.loot_price} :
		        <strike><span class="odprice" style="color:#2C2B2B; font:normal 13px Trebuchet MS, Arial, Helvetica, sans-serif">{$product.price|format_price:$currencies.$secondary_currency:""}/-</span></strike>
		        </p>
                <p class="price" style="padding:6px 0 10px 0; font: bold 15px/13px Trebuchet MS, Arial, Helvetica, sans-serif; color: #900; text-decoration: none;">{$lang.cracker_Price}:&nbsp;Rs. {$after_apply_promotion}/-
                </p>
		{else}
	       		<p>{$lang.loot_price} :
		        <span class="odprice" >{$product.price|format_price:$currencies.$secondary_currency:""}/-</span> 
		        </p>
		{/if}
         		{if $product.shipping_freight > 0 && $product.free_shipping == 'N'}
                <p style="padding-left:20px; clear:both;"><span style="font-weight:bold; text-decoration:none; ">(+)</span> {$lang.shipping_cost} :
		        	<span style="text-decoration:none;" >{$product.shipping_freight|format_price:$currencies.$secondary_currency:""}/-</span> 
		        </p>
                {else}
                		<p>{$lang.free_shipping}</p>                
                {/if}
                
    </div>  
</div>

<div style="float:right;">
<div class="odp_sucrb" style="background:none; border:1px solid #ccc; margin-top:10px; width:234px; float:right; padding-bottom:4px;">
        <form name="oneday_registration_form" action="{""|fn_url}" method="post" onsubmit="return validate();" style="float:left;">
            <div class="lightbox_container_bg" style="float:left;">
                <div class="pj2_submit_email_box" style="margin-top:0;">
                    <img src="{$config.ext_images_host}/images/skin/pjw_send_email.gif" style="float:left; margin:0 5px 0  0" />
                    <ul class="get_the_pj2">
                        <li>Get the </li>
                        <li><span class="best_offer">best offers </span>in town</li>
                    </ul>
                        <input type="hidden" name="dispatch" value="simple_registration.register" />
                        <input type="hidden" name="referer" value="subscribe" />
                        <input value="Your Email Address" name="email" id="oneday_email_registration" type="email" class="lightbox_container_textbox  pj2_new_textbox_block" />
                        <input name="" id="oneday_popup_submit" type="submit" class="lightbox_container_button pj2_od_go_btn" src="{$config.ext_images_host}/images/skin/pj2_go_btn_prd_page.gif" value="" style="cursor:pointer; margin:3px 9px 0 0!important" />

                </div>
            </div>
        </form>
    </div>
    
    <div class="odp_rghtcon" style="clear:both; margin-top:10px; float:right; width:242px;">
        {$lang.what_do_you_like_to_see_next}
    </div>
    </div>
{/if}



<div class="odp_bottom" style="padding:0; width:100%; clear:none; margin-top:25px;">
	<div class="review_text_ods_pj2" style="width:100%;">
		
	<div style="float:left; width:100%">
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
		<div style="float:right; width:240px;"></div>
</div>










{literal}
<script type="text/javascript">
$(function(){ 
	 $('.thumbpro').hover(function(){
			var img_src = $(this).children().attr("src");
			$('.mid_bigimg img').attr("src",img_src);
			return false;
	});
	
	$('#oneday_email_registration').focusin(function(){
		var input_value = $('#oneday_email_registration').val();
		if(input_value == 'Your Email Address') {
			$('#oneday_email_registration').val('');	
		}
	});
	
	$('#oneday_email_registration').focusout(function(){
		var input_value = $.trim($('#oneday_email_registration').val());
		if(input_value == '') {
			$('#oneday_email_registration').val('Your Email Address');	
		}
	});
});



function validate(){
            var val = document.getElementById('oneday_email_registration').value;
            val = jQuery.trim(val);
            document.getElementById('oneday_email_registration').value = val;
            if(val== '' || val=='Your Email Address'){ 
					//document.getElementById('oneday_email_registration').value = 'chandan';
                    //document.getElementById('oneday_email_registration').style.color = "#333";
                    //document.getElementById('oneday_email_registration').style.background = "#FFDDDD";
                    //document.getElementById('oneday_email_registration').value = 'Your Email Address';
					$('#oneday_email_registration').css('color','#333');
					$('#oneday_email_registration').addClass('reg_bg');
					//$('#oneday_email_registration').css('display','none');
                    return false;
            }else{
               var pattern=/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
				if(pattern.test(val)){         
					return true;   
				}else{   
					document.getElementById('oneday_email_registration').style.color = "#333";
                    document.getElementById('oneday_email_registration').style.background = "#FFF";
                    document.getElementById('oneday_email_registration').value = 'Your Email Address';
                    return false; 
				}
            }
        }
</script>
{/literal}
<div class="hide_trm" style="position: fixed; z-index: 200; background-image: url(http://www.shopclues.com/images/skin/background_for_banklist.png); left: 0px; top: 0px; width: 100%; min-height: 100%; background-position: initial initial; background-repeat: initial initial; display:none;">
<div style=" width:500px; margin:auto;">
<div class="pj2_popup_prd" style="width: 500px; float: left; background: white url(images/skin/buyer_payment_bg.gif) center no-repeat; border-radius: 5px; -moz-border-radius: 5px; padding: 10px; margin-top: 179px; border: 3px solid #444;">
<img class="img_close" src="{$config.ext_images_host}/images/skin/pj2_close_btn_banklist.png " style="float: right; margin: -25px -25px 0 0; cursor: pointer;">
<p style="font:16px/22px trebuchet MS; color:#000; display:block; text-align:left; padding:0; font-weight:bold; margin:0px 0 0 10px;">What does Top Rated Merchant mean to me?</p> 

<ul class="content" style="float: left; color: #454545; margin: 0; padding: 0 10px; font:11px/20px Verdana, Geneva, sans-serif; width: auto;">
    <li>
    <img src="{$addons.sdeep.trm_icon_url}" alt="Top Rated Merchant" title="Top Rated Merchant" style="float:left; margin-right:5px;">
    Any time you see the TRM seal next to a merchant, rest assured that this merchant has been rated excellent on all possible parameters by our team and by the ShopClues community. Our team has verified that the merchant exhibits highest standards for customer service, return, pricing, brands/selection etc, and you will have an excellent shopping experience with this merchant.</li>
</ul>
<a href="http://www.shopclues.com/index.php?dispatch=pages.view&amp;page_id=65" target="_blank" style="float:right">Know more</a>
</div>
</div>

</div>
{literal}
<script type="text/javascript">
$(document).ready(function(){

$('.trm_clk').click(function(){
    $(".hide_trm").show();
    });
	$('.img_close').click(function(){
		$(".hide_trm").hide();
		});
	$('.content').click(function(){
		$(".content").show();
		});			
});
</script>
{/literal}