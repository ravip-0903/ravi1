{* $Id: inventory.post.tpl 11501 2010-12-29 09:23:57Z klerik $ *}

<li>{$lang.configurable}:&nbsp;{if $product_stats.configurable}<a href="{"products.manage?configurable=C"|fn_url}">{$product_stats.configurable}</a>{else}0{/if}</li>