"use strict";
var JKY = JKY || {};
/**
 * JKY.Customer - process al layer functions
 *
 * require:	JKY.Utils.js(JKY.display_confirm)
 *
 * 		$(my_parent).find('.jky-customer-id'  ).val(the_id );
 *		$(my_parent).find('.jky-customer-name').val(my_name);
 */
JKY.Customer = function() {
	var my_the_id		= null;				//	external id that initiated the call
	var my_specific		= 'is_customer';	//	selected customer type: Punho, Gola, Galao
	var my_order_by		= 'nick_name';
	var my_filter		= 'jky-customer-filter';
	var my_search_body	= 'jky-customer-search-body';
	var my_layer		= 'jky-customer-search';

	function my_display(the_id, the_specific) {
		my_the_id	= the_id;
		if (typeof the_specific	== 'undefined') {
			my_specific	= 'is_customer';
		}else{
			my_specific	= the_specific;
		}
		JKY.set_focus(my_filter);
		my_load_data();
	}

	function my_load_data() {
		var my_data =
			{ method	: 'get_index'
			, table		: 'Contacts'
			, specific	:  my_specific
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
			my_html += '<tr onclick="JKY.Customer.click_row(this, ' + my_row.id + ')">'
					+  '<td class="jky-search-customer-name"	>' +				 my_row.nick_name		+ '</td>'
					+  '<td class="jky-search-customer-phone"	>' + JKY.fix_null	(my_row.phone		)	+ '</td>'
					+  '<td class="jky-search-customer-email"	>' +				 my_row.email			+ '</td>'
					+  '</tr>'
					;
		}
		JKY.set_html(my_search_body, my_html);
		JKY.show_modal(my_layer);
	}

	function my_click_row(the_index, the_id) {
		var my_name   = $(the_index).find('.jky-search-customer-name').html();
		var my_parent = $(my_the_id).parent();

		var my_dom_id = $(my_parent).find('#jky-customer-id');
		if (my_dom_id.length == 0) {
			my_dom_id = $(my_parent).find('.jky-customer-id');
		}
		my_dom_id.val(the_id );

		var my_dom_name = $(my_parent).find('#jky-customer-name');
		if (my_dom_name.length == 0) {
			my_dom_name = $(my_parent).find('.jky-customer-name');
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