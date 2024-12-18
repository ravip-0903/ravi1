{* $Id: sidebox_general.tpl 11786 2011-02-08 09:44:48Z 2tl $ *}

<div class="{$sidebox_wrapper|default:"sidebox-wrapper"} {if $hide_wrapper}hidden cm-hidden-wrapper{/if}">
	<h3 id="mob_filters_cat_heading" class="sidebox-title{if $header_class} {$header_class}{/if}"><span>{$title}</span></h3>
    <!--<span class="stars_icon"></span>-->
    <div class="clearboth"></div>
	<div class="sidebox-body">{$content|default:"&nbsp;"}</div>
	<div class="sidebox-bottom"><span>&nbsp;</span></div>
</div>