<?php 

if ( !defined('AREA') ) { die('Access denied'); }

/**
 * Called when a user goes into the product detail page
 * 
 * @param mixed $product_data
 * @param mixed $auth
 */
function fn_block_packs_get_product_data_more($product_data, $auth)
{
    /**
     * Collect data for Most Viewed calculation from "Product Details" view
     */
    //increment product view_count
    $product_id = $product_data['product_id'];
    $view_count = $product_data['view_count'];
    $view_count+=1;
    db_query("UPDATE  `cscart_products` SET  `view_count` =  '$view_count' WHERE  `cscart_products`.`product_id` =$product_id;");
    
    return true;
}

/**
 * Called from fn_get_products() before any database operations are performed
 * 
 * @param mixed $params
 * @param mixed $fields
 * @param mixed $sortings
 * @param mixed $condition
 * @param mixed $join
 * @param mixed $sorting
 * @param mixed $group_by
 * @param mixed $lang_code
 */
function fn_block_packs_get_products($params, $fields, $sortings, $condition, $join, $sorting, $group_by, $lang_code)
{
    /**
     * 
     * Save cart_products for the cart_items sidebox filling
     */
    $cart = & $_SESSION['cart'];
	$auth = & $_SESSION['auth'];
	list ($cart_products, $_SESSION['shipping_rates']) = fn_calculate_cart_content($cart, $auth, Registry::get('settings.General.estimate_shipping_cost') == 'Y' ? 'A' : 'S', true, 'F', true);
	fn_gather_additional_products_data($cart_products, array('get_icon' => true, 'get_detailed' => true, 'get_options' => true, 'get_discounts' => false));
	$cart_products = array_reverse($cart_products, true);
	Registry::get('view')->assign('cart_products', $cart_products);
	Registry::get('view')->assign('hide_add_to_cart_button', $params['hide_add_to_cart_button']);
	Registry::get('view')->assign('params', $params);
	/*Registry::get('view')->assign('currencies', $currencies);
	Registry::get('view')->assign('primary_currency', $primary_currency);*/
    
    /**
     * What other customers are looking at filling calculation
     */
    if($params['data_function']=="fn_block_packs_what_other_customers_are_looking_at_data")
    {
        //get the most popular products that have the highest view counts
       $popProductsArr = db_get_array("
        SELECT product_id, COUNT(product_id) AS count 
        FROM `cscart_what_other_customers_are_looking_at` 
        GROUP BY product_id
        ORDER BY count DESC
        LIMIT ".$params['limit']."
        ");
       
       //$products = array();
       $auth = & $_SESSION['auth'];
       foreach($popProductsArr as $product)
       {
       	   $products[] = fn_get_product_data($product['product_id'], $auth);
       }
       
       Registry::set("productsFrom_what_other_customers_are_looking_at_data_function", $products);
    }

    /**
     * Best Seller In A Category calculation
     */
	else if($params['data_function']=="fn_block_packs_best_seller_in_a_category_data")
    {
        
       $category_id = db_get_field("SELECT  `category_id` FROM  `cscart_category_descriptions` WHERE  `category` LIKE  '".$params['category']."' LIMIT 1;");
        //get the products that have the highest sales counts in a given category
        $dbQuery = "
        SELECT  `product_id` ,  `sales_count` 
		FROM  `cscart_best_seller_in_a_category` ";
		
		if ($category_id)
			$dbQuery .= "WHERE  `category_id` =$category_id";
		
		$dbQuery .= "
		ORDER BY  `sales_count` DESC 
        LIMIT ".$params['limit']."
        ";
       $bestSellCatProductsArr = db_get_array($dbQuery);
       
       //$products = array();
       $auth = & $_SESSION['auth'];
       foreach($bestSellCatProductsArr as $product)
       {
       	   $products[] = fn_get_product_data($product['product_id'], $auth);
       }
       
       Registry::set("productsFrom_fn_block_packs_best_seller_in_a_category_data_function", $products);
    }
    
    /**
     * Frequently Bought With calculation
     */
	else if($params['data_function']=="fn_block_packs_frequently_bought_with_data")
    {
	    $product_id = db_get_field("SELECT `product_id` FROM `cscart_frequently_bought_with` WHERE `product_id` LIKE '".$params['pid']."' LIMIT 1;");
        if ($product_id) {
	        
	       $boughtWithProductsArr = db_get_array("
	        SELECT  `bought_with_id` ,  `bought_with_count` 
			FROM  `cscart_frequently_bought_with` 
			WHERE  `product_id` = $product_id 
			ORDER BY  `bought_with_count` DESC 
	        LIMIT ".$params['limit']."
			");
	       
	       $auth = & $_SESSION['auth'];
	       foreach($boughtWithProductsArr as $product)
	       {
	       	   $products[] = fn_get_product_data($product['bought_with_id'], $auth);
	       }
       }
	   Registry::set("productsFrom_fn_block_packs_frequently_bought_with_data_function", $products);
    }
    
    /**
     * Customers Who Bought This Also Bought calculation
     */
	else if($params['data_function']=="fn_block_packs_customers_who_bought_this_also_bought_data")
    {
    	if ($params['pid']) {
	    	$alsoBoughtProductsArr = db_get_array("
			    SELECT DISTINCT cscart_order_details.product_id
				FROM cscart_order_details
				INNER JOIN cscart_orders
				ON cscart_order_details.order_id=cscart_orders.order_id
				WHERE cscart_orders.user_id IN (
				SELECT DISTINCT cscart_orders.user_id
				FROM cscart_order_details
				INNER JOIN cscart_orders
				ON cscart_order_details.order_id=cscart_orders.order_id
				WHERE cscart_order_details.product_id= ".$params['pid']." )
				AND cscart_order_details.product_id != ".$params['pid']."
	        	LIMIT ".$params['limit']."
				");
			$auth = & $_SESSION['auth'];
	       foreach($alsoBoughtProductsArr as $product)
	       {
	       	   $products[] = fn_get_product_data($product['product_id'], $auth);
	       }
       }
	   Registry::set("productsFrom_fn_block_packs_customers_who_bought_this_also_bought_data_function", $products);
    }
    
    /**
     * Inspired By Your Browsing History calculation
     */
	else if($params['data_function']=="fn_block_packs_inspired_by_your_browsing_history_data")
    {
    	$user_id = 0; //default to non-logged in guest user
	    $user_info = Registry::get('user_info');
	    if($user_info!=null)
	    {    	
	    	$recentHistoryProductsArr = db_get_array("
				    SELECT DISTINCT product_id
					FROM cscart_best_seller_in_a_category
					WHERE category_id
					IN (
					SELECT DISTINCT cscart_products_categories.category_id
					FROM cscart_what_other_customers_are_looking_at
					INNER JOIN cscart_products_categories
					ON cscart_products_categories.product_id = cscart_what_other_customers_are_looking_at.product_id
					WHERE cscart_what_other_customers_are_looking_at.user_id = ".$user_info['user_id']."
					)
					ORDER BY sales_count DESC
					");
	    	$auth = & $_SESSION['auth'];
		    foreach($recentHistoryProductsArr as $product)
		       {
		       	   $products[] = fn_get_product_data($product['product_id'], $auth);
		       }
	     }
	   Registry::set("productsFrom_fn_block_packs_inspired_by_your_browsing_history_data_function", $products);
    }
    
    /**
     * Customers Bought After Viewing calculation
     */
	else if($params['data_function']=="fn_block_packs_customers_bought_after_viewing_data")
    {
	    if ($params['pid']) {
	    	$boughtAfterViewingProductsArr = db_get_array("
			    SELECT DISTINCT cscart_order_details.product_id
				FROM cscart_what_other_customers_are_looking_at
				INNER JOIN cscart_orders ON cscart_what_other_customers_are_looking_at.user_id = cscart_orders.user_id
				INNER JOIN cscart_order_details ON cscart_orders.order_id = cscart_order_details.order_id
				WHERE cscart_orders.timestamp > cscart_what_other_customers_are_looking_at.timestamp
				AND cscart_orders.timestamp < cscart_what_other_customers_are_looking_at.timestamp + 24*60*60
				AND cscart_what_other_customers_are_looking_at.product_id = ".$params['pid']."
	        	LIMIT ".$params['limit']."
				");
			$auth = & $_SESSION['auth'];
	       foreach($boughtAfterViewingProductsArr as $product)
	       {
	       	   $products[] = fn_get_product_data($product['product_id'], $auth);
	       }
       }
	   Registry::set("productsFrom_fn_block_packs_customers_bought_after_viewing_data_function", $products);
    }
	
	/**
     * Top/Featured Merchants calculation
     */
	else if($params['data_function']=="fn_block_packs_top_featured_merchants_data")
    {
	    $category_id = db_get_field("SELECT  `category_id` FROM  `cscart_category_descriptions` WHERE  `category` LIKE  '".$params['category']."' LIMIT 1;");
	    if (!$category_id)
	    	$category_id = $params['cid'];
	    if ($category_id) {
	        $bestMerchantProductsArr = db_get_array("
				    SELECT cscart_companies.company_id
					FROM cscart_orders
					INNER JOIN cscart_order_details ON cscart_orders.order_id = cscart_order_details.order_id
					INNER JOIN cscart_companies ON cscart_companies.company_id = cscart_orders.company_id
					INNER JOIN cscart_products_categories ON cscart_products_categories.product_id = cscart_order_details.product_id
					WHERE cscart_orders.is_parent_order = 'N'
					AND cscart_products_categories.category_id = ".$category_id."
					GROUP BY cscart_orders.company_id
					ORDER BY SUM( cscart_order_details.amount ) DESC
		        	LIMIT ".$params['limit']."
					");
	       
	       $auth = & $_SESSION['auth'];
	       foreach($bestMerchantProductsArr as $product)
	       {
	       		//UNCOMMENT THIS LINE TO ENABLE - Currently won't work due to it being a company id rather than a product id...
	       	   //$products[] = fn_get_product_data($product['company_id'], $auth);
	       }
       }
	   Registry::set("productsFrom_fn_block_packs_top_featured_merchants_data_function", $products);
    }
    
    /**
     * Best Sellers In a List of Categories calculation
     */
	else if($params['data_function']=="fn_block_packs_best_seller_in_list_of_categories_data")
    {
        if ($params['CSV List of Categories']) {
    	$cats = str_replace(",", "\",\"", str_replace(" ,", ",", "\"".str_replace(", ", ",", $params['CSV List of Categories'])."\""));
	       $bestSellListOfCatsProductsArr = db_get_array("
	        SELECT product_id 
			FROM cscart_best_seller_in_a_category 
			WHERE category_id IN (
			SELECT cscart_category_descriptions.category_id
			FROM cscart_category_descriptions
			WHERE cscart_category_descriptions.category
			IN (".$cats.")
			) 
			ORDER BY sales_count DESC 
	        LIMIT ".$params['limit']
	        );
	        
	       $auth = & $_SESSION['auth'];
	       foreach($bestSellListOfCatsProductsArr as $product)
	       {
	       	   $products[] = fn_get_product_data($product['product_id'], $auth);
	       }
       }
	   
	   Registry::set("productsFrom_fn_block_packs_best_seller_in_list_of_categories_data_function", $products);
    }
    
    /**
     * Most viewed sorting schema extension
     */
    //add the view_count param to the sortings list and specify the db var name
    $sortings['view_count'] = 'products.view_count';
}

/**
 * Called from many places when the user_info is needed
 * 
 * @param mixed $user_info
 */
function fn_block_packs_get_user_info($user_info)
{
    //save user_info for later
    Registry::set('user_info', $user_info);
}

/**
 * Called after a fn_get_products() call has been made
 * 
 * @param mixed $products the product set returned by fn_get_products()
 */
function fn_block_packs_get_products_post($products)
{
    
    /**
     * Collect data for Most Viewed calculation on every fn_get_products() call (every time a product is displayed in ANY block)
     */
    //increment product view_count on products table for each product in the call 
    foreach($products as $product)
    {
        $product_id = $product['product_id'];
        $view_count = $product['view_count'];
        $view_count+=1;
        db_query("UPDATE  `cscart_products` SET  `view_count` =  '$view_count' WHERE  `cscart_products`.`product_id` =$product_id;");
    }
    
    /**
     * Collect data for What Other Customers Are Looking At calculation
     */
    //add product_id, user_id, timestamp to what_other_customers_are_looking_at table
    $user_id = 0; //default to non-logged in guest user
    $user_info = Registry::get('user_info');
    if($user_info!=null)
    {
        /*foreach($products as $product)
        {
            $product_id = $product['product_id'];
            $user_id = $user_info['user_id'];
             db_query("
                INSERT INTO  `cscart_what_other_customers_are_looking_at` (
                `input_id`,
                `product_id` ,
                `user_id` ,
                `timestamp`
                )
                VALUES (
                '', '$product_id',  '$user_id', 
                ".time()."
                );
             ");
        }*/
         
    }
    
    /**
     * Override products search result with What Other Customers Are Looking At result
     */
    $productsFrom = Registry::get('productsFrom_what_other_customers_are_looking_at_data_function');
    if($productsFrom!=null)
    {
        $products = $productsFrom;
        Registry::del('productsFrom_what_other_customers_are_looking_at_data_function');
    }
    unset($productsFrom);
    
	/**
     * Override products search result with Best Seller In A Category result
     */
    $productsFrom = Registry::get('productsFrom_fn_block_packs_best_seller_in_a_category_data_function');
    if($productsFrom!=null)
    {
        $products = $productsFrom;
        Registry::del('productsFrom_fn_block_packs_best_seller_in_a_category_data_function');
    }
    unset($productsFrom);
    
    /**
     * Override products search result with Frequently Bought With result
     */
    $productsFrom = Registry::get('productsFrom_fn_block_packs_frequently_bought_with_data_function');
    if($productsFrom!=null)
    {
        $products = $productsFrom;
        Registry::del('productsFrom_fn_block_packs_frequently_bought_with_data_function');
    }
    unset($productsFrom);
    
	/**
     * Override products search result with Customers Who Bought This Also Bought result
     */
    $productsFrom = Registry::get('productsFrom_fn_block_packs_customers_who_bought_this_also_bought_data_function');
    if($productsFrom!=null)
    {
        $products = $productsFrom;
        Registry::del('productsFrom_fn_block_packs_customers_who_bought_this_also_bought_data_function');
    }
    unset($productsFrom);
	
	/**
     * Override products search result with Inspired By Your Browsing History result
     */
    $productsFrom = Registry::get('productsFrom_fn_block_packs_inspired_by_your_browsing_history_data_function');
    if($productsFrom!=null)
    {
        $products = $productsFrom;
        Registry::del('productsFrom_fn_block_packs_inspired_by_your_browsing_history_data_function');
    }
    unset($productsFrom);
	
	/**
     * Override products search result with Other Customers Bought These Items After Viewing result
     */
    $productsFrom = Registry::get('productsFrom_fn_block_packs_customers_bought_after_viewing_data_function');
    if($productsFrom!=null)
    {
        $products = $productsFrom;
        Registry::del('productsFrom_fn_block_packs_customers_bought_after_viewing_data_function');
    }
    unset($productsFrom);
	
	/**
     * Override products search result with Top/Featured Merchants result
     */
    $productsFrom = Registry::get('productsFrom_fn_block_packs_top_featured_merchants_data_function');
    if($productsFrom!=null)
    {
        $products = $productsFrom;
        Registry::del('productsFrom_fn_block_packs_top_featured_merchants_data_function');
    }
    unset($productsFrom);
	
	/**
     * Override products search result with Best Seller In a List of Categories result
     */
    $productsFrom = Registry::get('productsFrom_fn_block_packs_best_seller_in_list_of_categories_data_function');
    if($productsFrom!=null)
    {
        $products = $productsFrom;
        Registry::del('productsFrom_fn_block_packs_best_seller_in_list_of_categories_data_function');
    }
    unset($productsFrom);
    
    return true;
}

/**
 * Called for fn_place_order() before the database operations are performed
 * 
 * @param mixed $order_id
 */
function fn_block_packs_place_order($order_id)
{
    /**
     * Count sales for the Best Seller In A Category filling
     */
    //add product_id, category_id, inc(sales_count) to best_seller_in_a_category
    $order_info = fn_get_order_info($order_id);
    
    foreach($order_info['items'] as $item)
    {
        $product_id = $item['product_id'];
        $primary_category_id = db_get_field("SELECT  `category_id` FROM  `cscart_products_categories` WHERE  `product_id` =$product_id LIMIT 1");
        $sales_count = db_get_field("SELECT `sales_count` FROM `cscart_best_seller_in_a_category` WHERE  `product_id` =$product_id LIMIT 1");
        $sales_count +=  $item['amount'];

        //product not already started counting sales? insert new row to track
        $num = db_get_field("SELECT * FROM `cscart_best_seller_in_a_category` WHERE `product_id`=$product_id");
        if($num==null)
        {
            db_query("
            INSERT INTO `cscart_best_seller_in_a_category` 
            (`input_id`, `product_id`, `category_id`, `sales_count`) 
            VALUES (NULL, '$product_id', '$primary_category_id', '$sales_count');
            ");
        }else //update sales count and cat id only if already tracking this product
        {
            db_query("
            UPDATE `cscart_best_seller_in_a_category` SET  `category_id` =  '$primary_category_id',
			`sales_count` =  '$sales_count' WHERE  `cscart_best_seller_in_a_category`.`product_id` =$product_id;
            ");
        }
    }

	/**
     * Count sales for the Frequently Bought Together filling
     */    
    foreach($order_info['items'] as $item)
    {
    	$linked_items = array();
		$index = 0;
    	foreach($order_info['items'] as $item2)
    	{
    		if ($item != $item2) {
        		$linked_product_id = $item2['product_id'];
				$linked_items[$index] = $linked_product_id;
				$index++;
			}
		}
        $product_id = $item['product_id'];

        foreach($linked_items as $linked_id)
        {
	        $bought_with_count = db_get_field("SELECT bought_with_count FROM cscart_frequently_bought_with WHERE  product_id=$product_id AND bought_with_id=$linked_id LIMIT 1");
	        $bought_with_count++;
	        //
	        $num = db_get_field("SELECT * FROM cscart_frequently_bought_with WHERE product_id=$product_id AND bought_with_id=$linked_id");
	        if ($num == null) {
	            db_query("
	            INSERT INTO `cscart_frequently_bought_with` 
	            (`input_id`, `product_id`, `bought_with_id`, `bought_with_count`) 
	            VALUES (NULL, $product_id, $linked_id, 1);
	            ");
	        } else {//
	            db_query("
	            UPDATE `cscart_frequently_bought_with` SET 
				`bought_with_count` = $bought_with_count WHERE `product_id` = $product_id AND `bought_with_id` = $linked_id;
	            ");
	        }
        }
    }
}

?>
