<?php

	/* 	This is an example class script proceeding secured API
		To use this class you should keep same as query string and function name
		Ex: If the query string value rquest=delete_user Access modifiers doesn't matter but function should be
		     function delete_user(){
				 Your code goes here
			 }
		Class will execute the function dynamically;
		
		usage :
		
		    $object->response(output_data, status_code);
			$object->_request	- to get santinized input 	
			
			output_data : JSON (I am using)
			status_code : Send status message for headers 	*/


	class profiles extends REST {
	
		public $data = "";

		public function __construct(){
			parent::__construct();				// Init parent contructor
		}
		
		
		
		/* 
		 *	Simple Api for Adderess book manipulation 
		 *
		 *	Created for addressbook manipulation through api
		 .
		 * 	___________________________________________________________________________________
		 *			Quering user's addressbook info
		 * 	____________________________________________________________________________________	
		 *	
		 *	Request 	: Strictly GET
		 * 	Parameters   	
		 *	@user_id     	: user id of the user whose address info is required.
		 *
		 * 	query format 	: addressbook?user_id=12345
		 *
		 * 	Return type  	: returns Json data representing the addressbook info for user.
		 *
		 * 	
		 *	______________________________________________________________________________________
		 * 			Creating  New Address Info
		 *	______________________________________________________________________________________
		 * 	
		 *	REQUEST   	: Strictly POST
		 *	Parameters	:
		 *	@user_id 	: id of the user for which new address is to be created.
		 * 	@fname		: First Name of user
		 *	@lname		: lastname of user
		 * 	@address	: address of user
		 *	@address2	: address line 2
		 * 	@city<pre>Arr
		 *	@state
		 *	@pincode
		 *	@phone
		 *	@address_name	: Name of the addressbook to be created
		 * 	
		 *
		 *	query format 	: /api/addressbook
		 *
		 *	________________________________________________________________________________________
		 *			Updating Existing Addressbook
		 *	________________________________________________________________________________________
		 *	
		 *	REQUEST 	: Strictly PUT
		 *	Parameters 	: same as creating new address. add profile_id and primary(if profile id is to be made primary) of the addressbook 
		 *			  to be updated
 		 *
		 *	Query format 	: /api/addressbook
		 *	
		 *	________________________________________________________________________________________
		 *
		 *			Deleting Existing Addressbook
		 *	________________________________________________________________________________________
		 *
		 *	REQUEST 	: Strictly DELETE
		 *	Parameters	: 
		 *	@user_id	: User id whose address book is to be deleted.
		 *	@profile_id	: profile id of address book to be deleted.
		 *
		 *	Query format	: addressbook?user_id=12345&profile_id=12345
		 *
		 */
		
		
		public function addressbook(){
			                              
                        $user_id = $this->_request['user_id'];
                        if(empty($user_id)){
				 $msg = array('status' => "Error", "msg" => fn_get_lang_var('api_param_invalid_params'));
	                	 $this->response($this->json($msg), 400);  
			
			}
                        if(!(is_numeric($user_id))){
                            $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_params'));
                            $this->response($this->json($error), 400);
                        }
				
			if($this->get_request_method() == "GET"){			
				$data = $this->getAddressBook($user_id);
				if($data)
					$this->response($this->json($data), 200);
				else{
					$error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_params'));
                            		$this->response($this->json($error), 400);
				
				}
			}
			elseif($this->get_request_method() == "POST"){			
				$new_address = $this->createAddressBook('create');
				if($new_address){
					$msg = array('status' => "Successful", "msg" => $new_address);
					$this->response($this->json($msg), 200);
				}
					
				else{
				 $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_params'));
				 $this->response($this->json($error), 400);
				}
				
			}
						
			elseif($this->get_request_method() == "PUT"){			
				$new_address = $this->createAddressBook('update');
				if($new_address){
					$msg = array('status' => "Successful", "msg" => fn_get_lang_var('api_profile_updated'));
					$this->response($this->json($msg), 200);
				}					
				else{
				 $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_profile_user_not_match'));
				 $this->response($this->json($error), 400);
				}
				
			}
			elseif($this->get_request_method() == "DELETE"){
				$affected_rows = $this->deleteAddressBook($this->_request['profile_id'],$user_id);
				if($affected_rows){
					$msg = array('status' => "Successful", "msg" => fn_get_lang_var('api_address_deleted'));
					$this->response($this->json($msg), 200);
				}					
				else{
                                        $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_profile_not_deleted'));
                                        $this->response($this->json($error), 400);
				}
				
			}
			else
				$this->response('',406);					
		}
		public function getAddressBook($user_id){
			$result = fn_get_user_profiles_for_address_book($user_id);
			return $result;
		
		}
				
		public function deleteAddressBook($profile_id,$user_id){
                        if(!(is_numeric($profile_id))){
                            $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_profile_id'));
                            $this->response($this->json($error), 400);
                        }
			$status = db_query("DELETE FROM ?:user_profiles WHERE profile_id = ?i and profile_type != 'P' and user_id = ?i", $profile_id,$user_id);
                        return mysql_affected_rows();
		
		}
		
		
			
		public function createAddressBook($action){
                                            
                        $_data = array();	
                        $_data['user_id']    = $this->_request['user_id'];
                        $_data['s_firstname'] = $_data['b_firstname'] = $this->_request['fname'];
                        $_data['s_lastname'] =  $_data['b_lastname'] = $this->_request['lname'];
                        $_data['s_address'] = $_data['b_address'] = $this->_request['address'];
                        $_data['s_address_2'] = $_data['b_address_2'] = $this->_request['address2'];
                        $_data['s_city'] = $_data['b_city'] = $this->_request['city'];
                        $_data['s_state'] = $_data['b_state'] = $this->_request['state'];
                        $_data['s_zipcode'] =  $_data['b_zipcode'] = $this->_request['pincode'];
                        $_data['s_phone'] =  $_data['b_phone'] = $this->_request['phone'];
                        $_data['profile_name'] = $this->_request['address_name'];
                        $_data['s_country'] = $_data['b_country'] = 'IN';

                        if($this->array_empty($_data)){
                            $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_params'));
                            $this->response($this->json($error), 400);
                            
                        }
                        if(!(is_numeric($_data['s_zipcode'])) || !( is_numeric($_data['s_phone']) ) ){
                            $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_params'));
                            $this->response($this->json($error), 400);
                        }
                        if(!preg_match("/^[0-9]{10}/", trim($_data['s_phone']))) {
                             $msg = array('status' => "Error", "msg" => fn_get_lang_var('api_param_invalid_phone'));
                             $this->response($this->json($msg), 400);
                         
                        }
                        
                        if(!($this->lettersOnly($_data['s_firstname'])) || !($this->lettersOnly($_data['s_lastname'])) ){
                            $msg = array('status' => "Error", "msg" => fn_get_lang_var('api_param_invalid_params'));
                            $this->response($this->json($msg), 400);    
                        }
                        
                        
                        $valid_states=fn_get_states('IN');
                        foreach($valid_states as $st=>$states){
                              $valid_state['code'][] = $states['code'];
                              $valid_state['state'][] = $states['state'];
                        }
                        
                        $temp_state = $_data['s_state'];
                        $valid = 0;
                        for($i=0;$i<count($valid_state['code']);$i++){
                             if($valid_state['code'][$i] == $temp_state){
                                $valid = 1;
                                break;
                            }
                        }
                        
                        if(!$valid){
                             $msg = array('status' => "Error", "msg" => fn_get_lang_var('api_param_invalid_state'));
                             $this->response($this->json($msg), 400);                             
                        }
                        
                        
                        $_data['verified'] = 0;
						$_data['profile_type'] = 'S';	
                        if($action == 'create'){
                            $_data['profile_id'] = 0;
                            $new_profile = db_query("INSERT INTO ?:user_profiles ?e", $_data);
                            return $new_profile;                                                  
                        }
                        
                        //for updating address book
                        else{
                            
                            $primary = 0;
                            $_data['profile_id'] = $this->_request['profile_id'];
                            if(!(is_numeric($_data['profile_id']))){
                                $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_profile_id'));
                                $this->response($this->json($error), 400);
                            }
                            if($this->_request['primary'] == '1'){
                                
                                $_data['profile_type'] = 'P';
                                $primary = 1;
                              
                             }
                             elseif($this->_request['primary'] == '0'){
                                $result = db_get_row("SELECT profile_id FROM ?:user_profiles WHERE user_id = ".$_data['user_id']." and profile_type = 'P' ");
                               
                                if($result['profile_id'] == $_data['profile_id']){
                                    $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_primary_secondry_profile_error'));
                                    $this->response($this->json($error), 400); 
                                    
                                }
                                else
                                    $_data['profile_type'] = 'S';
                              
                             }
                             else{
                                 $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_primary'));
                                 $this->response($this->json($error), 400);                                 
                                 
                             }
                            if(!(is_numeric($_data['profile_id']))){
                                $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_profile_id'));
                                $this->response($this->json($error), 400);
                            }
                            if($primary){
                                $status = db_query("UPDATE ?:user_profiles SET profile_type = 'S' WHERE user_id = ?i and profile_type = 'P'",$_data['user_id']);
                            
                            }
                         
                            $status = db_query("UPDATE ?:user_profiles SET ?u WHERE profile_id = ?i and user_id = ?i", $_data, $_data['profile_id'],$_data['user_id']);
                            return mysql_affected_rows();   
                                     
                        }	
		}
                
                /* 
		 *	Simple Api for profile info  manipulation and changing Password 
		 *
		 *	Created for addressbook manipulation through api
		 .
		 * 	___________________________________________________________________________________
		 *			Edit profile info
		 * 	____________________________________________________________________________________	
		 *	
                 *      module          : profile_details
		 *	Request 	: Strictly PUT
		 * 	Parameters   	
		 *	@firstname     	: firstname of the user
		 *      @lastname       : lastname of the user
                 *      @phone          : phone no of user
                 *      @user_id        : user id of user whose info is to be edited.
		 * 	query format 	: api/profile_details
		 *
		 * 	______________________________________________________________________________________
		 * 			Change User's password
		 *	______________________________________________________________________________________
		 * 	
		 *	REQUEST   	: Strictly PUT
		 *	Parameters	:
		 *	@user_id 	: id of the user whose password is to be changed
		 * 	@passwordc      : current password
		 *	@password1	: new password
		 * 	@password2	: confirm password
		 *	 	
		 *
		 *	query format 	: /api/password
		 *
		 */
                
                
                
                public function profile_details(){
                    
                    $user_id = $this->_request['user_id'];
                    $firstname = $this->_request['firstname'];
                    $lastname = $this->_request['lastname'];
                    $phone    = $this->_request['phone'];
                    if(!(is_numeric($user_id))){
                            $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_params'));
                            $this->response($this->json($error), 400);
                    }
                    if(empty($user_id) || empty($firstname) || empty($lastname) || empty($phone)){
                             $msg = array('status' => "Error", "msg" => fn_get_lang_var('api_param_invalid_params'));
                             $this->response($this->json($msg), 400);  

                    }
                    
                    if(!($this->lettersOnly($firstname)) || !($this->lettersOnly($lastname)) ){
                            $msg = array('status' => "Error", "msg" => fn_get_lang_var('api_param_invalid_params'));
                            $this->response($this->json($msg), 400);    
                    }
                    
                     if(!preg_match("/^[0-9]{10}/", trim($phone))) {
                             $msg = array('status' => "Error", "msg" => fn_get_lang_var('api_param_invalid_phone'));
                             $this->response($this->json($msg), 400);
                         
                     }
                    
                    if($this->get_request_method() == "PUT"){			
                            $new_details = fn_update_user($user_id, $this->_request, $_SESSION['auth'], '', true);                                                                                         
                            if(!empty($new_details)){
                                    
                                    list($user_id, $profile_id) = $new_details;
                                    $resp = array();
                                    $resp['user_id'] = $user_id;
                                    $resp[profile_id] = $profile_id;
                                    $msg = array('status' => fn_get_lang_var('api_profile_updated_successfully'), "msg" => $resp);
                                    $this->response($this->json($msg), 200);
                            }
                            else{
                                    $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_request'));
                                    $this->response($this->json($error), 400);
                            }
			
			}
                        else
                            $this->response('',406);
                }
                
                public function password(){
                        $user_id = $this->_request['user_id'];
                        $cur_pass = $this->_request['passwordc'];
                        if(empty($user_id)){
                                 $msg = array('status' => "Error", "msg" => fn_get_lang_var('api_param_invalid_params'));
                                 $this->response($this->json($msg), 400);  

                        }
                        if(!(is_numeric($user_id))){
                            $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_params'));
                            $this->response($this->json($error), 400);
                        }

                        $pass1 = $this->_request['password1'];
                        $pass2 = $this->_request['password2'];
                        if($this->get_request_method() == "PUT"){
                            if(!empty($cur_pass) && ($pass1 == $pass2) ){
                                  $password_check = db_get_field("SELECT password FROM cscart_users WHERE user_id = ". $user_id);
                                  
                                  if($password_check == $cur_pass)
                                        $stat = db_query("UPDATE cscart_users SET password= '".$pass1."' WHERE user_id = ".$user_id);
                                  if($stat){
                                        $msg = array('status' => "Successful.", "msg" => fn_get_lang_var('api_password_updated_successfully'));
                                        $this->response($this->json($msg), 200);                
                                  }
                                  else{
                                        $msg = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_params'));
                                        $this->response($this->json($msg), 400);
                                 }
                            }
                            else{
                                        $msg = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_params'));
                                        $this->response($this->json($msg), 400);                                      
                            }
                        }                        
                        else
                                $this->response('',406);
                    }
                    
                    
                    
                    /*public function wishlist(){
                        $user_id = $this->_request['user_id'];
                        if(empty($user_id)){
				 $msg = array('status' => "Error", "msg" => "Invalid parameters");
	                	 $this->response($this->json($msg), 400);  
			
			}
                        if(!(is_numeric($user_id))){
                            $error = array('status' => "Failed", "msg" => "Invalid Parameters");
                            $this->response($this->json($error), 400);
                        }
				
			if($this->get_request_method() == "GET"){			
				$data = $this->getWishlist($user_id);
                        }
                        else
                                $this->response('',406);                   
                        
                    }*/
                    
                    /*
                    public function getWishlist(){
                        
                        echo "<pre>";
                        print_r($_SESSION);
                        fn_add_breadcrumb(fn_get_lang_var('wishlist_content'));

                    $products = !empty($wishlist['products']) ? $wishlist['products'] : array();
                    print_r($products);
                        
                        
                        
                    }*/
                    
                    /*
                     *  request format : api/myaccount?user_id=1393638
                     * 
                     *  
                     */
                    
                    
                
                    public function myaccount(){
                       

                        $uid = $this->_request['user_id'];
                        if(empty($uid)){
                            $msg = array('status' => "Error", "msg" => fn_get_lang_var('api_param_empty_user_id'));
                            $this->response($this->json($msg), 400);  

                        }
                        if(!(is_numeric($uid))){
                            $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_user_id'));
                            $this->response($this->json($error), 400);
                        }
                        
                        $resonse = array();
                             
                        $user_data=fn_get_user_short_info($uid);
                        $shipping_data = db_get_row("SELECT s_address, s_address_2, s_city,  s_state ,s_country ,s_zipcode ,s_phone FROM ?:user_profiles USE INDEX(usr_pro_idx) WHERE user_id = ?i AND profile_type = 'P'", $uid);
                        $response['user_data'] = $user_data;
                        $response['shipping_address']  = $shipping_data;    
                        
                        $o = new orders();
                        $orders = $o->getOrders($uid);
                        $response['order_history'] = $orders; 
                        $this->response($this->json($response), 200);
	
                }

		/*
		 *	Encode array into JSON
		*/
		
		public function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}

	}

?>
