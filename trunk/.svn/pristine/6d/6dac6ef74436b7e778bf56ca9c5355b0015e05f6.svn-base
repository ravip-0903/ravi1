<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.footerformat.php
 * Type:     function
 * Name:     footerformat
 * Purpose:  custom footer formating of a passed in var
 * -------------------------------------------------------------
 */


function smarty_insert_footerformat($params, &$smarty)
{
    require_once 'simple_html_dom.php';
    $content = $params['content'];
    $url = $params['url'];
    $url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REDIRECT_URL'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REDIRECT_URL'];
    $url = $_SERVER['REDIRECT_URL'];
    
    
    $html = str_get_html($content);
    $output = "<ul>";
    foreach($html->find('a') as $element)
    {
       if($element->href==$url)
       {
           $output = $output."<li class=\"footer-link-selected-page\">".$element."</li>";
       }else {
            $output = $output."<li>".$element."</li>";
       }
    }
    $output = $output."</ul>";
    
    /*$f = fopen("c://test.txt", 'w');
    fwrite($f, $content."\n\n".$output);
    fclose($f);*/
    
   //print_r($_SERVER);
    return $output;
}
?>