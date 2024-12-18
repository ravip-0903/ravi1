{* $Id: product_filters.tpl 12029 2011-03-14 08:12:03Z klerik $ *}
{** block-description:original **}
{literal}
    <style>
        .box_collapse{background:url(http://cdn.shopclues.com/images/skin/bg_mainmenulink_new.gif) no-repeat 149px 5px ;}
        .box_expand{background:url("http://cdn.shopclues.com/images/skin/hor_arrow_cat_nl.gif") no-repeat scroll 149px 5px transparent}
    </style>
{/literal}
{if $smarty.request.category_id}
	{assign var="curr_url" value="categories.view"}
{else}
	{assign var="curr_url" value="products.search"}
{/if}


{assign var="allfilter" value=$_REQUEST|fn_assign_filtertoitem}

{if $config.zettata_master_switch && $allfilter.search_usage eq 1 } 
 
  {include file='blocks/zettata_filter.tpl'}
{else}

<div id="slr_search_all">
<form method="get" action="{$curr_url|fn_url}" name="newsearch" id="newsearch" />

{if $smarty.server.QUERY_STRING|strpos:"dispatch=" !== false}
	{assign var="curl" value=$config.current_url}
	{assign var="filter_qstring" value=$curl|fn_query_remove:"result_ids":"filter_id":"view_all":"req_range_id":"advanced_filter":"features_hash":"subcats":"page":"cid"}
{else}
	{assign var="filter_qstring" value="products.search"}
{/if}

{assign var="filter_qstring" value=$_REQUEST|fn_remove_filter}

{assign var="reset_qstring" value="products.search"}

{if $smarty.request.q}
	<input type="hidden" name="subcats" value="{$smarty.request.subcats}" />
	<input type="hidden" name="status" value="{$smarty.request.status}" />
	<input type="hidden" name="pname" value="{$smarty.request.pname}" />
	<input type="hidden" name="product_code" value={$smarty.request.product_code} />
	<input type="hidden" name="match" value="{$smarty.request.match}" />
	<input type="hidden" name="pkeywords" value="{$smarty.request.pkeywords}" />
	<input type="hidden" name="search_performed" value="{$smarty.request.search_performed}" />
	<input type="hidden" name="cid" value="{$smarty.request.cid}" />
	<input type="hidden" name="q" value="{$smarty.request.q}" />
{/if}
{if $smarty.request.sort_by}
	<input type="hidden" name="sort_by" value={$smarty.request.sort_by} />
	<input type="hidden" name="sort_order" value={$smarty.request.sort_order} />
{/if}
{if $smarty.request.company_id}
	<input type="hidden" name="company_id" value={$smarty.request.company_id} />
{/if}
{if $smarty.request.search_performed}
	<input type="hidden" name="search_performed" value={$smarty.request.search_performed} />
{/if}
{if $smarty.request.category_id}
	<input type="hidden" name="category_id" value={$smarty.request.category_id} />
	{assign var="filter_qstring" value=$filter_qstring|fn_link_attach:"subcats=Y"}
{/if}
{if $smarty.request.cid}
	<input type="hidden" name="cid" value={$smarty.request.cid} />
{/if}
    {assign var="filter_qstring" value=$filter_qstring|fn_link_attach:"search_performed=Y"}

{foreach from=$smarty.request.fac item="fac"}

{assign var="fac_fil" value=$fac_fil|cat:"$fac"|cat:","}

{/foreach} 

<div class="sidebox-wrapper ">
<div class="sidebox-body" id="slr_search">  

<!--  value 2:- {$allfilter.search_usage}
 --> <input type="hidden" name="z" value="{$allfilter.search_usage}" />

{*$allfilter|print_r*}
  <ul class="product-filters1 nav_mainmenu new_menu_link_nl" id="product-filters" style="margin: 0 0 10px -1px; overflow-y:scroll; max-height: 280px; overflow-x: hidden; min-height: 30px;" >
        {foreach from=$allfilter.category key="meta_cat_key" item="meta_cat_item"}
{if is_array($meta_cat_item) && !is_int($meta_cat_key)}
  <li>
        <div class="nav_mainmenu_label">
          <a class="new_link_nl_cate" href="javascript:void(0);"><div style="font:bold 11px/16px 'verdana', Arial, Helvetica, sans-serif; color:#000;" class="nl_cat_text_span">{$meta_cat_key}</div>
            </a>
        </div>
    </li>
        {foreach from=$meta_cat_item key="cat_id" item="cat" }
		{if $cat.count !=0}
			<li>
        <ul style="padding-left:13px; width:158px;" class="nav_submenu">
            <li><div class="nav_mainmenu_label">
            {if $smarty.request.cid == $cat.cat_id}                           
                <span style="display: block; font: bold 10px verdana; margin: 0 5px; padding: 5px; border-bottom:1px dotted #CCCCCC;">{$cat.cat_name} {if !$smarty.request.retain} ({$cat.count}) {/if} </span>
            {else}
                {if $smarty.request.retain} 
                    {assign var="curl" value=$config.current_url}
                    
                    {*assign var="remove_filter" value=$_REQUEST|fn_retain_url*}
                    
                    {assign var="retain_qstring" value=$curl|fn_query_remove:"result_ids":"filter_id":"view_all":"req_range_id":"advanced_filter":"features_hash":"subcats":"page":"cid":"fsrc":"br%5B%5D":"fq%5B%5D"}

                    <a href="{$retain_qstring}&fsrc=category:{$cat.cat_id}&cid={$cat.cat_id}" onclick="return deselect_filter(this);"> <div class="nl_cat_text_span">{$cat.cat_name}</div></a>
                {else}
                    <a href="{$filter_qstring}&fsrc=category:{$cat.cat_id}&cid={$cat.cat_id}" onclick="return deselect_filter(this);"> <div class="nl_cat_text_span">{$cat.cat_name} <span class="count" style="color:#888888;">({$cat.count})</span> </div></a>
                {/if}
            {/if}
            </div></li>
        </ul>
			</li>
		{/if}
        {/foreach}
{/if}            
        {/foreach}        
</ul>
<!-- {$allfilter.discount_percentage|print_r}
 --><div>
<h4 class="margin_top_ten slide_toggle_fct box_expand" style="background:none; margin-top:1px; cursor: pointer;" id="brands_fct_1" >By Brands {if !empty($smarty.request.br) || $fac_fil|strstr:"brand"}<span class="clear_filter">Clear</span>{/if}</h3>
<input name="brand_search" id="brand_search" placeholder="Search by brands" style="padding:4px;margin:4px;width:89%;" />
<ul id="brands_fct" class="brands_fct"  {if $allfilter.brand|count > 11} style="max-height: 300px; overflow: scroll; overflow-x: hidden; min-height: 30px; clear:both;" {else} style="max-height: 300px; overflow-x: hidden; min-height: 30px; clear:both;" {/if}>    
    {foreach from=$allfilter.brand key="brand_id" item="brand" }

    {assign var="filter_brand" value=""}
    {assign var="filter_brand" value=$filter_brand|cat:"brand@brand_id%3A"|cat:$brand.brand_id} 
    
        <li><p class="a_hover_cursor"><input type="checkbox" {if $brand.count eq "0"} disabled="disabled"{/if} id="sh_option{$brand.brand_id}" style="float:left; margin:0px 4px 0 0px" name="br[]" value="{$brand.brand_id}" rev="brand"  onclick="return new_search(this);" {if $brand.brand_id|in_array:$smarty.request.br || $filter_brand|in_array:$smarty.request.fac} checked {/if} /><span {if $brand.count == "0"} style="color:#999;" {/if} {if $brand.count != "0"} onclick="return chk_sh_option('{$brand.brand_id}');" {/if}><span {if $brand.count == "0"} style="color:#999;" {/if} class="pclass">{$brand.brand_name} </span> {if $brand.count !=0} <span class="count">({$brand.count})</span> {/if}</span></p></li>
    {/foreach}
</ul>
</div>

{if !empty($allfilter.is_trm)}
  
  {foreach from=$allfilter.is_trm name="ranges" key="rangekey" item="range"}
  {if $range.name==y}
  <div>
  <h4 class="margin_top_ten slide_toggle_fct box_expand" style="background:none; margin-top:1px; cursor: pointer;" id="trm_fct_1" >{$lang.trm_filter} {if !empty($smarty.request.is_trm) || $fac_fil|strstr:"is_trm"}<span class="clear_filter">Clear</span>{/if}</h4>

    {assign var="filter_is_trm" value="is_trm@is_trm%3A%221%22"} 
    
  <ul class="trm_fct search_dynamic_filter" id="trm_fct" style="clear:both;">
      <li><p class="a_hover_cursor"><input type="checkbox" {if $range.count eq "0"} disabled="disabled"{/if} id="sh_option{$range.id}" style="float:left; margin:0px 4px 0 0px" name="is_trm[]" value="{$range.id}" rev="is_trm"  onclick="return new_search(this);" {if $range.id|in_array:$smarty.request.is_trm || $filter_is_trm|in_array:$smarty.request.fac} checked {/if} /><span {if $range.count == "0"} style="color:#999;" {/if} {if $range.count != "0"} onclick="return chk_sh_option('{$range.id}');" {/if}>{$lang.trm_filter_value} {if $range.count !=0} <span class="count">({$range.count})</span> {/if}</span></p></li>
  </ul>
  </div>
  {/if}
  {/foreach}
  
{/if}

{if !empty($allfilter.is_cod)}
  
  {foreach from=$allfilter.is_cod name="ranges" key="rangekey" item="range"}
  {if $range.name=="y" && $range.count != 0}
  <div>
  <h4 class="margin_top_ten slide_toggle_fct box_expand" style="background:none; margin-top:1px; cursor: pointer;" id="cod_fct_1" >{$lang.solr_cod_filter} {if !empty($smarty.request.is_cod) || $fac_fil|strstr:"isCod"}<span class="clear_filter">Clear</span>{/if}</h4>
  
  {assign var="filter_is_cod" value="isCod@isCod%3A%220%22"} 
    
  <ul class="cod_fct search_dynamic_filter" id="cod_fct" style="clear:both;">
      <li><p class="a_hover_cursor"><input type="checkbox" {if $range.count eq "0"} disabled="disabled"{/if} id="sh_option{$range.id}" style="float:left; margin:0px 4px 0 0px" name="is_cod[]" value="{$range.id}" rev="is_cod"  onclick="return new_search(this);" {if $range.id|in_array:$smarty.request.is_cod || $filter_is_cod|in_array:$smarty.request.fac} checked {/if} /><span {if $range.count == "0"} style="color:#999;" {/if} {if $range.count != "0"} onclick="return chk_sh_option('{$range.id}');" {/if}>{$lang.solr_cod_filter_value} {if $range.count !=0} <span class="count">({$range.count})</span> {/if}</span></p></li>
  </ul>
  </div>
  {/if}
  {/foreach}
  
{/if}

{if !empty($allfilter.show_merchant)}
  <div>
  <h4 class="margin_top_ten slide_toggle_fct box_expand" style="background:none; margin-top:1px; cursor: pointer;" id="merchant_fct_1" >{$lang.show_merchant}{if !empty($smarty.request.show_merchant)}<span class="clear_filter">Clear</span>{/if}</h3>
  <input name="merchant_search" id="merchant_search" placeholder="Search by merchants" style="padding:4px;margin:4px;width:89%;"/>
  <ul class="merchant_fct search_dynamic_filter" id="merchant_fct" {if $allfilter.merchant|count > 11} style="max-height: 300px; overflow: scroll; overflow-x: hidden; min-height: 30px;" {else} style="max-height: 300px; overflow-x: hidden; min-height: 30px;clear: both;" {/if}>        

      {foreach from=$allfilter.show_merchant name="ranges" key="rangekey" item="range"}

      <li><p class="a_hover_cursor"><input type="checkbox" {if $range.count eq "0"} disabled="disabled"{/if} id="sh_option{$range.id}" style="float:left; margin:0px 4px 0 0px" name="show_merchant[]" value={$range.id} rev="show_merchant"  onclick="return new_search(this);" {if $range.id|in_array:$smarty.request.show_merchant} checked {/if} /><span {if $range.count == "0"} style="color:#999;" {/if} {if $range.count != "0"} onclick="return chk_sh_option('{$range.id}');" {/if} class="pclass" >{$range.name} {if $range.count !=0} <span class="count">({$range.count})</span> {/if}</span></p></li>

  {/foreach}
  </ul>
  </div>
{/if}
<div>
<h4 class="margin_top_ten  slide_toggle_fct box_expand" style="background:none; margin-top:1px; cursor: pointer;"  id="price_fct_1">By Price {if !empty($smarty.request.fq) || $fac_fil|strstr:"price"}<span class="clear_filter">Clear</span>{/if}</h3>
 <ul class="price_fct" id="price_fct" style="clear:both;">    
       {foreach from=$allfilter.price name="pricename" key="pricekey" item="priceval" }
		{assign var="shkey" value=$priceval.key|replace:' ':''} 
		{assign var="shkey1" value=$priceval.key|fn_assign_pricekey:"val"}
		{assign var="shval" value=$shkey1|fn_showprice}
    {assign var="filter_price" value=""}
   
    {assign var="price_encode" value=$priceval.key|replace:'*':'$'}
    {assign var="price_encode" value=$price_encode|escape:'url'}
    {assign var="price_encode" value=$price_encode|replace:'%24':'*'}
    {assign var="price_encode" value=$price_encode|replace:'%20':'+'}

    {assign var="filter_price" value=$filter_price|cat:"price@sort_price%3A"|cat:$price_encode} 
    
               <li><p class="a_hover_cursor"><input type="checkbox" {if $priceval.val eq "0"} disabled="disabled"{/if} id="sh_option{$shkey1}" style="float:left; margin:0px 4px 0 0px" name="fq[]" rev="price" value={$shkey1} onclick="return new_search(this);" {if $shkey1|in_array:$smarty.request.fq || $filter_price|in_array:$smarty.request.fac } checked {/if} /><span {if $priceval.val == "0"} style="color:#999;" {/if} {if $priceval.val != "0"} onclick="return chk_sh_option('{$shkey1}');" {/if}>{$shval} {if $priceval.val !=0} <span class="count">({$priceval.val})</span> {/if}</span></p></li>
        {/foreach}
     </ul>  
 </div>
{if $config.category_solr }
{foreach from=$allfilter name="filters" key="filterkey" item="filter"}
          {if !empty($filter) && !in_array($filterkey, $config.solr_exclude_filters)}
{*$smarty.request.$filterkey|print_r*}
<div>
    {assign var="filtername" value=$filterkey|fn_show_solr_filterkey}
    {assign var="is_global" value=$filtername|strstr:"O "}
    {if !empty($is_global)}
      {assign var="showname" value=$filtername|substr:2}
    {else}
      {assign var="showname" value=$filterkey|fn_show_solr_filterkey}
    {/if}

		<h4 class="margin_top_ten slide_toggle_fct box_expand" style="background:none; margin-top:1px; cursor: pointer;"  id="{$filterkey}_fct_1">{$showname} {if !empty($smarty.request.$filterkey)}<span class="clear_filter">Clear</span>{/if}</h3>
		<ul id="{$filterkey}_fct" class="search_dynamic_filter" style="clear:both;overflow-y:auto; max-height: 280px; overflow-x: hidden; min-height: 30px;" >
		{foreach from=$filter name="ranges" key="rangekey" item="range"}
		{if $range.count !=0}
 			{if empty($is_global)}
        <li><p class="a_hover_cursor"><input  type="checkbox" {if $range.count eq "0"} disabled="disabled"{/if} id="sh_option{$range.id}" style="float:left; margin:0px 4px 0 0px;" name="{$filterkey}[]" value="{$range.id}" rev="{$filterkey}"  onclick="return new_search(this);" {if $range.id|in_array:$smarty.request.$filterkey} checked {/if} /><span {if $range.count == "0"} style="color:#999;" {/if} {if $range.count != "0"} onclick="return chk_sh_option('{$range.id}');" {/if}>{$range.name} {if $range.count !=0} {if empty($is_global)}<span class="count">({$range.count})</span> {/if} {/if}</span></p></li>
      {else}
        {if $filtername|strstr:"Color"}
          <li style="background-color:{$range.name};float: left; width: auto; height:25px; width:25px;border-bottom:1px solid #eee !important;"><input  type="checkbox" {if $range.count eq "0"} disabled="disabled"{/if} id="sh_option{$range.id}" style="float:left; margin:0px 4px 0 0px; display: none;" name="{$filterkey}[]" value="{$range.id}" rev="{$filterkey}"  onclick="return new_search(this);" {if $range.id|in_array:$smarty.request.$filterkey} checked {/if} />
          <span class="img_colorHover_check {if $range.id|in_array:$smarty.request.$filterkey} select {/if}" alt="{$range.name}" title="{$range.name}" {if $range.count != "0"} onclick="return chk_sh_option('{$range.id}');" {/if}>&nbsp;</span></li>
        {else}          
          <li style="border-bottom: 1px solid #ccc !important;" class="globalOptions {if $range.id|in_array:$smarty.request.$filterkey}select{/if}"><input  type="checkbox" {if $range.count eq "0"} disabled="disabled"{/if} id="sh_option{$range.id}" style="float:left; margin:0px 4px 0 0px; display: none;" name="{$filterkey}[]" value="{$range.id}" rev="{$filterkey}"  onclick="return new_search(this);" {if $range.id|in_array:$smarty.request.$filterkey} checked {/if} />
          <span {if $range.count != "0"} onclick="return chk_sh_option('{$range.id}');" {/if}>{$range.name} {if $range.count !=0} {if empty($is_global)}<span class="count">({$range.count})</span> {/if} {/if}</span></li>
        {/if}         
      {/if}
 		{/if}
		{/foreach}
		</ul>
</div>
 	{/if}
{/foreach}
    
<div>
 <h4 class="margin_top_ten  slide_toggle_fct box_expand" style="background:none; margin-top:1px; cursor: pointer;"  id="discount_fct_1">By Discount {if !empty($smarty.request.df) || $fac_fil|strstr:"discount"}<span class="clear_filter">Clear</span>{/if}</h3>
 <ul class="discount_fct" id="discount_fct" style="clear:both;">    
       {foreach from=$allfilter.discount_percentage name="pricename" key="pricekey" item="priceval" }
        {assign var="shkey" value=$priceval.key|replace:' ':''} 
        {assign var="shkey1" value=$priceval.key|fn_assign_discountkey:"val"}
        {assign var="shval" value=$shkey1|fn_showdiscount}

        {assign var="filter_discount" value=""}
        {assign var="discount_encode" value=$priceval.key|replace:'*':'$'}

    {assign var="discount_encode" value=$discount_encode|escape:'url'}
    {assign var="discount_encode" value=$discount_encode|replace:'%24':'*'}
    {assign var="discount_encode" value=$discount_encode|replace:'%20':'+'}

{assign var="filter_discount" value=$filter_discount|cat:"discount@discount_percentage%3A"|cat:$discount_encode} 
    
                <li><p class="a_hover_cursor"><input type="checkbox" {if $priceval.val eq "0"} disabled="disabled"{/if} id="sh_option{$shkey1}" style="float:left; margin:0px 4px 0 0px" name="df[]" rev="discount_percentage" value={$shkey1} onclick="return new_search(this);" {if $shkey1|in_array:$smarty.request.df || $filter_discount|in_array:$smarty.request.fac} checked {/if} /><span {if $priceval.val == "0"} style="color:#999;" {/if} {if $priceval.val != "0"} onclick="return chk_sh_option('{$shkey1}');" {/if}>{$shval} {if $priceval.val !=0} <span class="count">({$priceval.val})</span> {/if}</span></p></li>
        {/foreach}
     </ul> 
</div>

<div>
<h4 class="margin_top_ten slide_toggle_fct box_expand" style="background:none; margin-top:1px; cursor: pointer;" id="percentage_fct_1">Availability {if !empty($smarty.request.product_amount_available) || $fac_fil|strstr:"inStock"}<span class="clear_filter">Clear</span>{/if}</h3>
<ul class="percentage_fct" id="percentage_fct" style="clear:both;">
    
{foreach from=$allfilter.product_amount_available name="ranges" key="rangekey" item="range"}
{if $range.name==1}

  {assign var="filter_count" value="inStock@inStock:true"}
        <li><p class="a_hover_cursor"><input type="checkbox" {if $range.count eq "0"} disabled="disabled"{/if} id="sh_option{$range.name}" style="float:left; margin:0px 4px 0 0px" name="product_amount_available[]" value="{$range.name}" rev="product_amount_available"  onclick="return new_search(this);" {if $range.name|in_array:$smarty.request.product_amount_available || $filter_count|in_array:$smarty.request.fac} checked {/if} /><span {if $range.count == "0"} style="color:#999;" {/if} {if $range.count != "0"} onclick="return chk_sh_option('{$range.name}');" {/if}>Exclude Out of stock</span></p></li>
{/if}
{/foreach}
</ul>
</div>

{/if}
        <input type="hidden" id="fsrc" name="fsrc" value="" />
       {foreach from=$smarty.request.x name="xname" item="xrange"}
            <input type="hidden" id="x" name="x[]" value="{$xrange}">
       {/foreach}
{if $smarty.request.retain} 
     <input type="hidden" id="retain" name="retain" value="1" />
     <input type="hidden" id="img" name="img" value="{$smarty.request.img}" />
     <input type="hidden" id="name" name="name" value="{$smarty.request.name}" />
     <input type="hidden" id="promofilter" name="promofilter" value="{$smarty.request.promofilter}" />
{/if}        
	<input type="submit" name="dispatch" value="{$curr_url}" id="newsearch_filter" style="display:none;" />
</form>
</div>

</div>
</div>
{/if}
{literal}
<script>

{/literal}{if $allfilter.search_usage}{literal}var use_zettata = {/literal} {$allfilter.search_usage};{else}{literal}var use_zettata =0;{/literal}{/if}{literal}

if(use_zettata == 0){


$(document).ready(function(){

$("#slr_search").children().each(function(i,div){
  if($(div).find("ul").find("li").length == 0){
    $(this).hide();
  }
});

$('.clear_filter').click(function(event){
  event.stopPropagation();
    $(this).parent().parent().find('li').find('input:checkbox').removeAttr('checked');
    document.getElementById('newsearch_filter').click();
  });

if($(window).width()<630)
	{
$('.box_expand').each(function(i, obj) {
$(this).addClass("box_collapse").removeClass("box_expand");
});
	}

  
});

function deselect_filter(obj) {
    $("#slr_search input:checkbox").attr("checked", false);
    //document.getElementById('newsearch_filter').click();
    //$('#newsearch_filter').submit(function (){
    //});
}

function new_search(obj){
    var fsrc = $(obj).attr('rev');
    $('#fsrc').val(fsrc+':'+$(obj).val());
    document.getElementById('newsearch_filter').click();
}
function deselect_all(obj){
	$('#option_unchk').attr('checked','checked');
	$('input:checkbox').removeAttr('checked');
	document.getElementById('newsearch_filter').click();
}
function chk_sh_option(obj){
        var fsrc = $('#sh_option'+obj).attr('rev');
        $('#fsrc').val(fsrc+':'+$('#sh_option'+obj).val());
	if($('#sh_option'+obj+':checkbox:checked').length == "1"){
		$('#sh_option'+obj).removeAttr("checked");
        } else {
                $('#sh_option'+obj).attr('checked','checked');
        }
	document.getElementById('newsearch_filter').click();
}
    
    $('.slide_toggle_fct').click(function(){
        var target_id = $(this).next().attr('id');
        $('#'+target_id).slideToggle();
        if($('#'+this.id).attr('id')=="brands_fct_1") { $('#brands_fct').slideToggle(); }
        if($('#'+this.id).attr('id')=="merchant_fct_1") { $('#merchant_fct').slideToggle(); }

        if($('#'+this.id).hasClass('box_expand')){
           $('#'+this.id).removeClass('box_expand'); 
           $('#'+this.id).addClass('box_collapse');
        }else{
           $('#'+this.id).removeClass('box_collapse');
           $('#'+this.id).addClass('box_expand');            
        }
    });

//alert($("#product-filters").find("li").length);
    if($("#product-filters").find("li").length == 0) {
        $(".sidebox-title").parent('.sidebox-wrapper').hide();
    } else {
        if($('#product-filters').height()< 280) {
            $('#product-filters').css('overflow-y',''); 
        }
    }

$('input[name="brand_search"]').keyup(function (element) {
    var wordToSearch = $(element.target).val();
    $("#brands_fct li p .pclass").each(function(index, obj) {
    var s = new RegExp("^"+wordToSearch, "gi");
     if($(obj).html().match(s)){
      $(obj).parent().parent().show();
     }
     else{
      $(obj).parent().parent().hide();
     }
    });
});

$('input[name="merchant_search"]').keyup(function (element) {

    var wordToSearch = $(element.target).val();
    $("#merchant_fct li p .pclass").each(function(index, obj) {
    var s = new RegExp("^"+wordToSearch, "gi");
     if($(obj).html().match(s)){
      $(obj).parent().parent().show();
     }
     else{
      $(obj).parent().parent().hide();
     }
    });
});
}    
</script>
<style>
.a_hover_cursor:hover{cursor:pointer;}
.count {color: #888;font-size: 10px;}
.clear_filter { font-size: 11px; cursor: pointer;font-weight: normal;font-variant: normal;float:right;}
.clear_filter:hover{ text-decoration: underline;}
</style>
{/literal}
