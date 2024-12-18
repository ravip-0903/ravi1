{assign var="content" value=$smarty.request.category_id|fn_get_subcategories_resp_mobile}
{assign var="content_count" value=$content|count}
{if $category_data.category_id!=$lang.48hrsale_category_id && $category_data.category_id!=$lang.24hrsale_category_id}
 <h1 class="cat_title">
 
{if $smarty.request.sort_by=='popularity'}
  {$lang.pre_popular}
 {/if}
{if $smarty.request.sort_by=='bestsellers'}
 {$lang.pre_bestsellers}
{/if}
{if $smarty.request.sort_by=='newarrivals'}
 {$lang.pre_newarrivals}
{/if}
{if $smarty.request.sort_by=='hotdeals'}
 {$lang.pre_hotdeals}
{/if}
{if $smarty.request.sort_by=='featured'}
 {$lang.pre_featured}
{/if}
 
{$category_data.category}
{if $config.isResponsive}
 {if $content_count > 0}
<span class="cat_title_box cat_box_collapse"></span>
{/if}
{/if}
</h1>
{/if}

{if $config.isResponsive}
{if $content_count > 0}
<div class="arrow-up-mobile-sub-menu" style="display:none;"></div>
<ul  class="mobile_sub_cat_menu nav_submenu mobile" style="display:none;">
            {foreach from=$content item="cur_cat"}
                <li><div class="nav_mainmenu_label"><a href="{"categories.view?category_id=`$cur_cat.category_id`"|fn_url}" {if $smarty.request.category_id == $cur_cat.category_id} class="new_link_nl_cate"{/if}><div class="nl_cat_text_span">{$cur_cat.category}</div>
                {if $cur_cat.show_as_new == 'Y'}
                    <div class="nav_mainmenu_iconnew"></div>
                {/if}</a>
                
                </div>
                </li>
            {/foreach}
    </ul>
{/if}
{/if}

{if $subcategories or $category_data.description || $category_data.main_pair}
{math equation="ceil(n/c)" assign="rows" n=$subcategories|count c=$columns|default:"1"}
{split data=$subcategories size=$rows assign="splitted_subcategories"}

{if $category_data.category_id|in_array:$config.special_sale_category_id}
  {if $category_data.category_id!=$lang.48hrsale_category_id && $category_data.category_id!=$lang.24hrsale_category_id}
    {if $category_data.description && $category_data.description != ""}
        <div class="mobile-cat-bnnr compact wysiwyg-content margin-bottom">{$category_data.description|unescape}</div>
    {/if}
  {/if}
{else}
  {if $category_data.category_id!=$lang.48hrsale_category_id && $category_data.category_id!=$lang.24hrsale_category_id}
    {if $category_data.description && $category_data.description != ""}
        <div class="mobile-cat-bnnr compact wysiwyg-content margin-bottom">{$category_data.description|unescape}</div>
    {/if}
  {/if}

{/if}



<div class="clear">
	{if $category_data.main_pair}
	<!--<div class="image-border float-left margin-bottom">
		{include file="common_templates/image.tpl" show_detailed_link=true images=$category_data.main_pair object_type="detailed_category" no_ids=true rel="category_image" show_thumbnail="Y" image_width=$settings.Thumbnails.category_details_thumbnail_width image_height=$settings.Thumbnails.category_details_thumbnail_height hide_if_no_image=true}
	</div>-->

	<!--{if $category_data.main_pair.detailed_id}
	{include file="common_templates/previewer.tpl" rel="category_image"}
	{/if}-->

	{/if}

</div>
{/if}
{if $config.isResponsive}
{if $content_count > 0}
{literal}
    <script>
       $(document).ready(function(){
           if($(".central-content").css('zoom') != 1){
               $('.mobile_sub_cat_menu').css('zoom',2.3);
               $('.arrow-up-mobile-sub-menu').css('zoom',2.4);
           }
         $(".cat_title").click(function(e){
            $(".mobile_sub_cat_menu").toggle();
            $(".arrow-up-mobile-sub-menu").toggle();
            if($('.cat_title_box').hasClass('cat_box_expand')){
                $('.cat_title_box').removeClass('cat_box_expand');
                $('.cat_title_box').addClass('cat_box_collapse');
            }else{
                $('.cat_title_box').removeClass('cat_box_collapse');
                $('.cat_title_box').addClass('cat_box_expand');
            }
            e.stopPropagation();
         });
         
         $(".mobile_sub_cat_menu").children().click(function(e){e.stopPropagation();});
        }); 
        
        $('html').click(function(event) {
           $(".mobile_sub_cat_menu").hide();
            $(".arrow-up-mobile-sub-menu").hide();
            $('.cat_title_box').removeClass('cat_box_expand');
            $('.cat_title_box').addClass('cat_box_collapse');
               });
        </script>
    {/literal}
    {/if}
    {/if}