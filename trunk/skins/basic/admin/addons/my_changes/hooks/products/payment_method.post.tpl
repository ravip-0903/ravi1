{if $mode == 'update'}
{*{assign var="payment_methods" value=""|fn_my_changes_payment_methods}
<div class="form-field">
	<label for="product_allowed_payment_method">Allowed Payment Method:</label>
	<div class="select-field">
    {foreach from=$payment_methods item="payment_method"}
			<input type="checkbox" name="product_data[allowed_payment_method][{$payment_method.payment_id}]" id="product_data_{$payment_method.payment_id}" {if $payment_method.payment_id|in_array:$product_data.allowed_payment_method}checked="checked"{/if} class="checkbox" value="{$payment_method.payment_id}" />
			<label for="product_data_{$payment_method.payment_id}">{$payment_method.payment}</label>
		{/foreach}
  	</div>
</div>*}

<div class="form-field">
	<label for="product_allowed_payment_method">{$lang.cod_allowed}:</label>
	<div class="select-field">
            <input type="radio" name="product_data[is_cod]" id="product_data_is_cod" {if $product_data.is_cod == "Y"}checked="checked"{/if} class="checkbox" value="Y" />Yes
            <input type="radio" name="product_data[is_cod]" id="product_data_is_cod" {if $product_data.is_cod == "N"}checked="checked"{/if} class="checkbox" value="N" />No
  	</div>
</div>

{assign var="shipping_estimations" value=""|fn_my_changes_get_shipping_estimation}
<div class="form-field">
	<label for="product_shipping_estimation" {if $product_data.status == 'A'}class="cm-required"{/if}>{$lang.shipping_estimate}:</label>
	<div class="select-field">
    <select name="product_data[product_shipping_estimation]" id="product_shipping_estimation">		
    <option value="">SELECT</option>
    {foreach from=$shipping_estimations item="shipping_estimation"}
		<option value="{$shipping_estimation.id}" {if $product_data.product_shipping_estimation == $shipping_estimation.id}selected="selected"{/if}>{$shipping_estimation.name}</option>
	{/foreach}
    </select>
  	</div>
</div>

{/if}