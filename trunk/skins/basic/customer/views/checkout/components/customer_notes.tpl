{* $Id: customer_notes.tpl 9403 2010-05-07 13:11:06Z klerik $ *}

{hook name="checkout:notes"}
<div id="customer_notes">
<p style="font:bold 9px verdana;">{$lang.text_customer_notes}:</p>
<textarea class="input-textarea checkout-textarea float_left" name="customer_notes" cols="60" rows="5"></textarea>
</div>
{/hook}
