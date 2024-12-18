
{literal}
<style>
.form-field{padding:0 0 0 123px;}
.form-field label{font:13px/16px "Trebuchet MS", Arial, Helvetica, sans-serif; width:123px; margin-left:-123px;}
.form-field label.cm-required{font:13px/16px "Trebuchet MS", Arial, Helvetica, sans-serif; width:115px; margin-left:-123px;}
</style>
{/literal}

<div style="float:left; width:100%;">
<form action="{""|fn_url}" method="post" id="user_report_issue" >
{if $smarty.request.status != 'success'} 
<div class="usr_rprt_issue_left" style="width:500px; float:left;">
 <input type="hidden" name="merchant_id" value={$product_merchant_data.merchant_id} />
    <input type="hidden" name="product_id1" value={$product_merchant_data.product_id} />
    <input type="hidden" name="token" value="{$smarty.session.form_token_value}"/>
    <input type="hidden" name="customer_id" value={$customer_id} />
<h2 style="font: bold 22px trebuchet ms; color: #EE811D;padding: 0px 0 5px 0; margin: 0 0 10px;">{$lang.report_issue} </h2>
 <div class="form-field">
<label for="from" >{$lang.product}:</label>
  <a  href="{"products.view?product_id=`$product_merchant_data.product_id`"|fn_url}"><b>{$product_merchant_data.product_name}</b></a>
</div>
<div class="form-field">
<label for="from" >{$lang.merchant}:</label>
<a href="{"index.php?dispatch=companies.view&company_id=`$product_merchant_data.company_id`"|fn_url}"><b>{$product_merchant_data.company_name}</b>

       </a>
</div>
 <div class="form-field">
      <label for="type" class="cm-required" >{$lang.type}</label>
      <select name="type" id="type" style="float:left">
      {foreach from=$report_issue_type item=report_issue_type}
                 <option value="{$report_issue_type.name}">{$report_issue_type.name}</option>
            {/foreach }
         
       </select>
         </div>
  <div class="form-field">
		<label for="message" class="cm-required" >{$lang.message}:</label>
        <textarea   for="message" id= "message" class="message"  style="width:331px; max-width:331px;" rows="5" cols"150" name="message" ></textarea> 
    </div>  
    
    <div class="box_functions " style="margin-top:0;padding-top:0;width:100px;float: left;margin-left: 359px; clear: both;">
    <input type="hidden" name="dispatch" value="products.report_issue" />
    <input class="box_functions_button" type="submit" name="dispatch[products.report_issue]" value="Send" style=" cursor:pointer;">
           
        
             <a href="{"products.view&product_id=`$product_merchant_data.product_id`"|fn_url}" style="cursor:pointer;float:left;margin:5px 0 0 0;">{$lang.cancel}
            </a>
</div>
</div>   
<div class="usr_rprt_issue_right" style="float:left; width:500px;">{$lang.how_will_it_works}</div>
    {elseif $smarty.request.status == 'success'}
    <div style="float:left; width:500px;">
      <h2 style="font: bold 22px trebuchet ms; color: #EE811D;padding: 0px 0 5px 0; margin: 0 0 10px;">{$lang.your_reported_issue}</h2>
      <div class="form-field">
     <label for="from" >{$lang.product}:</label>
    <a  href="{"products.view?product_id=`$product_merchant_data.product_id`"|fn_url}"><b>{$product_merchant_data.product_name}</b></a>

     </div>
    <div class="form-field">
   <label for="from" >{$lang.merchant}:</label>
    <a href="{"index.php?dispatch=companies.view&company_id=`$product_merchant_data.company_id`"|fn_url}"><b>{$product_merchant_data.company_name}</b>
     </a>
       </div>
     <div class="form-field" >
      <label for="type"  >{$lang.type}</label>
      <span style="float:left; width:98%;">{$report_issue_data.type}</span>
      </div>
      <div class="form-field" >
      <label for="message"  >{$lang.message}</label>
      <span style="float:left; width:98%; word-wrap:break-word; text-align: justify;">{$report_issue_data.message}</span>
      </div>
      <div class="box_functions " style="margin-top:0;padding-top:0;width:100px;float: left;margin-left:123px; clear: both;">
      <a href="{"products.view?product_id=`$product_merchant_data.product_id`"|fn_url}" style="cursor:pointer;float:left;margin:5px 0 0 0;">
           <input class="box_functions_button" type="button" value="Continue Shopping" style="cursor:pointer;">
            </a>


</div>
      
   
    </div>
    <div style="float:left; width:500px;">{$lang.now_what_will_happen}</div>
     {/if}
</form>
</div>  
