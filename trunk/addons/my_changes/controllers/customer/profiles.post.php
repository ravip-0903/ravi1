<?php
if ( !defined('AREA') )	{ die('Access denied');	}

if ($mode == 'upload_excel'){
    //Start code munish on 28 oct 2013
    $url_array = parse_url($_SERVER['HTTP_REFERER']);
    parse_str($url_array['query'], $query_string);
    //End code munish on 28 oct 2013
    if (empty($auth['user_id'])) {
		return array(CONTROLLER_STATUS_REDIRECT, $index_script);
	}
	fn_add_breadcrumb(fn_get_lang_var('upload_excel'));

	if($_REQUEST['mode_action'] == 'import'){
		unset($_SESSION['total_address']);
		unset($_SESSION['row_completed']);
		if(isset($_FILES["csvfile"])){
			if($_FILES["csvfile"]['name'] == ''){
                            //start code munish on 28 oct 2013
                            if($query_string['dispatch'] == "checkout.mashipping")
                                    {
                                        fn_set_notification('E','Error','Please Upload the excel file','I');
                                        return array(CONTROLLER_STATUS_REDIRECT, "checkout.mashipping");
                                    }
                                else
                                    {   
                                        fn_set_notification('E','Error','Please Upload the excel file','I');
                                        return array(CONTROLLER_STATUS_REDIRECT, "profiles.upload_excel");
                                    }//end code munish on 28 oct 2013
			} else { //code by munish
					$path_parts = pathinfo($_FILES["csvfile"]['name']);
                                        $extension = $path_parts['extension'];
                                        if($_FILES["csvfile"]["error"] == 0 && $extension == 'xls'){
						if($_FILES["csvfile"]['size'] < 1048576 ){
							$filename = date('m-d-Y-H-i-s').'-'.$_FILES["csvfile"]["name"];
                                                        move_uploaded_file($_FILES["csvfile"]["tmp_name"], "images/excel_addressbook/".$filename);
                                                        $user_data = array();
							$data = new Spreadsheet_Excel_Reader('images/excel_addressbook/'.$filename);

							$excel_address_name = fn_get_lang_var('excel_address_name');
							$excel_first_name = fn_get_lang_var('excel_first_name');
							$excel_last_name = fn_get_lang_var('excel_last_name');
							$excel_address1 = fn_get_lang_var('excel_address1');
							$excel_address2 = fn_get_lang_var('excel_address2');
							$excel_city = fn_get_lang_var('excel_city');
							$excel_state = fn_get_lang_var('excel_state');
							$excel_pin_code = fn_get_lang_var('excel_pin_code');
							$excel_mobile_number = fn_get_lang_var('excel_mobile_number');

						    if(($data->sst[0] == $excel_address_name) && ($data->sst[1] == $excel_first_name) && ($data->sst[2] == $excel_last_name) && ($data->sst[3] == $excel_address1) && ($data->sst[4] == $excel_address2) && ($data->sst[5] == $excel_city) && ($data->sst[6] == $excel_state) && ($data->sst[7] == $excel_pin_code) && ($data->sst[8] == $excel_mobile_number)){
							
								$rows = $data->rowcount($sheet_index=0);
								$cols = $data->colcount($sheet_index=0);
								//echo $rows.' x '.$cols;
								if($rows ==1){
									fn_set_notification('E','Error','Please enter some record in your file to upload the addressbook','I');
								}
								$tot_addresses = $rows-1;
								$_SESSION['total_address'] = $rows;
								$reports = '';
								$profile_row='';
								$firstname_row='';
								$lastname_row='';
								$address1_row='';
								$city_row='';
								$state_row='';
								$zip_row='';
								$phone_row='';
								$inv_row='';
								$invalid_row=0;
								$invalid_profile=0;
								$invalid_firstname=0;
								$invalid_lastname=0;
								$invalid_address=0;
								$invalid_address2=0;
								$invalid_city=0;
								$invalid_stat=0;
								$invalid_zip=0;
								$invalid_phone=0;
								$updated_records=0;
								$duplicate_records=0;
								$valid_state=array();
								$valid_states=fn_get_states('IN');

								foreach($valid_states as $st=>$states){
									$valid_state['code'][] = $states['code'];
									$valid_state['state'][] = $states['state'];
								}
								for($i=2; $i<=$rows; $i++){
									$_SESSION['row_completed']=$i;
									$user_data['profile_id']=0;

									  if (!preg_match('/^[a-zA-Z]+\s?[a-zA-Z]+$/', trim($data->val($i,1)))) {
										$invalid_profile++;
										$user_data['profile_name']='';
										$profile_row .=$i.',';
									  } else {
										$user_data['profile_name']=trim($data->val($i,1));
									  }
									  if (!preg_match('/^[a-zA-Z]+\s?[a-zA-Z]+$/', trim($data->val($i,2)))) {
										$invalid_firstname++;
										$user_data['s_firstname']='';	
										$firstname_row .=$i.',';
									  } else {
										$user_data['s_firstname']=trim($data->val($i,2));	
									  }
									  if (!preg_match('/^[a-zA-Z]+\s?[a-zA-Z]+$/', trim($data->val($i,3)))) {
										$invalid_lastname++;
		 								$user_data['s_lastname']='';
										$lastname_row .= $i.',';
									  } else {
		 								$user_data['s_lastname']=trim($data->val($i,3));
									  }
									  if (!preg_match("/[a-zA-Z0-9-\/,]/i", trim($data->val($i,4)))) {
										$invalid_address++;
		 								$user_data['s_address']='';
										$address1_row .= $i.',';
									  } else {
		 								$user_data['s_address']=trim($data->val($i,4));
									  }
									  if (!preg_match("/[a-zA-Z0-9-\/,]/i", trim($data->val($i,5)))) {
										$invalid_address2++;
		 								$user_data['s_address_2']='';
										$address2_row .= $i.',';
									  } else {
		 								$user_data['s_address_2']=trim($data->val($i,5));
									  }
									  if (!preg_match('/^[a-zA-Z]+\s?[a-zA-Z]+$/', trim($data->val($i,6)))) {
										$invalid_city++;
		 								$user_data['s_city']='';
										$city_row .= $i.',';
									  } else {
		 								$user_data['s_city']=trim($data->val($i,6));
									  }
									  if (in_array(trim($data->val($i,7)),$valid_state['code'])){
		 								$user_data['s_state']=trim($data->val($i,7));
									  }elseif (in_array(trim($data->val($i,7)),$valid_state['state'])){
										$st=array_search(trim($data->val($i,7)),$valid_state['state']);
		 								$user_data['s_state']=$valid_state['code'][$st];
									  } else {
										$invalid_stat++;
		 								$user_data['s_state']='';
										$state_row .= $i.',';
									  }
									  if (!ereg('^[0-9]{5,6}$', trim($data->val($i,8))) || strlen(trim($data->val($i,8))) !='6') {
										$invalid_zip++;
		 								$user_data['s_zipcode']='';
										$zip_row .= $i.',';
									  } else {
		 								$user_data['s_zipcode']=trim($data->val($i,8));
									  }
							 		  if(!preg_match("/^[0-9]{10}/", trim($data->val($i,9)))) {
										$invalid_phone++;
		 								$user_data['s_phone']='';
										$phone_row .= $i.',';
									  } else {
		 								$user_data['s_phone']=trim($data->val($i,9));
									  }
		if($user_data['profile_name']==''){
			$user_data['profile_name'] = $user_data['s_firstname'].' '.$user_data['s_lastname'];
		        $chk_profile_name=db_get_field("SELECT profile_name FROM ?:user_profiles WHERE user_id=?i AND profile_name LIKE ?s ORDER BY profile_id DESC LIMIT 0,1", $auth['user_id'], $user_data['profile_name'].'%');

				if($chk_profile_name){
					$pn = explode('-', $chk_profile_name);
					$dpn = $pn[1]+1;
					$user_data['profile_name'] = $pn[0].'-'.$dpn;
				}
		}
								    if(($user_data['s_firstname']=='') || ($user_data['s_lastname']=='') || ($user_data['s_address']=='') || ($user_data['s_city']=='') || ($user_data['s_state']=='') || ($user_data['s_zipcode']=='') || ($user_data['s_phone']=='')){ //code change by munish on 28 oct 2013 add phone in condition
										$invalid_row++;
										$inv_row .= $i.',';
								    } else {
									   $chk_profile = db_get_array("SELECT profile_id, profile_type, profile_name,s_title,s_firstname,s_lastname,s_address, s_address_2, s_city,  s_state ,s_country ,s_zipcode ,s_phone FROM ?:user_profiles WHERE user_id = ?i AND s_firstname =  ?s AND s_lastname = ?s AND s_address = ?s AND s_city = ?s AND s_state = ?s", $auth['user_id'], $user_data['s_firstname'], $user_data['s_lastname'], $user_data['s_address'], $user_data['s_city'], $user_data['s_state']);


										if(count($chk_profile) == 0){

										    fn_update_user($auth['user_id'],$user_data,$auth, 'Y', false, false, 'update_addressbook');
										    $updated_records++;
										} else {
										    $duplicate_records++;
										}
								    }
								}
								if($updated_records > 0){
									$reports .= '<p class="suc_rep" style="font-size:15px;">'.$updated_records.' address book inserted off of '.$tot_addresses.' addresses in excel file</p>';
								}
								if($duplicate_records > 0){
									$reports .= '<p class="suc_rep" style="font-size:15px;">'.$duplicate_records.' duplicate record found</p>';
								}
								if($invalid_row > 0){
									$inv_row=eregi_replace(',$', '', $inv_row);
									$reports .= '<p class="suc_rep">-'.$invalid_row.' row found invalid at rows '.$inv_row.'</p>';
								}
								if($invalid_firstname > 0){
									$firstname_row=eregi_replace(',$', '', $firstname_row);
									$reports .= '<p class="suc_rep">-'.$invalid_firstname.' invalid first name found at row '.$firstname_row.'</p>';
								}
								if($invalid_lastname > 0){
									$lastname_row=eregi_replace(',$', '', $lastname_row);
									$reports .= '<p class="suc_rep">-'.$invalid_lastname.' invalid last name found at row '.$lastname_row.'</p>';
								}
								if($invalid_address > 0){
									$address1_row=eregi_replace(',$', '', $address1_row);
									$reports .= '<p class="suc_rep">-'.$invalid_address.' invalid address found at row '.$address1_row.'</p>';
								}
								if($invalid_city > 0){
									$city_row=eregi_replace(',$', '', $city_row);
									$reports .= '<p class="suc_rep">-'.$invalid_city.' invalid city found at row '.$city_row.'</p>';
								}
								if($invalid_stat > 0){
									$state_row=eregi_replace(',$', '', $state_row);
									$reports .= '<p class="suc_rep">-'.$invalid_stat.' invalid state found at row '. $state_row.'</p>';
								}
								if($invalid_zip > 0){
									$zip_row=eregi_replace(',$', '', $zip_row);
									$reports .= '<p class="suc_rep">-'.$invalid_zip.' invalid zip found at row '.$zip_row.'</p>';
								}
								if($invalid_phone > 0){
									$phone_row=eregi_replace(',$', '', $phone_row);
									$reports .= '<p class="suc_rep">-'.$invalid_phone.' invalid mobile number found at row '. $phone_row.'</p>';
								}
							  } else {
								fn_set_notification('E','Error','Template field not in order, Please check your excel file','I');
							  }
							} else {
								fn_set_notification('E','Error','It is not allowed to upload files with size more than 1M','I');
							}
								$_SESSION['success_reports'] = $reports;
                                                                //start code munish on 28 oct 2013
                                                                if($inv_row>0)
                                                                {
                                                                    fn_set_notification('E', fn_get_lang_var('information'), $reports);
                                                                }
                                                                if($query_string['dispatch'] == "checkout.mashipping")
                                                                    {
                                                                        return array(CONTROLLER_STATUS_OK, "checkout.mashipping");
                                                                    }
                                                                else
                                                                    {
                                                                        return array(CONTROLLER_STATUS_OK, "profiles.manage_addressbook");
                                                                    }//end code munish on 28 oct 2013
					} else {
					if($query_string['dispatch'] == "checkout.mashipping")
                                            {
                                                fn_set_notification('E','Error','There is an error in your file or please check extension of file','I');
						return array(CONTROLLER_STATUS_REDIRECT, "checkout.mashipping");
                                            }
                                        else
                                            {
                                                fn_set_notification('E','Error','There is an error in your file or please check extension of file','I');
						return array(CONTROLLER_STATUS_REDIRECT, "profiles.upload_excel");
                                            }	
					}
			}
		}
	}
	if($_REQUEST['mode_action'] == 'delete_prf'){
		foreach($_REQUEST['prfChk'] as $k=>$v){
			db_query("DELETE FROM ?:user_profiles WHERE profile_id = ?i", $v);
		}
		fn_set_notification('N','Addresses','deleted successfully','I');
                return array(CONTROLLER_STATUS_OK, "profiles.manage_addressbook");
	}
}

if ($mode == 'remove_error'){
	unset($_SESSION['success_reports']);
}
?>
