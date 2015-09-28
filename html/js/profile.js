"use strict";

/**
 * profile.html
 */
var jky_program		= 'Profile';
var jky_focus		= 'jky-profile-user-name';

/**
 *	set all events (run only once per load)
 */
JKY.set_all_events_profile = function() {
	JKY.display_trace('set_all_events - ' + jky_program);
	if (JKY.is_loaded('jky-profile')) {
//		$('#jky-profile-user-name'	).change(function() {JKY.change_user_name		(this)	;});
//		$('#jky-confirm-password'	).change(function() {JKY.set_profile_save		(this)	;});
		$('#jky-profile-save'		).click (function() {JKY.process_profile_save	()		;});
		$('#jky-new-layer input[id]').each(function() {
			$(this).change(function() {JKY.process_change_input	(this);});
		});
		$('#jky-new-layer select[id]').each(function() {
			$(this).change(function() {JKY.process_change_input	(this);});
		});
	}else{
		setTimeout(function() {JKY.set_all_events_profile();}, 100);
	}
}

/**
 *	set initial values (run only once per load)
 */
JKY.set_initial_values_profile = function() {
	JKY.display_trace('set_initial_values - ' + jky_program);
	if (JKY.is_loaded('jky-profile')) {
		var my_user_name = JKY.Session.get_value('user_name' );
		var my_contact_id= JKY.Session.get_value('contact_id');
		var my_user = JKY.get_row('Contacts', my_contact_id);
		JKY.set_value('jky-profile-user-name'	, my_user_name		);
		JKY.set_value('jky-profile-first-name'	, my_user.first_name);
		JKY.set_value('jky-profile-last-name'	, my_user.last_name	);
		JKY.set_value('jky-profile-email'		, my_user.email		);
		JKY.t_tag	 ('jky-profile', 'span');
		JKY.t_input	 ('jky-profile', 'placeholder');
		JKY.set_focus(jky_focus);
	}else{
		setTimeout(function() {JKY.set_initial_values_profile();}, 100);
	}
}

/**
 * display profile
 */
JKY.display_profile = function() {
	JKY.display_trace('display_profile');
	if (JKY.is_loaded ('jky-profile')) {
		JKY.set_initial_values_profile();
		JKY.show_modal('jky-profile');
	}else{
		setTimeout(function() {JKY.display_profile();}, 100);
	}
}

/**
 * process change input
 */
JKY.process_change_input = function(the_id) {
	var my_id = $(the_id).attr('id');
	JKY.display_trace('process_change_input: ' + my_id);
	JKY.Changes.increment();
	JKY.set_profile_save();
	JKY.is_invalid(my_id);
}

JKY.is_invalid = function(the_id) {
	var my_error = '';
	if (the_id == null || the_id == 'jky-profile-user-name') {
		var my_user_name = JKY.get_value('jky-profile-user-name');
		if (JKY.is_empty(my_user_name)) {
			my_error += JKY.set_is_required('User Name');
		}
		var my_id = JKY.get_user_id(my_user_name);
		if (!JKY.is_empty(my_id) && my_id != JKY.Session.get_value('user_id')) {
			my_error += JKY.set_already_taken('User Name');
		}
	}
	if (the_id == null || the_id == 'jky-profile-first-name') {
		var my_first_name = JKY.get_value('jky-profile-first-name');
		if (JKY.is_empty(my_first_name)) {
			my_error += JKY.set_is_required('First Name');
		}
	}
	if (the_id == null || the_id == 'jky-profile-last-name') {
		var my_last_name = JKY.get_value('jky-profile-last-name');
		if (JKY.is_empty(my_last_name)) {
			my_error += JKY.set_is_required('Last Name');
		}
	}
	if (JKY.is_empty(my_error)) {
		return false;
	}else{
		JKY.display_message(my_error);
		return true;
	}
}

JKY.Xchange_user_name = function(the_id) {
	var my_user_name = $('#jky-profile-user-name').val();
	JKY.display_trace('change_user_name: ' + my_user_name);
	if (JKY.is_empty(my_user_name)) {
		JKY.display_message(JKY.set_is_required('User Name'));
		return false;
	}
	var my_data =
		{ method: 'get_user_id'
		, user_name: my_user_name
		};
	JKY.ajax(false, my_data, JKY.Xprocess_user_name);
}

JKY.Xprocess_user_name = function(the_response) {
	var my_user_id = JKY.Session.get_value('user_id');
	var new_id = the_response.id;
	if (new_id && new_id != my_user_id) {
		JKY.display_message(JKY.set_already_taken('User Name'));
		return false;
	}
	JKY.set_profile_save();
}

JKY.set_profile_save = function() {
	JKY.display_trace('set_profile_save');
	var my_user_name 	= $('#jky-profile-user-name'	).val();
	var my_first_name 	= $('#jky-profile-first-name'	).val();
	var my_last_name 	= $('#jky-profile-last-name'	).val();
	var my_email 		= $('#jky-profile-email'		).val();
	var my_current		= $('#jky-current-password'		).val();
	var my_new_password	= $('#jky-new-password'			).val();
	var my_confirm		= $('#jky-confirm-password'		).val();

	var my_error = '';
	if (JKY.is_empty(my_user_name	)) 	{my_error += JKY.set_is_required('User Name'		);}
	if (JKY.is_empty(my_first_name	)) 	{my_error += JKY.set_is_required('First Name'		);}
	if (JKY.is_empty(my_last_name	)) 	{my_error += JKY.set_is_required('Last Name'		);}
//	if (JKY.is_empty(my_email		)) 	{my_error += JKY.set_is_required('Email'			);}

	if (!JKY.is_empty(my_current		)
	||	!JKY.is_empty(my_new_password	)
	||  !JKY.is_empty(my_confirm		)) {
		if (JKY.is_empty(my_current		)) 	{my_error += JKY.set_is_required('Current Password'	);}
		if (JKY.is_empty(my_new_password)) 	{my_error += JKY.set_is_required(    'New Password'	);}
		if (JKY.is_empty(my_confirm		)) 	{my_error += JKY.set_is_required('Confirm Password'	);}
	}

	if (my_error == '') {
		if (my_new_password != my_confirm)	{my_error += '<br>' + JKY.t('Confirm Password does not match New Password');}
	}

	if (my_error == '') {
		JKY. enabled_id('jky-profile-save');
	}else{
		JKY.disabled_id('jky-profile-save');
//		JKY.display_message(my_error);
	}
}

JKY.process_profile_save = function() {
	if (JKY.is_invalid(null)) {
		return;
	}

	JKY.display_trace('process_profile_save');
	if (JKY.is_disabled('jky-profile-save')) {
		JKY.display_message('Please, fill in all information');
		JKY.set_focus(jky_focus);
		return;
	}
	var my_user_name 	= $('#jky-profile-user-name'	).val();
	var my_first_name 	= $('#jky-profile-first-name'	).val();
	var my_last_name 	= $('#jky-profile-last-name'	).val();
	var my_email 		= $('#jky-profile-email'		).val();
	var my_current		= $('#jky-current-password'		).val();
	var my_password		= $('#jky-new-password'			).val();

	var my_data =
		{ method		: 'profile'
		, user_name		:  my_user_name
		, first_name	:  my_first_name
		, last_name		:  my_last_name
		, email			:  my_email
		, current		: $.md5(my_current)
		, password		: $.md5(my_password)
		};
	JKY.ajax(false, my_data, JKY.process_profile_save_success);
	JKY.set_focus(jky_focus);
	JKY.Changes.reset();
}

JKY.process_profile_save_success = function(response) {
	JKY.display_trace('process_profile_save_success');
	JKY.Session.load_values();
	JKY.hide_modal('jky-profile');
	JKY.display_message(response.message);
}
