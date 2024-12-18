{* $Id: grid_list.tpl 11191 2010-11-11 11:56:01Z klerik $ *}
{** block-description:clues_cateogry_4product_list **}

{if $block.properties.hide_add_to_cart_button == "Y"}
	{assign var="_show_add_to_cart" value=false}
{else}
	{assign var="_show_add_to_cart" value=true}
{/if}

{include file="blocks/list_templates/meta_category.tpl" 
products=$items 
columns=$block.properties.number_of_columns 
no_sorting="Y" 
obj_prefix="`$block.block_id`000" 
item_number=$block.properties.item_number
discount_label= $block.properties.show_discount_label
show_add_to_cart=$_show_add_to_cart 
no_pagination=true}