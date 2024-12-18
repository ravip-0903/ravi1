{* $Id: categories_tree.tpl 12684 2011-06-14 15:19:15Z subkey $ *}
{if $smarty.request.company_id}
{assign var="cc_id" value=$smarty.request.category_id|default:$smarty.session.current_category_id}
{if !$smarty.request.company_id}
	{foreach from=$categories item=category key=cat_key name="categories"}
    	{assign var="id_path" value="/"|explode:$category.id_path}
        
        {if ($category.category_id == $cc_id) || ($category.parent_id == $cc_id)}
            
            {if $category.level == "0"}
                    {if $ul_subcategories == "started"}
                        </ul>
                        {assign var="ul_subcategories" value=""}
                    {/if}
                    <ul class="menu-root-categories tree">
                        <li><a href="{"categories.view?category_id=`$category.category_id`"|fn_url}" {if $category.category_id == $cc_id} class="active"{/if}><div class="nl_cat_text_span">{$category.category}</div>
                        {if $category.show_as_new == 'Y'}
                            <div class="nav_mainmenu_iconnew"></div>
                        {/if}</a>
                        
                        </li>
                    </ul>
                {else}
                    {if $ul_subcategories != "started"}
                        <ul class="menu-subcategories new_menu_link_nl">
                            {assign var="ul_subcategories" value="started"}
                    {/if}
                    <li style="padding-left: {if $category.level == "1"}13px{elseif $category.level > "1"}{math equation="x*y+13" x="7" y=$category.level}px{/if};"><a href="{"categories.view?category_id=`$category.category_id`"|fn_url}"{if $category.category_id == $cc_id} class="active"{/if}><div class="nl_cat_text_span">{$category.category}</div>
                    {if $category.show_as_new == 'Y'}
                        <div class="nav_mainmenu_iconnew"></div>
                    {/if}</a>
                    
                    </li>
            {/if}
    	{/if}	
    {/foreach}	
{/if}

{foreach from=$categories item=category key=cat_key name="categories"}
	{if $smarty.request.company_id}
        {assign var="company_data" value=$smarty.request.company_id|fn_get_company_data}    
        {assign var="merchant_category" value=$company_data.custom_category_ids|fn_get_merchant_category}
        {if ($category.category_id|in_array:$merchant_category)}    
            {if $category.level == "0"}
                {if $ul_subcategories == "started"}
                    </ul>
                    {assign var="ul_subcategories" value=""}
                {/if}
                <ul class="menu-root-categories tree">
                    <li><a href="{"categories.view?category_id=`$category.category_id`&company_id=`$smarty.request.company_id`"|fn_url}" {if $category.category_id == $cc_id} class="active"{/if}><div class="nl_cat_text_span">{$category.category}</div>
                    {if $category.show_as_new == 'Y'}
                        <div class="nav_mainmenu_iconnew"></div>
                    {/if}</a>
                    
                    </li>
                </ul>
            {else}
                {if $ul_subcategories != "started"}
                    <ul class="menu-subcategories">
                        {assign var="ul_subcategories" value="started"}
                {/if}
                <li style="padding-left: {if $category.level == "1"}13px{elseif $category.level > "1"}{math equation="x*y+13" x="7" y=$category.level}px{/if};"><a href="{"categories.view?category_id=`$category.category_id`&company_id=`$smarty.request.company_id`"|fn_url}"{if $category.category_id == $cc_id} class="active"{/if}><div class="nl_cat_text_span">{$category.category}</div>
                {if $category.show_as_new == 'Y'}
                    <div class="nav_mainmenu_iconnew"></div>
                {/if}</a>
               
                </li>
            {/if}
            {if $smarty.foreach.categories.last && $ul_subcategories == "started"}
                </ul>
            {/if}
    	{/if}
    {else}
        {if ($category.category_id != $cc_id) && ($category.parent_id != $cc_id)}
            {if $category.level == "0"}
                {if $ul_subcategories == "started"}
                    </ul>
                    {assign var="ul_subcategories" value=""}
                {/if}
                <ul class="menu-root-categories tree">
                    <li><a href="{"categories.view?category_id=`$category.category_id`"|fn_url}" {if $category.category_id == $cc_id} class="active"{/if}><div class="nl_cat_text_span">{$category.category}</div>
                    {if $category.show_as_new == 'Y'}
                        <div class="nav_mainmenu_iconnew"></div>
                    {/if}</a>
                    
                    </li>
                </ul>
            {else}
                {if $ul_subcategories != "started"}
                    <ul class="menu-subcategories">
                        {assign var="ul_subcategories" value="started"}
                {/if}
                <li style="padding-left: {if $category.level == "1"}13px{elseif $category.level > "1"}{math equation="x*y+13" x="7" y=$category.level}px{/if};"><a href="{"categories.view?category_id=`$category.category_id`"|fn_url}"{if $category.category_id == $cc_id} class="active"{/if}><div class="nl_cat_text_span">{$category.category}</div>
                {if $category.show_as_new == 'Y'}
                    <div class="nav_mainmenu_iconnew"></div>
                {/if}</a>
                
                </li>
            {/if}
        {/if}
        {if $smarty.foreach.categories.last && $ul_subcategories == "started"}
            </ul>            
        {/if}
    {/if}
{/foreach}

<!--<div class="float_right margin_right_five" style="width:169px; padding:0; margin:0;" >
<a href="http://www.shopclues.com/categories" rel="nofollow" class="link_custom extra-link" style="font:13px 'Trebuchet MS', Arial, Helvetica, sans-serif;
border: 1px solid #CCC;
border-top: 0px;
margin-bottom: 10px;
width: 94%;
display: block;
padding: 2px 5px;
text-align:right;
color: #FF5400;">{$lang.view_all_categories}</a>
					</div>-->
<li id="all_cat" class="all_cat_nl_cate" style="border-bottom:0; list-style:none;">{$lang.show_all_categories}</li>
<div id="all_categories" class="all_cat_brdr_nl" style="display:none;">
                    
<ul class="nav_mainmenu" >
{assign var="items" value='0'|fn_my_changes_get_product_data_more}
        
{foreach from=$items item="category" name=$foreach_name}
{assign var="popular_brands" value=$category.category_id|fn_get_popular_brands}
<li class="cate_nl_hover_cond">
{if (!$category.category_id|in_array:$config.category_hide_subcategory)}
 {if $category.subcategories}           
        <div class="box_submenu {if $popular_brands}box_submenu_background{/if}  {if $category.category_id=='633'} box_submenu_margintopminushundred{/if}" style="margin-top:-{if $category.category_id=='453'}5{/if}{if $category.category_id=='85'}49{/if}{if $category.category_id=='368'}58{/if}{if $category.category_id=='682'}87{/if}{if $category.category_id=='460'}130{/if}{if $category.category_id=='628'}145{/if}{if $category.category_id=='87'}190{/if}{if $category.category_id=='282'}160{/if}{if $category.category_id=='480'}250{/if}{if $category.category_id=='337'}280{/if}{if $category.category_id=='93'}160{/if}{if $category.category_id=='668'}200{/if}{if $category.category_id=='633'}350{/if}{if $category.category_id=='180'}240{/if}{if $category.category_id=='129'}160{/if}{if $category.category_id=='1752'}15{/if}{if $category.category_id=='1755'}15{/if}{if $category.category_id=='1758'}0{/if}{if $category.category_id=='1067'}140{/if}px" >
            
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
             {/if}             
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
 <a class="nl_cat_wid_fix" href="{"categories.view?category_id=`$category.category_id`"|fn_url}">
 <div class="nav_mainmenu_label" {if $category.category_id == $lang.48hrsale_category_id ||  $category.category_id == $lang.24hrsale_category_id} style="color:#FE0201;font-weight:700;" {/if}>
 {$category.category}</div>
     {if $category.category_id == $lang.48hrsale_category_id || $category.category_id == $lang.24hrsale_category_id}
     	<!--<div id="timer" class="nav_timer"></div>-->
     {/if}
 
 {if $category.show_as_new == 'Y'}
 <div class="nav_mainmenu_iconnew"></div>
 {/if}
 </a>
 
</li>
{if $separated && !$smarty.foreach.$foreach_name.last}

{/if}
{/foreach}
</ul>
</div>
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