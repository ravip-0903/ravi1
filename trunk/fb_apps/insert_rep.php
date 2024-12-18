
	<?php
		session_start();
	  if($_SERVER['REQUEST_METHOD']=='POST')
	  {
			if(@($_REQUEST['reply']))
			{	
					
				
					$_SESSION['reply_data']['title'] = $rep_comment =  ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a href=\"\\0\">\\0</a>",$_REQUEST['reply']);
					$_SESSION['reply_data']['pid'] = $pid = $_REQUEST['pid']; 
				

					if($user_id) 
					{	 	
				$_SESSION['reply_data']['title'] = $rep_comment = ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a href=\"\\0\">\\0</a>",$_REQUEST['reply']);
				$_SESSION['reply_data']['pid'] = $pid = $_REQUEST['pid'];
						try  
							{	
							
								include_once('user.php');
			//Insertion take place into 2 tables fb_comment and fb_users
			
						$cmnt = db_query("insert into clues_fb_comment (comment,fb_id,parent_comment_id,status) values('".$rep_comment."','".$fbid."','".$pid."','A') ");
								unset($_SESSION['reply_data']['title']);
								unset($_SESSION['reply_data']['pid']);
								  header( "Location:index.php" );
							}
							catch(FacebookApiException $e) 
							{
								$login_url = $facebook->getLoginUrl(array('scope' => 'email,user_birthday')); 
								error_log($e->getType());
								error_log($e->getMessage());
							}   
					}
					else 	
					{
						$login_url = $facebook->getLoginUrl(array('scope' => 'email,user_birthday')); 
						header( "Location: " . $login_url);
					}	
				}
			}

		if($_SESSION['reply_data']['title'] != '' && $_SESSION['reply_data']['pid'] != '' && isset($_REQUEST['code'])){
	//echo '<pre>';print_r($_SESSION);echo $user_id;die("Hello");
			if($user_id) 
			{	 	
				$rep_comment =	$_SESSION['reply_data']['title'] ;
				$pid = 	$_SESSION['reply_data']['pid'] ;
				
					try  
					{	
			
						include_once('user.php');
						
				//Insertion take place into 2 tables fb_comment and fb_users
				
						$cmnt = db_query("insert into clues_fb_comment (id,comment,fb_id,parent_comment_id,status) values('','".$rep_comment."','".$fbid."','".$pid."','A') ");
						unset($_SESSION['reply_data']['title']);
						unset($_SESSION['reply_data']['pid']);
					    header( "Location:index.php" );
					}
					catch(FacebookApiException $e) 
					{
					$login_url = $facebook->getLoginUrl(array('scope' => 'email,user_birthday')); 
					error_log($e->getType());
					error_log($e->getMessage());
					} 
				}  
			}
	?>
