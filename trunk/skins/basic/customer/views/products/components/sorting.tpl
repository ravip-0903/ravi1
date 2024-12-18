{* $Id: sorting.tpl 10960 2010-10-20 07:23:04Z klerik $ *}

{if $settings.DHTML.customer_ajax_based_pagination == "Y"}
	{assign var="ajax_class" value="cm-ajax cm-ajax-force"}
{/if}
{assign var="meta_category" value=$category_data.id_path|fn_get_root_category}
{assign var="curl" value=$config.current_url|fn_query_remove:"sort_by":"sort_order":"result_ids":"layout"}
{assign var="sorting" value=""|fn_get_products_sorting:"false"}
{assign var="layouts" value=""|fn_get_products_views:false:false}
{assign var="pagination_id" value=$id|default:"pagination_contents"}

{if $search.sort_order == "asc"}
	{capture name="sorting_text"}
		<a class="sort-asc">{$sorting[$search.sort_by].description}</a>
	{/capture}
{else}
	{capture name="sorting_text"}
		<a class="sort-desc">{$sorting[$search.sort_by].description}</a>
	{/capture}
{/if}

{if $search.sort_order == "asc"}
	{assign var="layout_sort_order" value="desc"}
{else}
	{assign var="layout_sort_order" value="asc"}
{/if}


<div style="float:left; padding-bottom:5px; width:100%;">
{*{if !(($category_data.selected_layouts|count == 1) || ($category_data.selected_layouts|count == 0 && ""|fn_get_products_views:true|count <= 1)) && !$hide_layouts}
<div class="float-left">
<strong>{$lang.view_as}:</strong>&nbsp;
{capture name="tools_list"}
	<ul>
	{foreach from=$layouts key="layout" item="item"}
		{if ($category_data.selected_layouts.$layout) || (!$category_data.selected_layouts && $item.active)}
			<li><a class="{$ajax_class} {if $layout == $selected_layout}active{/if}" rev="{$pagination_id}" href="{"`$curl`&amp;sort_by=`$search.sort_by`&amp;sort_order=`$layout_sort_order`&amp;layout=`$layout`"|fn_url}" rel="nofollow" name="layout_callback">{$item.title}</a></li>
		{/if}
	{/foreach}
	</ul>
{/capture}
{include file="common_templates/tools.tpl" tools_list=$smarty.capture.tools_list suffix="view_as" link_text=$layouts.$selected_layout.title}
</div>
{/if}

<div class="right">
<strong>{$lang.sort_by}:</strong>&nbsp;
{capture name="tools_list"}
	<ul>
		{foreach from=$sorting key="option" item="value"}
			{if $search.sort_by == $option}
				{assign var="sort_order" value=$search.sort_order}
			{else}
				{if $value.default_order}
					{assign var="sort_order" value=$value.default_order}
				{else}
					{assign var="sort_order" value="asc"}
				{/if}
			{/if}
			<li><a class="{$ajax_class} {if $search.sort_by == $option}active{/if}" rev="{$pagination_id}" href="{"`$curl`&amp;sort_by=`$option`&amp;sort_order=`$sort_order`"|fn_url}" rel="nofollow" name="sorting_callback">{$value.description}{if $search.sort_by == $option}&nbsp;{if $search.sort_order == "asc"}<img src="{$images_dir}/icons/sort_asc.gif" width="7" height="6" border="0" alt="" />{else}<img src="{$images_dir}/icons/sort_desc.gif" width="7" height="6" border="0" alt="" />{/if}{/if}</a>
			</li>
		{/foreach}
	</ul>
{/capture}
{include file="common_templates/tools.tpl" tools_list=$smarty.capture.tools_list suffix="sort_by" link_text=$smarty.capture.sorting_text no_link=true}
</div>
</div>*}
<div class="clearboth"></div>

<div class="box_sorting">
{if $meta_category != $config.nrh_root_category_id}
<div class="box_sorting_viewby">
<label class="box_sorting_viewby_label no_mobile">View by :</label>
<a class="box_sorting_viewby_viewlist" rev="pagination_contents" name="layout_callback" 
   rel="nofollow" href="javascript: void(0);" onclick="change_view('{$smarty.server.REQUEST_URI}','products')"></a>
<a class="box_sorting_viewby_viewgrid" rev="pagination_contents" name="layout_callback" 
   rel="nofollow" href="javascript: void(0);" onclick="change_view('{$smarty.server.REQUEST_URI}','products_grid')"></a>
   
 <!--  <a class="box_sorting_viewby_viewgrid" rev="pagination_contents" name="layout_callback" 
   rel="nofollow" href="{"categories.view&category_id=`$category_data.category_id`&sort_by=popularity&sort_order=desc&layout=products_grid"|fn_url}"></a>-->
</div>
{/if} 

<a class="mobile mob_filter_icn">
    <img src="http://cdn.shopclues.com/images/banners/icons/filter_design_mobile.jpg">
</a>

<div class="box_sorting_sortby">
{assign var="sorting" value=$smarty.request.sort_by|cat:"+"|cat:$smarty.request.sort_order}
<label class="box_sorting_sortby_label no_mobile">Sort by :</label>
<select name="" class="box_sorting_sortby_listbox" onchange="sort_products(this.value)">
    {if $config.isResponsive}
		{if $controller =="products" && $mode=="search"}
        	<option value="score+desc" {if $sorting == "score+desc"} selected="selected"{/if} >{$lang.relevance}</option>
        {else}
            <option  class="mobile" value="">{$lang.default_select_sort}</option>
        {/if}
        
     {else}
        {if $controller =="products" && $mode=="search"}
			<option class="no_mobile" value="score+desc" {if $sorting == "score+desc"} selected="selected"{/if} >{$lang.relevance}</option>
        {else}
            <option class="no_mobile" value="">{$lang.default_select_sort}</option>
        {/if}
    {/if}
    
	<option value="price+asc" {if $sorting == "price+asc"} selected="selected"{/if} >{$lang.cheapest_first}</option>
	<option value="price+desc" {if $sorting == "price+desc"} selected="selected"{/if} >{$lang.expensive_first}</option>
	<option value="product+asc" {if $sorting == "product+asc"} selected="selected"{/if} >{$lang.atoz}</option>
	<option value="product+desc" {if $sorting == "product+desc"} selected="selected"{/if} >{$lang.ztoa}</option>
	<option value="popularity+desc" {if $sorting == "popularity+desc"} selected="selected"{/if}  >{$lang.popular}</option>
	<option value="bestsellers+desc" {if $sorting == "bestsellers+desc"} selected="selected"{/if} >{$lang.bestseller}</option>
	<option value="newarrivals+desc" {if $sorting == "newarrivals+desc"} selected="selected"{/if} >{$lang.newarrivals}</option>
	<option value="hotdeals+desc" {if $sorting == "hotdeals+desc"} selected="selected"{/if} >{$lang.short_deals}</option>
	<option value="featured+desc" {if $sorting == "featured+desc"} selected="selected"{/if} >{$lang.featured}</option>
	
</select>

</div>
    
        
<!-- 
<div class="box_sorting_pageinfo">
Showing 1 - 15 of 15,735 Results
</div> -->
{assign var="per_page" value=""|cal_products_per_page}
{if !empty($per_page)}
<div class="box_sorting_sortby no_mobile no_tablet" style="float:right;background:none;">
    {assign var="products_per_page" value=$smarty.request.pp}
    <label class="box_sorting_sortby_label">{$lang.products_per_page}</label>
    <select style="width:54px;" name="" class="box_sorting_sortby_listbox" onchange="show_products_per_page(this.value)">
        {foreach from=$per_page name="namepage" key="keypage" item="itempage" }
            <option value="{$itempage}" {if $products_per_page == $itempage} selected="selected"{/if} >{$itempage}</option>
        {/foreach}        
    </select>
</div>
{/if}
</div>
</div>
{literal}
	<script type="text/javascript">		
		
		function change_view(req_uri, layout)
		{		
			var cur_url = req_uri;
			var arr = cur_url.split('?');
			var newurl=arr[0];
			if(arr.length>1)
			{
			    newurl = newurl + "?";
				var arr2 = arr[1].split('&');
				var found1 = 0;
				var and = '';
				for(var i=0;i<arr2.length;i++)
				{
				    var arr3 = arr2[i].split('=');
				    if(arr3[0] == 'layout')
				    {
				        found1 = 1;
				        newurl = newurl +and +arr3[0] + '=' + layout;
				        and='&';
				    }
				    else
				    {
				        newurl = newurl + and + arr2[i];
				        and='&';
				    }
				}
				if(found1==0)
				{
				    newurl = newurl + '&' +'layout=' + layout;
				}
			}
			else
			{
			 newurl =  newurl+'?layout=' + layout;
			}
			window.location = newurl;		
		}
		
		function sort_products(sort_by){
			sort_by = sort_by.split("+");
			var cur_url = window.location.toString();
			var arr = cur_url.split('?');
			var newurl=arr[0];
			if(arr.length>1)
			{
			    newurl = newurl + "?";
				var arr2 = arr[1].split('&');
				var found1 = 0;
				var found2 = 0;
				var and = '';
				for(var i=0;i<arr2.length;i++)
				{
				    var arr3 = arr2[i].split('=');
				    if(arr3[0] == 'sort_by')
				    {
				        found1 = 1;
				        newurl = newurl +and +arr3[0] + '=' + sort_by[0];
				        and='&';
				    }
				    else if(arr3[0] == 'sort_order')
				    {
				        found2=1;
				        newurl = newurl +and +arr3[0] + '=' + sort_by[1];
				        and='&';
				    }
				    else
				    {
				        newurl = newurl + and + arr2[i];
				        and='&';
				    }
				}
				if(found1==0)
				{
				    newurl = newurl + '&' +'sort_by=' + sort_by[0];
				}
				if(found2==0)
				{
				    newurl = newurl + '&' +'sort_order=' + sort_by[1];
				}
			}
			else
			{
			 newurl =  newurl+'?sort_by=' + sort_by[0] + '&sort_order='+sort_by[1];
			}
			window.location = newurl;
		}
function show_products_per_page(per_page){
			var cur_url = window.location.toString();
			var arr = cur_url.split('?');
			var newurl=arr[0];
			if(arr.length>1)
			{
			    newurl = newurl + "?";
				var arr2 = arr[1].split('&');
				var found3 = 0;
				var and = '';
				for(var i=0;i<arr2.length;i++)
				{
				    var arr3 = arr2[i].split('=');
				    if(arr3[0] == 'pp')
				    {
				        found3 = 1;
				        newurl = newurl +and +arr3[0] + '=' + per_page;
				        and='&';
				    }
				    else
				    {
				        newurl = newurl + and + arr2[i];
				        and='&';
				    }
				}
				if(found3==0)
				{
				    newurl = newurl + '&' +'pp=' + per_page;
				}
			}
			else
			{
                            
			 newurl =  newurl+'?pp=' + per_page;
			}
                        //alert(newurl);
			window.location = newurl;    
}                        
	</script>
{/literal}
