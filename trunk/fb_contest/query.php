<?php
$fb_details = db_get_array("SELECT * FROM clues_fb_users where uid=".$user_id);
        $name = $fb_details[0][name];
        $email = $fb_details[0][email];
        $link  = $fb_details[0][link];
        $val = db_get_array("select * from clues_fb_quiz where user_id=".$user_id);
        $start_date =  date('Y-m-d 00:00:00');
    $end_date =  date('Y-m-d 23:59:59');
    $att = $val[0][attempts_used];
    $user_id = $val[0][user_id];
    $last_win = $val[0][last_win];
    $last_attempted = $val[0][last_attempted];
     $last_win_count = db_get_field("select count(last_attempted) as last from clues_fb_quiz where last_win=1 and  last_attempted >'".$start_date."' and last_attempted<'".$end_date."'");
    $limit1 = user_total_win_per_day($start_date,$end_date,$user_id);
    $limit2 = total_win_per_day($start_date,$end_date);
  function user_total_win_per_day($start_date,$end_date,$user_id)
{
    return db_get_field("SELECT count(cf.fb_id) as win_counts FROM clues_fb_winner cf where cf.time_created >'".$start_date."' and cf.time_created <'".$end_date."' and fb_id=".$user_id);
}
function total_win_per_day($start_date,$end_date)
{
    return db_get_field("SELECT count(cf.fb_id) as win_counts FROM clues_fb_winner cf where cf.time_created >'".$start_date."' and cf.time_created <'".$end_date."'");
}
?>