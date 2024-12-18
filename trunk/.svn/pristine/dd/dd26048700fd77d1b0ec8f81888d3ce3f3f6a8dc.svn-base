{assign var="payment_methods" value=""|fn_my_changes_payment_methods}
<div class="form-field">
	<label for="product_allowed_payment_method">Allowed Payment Method:</label>
	<div class="select-field">
    {foreach from=$payment_methods item="payment_method"}
			<input type="checkbox" name="product_data[allowed_payment_method][{$payment_method.payment_id}]" id="product_data_{$payment_method.payment_id}" {if $payment_method.payment_id|in_array:$product_data.allowed_payment_method}checked="checked"{/if} class="checkbox" value="{$payment_method.payment_id}" />
			<label for="product_data_{$payment_method.payment_id}">{$payment_method.payment}</label>
		{/foreach}
  	</div>
</div>