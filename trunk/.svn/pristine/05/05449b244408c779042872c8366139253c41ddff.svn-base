{* $Id: exception.tpl 12726 2011-06-21 13:05:52Z bimib $ *}

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="{$smarty.const.CART_LANGUAGE|lower}">
<head>
<title>{$page_title}</title>

{include file="meta.tpl"}
<link href="{$images_dir}/icons/favicon.ico" rel="shortcut icon" />
{include file="common_templates/styles.tpl"}
{include file="common_templates/scripts.tpl"}
</head>

<body>
<div class="helper-container">
	<div id="container" class="container-long exception">
	<a name="top"></a>

	<div id="content" class="clear">
		<div class="central-column-long">
			<div class="central-content">

				<div class="exception-body">

				<h1>{$exception_status}</h1>

				<h2>
					{if $exception_status == "403"}
						{$lang.access_denied}
					{elseif $exception_status == "404"}
						{$lang.page_not_found}
					{/if}
				</h2>

				<div class="exception-content">
					{if $smarty.const.HTTPS === true}
						{assign var="return_url" value=$config.https_location|fn_url}
					{else}
						{assign var="return_url" value=$config.http_location|fn_url}
					{/if}
					{if $exception_status == "403"}
						<h3>{$lang.access_denied_text}</h3>
					{elseif $exception_status == "404"}
						<h3>{$lang.page_not_found_text}</h3>
					{/if}
					
					<ul class="exception-menu">
						<li id="go_back"><a onclick="history.go(-1);">{$lang.go_back}</a></li>
						<li><a href="{$return_url}">{$lang.go_to_the_homepage}</a></li>
					</ul>

					<script type="text/javascript">
					//<![CDATA[
					{literal}
					 jQuery.each(jQuery.browser, function(i, val) {
						if ((i == 'opera') && (val == true)) {
							if (history.length == 0) {
								$('#go_back').hide();
							}
						} else {
							if (history.length == 1) {
								$('#go_back').hide();
							}
						}
					});
					{/literal}
					//]]>
					</script>
				</div>

					<div class="exception-logo">
						<a href="{$return_url}"><img src="{$images_dir}/{$manifest.Customer_logo.filename}" width="{$manifest.Customer_logo.width}" height="{$manifest.Customer_logo.height}" border="0" alt="{$manifest.Customer_logo.alt}" /></a>
					</div>

				</div>

			</div>
		</div>
	</div>

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
</div>
</body>

</html>
