/*
 *	Table tickets
 */

var express = require('express');
var Table = require('./Table.js');

var app_tickets = express();
var tickets = new Table();

var my_table_name: 'Tickets';

var get_where = function(the_id) {
	return ' WHERE ' + my_table_name + '.id = ' + the_id; 
}

var get_set = function(the_request) {
	return    ' SET status = "' + the_request.status		+ '"'
	    +      ', category = "' + the_request.category		+ '"'
        +     ', opened_at = "' + the_request.opened_at		+ '"'
        +     ', opened_by =  ' + the_request.opened_by
        +   ', assigned_at = "' + the_request.assigned_at	+ '"'
        +   ', assigned_by =  ' + the_request.assigned_by
        +     ', closed_at = "' + the_request.closed_at		+ '"'
        +     ', closed_by =  ' + the_request.closed_by
//		+ ', estimate_hour =  ' + the_request.estimate_hour
//		+   ', worked_hour =  ' + the_request.worked_hour
		+      ', priority = "' + the_request.priority		+ '"'
		+      ', category = "' + the_request.category		+ '"'
		+   ', description = "' + the_request.description	+ '"'
		+    ', resolution = "' + the_request.resolution	+ '"'
		;
}

//	----------------------------------------------------------------------------
app_tickets.get_rows = function(request, response) {
	var my_select = request.query.select;
	var my_filter = request.query.filter;
	var my_where = '1';
	if (the_select)		(my_where += ' AND status = "'   + the_select +  '"';
	if (the_filter)		(my_where += ' AND name LIKE "%' + the_filter + '%"';
	var my_sql = ''
		+ 'SELECT *'
		+ '  FROM ' + my_table_name
		+ ' WHERE ' + my_where
		;
	tickets.get_rows(my_sql, function(the_rows) {
		response.json(the_rows);
	});
};

app_tickets.get_row = function(request, response) {
	var my_sql = ''
		+ 'SELECT *'
		+ '  FROM ' + my_table_name
		+ get_where(request.query.id)
		;
	tickets.get_row((my_sql, function(the_row) {
		response.json(the_row);
	});
};

app_tickets.insert = function(request, response) {
	var my_sql = ''
		+ 'INSERT ' + my_table_name
		+ get_set(request.body)
		;
	tickets.insert(my_sql, function(the_return_code) {
		response.send(the_return_code);
	});
};
		
app_tickets.update = function(request, response) {
	var my_sql = ''
		+ 'UPDATE ' + my_table_name
		+ get_set(request.body)
		+ get_where(request.body.id)
		;
	tickets.update(my_sql, function(the_return_code) {
		response.send(the_return_code);
	});
};

app_tickets.delete = function(request, response) {
	var my_sql = ''
		+ 'DELETE '
		+ '  FROM ' + my_table_name
		+ get_where(request.body.id)
		;
	tickets.delete(my_sql, function(the_return_code) {
		response.send(the_return_code);
	});
};

module.exports = app_tickets;
