{literal}
    <style>
    .cont_nl_address_box{width:600px;}
    .sllr_cont_nl{color:#666; float:left; margin-left:125px; clear:both;margin-top:4px;}
    </style>
    {/literal}
    
<div class="subheaders-group ask_a_mrchnt_left" style="margin: 0;float: left;width: 50%;">		
	<h2 style="font: bold 22px trebuchet ms; color: #EE811D;padding: 0px 0 5px 0;
margin: 0 0 10px;">
	
	{$lang.seller_connect_page} 

	</h2>

<form action="{""|fn_url}" method="post" id="user_message_thread" >
 
    <input type="hidden" name="merchant_id" value={$merchant_id} />
    <input type="hidden" name="merchant_email" value={$merchant_email} />
    {if !empty($product_id)}
        <input type="hidden" name="product_id" value={$product_id} />
    {/if}
    <input type="hidden" name="mode_debug" value={$mode_debug} />
    <input type="hidden" name="customer_id" value={$customer_id} />
    <input type="hidden" name="user_name" value={$user_name} />
    <input type="hidden" name="product_name" value={$product_name} />
    <input type="hidden" name="company_id" value={$company_id} />
    <input type="hidden" name="token" value="{$smarty.session.form_token_value}"/>
 <div class="cont_nl_address_box">
    <label for="from" class="cm-required cont_nl_address">{$lang.from}:</label>
    <b>{*{$smarty.session.cart.user_data.firstname} {$smarty.session.cart.user_data.lastname} ({$smarty.session.cart.user_data.email})*}
    
    {$user_complete_data.firstname} {$user_complete_data.lastname} ({$user_complete_data.email})
    
    </b>
    <span class="foot_note_nl sllr_cont_nl">{$lang.email_desc_nl}</span>
</div>
   
 <div class="cont_nl_address_box">
    <label for="to_merchant" class="cm-required cont_nl_address">{$lang.to}:</label>
    
    {if $smarty.request.status=='success'}
        
       <a href="{"companies.view&company_id=`$merchant_id`"|fn_url}"><b>{$company_name}</b>
       </a> 
     {else}
         
         <b>{$company_name}</b>
         
         {/if}
</div>

{if !empty($smarty.request.product_id)}
    
<div class="cont_nl_address_box">
    <label for="to_merchant" class="cm-required cont_nl_address">{$lang.about}:</label>
    
    {if $smarty.request.status=='success'}
        
        <span style="float:left; width:62%;"> <a href="{"products.view&product_id=`$product_id`"|fn_url}"><b>{$product_name}</b></a></span>
        
        {else}
            
            <span style="float:left; width:62%;"><b>{$product_name}</b></span>
            
        {/if}
        
</div>

{/if}

<div class="cont_nl_address_box">
    
<label class="cont_nl_address email_input_field cm-required" for="message_type">{$lang.reason_for_contact}:</label>
{if $smarty.request.status == 'success'}
    <label class="cont_nl_address email_input_field cm-required" for="message_type"><b>{$message_success_option}</b></label>
    {else}
<select id="message_type" name="message_id">
            <option value="">{$lang.select}</option>
            
            {foreach from=$option_data item=message_type}
                 <option value="{$message_type.issue_id}">{$message_type.name}</option>
            {/foreach }
</select>
{/if}
</div>
 
<div class="cont_nl_address_box" style="display:none;">
    <label for="subject_merchant" class="cm-required cont_nl_address" >{$lang.subject}</label>
    <span style="float:left; width:62%;">{$lang.user_question_subject|replace:'[name]':$user_complete_data.firstname|replace:'[product_name]':$product_name}{*{$smarty.session.cart.user_data.firstname} has question about {$product_name} <span id="option_replace"></span></span>*}
    <input type="hidden" id="subject_msg" name ="subject" value="{$lang.user_question_subject|replace:'[name]':$user_complete_data.firstname|replace:'[product_name]':$product_name}" /> 
</div>
 

<div class="cont_nl_address_box">
    <label for="user_message" class="cm-required cont_nl_address">{$lang.your_message}:</label>
    {if $smarty.request.status == 'success'}
        <label for="user_message" class="cm-required cont_nl_address"><b>{$message_success}</b></label>
        {else}
        <textarea rows="10" cols="40" name="user_message" id="user_message" > </textarea>
        
        {/if}
    <span class="foot_note_nl sllr_cont_nl" style="width:337px;">{$lang.txt_area_desc_nl}</span>
</div>
    
<div class="box_functions " style="margin-top:0;padding-top:0;width:100px;float: left;margin-left: 359px; clear: both;">
    
    {if $smarty.request.status !='success'}
     
        
      {if !empty($smarty.request.company_id)}
        
            <a href="{"companies.view&company_id=`$company_id`"|fn_url}" style="cursor:pointer;float:left;margin:5px 0 0 0;">{$lang.cancel}
            </a>

             {else}
        
            <a href="{"products.view&product_id=`$product_id`"|fn_url}" style="cursor:pointer;float:left;margin:5px 0 0 0;">{$lang.cancel}
            </a>

         {/if}
    
        <input class="box_functions_button" type="submit" name="dispatch[products.seller_connect]" value="Send" style=" cursor:pointer;">
     
      {else}
          
          {if !empty($smarty.request.company_id)}
        
            <a href="{"companies.view&company_id=`$company_id`"|fn_url}"><input class="box_functions_button" type="button" value="Continue Shopping" style="cursor:pointer;">
            </a>

            {else}
        
            <a href="{"products.view&product_id=`$product_id`"|fn_url}"><input class="box_functions_button" type="button" value="Continue Shopping" style="cursor:pointer;">
            </a>

          {/if}
    
       {/if}
    
    
</div>

</form>

</div>
       
       <div class="ask_a_mrchnt_right" style="float:left; margin:5px 0 0 0; width:50%">{if $smarty.request.status == "success"} {$lang.what_will_happen_now} {else}{$lang.how_does_it_works}{/if}</div>
{literal}
    
    <script type="text/javascript">
    
     //$('.box_mainmenu').hide();
    $(document).ready(function(){
       
       //$('.box_mainmenu').remove();
       //$('.right-column').remove();
        
        
        $('.central-column').css('width','100%').css('margin','0');
            
        $('#message_type').change(function() {
         
        var option_name = $('#message_type option:selected').text();

          document.getElementById('option_replace').innerHTML= 'about' + ' '+ option_name;

       
     });
         
         });
   </script>
    
{/literal}