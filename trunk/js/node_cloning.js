jQuery.fn.extend({
	//
	// Adds the tag
	//
	// @level - level in variable name that should be replaced
	// @clone - if set, the field values will be copied
	// E.g. (replace on '30')
	// level = 1, varname = data[20][sub][50] - after replacement data[30][sub][50]
	// level = 3, varname = data[20][sub][50] - after replacement data[20][sub][30]

	cloneNode: function(level, clone, before)
	{
		var before = before || false;
		var clone = clone || false;

		var self = $(this);
		var regex = new RegExp('((?:\\[\\w+\\]){' + (level - 1) + '})\\[(\\d+)\\]');
		var image_regex = new RegExp('((?:\\[\\w+\\]){0})\\[(\\d+)\\]');

		if (window['_counter']) {
			window['_counter']++;
		} else {
			window['_counter'] = 1;
		}
		new_id = self.attr('id') + '_' + window['_counter'];

		var new_node = self.clone();
		new_node.attr('id', new_id);

		$('select', new_node).each(function(ind) { // copy values of selectboxes
			$(this).val($('select', self).eq(ind).val());
		});

		$('textarea', new_node).each(function(ind) { // copy values of textareas
			$(this).val($('textarea', self).eq(ind).val());
		});

		// Remove all script tags
		$('script', new_node).remove();

		// Remove all picker tags
		$('.cm-picker', new_node).remove();

		// Correct Ids
		$('[id],[for],[rev]', new_node).each(function() {
			var self = $(this);
			var attrs = ['id', 'for', 'rev'];
			var id = '';
			for (var k in attrs) {
				id = self.attr(attrs[k]);
				if (id) {
					self.attr(attrs[k], id + '_' + window['_counter']);
				}
			}
		});

		// Update elements
		$('[name]', new_node).each(function() {
			var self = $(this);
			var name = self.attr('name');
			var it = 0;
			var matches = name.match(/(\[\d+\]+)/g);

			// Increment array index
			if (matches) {
				name = name.replace(self.hasClass('cm-image-field') ? image_regex : regex, '$1[#HASH#]'); // Magic... parseInt does not work for $2 in replace method...
				self.attr('name', name.str_replace('#HASH#', parseInt(RegExp.$2) + window['_counter']));
			}

			// Set default values
			if (clone == false) {
				if (self.is(':checkbox,:radio')) {
					self.attr('checked', self.get(0).defaultChecked ? 'checked' : '');
				} else if (self.is(':input') && self.attr('type') != 'hidden') {
					if (self.attr('name') != 'submit') {
						self.val('');
					}
				}
			}

			// Display enabled remove button
			if (name == 'remove') {
				self.addClass('hidden');
				self.next().removeClass('hidden');
			}
		});

		// magic increment for checkbox element classes like add-0 -> add-1 (to fix check_all microformat work)
		$(':checkbox[class]', new_node).each(function() {
			if (this.name == 'check_all') {
				var m = this.className.match(/cm-check-items-([\w]*)-(\d+)/);
				$(this).removeClass('cm-check-items-' + m[1] + '-' + m[2]).addClass('cm-check-items-' + m[1] + '-' + (parseInt(m[2]) + window['_counter']));

				$(':checkbox.cm-item-' + m[1] + '-' + m[2], new_node).each(function() {
					$(this).removeClass('cm-item-' + m[1] + '-' + m[2]).addClass('cm-item-' + m[1] + '-' + (parseInt(m[2]) + window['_counter']));
				});

				return false;
			}
		});

		// Insert node into the document
		if (before == true) {
			self.before(new_node);
		} else {
			self.after(new_node);
		}

		// if node has file uploader, process it
		$('[id^=clean_selection]', new_node).each(function() {

			var type_id = this.id.str_replace('clean_selection', 'type');
			if ($('#' + type_id).val() == 'local' || clone == false){
				fileuploader.clean_selection(this.id);
			}
		});

		return new_id;
	},

	//
	// Remove the tag
	//
	removeNode: function()
	{
		var self = $(this);
		if (!self.prev().length || self.hasClass('cm-first-sibling')) {
			return false;
		}

		self.remove();
	}
});