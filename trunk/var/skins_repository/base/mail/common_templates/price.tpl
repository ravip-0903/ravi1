{* $Id: price.tpl 10656 2010-09-16 11:33:58Z andyye $ *}
{strip}
{if $settings.General.alternative_currency == "Y"}
	{$value|format_price:$currencies.$primary_currency:$span_id:$class}{if $secondary_currency != $primary_currency}&nbsp;({$value|format_price:$currencies.$secondary_currency:$span_id:$class:true}){/if}
{else}
	{$value|format_price:$currencies.$secondary_currency:$span_id:$class:true}
{/if}
{/strip}