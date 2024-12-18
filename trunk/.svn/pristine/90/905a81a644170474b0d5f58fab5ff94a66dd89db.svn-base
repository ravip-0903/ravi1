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


	
	class cluesbucks extends REST {
	
		public $data = "";

		public function __construct(){
			parent::__construct();				// Init parent contructor
		}
		
                
                /* 
		 *	Simple Api for Quering clues bucks of a user 
		 *
		 *	Author: Mohit Baskota
		 *	Created for quering clues bucks through api
		 .
		 * 	___________________________________________________________________________________
		 *			Quering user's cluesbucks info
		 * 	____________________________________________________________________________________	
		 *	
		 *	Request 	: Strictly GET
		 * 	Parameters   	
		 *	@user     	: user id of the user whose cluesbucks info is required.
		 *      @sort_by        : options available->  timestamp,amount and expire_on
		 * 	@sort_order     : asc or desc
                 *      query format 	: /api/cluesbucks?user=12345
		 *                      : /api/cluesbucks?user=123456&sort_by=timestamp&sort_order=asc
		 *
		 * 	
		 */
                    
                
		public function cluesbucks(){
			if($this->get_request_method() != "GET"){
                       		 $this->response('',406);
                    	}
			$user = $this->_request['user_id'];
			if(empty($user)){
				$msg = array('status' => "Error", "msg" => fn_get_lang_var('api_param_invalid_params'));
                	 	$this->response($this->json($msg), 400);
			}
                        if(!(is_numeric($user))){
                            $error = array('status' => "Failed", "msg" => fn_get_lang_var('api_param_invalid_params'));
                            $this->response($this->json($error), 400);
                        }
			
			$sortings = array (
				'timestamp' => 'timestamp',
				'amount' => 'amount'
			);

			$directions = array (
				'asc' => 'asc',
				'desc' => 'desc'
			);

			$sort_order = empty($this->_request['sort_order']) ? '' : $this->_request['sort_order'];
			$sort_by = empty($this->_request['sort_by']) ? '' : $this->_request['sort_by'];

			if (empty($sort_order) || !isset($directions[$sort_order])){
				$sort_order = 'desc';
			}

			if (empty($sort_by) || !isset($sortings[$sort_by])) {
				$sort_by = 'timestamp';
			}
			if($sort_by=='timestamp'){
				$sec_sort_by=',change_id';
				$sec_sort_order=$sort_order;
			}
			else{
				$sec_sort_by='';
				$sec_sort_order='';
			}
			
			$cluesbucks = db_get_field("select data from cscart_user_data where user_id='".$user."' and type='W'");
			$cluesbucks = unserialize($cluesbucks);
			$log_count = db_get_field("SELECT COUNT(change_id) FROM ?:reward_point_changes WHERE user_id = ?i", $user);
			$userlog = db_get_array("SELECT rpc.change_id, rpc.action, rpc.timestamp, rpc.amount, rpc.reason, rpc.balance, rpc.expire_on, rpc.order_payment_history,rpc.ref_change_id, cbt.name FROM ?:reward_point_changes rpc LEFT JOIN clues_bucks_type cbt ON rpc.type_id=cbt.id WHERE rpc.user_id = ?i ORDER BY $sort_by $sort_order $sec_sort_by $sec_sort_order ", $user);
			$clues_bucks_type = db_get_array("SELECT id, name, code,expiry_days FROM clues_bucks_type WHERE status = ?s and id != ?i", 'A', 1);
			$response = array ();
			$response['total_cluesbucks'] = $cluesbucks;
                        foreach ($userlog as $k=>$ul) {
                            if (strpos($ul['reason'], 'refund on order') == FALSE) {
                                $statuses = fn_get_statuses(STATUSES_ORDER,true,true,true);
                                $reason = unserialize($ul['reason']);
                                $order_exist = fn_get_order_name($reason['order_id']);
                                $display_r = $userlog[$k]['reason'];
                                if ($ul['action'] == CHANGE_DUE_ORDER && $order_exist) {
                                    $display_r = fn_get_lang_var('order');
                                    $display_r .= ' '.$reason['order_id'].' '.$statuses[$reason['from']].' '.$statuses[$reason['to']];
                                }
                                if ($ul['action'] == CHANGE_DUE_ORDER_PLACE && $order_exist) {
                                    $display_r = fn_get_lang_var('order');
                                    $display_r .= ' '.$reason['order_id'].' : '.fn_get_lang_var('placed');;
                                }
                                if ($ul['action'] == CHANGE_DUE_ORDER_DELETE && $order_exist) {
                                    $display_r = fn_get_lang_var('order');
                                    $display_r .= ' '.$reason['order_id'].' : '.fn_get_lang_var('deleted');;
                                }
                                $userlog[$k]['reason'] = $display_r;
                            }
                        }
			$response['user_log']	= $userlog;
			$response['bucks_type'] = $clues_bucks_type;
		 	$response = array('status' => "SUCCESS", "msg" => $response);
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
