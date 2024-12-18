{* $Id: mainbox_general.tpl 12073 2011-03-18 12:12:26Z 2tl $ *}
{if $anchor}
<a name="{$anchor}"></a>
{/if}
{assign var="block_prop" value="/"|explode:$block.properties.appearances }
{assign var="block_prop_new" value="/"|explode:$block.properties.appearances}

{if $controller == "index"}
    {if !$config.mobile_perf_optimization}
        <div class="block_metacategory {if $block_prop.2=="clues_category_3x1.tpl" && $block.properties.show_key_feature == "Y"}grid3x1_feature{elseif $block_prop.2=="clues_category_3x2.tpl" && $block.properties.show_key_feature == "Y"}grid3x2_feature {elseif $block_prop.2=="clues_category_4x2.tpl" && $block.properties.show_key_feature == "Y"}grid4x2_feature {elseif $block_prop_new.1=="clues_cateogry_4product_list.tpl" && $block.properties.show_key_feature == "Y"}grid4x1_feature {elseif $block_prop_new.1=="clues_cateogry_4product_list.tpl"}grid4x1{elseif $block_prop.2=="clues_category_3x1.tpl"}grid3x1{elseif $block_prop.2=="clues_home_page_3x1.tpl"}grid_home_3x1{elseif $block_prop.2=="clues_home_page_3x2.tpl"}grid_home_3x2{elseif $block_prop.2=="clues_home_page_4x1.tpl"}grid_home_4x1{elseif $block_prop.2=="clues_home_page_4x2.tpl"}grid_home_4x2{elseif $block_prop.2=="clues_category_3x2.tpl"}grid3x2{elseif $block_prop.2=="clues_category_4x2.tpl"}grid4x2{/if}">
            {if $title}
            <h1 class="block_metacategory_heading">
            {$title}
            {$catId}
            {if $view_url!='' }
                <a href="{$view_url}" class="block_metacategory_heading_viewmore">View All</a>
            {/if}
            {if $block.properties.category != ''}
                <a href="{"categories.view?category_id=`$block.properties.category`"|fn_url}" class="block_metacategory_heading_viewmore"> View All</a>
            {/if}
                 {if $block.properties.fillings =='manually' && $block.properties.view_all != ""}
                    <a href="{$block.properties.view_all}" class="block_metacategory_heading_viewmore"> View All</a>

                 {/if}
            </h1>
            {/if}

        <div style="height: 305px;" class="jcarousel-skin-ie7 block_metacategory_content cus_{$block.block_id}">
        {$content}

        {if $config.isResponsive}
        <div class=" mobile-arrow jcarousel-prev jcarousel-prev-horizontal jcarousel-prev-disabled jcarousel-prev-disabled-horizontal" disabled="true" style="display: block; top: 145px;"></div>
            <div class=" mobile-arrow jcarousel-next jcarousel-next-horizontal jcarousel-next-disabled jcarousel-next-disabled-horizontal" disabled="true" style="display: block; top: 145px;"></div>
        {/if}
        </div>

        <div>
            <ul class="deal_block_other_deals" {if $link1=='' && $link2=='' && $link3=='' && $link4==''}style="display:none"{/if}>
            {if $link1!='' }
                <li class="other_deal_link">
            <a href="{$link1}" style="font:11px trebuchet ms; margin-top:10px;margin-right:10px;" class="ahover_nl">{$link1_text}</a>
                </li>
            {/if}
                {if $link2!='' }
                <li class="other_deal_link">
            <a href="{$link2}" style="font:11px trebuchet ms;  margin-top:10px;margin-right:10px;" class="ahover_nl">{$link2_text}</a>
                </li>
                    {/if}
                {if $link3!='' }
                <li class="other_deal_link">
            <a href="{$link3}" style="font:11px trebuchet ms; margin-top:10px;margin-right:10px;" class="ahover_nl">{$link3_text}</a>
                </li>
                    {/if}
                    {if $link4!='' }
                <li class="other_deal_link" style="width:23%;">
            <a href="{$link4}" style="font:11px trebuchet ms; margin-top:10px;margin-right:10px;" class="ahover_nl">{$link4_text}</a>
                </li>
            {/if}
            </ul>
            </div>
        <div class="clearboth height_fifty"></div>

        </div>
    {else}
    {/if}

{else}

<div class="block_metacategory {if $block_prop.2=="clues_category_3x1.tpl" && $block.properties.show_key_feature == "Y"}grid3x1_feature{elseif $block_prop.2=="clues_category_3x2.tpl" && $block.properties.show_key_feature == "Y"}grid3x2_feature {elseif $block_prop.2=="clues_category_4x2.tpl" && $block.properties.show_key_feature == "Y"}grid4x2_feature {elseif $block_prop_new.1=="clues_cateogry_4product_list.tpl" && $block.properties.show_key_feature == "Y"}grid4x1_feature {elseif $block_prop_new.1=="clues_cateogry_4product_list.tpl"}grid4x1{elseif $block_prop.2=="clues_category_3x1.tpl"}grid3x1{elseif $block_prop.2=="clues_home_page_3x1.tpl"}grid_home_3x1{elseif $block_prop.2=="clues_home_page_3x2.tpl"}grid_home_3x2{elseif $block_prop.2=="clues_home_page_4x1.tpl"}grid_home_4x1{elseif $block_prop.2=="clues_home_page_4x2.tpl"}grid_home_4x2{elseif $block_prop.2=="clues_category_3x2.tpl"}grid3x2{elseif $block_prop.2=="clues_category_4x2.tpl"}grid4x2{/if}">
    {if $title}
        <h1 class="block_metacategory_heading">
            {$title}
            {$catId}
            {if $view_url!='' }
                <a href="{$view_url}" class="block_metacategory_heading_viewmore">View All</a>
            {/if}
            {if $block.properties.category != ''}
                <a href="{"categories.view?category_id=`$block.properties.category`"|fn_url}" class="block_metacategory_heading_viewmore"> View All</a>
            {/if}
            {if $block.properties.fillings =='manually' && $block.properties.view_all != ""}
                <a href="{$block.properties.view_all}" class="block_metacategory_heading_viewmore"> View All</a>

            {/if}
        </h1>
    {/if}

    <div style="height: 305px;" class="jcarousel-skin-ie7 block_metacategory_content cus_{$block.block_id}">
        {$content}

        {if $config.isResponsive}
            <div class=" mobile-arrow jcarousel-prev jcarousel-prev-horizontal jcarousel-prev-disabled jcarousel-prev-disabled-horizontal" disabled="true" style="display: block; top: 145px;"></div>
            <div class=" mobile-arrow jcarousel-next jcarousel-next-horizontal jcarousel-next-disabled jcarousel-next-disabled-horizontal" disabled="true" style="display: block; top: 145px;"></div>
        {/if}
    </div>

    <div>
        <ul class="deal_block_other_deals" {if $link1=='' && $link2=='' && $link3=='' && $link4==''}style="display:none"{/if}>
            {if $link1!='' }
                <li class="other_deal_link">
                    <a href="{$link1}" style="font:11px trebuchet ms; margin-top:10px;margin-right:10px;" class="ahover_nl">{$link1_text}</a>
                </li>
            {/if}
            {if $link2!='' }
                <li class="other_deal_link">
                    <a href="{$link2}" style="font:11px trebuchet ms;  margin-top:10px;margin-right:10px;" class="ahover_nl">{$link2_text}</a>
                </li>
            {/if}
            {if $link3!='' }
                <li class="other_deal_link">
                    <a href="{$link3}" style="font:11px trebuchet ms; margin-top:10px;margin-right:10px;" class="ahover_nl">{$link3_text}</a>
                </li>
            {/if}
            {if $link4!='' }
                <li class="other_deal_link" style="width:23%;">
                    <a href="{$link4}" style="font:11px trebuchet ms; margin-top:10px;margin-right:10px;" class="ahover_nl">{$link4_text}</a>
                </li>
            {/if}
        </ul>
    </div>
    <div class="clearboth height_fifty"></div>

</div>
{/if}


