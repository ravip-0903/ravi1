{* $Id: buy_now.pre.tpl 9783 2010-06-10 10:24:09Z lexa $ *}

{if !$hide_wishlist_button}
	<!--<hr />-->
    {if (($product_amount <= 0 || $product_amount < $product.min_qty) && ($product.avail_since > $smarty.const.TIME))}
    {hook name="products:options_advanced"}
	{/hook}
    {/if}
    {if $product.product_id != $config.ws_subscription_product}
    {include file="addons/wishlist/views/wishlist/components/add_to_wishlist.tpl" but_id="button_wishlist_`$obj_prefix``$product.product_id`" but_name="dispatch[wishlist.add..`$product.product_id`]" but_role="text" but_meta="mob-wshlst-btn"}
    {/if}
{/if}