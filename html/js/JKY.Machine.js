"use strict";

/**
 * JKY.Machine - process al layer functions
 *
 * require:	JKY.Utils.js(JKY.display_confirm)
 *
 * 		$(my_parent).find('.jky-machine-id'  ).val(the_id );
 *		$(my_parent).find('.jky-machine-name').val(my_name);
 */
JKY.Machine = function() {
	var my_the_id		= null;				//	external id that initiated the call
	var my_specific		= 'is_machine';		//	selected machine type: Punho, Gola, Galao
	var my_order_by		= 'name';
	var my_filter		= 'jky-machine-filter';
	var my_search_body	= 'jky-machine-search-body';
	var my_layer		= 'jky-machine-search';

	function my_display(the_id, the_specific) {
		my_the_id	= the_id;
		if (typeof the_specific	== 'undefined') {
			my_specific	= 'is_machine';
		}else{
			my_specific	= the_specific;
		}
		JKY.set_focus(my_filter);
		my_load_data();
	}

	function my_load_data() {
		var my_data =
			{ method	: 'get_index'
			, table		: 'Machines'
//			, specific	:  my_specific
			, select	: 'All'
			, filter	:  JKY.get_value(my_filter)
			, display	: '10'
			, order_by	:  my_order_by
			};
		JKY.ajax(false, my_data, my_load_data_success);
	}

	function my_load_data_success(response) {
		var my_rows	= response.rows;
		var my_html = '';
		for(var i=0; i<my_rows.length; i++) {
			var my_row = my_rows[i];
			my_html += '<tr onclick="JKY.Machine.click_row(this, ' + my_row.id + ')">'
					+  '<td class="jky-search-machine-name"	>' +				 my_row.name				+ '</td>'
					+  '<td class="jky-search-machine-type"	>' + JKY.fix_null	(my_row.machine_type	)	+ '</td>'
					+  '<td class="jky-search-machine-brand">' + JKY.fix_null	(my_row.machine_brand	)	+ '</td>'
					+  '</tr>'
					;
		}
		JKY.set_html(my_search_body, my_html);
		JKY.show_modal(my_layer);
	}

	function my_click_row(the_index, the_id) {
		var my_name = $(the_index).find('.jky-search-machine-name').html();
		var my_parent = $(my_the_id).parent();

		var my_dom_id = $(my_parent).find('#jky-machine-id');
		if (my_dom_id.length == 0) {
			my_dom_id = $(my_parent).find('.jky-machine-id');
		}
		my_dom_id.val(the_id );

		var my_dom_name = $(my_parent).find('#jky-machine-name');
		if (my_dom_name.length == 0) {
			my_dom_name = $(my_parent).find('.jky-machine-name');
		}
		my_dom_name.val(my_name);

		my_dom_name.change();		//	to activate change event
		JKY.hide_modal(my_layer);
	}

	function my_add_new() {
		JKY.display_message('add_new');
	}

	return {
		  display		: function(the_id, the_type)	{		my_display(the_id, the_type);}
		, load_data		: function()					{		my_load_data();}
		, click_row		: function(the_index, the_id)	{		my_click_row(the_index, the_id);}
		, add_new		: function()					{		my_add_new();}
	};
}();