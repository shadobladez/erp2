"use strict";
var JKY = JKY || {};

/**
 * log_off
 */
var jky_program	= 'log_off';

/**
 * start program
 */
JKY.start_program = function() {
	JKY.display_trace('start_program - ' + jky_program);
	JKY.Session.load_values();
	JKY.set_all_events();
	JKY.set_initial_values();

//	this is needed to avoid another Session timeout
	JKY.Session.clear_timeout();
};

/**
 *	set all events (run only once per load)
 */
JKY.set_all_events = function() {
	JKY.display_trace('set_all_events');
	$('#jky-button-log-mini').click(function()	{window.location = '/';});
	$('#jky-button-log-in'	).click(function()	{window.location = '/';});
};

/**
 *	set initial values (run only once per load)
 */
JKY.set_initial_values = function() {
	JKY.display_trace('set_initial_values');
	JKY.hide('jky-header');
	JKY.set_html('jky-log-off-time', JKY.get_time());
};
