{** block-description:sdeep_auth_dealer **}

{assign var="product_count" value=$product.company_id|fn_product_count}
{literal}
<script type="text/javascript">
$(document).ready(function(){
<!-- buy with confidence -->
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

<div class="produ_detai_right_b_mng pj2_top_border" style="margin-top:14px">
<div class="pj2_prd_seller_name" style="font:bold 14px Arial, Helvetica, sans-serif; background:none; color:#000;">Seller Detail
    {literal}
    <style>
    .bg_like_nl{height:25px; width:26px; float:right; margin:-5px 0 0 ; position:relative; cursor:pointer}
    .nl_tool_tip{border-radius:5px; -moz-border-radius:5px; background:#f8f8f8; border:1px solid #ccc; display:none; position:absolute; z-index:10000; width:150px; top: 23px;
right: -24px; color:#666; padding:2px 10px; text-align:center; box-shadow:3px 3px 5px #c6edff; -moz-box-shadow:3px 3px 5px #c6edff; font:11px/16px Verdana, Geneva, sans-serif;}
	.bg_like_nl.store_liked{background:url(images/skin/shopclues_seller_like_new_look.png) no-repeat -27px 0;}
    .bg_like_nl:hover{background-position: -27px 0px}
    .bg_like_nl.store_not_liked{background:url(images/skin/shopclues_seller_like_new_look.png);background-position: -55px 0}
	.bg_like_nl.store_not_liked:hover{background-position: -27px 0}
    .bg_like_nl:hover .nl_tool_tip{display:block!important}
    </style>
    {/literal}
    <div class="bg_like_nl " id="like_unlike_icon" style="margin-top: -3px; position: absolute; margin-right: 0px; right: 28px; top: 4px;">
     <input type="hidden" id="like_unlike_url" value="" />
    <div  id="fav_like_tool_tip" class="nl_tool_tip" >
    {assign var="ret_url" value=$config.current_url|urlencode}
   
    </div>
    <div style="font:9px/9px 'Trebuchet MS', Arial, Helvetica, sans-serif ; float: left; margin:3px 0 0 26px; word-wrap:break-word; " id="like_unlike_text" ></div>
    </div>


</div>
{if $product.company_id}
	{assign var="vendor_info" value=$product.company_id|fn_sdeep_get_vendor_info}
	{assign var="rating" value=$product.company_id|fn_sdeep_get_rating}
	{assign var="feedback" value=$product.company_id|merchant_detail_rating}
	{assign var="auth_dealer_info" value=$product.company_id|fn_sdeep_get_auth_dealer_info}
    {assign var="vendor_state_city" value=$product.company_id|fn_get_vendor_state}
	<table width="100%">
    
		<tr>
			<td{if $rating} {/if} style="border:0;">
				<div class="" style="position: absolute; margin-left:185px; margin-top:-5px;">{include file="addons/sdeep/common_templates/vendor_icons.tpl" vendor_info=$vendor_info size="50"}</div>
                
                <div class="clearboth"></div>
                <h3 style="width:86%; float:left;">
                    {if $product_count < 25}
                        
                        {assign var="url" value="index.php?dispatch=companies.view&company_id=`$vendor_info.company_id`"|fn_url}
                        {assign var="new_url" value=$url|fn_new_url}
                           
                            <a href="{$new_url}" class="pj2_vendor_name" title="{$vendor_info.company}">{$vendor_info.company}</a>
                        {else}
                            
                            <a href="{"index.php?dispatch=companies.view&company_id=`$vendor_info.company_id`"|fn_url}" class="pj2_vendor_name" title="{$vendor_info.company}">{$vendor_info.company}</a>          
			                       
                   <!-- <a href="{"index.php?dispatch=companies.view&company_id=`$vendor_info.company_id`"|fn_url}" class="pj2_vendor_name" title="{$vendor_info.company}">{$vendor_info.company}</a> -->
                   
               </h3> 
               
               {if $rating}
                 {assign var="feedback_count" value=$feedback.count|default:0}
					{assign var="feedback_positive" value=$feedback.positive+$feedback.neutral|default:0}
					<a style="float:left; clear:both; width:120px;" href="{"index.php?dispatch=companies.view&company_id=`$vendor_info.company_id`"|fn_url}#feedback_heading">{include file="addons/sdeep/components/stars.tpl" stars=$rating|fn_sdeep_get_stars}</a>
					<!--<div class="clearboth"></div>-->
                    <span class="pj2_rating_text">
                    {if $feedback_count} ({$feedback_count} {$lang.mer_rating}{if {$feedback_count > 1}s{/if}){/if}
                    </span>
					<div class="clearboth"></div>
					<span style="font-size:12px;" class="">{if $feedback_positive}{$feedback_positive}% Positive Reviews{if {$feedback_count > 1}s{/if}{/if}</span>
               {/if}    
			</td>
            {/if}  
     	</tr>
             </table>
		{if $vendor_state_city.city || $vendor_state_city.state}
     <span style="float:left; display:inline; width:96%; font:12px/14px 'Trebuchet MS', Arial, Helvetica, sans-serif; color:#636566; margin:3px 5px 2px 5px;">{$lang.merchant_location_nl}{$vendor_state_city.city},<span style="margin-left:3px;">{$vendor_state_city.state}</span></span>
                                   {/if}
                                   
	{if $auth_dealer_info}
    <hr class="hrmng " />
	<table>
		<tr>
			<td valign="top" width="60">
				<h3>{$lang.sdeep_auth_dealer}</h3>
			</td>
			<td class="marc_brand_img">
				{foreach from=$auth_dealer_info item="auth_dealer_item"}
					{if $auth_dealer_item.thumb_path}
						{*include file="common_templates/image.tpl" object_type="feature_variant" images=$auth_dealer_item.pair_id*}
						<img src="{$config.ext_images_host}{$config.full_host_name}{$auth_dealer_item.thumb_path}"/>
					{/if}
				{/foreach}
			</td>
		</tr>
	</table>
	{/if}
	<hr class="hrmng clearboth" />
	{assign var="is_trm" value=$product.company_id|fn_sdeep_is_trm}
	{if $is_trm}
		<ul class="trm_feture">
			<li>Consistently receives highest merchant rating</li>
			<li>Ships Products Quickly</li>
		</ul>
	{/if}
	<span class="view_vendor_products" style="margin-bottom:0;"><a href="{"products.search?company_id=`$product.company_id`&search_performed=Y"|fn_url}">{$lang.view_vendor_products}</a></span>
{/if}
<div class="clearboth"></div>
{*Seller connect dony by Raj Kumar on 09-03-2013*}
                      {include file="views/products/seller_connect_redirect.tpl" }
        
</div>

<!-- popup Design -->
<div class="hide_trm" style="position:absolute; z-index:200; left:0; top:0; width:100%; min-height:100%; display:none;">
<div style=" width:500px; margin:auto;">
<div class="pj2_popup_prd">
<img class="img_close" src="{$config.ext_images_host}/images/skin/pj2_close_btn_banklist.png ">
<p style="font:16px/22px trebuchet MS; color:#000; display:block; text-align:left; padding:0; font-weight:bold; margin:0px 0 0 10px;">{$lang.seller_pop_data_title}</p> 

<ul class="content">
    <li>
    <img src="{$addons.sdeep.trm_icon_url}" alt="Top Rated Merchant" title="Top Rated Merchant" style="float:left; margin-right:5px;">
    {$lang.seller_pop_data}</li>
</ul>
<a href="{$config.seller_detail_trm_path}" target="_blank" style="float:right">{$lang.seller_pop_data_know_more}</a>
</div>
</div>

</div>
{literal}
  <script type="text/javascript">
	$('#like_unlike_icon').click(function(){
		 var url=$('#like_unlike_url').val();
		 window.location=url;
		 });
	if(ReadCookie('scfavstore')!='')
	{
		var favcookie=ReadCookie('scfavstore').split(",");
		var found=0;
		for(i=0;i<favcookie.length;i++)
		{
			if(favcookie[i]=='{/literal}{$product.company_id}{literal}')
			{
				found=1;
			}
		}
		
		if(found==1)
		{
			$('#like_unlike_icon').removeClass('store_not_liked');
				$('#like_unlike_icon').addClass('store_liked');
				$('#like_unlike_icon').css('right','50px');
				$('#fav_like_tool_tip').css('right','-43px');
				$('#like_unlike_text').css('width','55px');
				$('#like_unlike_text').css('white-space','nowrap');
				$('#like_unlike_url').val("index.php?dispatch=profiles.unlike&ret_url={/literal}{$ret_url}{literal}&p_id={/literal}{$product.product_id}{literal}&c_id={/literal}{$product.company_id}{literal}");
				$('#fav_like_tool_tip').html('{/literal}{$lang.already_liked|escape}{literal}');
				$('#like_unlike_text').html('{/literal}{$lang.store_ll}{literal}');
		}
		else
		{
			$('#like_unlike_icon').removeClass('store_liked');
				$('#like_unlike_icon').addClass('store_not_liked');
				$('#like_unlike_icon').css('right','28px');
				$('#fav_like_tool_tip').css('right','-20px');
				$('#like_unlike_text').css('width','27px');
				$('#like_unlike_url').val("index.php?dispatch=profiles.like&ret_url={/literal}{$ret_url}{literal}&p_id={/literal}{$product.product_id}{literal}&c_id={/literal}{$product.company_id}{literal}");
				$('#fav_like_tool_tip').html('{/literal}{$lang.not_liked|escape}{literal}');
				$('#like_unlike_text').html('{/literal}{$lang.store_nl}{literal}');
		}
	}
	else if((ReadCookie('sclikes')!='' && ReadCookie('scfavstore')=='') || (ReadCookie('sclikes')=='' && ReadCookie('scfavstore')=='' && {/literal}{$auth.user_id}{literal}!=0) )
	{
	   $.ajax({
		  type: "GET",
		  url: "index.php",
		  data: { dispatch:'products.check_fav_store',check:1,c_id:'{/literal}{$product.company_id}{literal}' }
		}).done(function( msg ) {
			
			if(msg==1)
			{
				$('#like_unlike_icon').removeClass('store_not_liked');
				$('#like_unlike_icon').addClass('store_liked');
				$('#like_unlike_icon').css('right','50px');
				$('#fav_like_tool_tip').css('right','-43px');
				$('#like_unlike_text').css('width','55px');
				$('#like_unlike_text').css('white-space','nowrap');
				$('#like_unlike_url').val("index.php?dispatch=profiles.unlike&ret_url={/literal}{$ret_url}{literal}&p_id={/literal}{$product.product_id}{literal}&c_id={/literal}{$product.company_id}{literal}");
				$('#fav_like_tool_tip').html('{/literal}{$lang.already_liked|escape}{literal}');
				$('#like_unlike_text').html('{/literal}{$lang.store_ll}{literal}');
			}
			else
			{
				$('#like_unlike_icon').removeClass('store_liked');
				$('#like_unlike_icon').addClass('store_not_liked');
				$('#like_unlike_icon').css('right','28px');
				$('#fav_like_tool_tip').css('right','-20px');
				$('#like_unlike_text').css('width','27px');
				$('#like_unlike_url').val("index.php?dispatch=profiles.like&ret_url={/literal}{$ret_url}{literal}&p_id={/literal}{$product.product_id}{literal}&c_id={/literal}{$product.company_id}{literal}");
				$('#fav_like_tool_tip').html('{/literal}{$lang.not_liked|escape}{literal}');
				$('#like_unlike_text').html('{/literal}{$lang.store_nl}{literal}');
			}
		});
	}
	else
	{
				$('#like_unlike_icon').removeClass('store_liked');
				$('#like_unlike_icon').addClass('store_not_liked');
				$('#like_unlike_icon').css('right','28px');
				$('#like_unlike_text').css('width','27px');
				$('#fav_like_tool_tip').css('right','-20px');
				$('#like_unlike_url').val("index.php?dispatch=profiles.like&ret_url={/literal}{$ret_url}{literal}&p_id={/literal}{$product.product_id}{literal}&c_id={/literal}{$product.company_id}{literal}");
				$('#fav_like_tool_tip').html('{/literal}{$lang.not_liked|escape}{literal}');
				$('#like_unlike_text').html('{/literal}{$lang.store_nl}{literal}');
	}
  </script>
  
{/literal}
