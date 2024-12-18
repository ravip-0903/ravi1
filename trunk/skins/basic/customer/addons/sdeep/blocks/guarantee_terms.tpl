{** block-description:sdeep_guarantee_terms **}
{if $product.company_id}
	{assign var="terms" value=$product.company_id|fn_sdeep_get_terms}
	{if $terms.guarantee}
		<div id="terms_{$product.company_id}">
			{$terms.guarantee}
		</div>
	{/if}
{/if}

