<?php
if(!defined('AREA') ) { die('Access denied'); }
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if($mode == 'contact') {

		//echo  "<pre>";print_r($_REQUEST);
		$customer_name		 	= addslashes($_REQUEST['fname']);
		$from 					= $_REQUEST['email'];
		$mobile_no 				= $_REQUEST['mobile_no'];
		$brand          	    =addslashes($_REQUEST['brand']);
		$category               =addslashes($_REQUEST['category']);
		$budget 				=addslashes($_REQUEST['budget']);
		$frequency              =addslashes($_REQUEST['frequency']);
		$comments 				=addslashes($_REQUEST['comments']);
		
		$query=db_query("insert into clues_advertisement (name,email,phone,brand_to_advertise,category,budget,frequency_spend,comment,creation_time)
			values('".$customer_name."','".$from."','".$mobile_no."','".$brand."','".$category."','".$budget."','".$frequency."','".$comments."','".time()."')");


		$msg_body 				 = '<br>';		
		$msg_body 				.= '<table class="contact_adv">';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td style="color:grey;">'.fn_get_lang_var('advertisement_name').':</td>'.'<td style="color:grey;">'.$customer_name.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td style="color:grey;">'.fn_get_lang_var('advertisement_email').':</td>'.'<td style="color:blue;">'.$from.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td style="color:grey;">'.fn_get_lang_var('advertisement_phone').':</td>'.'<td style="color:grey;">'.$mobile_no.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td style="color:grey;">'.fn_get_lang_var('advertisement_brand_to_advertise_mail').':</td>'.'<td style="color:grey;" valign="top">'.$brand.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td style="color:grey;">'.fn_get_lang_var('advertisement_location').':</td>'.'<td style="color:grey;">'.$category.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td style="color:grey;">'.fn_get_lang_var('advertisement_budget').':</td>'.'<td style="color:grey;">'.$budget.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td style="color:grey;">'.fn_get_lang_var('advertisement_frequency').':</td>'.'<td style="color:grey;">'.$frequency.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td style="color:grey;">'.fn_get_lang_var('advertisement_comments').':</td>'.'<td style="color:grey;">'.$comments.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '</table>';
		$to                      =  Registry::get('config.advertisement_email_to');
		$from                    =	Registry::get('config.advertisement_email_from');

		Registry::get('view_mail')->assign('msg_body', $msg_body);
 fn_instant_mail($to,$from,'advertisement/advertisement_subj.tpl','advertisement/advertisement_body.tpl');

fn_set_notification('N', '', fn_get_lang_var('thanks_to_writing_to_advertisement'));
			return array(CONTROLLER_STATUS_OK, "advertisement.contact");	

	}
}

if($mode == 'contact') {

	$advertisement_category_ads = explode(",",fn_get_lang_var('advertisement_category_ads'));
	$view->assign('advertisement_category_ads', $advertisement_category_ads);
	$advertisement_ads_budget = explode("@",fn_get_lang_var('advertisement_ads_budget'));
	$view->assign('advertisement_ads_budget', $advertisement_ads_budget);
	$advertisement_ads_spend_frequency = explode(",",fn_get_lang_var('advertisement_ads_spend_frequency'));
	$view->assign('advertisement_ads_spend_frequency', $advertisement_ads_spend_frequency);
}


?>