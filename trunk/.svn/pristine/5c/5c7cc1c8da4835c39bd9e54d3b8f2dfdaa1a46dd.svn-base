<?php
	define('AREA', 'C');
	define('AREA_NAME', 'customer');
	define('ACCOUNT_TYPE','customer');
		
	require dirname(__FILE__) . '/../../prepare.php';
	require dirname(__FILE__) . '/../../init.php';
	
$error = '';

function cleanProperNouns($name)
{
	$words = explode(' ',trim(strtolower($name)));
	
	$newname['first'] = $words[0];
	for($i=1; $i<=count($words); $i++)
	{
		if($words[$i] != '')
		{
			$newname['last'] = $newname['last'].' '.$words[$i];
		}
	}
	return $newname;
}

if(isset($_POST['submit']))
{	
if ( !defined('AREA') ) { die('Access denied'); }
	$_auth = &$auth;
	$name = cleanProperNouns($_POST['firstname']);
	$city = cleanProperNouns($_POST['city']);
	$pass = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,6);
	$userarray = array('email'=>$_POST['email'],'password1'=>$pass,'password2'=>$pass,'firstname'=>$name['first'], 'lastname'=>$name['last'],'b_city'=>$city['first'],'referer'=>'fblogin');
	if ($res = fn_update_user(0, $userarray, $_auth,!empty($_REQUEST['ship_to_another']),true,true)) {
		$sql = "select user_id from cscart_users where email='".$_POST['email']."'";
		$row = db_get_row($sql);
		fn_login_user($row['user_id']);
		?>
		<script>window.top.location='http://shopclues.com';</script>
		<?php	
	}
	else
	{
		$error = 'User Already Registered';
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Shopclues</title>
<link href="../fb_apps/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
function validateFields()
{
    var flag = 0;
    var name = document.getElementById('name');
    var name_error = document.getElementById('name_error');
	var email = document.getElementById('email');
    var email_error = document.getElementById('email_error');
    var city = document.getElementById('city');
    var city_error = document.getElementById('city_error');
	var filter = /^\w[a-zA-Z0-9-_.]+@[a-zA-Z_]+.[a-zA-Z]+.[a-zA-Z]{2,3}$/;
	var filtercity = /^[a-zA-Z ]*$/;
	var filtername = /^[a-zA-Z ]*$/;
	
    if(name.value.trim() == '' || String(name.value).search (filtername) == -1)                          
                                            {name_error.style.display = 'block'; flag++;}
    else                                    {name_error.style.display = 'none';}
	if(email.value.trim() == '' || String(email.value).search (filter) == -1)                         
                                            {email_error.style.display = 'block'; flag++;}
    else                                    {email_error.style.display = 'none';}
    if(city.value.trim() == '' || String(city.value).search (filtercity) == -1)
                                            {city_error.style.display = 'block'; flag++;}
    else                                    {city_error.style.display = 'none';}
	
    if(flag == 0)
    {
        return true;
    }
    else
    {
        return false;
    }
}
</script>
</head>

<body>
<div class="pj2_fb_signup_bg">
    <img class="pj2_like_us" src="images/pj2_likeus.jpg" />
	<img class="pj2_prd_image" src="images/nano_pic.png" />
	<div class="pj2_signup_text">
	    <img class="free_text" src="images/signup_img.jpg" />
    </div>
    <?php if(isset($error) && $error != '') {
		echo "<div class='fb_error_reg'>".$error."</div>";
		 } ?>
    <form name="profile_form" action="" method="post" onSubmit="return validateFields();">
    
    <div class="pj2_fb_signup_box">
    	<span class="pj2_field_name">Name</span>
   		<div class="pj2_field_data"><input class="pj2_text" type="text" name="firstname" id="name" value="<?php echo $_POST['firstname']; ?>"/></div>
        <div class="pj2_empty_error_message" id="name_error" style="display:none;">Please Enter Your Name</div>
    	<span class="pj2_field_name">Email Address</span>
   		<div class="pj2_field_data"><input class="pj2_text" type="text" name="email" id="email" value="<?php echo $_POST['email']; ?>"/></div>
        <div class="pj2_empty_error_message" id="email_error" style="display:none;">Please Enter Your Email</div>
    	<span class="pj2_field_name">Gender</span>
   		<div class="pj2_field_data">
            <input class="pj2_m_f_radio" type="radio" name="gender" value="Male" /><label class="pj2_title">Male</label>
            <input class="pj2_m_f_radio" type="radio" name="gender" value="Female" /><label class="pj2_title">Female</label>
   		</div>
        <span class="pj2_field_name">City</span>
        <div class="pj2_field_data">
            <input class="pj2_text" type="text" name="city" id="city" value="<?php echo $_POST['city']; ?>" />
        </div>
        <div class="pj2_empty_error_message" id="city_error" style="display:none;">Please Enter Your City Name</div>
        <div style="clear:both;" >
        	<input type="submit" class="pj2_fb_signup_btn" name="submit" value=""/>
        </div>
    </div>
   </form>
    <img class="pj2_shopclues_logo" src="images/shopclues_logo.png" />
    <div style="clear:both;"></div> 
</div>
</body>
</html>
