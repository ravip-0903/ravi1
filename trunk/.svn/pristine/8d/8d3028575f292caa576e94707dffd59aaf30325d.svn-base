{* $Id: search.tpl 12605 2011-06-02 12:38:41Z angel $ *}


{assign var="c_url" value=$config.current_url|fn_query_remove:"sort_by":"sort_order"}
{if $search_write.sort_order == "asc"}
    {assign var="sort_sign" value="&nbsp;&nbsp;&#8595;"}
{else}
    {assign var="sort_sign" value="&nbsp;&nbsp;&#8593;"}
{/if}
{if $settings.DHTML.customer_ajax_based_pagination == "Y"}
    {assign var="ajax_class" value="cm-ajax"}

{/if}

{literal}
    <style>
        .expand_box{ display:none; background: none repeat scroll 0 0 #F8F8F8;
                     border: 1px solid #CCCCCC;
                     border-radius: 5px 5px 5px 5px;
                     box-shadow: 3px 3px 5px #C6EDFF;
                     color: #666666;
                     display: none;
                     font: 11px/16px Verdana,Geneva,sans-serif;
                     padding: 2px 10px;
                     position: absolute;
                     right: -24px;
                     text-align: center;
                     top: 12px;
                     width: 150px;
                     z-index: 10000;}
        .not_eligible{position:relative;}
        .not_eligible:hover .expand_box{ display:block}

    </style>
{/literal}
<div style="width:99%; height:300px; {if !empty($orders_write) } height:300px; {else} height:145px;{/if}
     border-radius:0; -moz-border-radius:0;  background:none;  
     padding:0px 0 0 10px;overflow-x: hidden; margin:0 0 10px;
     overflow-y: scroll;">

    <div class="box_headerTwo">
        <h1 class="box_headingTwo">{$lang.select_order_from_list}</h1>

    </div>
    <div class="clearboth height_ten"></div>
    {include file="common_templates/pagination.tpl"}
    {assign var="i" value=0}
{*<pre>{$orders_write|print_r}</pre>*}
    {* Products In Mobile View*}
    <div class="customerSupport_Products">
        {foreach from=$orders_write item="o"}
            <div class="products">
                {assign var="order_id" value=$o.order_id}
                <div class="selection">                    
                    <input type="radio" id="checked_id" name="checked_id" value="{$o.order_id}" onclick="setOrderId('{$o.status}',{$o.order_id}, '{$return_or http://192.168.1.200:8080/svn/frontend/branches/www-352ders_write.$order_id}');">
                </div>
                <input type="hidden" name="cname" id="cname" value="{$o.firstname}{$o.lastname}">
                <input type="hidden" name="cemail" id="cemail" value="{$o.email}">
                <input type="hidden" name="user_sess_email" id="user_sess_email" value="{$user_sess_email}">
                <input type="hidden" name="cphone" id="cphone" value="{$o.phone}">

                <div class="asideLeft">
                    <a href="{"orders.details?order_id=`$o.order_id`"|fn_url}">
                        Order ID: {$o.order_id}
                    </a>
                    
                    <div>
                       {*bbbb <pre>{$orders_item_write|print_r}</pre>kkk*}
                        {foreach from=$orders_item_write[$i] item='order_detail' }
                            {$order_detail.product}&nbsp;&nbsp;({$lang.order_qty}:{$order_detail.amount})
                            <input type="hidden" name="cproducts" id="cproducts" value="{$order_detail.product}&nbsp;&nbsp;({$lang.order_qty}:{$order_detail.amount})">		
                            {if $o.pdd_edd}
                                {$lang.edd} {$o.pdd_edd.edd1} {$lang.pdd_mid} {$o.pdd_edd.edd2}
                            {/if}
                        {/foreach}                    
                    </div>
                    
                    <a href="#" class="b_moreDetails" >More Details</a>
                </div>
                <div class="asideRight">
                    <a href="{"orders.details?order_id=`$o.order_id`"|fn_url}">
                        {$o.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}
                    </a>
                    <div>
                        {include file="common_templates/status.tpl" status=$o.status display="view"}                    
                    </div>
                    <div>
                    {assign var="rma_status" value=$o.order_id|fn_get_return_status}
                    
                    {assign var="rma_id" value=$o.order_id|fn_get_return_id}
                    {assign var="get_gift_certificate" value=$o.order_id|fn_get_purchased_gift_certificates}
                    {if !empty($get_gift_certificate)}
                        {foreach from=$get_gift_certificate item="gift_certificate"}
                            Gift Certificate{if $o.status !='N'}({$gift_certificate.gift_cert_code}){/if}         
                        {/foreach}
                    {/if}


                    {include file="common_templates/price.tpl" value=$o.total}
                    <br />



                    <input type="hidden" name="cstatus" value="{include file="common_templates/status.tpl" status=$o.status display="view"}">

                    <a href="{"orders.reorder?order_id=`$o.order_id`"|fn_url}">{$lang.re_order}</a>

                    {assign var="order_id" value=$o.order_id}
                    {if $return_orders_write.$order_id=='Y'}
<br />
                        <a href="{"rma.create_return?order_id=`$o.order_id`"|fn_url}">
                            {$lang.return_registration}
                        </a>
                    {elseif $return_orders_write.$order_id=='E'}
                        
                        {if $lang.return_expired!=''}


                            {$lang.return_expired}
                            {$lang.return_expired_order_text}
                        {/if}
                    {/if}

                    {foreach from=$rma_id.returns item=rm_id key=id}
                        {assign var="rm_id" value=$id}
                    {/foreach}
                    {if ! empty($rma_status)}
                        <a href="{"rma.print_slip?return_id=`$rm_id`"|fn_url}">{$lang.print_shipping}</a>
                        <a href="http://cdn.shopclues.com/images/banners/Return_Instructions.pdf" target="_blank">{$lang.return_guidelines}</a>
                    {/if}

                    {if $o.status|in_array:$config.show_feedback_link_status}
                        {assign var="feedback_status" value=$o.order_id|fn_get_feedback_posting_status}

                        {if $feedback_status}
                            {$lang.feedback_posted|unescape}                        
                        {/if}
                    {/if}
                    
                    
                    {if !empty($get_gift_certificate)}
                    {else}
                        {if $o.allow_cancelation=='Y'}
                        {/if}
                    {/if}

                    {assign var="i" value=$i+1}
                </div>
                </div>

                

            </div>        
        {foreachelse}
            {$lang.text_no_orders}
        {/foreach}
    </div>
    {*End Products In Mobile View*}

    <table class="no_mobile table" border="0" cellpadding="0" cellspacing="0" width="100%" id="order_history_table">
        <tr>

            <th class="center">
                <input type="hidden" name="hh" >
            </th>
            <th width="20%"><a class="{$ajax_class}" href="{"`$c_url`&amp;sort_by=date&amp;sort_order=`$search_write.sort_order`"|fn_url}" rev="pagination_contents">{$lang.oh_order_date}</a>{if $search_write.sort_by == "date"}{$sort_sign}{/if}</th>
            <th width="10%"><a class="{$ajax_class}" href="{"`$c_url`&amp;sort_by=order_id&amp;sort_order=`$search_write.sort_order`"|fn_url}" rev="pagination_contents">{$lang.oh_order_number}</a>{if $search_write.sort_by == "order_id"}{$sort_sign}{/if}</th>


            <th width="30%"><a class="{$ajax_class}">{$lang.oh_products}</a></th>


            <th width="10%" class="right"><a class="{$ajax_class}" href="{"`$c_url`&amp;sort_by=total&amp;sort_order=`$search_write.sort_order`"|fn_url}" rev="pagination_contents">{$lang.oh_total}</a>{if $search_write.sort_by == "total"}{$sort_sign}{/if}</th>
            <th width="15%"><a class="{$ajax_class}" href="{"`$c_url`&amp;sort_by=status&amp;sort_order=`$search_write.sort_order`"|fn_url}" rev="pagination_contents">{$lang.status}</a>{if $search_write.sort_by == "status"}{$sort_sign}{/if}</th>
            <th width="14%">
                {$lang.action}
            </th>
        </tr>
{assign var="i" value=0}

        {foreach from=$orders_write item="o"}
            <tr {cycle values=",class=\"table-row\""}>
                {assign var="order_id" value=$o.order_id}
                <td align="center">
                    <input type="radio" id="checked_id" name="checked_id" value="{$o.order_id}" onclick="setOrderId('{$o.status}',{$o.order_id}, '{$return_orders_write.$order_id}');">
                </td>

            <input type="hidden" name="cname" id="cname" value="{$o.firstname}{$o.lastname}">
            <input type="hidden" name="cemail" id="cemail" value="{$o.email}">
            <input type="hidden" name="user_sess_email" id="user_sess_email" value="{$user_sess_email}">
            <input type="hidden" name="cphone" id="cphone" value="{$o.phone}">

            <td><a href="{"orders.details?order_id=`$o.order_id`"|fn_url}">{$o.timestamp|date_format:"`$settings.Appearance.date_format`, `$settings.Appearance.time_format`"}</a></td>
            <td class="center" {if $o.ff_priority == 'Y'} alt="{$o.priority_level_name.priority_level_name}" title="{$o.priority_level_name.priority_level_name}" style="background:url({$o.priority_level_name.icon_url}) left center no-repeat; padding:10px; background-size:24px;"{/if}><a href="{"orders.details?order_id=`$o.order_id`"|fn_url}"><strong>{$o.order_id}</strong></a></td>

            <td>

                {foreach from=$orders_item_write[$i] item='order_detail' }
                    
                    <ul class="no-markers">
                        <li>{$order_detail.product}&nbsp;&nbsp;({$lang.order_qty}:{$order_detail.amount})</li>
                        <input type="hidden" name="cproducts" id="cproducts" value="{$order_detail.product}&nbsp;&nbsp;({$lang.order_qty}:{$order_detail.amount})">

                        {if $o.pdd_edd}
                            <li style="color: #007AC0;font-size:10.6px;">{$lang.edd} {$o.pdd_edd.edd1} {$lang.pdd_mid} {$o.pdd_edd.edd2} </li>
                            {/if}

                    </ul>   
                {/foreach}

                {assign var="rma_status" value=$o.order_id|fn_get_return_status}
                {assign var="rma_id" value=$o.order_id|fn_get_return_id}
                {assign var="get_gift_certificate" value=$o.order_id|fn_get_purchased_gift_certificates}
                {if !empty($get_gift_certificate)}
                    <ul class="no-markers">
                        {foreach from=$get_gift_certificate item="gift_certificate"}
                            <li>Gift Certificate{if $o.status !='N'}({$gift_certificate.gift_cert_code}){/if}</li>         

                        {/foreach}
                    </ul>
                {/if}
            </td>


            <td class="right">{include file="common_templates/price.tpl" value=$o.total}</td>
            <td style="text-align:center">
                {*{if $o.status == 'N'}
                {$lang.incomple_order_message}
                {else}*}
                {include file="common_templates/status.tpl" status=$o.status display="view"}
                {* {/if}*}
                <input type="hidden" name="cstatus" value="{include file="common_templates/status.tpl" status=$o.status display="view"}">
            </td>
            <td >
                <a href="{"orders.reorder?order_id=`$o.order_id`"|fn_url}">{$lang.re_order}</a>

                {assign var="order_id" value=$o.order_id}
                {if $return_orders_write.$order_id=='Y'}
                    <br/>
                    <br/>
                    <a href="{"rma.create_return?order_id=`$o.order_id`"|fn_url}">
                        {$lang.return_registration}
                    </a>
                {elseif $return_orders_write.$order_id=='E'}
                    {if $lang.return_expired!=''}
                        <br/>
                        <br/>
                        <div class="not_eligible" style="cursor:pointer">{$lang.return_expired}
                            <div class="expand_box">{$lang.return_expired_order_text}</div>
                        </div>
                    {/if}
                {/if}

                {foreach from=$rma_id.returns item=rm_id key=id}
                    {assign var="rm_id" value=$id}
                {/foreach}
                {if ! empty($rma_status)}
                    <a href="{"rma.print_slip?return_id=`$rm_id`"|fn_url}">{$lang.print_shipping}</a>
                    <a href="http://cdn.shopclues.com/images/banners/Return_Instructions.pdf" target="_blank">{$lang.return_guidelines}</a>
                {/if}

                {if $o.status|in_array:$config.show_feedback_link_status}
                    {assign var="feedback_status" value=$o.order_id|fn_get_feedback_posting_status}
                    <br/>
                    <br/> 
                    {if $feedback_status}
                        {$lang.feedback_posted|unescape}
                    {else}
                        <a class="cm-ajax" href="index.php?dispatch=orders.show_feedback_form&order_id={$o.order_id}" onClick="return false">{$lang.post_feedback}</a>

                    {/if}
                {/if} 
                {if !empty($get_gift_certificate)}
                {else}
                    {if $o.allow_cancelation=='Y'}
                        <br/>
                        <br/>
                        <a href="index.php?dispatch=orders.get_cancel_content&order_id={$o.order_id}" class="cm-ajax" onClick="return false">{$lang.cancelation_request}</a>
                    {/if}
                {/if}
            </td>
            {assign var="i" value=$i+1}
            </tr>
        {foreachelse}
            <tr>
                <td colspan="7"><p class="no-items">{$lang.text_no_orders}</p></td>
            </tr>

        {/foreach}
        <tr class="table-footer">
            <td colspan="6">&nbsp;</td>
        </tr>
    </table>
    {include file="common_templates/pagination.tpl"}

{capture name="mainbox_title"}{$lang.orders}{/capture}
</div>

<div id="responePrg" class="hidden" style="margin-left:300px;">
    <p>Please Wait...</p>
    <img src="images/progress-bar.gif">
</div>

<div style="float:left; width:98%; background-color:#e5e5e5; padding:5px;">
    <input style="float:left;" type="checkbox" id="dont_know_order_id" name="dont_know_order_id" value="{$lang.dont_know_order_id}" onchange="javascript:addTextBox();">
    <div style="float:left; margin-left:5px; font-size:11px; color:#333; margin-top:2px;">{$lang.dont_know_order_id}</div>
</div>

<div class="status_msg" id="status_msg" style="color: #666666; float: left; font: 11px/20px verdana; margin: 2px 67px 19px 13px; width: 97%;">
</div>

<div id="still_issue_div" class="still_issue_div" style="float:left; width:98%; background-color:#e5e5e5; padding:5px; margin-top:20px;display:none;">
    <input style="float:left;" type="checkbox" id="still_have_issue" name="still_have_issue" value="{$lang.still_have_issue}" onchange="javascript:addIssueBox();" >
    <div style="float:left; margin-left:5px; font-size:11px; color:#333; margin-top:2px;">{$lang.still_have_issue}</div>
</div>

<div class="box_headerTwo" id="byer_protection" style="margin:5px 0px 10px 9px; display:none;">
    <span style="font: 11px verdana; color: #007AC0;">{$lang.order_is_no_more_byer_protection}</span>
</div>
</br>

{*Tell Us About Your Issue start here *}

<div id="select_order" style="color:#0a9ccc; margin-top:5px; float:left; width:100%;" >
    {$lang.select_order}
</div>

<div id="select_issue" style="display:none;">
    {$lang.contact_us_header_text|unescape}
    <form action="{""|fn_url}" method="post" name="profile_form" id="profile_form" enctype="multipart/form-data" onsubmit="return validateimg();">
<input type="hidden" name="token" value="{$smarty.session.form_token_value}"/>
        <div class="cnct_box_nl_left" style="background:none";>
            <div class="subheaders-group" style="margin:0 0 0 10px;">		
                <h4 class="subheader" style="font: bold 15px trebuchet ms; color: #EE811D;">
                    {$lang.tell_us_about_ur_issue} 
                </h4>

    <!-- <label for="email" class="cm-email cont_nl_address">{$lang.email}: <span class="red_astrik">*</span></label> -->
                <input type="hidden" name="email" id="email" size="55" value="" class="input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" onchange="javascript:emailEntered = true;" />


                <div class="cont_nl_address_box">
                    <label for="orderid" class="cont_nl_address">{$lang.orderid}: </label>
                    <input type="text" name="orderid" id="orderid" size="55" value=" " class="input-text round_five profile_detail_field cont_nl_inpt_width" onblur="javascript:OrderIdEntered = true;
                            showPostData('{$api_root}orderStatusContactUs.php', 'thisHASH4TST', this.form);
                            return false;" maxlength="100" />
                </div>


<!-- <label class="cm-trim cont_nl_address" for="name" >{$lang.name}:</label> -->
                <input type="hidden" name="name" id="name" size="55" value=" " class="input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" />


                <input type="hidden" name="status" id="status" value =" " />
                <input type="hidden" name="allow_return" id="allow_return" value =" " />

                <div class="cont_nl_address_box" id="add_textbox" style="display:none;">
                    <label for="phone" class="cm-phone cont_nl_address">{$lang.phone}:</label>
                    <input type="text" name="phone" id="phone" size="55" value=" " class="input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" onchange="javascript:phoneEntered = true;" />
                </div>

                <div class="cont_nl_address_box">
                    <label for="parent_issue" class="cont_nl_address cm-required">{$lang.issue_type}: <span class="red_astrik">*</span></label>
                    <select name="subject" id="parent_issue" class="round_five profile_detail_field cont_nl_slt_width" style="height:30px;" onchange="issue_type
                                    (this.value)" >
                        <option value="">Select</option>

                        {foreach from=$parent_issues item="parent_issue"}
                            <option name={$parent_issue.allow_free_text} value={$parent_issue.issue_id}>{$parent_issue.name}</option>
                        {/foreach}
                    </select>
                </div>

                <div id="rma_div_id" class="rma_div_id" style="display:none;">

                </div>
                <div id="duplicate_ticket" style="display:none;"></div>
                <div id="hide_issue">

                    {* Dynamically populate the contents *}

                    <div class="cont_nl_address_box" id="subissues" style="display:none;"> 

                    </div>

                    <div class="cont_nl_address_box" id="sub_subissues" style="display:none;">

                    </div>

                    <div class="cont_nl_address_box" id="sub_sub_subissues" >

                    </div>
<script type="text/javascript">
     if($('#subissues option:selected').val() != ''){
     //$("#hide_issue").show();
      $.ajax({
          type: "GET",
          url: " ",
          cache:false,
          data: {dispatch:'write_to_us.ajax_issues',child_id:$('#subissues option:selected').val()},
          error:function (data, textStatus, jqXHR) {
			  window.location.href="index.php?dispatch=write_to_us.error_notify";
			  }
      }).done(function(msg){
            
             if(msg != ''){
                 $('#sub_subissues').show();
                 $('#sub_subissues').html(msg);
				 $('#sub_sub_subissues').hide();
              }else{
                  $('#sub_subissues').html(msg);
				  $('#sub_sub_subissues').hide();
                  }
          });
          }
      });
	  
	$('#sub_subissues').live('change',function() {
     if($('#sub_subissues option:selected').val() != ''){
      $.ajax({
          type: "GET",
          url: " ",
          cache:false,
          data: {dispatch:'write_to_us.ajax_issues',child_child_id:$('#sub_subissues option:selected').val()},
          error:function (data, textStatus, jqXHR) {
			   window.location.href="index.php?dispatch=write_to_us.error_notify";
			  }
      }).done(function(msg){
            
             if(msg != ''){
                 $('#sub_sub_subissues').show();
                 $('#sub_sub_subissues').html(msg);
              }else{
                  $('#sub_sub_subissues').html(msg);
                  }
          });
          }
      });
	
</script>

                    {*End*}

                    <!-- code by arpit gaur to add the issue menu -->
                    {literal}
                        <script type="text/javascript">
                            $('#parent_issue').change(function() {

                                $.ajax({
                                    type: "GET",
                                    url: " ",
                                    cache: false,
                                    data: {dispatch: 'write_to_us.ajax_issues', parent_id: $('#parent_issue').val(), text: $('#parent_issue option:selected').text()},
                                    error: function(data, textStatus, jqXHR) {
                                        window.location.href = "index.php?dispatch=write_to_us.error_notify";
                                    }
                                }).done(function(msg) {

                                    if (msg == '') {
                                        //$('#duplicate_ticket').html('');
                                        //$('#duplicate_ticket').hide();
                                        $('#sub_subissues').hide();
                                        $('#sub_sub_subissues').hide();
                                        $('#subissues').html(msg);
                                    } else {
                                        //$('#duplicate_ticket').html('');
                                        //$('#duplicate_ticket').hide();
                                        $('#subissues').html(msg);
                                        $('#subissues').show();
                                        $('#sub_subissues').hide();
                                        $('#sub_sub_subissues').hide();

                                    }

                                });

                            });

                            $('#subissues').live('change', function() {

                                if ($('#subissues option:selected').val() != '') {
                                    //$("#hide_issue").show();
                                    $.ajax({
                                        type: "GET",
                                        url: " ",
                                        cache: false,
                                        data: {dispatch: 'write_to_us.ajax_issues', child_id: $('#subissues option:selected').val()},
                                        error: function(data, textStatus, jqXHR) {
                                            window.location.href = "index.php?dispatch=write_to_us.error_notify";
                                        }
                                    }).done(function(msg) {

                                        if (msg != '') {
                                            $('#sub_subissues').show();
                                            $('#sub_subissues').html(msg);
                                            $('#sub_sub_subissues').hide();
                                        } else {
                                            $('#sub_subissues').html(msg);
                                            $('#sub_sub_subissues').hide();
                                        }
                                    });
                                }
                            });

                            $('#sub_subissues').live('change', function() {
                                if ($('#sub_subissues option:selected').val() != '') {
                                    $.ajax({
                                        type: "GET",
                                        url: " ",
                                        cache: false,
                                        data: {dispatch: 'write_to_us.ajax_issues', child_child_id: $('#sub_subissues option:selected').val()},
                                        error: function(data, textStatus, jqXHR) {
                                            window.location.href = "index.php?dispatch=write_to_us.error_notify";
                                        }
                                    }).done(function(msg) {

                                        if (msg != '') {
                                            $('#sub_sub_subissues').show();
                                            $('#sub_sub_subissues').html(msg);
                                        } else {
                                            $('#sub_sub_subissues').html(msg);
                                        }
                                    });
                                }
                            });

                        </script>

                        <script type="text/javascript">
                            $('#profile_form').submit(function() {

                                var final_value = '';

                                //check for subissue
                                //if($('#subissues').length!=0)
                                final_value += $('#subissues').val();

                                //check for subissue
                                //if($('#sub_subissues').length!=0)
                                final_value += $('#sub_subissues').val();

                                //check for subissue
                                //if($('#sub_sub_subissues').length!=0)
                                final_value += $('#sub_sub_subissues').val();

                                $('#subissues').val(final_value);
                                //return false;;
                            });
                        </script>

                    {/literal}
                    <!-- code by arpit gaur ends here -->


                    <div class="cont_nl_address_box" >
                        <label for="message" class="cont_nl_address" >{$lang.message}: </label>

                        <div class="cont_nl_address_box" style="padding:0;">
                            <textarea name="message" id="message" rows="3" cols="40" class="round_five profile_detail_field"></textarea>
                        </div>

                        <div id="uploadFile_div" style="float:left; margin:10px 0 0 0; width: 100%">
                            <input type="file" style="float:left; font-size: 11px;" class="fieldMoz" id="uploadFile" onkeydown="return false;" size="40" name="uploadFile[]" multiple="true" />
                            <a style="color: #ff0000; background: #fff; box-shadow: 1px 1px 1px #ccc; border-radius: 11px; width: 20px; height: 20px; float: right; text-align: center; vertical-align: middle; line-height: 18px;" onclick="clearFileInputField('uploadFile_div')" href="javascript:noAction();">x</a>
                        </div>

                    </div>


                    <!-- code by arpit gaur for keeping the response message as hidden-->
                    <input type="hidden" id="custom_hidden_response" name="custom_hidden_response" value="" />
                    <!-- code by arpit gaur ends here -->


                </div>  
            </div>
            <div class="box_functions sup_act_btn" id="sub_button" >
                {include file="buttons/save.tpl" but_name="dispatch[write_to_us.write]" but_text="Submit" but_role="button_main" but_class="box_functions_button"}
                <span style="display:none; color:#999; float:left; margin-top:-43px; font-size:11px;">{$lang.request_submitted_to_cs}</span>
            </div>

        </div> 

    </form>
</div>
</div>

{*Added by shashikant to add a pop-up for comment upadte*}
<div class="dispatch_mnfst_x_blk" id="comment_update" style="display:none; width:500px; left:50%; top:140px; position:fixed; margin-left: -250px; background: #fff; padding: 10px; border-radius:5px; border:2px solid #ccc;">
    <img src="http://cdn.shopclues.com/skins/basic/customer/images/icons/close_popupbox.png"  class="cancel" style=" position: absolute; right: -12px; top: -13px;" />
    <form method="post" id="comment" name="comment_update_form" action ="{""|fn_url}" enctype="multipart/form-data" onsubmit="return encodeVal()">
<input type="hidden" name="token" value="{$smarty.session.form_token_value}"/>
        <div style="float:left; width:100%;"><h1 style="font:bold 15px trebuchet ms; color:#007ac0; padding:0px; margin:0px;">{$lang.Here_is_your_previous_issue}</h1></div>

        <div id="first_comment" name="first_comment" style="float:left; width:96%; font:13px trebuchet ms; background-color:#f5f5f5; padding:7px 10px;">

        </div>

        <div style="float:left; width:100%; margin-top:10px; font:bold 13px trebuchet ms; color:#333;">{$lang.Please_write_your_issue_in_the_below_comment_box}</div>


        <div class="cont_nl_address_box" style="width: 100%;">
            <label for="comment" class="cont_nl_address" style="width:70px;">Comment: </label>
            <textarea style="width: 415px; height: 100px;" id="comment_update_text" name="comment" maxlength="700" cols="40" rows="5" class="input-text round_five profile_detail_field cont_nl_inpt_width" ></textarea>
            <textarea rows="20" cols="70" name="mail_body" id ="text_body" style="display:none;"></textarea>
        </div>
        <div  style="display:inline-block; color:#ff0000;margin-left: 70px; float:left;   " id="errors_all"></div>

        <div id="uploadFile_divs" style="float:left; margin:5px 0 0 0;width: 348px;">
            <input type="file" style="float:left; font-size: 11px; width: 320px;" class="uploadFile1" id="uploadFile1" onkeydown="return false;" size="40" name="uploadFile[]" multiple="true"/>
            <a style="color: #ff0000; background: #fff; box-shadow: 1px 1px 1px #ccc; border-radius: 11px; width: 20px; height: 20px; float: right; text-align: center; vertical-align: middle; line-height: 18px;" onclick="clearFileInputFields('uploadFile_divs')" href="javascript:noAction();">x</a>
        </div>

        <span style="float:right;" class="button-submit">
            <input type="hidden" id="ticket" name="ticket_id"  value=" " />
            <input type="submit" value="Submit" name="dispatch[write_to_us.customer_comment_update]" /></span>
    </form>
</div>
{*End Added by shashikant to add a pop-up for comment upadte*}

{literal}
    <script type="text/javascript">

        //Added by shashikant to update comments in zendesk    
        function popup(ticket_id) {
            var com = $('#' + ticket_id).attr('param');
            $('#first_comment').html(com.trim());
            $("#comment_update").show();
            $('#errors_all').hide();
            document.getElementById('ticket').value = ticket_id;
            return false;
        }
        $('.cancel').click(function() {
            $('#comment_update').hide();
        });

        function encodeVal() {

            var filename = document.getElementById("uploadFile1").files || [];
            for (var i = 0; i < filename.length; i++) {
                var sizeinbytes = filename[i].size;
                var sizeinkb = sizeinbytes / 1024;
                if (sizeinkb > 250)
                {
                    alert(filename[i].name + {/literal}'{$lang.is_too_large_must_be_less_than_250kb}'{literal});

                    return false;
                }
                //Function to validate images when CS ticket is created
                var filetype = filename[i].name.substr((filename[i].name.lastIndexOf('.') + 1));
                var validtype = 'No';
                if (filetype == 'GIF' || filetype == 'gif' || filetype == 'jpeg' || filetype == 'JPEG' || filetype == 'jpg' || filetype == 'JPG' || filetype == 'png' || filetype == 'PNG') {
                    validtype = 'Yes';
                } else {
                    validtype = 'No';
                }

                if (validtype == 'No')
                {
                    alert('{/literal}{$lang.filetype_must_be_jpg_or_png_or_jpeg_or_gif_only}{literal}');
                    return false;
                }
            }
                
               return true;  
      }
     //Function to remove selected images 
     function clearFileInputField(tagId) {
          document.getElementById(tagId).innerHTML = document.getElementById(tagId).innerHTML;
      } 
     function clearFileInputFields(tagId) {
          document.getElementById(tagId).innerHTML = document.getElementById(tagId).innerHTML;
      } 
     //Validation on images by shashikant
     function validateimg(){
        var filename=document.getElementById("uploadFile").files || [];
        for (var i = 0; i < filename.length; i++) {  
	var sizeinbytes = filename[i].size;
	var sizeinkb = sizeinbytes/1024;
	if (sizeinkb > 250 )
	{ 
	  alert(filename[i].name + {/literal}'{$lang.is_too_large_must_be_less_than_250kb}'{literal});
          return false;
	}
        
  	var filetype=filename[i].name.substr( (filename[i].name.lastIndexOf('.') +1) );
  	var validtype='No';
  	if(filetype == 'GIF' || filetype == 'gif' || filetype == 'jpeg' || filetype == 'JPEG' || filetype == 'jpg' || filetype == 'JPG' || filetype == 'png' || filetype == 'PNG'){
		 validtype='Yes';
	}else{
		 validtype='No';
	}
 
       if(validtype=='No')
       {
    	alert('{/literal}{$lang.filetype_must_be_jpg_or_png_or_jpeg_or_gif_only}{literal}');
    	return false;
       }     
} 
     }
      
//End Added by shashikant to update comment in zendesk  
    
function setOrderId(status,oid,allow_return){
  $('#status_msg').html('');
  $('#duplicate_ticket').hide();
  $('#status').val(status);
  $('#orderid').val(oid);

            var message_body = $('#comment_update_text').val();
            document.getElementById('text_body').value = escape(message_body);
            $('#errors_all').hide();

            if (message_body == '' || message_body == 'null')

            {
                document.getElementById('errors_all').innerHTML = 'Please Enter Your Comments';
                $('#errors_all').show();
                return false;
            }

            return true;
        }
        //Function to remove selected images 
        function clearFileInputField(tagId) {
            document.getElementById(tagId).innerHTML = document.getElementById(tagId).innerHTML;
        }
        function clearFileInputFields(tagId) {
            document.getElementById(tagId).innerHTML = document.getElementById(tagId).innerHTML;
        }
        //Validation on images by shashikant
        function validateimg() {
            var filename = document.getElementById("uploadFile").files || [];
            for (var i = 0; i < filename.length; i++) {
                var sizeinbytes = filename[i].size;
                var sizeinkb = sizeinbytes / 1024;
                if (sizeinkb > 250)
                {
                    alert(filename[i].name + {/literal}'{$lang.is_too_large_must_be_less_than_250kb}'{literal});
                    return false;
                }

                var filetype = filename[i].name.substr((filename[i].name.lastIndexOf('.') + 1));
                var validtype = 'No';
                if (filetype == 'GIF' || filetype == 'gif' || filetype == 'jpeg' || filetype == 'JPEG' || filetype == 'jpg' || filetype == 'JPG' || filetype == 'png' || filetype == 'PNG') {
                    validtype = 'Yes';
                } else {
                    validtype = 'No';
                }

                if (validtype == 'No')
                {
                    alert('{/literal}{$lang.filetype_must_be_jpg_or_png_or_jpeg_or_gif_only}{literal}');
                    return false;
                }
            }
        }

        //End Added by shashikant to update comment in zendesk  

        function setOrderId(status, oid, allow_return) {
            $('#status_msg').html('');
            $('#duplicate_ticket').hide();
            $('#status').val(status);
            $('#orderid').val(oid);

            $("#allow_return").val(allow_return);
            var rr = $('#allow_return').val();

            $("#byer_protection").hide();
            $("#add_textbox").hide();
            $("#select_order").hide();
            $('input[name="dont_know_order_id"]').removeAttr("checked");
            $('input[name="still_have_issue"]').removeAttr("checked");

            var cname = $('#cname').val();
            $('#name').val(cname);

            var cemail = $('#cemail').val();
            $('#email').val(cemail);

            var cphone = $('#cphone').val();
            $('#phone').val(cphone);

            $("#rma_div_id").hide();
            $('#parent_issue').val('');
            $("#subissues").val('');
            $("#sub_subissues").val('');
            $("#subissues").hide();
            $("#sub_subissues").hide();

            $('#responePrg').show();
            $("#select_issue").hide();
            $("#hide_issue").hide();
            $("#sub_button").hide();

            $("#still_issue_div").hide();

            $.ajax({
                type: "GET",
                url: "index.php",
                data: {dispatch: 'write_to_us.show_status_msg', order_id: oid},
                error: function() {
                    $('#responePrg').hide();
                    $("#still_issue_div").hide();
                    $("#select_issue").show();
                    $("#hide_issue").show();
                    $("#sub_button").show();
                }
            }).done(function(msg) {

                if (msg == '') {
                    $('#responePrg').hide();
                    $("#still_issue_div").hide();
                    $("#select_issue").show();
                    $("#hide_issue").show();
                    $("#sub_button").show();
                } else {
                    $('#responePrg').hide();
                    $('#status_msg').html(msg);
                    $("#still_issue_div").show();
                    $("#select_issue").hide();
                    $("#hide_issue").hide();
                    $("#sub_button").hide();
                }

            });

        }

        function addIssueBox() {
            $('#duplicate_ticket').html('');
            $('#subissues').html('');
            if (document.getElementById("still_have_issue").checked) {
                $("#select_issue").show();
                $("#hide_issue").show();
                $("#sub_button").show();
            } else {
                $("#select_issue").hide();
                $("#hide_issue").hide();
                $("#sub_button").hide();
            }
        }


        function addTextBox() {
            if (document.getElementById("dont_know_order_id").checked) {
                $('#duplicate_ticket').hide();
                $("#select_issue").show();
                $('input[name="checked_id"]').removeAttr("checked");
                $("#add_textbox").show();

                var user_sess_email = $('#user_sess_email').val();
                $('#email').val(user_sess_email);

                $('#orderid').val('');
                $('#phone').val('');
                $('#name').val('');
                $('#status').val('');
                $('#status_msg').html('');
                $('#parent_issue').val('');
                $("#subissues").val('');
                $("#sub_subissues").val('');

                $("#select_order").hide();
                $("#subissues").hide();
                $("#sub_subissues").hide();
                $("#byer_protection").hide();
                $("#still_issue_div").hide();

                $("#hide_issue").show();
                $("#sub_button").show();
                $("#select_issue").show();
                $("#add_textbox").show();
                $("#rma_div_id").hide();


            } else {
                $("#add_textbox").hide();
                $("#select_issue").hide();
                $("#select_order").show();
            }
        }
        //Added by shashikant to remove duplicate tickets
        function issue_type(issue_id) {
            $('#duplicate_ticket').html('');
            var ord_id = $('#orderid').val();
            var status = $('#status').val();
            var allow_return = $('#allow_return').val();
            if (issue_id != '') {
                $.ajax({
                    type: "GET",
                    url: "index.php",
                    data: {dispatch: 'write_to_us.duplicate_tickets', order_id: ord_id, issue: issue_id}
                }).done(function(msg) {
                    if (msg != '') {
                        $('#duplicate_ticket').html(msg);
                        $('#duplicate_ticket').show();
                        $("#hide_issue").hide();
                        $("#sub_button").hide();

                    } else {
                        $('#duplicate_ticket').hide();
                        $("#hide_issue").show();
                        $("#sub_button").show();
                        window.location.href = "index.php?dispatch=write_to_us.error_notify";
                    }
                });
            }
            //End Added by shashikant to remove duplicate tickets                                

            if (issue_id == '78') {
                var ord_id = $('#orderid').val();
                var status = $('#status').val();
                var allow_return = $('#allow_return').val();
                if ((status == 'C' || status == 'H') && allow_return == 'Y') {
                    $("#hide_issue").hide();
                    $("#sub_button").hide();
                    $("#rma_div_id").show();

                    $.ajax({
                        type: "GET",
                        url: "index.php",
                        data: {dispatch: 'write_to_us.issue_type', order_id: ord_id, st: status},
                        error: function(data, textStatus, jqXHR) {
                            window.location.href = "index.php?dispatch=write_to_us.error_notify";
                        }
                    }).done(function(msg) {
                        if (msg != '') {
                            $('#rma_div_id').html(msg);
                        } else {
                            window.location.href = "index.php?dispatch=write_to_us.error_notify";
                        }
                    });
                } else if (status == 'A') {

                    $("#hide_issue").hide();
                    $("#sub_button").hide();
                    $("#rma_div_id").show();

                    $.ajax({
                        type: "GET",
                        url: "index.php",
                        data: {dispatch: 'write_to_us.issue_type', order_id: ord_id, st: status},
                        error: function(data, textStatus, jqXHR) {
                            window.location.href = "index.php?dispatch=write_to_us.error_notify";
                        }
                    }).done(function(msg) {

                        if (msg != '') {
                            $('#rma_div_id').html(msg);
                        } else {
                            window.location.href = "index.php?dispatch=write_to_us.error_notify";
                        }
                    });

                } else {
                    $("#hide_issue").show();
                    $("#sub_button").show();
                    $("#rma_div_id").hide();
                    $("#byer_protection").show();
                }
            } else {
                $("#hide_issue").show();
                $("#sub_button").show();
                $("#rma_div_id").hide();
                $("#byer_protection").hide();
            }
        }
        function confirm_delivery(order, status) {

            //$('#dilivary_confirm').val($(this).is(':checked'));
            if (document.getElementById("dilivary_confirm").checked)
            {
                var conf = confirm('{/literal}{$lang.are_you_sure_to_create_return}{literal}')
                if (conf == true)
                {
                    $("#rma_div_id").hide();
                    $.ajax({
                        type: "GET",
                        url: "index.php",
                        data: {dispatch: 'write_to_us.confirm_delivery', order_id: order, st: status},
                        error: function(data, textStatus, jqXHR) {
                            window.location.href = "index.php?dispatch=write_to_us.error_notify";
                        }
                    }).done(function(msg) {
                        //$('#rma_div_id').html(msg);
                        window.location.href = "index.php?dispatch=rma.create_return&order_id=" + order;
                    });
                }
            }

        }



    </script>
{/literal}
