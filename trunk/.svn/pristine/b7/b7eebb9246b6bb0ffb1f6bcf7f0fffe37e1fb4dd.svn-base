// $Id: func.js 12452 2011-05-13 11:33:14Z alexions $

$(document).bind('keyup', 'C', function(e) {
	return fn_quick_search_event(e);
});
$(document).bind('click', 'C', function(e) {
	return fn_quick_search_event(e);
});

$(window).load(function(){
	jform = $('.search-input:first').attr('id', 'quick_search').attr('autocomplete', 'off').addClass('cm-quick-search').parents('form');
	jform.bind('submit', function(e) {
		return fn_quick_search_event(e);
	});
});

var allow_submit = true;

function fn_quick_search_event(e)
{
	var jelm = $(e.target);
	var elm = e.target;
	
	if (e.type == 'keyup') {
		if (jelm.hasClass('cm-quick-search') && jelm.val().length >= qs_min_length) {
			params = {};
			params['q'] = jelm.val().replace(/(^\s+)|(\s+$)/g, "");
			params['result_ids'] = jelm.attr('id') + '_result';
			params['cid'] = $('.search-selectbox:first').val();
			
			search_elm = $('#' + params['result_ids']);
			control_buttons = {13: 'enter', 27: 'esc', 37: 'left', 38: 'up', 39: 'right' ,40: 'down'};
			
			if (typeof(fn_quick_search_event['text']) == 'undefined') {
				fn_quick_search_event['text'] = '';
			}
			
			var char_code = (e.which) ? e.which : e.keyCode;
			
			if (typeof(control_buttons[char_code]) != 'undefined') {
				if (char_code == 27) {
					fn_quick_search_hide(search_elm);
				} else if (char_code == 38 || char_code == 40) {
					row = $('.cm-search-row-selected', search_elm);
					
					if (row.length > 0) {
						if (char_code == 38) {
							new_row = row.prev();
						} else {
							new_row = row.next();
						}
					} else {
						if (char_code == 38) {
							direction = ':last';
						} else {
							direction = ':first';
						}
						
						new_row = $('.cm-search-row' + direction, search_elm);
					}
					
					$('.cm-search-row-selected', new_row.parent()).removeClass('cm-search-row-selected').addClass('cm-search-row');
					
					if ($('td', row).text() != $('td', new_row).text()) {
						new_row.removeClass('cm-search-row').addClass('cm-search-row-selected');
					}
					
					text = $('td', new_row).attr('text');
					text = text.replace(/(^\s+)|(\s+$)/g, "");
					
					if (text.length != 0) {
						jelm.val(text);
					} else {
						jelm.val(fn_quick_search_event['text']);
						$('.cm-search-row-selected').removeClass('cm-search-row-selected').addClass('cm-search-row');
					}
				} else if (char_code == 13) {
					row = $('.cm-search-row-selected', search_elm);
					if (row.length) {
						if ($('a', row).length) {
							href = $('a', row).attr('href');
							jQuery.redirect(href);
						}
					}
				}
				
			} else {
				fn_quick_search_event['text'] = jelm.val().replace(/(^\s+)|(\s+$)/g, "");
				fn_search_request('', params);
			}
		} else {
			fn_quick_search_hide($('#' + jelm.attr('id') + '_result'));
		}
	} else if (e.type == 'click' && jelm.parent().hasClass('cm-search-row') || jelm.parent().hasClass('cm-search-row-selected')) {
		if ($('a', jelm).length) {
			var href = $('a', jelm).attr('href');
			jQuery.redirect(href);
		} else {
			$('.cm-quick-search').val(jelm.attr('text').replace(/(^\s+)|(\s+$)/g, ''));
			$('[name=search_form]:first').submit();
		}
	} else if (e.type == 'submit') {
		row = $('.cm-search-row-selected');
		if (row.length) {
			if ($('a', row).length) {
				return false;
			}
		}
		
		return true;
	}
}

function fn_quick_search_hide(elm)
{
	elm.hide();
	$('.cm-search-row-selected' ,elm).removeClass('cm-search-row-selected').addClass('cm-search-row')
}

function fn_search_request(data, params)
{
	var show_tip = false;
	
	if (typeof(fn_search_request['url']) == 'undefined') {
		fn_search_request['url'] = '';
	}
	
	if (typeof(fn_search_request['locked']) == 'undefined') {
		fn_search_request['locked'] = false;
	}
	
	if (typeof(params['cid']) == 'undefined') {
		params['cid'] = $('.search-selectbox:first').val();
	}
	
	if (typeof(params['q']) == 'undefined') {
		fn_search_request['locked'] = false;
		show_tip = true;
		
	} else {
		fn_search_request['url'] = params['q'];
	}
	
	if (!fn_search_request['locked'] && fn_search_request['url'].length > 0) {
		delete(params['q']);
		fn_search_request['locked'] = true;
		
		$.ajaxRequest(index_script + '?dispatch=search.quick_search&cid=' + params['cid'], {
			result_ids: params['result_ids'],
			caching: false,
			data: {q: fn_search_request['url']},
			force_exec: true,
			hidden: true,
			callback: fn_search_request
		});
		
		delete(fn_search_request['url']);
	} else {
		fn_search_request['url'] = params['q'];
	}
	
	search_elm = $('#' + params['result_ids']);
	
	if (show_tip) {
		jelm = $('#' + params['result_ids'].str_replace('_result', ''));
		search_elm.css('left', jelm.offset().left).css('min-width', jelm.width()).css('top', jelm.offset().top + jelm.height() + 5).show();
	}
	
	return true;
}