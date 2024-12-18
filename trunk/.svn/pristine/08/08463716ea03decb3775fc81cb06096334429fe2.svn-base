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



	class users extends REST {
	
		public $data = "";

		public function __construct(){
			parent::__construct();				// Init parent contructor
		}
		
		/* 
		 * Simple login API
		 *  Login must be POST method
		 *  email : <USER EMAIL>
		 *  pwd : <USER PASSWORD>
		 */
		
		public function login(){
                                  
                        
			// Cross validation if the request method is POST else it will return "Not Acceptable" status
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
                        $user = $this->user_login();
                        if($user['error'] ==1 ){
                            $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_user_credentials'));
                            $this->response($this->json($error), 400);               
                        }
                        else{
                            $msg = $user['user_data']; 
                            $this->response($this->json($msg), 200); 
                            
                        }
                        
		}
                
                public function user_login($email='',$password=''){
                    
                    if(empty($email) && empty($password)){
                            $email = $this->_request['user'];		
                            $password = $this->_request['password'];
                            
                    }
                    
                    $response['error'] = 0;                   
                    if(!empty($email) and !empty($password)){
                        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                            $result = db_get_row("SELECT user_id, firstname, lastname,email FROM cscart_users WHERE status='A' AND email='".$email."' AND password='".$password."'");
                            
                            if(!empty($result)){                                
		                    	$response['user_data'] = $result; 
		                    return $response;
                            }
                            $response['error'] = 1;
                            return $response;
                        }
                    }
                   
                    $response['error'] = 1;
                    return $response;                   
                    
                }
                
                
                public function anyno_login($email=''){
                    
                    if(empty($email)){
                            $email = $this->_request['user'];		
                    }                    
                    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                        $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_email'));
                        $this->response($this->json($error), 400);                        
                    }                    
                    $user_data['email'] = $email;
                    $response['reg'] = 1;
                    $check_emailid = db_get_row("select user_id from cscart_users where email='".$user_data['email']."'");
                    if(empty($check_emailid)){
                        $pass = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',5)),0,6);
                        $userarray = array('email'=>$user_data['email'],'password1'=>$pass,'password2'=>$pass);
                        $arr_user_profile_id = fn_update_user(0, $userarray, $_auth,'N',true,true,'','checkout');				
                        $response['reg'] = 0;
                    }                  
                    $response['user_info']['user_id'] = $arr_user_profile_id[0];
                    $response['user_info']['email'] = $userarray['email'];
                    $response['shipping_info']['profile_id'] = $arr_user_profile_id[1];
                    return $response;                    
                }
         




                /**
                * signup API
                * @email as email id of the user
                * @firstname as the first name of the user
                * @lastname as the last name of the user
                * @password1 as the password of the user
                * @password2 as the reentered password
                * 
                * This function will register a user if the Registration was successful then user id will be returned 
                * else error code 506 will be returned.
                */

               public function signup(){

                    if($this->get_request_method() != "POST"){
                        $this->response('',406);
                    }
                    $email = $this->_request['email'];
                    $firstname = $this->_request['firstname'];
                    $lastname = $this->_request['lastname'];
                    $password1 = $this->_request['password1'];
                    $password2 = $this->_request['password2'];
                    if(empty($email) || empty($password1) || empty($password2) || empty($firstname) || empty($lastname) || ($password1 != $password2) || (!filter_var($email, FILTER_VALIDATE_EMAIL))){
                        $msg = array('status' => "Error", "msg" => "Invalid parameters");
                        $this->response($this->json($msg), 400);    
                    }
                    
                    if($password1 != $password2){
                        $msg = array('status' => "Error", "msg" => fn_get_lang_var('api_param_invalid_params'));
                        $this->response($this->json($msg), 400);    
                    }
                    if(!($this->lettersOnly($firstname)) || !($this->lettersOnly($lastname)) ){
                        $msg = array('status' => "Error", "msg" => fn_get_lang_var('api_param_invalid_params'));
                        $this->response($this->json($msg), 400);    
                    }
                    
                    if ($res = fn_update_user(0, $this->_request, $_SESSION['auth'], '', true)) {

                            list($user_id, $profile_id) = $res;

                            $resp['user_id'] = $user_id;

                            $this->response($this->json($resp), 200);
                    } 
                    else {
                        $msg = array('status' => "Error", "msg" => fn_get_lang_var('api_registration_unsuccessful'));
                        $this->response($this->json($msg), 200);    
                    }                    
               }


		/*
		 *	Encode array into JSON
		*/
		
		function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}
	}

?>
