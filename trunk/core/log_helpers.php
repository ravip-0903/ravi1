<?php
if ( !defined('AREA') )	{ die('Access denied');	}

class LogHelper {
    private static $log_folder = 'images/logs';
    private static $separator = ", ";
    private static $timezone = "Asia/Calcutta";
    private static $data = array();
    private static $logging_status = '';
    private static $logging_storage = '';
    
    public static function log_enabled(){
        if(!self::$logging_status){
            self::init_log_storage();
        }
        
        if(self::$logging_status == 'enabled'){
            return true;
        }
        return false;
    }
    
    private static function init_log_storage(){
        if(!self::$logging_storage){
            self::$logging_status = 'disabled';
            if(!in_array(substr(LogHelper::get_request_url(), -4), array('.jpg', '.png', '.gif'))){
                if(class_exists('Registry')){
                    $logging_storage = Registry::get('config.shopclues_logging.storage');
                }
                else{
                    global $config;
                    if($config['shopclues_logging']['storage']){
                        $logging_storage = $config['shopclues_logging']['storage'];
                    }
                }

                if(isset($logging_storage) && count($logging_storage) >= 1){
                    self::$logging_storage = $logging_storage;
                    foreach($logging_storage as $v){
                        if(method_exists(get_called_class(), 'dump_log_to_'. $v)){
                            self::$logging_status = 'enabled';
                            break;
                        }
                    }
                }
            }
        }        
    }
    
    public static function dump_log($logfile, $key, $value, $options = NULL){
        
        if(!self::log_enabled()){
            return false;
        }
        
        $first_and_always = LogConstants::LOG_ID | LogConstants::LOG_REQUEST_DATE_TIME;
        
        $metrics = self::fn_get_metrics($first_and_always);
        
        if(is_array($key) && is_array($value)){
            for($i=0; $i<count($key); $i++){
                $metrics[$key[$i]] = $value[$i];
            }
        }
        else{
            $metrics[$key] = $value;
        }
        
        $metrics = array_merge($metrics, self::fn_get_metrics($options & ~($options & $first_and_always)));

        $metrics = self::fn_filter_metrics($metrics);
        $metrics = self::fn_format_metrics($metrics);

        $logging_storage = self::$logging_storage;
        $call['class'] = get_called_class();
        foreach($logging_storage as $v){
            $call['func'] = 'dump_log_to_'. $v;
            if(method_exists($call['class'], $call['func'])){
                call_user_func_array(array_values($call), array($metrics, $logfile));
            }
        }
    }
    
    public static function fn_format_metrics($metrics){
        $format_nltobr = array('title', 'message', 'extra');
        foreach($format_nltobr as $i){
            if(isset($metrics[$i])){
		$metrics[$i] = htmlentities(str_replace(array("\r\n", "\r", "\n"), "<br />", $metrics[$i]), ENT_QUOTES);
            }
        }
        
        $format_urlencode = array('request_url', 'referer_url');
        foreach($format_urlencode as $k){
            if(isset($metrics[$k])){
		$metrics[$k] = urlencode($metrics[$k]);
            }
        }
        return $metrics;
    }
    
    public static function fn_filter_metrics($metrics){
        $email_regular_expression = "/([\d\w-+=_][.\d\w-+=_]*)?[-\d\w]@([-!#\$%&*+\\/=?\w\d^_`{|}~]+\.)+[a-zA-Z]{2,6}/";
        $metrics_str = json_encode($metrics);
        
        if(preg_match($email_regular_expression, $metrics_str)){
            foreach($metrics as $k => &$v){
                if(is_array($v)){
                    $callers=debug_backtrace();
                    if(isset($callers[1]['function']) && $callers[1]['function'] != __FUNCTION__){
                        $v = self::fn_filter_metrics($v);
                    }
                    else{
                        $v = preg_replace($email_regular_expression, "email_id_stripped", json_encode($v));
                    }
                }
                else{
                    $v = preg_replace($email_regular_expression, "email_id_stripped", $v);
                }
            }
        }
        
        return $metrics;
    }
    
    private static function dump_log_to_file($metrics, $logfile){
        $str = trim(self::fn_array_to_keyvalue($metrics, self::$separator), self::$separator) . "\n";
        
        $log_file_path = __DIR__ . '/../' . self::$log_folder . '/' . $logfile;
    	file_put_contents($log_file_path, $str, FILE_APPEND);
    }
    

    private static function dump_log_to_mongo($metrics, $logfile){
        $collection = str_replace(".log", '', $logfile);
        $c_mongo = self::fn_get_mongo_connection($collection);
        if($c_mongo && count($metrics) >= 1){
            unset($metrics['']);
            if(isset($metrics['request_date_time'])){
                $metrics['request_date_time'] = new MongoDate(strtotime($metrics['request_date_time']));
            }
            return $c_mongo->save($metrics);
        }
    }
    
    public static function fn_array_to_keyvalue($array, $sep){
        $str = '';
        foreach($array as $k => $v){
            if($k || $v){
                if(is_array($v)){
                    $v = implode('|', $v);
                }
                $str .= $k . '=' . $v . $sep;
            }
        }
        return $str;
    }
    
    public static function fn_get_metrics($options){
        $metrics = array();
        $functions = LogConstants::map_constants_to_function_names($options);
        foreach($functions as $fn){
            $fkey = str_replace("get_", "", $fn);
            $fval = self::$fn();
            $metrics[$fkey] = $fval;
        }
        return $metrics;
    }

    private static function fn_get_mongo_connection($collection){
        static $c_mongo = array();
        if(!$c_mongo[$collection]){
            $mongoConfig = self::fn_get_mongo_config();
            if($mongoConfig){
                try{
                    $mongo = new Mongo("mongodb://{$mongoConfig['host']}");
                    $mongodb = $mongo->$mongoConfig['dbname'];
                    if($mongodb){
                        $c_mongo[$collection] = $mongodb->$mongoConfig['collections'][$collection];
                        if(!$c_mongo[$collection]){
                            error_log('Collection "' . $mongoConfig['collection'][$collection] . '" not found in current mongo db');
                        }
                    }
                    else{
                        error_log('Mongo db connection error - ' . implode(":", array_values($mongoConfig)));
                    }
                }
                catch(Exception $e){
                    error_log($e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine(). ' ' . $e->getCode());
                }
            }
            else{
                error_log('Mongo connection configurations not defined');
            }
        }

        return $c_mongo[$collection];
    }

    private static function fn_get_mongo_config(){
        if(class_exists('Registry')){
            return Registry::get('config.shopclues_logging.mongo');
        }
        else{
            global $config;
            if($config['shopclues_logging']['mongo']){
                return $config['shopclues_logging']['mongo'];
            }
        }

        return false;
    }
    
    public static function get_request_url(){
    	   $url = ($_SERVER['SERVER_PORT'] == "80" ? "http://" : "https://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	   return $url;
    }
    
    public static function get_referer_url(){
	   return $_SERVER['HTTP_REFERER'];
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
    
    public static function get_processid(){
        return getmypid();
    }
    
    public static function get_mem_used(){
        return memory_get_usage(true);
    }
    
    public static function get_mem_peak_used(){
        return memory_get_peak_usage();
    }
    
    public static function get_backtrace(){
        global $bt;
        if(!isset($bt) || $bt == ''){
            $bt = debug_backtrace();
        }
        //array_shift($bt); // remove actual bt() call

        $i = 0;
        $bt_dump = "\n";
        foreach ($bt as $call) {
            if(isset($call["file"]) && $call["line"]){
                $file = basename($call["file"]) . " (" . $call["line"] . ")";
            } else {
                $file = "";
            }

            if ( isset($call["class"]) ) {
                $func = $call["class"] . "->" . $call["function"] . "()";
            } else {
                $func = $call["function"] . "()";
            }

            $bt_dump .= "#" . str_pad($i, 2, " ", STR_PAD_RIGHT) . ": " . str_pad($file.":", 42) . " $func\n";
            $i++;
        }

        return $bt_dump;
    }
    
    public static function get_session(){
        return json_encode($_SESSION);
    }
    
    public static function get_cookie(){
        return json_encode($_COOKIE);
    }
    
    public static function get_sess_id(){
        return session_id();
    }
    
    public static function get_identified_user(){
        if(Registry::get('user_info')){
            $user_info = Registry::get('user_info');
            return $user_info['user_id'];
        }
        else if($_SESSION['auth']['user_id']){
            return $_SESSION['auth']['user_id'];
        }
        else if($_COOKIE['sess_id']){
            $user_id = db_get_field("SELECT user_id FROM ?:user_session_products WHERE session_id = ?s", $_COOKIE['sess_id']);
            if($user_id){
                return $user_id;
            }
        }
        
        return false;
    }
    
    public static function get_server_name(){
        return gethostname();
    }
    
    public static function get_id(){
        return self::$data['LOG_ID'];
    }
    
    public static function fn_generate_id(){
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $uuid = md5(uniqid(rand(), true));
        self::$data['LOG_ID'] = $uuid;
        return;
        
        $id_length = 6;
        $character_set_array = str_split(str_replace(array('.', ' '), '', '0123456789abcdefg' . microtime() . 'hijklmnop'));
        shuffle($character_set_array);
        $character_set = implode('', $character_set_array);
        $character_count = strlen($character_set);
        $temp_array = array();
        for ($i = 0; $i < $id_length; $i++) {
            $temp_array[] = $character_set[rand(0, $character_count  - 1)];
        }
        shuffle($temp_array);
        self::$data['LOG_ID'] = implode('', $temp_array);
    }
    
    public static function fn_start_timer(){
        if(!isset(self::$data['start_time'])){
            self::$data['start_time'] = microtime(true);
        }
    }
    
    public static function fn_get_execution_time(){
        if(isset(self::$data['start_time'])){
            return round(microtime(true) - self::$data['start_time'],4);
        }
        return -1;
    }

    public static function fn_init_log(){
        self::fn_start_timer();
        
        static $log_initialized = false;
        if($log_initialized == false){
            self::fn_generate_id();
            $log_initialized = true;
        }
        else{
            LogMetric::dump_log("fn_init_log_multiple_calls", true);
        }
    }
    
    public static function get_request_date_time(){
        $date = new DateTime('', new DateTimeZone(self::$timezone));
        return $date->format('D M d H:i:s Y O');
    }
    
    public static function get_client_ip(){
	$forwarded_ip = '';
	$fields = array(
		'HTTP_X_FORWARDED_FOR',
		'HTTP_X_FORWARDED',
		'HTTP_FORWARDED_FOR',
		'HTTP_FORWARDED',
		'HTTP_forwarded_ip',
		'HTTP_X_COMING_FROM',
		'HTTP_COMING_FROM',
		'HTTP_CLIENT_IP',
		'HTTP_VIA',
		'HTTP_XROXY_CONNECTION',
		'HTTP_PROXY_CONNECTION');

	$matches = array();
	foreach ($fields as $f) {
		if (!empty($_SERVER[$f])) {
			preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/", $_SERVER[$f], $matches);
			if (!empty($matches) && !empty($matches[0]) && $matches[0] != $_SERVER['REMOTE_ADDR']) {
				$forwarded_ip = $matches[0];
                                $forwarded_header = $f;
				break;
			}
		}
	}        
        if($forwarded_ip != ''){
            if($forwarded_ip != $_SERVER['REMOTE_ADDR']){
                return $_SERVER['REMOTE_ADDR'] . ' (' . $forwarded_header . ': ' . $forwarded_ip . ')';
            }
        }
        return $_SERVER['REMOTE_ADDR'];
    }

    public static function get_get_params(){
        return json_encode($_GET);
    }
    
    public static function get_post_params(){
        return json_encode($_POST);
    }
    
    public static function get_response_code(){
        
    }
    
    public static function fn_get_friendly_error_type($type) 
    { 
        switch($type) 
        { 
            case E_ERROR: // 1 // 
                return 'E_ERROR'; 
            case E_WARNING: // 2 // 
                return 'E_WARNING'; 
            case E_PARSE: // 4 // 
                return 'E_PARSE'; 
            case E_NOTICE: // 8 // 
                return 'E_NOTICE'; 
            case E_CORE_ERROR: // 16 // 
                return 'E_CORE_ERROR'; 
            case E_CORE_WARNING: // 32 // 
                return 'E_CORE_WARNING'; 
            case E_CORE_ERROR: // 64 // 
                return 'E_COMPILE_ERROR'; 
            case E_CORE_WARNING: // 128 // 
                return 'E_COMPILE_WARNING'; 
            case E_USER_ERROR: // 256 // 
                return 'E_USER_ERROR'; 
            case E_USER_WARNING: // 512 // 
                return 'E_USER_WARNING'; 
            case E_USER_NOTICE: // 1024 // 
                return 'E_USER_NOTICE'; 
            case E_STRICT: // 2048 // 
                return 'E_STRICT'; 
            case E_RECOVERABLE_ERROR: // 4096 // 
                return 'E_RECOVERABLE_ERROR'; 
            case E_DEPRECATED: // 8192 // 
                return 'E_DEPRECATED'; 
            case E_USER_DEPRECATED: // 16384 // 
                return 'E_USER_DEPRECATED'; 
        } 
        return ""; 
    }
    
    public static function fn_get_friendly_page_name(){
        if (!defined('CONTROLLER') || !defined('MODE')){
            return '';
        }
        
        $controller_map = array(
            'checkout.cart' => 'cart.view',
            'checkout.add' => 'cart.add.product',
            'checkout.delete' => 'cart.delete.product',
            'checkout.checkout' => 'checkout',
            'checkout.order_info' => 'checkout.order_info',
            'checkout.apply_coupon' => 'checkout.apply_coupon',
            'checkout.place_order'=> 'checkout.place_order',
            'categories.view' => 'category.view',
            'products.view' => 'products.view',
            'auth.login_form' => 'login',
            'auth.login' => 'login',
            'products.search' => 'search',
            'statistics.collect' => 'statistics.collect',
            
        );
        $name = '';
        
        if(!isset($controller_map[CONTROLLER . '.' . MODE])){
            if(isset($_GET['dispatch']) && strlen($_GET['dispatch'])>=1){
                $name = $_GET['dispatch'];
            }
        }
        else{
            $name = $controller_map[CONTROLLER . '.' . MODE];
            if($name == 'checkout' && isset($_REQUEST['edit_step'])){
                $name = $name . '.' . $_REQUEST['edit_step'];
            }
            else if($name == 'login' && $_POST['form_name'] == 'step_one_login_form'){
                $name = $name . '.' . $_POST['form_name'];
            }
        }
        
        return $name;
        
    }
    
    
}