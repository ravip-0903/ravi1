<?php 

if ( !defined('AREA') ) { die('Access denied'); }

function fn_my_changes_get_product_data_more()
{
    $root_categories = fn_get_subcategories(0);
	foreach ($root_categories as $k => $v) {
		$root_categories[$k]['subcategories'] = fn_get_categories_tree($v['category_id']);
	}
	return $root_categories;
}

function fn_list_tabs($nav)
{	
	$GLOBALS['topLinks'][] = $nav;
	return $GLOBALS['topLinks'];
}

function fn_get_company_contactperson($company_id)
{
	$merchant_name = db_get_row("SELECT firstname, lastname FROM cscart_users WHERE company_id=$company_id");
	return ($merchant_name);
}
function fn_my_changes_payment_methods()
	{
		return db_get_array("SELECT cscart_payments.payment_id, cscart_payment_descriptions.payment FROM cscart_payments 
LEFT JOIN cscart_payment_descriptions ON (cscart_payments.payment_id = cscart_payment_descriptions.payment_id)
WHERE cscart_payments.status='A'");
	}
	
	function get_payment_methods($products)
	{
		$key = '';
		$common_payment_gateway = TRUE;
		foreach($products as $product)
		{
			$allowed_payment_method = db_get_row("select payment_method from product_payment_method where product_id='".$product['product_id']."'");
			if($allowed_payment_method['payment_method']!='') {
				$payment_methods[$product['product_id']] = explode(',',$allowed_payment_method['payment_method']);
				if($key =='')
				{
					$key = $product['product_id'];
				}
			}
			
		}
		
		$first_array = $payment_methods[$key];
		
		if(count($payment_methods)>0) {
			foreach($payment_methods as $payment_method)
			{	
				$final_array = array_intersect($first_array,$payment_method);
				//echo '<pre>count : '.count($final_array);print_r($final_array);
				if(count($final_array) == '0')
				{
					$common_payment_gateway = FALSE;
					break;
				}			
			}
			if($common_payment_gateway == FALSE) {
				$final_array = array();
				$final_array['msg'] = 'no_match_found';
				return $final_array;
			} elseif($common_payment_gateway == TRUE && count($final_array) > 0) {
				$final_array['msg'] = 'match_found';
				return($final_array);
		}
		} else {
			$final_array = array();
			$final_array['msg'] = 'no_method_alloted';
			return $final_array;
		}
	}
?>
