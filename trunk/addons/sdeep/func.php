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
// $Id: func.php 12865 2011-07-05 06:57:22Z 2tl $
//
if ( !defined('AREA') ) { die('Access denied'); }

function fn_sdeep_get_vendor_rating_params() {
	return db_get_array("SELECT * FROM ?:sdeep_rating_params ORDER BY rating_id");
}
function fn_sdeep_get_lang_icons() {
	return db_get_array("SELECT * FROM ?:sdeep_lang_icons");
}

function fn_sdeep_show_cod_warning($payment_id) {
	if(Registry::get('addons.sdeep.is_alternate_cod_behaviour') == 'Y') {
		$cod_id = Registry::get('addons.sdeep.cod_payment_id');
		if($payment_id == $cod_id) {
			//$msg = Registry::get('addons.sdeep.warning_cod_selected');
			//fn_set_notification('W', '', $msg, 'I');
		}
	}
}
function fn_sdeep_get_unreviewed_orders($auth) {
	if($auth['user_id']) {
		$db_orders = db_get_array("SELECT order_id,company_id FROM ?:orders WHERE user_id=?i AND status='C' AND rating_info='' AND order_id NOT IN(SELECT order_id from clues_user_product_rating) order by timestamp desc ", $auth['user_id']);
		$orders = array();
		foreach($db_orders as $order) {
			if($order['company_id']) {
				$order['company_name'] = fn_get_company_name($order['company_id']);
				$orders[$order['company_id']] = $order;
			}
		}
		return $db_orders;
	}
	return false;
}

function fn_sdeep_get_unreviewed_products($auth){ //this function return the order id and product ids

if($auth['user_id'])
{
	$i=0;
		
	$result=db_get_array("select cscart_order_details.order_id, cscart_order_details.product_id,cscart_order_details.price,cscart_order_details.extra,cscart_orders.timestamp,cscart_orders.payment_id
from cscart_order_details 
left join clues_user_product_rating
on (cscart_order_details.order_id=clues_user_product_rating.order_id && cscart_order_details.product_id=clues_user_product_rating.product_id)
left join cscart_orders on (cscart_orders.order_id = cscart_order_details.order_id)
where clues_user_product_rating.order_id is null and clues_user_product_rating.product_id is null and cscart_orders.status='C' and 
 cscart_orders.user_id=?i order by cscart_orders.timestamp desc",$auth['user_id']);
	
	foreach($result as $orders)
	{
		$extra=array();
		
		$cod_id = Registry::get('addons.sdeep.cod_payment_id');
		
		if($orders['payment_id']!=$cod_id)
		{
			$extra=@unserialize($orders['extra']);
		}
		
		
		
		$unreview_list[$i]['order_id']=$orders['order_id'];
		$unreview_list[$i]['status']='C';
		$unreview_list[$i]['total']=$orders['price'];
		$unreview_list[$i]['sale_date']=date('d/m/Y',$orders['timestamp']);
		$unreview_list[$i]['product_id']=$orders['product_id'];
		if(!empty($extra))
		$unreview_list[$i]['reward_point']=$extra['points_info']['reward'];
		else
		$unreview_list[$i]['reward_point']='';
			
		$i++;	
	}
	
	return $unreview_list;
}

}

//Added by shashi kant to show submitted feedbacks on my account page
function fn_get_reviewed_products($auth){

if($auth['user_id'])
{
	$i=0;
	$result=db_get_array("select cscart_order_details.order_id, cscart_order_details.product_id,clues_user_product_rating.shipping_time, 
                            clues_user_product_rating.shipping_cost,
                            clues_user_product_rating.product_quality,clues_user_product_rating.value_for_money, clues_user_product_rating.review_merchant,
                            clues_user_product_rating.product_rating,clues_user_product_rating.review, cscart_order_details.price,cscart_order_details.extra,
                            cscart_companies.company,clues_user_product_rating.creation_date,
                            cscart_orders.timestamp, cscart_orders.payment_id from cscart_order_details left join clues_user_product_rating on 
                            (cscart_order_details.order_id=clues_user_product_rating.order_id and cscart_order_details.product_id=clues_user_product_rating.product_id) 
                            left join cscart_orders on cscart_orders.order_id = cscart_order_details.order_id
                            left join cscart_companies on  cscart_orders.company_id = cscart_companies.company_id
                            where clues_user_product_rating.order_id is not null and 
                            clues_user_product_rating.product_id is not null and cscart_orders.status='C' and cscart_orders.user_id=?i order by clues_user_product_rating.creation_date desc",$auth['user_id']);
	
	foreach($result as $orders)
	{
		$extra=array();
		
		$cod_id = Registry::get('addons.sdeep.cod_payment_id');
		
		if($orders['payment_id']!=$cod_id)
		{
			$extra=@unserialize($orders['extra']);
		}
		
		$time = strtotime($orders['creation_date']);
                $date = date('d M Y H:i:s', $time);
		
		$review_list[$i]['order_id']=$orders['order_id'];
		$review_list[$i]['status']='C';
		$review_list[$i]['total']=$orders['price'];
		$review_list[$i]['sale_date']=date('d/m/Y',$orders['timestamp']);
		$review_list[$i]['product_id']=$orders['product_id'];
                $review_list[$i]['shipping_time']=$orders['shipping_time'];
                $review_list[$i]['shipping_cost']=$orders['shipping_cost'];
                $review_list[$i]['product_quality']=$orders['product_quality'];
                $review_list[$i]['value_for_money']=$orders['value_for_money'];
                $review_list[$i]['review_merchant']=$orders['review_merchant'];
                $review_list[$i]['product_rating']=$orders['product_rating'];
                $review_list[$i]['review']=$orders['review'];
                $review_list[$i]['creation_date']=$date;
                $review_list[$i]['company']=$orders['company'];
		if(!empty($extra))
		$review_list[$i]['reward_point']=$extra['points_info']['reward'];
		else
		$review_list[$i]['reward_point']='';
			
		$i++;	
	}
	return $review_list;
}

}

function fn_my_feedbacks($auth){ 

if($auth['user_id'])
{
fn_sdeep_get_reviewed_products($auth);
fn_get_reviewed_products($auth);
}
}
//End added by shashi kant to show submitted feedbacks on my account page

function fn_sdeep_get_rating($company_id, $update = false) {
	if($company_id) {
		// TODO: adjust to dynamic rating params if required
		$rate = db_get_field("SELECT avg(avg_rate) FROM clues_user_product_rating WHERE company_id=?i and avg_rate>0", $company_id);
                return $rate;
		/*$orders = db_get_array("SELECT rating_info FROM ?:orders WHERE company_id=?i", $company_id);
		if(is_array($orders) && !empty($orders)) {
			foreach($orders as $order) {
				$review = $order['rating_info'];
				if(is_string($review)) {
					$review = @unserialize($review);
					if(is_array($review)) {
						$num_of_reviews++;
						foreach($review as $k => $v) {
							if($k !== 'timestamp') {
								$total_mark += $v;
							}
						}
					}
				}
			}
			if($num_of_reviews) {
				$total_mark = round($total_mark / $num_of_reviews / 4, 2);
				if($update) {
					db_query("UPDATE ?:companies SET sdeep_rating=?d WHERE company_id=?i", $total_mark, $company_id);
				}
				return $total_mark;
			}
		}*/
	}
}
function fn_sdeep_get_terms($company_id) {
	return @unserialize(db_get_field("SELECT terms FROM ?:companies WHERE company_id=?i", $company_id));
}

function fn_sdeep_placement_routines($order_id, $order_info, $force_notification, $clear_cart, $action, $display_notification) {
/*if($order_info['status'] == 'O') {

		Registry::get('view_mail')->assign('items', $order_info['items']);

		//fn_send_mail($order_info['email'], array('email' => $company['company_orders_department'], 'name' => $company['company_name']), 'addons/sdeep/rate_product_subj.tpl', 'addons/sdeep/rate_product_body.tpl', '', $order_info['lang_code']);

		fn_send_mail($order_info['email'], Registry::get('settings.Company.company_orders_department'), 'addons/sdeep/rate_product_subj.tpl', 'addons/sdeep/rate_product_body.tpl', '', $order_info['lang_code']);
	}*/
}

function fn_sdeep_change_order_status($status_to, $status_from, $order_info, $force_notification, $order_statuses, $place_order = true) {
	if($status_to == 'C') {
		Registry::get('view_mail')->assign('order_info', $order_info);		
		Registry::get('view_mail')->assign('status_to', $status_to);
		fn_send_mail($order_info['email'], Registry::get('settings.Company.company_orders_department'), 'addons/sdeep/rate_product_subj.tpl', 'addons/sdeep/rate_product_body.tpl', '', $order_info['lang_code']);

	}
}

function fn_sdeep_get_vendor_info($company_id) {
	
	$sql=db_get_row("SELECT company_id, icon_url, company, is_trm FROM ?:companies WHERE company_id=?i", $company_id);
	//echo $sql;die;
	return $sql;
}
function fn_get_vendor_state($company_id)
{
	$sql="select c.city,c.state as short_form,cs.state_id,csd.state from cscart_companies c 
          left join cscart_states cs on cs.code=c.state and cs.country_code = 'IN'
          left join cscart_state_descriptions csd on csd.state_id = cs.state_id
          where c.company_id='".$company_id."'"; 
      
	  $state_city=db_get_row($sql);

 return $state_city;
	}


function fn_sdeep_is_trm($company_id) {
	$is_trm = db_get_field("SELECT is_trm FROM ?:companies WHERE company_id=?i", $company_id);
	if($is_trm === 'Y') {
		return true;
	}
	return false;
/*
	if(fn_sdeep_get_rating($company_id)) {
		$all_vendors = db_get_array("SELECT company_id FROM ?:companies ORDER BY sdeep_rating DESC");
		$percentage = Registry::get('addons.sdeep.trm_percentage');
		foreach($all_vendors as $k => $vendor) {
			if($vendor['company_id'] == $company_id) {
				$position = $k+1;
			}
		}
		return ($percentage >= 100/count($all_vendors) * $position);
	}
*/
}
function fn_sdeep_get_vendor_detailed_rating($company_id, $timestamp = 0) {
	if($company_id) {
		// TODO: adjust to dynamic rating params if required
		$orders = db_get_array("SELECT rating_info FROM ?:orders WHERE company_id=?i and rating_info !=''", $company_id);
		if(is_array($orders) && !empty($orders)) {
			$feedback['count'] = 0;
			foreach($orders as $order) {
				$review = $order['rating_info'];
				if(is_string($review)) {
					$review = @unserialize($review);
					if(is_array($review)) {
						if($review['timestamp'] > $timestamp) {
							$feedback['count']++;
							foreach($review as $k => $v) { 
								if($k !== 'timestamp') {
									$total_mark += $v;
								}
							}
							if($total_mark <= 4) {
								$feedback['negative']++;
							}
							if($total_mark < 13 && $total_mark > 4) {
								$feedback['neutral']++;
							}
							if ($total_mark >= 13) {
								$feedback['positive']++;
							}
							$total_mark = 0;
						}
					}
				}
			}
			if(!$feedback['count']) {
				$feedback['negative'] = 0;
				$feedback['neutral'] = 0;
				$feedback['positive'] = 0;
			} else {
				$feedback['negative'] = round($feedback['negative'] / $feedback['count'] * 100);
				$feedback['neutral'] = round($feedback['neutral'] / $feedback['count'] * 100);
				$feedback['positive'] = round($feedback['positive'] / $feedback['count'] * 100);
			}
			return $feedback;
		}
	}
}
function fn_sdeep_get_vendor_detailed_rating_30days($company_id) {
	return fn_sdeep_get_vendor_detailed_rating($company_id, strtotime('-30 days'));
}
function fn_sdeep_get_vendor_detailed_rating_90days($company_id) {
	return fn_sdeep_get_vendor_detailed_rating($company_id, strtotime('-90 days'));
}
function fn_sdeep_get_vendor_detailed_rating_365days($company_id) {
	return fn_sdeep_get_vendor_detailed_rating($company_id, strtotime('-365 days'));
}
function fn_sdeep_get_product_features_variants() {
	$return = (db_get_array("SELECT * FROM ?:product_feature_variant_descriptions AS d LEFT JOIN ?:product_feature_variants AS v ON v.variant_id = d.variant_id WHERE v.feature_id=?i AND lang_code=?s order by d.variant asc", Registry::get('addons.sdeep.features_brands_id'), DESCR_SL));
        return $return;
}
function fn_sdeep_get_vendors_features_variants($company_id, $system_fv = false) {
        if(!$system_fv) {
		$system_fv = fn_sdeep_get_product_features_variants();
	}
	$vendors_fv = db_get_field("SELECT sdeep_features FROM ?:companies WHERE company_id=?i", $company_id);
	$vendors_fv = @unserialize($vendors_fv);
	if(is_array($vendors_fv)) {
		foreach($system_fv as &$sfv) {
			if(in_array($sfv['variant_id'], $vendors_fv)) {
				$sfv['exists'] = true;
			}
		}
	}
        return $system_fv;
}
function fn_sdeep_get_stars($rating_value)
{
	static $cache = array();
	if (!isset($cache[$rating_value])) {
		$cache[$rating_value] = array();
		$cache[$rating_value]['full'] = floor($rating_value);
		$cache[$rating_value]['part'] = $rating_value - $cache[$rating_value]['full'];
		$cache[$rating_value]['empty'] = 5 - $cache[$rating_value]['full'] - (($cache[$rating_value]['part'] == 0) ? 0 : 1);
		if (!empty($cache[$rating_value]['part'])) {
			if ($cache[$rating_value]['part'] <= 0.25) {
				$cache[$rating_value]['part'] = 1;
			} elseif ($cache[$rating_value]['part'] <= 0.5) {
				$cache[$rating_value]['part'] = 2;
			} elseif ($cache[$rating_value]['part'] <= 0.75) {
				$cache[$rating_value]['part'] = 3;
			} elseif ($cache[$rating_value]['part'] <= 0.99) {
				$cache[$rating_value]['part'] = 4;
			}
		}
	}
	return $cache[$rating_value];
}
function fn_sdeep_get_auth_dealer_info($vendor_id) {
	$vendors_fv = db_get_field("SELECT sdeep_features FROM ?:companies WHERE company_id=?i", $vendor_id);
	$vendors_fv = @unserialize($vendors_fv);
	if($vendors_fv) {
		$auth_dealer_info = db_get_array("SELECT l.object_id, l.image_id, image_path FROM cscart_images_links as l LEFT JOIN cscart_images as i ON i.image_id = l.image_id WHERE l.object_type='feature_variant' AND l.object_id IN(".implode(',', $vendors_fv).")");
		foreach ($auth_dealer_info as &$v) {
			if($v['image_id'] && $v['image_path']) {
				$image_data['images_image_id'] = $v['image_id'];
				$image_data['image_path'] = $v['image_path'];
				$image_data = fn_attach_absolute_image_paths($image_data, 'feature_variant');
				$v['thumb_path'] = fn_generate_thumbnail($image_data['image_path'], Registry::get('addons.sdeep.brand_thumb_width'));
			}
		}
		return $auth_dealer_info;
	}
}

function merchant_detail_rating($company_id, $timestamp = 0){
	if($company_id) { 
		// TODO: adjust to dynamic rating params if required
		$merchant_ratings = db_get_array("SELECT shipping_time, shipping_cost, product_quality, value_for_money, creation_date FROM clues_user_product_rating WHERE company_id=?i and avg_rate>0", $company_id);
		if(is_array($merchant_ratings) && !empty($merchant_ratings)) {
			$feedback['count'] = 0;
			foreach($merchant_ratings as $merchant_rating) {
				if(strtotime($merchant_rating['creation_date']) > $timestamp) {
					$feedback['count']++;
					$total_mark = $merchant_rating['shipping_time'] + $merchant_rating['shipping_cost'] + $merchant_rating['product_quality'] + $merchant_rating['value_for_money'];
					if($total_mark <= 4) {
						$feedback['negative']++;
					}
					if($total_mark < 13 && $total_mark > 4) {
						$feedback['neutral']++;
					}
					if ($total_mark >= 13) {
						$feedback['positive']++;
					}
					$total_mark = 0;
				}
					
			}
			if(!$feedback['count']) {
				$feedback['negative'] = 0;
				$feedback['neutral'] = 0;
				$feedback['positive'] = 0;
			} else {
				$feedback['negative'] = round($feedback['negative'] / $feedback['count'] * 100);
				$feedback['neutral'] = round($feedback['neutral'] / $feedback['count'] * 100);
				//$feedback['positive'] = round($feedback['positive'] / $feedback['count'] * 100);
                                $feedback['positive'] = 100 - ($feedback['negative']+ $feedback['neutral']);                               
                        }
			return $feedback;
		}
	}
}

function merchant_detail_rating_30days($company_id) {
	return merchant_detail_rating($company_id, strtotime('-30 days'));
}
function merchant_detail_rating_90days($company_id) {
	return merchant_detail_rating($company_id, strtotime('-90 days'));
}
function merchant_detail_rating_365days($company_id) {
	return merchant_detail_rating($company_id, strtotime('-365 days'));
}
function fn_get_products_other_seller($pro_id,$comp_id,$pro_name)
{
	
	$sql="
SELECT p.product_id,cpp.price,p.list_price, p.amount, sum(po.amount), c.company_id
FROM cscart_products p
LEFT JOIN cscart_companies c ON c.company_id = p.company_id
LEFT JOIN cscart_product_descriptions pd ON pd.product_id = p.product_id
LEFT OUTER JOIN cscart_product_options_inventory po ON po.product_id = p.product_id
inner join cscart_product_prices cpp on cpp.product_id=p.product_id
WHERE 
c.status = 'A'
and p.status = 'A'
and pd.product =  '".$pro_name."'
and c.company_id != '".$comp_id."'
group by p.product_id
having if (sum(po.amount) is null,p.amount ,sum(po.amount)) > 0";

	if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
	{
			$memcache = $GLOBALS['memcache'];
			
			$key = md5($sql);
			if($mem_value = $memcache->get($key)){
				$result = $mem_value;
			}else{
				$result=db_get_array($sql);
				$status = $memcache->set($key,$result, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time'));
				if(!$status){
					$memcache->delete($key);
				}
			}   
	 }else{
		 $result=db_get_array($sql);
	 }
	return $result;
}
function fn_get_extra_block_content($pro_id,$sql_id)
{
	$query="";
	$res="";
	$sql="select sql_query from clues_product_blocks_sql where id='".$sql_id."'";
	$query=db_get_field($sql);
	$query=str_replace('[product_id]',$pro_id,$query);
	if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
	{
			$memcache = $GLOBALS['memcache'];
			
			$key = md5($query);
			if($mem_value = $memcache->get($key)){
				$res = $mem_value;
			}else{
				$res=db_get_array($query);
				$status = $memcache->set($key,$res, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time'));
				if(!$status){
					$memcache->delete($key);
				}
			}   
	 }else{
			$res=db_get_array($query);
	 }
	return $res;
}
//function by ankur to get upsell product
function fn_get_upsell_products($cat_id,$pro_id)
{
	$db_fetch="select sql_query from clues_product_blocks_sql where id='".Registry::get('config.upsell_product_query_id')."'";
	if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
	{
			$memcache = $GLOBALS['memcache'];
			
			$key = md5($db_fetch);
			if($mem_value = $memcache->get($key)){
				$query = $mem_value;
			}else{
				$query=db_get_field($db_fetch);
				$status = $memcache->set($key,$query, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time'));
				if(!$status){
					$memcache->delete($key);
				}
			}   
	 }else{
			$query=db_get_field($db_fetch);
	 }
	  
	
	
	$query=str_replace('[category_id]',$cat_id,$query);
	$query=str_replace('[product_id]',$pro_id,$query);

	if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
	{
			$memcache = $GLOBALS['memcache'];
			
			$key = md5($query);
			if($mem_value = $memcache->get($key)){
				$res = $mem_value;
			}else{
				$res=db_get_array($query);
				$status = $memcache->set($key,$res, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time'));
				if(!$status){
					$memcache->delete($key);
				}
			}   
	 }else{
			$res=db_get_array($query);
	 }
	return $res;
}
//function end

//function by ankur to get the new upsell products section and their info
function fn_get_upsell_product_info($product_id)
{
  $sql="select p.product_id,pd.product,p.list_price,cpp.price,p.promotion_id 
      from cscart_products p
      inner join cscart_product_prices cpp on p.product_id=cpp.product_id
      inner join cscart_product_descriptions pd on p.product_id=pd.product_id
      inner join clues_upsell_products cup on p.product_id=cup.upsell_product_id
      where cup.product_id='".$product_id."'";
  $result=db_get_array($sql);
  return $result;
}

function fn_get_upsell_product_coupon_code($promotion_id)
{
   $update_time = strtotime(date("Y-m-d "));
   
  $sql="select conditions_hash from cscart_promotions where promotion_id='".$promotion_id."' and status='A' and to_date>='".$update_time ."' ";
  $result=db_get_field($sql);
  $coupon_code='';
  if(stristr($result,'coupon_code=')){
      $result = explode(';',$result);
      foreach($result as $tmp){
            $t=explode('=',$tmp);
            if($t['0'] == 'coupon_code'){
                $coupon_code = $t['1'];
            }
       }
  }
  return $coupon_code;
}

function fn_process_coupon($data){
$cc=array();

foreach($data as $value)
{
	if(!empty($value['coupon_code']))
	{
	if(!in_array($value['coupon_code'],$cc)){
		$cc[]= $value['coupon_code'];
		
		$cc_sep=implode(', ',$cc);
	}
	}
	
}

return $cc_sep;
}

function fn_get_extra_block_solr_content($pro_id,$query_data, $product_count)
{
    //echo $query_data;
    $start_tag_count = substr_count($query_data,'<');
    $end_tag_count = substr_count($query_data,'>');
    if($start_tag_count != $end_tag_count){
        return array();
    }
    if(Registry::get('config.solr')) {
        $client = new SolrClient(Registry::get('config.solr_url'));
        $query_tokens = explode('<',$query_data);
        $token_array = array();
        foreach($query_tokens as $query_token)
        {
                if(strpos($query_token, '>')){
                        $token_value = substr($query_token, 0, strpos($query_token, '>'));		
                        $token_array[$token_value] = $token_value;
                }
        }
        if(!empty($token_array)){
            $fl = implode(array_keys($token_array), ' ');

            
            $query = new SolrQuery();
            $query->setQuery('product_id:'.$pro_id);
            $query->setParam('fl',$fl);
            try{
                $solrResult = $client->query($query)->getResponse();
                $total_items = $solrResult->response->numFound; 
            }catch(Exception $e){

            }
            $query_cond = (array)$solrResult->response->docs['0'];
            $query_cond = array_merge($token_array,$query_cond);

            foreach($query_cond as $cond_key=>$cond_value){
                if($cond_key == $cond_value){
                    $cond_value = TIME;
                }
                $query_data = str_replace("<$cond_key>",$cond_value,$query_data);

            }
        }
        $query = new SolrQuery();
        $query->setQuery($query_data);
        $query->setParam('fl','product price image_url seo_name list_price product_rating');
        $query->setRows($product_count);
        //echo $query;
        try{
            $solrResult = $client->query($query)->getResponse();
            $total_items = $solrResult->response->numFound; 
        }catch(Exception $e){

        }
        foreach($solrResult->response->docs as $pr=>$pord){
                $products[] = (array) $pord;
        }
        //echo '<pre>';print_r($products);die;
        return $products;
    }else
    {
        return array();
    }
}
/* Start code by  Munish on 24 Dec 2013 */
function fn_sdeep_ask_merchant($comp_id)
{
    $result = db_get_array("SELECT cc.ask_merchant_sla as percent,cbq.name,cbq.icon_url,cc.res_per_ten_days FROM cscart_companies as cc 
                        INNER JOIN clues_company_badge_answer as ccba ON ccba.id=cc.ask_merchant_badge_answer 
                        INNER JOIN clues_badge_questions as cbq ON cbq.id=ccba.question_id 
                        WHERE cc.company_id=".$comp_id);

    foreach ($result as $value) {
                        $response = $value;
                    }
                    return $response;
}

function fn_shipping_percentage($company_id,$percent){
     
     
     $fetch_sla = db_get_row("select 0_12_sla as 12_hr,0_24_sla as 24_hr,0_36_sla as 36_hr,24_48_sla as 48_hr,48_72_sla as 72_hr,
                                    72_96_sla as 96_hr,rating,timestamp,total,total_order_received from clues_sla_summary
                                    where company_id = $company_id ");
     
      $twenty_four = $fetch_sla['24_hr'];
      $fourty_eight = $fetch_sla['48_hr'];
     
     $fetch_sla['12_hr'] =round(($fetch_sla['12_hr']/ $fetch_sla['total'])*100); 
     $fetch_sla['24_hr'] =round(($fetch_sla['24_hr']/ $fetch_sla['total'])*100);
     $fetch_sla['36_hr'] =round(($fetch_sla['36_hr']/ $fetch_sla['total'])*100);
     $fetch_sla['48_hr'] =round((($twenty_four+$fetch_sla['48_hr'])/ $fetch_sla['total'])*100);
     $fetch_sla['72_hr'] =round((($twenty_four+$fourty_eight+$fetch_sla['72_hr'])/ $fetch_sla['total'])*100);
     $fetch_sla['96_hr'] =round(($fetch_sla['96_hr']/ $fetch_sla['total'])*100);
     
     $shipping['fill_rate'] = round(($fetch_sla['total']/$fetch_sla['total_order_received']) * 100);
     $shipping['badge_percent'] = $fetch_sla[$percent.'_hr'];
     
     return $shipping;
 }
 
function fn_sdeep_ship_info($comp_id)
{
    $result = db_get_array("SELECT cc.shipping_sla as percent , cbq.name, cbq.icon_url FROM cscart_companies as cc 
                    INNER JOIN clues_company_badge_answer as ccba ON ccba.id=cc.shipping_badge_answer  
                    INNER JOIN clues_badge_questions as cbq ON cbq.id=ccba.question_id 
                    WHERE cc.company_id=".$comp_id);
 
    foreach ($result as $value) {
                        $response = $value;
                    }
                    return $response;
}
/* End code by  Munish on 24 Dec 2013 */
function fn_get_feedback_posting_status($order_id)
{
	$sql="select cod.order_id from cscart_order_details cod
	left join clues_user_product_rating cupr on cupr.order_id=cod.order_id and cupr.product_id=cod.product_id
	where cod.order_id=$order_id and cupr.product_id is null";
	$res=db_get_field($sql);
	if(!empty($res))
	{
		return false;
	}
	else
	{
		return true;
	}
}
function fn_get_state_name_lookup($state_code){
	 $sql="SELECT csd.state FROM cscart_states cs 
		   inner join cscart_state_descriptions csd on csd.state_id=cs.state_id
		   where cs.country_code='IN' and cs.code='".$state_code."'and cs.status='A'";
		   
	$state=db_get_field($sql);
	return $state;
	
	}

?>
