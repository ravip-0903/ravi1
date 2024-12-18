<?php
if(!defined('AREA') ) { die('Access denied'); }

if($mode == "list"){
	$merchant_new_limit = (Registry::get('config.merchant_new_limit')) ? Registry::get('config.merchant_new_limit'):'15';
	$product_new_limit = (Registry::get('config.product_new_limit')) ? Registry::get('config.product_new_limit'):'15';
	$sql = "Select cbd.company_id, cbd.brand_id, cbd.brand_name custom_brand_name, cbd.logo_url, cbd.category_id, cc.is_ngo ,
			if(datediff(curdate(),date(cbd.date_created))<=".$merchant_new_limit.",'Y','N') is_new,
			if((select count(p.product_id) from cscart_products p where p.company_id=cbd.company_id and p.`status`='A' and 
			datediff(curdate(),date(from_unixtime(p.timestamp)))<=".$product_new_limit.")>0,'Y','N') as new_product,
			if(od.company_id is not null, 'Y','N') as offers,
			cpfvd.variant brand_name
			from clues_brandstore_details cbd 
			left join cscart_companies cc on cc.company_id = cbd.company_id and cc.status = 'A'
			left join cscart_product_feature_variant_descriptions cpfvd on cpfvd.variant_id = cbd.brand_id
			inner join cscart_categories cat on (cat.category_id = cbd.category_id)
			left join (select p.company_id 
			from cscart_products p
			inner join cscart_promotions pr on pr.promotion_id = p.promotion_id and pr.status = 'A'
			where p.status = 'A'
			group by p.company_id) as od on od.company_id = cbd.company_id
			where cbd.status='A' and cat.status = 'A' and cc.status = 'A'
			order by cat.position,
			if(cpfvd.variant is NULL, if(cbd.brand_name is NULL or cbd.brand_name = '',cc.company, cbd.brand_name), cpfvd.variant )";	
	$result = db_get_hash_multi_array($sql,array('category_id'));
	$is_new = 'N';
	foreach($result as $res){
		foreach($res as $r){
			if($r['is_new']	== 'Y'){
				$is_new = 'Y';	
			}
		}
	}
	$view->assign('brands',$result);
	$view->assign('is_new',$is_new);
}

?>