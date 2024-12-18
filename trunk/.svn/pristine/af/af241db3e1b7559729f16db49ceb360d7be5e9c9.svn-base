<?php
if ( !defined('AREA') )	{ die('Access denied');	}

class LogMetric extends LogCommon{
    public static function init(){
        parent::log_file('app_metric.log');
    }
    
    public static function dump_log($key, $value, $others = NULL) {
        parent::dump_log($key, $value, $others);
    }
}

LogMetric::init();