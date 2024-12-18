{* $Id: detailed_content.post.tpl 11786 2011-02-08 09:44:48Z 2tl $ *}

<fieldset>
	{include file="common_templates/subheader.tpl" title=$lang.seo}
	
	<div class="form-field">
		<label for="seo_name">{$lang.seo_name}:</label>
		<input type="text" name="category_data[seo_name]" id="seo_name" size="55" value="{$category_data.seo_name}" class="input-text-long" />
	</div>
</fieldset>