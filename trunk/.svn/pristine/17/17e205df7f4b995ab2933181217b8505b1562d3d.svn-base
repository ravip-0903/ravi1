<?php
/*
 * @author:- Raj Chaudhary
 * @description:- Controller for posting product reviews
 * @created date:- 07/09/2013
*/

if ( !defined('AREA') ) { die('Access denied'); }

// Rendering review view here

if ($mode == 'review') {
    //$view->assign('object_type', $data['object_type']);
}

function fn_discussion_get_object_by_thread($thread_id)
{
    static $cache = array();

    if (empty($cache[$thread_id])) {
        $cache[$thread_id] = db_get_row("SELECT object_type, object_id, type FROM ?:discussion WHERE thread_id = ?i", $thread_id);
    }

    return $cache[$thread_id];
}

// Handling get request here
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if ($mode == 'review') {
        //check for login
        if(empty($auth['user_id'])){
            $login_url = "index.php?dispatch=auth.login_form&return_url=";
            return array(CONTROLLER_STATUS_REDIRECT, $login_url.urlencode(Registry::get('config.current_url')));
        }
        //print_r($_REQUEST['product_id']);die;
        $product_id = $_REQUEST['product_id'];
        //$product_data = fn_get_product_data($product_id, $auth, CART_LANGUAGE, '', false, true, false, false);
        $list_price = fn_get_product_list_price($product_id);
        $deal_price = fn_get_product_deal_price($product_id);
        $product_name = fn_get_product_name($product_id);
        $selling_price = fn_get_product_selling_price($product_id);

        // Get total number of reviews
        $sql_query = "SELECT * FROM `cscart_discussion_posts` as cdp inner join `cscart_discussion` as cd on cdp.thread_id = cd.thread_id where cd.object_id ='".$product_id."'  and cdp.status = 'A' and cd.object_type='P'";
        $reviews = count(db_get_array($sql_query));

        // Assign values to view
        $view->assign('object_id', $product_id);
        $view->assign('list_price', (float)$list_price);
        $view->assign('selling_price', (float)$selling_price);
        $view->assign('deal_price', (float)$deal_price);
        $view->assign('product_name', $product_name);
        $view->assign('object_type', "P");
        $view->assign('reviews', $reviews);
    }
}

// Handling post request here

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sta_change=0;

    $discussion_settings = Registry::get('addons.discussion');
    $discussion_object_types = fn_get_discussion_objects();

    Registry::set('discussion_settings', $discussion_settings);

    $suffix = '';

    if ($mode == 'review') {

        $post_data = $_REQUEST;

        if(Registry::get('config.review_character_restriction')){

            if(strlen($post_data['message']) < Registry::get('config.reviews_min_character') ||  strlen($post_data['message']) > Registry::get('config.reviews_max_character')) {

                echo 'character_error';
                exit;
            }
        }

        if(strlen($post_data['name']) < Registry::get('config.reviews_title_min_character') ||  strlen($post_data['name']) > Registry::get('config.reviews_title_max_character')) {

            echo 'title_error';
            exit;
        }

        if(strlen($post_data['rating_value']) == 0){
            echo 'rating_error';
            exit;
        }

        $peices = explode("\n", $post_data['message']);
        $consecutive = array();
        for($i=0; $i<count($peices); $i++){
            if(count($consecutive) == 3){
                $post_data['status'] = 'D';
                $sta_change=1;
                break;
            }
            if(!preg_match('/\s/',trim($peices[$i]))){
                array_push($consecutive,1);
            }
            else{
                if(count($consecutive) > 0){
                    array_pop($consecutive);
                }
            }
        }

        // Do this for three strings and exit.

        if (!empty($post_data['thread_id'])) {
            $object = fn_discussion_get_object_by_thread($post_data['thread_id']);
            if (empty($object)) {
                fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('cant_find_thread'));
                return array(CONTROLLER_STATUS_REDIRECT, $_REQUEST['redirect_url']);
            }
            $object_name = $discussion_object_types[$object['object_type']];
            $object_data = fn_get_discussion_object_data($object['object_id'], $object['object_type']);
            $ip = fn_get_ip();
            $post_data['ip_address'] = $ip['host'];

            // checking for 3 comments per product for a given user.
            if(Registry::get('config.limitPerProduct_restriction')){
                $sql_query = "SELECT cd.thread_id FROM `cscart_discussion` as cd inner join `cscart_discussion_posts` as cdp on cdp.thread_id = cd.thread_id where cd.object_id = '".$object['object_id']."' and cdp.user_id = '" .$auth['user_id']."' and cdp.user_id <> 0";
                //print_r();die;
                if(count(db_get_array($sql_query))>= Registry::get('config.min_user_limit_per_product')){
                    echo 'limitPerProduct_exceed_error';
                    exit;
                }
            }

            // checking for 5 comments per day for a given user.
            if(Registry::get('config.limitPerDay_restriction')){
                $sql_query2 = "SELECT post_id FROM `cscart_discussion_posts` where user_id = '" .$auth['user_id']."' and timestamp > '" .strtotime(date('Y-m-d',strtotime('now'))). "'";
                //print_r();die;
                if(count(db_get_array($sql_query2))>= Registry::get('config.min_user_limit_per_day')){
                    echo 'limitPerDay_exceed_error';
                    exit;
                }
            }

            $review = $post_data['message'];
            $name=$post_data['name'];
            //$sta_change=0;
            $url_pattern = '{\b(?:http://)?(www\.)?([^\s]+)('.Registry::get('config.review_url_pattern').')\b}';
            $aPattern = '/^([^\s])\n$/';
            //preg_match_all($url_pattern,$review,$matches);
            if(preg_match($url_pattern,$review))
            {
                $sta_change=1;
                $post_data['status'] = 'D';
            }

            $ip_add=$_SERVER['REMOTE_ADDR'];
            $sql="select id from clues_restricted_phrase_ip where restrict_ip='".$ip_add."' and type='R'";

            $rest_check=db_get_field($sql);
            if(!empty($rest_check))
            {
                //fn_set_notification('N', '', fn_get_lang_var('ip_blocked'));
                $to=Registry::get('config.error_to_email_ids');
                $from="support@shopclues.com";
                $sub=fn_get_lang_var('review_post_error');
                $msg="<h1>Review Post Error Due To IP BLOCKED</h1>";
                $msg.="<br/><br/>Posted IP-". $ip_add;
                sendElasticEmail($to, $sub, " ", $msg, $from, fn_get_lang_var('review_mail_header'), '');
                $post_data['status'] = 'D';
                $sta_change=1;
            }
            $sql="select restrict_phrase from clues_restricted_phrase_ip where type='R'";
            $result=db_get_array($sql);

            $rest_ph_match=array();
            $name_rest_ph_match=array();
            foreach($result as $phrase)
            {
                if(stripos($review,$phrase['restrict_phrase'])!==false)
                {
                    $rest_ph_match[]=$phrase['restrict_phrase'];
                }
                if(stripos($name,$phrase['restrict_phrase'])!==false)
                {
                    $name_rest_ph_match[]=$phrase['restrict_phrase'];
                }
            }
            if(!empty($rest_ph_match) || !empty($name_rest_ph_match))
            {
                $to=Registry::get('config.error_to_email_ids');
                $from="support@shopclues.com";
                $sub=fn_get_lang_var('review_post_error');
                $msg="<h1>".fn_get_lang_var('phrase_restricted')."</h1>";
                $msg.="Name-".$post_data['name'];
                $msg.="<br/><br/>Posted Review-". $review;
                $msg.="<br/><br/>Restrcited Phrase Found-".implode(",",$rest_ph_match)." ".implode(",",$name_rest_ph_match);
                sendElasticEmail($to, $sub, " ", $msg, $from, fn_get_lang_var('review_mail_header'), '');
                $post_data['status'] = 'D';
                $sta_change=1;
            }
            if($sta_change==0)
            {
                $post_data['status'] = 'A';
            }
            //code end


            $_data = fn_check_table_fields($post_data, 'discussion_posts');
            $_data['timestamp'] = TIME;
            $_data['user_id'] = $auth['user_id'];

            $post_data['post_id'] = db_query("INSERT INTO ?:discussion_posts ?e", $_data);

            $_data = fn_check_table_fields($post_data, 'discussion_messages');
            $message = db_query("REPLACE INTO ?:discussion_messages ?e", $_data);

            $_data = fn_check_table_fields($post_data, 'discussion_rating');
            $rating = db_query("REPLACE INTO ?:discussion_rating ?e", $_data);

            if($post_data['post_id'] && $message && $rating){
                echo 'done';
                //echo $post_data['status'];
            }else{
                echo 'failed';
            }
            exit;
        }
        return array(CONTROLLER_STATUS_OK, $_REQUEST['redirect_url']);
    }
}

?>