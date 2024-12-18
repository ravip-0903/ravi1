<div class="box_headerTwo">
    <h1 class="box_headingTwo">{$lang.user_query_reply}</h1>
</div>

{literal}
    <style>
        .rcnt_msg_block{width:99%; padding:5px 0 0 0 ; float:left;}
        .lft_col_msg_nl{width:170px; display:inline-block; float:left; padding:0px 0;}
        .lft_col_msg_nl_sub{width:600px;  display:inline-block;}
        .time_stamp_rj{float:left; padding:1px 5px;  color:#666; font-style:italic;}
        .message_blk_nl{margin-top:10px;}
        .msg_body_title{padding:5px; float:left; width:99%; clear:both; border-top:1px dotted #ccc; color:#333;}
        .msg_rep_rj{}
        .ahover_nl{}
        .box_functions_button{cursor:pointer;}
        .sllr_cont_nl{color:#666; float:left; margin-left:3px; clear:both;margin-top:4px;}
    
        </style>
    {/literal}

<div class="rcnt_msg_block">

    <span class="lft_col_msg_nl">{$lang.from} :</span>
    <span><a href="{"companies.view&company_id=`$message_thread.merchant_id`"|fn_url}" target="_blank">{$message_thread.company}</a></span>
    <span class="time_stamp_rj" style="float: right; padding:0;">{$message_thread.timestamp|date_format:"%d-%b-%Y %H:%M %p"}</span>

</div> 


<div class="rcnt_msg_block">

    <span class="lft_col_msg_nl">{$lang.subject} :</span>
    <span class="lft_col_msg_nl_sub">{$message_thread.subject}</span>

</div> 

{if !empty($product_complete_name)}  
    
<div class="rcnt_msg_block">

    <span class="lft_col_msg_nl">{$lang.product_name} :</span>
    <span><a href="{"products.view&product_id=`$message_thread.product_id`"|fn_url}" traget="_blank">{$product_complete_name}</a></span>

</div>
    
 {/if}

<div class="rcnt_msg_block">

    <span class="lft_col_msg_nl">{$lang.topic} :</span>
    <span>{$topic}</span>

</div>

    <div class="clearboth"></div>
<div style="margin-left:170px;">    
  <div class="message_blk_nl">     
  <div class="msg_body_title" style="font-weight:bold; border:0; padding:5px;  background:none;">{$message_thread.message}</div>
    
    {foreach from=$message item='message'}
     <div class="msg_body_title"> 
         {if $message.direction=='M2C'}
              
             <a class="a_hover_nl" href="{"companies.view&company_id=`$message.merchant_id`"|fn_url}" target="_blank">{$message.company}</a>
              
              {elseif $message.direction=='C2M'}
                  
                  <a class="ahover_nl" style="margin-right:5px; float:leftl" >{$smarty.session.cart.user_data.firstname|cat:' '|cat:$smarty.session.cart.user_data.lastname}</a>
               
             {/if}
             
             <span class="msg_rep_rj" style="background:#fff;">{$message.message}</span>
             <div class="clearboth"></div>
             <span class="time_stamp_rj" style="padding:5px 0 0 0;">{$message.timestamp|date_format:"%d-%b-%Y %H:%M %p"}</span>
         
     </div>   
         {if empty($message.open_timestamp)}
            
             {assign var="update_open_timestamp" value=$smarty.request.thread_id|fn_update_child_thread_timestamp:$current_timestamp}

            {/if}
            
        {/foreach}
   </div>  
       
  <div class="cont_nl_address_box" style="clear:both;">
    
    <form action="{""|fn_url}" method="post" id="user_message_reply"  >
          <input type="hidden" name="token" value="{$smarty.session.form_token_value}"/>
        <input type="hidden" name="merchant_id" value="{$message_thread.merchant_id}" />
        <input type="hidden" name="thread_id" value="{$smarty.request.thread_id}" />
        <input type="hidden" name="merchant_email" value="{$message_thread.email}" />
        <input type="hidden" name="topic_id" value="{$message_thread.topic}" />
        <input type="hidden" name="product_id" value="{$message_thread.product_id}" />
        <input type="hidden" name="user_name" value="{$user_name}" />
        <input type="hidden" name="subject" value="{$message_thread.subject}" />
        
        <span class="foot_note_nl sllr_cont_nl" style="width:494px;word-wrap:break-word;margin-bottom:4px;">{$lang.txt_area_desc_nl_top}</span>
        <label class="cm-required" for="user_reply"></label>
        <textarea style="border:1px solid #ddd; border-radius: 5px; -moz-border-radius: 5px;" rows="5" cols="60" name="user_reply" id="user_reply" > </textarea>
        <span class="foot_note_nl sllr_cont_nl" style="width:494px;word-wrap:break-word;">{$lang.txt_area_desc_nl}</span>
    
        <div>
        <span class="submit-button cm-button-main box_functions " style="margin-top:0; width:497px;">
    
            <input class="box_functions_button" type="submit" name="dispatch[profiles.user_query_response]" value="Reply" style="float:right; margin-top:0px;" >
             
            <a href="index.php?dispatch=profiles.user_query" style="cursor:pointer;float:right;margin:5px 9px 0 0;">{$lang.cancel}
            </a>

        
        </span></div>

    </form>
    
 </div>
</div>
            
            {literal}
    <script>
    $(document).ready(function(){
        $('.central-column').css('width','81%');
});
    </script>
    {/literal}