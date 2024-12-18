{* $Id: product_features_short_list.tpl 8327 2009-11-27 09:11:44Z angel $ *}

{if $features}
{strip}
{if !$no_container}<p class="features-list description">{/if}
	{foreach from=$features name=features_list item=feature}
	{if $feature.prefix}{$feature.prefix}{/if}
	{if $feature.feature_type == "D"}{$feature.value_int|date_format:"`$settings.Appearance.date_format`"}
	{elseif $feature.feature_type == "M"}
		{foreach from=$feature.variants item="v" name="ffev"}
		{$v.variant|default:$v.value}{if !$smarty.foreach.ffev.last}, {/if}
		{/foreach}
	{elseif $feature.feature_type == "S" || $feature.feature_type == "N" || $feature.feature_type == "E"}
		{$feature.variant|default:$feature.value}
	{elseif $feature.feature_type == "C"}
		{$feature.description}
	{elseif $feature.feature_type == "O"}
		{$feature.value_int}
	{else}
		{$feature.value}
	{/if}
	{if $feature.suffix}{$feature.suffix}{/if}
		{if !$smarty.foreach.features_list.last} / {/if}
	{/foreach}
{if !$no_container}</p>{/if}
{/strip}
{else}
{strip}
	<p class="features-list description">&nbsp;</p>
{/strip}
{/if}

{if $category_data.show_feature=='Y'}
{assign var="key_features" value=$product.product_id|get_products_feature}

    {if $key_features|count > "0"}
        <ul class="box_metacategory_features list_view_feature">
            {foreach from=$key_features item="key_feature"}
            <li>{$key_feature.variant|truncate:20:"...."}</li>				
            {/foreach}
        </ul>
    {/if}
{/if}
{if $controller=="products" && $mode=="search" && $config.key_feature_on_search}

{assign var="key_features" value=$product.product_id|get_products_feature}

    {if $key_features|count > "0"}
        <ul class="box_metacategory_features list_view_feature">
            {foreach from=$key_features item="key_feature"}
            <li>{$key_feature.variant|truncate:20:"...."}</li>				
            {/foreach}
        </ul>
    {/if}
 {/if} 
   
