 <?php
          include_once('conn.php');
    if(isset($_REQUEST['error_reason']) && isset($_REQUEST['error']) && isset($_REQUEST['error_description']))
    {
        header( "Location:".$_REQUEST['redirect_url']);
    } 
       
        if($user_id) 
        {
		 try  
            {
                $fbshare = $facebook->api('/me/feed', 'POST', array (
                             'message'          =>  fn_get_lang_var('share_message'),	
                             'link'		=>  $_REQUEST['pro_url'],
                             'picture'		=>  $_REQUEST['pro_image'],
                             'name'		=>  $_REQUEST['pro_name'],
                             'caption'		=>  fn_get_lang_var('order_on_shopclues_caption'),
                             ));
               fn_set_notification('N', '', fn_get_lang_var('share_reply'));                     
               header( "Location:".urldecode($_REQUEST['redirect_url']));		
            } 			
            catch(FacebookApiException $e) 
            {
                $login_url = $facebook->getLoginUrl(array('scope' => 'publish_stream')); 
                header( "Location: " . $login_url);
            }   
           
        }

        else
        {
           	 $login_url = $facebook->getLoginUrl(array('scope' => 'publish_stream'));
            header( "Location: " . $login_url);
        }
    if(isset($_REQUEST['error_reason']) && isset($_REQUEST['error']) && isset($_REQUEST['error_description']))
    {
        header( "Location:".$_REQUEST['redirect_url']);
    } 
?>
