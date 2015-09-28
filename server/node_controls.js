var express = require('express');
var app_controls = express();

var Table = require('./../server/Table.js');
var controls = new Table('Controls');

app_controls.filter = function(request, response) {
	var my_where = 'name LIKE "%' + request.query.filter + '%"';
	controls.get_rows(null, my_where, function(the_rows) {
		response.json(the_rows);
	});
};

app_controls.get_rows = function(request, response) {
	var my_where = 'group_set = "' + request.query.group_set + '"';
	controls.get_rows(null, my_where, function(the_rows) {
		response.json(the_rows);
	});
};

app_controls.get_row = function(request, response) {
	var my_where = 'id = ' + request.query.id;
	controls.get_row(null, my_where, function(the_row) {
		response.json(the_row);
	});
};

app_controls.insert = function(request, response) {
	var my_set = ''
		+ '     status = "' + request.body.status		+ '"'
		+ ', group_set = "' + request.body.group_set	+ '"'
		+ ',  sequence =  ' + request.body.sequence
		+ ',      name = "' + request.body.name			+ '"'
		+ ',     value = "' + request.body.value		+ '"'
		;
	controls.insert(null, my_set, function(the_return_code) {
		response.send(the_return_code);
	});
};
		
app_controls.update = function(request, response) {
	var my_set = ''
		+ '     status = "' + request.body.status		+ '"'
		+ ',  sequence =  ' + request.body.sequence
		+ ',      name = "' + request.body.name			+ '"'
		+ ',     value = "' + request.body.value		+ '"'
		;
	var my_where = 'id = ' + request.body.id;
	controls.update(null, my_set, my_where, function(the_return_code) {
		response.send(the_return_code);
	});
};

app_controls.delete = function(request, response) {
	var my_where = 'id = ' + request.query.id;
	controls.delete(null, my_where, function(the_return_code) {
		response.send(the_return_code);
	});
};

module.exports = app_controls;
