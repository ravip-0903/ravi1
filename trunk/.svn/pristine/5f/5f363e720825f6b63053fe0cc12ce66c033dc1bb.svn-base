<!-- Used to fetch all user data -->
<?php

    $user_profile = $facebook->api('/me','GET');
    $fbid = $user_profile['id'];
    $link = $user_profile['link'];
    $name = $user_profile['name'];
    $gender = $user_profile['gender'];
    $fbemail = $user_profile['email'];
    $birthday = $user_profile['birthday'];
    $updated_time = $user_profile['updated_time'];
    $first_name = $user_profile['first_name'];
    $last_name = $user_profile['last_name'];
    $result = db_get_field("SELECT COUNT(*) uid FROM clues_fb_users  where uid =".$fbid);
    if($result == 0)
    {
            $facebook = db_query("insert into clues_fb_users (uid,name,gender,link,email,first_name,last_name,updated_time,birthday) values('".$fbid."',
                            '".$name."','".$gender."','".$link."','".$fbemail."','".$first_name."','".$last_name."','".$updated_time."','".$birthday."')");
    }
    
    $result_fb_quiz = db_get_field("SELECT COUNT(*) FROM clues_fb_quiz  where user_id =".$fbid);
    
    if($result_fb_quiz == 0)
    {
            $quiz = db_query("insert into clues_fb_quiz (user_id) values('".$fbid."')");
    }
    ?>