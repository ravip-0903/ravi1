		<?php
		session_start();
	  if($_SERVER['REQUEST_METHOD']=='POST')
	  {
	  	
			if(@($_REQUEST['submit']))
			{
			//Created session to store value when form get submitted for a new app user.
				
				$_SESSION['comment_data']['title'] = $title =   ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a href=\"\\0\">\\0</a>",$_REQUEST['title']); ;
				$_SESSION['comment_data']['comment'] = $comment = ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a href=\"\\0\">\\0</a>",$_REQUEST['comment']); 
				

					if($user_id) 
					{	 	
						try  
							{	
									include_once('user.php');
									$cmnt = db_query("insert into clues_fb_comment (id,title,comment,fb_id,status) values('','".$title."','".$comment."','".$fbid."','A') ");
								unset($_SESSION['comment_data']['title']);
								unset($_SESSION['comment_data']['comment']);
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
		
		if($_SESSION['comment_data']['title'] != '' && $_SESSION['comment_data']['comment'] != '' && isset($_REQUEST['code'])){
						

	//echo '<pre>';print_r($_SESSION);echo $user_id;die("Hello");
			 if($user_id) 
			{	 	
				$title =	$_SESSION['comment_data']['title'] ;
				$comment = 	$_SESSION['comment_data']['comment'] ;
				try  
				{	
						include_once('user.php');
						$cmnt = db_query("insert into clues_fb_comment (id,title,comment,fb_id,status) values('','".$title."','".$comment."','".$fbid."','A') ");
						unset($_SESSION['comment_data']['title']);
						unset($_SESSION['comment_data']['comment']);
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
