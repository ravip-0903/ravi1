<?php
if (! defined('AREA')) {
    die('Access denied');
}
define('DS', DIRECTORY_SEPARATOR); // I always use this short form in my code.

function fn_stagingsite_init_templater($view, $view_mail)
{
    $view->assign("stagingsite_cartPathName", dirname($_SERVER['SCRIPT_NAME']));
    
}
/**
 * Recursively copy all files and folders from a path to a destination, missing any folders in the excludeDirs array
 * Enter description here ...
 * @param unknown_type $path
 * @param unknown_type $dest
 * @param unknown_type $excludeDirs
 */
function copy_r ($path, $dest, $excludeDirs)
{
    if (is_dir($path)) {
        @mkdir($dest);
        $objects = scandir($path);
        if (sizeof($objects) > 0) {
            foreach ($objects as $file) {
                $breakReq = false;
                for ($i = 0; $i < count($excludeDirs); $i ++)
                {
                    if ($file == "." || $file == ".." ||
                     $file == $excludeDirs[$i]) {
                        $breakReq = true;
                    }
                }
                if ($breakReq)
                    continue;
                if (is_dir($path . DS . $file)) {
                    copy_r($path . DS . $file, $dest . DS . $file, $excludeDirs);
                    //if ((time() % 10) == 0) //only print once per second 
                    //{
                        append_to_page($dest. DS . $file . " <br />" . PHP_EOL);
                    //}
                } else {
                    copy($path . DS . $file, $dest . DS . $file);
                }
            }
        }
        return true;
    } elseif (is_file($path)) {
        return copy($path, $dest);
    } else {
        return false;
    }
}
function rrmdir ($dir)
{
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dir . "/" . $object) == "dir")
                    rrmdir($dir . "/" . $object);
                else
                {
                    unlink($dir . "/" . $object);
                }
            }
        }
        reset($objects);
        rmdir($dir);
    }
}

function update_overlay($string)
{
    fn_echo("
        <script language='javascript'>
        document.getElementById('overlay').innerHTML='$string'+'&nbsp;<img src=\'addons/stagingsite/resources/ajax-loader.gif\' />';
        </script>"
    ); 
}
function append_to_page($string)
{
    fn_echo("
    	$string".
    	"<script language='javascript'>
        refresh();
        </script>");
        /*
        <script language='javascript'>
        document.getElementById('pagewrapper').innerHTML=document.getElementById('pagewrapper').innerHTML + '$string';
        </script>"
    ); */
}

function backupDBtoFile($dbdump_filename)
{
	//$dbdump_filename = empty($_REQUEST['dbdump_filename']) ? $filename : $_REQUEST['dbdump_filename'];

	if (!fn_mkdir(DIR_DATABASE . 'backup')) {
		$err_msg = str_replace('[directory]', DIR_DATABASE . 'backup',fn_get_lang_var('text_cannot_create_directory'));
		fn_set_notification('E', fn_get_lang_var('error'), $err_msg);
		return array(CONTROLLER_STATUS_REDIRECT, "stagingsite.manage");
	}
	$dump_file = DIR_DATABASE . 'backup/' . $dbdump_filename;
	if (is_file($dump_file)) {
		if (!is_writable($dump_file)) {
			fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('dump_file_not_writable'));
			return array(CONTROLLER_STATUS_REDIRECT, "stagingsite.manage");
		}
	}

	$fd = @fopen(DIR_DATABASE . 'backup/' . $dbdump_filename, 'w');
	if (!$fd) {
		fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('dump_cant_create_file'));
		return array(CONTROLLER_STATUS_REDIRECT, "stagingsite.manage");
	}

	// Log database backup
	fn_log_event('database', 'backup');

	// set export format
	db_query("SET @SQL_MODE = 'MYSQL323'");

	fn_start_scroller();
	$create_statements = array();
	$insert_statements = array();
	
	$dbdump_tables = empty($_REQUEST['dbdump_tables']) ? array() : $_REQUEST['dbdump_tables'];

	// get status data
	$t_status = db_get_hash_array("SHOW TABLE STATUS", 'Name');

	foreach ($dbdump_tables as $k => $table) {
		if (!empty($_REQUEST['dbdump_schema']) && $_REQUEST['dbdump_schema'] == 'Y') {
			append_to_page('<br>' . fn_get_lang_var('backupping_schema') . ': <b>' . $table . '</b>');
			fwrite($fd, "\nDROP TABLE IF EXISTS " . $table . ";\n");
			$__scheme = db_get_row("SHOW CREATE TABLE $table");
			fwrite($fd, array_pop($__scheme) . ";\n\n");
		}

		if (!empty($_REQUEST['dbdump_data']) &&  $_REQUEST['dbdump_data'] == 'Y') {
			append_to_page('<br />' . fn_get_lang_var('backupping_data') . ': <b>' . $table . '</b>&nbsp;&nbsp;');
			$total_rows = db_get_field("SELECT COUNT(*) FROM $table");

			// Define iterator
			if (!empty($t_status[$table]) && $t_status[$table]['Avg_row_length'] < DB_MAX_ROW_SIZE) {
				$it = DB_ROWS_PER_PASS;
			} else {
				$it = 1;
			}
			for ($i = 0; $i < $total_rows; $i = $i + $it) {
				$table_data = db_get_array("SELECT * FROM $table LIMIT $i, $it");
				foreach ($table_data as $_tdata) {
					$_tdata = fn_add_slashes($_tdata, true);
					$values = array();
					foreach ($_tdata as $v) {
						$values[] = ($v !== null) ? "'$v'" : 'NULL';
					}
					fwrite($fd, "INSERT INTO $table (`" . implode('`, `', array_keys($_tdata)) . "`) VALUES (" . implode(', ', $values) . ");\n");
				}

				//append_to_page(' .');
			}
		}
	}
	
	fclose($fd);
	@chmod(DIR_DATABASE . 'backup/' . $dbdump_filename, DEFAULT_FILE_PERMISSIONS);

	if ($_REQUEST['dbdump_compress'] == 'Y') {
		fn_echo('<br />' . fn_get_lang_var('compressing_backup') . '...');

		fn_compress_files($dbdump_filename . '.tgz', $dbdump_filename, dirname($dump_file));
		unlink($dump_file);
	}

	fn_stop_scroller();
}

function db_import_sql_file_to_db($file, $dbName, $buffer = 16384, $show_status = true, $show_create_table = 1, $check_prefix = false, $track = false, $skip_errors = false)
{
    
	if (file_exists($file)) {
	    
		$path = dirname($file);
		$file_name = basename($file);
		$tmp_file = $path . "/$file_name.tmp";

		$executed_queries = array();
		if ($track && file_exists($tmp_file)) {
			$executed_queries = unserialize(fn_get_contents($tmp_file));
		}

		if ($skip_errors) {
			$_skip_errors = Registry::get('runtime.database.skip_errors');
			Registry::set('runtime.database.skip_errors', true);
		}

		include DIR_ROOT."/config.local.php";
        $link = mysql_connect($config['db_host'], $config['db_user'], $config['db_password']) or print(mysql_error());
        mysql_select_db($dbName) or print(mysql_error($link));
        		
		$fd = fopen($file, 'r');
		if ($fd) {
			$ret = array();
			$rest = '';
			while (!feof($fd)) {
				$str = $rest.fread($fd, $buffer);
				$rest = fn_parse_queries($ret, $str);

				if (!empty($ret)) {
					foreach ($ret as $query) {
						if (!in_array($query, $executed_queries)) {
							if ($show_create_table && preg_match('/CREATE\s+TABLE\s+`?(\w+)`?/i', $query, $matches)) {
								if ($show_create_table == 1) {
									$_text = fn_get_lang_var('creating_table');
								} elseif ($show_create_table == 2) {
									$_text = 'Creating table';
								}
								$table_name = $check_prefix ? fn_check_db_prefix($matches[1]) : $matches[1];
							    append_to_page('<br />' . $_text . ': <b>' . $table_name . '</b>');
							}

							if ($check_prefix) {
								$query = fn_check_db_prefix($query);
							}
							 //fn_echo("<p>$query</p>");
							 mysql_query($query);

							if ($track) {
								$executed_queries[] = $query;
								fn_put_contents($tmp_file, serialize($executed_queries));
							}

							if ($show_status) {
								//append_to_page(' .');
							}
						}
					}
					$ret = array();
				}
			}

			fclose($fd);
			return true;
		}

		if ($skip_errors) {
			Registry::set('runtime.database.skip_errors', $_skip_errors);
		}
	}

	return false;
}


?>
