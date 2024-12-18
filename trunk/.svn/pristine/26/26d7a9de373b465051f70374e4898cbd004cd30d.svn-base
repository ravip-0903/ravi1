{* $Id: image_verification.tpl 12815 2011-06-29 10:55:13Z alexions $ *}

{if ""|fn_needs_image_verification == true}
	{assign var="is" value="Image_verification"|fn_get_settings}
	<div class="clearboth"></div>
	<p{if $align} class="{$align}"{/if} style="font:11px verdana; padding:0px;">{$lang.image_verification_body}</p>
	{assign var="id_uniqid" value=$id|uniqid}
	{if $sidebox}
		<p style="margin:0px; padding:0px; border:1px solid #000;"><img id="verification_image_{$id}" class="image-captcha valign" src="{"image.captcha?verification_id=`$SESS_ID`:`$id`&amp;`$id_uniqid`&amp;"|fn_url:'C':'rel':'&amp;'}" alt="" onclick="this.src += 'reload' ;" width="{$is.width}" height="{$is.height}" /></p>
	{/if}

        <p style="margin:0px; padding:0px;"><input class="captcha-input-text valign cm-autocomplete-off" type="text" name="verification_answer" id="verification_answer"  value= "" />
	{if !$sidebox}
		<img id="verification_image_{$id}" class="image-captcha valign" src="{"image.captcha?verification_id=`$SESS_ID`:`$id`&amp;`$id_uniqid`&amp;"|fn_url:'C':'rel':'&amp;'}" alt="" onclick="this.src += 'reload' ;"  width="{$is.width}" height="{$is.height}" />
	{/if}</p>
{/if}

{if $destination == "step_four" && $smarty.session.auth.user_id != "0"}
    {assign var="is" value="Image_verification"|fn_get_settings}
	<div class="clearboth"></div>
	<p{if $align} class="{$align}"{/if} style="font:11px verdana; padding:0px;">{$lang.image_verification_body}</p>
	{assign var="id_uniqid" value=$id|uniqid}
	{if $sidebox}
		<p style="margin:0px; padding:0px;"><img id="verification_image_{$id}" class="image-captcha valign" src="{"image.captcha?verification_id=`$SESS_ID`:`$id`&amp;`$id_uniqid`&amp;"|fn_url:'C':'rel':'&amp;'}" alt="" onclick="this.src += 'reload' ;" width="{$is.width}" height="{$is.height}" /></p>
	{/if}

	<p style="margin:0px; padding:0px;"><input class="captcha-input-text valign cm-autocomplete-off" type="text" name="verification_answer"  id="verification_answer" value= "" />
	{if !$sidebox}
		<img id="verification_image_{$id}" class="image-captcha valign" src="{"image.captcha?verification_id=`$SESS_ID`:`$id`&amp;`$id_uniqid`&amp;"|fn_url:'C':'rel':'&amp;'}" alt="" onclick="this.src += 'reload' ;"  width="{$is.width}" height="{$is.height}" />
	{/if}</p>
{/if}
