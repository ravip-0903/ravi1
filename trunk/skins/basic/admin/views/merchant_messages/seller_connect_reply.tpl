<h1 class="mainbox-title">
                    {$lang.seller_connect_message_reply}
            </h1>
       

{assign var="merchant_company_name" value=$smarty.session.auth.company_id|fn_get_merchant_company_name}

<div class="rcnt_msg_block">

    <span class="lft_col_msg_nl">{$lang.from} :</span>
    <span>{$message_thread.firstname|cat:' '|cat:$message_thread.lastname[0]}</span>
    <span class="time_stamp_rj" style="float:right; clear:none; ">{$message_thread.timestamp|date_format:"%d-%b-%Y %H:%M %p"}</span>

</div>


<!--<div class="rcnt_msg_block">

    <span class="lft_col_msg_nl">{$lang.date_field}</span>
    <span>{$message_thread.timestamp|date_format:"%d-%b-%Y %H:%M %p"}</span>

</div>-->

<div class="rcnt_msg_block">

    <span class="lft_col_msg_nl">{$lang.subject} :</span>
    <span class="lft_col_msg_nl_sub">{$message_thread.subject}</span>

</div>

{if !empty($product_complete_name)}
<div class="rcnt_msg_block">

    <span class="lft_col_msg_nl">{$lang.product_name} :</span>
    <span><a href="vendor.php?dispatch=products.update&product_id={$message_thread.product_id}">{$product_complete_name}</a></span>

</div>
 {/if}

    
<div class="rcnt_msg_block">

    <span class="lft_col_msg_nl">{$lang.topic} :</span>
    <span>{$topic}</span>

</div>

 <div style="clear:both"></div>
  <div class="message_blk_nl">     
      <div class="msg_body_title" style="font-weight:bold; border:0;">{$message_thread.message}</div>

    {foreach from=$message item='message'}
     <div class="msg_body_title"> 
         {if $message.direction=='M2C'}
              
             <span class="span_hover_nl">{$message.company}</span>
              
            {elseif $message.direction=='C2M'}
                
                <span class="span_hover_nl">{$message_thread.firstname|cat:' '|cat:$message_thread.lastname[0]}</span>
                
             {/if}
             
        <span class="msg_rep_rj">{$message.message}</span>
        <span class="time_stamp_rj">{$message.timestamp|date_format:"%d-%b-%Y %H:%M %p"}</span>
     </div>   
         {if empty($message.open_timestamp)}
            
             {assign var="update_open_timestamp" value=$smarty.request.thread_id|fn_update_child_thread_timestamp:$current_timestamp}

            {/if}
            
        {/foreach}
     </div>
         
  <div class="cont_nl_address_box">
    
    <form action="{""|fn_url}" method="post" id="user_message_reply"  >
          
        <input type="hidden" name="customer_id" value="{$message_thread.customer_id}" />
        <input type="hidden" name="thread_id" value="{$smarty.request.thread_id}" />
        <input type="hidden" name="customer_email" value="{$message_thread.email}" />
        <input type="hidden" name="topic_id" value="{$message_thread.topic}" />
        <input type="hidden" name="merchant_name" value="{$merchant_company_name}" />
        <input type="hidden" name="product_id" value="{$message_thread.product_id}" />
        
        <label class="cm-required" for="merchant_reply"></label>
        <textarea rows="5" cols="60" name="merchant_reply" id="merchant_reply" style="margin-top:5px;" ></textarea>
        <div style="clear:both"></div>
        <div style="float: left; clear: both; margin-left: 375px;">
        <span class="submit-button cm-button-main" >
            <a href="vendor.php?dispatch=merchant_messages.seller_connect" style="cursor:pointer;float:left;margin:8px 9px 0 0;">{$lang.cancel}
            </a>
            
            <input class="box_functions_button" type="submit" name="dispatch[merchant_messages.seller_connect]" value="Reply" style="float:left;margin-top:4px;" >
            
            
       
        </span></div>

    </form>
    
 </div>
