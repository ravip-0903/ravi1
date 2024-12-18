<?php
/***************************************************************************
*                                                                          *                                                                         *
* Scrapbook controller                                                 *
*                                                                          *
****************************************************************************/


if ( !defined('AREA') ) { die('Access denied'); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {           
             if ($mode == 'upload_image') {
        $filename = 'image'.rand().time().'a.png';
        $img = str_replace('data:image/png;base64,', '', $_REQUEST['image']);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $file = 'images/scrapbook/'.$filename;
        $success = file_put_contents($file, $data);
        file_put_contents($file, $data);
       
        //Sync uploaded attachment to image server  


        
        $local = Registry::get('config.loc_img'). "scrapbook/" . $filename;
        $remote =  Registry::get('config.remote_img');
        $parameter = Registry::get('config.rsync_parameter');
        $rsyn = exec("rsync $parameter $local $remote &");
        
        
        if($_SESSION['auth']['user_id'])
        {
            $user = $_SESSION['auth']['user_id'];
        }
         else {
                $user = 0;
        }

        $query = "INSERT IGNORE INTO clues_scrapbook_images (`user_id`, `image_name`) VALUES (".$user.",'".$filename."' )";
        db_query($query);

        //return array(CONTROLLER_STATUS_OK, "scrapbook.show");
        exit;
}
elseif ($mode == 'share_image') {
        $filename = 'image'.rand().time().'a.png';
        $img = str_replace('data:image/png;base64,', '', $_REQUEST['image']);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $file = 'images/scrapbook/'.$filename;
        $success = file_put_contents($file, $data);
        file_put_contents($file, $data);

        //Sync uploaded attachment to image server
        $local = Registry::get('config.loc_img'). "scrapbook/" . $filename;
        $remote =  Registry::get('config.remote_img');
        $parameter = Registry::get('config.rsync_parameter');
        $rsyn = exec("rsync $parameter $local $remote &");

        echo $filename;

         exit;
}
}


if ($mode == 'view') 
{

    $query="SELECT  id, category_id, category, icon_path, images_path FROM clues_scrapbook_categories WHERE status='A'";
    
    
    $result = db_get_array($query);
    
    $view->assign('cat_data',$result);
    
   $cat_images = array();
   
    foreach ($result as $value) {
        
        $cat_images[$value['category_id']] = explode(',',$value['images_path']);
        
    }

    $view->assign('cat_image_data',  $cat_images);
    
}
elseif($mode == 'show') 
{
    $length = Registry::get("config.no_of_scrapbook_to_show");
    $page = (isset($_REQUEST['page'])?$_REQUEST['page']:1);
    if($page == 1)
    {
        $start = 0;
    }
    else
    {
        $start = $length *  ($page - 1);
    }
    
    $limit = $start." , ".$length;
    $query = "SELECT image_name,fb_like,fb_share FROM clues_scrapbook_images Where status='A' order by image_id DESC limit ".$limit;
    $result = db_get_array($query);
    
    if($_REQUEST['scrapbook_ajax'])
    {
        echo json_encode($result);
        exit;
    }
    else 
    {
        $view->assign('scrapbook_data',  $result);
    }
}


?>
