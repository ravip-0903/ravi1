<?php
if ( !defined('AREA') ) { die('Access denied'); }
fn_register_hooks('get_product_data_more',//from product details page
				  'get_products_post','send_mail_pre', //to modify the search
						'change_order_status');
?>
