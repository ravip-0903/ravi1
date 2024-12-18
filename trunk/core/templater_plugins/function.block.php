<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

function smarty_function_block($params, &$smarty)
{
	static $blocks;

	$display = true;
	if (!isset($blocks)) {
		$blocks = $smarty->get_var('blocks');
	}

	$main_group_id = 0;

	if (empty($params['group']) && empty($params['id'])) {
		return false;

	} elseif (!empty($params['group'])) {
		foreach ($blocks as $_block_id => $_block_data) {
			$blocks[$_block_id]['group_position'] = $params['group'];
			if ($_block_data['text_id'] == $params['group']) {
				$main_group_id = $_block_id;
			}
		}
		if (empty($main_group_id)) {
			return false;
		}

		$block_content = smarty_function_group_output($blocks, $blocks[$main_group_id], $smarty);

	} else {
		if (empty($blocks[$params['id']])) {
			return false;
		}
		$block_data = $blocks[$params['id']];
		if (!empty($params['no_box'])) {
			unset($block_data['properties']['wrapper']);
		}
		$block_content = smarty_function_block_output($block_data, $smarty);
	}

	if (empty($params['assign'])) {
		return $block_content;
	} else {
		$smarty->assign($params['assign'], $block_content, false);
	}
}

function smarty_function_group_output($blocks, $group_data, &$smarty)
{
	$group_content = '';

	foreach ($blocks as $_block_id => $_block_data) {
		if ($_block_data['group_id'] == $group_data['block_id']) {
			if ($group_data['text_id'] == 'product_details') {
				continue;
			}
			if ($_block_data['block_type'] == 'G') {
				$_content = smarty_function_group_output($blocks, $_block_data, $smarty);
			} else {
				$_content = smarty_function_block_output($_block_data, $smarty);
			}
			if (!empty($_content) && $group_data['properties']['block_order'] == 'H') {
				$_width = empty($_block_data['properties']['width']) ? '' : ' width="' . $_block_data['properties']['width'] . ($_block_data['properties']['width_unit'] == 'P' ? '%' : '') . '"';
				$_content = (empty($group_content) ? '' : '<td width="10"></td>') . '<td' . $_width . '>' . $_content . '</td>';
			}
			$group_content .= $_content;
		}
	}

	if (!empty($group_content)) {
		if ($group_data['properties']['block_order'] == 'H') {
			$group_content = '<table class="fixed-layout" width="100%" cellspacing="0" cellpadding="0" border="0"><tr valign="top">' . $group_content . '</tr></table>';
		}
		if (!empty($group_data['properties']['wrapper'])) { // if group is wrapped, display wrapper
			if (!empty($smarty->_smarty_vars['capture']['hide_wrapper'])) {
				$smarty->assign('hide_wrapper', true);
				unset($smarty->_smarty_vars['capture']['hide_wrapper']); // remove this flag
			}
			$smarty->assign('title', $group_data['description']);
			//$smarty->assign('var1', 'HELLO');
			$smarty->assign('content', $group_content, false);
			$group_content = $smarty->display($group_data['properties']['wrapper'], false);
		}
		return $group_content;
	} else {
		return '';
	}
}

function smarty_function_block_output($_block_data, &$smarty)
{
	// Added by Sudhir dt 24th Sept 2012 to hide these blocks except product view page

    if(!((CONTROLLER == 'products') AND (MODE == 'view'))){
		if (in_array($_block_data['block_id'], Registry::get('config.hide_block_id'))) {
			unset($_block_data);
		}
	} // Added by Sudhir dt 24th Sept 2012 end here
        if (Registry::get('config.mobile_perf_optimization')){
            if (in_array($_block_data['block_id'], Registry::get('config.hide_block_id_mobile'))) {
                    unset($_block_data);
            }   
        }
        
	if(CONTROLLER == 'products' && MODE == 'search')
	{
		//echo '<pre>';print_r($_block_data);echo '</pre>';
		if($_block_data['group_id']	== '2'){
			if($_block_data['block_id'] == '8'){
				unset($_block_data);	
			}
		}
	}
	
	if(CONTROLLER == 'categories' && MODE == 'view' && (isset($_REQUEST['features_hash'])) || isset($_REQUEST['company_id']))
	{
		//echo '<pre>';print_r($_block_data);echo '</pre>';
		if($_block_data['group_id']	== '3'){
			if($_block_data['block_id']	!= '7'){
				unset($_block_data);	
			}
		}
	}
	
	/*Modified by clues dev*/
	$cache_central = 'yes';
	/*Modified by chandan sharma to remove shopping options on product searh with category id*/	
	if(CONTROLLER == 'products' && MODE == 'search' && $_REQUEST['cid'] == '0')
	{
		//echo '<pre>';print_r($_block_data);echo '</pre>';
		if($_block_data['group_id']	== '2'){
			if($_block_data['block_id']	== '20'){
				unset($_block_data);	
			}
		}
	}
	/*Modified by chandan sharma to remove shopping options on product searh with category id*/	
	
	if(((CONTROLLER == 'products' && MODE == 'search')|| CONTROLLER == 'categories') && $_block_data['block_id'] == '7'){
		$cache_central = 'no';
	} 
	$q_string = $_SERVER['QUERY_STRING'];
    $q_string=  str_replace('&clean=scrp','',$q_string);
    $q_string=  str_replace('?clean=scrp','',$q_string);
    $q_string=  str_replace('clean=scrp','',$q_string);
    //$q_string = rtrim($q_string,'?');
    //$q_string = rtrim($q_string,'&');

	$non_cached_controller = array('wishlist','reward_points','rma','checkout','profiles','orders','auth','write_to_us');
	if((!in_array(CONTROLLER, $non_cached_controller)) && (Registry::get('config.memcache') && $GLOBALS['memcache_status']) && $cache_central == 'yes')
    {
        $memcache = $GLOBALS['memcache'];
        $key = md5($q_string.'--'.$_block_data['block_id'].'-blocks'.'-'.Registry::get('config.http_host'));
        $GLOBALS['memcache_key'][] = $key;

        if (isset($_REQUEST['clean']) 
        	&& $_REQUEST['clean'] == 'scrp' 
			&& !empty($_SESSION['auth']['user_id']) 
			&&  in_array($_SESSION['auth']['user_id'], Registry::get('config.memcache_reset_user_id'))) {
			// Unset the memcache store if the request variable 'clean' is set 
			// and a user is logged in.
			$memcache->delete($key);
		}

        if(($mem_value = $memcache->get($key)) !== false){
            $block_content = $mem_value;			
           	return $block_content;
        }else{
            $_tpl_vars = $smarty->_tpl_vars; // save state of original variables
			$display = true;
			if (!empty($_block_data['properties']['wrapper'])) { // if block is wrapped, display wrapper
				$display_tpl = $_block_data['properties']['wrapper'];
			}
		
			Registry::set('block_cache_generate', true);
		
			if (!empty($_block_data['text_id']) && $_block_data['text_id'] == 'central_content') {
				$block_content = $smarty->display($smarty->get_var('content_tpl'), false);
				if (!empty($display_tpl)) {
					if (!empty($smarty->_smarty_vars['capture']['hide_wrapper'])) {
						$smarty->assign('hide_wrapper', true);
						unset($smarty->_smarty_vars['capture']['hide_wrapper']); // remove this flag
					}
					$smarty->assign('title', !empty($smarty->_smarty_vars['capture']['mainbox_title']) ? $smarty->_smarty_vars['capture']['mainbox_title'] : '', false);
					$smarty->assign('content', $block_content, false);
					unset($block_content);
		
				} else {
					$display_tpl = $smarty->get_var('content_tpl');
				}
				$block_content = !empty($block_content) ? $block_content : $smarty->display($display_tpl, false);
		
			} else {
				$_template = !empty($_block_data['properties']['appearances']) ? $_block_data['properties']['appearances'] : ((!empty($_block_data['properties']['list_object']) && strpos($_block_data['properties']['list_object'], '.tpl') !== false) ? $_block_data['properties']['list_object'] : '');
		
				if (empty($_template)) {
					$memcache->set($key, "", MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time'));
					return '';
				}
		
				$use_cache = true;
				$data_function = $update_params_condition = '';
				$update_handlers = $update_params = array();
		
				if (strpos($_block_data['properties']['list_object'], '.tpl') === false || !empty($_block_data['properties']['items_function'])) {
					$properties = fn_get_block_properties($_block_data['properties']['list_object']);
					if (!empty($properties['data_function'])) {
						$data_function = $properties['data_function'];
					} elseif (!empty($_block_data['properties']['items_function'])) {
						$data_function = $_block_data['properties']['items_function'];
					} else {
						$data_function = 'fn_get_' . $_block_data['properties']['list_object'];
					}
		
				} else {
					$data_function = "_STATIC_TEMPLATE_BLOCK_";
				}
		
				if (Registry::get('config.tweaks.disable_block_cache') || Registry::get('settings.customization_mode') == 'Y' || Registry::get('settings.translation_mode') == 'Y' || isset($_block_data['properties']['static_block']) || defined('DISPLAY_FULL_PATHS')) {
					$use_cache = false;
				}
		
				if ($use_cache == true) {
					$cache_properties = fn_get_block_cache_properties();
					$update_handlers = $cache_properties['update_handlers'];
		
					if (!empty($cache_properties['data_functions'][$data_function]['templates'][$_template])) {
						$cache_function_properties = $cache_properties['data_functions'][$data_function]['templates'][$_template];
					} elseif (!empty($cache_properties['data_functions'][$data_function])) {
						$cache_function_properties = $cache_properties['data_functions'][$data_function];
					} elseif (!empty($properties['cache_properties'])) {
						$cache_function_properties = $properties['cache_properties'];
					} else {
						$cache_function_properties = false;
					}
		
					//Disable cache for data function
					if ($cache_properties['disable_cache'] == true || empty($cache_function_properties) || $cache_function_properties['disable_cache'] == true) {
						$use_cache = false;
					}
				}
		
				//Disable cache & update handlers for fillings & appearances & etc.
				if ($use_cache == true && !empty($_block_data['properties'])) {
					foreach ($_block_data['properties'] as $prop_name => $prop_val) {
						if (!empty($properties[$prop_name]) && !empty($properties[$prop_name][$prop_val])) {
		
							$property = $properties[$prop_name][$prop_val]; // fillings or appearances or etc.
		
							if (!empty($property['disable_cache'])) {
								$use_cache = false;
							}
		
							if (!empty($property['update_handlers'])) {
								$update_handlers = array_merge($update_handlers, $property['update_handlers']);
							}
		
							if (!empty($property['params']['request'])) {
								foreach ($property['params']['request'] as $param) {
									$param = strtolower(str_replace('%', '', $param));
									if (isset($_REQUEST[$param])) {
										$update_params_condition .= '|' . $param . '=' . md5(serialize($_REQUEST[$param]));
									}
								}
							}
							if (!empty($property['params']['session'])) {
								foreach ($property['params']['session'] as $param) {
									$param = strtolower(str_replace('%', '', $param));
									if (isset($_SESSION[$param])) {
										$update_params_condition .= '|' . $param . '=' . md5(serialize($_SESSION[$param]));
									}
								}
							}
							if (!empty($property['params']['auth'])) {
								foreach ($property['params']['auth'] as $param) {
									$param = strtolower(str_replace('%', '', $param));
									if (isset($_SESSION['auth'][$param])) {
										$update_params_condition .= '|' . $param . '=' . md5(serialize($_SESSION['auth'][$param]));
									}
								}
							}
							// Manually update condition
							if (!empty($property['update_params'])) {
								if (!empty($property['update_params']['request'])) {
									foreach ($property['update_params']['request'] as $param) {
										$param = strtolower(str_replace('%', '', $param));
										if (isset($_REQUEST[$param])) {
											$update_params_condition .= '|' . $param . '=' . $_REQUEST[$param];
										}
									}
								}
								if (!empty($property['update_params']['session'])) {
									foreach ($property['update_params']['session'] as $param) {
										$param = strtolower(str_replace('%', '', $param));
										if (isset($_SESSION[$param])) {
											$update_params_condition .= '|' . $param . '=' . $_SESSION[$param];
										}
									}
								}
								if (!empty($property['update_params']['auth'])) {
									foreach ($property['update_params']['auth'] as $param) {
										$param = strtolower(str_replace('%', '', $param));
										if (isset($_SESSION['auth'][$param])) {
											$update_params_condition .= '|' . $param . '=' . $_SESSION['auth'][$param];
										}
									}
								}
							}
						}
					}//-foreach
		
					if (!empty($_block_data['item_ids'])) {
						$update_params_condition .= '|' . 'item_ids' . '=' . $_block_data['item_ids'];
					}
				}
		
				fn_set_hook('smarty_function_block_output_pre_cache', $_block_data, $smarty, $use_cache, $update_handlers, $update_params_condition);
		
				if ($use_cache == true) {
					//update handlers for data function
					if (is_array($cache_function_properties['update_handlers'])) {
						$update_handlers = array_merge($update_handlers, $cache_function_properties['update_handlers']);
					}
		
					if (!empty($cache_function_properties['use_currency_cache_level'])) {
						$update_params_condition .= '|currency=' . CART_SECONDARY_CURRENCY;
					}
		
					$block_cache_name = 'block_' . CACHE_LEVEL_DAY . '_' . $_block_data['block_id'];
					$update_params_condition = !empty($update_params_condition)? md5($update_params_condition) : '';
		
					fn_set_hook('smarty_function_block_output_pre_register_cache', $_block_data, $smarty, $block_cache_name, $update_handlers, $update_params_condition);
		
					Registry::register_cache($block_cache_name, array_unique($update_handlers), CACHE_LEVEL_HTML_BLOCKS . '__' . $update_params_condition);
				}
		
				if ($use_cache == false || Registry::is_exist($block_cache_name) == false) {
		
					if ($data_function != "_STATIC_TEMPLATE_BLOCK_") {
						// This block is not static, so it is necessary to find its items
						$items = fn_get_block_items($_block_data, $properties);
						if (empty($items)) {
							$display = false;
						} else {
							$smarty->assign('items', $items);
						}
					}
		
					if ($display == true) {
		
						if ($smarty->template_exists($_template)) {
							if (strpos($_template, 'addons/') !== false) {
								$a = explode('/', $_template);
								if (fn_load_addon($a[1]) == false) { // do not display template of disabled addon
									$display = false;
								}
							}
						} else {
							$display = false;
						}
		
						if ($display == true) {
		
							$smarty->assign('block', $_block_data, false);
							// Pass extra parameters to smarty
		
							$block_content = $smarty->display($_template, false);
							if (!empty($display_tpl)) { // if wrapper exists, get block content
								if (trim($block_content)) {
									if (!empty($smarty->_smarty_vars['capture']['hide_wrapper'])) {
										$smarty->assign('hide_wrapper', true);
										unset($smarty->_smarty_vars['capture']['hide_wrapper']); // remove this flag
									}
									$smarty->assign('title', html_entity_decode($_block_data['description']));	
								$res=db_get_row("select csc.status,cscd.description from cscart_categories csc inner join cscart_category_descriptions cscd on csc.category_id=cscd.category_id where csc.category_id='".fn_get_lang_var('48hrsale_category_id')."' ");
								$smarty->assign('catg',$res);
								if(isset($_block_data['properties']['view_all_url']))
								{
									$smarty->assign('view_all', $_block_data['properties']['view_all_url']);
								}
								if(isset($_block_data['properties']['show_title']))
								{
									$smarty->assign('show_title', $_block_data['properties']['show_title']);
								}
								if(isset($_block_data['properties']['view_all_page_link']))
								{
									$smarty->assign('view_url', $_block_data['properties']['view_all_page_link']);
								}
								if(isset($_block_data['properties']['link1_url']))
								{
									$smarty->assign('link1', $_block_data['properties']['link1_url']);
								}
								if(isset($_block_data['properties']['link2_url']))
								{
									$smarty->assign('link2', $_block_data['properties']['link2_url']);
								}
								if(isset($_block_data['properties']['link3_url']))
								{
									$smarty->assign('link3', $_block_data['properties']['link3_url']);
								}
								if(isset($_block_data['properties']['link4_url']))
								{
									$smarty->assign('link4', $_block_data['properties']['link4_url']);
								}
								if(isset($_block_data['properties']['link1_text']))
								{
									$smarty->assign('link1_text', $_block_data['properties']['link1_text']);
								}
								if(isset($_block_data['properties']['link2_text']))
								{
									$smarty->assign('link2_text', $_block_data['properties']['link2_text']);
								}
								if(isset($_block_data['properties']['link3_text']))
								{
									$smarty->assign('link3_text', $_block_data['properties']['link3_text']);
								}
								if(isset($_block_data['properties']['link4_text']))
								{
									$smarty->assign('link4_text', $_block_data['properties']['link4_text']);
								}
								
								if(isset($_block_data['properties']['icon_image_url']))
								{
									$smarty->assign('icon_image', $_block_data['properties']['icon_image_url']);
								}
								if(isset($_block_data['properties']['punch_line_text']))
								{
									$smarty->assign('punch_line', $_block_data['properties']['punch_line_text']);
								}
									$smarty->assign('content', $block_content, false);
									
									unset($block_content);
								} else {
									$display = false;
								}
							} else {
								$display_tpl = $_template;
							}
						}
					}
		
					if ($display == true) {
						$block_content = !empty($block_content) ? $block_content : $smarty->display($display_tpl, false);
					}
		
					$cache = array(
						'block_content' => $block_content,
						'is_empty' => empty($block_content),
					);
		
					Registry::set($block_cache_name, $cache);
		
				} else {
					$cache = Registry::get($block_cache_name);
		
					$block_content = ($cache['is_empty'] == true)? '' : $cache['block_content'];
				}
			}
		
			$smarty->_tpl_vars = $_tpl_vars; // restore original vars
		
			Registry::del('block_cache_generate');
			$status = $memcache->set($key, $block_content, MEMCACHE_COMPRESSED, Registry::get('config.memcache_expire_time')); // or die ("Failed to save data at the server");
                        if(!$status){
                            $memcache->delete($key);
                        }
           	if ($display == true) {
				return trim($block_content);
			} else {
				return '';
			}
            
        }
    }else{        

		$_tpl_vars = $smarty->_tpl_vars; // save state of original variables
		$display = true;
		if (!empty($_block_data['properties']['wrapper'])) { // if block is wrapped, display wrapper
			$display_tpl = $_block_data['properties']['wrapper'];
		}
	
		Registry::set('block_cache_generate', true);
	
		if (!empty($_block_data['text_id']) && $_block_data['text_id'] == 'central_content') {
			$block_content = $smarty->display($smarty->get_var('content_tpl'), false);
			if (!empty($display_tpl)) {
				if (!empty($smarty->_smarty_vars['capture']['hide_wrapper'])) {
					$smarty->assign('hide_wrapper', true);
					unset($smarty->_smarty_vars['capture']['hide_wrapper']); // remove this flag
				}
				$smarty->assign('title', !empty($smarty->_smarty_vars['capture']['mainbox_title']) ? $smarty->_smarty_vars['capture']['mainbox_title'] : '', false);
				$smarty->assign('content', $block_content, false);
				unset($block_content);
	
			} else {
				$display_tpl = $smarty->get_var('content_tpl');
			}
			$block_content = !empty($block_content) ? $block_content : $smarty->display($display_tpl, false);
	
		} else {
			$_template = !empty($_block_data['properties']['appearances']) ? $_block_data['properties']['appearances'] : ((!empty($_block_data['properties']['list_object']) && strpos($_block_data['properties']['list_object'], '.tpl') !== false) ? $_block_data['properties']['list_object'] : '');
	
			if (empty($_template)) {
				return '';
			}
	
			$use_cache = true;
			$data_function = $update_params_condition = '';
			$update_handlers = $update_params = array();
	
			if (strpos($_block_data['properties']['list_object'], '.tpl') === false || !empty($_block_data['properties']['items_function'])) {
				$properties = fn_get_block_properties($_block_data['properties']['list_object']);
				if (!empty($properties['data_function'])) {
					$data_function = $properties['data_function'];
				} elseif (!empty($_block_data['properties']['items_function'])) {
					$data_function = $_block_data['properties']['items_function'];
				} else {
					$data_function = 'fn_get_' . $_block_data['properties']['list_object'];
				}
	
			} else {
				$data_function = "_STATIC_TEMPLATE_BLOCK_";
			}
	
			if (Registry::get('config.tweaks.disable_block_cache') || Registry::get('settings.customization_mode') == 'Y' || Registry::get('settings.translation_mode') == 'Y' || isset($_block_data['properties']['static_block']) || defined('DISPLAY_FULL_PATHS')) {
				$use_cache = false;
			}
	
			if ($use_cache == true) {
				$cache_properties = fn_get_block_cache_properties();
				$update_handlers = $cache_properties['update_handlers'];
	
				if (!empty($cache_properties['data_functions'][$data_function]['templates'][$_template])) {
					$cache_function_properties = $cache_properties['data_functions'][$data_function]['templates'][$_template];
				} elseif (!empty($cache_properties['data_functions'][$data_function])) {
					$cache_function_properties = $cache_properties['data_functions'][$data_function];
				} elseif (!empty($properties['cache_properties'])) {
					$cache_function_properties = $properties['cache_properties'];
				} else {
					$cache_function_properties = false;
				}
	
				//Disable cache for data function
				if ($cache_properties['disable_cache'] == true || empty($cache_function_properties) || $cache_function_properties['disable_cache'] == true) {
					$use_cache = false;
				}
			}
	
			//Disable cache & update handlers for fillings & appearances & etc.
			if ($use_cache == true && !empty($_block_data['properties'])) {
				foreach ($_block_data['properties'] as $prop_name => $prop_val) {
					if (!empty($properties[$prop_name]) && !empty($properties[$prop_name][$prop_val])) {
	
						$property = $properties[$prop_name][$prop_val]; // fillings or appearances or etc.
	
						if (!empty($property['disable_cache'])) {
							$use_cache = false;
						}
	
						if (!empty($property['update_handlers'])) {
							$update_handlers = array_merge($update_handlers, $property['update_handlers']);
						}
	
						if (!empty($property['params']['request'])) {
							foreach ($property['params']['request'] as $param) {
								$param = strtolower(str_replace('%', '', $param));
								if (isset($_REQUEST[$param])) {
									$update_params_condition .= '|' . $param . '=' . md5(serialize($_REQUEST[$param]));
								}
							}
						}
						if (!empty($property['params']['session'])) {
							foreach ($property['params']['session'] as $param) {
								$param = strtolower(str_replace('%', '', $param));
								if (isset($_SESSION[$param])) {
									$update_params_condition .= '|' . $param . '=' . md5(serialize($_SESSION[$param]));
								}
							}
						}
						if (!empty($property['params']['auth'])) {
							foreach ($property['params']['auth'] as $param) {
								$param = strtolower(str_replace('%', '', $param));
								if (isset($_SESSION['auth'][$param])) {
									$update_params_condition .= '|' . $param . '=' . md5(serialize($_SESSION['auth'][$param]));
								}
							}
						}
						// Manually update condition
						if (!empty($property['update_params'])) {
							if (!empty($property['update_params']['request'])) {
								foreach ($property['update_params']['request'] as $param) {
									$param = strtolower(str_replace('%', '', $param));
									if (isset($_REQUEST[$param])) {
										$update_params_condition .= '|' . $param . '=' . $_REQUEST[$param];
									}
								}
							}
							if (!empty($property['update_params']['session'])) {
								foreach ($property['update_params']['session'] as $param) {
									$param = strtolower(str_replace('%', '', $param));
									if (isset($_SESSION[$param])) {
										$update_params_condition .= '|' . $param . '=' . $_SESSION[$param];
									}
								}
							}
							if (!empty($property['update_params']['auth'])) {
								foreach ($property['update_params']['auth'] as $param) {
									$param = strtolower(str_replace('%', '', $param));
									if (isset($_SESSION['auth'][$param])) {
										$update_params_condition .= '|' . $param . '=' . $_SESSION['auth'][$param];
									}
								}
							}
						}
					}
				}//-foreach
	
				if (!empty($_block_data['item_ids'])) {
					$update_params_condition .= '|' . 'item_ids' . '=' . $_block_data['item_ids'];
				}
			}
	
			fn_set_hook('smarty_function_block_output_pre_cache', $_block_data, $smarty, $use_cache, $update_handlers, $update_params_condition);
	
			if ($use_cache == true) {
				//update handlers for data function
				if (is_array($cache_function_properties['update_handlers'])) {
					$update_handlers = array_merge($update_handlers, $cache_function_properties['update_handlers']);
				}
	
				if (!empty($cache_function_properties['use_currency_cache_level'])) {
					$update_params_condition .= '|currency=' . CART_SECONDARY_CURRENCY;
				}
	
				$block_cache_name = 'block_' . CACHE_LEVEL_DAY . '_' . $_block_data['block_id'];
				$update_params_condition = !empty($update_params_condition)? md5($update_params_condition) : '';
	
				fn_set_hook('smarty_function_block_output_pre_register_cache', $_block_data, $smarty, $block_cache_name, $update_handlers, $update_params_condition);
	
				Registry::register_cache($block_cache_name, array_unique($update_handlers), CACHE_LEVEL_HTML_BLOCKS . '__' . $update_params_condition);
			}
	
			if ($use_cache == false || Registry::is_exist($block_cache_name) == false) {
	
				if ($data_function != "_STATIC_TEMPLATE_BLOCK_") {
					// This block is not static, so it is necessary to find its items
					$items = fn_get_block_items($_block_data, $properties);
					if (empty($items)) {
						$display = false;
					} else {
						$smarty->assign('items', $items);
					}
				}
	
				if ($display == true) {
	
					if ($smarty->template_exists($_template)) {
						if (strpos($_template, 'addons/') !== false) {
							$a = explode('/', $_template);
							if (fn_load_addon($a[1]) == false) { // do not display template of disabled addon
								$display = false;
							}
						}
					} else {
						$display = false;
					}
	
					if ($display == true) {
	
						$smarty->assign('block', $_block_data, false);
						// Pass extra parameters to smarty
	
						$block_content = $smarty->display($_template, false);
						if (!empty($display_tpl)) { // if wrapper exists, get block content
							if (trim($block_content)) {
								if (!empty($smarty->_smarty_vars['capture']['hide_wrapper'])) {
									$smarty->assign('hide_wrapper', true);
									unset($smarty->_smarty_vars['capture']['hide_wrapper']); // remove this flag
								}
								$smarty->assign('title', $_block_data['description']);
								
								$res=db_get_row("select csc.status,cscd.description from cscart_categories csc inner join cscart_category_descriptions cscd on csc.category_id=cscd.category_id where csc.category_id='".fn_get_lang_var('48hrsale_category_id')."' ");
								$smarty->assign('catg',$res);
								if(isset($_block_data['properties']['view_all_url']))
								{
									$smarty->assign('view_all', $_block_data['properties']['view_all_url']);
								}
								if(isset($_block_data['properties']['show_title']))
								{
									$smarty->assign('show_title', $_block_data['properties']['show_title']);
								}
								if(isset($_block_data['properties']['view_all_page_link']))
								{
									$smarty->assign('view_url', $_block_data['properties']['view_all_page_link']);
								}
								
								if(isset($_block_data['properties']['link1_url']))
								{
									$smarty->assign('link1', $_block_data['properties']['link1_url']);
								}
								if(isset($_block_data['properties']['link2_url']))
								{
									$smarty->assign('link2', $_block_data['properties']['link2_url']);
								}
								if(isset($_block_data['properties']['link3_url']))
								{
									$smarty->assign('link3', $_block_data['properties']['link3_url']);
								}
								if(isset($_block_data['properties']['link4_url']))
								{
									$smarty->assign('link4', $_block_data['properties']['link4_url']);
								}
								if(isset($_block_data['properties']['link1_text']))
								{
									$smarty->assign('link1_text', $_block_data['properties']['link1_text']);
								}
								if(isset($_block_data['properties']['link2_text']))
								{
									$smarty->assign('link2_text', $_block_data['properties']['link2_text']);
								}
								if(isset($_block_data['properties']['link3_text']))
								{
									$smarty->assign('link3_text', $_block_data['properties']['link3_text']);
								}
								if(isset($_block_data['properties']['link4_text']))
								{
									$smarty->assign('link4_text', $_block_data['properties']['link4_text']);
								}
								
								if(isset($_block_data['properties']['icon_image_url']))
								{
									$smarty->assign('icon_image', $_block_data['properties']['icon_image_url']);
								}
								if(isset($_block_data['properties']['punch_line_text']))
								{
									$smarty->assign('punch_line', $_block_data['properties']['punch_line_text']);
								}
								$smarty->assign('content', $block_content, false);
								unset($block_content);
							} else {
								$display = false;
							}
						} else {
							$display_tpl = $_template;
						}
					}
				}
	
				if ($display == true) {
					$block_content = !empty($block_content) ? $block_content : $smarty->display($display_tpl, false);
				}
	
				$cache = array(
					'block_content' => $block_content,
					'is_empty' => empty($block_content),
				);
	
				Registry::set($block_cache_name, $cache);
	
			} else {
				$cache = Registry::get($block_cache_name);
	
				$block_content = ($cache['is_empty'] == true)? '' : $cache['block_content'];
			}
		}
	
		$smarty->_tpl_vars = $_tpl_vars; // restore original vars
	
		Registry::del('block_cache_generate');
	
		if ($display == true) {
			return trim($block_content);
		} else {
			return '';
		}
	}
}

?>
