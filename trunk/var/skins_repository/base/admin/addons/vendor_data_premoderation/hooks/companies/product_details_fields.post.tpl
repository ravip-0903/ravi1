{* $Id: product_details_fields.post.tpl 12540 2011-05-26 13:59:02Z 2tl $ *}

{if $smarty.const.PRODUCT_TYPE == "MULTIVENDOR" && ($product_data.company_pre_moderation == "Y" || $product_data.company_pre_moderation_edit == "Y")}
	<div class="form-field">
		<label>{$lang.approved}:</label>
		{if $product_data.approved == "Y"}{$lang.yes}{elseif $product_data.approved == "P"}{$lang.pending}{else}{$lang.no}{/if}
	</div>
{/if}