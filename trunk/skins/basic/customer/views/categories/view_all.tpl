{* $Id: view_all.tpl 12544 2011-05-27 10:34:19Z bimib $ *}
<table cellpadding="5" cellspacing="0" border="0" width="100%" class="view-all">
<div class="cat_nl_divsting">
{$lang.category_icon_banner|unescape}
{foreach from=$categories item=cat}
	{if $cat.subcategories}
    <div class="cate_heading_nl" style="margin-top:15px;"><a href="{"categories.view?category_id=`$cat.category_id`"|fn_url}">{$cat.category}</a></div>
    
	<div style="font:11px/18px Verdana, Geneva, sans-serif; border:1px solid #ccc; background:#f8f8f8; padding:5px; margin-top:5px;">
        <div class="cat_nl_divsting_cate">{if $cat.subcategories}
            {foreach from=$cat.subcategories item="subcat"}
            <div class="cat_icon_text_nl">
                <div class="nav_topsubmenu_label_new" style="font-weight:bold;"><a href="{"categories.view?category_id=`$subcat.category_id`"|fn_url}">{$subcat.category}</a></div>
                <div style="padding-left:25px;">
                {if $subcat.subcategories}
                    {foreach from=$subcat.subcategories item="subcat1"}
                        <div ><a href="{"categories.view?category_id=`$subcat1.category_id`"|fn_url}">{$subcat1.category}</a></div>
                        {if $subcat1.subcategories}
                            {foreach from=$subcat1.subcategories item="subcat2"}
                             <div style="padding-left:10px;"><a href="{"categories.view?category_id=`$subcat2.category_id`"|fn_url}">{$subcat2.category}</a></div>
                              {if $subcat2.subcategories}
                                  {foreach from=$subcat2.subcategories item="subcat3"}
                                  <div><a href="{"categories.view?category_id=`$subcat3.category_id`"|fn_url}">{$subcat3.category}</a></div>
                                  {/foreach}
                              {/if}
                            {/foreach}
                        {/if}
                    {/foreach}
                {/if}
                </div>
			</div>   
            {/foreach}
        {/if}
    	</div>
        <div style="clear:both;"></div>
    </div>
	{/if}
{/foreach}
</div>
</table>