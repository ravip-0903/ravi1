<?php
include_once('conn.php');
//THis below code is used to bypass security in IE8.
         header("p3p: CP=\"ALL DSP COR PSAa PSDa OUR NOR ONL UNI COM NAV\"");
    $max_play = Registry::get('config.max_play');
    $max_win  = Registry::get('config.max_win');
     $max_win_per_user = Registry::get('config.max_win_user');
   // $max_play  = 500;
     $fb_redirect = Registry::get('config.fb_quiz_redirect');
    //$fb_redirect = 'https://www.facebook.com/Shopclu/app_190322544333196';
    if(isset($_REQUEST['code']))
    {
        header("location:".$fb_redirect);
    }

    if($user_id)
    {
    $permissions = $facebook->api("/me/permissions");

         if((array_key_exists('publish_stream', $permissions['data'][0])))
         {
            try  
            {	
                $user_profile = $facebook->api('/me','GET');
                include_once('user_info.php');
            }
            catch(FacebookApiException $e) 
            {
                $login_url = $facebook->getLoginUrl(array('scope' => 'email,user_birthday,publish_stream')); 
            }   
        }
        else
        {
            $login_url = $facebook->getLoginUrl(array('scope' => 'publish_stream')); 
            echo "<script type='text/javascript'>top.location.href='".$login_url."';</script>";
        }
    }
    else 	
    { 
        $login_url = $facebook->getLoginUrl(array('scope' => 'email,user_birthday,publish_stream')); 
        echo "<script type='text/javascript'>top.location.href='".$login_url."';</script>";
        exit; 
    }	
?>
<?php
    include_once('query.php'); 
    $current_time = date('Y-m-d 00:00:00');
    if($last_attempted < $current_time)
    {
         $query_time_update = db_query("update clues_fb_quiz set attempts_used = 0,last_attempted='".$current_time."',last_win=0 where user_id=".$user_id);
    }
    if($last_win==1)
    {
        $fb_details = db_get_array("SELECT * FROM clues_fb_users where uid=".$user_id);
        $name = $fb_details[0][name];
        $email = $fb_details[0][email];
        $link  = $fb_details[0][link];
        include_once('win_form.php');
    }
    else
    {include_once('query.php');
        if($att <$max_play)
    {
$var1  =    rand(0,5);
$var2  =    rand(0,5);
$var3  =    rand(0,5);
//echo "Earlier value".$var1.$var2.$var3."next value";
if($limit1 > 0|| $limit2 >=$max_win || $last_win_count >0)
    
{
    $win_value = a;
    $win_msg = 'http://images.shopclues.com/images/banners/fb_contest/ops_nw.jpg';
    while(1)
    {
        if($var1 == $var2 && $var2 == $var3)
        {
            $var1  =    rand(0,5);
            $var2  =    rand(0,5);
            $var3  =    rand(0,5);
        }
        else
        break;
    }
}
else
{
    if($var1 == $var2 && $var2 == $var3)
    {
        $win_value = b;
        $win_msg = 'http://images.shopclues.com/images/banners/fb_contest/fb_jackpot_winner_2.jpg';
    }
    else
    {
        $win_value = a;
        $win_msg = 'http://images.shopclues.com/images/banners/fb_contest/ops_nw.jpg';
    }
}
$var12 = $var1;$var22 = $var2;$var23 =  $var3;
if($var12 == 0) $var12 = 9;
if($var22 == 0) $var22 = 9;
if($var23 == 0) $var23 = 9;   
db_query("update clues_fb_quiz set combination=".$var12.$var22.$var23." where user_id =".$user_id);
?><head> 
  <link type="text/css" rel="stylesheet" href="css/slot_machine.css?random=<?php echo time(); ?>" />

</head>
  <script src="scripts/jquery.min.js" ></script>
 <script type="text/javascript" src="js/slot.js?time?random=<?php echo time(); ?>"></script>
  <script type="text/javascript">$(function() { slot_play_game(); });</script>

<?php
   
    echo "</div>";
 }
 
 else
 {
     echo "<img src='https://images.shopclues.com/images/banners/fb_contest/fb_jackpot_greed.jpg' width='934px'>";
 }
 
    }
    
    function user_clicked_till_now()
{
   return db_get_field("SELECT sum(total_win+total_loss) as total_played FROM clues_fb_quiz");
}
 if($att <$max_play && $last_win==0)
    {
?>
<div class="fb_game_block">
<div id="viewport">
    <div id="container">
      <div id="reels">
	<canvas id="canvas1" width="70" height="300"></canvas>
	<canvas id="canvas2" width="70" height="300"></canvas>
	<canvas id="canvas3" width="70" height="300"></canvas>
	<div id="overlay">
	  <div id="winline"></div>
	</div>
	 
	  <div id="score">
	    
 <span id="msg_dis"></span>

<div  style="display:inline-block; color:#ff0000;margin-left: 50px;" id="errors"></div>
	  </div>
      
	  <div id="status"></div>
      </div>
      
    </div>
    <div id="results" style="display: block; position: absolute; z-index: 111; top:29px; left:-34px; display:none;"><?php echo "<img src
        =$win_msg width='567' height='475'>";?></div>
    <div id="buttons">
          <input type="hidden" id="hid1" value="<?php echo $var1; ?>" />
          <input type="hidden" id="hid2" value="<?php echo $var2; ?>" />
          <input type="hidden" id="hid3" value="<?php echo $var3; ?>" />
           <input type="hidden" id="w_v" value="<?php echo $win_value; ?>" />
           <div id ="play1">Click here to play <?php echo "<img src='http://cdn.shopclues.com/images/banners/fb_contest/arrow_slot.jpg'>"; ?></div>
           <div id ="play2" style="display:none;">Rolling Fingers Crossed</div>
      </div>
    <img id="play" alt="play" title="play" class="play_game" src=" http://images.shopclues.com/images/banners/fb_contest/animatoin_button_fb_game.gif" onclick="valid_entry('<?php echo $win_value; ?>')"/>
    
  </div>
  <?php
   $start_date =  date('Y-m-d 00:00:00');
    $end_date =  date('Y-m-d 23:59:59');
    $fb_detail = db_get_array("SELECT cfw.*,cfu.link FROM clues_fb_winner cfw inner join clues_fb_users cfu on cfu.uid =cfw.fb_id where cfw.time_created >'".$start_date."' and cfw.time_created <'".$end_date."' group by cfw.fb_id order by cfw.time_created desc limit 0,5");
	echo "<div class='winner_blk_heading'> ";
	$total_user_clicked  =  user_clicked_till_now();
    echo "<div class='winner_info'>Total no. of Clicks till today :".$total_user_clicked."</div>";
	echo "</div>";
    echo "<div class='winner_blk_cont'>";
    
    $count = 0;

    foreach($fb_detail as $details)
    {
        $count++;
        if($count > 0)
        {
        $t = strtotime($details['time_created']);
       $time = date('g:i a, M j, Y',$t);
      //  $time1 =  date_format($time,"F j, Y, g:i a");
        $profile_pic =  "http://graph.facebook.com/".$details['fb_id']."/picture";
        echo "<div class='post_list'>
            <div class='fb_pro_pic'><a href = \"".$details['link']."\" style='border:0;' target='blank'>
            <img class='profile_pic' src=\"" . $profile_pic . "\" /></a></div>"; 
        echo "<div class='info_blk'>";
        echo "<div class='name_field'>".$details['name']."</div>";
         echo "<div class='name_field_time'>".$time."</div>";     
       //  echo "<div class='name_field_winner'>".$count."</div>";     
        echo "</div></div>";
        }
    }   
	echo "</div>"; 
    }
	
  ?>
  
<script type="text/javascript">
    
    function valid_entry(win)
    {
        var data_check = {email_check:win};
        var randomNum = Math.ceil(Math.random() * 999999);
        var uri = "ajax_req.php?time="+randomNum;
        $.ajax({type: "post", url : uri, data: data_check, success: function(result)	
            {
                
            }
        });
    }
    
    </script>