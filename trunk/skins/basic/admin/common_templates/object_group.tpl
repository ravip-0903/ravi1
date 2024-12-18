{if !$no_table}
<div class="object-group{$element} clear cm-row-item {$additional_class}">
	<div class="float-right delete">
		{capture name="tool_items"}
			{if $tool_items}
			{$tool_items}
			{/if}
			{if $href_delete && !$skip_delete}
			<li><a href="{$href_delete|fn_url}" rev="{$rev_delete}" class="cm-ajax cm-delete-row cm-confirm lowercase">{$lang.delete}</a></li>
			{elseif $links}
			<li>{$links}</li>
			{else !$href_delete && !$links}
			<li class="undeleted-element"><span>{$lang.delete}</span></li>
			{/if}
		{/capture}
		{include file="common_templates/table_tools_list.tpl" separate=true tools_list=$smarty.capture.tool_items prefix=$id href=""}
	</div>
	<div class="float-right">
{/if}

	{if !$non_editable}
		{include file="common_templates/popupbox.tpl" id="group`$id_prefix``$id`" edit_onclick=$onclick text=$header_text act=$act|default:"edit" picker_meta=$picker_meta link_text=$link_text}
	{else}	
		<span class="unedited-element block">{$link_text|default:$lang.edit}</span>
	{/if}

{if !$no_table}
	</div>
	{if $status}
	<div class="float-right">
		{include file="common_templates/select_popup.tpl" id=$id status=$status hidden=$hidden object_id_name=$object_id_name table=$table hide_for_vendor=$hide_for_vendor}
	</div>
	{/if}
	<div class="object-name">
		{if $checkbox_name}
			<input type="checkbox" name="{$checkbox_name}" value="{$checkbox_value|default:$id}"{if $checked} checked="checked"{/if} class="checkbox cm-item" />
		{/if}
		<div class="object-group-link-wrap" style="width:425px">
        <span style="float:left; width:45%">
        <a class="cm-external-click{if $non_editable} no-underline{/if}{if $main_link} link{/if}"{if !$non_editable && !$no_rev} rev="opener_group{$id_prefix}{$id}"{/if}{if $main_link} href="{$main_link|fn_url}"{/if}>{$text}</a>
        </span>
        <span style="float:left; width:53%; margin-left:5px">
        {if !empty($group_name)}
          <span style="float:left; font-size:11px; margin-top:4px; text-align:left">
          <div style="display:none">
          {include file="common_templates/popupbox.tpl" id="group`$id_prefix``$group_id`" edit_onclick=$onclick text=$group_text act=$act|default:"edit" picker_meta=$picker_meta link_text=$link_text|default:edit_group href=$group_url}
          </div>
             <a class="cm-external-click{if $non_editable} no-underline{/if}"{if !$non_editable && !$no_rev} rev="opener_group{$id_prefix}{$group_id}"{/if}>{$group_name}</a>
          </span>
        {/if}
        {if isset($is_filter)}
            <span style="float:left; {if !empty($group_name)}margin-left:5px;{else}margin-left:0px;{/if} color:green; font-size:11px; margin-top:4px; text-align:left">
             {if $is_filter=='Y'}
               ({$lang.filter_feature})
             {else}
               ({$lang.generic_feature})
             {/if}
             </span>
        {/if}
        {if !empty($option_description)}
          <span style="font-size:12px; font-weight:normal">{$option_description}</span>
        {/if}
        </span>
        
        <div class="clear"></div>
        </div><span class="object-group-details" style="margin-left:0px">{$details}</span>
        
	</div>
</div>
{/if}