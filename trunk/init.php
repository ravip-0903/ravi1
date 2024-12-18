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


//
// $Id: init.php 12865 2011-07-05 06:57:22Z 2tl $
//

if ( !defined('AREA') ) { die('Access denied'); }

// Require configuration
require(DIR_ROOT . '/config.php');
        
LogMetric::dump_log("new_request", true, LogConstants::LOG_SERVER_NAME|LogConstants::LOG_CLIENT_IP|LogConstants::LOG_REQUEST_URL|LogConstants::LOG_REFERER_URL|LogConstants::LOG_PROCESSID);

if (isset($_REQUEST['version'])) {
	die(PRODUCT_NAME . ': version <b>' . PRODUCT_VERSION . ' ' . PRODUCT_TYPE . (PRODUCT_STATUS != '' ? (' (' . PRODUCT_STATUS . ')') : '') . '</b>');
}

if (isset($_REQUEST['check_https'])) {
	die(defined('HTTPS') ? 'OK' : '');
}

// Include core functions/classes
require(DIR_CORE . 'db/' . $config['db_type'] . '.php');
require(DIR_CORE . 'fn.database.php');
require(DIR_CORE . 'fn.users.php');
require(DIR_CORE . 'fn.catalog.php');
require(DIR_CORE . 'fn.cms.php');
require(DIR_CORE . 'fn.cart.php');
require(DIR_CORE . 'fn.ordersearch.php');
require(DIR_CORE . 'fn.locations.php');
require(DIR_CORE . 'fn.common.php');
require(DIR_CORE . 'fn.fs.php');
require(DIR_CORE . 'fn.requests.php');
require(DIR_CORE . 'fn.images.php');
require(DIR_CORE . 'fn.init.php');
require(DIR_CORE . 'fn.control.php');
require(DIR_CORE . 'fn.search.php');
require(DIR_CORE . 'fn.promotions.php');
require(DIR_CORE . 'fn.log.php');
require(DIR_CORE . 'fn.companies.php');
require(DIR_CORE . 'fn.wholesale.php');
require(DIR_CORE . 'excel_reader.php'); // Added By Sudhir dt 23 aug 2012
require(DIR_CORE.'fn.auction.php');

require(DIR_CORE . 'fn.nss.php');
require(DIR_CORE . 'fn.bazooka.php');
if (in_array(PRODUCT_TYPE, array('PROFESSIONAL', 'MULTIVENDOR', 'MULTISHOP'))) {
    require(DIR_CORE . 'editions/fn.pro_functions.php');
}
if (in_array(PRODUCT_TYPE, array('MULTIVENDOR', 'MULTISHOP'))) {
    require(DIR_CORE . 'editions/fn.mve_functions.php');
}
if (PRODUCT_TYPE == 'MULTISHOP') {
    require(DIR_CORE . 'editions/fn.mse_functions.php');
}

fn_define('ACCOUNT_TYPE', 'customer');
require(DIR_CORE . 'class.profiler.php');
require(DIR_CORE . 'class.registry.php');
require(DIR_CORE . 'class.session.php');

// Used for the javascript to be able to hide the Loading box when a downloadable file (pdf, etc.) is ready  
//setcookie('page_unload', 'N', '0', !empty($config['current_path'])? $config['current_path'] : '/');

if (isset($_GET['ct']) && (AREA == 'A' || defined('DEVELOPMENT'))) {
	fn_rm(DIR_THUMBNAILS, false);
}

// Set configuration options from config.php to registry
Registry::set('config', $config);
unset($config);

// Check if software is installed
if (Registry::get('config.db_host') == '%DB_HOST%') {
	die(PRODUCT_NAME . ' is <b>not installed</b>. Please click here to start the installation process: <a href="install/">[install]</a>');
}

// Connect to database
Registry::set('runtime.db.prepare.called', false);
Registry::set('runtime.db_prefixes.main', TABLE_PREFIX);

/*
$db_conn = db_initiate(Registry::get('config.db_host'), Registry::get('config.db_user'), Registry::get('config.db_password'), Registry::get('config.db_name'));

if (!$db_conn) {
	fn_error(debug_backtrace(), 'Cannot connect to the database server', false);
}

if (defined('MYSQL5')) {
	db_query("set @@sql_mode = ''");
}
*/

if(Registry::get('config.memcache'))
{
    $memcache = new Memcache;
    //$mem_conn= $memcache->connect(Registry::get('config.memcache_host_server'), Registry::get('config.memcache_port'));
    $memcache_servers = Registry::get('config.memcache_servers');
	foreach($memcache_servers as $memcache_server){
		$mem_conn=$memcache->addServer($memcache_server['host'],$memcache_server['port'],'',$memcache_server['weight']);
	}
    if($mem_conn){
        $GLOBALS['memcache'] = $memcache;
        $GLOBALS['memcache_status'] = $mem_conn;
    }
}
register_shutdown_function(array('Registry', 'save'));

// define lifetime for the cache data
define('CACHE_LEVEL_TIME', 'time');

// First-level cache: static - the same for all requests
define('CACHE_LEVEL_STATIC', 'cache_' . ACCOUNT_TYPE);

// define lifetime for the cache data
date_default_timezone_set('UTC'); // setting temporary timezone to avoid php warnings
define('CACHE_LEVEL_DAY', date('z', TIME));

// detect user agent
fn_init_ua();

// initialize ajax handler
fn_init_ajax();

// Start session mechanism
Session::init();

// initialize selected company
fn_init_selected_company_id($_REQUEST);

// var dirs
define('DIR_COMPILED', DIR_ROOT . VAR_PATH . '/var/compiled/');
define('DIR_DATABASE', DIR_ROOT . VAR_PATH . '/var/database/');
define('DIR_DOWNLOADS', DIR_ROOT . VAR_PATH . '/var/downloads/');
define('DIR_UPGRADE', DIR_ROOT . VAR_PATH . '/var/upgrade/');
define('DIR_EXIM', DIR_ROOT . VAR_PATH . '/var/exim/');
define('DIR_CACHE', DIR_ROOT . VAR_PATH . '/var/cache/');

// Clean up cache
if (isset($_GET['cc']) && (AREA == 'A' || defined('DEVELOPMENT'))) {
	fn_rm(DIR_COMPILED, false);
	fn_rm(DIR_CACHE, false);
	Registry::cleanup();
}

// Get settings
//Registry::register_cache('settings', array('settings'), CACHE_LEVEL_STATIC);
if (Registry::is_exist('settings') == false) {
	Registry::set('settings', fn_get_settings());
}

// Set timezone
date_default_timezone_set(Registry::get('settings.Appearance.timezone'));

// Init addons
fn_init_addons();

// get route to controller
fn_get_route();

// initialize store localization
if (AREA == 'C') {
	fn_init_localization($_REQUEST);
}

// initialize store language
fn_init_language($_REQUEST);

// initialize store currency
fn_init_currency($_REQUEST);

// Second-level (a) cache: different for dispatch-language-currency
define('CACHE_LEVEL_LOCALE', (defined('CART_LOCALIZATION') ? (CART_LOCALIZATION . '_') : '') . CART_LANGUAGE . '_' . CART_SECONDARY_CURRENCY);

// Second-level (b) cache: different for dispatch-language-currency
define('CACHE_LEVEL_DISPATCH', AREA . '_' . $_SERVER['REQUEST_METHOD'] . '_' . str_replace('.', '_', $_REQUEST['dispatch']) . '_' . (defined('CART_LOCALIZATION') ? (CART_LOCALIZATION . '_') : '') . CART_LANGUAGE . '_' . CART_SECONDARY_CURRENCY);
Registry::register_cache('lang_cache', array('language_values', 'mse_language_values'), CACHE_LEVEL_DISPATCH, true);

// initialize companies
fn_init_company($_REQUEST);

// Init addon multilingual options
fn_init_addon_options();

// init revisions
if (AREA == 'A' && Registry::get('settings.General.active_revisions_objects')) {
	require(DIR_CORE . 'fn.revisions.php');
	fn_init_revisions();
}

// Display full paths cresecure payment processor
if (isset($_REQUEST['display_full_path']) && ($_REQUEST['display_full_path'] == 'Y')) {
	define('DISPLAY_FULL_PATHS', true);
	Registry::set('config.full_host_name', (defined('HTTPS') ? 'https://' . Registry::get('config.https_host') : 'http://' . Registry::get('config.http_host')));
} else {
	Registry::set('config.full_host_name', '');
}

// select the skin to display
fn_init_skin($_REQUEST);

// initialize templater
fn_init_templater();

if (!defined('NO_SESSION')) {
	// Get descriptions for company country and state
	if (Registry::get('settings.Company.company_country')) {
		//Registry::set('settings.Company.company_country_descr', fn_get_country_name(Registry::get('settings.Company.company_country')));
		Registry::set('settings.Company.company_country_descr','India' );
	}
	if (Registry::get('settings.Company.company_state')) {
		//Registry::set('settings.Company.company_state_descr', fn_get_state_name(Registry::get('settings.Company.company_state'), Registry::get('settings.Company.company_country')));
		Registry::set('settings.Company.company_state_descr', 'Gujarat');
	}

	// Unset notification message by its id
	if (!empty($_REQUEST['close_notification'])) {
		unset($_SESSION['notifications'][$_REQUEST['close_notification']]);
		exit();
	}
}

// Include user information
fn_init_user();

// Third-level (a) cache: different for dispatch-user-language-currency
define('CACHE_LEVEL_USER', AREA . '_' . $_SERVER['REQUEST_METHOD'] . '_' . str_replace('.', '_', $_REQUEST['dispatch']) . '.' . (!empty($_SESSION['auth']['usergroup_ids']) ? implode('_', $_SESSION['auth']['usergroup_ids']) : '') . '.' . (defined('CART_LOCALIZATION') ? (CART_LOCALIZATION . '_') : '') . CART_LANGUAGE . '.' . CART_SECONDARY_CURRENCY);

// Third-level (b) cache: different for user(logged in/not)-usergroup-language-currency
define('CACHE_LEVEL_LOCALE_AUTH', AREA . '_' . $_SERVER['REQUEST_METHOD'] . '_' . (!empty($_SESSION['auth']['user_id']) ? 1 : 0) . '.' . (!empty($_SESSION['auth']['usergroup_ids']) ? implode('_', $_SESSION['auth']['usergroup_ids']) : '') . (defined('CART_LOCALIZATION') ? (CART_LOCALIZATION . '_') : '') . CART_LANGUAGE . '.' . CART_SECONDARY_CURRENCY);

// Set root template
Registry::set('root_template', 'index.tpl');

if (defined('SKINS_PANEL')) {
	Registry::get('view')->assign('demo_skin', Registry::get('demo_skin'));
}

// URL's assignments
Registry::set('config.current_url', Registry::get('config.' . ACCOUNT_TYPE . '_index') . ((!empty($_SERVER['QUERY_STRING'])) ? '?' . $_SERVER['QUERY_STRING'] : ''));

Registry::get('view')->assign('controller', CONTROLLER);
Registry::get('view')->assign('mode', MODE);
Registry::get('view')->assign('action', ACTION);
Registry::get('view')->assign('demo_username', Registry::get('config.demo_username'));
Registry::get('view')->assign('demo_password', Registry::get('config.demo_password'));
Registry::get('view')->assign('settings', Registry::get('settings'));
Registry::get('view')->assign('addons', Registry::get('addons'));
Registry::get('view')->assign('config', Registry::get('config'));
Registry::get('view')->assign('_REQUEST', $_REQUEST); // we need escape the request array too (access via $smarty.request in template)
Registry::get('view')->assign('SESS_ID', Session::get_id());

// Mail template assignments
Registry::get('view_mail')->assign('addons', Registry::get('addons'));
Registry::get('view_mail')->assign('settings', Registry::get('settings'));
Registry::get('view_mail')->assign('config', Registry::get('config'));

// init content search
fn_init_search();

// set cookie for akamai
fn_set_cookie_for_akamai();
if(Registry::get('config.checkout_logging')){
    fn_log_site_request();
}
?>
