{literal}
    <style type="text/css">
        
    .merchant_dtl_heading{font-size: 14px; font-weight: bold; margin-top: 8px;}
    
    </style>
    {/literal}

{* $Id$ *}

{hook name="companies:view"}

{assign var="obj_id" value=$company_data.company_id}
{assign var="obj_id_prefix" value="`$obj_prefix``$obj_id`"}
{include file="common_templates/company_data.tpl" company=$company_data show_name=true show_descr=true show_rating=true show_logo=true hide_links=true} 

<!--Panel Right -->
<div class="ml_panel_right"> 
  <!--Merchant Details -->
  <div class="ml_merchantinfo">
    {if $company_data.manifest.Customer_logo.vendor}
    <div class="ml_merchantinfo_image">
    {assign var="capture_name" value="logo_`$obj_id`"}
    {$smarty.capture.$capture_name}
    </div>
    {/if}
    <div class="ml_merchantinfo_details" {if !$company_data.manifest.Customer_logo.vendor} style="width:100%;"{/if}>
      <div class="ml_merchantinfo_details_header"> {* [andyye]: modified code below *}
        <h1 class="ml_merchantinfo_details_header_heading">{$company_data.company}</h1>
        <a class="ml_merchantinfo_details_header_button" href="{"products.search?company_id=`$company_data.company_id`&search_performed=Y"|fn_url}" style="cursor:pointer;">View Our Products</a>
        {include file="views/companies/seller_connect_companies.tpl"}
      </div>
      
      <!--Merchant Rating --> 
      {assign var="rating" value=$company_data.company_id|fn_sdeep_get_rating}
      {assign var="feedback" value=$company_data.company_id|merchant_detail_rating}
      {assign var="disc_count" value=$company_data.company_id|fn_get_discussion_count:'M'}
      {assign var="object_type" value="M"}
      {assign var="disc_count" value=$company_data.company_id|fn_get_discussion_count:$object_type}
      
      
      <div class="ml_merchantinfo_rating">
      
        <label class="ml_merchantinfo_rating_heading">{$lang.sdeep_rating}</label>
        <div class="ml_merchantinfo_rating_star">
        <a href="{"companies.view&company_id=`$company_data.company_id`"|fn_url}#rating">
        {include file="addons/sdeep/components/stars.tpl" stars=$rating|fn_sdeep_get_stars}
        </a>
        </div>
        {assign var="feedback_count" value=$feedback.count|default:0}
        <div class="ml_merchantinfo_rating_satisfyuser">
        {if $feedback_count} <a href="{"companies.view&company_id=`$company_data.company_id`"|fn_url}#rating">{$feedback_count} {$lang.merchant_dashboard_rating}{if {$feedback_count > 1}s{/if}</a>{/if}
        {if $disc_count >0}|<a href="{"companies.view&company_id=`$company_data.company_id`"|fn_url}#review">{$disc_count} Review{if $disc_count > 1}s{/if}</a>{/if}
        
        </div>

        <div class="ml_merchantinfo_rating_satisfyuser">{* [/andyye] *}
          
          {* [/MODIFIED MY SOUMYA : NEED TO UPGRADE LATER] *}
          {assign var="auth_dealer_info" value=$company_data.company_id|fn_sdeep_get_auth_dealer_info} </div>
      </div>
      <!--End Merchant Rating --> 
      
      {include file="addons/sdeep/common_templates/vendor_icons_full.tpl" vendor_info=$company_data}
      
      
      <!--End Top Rated Merchant --> 

      
    </div>
  </div>

  <!--End Merchant Details --> 
 
  <!--Authorized Dealer --> 
  {if !empty($auth_dealer_info)}
  <div class="ml_authorizeddealers">
    <h1 class="ml_authorizeddealers_heading">Authorised Dealer</h1>
    
    <!--Slider Authorized Dealer -->
    <div class="slider_authorizeddealer">
    <a class="slider_authorizeddealer_navleft"></a>
      <div class="slider_authorizeddealer_container">
        {foreach from=$auth_dealer_info item="auth_dealer_item"}
        {if $auth_dealer_item.thumb_path}
        {*include file="common_templates/image.tpl" object_type="feature_variant" images=$auth_dealer_item.thumb_path*}
        <img src="{$auth_dealer_item.thumb_path}"  />
        {else}
        <img src="{$config.no_image_available_path}"  />
        {/if}
        
        {/foreach}
       </div>
      <a class="slider_authorizeddealer_navright"></a>
    </div>
    
    <!--End Slider Authorized Dealer --> 
    
  </div>
  {/if} 
  <!--End Authorized Dealer --> 
  
  <!--Dealer Description -->

  {if $company_data.company_description}
  <div class="ml_descriptions">
    <h1 class="ml_descriptions_heading">Description</h1>

    <div class="ml_descriptions_description"> {$company_data.company_description|unescape}
    
    {if $company_data.terms.guarantee}
	{include file="common_templates/subheader.tpl" title=$lang.sdeep_guarantee_terms}
	<div id="terms_{$product.company_id}">
		{$company_data.terms.guarantee}
	</div>
{/if}
{if $company_data.terms.return}
	{include file="common_templates/subheader.tpl" title=$lang.sdeep_return_terms}
	<div id="terms_{$product.company_id}">
		{$company_data.terms.return}
	</div>
{/if}
{if $company_data.terms.shipping}
	{include file="common_templates/subheader.tpl" title=$lang.sdeep_shipping_terms}
	<div id="terms_{$product.company_id}">
		{$company_data.terms.shipping}
	</div>
{/if}</div>
  </div>
  {/if}
  <!--End Dealer Description --> 
  <div style="display:block; padding:10px 0; clear:both; background:url(http://www.shopclues.com/skins/basic/customer/images/subheader_bg.gif) repeat-x bottom"></div>
      <div class="merchant_info">
            <span style="color:#048CCC;font-weight: bold;"> {$lang.merchant_info_heading}</span>
            
            <div class="merchant_dtl_heading">{$merchant_since}</div>
            <div class="merchant_dtl_info">{$lang.merchant_since}</div>
            
            <div class="merchant_dtl_heading"> {$merchant_location}</div>
            <div class="merchant_dtl_info">{$lang.merchant_location}</div>

            <div class="merchant_dtl_heading">{$total_product}</div>
            <div class="merchant_dtl_info">{$lang.total_product}</div>
            
           <!-- <div class="merchant_dtl_heading">{$total_product_sold}</div>
            <div class="merchant_dtl_info">{$lang.total_product_sold}</div>-->
            
           
            <!--<div class="merchant_dtl_heading" style="font-size:18px;">{$last_product_sold}</div>
            <div class="merchant_dtl_info">{$lang.last_product_sold}:</div>-->


      </div>
  <!--Merchant Feedbacks -->
  
  {hook name="companies:feedback"}
  {/hook} 
  <!--End Merchant Feedbacks --> 
  
  <!--Product Reviews -->
  <!--<div class="box_header margin_top_twenty">
    <h1 class="box_heading">Reviews</h1>
  </div>
  <div class="box_productreview">
    <div class="box_productreview_image"> <img src="images/img_product.gif" height="100" width="100" /> </div>
    <div class="box_productreview_details">
      <div class="box_productreview_details_header">
        <div class="box_productreview_details_header_username"> Pankaj Jasoria
          <div class="clearboth"></div>
          <span class="box_productreview_details_header_username_updatetime">14th February 2012</span>
         </div>
        <div class="box_productreview_details_header_starrating">
          <div class="box_RatingSmall">
            <div class="box_RatingSmall_star"> <img src="images/monalisa/icon_starsmall.gif" width="14" height="14" /> <img src="images/monalisa/icon_starsmall.gif" width="14" height="14" /> <img src="images/monalisa/icon_starsmall.gif" width="14" height="14" /> <img src="images/monalisa/icon_starsmall.gif" width="14" height="14" /> <img src="images/monalisa/icon_starsmall_unselected.gif" width="14" height="14" /> </div>
          </div>
        </div>
      </div>
      <div class="box_productreview_details_reviewtext"> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. </div>
      <div class="box_productreview_details_reviewvideo">
        <div class="box_productreview_details_reviewvideo_fieldname">Product Video URL:</div>
        <div class="box_productreview_details_reviewvideo_field"> <a href="#">http://www.youtube.com/watch?v=begg0NKhiK8&feature=g-all-u&context=G20d6a47FAAAAAAAALAA </a> </div>
      </div>
    </div>
  </div>-->
  <!--End Product Reviews --> 
  <a name="review">&nbsp;&nbsp;</a>
</div>
<!--End Panel Right --> 
{/hook}
