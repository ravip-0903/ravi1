<?php
if ( !defined('AREA') )	{ die('Access denied');	}

class LogError extends LogCommon{
    public static function init(){
        parent::log_file('app_error.log');
    }
    
    public static function dump_log($key, $value, $others = NULL) {
        $always = LogConstants::LOG_BACKTRACE | LogConstants::LOG_REQUEST_URL;
        parent::dump_log($key, $value, $always | $others);
    }
}

LogError::init();