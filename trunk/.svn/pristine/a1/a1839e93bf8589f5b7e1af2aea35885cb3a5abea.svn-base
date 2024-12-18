{include file="letter_header.tpl"}
Hello {$order_info.firstname},<br />
<br />

{$lang.rate_product_message|replace:'[order_id]':$order_info.order_id}
<br />
{foreach from=$order_info.items item="oi"}
    {assign var="product_id" value=$oi.product_id}
    {assign var="pro_images" value=$product_id|fn_get_image_pairs:'product':'M'}
    <!--<a href="{"rate_product.manage?order_id=`$order_info.order_id`&product_id=$product_id"|fn_url:'C':'http'}">{$oi.product}</a>-->
	<br /><a href="{$config.domain_url}/index.php?dispatch=rate_product.manage&order_id={$order_info.order_id}&product_id={$product_id}">{$oi.product}</a>
{/foreach}
<br />	
{$lang.middle_review_product}
<br /></br />

{assign var="merchant_name" value=$order_info.company_id|fn_get_company_name}<br />
Order details:<br />
Order number: {$order_info.order_id}<br />
Order Date: {$order_info.timestamp|date_format:"%d %b %Y"}<br />
Products: <br />
{foreach from=$order_info.items item="oi"}
    {assign var="product_id" value=$oi.product_id}
    {assign var="pro_images" value=$product_id|fn_get_image_pairs:'product':'M'}
    {include file="common_templates/image.tpl" image_width="40" image_height="40" obj_id=$obj_id_prefix images=$pro_images object_type="product" show_thumbnail="Y"}
    <!--<a href="{"rate_product.manage?product_id=$product_id"|fn_url:'C':'http'}">{$oi.product}</a>-->
    <!--<a href="{"rate_product.manage?order_id=`$order_info.order_id`&product_id=$product_id"|fn_url:'C':'http'}">{$oi.product}</a>-->
	<br /><a href="{$config.domain_url}/index.php?dispatch=rate_product.manage&order_id={$order_info.order_id}&product_id={$product_id}">{$oi.product}</a>

{/foreach}<br />
Merchant:  {$merchant_name}<br />

{$lang.rate_product_footer_message|replace:'[domain_url]':$config.domain_url|replace:'[order_id]':$order_info.order_id|replace:'[product_id]':$product_id}

{include file="letter_footer.tpl"}
