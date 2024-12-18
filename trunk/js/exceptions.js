// $Id: exceptions.js 12452 2011-05-13 11:33:14Z alexions $

function fn_change_options(obj_id, id, option_id)
{
	// Change cart status
	cart_changed = true;
	
	params = [];
	update_ids = [];
	cache_query = true;
	
	parents = $('.cm-reload-' + obj_id);
	jQuery.each(parents, function(id, parent_elm) {
		update_ids.push(parent_elm.getAttribute('id'));
		
		elms = $(':input:not(:radio):not(:checkbox)', parent_elm);
		jQuery.each(elms, function(id, elm) {
			if (elm.type != 'submit' && elm.type != 'file' && !($(this).hasClass('cm-hint') && elm.value == elm.defaultValue) && elm.name.length != 0) {
				if (elm.name == 'no_cache' && elm.value) {
					cache_query = false;
				}
				params.push({name: elm.name, value: elm.value});
			}
		});
	});
	
	radio = $(':radio:checked, :checkbox', parents);
	jQuery.each(radio, function(id, elm) {
		value = elm.value;
		if ($(elm).is(':checkbox:checked')) {
			if (!$(elm).hasClass('cm-no-change')) {
				value = $(elm).val();
			}
		} else if ($(elm).is(':checkbox')) {
			if (!$(elm).hasClass('cm-no-change')) {
				value = 'unchecked';
			} else {
				value = '';
			}
		}
		
		params.push({name: elm.name, value: value});
	});
	
	url = fn_url('products.options?changed_option[' + id + ']=' + option_id);
	
	for (i in params) {
		url += '&' + params[i]['name'] + '=' + escape(params[i]['value']);
	}
	
	jQuery.ajaxRequest(url, {
		result_ids: update_ids.join(',').toString(),
		caching: cache_query,
		force_exec: true,
		pre_processing: fn_pre_process_form_files,
		callback: fn_post_process_form_files,
		method: 'post'
	});
	
}

function fn_set_option_value(id, option_id, value)
{
	elm = $('#option_' + id + '_' + option_id);
	if (elm.attr('type') == 'select-one') {
		elm.val(value).change();
	} else {
		elms = $('#option_' + id + '_' + option_id + '_group');
		$(':radio[value=' + value + ']', elms).click();
	}

	return true;
}

function fn_pre_process_form_files(data, params)
{
	if (data.html) {
		// Create temporarily div element
		$('body').append('<div id="file_container" class="hidden"></div>');
		var container = {};
		container = $('#file_container');
		
		// Move files blocks to the temporarily created container
		for (var k in data.html) {
			$('#' + k + ' .fileuploader').each(function(idx, elm){
				jelm = $(elm);
				jparent = jelm.parents('.form-field');
				jparent.appendTo(container);
				jparent.attr('id', 'moved_' + jparent.attr('id'));
			});
		}
	}
}

function fn_post_process_form_files(data, params)
{
	var container = {};
	container = $('#file_container');
	
	$('div.form-field', container).each(function(idx, elm){
		jelm = $(elm);
		elm_id = jelm.attr('id').replace('moved_', '');
		target = $('#' + elm_id);
		target.html('');
		jelm.children().appendTo(target);
	});
	
	container.remove();
	if($(window).width()<630 && $(".mobile-slider-cntnr").length>0){
		jQuery_1_10_2(".mobile-slider").owlCarousel(optionsOwl_product);
		// Custom Navigation Events
		jQuery_1_10_2(".mobile-slider-cntnr .jcarousel-next").click(function(){
		var owl = jQuery_1_10_2(this).parent().find(".mobile-slider");
		owl.trigger('owl.next');
		});
		jQuery_1_10_2(".mobile-slider-cntnr .jcarousel-prev").click(function(){
		var owl = jQuery_1_10_2(this).parent().find(".mobile-slider");
		owl.trigger('owl.prev');
		});
	}
}

function fn_change_variant_image(prefix, opt_id, var_id)
{
	var images = {};
	images = $('[id*=variant_image_' + prefix + '_' + opt_id + ']');
	images.removeClass('product-variant-image-selected').addClass('product-variant-image-unselected');
	
	if (typeof(var_id) == 'undefined') {
		var_id = $('select[id*=_' + prefix + '_' + opt_id + ']').val();
	}
	$('[id*=variant_image_' + prefix + '_' + opt_id + '_' + var_id + ']').removeClass('product-variant-image-unselected').addClass('product-variant-image-selected');
}
