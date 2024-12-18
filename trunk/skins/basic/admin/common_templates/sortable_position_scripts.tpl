{* $Id: sortable_position_scripts.tpl 12452 2011-05-13 11:33:14Z alexions $ *}

<script type="text/javascript">
//<![CDATA[


$(document).ready(
	function() {$ldelim}

		var text_position_updating = '{$lang.text_position_updating}';
		var update_sortable_url = '{"tools.update_position?table=`$sortable_table`&id_name=`$sortable_id_name`"|fn_url:'A':'rel':'&'}';
		var positionids = [];

{literal}
		$('.cm-sortable').sortable( {
			accept: 'cm-sortable-row',
			containment: '.cm-sortable',
			items: '.cm-row-item',
			tolerance: 'pointer',
			opacity: 0.9,
			axis: 'vertically',
			start: function(elm) {
				var i = 0;
				positionids = [];
				$("*[class*='cm-sortable-id-']").each(function() {
					var matched = $(this).attr('class').match(/cm-sortable-id-([^\s]+)/i);
					positionids[i++] = matched[1];
				});
			},
			stop: function(elm) {
				var i = 0;
				var changed_positions = [];
				var changed_ids = [];
				$("*[class*='cm-sortable-id-']").each(function() {
					var matched = $(this).attr('class').match(/cm-sortable-id-([^\s]+)/i);
					if (positionids[i] != matched[1]) {
						changed_positions.push(i);
						changed_ids.push(matched[1]);
					}
					i++;
				});
				if (changed_ids.length > 0) {
					var data_obj = {};
					data_obj['positions'] = changed_positions.join(',');
					data_obj['ids'] = changed_ids.join(',');
					jQuery.ajaxRequest(update_sortable_url, {method: 'get', caching: false, message: text_position_updating, data: data_obj});
					return true;
				}
			}
		});
{/literal}
	{$rdelim}
);
//]]>
</script>
