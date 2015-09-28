"use strict";
var JKY = JKY || {};
/**
 * quotations.js
 */

/**
 * start program
 */
JKY.start_program = function() {
	JKY.App = JKY.Application;
	JKY.App.set(
		{ object_name	: 'JKY.App'
		, program_name	: 'Quotations'
		, table_name	: 'Quotations'
		, specific		: 'same_server'			//	SUBSTR(Quotations.id,1,1) = SERVER_NUMBER
		, select		: JKY.sales.select
		, filter		: ''
		, sort_by		: 'quotation_number'
		, sort_seq		: 'DESC'
		, sort_list		: [[1, 1]]
		, focus			: 'jky-payments'
		, add_new		: 'display form'
		, class			: 'status'
		});
	JKY.App.init();
};

JKY.materials	= [];
JKY.threads		= [];
JKY.loads		= [];
JKY.settings	= [];
JKY.suppliers	= [];

/**
 *	set all events (run only once per load)
 */
JKY.set_all_events = function() {
	$('#jky-quoted-date			input').attr('data-format', JKY.Session.get_date_time());
	$('#jky-produce-from-date	input').attr('data-format', JKY.Session.get_date	 ());
	$('#jky-produce-to-date		input').attr('data-format', JKY.Session.get_date	 ());
//	$('#jky-delivered-date		input').attr('data-format', JKY.Session.get_date	 ());
	$('#jky-quoted-date'		).datetimepicker({language: JKY.Session.get_locale()});
	$('#jky-produce-from-date'	).datetimepicker({language: JKY.Session.get_locale(), pickTime: false});
	$('#jky-produce-to-date'	).datetimepicker({language: JKY.Session.get_locale(), pickTime: false});
//	$('#jky-delivered-date'		).datetimepicker({language: JKY.Session.get_locale(), pickTime: false});

	$('#jky-action-gen-sale'	).click( function() {JKY.generate_sale			();});
	$('#jky-action-generate'	).click( function() {JKY.generate_osa			();});
	$('#jky-action-close'		).click( function() {JKY.App.close_row(JKY.row.id);});
	$('#jky-lines-add-new'		).click (function()	{JKY.insert_line			();});

//	$('#jky-action-product'		).click (function() {JKY.display_product	();});
	$('#jky-action-product'		).click (function() {JKY.Product.display(this);});
//	$('#jky-search-add-new'		).click (function()	{JKY.add_new_product	();});
	$('#jky-action-save-customers').click (function()	{JKY.save_customers	();});
	$('#jky-action-save-remarks').click (function()	{JKY.save_remarks		();});
//	$('#jky-search-filter'		).KeyUpDelay(JKY.filter_product);

	JKY.set_side_active('jky-sales-quotations');
};

/**
 *	set initial values (run only once per load)
 */
JKY.set_initial_values = function() {
	JKY.append_file('jky-load-customer'		, '../JKY.Search.Customer.html'	);
	JKY.append_file('jky-load-salesman'		, '../JKY.Search.Salesman.html'	);
	JKY.append_file('jky-load-contact'		, '../JKY.Search.Contact.html'	);
	JKY.append_file('jky-load-machine'		, '../JKY.Search.Machine.html'	);
	JKY.append_file('jky-load-dyer'			, '../JKY.Search.Dyer.html'		);
	JKY.append_file('jky-load-product'		, '../JKY.Search.Product.html'	);
	JKY.append_file('jky-load-color'		, '../JKY.Search.Color.html'	);
	JKY.append_file('jky-load-product-type'	, '../JKY.Search.ProdType.html'	);

	JKY.set_html('jky-app-select', JKY.set_options(JKY.sales.select, 'All', 'Draft + Active', 'Draft', 'Active', 'Closed'));
	JKY.set_html('jky-app-select-label', JKY.t('Status'));
	JKY.show	('jky-app-select-line');

	$('#jky-customer-filter'	).KeyUpDelay(JKY.Customer	.load_data);
	$('#jky-salesman-filter'	).KeyUpDelay(JKY.Salesman	.load_data);
	$('#jky-contact-filter'		).KeyUpDelay(JKY.Contact	.load_data);
	$('#jky-machine-filter'		).KeyUpDelay(JKY.Machine	.load_data);
	$('#jky-dyer-filter'		).KeyUpDelay(JKY.Dyer		.load_data);
	$('#jky-product-filter'		).KeyUpDelay(JKY.Product	.load_data);
	$('#jky-color-filter'		).KeyUpDelay(JKY.Color		.load_data);
	$('#jky-prod-type-filter'	).KeyUpDelay(JKY.Product	.load_data);
	$('#jky-customer-name'		).change(function() {JKY.update_customer_info	();});
	$('#jky-advanced-amount'	).change(function() {JKY.update_sub_amount		();});

	$('#jky-advanced-amount').ForceNumericOnly();
};

/**
 *	set table row
 */
JKY.set_table_row = function(the_row) {
	var my_html = ''
		+  '<td class="jky-td-number"	>' +				 the_row.quotation_number	+ '</td>'
		+  '<td class="jky-td-name-s"	>' + JKY.fix_null	(the_row.customer_name	)	+ '</td>'
		+  '<td class="jky-td-date"		>' + JKY.out_date	(the_row.quoted_at		)	+ '</td>'
		+  '<td class="jky-td-date"		>' + JKY.out_date	(the_row.produce_from_date)	+ '</td>'
//		+  '<td class="jky-td-date"		>' + JKY.out_date	(the_row.delivered_date	)	+ '</td>'
		+  '<td class="jky-td-pieces"	>' +				 the_row.quoted_pieces		+ '</td>'
		+  '<td class="jky-td-pieces"	>' +				 the_row.produced_pieces	+ '</td>'
		+  '<td class="jky-td-pieces"	>' +				 the_row.delivered_pieces	+ '</td>'
		;
	return my_html;
};

/**
 *	set form row
 */
JKY.set_form_row = function(the_row) {
	if (the_row.status === 'Draft') {
		JKY.enable_button ('jky-action-generate');
		JKY.enable_delete_button();
		JKY.enable_button ('jky-lines-add-new'	);
	}else{
		JKY.disable_button('jky-action-generate');
		JKY.disable_delete_button();
		JKY.disable_button('jky-lines-add-new'	);
	}
	if (the_row.status === 'Active') {
		JKY.enable_button ('jky-action-close');
	}else{
		JKY.disable_button('jky-action-close');
	}

	JKY.hide_parent ('jky-status');
	JKY.set_value	('jky-quotation-number'	,				 the_row.quotation_number	);
	JKY.set_date	('jky-quoted-date'		, JKY.out_time	(the_row.quoted_at			));
	JKY.set_date	('jky-produce-from-date', JKY.out_date	(the_row.produce_from_date	));
	JKY.set_date	('jky-produce-to-date'	, JKY.out_date	(the_row.produce_to_date	));
	JKY.set_value	('jky-customer-id'		,				 the_row.customer_id		);
	JKY.set_value	('jky-customer-name'	,				 the_row.customer_name 		);
	JKY.set_value	('jky-salesman-id'		,				 the_row.salesman_id		);
	JKY.set_value	('jky-salesman-name'	,				 the_row.salesman_name		);
	JKY.set_value	('jky-contact-id'		,				 the_row.contact_id			);
	JKY.set_value	('jky-contact-name'		,				 the_row.contact_name + ' : ' +	the_row.contact_mobile);

	var my_sub_amount = (the_row.quoted_amount - the_row.discount_amount - the_row.advanced_amount).toFixed(2);
	JKY.set_value	('jky-quoted-amount'	,				 the_row.quoted_amount		);
	JKY.set_value	('jky-discount-amount'	,				 the_row.discount_amount	);
	JKY.set_value	('jky-advanced-amount'	,				 the_row.advanced_amount	);
	JKY.set_value	('jky-sub-amount'		,				 my_sub_amount				);
	JKY.set_value	('jky-payments'			,				 the_row.payments			);
	JKY.set_value	('jky-purchase-order'	,				 the_row.purchase_order		);
	JKY.set_value	('jky-customers'		,				 the_row.customers			);
	JKY.set_value	('jky-remarks'			, JKY.decode	(the_row.remarks			));
	JKY.display_lines();
};

/**
 *	set add new row
 */
JKY.set_add_new_row = function() {
	JKY.disable_button('jky-action-generate');
	JKY.disable_button('jky-action-gen-sale');
	JKY.disable_button('jky-action-delete'	);
	JKY.disable_button('jky-action-close'	);

	JKY.hide_parent ('jky-status');
	JKY.set_value	('jky-quotation-number'	, JKY.t('New'));
	JKY.set_date	('jky-quoted-date'		, JKY.out_time(JKY.get_now()));
	JKY.set_date	('jky-produce-from-date', JKY.out_date(JKY.get_date()));
	JKY.set_date	('jky-produce-to-date'	, JKY.out_date(JKY.get_date()));
	JKY.set_value	('jky-customer-id'		, null);
	JKY.set_value	('jky-customer-name'	, '');
	JKY.set_value	('jky-salesman-id'		, JKY.Session.get_value('contact_id'));
	JKY.set_value	('jky-salesman-name'	, JKY.Session.get_value('full_name'));
	JKY.set_value	('jky-contact-id'		, null);
	JKY.set_value	('jky-contact-name'		, '');

	JKY.set_value	('jky-quoted-amount'	, 0 );
	JKY.set_value	('jky-discount-amount'	, 0 );
	JKY.set_value	('jky-advanced-amount'	, 0 );
	JKY.set_value	('jky-sub-amount'		, 0 );
	JKY.set_value	('jky-payments'			, '');
	JKY.set_value	('jky-purchase-order'	, '');
	JKY.set_value	('jky-customers'		, '');

	JKY.set_value	('jky-remarks'			, '');
};

/**
 *	set replace
 */
JKY.set_replace = function() {
	JKY.disable_button('jky-action-generate');
	JKY.disable_button('jky-action-gen-sale');
	JKY.disable_button('jky-action-delete'	);
	JKY.disable_button('jky-action-close'	);

	JKY.show_parent ('jky-status');
	JKY.set_html	('jky-status', JKY.set_options('', '', 'Draft', 'Active', 'Closed'));
	JKY.set_value	('jky-quotation-number'	, '');
	JKY.set_date	('jky-quoted-date'		, '');
	JKY.set_date	('jky-produce-from-date', '');
	JKY.set_date	('jky-produce-to-date'	, '');
	JKY.set_value	('jky-customer-id'		, null);
	JKY.set_value	('jky-customer-name'	, '');
	JKY.set_value	('jky-salesman-id'		, null);
	JKY.set_value	('jky-salesman-name'	, '');
	JKY.set_value	('jky-contact-id'		, null);
	JKY.set_value	('jky-contact-name'		, '');

	JKY.set_value	('jky-quoted-amount'	, '');
	JKY.set_value	('jky-discount-amount'	, '');
	JKY.set_value	('jky-advanced-amount'	, '');
	JKY.set_value	('jky-sub-amount'		, '');
	JKY.set_value	('jky-payments'			, '');
	JKY.set_value	('jky-purchase-order'	, '');
	JKY.set_value	('jky-customers'		, '');

	JKY.hide('jky-form-tabs');
};

/**
 *	get form set
 */
JKY.get_form_set = function() {
	var my_customer_id	= JKY.get_value('jky-customer-id'	);
	var my_salesman_id	= JKY.get_value('jky-salesman-id'	);
	var my_contact_id	= JKY.get_value('jky-contact-id'	);
		my_customer_id	= (my_customer_id	=== '') ? 'null' : my_customer_id	;
		my_salesman_id	= (my_salesman_id	=== '') ? 'null' : my_salesman_id	;
		my_contact_id	= (my_contact_id	=== '') ? 'null' : my_contact_id	;

	var my_set = ''
		+       '  customer_id=  ' + my_customer_id
		+       ', salesman_id=  ' + my_salesman_id
		+        ', contact_id=  ' + my_contact_id
		+         ', quoted_at=  ' +			JKY.inp_time ('jky-quoted-date'			)
		+ ', produce_from_date=  ' +			JKY.inp_date ('jky-produce-from-date'	)
		+   ', produce_to_date=  ' +			JKY.inp_date ('jky-produce-to-date'		)
		+     ', quoted_amount=  ' +			JKY.get_value('jky-quoted-amount'		)
		+   ', discount_amount=  ' +			JKY.get_value('jky-discount-amount'		)
		+   ', advanced_amount=  ' +			JKY.get_value('jky-advanced-amount'		)
		+          ', payments=\'' +			JKY.get_value('jky-payments'			)	+ '\''
		+    ', purchase_order=\'' +			JKY.get_value('jky-purchase-order'		)	+ '\''
		;
	return my_set;
};

/**
 *	get replace set
 */
JKY.get_replace_set = function() {
	var my_set = '';
	if (!JKY.is_empty(JKY.get_value('jky-status'			)))	{my_set +=            ', status=\''	+ JKY.get_value('jky-status'			) + '\'';}
	if (!JKY.is_empty(JKY.inp_date ('jky-produce-from-date'	)))	{my_set += ', produce_from_date=  ' + JKY.inp_date ('jky-produce-from-date'	);}
	if (!JKY.is_empty(JKY.inp_date ('jky-produce-to-date'	)))	{my_set +=   ', produce_to_date=  ' + JKY.inp_date ('jky-produce-to-date'	);}
	if (!JKY.is_empty(JKY.get_value('jky-quoted-amount'		)))	{my_set +=     ', quoted_amount=  '	+ JKY.get_value('jky-quoted-amount'		);}
	if (!JKY.is_empty(JKY.get_value('jky-discount-amount'	)))	{my_set +=   ', discount_amount=  '	+ JKY.get_value('jky-discount-amount'	);}
	if (!JKY.is_empty(JKY.get_value('jky-advanced-amount'	)))	{my_set +=   ', advanced_amount=  ' + JKY.get_value('jky-advanced-amount'	);}
	if (!JKY.is_empty(JKY.get_value('jky-payments'			)))	{my_set +=          ', payments=\'' + JKY.get_value('jky-payments'			) + '\'';}
	if (!JKY.is_empty(JKY.get_value('jky-purchase-order'	)))	{my_set +=    ', purchase_order=\'' + JKY.get_value('jky-purchase-order'	) + '\'';}
	return my_set;
};

JKY.display_list = function() {
	JKY.show('jky-action-print'  );
	if (JKY.Session.get_value('user_role') == 'Admin'
	||  JKY.Session.get_value('user_role') == 'Support') {
		JKY.show('jky-action-replace');
	}
};

JKY.display_form = function() {
	JKY.show('jky-action-print'  );
	JKY.show('jky-action-approve');
	JKY.show('jky-action-copy'   );
};

JKY.zero_value = function(the_id, the_name) {
	JKY.App.process_change_input(the_id);
	$('#' + the_name).val('0');
};

JKY.process_validation = function() {
	var my_error = '';

	var my_produce_from_date = JKY.inp_date('jky-produce-from-date');
	var my_produce_to_date	 = JKY.inp_date('jky-produce-to-date'  );
	if (my_produce_from_date > my_produce_to_date) {
		my_error += JKY.set_is_invalid('Production To');
	}
	return my_error;
}

JKY.process_copy = function(the_id, the_row) {
	var my_set	= '  quoted_at =\'' + JKY.get_now() + '\''
				+ ', quoted_pieces = ' + the_row.quoted_pieces
				;
	var my_data =
		{ method	: 'update'
		, table		: 'Quotations'
		, set		: my_set
		, where		: 'id = ' + the_id
		};
	JKY.ajax(true, my_data);
	JKY.copy_lines(the_row.id, the_id);
};

JKY.process_delete = function(the_id, the_row) {
	var my_data = '';
	var my_rows = JKY.get_rows('QuotLines', the_id);

	for(var i in my_rows) {
		my_data =
			{ method: 'delete_many'
			, table : 'QuotColors'
			, where : 'parent_id = ' + my_rows[i].id
			};
		JKY.ajax(true, my_data);
	}

	my_data =
		{ method: 'delete_many'
		, table : 'QuotLines'
		, where : 'parent_id = ' + the_id
		};
	JKY.ajax(true, my_data);
};

/* -------------------------------------------------------------------------- */
JKY.generate_sale = function() {
	var my_error = '';
	var my_customer_id = JKY.get_value('jky-customer-id');
	if (!my_customer_id)		my_error += '<br>' + JKY.t('Customer') + ' ' + JKY.t('is required');
	var my_quoted_pieces = JKY.get_value_by_id('Quotations', 'quoted_pieces', JKY.row.id);
	if (my_quoted_pieces <= 0)	my_error += '<br>' + JKY.t('there is not any Quoted Piece');

	if (!JKY.is_empty(my_error)) {
		JKY.display_message(JKY.t('Sale cannot be generated, because'));
		JKY.display_message(my_error);
		return;
	}

	var my_data =
		{ method	: 'generate'
		, table		: 'Sale'
		, id		:  JKY.row.id
		};
	JKY.ajax(false, my_data, function(the_response) {
		JKY.display_message('Sale row generated: ' + JKY.row.id);
		JKY.App.display_row();
	});
};

/* -------------------------------------------------------------------------- */
JKY.generate_osa = function() {
	var my_error = '';
	var my_customer_id = JKY.get_value('jky-customer-id');
	if (!my_customer_id)		my_error += '<br>' + JKY.t('Customer') + ' ' + JKY.t('is required');
	var my_quoted_pieces = JKY.get_value_by_id('Quotations', 'quoted_pieces', JKY.row.id);
	if (my_quoted_pieces <= 0)	my_error += '<br>' + JKY.t('there is not any Quoted Piece');

	if (!JKY.is_empty(my_error)) {
		JKY.display_message(JKY.t('OSA cannot be generated, because'));
		JKY.display_message(my_error);
		return;
	}

	var my_data =
		{ method	: 'generate'
		, table		: 'OSA'
		, id		:  JKY.row.id
		};
	JKY.ajax(false, my_data, function(the_response) {
		JKY.display_message('OSA row generated: ' + JKY.row.id);
		JKY.App.display_row();
	});
};

/* -------------------------------------------------------------------------- */
JKY.save_customers = function() {
	var my_set	=   'customers = \'' + JKY.get_value('jky-customers') + '\'';
	var my_data =
		{ method: 'update'
		, table : 'Quotations'
		, set	:  my_set
		, where : 'Quotations.id = ' + JKY.row.id
		};
	JKY.ajax(true, my_data, function(the_response) {
		JKY.display_message('Customers saved, ' + the_response.message);
		JKY.row = JKY.get_row('Quotations', JKY.row.id);
	});
};

JKY.save_remarks = function() {
	var my_set	=   'remarks = \'' + JKY.get_value('jky-remarks') + '\'';
	var my_data =
		{ method: 'update'
		, table : 'Quotations'
		, set	:  my_set
		, where : 'Quotations.id = ' + JKY.row.id
		};
	JKY.ajax(true, my_data, function(the_response) {
		JKY.display_message('Remarks saved, ' + the_response.message);
		JKY.row = JKY.get_row('Quotations', JKY.row.id);
	});
};

JKY.update_customer_info = function() {
	var my_row = JKY.get_row('Contacts', JKY.get_value('jky-customer-id'));
	JKY.set_value('jky-contact-id'		, null);
	JKY.set_value('jky-contact-name'	, '');
	JKY.set_value('jky-payments'		, my_row.payments);
}

JKY.update_sub_amount = function() {
	if (JKY.is_empty($('#jky-advanced-amount').val()))		JKY.set_value('jky-advanced-amount', 0);
	var my_quoted_amount	= parseFloat($('#jky-quoted-amount'		).val());
	var my_discount_amount	= parseFloat($('#jky-discount-amount'	).val());
	var my_advanced_amount	= parseFloat($('#jky-advanced-amount'	).val());
	var my_sub_amount		= my_quoted_amount - my_discount_amount - my_advanced_amount;
	JKY.set_value('jky-sub-amount', my_sub_amount.toFixed(2));
};

/**
 * re-calculate amounts
 */
JKY.update_quotation_amount = function() {
	var my_quoted_amount	= 0;
	var my_discount_amount	= 0;
	var my_line_peso		= 0;
	var my_line_units		= 0;
	var my_line_discount	= '';
	var my_color_units		= 0;
	var my_color_price		= 0;
	var my_color_discount	= '';
	var my_color_amount		= 0;
	$('#jky-lines-body tr').each(function() {
		var my_quot_line_id = $(this).attr('quot_line_id');
		if (my_quot_line_id) {
			my_line_peso		= parseFloat($(this).find('.jky-product-peso'	).val());
			my_line_units		= parseInt	($(this).find('.jky-product-units'	).val());
			my_line_discount	=			 $(this).find('.jky-discount'		).val()	;
			if (my_line_units === 0)		my_line_peso = 1;
		}else{
			my_color_units		= parseFloat($(this).find('.jky-quoted-units'	).val());
			my_color_price		= parseFloat($(this).find('.jky-quoted-price'	).val());
			my_color_discount	=			 $(this).find('.jky-discount'		).val() ;

			my_color_amount		=  my_line_peso * my_color_units * my_color_price;
			my_quoted_amount	+= my_color_amount;

			if (my_color_discount === '')		my_color_discount = my_line_discount;
			my_color_discount = my_color_discount.trim();
			var my_length = my_color_discount.length;
			if (my_color_discount.substr(my_length-1, 1) === '%') {
				my_color_discount = parseFloat(my_color_discount);
				if (!isNaN(my_color_discount)) {
					my_discount_amount += my_color_amount * my_color_discount / 100;
				}
			}else{
				my_color_discount = parseFloat(my_color_discount);
				if (!isNaN(my_color_discount)) {
					my_discount_amount += my_line_peso * my_color_units * my_color_discount;
				}
			}
		}
	});
	var my_advanced_amount	= parseFloat($('#jky-advanced-amount').val());
	var my_sub_amount		= my_quoted_amount - my_discount_amount - my_advanced_amount;
	JKY.set_value('jky-quoted-amount'	, my_quoted_amount  .toFixed(2));
	JKY.set_value('jky-discount-amount'	, my_discount_amount.toFixed(2));
	JKY.set_value('jky-sub-amount'		, my_sub_amount		.toFixed(2));

	var my_set	=     'quoted_amount = ' + my_quoted_amount
				+ ', discount_amount = ' + my_discount_amount
				;
	var my_data =
		{ method: 'update'
		, table : 'Quotations'
		, set	:  my_set
		, where : 'Quotations.id = ' + JKY.row.id
		};
	JKY.ajax(true, my_data);
};

/**
 * print row
 */
JKY.print_row = function(the_id) {
	JKY.show('jky-loading');
	JKY.display_message('print_row: ' + the_id);
	var my_names;
	var my_extension;
	var my_row = JKY.get_row(JKY.App.get_prop('table_name'), the_id);

//window.print();
	var my_html = ''
		+ "<table class='jky-print-box'><tbody>"

		+ "<tr>"
		+ "<td class='jky-print-label'><span>Quotation Number</span>:</td><td id='jky-print-quotation-number'	class='jky-print-name' ></td>"
		+ "<td class='jky-print-label'><span>            Date</span>:</td><td id='jky-print-quoted-date'		class='jky-print-value'></td>"
		+ "</tr>"

		+ "<tr>"
		+ "<td class='jky-print-label'><span>        Customer</span>:</td><td id='jky-print-customer-name'		class='jky-print-name' ></td>"
		+ "<td class='jky-print-label'></td>"
		+ "</tr>"

		+ "<tr>"
		+ "<td class='jky-print-label'><span>         Contact</span>:</td><td id='jky-print-contact-name'		class='jky-print-name' ></td>"
//		+ "<td class='jky-print-label'><span>   Expected Date</span>:</td><td id='jky-print-expected-date'		class='jky-print-value'></td>"
		+ "<td class='jky-print-label'><span> Production From</span>:</td><td id='jky-print-produce-from-date'	class='jky-print-value'></td>"
		+ "</tr>"

		+ "<tr>"
		+ "<td class='jky-print-label'><span>        Payments</span>:</td><td id='jky-print-payments'			class='jky-print-name' ></td>"
//		+ "<td class='jky-print-label'><span>  Delivered Date</span>:</td><td id='jky-print-delivered-date'		class='jky-print-value'></td>"
		+ "<td class='jky-print-label'><span>   Production To</span>:</td><td id='jky-print-produce-to-date'	class='jky-print-value'></td>"
		+ "</tr>"

		+ "<tr>"
		+ "<td class='jky-print-label'><span>  Purchase Order</span>:</td><td id='jky-print-purchase-order'		class='jky-print-name' ></td>"
		+ "<td class='jky-print-label'><span> Advanced Amount</span>:</td><td id='jky-print-advanced-amount'	class='jky-print-value'></td>"
		+ "</tr>"

		+ "</tbody></table>"
		+ "<br>"

		+ "<table class='jky-print-box'>"
		+ "<tbody id='jky-print-lines-body'></body>"
		+ "</table>"
		+ "<br>"

		+ "<div class='jky-print-box'>"
		+ "<div id='jky-print-customers'></div>"
		+ "</div>"
		+ "<br>"

		+ "<div class='jky-print-box'>"
		+ "<div id='jky-print-remarks'></div>"
		+ "</div>"
		;
	var my_remarks	= JKY.get_config_value('Remarks', 'Quotation');

	JKY.set_html('jky-printable', my_html);

	JKY.set_html('jky-print-quotation-number'	, my_row.quotation_number);
	JKY.set_html('jky-print-customer-name'		, my_row.customer_name);
	JKY.set_html('jky-print-contact-name'		, JKY.fix_null(my_row.contact_name) + ' : ' + JKY.fix_null(my_row.contact_mobile));
	JKY.set_html('jky-print-payments'			, JKY.fix_null(my_row.payments));
	JKY.set_html('jky-print-purchase-order'		, JKY.fix_null(my_row.purchase_order));

	JKY.set_html('jky-print-quoted-date'		, JKY.out_date(my_row.quoted_at			));
	JKY.set_html('jky-print-produce-from-date'	, JKY.out_date(my_row.produce_from_date	));
	JKY.set_html('jky-print-produce-to-date'	, JKY.out_date(my_row.produce_to_date	));
	JKY.set_html('jky-print-advanced-amount'	, my_row.advanced_amount);

	JKY.set_html('jky-print-lines-body'			, JKY.print_lines(the_id));

	JKY.set_html('jky-print-customers'			, JKY.nl2br(my_row.customers));
	JKY.set_html('jky-print-remarks'			, JKY.nl2br(my_remarks));
	JKY.t_tag	('jky-printable', 'span');

//	JKY.show('jky-printable');
	$("#jky-printable").print();
	JKY.hide('jky-loading');
};

/**
 * approve row
 */
/**
JKY.approve_row = function(the_id){
	JKY.show('jky-loading');
	JKY.display_message('approve_row: ' + the_id);
	var my_names;
	var my_extension;
	var my_row = JKY.get_row(JKY.App.get_prop('table_name'), the_id);

//window.print();
	var my_html = ''
		+ "<div class='jky-print-title'><span>Approve</span></div>"

		+ "<table class='jky-print-box'><tbody>"

		+ "<tr>"
		+ "<td class='jky-print-label'><span>Quotation Number</span>:</td><td id='jky-print-quotation-number'	class='jky-print-name' ></td>"
		+ "<td class='jky-print-label'><span>            Date</span>:</td><td id='jky-print-quoted-date'		class='jky-print-value'></td>"
		+ "</tr>"

		+ "<tr>"
		+ "<td class='jky-print-label'><span>        Customer</span>:</td><td id='jky-print-customer-name'		class='jky-print-name' ></td>"
		+ "<td class='jky-print-label'></td>"
		+ "</tr>"

		+ "<tr>"
		+ "<td class='jky-print-label'><span>         Contact</span>:</td><td id='jky-print-contact-name'		class='jky-print-name' ></td>"
//		+ "<td class='jky-print-label'><span>   Expected Date</span>:</td><td id='jky-print-expected-date'		class='jky-print-value'></td>"
		+ "<td class='jky-print-label'><span> Production From</span>:</td><td id='jky-print-produce-from-date'	class='jky-print-value'></td>"
		+ "</tr>"

		+ "<tr>"
		+ "<td class='jky-print-label'><span>        Payments</span>:</td><td id='jky-print-payments'			class='jky-print-name' ></td>"
//		+ "<td class='jky-print-label'><span>  Delivered Date</span>:</td><td id='jky-print-delivered-date'		class='jky-print-value'></td>"
		+ "<td class='jky-print-label'><span>   Production To</span>:</td><td id='jky-print-produce-to-date'	class='jky-print-value'></td>"
		+ "</tr>"

		+ "<tr>"
		+ "<td class='jky-print-label'><span>  Purchase Order</span>:</td><td id='jky-print-purchase-order'		class='jky-print-name' ></td>"
		+ "<td class='jky-print-label'><span> Advanced Amount</span>:</td><td id='jky-print-advanced-amount'	class='jky-print-value'></td>"
		+ "</tr>"

		+ "</tbody></table>"
		+ "<br>"

		+ "<table class='jky-print-box'>"
		+ "<tbody id='jky-print-lines-body'></body>"
		+ "</table>"
		+ "<br>"

//		+ "<div class='jky-print-box'>"
//		+ "<div id='jky-print-customers'></div>"
//		+ "</div>"
//		+ "<br>"

//		+ "<div class='jky-print-box'>"
//		+ "<div id='jky-print-remarks'></div>"
//		+ "</div>"
		;
//	var my_remarks	= JKY.get_config_value('Remarks', 'Quotation');

	JKY.set_html('jky-printable', my_html);

	JKY.set_html('jky-print-quotation-number'	, my_row.quotation_number);
	JKY.set_html('jky-print-customer-name'		, my_row.customer_name);
	JKY.set_html('jky-print-contact-name'		, JKY.fix_null(my_row.contact_name) + ' : ' + JKY.fix_null(my_row.contact_mobile));
	JKY.set_html('jky-print-payments'			, JKY.fix_null(my_row.payments));
	JKY.set_html('jky-print-purchase-order'		, JKY.fix_null(my_row.purchase_order));

	JKY.set_html('jky-print-quoted-date'		, JKY.out_date(my_row.quoted_at			));
	JKY.set_html('jky-print-produce-from-date'	, JKY.out_date(my_row.produce_from_date	));
	JKY.set_html('jky-print-produce-to-date'	, JKY.out_date(my_row.produce_to_date	));
	JKY.set_html('jky-print-advanced-amount'	, my_row.advanced_amount);

	JKY.set_html('jky-print-lines-body'			, JKY.approve_lines(the_id));

//	JKY.set_html('jky-print-customers'			, JKY.nl2br(my_row.customers));
//	JKY.set_html('jky-print-remarks'			, JKY.nl2br(my_remarks));
	JKY.t_tag	('jky-printable', 'span');

//	JKY.show('jky-printable');
	$("#jky-printable").print();
	JKY.hide('jky-loading');
};
 */

/**
 * approve row
 */
JKY.approve_row = function(the_id){
	JKY.show('jky-loading');
	JKY.display_message('approve_row: ' + the_id);
	var my_names;
	var my_extension;
	var my_row = JKY.get_row(JKY.App.get_prop('table_name'), the_id);

//window.print();
	var my_html = ''
		+ "<table class='jky-approve-box'><tbody>"

		+ "<tr>"
		+ "<td class='jky-approve-label'><span>     Customer</span>:</td><td id='jky-approve-customer-name'		class='jky-approve-customer'	></td>"
		+ "<td class='jky-approve-label'><span>    Quotation</span>:</td><td id='jky-approve-quotation-number'	class='jky-approve-quotation'	></td>"
		+ "<td class='jky-approve-label'>						 PC:</td><td id='jky-approve-produce-to-date'	class='jky-approve-date'		></td>"
		+ "<td class='jky-approve-space'></td>"
		+ "<td class='jky-approve-label'><span>         Date</span>:</td><td id='jky-approve-quoted-date'		class='jky-approve-date'		></td>"
		+ "</tr>"

		+ "</tbody></table>"
		+ "<br>"

		+ "<div id='jky-approve-body'></div>"

		+ "<div id='jky-approve-remarks'></div>"
		+ "<br>"
		;
	JKY.set_html('jky-printable', my_html);

	JKY.set_html('jky-approve-customer-name'	,				 my_row.customer_name	);
	JKY.set_html('jky-approve-quotation-number'	,				 my_row.quotation_number);
	JKY.set_html('jky-approve-quoted-date'		, JKY.out_date	(my_row.quoted_at		));
	JKY.set_html('jky-approve-produce-to-date'	, JKY.out_date	(my_row.produce_to_date	));

	JKY.set_html('jky-approve-body', JKY.approve_lines(the_id));

	if (!JKY.is_empty(my_row.remarks)) {
		my_html = ''
			+ '<div class="jky-approve-box">'
			+ '<span>Remarks</span>: '
			+ JKY.nl2br(my_row.remarks)
			+ '</div>'
			;
		JKY.set_html('jky-approve-remarks', my_html);
	}
	JKY.t_tag('jky-printable', 'span');

//	JKY.show('jky-printable');
	$("#jky-printable").print();
	JKY.hide('jky-loading');
};
