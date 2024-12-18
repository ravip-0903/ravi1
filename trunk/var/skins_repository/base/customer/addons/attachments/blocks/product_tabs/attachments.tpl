{* $Id: attachments.tpl 12724 2011-06-21 12:48:57Z zeke $ *}
{** block-description:attachments **}

{if $attachments_data}
<div id="content_attachments">
{foreach from=$attachments_data item="file"}
<p>
{$file.description} ({$file.filename}, {$file.filesize|formatfilesize}) [<a href="{"attachments.getfile?attachment_id=`$file.attachment_id`"|fn_url}">{$lang.download}</a>]
</p>
{/foreach}
</div>
{/if}