<body style=overflow:hidden;">
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=208941289250794";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>

	<style type="text/css">
		.fb-like{
		overflow: hidden;
	}

</style>	
<style type="text/css">
	.bg_body{
	background:url(http://cdn.shopclues.com/images/banners/bg_body.gif) left top no-repeat;
	width:783px;
	height:560px;
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
.uploadYourPic{
float:right;
background:url(http://cdn.shopclues.com/images/banners/b_uploadyourpic.gif) left top no-repeat;
width:228px;
height:50px;
margin-right:30px;
margin-left:30px;
}
.details{
float:left;
width:703px;
height:330px;
padding:10px;
margin-left:30px;
background-color:#fff;
margin-top:20px;
border: 5px solid #cccccc;

}
.details .photo{
float:left;
width:500px;
height:300px;
}
.details .photo .like{
float:right;
margin-top:5px;
}
.details .detail{
float:left;
margin-left:10px;	
width:190px;
}
.details .detail div{
float:left;
width:100%;
color:#333;
font-size:12px;
padding-bottom:10px;
}
.details .detail div span{
color:#0b4cba;
font-size:13px;
font-weight:bold;
}
.clickToVote {
float: right;
background: url(http://cdn.shopclues.com/images/banners/img_BackToGallery1.gif) left top no-repeat;
width: 228px;
height: 50px;
}
</style>


<?php


if(!isset($_REQUEST['id']))
{

	$_REQUEST['id'] = $_SESSION['ids'];
}
else
{
	define('AREA', 'C');
	define('AREA_NAME', 'customer');
	require  dirname(__FILE__) . '/../prepare.php';
	require  dirname(__FILE__) . '/../init.php';	
}
// include your code to connect to DB.
$server_loc = Registry::get('config.http_location');
$data = db_get_array("select * from clues_photo_contest where id=".$_REQUEST['id']." and type=1");
if(!$data)
{
	header("location:index.php");
}
$cdn_url = Registry::get('config.fb_image_host_url'); 
$path = $data[0]['pic_path'];
$url_to_share = $server_loc.'/fb_college_campus/index.php?id='.$data[0]['id'];

$profile_pic =  $cdn_url."images/fb_photo_contest/".$path;
$absolute_path_fb = $cdn_url."images/fb_photo_contest/".$path;
$url_to_photo = $server_loc.'/fb_college_campus/voting_page.php';
$url_to_google = $server_loc.'/fb_college_campus/my_page.php?id='.$_REQUEST['id'];
if($data[0]['appeal'] =='')
{
	$data[0]['appeal'] = Registry::get('config.college_campus_appeal');
}

if(isset($_REQUEST['time']))
{
	echo "<div class='bg_body' style='background:none;'>";

}

else
{

	echo "<div class='bg_body'>
	<div class='logo_shopclues'></div>
	<div class='clickshotwin'></div>
	<div class='clearboth'></div>";
	echo "<a name='form' id='form' href='index.php' href='' class='uploadYourPic' style='margin-top:25px;'></a>";
	echo "<a name='form' id='form' href='voting_page.php' href='' class='clickToVote' style='margin-top:25px;'></a>";
}


echo "<div class='details'>
<div class='photo'>
	<img class='profile_pic' width='500' height='300'  src=\"" . $profile_pic . "\" />
	";
	?>
	<div class="clearboth"></div> 
	<a href="https://plus.google.com/share?url=<?php  echo $url_to_google; ?>" onclick="javascript:window.open(this.href,
		'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><img
		src="https://www.gstatic.com/images/icons/gplus-64.png" style="margin-left: 342px; width:27px;margin-top: 3px; alt="Share on Google+"/></a>           
		<div class="like">
			<div class="fb-like" data-href="<?php echo $profile_pic; ?>" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false" data-font="arial" ></div>                        
			<a style="color:#4a67b7;" onClick="window.open('https://www.facebook.com/dialog/feed?app_id=208941289250794&link=<?php  echo $url_to_share;  ?>&picture=<?php echo $absolute_path_fb; ?>&description=<?php  echo $data[0]['description'];  ?>&redirect_uri=<?php  echo $url_to_photo;  ?>','sharer','toolbar=0,status=0,width=548,height=325');" class="share_link sp_like" href="javascript: void(0)">Share</a>
		</div>
	</div>
	<div class="detail">
		<div>
			<span>By:</span> <?php echo $data[0]['name']; ?>
		</div>
		
		<div>
			<span>Title:</span> <?php echo $data[0]['title']; ?>
		</div>

		<div>
			<span>What your picture says:</span> <?php echo $data[0]['description']; ?>
		</div>
		
		<div>
			<span>Vote Appeal:</span>
			<br>
			<?php echo $data[0]['appeal']; ?>
		</div>
		
	</div>
</div>            
</div>
</div>