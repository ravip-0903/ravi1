{* $Id: sortable_scripts.tpl 12724 2011-06-21 12:48:57Z zeke $ *}

<script type="text/javascript">
//<![CDATA[
(function($){$ldelim}
	var text_position_updating = '{$lang.text_position_updating|escape:"javascript"}';
	var selected_section = '{$location}';
	var update_pos_url = '{"block_manager.save_layout"|fn_url:"A":"rel":"&"}';
	var block_object_id = '{$object_id}';
	var h_height = 100;
	var h_width = 300;

{literal}

	var methods = {
		init: function() {
			$('.cm-sortable-items').sortable({
				placeholder: 'ui-select',
				handle: 'h4:not(.cm-fixed-block)',
				tolerance: 'intersect',
				items: '> .cm-list-box', // only direct children are allowed to be sortable items
				opacity: 0.5,
				helper: function(e, elm) {
					var drag_height = $(elm).height() > h_height ? h_height : $(elm).height();
					var jelm = $('<div class="ui-drag"></div>');
					jelm.css({'height': drag_height, 'width' : h_width});

					return jelm;
				},
				stop: function(elm) {
					$('div.cm-sortable-items').each(function() {
						$('.cm-list-box', this).length == 2 ? $('p.no-items', this).show() : $('p.no-items', this).hide();
					});

					methods.save_positions();
				},
				start: function(e, ui) {
					// we can't drag group to another group
					var selector = ui.item.hasClass('cm-group-box') ? '.cm-sortable-items:not(.cm-decline-group)' : '.cm-sortable-items';

					$(this).sortable('option', 'connectWith', selector);
					$(this).sortable('refresh');
				}
			});
		},

		save_positions: function(user_choice) {
			var positions = [];
			var str_positions;

			$('.grab-items').each(function() {
				var self = this;
				var group_id = $('input[name=group_id_' + self.id + ']').val();
				if (!positions[group_id]) {
					positions[group_id] = [];
				}
				$('#' + self.id + ' :input').filter('.block-position').each(function() {
					if ($(this).parents('.grab-items:first').attr('id') == self.id) {
						positions[group_id].push($(this).val());
					}
				});
			});

			var data_obj = {};
			for (var section in positions) {
				if (positions[section].length) {
					data_obj['block_positions[' + section.str_replace('block_content_', '') + ']'] = positions[section].join(',');
				}
			}
			data_obj['add_selected_section'] = selected_section;
			data_obj['object_id'] = block_object_id;
			if (typeof(user_choice) != 'undefined') {
				data_obj['user_choice'] = user_choice;
			}

			jQuery.ajaxRequest(update_pos_url, {method: 'post', caching: false, message: text_position_updating, data: data_obj, callback: function(data) {
				if (typeof(data.confirm_text) != 'undefined') {
					methods.save_positions(confirm(data.confirm_text) ? 'Y' : 'N');
				}
			}});

			return true;
		}
	}


	$(document).ready(function(){
		methods.init();
	});
{/literal}
{$rdelim})(jQuery);
//]]>
</script>
