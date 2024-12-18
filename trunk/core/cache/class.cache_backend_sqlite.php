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


if (!defined('AREA')) { die('Access denied'); }

/**
 * Cache backend class, implements 4 methods: 
 * get - get data from the cache storage
 * set - set data to the cache storage
 * clear - clear expired data
 * save_handlers - save cache handlers
 * cleanup - delete all cached data
 */
class Cache_backend_sqlite {
	static private $db;
	static private $db_fetch;
	static private $db_class;
	static private $_cache_handlers = array();
	static private $_cache_handlers_is_updated = false;

	static function set($name, $data, $condition, $cache_level = NULL)
	{
		$fname = $name . '.' . $cache_level;

		if (!empty($data)) {

			$expiry = ($cache_level == CACHE_LEVEL_TIME) ? TIME + $condition : 0;
			self::$db->query("REPLACE INTO cache (name, data, expiry, tags) VALUES ('$fname', " . self::_db_escape(serialize($data)) . ", '" . $expiry . "', '$name')");

			if ($cache_level != CACHE_LEVEL_TIME) {
				foreach ($condition as $table) {
					if (empty(self::$_cache_handlers[$table])) {
						self::$_cache_handlers[$table] = array();
					}

					self::$_cache_handlers[$table][$name] = true;
					self::$_cache_handlers_is_updated = true;
				}
			}
		}

	}

	static function get($name, $cache_level = NULL)
	{
		$_lifetime = 0;
		$fname = $name . '.' . $cache_level;

		$fe = self::_db_fetch("SELECT data, expiry FROM cache WHERE name = '$fname'" . ($cache_level == CACHE_LEVEL_TIME ? ' AND expiry > ' . TIME : ''));

		if (!empty($name) && !empty($fe)) {
			$_cache_data = (!empty($fe['data'])) ? @unserialize($fe['data']) : false;
			if ($_cache_data !== false) {
				return array($_cache_data);
			}
			
			// clean up the cache
			self::$db->query("DELETE FROM cache WHERE name = '$fname'");
		}

		return false;
	}

	static function clear($changed_tables)
	{
		$tags = array();
		foreach ($changed_tables as $table => $flag) {
			if (!empty(self::$_cache_handlers[$table])) {
				$tags = fn_array_merge($tags, array_keys(self::$_cache_handlers[$table]), false);
			}
		}

		self::$db->query("DELETE FROM cache WHERE tags IN ('" . implode("', '", $tags) ."')");

		return true;
	}

	static function save_handlers()
	{
		if (self::$_cache_handlers_is_updated == true) {
			self::$db->query("REPLACE INTO cache (name, data) VALUES ('cache_handlers', " . self::_db_escape(serialize(self::$_cache_handlers)) . ")");
		}
	}

	static function cleanup()
	{
		return true;
	}

	static function init()
	{
		$init_db = false;
		if (!file_exists(DIR_CACHE . 'cache.db')) {
			$init_db = true;
		}

		self::$db = self::_db_init();

		if ($init_db == true) { 
			self::$db->query('CREATE TABLE cache (name varchar(128), data text, expiry int, tags varchar(64), PRIMARY KEY(name))');
			self::$db->query('CREATE INDEX tags ON cache (tags)');
			self::$db->query('CREATE INDEX exp ON cache (name, expiry)');
		}

		$fe = self::_db_fetch("SELECT data FROM cache WHERE name = 'cache_handlers'");
		self::$_cache_handlers = !empty($fe['data']) ? @unserialize($fe['data']) : array();

		return true;
	}

	static function _db_fetch($query)
	{
		$res = self::$db->query($query);
		$fe = array();
		if (!empty($res)) {
			if (self::$db_class == 'SQLite3') {
				$fe = $res->fetchArray(self::$db_fetch);
			} else {
				$fe = $res->fetch(self::$db_fetch);
			}
		}

		return $fe;
	}

	static function _db_escape($string)
	{
		if (self::$db_class == 'SQLite3') {
			return "'" . SQLite3::escapeString($string) . "'";
		} elseif (self::$db_class == 'SQLiteDatabase') {
			return "'" . sqlite_escape_string($string) . "'";
		} else {
			return self::$db->quote($string);
		}
	}
	
	function _db_init()
	{
		$pdo_sqlite = false;
		if (!class_exists('SQLite3') && class_exists('PDO')) {
			$drivers = PDO::getAvailableDrivers();
			if (!empty($drivers)) {
				foreach ($drivers as $driver) {
					if (strpos($driver, 'sqlite') !== false) {
						$pdo_sqlite = true;
						break;
					}
				}
			}
		}
		
		$init_prefix = '';
		if (class_exists('SQLite3')) {
			self::$db_class = 'SQLite3';
			self::$db_fetch = SQLITE3_ASSOC;

		} elseif (class_exists('PDO') && $pdo_sqlite) {
			self::$db_class = 'PDO';
			self::$db_fetch = PDO::FETCH_ASSOC;
			$init_prefix = 'sqlite://';

		} elseif (class_exists('SQLiteDatabase')) {
			self::$db_class = 'SQLiteDatabase';
			self::$db_fetch = SQLITE_ASSOC;
		} else {
			die('SQLITE cache data storage is not supported. Please choose another one.');
		}

		return new self::$db_class($init_prefix . DIR_CACHE . 'cache.db');
	}
}

?>