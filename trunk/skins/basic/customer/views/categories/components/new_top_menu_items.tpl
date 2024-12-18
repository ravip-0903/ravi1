{* $Id: menu_items.tpl 9353 2010-05-04 06:10:09Z klerik $ *}
{strip}
{assign var="foreach_name" value="cats_$cid"}

{assign var="sl_no" value=1}
{foreach from=$items item="category" name=$foreach_name}
{*assign var="popular_brands" value=$category.category_id|fn_get_popular_brands*}
<li class="{if $category.subcategories} cate_nl_hover_cond {else} cate_nl_hover_cond_new{/if}">
{if (!$category.category_id|in_array:$config.category_hide_subcategory)}
 {if $category.subcategories}  {*$category.category_id*}         
        <div id="new_box{$sl_no}" class="box_submenu {if $popular_brands}box_submenu_background{/if}">
        	
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
                            {if $level2.is_deal_category == 'Y'}
                              <div class="category_offer_badge">&nbsp;</div>
                            {elseif $level2.show_as_new == 'Y'}
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
                                 {if $level2.is_deal_category == 'Y'}
                                    <div class="category_offer_badge">&nbsp;</div>
                                {elseif $level2.show_as_new == 'Y'}
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
              $("#new_box"+{/literal}{$sl_no}{literal}).ready(function(){$("#new_box"+{/literal}{$sl_no}{literal}).width(500);});
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
                     		--><a href="{"categories.view&category_id=`$category.category_id`&subcats=Y&features_hash[]=9.`$brand.varient_id`&br[]=`$brand.varient_id`"|fn_url}">{$brand.varient_name}</a></li> 
                    {/foreach}
                    </ul>   
                </div> 
            {/if}
      </div>
        <div class="clearboth"></div>               
 {/if}
{/if}
 <a href="{"categories.view?category_id=`$category.category_id`"|fn_url}">
 <div class="nav_mainmenu_label" {if $category.category_id == $lang.48hrsale_category_id || $category.category_id == $lang.24hrsale_category_id} style="color:#FE0201;font-weight:700;" {/if}>
 {$category.category}</div>
     {if $category.category_id == $lang.48hrsale_category_id || $category.category_id == $lang.24hrsale_category_id}
     	<!--<div id="timer" class="nav_timer"></div>-->
     {/if}
{if $category.is_deal_category == 'Y'}
    <div class="category_offer_badge">&nbsp;</div>
{elseif $category.show_as_new == 'Y'}
    <div class="nav_mainmenu_iconnew"></div>
{/if}
 </a>
 
</li>
{if $separated && !$smarty.foreach.$foreach_name.last}

{/if}
{/foreach}
{/strip}

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