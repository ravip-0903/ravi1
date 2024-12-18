{* $Id: products_multicolumns2.tpl 11191 2010-11-11 11:56:01Z klerik $ *}
{** block-description:grid2 **}

{if $block.properties.hide_add_to_cart_button == "Y"}
	{assign var="_show_add_to_cart" value=false}
{else}
	{assign var="_show_add_to_cart" value=true}
{/if}

{include file="blocks/product_list_templates/products_multicolumns2.tpl" 
products=$items columns=$block.properties.number_of_columns 
form_prefix="block_manager" 
no_sorting="Y" 
no_pagination="Y" 
no_ids="Y" 
obj_prefix="`$block.block_id`000" 
item_number=$block.properties.item_number 
show_add_to_cart=$_show_add_to_cart}
