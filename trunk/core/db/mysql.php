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
// $Id: mysql.php 12865 2011-07-05 06:57:22Z 2tl $
//

if ( !defined('AREA') )	{ die('Access denied');	}

//
// Database function wrappers (mySQL)
//

// Returns connection ID or false on failure
function driver_db_connect($db_host, $db_user, $db_password, $dbc_name = 'main')
{
	$db_conn = mysql_connect($db_host, $db_user, $db_password);
	if (!empty($db_conn)) {
		Registry::set('runtime.dbs.' . $dbc_name, $db_conn);
	}
	
	return $db_conn;
}

// Returns connection ID or false on failure
function driver_db_select($db_name, $db = '')
{
	$db_conn = & Registry::get('runtime.dbs.' . (!empty($db) && Registry::get('runtime.dbs.' . $db) ? $db : 'main'));

	if (@mysql_select_db($db_name, $db_conn)) {
		return $db_conn;
	}

	return false;
}

function driver_db_create($db_name)
{
	return driver_db_query("CREATE DATABASE IF NOT EXISTS `$db_name`");
}

function driver_db_query($query, $db = '')
{ 
        if(Registry::get('runtime.db.prepare.called') === false){
            $db_conn_success = db_prepare();

            if ($db_conn_success && defined('MYSQL5')) {
                    db_query("set @@sql_mode = ''");
            }
        }

        static $reconnect_attempts = 0;
	$db = (!empty($db) && Registry::get('runtime.dbs.' . $db)) ? $db : 'main';
        // reconnect with salve server if query having select and not from checkout and order controller 
        $isConnectSlave = false;
        if(Registry::get('config.slave.slave_server') === true && $db != 'slave'){
		/*$sessionTables = array("cscart_stat_sessions","cscart_stored_sessions",
				       "cscart_user_session_products","cscart_lh_sessions","cscart_sessions"); */
		$controllersArray = Registry::get('config.select_on_master_controllers');
		$db = reconnectToSlave($query,$controllersArray,"slave",$isConnectSlave);
	}
	// end reconnect

	if (preg_match("/^REPLACE INTO/", $query) && Registry::get('config.replace_logs')) 
 {            
        $file = 'images/logs/replace_logs.txt';
        if(filesize($file)<52428800)
		file_put_contents($file,$query."\n",FILE_APPEND);
  }
	
        if(Registry::get('config.log_query')){
            $log_id = LogHelper::get_id();
            $query = preg_replace('/^\s*(select|insert|update|delete|set)\s/i', '\1 /*id=' . $log_id . '*/ ', $query, 1);
        }
        
	$db_conn = & Registry::get('runtime.dbs.' . $db);
	$result = mysql_query($query, $db_conn);

	if (empty($result) && $isConnectSlave === false) {
		// Lost connection, try to reconnect (max - 3 times)
		if ((mysql_errno($db_conn) == 2013 || mysql_errno($db_conn) == 2006) && $reconnect_attempts < 3) {
			driver_db_close($db);
			$db_conn = db_initiate(Registry::get('config.db_host'), Registry::get('config.db_user'), Registry::get('config.db_password'), Registry::get('config.db_name'));
			$reconnect_attempts++;
			$result = driver_db_query($query, $db);

		// Assume that the table is broken
		// Try to repair
		} elseif (preg_match("/'(\S+)\.(MYI|MYD)/", mysql_error($db_conn), $matches)) {
			$result = mysql_query("REPAIR TABLE $matches[1]", $db_conn);
		}
	}

	return $result;
}

function driver_db_query_nocheck($query, $db = '')
{
        if(Registry::get('runtime.db.prepare.called') === false){
            $db_conn_success = db_prepare();

            if ($db_conn_success && defined('MYSQL5')) {
                    db_query("set @@sql_mode = ''");
            }
        }

	$db_conn = & Registry::get('runtime.dbs.' . (!empty($db) && Registry::get('runtime.dbs.' . $db) ? $db : 'main'));

	$result = mysql_query($query, $db_conn);
	return $result;
}

function driver_db_result($result, $offset)
{
	return mysql_result($result, $offset);
}

function driver_db_fetch_row($result)
{
	return mysql_fetch_row($result);
}

function driver_db_fetch_array($result, $flag = MYSQL_ASSOC)
{
	return mysql_fetch_array($result, $flag);
}

function driver_db_free_result($result)
{
	@mysql_free_result($result);
}

function driver_db_num_rows($result)
{
	return mysql_num_rows($result);
}

function driver_db_insert_id($db = '') 
{
	$db_conn = & Registry::get('runtime.dbs.' . (!empty($db) && Registry::get('runtime.dbs.' . $db) ? $db : 'main'));

	return mysql_insert_id($db_conn);
}

function driver_db_affected_rows($db = '')
{
	$db_conn = & Registry::get('runtime.dbs.' . (!empty($db) && Registry::get('runtime.dbs.' . $db) ? $db : 'main'));

	return mysql_affected_rows($db_conn);
}

function driver_db_errno($db = '')
{
	$db_conn = & Registry::get('runtime.dbs.' . (!empty($db) && Registry::get('runtime.dbs.' . $db) ? $db : 'main'));

	static $skip_error_codes = array (
		1091, // column exists/does not exist during alter table
		1176, // key does not exist during alter table
		1050, // table already exist 
		1060  // column exists
	);

	$errno = mysql_errno($db_conn);

	return in_array($errno, $skip_error_codes) ? 0 : $errno;
}

function driver_db_error($db = '')
{
	$db_conn = & Registry::get('runtime.dbs.' . (!empty($db) && Registry::get('runtime.dbs.' . $db) ? $db : 'main'));

	return mysql_error($db_conn);
}

function driver_db_close($db = '')
{
	$db_conn = & Registry::get('runtime.dbs.' . (!empty($db) && Registry::get('runtime.dbs.' . $db) ? $db : 'main'));

	return @mysql_close($db_conn);
}

function reconnectToSlave($query,$controllersArray,$type,&$isConnectSlave){
	$controller = 'index';
        $request = $_REQUEST['dispatch']; 
        if(is_array($request) && isset($request[0])){
           $request = $request[0];
        }
        if($request!='' && is_string($request)){
	    $controllerArr = explode(".",$request);
	    $controller = $controllerArr[0];
	}
	$query = strtolower(trim($query));	
	if (isset($_REQUEST['rquest']))  {
        if ($_REQUEST['rquest'] == 'cart' || $_REQUEST['rquest'] == 'checkout' || $_REQUEST['rquest'] == 'payment') {
            $controller = 'rquest';
        }
    }
	if(!in_array($controller,$controllersArray) && substr($query,0,6) == 'select'){
	     $isConnectSlave = true;
	}
	$is_slave = check_query_for_slave($query);
	if($is_slave == 'Y' && substr($query,0,6) == 'select'){
		$isConnectSlave = true;	
	}
	if($isConnectSlave == true ){
		$db = $type;
		$db_conn = & Registry::get('runtime.dbs.' . $db);
		if($db_conn == null){
		    $db_conn = db_initiate(Registry::get('config.'.$type.'.db_host'), Registry::get('config.'.$type.'.db_user'), Registry::get('config.'.$type.'.db_password'),Registry::get('config.'.$type.'.db_name'),$db,TABLE_PREFIX);
		}
	}
        $db = (!empty($db) && Registry::get('runtime.dbs.' . $db)) ? $db : 'main';
        return $db;
          
}

function check_query_for_slave($query){
	$execlude_list = Registry::get('config.checkout_slave_query');
	$regex_pattern = "'/".implode('|',$execlude_list)."/i'";
	$regex_pattern = preg_replace('/[^a-zA-Z0-9.,\/|\_=]/','', $regex_pattern);
	$is_slave = 'N';
	$query = strtolower(preg_replace('/[^a-zA-Z0-9.,\/|\_=]/','', $query));
	preg_match($regex_pattern, $query, $matches);
	//echo '<pre>';print_r($matches);echo '</pre>';
	if(!empty($matches)){
		$is_slave = 'Y';
	}
	return $is_slave;
}

?>
