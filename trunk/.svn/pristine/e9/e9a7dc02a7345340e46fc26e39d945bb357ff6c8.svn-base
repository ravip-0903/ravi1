{* $Id: cart_status.tpl 12068 2011-03-17 12:38:40Z 2tl $ *}

<div>
{hook name="checkout:minicart"}
	<div id="cart_status">
	<div class="box_cartstatus">
		{*if $smarty.session.cart.amount*}
        {if $smarty.session.cart.products|count || $smarty.session.cart.gift_certificates|count}
            {assign var="_cart_products" value=$smarty.session.cart.products|array_reverse:true}
            {if isset($smarty.session.cart.gift_certificates)}
              {assign var="_gift_certificate" value=$smarty.session.cart.gift_certificates|count}
            {else}
              {assign var="_gift_certificate" value=0}
            {/if}
            {assign var="product_count_in_cart" value=$_cart_products|count}
            {assign var="product_count_in_cart" value=$product_count_in_cart+$_gift_certificate}
            <a class="shopping_cart_link cm-combination cm-combo-on valign hand " href="{$cofig.http_location}index.php?dispatch=checkout.cart">                
                <span class="bold nl_new_luk_cart_no">{$product_count_in_cart}</span>{if $product_count_in_cart>1 }{/if}
                Cart
            </a>
        
        {else}
		
        	<a class="shopping_cart_link cm-combination cm-combo-on valign hand " href="{$cofig.http_location}index.php?dispatch=checkout.cart"><span class="bold nl_new_luk_cart_no">0</span>Cart</a>
		
        {/if}
	
		<div id="cart_box" {if $product_count_in_cart>0} class="cart-list cm-popup-box" {else}class="hidden cm-popup-box"{/if}>
			<div class="list-container">
				<div class="list">
				{*if $smarty.session.cart.amount*}
                {if $smarty.session.cart.products|count || $smarty.session.cart.gift_certificates|count}
					<ul>
						{hook name="index:cart_status"}
						{assign var="_cart_products" value=$smarty.session.cart.products|array_reverse:true}
						{foreach from=$_cart_products key="key" item="p" name="cart_products"}
						{if !$p.extra.parent}
						<li class="clear">
							<a href="{"products.view?product_id=`$p.product_id`"|fn_url}" style="font:12px trebuchet ms; width:85%; color:#007acc !important;">{$p.product_id|fn_get_product_name|unescape}</a>
                            <div style="float:right; margin-top:5px;">{if (!"CHECKOUT"|defined || $force_items_deletion) && !$p.extra.exclude_from_calculate}

			{*{include file="buttons/button.tpl" but_href="checkout.delete.from_status?cart_id=`$key`" but_meta="cm-ajax" but_rev="cart_status" but_role="delete" but_name="delete_cart_item"}*}
	
			{/if}
                            </div>
							<p style="float:left; width:100%;">
								<strong class="valign">{$p.amount}</strong>&nbsp;x&nbsp;
                                {*include file="common_templates/price.tpl" value=$p.display_price span_id="price_`$key`" class="none"*}
                                {include file="common_templates/price.tpl" value=$p.price span_id="price_`$key`" class="none"}
							</p>
						</li>
						{if !$smarty.foreach.cart_products.last}
							<li class="delim">&nbsp;</li>
						{/if}
						{/if}
						{/foreach}
						{/hook}
					</ul>
				{else}
					<p class="center">{$lang.cart_is_empty}</p>
				{/if}
				</div>
				<div class="buttons-container {if $smarty.session.cart.products|count > 0 || $smarty.session.cart.gift_certificates|count > 0} full-cart{/if}">
                    <a href="{"checkout.cart"|fn_url}" rel="nofollow" class="view-cart">{$lang.view_cart}</a>
					{if $settings.General.checkout_redirect != "Y"}
						<!--<a href="{"checkout.checkout&edit_step=step_two"|fn_url}" rel="nofollow">{$lang.checkout}</a>-->
                        <a href="{$config.https_location}/index.php?dispatch=checkout.checkout&edit_step=step_two" rel="nofollow">{$lang.checkout}</a>
					{/if}
				</div>
			</div>
		</div>
	</div>

	
	<!--cart_status--></div>
{/hook}
</div>
