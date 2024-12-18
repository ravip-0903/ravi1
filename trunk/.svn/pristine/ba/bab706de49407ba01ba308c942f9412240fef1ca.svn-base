{* $Id: cart.tpl 9721 2010-06-04 11:17:55Z lexa $ *}

{script src="js/exceptions.js"}

<div class="box_header border_none">
    <h1 class="box_heading">{if $config.xbuy_now_popup}{$lang.xyour_shopping_cart}{else}{$lang.your_shopping_cart}{/if}</h1>
</div>
<div class="clearboth height_ten"></div>


{if !$cart|fn_cart_is_empty}
	{include file="views/checkout/components/cart_content.tpl"}
{else}
	<p class="no-items">{$lang.text_cart_empty}</p>

	<div class="buttons-container center box_functions">
		{include file="buttons/continue_shopping.tpl" but_href=$continue_url|default:$index_script but_role="action"}
	</div>
{/if}