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
// $Id: config.local.php 11725 2011-01-28 08:11:25Z 2tl $
//

if ( !defined('AREA') ) { die('Access denied'); }


/*
 * PHP options
 */

// Disable notices displaying
//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(1);
if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
	error_reporting(error_reporting() & ~E_DEPRECATED);
}

// Set maximum memory limit
// Updated by MC on 12/20/2011 - increased limit to 128M from 48M
//@ini_set('memory_limit', '128M');
@ini_set('memory_limit', '256M');

// Set maximum time limit for script execution
@set_time_limit(3600);

/*
 * Database connection options
 */
/*$config['db_host'] = 'shopclues.com';
$config['db_name'] = 'shopclue_test_cart';
$config['db_user'] = 'shopclue_dev';
$config['db_password'] = '20110905';
$config['db_type'] = 'mysql';*/

$config['db_host'] = 'dbfm1';
$config['db_name'] = 'shopclue_cart';
$config['db_user'] = 'production';
$config['db_password'] = 'India@agni5';
$config['db_type'] = 'mysql';

$config['slave']['slave_server'] = TRUE;
$config['slave']['db_host'] = 'dbfs1';
$config['slave']['db_name'] = 'shopclue_cart';
$config['slave']['db_user'] = 'production';
$config['slave']['db_password'] = 'India@agni5';


/*
 * Script location options
 *
 *	Example:
 *	Your url is http://www.yourcompany.com/store/cart
 *	$config['http_host'] = 'www.yourcompany.com';
 *	$config['http_path'] = '/store/cart';
 * 
 *	Your secure url is https://secure.yourcompany.com/secure_dir/cart
 *	$config['https_host'] = 'secure.yourcompany.com';
 *	$config['https_path'] = '/secure_dir/cart';
 *
 */

// Host and directory where software is installed on no-secure server
//$config['http_host'] = '180.179.49.4';
$config['http_host'] = 'www.shopclues.com';
$config['http_path'] = '';

// Host and directory where software is installed on secure server
//$config['https_host'] = '180.179.49.4';
$config['https_host'] = 'secure.shopclues.com';
$config['https_path'] = '';

/*
 * Misc options
 */
// Names of index files for administrative and customer areas
$config['admin_index'] = 'UniTechCity.php';
$config['customer_index'] = 'index.php';
$config['vendor_index'] = 'vendor.php';

// DEMO mode
$config['demo_mode'] = false;

// Tweaks
$config['tweaks'] = array (
	'js_compression' => true, // MC-12/20: changed from false - enables compession to reduce size of javascript files
	'check_templates' => false, //MC-12/20: changed from true -  disables templates checking to improve template engine speed
	'inline_compilation' => true, // compiles nested templates in one file
	'anti_csfr' => false, // protect forms from CSFR attacks (experimental)
	'disable_ajax_preload' => false, // used to disable ajax preload for speed-up admin area
	'disable_block_cache' => false, // used to disable block cache
);

// Cache backend
// Available backends: file, sqlite, mysql, shmem
// To use sqlite cache the "sqlite3" PHP module should be installed
// To use shmem cache the "shmop" PHP module should be installed
$config['cache_backend'] = 'file';

// Key for sensitive data encryption
$config['crypt_key'] = '20110905';

// Database tables prefix
define('TABLE_PREFIX', 'cscart_');

// Default permissions for newly created files and directories
define('DEFAULT_FILE_PERMISSIONS', 0666);
define('DEFAULT_DIR_PERMISSIONS', 0777);

// Maximum number of files, stored in directory. You may change this parameter straight after a store was installed. And you must not change it when the store has been populated with products already.
define('MAX_FILES_IN_DIR', 1000);

define('NOT_SERVICABLE',0);
define('NOT_SHIPPABLE',1);
define('BOTH_COD_PREPAID',3);
define('ONLY_PREPAID',4); 




// Developer configuration file
if (file_exists(DIR_ROOT . '/local_conf.php')) {
	include(DIR_ROOT . '/local_conf.php');
}

define('AUTH_CODE', '2M2Q0OGQ');


$config["tweaks"]["join_css"] = false; // is used to unite css files into one file
$config["tweaks"]["allow_php_in_templates"] = false; // Allow to use {php} tags in templates

$config['term_of_use_pageurl'] = 'user-agreement.html'; 
$config['privecy_policy_pageurl'] = 'privacy-policy.html'; 
$config['merchant_term_and_conditions'] = 'index.php?dispatch=pages.view&page_id=9';
$config['customer_term_of_use'] = 'index.php?dispatch=pages.view&page_id=9';
$config['customer_privacy_policy'] = 'index.php?dispatch=pages.view&page_id=9';
$config['daily_mail_limit'] = '7500';
$config['send_mail_count_per_cron'] = '20';
//$config['milkrun_statuses'] = array('G','E');
$config['milkrun_statuses'] = array('U', 'L', 'G', 'E', 'T', '11');
$config['default_shipping_estimation'] = '1';
$config['new_days_range'] = '4';

$config['second_level_menu_limit'] = '11'; //default value is 8
$config['third_level_menu_limit'] = '16'; //default value is 8
$config['category_popular_brands_limit'] = '15';

$config['top_menu_limit'] = '9'; //default value is 8
$config['order_statuses_for_merchant'] = array('A','B','C','E','G','H','J','K','L','P','Q');
$config['vendor_status'] = array('A','B','C','E','G','H','J','K','L','P','Q');

$config['order_statuses_for_milkrun'] = array('B','P','Q');
$config['deals_category_ids'] = array('87','337','99','456','274','204','97','121','219');
//$config['ext_css_path'] = "http://images.shopclues.com/skins/basic/customer";
//$config['ext_images_host'] = "http://images.shopclues.com";
$config['ext_css_path'] = (defined('HTTPS')) ? "https://secure.shopclues.com/skins/basic/customer" : "http://www.shopclues.com/skins/basic/customer";
//$config['ext_images_host'] = (defined('HTTPS')) ? "https://images2.shopclues.com" : "http://180.179.168.165";
$config['ext_images_host'] = (defined('HTTPS')) ? "https://image2.shopclues.com" : "http://cdn.shopclues.com";
$config['ext_js_path'] = (defined('HTTPS')) ? "https://secure.shopclues.com" : "http://www.shopclues.com";
//$config['internal_images_host'] =  "http://180.179.168.165";
$config['internal_images_host'] =  "http://cdn.shopclues.com";
$config['internal_images_server'] =  "http://10.20.72.28";


define('OUTPUT_FILE_DIRECTORY', DIR_ROOT.'/images/excel_upload/');
$config['order_statuses_for_milkrun_initiated'] = array('E');

$config['order_status_exclude_for_accounting']=array('F','I','N','D');

$config['order_statuses_for_create_manifest'] = array('Completed', 'Failed', 'Canceled', 'Declined', 'Refunded');

/* Start Set status for manifest report */

$config['status_for_manifest_dispatch'] = array('A','R');
$config['status_for_manifest_courier'] = array('P','E','G','B','Q','R');
$config['status_for_manifest_milkrun'] = array('E');
$config['status_for_manifest_return_to_merchant'] = array('A','B','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S');
/* End Set status for manifest report */



$config['noisewords']=array('','is','are','-',',','a','an','the');
$config['replacewithspace']=array(',','-');
$config['timestamp'] = '0';
$config['dead_orders'] = array('F','I','N','D','T');
#$config['special_sale_category_id'] = '1569';
$config['domain_url'] = 'http://www.shopclues.com';

$config['special_category_ids'] = array('1175','1181','3302');
$config['special_sale_category_id'] = array('1181','1569');
$config['memcache'] = TRUE;
$config['memcache_expire_time'] = 10800;
$config['memcache_host_server'] = '10.20.72.17';
$config['memcache_port'] = '11211';
$config['memcache_long_expire_time'] = 3600;

$config['memcache_session'] = TRUE;
$config['memcache_session_host_server'] = '10.20.72.20';
$config['memcache_session_host_port'] = '11211';
$config['memcache_servers']= array(
                                    array("host"=>"10.20.72.17","port"=>"11211","weight"=>"25"),
                                    array("host"=>"10.20.72.18","port"=>"11211","weight"=>"25")
                                    );

$config['log_events'] = FALSE;

$config['cssversion']=177;

$config['ftp_host'] = '10.20.72.28';  // ftp host
$config['ftp_user'] = 'cluesftp';            // ftp user
$config['ftp_pwd'] = 'gurgaon_sec15';                    // ftp password
$config['ftp_path'] = '/mnt/vol1/images/custom_files/sess_data/';
$config['ftp_path_order'] = '/mnt/vol1/images/custom_files/order_data/';
$config['ftp_temp'] = '/tmp/';
$config['ftp_host_image'] = 'http://10.20.72.28/';

$config['force_signin'] = 'NO';
$config['temp_category_id'] = array('1508');
$config['force_signin_pages'] = 'products-56823';

$config['emi_min_amount'] = '1500';
$config['hdfc_payment_gateway_return_url'] = 'https://pgw.shopclues.com';
$config['error_to_email_ids'] = 'technology@shopclues.com';
$config['cart_logging'] = TRUE;
$config['product_upload_count_check'] = 2;
#$config['left_for_gc_order']=array('F','I','Y','N','D','K','M');
$config['left_for_gc_order']=array('12','13','14','15','16','17','18','19','2','20','21','22','23','24','25','26','29','3','4','9','D','F','I','M','N','U','W','X','Y');
//$config['hide_block_id']= array('236','216','238','430','431','432','438');
$config['hide_block_id']= array('236','216','238','430','431','432','438','1679','1681','1683','1685','1849');
$config['category_hide_subcategory']= array('1673','1569');
$config['onedaysale_instock_limit'] = '20'; 

$config['gifting_charge'] = '25';
$config['giftable_fulfilment_id'] = array('1');
$config['default_gc_email'] = 'xxxxx@xxx.com';
#$config['gc_on_products'] = array('33469','121702','104892','57852','75835','106816','106919','88612','99593','117848','136608','105632','140818','122135','122441','123284','127650','59563','57187','96063','117112','85919','130428','66587','96204','118626','54178','59248','129580','114716','81055','130776','144925','112511','144528','135393','137872','113886','136709','139836','139020','142813','146182','148859','153212','156824','158306');
$config['gc_on_products'] = array('33469','81055','163894','163898','163900');
$config['giftable_region_code'] = '2';
$config['upsell_product_query_id']=7;
$config['show_upsell_products']=false;
$config['special_offer_badge_url'] = 'http://www.shopclues.com/images/skin/special_offericon.png';

$config['merchant_support_email'] = "merchantsupport@shopclues.com";

$config['cb_on_gc_in_pct'] = '0';
$config['cb_on_gc_max_amt'] = '0';
$config['max_cod_amount'] = '30000';
$config['merchant_new_limit'] = '30';
$config['product_new_limit'] = '18';
$config['show_feedback_link_status']=array('C');
$config['cb_expiry_days']=120;
$config['facebook_fan_page']='https://www.facebook.com/ShopClues/app_128953167177144';
$config['key_feature_on_search']=FALSE;
$config['cookie_domain'] = 'shopclues.com';
$config['cod_fee_amt'] = '0';
$config['dead_order_status']=array('F','I','N','D','Y','12','10','U','Z','83','84','75','74','73','56','55','54','53','52','51','50','49','48','47','46','45','44','43','42','41','40','39','38','37','36','35','34','25','24','23','22','21','20','19','14','13','11');
$config['javapdfurl']='http://10.20.72.7:8080/';
$config['3times_use_cc'] = 'SC2HD00';
$config['m3times_use_cc'] = 'SC2HD100';
//$config['solr']=FALSE;
//$config['solr_url']=array('hostname' => '10.20.117.42','port' => '8983','path' => '/solr/collection1');
//$config['min_match'] = '2';
$config['show_coupon_code_on_third_step']=true;
$config['check_license'] = FALSE;
$config['summit_from'] = 'sellersummit@shopclues.com';
$config['loc_img'] = ' images/';
$config['remote_img'] = ' shopclue@i1:/mnt/vol6/';
$config['write_pgw_log'] = TRUE;
$config['extend_time'] = '54000';
$config['max_play'] = 5;
##$config['fb_quiz_redirect'] = 'https://www.facebook.com/Shopclu/app_190322544333196';
$config[ 'fb_quiz_redirect'] = 'https://www.facebook.com/ShopClues/app_203351739677351';
$config['max_win'] = 0;
$config['winner_mail_to'] = 'rahul.gupta@shopclues.com';
//$config['category_solr']=FALSE;
//$config['homepage_solr']=TRUE;
//$config['referal_status'] = 'FALSE';
//$config['Referral_Candy'] = ' ';
$config[ 'review_login_required'] = 0;      // 0 means login not required
$config[ 'reviews_min_character'] = 50;
$config[ 'reviews_max_character'] = 2000;
$config[ 'review_character_restriction'] = 1;
$config['invoice_status'] = 'FALSE';
$config['referal_status'] = 'TRUE';
$config['Referral_Candy'] = 'invoices@3bnfmjdi4t.referralcandy.com';
$config['nrh_badge_id'] = 2;
$config['nrh_root_category_id'] = 2752;
$config['replace_logs']=TRUE; 
$config['checkout_logging'] = TRUE;
$config['checkout_logging_dispatchs'] = array(
"checkout.add", // Buy Now
"checkout.delete",
"checkout.checkout",
"checkout.place_order",
"checkout.express_checkout",
"checkout.update_steps",
"checkout.order_info",
"checkout.apply_coupon",
"checkout.apply_certificate",
"checkout.point_payment",
"payment_notification.return", 
"checkout.complete", 
"gift_certificates.add", 
"gift_certificates.delete"
);
$config['fail_percent_threshold'] = '30';
$config['fail_percent_max_amount_threshold'] = '30000';
$config['fail_percent_min_amount_threshold'] = '300';
$config['fail_on_cod_threshold_minutes'] = '480';
$config['affiliate_cat_id']='3168';
$config['deal_coupon_aff']='DealCoupon';
$config['coupon_for_same_affiliate']='Coupon';
$config['ivr_api_url']="http://localhost/api/ivr?key=d12121c70dda5edfgd1df6633fdb36c0&order_id="; 
$config['reviews_title_min_character'] = 0; 
$config['reviews_title_max_character'] = 100; 
$config['review_character_restriction'] = 1; 
$config['review_url_pattern'] = "\.com|\.org|\.net|\.co|\.in|\.mobi|\.bizz|\.info|\.cc|\.us|\.ca"; 
$config['min_user_limit_per_product'] = 3; 
$config['min_user_limit_per_day'] = 5; 
$config['limitPerProduct_restriction'] = 1; 
$config['limitPerDay_restriction'] = 1; 
$config['new_review_enable'] = 1;
$config['pdd_buffer_time'] = 172800; 
$config['pdd_edd_configs'] = array('pickup_time_deal' => array('JD'=>1,'Outrageous'=>1,'Sunday Flea Market'=>1), 
                                'pickup_time_min'=>array('1'=>86400,'2'=>86400,'3'=>86400), 
                                'pickup_time_max'=>array('1'=>0,'2'=>0,'3'=>0), 
                                'grace'=>array('1'=>0,'2'=>'0','3'=>'0'), 
                                'cutoff'=>array('1'=>array(9),'2'=>array(11),'3'=>array(11)) 
                                ); 
$config['pdd_weigth'] = 5;
//$config['shopclues_logging'] = TRUE;
/*** START SOLR CONFIGURATION ***/ 
$config['solr']=TRUE; 
$config['solr_url']= array('hostname' => '10.20.72.23', 'port'=> '8983', 'path'=> '/solr/shopclue_prod'); //180.179.50.231 
$config['min_match'] = '2'; 
$config['homepage_solr']=FALSE; 

$config['category_solr']=TRUE; 
$config['root_category_solr']=FALSE; 
// change in solr first for price ranges 
$config['fn_assign_pricekey']=array('P1' => 'price:[* TO 250]','P2' => 'price:[251 TO 500]','P3' => 'price:[501 TO 1000]','P4' => 'price:[1001 TO 2500]','P5' => 'price:[2501 TO 5000]','P6' => 'price:[5001 TO 10000]','P7' => 'price:[10001 TO 20000]','P8' => 'price:[20001 TO *]'); 
$config['fn_showprice']=array('P1' => 'Under 250','P2' => '251 - 500','P3' => '501 - 1,000','P4' => '1,001 - 2,500','P5' => '2,501 - 5,000','P6' => '5,001 - 10,000','P7' => '10,001 - 20,000','P8' => 'Over Rs 20,000'); 
// change in solr first for discount ranges 
$config['fn_assign_discountkey']=array('D1' => 'discount_percentage:[* TO 20]','D2' => 'discount_percentage:[21 TO 40]','D3' => 'discount_percentage:[41 TO 60]','D4' => 'discount_percentage:[61 TO 80]','D5' => 'discount_percentage:[81 TO *]'); 
$config['fn_showdiscount']=array('D1' => 'Less than 20%','D2' => '21 - 40%','D3' => '41 - 60%','D4' => '61 - 80%','D5' => 'More than 80%'); 
/*** END SOLR CONFIGURATION ***/
$config['markgroup_coupon_for_same_affiliate'] = 11531; 
$config['markgroup_deal_coupon_aff'] = 11646;
$config['captcha_email_status']='FALSE'; 
##$config['shopclues_app_id']='142530279235763';
$config['shopclues_app_id']='208941289250794';
$config['search_feedback_option'] = array("Can't find what I want","Technical issue with website","Product description is incorrect","Feature request","Others"); 
$config['send_product_name_to_payu'] = '0'; //0 = fixed value, 1 = single item or multiple item, 2 = product name 
$config['send_product_name_to_payu_fixed_value'] = 'Product Info';
$config['TM_limit'] = 6;
//$config['photo_contest_redirect']='https://www.facebook.com/R1234/app_203351739677351';
$config['photo_contest_redirect']='https://www.facebook.com/ShopClues/app_160430850678443';
$config['college_campus_appeal'] = 'Please vote for my picture'; 
$config['max_voting_limit_fb'] = 3;
$config['grid_limit_fb']=8;
$config['fb_image_host_url'] = "http://cdn.shopclues.com/";
$config['rsync_parameter_fb'] = '-az';
$config['locfb_img'] = 'images/fb_photo_contest/';
$config['remote_img_fb'] = ' shopclue@i1:/mnt/vol6/images/fb_photo_contest'; 
$config['edp_transit_time'] = 21600;
$config['secret_key']='W3LC0M32CLU3S!'; 
$config['OSLA_status1'] = '88'; 
$config['OSLA_status2'] = '89';
$config['brand_search'] = 'TRUE'; 
$config['filter_length'] = '20';
$config['correct_total'] = TRUE; 
$config['log_correct_total'] = TRUE;
$config['sponsored_count'] = 1;
$config['sponsored_highlight'] = FALSE;
$config['dropdown_count'] = 16; // 4 * 4
$config['api_listing_per_page'] = 10; 
$config['api_bestseller_per_page'] = 5; 
//$config['api_token_timeout'] = 86400;
$config['api_token_timeout'] = 604800;
$config['api_logging'] = true; 
$config['api_static_key'] = 'd12121c70dda5edfgd1df6633fdb36c0'; 
$config['api_blocks'] = array( 'buyer_central' => 1440, 
    'about_us' => 1442, 
    'contact_us' => 1446, 
    'privacy_policy' => 1455, 
    'user_agreement' => 1453, 
    'customer_support' => 1448, 
    'brand_of_trust' => 1444, 
    'help_topics' => 1450, 
'sell_with_us'=>1452, 
); 

$config['api_pages'] = array( 'buyer_central' => 258, 
    'about_us' => 260, 
    'contact_us' => 262, 
    'privacy_policy' => 271, 
    'user_agreement' => 273, 
    'customer_support' => 264, 
    'brand_of_trust' => 266, 
    'help_topics' => 268, 
'sell_with_us'=>270, 
);
//$config['image_generate'] = 'http://catalog.shopclues.com/';
$config['image_generate'] = 'http://10.20.72.52/';
$config['session_salt'] = '13ShoPClUesA2';
$config['category_boost'] = TRUE;
//$config['api_only_cod_products'] = true;
$config['api_only_cod_products'] = false;
$config['payoom_coupon_for_same_affiliate']='44'; 
$config['payoom_deal_coupon_aff']='46';
$config['shopclues_app_id_for_login'] = 208941289250794; 
$config['shopclues_app_secret_for_login'] = '0cc8437498b0771d49e1bde7404a9568';
$config['shopclues_logging']['storage'] = array('file'); 
$config['shopclues_logging']['mongo']['host'] = "10.20.117.30"; 
$config['shopclues_logging']['mongo']['dbname'] = "frontend_logs"; 
$config['shopclues_logging']['mongo']['collection'] = 'app_metric';
$config['new_block_query'] = TRUE;
$config['other_sellers_same_product'] = TRUE;
$config['viral_send_status']=1;
$config['avg_block_show']=1; 
$config['shipping_block_show']=1; 
$config['ask_merchant_block_show']=1; 
$config['green_rating_range_start']=60; 
$config['red_rating_range_end']=30;
$GLOBALS['memcache_key'] = array(); 
$config['shopclues_logging']['mongo']['collections']['app_metric'] = 'app_metric'; 
$config['shopclues_logging']['mongo']['collections']['app_error'] = 'app_error';
$config['top_rating_range_start']=60; 
$config['low_rating_range_end']=30; 
$config['low_rating_color']='#ff0000'; 
$config['middle_rating_color']='#ff9900'; 
$config['top_rating_color']='#009900';
$config['recently_view_product_limit']= 6; 
$config['other_sellers_percentage'] = '20'; 
$config['other_sellers_limit'] = '50';
$config['api_payment_gateway'] = 29; // payment gateway for Payu. used for mobile app's net banking
$config['api_single_gateway'] = FALSE; // true if single gateway to be used. For now that single gateway is Payu
$config['api_no_emi'] = TRUE; // Remove emi options in mobile app. make it false to enable emi in apps
$config['api_emi_payment_type_id'] = 6; // payment type id for emi payment
$config['apiCod_payment_option_id'] = 61; // payment option id for cod
$config['apiCod_payment_id'] = 6; // payment id for cod
$config['current_api_version'] = 'v2'; 
/*Endless Scrolling flag starts*/ 
$config['endLessScrollOn'] = 1; 
$config['numOfPagesES'] = 5; 
/*Endless Scrolling flag ends*/
/*Responsive ui flag*/
$config['isResponsive'] = 0;
/*Responsive design urls starts*/

$config['desktopSiteURL'] = "www.shopclues.com";
$config['mobileSiteURL'] = "m.shopclues.com";
/*Responsive design urls ends*/

/*Responsive redirection to main site starts*/
$config['redirectionOn'] = 0;
/*Responsive redirection to main site ends*/
$config['main_website_link'] = "www.shopclues.com";
$config['loc_prd_img'] = '/mnt/vol3/images/';
$config['remote_prd_img'] = 'shopclue@i1:/';
$config['rsync_parameter'] = '-azR';
$config['anniversary_status_show']=TRUE;
$config['readonly_browser'] = FALSE;
$config["express_to_four_step"] = TRUE;
$config['express_checkout']=TRUE;
$config['google_analytics_new_code']=1; // 1 means new GA code will be fired else old one. 
$config['google_api_key'] = "AIzaSyAqyMu7uT5js9O0iucF0XSfFSxLqDy4NNU";
$config['anniversary_status_show']=TRUE;
$config['elec_for_vcom']='GL7Q'; 
$config['others_for_vcom']='SL7K'; 
$config['coupon_for_vcom']='GLEQ'; 
$config['deal_coupon_for_vcom']='GL7W'; 
$config['new_vcom_stat']=TRUE; 
$config['tyroo_enable_stat']=FALSE; 
$config['tyroo_new_enable_script']=TRUE;
$config['hide_express_promotional_product'] = FALSE;
$config['seller_detail_trm_path'] = 'http://shopclues.com';
$config['piwik_url']='track.shopclues.com/';
$config['piwik_switch']=FALSE;
$config['edd_pdd_status']=array('27','28','32','33','34','72','82','88','92','A','B','E','G','K','L','O','P','Q','V','X'); 
$config['gc_limit_promotional'] = 1; //1 means only two promotions and 0 means all GC
$config['fb_user_engagement'] = 0; 
$config['expire_campaign_time']=1/2; // means half an hour
$config['no_of_fb_comment_show']= 3; 
$config['fb_comment_app_id'] = 208941289250794; 
$config['fb_comment_block'] = 0;
$config['app_calculate_thirdPrice'] = TRUE;
// config entry for mobile app popup 
$config["noAppReminderHours"] = 4; 
$config["showPopUpAndroid"] = 1; 
$config["showPopUpIphone"] = 0; 
$config["showPopUpBlackberry"] = 0; 
$config["showPopUpWindows"] = 0; 
$config["showPopUpAllMobiles"] = 0; 
// config entry for Desktop site view hours 
$config["dsktpSiteViewHours"] = 4;
$config['TM_you_like'] = 6;
$config['website_protocol'] = (defined('HTTPS')) ? "https://" : "http://";
$config['mobile_perf_optimization'] = 0; 
$config['merchant_instant_email'] = FALSE;
$config['labs_email_to'] = 'sandeep@shopclues.com'; 
$config['labs_email_from'] = 'labs@shopclues.com'; 
$config['you_may_like_tm_email']=TRUE;
$config['hide_block_id_mobile']= array(); 
$config['search_terms_solr']='product^5.0 meta_keywords^2.0 search_words^3.0 page_title^4.0';
$config['advertisement_email_to'] = 'advertise@shopclues.com'; 
$config['advertisement_email_from'] = 'advertise@shopclues.com';
$config['stop_elastic_mail'] = FALSE;
$config['enable_short_url'] = FALSE;
$config['url_shortener_time_limit'] =1000;
$config['sms_template_id_for_shipped_cod']= 29;
$config['sms_template_id_for_shipped_not_cod']= 28;
$config['sms_template_id_for_open_cod'] =9;
$config['sms_template_id_for_paid']= 26;
$config['sms_template_id_for_cod_confirmed']= 27;
$config['sms_template_id_for_71']= 27; 
$config['ws_subscription_product'] = 2365485;
$config['enable_wholesale_feature']=1;
$config['ws_membership_type'] = 1;
$config['wholesale_membership_validity_period']=365;
$config['track_user'] = TRUE; 
$config['enable_all_category']=TRUE;
$config['select_on_master_controllers'] = array("checkout","payment_notification", "companies", "rquest","auth");
$config['log_query'] = FALSE;
$config['TM_limit_vertical'] = 6;
$config['new_icubes_stat'] = TRUE;
$config['coupon_for_icubes'] = 'GL3e';
$config['deal_coupon_for_icubes'] ='GL3q';
$config['others_for_icubes'] = 'SL2i';
$config['elec_for_icubes'] = 'GL3k';
$config['wholesale_icon']=True; 
$config['desktop_os'] = array('Windows NT','Ubuntu','Macintosh');
$config['forget_password_issue'] = TRUE;
$config['showMobOffers']=FALSE;
$config['email_provider'] = 'ELASTIC'; // 'SENDGRID'
$config['sendgrid_entries'] = array('sendgrid_user'=>'mrinal@shopclues.com','sendgrid_pass'=>'Welcome2Core','sendgrid_url'=>'http://sendgrid.com/'); 
$config['sendgrid_remote_img'] = 'images/attachments/';
$config['labs_invitation_email_to'] = 'labs@shopclues.com';
$config['labs_invitation_email_from'] = 'labs@shopclues.com';
$config['labs_project_email_to'] = 'labs@shopclues.com';
$config['labs_project_email_from'] = 'labs@shopclues.com';
$config['project_attach_code_format'] = array("application/zip");
$config['project_presentation_format'] = array("application/vnd.ms-powerpoint","application/vnd.openxmlformats-officedocument.presentationml.presentation");
$config['project_write_up_format'] =array("text/plain","application/msword","application/vnd.openxmlformats-officedocument.wordprocessingml.document");
$config['solr_extra_params']=TRUE;
$config['solr_function']=array('fq'=>'addFilterQuery','sort'=>'addSortField' ,'facets'=>'addFacetField'); 
$config['products_limit_per_page'] = 0;
$config['promotion_types_one_day']=array('8');
$config['promotion_types_special_sale']=array('9');
$config['promotion_type_other_sale']=array('10'); 
$config['ques_ans_block_enable'] = 1; 
$config['remarketing_pixel_status']=TRUE;
$config['logging_for_user_id_issue'] = TRUE; 
$config['rma_group']=array('S','6','1','81','30','58','67','80','57','64','59','62','66','5','60','68','65','61');
$config['rto_group']=array('J','29','W','7','R','78','31','85','79','70','4','9','69');
$config['cancelled_no_ev_group']=array('14','I','73','Y','21','23','22','19','20','75','38','39','40','10','41','43','42','36','34','35','74','44');
$config['cancelled_with_ev_group']=array('Z','56','13','12','54','24','25','52','84','51','53','55','83','11','37','45','49','47','50','46','48','26');
$config['refunded_group']=array('M');
$config['approval_bucket'] = array( 1=> array('N','O','V','K'), 2=> array('N','O','V','K') , 3 => array('N','O','V','K') );
$config['process_bucket'] = array( 1=> array('P','Q','92','93','E','32','33','27','28'), 2=> array('P','Q','92','93') , 3 => array('P','Q','92','93') );
$config['qc_bucket'] = array( 1=> array('G','L'), 2=> array('L') , 3 => array() );
$config['out_delivery_bucket'] = array( 1=> array('A'), 2=> array('A') , 3 => array('A') );
$config['complete_bucket'] = array( 1=> array('H'), 2=> array('H') , 3 => array('H') );
$config['no_of_buckets']='5';
$config['bazooka_arr'] = array( 1=> $config['approval_bucket'], 2=> $config['process_bucket'], 3=> $config['qc_bucket'], 4=> $config['out_delivery_bucket'], 5=> $config['complete_bucket']);
$config['complete_msg_for_bazooka']="Order is estimated to be delivered between <EDD1> and <EDD2>.";
$config['bazooka_on']='1';
$config['bazooka_status_desc']=array('on_schedule'=>'On Schedule','delayed'=>'Delayed', 'complete'=>'Complete','cancel'=>'Cancelled','refund'=>'Refund','return'=>'Returned');
$config['bazooka_on_thank_you_page']='1';
$config['default_cod_on_mobile']=FALSE;
$config['display_otp_captcha']=TRUE;
$config['auto_confirmed_status_for_default_cod']=FALSE; 
$config['pixels_across_site'] = array('adroll_pixel_script'=>TRUE,'chuknu_pixel_switch'=>TRUE);
$config['footer_pixels_dynamic_lang_var'] = array('stat_clicktrack'=>TRUE,'ads_yahoo_pixel'=>TRUE,'vrmnt_script_across'=>0); 
$config['cb_reward_on_cod'] = FALSE; //either true or false 
$config['enable_free_product_shipping'] = TRUE;
$config['show_cookie_akamai']=FALSE;
$config['is_set_recent_cookie'] = 0; 
$config['cashback_inside_image_url'] = 'http://cdn.shopclues.com/images/skin/cash_back_inside.png';
$config['freebee_inside_image_url'] = 'http://cdn.shopclues.com/images/skin/Free_bee_icon.png';
$config['coupon_for_trootrac'] = '47';
$config['deal_coupon_for_trootrac'] ='46'; 
$config['mongo_memcache']=TRUE;
$config['mongo_block']=FALSE;
$config['mongo_block_connect'] = array('host' => '10.20.72.27','username' => 'production', 'password' => 'India@agni5', 'db'=> 'shopclue_mongo', 'collection' => 'production_blocks' );
$config['mongo_module'] = array('all_pages','checkout','products','index','categories'); 
$config['single_api_tm']=1;
$config['TM_limit_single_api']=6;
$config['prod_tm_blocks']="vsims,csims,rhf";
$config['cat_tm_blocks']="rhf,na,bs"; 
$config['update_third_price'] = TRUE;
$config['stay_signin'] = TRUE; 
$config['log_status_change_attempt'] = TRUE;
$config['TM_recommend_limit'] = 5;
$config["show_TM_on_mobile"] = 1;
$config['hide_payment_option_id_on_gc'] = array('58','107');
$config['hide_payment_type_id_on_gc'] = array(7); 
$config['enforce_nss_on_cod'] = FALSE;
$config['show_nss_alert'] = TRUE;
$config['enable_nss']=1; 
$config['enable_nss_logs']=1;
$config['quick_view_show']=true; 
$config['enable_auction']=0; 
$config['auction_data_refresh_frequency']=10000;//no of miliseconds for refreshing the auction data 
$config['show_auction_banner_1']=1; 
$config['show_auction_banner_2']=1;
$config['enable_scrapbook'] = FALSE;
$config['cdn_scrapbook_url'] = "http://cdn.shopclues.com/images/scrapbook/";
$config['scrapbook_logo'] = "http://images.shopclues.com/images/banners/icons/scrapbook_logo.png";
$config['fashion_week_url'] = "http://www.shopclues.com/shopclues-fashion-week.html";
$config['scrapbook_icon_url'] = "http://cdn.shopclues.com/images/banners/icons/scrap_book_img.png"; 
$config['cdn_scrapbook_loader_image'] = "http://cdn.shopclues.com/images/no_image.gif"; 
$config['search_source']='S'; 
$config['track_user_cookie']=3600*48; // 2 days
$config['no_of_scrapbook_to_show'] = 20;
$config['Suvidhaa_Salt'] = 'SC_Suvidhaa_20140503'; 
$config['suvidha_payment_id'] = '44'; //first create one payment method and put that id in this config 
$config['cbd_pending_status'] = '98';//first create one status for CBD pending and then put that id in config
$config['sms_template_id_for_cbd_pending_status'] = '44';
$config['sms_template_id_for_cbd_paid_status'] = '44';
$config['suvidha_url'] = 'www.shopclues.com';
$config['gc_applied_cart_limit'] = 2;
$config['xbuy_now_popup'] = TRUE; 
$config['checkout_fix_header']= True;
$config['show_min_qty_discount'] = 0; 
$config['update_min_qty_discount'] = 0; 
$config['min_qty_disc_validity_period'] = 365;
$config['coupon_for_komli'] = 133;
$config['deal_coupon_for_komli'] = 135;
$config['pixels_on_thankyou_page'] = array('icubes'=>TRUE,'viralmint'=>FALSE,'google_conversion'=>TRUE,'targeting_mantra'=>TRUE,'fb_dynamic_value'=>TRUE,'experian_tracking'=>TRUE,'komliremarketing'=>TRUE,'flex_msn_yahoo'=>TRUE);
$config['thankyou_pixels_dynamic_lang_var'] = array('flex_msn_script'=>FALSE,'conversion_tracking_script'=>TRUE);
$config['sitemap_changefreq'] = 'daily'; 
$config['sitemap_priority'] = 0.5; 
$config['sitemap_product_limit'] = 25000;
$config['enable_size_chart']=true;
$config['size_chart_categories_id']=array(218);
$config['allow_access_control'] = true;
$config['google_app_login_id'] = '694760809818-8bnbin555q9guuaoi1fn7h96775o7s0b.apps.googleusercontent.com';
$config['google_app_login_secret'] = 'gsdiGGBpkAgU0pK-MgGNFLj_';
$config['glogin_redirect_uri'] = 'http://shopclues.com/api/v3/glogin';
$config['facebook_secret_android_login'] = '3a79a35409c37783187ac86ac7ca7323';
$config['show_global_filters']=TRUE;
$config['mainBannerURL'] = "http://www.shopclues.com/api/v3/home_deals?key=d12121c70dda5edfgd1df6633fdb36c0"; 
$config['secondaryBannersURL'] = "http://www.shopclues.com/api/v3/new_deals?gid=16"; 
$config['categoriesURL'] = "http://www.shopclues.com/api/v3/category"; 
$config['subCatURL'] = "http://www.shopclues.com/api/v3/category?cat_id="; 
$config['catImgURL'] = "http://www.shopclues.com/api/v3/new_deals?gid=18"; 
$config['isLightVersion']=0; 
$config['jlt_comment_limit'] = '8';
$config['quantity_discount_flag'] = 0;
$config['enable_PBE']=1;
$config['zettata_master_switch']=FALSE; 
$config['zettata_track']=FALSE; 
$config['zettata_threshold']=0; 
$config['zettata_authkey']='a61917eb0cc5063b3c247b6e1ce0b8b1'; 
$config['zettata_url']="http://api.stage.us-east.zettata.com/v1/shopclues"; 
$config['fn_zettata_pricekey']=array('price:[* TO 250]' => 'P1','price:[251 TO 500]' => 'P2','price:[501 TO 1000]' => 'P3','price:[1001 TO 2500]' => 'P4','price:[2501 TO 5000]' => 'P5','price:[5001 TO 10000]' => 'P6','price:[10001 TO 20000]' => 'P7','price:[20001 TO *]' => 'P8'); 
$config['fn_zettata_showprice']=array('* TO 250' => 'Under 250','251 TO 500' => '251 - 500','501 TO 1000' => '501 - 1,000','1001 TO 2500' => '1,001 - 2,500','2501 TO 5000' => '2,501 - 5,000','5001 TO 10000' => '5,001 - 10,000','10001 TO 20000' => '10,001 - 20,000','20001 TO *' => 'Over Rs 20,000'); 
$config['fn_zettata_discountkey']=array('discount_percentage:[* TO 20]' =>'D1' ,'discount_percentage:[21 TO 40]' => 'D2','discount_percentage:[41 TO 60]' => 'D3','discount_percentage:[61 TO 80]' => 'D4','discount_percentage:[81 TO *]' => 'D5'); 
$config['fn_zettata_showdiscount']=array('* TO 20' => 'Less than 20%','21 TO 40' => '21 - 40%','41 TO 60' => '41 - 60%','61 TO 80' => '61 - 80%','81 TO *' => 'More than 80%'); 
$config['footer_pixels_dynamic_lang_var'] = array('stat_clicktrack'=>TRUE,'ads_yahoo_pixel'=>TRUE,'vrmnt_script_across'=>0,'zettata_tracker'=> 0);
$config['solr_exclude_filters'] = array('is_trm','show_merchant','product_rating','products','category','brand','price','products_count','items_per_page','discount_percentage','product_amount_available','is_cod'); // Existing config, add "is_cod" 
$config['solr_filters'] = array('is_trm','show_merchant','is_cod'); // existing config, add "is_cod" 
$config['solr_hide_filter'] = array(); // NEW config
?>
