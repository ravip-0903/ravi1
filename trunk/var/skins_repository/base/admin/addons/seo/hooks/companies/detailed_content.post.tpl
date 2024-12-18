{* $Id: detailed_content.post.tpl 11132 2010-11-05 13:49:16Z 2tl $ *}

{if !"COMPANY_ID"|defined}
<fieldset>
	{include file="common_templates/subheader.tpl" title=$lang.seo}
	
	<div class="form-field">
		<label for="company_seo_name">{$lang.seo_name}:</label>
		<input type="text" name="company_data[seo_name]" id="company_seo_name" size="55" value="{$company_data.seo_name}" class="input-text-large" />
	</div>
</fieldset>
{/if}