{* $Id: cart_items.tpl 12605 2011-06-02 12:38:41Z angel $ *}

{capture name="cartbox"}

    <div id="cart_items">
{if $mode == "checkout"}
	{if $cart.coupons|floatval}<input type="hidden" name="c_id" value="" />{/if}
	{hook name="checkout:form_data"}
	{/hook}
{/if}


{if $cart_products}

{assign var="prods" value=false}
{foreach from=$cart_products item="product" key="key" name="cart_products"}
{assign var="obj_id" value=$product.object_id|default:$key}
{hook name="checkout:items_list"}
{if !$cart.products.$key.extra.parent}
<div class="clear">
	{if $smarty.capture.prods}
		<hr class="dark-hr" />
	{else}
		{capture name="prods"}Y{/capture}
	{/if}
	{if $mode == "cart" || $show_images}
	<div class="product-image cm-reload-{$obj_id}" id="product_image_update_{$obj_id}">
            <!--changed by ankur to selected product option image  -->
            {if !empty($cart.products.$key.product_options)}
                  {assign var="pro_images" value=$product.product_id|fn_get_product_option_image:$cart.products.$key.product_options}
                  <a href="{"products.view?product_id=`$product.product_id`"|fn_url}">
                {include file="common_templates/image.tpl" obj_id=$key images=$pro_images object_type="product" show_thumbnail="Y" image_width="160" image_height="160"}
              {else}
		<a href="{"products.view?product_id=`$product.product_id`"|fn_url}">
		{include file="common_templates/image.tpl" obj_id=$key images=$product.main_pair object_type="product" show_thumbnail="Y" image_width="160" image_height="160"}
		
		</a>
              {/if}
	<!--product_image_update_{$obj_id}--></div>
	{/if}
	<div class="product-description">
		{if $use_ajax == true && $cart.amount != 1}
			{assign var="ajax_class" value="cm-ajax"}
		{/if}
		
		<a href="{"products.view?product_id=`$product.product_id`"|fn_url}" class="product-title">{$product.product|unescape}</a>
        {if !$product.exclude_from_calculate}&nbsp;<a class="{$ajax_class} mobile-cart-remove" href="{"checkout.delete?cart_id=`$key`&amp;redirect_mode=`$mode`"|fn_url}" rev="cart_items,checkout_totals,cart_status,checkout_steps,checkout_cart"><img src="{$images_dir}/icons/delete_product.gif" width="12" height="12" border="0" alt="{$lang.remove}" align="bottom" title="{$lang.remove}" /></a>{/if}
		{if $product.product_id|in_array:$cart.no_giftable_products}
        	{$lang.this_item_is_not_giftable}
        {/if}
		<p class="sku{if !$product.product_code} hidden{/if}" id="sku_{$key}">
			{$lang.sku}: <span class="cm-reload-{$obj_id}" id="product_code_update_{$obj_id}">{$product.product_code}<!--product_code_update_{$obj_id}--></span>
		</p>
		<!--Added by clues dev to show original price when catlog promotion is applied.-->
		{if $product.promotions}
			{if $product.list_price != $product.base_price}
			<div>
			<span class="list-price margin_right_five">{$lang.list_price}:
			<strike>
				{include file="common_templates/price.tpl" value=$product.list_price span_id="list_price_`$obj_prefix``$obj_id`" class="list_price"}
			</strike>
			</div>
			{/if}
		{/if}
		<div class="clearboth"></div>
		{if $product.base_price != $product.price}
			<div>
			<span class="list-price margin_right_five">{$lang.regular_price}:
			<strike>
				{include file="common_templates/price.tpl" value=$product.base_price span_id="list_price_`$obj_prefix``$obj_id`" class="list_price"}
			</strike>
			{assign var="initial_discount" value=$product.list_price|calculate_percent_offer:$product.base_price}
			{if $initial_discount > 0}({$initial_discount}% off on {$lang.list_price}){/if}
			</div>
			
		{/if}
		<div class="clearboth"></div>
		<div>
		<span class="list-price margin_right_five">{if $product.base_price != $product.price}{$lang.offer_price}{else}{$lang.selling_price}{/if}:
		{include file="common_templates/price.tpl" value=$product.price span_id="list_price_`$obj_prefix``$obj_id`" class="list_price"}
		{assign var="final_discount" value=$product.base_price|calculate_percent_offer:$product.price}
		{if $final_discount > 0}({$final_discount}% off on {$lang.regular_price}){/if}
		</div>
		
		<div class="clearboth"></div>
		<!--Added by clues dev to show original price when catlog promotion is applied.-->
		
		
		<div class="quantity cm-reload-{$obj_id}{if $settings.Appearance.quantity_changer == "Y"} changer{/if}" id="quantity_update_{$obj_id}">
			<input type="hidden" name="cart_products[{$key}][product_id]" value="{$product.product_id}" />
			{if $product.exclude_from_calculate}<input type="hidden" name="cart_products[{$key}][extra][exclude_from_calculate]" value="{$product.exclude_from_calculate}" />{/if}

			<label for="amount_{$key}">{$lang.qty}:</label>
			{if $product.qty_content && $product.is_edp != "Y"}
			<select name="cart_products[{$key}][amount]" id="amount_{$key}" onchange="fn_change_options({$obj_id})">
			{foreach from=$product.qty_content item="var"}
				<option value="{$var}"{if $product.amount == $var} selected="selected"{/if}>{$var}</option>
			{/foreach}
			</select>
			{else}
				{if $settings.Appearance.quantity_changer == "Y"}
				<div class="center valign cm-value-changer">
				<a class="cm-increase"><img src="{$images_dir}/icons/up_arrow.gif" width="11" height="5" border="0" /></a>
				{/if}
				<input type="text" size="3" id="amount_{$key}" name="cart_products[{$key}][amount]" value="{$product.amount}" class="input-text-short cm-amount{if $product.is_edp == "Y" || $product.exclude_from_calculate} disabled{/if}" {if $product.is_edp == "Y" || $product.exclude_from_calculate}disabled="disabled"{/if} />
				{if $settings.Appearance.quantity_changer == "Y"}
				<a class="cm-decrease"><img src="{$images_dir}/icons/down_arrow.gif" width="11" height="5" border="0" /></a>
				</div>
				{/if}
			{/if}
			{if $product.is_edp == "Y" || $product.exclude_from_calculate}
				<input type="hidden" name="cart_products[{$key}][amount]" value="{$product.amount}" />
			{/if}
			{if $product.is_edp == "Y"}
				<input type="hidden" name="cart_products[{$key}][is_edp]" value="Y" />
			{/if}
			x&nbsp;{include file="common_templates/price.tpl" value=$product.display_price span_id="product_price_`$key`" class="sub-price"}
			&nbsp;=&nbsp;&nbsp;&nbsp;{include file="common_templates/price.tpl" value=$product.display_subtotal span_id="product_subtotal_`$key`" class="price"}
			{if $product.zero_price_action == "A"}
				<input type="hidden" name="cart_products[{$key}][price]" value="{$product.base_price}" />
			{/if}
		<!--quantity_update_{$obj_id}--></div>
		
		{assign var="name" value="product_options_$key"}
		{capture name=$name}
		{if $product.product_options}
			<div class="cm-reload-{$obj_id}" id="options_update_{$obj_id}">
			{include file="views/products/components/product_options.tpl" product_options=$product.product_options product=$product name="cart_products" id=$key location="cart" disable_ids=$disable_ids form_name="checkout_form"}
			<!--options_update_{$obj_id}--></div>
		{/if}

		{capture name="product_info_update"}
		{hook name="checkout:product_info"}
		    {if $product.exclude_from_calculate}
				<strong><span class="price">{$lang.free}</span></strong>
			{elseif $product.discount|floatval || $product.taxes}
				
				<!--<table class="table" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<th>{$lang.list_price}</th>
					<th>{$lang.quantity}</th>
					{if $product.discount|floatval}<th>{$lang.discount}</th>{/if}
					{if $product.taxes && $settings.General.tax_calculation != "subtotal"}<th>{$lang.tax}</th>{/if}
					<th>{$lang.subtotal}</th>
				</tr>
				<tr>
					<td>{include file="common_templates/price.tpl" value=$product.list_price span_id="original_price_`$key`" class="none"}</td>
					<td class="center">{$product.amount}</td>
					
					{if $product.discount|floatval}
						{assign var="new_discount" value=$product.list_price-$product.price}
						<td>{include file="common_templates/price.tpl" value=$new_discount span_id="discount_subtotal_`$key`" class="none"}</td>
					{/if}
					{if $product.taxes && $settings.General.tax_calculation != "subtotal"}<td>{include file="common_templates/price.tpl" value=$product.tax_summary.total span_id="tax_subtotal_`$key`" class="none"}</td>{/if}
					<td>{include file="common_templates/price.tpl" span_id="product_subtotal_2_`$key`" value=$product.display_subtotal class="none"}</td>
				</tr>
				<tr class="table-footer">
					<td colspan="5">&nbsp;</td>
				</tr>
				</table>-->
			{/if}
			{include file="views/companies/components/product_company_data.tpl" company_name=$product.company_name company_id=$product.company_id}
		{/hook}
		{/capture}
		{if $smarty.capture.product_info_update|trim}
			<div class="cm-reload-{$obj_id}" id="product_info_update_{$obj_id}">
				{$smarty.capture.product_info_update}
			<!--product_info_update_{$obj_id}--></div>
		{/if}
		{/capture}
		
		{if $smarty.capture.$name|trim}
		<p><a id="sw_options_{$key}" class="cm-combo-on cm-combination">{$lang.text_click_here}</a></p>
		<div id="options_{$key}" class="product-options hidden">
			{$smarty.capture.$name}
		</div>

		{/if}
                {if ($smarty.cookies.pincode != '') &&  $invalid_pin != '-1'}
                {assign var="is_serviceable" value=$product.product_id|get_servicability_type:$smarty.cookies.pincode}
                {if $is_serviceable == '4'}
                    <div class="foot_note_nl" style="color:#666">
                        {$lang.not_cod_only_prepaid}
                    </div>
                {elseif $is_serviceable == '0'}
                    <div class="foot_note_nl" style="color:#ff0000">
                        {$lang.nss_product}
                    </div>
                {/if}
                {/if}
	</div>
</div>
{/if}
{/hook}
{/foreach}
{/if}

{hook name="checkout:extra_list"}
{/hook}

<!--cart_items--></div>
{/capture}
{include file="common_templates/mainbox_cart.tpl" title=$lang.cart_items content=$smarty.capture.cartbox}
