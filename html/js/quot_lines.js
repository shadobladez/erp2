"use strict";
/*
 * display Quotation Lines -----------------------------------------------------
 */

JKY.display_lines = function() {
	var my_data =
		{ method		: 'get_index'
		, table			: 'QuotLines'
		, select		:  JKY.row.id
		, order_by		: 'QuotLines.id'
		};
	JKY.ajax(false, my_data, JKY.generate_lines);
}

JKY.generate_lines = function(the_response) {
	var my_html  = '';
	var my_rows	 = the_response.rows;
	if (my_rows != '') {
		for(var i in my_rows) {
			var my_row = my_rows[i];
			my_html += JKY.generate_line(i, my_row);
		}
	}
	JKY.set_html('jky-lines-body', my_html);
	$('.jky-product-peso'	).ForceNumericOnly();
	$('.jky-product-units'	).ForceIntegerOnly();
	$('.jky-quoted-units'	).ForceNumericOnly();
	$('.jky-quoted-price'	).ForceNumericOnly();
	if (my_rows == '') {
		JKY.insert_line();
	}
	JKY.enable_disable_lines();
}

JKY.generate_line = function(the_index, the_row) {
	var my_id = the_row.id;
	var my_trash = JKY.is_status('Draft') ? '<a onclick="JKY.delete_line(this, ' + my_id + ')"><i class="icon-trash"></i></a>' : '';
	var my_product = ''
		+ "<input class='jky-product-id' type='hidden' value=" + the_row.product_id + " />"
		+ "<input class='jky-product-name' disabled onchange='JKY.update_line(this, " + my_id + ")' value='" + the_row.product_name + "' />"
		+ " <a href='#' onClick='JKY.Product.display		(this)'><i class='icon-share'		></i></a>"
		+ " <a href='#' onClick='JKY.Product.display_info	(this)'><i class='icon-info-sign'	></i></a>"
//		+ " <a href='#' onClick='JKY.Product.display(this)'><i class='icon-th'		></i></a>"
		;
	var my_machine = ''
		+ "<input class='jky-machine-id' type='hidden' value=" + the_row.machine_id + " />"
		+ "<input class='jky-machine-name' disabled onchange='JKY.update_line(this, " + my_id + ")' value='" + the_row.machine_name + "' />"
		+ " <a href='#' onClick='JKY.Machine.display(this)'><i class='icon-share'	></i></a>"
		;
	var my_disabled = JKY.is_status('Draft') ? '' : ' disabled="disabled"';
	var my_add_color = '<button class="btn btn-success" type="button" onclick="JKY.insert_color(this, ' + my_id + ')"' + my_disabled + '>' + JKY.t('Add Color') + '</button>';
	var my_copy	= (the_index == 0) ? '' : '<button class="btn btn-success" type="button" onclick="JKY.copy_previous_colors (this, ' + my_id + ')"' + my_disabled + '>' + JKY.t('Copy') + '</button>';
	var my_onchange = ' changeable onchange="JKY.update_line(this, ' + my_id + ')"';
	var my_disabled = ' disabled';
	var my_html = ''
		+ '<tr class="jky-line" quot_line_id=' + my_id + '>'
		+ '<td class="jky-td-action"	>' + my_trash + '</td>'
		+ '<td class="jky-td-key-w3"	>' + my_product + '</td>'
		+ '<td class="jky-td-key"		>' + my_machine + '</td>'
		+ '<td class="jky-td-name-s"	><input class="jky-remarks"			value="' + JKY.decode	(the_row.remarks		) + '"' + my_onchange + ' /></td>'
		+ '<td class="jky-td-key-m"		>' + my_add_color + '&nbsp;' + my_copy + '</td>'
		+ '<td class="jky-td-pieces"	><input class="jky-product-peso"	value="' + JKY.fix_null	(the_row.peso			) + '"' + my_onchange + ' /></td>'
		+ '<td class="jky-td-pieces"	><input class="jky-quoted-units"	value="' + JKY.fix_null	(the_row.quoted_units	) + '"' + my_disabled + ' /></td>'
		+ '<td class="jky-td-units"		><input class="jky-product-units"	value="' + JKY.fix_null	(the_row.units			) + '"' + my_onchange + ' /></td>'
		+ '<td class="jky-td-pieces"	><input class="jky-quoted-pieces"	value="' + JKY.fix_null	(the_row.quoted_pieces	) + '"' + my_disabled + ' /></td>'
		+ '<td class="jky-td-info"		></td>'
		+ '<td class="jky-td-discount"	><input class="jky-discount"		value="' + JKY.fix_null	(the_row.discount		) + '"' + my_onchange + ' /></td>'
		+ '</tr>'
		;
	var my_rows = JKY.get_rows('QuotColors', my_id);
	for(var i=0, max=my_rows.length; i<max; i++) {
		var my_row = my_rows[i];
		my_html += JKY.generate_color(my_row, the_row.units);
	}
	return my_html;
}

JKY.update_line = function(the_this, the_id) {
	var my_tr = $(the_this).parent().parent();
	var my_product_id	= my_tr.find('.jky-product-id'		).val();
	var my_machine_id	= my_tr.find('.jky-machine-id'		).val();
	var my_remarks		= my_tr.find('.jky-remarks'			).val();
	var my_peso			= my_tr.find('.jky-product-peso'	).val();
	var my_quoted_units	= my_tr.find('.jky-quoted-units'	).val();
	var my_units		= my_tr.find('.jky-product-units'	).val();
	var my_quoted_pieces= my_tr.find('.jky-quoted-pieces'	).val();
	var my_discount		= my_tr.find('.jky-discount'		).val();
/*
	if (my_units < 1) {
		JKY.display_message(JKY.set_value_is_under('Units/Piece', 1));
		my_tr.find('.jky-product-units').select();
		my_tr.find('.jky-product-units').focus();
		return false;
	}
*/
	var my_new_pieces	= (my_units == 0 ) ? my_quoted_units : Math.ceil(my_quoted_units / my_units);
	var my_diff_pieces	= my_new_pieces - my_quoted_pieces;

	var my_line_pieces_id	= my_tr.find('.jky-quoted-pieces');
	my_line_pieces_id.val(my_new_pieces);

	var my_set = ''
		+    '  product_id =  ' + my_product_id
		+    ', machine_id =  ' + my_machine_id
		+       ', remarks =\'' + my_remarks + '\''
		+          ', peso =  ' + my_peso
		+         ', units =  ' + my_units
		+ ', quoted_pieces =  ' + my_new_pieces
		+      ', discount =\'' + my_discount + '\''
		;
	var my_data =
		{ method	: 'update'
		, table		: 'QuotLines'
		, set		:  my_set
		, where		: 'QuotLines.id = ' + the_id
		};
	JKY.ajax(true, my_data);

	my_data =
		{ method	: 'update'
		, table		: 'Quotations'
		, where		: 'Quotations.id = ' + JKY.row.id
		, set		: 'quoted_pieces = quoted_pieces + ' + my_diff_pieces
		};
	JKY.ajax(true, my_data, function(the_response) {
		JKY.update_quotation_amount();
	})
}

JKY.insert_line = function() {
	var my_data =
		{ method	: 'insert'
		, table		: 'QuotLines'
		, set		: 'QuotLines.parent_id = ' + JKY.row.id
		};
	JKY.ajax(true, my_data, function(the_response) {
		var my_row = [];
		my_row.id				= the_response.id;
		my_row.order_id			= null;
		my_row.product_id		= null;
		my_row.product_name		= '';
		my_row.machine_id		= null;
		my_row.machine_name		= '';
		my_row.remarks			= '';
		my_row.peso				= 0;
		my_row.quoted_units		= 0;
		my_row.units			= 0;
		my_row.quoted_pieces	= 0;
		my_row.discount			= '';

		var my_count = JKY.get_count_by_id('QuotLines', JKY.row.id);
		var my_html = JKY.generate_line(my_count-1, my_row);
		JKY.append_html('jky-lines-body', my_html);
	})
}

JKY.copy_lines = function(the_source, the_to) {
	var my_data =
		{ method	: 'get_rows'
		, table		: 'QuotLines'
		, where		: 'QuotLines.parent_id = ' + the_source
		, order_by  : 'QuotLines.id'
		};
	var my_object = {};
	my_object.data = JSON.stringify(my_data);
	$.ajax(
		{ url		: JKY.AJAX_URL
		, data		: my_object
		, type		: 'post'
		, dataType	: 'json'
		, async		: false
		, success	: function(the_response) {
				if (the_response.status == 'ok') {
					var my_rows = the_response.rows;
					for(var i in my_rows) {
						var my_row	= my_rows[i];
						var my_set	= '      parent_id =   ' + the_to
									+ ',   osa_line_id = NULL'
									+ ',    product_id =   ' + my_row.product_id
									+ ',    machine_id =   ' + my_row.machine_id
									+ ',          peso =   ' + my_row.peso
									+ ', quoted_weight =   ' + my_row.quoted_weight
									+ ',  quoted_units =   ' + my_row.quoted_units
									+ ', quoted_pieces =   ' + my_row.quoted_pieces
									+ ',         units =   ' + my_row.units
									+ ',      discount = \'' + my_row.discount	+ '\''
									+ ',       remarks = \'' + my_row.remarks	+ '\''
									;
						var	my_data =
							{ method	: 'insert'
							, table		: 'QuotLines'
							, set		:  my_set
							};
						JKY.ajax(false, my_data, function(the_response) {
							JKY.copy_colors(my_row.id, the_response.id);
						})
					}
				}else{
					JKY.display_message(the_response.message);
				}
			}
		}
	)
}

JKY.delete_line = function(the_this, the_id) {
	var my_count = JKY.get_count_by_id('QuotColors', the_id);
	if (my_count > 0) {
		JKY.display_message(JKY.t('Error, delete first all sub records'));
		return;
	}

	$(the_this).parent().parent().remove();
	var my_data =
		{ method	: 'delete'
		, table		: 'QuotLines'
		, where		: 'QuotLines.id = ' + the_id
		};
	JKY.ajax(true, my_data, function(the_response) {
//		JKY.verify_total_percent();
	})
}

JKY.enable_disable_lines = function() {
	$('#jky-lines-body tr').each(function() {
		if ($(this).hasClass('jky-line')) {
			var my_peso  = $(this).find('.jky-product-peso' );
			var my_units = $(this).find('.jky-product-units');
			if ($(this).next().attr('color_id')) {
				my_peso .prop('disabled'	, true	);
				my_units.prop('disabled'	, true	);
				my_peso .prop('changeable'	, false	);
				my_units.prop('changeable'	, false	);
			}else{
				my_peso .prop('disabled'	, false	);
				my_units.prop('disabled'	, false	);
				my_peso .prop('changeable'	, true	);
				my_units.prop('changeable'	, true	);
			}
		}
	});
}

JKY.print_lines = function(the_id) {
	var my_html  = '';
	var my_data =
		{ method	: 'get_index'
		, table		: 'QuotLines'
		, select	:  the_id
		, order_by  : 'QuotLines.id'
		};
	var my_object = {};
	my_object.data = JSON.stringify(my_data);
	$.ajax(
		{ url		: JKY.AJAX_URL
		, data		: my_object
		, type		: 'post'
		, dataType	: 'json'
		, async		: false
		, success	: function(response) {
				if (response.status == 'ok') {
					var my_rows = response.rows;
					for(var i in my_rows) {
						var my_row = my_rows[i];
						var my_product = JKY.get_row('Products', my_row.product_id);
						my_html  += ''
							+ '<tr class="jky-print-head">'
							+ '<td class="jky-print-product" style="width:39%;"			 ><span>Product</span>: ' + my_row.product_name			+     '</td>'
							+ '<td class="jky-print-pieces"	 style="width:17%;"			 ><span>Peso   </span>: ' + my_row.peso					+  ' kg</td>'
							+ '<td class="jky-print-pieces"	 style="width:21%;" colspan=2><span>Weight </span>: ' + my_product.weight_customer	+ ' grs</td>'
							+ '<td class="jky-print-pieces"	 style="width:23%;" colspan=2><span>Width  </span>: ' + my_product.width_customer	+  ' cm</td>'
							+ '</tr>'
							;
						if (my_row.remarks) {
							my_html += ''
								+ '<tr class="jky-print-head">'
								+ '<td class="jky-print-extra" colspan=8><span>Extra</span>: ' + JKY.decode(my_row.remarks) + '</td>'
								+ '</tr>'
								;
						}
						my_html  += JKY.print_colors(my_row.id, my_row);
					}
				}else{
					JKY.display_message(response.message);
				}
			}
		}
	)
	return my_html;
}

/**
JKY.approve_lines = function(the_id) {
	var my_html  = '';
	var my_data =
		{ method	: 'get_index'
		, table		: 'QuotLines'
		, select	:  the_id
		, order_by  : 'QuotLines.id'
		};
	var my_object = {};
	my_object.data = JSON.stringify(my_data);
	$.ajax(
		{ url		: JKY.AJAX_URL
		, data		: my_object
		, type		: 'post'
		, dataType	: 'json'
		, async		: false
		, success	: function(response) {
				if (response.status == 'ok') {
					var my_rows = response.rows;
					for(var i in my_rows) {
						var my_row = my_rows[i];
						var my_product = JKY.get_row('Products', my_row.product_id);
						my_html  += ''
							+ '<tr class="jky-print-head">'
							+ '<td class="jky-print-product" style="width:39%;"			 ><span>Product</span>: ' + my_row.product_name			+     '</td>'
							+ '<td class="jky-print-pieces"	 style="width:17%;"			 ><span>Peso   </span>: ' + my_row.peso					+  ' kg</td>'
							+ '<td class="jky-print-pieces"	 style="width:21%;" colspan=2><span>Weight </span>: ' + my_product.weight_customer	+ ' grs</td>'
							+ '<td class="jky-print-pieces"	 style="width:23%;" colspan=2><span>Width  </span>: ' + my_product.width_customer	+  ' cm</td>'
							+ '</tr>'
							;
						if (my_row.remarks) {
							my_html += ''
								+ '<tr class="jky-print-head">'
								+ '<td class="jky-print-extra" colspan=8><span>Extra</span>: ' + JKY.decode(my_row.remarks) + '</td>'
								+ '</tr>'
								;
						}

						var my_ftp_id = JKY.get_ftp_id(my_row.product_id);
						var my_ftp = JKY.get_row('FTPs', my_ftp_id);
						my_html += ''
							+ '<tr><td></td></tr>'
							+ '<tr class="jky-print-head">'
							+ '<td class="jky-print-ftp"><span>FTP</span>: ' + my_ftp.ftp_number + '</td>'
							+ '<td class="jky-print-ftp" colspan=4>Composição do Produto: ' + my_ftp.composition + '</td>'
							+ '</tr>'
							+ '<tr><td></td></tr>'
							;
						var my_ftp_threads = JKY.get_rows('FTP_Threads', my_ftp_id);
						for(var j in my_ftp_threads) {
							var my_ftp_thread = my_ftp_threads[j];
							var my_thread = JKY.get_row('Threads', my_ftp_thread.thread_id);
							my_html += ''
								+ '<tr class="jky-print-head">'
								+ '<td class="jky-print-thread"><span>Thread</span>: ' + my_thread.name + '</td>'
								+ '<td class="jky-print-thread" colspan=3><span>Composition</span>: ' + my_thread.composition + '</td>'
								+ '<td class="jky-print-thread">' + parseInt(my_ftp_thread.percent) + ' (%)</td>'
								+ '</tr>'
								;
						}

						my_html += '<tr><td></td></tr>';
						my_html += JKY.approve_colors(my_row.id, my_row);
						my_html += '<tr><td></td></tr>';
					}
				}else{
					JKY.display_message(response.message);
				}
			}
		}
	)
	return my_html;
}
 */

JKY.approve_lines = function(the_id) {
	var my_html  = '';
	var my_data =
		{ method	: 'get_index'
		, table		: 'QuotLines'
		, select	:  the_id
		, order_by  : 'QuotLines.id'
		};
	var my_object = {};
	my_object.data = JSON.stringify(my_data);
	$.ajax(
		{ url		: JKY.AJAX_URL
		, data		: my_object
		, type		: 'post'
		, dataType	: 'json'
		, async		: false
		, success	: function(response) {
				if (response.status == 'ok') {
					var my_rows = response.rows;
					for(var i in my_rows) {
						var my_row = my_rows[i];
						var my_product		= JKY.get_row	('Products', my_row.product_id);
						var my_ftp_id		= JKY.get_ftp_id(my_row.product_id);
						var my_ftp			= JKY.get_row	('FTPs', my_ftp_id);
//						var my_ftp_threads	= JKY.get_rows	('FTP_Threads', my_ftp_id);

						my_html  += ''
							+ '<table class="jky-approve-line"><tr>'
							+ '<td class="jky-left"><span class="jky-bold">    Product</span>: ' + my_row.product_name	+ '</td>'
							+ '<td class="jky-left"><span class="jky-bold">Composition</span>: ' + JKY.fix_null(my_ftp.composition)	+ '</td>'
							+ '</tr></table>'
							;

						my_html  += ''
							+ '<table class="jky-approve-line"><tr>'
							+ '<td class="jky-left"><span class="jky-bold">  Machine</span>: ' + JKY.fix_null(my_ftp.machine_name) + '</td>'
							+ '<td class="jky-left"><span class="jky-bold">    Width</span>: ' + my_product.width_dyer	+ '</td>'
							+ '<td class="jky-left"><span class="jky-bold">Gramatura</span>: ' + my_product.weight_dyer	+ '</td>'
							+ '</tr></table>'
							;

						my_html += '<table class="jky-approve-line">'
/*
						for(var j in my_ftp_threads) {
							var my_ftp_thread = my_ftp_threads[j];
							var my_thread_name	 = JKY.get_value_by_id('Threads' , 'name'		, my_ftp_thread.thread_id	);
							my_html += ''
								+ '<tr>'
								+ '<td class="jky-left"><span class="jky-bold">     Thread</span>: ' + my_thread_name			+ '</td>'
								+ '<td class="jky-left"><span class="jky-bold">   Supplier</span>: ' + my_ftp_thread.supplier	+ '</td>'
								+ '<td class="jky-left"><span class="jky-bold">Consumption</span>: ' + parseFloat(my_ftp_thread.percent) + '</td>'
								+ '</tr>'
								;
						}
*/
						my_html += ''
							+ '<tr><td>&nbsp;</td></tr>'
							+ '<tr><td>&nbsp;</td></tr>'
							+ '<tr><td>&nbsp;</td></tr>'
							+ '<tr><td>&nbsp;</td></tr>'
							+ '<tr><td>&nbsp;</td></tr>'
							+ '</table>'
							;
						my_html += '<br>';

						my_html += JKY.approve_colors(my_row.id, my_row);
					}
				}else{
					JKY.display_message(response.message);
				}
			}
		}
	)
	return my_html;
}
