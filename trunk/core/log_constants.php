<?php
if ( !defined('AREA') )	{ die('Access denied');	}
class LogConstants{
    const LOG_REQUEST_DATE_TIME = 1;
    const LOG_ID = 2;
    const LOG_CLIENT_IP = 4;
    const LOG_SESS_ID = 8;
    const LOG_IDENTIFIED_USER = 16;
    const LOG_SERVER_NAME = 32;
    const LOG_REQUEST_URL = 64;
    const LOG_REFERER_URL = 128;
    const LOG_GET_PARAMS = 256;
    const LOG_POST_PARAMS = 512;
    const LOG_RESPONSE_CODE = 1024;
    const LOG_PROCESSID = 2048;
    const LOG_SESSION = 4096;
    const LOG_COOKIE = 8192;
    const LOG_MEM_USED = 16384;
    const LOG_MEM_PEAK_USED = 32768;
    const LOG_BACKTRACE = 65536;
    
    public static function map_constants_to_function_names($binary_OR_constants){
        $rc = new ReflectionClass('LogConstants');
        $constants = $rc->getConstants();
        $function_names = array();
        foreach($constants as $const => $value){
            $a = $binary_OR_constants & $value;
            if($binary_OR_constants & $value){
                $function_names[] = str_replace("log", "get", strtolower($const));
            }
        }
        return $function_names;
    }
}

?>
