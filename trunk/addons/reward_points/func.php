<?php
/***************************************************************************
*                                                                          *
*   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
*                                                                          *
* This  is  commercial  software,  only  users  who have purchased a valid *
* license  and  accept  to the terms of the  License Agreement can install *
* and use this program.                                                    *
*                                                                          *
****************************************************************************
* PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
* "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
****************************************************************************/


//
// $Id$
//
if ( !defined('AREA') ) { die('Access denied'); }

//
// Get product/category/global earned points list
//

function fn_get_reward_points($object_id, $object_type = PRODUCT_REWARD_POINTS, $usergroup_ids = array())
{
	$op_suffix = (Registry::get('addons.reward_points.consider_zero_values') == 'Y') ? '=' : '';
	
	if (!empty($usergroup_ids)) {
		$order_by = 'amount_type ' . (POINTS_FOR_USERGROUP_BY_AMOUNT_TYPE == 'A' ? 'ASC' : 'DESC') . ', amount ' . (Registry::get('addons.reward_points.several_points_action') == 'min' ? 'ASC' : 'DESC');
		return db_get_row("SELECT *, amount AS pure_amount FROM ?:reward_points WHERE object_id = ?i AND object_type = ?s AND amount >$op_suffix 0 AND usergroup_id IN(?n) ORDER BY ?p LIMIT 1", $object_id, $object_type, $usergroup_ids, $order_by);
	} else {
		return db_get_hash_array("SELECT *, amount AS pure_amount FROM ?:reward_points WHERE object_id = ?i AND object_type = ?s AND amount >$op_suffix 0 ORDER BY usergroup_id", 'usergroup_id', $object_id, $object_type);
	}
}


function fn_add_reward_points($object_data, $object_id = 0, $object_type = GLOBAL_EARNED_POINTS)
{ 
	$object_data = fn_array_merge($object_data, array('object_id' => $object_id, 'object_type' => $object_type));
	return db_query("REPLACE INTO ?:reward_points ?e", $object_data);
}

function fn_reward_points_get_cart_product_data($product_id, &$_pdata, $product)
{
	$_pdata = fn_array_merge($_pdata, db_get_row("SELECT is_pbp, is_oper, is_op FROM ?:products WHERE product_id = ?i", $product_id));
	if (isset($product['extra']['configuration'])) {
		$_pdata['extra']['configuration'] = $product['extra']['configuration'];	
	}
}

function fn_reward_points_calculate_cart(&$cart, &$cart_products, &$auth)
{
	fn_set_hook('reward_points_cart_calculation', $cart_products, $cart, $auth);
	
	// calculating price in points
	if (isset($cart['points_info']['total_price'])) {
		unset($cart['points_info']['total_price']);
	}

	if (Registry::get('addons.reward_points.price_in_points_order_discount') == 'Y' && !empty($cart['subtotal_discount']) && !empty($cart['subtotal'])) {
		$price_coef = 1 - $cart['subtotal_discount'] / $cart['subtotal'];
	} else {
		$price_coef = 1;
	}
	
	foreach ((array) $cart_products as $k => $v) {
		
		fn_set_hook('reward_points_calculate_item', $cart_products, $cart, $k, $v);
		
		if (!isset($v['exclude_from_calculate'])) {
			if (isset($cart['products'][$k]['extra']['points_info'])) {
				/*Modified by clues dev to solve the split order issue when applied clues bucks*/
				//unset($cart['products'][$k]['extra']['points_info']);
				/*Modified by clues dev to solve the split order issue when applied clues bucks*/
			}
			
			
			fn_reward_points_get_additional_product_data($cart_products[$k], $auth, true);
    
			//echo '<pre>';print_r($cart);echo '<pre>';
			if (isset($cart_products[$k]['points_info']['raw_price'])) {
				$cart['products'][$k]['extra']['points_info']['price'] = round($price_coef * $cart_products[$k]['points_info']['raw_price']);
				//$cart['points_info']['total_price'] = (isset($cart['points_info']['total_price']) ?  $cart['points_info']['total_price'] : 0) + $cart['products'][$k]['extra']['points_info']['price'];
				/*modified by chandan to add shipping cost in CB payments */
				$cart['points_info']['total_price'] = (isset($cart['points_info']['total_price']) ?  $cart['points_info']['total_price'] : 0) + $cart['products'][$k]['extra']['points_info']['price'] + ($cart['products'][$k]['shipping_freight'] * $cart['products'][$k]['amount']);
				/*modified by chandan to add shipping cost in CB payments */
			}
		}
	}
	//echo '<pre>';print_r($cart['points_info']);echo '</pre>';//die;
	if ( (!empty($cart['points_info']['in_use']) && (CONTROLLER == 'checkout' || (defined('ORDER_MANAGEMENT') && (MODE == 'totals' || MODE == 'summary' )) || $cart['api'] == 1)) ) {
            fn_set_point_payment($cart, $cart_products, $auth);
	}
	
	// calculating reward points
	if (isset($cart['points_info']['reward'])) {
		
		unset($cart['points_info']['reward']);
	}

	if (isset($cart['points_info']['additional'])) {
		
		$cart['points_info']['reward'] = $cart['points_info']['additional'];
		unset($cart['points_info']['additional']);
	}

	$discount = 0;
	if (Registry::get('addons.reward_points.reward_points_order_discount') == 'Y' && !empty($cart['subtotal_discount']) && !empty($cart['total'])) {
		$discount += $cart['subtotal_discount'];
	}

	if (!empty($cart['points_info']) && !empty($cart['points_info']['in_use']) && !empty($cart['points_info']['in_use']['cost'])) {
		$discount += $cart['points_info']['in_use']['cost'];
	}
	/*modified by chandan to add shipping cost in CB payments */
	$discount = $discount - $cart['shipping_cost'];
	/*modified by chandan to add shipping cost in CB payments */
	
	if ($discount && !empty($cart['subtotal'])) {
		$reward_coef = 1 - $discount / $cart['subtotal'];
	} else {
		$reward_coef = 1;
	}
	
	foreach ((array) $cart_products as $k => $v) {
		
		//fn_set_hook('reward_points_calculate_item', $cart_products, $cart, $k, $v);
	
		if (!isset($v['exclude_from_calculate'])) {
			if (isset($cart_products[$k]['points_info']['reward'])) {
				$product_reward = $v['amount'] * (!empty($v['product_options']) ? fn_apply_options_modifiers($cart['products'][$k]['product_options'], $cart_products[$k]['points_info']['reward']['raw_amount'], POINTS_MODIFIER_TYPE) : $cart_products[$k]['points_info']['reward']['raw_amount']);
				
				$cart['products'][$k]['extra']['points_info']['reward'] = round($product_reward);
				$cart_reward = round($reward_coef * $product_reward);
				$cart['points_info']['reward'] = (isset($cart['points_info']['reward']) ? $cart['points_info']['reward'] : 0) + $cart_reward;
				
			}
		}
	}
//echo '<pre>';  print_r($$cart_products);echo '</pre>';
}

//
//Apply point payment
//
function fn_set_point_payment(&$cart, &$cart_products, &$auth)
{
	$user_info = & Registry::get('user_info');

	$per = floatval(Registry::get('addons.reward_points.point_rate'));
	$user_points = (defined('ORDER_MANAGEMENT')) ? (fn_get_user_additional_data(POINTS, $auth['user_id']) + (!empty($cart['previous_points_info']['in_use']['points']) ? $cart['previous_points_info']['in_use']['points'] : 0)) : $user_info['points'];

	if ($per * $user_points * floatval($cart['total']) > 0) {
		/*Modified by clues dev to solve the split order issue when applied clues bucks*/
		if((isset($cart['processed_order_id']) || isset($cart['rewrite_order_id'])) && CONTROLLER != "order_management"){
			$points_in_use = 0;
			foreach ($cart['products'] as $cart_id=>$v) {
				if (isset($v['extra']['points_info']['discount'])) {
					$points_in_use += $v['extra']['points_info']['discount'];
				}
			}
		}else{
			$points_in_use = $cart['points_info']['in_use']['points'];
		}
		

		/*Modified by clues dev to solve the split order issue when applied clues bucks*/
		if ($points_in_use > $user_points) {
			$points_in_use = $user_points;
			fn_set_notification('W', fn_get_lang_var('warning'), fn_get_lang_var('text_points_exceed_points_on_account'));
		}
		if (empty($cart['points_info']['total_price'])) {
			$cart['points_info']['total_price'] = 0;
		}
		//echo '<pre>';print_r($cart);die;
		if ($points_in_use > $cart['points_info']['total_price']) {
			$points_in_use = $cart['points_info']['total_price'];
                        if(!(isset($cart['express_logging']) && $cart['express_logging']))
                        {
                            fn_set_notification('W', fn_get_lang_var('warning'), fn_get_lang_var('text_points_exceed_points_that_can_be_applied'));
                        }
		}
		
		if (!empty($points_in_use)) {
			$cost = 0;
			/*modified by chandan to add shipping cost in CB payments */
			//$subtotal_discount_coef = (!empty($cart['subtotal_discount'])) ? (1 - $cart['subtotal_discount'] / $cart['total']) : 1;
			/*modified by chandan to add shipping cost in CB payments */
			
			//foreach ($cart['products'] as $cart_id=>$v) {
//				if (isset($v['extra']['points_info']['price'])) {
//					/*modified by chandan to add shipping cost in CB payments */
//					$discount = $points_in_use / $cart['points_info']['total_price'] * ($cart_products[$cart_id]['subtotal'] + $cart['products'][$cart_id]['shipping_freight']) * $subtotal_discount_coef;
//					/*modified by chandan to add shipping cost in CB payments */
//					$cart['products'][$cart_id]['extra']['points_info']['discount'] = fn_format_price($discount);
//					$cost += $discount;
//				}
//			}
			
			$cost = $points_in_use;
			/*added by chandan to calculate the CB part to use in GC orders*/
			$total_cb_used = 0; 
			foreach ($cart['products'] as $cart_id=>$v) {
				if (isset($v['extra']['points_info']['price'])) {
					$line_subt = ($cart_products[$cart_id]['subtotal'] + ($cart['products'][$cart_id]['shipping_freight']*$cart['products'][$cart_id]['amount']));
					$cart_subt = $cart['subtotal'] + $cart['shipping_cost'];
					$discount = ($line_subt/$cart_subt)*$points_in_use;
					$cart['products'][$cart_id]['extra']['points_info']['discount'] = fn_format_price($discount);
					$total_cb_used += fn_format_price($discount);
				}
			}
			if(!isset($cart['processed_order_id']) || !isset($cart['rewrite_order_id'])){
				$cart['cb_for_gc'] = (fn_format_price($points_in_use - $total_cb_used)) ? fn_format_price($points_in_use - $total_cb_used) : 0 ;
			}
			/*added by chandan to calculate the CB part to use in GC orders*/
						
			//$odds = $cart['subtotal'] - $cost - (!empty($cart['subtotal_discount']) ? $cart['subtotal_discount'] : 0);
			/*modified by chandan to add shipping cost in CB payments */
			$odds = $cart['total'] - $cost;
			/*modified by chandan to add shipping cost in CB payments */
			
			if (fn_format_price($odds) < 0) {
				$points_in_use = round($points_in_use * ($cost + $odds) / $cost);
				$cost += $odds;
                                if(!(isset($cart['express_logging']) && $cart['express_logging'])){
				fn_set_notification('W', fn_get_lang_var('warning'), fn_get_lang_var('text_not_all_the_points_will_be_used'));
                                }
			}

			
			if (fn_format_price($cost) && $cost >= 0) {
				$cost = fn_format_price($cost);
				$cart['points_info']['in_use'] = array(
					'points' => $points_in_use,
					'cost' => $cost
				);
				$cart['total'] -= $cost;
				$cart['total'] = fn_format_price($cart['total']);
			} else {
				fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('text_points_cannot_applied_because_subtotal_redeemed'));
				unset($cart['points_info']['in_use']);
			}
		} else {
			unset($cart['points_info']['in_use']);
		}
	} else {
		if (floatval($cart['total']) == 0) {
			fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('text_cannot_apply_points_to_this_order_because_total'));
		}
		if ($user_points <= 0) {
			fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('text_cannot_apply_points_to_this_order_because_user'));
		}
		unset($cart['points_info']['in_use']);
	}
}
// fn_change_user_points_balance_for_expire created by HPRAHI
function fn_change_user_points_balance_for_expire($value, $user_id, $order_id,$clues_bucks_type='')
{ 
	if((int)$value < 0):
	
	
		$sql = "select * from ?:reward_point_changes where user_id='".$user_id."' and expire_on is not NULL and balance>0 order by expire_on";
		$res = db_get_array($sql);
		$remainamount = abs($value);
		foreach($res as $result)
		{
			if($remainamount == 0)
			{
				break;
			}
			$balance = $result['balance'];
			$am = 0;
			if((int)$balance >= (int)$remainamount)
			{
				$am = $remainamount;
				$balance = $balance - $remainamount;
				$remainamount = 0;
			}
			else
			{
				$remainamount = $remainamount - $balance;
				$am = $balance;
				$balance = 0;
			}
			$order_payment_history = $result['order_payment_history'];
			if($order_id)
			{
				$sep = '';
				if($order_payment_history!='')
				{
					$sep = '|';
				}
				$order_payment_history = $order_payment_history . $sep . $order_id . "," . $am. ",Alive";
				$sql = "insert into clues_expiry_clues_bucks_order_relation set change_id='".$result['change_id']."',
																				order_id='".$order_id."',
																				amount='".$am."',
																				status='Alive'";
				db_query($sql);
			}
			$sql = "update ?:reward_point_changes set balance='".$balance."', order_payment_history='".$order_payment_history."' where change_id='".$result['change_id']."'";
			db_query($sql);
		}
	elseif((int)$value > 0):
		if(!empty($order_id)):
		
			$remainamount = abs($value);
			$sql = "select * from ?:reward_point_changes where 
														order_payment_history like '".$order_id.",%' or 
														order_payment_history like '%|".$order_id.",%'
														order by expire_on asc";
			$res = db_get_array($sql);
			$arr_effect = array();
			foreach($res as $result)
			{
				$payment=0;
				$order_payment_history = $result['order_payment_history'];
				$arr = explode("|",$order_payment_history);
				$order_payment_history = "";
				foreach($arr as $str)
				{
					$sep='';
					if($order_payment_history != '')
					{
						$sep = '|';
					}
					$arro = explode(',',$str);
					if($arro[0]==$order_id && $arro[2] == "Alive")
					{
						$payment = $arro[1];
						$order_payment_history = $order_payment_history . $sep . $arro[0] . "," . $arro[1] . "," . "Dead";
						$remainamount = $remainamount - $payment;
						$arr_effect[] = array('points'=>$payment,'change_id'=>$result['change_id'],'expire_on'=>$result['expire_on']);
						
						$sql = "update clues_expiry_clues_bucks_order_relation set status='Dead' where change_id='".$result['change_id']."' and order_id='".$order_id."' and status='Alive'";
						db_query($sql);
						
					}
					else
					{
						$order_payment_history = $order_payment_history . $sep . $arro[0] . "," . $arro[1] . "," . $arro[2];	
					}
				}
				
				$sql = "update ?:reward_point_changes set order_payment_history='".$order_payment_history."' where change_id='".$result['change_id']."'";
				db_query($sql);
			}
			if($remainamount>0)
			{
					$arr_effect[] = array('points'=>$remainamount,'change_id'=>'','expire_on'=>'3000-01-01 12:00:00');
			}
			return $arr_effect;
		endif;
		
	endif;
}
//fn_point_expire();
function fn_point_expire()
{
	return true;
  /*$dat = date('Y-m-d');
	$sql = "select * from ?:reward_point_changes where expire_on is not NULL and date(expire_on) <'".$dat."' and balance>'0'";
	$res = db_get_array($sql);
	if(!defined('POINTS'))
	{
		define('POINTS','W');
	}
	foreach($res as $result)
	{
		
		//echo fn_get_user_additional_data(POINTS, $result['user_id']);die;
		fn_save_user_additional_data(POINTS, fn_get_user_additional_data(POINTS, $result['user_id']) - $result['balance'], $result['user_id']);
		$sql = "update ?:reward_point_changes set balance='0' where change_id='".$result['change_id']."'";
		db_query($sql);
		$change_points = array(
		'user_id' => $result['user_id'],
		'amount' => (-1*$result['balance']),
		'timestamp' => TIME,
		'action' => CHANGE_DUE_EXPIRE,
		'reason' => "Expired-" . $result['change_id'],
		);
		db_query("REPLACE INTO ?:reward_point_changes ?e", $change_points);
	}*/
}
function fn_expire_exception_rewards($change_id)
{
	return true;
  /*$sql = "select * from ?:reward_point_changes where change_id='".$change_id."'";
	$res=db_get_row($sql);
	foreach($res as $result)
	{
		
		//echo fn_get_user_additional_data(POINTS, $result['user_id']);die;
		fn_save_user_additional_data(POINTS, fn_get_user_additional_data(POINTS, $result['user_id']) - $result['balance'], $result['user_id']);
		$sql = "update ?:reward_point_changes set balance='0' where change_id='".$result['change_id']."'";
		db_query($sql);
		$change_points = array(
		'user_id' => $result['user_id'],
		'amount' => (-1*$result['balance']),
		'timestamp' => TIME,
		'action' => CHANGE_DUE_EXPIRE,
		'reason' => "Expired-" . $result['change_id'],
		);
		db_query("REPLACE INTO ?:reward_point_changes ?e", $change_points);
	}*/
}
function fn_check_if_clues_bucks_already_reduced($order_id,$amount)
{
	if($order_id){
		$sql = "select sum(amount) as am from clues_bucks_order_relation where order_id='".$order_id."'";
		$ret = db_get_row($sql);
		if($ret['am']<0 && $amount < 0){
			return false;
		}
		$sql = "insert into clues_bucks_order_relation set order_id='".$order_id."',amount='".$amount."'";
		db_query($sql);
	}
	return true;
}
//third,fourth parameter $order_id,$expire_days are included by HPRAHI
//$clues_bucks_type added by sudhir dt 10 sept 2012
function fn_change_user_points($value, $user_id, $reason = '', $action = CHANGE_DUE_ADDITION, $order_id = '',$expire_days='', $clues_bucks_type='1',$orderid='')
{	
	/*code to log the clues bucks event*/
	$log_amount = $value;
	$log_reason = $reason;
	$updated_by = $_SESSION['auth']['user_id'];
	$update_time = date('Y-m-d h:i:s');
	$to_user = $user_id;
	$sql = "insert into clues_bucks_history (amount, reason, update_by, update_time, to_user) value ('".$log_amount."','".$log_reason."','".$updated_by."','".$update_time."','".$to_user."')";
	db_query($sql);
	/*code to log the clues bucks event*/

	$ret = fn_check_if_clues_bucks_already_reduced($order_id,$value);
	
	if(!$ret)
	{
		return false;
	}
	fn_point_expire();
	if (!empty($value)) {
		//code by ankur to use the expired CB first if they expired and used in a order which gets incomplete
		  if($value<0 and AREA=='A')
		  {
			  fn_reward_handle_expiry_exception($value,$order_id,$user_id);
		  }
		//code end
		$ret = fn_change_user_points_balance_for_expire($value, $user_id, $order_id,$clues_bucks_type);// called by HPRAHI
		fn_save_user_additional_data(POINTS, fn_get_user_additional_data(POINTS, $user_id) + $value, $user_id);
		if(empty($ret))://this condition is put by HPRAHI.  this is previous code inside if
			$time = TIME;
			$change_points = array();
			if(!empty($expire_days))
			{
				$time = $time + (24*60*60*($expire_days));
				$dat = date("Y-m-d H:i:s", $time);
				$change_points = array(
				'user_id' => $user_id,
				'amount' => $value,
				'timestamp' => TIME,
				'action' => $action,
				'reason' => $reason,
				'expire_on' => $dat,
				'balance' => $value,
				'type_id' => $clues_bucks_type,
				'order_id' => $orderid
				);
			}
			else
			{
				$change_points = array(
				'user_id' => $user_id,
				'amount' => $value,
				'timestamp' => TIME,
				'action' => $action,
				'reason' => $reason,
				'type_id' => $clues_bucks_type,
				'order_id' => $orderid
				);
			}
			return db_query("REPLACE INTO ?:reward_point_changes ?e", $change_points);
		else://this condition is put by HPRAHI. this is the new code inside else.this will fall in else if and only if the value is positive and it has an order_id and we have some used values from expirable points .
		
			$remainingamount = $value;
			foreach($ret as $retarr)
			{
				$am = 0;
				if($remainingamount >= $retarr['points'])
				{
					$am = $retarr['points'];
					$remainingamount = $remainingamount - $retarr['points'];
				}
				else
				{
					$am = $remainingamount;
					$remainingamount = 0;
				}
				$change_points = array(
				'user_id' => $user_id,
				'amount' => $am,
				'timestamp' => TIME,
				'action' => $action,
				'reason' => "Due to refund on order:" . $order_id,
				'expire_on'=>$retarr['expire_on'],
				'ref_change_id'=>$retarr['change_id'],
				'balance'=>$am,
				'type_id' => $clues_bucks_type,
				'order_id' => $orderid
				);
				db_query("REPLACE INTO ?:reward_point_changes ?e", $change_points);
				$ch_id=db_get_field("select change_id from ?:reward_point_changes where user_id='".$user_id."' and amount='".$am."' and action='".$action."' and ref_change_id='".$retarr['change_id']."' and balance='".$am."' and reason='Due to refund on order:".$order_id."' ");
				db_query($sql);
				
				//code added by ankur to expire exception cases
				if(date('Y-m-d',strtotime($retarr['expire_on']))<date('Y-m-d'))
				{
					fn_point_expire();
				}
				else
				{
					$sql="select change_id from ?:reward_point_changes where change_id='".$retarr['change_id']."' and reason like 'To Fulfill Order%' and ref_change_id!=0";
					$exc_info=db_get_field($sql);
					if(!empty($exc_info))
					{
						fn_expire_exception_rewards($ch_id);
					}
				}
				//code end
				
			}
			if($remainingamount > 0)
			{
				$am = $remainingamount;
				$change_points = array(
				'user_id' => $user_id,
				'amount' => $am,
				'timestamp' => TIME,
				'action' => $action,
				'reason' => "Due to refund on order:" . $order_id,
				'type_id' => $clues_bucks_type,
				'order_id' => $orderid
				);
				db_query("REPLACE INTO ?:reward_point_changes ?e", $change_points);
			}
			return '';
		endif;//this condition is put by HPRAHI*/
	}
	return '';
}

function fn_reward_points_place_order($order_id, $fake, $fake1, &$cart)
{
	if (!empty($order_id)) {
		if (!empty($cart['points_info']['reward'])) {
			$order_data = array(
				'order_id' => $order_id,
				'type' => POINTS,
				'data' => $cart['points_info']['reward']
			);
			db_query("REPLACE INTO ?:order_data ?e", $order_data);
		}
		//echo '<pre>';print_r($cart);die;
		if (!empty($cart['points_info']['in_use'])) {
			$order_data = array(
				'order_id' => $order_id,
				'type' => POINTS_IN_USE,
				'data' => serialize($cart['points_info']['in_use'])
			);
			db_query("REPLACE INTO ?:order_data ?e", $order_data);
			$sql = "update cscart_orders set cb_used=".$cart['points_info']['in_use']['cost']." where order_id=".$order_id;
			db_query($sql);

		} elseif (!empty($cart['previous_points_info']['in_use'])) {
			db_query("DELETE FROM ?:order_data WHERE order_id = ?i AND type = ?s", $order_id, POINTS_IN_USE);
		}
		/*added by chandan to provide CB for GC orders of shopclues merchant*/		
		if(count($cart['companies'])  == '1' && array_key_exists('0',$cart['companies']) || (empty($cart['products']) && count($cart['companies'])  == '1' && array_key_exists('0',$cart['companies']))){
			/*It will not handle GC with shopclues merchant product. It will give the CB on the total of that order instead of total of GC*/
			$cb_on_gc_in_pct = (Registry::get('config.cb_on_gc_in_pct')) ? Registry::get('config.cb_on_gc_in_pct') : '0';
			$cb_on_gc_max_amt = (Registry::get('config.cb_on_gc_max_amt')) ? Registry::get('config.cb_on_gc_max_amt') : '0';
			
			$reward_amt = fn_format_price(($cart['total'] * $cb_on_gc_in_pct) / 100);
			$reward_amt = ($reward_amt > $cb_on_gc_max_amt) ? $cb_on_gc_max_amt : $reward_amt; 
			if ($reward_amt > 0) {
				$order_data = array(
					'order_id' => $order_id,
					'type' => POINTS,
					'data' => $reward_amt
				);
				db_query("REPLACE INTO ?:order_data ?e", $order_data);
			}
	
			if ($cart['cb_for_gc'] > 0) {
				$total_used_cb = $cart['cb_for_gc'];
				$cb_db_data = db_get_field("select data from cscart_order_data where type='".POINTS_IN_USE."' and order_id='".$order_id."'");
				if($cb_db_data){
					$used_cb = unserialize($cb_db_data);
					$used_cb = $used_cb['cost'];
					$total_used_cb = $used_cb + $cart['cb_for_gc'];
				}
				$cb_data = Array('points' => $total_used_cb,'cost' => $total_used_cb);
				
				$order_data = array(
					'order_id' => $order_id,
					'type' => POINTS_IN_USE,
					'data' => serialize($cb_data)
				);
				db_query("REPLACE INTO ?:order_data ?e", $order_data);
	
			}		
		}
		/*added by chandan to provide CB for GC orders of shopclues merchant*/		
	}
}

function fn_reward_points_get_order_info(&$order, &$additional_data)
{
	if(!empty($order))
	{
		foreach ($order['items'] as $k => $v) {
			if (isset($v['extra']['points_info']['price'])) {
				$order['points_info']['price'] = (isset($order['points_info']['price']) ? $order['points_info']['price'] : 0) + $v['extra']['points_info']['price'];
			}
		}
	}

	if (isset($additional_data[POINTS])) {
		$order['points_info']['reward'] = round($additional_data[POINTS]);
	}
	if (!empty($additional_data[POINTS_IN_USE])) {
		$order['points_info']['in_use'] = unserialize($additional_data[POINTS_IN_USE]);
	}
	
	$order['points_info']['is_gain'] = isset($additional_data[ORDER_DATA_POINTS_GAIN]) ? 'Y' : 'N';
}

function fn_reward_points_change_order_status($status_to, $status_from, &$order_info, $force_notification, $order_statuses, $place_order = false)
{
	
	static $log_id;
	if (isset($order_info['deleted_order'])) {
		if (!empty($log_id)) {
			$log_item = array(
				'action' => CHANGE_DUE_ORDER_DELETE
			);
			db_query("UPDATE ?:reward_point_changes SET ?u WHERE change_id = ?i", $log_item, $log_id);
		}
		return true;
	}
	
	$points_info = (isset($order_info['points_info'])) ? $order_info['points_info'] : array();
 
	if (!empty($points_info)) {
		$reason = array(
			'order_id' => $order_info['order_id'],
			'to' => $status_to,
			'from' =>$status_from
		);
    /*$stringData = 'status_to'.$status_to.'status_from'.$status_from.serialize($order_statuses)."\r\n";
    mail('seekumar@shopclues.com', 'My Subject', $stringData); */
		$action = empty($place_order) ? CHANGE_DUE_ORDER : CHANGE_DUE_ORDER_PLACE;
		// From D(O,P) to I(all the other statuses) HPRAHI
		if ($order_statuses[$status_to]['inventory'] == 'I' && $order_statuses[$status_from]['inventory'] == 'D') {
			// [andyye]
			
			if (!empty($points_info['in_use']['points'])) {
			//if (!fn_sdeep_is_cod_payment($order_info) && !empty($points_info['in_use']['points'])) {
				// increase points in use
				$failed_status = Registry::get('config.left_for_gc_order');
				if(in_array($status_to, $failed_status)){
					// this will add the points back to user account HPRAHI
					
					$log_id = fn_change_user_points($points_info['in_use']['points'], $order_info['user_id'], serialize(fn_array_merge($reason, array('text' => 'text_increase_points_in_use'))), $action,$order_info['order_id'],'', '',$order_info['order_id']);
				}
			}
			//die;
			// [andyye]
			// if ($points_info['is_gain'] == 'Y' && !empty($points_info['reward'])) {
				// if its not cod and isgain =y and point info reward is not null. I think this is related to the blow fn_sdeep condition. It reduces over here and will be gained in below. And I think this condition will never fall in true HPRAHI
				
			if (!fn_sdeep_is_cod_payment($order_info) && $points_info['is_gain'] == 'Y' && !empty($points_info['reward'])) {
				// decrease earned points
				$log_id = fn_change_user_points( - $points_info['reward'], $order_info['user_id'], serialize($reason), $action);
				db_query("DELETE FROM ?:order_data WHERE order_id = ?i AND type = ?s", $order_info['order_id'], ORDER_DATA_POINTS_GAIN);
			}
		}

		if ($order_statuses[$status_to]['inventory'] == 'D' && $order_statuses[$status_from]['inventory'] == 'I') {
			// [andyye]
			
			 if (!empty($points_info['in_use']['points']) && $place_order == '1') {
			//if (!fn_sdeep_is_cod_payment($order_info) && !empty($points_info['in_use']['points'])) {
				// decrease points in use
				$sql = "select change_id from cscart_reward_point_changes where order_id=".$order_info['order_id']." and user_id=".$order_info['user_id']." and amount='-".$points_info['in_use']['points']."'";
			$result = db_get_row($sql);
			if(empty($result)){
					if ($points_info['in_use']['points'] > fn_get_user_additional_data(POINTS, $order_info['user_id'])) {					
						//code by ankur to handle the exception case
						   if(AREA=='A')
						   {						 
							   $ret=fn_reward_handle_expiry_exception($points_info['in_use']['points'],$order_info['order_id'],$order_info['user_id']);
							   if(!$ret)
							   {
								  fn_set_notification('W', fn_get_lang_var('warning'), fn_get_lang_var('text_order_status_has_not_been_changed'));
								  fn_redirect($_POST['redirect_url']);//FIXME redirect in function  => bad style 
							   }
						   }
						   else
						   {
							  // echo '123';die;
							   fn_set_notification('W', fn_get_lang_var('warning'), fn_get_lang_var('text_order_status_has_not_been_changed'));
								fn_redirect($_POST['redirect_url']);//FIXME redirect in function  => bad style
						   }
						//code end
						
					}
					$log_id = fn_change_user_points( - $points_info['in_use']['points'], $order_info['user_id'], serialize(fn_array_merge($reason, array('text' => 'text_decrease_points_in_use'))), $action,$order_info['order_id'],'', '',$order_info['order_id']);
			}
			}
		}

		// [andyye]
		// if ($status_to == 'C' && $points_info['is_gain'] == 'N' && !empty($points_info['reward'])) {
			
		if (!fn_sdeep_is_cod_payment($order_info) && $status_to == 'C' && $points_info['is_gain'] == 'N' && !empty($points_info['reward'])) {
			// increase  rewarded points
			
			//code by ankur to get the expiry days of the automated credited CB from the config 
			if(Registry::get('config.cb_expiry_days'))
			{
				$exp_days=Registry::get('config.cb_expiry_days');
			}
			else
			{
				$exp_days='';
			}
			$sql = "select change_id from cscart_reward_point_changes where order_id=".$order_info['order_id']." and user_id=".$order_info['user_id']." and amount='".$order_info['points_info']['reward']."'";
			$result = db_get_row($sql);
			if(empty($result)){
				//code end
				$log_id = fn_change_user_points($points_info['reward'], $order_info['user_id'], serialize($reason), $action,'',$exp_days,'',$order_info['order_id']);// in this we will not send orderid because its not causing any kind of refund of points but it will add completely new points which do not have any refrence with prevous points. HPRAHI
				//in above function parameter added by ankur to send exp days
				$order_data = array(
					'order_id' => $order_info['order_id'],
					'type' => ORDER_DATA_POINTS_GAIN,
					'data' => 'Y'
				);
				db_query("REPLACE INTO ?:order_data ?e", $order_data);
			}
		}
	}
}


function fn_reward_points_delete_order($order_id)
{
	$order_info = array('deleted_order' => true);
	fn_reward_points_change_order_status('', '', $order_info, array(), array());
}

function fn_reward_points_get_user_info(&$user_data)
{
	$user_data['points'] = round(isset($user_data['user_id']) ? fn_get_user_additional_data(POINTS, $user_data['user_id']) : 0);
}

//
// Update product point price
//
function fn_add_price_in_points($price, $product_id)
{

	if (empty($price['lower_limit'])) {
		$price['lower_limit'] = '1';
	}

	$price['point_price'] = @abs($price['point_price']);
	$price['usergroup_id'] = isset($price['usergroup_id']) ? intval($price['usergroup_id']) : USERGROUP_ALL;

	$_data = fn_check_table_fields($price, 'product_point_prices');
	$_data['product_id'] =	$product_id;

	$result = db_query("REPLACE INTO ?:product_point_prices ?e", $_data);

	return $result;
}

function fn_get_price_in_points($product_id, &$auth)
{
	$usergroup = db_quote(" AND usergroup_id IN (?n)", ((AREA == 'C') ? array_merge(array(USERGROUP_ALL), $auth['usergroup_ids']) : USERGROUP_ALL));
	return db_get_field("SELECT MIN(point_price) FROM ?:product_point_prices WHERE product_id = ?i AND lower_limit = 1 ?p", $product_id, $usergroup);
}


function fn_reward_points_get_additional_product_data(&$product, &$auth, $get_point_info = true)
{
	// Check, if the product has any option points modifiers
	if (empty($product['options_update']) && !empty($product['product_options'])) {
		foreach ($product['product_options'] as $_id => $option) {
			if (!empty($product['product_options'][$_id]['variants'])) {
				foreach ($product['product_options'][$_id]['variants'] as $variant) {
					if (!empty($variant['point_modifier']) && floatval($variant['point_modifier'])) {
						$product['options_update'] = true;
						break 2;
					}
				}
			}
		}
	}
	
	if (isset($product['exclude_from_calculate']) || (isset($product['points_info']['reward']) && !(CONTROLLER == 'products' && MODE == 'options')) || $get_point_info == false) {
		return false;
	}
	
	$main_category = db_get_field("SELECT category_id FROM ?:products_categories WHERE product_id = ?i AND link_type = 'M'", $product['product_id']);
	$candidates = array(
		PRODUCT_REWARD_POINTS  => $product['product_id'],
		CATEGORY_REWARD_POINTS => $main_category,
		GLOBAL_REWARD_POINTS   => 0
	);

	$reward_points = array();
	foreach ($candidates as $object_type => $object_id) {
		$_reward_points = fn_get_reward_points($object_id, $object_type, $auth['usergroup_ids']);

		if ($object_type == CATEGORY_REWARD_POINTS && !empty($_reward_points)) {
			// get the "override point" setting
			$category_is_op = db_get_field("SELECT is_op FROM ?:categories WHERE category_id = ?i", $_reward_points['object_id']);
		}
		if ($object_type == CATEGORY_REWARD_POINTS && (empty($_reward_points) || $category_is_op != 'Y')) {
			// if there is no points of main category of the "override point" setting is disabled
			// then get point of secondary categories
			$secondary_categories = db_get_fields("SELECT category_id FROM ?:products_categories WHERE product_id = ?i AND link_type = 'A'", $product['product_id']);

			if (!empty($secondary_categories)) {
				$secondary_categories_points = array();
				foreach ($secondary_categories as $value) {
					$_rp = fn_get_reward_points($value, $object_type, $auth['usergroup_ids']);
					if (isset($_rp['amount'])) {
						$secondary_categories_points[] = $_rp;
					}
					unset($_rp);
				}

				if (!empty($secondary_categories_points)) {
					$sorted_points = fn_sort_array_by_key($secondary_categories_points, 'amount', (Registry::get('addons.reward_points.several_points_action') == 'min') ? SORT_ASC : SORT_DESC);
					$_reward_points = array_shift($sorted_points);
				}
			}

			if (!isset($_reward_points['amount'])) {
				if (Registry::get('addons.reward_points.higher_level_extract') == 'Y' && !empty($candidates[$object_type])) {
					$id_path = db_get_field("SELECT REPLACE(id_path, '{$candidates[$object_type]}', '') FROM ?:categories WHERE category_id = ?i", $candidates[$object_type]);
					if (!empty($id_path)) {
						$c_ids = explode('/', trim($id_path, '/'));
						$c_ids = array_reverse($c_ids);
						foreach ($c_ids as $category_id) {
							$__reward_points = fn_get_reward_points($category_id, $object_type, $auth['usergroup_ids']);
							if (!empty($__reward_points)) {
								// get the "override point" setting
								$_category_is_op = db_get_field("SELECT is_op FROM ?:categories WHERE category_id = ?i", $__reward_points['object_id']);
								if ($_category_is_op == 'Y') {
									$category_is_op = $_category_is_op;
									$_reward_points = $__reward_points;
									break;
								}
							}
						}
					}
				}
			}
		}

		if (!empty($_reward_points) && (($object_type == GLOBAL_REWARD_POINTS) || ($object_type == PRODUCT_REWARD_POINTS && $product['is_op'] == 'Y') || ($object_type == CATEGORY_REWARD_POINTS && (!empty($category_is_op) && $category_is_op == 'Y')))) {
			// if global points or category points (and override points is enabled) or product points (and override points is enabled)
			$reward_points = $_reward_points;
			break;
		}
	}
	
	if (isset($reward_points['amount'])) {
		if ((defined('ORDER_MANAGEMENT') || CONTROLLER == 'checkout') && isset($product['subtotal']) && isset($product['original_price'])) {
			if (Registry::get('addons.reward_points.points_with_discounts') == 'Y' && $reward_points['amount_type'] == 'P' && !empty($product['discounts'])) {
				$product['discount'] = empty($product['discount']) ? 0 : $product['discount'];
				$reward_points['coefficient'] = (floatval($product['price'])) ? (($product['price'] * $product['amount'] - $product['discount']) / $product['price'] * $product['amount']) / pow($product['amount'], 2) : 0;
			} else {
				$reward_points['coefficient'] = 1;
			}
		} else {
			$reward_points['coefficient'] =(Registry::get('addons.reward_points.points_with_discounts') == 'Y' && $reward_points['amount_type'] == 'P' && isset($product['discounted_price'])) ? $product['discounted_price'] / $product['price'] : 1;
		}

		if (isset($product['extra']['configuration'])) {
			if ($reward_points['amount_type'] == 'P') {
				// for configurable product calc reward points only for base price
				$price = $product['original_price'];
				if (!empty($product['discount'])) {
					$price -= $product['discount'];
				}
				$reward_points['amount'] = $price * $reward_points['amount'] / 100;
			} else {
				$points_info = Registry::get("runtime.product_configurator.points_info");
				if (!empty($points_info[$product['product_id']])) {
					$reward_points['amount'] = $points_info[$product['product_id']]['reward'];
					$reward_points['coefficient'] = 1;
				}
			}
		} else {
			if ($reward_points['amount_type'] == 'P') {
				$reward_points['amount'] = $product['price'] * $reward_points['amount'] / 100;
			}
		}

		$reward_points['raw_amount'] = $reward_points['coefficient'] * $reward_points['amount'];
		$reward_points['raw_amount'] = !empty($product['selected_options']) ? fn_apply_options_modifiers($product['selected_options'], $reward_points['raw_amount'], POINTS_MODIFIER_TYPE) : $reward_points['raw_amount'];
		
		$reward_points['amount'] = round($reward_points['raw_amount']);
		$product['points_info']['reward'] = $reward_points;
	}

	fn_calculate_product_price_in_points($product, $auth, $get_point_info);
}

function fn_calculate_product_price_in_points(&$product, &$auth, $get_point_info = true)
{
	if (isset($product['exclude_from_calculate']) || (AREA == 'A' && !defined('ORDER_MANAGEMENT') && CONTROLLER != 'subscriptions') || floatval($product['price']) == 0 || (isset($product['points_info']['price']) && !(CONTROLLER == 'products' && MODE == 'options')) || $get_point_info == false || !isset($product['is_pbp']) || $product['is_pbp'] == 'N') {
		return false;
	}

	if ((CONTROLLER == 'checkout' && isset($product['subtotal'])) || (defined('ORDER_MANAGEMENT') && (MODE == 'totals' || MODE == 'summary'))) {
		if (Registry::get('addons.reward_points.auto_price_in_points') == 'Y' && $product['is_oper'] == 'N') {
			$per = Registry::get('addons.reward_points.point_rate');

			if (Registry::get('addons.reward_points.price_in_points_with_discounts') == 'Y' && !empty($product['subtotal'])) {
				$subtotal = $product['subtotal'];
			} else {
				$subtotal = $product['price'] * $product['amount'];
			}
		} else {
			$per = (!empty($product['original_price']) && floatval($product['original_price'])) ? fn_get_price_in_points($product['product_id'], $auth) / $product['original_price'] : 0;
			$subtotal = $product['original_price'] * $product['amount'];
		}
	} else {
		if (Registry::get('addons.reward_points.auto_price_in_points') == 'Y' && $product['is_oper'] == 'N') {
			$per = Registry::get('addons.reward_points.point_rate');

			if (Registry::get('addons.reward_points.price_in_points_with_discounts') == 'Y' && isset($product['discounted_price'])) {
				$subtotal = $product['discounted_price'];
			} else {
				if (defined('ORDER_MANAGEMENT')) {
					$subtotal = $product['price'] * $product['amount'];
				} else {
					$subtotal = $product['price'];
				}
			}
			
		} else {
			$per = (!empty($product['price']) && floatval($product['price'])) ? fn_get_price_in_points($product['product_id'], $auth) / $product['price'] : 0;
			$subtotal = $product['price'];
		}
	}

	$product['points_info']['raw_price'] = $per * $subtotal;
	$product['points_info']['price'] = round($product['points_info']['raw_price']);
}

function fn_reward_points_clone_product($from_product_id, $to_product_id)
{

	$reward_points = fn_get_reward_points($from_product_id);
	if (!empty($reward_points)) {
		foreach ($reward_points as $v) {
			$_data = fn_check_table_fields($v, 'reward_points');
			fn_add_reward_points($_data, $to_product_id, PRODUCT_REWARD_POINTS);
		}
	}

	$fake = '';
	$price_in_points = fn_get_price_in_points($from_product_id, $fake);
	fn_add_price_in_points(array('point_price' => $price_in_points), $to_product_id);
}

function fn_check_points_gain($order_id)
{

	$is_gain = db_get_field("SELECT order_id FROM ?:order_data WHERE type = ?s AND order_id = ?i", ORDER_DATA_POINTS_GAIN, $order_id);
	return (!empty($is_gain)) ? true : false;
}


function fn_reward_points_get_selected_product_options(&$extra_variant_fields)
{
	$extra_variant_fields .= 'a.point_modifier, a.point_modifier_type,';
}


function fn_reward_points_get_product_options(&$extra_variant_fields)
{
	$extra_variant_fields .= 'a.point_modifier, a.point_modifier_type,';
}

function fn_reward_points_apply_option_modifiers(&$fields, $type)
{
	if ($type == POINTS_MODIFIER_TYPE) {
		$fields = "point_modifier as modifier, point_modifier_type as modifier_type";
	}
}

//
//Integrate with RMA
//
function fn_reward_points_rma_recalculate_order($item, $mirror_item, $type, $ex_data, $amount)
{

	if (!isset($item['extra']['exclude_from_calculate'])) {
		if (isset($mirror_item['extra']['points_info']['reward'])) {
			$item['extra']['points_info']['reward'] = floor((isset($item['primordial_amount']) ? $item['primordial_amount'] : $item['amount']) * ($mirror_item['extra']['points_info']['reward'] / $mirror_item['amount']));
		}
		if (isset($mirror_item['extra']['points_info']['price'])) {
			$item['extra']['points_info']['price'] = floor((isset($item['primordial_amount']) ? $item['primordial_amount'] : $item['amount']) * ($mirror_item['extra']['points_info']['price'] / $mirror_item['amount']));
		}
		if (in_array($type, array('O-', 'M-O+'))) {
			if (isset($item['extra']['points_info']['reward'])) {
				$points = (($type == 'O-') ? 1 : -1) * floor($amount * (!empty($item['amount']) ? ($item['extra']['points_info']['reward'] / $item['amount']) : ($mirror_item['extra']['points_info']['reward'] / $mirror_item['amount'])));
				$additional_data = db_get_hash_single_array("SELECT type,data FROM ?:order_data WHERE order_id = ?i", array('type', 'data'), $ex_data['order_id']);

				if (!empty($additional_data[POINTS])) {
					db_query('UPDATE ?:order_data SET ?u WHERE order_id = ?i AND type = ?s', array('data' => $additional_data[POINTS] + $points), $ex_data['order_id'], POINTS);
				}

				if (!empty($additional_data[ORDER_DATA_POINTS_GAIN]) && $additional_data[ORDER_DATA_POINTS_GAIN] == 'Y') {
					$user_id = db_get_field("SELECT user_id FROM ?:orders WHERE order_id = ?i", $ex_data['order_id']);
					$reason = array(
						'return_id' => $ex_data['return_id'],
						'to' 		=> $ex_data['status_to'],
						'from' 		=> $ex_data['status_from']
					);
					fn_change_user_points($points, $user_id, serialize($reason), CHANGE_DUE_RMA,$ex_data['order_id']);
				}
			}
		}
	}
}

function fn_reward_points_get_external_discounts($product, &$discounts)
{
	if (!empty($product['extra']['points_info']['discount'])) {
		$discounts += $product['extra']['points_info']['discount'];
	}
}

function fn_reward_points_form_cart(&$order_info, &$cart)
{
	if (!empty($order_info['points_info'])) {
		$cart['points_info'] = $cart['previous_points_info'] = $order_info['points_info'];
	}
}

function fn_reward_points_allow_place_order(&$total, &$cart)
{
	if (!empty($cart['points_info'])) {
		if (!empty($cart['points_info']['in_use']) && isset($cart['points_info']['in_use']['cost'])) {
			$total += $cart['points_info']['in_use']['cost'];
		}
	}

	return true;
}

function fn_reward_points_user_init(&$auth, &$user_info)
{
	if (empty($auth['user_id']) || AREA != 'C') {
		return false;
	}

	$auth['points'] = $user_info['points'] = fn_get_user_additional_data(POINTS, $auth['user_id']);

	return true;
}

function fn_reward_points_get_users(&$params, &$fields, &$sortings, &$condition, &$join)
{
	$sortings['points'] = '?:user_data.data';

	$join .= " LEFT JOIN ?:user_data ON ?:user_data.user_id = ?:users.user_id AND ?:user_data.type = 'W'";
	$fields[] = '?:user_data.data as points';

	return true;
}

function fn_reward_points_get_orders(&$params, &$fields, &$sortings, &$condition, &$join)
{
	$sortings['points'] = '?:order_data.data';

	$join .= db_quote(" LEFT JOIN ?:order_data ON ?:order_data.order_id = ?:orders.order_id AND ?:order_data.type = ?s", POINTS);
	$fields[] = "?:order_data.data as points";

	return true;
}

function fn_reward_points_get_product_data($product_id, &$field_list, &$join, &$auth)
{
	$field_list .= ", MIN(point_prices.point_price) as point_price";
	$join .= db_quote(" LEFT JOIN ?:product_point_prices as point_prices ON point_prices.product_id = ?:products.product_id AND point_prices.lower_limit = 1 AND point_prices.usergroup_id IN (?n)", ((AREA == 'C') ? array_merge(array(USERGROUP_ALL), $auth['usergroup_ids']) : USERGROUP_ALL));
}


function fn_reward_points_update_product($product_data, $product_id)
{
	if (isset($product_data['point_price'])) {
		fn_add_price_in_points(array('point_price' => $product_data['point_price']), $product_id);
	}

	if (isset($product_data['reward_points']) && ($product_data['is_op'] == 'Y')) {
		foreach ($product_data['reward_points'] as $v) {
			fn_add_reward_points($v, $product_id, PRODUCT_REWARD_POINTS);
		}
	}
}

function fn_reward_points_promotion_give_points($bonus, &$cart, &$auth, &$cart_products)
{
	$cart['promotions'][$bonus['promotion_id']]['bonuses'][$bonus['bonus']] = $bonus;

	if ($bonus['bonus'] == 'give_points') {
		$cart['points_info']['additional'] = (!empty($cart['points_info']['additional']) ? $cart['points_info']['additional'] : 0) + $bonus['value'];
	}

	return true;
}

function fn_reward_points_update_category($category_data, $category_id)
{
	if (isset($category_data['reward_points']) && $category_data['is_op'] == 'Y') {
		foreach ($category_data['reward_points'] as $v) {
			fn_add_reward_points($v, $category_id, CATEGORY_REWARD_POINTS);
		}
	}
}

function fn_reward_points_global_update(&$table, &$field, &$value, &$type, &$msg, &$update_data)
{
	// Updating product prices in points
	if (!empty($update_data['price_in_points'])) {
		$table[] = '?:product_point_prices';
		$field[] = 'point_price';
		$value[] = $update_data['price_in_points'];
		$type[] = $update_data['price_in_points_type'];

		$msg .= ($update_data['price_in_points'] > 0 ? fn_get_lang_var('price_in_points_increased') : fn_get_lang_var('price_in_points_decreased')) . ' ' . abs($update_data['price_in_points']) . ($update_data['price_in_points_type'] == 'A' ? ' ' . fn_get_lang_var('points_lower') : '%') . '.';
	}
}


function fn_sdeep_is_cod_payment($order_info) {
	if(Registry::get('addons.sdeep.is_alternate_cod_behaviour') == 'Y') {
		$cod_id = Registry::get('addons.sdeep.cod_payment_id');
		if($order_info['payment_id'] == $cod_id) {
			return true;
		}
	}
	return false;
}

/*function to calculate the CB reward on GC*/
function fn_cb_reward_on_gc($gc_data){
	$gc_total =0;
	foreach($gc_data as $gc)
	{
		$gc_total += $gc['subtotal'];
	}
	
	$cb_on_gc_in_pct = (Registry::get('config.cb_on_gc_in_pct')) ? Registry::get('config.cb_on_gc_in_pct') : '0';
	$cb_on_gc_max_amt = (Registry::get('config.cb_on_gc_max_amt')) ? Registry::get('config.cb_on_gc_max_amt') : '0';
	
	$reward_amt = fn_format_price(($gc_total * $cb_on_gc_in_pct) / 100);
	$reward_amt = ($reward_amt > $cb_on_gc_max_amt) ? $cb_on_gc_max_amt : $reward_amt;
	return $reward_amt;
}
function fn_get_cb_limit($user_ids)
{
	$user_ids=implode(",",$user_ids);
	
	$sql="select max(add_limit) as add_limit,max(sub_limit) as sub_limit from clues_cb_limits where user_group_id in ($user_ids)";
	$limits=db_get_row($sql);
	
	return $limits;
}
function fn_reward_handle_expiry_exception($points,$order_id,$user_id)
{
	 
	$sql="select * from ?:reward_point_changes where user_id='".$user_id."' and reason='To Fulfill Order-".$order_id."'";
	$already_done=db_get_row($sql);
	if(!empty($already_done))
	{
		return true;
	}
	
	$change_value=abs($points);
	$sql="select timestamp from cscart_orders where order_id='".$order_id."'";
	$order_timestamp=db_get_field($sql);
	
	 $sql="select * from ?:reward_point_changes where timestamp<='".TIME."' and timestamp>='".$order_timestamp."' and user_id='".$user_id."' and reason like 'Expired%' order by change_id asc";
	 $expired_info=db_get_array($sql);
	
	 if(!empty($expired_info))
	 {
		 $exp_amount_total=0;
		 foreach($expired_info as $result)
		 {
			 $exp_amount_total+=abs($result['amount']);
			 $exp_used_total=0;
			 $exp_used_tracking[$result['change_id']]=0;
			 if(!empty($result['order_payment_history']))
			 {
				 $exp_used_info=explode('#',$result['order_payment_history']);
				 foreach($exp_used_info as $exp_used)
				 {
					 $exp_used_arr=explode(':',$exp_used);
					 $exp_used_total+= $exp_used_arr[2];
					 $exp_used_tracking[$result['change_id']]+=$exp_used_arr[2];
				 }
			 }
		 }
		 
		 $exp_rem= $exp_amount_total-$exp_used_total;
		 if($exp_rem>=$change_value)
		 {
			 $adjust_value=$change_value;
		 }
		 else
		 {
			 $sql="select data from cscart_user_data where user_id='".$user_id."' and type='W'";
			 $curr_bal=unserialize(db_get_field($sql));
			 if($curr_bal>=($change_value-$exp_rem))
			 {
				 $adjust_value=$exp_rem;
			 }
			 else
			 {
				 return false;
			 }
		 }
		 $change_points = array(
		'user_id' => $user_id,
		'amount' => $adjust_value,
		'timestamp' => TIME,
		'action' => CHANGE_DUE_ADDITION,
		'reason' => "To Fulfill Order-" . $order_id,
		'expire_on' => date('Y-m-d h:i:s',TIME),
		'balance' => $adjust_value,
		'type_id' =>1
		);
		db_query("REPLACE INTO ?:reward_point_changes ?e", $change_points);
		$sql="select max(change_id) as change_id from ?:reward_point_changes where user_id='".$user_id."'";
		$new_change_id=db_get_field($sql);
		fn_save_user_additional_data(POINTS, fn_get_user_additional_data(POINTS, $user_id) + $adjust_value, $user_id);
		
		$temp_amount=$adjust_value;
		foreach($expired_info as $result)
		{
			$exp_bal=(abs($result['amount'])-($exp_used_tracking[$result['change_id']]));
			if($temp_amount>0 && $exp_bal>0)
			{
				
				if($temp_amount>=$exp_bal)
				{
					$used_amount=$exp_bal;
					$temp_amount-=$exp_bal;
				}
				else
				{
					$used_amount=$temp_amount;
					$temp_amount=0;
					
				}
				if(!empty($result['order_payment_history']))
				{
					$pay_history=$result['order_payment_history']."#".$new_change_id.":".$order_id.":".$used_amount.":USED";
				}
				else
				{
					$pay_history=$new_change_id.":".$order_id.":".$used_amount.":USED";
				}
				db_query("update ?:reward_point_changes set order_payment_history='".$pay_history."' where change_id='".$result['change_id']."'");
			}
		}
		return true;
		
	 }
	 else
	 {
		 return false;
	 }
   
}

function fn_reward_points_promotion_give_points_pct($bonus, &$cart, &$auth, &$cart_products)
{
	$cart['promotions'][$bonus['promotion_id']]['bonuses'][$bonus['bonus']] = $bonus;
	if ($bonus['bonus'] == 'give_points_pct') {
            $cart['points_info']['additional'] = (!empty($cart['points_info']['additional']) ? $cart['points_info']['additional'] : 0) + $cart['emi_fee'];
	}
        $cart['cashback_emi_fee_in_cb'] = 'Y';
	return true;
}
?>
