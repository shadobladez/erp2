"use strict";
var JKY = JKY || {};

/**
 * JKY.Util.js
 * generic functions for the JKY application
 *
 * require: JKY.Session.js
 */

/**
 * define all constants
 */
JKY.AJAX_APP	= '../';					//  relative to application directory
//JKY.AJAX_URL	= '../jky_proxy.php?';		//  relative to remote directory
JKY.AJAX_URL	= '../index.php/ajax?';		//  relative to remote directory

/**
 * run after jquery loaded
 * setup ajax
 */
$(function() {
	$.ajaxSetup({
		async	: false,		//	false = will cause delay (show) on IE, Chrome
		type	: 'post',
		dataType: 'json',
		error	: function(jqXHR, textStatus, errorThrown) {
			JKY.hide('jky-loading');
			JKY.display_message('Error from backend server, please re-try later.');
		}
	});
/*
	if(!JKY.is_loaded('jky-utils'))	{
		$('body').append('<div id="jky-utils"></div>');
		JKY.load_html('jky-utils', 'JKY.utils.html');
	}
*/
	JKY.load_util('jky-utils', 'JKY.utils.html');

	if (JKY.is_browser('msie')) {
		JKY.TRACE = false;		//	IE, TRACE must be false
	}else{
		JKY.TRACE = false;		//	on production, should be set to false, help development to trace sequence flow
	}

//	reset session timeout for every ajax request
	if (JKY.Session) {
		$(document).ajaxSend (function() {JKY.Session.reset_timeout();});
	}
//	$(document).ajaxStart(function() {						 $('#jky-loading').show();});
//	$(document).ajaxStop (function() {setTimeout(function() {$('#jky-loading').hide();}, 2000)});

	$(window).resize(function() {
//		JKY.setTableWidthHeight('jky-app-table', 851, 221, 390, 115);
		JKY.resize_window();
	});

	setTimeout(function() {
		JKY.resize_window();
	}, 500);	//	minimum delay of 100 ms

});

/**
 * binding on resize
 * not to bind on IE < 9, it will cause infinite loops
 * wait until home.html is loaded, to binding on scroll
 *
 * !!! important !!!
 * window resize can be bond only once per load
 */
JKY.Xbinding_on_resize = function() {
	if (JKY.is_browser('msie') && $.browser.version < 9) {
		return;
	}
	if (JKY.is_loaded('jky-loaded')) {
		$(window).bind('resize', function() {
			JKY.setTableWidthHeight('jky-app-table', 690, 390, 150);
		});
	} else {
		setTimeout(function() {JKY.binding_on_resize();}, 100);
	}
}

JKY.resize_window = function() {
	var my_width = $(window).width();
//	if (my_width < 1041)	my_width = 1041;

//	$('.adm-config-value'			).css('width' , my_width -880 + 'px');

	var my_height = $(window).height();
//	if (my_height < 400)	my_width = 400;
//	$('#ihs-controls').css('width', my_width-the_offset + 'px');

//	$('#jky-app-table'			).css('height', my_height-189 + 'px');
//	$('#jky-table-body'			).css('height', my_height-189 + 'px');
//	$('#jky-form-data'			).css('height', my_height-189 + 'px');
//	$('#adm-sub-selector-body'		).css('height', my_height-235 + 'px');
//	$('#adm-content-body'			).css('height', my_height-266 + 'px');
}

/**
 * set table width and height
 * adjust height minus offset height, but not less than minimum height
 * @param	tableId
 * @param	width
 * @param	off_width
 * @param	minHeight
 * @param	offHeight
 */
JKY.setTableWidthHeight = function(tableId, width, off_width, minHeight, offHeight) {
/**
 * jquery 1.7.x the function .height() was working for all 4 browsers (IE,FF,CH,SF)
 * but on 1.8.x it was working only on IE
 */
	var my_width  = $(window).width ();
	var my_height = $(window).height();
	if (!JKY.is_browser('msie')) {
		my_width  = document.body[ "clientWidth"  ];
		my_height = document.body[ "clientHeight" ];
	}
/*
	my_width  -= off_width;
	my_height -= offHeight;

	if (my_height < minHeight) {
		my_height = minHeight;
	}
*/
//	$('#jky-app-table').css('width' , my_width );
//	$('#jky-app-table').css('height', my_height);
//	$('#jky-app-form' ).css('width' , my_width );
//	$('#jky-app-form' ).css('height', my_height);

//JKY.display_message('Width: ' + my_width + ', Height: ' + my_height);
//$('#jky-table-body').css('width' , my_width -202);
//$('#jky-table-body').css('height', my_height-197);
}

/**
 * re direct
 * @param	the_program
 */
JKY.re_direct = function(the_program) {
	if (typeof the_program == 'undefined') {
		window.location = '/home';
	}else{
		window.location = '/' + the_program;
	}
}

/**
 * run when is template ready
 * wait until the template is ready
 * @param	template_name
 * @param	function_name
 */
JKY.run_when_is_ready = function(template_name, function_name) {
	JKY.display_trace('run_when_is_ready: ' + template_name);
	if (Em.TEMPLATES[template_name]) {
		function_name();
	}else{
		setTimeout(function() {JKY.run_when_is_ready(template_name, function_name);}, 100);
	}
}

/**
 * load html into specific id
 * wait until the id is rendered
 * @param	id_name
 * @param	file_name
 */
JKY.load_util = function(id_name, file_name) {
//	remove [bootstrap-datetimepicker-widget] from previous program
	$('.bootstrap-datetimepicker-widget').each(function() {$(this).remove();});
//	remove [plupload] from previous program
	$('.plupload').each(function() {$(this).remove();});

	JKY.display_trace('load_html: ' + file_name);
	if ($('#' + id_name).length > 0) {
		$('#' + id_name).load('../js/JKY.Reset.js');					//	reset abstract functions
		$('#' + id_name).load('../' + file_name);						//	production mode
//		$('#' + id_name).load('../' + file_name + '?' + Math.random());	//	testing mode
		JKY.display_trace('load_html: ' + file_name + ' DONE');

		JKY.t_tag	('jky-app-body', 'span');
		JKY.t_input	('jky-app-body', 'placeholder');
		JKY.t_button('jky-app-body', 'title');
	}else{
		setTimeout(function() {JKY.load_util(id_name, file_name);}, 100);
	}
}

/**
 * overlay html into specific id
 * wait until the id is rendered
 * @param	the_id
 * @param	the_url
 */
JKY.overlay_html = function(the_id, the_url) {
	JKY.display_trace('JKY.overlay_html: ' + the_id);
	if (JKY.is_loaded(the_id)) {
		JKY.set_html(the_id, '');
		$.get(the_url, function(the_response) {
			if (the_response.status == 'ok') {
				JKY.set_html(the_id, the_response.data.overlay);
				JKY.display_trace('JKY.overlay_html: ' + the_id + ' DONE');
			}
		});
	}else{
		setTimeout(function() {JKY.overlay_html(the_id, the_url);}, 100);
	}
}

/**
 * load html into specific id
 * wait until the id is rendered
 * @param	id_name
 * @param	file_name
 */
JKY.load_html = function(id_name, file_name) {
//	remove [bootstrap-datetimepicker-widget] from previous program
	$('.bootstrap-datetimepicker-widget').each(function() {$(this).remove();});
//	remove [plupload] from previous program
	$('.plupload').each(function() {$(this).remove();});

	JKY.display_trace('load_html: ' + file_name);
	if ($('#' + id_name).length > 0) {
		$('#' + id_name).load('../js/JKY.Reset.js');					//	reset abstract functions
		$('#' + id_name).load('../' + file_name);						//	production mode
//		$('#' + id_name).load('../' + file_name + '?' + Math.random());	//	testing mode
		JKY.display_trace('load_html: ' + file_name + ' DONE');

		JKY.t_tag	('jky-app-body', 'span');
		JKY.t_input	('jky-app-body', 'placeholder');
		JKY.t_button('jky-app-body', 'title');

		JKY.start_program(file_name);
	}else{
		setTimeout(function() {JKY.load_html(id_name, file_name);}, 100);
	}
}

/**
 * load handlebar into specific template
 * @param	template_name
 * @param	file_name
 */
JKY.load_hb = function(template_name, file_name) {
	JKY.display_trace('load_hb: ' + template_name);
	if ($('#jky-hb').length > 0) {
		$('#jky-hb').load('../hb/' + file_name, function(src) {
			Em.TEMPLATES[template_name] = Em.Handlebars.compile(src);
			$('#jky-hb').html('');
		});
		JKY.display_trace('load_hb: ' + template_name + ' DONE');
	}else{
		setTimeout(function() {JKY.load_hb(template_name, file_name);}, 100);
	}
}

/**
 * replace in template into specific id
 * wait until the template is loaded
 * @param	template_name
 * @param	id_name
 * @param	view_object
 * @return	(new)View
 */
JKY.replace_in = function(template_name, id_name, view_object) {
	JKY.display_trace('replace_in: ' + template_name);
	if (Em.TEMPLATES[template_name] && $('#' + id_name)) {
		view_object.replaceIn('#' + id_name);
		JKY.display_trace('replace_in: ' + template_name + ' DONE');
	}else{
		setTimeout(function() {JKY.replace_in(template_name, id_name, view_object)}, 100);
	}
}

/**
 * set translations
 *
 * @param	the_array
 * @example	JKY.set_translations('portugues')
 */
JKY.set_translations = function(the_array) {
    JKY.translations = the_array;
}

/**
 * translate
 *
 * @param	the_text
 * @return	translated text
 * @example JKY.t('Home')
 */
JKY.t = function(the_text, the_option) {
	if (typeof the_text == 'undefined' || the_text == '') {
		return '';
	}
	if (the_text.substr(0,1) == '<') {
		return the_text;
	}
	var my_result = JKY.translations[the_text];
	if (typeof my_result == 'undefined') {

if (JKY.Session.get_value('user_name') == 'patjan'
&&  jky_program != 'Profile'
&&  jky_program != 'Reset') {
	alert('JKY.t the_text: ' + the_text);
}

		my_result = '';
		var my_names = the_text.split('<br>');
		for (var i=0; i<my_names.length; i++) {
			var my_name = my_names[i];
			var my_translation = JKY.translations[my_name];
			my_result += (i == 0) ? '' : '<br>';
			if (typeof my_translation == 'undefined') {
				my_result += my_name;
			}else{
				my_result += my_translation;
			}
		}
	}

	if (the_option) {
//		to avoid table headers to break words as: [Check Out] into [Check<br>Out]
		my_result = my_result.replace(/ /g, '&nbsp;');
	}
	return my_result;
}

JKY.t_tag = function(the_id, the_tag) {
	$('#' + the_id + ' ' + the_tag).each(function() {
//		only translate span without id
		if (!$(this).attr('id')) {
			var my_text = $(this).html().trim();
			$(this).html(JKY.t(my_text, true));
		}
	});
}

JKY.t_input = function(the_id, the_attr) {
	$('#' + the_id + ' input').each(function() {
		var my_text = $(this).attr(the_attr);
		$(this).attr(the_attr, JKY.t(my_text));
	});
}

JKY.t_button = function(the_id, the_attr) {
	$('#' + the_id + ' button').each(function() {
		var my_text = $(this).attr(the_attr);
		$(this).attr(the_attr, JKY.t(my_text));
	});
}

/**
 * process action
 * @param	action
 */
JKY.process_action = function(action) {
//	JKY.display_trace('process_action: ' + action);
//	JKY.load_html('jky-body-content', action + '.html');

//	highlight first side action
//	because some action take long time to complete
	JKY.set_side_active('jky-sales-'		+ action);
	JKY.set_side_active('jky-planning-'		+ action);
//	JKY.set_side_active('jky-purchases-'	+ action);
	JKY.set_side_active('jky-production-'	+ action);
	JKY.set_side_active('jky-threads-'		+ action);
	JKY.set_side_active('jky-boxes-'		+ action);
	JKY.set_side_active('jky-dyers-'		+ action);
	JKY.set_side_active('jky-pieces-'		+ action);
	JKY.set_side_active('jky-receiving-'	+ action);
	JKY.set_side_active('jky-fabrics-'		+ action);
	JKY.set_side_active('jky-help-'			+ action);
	JKY.set_side_active('jky-admin-'		+ action);
	JKY.set_side_active('jky-support-'		+ action);

//	JKY.invisible('jky-application');
//	JKY.load_html('jky-application', action + '.html');
	JKY.load_html('jky-app-body', action + '.html');
//	$.getScript(JKY.AJAX_APP + 'js/' + action + '.js', function() {

//	JKY.start_program(action);		//	not ready

//	JKY.visible('jky-application');
//	});
}

/**
 * fix flag
 *
 * if	flag is 't'
 *		then return first	(true_value)
 *		else return second (false_value)
 *
 * @param	flag_value
 * @param	true_value
 * @param	false_value
 * @return	string_value
 */
JKY.fix_flag = function(flag_value, true_value, false_value) {
	if (flag_value) {
		if (flag_value == 't') {
			return true_value;
		}else{
			return false_value;
		}
	}else{
		return '&nbsp;';
	}
}

/**
 * fix break line
 *
 * replace ' ' with '<br />'
 *
 * @param	string_value
 * @return	string_value
 */
JKY.fix_br = function(string_value){
	if (string_value) {
		if (typeof string_value == 'string') {
			return string_value.replace(' ', '<br />');
		}else{
			return string_value;
		}
	}else{
		return '&nbsp;';
	}
}

/**
 * fix date time
 *
 * replace ' @ ' with '<br />'
 * replace ' @'  with ' '
 *
 * @param	date_time	mm/dd/yy @ hh:mm xm
 * @return	date_time	mm/dd/yy<br />hh:mm xm
 */
JKY.fix_date = function(date_time){
	if (date_time) {
		date_time = date_time.replace(' @ ', '<br />');
		date_time = date_time.replace(' @' , ' ');
		return date_time;
	}else{
		return '&nbsp;';
	}
}

/**
 * fix name
 *
 * return trailer last_name, first_name
 *
 * @param	trailer
 * @param	first_name
 * @param	last_name
 * @return	full_name
 */
JKY.fix_name = function(trailer, first_name, last_name){
	if (first_name && last_name) {
		return trailer + last_name + ', ' + first_name;
	}else{
		return '&nbsp;';
	}
}

/**
 * fix null
 *
 * @param	the_string
 * @return	the_string
 */
JKY.fix_null = function(the_string){
	if (the_string == null || the_string == 'null') {
		return ''
	}else{
		return the_string;
	}
}

/**
 * fix ymd to dmy
 *
 * @param	yyyy-mm-dd
 * @return	dd-mm-yyyy
 */
JKY.fix_ymd2dmy = function(the_date){
	if (the_date == null)	return '';
	var my_date = the_date.trim();
	if (my_date == 'null' || my_date == '')		return '';

	var my_dates = my_date.split('-');
	return my_dates[2] + '-' + my_dates[1] + '-' + my_dates[0];
}

/**
 * fix dmy to ymd
 *
 * @param	dd-mm-yyyy
 * @return	yyyy-mm-dd
 */
JKY.fix_dmy2ymd = function(the_date){
	if (the_date == null)	return '';
	var my_date = the_date.trim();
	if (my_date == 'null' || my_date == '')		return '';

	var my_dates = my_date.split('-');
	return my_dates[2] + '-' + my_dates[1] + '-' + my_dates[0];
}

/**
 * fix thumb name
 *
 * @param	the_photo	filename, date time, size
 * @param	the_id
 * @param	the_folder	contacts, products, ...
 * @return	img tag
 */
JKY.fix_thumb = function(the_photo, the_id, the_folder){
	if (the_photo) {
		var my_time = the_photo.split(',')[1];
		return '<img class="jky-mini" src="/thumbs/' + the_folder + '/' + the_id + '.png?time=' + my_time + '">';
	}else{
		return '';
	}
}

/**
 * out float
 *
 * @param	the_float
 *
 * @return	float value
 */
JKY.out_float = function(the_float){
	if (the_float == null)	return '';

	return parseFloat(the_float);
}

/**
 * out count
 *
 * @param	the_string
 *
 * @return	count
 */
JKY.out_count = function(the_string){
	if (JKY.is_empty(the_string))		return '';
	var my_array = the_string.split(', ');
	return my_array.length;
}

/**
 * out date
 *
 * @param	the_time	yyyy-mm-dd hh:mm:ss
 *
 * @return	mm-dd-yyyy (en_US)
 * @return	dd-mm-yyyy (pt_BR)
 */
JKY.out_date = function(the_time){
	if (the_time == null)	return '';

	var my_date		= the_time.substr(0, 10);
	var my_dates	= my_date.split('-');
	var my_result	= '';

	switch(JKY.Session.get_locale()) {
		case 'en_US'	: my_result = my_dates[1] + '-' + my_dates[2] + '-' + my_dates[0];	break;
		case 'pt_BR'	: my_result = my_dates[2] + '-' + my_dates[1] + '-' + my_dates[0];	break;
		default			: my_result = my_date;
	}
	return my_result;
}

/**
 * out time
 *
 * @param	the_time	yyyy-mm-dd hh:mm:ss
 *
 * @return	mm-dd-yyyy hh:mm (en_US)
 * @return	dd-mm-yyyy hh:mm (pt_BR)
 */
JKY.out_time = function(the_time){
	if (the_time == null)	return '';

	var my_date		= the_time.substr(0, 10);
	var my_time		= the_time.substr(11, 5);
	var my_dates	= my_date.split('-');
	var my_result	= '';

	switch(JKY.Session.get_locale()) {
		case 'en_US'	: my_result = my_dates[1] + '-' + my_dates[2] + '-' + my_dates[0];	break;
		case 'pt_BR'	: my_result = my_dates[2] + '-' + my_dates[1] + '-' + my_dates[0];	break;
		default			: my_result = my_date;
	}
	return my_result + ' ' + my_time;
}

/**
 * short date
 *
 * @param	yyyy-mm-dd hh:mm:ss
 *
 * if the date is not the current date, then return only the date
 * @return	mm-dd-yyyy 	(en_US)
 * @return	dd-mm-yyyy	(pt_BR)
 * if the date is the current date, then return the month, date, and time
 * @return	mm-dd hh:mm	(en_US)
 * @return	dd-mm hh:mm	(pt_BR)
 */
JKY.short_date = function(the_time){
	if (the_time == null)	return '';

	var my_time = JKY.out_time(the_time);
	var my_date = the_time.substr(0, 10);
	if (my_date == JKY.get_date()) {
		my_date = my_time.substr(0, 5) + '&nbsp;' + my_time.substr(11, 5);
	}else{
		my_date = my_time.substr(0, 10);
	}
	return my_date;
}

/**
 * set date
 *
 * @param	the_id
 * @param	the_date
 */
JKY.set_date = function(the_id, the_date){
	$('#' + the_id).datetimepicker('setDate', the_date);
}

/**
 * input date id
 *
 * @param	mm-dd-yyyy (en-US)
 *			dd-mm-yyyy (pt_BR)
 *
 * @return	yyyy-mm-dd
 */
JKY.inp_date = function(the_id){
	var my_date = $('#' + the_id + ' input').val();
	return JKY.inp_date_value(my_date);
}

/**
 * input date value
 *
 * @param	mm-dd-yyyy (en-US)
 *			dd-mm-yyyy (pt_BR)
 *
 * @return	yyyy-mm-dd
 */
JKY.inp_date_value = function(the_date){
	if (the_date == '')		return 'null';

	var my_date		= the_date.substr(0, 10);
	var my_dates	= my_date.replace(/\//g, '-').split('-');
	var my_result	= '';

	switch(JKY.Session.get_locale()) {
		case 'en_US'	: my_result = my_dates[2] + '-' + my_dates[0] + '-' + my_dates[1];	break;
		case 'pt_BR'	: my_result = my_dates[2] + '-' + my_dates[1] + '-' + my_dates[0];	break;
		default			: my_result = my_date;
	}
	return "'" + my_result + "'";
}

/**
 * input time id
 *
 * @param	mm-dd-yyyy hh:mm (en-US)
 *			dd-mm-yyyy hh:mm (pt_BR)
 *
 * @return	yyyy-mm-dd hh:mm
 */
JKY.inp_time = function(the_id){
	var my_time = $('#' + the_id + ' input').val();
	return JKY.inp_time_value(my_time);
}

/**
 * input time value
 * @param	the_time	mm-dd-yyyy hh:mm (en-US)
 *						dd-mm-yyyy hh:mm (pt_BR)
 *
 * @return	yyyy-mm-dd hh:mm
 */
JKY.inp_time_value = function(the_time){
	if (the_time == '')		return 'null';

	var my_date		= the_time.substr( 0, 10);
	var my_time		= the_time.substr(11,  5);
	var my_dates	= my_date.replace(/\//g, '-').split('-');
	var my_result	= '';

	switch(JKY.Session.get_locale()) {
		case 'en_US'	: my_result = my_dates[2] + '-' + my_dates[0] + '-' + my_dates[1];	break;
		case 'pt_BR'	: my_result = my_dates[2] + '-' + my_dates[1] + '-' + my_dates[0];	break;
		default			: my_result = my_date;
	}
	return "'" + my_result + ' ' + my_time + "'";
}

/**
 * display message on right bottom corner
 * it will stay be displayed long enought to be read
 * if provided id_name, will set focus after timeout
 * @param	message
 * @param	id_name
 *
 * dependency	#jky-message
 *				#jky-message-body
 *
 */
JKY.d = function(the_message, id_name) {
	JKY.display_message(the_message, id_name);
}

JKY.display_message = function(the_message, id_name) {
	var my_message = the_message.trim();
	if (my_message == '') {
		return;
	}
	if (my_message.substr(0, 4) == '<br>') {
		my_message = my_message.substr(4);
	}
	var my_body = $('#jky-message-body');
	if (my_body.html() == '') {
		my_body.append(my_message);
	}else{
		my_body.append('<br />' + my_message);
	}
	JKY.set_html('jky-message-header', JKY.t('Message'));
	JKY.show('jky-message');

	var my_time = my_body.html().length / 10;
		 if (my_time <  2)		{my_time =  2;}
	else if (my_time > 20)		{my_time = 20;}

	if (JKY.last_time_out){
		clearTimeout(JKY.last_time_out);
	}
	JKY.last_time_out = setTimeout(function(){
		JKY.hide('jky-message');
		my_body.html('');
		if (typeof(id_name) != 'undefined') {
			JKY.set_focus(id_name);
		}
	}, my_time * 1000);
}

/**
 * display trace on left bottom corner
 * it will be displayed if JKY.TRACE = true
 * @param	message
 */
JKY.display_trace = function(message){
	if (!JKY.TRACE) {		//	this control is set on [setup definition of constants] of [index.js]
		return
	}
	var my_date = new Date();
	var my_msec = (my_date.getMilliseconds() + 1000).toString().substr(1);
	var my_time = my_date.getMinutes() + ':' + my_date.getSeconds() + '.' + my_msec;
	console.log(my_time + ' ' + message);

	var my_html = my_time + ' ' + message + '<br />' + $('#jky-trace-body').html();
	$('#jky-trace-body').html(my_html);
	JKY.show('jky-trace');
}

/**
 * get now date and time
 * @return yyyy-mm-dd hh:mm:ss
 */
JKY.get_now = function() {
	return JKY.get_date() + ' ' + JKY.get_time();
}

/**
 * get date
 * @return yyyy-mm-dd
 */
JKY.get_date = function() {
	var  my_today = new Date();
	var  my_year	= my_today.getFullYear();
	var  my_month	= my_today.getMonth()+1;	if (my_month < 10)	my_month= '0' + my_month;
	var  my_day		= my_today.getDate ();		if (my_day   < 10)	my_day	= '0' + my_day	;
	return my_year + '-' + my_month + '-' + my_day;
}

/**
 * get time
 * @return hh:mm:ss
 */
JKY.get_time = function() {
	var  my_today = new Date();
	var  my_hour	= my_today.getHours();		if (my_hour   < 10)	my_hour   = ' ' + my_hour  ;
	var  my_minute	= my_today.getMinutes();	if (my_minute < 10)	my_minute = '0' + my_minute;
	var  my_second	= my_today.getSeconds();	if (my_second < 10)	my_second = '0' + my_second;
	return my_hour + ':' + my_minute + ':' + my_second;
}

/**
 * get text content of specific id
 * @param	idName
 * @return	html
 */
JKY.get_text = function(idName){
	return $('#' + idName).text();
}

/**
 * get html content of specific id
 * @param	idName
 * @return	html
 */
JKY.get_html = function(idName){
	return $('#' + idName).html();
}

/**
 * set specific id with html content
 * @param	id_name
 * @param	html
 */
JKY.set_html = function(id_name, html){
	if ($('#' + id_name).length > 0) {
		$('#' + id_name).html(html);
	}else{
		setTimeout(function() {JKY.set_html(id_name, html);}, 100);
	}
}

/**
 * append specific id with html content
 * @param	id_name
 * @param	html
 */
JKY.append_html = function(id_name, html){
	$('#' + id_name).append(html);
}

/**
 * append specific id with file content
 * @param	id_name
 * @param	file_name
 */
JKY.append_file = function(id_name, file_name){
	if ($('#' + id_name).length == 0) {
		$('body').append('<div id="' + id_name + '"></div>');
		$('#' + id_name).load(file_name);

		JKY.t_tag	(id_name, 'span');
		JKY.t_input	(id_name, 'placeholder');
//		JKY.t_button(id_name, 'title');
	}
}

/**
 * prepend specific id with html content
 * @param	id_name
 * @param	html
 */
JKY.prepend_html = function(id_name, html){
	$('#' + id_name).prepend(html);
}

/**
 * set specific id attr title with title
 * @param	the_id
 * @param	the_title
 */
JKY.set_title = function(the_id , the_title){
	$('#' + the_id).attr('title', the_title);
}

/**
 * set specific id attr src with filename
 * @param	the_id
 * @param	the_file_name
 */
JKY.set_src = function(the_id , the_file_name){
	$('#' + the_id).attr('src', the_file_name);
}

/**
 * set specific id attr css with value
 * @param	the_id
 * @param	the_css
 * @param	the_value
 */
JKY.set_css = function(the_id , the_css, the_value){
	$('#' + the_id).css(the_css, the_value);
}

JKY.has_attr = function(the_id, the_attr){
	var my_attr = $('#' + the_id).attr(the_attr);
	if (typeof my_attr == 'undefined' || my_attr == false) {
		return false;
	}else{
		return true;
	}
}

JKY.has_class = function(the_id, the_class){
	return $('#' + the_id).hasClass(the_class);
}

JKY.remove_attr = function(the_id, the_attr){
	var my_attr = $('#' + the_id).removeAttr(the_attr);
}

/**
 * get value of specific id
 * @param	the_id
 * @return	value
 */
JKY.get_value = function(the_id){
	var my_value = $('#' + the_id).val();
	if (my_value) {
		return my_value.trim();
	}else{
		return '';
	}
}

/**
 * set specific id with value
 * @param	the_id
 * @param	the_value
 */
JKY.set_value = function(the_id, the_value){
	var my_value = (the_value == 'null') ? '' : the_value;
	$('#' + the_id).val(my_value);
}

/**
 * get yes or no on specific checkbox
 * @param	id_name
 * @return	Yes
 * @return	No
 */
JKY.get_yes_no = function(id_name){
	if (JKY.is_checked(id_name)) {
		return 'Yes';
	}else{
		return 'No';
	}
}

/**
 * set yes on specific value
 * @param	id_name
 * @param	value
 */
JKY.set_yes = function(id_name, value){
	var my_id = $('#' + id_name);
//	my_id.removeAttr('checked');		//	jquery 1.8.2
	my_id.prop('checked', false);		//	jquery 2.0.3
	if (value == 'Yes') {
//		JKY.Changes.track(false);
//		$('#' + id_name).attr('checked', true);		//	jquery 1.8.1
//		my_id.click();								//	jquery 1.8.2
		my_id.prop('checked', true);				//	jquery 2.0.3
//		JKY.Changes.track(true);
	}
}

/**
 * get text of selected id
 * @param	the_id
 * @return	value
 */
JKY.get_selected_text = function(the_id){
	var my_value = $('#' + the_id + ' option:selected').text();
	return my_value;
}
/**
 * set check on specific value
 * @param	id_name
 * @param	value
 */
JKY.Xset_check = function(id_name, value){
	$('#' + id_name).removeAttr('checked');
//	var my_command = "$('#" + id_name + " :checkbox[value=" + value + "]').attr('checked', 'checked');";
	var my_command = "$('#" + id_name + " :checkbox[value=" + value + "]').attr('checked', true);";
	setTimeout(my_command, 100);
}

/**
 * set radio on specific value
 * @param	id_name
 * @param	value
 */
JKY.set_radio = function(id_name, value){
//	$('#' + id_name + ' input').filter(':checkbox').prop('checked', false);		//	jquery 1.8.2
	$('#' + id_name + ' input').prop('checked', false);							//	jquery 2.0.3
//	var my_command = "$('#" + id_name + " :radio[value=" + value + "]').attr('checked', true);";	//	jquery 1.8.2
	var my_command = "$('#" + id_name + " :radio[value=\"" + value + "\"]').prop('checked', true);";	//	jquery 2.0.3
	setTimeout(my_command, 100);
}

/**
 * set selected specific id with value
 * @param	id_name
 * @param	value
 */
JKY.set_option = function(id_name, value){
//	$('#' + id_name + ' option:selected').removeAttr('selected');		//	jquery 1.8.2
	$('#' + id_name + ' option').prop('selected', false);				//	jquery 2.0.3
	if (value) {
//		var my_command = "$('#" + id_name + " option[ value=\"" + value + "\" ]').attr('selected', 'selected');";	//	jquery 1.8.2
//		setTimeout(my_command, 100);																				//	jquery 1.8.2
		$('#' + id_name + ' option[ value="' + value + '" ]').prop('selected', 'selected');							//	jquery 2.0.3
	}
}

//	JKY.set_options(20, 'All', 10, 20, 50, 100, 200, 500, 1000)
//  Only String is able to work
//	----------------------------------------------------------------------------
JKY.set_options = function( ) {
     var my_options = '';

     for( var i=1, max=arguments.length; i<max; i++ ) {
          var my_value = arguments[i];
          var my_selected = (my_value == arguments[0]) ? ' selected="selected"' : '';
          my_options += '<option value="' + my_value + '"' + my_selected + '>' + JKY.t(my_value) + '</option>';
     }
     return my_options;
}

//	JKY.set_options_array(20, array, true)
//	----------------------------------------------------------------------------
JKY.set_options_array = function(the_selected, the_array, the_null) {
	var my_options = '';
	if (the_null) {
		my_options += '<option value=null></option>';
	}
	for(var i=0; i<the_array.length; i++) {
		var my_value = '';
		if (typeof the_array[i].nick_name != 'undefined') {
			my_value = the_array[i].nick_name;
		}else{
		if (typeof the_array[i].name != 'undefined') {
			my_value = the_array[i].name;
		}else{
		if (typeof the_array[i].full_name != 'undefined') {
			my_value = the_array[i].full_name;
		}}}
		var my_id    = the_array[i].id;
		if (typeof my_id == 'undefined') {
			my_id = my_value;
		}
		var my_selected = '';
		if (isNaN(the_selected)) {
			my_selected = (my_value == the_selected) ? ' selected="selected"' : '';
		}else{
			my_selected = (my_id    == the_selected) ? ' selected="selected"' : '';
		}
		my_options += '<option value="' + my_id + '"' + my_selected + '>' + my_value + '</option>';
     }
     return my_options;
}

//	get name by id from array
//	----------------------------------------------------------------------------
JKY.get_name_by_id = function(the_id, the_array) {
	for(var i=0; i<the_array.length; i++) {
		if (the_array[i].id == the_id) {
			return the_array[i].name;
		}
	}
	return null;
}

//	get index by id from array
//	----------------------------------------------------------------------------
JKY.get_index_by_id = function(the_id, the_array) {
	for(var i=0; i<the_array.length; i++) {
		if (the_array[i].id == the_id) {
			return i;
		}
	}
	return null;
}

//	JKY.set_radios(20, 'All', 10, 20, 50, 100, 200, 500, 1000)
//	----------------------------------------------------------------------------
JKY.set_radios = function() {
	var radios		= '';
	var set_id		= arguments[0];
	var set_value	= arguments[1];

	for(var i=2, max=arguments.length; i<max; i++) {
		var value = arguments[i];
		var checked = (value == set_value) ? ' checked="checked"' : '';
		radios += '<input type="radio" id="' + set_id + '" name="' + set_id + '" value="' + value + '" ' + checked + '/>&nbsp;' + value + ' &nbsp; ';
	}
	return radios;
}

//	JKY.set_radios_array('jky-product-type', array)
//	----------------------------------------------------------------------------
JKY.set_radios_array = function(the_name, the_array) {
	var my_radios = '';
	for(var i=0, max=the_array.length; i<max; i++) {
		var my_value = '';
		if (typeof the_array[i].name != 'undefined') {
			my_value = the_array[i].name;
		}else{
		if (typeof the_array[i].nick_name != 'undefined') {
			my_value = the_array[i].nick_name;
		}else{
		if (typeof the_array[i].full_name != 'undefined') {
			my_value = the_array[i].full_name;
		}}}
		my_radios += '<input type="radio" name="' + the_name + '" value="' + my_value + '" /><span>' + my_value + '</span><br>';
	}
	return my_radios;
}

//	JKY.set_checks('...', ..., '...')
//	----------------------------------------------------------------------------
JKY.Xset_checks = function() {
	var checks	= '';
	var set_id	= arguments[0];

	for(var i=1; i<arguments.length; i++ ) {
		var value = arguments[i];
		checks += '<input type="checkbox" id="' + set_id + '" name="' + set_id + '" value="' + value + '" ' + '/>&nbsp;' + value + ' <br>';
	}
	return checks;
}

/**
 * set 'active' class on specific id of menu
 * @param	id_name
 */
JKY.set_menu_active = function(id_name){
	JKY.hide('jky-side-sales'		);
	JKY.hide('jky-side-planning'	);
	JKY.hide('jky-side-purchases'	);
	JKY.hide('jky-side-production'	);
	JKY.hide('jky-side-threads'		);
	JKY.hide('jky-side-boxes'		);
	JKY.hide('jky-side-dyers'		);
	JKY.hide('jky-side-pieces'		);
	JKY.hide('jky-side-receiving'	);
	JKY.hide('jky-side-fabrics'		);
	JKY.hide('jky-side-help'		);
	JKY.hide('jky-side-admin'		);
	JKY.hide('jky-side-support'		);
	$('#jky-menus li').removeClass('active');
	$('#' + id_name).addClass('active');
}

/**
 * set 'active' class on specific id of side bar
 * @param	id_name
 */
JKY.set_side_active = function(id_name){
	if ($('#' + id_name).length == 0
	||  $('#' + id_name).parent().css('display') == 'none') {
		return;
	}
	$('#jky-side-bar div').removeClass('active');
	$('#' + id_name).addClass('active');
}

/**
 * get value of checkbox or radio checked
 * @param	the name
 * @return	value
 */
JKY.get_checked = function(id_name){
	return $('input[name=' + id_name + ']:checked').val();
}

/**
 * set 'active' class on specific id
 * @param	id_name
 */
JKY.set_active = function(id_name){
	$('#' + id_name).addClass('active');
}

/**
 * reset 'active' class on specific id
 * @param	id_name
 */
JKY.reset_active = function(id_name){
	$('#' + id_name).removeClass('active');
}

/**
 * reset all 'active' class on specific id
 * @param	id_name
 */
JKY.reset_all_active = function(id_name){
	$('#' + id_name + ' .active').each(function() {$(this).removeClass('active');});
}

/**
 * show specific id name
 * @param	id_name
 */
JKY.show = function(id_name){
	$('#' + id_name).show();
}

/**
 * hide specific id name
 * @param	id_name
 */
JKY.hide = function(id_name){
	if (id_name == 'jky-loading') {
//		the delay of 1 sec is just ilusion for the user to perceive the end of loading
//		setTimeout(function()	{$('#jky-loading').hide();}, 1000);		//	for Colors 2000
		setTimeout(function()	{$('#jky-loading').hide();}, 100);
	}else{
		$('#' + id_name).hide();
	}
}

/**
 * Collapse Side Bar
 * @param	id_name
 */

JKY.collapse = function(id_name){
	var my_id = $('#' + id_name);
	var x= my_id.css('margin-left');
	if (my_id.css('margin-left') == '0px') {
		my_id.css('display' , 'block');
		my_id.css('margin-left' , '-200px');
		$('#jky-collapsible-icon').removeClass('icon-step-backward').addClass('icon-step-forward');
	}else{
		$('#jky-collapsible-icon').removeClass('icon-step-forward').addClass('icon-step-backward');
		my_id.css('display' , 'table-cell');
		my_id.css('margin-left' , '0px');
	}

}

/**
 * hide specific id name
 * @param	id_name
 */
JKY.invisible = function(id_name){
	$('#' + id_name).css('visibility', 'hidden');
}

/**
 * show specific id name
 * @param	id_name
 */
JKY.visible = function(id_name){
	$('#' + id_name).css('visibility', 'visible');
}

/**
 * center specific box on windows
 * @param	id_name
 */
JKY.center_box = function(id_name) {
	var my_box  = $('#' + id_name);
	var my_left = ($(window).width () - my_box.width ())/2;
	var my_top  = ($(window).height() - my_box.height())/2;
	my_box.css('top' , my_top );
	my_box.css('left', my_left);
}

/**
 * show modal specific id name
 * @param	id_name
 */
JKY.show_modal = function(id_name) {
	var my_box  = $('#' + id_name);
	my_box.modal('show');
	my_box.draggable({handle:'.modal-header', containment:'window', delay:100, distance:10});
//	my_box.resizable();		//	comment out because of side effect on scrollable
}

/**
 * hide modal specific id name
 * @param	id_name
 */
JKY.hide_modal = function(id_name) {
	$('#' + id_name).modal('hide');
}

/**
 * enable button
 */
JKY.enable_button = function(id_name) {
	$('#' + id_name).show();
	$('#' + id_name).removeAttr ('disabled');
	$('#' + id_name).css('cursor', 'pointer');
}

/**
 * disable button
 */
JKY.disable_button = function(id_name) {
	$('#' + id_name).attr('disabled', 'disabled');
	$('#' + id_name).css('cursor', 'not-allowed');
}

/**
 * return true if specific browser is running
 * @param	browserName
 * @return  true | false
 *
 * @example
 *			JKY.is_browser('msie')
 *			JKY.is_browser('firefox')
 *			JKY.is_browser('chrome')
 *			JKY.is_browser('safari')
 */
JKY.is_browser = function(browserName){
	var myUserAgent = navigator.userAgent.toLowerCase();
	if (myUserAgent.indexOf(browserName) > -1) {
		return true;
	}else{
		return false;
	}
}

/**
 * scroll to top if the table
 * @param	class_name
 */
JKY.scroll_to_top = function(class_name){
	$('.' + class_name).scrollTop(0);
}

/**
 * return true if scroll bar is at end of table
 * @param	class_name
 * @return  true | false
 */
JKY.is_scroll_at_end = function(class_name){
	var my_id = $('.' + class_name)[0];
	var my_offset = my_id.scrollHeight - my_id.scrollTop - my_id.offsetHeight;
	if (my_offset < 100) {
		return true;
	}else{
		return false;
	}
}

/**
 * show confirm layer with message
 * @param	function_yes	(null | callback function if reply = Yes)
 * @param	function_no		(null | callback function if reply = No )
 * @param	header
 * @param	body
 * @param	label_yes
 * @param	label_no
 *
 * @example JKY.display_confirm
 *				(  JKY.restore_data
 *				,  null
 *				, 'Leaving'
 *				, 'You have <b>unsaved</b> change(s). <br>Do you want to <b>restore</b> this screen without save it?'
 *				, 'Leave Page'
 *				, 'Stay on Page'
 *				);
 */
JKY.display_confirm = function(function_yes, function_no, header, body, label_yes, label_no) {
	JKY.click_confirm = function(reply) {
		$('#jky-confirm').unbind('hidden');
		$('#jky-confirm').modal('hide');
		if (reply == 'Yes' && typeof(function_yes) == 'function')	{function_yes();}
		if (reply == 'No'  && typeof(function_no ) == 'function')	{function_no ();}
	}
	$('#jky-confirm-header'	).html(JKY.t(header		));
	$('#jky-confirm-body'	).html(JKY.t(body		));
	$('#jky-confirm-yes'	).html(JKY.t(label_yes	));
	$('#jky-confirm-no'		).html(JKY.t(label_no	));
	$('#jky-confirm').on('hidden', function() {JKY.click_confirm('No');});
	$('#jky-confirm').modal('show');
}

//        JKY.show_layer('login', 'user_name', 200)
//        ----------------------------------------------------------------------
JKY.show_layer = function(layer, field, z_index) {
     var   layer_name = layer + '-layer' ;
     var  shadow_name = layer + '-shadow';
/*
     var  JKY.shadow = document.getElementById(shadow_name);
     if( !JKY.shadow ) {
          JKY.shadow = document.createElement('div');
          JKY.shadow.setAttribute('id', shadow_name);
          JKY.shadow.setAttribute('class', 'shadow');
          document.body.appendChild(JKY.shadow);
     }
*/
     $('#' + shadow_name).show().css('z-index', z_index  );
     $('#' +  layer_name).show().css('z-index', z_index+1);
     JKY.set_focus(field);
     eval('setup_' + layer + '_data();');
}

//        JKY.hide_layer('login')
//        ----------------------------------------------------------------------
JKY.hide_layer = function(layer) {
     var   layer_name = layer + '-layer' ;
     var  shadow_name = layer + '-shadow';
     $('#' +  layer_name).hide();
     $('#' + shadow_name).hide();
}

/**
 * set string
 * @param	the_string
 * @return	string
 */
JKY.set_string = function(the_string) {
	if (the_string) {
		return the_string;
	}else{
		return '&nbsp;';
	}
}

/**
 * set icon file
 * @param	the_file_id
 * @return	image
 */
JKY.set_icon_file = function(the_file_id) {
	if (the_file_id) {
		return '<i class="icon-file"></i>';
	}else{
		return '&nbsp;';
	}
}

//		JKY.set_focus('user_name', 100)
//		----------------------------------------------------------------------
JKY.set_focus = function(the_name, the_delay) {
	var my_id = $('#' + the_name);
	if (my_id && my_id.is(':visible')) {
		var my_delay = (typeof the_delay == 'undefined') ? 0 : the_delay;
		setTimeout( function() {
			my_id.focus();
			my_id.select();
		}, my_delay);
	}else{
		setTimeout( function() {
			JKY.set_focus(the_name, the_delay);
		}, 100);
	}
}

/**
 * convert all [\n] to [<br>]
 */
JKY.nl2br = function(string) {
	if (string == null) {
		return '';
	}else{
		return string.replace(/\n/g, '<br>');
	}
}


/**
 * convert all [<br>] to [\n]
 */
JKY.br2nl = function(string) {
	if (string == null) {
		return '';
	}else{
		return string.replace(/<br>/g, "\n").replace(/<BR>/g, "\n");
	}
}


/*
 *	json to array
 */
JKY.json2Array = function(json) {
	JKY.display_trace('JKY.json2Array');
	var array = {'Status':'error', 'Error':{'Message':'Error, contact IT support'}};
	try{
		array = eval("(" + json + ")");
	} catch(error) {
		alert('Error: ' + error);
	}
	return array;
}

/**
 * convert all
 * [&] to [&amp;	]
 * [<] to [&lt;		]
 * [>] to [&gl;		]
 * ["] to [&quot;	]
 * ['] to [&#039;	]
 * [\] to [&#092;	]
 * [t] to [ ]
 */
JKY.encode = function(string) {
  string = string.replace( /&/g, "&amp;"	);
  string = string.replace( />/g, "&gt;"		);
  string = string.replace( /</g, "&lt;"		);
  string = string.replace( /"/g, "&quot;"	);
  string = string.replace( /'/g, "&#039;"	);
  string = string.replace(/\\/g, "&#092;"	);
  string = string.replace(/\t/g, " "		);
  return string;
}

/**
 * convert all
 * [&amp;	] to [&]
 * [&lt;	] to [<]
 * [&gl;	] to [>]
 * [&quot;	] to ["]
 * [&#039;	] to [']
 * [&#092;	] to [\]
 * [&nbsp;	] to [ ]
 */
JKY.decode = function(string)	{
	string = string.replace(/&amp;/g , '&' );
	string = string.replace(/&lt;/g	 , '<' );
	string = string.replace(/&gt;/g  , '>' );
	string = string.replace(/&quot;/g, '"' );
	string = string.replace(/&#039;/g, "'" );
	string = string.replace(/&#092;/g, "\\");
	string = string.replace(/&nbsp;/g, ' ' );
	return string;
}

/*
 *	capitalise only first character
 */
JKY.capitalise = function(string) {
	return string.charAt(0).toUpperCase() + string.slice(1);
}

JKY.max = function(max_size, string) {
	if (string == null) {
		return '&nbsp;';
	}else{
		var my_length = string.length;
		if (my_length < max_size) {
			return string;
		}else{
			return string.substr(0, max_size);
		}
	}
}

JKY.disabled_id	= function(id_name)	{		$('#' + id_name).addClass	('disabled');}
JKY.enabled_id 	= function(id_name)	{		$('#' + id_name).removeClass('disabled');}
JKY.is_disabled = function(id_name) {return $('#' + id_name).hasClass	('disabled');}

//        JKY.set_...
//        ----------------------------------------------------------------------
JKY.set_is_zero			= function(name)              {return '<br>' + JKY.t( name ) + ' ' + JKY.t('is zero'		);}
JKY.set_is_invalid		= function(name)              {return '<br>' + JKY.t( name ) + ' ' + JKY.t('is invalid'		);}
JKY.set_is_required		= function(name)              {return '<br>' + JKY.t( name ) + ' ' + JKY.t('is required'	);}
JKY.set_already_taken	= function(name)              {return '<br>' + JKY.t( name ) + ' ' + JKY.t('already taken'	);}
JKY.set_not_found		= function(name)              {return '<br>' + JKY.t( name ) + ' ' + JKY.t('not found'		);}
JKY.set_must_be_numeric	= function(name)              {return '<br>' + JKY.t( name ) + ' ' + JKY.t('must be numeric');}
JKY.set_size_is_under	= function(name, size )       {return '<br>' + JKY.t( name ) + ' ' + JKY.t('size is under'	) + ' [' + size  + ']';}
JKY.set_size_is_above	= function(name, size )       {return '<br>' + JKY.t( name ) + ' ' + JKY.t('size is above'	) + ' [' + size  + ']';}
JKY.set_value_is_under	= function(name, value)       {return '<br>' + JKY.t( name ) + ' ' + JKY.t('value is under'	) + ' [' + value + ']';}
JKY.set_value_is_above	= function(name, value)       {return '<br>' + JKY.t( name ) + ' ' + JKY.t('value is above'	) + ' [' + value + ']';}

//   Set Languages -------------------------------------------------------------
JKY.set_languages = function() {
     var  options = $('#en-speaking').html();
     if(  options == '' ) {
          setTimeout('JKY.set_languages()', 100);
     } else {
          $('#en-reading' ).html(options);
          $('#en-writing' ).html(options);
          $('#ma-speaking').html(options);
          $('#ma-reading' ).html(options);
          $('#ma-writing' ).html(options);
          $('#tw-speaking').html(options);
          $('#tw-reading' ).html(options);
          $('#tw-writing' ).html(options);
     }
}

/**
 * check if specific id  is loaded
 *
 * @param	id_name
 * @return  true | false
 */
JKY.is_loaded = function(id_name) {
	if ($('#' + id_name).length > 0
	||  $('#' + id_name + '-loaded').length > 0) {
		return true;
	}else{
		return false;
	}
}

//	check if the_string is empty
JKY.is_empty = function(the_string) {
	if (typeof the_string == 'undefined'
	||  the_string == 'null'
//	||  the_string == false			//	'0'
	||  $.trim(the_string) == '') {
		return true;
	}else{
		return false;
	}
}

/*
 * is checked, if specific id is checked
 *
 * @param	id_name
 *
 * @return	true		(if is  checked)
 * @return	false		(if not checked)
 */
JKY.is_checked = function(id_name) {
	if ($('#' + id_name).is(':checked')) {
		return true;
	}else{
		return false;
	}
};

//	email format xxx@xxx.xxx
JKY.is_email = function(the_email) {
	var  pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
	return pattern.test(the_email);
}

/*
 * is required, if empty then display message [x...x is required]
 *
 * @param	value
 * @param	label
 *
 * @return	false		(if value is empty)
 * @return	true		(if value is not empty)
 */
JKY.is_required = function(value, label) {
	if (value == '') {
		JKY.display_message(label + ' is required.');
		return false;
	}else{
		return true;
	}
};

/*
 * is numeric, if not then display message [x...x must be numeric]
 *
 * @param	value
 * @param	label
 *
 * @return	false		(if value is empty)
 * @return	true		(if value is not empty)
 */
JKY.is_numeric = function(value, label) {
	if (value == ''
	||  isNaN(value)) {
//		JKY.display_message(label + ' must be numeric.');
		return false;
	}else{
		return true;
	}
};

//        date format mm/dd/yyyy
JKY.is_date = function(date) {
     var  string = JKY.str_replace('%2F', '/', date);
     var  dates  = string.replace(/\//g, '-').split('-');
     var  mm   = parseInt(dates[0], 10);
     var  dd   = parseInt(dates[1], 10);
     var  yyyy = parseInt(dates[2], 10);
     var  new_date = new Date(yyyy, mm-1, dd);
     if(( new_date.getFullYear() == yyyy ) && ( new_date.getMonth() == mm-1 ) && ( new_date.getDate() == dd ))
          return true;
     else return false;
}

/*
 * if status match to value (english or translated)
 *
 * @param	value
 *
 * @return	true		(if value equal)
 * @return	false		(if value not equal)
 */
JKY.is_status = function(the_status) {
	var my_status = JKY.get_html('jky-status');
	if (my_status == the_status
	||  my_status == JKY.t(the_status)) {
		return true;
	}else{
		return false;
	}
};

JKY.is_array = function(the_value) {
	return the_value
		&& typeof the_value === 'object'
		&& typeof the_value.length === 'number'
		&& typeof the_value.splice === 'function'
		&& !(the_value.propertyIsEnumerable('length'))
		;
};

//        JKY.str_replace
//        ----------------------------------------------------------------------
JKY.str_replace = function(search, replace, subject, count) {
//   note: The count parameter must be passed as a string in order to find a global variable in which the result will be given
//   example 1:  JKY.str_replace( ' ', '.', 'Kevin van Zonneveld' );                          //   returns 1: 'Kevin.van.Zonneveld'
//   example 2:  JKY.str_replace([ '{name}', 'l' ], [ 'hello', 'm' ], '{name}, lars' );       //   returns 2: 'hemmo, mars'
     var  i     = 0
       ,  j     = 0
       ,  temp  = ''
       ,  repl  = ''
       ,  sl    = 0
       ,  fl    = 0
       ,  f     = [].concat(search )
       ,  r     = [].concat(replace)
       ,  s     = subject
       ,  ra    = Object.prototype.toString.call(r) == '[object Array]'
       ,  sa    = Object.prototype.toString.call(s) == '[object Array]'
       ;
     s = [].concat(s);
     if(  count ) {
          this.window[count] = 0;
     }

     for( i=0, sl=s.length; i<sl; i++ ) {
          if(  s[i] == '' ) {
               continue;
          }
          for( j=0, fl=f.length; j<fl; j++ ) {
               temp = s[i] + '';
               repl = ra ? (r[j] != undefined ? r[j] : '') : r[0];
               s[i] = (temp).split(f[j]).join(repl);
               if(  count && s[i] != temp ) {
                    this.window[count] += (temp.length - s[i].length) / f[j].length;
               }
          }
     }
     return sa ? s : s[0];
}

/**
 * set company name
 */
JKY.set_company_name = function(company_name) {
	JKY.set_html('jky-company-name', company_name);
}

/**
 * set user info
 */
JKY.set_user_info = function(full_name, contact_id) {
	if (full_name == null) {
		JKY.set_html('jky-user-full-name', '');
		JKY.hide('jky-user-logged');
		JKY.show('jky-user-unkown');
	}else{
//		var my_full_name = '<a href="#" onclick="JKY.process_profile()">' + full_name + '</a>';
		var my_full_name = '<a href="#" onclick="JKY.process_profile()">'
						 + '<img class="jky-mini" src="/thumbs/contacts/' + contact_id + '.png">'
						 + ' ' + full_name
						 + '</a>'
						 ;
		var my_log_off = ' &nbsp; <a id="jky-menu-logoff" href="#" onclick="JKY.process_log_off()">' + JKY.t('Log Off') + '</a>';
		JKY.set_html('jky-user-full-name', my_full_name + my_log_off);
		JKY.hide('jky-user-unkown');
		JKY.show('jky-user-logged');
	}
}

/**
 * set company logo
 */
JKY.set_company_logo = function(company_logo) {
	JKY.set_html('jky-company-logo', '<img src="/img/' + company_logo + '" />');
}

/**
 * set event name
 */
JKY.set_event_name = function(event_name) {
	JKY.set_html('jky-event-name', event_name);
}

JKY.is_permitted = function(the_menu_id) {
	var my_resource = '';
	switch(the_menu_id) {
		case('jky-menu-sales'		)	:	my_resource = 'Menu-Sales'		; break;
		case('jky-menu-planning'	)	:	my_resource = 'Menu-Planning'	; break;
//		case('jky-menu-purchases'	)	:	my_resource = 'Menu-Purchases'	; break;
		case('jky-menu-production'	)	:	my_resource = 'Menu-Production'	; break;
		case('jky-menu-threads'		)	:	my_resource = 'Menu-Threads'	; break;
		case('jky-menu-boxes'		)	:	my_resource = 'Menu-Boxes'		; break;
		case('jky-menu-dyers'		)	:	my_resource = 'Menu-Dyers'		; break;
		case('jky-menu-pieces'		)	:	my_resource = 'Menu-Pieces'		; break;
		case('jky-menu-receiving'	)	:	my_resource = 'Menu-Receiving'	; break;
		case('jky-menu-fabrics'		)	:	my_resource = 'Menu-Fabrics'	; break;
		case('jky-menu-help'		)	:	my_resource = 'Menu-Help'		; break;
		case('jky-menu-admin'		)	:	my_resource = 'Menu-Admin'		; break;
		case('jky-menu-support'		)	:	my_resource = 'Menu-Support'	; break;
		default							:	alert('JKY.is_permitted: ' + the_menu_id);
	}

	var my_action = JKY.Session.get_action(my_resource);
	if (my_action == '') {
		my_action = JKY.Session.get_action('All');
	}

	if (my_action == 'All') {
		return true;
	}else{
		return false;
	}
}

/**
 * set buttons menus
 */
JKY.set_buttons_menus = function(menus) {
	var my_html = '';
	for(var i=0; i<menus.length; i++) {
		var my_menu = menus[i];
		if (JKY.is_permitted(my_menu.id)) {
			my_html += '<li id="' + my_menu.id + '">'
					+  '<a onclick="JKY.process_menu(\'' + my_menu.id + '\')" >' +  JKY.t(my_menu.label)
	//				+  '<i class="icon-' + my_menu.icon + ' icon-white"></i>' + my_menu.label
					+  '</a>'
					+  '</li>'
					;
		}
	}
	JKY.set_html('jky-menus', my_html);
}

/**
 * set buttons control
 */
JKY.set_buttons_control = function(admins, language, languages) {
	var my_html = '';
	if (languages.length > 0) {
		my_html += '<span class="jky-label">' + JKY.t('Language') + ':</span>';
		my_html += '<select id="jky-control-language">';
		for(var i=0; i<languages.length; i++) {
			var my_language = languages[i];
			var my_selected = (my_language == language) ? ' selected="selected"' : '';
			my_html += '<option value="' + my_language + '"' + my_selected + '>' + JKY.t(my_language) + '</option>';
		}
		my_html += '</select>';
	}

	if (admins.length > 0) {
		my_html += '<div class="btn-group">'
				+  '<a class="btn btn-large dropdown-toggle" data-toggle="dropdown" href="#"><i class="icon-tasks icon-white"></i>Admin</a>'
				+  '<ul id="jky-control-admin" class="dropdown-menu">'
				;
		for(var j=0; j<admins.length; j++) {
			var my_admin = admins[j];
			my_html += '<li><a onclick="JKY.display_trace(\'' + my_admin.label + '\')"><i class="icon-' + my_admin.icon + ' icon-white"></i> &nbsp;' + my_admin.label + '</a></li>';
		}
		my_html += '</ul></div>';
	}
	my_html += '<a id="jky-control-tickets" class="btn btn-large"><i class="icon-share icon-white"></i>Tickets</a>';
	JKY.set_html('jky-control', my_html);
}

/**
 * set body header
 */
JKY.set_body_header = function(name, buttons) {
	JKY.set_html('jky-body-name', '<i class="icon-th"></i>' + name);
	var my_html = '';
	for(var i=0; i<buttons.length; i++) {
		var my_button = buttons[i];
		my_html += '<button onclick="JKY.display_trace(\'' + my_button.on_click + '\')" class="btn"><i class="icon-' + my_button.icon + '"></i> ' + JKY.t(my_button.label) + '</button>';
	}
	JKY.set_html('jky-body-buttons', my_html);
}

/**
 * set copyright
 */
JKY.set_copyright = function(copyright) {
	JKY.set_html('jky-copyright', copyright);
}

/**
 * set copyright
 */
JKY.set_contact_us = function(contact_us) {
//	JKY.set_html('jky-contact-us', contact_us);
}

/**
 * get ids
 */
JKY.get_ids = function(table) {
	JKY.display_trace('get_ids: ' + table);
	var my_rows = [];
	var my_data =
		{ method	: 'get_ids'
		, table		:  table
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
					my_rows = response.rows;
				}else{
					JKY.display_message(response.message);
				}
			}
		, error		: function(jqXHR, text_status, error_thrown) {
				if (typeof function_error != 'undefined') {
					function_error(jqXHR, text_status, error_thrown);
				}else{
					JKY.hide('jky-loading');
					JKY.display_message('Error from backend server, please re-try later.');
				}
			}
		}
	)
	return my_rows;
}

/**
 * get controls
 */
JKY.get_controls = function(group_set) {
	JKY.display_trace('get_controls: ' + group_set);
	var my_rows = [];
	var my_data =
		{ method	: 'get_controls'
		, group_set	:  group_set
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
					my_rows = response.rows;
				}else{
					JKY.display_message(response.message);
				}
			}
		, error		: function(jqXHR, text_status, error_thrown) {
				if (typeof function_error != 'undefined') {
					function_error(jqXHR, text_status, error_thrown);
				}else{
					JKY.hide('jky-loading');
					JKY.display_message('Error from backend server, please re-try later.');
				}
			}
		}
	)
	return my_rows;
}

/**
 * get configs
 */
JKY.get_configs = function(group_set) {
	JKY.display_trace('get_configs: ' + group_set);
	var my_rows = [];
	var my_data =
		{ method	: 'get_configs'
		, group_set	:  group_set
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
					my_rows = response.rows;
				}else{
					JKY.display_message(response.message);
				}
			}
		, error		: function(jqXHR, text_status, error_thrown) {
				if (typeof function_error != 'undefined') {
					function_error(jqXHR, text_status, error_thrown);
				}else{
					JKY.hide('jky-loading');
					JKY.display_message('Error from backend server, please re-try later.');
				}
			}
		}
	)
	return my_rows;
}

/**
 * get companies
 */
JKY.get_companies = function(specific) {
	JKY.display_trace('get_companies: ' + specific);
	var my_rows = [];
	var my_data =
		{ method	: 'get_companies'
		, specific	:  specific
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
					my_rows = response.rows;
				}else{
					JKY.display_message(response.message);
				}
			}
		, error		: function(jqXHR, text_status, error_thrown) {
				if (typeof function_error != 'undefined') {
					function_error(jqXHR, text_status, error_thrown);
				}else{
					JKY.hide('jky-loading');
					JKY.display_message('Error from backend server, please re-try later.');
				}
			}
		}
	)
	return my_rows;
}

/**
 * set group set
 */
JKY.set_group_set = function(table, selected, group_set, initial) {
	JKY.display_trace('set_control_set: ' + group_set);
	var my_html = '';
	var my_data =
		{ method	: 'get_index'
		, table		:  table
		, order_by	: 'sequence,name'
		, select	:  group_set
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
					my_html = '';
					if (initial == null ) {
//						no initial option
					}else{
						if (initial == '' ) {
							my_html += '<option value=""   >' + initial + '</option>';
						}else{
							my_html += '<option value="All">' + initial + '</option>';
						}
					}
					for(var i=0; i<response.rows.length; i+=1) {
						var my_name  = response.rows[i]['name' ];
						var my_value = response.rows[i]['value'];
//						if (my_value == '' || group_set == 'User Roles') {
						if (my_value == '' || my_value == 'null' || group_set == 'User Roles') {
							my_value = my_name;
						}
						var my_selected = (my_name == selected) ? ' selected="selected"' : '';
						my_html += '<option value="' + my_name + '"' + my_selected + '>' + my_value + '</option>';
					}
//					my_html += '<option onclick="JKY.process_option_search(this)"	class="jky-option-search"	>Search More...</option>';
//					my_html += '<option onclick="JKY.process_option_add_new(this)"	class="jky-option-add-new"	>Add New...</option>';
				}else{
					JKY.display_message(response.message);
				}
			}
		, error		: function(jqXHR, text_status, error_thrown) {
				if (typeof function_error != 'undefined') {
					function_error(jqXHR, text_status, error_thrown);
				}else{
					JKY.hide('jky-loading');
					JKY.display_message('Error from backend server, please re-try later.');
				}
			}
		}
	)
	return my_html;
}

/**
 * set configs
 */
JKY.set_configs = function(group_set, selected, initial) {
	JKY.display_trace('set_configs: ' + group_set);
	var my_html = '';
	var my_rows = JKY.get_configs(group_set);

	if (typeof initial == 'undefined' ) {
		my_html += '';
	}else{
	if (initial == '' ) {
		my_html += '<option value=""   >' + initial + '</option>';
	}else{
		my_html += '<option value="All">' + initial + '</option>';
	}}
	for(var i=0; i<my_rows.length; i+=1) {
		var my_name  = my_rows[i]['name' ];
		var my_value = my_rows[i]['value'];
//		if (my_value == '' || group_set == 'User Roles') {
		if (my_value == null || my_value == '' || my_value == 'null' || group_set == 'User Roles') {
			my_value = my_name;
		}
		var my_selected = (my_name == selected) ? ' selected="selected"' : '';
		my_html += '<option value="' + my_name + '"' + my_selected + '>' + my_value + '</option>';
	}
//	my_html += '<option onclick="JKY.process_option_search(this)"	class="jky-option-search"	>Search More...</option>';
//	my_html += '<option onclick="JKY.process_option_add_new(this)"	class="jky-option-add-new"	>Add New...</option>';
	return my_html;
}

/**
 * set controls
 * if initial is undefined then nothing will show up
 * if initial is equal to '' then add up a '___' option
 * if initial is equal to All then add up one 'All' option
 */
JKY.set_controls = function(group_set, selected, initial) {
	JKY.display_trace('set_controls: ' + group_set);
	var my_html = '';
	var my_rows = JKY.get_controls(group_set);

	if (typeof initial == 'undefined' ) {
		my_html += '';
	}else{
	if (initial == '' ) {
		my_html += '<option value=""   >' + initial + '</option>';
	}else{
		my_html += '<option value="All">' + initial + '</option>';
	}}
	for(var i=0; i<my_rows.length; i+=1) {
		var my_name  = my_rows[i]['name' ];
		var my_value = my_rows[i]['value'];
		if (my_value == null
		||	my_value == ''
		||	my_value == 'null') {
			my_value = my_name;
		}
		var my_selected = (my_name == selected) ? ' selected="selected"' : '';
		if (group_set == 'User Roles'
		||	group_set == 'NFE Folders') {
			my_html += '<option value="' + my_value + '"' + my_selected + '>' + my_name  + '</option>';
		}else{
			my_html += '<option value="' + my_name  + '"' + my_selected + '>' + my_value + '</option>';
		}
	}
//	my_html += '<option onclick="JKY.process_option_search(this)"	class="jky-option-search"	>Search More...</option>';
//	my_html += '<option onclick="JKY.process_option_add_new(this)"	class="jky-option-add-new"	>Add New...</option>';
	return my_html;
}

/**
 * set table options
 */
JKY.set_table_options = function(table, field, selected, initial) {
	JKY.display_trace('set_table_options: ' + table);
	var my_html = '';
	var my_data =
		{ method	: 'get_options'
		, table		:  table
		, field		:  field
		, selected	:  selected
		, initial	:  initial
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
					my_html = '';
					if (initial == null ) {
//						no initial option
					}else{
						if (initial == '' ) {
							my_html += '<option value=""   >' + initial + '</option>';
						}else{
							my_html += '<option value="All">' + initial + '</option>';
						}
					}
					for(var i=0; i<response.rows.length; i+=1) {
						var my_id  	 = response.rows[i]['id' ];
						var my_value = response.rows[i][field];
						var my_selected = (my_id == selected) ? ' selected="selected"' : '';
						my_html += '<option value="' + my_id + '"' + my_selected + '>' + my_value + '</option>';
					}
				}else{
					JKY.display_message(response.message);
				}
			}
		, error		: function(jqXHR, text_status, error_thrown) {
				if (typeof function_error != 'undefined') {
					function_error(jqXHR, text_status, error_thrown);
				}else{
					JKY.hide('jky-loading');
					JKY.display_message('Error from backend server, please re-try later.');
				}
			}
		}
	)
	return my_html;
}

/**
 * process ajax
 *
 * @param	async	(true | false)
 * @param	data	(array)
 * @param	function_success
 * @param	function_error
 */
JKY.ajax = function(async, data, function_success, function_error) {
	var my_object = {};
	my_object.data = JSON.stringify(data);
	JKY.show('jky-loading');
	$.ajax(
		{ url		: JKY.AJAX_URL
		, data		: my_object
		, type		: 'post'
		, dataType	: 'json'
		, async		: async
		, success	: function(response) {
				JKY.hide('jky-loading');
				if (response.status == 'ok') {
					if (typeof function_success != 'undefined') {
						function_success(response);
					}
				}else{
					var my_messages = response.message.split(':');
					if (my_messages.length > 2) {
						var my_words = my_messages[2].trim().split(' ');
						if (my_words[0] == '1062') {
							JKY.display_message('Error, the key of this record is already taken.');
						}else{
							JKY.display_message(response.message);
						}
					}else{
						var my_message = response.message;
						if (my_message.substr(0, 4) == '<br>') {
							my_message = my_message.substr(4);
						}
						JKY.display_message(JKY.t(my_message));
					}
				}
			}
		, error		: function(jqXHR, text_status, error_thrown) {
				JKY.hide('jky-loading');
				if (typeof function_error != 'undefined') {
					function_error(jqXHR, text_status, error_thrown);
				}else{
					JKY.hide('jky-loading');
					JKY.display_message('Error from backend server, please re-try later.');
				}
			}
		}
	);
}

JKY.get_row = function(table_name, id) {
	var my_row = null;
	var my_where = table_name + '.id = ' + id;
	var my_data =
		{ method: 'get_row'
		, table	: table_name
		, where : my_where
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
					my_row = response.row;
				}else{
					JKY.display_message(response.message);
				}
			}
		, error		: function(jqXHR, text_status, error_thrown) {
				if (typeof function_error != 'undefined') {
					function_error(jqXHR, text_status, error_thrown);
				}else{
					JKY.hide('jky-loading');
					JKY.display_message('Error from backend server, please re-try later.');
				}
			}
		}
	);
	return my_row;
}

JKY.get_rows = function(table_name, id) {
	var my_rows = null;
	var my_where = table_name + '.parent_id = ' + id;
	var my_data =
		{ method	: 'get_rows'
		, table		:  table_name
		, where		:  my_where
		, order_by	: 'id'
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
					my_rows = response.rows;
				}else{
					JKY.display_message(response.message);
				}
			}
		, error		: function(jqXHR, text_status, error_thrown) {
				if (typeof function_error != 'undefined') {
					function_error(jqXHR, text_status, error_thrown);
				}else{
					JKY.hide('jky-loading');
					JKY.display_message('Error from backend server, please re-try later.');
				}
			}
		}
	);
	return my_rows;
}

JKY.get_rows_by_where = function(the_table, the_where) {
	var my_rows = null;
	var my_data =
		{ method: 'get_rows'
		, table	:  the_table
		, where :  the_where
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
					my_rows = response.rows;
				}else{
					JKY.display_message(response.message);
				}
			}
		, error		: function(jqXHR, text_status, error_thrown) {
				if (typeof function_error != 'undefined') {
					function_error(jqXHR, text_status, error_thrown);
				}else{
					JKY.hide('jky-loading');
					JKY.display_message('Error from backend server, please re-try later.');
				}
			}
		}
	);
	return my_rows;
}

JKY.get_xml = function(the_file_name) {
	var my_row = null;
	var my_data =
		{ method	: 'get_xml'
		, file_name : the_file_name
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
					my_row = response.xml_nfe;
				}else{
					JKY.display_message(response.message);
				}
			}
		, error		: function(jqXHR, text_status, error_thrown) {
				if (typeof function_error != 'undefined') {
					function_error(jqXHR, text_status, error_thrown);
				}else{
					JKY.hide('jky-loading');
					JKY.display_message('Error from backend server, please re-try later.');
				}
			}
		}
	);
	return my_row;
}

/**
 * process profile
 */
JKY.process_profile = function() {
	JKY.display_trace('process_profile');
	if (JKY.is_loaded('jky-profile')) {
		JKY.display_profile();
	}else{
		$('#jky-new-layer').load('../contacts.html');
		JKY.set_all_events_profile();
		JKY.display_profile();
	}
}

/**
 * process reset
 */
JKY.process_reset = function() {
	JKY.display_trace('process_reset');
	if (JKY.is_loaded('jky-reset')) {
		JKY.display_reset();
	}else{
		$('#jky-new-layer').load('../reset.html');
		JKY.set_all_events_reset();
		JKY.display_reset();
	}
}

/**
 * process log off
 */
JKY.process_log_off = function() {
	JKY.display_trace('process_log_off');
//	JKY.hide('jky-body');
	JKY.set_buttons_menus([]);
	JKY.set_user_info(null);
	var my_data = { method : 'log_out'};
	JKY.ajax(false, my_data, JKY.process_log_off_success);
}

/**
 * process log off success
 */
JKY.process_log_off_success = function() {
//	JKY.process_action('login');
//	JKY.hide('jky-wrapper');
//	window.location = 'home.html';
	JKY.process_action('logoff');
}

/**
 * process export
 */
JKY.run_export = function(table, select, filter, specific, sort_by) {
	if ($('#jky-export').length == 0)	{
		$('body').append('<div id="jky-export"></div>');
	}
	var my_html = ''
		+ '<form id="jky-export-form" action="jky_export.php" method="post">'
		+ '<input type="hidden" name="table"    value="' + table	+ '" />'
		+ '<input type="hidden" name="select"   value="' + select	+ '" />'
		+ '<input type="hidden" name="filter"   value="' + filter	+ '" />'
		+ '<input type="hidden" name="display"  value="' + 1000		+ '" />'
		+ '<input type="hidden" name="specific" value="' + specific	+ '" />'
		+ '<input type="hidden" name="order_by" value="' + sort_by	+ '" />'
		+ '</form>'
		;
	$('#jky-export').html(my_html);
	$('#jky-export-form').submit();
};

JKY.get_user_id = function(the_user_name) {
	var my_id = null;
	var my_data =
		{ method: 'get_user_id'
		, user_name: the_user_name
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
					my_id = response.id;
				}else{
					JKY.display_message(response.message);
				}
			}
		, error		: function(jqXHR, text_status, error_thrown) {
				if (typeof function_error != 'undefined') {
					function_error(jqXHR, text_status, error_thrown);
				}else{
					JKY.display_message('Error from backend server, please re-try later.');
				}
			}
		}
	);
	return my_id;
}

JKY.Xget_product_id = function(the_product_name) {
	var my_id = null;
	var my_data =
		{ method: 'get_product_id'
		, product_name: the_product_name
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
					my_id = response.id;
				}else{
					JKY.display_message(response.message);
				}
			}
		, error		: function(jqXHR, text_status, error_thrown) {
				if (typeof function_error != 'undefined') {
					function_error(jqXHR, text_status, error_thrown);
				}else{
					JKY.display_message('Error from backend server, please re-try later.');
				}
			}
		}
	);
	return my_id;
}

JKY.get_id = function(table, where) {
	var my_id = null;
	var my_data =
		{ method: 'get_id'
		, table	: table
		, where : where
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
					my_id = response.id;
				}else{
					JKY.display_message(response.message);
				}
			}
		, error		: function(jqXHR, text_status, error_thrown) {
				if (typeof function_error != 'undefined') {
					function_error(jqXHR, text_status, error_thrown);
				}else{
					JKY.display_message('Error from backend server, please re-try later.');
				}
			}
		}
	);
	return my_id;
}

JKY.get_value_by_id = function(table, field, id) {
	var my_value = '';
	var my_data =
		{ method: 'get_value'
		, table	: table
		, field	: field
		, where : 'id = ' + id
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
					my_value = response.value;
				}else{
					JKY.display_message(response.message);
				}
			}
		, error		: function(jqXHR, text_status, error_thrown) {
				if (typeof function_error != 'undefined') {
					function_error(jqXHR, text_status, error_thrown);
				}else{
					JKY.display_message('Error from backend server, please re-try later.');
				}
			}
		}
	);
	return my_value;
}

JKY.get_count_by_id = function(table, id) {
	var my_count = 0;
	var my_data =
		{ method: 'get_count'
		, table	: table
		, where : 'parent_id = ' + id
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
					my_count = response.count;
				}else{
					JKY.display_message(response.message);
				}
			}
		, error		: function(jqXHR, text_status, error_thrown) {
				if (typeof function_error != 'undefined') {
					function_error(jqXHR, text_status, error_thrown);
				}else{
					JKY.display_message('Error from backend server, please re-try later.');
				}
			}
		}
	);
	return my_count;
}

JKY.get_count_by_where = function(the_table, the_where) {
	var my_count = 0;
	var my_data =
		{ method: 'get_count'
		, table	:  the_table
		, where :  the_where
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
					my_count = response.count;
				}else{
					JKY.display_message(response.message);
				}
			}
		, error		: function(jqXHR, text_status, error_thrown) {
				if (typeof function_error != 'undefined') {
					function_error(jqXHR, text_status, error_thrown);
				}else{
					JKY.display_message('Error from backend server, please re-try later.');
				}
			}
		}
	);
	return my_count;
}

JKY.get_sum_by_id = function(table, field, id) {
	var my_sum = 0;
	var my_data =
		{ method: 'get_sum'
		, table	: table
		, field	: field
		, where : 'parent_id = ' + id
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
					my_sum = response.sum;
				}else{
					JKY.display_message(response.message);
				}
			}
		, error		: function(jqXHR, text_status, error_thrown) {
				if (typeof function_error != 'undefined') {
					function_error(jqXHR, text_status, error_thrown);
				}else{
					JKY.display_message('Error from backend server, please re-try later.');
				}
			}
		}
	);
	return my_sum;
}

JKY.get_file_name = function(the_full_name) {
	var my_names = the_full_name.split('/');
	var my_length = my_names.length;
	if (my_length > 1 ) {
		return my_names[my_length-1];
	}else{
		return '';
	}
}

JKY.get_file_type = function(the_full_name) {
	var my_names = the_full_name.split('.');
	var my_length = my_names.length;
	if (my_length > 1 ) {
		return my_names[my_length-1];
	}else{
		return '';
	}
}

JKY.get_config_value = function(the_group_set, the_name) {
	var my_where = 'group_set = \'' + the_group_set + '\''
				 + ' AND name = \'' + the_name + '\''
				 ;
	var my_id	 = JKY.get_id('Configs', my_where);
	var my_value = JKY.get_value_by_id('Configs', 'value', my_id);
	return my_value;
}

JKY.get_control_value = function(the_group_set, the_name) {
	var my_where = 'group_set = \'' + the_group_set + '\''
				 + ' AND name = \'' + the_name + '\''
				 ;
	var my_id	 = JKY.get_id('Controls', my_where);
	var my_value = JKY.get_value_by_id('Controls', 'value', my_id);
	return my_value;
}

JKY.play_beep = function() {
	var audio = document.createElement("audio");
	audio.src = "http://erp/img/beep-5.wav";
	audio.play();
}

JKY.get_prev_dom = function(the_id, the_attr) {
	var my_prev_id = the_id;
	var my_line_id = null;
	do {
		my_prev_id = my_prev_id.prev();
		my_line_id = my_prev_id.attr(the_attr);
	} while(my_line_id == null);
	return my_prev_id;
}

JKY.set_decimal = function(the_number, the_decimal) {
	var my_string  = the_number.toString(10, 2);
	var my_numbers = my_string.split('.');
	var my_integer = my_numbers[0];
	var my_decimal = my_numbers[1];
	if (typeof my_decimal == 'undefined')	my_decimal = '00';
	return my_integer + '.' + my_decimal;
}

JKY.var_dump = function(the_name, the_object) {
	var dump_object = function(the_obj) {
		var my_string = '';
		for(var key in the_obj) {
			var my_obj = the_obj[key];
			if (typeof my_obj === 'object')	{
				my_string += dump_object(my_obj);
			}else{
				my_string += key + '=' + my_obj + ', ';
			}
		}
		return my_string;
	}

	var my_output = the_name + '\n';
	for( var i in the_object) {
		var my_object = the_object[i];
		my_output += i + ': ';
		my_output += dump_object(my_object);
		my_output += "\n";
	}
	var my_pre = document.createElement('pre');
	my_pre.innerHTML = my_output;
	document.body.appendChild(my_pre);
}

JKY.enable_delete_button = function() {
	if (JKY.Session.get_value('user_role') == 'Support'
	||  JKY.Session.get_value('user_role') == 'Admin') {
		JKY.enable_button('jky-action-delete');
	}else{
		JKY.hide('jky-action-delete');
	}
}

JKY.disable_delete_button = function() {
	if (JKY.Session.get_value('user_role') == 'Support'
	||  JKY.Session.get_value('user_role') == 'Admin') {
		JKY.disable_button('jky-action-delete');
	}else{
		JKY.hide('jky-action-delete');
	}
}

JKY.in_array = function(the_value, the_array) {
	for(var i=0, max=the_array.length; i<max; i++) {
		if (the_value == the_array[i]) {
			return true;
		}
	}
	return false;
}
