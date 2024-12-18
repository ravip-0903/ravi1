 
<table class="message_root">
    
    <tr>
        
        <td width="15%" valign="top" class="message_root_left">
            <div id="navigation" class="cm-j-tabs">
                <ul>
                    <li class="message_root_li"><span class="message_root_span"><a href="vendor.php?dispatch=merchant_messages.seller_connect">{$lang.view_all_messages}</a></span></li>
                    {*$message_count|var_dump*}
                    {foreach from=$message_count item='cat_mail_count'}
                            {*assign var="total_val" value = $total_val+$cat_mail_count.message_type_count*}
                            <li class="message_root_li"><span class="message_root_span"><a href="vendor.php?dispatch=merchant_messages.seller_connect&cat_id={$cat_mail_count.topic}">{$cat_mail_count.topic_name}<label class="message_root_label" style="font-weight:bold; margin-left:4px;   ">({$cat_mail_count.total_message})</label></a></span></li>
                    {/foreach}
                </ul>
            </div>
         
                
        </td>
        <td width="85%" valign="top"  class="message_root_right">
            <h1 class="mainbox-title" style="padding-bottom: 5px;">
                    {$lang.seller_connect_message_listing}
            </h1>
    
             {if !empty($smarty.request.cat_name)}
                 
                   <span class="message_headline_display">{$smarty.request.cat_name|replace:'amp;':''} {if !empty($smarty.session.auth.company_id)}: ({$smarty.request.cat_count}) {/if}</span>
                   
                   {else}
                       
                       <span class="message_headline_display">{*{$lang.total_messages} {if !empty($smarty.session.auth.company_id)} :({$total_val}) {/if}*} </span>
                 
                   {/if}
                 
                   <div class="message_sort_header table" >
                    <a class="sort_type " style="width:15%;display: inline-block;" href="vendor.php?dispatch=merchant_messages.seller_connect{if $smarty.request.cat_id}&cat_id={$smarty.request.cat_id}{/if}&field=from&order={$order}">{$lang.from}</a>
                    <a class=" msg_box" style="width:30%;" href="vendor.php?dispatch=merchant_messages.seller_connect&field=subject{if $smarty.request.cat_id}&cat_id={$smarty.request.cat_id}{/if}&order={$order}">{$lang.subject}</a>
                    <a class="sort_type" style="padding-left:0; width:15%;display: inline-block;" href="vendor.php?dispatch=merchant_messages.seller_connect{if $smarty.request.cat_id}&cat_id={$smarty.request.cat_id}{/if}&field=date&order={$order}">{$lang.date_received}</a>
                    <a class=" msg_type_box" style="width:15%;" href="vendor.php?dispatch=merchant_messages.seller_connect{if $smarty.request.cat_id}&cat_id={$smarty.request.cat_id}{/if}&field=topic&order={$order}">{$lang.topic}</a>
                    <a class=" msg_type_box" style="width:15%;" href="vendor.php?dispatch=merchant_messages.seller_connect{if $smarty.request.cat_id}&cat_id={$smarty.request.cat_id}{/if}&field=ert&order={$order}">{$lang.expected_response_time}</a>

                {foreach from=$message_data item='message_value'}
                    
                     <a href="vendor.php?dispatch=merchant_messages.seller_connect_reply&thread_id={$message_value.thread_id}" {if $message_value.open_timestamp==0} style="background:#ebfaff;" {/if}    class="message_sort_data_loop">
                            <div class="sort_type" style="width:15%;display: inline-block;">{$message_value.firstname|cat:' '|cat:$message_value.lastname[0]}</div> 
                            <div class=" msg_box" style="width:28%;">{$message_value.subject}</div>
                            <div class="sort_type_2 " style="width:15%;display: inline-block;">{$message_value.timestamp|date_format:"%d-%b-%Y %H:%M %p"} </div>
                                <div class="msg_box" style="width:13%;"> {$message_value.name} </div>
                                <div class="sort_type_2 " style="width:15%; padding-left:0; display: inline-block;">{$message_value.timestamp+$config.expected_response_time|date_format:"%d-%b-%Y"} </div>
                            
                        </a>
                        {/foreach}

            </div>
            
        </td>

    </tr>

</table>

                    
{literal}
    
    <script>
        
        $(document).ready(function() {
            
               $('#main_column').removeAttr('id');
            
            });
        
        </script>
    
    
    {/literal}