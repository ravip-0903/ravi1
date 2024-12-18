{section name="full_star" loop=$stars.full}
<img src="images/monalisa/icon_starbig.png" {if $height!=''} height="{$height}" {/if} {if $width!=''} width="{$width}"{/if} />
{/section}

{if $stars.part}<img src="images/monalisa/icon_starbig_half.png" {if $height!=''} height="{$height}" {/if}
{if $width!=''} width="{$width}"{/if} />{/if}

{section name="full_star" loop=$stars.empty}
	<img src="images/monalisa/icon_starbig_unselected.png" {if $height!=''} height="{$height}" {/if} {if $width!=''} width="{$width}"{/if} />
{/section}
    
    <!--{if $controller=='companies' && $mode=='view'}
    {assign var="vendor_reviews_count" value=$company_data.company_id|fn_sdeep_get_vendor_detailed_rating}
    <br />
    <span class="vendor_review_count">({$vendor_reviews_count.count} review)</span>
    {/if}-->
