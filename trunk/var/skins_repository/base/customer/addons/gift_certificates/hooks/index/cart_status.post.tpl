{* $Id: cart_status.post.tpl 11427 2010-12-20 11:45:54Z alexions $ *}

{if $smarty.session.cart.gift_certificates}
	{if $smarty.session.cart.products}
		<li class="delim">&nbsp;</li>
	{/if}
	
	{foreach from=$smarty.session.cart.gift_certificates item="gift" key="gift_key" name="f_gift_certificates"}
	<li class="clear">
		{assign var="redirect_url" value="delete.from_status&cart_id=`$gift_key`"|urlencode}
		<a href="{"gift_certificates.update?gift_cert_id=`$gift_key`"|fn_url}">{$lang.gift_certificate}</a>{if !"CHECKOUT"|defined || $force_items_deletion}{include file="buttons/button.tpl" but_href="gift_certificates.delete?gift_cert_id=`$gift_key`&amp;redirect_mode=`$redirect_url`" but_meta="cm-ajax" but_rev="cart_status" but_role="delete" but_name="delete_cart_item"}{/if}
		<p>
			{include file="common_templates/price.tpl" value=$gift.display_subtotal span_id="subtotal_gc_`$gift_key`" class="none"}
		</p>
	</li>
	
	{if !$smarty.foreach.f_gift_certificates.last}
		<li class="delim">&nbsp;</li>
	{/if}
	{/foreach}
{/if}