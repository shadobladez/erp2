JKY.verify_user_name = function() {
	JKY.display_trace('verify_user_name');
	var my_user_name = JKY.get_value('jky-user-name');
	var my_error = '';

	if (!JKY.is_empty(my_user_name)) {
		var my_user_id = JKY.get_user_id(my_user_name);
//	JKY.display_message('my_user_id: ' + my_user_id + ', JKY.row.user_id: ' + JKY.row.user_id);
		if (!JKY.is_empty(my_user_id)								//	found user_name
		&& (JKY.row == null || my_user_id != JKY.row.user_id)) {	//	and not the same record
			my_error += JKY.set_already_taken('User Name');
		}
	}

	if (!JKY.is_empty(my_error)) {
		JKY.display_message(my_error);
		JKY.set_focus('jky-user-name', 100);
		return false;
	}else{
		return true;
	}
}

JKY.verify_input = function() {
	JKY.display_trace('verify_input');
	var my_first_name= JKY.get_value('jky-first-name');
	var my_last_name = JKY.get_value('jky-last-name');
	var my_email	 = JKY.get_value('jky-email'	);
	var my_user_name = JKY.get_value('jky-user-name');
	var my_error = '';

	if (JKY.is_empty(my_first_name)) {
		my_error += JKY.set_is_required('First Name');
	}
	if (JKY.is_empty(my_last_name)) {
		my_error += JKY.set_is_required('Last Name');
	}
	if (!JKY.is_empty(my_email) && !JKY.is_email(my_email)) {
		my_error += JKY.set_is_invalid('Email');
	}
	if (!JKY.is_empty(my_user_name)) {
		var my_user_id = JKY.get_user_id(my_user_name);
//	JKY.display_message('my_user_id: ' + my_user_id + ', JKY.row.user_id: ' + JKY.row.user_id);
		if (!JKY.is_empty(my_user_id)								//	found user_name
		&& (JKY.row == null || my_user_id != JKY.row.user_id)) {	//	and not the same record
			my_error += JKY.set_already_taken('User Name');
		}
	}

	if (!JKY.is_empty(my_error)) {
		JKY.display_message(my_error);
		JKY.set_focus('jky-user-name', 100);
		return false;
	}else{
		return true;
	}
}

JKY.insert_user = function(contact_id) {
	var my_first_name= JKY.get_value('jky-first-name');
	var my_last_name = JKY.get_value('jky-last-name');
	var my_user_name = JKY.get_value('jky-user-name');
	var my_user_role = JKY.get_value('jky-user-role');
	if (JKY.is_empty(my_user_name)) {
		return;
	}
	JKY.display_trace('insert_user: ' + contact_id);

	var my_set  =  'contact_id =   ' + contact_id
				+ ', user_name = \'' + my_user_name + '\''
				+ ', user_role = \'' + my_user_role + '\''
				+  ', password = \'' + $.md5(my_first_name + my_last_name) + '\''
				;
	var my_data =
		{ method: 'insert'
		, table : 'JKY_Users'
		, set	: my_set
		};
	JKY.ajax(false, my_data, JKY.insert_user_success);
}

JKY.insert_user_success = function(response) {
	JKY.display_trace('insert_user_success');
//	JKY.display_message(response.message);
}

JKY.update_user = function(contact_id, user_id) {
	var my_user_name = JKY.get_value('jky-user-name');
	var my_user_role = JKY.get_value('jky-user-role');
	if (JKY.is_empty(my_user_name)) {
		return;
	}
	if (JKY.is_empty(user_id)) {
		JKY.insert_user(contact_id);
		user_id = JKY.get_user_id(my_user_name);
	}
	JKY.display_trace('update_user: ' + contact_id + ' : ' + user_id);

	var my_set  =  'contact_id =   ' + contact_id
				+ ', user_name = \'' + my_user_name + '\''
				+ ', user_role = \'' + my_user_role + '\''
				;
	var my_where = 'id = ' + user_id;
	var my_data =
		{ method: 'update'
		, table : 'JKY_Users'
		, set	: my_set
		, where : my_where
		};
	JKY.ajax(false, my_data, JKY.update_user_success);
}

JKY.update_user_success = function(response) {
	JKY.display_trace('update_user_success');
//	JKY.display_message(response.message);
}

JKY.delete_user = function(contact_id, user_id) {
	var my_user_name = JKY.get_value('jky-user-name');
	if (JKY.is_empty(my_user_name)) {
		return;
	}

	JKY.display_trace('delete_user: ' + contact_id + ' : ' + user_id);

	var my_where = 'id = ' + user_id;
	var my_data =
		{ method: 'delete'
		, table : 'JKY_Users'
		, where : my_where
		};
	JKY.ajax(false, my_data, JKY.delete_user_success);
}

JKY.delete_user_success = function(response) {
	JKY.display_trace('delete_user_success');
//	JKY.display_message(response.message);
}

JKY.reset_password = function() {
	JKY.display_trace('JKY.reset_password');
	var my_first_name= JKY.get_value('jky-first-name');
	var my_last_name = JKY.get_value('jky-last-name');
	var my_user_name = JKY.get_value('jky-user-name');

	if (JKY.is_empty(my_user_name)) {
		return;
	}

	var my_error = '';
	if (JKY.is_empty(my_first_name)) {
		my_error += JKY.set_is_required('First Name');
	}
	if (JKY.is_empty(my_last_name)) {
		my_error += JKY.set_is_required('Last Name');
	}

	if (!JKY.is_empty(my_error)) {
		JKY.display_message(my_error);
		JKY.set_focus('jky-first-name', 100);
		return false;
	}

	var my_set = 'password = \'' + $.md5(my_first_name + my_last_name) + '\'';
	var my_where = 'id = ' + JKY.row.user_id;
	var my_data =
		{ method: 'update'
		, table : 'JKY_Users'
		, set	: my_set
		, where : my_where
		};
	JKY.ajax(false, my_data, function(the_response) {
		JKY.display_message(the_response.message);
	});
}

JKY.reset_user_success = function(response) {
	JKY.display_trace('reset_user_success');
}

JKY.save_address = function() {
//	JKY.display_message('JKY.save_address');
	var my_set  =     'street1 = \'' + JKY.get_value('jky-street1'	) + '\''
				+ ', st_number = \'' + JKY.get_value('jky-st-number') + '\''
				+    ', st_cpl = \'' + JKY.get_value('jky-st-cpl'	) + '\''
				+   ', street2 = \'' + JKY.get_value('jky-street2'	) + '\''
				+      ', city = \'' + JKY.get_value('jky-city'		) + '\''
				+     ', state = \'' + JKY.get_value('jky-state'	) + '\''
				+       ', zip = \'' + JKY.get_value('jky-zip'		) + '\''
				+   ', country = \'' + JKY.get_value('jky-country'	) + '\''
				+  ', district = \'' + JKY.get_value('jky-district'	) + '\''
				;
	var my_where = 'id = ' + JKY.row.id;
	var my_data =
		{ method: 'update'
		, table : 'Contacts'
		, set	: my_set
		, where : my_where
		};
	JKY.ajax(true, my_data, JKY.save_address_success);
}

JKY.save_address_success = function(response) {
//	JKY.display_trace('save_address_success');
	JKY.display_message('Address saved, ' + response.message);
	JKY.row = JKY.get_row('Contacts', JKY.row.id);
}