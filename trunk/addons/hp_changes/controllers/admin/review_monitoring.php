<?php
if ( !defined('AREA') ) { die('Access denied'); }

if($_SERVER['REQUEST_METHOD']=='POST')
{
	
	if($mode=='product_review')
	{
		foreach($_REQUEST['posts'] as $id=>$data)
		{
			if(isset($data['status']))
			{
				hp_update_product_review_status($id,$data);
			}
			if($data['edittext']==1)
			{
				hp_update_product_review($id,$data);
			}
			
		}
		return array(CONTROLLER_STATUS_OK, "review_monitoring.product_review");
	}
	else if($mode=='merchant_review')
	{
		foreach($_REQUEST['posts'] as $id=>$data)
		{
			if(isset($data['status']))
			{
				hp_update_merchant_review_status($id,$data);
			}
			if ($data['edittext']==1)
			{
				hp_update_merchant_review($id,$data);
			}
			if($data['add_admin_comment']==1)
			{
				hp_add_admin_comment($id,$data);
			}
		}
		return array(CONTROLLER_STATUS_OK, "review_monitoring.merchant_review");
	}
}



if($mode=='product_review')
{
	$page=0;
	if(isset($_GET['page']))
	$page=$_GET['page'];
	
	$posts=hp_get_latest_review_for_product($page);
	$view->assign("post",$posts);
}
else if($mode=='merchant_review')
{
	$page=0;
	if(isset($_GET['page']))
	$page=$_GET['page'];
	
	$posts=hp_get_latest_review_for_merchant($page);
	$view->assign("post",$posts);
}
//these functions are for review monitoring not for default review management process
function hp_get_latest_review_for_product($page)
{
	$sets = Registry::get('addons.discussion');
	
	$sql="SELECT po.name as name,me.message as review,from_unixtime(po.timestamp) as post_date,po.post_id as id,po.status as status,pro.product,pro.product_id from cscart_discussion_messages me inner join cscart_discussion_posts po on po.post_id=me.post_id and po.thread_id=me.thread_id inner join cscart_discussion d on d.thread_id=me.thread_id inner join cscart_product_descriptions pro on pro.product_id=d.object_id         where d.object_type='P' and d.type='B'";
	   $sql1="select o.b_firstname as name,pr.review as review,pr.creation_date as post_date,pr.id as id,pr.for_product_status as status,pro.product,pro.product_id from clues_user_product_rating pr inner join cscart_product_descriptions pro on pro.product_id=pr.product_id	inner join cscart_orders o on o.order_id=pr.order_id";
			
	$count1=count(db_get_array($sql));
	$count2=count(db_get_array($sql1));
	
	$total_pages=$count1+$count2;
	$limit=fn_paginate($page, $total_pages, $sets['page_posts_per_page']);
			
	$res=db_get_array("select * from (" . $sql ." UNION ". $sql1 . ") tbl " . " ORDER BY post_date DESC $limit");
	
	return $res;

}

function hp_update_product_review_status($id,$params)
{
	db_query("update clues_user_product_rating set for_product_status='".$params['status']."' where id='".$id."'");
	db_query("update cscart_discussion_posts set status='".$params['status']."' where post_id='".$id."' and name='".$params['name']."'");
	
}
function hp_update_product_review($id,$params)
{
	$msg=addslashes($params['message']);
	db_query("update clues_user_product_rating set review='".$msg."' where id='".$id."'");
	db_query("update cscart_discussion_messages set message='".$msg."' where post_id='".$id."'");
}

function hp_get_latest_review_for_merchant($page)
{
	$sets = Registry::get('addons.discussion');
	
	$sql="SELECT po.name as name,me.message as review,from_unixtime(po.timestamp) as post_date,po.post_id as id,po.status as status,cc.company,cc.company_id,cdr.negative_rating_case_resolved,cdr.negative_rating_comment,cdr.resolved_by,cdr.resolving_date from cscart_discussion_messages me inner join cscart_discussion_posts po on po.post_id=me.post_id and po.thread_id=me.thread_id inner join cscart_discussion d on d.thread_id=me.thread_id inner join cscart_companies cc on cc.company_id =d.object_id inner join cscart_discussion_rating cdr on cdr.post_id=me.post_id and cdr.thread_id=me.thread_id         where d.object_type='M' and d.type='C'";
	   $sql1="select o.b_firstname as name,pr.review_merchant as review,pr.creation_date as post_date,pr.id as id,pr.for_merchant_status as status,cc.company,cc.company_id,negative_rating_case_resolved,negative_rating_comment,resolved_by,resolving_date from clues_user_product_rating pr inner join cscart_companies cc on cc.company_id=pr.company_id	inner join cscart_orders o on o.order_id=pr.order_id";
			
	$count1=count(db_get_array($sql));
	$count2=count(db_get_array($sql1));
	
	$total_pages=$count1+$count2;
	$limit=fn_paginate($page, $total_pages, $sets['page_posts_per_page']);
			
	$res=db_get_array("select * from (" . $sql ." UNION ". $sql1 . ") tbl " . " ORDER BY post_date DESC $limit");
	
	return $res;
}

function hp_update_merchant_review_status($id,$params)
{
	db_query("update clues_user_product_rating set for_merchant_status='".$params['status']."' where id='".$id."'");
	db_query("update cscart_discussion_posts set status='".$params['status']."' where post_id='".$id."' and name='".$params['name']."'");
}
function hp_update_merchant_review($id,$params)
{
	$msg=addslashes($params['message']);
	db_query("update clues_user_product_rating set review_merchant='".$msg."' where id='".$id."'");
	db_query("update cscart_discussion_messages set message='".$msg."' where post_id='".$id."'");	
}
function fn_check_negative_rating_and_get_star_count($id,$comp_id)
{
	$res=db_get_row("select shipping_time,shipping_cost,product_quality,value_for_money from clues_user_product_rating where id='".$id."' and company_id='".$comp_id."' ");
	
	if(!empty($res))
	{
		$sum=$res['shipping_time']+$res['shipping_cost']+$res['product_quality']+$res['value_for_money'];
		
		if($sum<=4)
		$return_arr['negative']=1;
		else
		$return_arr['negative']=0;
		
		if($sum==0)
		{
			$return_arr['no_review_post']=1;
		}
		else
		{
			$return_arr['no_review_post']=0;
		}
		
		$return_arr['param']=1;
		$return_arr['shipping_time']=$res['shipping_time'];
		$return_arr['shipping_cost']=$res['shipping_cost'];
		$return_arr['product_quality']=$res['product_quality'];
		$return_arr['value_for_money']=$res['value_for_money'];
		
		return $return_arr;
	}
	else
	{
		$res=db_get_row("select rating_value from cscart_discussion_rating where post_id='".$id."'");
		
		if($res['rating_value']<=1)
			$return_arr['negative']=1;
		else
		    $return_arr['negative']=0;
			
		$return_arr['no_review_post']=0;	
		
		$return_arr['param']=0;
			
		return $return_arr;
		
	}
	
}
function fn_get_negative_reting_comment_and_case_status($id,$comp_id)
{
	$res=db_get_row("select cpr.negative_rating_comment,cpr.negative_rating_case_resolved,cu.firstname,cpr.resolving_date from clues_user_product_rating cpr left join cscart_users cu on cu.user_id=cpr.resolved_by where cpr.id='".$id."' and cpr.company_id='".$comp_id."'");

	if(!empty($res))
	{
		return $res;
	}
	else
	{
		$res=db_get_row("select cdr.negative_rating_comment,cdr.negative_rating_case_resolved,cu.firstname,cdr.resolving_date from cscart_discussion_rating cdr left join cscart_users cu on cu.user_id=cdr.resolved_by where cdr.post_id='".$id."'");
		return $res;
	}
	
}
function hp_add_admin_comment($id,$data)
{
	$res=db_get_field("select count(id) as id from clues_user_product_rating where id='".$id."' and company_id='".$data['company_id']."'");
	if($res==1)
	{
		db_query("update clues_user_product_rating set shipping_time=3,shipping_cost=3,product_quality=3,value_for_money=3,avg_rate=3, negative_rating_comment='".addslashes($data['admin_comment'])."',negative_rating_case_resolved=1,resolved_by='".$_SESSION['auth']['user_id']."',resolving_date='".date('Y-m-d h:i:s a')."' where id='".$id."' and company_id='".$data['company_id']."'");
	}
	else
	{
		db_query("update cscart_discussion_rating set rating_value=3,negative_rating_comment='".addslashes($data['admin_comment'])."',negative_rating_case_resolved=1,resolved_by='".$_SESSION['auth']['user_id']."',resolving_date='".date('Y-m-d h:i:s a')."' where post_id='".$id."'");
	}

}
function fn_hp_get_user_name_from_id($user_id)
{
	$user = db_get_field("select firstname from cscart_users where user_id = '".$user_id."'");
	return $user;
}
?>