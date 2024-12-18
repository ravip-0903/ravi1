{* $Id: search.tpl 7903 2009-08-26 10:54:27Z angel $ *}

   {* [andyye]: updated by soumya need to modify later *}
		{assign var="obj_id" value=$company_data.company_id}
        
        {if isset($obj_id)}
		{assign var="obj_id_prefix" value="`$obj_prefix``$obj_id`"}
        {include file="common_templates/company_data.tpl" company=$company_data show_name=true show_descr=true show_rating=true show_logo=true hide_links=true}
<!--Merchant Details -->
<div class="ml_merchantinfo">
    {if $company_data.manifest.Customer_logo.vendor}
    <div class="ml_merchantinfo_image">
    {assign var="capture_name" value="logo_`$obj_id`"}
    {$smarty.capture.$capture_name}
    </div>
    {/if}
    <div class="ml_merchantinfo_detFails" {if !$company_data.manifest.Customer_logo.vendor} style="width:100%;"{/if}>
      <div class="ml_merchantinfo_details_header"> {* [andyye]: modified code below *}
        <h1 class="ml_merchantinfo_details_header_heading">{$company_data.company}</h1>
        <a class="ml_merchantinfo_details_header_button" href="{"companies.view&company_id=$obj_id"|fn_url}">{$lang.merchant_microsite}</a>
      </div>
      
      <!--Merchant Rating --> 
      {* [andyye] *}
      {assign var="rating" value=$company_data.company_id|fn_sdeep_get_rating}
       {assign var="feedback" value=$company_data.company_id|merchant_detail_rating}
      {assign var="disc_count" value=$company_data.company_id|fn_get_discussion_count:'M'}
      {assign var="object_type" value="M"}
      {assign var="disc_count" value=$company_data.company_id|fn_get_discussion_count:$object_type}
      <div class="ml_merchantinfo_rating">
      
        <label class="ml_merchantinfo_rating_heading">{$lang.sdeep_rating}</label>
        <div class="ml_merchantinfo_rating_star">
        {include file="addons/sdeep/components/stars.tpl" stars=$rating|fn_sdeep_get_stars}
        </div>
        {assign var="feedback_count" value=$feedback.count|default:0}
        <div class="ml_merchantinfo_rating_satisfyuser">
        {if $feedback_count} {$feedback_count} {$lang.merchant_dashboard_rating}{if {$feedback_count > 1}s{/if}{/if}
        {if $disc_count >0}|<a href="{"companies.view&company_id=`$company_data.company_id`"|fn_url}#review">{$disc_count} Review{if $disc_count > 1}s{/if}</a>{/if}
        
        </div>

        <div class="ml_merchantinfo_rating_satisfyuser">{* [/andyye] *}
          
          {* [/MODIFIED MY SOUMYA : NEED TO UPGRADE LATER] *}
          {assign var="auth_dealer_info" value=$company_data.company_id|fn_sdeep_get_auth_dealer_info} </div>
      </div>
      <!--End Merchant Rating --> 
      
      <!--Top Rated Merchant -->

      {include file="addons/sdeep/common_templates/vendor_icons_full.tpl" vendor_info=$company_data}
      <!--End Top Rated Merchant --> 
    </div>
  </div>
<!--End Merchant Details -->
<div class="clearboth height_ten"></div>

{/if}
	{* [andyye]: updated by soumya need to modify later *}

{if $search}
	{assign var="_title" value=$lang.search_results}
	{assign var="_collapse" value=true}
{else}
	{assign var="_title" value=$lang.advanced_search}
	{assign var="_collapse" value=false}
{/if}

{*include file="views/products/components/products_search_form.tpl" dispatch="products.search" collapse=$_collapse*}

{if !empty($smarty.request.retain)}
    {if !empty($smarty.request.name)}
        {assign var="search_title" value='_'|str_replace:" ":$smarty.request.name}
        <h1 class="cat_title" style="margin-top:10px !important;" id="stitle"> {$search_title} </h1>
        {literal} <script> $(document).ready(function(){ $("title").html($('#stitle').html()); $(".breadcrumbs span").html($('#stitle').html()); }) </script> {/literal}
    {/if}
    {if !empty($smarty.request.img)}
        <div class="compact wysiwyg-content margin-bottom">
            <p><img src="{$config.ext_images_host}{$smarty.request.img}"></p>
        </div>
    {/if}
{else}
    {if !empty($smarty.request.q)}
        {include file="views/products/search_feedback_form.tpl"}
    {/if}
{/if}

{assign var="title_extra" value="`$lang.products_found`:&nbsp;<strong>`$product_count`</strong>"}

{assign var="suggestword" value=$suggestion}
{if $suggestword}
  {assign var="filter_qstring" value=$config.current_url|fn_query_remove:"q"}
<div style="float:left;color: #900;margin: 2px 0 10px 0;font-size: 18px;">Did you mean: <a href="{$filter_qstring|fn_link_attach:"q="}{$suggestword}" style="color: #048ccc;text-decoration:underline;cursor:pointer;"><b><i>{$suggestword}</i></b></a></div>
{/if}
{if $search}
	{if $products && empty($products_similar)}
		{assign var="layouts" value=""|fn_get_products_views:false:0}
		{if $category_data.product_columns}
			{assign var="product_columns" value=$category_data.product_columns}
		{else}
			{assign var="product_columns" value=$settings.Appearance.columns_in_products_list}
		{/if}
		
		{if $layouts.$selected_layout.template}
			{include file="`$layouts.$selected_layout.template`" columns=`$product_columns`}
		{/if}
	{else}
		{*<p class="no-items">{$lang.text_no_matching_products_found}</p>*}
			{if $last_search}
				{assign var="option_selected" value=$last_search|shopping_option_name}
				{$lang.matching_records|replace:'[option_selected]':$option_selected}
			{else}
				<div class="no-items" id="product_found">{$lang.text_no_matching_products_found}</div>
        
        <div class="no-items" id="category_found" style="text-align:left;display:none;">{$lang.text_no_matching_category_found}</div>
        <div class="sidebox-wrapper"><div class="sidebox-body">          
          <ul id="category_names" class="product-filters1 nav_mainmenu new_menu_link_nll" id="product-filters">&nbsp;</ul>
        </div></div> 
			{/if}
		{if $products_similar}
			{assign var="layouts" value=""|fn_get_products_views:false:0}
			{if $category_data.product_columns}
				{assign var="product_columns" value=$category_data.product_columns}
			{else}
				{assign var="product_columns" value=$settings.Appearance.columns_in_products_list}
			{/if}
		
			{if $layouts.$selected_layout.template}
				{include file="`$layouts.$selected_layout.template`" columns=`$product_columns`}
			{/if}
		{/if}
	{/if}

{/if}

{capture name="mainbox_title"}
<span class="float-right">
{$title_extra}
{$_title}
</span>
{/capture}

{literal}
<script>
  var productfound = $('#product_found').text();
  if(productfound != '') {
        
        var some_cat = $("#product-filters").html().trim();
        
        if(some_cat != '') {

           var catname = $("#cid option:selected").text();
           if(catname != '') {

              $("#product_found").hide();

              var str=$('#category_found').html();
              var catdesc=str.replace("{selectedcategory}", catname);
              
              $('#category_found').html(catdesc);
              $("#category_names").html(some_cat);

              $("#category_found").show();
           }
        }
  }
</script>
{/literal}
