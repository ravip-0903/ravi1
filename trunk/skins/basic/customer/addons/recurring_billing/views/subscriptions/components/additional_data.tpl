{* $Id: additional_data.tpl 12724 2011-06-21 12:48:57Z zeke $ *}

{assign var="item_ids" value=","|explode:$data}
{foreach from=$item_ids item="item_id"}
<p>{$item_id|fn_get_product_name|escape}</p>
{/foreach}