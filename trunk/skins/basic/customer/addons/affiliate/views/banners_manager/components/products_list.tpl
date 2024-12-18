{* $Id: products_list.tpl 11823 2011-02-11 15:55:09Z zeke $ *}

{if $list_data}

<ul class="bullets-list">
{foreach from=$list_data key=product_id item=product_name}
	<li>{include file="common_templates/popupbox.tpl" id="product_`$product_id`" link_text=$product_name text=$lang.product href="banner_products.view?product_id=`$product_id`"}</li>
{/foreach}
</ul>
{/if}