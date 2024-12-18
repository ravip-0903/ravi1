
{if !isset($vendor_info)}
	{assign var="vendor_info" value=$vendor_id|fn_sdeep_get_vendor_info}
{/if}
{if !isset($size)}
	{assign var="size" value="10"}
{/if}

{assign var="is_trm" value=$vendor_info.company_id|fn_sdeep_is_trm}
{if $is_trm}
<a class="trm_clk" ><img src="{$addons.sdeep.trm_icon_url}" width="30"/></a>
{/if}

{*{if $vendor_info.icon_url}
<a class="trm_clk" href="{$addons.sdeep.trm_explanation_url}"><img src="{$vendor_info.icon_url}" width="{$size}%"/></a>
{/if}*}
<!-- href="{$addons.sdeep.trm_explanation_url}"-->