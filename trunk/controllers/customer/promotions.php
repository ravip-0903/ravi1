<?php
/***************************************************************************
*                                                                          *
*    Copyright (c) 2009 Simbirsk Technologies Ltd. All rights reserved.    *
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
// $Id: promotions.php 7502 2009-05-19 14:54:59Z zeke $
//

if (!defined('AREA') ) { die('Access denied'); }

if ($mode == 'list') {
	$params = array (
		'active' => true,
		/*'zone' => 'catalog',*/
		'get_hidden' => false,
	);

	list($promotions) = fn_get_promotions($params);

	$view->assign('promotions', $promotions);
}elseif($mode == 'merchants'){ // Added by sudhir dt 14th feb 2013 for new launch merchant promotion feature
	if(isset($_REQUEST['type_id'])){

		$valid_type_id = db_get_array("SELECT promotion_type_id FROM clues_promotion_type WHERE show_to_merchant='Y'");
			
		foreach($valid_type_id as $key=>$val){
			$valid_type[] = $val['promotion_type_id'];
		}
		if (!in_array($_REQUEST['type_id'], $valid_type)){
			return array(CONTROLLER_STATUS_NO_PAGE);
		}
		$time = time();
		$result = db_get_array("select p.product_id, descr1.product as product, MIN(prices.price) as price, c.company as company_name, GROUP_CONCAT(IF(pc.link_type = 'M', CONCAT(pc.category_id, 'M'), pc.category_id)) as category_ids, cscart_seo_names.name as seo_name, p.promotion_id, p.list_price, p.deal_inside_badge, p.special_offer_badge, p.price_see_inside, p.special_offer_text, p.freebee_inside, p.timestamp, pr.promotion_id, cd1.category as meta_category, cd1.category_id
from cscart_products p
LEFT JOIN cscart_product_descriptions as descr1 ON descr1.product_id = p.product_id AND descr1.lang_code = 'EN'
LEFT JOIN cscart_product_prices as prices ON prices.product_id = p.product_id AND prices.lower_limit = 1 
LEFT JOIN cscart_seo_names ON cscart_seo_names.object_id = p.product_id AND cscart_seo_names.type = 'p' AND cscart_seo_names.dispatch = '' AND cscart_seo_names.lang_code = 'EN' 
LEFT JOIN cscart_product_options_inventory as inv on inv.product_id=p.product_id
inner join cscart_companies c on c.company_id = p.company_id and c.status = 'A'
inner join cscart_products_categories pc on pc.product_id = p.product_id and pc.link_type='M' 
INNER JOIN cscart_categories c0 on c0.category_id = pc.category_id and c0.status = 'A'
INNER JOIN cscart_category_descriptions cd1 ON cd1.category_id = SUBSTRING_INDEX(SUBSTRING_INDEX(id_path, '/',2), '/',1) 
INNER JOIN cscart_categories c1 on c1.category_id = cd1.category_id and c1.status = 'A'
inner join cscart_promotions pr on pr.promotion_id = p.promotion_id
where p.status = 'A'
and pr.status = 'A'
and pr.promotion_type_id = '".$_REQUEST['type_id']."'
and pr.to_date >= '".$time."'
and pr.from_date <= '".$time."'
GROUP BY p.product_id
order by  c1.position ");
		foreach($result as $key=>$val){
			$res[$val['category_id']][] = $val;
		}
		$view->assign('promo_product', $res);
	}else{
		return array(CONTROLLER_STATUS_NO_PAGE);
	}
}

?>
