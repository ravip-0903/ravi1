{* $Id: tabs_content.post.tpl 7083 2009-03-19 09:52:10Z zeke $ *}

{if $addons.tags.tags_for_products == "Y"}
	{include file="addons/tags/views/tags/components/object_tags.tpl" object=$product_data input_name="product_data"}
{/if}