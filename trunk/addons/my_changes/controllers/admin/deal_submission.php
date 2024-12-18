<?php
if(!defined('AREA') ) { die('Access denied'); }
if($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    if($mode == 'deal_update')
    {
        //echo "<pre>";print_r($_REQUEST);
        $update_query = db_query("update clues_deal_submission set category_name='".$_REQUEST['cat_name']."',product='".$_REQUEST['pro_name']."',brand='".$_REQUEST['brand_name']."' ,possession_time='".$_REQUEST['poss_time']."',mrp='".$_REQUEST['mrp']."',rsp='".$_REQUEST['rsp']."',deal_price='".$_REQUEST['deal_price']."',minqty='".$_REQUEST['minqty']."',is_minqty='".$_REQUEST['is_minqty']."',status='".$_REQUEST['select']."',notes='".$_REQUEST['notes_deal']."',type_of_deal='".$_REQUEST['deal_select']."' where id =".$_REQUEST['id']);
    }
    
     fn_set_notification('N',fn_get_lang_var('changes_saved')); 
     return array(CONTROLLER_STATUS_REDIRECT, "deal_submission.deal_update&id=".$_REQUEST['id']); 
    
}

if($mode == 'manage'){
    
    $page = 0;
    if (isset($_GET['page']))
        $page = $_GET['page'];
    
    $sortings = array (
		'date_created' => "cpi.date_created",
			'id' => "cd.id",
			'brand'=>"cd.brand",
			'merchant_name'=>"cd.merchant_name"
		);
    

     $directions = array (
   'asc' => 'asc',
   'desc' => 'desc'
  );
   if (isset($_REQUEST['id']) && $_REQUEST['id'] == 'id') {
                $sort_by = "cd.id";
   }
  $sort_order = empty($_REQUEST['sort_order']) ? '' : $_REQUEST['sort_order'];
  $sort_by = empty($_REQUEST['sort_by']) ? '' : $_REQUEST['sort_by'];
  
  if (empty($sort_order) || !isset($directions[$sort_order])) {
  $sort_order = '';
  }
      
 if (empty($sort_by) || !isset($sortings[$sort_by])) {
   //$sort_by = 'date_created';
    $sort_by = 'cd.id';
  }
    $view->assign('sort_order', ($sort_order == 'asc') ? 'desc' : 'asc');
  $view->assign('sort_by', $sort_by);
    
  
  $merchant_search = !empty($_REQUEST['merchant_name_search']) ? $_REQUEST['merchant_name_search'] : 0;
  $brand_search = !empty($_REQUEST['brand_name_search']) ? $_REQUEST['brand_name_search'] : 0;
  $status = !empty($_REQUEST['status_deal']) ? $_REQUEST['status_deal'] : 0;
   $deal_pro = !empty($_REQUEST['pro_deal']) ? $_REQUEST['pro_deal'] : 0;
  if (isset($_GET['time_from']) and $_GET['time_from'] != '') {
        $date_from = explode('/', $_GET['time_from']);
        $date_from = $date_from[2] . '-' . $date_from[1] . '-' . $date_from[0];
    }
    else
        $date_from = '';

    if (isset($_GET['time_to']) and $_GET['time_to'] != '') {
        $date_to = explode('/', $_GET['time_to']);
        $date_to = $date_to[2] . '-' . $date_to[1] . '-' . $date_to[0];
    }
    else
        $date_to = '';
  
if (isset($_GET['time_from']) and $_GET['time_from'] != '') {
        $date_from = explode('/', $_GET['time_from']);
        $date_from = $date_from[2] . '-' . $date_from[1] . '-' . $date_from[0];
    }
    else
        $date_from = '';

    if (isset($_GET['time_to']) and $_GET['time_to'] != '') {
        $date_to = explode('/', $_GET['time_to']);
        $date_to = $date_to[2] . '-' . $date_to[1] . '-' . $date_to[0];
    }
    else
        $date_to = '';
    $sql = "SELECT pt.type, cd.date_created,cd.status,cd.mobile_no,cd.merchant_id, cd.email, cd.id, cd.mrp , cd.brand , cd.category_name, cd.merchant_name , cd.product, cd.rsp , cd.mrp , cd.deal_price , cd.minqty , cd.is_minqty, cd.possession_time  FROM clues_promotion_type pt INNER JOIN clues_deal_submission cd ON pt.promotion_type_id = cd.type_of_deal ";
    $promotion_data = db_get_array("select type,promotion_type_id FROM clues_promotion_type");
    
	$view->assign("pro_result", $promotion_data);
     
      if($merchant_search)
    {
        
        $sql.= "and cd.merchant_name = '".$merchant_search."'";
    }
    if($brand_search)
    {
        $sql.= "and cd.brand = '".$brand_search."'";
    }
    
    if ($date_from != '') {
        $sql.= "and date(cd.date_created) >='" . $date_from . "' and date(cd.date_created) <='" . $date_to . "'";  
    }
    if($status)
    {
        $sql.= "and cd.status='".$status."'";
    }
    if($deal_pro)
    {
        $sql.="and cd.type_of_deal=".$deal_pro;
    }
  
    //Limit applied finally after all conditions
     $total_pages = count(db_get_array($sql));
     $limit = fn_paginate($page, $total_pages, $sets['page_posts_per_page']);
   $sql.= " ORDER BY $sort_by $sort_order ".$limit;
   //echo $sql;
	$result = db_get_array($sql);
        
       // echo "<pre>";print_r($result);die;
	$view->assign("promotion_get", $result);
} 
if($mode == 'deal_update')
{
    
    $sql_fetch = "SELECT pt.type, cd.date_created,cd.notes,cd.merchant_id ,cd.type_of_deal,cd.status, cd.email, cd.id, cd.mrp , cd.brand , cd.category_name, cd.merchant_name , cd.product, cd.rsp , cd.mrp , cd.deal_price , cd.minqty , cd.is_minqty, cd.possession_time  FROM clues_promotion_type pt INNER JOIN clues_deal_submission cd ON pt.promotion_type_id = cd.type_of_deal where cd.id=".$_REQUEST['id'];
        
    $promotion_data = db_get_array("select type,promotion_type_id FROM clues_promotion_type");
	$view->assign("pro_result", $promotion_data);    
	$result = db_get_array($sql_fetch);
        $result[0]['mrp'] = intval($result[0]['mrp']);
        $result[0]['rsp'] = intval($result[0]['rsp']);
        $result[0]['deal_price'] = intval($result[0]['deal_price']);
	$view->assign("fetch_data", $result);
}
?> 
