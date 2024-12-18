{* $Id: product_filters.tpl 12029 2011-03-14 08:12:03Z klerik $ *}
{** block-description:original **}
<!-- <pre>{$_REQUEST|print_r} -->
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
  <input type="hidden" name="search_performed" value="{$smarty.request.search_performed}" />
  <input type="hidden" name="cid" value="{$smarty.request.cid}" />
  <input type="hidden" name="q" value="{$smarty.request.q}" />
{/if}


{if $smarty.request.search_performed}
  <input type="hidden" name="search_performed" value={$smarty.request.search_performed} />
{/if}

{if $smarty.request.cid}
  <input type="hidden" name="cid" value={$smarty.request.cid} />
{/if}
    {assign var="filter_qstring" value=$filter_qstring|fn_link_attach:"search_performed=Y"}



<div class="sidebox-wrapper ">
<div class="sidebox-body" id="slr_search">  


 <!-- value 3:- {$allfilter.search_usage}
  --><input type="hidden" name="z" value="{$allfilter.search_usage}" />
<!-- <pre>{$allfilter.price|print_r} -->
  <ul class="product-filters1 nav_mainmenu new_menu_link_nl" id="product-filters" style="margin: 0 0 10px -1px; overflow-y:scroll; max-height: 280px; overflow-x: hidden; min-height: 30px;" >
        {foreach from=$allfilter.category key="meta_cat_key" item="meta_cat_item"}
{if is_array($meta_cat_item)}

    <li>
        <div class="nav_mainmenu_label">
          <a class="new_link_nl_cate" href="javascript:void(0);"><div style="font:bold 11px/16px 'verdana', Arial, Helvetica, sans-serif; color:#000;" class="nl_cat_text_span">{$meta_cat_key}</div>
            </a>
        </div>
    </li>
        {foreach from=$meta_cat_item key="cat_id" item="cat" }
        
      <li>
        <ul style="padding-left:13px; width:158px;" class="nav_submenu">
            <li><div class="nav_mainmenu_label">
            
            {if $smarty.request.cid == $cat_id }                           
                <span style="display: block; font: bold 10px verdana; margin: 0 5px; padding: 5px; border-bottom:1px dotted #CCCCCC;">{$cat.cat_name} {if !$smarty.request.retain} ({$cat.count}) {/if} </span>
            {else}
                <!-- {if $smarty.request.retain}  -->
                   
                    
                    <!-- {*assign var="remove_filter" value=$_REQUEST|fn_retain_url*} -->
                    
                    <!-- {assign var="filter_qstring" value=$filter_qstring|fn_query_remove:"fac"} -->

                    <!-- <a href="{$retain_qstring}&fsrc=category:{$cat_id}&cid={$cat_id}" onclick="return deselect_filter(this);"> <div class="nl_cat_text_span">{$cat.cat_name}</div></a>
                {else} -->
                
                    <a href="{$filter_qstring}&category={$cat.filter}&cid={$cat_id}" onclick="return deselect_filter(this);"> <div class="nl_cat_text_span">{$cat.cat_name} <span class="count" style="color:#888888;">({$cat.count})</span> </div></a>
                {/if}
            {/if}
            </div></li>
        </ul>
      </li>
    
        {/foreach}
{/if}            
        {/foreach}        
</ul>
{assign var="fac_fil" value=""}

{foreach from=$smarty.request.fac item="fac"}

{assign var="fac_fil" value=$fac_fil|cat:"$fac"|cat:","}

{/foreach} 

{foreach from=$allfilter.facet key="fac_id" item="facet" }
{assign var="facet_id" value=$fac_id|replace:' ':'_'}
<div>
<h4 class="margin_top_ten slide_toggle_fct box_expand" style="background:none; margin-top:1px; cursor: pointer;" id="{$facet_id}_fct_1" >{if $fac_id eq 'is_trm'}Top Rated Merchant {elseif $fac_id eq 'company' } Merchants {else} By {$fac_id} {/if} {if $fac_fil|strstr:"$fac_id" || ($fac_id =='brand' && !empty($smarty.request.br))}<span class="clear_filter">Clear</span>{/if}</h3>
{if $fac_id eq 'brand'}
<input name="brand_search" id="{$fac_id}_search" placeholder="Search by brands" style="padding:4px;margin:4px;width:89%;" />
{/if}
{if $fac_id eq 'company'}
<input name="company_search" id="{$fac_id}_search" placeholder="Search by Company" style="padding:4px;margin:4px;width:89%;" />
{/if}
<ul id="fct_{$facet_id}" class="brands_fct" {if $allfilter.brand|count > 11} style="max-height: 300px; overflow: scroll; overflow-x: hidden; min-height: 30px;" {else} style="max-height: 300px; overflow-x: hidden; min-height: 30px;" {/if}>    
    {foreach from=$facet key="item_id" item="fct_item" }
    {assign var="fac_val" value=""}
    {assign var="fac_val" value=$fac_val|cat:"$fac_id"|cat:"@"|cat:$fct_item.filter}
    <!-- {$fac_val} -->
        <li><p class="a_hover_cursor">
        <input type="checkbox" {if $fct_item.numDocs eq "0"} disabled="disabled"{/if} id="sh_option_{$fct_item.id}" 
        style="float:left; margin:0px 4px 0 0px" name="fac[]" value="{$fac_id}@{$fct_item.filter}" rev="{$fac_id}"  onclick="return new_zettata_search(this);" {if $fac_val|in_array:$smarty.request.fac || $fct_item.id|in_array:$smarty.request.br} checked {/if} />
        <span {if $fct_item.numDocs == "0"} style="color:#999;" {/if} {if $fct_item.numDocs != "0"} onclick="return chk_sh_option('{$fct_item.id}');" {/if}>
        <span {if $fct_item.numDocs == "0"} style="color:#999;" {/if} class="pclass">{if $fac_id eq 'is_trm'}Include TRM {else}{$fct_item.name}{/if} </span> 
        {if $fct_item.numDocs !=0} <span class="count">({$fct_item.numDocs})</span> {/if}
        </span></p></li>

    {/foreach}
</ul>
</div>       
{/foreach}

<div>
<h4 class="margin_top_ten  slide_toggle_fct box_expand" style="background:none; margin-top:1px; cursor: pointer;"  id="price_fct_1">By Price {if $fac_fil|strstr:"price" || !empty($smarty.request.fq)}<span class="clear_filter">Clear</span>{/if}</h3>
 <ul class="price_fct" id="price_fct" style="clear:both;">    
 
       {foreach from=$allfilter.price name="pricename" key="pricekey" item="priceval" }
       {assign var="shkey_val" value=""}
    {assign var="shkey_val" value=$shkey_val|cat:"price"|cat:"@"|cat:$priceval.filter} 
    {assign var="shkey1" value=$priceval.key}
    {assign var="shval" value=$shkey1|fn_zettata_showprice}
    {assign var="filter_price" value=$priceval.filter|fn_price_check}
  
                <li><p class="a_hover_cursor"><input type="checkbox" {if $priceval.val eq "0"} disabled="disabled"{/if} id="sh_option_{$shkey1}" style="float:left; margin:0px 4px 0 0px" name="fac[]" rev="price" value="{$shkey_val}" onclick="return new_zettata_search(this);" {if $shkey_val|in_array:$smarty.request.fac || $filter_price|in_array:$smarty.request.fq} checked {/if} /><span {if $priceval.val == "0"} style="color:#999;" {/if} {if $priceval.val != "0"} onclick="return chk_sh_option('{$shkey1}');" {/if}>{$shval} {if $priceval.val !=0} <span class="count">({$priceval.val})</span> {/if}</span></p></li>
 
        {/foreach}
     </ul>  
 </div>


<div>
 <h4 class="margin_top_ten  slide_toggle_fct box_expand" style="background:none; margin-top:1px; cursor: pointer;"  id="price_fct_1">By Discount {if $fac_fil|strstr:"discount" || !empty($smarty.request.df)}<span class="clear_filter">Clear</span>{/if}</h3>
 <ul class="discount_fct" id="discount_fct" style="clear:both;">    
       {foreach from=$allfilter.discount_percentage name="discname" key="disckey" item="discval" }
        {assign var="dis_key" value=$discval.key} 
        {assign var="disc_val" value=$dis_key|fn_zettata_showdiscount}
    
        {assign var="disckey_val" value=""}
    {assign var="disckey_val" value=$disckey_val|cat:"discount"|cat:"@"|cat:$discval.filter} 
    {assign var="filter_disc" value=$discval.filter|fn_discount_check}
     
                <li><p class="a_hover_cursor"><input type="checkbox" {if $discval.val eq "0"} disabled="disabled"{/if} id="sh_option_{$dis_key}" style="float:left; margin:0px 4px 0 0px" name="fac[]" rev="discount_percentage" value={$disckey_val} onclick="return new_zettata_search(this);" {if $disckey_val|in_array:$smarty.request.fac || $filter_disc|in_array:$smarty.request.df} checked {/if} />
                <span {if $discval.val == "0"} style="color:#999;" {/if} {if $discval.val != "0"} onclick="return chk_sh_option('{$dis_key}');" {/if}>{$disc_val} {if $discval.val !=0} <span class="count">({$discval.val})</span> {/if}</span></p></li>
        {/foreach}
     </ul> 
</div>

<div>
<h4 class="margin_top_ten slide_toggle_fct box_expand" style="background:none; margin-top:1px; cursor: pointer;" id="percentage_fct_1">Availability {if $fac_fil|strstr:"inStock" || !empty($smarty.request.product_amount_available)}<span class="clear_filter">Clear</span>{/if}</h3>

    {assign var="inStock_val" value=""}
    {assign var="inStock_val" value=$inStock_val|cat:"inStock"|cat:"@"|cat:"inStock:true"} 

<ul class="percentage_fct" id="percentage_fct" style="clear:both;">
       <li><p class="a_hover_cursor"><input type="checkbox" id="sh_option_instock" style="float:left; margin:0px 4px 0 0px" name="fac[]" value={$inStock_val} rev="inStock"  onclick="return new_zettata_search(this);" {if 
       $inStock_val|in_array:$smarty.request.fac || "1"|in_array:$smarty.request.product_amount_available} checked {/if} /><span onclick="return chk_sh_option('inStock:true');">Exclude Out of stock</span></p></li>
</ul>
</div>
<!-- {if $smarty.request.retain} 
     <input type="hidden" id="retain" name="retain" value="1" />
     <input type="hidden" id="img" name="img" value="{$smarty.request.img}" />
     <input type="hidden" id="name" name="name" value="{$smarty.request.name}" />
     <input type="hidden" id="promofilter" name="promofilter" value="{$smarty.request.promofilter}" />
{/if}  --> 
<input type="hidden" id="fsrc" name="fsrc" value="" />      
  <input type="submit" name="dispatch" value="{$curr_url}" id="newsearch_filter" style="display:none;" />
</form>
</div>

</div>
</div>

{literal}
<script>

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
    
}

function new_zettata_search(obj){
   
    var fsrc = $(obj).attr('rev');
    
    $('#fsrc').val(fsrc+':'+$(obj).val());
    document.getElementById('newsearch_filter').click();
}
function deselect_all(obj){

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
        if($('#'+this.id).attr('id')=="brand_fct_1") { $('#fct_brand').slideToggle(); }
        if($('#'+this.id).attr('id')=="company_fct_1") { $('#fct_company').slideToggle(); }

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
        //$(".sidebox-title").parent('.sidebox-wrapper').hide();
    } else {
        if($('#product-filters').height()< 280) {
            $('#product-filters').css('overflow-y',''); 
        }
    }

$('input[name="brand_search"]').keyup(function (element) {
    var wordToSearch = $(element.target).val();
    $("#fct_brand li p .pclass").each(function(index, obj) {
    var s = new RegExp("^"+wordToSearch, "gi");
     if($(obj).html().match(s)){
      $(obj).parent().parent().show();
     }
     else{
      $(obj).parent().parent().hide();
     }
    });
});

$('input[name="company_search"]').keyup(function (element) {
    var wordToSearch = $(element.target).val();
    $("#fct_company li p .pclass").each(function(index, obj) {
    var s = new RegExp("^"+wordToSearch, "gi");
     if($(obj).html().match(s)){
      $(obj).parent().parent().show();
     }
     else{
      $(obj).parent().parent().hide();
     }
    });
});
    
</script>
<style>
.a_hover_cursor:hover{cursor:pointer;}
.count {color: #888;font-size: 10px;}
.clear_filter { font-size: 11px; cursor: pointer;font-weight: normal;font-variant: normal;float:right;}
.clear_filter:hover{ text-decoration: underline;}
</style>
{/literal}
