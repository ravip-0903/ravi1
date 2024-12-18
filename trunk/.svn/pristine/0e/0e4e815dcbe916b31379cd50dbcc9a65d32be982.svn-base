{* $Id: mobile_responsive_menu.tpl 9353 2010-05-04 06:10:09Z klerik $ *}
{strip}
    {assign var="foreach_name" value="cats_$cid"}

    {if $smarty.request.company_id}

        {foreach from=$items item="category" name=$foreach_name}

            {assign var="company_data" value=$smarty.request.company_id|fn_get_company_data}

            {assign var="merchant_category" value=$company_data.custom_category_ids|fn_get_merchant_category}
            {if ($category.category_id|in_array:$merchant_category)}
                {assign var="subcategories" value=$category.category_id|fn_get_subcategories_resp_mobile}
                <li class="mob_cat_out_fl_blk">
                    <a class="mob_cat_fl_blk">
                        <div class="nav_mainmenu_label">{$category.category}</div>
                        <span class="mob_cat_icn_rgt"></span>
                    </a>
                    <div class="mob_inn_mnu_blk">
                        <ul>
                            {foreach from=$subcategories item="subcategory" name=$foreach_name}
                                <li>
                                    <a href="{"categories.view?category_id=`$subcategory.category_id`&company_id=`$smarty.request.company_id`"|fn_url}">
                                        <span class="inn_men_name">{$subcategory.category}</span>
                                    </a>
                                </li>
                            {/foreach}
                        </ul>
                    </div>
                </li>

            {/if}
        {/foreach}
    {else}
        {assign var="sl_no" value=1}
        {foreach from=$items item="category" name=$foreach_name}
            {assign var="popular_brands" value=$category.category_id|fn_get_popular_brands}
            {assign var="subcategories" value=$category.category_id|fn_get_subcategories_resp_mobile}
            <li class="mob_cat_out_fl_blk">
                <a class="mob_cat_fl_blk">
                    <span class="mob_cat_name_lft">{$category.category}</span>
                    {if $category.is_deal_category == 'Y'}
                        <div class="category_offer_badge">&nbsp;</div>
                    {elseif $category.show_as_new == 'Y'}
                        <div class="nav_mainmenu_iconnew"></div>
                    {/if}
                    <span class="mob_cat_icn_rgt"></span>
                </a>
                <div class="mob_inn_mnu_blk">
                    <ul>
                        {foreach from=$subcategories item="subcategory" name=$foreach_name}
                            <li>
                                <a href="{"categories.view?category_id=`$subcategory.category_id`&company_id=`$smarty.request.company_id`"|fn_url}">
                                    <span class="inn_men_name">{$subcategory.category}</span>
                                </a>
                            </li>
                        {/foreach}
                    </ul>
                </div>

            </li>
        {/foreach}
    {/if}
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

{literal}
    <script type="text/javascript">
        $(document).ready(function(){
            $(".mob_cat_fl_blk").click(function(){

                if($(this).hasClass("active"))
                {
                    $(".mob_inn_mnu_blk").slideUp("fast");
                    $(".mob_cat_fl_blk").removeClass("active");
                }
                else
                {
                    $(".mob_inn_mnu_blk").slideUp("fast");
                    $(".mob_cat_fl_blk").removeClass("active");
                    $(this).addClass("active");
                    $(this).next(".mob_inn_mnu_blk").slideDown("fast");
                }
            });
        });
    </script>
{/literal}

