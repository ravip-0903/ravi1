{* $Id: help.tpl 11890 2011-02-22 10:25:13Z zeke $ *}

{if $content}
<div class="float-right">
	{capture name="notes_picker"}
		{$content}
	{/capture}
	{include file="common_templates/popupbox.tpl" act="notes" id="content_`$id`_notes" text=$lang.note content=$smarty.capture.notes_picker link_text="?"}
</div>
{/if}