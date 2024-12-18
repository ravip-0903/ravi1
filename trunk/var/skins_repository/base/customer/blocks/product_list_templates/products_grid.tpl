{* $Id: products_grid.tpl 11191 2010-11-11 11:56:01Z klerik $ *}
{** template-description:products_grid **}

{include file="blocks/list_templates/products_grid.tpl" 
show_trunc_name=true 
show_sku=true 
show_rating=true 
show_old_price=true 
show_price=true 
show_clean_price=true 
show_add_to_cart=$show_add_to_cart|default:true 
show_list_buttons=true 
but_role="action" 
separate_buttons=true 
no_pagination=$no_pagination}
