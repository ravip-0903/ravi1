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
// $Id: fn.promotions.php 12865 2011-07-05 06:57:22Z 2tl $
//

if ( !defined('AREA') )	{ die('Access denied');	}

fn_define('COUPON_CODE_LENGTH', 8);

/**
 * Get promotions
 *
 * @param array $params array with search params
 * @param int $items_per_page
 * @param string $lang_code
 * @return array list of promotions in first element, filtered parameters in second
 */
function fn_get_promotions($params, $items_per_page = 0, $lang_code = CART_LANGUAGE)
{
	// Init filter
	$params = fn_init_view('promotions', $params);
	
	if ($params['api'] == 1) $mobile_coupon = 1;

	// Set default values to input params
	$params['page'] = empty($params['page']) ? 1 : $params['page']; // default page is 1
	$params['get_hidden'] = !isset($params['get_hidden']) ? true : $params['get_hidden']; // always get hidden promotions

	// Define fields that should be retrieved
	$fields = array (
		"?:promotions.*",
		"?:promotion_descriptions.name",
		"?:promotion_descriptions.internal_name", // added by Sudhir
		"?:promotion_descriptions.detailed_description",
		"?:promotion_descriptions.short_description",
	);

	// Define sort fields
	$sortings = array (
		'name' => "?:promotion_descriptions.name",
		'priority' => "?:promotions.priority",
		'zone' => "?:promotions.zone",
		'status' => "?:promotions.status",
		'start_date'=>"?:promotions.from_date", //added by ankur start
		'end_date'=>"?:promotions.to_date",
		'used_count'=>"?:promotions.number_of_usages",
		'created_by'=>"prom_created_by",
		'created_date'=>"?:promotions.creation_date", //end
	);

	$directions = array (
		'asc' => 'asc',
		'desc' => 'desc'
	);

	if (empty($params['sort_order']) || empty($directions[$params['sort_order']])) {
		$params['sort_order'] = 'desc';
	}

	if (empty($params['sort_by']) || empty($sortings[$params['sort_by']])) {
		$params['sort_by'] = 'name';
	}

	$sorting = (is_array($sortings[$params['sort_by']]) ? implode(' ' . $directions[$params['sort_order']]. ', ', $sortings[$params['sort_by']]): $sortings[$params['sort_by']]). " " .$directions[$params['sort_order']];

	// Reverse sorting (for usage in view)
	$params['sort_order'] = $params['sort_order'] == 'asc' ? 'desc' : 'asc';

	$condition = $join = $group = '';
	
	//changes by aknur to show only those promotion which are not created by merchant
	if(AREA=='C')
	{
		$condition .= fn_get_company_condition('?:promotions.company_id');
	}
	else if(AREA=='A')
	{
		if(defined("COMPANY_ID"))
		$comp_id=COMPANY_ID;
		else
		$comp_id=0;
		if(CONTROLLER=='mpromotions' and !defined("COMPANY_ID"))
		{
			$condition .=db_quote(" AND ?:promotions.company_id!='".$comp_id."'");
		}
		else
		{
			$condition .=db_quote(" AND ?:promotions.company_id='".$comp_id."'");
		}
		
		if(CONTROLLER=='mpromotions')
		{
			$fields[]="case when concat(?:users.firstname,'',?:users.lastname)='' then ?:users.email else concat(?:users.firstname,' ',?:users.lastname) end as prom_created_by ";//added by ankur
			$join .= db_quote(" left join ?:users on ?:users.user_id=?:promotions.created_by");
		}
		
	}

	$statuses = array('A');
	if (!empty($params['get_hidden'])) {
		$statuses[] = 'H';
	}

	if (!empty($params['promotion_id'])) {
		$condition .= db_quote(' AND ?:promotions.promotion_id IN (?n)', $params['promotion_id']);
	}

	if (!empty($params['active'])) {
		$condition .= db_quote(" AND IF(from_date, from_date <= ?i, 1) AND IF(to_date, to_date >= ?i, 1) AND status IN (?a)", TIME, TIME, $statuses);
	}

	if (!empty($params['zone'])) {
		$condition .= db_quote(" AND ?:promotions.zone = ?s", $params['zone']);
	}
	
	if(AREA == 'C'){
		if (!empty($params['coupon_codes'])) {		
			foreach($params['coupon_codes'] as $k=>$coupon_code){
				if($k == '0'){
					$cond = " conditions_hash like '%$coupon_code%'";
				}else{
					$cond .= " OR conditions_hash like '%$coupon_code%'";
				}
			}
			if (!empty($params['promotion_ids'])) {
				foreach($params['promotion_ids'] as $k=>$pid){
					$cond .= " OR ?:promotions.promotion_id = '$pid'";
				}	
			}
			$condition .= " AND ($cond)";
			
			
		}elseif(empty($params['coupon_codes']) && empty($params['promotion_id'])){
			$condition .= " AND conditions_hash not like '%coupon_code%'";
		}
		
		// by ajay for mobile api 
		if($mobile_coupon == "1"){
				$condition .= " AND ?:promotions.valid_for IN ('M', 'B') ";
		}else{
				$condition .= " AND ?:promotions.valid_for IN ('W', 'B') ";
		}
			//end  by ajay for mobile api 
	}

	if (!empty($params['coupon_code'])) {
		$condition .= db_quote(" AND ?:promotions.conditions_hash LIKE ?l", "%$params[coupon_code]%"); // FIXME, more smart rules
	}

	if (!empty($params['coupons'])) {
		$condition .= db_quote(" AND ?:promotions.conditions_hash LIKE ?l", "%coupon_code=%"); // FIXME
	}

	if (!empty($params['auto_coupons'])) {
		$condition .= db_quote(" AND ?:promotions.conditions_hash LIKE ?l", "%auto_coupons=%");
	}
// Added by Sudhir
	if (!empty($params['q'])) {
		$condition .= db_quote(" AND ?:promotion_descriptions.name LIKE ?l", "%$params[q]%");
		$condition .= db_quote(" OR ?:promotion_descriptions.internal_name LIKE ?l", "%$params[q]%");
		$condition .= db_quote(" OR ?:promotions.conditions_hash LIKE ?l", "%$params[q]%");
	}
// Added by Sudhir end here
	$join .= db_quote(" LEFT JOIN ?:promotion_descriptions ON ?:promotion_descriptions.promotion_id = ?:promotions.promotion_id AND ?:promotion_descriptions.lang_code = ?s", $lang_code);
	
	fn_set_hook('get_promotions', $params, $fields, $sortings, $condition, $join);

	$limit = '';
	if (!empty($items_per_page)) {
		$total = db_get_field("SELECT COUNT(*) FROM ?:promotions $join WHERE 1 $condition $group");
		$limit = fn_paginate($params['page'], $total, $items_per_page);
	}
	if (!empty($params['simple'])) {
		return db_get_hash_single_array("SELECT ?:promotions.promotion_id, ?:promotion_descriptions.name FROM ?:promotions $join WHERE 1 $condition $group ORDER BY $sorting $limit", array('promotion_id', 'name'));
	} else {
			   $promotions = db_get_hash_array('SELECT ' . implode(', ', $fields) . " FROM ?:promotions $join WHERE 1 $condition $group ORDER BY ?:promotions.stop desc, $sorting $limit", 'promotion_id');
	}
	/*Modified by chandan to find the promotion based on the coupon either the coupon is disabled or expired*/
	if(empty($promotions)){
		if(AREA == 'C'){
			if (!empty($params['coupon_codes'])) {		
				foreach($params['coupon_codes'] as $k=>$coupon_code){
					if($k == '0'){
						$cond = " conditions_hash like '%$coupon_code%'";
					}else{
						$cond .= " OR conditions_hash like '%$coupon_code%'";
					}
				}
				$_condition .= " AND ($cond)";
				
			}
			// by ajay for mobile api 
		if($mobile_coupon == "1"){
				$_condition .= " AND ?:promotions.valid_for IN ('M', 'B') ";
		}else{
				$_condition .= " AND ?:promotions.valid_for IN ('W', 'B') ";
		}
			$sql = "SELECT cscart_promotions.*, cscart_promotion_descriptions.name, cscart_promotion_descriptions.internal_name, cscart_promotion_descriptions.detailed_description, cscart_promotion_descriptions.short_description FROM cscart_promotions LEFT JOIN cscart_promotion_descriptions ON cscart_promotion_descriptions.promotion_id = cscart_promotions.promotion_id AND cscart_promotion_descriptions.lang_code = 'EN' WHERE 1 AND cscart_promotions.zone = 'cart' $_condition ORDER BY cscart_promotions.priority asc ";
			$promotions = db_get_hash_array($sql, 'promotion_id');
			
			
		}
			
	}
	/*Modified by chandan to find the promotion based on the coupon either the coupon is disabled or expired*/
	if (!empty($params['expand'])) {
		foreach ($promotions as $k => $v) {
			$promotions[$k]['conditions'] = !empty($v['conditions']) ? unserialize($v['conditions']) : array();
			$promotions[$k]['bonuses'] = !empty($v['bonuses']) ? unserialize($v['bonuses']) : array();
		}
	}

	return array($promotions, $params);
}


/**
 * Apply promotion rules
 *
 * @param array $data data array (product - for catalog rules, cart - for cart rules)
 * @param string $zone - promotiontion zone (catalog, cart)
 * @param array $cart_products (optional) - cart products array (for car rules)
 * @param array $auth (optional) - auth array (for car rules)
 * @return bool true if rule can be applied, false - otherwise
 */
function fn_promotion_apply($zone, &$data, &$auth = NULL, &$cart_products = NULL)
{
	if($data['api']==1)
	{
		$params['api'] = $data['api'];
		$promotions = array();
	}
	else
	{
                if($data['bulk_coupon'] == 'Y'){
                    $promotions = array();
                }else{
                    static $promotions = array();
                }
	}
	$applied_promotions = array();
	
	/*modified by clues dev to external promotion code with internal promotion code */
	$sql = "SELECT * from clues_mass_coupon_code where coupon_code = '".$data['pending_coupon']."'";
	$coupon_result = db_get_row($sql);
	if(!empty($coupon_result)){
		if($coupon_result['status']!='ACTIVE')
		{
			if($data['api']==1)
			{
				$data['coupon_error']=fn_get_lang_var('coupon_is_used');
			}
			fn_set_notification('E', '', fn_get_lang_var('coupon_is_used'));
			unset($data['coupons'][$key]);
			unset($_SESSION['cart']['pending_coupon']);
			return false; 
		}
		if(count($_SESSION['cart']['custom_coupon'])==0){
			$_SESSION['cart']['custom_coupon'][$data['pending_coupon']]=$coupon_result['system_coupon_code'];
		}
		$data['pending_coupon'] = $coupon_result['system_coupon_code'];
	}
	/*modified by clues dev to external promotion code with internal promotion code */
	
        // For customer coupon code

	    $user_id = $_SESSION['auth']['user_id'];
        $coupon_timestamp = time();
        $sql = "SELECT * from clues_customer_coupon where coupon_code = '".$data['pending_coupon']."'
                                              and user_id=$user_id and expiration_date > $coupon_timestamp ";
       
        $coupon_result = db_get_row($sql);

	if(!empty($coupon_result)){
           
            $pending_coupon = $data['pending_coupon'];
            
            $dead_orders=implode("','",Registry::get('config.dead_orders'));
            $res=db_get_row("select * from cscart_orders where coupon_codes LIKE '%".$pending_coupon ."%' and status NOT IN ('".$dead_orders."')");
            
            if(!empty($res)){
		  fn_set_notification('E', '', fn_get_lang_var('coupon_is_used'));
                  unset($data['coupons'][$key]);
                  unset($_SESSION['cart']['pending_coupon']);
                  return false;
             }

	    if(count($_SESSION['cart']['custom_coupon'])==0){
			$_SESSION['cart']['custom_coupon'][$data['pending_coupon']]=$coupon_result['system_coupon_code'];
		}
            $data['pending_coupon'] = $coupon_result['system_coupon_code'];
            
        } 
        
	if (!isset($promotions[$zone])) {
			$params = array(
				'active' => true,
				'expand' => true,
				//'zone' => $zone,
				'zone' => 'cart',//Modified by chandan to remove catalog promotions
				'sort_by' => 'priority',
				'sort_order' => 'asc'
			);
			if(isset($data['pending_coupon']) && $data['pending_coupon'] != ''){
				$params['coupon_codes'] = array($data['pending_coupon']);
				if(Registry::get('settings.General.use_single_coupon') == 'N'){
					if(isset($data['coupons']) && count($data['coupons']) > 0){
							$params['coupon_codes'] = array_merge($params['coupon_codes'],array_keys($data['coupons']));
					}
				}	
			}elseif(isset($data['coupons']) && count($data['coupons']) > 0){
					$params['coupon_codes'] = array_keys($data['coupons']);
			}
			
			if(Registry::get('settings.General.use_single_coupon') == 'N'){
				if(isset($data['promotions']) && !empty($data['promotions'])){
					$params['promotion_ids'] = array_keys($data['promotions']);	
				}
			}
			
			/*if(Registry::get('settings.General.use_single_coupon') == 'N'){
				if(isset($data['coupons']) && count($data['coupons']) > 0){
					$params['coupon_codes'] = array_merge($params['coupon_codes'],array_keys($data['coupons']));
				}
			}*/
			
			//$params['coupon_codes'] = array('XYZ', 'PQR');
			
			//echo '<pre>';print_r($params);echo '</pre>';die;
			if ($data['api'] == 1) $params['api'] = 1;

					
			
			list($promotions[$zone]) = fn_get_promotions($params);
	}
	/*Modified by chandan to show different promotion error*/
	if(!empty($promotions[$zone]) && isset($data['pending_coupon'])){		
		foreach($promotions[$zone] as $k=>$promotion){
			if($promotion['status'] == 'D'){
				unset($_SESSION['cart']['pending_coupon']);
				$data['coupons'] = array();
				unset($promotions[$zone][$k]);
		 		//if (!fn_notification_exists('W', 'promotion_is_no_longer_exist')) {
				if($data['api']==1)
				{
					$data['coupon_error']=fn_get_lang_var('coupon_disabled');
				}
				fn_set_notification('W', '', fn_get_lang_var('coupon_disabled'));
				//}
			}elseif($promotion['to_date'] > 0 && $promotion['from_date'] > 0 && $promotion['to_date'] < TIME){
				unset($_SESSION['cart']['pending_coupon']);
		 		$data['coupons'] = array();
				unset($promotions[$zone][$k]);
		 		//if (!fn_notification_exists('W', 'coupon_disabled')) {
				if($data['api']==1)
				{
					$data['coupon_error']=fn_get_lang_var('promotion_is_no_longer_exist');
				}
				fn_set_notification('W', '', fn_get_lang_var('promotion_is_no_longer_exist'));
				//}
			}
		}
	}elseif(isset($data['coupons']) && empty($data['coupons']) && isset($data['pending_coupon'])){
		 unset($_SESSION['cart']['pending_coupon']);
		 if($data['api']==1)
		 {
		 	$data['coupon_error'] = fn_get_lang_var('no_such_coupon_exist');
		 }
		 fn_set_notification('W', '', fn_get_lang_var('no_such_coupon_exist'));
	}
	
	/*Modified by chandan to show different promotion error*/
	// If we're in cart, set flag that promotions available
	if ($zone == 'cart') {
		$_promotion_ids = !empty($data['promotions']) ? array_keys($data['promotions']) : array();
		$data['no_promotions'] = empty($promotions[$zone]);
		$data['promotions'] = array(); // cleanup stored promotions
		$data['subtotal_discount'] = 0; // reset subtotal discount (FIXME: move to another place)
		$data['has_coupons'] = true; // default false, set true by clues dev to show the coupon input box to user.
	}
	if (empty($promotions[$zone])) {
		return false;
	}	
	
	// Pre-check coupon
	if ($zone == 'cart' && !empty($data['pending_coupon'])) {
		fn_promotion_check_coupon($data, true);
	}
    /*Modified by clues dev to check the autogenerated coupon code expiration*/
    if(isset($data['coupons'])) {
	foreach($data['coupons'] as $key=>$coupon) {
        $coupon_code = $key;
        $user_id = $_SESSION['auth']['user_id'];
        $sql = "SELECT * from clues_customer_coupon where coupon_code = '$coupon_code'";
        $coupon_result = db_get_row($sql);
        if(!empty($coupon_result)){
            if( $coupon_result['user_id'] == $user_id){
                if( date('Y-m-d') <= date('Y-m-d',$coupon_result['expiration_date']) ){			     
                //return true;
                    $dead_orders=implode("','",Registry::get('config.dead_orders'));
                  //  $res=db_get_row("select * from cscart_orders where find_in_set('".$coupon_code."',coupon_codes) and status NOT IN ('".$dead_orders."')");
                    $res=db_get_row("select * from cscart_orders where coupon_codes LIKE '%".$coupon_code."%' and status NOT IN ('".$dead_orders."')");

                    if(empty($res)){
        				
        			}else{
        				if($data['api']==1)
        				{
        					$data['coupon_error']= fn_get_lang_var('coupon_is_used');
        				}
                        fn_set_notification('E', '', fn_get_lang_var('coupon_is_used'));
                        unset($data['coupons'][$key]);
                        unset($_SESSION['cart']['pending_coupon']);
                		return false; 
        			}                                    
        		}else{
        			if($data['api']==1)
        			{
        				$data['coupon_error']=fn_get_lang_var('coupon_expired');
        			}
            		  fn_set_notification('E', '', fn_get_lang_var('coupon_expired'));
		                  unset($data['coupons']);
                      	  unset($_SESSION['cart']['pending_coupon']);
            		  return false;
        		}  
            }else{
            	if($data['api']==1)
            	{
            		$data['coupon_error']=fn_get_lang_var('coupon_not_associated_with_you');
            	}
                fn_set_notification('E', '', fn_get_lang_var('coupon_not_associated_with_you'));
                unset($data['coupons'][$key]);
                unset($_SESSION['cart']['pending_coupon']);
        		return false;
            }
        }
    }
	}
	
    /*Modified by clues dev to check the autogenerated coupon code expiration*/
	foreach ($promotions[$zone] as $promotion) {
		// Rule is valid and can be applied
		if (fn_promotion_check($promotion['promotion_id'], $promotion['conditions'], $data, $auth, $cart_products)) {
			if (fn_promotion_apply_bonuses($promotion, $data, $auth, $cart_products)) {
				$applied_promotions[$promotion['promotion_id']] = $promotion;
				$data['sor'] = 'N';
				// Stop processing further rules, if needed
				if ($promotion['stop'] == 'Y') {
					$data['sor'] = 'Y';
					break;
				}
			}
		}
	}
	if ($zone == 'cart') {

		// Post-check coupon
		if (!empty($data['pending_coupon'])) {
			fn_promotion_check_coupon($data, false, $applied_promotions);
		}

		if (!empty($applied_promotions)) {
			// Display notifications for new promotions
			$_text = array();
			foreach ($applied_promotions as $v) {
				if (!in_array($v['promotion_id'], $_promotion_ids)) {
					$_text[] = $v['name'];
				}
			}

			if (!empty($_text)) {
				//fn_set_notification('W', fn_get_lang_var('important'), fn_get_lang_var('text_applied_promotions') . ': ' . implode(', ', $_text));
				//fn_set_notification('N', '', fn_get_lang_var('text_applied_promotions') . ': ' . implode(', ', $_text));
			}

			Registry::get('view')->assign('applied_promotions', $applied_promotions);

			// Delete obsolete coupons
			if (!empty($data['coupons'])) {
				foreach ($data['coupons'] as $_coupon_code => $_p_ids) {
					foreach ($_p_ids as $_ind => $_p_id) {
						if (!isset($applied_promotions[$_p_id])) {
							unset($data['coupons'][$_coupon_code][$_ind]);
						}
					}
					if (empty($data['coupons'][$_coupon_code])) {
						unset($data['coupons'][$_coupon_code]);
					}
				}
			}

		} else {
			$data['coupons'] = array();
		}

		// Delete obsolete discounts
		foreach ($cart_products as $p_id => $_val) {
			$data['products'][$p_id]['discount'] = !empty($_val['discount']) ? $_val['discount'] : 0;
			$data['products'][$p_id]['promotions'] = !empty($_val['promotions']) ? $_val['promotions'] : array();
		}
		
		/*if(count($data['promotions']) > 0) {
			$data['emi_fee'] = fn_format_price((($data['total']-$data['emi_fee']) * $data['payment_details']['fee'])/100);
		}else{
			if(date('Y-m-d h:i:s') <= $data['payment_details']['promo_end_date']) {
				$data['emi_fee'] = $data['payment_details']['promo_fee'];
			}else {
                            echo '<pre>';print_r($data);
                            $data['emi_fee'] = fn_format_price((($data['total']-$data['emi_fee']) * $data['payment_details']['fee'])/100);
			}			
		}*/

		// Summarize discounts
		foreach ($cart_products as $k => $v) {
			if (!empty($v['promotions'])) {
				foreach ($v['promotions'] as $pr_id => $bonuses) {
					foreach ($bonuses['bonuses'] as $bonus) {
						if (!empty($bonus['discount'])) {
							$data['promotions'][$pr_id]['total_discount'] = (!empty($data['promotions'][$pr_id]['total_discount']) ? $data['promotions'][$pr_id]['total_discount'] : 0) + ($bonus['discount'] * $v['amount']);
						}
					}
				}
			}
		}
	}
	return !empty($applied_promotions);
}
/**
 * Apply discount to the product
 *
 * @param int $promotion_id promotion ID
 * @param array $bonus promotion bonus
 * @param array $product product array (product - for catalog rules, cart - for cart rules)
 * @param bool $use_base use base price or with applied discounts
 * @return bool true if rule can be applied, false - otherwise
 */

function fn_promotion_apply_discount($promotion_id, $bonus, &$product, $use_base = true)
{
	/*Added by chandan to track the mpromotion discount*/
        $is_mpromotion_sql = "select cp.company_id, cpt.show_to_merchant
                                from cscart_promotions cp
                                join clues_promotion_type  cpt on cpt.promotion_type_id=cp.promotion_type_id
                                where cp.promotion_id='".$promotion_id."'";
        $is_mpromotion_res = db_get_row($is_mpromotion_sql);
        //echo '<pre>';print_r($is_mpromotion_res);echo '</pre>';
        /*Added by chandan to track the mpromotion discount*/        
        if (!isset($product['promotions'])) {
		$product['promotions'] = array();
	}

	if (!isset($product['discount'])) {
		$product['discount'] = 0;
	}

	if (!isset($product['base_price'])) {
		$product['base_price'] = $product['price'];
	}

	$base_price = ($use_base == true) ? $product['base_price'] + (empty($product['modifiers_price']) ? 0 : $product['modifiers_price']) : $product['price'];

	$discount = fn_promotions_calculate_discount($bonus['discount_bonus'], $base_price, $bonus['discount_value'], $product['price']);
	$discount = fn_format_price($discount);
    //fn_print_die($bonus);
    /* Added by chandan to limit the discount on condition quantity And max amount */
          
        $_SESSION['cart']['category_discount_condition'][$bonus['promotion_id']]['promo_prod_count'] = $_SESSION['cart']['category_discount_condition'][$bonus['promotion_id']]['promo_prod_count']+1;
        
        if($bonus['max_discount_qty'] < $product['amount'] && $bonus['max_discount_qty'] != 0){
            $unit_discount  = $discount;
            $dis_amt        = $bonus['max_discount_qty'];
            $total_discount = $discount*$bonus['max_discount_qty'];
        }else{
            $total_discount = $discount*$product['amount'];
        }
        $discount = ($total_discount)/$product['amount'];
        
        if($_SESSION['cart']['category_discount_condition'][$bonus['promotion_id']]['promo_prod_count'] > $bonus['max_product'] && $bonus['max_product'] != 0){
            $discount = 0;
        }
        
        if($_SESSION['cart']['category_discount_condition'][$bonus['promotion_id']]['current_total_discount']+($discount*$product['amount']) > $bonus['max_discount_amt'] && $bonus['max_discount_amt'] != 0){
            $discount = ($bonus['max_discount_amt']-$_SESSION['cart']['category_discount_condition'][$bonus['promotion_id']]['current_total_discount'])/$product['amount'];
        }
        
        $_SESSION['cart']['category_discount_condition'][$bonus['promotion_id']]['current_total_discount'] =  $_SESSION['cart']['category_discount_condition'][$bonus['promotion_id']]['current_total_discount']+($discount*$product['amount']);
     
    /* Added by chandan to limit the discount on condition quantity */
	$product['discount'] += $discount;
	$product['price'] -= $discount;
	    
        /*Added by chandan to track the mpromotion discount*/
        if($is_mpromotion_res['company_id'] != '0' && $is_mpromotion_res['show_to_merchant'] == 'Y'){
            //echo 'hi';
            //$product['m_discount'] = $product['discount'];
            if(!in_array($promotion_id,$_SESSION['cart']['mpromotions'][$product['product_id']]['m_promotion_id'])){
                $_SESSION['cart']['mpromotions'][$product['product_id']]['m_discount'] += $product['discount'];
                $_SESSION['cart']['mpromotions'][$product['product_id']]['m_promotion_id'][] = $promotion_id;
            }
            
        }
        /*Added by chandan to track the mpromotion discount*/
	
	if ($product['price'] < 0) {
		$product['discount'] += $product['price'];
		$product['price'] = 0;
	}

	$product['promotions'][$promotion_id]['bonuses'][] = array (
		'discount_bonus' =>	$bonus['discount_bonus'],
		'discount_value' => $bonus['discount_value'],
		'discount' => $product['discount']
	);

	if (isset($product['subtotal'])) {
		$product['subtotal'] = $product['price'] * $product['amount'];
	}

	if (!empty($base_price)) {
		$product['discount_prc'] = sprintf('%d', round($product['discount'] * 100 / $base_price));	
	} else {
		$product['discount_prc'] = 0;	
	}
	
	return true;
}

/**
 * Apply promotion catalog rule
 *
 * @param array $promotion promotion array
 * @param array $product product array (product - for catalog rules, cart - for cart rules)
 * @param array $auth (optional) - auth array
 * @return bool true if rule can be applied, false - otherwise
 */
function fn_promotion_apply_catalog_rule($bonus, &$product, &$auth)
{
	if ($bonus['bonus'] == 'product_discount') {
		fn_promotion_apply_discount($bonus['promotion_id'], $bonus, $product);
	}

	return true;
}
/**
 * Apply promotion cart rule
 *
 * @param array $promotion promotion array
 * @param array $cart cart array
 * @param array $auth (optional) - auth array
 * @param array $cart_products (optional) - cart products array (for cart rules)
 * @return bool true if rule can be applied, false - otherwise
 */
function fn_promotion_apply_cart_rule($bonus, &$cart, &$auth, &$cart_products)
{
	// Clean bonuses
	if (!isset($cart['promotions'][$bonus['promotion_id']]['bonuses'])) {
		$cart['promotions'][$bonus['promotion_id']]['bonuses'] = array();
	}
	$bonus_id = count($cart['promotions'][$bonus['promotion_id']]['bonuses']);
	$cart['promotions'][$bonus['promotion_id']]['bonuses'][$bonus_id] = $bonus;

	if ($bonus['bonus'] == 'order_discount') {
		if (floatval($cart['subtotal'])) {
			if (!isset($cart['subtotal_discount'])) {
				$cart['subtotal_discount'] = 0;
			}
			$discount = fn_promotions_calculate_discount($bonus['discount_bonus'], $cart['subtotal'], $bonus['discount_value']);

			if (floatval($discount)) {
				$cart['use_discount'] = true;
				//$cart['subtotal_discount'] += fn_format_price($discount);
				/*added by chandan to check the discount amount with max discount amount*/
				$cart['subtotal_discount'] += ($bonus['max_discount_value'] > 0 && fn_format_price($discount) > $bonus['max_discount_value']) ? $bonus['max_discount_value'] : fn_format_price($discount);
			}
		}

	} elseif ($bonus['bonus'] == 'discount_on_products') {
                $_SESSION['cart']['category_discount_condition'][$bonus['promotion_id']]['current_total_discount'] = 0;
                $_SESSION['cart']['category_discount_condition'][$bonus['promotion_id']]['promo_prod_count'] = 0;
		foreach ($cart_products as $k => $v) {
			if (isset($v['exclude_from_calculate']) || (!floatval($v['base_price']) && $v['base_price'] != 0)) {
				continue;
			}

			if (fn_promotion_validate_attribute($v['product_id'], $bonus['value'], 'in')) {
				if (fn_promotion_apply_discount($bonus['promotion_id'], $bonus, $cart_products[$k])) {
					$cart['use_discount'] = true;
				}
			}
		}
        } elseif ($bonus['bonus'] == 'discount_on_companies') {
                $_SESSION['cart']['category_discount_condition'][$bonus['promotion_id']]['current_total_discount'] = 0;
                $_SESSION['cart']['category_discount_condition'][$bonus['promotion_id']]['promo_prod_count'] = 0;
		foreach ($cart_products as $k => $v) {
			if (isset($v['exclude_from_calculate']) || (!floatval($v['base_price']) && $v['base_price'] != 0)) {
				continue;
			}

			if (fn_promotion_validate_attribute($v['company_id'], $bonus['value'], 'in')) {
				if (fn_promotion_apply_discount($bonus['promotion_id'], $bonus, $cart_products[$k])) {
					$cart['use_discount'] = true;
				}
			}
		}

	} elseif ($bonus['bonus'] == 'discount_on_categories') {
                $_SESSION['cart']['category_discount_condition'][$bonus['promotion_id']]['current_total_discount'] = 0;
                $_SESSION['cart']['category_discount_condition'][$bonus['promotion_id']]['promo_prod_count'] = 0;
		foreach ($cart_products as $k => $v) {
			if (isset($v['exclude_from_calculate']) || (!floatval($v['base_price']) && $v['base_price'] != 0)) {
				continue;
			}

			$c_ids = array_keys($v['category_ids']);

			if (fn_promotion_validate_attribute($c_ids, $bonus['value'], 'in')) {
				if (fn_promotion_apply_discount($bonus['promotion_id'], $bonus, $cart_products[$k])) {
					$cart['use_discount'] = true;
				}
			}
		}

	} elseif ($bonus['bonus'] == 'give_usergroup') {
		$cart['promotions'][$bonus['promotion_id']]['bonuses'][$bonus_id]['pending'] = true;

	} elseif ($bonus['bonus'] == 'give_coupon') {
		$cart['promotions'][$bonus['promotion_id']]['bonuses'][$bonus_id]['pending'] = true;
		$cart['promotions'][$bonus['promotion_id']]['bonuses'][$bonus_id]['coupon_code'] = fn_generate_code('', COUPON_CODE_LENGTH);

	} elseif ($bonus['bonus'] == 'free_shipping') {

		$cart['free_shipping'][] = $bonus['value'];

	} elseif ($bonus['bonus'] == 'free_product_shipping') {

		if(Registry::get('config.enable_free_product_shipping')){
                    $promo_id   = $bonus['promotion_id'];
                    $sql = "select conditions from cscart_promotions where promotion_id=$promo_id";
                    $res = db_get_field($sql);
                    $res = unserialize($res);
                    $fsp = array();
                    foreach($res['conditions'] as $r){
                        if($r['condition'] == "products"){
                            foreach($r['value'] as $rp){
                                $fsp[] = $rp['product_id'];
                            }
                        }

                    }

                    $fsc = 0;
                    foreach($cart['products'] as $k=>$cp){
                        if(in_array($cp['product_id'], $fsp) && (($cp['is_edp'] !='Y' || ($cp['is_edp'] == 'Y' && $cp['edp_shipping'] == 'Y')) && $cp['free_shipping'] != 'Y')){
                            $fsc += $cp['shipping_freight'];
                            $cart['products'][$k]['extra']['sdtz'] = 'Y'; // sdtz = shipping discounted to zero
                        }
                    }
                    $cart['subtotal_discount'] += $fsc;
                }

	} elseif ($bonus['bonus'] == 'free_products') {

		foreach ($bonus['value'] as $p_data) {
			$product_data = array (
				$p_data['product_id'] => array (
					'amount' => $p_data['amount'],
					'product_id' => $p_data['product_id'],
					'extra' => array (
						'exclude_from_calculate' => true,
						'aoc' => empty($p_data['product_options']),
						'saved_options_key' => $bonus['promotion_id'] . '_' . $p_data['product_id'],
                                                'is_freebie' => 'Y', /*Added by chandan to set is_freebie Y if its a free product with promotion*/
					)
				),
			);

			if (!empty($cart['saved_product_options'][$bonus['promotion_id'] . '_' . $p_data['product_id']])) {
				$product_data[$p_data['product_id']]['product_options'] = $cart['saved_product_options'][$bonus['promotion_id'] . '_' . $p_data['product_id']];
			} elseif (!empty($p_data['product_options'])) {
				$product_data[$p_data['product_id']]['product_options'] = $p_data['product_options'];
			}

			$existing_products = array_keys($cart['products']);

			if ($ids = fn_add_product_to_cart($product_data, $cart, $auth)) {
				$new_products = array_diff(array_keys($cart['products']), $existing_products);
				if (!empty($new_products)){
					$hash = array_pop($new_products);
				}else{
					$hash = key($ids);
				}	

				$_cproduct = fn_get_cart_product_data($hash, $cart['products'][$hash], true, $cart, $auth, !empty($new_products) ? 0 : $p_data['amount']);
				if (!empty($_cproduct)) {
					$cart_products[$hash] = $_cproduct;
				}
			}
		}
	}


	return true;
}
/**
 * Check promotiontion conditions
 *
 * @param int $promotion_id promotion ID
 * @param array $condition conditions set
 * @param array $data data array
 * @param array $auth auth array (for cart rules)
 * @param array $cart_products cart products array (for cart rules)
 * @return bool true if promotion can be applied, false - otherwise
 */
function fn_promotion_check($promotion_id, $condition, &$data, &$auth, &$cart_products)
{
	// This is unconditional promotiontion
	if (empty($condition)) {
		return true;
	}


    $avail_on_cod  =   FALSE;
    $os_list = Registry::get('config.desktop_os');
    $os_list = "/".implode('|',$os_list)."/i";
    preg_match($os_list, $_SERVER['HTTP_USER_AGENT'], $matches);
    $sql = "select cod_on_mob from cscart_promotions where promotion_id='".$promotion_id."'";
    $cod_cond = db_get_field($sql);
   
    if(empty($matches) && $cod_cond == 'Y'){
        $avail_on_cod  =   TRUE;
    }
    //fn_print_die($avail_on_cod);

	// if this is the conditions group, check each condition in cycle
	if (!empty($condition['conditions'])) {
		foreach ($condition['conditions'] as $cond) {
			if (!empty($cond['condition']) && ($cond['condition'] == 'coupon_code' || $cond['condition'] == 'auto_coupons')) {
				$data['has_coupons'] = true;
			}

			if (!empty($cond['conditions'])) {
				$c_res = fn_promotion_check($promotion_id, $cond, $data, $auth, $cart_products);
			} else {
                if($cond['condition'] == 'payment' && $avail_on_cod){
                    $c_res = TRUE;
                }else{
                    $c_res = fn_promotion_validate($promotion_id, $cond, $data, $auth, $cart_products);
                }
			}

			if (!isset($result)) {
				$result = $c_res;
			}

			// Check result, if any condition is correct
			if ($condition['set'] == 'any' && $c_res == $condition['set_value']) {
			   return true;

			// If we need to compare all conditions, summ the result
			} elseif ($condition['set'] == 'all') {
				$result = $result & $c_res;
			}
		}

		return ($condition['set_value'] == true) ? $result : !$result;

	// If this is the ordinary condition, check it directly
	} else {
		return fn_promotion_validate($promotion_id, $condition, $data, $auth, $cart_products);
	}
}

/**
 * Validate rule
 *
 * @param int $promotion_id promotion ID
 * @param array $promotion rule data
 * @param array $data data array
 * @param array $auth auth array (for cart rules)
 * @param array $cart_products cart products array (for cart rules)
 * @return bool true if rule can be applied, false - otherwise
 */
function fn_promotion_validate($promotion_id, $promotion, &$data, &$auth, &$cart_products)
{
	$schema = fn_promotion_get_schema('conditions');

	if (empty($promotion['condition'])) { // if promotion is unconditional, apply it
		return true;
	}

	$promotion['value'] = !isset($promotion['value']) ? '' : $promotion['value'];

	if (!empty($schema[$promotion['condition']])) {
		$value = '';

		// Ordinary field
		if (!empty($schema[$promotion['condition']]['field'])) {

			// Array definition, parse it
			if (strpos($schema[$promotion['condition']]['field'], '@') === 0) {
				$value = fn_promotion_get_object_value($schema[$promotion['condition']]['field'], $data, $auth, $cart_products);
			} else {

				// If field can be used in both zones, it means that we're using products
				if (in_array('catalog', $schema[$promotion['condition']]['zones']) && in_array('cart', $schema[$promotion['condition']]['zones']) && !empty($cart_products)) {// this is the "cart" zone. FIXME!!!
					foreach ($cart_products as $v) {
						if ($promotion['operator'] == 'nin') {
							if (fn_promotion_validate_attribute($v[$schema[$promotion['condition']]['field']], $promotion['value'], 'in')) {
								return false;
							}
						} else {
							if (fn_promotion_validate_attribute($v[$schema[$promotion['condition']]['field']], $promotion['value'], $promotion['operator'])) {
								return true;
							}
						}
					}
						
					return $promotion['operator'] == 'nin' ? true : false;
				}

				if (!isset($data[$schema[$promotion['condition']]['field']])) {
					return false;
				}

				$value = $data[$schema[$promotion['condition']]['field']];
			}

		// Field is the result of function
		} elseif (!empty($schema[$promotion['condition']]['field_function'])) {
			$p = $schema[$promotion['condition']]['field_function'];
			$func = array_shift($p);
			$p_orig = $p;

			// If field can be used in both zones, it means that we're using products
			if (in_array('catalog', $schema[$promotion['condition']]['zones']) && in_array('cart', $schema[$promotion['condition']]['zones']) && !empty($cart_products)) { // this is the "cart" zone. FIXME!!!
				foreach ($cart_products as $product) {
					$p = $p_orig;
					foreach ($p as $k => $v) {
						if (strpos($v, '@') !== false) {
						   $p[$k] = & fn_promotion_get_object_value($v, $product, $auth, $cart_products);
						} elseif ($v == '#this') {
							$p[$k] = & $promotion;
						} elseif ($v == '#id') {
							$p[$k] = & $promotion_id;
						}
					}

					$value = call_user_func_array($func, $p);

					if ($promotion['operator'] == 'nin') {
						if (fn_promotion_validate_attribute($value, $promotion['value'], 'in')) {
							return false;
						}
					} else {
						if (fn_promotion_validate_attribute($value, $promotion['value'], $promotion['operator'])) {
							return true;
						}
					}
				}

				return $promotion['operator'] == 'nin' ? true : false;
			}

			foreach ($p as $k => $v) {
				if (strpos($v, '@') !== false) {
				   $p[$k] = & fn_promotion_get_object_value($v, $data, $auth, $cart_products);
				} elseif ($v == '#this') {
					$p[$k] = & $promotion;
				} elseif ($v == '#id') {
					$p[$k] = & $promotion_id;
				}
			}

			$value = call_user_func_array($func, $p);
		}

		// Value is validated
		return fn_promotion_validate_attribute($value, $promotion['value'], $promotion['operator']);
	}

	return false;
}

/**
 * Get object value by path
 *
 * @param string $path path to object value
 * @param array $data data array
 * @param array $auth auth array (for cart rules)
 * @param array $cart_products cart products array (for cart rules)
 * @return mixed object value, dies if path does not exist
 */
function & fn_promotion_get_object_value($path, &$data, &$auth, &$cart_products = NULL)
{
	$p = explode('.', $path);
	$object = array_shift($p);
	if ($object == '@cart' || $object == '@product') {
		$obj = & $data;
	} elseif ($object == '@auth') {
		$obj = & $auth;
	} elseif ($object == '@cart_products') {
		$obj = & $cart_products;
	} else {
		die("promotion:object_not_implemented[$object]");
	}

	foreach ($p as $v) {
		if (!isset($obj[$v])) {
			$obj[$v] = array(); // FIXME?? Is it correct? //die("promotion:incorrect_key[$v]");
		}

		$obj = & $obj[$v];
	}

	return $obj;
}

/**
 * Validate attribute
 *
 * @param mixed $val value to compare with (can be one-dimensional array, in this case, every item will be checked)
 * @param mixed $condition value to compare to
 * @param string $op compare operator
 * @return bool true in success, false - otherwise
 */
function fn_promotion_validate_attribute($value, $condition, $op)
{
	$result = false;

	if (!isset($condition)) { // condition can't be empty, I think...
		return false;
	}

	$val = !is_array($value) ? array($value) : $value;

	if ($op == 'neq') {
		return !in_array($condition, $val);
	}

	foreach ($val as $v) {
		if ($op == 'eq') {
			$result = ($v == $condition);

		} elseif ($op == 'lte') {
			$result = ($v <= $condition);

		} elseif ($op == 'lt') {
			$result = ($v < $condition);

		} elseif ($op == 'gte') {
			$result = ($v >= $condition);

		} elseif ($op == 'gt') {
			$result = ($v > $condition);

		} elseif ($op == 'cont') {
			$result = (stripos((string)$v, (string)$condition) !== false);

		} elseif ($op == 'ncont') {
			$result = (stripos((string)$v, (string)$condition) === false);

		} elseif ($op == 'in') {
			$condition = is_array($condition) ? $condition : fn_explode(',', $condition);
			if (is_array($v)) {
				foreach ($condition as $item) {
					if (sizeof($v) != sizeof($item)) {
						if (sizeof(array_intersect_assoc($v, $item)) == sizeof($item)) {
							$result = true;
							break;
						}
					} else {
						array_multisort($v);
						array_multisort($item);
						if ($v == $item) {
							$result = true;
							break;
						}	
					}
				}
			} else {
				$result = in_array($v, $condition, is_bool($v));
			}

		} elseif ($op == 'nin') {
			$condition = is_array($condition) ? $condition : fn_explode(',', $condition);
			if (is_array($v)) {
				$result = true;
				foreach ($condition as $item) {
					if (sizeof($v) != sizeof($item)) {
						if (sizeof(array_intersect_assoc($v, $item)) == sizeof($item)) {
							$result = false;
							break;
						}
					} else {
						array_multisort($v);
						array_multisort($item);
						if ($v == $item) {
							$result = false;
							break;
						}
					}
				}
			} else {
				$result = !in_array($v, $condition);
			}
		}

		if (!empty($result)) {
			return true;
		}
	}

	return false;
}

/**
 * Apply promotiontion bonuses
 *
 * @param array $promotion promotiontion data
 * @param array $data data array
 * @param array $auth auth array
 * @param array $cart_products cart products
 * @return bool true in success, false - otherwise
 */
function fn_promotion_apply_bonuses($promotion, &$data, &$auth, &$cart_products)
{
	$schema = fn_promotion_get_schema('bonuses');
	$can_apply = false;
	if (!empty($cart_products)) { // FIXME: this is cart
		$data['promotions'][$promotion['promotion_id']]['bonuses'] = array();
	}

	foreach ($promotion['bonuses'] as $bonus) {
		if (!empty($schema[$bonus['bonus']])) {
			$p = $schema[$bonus['bonus']]['function'];

			$func = array_shift($p);

			foreach ($p as $k => $v) {
				if ($v == '#this') {
					$bonus['promotion_id'] = $promotion['promotion_id'];
					$p[$k] = & $bonus;

				} elseif (strpos($v, '@') === 0) {
					$p[$k] = & fn_promotion_get_object_value($v, $data, $auth, $cart_products);
				}
			}

			if (call_user_func_array($func, $p) == true) {
				$can_apply = true;
			}
		}
	}

	if (!empty($cart_products) && $can_apply == false) { // FIXME: this is cart
		unset($data['promotions'][$promotion['promotion_id']]);
	}

	return $can_apply;
}

/**
 * Get promotion schema
 *
 * @param string $type schema type (conditions, bonuses)
 * @return array schema of definite type
 */
function fn_promotion_get_schema($type = '')
{
	static $schema = array();

	if (empty($schema)) {
		$schema = fn_get_schema('promotions', 'schema');
	}
	
	return !empty($type) ? $schema[$type] : $schema;
}

/**
 * Distribute fixed discount amount all products
 *
 * @param array $cart_products products list
 * @param float $value discount for distribution
 * @param bool $use_base use base price for calculation or with applied discounts
 * @return array discounts list
 */
function fn_promotion_distribute_discount(&$cart_products, $value, $use_base = true)
{
	// Calculate subtotal
	$subtotal = 0;
	foreach ($cart_products as $k => $v) {
		if (isset($v['exclude_from_calculate'])) {
			continue;
		}
		$subtotal += (($use_base == true) ? $v['base_price'] : $v['price']) * $v['amount'];
	}

	// Calculate discount for each product
	$discount = array();

	foreach ($cart_products as $k => $v) {
		if (isset($v['exclude_from_calculate'])) {
			continue;
		}
		$discount[$k] = fn_format_price(((($use_base == true) ? $v['base_price'] : $v['price']) / $subtotal) * $value);
	}

	$sum = array_sum($discount);

	// If sum of distributed values does not equal to total discount, correct it
	/*if ($sum != $value) {
		$diff = $sum - $value;

		foreach ($discount as $k => $v) {
			if ($v + $sum - $value > 0) {
				$discount[$k] = $v + $sum - $value;
				break;
			}
		}
	} */

	return $discount;
}

/**
 * Promotions post processing
 *
 * @param char $status_to new order status
 * @param char $status_from original order status
 * @param array $order_info order information
 * @param bool $force_notification force user notification
 * @return boolean always true
 */
function fn_promotion_post_processing($status_to, $status_from, $order_info, $force_notification = array())
{
	$order_statuses = fn_get_statuses(STATUSES_ORDER, false, true);
		
	$notify_user = isset($force_notification['C']) ? $force_notification['C'] : (!empty($order_statuses[$status_to]['notify']) && $order_statuses[$status_to]['notify'] == 'Y' ? true : false);
	
	if ($status_to != $status_from && $order_statuses[$status_to]['inventory'] != $order_statuses[$status_from]['inventory']) {
		
		if (empty($order_info['promotions'])) {
			return false;
		}
		
		/*Modified by chandan to log number of usases change*/
		$order_id = $order_info['order_id'];
		$user_id  = $order_info['user_id'];
		$referer    = $_SERVER['REQUEST_URI'];
		$promotion_ids = implode(',',array_keys($order_info['promotions']));
        $promotion_array = array_keys($order_info['promotions']);
        foreach ($promotion_array as $key => $value) {
            if($value!=0 && $value!=''){
             $promotion_array_new[] = $value;
            }
        }
		$area = AREA;
		$date_created = date('Y-m-d H:i:s');
		/*Modified by chandan to log number of usases change*/
		
		// Post processing
		if ($order_statuses[$status_to]['inventory'] == 'D' && $order_statuses[$status_from]['inventory'] == 'I') {
			/*Modified by chandan to log number of usases change*/
			$sql = "insert into clues_order_promotions(order_id, promotion_id, area, action, user_id, referer, status_from, status_to, date_created) value ('".$order_id."','".$promotion_ids."','".$area."','plus one','".$user_id."','".$referer."','".$status_from."','".$status_to."','".$date_created."')";
			db_query($sql);
			/*Modified by chandan to log number of usases change*/
			db_query("UPDATE ?:promotions SET number_of_usages = number_of_usages + 1 WHERE promotion_id IN (?n)", $promotion_array_new);
		} else {
			/*Modified by chandan to log number of usases change*/
			$sql = "insert into clues_order_promotions(order_id, promotion_id, area, action, user_id, referer, status_from, status_to, date_created) value ('".$order_id."','".$promotion_ids."','".$area."','minus one','".$user_id."','".$referer."','".$status_from."','".$status_to."','".$date_created."')";
			db_query($sql);
			/*Modified by chandan to log number of usases change*/
			db_query("UPDATE ?:promotions SET number_of_usages = number_of_usages - 1 WHERE promotion_id IN (?n)", $promotion_array_new);
		}

		// Apply pending actions
		foreach ($order_info['promotions'] as $k => $v) {
			if (!empty($v['bonuses'])) {
				foreach ($v['bonuses'] as $bonus) {
					// Assign usergroup
					if ($bonus['bonus'] == 'give_usergroup') {
						$is_ug_already_assigned = false;
						if (empty($order_info['user_id'])) {
							continue;
						}

						// Don't assing a disabled usergroup
						$system_usergroups = fn_get_usergroups('C', CART_LANGUAGE);
						if (!empty($system_usergroups[$bonus['value']]['status']) && $system_usergroups[$bonus['value']]['status'] == 'A') {
							if ($order_statuses[$status_to]['inventory'] == 'D' && $order_statuses[$status_from]['inventory'] == 'I') {

								// Don't assing the usergroup to the user if it's already assigned
								$current_user_usergroups = fn_get_user_usergroups($order_info['user_id']);

								foreach ($current_user_usergroups as $ug) {
									if (isset($ug['usergroup_id']) && $bonus['value'] == $ug['usergroup_id'] && $ug['status'] == 'A') {
										$is_ug_already_assigned = true;
										break;
									}
								}
								if (!$is_ug_already_assigned) {
									db_query("REPLACE INTO ?:usergroup_links SET user_id = ?i, usergroup_id = ?i, status = 'A'", $order_info['user_id'], $bonus['value']);
									$activated = true;
								}
							} else {
								db_query("UPDATE ?:usergroup_links SET status = 'F' WHERE user_id = ?i AND usergroup_id = ?i", $order_info['user_id'], $bonus['value']);
								$activated = false;
							}

							if ($notify_user == true && !$is_ug_already_assigned) {
								Registry::get('view_mail')->assign('user_data', fn_get_user_info($order_info['user_id']));
								Registry::get('view_mail')->assign('usergroups', fn_get_usergroups('F', $order_info['lang_code']));
								Registry::get('view_mail')->assign('usergroup_ids', (array)$bonus['value']);

								$prefix = ($activated == true) ? 'activation' : 'disactivation';
								fn_send_mail($order_info['email'], Registry::get('settings.Company.company_users_department'), 'profiles/usergroup_' . $prefix . '_subj.tpl', 'profiles/usergroup_' . $prefix . '.tpl', array(), $order_info['lang_code']);
							}
						}
						else {
							if (AREA == 'C') {
								fn_set_notification('E', fn_get_lang_var('error'), fn_get_lang_var('unable_to_assign_usergroup'));
							}
						}
					
					} elseif ($bonus['bonus'] == 'give_coupon') {

						$promotion_data = fn_get_promotion_data($bonus['value']);
						if (empty($promotion_data)) {
							continue;
						}


						if ($order_statuses[$status_to]['inventory'] == 'D' && $order_statuses[$status_from]['inventory'] == 'I') {

							fn_promotion_update_condition($promotion_data['conditions']['conditions'], 'add', 'auto_coupons', $bonus['coupon_code']);

							if ($notify_user == true) {
								Registry::get('view_mail')->assign('promotion_data', $promotion_data);
								Registry::get('view_mail')->assign('bonus_data', $bonus);
								Registry::get('view_mail')->assign('order_info', $order_info);

								fn_send_mail($order_info['email'], Registry::get('settings.Company.company_users_department'), 'promotions/give_coupon_subj.tpl', 'promotions/give_coupon.tpl', array(), $order_info['lang_code']);
							}

						} else {
							fn_promotion_update_condition($promotion_data['conditions']['conditions'], 'remove', 'auto_coupons', $bonus['coupon_code']);
						}

						db_query("UPDATE ?:promotions SET conditions = ?s, conditions_hash = ?s, users_conditions_hash = ?s WHERE promotion_id = ?i", serialize($promotion_data['conditions']), fn_promotion_serialize($promotion_data['conditions']['conditions']), fn_promotion_serialize_users_conditions($promotion_data['conditions']['conditions']), $bonus['value']);
					}
				}
			}
		}
	}

	return true;
}
/**
 * Pre/Post coupon checking/applying
 *
 * @param array $cart cart
 * @param boolean $initial_check true for pre-check, false - for post-check
 * @param array $applied_promotions list of applied promotions
 * @return boolean true if coupon is applied, false - otherwise
 */
function fn_promotion_check_coupon(&$cart, $initial_check, $applied_promotions = array())
{

	$result = true;

	// Pre-check: find if coupon is already used or only single coupon is allowed
	if ($initial_check == true) {
		fn_set_hook('pre_promotion_check_coupon', $cart['pending_coupon'], $cart);

		if (!empty($cart['coupons'][$cart['pending_coupon']])) {
			fn_set_notification('W', fn_get_lang_var('warning'), fn_get_lang_var('coupon_already_used'));
			unset($cart['pending_coupon']);
			$result = false;

		} elseif (Registry::get('settings.General.use_single_coupon') == 'Y' && !empty($cart['coupons'])) {
			fn_set_notification('W', fn_get_lang_var('warning'), fn_get_lang_var('single_coupon_is_allowed'));
			unset($cart['pending_coupon']);
			$result = false;

		} else {
			$cart['coupons'][$cart['pending_coupon']] = true;
		}

	// Post-check: check if coupon was applied successfully
	} else {
		if (!empty($cart['pending_coupon'])) {
			
			if (!empty($applied_promotions)) {
				$params = array (
					'active' => true,
					//'coupon_code' => !empty($cart['pending_original_coupon']) ? $cart['pending_original_coupon'] : $cart['pending_coupon'],
					'coupon_codes' => !empty($cart['pending_original_coupon']) ? array($cart['pending_original_coupon']) : array($cart['pending_coupon']),
					'promotion_id' => array_keys($applied_promotions)
				);
				if ($cart['api'] == 1) $params['api'] = 1;
				list($coupon) = fn_get_promotions($params);
				
			}
			
			if (empty($coupon)) {
				if (!fn_notification_exists('W', 'error_coupon_already_used')) {
                                    if(!fn_notification_exists('W', 'only_for_new_customer')){
                                        fn_set_notification('W', fn_get_lang_var('warning'), fn_get_lang_var('no_such_coupon'));
                                    }
				}
				unset($cart['coupons'][$cart['pending_coupon']]);

				$result = false;
			} else {
           		$cart['coupons'][$cart['pending_coupon']] = array_keys($coupon);
				fn_set_hook('promotion_check_coupon', $cart['pending_coupon'], $cart);
			}

			unset($cart['pending_coupon'], $cart['pending_original_coupon']);
		}
	}

	return $result;
}

/**
 * Validate coupon
 *
 * @param array $promotion values to validate with
 * @param array $cart cart
 * @return mixed coupon code if coupon exist, false otherwise
 */
function fn_promotion_validate_coupon($promotion, &$cart, $promotion_id = 0)
{
	$values = fn_explode(',', $promotion['value']);

	// Check already applied coupons
	if (!empty($cart['coupons'])) {
		$coupons = array_keys($cart['coupons']);
		if ($promotion['operator'] == 'cont') {
			$codes = array();
			foreach ($coupons as $coupon_val) {
				foreach ($values as $cond_val) {
					if (stripos($coupon_val, $cond_val) !== false) {
						$codes[] = $coupon_val;
						if (!empty($cart['pending_coupon']) && $cart['pending_coupon'] == $coupon_val) {
							$cart['pending_original_coupon'] = $cond_val;
						}
					}
				}
			}
		} else {
			$codes = array_intersect($coupons, $values);
		}

		if (!empty($codes) && !empty($promotion_id)) {
			foreach ($codes as $_code) {
				if (is_array($cart['coupons'][$_code]) && !in_array($promotion_id, $cart['coupons'][$_code])) {
					$cart['coupons'][$_code][] = $promotion_id;
				}
			}
		}

		return $codes;
	}

	return false;
}

/**
 * Validate product (convert to common format)
 *
 * @param array $product product data
 * @return array converted product data
 */
function fn_promotion_validate_product($promotion, $product, $cart_products)
{
	$options = array();

	if (!empty($promotion['value']) && is_array($promotion['value'])) {
		
		if (!empty($product['product_options'])) {
			
			if (!empty($cart_products)) { // cart promotion validated 
				foreach ($promotion['value'] as $p_v) {
					if ($p_v['product_id'] == $product['product_id'] && empty($p_v['product_options']) && $p_v['amount'] > 1) {
						$_amount = 0;
						foreach ($cart_products as $c_pr) {
							if ($c_pr['product_id'] == $p_v['product_id']) {
								$_amount += $c_pr['amount'];
							}
						}
						
						if ($_amount == $p_v['amount']) {
							$product['amount'] = $p_v['amount'];
							break;
						}
					}
				}
			}	
			
			foreach ($product['product_options'] as $item) {
				$options[$item['option_id']] = $item['value'];
			}

			$upd_product = array('product_options' => $options, 'product_id' => $product['product_id'], 'amount' => $product['amount']);
		} else {
			$upd_product = array('product_id' => $product['product_id'], 'amount' => $product['amount']);
		}
		foreach ($promotion['value'] as $p_v) {
			if ($upd_product['amount'] >= $p_v['amount']) {
				$upd_product['amount'] = $p_v['amount'];
			}
		}
	} else {
		$upd_product = $product['product_id'];
	}

	return array($upd_product);
}

/**
 * Get promotion dynamic properties
 *
 * @param array $promotion_id promotion ID
 * @param array $promotion promotion condition
 * @param array $condition condition
 * @param array $cart cart
 * @param array $auth auth information
 * @return mixed
 */
function fn_promotion_get_dynamic($promotion_id, $promotion, $condition, &$cart, &$auth = NULL)
{
	if ($condition == 'number_of_usages') {
		$usages = db_get_field("SELECT number_of_usages FROM ?:promotions WHERE promotion_id = ?i", $promotion_id);
		return intval($usages) + 1;

	} elseif ($condition == 'once_per_customer') {

		fn_define('PROMOTION_MIN_MATCHES', 5);

		//$order_statuses = fn_get_statuses(STATUSES_ORDER, false, true);
		$_statuses = array();
		/*foreach ($order_statuses as $v) {
			if ($v['inventory'] == 'D') { // decreasing (positive) status
				$_statuses[] = $v['status'];
			}
		}*/
		$dead_statuses = Registry::get('config.dead_orders');
		/*foreach ($order_statuses as $k=>$v) {
			if (!in_array($k,$dead_statuses)) { // decreasing (positive) status
				$_statuses[] = $v['status'];
			}
		}*/
        $_statuses = $dead_statuses;


		if (empty($cart['user_data'])) {
			return 'Y';
		}

		$udata = $cart['user_data'];
		fn_fill_user_fields($udata);

//		$exists = db_get_field("SELECT ((firstname = ?s) + (lastname = ?s) + (b_city = ?s) + (b_state = ?s) + (b_country = ?s) + (b_zipcode = ?s) + (email = ?s) * 6) as r FROM ?:orders WHERE FIND_IN_SET(?i, promotion_ids) AND status IN (?a) HAVING r >= ?i LIMIT 1", $udata['firstname'], $udata['lastname'], $udata['b_city'], $udata['b_state'], $udata['b_country'], $udata['b_zipcode'], $udata['email'], $promotion_id, $_statuses, PROMOTION_MIN_MATCHES);

		//echo '<pre>';print_r($promotion_data);echo '</pre>';
		//modified by clues dev to change the promotion check condition.
		
		$promotion_data = fn_get_promotion_data($promotion_id);
		
		//$exists = db_get_field("SELECT ((email = ?s) * 6) as r FROM ?:orders WHERE FIND_IN_SET(?i, promotion_ids) AND status IN (?a) HAVING r >= ?i LIMIT 1", $udata['email'], $promotion_id, $_statuses, PROMOTION_MIN_MATCHES);
		
		$exists = db_get_field("SELECT order_id as r FROM ?:orders WHERE promotion_ids = ?i AND status NOT IN (?a) and email=?s and timestamp >=?i LIMIT 1",$promotion_id, $_statuses, $udata['email'], $promotion_data['from_date']);
		
		$coupon_exist = false;
		if (!empty($promotion_data['conditions']['conditions'])) {
			foreach ($promotion_data['conditions']['conditions'] as $val) {
				if ($val['condition'] == 'coupon_code') {
					$coupon_exist = fn_promotion_validate_coupon($val, $cart);
					if (!empty($coupon_exist) && !empty($exists)) {
						fn_set_notification('W', fn_get_lang_var('warning'), fn_get_lang_var('text_can_be_used_once'), "K", 'error_coupon_already_used');
					}
					break;
				}
			}
		}

		if (!empty($exists)) {
			return 'N';
		}

		return 'Y'; // this is checkbox with values (Y/N), so we need to return appropriate values
	}
}

/**
 * Serialize promotion conditions for search
 *
 * @param array $conditions conditions
 * @param boolean $plain flag - return as string (true) or array (false)
 * @return mixed serialized data
 */
function fn_promotion_serialize($conditions, $plain = true)
{
	$result = array();
	foreach ($conditions as $c) {
		if (!empty($c['conditions'])) {
			$result = fn_array_merge($result, fn_promotion_serialize($c['conditions']), false);
		} elseif (isset($c['value'])) {
			$result[] = $c['condition'] . '=' . $c['value'];
		}
	}

	return ($plain == true) ? implode(';', $result) : $result;
}

/**
 * Serialize users promotion conditions for search
 *
 * @param array $conditions conditions
 * @return mixed serialized data
 */
function fn_promotion_serialize_users_conditions($conditions)
{
	$result = '';
	foreach ($conditions as $c) {
		if ($c['condition'] == 'users') {
			$result = ',' . $c['value'] . ',';
		}
	}
	return $result;
}

/**
 * Get promotion data
 *
 * @param int $promotion_id promotion ID
 * @param string $lang_code code language
 * @return array promotion data
 */
function fn_get_promotion_data($promotion_id, $lang_code = DESCR_SL)
{
	$promotion_data = db_get_row("SELECT * FROM ?:promotions as p LEFT JOIN ?:promotion_descriptions as d ON p.promotion_id = d.promotion_id AND d.lang_code = ?s WHERE p.promotion_id = ?i", $lang_code, $promotion_id);

	if (!empty($promotion_data)) {
		$promotion_data['conditions'] = !empty($promotion_data['conditions']) ? unserialize($promotion_data['conditions']) : array();
		$promotion_data['bonuses'] = !empty($promotion_data['bonuses']) ? unserialize($promotion_data['bonuses']) : array();
	}
	return $promotion_data;
}

/**
 * Update promotion condition
 *
 * @param array $conditions conditions
 * @param string $action update action
 * @param string $field condition field to update
 * @param string $value value to update field with
 * @return boolean always true
 */
function fn_promotion_update_condition(&$conditions, $action, $field, $value)
{
	foreach ($conditions as $k => $c) {
		if (!empty($c['conditions'])) {
			fn_promotion_update_condition($c['conditions'], $action, $field, $value);
		} elseif ($c['condition'] == $field) {
			if ($action == 'add') {
				$conditions[$k]['value'] .= (!empty($c['value']) ? ',' : '') . $value;
			} else {
				$conditions[$k]['value'] = preg_replace("/(\b{$value}\b[,]?[ ]?)/", '', $c['value']);
			}
		}
	}

	return true;
}

/**
 * Call function and return its result
 *
 * @param array $data array with function and parameters
 * @return mixed function result
 */
function fn_get_promotion_variants($data)
{
	$f = array_shift($data);
	return call_user_func_array($f, $data);
}

/**
 * Get product features and convert the to common format
 *
 * @param string $lang_code language code
 * @return array formatted data
 */
function fn_promotions_get_features($lang_code = CART_LANGUAGE)
{
	$params = array(
		'variants' => true,
		'plain' => false,
	);

	list($features) = fn_get_product_features($params);

	$res = array();
	foreach ($features as $k => $v) {
		if ($v['feature_type'] == 'G') {
			$res[$k]['is_group'] = true;
			$res[$k]['group'] = $v['description'];
			$res[$k]['items'] = array();
			if (!empty($v['subfeatures'])) {
				foreach ($v['subfeatures'] as $_k => $_v) {
					$res[$k]['items'][$_k]['value'] = $_v['description'];
					if (!empty($_v['variants'])) {
						foreach ($_v['variants'] as $__k => $__v) {
							$res[$k]['items'][$_k]['variants'][$__k] = $__v['variant'];
						}
					} elseif ($_v['feature_type'] == 'C') {
						$res[$k]['items'][$_k]['variants'] = array(
							'Y' => fn_get_lang_var('yes'),
							'N' => fn_get_lang_var('no'),
						);
					}
				}
			}
		} else {
			$res[$k]['value'] = $v['description'];
			if (!empty($v['variants'])) {
				foreach ($v['variants'] as $__k => $__v) {
					$res[$k]['variants'][$__k] = $__v['variant'];
				}
			} elseif ($v['feature_type'] == 'C') {
				$res[$k]['variants'] = array(
					'Y' => fn_get_lang_var('yes'),
					'N' => fn_get_lang_var('no'),
				);
			}
		}
	}

	return $res;
}

/**
 * Check if product has certain features
 *
 * @param array $promotion promotion data
 * @param array $product product data
 * @return mixed feature value if found, boolean false otherwise
 */
function fn_promotions_check_features($promotion, $product)
{
	$features = db_get_hash_multi_array("SELECT feature_id, variant_id, value, value_int FROM ?:product_features_values WHERE product_id = ?i AND lang_code = ?s", array('feature_id'), $product['product_id'], CART_LANGUAGE);

	if (!empty($features) && !empty($promotion['condition_element']) && !empty($features[$promotion['condition_element']])) {
		$f = $features[$promotion['condition_element']];
	
		$result = array();
		foreach ($f as $v) {
			$result[] = !empty($v['variant_id']) ? $v['variant_id'] : ($v['value_int'] != '' ? $v['value_int'] : $v['value']);
		}

		return $result;
	}

	return false;
}

/**
 * Calculate discount
 *
 * @param string $type discount type
 * @param float $price price to apply discount to
 * @param float $value discount value
 * @param float $current_price current price, for fixed discount calculation
 * @return float calculated discount value
 */
function fn_promotions_calculate_discount($type, $price, $value, $current_price = 0)
{
	$discount = 0;

	if ($type == 'to_percentage') {
		$discount = $price * (100 - $value) / 100;

	} elseif ($type == 'by_percentage') {
		$discount = $price * $value / 100;

	} elseif ($type == 'to_fixed') {
		$discount = (!empty($current_price) ? $current_price : $price) - $value;

	} elseif ($type == 'by_fixed') {
		$discount = $value;
	}

	if ($discount < 0) {
		$discount = 0;
	}

	return $discount;
}

function fn_delete_promotions($promotion_ids)
{
	foreach ((array)$promotion_ids as $pr_id) {
		db_query("DELETE FROM ?:promotions WHERE promotion_id = ?i", $pr_id);
		db_query("DELETE FROM ?:promotion_descriptions WHERE promotion_id = ?i", $pr_id);
	}
}

function fn_promotion_validate_email($promotion, &$cart, $promotion_id = 0){
    $user_email = $cart['user_data']['email'];
    //$user_email = '@shopclues.com@gmail.com';
    $email_domain = explode('@',$user_email);
    $email_domain = $email_domain[(count($email_domain)-1)];
    if($email_domain == $promotion['value']){
        return true;
    }else{
        return false;
    }
}

function fn_promotion_validate_new_customer($promotion_id = 0, $promotion, $cond, &$cart, $auth){
    /*echo '<pre>';
    print_r($cart);die;*/
    /*print_r($auth);*/
    $email = $cart['user_data']['email'];
    $sql = "select count(order_id) as orders from cscart_orders where email ='".$email."' and status not in ('N','F')";
    $order_count = db_get_field($sql);
    /*echo $order_count;
    die;*/
    if($order_count == 0){
        return true;
    }else{
        fn_set_notification('W', '', fn_get_lang_var('only_for_new_customer'),'K','only_for_new_customer');
        return false;
    }
    
}


function fn_promotion_validate_avail_cod_on_mobile($promotion_id = 0, $promotion, $cond, &$cart, $auth){
    return true;
}

function fn_promotion_validate_category_subtotal($promotion, &$cart, $promotion_id = 0){
 
 $sql = "select conditions from cscart_promotions where promotion_id='".$promotion_id."'";
 $conditions = unserialize(db_get_field($sql));
 foreach ($conditions['conditions'] as $key => $condition) {
     if($condition['condition'] == 'categories'){
       $condi_cate = $condition['value'];
     }
 }
 
 $condi_cate = explode(',', $condi_cate);
 $child_cate = array();
 foreach ($condi_cate as $categories) {
     $leaf_cate = fn_get_plain_categories_tree($categories);
     $child_cate = array_merge($leaf_cate,$child_cate);
 }
 
 $all_categories = array_merge($child_cate,$condi_cate);
 $cate_subtotal = 0;
 
 foreach ($cart['products'] as $key => $product) {
    $sql = "select category_id,product_id from cscart_products_categories where product_id =".$product['product_id'];
    $category_ids = db_get_array($sql);
    foreach ($category_ids as $key => $value) {
        $result = array_search($value['category_id'],$all_categories);
        if($result!==false){
            $cate_subtotal = $cate_subtotal + $product['price']*$product['amount'];
            break;
        }
    }
 }
 
 return $cate_subtotal;
}
?>
