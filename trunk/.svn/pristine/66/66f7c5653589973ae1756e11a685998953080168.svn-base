{* $Id: categories_tree.tpl 12684 2011-06-14 15:19:15Z subkey $ *}

{assign var="cc_id" value=$smarty.request.category_id|default:$smarty.session.current_category_id}

{foreach from=$categories item=category key=cat_key name="categories"}

{if $category.parent_id == $cc_id}
	{if $category.level == "0"}
		{if $ul_subcategories == "started"}
			</ul>
			{assign var="ul_subcategories" value=""}
		{/if}
		<ul class="menu-root-categories tree">
			<li><a href="{"categories.view?category_id=`$category.category_id`"|fn_url}" {if $category.category_id == $cc_id} class="active"{/if}>{$category.category}</a></li>
		</ul>
	{else}
		{if $ul_subcategories != "started"}
			<ul class="nav_metacategory_links">
				{assign var="ul_subcategories" value="started"}
		{/if}
		<li>
        <a href="{"categories.view?category_id=`$category.category_id`"|fn_url}"{if $category.category_id == $cc_id} class="active"{/if}>{$category.category}
        </a>
        </li>
	{/if}
	{if $smarty.foreach.categories.last && $ul_subcategories == "started"}
		</ul>
	{/if}
{/if}
{/foreach}

