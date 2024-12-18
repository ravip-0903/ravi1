<?php


//   apc_clear_cache();
//  apc_clear_cache('user');
//  apc_clear_cache('opcode');
//  echo json_encode(array('success' => true));


if (apc_clear_cache() && apc_clear_cache('user') && apc_clear_cache('opcode')){

               echo 'APC Cache Cleared!';
} else {
               echo 'APC Clearing Failed!';
}
//        print '<pre>';
//        print_r(apc_cache_info());
//        print '</pre>';

?>
