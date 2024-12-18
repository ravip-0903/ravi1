<?php
if(!defined('AREA') ) { die('Access denied'); }
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if($mode == 'add') {
		//echo '<pre>';print_r($_REQUEST);die;
		$email=$_REQUEST['Email'];
		$mer_name=addslashes($_REQUEST['Merchant_name']);
		$category_name=addslashes($_REQUEST['Category_name']);
		$type_deal=$_REQUEST['Deal_select'];
		$product=addslashes($_REQUEST['Product']);
		$brand=addslashes($_REQUEST['Brand']);
		$mrp=$_REQUEST['Mrp'];
		$rsp=$_REQUEST['Rsp'];
		$deal_price=$_REQUEST['Deal_price'];
                $mobile = $_REQUEST['mobile_no'];
                $mid = $_REQUEST['merchant_id'];
		$minqty=$_REQUEST['Minqty'];
		$is_minqty=$_REQUEST['is_minqty'];
		$possession_time=$_REQUEST['Possession_time'];
	  //print_r($_REQUEST);die;
	  $query=("insert into clues_deal_submission (id,Email,Merchant_name,Category_name,type_of_deal,product,Brand,Mrp,Rsp,deal_price,Minqty,is_minqty,possession_time,mobile_no,merchant_id)
	  values('','".$email."','".$mer_name."','".$category_name."','".$type_deal."','".$product."','".$brand."','".$mrp."','".$rsp."','".$deal_price."','".$minqty."','".$is_minqty."','".$possession_time."','".$mobile."','".$mid."')");
	 
	
	  $type = "SELECT distinct(pt.type) FROM clues_promotion_type pt INNER JOIN clues_deal_submission cd ON pt.promotion_type_id = cd.type_of_deal where cd.type_of_deal='".$type_deal."'";
	  $type_result = db_get_field($type);
	  
		if(db_query($query)){
				
				//fn_set_notification('N', '', fn_get_lang_var('value inserted'));
			
		}
		$from 					= Registry::get('config.deal_sub_email_from');
		$msg_subject 			= fn_get_lang_var('deal_submission_sub');
		$msg_body 				= '<br>';		
		$msg_body 				.= '<table>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('ds_email').'</td>'.'<td>'.$email.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('merchant_name').'</td>'.'<td>'.$mer_name.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('category_name').'</td>'.'<td>'.$category_name.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('deal_select').'</td>'.'<td>'. $type_result.'</td>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('products').'</td>'.'<td>'.$product.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('brands').'</td>'.'<td>'.$brand.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('mrp').'</td>'.'<td>'.$mrp.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('rsp').'</td>'.'<td>'.$rsp.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('deal_price').'</td>'.'<td>'.$deal_price.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('minqty').'</td>'.'<td>'.$minqty.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('is_minqty').'</td>'.'<td>'.$is_minqty.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('possession_time').'</td>'.'<td>'.$possession_time.'</td>';
		$msg_body 				.= '</tr>';		
		$msg_body 				.= '</table>';
		$to 					= Registry::get('config.deal_sub_email_from');
		
		//print_r($_REQUEST);die;
		//echo $msg_body;die;
		if(db_query("INSERT INTO clues_email_queue (user_id, from_email, to_email, subject, message, status) values('','".$from."','".$to."','".addslashes($msg_subject)."','".addslashes($msg_body)."','UNSENT')")){
		
			fn_set_notification('N', '', fn_get_lang_var('thanks_to_writing'));
			return array(CONTROLLER_STATUS_OK, "deal_submission.add");	
			
			
		}	
	}
}

if($mode == 'add'){
	$sql = "SELECT * FROM clues_promotion_type WHERE is_external = 'Y'";
	$result = db_get_array($sql);
	$view->assign("promotion_type", $result);
}
?>
