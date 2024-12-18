// $Id: ckeditor.editor.js 12581 2011-06-01 07:10:06Z alexions $
/* editior-description:text_ckeditor */

$.data.ceEditorMethods = {
	runEditor: function(elm) {
		CKEDITOR_BASEPATH = current_path + '/lib/js/ckeditor/';
		
		if (typeof(window.CKEDITOR) == 'undefined') {
			$.ceEditor('state', 'loading');
			return $.getScript(current_path + '/lib/js/ckeditor/ckeditor.js', function() {
				$.ceEditor('state', 'loaded');
				elm.ceEditor('run');
			});
		}
			
		CKEDITOR.replace(elm.attr('id'), {
			toolbar: 'Custom',
			toolbar_Custom: [['Format','Font','FontSize', 'Bold','Italic','Underline','TextColor','BGColor','-','Link','Image','-','NumberedList','BulletedList','Indent','Outdent','JustifyLeft','JustifyCenter','JustifyRight','-','Source']],
			bodyClass: 'wysiwyg-content',
			contentsCss: [current_path + '/skins/' + skin_name_customer + '/customer/styles.css', current_path + '/skins/' + skin_name + '/admin/wysiwyg_reset.css'],
			filebrowserBrowseUrl : current_path + '/lib/js/elfinder/elfinder.ckeditor.html',
			filebrowserWindowWidth : '600',
			filebrowserWindowHeight : '500'
		});

	},

	destroyEditor: function(elm) {
		CKEDITOR.instances[elm.attr('id')].destroy();
	},

	recoverEditor: function(elm) {
		$.data.ceEditorMethods.runEditor(elm);
	}
}