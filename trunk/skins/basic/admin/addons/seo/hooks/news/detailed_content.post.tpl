{* $Id: detailed_content.post.tpl 12724 2011-06-21 12:48:57Z zeke $ *}

<fieldset>
	{include file="common_templates/subheader.tpl" title=$lang.seo}
	
	<div class="form-field">
		<label for="seo_name">{$lang.seo_name}:</label>
		<input type="text" name="news_data[seo_name]" id="seo_name" size="55" maxlength="255" value="{$news_data.seo_name}" class="input-text" />
	</div>
</fieldset>