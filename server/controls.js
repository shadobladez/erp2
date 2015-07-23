/*
 *	Table controls
 */

var express = require('express');
var Table = require('./Table.js');

var app_controls = express();
var controls = new Table();

var my_table_name = 'Controls';

var get_where = function(the_id) {
	return ' WHERE ' + my_table_name + '.id = ' + the_id; 
}

var get_set = function(the_request) {
	return ' SET status = "' + the_request.status		+ '"'
		+   ', sequence =  ' + the_request.sequence
		+  ', group_set = "' + the_request.group_set	+ '"'
		+       ', name = "' + the_request.name			+ '"'
		+      ', value = "' + the_request.value		+ '"'
		+    ', remarks = "' + the_request.remarks		+ '"'
		;
}

//	----------------------------------------------------------------------------
app_controls.get_rows = function(request, response) {
	var my_select = request.query.select;
	var my_filter = request.query.filter;
	var my_where = '1';
	if (my_select)		{my_where += ' AND group_set = "' + my_select +  '"'};
	if (my_filter)		{my_where += ' AND name LIKE "%'  + my_filter + '%"'};
	var my_sql = ''
		+ 'SELECT *'
		+ '  FROM ' + my_table_name
		+ ' WHERE ' + my_where
		;
	controls.get_rows(my_sql, function(the_rows) {
		response.json(the_rows);
	});
};

app_controls.get_row = function(request, response) {
	var my_sql = ''
		+ 'SELECT *'
		+ '  FROM ' + my_table_name
		+ get_where(request.query.id)
		;
	controls.get_row(my_sql, function(the_row) {
		response.json(the_row);
	});
};

app_controls.insert = function(request, response) {
	var my_sql = ''
		+ 'INSERT ' + my_table_name
		+ get_set(request.body)
		;
	controls.insert(my_sql, function(the_return_code) {
		response.send(the_return_code);
	});
};
		
app_controls.update = function(request, response) {
	var my_sql = ''
		+ 'UPDATE ' + my_table_name
		+ get_set(request.body)
		+ get_where(request.body.id)
		;
	controls.update(my_sql, function(the_return_code) {
		response.send(the_return_code);
	});
};

app_controls.delete = function(request, response) {
	var my_sql = ''
		+ 'DELETE '
		+ '  FROM ' + my_table_name
		+ get_where(request.query.id)
		;
	controls.delete(my_sql, function(the_return_code) {
		response.send(the_return_code);
	});
};

module.exports = app_controls;
