{* $Id: scripts.post.tpl 12724 2011-06-21 12:48:57Z zeke $ *}

{if $smarty.const.CONTROLLER != "checkout"}
{script src="addons/recurring_billing/js/func.js"}
<script type="text/javascript">

// Extend core function
fn_register_hooks('recurring_billing', ['check_exceptions']);

</script>
{/if}