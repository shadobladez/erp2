"use strict";
var JKY = JKY || {};

/**
 * help_sent
 */
var jky_program	= 'help_sent';

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
	$('#jky-button-log-in').click (function() {JKY.load_dialog('log_in', 320)});
}

/**
 *	set initial values (run only once per load)
 */
JKY.set_initial_values = function() {
	JKY.display_trace('set_initial_values');
}