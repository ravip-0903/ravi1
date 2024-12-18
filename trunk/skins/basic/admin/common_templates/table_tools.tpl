{* $Id: table_tools.tpl 10965 2010-10-20 15:36:01Z 2tl $ *}

{if $elements_count != 1}

<div class="table-tools">
	<a href="{$href}" name="check_all" class="cm-check-items cm-on underlined">{$lang.select_all}</a>|
	<a href="{$href}" name="check_all" class="cm-check-items cm-off underlined">{$lang.unselect_all}</a>
</div>

{/if}
