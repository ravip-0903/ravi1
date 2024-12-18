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