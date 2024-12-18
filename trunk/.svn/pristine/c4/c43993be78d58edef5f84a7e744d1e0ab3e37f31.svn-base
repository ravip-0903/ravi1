
<script src="scripts/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
 $(document).ready(function(){
   $('#errors_all').hide();
   $("#submit").click(function()
   {

     var name = $.trim($('#user_name').val());
     var email = $.trim($('#email').val());
     var mobile = $.trim($('#mobile').val());
     var upload = $('#upload1').val();
     var desc = $.trim($('#desc').val());
     var titles = $.trim($('#titles').val());
    // var vote = $.trim($('#ask').val());
    var email_valid = new RegExp(/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);
    if(name == '' || name == null || titles == '' || titles == null  || email == '' || email==null || upload == '' || upload == null  || mobile == '' || mobile ==null || desc == '' || desc ==null)
    {
     $('#errors_all').show();
     document.getElementById('errors_all').innerHTML='Please fill all details before submitting.';     
     return false;
   }   
   else if(isNaN(mobile) || mobile.length!=10)
   {
    $('#errors_all').show();
    document.getElementById('errors_all').innerHTML='Mobile number should be 10-digit number e.g. 9876543210';     
    return false;
  }   
  else if (!email_valid.test(email)) 
  {

   $('#errors_all').show();
   document.getElementById('errors_all').innerHTML='Check the email id format';     
   return false;

 }
});   
$("#upload1").change(function(event)
{ 
  var type = event.target.files[0].type;
  var size = event.target.files[0].size;
  if((type=="image/gif" || type=="image/jpeg" || type=="image/jpg" || type=="image/pjpeg" || type=="image/x-png" || type=="image/png") && size <1000000)
  {
    $('#errors_all').hide();    
    $('#submit').removeAttr('disabled');
  }
  else
  {
   $('#errors_all').show();
   document.getElementById('errors_all').innerHTML='Image format should be jpg,jpeg,png or gif and size should be less than 1 MB';     
   $('#submit').attr('disabled','disabled');
 }
}); 
});
</script>
</head>
<?php
    // Connection setup
//THis below code is used to bypass security in IE8.
header("p3p: CP=\"ALL DSP COR PSAa PSDa OUR NOR ONL UNI COM NAV\"");

include_once('conn.php');
//$photo_redirect = 'index.php';
$photo_redirect = Registry::get('config.photo_contest_redirect');
    // $photo_redirect = 'https://www.facebook.com/Shopclu/app_203351739677351';
$server_loc = Registry::get('config.http_location');


if(isset($_REQUEST['id']))
{
  $_SESSION['ids'] = $_REQUEST['id'];

  header("location:".$photo_redirect);
}
else
{
  if($_SESSION['ids'])
  {
   include_once("my_page.php");
   unset($_SESSION['ids']);
 }
 else
 {

  if(isset($_REQUEST['code']))
  {
    header("location:".$photo_redirect);
  }

//Fetching o/p based on form submit.....
  if(isset($_REQUEST['submit']))

  {

   $exp  = explode('.',$_FILES["picture_upload"]["name"]);
   $name_exp = $exp[0];
   $ext  = $exp[1];

   $character_array = array_merge(range(a, z), range(0, 9),range(A, Z));
   $string = "";
   for($i = 0; $i < 15; $i++) {
    $string .= $character_array[rand(0, (count($character_array) - 1))];
  }
  $name_exp = $string;

  $_FILES["picture_upload"]["name"]  =  $name_exp.".".$ext;


  if ($_FILES["picture_upload"]["error"] > 0)
  {
    header("location:index.php");
  }
  else
  {
    if (preg_match('/[^a-zA-Z0-9-_.\s]+/',$_REQUEST['user_name'])  ||  preg_match('/[^a-zA-Z0-9-_.\s]+/',$_REQUEST['titles']) ||  preg_match('/[^a-zA-Z0-9-_.\s]+/',$_REQUEST['desc'])  ||  preg_match('/[^a-zA-Z0-9-_.\s]+/',$_REQUEST['ask']))
    {

     header("location:index.php?error=1");
   }
   else
   {


    $cdn_url = Registry::get('config.fb_image_host_url');  
    $_REQUEST['desc'] = addslashes(substr($_REQUEST['desc'],0,50));
    $_REQUEST['ask'] = addslashes(substr($_REQUEST['ask'],0,50));
    $_REQUEST['titles'] = addslashes(substr($_REQUEST['titles'],0,50));

    move_uploaded_file($_FILES["picture_upload"]["tmp_name"],
      "../images/fb_photo_contest/".$_FILES["picture_upload"]["name"]);

    $local = '../' . Registry::get('config.locfb_img') .$_FILES["picture_upload"]["name"];
    $remote =Registry::get('config.remote_img_fb');
    $parameter = Registry::get('config.rsync_parameter_fb');
    $rsyn = exec("rsync $parameter $local $remote ");

                    //echo "Stored in: " . "/../images/fb_photo_contest/" . $_FILES["picture_upload"]["name"];
    $insert_query = db_query("insert into clues_photo_contest (user_id,name,email,pic_path,mobile,description,appeal,type,title) values('".$user_id."','".$_REQUEST['user_name']."','".$_REQUEST['email']."','".$_FILES["picture_upload"]["name"]."','".$_REQUEST['mobile']."','".($_REQUEST['desc'])."','".($_REQUEST['ask'])."','1','".($_REQUEST['titles'])."')");  

    $fbshare = $facebook->api('/me/feed', 'POST', array (
      'message'       =>  fn_get_lang_var('share_for_photo_contest'), 
      'link'    =>  $server_loc.'/fb_college_campus/index.php',
      'picture'    => $cdn_url.'/images/fb_photo_contest/'.$_FILES["picture_upload"]["name"],
      'description' =>$_REQUEST['desc']
      ));       
    header("location:voting_page.php");
  }

}
}


if($user_id)
{
  $permissions = $facebook->api("/me/permissions");

  if((array_key_exists('publish_stream', $permissions['data'][0])))
  {
    try  
    { 
      $user_profile = $facebook->api('/me','GET');
      include_once('user_info.php');
    }
    catch(FacebookApiException $e) 
    {
      $login_url = $facebook->getLoginUrl(array('scope' => 'email,user_birthday,publish_stream')); 
    }   
  }
  else
  {
    $login_url = $facebook->getLoginUrl(array('scope' => 'publish_stream')); 
    echo "<script type='text/javascript'>top.location.href='".$login_url."';</script>";
  }
}
else  
{ 
  $login_url = $facebook->getLoginUrl(array('scope' => 'email,user_birthday,publish_stream')); 
  echo "<script type='text/javascript'>top.location.href='".$login_url."';</script>";
  exit; 
} 
?>

<?php
$allowd_chances = db_get_field("select count(*) as count from clues_photo_contest where user_id=".$user_id." and type = 1");
$max_voting = Registry::get('config.max_voting_limit_fb');
//Content based on registration..
if($user_id)
{

  $user_details = get_details($user_id);
  $name = $user_details[0]['name'];
  $email = $user_details[0]['email'];
  ?>

  <style type="text/css">
    .bg_body{
     background:url(http://cdn.shopclues.com/images/banners/bg_body.gif) left top repeat;
     width:783px;
     height:799px;
     margin:auto;
     position:relative;
     font:13px trebuchet ms;
   }
   a{
     font:13px trebuchet ms;
     color:#fff;
     text-decoration:none;
   }
   .logo_shopclues{
     background:url(http://cdn.shopclues.com/images/banners/logo_shopclues.gif) left top no-repeat;
     width:245px;
     height:57px;
     margin-top:30px;
     margin-left:30px;
     float:left;
   }
   .clickshotwin{
     background:url(http://cdn.shopclues.com/images/banners/bg_clickshotwin.gif) left top no-repeat;
     width:282px;
     height:109px;
     float:right;
     position:absolute;
     right:0px;
     margin-top:-12px;
   }
   .clearboth{
     clear:both;
     font-size:0px;
   }
   .clickToVote{
     float:right;
     background:url(http://cdn.shopclues.com/images/banners/img_clickForVote.gif) left top no-repeat;
     width:228px;
     height:53px;
     margin-right:30px;
     margin-top:33px;
   }
 </style>
 <!--a name="details" id="details" onclick="display();" style="display:none">click for form</a-->
 <div id="main_form">
  <form name="contest" method="post" action="" enctype="multipart/form-data" autocomplete="off">
   <div class="bg_body">
    <div class="logo_shopclues"></div>
    <div class="clickshotwin"></div>
    <div class="clearboth"></div>
    
    <a name="form" id="form" href="voting_page.php" href="index.php" class="clickToVote"></a>
    
    <div class="form_field_bg">
      <div class="form_field_inner">
        <?php
        if($allowd_chances >= $max_voting)
        {
          ?>
          <!--Meesage Div start from here-->
          <div style="float:left; width:100%; text-align:center;">
            <span style="float:left; width:100%; font:bold 50px trebuchet ms; color:#448bca; margin-top:20px;">Good Job!!</span>
            <span style="float:left; width:100%; font:bold 23px trebuchet ms; color:#333; margin-top:20px;">You have already submitted <?php echo $max_voting; ?> entries.</span>
            <span style="float:left; width:100%; font:bold 20px trebuchet ms; color:#333;">It's time to get popular</span>
            <span style="float:left; width:100%; font:bold 15px trebuchet ms; color:#448bca; margin-top:10px; padding-bottom:20px;">All the best!!</span>
          </div>
          <?php
        }
        else
        {
          ?>
          <!--Registration Div start from here-->
          <div style="">
            <div class="register_txt">Register</div>
            <div class="main_form_field">
              <div class="inner_form_field">
                <div class="form_field_name">Name <b style="color:red;">*</b>: </div>
                <div class="form_field_text_box">
                 <input name="user_name" type="text" id="user_name" value="<?php echo $name; ?>" />
               </div>
             </div>
             <div class="inner_form_field">
              <div class="form_field_name">Mobile Number <b style="color:red;">*</b>:</div>
              <div class="form_field_text_box">
               <input name="mobile" id="mobile" type="text" maxlength="10"/>
             </div>
           </div>
           <div class="inner_form_field">
            <div class="form_field_name">Email <b style="color:red;">*</b>:</div>
            <div class="form_field_text_box">
             <input name="email" type="text" value="<?php  echo $email; ?>"  id="email" />
           </div>
         </div>
         <div class="form_field_participate">Participate in the Contest by Uploading Your Picture :</div>
         <div class="inner_form_field">
          <div class="form_field_name">Upload your picture <b style="color:red;">*</b>:</div>
          <div class="form_field_text_box">
           <input class="fb_text_field" type="file" name="picture_upload" id="upload1" />
         </div>
       </div>

       <div class="inner_form_field">
        <div class="form_field_name"> Title <b style="color:red;">*</b>:<br>
          <span style="font:11px verdana; color:#808080;">Max word limit 50 chars</span>
        </div>

        <div class="form_field_text_box">                
          <textarea name="titles" type="text" id="titles" maxlength="50"></textarea>
        </div>
      </div>

      <div class="inner_form_field">
        <div class="form_field_name">What your picture says <b style="color:red;">*</b>:<br>
          <span style="font:11px verdana; color:#808080;">Max word limit 50 chars</span>
        </div>
        <div class="form_field_text_box">
          <textarea name="desc" type="text" id="desc" maxlength="50"></textarea>
        </div>
      </div>


      <div class="inner_form_field">
        <div class="form_field_name"> Make a Vote Appeal:<br>
          <span style="font:11px verdana; color:#808080;">Max word limit 50 chars</span>
        </div>

        <div class="form_field_text_box">                
          <textarea name="ask" type="text" id="ask" maxlength="50"></textarea>
        </div>
      </div>
      
      <div class="inner_form_field">
        <div class="form_field_name"></div>
        <div class="form_field_text_box">
         <input  style="background:url(https://images.shopclues.com/images/banners/icons/submit.png)no-repeat; border:0px; width: 83px; height:28px;" type="submit" name="submit" value="" id="submit"/>
         <input type="hidden" name="hid" value="<?php echo $user_id;  ?>" />
         <div>
          <input type="hidden" name="hidden_param" value="done" /></div>
        </div>
      </div>
    </div>
  </div>
  <?php
}
?>

</div>



</div>


<?php
if($allowd_chances <  $max_voting)
{
  ?>
  <?php
}
?>
<div style="float: left; color: red; margin-left: 25px; margin-top: 5px; font: 12px trebuchet ms;" id="errors_all"></div>
<?php
if(isset($_REQUEST['error']))
{
  echo "<div style='float: right; color: red; margin-left: 25px; margin-top: -12px; margin-right:23px; font: 12px trebuchet ms;'>Please don't enter any special characters</div>";
  unset($_REQUEST['error']);
}


?>
</div>
</form>
<?php

}
unset($_SESSION['ids']);
}
?>

<!--<div style="display: none;" id="terms">
    <?php  include_once('terms.php'); ?>
</div>
-->
<?php

}
function get_details($user_id)
{
  return db_get_array("select name,email from clues_fb_users where uid=".$user_id);
}
?> 
<style type="text/css">
 .main_bg{width:816px; height:689px; margin: 0px auto; background-image:url(https://images.shopclues.com/images/banners/icons/capmus_connect_bg_SC.jpg); background-repeat:no-repeat; background-position:top center;}
 .main_bg_inner{width:494px; margin: 0px auto; }
 .form_bg{width:494px; height:187px; background-image:url(https://images.shopclues.com/images/banners/icons/form_bg.png); background-repeat:no-repeat; background-position:top center; float:left; margin-top:16px}
 .form_bg_inner{width:251px; float:left; margin:25px 0px 0px 122px; text-align:center}
 .bg_form_center{width:494px; background-image:url(https://images.shopclues.com/images/banners/icons/form_bg_center.png); background-repeat: repeat-y; background-position:top center; float:left;}
 .form_field_bg{
  margin-top:30px;
  width:723px;
  margin-left:30px;
  margin-right:30px;
  float:left;
  background-color:#efefef
}
.form_field_inner{width:713px; float:left; background-color:#efefef; border:2px solid #fff; margin:3px}
.register_txt{width:97%; float:left; background-color:#efefef; border-bottom:1px solid #fff; font-family:Arial, Helvetica, sans-serif; font-size:30px; color:#448bca; padding:5px 0px 5px 20px}
.main_form_field{width:95%; float:left; background-color:#efefef; margin-left:35px; margin-top:30px}
.inner_form_field{width:100%; float:left; background-color:#efefef; margin-bottom:20px}
.form_field_name{ margin-top:2px; width:200px; float:left; background-color:#efefef; text-align:left; font-family:Arial, Helvetica, sans-serif; font-size:15px; color:#000; margin-right:10px}
.form_field_text_box{float:left; background-color:#efefef; }
.form_field_text_box input, .form_field_text_box textarea{
  float:left;
  border:1px solid #a6a6a6;
  background-color:#efefef;
  width:250px;
  background-color:#fff;
  padding:3px;
  font-size:12px;
}
.form_field_participate{width:100%; float:left; background-color:#efefef; margin-bottom:5px; font-family:Arial, Helvetica, sans-serif; font-size:15px; color:#000; padding-top:10px; padding-bottom:10px; }
.bottom_bg{width:494px; height:87px; background-image:url(https://images.shopclues.com/images/banners/icons/form_bg_bot.png); background-repeat:no-repeat; background-position:top center; float:left;}
</style>
