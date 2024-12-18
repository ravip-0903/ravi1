{* $Id: mainbox_general.tpl 12073 2011-03-18 12:12:26Z 2tl $ *}
{if $anchor}
<a name="{$anchor}"></a>
{/if}

{if $controller == "index"}

    {if !$config.mobile_perf_optimization}
        <div class="box_homepagedealblock">
        <div class="box_homepagedealblock_heading">
            {if $icon_image!=''}
             <img src="{$icon_image}" alt="{$block.description}" height="45" width="130" title="{$block.description}"  style="float:left" />
            {/if}

            {if $show_title=='Y'}
             {$title}
            {/if}
            <span class="box_homepagedealblock_heading_punchline">{if $punch_line!=''}{$punch_line}{/if}</span>
           {if $view_all!=''} <a href="{$view_all}" class="box_homepagedealblock_heading_viewall">View All</a>{/if}


        </div>

        <div class="box_homepagedealblock_content">
        {$content}
        </div>
        </div>
    {else}

    {/if}
{else}
    <div class="box_homepagedealblock">
        <div class="box_homepagedealblock_heading">
            {if $icon_image!=''}
                <img src="{$icon_image}" alt="{$block.description}" height="45" width="130" title="{$block.description}"  style="float:left" />
            {/if}

            {if $show_title=='Y'}
                {$title}
            {/if}
            <span class="box_homepagedealblock_heading_punchline">{if $punch_line!=''}{$punch_line}{/if}</span>
            {if $view_all!=''} <a href="{$view_all}" class="box_homepagedealblock_heading_viewall">View All</a>{/if}


        </div>

        <div class="box_homepagedealblock_content">
            {$content}
        </div>
    </div>
{/if}
