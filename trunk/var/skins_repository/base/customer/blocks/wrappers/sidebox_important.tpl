{* $Id: sidebox_important.tpl 11191 2010-11-11 11:56:01Z klerik $ *}

<div class="sidebox-categories-wrapper {if $hide_wrapper}hidden cm-hidden-wrapper{/if}">
	<h3 class="sidebox-title{if $header_class} {$header_class}{/if}"><span>{$title}</span></h3>
	<div class="sidebox-body">{$content|default:"&nbsp;"}</div>
	<div class="sidebox-bottom"><span>&nbsp;</span></div>
</div>