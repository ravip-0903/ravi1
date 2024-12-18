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


if ( !defined('AREA') ) { die('Access denied'); }

/**
 * Init mail engine
 *
 * @return boolean always true
 */
function fn_init_mailer()
{
	if (defined('MAILER_STARTED')) {
		
		$mailer = & Registry::get('mailer');
		$mailer->ClearReplyTos();
		$mailer->ClearAttachments();
		$mailer->Sender = '';
		
		return true;
	}

	$mailer_settings = fn_get_settings('Emails');

	if (!include(DIR_CORE . 'class.mailer.php')) {
		fn_error(debug_backtrace(), "Can't find Mail class", false);
	}

	$mailer = new Mailer();
	$mailer->LE = (defined('IS_WINDOWS')) ? "\r\n" : "\n";
	$mailer->PluginDir = DIR_LIB . 'phpmailer/';

	if ($mailer_settings['mailer_send_method'] == 'smtp') {
		$mailer->IsSMTP();
		$mailer->SMTPAuth = ($mailer_settings['mailer_smtp_auth'] == 'Y') ? true : false;
		$mailer->Host = $mailer_settings['mailer_smtp_host'];
		$mailer->Username = $mailer_settings['mailer_smtp_username'];
		$mailer->Password = $mailer_settings['mailer_smtp_password'];

	} elseif ($mailer_settings['mailer_send_method'] == 'sendmail') {
		$mailer->IsSendmail();
		$mailer->Sendmail = $mailer_settings['mailer_sendmail_path'];

	} else {
		$mailer->IsMail();
	}

	Registry::set('mailer', $mailer);

	define('MAILER_STARTED', true);

	return true;
}

/**
 * Init template engine
 *
 * @return boolean always true
 */
function fn_init_templater()
{
	if (defined('TEMPLATER_STARTED')) {
		return true;
	}

	require(DIR_CORE . 'class.templater.php');

	//
	// Template objects for processing html templates
	//
	$view = new Templater();
	$view_mail = new Templater();

	fn_set_hook('init_templater', $view, $view_mail);

	$view->register_prefilter(array(&$view, 'prefilter_hook'));
	$view_mail->register_prefilter(array(&$view_mail, 'prefilter_hook'));
	if (AREA == 'A' && !empty($_SESSION['auth']['user_id'])) {
		$view->register_prefilter(array(&$view, 'prefilter_form_tooltip'));
	}
	
	if (!Registry::get('config.tweaks.allow_php_in_templates')) {
		$view->register_prefilter(array(&$view, 'prefilter_security_exec'));
	}

	if (Registry::get('settings.customization_mode') == 'Y' && AREA == 'C') {
		$view->register_prefilter(array(&$view, 'prefilter_template_wrapper'));
		$view->register_outputfilter(array(&$view, 'outputfilter_template_ids'));
		$view->customization = true;
	} else {

		// Inline prefilter
		if (Registry::get('config.tweaks.inline_compilation') == true) {
			$view->register_prefilter(array(&$view, 'prefilter_inline'));
		}
	}

	if (Registry::get('config.tweaks.anti_csfr') == true) {
		$view->register_prefilter(array(&$view, 'prefilter_security_hash'));
	}

	
	// Output bufferring postfilter
	$view->register_prefilter(array(&$view, 'prefilter_output_buffering'));

	// Translation postfilter
	$view->register_postfilter(array(&$view, 'postfilter_translation'));

	if (Registry::get('settings.translation_mode') == 'Y') {
		$view->register_outputfilter(array(&$view, 'outputfilter_translate_wrapper'));
	}

	//
	// Store all compiled templates to the single directory
	//
	$view->use_sub_dirs = false;
	$view->compile_check = (Registry::get('settings.store_optimization') == 'dev')? true : false;


	if (Registry::get('settings.General.debugging_console') == 'Y') {

		if (empty($_SESSION['debugging_console']) && !empty($_SESSION['auth']['user_id']))	{
			$user_type = db_get_field("SELECT user_type FROM ?:users WHERE user_id = ?i", $_SESSION['auth']['user_id']);
			if ($user_type == 'A') {
				$_SESSION['debugging_console'] = true;
			}
		}

		if (isset($_SESSION['debugging_console']) && $_SESSION['debugging_console'] == true) {
			error_reporting(0);
			$view->debugging = true;
		}
	}

	$skin_path = DIR_SKINS . Registry::get('config.skin_name');
	$area = AREA_NAME;
	
	fn_set_hook('get_skin_path', $area, $skin_path);
	
	$view->template_dir = $skin_path . '/' . AREA_NAME;
	$view->config_dir = $skin_path . '/' . AREA_NAME;
	$view->secure_dir = $skin_path . '/' . AREA_NAME;
	$view->assign('images_dir', Registry::get('config.full_host_name') . Registry::get('config.current_path') . (str_replace(DIR_ROOT, '', $skin_path)) . '/' . AREA_NAME . "/images");
	
 	$view->compile_dir = DIR_COMPILED . AREA_NAME . (defined('SKINS_PANEL') || PRODUCT_TYPE == 'MULTISHOP' ? '/' . Registry::get('config.skin_name') : '');
	$view->cache_dir = DIR_CACHE . (PRODUCT_TYPE == 'MULTISHOP' && defined('COMPANY_ID') ? COMPANY_ID : ''); //FIXME: move this code to other place
	$view->assign('skin_area', AREA_NAME);
	
	// Get manifest
	$manifest = fn_get_manifest(AREA_NAME);
	$view->assign('manifest', $manifest);
	
	// Mail templates should be taken from the customer skin
	if (AREA != 'C') {
		$manifest = fn_get_manifest('customer');
	}
	$view_mail->assign('manifest', $manifest);
	$view_mail->template_dir = DIR_SKINS . Registry::get('settings.skin_name_customer') . '/mail';
	$view_mail->config_dir = DIR_SKINS . Registry::get('settings.skin_name_customer') . '/mail';
	$view_mail->secure_dir = DIR_SKINS . Registry::get('settings.skin_name_customer') . '/mail';
	$view_mail->assign('images_dir', Registry::get('config.full_host_name') . Registry::get('config.current_path') . '/skins/' . Registry::get('settings.skin_name_customer') . '/mail/images');
	$view_mail->compile_dir = DIR_COMPILED . 'mail';
	$view_mail->assign('skin_area', 'mail');

	if (!is_dir($view->compile_dir)) {
		fn_mkdir($view->compile_dir);
	}

	if (!is_dir($view->cache_dir)) {
		fn_mkdir($view->cache_dir);
	}


	if (!is_dir($view_mail->compile_dir) ) {
		fn_mkdir($view_mail->compile_dir);
	}

	if (!is_writable($view->compile_dir) || !is_dir($view->compile_dir) ) {
		fn_error(debug_backtrace(), "Can't write template cache in the directory: <b>" . $view->compile_dir . '</b>.<br>Please check if it exists, and has writable permissions.', false);
	}

	$view->assign('ldelim','{');
	$view->assign('rdelim','}');
	
	$avail_languages = array();
	foreach (Registry::get('languages') as $k => $v) {
		if ($v['status'] == 'D') {
			continue;
		}

		$avail_languages[$k] = $v;
	}
	$view->assign('languages', $avail_languages);
	$view->setLanguage(CART_LANGUAGE);
	$view_mail->setLanguage(CART_LANGUAGE);
	$view->assign('localizations', fn_get_localizations(CART_LANGUAGE , true));
	if (defined('CART_LOCALIZATION')) {
		$view->assign('localization', fn_get_localization_data(CART_LOCALIZATION));
	}
	// [andyye]
	//$view->assign('currencies', Registry::get('currencies'), false);
	$currencies = Registry::get('currencies');
	foreach($currencies as &$currency) {
		$currency['symbol'] = fn_sdeep_image_currency_symbol($currency['symbol']);
	}
	$view->assign('currencies', $currencies, false);
	// [/andyye]

	$view->assign('primary_currency', CART_PRIMARY_CURRENCY, false);
	$view->assign('secondary_currency', CART_SECONDARY_CURRENCY, false);
	$view_mail->assign('currencies', Registry::get('currencies'), false);
	$view_mail->assign('primary_currency', CART_PRIMARY_CURRENCY, false);
	$view_mail->assign('secondary_currency', CART_SECONDARY_CURRENCY, false);

	$view->assign('s_companies', Registry::get('s_companies'));
	$view->assign('s_company', defined('COMPANY_ID') ? COMPANY_ID : 'all');

	$view_mail->assign('s_companies', Registry::get('s_companies'));
	$view_mail->assign('s_company', defined('COMPANY_ID') ? COMPANY_ID : 'all');

	Registry::set('view', $view);
	Registry::set('view_mail', $view_mail);

	define('TEMPLATER_STARTED', true);

	return true;
}

/**
 * Init crypt engine
 *
 * @return boolean always true
 */
function fn_init_crypt()
{
	if (!defined('CRYPT_STARTED')) {
		if (!include(DIR_LIB . 'crypt/Blowfish.php')) {
			fn_error(debug_backtrace(), "Can't connect Blowfish crypt class", false);
		}

		$crypt = new Crypt_Blowfish(Registry::get('config.crypt_key'));
		Registry::set('crypt', $crypt);

		fn_define('CRYPT_STARTED', true);
	}

	return true;
}

/**
 * Init ajax engine
 *
 * @return boolean true if current request is ajax, false - otherwise
 */
function fn_init_ajax()
{
	if (defined('AJAX_REQUEST')) {
		return true;
	}

	if (empty($_REQUEST['ajax_custom']) && (!empty($_REQUEST['is_ajax']) || (!empty($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false))) {
		require(DIR_CORE . 'class.ajax.php');
		$ajax = new Ajax();
		Registry::set('ajax', $ajax);
		fn_define('AJAX_REQUEST', true);
		return true;
	}

	return false;
}

/**
 * Init yaml engine
 *
 * @return boolean always true
 */
function fn_init_yaml()
{
	if (!defined('YAML_STARTED')) {
		require(DIR_LIB . 'spyc/spyc.php');
		fn_define('YAML_STARTED', true);
	}

	return true;
}

/**
 * Init pdf engine
 *
 * @return boolean always true
 */
function fn_init_pdf()
{
	// pdf can't be generated correctly without DOM extension (DOMDocument class)
	if (!class_exists('DOMDocument')) {
		$msg = (AREA == 'A') ? fn_get_lang_var('error_generate_pdf_admin') : fn_get_lang_var('error_generate_pdf_customer');
		fn_set_notification('E', fn_get_lang_var('error'), $msg);
		return false;
	}

	if (defined('PDF_STARTED')) {
		return true;
	}

	define('CACHE_DIR', DIR_CACHE . 'pdf/cache');
	define('OUTPUT_FILE_DIRECTORY', DIR_CACHE . 'pdf/out');
	define('WRITER_TEMPDIR', DIR_CACHE . 'pdf/temp');

	if (!is_dir('CACHE_DIR')) {
		fn_mkdir(CACHE_DIR);
	}

	if (!is_dir('OUTPUT_FILE_DIRECTORY')) {
		fn_mkdir(OUTPUT_FILE_DIRECTORY);
	}

	if (!is_dir('WRITER_TEMPDIR')) {
		fn_mkdir(WRITER_TEMPDIR);
	}

	require(DIR_CORE . 'class.pdf_converter.php');
	parse_config_file(HTML2PS_DIR . 'html2ps.config');

	fn_define('PDF_STARTED', true);
	
	return true;
}

/**
 * Init diff engine
 *
 * @return boolean always true
 */
function fn_init_diff()
{
	if (!defined('DIFF_STARTED')) {
		include(DIR_LIB . 'pear/PEAR.php');
		include(DIR_LIB . 'Text/Diff.php');
		include(DIR_LIB . 'Text/Diff/Renderer.php');
		include(DIR_LIB . 'Text/Diff/Renderer/inline.php');

		fn_define('DIFF_STARTED', true);
	}

	return true;
}

/**
 * Init languages
 *
 * @param array $params request parameters
 * @return boolean always true
 */
function fn_init_language($params)
{
	$join_cond = '';
	$condition = (AREA == 'A') ? '' : ((isset($_SESSION['auth']['area']) && ($_SESSION['auth']['area'] == 'A')) ? '' : "WHERE ?:languages.status = 'A'");
	$order_by = '';
	if ((AREA == 'C') && defined('CART_LOCALIZATION')) {
		$join_cond = "LEFT JOIN ?:localization_elements ON ?:localization_elements.element = ?:languages.lang_code AND ?:localization_elements.element_type = 'L'";
		$separator = ($condition == '') ? 'WHERE' : 'AND';      
		$condition .= db_quote(" $separator ?:localization_elements.localization_id = ?i", CART_LOCALIZATION);
		$order_by = "ORDER BY ?:localization_elements.position ASC";
	}
	$languages = db_get_hash_array("SELECT ?:languages.* FROM ?:languages $join_cond ?p $order_by", 'lang_code', $condition);
	$avail_languages = array();

	foreach ($languages as $k => $v) {
		if ($v['status'] == 'D') {
			continue;
		}

		$avail_languages[$k] = $v;
	}

	if (!empty($params['sl']) && !empty($avail_languages[$params['sl']])) {
		fn_define('CART_LANGUAGE', $params['sl']);
	} elseif (!fn_get_session_data('cart_language' . AREA) && $_lc = fn_get_browser_language($avail_languages)) {
		fn_define('CART_LANGUAGE', $_lc);
	} elseif (!fn_get_session_data('cart_language' . AREA) && !empty($avail_languages[Registry::get('settings.Appearance.' . AREA_NAME . '_default_language')])) {
		fn_define('CART_LANGUAGE', Registry::get('settings.Appearance.' . AREA_NAME . '_default_language'));

	} elseif (($_c = fn_get_session_data('cart_language' . AREA)) && !empty($avail_languages[$_c])) {
		fn_define('CART_LANGUAGE', $_c);

	} else {
		reset($avail_languages);
		fn_define('CART_LANGUAGE', key($avail_languages));
	}

	// For administrative area, set description language
	if (!empty($params['descr_sl']) && !empty($avail_languages[$params['descr_sl']])) {
		fn_define('DESCR_SL', $params['descr_sl']);
		fn_set_session_data('descr_sl', $params['descr_sl'], COOKIE_ALIVE_TIME);
	} elseif (($d = fn_get_session_data('descr_sl')) && !empty($avail_languages[$d])) {
		fn_define('DESCR_SL', $d);
	} else {
		fn_define('DESCR_SL', CART_LANGUAGE);
	}


	if (CART_LANGUAGE != fn_get_session_data('cart_language' . AREA)) {
		fn_set_session_data('cart_language' . AREA, CART_LANGUAGE, COOKIE_ALIVE_TIME);
	}

	fn_define('CHARSET', 'utf-8');
	header("Content-Type: text/html; charset=" . CHARSET);

	Registry::set('languages', $languages);

	return true;
}

/**
 * Init company
 *
 * @param array $params request parameters
 * @return boolean always true
 */
function fn_init_company($params)
{
	if (AREA == 'A' && !empty($_SESSION['auth']['company_id']) && (PRODUCT_TYPE == 'MULTIVENDOR' || PRODUCT_TYPE == 'MULTISHOP')) {
		fn_define('COMPANY_ID', $_SESSION['auth']['company_id']);

		$companies = db_get_hash_array("SELECT ?:companies.* FROM ?:companies WHERE company_id = ?i AND status IN ('A', 'P', 'S','R','B','M','E','C','H','T','W','X','Y','Z','F')",  'company_id', COMPANY_ID);
		if (empty($companies)) {

			$_SESSION['auth'] = array();
			fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('access_denied'));
			$suffix = (strpos($_SERVER['HTTP_REFERER'], '?') !== false ? '&' : '?') . 'login_type=login' . (!empty($_REQUEST['return_url']) ? '&return_url=' . urlencode($_REQUEST['return_url']) : '');
			fn_redirect("$_SERVER[HTTP_REFERER]$suffix");
		}
		
	} else {
		
		$companies = array(
			'all' => array(
				'company_id' => 'all',
				'company' => fn_get_lang_var('all_vendors'),
			),
			'0' => array(
				'company_id' => '0',
				'company' => Registry::get('settings.Company.company_name'),
				'status' => 'A'
			),
		);
		
		if (defined('SELECTED_COMPANY_ID') && SELECTED_COMPANY_ID != 'all') {
			// trying to select company data
			if (SELECTED_COMPANY_ID) {
				$_companies = db_get_hash_array("SELECT ?:companies.* FROM ?:companies WHERE company_id = ?i", 'company_id', SELECTED_COMPANY_ID);
				if (!empty($_companies[SELECTED_COMPANY_ID])) {
					$companies = $companies + $_companies;
				}
			}
			
			// For administrative area, set selected company
			if (!empty($companies[SELECTED_COMPANY_ID])) {
				fn_define('COMPANY_ID', SELECTED_COMPANY_ID);
				fn_set_session_data('company_id', COMPANY_ID, COOKIE_ALIVE_TIME);
			} else {
				fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('company_not_found'));

				$dispatch = $params['dispatch'];
				unset($params['s_company']);
				unset($params['dispatch']);

				$_c = fn_get_session_data('company_id');
				$_companies = db_get_hash_array("SELECT ?:companies.* FROM ?:companies WHERE company_id = ?i", 'company_id', $_c);
				if (empty($_companies[$_c])) {
					fn_delete_session_data('company_id');
					$_c = 'all';
				}
				$params['s_company'] = $_c;

				fn_redirect(fn_url("$dispatch?" . fn_build_query($params)));
			}
		}
	}
	
	Registry::set('s_companies', $companies);
	
	fn_set_hook('init_companies', $params, $companies);

	return true;
}

function fn_init_selected_company_id($params)
{
	if ((PRODUCT_TYPE == 'MULTIVENDOR' && AREA == 'A') || PRODUCT_TYPE == 'MULTISHOP') {
		if (!empty($_SESSION['auth']['company_id'])) {
			fn_define('SELECTED_COMPANY_ID', $_SESSION['auth']['company_id']);
		} else {
			// Set selected company
			$_c = fn_get_session_data('company_id');
			if (isset($params['s_company']) && $params['s_company'] !== false) {
				fn_define('SELECTED_COMPANY_ID', $params['s_company']);
			} elseif ($_c !== false) {
				fn_define('SELECTED_COMPANY_ID', $_c);
			}
		}

		if (defined('SELECTED_COMPANY_ID') && SELECTED_COMPANY_ID == 'all') {
			fn_set_session_data('company_id', SELECTED_COMPANY_ID, COOKIE_ALIVE_TIME);
		}
	}
	
	$var_path = '';
	
	fn_set_hook('init_selected_company', $params, $var_path);
	
	fn_define('VAR_PATH', $var_path);
}

/**
 * Init currencies
 *
 * @param array $params request parameters
 * @return boolean always true
 */
function fn_init_currency($params)
{
	$cond = $join = $order_by = '';
	if ((AREA == 'C') && defined('CART_LOCALIZATION')) {
		$join = " LEFT JOIN ?:localization_elements as c ON c.element = a.currency_code AND c.element_type = 'M'";
		$cond = db_quote('AND c.localization_id = ?i', CART_LOCALIZATION);
		$order_by = "ORDER BY c.position ASC";
	}
	if (!$order_by) {
		$order_by = 'ORDER BY a.position';	
	}
	$currencies = db_get_hash_array("SELECT a.*, b.description FROM ?:currencies as a LEFT JOIN ?:currency_descriptions as b ON a.currency_code = b.currency_code AND lang_code = ?s $join WHERE status = 'A' ?p $order_by", 'currency_code', CART_LANGUAGE, $cond);

	if (!empty($params['currency']) && !empty($currencies[$params['currency']])) {
		$secondary_currency = $params['currency'];
	} elseif (($c = fn_get_session_data('secondary_currency' . AREA)) && !empty($currencies[$c])) {
		$secondary_currency = $c;
	} else {
		foreach ($currencies as $v) {
			if ($v['is_primary'] == 'Y') {
				$secondary_currency = $v['currency_code'];
				break;
			}
		}
	}

	if (empty($secondary_currency)) {
		reset($currencies);
		$secondary_currency = key($currencies);
	}

	if ($secondary_currency != fn_get_session_data('secondary_currency' . AREA)) {
		fn_set_session_data('secondary_currency'.AREA, $secondary_currency, COOKIE_ALIVE_TIME);
	}

	$primary_currency = '';

	foreach ($currencies as $v) {
		if ($v['is_primary'] == 'Y') {
			$primary_currency = $v['currency_code'];
			break;
		}
	}

	if (empty($primary_currency)) {
		reset($currencies);
		$first_currency = current($currencies);
		$primary_currency = $first_currency['currency_code'];
	}

	define('CART_PRIMARY_CURRENCY', $primary_currency);
	define('CART_SECONDARY_CURRENCY', $secondary_currency);

	Registry::set('currencies', $currencies);

	return true;
}

/**
 * Init skin
 *
 * @param array $params request parameters
 * @return boolean always true
 */
function fn_init_skin($params)
{
	if (defined('DEVELOPMENT')) {
		foreach (Registry::get('config.dev_skins') as $k => $v) {
			Registry::set('settings.skin_name_' . $k, $v);
		}
	}
	
	$skin_path = DIR_SKINS . Registry::get('settings.skin_name_' . AREA_NAME);
	$area = AREA_NAME;
	
	fn_set_hook('get_skin_path', $area, $skin_path);
	
	if ((Registry::get('settings.skin_name_' . AREA_NAME) == '' || !is_dir($skin_path)) && !defined('SKINS_PANEL')) {
		$all = fn_get_dir_contents(DIR_SKINS, true);
		$skin_found = false;
		foreach ($all as $sk) {
			if (is_file(DIR_SKINS . $sk . '/' . AREA_NAME . '/index.tpl')) {
				Registry::set('settings.skin_name_' . AREA_NAME, basename($sk));
				$skin_found = true;
				break;
			}
		}

		if ($skin_found == false) {
			die("No skins found");
		} else {
			echo <<<EOT
				<div style="background: #ff0000; color: #ffffff; font-weight: bold;" align="center">SELECTED SKIN NOT FOUND. REPLACED BY FIRST FOUND</div>
EOT;
		}
	}

	// Allow user to change the skin during the current session
	if (defined('SKINS_PANEL')) {
		$demo_skin = fn_get_session_data('demo_skin');

		if (!empty($params['demo_skin'][AREA])) {
			$tmp_skin = basename($params['demo_skin'][AREA]);

			if (is_dir(DIR_SKINS . $tmp_skin)) {
				Registry::set('settings.skin_name_' . AREA_NAME, $tmp_skin);
				$demo_skin[AREA] = $tmp_skin;
			} else {
				Registry::set('settings.skin_name_' . AREA_NAME, $demo_skin[AREA]);
			}
		} elseif (empty($demo_skin[AREA])) {
			$demo_skin[AREA] = 'basic';
		}

		Registry::set('settings.skin_name_' . AREA_NAME, $demo_skin[AREA]);
		fn_set_session_data('demo_skin', $demo_skin);
		
		$skin_path = DIR_SKINS . Registry::get('settings.skin_name_' . AREA_NAME);
		$area = AREA_NAME;

		fn_set_hook('get_skin_path', $area, $skin_path);

		Registry::set('demo_skin', array(
			'selected' => $demo_skin,
			'available_skins' => fn_get_available_skins(AREA_NAME)
		));
	}

	$skin_name = Registry::get('settings.skin_name_' . AREA_NAME);
	Registry::set('config.skin_name', $skin_name);
	Registry::set('config.skin_path', Registry::get('config.full_host_name') . Registry::get('config.current_path') . str_replace(DIR_ROOT, '', $skin_path . '/' . AREA_NAME));
	Registry::set('config.no_image_path', Registry::get('config.full_host_name') . Registry::get('config.images_path') . 'no_image.gif');

	return true;
}

/**
 * Init user
 *
 * @return boolean always true
 */
function fn_init_user()
{
	if(isset($_SESSION['auth']['user_id']) && !empty($_SESSION['auth']['user_id']) && $_SESSION['auth']['user_id'] !=0 && !isset($_COOKIE['scun']))
	{
		if(CONTROLLER == 'profiles' || CONTROLLER == 'orders' || CONTROLLER == 'reward_points' || CONTROLLER == 'rma' || CONTROLLER == 'wishlist' || CONTROLLER == 'write_to_us'|| (CONTROLLER == 'auth' && MODE == 'login_form') )
		{
                    fn_set_scun_cookie();
		}
	}
	//Code added by Rahul to handle stay login starts here	
	if(Registry::get('config.stay_signin') && $_COOKIE['scun'] !='')
	{
		if(CONTROLLER == 'profiles' || CONTROLLER == 'orders' || CONTROLLER == 'reward_points' || CONTROLLER == 'rma' || CONTROLLER == 'wishlist' || (CONTROLLER == 'checkout' && MODE != 'cart') || (CONTROLLER == 'auth' && MODE == 'login_form') || (CONTROLLER == 'write_to_us' && MODE == 'write'))
		{
			if(isset($_COOKIE['sidk']) && (empty($_SESSION['auth']['user_id']) || $_SESSION['auth']['user_id'] ==0))
			{
				$cookie_stay_login = trim($_COOKIE['sidk']);
				$logged_on_session = db_get_row("select email,user_id,phone,password,user_auth_code from cscart_users where user_verification_code='".$cookie_stay_login."'");
				if($logged_on_session)
				{
					$unique_key = fn_generate_unique_logged_in_key($logged_on_session);
					if($unique_key == $cookie_stay_login)
					{
						$_SESSION['auth']['user_id'] = $logged_on_session['user_id'];
					}
				}
			}
		}
	}
	//Code added by Rahul to handle stay concept ends here
	if (!empty($_SESSION['auth']['user_id']))	{
		$user_info = fn_get_user_short_info($_SESSION['auth']['user_id']);
		if (empty($user_info)) { // user does not exist in the database, but exists in session
			$_SESSION['auth'] = array();
		} else {
			$_SESSION['auth']['usergroup_ids'] = fn_define_usergroups(array('user_id' => $_SESSION['auth']['user_id'], 'user_type' => $user_info['user_type']));
		}
	}

	$first_init = false;
	if (empty($_SESSION['auth'])) {

		$udata = array();
		$user_id = fn_get_session_data(AREA_NAME . '_user_id');

		if ($user_id) {
			fn_define('LOGGED_VIA_COOKIE', true);
		}

		fn_login_user($user_id);

		if (!defined('NO_SESSION')) {
			$_SESSION['cart'] = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
		}

		if ((defined('LOGGED_VIA_COOKIE') && !empty($_SESSION['auth']['user_id'])) || ($cu_id = fn_get_session_data('cu_id'))) {
			$first_init = true;
			if (!empty($cu_id)) {
				fn_define('COOKIE_CART' , true);
			}

			// Cleanup cached shipping rates

			unset($_SESSION['shipping_rates']);

			$_utype = empty($_SESSION['auth']['user_id']) ? 'U' : 'R';
			$_uid = empty($_SESSION['auth']['user_id']) ? $cu_id : $_SESSION['auth']['user_id'];
			fn_extract_cart_content($_SESSION['cart'], $_uid , 'C' , $_utype);
			fn_save_cart_content($_SESSION['cart'] , $_uid , 'C' , $_utype);
			if (!empty($_SESSION['auth']['user_id'])) {
				$_SESSION['cart']['user_data'] = fn_get_user_info($_SESSION['auth']['user_id']);
				$user_info = fn_get_user_short_info($_SESSION['auth']['user_id']);
			}
                        //session cart gets new products from db, so update cart cookie.
                        update_cart_cookie();
		}
	}

	/*Commented by chandan to remove the functionality of deleting the cart of unregistered customer every time.*/
	/*if (TIME > Registry::get('settings.cart_products_next_check')) {
		fn_define('CART_PRODUCTS_CHECK_PERIOD' , SECONDS_IN_HOUR * 12);
		fn_define('CART_PRODUCTS_DELETE_TIME' , TIME - SECONDS_IN_DAY * 30);echo CART_PRODUCTS_DELETE_TIME;
		db_query("DELETE FROM ?:user_session_products WHERE user_type = 'U' AND timestamp < ?i", CART_PRODUCTS_DELETE_TIME);
		fn_set_setting_value('cart_products_next_check', TIME + CART_PRODUCTS_CHECK_PERIOD);
	}*/
	/*Commented by chandan to remove the functionality of deleting the cart of unregistered customer every time.*/
	// If administrative account has usergroup, it means the access restrictions are in action
	
        $not_restricted_admin = Registry::get('config.not_restricted_admin');
        
        if (AREA == 'A' && (!empty($_SESSION['auth']['usergroup_ids']) || (!empty($_SESSION['auth']['company_id']) && $_SESSION['auth']['is_root'] != 'Y'))) {
            
              if(count(array_intersect((array)$_SESSION['auth']['usergroup_ids'],$not_restricted_admin))>0){
                 
                  
                }else{ 
                    
                    fn_define('RESTRICTED_ADMIN', true);
                 
                }
	}
	if (!empty($user_info) && $user_info['user_type'] == 'A' && (empty($user_info['company_id']) || defined('COMPANY_ID') && COMPANY_ID == $user_info['company_id'])) {
		if (Registry::get('settings.translation_mode') == 'Y') {
			fn_define('TRANSLATION_MODE', true);
		}
		
		if (Registry::get('settings.customization_mode') == 'Y') {
			if (AREA != 'A') {
				fn_define('PARSE_ALL', true);
			}
			fn_define('CUSTOMIZATION_MODE', true);
		}
	}

	fn_set_hook('user_init', $_SESSION['auth'], $user_info, $first_init);

	Registry::set('user_info', $user_info);
	Registry::get('view')->assign('auth', $_SESSION['auth']);
	Registry::get('view')->assign('user_info', $user_info);

        $keys_to_log = array('firstname', 'lastname', 'user_type', 'user_id', 'company_id');
        $log_metrics = array_intersect_key($user_info, array_flip($keys_to_log));
	LogMetric::dump_log(array_keys($log_metrics), array_values($log_metrics), LogConstants::LOG_IDENTIFIED_USER);
	return true;
}
/**
 * Init localizations
 *
 * @param array $params request parameters
 * @return boolean true if localizations exists, false otherwise
 */
function fn_init_localization($params)
{
	$locs = db_get_hash_array("SELECT localization_id, custom_weight_settings, weight_symbol, weight_unit FROM ?:localizations WHERE status = 'A'", 'localization_id');

	if (empty($locs)) {
		return false;
	}

	if (!empty($_REQUEST['lc']) && !empty($locs[$_REQUEST['lc']])) {
		$cart_localization = $_REQUEST['lc'];

	} elseif (($l = fn_get_session_data('cart_localization')) && !empty($locs[$l])) {
		$cart_localization = $l;

	} else {
		$_ip = fn_get_ip(true);
		$_country = fn_get_country_by_ip($_ip['host']);
		$_lngs = db_get_hash_single_array("SELECT lang_code, 1 as 'l' FROM ?:languages WHERE status = 'A'", array('lang_code', 'l'));
		$_language = fn_get_browser_language($_lngs);

		$cart_localization = db_get_field("SELECT localization_id, COUNT(localization_id) as c FROM ?:localization_elements WHERE (element = ?s AND element_type = 'C') OR (element = ?s AND element_type = 'L') GROUP BY localization_id ORDER BY c DESC LIMIT 1", $_country, $_language);

		if (empty($cart_localization) || empty($locs[$cart_localization])) {
			$cart_localization = db_get_field("SELECT localization_id FROM ?:localizations WHERE status = 'A' AND is_default = 'Y'");
		}
	}

	if (empty($cart_localization)) {
		reset($locs);
		$cart_localization = key($locs);
	}

	if ($cart_localization != fn_get_session_data('cart_localization')) {
		fn_set_session_data('cart_localization', $cart_localization, COOKIE_ALIVE_TIME);
	}

	if ($locs[$cart_localization]['custom_weight_settings'] == 'Y') {
		Registry::set('config.localization.weight_symbol', $locs[$cart_localization]['weight_symbol']);
		Registry::set('config.localization.weight_unit', $locs[$cart_localization]['weight_unit']);
	}

	fn_define('CART_LOCALIZATION', $cart_localization);

	return true;
}
/**
 * Detect user agent
 *
 * @return boolean true always
 */
function fn_init_ua() 
{
	static $crawlers = array(
		'google', 'bot', 'yahoo',
		'spider', 'archiver', 'curl',
		'python', 'nambu', 'twitt',
		'perl', 'sphere', 'PEAR',
		'java', 'wordpress', 'radian',
		'crawl', 'yandex', 'eventbox',
		'monitor', 'mechanize', 'facebookexternal'
	);
             
	$http_ua = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : '';
	if (strpos($http_ua, 'shiretoko') !== false || strpos($http_ua, 'firefox') !== false) {
		$ua = 'firefox';
	} elseif (strpos($http_ua, 'chrome') !== false) {
		$ua = 'chrome';
	} elseif (strpos($http_ua, 'safari') !== false) {
		$ua = 'safari';
	} elseif (strpos($http_ua, 'opera') !== false) {
		$ua = 'opera';
	} elseif (strpos($http_ua, 'msie') !== false) {
		$ua = 'ie';
	} elseif (empty($http_ua) || preg_match('/(' . implode('|', $crawlers) . ')/', $http_ua, $m)) {
		$ua = 'crawler';
		if (!empty($m)) {
			fn_define('CRAWLER', $m[1]);
		}
		if (!defined('SKIP_SESSION_VALIDATION')) {
			fn_define('NO_SESSION', true); // do not start session for crawler
		}
	} else {
		$ua = 'unknown';
	}

	fn_define('USER_AGENT', $ua);

	return true;
}
// [andyye]
function fn_sdeep_image_currency_symbol($symb) {return $symb;
	/*$addon = Registry::get('addons.sdeep');
	if($addon) {
		$icon_url = db_get_field("SELECT icon_url FROM ?:sdeep_lang_icons WHERE pattern=?s", $symb);
		if($icon_url) {
			return '<img src="'.$icon_url.'"/>';
		}
	}
	return $symb;*/
}
// [/andyye]

// to set cookie for akamai to cache pages
function fn_set_cookie_for_akamai(){
	if(Registry::get('config.show_cookie_akamai'))
	{
        if(CONTROLLER == '_no_page'){
            return;
        }
        $domain = $_SERVER['HTTP_HOST'] == 'localhost' ? '' : '.shopclues.com';
        $setSccache = false;
        if(isset($_SESSION['cart']['products']) && count($_SESSION['cart']['products']) >= 1){
            $setSccache = true;
        }
        else if(isset($_SESSION['auth']['user_id']) && $_SESSION['auth']['user_id'] != 0){
            $setSccache = true;
        }


        if($setSccache){
                setcookie("sccache",true,time()+1440,"/",$domain);
        }
        else if(isset($_COOKIE['sccache'])){
                setcookie ("sccache", false, time() - 3600, "/", $domain); 
        }

        //code by ankur to set cookie for fav store
        if($_SESSION['auth']['user_id'] && AREA=='C' && !isset($_COOKIE['sclikes']))
        {
                $rand1=substr(rand(),0,6);
                $rand2=substr(rand(),0,6);
                $cookie_val=$rand1.$_SESSION['auth']['user_id'].$rand2; 
                setcookie("sclikes",$cookie_val,time()+3600*24*60,"/",$domain); 
                //setcookie("sclikes",$cookie_val,time()+3600*24*60);
        }
	}        //code end
}

function fn_set_cookie_for_utm_source($utm_source){
   
    $sql = "select utm_code from clues_tracking_code where utm_source='".strtolower($utm_source)."' and status ='A'";
    $result = db_get_field($sql);
    return htmlspecialchars($result);

}

function fn_find_categories($product_id, $utm_source){
    
    foreach ($product_id as $key=>$product_id)
    {
        $products_id[]=$product_id['product_id'];
    }
    
    for($i=0;$i<count($products_id);$i++)
        {
            $sql = "select category_id from cscart_products_categories where product_id='".$products_id[$i]."'";
            $category_id[] = db_get_row($sql);
        }
    
    for($i=0;$i<count($category_id);$i++)
        {
            $sql = "select parent_id,category_id,id_path from cscart_categories where category_id='".$category_id[$i]['category_id']."'";
            
            $parent_cat[] = db_get_row($sql);
        }  
       
        if(count($parent_cat)==1){
            $par_id= explode('/',$parent_cat[0]['id_path']);
           
                 $par_cat = $par_id[0];
                 $sql= "select category from cscart_category_descriptions where category_id='".$par_cat."'"; 
                 $par_cat=db_get_row($sql);
                 
                 $sql = "select category_replace as category from clues_tracking_category where parent_category='".$par_cat['category']."' AND utm_source='".$utm_source."'";
                 $val = db_get_row($sql);
                 $par_cat = !empty($val)? $val:$par_cat;
             } else {
                 for($i=0;$i<count($parent_cat);$i++){
                    $par_id[$i]= explode('/',$parent_cat[$i]['id_path']);            
                     }  
                   for($i=0;$i<1;$i++){
                    for($j=1;$j<count($parent_cat);$j++){
                        if($par_id[$i][$i]==$par_id[$j][$i]){
                            $par_cat = $par_id[$i][$i];
                          }
                       } 
                   }
                   if(!empty($par_cat))
                   {
                       
                     $sql= "select category from cscart_category_descriptions where category_id='".$par_cat."'"; 
                     $par_cat=db_get_row($sql);
                     
                     
                     $sql = "select category_replace as category from cscart_tracking_category where parent_category='".$par_cat['category']."' AND utm_source='".$utm_source."'";
                     $val = db_get_row($sql);
                     $par_cat = !empty($val)? $val:$par_cat;
                     
                    } else {
                       
                       $par_cat="electronics";
                   }
                  
             }
             
           if(is_array($par_cat))
              {
                    return $par_cat['category'];
               } else {
                    return $par_cat;
             }
        
}

function fn_promotion_cookie($utm_source, $promotion_id)
{
    $sql = "select utm_source,promotion_id from cscart_promotions where utm_source='".$utm_source."' and promotion_id='".$promotion_id."'";
    $value = db_get_row($sql);
    return $value;
}


function fn_utm_source_log($order_id,$utm_source,$utm_data){
    
    $data = db_get_row("select utm_source,order_id from clues_utmsource_data where order_id=".$order_id."");
    
    $current_timestamp = date(time());
    
    if(empty($data)){
    	
    	if (isset($_COOKIE['utm_campaigns']))
    	{
    		$cookie_campaign =$_COOKIE['utm_campaigns'];
    		$sql = "insert into clues_utmsource_data(order_id,utm_source,utm_code,timestamp,utm_campaign) values(".$order_id.",'".$utm_source."','".$utm_data."',".$current_timestamp.",'".$cookie_campaign."')";
    		//setcookie($cookie_campaign, "", time()-3600);
    	}
    	else
    	{
    		$sql = "insert into clues_utmsource_data(order_id,utm_source,utm_code,timestamp) values(".$order_id.",'".$utm_source."','".$utm_data."',".$current_timestamp.")";
    	}

        db_query($sql);

    }
    
}

function fn_init_sessioncart_from_cookie()
{
    if(CONTROLLER == '_no_page'){
        return;
    }
    // if cart is present in cookie but session is empty
    //if (isset($_COOKIE['sess_id1']) && empty($_SESSION['cart']['products']) && (!empty($_SESSION['auth']['user_id']) || (CONTROLLER == 'checkout' && MODE == 'cart')))
    if (isset($_COOKIE['sess_id1']))
    {
        $cookie_products = json_decode(base64_decode($_COOKIE['sess_id1']), true);
        $cookie_prod_count = count($cookie_products);
        if (count($cookie_products) > 0 && !empty($_SESSION['cart']['products'])){
            $cookie_products = array_diff($cookie_products, $_SESSION['cart']['products']);
        }
            
        if(count($cookie_products) > 0){
            fn_add_product_to_cart($cookie_products, $_SESSION['cart'], $_SESSION['auth'], false);
            update_cart_cookie();
        }	
        else if(count($cookie_products) == 0 && $cookie_prod_count!=count($_SESSION['cart']['products']) && (CONTROLLER == 'checkout' && MODE == 'cart')){
            update_cart_cookie();
        }
        //$domain = $_SERVER['HTTP_HOST'] == 'localhost' ? '' : '.shopclues.com';
        //setcookie('scisc','Y',time()+60*20,'',$domain);
    }
    else if(!isset($_COOKIE['sess_id1']) && CONTROLLER == 'checkout')
    {
    	update_cart_cookie();
    }
}

//function made by tushar to update cart cookie from session
function update_cart_cookie()
{
    if(CONTROLLER == '_no_page'){
        return;
    }
    $domain = $_SERVER['HTTP_HOST'] == 'localhost' ? '' : '.shopclues.com';

    if(count($_SESSION['cart']['products']) > 0){
        foreach($_SESSION['cart']['products'] as $key => $value)
        {
                $store_products[$key]['product_id'] = $value['product_id'];  
                $store_products[$key]['product_options'] = $value['product_options'];
                $store_products[$key]['amount'] = $value['amount'];
                $store_products[$key]['price'] = $value['price'];
                $prod_name = empty($value['name'])? $value['product_name']:$value['name'];
                $store_products[$key]['name'] = $prod_name;
        }
        $out = base64_encode(json_encode($store_products));
        setcookie('sess_id1',$out,time()+60*60*24*365,'',$domain);
        $_COOKIE['sess_id1'] = $out;
    }else{
        setcookie('sess_id1',"",time()+60*60*24*365,'',$domain);  
        $_COOKIE['sess_id1'] = '';
        setcookie('scisc','',time()+60*20,'',$domain);
    }
}


function fn_log_site_request(){        
    $log_dispatch = Registry::get('config.checkout_logging_dispatchs');
    $dispatch = CONTROLLER.'.'.MODE;

	if(in_array($dispatch, $log_dispatch)){
        $data['referer']        = $_SESSION['auth']['previous_step'];
		$others['place_order'] = 'place_order'; 
		$others['failed'] = 'order failed';
		$others['add'] = 'buy now';
		$others['complete'] = 'order success';
		$others['return'] = 'gateway response recieved';
		$others['express_checkout'] = 'express_checkout';
		if( isset($_REQUEST['edit_step'])	&& ($_SESSION['auth']['user_id'] == 0) && $_REQUEST['edit_step'] == 'step_two' ){ 
				$other = 'step_one';
		}
		elseif(isset($_REQUEST['edit_step'])){
			$other = addslashes($_REQUEST['edit_step']);
		}
		else{
			$other = '';
		}
		
		if($_SESSION['auth']['payment_failed'] == 1){
			$_SESSION['auth']['payment_failed'] = 0;
			return 0;
		}
		
		if(MODE == 'checkout' && strpos($data['referer'],'payment_notification.return') ){
			$_SESSION['auth']['payment_failed'] = 1;
			$other = $others['failed'];
		}		
		
        if( ($data['referer']  != $_SERVER['REQUEST_URI'] && !defined('AJAX_REQUEST') ) ||  ( MODE == 'add') ){
            $_SESSION['auth']['last_referer']     = $data['referer'];
            $data['user_agent']     = addslashes($_SERVER['HTTP_USER_AGENT']);
            $data['server_name']    = gethostname();
            $data['action']         = addslashes($dispatch);
            $data['timestamp']      = TIME;
            $data['session_id']     = Session::get_id();
            $data['other']          = ($other == '') ? $others[MODE] : $other;
 
            $sql = "INSERT INTO clues_site_requests (user_agent, referer, server_name, action, timestamp, session_id, other) values ('".$data['user_agent']."','".$data['referer']."','".$data['server_name']."','".$data['action']."','".$data['timestamp']."','".$data['session_id']."','".$data['other']."')";
            db_query($sql);
        }
	$_SESSION['auth']['previous_step'] = $_SERVER['REQUEST_URI'];
    }        
}

function fn_find_categories_for_affiliates($product_id, $utm_source){
	$whole_sale_status  = TRUE;
	if($utm_source!='' || !empty($utm_source))
	{
	 foreach ($product_id as $key=>$product_id)
    {
        $products_id[]=$product_id['product_id'];
    }
    
    for($i=0;$i<count($products_id);$i++)
        {
            $sql = db_get_field("select product_id from cscart_products where product_id='".$products_id[$i]."' and is_wholesale_product=1");
           
           if($sql>0)
           {
           		$whole_sale_status = FALSE;
           }

        }
    return $whole_sale_status;        
	}
}

function fn_find_promotion_for_affiliates($promotion_id,$utm_source){
	$true_pixel = FALSE;
        $promotion_type_id=  Registry::get('config.promotion_affiliate_type');
        $cond=TRUE;
        if(!empty($promotion_type_id))
        {
            $promotion_type_id_temp=  db_get_field("select promotion_type_id from cscart_promotions where promotion_id='".$promotion_id."'");
            if($promotion_type_id!=$promotion_type_id_temp)$cond=FALSE;
        }
        if($cond){
	if($utm_source=='markgroup')
	{
	    $internal_name = db_get_field("select count(*) as count from cscart_promotion_descriptions where promotion_id='".$promotion_id."' and (internal_name like '%omg%' or internal_name like '%markgroup%') ");
	}
	else if($utm_source=='shoogloo')
	{
		$internal_name = db_get_field("select count(*) as count from cscart_promotion_descriptions where promotion_id='".$promotion_id."' and (internal_name like '%trootrac%') ");
	}
	else
	{
		$internal_name = db_get_field("select count(*) as count from cscart_promotion_descriptions where promotion_id='".$promotion_id."' and internal_name like '%".$utm_source."%'");
	}
        }

	if($internal_name >0)
		{
				$true_pixel = TRUE;
		}
		return $true_pixel;
	}

function fetch_promotion_type_from_id($promotion_id,$type)
{
	
	$promotion_types_track = implode(",",$type);
	$sql = db_get_field("SELECT count(*) as count FROM cscart_promotions where promotion_id=".$promotion_id."
	 				and promotion_type_id in(".$promotion_types_track.")");
	if($sql > 0){
		return TRUE;
	}else{
		return FALSE;
	}
}	
?>
