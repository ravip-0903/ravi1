{* $Id: index.tpl 12625 2011-06-03 13:57:09Z alexions $ *}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="{$smarty.const.CART_LANGUAGE|lower}" xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
{strip}
<title>
{if $page_title}
	{$page_title|escape:"html"}
{else}
	{foreach from=$breadcrumbs item=i name="bkt"}
		{if !$smarty.foreach.bkt.first}{$i.title|unescape|strip_tags|escape:"html"}{if !$smarty.foreach.bkt.last} :: {/if}{/if}
	{/foreach}
	{if !$skip_page_title}{if $breadcrumbs|count > 1} - {/if}{$location_data.page_title|escape:"html"}{/if}
{/if}
</title>
{/strip}
{include file="meta.tpl"}
<link href="{$images_dir}/icons/favicon.ico" rel="shortcut icon" />
{include file="common_templates/styles.tpl" include_dropdown=true}
{include file="common_templates/scripts.tpl"}
</head>

<body>
<div class="promotional-banner">

	<div id="promotional-banner-text" class="promotional-banner-text">This is the very small promotional banner</div>

</div>

{if "SKINS_PANEL"|defined}
{include file="demo_skin_selector.tpl"}
{/if}
<div class="helper-container">
	<a name="top"></a>
	{include file="common_templates/loading_box.tpl"}

	{include file="main.tpl"}

	{if "TRANSLATION_MODE"|defined}
		{include file="common_templates/translate_box.tpl"}
	{/if}
	{if "CUSTOMIZATION_MODE"|defined}
		{include file="common_templates/template_editor.tpl"}
	{/if}
	{if "CUSTOMIZATION_MODE"|defined || "TRANSLATION_MODE"|defined}
		{include file="common_templates/design_mode_panel.tpl"}
	{/if}
</div>

{hook name="index:footer"}{/hook}

{script src="js/jquery.min.js"}
{script src="js/jquery.easing.1.2.js"}
{script src="js/jquery.anythingslider.js"}

</body>

</html>
