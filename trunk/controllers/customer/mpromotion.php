<?php
if($mode =='promo')
{	
	//echo "<br>"; die('calling page.');
	$id = (!empty($_REQUEST['object_id'])) ? $_REQUEST['object_id'] : '';
	
	$promo_type = fn_fetch_mpromotion_type($id);
	$view->assign('promo_type',$promo_type);
//and ( (date(from_unixtime(cp.from_date))<= DATE( CURRENT_TIMESTAMP( )) and (from_unixtime(cp.to_date))>= DATE( CURRENT_TIMESTAMP( )))  )
	$sql="SELECT cpt.promotion_type_id ,cp.promotion_id, cp.conditions_hash,p.product_id,p.company_id
	,cp.conditions,cp.bonuses,date(from_unixtime(cp.from_date)),date_format(from_unixtime(cp.to_date),'%D %M %Y') as Valid_up_to
	,ccd.category,ccd.category_id,cpd.product,cc1.company as company_name,cpt.file_imagepath,prices.price,p.list_price,p.*
FROM `clues_promotion_type` cpt 
inner join cscart_promotions cp on cp.promotion_type_id=cpt.promotion_type_id
inner join cscart_products p on p.promotion_id=cp.promotion_id
inner join cscart_companies cc1 on cc1.company_id=p.company_id
inner join cscart_products_categories cpc on cpc.product_id=p.product_id
inner join cscart_product_descriptions cpd on cpd.product_id=cpc.product_id
LEFT JOIN cscart_product_prices as prices ON prices.product_id = p.product_id AND prices.lower_limit = 1 
inner join cscart_categories cc on cc.category_id=cpc.category_id
inner join cscart_category_descriptions  ccd on ccd.category_id=   SUBSTRING_INDEX(SUBSTRING_INDEX(id_path, '/',2), '/',1)
inner join cscart_categories cc2 on cc2.category_id=ccd.category_id
where cpt.show_to_merchant='Y' and cpt.promotion_type_id ='".$id."' and cp.status='A' and p.status='A' and cc1.status='A' and cc2.status='A'
and ((date(from_unixtime(cp.from_date))<= DATE( CURRENT_TIMESTAMP( )) and (from_unixtime(cp.to_date))>= DATE(CURRENT_TIMESTAMP( ))))
order by ccd.category_id";
//

//echo $sql;

	$promo_data= db_get_hash_multi_array($sql,array('category_id'));
	//print_r($promo_data);
 	$view->assign('promo_data',$promo_data);
}

function fn_fetch_mpromotion_type($type_id) {
	$sql="select type, file_bannerpath from clues_promotion_type where promotion_type_id='".$type_id."'";
	return db_get_row($sql);
}
function fn_get_discount($promotion_id)
{ 
	$result = fn_get_promotion_data($promotion_id);
	//echo "<pre>";print_r($result);
	foreach($result as  $key => $v)
	{	//echo "<pre>";print_r($v);
		foreach($v as $k)
			{  if($k['discount_bonus']=='by_percentage'){;
				$x=$k['discount_value'];
			   }

			}
	}
	return  $x;
}
function fn_get_flat_discount($promotion_id)
{ 
	$result = fn_get_promotion_data($promotion_id);
	//echo "<pre>";print_r($result);
	foreach($result as  $key => $v)
	{	//echo "<pre>";print_r($v);
		foreach($v as $k)
			{  if($k['discount_bonus']=='by_fixed'){;
				$x=$k['discount_value'];
			   }

			}
	}
	return  $x;
}

	
 function fn_get_coupon_code($data)
 {
	 if(stristr($data,'coupon_code=')){
      $data = explode(';',$data);
      foreach($data as $tmp){
            $t=explode('=',$tmp);
            if($t['0'] == 'coupon_code'){
                $coupon_code = $t['1'];
            }
      }
  	}
  return $coupon_code ;
}
 
?>
 
