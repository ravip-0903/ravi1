
{if !isset($vendor_info)}
	{assign var="vendor_info" value=$vendor_id|fn_sdeep_get_vendor_info}
{/if}
{if !isset($size)}
	{assign var="size" value="10"}
{/if}

{assign var="is_trm" value=$vendor_info.company_id|fn_sdeep_is_trm}
{if $is_trm}

<div class="ml_merchantinfo_toprated">
        <div class="ml_merchantinfo_toprated_details">
          <h1 class="ml_merchantinfo_toprated_details_heading">Top Rated Merchent</h1>
          <div class="ml_merchantinfo_toprated_details_text">
          {$lang.top_rated_merchant_text}</div>
        </div>
        <div class="ml_merchantinfo_toprated_icon"><a href="{$addons.sdeep.trm_explanation_url}"><img src="{$addons.sdeep.trm_icon_url}" width="92" height="92" /></a></div>
      </div>
{/if}

{*{if $vendor_info.icon_url}
<a href="{$addons.sdeep.trm_explanation_url}"><img src="{$vendor_info.icon_url}" width="{$size}%"  /></a>
{/if}*}
