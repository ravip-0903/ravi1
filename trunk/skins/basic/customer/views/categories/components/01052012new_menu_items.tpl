{* $Id: new_menu_items.tpl 9353 2010-05-04 06:10:09Z klerik $ *}
{strip}
{assign var="foreach_name" value="cats_$cid"}
{assign var="cat_count" value="1"}
{assign var="cat_limit" value="8"}
{foreach from=$items item="category" name=$foreach_name}
{if $cat_count <= $cat_limit }
<li {if $category.subcategories}class="first-level"{/if}>
	{if $category.subcategories}
		<ul>
			{include file="views/categories/components/new_menu_items.tpl" items=$category.subcategories separated=true submenu=true cid=$category.category_id}
		</ul>
	{/if}
	<a href="{"categories.view?category_id=`$category.category_id`"|fn_url}">{$category.category}</a>
</li>
{if $separated && !$smarty.foreach.$foreach_name.last && $cat_count!=8}
<li class="h-sep">&nbsp;</li>
{/if}
{assign var="cat_count" value=$cat_count+1}
{/if}
{/foreach}
{/strip}
