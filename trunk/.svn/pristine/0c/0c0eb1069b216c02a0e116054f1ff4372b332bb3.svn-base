{* $Id: average_rating.tpl 5626 2008-07-21 07:47:04Z brook $ *}

{assign var="average_rating" value=$object_id|fn_get_average_rating:$object_type}
{if $average_rating}
{include file="addons/discussion/views/discussion/components/stars.tpl" stars=$average_rating|fn_get_discussion_rating}
{/if}
<!--commented by ankur for the reviews section design changes-->
<!--{if $location=="reviews"}<div class="clearboth"></div>{/if}-->

{if $controller != 'rate_product'}
{assign var="disc_count" value=$object_id|fn_get_discussion_count:$object_type}
{assign var="avg_rating_cnt" value=$object_id|fn_get_average_rating_cnt:$object_type}
<div style="float:left;" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
    {if $average_rating}
    <meta itemprop="ratingValue" content={$average_rating} />
    {$avg_rating_cnt} Ratings
    {/if}

    {if $object_type=='P' or $object_type=='M'}

    {if $disc_count}
    {if $avg_rating_cnt>0} |{/if}
    <a {if $mode=='catalog'} href="{"index.php?dispatch=companies.view&company_id=`$object_id`"|fn_url}#review" {elseif $controller=='product_quick_view'}style='color:black;cursor:alias' {else} href="{$smarty.server.REQUEST_URI|fn_url}{if $smarty.request.page}?page={$smarty.request.page}{/if}#Reviews" {/if}>(<span itemprop="reviewCount">{$disc_count} </span>{$lang.customer_reviews})</a>


    {/if}

    {/if}
</div>
{/if}
{*/if*}