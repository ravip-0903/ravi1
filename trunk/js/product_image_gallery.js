(function($) {
	$.ceProductImageGallery = function() {
		var VISIBLE_THUMBNAILS = 3;
		var pth = $('#product_thumbnails');
		var th_len = $('li', pth).length;
	
		if (th_len > VISIBLE_THUMBNAILS)	{
			var i_width = $('.cm-thumbnails-mini', pth).outerWidth(true);
			var c_width = i_width * VISIBLE_THUMBNAILS;
			var i_height = $('.cm-thumbnails-mini', pth).outerHeight(true);
	
			pth.jcarousel({
				scroll: 1,
				wrap: 'circular',
				animation: 'fast',
				initCallback: $.ceScrollerMethods.fn_scroller_init_callback,
				// Obsolete code for new jCaroucel
				/*itemVisibleOutCallback: {
					onAfterAnimation: $.ceScrollerMethods.fn_scroller_next_callback, 
					onBeforeAnimation: $.ceScrollerMethods.fn_scroller_prev_callback
				},*/
				itemFallbackDimension: i_width,
				item_width: i_width,
				item_height: i_height,
				clip_width: c_width,
				clip_height: i_height,
				buttonNextHTML: '<div></div>',
				buttonPrevHTML: '<div></div>',
				buttonNextEvent: 'click',
				buttonPrevEvent: 'click',
				size: th_len
			});
			$('.jcarousel-skin').css({
				'width': c_width + $('.jcarousel-prev-horizontal').outerWidth(true) * 2 + 'px'
			});
		}
	
		pth.click(function(e) {
			var jelm = $(e.target);
			var pjelm;
			
			// Check elm clicking
			var in_elm = jelm.parents('li');
			if (in_elm.length == 0 && !jelm.is('img')) {
				return false;
			}
	
			if (jelm.hasClass('cm-thumbnails-mini') || (pjelm = jelm.parents('a:first.cm-thumbnails-mini'))) {
				jelm = (pjelm && pjelm.length) ? pjelm : jelm;
	
				$('a[rev="preview[product_images]"]').each(function() {
					var id = $(this).attr('id');
					var c_id = jelm.attr('id').str_replace('_mini', '');
	
					if (id == c_id) {
						$('.cm-thumbnails-mini', pth).removeClass('cm-cur-item');
						jelm.addClass('cm-cur-item');
						$(this).show();
						$('#box_' + id).show();
					} else {
						$(this).hide();
						$('#box_' + id).hide();
					}
				});
			}
		});
	
		pth.show();
	}
})(jQuery)