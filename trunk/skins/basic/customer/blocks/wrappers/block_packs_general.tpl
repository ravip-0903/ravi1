{* $Id: mainbox_general.tpl 12073 2011-03-18 12:12:26Z 2tl $ *}
{if $anchor}
<a name="{$anchor}"></a>
{/if}

{if $controller == "index"}

{if !$config.mobile_perf_optimization}
<div class="block-packs-general{if $details_page} details-page{/if}">
	{if $title}
	<h1 class="block-packs-title">
    <span style="float:left;">{$title}</span>
     {if $view_url!='' }
	     <a href="{$view_url}" style="font:12px trebuchet ms; float:left; margin:4px 0 0 10px;" class="ahover_nl">View All</a>
     {/if}
    </h1>
	{/if} 
	<div class="block-packs-body">{$content}</div>
	
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
	
</div>
{else}
{/if}

{else}
<div class="block-packs-general{if $details_page} details-page{/if}">
    {if $title}
        <h1 class="block-packs-title">
            <span style="float:left;">{$title}</span>
            {if $view_url!='' }
                <a href="{$view_url}" style="font:12px trebuchet ms; float:left; margin:4px 0 0 10px;" class="ahover_nl">View All</a>
            {/if}
        </h1>
    {/if}
    <div class="block-packs-body">{$content}</div>

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

</div>
{/if}
