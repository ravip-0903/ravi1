{literal}
<style>
.my_acc_nl{ border:0;}
.my_acc_nl td{border-bottom:1px solid #ccc; border-right:0;  padding:2px 0!important; height:24px;}
.lightbox_overlay
{
 width:100%;
 height:100%;
 background-color:#000;
 opacity:0.5;
 -moz-opacity:.50;
 filter:alpha(opacity=50); 
 z-index:10000;
 position:fixed;
 left:0px;
 top:0px;
}
.lightbox_container
{
 /*background:rgba(255,255,255,.7);
 border-radius:20px;
 -moz-border-radius:15px;
 -webkit-border-radius:15px;
 border:1px solid #fff;*/
 height:335px;
 width:706px; 
 z-index:10001;
 position:fixed;
 left:50%;
 margin-left:-245px;
 top:174px;
}

.lightbox_container .lightbox_container_close
{
 position:absolute;
 margin-left:507px;
 margin-top:-12px;
 z-index:10002;
 background:url(images/skin/sprite_png_icon.png) -74px -1px no-repeat; 
 height:30px;
 width:31px;
 cursor: pointer;
}
.lightbox_container .lightbox_container_bg
{
 float:left;
 display:inline;
 background: left top no-repeat;
 width:675px;
 height:331px;
}
.lightbox_container .lightbox_container_coupon_bg
{
	width: 500px;
float: left;
background: white url(images/skin/buyer_payment_bg.gif) center no-repeat;
border-radius: 5px;
-moz-border-radius: 5px;
padding: 10px;
border: 3px solid #444;
}
.lightbox_container .lightbox_container_label
{
 float:left;
 display:inline;
 font:20px trebuchet ms;
 color:#fff;
 margin-top:120px;
 margin-left:165px;
}
.lightbox_container .lightbox_container_textbox
{
 float:left;
 display:inline;
 font:15px trebuchet ms;
 color:#656366;
 margin-top:117px;
 margin-left:10px;
 padding:5px 5px 5px 5px;
 border-radius:5px;
 width:185px;
 border:0px;
 background-color:#fff;
}
.lightbox_container .lightbox_container_button
{
 float:left;
 display:inline;
 font:15px trebuchet ms;
 color:#fff;
 margin-top:117px;
 margin-left:15px;
 padding:5px;
 border-radius:5px;
 -moz-border-radius:5px;
 -webkit-border-radius:5px;
 border:0px;
/* background:url(images/skin/diwali_pop_up_btn.gif) no-repeat 0 0 ;
 width:223px;*/
 height:30px;
background:#0084b6;
}
.lightbox_container .lightbox_container_button:hover{background:#0587bb}
</style>
{/literal}

{* $Id: myaccount.tpl 12544 2011-05-27 10:34:19Z bimib $ *}
<div style="width:100%;">
{assign var="user_saving"  value=$auth.user_id|fn_get_user_saving}
{assign var="user_saved_subtotal" value=$user_saving.sd }
{assign var="user_saved_discount" value=$user_saving.dis}
{assign var="user_saved" value=$user_saved_subtotal+$user_saved_discount}
{assign var="user_saved" value=$user_saved|number_format}

{if $user_saved!='0'}
<div class="mobile mobile_bucks">
<div class="box_mycluesbucks">
<div class="box_mycluesbucks_details height_auto" style="width:100%; background:#c3efff;">
<h1 class="box_mycluesbucks_details_heading" style="color:#000; font-weight:bold; font-size:24px; text-align:center;">{$lang.user_saving|replace:"[user_saving]":$user_saved}</h1>
<div class="box_mycluesbucks_details_text height_auto" style="color:#000; margin-top: -5px; text-align:center;">
{$lang.mobile_you_saved_so_far}
</div>
</div>
</div>
</div>
{/if}

<div class="aside_left {if $user_saved!='0'}
width36{/if}">
<!--Clues Bucks -->
<div class="box_mycluesbucks">
<div class="box_mycluesbucks_image width80"><img src="images/skin/myaccount_images/img_mycluesbucks.gif" {if $user_saved!='0'}
width="100" {else}width="122" height="134"{/if}  /></div>

<div class="box_mycluesbucks_details height_auto">
<h1 class="box_mycluesbucks_details_heading">{$lang.user_clues_buck}</h1>
{if $user_profiles.points!='' or $user_profiles.points!=0 }
<div class="box_mycluesbucks_details_text height_auto">
{$lang.have_buck_text}
</div>
<div class="box_mycluesbucks_details_cluesbucks">{$lang.you_have}&nbsp;"<span class="box_mycluesbucks_details_cluesbucks_clues">{$user_profiles.points}</span>" &nbsp;{$lang.clues_buck}</div>
{else}
<div class="box_mycluesbucks_details_text height_auto">
{$lang.not_have_buck_text}
</div>
{/if}
<div class="box_mycluesbucks_functions">
<input name="" type="button" class="box_mycluesbucks_functions_button" value="Go Shopping!" onclick="location.href='index.php'" />
</div>


</div>

</div>
<!--End Clues Bucks -->
</div>
<!--user_saving-->
{if $user_saved!='0'}
<div class="aside_left width36 no_mobile my_account_clues_bcks_middle" style="width:18%; margin-left:6%;">
<div class="box_mycluesbucks">
<div class="box_mycluesbucks_details height_auto" style="width:100%;">
<h1 class="box_mycluesbucks_details_heading" style="color:#333333; font-weight:bold; font-size:24px;">{$lang.user_saving|replace:"[user_saving]":$user_saved}</h1>
<div class="box_mycluesbucks_details_text height_auto" style="color:#000; margin-top: -5px;">
{$lang.you_saved_so_far}
</div>
</div>
</div>
</div>
{/if}



<div class="aside_right {if $user_saved!='0'}
width36{/if}">
<!--Feedback pending -->
<div class="box_feedbackpending">
<div class="box_feedbackpending_image width80"><img src="images/skin/myaccount_images/img_feddbackpending.gif" {if $user_saved!='0'}
width="100" {else}width="122" height="134"{/if} /></div>
{if $pend_feedback_count>0 || $post_feedback_count>0 }
<div class="box_feedbackpending_details height_auto">
<h1 class="box_feedbackpending_details_heading">({$pend_feedback_count}) {$lang.user_feedback_pending}</h1>
<div class="box_feedbackpending_details_text">
{$lang.feedback_text}
</div>

<div class="box_feedbackpending_functions">
<input name="" type="button" class="box_feedbackpending_functions_button" value="Feedback" onclick="location.href='index.php?dispatch=profiles.my_feedbacks'" />
</div>
{else}
<div class="box_feedbackpending_details">
<h1 class="box_feedbackpending_details_heading"> {$lang.user_feedback_pending}</h1>
<div class="box_feedbackpending_details_text">
{$lang.no_feedback_text}
</div>
</div>
{/if}

</div>

</div>
<!--End Feedback pending -->
</div>

</div>

<div class="clearboth height_twenty"></div>


<div class="aside_left">
<div class="box_header">

<!--end-->
<h1 class="box_heading">{$lang.user_profile}</h1>
<a href="index.php?dispatch=profiles.update" class="box_header_linkright">Edit</a>
</div>

<!--My Profile -->
<div class="box_myprofile">
<div class="box_myprofile_details">
{if $user_profiles.firstname==''}<div class="box_myprofile_details_textnormal">{$user_profiles.email}</div>
{else}
<div class="box_myprofile_details_textbold">{$user_profiles.firstname}&nbsp;{$user_profiles.lastname}</div>
<div class="box_myprofile_details_textnormal">{if $user_profiles.phone!='' }+91-{$user_profiles.phone}{/if}</div>
<div class="box_myprofile_details_textnormal">{$user_profiles.email}</div>
{/if}

{if $user_profiles.s_address!=''}
<div class="box_myprofile_details_textbold margin_top_ten">Shipping Address</div>
<div class="box_myprofile_details_textnormal">
{$user_profiles.s_address}{if $user_profiles.s_address_2!=''},{$user_profiles.s_address_2}{/if}
<br />
{$user_profiles.s_city}{if $user_profiles.s_county!=''},{$user_profiles.s_county}{/if}{if $user_profiles.s_zipcode!=''}-{$user_profiles.s_zipcode}{/if}
</div>
{/if}
</div>

</div>
<!--End My Profile -->
<div  id="coupon_pop" style="display:none">
  <div class="lightbox_overlay"></div>
    <div class="lightbox_container">
   
    
        <div class="lightbox_container_close" onclick="document.getElementById('coupon_pop').style.display='none'">
        <img id="popup_close" width="31" height="30" src="images/skin/img_close_lightbox.png" style="cursor: pointer;">
        </div>
        <div class="clearboth"></div>
        
                
        <div class="lightbox_container_coupon_bg" style="overflow-y:auto;">
        
        
        
        
        <div class="float_left margin_left_ten" style="width:96%; font:12px trebuchet ms; color:#636566; line-height:20px;">
        <h1 class="box_heading" style="margin-top:10px;">{$lang.how_to_use_coupon_heading}</h1>
        <div class="clearboth"></div>
        {$lang.how_to_use_coupon_text}
        </div>
        
        </div>
    </div>
  </div>
</div>
<div  id="tandc_pop" style="display:none">
  <div class="lightbox_overlay"></div>
    <div class="lightbox_container">
   
    
        <div class="lightbox_container_close" onclick="document.getElementById('tandc_pop').style.display='none'">
        <img id="popup_close" width="31" height="30" src="images/skin/img_close_lightbox.png" style="cursor: pointer;">
        </div>
        <div class="clearboth"></div>
        <div class="lightbox_container_coupon_bg" style="overflow-y:auto;">
        

<div class="float_left margin_left_ten" style="width:96%; font:12px trebuchet ms; color:#636566; line-height:20px;">
<h1 class="box_heading" style="margin-top:10px;">{$lang.coupon_tandc_heading}</h1>
        <div class="clearboth"></div>

        {$lang.coupon_tandc_text}
        </div>
        </div>
    </div>
  </div>
  
{if !empty($coupons_detail)}
<div class="aside_right">
<div class="box_header">
<h1 class="box_heading">{$lang.user_coupon}</h1>
<div style="float:right"><a href="javascript:void(0)" onclick="document.getElementById('coupon_pop').style.display='block'">{$lang.how_to_use_coupon}</a></div>
</div>

<!--List Coupon History -->

<div class="list_orderhistory" style="background-color:#f5f5f5;">
<div class="list_orderhistory_name bold">{$lang.coupon_codes}</div>
<div class="list_coupon_status bold">{$lang.coupon_status}</div>
<div class="list_orderhistory_price bold" style="color:#636566;">{$lang.coupon_expiry_date}</div>
</div>

{foreach from=$coupons_detail item="cou"}
<div class="list_orderhistory" >
<div class="list_orderhistory_name" {if !in_array($cou.coupon_code,$unused_coupon)} style="text-decoration:line-through;" {elseif $cou.expiration_date < $smarty.now } style="text-decoration:line-through;" {/if}>{$cou.coupon_code}</div> 
<div class="list_coupon_status">{if !in_array($cou.coupon_code,$unused_coupon)}{$lang.coupon_used}{elseif $cou.expiration_date < $smarty.now}{$lang.coupon_expired_myaccount}{else}{$lang.coupon_default}{/if}</div>
<div class="list_orderhistory_price" {if !in_array($cou.coupon_code,$unused_coupon)} style="text-decoration:line-through;" {elseif $cou.expiration_date < $smarty.now} style="text-decoration:line-through;" {/if}>{$cou.expiration_date|date_format:"`$settings.Appearance.date_format`"}</div>
</div>
{/foreach}
<!--End coupon History -->
<div style="float:left"><a href="javascript:void(0)" onclick="document.getElementById('tandc_pop').style.display='block'">{$lang.tandc}</a></div>
</div>

{elseif !empty($my_stores)}

<div class="myaccnt_fav_store"style="width:400px; float:right;">
<div class="box_header"><h1 class="box_heading">My Favourite Stores</h1>
<a href="index.php?dispatch=profiles.store" class="box_header_linkright">View All</a>
</div>

	<table border="0" cellpadding="0" cellspacing="0" width="100%" class="table my_acc_nl" id="my_fav_store">


{foreach from=$my_stores item="ms" name="fav_str"}
	{if $smarty.foreach.fav_str.iteration <= "5"}
  <tr>
    
    <td class="myaccnt_fs_str_nm" width="56%">{if $ms.status=='A'}<a href="{"index.php?dispatch=companies.view&company_id=`$ms.company_id`"|fn_url}">{$ms.company}</a>
    
    
    {else}{$ms.company}<br/><span>{$lang.comp_disabled}</span>{/if}</td>
    <td class="myaccnt_fs_str_ratng" width="42%">
      {assign var="is_trm" value=$ms.company_id|fn_sdeep_is_trm}
      {assign var="rating" value=$ms.company_id|fn_sdeep_get_rating}
	{assign var="feedback" value=$ms.company_id|merchant_detail_rating}
    <div style="float:left; width:70%; margin-top:2px;">
    {if $rating}
     {assign var="feedback_count" value=$feedback.count|default:0}
        {assign var="feedback_positive" value=$feedback.positive+$feedback.neutral|default:0}
        <a style="float:left;" href="{"index.php?dispatch=companies.view&company_id=`$ms.company_id`"|fn_url}#feedback_heading">{include file="addons/sdeep/components/stars.tpl" stars=$rating|fn_sdeep_get_stars}</a>
        {else}
        {assign var="feedback_count" value=$feedback.count|default:0}
        {assign var="feedback_positive" value=$feedback.positive+$feedback.neutral|default:0}
        <a style="float:left;" href="{"index.php?dispatch=companies.view&company_id=`$ms.company_id`"|fn_url}#feedback_heading">{include file="addons/sdeep/components/stars.tpl" stars=$rating|fn_sdeep_get_stars}</a>
        <!--<div class="clearboth"></div>-->
           {/if}
   </div>
   <div style="float:left; width:29%">
    {if $is_trm}
    	<a class="trm_clk" ><img src="{$addons.sdeep.trm_icon_url}" width="24"/></a>
    {/if}
   </div> 
   <div class="clear"></div>
    </td>    
   
  </tr>
  {/if}
{foreachelse}
<tr>
	<td colspan="7"><p class="no-items">{$lang.no_fav_store}</p></td>
</tr>
{/foreach}
</table>


</div>
{else}
{$lang.no_fav_cc_found}
{/if}

<div class="clearboth height_fifty"></div>

<!-- order history    -->
<div class="box_header">
<h1 class="box_heading">{$lang.user_order_history}</h1>
{if $item_info|count >3}<a href="index.php?dispatch=orders.search" class="box_header_linkright">View All</a>{/if}
</div>
{assign var="i" value="0"}
<div>
{assign var="i" value="0"}
<pre>{*$item_info|print_r*}</pre>
{foreach from=$item_info item="o"}
{if $i<3}
<a href="index.php?dispatch=orders.details&order_id={$o.order_id}" class="list_orderhistory">
<div style="float:left; color:#636566; width:100px; margin-left:10px;">{$o.timestamp|date_format:"`$settings.Appearance.date_format`"}</div>
<div class="myaccount_recent_purchase_price" alt="{$o.priority_level_name.priority_level_name}" title="{$o.priority_level_name.priority_level_name}" style="float:left; color:#636566; width:100px; margin-left:10px; padding:2px 0 2px 28px; {if $o.ff_priority == 'Y'}background:url({$o.priority_level_name.icon_url}) left center no-repeat; background-size:24px;{/if}">{$o.order_id}</div>
<div class="myaccnt_product_name"style="float:left; color:#636566; width:300px; margin-left:10px;">{$o.product_id|fn_get_product_name|truncate:40:"---"}</div>
<div class="list_orderhistory_name">Rs. {$o.price}</div>
<div style="float:right; color:#636566; width:100px; text-align:right; margin-right:10px;" >{include file="common_templates/status.tpl" status=$o.status display="view"}</div>
</a>
{/if}
{assign var="i" value=$i+1}
{foreachelse}
<div class="clearboth"></div>
<p class="no-items">{$lang.no_orders_placed}</p>
<!--End List Order History -->
{/foreach}
</div>

<!--   end              -->
<div class="clearboth height_fifty"></div>
{if !empty($product_id)}
<div class="box_header">
<h1 class="box_heading">{$lang.user_shopping_cart}</h1>
{if $total_product>6}<a href="index.php?dispatch=checkout.cart" class="box_header_linkright">View All</a>{/if}
</div>

<div class="box_productsSmall">
{foreach from=$product_id item="product"}
{assign var="product_name" value=$product|fn_get_product_name}
<div class="box_productsSmall_product">
<div class="box_productsSmall_product_image">
<a href="{"products.view?product_id=`$product`"|fn_url}" class="box_GridProduct_product" alt="{$product_name}" title="{$product_name}"  >
{assign var="pro_images" value=$product|fn_get_image_pairs:'product':'M'}
{include file="common_templates/image.tpl" image_width="160" image_height="160" obj_id=$product images=$pro_images object_type="product" show_thumbnail="Y" alt_text=$product_name }  </a>
</div>

<div class="box_productsSmall_product_name"><a href="{"products.view?product_id=`$product`"|fn_url}" alt="{$product_name}" title="{$product_name}"  >{$product_name|truncate:50:"---"}</a></div>
</div>
{/foreach}
</div>


<div class="clearboth height_fifty"></div>
{/if}



{if !empty($wishlist)}
<div class="box_header">
<h1 class="box_heading">{$lang.user_wish_list}</h1>
{if $total_wish>6}<a href="index.php?dispatch=wishlist.view" class="box_header_linkright">View All</a>{/if}
</div>
<div class="box_productsSmall">
{foreach from=$wishlist item="wish_list"}
{assign var="product_name" value=$wish_list.product_id|fn_get_product_name}
<div class="box_productsSmall_product">
<div class="box_productsSmall_product_image">
<a href="{"products.view?product_id=`$wish_list.product_id`"|fn_url}" class="box_GridProduct_product">
{assign var="pro_images" value=$wish_list.product_id|fn_get_image_pairs:'product':'M'}
{include file="common_templates/image.tpl" image_width="160" image_height="160" obj_id=$wish_list.product_id images=$pro_images object_type="product" show_thumbnail="Y" alt_text=$product_name }</a>
</div>
<div class="box_productsSmall_product_name"><a href="{"products.view?product_id=`$wish_list.product_id`"|fn_url}" alt="{$product_name}" title="{$product_name}" >{$wish_list.product_id|fn_get_product_name|truncate:50:"---"}</a></div>
</div>
{/foreach}
</div>
<div class="clearboth"></div>
{/if}


</div>

