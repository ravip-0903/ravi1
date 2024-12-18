<?php
if(!defined('AREA') ) { die('Access denied'); }

if($mode=='store')
{
	if (!empty($auth['user_id'])) {
		
		$sql="select mfs.company_id,mfs.timestamp,c.company,c.status
		from clues_my_favourite_store mfs
		inner join cscart_companies c on c.company_id=mfs.company_id
		where mfs.user_id='".$_SESSION['auth']['user_id']."' and mfs.store_like=1
		
		";
		$res=db_get_array($sql);
		
		$params['page'] = empty($params['page']) ? 1 : $params['page'];
		
		$total=count($res);
		
		$arrlim=false;
		$items_per_page=Registry::get('settings.Appearance.orders_per_page');
		$limit = fn_paginate($_REQUEST['page'], $total, $items_per_page,false,$arrlim);
		
		$sql="select mfs.company_id,mfs.timestamp,c.company,c.status
		from clues_my_favourite_store mfs
		inner join cscart_companies c on c.company_id=mfs.company_id
		where mfs.user_id='".$_SESSION['auth']['user_id']."' and mfs.store_like=1 order by timestamp desc $limit
		";
		$res=db_get_array($sql);
		
		fn_add_breadcrumb(fn_get_lang_var('my_fav_store'));
		
		
		$view->assign('my_stores',$res);
	} else {
		return array(CONTROLLER_STATUS_REDIRECT, "auth.login_form?return_url=" . urlencode(Registry::get('config.current_url')));
	}
}
else if($mode=='like')
{
	$cur_url=Registry::get('config.current_url');
	$ref_url=$_REQUEST['ret_url'];
	if (!empty($auth['user_id'])) {
		
		$sql="select id from clues_my_favourite_store where user_id='".$auth['user_id']."' and company_id='".$_REQUEST['c_id']."'";
		$id=db_get_field($sql);
		if(!empty($id))
		{
			$sql="update clues_my_favourite_store set user_id='".$auth['user_id']."',company_id='".$_REQUEST['c_id']."',product_id='".$_REQUEST['p_id']."',store_like=1 where id='".$id."'";
		}
		else
		{
			$sql="insert into clues_my_favourite_store set user_id='".$auth['user_id']."',company_id='".$_REQUEST['c_id']."',product_id='".$_REQUEST['p_id']."',store_like=1";
		}
		db_query($sql);
		
		return array(CONTROLLER_STATUS_REDIRECT,$ref_url );
	}
	else
	{
		$erro_msg=fn_get_lang_var('need_to_login');
		fn_set_notification('E',fn_get_lang_var('notice'),$erro_msg);
		return array(CONTROLLER_STATUS_REDIRECT, "auth.login_form?return_url=" . urlencode($cur_url));
	}
	
}
else if($mode=='unlike')
{
	$cur_url=Registry::get('config.current_url');
	$ref_url=$_REQUEST['ret_url'];
	if (!empty($auth['user_id'])) {
		
		$sql="update clues_my_favourite_store set store_like=0 where user_id='".$auth['user_id']."' and company_id='".$_REQUEST['c_id']."'";
		
		db_query($sql);
		
		return array(CONTROLLER_STATUS_REDIRECT,$ref_url );
	}
	else
	{
		$erro_msg=fn_get_lang_var('need_to_login');
		fn_set_notification('E',fn_get_lang_var('notice'),$erro_msg);
		return array(CONTROLLER_STATUS_REDIRECT, "auth.login_form?return_url=" . urlencode($cur_url));
	}
}


?>