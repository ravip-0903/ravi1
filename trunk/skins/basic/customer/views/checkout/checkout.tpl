{* $Id: checkout.tpl 12479 2011-05-18 08:54:10Z alexions $ *}

{script src="js/exceptions.js"}
{script src="js/cc_validator.js"}

<script type="text/javascript">
//<![CDATA[
	{if $edit_steps}
	{assign var="c_step" value=$edit_steps|implode:""}	
	$(document).ready(function() {$ldelim}
		jQuery.scrollToElm($('#{$c_step}'));
	{$rdelim});
	{/if}
//]]>
</script>

{if $settings.General.checkout_style == "multi_page"}
	{if $cart_products}
	{*include file="views/checkout/components/progressbar.tpl"*}
	{/if}

	{include file="views/checkout/components/checkout_steps.tpl"}
	{capture name="mainbox_title"}<span class="secure-page-title classic-checkout-title">{$lang.secure_checkout}</span>{/capture}
{else}
	{$smarty.capture.checkout_error_content}
	<a name="checkout_top"></a>
	{include file="views/checkout/components/checkout_steps.tpl"}

	{capture name="mainbox_title"}<span class="secure-page-title">{$lang.secure_checkout}</span>{/capture}
{/if}


<script type="text/javascript">
//<![CDATA[
{literal}
function checkout_radio(obj){
    if(obj == "Y"){
	document.getElementById('haveaccount').style.display = 'none';
	document.getElementById('anonymous_checkout').style.display = 'block';
	document.getElementById("a_checkout_login_radio1").checked = true;
	document.getElementById("soacf_elm_email").value = document.getElementById("login_checkout").value;
	document.getElementById('soacf_elm_email').focus();
    }else if(obj == "N"){
	document.getElementById('haveaccount').style.display = 'block';
	document.getElementById('anonymous_checkout').style.display = 'none';
	document.getElementById('checkout_passwd').style.display = 'block';
	document.getElementById("checkout_login_radio2").checked = true;
 	document.getElementById("login_checkout").value = document.getElementById("soacf_elm_email").value;
	document.getElementById('login_checkout').focus();
    }
}
{/literal}
//]]>
</script>
