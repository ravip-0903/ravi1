{* $Id: add_to_cart.override.tpl 12724 2011-06-21 12:48:57Z zeke $ *}

{if $product.company_id|fn_catalog_mode_enabled == 'Y'}
	{if !$product.buy_now_url && $addons.catalog_mode.add_to_cart_empty_buy_now_url != 'Y'}
		&nbsp;
	{elseif !$product.buy_now_url}
		&nbsp;
	{else}
		{include file="buttons/button.tpl" but_id=$but_id but_text=$lang.buy_now but_href=$product.buy_now_url but_role=$but_role|default:"text" but_name=""}
	{/if}
{/if}
