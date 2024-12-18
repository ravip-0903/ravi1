<?php

					if(($_REQUEST['feed']))
			{
		$comment = $_REQUEST['ptitle'];

				if($user_id) 
				{
	   				try  
			 		{
						
							$fbshare = $facebook->api('/me/feed', 'POST', array(   
										'message' => $comment,	
										'link'	  => 'www.yahoo.com'
							/*			'picture'		=> {$config.ext_images_host}{$pro_images.detailed.http_image_path},
										'name'			=> 'rahul',
										'caption'		=> {$lang.order_on_shopclues_caption},
										'description'	=>{$lang.purchase_from_shopclues_desc}
							*/
									 ));
							 header( "Location:index.php" );
					} 
					catch(FacebookApiException $e) 
					{
							$login_url = $facebook->getLoginUrl(array('scope' => 'publish_stream')); 
							error_log($e->getType());
							error_log($e->getMessage());
					}   
				}
				 else 	
				 {
						$login_url = $facebook->getLoginUrl(array('scope' => 'publish_stream')); 
						header( "Location: " . $login_url);
				 }
			}
		
?>
