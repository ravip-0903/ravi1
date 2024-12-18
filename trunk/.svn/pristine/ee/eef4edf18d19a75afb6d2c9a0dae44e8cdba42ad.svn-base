{** block-description:sdeep_shipping_terms **}
{if $product.company_id}
	{assign var="terms" value=$product.company_id|fn_sdeep_get_terms}
	{if $terms.shipping}
		<div id="terms_{$product.company_id}">
			{$terms.shipping}
		</div>
	{/if}
{/if}

