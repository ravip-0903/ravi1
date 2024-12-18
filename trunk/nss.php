<?php
    define('AREA', 'C');
    define('AREA_NAME', 'customer');
    define('DIR_ROOT', __DIR__);
    require_once('config.php');
    require_once('config.local.php');
    require(DIR_CORE . 'class.registry.php');
    Registry::set('config', $config);
    require(DIR_CORE . 'db/mysql.php');
    require_once(DIR_CORE . 'fn.database.php');
    if(defined('PROFILER') && PROFILER){
        require(DIR_CORE . 'class.profiler.php');
    }    
    include_once __DIR__ . '/core/log_init.php';    
    require(DIR_CORE . 'fn.nss.php');
    require_once('addons/my_changes/func.php');
    require(DIR_CORE . 'fn.cart.php');
    $response = array();
    if(isset($_REQUEST['pincode_no']) && isset($_REQUEST['product_ids']))
    {
      if(strlen($_REQUEST['pincode_no'])!=6 || !is_numeric($_REQUEST['pincode_no']))
      {
          $response['pin_result'] = "-1";
 	      echo json_encode($response);
          exit;
      }
    }

    if(isset($_REQUEST['pincode_no']) && isset($_REQUEST['product_ids']))
    {

        setcookie("pincode", $_REQUEST['pincode_no'],time()+3600*24*365,'/','.shopclues.com');
        $_COOKIE['pincode'] = $_REQUEST['pincode_no'];
        $db_conn = db_initiate(Registry::get('config.db_host'), Registry::get('config.db_user'), Registry::get('config.db_password'), Registry::get('config.db_name'));
        $is_servicable = get_servicability_type($_REQUEST['product_ids'], $_REQUEST['pincode_no']);
        if(isset($is_servicable))
        {
            $response['pin_result'] = $is_servicable;
            if($is_servicable == 3 || $is_servicable == 4)
            {
                $product = array($_REQUEST['product_ids']);
                $x[$_REQUEST['product_ids']] = 1;
                $result = json_decode(fn_get_shipping_time($_REQUEST['pincode_no'],$product,$x,''),true);
                $curdate = strtotime('now');
                $estdate = $curdate + $result['shipping_time']['total_shipping_time'];
                $estdate1 = $curdate + $result['shipping_time']['total_shipping_time_max'];
                $response['fdate'] = date('D j M',$estdate);
                $response['sdate'] = date('D j M',$estdate1);
            }
                        echo json_encode($response);
                       // print_r($result);die;
        }
        exit;
    }
?>
