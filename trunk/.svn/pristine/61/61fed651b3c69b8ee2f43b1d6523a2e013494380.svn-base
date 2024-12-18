<?php


require dirname(__FILE__) ."/users.php";
require dirname(__FILE__) ."/profiles.php";

class SocialLogin extends REST {
	
	public $data = "";

	public function __construct(){
		parent::__construct();				// Init parent contructor
	}
	
	
	public function callFb($access_token){
		
		$config['appId'] = Registry::get('config.shopclues_app_id_for_login');
		$config['secret'] = Registry::get('config.facebook_secret_android_login');
		$facebook = new Facebook($config);
		$facebook->setAccessToken($access_token);
		$user = $facebook->getUser();
		if ($user) {
			try {
			  // Proceed knowing you have a logged in user who's authenticated.
			  $user_profile = $facebook->api("/$user");
			} 
			catch (FacebookApiException $e) {
				error_log($e);
				$user = null;
				$msg = array('status' => "Failed", "msg" => fn_get_lang_var('api_fb_login_unsuccessful'));
				$this->response($this->json($msg), 200);
			}
		}
		
		if(!$user_profile){
			$msg = array('status' => "Failed", "msg" => fn_get_lang_var('api_fb_login_unsuccessful'));
			$this->response($this->json($msg), 200);  
		}
		return $user_profile;	
	}	
		
	public function fblogin($checkout = 0){    
                       
		$access_token = $this->_request['token'];
		$user_profile = $this->callFb($access_token);		
		$users = new users();
		$status = $users->anyno_login($user_profile['email']);
		if($status['reg'] == 1){
			$res = $this->getRegUser($user_profile['email']);		
		}
		else{
			$res = $this->getNewUser($user_profile['email'],$status,$user_profile,'fb');
		}		
		if($checkout == 1){
			return $res;
		}
		$this->response($this->json($res['user_info']), 200);
		
	} 
	
	public function fbcheckout(){
		$checkout = TRUE;
		$resp = $this->fblogin($checkout);		
		$address = new profiles();
		$dummy = $address->getAddressBook($resp['user_info']['user_id']);
		$resp['shipping_info'] = $dummy[0];
		$this->response($this->json($resp), 200);
		
	}
	
	public function callGoogle($authorization_code){
		
		$google_client_id = Registry::get('config.google_app_login_id');
		$google_client_secret = Registry::get('config.google_app_login_secret');
		$google_redirect_url 	=  Registry::get('config.glogin_redirect_uri');; //path to your script
		$gClient = new Google_Client();
		$gClient->setApplicationName('shopclues');
		$gClient->setClientId($google_client_id);
		$gClient->setClientSecret($google_client_secret);
		//$gClient->setRedirectUri($google_redirect_url);
		$google_oauthV2 = new Google_Oauth2Service($gClient);
		try{
			$gClient->authenticate($authorization_code);
		}
		catch(Exception $e){
			$msg = array('status' => "Failed", "msg" => fn_get_lang_var('api_glogin_login_unsuccessful'));
			$this->response($this->json($msg), 200);  
		}
		if($gClient->getAccessToken()){
			  //For logged in user, get details from google using access token
			  $user	= $google_oauthV2->userinfo->get();
		}
		else{
			$msg = array('status' => "Failed", "msg" => fn_get_lang_var('api_glogin_login_unsuccessful'));
			$this->response($this->json($msg), 200);  
		}
		return $user;
	}	
		
	public function glogin($checkout = 0){    
                       
		$access_token = $this->_request['token'];
		$user_profile = $this->callGoogle($access_token);		
		$users = new users();
		$status = $users->anyno_login($user_profile['email']);
		if($status['reg'] == 1){
			$res = $this->getRegUser($user_profile['email']);		
		}
		else{
			$res = $this->getNewUser($user_profile['email'],$status,$user_profile,'google');
		}		
		if($checkout == 1){
			return $res;
		}
		$this->response($this->json($res['user_info']), 200);
		
	} 
	
	public function gcheckout(){
		$checkout = TRUE;
		$resp = $this->glogin($checkout);		
		$address = new profiles();
		$dummy = $address->getAddressBook($resp['user_info']['user_id']);
		$resp['shipping_info'] = $dummy[0];
		$this->response($this->json($resp), 200);
		
	}	
	
	public function getRegUser($email){
		
		$user_data = db_get_row("SELECT user_id, firstname, lastname,email,password FROM cscart_users WHERE status='A' AND email='".$email."'");
		$user_data['hash_key'] = $user_data['password'];
		unset($user_data['password']);
		$data['user_info'] = $user_data;
		$data['user_info']['user_type'] = 'R';
		return $data;		
	}
	
	public function getNewUser($email,$info,$user_profile,$login_type=''){
		
		if($login_type=='fb'){
			$firstname = $user_profile['first_name'];
			$lastname = $user_profile['last_name'];
			$id = $user_profile['id'];
			$email = $user_profile['email'];
			$gender    =  strtoupper(substr($user_profile['gender'],0,1));
		}
		else{
			$firstname = $user_profile['given_name'];
			$lastname = $user_profile['family_name'];
			$id = $user_profile['id'];
			$email = $user_profile['email'];
			$gender    =  strtoupper(substr($user_profile['gender'],0,1));
			
		}
		
		$user_data = db_get_row("SELECT password FROM cscart_users WHERE status='A' AND email='".$email."'");
		$resp['user_info'] = $info['user_info'];
		$resp['user_info']['hash_key'] = $user_data['password'];
		$resp['user_info']['firstname'] = $firstname;
		$resp['user_info']['lastname'] = $lastname;
		$resp['user_info']['user_type'] = 'U';
		
		db_query("update cscart_users SET 
						gender='".$gender."',
						firstname='".$firstname."',
						lastname='".$lastname."',
						referer='fb_login_android_app_".$id."'
						where email='".$email."'");
		return $resp;
	}
                
             
	function json($data){
		if(is_array($data)){
			return json_encode($data);
		}
	}
}

?>
