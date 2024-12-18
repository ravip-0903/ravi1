{* $Id: new_menu_items.tpl 9353 2010-05-04 06:10:09Z klerik $ *}

{strip}
{assign var="foreach_name" value="cats_$cid"}
{foreach from=$items item="category" name=$foreach_name}
{if $smarty.foreach.$foreach_name.iteration <= $config.top_menu_limit-1}
    {assign var="popular_brands" value=$category.category_id|fn_get_popular_brands}
    <li> <a href="{"categories.view?category_id=`$category.category_id`"|fn_url}" {if $category.show_as_new == 'Y'}class="nav_topmenu_new"{/if}>
      <div class="nav_topmenu_label"> {$category.category} </div>      
      </a>
{if (!$category.category_id|in_array:$config.category_hide_subcategory)}

  <div class="clearboth"></div>
  {if $category.subcategories}
  <div class="box_topsubmenu {if $popular_brands}box_submenu_background_top{/if} {if $smarty.foreach.$foreach_name.iteration > $config.top_menu_limit-5} box_topsubmenu_rightAlignLastFour{/if}">
        <div class="box_topsubmenu_Column">
      <ul class="nav_topsubmenu">
            <li> {foreach from=$category.subcategories item="level2" name="lvl2"}
          {if $smarty.foreach.lvl2.iteration <= $config.second_level_menu_limit/2 } 
          <a href="{"categories.view?category_id=`$level2.category_id`"|fn_url}">
              <div class="nav_topsubmenu_label" {if $level2.category_id|in_array:$config.temp_category_id}style="color:red;"{/if}>{$level2.category}</div>
              {if $level2.is_deal_category == 'Y'}
                <div class="category_offer_badge">&nbsp;</div>
              {elseif $level2.show_as_new == 'Y'}
                <div class="nav_topsubmenu_iconnew"></div>
              {/if}
              {if $level2.subcategories}
              <div class="nav_topsubmenu_bulletCategory"></div>
              {/if} </a> {if $level2.subcategories}
          <ul class="nav_topsubmenu_category">
                {foreach from=$level2.subcategories item="level3" name="lvl3"}
                {if $smarty.foreach.lvl3.iteration <= $config.third_level_menu_limit-1}
                <li> <a href="{"categories.view?category_id=`$level3.category_id`"|fn_url}">{$level3.category}</a></li>
                {elseif $smarty.foreach.lvl3.iteration == $config.third_level_menu_limit}
                <li> <a href="{"categories.view?category_id=`$level2.category_id`"|fn_url}" class="nav_topsubmenu_category_more">More...</a> </li>
                {/if}
                {/foreach}
              </ul>
          {/if}                         
          {/if}                         
          {/foreach} </li>
          </ul>
    </div>
        {if $category.subcategories|count > $config.second_level_menu_limit/2 }
        <div class="box_topsubmenu_Column">
      <ul class="nav_topsubmenu">
            {foreach from=$category.subcategories item="level2" name="lvl2"}                        
            {if $smarty.foreach.lvl2.iteration > $config.second_level_menu_limit/2 &&  $smarty.foreach.lvl2.iteration <= $config.second_level_menu_limit-1}
            <li>
            <a href="{"categories.view?category_id=`$level2.category_id`"|fn_url}">
              <div class="nav_topsubmenu_label" {if $level2.category_id|in_array:$config.temp_category_id}style="color:red;"{/if}>{$level2.category}</div>
              
              {if $level2.subcategories}
              <div class="nav_topsubmenu_bulletCategory"></div>
              {/if}
              {if $level2.is_deal_category == 'Y'}
                <div class="category_offer_badge">&nbsp;</div>
              {elseif $level2.show_as_new == 'Y'}
              <div class="nav_topsubmenu_iconnew"></div>
              {/if}
              </a> {if $level2.subcategories}
          <ul class="nav_topsubmenu_category">
                {foreach from=$level2.subcategories item="level3"}
                <li> <a href="{"categories.view?category_id=`$level3.category_id`"|fn_url}">{$level3.category}</a></li>
                {/foreach}
              </ul>
          {/if} </li>
            {elseif $smarty.foreach.lvl2.iteration == $config.second_level_menu_limit}
            <li> <a href="{"categories.view?category_id=`$category.category_id`"|fn_url}" class="nav_topsubmenu_more">More...</a> </li>
            {/if}
            {/foreach}
          </ul>
    </div>
        {/if}             
        {if $popular_brands}
        <div class="box_topsubmenuBrand">
      <h3 class="box_topsubmenuBrand_heading">Popular Brands</h3>
      <ul class="box_topsubmenuBrand_brand">
            {foreach from=$popular_brands item="brand"}
            <li><a href="{"categories.view&category_id=`$category.category_id`&subcats=Y&features_hash[]=9.`$brand.varient_id`"|fn_url}">{$brand.varient_name}</a></li>
            {/foreach}
          </ul>
    </div>
        {/if}
       
       <!--Category Image -->
       <!-- <div class="float_left">
        {assign var="image_pair" value=$category.category_id|fn_get_image_pairs:'category':'M':true:true:''}
        <img src="{$image_pair.detailed.image_path}" />
        </div> -->
        <!--End Category Image -->
        
        </div>
  {/if} </li>
{if $separated && !$smarty.foreach.$foreach_name.last}
    
    {/if}

{/if}
{/if}
{/foreach}
<li>
	<a href="#">
  		<div class="nav_topmenu_label">More...</div>
  	</a>
    <div class="clearboth"></div>
      <div class="box_topsubmenu box_topmenu_lastaligntment">
        <div class="box_topsubmenu_Column">
          <ul class="nav_topsubmenu">
          	{foreach from=$items item="category" name=$foreach_name}
				{if $smarty.foreach.$foreach_name.iteration >= $config.top_menu_limit}
                    <li> 
                      <a href="{"categories.view?category_id=`$category.category_id`"|fn_url}">
                      <div class="nav_topsubmenu_label">{$category.category}</div>
                      {*{if $category.show_as_new == 'Y'}
                        <div class="nav_topmenu_iconnew"></div>
                      {/if} *}
                      {if $category.is_deal_category == 'Y'}
                        <div class="category_offer_badge">&nbsp;</div>
                      {elseif $category.show_as_new == 'Y'}
                        <div class="nav_topmenu_iconnew"></div>
                      {/if}
                      </a>
                    </li>
                {/if}
           	{/foreach}
          </ul>
        </div>
      </div>
</li>


{/strip} 
