{include file="common_templates/subheader.tpl" title=$lang.sdeep_feedback}
{assign var="feedback" value=$company_data.company_id|fn_sdeep_get_vendor_detailed_rating}
{assign var="feedback30" value=$company_data.company_id|fn_sdeep_get_vendor_detailed_rating_30days}
{assign var="feedback90" value=$company_data.company_id|fn_sdeep_get_vendor_detailed_rating_90days}
{assign var="feedback365" value=$company_data.company_id|fn_sdeep_get_vendor_detailed_rating_365days}
<table border="0" cellspacing="0" cellpadding="0" class="table">
	<tr>
		<th>Feedback</th>
		<th>30 days</th>
		<th>90 days</th>
		<th>365 days</th>
		<th>Lifetime</th>
	</tr>
	<tr>
		<td>Positive</td>
		<td>{$feedback30.positive|default:0}%</td>
		<td>{$feedback90.positive|default:0}%</td>
		<td>{$feedback365.positive|default:0}%</td>
		<td>{$feedback.positive|default:0}%</td>
	</tr>
	<tr>
		<td>Neutral</td>
		<td>{$feedback30.neutral|default:0}%</td>
		<td>{$feedback90.neutral|default:0}%</td>
		<td>{$feedback365.neutral|default:0}%</td>
		<td>{$feedback.neutral|default:0}%</td>
	</tr>
	<tr>
		<td>Negative</td>
		<td>{$feedback30.negative|default:0}%</td>
		<td>{$feedback90.negative|default:0}%</td>
		<td>{$feedback365.negative|default:0}%</td>
		<td>{$feedback.negative|default:0}%</td>
	</tr>
	<tr>
		<td>Count</td>
		<td>{$feedback30.count|default:0}</td>
		<td>{$feedback90.count|default:0}</td>
		<td>{$feedback365.count|default:0}</td>
		<td>{$feedback.count|default:0}</td>
	</tr>
</table>
<a href="{$addons.sdeep.what_these_mean_url}">What do these mean?</a>
{assign var="terms" value=$company_data.company_id|fn_sdeep_get_terms}
{if $terms.guarantee}
	{include file="common_templates/subheader.tpl" title=$lang.sdeep_guarantee_terms}
	<div id="terms_{$product.company_id}">
		{$terms.guarantee}
	</div>
{/if}
{if $terms.return}
	{include file="common_templates/subheader.tpl" title=$lang.sdeep_return_terms}
	<div id="terms_{$product.company_id}">
		{$terms.return}
	</div>
{/if}
{if $terms.shipping}
	{include file="common_templates/subheader.tpl" title=$lang.sdeep_shipping_terms}
	<div id="terms_{$product.company_id}">
		{$terms.shipping}
	</div>
{/if}

