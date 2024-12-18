{* $Id$ *}
{** block-description:vendors **}

{if $items.companies}
	<ul>
	{foreach from=$items.companies item=v key=k}
		{* [andyye]: rating was added *}
		{assign var="rating" value=$k|fn_sdeep_get_rating}
		<li>{include file="addons/sdeep/common_templates/vendor_icons.tpl" vendor_id=$k size="5"}<a href="{"companies.view?company_id=`$k`"|fn_url}">{$v}{if $rating} ({$rating}){/if}</a></li>
		{* [/andyye] *}
	{/foreach}
	</ul>

	{if $items.companies|count < $items.count}
		<p class="right">
			<a class="extra-link" href="{"companies.catalog"|fn_url}">{$lang.view_all}</a>
		</p>
	{/if}
{/if}
