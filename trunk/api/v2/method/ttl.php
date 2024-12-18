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


	class ttl extends REST {
	
		public $data = "";

		public function __construct(){
			parent::__construct();				// Init parent contructor
		}
		
		
		
		public function ttl(){
                    if($this->get_request_method() != "GET"){
                        $this->response('',406);
                    }
                    $data['status'] = 'Success';
                    $data['ttl'] = time();
                    $this->response($this->json($data), 200);                        
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
