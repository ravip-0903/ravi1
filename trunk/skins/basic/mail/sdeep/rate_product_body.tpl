{$lang.sdeep_please_rate_products}:<br/>
<ul>
	{foreach from=$items item="oi"}
		<li>
			{assign var="product_id" value=$oi.product_id}
			<a href="{"rate_product.manage?product_id=$product_id"|fn_url:'C':'http'}">{$oi.product}</a>
		</li>
	{/foreach}
</ul>
