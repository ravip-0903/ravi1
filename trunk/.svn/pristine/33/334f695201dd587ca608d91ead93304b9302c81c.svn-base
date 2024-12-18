{* $Id *}

{capture name="styles"} 

<!--<link href="{$config.skin_path}/css/ui/jqueryui.css" rel="stylesheet" type="text/css"/>
<link href="{$config.skin_path}/styles.css" rel="stylesheet" type="text/css" />
<link href="{$config.skin_path}/print.css" rel="stylesheet" media="print" type="text/css" />-->

    {if $config.mobile_perf_optimization && ($controller == "index")}
        <link href="{$config.ext_css_path|cat:'/css/ui/jqueryui.css'|cat:'?v='|cat:$config.cssversion}" rel="stylesheet" type="text/css"/>
        <link href="{$config.ext_css_path|cat:'/styles.css'|cat:'?v='|cat:$config.cssversion}" rel="stylesheet" type="text/css" />
        <link href="{$config.skin_path}/owl.carousel.css" rel="stylesheet">
        <link href="{$config.ext_css_path|cat:'/responsive_styles.css'|cat:'?v='|cat:$config.cssversion}" rel="stylesheet">
    {else}
        <link href="{$config.ext_css_path|cat:'/css/ui/jqueryui.css'|cat:'?v='|cat:$config.cssversion}" rel="stylesheet" type="text/css"/>
        <!--<link href="{$config.ext_css_path|cat:'/styles.css'|auto_version}" rel="stylesheet" type="text/css" /> -->
        <link href="{$config.ext_css_path|cat:'/styles.css'|cat:'?v='|cat:$config.cssversion}" rel="stylesheet" type="text/css" />
        <link href="{$config.ext_css_path|cat:'/print.css'|cat:'?v='|cat:$config.cssversion}" rel="stylesheet" media="print" type="text/css" />
        <link href="{$config.skin_path}/owl.carousel.css" rel="stylesheet">
        {if $config.isResponsive}
            <link href="{$config.ext_css_path|cat:'/responsive_styles.css'|cat:'?v='|cat:$config.cssversion}" rel="stylesheet">
        {/if}
    {/if}

    {if "TRANSLATION_MODE"|defined || "CUSTOMIZATION_MODE"|defined}
<link href="{$config.ext_css_path|cat:'/design_mode.css'|cat:'?v='|cat:$config.cssversion}" rel="stylesheet" type="text/css" />
{/if}
{*{if $include_dropdown}
<link href="{$config.ext_css_path}/dropdown.css" rel="stylesheet" type="text/css" />
{/if}*}
<!--[if lte IE 7]>
<link href="{$config.skin_path}/styles_ie.css" rel="stylesheet" type="text/css" />
<![endif]-->

{hook name="index:styles"}{/hook}

{/capture}
{join_css content=$smarty.capture.styles}

