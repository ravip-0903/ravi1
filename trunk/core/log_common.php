<?php
if ( !defined('AREA') )	{ die('Access denied');	}

abstract class LogCommon{
    protected static $log_file;
    protected static function log_file($filename = ''){
        $class = get_called_class();
        if($filename == ''){
            if(isset(self::$log_file[$class]) && self::$log_file[$class] != ''){
                return self::$log_file[$class];
            }else{
                return '';
            }
        }else{
            if(!isset(self::$log_file[$class]) || self::$log_file[$class] == ''){
                self::$log_file[$class] = $filename;
            }
        }
    }
    
    protected static function dump_log($key, $value, $others = NULL){
        $always = '';
        
        LogHelper::dump_log(self::log_file(), $key, $value, $always | $others); 
    }

}