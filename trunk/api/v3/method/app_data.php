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


	class app_data extends REST {

		public $data = "";

		public function __construct(){
			parent::__construct();				// Init parent contructor
		}
		
	
		public function app_data(){
			
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$data['imei_no'] = $this->_request['imei_no'];
			$data['google_reg_id'] = $this->_request['google_reg_id'];
			$data['operator_name'] = $this->_request['operator_name'];
			
			$error = $this->validate_params($data);
			if(!$error){	
				
				$row = db_get_row('SELECT imei_no FROM clues_android_app_device_data WHERE imei_no =\''.$data['imei_no'].'\'');
				if(isset($row['imei_no'])){
					$sql = "UPDATE clues_android_app_device_data SET
							google_reg_id ='".$data['google_reg_id']. "' ,"."
							operator_name ='".$data['operator_name']. "' ,"."
							timestamp = CURRENT_TIMESTAMP
							WHERE imei_no='".$data['imei_no']."'";
					$exec = db_query($sql);
				}
				else{
					$sql = "INSERT INTO clues_android_app_device_data(imei_no, google_reg_id, operator_name)
					 value ('".$data['imei_no']."','".$data['google_reg_id']."','".$data['operator_name']."')";
				}	
				$exec = db_query($sql);
				if($exec){
					$msg = array('status' => "success", "msg" => 'App data updated successfully');
					$this->response($this->json($msg), 200);
				}
				else{
					$msg = array('status' => "failed", "msg" => 'Invalid Request Parameters');
					$this->response($this->json($msg), 400);
				}
			}
			else{
				$error = array('status' => "Failed", "msg" => 'Invalid Request Parameters');
				$this->response($this->json($error), 400);
			}
		}
		
		public function validate_params($params){
			
			$error = 0;
			foreach($params as $k => $v){
				if(empty($params[$k])){
					$error = 1;
					break;
				}
			}
			return $error;			
		}
		
		

		function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}
	}

?>
