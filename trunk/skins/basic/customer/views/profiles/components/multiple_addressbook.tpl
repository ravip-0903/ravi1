{* $Id: multiple_profiles.tpl 10336 2010-08-04 07:17:04Z klerik $ *}

{if !$hide_profile_name}
<div class="form-field">
	<label for="elm_profile_id" class="cm-required">{$lang.address_name}:</label>
	{if $action == "add_profile" || $no_edit != "Y"}
		{assign var="profile_name" value="- `$lang.new` -"}
	{else}
		{assign var="profile_name" value=$lang.main}
	{/if}

	<input type="hidden" name="user_data[profile_id]" value="{$profile_id|default:"0"}" />
	<input type="text"  id="elm_profile_id" name="user_data[profile_name]" size="32" value="{$user_data.profile_name|default:$profile_name}" class="input-text round_five profile_detail_field" />
</div>
	{if $user_data.profile_type == 'S'}
		<div class="form-field"><label for="makeitprimary">{$lang.makeitprimary}:</label><input type="checkbox" name="user_data[makeprimary]" value="P"></div>
	{/if}

{else}
	<input type="hidden" id="profile_name" name="user_data[profile_name]" value="{$user_data.profile_name|default:$lang.main}" />
{/if}
