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
// $Id: categories.php 12865 2011-07-05 06:57:22Z 2tl $
//

if ( !defined('AREA') ) { die('Access denied'); }

$_REQUEST['category_id'] = empty($_REQUEST['category_id']) ? 0 : $_REQUEST['category_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	// Define trusted variables that shouldn't be stripped
	fn_trusted_vars (
		'category_data',
		'categories_data'
	);

	//
	// Processing additon of new category element
	//
	if ($mode == 'add') {
		if (!empty($_REQUEST['category_data']['category'])) {  // Checking for required fields for new category

			// Adding category record
			$category_id = fn_update_category($_REQUEST['category_data']);

			if (!empty($category_id)) {
				// Adding category images pair
				fn_attach_image_pairs('category_main', 'category', $category_id, DESCR_SL);

				$suffix = ".update?category_id=$category_id";
			} else {

				$suffix = ".manage";
			}
		} else {
			$suffix = ".add";
		}
	}

	//
	// Processing updating of category element
	//
	if ($mode == 'update') {

		if (!empty($_REQUEST['category_data']['category'])) {

			// Updating category record
			fn_update_category($_REQUEST['category_data'], $_REQUEST['category_id'], DESCR_SL);

			// Updating category images
			fn_attach_image_pairs('category_main', 'category', $_REQUEST['category_id'], DESCR_SL);
		}

		$suffix = ".update?category_id=$_REQUEST[category_id]" . (!empty($_REQUEST['category_data']['block_id']) ? "&selected_block_id=" . $_REQUEST['category_data']['block_id'] : "");
	}

	//
	// Processing mulitple addition of new category elements
	//
	if ($mode == 'm_add') {
		if (!fn_is_empty($_REQUEST['categories_data'])) {
			$is_added = false;
			foreach ($_REQUEST['categories_data'] as $k => $v) {
				if (!empty($v['category'])) {  // Checking for required fields for new category
					if (fn_update_category($v)) {
						$is_added = true;
					}
				}
			}

			if ($is_added) {
				fn_set_notification('N', fn_get_lang_var('notice'), fn_get_lang_var('categories_have_been_added'));
			}
		}


		$suffix = ".manage";
	}

	//
	// Processing multiple updating of category elements
	//
	if ($mode == 'm_update') {

		// Update multiple categories data
		if (is_array($_REQUEST['categories_data'])) {
			fn_attach_image_pairs('category_main', 'category', 0, DESCR_SL);

			foreach ($_REQUEST['categories_data'] as $k => $v) {
				fn_update_category($v, $k, DESCR_SL);
			}
		}

		$suffix = ".manage";
	}

	//
	// Processing deleting of multiple category elements
	//
	if ($mode == 'm_delete') {

		if (isset($_REQUEST['category_ids'])) {
			foreach ($_REQUEST['category_ids'] as $v) {
				fn_delete_category($v);
			}
		}

		unset($_SESSION['category_ids']);

		fn_set_notification('N', fn_get_lang_var('notice'), fn_get_lang_var('text_categories_have_been_deleted'));
		$suffix = ".manage";
	}


	//
	// Store selected fields for using in 'm_update' mode
	//
	if ($mode == 'store_selection') {

		if (!empty($_REQUEST['category_ids'])) {
			$_SESSION['category_ids'] = $_REQUEST['category_ids'];
			$_SESSION['selected_fields'] = $_REQUEST['selected_fields'];

			$suffix = ".m_update";
		} else {
			$suffix = ".manage";
		}
	}

	//
	// This mode is using to send search data via POST method
	//
	if ($mode == 'do_search_categories') {
		$suffix = ".manage";
	}

	return array(CONTROLLER_STATUS_OK, "categories$suffix");
}

//
// 'Add new category' page
//
if ($mode == 'add') {

	fn_add_breadcrumb(fn_get_lang_var('categories'), "categories.manage");

	// [Page sections]
	Registry::set('navigation.tabs', array (
		'detailed' => array (
			'title' => fn_get_lang_var('general'),
			'js' => true
		),
		'addons' => array (
			'title' => fn_get_lang_var('addons'),
			'js' => true
		),
	));
	// [/Page sections]


	if (!empty($_REQUEST['parent_id'])) {
		$category_data['parent_id'] = $_REQUEST['parent_id'];
		$view->assign('category_data', $category_data);
	}

//
// 'Multiple categories addition' page
//
} elseif ($mode == 'm_add') {

//
// 'category update' page
//
} elseif ($mode == 'update') {

	// Get current category data
	$category_data = fn_get_category_data($_REQUEST['category_id'], DESCR_SL);
	
	if (empty($category_data)) {
		return array(CONTROLLER_STATUS_NO_PAGE);
	}

	// [Breadcrumbs]
	fn_add_breadcrumb(fn_get_lang_var('categories'), "categories.manage");
	// [/Breadcrumbs]

	// [Page sections]
	$tabs = array (
		'detailed' => array (
			'title' => fn_get_lang_var('general'),
			'js' => true
		)
	);
	if (!defined('COMPANY_ID')) {
		$tabs['blocks'] = array (
			'title' => fn_get_lang_var('blocks'),
			'js' => true
		);
	}
	$tabs['addons'] = array (
		'title' => fn_get_lang_var('addons'),
		'js' => true
	);
	$tabs['layout'] = array (
		'title' => fn_get_lang_var('layout'),
		'js' => true
	);
	Registry::set('navigation.tabs', $tabs);
	// [/Page sections]
	$view->assign('category_data', $category_data);

	// [Block manager]
	list($blocks) = fn_get_blocks(array('location' => 'categories', 'block_type' => 'B', 'all' => true));
	if (!empty($blocks)) {
		$view->assign('blocks', $blocks);
		$view->assign('selected_block', fn_get_selected_block_data($_REQUEST, $blocks, $_REQUEST['category_id'], 'categories'));
		$view->assign('block_properties', fn_get_block_properties());
	}
	// [/Block manager]

//
// 'Mulitple categories updating' page
//
} elseif ($mode == 'm_update') {

	fn_add_breadcrumb(fn_get_lang_var('categories'), "categories.manage");

	$category_ids = $_SESSION['category_ids'];
	$selected_fields = $_SESSION['selected_fields'];

	if (empty($category_ids) || empty($selected_fields) || empty($selected_fields['object']) || $selected_fields['object'] != 'category') {
		return array(CONTROLLER_STATUS_REDIRECT, "categories.manage");
	}

	$field_groups = array (
		'A' => array (
			'category' => 'categories_data',
			'page_title' => 'categories_data',
			'position' => 'categories_data',
		),

		'C' => array ( // textareas
			'description' => 'categories_data',
			'meta_keywords' => 'categories_data',
			'meta_description' => 'categories_data',
		),
	);

	$get_main_pair = false;

	$fields2update = $selected_fields['data'];

	$data_search_fields = implode($fields2update, ', ');

	if (!empty($data_search_fields)) {
		$data_search_fields = ', ' . $data_search_fields;
	}

	if (!empty($selected_fields['images'])) {
		foreach ($selected_fields['images'] as $value) {
			$fields2update[] = $value;
			if ($value == 'image_pair') {
				$get_main_pair = true;
			}
		}
	}

	$filled_groups = array();
	$field_names = array();
	foreach ($fields2update as $field) {
		if ($field == 'usergroup_ids') {
			$desc = 'usergroups';
		} elseif ($field == 'timestamp') {
			$desc = 'creation_date';
		} else {
			$desc = $field;
		}
		if ($field == 'category_id') {
			continue;
		}

		if (!empty($field_groups['A'][$field])) {
			$filled_groups['A'][$field] = fn_get_lang_var($desc);
			continue;
		} elseif (!empty($field_groups['B'][$field])) {
			$filled_groups['B'][$field] = fn_get_lang_var($desc);
			continue;
		} elseif (!empty($field_groups['C'][$field])) {
			$filled_groups['C'][$field] = fn_get_lang_var($desc);
			continue;
		}

		$field_names[$field] = fn_get_lang_var($desc);
	}

	ksort($filled_groups, SORT_STRING);

	$categories_data = array();
	foreach($category_ids as $value){
		$categories_data[$value] = fn_get_category_data($value, DESCR_SL, '?:categories.category_id' . $data_search_fields, $get_main_pair);
	}

	$view->assign('field_groups', $field_groups);
	$view->assign('filled_groups', $filled_groups);

	$view->assign('fields2update', $fields2update);
	$view->assign('field_names', $field_names);

	$view->assign('categories_data', $categories_data);
}
//
// Delete category
//
elseif ($mode == 'delete') {

	if (!empty($_REQUEST['category_id'])) {
		fn_delete_category($_REQUEST['category_id']);
	}

	fn_set_notification('N', fn_get_lang_var('notice'), fn_get_lang_var('text_category_has_been_deleted'));
	return array(CONTROLLER_STATUS_REDIRECT, "categories.manage");

//
// 'Management' page
//
} elseif ($mode == 'manage' || $mode == 'picker') {

	if ($mode == 'manage') {
		unset($_SESSION['category_ids']);
		unset($_SESSION['selected_fields']);
	}

	$category_count = db_get_field("SELECT COUNT(*) FROM ?:categories");
	$category_id = empty($_REQUEST['category_id']) ? 0 : $_REQUEST['category_id'];
	$except_id = 0; 
$escape_cat_id='';
	if (!empty($_REQUEST['except_id'])) {
		$except_id = $_REQUEST['except_id'];
		$view->assign('except_id', $_REQUEST['except_id']);
	}
	
	if (defined('COMPANY_ID') && $mode == 'picker') {
		$escape_cat_id = Registry::get('config.special_category_ids');
	}
	if ($category_count < CATEGORY_THRESHOLD) {
		$params = array (
			'simple' => false,
			'add_root' => !empty($_REQUEST['root']) ? $_REQUEST['root'] : '',
			'b_id' => !empty($_REQUEST['b_id']) ? $_REQUEST['b_id'] : '',
			'except_id' => $except_id,
			'escape_cat_id' => $escape_cat_id
		);
 		list($categories_tree, ) = fn_get_categories($params);
 		$view->assign('show_all', true);
	} else {
		$params = array (
			'category_id' => $category_id,
			'current_category_id' => $category_id,
			'visible' => true,
			'simple' => false,
			'add_root' => !empty($_REQUEST['root']) ? $_REQUEST['root'] : '',
			'b_id' => !empty($_REQUEST['b_id']) ? $_REQUEST['b_id'] : '',
			'except_id' => $except_id,
			'escape_cat_id' => $escape_cat_id
		);
		list($categories_tree, ) = fn_get_categories($params);//categories issue to be solved by clues dev
	}

	$view->assign('categories_tree', $categories_tree);
	if ($category_count < CATEGORY_SHOW_ALL) {
		$view->assign('expand_all', true);
	}
	if (defined('AJAX_REQUEST')) {
		if (!empty($_REQUEST['random'])) {
			$view->assign('random', $_REQUEST['random']);
		}
		$view->assign('category_id', $category_id);
	}
}

//
// Categories picker
//
if ($mode == 'picker') {
	$view->display('pickers/categories_picker_contents.tpl');
	exit;
}


//
// Delete category, its subcategories and products by id
//
function fn_delete_category($category_id, $recurse = true)
{
	if (!empty($category_id)) {

		// Log category deletion
		fn_log_event('categories', 'delete', array(
			'category_id' => $category_id
		));

		// Delete all subcategories
		if ($recurse == true) {
			$id_path = db_get_field("SELECT id_path FROM ?:categories IGNORE INDEX(p_category_id) WHERE category_id = ?i", $category_id);
			$category_ids	= db_get_fields("SELECT category_id FROM ?:categories WHERE category_id = ?i OR id_path LIKE ?l", $category_id, "$id_path/%");
		} else {
			$category_ids[] = $category_id;
		}
		
		foreach ($category_ids as $k => $category_id) {

			fn_clean_block_items('categories', $category_id);
			fn_clean_block_links('categories', $category_id);

			// Deleting category
			db_query("DELETE FROM ?:categories WHERE category_id = ?i", $category_id);
			db_query("DELETE FROM ?:category_descriptions WHERE category_id = ?i", $category_id);

			// Deleting additional product associations without deleting products itself
			db_query("DELETE FROM ?:products_categories WHERE category_id = ?i AND link_type = 'A'", $category_id);

			// Remove this category from features assignments
			db_query("UPDATE ?:product_features SET categories_path = ?p", fn_remove_from_set('categories_path', $category_id));

			// Deleting main products association with deleting products
			$products_to_delete = db_get_fields("SELECT product_id FROM ?:products_categories WHERE category_id = ?i AND link_type = 'M'", $category_id);
			if(!empty($products_to_delete))	{
				foreach($products_to_delete as $key	=> $value) {
					fn_delete_product($value);
				}
			}

			// Deleting category images
			fn_delete_image_pairs($category_id, 'category');

			// Executing delete_category functions from active addons
			fn_set_hook('delete_category', $category_id);
		}

	   	return $category_ids; // Returns ids of deleted categories
	} else {
		return false;
	}
}

?>
