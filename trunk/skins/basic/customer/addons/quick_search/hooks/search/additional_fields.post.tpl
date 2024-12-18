{* $Id *}

{if !$smarty.capture.quick_search}
	{include file="addons/quick_search/views/quick_search/components/quick_search.tpl" id="quick_search"}
{/if}
{capture name="quick_search"}Y{/capture}