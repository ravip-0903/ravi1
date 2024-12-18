<?php
define('AREA', 'A');
define('AREA_NAME', 'admin');
require dirname(__FILE__) . '/../prepare.php';
require dirname(__FILE__) . '/../init.php';
include dirname(__FILE__).'/../addons/log_parser/tail.php';

$tailfile = isset($_REQUEST['file'])?urldecode($_REQUEST['file']):"./mail";

$mytail = new tail($tailfile);


if (isset($_REQUEST['grep'])) {
    $mytail->setGrep($_REQUEST['grep']);
}

if (isset($_REQUEST['show']) && is_numeric($_REQUEST['show'])){
    $mytail->setNumberOfLines($_REQUEST['show']);
}

echo $mytail->output(HIGHLIGHT_BOLD+OL_LIST);


?> 
