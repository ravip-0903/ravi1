<?php
	define('AREA', 'A');
	define('AREA_NAME', 'admin');
	define('PAYMENT_ID','12');
		
	require  dirname(__FILE__) . '/../../prepare.php';
	require  dirname(__FILE__) . '/../../init.php';
	
$error = '';

if(isset($_POST['submit']))
{	
if ( !defined('AREA') ) { die('Access denied'); }
	$_auth = &$auth;
	$pass = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,6);
	$userarray = array('email'=>$_POST['email'],'password1'=>$pass,'password2'=>$pass,'firstname'=>$_POST['firstname'],'b_city'=>$_POST['city']);
	if ($res = fn_update_user(0, $userarray, $_auth,!empty($_REQUEST['ship_to_another']),true,true)) {
		$suffix = 'update';
		$sql = "update ?:users set referer='fblogin' where email='". $_POST['email'] ."'";
		db_query($sql);
		//fn_login_user($res);
		fn_set_notification('N', '', 'Registraion Completed Successfully');
		?>
        <script type="text/javascript">
		window.top.location.href = "http://www.shopclues.com";
        </script><?php 
	}
	else
	{
		$suffix = 'add';
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
</head>

<body>
<div class="pj2_fb_signup_bg">
	<img class="pj2_prd_image" src="../fb_apps/images/shuffle_pic.png" />
	<div class="pj2_signup_text">
    	<span class="signup">Sign up &</span>
        <label class="win_shuffle">WIN</label>
        <img class="free_text" src="../fb_apps/images/free_shuffle.jpg" /> 
    </div>
    <?php if(isset($error) && $error != '') {
		echo "<div class='fb_error_reg'>".$error."</div>";
		 } ?>
    <form name="profile_form" action="" method="post">
    <div class="pj2_fb_signup_box">
    	<span class="pj2_field_name">Name</span>
   		<div class="pj2_field_data"><input class="pj2_text" type="text" name="firstname" value="<?php echo $_POST['firstname']; ?>"/></div>
    	<span class="pj2_field_name">Email Address</span>
   		<div class="pj2_field_data"><input class="pj2_text" type="text" name="email" value="<?php echo $_POST['email']; ?>"/></div>
    	<span class="pj2_field_name">Gender</span>
   		<div class="pj2_field_data">
            <input class="pj2_m_f_radio" type="radio" value="Male" /><label class="pj2_title">Male</label>
            <input class="pj2_m_f_radio" type="radio" value="Female" /><label class="pj2_title">Female</label>
   		</div>
        <span class="pj2_field_name">City</span>
        <div class="pj2_field_data">
           <input class="pj2_text"  type="text" name="city" value="<?php echo $_POST['city']; ?>" />
        </div>
        <div style="clear:both;" >
        	<input type="submit" class="pj2_fb_signup_btn" name="submit" value=""/>
        </div>
    </div>
    </form>
    <img class="pj2_shopclues_logo" src="../fb_apps/images/shopclues_logo.png" />
    
</div>
</body>
</html>
