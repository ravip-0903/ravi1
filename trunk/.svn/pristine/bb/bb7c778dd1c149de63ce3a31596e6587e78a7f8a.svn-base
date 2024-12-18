{if $product.company_id}
	{assign var="vendor_info" value=$product.company_id|fn_sdeep_get_vendor_info}
	{assign var="rating" value=$product.company_id|fn_sdeep_get_rating}
	{assign var="feedback" value=$product.company_id|fn_sdeep_get_vendor_detailed_rating}
	{assign var="auth_dealer_info" value=$product.company_id|fn_sdeep_get_auth_dealer_info}
	<table>
		{if $rating}
			<tr>
				<td>
					{assign var="feedback_count" value=$feedback.count|default:0}
					{assign var="feedback_positive" value=$feedback.positive|default:0}
					{include file="addons/sdeep/components/stars.tpl" stars=$rating|fn_sdeep_get_stars}{if $feedback_count} ({$feedback_count} review{if {$feedback_count > 1}s{/if}){/if}<br/>
					{if $feedback_positive}<br/>{$feedback_positive}% positive review{if {$feedback_count > 1}s{/if}{/if}
				</td>
			</tr>
		{/if}
	</table>
	{if $auth_dealer_info}
	<table>
		<tr>
			<td>
				<h3>{$lang.sdeep_auth_dealer}</h3>
			</td>
			<td>
				{foreach from=$auth_dealer_info item="auth_dealer_item"}
					{if $auth_dealer_item.thumb_path}
						{*include file="common_templates/image.tpl" object_type="feature_variant" images=$auth_dealer_item.pair_id*}
						<img src="{$auth_dealer_item.thumb_path}"/>
					{/if}
				{/foreach}
			</td>
		</tr>
	</table>
	{/if}
	<hr/>
	<a href="{"products.search?company_id=`$product.company_id`&search_performed=Y"|fn_url}">{$lang.view_vendor_products}</a>
{/if}
