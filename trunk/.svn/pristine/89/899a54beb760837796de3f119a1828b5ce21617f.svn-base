{literal}
<style>
.crt_new_txt_coupon input[type="text"]{color:#aaa!important; width:120px;}
.crt_new_txt_coupon input[type="text"]:focus{color:#333!important;}
.subheader{padding-top:0;}
</style>
{/literal}
{* $Id: step_three.tpl 12479 2011-05-18 08:54:10Z alexions $ *}

{assign var="customerId" value=$smarty.session.auth.user_id}
<div class="step-container{if $edit}-active{/if}" id="step_three">
	<h2 class="step-title{if $edit}-active{/if}" style="padding-bottom:0">
		<span class="float-left">{if $profile_fields.B || $profile_fields.S}3{else}2{/if}.</span>

    {if $complete && !$edit}
        <img src="{$images_dir}/icons/icon_step_close.gif" width="19" height="17" border="0" alt="" class="float-right" />
    {/if}

    {hook name="checkout:edit_link_title"}
        <a class="title{if $complete && !$edit} cm-ajax{/if}" {if $complete && !$edit}href="{"checkout.checkout?edit_step=step_three&amp;from_step=`$edit_step`"|fn_url}" rev="checkout_steps"{/if}>{$lang.payment_and_shipping}</a>
    {/hook}
</h2>
{if isset($cart.cod_eligible_order_id) && $cart.cod_eligible_order_id != 0}
    {assign var="data" value=$cart.cod_eligible_order_id.0|check_for_cod_eligible}

    {if isset($data) && !empty($data)}
        {if $data.cod_eligible == "1"}
            <div class="notification-n" style="background-image:none; margin-left: 20px; width: 95%;">
                <img class="cm-notification-close hand" src="http://cdn.shopclues.com/skins/basic/customer/images/icons/icon_close.gif" width="13" height="13" border="0" alt="Close">
                <div style="padding-left: 15px; display:inline; width:700px;">{$lang.eligible_for_cod_with_all_promotion}</div>
                <form method="POST" action="{""|fn_url}" name="place_on_cod" style="float:right; margin:-7px 30px 0 0 ;">
                    <input type="hidden" name="order_id" value="{$data.order_id}">
                    <input type="hidden" name="user_id" value="{$data.user_id}">
                    <input type="hidden" name="key" value="{$data.key}">
                    <input type="hidden" name="dispatch" value="checkout.place_cod_order">
                    <input style="background:#fc8900; border-radius:5px; border:0;color:#fff; font-size:13px; cursor:pointer; padding:5px 10px; float:right;" type="submit" name="btn_place_cod" value="{$lang.btn_place_cod}">
                </form>
            </div>
        {/if}
    {/if}

{/if}
<div id="step_three_body" class="step-body{if $edit}-active{/if} {if !$edit && !$complete}hidden{/if}">
    <div class="clear">
        {if $edit}
            <div class="float-left" style="width:100%">
                <div class="left-column-new">
                    <form name="step_three_payment_and_shipping"  class="resp-stp-three-frm {$ajax_form} {$ajax_form_force}" action="{""|fn_url}" method="{if !$edit}get{else}post{/if}">
                        <input type="hidden" name="update_step" value="step_three" />
                        <input type="hidden" name="next_step" value="step_four" />
                        <input type="hidden" name="result_ids" value="checkout_steps,checkout_cart" />
                        {if $cart.payment_id}
                            {include file="common_templates/subheader.tpl" title=$lang.select_payment_method}
                            {include file="views/checkout/components/payment_methods.tpl" no_mainbox="Y"}
                        {else}
                            {$lang.text_no_payments_needed}
                        {/if}


                        {if $edit}
                            <div class="buttons-container hidden mob_hidden_new">
                                {include file="buttons/button.tpl" but_name="dispatch[checkout.update_steps]" but_text=$but_text but_id="step_three_but"}
                            </div>
                        {/if}
                    </form>

                    <div class="form_twocolumnwithbutton">
                        <div class="mobile-stp-three-hdng" onclick="mobExpandCollapse(this)">{include file="common_templates/subheader.tpl" title=$lang.promotion_cluesbucks}<span class="mob_cat_icn_rgt"></span></div>

                        <div class="mob_inn_mnu_blk">
                            {if $cart_products}
                                {capture name="cart_promotions"}

                                    {if $cart.has_coupons}
                                        {include file="views/checkout/components/promotion_coupon.tpl" location=$location}
                                    {/if}

                                    {hook name="checkout:payment_extra"}
                                    {/hook}
                                {/capture}

                                {if $smarty.capture.cart_promotions|trim}
                                    <div class="coupon-code-container crt_new_txt_coupon" style="margin-top:-10px;">
                                        {$smarty.capture.cart_promotions}
                                    </div>
                                {/if}
                            {/if}
                        </div>
                    </div>
                </div>
                <div class="right-column-new">
                    <div class="checkout_gift_products">
                    <!--Form discount -->

                    <!--End Form discount -->
                    <!--gift-->
                    <div class="mobile-stp-three-hdng no_mobile no_tablet" onclick="mobExpandCollapse(this)">{include file="common_templates/subheader.tpl" title=$lang.gift_wrapping_header}<span class="mob_cat_icn_rgt"></span></div>
                    <div class="gift_wrapping mob_inn_mnu_blk" style="margin: 1px  0 20px  0; float:left; width:413px;" >

                        {if $cart.giftable == 'Y' && isset($cart.gifting) && $cart.gifting.gift_it == 'Y'}
                            <input type="checkbox" name="gift_wrap_it" style="float:left;" onclick="remove_gift()" {if $cart.gifting.gift_it == 'Y'} checked="checked" {/if} /><span style="margin:-4px 0 0 5px; float:left">{$lang.remove_wrap_it_for_me}</span>
                            <div style="clear:both"></div>
                        {else}
                            {if $cart.giftable == 'Y'}
                                <input type="checkbox" name="gift_wrap_it" onclick="gift_it()" style="float:left;" /><span style="margin:-4px 0 0 5px; float:left">{$lang.wrap_it_for_me}</span>
                                <div style="clear:both"></div>
                            {/if}
                        {/if}
                        {if $cart.giftable == 'N'}
                            <div class="lang_not_giftable">{$lang.this_cart_is_not_giftable}</div>
                        {/if}

                        {if $cart.giftable == 'Y' &&  $cart.gifting.gift_it == 'Y' && $cart.gifting.to != "" && $cart.gifting.from != "" && $cart.gifting.msg != ""}
                            <div class="gift_area" style="">
                                <p class="gift_area_to">{$lang.gift_to} : {$cart.gifting.to}</p>
                                <p class="gift_area_msg">{$lang.gift_msg} : {$cart.gifting.msg}</p>
                                <p class="gift_area_frm">{$lang.gift_from} : {$cart.gifting.from}</p>
                                <div style="clear:both;"></div>
                                <a href="javascript:void(0)" class="gift_area_rm_gift " style="float:left; margin-left:10px;" onclick="remove_gift()">{$lang.remove_gift}</a>
                                <a href="javascript:void(0)" class="gift_area_chng_gift " onclick="gift_it()">{$lang.change_gift}</a>
                            </div>
                        {elseif $cart.gifting.gift_it == 'Y' &&  $cart.giftable == 'Y'}
                            <div class="gift_area" style="background:none;">
                                <span>{$lang.you_opted_for_only_wrapping}</span>
                                <div style="clear:both;"></div>
                                <a href="javascript:void(0)" class="gift_area_rm_gift " style="float:left; margin-left:10px;" onclick="remove_gift()">{$lang.remove_gift}</a>
                                <a href="javascript:void(0)" class="gift_area_chng_gift " onclick="gift_it()">{$lang.change_gift}</a>
                            </div>
                        {/if}
                        {if $cart.giftable == 'Y'}
                            <div class="lang_not_giftable">{$lang.gift_instructions}</div>
                        {/if}
                    </div>
                    <!--gift-->
                    <!--price-->
                    <div class="gift_wrap_frm" id="gift_wrap_frm"  style="display:none; position: absolute; z-index:5000; left: 0px; top: 0px; width: 100%; min-height: 100%; background-position: initial initial; background-repeat: initial initial; ">
                        <div class="gft_wrp_popup" style="width:1000px; margin:auto; position:relative;">
                            <div class="add_nl_chng_add" style="display:block;">
                                <form name="gift_wrap" class="cm-ajax gift_popup_block" method="post" action="{""|fn_url}">
                                    <div class="add_nl_chng_add_new">{$lang.wrap_your_gift}</div>
                                    <div class="form-field">
                                        <label for="gift_to" class="title_name">{$lang.gift_to}:</label>
                                        <input type="text" name="gift_to" id="gift_to" size="55" maxlength="50" value="{if isset($smarty.session.cart.gifting)}{$smarty.session.cart.gifting.to}{/if}" class="input-text round_five profile_detail_field cont_nl_inpt_width title_box" />
                                    </div>

                                    <div class="form-field">
                                        <label for="gift_from" class="title_name">{$lang.gift_from}:</label>
                                        <input type="text" name="gift_from" id="gift_from" size="55" maxlength="50"  value="{if isset($smarty.session.cart.gifting)}{$smarty.session.cart.gifting.from}{/if}" class="title_box input-text round_five profile_detail_field cont_nl_inpt_width" />
                                    </div>

                                    <div class="form-field">
                                        <label for="gift_message" class="title_name">{$lang.gift_message}:</label>
                                        <textarea name="gift_message" id="gift_message" rows="3" cols="40" class="round_five profile_detail_field" style="max-width:387px; margin-top:5px;  height:103px; max-height:250px; width:387px;" onKeyDown="limitText(this.form.gift_message,this.form.countdown,250);"
                                                  onKeyUp="limitText(this.form.gift_message,this.form.countdown,250);">{if isset($smarty.session.cart.gifting)}{$smarty.session.cart.gifting.msg}{/if}</textarea>
                                        <span style="float:left; width:100%">You have <input readonly type="text" id="countdown" name="countdown" value="250" style="border:0; float:none; max-width:23px; min-width:10px; background:none; padding:0; margin:0;" /> characters left.</font></span>
                                    </div>
                                    <input type="hidden" name="redirect" value="checkout.checkout" />
                                    <input type="hidden" name="result_ids" value="payment_summary,shipping_rates_list,checkout_cart,checkout_totals,checkout_steps,cart_status,step_three" />



                                    <span class="sv_widout_change_btn">{include file="buttons/button.tpl" but_role="button_main" but_name="dispatch[checkout.gift_wrap]" but_text=$lang.save_without_message but_class="form_twocolumnwithbutton_functions_button"}</span>
                                    {include file="buttons/save.tpl" but_name="dispatch[checkout.gift_wrap]" but_text="Submit" but_role="button_main" but_class="box_functions_button nl_btn_blue gift_btn_new"}
                                    <a href="javascript:void(0)" class="gift_area_rm_gift act_btn_nl_add" style="float:right; margin: 0px 10px; width: auto;" onclick="close_gift_frm()">{$lang.close_gift_frm}</a>
                                </form>
                            </div>
                        </div>
                    </div>
                    {include file="views/checkout/components/cart_item_box.tpl"}
                    <!--price-->
                    {include file="views/checkout/components/checkout_totals_custom.tpl"}

                    {if $edit}
                        <div id="third_step_button" class="buttons-container box_functions step_IIIrd_btn_align" {if $cart.differ_address == "yes" && $cart.payment_id == "6"} style="visibility: hidden"{/if}>
                            {include file="buttons/button.tpl" but_onclick="$('#step_three_but').click();" but_text=$but_text but_role="action"}
                        </div>
                    {/if}
                </div>
                </div>
                 </div>



               <!--<div class="clearboth"></div>-->




              {assign var="min_emi_amount" value=$config.emi_min_amount|default:"4000"}
              {if $cart.total >= $min_emi_amount}
                  {assign var="payment_emi_options" value=$cart.payment_option_id|get_emi_options}
                  {foreach from=$payment_emi_options item="payment_emi"}
                      {if $payment_emi.period == $cart.payment_details.period}
                          {assign var="interest_rate" value=$payment_emi.interest_rate}
                          {assign var="period" value=$payment_emi.period}
                          {if $smarty.now|date_format:'%Y-%m-%d' <= $payment_emi.promo_end_date}
                              {assign var="emi_fees" value=$payment_emi.promo_fee}
                          {else}
                              {assign var="emi_processing_fee" value=$cart.total-$cart.emi_fee}
                              {assign var="emi_processing_fee" value=$emi_processing_fee*$payment_emi.fee}
                              {assign var="emi_processing_fee" value=$emi_processing_fee/100}
                              {assign var="emi_fees" value=$emi_processing_fee}
                          {/if}

                            {assign var="cart_total" value=$cart|fn_cart_total}
                            {assign var="total_amount" value=$cart_total+$emi_fees}
                            {assign var="installment" value=$total_amount|fn_calculate_emi:$payment_emi.interest_rate:$payment_emi.period}
                            {assign var="interest" value =$installment*$payment_emi.period-$total_amount}
                            {assign var="interest" value=$interest|ceil|number_format}
                          {assign var="emi_fees" value=$emi_fees|number_format}
                          {if $payment_emi.interest_rate != '0'}
                          <div style="float:right; margin-top: 20px;">{$lang.bank_interest_and_percent|replace:'[percent]':$payment_emi.interest_rate|replace:'[interest]':$interest}</div>
                          {/if}
                      {/if}
                  {/foreach}
              {/if}
<div class="clearboth"></div>




        {else}
            {if $completed_steps.step_three}
                <table width="100%">
                    <tr valign="top"><td width="45%">
                            <div class="step-complete-wrapper">
                                {if $cart.payment_id}
                                    <strong>{$lang.payment_method}: &nbsp;</strong>{$payment_info.payment};
                                    {if $cart.extra_payment_info.card_number}
                                        {foreach from=$credit_cards item="card"}
                                            {if $card.param == $cart.extra_payment_info.card}
                                                {$card.descr}:&nbsp;{$cart.extra_payment_info.secure_card_number}&nbsp;{$lang.exp}:&nbsp;{$cart.extra_payment_info.expiry_month}/{$cart.extra_payment_info.expiry_year}
                                            {/if}
                                        {/foreach}
                                    {/if}
                                {else}
                                    {$lang.text_no_payments_needed}
                                {/if}
                            </div>
                        </td>
                        <td width="10%">&nbsp;</td>
                        <td width="45%">
                            <div class="step-complete-wrapper">
                                {hook name="checkout:select_shipping_complete"}
                                    <strong>{$lang.shipping_method}: &nbsp;</strong>
                                {if $cart.shipping_required == true}
                                    {include file="views/checkout/components/shipping_rates.tpl" no_form=true display="show"}
                                {else}
                                    {$lang.free_shipping}
                                {/if}
                                {/hook}
                            </div>
                        </td></tr>
                </table>
            {/if}
        {/if}
    </div>

    {if $complete && !$edit}
        {hook name="checkout:edit_link"}
            <div class="right">
                {include file="buttons/button.tpl" but_meta="cm-ajax" but_href="checkout.checkout?edit_step=step_three&amp;from_step=$edit_step" but_rev="checkout_steps" but_text=$lang.change but_role="tool"}
            </div>
        {/hook}
    {/if}
</div>
{assign var="index" value = 0}
{foreach from=$cart.products item="product" key="key"}
    {if $index == 0}
        {assign var="productIds" value = $product.product_id}
    {else}
        {assign var="temp_id" value = $product.product_id}
        {assign var="productIds" value = $productIds,$temp_id}
    {/if}
    {assign var="index" value = $index+1}

{/foreach}

{foreach from=$cart_products item="product" key="key"}
    {if $product.is_serviceable == '0' || $cart.products.$key.is_serviceable == '0'}
        {assign var="lang_var_for_pin" value="1"}
    {/if}
{/foreach}
{if $lang_var_for_pin == '1'}
    <p style="color:#7C8E8E; text-align: left; margin-right: 25px;">{$lang.pincode_lang_var}</p>
{/if}
{$lang.third_step_footer_text}
<!--step_three--></div>



{literal}
<script type="text/javascript">

    $('#close_notification').click(function(){
        $('#gift_wrap_frm').toggle();
    });

    $('.cm-notification-close').click(function(){
        $('.notification-n').toggle();
    });

    function mobExpandCollapse(object) {
        if($(window).width()<=800){

            if($(object).hasClass("active"))
            {
                $(object).next(".mob_inn_mnu_blk").slideUp("fast");
                $(object).removeClass("active");
            }
            else
            {
                $(object).addClass("active");
                $(object).next(".mob_inn_mnu_blk").slideDown("fast");
            }
        }
    }

    function gift_it()
    {
        var img = "<img src='http://api.targetingmantra.com/RecordEvent?mid=130915&eid=10&pid={/literal}{$productIds}{literal}&cid={/literal}{$customerId}{literal}' width='1' height='1'>";
        $(".gift_wrapping").append(img);
        $('#gift_wrap_frm').toggle();
        document.getElementById('countdown').value = 250 - document.getElementById('gift_message').value.length;
    }

    function close_gift_frm()
    {
        jQuery.ajaxRequest("index.php?dispatch=checkout.checkout&edit_step=step_three", {cache: false, result_ids: 'payment_summary,shipping_rates_list,checkout_cart,checkout_totals,checkout_steps,cart_status,step_three'});
    }

    function remove_gift()
    {
        jQuery.ajaxRequest("index.php?dispatch=checkout.remove_gift_wrap", {cache: false, result_ids: 'payment_summary,shipping_rates_list,checkout_cart,checkout_totals,checkout_steps,cart_status,step_three'});
    }

    function limitText(limitField, limitCount, limitNum) {
        if (limitField.value.length > limitNum) {
            limitField.value = limitField.value.substring(0, limitNum);
        } else {
            limitCount.value = limitNum - limitField.value.length;
        }
    }
    document.getElementById('countdown').value = 250 - document.getElementById('gift_message').value.length;
   
    $('#wholesale_subscription_new').click(function() {
        window.location.assign("/index.php?dispatch=checkout.add_wholesale_subscription");
        
    });
</script>
{/literal}
