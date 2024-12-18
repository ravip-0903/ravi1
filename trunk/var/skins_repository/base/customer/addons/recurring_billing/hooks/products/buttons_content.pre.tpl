{* $Id: buttons_content.pre.tpl 12724 2011-06-21 12:48:57Z zeke $ *}

{if $product.recurring_plans}

{include file="addons/recurring_billing/views/products/components/recurring_plans.tpl" hide_common_inputs=true}
{capture name="passed_to_buttons_content"}Y{/capture}

{/if}