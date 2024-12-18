	<form action="{""|fn_url}" method="post" name="sdeep_vendor_rating" class="cm-form-highlight">
		<input type="hidden" name="product_id" value="{$smarty.request.product_id}"/>
		{include file="common_templates/subheader.tpl" title=$lang.sdeep_rate_product}
		<div class="form-field">
			<label for="sdeep_product_rating">{$lang.sdeep_rate_product}:</label>
			<select id="sdeep_product_rating" name="rating">
				<option value="5">Excellent</option>
				<option value="4">Good</option>
				<option value="3">So so</option>
				<option value="2">Bad</option>
				<option value="1">Very bad</option>
			</select>
		</div>
		<div class="buttons-container buttons-bg">
			<div class="float-left">
				{include file="buttons/save.tpl" but_name="dispatch[rate_vendor.update]" but_role="button_main"}
			</div>
		</div>
	</form>
