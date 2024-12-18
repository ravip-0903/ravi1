{* $Id: group_element.tpl 12724 2011-06-21 12:48:57Z zeke $ *}
{foreach from=$blocks item="block_data"}
{if ($block_data.text_id == $blocks_target || $block_data.block_id == $blocks_target) && $block_data.block_type == "G"}
	{if $block_data.text_id}
	<div id="{$blocks_target}_column_holder"{if $main_class} class="{$main_class}"{/if}>
		<div class="block-manager">
			{if $block_data.text_id == "product_details"}
				<input type="hidden" name="block_positions[]" class="block-position" value="{$block_data.block_id}" />
				{assign var="def_id" value="block_content_`$block_data.block_id`"}
			{else}
				{assign var="def_id" value=$block_data.text_id}
			{/if}
			<h2 class="clear">
				{if !$simple}
				<div class="float-right">
					{include file="common_templates/object_group.tpl" content=$smarty.capture.update_block id="`$block_data.block_id``$block_data.block_type`_`$location`" no_table=true but_name="dispatch[block_manager.update]" href="block_manager.update?block_id=`$block_data.block_id`&amp;location=`$location`&amp;selected_section=`$location`" header_text="`$lang.editing_block`: `$block_data.description`" opener_ajax_class="cm-ajax" link_class="cm-ajax-force" picker_meta="cm-clear-content"}
				</div>
				{/if}
				{$block_data.description}
			</h2>
			<input type="hidden" name="group_id_{$def_id}" value="{$block_data.block_id}" />
			<div id="{$def_id}" class="cm-sortable-items grab-items {if $block_data.text_id == "product_details"}cm-product-details{/if}">
	{else}
	<div id="blocks_group_{$blocks_target}" class="cm-list-box cm-group-box block-container base-block">
		<div class="block-manager">
			<input type="hidden" name="block_positions[]" class="block-position" value="{$block_data.block_id}" />
			<h4 class="group-header {if $show_for_location && $block_data.location != $show_for_location}cm-fixed-block{/if}">
			{if !$simple}
			<div class="float-right">
				{include file="common_templates/object_group.tpl" content=$smarty.capture.update_block id="`$block_data.block_id``$block_data.block_type`_`$location`" no_table=true but_name="dispatch[block_manager.update]" href="block_manager.update?block_id=`$block_data.block_id`&amp;location=`$location`&amp;selected_section=`$location`" header_text="`$lang.editing_block`: `$block_data.description`" opener_ajax_class="cm-ajax" link_class="cm-ajax-force" picker_meta="cm-clear-content"}
				{if ($location == "all_pages" || $block_data.location != "all_pages") && !$block_data.properties.static_block}
					<a class="cm-confirm" href="{"block_manager.delete?selected_section=`$location`&amp;block_id=`$block_data.block_id`"|fn_url}"><img src="{$images_dir}/icons/icon_delete.gif" width="12" height="18" border="0" title="{$lang.delete}" alt="{$lang.delete}" class="valign" /></a>
				{/if}
			</div>
			{/if}
			<span>
				{assign var="block_content_id" value="block_content_`$block_data.block_id`"}
				<img src="{$images_dir}/icons/icon_show.gif" width="13" height="13" border="0" alt="" id="on_{$block_content_id}" class="cm-combination cm-save-state{if $smarty.cookies.$block_content_id} hidden{/if}" /><img src="{$images_dir}/icons/icon_hide.gif" width="13" height="13" border="0" alt="" id="off_{$block_content_id}" class="cm-combination cm-save-state{if !$smarty.cookies.$block_content_id} hidden{/if}" />
				{$block_data.description}
			</span>
			</h4>
			<input type="hidden" name="group_id_{$block_content_id}" value="{$block_data.block_id}" />
			<div id="{$block_content_id}" class="cm-sortable-items cm-decline-group group-content grab-items {if !$smarty.cookies.$block_content_id} hidden{/if}">
	{/if}

			{if !$simple}
			<div class="info-line clear">
				{if $block_data.properties.block_order}	
					<p{if !$block_data.properties.wrapper} class="float-left"{/if}>
						<label>{$lang.direction}:</label>
						{if $block_data.properties.block_order == "V"}{$lang.vertical}{else}{$lang.horizontal}{/if}
					</p>
				{/if}
		
				{if $block_data.properties.wrapper}
					<p class="float-left">
						<label>{$lang.wrapper}:</label>
						{$block_data.properties.wrapper}
					</p>
				{/if}
				<div class="float-right">
					{if $block_data.location == "all_pages" && $smarty.request.selected_section && $smarty.request.selected_section != "all_pages"}
						{if $block_data.status == "A"}
							{assign var="_block_id" value=$block_data.block_id}
						{else}
							{assign var="_block_id" value=0}
						{/if}
		
						{if $block_data.disabled_locations|strpos:$smarty.request.selected_section === false && $block_data.status == "A"}
							{assign var="status" value="A"}
						{else}
							{assign var="status" value="D"}
						{/if}
						{assign var="lang_active" value=$lang.active|escape:dquotes}
						{assign var="lang_disabled" value=$lang.disabled|escape:dquotes}
						{include file="common_templates/select_popup.tpl" id=$_block_id status=$status object_id_name="block_id" table="blocks" update_controller="block_manager" items_status="A: \"`$lang_active`\", D: \"`$lang_disabled`\"" extra="&amp;selected_location=`$smarty.request.selected_section`&amp;block_location=`$block_data.location`"}
					{else}
						{include file="common_templates/select_popup.tpl" id=$block_data.block_id status=$block_data.status object_id_name="block_id" table="blocks"}
					{/if}
				</div>
			</div>
			{/if}

			<div class="cm-list-box list-box-invisible"></div>
			{assign var="_not_empty" value=false}
			{foreach from=$blocks item="inner_block"}
				{if $inner_block.group_id == $block_data.block_id}
					{if $inner_block.block_type == "B"}
						{if $inner_block.text_id == "central_content"}
							<div class="cm-list-box central-content">
								<h3>{$inner_block.description}</h3>
								<input type="hidden" name="block_positions[]" class="block-position" value="{$inner_block.block_id}" />
								<div class="block-content clear">
								{if $inner_block.properties.wrapper}
									<p><label>{$lang.wrapper}:</label>
									{$inner_block.properties.wrapper}</p>
								{/if}
		
								{if !$simple}
								{include file="common_templates/object_group.tpl" content=$smarty.capture.update_block id="`$inner_block.block_id``$inner_block.block_type`_`$location`" no_table=true but_name="dispatch[block_manager.update]" href="block_manager.update?block_id=`$inner_block.block_id`&amp;location=`$location`" header_text="`$lang.editing_block`: `$inner_block.description`"}
								{/if}
								</div>
							</div>
							{foreach from=$blocks item="_block"}
								{if $_block.text_id == "product_details"}
									{include file="views/block_manager/components/group_element.tpl" blocks_target=$_block.block_id main_class="product-tabs"}
								{/if}
							{/foreach}
						{else}
							{include file="views/block_manager/components/block_element.tpl" block_data=$inner_block}
						{/if}
					{elseif $inner_block.text_id != "product_details"}
						{include file="views/block_manager/components/group_element.tpl" blocks_target=$inner_block.block_id main_class=""}
					{/if}
					{if $inner_block.block_type == "B" && !$inner_block.disabled || $inner_block.block_type == "G"}
						{assign var="_not_empty" value=true}
					{/if}
				{/if}
			{/foreach}
			<p class="no-items{if $_not_empty} hidden{/if}">{$lang.no_blocks}</p>
			<div class="cm-list-box list-box-invisible"></div>
		</div>
	</div>
</div>
{/if}
{/foreach}
