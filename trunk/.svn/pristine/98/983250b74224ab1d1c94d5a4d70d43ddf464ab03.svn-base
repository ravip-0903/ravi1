{* $Id: ajax_select_object.tpl 12795 2011-06-28 10:58:30Z alexions $ *}

<div class="tools-container inline">
	{if $label}<label>{$label}:</label>{/if}

	<a id="sw_{$id}_wrap_" class="select-link cm-combo-on cm-combination">{$text}</a>

	<div id="{$id}_wrap_" class="popup-tools cm-popup-box cm-smart-position hidden">	
		<div class="select-object-search"><input type="text" value="{$lang.search}..." class="input-text cm-hint cm-ajax-content-input" rev="content_loader_{$id}" size="16" /></div>
		<div class="ajax-popup-tools" id="scroller_{$id}">
			<ul class="cm-select-list" id="{$id}">
				{foreach from=$objects key="object_id" item="item"}
					{assign var="name" value=$item.name|substr:0:40}
					{if $item.name|fn_strlen > 40}
						{assign var="name" value="`$name`..."}
					{/if}
					
					<li class="{$item.extra_class}"><a action="{$item.value}">{$name}</a></li>
				{/foreach}
			<!--{$id}--></ul>

			<ul>
				<li id="content_loader_{$id}" class="cm-ajax-content-more small-description" rel="{$data_url|fn_url}" rev="{$id}" result_elm="{$result_elm}">{$lang.loading}</li>				
			</ul>
		</div>
	</div>
</div>

