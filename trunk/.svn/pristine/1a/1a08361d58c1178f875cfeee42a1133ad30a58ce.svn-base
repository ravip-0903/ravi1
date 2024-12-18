<?php
/***************************************************************************
*                                                                          *
*    Copyright (c) 2004 Simbirsk Technologies Ltd. All rights reserved.    *
*                                                                          *
* This  is  commercial  software,  only  users  who have purchased a valid *
* license  and  accept  to the terms of the  License Agreement can install *
* and use this program.                                                    *
*                                                                          *
****************************************************************************
* PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
* "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
****************************************************************************/


//
// $Id: prepare.php 11257 2010-11-18 15:07:51Z 2tl $
//
include_once __DIR__ . '/core/log_init.php';
function fn_catch_errors($errno, $errstr, $errfile, $errline, $errcontext){
    $ignore_error_types = E_NOTICE | E_WARNING | E_STRICT | E_DEPRECATED;
    if($errno & $ignore_error_types){
        return;
    }
    global $bt;
    $bt = debug_backtrace();

    $e_type = LogHelper::fn_get_friendly_error_type($errno);
    $e_msg = $errstr . " in " . $errfile . ' line ' . $errline;
    LogError::dump_log($e_type, $e_msg, LogConstants::LOG_PROCESSID | LogConstants::LOG_MEM_USED | LogConstants::LOG_MEM_PEAK_USED);
}

function fn_shutdown_handler(){
    $e = error_get_last();
    if($e && $e['type'] === E_ERROR|E_COMPILE_ERROR|E_CORE_ERROR){
        $e_type = LogHelper::fn_get_friendly_error_type($e['type']);
        $e_msg = $e['message'] . " in " . $e['file'] . ' line ' . $e['line'];
        LogError::dump_log($e_type, $e_msg, LogConstants::LOG_PROCESSID | LogConstants::LOG_MEM_USED | LogConstants::LOG_MEM_PEAK_USED);
    }
    
    if (extension_loaded('newrelic')) {
       newrelic_add_custom_parameter('memory-used-at-end', memory_get_usage(true));
       newrelic_add_custom_parameter('memory-peak-usage', memory_get_peak_usage(true));
    }
    
    if(LogHelper::log_enabled() && !Registry::get('exec_time_logged')){
        $exec_time = LogHelper::fn_get_execution_time();
        if($exec_time > -1){
            LogMetric::dump_log("execution_time", $exec_time);
        }
    }

}

set_error_handler('fn_catch_errors');
register_shutdown_function('fn_shutdown_handler');

define('TIME', time());
define('MICROTIME', microtime(true));
define('MIN_PHP_VERSION', '5.1.0');
if (stristr(PHP_OS, 'WIN')) {// Define operation system
	define('IS_WINDOWS', true);
}
define('DIR_ROOT', fn_unified_path(dirname(__FILE__))); // Real path to the directory where software is installed

if (empty($_SERVER['REQUEST_METHOD'])) { // if we do not have $_SERVER['REQUEST_METHOD'], assume that we're in console mode
	define('CONSOLE', true);
}

if (version_compare(PHP_VERSION, MIN_PHP_VERSION, '<')) {
	die('PHP version <b>' . MIN_PHP_VERSION . '</b> or greater is required. Your PHP is version <b>' . PHP_VERSION . '</b>, please ask your host to upgrade it.');
}

if (!isset($_SERVER['HTTP_HOST'])) {
	$_SERVER['HTTP_HOST'] = 'localhost';
}

// Detect https
if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == '1')) {
	define('HTTPS', true);
} elseif (isset($_SERVER['HTTP_X_FORWARDED_SERVER']) && ($_SERVER['HTTP_X_FORWARDED_SERVER'] == 'secure' || $_SERVER['HTTP_X_FORWARDED_SERVER'] == 'ssl')) {
	define('HTTPS', true);
} elseif (isset($_SERVER['SCRIPT_URI']) && (substr($_SERVER['SCRIPT_URI'], 0, 5) == 'https')) {
	define('HTTPS', true);
} elseif (isset($_SERVER['HTTP_HOST']) && (strpos($_SERVER['HTTP_HOST'], ':443') !== false)) {
	define('HTTPS', true);
}

// Detect http host
if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
	define('REAL_HOST', $_SERVER['HTTP_X_FORWARDED_HOST']);
} else {
	define('REAL_HOST', $_SERVER['HTTP_HOST']);
}

if (version_compare(PHP_VERSION, '5.3.0', '<')) {
	set_magic_quotes_runtime(0);
}

if (get_magic_quotes_gpc()) {
	define('QUOTES_ENABLED', true);
}

ini_set('magic_quotes_sybase', 0);
ini_set('session.bug_compat_42', 1);
ini_set('session.bug_compat_warn', 0);
ini_set('session.use_only_cookies', 1);
ini_set('session.use_trans_sid', 0);

if (defined('CONSOLE')) { // Script is running from the server
	if (!fn_parse_cmd_args()) {
		die('Invalid parameters list');
	}

	$_SERVER['SERVER_SOFTWARE'] = 'CART';
	$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
	$_SERVER['REQUEST_METHOD'] = 'GET';
	$_SERVER['HTTP_USER_AGENT'] = 'Console';
	chdir(DIR_ROOT);
	@set_time_limit(0); // the script, running in console mode has no time limits

} else {
	// Prevent caching
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');

	if (defined('HTTPS')) {
		header('Cache-Control: private');
	} else {
		header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		header('Pragma: no-cache');
		header('Expires: -1');
	}
	define('IIS', (stristr($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS')) ? true : false);
	if (isset($_SERVER['HTTP_X_REWRITE_URL'])) { // for isapi_rewrite
		$_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_REWRITE_URL'];
	}
	// Define real URL
	define('REAL_URL', (defined('HTTPS') ? 'https://' : 'http://') . REAL_HOST . !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '');
}

if (!defined('PATH_SEPARATOR')) {
	define('PATH_SEPARATOR', (defined('IS_WINDOWS')) ? ';' : ':');
}

define('PS', PATH_SEPARATOR);

if (!empty($_SERVER['QUERY_STRING'])) {
	$_SERVER['QUERY_STRING'] = (defined('QUOTES_ENABLED')) ? stripslashes($_SERVER['QUERY_STRING']) : $_SERVER['QUERY_STRING'];
	$_SERVER['QUERY_STRING'] = str_replace(array('"', "'"), array('', ''), $_SERVER['QUERY_STRING']);
}

if (fn_get_ini_param('register_globals')) {
	fn_unregister_globals();
}

// create REQUEST from stripped GET and POST
$_REQUEST = fn_safe_input(array_merge($_POST, $_GET));

// Set pear include path
@ini_set('include_path', DIR_ROOT . '/lib/pear/' . ini_get('include_path'));

if (extension_loaded('newrelic')) {
    newrelic_add_custom_parameter('process-id', getmypid());
    newrelic_add_custom_parameter('memory-used-at-start', memory_get_usage(true));
    newrelic_add_custom_parameter('request-url', LogHelper::fn_get_url_for_external_logging(LogHelper::get_request_url()));
}

/**
 * Retrieve parameter from php options
 *
 * @param string $param parameter to get value for
 * @param boolean $get_value if true, get value, otherwise return true if parameter enabled, false if disabled
 * @return mixed parameter value
 */
function fn_get_ini_param($param, $get_value = false)
{
	$res = ini_get($param);
	if ($get_value == true) {
		return $res;
	} else {
		return (intval($res) || !strcasecmp($res, 'on')) ? true : false;
	}
}

/**
 * Strip html tags from the data
 *
 * @param mixed $var variable to strip tags from
 * @return mixed filtered variable
 */
function fn_strip_tags(&$var)
{

	if (!is_array($var)) {
		return (strip_tags($var));
	} else {
		$stripped = array();
		foreach ($var as $k => $v) {
			$sk = strip_tags($k);
			if (!is_array($v)) {
				$sv = strip_tags($v);
			} else {
				$sv = fn_strip_tags($v);
			}
			$stripped[$sk] = $sv;
		}
		return ($stripped);
	}
}

/**
 * Add/remove html special chars
 *
 * @param mixed $data data to filter
 * @param boolean $revert if true, decode special chars
 * @return mixed filtered variable
 */
function fn_html_escape($data, $revert = false)
{
	if (is_array($data)) {
		foreach ($data as $k => $sub) {
			if (is_array($sub) === true) {
				$data[$k] = fn_html_escape($sub, $revert);
			} else {
				$data[$k] = ($revert == false) ? htmlspecialchars($sub, ENT_QUOTES, 'UTF-8') : htmlspecialchars_decode($sub, ENT_QUOTES);
			}
		}
	} else {
		$data = ($revert == false) ? htmlspecialchars($data, ENT_QUOTES, 'UTF-8') : htmlspecialchars_decode($data, ENT_QUOTES);
	}

	return $data;
}

/**
 * Add slashes
 *
 * @param mixed $var variable to add slashes to
 * @param boolean $escape_nls if true, escape "new line" chars with extra slash
 * @return mixed filtered variable
 */
function fn_add_slashes(&$var, $escape_nls = false)
{
	if (!is_array($var)) {
		return ($var === null) ? null : (($escape_nls == true) ? str_replace("\n", "\\n", addslashes($var)) : addslashes($var));
	} else {
		$slashed = array();
		foreach ($var as $k => $v) {
			$sk = addslashes($k);
			if (!is_array($v)) {
				$sv = ($v === null) ? null : (($escape_nls == true) ? str_replace("\n", "\\n", addslashes($v)) : addslashes($v));
			} else {
				$sv = fn_add_slashes($v, $escape_nls);
			}
			$slashed[$sk] = $sv;
		}
		return ($slashed);
	}
}

/**
 * Strip slashes
 *
 * @param mixed $var variable to strip slashes from
 * @return mixed filtered variable
 */
function fn_strip_slashes($var)
{
	if (is_array($var)) {
		$var = array_map('fn_strip_slashes', $var);
		return $var;
	}

	return (strpos($var, '\\\'') !== false || strpos($var, '\\\\') !== false || strpos($var, '\\"') !== false) ? stripslashes($var) : $var;
}

/**
 * Replace backslashes in windows-style path
 *
 * @param string $path path
 * @return string filtered path
 */
function fn_unified_path($path)
{
	if (defined('IS_WINDOWS')) {
		$path = str_replace('\\', '/', $path);
	}
	return $path;
}

/**
 * Sanitize input data
 *
 * @param mixed $data data to filter
 * @return mixed filtered data
 */
function fn_safe_input($data)
{
	if (defined('QUOTES_ENABLED')) {
		$data = fn_strip_slashes($data);
	}

	return fn_strip_tags($data);
}

/**
 * Delete request variables from the global scope
 *
 * @param string $key if passed, deletes data of this passed superglobal variable
 * @return boolean always true
 */
function fn_unregister_globals($key = NULL)
{
	static $_vars = array('_GET', '_POST', '_FILES', '_ENV', '_COOKIE', '_SERVER');

	$vars = ($key) ? array($key) : $_vars;
	foreach ($vars as $var) {
		if (isset($GLOBALS[$var])) {
			foreach ($GLOBALS[$var] as $k => $v) {
				unset($GLOBALS[$k]);
			}
		}
		if (isset($GLOBALS['HTTP' . $var . '_VARS'])) {
			unset($GLOBALS['HTTP' . $var . '_VARS']);
		}
	}

	return true;
}

/**
 * Parse command-line parameters and put them to _GET array
 *
 * @return boolean true if parameters parsed correctly, false - otherwise
 */
function fn_parse_cmd_args()
{
	while ($code = next($_SERVER['argv'])) {
		if (preg_match('/^-{2}([a-zA-Z0-9_]*)=?(.*)$/', $code, $matches)) {
			$_GET[$matches[1]] = $matches[2];

		} elseif(preg_match('/^-{1}([a-zA-Z0-9]*)$/', $code, $matches)) {
			if (!$value = next($_SERVER['argv'])) {
				return false;
			}
			$_GET[$matches[1]] = $value;
		}
	}

	return true;
}

?>
