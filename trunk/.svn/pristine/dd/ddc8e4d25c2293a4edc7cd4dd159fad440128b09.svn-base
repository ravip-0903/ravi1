{** block-description:sdeep_auth_dealer **}
<div class="produ_detai_right_b_mng">
{if $product.company_id}
	{assign var="vendor_info" value=$product.company_id|fn_sdeep_get_vendor_info}
	{assign var="rating" value=$product.company_id|fn_sdeep_get_rating}
	{assign var="feedback" value=$product.company_id|fn_sdeep_get_vendor_detailed_rating}
	{assign var="auth_dealer_info" value=$product.company_id|fn_sdeep_get_auth_dealer_info}
	<table>
		<tr>
			<td{if $rating} {/if}>
				<h3>{$vendor_info.company}</h3>
                <div class="right_right_ico">{include file="addons/sdeep/common_templates/vendor_icons.tpl" vendor_info=$vendor_info size="50"}</div>
			</td>
		</tr>
		{if $rating}
			<tr>
				<td class="right_retail_info">
					{assign var="feedback_count" value=$feedback.count|default:0}
					{assign var="feedback_positive" value=$feedback.positive|default:0}
					{include file="addons/sdeep/components/stars.tpl" stars=$rating|fn_sdeep_get_stars}
                    <span class="revie_con">
                    {if $feedback_count} ({$feedback_count} review{if {$feedback_count > 1}s{/if}){/if}
                    </span>
					<p class="posi_feed">{if $feedback_positive}{$feedback_positive}% positive review{if {$feedback_count > 1}s{/if}{/if}</p>
				</td>
			</tr>
		{/if}
	</table>
	{if $auth_dealer_info}
	<hr class="hrmng"/>
	<table>
		<tr>
			<td valign="top" width="60">
				<h3>{$lang.sdeep_auth_dealer}</h3>
			</td>
			<td class="marc_brand_img">
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
	<hr class="hrmng"/>
	{assign var="is_trm" value=$product.company_id|fn_sdeep_is_trm}
	{if $is_trm}
		<ul class="trm_feture">
			<li>Consistently receives heightest merchant rating</li>
			<li>Ships Items Quickly</li>
		</ul>
	{/if}
	<span class="view_vendor_products"><a href="{"products.search?company_id=`$product.company_id`&search_performed=Y"|fn_url}">{$lang.view_vendor_products}</a></span>
{/if}
</div>