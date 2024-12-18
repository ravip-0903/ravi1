{* $Id: quick_search.tpl 11228 2010-11-16 13:46:51Z alexions $ *}

<div class="qsearch-result border cm-popup-box" id="{$id}_result">

{if $patterns}
	<table class="quick-search sortable" width="100%">
	{foreach from=$patterns item="pattern"}
		<tr class="cm-search-row nowrap"><td text="{$pattern.full_text}">{if $pattern.url}<a href="{$pattern.url|unescape|urldecode}">{/if}{if $addons.quick_search.show_product_images == "Y" && !$settings.General.search_objects}{include file="common_templates/image.tpl" image_width="40" image_height="40" images=$pattern.image object_type="product" obj_id=$pattern.id show_thumbnail="Y"}&nbsp;{/if}{$pattern.text.highlighted|unescape|default:$pattern.text.value}{if $pattern.url}</a>{/if}</td></tr>
	{/foreach}
	</tbody>
	</table>
{else}
	<div class="center">{$lang.no_data}</div>
{/if}

<p class="right">
	<a class="extra-link" onclick="$('#{$id}_result').hide();">{$lang.close}</a>
</p>
<!--{$id}_result--></div>

<script type="text/javascript">
	var qs_min_length = {$addons.quick_search.min_length}
</script>