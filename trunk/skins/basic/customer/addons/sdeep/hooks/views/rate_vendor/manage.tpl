{assign var="orders" value=$auth|fn_sdeep_get_unreviewed_orders}
{if $vendor_rating_params && $orders}
	<form action="{""|fn_url}" method="post" name="sdeep_vendor_rating" class="cm-form-highlight">
		{include file="common_templates/subheader.tpl" title=$lang.sdeep_rate_vendor}
		<div class="form-field">
			<label for="vendors">{$lang.vendor}:</label>
			<select id="vendors" name="order_id">
				{foreach from=$orders item="order"}
					<option value="{$order.order_id}">{$order.company_name}</option>
				{/foreach}
			</select>
		</div>
		{foreach from=$vendor_rating_params item="param" key="k"}
			<div class="form-field">
				<label for="rating_{$k}">{$param.name}:</label>
				<select id="rating_{$k}" name="rating_info[{$k}]">
					<option value="5">Excellent</option>
					<option value="4">Good</option>
					<option value="3">So so</option>
					<option value="2">Bad</option>
					<option value="1">Very bad</option>
				</select>
			</div>
		{/foreach}
		<div class="buttons-container buttons-bg">
			<div class="float-left">
				{include file="buttons/save.tpl" but_name="dispatch[rate_vendor.update]" but_role="button_main"}
			</div>
		</div>
	</form>
{/if}

