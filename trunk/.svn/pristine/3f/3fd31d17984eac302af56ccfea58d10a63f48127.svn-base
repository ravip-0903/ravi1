<?php

class Authenticator extends REST{
    
    private static $HttpsApis = array('trackorder',
                                        'orders',
                                        'addressbook',
                                        'profile_details',
                                        'password',
                                        'myaccount',
                                        'cluesbucks',
                                        'cart',
                                        'wishlist',
                                        'checkout',
                                        'login',
                                        'signup'
                        );
    
    private static $SecureApis = array('trackorder',
                                        'orders',
                                        'addressbook',
                                        'profile_details',
                                        'password',
                                        'myaccount',
                                        'cluesbucks',
                                        'cart',
                                        'wishlist',
                                        'checkout'
                        );
    
    private $AvailableAlgos = array('SHA256');
    private $ExcludeToken = array('step_one');                                  //excludes checkout step one checkout from token authentication.user_id is not available in this step                  
    //private static $StaticKey = 'd12121c70dda5edfgd1df6633fdb36c0';
    private static $StaticKey;
    //private static $ExpireToken = Registry::get('settings.api_token_timeout');                                  //definded in seconds. After the defined seconds the token will expire.
    //private static $ExpireToken = 86400;
    private static $ExpireToken;
        
    /*
     * variables used to calculate HMAC. These variables are passed by user in constructor.
     * variables are private, and are initialised through constructor.
     * 
     */
    private $UserId;
    private $Timestamp;
    private $ApiName;
    private $Algo;
    private $Key;
    
    //user calculated token
    private $Token;       
    
    public function __construct($algo){
       parent::__construct();
       self::$ExpireToken = Registry::get('config.api_token_timeout');
       self::$StaticKey = Registry::get('config.api_static_key');
       $this->UserId = $this->_request['user_id'];
       $this->Timestamp = $this->_request['ttl'];
       $this->ApiName = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
       $this->Algo = $algo;
       $this->Key = $this->_request['key'];
       $this->Token = $this->_request['token'];
   }
   
   public function AuthenticateUser(){
       
       //check https connection status if the apis need to run on https
       if(in_array($this->ApiName,self::$HttpsApis) && $_SERVER['SERVER_PORT'] != 443){                         
           $msg =  fn_get_lang_var('api_request_unsecure');
           $code = 400;
           $this->ThrowError($msg, $code);
           
       }      
       
       //validate only developer static key if api is not in secure list.            
       if(!in_array($this->ApiName,self::$SecureApis) || ($this->ApiName == 'checkout' && (empty($this->_request['edit_step']) || $this->_request['edit_step'] =='step_one' ) )){                         //validate only developer static key if api is not in secure list.
            if($this->Key != self::$StaticKey){
                //log false developer  key alert
                $log['invalid_Key'] = 'True';
                $log['Api'] = $this->ApiName;
                $log['user_id'] =  $this->UserId; 
                $log['key'] = $this->Key;
                $this->RecordLogs($log);
                echo json_encode(array("result" => array(array("status"=>"failed","msg" => "Invalid Key", "error"=>"105"))));
                exit;
            }
       }
       else{                                                                    //we need to validate the token for user authentication
           
           if($this->Key != self::$StaticKey){
                //log false developer  key alert
               //log false developer  key alert
                $log['invalid_Key'] = 'True';
                $log['Api'] = $this->ApiName;
                $log['user_id'] =  $this->UserId;
                $log['key'] = $this->Key;
                $this->RecordLogs($log);
                $this->ThrowError('Invalid Key');                
            }
           
           try{                                                                     //validates if hmac algos and other paramerters are comatible with us or not.
             $this->ValidateParameters();
           }
           catch(Exception $e){
                $this->ThrowError($e->getMessage());          
           }
           
           try{
               $this->AuthProtocol();               
           }
           catch(Exception $e){                       
               $this->ThrowError($e->getMessage(),401);               
           }           
       } 
   }
   
   private function ValidateParameters(){
       
      if(!in_array($this->Algo, $this->AvailableAlgos)){ 
        $msg =  fn_get_lang_var('api_undefined_algorthim');
        throw new Exception ($msg);
      }
      
      if(empty($this->Algo) || empty($this->UserId) || empty($this->ApiName) || empty($this->Timestamp) || empty($this->Token)){
        $msg =  fn_get_lang_var('api_param_invalid_auth');
        //log invalid request alert
        $log['invalid_auth_parameters'] = 'True';
        $log['Api'] = $this->ApiName;
        $log['key'] = $this->Key;
        $log['user_id'] =  $this->UserId;
        $this->RecordLogs($log);
        throw new Exception ($msg);
      }      
       
   }
    
    private function AuthProtocol(){
        $hmac = $this->GenerateHmac();
        try{
            $this->ValidateHmac($hmac);            
        }
        catch(Exception $e){
            $log['msg'] = $e->getMessage();
            $log['Api'] = $this->ApiName;
            $log['key'] = $this->Key;
            $log['user_id'] =  $this->UserId;
            $log['token'] = $this->Token;
            $log['ttl'] = $this->Timestamp;
            $this->RecordLogs($log);
            throw new Exception ($e->getMessage());  
        }        
    }    
    
    /*
     * child class can override this method for new hmac generation scheme.
     * rules for HMAC generation are defined here.
     * Hmac algo, message generation and key usuage rules are defined here.
     */
    
    protected function GenerateHmac(){	 
        
        //$message = $this->Key.$this->UserId.$this->ApiName.$this->Timestamp;
        $message = $this->Key.$this->UserId.$this->Timestamp;
        $password = db_get_field("SELECT password FROM cscart_users WHERE status='A' AND user_id = ".$this->UserId);
        $hmac = hash_hmac ( $this->Algo, $message, $password,false  );
        return $hmac;        
    }
    
    /*
     * child class can override this method to create new validation scheme
     * validation rules of the token are defined her
     * expiration time and other restrictions rules are to be set here 
     */
    
    protected function ValidateHmac($hmac){   
        
        if($this->Token != $hmac){            
            //log for invalid token . $this->Token
            $msg =  fn_get_lang_var('api_param_invalid_token');
            //$msg = "Invalid Token - Valid token is = " . $hmac . " but we got is = " . $this->Token . " and old ttl is - " . $this->Timestamp;
            throw new Exception ($msg);           
        }
        elseif( ( time() - $this->Timestamp ) > self::$ExpireToken ){
              //log for expired token . $this->Token
            $msg = "Token Expired ";
            $msg =  fn_get_lang_var('api_param_expired_token');
            throw new Exception ($msg); 
        }    
    }
    
    private function ThrowError($msg,$code=''){
       
        if($code == ''){            
            $code = 203;
        }
        $res = array("status" => "failed",
                     "msg"    =>  fn_get_lang_var('api_request_non_auth'),
                     "extra"  =>  $msg,
                     "ttl"    => time(),
                     //"expires" => time()
                    );       
// remove following 
$code=200;                   
        $this->response($this->json($res),$code);            
        exit;
    }
    
    
    private function ThrowMsg(){       
        
        
    }
         
}

?>
