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
    require(DIR_CORE . 'fn.auction.php');
    
   if(Registry::get('config.memcache'))
   {
    $memcache = new Memcache;
    $memcache_servers = Registry::get('config.memcache_servers');
    foreach($memcache_servers as $memcache_server){
      $mem_conn=$memcache->addServer($memcache_server['host'],$memcache_server['port'],'',$memcache_server['weight']);
    }
     if($mem_conn){
        $GLOBALS['memcache'] = $memcache;
        $GLOBALS['memcache_status'] = $mem_conn;
      }
   }

    $db_conn = db_initiate(Registry::get('config.db_host'), Registry::get('config.db_user'), Registry::get('config.db_password'), Registry::get('config.db_name'));
    if(isset($_REQUEST['action']) && $_REQUEST['action'] == 1 && !empty($_REQUEST['auction_id']))
    {
       echo  json_encode(get_common_auction_values($_REQUEST['auction_id']));
       exit;
    }
?>
