{* $Id: myaccount.tpl 12544 2011-05-27 10:34:19Z bimib $ *}

{if !$nothing_extra}
	{include file="common_templates/subheader.tpl" title=$lang.updatepassword}
{/if}

{if $smarty.request.redirect != 'mail'}
    
<form name="password_form" action="{""|fn_url}" method="post">
    <input type="hidden" name="token" value="{$smarty.session.form_token_value}"/>
<div class="form-field">
	<label for="passwordc" class="cm-required">{$lang.current_password}:</label>
	<input type="password" id="passwordc" name="passwordc" size="32" maxlength="32" class="input-text cm-autocomplete-off round_five profile_detail_field" />
</div>
        
<div class="form-field">
	<label for="password1" class="cm-required cm-password">{$lang.new_password}:</label>
	<input type="password" id="password1" name="password1" size="32" maxlength="32" class="input-text cm-autocomplete-off round_five profile_detail_field" />
</div>

<div class="form-field">
	<label for="password2" class="cm-required cm-password">{$lang.confirm_password}:</label>
	<input type="password" id="password2" name="password2" size="32" maxlength="32" class="input-text cm-autocomplete-off round_five profile_detail_field" />
</div>
<div class="form-field"> </div><div class="form-field"> </div>
<div style="float:left;margin-left:450px" >
      {include file="buttons/save.tpl" but_name="dispatch[profiles.updatepassword]" but_id="save_profile_but" }
</div>

</form>

{else}
    
    <form name="password_form" action="{""|fn_url}" method="post">
    <input type="hidden" name="mail" id="mail" value="mail" />
     <input type="hidden" name="token" value="{$smarty.session.form_token_value}"/>
    <div class="form-field">
	<label for="password3" class="cm-required cm-password">{$lang.new_password}:</label>
	<input type="password" id="password3" name="password3" size="32" maxlength="32" class="input-text cm-autocomplete-off round_five profile_detail_field" />
</div>

<div class="form-field">
	<label for="password4" class="cm-required cm-password">{$lang.confirm_password}:</label>
	<input type="password" id="password4" name="password4" size="32" maxlength="32" class="input-text cm-autocomplete-off round_five profile_detail_field" />
</div>
<div class="form-field"> </div><div class="form-field"> </div>
<div style="float:left;margin-left:450px" >
      {include file="buttons/save.tpl" but_name="dispatch[profiles.updatepassword]" but_id="save_profile_but" }
</div>

    </form>
    
    {/if}
