{* code for Solr_search popup feedback added by Divya*}
<div class="feedback_search_blk rposition" id = "feedback">
   <span> <scroll>{$lang.found_what_you_want} </scroll></span>
         <a class="ahover_nl font_bold" id="yes">Yes</a> / <a class="ahover_nl font_bold" id="No">No</a>
     
    <div id="search_feedback_form" class="popup feedback_form_srch" style= "display: none;">
        <div class="popupbox-closer"><img src="http://cdn.shopclues.com/images/banners/cross_close_icon.png" class="cm-notification-close" title="Close" alt="Close" id="close"></div>
         <h1>{$lang.please_provide_us_feedback}</h1>
         <input type="hidden" value="{$smarty.request.q}" id="search_value" name="search_value">
         <input type="hidden" value="{$smarty.session.auth.user_id}" id="user_id" name="user_id">
         <input type="hidden" value="{$smarty.session.continue_url}" id="url" name="url">
                    <div class="fdbk_frm"><label for="feedback_mobile_no">{$lang.mobile_no}</label>               
                    <span class="mob_no">+91-</span> <input id="feedback_mobile_no" class="cm-required" style="width:127px;" class="feedback_mobile_no" name="feedback_mobile_no" maxlength='10' placeholder="Mobile Number">
                    <div id="mobile_error" class="error_feedback_category" style= "display:none">
                     <span>Please enter valid mobile number.</span>
                    </div>
                    </div>
                    <div class="fdbk_frm"><label for="feedback_email">{$lang.email_feedback}</label>               
                    <input id="feedback_email" class="feedback_email" name="feedback_email" placeholder="Email Id">
                    <div id="email_error" class="error_feedback_category" style= "display:none">
                     <span>Please enter correct email.</span>
                    </div>
                    </div>
                    <div class="fdbk_frm"><label>{$lang.category_feedback}</label>
                        {assign var="feedback_category" value=$config.search_feedback_option}
                         <select id="feedback_category" type="text" style="width:170px;" class="feedback_category" name="feedback_category">
                        <option value="">Select a Category</option>
                         {foreach from=$feedback_category item="feedback" }
                             <option value="{$feedback}">{$feedback}</option>                            
                         {/foreach}
                    </select>
                        <div id="category_error" class="error_feedback_category" style= "display:none">
                     <span>Please select an item in the list.</span>
                    </div>
                 </div>
                 <div class="fdbk_frm"><label for="feedback_comments">{$lang.comments_feedback}</label>
                 <textarea id="feedback_comments" type="text" class="feedback_comments" name="feedback_comments" placeholder="Enter your comments here"  rows="4" cols="35" maxlength="255"></textarea>
                 </div>
<div style="clear:both;">
<span class="button" style=""><a id="submit" class="button">Submit</a></span>
<a href="javascript:void(0)" style="float:right;margin:16px 6px 0 0" onclick="$('#search_feedback_form').hide();" alt="Cancel">Cancel</a>
</div>
                    <div id="email_error" style= "display:none">
                     <label>Please Enter Correct Email ID.</label>
                    </div>
              </div>
     </div> 
    <div id="thanks" style= "display: none; float:right;">
    <span >{$lang.thank_you_for_your_feedback}</span>
    </div> 
{*Ajax and java script code is used, in order to ajaxify the popup form *}
{literal}
<script type="text/javascript">
 
//function for entry in search_feedback table
function submit_feedback(feedback){
  var feedback_comments=addslashes($('#feedback_comments').val());
  var feedback_category=addslashes($('#feedback_category').val());
  var feedback_mobile_no=addslashes($('#feedback_mobile_no').val());
  var search_value=$('#search_value').val();
  var email=addslashes($('#feedback_email').val());
  var url=$('#url').val();
  var found=feedback;
  var user_id=$('#user_id').val();
 $.ajax({
type: "POST", 
url: "index.php?dispatch=products.search_feedback_form", 
data: { feedback_comments:feedback_comments, feedback_category:feedback_category, feedback_mobile_no:feedback_mobile_no, search_value:search_value,email:email,url:url,user_id:user_id, found:found},   
  success: function() {
  
  }
  
  });
    
}
$("#yes").click(function(){
submit_feedback("yes");
$("#feedback").hide();
$("#thanks").show();
});


$("#close").click(function(){
$('#search_feedback_form').hide();
});


$("#No").click(function(){
$('#search_feedback_form').show();
});
    
//for adding slashes before special characters
function addslashes(str) {
  
  return (str + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
}

// function for email validation using regex
function checkEmail() {

    var email = $('#feedback_email').val();
    var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

    if (!filter.test(email)) {
    $('#email_error').show();
    
    return false;
 }
return true;
}


// function to validate mobile_no field
//isNan() is used to check not a number
 //(! x.match(/^[1-9][0-9\s(-)]*$/)) as 1st digit should not be zero    
function checkMobileNo()
{ var x = $('#feedback_mobile_no').val();
  if(isNaN(x) || (x.length!=10) || (! x.match(/^[1-9][0-9\s(-)]*$/)))
              {
               $('#mobile_error').show();
              return false;   
               }
          
             return true;
}

// function to validate category field

function category()
{ var x = $('#feedback_category').val();
   
    if(!x){
        
              $('#category_error').show();
              return false; 
             } 
     return true;
}


// on submit event
$("#submit").click(function(){
    
    $('#mobile_error').hide();
    $('#email_error').hide();
    $('#category_error').hide();
// validation

if( checkMobileNo() &&  checkEmail() && category())  { 
submit_feedback("No");
$('#feedback').hide();
$('#thanks').show();
   }
 });

 </script>
 {/literal}
             
