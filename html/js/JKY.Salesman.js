"use strict";
var JKY = JKY || {};
/**
 * JKY.Salesman - process al layer functions
 *
 * require:	JKY.Utils.js(JKY.display_confirm)
 *
 * 		$(my_parent).find('.jky-salesman-id'  ).val(the_id );
 *		$(my_parent).find('.jky-salesman-name').val(my_name);
 */
JKY.Salesman = function() {
	var my_the_id		= null;			//	external id that initiated the call
	var my_customer_id	= null;			//	selected customer id
	var my_specific		= 'company';	//	selected company
	var my_order_by		= 'nick_name';
	var my_filter		= 'jky-salesman-filter';
	var my_search_body	= 'jky-salesman-search-body';
	var my_layer		= 'jky-salesman-search';

	var my_display = function(the_this) {
		my_the_id = the_this;
		JKY.set_focus(my_filter);
		my_load_data();
	}

	var my_load_data = function() {
		var my_data =
			{ method		: 'get_index'
			, table			: 'Contacts'
			, specific		: 'is_salesman'
			, specific_id	: '100002'		//	Company = Tecno
			, select		: 'All'
			, filter		:  JKY.get_value(my_filter)
			, display		: '10'
			, order_by		:  my_order_by
			};
		JKY.ajax(false, my_data, my_load_data_success);
	}

	var my_load_data_success = function(response) {
		var my_rows	= response.rows;
		var my_html = '';
		for(var i=0; i<my_rows.length; i++) {
			var my_row = my_rows[i];
			my_html += '<tr onclick="JKY.Salesman.click_row(this, ' + my_row.id + ')">'
					+  '<td class="jky-search-salesman-name"	>' +				 my_row.full_name		+ '</td>'
					+  '<td class="jky-search-salesman-mobile"	>' + JKY.fix_null	(my_row.mobile		)	+ '</td>'
					+  '<td class="jky-search-salesman-email"	>' +				 my_row.email			+ '</td>'
					+  '</tr>'
					;
		}
		JKY.set_html(my_search_body, my_html);
		JKY.show_modal(my_layer);
	}

	var my_click_row = function(the_index, the_id) {
		var my_name   = $(the_index).find('.jky-search-salesman-name'  ).html();
		var my_mobile = $(the_index).find('.jky-search-salesman-mobile').html();
		var my_parent = $(my_the_id).parent();

		var my_dom_id = $(my_parent).find('#jky-salesman-id');
		if (my_dom_id.length == 0) {
			my_dom_id = $(my_parent).find('.jky-salesman-id');
		}
		my_dom_id.val(the_id );

		var my_dom_name = $(my_parent).find('#jky-salesman-name');
		if (my_dom_name.length == 0) {
			my_dom_name = $(my_parent).find('.jky-salesman-name');
		}
		my_dom_name.val(my_name);

		my_dom_name.change();		//	to activate change event
		JKY.hide_modal(my_layer);
	}

	var my_add_new = function() {
		JKY.display_message('add_new');
	}

	return {
		  display		: function(the_this)			{		my_display(the_this);}
		, load_data		: function()					{		my_load_data();}
		, click_row		: function(the_index, the_id)	{		my_click_row(the_index, the_id);}
		, add_new		: function()					{		my_add_new();}
	};
}();