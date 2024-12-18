{* $Id: rss_feed.tpl 11191 2010-11-11 11:56:01Z klerik $ *}
{** block-description:rss_feed **}

{if $items}
<ul class="site-rss">
{foreach from=$items[0] item="item" name="site_rss"}
{if is_string($item.title)}
	<li>
		{if is_string($item.pubDate)}
		<strong>{$item.pubDate|date_format:$settings.Appearance.date_format}</strong>
		{/if}
		<a href="{$item.link}">{$item.title}</a>
	</li>
	{if !$smarty.foreach.site_rss.last}
	<li class="delim"></li>
	{/if}
{/if}
{/foreach}
</ul>

<p class="right">
	<a href="{$items[1]}" class="extra-link">{$lang.view_all}</a> | <a href="{$items[2]}" class="extra-link">{$lang.rss_feed}</a>
</p>
{/if}
