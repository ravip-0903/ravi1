<?php
if (! defined('AREA')) {
    die('Access denied');
}


function devtools_update_overlay($string)
{
    fn_echo("
        <script language='javascript'>
        document.getElementById('overlay').innerHTML='$string'+'&nbsp;<img src=\'http://localhost/CSCart2.1.4-3/addons/stagingsite/resources/ajax-loader.gif\' />';
        </script>"
    ); 
}
function devtools_append_to_page($string)
{
    fn_echo("
    	$string");/*
        <script language='javascript'>
        document.getElementById('pagewrapper').innerHTML=document.getElementById('pagewrapper').innerHTML + '$string';
        </script>"
    ); */
    	
}

?>
