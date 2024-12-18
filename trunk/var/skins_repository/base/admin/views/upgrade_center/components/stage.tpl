{* $Id: stage.tpl 11717 2011-01-27 12:33:35Z 2tl $ *}

{if $stages}
<div><p>
{$lang.stage} {$stages.stage_number} {$lang.of} {$stages.total}. {$lang.processing} {$stages.stage}.
</p></div>
{/if}
