<?php
if(!defined('AREA') ) { die('Access denied'); }
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if($mode == 'update') {
		
		
		$fileflag=0;
		if ($_FILES["review_img"]["name"])
		{
			if($_FILES["review_img"]["type"]=="image/png" || $_FILES["review_img"]["type"]=="image/jpeg" || $_FILES["review_img"]["type"]=="image/gif")
			{
				move_uploaded_file($_FILES["review_img"]["tmp_name"],DIR_IMAGES."reviews/" . $_REQUEST['order_id'] . ".jpg");
				$fileflag=1;
			}
		}										  
		
		
		$cnt = 0;
		$sum = 0;
		if($_REQUEST['shipping_time']>0)
		{
			$cnt++;
			$sum = $sum + $_REQUEST['shipping_time'];
		}
		if($_REQUEST['shipping_cost']>0)
		{
			$cnt++;
			$sum = $sum + $_REQUEST['shipping_cost'];
		}
		if($_REQUEST['product_quality']>0)
		{
			$cnt++;
			$sum = $sum + $_REQUEST['product_quality'];
		}
		if($_REQUEST['value_for_money']>0)
		{
			$cnt++;
			$sum = $sum + $_REQUEST['value_for_money'];
		}
		$avg_rate = 0;
		if($cnt>0)
		{
			$avg_rate = $sum/$cnt;
			
			$rating_info = db_get_row("SELECT count(id) as cnt,sum(avg_rate) as sm FROM clues_user_product_rating WHERE company_id='".  $_REQUEST['company_id'] ."' and avg_rate>0");
			$rate_overall = ($rating_info['cnt'] + $avg_rate)/($rating_info['cnt']+1);
			$sql = "update ?:companies set sdeep_rating='".$rate_overall."' where company_id='".$_REQUEST['company_id']."'";
			db_query($sql);
			$rating_info1['1'] = $_REQUEST['shipping_time'];
			$rating_info1['2'] = $_REQUEST['shipping_cost'];
			$rating_info1['3'] = $_REQUEST['product_quality'];
			$rating_info1['4'] = $_REQUEST['value_for_money'];
			$rating_info1['timestamp'] = time();
			db_query("UPDATE ?:orders SET rating_info=?s WHERE order_id=?i", serialize($rating_info1), $_REQUEST['order_id']);
		}
	
	
		
		
		$strt = $_REQUEST['review_title'];
		$strt = str_replace("'","''",$strt);
		
		$strv = $_REQUEST['video_url'];
		$strv = str_replace("'","''",$strv);
		
		//change by ankur to disable the review
		$ex_cond1="";
		$ex_cond2="";
		$ex_cond3="";
		
		  if($_REQUEST['ipb']==1)
		  {
			  $ex_cond1=",for_product_status='D',for_merchant_status='D'";
		  }
		  if($_REQUEST['pb']==1)
		  {
			  $ex_cond2=",for_product_status='D'";
		  }
		  if($_REQUEST['cb']==1)
		  {
			  $ex_cond3=",for_merchant_status='D'";
		  }
		//code end
		
		$sql = "insert into clues_user_product_rating set product_id='".$_REQUEST['product_id']."',
			      company_id='".$_REQUEST['company_id']."',
			      order_id='".$_REQUEST['order_id']."',
			      product_rating='".$_REQUEST['product_rating']."',
			      score='".$_REQUEST['score']."',
			      review_title='',
			      review='".addslashes($_REQUEST['review'])."',
			      video_url='".$strv."',
			      shipping_time='".$_REQUEST['shipping_time']."',
			      shipping_cost='".$_REQUEST['shipping_cost']."',
			      product_quality='".$_REQUEST['product_quality']."',
			      value_for_money='".$_REQUEST['value_for_money']."',
				  review_merchant='".addslashes($_REQUEST['review_merchant'])."',
			      avg_rate='".$avg_rate."' $ex_cond1 $ex_cond2 $ex_cond3";
				
		if($fileflag)
		{
			$sql = $sql . ",`file`='". $_REQUEST['order_id'] . ".jpg'";
		}
														  
		db_query($sql);
		
		if(is_numeric($_REQUEST['product_rating']) && is_numeric($_REQUEST['product_id']) && $_REQUEST['product_rating']>0) {
			// Check whether the product exists
			$product_id = db_get_field("SELECT product_id FROM ?:products WHERE product_id=?i", $_REQUEST['product_id']);
			if($product_id) {
				$rating_info = db_get_field("SELECT sdeep_rating_info FROM ?:products WHERE product_id=?i", $product_id);
				$rating_info = @unserialize($rating_info);
				//echo '<pre>';print_r($_REQUEST);print_r($rating_info);die;
				if(!isset($rating_info['total_score'])) $rating_info['total_score'] = 0;
				if(!isset($rating_info['num_rates'])) $rating_info['num_rates'] = 0;
				$rating_info['total_score'] = ($_REQUEST['product_rating'] + $rating_info['total_score']*$rating_info['num_rates'])/($rating_info['num_rates'] + 1);
				$rating = (int)($rating_info['total_score']*100);
				$rating_info['total_score'] = $rating / 100;
				$rating_info['num_rates'] = $rating_info['num_rates'] + 1;
				db_query("UPDATE ?:products SET sdeep_rating_info = ?s WHERE product_id = ?i", @serialize($rating_info), $_REQUEST['product_id']);
			} else {
				return array(CONTROLLER_STATUS_NO_PAGE);
			}
		} else { 
			return array(CONTROLLER_STATUS_NO_PAGE);
		}
		
		
	
	
	fn_set_notification('N', '', 'Thanks for your feedback. ');
	return array(CONTROLLER_STATUS_REDIRECT, 'profiles.pending_feedback');
	}
	
	
	//code by ankur
	if($mode="validate") //this is to validate review and IP
	{
		//code by ankur to not allow restrcated phrase and ip in the review.
		$post_data=$_REQUEST;
		$ip = $_SERVER['REMOTE_ADDR'];
		$sql="select id from clues_restricted_phrase_ip where restrict_ip='".$ip."' and type='R'";
		$rest_check=db_get_field($sql);
		
		
		if(!empty($rest_check))
		{
			//fn_set_notification('N', '', fn_get_lang_var('ip_blocked'));
			$to="technology@shopclues.com";
			$from="support@shopclues.com";
			$sub=fn_get_lang_var('feedback_post_error');
			$msg="<h1>Feedback Post Error Due To IP BLOCKED</h1>";
			$msg.="<br/><br/>Posted IP-". $ip;
			sendElasticEmail($to, $sub, " ", $msg, $from, fn_get_lang_var('review_mail_header'), '');
			echo 'IP_BLOCKED';
			exit;
		}
		
		 $pro_review = $post_data['pro_review'];
		 $comp_review=$post_data['comp_review'];
		 $url_pattern = '{\b(?:http://)?(www\.)?([^\s]+)(\.com|\.org|\.net|\.co|\.in|\.mobi|\.bizz|\.info|\.cc|\.us|\.ca)\b}';
		 $str="";
  		//preg_match_all($url_pattern,$review,$matches);
		if(preg_match($url_pattern,$pro_review))
		{
			$str="PRO_REVIEW_BLOCKED,";
		}
		if(preg_match($url_pattern,$comp_review))
		{
			$str.="COMP_REVIEW_BLOCKED,";
		}
		if($str!='')
		{
			echo $str;
			exit;
		}
		
		$sql="select restrict_phrase from clues_restricted_phrase_ip where type='R'";
		$result=db_get_array($sql);
		$pro_rest_ph_match=array();
		$comp_rest_ph_match=array();
		$msg='';
		foreach($result as $phrase)
		{
			if(stripos($pro_review,$phrase['restrict_phrase'])!==false)
			{
				$pro_rest_ph_match[]=$phrase['restrict_phrase'];
			}
			if(stripos($comp_review,$phrase['restrict_phrase'])!==false)
			{
				$comp_rest_ph_match[]=$phrase['restrict_phrase'];
			}
			
		}
		
		if(!empty($comp_rest_ph_match) || !empty($pro_rest_ph_match))
		{
			$to=Registry::get('config.error_to_email_ids');
			$from="support@shopclues.com";
			$sub=fn_get_lang_var('feedback_post_error');
			$msg="<h1>".fn_get_lang_var('phrase_restricted')."</h1>";
			$msg.="Name-".$post_data['name'];
		}
		if(!empty($pro_rest_ph_match))
		{
			$msg.="<br/><br/>Posted Product Feedback-". $pro_review;
			$msg.="<br/><br/>Restricted Phrase Found-".implode(",",$pro_rest_ph_match);
			$str="PRO_REVIEW_BLOCKED,";
		}
		if(!empty($comp_rest_ph_match))
		{
			$msg.="<br/><br/>Posted Company Feedback-". $comp_review;
			$msg.="<br/><br/>Restricted Phrase Found-".implode(",",$comp_rest_ph_match);
			$str.="COMP_REVIEW_BLOCKED,";
		}
		if($msg!='')
		{
			sendElasticEmail($to, $sub, " ", $msg, $from, fn_get_lang_var('review_mail_header'), '');
			echo $str;die;
		}
		echo 'Done';
		exit;
		//code end
	}
	
	//code by ankur to post feedback from orders page
} 


if($mode == 'manage') {
	if($_REQUEST['order_id'] && $_REQUEST['product_id'])
	{
		$sql1 = "select * from clues_user_product_rating where order_id='".$_REQUEST['order_id']."' and product_id='".$_REQUEST['product_id']."'";
		//echo $sql;
		$res1 = db_get_row($sql1);
		if(!empty($res1))
		{
			fn_set_notification('N', 'Thanks', 'You have already submitted a review.');
			return array(CONTROLLER_STATUS_REDIRECT, $index_script);
		}
		$order_info = fn_get_order_info($_REQUEST['order_id']);
		$view->assign('order_info', $order_info);
	}
	else
	{
		return array(CONTROLLER_STATUS_REDIRECT, $index_script);
	}
	
}
function fn_get_basic_product_info($pro_id)
{
	$sql="select p.product_id,p.company_id,pd.product
	from cscart_products p
	inner join cscart_product_descriptions pd on p.product_id=pd.product_id
	where p.product_id=$pro_id";
	return db_get_row($sql);
}
?>
