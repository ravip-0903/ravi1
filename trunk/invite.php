<?php
$frm_email_id = trim($_GET['invite']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:og="http://ogp.me/ns#"
      xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<title>ShopClues.com is coming...</title>
<meta property="og:image" content="http://shopclues.com/skins/basic/customer/images/ShopClues_beta.png"/>
<meta property="og:url" content="http://shopclues.com/invite.html"/>
<meta property="og:title" content="ShopClues.com is coming soon."/>
<meta property="og:type" content="website"/>
<meta property="og:description" content="Online shopping in India will never be the same again..."/>



<script type="text/javascript" src="lib/js/jquery/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>



<style type="text/css">
*{ outline:none; border:none;}
body{ background:url(images/Store_closed/bg.jpg) no-repeat top center; width:1000px; margin:0 auto; font-family:"Times New Roman", Times, serif; font-size:20px; color:#e9e7e8; height:655px;}
h4{ font-size:24px; font-weight:normal; margin-top:0px; margin-bottom:25px;}
input{background: url("images/Store_closed/textbox.png") no-repeat scroll 0 0 transparent;border: medium none;color: #939292;font-family: "Times New Roman",Times,serif;font-size: 16px;font-style: italic;font-weight: bold;padding: 11px 0;text-align: center;width: 280px; margin-bottom:0px;}

.textarea{background: url("images/Store_closed/textarea.png") no-repeat scroll 0 0 transparent;float:left;}

textarea {background: none repeat scroll 0 0 transparent;border: medium none;color: #939292;float: left;font-family: "Times New Roman",Times,serif;font-size: 16px; height: 92px;margin: 5px 0 16px;padding: 10px 8px 10px 10px;text-align: left;width: 261px;}

p{ margin:10px 0;}
input[type="submit"]{ background:url(images/Store_closed/rsvp.jpg) no-repeat top center; width:114px; height:38px; color:#FFFFFF; font-style:normal; font-size:18px;padding: 9px 0;}
small.msg{ font-size:13px; color:#333; position:relative; top:-10px;}
.logo{width:230px; height:54px; display:block; margin-top:110px; float:left;}
.invite_form_d{float:left; text-align:center; width:625px; margin:31px 0 0 144px;}

.footer{width:100%; display:block; margin-top:288px;}
/*IE <= 7:*/ .footer { *margin-top:313px; }
.footer a{ color:#e9e7e8; text-decoration:none;}
.footer .fb {background: url("images/Store_closed/fb.png") no-repeat scroll 0 0 transparent;}
.footer .tt {background: url(images/Store_closed/tw.png) no-repeat scroll 0 0 transparent;}
.in_fr { background:url(images/Store_closed/invitefriends.png) no-repeat scroll 0 0;}
.inv_mng{margin-right:33%;}
.fb, .tt{ float:left; width:200px;padding: 6px 10px 3px 64px;line-height: 19px;}
.fb:hover, .tt:hover{ text-decoration:underline;}
.in_fr{ display:block; width:199px; height:36px; float:right; margin-top:7px;}
#visitor_form{ height:162px;}
#invite_friend{ margin:0 auto; width:420px; display:none; position:absolute; float:right; background:#CCCCCC; left:35%; top:30%; border:5px solid #333; -moz-border-radius:10px; -webkit-border-radius:10px; -o-border-radius:10px; -ms-border-radius:10px; padding:10px 30px 10px 20px ;}
#invite_friend strong{ color:#333333; text-align:center; margin-bottom:5px;}
#invite_friend label {color: #333333;float: left;font-size: 14px;font-weight: bold;text-align: right;width: 125px; padding-top:10px;}
.close{ background:url(images/Store_closed/close.png) no-repeat; width:42px; height:42px; display:block; float:right; position:absolute;right: -5px;top: -12px;}
.succ, .succe{ background:#008ECC; border:5px solid #ccc; -moz-border-radius:10px; -webkit-border-radius:10px; -o-border-radius:10px; -ms-border-radius:10px; padding:5px; text-align:center; }
.succe{border:5px solid #333;}
#frm_store_close input[type="submit"]{ margin-top:10px;}
.right_mng{ margin-right:18px;}
.right_mng, .right_mng input, .right_mng small.msg{ float: right;}
/*IE <= 7:*/ .right_mng small.msg{ *top:0px;}

.shopclue_links{ position:relative;}
.shopclue_links .linksmng{padding: 2px 44px 5px;position: absolute;top: 230px; left:0;}
.shopclue_links a{color:#FFF; font-size:16px; text-decoration:none; margin-right:8px;}
.shopclue_links a:hover{text-decoration:underline;}

</style>

</head>
<body>
<div class="shopclue_links">
<div class="linksmng"><a id="aboutus" target="_blank" href="http://shopclues.com/shopclues_about_us.html" title="About us">About us</a>|
<a id="team" target="_blank" href="http://shopclues.com/shopclues_team.html" title="Team">Team</a>
</div>
</div>

<a class="logo" href="http://www.shopclues.com" title="shopclues.com">
<img src="http://shopclues.com/images/Store_closed/logo.png" alt="Shopclues" />
</a>

<div class="invite_form_d">
<h4>Online shopping in India will<br />never be the same again...</h4>
<p>Do you have a clue?</p>
<div id="visitor_form">
<div class="succ" style=" display:none; font-size:16px; font-weight:bold; height:auto; overflow:auto;">
	Thank you for registering. We will inform you as soon as ShopClues site launches. Invite your friends by clicking on the button below for a chance to win Grand prize<br clear='all'/>
    <a class="in_fr inv_mng" href="javascript:void(0)" title="Invite Friend"></a>
</div>
<form id="frm_store_close" name="frm_store_close">
	<input type="text" name="email" id="email" value="Enter Your Email" onfocus="if(this.value=='Enter Your Email')this.value='';" onblur="if(this.value=='')this.value='Enter Your Email';" />
    <input type="text" name="region" id="region" value="Enter Your City" onfocus="if(this.value=='Enter Your City')this.value='';" onblur="if(this.value=='')this.value='Enter Your City';" /><br />
    <?php
		if($frm_email_id != '') { ?>
			<input type="hidden" name="invite" value="<?php echo $frm_email_id;?>"/>	
	<?php	}
	?>
    <input type="submit" name="submit" value="RSVP" />
</form>
</div>

</div>
<br clear="all" />
<div class="footer">
	<a class="fb" href="http://www.facebook.com/ShopClues/" title="Facebook" target="_blank">Special offers for<br />our special fans</a>
	<a class="tt" href="http://www.twitter.com/ShopClues/" title="Twitter" target="_blank">Tweets about the<br />great deals</a>
	<a class="in_fr" href="javascript:void(0)" title="Invite Friend"></a>
</div>

    <div id="invite_friend">
    <a class="in_fr close" href="javascript:void(0)" title="Close"></a>
	<div class="succe" style=" display:none;"></div>
	<form id="frm_invite_friend" name="frm_invite_friend">
    	<strong>Invite Friend</strong><br />
		<p><label>Your email address:</label><input type="text" name="frm_email" id="frm_email" value="Enter Your Email" onfocus="if(this.value=='Enter Your Email')this.value='';" onblur="if(this.value=='')this.value='Enter Your Email';" /></p>
		<p><label>Your friends<br />email address:</label><div class="textarea"><textarea name="ref_email" id="ref_email" ></textarea></div></p>
        <div class="right_mng"><small class="msg">separate email addresses with comma.</small><br />
		<input type="submit" name="submit" value="Invite Friend" /></div>
	</form>
</div>
<script type="text/javascript">
//<![CDATA[
	var _gaq = _gaq || [];
	_gaq.push(["_setAccount", "UA-27831792-1"]);
	_gaq.push(["_trackPageview"]);
	
	(function() {
		var ga = document.createElement("script");
		ga.src = ("https:" == document.location.protocol ? "https://ssl" : "http://www") + ".google-analytics.com/ga.js";
		ga.setAttribute("async", "true");
		document.documentElement.firstChild.appendChild(ga);
	})();
//]]>
</script>

<script type="text/javascript">


	$(document).ready(function () {
	  	$("#frm_store_close").submit(function() {
    	
		var email = $("input#email").val();
    	if (email == "") {
          $("label#email").show();
          $("input#email").focus();
          return false;
        } else {
			if(!validateEmail(email)) {
				$("label#email").show();
          		$("input#email").focus();
				return false;
			}
		}		
    	var region = $("input#region").val();
    	if (region == "" || region == "Enter Your City") {
          $("label#region").show();
          $("input#region").focus();
          return false;
        }		
    	var dataString = $(this).serialize();
    	//alert (dataString);return false;
    	
		  $.ajax({
		  type: "POST",
		  url: "process.php",
		  data: dataString,
		  success: function(s) {
			$('.succ').show();
			$('#frm_store_close').html("");
			
		  }
		 });
        return false;
    	});
		
		$("#frm_invite_friend").submit(function() {
    	
		var ref_email = $("input#ref_email").val();
		var frm_email = $("input#frm_email").val();
    	if (frm_email == "") {
          $("label#frm_email").show();
          $("input#frm_email").focus();
          return false;
        } else {
			if(!validateEmail(frm_email)) {
				$("label#frm_email").show();
          		$("input#frm_email").focus();
				return false;
			}
		}
		if (ref_email == "") {
          $("label#ref_email").show();
          $("input#ref_email").focus();
          return false;
        } 		
    	var dataString = $(this).serialize();
    	//alert (dataString);return false;
    	
		  $.ajax({
		  type: "POST",
		  url: "send_mail.php",
		  data: dataString,
		  success: function(s) {
			$('.succe').html("Your friend has been invited to join the excitement.");
			$('.succe').show();
			$('#frm_invite_friend').html("");
		  }
		 });
        return false;
    	});
		
		$(".in_fr").click(function() {
			
			$("#invite_friend").slideToggle();
			
			});
		
		$("#ref_email").bind("keypress", function(e) {
			if (e.keyCode == 13) {
				alert('Please use comma to separate the Email ID.');
				return false;
			}
		});
	
	
	});	


function validateEmail(email) 
{ 
 var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/ 
 return email.match(re) 
}
</script>
</body>				    
</html>
