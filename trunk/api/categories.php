<?php

define('AREA', 'C');
define('AREA_NAME', 'customer');
define('ACCOUNT_TYPE', 'customer');

require  dirname(__FILE__) . '/../prepare.php';
require  dirname(__FILE__) . '/../init.php';


/**
 * Provide the access key in the url to access data
 */
if(!(isset($_REQUEST['key']) && ($_REQUEST['key'] == 'd12121c70dda5edfgd1df6633fdb36c0'))){
	echo json_encode(array("result" => array(array("success"=>"false","key"=>"invalid", "error"=>"1"))));
	exit;
}

/**
 * 
 * @param type $cat_id - this is the Category Id whose child categories you wish to access
 * 
 * If $cat_id is 0 then all the Meta Categories are returned.
 * 
 * If the category is Leaf Category then the products inside this category will be returned.
 */
function getCategoryInfo($cat_id = 0)
{
    $cat = db_get_array("SELECT cd.category_id, cd.category 
    FROM cscart_categories cc 
    LEFT JOIN cscart_category_descriptions cd ON cc.category_id = cd.category_id 
    WHERE cc.status='A' 
    AND cc.parent_id=".$cat_id);

    if(count($cat) == 0)
    {
        $cat = db_get_array("SELECT p.product_id, pd.product 
        FROM cscart_products p 
        INNER JOIN cscart_product_descriptions pd on p.product_id = pd.product_id
        INNER JOIN cscart_products_categories pc ON pc.product_id = p.product_id 
        INNER JOIN cscart_categories cc ON cc.category_id = pc.category_id 
        WHERE p.status='A' 
        AND pc.category_id=".$_REQUEST['cat_id']);
        
        if($cat){
            echo json_encode(array("result" => array(array("success"=>"true", "product"=>$cat))));
            exit;
        }
        else
        {
            echo json_encode(array("result" => array(array("success"=>"false", "error"=>"2"))));
            exit;
        }
    }
    else
    {
        if($cat){
            echo json_encode(array("result" => array(array("success"=>"true", "category"=>$cat))));
            exit;
        } 
        else {
            echo json_encode(array("result" => array(array("success"=>"false", "error"=>"2"))));
            exit;
        }
    }
    //$cat = array_merge($cat,$prod);
    
    //echo "<pre>";print_r($cat);

}

/**
 * To get details about categories then type=categories should be passed in url
 * 
 * if cat_id is also passed in the url then its child categories will be returned else all the Meta Categories will be returned
 */
if(isset($_REQUEST['type']) && ($_REQUEST['type'] == 'categories')){

    if(isset($_REQUEST['cat_id'])){	
            getCategoryInfo($_REQUEST['cat_id']);
    } 
    else {
            getCategoryInfo(0);
    }
} 
else {
        echo json_encode(array("result" => array(array("success"=>"false", "error"=>"3"))));
        exit;
}

?>
