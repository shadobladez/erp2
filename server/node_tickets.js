var express = require('express');
var app_tickets = express();

var Table = require('./../server/Table.js');
var tickets = new Table('Tickets');

app_tickets.filter = function(request, response) {
	var my_where = 'name LIKE "%' + request.query.filter + '%"';
	tickets.get_rows(null, my_where, function(the_rows) {
		response.json(the_rows);
	});
};

app_tickets.get_tickets = function(request, response) {
	var my_where = 'group_set = "' + request.query.group_set + '"';
	tickets.get_rows(null, my_where, function(the_rows) {
		response.json(the_rows);
	});
};

app_tickets.get_ticket = function(request, response) {
	var my_where = 'id = ' + request.query.id;
	tickets.get_row(null, my_where, function(the_row) {
		response.json(the_row);
	});
};

app_tickets.insert = function(request, response) {
	var my_set = ''
		+ '     status = "' + request.body.status		+ '"'
		+ ', group_set = "' + request.body.group_set	+ '"'
		+ ',  sequence =  ' + request.body.sequence
		+ ',      name = "' + request.body.name			+ '"'
		+ ',     value = "' + request.body.value		+ '"'
		;
	tickets.insert(null, my_set, function(the_return_code) {
		response.send(the_return_code);
	});
};
		
app_tickets.update = function(request, response) {
	var my_set = ''
		+ '     status = "' + request.body.status		+ '"'
		+ ',  sequence =  ' + request.body.sequence
		+ ',      name = "' + request.body.name			+ '"'
		+ ',     value = "' + request.body.value		+ '"'
		;
	var my_where = 'id = ' + request.body.id;
	tickets.update(null, my_set, my_where, function(the_return_code) {
		response.send(the_return_code);
	});
};

app_tickets.delete = function(request, response) {
	var my_where = 'id = ' + request.query.id;
	tickets.delete(null, my_where, function(the_return_code) {
		response.send(the_return_code);
	});
};

module.exports = app_tickets;
