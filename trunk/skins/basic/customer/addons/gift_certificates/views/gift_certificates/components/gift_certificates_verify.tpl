{* $Id: gift_certificates_verify.tpl 9517 2010-05-19 14:02:43Z klerik $ *}


<div class="form_redeemgiftcertificate">
<form name="gift_certificate_verification_form" action="{""|fn_url}">
<div class="form_redeemgiftcertificate_fieldname">
<label for="id_verify_code" class="cm-required">{$lang.certificate_verification}</label>
</div>

{strip}
<div class="form_redeemgiftcertificate_field">
<input type="text" name="verify_code" id="id_verify_code" value="{$lang.enter_code|escape:html}" class="form_redeemgiftcertificate_field_textbox" onclick="if(this.value=='{$lang.enter_code|escape:html}')this.value='';" onblur="if(this.value=='') this.value='{$lang.enter_code|escape:html}';" />
{include file="buttons/go.tpl" but_name="gift_certificates.verify" alt=$lang.go}
</div>
{/strip}
</form>
</div>