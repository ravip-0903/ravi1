{* $Id$ *}

{if $order_info.points_info.price && $product}
	<div class="product-list-field">
		<label>{$lang.price_in_points}:</label>
		{* [andyye] *}
		<a href="{$addons.sdeep.cod_explanation_url}">
		{* [/andyye] *}
		{$product.extra.points_info.price}
		{* [andyye] *}
		</a>
		{* [/andyye] *}
	</div>
{/if}
