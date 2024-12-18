<form action="{""|fn_url}" method="post" name="sdeep_iconization" class="cm-form-highlight">
	{include file="common_templates/subheader.tpl" title=$lang.sdeep_iconization}
	{foreach from=$lang_icons item="icon" key="k"}
		{if $k}<hr/>{/if}
		<div class="form-field">
			<label for="icon_{$k}_pattern">{$lang.sdeep_pattern}:</label>
			<input type="text" id="icon_{$k}_pattern" name="icons[{$k}][pattern]" value="{$icon.pattern}"  class="input-text" size="20">
		</div>
		<div class="form-field">
			<label for="icon_{$k}_url">{$lang.icon} {$lang.url}:</label>
			<input type="text" id="icon_{$k}_url" name="icons[{$k}][icon_url]" value="{$icon.icon_url}"  class="input-text" size="30">
		</div>
	{/foreach}
	{if $lang_icons}<hr/>{/if}
	<div class="form-field">
		<label for="icon_new_pattern">{$lang.new} {$lang.sdeep_pattern}:</label>
		<input type="text" id="icon_new_pattern" name="icons[new][pattern]" class="input-text" size="20">
	</div>
	<div class="form-field">
		<label for="icon_new_url">{$lang.icon} {$lang.url}:</label>
		<input type="text" id="icon_new_url" name="icons[new][icon_url]" class="input-text" size="30">
	</div>
	<div class="buttons-container buttons-bg">
		<div class="float-left">
			{include file="buttons/save.tpl" but_name="dispatch[sdeep_iconization.update]" but_role="button_main"}
		</div>
	</div>
</form>
