{* $Id$ *}

{hook name="companies:view"}

{assign var="obj_id" value=$company_data.company_id}
{assign var="obj_id_prefix" value="`$obj_prefix``$obj_id`"}
{include file="common_templates/company_data.tpl" company=$company_data show_name=true show_descr=true show_rating=true show_logo=true hide_links=true}

<div class="clear product-main-info">
	<div class="float-left image-border center">
		{assign var="capture_name" value="logo_`$obj_id`"}
		{$smarty.capture.$capture_name}
	</div>

	<div id="block_company_{$company_data.company_id}" class="product-info">
		{* [andyye]: modified code below *}
		<h1 class="mainbox-title">{$company_data.company}{include file="addons/sdeep/common_templates/vendor_icons.tpl" vendor_info=$company_data}</h1>        
		{assign var="rating" value="rating_`$obj_id`"}{$smarty.capture.$rating}
		&nbsp;
        
        {* [andyye] *}
		{assign var="rating" value=$company_data.company_id|fn_sdeep_get_rating}
		<div id="company_rating" class="form-field product-list-field">
			<label>{$lang.sdeep_rating}:</label>
			{if $rating}
				<div class="right">{include file="addons/sdeep/components/stars.tpl" stars=$rating|fn_sdeep_get_stars}</div>
			{else}N/A{/if}
		</div>
		{* [/andyye] *}
        
		<hr class="dashed clear-both" />

		<p class="product-descr">
        {$company_data.company_description|unescape}
        </p>
        {if $company_data.phone}
		
		<div id="company_phone" class="form-field product-list-field">
			<label>{$lang.phone}:</label>
			{$company_data.phone}
		</div>
		{/if}

		{if $company_data.url}
		<div id="company_website" class="form-field product-list-field">
			<label>{$lang.website}:</label>
			<a href="{$company_data.url}">{$company_data.url}</a>
		</div>
		{/if}

		<div id="company_products">
			<a class="vendor_products" href="{"products.search?company_id=`$company_data.company_id`&search_performed=Y"|fn_url}">{$lang.view_vendor_products}</a>
		</div>

	</div>
</div>
{hook name="companies:feedback"}
{/hook}
{/hook}


