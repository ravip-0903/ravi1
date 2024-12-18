{* $Id: meta.tpl 11825 2011-02-11 16:36:26Z zeke $ *}

{hook name="index:meta"}
{if $display_base_href}
<base href="{$config.current_location}/" />
{/if}
<meta http-equiv="Content-Type" content="text/html; charset={$smarty.const.CHARSET}" />
<meta http-equiv="Content-Language" content="{$smarty.const.CART_LANGUAGE|lower}" />
<meta name="description" content="{$meta_description|html_entity_decode|default:$location_data.meta_description}" />
<meta name="keywords" content="{$meta_keywords|default:$location_data.meta_keywords}" />
{/hook}

<meta property="og:site_name" content="shopclues"/>
<meta property="og:image" content="{$config.current_location}{$product.main_pair.detailed.http_image_path}"/>
<meta property="og:type" content="product"/>
<meta property="og:description" content="{$meta_description|default:$lang.home_meta_description}"/>

<meta property="og:title" content='{strip}
{if $page_title}
    {$page_title}
{else}
    {foreach from=$breadcrumbs item=i name="bkt"}
        {if !$smarty.foreach.bkt.first}{$i.title}{if !$smarty.foreach.bkt.last} :: {/if}{/if}
    {/foreach}
    {if !$skip_page_title}{if $breadcrumbs|count > 1} - {/if}{$lang.page_title_text}{/if}
{/if}
{/strip}'/>
