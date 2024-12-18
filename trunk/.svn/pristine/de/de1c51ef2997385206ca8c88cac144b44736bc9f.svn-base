<?php
define('AUCTION_VALID',1);
define('INVALID_INPUT',2);
define('NO_SUCH_AUCTION',3);
define('AUCTION_DISABLED',4);
define('OUT_OF_SCHEDULE',5);
define('BID_COUNT_EXCEED',6);
define('LT_MINIMUM_AMT',7);
define('LT_SAFE_AMOUNT',8);
define('EXCEED_MAX_BID',9);
define('UNKNOWN_ERROR',10);
define('USER_NOT_ALLOWED',11);

function place_bid($user_id,$auction_id,$amount)
{
  $ip_address = $_SERVER['REMOTE_ADDR'];
  $query= "INSERT INTO clues_auction_users (auction_id,user_id,amount,bid_date,ip_address) VALUES
           ($auction_id,$user_id,$amount,UNIX_TIMESTAMP(),'$ip_address')";
  $result  = db_query($query);
  set_auction_data_memcache($auction_id);
  return true;
}

function process_bid($user_id,$auction_id,$amount)
{
  if(!is_valid_input($user_id) OR !is_valid_input($auction_id) OR !is_valid_input($amount))
  {
    return INVALID_INPUT;
  }
  if(!is_user_allowed_bid($user_id))
  {
    return USER_NOT_ALLOWED; 
  }
  $query = "SELECT minimum_price,min_increment,max_increment,bids_per_user,status,start_date,end_date FROM clues_product_auctions WHERE 
            id=$auction_id";
  $data  = db_get_row($query);
  
  if(empty($data))
  {
    return NO_SUCH_AUCTION;
  }
  if($data['status'] == 0)
  {
    return AUCTION_DISABLED;
  }
  $current_time = time();
  if($data['start_date'] > $current_time OR $data['end_date'] < $current_time)
  {
    return OUT_OF_SCHEDULE;
  }
  $query = "SELECT count(*) as count FROM clues_auction_users WHERE user_id=$user_id AND auction_id=$auction_id";
  $no_of_bids = db_get_field($query);
  if(!empty($data['bids_per_user']) AND $data['bids_per_user'] <= $no_of_bids)
  {
    return BID_COUNT_EXCEED;
  }
  if($amount < $data['minimum_price'])
  {
    return LT_MINIMUM_AMT;
  }
  $current_max = get_current_max_amount($auction_id);
  $safe_amount  = $current_max + $data['min_increment'];
  if($amount < $safe_amount)
  {
    return LT_SAFE_AMOUNT;
  }
  if(empty($current_max))
  {
    $current_max = $data['minimum_price'];
  }
  $max_bid_amount = $current_max+$data['max_increment'];
  if(!empty($data['max_increment']) AND $amount > $max_bid_amount)
  {
    return EXCEED_MAX_BID;
  }
  if(place_bid($user_id,$auction_id,$amount))
  {
    return AUCTION_VALID;
  }
  return UNKNOWN_ERROR;
}


function get_current_max_amount($auction_id)
{
  $query = "SELECT max(amount) as max_amount FROM clues_auction_users WHERE auction_id=$auction_id";
  $data  = db_get_field($query);
  return $data;
}

function get_auction_data($auction_id)
{
  $query= "SELECT  start_date, end_date, FROM_UNIXTIME(end_date) as timer_end_time, id as auction_id,minimum_price, min_increment,max_increment FROM clues_product_auctions WHERE id=$auction_id";
  $result = db_get_row($query);
  if(empty($result))
  {
    return $result;
  }
  $result['current_time'] = time();
  $query = "SELECT count(*) as total_bid_count, max(amount) as current_max_amount FROM clues_auction_users WHERE auction_id=$auction_id group by auction_id";
  $data  = db_get_row($query);
  if(empty($data))
  {
	  $result['total_bid_count']=0;
	  $result['current_max_amount']=$result['minimum_price'];
  }
  return array_merge($data,$result);
}

function is_valid_input($param)
{
  if(empty($param) OR !is_numeric($param))
    return false;
  return true;
}

function set_auction_data_memcache($auction_id)
{
 if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
 {
   $memcache = $GLOBALS['memcache'];
   $key      = "product_auction_$auction_id";
   $auction_data = get_auction_data($auction_id);
   if(!empty($auction_data))
   {
    $status = $memcache->set($key, $auction_data, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time'));
    if(!$status){
       $memcache->delete($key);
     }
    return $auction_data;
   }
 }
 return false;
}

function get_auction_data_memcache($auction_id)
{
 if(Registry::get('config.memcache') && $GLOBALS['memcache_status'])
 {
   $memcache = $GLOBALS['memcache'];
   $key      = "product_auction_$auction_id";
   if($mem_value = $memcache->get($key)){
      $_result = $mem_value;
   }else{
      $_result = set_auction_data_memcache($auction_id);
   } 
 }else
 {
  $_result  = get_auction_data($auction_id);
  }
  return $_result;
}

function is_auction_active($auction_id)
{
  $query = "SELECT id FROM clues_product_auctions WHERE status=1 AND  start_date <= UNIX_TIMESTAMP() AND end_date > UNIX_TIMESTAMP() and id=$auction_id";
  $data = db_get_row($query);
  if(!empty($data))
  return true;
  return false;
}

function get_common_auction_values($auction_id)
{
  $error = array('error' => 0);
  $data  = array();
  if(is_auction_active($auction_id))
  {
      $data  = get_auction_data_memcache($auction_id);
      if(empty($data)){
        $error['error'] = 1;
        $error['error_message'] ='No such auction id';
      }
  }else{
    $error['error'] = 1;
    $error['error_message'] ='No active auction';
  }
     return array_merge($error,$data);
}

function is_user_allowed_bid($user_id)
{
  $query="SELECT winner_user_id FROM clues_product_auctions WHERE winner_user_id=$user_id AND is_winner_allowed=0";
  $data = db_get_field($query);
  if(empty($data))
  {
    return true;
  }
  return false;
}

function is_product_allowed_for_user($user_id,$product_id)
{	
  $query= "SELECT id FROM clues_product_auctions WHERE product_id=$product_id";
  $auction_id = db_get_field($query);
  if(!empty($auction_id))
  {
    $query="SELECT winner_user_id FROM clues_product_auctions WHERE id=$auction_id";
    $winner_id = db_get_field($query);
    if($winner_id == $user_id)
    {
      return true;
    }
    return false;
  }
return true;
}

function allow_winner_user_for_auction($user_id,$product_ids)
{
	$product_id_string=implode(",",$product_ids);
	$sql="select product_id from clues_product_auctions where product_id in (".$product_id_string.")";
	$result=db_get_fields($sql);
	if(!empty($result) && count($result)==1)
	{
		$sql="update clues_product_auctions set is_winner_allowed = 1 where product_id=".$result[0]." and winner_user_id=$user_id";
		if(db_query($sql))
		{
			return true;
		}
	}
	return false;
}

function get_upcoming_auctions()
{
	$sql="select pa.product_id ,pd.product, date_format(from_unixtime(pa.start_date),'%D %M') as start_date,sn.name as seo_name,
				CONCAT('images/detailed/',floor(if(i.image_id!=0, i.image_id,il.image_id)/1000),'/',REPLACE(REPLACE(image_path, ' ', ''), ' ', '')) as detailed_image_url 
				from clues_product_auctions pa
				inner join cscart_products p on p.product_id=pa.product_id
				inner join cscart_product_descriptions pd on pd.product_id=p.product_id 
				LEFT JOIN cscart_images_links il ON il.object_id = pa.product_id and il.object_type = 'product' and il.type='M'
				LEFT JOIN cscart_images i ON il.detailed_id = i.image_id
				inner join cscart_seo_names sn on sn.object_id=pa.product_id and sn.type='P' 
				where pa.start_date > ".time()." limit 4";
	$result=db_get_array($sql);
	if(!empty($result))
	{
		//echo "<pre>";var_dump($result);die;
		return $result;
	}
	else
	{
		return false;
	}
	
}
