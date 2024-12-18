{if !isset($vendor_info)}
	{assign var="vendor_info" value=$vendor_id|fn_sdeep_get_vendor_info}
{/if}
{if !isset($size)}
	{assign var="size" value="10"}
{/if}
{include file="addons/sdeep/common_templates/vendor_icons.tpl" vendor_info=$vendor_info size=$size}
<a href="{"companies.view?company_id=$vendor_id"|fn_url}">
	{$vendor_info.company}
</a>
