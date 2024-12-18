<?php
    //database connection using cs-cart
    define('AREA', 'C');
    define('AREA_NAME', 'customer');
    require  dirname(__FILE__) . '/../prepare.php';
    require  dirname(__FILE__) . '/../init.php';
    //THis below code is used to bypass security in IE8.
         header("p3p: CP=\"ALL DSP COR PSAa PSDa OUR NOR ONL UNI COM NAV\"");
    require_once('src/facebook.php');
    //Below is the facebook app setup.
    $config = (array(
    'appId' => '208941289250794',
    'secret' => '0cc8437498b0771d49e1bde7404a9568',
        ) );
    $facebook = new Facebook($config);
    $user_id = $facebook->getUser();
    //IP ADDRESS   $ip = $_SERVER["REMOTE_ADDR"];
?>