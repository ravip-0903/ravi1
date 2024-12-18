{assign var="product_count" value=$vendor_id|fn_product_count}

{if !isset($vendor_info)}
	{assign var="vendor_info" value=$vendor_id|fn_sdeep_get_vendor_info}
{/if}
{if !isset($size)}
	{assign var="size" value="10"}
{/if}

<div class="home_marc_co_img">
{if !$right}
	{include file="addons/sdeep/common_templates/vendor_icons.tpl" vendor_info=$vendor_info size=$size}
{/if}

{if $product_count < 25}
    
{assign var="url" value="companies.view?company_id=$vendor_id"|fn_url}
{assign var="new_url" value=$url|fn_new_url}

<a class="mconame" href="{$new_url}">
  {$vendor_info.company}
</a>
  
{else}
    
<a class="mconame" href="{"companies.view?company_id=$vendor_id"|fn_url}">
	{$vendor_info.company}
</a>
        
{/if}

{if $right}
	{include file="addons/sdeep/common_templates/vendor_icons.tpl" vendor_info=$vendor_info size=$size}
{/if}
</div>