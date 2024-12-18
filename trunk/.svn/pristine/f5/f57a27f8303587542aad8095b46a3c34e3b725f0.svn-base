// $Id: prettyphoto.previewer.js 12718 2011-06-21 10:46:14Z alexions $
/* previewer-description:text_prettyphoto */

jQuery.loadCss(['/lib/js/prettyphoto/css/prettyPhoto.css']);
jQuery.getScript(current_path + '/' + 'lib/js/prettyphoto/js/jquery.prettyPhoto.js');

$.data.cePreviewerMethods = {
	display: function(elm) {
		
		var inited = elm.data('inited');
		
		if (inited != true) {
			var elms = $('a[rel="' + rel + '"]');
			elms.data('inited', true);
			
			elms.prettyPhoto();
			elm.click();
		}
	}
}