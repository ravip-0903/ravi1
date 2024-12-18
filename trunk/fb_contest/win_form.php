<style type="text/css">
.text_format {font-family: Calibri;font-size: 16px;font-weight: normal;color: #000;}
.box_size {height: 25px;}
.form_class{}
    .form_class .input_class{float:left; clear:both;}
    .form_class .span_class{float:left; width: 150px; margin-right: 20px;}
    .form_class input{float:left; }
    .form_class .button_blk{background:#048ccc; border:0; cursor: pointer; padding: 10px; color: #ffffff;}
    .form_class .Disabledbut {   
        background-color: #442c2c;  
        color: #f9e7e7;
        cursor:wait;
}    
</style>
<?php
include_once('conn.php');
include_once('query.php');
$state = db_get_array("SELECT csd.state FROM `cscart_states` cs inner join cscart_state_descriptions csd on cs.state_id = csd.state_id where cs.country_code = 'IN'");
if($_POST['hidden_param'] == 'done')
{
   
   header("location:index.php?time=".time());
}

if($last_win ==1)
{
?>
<body>
   
    <form id="form1" class="form_class" name="form1" method="post"  onsubmit="return validate_form()">
<table width="812" border="0" align="center" style="background-image:url('http://cdn.shopclues.com/images/banners/fb_contest/bg.jpg'); background-repeat:repeat;">
  <tr>
    <td><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="width:734px;">
        <tr>
          <td colspan="2" align="center" style="padding-top:30px;"><img src="http://cdn.shopclues.com/images/banners/fb_contest/jackpot_img.jpg" width="254" height="99" /></td>
        </tr>
        <tr>
          <td colspan="2" align="center" style="padding-bottom:40px;"><img src="http://cdn.shopclues.com/images/banners/fb_contest/text1.jpg" width="546" height="44" /></td>
        </tr>
        <tr>
        <td>
        <div  style="display:inline-block; color:#ff0000;margin-left: 10px;" id="errors_all"></div>
<div  style="display:inline-block; color:#ff0000;margin-left: 10px;" id="errors_mob"></div>
<div  style="display:inline-block; color:#ff0000;margin-left: 10px;" id="errors_email"></div>
    </td>
        </tr>
     
        <tr>
          <td class="text_format" style="padding-left:10px;"><span style="color:red">*</span> ShopClues Registered Email Id:</td>
          
        </tr>
        <tr>
          <td style="padding-left:10px; padding-top:5px;"><input name="shop_email" type="text" class="box_size" id="shop_email" value="" size="45"   /></td>
          <td style="padding-left:10px; padding-top:5px;"> <a href="https://secure.shopclues.com/login" target="_new"> Click if you are not having ShopClues registered mail ID</a></td>
        </tr>
        <tr>
          <td class="text_format" style="padding-top:15px; padding-left:10px;" ><span style="color:red">*</span>Name :</td>
          <td class="text_format" style="padding-left:10px; padding-top:15px;">FB ID :</td>
        </tr>
        <tr>
          <td style="padding-left:10px; padding-top:5px;"><input name="name" type="text" class="box_size" id="name" size="45"  value="<?php  echo $name; ?>"/></td>
          <td style="padding-left:10px; padding-top:5px;"><input name="fb_id" type="text" class="box_size" id="fb_id"  size="45"  value="<?php  echo $user_id; ?>" readonly /></td>
        </tr>
        <tr>
          <td class="text_format" style="padding-top:15px;padding-left:10px;" >FB Picture : </td>
          <td class="text_format" style="padding-left:10px; padding-top:15px;"><span style="color:red">*</span>Secondary Email ID:</td>
        </tr>
        <tr>
          <td style="padding-left:10px; padding-top:5px;">
              <?php
        $profile_pic =  "http://graph.facebook.com/".$user_id."/picture";
        echo "<div class='post_list'>
        <div class='fb_pro_pic'><a href = \"".$link."\" style='border:0;' target='_new'>
        <img class='profile_pic' src=\"" . $profile_pic . "\" /></a></div>"; 
        ?>
              
          </td>
          <td valign="top" style="padding-left:10px; padding-top:5px;"><input name="sec_email" type="text" class="box_size" id="sec_email" value="<?php  echo $email; ?>" size="45"   /></td>
        </tr>
        <tr>
          <td class="text_format" style="padding-top:15px; padding-left:10px;"><span style="color:red">*</span>Address : </td>
          <td class="text_format" style="padding-left:10px; padding-top:15px;">Adress line 2 :</td>
        </tr>
        <tr>
          <td style="padding-left:10px; padding-top:5px;"><input name="add" type="text" class="box_size" id="add" size="45" /></td>
          <td style="padding-left:10px; padding-top:5px;"><input name="add1" type="text" class="box_size" id="add1" value="" size="45"  /></td>
        </tr>
        <tr>
          <td class="text_format" style="padding-top:15px; padding-left:10px;"><span style="color:red">*</span>City : </td>
          <td class="text_format" style="padding-left:10px; padding-top:15px;"><span style="color:red">*</span>State :</td>
        </tr>
        <tr>
          <td style="padding-left:10px; padding-top:5px;"><input name="city" type="text" class="box_size" id="city" size="45"  /></td>
          <td style="padding-left:10px; padding-top:5px;"><select name="select" id="select">
        <?php 
              echo  "<option value=''>--Select state--</option>";
        foreach($state as $states)
        {
             echo "<option value='".$states['state']."'>".$states['state']."</option>";
             
        }
      
         ?>  
          </select></td>
        </tr>
        <tr>
          <td class="text_format" style="padding-top:15px;  padding-left:10px;"><span style="color:red">*</span>Pincode : </td>
          <td class="text_format" style=" padding-left:10px; padding-top:15px;"><span style="color:red">*</span>Mobile :</td>
        </tr>
        <tr>
          <td style="padding-left:10px; padding-top:5px;"><input name="pin" type="text" class="box_size" id="pin" size="45"  /></td>
          <td style="padding-left:10px; padding-top:5px;"><input name="mobile" type="text" class="box_size" id="mobile" value="" size="45"  /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><div><input type="hidden" name="hidden_param" value="done" /></div>
    <div class="input_class"><span class="span_class"></span> <input type="submit" id="sub_valid" class="button_blk" name="submit" /></div></td>
        </tr>
      </table></td>
  </tr>
</table>
    </form>
</body>
</html>
<?php

}
else
{
    header("location:index.php");
}
?>
<?php
        $fb_redirect = Registry::get('config.fb_quiz_redirect');
       if(isset($_REQUEST['submit']))
    {
           $address = array($_REQUEST['add'],$_REQUEST['add1'],$_REQUEST['city'],$_REQUEST['select'],$_REQUEST['pin']);
           $address  = serialize($address);
           if($last_win ==1)
           {
        $insert_winner = db_query("insert into clues_fb_winner (fb_id,name,mobile_no,shopclue_email_id,secondary_email_id,address)  values('".$user_id."','".$_REQUEST['name']."','".$_REQUEST['mobile']."','".$_REQUEST['shop_email']."','".$_REQUEST['sec_email']."','".$address."')");
         $one = db_query("update clues_fb_quiz set last_win=0 where user_id=".$user_id);
         
          $fbshare = $facebook->api('/me/feed', 'POST', array (
        'message'       =>  fn_get_lang_var('win_msg_for_fb'),	
        'link'		=>  $fb_redirect,
         'picture'		=> ' http://images.shopclues.com/images/banners/fb_contest/small_fb.jpg',
        ));        
          header("location:index.php?time=".time());
           }
//      I have tried my LUCK on ShopClues Jackpot. Have you tried it? Check Now!
    }
        
        ?>
<!--	Script to validate form before submit and retain its value -->		
	
            <script src="scripts/jquery.min.js" type="text/javascript"></script>
            <script type="text/javascript">
              function validate_form(){$("#errors_all").hide();$("#errors_mob").hide();$("#errors_email").hide();var i=$("#mobile").val();var e=$.trim(i);var g=$("#pin").val();var b=$.trim(g);var o=$("#city").val();var h=$.trim(o);var d=$("#select").val();var k=$("#add").val();var n=$.trim(k);var l=$("#name").val();var c=$.trim(l);var m=$("#shop_email").val();var f=$("#sec_email").val();var a=f.indexOf("@");var j=f.lastIndexOf(".");if(m==""||m==null||e==""||e==null||c==""||c==null||f==null||f==""||n==""||n==null||b==""||b==null||h==""||h==null||d==""||d==null){$("#errors_all").show();document.getElementById("errors_all").innerHTML="Please fill all the columns";return false}else{if(a<1||j<a+2||j+2>=f.length){$("#errors_email").show();document.getElementById("errors_email").innerHTML="Please recheck your email ID and enter a valid email ID";return false}else{if(!isNaN(h)){$("#errors_mob").show();document.getElementById("errors_mob").innerHTML="We dont have cities by numeirc values. Please recheck your city name";return false}else{if(isNaN(b)||b.length!=6){$("#errors_mob").show();document.getElementById("errors_mob").innerHTML="Please recheck your pincode and enter only 6 digit numeric values";return false}else{if(isNaN(e)||e.length!=10){$("#errors_mob").show();document.getElementById("errors_mob").innerHTML="Please recheck your mobile no. and enter only 10 digit numeric values";return false}}}}}}$(document).ready(function(){$("#form1").on("blur","#shop_email",validate_shop)});function validate_shop(){$("#errors_email").hide();var a=$("#shop_email").val();var b={email_check1:a};$.ajax({type:"post",url:"winner_valid.php",data:b,success:function(c){if(c=="wrong"){$("#sub_valid").addClass("Disabledbut");$("#sub_valid").attr("disabled","disabled");$("#errors_email").show();document.getElementById("errors_email").innerHTML="Please recheck your Shopclue email id before submit the form.";return false}else{if(c=="mismatch"){$("#sub_valid").addClass("Disabledbut");$("#sub_valid").attr("disabled","disabled");$("#errors_email").show();document.getElementById("errors_email").innerHTML="please fill correct way of email id";return false}else{$("#sub_valid").removeClass("Disabledbut");$("#sub_valid").removeAttr("disabled")}}}})};
	</script>