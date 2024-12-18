<?php
	define('AREA', 'C');
	define('AREA_NAME', 'customer');
		
	require  dirname(__FILE__) . '/../prepare.php';
	require  dirname(__FILE__) . '/../init.php';
	
	if(isset($_POST['continue_shopping'])){
		$user_data['firstname'] = $_REQUEST['fname'];
		$user_data['lastname'] = $_REQUEST['lname'];
		$user_data['email'] = $_REQUEST['email'];
		$user_data['password'] = md5($_REQUEST['password']);
		$is_exist = db_get_row("SELECT user_id FROM ?:users WHERE email like '%".$user_data['email']."%'");
		if(empty($is_exist)) {
			$auth = $_SERVER['auth'];
			$res = fn_update_user(0, $user_data, $auth, '', 'true');
			list($user_id, $profile_id) = $res;			
		}
		fn_redirect($_REQUEST['return_url']);	
	
	}
?>
<html>
<head>
<title><?php echo fn_get_lang_var('monster_landingpage_title');?></title>
<script type="text/javascript">
//<![CDATA[
 var _gaq = _gaq || [];
 _gaq.push(["_setAccount", "UA-27831792-1"]);
 _gaq.push(["_trackPageview"]);
 
 (function() {
  var ga = document.createElement("script");

  <?php  $script = Registry::get('config.google_analytics_new_code');

  if($script == 1)
  	{?>
  		ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + "stats.g.doubleclick.net/dc.js";
  		<?php } 

  else
  	{?>
  		ga.src = ("https:" == document.location.protocol ? "https://ssl" : "http://www") + ".google-analytics.com/ga.js";

  		<?php }?>
  		ga.setAttribute("async", "true");
  		document.documentElement.firstChild.appendChild(ga);
  	})();
//]]>
</script>
<script src="js/jquery-latest.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
  <script>
  $(document).ready(function(){
    $("#regform").validate({
	  onsubmit: true,
	  rules: {
		fname: "required",
		lname: "required",
		email: {
			required:true,
			email:true
		},
		password: "required",
		cpassword:{equalTo:"#password"}
	  },
	  messages: {
			email: {
				required: 'Enter this!',
				email: 'enter valid email'
			},
			name: {
				required: 'Enter name!'
			},
			password: {
				required: 'enter password!'
			},
			cpassword: {
				required: 'enter same password'
			},
		}
	});
  });
  </script>
  
  <link href="css/stylesheet.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<div class="box_main">


<div class="logo_shopclues">
<a href="http://www.shopclues.com" target="_blank"><img src="images/logo_shopclues.gif" width="200" height="64" alt="ShopClues" />
</a>
</div>

<h1 class="box_heading">
<?php echo fn_get_lang_var('monster_landingpage_heading');?>
</h1>

<p class="box_context">
<?php echo fn_get_lang_var('monster_landingpage_top_message');?>
</p>

<form class="regform" id="regform" method="post" action="#">
<div class="form_signup">

<div class="form_signup_row">
<div class="aside_left">
<div class="form_signup_row_fieldname">
<label for="fname">First Name:</label>
</div>
<div class="form_signup_row_field">
<input id="fname" name="fname" size="25" type="text"  minlength="2" class="form_signup_row_field_textbox" />
</div>
</div>
<div class="aside_right">
<div class="form_signup_row_fieldname">
<label for="lname">Last Name:</label>
</div>

<div class="form_signup_row_field">
<input id="lname" name="lname" size="25" type="text"  minlength="2" class="form_signup_row_field_textbox" />
</div>
</div>
</div>

<div class="form_signup_row">

<div class="form_signup_row_fieldname" style="width:19%;">
<label for="cemail">E-Mail:</label>
</div>
<div class="form_signup_row_field">
<input id="cemail" name="email" size="25" type="text"  class="form_signup_row_field_textbox" style="width:445px;"  />
</div>

</div>

<div class="form_signup_row">
<div class="aside_left">
<div class="form_signup_row_fieldname">
<label for="cemail">Password:</label>
</div>
<div class="form_signup_row_field">
<input id="password" name="password" size="25" type="password"  class="form_signup_row_field_textbox" />
</div>
</div>
<div class="aside_right">
<div class="form_signup_row_fieldname">
<label for="cemail">Confirm password:</label>
</div>

<div class="form_signup_row_field">
<input id="cpassword" name="cpassword" size="25" type="password"  class="form_signup_row_field_textbox"/>
<input type="hidden" name="return_url" value="<?php echo $_REQUEST['return_url'];?>" />
</div>
</div>
</div>


<div class="form_signup_row">
<div class="form_signup_row_fieldname">
<a href="<?php echo $_REQUEST['return_url'];?>" class="form_signup_row_field_link">Skip</a>
</div>

<div class="form_signup_row_field">
<input class="form_signup_row_field_button" type="submit" name="continue_shopping" value="Go Shopping!"/>



</div>

</div>



</div>

 
        <br />
        
       <br />
       
        <br />
        
        <br />
        
        
        
 </form>


<div class="box_disclaimer">
<?php echo fn_get_lang_var('monster_landingpage_bottom_message');?>
</div>




<div class="clearboth"></div>
</div>
    
<!-- Code for Branding Lead-->
<script language="JavaScript" type="text/javascript">
var monster_cid = 104;
var monster_track_emailbl = 1;
</script>
<script language="javascript" src="http://media.monsterindia.com/v2/js/common/track/brandingLead.js" ></script>
<noscript>
<img height=1 width=1 border=0 src="http://www.monsterindia.com/tracker.html?cmpid=104&track_emailbl=1" />
</noscript>
    
       
</body>
</html>