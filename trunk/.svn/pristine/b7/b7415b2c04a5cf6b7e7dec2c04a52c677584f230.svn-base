{* $Id: create_return.tpl 11949 2011-03-01 11:35:15Z aelita $ *}

<div class="box_headerTwo">
    <h1 class="box_headingTwo">{$lang.request_for_return}</h1>
</div>
</hr>
{*<pre>{$bank_names|print_r}</pre>*}
<div class="AccountContainer">

    <div class="OrderNumber">
        <div>
            <span>{$lang.order_no}</span>        
            <span>({$order_info.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"})</span>        
        </div>        
        <span>{$smarty.request.order_id}</span>
    </div>

    <p>
        {$lang.select_product_and_what_u_like_to_do}
    </p>

    <form action="{""|fn_url}" method="post" name="return_registration_form" onsubmit="return rmaForm()" enctype="multipart/form-data">
        <input name="order_id" type="hidden" value="{$smarty.request.order_id}" />        
        <input name="user_id" type="hidden" value="{$order_info.user_id}" />
        <input type="hidden" name="token" value="{$smarty.session.form_token_value}"/>
        <input type="hidden" id="apv_req" name="apv_req" value=""/>
        <input type="hidden" id="sc_apv_req" name="sc_apv_req" value=""/>
        <input type="hidden" id="mc_apv_req" name="mc_apv_req" value=""/>
        <input type="hidden" id="cust_msg" name="cust_msg" value=""/>
        <input type="hidden" id="picture_req" name="picture_req" value=""/>
        <input type="hidden" name = "payment_id" value="{$order_info.payment_id}"/>

        <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tr>
                <th class="CheckBox"><input type="checkbox" name="check_all" value="Y" title="{$lang.check_uncheck_all}" class="checkbox cm-check-items" /></th>
                <th>{$lang.product}</th>
                <th>{$lang.price}</th>
                <th>{$lang.quantity}</th>
            </tr>
            {*<pre>{$order_info|print_r}</pre>*}
            {foreach from=$order_info.items item="oi" key="key"}
                <tr {cycle values=",class=\"table-row\""}>
                    <td>

                        <input type="checkbox" name="returns[{$oi.cart_id}][chosen]" id="delete_checkbox" value="Y" class="checkbox cm-item" />
                        <input type="hidden" name="returns[{$oi.cart_id}][product_id]" value="{$oi.product_id}" />
                    </td>

                    <td>
                        <a href="{"products.view?product_id=`$oi.product_id`"|fn_url}">{$oi.product|unescape}</a>
                        {if $oi.product_options}
                            {include file="common_templates/options_info.tpl" product_options=$oi.product_options}
                        {/if}
                    </td>

                    <td>

                        {if $oi.extra.exclude_from_calculate}
                            {$lang.free}
                        {else}
                            {include file="common_templates/price.tpl" value=$oi.price}
                        {/if}
                    </td>

                    <td class="center">
                        <input type="hidden" name="returns[{$oi.cart_id}][available_amount]" value="{$oi.amount}" />
                        <select name="returns[{$oi.cart_id}][amount]">
                            {section name=$key loop=$oi.amount+1 start="1" step="1"}
                                <option value="{$smarty.section.$key.index}">{$smarty.section.$key.index}</option>
                            {/section}
                        </select>
                    </td>
                </tr>
            {foreachelse}
                <tr>
                    <td colspan="6">
                        <p class="no-items">{$lang.no_items}</p>
                    </td>
                </tr>
            {/foreach}
        </table>

        <div class="form">
            <div>
                <label>{$lang.Why_do_you_want_to_request_for_return}</label>
                {if $reasons}
                    <select name="returns[{$oi.cart_id}][reason]" id="refund_reason" onchange="show_description_div(this.value)" style="font-size:13px;"  >
                        <option value="">Select</option>
                        {foreach from=$reasons item="reason" key="reason_id"}
                            <option cust_msg="{$reason.customer_message}" id="reason_option_{$reason_id}"  pic="{$reason.picture_required}" apv_req="{$reason.approval_required}" sc_apv_req="{$reason.sc_approval_required}" mc_apv_req="{$reason.merchant_approval_required}" value="{$reason_id}">{$reason.property}</option>
                        {/foreach}
                    </select>
                    {foreach from=$reasons item="property" key="property_id"}
                        {if !empty($property.description)}
                            <div class="description_block_div no_mobile" id="description_block_{$property_id}">
                                {$property.description}
                            </div>
                        {/if}
                    {/foreach}
                {/if}
            </div>

            <div id="rma_pic_div">
                <label>{$lang.upload_picture_of_product}</label>		
                <input type="file" name="product_pic" onchange="clearFileInputField();" id="product_pic" />                
                <a id="image_remove" style="display: none;" onclick="clearFileInputField1();">x</a>

                <span>{$lang.uploading_picture_helps_us_understand_your_problem_better}</span>
            </div>

            {if $actions}
                <div>
                    <label>{$lang.refund_action}</label>                 
                    <div class="options">                        
                        {foreach from=$actions item="action" key="action_id"}                        
                            <input type="radio" name="action" value="{$action_id}" onchange="showhiderefundDiv(this.value);">
                            <label>{$action.property}</label>
                            <div class="clearboth no_desktop"></div>
                        {/foreach}                    
                    </div>                                                                                    
                </div>
            {/if}

            <div id="rma_refund" style="display: none;">
                <label>{$lang.refund_mode}</label>
                <div class="options">

                    {if $order_info.payment_id == 6}
                        <input type="radio" name="refund_mode_cod" value="refund_in_cb" onclick="cod_returns_case(this.value);">
                        <label>{$lang.refund_in_clues_bucks}</label>

                        <div class="clearboth no_desktop"></div>

                        <input type="radio" id="cod_account_info" name="refund_mode_cod" value="refund_in_ac" onclick="cod_returns_case(this.value);">
                        <label>{$lang.account_refund}</label>
                    {else}
                        <input type="radio" name="refund_mode" value="refund_in_cb">
                        <label>{$lang.refund_in_clues_bucks}</label>

                        <div class="clearboth no_desktop"></div>

                        <input type="radio" name="refund_mode" value="refund_in_ac">
                        <label>{$lang.refund_money_in_my_account}</label>
                    {/if}
                </div>

                <span class="CluesBuckAdvantage" id="adv_cb">
                    {$lang.advantages_in_cb}
                </span>

                {if $order_info.payment_id == 6}                    
                        <div id="cod_order_case" style="display :none;">                                                 
                            <div>
                                {assign var="disable_bank_status" value="-1"}
                                <label>{$lang.account_holder_name}</label>
                                <input type="text" id="account_name" name="username" value="{$order_info.firstname} {$order_info.lastname}" onchange="validatecharacter(this.value);">
                            </div>
                            <div>
                                <label>{$lang.bank_name}</label>
                                <select id="bank_name"  name="bank_name">
                                    <option value="">Select</option>

                                     {foreach from=$bank_names item=bank}
                                         <option {if $bank.status=='D' && $disable_bank_status=='-1'} class="disabled_bank_names" {/if} value="{$bank.payment_option_id}">{$bank.name}</option>
                                          {if $bank.status=='D'}
                                            {assign var="disable_bank_status" value="2"}
                                         {/if}
                                    {/foreach}                
                                </select>
                            </div>
                            <div id="other_bank" style="display : none">
                                <label>{$lang.other_bank_name}</label>
                                <input type="text" name="other_bank" id="other_bank_nm">  
                            </div>                                    
                            <div>
                                <label>{$lang.bank_account_type}</label>
                                <select name="bank_type" id="bank_type">
                                    <option value="">Select</option>
                                    <option value="Savings">Savings</option>
                                    <option value="Current">Current</option>
                                    <option value="Any Other">Any Other</option>
                                </select>
                            </div>
                            <div>
                                <label>{$lang.account_no}</label>
                                <input type="text" id="account_no" name="account_no" onchange="validateinteger(this.value);">
                            </div>
                            <div>
                                <label>{$lang.ifsc_code}</label>
                                <input type="text" id ="ifsc_code" name="ifsc_code" onchange="ifscalphanumeric(this.value);">
                            </div>
                            <div>
                                <label>{$lang.bank_branch}</label>
                                <input type="text" name="bank_branch" id="bank_branch">
                            </div>
                        </div>                    
                {/if}

            </div>

            <div>
                <label>{$lang.shipping_mode}</label>
                <div class="options">
                    <input type="radio" name="shipping_mode" value="cust_will_send_product" />
                    <label>{$lang.i_will_take_care_of_sending_the_product}</label>

                    <div class="clearboth no_desktop"></div>

                    <input type="radio" name="shipping_mode" value="pick_up_frm_my_shipping_add" />
                    <label>{$lang.pick_up_product_from_my_shipping_address}</label>
                </div>
            </div>

            <div>
                <label>{$lang.comments}</label>
                <textarea name="comment"></textarea>
            </div>

        </div>

        <div>                
            {include file="buttons/button.tpl" but_text=$lang.rma_return but_name="dispatch[rma.add_return]"  but_meta="cm-process-items"}
        </div>

    </form>

</div>

{capture name="mainbox_title"}{$lang.return_registration}{/capture}

{literal}
    <script type="text/javascript">
        var payment_cod_id = {/literal}'{$order_info.payment_id}'{literal}
        function showhiderefundDiv(action) {
            if (payment_cod_id == 6) {
                document.getElementById("cod_order_case").style.display = 'none';
            }
            $('input[name="refund_mode_cod"]').removeAttr("checked");
            $('input[name="refund_mode"]').removeAttr("checked");

            if (action == "2") {
                document.getElementById("rma_refund").style.display = 'block';
                $('input[type=radio][name="refund_mode"]').attr('disabled', false);
            } else {
                document.getElementById("rma_refund").style.display = 'none';
                $('input[type=radio][name="refund_mode"]').attr('disabled', true);
                //$('input[type=radio][name="refund_mode_cod"]').removeAttr("checked");
            }

        }


        $('#refund_reason').change(function() {
            var reason_val = $(this);
            var picv = $('option:selected', this).attr('pic');
            $('#picture_req').val(picv);

            var aprv_req = $('option:selected', this).attr('apv_req');
            $('#apv_req').val(aprv_req);

            var sc_aprv_req = $('option:selected', this).attr('sc_apv_req');
            $('#sc_apv_req').val(sc_aprv_req);

            var mc_aprv_req = $('option:selected', this).attr('mc_apv_req');
            $('#mc_apv_req').val(mc_aprv_req);

            var cust_message = $('option:selected', this).attr('cust_msg');
            $('#cust_msg').val(cust_message);

        });

        function show_description_div(id)
        {
            $('.description_block_div').hide();
            if ($('#description_block_' + id).length) {
                $('#description_block_' + id).show();
            }

        }

        function rmaForm()
        {

            if (!$('#refund_reason').val()) {
                alert('{/literal}{$lang.reason_must_be_selected}{literal}');
                return false;
            }

// Modification by Shahid begins here

            if (document.getElementById("product_pic").value == "")
            {
                if ($("#refund_reason option:selected").attr("pic") == 'Y')
                {
                    alert('{/literal}{$lang.product_picture_must_be_uploaded}{literal}');
                    return false;
                }
            }
            else
            {
                var filename = document.getElementById("product_pic").value;
                // ristrict more than 256KB files to upload

                var sizeinbytes = document.getElementById('product_pic').files[0].size;
                var sizeinkb = sizeinbytes / 1024;

                if (sizeinkb > 256)
                {
                    alert('{/literal}{$lang.file_size_too_large_should_be_less_than_256KB}{literal}');
                    return false;
                }

                // extracting file extension in the filetype, example jpg, png.
                var filetype = filename.substr((filename.lastIndexOf('.') + 1));
                var validtype = 'No';
                if (filetype == 'GIF' || filetype == 'gif' || filetype == 'jpeg' || filetype == 'JPEG' || filetype == 'jpg' || filetype == 'JPG' || filetype == 'png' || filetype == 'PNG') {
                    validtype = 'Yes';
                } else {
                    validtype = 'No';
                }
                // update this line to allow other filetypes

                if (validtype == 'No')
                {
                    alert('{/literal}{$lang.uploaded_picture_filetype_must_be_jpg_or_png_only}{literal}');
                    return false;
                }
            }

// Modification by Shahid ends here

            if (!$("input[type=radio][name='action']:checked").val()) {
                alert('{/literal}{$lang.refund_action_must_be_filled_out}{literal}');
                return false;
            }
            else if ($("input[type=radio][name='action']:checked").val() == 2)
            {
                if (payment_cod_id != 6) {
                    if ($("input[type=radio][name=refund_mode]:checked").length == 0) {
                        alert('{/literal}{$lang.refund_mode_must_be_filled_out}{literal}');
                        return false;
                    }
                }
            }

            if ((payment_cod_id == 6) && ($("input[type=radio][name='action']:checked").val() == 2)) {
                if ($("input[type=radio][name='refund_mode_cod']:checked").length == 0) {
                    alert('{/literal}{$lang.refund_mode_must_be_filled_out}{literal}');
                    return false;
                }
            }
            if ($("input[type=radio][name='refund_mode_cod']:checked").val() == "refund_in_ac") {

                if ($('#account_name').val() == "") {
                    alert('{/literal}{$lang.account_holder_name_must_be_filled}{literal}');
                    return false;
                }
                if ($('#bank_name').val() == "") {
                    alert('{/literal}{$lang.bank_name_must_be_selected}{literal}');
                    return false;
                }

                if ($('#bank_name').val() == "others")
                {
                    if ($('#other_bank_nm').val() == "")
                    {
                        alert('{/literal}{$lang.other_bank_name_must_be_filled}{literal}');
                        return false;
                    }
                }
                if ($('#bank_type').val() == "") {
                    alert('{/literal}{$lang.bank_account_type_must_be_selected}{literal}');
                    return false;
                }

                if ($('#account_no').val() == "") {
                    alert('{/literal}{$lang.account_no_must_be_filled}{literal}');
                    return false;
                }

                if ($('#ifsc_code').val() == "") {
                    alert('{/literal}{$lang.ifsc_code_must_be_filled}{literal}');
                    return false;
                }

                if ($('#bank_branch').val() == "") {
                    alert('{/literal}{$lang.bank_branch_must_be_filled}{literal}');
                    return false;
                }
            }
            if ($("input[type=radio][name=shipping_mode]:checked").length == 0)
            {
                alert('{/literal}{$lang.shipping_mode_must_be_filled_out}{literal}');
                return false;
            }

        }

        $('#bank_name').change(function() {
            if ($('#bank_name').val() == "others") {
                $('#other_bank').show();
            } else
            {
                $('#other_bank').hide();
            }
        });

        function clearFileInputField1() {
            $("#product_pic").val("");
            $('#image_remove').hide();
        }

        function clearFileInputField() {
            var img_path = $("#product_pic").val();
            if (!img_path)
            {
                $('#image_remove').hide();
            }
            else {
                $('#image_remove').show();
            }
        }

        function cod_returns_case(value) {
            if (value == 'refund_in_ac') {
                $('#cod_order_case').show();
            } else {
                $('#cod_order_case').hide();
            }
        }

        function validateinteger(chk)
        {
            if ($('#account_no').val()) {
                var tomatch = /^\d+$/;
                if (tomatch.test(chk))
                {
                    return true;
                }
                else
                {
                    alert('Account no. has to be numeric');
                    document.getElementById('account_no').value = "";
                    return false;
                }
            }
        }
        function validatecharacter(chk)
        {
            var tomatch = /^[a-zA-Z ]+$/;

            if (tomatch.test(chk))
            {
                return true;
            }
            else
            {
                alert('name should contain only alphabetical characters only');
                document.getElementById('account_name').value = "";
                exit;
                return false;
            }

        }

        function ifscalphanumeric(chk)
        {
            var tomatch = /((^[0-9]+[a-z]+)|(^[a-z]+[0-9]+))+[0-9a-z]+$/i;

            if (chk != '') {
                if (tomatch.test(chk))
                {
                    return true;
                }
                else
                {
                    alert('ifsc code should be alphanumeric only');
                    document.getElementById('ifsc_code').value = "";
                    exit;
                    return false;
                }
            }
        }

    </script>
{/literal}