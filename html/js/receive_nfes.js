"use strict";

/**
 * receive_nfes.js
 */

/**
 * start program
 */
JKY.start_program = function() {
	JKY.App = JKY.Application;
	JKY.App.set(
		{ object_name	: 'JKY.App'
		, program_name	: 'Receive NFEs'
		, table_name	: 'NFEs'
		, specific		: ''
		, select		: ''
		, filter		: '*.xml'
		, sort_by		: 'nfe_number'
		, sort_seq		: 'DESC'
		, sort_list		: [[1, 1]]
		, focus			: 'jky-vendor-name'
		, add_new		: ''
		});
	JKY.App.init();
};

/**
 *	set all events (run only once per load)
 */
JKY.set_all_events = function() {
	$('#jky-received-date	input').attr('data-format', JKY.Session.get_date_time	());
	$('#jky-invoice-date	input').attr('data-format', JKY.Session.get_date		());
	$('#jky-received-date'		).datetimepicker({language: JKY.Session.get_locale()});
	$('#jky-invoice-date'		).datetimepicker({language: JKY.Session.get_locale(), pickTime: false});

	$('#jky-action-insert'		).click( function() {JKY.App.process_insert	();});
	$('#jky-action-update'		).click( function() {JKY.App.process_update	();});

	$('#jky-action-close'		).click( function() {JKY.App.close_row(JKY.row.id);});
	$('#jky-batches-add-new'	).click (function() {JKY.insert_batch		();});

	$('#jky-boxes-print'		).click (function() {JKY.Batch.print		()});
	$('#jky-action-save-remarks').click (function()	{JKY.save_remarks		();});

	JKY.set_side_active('jky-receiving-receive-nfes');
};

/**
 *	set initial values (run only once per load)
 */
JKY.set_initial_values = function() {
//	JKY.append_file('jky-load-thread'	, '../JKY.Search.Thread.html'	);
//	JKY.append_file('jky-load-purline'	, '../JKY.Search.PurLine.html'	);

	JKY.hide('jky-action-add-new');
	JKY.show('jky-action-graph'  );

	JKY.set_html('jky-app-select', JKY.set_controls('NFE Folders', ''));
	JKY.set_html('jky-app-select-label', JKY.t('Folder'));
	JKY.show	('jky-app-select-line');
//	JKY.set_html('jky-dyer-name', JKY.set_options_array('', JKY.get_companies('is_dyer'), false));
//	select the first option as default
	$('#jky-app-select option').eq(1).prop('selected', true);
	$('#jky-app-select').change();

//	$('#jky-thread-filter'	).KeyUpDelay(JKY.Product.load_data);
//	$('#jky-purline-filter'	).KeyUpDelay(JKY.PurLine.load_data);

	$('#jky-invoice-pieces'		).ForceNumericOnly();
	$('#jky-invoice-weight'		).ForceNumericOnly();
	$('#jky-invoice-amount'		).ForceNumericOnly();
	$('#jky-received-pieces'	).ForceNumericOnly();
	$('#jky-received-weight'	).ForceNumericOnly();
	$('#jky-received-amount'	).ForceNumericOnly();
};

/**
 *	set table row
 */
JKY.set_table_row = function(the_row) {
	var my_invoice_weight	= parseFloat(the_row.invoice_weight	);
	var my_received_weight	= parseFloat(the_row.received_weight);
	var my_class = (my_invoice_weight == my_received_weight) ? '' : ' jky-error';

	var my_html = ''
		+  '<td class="jky-td-name-w"	>' + the_row.name	+ '</td>'
		+  '<td class="jky-td-key-s"	>' + the_row.ext	+ '</td>'
		+  '<td class="jky-td-name-s"	>' + the_row.size	+ '</td>'
		+  '<td class="jky-td-datetime"	>' + the_row.time	+ '</td>'
		;
	return my_html;
};

/**
 *	set form row
 */
JKY.set_form_row = function(the_row) {
	JKY.hide('jky-action-delete');
//	var my_xml_nfe	= the_row.xml_nfe;
	var my_NFe		= the_row.NFe;
	var my_infNFe	= my_NFe.infNFe;
	var my_det		= my_infNFe.det;
	var my_ide		= my_infNFe.ide;
	var my_emit		= my_infNFe.emit;
	var my_total	= my_infNFe.total;
	var my_transp	= my_infNFe.transp;
	var my_infAdic	= my_infNFe.infAdic;

	var my_vendor_id = JKY.get_id('Contacts', 'full_name = \'' + my_emit.xNome + '\'');
		my_vendor_id = my_vendor_id || 'null';

	var my_nfe_id	 = JKY.get_id('NFEs', 'vendor_id = ' + my_vendor_id + ' AND nfe_number = ' + my_ide.nNF);
		my_nfe_id	 = my_nfe_id || 'null';

	if (my_nfe_id == 'null') {
		JKY.show('jky-action-insert');
		JKY.hide('jky-action-update');
	}else{
		JKY.display_message('NFE already processed: ' + my_nfe_id);
		JKY.row.id = my_nfe_id;
		JKY.hide('jky-action-insert');
		JKY.show('jky-action-update');
	}
	JKY.hide('jky-action-add-new');

	JKY.set_value	('jky-nfe-key'			,				 my_infNFe['@attributes'].Id.substr(3));
	JKY.set_value	('jky-nfe-id'			,				 my_nfe_id			 );
	JKY.set_value	('jky-nfe-number'		,				 my_ide.nNF			 );
	JKY.set_date	('jky-received-date'	, JKY.out_time	(JKY.get_now()		));
	JKY.set_value	('jky-vendor-id'		,				 my_vendor_id		 );
	JKY.set_value	('jky-vendor-name'		,				 my_emit.xNome		 );
	JKY.set_date	('jky-invoice-date'		, JKY.out_date	(my_ide.dEmi		));
	JKY.set_value	('jky-invoice-pieces'	,				 my_transp.vol.qVol	 );
	JKY.set_value	('jky-invoice-weight'	,				 my_transp.vol.pesoL );
	JKY.set_value	('jky-invoice-amount'	,				 my_total.ICMSTot.vNF);
	JKY.set_value	('jky-received-pieces'	,				 0);
	JKY.set_value	('jky-received-weight'	,				 0);
	JKY.set_value	('jky-received-amount'	,				 0);

	JKY.set_value	('jky-remarks'			,				 my_infAdic.infCpl	);
	JKY.generate_lines(my_infNFe.det);
};

JKY.generate_lines = function(the_rows) {
	var my_html  = '';
	if (JKY.is_array(the_rows)) {
		for(var i in the_rows) {
			var my_row = the_rows[i];
			my_html += JKY.generate_line(my_row);
		}
	}else{
		my_html += JKY.generate_line(the_rows);
	}
	JKY.set_html('jky-items-body', my_html);
};

JKY.generate_line = function(the_row) {
	var my_id = the_row['@attributes'].nItem;
	var my_row = the_row.prod;
	var my_trash = JKY.is_status('Draft') ? '<a onclick="JKY.delete_line(this, ' + my_id + ')"><i class="icon-trash"></i></a>' : '';
	var my_html = ''
		+ '<tr class="jky-line" nfe_item_id=' + my_id + '>'
		+ '<td class="jky-td-action"	>' + my_trash	+ '</td>'
		+ '<td class="jky-td-name-s"	><input class="jky-item-code"	disabled value="' + my_row.cProd	+ '" /></td>'
		+ '<td class="jky-td-name-w"	><input class="jky-item-name"	disabled value="' + my_row.xProd	+ '" /></td>'
		+ '<td class="jky-td-code"		><input class="jky-item-ncm"	disabled value="' + my_row.NCM		+ '" /></td>'
		+ '<td class="jky-td-weight"	><input class="jky-item-weight"	disabled value="' + my_row.qTrib	+ '" /></td>'
		+ '<td class="jky-td-price"		><input class="jky-item-price"	disabled value="' + my_row.vUnTrib	+ '" /></td>'
		+ '<td class="jky-td-price"		><input class="jky-item-total"	disabled value="' + my_row.vProd	+ '" /></td>'
		+ '</tr>'
		;
	var my_received_weight = parseFloat(JKY.get_value('jky-received-weight'));
	var my_received_amount = parseFloat(JKY.get_value('jky-received-amount'));
	JKY.set_value('jky-received-weight', my_received_weight + parseFloat(my_row.qTrib));
	JKY.set_value('jky-received-amount', my_received_amount + parseFloat(my_row.vProd));
	JKY.set_calculated_color();

	return my_html;
}

/**
 *	set calculated color
 */
JKY.set_calculated_color = function() {
	var my_invoice_weight	= parseFloat(JKY.get_value('jky-invoice-weight'	));
	var my_received_weight	= parseFloat(JKY.get_value('jky-received-amount'	));
	JKY.set_css('jky-received-weight', 'color', ((my_invoice_weight - my_received_weight) > 0.001) ? 'red' : 'black');

	var my_invoice_amount	= parseInt(JKY.get_value('jky-invoice-amount'	));
	var my_received_amount	= parseInt(JKY.get_value('jky-received_amount'	));
	JKY.set_css('jky-received-amount', 'color', (my_invoice_amount > my_received_amount) ? 'red' : 'black');

//	var my_reserved_boxes	= parseInt(JKY.get_value('jky-reserved-boxes'	));
//	JKY.set_css('jky-reserved-boxes', 'color', (my_reserved_boxes < 0) ? 'red' : 'black');
}

/**
 *	set add new row
 */
JKY.set_add_new_row = function() {
	JKY.disable_button('jky-action-delete'	);
	JKY.disable_button('jky-action-close'	);

	JKY.set_value	('jky-receive-number'	,  JKY.t('New'));
	JKY.set_date	('jky-received-date'	,  JKY.out_time(JKY.get_now()));
	JKY.set_option	('jky-dyer-name'	, '');
	JKY.set_value	('jky-nfe-dl'			, '');
	JKY.set_value	('jky-nfe-tm'			, '');
	JKY.set_date	('jky-invoice-date'		,  JKY.out_date(JKY.get_date()));
	JKY.set_value	('jky-invoice-weight'	,  0);
	JKY.set_value	('jky-invoice-amount'	,  0);
	JKY.set_value	('jky-received-weight'	,  0);
	JKY.set_value	('jky-received-amount'	,  0);
};

/**
 *	get form set
 */
JKY.get_form_set = function() {
	var my_vendor_id = JKY.get_id('Contacts', 'full_name = \'' + JKY.get_value('jky-vendor-name') + '\'');
		my_vendor_id = my_vendor_id || 'null';

	var my_set = ''
		+           'nfe_key=\'' + JKY.get_value	('jky-nfe-key'			) + '\''
		+      ', nfe_number=  ' + JKY.get_value	('jky-nfe-number'		)
		+       ', vendor_id=  ' + my_vendor_id
		+     ', vendor_name=\'' + JKY.get_value	('jky-vendor-name'		) + '\''
		+    ', invoice_date=  ' + JKY.inp_date		('jky-invoice-date'		)
		+  ', invoice_pieces=  ' + JKY.get_value	('jky-invoice-pieces'	)
		+  ', invoice_weight=  ' + JKY.get_value	('jky-invoice-weight'	)
		+  ', invoice_amount=  ' + JKY.get_value	('jky-invoice-amount'	)
		+ ', received_pieces=  ' + JKY.get_value	('jky-received-pieces'	)
		+ ', received_weight=  ' + JKY.get_value	('jky-received-weight'	)
		+ ', received_amount=  ' + JKY.get_value	('jky-received-amount'	)
		+         ', remarks=\'' + JKY.get_value	('jky-remarks'			) + '\''
		;
	return my_set;
};

JKY.process_delete = function(the_id, the_row) {
	var my_data =
		{ method: 'delete_many'
		, table : 'Batches'
		, where : 'receivedyer_id = ' + the_id
		};
	JKY.ajax(true, my_data);
};

/**
 *	set calculated color
 */
JKY.set_calculated_color = function() {
	var my_invoice_weight	= parseFloat(JKY.get_value('jky-invoice-weight'	));
	var my_invoice_amount	= parseFloat(JKY.get_value('jky-invoice-amount'	));
	var my_received_weight	= parseFloat(JKY.get_value('jky-received-weight'));
	var my_received_amount	= parseFloat(JKY.get_value('jky-received-amount'));
	JKY.set_css('jky-received-amount', 'color', (Math.abs(my_invoice_amount - my_received_amount) > 0.021) ? 'red' : 'black');
	JKY.set_css('jky-received-weight', 'color', (Math.abs(my_invoice_weight - my_received_weight) > 0.021) ? 'red' : 'black');
};

JKY.display_list = function() {
	JKY.hide('jky-action-add-new');
};


JKY.display_graph = function() {
	JKY.show('jky-loading');
	var my_data =
		{ method	: 'get_index'
		, table		: JKY.App.get_prop('table_name')
		, specific	: JKY.App.get_prop('specific')
		, select	: JKY.App.get_prop('select')
		, filter	: JKY.App.get_prop('filter')
		, display	: JKY.App.get_prop('display')
//		, order_by	: 'invoice_date'
//		, group_by	: 'invoice_date'
		};
	JKY.ajax(false, my_data, JKY.display_graph_success);
}

JKY.display_graph_success = function(response) {
	var my_rows	= response.rows;

//	sum all [invoice_weight] by [invoice_date]
	var sum_by_invoice_date = d3.nest()
		.key	(function(d)	{return d.invoice_date ? d.invoice_date.substr(5,5) : 'unknown';})
		.sortKeys(d3.ascending)
		.rollup	(function(d)	{return	{invoice_weight:d3.sum(d, function(g)	{return +g.invoice_weight ;})};})
		.entries(my_rows)
		;
//	JKY.var_dump('sum_by_invoice_date', sum_by_invoice_date);

//	sum all [received_weight] by [received_date]
	var sum_by_received_date = d3.nest()
		.key	(function(d)	{return d.received_at ? d.received_at.substr(5,5) : 'unknown';})
		.sortKeys(d3.ascending)
		.rollup	(function(d) 	{return	{received_weight:d3.sum(d, function(g)	{return +g.received_weight;})};})
		.entries(my_rows)
		;
//	JKY.var_dump('sum_by_received_date', sum_by_received_date);

	var merged_array = [];
	var get_index = function(the_key) {
		var j = merged_array.length;
		if (j > 0) {
			for(j in merged_array) {
				var my_key = merged_array[j].key;
				if (my_key == the_key)		{return j;}
				if (my_key >  the_key)	 	{j=parseInt(j)-1; break;}
			}
			j=parseInt(j)+1;
		}
		var my_row = {'key':the_key, 'invoice_weight':0, 'received_weight':0};
		merged_array.splice(j, 0, my_row);
		return j;
	}

//	merge all [invoice_weight] by [invoice_date]
	for(var i in sum_by_invoice_date) {
		var my_row = sum_by_invoice_date[i];
		if (my_row.values.invoice_weight > 0) {
			var my_index = get_index(my_row.key);
			merged_array[my_index].invoice_weight += my_row.values.invoice_weight;
		}
	}

//	merge all [received_weight] by [received_date]
	for(var i in sum_by_received_date) {
		var my_row = sum_by_received_date[i];
		if (my_row.values.received_weight > 0) {
			var my_index = get_index(my_row.key);
			merged_array[my_index].received_weight += my_row.values.received_weight;
		}
	}
//	JKY.var_dump('merged_array', merged_array);

//	draw dual_bar chart with [invoice_weight] and [received_weight] by [date]
	$('#jky-graph-body').html('');
	JKY.Graph = JKY.D3;
	JKY.Graph.setArgs(
		{ id_name		: 'jky-graph-body'
		, graph_name	: 'dual_bar'
		, axis_name		: 'key'
		, var1_name		: 'invoice_weight'
		, var2_name		: 'received_weight'
		, round_up		: 200
		, chart_width	: 600
		, chart_height	:   0
		});
	JKY.Graph.draw(merged_array);
	JKY.hide('jky-loading');
}

/* -------------------------------------------------------------------------- */
JKY.save_remarks = function() {
	var my_set	=   'remarks = \'' + JKY.get_value('jky-remarks') + '\'';
	var my_data =
		{ method: 'update'
		, table : 'Quotations'
		, set	:  my_set
		, where : 'Quotations.id = ' + JKY.row.id
		};
	JKY.ajax(true, my_data, JKY.save_remarks_success);
};

JKY.save_remarks_success = function(response) {
	JKY.display_message('Remarks saved, ' + response.message);
	JKY.row = JKY.get_row('Quotations', JKY.row.id);
};

JKY.process_insert = function(the_id) {
	JKY.move_to_processed();
};

JKY.process_update = function(the_id, the_row) {
	JKY.move_to_processed();
}

JKY.move_to_processed = function() {
	var my_filename = JKY.get_file_name(JKY.row_id);

	var my_data =
		{ method	: 'move'
		, table		:  JKY.App.get_prop('table_name')
		, filename	:  my_filename
		, from		:  JKY.get_control_value('NFE Folders', 'Received' )
		, to		:  JKY.get_control_value('NFE Folders', 'Processed')
		};
	JKY.ajax(false, my_data, JKY.move_to_processed_success);
};

JKY.move_to_processed_success = function(response) {
	setTimeout(function() {
		JKY.App.display_list();
	}, 1000);
}