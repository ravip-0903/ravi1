<?php
if(!defined('AREA') ) { die('Access denied'); }
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	if($mode == 'add') {

		if(isset($_REQUEST['dispatch_new']) && $_REQUEST['dispatch_new'] == 'submit_project')
		{

			$requester_name		 	= addslashes($_REQUEST['requester_name']);
			$requester_email 		= addslashes($_REQUEST['requester_email']);
			$institute 				= addslashes($_REQUEST['requester_institute']);
			$contact_person_name    =addslashes($_REQUEST['contact_name']);
			$designation            =addslashes($_REQUEST['designation']);
			$contact_person_email   =addslashes($_REQUEST['contact_email']);
			$contact_person_phone	= $_REQUEST['contact_phone'];
			$reason 			    =addslashes($_REQUEST['reason']);
			if(trim($requester_name) == '' || (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$requester_email)) || !is_numeric($contact_person_phone) || trim($institute) == '' || trim($contact_person_name) == '' || trim($designation) == ''|| (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$contact_person_email)) || trim($reason) == '')
			{
				$data = array('rname' => $requester_name,'remail' => $requester_email,'institute'=>$institute,'cname'=>$contact_person_name,'designation'=>$designation,'cemail'=>$contact_person_email,'cphone'=>$contact_person_phone,'reason'=>$reason);
				$data =  urlencode(serialize($data));
				fn_set_notification('E', '', fn_get_lang_var('error_invitation_form'));
				return array(CONTROLLER_STATUS_OK, "labs.add?data=$data");
			}
			else
			{
			$query=db_query("insert into clues_labs_invitation (requester_name,requester_email,institute,contacted_person,designation,contact_person_email,phone,reason,creation_time)
				values('".$requester_name."','".$requester_email."','".$institute."','".$contact_person_name."','".$designation."','".$contact_person_email."','".$contact_person_phone."','".$reason."','".time()."')");
		

		$msg_body 				 = '<br>';		
		$msg_body 				.= '<table>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('labs_invitation_req_name').':</td>'.'<td>'.$requester_name.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('labs_invitation_req_email').':</td>'.'<td>'.$requester_email.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('labs_invitation_institute').':</td>'.'<td>'.$institute.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('labs_invitation_con_name').':</td>'.'<td>'. $contact_person_name.'</td>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('labs_invitation_con_designation').':</td>'.'<td>'.$designation.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('labs_invitation_con_email').':</td>'.'<td>'.$contact_person_email.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('labs_invitation_con_phone').':</td>'.'<td>'.$contact_person_phone.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('labs_invitation_reason').':</td>'.'<td>'.$reason.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '</table>';
		$to                      = $requester_email.";".Registry::get('config.labs_invitation_email_to');
		$from                    = Registry::get('config.labs_invitation_email_from');

		Registry::get('view_mail')->assign('msg_body', $msg_body);

	fn_instant_mail($to,$from,'labs/labs_invitation_subject.tpl','labs/labs_invitation_body.tpl');
	fn_set_notification('N', '', fn_get_lang_var('thanks_to_writing_to_labs_invitation'));
			return array(CONTROLLER_STATUS_OK, "labs.add");	
		}
	}
		else
		{
		$customer_name		 	= addslashes($_REQUEST['fname']);
		$from 					= $_REQUEST['email'];
		$mobile_no 				= $_REQUEST['mobile_no'];
		$address_1              =addslashes($_REQUEST['addr_1']);
		$address_2              =addslashes($_REQUEST['addr_2']);
		$city 					=addslashes($_REQUEST['city']);
		$state					=addslashes($_REQUEST['state']);
		$country 			    =addslashes($_REQUEST['country']);
		$pincode 				=addslashes($_REQUEST['pincode']);
		$institute_name 		=addslashes($_REQUEST['institute_name']);
		$graduation_complete 	=strtotime($_REQUEST['graduation_complete']);
		$branch_selected 		=addslashes($_REQUEST['concentration']);
		$pro_working 			=addslashes($_REQUEST['pro_working']);
		$expect_start_date 		=strtotime($_REQUEST['expect_start_date']);
		$expect_end_date 		=strtotime($_REQUEST['expect_end_date']);
		$pro_selection_reason 	=addslashes($_REQUEST['pro_selection']);
		$pro_vision 			=addslashes($_REQUEST['pro_vision']);
		
		$full_address = $address_1." ".$address_2." ".$city.",".$state." ".$country." ".$pincode;

		$query=db_query("insert into clues_labs (name,email,phone,address_1,address_2,state,city,pincode,country,institute_name,graduation_complete,concentration,project_working_on,pro_expctd_start_date,pro_expctd_end_date,selection_message,vision)
			values('".$customer_name."','".$from."','".$mobile_no."','".$address_1."','".$address_2."','".$state."','".$city."','".$pincode."','".$country."','".$institute_name."','".$graduation_complete."','".$branch_selected."','".$pro_working."','".$expect_start_date."'
				,'".$expect_end_date."','".$pro_selection_reason."','".$pro_vision."')");

		$from                  =  Registry::get('config.labs_email_from');
		$msg_body 				 = '<br>';	
		$msg_body 				 = fn_get_lang_var('labs_custom_message_for_user');		
		$msg_body 				.= '<table>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('labs_name').'</td>'.'<td>'.$customer_name.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('labs_email').'</td>'.'<td>'.$_REQUEST['email'].'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('labs_phone').'</td>'.'<td>'.$mobile_no.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('labs_address').'</td>'.'<td>'. $full_address.'</td>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('labs_institute_name').'</td>'.'<td>'.$institute_name.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('labs_expect_to_graduate').'</td>'.'<td>'.date('Y-m-d',$graduation_complete).'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('labs_concentration').'</td>'.'<td>'.$branch_selected.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('labs_pro_working_on').'</td>'.'<td>'.$pro_working.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('labs_expect_strt_date').'</td>'.'<td>'.date('Y-m-d',$expect_start_date).'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('labs_expect_end_date').'</td>'.'<td>'.date('Y-m-d',$expect_end_date).'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('labs_why_are_you_choosing').'</td>'.'<td>'.$pro_selection_reason.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('your_vision_on_this').'</td>'.'<td>'.$pro_vision.'</td>';
		$msg_body 				.= '</tr>';	
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('labs_custom_footer_message_for_user').'</td>';
		$msg_body 				.= '</tr>';	
		$msg_body 				.= '</table>';
		$to                      = $_REQUEST['email'].";".Registry::get('config.labs_email_to');

		Registry::get('view_mail')->assign('msg_body', $msg_body);
		$res = fn_instant_mail($to,$from,'labs/labs_subject.tpl','labs/labs_body.tpl');
		{
			fn_set_notification('N', '', fn_get_lang_var('thanks_to_writing_to_labs'));
		}
		return array(CONTROLLER_STATUS_OK, "labs.add");	
	}
	}

if($mode == 'submit_project')
{
	$name					= addslashes($_REQUEST['fname']);
	$email 					= addslashes($_REQUEST['email']);
	$phone					= $_REQUEST['mobile_no'];
	$institute 				= addslashes($_REQUEST['institute_name']);
	$project_working  		=addslashes($_REQUEST['pro_working']);
	$attach_code = $_FILES['attach_your_code']['type'];
	$presentation = $_FILES['presentation']['type'];
	$write_up  = $_FILES['write_up']['type'];
	$project_attach_code = Registry::get('config.project_attach_code_format');
	$project_presentation = Registry::get('config.project_presentation_format');
	$project_write_up = Registry::get('config.project_write_up_format');	

	if(!in_array($attach_code,$project_attach_code) || ($presentation!='' && !in_array($presentation,$project_presentation)) || ($write_up !='' && !in_array($write_up,$project_write_up)) || trim($name) == '' || (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email)) || !is_numeric($phone) || trim($institute) == '' || trim($project_working) == '')
	{
		$data = array('name' => $name,'email' => $email,'phone'=>$phone,'institute'=>$institute,'project'=>$project_working);
		$data =  urlencode(serialize($data));
		fn_set_notification('E', '', fn_get_lang_var('error_project_form'));
		return array(CONTROLLER_STATUS_OK, "labs.submit_project?data=$data");
	} 
	else
	{
	$attach_id = '';
	$upload_data = '';
	foreach($_FILES as $key=>$attach)
	{
		if($attach['error'] == 0)
		{

			$filename = time()."_".$attach['name'];
			move_uploaded_file($attach["tmp_name"],
				"images/labs_attachments/" . $filename);

		//Sync uploaded attachment to image server  

		$local = Registry::get('config.loc_img'). "labs_attachments/" . $filename;
		$remote =  Registry::get('config.remote_img');
		$parameter = Registry::get('config.rsync_parameter');
		$rsyn = exec("rsync $parameter $local $remote &");

		$data = file_get_contents("images/labs_attachments/".$filename);
		$attach_id = uploadAttachment("images/labs_attachments/",$filename,$data);   
		if(is_numeric($attach_id))
		{
			$attach_id_val.=$attach_id.";";
			$upload_data.=$filename.";";
		}    
		else
		{
			$upload_data.="N.A;";
		}  

	}
	else
	{
		$upload_data.="N.A;";
	}
}

	$upload_data_arr = rtrim($upload_data,';');
	$upload_data = explode(';',$upload_data_arr);
	$upload_data_ser = serialize($upload_data);
	$attach_id_val = rtrim($attach_id_val,";");

	$query=db_query("insert into clues_labs_project (name,email,phone,institute,project_submitting,project_code,creation_time)
				values('".$name."','".$email."','".$phone."','".$institute."','".$project_working."','".$upload_data_ser."','".time()."')");

	
		$msg_subject 			= fn_get_lang_var('labs_project_msg_subject');
		$msg_body 				 = '<br>';	
		$msg_body 				 = fn_get_lang_var('labs_project_custom_message_for_user');		
		$msg_body 				.= '<table>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('labs_name').':</td>'.'<td>'.$name.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('labs_email').':</td>'.'<td>'.$email.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('labs_phone').':</td>'.'<td>'.$phone.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('labs_institute_name').':</td>'.'<td>'.$institute.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('labs_pro_submitting').':</td>'.'<td>'.$project_working.'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('attach_your_code').':</td>'.'<td>'.($upload_data[0] == 'N.A' ? fn_get_lang_var('labs_project_not_submitted'):fn_get_lang_var('labs_project_submitted')).'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('attach_presentation').':</td>'.'<td>'.($upload_data[1] == 'N.A' ? fn_get_lang_var('labs_project_not_submitted'):fn_get_lang_var('labs_project_submitted')).'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('attach_write_up').':</td>'.'<td>'.($upload_data[2] == 'N.A' ? fn_get_lang_var('labs_project_not_submitted'):fn_get_lang_var('labs_project_submitted')).'</td>';
		$msg_body 				.= '</tr>';
		$msg_body 				.= '<tr>';
		$msg_body 				.= '<td>'.fn_get_lang_var('labs_project_custom_footer_message_for_user').'</td>';
		$msg_body 				.= '</tr>';	
		$msg_body 				.= '</table>';
		$to = $_REQUEST['email'].";".Registry::get('config.labs_project_email_to');
		$from = Registry::get('config.labs_project_email_from');
		sendElasticEmail($to, $msg_subject, " ", $msg_body, $from,'ShopClues.com', $attach_id_val); 
		fn_set_notification('N', '', fn_get_lang_var('thanks_to_writing_to_labs_project'));
		return array(CONTROLLER_STATUS_OK, "labs.submit_project");	
}
}
}
if($mode == 'add') {

	if($_REQUEST['data'])
	{
		$request_data = unserialize(urldecode($_REQUEST['data']));
		$view->assign('request_data', $request_data);
	}
	$labs_institute_names = explode(",",fn_get_lang_var('labs_institutes_name_data'));
	$view->assign('labs_institute_names', $labs_institute_names);
	$labs_concentration = explode(",",fn_get_lang_var('labs_concentration_data'));
	$view->assign('labs_concentration', $labs_concentration);
	$labs_project_working = explode(",",fn_get_lang_var('labs_project_working_on'));
	$view->assign('labs_project_working', $labs_project_working);
}

if($mode == 'submit_project')
{
	
	if($_REQUEST['data'])
	{
		$request_data = unserialize(urldecode($_REQUEST['data']));
		$view->assign('request_data', $request_data);
	}

	$labs_institute_names = explode(",",fn_get_lang_var('labs_institutes_name_data'));
	$view->assign('labs_institute_names', $labs_institute_names);
	$labs_concentration = explode(",",fn_get_lang_var('labs_concentration_data'));
	$view->assign('labs_concentration', $labs_concentration);
	$labs_project_working = explode(",",fn_get_lang_var('labs_project_working_on'));
	$view->assign('labs_project_working', $labs_project_working);

}	
?>