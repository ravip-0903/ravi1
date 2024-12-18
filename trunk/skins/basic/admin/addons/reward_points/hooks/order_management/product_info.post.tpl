{* $Id: product_info.post.tpl 11786 2011-02-08 09:44:48Z 2tl $ *}

{if $cart.points_info.total_price}
	<p>{$lang.price_in_points}:&nbsp;{$cart.products.$key.extra.points_info.price|default:"-"}</p>
{/if}
