{* $Id: mainbox.tpl 12544 2011-05-27 10:34:19Z bimib $ *}
{if $anchor}
<a name="{$anchor}"></a>
{/if}
<div>

{if $title_extra || $tools || ($navigation.dynamic && $navigation.dynamic.actions) || $select_languages || $extra_tools}
	<div class="clear mainbox-title-container">
{/if}

	{if $notes}
		{include file="common_templates/help.tpl" content=$notes id=$notes_id}
	{/if}

	{if $tools}{$tools}{/if}

	<h1 class="mainbox-title{if $title_extra} float-left{/if}">
		{$title|default:"&nbsp;"}
	</h1>
    
    {if 'COMPANY_ID'|defined && $mode=='add'}
     <span class="extra_info" style="margin-top:6px">{$lang.extra_info_heading|escape}</span>
    {elseif 'COMPANY_ID'|defined && $mode=='m_add'}
     <span class="extra_info" style="margin-top:6px">{$lang.extra_info_heading_exp_upload|escape}</span>
    {/if}
    

	{if $title_extra}<div class="title">-&nbsp;</div>
		{$title_extra}
	{/if}
{if $title_extra || $tools || $navigation.dynamic.actions || $select_languages || $extra_tools}
	</div>
{/if}

{if $navigation.dynamic.actions || $select_languages || $extra_tools}<div class="extra-tools">{/if}

{if $select_languages && $languages|sizeof > 1}
<div class="select-lang">
	{include file="common_templates/select_object.tpl" style="graphic" link_tpl=$config.current_url|fn_link_attach:"descr_sl=" items=$languages selected_id=$smarty.const.DESCR_SL key_name="name" suffix="content" display_icons=true}
</div>{if $navigation.dynamic.actions || $extra_tools}&nbsp;&nbsp;{/if}
{/if}

{if $extra_tools|trim}
	{$extra_tools}{if $navigation.dynamic.actions}{/if}
{/if}
{if $navigation.dynamic.actions}
	{foreach from=$navigation.dynamic.actions key=title item=m name="actions"}
		{if "COMPANY_ID"|defined && $title=="add_product" && ($category_data.show_to_merchant == 'N')}
		
		{elseif $title == "delete_this_category" || $title == "delete_this_product"}
		{else}
        {if $mode=="manage" && $controller=="products" && $title=="global_update"}  <!--/*change by ankur to hide remaining links*/-->
		{include file="buttons/button.tpl" but_href=$m.href but_text=$lang.$title but_role="tool" but_target=$m.target but_meta=$m.meta}
        {elseif ($mode!="manage" && $controller=="products") || $controller!="products"}
           {include file="buttons/button.tpl" but_href=$m.href but_text=$lang.$title but_role="tool" but_target=$m.target but_meta=$m.meta}
        {/if}
        
		{/if}
	{/foreach}
{/if}

{if $smarty.capture.preview|trim}
	<div class="float-right preview-link">
		{$smarty.capture.preview}
	</div>
{/if}

{if $navigation.dynamic.actions || $select_languages || $extra_tools}</div>{/if}

	<div class="mainbox-body" {if $box_id}id="{$box_id}"{/if}>
		{$content|default:"&nbsp;"}
	{if $box_id}<!--{$box_id}-->{/if}</div>
</div>
