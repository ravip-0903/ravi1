{* $Id: verify.tpl 10402 2010-08-12 08:18:09Z klerik $ *}

<div class="clear">
	{*include file="addons/my_changes/views/clues_bucks/components/clues_bucks_verify.tpl"*}
	
	<div class="form_redeemgiftcertificate">
	<form name="clues_bucks_verification_form" action="{""|fn_url}">
	<div class="form_redeemgiftcertificate_fieldname">
	<label for="id_email_id" class="cm-required">{$lang.clues_bucks_verification}</label>
	</div>

	{strip}
	<div class="form_redeemgiftcertificate_field">
	<input type="email" name="email_id" id="id_email_id" value="{$lang.enter_email_id|escape:html}" 
	class="form_redeemgiftcertificate_field_textbox" onclick="if(this.value=='{$lang.enter_email_id|escape:html}')this.value='';" 
	onblur="if(this.value=='') this.value='{$lang.enter_email_id|escape:html}';" />
	{include file="buttons/go.tpl" but_name="clues_bucks.verify" alt=$lang.go}
	</div>
	{/strip}
	</form>
	</div>
	
</div>

<pre>{*$cb_remain|print_r*}</pre>

{if $cb.email}
  
{** /clues_bucks section **}

<div>
	<h5 style="margin-top:30px; width:200px; margin-left:5px; font:17px times new roman; background:url(cdn.shopclues.com/images/tab_left_active.png) 
	no-repeat scroll left top #DFE2E5; border-top-left-radius:5px; border-top-right-radius:5px; padding:7px 10px;">
	{$lang.clues_bucks_info} :
	<strong> {$cb_remain|default:0}</strong>
	</h5>
	
	<p>{$lang.enterd_email}: <strong>{$smarty.request.email_id} </strong></p>
	<div>
    <div style="border-bottom:1px solid #cecece; width:100%; padding:10px;">
    <strong>{$lang.clues_bucks_msg}</strong>
	<!--<strong>{$cb_remain|default:0}</strong>-->
    </div>
			
</div>
{else}
	<div class="center strong">{$lang.invalid_email_id}</div>
{/if}
{** /clues_bucks section **}

