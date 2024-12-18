 {$lang.contact_us_header_text|unescape}
<form action="{""|fn_url}" method="post" name="profile_form" id="profile_form" onsubmit="javascript:showPostData('formSubmit.php','thisHASH4TST',this);return true;">
<input type="hidden" name="token" value="{$smarty.session.form_token_value}"/>
<div class="cnct_box_nl_left">
<div class="subheaders-group" style="margin:0;">		
	<h2 class="subheader" style="font: bold 22px trebuchet ms; color: #EE811D;">
	
	{$lang.write_to_us} 

	</h2>
  
<div class="cont_nl_address_box">
<label for="parent_issue" class="cont_nl_address cm-required">{$lang.issue_type}: <span class="red_astrik">*</span></label>
<select name="subject" id="parent_issue" class="round_five profile_detail_field cont_nl_slt_width" style="height:30px;" >
<option value="">Select</option>

{foreach from=$parent_issues item="parent_issue"}
    <option name={$parent_issue.allow_free_text} value={$parent_issue.issue_id}>{$parent_issue.name}</option>
{/foreach}
</select>
</div>

<div id="rma_div_id" style="display:none;">
<a href="{"write_to_us.write"|fn_url}">
{$lang.Login_to_continue}
</a>
</div>

<div id="hide_all_fields"> 

{* Dynamically populate the contents *}

<div class="cont_nl_address_box" id="subissues"> 

</div>

<div class="cont_nl_address_box" id="sub_subissues" >
        
</div>

<div class="cont_nl_address_box" id="sub_sub_subissues" >
        
</div>
    

{*End*}  


  
<div class="cont_nl_address_box">
    <label for="email" class="cm-required cm-email cont_nl_address">{$lang.email}: <span class="red_astrik">*</span></label>
    <input type="text" name="email" id="email" size="55" value="{$smarty.request.email_id}" class="input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" onchange="javascript:emailEntered=true;" />
</div>

<div class="cont_nl_address_box">
<label for="orderid" class="cont_nl_address">{$lang.orderid}: </label>
<input type="text" name="orderid" id="orderid" size="55" value="{$smarty.request.order_id}" class="input-text round_five profile_detail_field cont_nl_inpt_width" onblur="javascript:OrderIdEntered=true;showPostData('{$api_root}orderStatusContactUs.php','thisHASH4TST',this.form);return false;" maxlength="100" />
</div>


<div class="cont_nl_address_box">
    <label class="cm-trim cont_nl_address" for="name" >{$lang.name}:</label>
    <input type="text" name="name" id="name" size="55" value="{$prepopulate_name}" class="input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" />
</div>


<div class="cont_nl_address_box">
    <label for="phone" class="cm-phone cont_nl_address">{$lang.phone}:</label>
    <input type="text" name="phone" id="phone" size="55" value="{$prepopulate_phone}" class="input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" onchange="javascript:phoneEntered=true;" />
</div>


<!-- code by arpit gaur to add the issue menu -->
{literal}
<script type="text/javascript">      
  $('#parent_issue').change(function(){
     var selcted_issue = $('#parent_issue').val();
     if (selcted_issue == 78){
      $("#hide_all_fields").hide();
      $("#sub_button").hide();
      $("#rma_div_id").show();

     }else{
     $("#hide_all_fields").show();
     $("#sub_button").show();
     $("#rma_div_id").hide();
     }
    
   $.ajax({
       type: "GET",
       url: " ",
       cache:false,
       data: {dispatch:'write_to_us.ajax_issues',parent_id:$('#parent_issue').val(),text:$('#parent_issue option:selected').text()},
       error:function (data, textStatus, jqXHR) { 
	   window.location.href="index.php?dispatch=write_to_us.get_error";
	    }
   }).done(function( msg ) {
      
       if(msg == ''){
           $('#sub_subissues').hide();
		   $('#sub_sub_subissues').hide();
           $('#subissues').html(msg);
        }else{
            
           $('#subissues').html(msg);
		   $('#sub_subissues').hide();
		   $('#sub_sub_subissues').hide();
          
        }
          
     }); 
         
   });
  
  $('#subissues').live('change',function() {
	  //alert('value changed');
     if($('#subissues option:selected').val() != ''){
      $.ajax({
          type: "GET",
          url: " ",
          cache:false,
          data: {dispatch:'write_to_us.ajax_issues',child_id:$('#subissues option:selected').val()},
          error:function (data, textStatus, jqXHR) {
			  window.location.href="index.php?dispatch=write_to_us.get_error";
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
			  
			  //alert("Error Occured");
			   window.location.href="index.php?dispatch=write_to_us.get_error";
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

<script type="text/javascript">
$('#profile_form').submit(function(){
	
	var final_value='';
	
	//check for subissue
	//if($('#subissues').length!=0)
	final_value+=$('#subissues').val();
	
	//check for subissue
	//if($('#sub_subissues').length!=0)
	final_value+=$('#sub_subissues').val();
	
	//check for subissue
	//if($('#sub_sub_subissues').length!=0)
	final_value+=$('#sub_sub_subissues').val();
	
	//alert(final_value);
	
	$('#subissues').val(final_value);
	//return false;;
	});
</script>

{/literal}
<!-- code by arpit gaur ends here -->


<div class="cont_nl_address_box" >
<label for="message" class="cont_nl_address" >{$lang.message}: </label>

<div class="cont_nl_address_box" style="padding:0;">
<textarea name="message" id="message" rows="3" cols="40" class="round_five profile_detail_field" style="max-width:345px;  height: 133px; max-height: 90px; width:345px;"></textarea>
</div>

</div>

<!-- arpit gaur code starts here for captcha integration -->
<div style="margin-top:5px;"></div>
<div class="contact_us_catcha_div">
	<p>Enter the text you see in the box : </p>
    
    <input type="hidden" id="real_captcha" name="real_captcha" value="{$real_captcha}" />
    <span style="float:left; background:url(images/skin/bg_captcha_img.jpg); cursor:pointer; height:28px; line-height:28px; text-align:center; display:block; padding:0 5px; width:63px; margin-right:10px; border:1px solid #ccc; margin-left:90px;"><strong id="captcha_original_value">{$real_captcha}</strong></span>
    <span style="width:60%;margin-left:7%;"><input class="round_five profile_detail_field" type="text" name="user_captcha" style="width:70px" /></span>
    
</div>

{literal}
<script type="text/javascript">
	$('#captcha_original_value').click(function(){
		var captcha=Math.floor((Math.random()*1000000));
		$('#captcha_original_value').html(''+captcha);
		$('#real_captcha').val(captcha);
		});
</script>
{/literal}

<!-- code by arpit gaur ends here -->

<!-- code by arpit gaur for keeping the response message as hidden-->
	<input type="hidden" id="custom_hidden_response" name="custom_hidden_response" value="" />
	<input type="hidden" id="query_from" name="query_from" value="add" />
<!-- code by arpit gaur ends here -->


</div>  

<div class="box_functions sup_act_btn" id="sub_button" >
    {include file="buttons/save.tpl" but_name="dispatch[write_to_us.add]"  but_text="Submit" but_role="button_main" but_class="box_functions_button"}
<span style="display:none;color:#999; float:left; margin-top:-43px; font-size:11px;">{$lang.request_submitted_to_cs}</span>
    </div>

</div> 

   


<div class="faq_contact_us_nl" id="htm2display"></div>
</div>
</form>

<div class="faq_contact_us_nl" id="faq_contact_us_nl">
{$lang.faq|unescape}
</div>

<div class="cust_supt_nl">
{$lang.call_us_wts|unescape}
</div>


{literal}
	<style type="text/css">#faq_contact_us_nl h2{cursor:pointer;}</style>
	

	<script type="text/javascript">
		var flag=0;//faq is hidden
		$('#faq_contact_us_nl h2').click(function(){
			if(flag%2==0)
			{
				$('.num_points_ans').hide();
				flag+=1;
			}
			else
			{
				$('.num_points_ans').show();
				flag+=1;
			}
		});
	</script>
{/literal} 




