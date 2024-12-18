{* $Id: items_list_row.post.tpl 10172 2010-07-22 11:45:57Z alexions $ *}

{assign var="products" value=$wishlist_products}
{assign var="show_price" value=false}

<td valign="top" colspan="2">
	<div id="wishlist_products_{$customer.user_id}">
	{if $customer.user_id == $sl_user_id}
		{if $wishlist_products}
		<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table">
		<tr>
			<th>{$lang.wishlist_products}</th>
		</tr>
		
		{foreach from=$wishlist_products item="product" name="products"}
		{hook name="cart:product_row"}
		{if !$product.extra.extra.parent}
		<tr>
			<td>
			{if $product.item_type == "P"}
				{if $product.product}
				<a href="{"products.update?product_id=`$product.product_id`"|fn_url}">{$product.product|unescape}</a>
				{else}
				{$lang.deleted_product}
				{/if}
			{/if}
			{hook name="cart:products_list"}
			{/hook}
			</td>
		</tr>
		{/if}
		{/hook}
		{/foreach}
		</table>
		{else}
		&nbsp;
		{/if}
	{else}
		&nbsp;
	{/if}
	<!--wishlist_products_{$customer.user_id}--></div>
</td>