<?php
class OrderStatus {
	
	private $id ="";
	private $user_id ="";
	private $order_id ="";
	private $from_status ="";
	private $to_status ="";
	private $transition_date ="";
	private $transition_id ="";
	private $memo ="";
	public function getId(){
		return $this->id;
	}
	public function getUserId(){
		return $this->user_id;
	}
	public function getOrderId(){
		return $this->order_id;
	}
	public function getFromStatus(){
		return $this->from_status;
	}
	public function getToStatus(){
		return $this->to_status;
	}
	public function getTransitionDate(){
		return $this->transition_date;
	}
	public function getTransitionId(){
		return $this->transition_id;
	}
	public function getMemo(){
		return $this->memo;
	}
	
	public function setId($obj){
		$this->id = $obj->id;
	}
	public function setUserId($obj){
		$this->user_id = $obj->user_id;
	}
	public function setOrderId($obj){
		$this->order_id = $obj->order_id;
	}
	public function setFromStatus($obj){
		$this->from_status = $obj->from_status;
	}
	public function setToStatus($obj){
		$this->to_status = $obj->to_status;
	}
	public function setTransitionDate($obj){
		$this->transition_date = $obj->transition_date;
	}
	public function setTransitionId($obj){
		$this->transition_id = $obj->transition_id;
	}
	public function setMemo($obj){
		$this->memo = $obj->memo;
	}
	public function __construct($obj,$array = ""){
	 if(is_array($array)){
			$this->setAll('',$array);
		}else{
		 $this->setAll($obj);

		}
	 return $this;
	}
	public function setAll($obj = "",$array = ""){
		$variable = @get_object_vars($obj);
		if(is_array($array)){
			$variable = $array;
		}
		if(is_array($variable)){
			foreach ($variable as $key => $value){
				$this->$key = $value;
			}
			return true;
		}else{
			return false;
		}
	}
}
?>