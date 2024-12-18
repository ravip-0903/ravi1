{* $Id: checkout_cart.tpl 12643 2011-06-07 09:21:22Z subkey $ *}

<div class="checkout-steps clear cm-save-fields" id="checkout_cart">
	<div class="step-container{if $expand_cart}-active{/if}">
		<h2 class="step-title{if $expand_cart}-active{/if} clear">
			{assign var="url" value="`$config.current_url`"|fn_query_remove:"expand_cart"}			
			{if !$expand_cart}
				<a href="{$url|fn_query_remove:"is_ajax"}&amp;expand_cart=Y" rev="checkout_cart" title="{$lang.show_cart_content}" class="cm-ajax title float-left">{$lang.cart_content}</a>
				<span class="subtotal float-left ">{$lang.subtotal}: {include file="common_templates/price.tpl" value=$cart.display_subtotal}</span>
				{if $completed_steps.step_three == true}
					<!--<span class="total float-left">{$lang.total}: {include file="common_templates/price.tpl" value=$cart.total}</span>-->
                    {if $cart.has_coupons && $cart.promotions}
                    {include file="views/checkout/components/applied_promotions.tpl" location=$location show_active="false"}
                    {/if}
				{/if}
				<span class="checkout-show-hide button-tool"><a href="{$url|fn_query_remove:"is_ajax"}&amp;expand_cart=Y" rev="checkout_cart" class="cm-ajax">{$lang.view}</a></span>
			{else}
				<a href="{$url|fn_query_remove:"is_ajax"}&amp;expand_cart=N" rev="checkout_cart" title="{$lang.hide_cart_content}" class="cm-ajax title float-left">{$lang.cart_content}</a>
				<span class="checkout-show-hide button-tool"><a href="{$url|fn_query_remove:"is_ajax"}&amp;expand_cart=N" rev="checkout_cart" class="cm-ajax">{$lang.hide}</a></span>
			{/if}
		</h2>
		{if $expand_cart}
			<div class="step-body-active">
				{assign var="result_ids" value="cart_items,checkout_totals,checkout_steps,cart_status,checkout_cart"}

				<form name="checkout_form" class="cm-check-changes cm-ajax" action="{""|fn_url}" method="post" enctype="multipart/form-data">
				<input type="hidden" name="redirect_url" value="{$url}" />
				<input type="hidden" name="redirect_mode" value="checkout" />
				<input type="hidden" name="result_ids" value="{$result_ids}" />

				{include file="views/checkout/components/cart_items.tpl" disable_ids="button_cart" show_images=true}

				<div class="cart-buttons clear">
					<div class="float-right">{include file="buttons/update_cart.tpl" but_id="button_cart" but_name="dispatch[checkout.update]"}</div>
				</div>
				</form>
				
				{assign var="position" value="_cart"}
				
				{*{capture name="cart_promotions"}
					{if $cart.has_coupons}
						{include file="views/checkout/components/promotion_coupon.tpl" location=$location}
					{/if}
					
					{hook name="checkout:payment_extra"}
					{/hook}
				{/capture}
				{if $smarty.capture.cart_promotions|trim}
					<div class="coupon-code-container">
						{$smarty.capture.cart_promotions}
					</div>
				{/if}*}
				
				{assign var="position" value=""}
				
				<div class="costs">
					<span class="subtotal">{$lang.subtotal}: {include file="common_templates/price.tpl" value=$cart.subtotal}</span>
				{if $completed_steps.step_three == true}
					<!--<span class="total">{$lang.total}: {include file="common_templates/price.tpl" value=$cart.total}</span>-->
				{/if}                
                {if $cart.has_coupons && $cart.promotions}
                    <span style="float: right;width: auto; ">
                    {include file="views/checkout/components/applied_promotions.tpl" location=$location show_active="false"}</span>
                {/if}
				</div>
			</div>
		{/if}
	</div>
<!--checkout_cart--></div>