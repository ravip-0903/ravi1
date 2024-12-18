<?php

define('AREA', 'C');
define('AREA_NAME', 'customer');
define('ACCOUNT_TYPE', 'customer');

// Key has been checked for unknown host if they try to submit the form then it just exist from here with out connecting to db. 
/*if(!(isset($_REQUEST['key']) && ($_REQUEST['key'] == 'd12121c70dda5edfgd1df6633fdb36c0'))){
	echo json_encode(array("result" => array(array("status"=>"failed","msg" => "Invalid Key", "error"=>"105"))));
	exit;
}*/

require  dirname(__FILE__) . '/../prepare.php';
require  dirname(__FILE__) . '/../init.php';

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


	require_once("Rest.inc.php");
	
	class API extends REST {
	
		public $data = "";

		public function __construct(){
			parent::__construct();				// Init parent contructor
		}
		
		/*
		 * Public method for access api.
		 * This method dynmically call the method based on the query string
		 *
		 */
		public function processApi(){
			$func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));

			if((int)method_exists($this,$func) > 0){
				$this->$func();
			}
			else{
				$this->response('',404); //If the method not exist with in this class, response would be "Page not found".
			}
		}
		
		/* 
		 * Simple login API
		 *  Login must be POST method
		 *  email : <USER EMAIL>
		 *  pwd : <USER PASSWORD>
		 */
		
		private function login(){
			// Cross validation if the request method is POST else it will return "Not Acceptable" status
			/*if($this->get_request_method() != "POST"){

				$this->response('',406);
			}*/
			
			$email = $this->_request['user'];		
			$password = $this->_request['password'];
			
			// Input validations
			if(!empty($email) and !empty($password)){
				if(filter_var($email, FILTER_VALIDATE_EMAIL)){
					$result = db_get_row("SELECT user_id, firstname, lastname FROM cscart_users WHERE status='A' AND user_type='C' AND is_root='N' AND company_id=0 AND email='".$email."' AND password='".md5($password)."'");
					if($result){
						// If success everythig is good send header as "OK" and user details
						$this->response($this->json($result), 200);
					}

					// If invalid inputs "Bad Request" status message and reason
					$error = array('status' => "Failed", "msg" => "Invalid Email address or Password");
					$this->response($this->json($error), 400);
				}
			}

			// If invalid inputs "Bad Request" status message and reason
			$error = array('status' => "Failed", "msg" => "Invalid Email address or Password");
			$this->response($this->json($error), 400);
		}
		

                /**
                 * getSearchOrder function returns orders for the specified 'user_id'
                 * also if 'order_id' is specified then details of that order will be shown 
                 */
                private function getSearchOrder()
               {
                    /*if($this->get_request_method() != "GET"){

				$this->response('',406);
			}*/
                   $params = $_REQUEST;
                    if (!empty($_REQUEST['user_id'])) {
                            $params['user_id'] = $_REQUEST['user_id'];
                            //echo "I was Here";die();

                    } elseif (!empty($_REQUEST['order_ids'])) {
                            if (empty($params['order_id'])) {
                                    $params['order_id'] = $_REQUEST['order_ids'];
                            } else {
                                    $ord_ids = is_array($params['order_id']) ? $params['order_id'] : explode(',', $params['order_id']);
                                    $params['order_id'] = array_intersect($ord_ids, $auth['order_ids']);
                            }

                    } else {
                            $error = array('status' => "Failed", "msg" => "No Content");
                            $this->response($this->json($error), 204);
                    }

                    list($orders, $search) = fn_get_orders($params);
                    //get order details for the orders pulled for this user or the order_id mentioned
                    foreach($orders as $order_detail)
                    {
                            $order_info[]=fn_get_order_info($order_detail['order_id']);
                    }
                    $orders_item=array();
                    if(!empty($order_info))
                    {
                            foreach($order_info as $order_detail)
                            {
                                    $orders_item[]=$order_detail['items'];

                                    if($order_detail['allow_return']=='Y')
                                    {
                                            $return_order[$order_detail['order_id']]='Y';
                                    }
                                    if(isset($order_detail['order_return_status']) && $order_detail['order_return_status']=='N')
                                    {
                                            $return_order[$order_detail['order_id']]='N';
                                    }
                                    elseif(isset($order_detail['order_return_status']) && $order_detail['order_return_status']=='E')
                                    {
                                            $return_order[$order_detail['order_id']]='E';
                                    }
                            }
                    }

                    $orders['search'] = $search;
                    $orders['orders_item'] = $orders_item;
                    $orders['return_orders'] = $return_order;

                    $this->response($this->json($orders), 200);
               }
               
               /**
                * this function will accept order_id and email from the user and give 
                * order details if the order belongs to the specifed emailid
                */
               private function getCheckOrderStatus()
               {
                   if($this->get_request_method() != "GET"){

				$this->response('',406);
			}
                   $params = $this->_request;
                    if (empty($params['email_id']) || empty($params['order_id'])) {
                            $error = array('status' => "Failed", "msg" => "Non-Authoritative Information");
                            $this->response($this->json($error), 203);
                    }
                    else
                    {
                        $res=db_get_row("select order_id from cscart_orders where order_id='".$params['order_id']."' and email='".$params['email_id']."'");
                        
                        if(!empty($res))
                        {
                                $order_info = fn_get_order_info($params['order_id']);

                                $shipments = db_get_array('SELECT ?:shipments.shipment_id, ?:shipments.comments, ?:shipments.tracking_number, ?:shipping_descriptions.shipping AS shipping, ?:shipments.carrier FROM ?:shipments LEFT JOIN ?:shipment_items ON (?:shipments.shipment_id = ?:shipment_items.shipment_id) LEFT JOIN ?:shipping_descriptions ON (?:shipments.shipping_id = ?:shipping_descriptions.shipping_id) WHERE ?:shipment_items.order_id = ?i AND ?:shipping_descriptions.lang_code = ?s GROUP BY ?:shipments.shipment_id', $order_info['order_id'], DESCR_SL);

                                if (!empty($shipments)) {
                                        foreach ($shipments as $id => $shipment) {
                                                $shipments[$id]['items'] = db_get_array('SELECT item_id, amount FROM ?:shipment_items WHERE shipment_id = ?i', $shipment['shipment_id']);
                                        }
                                }
                                
                                $trackin = db_get_array("SELECT carrier_status, sc_status, from_location, to_location, status_update_date, memo, awbno, receiver_name, receiver_contact FROM clues_shipment_tracking_center WHERE order_id=".$order_info['order_id']." ORDER BY latest desc, date_of_creation desc");

                                foreach($trackin as $tr=>$track){
                                        $tracking[$track['awbno']][]=$track;
                                }
                                $orders['tracking'] =  $tracking;
                                $orders['shipments'] = $shipments;
                                $orders['order_info'] = $order_info;
                                
                                $this->response($this->json($orders), 200);
                        }
                        else
                        {
                            $error = array('status' => "Failed", "msg" => "Non-Authoritative Information");
                            $this->response($this->json($error), 203);
                        } 
                    }
               }
               
		/**
                * 
                * @param type $order_id - is the id of the order whose details need to be displayed
                */
               private function getOrderDetails()
               {
                   /*if($this->get_request_method() != "GET"){

				$this->response('',406);
			}*/
                   $order_id = $this->_request['order_id'];
                   //Get Basic Order Details
                   $order_info = fn_get_order_info($order_id);

                   //If needed get Shipping Details
                   if (isset($order_info['need_shipping']) && $order_info['need_shipping']) {
                       $shippings = db_get_array("SELECT a.shipping_id, a.min_weight, a.max_weight, a.position, a.status, b.shipping, b.delivery_time, a.usergroup_ids FROM ?:shippings as a LEFT JOIN ?:shipping_descriptions as b ON a.shipping_id = b.shipping_id AND b.lang_code = ?s WHERE a.status = ?s ORDER BY a.position", CART_LANGUAGE, 'A');
                       $order_info['shippings']= $shippings;
                   }

                   //Paid through Details              
                   $paid = db_get_row("SELECT cpo.name,cpt.name as type_name FROM cscart_orders co , clues_payment_options cpo, 
                                             clues_payment_types cpt where co.payment_option_id = cpo.payment_option_id AND 
                                             cpo.payment_type_id=cpt.payment_type_id AND co.order_id='" . $order_id . "'");

                   $order_info['paid'] = $paid;

                   //Merchant city and state
                   $merchant = db_get_row("select warehouse_city,warehouse_state from clues_warehouse_contact where 
                                                  company_id='" . $order_info['company_id'] . "'");

                   $order_info['merchant'] = $merchant;

                   // fulfillment type 
                   $fulfillment = db_get_row("select cfl.description from clues_fulfillment_lookup cfl
                                                       inner join cscart_companies  cc on cfl.fulfillment_id=cc.fulfillment_id
                                                       where cc.company_id='" . $order_info['company_id'] . "'");
                   $order_info['fulfillment'] = $fulfillment;

                   //clues bucks used 
                   $order_data = db_get_hash_single_array("select type, data from cscart_order_data where order_id='" . $order_info['order_id'] . "' 
                                                               and type in('I','U','W')", array('type', 'data'));
                   foreach ($order_data as $k => $o_data) {
                       $data = @unserialize($o_data);
                       if (is_array($data)) {
                           $order_data[$k] = @unserialize($o_data);
                       } else {
                           $order_data[$k] = $o_data;
                       }
                   }
                   $order_info['order_data'] = $order_data;

                   //Customer Remarks
                   $cust_remarks = db_get_array("select concat(cu.firstname,' ',cu.lastname) as user_name,ccq.issue_id, ci.name, ccq.date, 
                                              ccq.remarks, csd.status,csd.type,csd.description, 
                                              ccq.follow_up, ccq.customer_comments, ccq.ticket_number from clues_customer_queries ccq 
                                              left join cscart_users cu on (ccq.user_id = cu.user_id) 
                                              left join clues_issues ci on ci.issue_id = ccq.issue_id 
                                              left join cscart_status_descriptions csd on csd.status=ccq.status where  
                                              ccq.order_id='" . $order_info['order_id'] . "' and csd.type='O' order by ccq.id desc");
                   $order_info['cust_remarks'] = $cust_remarks;

                   // Refund details 
                   $refund_details = db_get_row("select cr.id, cr.pgw_refund, cr.gc_refund, cr.cb_refund, cr.total_refund, cr.other_refund, cr.grace_cb, cr.user_id, concat(cu.firstname,' ' ,cu.lastname) as user_name,                                  cr.status,from_unixtime(cr.timestamp) as refund_date,round(((cr.timestamp+(crsr.sla_hours*3600))-unix_timestamp())/3600) as sla_left,from_unixtime(cr.batch_id) as batch_time
                                                                        from     clues_refunds cr left join cscart_users cu on (cu.user_id = cr.user_id)
                                                               left join clues_refund_status_relation crsr on (crsr.from_status=cr.req_order_status)
                                                                 where cr.order_id=" . $order_id . " order by cr.id desc limit 0,1");
                          $order_info['refund_details'] = $refund_details;


                   //Status Details
                   $order_info['status_settings'] = fn_get_status_params($order_info['status']);

                   // Delete order_id from new_orders table
                   db_query("DELETE FROM ?:new_orders WHERE order_id = ?i AND user_id = ?i", $order_id, $auth['user_id']);

                   fn_add_breadcrumb(fn_get_lang_var('orders'), "orders.manage.reset_view");
                   fn_add_breadcrumb(fn_get_lang_var('search_results'), "orders.manage.last_view");

                   // Check if customer's email is changed
                   if (!empty($order_info['user_id'])) {
                       $current_email = db_get_field("SELECT email FROM ?:users WHERE user_id = ?i", $order_info['user_id']);
                       if (!empty($current_email) && $current_email != $order_info['email']) {
                           $order_info['email_changed'] = true;
                       }
                   }
                   // order history 
                   $order_history = db_get_array("SELECT user_id, order_id, from_status, to_status, transition_date, transition_id, memo FROM clues_order_history WHERE order_id= ?i", $order_id);

                   if (!empty($order_history)) {
                       $order_info['order_history'] = $order_history;
                   }

                   //RTO Details
                   $RTOFLAG = 0;
                   foreach ($order_history as $statuscheck) {
                       if ($statuscheck['to_status'] == 'S') {
                           $RTOFLAG = 1;
                       }
                   }
                   $order_info['rtoflag'] = $RTOFLAG;
                   $sql = "select id,cause,parents from clues_exception_causes_list where type='Cause'";
                   $ret = db_get_array($sql);

                   $not_del = array();
                   $not_ship = array();
                   $not_del = array();
                   $other = array();
                   foreach ($ret as $result) {
                       if ($result['parents'] == "Not Delivered") {
                           $not_del[$result['id']] = $result['cause'];
                       } else if ($result['parents'] == "Not Shipped") {
                           $not_ship[$result['id']] = $result['cause'];
                       } else if ($result['parents'] == "Not Complete") {
                           $not_comp[$result['id']] = $result['cause'];
                       } else {
                           $other[$result['id']] = $result['cause'];
                       }
                   }

                   //Return Shipping Details
                   $ret_ship = db_get_array("SELECT csi.shipment_id FROM cscart_shipments cs, cscart_shipment_items csi WHERE csi.order_id ='" . $order_info['order_id'] . "' AND cs.shipment_id = csi.shipment_id AND return_id != ''");
                   foreach ($ret_ship as $inner) {
                       $result[key($inner)] = current($inner);
                       $return_ship[] = $result['shipment_id'];
                   }

                   $order_info['return_ship'] = $return_ship;


                   //Not Delivered, Not Shipped, Not Complete etc. Parent Order Details
                   $order_info['not_del'] = $not_del;
                   $order_info['not_ship'] = $not_ship;
                   $order_info['not_comp'] = $not_comp;
                   $order_info['other'] = $other;

                   $order_info['cause_list'] = $ret;
                   $sql = "select id,cause,parents from clues_exception_causes_list where type='Action'";
                   $ret = db_get_array($sql);
                   $order_info['action_list'] = $ret;
                   $sql = "select id,cause from clues_exception_causes_list where type='Tag'";
                   $ret = db_get_array($sql);
                   $order_info['tag_list'] = $ret;

                   $ret = db_get_array("select id,title from clues_email_templates");
                   $order_info['em_temp'] = $ret;

                   /// RMA Details
                   $query = db_get_array("SELECT user_id, order_id, status_from, status_to, return_id, comment, datetime FROM clues_rma_history WHERE order_id = '" . $order_id . "'");
                   $order_info['rma_history'] = $query;

                   //show return id in rma history section
                   $return_id = db_get_field("SELECT return_id from cscart_rma_returns where order_id='".$order_id."'");
                   $order_info['return_id'] = $return_id;


                   //code to display a small box for parent and child order of this particular order 

                   $parent_id = 0;

                   if ($order_info['is_parent_order'] == 'Y' and $order_info['parent_order_id'] == 0) {
                       $parent_id = $order_info['order_id'];
                   } else if ($order_info['is_parent_order'] == 'N' and $order_info['parent_order_id'] != 0) {
                       $parent_id = $order_info['parent_order_id'];
                   }

                   if ($order_info['is_parent_order'] == 'N' and $order_info['parent_order_id'] == 0) { //this is to display a small message whether it is asplit order or not
                       $order_type = 'single';
                   } else {
                       $order_type = 'split';
                   }
                   $child_id_array = array();
                   if ($parent_id != 0) {
                       $child_order_ids = db_get_array("SELECT order_id FROM cscart_orders WHERE parent_order_id= ?i", $parent_id);


                       foreach ($child_order_ids as $child_id) {
                           if ($child_id['order_id'] != $order_info['order_id'])
                               $child_id_array[] = $child_id['order_id'];
                       }
                   }

                   $order_info['parent_id'] = $parent_id;
                   $order_info['order_type'] = $order_type;
                   $order_info['child_order_id'] = $child_id_array;


                   // Calculate customer successful order score  

                   $email_id = db_get_row("SELECT email FROM cscart_orders WHERE order_id= ?i", $order_id);
                   //email id
                   $order_info['zen_email'] = $email_id['email'];

                   /*$acceptance_efficacy_data = acceptance_efficacy($email_id['email']);

                   $successful_order_count = $acceptance_efficacy_data['total_orders'] - $acceptance_efficacy_data['orders_rejected'];

                   $success_calculate = ($successful_order_count / $acceptance_efficacy_data['total_orders']) * 100;

                   $order_info['success_percentage'] = $success_calculate;
                   $order_info['success_order_count'] = $acceptance_efficacy_data['total_orders'];
                   $order_info['total_order_count'] = $acceptance_efficacy_data['total_orders'];
                   $order_info['unsuccessful_order'] = $acceptance_efficacy_data['orders_rejected'];*/

                   $order_info['request_order_id'] = $order_id; 

                   //Get countries and states
                       $order_info['countries'] = fn_get_countries(CART_LANGUAGE, true);
                       $order_info['states'] = fn_get_all_states();

                   $this->response($this->json($order_info), 200);
                       //echo json_encode(array("result" => array(array("success"=>"true", "order_info"=>$order_info))));
                       //exit;
               }
               
               private function SignUp(){
                  

                    if($this->get_request_method() != "POST"){
                        $this->response('',406);
                    }
                    if ($res = fn_update_user(0, $this->_request, $_SESSION['auth'], !empty($this->_request['ship_to_another']), (AREA == 'A' ? !empty($this->_request['notify_customer']) : true))) {

                            list($user_id, $profile_id) = $res;
                            //print_r($user_id);die("------------");
                            //$msg = array('status' => "Successful", "msg" => "OK");
                            $resp['user_id'] = $user_id;
                             
                            // Cleanup user info stored in cart
                            if (!empty($_SESSION['cart']) && !empty($_SESSION['cart']['user_data']) && AREA != 'A') {
                                    unset($_SESSION['cart']['user_data']);
                            }

                            if (Registry::get('settings.General.user_multiple_profiles') == 'Y') {
                                    $suffix .= "?profile_id=$profile_id";
                            }

                            $this->response($this->json($resp), 200);
                    } 
                    else {
                        $msg = array('status' => "Error", "msg" => "Registration Failed. Either user already exists or invalid credentials");
                        $this->response($this->json($msg), 506);    
                    }                    
               }


		/* 
		 *  BestSellingProduct API
		 *  Login must be POST method
		 */
		
		private function bestsellers(){

			// Cross validation if the request method is POST else it will return "Not Acceptable" status
			/*if($this->get_request_method() != "POST"){

				$this->response('',406);
			}*/

			$categories = db_get_array("select * from clues_merchandise_data where type='BESTSELLING' order by position asc");

			if(!empty($categories) && count($categories)>0) {
				require  dirname(__FILE__) . '/../addons/bestsellingproduct/func.php';
				if (Registry::get('settings.General.show_products_from_subcategories') == 'Y') {
					$params['subcats'] = 'Y';
				}
				foreach($categories as $category) {
					$category_array = explode(',',$category['value']);
					$params['cid'] = $category_array;
					$params['limit'] = 20;
					$products[$category['id']] = fn_bestsellerproduct_get_products($params);
				}

				if($products){
					// If success everythig is good send header as "OK" and best selling products
					$this->response($this->json($products), 200);
				}
				$this->response('',204);	// If no records "No Content" status
			}
		}


		/* 
		 *  BestSellingProduct API
		 *  Login must be POST method
		 */
		
		private function category(){

			// Cross validation if the request method is GET else it will return "Not Acceptable" status
			if($this->get_request_method() != "GET"){

				$this->response('',406);
			}

			if(!empty($this->_request['cat_id'])){
				$cat_id = $this->_request['cat_id'];
			} else {
				$cat_id = 0;
			}

			    $result = db_get_array("SELECT cd.category_id, cd.category 
			    FROM cscart_categories cc 
			    LEFT JOIN cscart_category_descriptions cd ON cc.category_id = cd.category_id 
			    WHERE cc.status='A' 
			    AND cc.parent_id=".$cat_id);

			    if(count($result) == 0)
			    {
				$result = db_get_array("SELECT p.product_id, pd.product 
				FROM cscart_products p 
				INNER JOIN cscart_product_descriptions pd on p.product_id = pd.product_id
				INNER JOIN cscart_products_categories pc ON pc.product_id = p.product_id 
				INNER JOIN cscart_categories cc ON cc.category_id = pc.category_id 
				WHERE p.status='A' 
				AND pc.category_id=".$cat_id);
		
				if($result){
					// If success everythig is good send header as "OK" and best selling products
					$this->response($this->json($result), 200);
				}
				$this->response('',204);	// If no records "No Content" status
			    }
			    else
			    {
				if($result){
					// If success everythig is good send header as "OK" and best selling products
					$this->response($this->json($result), 200);
				}
				$this->response('',204);	// If no records "No Content" status
			    }
		}
		
		/* 
		 *	Simple search API
		 * 	Query must be in Get Method
		 *	q:  		<query string>
		 *	cid:		category id (obtained by clicking on the category filter)
		 *	fq(array):      filters the result with given price range id 
		 *	br(array):      filters the result with given brands id              
		 *	sort_by	:	price,bestsellers,hotdeals,featured....
		 *	sort_order:	asc or dsc   
		 *	Pagination:     offset and limit  
		 *
		 */
		 
		private function search(){
			if($this->get_request_method() != "GET"){
					$this->response('',406);
				}
                    
                    //create an array for response
                    include   '../addons/my_changes/func.php';
                    include   'func.php';

                    $response = array();                   

                    $search_result = get_products($this->_request);
                    $filter_result = fn_assign_filtertoitem($this->request);
	
			if(!empty($search_result)){
		            $response['filters'] = $filter_result;
		            $response['products'] = $search_result;
		            $this->response($this->json($response), 200);
			}else{
                            $error = array('status' => "Failed", "msg" => "No Content");
                            $this->response($this->json($error), 204);
			}
		
		}
		
		private function addressbook(){
			
			$user_id = $this->_request['user_id'];
			if(empty($user_id)){
				 $msg = array('status' => "Error", "msg" => "Invalid parameters");
	                	 $this->response($this->json($msg), 400);  
			
			}
				
			if($this->get_request_method() == "GET"){			
				$data = $this->getAddressBook($user_id);
				if($data)
					$this->response($this->json($data), 200);
				else{
					$error = array('status' => "Failed", "msg" => "Invalid Parameters");
                            		$this->response($this->json($error), 400);
				
				}
			}
			elseif($this->get_request_method() == "POST"){			
				$new_address = $this->createAddressBook();
				if($new_address){
					$msg = array('status' => "Successful", "msg" => $new_address);
					$this->response($this->json($msg), 200);
				}
					
				else{
				 $error = array('status' => "Failed", "msg" => "Invalid Parameters");
				 $this->response($this->json($test), 400);
				}
				
			}
						
			elseif($this->get_request_method() == "PUT"){			
				$new_address = $this->editAddressBook();
				if($new_address){
					$msg = array('status' => "Successful", "msg" => 'Profile Updated');
					$this->response($this->json($msg), 200);
				}					
				else{
				 $error = array('status' => "Failed", "msg" => "Invalid Parameters");
				 $this->response($this->json($error), 400);
				}
				
			}
			elseif($this->get_request_method() == "DELETE"){
				$deleted = $this->deleteAddressBook($this->_request['profile_id']);
				if($deleted){
					$msg = array('status' => "Successful", "msg" => 'Address Deleted');
					$this->response($this->json($msg), 200);
				}
					
				else{
				 $error = array('status' => "Failed", "msg" => "Invalid Parameters");
				 $this->response($this->json($test), 400);
				}
				
			}
			else
				$this->response('',406);					
		}
		
		private function getAddressBook($user_id){
			$result = db_get_array("SELECT profile_id , profile_type, s_firstname, s_lastname, s_address, s_address_2, s_city, 								s_county, s_state,s_country, s_zipcode, s_phone, profile_name FROM  cscart_user_profiles 					WHERE user_id=".$user_id);
			return $result;
		
		}
		
		
		
		private function deleteAddressBook($profile_id){
		
			$status = db_query("DELETE FROM ?:user_profiles WHERE profile_id = ?i", $profile_id);
			return $status;
		
		}
		
		
		private function editAddressBook(){
					
			$_data = array();
			$profile_id = $this->_request['profile_id'];
			$_data['profile_type'] = 'S';						
			$_data['user_id']    = $this->_request['user_id'];
			$_data['b_title'] = '';
			$_data['s_title'] = '';
			$_data['b_firstname'] = '';
			$_data['b_lastname'] = '';
			$_data['b_county'] = '';
			$_data['b_state'] = '';
			$_data['b_zipcode'] = '';
			$_data['b_phone'] = '';
			$_data['s_firstname'] = $this->_request['fname'];
			$_data['s_lastname'] = $this->_request['lname'];
			$_data['s_address'] = $this->_request['address'];
			$_data['s_address_2'] = $this->_request['address2'];
			$_data['b_address'] = '';
			$_data['b_address_2'] = '';
			$_data['s_city'] = $this->_request['city'];
			$_data['b_city'] = '';
			$_data['s_county'] = '';
			$_data['s_state'] = $this->_request['state'];
			$_data['s_zipcode'] = $this->_request['pincode'];
			$_data['s_phone'] = $this->_request['phone'];
			$_data['s_address_type'] = '';
			$_data['profile_name'] = $this->_request['address_name'];
			$_data['credit_cards'] = '';
			$_data['verified'] = 0;
			$status = db_query("UPDATE ?:user_profiles SET ?u WHERE profile_id = ?i", $_data, $profile_id);
			return $status;
		}
		
		
		private function createAddressBook(){
		
			$_data = array();
			$_data['profile_id'] = 0;
			$_data['profile_type'] = 'S';						
			$_data['user_id']    = $this->_request['user_id'];
			$_data['b_title'] = '';
			$_data['s_title'] = '';
			$_data['b_firstname'] = '';
			$_data['b_lastname'] = '';
			$_data['b_county'] = '';
			$_data['b_state'] = '';
			$_data['b_zipcode'] = '';
			$_data['b_phone'] = '';
			$_data['s_firstname'] = $this->_request['fname'];
			$_data['s_lastname'] = $this->_request['lname'];
			$_data['s_address'] = $this->_request['address'];
			$_data['s_address_2'] = $this->_request['address2'];
			$_data['b_address'] = '';
			$_data['b_address_2'] = '';
			$_data['s_city'] = $this->_request['city'];
			$_data['b_city'] = '';
			$_data['s_county'] = '';
			$_data['s_state'] = $this->_request['state'];
			$_data['s_zipcode'] = $this->_request['pincode'];
			$_data['s_phone'] = $this->_request['phone'];
			$_data['s_address_type'] = '';
			$_data['profile_name'] = $this->_request['address_name'];
			$_data['credit_cards'] = '';
			$_data['verified'] = 0;
			
			$new_profile = db_query("INSERT INTO ?:user_profiles ?e", $_data);
			return $new_profile;
				
		}
			

		/*
		 *	Encode array into JSON
		*/
		
		private function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}
	}

	
	// Initiiate Library
	
	$api = new API;
	$api->processApi();

?>
