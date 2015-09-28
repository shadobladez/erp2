"use strict";
var JKY = JKY || {};
/**
 * JKY.Application - process all application functions
 *
 * require:	JKY.Utils.js
 *			JKY.Changes.js
 *			JKY.Validation.js
 */
JKY.Application = function() {
	var my_args		= null;
	var my_count	= 0;
	var my_index	= 0;
	var my_first	= true;
	var my_skip_form;
	var my_saved_id	= null;

	JKY.row 		= null;		//	null=Add New

	JKY.checkout	= JKY.checkout	|| [];
	JKY.incoming	= JKY.incoming	|| [];
	JKY.loadout		= JKY.loadout	|| [];
	JKY.loadsale	= JKY.loadsale	|| [];
	JKY.osas		= JKY.osas		|| [];
	JKY.planning	= JKY.planning	|| [];
	JKY.receive		= JKY.receive	|| [];
	JKY.purchase	= JKY.purchase	|| [];
	JKY.sales		= JKY.sales		|| [];
	JKY.shipdyer	= JKY.shipdyer	|| [];

	JKY.checkout	.select = JKY.checkout	.select	||	'Draft + Active';
	JKY.incoming	.select = JKY.incoming	.select	||			'Active';
	JKY.loadout		.select	= JKY.loadout	.select	||			'Active';
	JKY.loadsale	.select	= JKY.loadsale	.select	||	'Draft + Active';
	JKY.osas		.select	= JKY.osas		.select	||	'Draft + Active';
	JKY.planning	.select = JKY.planning	.select	||	'Draft + Active';
	JKY.receive		.select	= JKY.receive	.select	||			'Active';
	JKY.purchase	.select = JKY.purchase	.select	||	'Draft + Active';
	JKY.sales		.select	= JKY.sales		.select	||	'Draft + Active';
	JKY.shipdyer	.select	= JKY.shipdyer	.select	||	'Draft + Active';

/**
 *	set all events (run only once per load)
 */
	var my_set_all_events = function() {
		JKY.display_trace('my_set_all_events - ' + my_args.program_name);
		if (JKY.is_loaded('jky-body-loaded')) {
			if (my_first == true) {
				my_first = false;

				//	this is needed only to avoid flick on first wide display of sidebar 
				JKY.show('jky-app-header');
				JKY.show('jky-app-table');

				JKY.display_trace('my_set_all_events - first - ' + my_args.program_name);
				$('#jky-app-select'			).change(function() {JKY.Changes.can_leave(function() { my_change_select		();})});
				$('#jky-app-filter'			).change(function() {JKY.Changes.can_leave(function() { my_change_filter		();})});
				$('#jky-action-add-new'		).click (function() {JKY.Changes.can_leave(function() { my_process_add_new		();})});
				$('#jky-action-combine'		).click (function() {JKY.Changes.can_leave(function() { my_process_combine		();})});
				$('#jky-action-print'		).click (function() {JKY.Changes.can_leave(function() { my_process_print		();})});
				$('#jky-action-approve'		).click (function() {JKY.Changes.can_leave(function() { my_process_approve		();})});
				$('#jky-action-export'		).click (function() {JKY.Changes.can_leave(function() { my_process_export		();})});
				$('#jky-action-publish'		).click (function() {JKY.Changes.can_leave(function() { my_process_publish		();})});
				$('#jky-action-replace'		).click (function() {JKY.Changes.can_leave(function() { my_display_replace		();})});
				$('#jky-action-prev'		).click (function() {JKY.Changes.can_leave(function() { my_display_prev			();})});
				$('#jky-action-next'		).click (function() {JKY.Changes.can_leave(function() { my_display_next			();})});
				$('#jky-action-list'		).click (function() {JKY.Changes.can_leave(function() { my_display_list			();})});
				$('#jky-action-graph'		).click (function() {JKY.Changes.can_leave(function() { my_display_graph		();})});
				$('#jky-action-form'		).click (function() {JKY.Changes.can_leave(function() { my_display_form			();})});
			}
			$('#jky-action-change'		).click( function() {JKY.Changes.can_leave(function() { my_change_status(JKY.row.id);})});
			$('#jky-action-save'		).click (function() {									my_process_save			();});
			$('#jky-action-copy'		).click (function() {JKY.Changes.can_leave(function() { my_process_copy			();})});
			$('#jky-action-update'		).click (function() {									my_process_replace		();});
			$('#jky-action-delete'		).click (function() {									my_process_delete		();});
			$('#jky-action-cancel'		).click (function() {JKY.Changes.can_leave(function() { my_process_cancel		();})});
//			disabled by tablesorter
//			$('#jky-check-all'			).click (function() {									my_set_all_check	(this);});

//			this delay is needed in threadforecast.js
			setTimeout(function() {
					my_set_initial_values();
			}, 100);

			JKY.set_all_events();	// from caller
		}else{
			setTimeout(function() {my_set_all_events()}, 100);
		}
	}

/**
 *	set initial values (run only once per load)
 */
	var my_set_initial_values = function() {
		JKY.set_css('jky-app-breadcrumb', 'color', '#4C4C4C');
		JKY.display_trace('my_set_initial_values - ' + my_args.program_name);
		if (JKY.is_loaded('jky-body')) {
			JKY.set_html ('jky-app-breadcrumb', JKY.t(my_args.program_name));
			JKY.set_value('jky-app-filter', my_args.filter);
			JKY.hide('jky-app-select-line');
			my_display_list();
//			my_display_form();
			JKY.show('jky-app-header');
			JKY.hide('jky-action-publish'	);
			JKY.show('jky-action-list'		);
			JKY.hide('jky-action-graph'		);
			JKY.show('jky-action-form'		);
			JKY.set_initial_values();

//			$('#jky-form-data       input[id]').each (function() {$(this).change(function() 	{my_process_change_input(this);});});
//			$('#jky-form-data     input[name]').each (function() {$(this).change(function() 	{my_process_change_input(this);});});
//			$('#jky-form-data      select[id]').each (function() {$(this).change(function()		{my_process_change_input(this);});});
//			$('#jky-form-data    textarea[id]').each (function() {$(this).change(function()		{my_process_change_input(this);});});

			$('#jky-form-data       input[id]').each (function() {$(this).keyup (function(event)		{my_process_keyup_input (this, event, true	);});});
			$('#jky-form-data     input[name]').each (function() {$(this).keyup (function(event)		{my_process_keyup_input (this, event, true	);});});
			$('#jky-form-data    textarea[id]').each (function() {$(this).keyup (function(event)		{my_process_keyup_input (this, event, false	);});});

			$('#jky-form-data       input[id]').each (function() {$(this).change(function()				{my_process_change_input(this);});});
			$('#jky-form-data     input[name]').each (function() {$(this).change(function()				{my_process_change_input(this);});});
			$('#jky-form-data      select[id]').each (function() {$(this).change(function()				{my_process_change_input(this);});});
			$('#jky-form-data           .date').each (function() {$(this).on('changeDate', function()	{my_process_change_input(this);});});

//			do not use blur, it will break [click] from jky-action-...
//			$('#jky-form-data       input[id]').each (function() {$(this).on('blur', function()		{my_process_verify_input (this);});});
//			$('#jky-form-data     input[name]').each (function() {$(this).on('blur', function()		{my_process_verify_input (this);});});
//			$('#jky-form-data    textarea[id]').each (function() {$(this).on('blur', function()		{my_process_verify_input (this);});});
//			$('#jky-form-data      select[id]').each (function() {$(this).on('blur', function()		{my_process_verify_input (this);});});
//			$('#jky-form-data           .date').each (function() {$(this).on('blur', function()		{my_process_verify_input (this);});});

			$('#jky-form-data       input[id]').each (function() {$(this).change(function()		{my_process_verify_input (this);});});
			$('#jky-form-data     input[name]').each (function() {$(this).change(function()		{my_process_verify_input (this);});});
			$('#jky-form-data    textarea[id]').each (function() {$(this).change(function()		{my_process_verify_input (this);});});
			$('#jky-form-data      select[id]').each (function() {$(this).change(function()		{my_process_verify_input (this);});});
			$('#jky-form-data           .date').each (function() {$(this).change(function()		{my_process_verify_input (this);});});
			JKY.Changes.reset();
		}else{
			setTimeout(function() {my_set_initial_values()}, 100);
		}
	}

	var my_change_select = function(){
			my_args.select = JKY.get_value('jky-app-select');
			JKY.display_trace('my_change_select: ' + my_args.select);
			my_display_list();

			switch(my_args.program_name) {
				case 'Batches'			:	JKY.incoming	.select = my_args.select; break;
				case 'BatchOuts'		:	JKY.checkout	.select = my_args.select; break;
//				case 'Boxes'			:	JKY.incoming	.select = my_args.select; break;
				case 'CheckOuts'		:	JKY.checkout	.select = my_args.select; break;
				case 'Incomings'		:	JKY.incoming	.select = my_args.select; break;
				case 'LoadOuts'			:	JKY.loadout		.select = my_args.select; break;
				case 'LoadSales'		:	JKY.loadsale	.select = my_args.select; break;
				case 'Orders'			:	JKY.planning	.select = my_args.select; break;
				case 'Purchases'		:	JKY.purchase	.select = my_args.select; break;
				case 'Purchase Lines'	:	JKY.purchase	.select = my_args.select; break;
				case 'Quotations'		:	JKY.sales		.select	= my_args.select; break;
				case 'Thread Dyers'		:	JKY.planning	.select = my_args.select; break;
			}
		}

	var my_change_filter = function(){
			my_args.filter = JKY.get_value('jky-app-filter');
			JKY.display_trace('my_change_filter: ' + my_args.filter);
			my_display_list();
		}

	var my_display_prev = function() {
			JKY.display_trace('my_display_prev: ' + my_index);
			my_index = (my_index <= 1) ? my_count : (my_index-1);
			my_display_row(my_index);
		}

	var my_display_next = function() {
			JKY.display_trace('my_display_next: ' + my_index);
			my_index = (my_index >= my_count) ? 1 : (my_index+1);
			my_display_row(my_index);
		}

	var my_set_all_check = function(the_index) {
			JKY.display_trace('set_all_check');
			if ($(the_index).is(':checked')) {
				$('#jky-table-body .jky-td-checkbox input').each(function() {$(this).prop('checked', true);})
			}else{
				$('#jky-table-body .jky-td-checkbox input').each(function() {$(this).prop('checked', false);})
			}
		}

	var my_set_checkbox = function(the_index) {
			JKY.display_trace('set_checkbox');
			my_skip_form = true;
			my_index = the_index.rowIndex - 1;
			return false;		//	to avoid the trigger of click tr row
		}

	var my_display_list = function() {
			JKY.display_trace('my_display_list');
			JKY.show('jky-app-filter'		);
			JKY.show('jky-app-more'			);
			JKY.hide('jky-app-navs'			);
			JKY.hide('jky-app-add-new'		);
			JKY.show('jky-app-counters'		);
			if (JKY.is_loaded('jky-app-form')) {
//				JKY.show('jky-action-add-new');
				JKY.enable_button('jky-action-add-new');
			}else{
				JKY.hide('jky-action-add-new');
			}
			JKY.hide('jky-action-combine'	);
			JKY.hide('jky-action-print'		);
			JKY.hide('jky-action-approve'	);
			JKY.hide('jky-action-clear'		);
			JKY.hide('jky-action-confirm'	);
			if (JKY.Session.get_value('user_role') == 'Support'
			|| (JKY.Session.get_value('user_role') == 'Admin' && my_args.table_name == 'ThreadForecast')
			|| (JKY.Session.get_value('user_role') == 'Admin' && my_args.table_name == 'Pieces')) {
				JKY.show('jky-action-export');
			}else{
				JKY.hide('jky-action-export');
			}
			JKY.hide('jky-action-replace'	);
			JKY.hide('jky-action-save'		);
			JKY.hide('jky-action-copy'		);
			JKY.disable_button('jky-action-delete');
			JKY.hide('jky-action-cancel'	);
			JKY.show('jky-app-table'		);
			JKY.hide('jky-app-graph'		);
			JKY.hide('jky-app-form'			);
/*
			var my_html = ''
				+ '<tr>'
				+ '<th class="jky-td-checkbox"><input id="jky-check-all" type="checkbox" title="Click here to check all records" /></th>'
				+ JKY.set_table_header()
				+ '</tr>'
				;
			JKY.set_html('jky-table-header', my_html);
*/
			JKY.display_list();
			my_load_table();
		}

	var my_load_table = function() {
		JKY.display_trace('my_load_table');
		if (my_args.table_name == '') {
//			to bind [Check All] function for screens without table loaded
			$('#jky-check-all').click (function() {my_set_all_check(this);});
			return;
		}

		JKY.show('jky-loading');
		if (my_args.program_name == 'Receive NFEs') {
			var my_data =
				{ method	: 'glob'
				, select	: my_args.select
				, filter	: my_args.filter
				};
			JKY.ajax(false, my_data, my_process_load_success);
		}else{
			var my_data =
				{ method	: 'get_index'
				, table		: my_args.table_name
				, specific	: my_args.specific
				, select	: my_args.select
				, filter	: my_args.filter
				, display	: my_args.display
				, order_by	: my_args.sort_by + ' ' + my_args.sort_seq
				};
			JKY.ajax(false, my_data, my_process_load_success);
		}
	}

	var my_process_load_success = function(response) {
		JKY.display_trace('my_process_load_success');
		var my_rows	= response.rows;
		my_count	= my_rows.length;
		if (my_index == 0 || my_index > my_count) 		my_index = my_count > 0 ? 1 : 0;
		var my_html = '';
		for(var i=0; i<my_count; i++) {
			var my_row = my_rows[i];
			my_html += my_set_table_row(my_args, my_row);
		}
		JKY.set_html('jky-app-index', my_index);
		JKY.set_html('jky-app-count', my_count);
		JKY.set_html('jky-table-body', my_html);
		setTimeout(function() {
			my_tablesorter();
		}, 10);
//		JKY.setTableWidthHeight('jky-app-table', 851, 221, 390, 115);
//		JKY.setTableWidthHeight('jky-app-table', 851, 240, 350, 125);
		JKY.set_focus('jky-app-filter');
		JKY.hide('jky-loading');
	}

	var my_tablesorter = function() {
		JKY.display_trace('my_tablesorter');
		var my_date_format = 'yyyymmdd';
		var	my_locale = JKY.Session.get_value('locale');
		switch(my_locale) {
			case 'en_US'	: my_date_format = 'mmddyyyy'; break;
			case 'pt_BR'	: my_date_format = 'ddmmyyyy'; break;
		}

		var my_sort_false = {};
			my_sort_false[0] = {sorter:false};
		if (my_args.sort_false) {
			my_sort_false[my_args.sort_false] = {sorter:false};
		}
		var my_sort_list = my_args.sort_list ? my_args.sort_list : [[1,0]];
		$("#jky-app-table").tablesorter(
			{delayInit		:true
			,dateFormat		:my_date_format
			,headers		:my_sort_false		//	disable sort on checkbox column
			,sortList		:my_sort_list		//	sort on second column, order asc
			,showProcessing	:true
			});

		$("#jky-app-table").trigger("update", true);
//		to re-bind [Check All] after tablesorter
		$('#jky-check-all').click (function() {JKY.App.set_all_check(this);});
	}

	var my_set_table_row = function(the_args, the_row) {
		var my_checkbox = '<input type="checkbox" onclick="' + the_args.object_name + '.set_checkbox(this)" row_id=' + the_row.id + ' />';
		var my_clickrow = JKY.is_loaded('jky-app-form') ? ' onclick="' + the_args.object_name + '.display_form(this)"' : '';
		var my_class = '';
		if (my_args.class === 'status') {
			my_class = ' class="' + the_row.status + '"';
		}else
		if (my_args.class === 'scheduled_date') {
			var my_date = the_row.scheduled_date;
			if (my_date) {
				var my_today = JKY.get_date();
					 if (my_date < my_today)	my_class = ' class="Past"'	 ;
				else if (my_date > my_today)	my_class = ' class="Future"' ;
				else							my_class = ' class="Present"';
			}
		}
		return '<tr row_id=' + the_row.id + my_clickrow + my_class + '>'
			+  '<td class="jky-td-checkbox">' + my_checkbox + '</td>'
			+  JKY.set_table_row(the_row)
			+  '</tr>'
			;
	}

/**
 *	display graph
 *
 *	$param	undefined	display last index
 *	$param	number		display new  index
 *	$param	object		display index of the row
 */
	var my_display_graph = function(the_index) {
		JKY.display_trace('my_display_graph: ' + the_index);
		if (my_args.table_name == '')		return;

		JKY.hide('jky-app-table');
		JKY.show('jky-app-graph');
		JKY.hide('jky-app-form'	);

		JKY.display_graph();
	}

/**
 *	display form
 *
 *	$param	undefined	display last index
 *	$param	number		display new  index
 *	$param	object		display index of the row
 */
	var my_display_form = function(the_index) {
		JKY.display_trace('my_display_form: ' + the_index);
//if (JKY.params === '') {
		if (typeof(the_index) == 'number') {
			my_index = the_index;
		}else
		if (typeof(the_index) == 'object') {
			if (typeof(the_index.rowIndex) == 'number') {
				my_index = the_index.rowIndex;
			}
		}
//}else{
//		my_index = the_index;
//}

		if (my_skip_form) {
			my_skip_form = false;
			return;
		}
//		JKY.show('jky-app-filter'		);
		JKY.hide('jky-app-more'			);
		JKY.show('jky-app-navs'			);
		JKY.hide('jky-app-add-new'		);
		JKY.show('jky-app-counters'		);
		JKY.enable_button('jky-action-add-new');
		JKY.hide('jky-action-combine'	);
		JKY.hide('jky-action-print'		);
		JKY.hide('jky-action-approve'	);
		JKY.show('jky-action-save'		);
		JKY.hide('jky-action-copy'		);
		JKY.hide('jky-action-update'	);

		JKY.enable_delete_button();

		JKY.show('jky-action-cancel'	);
		JKY.hide('jky-app-table'		);
		JKY.hide('jky-app-graph'		);
		JKY.show('jky-app-form'			);
		JKY.show('jky-app-upload'		);		//	??????????

		JKY.Changes.reset();		//	to reset the changes counter of previous record
		JKY.display_form();
		my_display_row(my_index);
	}

/**
 * display replace
 */
	var my_display_replace = function() {
		JKY.display_trace('my_display_replace');
		var my_counter = $('#jky-table-body .jky-td-checkbox input:checked').size();
		if (my_counter == 0) {
			JKY.display_message(JKY.t('No record selected to be replaced'));
			return;
		}

		JKY.set_html('jky-app-index', my_counter);
		JKY.display_message(JKY.t('Total records to be replaced') + ': ' + my_counter);

//		JKY.show('jky-app-filter'		);
//		JKY.hide('jky-app-more'			);
		JKY.hide('jky-app-navs'			);
		JKY.hide('jky-app-add-new'		);
		JKY.show('jky-app-counters'		);
		JKY.hide('jky-action-add-new'	);
		JKY.hide('jky-action-export'	);
		JKY.show('jky-action-replace'	);
		JKY.hide('jky-action-save'		);
		JKY.hide('jky-action-copy'		);
		JKY.show('jky-action-update'	);
		JKY.hide('jky-action-delete'	);
		JKY.show('jky-action-cancel'	);
		JKY.hide('jky-app-table'		);
		JKY.hide('jky-app-graph'		);
		JKY.show('jky-app-form'			);

		JKY.set_replace();
		JKY.set_focus(my_args.focus);
	}

	var my_display_row = function(the_index) {
		JKY.display_trace('my_display_row');
		JKY.show('jky-form-tabs');
		if (the_index) {
			my_index = the_index;
		}
		JKY.display_trace('my_index: ' + my_index);
//if (JKY.params === '') {
		JKY.row_id = $('#jky-app-table tbody tr:eq(' + (my_index-1) + ')').attr('row_id');
//}else{
//		JKY.row_id = the_index;
//}
		JKY.display_trace('JKY.row_id: ' + JKY.row_id);
		if (my_args.program_name == 'Receive NFEs') {
			JKY.row = JKY.get_xml(JKY.row_id);
		}else{
			JKY.row = JKY.get_row(my_args.table_name, JKY.row_id);
		}
		JKY.set_html('jky-app-index', my_index);

		if (JKY.row.status == 'Closed') {
//			$('#jky-app-form        a[changeable]').prop('disabled', true );
//			$('#jky-app-form   button[changeable]').prop('disabled', true );
			$('#jky-app-form    input[changeable]').prop('disabled', true );
			$('#jky-app-form   select[changeable]').prop('disabled', true );
			$('#jky-app-form textarea[changeable]').prop('disabled', true );
			$('#jky-upload-photo').css('visibility', 'hidden');
			$('.add-on').css('visibility', 'hidden');
		}else{
//			$('#jky-app-form        a[changeable]').prop('disabled', false);
//			$('#jky-app-form   button[changeable]').prop('disabled', false);
			$('#jky-app-form    input[changeable]').prop('disabled', false);
			$('#jky-app-form   select[changeable]').prop('disabled', false);
			$('#jky-app-form textarea[changeable]').prop('disabled', false);
			$('#jky-upload-photo').css('visibility', 'visible');
			$('.add-on').css('visibility', 'visible');
		}

		JKY.set_html('jky-current-status', JKY.t(JKY.row.status));
		JKY.set_form_row(JKY.row);
		JKY.set_focus(my_args.focus);
	}

	var my_process_add_new = function() {
		JKY.display_trace('my_process_add_new');
		JKY.hide('jky-form-tabs');
//		JKY.hide('jky-app-filter'		);
		JKY.hide('jky-app-more'			);
		JKY.hide('jky-app-navs'			);
		JKY.show('jky-app-add-new'		);
		JKY.hide('jky-app-counters'		);
		JKY.disable_button('jky-action-add-new'	);
		JKY.hide('jky-action-combine'	);
		JKY.hide('jky-action-print'		);
		JKY.hide('jky-action-approve'	);
		JKY.show('jky-action-save'		);
		JKY.hide('jky-action-copy'		);
		JKY.disable_button('jky-action-delete'	);
		JKY.show('jky-action-cancel'	);
		JKY.hide('jky-app-table'		);
		JKY.show('jky-app-form'			);
		JKY.hide('jky-app-upload'		);		//	??????
		JKY.process_add_new();
		my_display_new();
	}

	var my_display_new = function() {
		JKY.display_trace('my_display_new');
//		my_index = 0;
		JKY.row = null;
//		$('#jky-app-form        a[changeable]').prop('disabled', false);
//		$('#jky-app-form   button[changeable]').prop('disabled', false);
		$('#jky-app-form    input[changeable]').prop('disabled', false);
		$('#jky-app-form   select[changeable]').prop('disabled', false);
		$('#jky-app-form textarea[changeable]').prop('disabled', false);
		$('#jky-upload-photo').css('display', 'block');
		$('.add-on').css('display', 'inline-block');
		JKY.set_add_new_row();
		JKY.set_focus(my_args.focus);
	}

	var my_process_save = function() {
			JKY.display_trace('my_process_save');
			if (JKY.Validation.is_invalid(JKY.row, null)) {
				return;
			}

			if (JKY.row == null) {
				my_process_insert();
			}else{
				my_process_update();
			}
		}

	var my_process_insert = function() {
			JKY.display_trace('my_process_insert');

			var my_set = '';
			if (my_args.program_name == 'Customers'	) {my_set = ', is_customer  = \'Yes\'';}
			if (my_args.program_name == 'Suppliers'	) {my_set = ', is_supplier  = \'Yes\'';}
			if (my_args.program_name == 'Dyers'		) {my_set = ', is_dyer      = \'Yes\'';}
			if (my_args.program_name == 'Partners'	) {my_set = ', is_partner   = \'Yes\'';}
			if (my_args.program_name == 'Transports') {my_set = ', is_transport = \'Yes\'';}
			var my_data =
				{ method: 'insert'
				, table :  my_args.table_name
				, set	:  JKY.get_form_set() + my_set
				};
			JKY.ajax(false, my_data, my_process_insert_success);
		}

	var my_process_insert_success = function(response) {
			JKY.display_trace('my_process_insert_success');
			JKY.display_message(response.message);
			JKY.process_insert (response.id);
			var my_row = JKY.get_row(my_args.table_name, response.id);
			var my_html = my_set_table_row(my_args, my_row);
			$('#jky-table-body').prepend(my_html);
			my_index  = 1;
			my_count += 1;
			JKY.set_html('jky-app-count', my_count);
			JKY.Changes.reset();

			if (my_args.add_new == 'display form') {
//				my_display_form(JKY.get_index_by_id(response.id, JKY.rows)+1);
//				my_index = $('#jky-table-body tr[row_id="' + response.id + '"]').index() + 1;
				my_display_form(my_index);		//	display added record
			}else
			if (my_args.add_new == 'display list') {
				my_process_add_new();
			}
		}

	var my_process_update = function() {
			JKY.display_trace('my_process_update');
			var my_data =
				{ method: 'update'
				, table :  my_args.table_name
				, set	:  JKY.get_form_set()
				, where :  'id = ' + JKY.row.id
				};
			JKY.ajax(false, my_data, my_process_update_success);
		}

	var my_process_update_success = function(response) {
			JKY.display_trace('my_process_update_success');
			JKY.display_message(response.message);
			JKY.process_update (response.id, JKY.row);
//			JKY.rows[my_index-1] = JKY.get_row(my_args.table_name, JKY.rows[my_index-1]['id']);
//			my_display_next();
			my_display_row(my_index);
			JKY.Changes.reset();
		}

/**
 * process copy
 */
	var my_process_copy = function() {
		JKY.display_trace('my_process_copy');
		var my_data =
			{ method: 'insert'
			, table :  my_args.table_name
			, set	:  JKY.get_form_set()
			};
		JKY.ajax(false, my_data, function(the_response) {
			JKY.display_message(the_response.message);
			JKY.process_copy(the_response.id, JKY.row);
			my_display_list();
		})
	}

	var my_process_delete = function() {
		JKY.display_trace('my_process_delete');
		JKY.display_confirm(my_delete_confirmed, null, 'Delete', 'You requested to <b>delete</b> this record. <br>Are you sure?', 'Yes', 'No');
	}

	var my_delete_confirmed = function() {
		JKY.display_trace('my_delete_confirmed');
		var my_data =
			{ method: 'delete'
			, table :  my_args.table_name
			, where : 'id = ' + JKY.row.id
			};
		JKY.ajax(false, my_data, my_process_delete_success);
	}

	var my_process_delete_success = function(response) {
		JKY.display_trace('my_process_delete_success');
		JKY.display_message(response.message);
		JKY.process_delete (response.id, JKY.row);

		$('#jky-table-body tr:eq(' + (my_index-1) + ')').remove();
		my_count -= 1;
		JKY.set_html('jky-app-count', my_count);
		if (my_count == 0) {
			my_process_add_new()
		}else{
			my_index = (my_index >= my_count) ? my_count : my_index;
			my_display_row(my_index);
		}
	}

	var my_process_cancel = function() {
			JKY.display_trace('my_process_cancel');
			my_display_list();
			JKY.Changes.reset();
		}

/**
 * process combine
 */
	var my_process_combine = function() {
		JKY.display_trace('my_process_combine');
		var my_ids = [];
		$('#jky-table-body .jky-td-checkbox input:checked').each(function() {
			my_ids.push($(this).attr('row_id'));
		});
		JKY.process_combine(my_ids);
	}

/**
 * process print
 */
	var my_process_print = function() {
		JKY.display_trace('my_process_print');
		if ($('#jky-app-form').css('display') == 'block') {
			my_print_row(JKY.row.id);
		}else{
			$('#jky-table-body .jky-td-checkbox input:checked').each(function() {
				my_print_row($(this).attr('row_id'));
			});
		}
	}

/**
 * process print
 */
	var my_print_row = function(the_id) {
		JKY.display_trace('my_print_row');
		JKY.print_row(the_id);
	}

/**
 * process approve
 */
	var my_process_approve = function() {
		JKY.display_trace('my_process_approve');
		if ($('#jky-app-form').css('display') == 'block') {
			my_approve_row(JKY.row.id);
		}else{
			$('#jky-table-body .jky-td-checkbox input:checked').each(function() {
				my_approve_row($(this).attr('row_id'));
			});
		}
	}

/**
 * process approve
 */
	var my_approve_row = function(the_id) {
		JKY.display_trace('my_approve_row');
		JKY.approve_row(the_id);
	}

/**
 * process replace
 */
	var my_process_replace = function() {
		JKY.display_trace('my_process_replace');
		var my_set = JKY.get_replace_set();
		if (my_set != '') {
			$('#jky-table-body .jky-td-checkbox input:checked').each(function() {
				var my_data =
					{ method: 'update'
					, table :  my_args.table_name
					, set	:  my_set.substr(2)
					, where :  my_args.table_name + '.id = ' + $(this).attr('row_id')
					};
				JKY.ajax(true, my_data, function(the_response) {
					JKY.display_message('Record replaced, ' + the_response.message);
				})
			})
			setTimeout(function() {
				JKY.Changes.reset();
				my_display_list();
			}, 1000);
		}
	}

/**
 *	save remarks
 */
	var my_save_remarks = function() {
		var my_set	= 'remarks = \'' + JKY.encode(JKY.get_value('jky-remarks')) + '\'';
		var my_data =
			{ method: 'update'
			, table :  my_args.table_name
			, set	:  my_set
			, where :  my_args.table_name + '.id = ' + JKY.row.id
			};
		JKY.ajax(true, my_data, function(the_response) {
			JKY.display_message('Remarks saved, ' + the_response.message);
			JKY.row = JKY.get_row(my_args.table_name, JKY.row.id);
		})
	}

/**
 * change status
 */
	var my_change_status = function(the_id) {
		var my_status = JKY.is_status('Active') ? 'Inactive' : 'Active';
		var my_data =
			{ method	: 'update'
			, table		:  my_args.table_name
			, set		: 'status = \'' + my_status + '\''
			, where		: 'id = ' + the_id
			};
//		JKY.ajax(false, my_data, my_display_list);
		JKY.ajax(false, my_data, my_display_form);
		JKY.display_message('record (' + the_id + ') changed')
	}

/**
 * process close
 */
	var my_close_row = function(the_id) {
		my_saved_id = the_id;
		JKY.display_trace('my_close_row');
		JKY.display_confirm(my_close_confirmed, null, 'Close', 'You requested to <b>close</b> this record.<br>Are you sure?', 'Yes', 'No');
	}

	var my_close_confirmed = function() {
		JKY.display_trace('my_close_confirmed');
		var my_data =
			{ method	: 'update'
			, table		:  my_args.table_name
			, set		: 'status = \'Closed\''
			, where		: 'id = ' + my_saved_id
			};
		JKY.ajax(false, my_data, my_display_list);
		JKY.close_row(my_saved_id);
		JKY.display_message('record (' + my_saved_id + ') closed')
	}

/**
 * process export
 */
	var my_process_export = function() {
			JKY.display_trace('my_process_export');
			var my_sort_by = my_args.sort_by + ' ' + my_args.sort_seq;
			JKY.run_export(my_args.table_name, my_args.select, my_args.filter, my_args.specific, my_sort_by);
		}

/**
 * process publish
 */
	var my_process_publish = function() {
		JKY.display_trace('my_process_publish');
		JKY.process_publish();
	}

/**
 * process keup input
 */
	var my_process_keyup_input = function(the_id, the_event, the_enter) {
		if (the_enter && the_event.which == 13) {
//			not able to simulate tab to focus on next field
//			$(the_id).trigger({type:'keypress', which:9});
//			$(the_id).next("input, textarea").focus();
			return;
		}
		if (the_event.which ==   8									//	backspace
		||  the_event.which ==  13									//	enter
		||  the_event.which ==  32									//	space
		||  the_event.which ==  46									//	delete
		|| (the_event.which >=  48 && the_event.which <=  90)		//	0 - Z
		|| (the_event.which >=  96 && the_event.which <= 111)		//	0 - /
		|| (the_event.which >= 160 && the_event.which <= 222)) {	//	^ - "
			JKY.Changes.increment();
		}
	}

/**
 * process change input
 */
	var my_process_change_input = function(the_id, the_event) {
		var my_id = $(the_id).attr('id');
		JKY.display_trace('my_process_change_input: ' + my_id);
		JKY.Changes.increment();
	}

/**
 * process verify input
 */
	var my_process_verify_input = function(the_id) {
		var my_id = $(the_id).attr('id');
		JKY.display_trace('my_process_verify_input: ' + my_id);
		JKY.Validation.is_invalid(JKY.row, my_id);
	}

	var my_init = function() {
		JKY.display_trace('my_init');
		my_set_all_events();
//setTimeout(function() {
//		my_set_initial_values();
//}, 100);
	}

	var my_set = function(the_args) {
			JKY.display_trace('my_set');
			my_args = the_args;
		}

	var my_get_prop = function(the_property) {
			JKY.display_trace('my_get');
			return my_args[the_property];
		}

	var my_set_prop = function(the_prop, the_value) {
			JKY.display_trace('my_set_prop');
			my_args[the_prop] = the_value;
		}

//	$(function() {
//		my_changes = 0;
//	});

	return {version : '2.0.0'
		, init			:	function()						{		my_init()						;}
		, set			:	function(the_args)				{		my_set(the_args)				;}
		, get_prop		:	function(the_prop)				{return my_get_prop(the_prop)			;}
		, set_prop		:	function(the_prop, the_value)	{		my_set_prop(the_prop, the_value);}

		, set_all_check			:	function(the_index)		{		my_set_all_check(the_index)		;}
		, display_list			:	function()				{		my_display_list	()				;}
		, display_form			:	function(the_index)		{		my_display_form	(the_index)		;}
		, display_row			:	function(the_index)		{		my_display_row	(the_index)		;}
		, change_status			:	function(the_index)		{		my_change_status(the_index)		;}
		, close_row				:	function(the_index)		{		my_close_row	(the_index)		;}
		, set_checkbox			:	function(the_index)		{		my_set_checkbox	(the_index)		;}
		, Xprocess_is_company	:	function(the_id)		{		Xmy_process_is_company(the_id)	;}
		, process_change_input	:	function(the_id)		{		my_process_change_input(the_id) ;}
		, process_add_new		:	function()				{		my_process_add_new()			;}
		, process_insert		:	function()				{		my_process_insert()				;}
		, process_update		:	function()				{		my_process_update()				;}

		, save_remarks			:	function()				{		my_save_remarks()				;}
	};
}();
