<?php
/***************************************************************************
*                                                                          *
*   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
*                                                                          *
* This  is  commercial  software,  only  users  who have purchased a valid *
* license  and  accept  to the terms of the  License Agreement can install *
* and use this program.                                                    *
*                                                                          *
****************************************************************************
* PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
* "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
****************************************************************************/


//
// $Id: func.php 12865 2011-07-05 06:57:22Z 2tl $
//

if ( !defined('AREA') ) { die('Access denied'); }

function fn_get_discussion($object_id, $object_type)
{
	static $cache = array();
	static $customer_companies = null;
	
	if (empty($cache["{$object_id}_{$object_type}"])) {
		
		//change by ankur if the result is empty then create the record
		$result=db_get_row("SELECT thread_id, type, object_type FROM ?:discussion WHERE object_id = ?i AND object_type = ?s", $object_id, $object_type);
		if(!empty($result))
		{
			$cache["{$object_id}_{$object_type}"] =$result; 
		}
		else if($object_type=='P') //this is because this addon also called on order detail page on front end
		{
			db_query("insert into ?:discussion set object_id='".$object_id."',object_type='".$object_type."',type='B'");
			$result=db_get_row("SELECT thread_id, type, object_type FROM ?:discussion WHERE object_id = ?i AND object_type = ?s", $object_id, $object_type);
			$cache["{$object_id}_{$object_type}"] =$result;
		}
        //code end
		if (empty($cache["{$object_id}_{$object_type}"]) && $object_type == 'M') {
			$company_discussion_type = Registry::if_get('addons.discussion.company_discussion_type', 'D');
			if ($company_discussion_type != 'D') {
				$cache["{$object_id}_{$object_type}"] = array('object_type' => 'M', 'object_id' => $object_id, 'type' => $company_discussion_type);
				$cache["{$object_id}_{$object_type}"]['thread_id'] = db_query('INSERT INTO ?:discussion ?e', $cache["{$object_id}_{$object_type}"]);
			}
		}
		
		if (!empty($cache["{$object_id}_{$object_type}"]) && AREA == 'C' && $object_type == 'M' && Registry::if_get('addons.discussion.company_only_buyers', 'Y') == 'Y') {
			if (empty($_SESSION['auth']['user_id'])) {
				$cache["{$object_id}_{$object_type}"]['disable_adding'] = true;
			} else {
				if ($customer_companies === null) {
					$customer_companies = db_get_hash_single_array('SELECT company_id FROM ?:orders WHERE user_id = ?i', array('company_id', 'company_id'), $_SESSION['auth']['user_id']);
				}
				if (empty($customer_companies[$object_id])) {
					$cache["{$object_id}_{$object_type}"]['disable_adding'] = true;
				}
			}
		} 
		
		fn_set_hook('get_discussion', $object_id, $object_type, $cache["{$object_id}_{$object_type}"]);
	}
	
	if (!empty($_SESSION['saved_post_data']) && !empty($_SESSION['saved_post_data']['post_data'])) {
		$cache["{$object_id}_{$object_type}"]['post_data'] = $_SESSION['saved_post_data']['post_data'];
		unset($_SESSION['saved_post_data']['post_data']);
	}

	return !empty($cache["{$object_id}_{$object_type}"]) ? $cache["{$object_id}_{$object_type}"] : false;
}

function fn_get_discussion_posts($thread_id = 0, $page = 0, $first_limit = '', $random = 'N',$filter=0)
{ 
	
	$sets = Registry::get('addons.discussion');
 $discussion_object_types = fn_get_discussion_objects();

 if (empty($thread_id)) {
  return false;
 }
 
 $thread_data = db_get_row("SELECT type, object_type FROM ?:discussion WHERE thread_id = ?i", $thread_id);
 
 
 if ($thread_data['type'] == 'D') {
  return false;
 }
 $join = $fields = '';

 if ($thread_data['type'] == 'C' || $thread_data['type'] == 'B') {
  $join .= " LEFT JOIN ?:discussion_messages ON ?:discussion_messages.post_id = ?:discussion_posts.post_id ";
  $fields .= ", ?:discussion_messages.message";
 }

 if ($thread_data['type'] == 'R' || $thread_data['type'] == 'B') {
  $join .= " LEFT JOIN ?:discussion_rating ON ?:discussion_rating.post_id = ?:discussion_posts.post_id ";
  $fields .= ", ?:discussion_rating.rating_value";
 }
 
 if($thread_data['object_type']=='O')
 {
   $status_cond = (AREA == 'A') ? '' : " AND ?:discussion_posts.status = 'A'";
	$total_pages = db_get_field("SELECT COUNT(*) FROM ?:discussion_posts WHERE thread_id = ?i $status_cond", $thread_id);

	if ($first_limit != '') {
		$limit = "LIMIT $first_limit";
	} else {
		$limit = fn_paginate($page, $total_pages, $sets[$discussion_object_types[$thread_data['object_type']] . '_posts_per_page']);
	}

	$order_by = $random == 'N' ? '?:discussion_posts.timestamp DESC' : 'RAND()';
 	
	return db_get_array("SELECT ?:discussion_posts.* $fields FROM ?:discussion_posts $join WHERE ?:discussion_posts.thread_id = ?i $status_cond ORDER BY ?p $limit", $thread_id, $order_by);
 }
 else
 {
	 if(CONTROLLER == 'companies')
	 {
	  $ret2 = db_get_row("select * from ?:discussion where thread_id='".$thread_id."' and object_type='M' and type='C'");
	 }
	 else 
	 {
	  $ret2 = db_get_row("select * from ?:discussion where thread_id='".$thread_id."' and object_type='P' and type='B'");
	 }
	 
	 $status_cond = (AREA == 'A') ? '' : " AND ?:discussion_posts.status = 'A'";
	 
	 if(!empty($filter) && CONTROLLER != 'companies'){
             
             $total_pages_cnt1 = db_get_field("select count(cdp.post_id) as count FROM cscart_discussion_posts cdp,cscart_discussion_rating csr WHERE cdp.thread_id = $thread_id and cdp.status = 'A' and cdp.post_id =csr.post_id and csr.rating_value=".$filter);
             
         }else{
             
	 $total_pages_cnt1 = db_get_field("select count(*) count FROM cscart_discussion_posts WHERE thread_id = $thread_id and cscart_discussion_posts.status = 'A'");
         }
         
	 if(AREA !='A')
	{
		if(CONTROLLER != 'companies')
		{
                        $total_pages_cnt2 = "select count(*) count from clues_user_product_rating where product_id = '".$ret2['object_id']."' and for_product_status='A'";
                        
                        if(!empty($filter)){
                            
                            $total_pages_cnt2 .= "and product_rating = ".$filter;
                        }
                        
			$total_pages_cnt2 = db_get_field($total_pages_cnt2);
                        
		}
		else if(CONTROLLER == 'companies')
		{       
                        $total_pages_cnt2 = "select count(*) count from clues_user_product_rating where company_id = '".$ret2['object_id']."' and avg_rate>0 and for_merchant_status='A' and review_merchant!=''";
                        if(!empty($filter)){
                           
                            $total_pages_cnt2 .= "and round(avg_rate)=".$filter;
                        }
                        
			$total_pages_cnt2 = db_get_field($total_pages_cnt2);
		}
	}
	else
	{

		if(CONTROLLER != 'companies')
		{
			$total_pages_cnt2 = db_get_field("select count(*) count from clues_user_product_rating where product_id = '".$ret2['object_id']."'");
                        
                        if(!empty($filter)){
                            
                            $total_pages_cnt2 .= " and product_rating=".$filter;
                        }
		}
		else if(CONTROLLER == 'companies')
		{
			$total_pages_cnt2 = db_get_field("select count(*) count from clues_user_product_rating where company_id = '".$ret2['object_id']."'");
                        
                        if(!empty($filter)){
                            
                            $total_pages_cnt2 .= " and round(avg_rate)=".$filter;
                        }
		}
	}
        
        if(CONTROLLER =='companies' && !empty($filter)){
            
            $total_pages = $total_pages_cnt2;
            
        }else if(CONTROLLER=='companies' && empty($filter)){
            
        
            $total_pages = $total_pages_cnt1+$total_pages_cnt2;
         
        }else{
            
            $total_pages = $total_pages_cnt1+$total_pages_cnt2;
        }
	 if ($first_limit != '') {
	  $limit = "LIMIT $first_limit";
	 } else {
	  $limit = fn_paginate($page, $total_pages, $sets[$discussion_object_types[$thread_data['object_type']] . '_posts_per_page']);
	 }
	
	 $order_by = $random == 'N' ? '?:discussion_posts.timestamp DESC' : 'RAND()';
		
		if(!empty($ret2) && CONTROLLER != 'companies')
		{
			if(AREA !='A')
			{
				$status_cond1="and status='A'";
                                $status_cond2="and for_product_status='A'";
			}
			else
			{
				$status_cond1="";
				$status_cond2="";
			}     
                        $sql1 = "SELECT ?:discussion_posts.* $fields FROM ?:discussion_posts $join WHERE ?:discussion_posts.thread_id = '".$thread_id."' $status_cond1";
                        
                          if(!empty($filter)){
                                    
                                    $sql1 .= " and cscart_discussion_rating.rating_value=".$filter;
                                }                      
						
			/*$sql = "select 
						cupr.id as post_id,
						'0' as thread_id,
						u.firstname as name,
						UNIX_TIMESTAMP(cupr.creation_date) as timestamp,
						u.user_id as user_id,
						'' as ip_address,
						'A' as status,
						concat(cupr.review_title ,'hprconcatsc',cupr.review,'hprconcatsc',cupr.video_url,'hprconcatsc',cupr.file) as message, 
						cupr.product_rating as rating_value 
						from clues_user_product_rating cupr,?:orders o,?:users u where product_id='".$ret2['object_id']."' and cupr.order_id = o.order_id and o.user_id = u.user_id";*/
			
			$sql = "select 
						cupr.id as post_id,
						'0' as thread_id,
						b_firstname as name,
						UNIX_TIMESTAMP(cupr.creation_date) as timestamp,
						o.email as user_id,
						'' as ip_address,
						cupr.for_product_status as status,
						concat(cupr.review_title ,'hprconcatsc',cupr.review,'hprconcatsc',cupr.video_url,'hprconcatsc',cupr.file) as message, 
						cupr.product_rating as rating_value 
						from clues_user_product_rating cupr,?:orders o where product_id='".$ret2['object_id']."' and cupr.order_id = o.order_id $status_cond2";
			     
                        
                              if(!empty($filter)){
                                 
                                  $sql .= " and cupr.product_rating =".$filter;
                              
                                  
                              }
                              
   
				$ret3 = db_get_array("select * from (" . $sql1 ." UNION ". $sql . ") tbl " . " ORDER BY timestamp DESC ". $limit);
				
			    return $ret3;
		}
		else
		{
			if(AREA !='A')
			{
				$status_cond1="and status='A'";
				$status_cond2="and for_merchant_status='A'";
				$review_cond="and review_merchant!=''";
			}
			else
			{
				$status_cond1="";
				$status_cond2="";
				$review_cond="";
			}
			$sql1 = "SELECT ?:discussion_posts.* $fields FROM ?:discussion_posts $join WHERE ?:discussion_posts.thread_id = '".$thread_id."' $status_cond1";
			
	 
	  $sql = "select 
		 cupr.id as post_id,
		 '0' as thread_id,
		 b_firstname as name,
		 UNIX_TIMESTAMP(cupr.creation_date) as timestamp,
		 o.email as user_id,
		 '' as ip_address,
		 cupr.for_merchant_status as status,
		 cupr.review_merchant as message
		 from clues_user_product_rating cupr,cscart_orders o where cupr.company_id='".$ret2['object_id']."' and cupr.order_id = o.order_id and cupr.avg_rate>0 $status_cond2 $review_cond";
          
                       
                           if(!empty($filter)) {
                               
                                  $sql .= "and round(cupr.avg_rate)=".$filter;
                                  
                              
                              }
        
                              
         if(!empty($filter)){
             $ret3 = db_get_array("select * from (" .$sql . ") tbl " . " ORDER BY timestamp DESC ". $limit);
         }   else {                  
	  $ret3 = db_get_array("select * from (" . $sql1 ." UNION ". $sql . ") tbl " . " ORDER BY timestamp DESC ". $limit);
         }
	  return $ret3;
		}
   }
}

function fn_delete_discussion($object_id, $object_type)
{
	$thread_id = db_get_field("SELECT thread_id FROM ?:discussion WHERE object_id IN (?n) AND object_type = ?s", $object_id, $object_type);

	if (!empty($thread_id)) {
		db_query("DELETE FROM ?:discussion_messages WHERE thread_id = ?i", $thread_id);
		db_query("DELETE FROM ?:discussion_posts WHERE thread_id = ?i", $thread_id);
		db_query("DELETE FROM ?:discussion_rating WHERE thread_id = ?i", $thread_id);
		db_query("DELETE FROM ?:discussion WHERE thread_id = ?i", $thread_id);
	}
}

function fn_discussion_update_product($product_data, $product_id)
{
	if (empty($product_data['discussion_type'])) {
		return false;
	}

	$discussion = array(
		'object_type' => 'P',
		'object_id' => $product_id,
		'type' => $product_data['discussion_type']
	);

	fn_update_discussion($discussion);
}

function fn_discussion_delete_product($product_id)
{
	return fn_delete_discussion($product_id, 'P');
}

function fn_discussion_update_category($category_data, $category_id)
{
	if (empty($category_data['discussion_type'])) {
		return false;
	}

	$discussion = array(
		'object_type' => 'C',
		'object_id' => $category_id,
		'type' => $category_data['discussion_type']
	);

	fn_update_discussion($discussion);
}

function fn_discussion_delete_category($category_id)
{
	return fn_delete_discussion($category_id, 'C');
}

function fn_discussion_delete_order($order_id)
{
	return fn_delete_discussion($order_id, 'O');
}

function fn_discussion_update_page($page_data, $page_id)
{
	if (empty($page_data['discussion_type'])) {
		return false;
	}

	$discussion = array(
		'object_type' => 'A',
		'object_id' => $page_id,
		'type' => $page_data['discussion_type']
	);

	fn_update_discussion($discussion);
}

function fn_discussion_delete_page($page_id)
{
	return fn_delete_discussion($page_id, 'A');
}

function fn_discussion_update_news($news_data, $news_id)
{
	if (empty($news_data['discussion_type'])) {
		return false;
	}

	$discussion = array(
		'object_type' => 'N',
		'object_id' => $news_id,
		'type' => $news_data['discussion_type']
	);

	fn_update_discussion($discussion);
}

// FIX-EVENTS
function fn_discussion_delete_news($news_id)
{
	return fn_delete_discussion($news_id, 'N');
}

function fn_discussion_update_event($event_data, $event_id)
{
	if (empty($event_data['discussion_type'])) {
		return false;
	}

	$discussion = array(
		'object_type' => 'G',
		'object_id' => $event_id,
		'type' => $event_data['discussion_type']
	);

	fn_update_discussion($discussion);
}

// FIX-EVENTS
function fn_discussion_delete_event($event_id)
{
	return fn_delete_discussion($event_id, 'G');
}

//
// Get average rating
//
function fn_get_discussion_rating($rating_value)
{
	 $cache = array();
	if (!isset($cache[$rating_value])) {
		$cache[$rating_value] = array();
		$cache[$rating_value]['full'] = floor($rating_value);
		$cache[$rating_value]['part'] = $rating_value - $cache[$rating_value]['full'];
		$cache[$rating_value]['empty'] = 5 - $cache[$rating_value]['full'] - (($cache[$rating_value]['part'] == 0) ? 0 : 1);
     
		if (!empty($cache[$rating_value]['part'])) {
			if ($cache[$rating_value]['part'] <= 0.25) {
				$cache[$rating_value]['part'] = 1;
			} elseif ($cache[$rating_value]['part'] <= 0.5) {
				$cache[$rating_value]['part'] = 2;
			} elseif ($cache[$rating_value]['part'] <= 0.75) {
				$cache[$rating_value]['part'] = 3;
			} elseif ($cache[$rating_value]['part'] <= 0.99) {
				$cache[$rating_value]['part'] = 4;
			}
		}
	}
	return $cache[$rating_value];
}

//
// Get thread average rating
//
function fn_get_average_rating($object_id, $object_type)
{
	$discussion = fn_get_discussion($object_id, $object_type);

	if ((empty($discussion) || ($discussion['type'] != 'R' && $discussion['type'] != 'B')) && $object_type != "P" && $object_type != "M") {
		return false;
	}
	if($object_type=="P")
	{
		
		
				$rat1 = db_get_row("SELECT sum(a.rating_value) as sum,count(*) as count FROM ?:discussion_rating as a LEFT JOIN ?:discussion_posts as b ON a.post_id = b.post_id WHERE a.thread_id = ?i and b.status = 'A'", $discussion['thread_id']);
		
		 
		 
		
				$rat2 = db_get_row("select sum(product_rating) as sum,count(*) as count from clues_user_product_rating where product_id='".$object_id."'");
		 
		
		$total_rate_count = $rat1['count'] + $rat2['count'];
		$total_rate_sum = $rat1['sum'] + $rat2['sum'];
		if($total_rate_count != '0')
		{
			$rat = $total_rate_sum / $total_rate_count;
			return $rat;
		}else{
			return 0;
		}
	
		
	}
	else if($object_type == "M")
	{
		
		$rat = db_get_field("select AVG(avg_rate) from clues_user_product_rating where company_id='".$object_id."' and avg_rate>0");
		 
		return $rat;
	}
	else
	{
		
		$rat = db_get_field("SELECT AVG(a.rating_value) as val FROM ?:discussion_rating as a LEFT JOIN ?:discussion_posts as b ON a.post_id = b.post_id WHERE a.thread_id = ?i and b.status = 'A'", $discussion['thread_id']);
		 
		return $rat;
	}
	
}
function fn_get_average_rating_cnt($object_id, $object_type)
{
	$discussion = fn_get_discussion($object_id, $object_type);
	if($object_type=="P"){
		//$rat = db_get_field("select count(id) from clues_user_product_rating where product_id='".$object_id."'");
		//return $rat;
		
		
				$rat1 = db_get_row("SELECT sum(a.rating_value) as sum,count(*) as count FROM ?:discussion_rating as a LEFT JOIN ?:discussion_posts as b ON a.post_id = b.post_id WHERE a.thread_id = ?i and b.status = 'A'", $discussion['thread_id']);
		 
		
		
		
				$rat2 = db_get_row("select sum(product_rating) as sum,count(*) as count from clues_user_product_rating where product_id='".$object_id."'");
		 
		
		$total_rate_count = $rat1['count'] + $rat2['count'];
		return $total_rate_count;
	}
	else if($object_type=="M")
	{
		$rat = db_get_field("select count(id) from clues_user_product_rating where company_id='".$object_id."' and avg_rate>0");
		return $rat;
	}
}

function fn_get_discussion_object_data($object_id, $object_type, $lang_code = CART_LANGUAGE)
{
	$data = array();

	// product
	if ($object_type == 'P') {
		$data['description'] = db_get_field("SELECT product FROM ?:product_descriptions WHERE product_id = ?i AND lang_code = ?s", $object_id, $lang_code);
		if (AREA == 'A') {
			$data['url'] = "products.update?product_id=$object_id&selected_section=discussion";
		} else {
			$data['url'] = "products.view?product_id=$object_id";
		}
	} elseif ($object_type == 'C') { // category
		$data['description'] = db_get_field("SELECT category FROM ?:category_descriptions WHERE category_id = ?i AND lang_code = ?s", $object_id, $lang_code);
		if (AREA == 'A') {
			$data['url'] = "categories.update?category_id=$object_id&selected_section=discussion";
		} else {
			$data['url'] = "categories.view?category_id=$object_id";
		}

	} elseif ($object_type == 'M') { // company
		$data['description'] = fn_get_company_name($object_id);
		if (AREA == 'A') {
			$data['url'] = "companies.update?company_id=$object_id&selected_section=discussion";
		} else {
			$data['url'] = "companies.view?company_id=$object_id";
		}

	// order
	} elseif ($object_type == 'O') {
		$data['description'] = '#'.$object_id;
		if (AREA == 'A') {
			$data['url'] = "orders.details?order_id=$object_id&selected_section=discussion";
		} else {
			$data['url'] = "orders.details?order_id=$object_id";
		}

	// page
	} elseif ($object_type == 'A') {
		$data['description'] = db_get_field("SELECT page FROM ?:page_descriptions WHERE page_id = ?i AND lang_code = ?s", $object_id, $lang_code);

		if (AREA == 'A') {
			$data['url'] = "pages.update?page_id=$object_id&selected_section=discussion";
		} else {
			$data['url'] = "pages.view?page_id=$object_id";
		}

	// Site layout/testimonials
	} elseif ($object_type == 'E') {
		$data['description'] = fn_get_lang_var('discussion_title_home_page');
		if (AREA == 'A') {
			$data['url'] = "discussion.update?discussion_type=E";
		} else {
			$data['url'] = '';
		}
	}

	fn_set_hook('get_discussion_object_data', $data, $object_id, $object_type);

	return $data;
}

function fn_get_discussion_objects()
{
	static $discussion_object_types = array(
		'P' => 'product',
		'C' => 'category',
		'A' => 'page',
		'O' => 'order',
		'E' => 'home_page',
		'M' => 'company',
	);

	fn_set_hook('get_discussion_objects', $discussion_object_types);

	return $discussion_object_types;

}

//
// Clone discussion
//
function fn_clone_discussion($object_id, $new_object_id, $object_type)
{

	// Clone attachment
	$data = db_get_row("SELECT * FROM ?:discussion WHERE object_id = ?i AND object_type = ?s", $object_id, $object_type);

	if (empty($data)) {
		return false;
	}

	$old_thread_id = $data['thread_id'];
	$data['object_id'] = $new_object_id;
	unset($data['thread_id']);
	$thread_id = db_query("REPLACE INTO ?:discussion ?e", $data);

	// Clone posts
	$data = db_get_array("SELECT * FROM ?:discussion_posts WHERE thread_id = ?i", $old_thread_id);
	foreach ($data as $v) {
		$old_post_id = $v['post_id'];
		$v['thread_id'] = $thread_id;
		unset($v['post_id']);
		$post_id = db_query("INSERT INTO ?:discussion_posts ?e", $v);

		$message = db_get_row("SELECT * FROM ?:discussion_messages WHERE post_id = ?i", $old_post_id);
		$message['post_id'] = $post_id;
		$message['thread_id'] = $thread_id;
		$message['message'] = $message['message'];
		db_query("INSERT INTO ?:discussion_messages ?e", $message);

		$rating = db_get_row("SELECT * FROM ?:discussion_rating WHERE post_id = ?i", $old_post_id);
		$rating['post_id'] = $post_id;
		$rating['thread_id'] = $thread_id;
		db_query("INSERT INTO ?:discussion_rating ?e", $rating);
	}

	return true;
}

function fn_discussion_clone_product($product_id, $to_product_id)
{
	fn_clone_discussion($product_id, $to_product_id, 'P');
}


function fn_get_rating_list($object_type, $parent_object_id = '')
{

	$object2parent_links = array(
		'P' => array(	//	for product
			'table' => '?:categories',
			'field' => 'category_id',
			'join' => array('?:products_categories' => "?:discussion.object_id=?:products_categories.product_id AND ?:products_categories.link_type='M'",
							'?:categories' => "?:products_categories.category_id=?:categories.category_id"),
		)/*,
		'A' => array(	// for page
			'table' => '?:topics',
			'field' => 'topic_id',
			'join' => array('?:pages_topics' => "?:discussion.object_id=?:pages_topics.page_id AND ?:pages_topics.link_type='M'",
			'?:topics' => "?:pages_topics.topic_id=?:topics.topic_id"),
		)*/
	);

	$query = db_quote(" object_type = ?s AND ?:discussion.type IN ('R', 'B') AND !(?:discussion_rating.rating_value IS NULL) ", $object_type);
	$join = array();
	if (isset($object2parent_links[$object_type]) && !empty($parent_object_id)) {
		$path = db_get_field("SELECT id_path FROM {$object2parent_links[$object_type]['table']} WHERE {$object2parent_links[$object_type]['field']} = ?i", $parent_object_id);
		$parent_object_ids = db_get_fields("SELECT {$object2parent_links[$object_type]['field']} FROM {$object2parent_links[$object_type]['table']} WHERE id_path LIKE ?l", "$path/%");
		$parent_object_ids[] = $parent_object_id;
		$query .= " AND {$object2parent_links[$object_type]['table']}.{$object2parent_links[$object_type]['field']} IN ('" . implode("','", $parent_object_ids) . "') AND {$object2parent_links[$object_type]['table']}.status='A'";
		$join = $object2parent_links[$object_type]['join'];
	}

	if ($object_type == 'P') {
		// Adding condition for the "Show out of stock products" setting
		if (Registry::get('settings.General.inventory_tracking') == 'Y' && Registry::get('settings.General.show_out_of_stock_products') == 'N' && AREA == 'C') {
			$join["?:product_options_inventory AS inventory"] =  "inventory.product_id=?:discussion.object_id";
			$join['?:products'] = "?:products.product_id=?:discussion.object_id";
			$query .= " AND IF(?:products.tracking='O', inventory.amount>0, ?:products.amount>0)";
		}
	}

	$join_conditions = '';
	foreach ($join as $table => $j_cond) {
		$join_conditions .= " LEFT JOIN $table ON $j_cond ";
	}

	return db_get_hash_array("SELECT object_id, avg(rating_value) AS rating FROM ?:discussion LEFT JOIN ?:discussion_rating ON ?:discussion.thread_id=?:discussion_rating.thread_id $join_conditions WHERE $query GROUP BY ?:discussion.thread_id ORDER BY rating DESC", 'object_id');
}

function fn_is_accessible_discussion($data, &$auth)
{
	$access = false;

	if ($data['object_type'] == 'P') {//product
		$access = fn_get_product_data($data['object_id'], $auth, CART_LANGUAGE, $field_list = 'product_id', false, false, false);

	} elseif ($data['object_type'] == 'C') {//category
		$access = fn_get_category_data($data['object_id'], '', $field_list = 'category_id', false);

	} elseif ($data['object_type'] == 'M') {//company
		$access = fn_get_company_data($data['object_id'], CART_LANGUAGE, false);

	} elseif ($data['object_type'] == 'O') {//order
		if (!empty($auth['user_id'])) {
			$access = db_get_field("SELECT order_id FROM ?:orders WHERE order_id = ?i AND user_id = ?i", $data['object_id'], $auth['user_id']);
		} elseif (!empty($auth['order_ids'])) {
			$access = in_array($data['object_id'], $auth['order_ids']);
		}

	} elseif ($data['object_type'] == 'A') {// page
		$access = fn_get_page_data($data['object_id'], CART_LANGUAGE);

	} elseif ($data['object_type'] == 'E') {// testimonials
		$access = true;
	}

	fn_set_hook('is_accessible_discussion', $data, $auth, $access);

	return !empty($access);
}

function fn_discussion_get_product_data($product_id, &$field_list, &$join)
{
	$field_list .= ", ?:discussion.type as discussion_type";
	$join .= " LEFT JOIN ?:discussion ON ?:discussion.object_id = ?:products.product_id AND ?:discussion.object_type = 'P'";

	return true;
}

function fn_update_discussion($params)
{
	$_data = fn_check_table_fields($params, 'discussion');
	$discussion = fn_get_discussion($params['object_id'], $params['object_type']);

	if (!empty($discussion['thread_id'])) {
		db_query("UPDATE ?:discussion SET ?u WHERE thread_id = ?i", $_data, $discussion['thread_id']);
	} else {
		db_query("REPLACE INTO ?:discussion ?e", $_data);
	}

	return true;
}

function fn_discussion_get_products(&$params, &$fields, &$sortings, &$condition, &$join, &$sorting, &$limit)
{
	if (!empty($params['rating'])) {
		$fields[] = 'avg(?:discussion_rating.rating_value) AS rating';
		$join .= db_quote(" INNER JOIN ?:discussion ON ?:discussion.object_id = products.product_id AND ?:discussion.object_type = 'P'");
		$join .= db_quote(" INNER JOIN ?:discussion_rating ON ?:discussion.thread_id=?:discussion_rating.thread_id");
		$join .= db_quote(" INNER JOIN ?:discussion_posts ON ?:discussion_posts.post_id=?:discussion_rating.post_id AND ?:discussion_posts.status = 'A'");

		$params['sort_by'] = 'rating';
		$params['sort_order'] = 'desc';
		$sortings['rating'] = 'rating';
	}

	return true;
}

function fn_discussion_get_categories(&$params, &$join, &$condition, &$fields, &$group_by, &$sortings)
{
	if (!empty($params['rating'])) {
		$fields[] = 'avg(?:discussion_rating.rating_value) AS rating';
		$join .= db_quote(" INNER JOIN ?:discussion ON ?:discussion.object_id = ?:categories.category_id AND ?:discussion.object_type = 'C'");
		$join .= db_quote(" INNER JOIN ?:discussion_rating ON ?:discussion.thread_id=?:discussion_rating.thread_id");
		$join .= db_quote(" INNER JOIN ?:discussion_posts ON ?:discussion_posts.post_id=?:discussion_rating.post_id AND ?:discussion_posts.status = 'A'");
		$group_by = 'GROUP BY ?:discussion_rating.thread_id';
		$sortings['rating'] = 'rating';
		$params['sort_by'] = 'rating';
		$params['sort_order'] = 'asc';
	}

	return true;
}

function fn_discussion_get_pages(&$params, &$join, &$conditions, &$fields, &$group_by, &$sortings)
{
	if (!empty($params['rating'])) {
		$fields[] = 'avg(?:discussion_rating.rating_value) AS rating';
		$join .= db_quote(" INNER JOIN ?:discussion ON ?:discussion.object_id = ?:pages.page_id AND ?:discussion.object_type = 'A'");
		$join .= db_quote(" INNER JOIN ?:discussion_rating ON ?:discussion.thread_id=?:discussion_rating.thread_id");
		$join .= db_quote(" INNER JOIN ?:discussion_posts ON ?:discussion_posts.post_id=?:discussion_rating.post_id AND ?:discussion_posts.status = 'A'");
		$group_by = '?:discussion_rating.thread_id';
		$sortings['rating'] = 'rating';
		$params['sort_by'] = 'rating';
		$params['sort_order'] = 'desc';
	}

	return true;
}

function fn_discussion_get_companies(&$params, &$fields, &$sortings, &$condition, &$join, &$auth, &$lang_code, &$group_by)
{
	if (!empty($params['sort_by']) && $params['sort_by'] == 'rating') {
		$fields[] = 'avg(?:discussion_rating.rating_value) AS rating';
		$fields[] = "CONCAT(?:companies.company_id, '_', IF (?:discussion_rating.thread_id, ?:discussion_rating.thread_id, '0')) AS company_thread_ids";
		$join .= db_quote(" LEFT JOIN ?:discussion ON ?:discussion.object_id = ?:companies.company_id AND ?:discussion.object_type = 'M'");
		$join .= db_quote(" LEFT JOIN ?:discussion_rating ON ?:discussion.thread_id=?:discussion_rating.thread_id");
		$join .= db_quote(" LEFT JOIN ?:discussion_posts ON ?:discussion_posts.post_id=?:discussion_rating.post_id AND ?:discussion_posts.status = 'A'");
		$group_by = 'GROUP BY company_thread_ids';
		$sortings['rating'] = 'rating';
	}
}

function fn_discussion_companies_sorting(&$sorting)
{
	if (in_array(Registry::get('addons.discussion.company_discussion_type'), array('B', 'R'))) {
		$sorting['rating'] = array('description' => fn_get_lang_var('rating'), 'default_order' => 'desc');
	}
}	
	
function fn_discussion_delete_company($company_id)
{
	return fn_delete_discussion($company_id, 'M');
}

function fn_get_discussion_count($object_id, $object_type)
{
	
			$result = db_get_row("SELECT thread_id FROM ?:discussion WHERE object_id = ?i AND object_type = ?s", $object_id, $object_type);
	 
	
	
			$discussion_count = db_get_row("SELECT count(*) as count FROM cscart_discussion_posts WHERE thread_id = '".$result['thread_id']."' and cscart_discussion_posts.status = 'A' ");
	
		
	$cnt = $discussion_count['count'];
	
	if($object_type=="P")
	{
		if(AREA !='A')
		{
			
			$cnt2 = db_get_field("select count(id) from clues_user_product_rating where product_id='".$object_id."' and for_product_status='A'");
			 
		}
		else
		{
			$cnt2 = db_get_field("select count(id) from clues_user_product_rating where product_id='".$object_id."'");
		}
		$cnt = $cnt + $cnt2;
	}
	else if($object_type=="M")
	{
		if(AREA !='A')
		{
			$cnt2 = db_get_field("select count(id) from clues_user_product_rating where company_id='".$object_id."' and avg_rate>0 and for_merchant_status='A' and review_merchant!=''");
		}
		else
		{
			$cnt2 = db_get_field("select count(id) from clues_user_product_rating where company_id='".$object_id."' and avg_rate>0");
		}
		
	
		$cnt = $cnt + $cnt2;
	}
	
	return $cnt;
	
}
function fn_get_detail_rating($obj_id, $object_type='P') //this function is also used for getting detail rating of merchant to show on merchant page by ankur
{
	$discussion = fn_get_discussion($obj_id, $object_type);

	if ((empty($discussion) || ($discussion['type'] != 'R' && $discussion['type'] != 'B')) && $object_type != "P" && $object_type != "M") {
		return false;
	}
	if($object_type=="P")
	{
		
				$rat1 = db_get_array("SELECT distinct(a.rating_value),count(a.rating_value) as count FROM ?:discussion_rating as a LEFT JOIN ?:discussion_posts as b ON a.post_id = b.post_id WHERE a.thread_id = ?i and b.status = 'A' group by a.rating_value", $discussion['thread_id']);
		 
		 
		
				$rat2 = db_get_array("select distinct(product_rating) as rating_value, count(product_rating) as count from clues_user_product_rating where product_id='".$obj_id."' group by product_rating");
		 
		$rat = array_merge($rat1,$rat2);
        $detail_rating = array('1'=>0,'2'=>0,'3'=>0,'4'=>0,'5'=>0);
        foreach($rat as $rating){
            if(array_key_exists($rating['rating_value'], $detail_rating)){
                $detail_rating[$rating['rating_value']] = $detail_rating[$rating['rating_value']] + $rating['count'];
            }else{
                $detail_rating[$rating['rating_value']] = $rating['count'];
            }
        }
        return $detail_rating;		
	}
	else if($object_type=="M")
	{
		$rat1 = db_get_array("SELECT distinct(a.rating_value),count(a.rating_value) as count FROM ?:discussion_rating as a LEFT JOIN ?:discussion_posts as b ON a.post_id = b.post_id WHERE a.thread_id = ?i and b.status = 'A' group by a.rating_value", $discussion['thread_id']);
		
		$rat2 = db_get_array("select distinct(round(avg_rate)) as rating_value, count(round(avg_rate)) as count from clues_user_product_rating where company_id='".$obj_id."' and avg_rate>0 group by round(avg_rate)");
		$rat = array_merge($rat1,$rat2);
        $detail_rating = array('1'=>0,'2'=>0,'3'=>0,'4'=>0,'5'=>0);
        foreach($rat as $rating){
            if(array_key_exists($rating['rating_value'], $detail_rating)){
                $detail_rating[$rating['rating_value']] = $detail_rating[$rating['rating_value']] + $rating['count'];
            }else{
                $detail_rating[$rating['rating_value']] = $rating['count'];
            }
        }
        return $detail_rating;	
	}
}

//function by ankur to get recent reviews
function fn_get_recent_reviews($obj_id,$obj_type)
{
	$sql1="select cdm.message as review,cdr.rating_value as rating,cdp.name as name,cdp.timestamp,cdp.thread_id from cscart_discussion_messages cdm inner join cscart_discussion cd on cd.thread_id=cdm.thread_id inner join cscart_discussion_posts cdp on cdp.thread_id=cdm.thread_id and cdp.post_id=cdm.post_id inner join cscart_discussion_rating cdr on cdr.post_id=cdp.post_id and cdr.thread_id=cdp.thread_id  where cd.object_id='".$obj_id."' and cd.object_type='".$obj_type."' and cd.type='B' and cdp.status='A'";
	if($obj_type=='P')
	{
		$sql2="select cupr.review,cupr.product_rating as rating,o.b_firstname as name,UNIX_TIMESTAMP(creation_date) as timestamp,'0' as thread_id from clues_user_product_rating cupr inner join cscart_orders o on o.order_id=cupr.order_id where cupr.product_id='".$obj_id."' and cupr.for_product_status='A'";
                }
	else if($obj_type=='M')
	{       
		$sql2="select cupr.review_merchant as review,cupr.avg_rate as rating,o.b_firstname as name,UNIX_TIMESTAMP(creation_date) as timestamp,'0' as thread_id from clues_user_product_rating cupr inner join cscart_orders o on o.order_id=cupr.order_id where cupr.company_id='".$obj_id."' and cupr.for_merchant_status='A' and review_merchant!=''";
	}
                
	
			$ret3 = db_get_array("select * from (" . $sql1 ." UNION ". $sql2 . ") tbl " . " ORDER BY rating desc,timestamp DESC  limit 3");
	 
	return $ret3;
}
function fn_get_useful_reviews($obj_id,$obj_type)
{       
	$sql1="select cdm.message as review,cdr.rating_value as rating,cdp.name as name,cdp.timestamp as date,cdp.thread_id,cdm.useful_yes_count as useful from cscart_discussion_messages cdm inner join cscart_discussion cd on cd.thread_id=cdm.thread_id inner join cscart_discussion_posts cdp on cdp.thread_id=cdm.thread_id and cdp.post_id=cdm.post_id inner join cscart_discussion_rating cdr on cdr.post_id=cdp.post_id and cdr.thread_id=cdp.thread_id where cd.object_id='".$obj_id."' and cd.object_type='".$obj_type."' and cd.type='B' and cdp.status='A'";
	if($obj_type=='P')
	{
		$sql2="select cupr.review,cupr.product_rating as rating,o.b_firstname as name,UNIX_TIMESTAMP(cupr.creation_date) as date,'0' as thread_id,cupr.product_useful_yes_count as useful from clues_user_product_rating cupr inner join cscart_orders o on o.order_id=cupr.order_id where cupr.product_id='".$obj_id."' and cupr.for_product_status='A'";
	}
	else if($obj_type=='M')
	{
		$sql2="select cupr.review_merchant as review,cupr.avg_rate as rating,o.b_firstname as name,UNIX_TIMESTAMP(cupr.creation_date) as date,'0' as thread_id,cupr.merchant_useful_yes_count as useful from clues_user_product_rating cupr inner join cscart_orders o on o.order_id=cupr.order_id where cupr.company_id='".$obj_id."' and cupr.for_merchant_status='A' and review_merchant!=''";
                }
	
	
	
			$ret3 = db_get_array("select * from (" . $sql1 ." UNION ". $sql2 . ") tbl " . " ORDER BY useful DESC  limit 3");
	 
	return $ret3;
}
function fn_get_merchant_review_rating($post_id,$thread_id)
{
	if($thread_id==0)
	{
		return db_get_field("select avg_rate from clues_user_product_rating where id='".$post_id."'");
	}
	else
	{
		return db_get_field("select rating_value from cscart_discussion_rating where post_id='".$post_id."' and thread_id='".$thread_id."'");
	}
}

function fn_vendor_review_replies($post_id)
{
        $vendor = "SELECT * FROM clues_merchant_review_response  where parent_id='".$post_id."' and reply_direction !='M2S' order by time_created desc";
        return db_get_array($vendor);
}
function fn_vendor_review_reply($post_id)
{
        $vendor = "SELECT * FROM clues_merchant_review_response  where parent_id='".$post_id."' order by time_created desc";
        return db_get_array($vendor);
}
function fn_vendor_review_flag($post_id)
{
        $vendor = "select * from clues_user_product_rating where id='".$post_id."' ";
        return db_get_array($vendor);
}


function fn_get_avg_merchant_review_rating($company_id){
    
    
    $avg_rating_value = db_get_row("SELECT avg(shipping_time) as shipping_time,avg(shipping_cost) as shipping_cost,avg(product_quality) as product_quality,avg(value_for_money) as value_for_money FROM `clues_user_product_rating` where company_id=".$company_id);
  
 $avg_rating_value['avg'] = ($avg_rating_value['shipping_time'] + $avg_rating_value['shipping_cost'] + $avg_rating_value['product_quality'] + $avg_rating_value['value_for_money'])/4;
 
 $avg_rating_value1['shipping_time']['full'] = floor($avg_rating_value['shipping_time']);
 $avg_rating_value1['shipping_time']['part'] = $avg_rating_value['shipping_time'] - $avg_rating_value1['shipping_time']['full'];
 $avg_rating_value1['shipping_time']['empty'] =5- $avg_rating_value1['shipping_time']['full'] - $avg_rating_value1['shipping_time']['part'];
 
 if (!empty($avg_rating_value1['shipping_time']['part'])) {
         if ($avg_rating_value1['shipping_time']['part'] <= 0.25) {
	       $avg_rating_value1['shipping_time']['part'] = 1;
	    } elseif ($avg_rating_value1['shipping_time']['part'] <= 0.5) {
			$avg_rating_value1['shipping_time']['part'] = 2;
	     } elseif ($avg_rating_value1['shipping_time']['part'] <= 0.75) {
			 $avg_rating_value1['shipping_time']['part'] = 3;
	     } elseif ($avg_rating_value1['shipping_time']['part'] <= 0.999) {
				$avg_rating_value1['shipping_time']['part'] = 4;
			}
		}
                
$avg_rating_value1['shipping_cost']['full'] = floor($avg_rating_value['shipping_cost']);
 
 $avg_rating_value1['shipping_cost']['part'] = $avg_rating_value['shipping_cost'] - $avg_rating_value1['shipping_cost']['full'];
 $avg_rating_value1['shipping_cost']['empty'] =5- $avg_rating_value1['shipping_cost']['full'] - $avg_rating_value1['shipping_cost']['part'];
 
 if (!empty($avg_rating_value1['shipping_cost']['part'])) {
         if ($avg_rating_value1['shipping_cost']['part'] <= 0.25) {
	       $avg_rating_value1['shipping_cost']['part'] = 1;
	    } elseif ($avg_rating_value1['shipping_cost']['part'] <= 0.5) {
			$avg_rating_value1['shipping_cost']['part'] = 2;
	     } elseif ($avg_rating_value1['shipping_cost']['part'] <= 0.75) {
			 $avg_rating_value1['shipping_cost']['part'] = 3;
	     } elseif ($avg_rating_value1['shipping_cost']['part'] <= 0.999) {
				$avg_rating_value1['shipping_cost']['part'] = 4;
			}
		}
                
$avg_rating_value1['product_quality']['full'] = floor($avg_rating_value['product_quality']);
$avg_rating_value1['product_quality']['part'] = $avg_rating_value['product_quality'] - $avg_rating_value1['product_quality']['full'];
$avg_rating_value1['product_quality']['empty'] =5- $avg_rating_value1['product_quality']['full'] - $avg_rating_value1['product_quality']['part'];
 
if (!empty($avg_rating_value1['product_quality']['part'])) {
         if ($avg_rating_value1['product_quality']['part'] <= 0.25) {
	       $avg_rating_value1['product_quality']['part'] = 1;
	    } elseif ($avg_rating_value1['product_quality']['part'] <= 0.5) {
			$avg_rating_value1['product_quality']['part'] = 2;
	     } elseif ($avg_rating_value1['product_quality']['part'] <= 0.75) {
			 $avg_rating_value1['product_quality']['part'] = 3;
	     } elseif ($avg_rating_value1['product_quality']['part'] <= 0.999) {
				$avg_rating_value1['product_quality']['part'] = 4;
			}
		}
                
$avg_rating_value1['value_for_money']['full'] = floor($avg_rating_value['value_for_money']);
$avg_rating_value1['value_for_money']['part'] = $avg_rating_value['value_for_money'] - $avg_rating_value1['value_for_money']['full'];
$avg_rating_value1['value_for_money']['empty'] =5- $avg_rating_value1['value_for_money']['full'] - $avg_rating_value1['value_for_money']['part'];
 
 if (!empty($avg_rating_value1['value_for_money']['part'])) {
         if ($avg_rating_value1['value_for_money']['part'] <= 0.25) {
	       $avg_rating_value1['value_for_money']['part'] = 1;
	    } elseif ($avg_rating_value1['value_for_money']['part'] <= 0.5) {
			$avg_rating_value1['value_for_money']['part'] = 2;
	     } elseif ($avg_rating_value1['value_for_money']['part'] <= 0.75) {
			 $avg_rating_value1['value_for_money']['part'] = 3;
	     } elseif ($avg_rating_value1['value_for_money']['part'] <= 0.999) {
				$avg_rating_value1['value_for_money']['part'] = 4;
			}
		}
 
 return array($avg_rating_value,$avg_rating_value1);
    
}
function fn_get_expert_review($product_id)
{
	$expert_review=db_get_row("select title,review,status from clues_expert_reviews where status='A' and product_id='".$product_id."'");
	//print_r($expert_review);
	return $expert_review; 
	}


//end
        
// Check whether user has logged in and rating configuration
        
function fn_user_rating_status()
{
    
    $user_logged_in = !empty($_SESSION['auth']['user_id']) ? $_SESSION['auth']['user_id'] : 0;
    
    $comment_status = Registry::get('config.review_login_required');
    
    // first check if comment config is enabled
    
    if($comment_status)
    {
          
        if(!empty($user_logged_in)){

            return 2;

        } else {
                
            return 3;
        }
        
    } else {
       
        return false;
    }   
    
}

        
?>
