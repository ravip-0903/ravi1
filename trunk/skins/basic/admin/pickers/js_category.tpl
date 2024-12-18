{* $Id: js_category.tpl 12127 2011-03-30 10:39:55Z bimib $ *}

{if $category_id}
	{assign var="category" value=$category_id|fn_get_category_name|default:"`$ldelim`category`$rdelim`"}
{else}
	{assign var="category" value=$default_name}
{/if}
{if $multiple}
	<tr {if !$clone}id="{$holder}_{$category_id}" {/if}class="cm-js-item {if $clone} cm-clone hidden{/if}">
		{if $position_field}<td><input type="text" name="{$input_name}[{$category_id}]" value="{math equation="a*b" a=$position b=10}" size="3" class="input-text-short"{if $clone} disabled="disabled"{/if} /></td>{/if}
		<td><a href="{"categories.update?category_id=`$category_id`"|fn_url}">{$category|escape}</a></td>
		<td class="nowrap">
		{if !$hide_delete_button && !$view_only}
		{capture name="tools_items"}
			<li><a onclick="jQuery.delete_js_item('{$holder}', '{$category_id}', 'c'); return false;">{$lang.delete}</a></li>
			{/capture}
			{include file="common_templates/table_tools_list.tpl" prefix=$category_id tools_list=$smarty.capture.tools_items href="categories.update?category_id=`$category_id`"}
		{else}&nbsp;
		{/if}
		</td>
	</tr>
{else}
	{if $view_mode != "list"}
		<{if $single_line}span{else}p{/if} {if !$clone}id="{$holder}_{$category_id}" {/if}class="cm-js-item no-margin{if $clone} cm-clone hidden{/if}">
		{if !$first_item && $single_line}<span class="cm-comma{if $clone} hidden{/if}">,&nbsp;&nbsp;</span>{/if}
		<input class="input-text-medium cm-picker-value-description float-left{$extra_class}" type="text" value="{$category|escape}" {if $display_input_id}id="{$display_input_id}"{/if} size="10" name="category_name" readonly="readonly" {$extra} />
		</{if $single_line}span{else}p{/if}>
	{else}
		{assign var="default_category" value="`$ldelim`category`$rdelim`"}
		{assign var="default_category_id" value="`$ldelim`category_id`$rdelim`"}
		{if $first_item || !$category_id}<p class="cm-js-item cm-clone hidden margin-top-clear"><input class="radio" type="radio" name="{$radio_input_name}" value="{$default_category_id}">{$default_category} <a onclick="jQuery.delete_js_item('{$holder}', '{$default_category_id}', 'c'); return false;"><img src="{$images_dir}/icons/delete_icon.gif" width="12" height="11" border="0" alt="{$lang.remove}" align="bottom" /></a></p>{/if}
		{if $category_id}<p class="cm-js-item margin-top-clear {$extra_class}" id="{$holder}_{$category_id}" {$extra} /><input class="radio" {if $category_type == "M"}checked{/if} type="radio" name="{$radio_input_name}" value="{$category_id}">{$category|escape} <a onclick="jQuery.delete_js_item('{$holder}', '{$category_id}', 'c'); return false;"><img src="{$images_dir}/icons/delete_icon.gif" width="12" height="11" border="0" alt="{$lang.remove}" title="{$lang.remove}" align="bottom" /></a></p>{/if}
	{/if}
{/if}
