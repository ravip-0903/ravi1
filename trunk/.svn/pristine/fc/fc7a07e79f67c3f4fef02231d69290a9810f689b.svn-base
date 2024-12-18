<?php
/***************************************************************************
*                                                                          *
*   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
*                                                                          *
* This  is  commercial  software,  only  users  who have purchased a valid *
* license  and  accept  to the terms of the  License Agreement can install *
* and use this program.                                                    *
*                                                                          *
****************************************************************************
* PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
* "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
****************************************************************************/

if ( !defined('AREA') )	{ die('Access denied');	}

define('GET_CONTROLLERS', 1);
define('GET_PRE_CONTROLLERS', 2);
define('GET_POST_CONTROLLERS', 3);

/**
 * Set hook to use by addons
 *
 * @param mixed $argN argument, passed to addon
 * @return boolean always true
 */
function fn_set_hook($arg0 = NULL, &$arg1 = NULL, &$arg2 = NULL, &$arg3 = NULL, &$arg4 = NULL, &$arg5 = NULL, &$arg6 = NULL, &$arg7 = NULL, &$arg8 = NULL, &$arg9 = NULL, &$arg10 = NULL, &$arg11 = NULL, &$arg12 = NULL, &$arg13 = NULL, &$arg14 = NULL, &$arg15 = NULL)
{
	$hooks = Registry::get('hooks');
	static $loaded_addons;
	static $callable_functions;
	static $hooks_already_sorted;
	
	$edition_hooks = array(
		'COMMUNITY'    => 'com_',
		'PROFESSIONAL' => 'pro_',
		'MULTIVENDOR'  => 'mve_',
		'MULTISHOP'    => 'mse_',
	);

	for ($args = array(), $i = 0; $i < 10; $i++) {
		$name = 'arg' . $i;
		if ($i < func_num_args()) {
			$args[$i] = &$$name;
		}
		unset($$name, $name);
	}

	$hook_name = array_shift($args);

	// Check for the core functions
	$core_func = 'fn_core_' . $hook_name;
	if (is_callable($core_func)) {
		call_user_func_array($core_func, $args);
	}
	
	if (!empty($edition_hooks[PRODUCT_TYPE])) {
		$edition_hook_func = 'fn_' . $edition_hooks[PRODUCT_TYPE] . $hook_name;
		if (is_callable($edition_hook_func)) {
			call_user_func_array($edition_hook_func, $args);
		}
	}

	if (isset($hooks[$hook_name])) {

		// cache hooks sorting
		if (!isset($hooks_already_sorted[$hook_name])) {
			$hooks[$hook_name] = fn_sort_array_by_key($hooks[$hook_name], 'priority');
			$hooks_already_sorted[$hook_name] = true;
		}

		foreach ($hooks[$hook_name] as $callback) {

			//cache loaded addon
			if (!isset($loaded_addons[$callback['addon']])) { // FIXME: duplicate with cache in fn_load_addon
				fn_load_addon($callback['addon']);
				$loaded_addons[$callback['addon']] = true;
			}

			// cache if hook function callable
			if (!isset($callable_functions[$callback['func']])) {
				if (!is_callable($callback['func'])) {
					die("Hook $callback[func] is not callable");
				}
				$callable_functions[$callback['func']] = true;
			}

			call_user_func_array($callback['func'], $args);
		}
	}

	return true;
}


/**
 * Register hooks addon uses
 *
 * @return boolean always true
 */
function fn_register_hooks()
{
	$hooks = & Registry::get('hooks');

	$args = func_get_args();
	$backtrace = debug_backtrace();

	$addon_path = fn_unified_path($backtrace[0]['file']);
	
	$path_dirs = explode('/', substr($addon_path, strlen(DIR_ADDONS)));
	$addon_name = array_shift($path_dirs);
	
	$addon_priority = Registry::get('addons.' . $addon_name . '.priority');
	foreach ($args as &$hook) {
		$priority = $addon_priority;

		// if we get array we need to set priority manually
		if(is_array($hook)) {
			$priority = $hook[1];
			$hook = $hook[0];
		}

		$callback = 'fn_' . $addon_name . '_' . $hook;

		if (!isset($hooks[$hook])) {
			$hooks[$hook] = Array();
		}

		$hooks[$hook][] = array('func' => $callback, 'addon' => $addon_name, 'priority' => $priority);
	}

	return true;
}

/**
 * Initialize all enabled addons
 *
 * @return boolean always true
 */
function fn_init_addons()
{
	$account_type = PRODUCT_TYPE == 'MULTIVENDOR' ? ((defined('ACCOUNT_TYPE') && ACCOUNT_TYPE == 'vendor' || defined('SELECTED_COMPANY_ID') && SELECTED_COMPANY_ID != 'all') ? 'vendor' : 'admin') : '';
	Registry::register_cache('addons', array('addons'), CACHE_LEVEL_STATIC . $account_type);

	// Get settings
	if (Registry::is_exist('addons') == false) {
		$_addons = db_get_hash_array("SELECT addon, priority, IF(status = 'A', options, '') as options, status FROM ?:addons ORDER BY priority", 'addon');
		foreach ($_addons as $k => $v) {
			if (PRODUCT_TYPE == 'MULTIVENDOR' && (defined('ACCOUNT_TYPE') && ACCOUNT_TYPE == 'vendor' || defined('SELECTED_COMPANY_ID') && SELECTED_COMPANY_ID != 'all') && !fn_check_addon_permission($k)) {
				unset($_addons[$k]);
				continue;
			}
			$_addons[$k] = ($v['status'] == 'A' && !empty($v['options'])) ? fn_parse_addon_options($v['options']) : array();
			$_addons[$k]['status'] = $v['status'];
			$_addons[$k]['priority'] = $v['priority'];
		}
		Registry::set('addons', $_addons);
	}

	foreach	((array)Registry::get('addons') as $addon_name => $data) {
		if ($data['status'] == 'A') {
			if (is_file(DIR_ADDONS . $addon_name . '/init.php')) {
				include(DIR_ADDONS . $addon_name . '/init.php');
			}
		}
	}

	return true;
}

/**
 * Get multilingual addon options
 *
 * @param string $lang_code language code to initialize options for
 * @return boolean always true
 */
function fn_init_addon_options($lang_code = CART_LANGUAGE)
{
	$addons = Registry::get('addons');

	Registry::register_cache('addon_options', array('addons'), CACHE_LEVEL_LOCALE);

	// Get settings
	if (Registry::is_exist('addon_options') == false) {
		$addon_options = db_get_hash_multi_array("SELECT object_id, description, addon FROM ?:addon_descriptions WHERE object_id != '' AND object_type = 'L' AND lang_code = ?s", array('addon', 'object_id'), $lang_code);
		Registry::set('addon_options', $addon_options);
	} else {
		$addon_options = Registry::get('addon_options');
	}

	foreach ($addons as $k => $v) {
		if (!empty($addon_options[$k])) {
			foreach ($addons[$k] as $opt => $val) {
				if ($val == '%ML%' && isset($addon_options[$k][$opt])) {
					$addons[$k][$opt] = $addon_options[$k][$opt]['description'];
				}
			}
		}
	}

	Registry::set('addons', $addons, true);

	return true;
}


/**
 * Load addon
 *
 * @param string $addon_name addon name
 * @return boolean true if addon loaded, false otherwise
 */
function fn_load_addon($addon_name)
{
	static $cache = array(); // FIXME: duplicate with fn_set_hook
        
	if (!isset($cache[$addon_name])) {
		if (Registry::get("addons.$addon_name.status") === 'D') {
			$cache[$addon_name] = false;
			return false;
		}

		if (file_exists(DIR_ADDONS . $addon_name . '/func.php')) {
                        /*          Fix ME
                         *  can we use include_once and neglet the condition for api ??
                         *  need to see if frontend works fine if we replaced include with include_once.                         * 
                         */
                        
                        if(ANDROID_API == 'TRUE'){
                            include_once(DIR_ADDONS . $addon_name . '/func.php');           //if condition added for mobile api
                        }
                        else{
                            include(DIR_ADDONS . $addon_name . '/func.php');
                        }
                            
                        
                }
		if (file_exists(DIR_ADDONS . $addon_name . '/config.php')) {
			if(ANDROID_API == 'TRUE'){
                            include_once(DIR_ADDONS . $addon_name . '/config.php');         //if condition added for mobile api
                        }
                        else{
                            include(DIR_ADDONS . $addon_name . '/config.php');                            
                        }                            
                }
		$cache[$addon_name] = true;
	}

	return $cache[$addon_name];
}

/**
 * Gets list of secure controllers which use https connection
 *
 * @return array list of secure controllers
 */
function fn_get_secure_controllers()
{
	$secure_controllers = array(
		'payment_notification' => 'passive',
		'image' => 'passive',
	);

	if (Registry::get('settings.General.secure_auth') == 'Y') {
		$secure_controllers = array_merge($secure_controllers, array(
			'auth' => 'active',
			'orders' => 'active',
			'profiles' => 'active',
		));
	}
	
	if(in_array(CONTROLLER,Registry::get('config.secure_controllers')))
	{
		$secure_controllers = array_merge($secure_controllers, array(
		 CONTROLLER =>'active'
		));
	}

	if (Registry::get('settings.General.secure_checkout') == 'Y') {
		$secure_controllers = array_merge($secure_controllers, array(
			'checkout' => 'active',
		));
	}

	fn_set_hook('init_secure_controllers', $secure_controllers);

	return $secure_controllers;
}

/**
 * Dispathes the execution control to correct controller
 *
 * @return nothing
 */
function fn_dispatch()
{
	Profiler::checkpoint('After init');

	fn_set_hook('before_dispatch');

	$regexp = "/^[a-zA-Z0-9_\+]+$/";
	$view = & Registry::get('view');
	$run_controllers = true;
	$external = false;
	$status = CONTROLLER_STATUS_NO_PAGE;

	// Security
	if (Registry::get('config.tweaks.anti_csfr') == true) {
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && (empty($_SESSION['security_hash']) || empty($_REQUEST['security_hash']) || $_REQUEST['security_hash'] != $_SESSION['security_hash'])) {
			die('Access denied: CSRF attack');
		}
	}

	//If $config['http_host'] was different from the domain name, there was redirection to $config['http_host'] value.
	if ((defined('HTTPS') ? Registry::get('config.https_host') : Registry::get('config.http_host')) != REAL_HOST && $_SERVER['REQUEST_METHOD'] == 'GET' && !defined('CONSOLE')) {
		fn_redirect((defined('HTTPS') ? 'https://' . Registry::get('config.https_host') : 'http://' . Registry::get('config.http_host')) . (!empty($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL'] : (!empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : Registry::get('config.current_url'))), false, true);
	}
	if (isset($_SERVER['CONTENT_LENGTH']) && ($_SERVER['CONTENT_LENGTH'] > fn_return_bytes(ini_get('upload_max_filesize')) || $_SERVER['CONTENT_LENGTH'] > fn_return_bytes(ini_get('post_max_size')))) {
		$max_size = fn_return_bytes(ini_get('upload_max_filesize')) < fn_return_bytes(ini_get('post_max_size')) ? ini_get('upload_max_filesize') : ini_get('post_max_size');
		$msg = fn_get_lang_var('text_forbidden_uploaded_file_size');
		$msg = str_replace('[size]', $max_size, $msg);
		fn_set_notification('E', fn_get_lang_var('error'), $msg);
		fn_redirect($_SERVER['HTTP_REFERER'], false);
	}

	// If URL contains session ID, remove it
	if (!empty($_REQUEST[SESS_NAME]) && $_SERVER['REQUEST_METHOD'] == 'GET') {
		fn_redirect(fn_query_remove(Registry::get('config.current_url'), SESS_NAME));
	}

	if (!preg_match($regexp, CONTROLLER) || !preg_match($regexp, MODE)) {
		$status = CONTROLLER_STATUS_NO_PAGE;
		$run_controllers = false;
	}

	// If demo mode is enabled, check permissions FIX ME - why did we need one more user login check?
	if (AREA == 'A') {
		if (Registry::get('config.demo_mode') == true) {
			$run_controllers = fn_check_permissions(CONTROLLER, MODE, 'demo');

			if ($run_controllers == false) {
				fn_set_notification('W', fn_get_lang_var('demo_mode'), fn_get_lang_var('demo_mode_content_text'), 'K', 'demo_mode');
				if (defined('AJAX_REQUEST')) {
					exit;
				}
				
				fn_delete_notification('changes_saved');
				
				$status = CONTROLLER_STATUS_REDIRECT;
				$_REQUEST['redirect_url'] = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : INDEX_SCRIPT;
			}
		} elseif (!empty($_SESSION['auth']['usergroup_ids']) || defined('COMPANY_ID')) {
			$run_controllers = fn_check_permissions(CONTROLLER, MODE, 'admin', '', $_REQUEST);
			if ($run_controllers == false) {
				if (defined('AJAX_REQUEST')) {
					$ajax = & Registry::get('ajax');
					$force_redirection = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
					//$ajax->assign('force_redirection', $force_redirection);
					$_info = defined('DEVELOPMENT') ? ' ' . CONTROLLER . '.' . MODE : '';
					fn_set_notification('W', fn_get_lang_var('warning'), fn_get_lang_var('access_denied') . $_info);
					exit;
				}
				$status = CONTROLLER_STATUS_DENIED;
			}
		}
	}

	// Check if request was rewritten and not handled
	// In this case this means that request was incorrect
	if (isset($_REQUEST['sef_rewrite'])) {
		$status = CONTROLLER_STATUS_NO_PAGE;
		$run_controllers = false;
	}


	if (AREA == 'A' && (Registry::get('settings.General.secure_admin') == 'Y')  && !defined('HTTPS') && ($_SERVER['REQUEST_METHOD'] != 'POST') && !defined('AJAX_REQUEST') && empty($_REQUEST['keep_location']) && !defined('CONSOLE')) {
		fn_redirect(Registry::get('config.https_location') . '/' . Registry::get('config.current_url'));
	} elseif (AREA == 'C' && $_SERVER['REQUEST_METHOD'] != 'POST' && !defined('AJAX_REQUEST')) {
		$secure_controllers = fn_get_secure_controllers();
		// if we are not on https but controller is secure, redirect to https
		if (isset($secure_controllers[CONTROLLER]) && $secure_controllers[CONTROLLER] == 'active' && !defined('HTTPS')) {
			fn_redirect(Registry::get('config.https_location') . '/' . Registry::get('config.current_url'));
		}

		// if we are on https and the controller is insecure, redirect to http
		if (!isset($secure_controllers[CONTROLLER]) && defined('HTTPS') && Registry::get('settings.General.keep_https') != 'Y') {
			fn_redirect(('http://' . Registry::get('config.http_host')) . (!empty($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL'] : Registry::get('config.http_path') . '/' . Registry::get('config.current_url')));
		}
	}

	if (AREA == 'A') {
		fn_init_last_view($_REQUEST);
	}

	$controllers_cascade = array();
	$controllers_list = array('init');
	if ($run_controllers == true) {
		$controllers_list[] = CONTROLLER;
		$controllers_list = array_unique($controllers_list);
	} 
	foreach ($controllers_list as $ctrl) {
		$core_controllers = fn_init_core_controllers($ctrl);
		list($addon_controllers) = fn_init_addon_controllers($ctrl);

		if (empty($core_controllers) && empty($addon_controllers)) {
			$controllers_cascade = array();
			$status = CONTROLLER_STATUS_NO_PAGE;
			break;
		}

		if ((count($core_controllers) + count($addon_controllers)) > 1) {
			die('Duplicate controller ' . CONTROLLER . fn_print_r(array_merge($core_controllers, $addon_controllers), 1));
		}

		$core_pre_controllers = fn_init_core_controllers($ctrl, GET_PRE_CONTROLLERS);
		$core_post_controllers = fn_init_core_controllers($ctrl, GET_POST_CONTROLLERS);

		list($addon_pre_controllers) = fn_init_addon_controllers($ctrl, GET_PRE_CONTROLLERS);
		list($addon_post_controllers, $addons) = fn_init_addon_controllers($ctrl, GET_POST_CONTROLLERS);

		// we put addon post-controller to the top of post-controller cascade if current addon serves this request
		if(count($addon_controllers)) {
			$addon_post_controllers = fn_reorder_post_controllers($addon_post_controllers, $addon_controllers[0]);
		}

		$controllers_cascade = array_merge($controllers_cascade, $addon_pre_controllers, $core_pre_controllers, $core_controllers, $addon_controllers, $core_post_controllers, $addon_post_controllers);

		if (empty($controllers_cascade)) {
			die("No controllers for: $controller");
		}
	}
        
        fn_init_sessioncart_from_cookie();


	if (MODE == 'add') {
		$tpl = 'update.tpl';
	} elseif (strpos(MODE, 'add_') === 0) {
		$tpl = str_replace('add_', 'update_', MODE) . '.tpl';
	} else {
		$tpl = MODE . '.tpl';
	}

	$view = & Registry::get('view');
	if ($view->template_exists('views/' . CONTROLLER . '/' . $tpl)) { // try to find template in base views
		$view->assign('content_tpl', 'views/' . CONTROLLER . '/' . $tpl);
	} elseif (defined('LOADED_ADDON_PATH') && $view->template_exists('addons/' . LOADED_ADDON_PATH . '/views/' . CONTROLLER . '/' . $tpl)) { // try to find template in addon views
		$view->assign('content_tpl', 'addons/' . LOADED_ADDON_PATH . '/views/' . CONTROLLER . '/' . $tpl);
	} elseif (!empty($addons)) { // try to find template in addon views that extend base views
		foreach ($addons as $addon => $_v) {
			if ($view->template_exists('addons/' . $addon . '/views/' . CONTROLLER . '/' . $tpl)) {
				$view->assign('content_tpl', 'addons/' . $addon . '/views/' . CONTROLLER . '/' . $tpl);
				break;
			}
		}
	}

	foreach ($controllers_cascade as $item) {
		$_res = fn_run_controller($item); // 0 - status, 1 - url

		$external = !empty($_res[2]) ? $_res[2] : false;
		$url = !empty($_res[1]) ? $_res[1] : '';
		// Status could be changed only if we allow to run controllers despite of init controller
		if ($run_controllers == true) {
			$status = !empty($_res[0]) ? $_res[0] : CONTROLLER_STATUS_OK;
		}

		if ($status == CONTROLLER_STATUS_OK && !empty($url)) {
			$redirect_url = $url;
		} elseif ($status == CONTROLLER_STATUS_REDIRECT && !empty($url)) {
			$redirect_url = $url;
			break;
		} elseif ($status == CONTROLLER_STATUS_DENIED || $status == CONTROLLER_STATUS_NO_PAGE) {
			break;
		}
	}

	if (AREA == 'A') {
		fn_init_view_tools($_REQUEST);
	}

	// In console mode, just stop here
	if (defined('CONSOLE')) {
		exit;
	}

	// Redirect if controller returned successful/redirect status only
	if (in_array($status, array(CONTROLLER_STATUS_OK, CONTROLLER_STATUS_REDIRECT)) && !empty($_REQUEST['redirect_url']) && !$external) {
		$redirect_url = $_REQUEST['redirect_url'];
	}

	// If controller returns "Redirect" status, check if redirect url exists
	if ($status == CONTROLLER_STATUS_REDIRECT && empty($redirect_url)) {
		$status = CONTROLLER_STATUS_NO_PAGE;
	}

	// Attach params and redirect if needed
	if (in_array($status, array(CONTROLLER_STATUS_OK, CONTROLLER_STATUS_REDIRECT)) && !empty($redirect_url)) {
		$params = array (
			'page',
			'selected_section',
		);

		$url_params = array();
		foreach ($params as $param) {
			if (!empty($_REQUEST[$param])) {
				$url_params[] = "$param=" . $_REQUEST[$param];
			}
		}
		if (!empty($url_params)) {
			$redirect_url .= (strpos($redirect_url, '?') === false ? '?' : '&') . implode('&', $url_params);
		}

		if (!isset($external)) {
			$external = false;
		}

		fn_redirect($redirect_url, false, $external);
	}

	if (!$view->get_var('content_tpl') && $status == CONTROLLER_STATUS_OK) { // FIXME
		$status = CONTROLLER_STATUS_NO_PAGE;
	}

	if ($status != CONTROLLER_STATUS_OK) {

		if ($status == CONTROLLER_STATUS_NO_PAGE) {
			header(' ', true, 404);
                        $log_keys['status'] = 404;
		}
		$view->assign('exception_status', $status);
		$view->assign('content_tpl', 'exception.tpl');
		if ($status == CONTROLLER_STATUS_DENIED) {
			$view->assign('page_title', fn_get_lang_var('access_denied'));
                        $log_keys['status_message'] = 'access_denied';
		} elseif ($status == CONTROLLER_STATUS_NO_PAGE) {
			$view->assign('page_title', fn_get_lang_var('page_not_found'));
                        $log_keys['status_message'] = 'page_not_found';
		}
		if (AREA != 'A') {
			Registry::set('root_template', 'exception.tpl');
		}
                LogMetric::dump_log(array_keys($log_keys), array_values($log_keys));
	}
	Profiler::checkpoint('Before TPL');	
	writedate("before just call back");
	writedate("before just call back");
	if(isset($_GET['mergescript'])){
		ob_start("callback_hp");
	}
	writedate("just call back");
	Registry::get('view')->display(Registry::get('root_template'));
	writedate("after call back");
	if(isset($_GET['mergescript'])){
		ob_end_flush();
	}
	Profiler::checkpoint('After TPL');
	Profiler::display();
	

	fn_set_hook('complete');

	if (extension_loaded('newrelic')) {
	   newrelic_add_custom_parameter('memory-used-at-end', memory_get_usage(true));
	   newrelic_add_custom_parameter('memory-peak-usage', memory_get_peak_usage(true));
	}
        
        $exec_time = LogHelper::fn_get_execution_time();
        if($exec_time > -1){
            LogMetric::dump_log("execution_time", $exec_time);
            Registry::set('exec_time_logged', true);
        }
        
 	exit; // stop execution
}
function writedate($str){return;
 $myFile = "a.txt";
	$fh = fopen($myFile, 'a') or die("can't open file");
	fwrite($fh, date("Y-m-d\TH:i:s") . substr((string)microtime(), 1, 8) . " ".$str . "\r\n");
	fclose($fh);
}
function callback_hp($buffer)
{
	
	if((int)stripos($buffer,"<title>Page Not Found</title>")>0)
	{
		return $buffer;
	}
	$strt = '';
	$strarr = array();
	$strt2 = '';
	$myFile = "includes.js";
	while(stripos($buffer,"<script"))
	{
		$i = stripos($buffer,"<script");
		$str = substr($buffer,$i);
		$j = stripos($str,"</script>");
		$str = substr($str,0,$j+9);
		$buffer = str_replace($str,"",$buffer);
		$strarr[] = $str;
	}
	if($_GET['mergebutdontwrite'])
	{
		$y = strrpos($buffer,"</body>");
		$str_sc = "<script src='".Registry::get('config.http_location')."/".$myFile."?1'></script></body>";
		$buffer = substr($buffer,0,$y) . $str_sc . substr($buffer,$y+7);
		writedate("nomergeend");
		return $buffer;
	}
	foreach($strarr as $str)
	{
		$i = stripos($str,">");
		$j = stripos($str,"src");
		if((int)$j<(int)$i && $j != false)
		{
			$str_r = substr($str,$j);
			$k = stripos($str_r,"\"");
			$l = stripos($str_r,"'");
			$strnext = "";
			$m = 0;
			if($k != false && $l != false)
			{
				if((int)$k < (int)$l)
				{
					$m = $k;
					$strnext = "\"";
				}
				else
				{
					$m = $l;
					$strnext = "'";
				}
			}
			else
			{
				if($k!=false)
				{
					$m = $k;
					$strnext = "\"";
				}
				if($l != false)
				{
					$m = $l;
					$strnext = "'";
				}
			}
			if($strnext !=''){
				$str_r = substr($str_r,$m+1);
				$n = stripos($str_r,$strnext);
				$str_r = substr($str_r,0,$n);
			}
			$z = stripos("B" . $str_r,"http");
			$str_http = '';
			if((int)$z == 0)
			{
				$str_http = Registry::get('config.http_location');
				$str_http = str_replace( Registry::get('config.http_path'),"",$str_http);
			}
			$strresponse = '';
			$str_r = "/" . $str_r;
			$str_r = str_replace("//","/",$str_r);
			$strurl = $str_http . $str_r;
			if (fopen($strurl, "r"))
			{
				$strresponse =  file_get_contents($strurl);
			}
			$strt = $strt . "\r\n // INCLUDE JSFILE " . $strurl . "\r\n";
			$strt = $strt.  $strresponse;
			
			//break;
		}
		else
		{
			$j = stripos($str,"</script");
			$str_r = substr($str,$i+1,$j-$i-1);
			$strt = $strt . "\r\n //INLINE SCRIPT\r\n";
			$strt = $strt.  $str_r;
			
		}
	}
	$fh = fopen($myFile, 'w') or die("can't open file");
	$stringData = $strt;
	fwrite($fh, $stringData);
	fclose($fh);
	$y = strrpos($buffer,"</body>");
	$str_sc = "<script src='".Registry::get('config.http_location')."/".$myFile."'></script></body>";
	$buffer = substr($buffer,0,$y) . $str_sc . substr($buffer,$y+7);
	writedate("mergeend");
	return $buffer;
}
/**
 * Puts the addon post-controller to the top of post-controllers cascade if current addon serves this request
 *
 * @param array $addon_post_controllers post controllers from addons
 * @param array $current_controller current controllers list
 * @return array controllers list
 */
function fn_reorder_post_controllers($addon_post_controllers, $current_controller)
{
	if (empty($addon_post_controllers) || empty($current_controller)) {
		return $addon_post_controllers;
	}

	// get addon name from the path like /var/www/html/cart/addons/addon/controllers/admin/addon.php
	$part = substr($current_controller, strlen(DIR_ADDONS));
	// we have addon/controllers/admin/addon.php  in $part
	$addon_name = substr($part, 0, strpos($part, '/'));

	// we search post-controller of the addon that owns active controller of current request
	// and if we find it we put this post-controller to the top of the cascade
	foreach($addon_post_controllers as $k => $post_controller) {
		if(strpos($post_controller, DIR_ADDONS . $post_controller) !== false) {
			// delete in current place..
			unset($addon_post_controllers[$k]);
			// and put at the beginning
			array_unshift($addon_post_controllers, $post_controller);
			break; // only one post controller can be here
		}
	}

	return $addon_post_controllers;
}

/**
 * Runs specified controller by including its file
 *
 * @param string $path path to controller
 * @return array controller return status
 */
function fn_run_controller($path)
{
	$auth = & $_SESSION['auth'];

	$controller = CONTROLLER;
	$mode = MODE;
	$action = ACTION;
	$dispatch_extra = DISPATCH_EXTRA;
	$index_script = INDEX_SCRIPT;

	$ajax = & Registry::get('ajax');
	$view = & Registry::get('view');
	$view_mail = & Registry::get('view_mail');

	return include($path);
}

/**
 * Generates list of core (pre/post)controllers
 *
 * @param string $controller controller name
 * @param string $type controller type (pre/post)
 * @return array controllers list
 */
function fn_init_core_controllers($controller, $type = GET_CONTROLLERS)
{
	$controllers = array();

	$prefix = '';

	if ($type == GET_POST_CONTROLLERS) {
		$prefix = '.post';
	} elseif ($type == GET_PRE_CONTROLLERS) {
		$prefix = '.pre';
	}

	// try to find area-specific controller
	if (is_readable(DIR_ROOT . '/controllers/' . AREA_NAME . '/' . $controller . $prefix . '.php')) {
		$controllers[] = DIR_ROOT . '/controllers/' . AREA_NAME . '/' . $controller . $prefix . '.php';
	}

	// try to find common controller
	if (is_readable(DIR_ROOT . '/controllers/common/' . $controller . $prefix . '.php')) {
		$controllers[] = DIR_ROOT . '/controllers/common/' . $controller . $prefix . '.php';
	}

	return $controllers;
}

/**
 * Generates list of (pre/post)controllers from active addons
 *
 * @param string $controller controller name
 * @param string $type controller type (pre/post)
 * @return array controllers list and active addons
 */
function fn_init_addon_controllers($controller, $type = GET_CONTROLLERS)
{
	$controllers = array();
	static $addons = array();
	$prefix = '';

	if($type == GET_POST_CONTROLLERS) {
		$prefix = '.post';
	} elseif ($type == GET_PRE_CONTROLLERS) {
		$prefix = '.pre';
	}

	foreach	((array)Registry::get('addons') as $addon_name => $data) {
		if (fn_load_addon($addon_name) == true) {
			// try to find area-specific controller
			$dir = DIR_ADDONS . $addon_name . '/controllers/' . AREA_NAME . '/';
			if (is_readable($dir . $controller . $prefix . '.php')) {
				$controllers[] = $dir . $controller . $prefix . '.php';
				$addons[$addon_name] = true;
				if (empty($prefix)) {
					fn_define('LOADED_ADDON_PATH', $addon_name);
				}
			}

			// try to find common controller
			$dir = DIR_ADDONS . $addon_name . '/controllers/common/';
			if (is_readable($dir . $controller . $prefix . '.php')) {
				$controllers[] = $dir . $controller . $prefix . '.php';
				$addons[$addon_name] = true;
				if (empty($prefix)) {
					fn_define('LOADED_ADDON_PATH', $addon_name);
				}
			}
		}
	}

	return array($controllers, $addons);
}

/**
 * Looks for "dispatch" parameter in REQUEST array and extracts controller, mode, action and extra parameters.
 *
 * @return boolean always true
 */
function fn_get_route()
{
	fn_set_hook('get_route', $_REQUEST);

	/*$url_pattern = parse_url($_SERVER['REQUEST_URI']);
	if (!empty($url_pattern['path'])) {
		$path = substr($url_pattern['path'], strlen((defined('HTTPS') ? Registry::get('config.https_path') : Registry::get('config.http_path'))) + 1); // remove path prefix
		$path = rtrim($path, '/'); // remove trailing slash

		$a = explode('/', $path);

		if ($a[0] == 'admincp') {
			fn_define('AREA', 'A');
			fn_define('AREA_NAME', 'admin');
			fn_define('ACCOUNT_TYPE', 'admin');
			array_shift($a);
		} elseif ($a[0] == 'vendorcp') {
			fn_define('AREA', 'A');
			fn_define('AREA_NAME', 'admin');
			fn_define('ACCOUNT_TYPE', 'vendor');
			array_shift($a);
		} else {
			fn_define('AREA', 'C');
			fn_define('AREA_NAME', 'customer');
			fn_define('ACCOUNT_TYPE', 'customer');
		}

		if (sizeof($a) == 3) {
			list($c, $m, $id) = $a;
		} elseif (sizeof($a) == 2) {
			list($c, $m) = $a;
		} elseif (fn_is_empty($a)) {
			$c = $m = 'index';
		}

		$_REQUEST['dispatch'] = $c . '.' . $m;
		if (!empty($id)) {
			if (substr($c, -3) == 'ies') {
				$k = substr_replace($c, 'y', -3) . '_id';
			} elseif (substr($c, -1) == 's') {
				$k = substr_replace($c, '', -1) . '_id';
			}

			if (!empty($k)) {
				$_REQUEST[$k] = $id;
			}
		}
		unset($_REQUEST['sef_rewrite']);
	}*/

	if (!empty($_REQUEST['dispatch'])) {
		$request = $_REQUEST['dispatch'] = is_array($_REQUEST['dispatch']) ? key($_REQUEST['dispatch']) : $_REQUEST['dispatch'];
	} else {
		$request = $_REQUEST['dispatch'] = 'index.index';
	}

	rtrim($request, '/');
	rtrim($request, '.');
	$request = str_replace('/', '.', $request);

	@list($c, $m, $a, $e) = explode('.', $request);

	define('CONTROLLER', empty($c) ? 'index' : $c);
	define('MODE', empty($m) ? 'index' : $m);
	define('ACTION', $a);
	define('DISPATCH_EXTRA', $e);

	$_REQUEST['dispatch'] = $request;
        
        if($n = LogHelper::fn_get_friendly_page_name())
            LogMetric::dump_log("page", $n);

	return true;
}

/**
 * Parse addon options
 *
 * @param string $options serialized options
 * @return array parsed options list
 */
function fn_parse_addon_options($options)
{
	$options = unserialize($options);
	if (!empty($options)) {
		foreach ($options as $k => $v) {
			if (strpos($v, '#M#') === 0) {
				parse_str(str_replace('#M#', '', $v), $options[$k]);
			}
		}
	}

	return $options;
}

/**
 * Get list of templates that should be overriden by addons
 *
 * @param string $resource_name base template name
 * @param object $view templater object
 * @return string overridden template name
 */
function fn_addon_template_overrides($resource_name, &$view)
{
	static $init = array();

	$o_name = 'template_overrides_' . AREA . '_' . $view->get_var('skin_area');
	
	if (!isset($init[$o_name])) {
		Registry::register_cache($o_name, array('addons'), CACHE_LEVEL_STATIC);

		if (!Registry::is_exist($o_name)) {
			$template_overrides = array();
			foreach (Registry::get('addons') as $a => $_settings) {
				$odir = $view->template_dir . '/addons/' . $a . '/overrides';
				if ($_settings['status'] == 'A' && is_dir($odir)) {
					$tpls = fn_get_dir_contents($odir, false, true, '', '', true);
					
					foreach ($tpls as $k => $t) {
						if (empty($template_overrides[md5($t)])) {
							$template_overrides[md5($t)] = 'addons/' . $a . '/overrides/' . $t;
						}
					}
				}
			}

			if (empty($template_overrides)) {
				$template_overrides['plug'] = true;
			}
			
			Registry::set($o_name, $template_overrides);
		}

		$init[$o_name] = true;
	}

	return Registry::if_get($o_name . '.' . md5($resource_name), $resource_name);
}

?>
