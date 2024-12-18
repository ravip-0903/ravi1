{* $Id: sub_category.tpl 12544 2011-05-27 10:34:19Z bimib $ *}
{foreach from=$subcategories item="sub_cat" name="sb"}
<a href="{"categories.view?category_id=`$sub_cat.category_id`"|fn_url}{if isset($smarty.request.market_id)}?market_id={$smarty.request.market_id}{/if}" class="sub_category_nl_nrh {if $smarty.foreach.sb.iteration mod 3 == 0} margin_none{/if}">
	<div class="nrh_sub_category_name">{$sub_cat.category}</div>
    <div class="nhr_sub_category_pic">
		{if !empty($sub_cat.main_pair)}
        	<img src="http://cdn.shopclues.com{$sub_cat.main_pair.detailed.http_image_path}"  alt="{$sub_cat.category}" title="{$sub_cat.category}" />
        {else}
        	<img src="http://cdn.shopclues.com/images/banners/no_image.png"  alt="{$sub_cat.category}" title="{$sub_cat.category}" />
        {/if}
	</div>
</a>
{/foreach}
<pre>{*$subcategories|print_r*}</pre>