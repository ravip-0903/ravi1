<?php
class ErrorHandler {
    public static function dump_error($str){
    	error_log("ErrorHandler: " . $str);
    }

    public static function fn_get_current_request_url(){
    	   $prot = $_SERVER['SERVER_PORT'] == "80" ? "http://" : "https://";
	   return $prot . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }
    
    public static function fn_is_qs_strippable($path){
        $restricted_paths = array('/payments/amex/PHP_VPC_3Party_Auth_Capture_Order_DR.php',
                '/tools/fb_apps/after_login.php',
                '/tools/fb_apps/google_login/home.php',
            );
        return !in_array($path, $restricted_paths);
    }

    public static function fn_get_url_for_external_logging($url){
        $parse = parse_url(urldecode($url));
	if(!isset($parse['query'])){
	    $parse['query'] = '';
	}

        if(!self::fn_is_qs_strippable($parse['path'])){
            unset($parse['query']);
            return http_build_query($parse);
        }

        parse_str($parse['query'], $qs);

        $restricted_qs_variables = array('email_id', 'sess_id','ekey', 'code', 'order_id', 'coupon_code', 'cart_id', 'state', );

        foreach($restricted_qs_variables as $val){
            if(isset($qs[$val])){
                $qs[$val] = "stripped";
            }
        }

        $parse['query'] = http_build_query($qs);

        $return = $parse['scheme'] . '://' . $parse['host'] . $parse['path'] . '?' . $parse['query'];

        return $return;
    }
}