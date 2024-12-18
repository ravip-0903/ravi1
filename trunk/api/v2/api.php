<?php

define('ANDROID_API','TRUE');                           // fix me -- Its used in fn.control. Do we really need to use it on fn.control ??
define('AREA', 'C');
define('AREA_NAME', 'customer');
define('ACCOUNT_TYPE', 'customer');
require_once  dirname(__FILE__) . '/../../prepare.php';
require_once  dirname(__FILE__) . '/../../init.php';
require_once dirname(__FILE__) ."/Rest.inc.php";
require_once dirname(__FILE__) ."/class.authenticator.php";

$VERSION_DIR = 'v2';
// version calculation
$func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
if ($func == 'v') {
	$ini_array = parse_ini_file("version.ini");		
	print json_encode($ini_array);die;
}


$REQ = fn_get_req_vars();
//echo '<pre>';print_r($REQ);die;
$auth = new Authenticator('SHA256');
$auth->AuthenticateUser();


if($func == 'login' || $func == 'signup'){
        $file = 'users.php';
        $class = 'users';
}elseif($func == 'category' || $func == 'bestsellers' || $func == 'popular'){
        $file = 'category.php';
        $class = 'categories';
        require  DIR_ADDONS . 'discussion/func.php';
        require  DIR_ADDONS . 'my_changes/func.php';
}elseif($func == 'search'){
        $file = 'search.php';
        $class = 'search';
}elseif($func == 'trackorder' || $func == 'orders'){
        $file = 'orders.php';
        $class = 'orders';
}elseif($func == 'addressbook' || $func == 'profile_details' || $func == 'password'){
        $file = 'profiles.php';
        $class = 'profiles';
}
elseif($func == 'forgotpassword'){
        $file = 'forgotpassword.php';
        $class = 'forgotpassword';
}
elseif($func == 'myaccount'){
        $file = 'profiles.php';
        $class = 'profiles';
        require_once './method/orders.php';
}
elseif($func == 'products' || $func == 'check_inventory'){
        $file = 'products.php';
        $class = 'products';
}elseif($func == 'cluesbucks'){
        $file = 'cluesbucks.php';
        $class = 'cluesbucks';
}
elseif($func == 'hot_deals'){
        $file = 'blocks.php';
        $class = 'blocks';
        require_once './method/category.php';
        require  DIR_ADDONS . 'discussion/func.php';
        require  DIR_ADDONS . 'my_changes/func.php';

}
elseif($func == 'home_deals' || $func == 'new_deals' ){
        $file = 'blocks.php';
        $class = 'blocks';

}

elseif($func == 'ttl'){                    
        $file = 'ttl.php';
        $class = 'ttl';        
}

elseif($func == 'featured_products'){
        require_once './method/category.php';
        require  DIR_ADDONS . 'discussion/func.php';
        require  DIR_ADDONS . 'my_changes/func.php';

        $file = 'blocks.php';
        $class = 'blocks';
        
}
elseif($func == 'pages'){
        $file = 'blocks.php';
        $class = 'blocks';            
}elseif($func == 'wishlist'){
        $file = 'wishlist.php';
        $class = 'user_wishlist';
        require  DIR_ADDONS . 'wishlist/controllers/customer/wishlist.php';
}elseif($func == 'ivr'){
        $class = 'ivr';
        $file = 'ivr.php';
}
elseif($func == 'cart' || $func == 'cart_count'){
        $file = 'cart.php';
        $class = 'user_cart';  
        require  DIR_ADDONS . 'sdeep/func.php';
}
elseif($func == 'checkout'){

        $file = 'checkout.php';
        $class = 'checkout';
        require  DIR_ROOT . '/controllers/customer/checkout.php';
}

elseif($func == 'app_data'){

        $file = 'app_data.php';
        $class = 'app_data';
        require  DIR_ROOT . '/controllers/customer/checkout.php';
}

if (file_exists(DIR_ROOT . '/api/'.$VERSION_DIR.'/method/' . $file)) {
        include(DIR_ROOT . '/api/'.$VERSION_DIR.'/method/' . $file);
}

/*for payment gateway*/
if($func == 'payment'){
    $file = 'method/process_payment.php';
    require  DIR_ROOT . '/controllers/customer/checkout.php'; 
    require  DIR_ROOT . '/api/'.$VERSION_DIR.'/method/checkout.php'; 
    include $file; 
    exit();
}
// Initiiate Library

if (class_exists($class)) {
        $myclass = new $class();
        $myclass->$func();
        exit;
}


function fn_get_req_vars() {
    $req_method = $_SERVER['REQUEST_METHOD'];
    if ($req_method == 'POST') {
        $data = $_POST;
        if($_SERVER['CONTENT_TYPE']=='application/json'){
            $data = file_get_contents("php://input");
            $data = json_decode($data,TRUE);
        }
    }    
    if ($req_method == 'PUT') {
        if($_SERVER['CONTENT_TYPE']=='application/json'){
            $data = file_get_contents("php://input");
            $data = json_decode($data,TRUE);            
        }
        else {
            parse_str(file_get_contents("php://input"),$data);            
        }
    }    
    if ($req_method == 'GET' || $req_method == 'DELETE') {
        $data = $_GET;
    }    
    return $data;
}


function fn_remove_specialchars_from_image_path($image, $width=160) {
	$path = explode('/', $image);
	$c = count($path) - 1;
	$name = $path[$c];
	$name = preg_replace("/[^a-zA-Z0-9.]/i", "", $name);
	unset($path[$c]);
	$remaining = implode('/', $path);
	$remaining = str_replace('320', $width, $remaining);
	$final = $remaining.'/'.$name;
	return $final;
}

?>
