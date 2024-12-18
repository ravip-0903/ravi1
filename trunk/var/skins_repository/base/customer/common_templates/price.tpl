{* $Id: price.tpl 10845 2010-10-11 07:33:48Z andyye $ *}
{strip}
{if $settings.General.alternative_currency == "Y"}
	{$value|format_price:$currencies.$primary_currency:$span_id:$class:false}{if $secondary_currency != $primary_currency}&nbsp;{if $class}<span class="{$class}">{/if}({if $class}</span>{/if}{$value|format_price:$currencies.$secondary_currency:$span_id:$class:true:$is_integer}{if $class}<span class="{$class}">{/if}){if $class}</span>{/if}{/if}
{else}
	{$value|format_price:$currencies.$secondary_currency:$span_id:$class:true}
{/if}
{/strip}