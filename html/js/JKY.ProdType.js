"use strict";
var JKY = JKY || {};
/**
 * JKY.ProdType - process all changes during one transaction
 *				 control save into private array [my_appraisals]
 *
 * method:	display(the_id)
 * 			load_data()
 *			click_row(the_index, the_id)
 *			add_new()
 *
 * require:	JKY.Utils.js(JKY.display_confirm)
 *
 *		$(my_parent).find('.jky-product-type').val(my_name);
 */
JKY.ProdType = function() {
	var my_the_id		= null;		//	external id that initiated the call
	var my_the_type		= null;		//	selected product-type type: Punho, Gola, Galao
	var my_order_by		= 'product-type';
	var my_filter		= 'jky-product-type-filter';
	var my_search_body	= 'jky-product-type-search-body';
	var my_layer		= 'jky-product-type-search';
	var my_cookie		= null;

	function my_display(the_id, the_type) {
		my_the_id = the_id;
		if (the_type == null) {
			my_the_type	= 'All';
		}else{
			my_the_type	= the_type;
		}
		JKY.set_focus(my_filter);
		my_load_data();
	}

	function my_load_data() {
		var my_rows = JKY.get_configs('Product Types');
		my_load_data_success(my_rows);
	}

	function my_load_data_success(the_rows) {
		var my_rows	= the_rows;
		var my_html = '';
		for(var i=0, max=my_rows.length; i<max; i++) {
			var my_row = my_rows[i];
			my_html += '<tr onclick="JKY.ProdType.click_row(this, \'' + my_row.name + '\')">'
					+  '<td class="jky-search-product-type">' + my_row.name	+ '</td>'
					+  '</tr>'
					;
		}
		JKY.set_html(my_search_body, my_html);
		JKY.show_modal(my_layer);
	}

	function my_click_row(the_index, the_type) {
		var my_dom_type = $('#jky-product-type');
		if (my_dom_type.length == 0) {
			var my_parent = $(my_the_id).parent().parent();
			my_dom_type = $(my_parent).find('.jky-product-type');
		}
		my_dom_type.val(the_type);
		my_dom_type.change();		//	to activate change event

		JKY.hide_modal(my_layer);
	}

	function my_add_new() {
		JKY.display_message('add_new');
	}

	$(function() {
		my_cookie = $.cookie(my_layer);
	});

	return {
		  display		: function(the_id, the_type)	{		my_display(the_id, the_type);}
		, load_data		: function()					{		my_load_data();}
		, click_row		: function(the_index, the_id)	{		my_click_row(the_index, the_id);}
		, add_new		: function()					{		my_add_new();}
	};
}();