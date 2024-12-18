{** block-description:sdeep_pincode_validation **}

{*assign var="product_count" value=$product.company_id|fn_product_count*}
<div class="produ_detai_right_b_mng pj2_top_border" style="padding:5px; padding-top: 0; width:215px;">
<div class="pj2_prd_seller_name" style="font:bold 14px Arial, Helvetica, sans-serif; background:none; color:#000;">{$lang.pincode_availablity}
</div>

    <div style="float: left; margin-left:3px;" class="form-field" id="pincode_avail">
    <label for="pincode"></label>
    <input type="tel" style="border-radius: 5px 5px 5px 5px; width:135px; float:left; border-top-right-radius: 0; border-bottom-right-radius: 0; border: 1px solid rgb(204, 204, 204); padding: 6px;" name="pincode" id="pincode" placeholder="Enter Pincode" maxlength="6"> 
          
              <span style=" margin:0; float:left;" class="button-submit cm-button-main ">
                  <input class="box_functions_button" id="check_but" type="submit" value="Check" onclick="check_pincode({$product.product_id},0);" style="border-top-left-radius: 0; border-bottom-left-radius: 0; cursor:pointer;">
              </span>
    </div>
    
    
    
    
    <div id="change_pin" style="display:none;" class="pincode_prd_page_blk">
        <span>{$lang.shipping_to}: </span><span id="pin_display" class="font_bold"></span><a class="ahover_nl pincode_change" onclick="change_pincode();"> {$lang.change_pin}</a>
    </div>
    
          <div id="not_servicable" style="display:none">
              <p>{$lang.not_servicable}</p>
          </div>
          <div id="both_cod_prepaid" style="display:none">
              <p>{$lang.both_cod_prepaid}</p>
          </div>
          <div id="only_prepaid" style="display:none">
              <p>{$lang.only_prepaid}</p>
          </div>  
          <div id="not_valid" style="display:none">
              <p>{$lang.not_valid_pincode}</p>
          </div> 
         
<div id="edd" style="display:none">
   <div style="color:#0A70C0;">{$lang.pdd_product_page} <span id="fdate"></span> {$lang.pdd_mid} <span id="sdate"></span> <span>{$lang.delivery_help}</span></div>
   
</div>
</div>

{literal}
<script>
    
$(document).ready(function(){
    
 var pincode = ReadCookie('pincode');
 var prod_id = {/literal}{$product.product_id};{literal}
    //alert(pincode+'==='+prod_id);
   if(pincode!='')
   {
       check_pincode(prod_id,pincode);
   }

});

function change_pincode()
{
    $('#pincode_avail').show();
    $('#check_but').removeAttr('disabled');
    $('#change_pin').hide();
}
function check_pincode(prod_id,pincode)
{
  if (pincode == ''){
    var pincode = $('#pincode').val();
  }
    //$('#check_but').attr('disabled','disabled');
    $('#edd').hide();
    $('#fdate').html('');
    $('#sdate').html('');
    
        $.ajax({
         type: "GET",
         url: "nss.php", 
         data:{pincode_no:pincode,product_ids:prod_id},
         dataType : 'text json',
         success: function(result){
             
        //alert(result);//alert(result.fdate);alert(result.sdate);
                     $('#pin_display').text(pincode);
                     if(result.pin_result=='0'){
                         $('#pincode_avail').hide();
                         $('#change_pin').show();
                         $('#both_cod_prepaid').hide();
                         $('#only_prepaid').hide();
                         $('#not_servicable').show();
                         $('#not_valid').hide();
                       
                      }
                     else if(result.pin_result=='3')
                     {
                         $('#pincode_avail').hide();
                        $('#change_pin').show();
                         $('#both_cod_prepaid').show();
                         $('#only_prepaid').hide();
                         $('#not_servicable').hide();
                         $('#not_valid').hide();
                       
                     }
                     else if(result.pin_result=='4' || result.pin_result=='1')
                     {
                         $('#pincode_avail').hide();
                         $('#change_pin').show();
                         $('#both_cod_prepaid').hide();
                         $('#only_prepaid').show();
                         $('#not_servicable').hide();
                         $('#not_valid').hide();
                        
                     }
                     else if(result.pin_result=='-1')
                     {
                         $('#check_but').removeAttr('disabled');
                         $('#pincode_avail').show();
                        $('#change_pin').hide();
                         $('#both_cod_prepaid').hide();
                         $('#only_prepaid').hide();
                         $('#not_servicable').hide();
                         $('#not_valid').show();
                       
                     }
                     if(result.pin_result=='4' || result.pin_result=='3')
                     {
                         $('#edd').show();
                         $('#fdate').html(result.fdate);
                         $('#sdate').html(result.sdate);
                     }
         }
     
    });
    return false;
    
}
</script>
{/literal}
