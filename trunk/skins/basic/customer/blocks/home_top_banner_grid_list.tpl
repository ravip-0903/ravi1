{* $Id: grid_list.tpl 11191 2010-11-11 11:56:01Z klerik $ *}
{** block-description:top_products_grid **}

{if $block.properties.hide_add_to_cart_button == "Y"}
	{assign var="_show_add_to_cart" value=false}
{else}
	{assign var="_show_add_to_cart" value=true}
{/if}

{*include file="blocks/product_list_templates/products_grid.tpl" 
products=$items 
columns=$block.properties.number_of_columns 
no_sorting="Y" 
obj_prefix="`$block.block_id`000" 
item_number=$block.properties.item_number 
show_add_to_cart=$_show_add_to_cart 
no_pagination=true*}

{include file="blocks/list_templates/top_home_products_grid.tpl" 
products=$items 
columns=$block.properties.number_of_columns 
no_sorting="Y" 
obj_prefix="`$block.block_id`000" 
item_number=$block.properties.item_number
discount_label= $block.properties.show_discount_label
show_add_to_cart=$_show_add_to_cart 
no_pagination=true}