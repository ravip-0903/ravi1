<?php

include('conn.php');
//THis below code is used to bypass security in IE8.
         header("p3p: CP=\"ALL DSP COR PSAa PSDa OUR NOR ONL UNI COM NAV\"");
if(isset($_REQUEST['email_check']))
    {
    $val = db_get_array("select * from clues_fb_quiz where user_id=".$user_id);
    $att = $val[0][attempts_used];
    $att++;
    $win = $val[0][total_win];
    $loss = $val[0][total_loss];
    $combi = $val[0][combination];
    $first = substr($combi,0,1);
    $sec = substr($combi,1,1);
    $third = substr($combi,2,2);
	if($_REQUEST['email_check'] =='ghghgh')
	{
            
		
        }
        else if($first == $sec && $sec == $third)
        {
                
                 $win++;
            $one = db_query("update clues_fb_quiz set attempts_used=".$att.",total_win=".$win.",last_win=1 where user_id=".$user_id); 
        }
        else
        {
            $loss++;
            $one = db_query("update clues_fb_quiz set attempts_used=".$att.",total_loss=".$loss." where user_id=".$user_id);
        }
        
    }
    else
    {
        header("location:index.php?time=".time());
    }
?>