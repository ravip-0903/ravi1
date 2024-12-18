<div style="width:170px; float:left;"> 
{$lang.nrh_left_block}                                                    
</div>
<div class="nrh_category_wid81" style="">
<h1 class="cat_title nrh_cate_title">{$market.name}</h1>

<p>
{$market.description|unescape}
</p>
{foreach from=$categories item="sub_cat" name="sb"}

<a href="{"categories.view?category_id=`$sub_cat.pcategory_id`"|fn_url}?market_id={$smarty.request.market_id}" class="category_nl_nrh {if $smarty.foreach.sb.iteration mod 3 == 0} margin_none{/if}">
	<div class="nrh_category_name">{$sub_cat.category}</div>
    <div class="nhr_category_pic">
		{if !empty($sub_cat.main_pair)}
        	<img src="http://cdn.shopclues.com{$sub_cat.main_pair.detailed.http_image_path}"  alt="{$sub_cat.category}" title="{$sub_cat.category}" />
        {else}
        	<img src="http://cdn.shopclues.com/images/banners/no_image.png"  alt="{$sub_cat.category}" title="{$sub_cat.category}" />
        {/if}
	</div>
</a>
{/foreach}

</div>
