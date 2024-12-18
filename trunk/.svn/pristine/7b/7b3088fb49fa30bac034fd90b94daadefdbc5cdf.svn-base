<?php 

if ( !defined('AREA') ) { die('Access denied'); }

function fn_get_order_details($order_id)
{	
	$company_data = db_get_row("SELECT cc.company, cc.address, cc.state 
			FROM cscart_companies as cc, cscart_orders as co 
			WHERE co.order_id='$order_id' and co.company_id=cc.company_id ");
	return $company_data;
}

function fn_get_product_details($product_id)
{	
	$product_data = db_get_row("SELECT cpd.product, cp.product_code 
			FROM cscart_products as cp, cscart_product_descriptions as cpd 
			WHERE cp.product_id='$product_id' and cp.product_id=cpd.product_id ");
	return $product_data;
}

?>