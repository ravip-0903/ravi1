<?php
if ( !defined('AREA') )	{ die('Access denied');	}

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	set_time_limit(36000);
	
	if ($mode == 'compileskinsonce') 
	{
	    //compile the templates now one time only
	    fn_set_notification('N', fn_get_lang_var('notice'), $lang.notImplemented, "K");
	    
	}
	
	if ($mode == 'toggletplcaching') 
	{
	    //toggle force compile between on/off
	    $files_to_search = array(DIR_ROOT."/lib/templater/Smarty.class.php") ;
              
        $snr = new File_SearchReplace("var \$force_compile   =  true;",'',$files_to_search,'',false) ;
        $toggleCache = $snr->search($files_to_search[0]);
        if(isset($toggleCache[0]))
        {
            //toggle off
            $search_string  = "var \$force_compile   =  true;";
            $replace_string = "var \$force_compile   =  false;";
            
            $snr = new File_SearchReplace($search_string,$replace_string,$files_to_search,'',false);
                                      
            $snr->doSearch();
                                      
        }else
        {
            //toggle on
            $search_string  = "var \$force_compile   =  false;";
            $replace_string = "var \$force_compile   =  true;";
            $snr = new File_SearchReplace($search_string,$replace_string,$files_to_search,'',false);
            
            $snr->doSearch();
                                      
        }
                 
        if($replace_string=="var \$force_compile   =  true;")
        {
            fn_set_notification('N', fn_get_lang_var('notice'), "Forced compilation of smarty templates <b>enabled</b>. <span style=\"color:red\">This will adversely affect site performance!</span>", "K");
        }else {
            fn_set_notification('N', fn_get_lang_var('notice'), "Forced compilation of smarty templates <b>disabled</b>. <span>This will speed up site performance considerably.</span>", "K");
            
        }
        
        
	} 
	
	//adds 10k users to the system for testing large batch processing algorithms
	if ($mode == 'add10kuserstest') 
	{
	    $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $startTime = $mtime; 
        //ob_start();
        //ob_flush();
	    fn_start_scroller();
	    
	    fn_echo("
            <style>         
            html, BODY { height: 100%; }
            div.pagewrapper {
            	width: 100%; height: 100% overflow: auto; 
			}
            div.overlay {
                position: fixed; right: 40%; top: 50%;
                background: lightgrey;
                font-family:Verdana, Geneva, sans-serif;
            }  
            div.overlaycontrols {
                position: fixed; right: 32%; top: 50%;
                background: lightgrey;
                font-family:Verdana, Geneva, sans-serif;
            }  
            div.overlaytitle {
                position: fixed; right: 40%; top: 40%;
                
                font-family:Verdana, Geneva, sans-serif;
            }    
            </style> 	
			
            <script>
            function stopProcessing()
            {
            	window.history.back();
            	document.getElementById('overlay').innerHTML='...';
            	document.getElementById('stopBtn').value='Stopping...';
            	document.getElementById('stopBtn').disabled=true;
            }
            </script>
            
            <div id='pagewrapper' class='pagewrapper'></div>
            <div id='overlaytitle' class='overlaytitle'><h2>Processing Users</h2></div> 
        	<div id='overlay' class='overlay'>...</div> 
        	<div id='overlaycontrols' class='overlaycontrols'><input id='stopBtn' type='button' onclick='stopProcessing();' value='Stop'/></div>        	 
	    ");
	    
	    devtools_update_overlay("Starting up");
	    
        for($i=1;$i<10000;$i++)
        {
            $user_data['user_type'] = 'C';
			$user_data['password1'] = fn_generate_password();
			$user_data['password2'] = $user_data['password1'];
			$user_data['status'] = ' ';
			$user_data['company_id'] = $company_id;
			$user_data['email'] = 'test@test'.$i.'.com';
			$user_data['company'] = ' ';
			$user_data['last_login'] = 0;
			$user_data['lang_code'] = 'EN';
			$user_data['password_change_timestamp'] = 0;
			$user_data['is_root'] = 'N';

			// Copy vendor admin billing and shipping addresses from the company's credentials
			$user_data['firstname'] = $user_data['b_firstname'] = $user_data['s_firstname'] = "test".$i;
			$user_data['lastname'] = $user_data['b_lastname'] = $user_data['s_lastname'] = "test".$i;

			$user_data['b_address'] = $user_data['s_address'] = "test";
			$user_data['b_city'] = $user_data['s_city'] = "test";
			$user_data['b_country'] = $user_data['s_country'] = "test";
			$user_data['b_state'] = $user_data['s_state'] = "test";
			$user_data['b_zipcode'] = $user_data['s_zipcode'] = "test";
            
			$updateResult = fn_update_user(0, $user_data, $null, false, false, false);
			$updateResultString = '';
			if(!$updateResult)
			{
			    $updateResultString = 'failed';
			}
			fn_echo(' <br />Adding user: ' .$user_data[firstname] . ': ' . $user_data[email] . ' '.$updateResultString);
			devtools_update_overlay("$user_data[email] ");
			fn_echo("
            <script language='javascript'>
            //alert('');
            </script>"
        );
        }
        
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endTime = $mtime; 
		$opTime = $endTime-$startTime;
		
		fn_set_notification('N', fn_get_lang_var('notice'), "Completed \"add users test\" in <b>". round($opTime, 2)."</b> seconds." , "K");
		
		
		fn_stop_scroller();

		
		fn_echo("
            <script language='javascript'>
            document.getElementById('overlay').innerHTML='Completed successfully.';
            </script>"
        );
	}
	
	
	return array(CONTROLLER_STATUS_OK, "$index_script?dispatch=devtools.manage");
		
}
if ($mode == 'manage') 
{
	//initial stuff for the manage controller
} 
	
	
	


?>