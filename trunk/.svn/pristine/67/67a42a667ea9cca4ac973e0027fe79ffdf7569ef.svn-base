{* $Id: cart_content.tpl 12479 2011-05-18 08:54:10Z alexions $ *}

{assign var="result_ids" value="cart_items,checkout_totals,checkout_steps,cart_status,checkout_cart"}

<form name="checkout_form" class="cm-check-changes" action="{""|fn_url}" method="post" enctype="multipart/form-data">
<input type="hidden" name="redirect_mode" value="cart" />
<input type="hidden" name="result_ids" value="{$result_ids}" />
{if $config.xbuy_now_popup == TRUE}
	{include file="views/checkout/components/xcart_items.tpl" disable_ids="button_cart"}
{else}
	{include file="views/checkout/components/cart_items.tpl" disable_ids="button_cart"}
{/if}

<div class="cart-buttons clear {if $config.xbuy_now_popup}checkoutPopupTotal{/if}">
	<div class="float-left">{include file="buttons/clear_cart.tpl" but_href="checkout.clear" but_role="text" but_meta="cm-confirm"}</div>
	<div class="float-right">
            {include file="buttons/update_cart.tpl" but_id="button_cart" but_name="dispatch[checkout.update]"}
        </div>
</div>

</form>
            
{include file="views/checkout/components/checkout_totals_custom.tpl" location="cart_content"}

<div class="clearboth"></div>
<div class="buttons-container clear">
	<div id="cntinue_shpping"class="float-left">
		{include file="buttons/continue_shopping.tpl" but_href=$continue_url|default:$index_script}</div>
                <form action="{""|fn_url}" method="post" id="user_message_thread" style="float: left;" >
    <div style="float: left; margin-left:10px; display:none" id="pincode_avail">
    
        <input type="tel" class="cm-required cm-integer" style="border-radius: 5px 5px 5px 5px; width:135px; float:left; border-top-right-radius: 0; border-bottom-right-radius: 0; border: 1px solid rgb(204, 204, 204); padding: 6px;" name="pincode" id="pincode" onchange="pincode_validate()" placeholder="Enter Pincode" maxlength="6"> 
    <span class="button-submit" style="float:left; margin-left:0;">
    <input class="box_functions_button" id="check_but" type="submit" name="dispatch[checkout.validate_pin_cart]" value="Check" style=" border-top-left-radius: 0; border-bottom-left-radius: 0; cursor:pointer;">
</span>
              
</div>   

            
        <div id="change_pin" class="form-field pincode_prd_page_blk" style="display:none; float: left; margin-bottom: 3px; margin-left: 10px; margin-top: 2px;">
            <span>{$lang.shipping_to}:{$smarty.cookies.pincode}</span><a class="ahover_nl pincode_change" onclick="change_pincode();"> {$lang.change_pin}</a>
        </div>
        
        
        {if $invalid_pin == '-1'}
            <div style="color: rgb(255, 0, 0); float: left; clear: both; font-size: 12px; margin: 5px 0px 10px 20px;">
            {$lang.invalid_pincode}
            </div>
        {/if}
</form>
        
	<div class="float-right right" id="cart_place_order">

{literal}
<script type="text/javascript">
   // $('#pincode_avail').hide();
    var cookie_pincode = {/literal}'{$smarty.cookies.pincode}';{literal}
   // alert(cookie_pincode);
    if(cookie_pincode == ''){
        $('#pincode_avail').show();      
    }
    else{
        
        $('#change_pin').show();
    }
    
    var invalid_pin = {/literal}'{$invalid_pin}';{literal}
     if(invalid_pin == '-1')
     {
         $('#pincode_avail').show(); 
         $('#change_pin').hide();
     }
   
   function change_pincode()
   {
    
    $('#pincode_avail').show();
   // $('#check_but').removeAttr('disabled');
    $('#change_pin').hide();
  }

 
 //change_pincode(cookie_pincode);
 </script>
 {/literal}
            
            
            
            
	{if $payment_methods}
		{assign var="m_name" value="checkout"}
		{assign var="link_href" value="checkout.checkout&edit_step=step_two"}
		{include file="buttons/checkout.tpl" but_href=$link_href}
	{/if}
	{if $checkout_add_buttons}
		{foreach from=$checkout_add_buttons item="checkout_add_button"}
			<p>{$checkout_add_button}</p>
		{/foreach}
	{/if}
	</div>
        
        
        
</div>

