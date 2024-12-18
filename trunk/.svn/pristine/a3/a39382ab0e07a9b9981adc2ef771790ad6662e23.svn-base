<form action="{""|fn_url}" method="post" name="merchant_contact_form" id="merchant_contact_form" enctype="multipart/form-data" onSubmit="return check_captcha();" >
<div class="cnct_box_nl_left">
<div class="subheaders-group" style="margin:0;">		
	<h2 style="font: bold 22px trebuchet ms; color: #EE811D;padding: 13px 0 5px 0;
margin: 0 0 10px;">
	
	{$lang.write_to_us} 

	</h2>

<div class="cont_nl_address_box">
    <label for="email" class="cm-required cm-email cont_nl_address">{$lang.email}: <span class="red_astrik">*</span></label>
    <input type="email" name="email" id="email" size="55" value={if empty($email)}''{else}{$email}{/if} class="input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100"  />
</div>

<div class="cont_nl_address_box">
<label for="mid" class="cont_nl_address">{$lang.merchant_id}: </label>
<input type="text" name="mid" id="mid" size="55" value={if empty($company_id)}''{else}{$company_id}{/if} class="input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" />
</div>


<div class="cont_nl_address_box">
    <label class="cm-trim cont_nl_address" for="name" >{$lang.name}:</label>
    <input type="text" name="name" id="name" size="55" value={if empty($name)}''{else}{$name}{/if} class="input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" />
</div>


<div class="cont_nl_address_box">
    <label for="phone" class="cm-phone cont_nl_address">{$lang.phone}:</label>
    <input type="tel" name="phone" id="phone" size="55" value={if empty($phone)}''{else}{$phone}{/if} class="input-text round_five profile_detail_field cont_nl_inpt_width" maxlength="100" />
</div>

<!--<div class="mer_con_nl_address_box">
<label for="subject" class="mer_con_nl_address cm-required">{$lang.issue_type}: <span class="red_astrik">*</span></label>
<input type="text" name="issue" id="subject" size="55" value="" class="input-text round_five profile_detail_field mer_nl_inpt_width" maxlength="100"/>
</div>-->

<div class="cont_nl_address_box">
<label for="parent_issue" class="cont_nl_address cm-required">{$lang.issue_type}: <span class="red_astrik">*</span></label>
<select name="subject" id="parent_issue" class="round_five profile_detail_field cont_nl_slt_width" style="height:30px;" >
<option value="">Select</option>

{foreach from=$parent_issues item="parent_issue"}
    <option name={$parent_issue.allow_free_text} value={$parent_issue.issue_id}>{$parent_issue.name}</option>
{/foreach}
</select>
</div>

{* Dynamically populate the contents *}

<div class="cont_nl_address_box" id="subissues"> 

</div>

<div class="cont_nl_address_box" id="sub_subissues" >
        
    </div>

<div class="cont_nl_address_box" id="sub_sub_subissues">

  </div>

{*End*}

<div class="cont_nl_address_box" >
<label for="message" class="cm-required cont_nl_address" >{$lang.message}: <span class="red_astrik">*</span></label>

<div class="cont_nl_address_box" style="padding:0;">
<textarea name="message" id="message" rows="3" cols="40" class="round_five profile_detail_field input-text " style="max-width:345px;  height: 133px; max-height: 90px; width:345px;"></textarea>
</div>

<div id="uploadFile_divs" style="float:left; margin:5px 0 0 0;width: 348px;">
<input type="file" style="float:left; font-size: 11px; width: 320px;" class="uploadFile" id="uploadFile" name="uploadFile[]" size="40" multiple="multiple"/>
<a style="color: #ff0000; background: #fff; box-shadow: 1px 1px 1px #ccc; border-radius: 11px; width: 20px; height: 20px; float: right; text-align: center; vertical-align: middle; line-height: 18px;" onclick="clearFileInputField();">x</a>
<p>Please upload files upto 1 Mb</p>
</div>


<!--<div class="cont_nl_address_box" style="display:none;">
<textarea name="message2" id="subject_pass" rows="3" cols="40"  style="max-width:345px;  height: 133px; max-height: 90px; width:345px;"></textarea>
</div>-->

</div>

{include file="common_templates/common_captcha.tpl" }

<!--<div class="cont_nl_address_box">
    <span class="submit-button cm-button-main" style="float:left;clear:both;margin:10px 0 0 0;" >
       
     <input type="submit"  name="dispatch[companies.contact_form]" value="Save" style="float:left;clear:both;" >
     
     &nbsp;&nbsp;           
     <input type="submit"  name="dispatch[companies.contact_form]" value="Reset"  >
   
  </span> 
    
   </div>-->

<div class="box_functions sup_act_btn">
    
    <input class="box_functions_button" type="submit" name="dispatch[companies.contact_form]" value="Submit" style="margin-left:120px; curser:hand; cursor:pointer;">
 
</div>

</div>
</div>
</form>
    {$lang.merchant_faq_recent_question}
 

   {$lang.merchant_faq_footer}   
     
    
{literal}
 
  <script type = "text/javascript">
  
  $(document).ready(function() {
      
  $('#parent_issue').change(function(){
   $.ajax({
       type: "GET",
       url: " ",
       cache:false,
       data: {dispatch:'companies.contact_form',parent_id:$('#parent_issue').val(),text:$('#parent_issue option:selected').text()},
       error:function (data, textStatus, jqXHR) { alert("Oops, Some Error Occured"); }
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
  
  $('#subissue').live('change',function() {
     
      $.ajax({
          type: "GET",
          url: " ",
          cache:false,
          data: {dispatch:'companies.contact_form',child_id:$('#subissue').val(),text:$('#subissue option:selected').text()},
          error:function (data, textStatus, jqXHR) {alert("Error Occured");}
      }).done(function(msg){
             
             if(msg == ''){
                 $('#sub_subissues').hide();
                 $('#sub_sub_subissues').hide();
                 //$('#sub_subissues').html(msg);
              }else{
                  $('#sub_subissues').show();
                  $('#sub_subissues').html(msg);
                  
                  }
          });
      });
          
$('#sub_subissue').live('change',function() {
     
      $.ajax({
          type: "GET",
          url: " ",
          cache:false,
          data: {dispatch:'companies.contact_form',sub_subissueid:$('#sub_subissue').val(),text:$('#sub_subissue option:selected').text()},
          error:function (data, textStatus, jqXHR) {alert("Error Occured");}
      }).done(function(msg){
            
             if(msg != ''){
                 $('#sub_sub_subissues').show();
                 $('#sub_sub_subissues').html(msg);
              }else{
                  $('#sub_sub_subissues').html(msg);
                  }
          });
          
      });
        
  $(".central-column").css({'width':'100%','margin-left':'0px'});
  $(".right-column").hide();
   
 });
//Validation on images by shashikant
$('#uploadFile').change(function(){
        var filename=document.getElementById("uploadFile").files || [];
        for (var i = 0; i < filename.length; i++) {
            var sizeinbytes = filename[i].size;
            var sizeinkb = sizeinbytes/1024;

	if (sizeinkb > 1000 )
	{
	  alert(filename[i].name + {/literal}'{$lang.is_too_large_must_be_less_than_1Mb}'{literal});
          $("#uploadFile").val("");
          return false;
	}
        
  	var filetype=filename[i].name.substr( (filename[i].name.lastIndexOf('.') +1) );
  	var validtype='No';
  	if(filetype == 'GIF' || filetype == 'gif' || filetype == 'jpeg' || filetype == 'JPEG' || filetype == 'jpg' || filetype == 'JPG' || filetype == 'png' || filetype == 'PNG' || filetype == 'pdf' || filetype == 'PDF' || filetype == 'xlsx' || filetype == 'XLSX' || filetype == 'xls' || filetype == 'XLS' || filetype == 'doc' || filetype == 'DOC' || filetype == 'docx' || filetype == 'DOCX'){
		 validtype='Yes';
	}else{
		 validtype='No';
	}
 
       if(validtype=='No')
       {
    	alert('{/literal}{$lang.filetype_must_be_jpg_or_png_or_jpeg_or_gif_or_pdf_or_xlsx_or_xls_doc_only}{literal}');
        $("#uploadFile").val("");
    	return false;
       }     
     } 
   });
   function clearFileInputField() {
   $("#uploadFile").val("");
   }
//Validation on images by shashikant   
</script>
    
{/literal}