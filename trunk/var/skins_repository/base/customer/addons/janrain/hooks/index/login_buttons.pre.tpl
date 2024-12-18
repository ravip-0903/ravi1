{* $Id: login_buttons.pre.tpl 12602 2011-06-02 10:49:53Z bimib $ *}
{if $addons.janrain.appdomain}
<div class="float-right">
	{assign var="escaped_current_location" value=$config.current_location|escape:url}
	{assign var="escaped_return_url" value=$smarty.request.return_url|default:$config.current_url|escape:url|escape:url}
	<div class="janrain_wrapper">{include file="buttons/button.tpl" but_meta="rpxnow janrain-button" but_href="`$addons.janrain.appdomain`/openid/v2/signin?token_url=`$escaped_current_location`%2Findex.php%3Fdispatch%3Dauth.login%26return_url=`$escaped_return_url`" but_text=$lang.social_login}</div>
</div>
{/if}
