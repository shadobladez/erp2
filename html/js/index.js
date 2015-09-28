"use strict";
var JKY = JKY || {};

/**
 * index
 */
var jky_program	= 'index';

$(function() {
	JKY.start_program();
});

/**
 * start program
 */
JKY.start_program = function() {
	JKY.display_trace('start_program - ' + jky_program);

//	if user has not login
	if (!JKY.Session.has('full_name')) {

		//	then load login.html
		//	important, pre-load specific [language] for translations  		
		JKY.append_html('jky-utils', '<scr' + 'ipt src="js/translations/' + JKY.Session.get_value('locale') + '.js"></scr' + 'ipt>');
		JKY.load_dialog('log_in', 320);;
	}else{
	
		//	else load home.html
		window.location = 'home.html';
	}
};
