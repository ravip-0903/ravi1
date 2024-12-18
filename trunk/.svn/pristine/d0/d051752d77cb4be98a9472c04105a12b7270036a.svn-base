<form action="{""|fn_url}" method="post" name="sdeep_vendor_rating_params" class="cm-form-highlight">
	{if $vendor_rating_params}
		{include file="common_templates/subheader.tpl" title=$lang.sdeep_configure_ratings}
		{foreach from=$vendor_rating_params item="param" key="k"}
			<h3>Parameter {$k+1}</h3>
			<div class="form-field">
				<label for="vendor_parameter_{$k}_name">{$lang.name}:</label>
				<input type="text" id="vendor_parameter_{$k}_name" name="vendor_parameters[{$k}][name]" value="{$param.name}"  class="input-text" size="30">
			</div>
			<div class="form-field">
				<label for="vendor_parameter_{$k}_icon_url">{$lang.icon} {$lang.url}:</label>
				<input type="text" id="vendor_parameter_{$k}_icon_url" name="vendor_parameters[{$k}][icon_url]" value="{$param.icon_url}"  class="input-text" size="30">
			</div>
		{/foreach}
	{/if}
	<div class="buttons-container buttons-bg">
		<div class="float-left">
			{include file="buttons/save.tpl" but_name="dispatch[sdeep_ratings.update]" but_role="button_main"}
		</div>
	</div>
</form>
