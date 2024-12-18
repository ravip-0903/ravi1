{* $Id: mashipping.tpl 12479 2011-05-18 08:54:10Z alexions $ *}
<form name="mashipping_frm" method="post" action="{""|fn_url}" style="margin-top:20px;">
    <h2 class="step-title-active" style="float:left; width:75%;">
		<span class="float-left">{$lang.multiaddress_step}</span>
		<span style="float:left" class="title">{$lang.multiaddress_step_name}</span>
        {$lang.mul_add_help_link}
        <span style="cursor: pointer;margin: 4px 0 0 10px;float: right;color:#048ccc;font: normal 12px Trebuchet MS, Arial, Helvetica, sans-serif;">
            <a href="http://images.shopclues.com/images/addressbook_sample.xls" style="margin:2px 0 0 0px;">Download Template</a>
             / 
            <a class="upload_add_book" href="javascript: void(0);" style="margin:2px 0 0 0px;">Upload Address Book</a>
        </span>
	</h2>
                {if $lang.multiaddress_to_single != ''}
                    <div class="mul_add_new_btn">{include file="buttons/button.tpl" but_name="ship_to" but_text=$lang.multiaddress_to_single}</div>
                {/if}
    <div class="mul_add_main_blk">
<div class="mul_add_left_blk">
    <!--<div class="list_lightboxcartitem" style="height: 0px;padding: 0px;"></div>-->
{foreach from=$new_cart_structure item="new_cart" key="k"}
    <div class="mul_add_sep_blks">
    <input type="hidden" name="new_cart[{$k}][product_id]" value="{$new_cart.product_id}">
    <input type="hidden" name="new_cart[{$k}][name]" value="{$new_cart.name}">
    <input type="hidden" name="new_cart[{$k}][cart_id]" value="{$new_cart.cart_id}">
    <input type="hidden" name="new_cart[{$k}][price]" value="{$new_cart.price}">
    <div class="mul_add_prd_img">
        {assign var="pro_images" value=$new_cart.product_id|fn_get_image_pairs:'product':'M'}
        {include assign="image_inc_tag" file="common_templates/image.tpl" image_width="50" image_height="50" obj_id=$obj_id_prefix images=$pro_images object_type="product" show_thumbnail="Y"}
        {assign var='image_inc_tag' value=$image_inc_tag|extractimg}
        {$image_inc_tag}
    </div>
    <div class="mul_add_prd_name">
        {$new_cart.name}
        {assign var ="product_option" value=$new_cart.product_options|fn_get_selected_product_options_info}
        {if $product_option}
            {include file="common_templates/options_info.tpl" product_options=$product_option}
        {else}
            <br/>
        {/if}
        <br/>
        <p class="mul_add_special_msg"></p>
    </div>
    <div class="mul_add_add_field">
        <select id="profile_select_{$k}" class="profile_select" name="new_cart[{$k}][profile_id]">
        {foreach from=$user_profiles item="profile"}
            {capture name="display_value"}{$profile.s_firstname} {$profile.s_lastname} {$profile.s_address} {$profile.s_address_2} {$profile.s_city}{/capture}
            {if $profile.profile_id==$new_cart.profile_id}
                <option selected="selected" value="{$profile.profile_id}">{$smarty.capture.display_value}</option>
            {else}
                <option value="{$profile.profile_id}">{$smarty.capture.display_value}</option>
            {/if}
        {/foreach}
        </select>
        
        <div class="edit_add_address">
            <a class="part_edt_add_ress change_address" href="javascript: void(0);" >{$lang.multiaddress_edit}</a>
            <a class="part_edt_add_ress add_new_address" href="javascript: void(0);" >{$lang.multiaddress_add}</a>
        </div>
    </div>
    
    <div class="prd_box_nl_fs_crt_val" title="{$lang.multiaddress_increase_decrease_title}">
    <a href="javascript: void(0);" class='dec_product' ></a>
    
    <span class="mid_txt_val"><input class="mid_txt" rev="{$new_cart.product_id}" type="text" id="amount{$k}" name="new_cart[{$k}][amount]" value="{$new_cart.amount}" maxlength="2" readonly="readonly"  style="max-width: 20px;"></span>
    
    <a href="javascript: void(0);" class='inc_product' ></a>
    <div class="list_lightboxcartitem ma_shipping_step_three">
            <a style="margin:0 12px 0 0;" href="javascript: void(0);" class="list_lightboxcartitem_close remove_product" title="Remove Item">X</a>				</div>
    </div>
    
        
    
</div>
{/foreach}
</div>

<div class="mul_add_right_blk">

<input type="hidden" name="dispatch" value="checkout.update_steps" />
<div class="mul_add_cart_info">
    <div class="mul_add_heading">Total Products</div>
    <div class="mul_add_value"><span id="product_qty">{$total_product_quantity}</span></div>
    <input type="hidden" id="multi_product_qty" value="{$total_product_quantity}" />
</div>
<div class="mul_add_cart_info price_mul_add_mrgn_top">
    <div class="mul_add_heading">Total Price</div>
    <div class="mul_add_value">Rs. <span id="product_amount">{$total_product_price}</span></div>
    <input type="hidden" id="multi_product_amount" value="{$total_product_price}" />
</div>

<div class="mul_add_cont_btn">{include file="buttons/button.tpl" but_name="dispatch[checkout.update_steps]" but_text=$lang.continue}</div>
<div class="clearboth"></div>
<div style="margin: 30px 0 0 20px;">
    <p  class="billing_address"></p>
</div>
</div>
</div>
</form>

  <div id="change_address_popup" style="position: absolute; display:none; z-index: 200; margin-top:-130px; left: 0px; top: 0px; width: 100%; min-height: 100%;  background-position: initial initial; background-repeat: initial initial;"> 
    	<div style="width:1000px; margin:auto; position:relative;">
            <div  class="add_nl_chng_add mul_add_stp_thr_add" style="display:block;">
            <img id="close_notification" class="cm-notification-close hand add_nl_close_btn" src="skins/basic/customer/images/icons/icon_close.gif" width="13" height="13" border="0" alt="Close" title="Close">
            <form name="change_add_popup" id="change_add_popup">
            	<div class="add_nl_chng_add_new"></div>
                <input type="hidden" id="pop_profile_id" name="profile_id" value="" />
                <input type="hidden" id="pop_user_id" name="user_id" value="{$cart.user_data.user_id}" />
                <div class="chng_address_fields">
                <label class="nl_add_chng_add_title cm-required" for="s_profile_name" >Profile Name</label>
                <input type="text" id="s_profile_name" class="cm-required"  style="margin-left:13px" name="profile_name" value="" onfocus="this.style.background='#FFF';"/>
                </div>
                <div class="chng_address_fields">
                <label class="nl_add_chng_add_title cm-required" for="s_firstname" >First Name</label>
		<input type="text" class="cm-required" id="s_firstname"  name="firstname" value="" onfocus="this.style.background='#FFF';" />
</div>
                <div class="chng_address_fields">
                <label class="nl_add_chng_add_title_2 cm-required" for="s_lastname" >Last Name</label>
                                <input type="text" id="s_lastname" class="cm-required" name="lastname" value="" onfocus="this.style.background='#FFF';" />
                </div>
                <div class="chng_address_fields">
                <label class="nl_add_chng_add_title cm-required" for="s_address" >Address</label>
		<input type="text" id="s_address" class="cm-required" name="address" value="" onfocus="this.style.background='#FFF';" />
                </div>
                <div class="chng_address_fields">
                <label class="nl_add_chng_add_title_2">Address, line 2:</label>
               <input type="text" id="s_address_2"  name="address_2" value="" />
                </div>
                <div class="chng_address_fields">
                    <label class="nl_add_chng_add_title cm-required" for="s_city" >City</label>
		 <input type="text" id="s_city" class="cm-required" name="city" value="" onfocus="this.style.background='#FFF';" />
              </div>
                <div class="chng_address_fields">
                <label class="nl_add_chng_add_title_2 cm-required" for="s_state" >State</label>
                 {assign var="country_code" value=$settings.General.default_country}
                {assign var="state_code" value=$value|default:$settings.General.default_state}
                <select name="state" class="cm-required" id="s_state" onfocus="this.style.background='#FFF';">
                    <option value="">- {$lang.select_state} -</option>
                    {if $states}
                        {foreach from=$states.$country_code item=state}
                            <option value="{$state.code}">{$state.state}</option>
                        {/foreach}
                    {/if}
                </select>
                </div>
                <div class="clearboth"></div>
                <!--<input type="text" id="state"  name="state" value="" /><br />-->
                <input type="hidden" id="s_country"  name="country" value="" />
                <div class="chng_address_fields">
                    <label class="nl_add_chng_add_title cm-integer cm-required" for="s_zipcode" >Pincode</label>
		<input type="tel" id="s_zipcode" class="cm-required cm-integer" name="zipcode" value="" onfocus="this.style.background='#FFF';" />
                </div>
                <div class="chng_address_fields">
                <label class="nl_add_chng_add_title_2 cm-phone cm-required" for="s_phone" >Mobile Number</label>
                <input type="tel" id="s_phone" class="cm-required cm-phone" name="phone" value="" onfocus="this.style.background='#FFF';" />
                </div>
                <a href="javascript: void(0)" class="act_btn_nl_add" id="add_popup_close">Close</a>
                <input type="submit" name="save" class="act_btn_nl_add_chng cm-ajax" style="cursor:pointer;" value="" />
            </form>
        </div>
	</div>
</div>
<div id="upload_address_book" class="pj2_content_hidea hide_emia" style="display:none; position: fixed; z-index: 200; left: 0px; top: 0px; width: 100%; min-height: 100%;">
<div style=" width:600px; margin:auto;">
<div class="pj2_popup_prd" style="margin-top:144px; width:600px;">
<img class="img_close" src="http://images.shopclues.com/images/skin/pj2_close_btn_banklist.png ">
<p style="font:16px/22px trebuchet MS; color:#000; display:block; text-align:left; padding:0; font-weight:bold; margin:0px 0 0 10px;">{$lang.upload_address_book_mul_add}</p> 
<form style="padding:10px;" method="post" action="{""|fn_url}" name="addressbook" id="addressbook" enctype="multipart/form-data">
        <input type="file" name="csvfile" class="input_box_add_pj2" id="csvfile" />
        <label for="csvfile" class="label_box_add_pj2">Upload Address Book Data (xls File Only):</label>
        <div class="hidden" id="responePrg">
                <p>{$lang.address_import_inprogress}</p>
                <img src="images/progress-bar.gif">
        </div>
        <a href="index.php?dispatch=profiles.manage_addressbook" >{$lang.multiaddress_add_manually}</a>
    <input type="hidden" name="mode_action" value="import" /><br clear="all" /><br />
    <span class="button-submit">
    <input type="submit" value="Upload Address Book" name="dispatch[profiles.upload_excel]" onclick="return responseProcess();" />
    </span>
</form>
</div>
</div>
</div>

<div id="change_message_popup" style="position: absolute; display:none; z-index: 200; margin-top:-130px; left: 0px; top: 0px; width: 100%; min-height: 100%;  background-position: initial initial; background-repeat: initial initial;"> 
    	<div style="width:1000px; margin:auto; position:relative;">
            <div  class="add_nl_chng_add mul_add_stp_thr_add" style="display:block;">
            <img id="close_message_notification" class="cm-notification-close hand add_nl_close_btn" src="skins/basic/customer/images/icons/icon_close.gif" width="13" height="13" border="0" alt="Close" title="Close">
            <form name="change_message_popup" id="change_message_popup">
            	<div class="add_nl_chng_add_new" id="message_popup_title" style="float:left; font-size:16px; width:100%;">ADD Your Message</div>
                <label class="nl_add_chng_add_title cm-required" for="msg_to"  style="width:100px; margin-top: 6px;">To</label>
                <input type="text" id="msg_to" class="cm-required"  style="width:266px" name="msg_to" value="" />
                <label class="nl_add_chng_add_title cm-required" for="msg_from"  style="width:100px; margin-top: 6px;">From</label>
                <input type="text" class="cm-required" id="msg_from"  style="width:266px" name="msg_from" value="" />
                <label class="nl_add_chng_add_title cm-required" for="msg_desc" style="width:100px" >Message</label>
                <textarea id="msg_desc" name="msg_desc" maxlength="250" style="clear: both; width: 386px; background-color: rgb(255, 255, 255); border: 1px solid rgb(204, 204, 204); border-top-left-radius: 5px; border-top-right-radius: 5px; border-bottom-right-radius: 5px; border-bottom-left-radius: 5px;  margin: 7px;max-width: 386px;max-height: 50px;height: 50px;"></textarea>
                <a href="javascript: void(0)" class="act_btn_nl_add" id="add_message_close">Close</a>
                <input type="submit" name="save" class="act_btn_nl_add_chng cm-ajax" style="cursor:pointer;" value="Save" />
            </form>
        </div>
	</div>
</div>
{literal}
<script type="text/javascript">
    user_profiles_json = JSON.parse('{/literal}{$user_profiles_json|json_encode}{literal}');
    product_price_json = JSON.parse('{/literal}{$product_prices_arr|json_encode}{literal}');
    product_data_arr = JSON.parse('{/literal}{$smarty.session.cart.products|json_encode}{literal}');
    var feature_check = '{/literal}{$config.quantity_discount_flag}{literal}'
    var cart_products = new Array();
    var add_blk;
    var max_cart_index = 0;
    var billing_flag = 'N';
    var close_chkbox ;
    var message;
    function get_billing_address()
    {
        $.each(user_profiles_json, function(index){
            if(user_profiles_json[index]['profile_type'] == 'P')
            {
                add_blk = '<div class="add_nl_block" style="border:0; background:none; width:auto; cursor:default;">\
                                <span><p><strong>Billing Address</strong></p></span>\
                                <span> <input type="hidden" id="primary_profile" value="' + user_profiles_json[index]['profile_id'] + '" /></span>\
                                <div class="address_title">\
                                    <span class="add_nl_name">' + user_profiles_json[index]['b_firstname'] + '</span>\
                                    <span class="add_nl_surname">' + user_profiles_json[index]['b_lastname'] + '</span>\
                                </div>\
                                <div style="clear:both;"></div>\
                                <span>' + user_profiles_json[index]['b_address'] + '</span>\
                                <span>' + user_profiles_json[index]['b_address_2'] + '</span>\
                                <div style="clear:both;"></div>\
                                <span>' + user_profiles_json[index]['b_city'] + '</span>, <span>' + user_profiles_json[index]['s_state'] + '</span>\
                                <div style="clear:both;"></div>\
                                <span>' + user_profiles_json[index]['b_zipcode'] + '</span>\
                                <div style="clear:both;"></div>\
                                <span>' + user_profiles_json[index]['b_phone'] + '</span>\
                                <span><a class="edit_billing_address" href="javascript: void(0);" >Change</a></span>\
                        </div>';
                $('.billing_address').next('div.add_nl_block').remove();
                $('.billing_address').after(add_blk);
                $('a.edit_billing_address').bind('click', popup_billing_address); 
                {/literal}
                    {if $smarty.request.change_billing == 'Y'}
                        {literal}popup_billing_address();{/literal}
                    {/if}
                {literal}
            } 
        });
    } 
    function get_address_block(pid,product_id,obj){
        var msgtag = $(obj).parents().prev().children('.mul_add_special_msg');
        if(typeof user_profiles_json[pid][product_id] != 'undefined')
        {
            if(user_profiles_json[pid][product_id]== 'Y')
                {
                    add_blk = '<div class="add_nl_block">\
                            <div class="address_title">\
                                <span class="add_nl_name">' + user_profiles_json[pid]['s_firstname'] + '</span>\
                                <span class="add_nl_surname">' + user_profiles_json[pid]['s_lastname'] + '</span>\
                            </div>\
                            <div style="clear:both;"></div>\
                            <span>' + user_profiles_json[pid]['s_address'] + '</span>\
                            <span>' + user_profiles_json[pid]['s_address_2'] + '</span>\
                            <div style="clear:both;"></div>\
                            <span>' + user_profiles_json[pid]['s_city'] + '</span>, <span>' + user_profiles_json[pid]['s_state'] + '</span>\
                            <div style="clear:both;"></div>\
                            <span>' + user_profiles_json[pid]['s_zipcode'] + '</span>\
                            <div style="clear:both;"></div>\
                            <span>' + user_profiles_json[pid]['s_phone'] + '</span>\
                    </div>';
                    $(obj).after(add_blk);
                    if(user_profiles_json[pid]['msg'] == '')
                        {
                            msgtag.html('<input type="checkbox" name="special_msg" class="special_msg_chk_box" />Send Special Gift Message (no extra charges)');
                            $('.special_msg_chk_box').bind('change', popup_message_box);
                        }
                    else
                        {
                            if( user_profiles_json[pid]['msg'].length >= 25 )
                            { 
                                message = user_profiles_json[pid]['msg'].substr(0,25)+'...';
                            }
                            else
                            { 
                                message = user_profiles_json[pid]['msg'];
                            }
                            msgtag.html('<fieldset style="width: 75%;border: 1px solid #ccc;border-radius: 3px;padding: 0 8px;float: left;"><legend>To '+user_profiles_json[pid]['to']+'</legend><span>'+message+'<br/><a class="edit_mul_message">Edit</a> / <a class="delete_mul_message cm-ajax">Remove</a></span></fieldset>');
                            $('.edit_mul_message').bind('click', edit_message_box);
                            $('.delete_mul_message').bind('click', remove_message_box);
                        }
                }
            else
                {
                    add_blk = '<div class="add_nl_block">{/literal}{$lang.no_serviceability}{literal}</div>';
                    $(obj).after(add_blk);
                    msgtag.html('');
                }
        }
        else
            {   
                $.ajax({
                            type: "GET",
                            url: "index.php",
                            async: false,
                            data: {'dispatch':'checkout.get_service_status','product_id':product_id,'pin_code':user_profiles_json[pid]['s_zipcode']},
                            dataType : 'text',
                            success: function(result){
                                if(result=='4' || result=='3')
                                    {   
                                        user_profiles_json[pid][product_id] = 'Y';
                                        add_blk = '<div class="add_nl_block">\
                                    <div class="address_title">\
                                        <span class="add_nl_name">' + user_profiles_json[pid]['s_firstname'] + '</span>\
                                        <span class="add_nl_surname">' + user_profiles_json[pid]['s_lastname'] + '</span>\
                                    </div>\
                                    <div style="clear:both;"></div>\
                                    <span>' + user_profiles_json[pid]['s_address'] + '</span>\
                                    <span>' + user_profiles_json[pid]['s_address_2'] + '</span>\
                                    <div style="clear:both;"></div>\
                                    <span>' + user_profiles_json[pid]['s_city'] + '</span>, <span>' + user_profiles_json[pid]['s_state'] + '</span>\
                                    <div style="clear:both;"></div>\
                                    <span>' + user_profiles_json[pid]['s_zipcode'] + '</span>\
                                    <div style="clear:both;"></div>\
                                    <span>' + user_profiles_json[pid]['s_phone'] + '</span>\
                                     </div>';
                                    $(obj).after(add_blk);
                                    if(user_profiles_json[pid]['msg'] == '')
                                        {
                                            msgtag.html('<input type="checkbox" name="special_msg" class="special_msg_chk_box" />Send Special Gift Message (no extra charges)');
                                            $('.special_msg_chk_box').bind('change', popup_message_box);
                                        }
                                    else
                                        {
                                            if( user_profiles_json[pid]['msg'].length >= 25 )
                                            { 
                                                message = user_profiles_json[pid]['msg'].substr(0,25)+'...';
                                            }
                                            else
                                            { 
                                                message = user_profiles_json[pid]['msg'];
                                            }
                                            msgtag.html('<fieldset style="width: 75%;border: 1px solid #ccc;border-radius: 3px;padding: 0 8px;float: left;"><legend>To '+user_profiles_json[pid]['to']+'</legend><span>'+message+'<br/><a class="edit_mul_message">Edit</a> / <a class="delete_mul_message cm-ajax">Remove</a></span></fieldset>');
                                            $('.edit_mul_message').bind('click', edit_message_box);
                                            $('.delete_mul_message').bind('click', remove_message_box);
                                        }

                                    }
                                else
                                    {
                                        user_profiles_json[pid][product_id] = 'N';
                                        add_blk = '<div class="add_nl_block">{/literal}{$lang.no_serviceability}{literal}</div>';
                                        $(obj).after(add_blk);
                                        msgtag.html('');
                                    }
                                    
                        }
                });
            }
    }
    
    function profile_select_change(){
        $(this).next('div.add_nl_block').remove();
        var y = $(this).parents('div.mul_add_sep_blks').find('input[name="new_cart['+ $(this).attr('id').match(/\d+/)[0] + '][product_id]"]').val();
        get_address_block($(this).attr('value'),y,this);
        //$(this).after(get_address_block($(this).attr('value'),y));
    }
    
    $(document).ready(function(){
        $('form#change_add_popup').bind('submit', saveaddress);
        $('form#change_message_popup').bind('submit', savemessage);
        get_billing_address();
        $('.profile_select').each(profile_select_change);
        add_product_lines_form();
        synchronize_bind(); 
    });
    
    function synchronize_bind(){
        $('.profile_select').unbind('change', profile_select_change);
        $('a.inc_product').unbind('click', increase_decrease_product_quantity);
        $('a.dec_product').unbind('click', increase_decrease_product_quantity);
        $('a.remove_product').unbind('click', remove_product);
        $('a.part_edt_add_ress').unbind('click', add_edit_address);
        
        $('.profile_select').bind('change', profile_select_change);
        $('a.inc_product').bind('click', increase_decrease_product_quantity);
        $('a.dec_product').bind('click', increase_decrease_product_quantity);
        $('a.remove_product').bind('click', remove_product);
        $('a.part_edt_add_ress').bind('click', add_edit_address);
        $('a.upload_add_book').bind('click', upload_address_popup);
        $('.img_close').bind('click', close_upload_address_popup);
        
    }
    
    function popup_message_box()
    {
       if($(this).attr("checked")) {
            $('#message_popup_title').html('ADD Your Message');
            $('#msg_to').val('');
            $('#msg_from').val('');
            $('#msg_desc').val('');
            $('#change_message_popup').css('display','block');
            close_chkbox = this;
        }
    }
    function edit_message_box()
    {
            $('#change_message_popup').css('display','block');
            $('#message_popup_title').html('Update Your Message');
            var id = $(this).parents('.mul_add_prd_name').next().children('.profile_select').val();
            $('#msg_to').val(user_profiles_json[id]['to']);
            $('#msg_from').val(user_profiles_json[id]['from']);
            $('#msg_desc').val(user_profiles_json[id]['msg']);
            close_chkbox = this;
       
    }
    function remove_message_box()
    {
            var pid = $(this).parents('.mul_add_prd_name').next().children('.profile_select').val();
            $(this).parents(".mul_add_special_msg").html('Wait Please...');
            $.ajax({
             type: "GET",
             url: "index.php",
             data: {'dispatch':'checkout.removemessage','profile':pid},
             dataType : 'text',
             success: function(result){
                 user_profiles_json[pid]['to'] = '';
                 user_profiles_json[pid]['from'] ='';
                 user_profiles_json[pid]['msg'] = '';
                 $('.profile_select').each(profile_select_change);
                 jQuery.toggleStatusBox('hide');
                }
    });
             return false;
    }
    function upload_address_popup()
    {
        $('#upload_address_book').css('display','block');
    }
    function close_upload_address_popup()
    {
        $('#upload_address_book').css('display','none');
    }   
    function responseProcess(){
	$('#resPrg').hide();
	$('#responePrg').show();
    }
    function remove_product(){
        input_id = $(this).parents('div.prd_box_nl_fs_crt_val').find('input.mid_txt').attr('id');
        price = $(this).parents('div.mul_add_sep_blks').find('input[name*="\[price\]"]').val();

        var x = parseInt($('#'+input_id).val());
        decrease(input_id, price, x);
        
        line_obj = $(this).parents('div.mul_add_sep_blks').hide("slow");
    }
    
    function add_edit_address(){
        if($(this).attr('class').search('add_new_address') >= 0){
            popup_address();
        }
        else{
            select_id = $(this).parents('div.mul_add_sep_blks').find('select[id*="profile_select_"]').attr('id');
            cart_index = parseInt(select_id.match(/\d+/)[0]);
            popup_address(cart_index);
        }
    }
    
    function increase_decrease_product_quantity(){
        input_id = $(this).parents('div.prd_box_nl_fs_crt_val').find('input.mid_txt').attr('id');
        price = $(this).parents('div.mul_add_sep_blks').find('input[name*="\[price\]"]').val();
        if($(this).attr('class').search('inc_product') >= 0){
            increase(input_id, price);
        }
        else{
            decrease(input_id, price);
        }
    }
    
    function add_product_lines_form(){
        $("input[name*='\[cart_id\]']").each(function(){
            cart_id = $(this).val();
            cart_index = parseInt($(this).attr('name').match(/\d+/)[0]);
            if(max_cart_index < cart_index){
                max_cart_index = cart_index;
            }
            if(!cart_products[cart_id]){
                cart_products[cart_id] = new Array();
                cart_products[cart_id]['index'] = cart_index;
                cart_products[cart_id]['name'] = $("input[name='new_cart\[" + cart_index + "\][name]']").val();
                cart_products[cart_id]['object'] = $(this).parents('div.mul_add_sep_blks');
                
                product_options = $(this).parents('div.mul_add_sep_blks').find('div.product-list-field').html();
                if(product_options){
                    product_options = product_options.str_replace('<label>Options:</label>','');
                    product_options = product_options.replace(/(\s\s)+/g,'');
                    cart_products[cart_id]['options'] = product_options;
                }
            }
        });
        
        if(cart_products.length >= 1){
            options = '';
            for(cart_id in cart_products){
                str = cart_products[cart_id]['name'];
                if(cart_products[cart_id]['options']){
                    str += " (" + cart_products[cart_id]['options'] + ")";
                }
                var prd_id = (product_data_arr[cart_id] != undefined ? product_data_arr[cart_id]['product_id'] : 0);
                options += '<option value="' + cart_id +'" prd_id="'+prd_id+'">' + str + '</option>';
                
            }
            product_select = '<select id="add_product_cart_id">' + options + '</select>';
            product_qty = '<input id="add_product_qty" value="1" maxlength="1" size="2" />';
            add_button = '<input id="add_product_line" class="add_prd_mul_ship" type="button" value="Add Product" />';
            $new_lines_add_block = '<div class="add_new_product_lines">{/literal}{$lang.multiaddress_add_new_lines}{literal}' + product_select + product_qty + add_button + '</div>';
            $('div.mul_add_main_blk').append($new_lines_add_block);
            $('#add_product_line').bind('click',add_product_lines);
        }
    }
    
    (function($){
        $.fn.visible = function(partial,hidden){
            var $t                                = $(this).eq(0),
                    t                                = $t.get(0),
                    $w                                = $(window),
                    viewTop                        = $w.scrollTop(),
                    viewBottom                = viewTop + $w.height(),
                    _top                        = $t.offset().top,
                    _bottom                        = _top + $t.height(),
                    compareTop                = partial === true ? _bottom : _top,
                    compareBottom        = partial === true ? _top : _bottom,
                    clientSize                = hidden === true ? t.offsetWidth * t.offsetHeight : true;
                return !!clientSize && ((compareBottom <= viewBottom) && (compareTop >= viewTop));
        };
    })(jQuery);

    function add_product_lines(){
        add_qty = parseInt($('#add_product_qty').val());
        if(add_qty < 1){
            $('#add_product_qty').select();
            return;
        }
        var prd_id = 0;
        var prod_id_sum_prev = 0;
        $('#add_product_cart_id').children('option:selected').each(function(){ 
            prd_id = $(this).attr('prd_id');
        });
        $('input[rev="'+prd_id +'"]').each(function(){
            prod_id_sum_prev += parseInt($(this).val());
        });
        var total_to_be_subtract = 0;
        if(prod_id_sum_prev !== 0)
        {
            total_to_be_subtract = parseInt(prod_id_sum_prev*get_updated_price(prd_id,prod_id_sum_prev));
        }
        
        cart_id = $('#add_product_cart_id').val();

        ref_cart_index = cart_products[cart_id]['index'];
        ref_obj = cart_products[cart_id]['object'];
        if(!$('#profile_select_' + ref_cart_index).visible()){
            document.getElementById('profile_select_' + ref_cart_index).scrollIntoView();
        }

        names_to_change = new Object();
        names_to_change['input'] = ['new_cart[{d}][product_id]', 'new_cart[{d}][name]', 'new_cart[{d}][cart_id]', 'new_cart[{d}][amount]', 'new_cart[{d}][price]'];
        names_to_change['select'] = ['new_cart[{d}][profile_id]'];
        ids_to_change = ['profile_select_{d}','amount{d}'];

        for(i=0; i<add_qty; i++){
            qty=1;
            max_cart_index++;
            new_cart_index = max_cart_index;
            new_obj = ref_obj.clone();

            for(id in ids_to_change){
                ref_id = ids_to_change[id].str_replace('{d}',ref_cart_index);
                new_id = ids_to_change[id].str_replace('{d}',new_cart_index);
                new_obj.find('#' + ref_id).attr('id',new_id);
            }

            for(type in names_to_change){
                for(name in names_to_change[type]){
                    ref_name = names_to_change[type][name].str_replace('{d}',ref_cart_index);
                    new_name = names_to_change[type][name].str_replace('{d}',new_cart_index);
                    new_obj.find(type + '[name="' + ref_name + '"]').attr('name',new_name);
                }
            }        

            new_obj.find('#amount' + new_cart_index).val(qty);

            new_obj.hide();
            ref_obj.after(new_obj);
            new_obj.show("slow");

        }

        total_qty = parseInt($('#multi_product_qty').val()) + add_qty;
        $('#multi_product_qty').val(total_qty);
        $('#product_qty').html(total_qty);
        var product_id = $('#amount'+new_cart_index).attr('rev');
        var prod_id_sum = 0;
        $('input[rev="'+product_id+'"]').each(function(){
            prod_id_sum += parseInt($(this).val());
        });
        var updated_price = 0;
        if(prod_id_sum != 0)
        {
            updated_price = get_updated_price(product_id,prod_id_sum);
        }
        
        product_price = parseFloat(new_obj.find('input[name="new_cart\[' + new_cart_index + '\]\[price\]"]').val());
        product_price = (updated_price != null ? updated_price : product_price);

        total_price =  Math.round(parseFloat($('#multi_product_amount').val()) + ((add_qty+prod_id_sum_prev) * product_price) - total_to_be_subtract);
        $('#multi_product_amount').val(total_price);
        $('#product_amount').html(total_price);

        synchronize_bind();
    }
    
    function popup_address(id){
            var pid = $('#profile_select_'+id).val();
            if(pid)
                {
                    $('.add_nl_chng_add_new').html('Change Your Address');
                    $('#s_profile_name').val(user_profiles_json[pid]['profile_name']);
                    $('#s_firstname').val(user_profiles_json[pid]['s_firstname']);
                    $('#s_lastname').val(user_profiles_json[pid]['s_lastname']);
                    $('#s_address').val(user_profiles_json[pid]['s_address']);
                    $('#s_address_2').val(user_profiles_json[pid]['s_address_2']);
                    $('#s_city').val(user_profiles_json[pid]['s_city']);
                    $('#s_state').val(user_profiles_json[pid]['s_state']);
                    $('#s_country').val(user_profiles_json[pid]['s_country']);
                    $('#s_zipcode').val(user_profiles_json[pid]['s_zipcode']);
                    $('#s_phone').val(user_profiles_json[pid]['s_phone']);
                    $('#pop_profile_id').val(user_profiles_json[pid]['profile_id']);
                    $('.act_btn_nl_add_chng').val('Update');
                }
            else
                {
                    $('.add_nl_chng_add_new').html('Add Your Address');
                    $('#s_profile_name').val('');
                    $('#s_firstname').val('');
                    $('#s_lastname').val('');
                    $('#s_address').val('');
                    $('#s_address_2').val('');
                    $('#s_city').val('');
                    $('#s_state').val('');
                    $('#s_country').val('');
                    $('#s_zipcode').val('');
                    $('#s_phone').val('');
                    $('#pop_profile_id').val('');
                    $('.act_btn_nl_add_chng').val('Save');
                }
            $('#change_address_popup').css('display','block');
            $('.header_global').css('z-index','-1');
            billing_flag = 'N';
    }
    
    function popup_billing_address()
    {
            var pid = $('#primary_profile').val();
            $('.add_nl_chng_add_new').html('Change Your Billing Address');
            $('#s_profile_name').val(user_profiles_json[pid]['profile_name']);
            $('#s_firstname').val(user_profiles_json[pid]['b_firstname']);
            $('#s_lastname').val(user_profiles_json[pid]['b_lastname']);
            $('#s_address').val(user_profiles_json[pid]['b_address']);
            $('#s_address_2').val(user_profiles_json[pid]['b_address_2']);
            $('#s_city').val(user_profiles_json[pid]['b_city']);
            $('#s_state').val(user_profiles_json[pid]['b_state']);
            $('#s_country').val(user_profiles_json[pid]['b_country']);
            $('#s_zipcode').val(user_profiles_json[pid]['b_zipcode']);
            $('#s_phone').val(user_profiles_json[pid]['b_phone']);
            $('#pop_profile_id').val(user_profiles_json[pid]['profile_id']);
            $('.act_btn_nl_add_chng').val('Update');
            $('#change_address_popup').css('display','block');
            $('.header_global').css('z-index','-1');
            billing_flag = 'Y';
    }
    $('#add_popup_close').click(function(){
            $('#change_address_popup').css('display','none');
            $('.header_global').css('z-index','10');
    });
    $('#close_notification').click(function(){
            $('#change_address_popup').css('display','none');
            $('.header_global').css('z-index','10');
    });
    $('#add_message_close').click(function(){
            $('#change_message_popup').css('display','none');
            $(close_chkbox).removeAttr('checked');
            $('.header_global').css('z-index','10');
    });
    $('#close_message_notification').click(function(){
            $('#change_message_popup').css('display','none');
            $(close_chkbox).removeAttr('checked');
            $('.header_global').css('z-index','10');
    });
	
    function saveaddress()
    {
        if($(this).find('.cm-failed-field').length > 0){
            return false;
        }
        var pro_name = $('#s_profile_name').val();
        var u_id = $('#pop_user_id').val();
        var pro_id = $('#pop_profile_id').val();
        var fname = $('#s_firstname').val();
        var lname = $('#s_lastname').val();
        var add = $('#s_address').val();
        var add2 = $('#s_address_2').val();
        var city = $('#s_city').val();
        var country = $('#s_country').val();
        var state = $('#s_state').val();
        var zip = $('#s_zipcode').val();
        var phone = $('#s_phone').val();
        $.ajax({
         type: "GET",
         url: "index.php",
         data: {'dispatch':'checkout.saveaddress','profile_name':pro_name,
         'profile_id':pro_id,'user_id':u_id,
         's_fname':fname,'s_lname':lname,
         's_add':add,'s_add_2':add2,
         's_city': city,'s_state':state,
         's_country':country,'s_zip':zip,
         's_phone':phone,'billing':billing_flag},
         dataType : 'text',
         success: function(result){
             
             if(result=='update')
                 {
                     if(user_profiles_json[pro_id]['profile_type'] == 'P')
                         {
                             user_profiles_json[pro_id]['b_firstname']= fname;
                             user_profiles_json[pro_id]['b_lastname']= lname;
                             user_profiles_json[pro_id]['b_address']= add;
                             user_profiles_json[pro_id]['b_address_2']= add2;
                             user_profiles_json[pro_id]['b_city']= city;
                             user_profiles_json[pro_id]['b_state']= state;
                             user_profiles_json[pro_id]['b_zipcode']= zip;
                             user_profiles_json[pro_id]['b_phone']= phone;
                             get_billing_address();
                         }
                     user_profiles_json[pro_id]['profile_name']= pro_name;
                     user_profiles_json[pro_id]['s_firstname']= fname;
                     user_profiles_json[pro_id]['s_lastname']= lname;
                     user_profiles_json[pro_id]['s_address']= add;
                     user_profiles_json[pro_id]['s_address_2']= add2;
                     user_profiles_json[pro_id]['s_city']= city;
                     user_profiles_json[pro_id]['s_state']= state;
                     if(user_profiles_json[pro_id]['s_zipcode']!= zip)
                         {
                            {/literal}
                            {foreach from=$new_cart_structure item="new_cart"}
                            {literal}    
                            var x = {/literal}{$new_cart.product_id}{literal};
                            if(typeof user_profiles_json[pro_id][x] != 'undefined')
                            {
                            delete user_profiles_json[pro_id][x];
                            }
                            {/literal}    
                            {/foreach}
                            {literal}
                         }
                     user_profiles_json[pro_id]['s_zipcode']= zip;
                     user_profiles_json[pro_id]['s_phone']= phone;
                     $('.profile_select').each(profile_select_change);
                     var x = fname+' '+lname+' '+add+' '+add2+' '+city;
                     $('.profile_select option[value=' + pro_id + ']').html(x);
                     $('#change_address_popup').css('display','none');
                     $('.header_global').css('z-index','10');
                     jQuery.toggleStatusBox('hide');
                     
                 }
             else if(result=='update_new'){
                     pro_id = $('.profile_select option:first').val();
                     if(user_profiles_json[pro_id]['profile_type'] == 'P')
                         {
                             user_profiles_json[pro_id]['b_firstname']= fname;
                             user_profiles_json[pro_id]['b_lastname']= lname;
                             user_profiles_json[pro_id]['b_address']= add;
                             user_profiles_json[pro_id]['b_address_2']= add2;
                             user_profiles_json[pro_id]['b_city']= city;
                             user_profiles_json[pro_id]['b_state']= state;
                             user_profiles_json[pro_id]['b_zipcode']= zip;
                             user_profiles_json[pro_id]['b_phone']= phone;
                             get_billing_address();
                         }
                     user_profiles_json[pro_id]['profile_name']= pro_name;
                     user_profiles_json[pro_id]['s_firstname']= fname;
                     user_profiles_json[pro_id]['s_lastname']= lname;
                     user_profiles_json[pro_id]['s_address']= add;
                     user_profiles_json[pro_id]['s_address_2']= add2;
                     user_profiles_json[pro_id]['s_city']= city;
                     user_profiles_json[pro_id]['s_state']= state;
                     if(user_profiles_json[pro_id]['s_zipcode']!= zip)
                         {
                            {/literal}
                            {foreach from=$new_cart_structure item="new_cart"}
                            {literal}    
                            var x = {/literal}{$new_cart.product_id}{literal};
                            if(typeof user_profiles_json[pro_id][x] != 'undefined')
                            {
                            delete user_profiles_json[pro_id][x];
                            }
                            {/literal}    
                            {/foreach}
                            {literal}
                         }
                     user_profiles_json[pro_id]['s_zipcode']= zip;
                     user_profiles_json[pro_id]['s_phone']= phone;
                     $('.profile_select').each(profile_select_change);
                     var x = fname+' '+lname+' '+add+' '+add2+' '+city;
                     $('.profile_select option[value=' + pro_id + ']').html(x);
                     $('#change_address_popup').css('display','none');
                     $('.header_global').css('z-index','10');
                     jQuery.toggleStatusBox('hide');
                        
                    }
                    else if(result=='update_billing'){
                     user_profiles_json[pro_id]['profile_name']= pro_name;
                     user_profiles_json[pro_id]['b_firstname']= fname;
                     user_profiles_json[pro_id]['b_lastname']= lname;
                     user_profiles_json[pro_id]['b_address']= add;
                     user_profiles_json[pro_id]['b_address_2']= add2;
                     user_profiles_json[pro_id]['b_city']= city;
                     user_profiles_json[pro_id]['b_state']= state;
                     user_profiles_json[pro_id]['b_zipcode']= zip;
                     user_profiles_json[pro_id]['b_phone']= phone;
                     get_billing_address();
                     $('#change_address_popup').css('display','none');
                     $('.header_global').css('z-index','10');
                     jQuery.toggleStatusBox('hide');
                    }
             else
                 { 
                     result = parseInt(result);
                     var x = "<option value='"+result+"'>"+fname+' '+lname+' '+add+' '+add2+' '+city+"</option>";
                     $('.profile_select').append(x);
                     newObj = new Object;
                     newObj[result] = {profile_name:pro_name, s_firstname: fname,
                         s_lastname:lname,s_address:add,s_address_2:add2,s_city:city,s_state:state,
                         s_zipcode:zip,s_phone:phone,profile_id:result};
                     jQuery.extend(user_profiles_json, newObj);
                     $('#change_address_popup').css('display','none');
                     $('.header_global').css('z-index','10');
                     jQuery.toggleStatusBox('hide');
                     
                 }
            }
});
         return false;
    }
function savemessage()
    {
        if($(this).find('.cm-failed-field').length > 0){
            return false;
        }
        var pro_id = $(close_chkbox).parents('.mul_add_prd_name').next().children('.profile_select').val();
        var to = $('#msg_to').val();
        var from = $('#msg_from').val();
        var message = $('#msg_desc').val();
        $.ajax({
         type: "GET",
         url: "index.php",
         data: {'dispatch':'checkout.savemessage','msg_to':to,
         'msg_from':from,'msg_desc':message,'profile':pro_id},
         dataType : 'text',
         success: function(result){
             user_profiles_json[pro_id]['to'] = to;
             user_profiles_json[pro_id]['from'] = from;
             user_profiles_json[pro_id]['msg'] = message;
             $('.profile_select').each(profile_select_change);
             $('#change_message_popup').css('display','none');
             jQuery.toggleStatusBox('hide');
            }
});
         return false;
}     
function increase(id,amount,count)
{
    if(typeof(count)==='undefined'){
        count = 1;
    }
    else{
        count = parseInt(count);
    }
    
    var x = parseInt($('#'+id).val());
    x += count;
    $('#'+id).val(x);
    var qty = parseInt($('#multi_product_qty').val());
    qty += count;
    
    var product_id = $('#'+id).attr('rev');
    var prod_id_sum = 0;
    $('input[rev="'+product_id+'"]').each(function(){
        prod_id_sum += parseInt($(this).val());
    });
    var new_price = get_updated_price(product_id,prod_id_sum);
    var current_price = get_updated_price(product_id,prod_id_sum-1);
    current_price = (current_price != null ? current_price : amount);
    amount = (new_price != null ? new_price : amount);
    
    $('#multi_product_qty').val(qty);
    $('#product_qty').html(qty);
    var amt = $('#multi_product_amount').val();
    //amt = parseInt(amt) + (parseInt(amount)*count);
    amt = parseInt(amt) - parseInt(current_price*(prod_id_sum-1)) + parseInt(amount*prod_id_sum);
    $('#multi_product_amount').val(amt);
    $('#product_amount').html(amt);
}
function decrease(id,amount,count)
{
    if(typeof(count)==='undefined'){
        count = 1;
    }
    else{
        count = parseInt(count);
    }
    
    var x = parseInt($('#'+id).val());
    
    if(x < count){
        count = x;
    }
    x -= count;
    $('#'+id).val(x);
    
    var qty = parseInt($('#multi_product_qty').val());
    qty -= count;
    
    var product_id = $('#'+id).attr('rev');
    var prod_id_sum = 0;
    $('input[rev="'+product_id+'"]').each(function(){
        prod_id_sum += parseInt($(this).val());
    });

    var new_price = get_updated_price(product_id,prod_id_sum);
    var current_price = get_updated_price(product_id,prod_id_sum+1);
    current_price = (current_price != null ? current_price : amount);
    amount = (new_price != null ? new_price : amount);

    $('#multi_product_qty').val(qty);
    $('#product_qty').html(qty);
    
    var amt = $('#multi_product_amount').val();
    //amt = parseInt(amt) - (parseInt(amount)*count);
    amt = parseInt(amt) - parseInt(current_price*(prod_id_sum+1)) + parseInt(amount*prod_id_sum);
    $('#multi_product_amount').val(amt);
    $('#product_amount').html(amt);
}

function get_updated_price(product_id,qty)
{
    if(feature_check == '0')
        return null;
    var actual_price = '';
    if(product_price_json[product_id][qty] == undefined){
        var i = qty;
        while(product_price_json[product_id][i] == undefined && i > 0){
            i--;
        }
        return product_price_json[product_id][i].price;
    }else{
        return product_price_json[product_id][qty].price;
    }
    return null;
}
</script>    
{/literal}
