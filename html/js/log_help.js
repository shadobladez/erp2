"use strict";
var JKY = JKY || {};

/**
 * log_help
 */
var jky_program	= 'log_help';
var jky_focus	= 'jky-log-user-name';

/**
 * start program
 */
JKY.start_program = function() {
	JKY.display_trace('start_program - ' + jky_program);
	JKY.Session.load_values();
	JKY.set_all_events();
	JKY.set_initial_values();
}

/**
 *	set all events (run only once per load)
 */
JKY.set_all_events = function() {
	JKY.display_trace('set_all_events');
	$('#jky-log-user-name'		).change(function() {JKY.process_user_name(this);});
	$('#jky-button-log-in'		).click (function() {JKY.load_dialog('log_in', 320);});
	$('#jky-button-submit-help'	).click (function() {JKY.process_submit_help();});
}

/**
 *	set initial values (run only once per load)
 */
JKY.set_initial_values = function() {
	JKY.display_trace('set_initial_values');
	$('#jky-log-user-name').val('');
	JKY.set_button_submit_help();
	JKY.set_focus(jky_focus);
}

JKY.process_user_name= function(user_name) {
	var my_user_name = user_name.value;
	JKY.display_trace('change_user_name: ' + my_user_name);
//	$('#jky-sign-up-user-name').val(my_user_name);
	JKY.set_button_submit_help();
}

JKY.set_button_submit_help = function() {
	JKY.display_trace('set_button_submit_help');
	var my_user_name = JKY.get_value('jky-log-user-name');
	if (JKY.is_empty(my_user_name)) {
		JKY.disabled_id('jky-button-submit-help');
	}else{
		JKY.enabled_id ('jky-button-submit-help');
	}
}

JKY.process_submit_help = function() {
	JKY.display_trace('process_submit_help');
	if (JKY.is_disabled('jky-button-submit-help')) {
		JKY.display_message(JKY.t('Please, fill in missing information'));
		JKY.set_focus(jky_focus);
		return;
	}
	var my_user_name = $('#jky-log-user-name').val();
	var my_data =
		{ method		: 'log_help'
		, help_name		: my_user_name
		};
	JKY.ajax(false, my_data, function(the_response) {
		var my_data = the_response.data;
		for(var i=0, max=my_data.length; i<max; i++) {
			JKY.display_message(JKY.t('Help emailed to') + ': ' + my_data[i]);
		}
		JKY.load_dialog('help_sent', 320);
	})
	JKY.set_focus(jky_focus);
}
