<?php
/******************************************************************************
*                                                                             *
*    (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev      *
*                                                                             *
******************************************************************************/

//
// $Id: class.twgapiv2.php 12865 2011-07-05 06:57:22Z 2tl $
//

/*
 * Twigmo Api v 2.0 
 * includes separate section for meta data
 * and errors
 */
class TwgApiv2 extends TwgApiBase
{
	const STATUS_OK = 'OK';
	const STATUS_ERROR = 'ERROR';

	const VERSION = '2.0';

	function __construct()
	{
		$this->meta = array (
			'version' => self::VERSION
		);
	}

	function setMeta($value, $name)
	{
		$this->meta[$name] = $value;
	}

	function getMeta($name = '')
	{
		if (empty($name)) {
			return $this->meta;
		}
		return !empty($this->meta[$name]) ? $this->meta[$name] : '';
	}
	
	function getResponseData()
	{
		$result = array (
			'meta' => $this->meta
		);
		
		if (!empty($this->errors)) {
			$result['meta']['status'] = self::STATUS_ERROR;
			$result['meta']['errors'] = $this->errors;			
		} else {
			$result['meta']['status'] = self::STATUS_OK;
		}
		
		if (!empty($this->data)) {
			$result['data'] = $this->data;
		}

		return $result;
	}

	function parseResponse($doc, $format = TWG_DEFAULT_DATA_FORMAT)
	{
		$data = ApiData::parseDocument($doc, $format);

		if (empty($data)) {
			return false;
		}

		if (empty($data['meta'])) {
			return false;
		}

		$this->meta = $data['meta'];

		if (!empty($data['meta']['errors'])) {
			$this->errors = ApiData::getObjects($data['meta']['errors']);
		}

		if (!empty($data['data'])) {
			$this->data = $data['data'];
		}
		
		return true;
	}

	function setResponseList($list)
	{
		if (!empty($list)) {
			$this->setData(current($list));
		}
	}

}

?>