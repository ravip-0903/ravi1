<?php
/******************************************************************************
*                                                                             *
*    (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev      *
*                                                                             *
******************************************************************************/

//
// $Id: class.twgapibase.php 12865 2011-07-05 06:57:22Z 2tl $
//

/*
 * Twigmo api base object
 */
class TwgApiBase
{
	protected $errors = array(); // request errors list
	protected $data = array(); // request returned data: object list or object details
	protected $meta = array(); 

	function getErrors()
	{
		$errors = array();
		
		if (empty($this->errors)) {
			return array();
		}
		
		foreach($this->errors as $k => $v) {
			$errors[$v['code']] = $v['message'];
		}
		
		return $errors;
	}
	
	function getData()
	{
		return $this->data;
	}

	function addError($code, $message)
	{
		$error = array (
			'code' => $code,
			'message' => $message
		);

		if (!empty($additional_data)) {
			$error = array_merge($error, $additional_data);
		}

		$this->errors[] = $error;

		return true;
	}
	
	function setData($data, $name = '')
	{
		if (!empty($name)) {
			$this->data[$name] = $data;
		} else {
			$this->data = array_merge($this->data, $data);
		}
	}

}

?>