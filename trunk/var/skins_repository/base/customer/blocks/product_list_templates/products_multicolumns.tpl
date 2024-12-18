{* $Id: products_multicolumns.tpl 11191 2010-11-11 11:56:01Z klerik $ *}
{** template-description:grid **}

{include file="blocks/list_templates/grid_list.tpl" 
show_trunc_name=true 
show_old_price=true 
show_price=true 
show_clean_price=true 
show_list_discount=true 
show_add_to_cart=$show_add_to_cart|default:true 
but_role="action"}