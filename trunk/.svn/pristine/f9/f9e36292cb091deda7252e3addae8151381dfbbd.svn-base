{* $Id: coming_soon_notice.tpl 9828 2010-06-22 08:00:34Z alexions $ *}

<div class="product-coming-soon wrapped"{if $block_width} style="clear:both; font: 12px/14px 'Trebuchet MS', Arial, Helvetica, sans-serif; color: #636566; width: 380px; float:left; margin-top:7px;"{/if}>
	{assign var="date" value=$avail_date|date_format:$settings.Appearance.date_format}
	{if $add_to_cart == "N"}{$lang.product_coming_soon|replace:"[avail_date]":$date}{else}{$lang.product_coming_soon_add|replace:"[avail_date]":$date}{/if}
</div>