
{* $Id: custom_categories_tree.tpl 12660 2011-06-09 13:19:38Z bimib $ *}

{if !$smarty.request.company_id}
{assign var="items" value=$smarty.request.category_id|get_left_category_structure}
{assign var="category_path" value ="/"|explode:$category_data.id_path}
<ul class="nav_mainmenu new_menu_link_nl" style="margin: 0 0 10px -1px;">
	<li>
        <div class="nav_mainmenu_label">
        	<a href="{"categories.view?category_id=`$items.0.category_id`"|fn_url}" {if $smarty.request.category_id == $items.0.category_id} class="new_link_nl_cate"{/if}><div class="nl_cat_text_span" style="font:bold 11px/16px 'verdana', Arial, Helvetica, sans-serif; color:#000;">{$items.0.category}</div>
            {if $items.0.show_as_new == 'Y'}
             	<div class="nav_mainmenu_iconnew"></div>
            {/if}</a>
            
        </div>
    </li>
    <li>
    	<ul  class="nav_submenu" style="padding-left:13px; width:158px;">
            {foreach from=$items.0.subcategories item="cur_cat"}
                <li><div class="nav_mainmenu_label"><a href="{"categories.view?category_id=`$cur_cat.category_id`"|fn_url}" {if $smarty.request.category_id == $cur_cat.category_id} class="new_link_nl_cate"{/if}><div class="nl_cat_text_span">{$cur_cat.category}</div>
                {if $cur_cat.show_as_new == 'Y'}
                    <div class="nav_mainmenu_iconnew"></div>
                {/if}</a>
                
                </div>
                </li>
                {if $cur_cat.subcategories && $cur_cat.category_id|in_array:$category_path}
                	<li>
                    	<ul  class="nav_submenu" style="padding-left:13px ; width:145px;">
                        	{foreach from=$cur_cat.subcategories item="cur_sub_cat"}
                            	<li><a href="{"categories.view?category_id=`$cur_sub_cat.category_id`"|fn_url}" {if $smarty.request.category_id == $cur_sub_cat.category_id} class="new_link_nl_cate"{/if}><div class="nl_cat_text_span">{$cur_sub_cat.category}</div>
                                {if $cur_sub_cat.show_as_new == 'Y'}
                                    <div class="nav_mainmenu_iconnew"></div>
                                {/if}</a>
                                
                                </li>
                                {if $cur_sub_cat.subcategories && $cur_sub_cat.category_id|in_array:$category_path}
                                    <li>
                                        <ul  class="nav_submenu" style="padding-left:13px; width:132px;">
                                            {foreach from=$cur_sub_cat.subcategories item="cur_sub_sub_cat"}
                                                <li><a href="{"categories.view?category_id=`$cur_sub_sub_cat.category_id`"|fn_url}" {if $smarty.request.category_id == $cur_sub_sub_cat.category_id} class="new_link_nl_cate"{/if}><div class="nl_cat_text_span">{$cur_sub_sub_cat.category}</div>
                                                {if $cur_sub_sub_cat.show_as_new == 'Y'}
                                                        <div class="nav_mainmenu_iconnew"></div>
                                                    {/if}</a>
                                                	
                                                </li>
                                                {if $cur_sub_sub_cat.subcategories && $cur_sub_sub_cat.category_id|in_array:$category_path}
                                                    <li>
                                                        <ul  class="nav_submenu" style="padding-left:13px; width:119px;">
                                                            {foreach from=$cur_sub_sub_cat.subcategories item="cur_sub_sub_sub_cat"}
                                                                <li><a href="{"categories.view?category_id=`$cur_sub_sub_sub_cat.category_id`"|fn_url}" {if $smarty.request.category_id == $cur_sub_sub_sub_cat.category_id} class="new_link_nl_cate"{/if}><div class="nl_cat_text_span">{$cur_sub_sub_sub_cat.category}</div>
                                                                {if $cur_sub_sub_sub_cat.show_as_new == 'Y'}
                                                                        <div class="nav_mainmenu_iconnew"></div>
                                                                    {/if}</a>
                                                                	
                                                                </li>
                                                            {/foreach}
                                                        </ul>
                                                    </li>
                                                {/if}
                                            {/foreach}
                                        </ul>
                                    </li>
                                {/if}
                            {/foreach}
                        </ul>
                    </li>
                {/if}
            {/foreach}
        </ul>
    </li>
</ul>

{if $config.isResponsive}
    {if !($controller == "categories" && $mode == "view")}
    <ul class="nav_mainmenu new_menu_link_nl" style="margin: 0 0 10px -1px;">
        <li id="all_cat" class="all_cat_nl_cate" style="font:bold 15px Trebuchet MS; color:#3A3939; background-color:#CDCDCD;">{$lang.show_all_categories}</li>
        <div id="all_categories" style="display:none;">
            {strip}
                {assign var="foreach_name" value="cats_$cid"}

                {if $smarty.request.company_id}

                    {foreach from=$items.1 item="category" name=$foreach_name}

                        {assign var="company_data" value=$smarty.request.company_id|fn_get_company_data}

                        {assign var="merchant_category" value=$company_data.custom_category_ids|fn_get_merchant_category}
                        {if ($category.category_id|in_array:$merchant_category)}

                            <li class="cate_nl_hover_cond">
                                <a href="{"categories.view?category_id=`$category.category_id`&company_id=`$smarty.request.company_id`"|fn_url}" style="background-image:none;">
                                    <div class="nav_mainmenu_label">{$category.category}</div></a></li>

                        {/if}
                    {/foreach}
                {else}
                    {assign var="sl_no" value=1}
                    {foreach from=$items.1 item="category" name=$foreach_name}
                        {assign var="popular_brands" value=$category.category_id|fn_get_popular_brands}
                        <li class="cate_nl_hover_cond">
                            {if (!$category.category_id|in_array:$config.category_hide_subcategory) && !$category.category_id|in_array:$category_path}
                                {if $category.subcategories}
                                    <div id="box{$sl_no}" class="box_submenu {if $popular_brands}box_submenu_background{/if}" style="margin-top:34px;">
                                        <div style="float:left;">

                                            {$category.banner_html|html_entity_decode}
                                            <div class="clearboth"></div>

                                            <div class="box_submenu_Column">
                                                <ul class="nav_submenu">
                                                    <li>
                                                        {foreach from=$category.subcategories item="level2" name="lvl2"}
                                                            {if $smarty.foreach.lvl2.iteration <= $config.second_level_menu_limit/2 }
                                                                <a href="{"categories.view?category_id=`$level2.category_id`"|fn_url}">
                                                                    <div class="float_left" {if $level2.category_id|in_array:$config.temp_category_id}style="color:red;"{/if}>{$level2.category}</div>
                                                                    {if $level2.subcategories}
                                                                        <div class="nav_submenu_bulletCategory"></div>
                                                                    {/if}
                                                                    {if $level2.show_as_new == 'Y'}
                                                                        <div class="nav_submenu_iconnew"></div>
                                                                    {/if}
                                                                </a>
                                                                {if $level2.subcategories}
                                                                    <ul class="nav_submenu_category">
                                                                        {foreach from=$level2.subcategories item="level3" name="lvl3"}
                                                                            {if $smarty.foreach.lvl3.iteration <= $config.third_level_menu_limit-1}
                                                                                <li> <a href="{"categories.view?category_id=`$level3.category_id`"|fn_url}">{$level3.category}</a></li>
                                                                            {elseif $smarty.foreach.lvl3.iteration == $config.third_level_menu_limit}
                                                                                <li>
                                                                                    <a href="{"categories.view?category_id=`$level2.category_id`"|fn_url}" class="nav_submenu_category_more">More...</a>
                                                                                </li>
                                                                            {/if}
                                                                        {/foreach}
                                                                    </ul>
                                                                {/if}
                                                            {/if}
                                                        {/foreach}
                                                    </li>
                                                </ul>
                                            </div>

                                            {if $category.subcategories|count > $config.second_level_menu_limit/2 }
                                                <div class="box_submenu_Column">
                                                    <ul class="nav_submenu">
                                                        {foreach from=$category.subcategories item="level2" name="lvl2"}
                                                            {if $smarty.foreach.lvl2.iteration > $config.second_level_menu_limit/2 &&  $smarty.foreach.lvl2.iteration <= $config.second_level_menu_limit-1}
                                                                <li>
                                                                    <a href="{"categories.view?category_id=`$level2.category_id`"|fn_url}"><div class="float_left" {if $level2.category_id|in_array:$config.temp_category_id}style="color:red;"{/if}>{$level2.category}</div>
                                                                        {if $level2.subcategories}
                                                                            <div class="nav_submenu_bulletCategory"></div>
                                                                        {/if}
                                                                        {if $level2.show_as_new == 'Y'}
                                                                            <div class="nav_submenu_iconnew"></div>
                                                                        {/if}
                                                                    </a>
                                                                    {if $level2.subcategories}
                                                                        <ul class="nav_submenu_category">
                                                                            {foreach from=$level2.subcategories item="level3"}
                                                                                <li> <a href="{"categories.view?category_id=`$level3.category_id`"|fn_url}">{$level3.category}</a></li>                               {/foreach}
                                                                        </ul>
                                                                    {/if}
                                                                </li>
                                                            {elseif $smarty.foreach.lvl2.iteration == $config.second_level_menu_limit}
                                                                <li>
                                                                    <a href="{"categories.view?category_id=`$category.category_id`"|fn_url}" class="nav_submenu_more">More...</a>
                                                                </li>
                                                            {/if}
                                                        {/foreach}
                                                    </ul>
                                                </div>
                                            {else}
                                            {literal}
                                                <script>
                                                    $("#box"+{/literal}{$sl_no}{literal}).ready(function(){$("#box"+{/literal}{$sl_no}{literal}).width(500);});
                                                </script>
                                            {/literal}

                                            {/if}
                                        </div>
                                        {assign var="sl_no" value=$sl_no+1}

                                        {if $popular_brands}
                                            <div class="box_submenuBrand">
                                                <h3 class="box_submenuBrand_heading">Popular Brands</h3>
                                                <ul class="box_submenuBrand_brand">
                                                    {foreach from=$popular_brands item="brand"}
                                                        <li><!--<a href="{"product_features.view&variant_id=`$brand.varient_id`"|fn_url}">{$brand.varient_name}</a>
                                --><a href="{"categories.view&category_id=`$category.category_id`&subcats=Y&features_hash=V`$brand.varient_id`"|fn_url}">{$brand.varient_name}</a></li>
                                                    {/foreach}
                                                </ul>
                                            </div>
                                        {/if}
                                    </div>
                                    <div class="clearboth"></div>
                                {/if}
                            {/if}
                            {if !$category.category_id|in_array:$category_path}
                                <a href="{"categories.view?category_id=`$category.category_id`"|fn_url}">
                                    <div class="nav_mainmenu_label" {if $category.category_id == $lang.48hrsale_category_id || $category.category_id == $lang.24hrsale_category_id} style="color:#FE0201;font-weight:700;" {/if}>
                                        {$category.category}
                                    </div>
                                    {if $category.category_id == $lang.48hrsale_category_id || $category.category_id == $lang.24hrsale_category_id}
                                        <!--<div id="timer" class="nav_timer"></div>-->
                                    {/if}

                                    {if $category.show_as_new == 'Y'}
                                        <div class="nav_mainmenu_iconnew"></div>
                                    {/if}
                                </a>
                            {/if}

                        </li>
                        {if $separated && !$smarty.foreach.$foreach_name.last}

                        {/if}
                    {/foreach}
                {/if}
            {/strip}

    </ul>
    {/if}
{else}

<ul class="nav_mainmenu new_menu_link_nl" style="margin: 0 0 10px -1px;">    
	<li id="all_cat" class="all_cat_nl_cate" style="font:bold 15px Trebuchet MS; color:#3A3939; background-color:#CDCDCD;">{$lang.show_all_categories}</li>
    <div id="all_categories" style="display:none;">
    {strip}
    {assign var="foreach_name" value="cats_$cid"}
    
    {if $smarty.request.company_id}
    
        {foreach from=$items.1 item="category" name=$foreach_name}
        
            {assign var="company_data" value=$smarty.request.company_id|fn_get_company_data}
            
            {assign var="merchant_category" value=$company_data.custom_category_ids|fn_get_merchant_category}
            {if ($category.category_id|in_array:$merchant_category)}
            
                <li class="cate_nl_hover_cond">
                <a href="{"categories.view?category_id=`$category.category_id`&company_id=`$smarty.request.company_id`"|fn_url}" style="background-image:none;">
                 <div class="nav_mainmenu_label">{$category.category}</div></a></li>
            
            {/if}
        {/foreach}
    {else}
    {assign var="sl_no" value=1}
    {foreach from=$items.1 item="category" name=$foreach_name}
    {assign var="popular_brands" value=$category.category_id|fn_get_popular_brands}
    <li class="cate_nl_hover_cond">
    {if (!$category.category_id|in_array:$config.category_hide_subcategory) && !$category.category_id|in_array:$category_path}
     {if $category.subcategories}           
            <div id="box{$sl_no}" class="box_submenu {if $popular_brands}box_submenu_background{/if}" style="margin-top:34px;">               
				<div style="float:left;">        
                	
                {$category.banner_html|html_entity_decode}            
	            <div class="clearboth"></div>

                <div class="box_submenu_Column">
                    <ul class="nav_submenu">
                        <li>
                            {foreach from=$category.subcategories item="level2" name="lvl2"}
                                {if $smarty.foreach.lvl2.iteration <= $config.second_level_menu_limit/2 }
                                    <a href="{"categories.view?category_id=`$level2.category_id`"|fn_url}">
                                    <div class="float_left" {if $level2.category_id|in_array:$config.temp_category_id}style="color:red;"{/if}>{$level2.category}</div>
                                    {if $level2.subcategories}
                                    <div class="nav_submenu_bulletCategory"></div>
                                    {/if}
                                    {if $level2.show_as_new == 'Y'}
                                    <div class="nav_submenu_iconnew"></div>
                                    {/if}
                                    </a>
                                    {if $level2.subcategories}
                                        <ul class="nav_submenu_category">
                                            {foreach from=$level2.subcategories item="level3" name="lvl3"}
                                             {if $smarty.foreach.lvl3.iteration <= $config.third_level_menu_limit-1}
                                                 <li> <a href="{"categories.view?category_id=`$level3.category_id`"|fn_url}">{$level3.category}</a></li>
                                                {elseif $smarty.foreach.lvl3.iteration == $config.third_level_menu_limit}
                                                    <li>
                                                        <a href="{"categories.view?category_id=`$level2.category_id`"|fn_url}" class="nav_submenu_category_more">More...</a>
                                                    </li>
                                                {/if}
                                          {/foreach}
                                        </ul>
                                    {/if}                         
                                {/if}                         
                            {/foreach}
                        </li>            
                    </ul>
             	</div>
                
                {if $category.subcategories|count > $config.second_level_menu_limit/2 }
                    <div class="box_submenu_Column">
                    <ul class="nav_submenu">                    
                            {foreach from=$category.subcategories item="level2" name="lvl2"}                        
                                {if $smarty.foreach.lvl2.iteration > $config.second_level_menu_limit/2 &&  $smarty.foreach.lvl2.iteration <= $config.second_level_menu_limit-1}
                              <li>
                                     <a href="{"categories.view?category_id=`$level2.category_id`"|fn_url}"><div class="float_left" {if $level2.category_id|in_array:$config.temp_category_id}style="color:red;"{/if}>{$level2.category}</div>
                                     {if $level2.subcategories}
                                     <div class="nav_submenu_bulletCategory"></div>
                                     {/if}
                                     {if $level2.show_as_new == 'Y'}
                                        <div class="nav_submenu_iconnew"></div>
                                    {/if}
                                     </a>
                                        {if $level2.subcategories}
                                            <ul class="nav_submenu_category">
                                            {foreach from=$level2.subcategories item="level3"}
                                                   <li> <a href="{"categories.view?category_id=`$level3.category_id`"|fn_url}">{$level3.category}</a></li>                               {/foreach}
                                            </ul>
                                        {/if}
                                 </li>
                                {elseif $smarty.foreach.lvl2.iteration == $config.second_level_menu_limit}
                                    <li>
                                     <a href="{"categories.view?category_id=`$category.category_id`"|fn_url}" class="nav_submenu_more">More...</a>
                                    </li>
                                {/if}
                            {/foreach}                    
                        </ul>
                    </div> 
            {else}
             {literal}
			 <script>
              $("#box"+{/literal}{$sl_no}{literal}).ready(function(){$("#box"+{/literal}{$sl_no}{literal}).width(500);});
              </script>
              {/literal}

                 {/if}
                 </div>             
                 {assign var="sl_no" value=$sl_no+1}           
                 
                {if $popular_brands}                
                    <div class="box_submenuBrand">
                        <h3 class="box_submenuBrand_heading">Popular Brands</h3>
                        <ul class="box_submenuBrand_brand">
                        {foreach from=$popular_brands item="brand"}
                         <li><!--<a href="{"product_features.view&variant_id=`$brand.varient_id`"|fn_url}">{$brand.varient_name}</a>
                                --><a href="{"categories.view&category_id=`$category.category_id`&subcats=Y&features_hash=V`$brand.varient_id`"|fn_url}">{$brand.varient_name}</a></li> 
                        {/foreach}
                        </ul>   
                    </div> 
                {/if}
          </div>
            <div class="clearboth"></div>               
     {/if}
    {/if}
     {if !$category.category_id|in_array:$category_path}
         <a href="{"categories.view?category_id=`$category.category_id`"|fn_url}">
             <div class="nav_mainmenu_label" {if $category.category_id == $lang.48hrsale_category_id || $category.category_id == $lang.24hrsale_category_id} style="color:#FE0201;font-weight:700;" {/if}>
             	{$category.category}
             </div>
             {if $category.category_id == $lang.48hrsale_category_id || $category.category_id == $lang.24hrsale_category_id}
             	<!--<div id="timer" class="nav_timer"></div>-->
             {/if}
             
             {if $category.show_as_new == 'Y'}
             	<div class="nav_mainmenu_iconnew"></div>
             {/if}
         </a>
     {/if}
     
    </li>
    {if $separated && !$smarty.foreach.$foreach_name.last}
    
    {/if}
    {/foreach}
    {/if}
    {/strip}

</ul>
{/if}
{if !$config.isResponsive}
{literal}
<script type="text/javascript">
	//<![CDATA[	
	//$(document).ready(function() {$ldelim}
		// create a new date and insert it
		var EndDate = new Date({/literal}{$lang.48hrsale_end_datetime}{literal});
		$.countdown('#timer', EndDate);
		$.countdown('#timer2', EndDate);
	//{$rdelim});	
	//]]>
 </script>
{/literal}
{/if}
{literal}
<script type="text/javascript">
        $('#all_cat').click(function(){
                $('#all_categories').slideToggle("slow");
                if($('#all_cat').hasClass('div_opnd'))
                {
                   $('#all_cat').removeClass('div_opnd');
                }else{
                   $('#all_cat').addClass('div_opnd');
                }
        });

</script>
{/literal}
{/if}