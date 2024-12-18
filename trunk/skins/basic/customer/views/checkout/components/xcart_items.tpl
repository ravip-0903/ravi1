{* $Id: cart_items.tpl 12605 2011-06-02 12:38:41Z angel $ *}

{capture name="cartbox"}

    <div id="cart_items">
{if $mode == "checkout"}
	{if $cart.coupons|floatval}<input type="hidden" name="c_id" value="" />{/if}
	{hook name="checkout:form_data"}
	{/hook}
{/if}


{if $cart_products}
<div class="hdr_lst_pop_up_bar no_mobile">
        <div class="xlist_image hdr_lst_pop_up">Item</div>
        <div class="xlist_name hdr_lst_pop_up">Name</div>
        <div class="xlist_quantity hdr_lst_pop_up">Quantity</div>
        <div class="xlist_pricing hdr_lst_pop_up">Price</div>
        <div class="xlist_discount  hdr_lst_pop_up">Discount</div>
        <div class="xlist_shipping  hdr_lst_pop_up">Shipping Cost</div>
        <div class="xlist_subtotal hdr_lst_pop_up">SubTotal</div>
</div>
{assign var="prods" value=false}
{foreach from=$cart_products item="product" key="key" name="cart_products"}
{assign var="obj_id" value=$product.object_id|default:$key}
{hook name="checkout:items_list"}
{if !$cart.products.$key.extra.parent}
<div class="xlist_item">
	{if $smarty.capture.prods}
	{else}
		{capture name="prods"}Y{/capture}
	{/if}
	{if $mode == "cart" || $show_images}
	<div class="xlist_row_itm xlist_image product-image cm-reload-{$obj_id}" id="product_image_update_{$obj_id}">
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
	<!--product_image_update_{$obj_id}-->
        
        <a class="remove no_desktop" href="{"checkout.delete?cart_id=`$key`&amp;redirect_mode=`$mode`"|fn_url}" rev="cart_items,checkout_totals,cart_status,checkout_steps,checkout_cart">Remove</a>
        
        </div>
	{/if}
	<div class="product-description xlist_row_itm xlist_name">
		{if $use_ajax == true && $cart.amount != 1}
			{assign var="ajax_class" value="cm-ajax"}
		{/if}
		
        
		<a href="{"products.view?product_id=`$product.product_id`"|fn_url}" class="{$ajax_class} product-title">{$product.product|unescape}</a>
        <div class="x_list_delete_row no_mobile">
      
		{*for showing options*}
		 {foreach from=$cart.products.$key.product_options item="item"}
	                {assign var ="product_option_count" value=$item|count}
                {/foreach}

                {if $product_option_count == 1}
                    {assign var ="product_option" value=$cart.products.$key.product_options|fn_get_selected_product_options_info}
                    {if $product_option}
                        {include file="common_templates/options_info.tpl" product_options=$product_option}
                    {/if}
                
                {else}
		            {assign var ="product_option_array" value=$cart.products.$key.product_options|fn_get_array_chunk}
		            {foreach from=$product_option_array item="pro_opt"}
				        {assign var ="product_option" value=$pro_opt|fn_get_selected_product_options_info}
				        {if $product_option}
				            {include file="common_templates/options_info.tpl" product_options=$product_option}
				        {/if}
		            {/foreach}
                {/if}
        </div>       
        
		{if $product.product_id|in_array:$cart.no_giftable_products}
        	{*$lang.this_item_is_not_giftable*}
        {/if}
		
		<!--Added by clues dev to show original price when catlog promotion is applied.-->
        
	
		<!--Added by clues dev to show original price when catlog promotion is applied.-->
		

		
		
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
		{/hook}
		{/capture}
		
		{/capture}		
                {if !$product.exclude_from_calculate}
                    <a class="{$ajax_class} mobile-cart-remove no_mobile" href="{"checkout.delete?cart_id=`$key`&amp;redirect_mode=`$mode`"|fn_url}" rev="cart_items,checkout_totals,cart_status,checkout_steps,checkout_cart">
                        Remove
                    </a>
                {/if}                
                
		{if $smarty.capture.$name|trim}
		  | <a id="sw_options_{$key}" class="cm-combination mobile-cart-remove">{$lang.edit}</a>
                    <div id="options_{$key}" class="ProductOptions hidden">
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
        
        <div class="xlist_row_itm xlist_quantity quantity {if $settings.Appearance.quantity_changer == "Y"} changer{/if}" id="quantity_update_{$obj_id}">
			<input type="hidden" name="cart_products[{$key}][product_id]" value="{$product.product_id}" />
			{if $product.exclude_from_calculate}<input type="hidden" name="cart_products[{$key}][extra][exclude_from_calculate]" value="{$product.exclude_from_calculate}" />{/if}

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
			
		<!--quantity_update_{$obj_id}-->
        </div>
        
        
         <div class="xlist_row_itm xlist_pricing">
			<div class="xlist_row_itm xlist_priceoffer" style="float:right;">
                            <span class="list-price margin_right_five">
            {include file="common_templates/price.tpl" value=$product.price span_id="list_price_`$obj_prefix``$obj_id`" class="list_price"}
            </div>
        </div>

<div class="xlist_row_itm xlist_pricing">
{if isset($cart.products.$key.third_price) && $cart.products.$key.third_price==0 || !isset($cart.products.$key.third_price)}
    {assign var="xdiscount" value=0}
{else}
    {assign var="xdiscount" value=$product.price-$cart.products.$key.third_price}
{/if}
<div class="xlist_priceoffer">Rs.{$xdiscount|ceil|number_format}</div>
</div>

<div class="xlist_row_itm xlist_pricing">
{if $product.free_shipping == 'Y' || $product.shipping_freight==0}
    {assign var="shipping_price" value=0}
{else}
    {assign var="shipping_price" value=$product.shipping_freight}
{/if}
    
{if $shipping_price==0}   
<div class="xlist_pricing_priceoffer">Free</div>
{else}
<div class="xlist_pricing_priceoffer">Rs.{$shipping_price|ceil|number_format}</div>
{/if}
</div>

{assign var="product_subtotal_display" value=$product.price-$xdiscount+$shipping_price}
{assign var="product_subtotal_display" value=$product.amount*$product_subtotal_display}

        <div class="xlist_row_itm xlist_subtotal">
        {include file="common_templates/price.tpl" value=$product_subtotal_display span_id="product_subtotal_`$key`" class="price"}
        {if $product.zero_price_action == "A"}
            <input type="hidden" name="cart_products[{$key}][price]" value="{$product.base_price}" />
        {/if}
        </div>
        

</div>
{/if}
{/hook}
{/foreach}
{/if}
{if $cart.gift_certificates|count >0}
 {foreach from=$cart.gift_certificates item="gc" key="key"}
   <div class="xlist_item">
       
       <div class="xlist_row_itm xlist_image">
           <a href="{"gift_certificates.update?gift_cert_id=`$gc.item_id`"|fn_url}">
               <img src="skins/basic/customer/images/icons/gift_certificates_cart_icon.gif" height="40px" />               
           </a>
       </div>
               
        <div class="product-description xlist_row_itm xlist_name">
            <a href="{"gift_certificates.update?gift_cert_id=`$gc.item_id`"|fn_url}">
                {$lang.gift_certificate}
                <br/>
                To: {$gc.recipient}
                <br/>
                From: {$gc.sender}
            </a>
            <div class="x_list_delete_row">
                {if $location!="checkout"}
                    {assign var="delete_cart_gift_certificate" value="index.php?dispatch=gift_certificates.delete&gift_cert_id=`$key`&redirect_mode=delete.from_status&cart_id=`$key`"}
                    <a class="mobile-cart-remove {$ajax_class}" href="{"gift_certificates.delete?gift_cert_id=`$key`&redirect_mode=`$mode`"|fn_url}" rev="cart_items,cart_status,checkout_totals,checkout_steps">Remove</a>
                {/if}
            </div>
        </div>
            
    
    <div class="xlist_row_itm xlist_quantity quantity">
        <input type="text" class="input-text-short cm-amount border_none" disabled value="1" />
    </div>
            
            
    <div class="xlist_row_itm xlist_pricing">
        <div class="xlist_row_itm xlist_priceoffer float_right">
            <span class="list-price margin_right_five">
                <span class="list_price">Rs.</span><span class="list_price">{$gc.amount}</span></span>
        </div>
    </div>
    
    <div class="xlist_row_itm xlist_pricing">
        <div class="xlist_priceoffer">Rs.0</div>
    </div>
        
    <div class="xlist_row_itm xlist_pricing">
            <div class="xlist_priceoffer">Free</div>
    </div>
        
    <div class="xlist_row_itm xlist_subtotal">
        <span class="price">Rs.</span><span class="price">{$gc.amount}</span>
    </div>
        
    <a href="{"gift_certificates.update?gift_cert_id=`$gift_key`"|fn_url}">      
     {assign var="cart_total" value=$cart_total+$gc.amount}
   </div>  
 {/foreach}
{/if}
<!--cart_items--></div>
{/capture}
{include file="common_templates/mainbox_cart.tpl" title=$lang.cart_items content=$smarty.capture.cartbox}
{literal}
<script>
$('.mainbox-cart-title').parent('div').addClass('x_clk_view_cart clk_view_prd_blk').css('display','block');
$('.clk_view_prd_blk').parent('form').addClass('x_clk_prnt');
</script>

{/literal}
