{* $Id: help.tpl 11988 2011-03-05 09:44:33Z 2tl $ *}

{if $content}

{if !$link_only}<div class="float-right">{/if}
	{capture name="notes_picker"}
		{$content}
	{/capture}
	{include file="common_templates/popupbox.tpl" act="notes" id="content_`$id`_notes" text=$text content=$smarty.capture.notes_picker link_text=$link_text|default:"?" show_brackets=$show_brackets}
{if !$link_only}</div>{/if}
{/if}