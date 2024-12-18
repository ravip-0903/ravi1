{** block-description:sdeep_return_terms **}
{if $product.company_id}
	{assign var="terms" value=$product.company_id|fn_sdeep_get_terms}
	{if $terms.return}
		<div id="terms_{$product.company_id}">
			{$terms.return}
		</div>
	{/if}
{/if}

