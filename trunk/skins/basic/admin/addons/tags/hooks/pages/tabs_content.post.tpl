{* $Id: tabs_content.post.tpl 11786 2011-02-08 09:44:48Z 2tl $ *}

{if $addons.tags.tags_for_pages == "Y"}
	{include file="addons/tags/views/tags/components/object_tags.tpl" object=$page_data input_name="page_data"}
{/if}