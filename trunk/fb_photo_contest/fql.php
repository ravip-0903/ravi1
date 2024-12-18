<?php
include('conn.php');
 $result = db_get_array("select * from clues_photo_contest");
  $server_loc = Registry::get('config.fb_front_like_url');
 foreach($result as $key=>$k)
 {
      $path = $result[$key]['pic_path'];
        $profile_pic =  "../images/fb_photo_contest/".$path;
       //$absolute_path = "http://staging1.shopclues.com/images/fb_photo_contest/".$path;
        $absolute_path = $server_loc."/images/fb_photo_contest/".$path;
     //  echo "<pre>";print_r($absolute_path);
     $fql = "select comment_count, share_count, like_count from link_stat where url ='".$absolute_path."'";
    $param  =   array(
    'method'    => 'fql.query',
    'access_token' => $cookie['access_token'],
    'query'     => $fql,
    'callback'  => ''
);
$response   =   $facebook->api($param);

$likes = $response[0]['like_count'];
$update_likes = db_query("update clues_photo_contest set like_click=".$likes." where pic_path='".$path."'" );
 }

?>