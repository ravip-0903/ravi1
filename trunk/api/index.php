<?php

define('AREA', 'C');
define('AREA_NAME', 'customer');
define('ACCOUNT_TYPE', 'customer');

// Key has been checked for unknown host if they try to submit the form then it just exist from here with out connecting to db. 
/*if(!(isset($_REQUEST['key']) && ($_REQUEST['key'] == 'd12121c70dda5edfgd1df6633fdb36c0'))){
	echo json_encode(array("result" => array(array("status"=>"failed","msg" => "Invalid Key", "error"=>"105"))));
	exit;
}*/



require  dirname(__FILE__) . '/../prepare.php';
require  dirname(__FILE__) . '/../init.php';

//include_once 'search.class.php';

function __autoload($classname) {

    echo $filename = $classname .".class.php";
    /*(if (!file_exists($filename)){
    	echo json_encode(array("result" => array(array("status"=>"failed","msg" => "Invalid request", "error"=>"106"))));
    	exit;
    }*/	

echo dirname(__FILE__) . '/'.$filename;
    include dirname(__FILE__) . '/'.$filename;

}
$class_file = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
echo $class_file; die;
$get_class = explode('.',$class_file);
echo $class_name = $get_class[0];
$api = new $class_name();
if($class_name == 'search'){
	$api->search();
}
elseif($class_name == 'users'){
	$api->login();
}
else{
	echo json_encode(array("result" => array(array("status"=>"failed","msg" => "Invalid request", "error"=>"106"))));

}

?>
