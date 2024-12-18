<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty extractimg modifier plugin
 *
 * Type:     modifier<br>
 * Name:     extractimg<br>
 * Purpose:  Extract image tag from passed string
 * @author   Amit Kejriwal
 * @param string
 * @return string
 */
function smarty_modifier_extractimg($string)
{
	if (is_array($string)) {
            return '';
	} else {
            preg_match('/(<img[^>]+>)/i', $string, $matches);
        }
        if($matches[0]){
            return str_replace("\n", "", $matches[0]);
        }
}

?>
