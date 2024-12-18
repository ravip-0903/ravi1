{include file="common_templates/subheader.tpl" title=$lang.sdeep_sdeep}
<div class="form-field">
	<label for="is_trm_{$company_data.company_id}">{$lang.sdeep_trm}:</label>
	<input type="hidden" name="company_data[is_trm]" value="N"/>
	<input type="checkbox" id="is_trm_{$company_data.company_id}" name="company_data[is_trm]" {if $company_data.is_trm == 'Y'}checked=checked{/if} value="Y" class="checkbox"/>
</div>
<div class="form-field">
	<label>{$lang.icon} {$lang.url}:</label>
	{include file="common_templates/fileuploader.tpl" var_name="trm_icon[0]"}
	<div class="float-left attach-images-alt logo-image">
		{if $company_data.icon_url}
		<img class="solid-border" src="{$company_data.icon_url}" height="75" />
		{else}
		<img class="logo-empty" src="{$config.no_image_path}" />
		{/if}
	</div>
</div>
{assign var="product_features" value=$company_data.company_id|fn_sdeep_get_vendors_features_variants}
{foreach from=$product_features item="pf"}
	<div class="form-field">
		{*$pf|fn_print_r*}
		<label for="pf_{$pf.variant_id}">{$pf.variant}:</label>
		<input type="checkbox" class="checkbox"  id="pf_{$pf.variant_id}" name="sdeep_features[{$pf.variant_id}]"{if $pf.exists} checked=checked{/if}/>
	</div>
{/foreach}
