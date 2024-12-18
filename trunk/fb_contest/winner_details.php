<?php
     define('AREA', 'C');
    define('AREA_NAME', 'customer');
    require  dirname(__FILE__) . '/../prepare.php';
    require  dirname(__FILE__) . '/../init.php';
    $one_day_less = 24*60*60;
    $start_date =  time() - $one_day_less;
    $end_date =  time() - $one_day_less; 
    $start_date  = date('Y-m-d 00:00:00',$start_date);
    $end_date   = date('Y-m-d 23:59:59',$end_date);
        //echo $start_date.$end_date;
    
    //SELECT * FROM `clues_photo_contest` where (date_format(time_created,'%Y-%m-%d')) = (CURDATE() - INTERVAL 1 DAY)
    
   $query = db_get_array("SELECT * FROM clues_fb_winner  where time_created >'".$start_date."' and time_created <'".$end_date."' group by fb_id order by time_created desc");
    
    $winner_body = "<ol>";
    foreach($query as $query1)
    {
        
        $address = $query1['address'];
        $address  = unserialize($address);
        $address  = $address[0]." ".$address[1]." ".$address[2]." ".$address[3]." ".$address[4];
        $winner_body = $winner_body.'<li>'."Winner's name : ".$query1['name']." , Mobile no:".$query1['mobile_no'].", ShopClues email ID:".$query1['shopclue_email_id']." , Address : ".$address.'</li>';
    }
        $winner_body =$winner_body.'</ol>';
        $today = date('j M, Y');
          Registry::get('view_mail')->assign('winner_mail_body', $winner_body);
          Registry::get('view_mail')->assign('today_date', $today);
               $to = Registry::get('config.error_test');
             //   $to = 
             $from =  Registry::get('config.winner_mail_to');              
                fn_instant_mail($to, $from,'fb_contest/winner_subj.tpl','fb_contest/winner_body.tpl');
?>