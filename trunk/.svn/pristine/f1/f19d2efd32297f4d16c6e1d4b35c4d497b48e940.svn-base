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
	$sort_by = 'time_created';
}
$sort_order  = (($sort_order == 'asc') ? 'desc' : 'asc');

if(isset($_REQUEST['user_name']))
	$name = $_REQUEST['user_name'];
else
	$name='';
?>
<div style="width:793px; margin:auto; clear:both;">
	<form name="search" id="search" method="post" action="voting_page.php?search_value=search">

		<input type="name" name="user_name" id="user_name" style="float:left; width:150px; border:1px solid #dddddd; outline:none; border-radius:5px; padding:3px;" />
		<input type="submit" name="search_sub" id="search_sub" value="Search" style="float:left; border:0px; background-color:#007ac0; color:#fff; outline:none; border-radius:5px; padding:4px 10px;" />

		<a style="float:left; margin-left:10px; margin-top:5px; text-decoration:none; text-transform:capitalize; color:#007ac0; font:13px verdana;" href="voting_page.php?sort_by=like_click&search_sub=search&user_name=<?php echo $name; ?>&sort_order=<?php  echo $sort_order;  ?>">Order by 'Likes'</a>
		<div style="float:left; margin-top:5px; margin-left:10px; margin-right:10px; font-size:11px;">|</div>
		<a style="float:left; margin-top:5px; text-decoration:none; text-transform:capitalize; color:#007ac0; font:13px verdana;" href="voting_page.php?sort_by=time_created&search_sub=search&user_name=<?php echo $name; ?>&sort_order=<?php  echo $sort_order;  ?>">Most recent first</a>




	</form>

	<?php

	define('AREA', 'C');
	define('AREA_NAME', 'customer');
	require  dirname(__FILE__) . '/../prepare.php';
	require  dirname(__FILE__) . '/../init.php';	
// include your code to connect to DB.

	$tbl_name="clues_photo_contest";		//your table name
	// How many adjacent pages should be shown on each side?
	$adjacents = 7;
	
	/* 
	   First get total number of rows in data table. 
	   If you have a WHERE clause in your query, make sure you mirror it here.
	*/
	   /* Setup vars for query. */
	$targetpage = "voting_page.php"; 	//your file name  (the name of this file)
	$limit = Registry::get('config.grid_limit_fb'); 					//how many items to show per page
	$page = $_GET['page'];
	if($page) 
		$start = ($page - 1) * $limit; 			//first item to display on this page
	else
		$start = 0;								//if no page var is given, set start to 0
	
	/* Get data. 
         */


	$sql = "SELECT * FROM $tbl_name";

	$name = $_REQUEST['user_name'];

	if(isset($_REQUEST['search_sub']))
	{

		if($_REQUEST['user_name'])
		{
			$sql.= (" where name like '%".$_REQUEST['user_name']."%'");
		}

	}

	$query = "SELECT COUNT(*) as num FROM $tbl_name";

	if($_REQUEST['user_name'])
	{
		$query.= (" where name like '%".$_REQUEST['user_name']."%'");
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

	$server_loc = Registry::get('config.http_location');
	echo $pagination;

	if(count($result) <1)
	{
		echo "No result found for the string : '".$name."'";
	}


	echo "</div>
	<div class='present_cam_con'>
		<div class='cc_form_center'>
			<div class='present_cc_logo' stye='position:relative;'>
				<div class='inner_logo'><img src='https://images.shopclues.com/images/banners/icons/shopclues_logo.png' width='202' height='64' /><br />
					<img src='https://images.shopclues.com/images/banners/icons/capmus_connect_logo.png' style='margin-top:10px' width='254' height='84' />

					<a name='form' id='form' href='index.php'  style='position:absolute; margin-left: 214px; margin-top:-170px; '>
						<img src='https://images.shopclues.com/images/banners/img_uploadyourpic.gif'  width='141' height='129' />
					</a>

				</div>
			</div>
			<div class='pic_upload_cc'>
				<div class='cc_form'>
					<div class='cc_form_nl'>

						";

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
							$desc_half = substr($k['description'],0,25);
							$url_to_share = $server_loc.'/fb_photo_contest/index.php';
							$url_to_photo = $server_loc.'/fb_photo_contest/voting_page.php';
							echo "<div class='post_list'>  
							 <div class='pic_upload'><a href = \"".$profile_pic."\" style='border:0;' target='_new' >
        <img class='profile_pic' style='width: 130px; height: 100px;' src=\"" . $profile_pic . "\" /></a>
    </div>"; 	
							?>
							<div style="margin-top:5px; height:30px; color:#6475ab; text-align: center; font:bold 11px verdana;">
<span style="color:#b3b3b3; ">By</span> <?php echo $name; ?>
</div>

<div style="margin-top:3px; color:#6475ab; height:23px; text-align: center; font:11px verdana; color:#808080; font-family: Segoe, 'Segoe UI', 'DejaVu Sans', 'Trebuchet MS', Verdana, sans-serif">
<?php echo $desc_half  ; 
?>
</div>
							<div style="float:left; margin-top:10px;" class="fb-like" data-href="<?php echo $absolute_path_fb; ?>" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false" data-font="arial" >
							</div>
					
<a style="float:right; margin-top:10px;" onClick="window.open('https://www.facebook.com/dialog/feed?app_id=208941289250794&link=<?php  echo $url_to_share;  ?>&picture=<?php echo $absolute_path_fb; ?>&description=<?php  echo $k['description'];  ?>&redirect_uri=<?php  echo $url_to_photo;  ?>','sharer','toolbar=0,status=0,width=548,height=325');" class="share_link sp_like" href="javascript: void(0)">Share</a>

						</div>

						<?php  
					}
					?>

      <!--   <div class="pic_upload"><img src="pic_upload.png" /></div>
      <div align="center" class="user_name">dgdfgfgf</div> -->
  </div>

</div></div>
<div class="form_footer">

 <!-- <div>
  <a href="index.php" style="float: left; margin-left: 20px; background-color: #007ac0; color: #fff; padding: 5px 10px; text-decoration: none; border-radius: 5px; font: 13px verdana; text-transform:capitalize; margin-top:30px;">click to upload</a>
</div>-->


</div>
</div>
</div>


<style type="text/css">
	.fb_pro_pic{display:block;}
	.fb_pro_pic a{margin:auto; text-align:center; display: block;}
	.post_list {width: 132px; float:left; margin-right: 7px; padding-bottom:50px;}
	.share_link{background: #eceef5; -webkit-border-radius: 3px; border: 1px solid #cad4e7; cursor: pointer; padding: 2px 6px 4px; white-space: nowrap; color: #3b5998;}
	.sp_like{background-image: url(https://fbstatic-a.akamaihd.net/rsrc.php/v2/y-/r/tbhIfdAHjXE.png); background-size: auto;background-repeat: no-repeat; display: inline-block; height: 10px; background-position: 4px 1px; padding-left: 20px; font-size: 12px; text-decoration: none; padding-top: 2px;}
	.fb-like{
		overflow: hidden;
	}
	.present_cam_con{width:816px; height:689px; margin: 0px auto; background-image:url(https://images.shopclues.com/images/banners/icons/capmus_connect_bg_SC.jpg); background-repeat:no-repeat; background-position:top center;}
	.present_cc_logo{width:615px; margin: 0px auto; "><div style="width:615px; height:176px; background-image:url(https://images.shopclues.com/images/banners/icons/pic_upload_bg_top.png); background-repeat:no-repeat; background-position:top center; float:left; margin-top:16px}
	.inner_logo{width:251px; float:left; margin:25px 0px 0px 200px; text-align:center}
	.pic_upload_cc{width:615px; background-image:url(https://images.shopclues.com/images/banners/icons/pic_upload_bg_medium.png); background-repeat: repeat-y; background-position:top center; float:left;}
	.cc_form{width:570px; /*height:340px; overflow-x:hidden; overflow-y:scroll;*/ float:left; border:solid 1px #dedede; margin:20px 20px 10px 20px;}
	.cc_form_nl{width:100%; margin:15px 10px 10px 10px;}
	.pic_upload{border:solid 1px #CCCCCC;}
	.user_name{font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#000; padding-top:5px;}
	.form_footer{width:615px; height:104px; background-image:url(https://images.shopclues.com/images/banners/icons/pic_upload_bg_bottom.png); background-repeat:no-repeat; background-position:top center; float:left;}
	.cc_form_center{width:615px; margin:auto; display:block;}
	.pagination{float:right; font:11px verdana; text-transform:capitalize; color:#007ac0; margin-top:5px;}
	.pagination span{font:11px verdana; text-transform:capitalize; color:#007ac0; text-decoration:none; margin:0px 3px;}
	.pagination a{font:11px verdana; text-transform:capitalize; color:#007ac0; text-decoration:none; margin:0px 3px;}
</style>	