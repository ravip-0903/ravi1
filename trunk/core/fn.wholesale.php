<?php
define('WHOLESALE_CHECKOUT_ALLOWED',1);
define('NOT_UNIFORM_CART',2);
define('NO_MEMBERSHIP_PRODUCT',3);


function is_pro_member($user_id,$mtype_id)
{
  if(empty($user_id) OR empty($mtype_id) OR !is_numeric($user_id) OR !is_numeric($mtype_id))
  {
    return false;
  }
  
  $current_time = time();
  $query= "SELECT count(*) as count FROM clues_membership_details WHERE user_id=$user_id AND expiry_date>$current_time 
           AND status=1 AND mtype_id=$mtype_id";
  $data = db_get_field($query);
  if($data > 0)
  {
    return true;
  }
  return false;
}

//this function assigns the wholesale membership on the basis of order id
function assign_wholesale_membership($user_id,$order_id,$mtype_id=1)
{
	if(!Registry::get('config.enable_wholesale_feature'))
	{
		return false;
	}
	
	if(is_order_already_member($order_id,$mtype_id))
	{
		return false;
	}
	
	$whole_membership_product_id=Registry::get('config.ws_subscription_product');
	$sql="select od.order_id,od.product_id 
				from cscart_order_details od 
				where od.order_id =$order_id and od.product_id=$whole_membership_product_id";
	$result=db_get_row($sql);
	
	if(!empty($result))
	{
		
		$sql="update clues_membership_details set status=0 where user_id=$user_id and mtype_id=$mtype_id";
		db_query($sql);
		
		$created_date=time();
		$expiry_date=$created_date+Registry::get('config.wholesale_membership_validity_period')*86400;
		
		$sql="insert into clues_membership_details (user_id,order_id,mtype_id,expiry_date,status,created,updated_by,last_updated)
					values (".$user_id.",".$order_id.",1,$expiry_date,1,$created_date,".$_SESSION['auth']['user_id'].",$created_date);";
		db_query($sql);

		return true;
	}	
	return false;
}

function is_order_already_member($order_id,$mtype_id)
{
	$sql="select count(*) as count from clues_membership_details where order_id=$order_id and mtype_id=$mtype_id";
	$count=db_get_field($sql);
	if($count>0)
	{
		return true;
	}
	return false;
}

function complete_wholesale_order($order_id)
{
	fn_change_order_status($order_id,'C');
}

function is_uniform_cart($products)
{
  if(!empty($products))
  {
    $products     = array_unique($products);
    $product_str  = implode(',',$products);
    $product_cnt  = count($products);
    $query="SELECT count(*) as count FROM cscart_products WHERE product_id IN ($product_str) AND is_wholesale_product=1";
    $data = db_get_field($query);
    if($data == $product_cnt OR $data == 0)
    {
     return true;
    }
  }
  return false;
}

function can_add_in_cart($products_arr,$product_id)
{
  if(!Registry::get('config.enable_wholesale_feature'))
         return true;
  $products = get_products_in_cart($products_arr);
  if(empty($products)) return true;
  if(empty($product_id)) return false;
  array_push($products,$product_id);
  return is_uniform_cart($products);   
}

function is_wholesale_product_in_cart($products)
{
  if(!empty($products))
  {
    $product_str  = implode(',',$products);
    $query="SELECT count(*) as count FROM cscart_products WHERE product_id IN ($product_str) AND is_wholesale_product=1";
    $data = db_get_field($query);
    if($data > 0)
    {
     return true;
    }
  }
  return false;
}

function is_membership_product_in_cart($products)
{
  $membership_product = Registry::get('config.ws_subscription_product');

  foreach($products as $key => $product_id)
  {
    if($product_id ==$membership_product)
    {
      return true;
    }
  }
  return false;
}

function get_wholesale_products($products)
{
 if(!empty($products))
  {
    $product_str  = implode(',',$products);
    $query="SELECT product_id FROM cscart_products WHERE product_id IN ($product_str) AND is_wholesale_product=1";
    $data = db_get_array($query);
    if($data > 0)
    {
     $product_ids = array_values($data);
    }
    return $product_ids;
  }
  return false;
}

function get_products_in_cart($products_arr)
{
  $products = array();
  foreach($products_arr as $key => $value)
  {
    if(!empty($value['product_id']))
    {
      if(!in_array($value['product_id'],$products))
       array_push($products,$value['product_id']);
    }
  }
  return $products;  
}

function is_wholesale_checkout_allowed($products_arr,$user_id,$mtype_id)
{
  if(!Registry::get('config.enable_wholesale_feature'))
     return WHOLESALE_CHECKOUT_ALLOWED;
  $products  = get_products_in_cart($products_arr);
  
  if(!is_uniform_cart($products))
  {
    return NOT_UNIFORM_CART; 
  }
   
  if(is_wholesale_product_in_cart($products) AND !(is_membership_product_in_cart($products) OR is_pro_member($user_id,$mtype_id)))
  {
    return NO_MEMBERSHIP_PRODUCT;  
  }

  return WHOLESALE_CHECKOUT_ALLOWED;
}


/**
This function takes 
@products_data - aray of products with their details
@user_id - id of user who is placing order
@membership_type - type of membership
@edit_step - checkout step

--------------

This method will check all the products in the cart or order and verify that if the user is not a member for wholesale
and is purchasing wholesale product then return true to take the user to step three
if on step three then show notification and give option to subscribe

Returns:
true : redirect user to step three of checkout
false : do not redirect the user

*/
function notify_wholesale_subscription($products_data,$user_id,$membership_type,$edit_step='')
{
  if(!Registry::get('config.enable_wholesale_feature'))
  {
    return false;
  }

  if(empty($products_data) || empty($membership_type))
  {
    return false;
  }
 
  if ($edit_step == 'step_three' AND defined('AJAX_REQUEST')) 
  {
    return false;
  } 
  $checkout_response  = is_wholesale_checkout_allowed($products_data,$user_id,$membership_type);
  if($checkout_response != WHOLESALE_CHECKOUT_ALLOWED)
  {
    if($edit_step == 'step_three')
    {
      if($checkout_response == NO_MEMBERSHIP_PRODUCT)
      {
        fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('wholesale_subscription_notification_lang_new'));  
      }else if($checkout_response == NOT_UNIFORM_CART)
      {
        fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('wholesale_not_uniform_cart'));  
      }
      return false;
    }
    else
    {
      return true;  
    }
  }
  return false;
}

