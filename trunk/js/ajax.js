jQuery.extend({
	ajaxRequest: function(url, params)
	{

		params = params || {};
		params.method = params.method || 'get';
		params.callback = params.callback || {};
		params.pre_processing = params.pre_processing || {};
		params.data = params.data || {};
		params.message = params.message || lang.loading;
		params.caching = params.caching || false;
		params.hidden = params.hidden || false;
		params.repeat_on_error = params.repeat_on_error || false;
		params.low_priority = params.low_priority || false;
		params.force_exec = params.force_exec || false;
		params.obj = params.obj || null;
		params.append = params.append || null;
		params.result_ids = params.result_ids || null;
		
		var QUERIES_LIMIT = 1;

		if (jQuery.active_queries >= QUERIES_LIMIT) { // if we have queries in the queue, push request to it
			if (params.low_priority == true) {
				jQuery.queries_stack.push(function() {
					jQuery.ajaxRequest(url, params);
				});
			} else {
				jQuery.queries_stack.unshift(function() {
					jQuery.ajaxRequest(url, params);
				});
			}

			return true;
		}

		// If query is not hidden, display loading box
		if (params.hidden == false) {
			jQuery.toggleStatusBox('show', params.message);
		}

		var hash = '';
		if (params.caching == true) {
			hash = jQuery.crc32(url + params.result_ids + jQuery.param(params.data));
		}

		if (!hash || !jQuery.ajax_cache[hash]) {
			url = fn_query_remove(url, 'result_ids');

			// Add result IDs to data
			if (params.result_ids) {
				params.data.result_ids = params.result_ids;
			}

			// If this param is set, all result IDs populated with whole retrieved content
			if (params.skip_result_ids_check) {
				params.data.skip_result_ids_check = params.skip_result_ids_check;
			}
			
			// Check, if we need to save all the input fields values from the updated element
			var saved_data = [];
			var result_ids = '';
			
			for (i in params.data) {
				if (i == 'result_ids') {
					result_ids = params.data[i].split(',');
					break;
					
				} else if (params.data[i] && typeof(params.data[i]['name']) != 'undefined' && params.data[i]['name'] == 'result_ids') {
					result_ids = params.data[i]['value'].split(',');
					break;
				}
			}
			
			if (result_ids.length > 0) {
				for (j in result_ids) {
					var container = $('#' + result_ids[j]);
					if (container.hasClass('cm-save-fields')) {
						saved_data[result_ids[j]] = $(':input:visible', container).serializeArray();
					}
				}
				params.saved_data = saved_data;
			}
			
			if (url) {
				if (params.obj && params.obj.hasClass('cm-comet')) {
					jQuery.ajaxSubmit(params.obj, null, {url: url, result_ids: result_ids});
				} else {
					jQuery.active_queries++;
					return jQuery.ajax({
						type: params.method,
						url: url,
						dataType: 'json',
						cache: true,
						data: params.data,
						success: function(data, textStatus) {
							if (hash) { // cache response
								jQuery.ajax_cache[hash] = data;
							}

							jQuery.ajaxResponse(data, params);
						},
						error: function(XMLHttpRequest, textStatus, errorThrown) {
							// Repeat the query on the error response
							if (params.repeat_on_error) {
								params.repeat_on_error = false;
								jQuery.ajaxRequest(url, params);
								
								return false;
							}
							
							// Hide loading box
							jQuery.toggleStatusBox('hide');

							// If query is not hidden, display error notice
							if (params.hidden == false) {
								var err_msg = lang.error_ajax.str_replace('[error]', (textStatus ? textStatus : lang.error));
								jQuery.showNotifications({'data': {'type': 'E', 'title': lang.error, 'message': err_msg, 'save_state': false}});
							}
						},
						complete: function(XMLHttpRequest, textStatus) {

							jQuery.active_queries--;
							if (jQuery.queries_stack.length) {
								var f = jQuery.queries_stack.shift();
								f();
							}
						}
					});
				}
			}

		} else if (hash && jQuery.ajax_cache[hash]) {
			jQuery.ajaxResponse(jQuery.ajax_cache[hash], params);
		}
	},

	ajaxSubmit: function(obj, elm, params)
	{
		var callback = 'fn_form_post_' + obj.attr('name');
		var f_callback = window[callback] || null;
		var REQUEST_XML = 1;
		var REQUEST_IFRAME = 2;
		var REQUEST_COMET = 3;
		var is_comet = obj.hasClass('cm-comet') || (elm && elm.hasClass('cm-comet'));


		// Enable form fields after ajax request
		if (obj.is('form')) {
			f_callback = function(data, params) {
				if (obj.hasClass('cm-disable-empty')) {
					$('input:text[value=""]', obj).removeAttr('disabled');
				}
				
				if (obj.hasClass('cm-disable-empty-files')) {
					$('input:file[value=""]', obj).removeAttr('disabled');
				}

				if (window[callback]) {
					window[callback](data, params);
				}
			}
		}

		jQuery.processNotifications();

		if (is_comet || obj.attr('enctype') == 'multipart/form-data') {
			if (!$('iframe[name=upload_iframe]').length) {
				$('<iframe name="upload_iframe" src="javascript: false;" class="hidden"></iframe>').appendTo('body');
				
				$('#comet_container').ceProgress('init');
				
				$('iframe[name=upload_iframe]').load(function() {
					$('#comet_container').ceProgress('finish');
					
					var response = {};
					if ($(this).contents().text() != null) {
						eval('var response = ' + $(this).contents().find('textarea').val());
					}

					response = response || {};

					jQuery.ajaxResponse(response, {callback: f_callback});

					if ($(this).contents().find('textarea').val() != undefined) {
						jQuery.comet_active = false;
					}
				});
			}

			if (obj.is('form')) {
				obj.append('<input type="hidden" name="is_ajax" value="' + (is_comet ? REQUEST_COMET :  REQUEST_IFRAME) + '" />');
				obj.attr('target', 'upload_iframe');
				jQuery.comet_active = true;
				jQuery.ajaxRequest('', null);
			}

			if (is_comet && !obj.is('form')) {
				$('iframe[name=upload_iframe]').attr('src', params.url + '&result_ids=' + params.result_ids + '&is_ajax=' + REQUEST_COMET);
			}

			return true;
		} else {
			var hash = $(':input', obj).serializeArray();

			// Send name/value of clicked button
			hash.push({name: elm.attr('name'), value: elm.val()});

			var aj = jQuery.ajaxRequest(obj.attr('action'), {
				method: obj.attr('method'),
				data: hash,
				callback: f_callback,
				force_exec: obj.hasClass('cm-ajax-force') ? true : false
			});
	
			return false;
		}
	},

	ajaxResponse: function(response, params)
	{
		params = params || {};
		params.force_exec = params.force_exec || false;
		params.callback = params.callback || {};
		params.pre_processing = params.pre_processing || {};
		
		var regex_all = new RegExp('<script[^>]*>([\u0001-\uFFFF]*?)</script>', 'img');
		var matches = [];
		var match = '';
		var elm;
		var data = response.data || {};

		// If pre processing function passed, run it
		if (params.pre_processing) {
			if (typeof(params.pre_processing) == 'function') { // call ordinary function
				params.pre_processing(data, params);
			} else if (params.pre_processing[1]) { // call object method [obj, 'meth']
				params.pre_processing[0][params.pre_processing[1]](data, response.text, params);
			}
		}

		// Ajax request forces browser to redirect
		if (data.force_redirection) {
			jQuery.redirect(data.force_redirection);
		}

		// Data returned that should be inserted to DOM
		if (data.html) {
			for (var k in data.html) {
				elm = $('#' + k);
				if (elm.length != 1) {
					continue;
				}

				// If returned data contains forms and we're inside the form, move it to body
				if (data.html[k].indexOf('<form') != -1 && elm.parents('form').length) {
					$('body').append(elm);
				}

				matches = data.html[k].match(regex_all);

				if (params.append) {
					elm.append(matches ? data.html[k].replace(regex_all, '') : data.html[k]);
				} else {
					elm.html(matches ? data.html[k].replace(regex_all, '') : data.html[k]);
				}

				// Restore saved data
				if (typeof(params.saved_data) != 'undefined' && typeof(params.saved_data[k]) != 'undefined') {
					
					var elements = [];
					for (var i in params.saved_data[k]) {
						elements[params.saved_data[k][i]['name']] = params.saved_data[k][i]['value'];
					}
					
					$('input:visible, select:visible', elm).each(function(id, local_elm) {
						jelm = $(local_elm);
						
						if (typeof(elements[jelm.attr('name')]) != 'undefined') {
							if (jelm.attr('type') == 'radio') {
								if (jelm.attr('value') == elements[jelm.attr('name')]) {
									jelm.attr('checked', 'checked');
								}
							} else {
								jelm.val(elements[jelm.attr('name')]);
							}
							jelm.trigger('change');
						}
					});
				}
				
				// Display/hide hidden block wrappers
				if (jQuery.trim(elm.html())) {
					elm.parents('.hidden.cm-hidden-wrapper').removeClass('hidden');
				} else {
					elm.parents('.cm-hidden-wrapper').addClass('hidden');
				}

				// If returned data contains scripts, execute them
				// first - collect all external scripts, execute them
				// for the last external script add a onload callback to run inline script
				if (matches) {
					var s_sources = [];
					jQuery.loaded_scripts = jQuery.loaded_scripts || [];

					for (var i = 0; i < matches.length; i++ ) {
						var m = $(matches[i]);

						if (m.attr('src')) {
							var _src = jQuery.getBaseName(m.attr('src'));
							var script_idx = jQuery.inArray(_src, jQuery.loaded_scripts);
							if (m.hasClass('cm-ajax-force') && script_idx != -1) {
								jQuery.loaded_scripts[script_idx] = null;
								script_idx = -1;
							}
							if (script_idx == -1) {
								s_sources.push(m.attr('src'));
							}
						} else {
							var _hash = jQuery.crc32(m.html());
							var _execute = false;
							if (!this.eval_cache[_hash] || params.force_exec || m.hasClass('cm-ajax-force')) {
								this.eval_cache[_hash] = true;
								if (!s_sources.length) {
									jQuery.globalEval(m.html());
								} else {
									_execute = true;
								}
							}

							if (s_sources.length) {
								var list = [];
								for (var _i = 0; _i < s_sources.length ; _i++) {
									list.push($.getScript(s_sources[_i]));
								}

								if (_execute == true) {
									(function(s) {
										$.when.apply(null, list).then(function() {
											jQuery.globalEval(s);
										});
									})(m.html());
								}

								s_sources = [];
							}
						}
					}

					if (s_sources.length) {
						for (var _i = 0; _i < s_sources.length ; _i++) {
							$.getScript(s_sources[_i]);
						}
					}
				}

				// Init tabs
				$(".cm-j-tabs", elm).each(function(){ $(this).idTabs(); });

				// if returned data contains forms, process them
				if (data.html[k].indexOf('<form') != -1 || data.html[k].indexOf('<!--processForm') != -1) {
					jQuery.processForms(elm);
				}

				if (elm.parents('form').length) {
					elm.parents('form:first').highlightFields();
				}

				$('.cm-hide-inputs').disableFields();

				if (elm.data('callback')) {
					elm.data('callback')();
					elm.removeData('callback');
				}
				
				if (data.html[k].indexOf('cm-ajax-content-more') != -1) {
					$('.cm-ajax-content-more', elm).each(function() {
						var self = $(this);
						self.appear(function() {
							jQuery.loadAjaxContent(self);
						}, {
							one: false,
							container: '#scroller_' + self.attr('rev')
						});
					});
				}
			}
		}

		// Display notification
		if (data.notifications) {
			jQuery.showNotifications(data.notifications);
		}

		// If callback function passed, run it
		if (params.callback) {
			if (typeof(params.callback) == 'function') { // call ordinary function
				params.callback(data, params);
			} else if (params.callback[1]) { // call object method [obj, 'meth']
				params.callback[0][params.callback[1]](data, response.text, params);
			}
		}

		// Rebuild floating buttons
		$.ceFloatingBar();

		// Hide loading box
		jQuery.toggleStatusBox('hide');
	},

	getBaseName: function (path)
	{
		return path.split('/').pop();
	},

	ajax_cache: {},
	queries_stack: [],
	active_queries: 0,
	comet_active: false,//flag for automatic tests
	eval_cache: {}
});

function fn_query_remove(query, vars)
{
	if (typeof(vars) == 'undefined') {
		return query;
	}
	if (typeof vars == 'string') {
		vars = [vars];
	}
	var start = query;
	if (query.indexOf('?') >= 0) {
		start = query.substr(0, query.indexOf('?') + 1);
		var search = query.substr(query.indexOf('?') + 1);
		var srch_array = search.split("&");
		var temp_array = new Array();
		var concat = true;
		var amp = '';

		for (var i = 0; i < srch_array.length; i++) {
			temp_array = srch_array[i].split("=");
			concat = true;
			for (var j = 0; j < vars.length; j++) {
				if (vars[j] == temp_array[0] || temp_array[0].indexOf(vars[j]+'[') != -1) {
					concat = false;
					break;
				}
			}
			if (concat == true) {
				start += amp + temp_array[0] + '=' + temp_array[1];
			}
			amp = '&';
		}

	}
	return start;
};


// Override default ajax method to get count of loaded scripts
(function($) {
	var ajax = $.ajax;
	// override default to get count
	$.ajax = function( origSettings ) {

		if (!$.loaded_scripts) {
			$.loaded_scripts = [];
			$('script').each(function() {
				var _src = $(this).attr('src');
				if (_src) {
					$.loaded_scripts.push($.getBaseName(_src));
				}
			});
		}

		if (origSettings.dataType && origSettings.dataType == 'script') {
			var _src = $.getBaseName(origSettings.url);
			if ($.inArray(_src, $.loaded_scripts) != -1) {
				return false;
			}

			$.loaded_scripts.push($.getBaseName(origSettings.url));
		}

		return ajax(origSettings);
	};

	if (typeof(ajax_callback_data) != 'undefined' && ajax_callback_data) {
		jQuery.globalEval(ajax_callback_data);
		ajax_callback_data = false;
	};

	$.getScript = function(url, callback){
		return $.ajax({
			type: "GET",
			url: url,
			success: callback,
			dataType: "script",
			cache: true
		});
	};
})(jQuery);