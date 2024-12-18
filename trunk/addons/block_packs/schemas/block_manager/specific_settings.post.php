<?php

if ( !defined('AREA') ) { die('Access denied'); }

$schema['fillings']['what_other_customers_are_looking_at'] = array (
				'limit' => array (
				'type' => 'input',
				'default_value' => 6
			),
			'num_rows' => array (
			'type' => 'input',
			'default_value' => 4
		    )
		);
$schema['fillings']['best_seller_in_a_category'] = array (
			'limit' => array (
			'type' => 'input',
			'default_value' => 6
		    ),
			'num_rows' => array (
			'type' => 'input',
			'default_value' => 4
		    ),
		    'category' => array (
			'type' => 'input',
			'default_value' => ''
		    ),
		);
$schema['fillings']['your_recent_history'] = array (
				'limit' => array (
				'type' => 'input',
				'default_value' => 6
			),
			'num_rows' => array (
			'type' => 'input',
			'default_value' => 4
		    )
		);
$schema['fillings']['most_viewed'] = array (
				'limit' => array (
				'type' => 'input',
				'default_value' => 6
			),
			'num_rows' => array (
			'type' => 'input',
			'default_value' => 4
		    )
		);
$schema['fillings']['frequently_bought_with'] = array (
				'limit' => array (
				'type' => 'input',
				'default_value' => 6
			),
			'num_rows' => array (
			'type' => 'input',
			'default_value' => 4
		    )
		);
$schema['fillings']['customers_who_bought_this_also_bought'] = array (
				'limit' => array (
				'type' => 'input',
				'default_value' => 6
			),
			'num_rows' => array (
			'type' => 'input',
			'default_value' => 4
		    )
		);
$schema['fillings']['inspired_by_your_browsing_history'] = array (
				'limit' => array (
				'type' => 'input',
				'default_value' => 6
			),
			'num_rows' => array (
			'type' => 'input',
			'default_value' => 4
		    )
		);
$schema['fillings']['customers_bought_after_viewing'] = array (
				'limit' => array (
				'type' => 'input',
				'default_value' => 6
			),
			'num_rows' => array (
			'type' => 'input',
			'default_value' => 4
		    )
		);
$schema['fillings']['top_featured_merchants'] = array (
				'limit' => array (
				'type' => 'input',
				'default_value' => 6
			),
			'num_rows' => array (
			'type' => 'input',
			'default_value' => 4
		    ),
		    'category' => array (
			'type' => 'input',
			'default_value' => ''
		    )
		);
$schema['fillings']['best_seller_in_list_of_categories'] = array 
        (
			'limit' => array 
            (
				'type' => 'input',
				'default_value' => 6
			),
			'num_rows' => array (
			'type' => 'input',
			'default_value' => 4
		    ),
			'csv_list_of_categories' => array 
		    (
    			'type' => 'input',
    			'default_value' => ''
		    )
		);
?>