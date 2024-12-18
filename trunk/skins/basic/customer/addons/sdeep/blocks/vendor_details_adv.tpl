{** block-description:sdeep_auth_dealer_adv **}

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
<div class="pj2_prd_seller_name" style="font:bold 14px Arial, Helvetica, sans-serif; background:none; color:#000;">{$lang.seller_detail}
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
			<td style="border:0;">		
                <div class="clearboth"></div>
                <h3 style="width:86%; float:left;">
                        
                        {assign var="url" value="index.php?dispatch=companies.view&company_id=`$vendor_info.company_id`"|fn_url}
                        {assign var="new_url" value=$url|fn_new_url}
                           
                            <a href="{$new_url}" class="pj2_vendor_name" title="{$vendor_info.company}">{$vendor_info.company}</a>
                            
               </h3> 
               
               {if $rating}
                 {assign var="feedback_count" value=$feedback.count|default:0}
                        {assign var="feedback_positive" value=$feedback.positive+$feedback.neutral|default:0}
                        <a style="float:left; clear:both; width:120px;" href="{"index.php?dispatch=companies.view&company_id=`$vendor_info.company_id`"|fn_url}#feedback_heading">{include file="addons/sdeep/components/stars.tpl" stars=$rating|fn_sdeep_get_stars}</a>
                        <!--<div class="clearboth"></div>-->
                        <div class="clearboth"></div> 
                        {if $vendor_state_city.city || $vendor_state_city.state}
                            <span style="float:left; display:inline; width:96%; font:12px/14px 'Trebuchet MS', Arial, Helvetica, sans-serif; color:#636566; margin:3px 5px 2px 0px;">{$lang.merchant_location_nl}{$vendor_state_city.city},<span style="margin-left:3px;">{$vendor_state_city.state}</span></span>
                       {/if}
                      <div class="clearboth"></div>
                    <span class="pj2_rating_text prd_blk_mrchnt_rating">
                        <label class="bold_txt" style="color: {if $feedback_positive <= $config.low_rating_range_end}{$config.low_rating_color}{elseif $feedback_positive > $config.low_rating_range_end && $feedback_positive < $config.top_rating_range_start}{$config.middle_rating_color}{elseif $feedback_positive >= $config.top_rating_range_start}{$config.top_rating_color}{/if};" >{if $feedback_positive}{$feedback_positive}% </label> <span class="bold_val"> {$lang.postive_review}{if {$feedback_count > 1}s{/if}</span>{/if}</span>
                    <span class="pj2_rating_text prd_blk_mrchnt_rating">
                    {if $feedback_count} <label class="bold_txt">{$feedback_count}</label> <span class="bold_val"> {$lang.mer_rating}{if {$feedback_count > 1}s{/if}</span>{/if}
                    </span>
`             {else}
                    <span style="float:left; display:inline; width:96%; font:12px/14px 'Trebuchet MS', Arial, Helvetica, sans-serif; color:#636566; margin:3px 5px 2px 0px;">{$lang.merchant_location_nl}{$vendor_state_city.city},<span style="margin-left:3px;">{$vendor_state_city.state}</span></span>
                                <div class="clearboth"></div>
               {/if} 
<div class="trm_icon_new">{include file="addons/sdeep/common_templates/vendor_icons.tpl" vendor_info=$vendor_info size="50"}</div>    
			</td>
     	</tr>
             </table>
        <span class="view_vendor_products" style="margin-bottom:0; font-size:12px;"><a href="{"products.search?company_id=`$product.company_id`&search_performed=Y"|fn_url}">{$lang.view_vendor_products}</a></span>                           
    <div class="clearboth"></div>	

{if $auth_dealer_info}
    <hr class="hrmng " />
	<table width="100%">
		<tr>
			<td valign="top">
				<h3 class="hv_a_question" style="padding:0;">{$lang.sdeep_auth_dealer}</h3>
			</td>
                </tr><tr>
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
	
{/if}
{if $config.ask_merchant_block_show}
<hr class="hrmng clearboth" />
<p class="hv_a_question">{$lang.have_question}</p>
{assign var="ask_question" value=$product.company_id|fn_sdeep_ask_merchant}
{if $ask_question.percent >= 0 && $ask_question.percent}
<div class="query_resp_rt_blk">
<img class="response" src="{$ask_question.icon_url}" alt="image" align="left" name="{$ask_question.name}"/>
<span class="response">{$lang.ask_have_question_mid_value|replace:'[percent]':$ask_question.percent|replace:'[name]':$ask_question.name}</span>
<span class="response fillrate">{$lang.ask_response_rate|replace:'[response]':$ask_question.res_per_ten_days}{include file="common_templates/tooltip.tpl" tooltip=$lang.ask_response_define}</span>
</div>
{/if}
<div class="clearboth"></div>
{/if}

{include file="views/products/seller_connect_redirect.tpl" }
{if $config.shipping_block_show}

    {assign var="ship_info" value=$product.company_id|fn_sdeep_ship_info}
    
    {assign var="shipping_percentage" value=$product.company_id|fn_shipping_percentage:$ship_info.name}
    
 {if $shipping_percentage}
    <hr class="hrmng clearboth" />
    <p class="hv_a_question">{$lang.ship_performance}</p>
    <div class="query_resp_rt_blk">
    <img class="response" src="{$ship_info.icon_url}" alt="image" align="left" name="{$ship_info.name}"/>
    <span class="response" style="margin-left:10px;">{$lang.ship_info_mid_value|replace:'[percent]':$shipping_percentage.badge_percent|replace:'[name]':$ship_info.name}</span>
    <span class="response fillrate">{$lang.fill_response_rate|replace:'[fill]':$shipping_percentage.fill_rate}{include file="common_templates/tooltip.tpl" tooltip=$lang.shipping_fill_rate_define}</span>
</div>
  {/if}
{/if}
<div class="clearboth"></div>
{if $config.avg_block_show}
{assign var="tot_avg_rate" value=$product.company_id|fn_get_avg_merchant_review_rating}
{assign var="avg_rate" value=$tot_avg_rate.0.avg|number_format:2}
{if $avg_rate|intval}
<hr class="hrmng clearboth" />
<div class="box_reviewdetails mer_dtl_rat_nl prd_rght_mer_blk">
    <div class="subheader_nl">{$lang.detailed_merchant_ratings}</div>
   <div class="sub_title_mer_dtl">{$lang.rating_header_lang|replace:'[output]':$avg_rate}</div>
<div class="box_reviewdetails_starrating">

  <div class="box_negativerating">
   <div class="box_negativerating_heading">{$lang.review_shipping_time}</div>
      <div class="box_negativerating_stars">

           {include file="addons/discussion/views/discussion/components/stars.tpl" stars=$tot_avg_rate.1.shipping_time}

           <span class="avg_rat">{$tot_avg_rate.0.shipping_time|number_format:1} </span>

           </div>
       </div>
   <div class="box_negativerating">
   <div class="box_negativerating_heading">{$lang.review_shipping_cost}</div>
   <div class="box_negativerating_stars">


{include file="addons/discussion/views/discussion/components/stars.tpl" stars=$tot_avg_rate.1.shipping_cost} 

 <span class="avg_rat">  {$tot_avg_rate.0.shipping_cshippingost|number_format:1} </span>

   </div>
   </div>
   <div class="box_negativerating">
   <div class="box_negativerating_heading">{$lang.review_product_quality}</div>
   <div class="box_negativerating_stars">

     {include file="addons/discussion/views/discussion/components/stars.tpl" stars=$tot_avg_rate.1.product_quality} 


        <span class="avg_rat">{$tot_avg_rate.0.product_quality|number_format:1}</span>

   </div>
   </div>
   <div class="box_negativerating">
   <div class="box_negativerating_heading">{$lang.review_val_money}</div>
   <div class="box_negativerating_stars">

   {include file="addons/discussion/views/discussion/components/stars.tpl" stars=$tot_avg_rate.1.value_for_money} 

   <span class="avg_rat">{$tot_avg_rate.0.value_for_money|number_format:1} </span>
   </div>
   </div>


   </div>
    {$lang.rating_footer_val}
   </div>
{/if}
{/if} 
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
