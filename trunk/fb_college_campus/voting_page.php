<script src="scripts/jquery.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="scripts/facybox.css" media="all" />
<script type="text/javascript" src="scripts/facybox.js"></script>
<script type="text/javascript" src="scripts/facybox_ext.js"></script>

 
<div id="fb-root"></div>
<script>(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=208941289250794";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<?php

//THis below code is used to bypass security in IE8.
header("p3p: CP=\"ALL DSP COR PSAa PSDa OUR NOR ONL UNI COM NAV\"");

$sortings = array (
	'likes' => "like_click",
	'id' =>'id',
	'time_created ' =>"time_created"
	);
$directions = array (
	'asc' => 'asc',
	'desc' => 'desc'
	);
if (isset($_REQUEST['id']) && $_REQUEST['id'] == 'id') {
	$sort_by = "id";
}
$sort_order = empty($_REQUEST['sort_order']) ? '' : $_REQUEST['sort_order'];
$sort_by = empty($_REQUEST['sort_by']) ? '' : $_REQUEST['sort_by'];
if (empty($sort_order) || !isset($directions[$sort_order])) {
	$sort_order = '';
}

if (empty($sort_by)) {
   //$sort_by = 'date_created';
	$sort_by = 'like_click';
}
$sort_order  = (($sort_order == 'desc') ? 'asc' : 'desc');

if(isset($_REQUEST['user_name']))
	$name = $_REQUEST['user_name'];
else
	$name='';
?>
<style type="text/css">
	.fb-like{
		overflow: hidden;
	}
	.bg_body{
		background:url(http://cdn.shopclues.com/images/banners/bg_body.gif) left top no-repeat;
		width:783px;
		height:725px;
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
	.searchbar{
		float:left;
		margin-left:30px;
		margin-top:60px;
		color:#fff;	
	}
	.searchbar .textbox{
		float:left;
		background-color:#fff;
		border:0px none;
		border-radius:5px;
		outline:none;
		padding:3px;
	}
	.searchbar .buttonSearch{
		float:left;
		margin-left:5px;
		border:0px none;
		background:url(http://cdn.shopclues.com/images/banners/b_search.gif) left top no-repeat;
		width:82px;
		height:25px;
	}
	.searchbar div{	
		float:left;
		margin-top:5px;
		margin-left:10px;
	}
	.uploadYourPic{
		float:right;
		background:url(http://cdn.shopclues.com/images/banners/b_uploadyourpic.gif) left top no-repeat;
		width:228px;
		height:53px;
		margin-right:30px;
		margin-top:40px;
	}


	.black_overlay{
		display: none;
		position: absolute;
		top: 0%;
		left: 0%;
		width: 100%;
		height: 100%;
		background-color: black;
		z-index:1001;
		-moz-opacity: 0.8;
		opacity:.80;
		filter: alpha(opacity=80);
	}
	.white_content {
		display: none;
		position: absolute;
		top: 25%;
		left: 25%;
		width: 50%;
		height: 50%;
		padding: 16px;
		border: 16px solid orange;
		background-color: white;
		z-index:1002;
		overflow: auto;
	}


	.gallery{
		float:left;
		width:723px;
		margin-left:30px;
		margin-right:30px;
		margin-top:10px;
		height:468px;
		overflow: hidden;

	}
	.gallery .photo{
		float:left;
		width:171px;
		height:224px;	
		margin-right:6px;
		margin-bottom:10px;
		background-color:#fff;
	}
	.gallery .photoDetails{
		float:left;
		width:171px;
		height:224px;	
		margin-right:6px;
		margin-bottom:10px;
		background-color:#fff;
	}
	.gallery .photoDetails .photo{
		float:left;
		width:158px;
		height:150px;
		margin-left:7px;
		margin-top:7px;
	}
	.gallery .photoDetails .name{
		float:left;
		width:100%;
		text-align:center;	
		color:#333;
	}
	.gallery .photoDetails .name span{
		color:#4965b5;
	}
	.gallery .photoDetails .comment{
		float:left;
		width:100%;
		text-align:center;	
		color:black;
		font-size:11px;
	}
	.gallery .photoDetails .like{
		float:left;
		margin-left:14px;	
	}
	.gallery .photoDetails .share{
		float:left;
		margin-left:5px;	
	}
	.pagination{
		float:right;	
		margin-right:60px;
	}
	.pagination span
	{
		float:left;
		background-color:#042894;
		border:1px solid #3d57a4;
		padding:5px;
		color:#3d57a4;
		margin-right:1px;	
	}
	.pagination a{
		float:left;
		background-color:#fff;
		padding:5px;
		margin-right:1px;	
		color:#4a67b7;
		border:1px solid #fff;
	} 
	.pagination .disabled{
		background-color:#fff;
		border:1px solid #3d57a4;
		color:#3d57a4;
		border:1px solid #3d57a4;
	}
	.share_link{
		background: #eceef5;
		-webkit-border-radius: 3px;
		border: 1px solid #cad4e7;
		cursor: pointer;
		padding: 2px 6px 4px;
		white-space: nowrap;
		color: #3b5998;
	}
	.sp_like{
		background: url(https://fbstatic-a.akamaihd.net/rsrc.php/v2/y-/r/tbhIfdAHjXE.png) 4px 2px no-repeat;
		display: inline-block;
		height: 10px;
		padding-left: 20px;
		font-size: 12px;
		text-decoration: none;
		padding-top: 2px;
		padding-bottom:6px;
	}
</style>


<?php
define('AREA', 'C');
define('AREA_NAME', 'customer');
require  dirname(__FILE__) . '/../prepare.php';
require  dirname(__FILE__) . '/../init.php';	
	// include your code to connect to DB.

	$tbl_name="clues_photo_contest";		//your table name
	// How many adjacent pages should be shown on each side?
	$adjacents = 7;
	
	/* Setup vars for query. */
	$targetpage = "voting_page.php"; 	//your file name  (the name of this file)
	$limit = Registry::get('config.grid_limit_fb'); 					//how many items to show per page
	$page = $_GET['page'];
	if($page) 
		$start = ($page - 1) * $limit; 			//first item to display on this page
	else
		$start = 0;								//if no page var is given, set start to 0

	$sql = "SELECT * FROM $tbl_name where type=1";

	$name = $_REQUEST['user_name'];

	if(isset($_REQUEST['search_sub']))
	{

		if($_REQUEST['user_name'])
		{
			$sql.= (" and name like '%".$_REQUEST['user_name']."%'");
		}

	}

	$query = "SELECT COUNT(*) as num FROM $tbl_name where type=1";

	if($_REQUEST['user_name'])
	{
		$query.= (" and name like '%".$_REQUEST['user_name']."%'");
	}
	$total_pages =db_get_field($query);
	$sql.=  " ORDER BY $sort_by $sort_order LIMIT $start, $limit";

        //echo $sql;

	$result = db_get_array($sql);
	/* Setup page vars for display. */
	if ($page == 0) $page = 1;					//if no page var is given, default to 1.
	$prev = $page - 1;							//previous page is page - 1
	$next = $page + 1;							//next page is page + 1
	$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;						//last page minus 1
	
	/* 
		Now we apply our rules and draw the pagination object. 
		We're actually saving the code to a variable in case we want to draw it more than once.
	*/
		$pagination = "";
		if($lastpage > 1)
		{	
			$pagination .= "<div class=\"pagination\">";
		//previous button
			if ($page > 1) 
				$pagination.= "<a href=\"$targetpage?page=$prev&search_sub=search&user_name=$name&sort_by=like_click&sort_order=$sort_order\"> previous</a>";
			else
				$pagination.= "<span class=\"disabled\"> previous</span>";	

		//pages	
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<span class=\"current\">$counter</span>";
				else
					$pagination.= "<a href=\"$targetpage?page=$counter&search_sub=search&user_name=$name&sort_by=like_click&sort_order=$sort_order\">$counter</a>";					
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"$targetpage?page=1\">1</a>";
				$pagination.= "<a href=\"$targetpage?page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage?page=$lpm1\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage?page=$lastpage\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"$targetpage?page=1\">1</a>";
				$pagination.= "<a href=\"$targetpage?page=2\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage?page=$counter\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<a href=\"$targetpage?page=$next&search_sub=search&user_name=$name&sort_by=like_click&sort_order=$sort_order\">next </a>";
		else
			$pagination.= "<span class=\"disabled\">next </span>";
		$pagination.= "</div>\n";		
	}

	$server_loc = Registry::get('config.https_location');
	

	

	echo "
	<div class='bg_body'>
		<div class='logo_shopclues'></div>
		<div class='clickshotwin'></div>
		<div class='clearboth'></div>";

		echo "<div class='searchbar'>
		<form name='search' id='search' method='post' action='voting_page.php?search_value=search'>
			<input type='name' name='user_name' id='user_name' class='textbox' />
			<input type='submit' name='search_sub' id='search_sub' value='' class='buttonSearch' />
			<div>

				<a href='voting_page.php?sort_by=like_click&search_sub=search&user_name=$name&sort_order=$sort_order'>Order by 'Likes'</a>
				| 
				<a href='voting_page.php?sort_by=time_created&search_sub=search&user_name=$name&sort_order=$sort_order'>Most recent first</a>
			</div>
		</form>
	</div>
	<a name='form' id='form' href='index.php' href='' class='uploadYourPic'></a>
	<div class='clearboth'></div>
	<div class='gallery'>
		";
		if(count($result) <1)
		{
			echo "<div style='margin-left: 100px;margin-top: 79px;'>No result found for the string : '".$name."'</div>";
		}
		foreach($result as $key=>$k)
		{        
			$cdn_url = Registry::get('config.fb_image_host_url'); 
			$likes = $k['like_click'];
			$created = $k['time_created'];
			$name = $k['name'];
			if(strlen($name) > 17)
			{
				$name= substr($name,0,16)."...";
			}
			$id = $k['id'];
			$path = $result[$key]['pic_path'];
			$id_path = explode('.',$path);
			$id_path = $id_path[0];
			$profile_pic =  $cdn_url."images/fb_photo_contest/".$path;
			$absolute_path = $server_loc."/images/fb_photo_contest/".$path;
			$absolute_path_fb = $cdn_url."images/fb_photo_contest/".$path;
							//echo $absolute_path;
							//$title='';//urlencode(fn_get_lang_var('title_for_share_pic'));
			$url=urlencode($absolute_path);
			$image=urlencode($absolute_path_fb);
			$k['description'] = addslashes($k['description']);
			$desc_half = substr($k['des
				cription'],0,25);
			$k['title'] = addslashes($k['title']);
			$title_half = substr($k['title'],0,22)."...";
			$url_to_share = $server_loc.'/fb_college_campus/index.php?id='.$id;
			$url_to_photo = $server_loc.'/fb_college_campus/voting_page.php';
			$image_page = $server_loc.'/fb_college_campus/my_page.php';
			$time = time();

			echo "

			<div class='photoDetails'>
				<a rel='facybox' href='javascript:void(0)' style='cursor: hand;' id=$id onclick='contest($id);' class='photo'>
					<img height='150' width='158' src=\"" . $profile_pic . "\" alt='Photo' />
				</a>							
				";
//echo $likes;
				?>                        
				<div id="votingpic_<?php  echo $id; ?>" style="position:fixed; z-index:9999; top:125px; margin-left:-391px; left:50%; width:783px; height:530px; display:none; overflow:hidden;">
					<a style="position:absolute; right:0px; margin-top:20px; width:30px; height:30px; text-align:right;" class="close" id="<?php  echo $id; ?>">
						<img src="http://cdn.shopclues.com/skins/basic/customer/images/icons/close_popupbox.png"  alt="Close"/>
					</a>



					<?php echo "<iframe src= $image_page?id=$id&time=$time  width='783'  height='540' border='0' style='border:0px none; float:left;  clear:both;' ></iframe>";
					?>
				</div>

				<div class="name"><span>BY</span> <?php echo $name; ?></div>
				<div class="comment"><?php echo $title_half;?></div>

				<div class="like">
					<div class="fb-like" data-href="<?php echo $absolute_path_fb; ?>" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false" data-font="arial">
					</div>
				</div>

				<div class="share">
					<a style="color:#333;" onClick="window.open('https://www.facebook.com/dialog/feed?app_id=208941289250794&link=<?php  echo $url_to_share; ?>&picture=<?php echo $absolute_path_fb; ?>&description=<?php  echo $k['description'];  ?>&redirect_uri=<?php  echo $url_to_photo;  ?>','sharer','toolbar=0,status=0,width=548,height=325');" class="share_link sp_like" href="javascript: void(0)">Share</a>
				</div>
			</div>                       

			<?php
		}
		?>
	</div>
	<?php echo $pagination; ?>
</div>



<script src="scripts/jquery.min.js" type="text/javascript"></script> 
<script type="text/javascript">
	function contest(id)
	{
		$('#facybox_overlay').css('top','0px');
		$('#votingpic_'+id).show();

	}

	$(document).ready(function(){

		$('.close').click(function(){

			$('#facybox_overlay').css('top','1000000');

			var pic_id = $(this).attr('id');
			$('#votingpic_'+pic_id).hide();

		});

	});
</script> 
