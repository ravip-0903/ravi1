
	<?php
		include_once('fbtime.php');
		include_once('insert_rep.php');
		include_once('smiley.php');
	?>	
	<?php
		
			$result = db_get_array("select cfc.title , cfc.comment , cfc.fb_id , cfc.parent_comment_id , cfc.id, cfc.date_created, cfc.status,
								cfu.link , cfu.name from 
								clues_fb_comment cfc INNER JOIN clues_fb_users cfu ON
								 cfu.uid = cfc.fb_id where cfc.status != 'D'
								 order by id desc");
								

			foreach($result as $row)	
			{
				
				$title1 = $row['title'];
				$title =  parseSmiley($title1) ;
				$comment1 = $row['comment'];
				$comment =  parseSmiley($comment1) ;
				$link = $row['link'];
				$name = $row['name'];
				$fb_id = $row['fb_id'];
				$parentid = $row['parent_comment_id'];
				$id = $row['id'];
				$datetm = $row['date_created'];
				$status = $row['status'];
				if($parentid == 0)
				{
					 $thread[$id]['new'] = array("id" => $id, "title" => $title, "comment" => $comment, "datetm" => $datetm, "name" => $name, "fb_id" => $fb_id, "link" => $link, 
					 						"status" => $status);
				}
				else 
				{
					$thread[$parentid]['replies'][] = array("id" => $id, "comment" => $comment, "datetm" => $datetm, "name" => $name, "fb_id" => $fb_id, "link" => $link, "status" 
														=> $status);
				}
			}
						//data retrieved in two thread subset replies and new used to display below..
			
				foreach($thread as $th)
				{
				
					if($th['new']['status'] =='A' || $th['new']['status'] =='C')
					{
					$profile_pic =  "http://graph.facebook.com/".$th['new']['fb_id']."/picture";
					echo "<div style='float:left; width:786px; padding-bottom:2px; margin-top:20px;'><div class='fb_pro_pic'><a href = \"".$th['new']['link']."\" 
						style='border:0;'>
							<img src=\"" . $profile_pic . "\" /></a></div>"; 
					echo "<div class='coment_box_fb'>";
					echo "<b class='fb_pro_desc'>".$th['new']['title']."</b>"."<div class='fb_pro_cmnt'>".$th['new']['comment']."</div>";	
					echo "<div class='fb_share_box'><a href = \"".$th['new']['link']."\"> ".$th['new']['name']." </a>";
					echo "&nbsp;-&nbsp;".relativeTime($th['new']['datetm']);
					echo "</div>";
					echo "<form method ='post' action =''>";
					echo "<div class='fb_cmnt_box_txt'><input type = 'text' name = 'reply' class = 'reply'  value = 'write a reply'/></div>";
					echo "<input type = 'hidden' name = 'pid'  value ='".$th['new']['id']."' />";
					
					echo "</div></div>"; 
					
					if(isset($th['replies']))
					{	
						foreach($th['replies'] as $reply)
						{
								$profile_pic =  "http://graph.facebook.com/".$reply['fb_id']."/picture";
								echo "<div class='fb_pro_cmnt_inside'><div style='float:left; width:40px;'><a class='fb_pro_cmnt_name_ins' href = \"".$reply['link']."\" > <img 
											src=\"" . $profile_pic . "\" width='30' height='30' style='margin-top:5px; margin-left:5px;' /></a></div>"; 
								echo "<div class='fb_pro_cmnt_ins_msg'>";
								echo "<div class='fb_pro_box_ins_blk'><a class='fb_pro_cmnt_name_ins' href = \"".$reply['link']."\"> ".$reply['name']." </a>";
								echo $reply['comment']."</div>";	
								echo "<div class='clearbothnl'></div><div class='fb_pro_rel_time'>".relativeTime($reply['datetm'])."</div>";
								//echo "&nbsp;-&nbsp;<input type='submit' name='feed' value='share' />";
								echo "</div></div>";
						}
						echo "";
					}
					}
				echo "</form>";
				}
			?>
				<!--Script to make textbox text blur on mouseover-->	
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js" type="text/javascript"></script>
		<script type="text/javascript">
		$(document).ready(function() {
		var fill = $(".reply").val();
		
		$(".reply").on('focus', function() { 
			$(this).val('');
		}).on('blur', function() {
			if ($(".reply").val() == "") {
				$(this).val(fill);
			}
		});
		
		});
		</script>
       	